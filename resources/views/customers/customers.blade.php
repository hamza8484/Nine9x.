@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    العملاء - ناينوكس
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">العملاء</h4><span class="text-muted mt-1 tx-17 mr-2 mb-0">/ إضافة عميل</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @foreach (['success', 'add', 'edit', 'delete'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg == 'success' ? 'success' : ($msg == 'add' ? 'info' : ($msg == 'edit' ? 'warning' : 'danger')) }} alert-dismissible fade show" role="alert">
                <strong>{{ session($msg) }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    @endforeach

    <style>
        .alert-success {
            background-color: #28a745;
            color: white;
        }
        .alert-info {
            background-color: #17a2b8;
            color: white;
        }
        .alert-warning {
            background-color: #ffc107;
            color: black;
        }
        .alert-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>

<div class="row">
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;">إضافة عميل جديد</a>
                    <button onclick="printAllCustomers()" style="padding: 10px 20px; font-size: 16px; background-color: #3498db; color: white; border: none; cursor: pointer;">طباعة جميع العملاء</button>
					<i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
            </div>
            <div class="card-body">
                <table id="example1" class="table key-buttons text-md-nowrap table-striped" data-page-length='50'>
                    <thead>
                        <tr>
                            <th class="wd-5p border-bottom-0 text-center">تسلسل</th>
                            <th class="wd-20p border-bottom-0 text-center">إسم العميل</th>
                            <th class="wd-15p border-bottom-0 text-center">الرقم الضريبي</th>
                            <th class="wd-10p border-bottom-0 text-center">الهاتف</th>
                            <th class="wd-10p border-bottom-0 text-center">الجوال</th>
                            <th class="wd-10p border-bottom-0 text-center">الرصيد</th>
                            <th class="wd-25p border-bottom-0 text-center">ملاحظات</th>
                            <th class="wd-15p border-bottom-0 text-center">الأحداث</th>
                        </tr>
                    </thead>
					<tbody>
						@foreach($customers as $x)
						<tr>
							<td class="text-center">{{ $loop->iteration }}</td> <!-- استخدم loop->iteration لعدد التسلسل -->
							<td class="text-center">{{ $x->cus_name }} </td>
							<td class="text-center">{{ $x->cus_tax_number }}</td>
							<td class="text-center">{{ $x->cus_phone }}</td>
							<td class="text-center">{{ $x->cus_mobile }}</td>
							<td class="text-center">{{ $x->cus_balance }}</td>
							<td class="text-center">{{ $x->cus_notes }}</td>
							<td class="text-center">{{ $x->Created_by }}</td>
							<td>
                                <div class="dropdown" style="display: flex; justify-content: center; align-items: center;">
                                    <button class="btn ripple btn-primary btn-sm" data-toggle="dropdown" type="button" style="width: auto; padding: 2px 70px;">
                                        العمليات<i class="fas fa-caret-down ml-3"></i>
                                    </button>
                                    <div class="dropdown-menu tx-13">
                                        <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale" 
                                        data-id="{{ $x->id }}" 
                                        data-cus_name="{{ $x->cus_name }}" 
                                        data-cus_tax_number="{{ $x->cus_tax_number }}" 
                                        data-cus_phone="{{ $x->cus_phone }}" 
                                        data-cus_mobile="{{ $x->cus_mobile }}" 
                                        data-cus_maile="{{ $x->cus_maile }}" 
                                        data-cus_commercial_record="{{ $x->cus_commercial_record }}" 
                                        data-cus_balance="{{ $x->cus_balance }}" 
                                        data-cus_address="{{ $x->cus_address }}" 
                                        data-cus_notes="{{ $x->cus_notes }}" 
                                        data-toggle="modal" 
                                        href="#exampleModal2" 
                                        title="تعديل">
                                        <i class="las la-pen"></i>
                                        </a>
                                        <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" 
                                        data-id="{{ $x->id }}" 
                                        data-cus_name="{{ $x->cus_name }}" 
                                        data-toggle="modal" 
                                        href="#modaldemo9" 
                                        title="حذف">
                                        <i class="las la-trash"></i>
                                        </a>
                                        <button onclick="printCustomerData({{ $x->id }})" 
                                                class="btn btn-primary" 
                                                style="font-size: 5px; width: 40px; height: 25px;">
                                            طباعة  
                                        </button>
                                        <a href="{{ route('customers.transactions', $x->id) }}" class="btn btn-sm btn-success">
                                            كشف
                                        </a>
                                    </div>
                                </div>
                            </td>

						</tr>
						@endforeach
					</tbody>

                </table>                        
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add Customer -->
<div class="modal" id="modaldemo8">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">إضافة عميل</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('customers.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label for="cus_name">اسم العميل</label>
                            <input type="text" class="form-control" id="cus_name" name="cus_name" required>
                        </div>
                        <div class="form-group col-md-4 ">
                        <label for="cus_balance">الرصيد</label>
                        <input type="text" class="form-control" id="cus_balance" name="cus_balance" required>
                    </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="cus_tax_number">الرقم الضريبي</label>
                            <input type="text" class="form-control" id="cus_tax_number" name="cus_tax_number" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cus_commercial_record">السجل التجاري</label>
                            <input type="text" class="form-control" id="cus_commercial_record" name="cus_commercial_record" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="cus_phone">الهاتف</label>
                            <input type="text" class="form-control" id="cus_phone" name="cus_phone">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cus_mobile">الجوال</label>
                            <input type="text" class="form-control" id="cus_mobile" name="cus_mobile">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cus_maile">الايميل</label>
                            <input type="text" class="form-control" id="cus_maile" name="cus_maile">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="cus_address">العنوان</label>
                        <textarea class="form-control" id="cus_address" name="cus_address" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="cus_notes">ملاحظات</label>
                        <textarea class="form-control" id="cus_notes" name="cus_notes" rows="2"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px;">حفظ</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

		<!-- Modal for Edit Customer -->
	<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">تعديل العميل</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- قم بتعديل action هنا ليكون فارغاً -->
					<form action="" method="POST" id="editForm">
						{{ method_field('PATCH') }}
						{{ csrf_field() }}
						<input type="hidden" name="id" id="edit_id"> <!-- سيتم تحديثه باستخدام JavaScript -->
						<div class="form-group">
							<label for="cus_name">اسم العميل</label>
							<input type="text" class="form-control" name="cus_name" id="cus_name" required>
						</div>
						<div class="form-group">
							<label for="cus_tax_number">الرقم الضريبي</label>
							<input type="text" class="form-control" name="cus_tax_number" id="cus_tax_number" required>
						</div>
						<div class="form-group">
							<label for="cus_commercial_record">السجل التجاري</label>
							<input type="text" class="form-control" id="cus_commercial_record" name="cus_commercial_record" required>
						</div>
						<div class="form-group">
							<label for="cus_phone">الهاتف</label>
							<input type="text" class="form-control" id="cus_phone" name="cus_phone">
						</div>
						<div class="form-group">
							<label for="cus_mobile">الجوال</label>
							<input type="text" class="form-control" id="cus_mobile" name="cus_mobile">
						</div>
                        <div class="form-group ">
                            <label for="cus_maile">الايميل</label>
                            <input type="text" class="form-control" id="cus_maile" name="cus_maile">
                        </div>
						<div class="form-group">
							<label for="cus_balance">الرصيد</label>
							<input type="text" class="form-control" id="cus_balance" name="cus_balance" required>
						</div>
						<div class="form-group">
							<label for="cus_address">العنوان</label>
							<textarea class="form-control" id="cus_address" name="cus_address" rows="2"></textarea>
						</div>
						<div class="form-group">
							<label for="cus_notes">ملاحظات</label>
							<textarea class="form-control" id="cus_notes" name="cus_notes" rows="2"></textarea>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">تأكيد</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>



		<!-- Modal for Delete Customer -->
	<div class="modal" id="modaldemo9">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content modal-content-demo">
				<div class="modal-header">
					<h6 class="modal-title">حذف العميل</h6>
					<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<form action="" method="POST" id="deleteForm"> <!-- أزل $x->id هنا واستخدم الـ id ديناميكياً -->
					{{ method_field('DELETE') }}
					{{ csrf_field() }}
					<input type="hidden" name="id" id="id"> <!-- فقط لاستخدامه في JavaScript -->
					<div class="modal-body">
						<p>هل انت متأكد من عملية الحذف ؟</p><br>
						<input class="form-control" name="cus_name" id="cus_name" type="text" readonly>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
						<button type="submit" class="btn btn-danger">تأكيد</button>
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

<script src="{{URL::asset('assets/js/modal.js')}}"></script>


<script>
    $('#exampleModal2').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var id = button.data('id'); // الحصول على المعرف
        var cus_name = button.data('cus_name'); // الحصول على اسم العميل
        var cus_tax_number = button.data('cus_tax_number'); // الحصول على الرقم الضريبي
        var cus_commercial_record = button.data('cus_commercial_record'); // الحصول على السجل التجاري
        var cus_phone = button.data('cus_phone'); // الحصول على الهاتف
        var cus_mobile = button.data('cus_mobile'); // الحصول على الجوال
        var cus_maile = button.data('cus_maile'); // الحصول على الايميل
        var cus_balance = button.data('cus_balance'); // الحصول على الرصيد
        var cus_address = button.data('cus_address'); // الحصول على العنوان
        var cus_notes = button.data('cus_notes'); // الحصول على الملاحظات
        
        var modal = $(this);
        
        // تحديث نموذج التعديل بالقيم
        modal.find('.modal-body #edit_id').val(id); 
        modal.find('.modal-body #cus_name').val(cus_name); 
        modal.find('.modal-body #cus_tax_number').val(cus_tax_number); 
        modal.find('.modal-body #cus_commercial_record').val(cus_commercial_record); 
        modal.find('.modal-body #cus_phone').val(cus_phone); 
        modal.find('.modal-body #cus_mobile').val(cus_mobile); 
        modal.find('.modal-body #cus_maile').val(cus_maile); 
        modal.find('.modal-body #cus_balance').val(cus_balance); 
        modal.find('.modal-body #cus_address').val(cus_address); 
        modal.find('.modal-body #cus_notes').val(cus_notes); 

        // تحديث action للـ Form
        var actionUrl = "/customers/" + id; 
        modal.find('form#editForm').attr('action', actionUrl);
    });

    // تفعيل نموذج الحذف
    $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var id = button.data('id'); // الحصول على المعرف
        var cus_name = button.data('cus_name'); // الحصول على اسم العميل
        var modal = $(this);
        
        // تحديث نموذج الحذف
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #cus_name').val(cus_name);
        
        // تحديث الرابط الخاص بالنموذج (action)
        var action = "{{ url('customers') }}/" + id; // قم ببناء الـ URL ديناميكياً
        modal.find('form#deleteForm').attr('action', action);
    });
</script>

<script>
    function printCustomerData(customerId) {
        // إرسال طلب AJAX للحصول على بيانات العميل من السيرفر
        $.ajax({
            url: '/customers/print/' + customerId,  // الرابط لجلب البيانات
            method: 'GET',
            success: function(response) {
                var customerData = ` 
                    <div style="text-align: center; font-family: Arial, sans-serif; padding: 20px; direction: rtl;">
                        <h2 style="color: #2c3e50;">بيانات العميل</h2>
                        <table style="width: 80%; margin: 0 auto; border-collapse: collapse; font-size: 16px; background-color: #f9f9f9;">
                            <tr style="background-color: #ecf0f1;">
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc; width: 40%;">اسم العميل:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_name}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الرقم الضريبي:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_tax_number}</td>
                            </tr>
                            <tr style="background-color: #ecf0f1;">
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الهاتف:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_phone}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الجوال:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_mobile}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الايميل:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_maile}</td>
                            </tr>
                            <tr style="background-color: #ecf0f1;">
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">السجل التجاري:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_commercial_record}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الرصيد:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_balance}</td>
                            </tr>
                            <tr style="background-color: #ecf0f1;">
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">العنوان:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_address}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">ملاحظات:</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.cus_notes}</td>
                            </tr>
                        </table>
                    </div>
                `;

                // فتح نافذة جديدة للطباعة
                var printWindow = window.open('', '', 'height=900,width=900');
                printWindow.document.write('<html><head><title>طباعة بيانات العميل</title>');
                printWindow.document.write('<style>body { font-family: Arial, sans-serif; padding: 20px; text-align: center; } h2 { color: #2c3e50; } table { width: 80%; margin: 0 auto; border-collapse: collapse; font-size: 16px; background-color: #f9f9f9; } td { padding: 12px; text-align: center; border: 1px solid #ccc; } tr:nth-child(even) { background-color: #ecf0f1; }</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(customerData);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            },
            error: function() {
                alert('حدث خطأ أثناء جلب بيانات العميل!');
            }
        });
    }
</script>

<script>
    function printAllCustomers() {
        // إرسال طلب AJAX للحصول على بيانات جميع العملاء من السيرفر
        $.ajax({
    url: '/customers/print-all',
    method: 'GET',
    success: function(response) {
        // طباعة الاستجابة بالكامل
        console.log("Response from server:", response);

        // تحقق إذا كانت هناك بيانات للعملاء في response.customers
        if (response && response.customers) {
            console.log("Data for customers:", response.customers);
        } else {
            console.log("No customers data found in the response.");
        }

        // إضافة بيانات العملاء إلى الجدول
        var customersData = `
            <div style="text-align: center; font-family: Arial, sans-serif; padding: 20px; direction: rtl;">
                <h2 style="color: #2c3e50;">بيانات جميع العملاء</h2>
                <table style="width: 80%; margin: 0 auto; border-collapse: collapse; font-size: 16px; background-color: #f9f9f9;">
                    <tr style="background-color: #ecf0f1;">
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">اسم العميل</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الرقم الضريبي</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الهاتف</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الجوال</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الايميل</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">السجل التجاري</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">الرصيد</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">العنوان</th>
                        <th style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">ملاحظات</th>
                    </tr>
        `;
        
        // إضافة بيانات العملاء إلى الجدول
        response.customers.forEach(function(customer) {
            customersData += `
                <tr>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_name}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_tax_number}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_phone}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_mobile}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_maile}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_commercial_record}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_balance}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_address}</td>
                    <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${customer.cus_notes}</td>
                </tr>
            `;
        });

        customersData += `</table></div>`;
        
        // فتح نافذة للطباعة
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>طباعة بيانات جميع العملاء</title>');
        printWindow.document.write('<style>body { font-family: Arial, sans-serif; padding: 20px; text-align: center; } h2 { color: #2c3e50; } table { width: 80%; margin: 0 auto; border-collapse: collapse; font-size: 16px; background-color: #f9f9f9; } td, th { padding: 12px; text-align: center; border: 1px solid #ccc; } tr:nth-child(even) { background-color: #ecf0f1; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(customersData);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    },
    error: function() {
        alert('حدث خطأ أثناء جلب بيانات جميع العملاء!');
        }
    });
    }

</script>

@endsection

