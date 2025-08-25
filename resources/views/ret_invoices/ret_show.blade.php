@extends('layouts.master')

@section('css')
@endsection

@section('title')
{{ __('home.MainPage36') }}
@stop

@section('page-header')

@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h2 class="m-0">عرض مرتـجع رقم - {{ $returnInvoices->ret_inv_number }}</h2>
                    <a href="{{ route('ret_invoices.index') }}" class="btn btn-light"><i class="fa fa-arrow-left"></i> الرجوع الى مرتـجع المبيعات</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th style="color: black; font-weight: bold;" >رقم المرتجع</th>
                                <td style="color: black; font-weight: bold;" >{{ $returnInvoices->ret_inv_number }}</td>
                                <th style="color: black; font-weight: bold;" >تاريخ المرتجع</th>
                                <td style="color: black; font-weight: bold;" >{{ $returnInvoices->ret_inv_date }}</td>
                                <th style="color: black; font-weight: bold;" >اسم العميل</th>
                                <td style="color: black; font-weight: bold;" >{{ $returnInvoices->customer->cus_name }}</td>
                            </tr>
                            <tr>
                                <th style="color: black; font-weight: bold;" >اسم المخزن</th>
                                <td style="color: black; font-weight: bold;" >{{ $returnInvoices->store->store_name }}</td>
                                <th style="color: black; font-weight: bold;" >طريقة الدفع</th>
                                <td style="color: black; font-weight: bold;" >{{ $returnInvoices->ret_inv_payment }}</td>
                                <th style="color: black; font-weight: bold;">اسم المستخدم</th>
                                <td style="color: black; font-weight: bold;">{{ auth()->user()->name }}</td>
                            </tr>
                        </table>
                        
                        <h3 class="mt-4 mb-3">تفاصيل المرتـجع</h3>
                        <hr>
                        <table class="table table-bordered">
                            <thead>
                            <tr class="table-primary text-center">
                                <th style="width: 5%; color: black; font-weight: bold; font-size: 16px;">تسلسل</th>
                                <th style="width: 15%; color: black; font-weight: bold; font-size: 16px;">المجموعة</th>
                                <th style="width: 18%; color: black; font-weight: bold; font-size: 16px;">الصنف</th>
                                <th style="width: 10%; color: black; font-weight: bold; font-size: 16px;">الوحدة</th>
                                <th style="width: 15%; color: black; font-weight: bold; font-size: 16px;">الباركود</th>
                                <th style="width: 10%; color: black; font-weight: bold; font-size: 16px;">الكمية</th>
                                <th style="width: 10%; color: black; font-weight: bold; font-size: 16px;">السعر</th>
                                <th style="width: 10%; color: black; font-weight: bold; font-size: 16px;">الخصم</th>
                                <th style="width: 15%; color: black; font-weight: bold; font-size: 16px;">المجموع</th>
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
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">المجموع</td>
                                <td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $returnInvoices->sub_total }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">الخصم</td>
                                <td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $returnInvoices->discount_value }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">ضريبة القيمة المضافة 15%</td>
                                <td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $tax->tax_rate }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">مجموع الضريبة</td>
                                <td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $returnInvoices->vat_value }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: green; font-weight: bold; font-size: 18px; padding: 12px;">الأجمـالـي النهائــي للفـاتـــورة</td>
                                <td  class="text-center" style="color: green; font-weight: bold; font-size: 15px; padding: 12px;">{{ $returnInvoices->total_deu }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: green; font-weight: bold; font-size: 18px; padding: 12px;">المبلغ المدفوع</td>
                                <td  class="text-center" style="color: green; font-weight: bold; font-size: 15px; padding: 12px;">{{ $returnInvoices->total_paid }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: red; font-weight: bold; font-size: 18px; padding: 12px;">المبلغ المتبقي</td>
                                <td  class="text-center" style="color: red; font-weight: bold; font-size: 15px; padding: 12px;">{{ $returnInvoices->total_unpaid }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">المبلغ آجــل</td>
                                <td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $returnInvoices->total_deferred }} ريال</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!--
                        <div class="row justify-content-end">
                            <div class="col-12 text-center">
                                <a href="#" class="btn btn-primary btn-lg mx-2"><i class="fa fa-print"></i> طباعــة</a>
                                <a href="#" class="btn btn-secondary btn-lg mx-2"><i class="fa fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>
@stop
