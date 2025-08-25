@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('title')
    {{ __('home.MainPage6') }}
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Program Setting') }} </h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.transactions') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@foreach (['Add', 'Update', 'Delete'] as $msg)
    @if(session($msg))
        <div class="alert alert-{{ 
            $msg == 'Add' ? 'info' : 
            ($msg == 'Update' ? 'warning' : 'danger') 
        }} alert-dismissible fade show" role="alert">
            <strong>{{ session($msg) }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach
<script>
    // إخفاء الرسائل بعد 10 ثواني
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 10000);
</script>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <a class="modal-effect btn btn-outline-primary btn-block" 
                       data-effect="effect-scale" data-toggle="modal" 
                       href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;">
                        {{ __('home.Add transactions') }}
                    </a>
                    <!-- زر الرجوع إلى الخزنة -->
                    <a href="{{ route('cashboxes.index', ['cashbox' => $cashbox_id]) }}" class="btn btn-secondary mb-3">
                        {{ __('home.Back To Cashboxes') }}
                    </a>
                    <!-- زر الطباعة -->
                    <button class="btn btn-outline-success mb-3" onclick="printTable()">
                        {{ __('home.Print1') }}
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- Display Success/Failure Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table text-md-nowrap" id="example1" data-page-length="50">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 4%;">{{ __('home.No.') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('home.transactionsName') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('home.transactionsType') }}</th>
                                <th class="text-center" style="width: 7%;">{{ __('home.Palance') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('home.CurrentBalance') }}</th>
                                <th class="text-center" style="width: 7%;">{{ __('home.UsreName') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('home.Notes') }}</th>
                                <th class="text-center" style="width: 10%;">{{ __('home.transactionsDate') }}</th>
                                <th class="text-center no-print" style="width: 10%;">{{ __('home.Events') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr id="transaction-{{ $transaction->id }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        {{ $transaction->cashbox ? $transaction->cashbox->cash_name : __('home.not_defined') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $transaction->type == 'deposit' ? __('home.deposit') : __('home.withdrawal') }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($transaction->cash_amount, 2) }} {{ __('home.currency') }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($transaction->running_balance, 2) }} {{ __('home.currency') }}
                                    </td>
                                    <td class="text-center">{{ $transaction->user->name }}</td>
                                    <td class="text-center">{{ $transaction->cash_description ?? __('home.no_notes') }}</td>
                                    <td class="text-center">{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                                    <td class="text-center no-print">
                                        <!-- زر الطباعة لكل عملية -->
                                            <button class="btn btn-outline-info btn-sm no-print" onclick="printTransaction({{ $transaction->id }})">
                                                {{ __('home.Print') }}
                                            </button>
                                        </td>

                                </tr>
                            @endforeach

                        </tbody>
                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Transaction Modal -->
<div class="modal" id="modaldemo8">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="cashbox_id" value="{{ $cashbox_id }}">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.Add transactions') }}</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="type">{{ __('home.transactionsType') }}</label>
                    <select name="type" class="form-control" required>
                        <option value="deposit">{{ __('home.deposit') }}</option>
                        <option value="withdrawal">{{ __('home.withdrawal') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cash_amount">{{ __('home.Palance') }}</label>
                    <input type="number" name="cash_amount" class="form-control" required min="0.01" step="0.01">
                </div>
                <div class="form-group">
                    <label for="cash_description">{{ __('home.Notes') }}</label>
                    <textarea name="cash_description" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">{{ __('home.Closed') }}</button>
                <button class="btn btn-primary" type="submit">{{ __('home.Add transactions') }}</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>





<!-- طباعة الكل -->
    <script>
        // دالة الطباعة لجدول المعاملات
        function printTable() {
            var table = document.getElementById('example1');
            var printWindow = window.open('', '', 'height=800,width=800');
            var userName = "{{ auth()->user()->name }}";
            var printDate = new Date().toLocaleString();

            // إضافة تنسيق خاص بالطباعة
            printWindow.document.write('<html><head><title>طباعة جدول المعاملات</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; direction: rtl; text-align: right; }');
            printWindow.document.write('h3 { text-align: center; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
            printWindow.document.write('th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: center; font-size: 12px; }'); // تم تصغير الخط هنا
            printWindow.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
            printWindow.document.write('footer { margin-top: 30px; text-align: center; font-size: 12px; }');
            printWindow.document.write('.date-print { text-align: right; font-size: 14px; margin-bottom: 10px; }');
            printWindow.document.write('.no-print { display: none; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');

            // إضافة تاريخ الطباعة في أعلى الصفحة
            printWindow.document.write('<div class="date-print"><strong>تاريخ الطباعة: </strong>' + printDate + '</div>');

            printWindow.document.write('<h3>معاملات الخزنة</h3>');
            printWindow.document.write('<p>مستخدم: ' + userName + '</p>');

            // طباعة الجدول
            printWindow.document.write(table.outerHTML);

            // فوتر
            printWindow.document.write('<footer><p>تم الطباعة بواسطة النظام الإلكتروني - ناينوكس </p></footer>');

            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
<!-- طباعة محددة -->
    <script>

        // دالة الطباعة لكل معاملة
        function printTransaction(transactionId) {
            var transaction = document.getElementById('transaction-' + transactionId);
            var printWindow = window.open('', '', 'height=600,width=800');
            var printDate = new Date().toLocaleString();
            
            // إضافة تنسيق خاص بالطباعة للمعاملة
            printWindow.document.write('<html><head><title>طباعة معاملة</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; direction: rtl; text-align: right; }');
            printWindow.document.write('h3 { text-align: center; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
            printWindow.document.write('th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: center; }');
            printWindow.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
            printWindow.document.write('footer { margin-top: 30px; text-align: center; font-size: 12px; }');
            printWindow.document.write('.date-print { text-align: right; font-size: 14px; margin-bottom: 10px; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            
            // إضافة تاريخ الطباعة في أعلى الصفحة
            printWindow.document.write('<div class="date-print"><strong>تاريخ الطباعة: </strong>' + printDate + '</div>');
            
            // إضافة تفاصيل المعاملة في جدول منسق
            printWindow.document.write('<h3>تفاصيل المعاملة</h3>');
            printWindow.document.write('<table>');
            printWindow.document.write('<tr><th>الخزنة</th><td>الخزنة الرئيسية</td></tr>');
            printWindow.document.write('<tr><th>النوع</th><td>إيداع</td></tr>');
            printWindow.document.write('<tr><th>المبلغ</th><td>500.00 ريال</td></tr>');
            printWindow.document.write('<tr><th>الرصيد الحالي</th><td>0.00 ريال</td></tr>');
            printWindow.document.write('<tr><th>المستخدم</th><td>HamzaFathi</td></tr>');
            printWindow.document.write('<tr><th>الملاحظات</th><td>لا</td></tr>');
            printWindow.document.write('<tr><th>التاريخ</th><td>2025/05/12 15:31</td></tr>');
            printWindow.document.write('</table>');

            // إضافة فوتر مع ملاحظة تم الطباعة بواسطة النظام الإلكتروني
            printWindow.document.write('<footer><p>تم الطباعة بواسطة النظام الإلكتروني - ناينوكس </p></footer>');
            
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

@endsection
