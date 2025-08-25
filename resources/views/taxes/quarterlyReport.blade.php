@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}">
@endsection

@section('title')
    {{ __('Tax.page_3') }}
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('Tax.ReportTax') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('Tax.ReportAllTax') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
<div class="container">
    <!-- عنوان التقرير -->
    <h2 class="mb-4 text-center text-primary">{{ __('Tax.SearchTaxReport') }}</h2>

   <!-- نموذج إدخال التواريخ -->
<form action="{{ route('taxes.quarterlyReport') }}" method="GET" class="mb-5 shadow-sm p-4 rounded bg-white">
    <div class="row justify-content-center">
        <div class="col-md-3 mb-3">
            <label for="start_date" class="form-label">{{ __('Tax.FromDate') }}</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label for="end_date" class="form-label">{{ __('Tax.ToDate') }}</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}" required>
        </div>

        <!-- أزرار -->
        <div class="col-md-6 d-flex justify-content-between mt-3">
            <!-- زر عرض التقرير -->
            <div class="col-md-5 mb-3">
                <button type="submit" class="btn btn-success w-100">{{ __('Tax.ShowReport') }}</button>
            </div>

            <!-- زر العودة إلى قائمة الضرائب -->
            <div class="col-md-5 mb-3">
                <a href="{{ route('taxes.index') }}" class="btn btn-primary w-100">{{ __('Tax.BackToTaxList') }}</a>
            </div>
        </div>
    </div>
</form>


    <!-- عرض التقرير بعد التواريخ -->
    @if(isset($startDate) && isset($endDate))
        <div class="report-summary mb-4 text-center">
            <h5 class="text-success">{{ __('Tax.PeriodFrom') }} {{ $startDate }} {{ __('Tax.To') }} {{ $endDate }}</h5>
        </div>

        <!-- تحسين الجدول -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Tax.SaleTotal') }}</th>
                        <th>{{ __('Tax.TaxSaleTotal') }}</th>
                        <th>{{ __('Tax.PurchaseTotal') }}</th>
                        <th>{{ __('Tax.TaxPurchaseTotal') }}</th>
                        <th>{{ __('Tax.TotalTax') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ number_format($totalSales, 2) }} {{ __('Tax.S.R') }}</td>
                        <td>{{ number_format($totalSalesTax, 2) }} {{ __('Tax.S.R') }}</td>
                        <td>{{ number_format($totalPurchases, 2) }} {{ __('Tax.S.R') }}</td>
                        <td>{{ number_format($totalPurchasesTax, 2) }} {{ __('Tax.S.R') }}</td>
                        <td>{{ number_format($netTax, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- زر تحميل التقرير PDF -->
        <div class="text-center mt-3">
            <a href="{{ route('taxes.printReport', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                class="btn btn-outline-primary" style="font-size: 15px; padding: 10px 30px; font-weight: 500;">
                {{ __('Tax.PrintRport') }}
            </a>
        </div>
    @endif
</div>
@endsection

@section('js')
@endsection
