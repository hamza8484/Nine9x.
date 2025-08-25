@extends('layouts.master')

@section('title')
  طباعة جميع الأصـنــاف - ناينوكس
@stop

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">Pages</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Empty</span>
						</div>
					</div>
					
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة جميع الأصناف</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>جميع الأصناف</h1>
    <table>
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>الباركود</th>
                <th>السعر</th>
                <th>الكمية</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->product_barcode }}</td>
                    <td>{{ $product->product_sale_price }}</td>
                    <td>{{ $product->product_quantity }}</td>
                    <td>{{ $product->product_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
@endsection