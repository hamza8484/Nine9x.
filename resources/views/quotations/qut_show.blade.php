@extends('layouts.master')

@section('css')
<!-- إضافة CSS حديث لجعل التصميم أكثر جمالاً -->
<style>
    /* استخدام خلفيات وتصاميم عصرية */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f7fc;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .card-header {
        background: linear-gradient(145deg, #4e73df, #2e59d9);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 15px 25px;
    }

    .card-header h2 {
        font-size: 22px;
        font-weight: 600;
    }

    .btn {
        font-weight: 500;
        border-radius: 25px;
        padding: 10px 25px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }

    

    .table {
        border-radius: 15px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .table th, .table td {
        padding: 15px;
        text-align: center;
        font-size: 16px;
    }

    .table th {
        background-color: #f7f7f7;
        font-weight: 600;
        color: #333;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table-info {
        font-size: 18px;
        background-color: #e9f7ef;
    }

    .final-total {
        font-size: 18px;
        color: #28a745;
        font-weight: bold;
    }

    .table-primary {
        background-color: #f0f8ff;
    }

    .table-info td {
        color: #333;
    }

    .container-fluid {
        padding: 30px;
    }

    /* تحسين الأزرار مع تأثيرات حركة */
    .btn-lg {
        font-size: 16px;
        padding: 12px 25px;
    }
</style>
@endsection

@section('title')
 {{ __('home.MainPage23') }}
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="m-0">{{ __('home.QuotationShowNo') }} - {{ $quotation->qut_number }}</h2>
                    <a href="{{ route('quotations.index') }}" class="btn btn-light"><i class="fa fa-arrow-left"></i> {{ __('home.BackQuotations') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>{{ __('home.QuotationNumber') }}</th>
                                <td>{{ $quotation->qut_number }}</td>
                                <th>{{ __('home.QuotationDate') }}</th>
                                <td>{{ $quotation->qut_date }}</td>
                                <th>{{ __('home.Customers') }}</th>
                                <td>{{ $quotation->customer->cus_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('home.Store') }}</th>
                                <td>{{ $quotation->store->store_name }}</td>
                                <th>{{ __('home.Status') }}</th>
                                <td>{{ $quotation->qut_status }}</td>
                                <th>{{ __('home.User Name') }}</th>
                                <td>{{ auth()->user()->name }}</td>
                            </tr>
                        </table>

                        <h3 class="mt-4 mb-3">{{ __('home.QuotationDetails') }}</h3>
                        <hr>
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th style="text-align: center; width: 3%;"> {{ __('home.No.') }}</th>
                                    <th style="text-align: center; width: 13%;">{{ __('home.Categoey') }}</th>
                                    <th style="text-align: center; width: 16%;">{{ __('home.Product') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Units') }}</th>
                                    <th style="text-align: center; width: 17%;">{{ __('home.Barcode') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Qty') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Price') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Discount') }}</th>
                                    <th style="text-align: center; width: 15%;">{{ __('home.Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotation->detailsQuotations as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->category->c_name }}</td>
                                        <td>{{ $item->product->product_name }}</td>
                                        <td>{{ $item->unit->unit_name }}</td>
                                        <td>{{ $item->product_barcode }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->product_price }}</td>
                                        <td>{{ $item->product_discount }}</td>
                                        <td>{{ $item->total_price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center">{{ __('home.InvoiceTotal') }}</td>
                                    <td>{{ $quotation->sub_total }} {{ __('home.currency_symbol') }}</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center">{{ __('home.DiscountType') }}</td>
                                    <td>{{ $quotation->discount_value }} {{ __('home.currency_symbol') }}</td>
                                </tr>
                               
                                <tr class="table-info">
                                    <td colspan="4" class="text-center">{{ __('home.TotalVAT') }}</td>
                                    <td>{{ $quotation->vat_value }} {{ __('home.currency_symbol') }}</td>
                                </tr>
                                <tr class="table-info final-total">
                                    <td colspan="4" class="text-center">{{ __('home.FinalQuotationTotal') }}</td>
                                    <td>{{ $quotation->total_deu }} {{ __('home.currency_symbol') }}</td>
                                </tr>
                                <tr class="table-info text-end">
                                    <td colspan="4" class="text-center">{{ __('home.Notes') }}</td>
                                    <td>{{ $quotation->qut_notes }}</td>
                                </tr>
                               
                            </tfoot>
                        </table>
                    </div>
                    <!--
                        <div class="row justify-content-end">
                            <div class="col-12 text-center">
                                <a href="{{route('quotations.print',$quotation->id) }}" class="btn btn-primary btn-lg mx-2"><i class="fa fa-print"></i> طباعــة</a>
                                <a href="{{route('quotations.edit',$quotation->id) }}" class="btn btn-secondary btn-lg mx-2"><i class="fa fa-file-pdf"></i> PDF </a>
                                <a href="#" class="btn btn-success btn-lg mx-2"><i class="fa fa-envelope"></i> إرسال الى البريد الإلكتروني</a>
                            </div>
                        </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
