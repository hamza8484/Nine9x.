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
    {{ __('home.MainPage5') }} 
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Program Setting') }}</h4><span class="text-muted mt-1 tx-19 mr-2 mb-0">/ {{ __('home.Branchs') }}</span>
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


<div class="col-xl-12">
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <a class="modal-effect btn btn-outline-primary btn-block" 
                   data-effect="effect-scale" data-toggle="modal" 
                   href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;">{{ __('home.Add Branchs') }}</a>
                <i class="mdi mdi-dots-horizontal text-gray"></i>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table text-md-nowrap" id="example1" data-page-length='50'>
                    <thead>
                        <tr>
                            <th class="wd-5p border-bottom-0 text-center">{{ __('home.No.') }}</th>
                            <th class="wd-25p border-bottom-0 text-center">{{ __('home.Name Branch') }}</th>
                            <th class="wd-20p border-bottom-0 text-center">{{ __('home.Type Branch') }}</th>
                            <th class="wd-25p border-bottom-0 text-center">{{ __('home.CreatBy') }}</th>
                            <th class="wd-25p border-bottom-0 text-center">{{ __('home.Manager') }}</th>
                            <th class="wd-10p border-bottom-0 text-center">{{ __('home.PhoneManager') }}</th>
                            <th class="wd-10p border-bottom-0 text-center">{{ __('home.Status') }}</th>
                            <th class="wd-15p border-bottom-0 text-center">{{ __('home.Events') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0 ?>
                        @foreach($branchs as $branch)
                            <?php $i++ ?>
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $branch->bra_name }}</td>  <!-- عرض اسم الفرع -->
                                <td class="text-center">{{ $branch->bra_type }}</td>  <!-- عرض نوع الفرع -->   
                                <td class="text-center">{{ $branch->user->name }}</td>  <!-- عرض اسم منشئ الفرع -->
                                <td class="text-center">{{ $branch->bra_manager }}</td>  <!-- عرض اسم مدير الفرع -->
                                <td class="text-center">{{ $branch->bra_manager_phone }}</td>  <!-- عرض رقم جوال مدير الفرع -->
                                <td class="text-center">
                                    @if($branch->is_active)
                                        <span class="badge badge-success">{{ __('home.Active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('home.Inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
									<a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
										data-id="{{ $branch->id }}" 
										data-bra_name="{{ $branch->bra_name }}" 
										data-bra_type="{{ $branch->bra_type }}" 
										data-bra_address="{{ $branch->bra_address }}" 
										data-bra_phone="{{ $branch->bra_phone }}" 
										data-bra_manager="{{ $branch->bra_manager }}" 
										data-bra_manager_phone="{{ $branch->bra_manager_phone }}" 
										data-bra_telephon="{{ $branch->bra_telephon }}" 
										data-branch_notes="{{ $branch->branch_notes }}"
										data-is_active="{{ $branch->is_active }}"
										data-toggle="modal" href="#exampleModal2" title="تعديل">
										<i class="las la-pen"></i>
									</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

<!-- Modal لإضافة الفرع -->
<div class="modal" id="modaldemo8">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content modal-content-demo">
					<div class="modal-header">
						<h6 class="modal-title"> {{ __('home.Add Branchs') }}</h6>
						<button aria-label="Close" class="close" data-dismiss="modal" type="button">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						<!-- نموذج إضافة الفرع -->
						<form action="{{ route('branches.store') }}" method="POST" autocomplete="off">
							@csrf
							<div class="row">
								<div class="form-group col-md-5">
									<label for="bra_name"> {{ __('home.BranchName') }}</label>
									<input type="text" name="bra_name" id="bra_name" class="form-control" value="{{ old('bra_name') }}" required>
								</div>
								<div class="form-group col-md-3">
									<label for="bra_type">{{ __('home.Type Branch') }}</label>
									<select name="bra_type" id="bra_type" class="form-control" required>
										<option value="رئيسي" {{ old('bra_type') == 'رئيسي' ? 'selected' : '' }}>{{ __('home.Main') }}</option>
										<option value="فرعي" {{ old('bra_type') == 'فرعي' ? 'selected' : '' }}>{{ __('home.Sub') }}</option>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label for="bra_phone">{{ __('home.TelephneBranch') }}</label>
									<input type="text" name="bra_phone" id="bra_phone" class="form-control" value="{{ old('bra_phone') }}" required>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-md-12">
									<label for="bra_address">{{ __('home.AddressBranch') }}</label>
									<input type="text" name="bra_address" id="bra_address" class="form-control" value="{{ old('bra_address') }}" required>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-md-6">
									<label for="bra_manager">{{ __('home.Manager') }}</label>
									<input type="text" name="bra_manager" id="bra_manager" class="form-control" value="{{ old('bra_manager') }}" required>
								</div>
								<div class="form-group col-md-6">
									<label for="bra_manager_phone">{{ __('home.PhoneManager') }}</label>
									<input type="text" name="bra_manager_phone" id="bra_manager_phone" class="form-control" value="{{ old('bra_manager_phone') }}" required>
								</div>
							</div>

							<!-- إضافة الحقول المفقودة هنا -->
							<div class="row">
								<div class="form-group col-md-12">
									<label for="bra_telephon">{{ __('home.Phone') }}</label>
									<input type="text" name="bra_telephon" id="bra_telephon" class="form-control" value="{{ old('bra_telephon') }}">
								</div>
							</div>

							<div class="row">
								<div class="form-group col-md-12">
									<label for="branch_notes">{{ __('home.Notes') }}</label>
									<textarea name="branch_notes" id="branch_notes" class="form-control">{{ old('branch_notes') }}</textarea>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-md-6">
									<label for="is_active">{{ __('home.Status') }}</label>
									<select name="is_active" id="is_active" class="form-control" required>
										<option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>{{ __('home.Active') }}</option>
										<option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>{{ __('home.Inactive') }}</option>
									</select>
								</div>

								<div class="form-group col-md-6">
									<label for="user_id">{{ __('home.UsreName') }}</label>
									<input type="hidden" name="user_id" value="{{ Auth::user()->id }}"> <!-- إضافة الحقل المخفي لإرسال المعرف -->
									<input type="text" class="form-control form-control-sm" id="user_id" value="{{ Auth::user()->name }}" readonly>
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
</div>

		<!-- Modal لتعديل الفرع -->
<div class="modal" id="exampleModal2">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title"> {{ __('home.EditBranch') }}</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

			<div class="modal-body">
				<form action="{{ route('branches.update', ['branch' => ':id']) }}" method="POST" autocomplete="off">
					@csrf
					@method('PUT')

					<input type="hidden" name="id" id="branch_id"> <!-- حقل مخفي لمعرف الفرع -->

					<div class="row">
						<div class="form-group col-md-5">
							<label for="bra_name">{{ __('home.BranchName') }}</label>
							<input type="text" name="bra_name" id="bra_name" class="form-control" value="{{ old('bra_name') }}" required>
						</div>
						<div class="form-group col-md-3">
							<label for="bra_type">{{ __('home.Type Branch') }}</label>
							<select name="bra_type" id="bra_type" class="form-control" required>
								<option value="رئيسي" {{ old('bra_type') == 'رئيسي' ? 'selected' : '' }}>
									{{ __('home.Main') }}
								</option>
								<option value="فرعي" {{ old('bra_type') == 'فرعي' ? 'selected' : '' }}>
									{{ __('home.Sub') }}
								</option>
							</select>
						</div>

						<div class="form-group col-md-4">
							<label for="bra_phone">{{ __('home.TelephneBranch') }}</label>
							<input type="text" name="bra_phone" id="bra_phone" class="form-control" value="{{ old('bra_phone') }}" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-12">
							<label for="bra_address">{{ __('home.AddressBranch') }}</label>
							<input type="text" name="bra_address" id="bra_address" class="form-control" value="{{ old('bra_address') }}" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-6">
							<label for="bra_manager">{{ __('home.Manager') }}</label>
							<input type="text" name="bra_manager" id="bra_manager" class="form-control" value="{{ old('bra_manager') }}" required>
						</div>
						<div class="form-group col-md-6">
							<label for="bra_manager_phone">{{ __('home.PhoneManager') }}</label>
							<input type="text" name="bra_manager_phone" id="bra_manager_phone" class="form-control" value="{{ old('bra_manager_phone') }}" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-12">
							<label for="bra_telephon">{{ __('home.Phone') }}</label>
							<input type="text" name="bra_telephon" id="bra_telephon" class="form-control" value="{{ old('bra_telephon') }}">
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-12">
							<label for="branch_notes">{{ __('home.Notes') }}</label>
							<textarea name="branch_notes" id="branch_notes" class="form-control">{{ old('branch_notes') }}</textarea>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-6">
							<label for="is_active">{{ __('home.Status') }}</label>
							<select name="is_active" id="is_active" class="form-control" required>
								<option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>{{ __('home.Active') }}</option>
								<option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>{{ __('home.Inactive') }}</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="user_id">{{ __('home.UsreName') }}</label>
							<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
							<input type="text" class="form-control form-control-sm" id="user_id" value="{{ Auth::user()->name }}" readonly>
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
<!-- Internal Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>

<script>
    // عند فتح الـ modal
    $('#exampleModal2').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // الزر الذي قام بتشغيل الـ modal
        var id = button.data('id');
        var bra_name = button.data('bra_name');
        var bra_type = button.data('bra_type');
        var bra_address = button.data('bra_address');
        var bra_phone = button.data('bra_phone');
        var bra_manager = button.data('bra_manager');
        var bra_manager_phone = button.data('bra_manager_phone');
		var bra_telephon = button.data('bra_telephon');
		var branch_notes = button.data('branch_notes');
        var is_active = button.data('is_active');

        // تحديث الحقول داخل الـ modal
        var modal = $(this);
        modal.find('.modal-body #bra_name').val(bra_name);
        modal.find('.modal-body #bra_type').val(bra_type);
        modal.find('.modal-body #bra_address').val(bra_address);
        modal.find('.modal-body #bra_phone').val(bra_phone);
        modal.find('.modal-body #bra_manager').val(bra_manager);
        modal.find('.modal-body #bra_manager_phone').val(bra_manager_phone);
		modal.find('.modal-body #bra_telephon').val(bra_telephon);
		modal.find('.modal-body #branch_notes').val(branch_notes);
        modal.find('.modal-body #is_active').val(is_active);

        // تحديث رابط التعديل ليشمل الـ id الصحيح
        modal.find('form').attr('action', '/branches/' + id);

        // تحديث قيمة الـ hidden user_id إذا كان يجب تحديدها في عملية التعديل
        modal.find('.modal-body #user_id').val('{{ Auth::user()->id }}');
    });
</script>


@endsection
