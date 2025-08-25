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
 رواتب الموظفين - نـاينوكـس
@stop

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الموظفين</h4><span class="text-muted mt-1 tx-17 mr-2 mb-0">/ رواتب الموظفين</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

<!-- رسائل النجاح أو الخطأ -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- رسائل التحقق من الإدخال -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

				<!-- row -->
		<div class="row">
			<div class="col-xl-12">
				<div class="card mg-b-20">
					<div class="card-header pb-0">
						<div class="d-flex justify-content-between">
							<button class="btn btn-outline-primary btn-block" data-toggle="modal" data-target="#addSalaryPaymentModal" style="font-size: 15px; width: 300px; height: 40px;">إضافة دفع راتب جديد</button>
							
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
						</div>
					</div>
					<div class="card-body">
						<table id="example1" class="table key-buttons text-md-nowrap table-striped" data-page-length="50">
							<thead>
								<tr>
									<th class="wd-5p border-bottom-0 text-center">تسلسل</th>
									<th class="wd-20p border-bottom-0 text-center">إسم الموظف</th>
									<th class="wd-10p border-bottom-0 text-center">المبلغ</th>
									<th class="wd-10p border-bottom-0 text-center">تاريخ الدفع</th>
									<th class="wd-10p border-bottom-0 text-center">طريقة الدفع</th>
									<th class="wd-10p border-bottom-0 text-center">الحالة</th>
									<th class="wd-15p border-bottom-0 text-center">الملاحظات</th>
									<th class="wd-25p border-bottom-0 text-center">الأحداث</th>
								</tr>
							</thead>
							<tbody>
                                @foreach($salaryPayments as $payment)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $payment->employee->emp_name }}</td>
                                        <td class="text-center">{{ $payment->amount }}</td>
                                        <td class="text-center">{{ $payment->payment_date }}</td>
                                        <td class="text-center">{{ $payment->payment_method }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $payment->payment_status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                                                {{ ucfirst($payment->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $payment->payment_notes }}</td>
                                        <td class="text-center">
                                           <a class="modal-effect btn btn-sm btn-info" data-toggle="modal" href="#viewSalaryPaymentModal" data-id="{{ $payment->id }}" title="عرض التفاصيل">
                                                <i class="las la-eye"></i> عرض
                                            </a>

                                            <a class="modal-effect btn btn-sm btn-danger" href="#" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                <i class="las la-trash"></i> حذف
                                            </a>

                                            <!-- إضافة زر التحديث هنا -->
                                            @if($payment->payment_status == 'unpaid')
                                                <form action="{{ route('salary_payments.update_status', $payment->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">تحديث إلى مدفوع</button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>تم الدفع</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

						</table>
					</div>
				</div>
			</div>
		</div>
        <!-- Modal لعرض تفاصيل الدفع -->
<div class="modal fade" id="viewSalaryPaymentModal" tabindex="-1" role="dialog" aria-labelledby="viewSalaryPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSalaryPaymentModalLabel">تفاصيل الدفع</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- سيتم ملء التفاصيل هنا باستخدام JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

		<!-- Modal لإضافة دفع راتب جديد -->
		<div class="modal fade" id="addSalaryPaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">إضافة دفع راتب جديد</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('salary_payments.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <!-- الموظف -->
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="employee_id">إسم الموظف</label>
                                    <select name="employee_id" id="employee_id" class="form-control" required onchange="getEmployeeSalary()">
                                        <option value="">اختر الموظف</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->emp_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- الخزنة -->
                                <div class="form-group col-md-3">
                                    <label for="cashbox_id">الخزنة</label>
                                    <select name="cashbox_id" id="cashbox_id" class="form-control">
                                        <option value="">اختر الخزنة</option>
                                        @foreach($cashboxes as $cashbox)
                                            <option value="{{ $cashbox->id }}">{{ $cashbox->cash_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- الحساب -->
                                <div class="form-group col-md-3">
                                    <label for="account_id">الحساب</label>
                                    <select name="account_id" id="account_id" class="form-control">
                                        <option value="">اختر الحساب</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="branch_id">{{ __('home.SelectBranch') }}</label>
                                    <select name="branch_id" required class="form-control">
                                        <option value="">{{ __('home.SelectBranch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->bra_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- الراتب الإجمالي قبل الخصومات -->
                                <div class="form-group col-md-3">
                                    <label for="gross_salary">الراتب الإجمالي</label>
                                    <input type="number" name="gross_salary" id="gross_salary" class="form-control" required readonly>
                                </div>

                                <!-- المبلغ -->
                                <div class="form-group col-md-3">
                                    <label for="amount">المبلغ</label>
                                    <input type="number" name="amount" id="amount" class="form-control" required >
                                </div>

                                <!-- المكافآت -->
                                <div class="form-group col-md-2">
                                    <label for="bonus">المكافآت</label>
                                    <input type="number" name="bonus" id="bonus" class="form-control" value="0">
                                </div>

                                <!-- الخصومات -->
                                <div class="form-group col-md-2">
                                    <label for="deduction">الخصومات</label>
                                    <input type="number" name="deduction" id="deduction" class="form-control" value="0">
                                </div>

                                <!-- خصم الضريبة -->
                                <div class="form-group col-md-2">
                                    <label for="tax_deduction">خصم الضريبة</label>
                                    <input type="number" name="tax_deduction" id="tax_deduction" class="form-control" value="0">
                                </div>

                                <!-- مجموع الخصومات -->
                                <div class="form-group col-md-2">
                                    <label for="total_deductions">مجموع الخصومات</label>
                                    <input type="number" name="total_deductions" id="total_deductions" class="form-control" value="0" required>
                                </div>

                                <!-- المبلغ الصافي بعد الخصومات -->
                                <div class="form-group col-md-4">
                                    <label for="net_amount">المبلغ الصافي</label>
                                    <input type="number" name="net_amount" id="net_amount" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- تاريخ الدفع -->
                                <div class="form-group col-md-3">
                                    <label for="payment_date">تاريخ الدفع</label>
                                    <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                                </div>

                                <!-- طريقة الدفع -->
                                <div class="form-group col-md-3">
                                    <label for="payment_method">طريقة الدفع</label>
                                    <select name="payment_method" id="payment_method" class="form-control" required>
                                        <option value="cash">نقدًا</option>
                                        <option value="bank_transfer">تحويل بنكي</option>
                                        <option value="check">شيك</option>
                                    </select>
                                </div>

                                <!-- مرجع الدفع -->
                                <div class="form-group col-md-3">
                                    <label for="payment_reference">مرجع الدفع</label>
                                    <input type="text" name="payment_reference" id="payment_reference" class="form-control">
                                </div>

                                <!-- الشخص الذي استلم الدفع -->
                                <div class="form-group col-md-3">
                                    <label for="payment_received_by">الشخص الذي استلم الدفع</label>
                                    <input type="text" name="payment_received_by" id="payment_received_by" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <!-- تفاصيل طريقة الدفع -->
                                <div class="form-group col-md-4">
                                    <label for="payment_method_details">تفاصيل طريقة الدفع</label>
                                    <input type="text" name="payment_method_details" id="payment_method_details" class="form-control">
                                </div>

                                <!-- شهر الراتب -->
                                <div class="form-group col-md-4">
                                    <label for="salary_month">شهر الراتب</label>
                                    <select name="salary_month" id="salary_month" class="form-control" required>
                                        <option value="January">يناير</option>
                                        <option value="February">فبراير</option>
                                        <option value="March">مارس</option>
                                        <option value="April">أبريل</option>
                                        <option value="May">مايو</option>
                                        <option value="June">يونيو</option>
                                        <option value="July">يوليو</option>
                                        <option value="August">أغسطس</option>
                                        <option value="September">سبتمبر</option>
                                        <option value="October">أكتوبر</option>
                                        <option value="November">نوفمبر</option>
                                        <option value="December">ديسمبر</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="salary_year">سنة الراتب</label>
                                    <input type="number" name="salary_year" id="salary_year" class="form-control" required 
                                        min="2000" max="{{ date('Y') }}" placeholder="أدخل سنة الراتب" step="1">
                                </div>
                            </div>

                            <!-- ملاحظات الدفع -->
                            <div class="form-group">
                                <label for="payment_notes">الملاحظات</label>
                                <textarea name="payment_notes" id="payment_notes" class="form-control"></textarea>
                            </div>

                            <!-- حالة الدفع -->
                            <div class="form-group">
                                <label for="payment_status">حالة الدفع</label>
                                <select name="payment_status" id="payment_status" class="form-control" required>
                                    <option value="paid">تم الدفع</option>
                                    <option value="unpaid">لم يتم الدفع</option>
                                </select>
                            </div>

                            <!-- العملة -->
                            <div class="form-group">
                                <label for="currency">العملة</label>
                                <select name="currency" id="currency" class="form-control" required>
                                    <option value="SAR">ريال سعودي (SAR)</option>
                                    <option value="KWD">دينار كويتي (KWD)</option>
                                    <option value="JOD">دينار أردني (JOD)</option>
                                    <option value="USD">دولار أمريكي (USD)</option>
                                    <option value="EUR">يورو (EUR)</option>
                                    <option value="EGP">جنيه مصري (EGP)</option>
                                    <option value="AED">درهم إماراتي (AED)</option>
                                    <option value="BHD">دينار بحريني (BHD)</option>
                                    <option value="OMR">ريال عماني (OMR)</option>
                                    <!-- يمكنك إضافة المزيد من العملات هنا حسب الحاجة -->
                                </select>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary">إضافة دفع راتب</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>

<script>
    function getEmployeeSalary() {
        var employeeId = document.getElementById('employee_id').value;

        if (employeeId) {
            fetch(`/get-employee-salary/${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    // تعيين الراتب إلى حقل الراتب الإجمالي
                    document.getElementById('gross_salary').value = data.salary;
                })
                .catch(error => {
                    console.error('Error fetching salary:', error);
                });
        } else {
            // إذا لم يتم تحديد موظف، مسح قيمة الراتب الإجمالي
            document.getElementById('gross_salary').value = '';
        }
    }
</script>

<script>
    $('#viewSalaryPaymentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var paymentId = button.data('id');
        var modal = $(this);

        // تنظيف المودال مؤقتًا أثناء التحميل
        modal.find('.modal-body').html('<p>جاري تحميل التفاصيل...</p>');

        // طلب AJAX لجلب البيانات
       fetch(`/salary-payments/details/${paymentId}`)
    .then(response => response.json())
    .then(data => {
        console.log(data); // اطبع البيانات لتتأكد من أنها تحتوي على جميع الحقول
        modal.find('.modal-body').html(`
            <div class="salary-details">
                <p><strong>اسم الموظف:</strong> <span class="employee-name">${data.employee_name}</span></p>
                <p><strong>المبلغ:</strong> <span class="amount">${data.amount}</span></p>
                <p><strong>تاريخ الدفع:</strong> <span class="payment-date">${data.payment_date}</span></p>
                <p><strong>طريقة الدفع:</strong> <span class="payment-method">${data.payment_method}</span></p>
                <p><strong>الحالة:</strong> <span class="payment-status">${data.payment_status}</span></p>
                <p><strong>الملاحظات:</strong> <span class="payment-notes">${data.payment_notes}</span></p>
                <p><strong>المبلغ الإجمالي:</strong> <span class="gross-salary">${data.gross_salary}</span></p>
                <p><strong>البونص:</strong> <span class="bonus">${data.bonus}</span></p>
                <p><strong>الخصم:</strong> <span class="deduction">${data.deduction}</span></p>
                <p><strong>الخصم الضريبي:</strong> <span class="tax-deduction">${data.tax_deduction}</span></p>
                <p><strong>إجمالي الخصومات:</strong> <span class="total-deductions">${data.total_deductions}</span></p>
                <p><strong>المبلغ الصافي:</strong> <span class="net-amount">${data.net_amount}</span></p>
                <p><strong>المرجع:</strong> <span class="payment-reference">${data.payment_reference}</span></p>
                <p><strong>العملة:</strong> <span class="currency">${data.currency}</span></p>
                <p><strong>تم استلام الدفع من:</strong> <span class="payment-received-by">${data.payment_received_by}</span></p>
                <p><strong>تفاصيل طريقة الدفع:</strong> <span class="payment-method-details">${data.payment_method_details}</span></p>
                <p><strong>الشهر:</strong> <span class="salary-month">${data.salary_month}</span></p>
                <p><strong>السنة:</strong> <span class="salary-year">${data.salary_year}</span></p>
            </div>
            <button id="printButton" class="btn btn-primary mt-3">طباعة التفاصيل</button>
        `);

        // إضافة وظيفة للطباعة عند الضغط على الزر
        document.getElementById('printButton').addEventListener('click', function() {
            printSalaryDetails(data);
        });
    })
    .catch(error => {
        modal.find('.modal-body').html('<p class="text-danger">حدث خطأ أثناء تحميل البيانات.</p>');
        console.error('Fetch error:', error);
    });
});
</script>

<script>
    function printSalaryDetails(data) {
        var username = "{{ auth()->user()->name }}"; // اسم المستخدم من Laravel

        var printWindow = window.open('', '', 'height=800,width=900');
        printWindow.document.write('<html><head><title>تفاصيل دفع الموظف</title>');
        printWindow.document.write('<style>');
        
        // تنسيقات الصفحة مع التأكد من أن المحتوى لا ينقسم إلى صفحتين
        printWindow.document.write('@page { size: A4; margin: 15mm; }');
        printWindow.document.write('body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; direction: rtl; background-color: #f9f9f9; padding: 15px; font-size: 12px; line-height: 1.4; }');
        printWindow.document.write('h1, h2 { text-align: center; color: #007bff; font-size: 16px; margin-bottom: 20px; }');
        printWindow.document.write('p { font-size: 12px; margin-bottom: 8px; color: #333; line-height: 1.4; }');
        printWindow.document.write('.salary-details { margin-top: 20px; padding: 15px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }');
        printWindow.document.write('.salary-details p { font-size: 12px; margin-bottom: 8px; }');
        printWindow.document.write('.amount, .gross-salary, .net-amount { color: #28a745; font-weight: bold; font-size: 14px; }');
        printWindow.document.write('.payment-status { color: #ffc107; font-weight: bold; font-size: 14px; }');
        printWindow.document.write('.signature-block { display: flex; justify-content: space-between; margin-top: 40px; font-size: 12px; }');
        printWindow.document.write('.signature-block div { width: 45%; text-align: center; font-size: 12px; }');
        printWindow.document.write('.footer-note { text-align: center; margin-top: 30px; font-size: 12px; color: #555; }');
        printWindow.document.write('.divider { border-top: 1px solid #ddd; margin: 20px 0; }');

        // تقليص المسافة بين الأسطر للحفاظ على محتوى الصفحة الواحدة
        printWindow.document.write('.table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }');
        printWindow.document.write('.table th, .table td { padding: 8px; border: 1px solid #ddd; text-align: center; vertical-align: middle; }');
        printWindow.document.write('.table th { background-color: #007bff; color: white; font-weight: bold; }');
        printWindow.document.write('.table tr:nth-child(even) { background-color: #f9f9f9; }');
        printWindow.document.write('.table td { color: #555; }');
        
        // منع تقسيم المحتوى إلى صفحتين
        printWindow.document.write('body, .salary-details { page-break-after: avoid; page-break-before: avoid; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        
        var currentDate = new Date();
        var formattedDate = currentDate.toLocaleDateString('ar-EG', {
            year: 'numeric', month: 'long', day: 'numeric', weekday: 'long'
        });

        // عرض التاريخ واسم المستخدم
        printWindow.document.write('<p style="font-size: 12px; display: flex; justify-content: space-between;">');
        printWindow.document.write('<span>تاريخ الطباعة: ' + formattedDate + '</span>');
        printWindow.document.write('<span>اسم المستخدم: ' + username + '</span>');
        printWindow.document.write('</p>');

        // عنوان التقرير
        printWindow.document.write('<h2>تفاصيل دفع الموظف</h2>');
        
        // تفاصيل الراتب
        printWindow.document.write('<div class="salary-details">');
        printWindow.document.write('<p><strong>اسم الموظف:</strong> ' + data.employee_name + '</p>');
        printWindow.document.write('<p><strong>المبلغ:</strong> ' + data.amount + '</p>');
        printWindow.document.write('<p><strong>تاريخ الدفع:</strong> ' + data.payment_date + '</p>');
        printWindow.document.write('<p><strong>طريقة الدفع:</strong> ' + data.payment_method + '</p>');
        printWindow.document.write('<p><strong>الحالة:</strong> <span class="payment-status">' + data.payment_status + '</span></p>');
        printWindow.document.write('<p><strong>الملاحظات:</strong> ' + data.payment_notes + '</p>');
        printWindow.document.write('<p><strong>المبلغ الإجمالي:</strong> <span class="amount">' + data.gross_salary + '</span></p>');
        printWindow.document.write('<p><strong>البونص:</strong> ' + data.bonus + '</p>');
        printWindow.document.write('<p><strong>الخصم:</strong> ' + data.deduction + '</p>');
        printWindow.document.write('<p><strong>الخصم الضريبي:</strong> ' + data.tax_deduction + '</p>');
        printWindow.document.write('<p><strong>إجمالي الخصومات:</strong> ' + data.total_deductions + '</p>');
        printWindow.document.write('<p><strong>المبلغ الصافي:</strong> <span class="net-amount">' + data.net_amount + '</span></p>');
        printWindow.document.write('<p><strong>المرجع:</strong> ' + data.payment_reference + '</p>');
        printWindow.document.write('<p><strong>العملة:</strong> ' + data.currency + '</p>');
        printWindow.document.write('<p><strong>تم استلام الدفع من:</strong> ' + data.payment_received_by + '</p>');
        printWindow.document.write('<p><strong>تفاصيل طريقة الدفع:</strong> ' + data.payment_method_details + '</p>');
        printWindow.document.write('<p><strong>الشهر:</strong> ' + data.salary_month + '</p>');
        printWindow.document.write('<p><strong>السنة:</strong> ' + data.salary_year + '</p>');
        printWindow.document.write('</div>');

        // إضافة سطر فاصل
        printWindow.document.write('<div class="divider"></div>');

        // التواقيع
        printWindow.document.write('<div class="signature-block">');
        printWindow.document.write('<div>');
        printWindow.document.write('<p><strong>توقيع المدير:</strong></p>');
        printWindow.document.write('<p>.................................</p>');
        printWindow.document.write('</div>');
        printWindow.document.write('<div>');
        printWindow.document.write('<p><strong>توقيع المستخدم:</strong></p>');
        printWindow.document.write('<p>' + username + '</p>'); // توقيع تلقائي باسم المستخدم
        printWindow.document.write('</div>');
        printWindow.document.write('</div>');

        // ملاحظة
        printWindow.document.write('<div class="footer-note">');
        printWindow.document.write('<p><strong>ملاحظة:</strong> هذا التقرير للاستخدام الداخلي فقط.</p>');
        printWindow.document.write('</div>');

        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>




<style>
    .salary-details p {
        font-size: 16px;
        margin: 10px 0;
        color: #333;
    }

    .salary-details strong {
        color: #0056b3; /* اللون للأسماء */
    }

    .employee-name {
        font-weight: bold;
        color: #007bff; /* لون اسم الموظف */
    }

    .amount, .gross-salary, .net-amount {
        color: #28a745; /* اللون للمبالغ */
        font-weight: bold;
    }

    .payment-status {
        color: #ffc107; /* اللون للحالة */
    }

    .payment-method, .payment-received-by {
        color: #17a2b8; /* اللون لطريقة الدفع */
    }

    .payment-reference {
        color: #6c757d; /* اللون للمرجع */
    }

    .salary-month, .salary-year {
        color: #007bff;
    }

    .payment-method-details {
        font-style: italic;
        color: #343a40;
    }

    .text-danger {
        color: #dc3545;
    }

    .modal-body {
        padding: 20px;
    }
</style>



@endsection