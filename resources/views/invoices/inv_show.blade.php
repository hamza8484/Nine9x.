@extends('layouts.master')


@section('css')
@endsection


@section('title')
    {{ __('home.MainPage34') }}
@stop

@section('page-header')

@endsection
@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h2 class="m-0">{{ __('home.ShowInvoiceNumber') }} - {{ $invoices->inv_number }}</h2>
                    <a href="{{ route('invoices.index') }}" class="btn btn-light"><i class="fa fa-arrow-left"></i> {{ __('home.BackToInvoiceList') }}</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tr>
                                <th style="color: black; font-weight: bold;" >{{ __('home.InvoiceNo') }}</th>
                                <td style="color: black; font-weight: bold;" >{{ $invoices->inv_number }}</td>
                                <th style="color: black; font-weight: bold;" >{{ __('home.Date') }}</th>
                                <td style="color: black; font-weight: bold;" >{{ $invoices->inv_date }}</td>
                                <th style="color: black; font-weight: bold;" >{{ __('home.Customers') }}</th>
                                <td style="color: black; font-weight: bold;" >{{ $invoices->customer->cus_name }}</td>
                            </tr>
                            <tr>
                                <th style="color: black; font-weight: bold;" >{{ __('home.Store') }}</th>
                                <td style="color: black; font-weight: bold;" >{{ $invoices->store->store_name }}</td>
                                <th style="color: black; font-weight: bold;" >{{ __('home.PaymentMethod') }}</th>
                                <td style="color: black; font-weight: bold;" >{{ $invoices->inv_payment }}</td>
								<th style="color: black; font-weight: bold;">{{ __('home.User Name') }}</th>
                                <td style="color: black; font-weight: bold;">{{ auth()->user()->name }}</td>
                            </tr>
                        </table>
                        
                        <h3 class="mt-4 mb-3">{{ __('home.invoiceDetails') }}</h3>
                        <hr>
                        <table class="table table-bordered">
                            <thead>
							<tr class="table-primary text-center">
                                    <th style="text-align: center; width: 3%;"> {{ __('home.No.') }}</th>
                                    <th style="text-align: center; width: 13%;">{{ __('home.Categoey') }}</th>
                                    <th style="text-align: center; width: 16%;">{{ __('home.Product') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Units') }}</th>
                                    <th style="text-align: center; width: 17%;">{{ __('home.Barcode') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Qty') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Price') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Discount') }}</th>
                                    <th style="text-align: center; width: 15%;">{{ __('home.Total') }}</th>
                                   
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
									<td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">{{ __('home.InvoiceTotal') }}</td>
									<td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $invoices->sub_total }} {{ __('home.S.R') }}</td>
								</tr>
								<tr class="table-info">
									<td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">{{ __('home.DiscountType') }}</td>
									<td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $invoices->discount_value }} {{ __('home.S.R') }}</td>
								</tr>
								<tr class="table-info">
									<td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">{{ __('home.TaxRate') }}</td>
									<td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $tax->tax_rate }} {{ __('home.S.R') }}</td>
								</tr>
								<tr class="table-info">
									<td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">{{ __('home.VAT') }}</td>
									<td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $invoices->vat_value }} {{ __('home.S.R') }}</td>
								</tr>
								<tr class="table-info">
									<td colspan="4" class="text-center" style="color: green; font-weight: bold; font-size: 18px; padding: 12px;">{{ __('home.FinalTotal') }}</td>
									<td  class="text-center" style="color: green; font-weight: bold; font-size: 15px; padding: 12px;">{{ $invoices->total_deu }} {{ __('home.S.R') }}</td>
								</tr>
								<tr class="table-info">
									<td colspan="4" class="text-center" style="color: green; font-weight: bold; font-size: 18px; padding: 12px;">{{ __('home.Paid') }}</td>
									<td  class="text-center" style="color: green; font-weight: bold; font-size: 15px; padding: 12px;">{{ $invoices->total_paid }} {{ __('home.S.R') }}</td>
								</tr>
								<tr class="table-info">
									<td colspan="4" class="text-center" style="color: red; font-weight: bold; font-size: 18px; padding: 12px;">{{ __('home.Remaining Paid') }}</td>
									<td  class="text-center" style="color: red; font-weight: bold; font-size: 15px; padding: 12px;">{{ $invoices->total_unpaid }} {{ __('home.S.R') }}</td>
								</tr>
								<tr class="table-info">
									<td colspan="4" class="text-center" style="color: black; font-weight: bold; font-size: 18px; padding: 12px;">{{ __('home.AmountDue') }}</td>
									<td  class="text-center" style="color: black; font-weight: bold; font-size: 15px; padding: 12px;">{{ $invoices->total_deferred }} {{ __('home.S.R') }}</td>
								</tr>
							</tr>
                            </tfoot>
                        </table>
                    </div>
                    <!--
                        <div class="row justify-content-end">
                            <div class="col-12 text-center">
                                <a href="{{route('invoices.print',$invoices->id) }}" class="btn btn-primary btn-lg mx-2"><i class="fa fa-print"></i> طباعــة</a>
                                <a href="{{route('invoices.pdf',$invoices->id) }}" class="btn btn-secondary btn-lg mx-2"><i class="fa fa-file-pdf"></i> PDF </a>
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