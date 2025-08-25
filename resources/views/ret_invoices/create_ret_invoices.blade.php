@extends('layouts.master')


@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@endsection


@section('title')
    {{ __('home.MainPage37') }}
@stop

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h3 class="content-title mb-0 my-auto">{{ __('home.RetarnInvoic') }}</h3><span class="text-muted mt-1 tx-20 mr-2 mb-0">/
                            {{ __('home.AddRetarnInvoic') }}</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
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

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
        <div class="card-header">
        {{ __('home.AddRetarnInvoic') }}
</div>

<style>
    .card-header {
        background-color: #007bff;  /* لون خلفية العنوان */
        color: white;  /* لون النص */
        font-size: 24px;  /* حجم الخط */
        font-weight: bold;  /* جعل النص غامقًا */
        text-align: center;  /* محاذاة النص في المنتصف */
        padding: 15px;  /* إضافة padding حول النص */
        border-radius: 8px 8px 0 0;  /* إضافة حدود مستديرة من الأعلى */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);  /* إضافة ظل خفيف حول العنصر */
        margin-bottom: 15px;  /* إضافة مسافة بين العنوان والمحتوى التالي */
    }
</style>

<div class="card-body">
                <form action="{{ route('ret_invoices.store') }}" method="post" class="form">
                    @csrf
                    <div class="row">
                    <input type="hidden" name="invoice_id" value="{{ $selectedInvoiceId }}">

                        <div class="col-3">
                            <div class="form-group">
                                <label for="ret_inv_number">{{ __('home.RetarnNo') }}</label>
                                <input type="text" name="ret_inv_number" id="ret_inv_number" class="form-control"
                                       value="{{ old('ret_inv_number', $newRetInvoiceNumber) }}" readonly style="text-align: center;">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="ret_inv_date">{{ __('home.RetarnDate') }}</label>
                                <input type="text" name="ret_inv_date" id="ret_inv_date" class="form-control datepicker"
                                       value="{{ old('ret_inv_date', now()->toDateString()) }}" required style="text-align: center;">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="invoice_select">{{ __('home.InvoiceSaleNo') }}</label>
                            <select name="invoice_id" id="invoice_select" class="form-control" required>
                            <option value="">اختيار رقم الفاتورة</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                    {{ $invoice->inv_number }}
                                </option>
                            @endforeach
                        </select>


                            @error('invoice_id')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-3">
                            <label for="customer_id">{{ __('home.Customers') }}</label>
                            <select name="customer_id" id="customer_id" class="form-control" required style="text-align: center;">
                                <option value="" disabled selected>{{ __('home.SelectCustomer') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" style="text-align: center;">{{ $customer->cus_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label for="store_id">{{ __('home.Store') }}</label>
                            <select name="store_id" id="store_id" class="form-control" style="text-align: center;">
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                @endforeach
                            </select>
                        </div>
                   
                        <div class="col-3">
                            <label for="ret_inv_payment">{{ __('home.PaymentMethod') }}</label>
                            <select name="ret_inv_payment" id="ret_inv_payment" class="form-control" required style="text-align: center;">
                                <option value="" disabled selected>اختر</option>
                                <option value="نقــدي">{{ __('home.Cash') }}</option>
                                <option value="شــبكة">{{ __('home.Card') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="branch_id">{{ __('home.Branch') }}</label>
                            <select name="branch_id" id="branch_id" class="form-control" style="text-align: center;">
                                <option value="" disabled selected>{{ __('home.Selectbranch') }}</option>   
                                @foreach($branchs as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->bra_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cashbox_id">{{ __('home.Cashbox') }}</label>
                            <select name="cashbox_id" id="cashbox_id" class="form-control" style="text-align: center;">
                                <option value="" disabled selected>{{ __('home.SelectCashbox') }}</option> 
                                @foreach($cashboxes as $cashbox)
                                    <option value="{{ $cashbox->id }}">{{ $cashbox->cash_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="account_id">{{ __('home.Account') }}</label>
                            <select name="account_id" id="account_id" class="form-control" style="text-align: center;">
                                <option value="" disabled selected>{{ __('home.SelectAccount') }}</option> 
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="created_by">{{ __('home.User Name') }}</label>
                            <input type="text" name="created_by" id="created_by" class="form-control" style="text-align: center;"
                                   value="{{ auth()->user()->name }}" disabled>
                        </div>
					</div>
                    
                    <hr>

                    <!-- جدول تفاصيل الفاتورة -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-hover" id="invoice_details">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 3%;"> {{ __('home.No.') }}</th>
                                    <th style="text-align: center; width: 13%;">{{ __('home.Categoey') }}</th>
                                    <th style="text-align: center; width: 16%;">{{ __('home.Product') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Units') }}</th>
                                    <th style="text-align: center; width: 17%;">{{ __('home.Barcode') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Qty') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Price') }}</th>
                                    <th style="text-align: center; width: 10%;">{{ __('home.Discount') }}</th>
                                    <th style="text-align: center; width: 15%;">{{ __('home.Total') }}</th>
                                    <th style="text-align: center; width: 1%;"> {{ __('home.Events') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="cloning_row" id="0">
                                    <td style="text-align: center;">#</td>
                                    <td>
                                        <select name="categories[]" class="form-control SlectBox" style="text-align: center;" required>
                                            <option value="" selected disabled>{{ __('home.Categoey') }}</option>
                                            @foreach($e_categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->c_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="products[]" class="form-control SlectBox" style="text-align: center;" required>
                                            <option value="" disabled selected>{{ __('home.Product') }}</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="units[]" class="form-control SlectBox" style="text-align: center;" required>
                                            <option value="" disabled selected>{{ __('home.Units') }}</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number" name="product_barcode[]" class="product_barcode form-control" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" step="0.01" class="quantity required form-control" required min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="product_price[]" step="0.01" class="product_price required form-control" required min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="product_discount[]" step="0.01" class="product_discount form-control">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="total_price[]" class="total_price form-control" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-minus"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <tr>
                        <td colspan="2" class="text-center">
                            <button type="button" class="btn_add btn btn-primary">{{ __('home.AddRow') }}</button>
                        </td>
                    </tr>
                    <!-- تفاصيل الدفع -->
                    <div class="card shadow-lg border-0 rounded-4 mt-4">
                        <div class="card-body p-4">
                            <table class="table table-bordered table-hover">
                                <tfoot>
                                    
                                    <tr>
                                        <td class="text-end">{{ __('home.InvoiceTotal') }}</td>
                                        <td>
                                            <input type="number" step="0.01" name="sub_total" id="sub_total" class="sub_total form-control" readonly style="width: 100%; text-align: center;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">{{ __('home.DiscountType') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input type="number" step="0.01" name="discount_value" id="discount_value" class="discount_value form-control" value="0.00" style="width: 60%; text-align: center;">
                                                <select name="discount_type" id="discount_type" class="discount_type custom-select form-control" style="width: 30%; text-align: center;">
                                                    <option value="نســبة">{{ __('home.Percentage') }}</option>
                                                    <option value="سعر ثابت">{{ __('home.Price') }}</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-end">{{ __('home.TaxRate') }}</td>
                                        <td>
                                            <input type="text" class="form-control" value="{{ $taxRate }}%" readonly style="width: 100%; text-align: center; ">
                                        </td>
                                        <td class="text-end">{{ __('home.VAT') }}</td>
                                        <td>
                                            <input type="number" step="0.01" name="vat_value" id="vat_value" class="vat_value form-control" readonly style="width: 100%; text-align: center;">
                                        </td>
                                    </tr>
                    
                                    <tr>
                                        <td class="text-end">{{ __('home.FinalTotal') }}</td>
                                        <td>
                                            <input type="number" step="0.01" name="total_deu" id="total_deu" class="total_deu form-control" readonly style="width: 100%; text-align: center;" value="0.00">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">{{ __('home.Paid') }}</td>
                                        <td>
                                            <input type="number" step="0.01" name="total_paid" id="total_paid" class="total_paid form-control" 
                                                style="width: 100%; text-align: center; font-weight: bold; background-color: #d4edda;" 
                                                oninput="updateRemaining()">
                                        </td>
                                        <td class="text-end">{{ __('home.Remaining Paid') }}</td>
                                        <td>
                                            <input type="number" step="0.01" name="total_unpaid" id="total_unpaid" class="total_unpaid form-control" 
                                                readonly style="width: 100%; text-align: center; font-weight: bold; background-color: #f8d7da;" value="0.00">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-end">{{ __('home.AmountDue') }}</td>
                                        <td>
                                            <input type="number" step="0.01" name="total_deferred" id="total_deferred" class="total_deferred form-control" readonly style="width: 100%; text-align: center;" value="0.00">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- زر الحفظ -->
                    <div class="text-right pt-3">
                        <button type="submit" name="save" class="btn btn-primary">{{ __('home.SaveReternInvoice') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

				
@endsection


@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


<script>
    $(document).ready(function(){
        // حساب المجموع في الصفوف
        $('#invoice_details').on('keyup blur', '.quantity, .product_price, .product_discount', function () {
            let $row = $(this).closest('tr');
            let quantity = parseFloat($row.find('.quantity').val()) || 0;
            let product_price = parseFloat($row.find('.product_price').val()) || 0;
            let product_discount = parseFloat($row.find('.product_discount').val()) || 0;

            // حساب المجموع قبل الخصم
            let total_price_before_discount = quantity * product_price;
            let total_price_after_discount = total_price_before_discount - product_discount;

            // التأكد من عدم ظهور قيمة سالبة في المجموع بعد الخصم
            if (total_price_after_discount < 0) {
                total_price_after_discount = 0;
            }

            $row.find('.total_price').val(total_price_after_discount.toFixed(2));

            // تحديث قيمة الإجمالي
            $('.sub_total').val(sum_total('.total_price'));

            // إعادة حساب الضريبة
            $('.vat_value').val(calculate_vat());

            // إعادة حساب المجموع الكلي
            $('.total_deu').val(sum_total_deu());
        });

        // التعامل مع التغييرات في قيمة الخصم أو نوع الخصم
        $('#discount_value, #discount_type').on('keyup blur change', function () {
            // إعادة حساب المجموع الكلي بعد تحديث الخصم
            $('.sub_total').val(sum_total('.total_price'));

            // إعادة حساب الضريبة
            $('.vat_value').val(calculate_vat());

            // إعادة حساب المجموع الكلي
            $('.total_deu').val(sum_total_deu());
        });

        // دالة حساب الإجمالي
        let sum_total = function ($selector){
            let sum = 0;
            $($selector).each(function () {
                let selectorVal = $(this).val() != '' ? $(this).val() : 0;
                sum += parseFloat(selectorVal);
            });
            return sum.toFixed(2);
        }

        // دالة حساب الضريبة (15%)
        let calculate_vat = function () {
            let sub_totalval = parseFloat($('.sub_total').val()) || 0;
            let discount_type = $('#discount_type').val();
            let discount_value = parseFloat($('#discount_value').val()) || 0;

            // حساب قيمة الخصم بناءً على النوع (نسبة مئوية أو قيمة ثابتة)
            let discountVal = discount_value != 0 ? discount_type == 'percentage' ? sub_totalval * (discount_value / 100) : discount_value : 0;

            // حساب الضريبة (15%)
            let vatVal = (sub_totalval - discountVal) * (taxRate / 100);
            return vatVal.toFixed(2);
        }

        // دالة حساب المجموع الكلي
        let sum_total_deu = function () {
            let sum = 0;
            let sub_totalval = parseFloat($('.sub_total').val()) || 0;
            let discount_type = $('#discount_type').val();
            let discount_value = parseFloat($('#discount_value').val()) || 0;

            // حساب الخصم بناءً على النوع
            let discountVal = discount_value != 0 ? discount_type == 'percentage' ? sub_totalval * (discount_value / 100) : discount_value : 0;

            // حساب قيمة الضريبة
            let vatVal = parseFloat($('.vat_value').val()) || 0;

            // حساب المجموع الكلي
            sum += sub_totalval;
            sum -= discountVal;  // خصم قيمة الخصم
            sum += vatVal;

            return sum.toFixed(2);
        }

        // إضافة صف جديد عند الضغط على زر الإضافة
        $(document).on('click', '.btn_add', function () {
            let trCount = $('#invoice_details').find('tr.cloning_row :last').length;
            let nuberIncr = trCount > 0 ? parseInt($('#invoice_details').find('tr.cloning_row :last').attr('id')) + 1 : 0;

            // إضافة صف جديد
            $('#invoice_details').find('tbody').append(
                '<tr class="cloning_row" id="' + nuberIncr + '">' +
                '<td style="text-align: center;">#</td>' +
                '<td>' +
                '<select name="categories[]" class="form-control SlectBox" style="text-align: center;" required >' +
                '<option value="" selected disabled>{{ __('home.Categoey') }}</option>' +
                '@foreach($e_categories as $category)' +
                '<option value="{{ $category->id }}">{{ $category->c_name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select name="products[]" class="form-control SlectBox product" style="text-align: center;" required >' +
                '<option value="" disabled selected>{{ __('home.Product') }}</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select name="units[]" class="form-control SlectBox unit" style="text-align: center;" required >' +
                '<option value="" disabled selected>{{ __('home.Units') }}</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<input type="number" name="product_barcode[]" class="product_barcode form-control" readonly>' +
                '</td>' +
                '<td>' +
                '<input type="number" name="quantity[]" step="0.01" class="quantity form-control" required min="0">' +
                '</td>' +
                '<td>' +
                '<input type="number" name="product_price[]" step="0.01" class="product_price form-control" required min="0">' +
                '</td>' +
                '<td>' +
                '<input type="number" name="product_discount[]" step="0.01" class="product_discount form-control">' +
                '</td>' +
                '<td>' +
                '<input type="number" step="0.01" name="total_price[]" class="total_price form-control" readonly>' +
                '</td>' +
                '<td><button type="button" class="btn btn-danger btn-sm delgated-btn"><i class="fa fa-minus"></i></button></td>' +
                '</tr>'
            );
        });

        // حذف الصف عند الضغط على زر الحذف
        $(document).on('click', '.delgated-btn', function (e) {
            e.preventDefault();
            $(this).parent().parent().remove();
                // تحديث قيمة الإجمالي
            $('.sub_total').val(sum_total('.total_price'));
                // إعادة حساب الضريبة
            $('.vat_value').val(calculate_vat());
                // إعادة حساب المجموع الكلي
            $('.total_deu').val(sum_total_deu());
        });
    });

    // إرسال قيمة الضريبة المخزنة من Blade إلى JavaScript
    var taxRate = parseFloat("{{ $taxRate }}");

    // عندما يتم اختيار مجموعة جديدة
    $(document).on('change', 'select[name="categories[]"]', function() {
        var categoryId = $(this).val(); // الحصول على ID المجموعة المحددة

        if (categoryId) {
            $.ajax({
                url: '/category/' + categoryId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var productsSelect = $(this).closest('tr').find('select[name="products[]"]');
                    productsSelect.empty(); // مسح الخيارات السابقة
                    productsSelect.append('<option value="" disabled selected>{{ __('home.AddProduct') }}</option>');
                    $.each(response, function(id, name) {
                        productsSelect.append('<option value="' + id + '">' + name + '</option>');
                    });
                }.bind(this)
            });
        }
    });

    // عندما يتم اختيار صنف جديد
    $(document).on('change', 'select[name="products[]"]', function() {
        var productId = $(this).val(); // الحصول على ID الصنف المحدد

        // إذا تم اختيار "اختر الصنف" مسح الحقول الأخرى
        if (!productId) {
            $(this).closest('tr').find('.product_barcode').val('');
            $(this).closest('tr').find('.product_price').val('');
            $(this).closest('tr').find('select[name="units[]"]').empty();
            $(this).closest('tr').find('select[name="units[]"]').append('<option value="" disabled selected>{{ __('home.AddUnits') }}</option>');
        } else {
            // في حال تم اختيار صنف معين
            $.ajax({
                url: '/product-details/' + productId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.product) {
                        // تحديث الحقول الخاصة بالباركود والسعر
                        $(this).closest('tr').find('.product_barcode').val(response.product.barcode);
                        $(this).closest('tr').find('.product_price').val(response.product.price);

                        // تحديث الـ select الخاص بالوحدة
                        var unitsSelect = $(this).closest('tr').find('select[name="units[]"]');
                        unitsSelect.empty(); // مسح الخيارات السابقة
                        unitsSelect.append('<option value="" disabled selected>{{ __('home.AddUnits') }}</option>');
                        unitsSelect.append('<option value="' + response.product.unit_id + '">' + response.product.unit + '</option>');
                    }
                }.bind(this)
            });
        }
    });

    // وظيفة لحساب المبلغ المتبقي
    function updateRemaining() {
        var totalAmount = parseFloat(document.getElementById("total_deu").value) || 0;
        var paidAmount = parseFloat(document.getElementById("total_paid").value) || 0;
        var remainingAmount = totalAmount - paidAmount;

        // تحديث حقل المبلغ المتبقي
        document.getElementById("total_unpaid").value = remainingAmount.toFixed(2);
    }

    // تحديث الحقل عند تغيير طريقة الدفع
    document.getElementById('inv_payment').addEventListener('change', function() {
        var paymentMethod = this.value;
        var totalAmount = parseFloat(document.getElementById('total_deu').value) || 0;

        if (paymentMethod === 'آجــل') {
            document.getElementById('total_deferred').value = totalAmount;
            document.getElementById('total_paid').value = 0;
            document.getElementById('total_unpaid').value = totalAmount;
        } else {
            document.getElementById('total_deferred').value = 0;
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('invoice_form');
        const purchaseSelect = document.getElementById('invoice_select');
        const hiddenInput = document.querySelector('input[name="invoice_id"]'); // حقل مخفي لإرسال القيمة

        // عندما يتغير الاختيار في الـ select
        purchaseSelect.addEventListener('change', function () {
            // تعيين القيمة المختارة في الحقل المخفي
            hiddenInput.value = purchaseSelect.value;
        });

        // عند محاولة إرسال النموذج
        form.addEventListener('submit', function (event) {
            const selectedOption = purchaseSelect.options[purchaseSelect.selectedIndex];
            const isReturned = selectedOption.getAttribute('data-returned') === 'true';

            if (isReturned) {
                // إظهار رسالة تحذيرية إذا كانت الفاتورة قد تم إرجاعها مسبقًا
                alert("{{ __('home.Messages3') }}");
                
                // منع إرسال النموذج
                event.preventDefault();
            }
        });
    });
</script>



@endsection