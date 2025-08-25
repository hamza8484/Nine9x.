<!-- main-sidebar -->
		<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
				<aside class="app-sidebar sidebar-scroll">
					<div class="main-sidebar-header active">
						<a class="desktop-logo logo-light active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/logo.png')}}" class="main-logo" alt="logo"></a>
						<a class="desktop-logo logo-dark active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/logo-white.png')}}" class="main-logo dark-theme" alt="logo"></a>
						<a class="logo-icon mobile-logo icon-light active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="logo-icon" alt="logo"></a>
						<a class="logo-icon mobile-logo icon-dark active" href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon-white.png')}}" class="logo-icon dark-theme" alt="logo"></a>
					</div>
					<div class="main-sidemenu">
					<div class="app-sidebar__user clearfix">
			<div class="dropdown user-pro-body">
				<div class="">
					<img alt="user-img" class="avatar avatar-xl brround" src="{{URL::asset('assets/img/faces/6..jpg')}}"><span class="avatar-status profile-status bg-green"></span>
				</div>
				<div class="user-info">
					<h4 class="font-weight-semibold mt-3 mb-0" style="color: blue;">{{ optional(Auth::user())->name }}</h4>
					<br>
				</div>
			</div>
		</div>

				<ul class="side-menu">
				<li class="side-item side-item-category">{{ __('home.main_page') }}</li>
					<li class="slide">
						<a class="side-menu__item" href="{{ url('/' . ($page = 'home')) }}"><svg
								xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
								<path d="M0 0h24v24H0V0z" fill="none" />
								<path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
								<path
									d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
							</svg><span class="side-menu__label" >{{ __('home.ninox_program') }}</span></a>
								</li>
								@can('الإعدادت')
								<li class="side-item side-item-category">{{ __('home.Settings') }}</li>
								@endcan()
								<li class="slide">
									@can('الإعدادات النظام'	)
									<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3"/><path d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z"/></svg><span class="side-menu__label">{{ __('home.Main Settings') }}</span><i class="angle fe fe-chevron-down"></i></a>
									@endcan()
									<ul class="slide-menu">
									@can('إعدادات الفروع')
										<li>
											<a class="slide-item" href="{{ url('/' . $page='branches') }}">
												<i class="fas fa-code-branch"></i> {{ __('home.Branch Settings') }}
											</a>
										</li>
									@endcan

									@can('إعدادات المؤسسة')
										<li>
											<a class="slide-item" href="{{ url('/' . $page='company') }}">
												<i class="fas fa-building"></i> {{ __('home.Company') }}
											</a>
										</li>
									@endcan

									@can('إعدادات الضريبة')
										<li>
											<a class="slide-item" href="{{ url('/' . $page='taxes') }}">
												<i class="fas fa-percentage"></i> {{ __('home.Tax') }}
											</a>
										</li>
									@endcan

									@can('إعدادات الخزنة')
										<li>
											<a class="slide-item" href="{{ url('/' . $page='cashboxes') }}">
												<i class="fas fa-cash-register"></i> {{ __('home.Cashboxe') }}
											</a>
										</li>
									@endcan
									@can('معاملات الخزنة')
										<li>
											<a class="slide-item" href="{{ url('/' . $page='transactions') }}">
												<i class="fas fa-cash-register"></i> {{ __('home.transactions') }}
											</a>
										</li>
									@endcan
								</ul>

								</li>

							<li class="slide">
								@can('إعدادات الحسابات')
									<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
										<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
											<path d="M0 0h24v24H0V0z" fill="none"/>
											<path d="M3.31 11l2.2 8.01L18.5 19l2.2-8H3.31zM12 17c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" opacity=".3"/>
											<path d="M22 9h-4.79l-4.38-6.56c-.19-.28-.51-.42-.83-.42s-.64.14-.83.43L6.79 9H2c-.55 0-1 .45-1 1 0 .09.01.18.04.27l2.54 9.27c.23.84 1 1.46 1.92 1.46h13c.92 0 1.69-.62 1.93-1.46l2.54-9.27L23 10c0-.55-.45-1-1-1zM12 4.8L14.8 9H9.2L12 4.8zM18.5 19l-12.99.01L3.31 11H20.7l-2.2 8zM12 13c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
										</svg>
										<span class="side-menu__label">{{ __('home.Account Settings') }}</span>
										<i class="angle fe fe-chevron-down"></i>
									</a>
								@endcan

								<ul class="slide-menu">
									@can('أنواع الحسابات')
									<li><a class="slide-item" href="{{ url('/' . $page='account_types') }}"><i class="fa fa-list-alt mr-2"></i> {{ __('home.TypesAccounts') }}</a></li>
									@endcan

									@can('الحسابات')
									<li><a class="slide-item" href="{{ url('/' . $page='accounts') }}"><i class="fa fa-book mr-2"></i> {{ __('home.Accounts') }}</a></li>
									@endcan
									@can('الحركات المالية')
									<li><a class="slide-item" href="{{ url('/' . $page='journal_entries') }}"><i class="fa fa-random mr-2"></i> {{ __('home.FinancialTransactions') }}</a></li>
									@endcan
									
									@can('السنة المالية')
									<li><a class="slide-item" href="{{ url('/' . $page='fiscal_years') }}"><i class="fa fa-calendar-alt mr-2"></i> {{ __('home.FinancialYear') }}</a></li>
									@endcan

									@can('كشف الحساب')
									<li><a class="slide-item" href="{{ route('journal_entry_lines.index') }}"><i class="fa fa-file-alt mr-2"></i>{{ __('home.Simplifiedentry') }}</a></li>
									<li><a class="slide-item" href="/"><i class="fa fa-file-alt mr-2"></i> {{ __('home.AccountStatement') }}</a></li>
									
									@endcan

									<li><a class="slide-item" href="{{ route('reconciliations.index') }}"><i class="fa fa-chart-line mr-2"></i> {{ __('home.Financialsettlement') }}</a></li>

									
								</ul>
							</li>


					@can('إدارة المستخدمين')
					<li class="side-item side-item-category">{{ __('home.Users Management') }}</li>
					@endcan()
					<li class="slide">
						@can('المستخدمين')
						<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" ><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15 11V4H4v8.17l.59-.58.58-.59H6z" opacity=".3"/><path d="M21 6h-2v9H6v2c0 .55.45 1 1 1h11l4 4V7c0-.55-.45-1-1-1zm-5 7c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v14l4-4h10zM4.59 11.59l-.59.58V4h11v7H5.17l-.58.59z"/></svg><span class="side-menu__label">{{ __('home.Users') }}</span><i class="angle fe fe-chevron-down"></i></a>
						@endcan()
						<ul class="slide-menu">
							@can('قائمة المستخدمين')
							<li>
								<a class="slide-item" href="{{ url('/' . $page='users') }}">
									<i class="fa fa-user mr-2"></i> {{ __('home.User List') }}
								</a>
							</li>
							@endcan()
							@can('صلاحيات المستخدمين')
							<li>
								<a class="slide-item" href="{{ url('/' . $page='roles') }}">
									<i class="fa fa-user-shield mr-2"></i> {{ __('home.User Permissions') }}
								</a>
							</li>
							@endcan()
						</ul>
					</li>
					
					@can('إدارة المخزن')
						<li class="side-item side-item-category">
							<i class="fa fa-warehouse mr-2"></i> {{ __('home.Store Management') }}
						</li>
					@endcan

					<li class="slide">
						@can('المخازن ')
							<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
								<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
									<path d="M0 0h24v24H0V0z" fill="none"/>
									<path d="M15 11V4H4v8.17l.59-.58.58-.59H6z" opacity=".3"/>
									<path d="M21 6h-2v9H6v2c0 .55.45 1 1 1h11l4 4V7c0-.55-.45-1-1-1zm-5 7c.55 0 1-.45 1-1V3c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v14l4-4h10zM4.59 11.59l-.59.58V4h11v7H5.17l-.58.59z"/>
								</svg>
								<span class="side-menu__label">{{ __('home.Stores') }}</span>
								<i class="angle fe fe-chevron-down"></i>
							</a>
						@endcan

						<ul class="slide-menu">
							@can('قائمة المخازن ')
							<li>
								<a class="slide-item" href="{{ url('/' . $page='stores') }}">
									<i class="fa fa-store-alt mr-2"></i> {{ __('home.Stores List') }}
								</a>
							</li>
							@endcan

							@can('إضافة مخزن')
							<li>
								<a class="slide-item" href="{{ url('/' . $page='/') }}">
									<i class="fa fa-plus-square mr-2"></i> {{ __('home.Add Store') }}
								</a>
							</li>
							@endcan

							@can('جرد المخزن')
							<li>
								<a class="slide-item" href="{{ url('/' . $page='/') }}">
									<i class="fa fa-clipboard-list mr-2"></i> {{ __('home.Inventory') }}
								</a>
							</li>
							@endcan
						</ul>
					</li>


					@can('إدارة الأصناف')
						<li class="side-item side-item-category">
							<i class="fa fa-boxes mr-2"></i> {{ __('home.Items Management') }}
						</li>
					@endcan

						<li class="slide">
							@can('الأصناف')
								<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
										<path d="M0 0h24v24H0V0z" fill="none"/>
										<path d="M4 12c0 4.08 3.06 7.44 7 7.93V4.07C7.05 4.56 4 7.92 4 12z" opacity=".3"/>
										<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93s3.05-7.44 7-7.93v15.86zm2-15.86c1.03.13 2 .45 2.87.93H13v-.93zM13 7h5.24c.25.31.48.65.68 1H13V7zm0 3h6.74c.08.33.15.66.19 1H13v-1zm0 9.93V19h2.87c-.87.48-1.84.8-2.87.93zM18.24 17H13v-1h5.92c-.2.35-.43.69-.68 1zm1.5-3H13v-1h6.93c-.04.34-.11.67-.19 1z"/>
									</svg>
									<span class="side-menu__label">{{ __('home.Items') }}</span>
									<i class="angle fe fe-chevron-down"></i>
								</a>
							@endcan

							<ul class="slide-menu">
								@can('إضافة مجموعات')
								<li>
									<a class="slide-item" href="{{ url('/' . $page='categories') }}">
										<i class="fa fa-layer-group mr-2"></i> {{ __('home.Add Categories') }}
									</a>
								</li>
								@endcan

								@can('إضافة الوحدات')
								<li>
									<a class="slide-item" href="{{ url('/' . $page='units') }}">
										<i class="fa fa-ruler-combined mr-2"></i> {{ __('home.Add Units') }}
									</a>
								</li>
								@endcan

								@can('إضافة صنف')
								<li>
									<a class="slide-item" href="{{ url('/' . $page='products') }}">
										<i class="fa fa-box-open mr-2"></i> {{ __('home.Add Product') }}
									</a>
								</li>
								@endcan
							</ul>
						</li>

					
						@can('إدارة الفواتير')
								<li class="side-item side-item-category">
									<i class="fa fa-file-invoice-dollar mr-2"></i> {{ __('home.Invoice Management') }}
								</li>
							@endcan

							
							<li class="slide">
								@can('المشتريات')
									<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
										<i class="fa fa-cart-arrow-down side-menu__icon"></i>
										<span class="side-menu__label">{{ __('home.Purchase Invoice') }}</span>
										<i class="angle fe fe-chevron-down"></i>
									</a>
								@endcan
								<ul class="slide-menu">
									@can('فاتورة مشتريات')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='purchases') }}">
											<i class="fa fa-plus-square mr-2"></i> {{ __('home.Add Purchase') }}
										</a>
									</li>
									@endcan
									@can('مرتجع المشتريات')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='ret_purchases') }}">
											<i class="fa fa-undo mr-2"></i> {{ __('home.Purchase Return') }}
										</a>
									</li>
									@endcan
								</ul>
							</li>

							
							<li class="slide">
								@can('المبيعات')
									<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
										<i class="fa fa-cart-plus side-menu__icon"></i>
										<span class="side-menu__label">{{ __('home.Sale Invoice') }}</span>
										<i class="angle fe fe-chevron-down"></i>
									</a>
								@endcan
								<ul class="slide-menu">
									@can('فاتورة مبيعات')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='invoices') }}">
											<i class="fa fa-plus-square mr-2"></i> {{ __('home.Add Sales') }}
										</a>
									</li>
									@endcan
									@can('مرتجع مبيعات')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='ret_invoices') }}">
											<i class="fa fa-undo mr-2"></i> {{ __('home.Sales Return') }}
										</a>
									</li>
									@endcan
								</ul>
							</li>

							
							<li class="slide">
								@can('عروض السعر')
									<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
										<i class="fa fa-file-signature side-menu__icon"></i>
										<span class="side-menu__label">{{ __('home.Quotations') }}</span>
										<i class="angle fe fe-chevron-down"></i>
									</a>
								@endcan
								<ul class="slide-menu">
									@can('إضافة عرض سعر')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='quotations') }}">
											<i class="fa fa-plus-circle mr-2"></i> {{ __('home.Add Quotation') }}
										</a>
									</li>
									@endcan
								</ul>
							</li>


							@can('العملاء/الموردين')
								<li class="side-item side-item-category">
									<i class="fa fa-users-cog mr-2"></i> {{ __('home.Customers/Suppliers') }}
								</li>
							@endcan

							
							<li class="slide">
								@can('الموردين')
									<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
										<i class="fa fa-truck-loading side-menu__icon"></i>
										<span class="side-menu__label">{{ __('home.Suppliers') }}</span>
										<i class="angle fe fe-chevron-down"></i>
									</a>
								@endcan
								<ul class="slide-menu">
									@can('إضافة مورد')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='suppliers') }}">
											<i class="fa fa-plus mr-2"></i> {{ __('home.Add Supplier') }}
										</a>
									</li>
									@endcan
									@can('سند صرف')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='supplier_transactions') }}">
											<i class="fa fa-money-check-alt mr-2"></i> {{ __('home.Supplier Receipt') }}
										</a>
									</li>
									@endcan
								</ul>
							</li>

							
							<li class="slide">
								@can('العملاء')
									<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
										<i class="fa fa-user-friends side-menu__icon"></i>
										<span class="side-menu__label">{{ __('home.Customers') }}</span>
										<i class="angle fe fe-chevron-down"></i>
									</a>
								@endcan
								<ul class="slide-menu">
									@can('إضافة عميل')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='customers') }}">
											<i class="fa fa-plus mr-2"></i> {{ __('home.Add Customer') }}
										</a>
									</li>
									@endcan
									@can('سند قبض')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='client_transactions') }}">
											<i class="fa fa-hand-holding-usd mr-2"></i> {{ __('home.Customer Receipt') }}
										</a>
									</li>
									@endcan
								</ul>
							</li>


							@can('إدارة الموظفين')
								<li class="side-item side-item-category">
									<i class="fa fa-id-badge mr-2"></i> {{ __('home.Employee Management') }}
								</li>
							@endcan

							<li class="slide">
								@can('الموظفين')
									<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
										<i class="fa fa-user-tie side-menu__icon"></i>
										<span class="side-menu__label">{{ __('home.Employees') }}</span>
										<i class="angle fe fe-chevron-down"></i>
									</a>
								@endcan
								<ul class="slide-menu">
									@can('قائمة الموظفين')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='employees') }}">
											<i class="fa fa-plus mr-2"></i> {{ __('home.Add Employee') }}
										</a>
									</li>
									@endcan
									@can('راتب الموظف')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='salary_payments') }}">
											<i class="fa fa-money-bill-wave mr-2"></i> {{ __('home.Salary Employee') }}
										</a>
									</li>
									@endcan
								</ul>
							</li>


							@can('إدارة المصروفات')
								<li class="side-item side-item-category">
									<i class="fa fa-wallet mr-2"></i> {{ __('home.Expense Management') }}
								</li>
							@endcan

							<li class="slide">
								@can('المصروفات')
								<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
									<i class="fa fa-receipt side-menu__icon"></i>
									<span class="side-menu__label">{{ __('home.Expenses') }}</span>
									<i class="angle fe fe-chevron-down"></i>
								</a>
								@endcan
								<ul class="slide-menu">
									@can('التصنيف')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='e_categories') }}">
											<i class="fa fa-folder-open mr-2"></i> {{ __('home.Add Category') }}
										</a>
									</li>
									@endcan
									@can('إضافة مصروف')
									<li>
										<a class="slide-item" href="{{ url('/' . $page='expenses') }}">
											<i class="fa fa-plus-circle mr-2"></i> {{ __('home.Add Expense') }}
										</a>
									</li>
									@endcan
								</ul>
							</li>

					
							@can('إدارة التقارير')
							<li class="side-item side-item-category">
								<i class="fa fa-chart-line mr-2"></i> {{ __('home.ReportManagement') }}
							</li>
							@endcan

							<li class="slide">
								@can('التقارير')
								<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
									<i class="fa fa-file-alt side-menu__icon"></i>
									<span class="side-menu__label">{{ __('home.reports') }}</span>
									<i class="angle fe fe-chevron-down"></i>
								</a>
								@endcan
								<ul class="slide-menu">

									
									@can('تقارير الفواتير')
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
											<i class="fa fa-file-invoice mr-2"></i> <span class="sub-side-menu__label">{{ __('home.InvoiceReports') }}</span>
											<i class="sub-angle fe fe-chevron-down"></i>
										</a>
										<ul class="sub-slide-menu">
											@can('تقرير جميع الفاواتير')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='/') }}"><i class="fa fa-list mr-2"></i> {{ __('home.AllInvoicesReport') }}</a></li>
											@endcan
										</ul>
									</li>
									@endcan

									
									@can('تقارير المبيعات')
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
											<i class="fa fa-cash-register mr-2"></i> <span class="sub-side-menu__label">{{ __('home.SalesReports') }}</span>
											<i class="sub-angle fe fe-chevron-down"></i>
										</a>
										<ul class="sub-slide-menu">
											@can('تقرير المبيعات')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='invoices_report') }}"><i class="fa fa-chart-bar mr-2"></i> {{ __('home.SaleReport') }}</a></li>
											@endcan
											@can('تقرير مرتجع المبيعات')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='ret_invoices_report') }}"><i class="fa fa-undo-alt mr-2"></i> {{ __('home.SalesReturnReport') }}</a></li>
											@endcan
										</ul>
									</li>
									@endcan

									
									@can('تقارير المشتريات')
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
											<i class="fa fa-shopping-cart mr-2"></i> <span class="sub-side-menu__label">{{ __('home.PurchaseReports') }}</span>
											<i class="sub-angle fe fe-chevron-down"></i>
										</a>
										<ul class="sub-slide-menu">
											@can('تقرير المشتريات')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='purchases_report') }}"><i class="fa fa-chart-area mr-2"></i> {{ __('home.PurchaseReport') }}</a></li>
											@endcan
											@can('تقرير مرتجع المشتريات')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='ret_purchases_report') }}"><i class="fa fa-undo mr-2"></i> {{ __('home.PurchaseReturnReport') }}</a></li>
											@endcan
										</ul>
									</li>
									@endcan

									
									@can('تقارير الأصناف')
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
											<i class="fa fa-boxes mr-2"></i> <span class="sub-side-menu__label">{{ __('home.ItemReports') }}</span>
											<i class="sub-angle fe fe-chevron-down"></i>
										</a>
										<ul class="sub-slide-menu">
											@can('تقرير مجموعة الأصناف')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='/') }}"><i class="fa fa-layer-group mr-2"></i> {{ __('home.ProductCategoriesReport') }}</a></li>
											@endcan
											@can('تقرير الوحدات')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='/') }}"><i class="fa fa-ruler-combined mr-2"></i> {{ __('home.UnitsReport') }}</a></li>
											@endcan
											@can('تقرير الأصناف')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='/') }}"><i class="fa fa-box mr-2"></i> {{ __('home.ItemReport') }}</a></li>
											@endcan
										</ul>
									</li>
									@endcan

								</ul>
							</li>

					
							@can('إدارة الدعم')
							<li class="side-item side-item-category">
								<i class="fa fa-headset mr-2"></i> {{ __('home.Support Management') }}
							</li>
							@endcan

							<li class="slide">
								@can('إدارة الدعم')
								<a class="side-menu__item" data-toggle="slide" href="{{ url('/' . $page='#') }}">
									<i class="fa fa-life-ring side-menu__icon"></i>
									<span class="side-menu__label">{{ __('home.Supports') }}</span>
									<i class="angle fe fe-chevron-down"></i>
								</a>
								@endcan
								<ul class="slide-menu">

									
									@can('دعم')
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
											<i class="fa fa-tools mr-2"></i> <span class="sub-side-menu__label">{{ __('home.Support') }}</span>
											<i class="sub-angle fe fe-chevron-down"></i>
										</a>
										<ul class="sub-slide-menu">
											@can('دعم')
											<li><a class="sub-slide-item" href="{{ url('/' . $page='#') }}"><i class="fa fa-headphones-alt mr-2"></i> {{ __('home.Technical Support') }}</a></li>
											@endcan
											
										</ul>
									</li>
									@endcan

								</ul>
								<ul class="slide-menu">

									@can('الإشتراكات')
									<li class="sub-slide">
										<a class="sub-side-menu__item" data-toggle="sub-slide" href="#">
											<i class="fa fa-tools mr-2"></i> <span class="sub-side-menu__label">{{ __('home.subscriptions') }}</span>
											<i class="sub-angle fe fe-chevron-down"></i>
										</a>
										<ul class="sub-slide-menu">
											@can('الإشتراك')
												<li><a class="sub-slide-item" href="{{ route('subscription.plans') }}">
												<i class="fa fa-headphones-alt mr-2"></i> {{ __('home.subscription') }}</a></li>
											@endcan
												@can('شاشة متابعة الاشتراك')
													<li><a class="sub-slide-item" href="{{ route('dashboard.statistics') }}">
													<i class="fa fa-chart-line mr-2"></i> {{ __('home.ShowSubscription') }}</a></li>
												@endcan
												@can('إدارة الاشتراك')
													<li><a class="sub-slide-item" href="{{ route('subscription.manage') }}">
													<i class="fa fa-cogs mr-2"></i> {{ __('home.SubscriptionMange') }}</a></li>
												@endcan
												@can('تاريخ المدفوعات')
													<li><a class="sub-slide-item" href="{{ route('payments.history') }}">
													<i class="fa fa-history mr-2"></i> {{ __('home.PaymentMange') }}</a></li>
												@endcan
										</ul>
									</li>
									@endcan

								</ul>


							</li>

				</ul>
			</div>
		</aside>
<!-- main-sidebar -->
