@extends('layouts.master')

@section('css')
<style>
    body {
        direction: rtl;
        font-family: 'Tahoma', sans-serif;
        font-size: 12px;
        color: #000;
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid #aaa;
        padding: 8px;
        text-align: center;
        font-size: 12px;
    }

    h1, h4 {
        text-align: center;
        color: #4CAF50;
        margin: 20px 0 10px;
    }

    .signature-area {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px dashed #ccc;
        font-size: 12px;
    }

    .signature-area div {
        text-align: center;
        width: 45%;
    }

    .note {
        margin-top: 30px;
        font-size: 11px;
        text-align: center;
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
            background: none;
        }

        .container-fluid {
            padding: 0 !important;
        }

        .footer-section {
            page-break-after: avoid;
        }

        img, table, .footer-section {
            break-inside: avoid;
        }

        .page-break {
            page-break-before: always;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-4" id="printArea">

    <!-- الهيدر -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        
        <!-- بيانات المؤسسة -->
        <div style="text-align: right; font-size: 14px;">
            <h2>{{ $company->company_name }}</h2>
            <p>الرقم الضريبي: {{ $company->tax_number }}</p>
            <p>السجل التجاري: {{ $company->commercial_register }}</p>
            <p>الهاتف: {{ $company->phone }} - الجوال: {{ $company->mobile }}</p>
            <p>البريد الإلكتروني: {{ $company->email }}</p>
            <p>العنوان: {{ $company->address }}</p>
        </div>

        <!-- QR -->
        <div style="text-align: center;">
            <img src="data:image/png;base64,{{ $qrImage }}" alt="QR Code" style="max-width: 120px;">
        </div>

        <!-- شعار الشركة -->
        <div>
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="شعار الشركة" style="max-width: 160px;">
            @else
                <p>لا يوجد شعار</p>
            @endif
        </div>
    </div>

    <!-- عنوان الفاتورة -->
    <h1 class="invoice-title">فاتورة مرتجع مشتريات</h1>

    <!-- معلومات الفاتورة -->
    <table class="table table-bordered text-center">
        <tr>
            <th>رقم المرتجع</th>
            <td>{{ $ret_purchase->ret_pur_number }}</td>
            <th>رقم فاتورة المشتريات</th>
            <td>{{ $ret_purchase->purchase->pur_number ?? '-' }}</td>
        </tr>
        <tr>
            <th>تاريخ المرتجع</th>
            <td>{{ $ret_purchase->ret_pur_date }}</td>
            <th>تاريخ فاتورة المشتريات</th>
            <td>{{ $ret_purchase->purchase->pur_date ?? '-' }}</td>
        </tr>
        <tr>
            <th>المورد</th>
            <td>{{ $ret_purchase->supplier->sup_name }}</td>
            <th>الرقم الضريبي</th>
            <td>{{ $ret_purchase->supplier->sup_tax_number }}</td>
        </tr>
    </table>

    <!-- تفاصيل إضافية -->
    <table class="table table-bordered text-center">
        <tr>
            <th>المخزن</th>
            <td>{{ $ret_purchase->store->store_name }}</td>
            <th>طريقة الدفع</th>
            <td>{{ $ret_purchase->ret_pur_payment }}</td>
            <th>المستخدم</th>
            <td>{{ auth()->user()->name }}</td>
        </tr>
    </table>

    <!-- تفاصيل المرتجع -->
    <h4>تفاصيل المرتجع</h4>
    <table class="table table-striped table-bordered text-center">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>المجموعة</th>
                <th>الصنف</th>
                <th>الوحدة</th>
                <th>الباركود</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>الخصم</th>
                <th>المجموع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ret_purchase->detailsReturnPurchases as $item)
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
    </table>

    <!-- الملخص -->
    <h4>ملخص الفاتورة</h4>
    <table class="table table-bordered text-center mt-4">
        <tr>
            <th>الإجمالي</th>
            <td>{{ $ret_purchase->sub_total }} ريال</td>
        </tr>
        <tr>
            <th>الخصم</th>
            <td>{{ $ret_purchase->discount_value }} ريال</td>
        </tr>
        <tr>
            <th>الضريبة (15%)</th>
            <td>{{ $tax->tax_rate }} ريال</td>
        </tr>
        <tr>
            <th>قيمة الضريبة</th>
            <td>{{ $ret_purchase->vat_value }} ريال</td>
        </tr>
        <tr class="table-success">
            <th>الإجمالي النهائي</th>
            <td>{{ $ret_purchase->total_deu }} ريال</td>
        </tr>
        <tr>
            <th>المدفوع</th>
            <td>{{ $ret_purchase->total_paid }} ريال</td>
        </tr>
        <tr>
            <th>المتبقي</th>
            <td>{{ $ret_purchase->total_unpaid }} ريال</td>
        </tr>
    </table>

    <!-- زر طباعة -->
    <div class="mt-3 text-left no-print">
        <button onclick="printInvoice()" class="btn btn-success">🖨️ طباعة</button>
    </div>

</div>
@endsection

@section('js')
<script>
    function printInvoice() {
        // استدعاء محتوى منطقة الطباعة
        var content = document.getElementById('printArea').innerHTML;

        // فتح نافذة جديدة للطباعة
        var printWindow = window.open('', '_blank', 'width=1000,height=600');

        // كتابة محتوى الصفحة داخل نافذة الطباعة
        printWindow.document.write(`
            <html>
                <head>
                    <title>طباعة الفاتورة</title>
                    <style>
                        body {
                            direction: rtl;
                            font-family: 'Tahoma', sans-serif;
                            color: #000;
                            padding: 20px;
                            margin: 0;
                        }

                        h1 {
                            text-align: center;
                            color: #4CAF50;
                            font-size: 22px;
                            margin: 20px 0;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                        }

                        th, td {
                            border: 1px solid #ccc;
                            padding: 8px;
                            text-align: center;
                            font-size: 12px;
                        }

                        .header, .footer {
                            text-align: center;
                            margin-bottom: 20px;
                        }

                        .no-print {
                            display: none !important;
                        }

                        @media print {
                            body {
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                                background: none;
                            }
                        }
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

@endsection
