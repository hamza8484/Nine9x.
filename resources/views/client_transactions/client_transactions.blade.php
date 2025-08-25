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
    سندات العملاء - ناينوكس
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">السندات</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ سند عميل</span>
            </div>
        </div>
    </div>
@endsection

@section('content')

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('Add'))
    <div class="alert alert-success">
        {{ session('Add') }}
    </div>
@endif

@if(session('edit'))
    <div class="alert alert-success">
        {{ session('edit') }}
    </div>
@endif

@if(session('delete'))
    <div class="alert alert-success">
        {{ session('delete') }}
    </div>
@endif


<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;" >إضافة سند عميل</a>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
            </div>
            <div class="card-body">
                <table id="example1" class="table key-buttons text-md-nowrap table-striped" data-page-length='50'>
                    <thead>
                        <tr>
                            <th class="border-bottom-0 text-center">تسلسل</th>
                            <th class="border-bottom-0 text-center">إسم العميل</th>
                            <th class="border-bottom-0 text-center">القبض</th>
                            <th class="border-bottom-0 text-center">رصيد سابق</th>
                            <th class="border-bottom-0 text-center">طريقة الدفع</th>
                            <th class="border-bottom-0 text-center">تاريخ السند</th>
                            <th class="border-bottom-0 text-center">الملاحظات</th>
                            <th class="border-bottom-0 text-center">الأحداث</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0 ?>
                        @foreach($transactions as $x)
                            <?php $i++ ?>
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $x->client->cus_name }}</td>
                                <td class="text-center">{{ $x->amount }}</td>
                                <td class="text-center">{{ $x->balance }}</td>
                                <td class="text-center">{{ $x->payment_method }}</td>
                                <td class="text-center">{{ $x->date }}</td>
                                <td class="text-center">{{ $x->notes }}</td>
                                <td>
                                    <a class="modal-effect btn btn-sm btn-info btn-print-sand" 
                                        data-effect="effect-scale" 
                                        data-id="{{ $x->id }}" 
                                        data-client_name="{{ $x->client->cus_name }}" 
                                        data-amount="{{ $x->amount }}" 
                                        data-balance="{{ $x->balance }}" 
                                        data-payment_method="{{ $x->payment_method }}" 
                                        data-date="{{ $x->date }}" 
                                        data-notes="{{ $x->notes }}" 
                                        title="طباعة سند">
                                        <i class="las la-print"></i> طباعة سند
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">إضافة سند عميل</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="modal-body">
                    <form action="{{ route('client_transactions.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-5">
                                <label for="client_id">العميل</label>
                                <select id="client_id" name="client_id" class="form-control" onchange="getcustomerbalance()">
                                    <option value="">اختر العميل</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->cus_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="balance">رصيد العميل</label>
                                <input type="number" step="0.01" class="form-control" id="balance" name="balance" required readonly>
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label for="date">تاريخ السند</label>
                                <input type="datetime-local" class="form-control" id="date" name="date" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-5">
                                <label for="cashbox_id">الخزنة</label>
                                <select class="form-control" id="cashbox_id" name="cashbox_id" required>
                                    <option value="">اختر الخزنة</option>
                                    @foreach($cashboxes as $cashbox)
                                        <option value="{{ $cashbox->id }}">{{ $cashbox->cash_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="account_id">الحسابات</label>
                                <select class="form-control" id="account_id" name="account_id" required>
                                    <option value="">اختر الحساب</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="payment_method">طريقة الدفع</label>
                                <select class="form-control" id="payment_method" name="payment_method" required>
                                    <option value="نقدي">نقدي</option>
                                    <option value="بطاقة">بطاقة</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="amount">القبض</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="notes">الملاحظات</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px;">حفظ</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Basic modal -->

</div>

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
    function getcustomerbalance() {
        var clientId = document.getElementById('client_id').value;

        if (clientId) {
            fetch(`/get-customer-balance/${clientId}`)
                .then(response => response.json())
                .then(data => {
                    // إذا تم جلب الرصيد بنجاح
                    document.getElementById('balance').value = data.cus_balance;
                    document.getElementById('balance').classList.remove('error'); // إزالة الكلاس الأحمر في حال النجاح
                })
                .catch(error => {
                    console.error('حدث خطأ أثناء جلب رصيد العميل:', error);
                    document.getElementById('balance').value = '';  // في حال حدوث خطأ
                    document.getElementById('balance').classList.add('error'); // إضافة الكلاس الأحمر عند الخطأ
                });
        } else {
            document.getElementById('balance').value = '';  // إذا لم يتم اختيار العميل
            document.getElementById('balance').classList.remove('error'); // إزالة الكلاس الأحمر إذا لم يتم اختيار العميل
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const printButtons = document.querySelectorAll('.btn-print-sand');
        
        printButtons.forEach(button => {
            button.addEventListener('click', function () {
                const transactionId = button.getAttribute('data-id');
                const clientName = button.getAttribute('data-client_name');
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
                                    <td>${clientName}</td>
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
