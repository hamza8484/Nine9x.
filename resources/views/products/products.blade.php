@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
  {{ __('home.MainPage11') }}
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Products') }}</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.AddProduct') }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@foreach (['Add', 'Update', 'Delete'] as $msg)
    @if(session($msg))
        <div class="alert alert-{{ 
            $msg == 'Add' ? 'info' : 
            ($msg == 'Update' ? 'warning' : 'danger') 
        }} alert-dismissible fade show" role="alert">
            <strong>{{ session($msg) }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach
<script>
    // إخفاء الرسائل بعد 10 ثواني
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 10000);
</script>


    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-lg rounded">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a class="btn btn-outline-primary btn-lg px-4 py-2" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8" title="إضافة صنف جديد">
                        {{ __('home.AddNewProduct') }}
                    </a>
					<a class="btn btn-outline-primary btn-lg px-4 py-2" data-effect="effect-scale" href="{{ route('products.print_product_barcode') }}">
                        {{ __('home.BarcodeProductPrint') }}	
					</a>
                </div>

				<div class="card-body"> 
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-md-nowrap" id="example1" data-page-length="50">
                            <thead>
                                <tr>
                                    <th class="text-center"  style=" width: 20px;" >{{ __('home.No.') }}</th>
                                    <th class="text-center"  style=" width: 200px;" >{{ __('home.Product') }}</th>
                                    <th class="text-center"  style=" width: 100px;" >{{ __('home.Categoey') }}</th>
                                    <th class="text-center"  style=" width: 40px;" >{{ __('home.Units') }}</th>
                                    <th class="text-center"  style=" width: 40px;" >{{ __('home.Barcode') }}</th>
                                    <th class="text-center"  style=" width: 50px;" >{{ __('home.Price') }}</th>
                                    <th class="text-center"  style=" width: 50px;" >{{ __('home.VAT') }}</th>
                                    <th class="text-center"  style=" width: 50px;" >{{ __('home.Total') }}</th> 
                                    <th class="text-center"  style=" width: 50px;" >{{ __('home.Qty') }}</th> 
                                    <th class="text-center"  style=" width: 50px;" >{{ __('home.Status') }}</th>
                                    <th class="text-center"  style=" width: 100px;" >{{ __('home.Events') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($products as $Product)
                                    <?php $i++; ?>
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ $Product->product_name }}</td>
                                        <td class="text-center">{{ $Product->Category->c_name ?? 'غير موجود' }}</td>
                                        <td class="text-center">{{ $Product->unit->unit_name ?? 'غير موجود' }}</td>
                                        <td class="text-center">{{ $Product->product_barcode }}</td>
                                        <td class="text-center">{{ number_format($Product->product_sale_price, 2) }}</td>
                                        <td class="text-center">{{ number_format($Product->tax_rate, 2) }}%</td>
                                        <td class="text-center">{{ number_format($Product->product_total_price, 2) }}</td>
                                        <td class="text-center">{{ $Product->product_quantity }}</td>
                                        <td class="text-center">{{ $Product->product_status }}</td>
                                        <td>
                                            <!-- زر التعديل داخل الجدول -->
                                            <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                data-id="{{ $Product->id }}" 
                                                data-product_name="{{ $Product->product_name }}"
                                                data-product_barcode="{{ $Product->product_barcode }}"
                                                data-product_serial_number="{{ $Product->product_serial_number }}"
                                                data-store_id="{{ $Product->store_id }}"
                                                data-category_id="{{ $Product->category_id }}"
                                                data-unit_id="{{ $Product->unit_id }}"
                                                data-tax_id="{{ $Product->tax_id }}"
                                                data-tax_rate="{{ $Product->tax_rate }}"
                                                data-product_expiry_date="{{ $Product->product_expiry_date }}"
                                                data-product_image="{{ $Product->product_image }}"
                                                data-product_stock_quantity="{{ $Product->product_quantity }}"
                                                data-product_quantity="{{ $Product->product_quantity }}"
                                                data-product_sale_price="{{ $Product->product_sale_price }}"
                                                data-product_total_price="{{ $Product->product_total_price }}"
                                                data-product_status="{{ $Product->product_status }}"
                                                data-expiry_date_status="{{ $Product->expiry_date_status }}"
                                                data-created_by="{{ $Product->created_by }}"
                                                data-product_description="{{ $Product->product_description }}"
                                                data-toggle="modal" 
                                                href="#exampleModal2" 
                                                title="تعديل">
                                                <i class="las la-pen"></i>
                                            </a>

                                            <a class="btn btn-danger btn-sm" data-effect="effect-scale" 
                                                data-id="{{ $Product->id }}" 
                                                data-c_name="{{ $Product->product_name }}" 
                                                data-toggle="modal" 
                                                href="#modaldemo9" 
                                                title="حذف">
                                                <i class="las la-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>

       <!-- اضافة الصنف-->
		<div class="modal" id="modaldemo8">
			<div class="modal-dialog modal-dialog-centered modal-lg " role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h6 class="modal-title">{{ __('home.AddNewProduct') }}</h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
							@csrf
                    <div class="col-lg-12 col-md-12">
						<div class="card">
                        <div class="card-body">
							<div class="row">
								<div class="form-group col-md-7">
									<label for="product_name">{{ __('home.ProductName') }}</label>
									<input type="text" class="form-control" id="product_name" name="product_name" required>
								</div>

								<div class="form-group col-md-5">
									<label for="product_barcode">{{ __('home.ProductBarcode') }}</label>
									<input type="text" class="form-control" id="product_barcode" name="product_barcode" value="{{ $newBarcode ?? old('product_barcode') }}" readonly>
								</div>
                                </div>
                                </div>
                                </div>
							</div>

							<hr>

							<div class="row">
							<div class="form-group col-md-7">
								<label for="product_serial_number">{{ __('home.SerialNo') }}</label>
								<input type="text" class="form-control" id="product_serial_number" name="product_serial_number" value="{{ $newSerialNumber ?? old('product_serial_number') }}" required>
							</div>
							<div class="form-group col-md-5">
								<label for="product_status">{{ __('home.Status') }}</label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" id="product_status_active" name="product_status" value="مفعل" required checked>
									<label class="form-check-label" for="product_status_active">{{ __('home.Active') }}</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" id="product_status_inactive" name="product_status" value="غير مفعل" required>
									<label class="form-check-label" for="product_status_inactive">{{ __('home.Inactive') }}</label>
								</div>
							</div>

							</div>

							<hr>

							<div class="row">
								<div class="form-group col-md-8">
									<label for="expiry_date_status">{{ __('home.ExpirDate') }}</label><br>
									<div class="form-check form-check-inline">
										<input type="date" class="form-control" id="product_expiry_date" name="product_expiry_date" disabled>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" id="expiry_date_enabled" name="expiry_date_status" value="مفعل" required>
										<label class="form-check-label" for="expiry_date_enabled">{{ __('home.Active') }}</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" id="expiry_date_disabled" name="expiry_date_status" value="غير مفعل" checked required>
										<label class="form-check-label" for="expiry_date_disabled">{{ __('home.Inactive') }}</label>
									</div>
								</div>
							</div>

							<hr>

							<div class="row">
								<!-- حقل السعر -->
								<div class="form-group col-md-4">
									<label for="product_sale_price">{{ __('home.Price') }}</label>
									<input type="number" class="form-control" id="product_sale_price" name="product_sale_price" step="0.01" min="0" required>
								</div>

								<!-- حقل الضريبة المخفي (يتم تحديده ولكن لا يظهر للمستخدم) -->
								<div class="form-group col-md-4" style="display: none;">
									<label for="tax_id">{{ __('home.VAT') }}</label>
									<select class="form-control" id="tax_id" name="tax_id" required>
										@foreach ($taxes as $tax)
											<option value="{{ $tax->id }}" data-tax-rate="{{ $tax->tax_rate }}">
												{{ $tax->tax_rate }}%
											</option>
										@endforeach
									</select>
								</div>

								<!-- حقل نسبة الضريبة (سيتم تحديثه عند اختيار الضريبة) -->
								<div class="form-group col-md-4">
									<label for="tax_rate">{{ __('home.VatRate') }}</label>
									<input type="number" class="form-control" id="tax_rate" name="tax_rate" step="0.01" min="0" readonly>
								</div>

								<!-- حقل المجموع (يتم حسابه بناءً على السعر ونسبة الضريبة) -->
								<div class="form-group col-md-4">
									<label for="product_total_price">{{ __('home.Total') }}</label>
									<input type="number" class="form-control" id="product_total_price" name="product_total_price" step="0.01" min="0" readonly>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-4">
									<label for="product_quantity">{{ __('home.NewQty') }}</label>
									<input type="number" class="form-control" id="product_quantity" name="product_quantity" min="0" step="1" required>
								</div>
								<div class="form-group col-md-4">
									<label for="product_stock_quantity">{{ __('home.StoreQty') }}</label>
									<input type="number" class="form-control" id="product_stock_quantity" name="product_stock_quantity" min="0" step="1" value="0" required readonly>
								</div>
							</div>

							<div class="row">
								<div class="form-group col-md-4">
									<label for="store_id">{{ __('home.StoreName') }}</label>
									<select class="form-control" id="store_id" name="store_id" required>
										@foreach ($stores as $store)
											<option value="{{ $store->id }}">{{ $store->store_name }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group col-md-4">
									<label for="category_id">{{ __('home.Categoey') }}</label>
									<select class="form-control" id="category_id" name="category_id" required>
										@foreach ($categories as $category)
											<option value="{{ $category->id }}">{{ $category->c_name }}</option>
										@endforeach
									</select>
								</div>

								<div class="form-group col-md-4">
									<label for="unit_id">{{ __('home.Units') }}</label>
									<select class="form-control" id="unit_id" name="unit_id" required>
										@foreach ($units as $unit)
											<option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<hr>

							<div class="row">
								<div class="form-group col-md-4">
									<label for="created_by">{{ __('home.User Name') }}</label>
									<input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
								</div>
								<div class="form-group">
                                    <label for="product_image">{{ __('home.ProductPhoto') }} </label>
                                    <div style="display: flex; align-items: center;">
                                        <!-- حقل اختيار الصورة -->
                                        <input type="file" id="product_image" name="product_image" class="form-control" style="margin-right: 15px;">
                                        
                                        <!-- عرض الصورة بجانب المدخل -->
                                        <div style="max-width: 100px; max-height: 100px; overflow: hidden; border: 1px solid #ccc;">
                                            <!-- إذا كانت الصورة موجودة، عرضها، وإذا لم تكن موجودة، عرض صورة افتراضية -->
                                            
                                        </div>
                                    </div>
                                </div>


							</div>

							<div class="form-group">
								<label for="product_description">{{ __('home.Notes') }} </label>
								<textarea class="form-control" id="product_description" name="product_description" rows="3" placeholder="{{ __('home.NoNotes') }}"></textarea>
							</div>


							<div class="modal-footer">
								<button type="submit" class="btn btn-success btn-lg w-100">{{ __('home.Save') }}</button>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Close') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

        <div class="modal" id="exampleModal2">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __('home.EditProduct') }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="{{ route('products.update', ':id') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- استخدام PUT لتحديث المنتج -->

                    <!-- حقل اسم المنتج -->
                    <div class="form-group">
                        <label for="product_name">{{ __('home.ProductName') }}</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>

                    <!-- حقل باركود المنتج -->
                    <div class="form-group">
                        <label for="product_barcode">{{ __('home.ProductBarcode') }}</label>
                        <input type="text" class="form-control" id="product_barcode" name="product_barcode" required>
                    </div>

                    <!-- حقل الرقم التسلسلي للمنتج -->
                    <div class="form-group">
                        <label for="product_serial_number">{{ __('home.SerialNo') }}</label>
                        <input type="text" class="form-control" id="product_serial_number" name="product_serial_number" required>
                    </div>

                    <!-- حقل حالة المنتج -->
                    <div class="form-group">
                        <label>{{ __('home.Status') }}</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="product_status_active" name="product_status" value="مفعل" required>
                            <label class="form-check-label" for="product_status_active">{{ __('home.Active') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="product_status_inactive" name="product_status" value="غير مفعل" required>
                            <label class="form-check-label" for="product_status_inactive">{{ __('home.Inactive') }}</label>
                        </div>
                    </div>

                    <!-- حقل تاريخ انتهاء الصلاحية -->
                    <div class="form-group">
                        <label for="expiry_date_status">{{ __('home.ExpirDate') }}</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="expiry_date_enabled" name="expiry_date_status" value="مفعل">
                            <label class="form-check-label" for="expiry_date_enabled">{{ __('home.Active') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="expiry_date_disabled" name="expiry_date_status" value="غير مفعل" checked>
                            <label class="form-check-label" for="expiry_date_disabled">{{ __('home.Inactive') }}</label>
                        </div>
                        <input type="date" class="form-control mt-2" id="product_expiry_date" name="product_expiry_date" disabled>
                    </div>

                    <!-- حقل السعر -->
                    <div class="form-group">
                        <label for="product_sale_price">{{ __('home.Price') }}</label>
                        <input type="number" class="form-control" id="product_sale_price" name="product_sale_price" step="0.01" min="0" required>
                    </div>

                    <!-- حقل الضريبة -->
                    <div class="form-group">
                        <label for="tax_rate">{{ __('home.VatRate') }}</label>
                        <input type="number" class="form-control" id="tax_rate" name="tax_rate" step="0.01" min="0" readonly>
                    </div>

                    <!-- حقل المجموع (مع الضريبة) -->
                    <div class="form-group">
                        <label for="product_total_price">{{ __('home.Total') }}</label>
                        <input type="number" class="form-control" id="product_total_price" name="product_total_price" step="0.01" min="0" readonly>
                    </div>

                    <!-- حقل الكمية الجديدة -->
                    <div class="form-group">
                        <label for="product_quantity">{{ __('home.NewQty') }}</label>
                        <input type="number" class="form-control" id="product_quantity" name="product_quantity" min="0" step="1" required>
                    </div>

                    <!-- حقل الكمية في المخزن -->
                    <div class="form-group">
                        <label for="product_stock_quantity">{{ __('home.StoreQty') }}</label>
                        <input type="number" class="form-control" id="product_stock_quantity" name="product_stock_quantity" min="0" step="1" required readonly>
                    </div>

                    <!-- حقل اسم المتجر -->
                    <div class="form-group">
                        <label for="store_id">{{ __('home.StoreName') }}</label>
                        <select class="form-control" id="store_id" name="store_id" required>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- حقل الفئة -->
                    <div class="form-group">
                        <label for="category_id">{{ __('home.Categoey') }}</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->c_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- حقل الوحدة -->
                    <div class="form-group">
                        <label for="unit_id">{{ __('home.Units') }}</label>
                        <select class="form-control" id="unit_id" name="unit_id" required>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- حقل المستخدم الذي قام بإنشاء المنتج -->
                    <div class="form-group">
                        <label for="created_by">{{ __('home.User Name') }}</label>
                        <input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <!-- حقل صورة المنتج -->
                    <div class="form-group">
                        <label for="product_image">{{ __('home.ProductPhoto') }}</label>
                        <div style="display: flex; align-items: center;">
                            <input type="file" id="product_image" name="product_image" class="form-control" style="margin-right: 15px;">
                            <div style="max-width: 100px; max-height: 100px; overflow: hidden; border: 1px solid #ccc;">
                                <!-- إذا كانت الصورة موجودة، عرضها هنا -->
                                <img id="current_product_image" src="" alt="صورة المنتج" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <!-- حقل وصف المنتج -->
                    <div class="form-group">
                        <label for="product_description">{{ __('home.Notes') }}</label>
                        <textarea class="form-control" id="product_description" name="product_description" rows="3" placeholder="{{ __('home.NoNotes') }}"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">{{ __('home.SaveChanges') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


     
        <!--  حذف الصنف  -->
		<div class="modal" id="modaldemo9">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content modal-content-demo">
						<div class="modal-header">
							<h6 class="modal-title">{{ __('home.DeleteProduct') }}</h6>
							<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<form id="deleteForm" action="{{ route('products.destroy', ['product' => 0]) }}" method="POST">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}
								<p>{{ __('home.Messages') }}</p>

								<div class="modal-footer">
									<button type="submit" class="btn btn-danger">{{ __('home.Delete') }}</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('home.Cancel') }}</button>
								</div>
							</form>
						</div>
					</div>
				</div>
		</div>

	
</div>
</div>

   
@endsection

<style>
    .form-group label {
        text-align: right; /* توسيط النص داخل الـ label */
        display: block;
    }

    .form-control {
        text-align: center; /* توسيط النص داخل الـ input و textarea */
    }
</style>

@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>

<script src="{{URL::asset('assets/js/modal.js')}}"></script>


<script>
    document.getElementById('product_name').setAttribute('autocomplete', 'off');
</script>

	<!--  تاريخ الصلاحية -->
<script>
    // نبدأ بالتأكد من خيار "غير مفعل" هو الافتراضي
    document.addEventListener('DOMContentLoaded', function() {
        var expiryDateEnabled = document.getElementById('expiry_date_enabled');
        var expiryDateDisabled = document.getElementById('expiry_date_disabled');
        var expiryDateInput = document.getElementById('product_expiry_date');

        // عندما يتم تحديد "مفعل"، نقوم بتمكين حقل التاريخ
        expiryDateEnabled.addEventListener('change', function() {
            if (expiryDateEnabled.checked) {
                expiryDateInput.disabled = false; // تفعيل حقل التاريخ
            }
        });

        // عندما يتم تحديد "غير مفعل"، نقوم بتعطيل حقل التاريخ
        expiryDateDisabled.addEventListener('change', function() {
            if (expiryDateDisabled.checked) {
                expiryDateInput.disabled = true; // تعطيل حقل التاريخ
            }
        });

        // إذا كان "غير مفعل" هو الخيار الافتراضي عند تحميل الصفحة، نؤكد أنه معطل
        if (expiryDateDisabled.checked) {
            expiryDateInput.disabled = true; // تعطيل حقل التاريخ عند البدء
        }
    });
</script>
	<!-- العملية الحسابية -->
<script>
    // هذا الكود سيقوم بتحديث نسبة الضريبة في حقل tax_rate عند اختيار ضريبة من القائمة
    document.getElementById('tax_id').addEventListener('change', function() {
        // الحصول على نسبة الضريبة المختارة من البيانات المرتبطة بالعنصر
        var taxRate = this.selectedOptions[0].getAttribute('data-tax-rate');
        
        // تحديث حقل نسبة الضريبة
        document.getElementById('tax_rate').value = taxRate;

        // حساب المجموع عند اختيار الضريبة
        updateTotalPrice();
    });

    // تحديث المجموع عند إدخال السعر
    document.getElementById('product_sale_price').addEventListener('input', function() {
        updateTotalPrice();
    });

    // دالة لحساب المجموع بناءً على السعر ونسبة الضريبة
    function updateTotalPrice() {
        var price = parseFloat(document.getElementById('product_sale_price').value) || 0;
        var taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        
        var totalPrice = price + (price * (taxRate / 100));
        
        // تحديث حقل المجموع
        document.getElementById('product_total_price').value = totalPrice.toFixed(2);
    }

    // عند تحميل الصفحة، قم بتعيين أول ضريبة بشكل افتراضي
    window.addEventListener('load', function() {
        // تحديد أول ضريبة (افتراضيًا)
        var firstTaxOption = document.getElementById('tax_id').options[0];
        var defaultTaxRate = firstTaxOption.getAttribute('data-tax-rate');
        
        // تحديث حقل نسبة الضريبة بالقيمة الافتراضية
        document.getElementById('tax_rate').value = defaultTaxRate;
        
        // قم بحساب المجموع بناءً على السعر الأول والضريبة الافتراضية
        updateTotalPrice();
    });
</script>
	<!--  العملية الحسابية للكميات في المخزن -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var stockQuantityInput = document.getElementById('product_stock_quantity');
        var newQuantityInput = document.getElementById('product_quantity');

        // تحديث الكمية الإجمالية في المخزن عندما يتم إدخال الكمية الجديدة
        newQuantityInput.addEventListener('input', function() {
            // الحصول على الكمية الحالية في المخزن
            var currentStock = parseInt(stockQuantityInput.value) || 0;
            // الحصول على الكمية الجديدة
            var newQuantity = parseInt(newQuantityInput.value) || 0;

            // حساب الكمية الجديدة بعد إضافة الكمية الجديدة
            var totalQuantity = currentStock + newQuantity;

            // عرض الكمية الإجمالية في المخزن (إذا كنت تريد عرضها في حقل أو استخدامها)
            stockQuantityInput.value = totalQuantity;
        });
    });
</script>
	<!--   توليد الباركود -->
<script>
    // تعيين قيمة العداد بناءً على قيمة الباركود المرسل من PHP
    let barcodeCounter = {{ $newBarcode }};  // تعيين قيمة العداد من السيرفر

    let generatedBarcodes = new Set(); // لتخزين الباركودات المولدة وضمان عدم تكرارها

    document.getElementById("generate_barcode").addEventListener("click", function() {
        let barcode;

        // توليد الباركود التالي الذي لم يتم توليده بعد
        do {
            barcode = String(barcodeCounter).padStart(14, '0');
            barcodeCounter++; // زيادة العداد بعد كل محاولة
        } while (generatedBarcodes.has(barcode)); // التأكد من أن الباركود غير مكرر

        // إضافة الباركود المولد إلى مجموعة الباركودات المولدة
        generatedBarcodes.add(barcode);

        // وضع الباركود في الحقل
        document.getElementById("product_barcode").value = barcode;
    });
</script>

<!-- التعديل -->
<script>
    $(document).ready(function() {
    $('a[data-toggle="modal"]').on('click', function() {
        var id = $(this).data('id');
        var product_name = $(this).data('product_name');
        var product_barcode = $(this).data('product_barcode');
        var product_serial_number = $(this).data('product_serial_number');
        var store_id = $(this).data('store_id');
        var category_id = $(this).data('category_id');
        var unit_id = $(this).data('unit_id');
        var tax_id = $(this).data('tax_id');
        var tax_rate = $(this).data('tax_rate');
        var product_expiry_date = $(this).data('product_expiry_date');
        var product_image = $(this).data('product_image');
        var product_quantity = $(this).data('product_quantity');
        var product_sale_price = $(this).data('product_sale_price');
        var product_total_price = $(this).data('product_total_price');
        var product_status = $(this).data('product_status');
        var expiry_date_status = $(this).data('expiry_date_status');
        var created_by = $(this).data('created_by');
        var product_description = $(this).data('product_description');

        // تعيين القيم في الحقول داخل الـ Modal
        $('#exampleModal2 #id').val(id);
        $('#exampleModal2 #product_name').val(product_name);
        $('#exampleModal2 #product_barcode').val(product_barcode);
        $('#exampleModal2 #product_serial_number').val(product_serial_number);
        $('#exampleModal2 #store_id').val(store_id);
        $('#exampleModal2 #category_id').val(category_id);
        $('#exampleModal2 #unit_id').val(unit_id);
        $('#exampleModal2 #tax_id').val(tax_id);
        $('#exampleModal2 #tax_rate').val(tax_rate);
        $('#exampleModal2 #product_expiry_date').val(product_expiry_date);
        $('#exampleModal2 #product_quantity').val(product_quantity);
        $('#exampleModal2 #product_sale_price').val(product_sale_price);
        $('#exampleModal2 #product_total_price').val(product_total_price);
        $('#exampleModal2 #product_status').val(product_status);
        $('#exampleModal2 #created_by').val(created_by);
        $('#exampleModal2 #product_description').val(product_description);

        // التحقق من تاريخ الصلاحية
        if (expiry_date_status === 'مفعل') {
            $('#exampleModal2 #product_expiry_date').prop('disabled', false);
            $('#exampleModal2 #expiry_date_enabled').prop('checked', true);
            $('#exampleModal2 #expiry_date_disabled').prop('checked', false);
        } else {
            $('#exampleModal2 #product_expiry_date').prop('disabled', true);
            $('#exampleModal2 #expiry_date_enabled').prop('checked', false);
            $('#exampleModal2 #expiry_date_disabled').prop('checked', true);
        }

        // عرض الصورة
        if (product_image) {
            $('#exampleModal2 #product_image_preview').attr('src', product_image).show();
        } else {
            $('#exampleModal2 #product_image_preview').hide();
        }

        // حساب المجموع بناءً على السعر ونسبة الضريبة
        if (product_sale_price && tax_rate) {
            var total = parseFloat(product_sale_price) * (1 + (parseFloat(tax_rate) / 100));
            $('#exampleModal2 #product_total_price').val(total.toFixed(2));
        }
    });

    // تحديث المجموع عند تغيير السعر أو الضريبة
    $('#exampleModal2 #product_sale_price, #exampleModal2 #tax_rate').on('input', function() {
        var productSalePrice = $('#exampleModal2 #product_sale_price').val();
        var taxRate = $('#exampleModal2 #tax_rate').val();

        if (productSalePrice && taxRate) {
            var total = parseFloat(productSalePrice) * (1 + (parseFloat(taxRate) / 100));
            $('#exampleModal2 #product_total_price').val(total.toFixed(2));
        }
    });

    // تحديث حالة حقل تاريخ الصلاحية عند تغيير الاختيار
    $("input[name='expiry_date_status']").on("change", function() {
        if ($("#expiry_date_enabled").prop("checked")) {
            $("#product_expiry_date").prop("disabled", false);
        } else {
            $("#product_expiry_date").prop("disabled", true);
        }
    });
});

</script>

<!-- الحذف -->
<script>
    // عندما يفتح الـ modal
    $('#modaldemo9').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // الزر الذي فتح الـ modal
        var productId = button.data('id'); // الحصول على id المنتج من الزر
        var productName = button.data('c_name'); // الحصول على اسم المنتج من الزر

        // تحديث الـ action URL للـ form داخل الـ modal
        var actionUrl = '{{ route('products.destroy', ['product' => ':id']) }}';
        actionUrl = actionUrl.replace(':id', productId);
        $('#deleteForm').attr('action', actionUrl);
    });

    
</script>


<!--    صورة الصنف -->
<script>
    // عند اختيار صورة جديدة من المدخلات
    document.getElementById('product_image').addEventListener('change', function(event) {
        var reader = new FileReader();
        reader.onload = function(e) {
            // تعيين مصدر الصورة الجديدة في العنصر <img>
            document.getElementById('product_image_preview').src = e.target.result;
        }
        reader.readAsDataURL(this.files[0]);
    });
    document.getElementById('product_image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        // عرض الصورة المعينة حديثًا في المعاينة
        const previewImage = document.getElementById('product_image_preview');
        previewImage.src = e.target.result;
    };

    if (file) {
        reader.readAsDataURL(file);
    }
});

</script>


@endsection
