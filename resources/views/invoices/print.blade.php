@extends('layouts.master')

@section('css')
   
@endsection

@section('title')
     {{ __('home.MainPage35') }}
@stop

@section('page-header')
    <!-- بيانات المؤسسة -->
    <div class="d-flex justify-content-between align-items-start mb-4" style="border-bottom: 2px solid #ccc; padding-bottom: 15px;">
        
        <div style="text-align: right; line-height: 1.8; font-size: 14px;">
            <h1>{{ $company->company_name }}</h1>
                <strong>{{ $company->name }}</strong><br>
                الرقم الضريبي: {{ $company->tax_number }}<br>
                السجل التجاري: {{ $company->commercial_register }}<br>
                الهاتف: {{ $company->phone }} - الجوال: {{ $company->mobile }}<br>
                البريد الإلكتروني: {{ $company->email }}<br>
                العنوان: {{ $company->address }}
        </div>

        <!-- شعار الشركة -->
        <div style="text-align: left;">
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="شعار الشركة" style="width: 300px; height: 180px; object-fit: contain;">
            @else
                <p style="font-size: 12px; color: #888;">لا يوجد شعار</p>
            @endif
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th style="color: black; font-weight: bold;">رقم الفاتورة</th>
                                <td style="color: black; font-weight: bold;">{{ $invoices->inv_number }}</td>
                                <th style="color: black; font-weight: bold;">تاريخ الفاتورة</th>
                                <td style="color: black; font-weight: bold;">{{ $invoices->inv_date }}</td>
                                <th style="color: black; font-weight: bold;">اسم العميل</th>
                                <td style="color: black; font-weight: bold;">{{ $invoices->customer->cus_name }}</td>
                            </tr>
                            <tr>
                                <th style="color: black; font-weight: bold;">اسم المخزن</th>
                                <td style="color: black; font-weight: bold;">{{ $invoices->store->store_name }}</td>
                                <th style="color: black; font-weight: bold;">طريقة الدفع</th>
                                <td style="color: black; font-weight: bold;">{{ $invoices->inv_payment }}</td>
                                <th style="color: black; font-weight: bold;">اسم المستخدم</th>
                                <td style="color: black; font-weight: bold;">{{ auth()->user()->name }}</td>
                            </tr>
                        </table>
                        
                        <h3 class="mt-4 mb-3">تفاصيل الفاتــورة</h3>
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
                                @foreach($invoices->invoiceDetails as $item)
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
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $invoices->sub_total }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">الخصم</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $invoices->discount_value }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">ضريبة القيمة المضافة 15%</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $tax->tax_rate }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">مجموع الضريبة</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $invoices->vat_value }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">الأجمـالـي النهائــي للفـاتـــورة</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $invoices->total_deu }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">المبلغ المدفوع</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $invoices->total_paid }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">المبلغ المتبقي</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $invoices->total_unpaid }} ريال</td>
                                </tr>
                                <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">المبلغ آجــل</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $invoices->total_deferred }} ريال</td>
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
