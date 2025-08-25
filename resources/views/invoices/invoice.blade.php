@extends('layouts.master')

@section('css')
<style>
    body {
        direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
        font-family: 'Tahoma', sans-serif;
        background: #fff;
        color: #000;
    }

    .invoice-header {
        display: flex !important;
        flex-direction: {{ app()->getLocale() === 'ar' ? 'row-reverse' : 'row' }} !important;
        justify-content: space-between;
        align-items: start;
    }

    .invoice-header img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #34495e;
      
    }
    .invoice-title {
        color: #4CAF50;
        text-align: center;
        font-size: 30px;
        font-weight: bold;
        margin: 20px 0;
    }
    .info-table td {
        padding: 8px;
        font-weight: bold;
    }
    .details-table th, .details-table td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        font-size: 14px;
    }
    .summary-table td {
        padding: 8px 15px;
        font-weight: bold;
        font-size: 15px;
    }
    .footer-section {
        margin-top: 40px;
        text-align: center;
        font-size: 14px;
        color: #555;
    }

    @media print {
        
        .no-print {
            display: none !important;
        }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .footer-section {
            page-break-after: avoid;
        }

        img, table, .footer-section {
            break-inside: avoid;
        }
        body {
            background: none;
        }
        button {
            display: none;
        }
        .container-fluid {
            padding: 0 !important;
        }
        .page-break {
            page-break-before: always;
        }
    }
</style>
@endsection


@section('title')
    {{ __('home.MainPage35') }}
@stop

@section('content')

<!-- كل الفاتورة -->
<div class="container-fluid p-4">

   
   <!-- هيدر الفاتورة -->
<div class="invoice-header d-flex justify-content-between align-items-start">
    @if(app()->getLocale() === 'ar')
        <!-- شعار على اليمين -->
        <div>
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="شعار الشركة" >
            @else
                <p>لا يوجد شعار</p>
            @endif
        </div>

        <!-- بيانات المؤسسة على اليسار -->
        <div style="text-align: right; font-size: 14px; line-height: 1.8;">
            <h2 style="margin-bottom: 0;">{{ $company->company_name }}</h2><br>
            {{ __('home.VatNumber') }}: {{ $company->tax_number }}<br>
            {{ __('home.C.R.') }}: {{ $company->commercial_register }}<br>
            {{ __('home.PhoneNo.') }}: {{ $company->phone }} - {{ __('home.TelphoneNo.') }}: {{ $company->mobile }}<br>
            {{ __('home.Emails') }}: {{ $company->email }}<br>
            {{ __('home.AddressA') }}: {{ $company->address }}
        </div>
    @else
        <!-- بيانات المؤسسة على اليسار -->
        <div style="text-align: left; font-size: 14px; line-height: 1.8;">
            <h2 style="margin-bottom: 0;">{{ $company->company_name }}</h2><br>
            {{ __('home.VatNumber') }}: {{ $company->tax_number }}<br>
            {{ __('home.C.R.') }}: {{ $company->commercial_register }}<br>
            {{ __('home.PhoneNo.') }}: {{ $company->phone }} - {{ __('home.TelphoneNo.') }}: {{ $company->mobile }}<br>
            {{ __('home.Emails') }}: {{ $company->email }}<br>
            {{ __('home.AddressA') }}: {{ $company->address }}
        </div>

        <!-- شعار على اليمين -->
        <div>
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo" style="max-width: 150px;">
            @else
                <p>No Logo</p>
            @endif
        </div>
    @endif
</div>


    <div class="invoice-title">{{ __('home.Taxinvoice') }}</div>
        <!-- زر الطباعة -->
        <div style="text-align: left; margin-bottom: 20px;" class="no-print">
            <button onclick="printInvoice()" class="btn btn-success">🖨️ {{ __('home.InvoicePrint') }}</button>
        </div>

    <!-- بيانات الفاتورة -->
    <table class="table info-table mb-4" style="width: 100%;">
        <tr>
            <td>{{ __('home.InvoiceNo') }}: {{ $invoices->inv_number }}</td>
            <td>{{ __('home.Date') }} : {{ $invoices->inv_date }}</td>
            <td>{{ __('home.PaymentMethod') }} : {{ $invoices->inv_payment }}</td>
        </tr>
        <tr>
            <td colspan="1">{{ __('home.Customers') }}: {{ $invoices->customer->cus_name }}</td>
            <td>{{ __('home.VatNumber') }} :{{ $invoices->customer->cus_tax_number }} </td>
            <td>{{ __('home.User Name') }} : {{ auth()->user()->name }}</td>
        </tr>
    </table>

    <!-- تفاصيل الأصناف -->
    <h5 class="mb-3">{{ __('home.DetailsInvoice') }}</h5><br>
    <table class="table table-bordered details-table" style="width: 100%;">
        <thead class="table-success">
            <tr>
                <th>{{ __('home.No.') }}</th>
                <th>{{ __('home.Categoey') }}</th>
                <th>{{ __('home.Product') }}</th>
                <th>{{ __('home.Units') }}</th>
                <th>{{ __('home.Barcode') }}</th>
                <th>{{ __('home.Qty') }}</th>
                <th>{{ __('home.Price') }}</th>
                <th>{{ __('home.Discount') }}</th>
                <th>{{ __('home.Total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices->invoiceDetails as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->category->c_name }}</td>
                <td>{{ $item->product->product_name }}</td>
                <td>{{ $item->unit->unit_name }}</td>
                <td>{{ $item->product_barcode }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->product_price, 2) }}</td>
                <td>{{ number_format($item->product_discount, 2) }}</td>
                <td>{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ملخص الفاتورة -->
    <table class="table summary-table" style="width: 50%; margin-top: 30px;">
        <tr>
            <td>{{ __('home.InvoiceTotal') }}</td>
            <td>{{ number_format($invoices->sub_total, 2) }} {{ __('home.S.R') }}</td>
        </tr>
        <tr>
            <td>{{ __('home.DiscountType') }}</td>
            <td>{{ number_format($invoices->discount_value, 2) }} {{ __('home.S.R') }}</td>
        </tr>
        <tr>
            <td>{{ __('home.VAT15%') }}</td>
            <td>{{ number_format($invoices->vat_value, 2) }} {{ __('home.S.R') }}</td>
        </tr>
        <tr style="background: #4CAF50; color: white;">
            <td>{{ __('home.FinalTotal') }}</td>
            <td>{{ number_format($invoices->total_deu, 2) }} {{ __('home.S.R') }}</td>
        </tr>
        <tr>
            <td>{{ __('home.Paid') }}</td>
            <td>{{ number_format($invoices->total_paid, 2) }} {{ __('home.S.R') }}</td>
        </tr>
        <tr>
            <td>{{ __('home.Remaining Paid') }}</td>
            <td>{{ number_format($invoices->total_unpaid, 2) }} {{ __('home.S.R') }}</td>
        </tr>
        <tr>
            <td>{{ __('home.AmountDue') }}</td>
            <td>{{ number_format($invoices->total_deferred, 2) }} {{ __('home.S.R') }}</td>
        </tr>
    </table>

 
    <!-- QR Code -->
        <div style="text-align: center; margin-top: 30px;">
        <img src="data:image/png;base64,{{ $qrImage }}" alt="QR Code" style="max-width: 280px; height: auto;">
            <div style="font-size: 14px; margin-top: 8px;">{{ __('home.Surveytoaccesstheauthority') }}</div>
        </div>

        <!-- توقيع -->
        <div class="footer-section mt-4" style="margin-bottom: 0;">
           {{ __('home.MMessages') }}
            <br><br>
            <strong>{{ __('home.signature') }}</strong>
            <div style="margin-top: 5px;">ـــــــــــــــــــــــــــــــــــــــــــــــ</div>
        </div>

</div>

@endsection


@section('js')
<script>
    function printInvoice() {
        var content = document.getElementById('printArea').innerHTML;
        var printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>طباعة الفاتورة</title>
                    <style>
                        body { direction: rtl; font-family: 'Tahoma', sans-serif; color: #000; padding: 20px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; font-size: 14px; }
                        .invoice-title { text-align: center; font-size: 24px; font-weight: bold; color: #4CAF50; margin: 20px 0; }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    ${content}
                </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>

<script>
    function printInvoice() {
        window.print();

        // إعادة التوجيه بعد انتهاء الطباعة
        window.onafterprint = function () {
            window.location.href = "{{ route('invoices.index') }}";
        };
    }
</script>
@endsection


