@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">

<style>
    
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>


@endsection

@section('title')
 {{ __('home.MainPage25') }}
@stop
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h3 class="content-title mb-0 my-auto">{{ __('home.InvoicesList') }}</h3><span class="text-muted mt-1 tx-19 mr-2 mb-0">/ {{ __('home.AddNewInvoices') }}</span>
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
        <div class="card mg-xl-20">
            <div class="card-header pb-0">
                <a href="{{ route('invoices.create') }}" class="modal-effect btn btn-primary me-1" style="color:white">
                    {{ __('home.AddInvoice') }}
                </a>
                <a href="{{ url('export_Invoice') }}" class="modal-effect btn btn-primary" style="color:white">
                    <i class="fas fa-file-download"></i>&nbsp;{{ __('home.ExportInvoice') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                <table class="table text-md-nowrap display nowrap" id="example1" data-page-length='50'>
                        <thead>
                            <tr>
                                <th class="wd-5p border-bottom-0 text-center">{{ __('home.No.') }}</th>
                                <th class="wd-15p border-bottom-0 text-center">{{ __('home.InvoiceNo') }}</th>
                                <th class="wd-15p border-bottom-0 text-center">{{ __('home.Date') }}</th>
                                <th class="wd-20p border-bottom-0 text-center">{{ __('home.Customers') }}</th>
                                <th class="wd-10p border-bottom-0 text-center">{{ __('home.VAT') }}</th>
                                <th class="wd-20p border-bottom-0 text-center">{{ __('home.Total') }}</th>
                                <th class="wd-25p border-bottom-0 text-center">{{ __('home.Events') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="text-center"> {{ $loop->iteration }}</td>
                                    <td class="text-center" ><a href="{{ route('invoices.show',$invoice->id) }}">{{ $invoice->inv_number }}</a></td>
                                    <td class="text-center">{{ $invoice->inv_date }}</td>
                                    <td class="text-center">{{ $invoice->customer->cus_name }}</td>
                                    <td class="text-center">{{ number_format ($invoice->vat_value,2) }}</td>
                                    <td class="text-center">{{ number_format ($invoice->total_deu,2) }}</td>
                                    <td >
                                   <div class="dropdown">
                                        <button aria-expanded="false" aria-haspopup="true"
                                            class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                            type="button">{{ __('home.Events') }}<i class="fas fa-caret-down ml-1"></i></button>
                                            <div class="dropdown-menu tx-13">
                                                <a href="{{ route('invoices.edit',$invoice->id) }}" class="btn btn-primary btn-sm" ><i class="fa  fa-edit" ></i></a>
                                                <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>
                                                <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-secondary btn-sm">
                                                    <i class="fa fa-file-pdf"></i>
                                                </a>
                                                <a href="javascript:void(0)" onclick="if (confirm('{{ __('هل أنت متأكد من حذف الفاتورة') }}')) { document.getElementById('delete-{{ $invoice->id }}').submit(); } else { return false; }" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </a>

                                                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" id="delete-{{ $invoice->id }}" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                            </div>
                                    </div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="text-center" style="font-size: 20px; font-weight: bold;">
                                <strong>{{ __('home.TotalAllInvoices') }}</strong>
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
    $(document).ready(function () {
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

        // حساب المجاميع
        function updateTotals() {
            var totalVat = 0;
            var totalSum = 0;

            table.rows({ search: 'applied' }).every(function () {
                var data = this.data();
                totalVat += parseFloat(data[4] || 0); // الضريبة
                totalSum += parseFloat(data[5] || 0); // الإجمالي
            });

            $('#total-vat').text(totalVat.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' {{ __('home.S.R') }}')
                .css('color', 'green').css('font-weight', 'bold');

            $('#total-sum').text(totalSum.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' {{ __('home.S.R') }}')
                .css('color', 'green').css('font-weight', 'bold');
        }

        table.on('draw', function () {
            updateTotals();
        });

        updateTotals(); // مرة عند تحميل الصفحة
    });
</script>

@endsection
