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
   {{ __('home.MainPage18') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h3 class="content-title mb-0 my-auto">{{ __('home.ReturnPurchase') }}</h3><span class="text-muted mt-1 tx-20 mr-2 mb-0">/ {{ __('home.ReturnPurchaseList') }}</span>
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
                <div class="d-flex justify-content-between">
                    <a href="{{ route('ret_purchases.create') }}" class="btn btn-success">{{ __('home.AddReturnPurchase') }}</a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap display nowrap" id="example1" data-page-length='50'>
                        <thead>
                            <tr>
                                <th class="wd-5p border-bottom-0 text-center">{{ __('home.No.') }}</th>
                                <th class="wd-15p border-bottom-0 text-center">{{ __('home.ReturnNo') }}</th>
                                <th class="wd-14p border-bottom-0 text-center">{{ __('home.Date') }}</th>
                                <th class="wd-13p border-bottom-0 text-center">{{ __('home.PurchaseNo') }}</th>
                                <th class="wd-19p border-bottom-0 text-center">{{ __('home.Supplier') }}</th>
                                <th class="wd-10p border-bottom-0 text-center">{{ __('home.Vat') }}</th>
                                <th class="wd-10p border-bottom-0 text-center">{{ __('home.Amount') }}</th>
                                <th class="wd-18p border-bottom-0 text-center">{{ __('home.Events') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($ret_purchase as $retPurchase)
                            <tr>
                                <td class="text-center"> {{ $loop->iteration }}</td>
                                <td class="text-center"><a href="{{ route('ret_purchases.show', $retPurchase->id) }}">{{ $retPurchase->ret_pur_number }}</a></td>
                                <td class="text-center">{{ $retPurchase->ret_pur_date }}</td>
                                <td class="text-center">{{ $retPurchase->purchase->pur_number }}</td>
                                <td class="text-center">{{ $retPurchase->supplier->sup_name ?? 'غير موجود' }}</td>  
                                <td class="text-center">{{ number_format ($retPurchase->vat_value,2) }}%</td>
                                <td class="text-center">{{ number_format ($retPurchase->total_deu,2) }} </td>
                                <td>
                                    <a href="{{ route('ret_purchases.edit', $retPurchase->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('ret_purchases.print', $retPurchase->id) }}" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>

                                    <!-- نموذج الحذف -->
                                    <form id="delete-{{ $retPurchase->id }}" action="{{ route('ret_purchases.destroy', $retPurchase->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <a href="javascript:void(0)" onclick="if (confirm('{{ __('home.Messages') }}')) { document.getElementById('delete-{{ $retPurchase->id }}').submit(); } else { return false; }" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-center" style="font-size: 20px; font-weight: bold;">
                                    <strong>{{ __('home.TotalAllReturns') }}</strong>
                                </td>
                                <td class="text-center" style="font-size: 17px; font-weight: bold;" id="total-vat">{{ __('home.S.R') }}</td>
                                <td class="text-center" style="font-size: 17px; font-weight: bold;" id="total-sum">{{ __('home.S.R') }}</td>
                                <td class="text-center"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-dialog {
        max-width: 90%;
    }
</style>
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

<!-- المجاميع -->
<script>
    $(document).ready(function() {
        var table = $('#example1').DataTable({
            scrollX: true,             // تمكين السحب الأفقي
            scrollY: '400px',          // تمكين السحب العمودي بارتفاع معين
            scrollCollapse: true,      // تقليص الجدول عند الحاجة
            paging: true,              // تفعيل الترقيم
            responsive: true,
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json"
            }
        });

        // Function to update the totals
        function updateTotals() {
            var totalVat = 0;
            var totalSum = 0;

            table.rows().every(function() {
                var data = this.data();
                totalVat += parseFloat(data[5] || 0); 
                totalSum += parseFloat(data[6] || 0); 
            });

            // تعيين النص واللون للعناصر وجعلها بالخط العريض (بولد)
            $('#total-vat').text(totalVat.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' ريال').css('color', 'green').css('font-weight', 'bold');  // الضريبة باللون الأخضر وبخط عريض
            $('#total-sum').text(totalSum.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' ريال').css('color', 'green').css('font-weight', 'bold');  // المجموع باللون الأخضر وبخط عريض
        }

        // Update totals on page load and whenever the table is drawn
        table.on('draw', function() {
            updateTotals();
        });

        // Initial calculation
        updateTotals();
    });
</script>

@endsection
