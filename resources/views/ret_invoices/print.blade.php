@extends('layouts.master')

@section('css')
    <style>
        /* تنسيق البيانات الخاصة بالمؤسسة */
        .company-info {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .company-info h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            margin-top: 20px; /* إضافة مسافة من الأعلى لجعل العنوان أسفل الحقول */
            font-size: 30px; /* زيادة حجم الخط */
            font-weight: bold; /* جعل العنوان غامقًا */
        }

        .company-info p {
            font-size: 14px;
            font-weight: bold;
            color: #34495e;
            line-height: 1.5;
            margin-bottom: 10px; /* إضافة مسافة بين الحقول */
            margin-left: 20px;  /* إضافة مسافة من اليسار */
            margin-right: 20px; /* إضافة مسافة من اليمين */
        }

        .company-info strong {
            color: rgb(0, 41, 188);
        }

        /* تنسيق الشعار ليظهر بشكل مربع */
        .company-logo {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #34495e;
        }

        /* تنسيق الجدول */
        .table th, .table td {
            text-align: center;
            padding: 12px;
            vertical-align: middle;
        }

        .table thead {
            background-color: #007bff;
            color: white;
        }

        /* تنسيق جديد لترتيب العناصر بجانب بعض */
        .company-info .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .company-info .col {
            flex: 1;
            min-width: 200px;
            margin-right: 20px;
        }
    </style>
@endsection

@section('title')
    طباعة مرتـجع مبيعات - ناينوكس
@stop

@section('page-header')
    <!-- بيانات المؤسسة -->
    <div class="company-info">
        <div class="row">
            <div class="col">
                <h3>{{ $company->company_name }}</h3>
                <div class="d-flex">
                    <p><strong>رقم الضريبة: </strong>{{ $company->tax_number }}</p>
                    <p><strong> س.ت: </strong>{{ $company->commercial_register }}</p>
                </div>
                <div class="d-flex">
                    <p><strong>رقم الهاتف: </strong>{{ $company->phone }}</p>
                    <p><strong>رقم الجوال: </strong>{{ $company->mobile }}</p>
                </div>
               
            </div>
            <div>
                <img src="{{ asset('storage/' . $company->logo) }}" alt="شعار الشركة" class="company-logo">
            </div>
        </div>
    </div>

<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th style="color: black; font-weight: bold;">رقم المرتجع</th>
                                <td style="color: black; font-weight: bold;">{{ $returnInvoices->ret_inv_number  }}</td>
                                <th style="color: black; font-weight: bold;">تاريخ المرتجع</th>
                                <td style="color: black; font-weight: bold;">{{ $returnInvoices->ret_inv_date }}</td>
                                <th style="color: black; font-weight: bold;">اسم العميل</th>
                                <td style="color: black; font-weight: bold;">{{ $returnInvoices->customer->cus_name }}</td>
                            </tr>
                            <tr>
                                <th style="color: black; font-weight: bold;">اسم المخزن</th>
                                <td style="color: black; font-weight: bold;">{{ $returnInvoices->store->store_name }}</td>
                                <th style="color: black; font-weight: bold;">طريقة الدفع</th>
                                <td style="color: black; font-weight: bold;">{{ $returnInvoices->inv_payment }}</td>
                                <th style="color: black; font-weight: bold;">اسم المستخدم</th>
                                <td style="color: black; font-weight: bold;">{{ auth()->user()->name }}</td>
                            </tr>
                        </table>
                        
                        <h3 class="mt-4 mb-3">تفاصيل المرتـجع</h3>
                        <hr>
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-primary text-center">
                                    <th style="width: 5%; font-weight: bold; font-size: 16px;">تسلسل</th>
                                    <th style="width: 15%; font-weight: bold; font-size: 16px;">المجموعة</th>
                                    <th style="width: 18%; font-weight: bold; font-size: 16px;">الصنف</th>
                                    <th style="width: 10%; font-weight: bold; font-size: 16px;">الوحدة</th>
                                    <th style="width: 15%; font-weight: bold; font-size: 16px;">الباركود</th>
                                    <th style="width: 10%; font-weight: bold; font-size: 16px;">الكمية</th>
                                    <th style="width: 10%; font-weight: bold; font-size: 16px;">السعر</th>
                                    <th style="width: 10%; font-weight: bold; font-size: 16px;">الخصم</th>
                                    <th style="width: 15%; font-weight: bold; font-size: 16px;">المجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returnInvoices->detailsReturnInvoices as $item)
                                    <tr class="text-center">
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
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">المجموع</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $returnInvoices->sub_total }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">الخصم</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $returnInvoices->discount_value }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">ضريبة القيمة المضافة 15%</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $tax->tax_rate }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">مجموع الضريبة</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $returnInvoices->vat_value }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">الأجمـالـي النهائــي للفـاتـــورة</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $returnInvoices->total_deu }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">المبلغ المدفوع</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $returnInvoices->total_paid }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">المبلغ المتبقي</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $returnInvoices->total_unpaid }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">المبلغ آجــل</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $returnInvoices->total_deferred }} ريال</td>
                                </tr>
                            </tfoot>
                        </table>
						<!-- عرض QR Code في قسم بيانات الفاتورة -->
						<div class="card" >
							<div class="row d-flex align-items-center">
								<!-- QR Code -->
								<div class="mr-3">
                                <img src="data:image/png;base64,{{ base64_encode($qrImage) }}" alt="QR Code" />
								</div>
								<!-- العنوان -->
								<div class="mr-3" >
									<p><strong>العنوان: </strong>{{ $company->address }}</p>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
@endsection
