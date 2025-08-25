@extends('layouts.master')


@section('css')
@endsection

@section('title')
    {{ __('home.main_page') }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <div class="d-flex align-items-center">
            <h2 class="main-content-title tx-22 mg-b-0 mg-b-lg-1 mb-0">
                {{ __('home.welcome_back') }}
            </h2>
            @auth
            <div class="d-flex align-items-center ms-4 position-relative">
                <span class="ms-3" style="font-size: 17px; color:rgba(38, 0, 254, 0.5);">
                    {{ auth()->user()->name }}
                </span>
            </div>
            @else
                <script>window.location.href = "{{ route('login') }}";</script>
            @endauth
        </div>
    </div>
    <div class="main-dashboard-header-right">
        <div>
            <label class="tx-13">{{ __('home.CustomerRatings') }}</label>
            <div class="main-star">
                <i class="typcn typcn-star active"></i> 
                <i class="typcn typcn-star active"></i> 
                <i class="typcn typcn-star active"></i> 
                <i class="typcn typcn-star active"></i> 
                <i class="typcn typcn-star"></i> 
                <span>(146)</span>
            </div>
        </div>
        <div>
            <label class="tx-13">{{ __('home.OnlineSales') }}</label>
            <h5>563</h5>
        </div>
        <div>
            <label class="tx-13">{{ __('home.OfflineSales') }}</label>
            <h5>783</h5>
        </div>
    </div>
</div>
<!-- /breadcrumb -->
@endsection

@section('content')
<div class="container">
    @auth
       <br>
	   <br>
	   <hr>
        <!-- تنبيهات الاشتراك -->
        @if (auth()->user()->unreadNotifications->count())
            <div class="mt-4">
                <h4>التنبيهات</h4>
                @foreach (auth()->user()->unreadNotifications as $notification)
                    <div class="alert alert-warning d-flex justify-content-between align-items-center">
                        <div>
                            {{ $notification->data['message'] }}
                        </div>
                        <div>
                            <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-primary">
								{{ __('home.RenewSubscription') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <script>window.location.href = "{{ route('login') }}";</script>
    @endauth


				<!-- row -->
		<div class="row row-sm">
				<!-- المبيعات -->
				@php
					$sales = \App\invoice::sum('total_deu');
					$purchases = \App\purchase::sum('total_deu');
					$sales_percentage = $purchases > 0 ? ($sales / $purchases) : 0;
				@endphp

				<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-primary-gradient">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h4 class="mb-3 text-white">{{ __('home.Sales') }}</h4>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">
											{{ number_format($sales, 2) }} - {{ __('home.currency_symbol') }}
										</h4>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Cash') }}     - {{ \App\invoice::where('inv_payment',1)->sum('total_deu') }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Card') }}     - {{ \App\invoice::where('inv_payment',2)->sum('total_deu') }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Deferred') }} - {{ \App\invoice::where('inv_payment',3)->sum('total_deu') }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Bill') }}     - {{ \App\invoice::count() }}</p>
										
									</div>
									<span class="float-right my-auto mr-auto">
										<i class="fas fa-arrow-circle-up text-white"></i>
										<span class="text-white op-7">100%</span>
									</span>
								</div>
							</div>
						</div>
						<span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
					</div>
				</div>

				<!-- المشتريات -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-warning-gradient">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h4 class="mb-3 text-white">{{ __('home.Purchase') }}</h4>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">
											{{ number_format(\App\purchase::sum('total_deu'), 2) }} - {{ __('home.currency_symbol') }} 
										</h4>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Cash') }}     - {{ \App\purchase::where('inv_payment',1)->sum('total_deu') }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Card') }}     - {{ \App\purchase::where('inv_payment',2)->sum('total_deu') }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Deferred') }} - {{ \App\purchase::where('inv_payment',3)->sum('total_deu') }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Bill') }}     - {{ \App\purchase::count() }}</p>
									
									</div>
									<span class="float-right my-auto mr-auto">
										<i class="fas fa-arrow-circle-down text-white"></i>
										<span class="text-white op-7">{{ $sales > 0 ? round(($purchases / $sales) * 100, 2) : 0 }}%</span>
									</span>
								</div>
							</div>
						</div>
						<span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
					</div>
				</div>

				@php
					$sales = \App\invoice::sum('total_deu');
					$purchases = \App\purchase::sum('total_deu');
					$sales_percentage = $purchases > 0 ? ($sales / $purchases) : 0;
					$total_invoices = $sales + $purchases;

					$cash_total = \App\invoice::where('inv_payment', 1)->sum('total_deu') + \App\purchase::where('inv_payment', 1)->sum('total_deu');
					$network_total = \App\invoice::where('inv_payment', 2)->sum('total_deu') + \App\purchase::where('inv_payment', 2)->sum('total_deu');
					$credit_total = \App\invoice::where('inv_payment', 3)->sum('total_deu') + \App\purchase::where('inv_payment', 3)->sum('total_deu');
					$total_invoices_count = \App\invoice::count() + \App\purchase::count();
				@endphp


				<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
					<div class="card overflow-hidden sales-card bg-success-gradient">
						<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
							<div class="">
								<h4 class="mb-3 text-white">{{ __('home.TotalInvoices') }}</h4>
							</div>
							<div class="pb-0 mt-0">
								<div class="d-flex">
									<div class="">
										<h4 class="tx-20 font-weight-bold mb-1 text-white">
											{{ number_format($total_invoices, 2) }} - {{ __('home.currency_symbol') }} 
										</h4>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Cash') }}     - {{ number_format($cash_total, 2) }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Card') }}     - {{ number_format($network_total, 2) }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Deferred') }}- {{ number_format($credit_total, 2) }} - {{ __('home.currency_symbol') }} </p>
										<p class="mb-0 tx-12 text-white op-7">{{ __('home.Bill') }} - {{ $total_invoices_count }}</p>

									</div>
									<span class="float-right my-auto mr-auto">
										<i class="fas fa-arrow-circle-up text-white"></i>
										<span class="text-white op-7">100%</span>
									</span>
								</div>
							</div>
							</div>
							<span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
						</div>
				</div>
					@php
						$vat_sales = \App\invoice::sum('vat_value');
						$vat_purchases = \App\purchase::sum('vat_value');
						$vat_percentage = $vat_purchases > 0 ? ($vat_sales / $vat_purchases) * 100 : 0;
						$total_vat = $vat_sales + $vat_purchases;
					@endphp

					<!-- الضريبة -->
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card" style="background-color: Green !important;">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div>
									<h4 class="mb-3 tx-20 text-white">{{ __('home.VatTotal') }}</h4>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div>
											<h4 class="tx-20 font-weight-bold mb-1 text-white">
												{{ number_format($total_vat, 2) }} - {{ __('home.currency_symbol') }} 
											</h4>
											<p class="mb-0 tx-12 text-white op-7">
												مبيعات - {{ number_format($vat_sales, 2) }} - {{ __('home.currency_symbol') }} 
											</p>
											<p class="mb-0 tx-12 text-white op-7">
												مشتريات - {{ number_format($vat_purchases, 2) }} - {{ __('home.currency_symbol') }} 
											</p>
											<p class="mb-0 tx-12 text-white op-7">
												.
											</p>
											<p class="mb-0 tx-12 text-white op-7">
												.
											</p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-{{ $vat_percentage >= 100 ? 'up' : 'down' }} text-white"></i>
											<span class="text-white op-7">
											
											{{ number_format($vat_purchases, 2) }}%

											</span>
										</span>
									</div>
								</div>
							</div>
							<span id="compositeline2" class="pt-1">
								3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7
							</span>
						</div>
					</div>

				
					@php
						// إجمالي المبيعات والمشتريات
						$sales = \App\invoice::sum('total_deu');
						$purchases = \App\purchase::sum('total_deu');

						// مرتجعات
						$return_sales = \App\ReturnInvoice::sum('total_deu');
						$return_purchases = \App\RetPurchase::sum('total_deu');

						// المصروفات
						$expenses = \App\expense::sum('final_amount');

						// النسب المئوية
						$return_sales_percentage = $sales > 0 ? ($return_sales / $sales) * 100 : 0;
						$return_purchases_percentage = $purchases > 0 ? ($return_purchases / $purchases) * 100 : 0;
						$expense_percentage = $sales > 0 ? ($expenses / $sales) * 100 : 0;
					@endphp
					</div>
					<div class="row row-sm">
						<!-- مرتجع مبيعات -->
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
							<div class="card overflow-hidden sales-card bg-danger-gradient">
								<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
									<div>
										<h4 class="mb-3 tx-20 text-white">{{ __('home.ReturnSale') }}</h4>
									</div>
									<div class="pb-0 mt-0">
										<div class="d-flex">
											<div>
												<h4 class="tx-20 font-weight-bold mb-1 text-white">
													{{ number_format($return_sales, 2) }} - {{ __('home.currency_symbol') }}  
												</h4>
												<p class="mb-0 tx-16 text-white op-7">
													{{ \App\ReturnInvoice::count() }} - {{ __('home.Bill') }} 
												</p>
											</div>
											<span class="float-right my-auto mr-auto">
												<i class="fas fa-arrow-circle-down text-white"></i>
												<span class="text-white op-7">
													{{ number_format($return_sales_percentage, 2) }}%
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- مرتجع مشتريات -->
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
							<div class="card overflow-hidden sales-card bg-danger-gradient">
								<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
									<div>
										<h4 class="mb-3 tx-20 text-white">{{ __('home.ReturnPurchase') }}</h4>
									</div>
									<div class="pb-0 mt-0">
										<div class="d-flex">
											<div>
												<h4 class="tx-20 font-weight-bold mb-1 text-white">
													{{ number_format($return_purchases, 2) }} - {{ __('home.currency_symbol') }}  
												</h4>
												<p class="mb-0 tx-16 text-white op-7">
													{{ \App\RetPurchase::count() }} - {{ __('home.Bill') }} 
												</p>
											</div>
											<span class="float-right my-auto mr-auto">
												<i class="fas fa-arrow-circle-down text-white"></i>
												<span class="text-white op-7">
													{{ number_format($return_purchases_percentage, 2) }}%
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- المصروفات -->
						<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
							<div class="card overflow-hidden sales-card bg-danger-gradient">
								<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
									<div>
										<h4 class="mb-3 tx-20 text-white">{{ __('home.ExpenseTotal') }}</h4>
									</div>
									<div class="pb-0 mt-0">
										<div class="d-flex">
											<div>
												<h4 class="tx-20 font-weight-bold mb-1 text-white">
													{{ number_format($expenses, 2) }} - {{ __('home.currency_symbol') }}  
												</h4>
												<p class="mb-0 tx-16 text-white op-7">
													{{ \App\expense::count() }} - {{ __('home.Bill') }} 
												</p>
											</div>
											<span class="float-right my-auto mr-auto">
												<i class="fas fa-arrow-circle-down text-white"></i>
												<span class="text-white op-7">
													{{ number_format($expense_percentage, 2) }}%
												</span>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
		</div>
		<!-- Container closed -->
@endsection


@section('js')
<!--Internal  index js -->
<script src="{{URL::asset('assets/js/index.js')}}"></script>
<script src="{{URL::asset('assets/js/jquery.vmap.sampledata.js')}}"></script>	
@endsection