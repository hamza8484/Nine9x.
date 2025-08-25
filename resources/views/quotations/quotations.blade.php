@extends('layouts.master')

@section('css')
@endsection

@section('title')
    {{ __('home.MainPage20') }}
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Quotations') }}</h4><span class="text-muted mt-1 tx-17 mr-2 mb-0">/ {{ __('home.QuotationList') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('quotations.create') }}" class="btn btn-success">{{ __('home.AddNewQuotation') }}</a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap" id="example1" data-page-length='50'>
                        <thead>
                            <tr>
                                <th class="wd-5p border-bottom-0 text-center">{{ __('home.No.') }}</th>
                                <th class="wd-15p border-bottom-0 text-center">{{ __('home.QuotationNumber') }}</th>
                                <th class="wd-15p border-bottom-0 text-center">{{ __('home.QuotationDate') }}</th>
                                <th class="wd-20p border-bottom-0 text-center">{{ __('home.Customers') }}</th>
                                <th class="wd-10p border-bottom-0 text-center">{{ __('home.VAT') }}</th>
                                <th class="wd-10p border-bottom-0 text-center">{{ __('home.Total') }}</th>
                                <th class="wd-10p border-bottom-0 text-center">{{ __('home.Status') }}</th>
                                <th class="wd-20p border-bottom-0 text-center">{{ __('home.Events') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotations as $index => $quotation)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    
                                    <td class="text-center" ><a href="{{ route('quotations.show',$quotation->id) }}">{{ $quotation->qut_number }}</a></td>
                                    <td class="text-center">{{ $quotation->qut_date }}</td>
                                    <td class="text-center">{{ $quotation->customer->cus_name }}</td>
                                    <td class="text-center">{{ $quotation->vat_value }}</td>
                                    <td class="text-center">{{ number_format($quotation->total_deu, 2) }} {{ __('home.currency_symbol') }}</td>
                                    <td class="text-center" id="status-{{ $quotation->id }}">
                                        {{ $quotation->qut_status }}
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="{{route('quotations.edit',$quotation->id) }}" class="btn btn-primary btn-sm" ><i class="fa  fa-edit" ></i></a>
                                        <a href="{{route('quotations.print',$quotation->id) }}" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="text-center" style="font-size: 20px; font-weight: bold;">
                                <strong>{{ __('home.TotalAllInvoices') }}</strong>
                            </td>
                            <td class="text-center" style="font-size: 17px; font-weight: bold;" id="total-vat">0 {{ __('home.currency_symbol') }}</td>
                            <td class="text-center" style="font-size: 17px; font-weight: bold;" id="total-sum">0 {{ __('home.currency_symbol') }}</td>
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
        max-width: 90%; /* يمكنك تعديل هذه القيمة حسب الحاجة */
    }

    /* إضافة الألوان حسب حالة العرض */
    .status-sari {
        color: green;
        font-weight: bold;
    }

    .status-muntahi {
        color: orange;
        font-weight: bold;
    }

    .status-intizar {
        color: blue;
        font-weight: bold;
    }

    .status-malgi {
        color: red;
        font-weight: bold;
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
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>

<!-- المجاميع -->
<script>
    $(document).ready(function() {
        var table = $('#example1').DataTable();

        // Function to update the totals
        function updateTotals() {
            var totalVat = 0;
            var totalSum = 0;

            table.rows().every(function() {
                var data = this.data();
                totalVat += parseFloat(data[4] || 0); // vat_value column index
                totalSum += parseFloat(data[5] || 0); // total_deu column index
            });

            // تعيين النص واللون للعناصر وجعلها بالخط العريض (بولد)
            $('#total-vat').text(totalVat.toFixed(2)).css('color', 'green').css('font-weight', 'bold');  // الضريبة باللون الأخضر وبخط عريض
            $('#total-sum').text(totalSum.toFixed(2)).css('color', 'green').css('font-weight', 'bold');  // المجموع باللون الأخضر وبخط عريض
        }

        // Update totals on page load and whenever the table is drawn
        table.on('draw', function() {
            updateTotals();
        });

        // Initial calculation
        updateTotals();
    });

    // تغيير لون حالة العرض حسب القيمة
    $(document).ready(function() {
        @foreach($quotations as $quotation)
            var status = "{{ $quotation->qut_status }}";
            var statusElement = $("#status-{{ $quotation->id }}");

            if (status === 'ساري') {
                statusElement.addClass('status-sari');
            } else if (status === 'منتهي') {
                statusElement.addClass('status-muntahi');
            } else if (status === 'إنتظار') {
                statusElement.addClass('status-intizar');
            } else if (status === 'ملغي') {
                statusElement.addClass('status-malgi');
            }
        @endforeach
    });
</script>
@endsection
