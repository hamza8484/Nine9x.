@extends('layouts.master')

@section('css')
<!-- يمكنك إضافة أي أكواد CSS هنا إذا لزم الأمر -->
@endsection

@section('title')
  {{ __('home.MainPage21') }}
@stop

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">{{ __('home.Quotations') }}</h4><span class="text-muted mt-1 tx-17 mr-2 mb-0">/ {{ __('home.AddQuotation') }}</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection

@section('content')
<div class="container mt-4">
    <!-- كارت العرض -->
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title" style="font-size: 24px; color: white;">{{ __('home.AddQuotation') }}</h3>
        <a href="{{ route('quotations.index') }}" class="btn btn-light"><i class="fa fa-arrow-left"></i> {{ __('home.BackQuotations') }}</a>
    </div>

        <div class="card-body">
            <form action="{{ route('quotations.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- رقم العرض -->
                    <div class="form-group col-md-2">
                        <label for="qut_number">{{ __('home.QuotationNumber') }}</label>
                        <input type="text" name="qut_number" id="qut_number" class="form-control"
                               value="{{ old('qut_number', $newQuotationNumber) }}" required readonly>
                    </div>

                    <!-- تاريخ العرض -->
                    <div class="form-group col-md-2">
                        <label for="qut_date">{{ __('home.QuotationDate') }}</label>
                        <input type="date" name="qut_date" class="form-control" id="qut_date" value="{{ old('qut_date', now()->format('Y-m-d')) }}" required>
                    </div>

                    <!-- اختيار العميل -->
                    <div class="form-group col-md-3">
                        <label for="customer_id">{{ __('home.Customers') }}</label>
                        <select name="customer_id" class="form-control" id="customer_id" required>
                            <option value="" disabled selected>{{ __('home.SelectCustomer') }}</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->cus_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="store_id">{{ __('home.Store') }}</label>
                        <select name="store_id" id="store_id" class="form-control" style="text-align: center;">
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- حالة العرض -->
                    <div class="form-group col-md-2">
                        <label for="qut_status">{{ __('home.Status') }}</label>
                        <select name="qut_status" class="form-control" id="qut_status" required>
                            <option value="ساري" selected>{{ __('home.Active') }}</option>
                            <option value="منتهي">{{ __('home.Expired') }}</option>
                            <option value="إنتظار">{{ __('home.Pending') }}</option>
                            <option value="ملغي">{{ __('home.Cancelled') }}</option>
                        </select>
                    </div>
                </div>

                <hr>

                <!-- جدول تفاصيل الفاتورة -->
                <h3 class="mt-4 mb-3">{{ __('home.QuotationDetails') }}</h3>
                <hr>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover" id="invoice_details">
                        <thead class="bg-info text-white text-center">
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
                                <td class="text-center serial-number">#</td>
                                <td>
                                    <select name="categories[]" class="form-control" required>
                                        <option value="" selected disabled>{{ __('home.Categoey') }}</option>
                                        @foreach($e_categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->c_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="products[]" class="form-control" required>
                                        <option value="" disabled selected>{{ __('home.Product') }}</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="units[]" class="form-control" required>
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
                                    <input type="number" name="quantity[]" step="0.01" class="quantity form-control" required min="0">
                                </td>
                                <td>
                                    <input type="number" name="product_price[]" step="0.01" class="product_price form-control" required min="0">
                                </td>
                                <td>
                                    <input type="number" name="product_discount[]" step="0.01" class="product_discount form-control" value="0">
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

                <div class="text-right mt-2">
                    <button type="button" class="btn btn-primary btn_add">{{ __('home.AddRow') }}</button>
                </div>

                <hr>

                <!-- تفاصيل الدفع -->
                <div class="card shadow-lg border-0 rounded-4 mt-4">
                    <h3 class="mt-4 mb-3">{{ __('home.TotalDetails') }}</h3>
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
                                        <input type="text" class="form-control" value="{{ $taxRate }}%" readonly style="width: 100%; text-align: center;">
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
                            </tfoot>
                        </table>
                        <td class="text-end">{{ __('home.Notes') }}</td>
                        <td>
                            <textarea name="qut_notes" id="qut_notes" class="qut_notes form-control" style="width: 100%; text-align: center;" rows="2" oninput="autoResize(this)">{{ old('qut_notes', __('home.NoNotes')) }}</textarea>
                        </td>
                    </div>
                </div>

                <!-- زر ارسال -->
                <div class="text-right">
                    <button type="submit" class="btn btn-success mt-4">{{ __('home.AddQuotation') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- تحديث الأرقام التسلسلية -->
<script>
    // تحديث الأرقام التسلسلية في الجدول
    function updateSerialNumbers() {
        $('#invoice_details tbody tr').each(function(index) {
            $(this).find('.serial-number').text(index + 1);
            });
        }


    // تحديث الأرقام التسلسلية عند تحميل الصفحة
    $(document).ready(function() {
        updateSerialNumbers();
    });
</script>

<!--تنسيق حقل الملاحظات-->
<script>
    function autoResize(element) {
        element.style.height = 'auto';
        element.style.height = (element.scrollHeight) + 'px';
    }
</script>


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

@endsection
