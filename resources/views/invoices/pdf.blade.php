<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8" />
    <title>فاتورة مبيعات</title>
    <style>
        @font-face {
			font-family: 'Cairo';
			src: url('/fonts/Cairo-Regular.ttf') format('truetype');
		}



        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 14px;
            color: #555;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .no-border td {
            border: none;
        }

        .qr-code {
            text-align: center;
            margin-top: 20px;
        }

        .total {
            font-weight: bold;
        }

        .heading {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <table class="no-border">
        <tr>
            <td>
                <strong>رقم الفاتورة:</strong> {{ $inv_number }}<br>
                <strong>التاريخ:</strong> {{ $inv_date }}<br>
                <strong>طريقة الدفع:</strong> {{ $inv_payment }}
            </td>
            <td>
                <strong>العميل:</strong> {{ $customer_name }}<br>
                <strong>المتجر:</strong> {{ $store_name }}
            </td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
        <tr class="heading">
            <th>التصنيف</th>
            <th>المنتج</th>
            <th>الوحدة</th>
            <th>الباركود</th>
            <th>الكمية</th>
            <th>سعر الوحدة</th>
            <th>الخصم</th>
            <th>الإجمالي</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $item['category_id'] }}</td>
                <td>{{ $item['product_id'] }}</td>
                <td>{{ $item['unit_id'] }}</td>
                <td>{{ $item['product_barcode'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ number_format($item['product_price'], 2) }}</td>
                <td>{{ number_format($item['product_discount'], 2) }}</td>
                <td>{{ number_format($item['total_price'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <br>

    <table>
        <tr>
            <td class="total">الإجمالي الفرعي:</td>
            <td>{{ number_format($sub_total, 2) }}</td>
        </tr>
        <tr>
            <td class="total">قيمة الخصم:</td>
            <td>{{ number_format($discount_value, 2) }}</td>
        </tr>
        <tr>
            <td class="total">الضريبة:</td>
            <td>{{ number_format($vat_value, 2) }}</td>
        </tr>
        <tr>
            <td class="total">الإجمالي:</td>
            <td>{{ number_format($total_deu, 2) }}</td>
        </tr>
        <tr>
            <td class="total">المدفوع:</td>
            <td>{{ number_format($total_paid, 2) }}</td>
        </tr>
        <tr>
            <td class="total">المتبقي:</td>
            <td>{{ number_format($total_unpaid, 2) }}</td>
        </tr>
    </table>

    <div class="qr-code">
        <p><strong>رمز الاستجابة السريع (QR):</strong></p>
        <img src="data:image/png;base64,{{ $qrImage }}" style="width: 150px;" alt="QR Code">
    </div>
</div>

</body>
</html>
<style>
    body {
        font-family: 'cairo', sans-serif;
        direction: rtl;
        text-align: right;
    }
</style>
