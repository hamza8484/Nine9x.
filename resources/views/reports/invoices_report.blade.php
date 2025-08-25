@extends('layouts.master')


@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
    {{ __('home.MainPage60') }}
@stop



@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Reports') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.SaleReport') }}</span>
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

    <!-- row -->
    <div class="row">

        <div class="col-xl-12">
            <div class="card mg-b-20">


                <div class="card-header pb-0">

                    <form action="/Search_invoices" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="col-lg-3">
                            <label class="rdiobox">
                                <input checked name="rdio" type="radio" value="1" id="type_div"> 
                                <span>{{ __('home.SearchInvoiceType') }}</span></label>
                        </div>

                        <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                            <label class="rdiobox"><input name="rdio" value="2" type="radio">
                            <span>{{ __('home.SearchInvoiceNumber') }}</span></label>
                        </div><br><br>

                        <div class="row">

                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                                <p class="mg-b-10">{{ __('home.SelectInvoiceType') }}</p>
                                <select class="form-control select2" name="type" required>
                                
                                    <option value="الكل">{{ __('home.All') }}</option>
                                    <option value="نقــدي">{{ __('home.Cash') }}</option>
                                    <option value="شــبكة">{{ __('home.Network') }}</option>
                                    <option value="آجــل">{{ __('home.Credit') }}</option>
                                </select>
                            </div>



                            <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="inv_number">
                                <p class="mg-b-10">{{ __('home.SearchByInvoiceNumber') }}</p>
                                <input type="text" class="form-control" id="inv_number" name="inv_number">
                            </div>


                            <div class="col-lg-3" id="start_at">
                                <label for="exampleFormControlSelect1">{{ __('home.FromDate') }} :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}" name="start_at" placeholder="YYYY-MM-DD" type="text">
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-3" id="end_at">
                                <label for="exampleFormControlSelect1">{{ __('home.ToDate') }} :</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control fc-datepicker" name="end_at" value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text">
                                </div><!-- input-group -->
                            </div>

                        </div><br>

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="d-flex justify-content-between">
                                    <!-- زر البحث -->
                                    <button class="btn btn-primary" style="width: 48%;">{{ __('home.Search') }}</button>
                                    
                                    <!-- زر طباعة التقرير -->
                                    <button class="btn btn-info" style="width: 48%;" onclick="printReport()">{{ __('home.PrintReport') }}</button>
                                </div>
                            </div>
                        </div>


                    </form>

                </div>
                <div class="card-body">
                    <div class="table-responsive" id="reportContent">
                        @if (isset($details))
                            <table id="example" class="table key-buttons text-md-nowrap" style=" text-align: center">
                                <thead>
                                    <tr>
                                        <th class="wd-5p border-bottom-0 text-center">{{ __('home.Sequence') }}</th>
                                        <th class="wd-15p border-bottom-0 text-center">{{ __('home.InvoiceNumber') }}</th>
                                        <th class="wd-15p border-bottom-0 text-center">{{ __('home.InvoiceDate') }}</th>
                                        <th class="wd-20p border-bottom-0 text-center">{{ __('home.Customer') }}</th>
                                        <th class="wd-10p border-bottom-0 text-center">{{ __('home.PaymentMethod') }}</th>
                                        <th class="wd-10p border-bottom-0 text-center">{{ __('home.Tax') }}</th>
                                        <th class="wd-10p border-bottom-0 text-center">{{ __('home.Discount') }}</th>
                                        <th class="wd-20p border-bottom-0 text-center">{{ __('home.Total') }}</th>
                                        <th class="wd-25p border-bottom-0 text-center">{{ __('home.Actions') }}</th>
                                    </tr>

                                </thead>
                                <tbody>
                                @foreach ($details as $invoice)
                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration }}</td>
                                        <td class="text-center" ><a href="{{ route('invoices.show',$invoice->id) }}">{{ $invoice->inv_number }}</a></td>
                                        <td class="text-center">{{ $invoice->inv_date }}</td>
                                        <td class="text-center">{{ $invoice->customer->cus_name }}</td>
                                        <td class="text-center">{{ $invoice->inv_payment }}</td>
                                        <td class="text-center">{{ number_format ($invoice->vat_value,2 )}}</td>
                                        <td class="text-center">{{ $invoice->discount_value }}</td>
                                        <td class="text-center">{{ number_format ($invoice->total_deu,2) }}</td>
                                        <td>
                                            <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        @endif
                    </div>
                    <div class="col-sm-1 col-md-1 mt-3">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();

</script>

<script>
    $(document).ready(function() {

        $('#inv_number').hide();

        $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'type_div') {
                $('#inv_number').hide();
                $('#type').show();
                $('#start_at').show();
                $('#end_at').show();
            } else {
                $('#inv_number').show();
                $('#type').hide();
                $('#start_at').hide();
                $('#end_at').hide();
            }
        });
    });
</script>

<script>
    function printReport() {
        // الحصول على المحتوى الذي سيتم طباعته
        var content = document.getElementById('reportContent').innerHTML;

        // الحصول على تاريخ الطباعة
        var printDate = new Date().toLocaleDateString('ar-SA');

        // فتح نافذة جديدة للطباعة
        var printWindow = window.open('', '_blank', 'width=900,height=700');

        // كتابة المحتوى داخل النافذة الجديدة
        printWindow.document.write(`
            <html lang="ar">
                <head>
                    <meta charset="UTF-8">
                    <title>{{ __('home.SalesInvoiceReport') }}</title>
                    <style>
                        body {
                            direction: rtl;
                            font-family: 'Tahoma', sans-serif;
                            color: #000;
                            background: #fff;
                            margin: 0;
                            padding: 40px;
                            font-size: 14px;
                            overflow: visible; /* التأكد من عدم وجود التمرير */
                        }
                        .table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                            table-layout: auto; /* يسمح بتوزيع الأعمدة */
                        }
                        th, td {
                            border: 1px solid #ccc;
                            padding: 10px;
                            text-align: center;
                            word-wrap: break-word; /* لضمان عدم انكسار النصوص داخل الخلايا */
                            font-size: 12px; /* تقليل حجم الخط */
                        }

                        /* إجبار طباعة الجدول عبر صفحات متعددة إذا لزم الأمر */
                        .table tr {
                            page-break-inside: auto;
                        }

                        .table thead {
                            display: table-header-group;
                        }

                        .table tfoot {
                            display: table-footer-group;
                        }

                        /* إخفاء الأزرار والعناصر غير المرغوب فيها عند الطباعة */
                        button, .btn, .breadcrumb-header, .dataTables_info, .dataTables_paginate {
                            display: none;
                        }

                        /* تنسيق عنوان التقرير وتاريخ الطباعة */
                        .report-header {
                            text-align: center;
                            margin-bottom: 20px;
                            font-size: 18px;
                        }

                        .print-date {
                            text-align: center;
                            font-size: 14px;
                            margin-bottom: 20px;
                        }

                        /* إعدادات الصفحة للطباعة */
                        @page {
                            size: A4; /* حجم الصفحة A4 */
                            margin: 20mm; /* الهوامش */
                        }

                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    <div class="report-header">
                        <h2>{{ __('home.AllInvoicesReport') }}</h2>
                    </div>
                    <div class="print-date">
                        <p>{{ __('home.PrintDate') }}: ${printDate}</p>
                    </div>
                    ${content}
                </body>
            </html>
        `);

        printWindow.document.close();
    }
</script>
@endsection
