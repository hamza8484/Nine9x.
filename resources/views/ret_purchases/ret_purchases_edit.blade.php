@extends('layouts.master')

@section('css')
@endsection

@section('title')
 تعديل مرتجـع مشتريـات - ناينوكس
@stop

@section('content')

@if ($errors->any())
    <div class="alert alert-danger" id="error-alert">
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
        }} alert-dismissible fade show" role="alert" id="{{ strtolower($msg) }}-alert">
            <strong>{{ session($msg) }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach

<script>
    // إخفاء الرسائل بعد 9 ثواني
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 9000);
</script>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="m-0">تعديل مرتجع مشتريات رقم - <?php echo $ret_purchase->ret_pur_number; ?></h2>
                <a href="{{ route('ret_purchases.index') }}" class="btn btn-primary">الرجوع الى مرتجع المشتريـات</a>
            </div>

            <div class="card-body">
                <form action="{{ route('ret_purchases.update', $ret_purchase->id) }}" method="post" class="form">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="ret_pur_number">رقم المرتجع</label>
                                <input type="text" name="ret_pur_number" id="ret_pur_number" class="form-control"
                                       value="{{ old('ret_pur_number', $newRetPurchaseNumber) }}" readonly style="text-align: center;">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="ret_pur_date">تاريخ المرتجع</label>
                                <input type="text" name="ret_pur_date" id="ret_pur_date" class="form-control datepicker"
                                       value="{{ old('ret_pur_date', $ret_purchases->ret_pur_date ?? now()->toDateString()) }}" required style="text-align: center;">
                            </div>
                        </div>

                        <div class="col-3">
                            <label for="supplier_id">المورد</label>
                            <select name="supplier_id" id="supplier_id" class="form-control" required style="text-align: center;">
                                <option value="" disabled selected>اختر</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" 
                                        @if($supplier->id == $ret_purchase->supplier_id) selected @endif 
                                        style="text-align: center;">
                                        {{ $supplier->sup_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-4">
                            <label for="store_id">المخزن</label>
                            <select name="store_id" id="store_id" class="form-control" style="text-align: center;">
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-3">
                            <label for="ret_pur_payment">طريقة الدفع</label>
                            <select name="ret_pur_payment" id="ret_pur_payment" class="form-control" required style="text-align: center;">
                                <option value="" disabled selected>اختر</option>
                                <option value="نقــدي" @if($ret_purchase->ret_pur_payment == 'نقــدي') selected @endif>نقــدي</option>
                                <option value="شــبكة" @if($ret_purchase->ret_pur_payment == 'شــبكة') selected @endif>شــبكة</option>
                            </select>
                        </div>

                        <div class="col-2">
                            <label for="created_by">اسم المستخدم</label>
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
                                    <th style="text-align: center; width: 3%;">تسلسل</th>
                                    <th style="text-align: center; width: 13%;">المجموعة</th>
                                    <th style="text-align: center; width: 16%;">الصنف</th>
                                    <th style="text-align: center; width: 10%;">الوحدة</th>
                                    <th style="text-align: center; width: 17%;">الباركود</th>
                                    <th style="text-align: center; width: 10%;">الكمية</th>
                                    <th style="text-align: center; width: 10%;">السعر</th>
                                    <th style="text-align: center; width: 10%;">الخصم</th>
                                    <th style="text-align: center; width: 15%;">المجموع</th>
                                    <th style="text-align: center; width: 1%;">الاجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($ret_purchase->detailsReturnPurchases as $item)
                                    <tr class="cloning_row" id="{{ $loop->index }}">
                                        <td style="text-align: center;">
                                            {{ $loop->iteration }}
                                        </td>

                                        <!-- اختيار المجموعة -->
                                        <td>
                                            <select name="categories[]" class="form-control SlectBox" style="text-align: center;" required>
                                                <option value="" disabled>المجموعة</option>
                                                @foreach($e_categories as $category)
                                                    <option value="{{ $category->id }}" 
                                                        @if($category->id == $item->category_id) selected @endif>
                                                        {{ $category->c_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- اختيار الصنف -->
                                        <td>
                                            <select name="products[]" class="form-control SlectBox" style="text-align: center;" required>
                                                <option value="" disabled>الصنف</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                        @if($product->id == $item->product_id) selected @endif>
                                                        {{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- اختيار الوحدة -->
                                        <td>
                                            <select name="units[]" class="form-control SlectBox" style="text-align: center;" required>
                                                <option value="" disabled>الوحدة</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit->id }}" 
                                                        @if($unit->id == $item->unit_id) selected @endif>
                                                        {{ $unit->unit_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- باركود المنتج -->
                                        <td>
                                            <input type="number" name="product_barcode[]" class="product_barcode form-control" value="{{ $item->product_barcode }}" readonly>
                                        </td>

                                        <!-- كمية المنتج -->
                                        <td>
                                            <input type="number" name="quantity[]" step="0.01" class="quantity required form-control" value="{{ $item->quantity }}" required min="0">
                                        </td>

                                        <!-- سعر المنتج -->
                                        <td>
                                            <input type="number" name="product_price[]" step="0.01" class="product_price required form-control" value="{{ $item->product_price }}" required min="0">
                                        </td>

                                        <!-- خصم المنتج -->
                                        <td>
                                            <input type="number" name="product_discount[]" step="0.01" class="product_discount form-control" value="{{ $item->product_discount }}">
                                        </td>

                                        <!-- إجمالي السعر -->
                                        <td>
                                            <input type="number" name="total_price[]" step="0.01" class="total_price form-control" value="{{ $item->total_price }}" readonly>
                                        </td>

                                        <!-- زر الحذف -->
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"><i class="fa fa-minus"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <tr>
                        <td colspan="2" class="text-center">
                            <button type="button" class="btn_add btn btn-primary">اضافة صف</button>
                        </td>
                    </tr>

                    <!-- تفاصيل الدفع -->
                    <div class="card shadow-lg border-0 rounded-4 mt-4">
                        <div class="card-body p-4">
                            <table class="table table-bordered table-hover">
                                <tfoot>
                                    <tr>
                                        <td class="text-end">المجموع</td>
                                        <td>
                                            <input type="number" step="0.01" name="sub_total" id="sub_total" class="sub_total form-control" readonly style="width: 100%; text-align: center;" value="{{ old('sub_total', $ret_purchase->sub_total) }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">الخصم</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input type="number" step="0.01" name="discount_value" id="discount_value" class="discount_value form-control" value="{{ old('discount_value', $ret_purchase->discount_value) }}" style="width: 60%; text-align: center;">
                                                <select name="discount_type" id="discount_type" class="discount_type custom-select form-control" style="width: 30%; text-align: center;">
                                                    <option value="fixed" {{ $ret_purchase->discount_type == 'fixed' ? 'selected' : '' }}>سعر ثابت</option>
                                                    <option value="percentage" {{ $ret_purchase->discount_type == 'percentage' ? 'selected' : '' }}>بالنسبة</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">الضريبة</td>
                                        <td>
                                            <input type="text" class="form-control" value="{{ old('tax_rate', $taxRate) }}%" readonly style="width: 100%; text-align: center;">
                                        </td>
                                        <td class="text-end">قيمة الضريبة</td>
                                        <td>
                                            <input type="number" step="0.01" name="vat_value" id="vat_value" class="vat_value form-control" readonly style="width: 100%; text-align: center;" value="{{ old('vat_value', $ret_purchase->vat_value) }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">الأجمالـي</td>
                                        <td>
                                            <input type="number" step="0.01" name="total_deu" id="total_deu" class="total_deu form-control" readonly style="width: 100%; text-align: center;" value="{{ old('total_deu', $ret_purchase->total_deu) }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">المبلغ المدفوع</td>
                                        <td>
                                            <input type="number" step="0.01" name="total_paid" id="total_paid" class="total_paid form-control" style="width: 100%; text-align: center; font-weight: bold; background-color: #d4edda;" value="{{ old('total_paid', $ret_purchase->total_paid) }}" oninput="updateRemaining()">
                                        </td>
                                        <td class="text-end">المبلغ المتبقي</td>
                                        <td>
                                            <input type="number" step="0.01" name="total_unpaid" id="total_unpaid" class="total_unpaid form-control" readonly style="width: 100%; text-align: center; font-weight: bold; background-color: #f8d7da;" value="{{ old('total_unpaid', $ret_purchase->total_unpaid) }}">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- زر التعديل -->
                    <div class="text-right pt-3">
                        <button type="submit" name="save" class="btn btn-success btn-lg" style="font-size: 18px; padding: 12px 24px;">تعديــل المرتــجـع</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
    .form-control {
        border-radius: 8px;
       
    }
    .table th, .table td {
        padding: 10px;
        text-align: center;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .text-end {
        text-align: right;
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
        font-size: 16px;
    }
</style>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            let trCount = $('#invoice_details').find('tr.cloning_row').length;
            let nuberIncr = trCount > 0 ? trCount : 0; // تعديل حساب عدد الصفوف لتجنب مشكلة الرقم الأخير

            // إضافة صف جديد
            $('#invoice_details').find('tbody').append(
                '<tr class="cloning_row" id="' + nuberIncr + '">' +
                '<td style="text-align: center;">#</td>' +
                '<td>' +
                '<select name="categories[]" class="form-control SlectBox" style="text-align: center;" required >' +
                '<option value="" selected disabled>المجموعة</option>' +
                '@foreach($e_categories as $category)' +
                '<option value="{{ $category->id }}">{{ $category->c_name }}</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select name="products[]" class="form-control SlectBox product" style="text-align: center;" required >' +
                '<option value="" disabled selected>الصنف</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select name="units[]" class="form-control SlectBox unit" style="text-align: center;" required >' +
                '<option value="" disabled selected>الوحدة</option>' +
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
                    productsSelect.append('<option value="" disabled selected>اختر الصنف</option>');
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
            $(this).closest('tr').find('select[name="units[]"]').append('<option value="" disabled selected>اختر وحدة</option>');
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
                        unitsSelect.append('<option value="" disabled selected>اختر وحدة</option>');
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
