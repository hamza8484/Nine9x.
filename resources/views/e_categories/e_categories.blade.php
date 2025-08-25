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
فئات المصروفات - ناينوكس
@stop

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">المصروفات</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ فئات المصروفات</span>
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
	@if (session()->has('Add'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>{{ session()->get('Add') }}</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	@endif

	@if (session()->has('Error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
	<!-- row -->
	<div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">
									<a class="modal-effect btn btn-outline-primary btn-block" 
									data-effect="effect-scale" data-toggle="modal" 
									href="#modaldemo8" style="font-size: 17px; width: 300px; height: 40px;" >إضـافــة فــئـة</a>
									
									<!-- زر الذهاب إلى إضافة المصروفات في الجهة اليسرى -->
                                        <a href="{{ route('expenses.index') }}" class="btn btn-primary" style="font-size: 17px; width: 200px; height: 40px;">
                                          شاشــة المصروفات
                                        </a>  
								</div>
							</div>

							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered text-md-nowrap" id="example1" data-page-length="50">
										<thead>
											<tr>
												<th class="text-center" style="width: 6%;">تسلسل</th>
												<th class="text-center" style="width: 20%;">اسم الفئة</th>
												<th class="text-center" style="width: 30%;">الوصــف</th>
												<th class="text-center" style="width: 10%;">الاحداث</th>
											</tr>
										</thead>
											<tbody>
												<?php $i =0?>
												@foreach( $e_categories as $x)
												<?php $i++?>
													<tr>
														<td class="text-center">{{ $i }}</td>
														<td class="text-center">{{ $x->cat_name}}</td>
														<td class="text-center">{{ $x->description}}</td>
														
														<td>
															
																<a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
																	data-id="{{ $x->id }}" data-cat_name="{{ $x->cat_name }}"
																	data-description="{{ $x->description }}" data-toggle="modal"
																	href="#exampleModal2" title="تعديل"><i class="las la-pen"></i></a>
															

														
																<a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
																	data-id="{{ $x->id }}" data-cat_name="{{ $x->cat_name }}"
																	data-toggle="modal" href="#modaldemo9" title="حذف"><i
																		class="las la-trash"></i></a>
														
														</td>
													</tr>
												@endforeach
											</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- Modal effects -->
						<div class="modal" id="modaldemo8">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content modal-content-demo">
									<div class="modal-header">
										<h6 class="modal-title">إضافة فئة <h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										<form action="{{ route('e_categories.store') }}" method="post">
										{{ csrf_field() }}

										<div class="form-group">
											<label for="exampleInputEmail1">اسم الفئة</label>
											<input type="text" class="form-control" id="cat_name" name="cat_name">
										</div>
										<div class="form-group">
											<label for="created_by">اسم المستخدم:</label>
											<input type="text" class="form-control" name="created_by" value="{{ auth()->user()->name }}" required readonly>
										</div>

										<div class="form-group">
												<label for="exampleFormControlTextarea1">الوصــف</label>
												<textarea class="form-control" id="description" name="description" rows="3"></textarea>
										</div>


									</div>
												<div class="modal-footer">
												<button type="submit" class="btn btn-success" 
												style="font-size: 15px; width: 300px; height: 40px;" >حفظ</button>
												<button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
											</div>
										</form>
								</div>
							</div>
						</div>
					<!-- End Modal effects-->

					 <!-- edit -->
					<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">تعديل الفئة</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<form action="{{ route('e_categories.update', ':id') }}" method="post" autocomplete="off" id="editForm">
										{{ method_field('PATCH') }}
										{{ csrf_field() }}
										<div class="form-group">
											<input type="hidden" name="id" id="id" value="">
											<label for="recipient-name" class="col-form-label">اسم الفئة:</label>
											<input class="form-control" name="cat_name" id="cat_name" type="text">
										</div>
										<div class="form-group">
												<label for="created_by">اسم المستخدم:</label>
												<input type="text" class="form-control" name="created_by" value="{{ auth()->user()->name }}" required readonly>
											</div>

										<div class="form-group">
											<label for="message-text" class="col-form-label">الوصــف:</label>
											<textarea class="form-control" id="description" name="description"></textarea>
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary" form="editForm">تاكيد</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
								</div>
							</div>
						</div>
					</div>

					<!-- delete -->
					<div class="modal" id="modaldemo9">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content modal-content-demo">
								<div class="modal-header">
									<h6 class="modal-title">حذف الفـئـة</h6>
									<button aria-label="Close" class="close" data-dismiss="modal" type="button">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<form action="{{ route('e_categories.destroy', ':id') }}" method="post" id="deleteForm">
									{{ method_field('delete') }}
									{{ csrf_field() }}
									<div class="modal-body">
										<p>هل انت متأكد من عملية الحذف ؟</p><br>
										<input type="hidden" name="id" id="id" value="">
										<input class="form-control" name="cat_name" id="cat_name" type="text" readonly>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
										<button type="submit" class="btn btn-danger">تاكيد</button>
									</div>
								</form>
							</div>
						</div>
					</div>

				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
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
    // استخدام الجافا سكربت لتعيين الـ id عند فتح المودال
    $('#exampleModal2').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var id = button.data('id'); // الحصول على الـ id من الزر
        var cat_name = button.data('cat_name');
        var description = button.data('description');
        var modal = $(this);

        // تحديث الحقول داخل المودال
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #cat_name').val(cat_name);
        modal.find('.modal-body #description').val(description);

        // تعديل مسار الفورم ليشمل الـ id
        var actionUrl = "{{ route('e_categories.update', ':id') }}".replace(':id', id);
        modal.find('form').attr('action', actionUrl);
    });
</script>

<script>
    $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var id = button.data('id'); // الحصول على الـ id من الزر
        var cat_name = button.data('cat_name'); // الحصول على اسم الفئة من الزر
        var modal = $(this);

        // تحديث الحقول داخل المودال
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #cat_name').val(cat_name);

        // تحديث الرابط داخل الفورم ليشمل الـ id
        var actionUrl = "{{ route('e_categories.destroy', ':id') }}".replace(':id', id);
        modal.find('form').attr('action', actionUrl);
    });
</script>



@endsection