@extends('layouts.master')


@section('css')
@endsection
@section('title')
 عرض فاتورة مشتريــات - ناينوكس
@stop

@section('page-header')

@endsection
@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h2 class="m-0">عرض فاتورة رقم - {{ $purchase->pur_number }}</h2>
                    <a href="{{ route('purchases.index') }}" class="btn btn-light"><i class="fa fa-arrow-left"></i> الرجوع الى الفواتير</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th style="color: black; font-weight: bold;">رقم الفاتورة :</th>
                                <td style="color: blue; font-weight: bold;">{{ $purchase->pur_number }}</td>
                                <th style="color: black; font-weight: bold;">تاريخ الفاتورة :</th>
                                <td style="color: blue; font-weight: bold;">{{ $purchase->pur_date }}</td>
                                <th style="color: black; font-weight: bold;">اسم المورد :</th>
                                <td style="color: blue; font-weight: bold;">{{ $purchase->supplier->sup_name }}</td>
                                <th style="color: black; font-weight: bold;">المخزن:</th>
                                <td style="color: blue; font-weight: bold;">{{ $purchase->store->store_name}}</td>
                                <th style="color: black; font-weight: bold;">الفرع :</th>
                                <td style="color: blue; font-weight: bold;">{{ $purchase->branch->bra_name}}</td>
                            </tr>
                            <tr>
                                <th style="color: black; font-weight: bold;">الخزنة :</th>
                                <td style="color: blue; font-weight: bold;">{{ $purchase->cashbox->cash_name }}</td>
                                <th style="color: black; font-weight: bold;">طريقة الدفع :</th>
                                <td style="color: blue; font-weight: bold;">{{ $purchase->inv_payment }}</td>
                                <th style="color: black; font-weight: bold;">الحساب :</th>
                                <td style="color: blue; font-weight: bold;">{{ $purchase->account->name  }}</td>
                                <th style="color: black; font-weight: bold;">اسم المستخدم :</th>
                                <td style="color: blue; font-weight: bold;">{{ auth()->user()->name }}</td>
                            </tr>
                        </table>
                        
                        <h3 class="mt-4 mb-3">تفاصيل الفاتــورة :</h3>
                        <hr>
                        <table class="table table-bordered">
                            <thead>
                            <tr class="table-primary text-center">
                                <th style="width: 5%; color:  black; font-weight: bold; font-size: 16px;">تسلسل</th>
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
                                @foreach($purchase->purchaseDetails as $item)
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
                                <td class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $purchase->sub_total }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">الخصم</td>
                                <td class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $purchase->discount_value }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">ضريبة القيمة المضافة 15%</td>
                                <td class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $tax->tax_rate }} %</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">مجموع الضريبة</td>
                                <td class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $purchase->vat_value }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: green; font-weight: bold; font-size: 18px; padding: 12px;">الأجمـالـي النهائــي للفـاتـــورة</td>
                                <td class="text-center" style="color: green; font-weight: bold; font-size: 15px; padding: 12px;">{{ $purchase->total_deu }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: green; font-weight: bold; font-size: 18px; padding: 12px;">المبلغ المدفوع</td>
                                <td class="text-center" style="color: green; font-weight: bold; font-size: 15px; padding: 12px;">{{ $purchase->total_paid }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: red; font-weight: bold; font-size: 18px; padding: 12px;">المبلغ المتبقي</td>
                                <td class="text-center" style="color: red; font-weight: bold; font-size: 15px; padding: 12px;">{{ $purchase->total_unpaid }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">المبلغ آجــل</td>
                                <td class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $purchase->total_deferred }} ريال</td>
                            </tr>
                            <tr class="table-info">
                                    <td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px;">الملاحظات</td>
                                    <td class="text-center" style="color: black; font-weight: bold; font-size: 15px;">{{ $purchase->notes }} </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!--
                        <div class="row justify-content-end">
                            <div class="col-12 text-center">
                                <a href="{{route('purchases.print',$purchase->id) }}" class="btn btn-primary btn-lg mx-2"><i class="fa fa-print"></i> طباعــة</a>
                                <a href="{{route('purchases.pdf',$purchase->id) }}" class="btn btn-secondary btn-lg mx-2"><i class="fa fa-file-pdf"></i> PDF </a>
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