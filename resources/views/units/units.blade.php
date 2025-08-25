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
    {{ __('home.MainPage10') }}
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Units') }}</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.AddUnits') }}</span>
            </div>
        </div>
    </div>
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
                href="#modaldemo8" style="font-size: 17px; width: 300px; height: 40px;">{{ __('home.AddNewUnit') }}</a>
            </div>
            <div class="card-body">
                <table id="example1" class="table key-buttons text-md-nowrap table-striped" data-page-length='50'>
                    <thead>
                        <tr>
                            <th class="border-bottom-0 text-center" style="width: 20%;">{{ __('home.No.') }}</th>
                            <th class="border-bottom-0 text-center" style="width: 50%;">{{ __('home.UnitName') }}</th>
                            <th class="border-bottom-0 text-center" style="width: 30%;">{{ __('home.Events') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $unit->unit_name }}</td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                       data-id="{{ $unit->id }}" data-unit_name="{{ $unit->unit_name }}" data-toggle="modal" 
                                       href="#exampleModal2" title="تعديل">
                                        <i class="las la-pen"></i>
                                    </a>

                                    <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" 
                                       data-id="{{ $unit->id }}" 
									   data-unit_name="{{ $unit->unit_name }}"
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

    <!-- Modal لإضافة وحدة -->
    <div class="modal" id="modaldemo8">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('home.AddUnit') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('units.store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="unit_name">{{ __('home.UnitName') }}</label>
                            <input type="text" class="form-control" id="unit_name" name="unit_name" required>
                        </div>

                        <div class="form-group">
                            <label for="created_by">{{ __('home.UsreName') }}</label>
                            <input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">{{ __('home.Save') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Close') }}</button>
                </div>
                    </form>
            </div>
        </div>
    </div>

    <!-- Modal لتعديل وحدة -->
    <div class="modal" id="exampleModal2">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('home.EditUnit') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="editForm">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="unit_name">{{ __('home.UnitName') }}</label>
                            <input type="text" class="form-control" id="unit_name" name="unit_name" required>
                        </div>

                        <div class="form-group">
                            <label for="created_by">{{ __('home.UsreName') }}</label>
                            <input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">{{ __('home.EditUnit') }}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لحذف وحدة -->
    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('home.DeleteUnit') }}</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="deleteForm">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <div class="form-group">
                            <label for="unit_name">{{ __('home.UnitName') }}</label>
                            <input type="text" class="form-control" name="unit_name" id="unit_name" required readonly>
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
    // تحديث الرابط في modal التعديل
    $('#exampleModal2').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var unitName = button.data('unit_name');
        var unitId = button.data('id');

        var modal = $(this);
        modal.find('#unit_name').val(unitName);
        modal.find('form').attr('action', '/units/' + unitId);
    });

    // تحديث الرابط في modal الحذف
    $('#modaldemo9').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var unitName = button.data('unit_name');
        var unitId = button.data('id');

        var modal = $(this);
        modal.find('#unit_name').val(unitName);
        modal.find('form').attr('action', '/units/' + unitId);
    });
</script>

@endsection
