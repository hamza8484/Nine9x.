@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ __('home.MainPage14') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.SuppliersTransactions') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.AddTransactions') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


<div class="col-xl-12">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;">{{ __('home.AddNewTransaction') }}</a>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>
        </div>
        <div class="card-body">
            <table id="example1" class="table key-buttons text-md-nowrap table-striped" data-page-length='50'>
                <thead>
                    <tr>
                        <th class="border-bottom-0 text-center">{{ __('home.No.') }}</th>
                        <th class="border-bottom-0 text-center">{{ __('home.SupplierName') }}</th>
                        <th class="border-bottom-0 text-center">{{ __('home.Palance') }}</th>
                        <th class="border-bottom-0 text-center">{{ __('home.Credit') }}</th>
                        <th class="border-bottom-0 text-center">{{ __('home.PaymentMethod') }}</th>
                        <th class="border-bottom-0 text-center">{{ __('home.Date') }}</th>
                        <th class="border-bottom-0 text-center">{{ __('home.Notes') }}</th>
                        <th class="border-bottom-0 text-center">{{ __('home.Events') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0 ?>
                    @foreach($transactions as $x)
                    <?php $i++ ?>
                    <tr>
                        <td class="text-center">{{ $i }}</td>
                        <td class="text-center">{{ $x->supplier->sup_name }}</td>
                        <td class="text-center">{{ $x->amount }}</td>
                        <td class="text-center">{{ $x->balance }}</td>
                        <td class="text-center">{{ $x->payment_method }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($x->date)->format('Y-m-d H:i:s') }}</td>
                        <td class="text-center">{{ $x->notes }}</td>
                            <td>
                                <a class="modal-effect btn btn-sm btn-info btn-print-sand" 
                                    data-effect="effect-scale" 
                                    data-id="{{ $x->id }}" 
                                    data-sup_name="{{ $x->supplier->sup_name }}" 
                                    data-amount="{{ $x->amount }}" 
                                    data-balance="{{ $x->balance }}" 
                                    data-payment_method="{{ $x->payment_method }}" 
                                    data-date="{{ $x->date }}" 
                                    data-notes="{{ $x->notes }}" 
                                    title="طباعة سند">
                                    <i class="las la-print"></i> {{ __('home.Print') }}
                                </a>      
                            </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Basic modal -->
<div class="modal" id="modaldemo8">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.AddTransactions') }}</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
            <form action="{{ route('supplier_transactions.store') }}" method="post">
                    @csrf
                    <div class="row">
                    <div class="form-group col-md-6 ">
                        <label for="supplier_id">{{ __('home.Supplier') }}</label>
                        <select class="form-control" id="supplier_id" name="supplier_id" required onchange="getsuplierbalance()">
                            <option value="">{{ __('home.SelectSupplier') }}</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->sup_name }}</option>
                            @endforeach
                        </select>
                    </div>
                   
                   
                    <div class="form-group col-md-3 ">
                        <label for="amount">{{ __('home.Amount') }}</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                    </div>
                   
                    
                    <div class="form-group col-md-3 ">
                        <label for="balance">{{ __('home.Palance') }}</label>
                        <input type="number" step="0.01" class="form-control" id="balance" name="balance" required readonly>
                    </div>
                    </div>
                    <div class="row">
                    <div class="form-group col-md-6">
                                <label for="cashbox_id">{{ __('home.Cashboxes') }}</label>
                                <select class="form-control" id="cashbox_id" name="cashbox_id" required>
                                    <option value="">{{ __('home.SelectCashboxMessage') }}</option>
                                    @foreach($cashboxes as $cashbox)
                                        <option value="{{ $cashbox->id }}">{{ $cashbox->cash_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        <div class="form-group col-md-3 ">
                            <label for="payment_method">{{ __('home.PaymentMethod') }}</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="نقدي">{{ __('home.Cash') }}</option>
                                <option value="بطاقة">{{ __('home.Card') }}</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3 ">
                            <label for="date">{{ __('home.Date') }}</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                    </div>

                    

                    <div class="form-group">
                        <label for="notes">{{ __('home.Notes') }}</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px;">{{ __('home.Save') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Basic modal -->

@endsection

@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!-- Internal Modal js-->
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>

    <script>
    function getsuplierbalance() {
        var suplierId = document.getElementById('supplier_id').value;

        if (suplierId) {
            fetch(`/get-suplier-balance/${suplierId}`)
                .then(response => response.json())
                .then(data => {
                    // تحديث حقل الرصيد داخل النموذج
                    document.getElementById('balance').value = data.sup_balance;
                })
                .catch(error => {
                    console.error('حدث خطأ أثناء جلب رصيد المورد:', error);
                    document.getElementById('balance').value = '';
                });
        } else {
            document.getElementById('balance').value = '';
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const printButtons = document.querySelectorAll('.btn-print-sand');
        
        printButtons.forEach(button => {
            button.addEventListener('click', function () {
                const transactionId = button.getAttribute('data-id');
                const sup_name = button.getAttribute('data-sup_name');
                const amount = button.getAttribute('data-amount');
                const balance = button.getAttribute('data-balance');
                const paymentMethod = button.getAttribute('data-payment_method');
                const date = button.getAttribute('data-date');
                const notes = button.getAttribute('data-notes');
                const username = "{{ auth()->user()->name }}";  // اسم المستخدم الذي سجل الدخول

                // إضافة صباحًا أو مساءً بناءً على الوقت
                const dateObj = new Date(date); // تحويل النص إلى كائن تاريخ
                const hours = dateObj.getHours();
                const timeOfDay = hours < 12 ? "صباحًا" : "مساءً";
                const formattedDate = `${dateObj.getFullYear()}-${(dateObj.getMonth() + 1).toString().padStart(2, '0')}-${dateObj.getDate().toString().padStart(2, '0')} ${dateObj.getHours().toString().padStart(2, '0')}:${dateObj.getMinutes().toString().padStart(2, '0')}:${dateObj.getSeconds().toString().padStart(2, '0')} ${timeOfDay}`;

                // إنشاء محتوى السند للطباعة
                const printContent = `
                    <html>
                    <head>
                        <title>طباعة سند</title>
                        <style>
                            body {
                                font-family: 'Arial', sans-serif;
                                direction: rtl;
                                text-align: center;
                                padding: 20px;
                            }
                            .container {
                                width: 100%;
                                max-width: 750px;
                                margin: 0 auto;
                                border: 1px solid #000;
                                padding: 10px;
                                background-color: #fff;
                                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                margin-bottom: 20px;
                            }
                            .logo {
                                width: 150px;
                                height: 150px;
                                border: 2px solid #000;
                            }
                            .company-details {
                                text-align: right;
                                font-size: 14px;
                                margin-right: 10px;
                            }
                            .company-name {
                                font-size: 24px;
                                font-weight: bold;
                            }
                            .company-contact {
                                display: flex;
                                justify-content: space-between;
                                font-size: 16px;
                            }
                            .company-phone, .company-mobile {
                                margin: 5px 0;
                                width: 48%;
                            }
                            .company-tax, .company-register {
                                font-size: 16px;
                                margin: 5px 0;
                            }
                            .company-details p {
                                margin: 3px 0;
                            }
                            .bold {
                                font-weight: bold;
                            }
                            .line {
                                border-top: 2px solid #000;
                                margin-top: 20px;
                                margin-bottom: 20px;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-top: 10px;
                            }
                            table, th, td {
                                border: 1px solid #000;
                            }
                            th, td {
                                padding: 10px;
                                text-align: right;
                                font-size: 16px;
                            }
                            th {
                                background-color: #f0f0f0;
                            }
                            .footer {
                                margin-top: 30px;
                                text-align: center;
                                font-size: 14px;
                                color: #777;
                            }
                            .footer p {
                                margin: 0;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <!-- اسم المؤسسة والبيانات على اليسار -->
                                <div class="company-details">
                                    <h2 class="company-name">${companyName}</h2>
                                    <h4 class="company-phone">ج:${companyPhone}</h4>
                                    <h4 class="company-mobile">ه:${companyMobile}</h4>
                                    <h4 class="company-tax"> ر-ض: ${companyTaxNumber}</h4>
                                    <h4 class="company-register">  س-ت: ${companyCommercialRegister}</h4>
                                    <h3 class="company-address">${companyAddress}</h3>
                                </div>
                                
                                <!-- الشعار على اليمين -->
                                <img src="${companyLogo}" class="logo" alt="Logo" />
                            </div>

                            <!-- تفاصيل السند -->
                            <p><strong>تاريخ السند:</strong> ${formattedDate}</p>
                            <p class="bold">رقم السند: ${transactionId}</p>

                            <table>
                                <tr>
                                    <th>إسم العميل</th>
                                    <td>${sup_name}</td>
                                </tr>
                                <tr>
                                    <th>المبلغ</th>
                                    <td>${amount}</td>
                                </tr>
                                <tr>
                                    <th>الرصيد</th>
                                    <td>${balance}</td>
                                </tr>
                                <tr>
                                    <th>طريقة الدفع</th>
                                    <td>${paymentMethod}</td>
                                </tr>
                                <tr>
                                    <th>الملاحظات</th>
                                    <td>${notes}</td>
                                </tr>
                            </table>

                            <div class="line"></div>
                            <div class="footer">
                                <p>تم إصدار السند بواسطة: ${username}</p>
                                <p>تم إصدار السند من قبل النظام (نــايـنـوكــس)</p>
                            </div>
                        </div>
                    </body>
                    </html>
                `;

                // فتح نافذة جديدة للطباعة
                const printWindow = window.open('', '', 'height=800,width=600');
                printWindow.document.write(printContent);
                printWindow.document.close();
                printWindow.print();
            });
        });
    });
</script>

<script>
    // عند تحميل الصفحة، نضبط التاريخ والوقت الحالي في الحقل
    document.addEventListener('DOMContentLoaded', function() {
        var now = new Date();
        var year = now.getFullYear();
        var month = (now.getMonth() + 1).toString().padStart(2, '0');  // إضافة صفر إذا كان الشهر أقل من 10
        var day = now.getDate().toString().padStart(2, '0');  // إضافة صفر إذا كان اليوم أقل من 10
        var hours = now.getHours().toString().padStart(2, '0');  // إضافة صفر إذا كانت الساعة أقل من 10
        var minutes = now.getMinutes().toString().padStart(2, '0');  // إضافة صفر إذا كانت الدقائق أقل من 10
        
        var dateTimeValue = `${year}-${month}-${day}T${hours}:${minutes}`;

        // تعيين القيمة للحقل datetime-local
        document.getElementById('date').value = dateTimeValue;
    });
</script>


<script>
    // جلب بيانات الشركة من الـ Blade
    const companyName = "{{ $company->company_name }}";
    const companyLogo = "{{ asset('storage/' . $company->logo) }}";  // assuming the logo is stored in public storage
    const companyAddress = "{{ $company->address }}";
    const companyPhone = "{{ $company->phone }}";
    const companyMobile = "{{ $company->mobile }}";
    const companyTaxNumber = "{{ $company->tax_number }}";
    const companyCommercialRegister = "{{ $company->commercial_register }}";
</script>

@endsection
