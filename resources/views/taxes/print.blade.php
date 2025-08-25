@extends('layouts.master')

@section('title')
    {{ __('Tax.page_1') }} 
@stop

@section('css')
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f7f7f7;
        }

        .report-container {
            background-color: #fff;
            padding: 40px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: 'Tahoma', sans-serif;
            margin-top: 30px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .report-header h1 {
            font-size: 28px;
            color: #2c3e50;
        }

        .report-header h5 {
            margin-top: 10px;
            color: #555;
            font-size: 18px;
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .company-info {
            text-align: right;
            font-size: 14px;
            line-height: 1.8;
        }

        .company-logo img {
            width: 300px;
            height: 180px;
            object-fit: contain;
        }

        .vat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 15px;
        }

        .vat-table th, .vat-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .vat-table th {
            background-color: #f0f0f0;
        }

        .footer-note {
            margin-top: 40px;
            font-size: 14px;
            text-align: center;
            color: #777;
        }

        .qr-signature {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 40px;
            flex-direction: column;
        }

        .qr-code {
            text-align: center;
            margin-bottom: 20px;
        }

        .signature-box {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin-top: 30px;
            flex-direction: column;
        }

        .signature-item {
            flex: 1;
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .print-buttons {
            text-align: center;
            margin-top: 30px;
        }

        .print-buttons button, .print-buttons a {
            margin: 0 10px;
            padding: 10px 25px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            color: #fff;
        }

        .btn-print {
            background-color: #28a745;
        }

        .btn-back {
            background-color: #007bff;
            text-decoration: none;
            display: inline-block;
        }

        @media print {
            .no-print { display: none; }
            body { background: none; padding: 0; }
            .report-container { border: none; box-shadow: none; padding: 0; }
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="report-container">

        <!-- تفاصيل الشركة -->
        <div class="info-header">
            <div class="company-info">
                <h4>{{ $company->company_name }}</h4>
                <strong>{{ $company->name }}</strong><br>
                {{ __('Tax.Tax number:') }} {{ $company->tax_number }}<br>
                {{ __('Tax.Commercial register:') }} {{ $company->commercial_register }}<br>
                {{ __('Tax.Phone:') }} {{ $company->phone }} - {{ __('Tax.Mobile:') }} {{ $company->mobile }}<br>
                {{ __('Tax.Email:') }} {{ $company->email }}<br>
                {{ __('Tax.Address:') }} {{ $company->address }}
            </div>

            <!-- شعار الشركة -->
            <div class="company-logo">
                @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="شعار الشركة">
                @else
                    <p style="font-size: 12px; color: #888;">{{ __('Tax.No logo available') }}</p>
                @endif
            </div>
        </div>

        <!-- العنوان -->
        <div class="report-header">
            <h1>{{ __('Tax.Tax declaration report') }}</h1>
            <br>
            <h5>{{ __('Tax.Period from') }} {{ $startDate }} {{ __('Tax.to') }} {{ $endDate }}</h5>
        </div>

        <!-- جدول التقرير -->
        <table class="vat-table">
            <thead>
                <tr>
                    <th>{{ __('Tax.Total sales') }}</th>
                    <th>{{ __('Tax.Sales tax') }}</th>
                    <th>{{ __('Tax.Total purchases') }}</th>
                    <th>{{ __('Tax.Purchases tax') }}</th>
                    <th>{{ __('Tax.Net tax') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($totalSales, 2) }}</td>
                    <td>{{ number_format($totalSalesTax, 2) }}</td>
                    <td>{{ number_format($totalPurchases, 2) }}</td>
                    <td>{{ number_format($totalPurchasesTax, 2) }}</td>
                    <td>{{ number_format($netTax, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- ملاحظات أسفل التقرير -->
        <div class="footer-note">
            {{ __('Tax.This report does not replace the official declaration submitted to the Zakat, Tax, and Customs Authority.') }}
        </div>

        <!-- QR Code و توقيع -->
        <div class="qr-signature">
            <!-- QR Code -->
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://zatca.gov.sa" alt="QR Code">
                <div style="font-size: 12px; margin-top: 5px;">{{ __('Tax.Scan to access the authority') }}</div>
            </div>

            <!-- التوقيع -->
            <div class="signature-box">
                <div class="signature-item text-center">
                    <strong>{{ __('Tax.User name:') }}</strong> {{ auth()->user()->name }}
                </div>
                <div class="signature-item text-center">
                    <strong>{{ __('Tax.Signature:') }}</strong> _______________
                </div>
                <div class="signature-item text-center">
                    <strong>{{ __('Tax.Date:') }}</strong> {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}
                </div>
            </div>
        </div>

        <hr>
        <p class="text-center">{{ __('Tax.Printed by the Naynox program') }}</p>

        <!-- أزرار الطباعة والرجوع -->
        <div class="print-buttons no-print">
            <button class="btn-print" onclick="window.print()">{{ __('Tax.Print report') }}</button>
            <a href="{{ route('taxes.quarterlyReport') }}" class="btn-back">{{ __('Tax.Back to reports') }}</a>
        </div>
    </div>
</div>
@endsection
