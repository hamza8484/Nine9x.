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
 {{ __('home.MainPage8') }}
@stop
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">{{ __('home.Store') }}</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.AddStore') }}</span>
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

				<!-- row -->
				<div class="row">
					<div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<a class="modal-effect btn btn-outline-primary btn-block"
										data-effect="effect-scale" data-toggle="modal" 
										href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;">{{ __('home.AddStore') }}</a>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>
							</div>
							<div class="card-body">
								<table id="example1" class="table key-buttons text-md-nowrap table-striped" data-page-length='50'>
									<thead>
										<tr>
											<th class="border-bottom-0 text-center">{{ __('home.No.') }}</th>
											<th class="border-bottom-0 text-center">{{ __('home.StoreName') }}</th>
											<th class="border-bottom-0 text-center">{{ __('home.Location') }}</th>
											<th class="border-bottom-0 text-center">{{ __('home.Branch') }}</th> 
											<th class="border-bottom-0 text-center">{{ __('home.Qty') }}</th>
											<th class="border-bottom-0 text-center">{{ __('home.Inventory') }}</th>
											<th class="border-bottom-0 text-center">{{ __('home.User Name') }}</th>
											<th class="border-bottom-0 text-center">{{ __('home.Notes') }}</th>
											<th class="border-bottom-0 text-center">{{ __('home.Events') }}</th>
										</tr>
									</thead>
									<tbody>
										@foreach($stores as $key => $store)
										<tr>
											<td class="text-center">{{ $key + 1 }}</td>  <!-- التسلسل -->
											<td class="text-center">{{ $store->store_name }}</td>
											<td class="text-center">{{ $store->store_location }}</td>
											<td class="text-center">{{ $store->branch ? $store->branch->bra_name : 'غير معرف' }}</td> <!-- عرض اسم الفرع -->
											<td class="text-center">{{ $store->total_stock }}</td>
											<td class="text-center">{{ $store->inventory_value }}</td>
											<td class="text-center">{{ $store->status }}</td>
											<td class="text-center">{{ $store->store_notes }}</td>
											<td>
												<!-- زر تعديل -->
												<a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
													data-id="{{ $store->id }}" 
													data-store_name="{{ $store->store_name }}" 
													data-store_location="{{ $store->store_location }}" 
													data-total_stock="{{ $store->total_stock }}"
													data-inventory_value="{{ $store->inventory_value }}" 
													data-status="{{ $store->status }}"
													data-store_notes="{{ $store->store_notes }}" 
													data-branch_id="{{ $store->branch_id }}" 
													data-toggle="modal" href="#exampleModal2" title="تعديل">
													<i class="las la-pen"></i>
													</a>


												
												<!-- زر الحذف في الواجهة -->
												<a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
													data-id="{{ $store->id }}"
													data-store_name="{{ $store->store_name }}"
													data-toggle="modal" href="#deleteModal" title="حذف">
													<i class="las la-trash"></i>
												</a>

											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>

						</div>
					</div>
					<!-- Modal effects -->
						<div class="modal" id="modaldemo8">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document"> <!-- استخدمنا modal-lg هنا لزيادة العرض -->
								<div class="modal-content modal-content-demo">
									<div class="modal-header">
										<h6 class="modal-title">{{ __('home.AddStore') }}</h6>
										<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										<form action="{{ route('stores.store') }}" method="post">
											{{ csrf_field() }}
											<div class="row">
												<!-- اسم المخزن -->
												<div class="form-group col-md-8 ">
													<label for="store_name">{{ __('home.StoreName') }}</label>
													<input type="text" class="form-control" id="store_name" name="store_name" required>
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
												<!-- الموقع داخل المخزن -->
												<div class="form-group col-md-4 ">
													<label for="store_location">{{ __('home.Location') }}</label>
													<input type="text" class="form-control" id="store_location" name="store_location">
												</div>
											</div>

											<div class="row">
												<!-- إجمالي المخزون -->
												<div class="form-group col-md-6 ">
													<label for="total_stock">{{ __('home.Qty') }}</label>
													<input type="number" class="form-control" id="total_stock" name="total_stock" value="0" step="1">
												</div>

												<!-- قيمة المخزون -->
												<div class="form-group col-md-6 ">
													<label for="inventory_value">{{ __('home.Inventory') }}</label>
													<input type="number" class="form-control" id="inventory_value" name="inventory_value" value="0" step="0.01">
												</div>
											</div>

											<div class="row">
												<!-- حالة المخزن -->
												<div class="form-group col-md-5 ">
													<label for="status">{{ __('home.Status') }}</label><br>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" id="status_active" name="status" value="active" checked>
														<label class="form-check-label" for="status_active">{{ __('home.Active') }}</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" id="status_inactive" name="status" value="inactive">
														<label class="form-check-label" for="status_inactive">{{ __('home.Inactive') }}</label>
													</div>
												</div>
											</div>
											<div class="row">
												<!-- ملاحظات إضافية -->
												<div class="form-group col-md-12 ">
													<label for="store_notes">{{ __('home.Notes') }}</label>
													<textarea class="form-control" id="store_notes" name="store_notes" rows="3"></textarea>
												</div>
											</div>
											<div class="row">
												<!-- اسم المستخدم -->
												<div class="form-group col-md-12 ">
													<label for="created_by">{{ __('home.UsreName') }}</label>
													<input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
												</div>
											</div>
									</div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px;">{{ __('home.Save') }}</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Close') }}</button>
									</div>
									</form>
								</div>
							</div>
						</div>
						<!-- End Modal effects -->

						<!-- Modal تعديل المخزن -->
						<div class="modal" id="exampleModal2">
							<div class="modal-dialog modal-dialog-centered modal-lg" role="document"> <!-- استخدمنا modal-lg هنا لزيادة العرض -->
								<div class="modal-content modal-content-demo">
									<div class="modal-header">
										<h6 class="modal-title">{{ __('home.StoreEdit') }}</h6>
										<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										<!-- نموذج التعديل -->
										<form method="POST" enctype="multipart/form-data">
											@csrf
											@method('PUT')
											<div class="row">
												<!-- اسم المخزن -->
												<div class="form-group col-md-8">
													<label for="store_name">{{ __('home.StoreName') }}</label>
													<input type="text" class="form-control" id="store_name" name="store_name" required>
												</div>
												<div class="form-group col-md-4">
													<label for="branch_id">{{ __('home.SelectBranch') }}</label>
													<select name="branch_id" id="branch_id" required class="form-control">
														<option value="">{{ __('home.SelectBranch') }}</option>
														@foreach ($branches as $branch)
															<option value="{{ $branch->id }}">{{ $branch->bra_name }}</option>
														@endforeach
													</select>
												</div>

											</div>

											<div class="row">
												<!-- الموقع داخل المخزن -->
												<div class="form-group col-md-4">
													<label for="store_location">{{ __('home.Location') }}</label>
													<input type="text" class="form-control" id="store_location" name="store_location">
												</div>
											</div>

											<div class="row">
												<!-- إجمالي المخزون -->
												<div class="form-group col-md-6">
													<label for="total_stock">{{ __('home.Qty') }}</label>
													<input type="number" class="form-control" id="total_stock" name="total_stock" value="0" step="1">
												</div>

												<!-- قيمة المخزون -->
												<div class="form-group col-md-6">
													<label for="inventory_value">{{ __('home.Inventory') }}</label>
													<input type="number" class="form-control" id="inventory_value" name="inventory_value" value="0" step="0.01">
												</div>
											</div>

											<div class="row">
												<!-- حالة المخزن -->
												<div class="form-group col-md-5">
													<label for="status">{{ __('home.Status') }}</label><br>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" id="status_active" name="status" value="active" checked>
														<label class="form-check-label" for="status_active">{{ __('home.Active') }}</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" id="status_inactive" name="status" value="inactive">
														<label class="form-check-label" for="status_inactive">{{ __('home.Inactive') }}</label>
													</div>
												</div>
											</div>

											<div class="row">
												<!-- ملاحظات إضافية -->
												<div class="form-group col-md-12">
													<label for="store_notes">{{ __('home.Notes') }}</label>
													<textarea class="form-control" id="store_notes" name="store_notes" rows="3"></textarea>
												</div>
											</div>
											<div class="row">
												<!-- اسم المستخدم -->
												<div class="form-group col-md-12">
													<label for="created_by">{{ __('home.UsreName') }}</label>
													<input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
												</div>
											</div>
									</div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px;">{{ __('home.Edit') }}</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Close') }}</button>
									</div>
									</form>
								</div>
							</div>
						</div>
						<!-- End Modal تعديل المخزن-->


						<!-- Modal تأكيد الحذف -->
						<div class="modal" id="deleteModal">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content modal-content-demo">
									<div class="modal-header">
										<h6 class="modal-title">{{ __('home.ConfirmStore') }}</h6>
										<button aria-label="Close" class="close" data-dismiss="modal" type="button">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<p>{{ __('home.Messages') }}<strong id="store_name"></strong>؟</p>
									</div>
									<div class="modal-footer">
										<!-- الزر الذي ينفذ عملية الحذف -->
										<form id="deleteForm" method="POST">
											@csrf
											@method('DELETE')
											<button type="submit" class="btn btn-danger">{{ __('home.Delete') }}</button>
											<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Cancel') }}</button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- End Modal تأكيد الحذف -->
				</div>
				<!-- row closed -->

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
    // عند عرض Modal تعديل المخزن
    $('#exampleModal2').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // الزر الذي فتح الـ Modal
        var id = button.data('id');
        var store_name = button.data('store_name');
        var store_location = button.data('store_location');
        var total_stock = button.data('total_stock');
        var inventory_value = button.data('inventory_value');
        var status = button.data('status');
        var store_notes = button.data('store_notes');
        var branch_id = button.data('branch_id'); // إضافة قيمة الفرع

        var modal = $(this);
        modal.find('.modal-body #store_name').val(store_name);
        modal.find('.modal-body #store_location').val(store_location);
        modal.find('.modal-body #total_stock').val(total_stock);
        modal.find('.modal-body #inventory_value').val(inventory_value);
        modal.find('.modal-body #status_' + status).prop('checked', true);
        modal.find('.modal-body #store_notes').val(store_notes);

        // تعيين قيمة الفرع في الـ Select عند تعديل المخزن
        modal.find('.modal-body #branch_id').val(branch_id); // تعيين الفرع المختار

        // تحديث الرابط لعملية التحديث باستخدام الـ Route الخاصة بـ Laravel
        modal.find('form').attr('action', '{{ route("stores.update", ":id") }}'.replace(':id', id));
    });
</script>

<script>
    // عند عرض Modal تأكيد الحذف
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // الزر الذي فتح الـ Modal
        var id = button.data('id');
        var store_name = button.data('store_name');

        var modal = $(this);
        modal.find('.modal-body #store_name').text(store_name); // عرض اسم المخزن في الـ Modal

        // تحديث رابط الحذف في النموذج
        modal.find('#deleteForm').attr('action', '{{ route("stores.destroy", ":id") }}'.replace(':id', id));

    });
</script>


@endsection