@extends('layouts.master')

@section('title')
    كشف حساب عميل - ناينوكس
@stop

<head>
    <!-- إضافة خط تاجوال -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #ffffff; /* تغيير خلفية الصفحة إلى اللون الأبيض */
        }

        /* تحسين تنسيق الجدول */
        table {
            font-size: 14px;
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            text-align: center; /* توسيط النص أفقيًا */
            vertical-align: middle; /* توسيط النص عموديًا */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .container {
            margin-top: 30px;
        }

        .balance-info {
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* تخصيص النصوص بشكل جيد */
        p {
            text-align: center;
            font-size: 16px;
            margin-bottom: 30px;
        }

        /* إضافة بعض الظلال والحدود للجداول */
        table {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        /* تعديل النصوص ليكون من اليمين لليسار */
        .table th, .table td {
            direction: rtl;
        }

        /* تنسيق الفورم داخل المربع المنسق */
        .card {
            border-radius: 15px; /* حواف منحنية للمربع */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* إضافة ظل للمربع */
            padding: 20px;
            margin-top: 30px;
        }

        .btn-custom {
			background-color: #28a745; /* اللون الأخضر */
			color: white; /* النص باللون الأبيض */
			border-radius: 5px;
		}

		.btn-custom:hover {
			background-color: #218838; /* تغيير اللون عند المرور بالماوس */
		}

    </style>
</head>

@section('content')
    <div class="container" dir="rtl">
        <div class="card">
            <h1>كشف حساب للعميل: {{ $customer->cus_name }}</h1>

            <div class="balance-info">
                <h2>الرصيد الحالي: <strong>{{ $customer->cus_balance }} </strong> - ريال سعودي</h2>
            </div>

            <!-- جدول الحركات -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>تسلسل</th>
                        <th>التاريخ</th>
                        <th>المبلغ</th>
                        <th>طريقة الدفع</th>
                        <th>الملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->date }}</td>
                            <td>{{ $transaction->amount }}</td>
                            <td>{{ $transaction->payment_method }}</td>
                            <td>{{ $transaction->notes }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

          
            <!-- أزرار الطباعة و زر الرجوع -->
            <div class="d-flex justify-content-between mb-4">
                <!-- زر الطباعة -->
                <button class="btn btn-print" onclick="window.print()">طباعة</button>

                <!-- زر الرجوع إلى صفحة العميل -->
                <a href="{{ route('customers.index') }}" class="btn btn-back">رجوع إلى قائمة العملاء</a>
            </div>

            <style>
                .btn-print {
                    background-color: #007bff;
                    color: white;
                }

                .btn-back {
                    background-color: #dc3545;
                    color: white;
                }
            </style>


        </div>
    </div>
@endsection
