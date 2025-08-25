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
 {{ __('Tax.page_2') }} 
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h5 class="content-title mb-0 my-auto"> {{ __('Tax.ProgramSetting') }}</h5><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('Tax.Add Taxes') }} </span>
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

    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <a class="modal-effect btn btn-outline-primary btn-block" 
                        data-effect="effect-scale" data-toggle="modal" 
                        href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;" >{{ __('Tax.New Tax') }}</a>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                        <a href="{{ route('taxes.quarterlyReport') }}" class="btn btn-info">
                        {{ __('Tax.ShowReport') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1" data-page-length='50'>
                            <thead>
                                <tr>
                                    <th class="wd-8p border-bottom-0 text-center">{{ __('Tax.No.') }}</th>
                                    <th class="wd-25p border-bottom-0 text-center">{{ __('Tax.TaxName') }} </th> 
                                    <th class="wd-25p border-bottom-0 text-center">{{ __('Tax.TaxRate') }}</th>
                                    <th class="wd-20p border-bottom-0 text-center">{{ __('Tax.UsreName') }}</th>
                                    <th class="wd-20p border-bottom-0 text-center">{{ __('Tax.CreatDate') }}</th>
                                    <th class="wd-15p border-bottom-0 text-center">{{ __('Tax.Events') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0 ?>
                                @foreach($taxes as $x)
                                    <?php $i++ ?>
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ $x->tax_name }}</td> <!-- عرض اسم الضريبة -->
                                        <td class="text-center">{{ $x->tax_rate }}</td>
                                        <td class="text-center">{{ $x->user->name }}</td> <!-- عرض اسم المستخدم -->
                                        <td class="text-center">{{ $x->created_at->format('Y-m-d H:i') }}</td> <!-- عرض تاريخ الإنشاء -->
                                        <td>
                                            <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                            data-id="{{ $x->id }}" data-tax_name="{{ $x->tax_name }}" data-tax_rate="{{ $x->tax_rate }}"
                                            data-toggle="modal" href="#exampleModal2" title="{{ __('Edit') }}">
                                            <i class="las la-pen"></i>
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

        <!-- Basic modal لإضافة الضريبة -->
        <div class="modal" id="modaldemo8">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ __('Tax.AddTax') }}</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @if ($taxes->isEmpty())  <!-- إذا كانت الضريبة غير موجودة، يظهر النموذج -->
                        <form action="{{ route('taxes.store') }}" method="post" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="tax_name">{{ __('Tax.TaxName') }} </label>
                                    <input type="text" name="tax_name" id="tax_name" class="form-control" value="{{ old('tax_name') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="tax_rate">{{ __('Tax.TaxRate') }} </label>
                                    <input type="number" name="tax_rate" id="tax_rate" class="form-control" value="{{ old('tax_rate') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="user_id">{{ __('Tax.UsreName') }}</label>
                                    <input type="text" class="form-control form-control-sm" id="user_id" name="user_id" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px;">{{ __('home.Save') }}</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Tax.Close') }}</button>
                            </div>
                        </form>
                        @else
                            <p class="alert-red">{{ __('Tax.tax_added') }}</p>
                        @endif
                        <style>
                            .alert-red {
                                color: red;
                            }
                        </style>

                    </div>
                </div>
            </div>
        </div>

    <!-- edit -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ __('Tax.EditTax')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" action="{{ route('taxes.update', ':tax_id') }}" method="POST" autocomplete="off">
                            @method('PATCH')
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="hidden" name="id" id="id" value="">
                                <label for="tax_name" class="col-form-label"> {{ __('Tax.TaxName') }}</label>
                                <input class="form-control" name="tax_name" id="tax_name" type="text">
                            </div>
                            <div class="form-group">
                                <label for="tax_rate" class="col-form-label">{{ __('Tax.TaxRate') }}</label>
                                <input class="form-control" name="tax_rate" id="tax_rate" type="text">
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="user_id">{{ __('Tax.UsreName') }}</label>
                                    <input type="text" class="form-control form-control-sm" id="user_id" name="user_id" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" style="font-size: 15px; width: 300px; height: 40px;">{{ __('Tax.Confirm') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Tax.Close') }}</button>
                    
                    </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- edit end -->


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
<!-- Internal Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>

<script>
    $('#exampleModal2').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // الزر الذي فتح الـ modal
    var taxId = button.data('id'); // الحصول على المعرف (id) للضريبة
    var taxName = button.data('tax_name'); // الحصول على اسم الضريبة
    var taxRate = button.data('tax_rate'); // الحصول على نسبة الضريبة

    // تحديث الـ action في الفورم (إضافة tax_id إلى الرابط)
    var form = $(this).find('form');
    var actionUrl = form.attr('action').replace(':tax_id', taxId); // استبدال :tax_id بالـ taxId الفعلي
    form.attr('action', actionUrl);

    // ملء الحقول بالقيم الحالية
    $('#id').val(taxId);
    $('#tax_name').val(taxName);
    $('#tax_rate').val(taxRate);
});


</script>
@endsection
