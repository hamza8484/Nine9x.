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
    {{ __('home.MainPage13') }}
@stop
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">{{ __('home.Suppliers') }}</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.AddSuplier') }}</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
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

				<!-- row -->
				<div class="row">
					<div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<a class="modal-effect btn btn-outline-primary btn-block" 
									data-effect="effect-scale" data-toggle="modal" 
									href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;" >{{ __('home.AddNewSupplier') }}</a>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
							</div>
							<div class="card-body">
								
									<table id="example1" class="table key-buttons text-md-nowrap table-striped" data-page-length='50' >
										<thead>
										<tr>
											<th class="text-center"  style=" width: 5px;"  >{{ __('home.No.') }}</th>
											<th class="text-center"  style=" width: 100px;"  >{{ __('home.SupplierName') }}</th>
											<th class="text-center"  style=" width: 100px;" >{{ __('home.TaxNumber') }}</th>
											<th class="text-center"  style=" width: 40px;" >{{ __('home.Telphone') }}</th>
											<th class="text-center"  style=" width: 40px;"  >{{ __('home.Phone') }}</th>
											<th class="text-center"  style=" width: 70px;"  >{{ __('home.CommercialNo') }}</th>
											<th class="text-center"  style=" width: 50px;"  >{{ __('home.Palance') }}</th>
											<th class="text-center"  style=" width: 140px;"  >{{ __('home.Events') }}</th>
										</tr>
										</thead>
										<tbody>
											@foreach($suppliers as $x)
											<tr>
												<td class="text-center">{{ $loop->iteration }}</td> <!-- استخدم loop->iteration لعدد التسلسل -->
												<td class="text-center">{{ $x->sup_name }} </td>
												<td class="text-center">{{ $x->sup_tax_number }}</td>
												<td class="text-center">{{ $x->sup_phone }}</td>
												<td class="text-center">{{ $x->sup_mobile }}</td>
												<td class="text-center">{{ $x->sup_commercial_record }}</td>
												<td class="text-center">{{ $x->sup_balance }}</td>	
												<td>
													<div class="dropdown">
														<button aria-expanded="false" aria-haspopup="true"
															class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
															type="button">{{ __('home.Events') }}<i class="fas fa-caret-down ml-1"></i></button>
															<div class="dropdown-menu tx-13">
																<a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale" 
																data-id="{{ $x->id }}" 
																data-sup_name="{{ $x->sup_name }}" 
																data-sup_tax_number="{{ $x->sup_tax_number }}" 
																data-sup_phone="{{ $x->sup_phone }}" 
																data-sup_mobile="{{ $x->sup_mobile }}" 
																data-sup_commercial_record="{{ $x->sup_commercial_record }}" 
																data-sup_balance="{{ $x->sup_balance }}" 
																data-sup_address="{{ $x->sup_address }}" 
																data-sup_notes="{{ $x->sup_notes }}" 
																data-toggle="modal" 
																href="#exampleModal2" 
																title="تعديل" 
																style="width: 30px; height: 20px; font-size: 14px; display: inline-flex; justify-content: center; align-items: center; margin: 0 2px;">
																<i class="las la-pen"></i>
																</a>

																<!-- زر حذف المورد -->
																<a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" 
																data-id="{{ $x->id }}" 
																data-sup_name="{{ $x->sup_name }}" 
																data-toggle="modal" 
																href="#modaldemo9" 
																title="حذف" 
																style="width: 30px; height: 20px; font-size: 14px; display: inline-flex; justify-content: center; align-items: center; margin: 0 2px;">
																<i class="las la-trash"></i>
																</a>

																<!-- زر طباعة المورد -->
																<button onclick="printSupplierData({{ $x->id }})" 
																		class="btn btn-primary btn-sm" 
																		title="طباعة" 
																		style="font-size: 14px; width: 30px; height: 20px; display: inline-flex; justify-content: center; align-items: center; margin: 0 2px;">
																	<i class="fas fa-print"></i>
																</button>

																<!-- زر عرض كشف الحساب مع أيقونة -->
																<a href="{{ route('suppliers.statement', $x->id) }}" class="btn btn-sm btn-success" 
																title="كشف المورد" 
																style="width: 30px; height: 20px; display: inline-flex; justify-content: center; align-items: center; font-size: 14px; margin: 0 2px;">
																<i class="fas fa-file-invoice"></i> <!-- أيقونة كشف الحساب -->
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
			</div>	
		</div>
		
		 <!-- Modal for Add suppliers -->
			<div class="modal" id="modaldemo8">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content modal-content-demo">
						<div class="modal-header">
							<h6 class="modal-title">{{ __('home.AddSuplier') }}</h6>
							<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
						</div>

						<div class="modal-body">
							<form action="{{ route('suppliers.store') }}" method="POST">
								{{ csrf_field() }}
								<div class="row">
									<div class="form-group col-md-8">
										<label for="sup_name">{{ __('home.SupplierName') }}</label>
										<input type="text" class="form-control" id="sup_name" name="sup_name" required>
									</div>
									<div class="form-group col-md-4 ">
									<label for="sup_balance">{{ __('home.Palance') }}</label>
									<input type="text" class="form-control" id="sup_balance" name="sup_balance" required>
								</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label for="sup_tax_number">{{ __('home.TaxNumber') }}</label>
										<input type="text" class="form-control" id="sup_tax_number" name="sup_tax_number" required>
									</div>
									<div class="form-group col-md-6">
										<label for="sup_commercial_record">{{ __('home.CommercialNo') }}</label>
										<input type="text" class="form-control" id="sup_commercial_record" name="sup_commercial_record" required>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-6">
										<label for="sup_phone">{{ __('home.Telphone') }}</label>
										<input type="text" class="form-control" id="sup_phone" name="sup_phone">
									</div>
									<div class="form-group col-md-6">
										<label for="sup_mobile">{{ __('home.Phone') }}</label>
										<input type="text" class="form-control" id="sup_mobile" name="sup_mobile">
									</div>
								</div>
								
								<div class="form-group">
									<label for="sup_address">{{ __('home.Address') }}</label>
									<textarea class="form-control" id="sup_address" name="sup_address" rows="2"></textarea>
								</div>
								<div class="form-group">
									<label for="sup_notes">{{ __('home.Notes') }}</label>
									<textarea class="form-control" id="sup_notes" name="sup_notes" rows="2"></textarea>
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

			<!-- Modal for Edit suppliers -->
			<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">{{ __('home.EditSupplier') }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<!-- قم بتحديد action هنا ليكون المسار الصحيح لتحديث المورد -->
							<form action="{{ route('suppliers.update', ':id') }}" method="POST" id="editForm">
								{{ method_field('PUT') }} <!-- أو PATCH حسب الطريقة التي تستخدمها -->
								{{ csrf_field() }}
								<input type="hidden" name="id" id="edit_id"> <!-- سيتم تحديثه باستخدام JavaScript -->
								<div class="form-group">
									<label for="sup_name">{{ __('home.SupplierName') }}</label>
									<input type="text" class="form-control" name="sup_name" id="sup_name" required>
								</div>
								<div class="form-group">
									<label for="sup_tax_number">{{ __('home.TaxNumber') }}</label>
									<input type="text" class="form-control" name="sup_tax_number" id="sup_tax_number" required>
								</div>
								<div class="form-group">
									<label for="sup_commercial_record">{{ __('home.CommercialNo') }}</label>
									<input type="text" class="form-control" id="sup_commercial_record" name="sup_commercial_record" required>
								</div>
								<div class="form-group">
									<label for="sup_phone">{{ __('home.Telphone') }}</label>
									<input type="text" class="form-control" id="sup_phone" name="sup_phone">
								</div>
								<div class="form-group">
									<label for="sup_mobile">{{ __('home.Phone') }}</label>
									<input type="text" class="form-control" id="sup_mobile" name="sup_mobile">
								</div>
								<div class="form-group">
									<label for="sup_balance">{{ __('home.Palance') }}</label>
									<input type="text" class="form-control" id="sup_balance" name="sup_balance" required>
								</div>
								<div class="form-group">
									<label for="sup_address">{{ __('home.Address') }}</label>
									<textarea class="form-control" id="sup_address" name="sup_address" rows="2"></textarea>
								</div>
								<div class="form-group">
									<label for="sup_notes">{{ __('home.Notes') }}</label>
									<textarea class="form-control" id="sup_notes" name="sup_notes" rows="2"></textarea>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary">{{ __('home.Confirm') }}</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Close') }}</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- Modal for Delete suppliers -->
			<div class="modal" id="modaldemo9">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content modal-content-demo">
						<div class="modal-header">
							<h6 class="modal-title">{{ __('home.DeleteSupplier') }}</h6>
							<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
						</div>
						<form action="" method="POST" id="deleteForm"> <!-- أزل $x->id هنا واستخدم الـ id ديناميكياً -->
							{{ method_field('DELETE') }}
							{{ csrf_field() }}
							<input type="hidden" name="id" id="id"> <!-- فقط لاستخدامه في JavaScript -->
							<div class="modal-body">
								<p>{{ __('home.Messages') }}</p><br>
								<input class="form-control" name="sup_name" id="sup_name" type="text" readonly>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Cancel') }}</button>
								<button type="submit" class="btn btn-danger">{{ __('home.Confirm') }}</button>
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
    $('#exampleModal2').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget); // Button that triggered the modal
		var supplierId = button.data('id'); // Extract info from data-* attributes
		var supplierName = button.data('sup_name');
		var supplierTaxNumber = button.data('sup_tax_number');
		var supplierPhone = button.data('sup_phone');
		var supplierMobile = button.data('sup_mobile');
		var supplierCommercialRecord = button.data('sup_commercial_record');
		var supplierBalance = button.data('sup_balance');
		var supplierAddress = button.data('sup_address');
		var supplierNotes = button.data('sup_notes');
		
		// Update the modal's content
		var modal = $(this);
		modal.find('.modal-title').text('{{ __('home.EditSupplier') }} ' + supplierName);
		modal.find('#edit_id').val(supplierId);
		modal.find('#sup_name').val(supplierName);
		modal.find('#sup_tax_number').val(supplierTaxNumber);
		modal.find('#sup_phone').val(supplierPhone);
		modal.find('#sup_mobile').val(supplierMobile);
		modal.find('#sup_commercial_record').val(supplierCommercialRecord);
		modal.find('#sup_balance').val(supplierBalance);
		modal.find('#sup_address').val(supplierAddress);
		modal.find('#sup_notes').val(supplierNotes);

		// Update form action with the correct route
		var formAction = "{{ route('suppliers.update', ':id') }}";
		formAction = formAction.replace(':id', supplierId);
		modal.find('#editForm').attr('action', formAction);
	});

    // تفعيل نموذج الحذف
    $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var id = button.data('id'); // الحصول على المعرف
        var sup_name = button.data('sup_name'); // الحصول على اسم المورد
        var modal = $(this);
        
        // تحديث نموذج الحذف
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #sup_name').val(sup_name);
        
        // تحديث الرابط الخاص بالنموذج (action)
        var action = "{{ url('suppliers') }}/" + id; // قم ببناء الـ URL ديناميكياً
        modal.find('form#deleteForm').attr('action', action);
    });
</script>

<script>
    function printSupplierData(supplierId) {
        // إرسال طلب AJAX للحصول على بيانات المورد من السيرفر
        $.ajax({
            url: '/suppliers/print/' + supplierId,  // الرابط لجلب البيانات
            method: 'GET',
            success: function(response) {
                var supplierData = ` 
                    <div style="text-align: center; font-family: Arial, sans-serif; padding: 20px; direction: rtl;">
                        <h2 style="color: #2c3e50;">{{ __('home.SuplierDetail') }}</h2>
                        <table style="width: 80%; margin: 0 auto; border-collapse: collapse; font-size: 16px; background-color: #f9f9f9;">
                            <tr style="background-color: #ecf0f1;">
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc; width: 40%;">{{ __('home.SupplierName') }}</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.sup_name}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">{{ __('home.TaxNumber') }}</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.sup_tax_number}</td>
                            </tr>
                            <tr style="background-color: #ecf0f1;">
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">{{ __('home.Telphone') }}</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.sup_phone}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">{{ __('home.Phone') }}</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.sup_mobile}</td>
                            </tr>
                            <tr style="background-color: #ecf0f1;">
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">{{ __('home.CommercialNo') }}</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.sup_commercial_record}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">{{ __('home.Palance') }}</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.sup_balance}</td>
                            </tr>
                            <tr style="background-color: #ecf0f1;">
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">{{ __('home.Address') }}</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.sup_address}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; text-align: center; font-weight: bold; border: 1px solid #ccc;">{{ __('home.Notes') }}</td>
                                <td style="padding: 12px; text-align: center; border: 1px solid #ccc;">${response.sup_notes}</td>
                            </tr>
                        </table>
                    </div>
                `;

                // فتح نافذة جديدة للطباعة
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>{{ __('home.PrintSuplierData') }}</title>');
                printWindow.document.write('<style>body { font-family: Arial, sans-serif; padding: 20px; text-align: center; } h2 { color: #2c3e50; } table { width: 80%; margin: 0 auto; border-collapse: collapse; font-size: 16px; background-color: #f9f9f9; } td { padding: 12px; text-align: center; border: 1px solid #ccc; } tr:nth-child(even) { background-color: #ecf0f1; }</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write(supplierData);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            },
            error: function() {
                alert('حدث خطأ أثناء جلب بيانات المورد!');
            }
        });
    }
</script>




@endsection