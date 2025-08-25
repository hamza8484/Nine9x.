@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* تخصيص البطاقات */
    .stat-card {
        border-radius: 15px;
        padding: 25px;
        color: #fff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    .bg-users { background: #007bff; }
    .bg-active { background: #28a745; }
    .bg-revenue { background: #ffc107; color: #000; }
    .bg-expiring { background: #dc3545; }
    
    /* تخصيص الجدول */
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }
    .badge {
        padding: 5px 10px;
        border-radius: 10px;
    }
    .badge-danger { background-color: #dc3545; }
    .badge-warning { background-color: #ffc107; }
    .badge-success { background-color: #28a745; }

    /* تخصيص التنبيهات */
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
    }

    .alert-warning h5 {
        font-weight: bold;
    }
</style>
@endsection

@section('title')
 شاشة إحصاء الإشتراكات - ناينوكس
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0 my-auto">لوحة التحكم</h4>
        <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إحصائيات النظام</span>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <!-- عدد المستخدمين -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="stat-card bg-users text-center">
            <div class="stat-icon mb-2"><i class="fas fa-users"></i></div>
            <h5>إجمالي المستخدمين</h5>
            <h3>{{ $totalUsers }}</h3>
        </div>
    </div>

    <!-- الاشتراكات النشطة -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="stat-card bg-active text-center">
            <div class="stat-icon mb-2"><i class="fas fa-check-circle"></i></div>
            <h5>الاشتراكات النشطة</h5>
            <h3>{{ $activeSubscriptions }}</h3>
        </div>
    </div>

    <!-- الإيرادات -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="stat-card bg-revenue text-center">
            <div class="stat-icon mb-2"><i class="fas fa-coins"></i></div>
            <h5>إجمالي الإيرادات</h5>
            <h3>{{ number_format($totalRevenue, 2) }} ريال</h3>
        </div>
    </div>

    <!-- اشتراكات تنتهي قريبًا -->
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="stat-card bg-expiring text-center">
            <div class="stat-icon mb-2"><i class="fas fa-exclamation-triangle"></i></div>
            <h5>تنتهي خلال 7 أيام</h5>
            <h3>{{ $expiringSoon }}</h3>
        </div>
    </div>
</div>

<!-- تنبيهات الاشتراكات -->
<div class="alert alert-warning mt-4">
    <h5>تنبيهات الاشتراك:</h5>
    @foreach($expiringSubscriptions as $subscription)
        <p>الاشتراك للمستخدم <strong>{{ $subscription->user->name }}</strong> سينتهي في {{ \Carbon\Carbon::parse($subscription->end_date)->diffForHumans() }}.</p>
    @endforeach
</div>

<!-- جدول آخر الاشتراكات -->
<div class="card mt-5">
    <div class="card-header">
        <h5 class="mb-0">تفاصيل آخر الاشتراكات</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>المستخدم</th>
                    <th>الخطة</th>
                    <th>بداية الاشتراك</th>
                    <th>نهاية الاشتراك</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentSubscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->user->name ?? '-' }}</td>
                        <td>{{ $subscription->plan->name ?? '-' }}</td>
                        <td>{{ $subscription->start_date }}</td>
                        <td>{{ $subscription->end_date }}</td>
                        <td>
                            @if ($subscription->trashed())
                                <span class="badge badge-danger">محذوف</span>
                            @elseif (now()->gt($subscription->end_date))
                                <span class="badge badge-warning text-dark">منتهي</span>
                            @else
                                <span class="badge badge-success">نشط</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- الرسوم البيانية -->
<div class="card mt-5">
    <div class="card-header">
        <h5 class="mb-0">الإيرادات حسب الشهر (آخر 6 شهور)</h5>
    </div>
    <div class="card-body">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

<div class="card mt-5">
    <div class="card-header"><h5>اشتراكات جديدة مقابل تجديدات (آخر 30 يوم)</h5></div>
    <div class="card-body">
        <canvas id="renewalChart" height="100"></canvas>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // الرسم البياني للإيرادات
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',  // رسم بياني خطي
        data: {
            labels: {!! json_encode($months) !!}, // الأشهر
            datasets: [{
                label: 'الإيرادات الشهرية (ريال)', // التسمية على الرسم البياني
                data: {!! json_encode($revenues) !!}, // البيانات (الإيرادات)
                backgroundColor: 'rgba(0, 123, 255, 0.3)', // لون خلفية المنطقة
                borderColor: '#007bff', // لون الحدود
                borderWidth: 2,
                fill: true,  // تعبئة المنطقة تحت الخط
                tension: 0.4, // انحناء الخط
                pointBackgroundColor: '#fff', // لون النقاط على الخط
                pointBorderColor: '#007bff', // حدود النقاط
                pointRadius: 5, // حجم النقاط
            }]
        },
        
        options: {
            responsive: true,
            scales: {
                x: {
                    title: { // إضافة عنوان للمحور X
                        display: true,
                        text: 'الشهر'
                    },
                    grid: {
                        display: false, // إخفاء شبكة المحور X
                    },
                    ticks: {
                        autoSkip: false, // عدم تخطي الأشهر
                        maxRotation: 0, // عدم تدوير النصوص
                        minRotation: 0,  // عدم تدوير النصوص
                        font: {
                            size: 14, // حجم الخط
                        },
                        padding: 10, // المسافة بين النص والمحور
                    },
                },
                y: {
                    beginAtZero: true,
                    title: { // إضافة عنوان للمحور Y
                        display: true,
                        text: 'الإيرادات (ريال)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' ريال'; // إضافة وحدة الريال
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false, // إخفاء الأسطورة
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.raw + ' ريال'; // إضافة الريال في التلميحات
                        }
                    }
                }
            },
            interaction: {
                mode: 'index', // التفاعل مع جميع النقاط
                intersect: false,
            }
        }
    });

    // الرسم البياني للتجديدات
    const renewalCtx = document.getElementById('renewalChart').getContext('2d');
    new Chart(renewalCtx, {
        type: 'bar',
        data: {
            labels: ['اشتراكات جديدة', 'تجديدات'],
            datasets: [{
                label: 'عدد العمليات',
                data: [{{ $newSubscriptions }}, {{ $renewals }}],
                backgroundColor: ['#007bff', '#ffc107']
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
</script>
@endsection
