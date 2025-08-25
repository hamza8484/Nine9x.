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
 {{ __('home.MainPage9') }}
@stop
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">{{ __('home.categories') }} </h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.AddCategory') }}</span>
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
                                    <div class="d-flex justify-content-between w-100">
                                    <!-- زر إضافة مصروف جديد في الجهة اليسرى -->
                                    <a class="modal-effect btn btn-outline-primary btn-block" 
                                    data-effect="effect-scale" data-toggle="modal" 
                                    href="#modaldemo8" style="font-size: 17px; width: 300px; height: 40px;">{{ __('home.AddNewCategory') }}</a>
                                </div>



                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-md-nowrap" id="example1" data-page-length="50">
                            <thead>
                                <tr>
                                    <th class="wd-10p border-bottom-0 text-center">{{ __('home.No.') }}</th>
                                    <th class="wd-20p border-bottom-0 text-center">{{ __('home.CategoryName') }}</th>
                                    <th class="wd-30p border-bottom-0 text-center">{{ __('home.Notes') }}</th>                                    
                                    <th class="wd-30p border-bottom-0 text-center">{{ __('home.Events') }}</th>
                                </tr>
                            </thead>
                            <tbody>
							<?php $i=0 ?>
								@foreach ($categories as $Category )
							<?php $i++ ?>
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td class="text-center">{{$Category->c_name}}</td>
									<td class="text-center">{{$Category->c_description}}</td>
                                    
                                        <td>
                                            <!-- تعديل -->
											<a class="modal-effect btn btn-sm btn-info  " data-effect="effect-scale" 
												data-id="{{ $Category->id }}" 
												data-c_name="{{ $Category->c_name }}" 
												
												data-c_description="{{ $Category->c_description }}" 
												data-toggle="modal" 
												href="#exampleModal2" 
												title="تعديل">
												<i class="las la-pen"></i>
												</a>


                                            	<!-- حذف -->
												<a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
												data-id="{{ $Category->id }}" 
												data-c_name="{{ $Category->c_name }}" 
												data-toggle="modal" href="#modaldemo9" title="حذف">
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
        </div>

		<!-- Modal effects -->
			<div class="modal" id="modaldemo8">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content modal-content-demo">
						<div class="modal-header">
							<h6 class="modal-title">{{ __('home.AddNewCategory') }}</h6>
							<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<form action="{{ route('categories.store') }}" method="post">
								{{ csrf_field() }}

								<div class="form-group">
									<label for="c_name">{{ __('home.CategoryName') }}</label>
									<input type="text" class="form-control" id="c_name" name="c_name" required>
								</div>
							
								
								<div class="form-group  ">
									<label for="created_by">{{ __('home.User Name') }}</label>
									<input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
								</div>

								<div class="form-group">
									<label for="c_description">{{ __('home.Notes') }}</label>
									<textarea class="form-control" id="c_description" name="c_description" rows="3"></textarea>
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
		<!-- End Modal effects-->

			
			<!-- Modal for Edit Customer -->
	<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ __('home.CategoeyEdit') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<!-- قم بتعديل action هنا ليكون فارغاً -->
					<form action="categories/update" method="POST" id="editForm">
						{{ method_field('PATCH') }}
						{{ csrf_field() }}
						<input type="hidden" name="id" id="id"> <!-- سيتم تحديثه باستخدام JavaScript -->
						<div class="form-group">
							<label for="c_name">{{ __('home.CategoryName') }}</label>
							<input type="text" class="form-control" name="c_name" id="c_name" required>
						</div>
						
						
						<div class="form-group">
							<label for="c_description">{{ __('home.Notes') }}</label>
							<textarea class="form-control" id="c_description" name="c_description" rows="2"></textarea>
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

			<!-- Modal effects for Delete -->
			<div class="modal" id="modaldemo9">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content modal-content-demo">
						<div class="modal-header">
							<h6 class="modal-title">{{ __('home.Messages') }}</h6>
							<button aria-label="Close" class="close" data-dismiss="modal" type="button">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="{{ route('categories.destroy', ':id') }}" method="post" id="deleteForm">
								@csrf
								@method('DELETE')
								
								<div class="form-group">
									<label for="c_name">{{ __('home.CategoryName') }}</label>
									<input type="text" class="form-control" name="c_name" id="c_name" required readonly>
								</div>
								
								<div class="modal-footer">
									<button type="submit" class="btn btn-danger">{{ __('home.Delete') }}</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Cancel') }}</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End Modal effects for Delete -->






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
	$('#exampleModal2').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var id = button.data('id'); // الحصول على المعرف
        var c_name = button.data('c_name'); 
        var c_width = button.data('c_width'); 
        var c_height = button.data('c_height'); 
        var c_color = button.data('c_color'); // الحصول على الهاتف
        var c_description = button.data('c_description'); // الحصول على الجوال
        var modal = $(this);
        
        // تحديث نموذج التعديل بالقيم
        modal.find('.modal-body #id').val(id); 
        modal.find('.modal-body #c_name').val(c_name); 
        modal.find('.modal-body #c_width').val(c_width); 
        modal.find('.modal-body #c_height').val(c_height); 
        modal.find('.modal-body #c_color').val(c_color); 
        modal.find('.modal-body #c_description').val(c_description); 

        // تحديث action للـ Form
        var actionUrl = "/categories/" + id; 
        modal.find('form#editForm').attr('action', actionUrl);
    });


	$('#modaldemo9').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
		var id = button.data('id'); // جلب الـ id
		var c_name = button.data('c_name'); // جلب اسم المجموعة

		// تعيين الـ action للرابط مع ID
		var modal = $(this);
		var actionUrl = '/categories/' + id;
		modal.find('#deleteForm').attr('action', actionUrl);
		modal.find('#c_name').val(c_name); // تعيين اسم المجموعة في الحقل
	});

</script>


@endsection