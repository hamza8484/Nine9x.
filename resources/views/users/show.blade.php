@extends('layouts.master')

@section('title')
{{ __('home.MainPage41') }}
@stop

@section('css')
<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<!-- Font Awesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- تحسينات تنسيق إضافية -->
<style>
    .card {
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 10px;
        background-color: #fff;
    }

    .card h2 {
        font-size: 22px;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }

    .badge {
        font-size: 14px;
        padding: 6px 12px;
    }

    .table th {
        background-color: #f8f9fa;
        color: #333;
    }

    .btn i {
        margin-left: 5px;
    }

    .card .btn {
        min-width: 160px;
    }

    .alert {
        font-size: 15px;
    }

    .content-title {
        font-weight: 600;
        color: #444;
    }

    .breadcrumb-header {
        margin-bottom: 30px;
    }

    /* تأثير الرمش */
    @keyframes blink {
        0% {
            opacity: 1;
        }
        50% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    .blinking {
        color: green;
        animation: blink 1s infinite;
    }
</style>
@endsection

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Accounts') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.AccountDetail') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')



<div class="container mt-5">

    <!-- معلومات المستخدم -->
    <div class="card p-4 mb-4">
        <h2><i class="fas fa-user-circle text-primary"></i> {{ __('home.AccountInformation') }}</h2>
        <h5>{{ __('home.Name') }}: {{ $user->name }}</h5>
        <h5>{{ __('home.Email') }}: {{ $user->email }}</h5>
             <h5>{{ __('home.Status') }}:
                @if($user->Status == 'مفعل')
                    <span class="badge badge-success">{{ __('home.Enabled') }}</span>
                @else
                    <span class="badge badge-danger">{{ __('home.Disabled') }}</span>
                @endif
            </h5>

         <!-- زر فتح المودال لتغيير كلمة المرور -->
         <div class="mt-4">
            <button class="btn btn-primary" data-toggle="modal" data-target="#changePasswordModal"><i class="fas fa-sync-alt"></i> {{ __('home.ResetPassword') }}</button>
        </div>
    </div>

    <!-- معلومات الاشتراك -->
    <div class="card p-4 mb-4">
        <h2><i class="fas fa-file-contract text-primary"></i> {{ __('home.SubscriptionInformation') }}</h2>

        @if($user->subscription)
            <h5>{{ __('home.Plan') }}: {{ $user->subscription->plan->name ?? 'غير محدد' }}</h5>
            <h5>{{ __('home.StartDate') }}: {{ \Carbon\Carbon::parse($user->subscription->start_date)->format('Y-m-d') }}</h5>
            <h5>{{ __('home.EndDate') }}: {{ \Carbon\Carbon::parse($user->subscription->end_date)->format('Y-m-d') }}</h5>
            <h5>{{ __('home.Status') }}: 
                <span id="subscription-status" class="badge {{ $user->subscription->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                    {{ $user->subscription->status == 'active' ? __('home.Active') : __('home.Inactive') }}
                </span>
            </h5>

            <div class="mt-4">
                @can('')
                <button id="renew-subscription" class="btn btn-success"><i class="fas fa-sync-alt"></i> تجديد الاشتراك</button>
                @endcan()
                @can('')
                <button id="cancel-subscription" class="btn btn-danger"><i class="fas fa-times-circle"></i> إلغاء الاشتراك</button>
                @endcan()
            </div>
        @else
        <div class="alert alert-warning mt-3">
            {{ __('home.NoActiveSubscription') }}
        </div>

        @endif
    </div>

    <!-- المودال لتغيير كلمة المرور -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">{{ __('home.ChangePasswordTitle') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('home.Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.change-password') }}" method="POST">
                        @csrf
                        <input type="password" name="fake_password" style="display:none">

                        <div class="form-group">
                            <label for="current_password">{{ __('home.CurrentPassword') }}</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required autocomplete="off" placeholder="{{ __('home.EnterCurrentPassword') }}">
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('home.NewPassword') }}</label>
                            <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password" placeholder="{{ __('home.EnterNewPassword') }}">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">{{ __('home.ConfirmNewPassword') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="{{ __('home.ReenterNewPassword') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ __('home.SubmitChangePassword') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- سجل عمليات الدفع -->
    <h2><i class="fas fa-credit-card text-primary"></i> {{ __('home.PaymentHistory') }}</h2>

    @if($user->payments && $user->payments->count() > 0)
        <table class="table table-hover table-striped text-center align-middle">
            <thead>
                <tr>
                    <th>{{ __('home.Serial') }}</th>
                    <th>{{ __('home.Plan') }}</th>
                    <th>{{ __('home.Amount') }}</th>
                    <th>{{ __('home.PaymentMethod') }}</th>
                    <th>{{ __('home.Status') }}</th>
                    <th>{{ __('home.PaymentDate') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payment->plan->name ?? '---' }}</td>
                        <td class="blinking">{{ $payment->amount }} {{ __('home.SAR') }}</td>
                        <td>{{ $payment->payment_method == 'credit_card' ? __('home.CreditCard') : $payment->payment_method }}</td>
                        <td>
                            @if($payment->payment_status == 'active')
                                <span class="badge badge-success">{{ __('home.Success') }}</span>
                            @else
                                <span class="badge badge-danger">{{ __('home.Failed') }}</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info mt-3">
            {{ __('home.NoPayments') }}
        </div>
    @endif


</div>

@endsection

@section('js')
<!-- jQuery & Toastr JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function(){

        // عرض رسالة خطأ أو نجاح باستخدام toastr عند وجود رسالة من السيرفر
        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif
        
        // التحقق من تطابق كلمة المرور الجديدة مع تأكيد كلمة المرور عند إرسال النموذج
        $("form").submit(function(event) {
            var password = $("#password").val();
            var passwordConfirmation = $("#password_confirmation").val();

            if (password !== passwordConfirmation) {
                toastr.error('الكلمة لا تطابق كلمة المرور الجديدة');
                event.preventDefault();  // منع إرسال النموذج
            }
        });

        // Ajax لتجديد الاشتراك
        $('#renew-subscription').click(function() {
            $.ajax({
                url: "{{ route('subscription.renew') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success('تم تجديد الاشتراك بنجاح ✅');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    toastr.error('حدث خطأ أثناء تجديد الاشتراك ❌');
                }
            });
        });

        // Ajax لإلغاء الاشتراك
        $('#cancel-subscription').click(function() {
            if(confirm('هل أنت متأكد أنك تريد إلغاء الاشتراك؟')) {
                $.ajax({
                    url: "{{ route('subscription.cancel') }}",
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success('تم إلغاء الاشتراك بنجاح ✅');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        toastr.error('حدث خطأ أثناء إلغاء الاشتراك ❌');
                    }
                });
            }
        });

    });
</script>
@endsection
