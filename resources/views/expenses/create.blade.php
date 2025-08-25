@extends('layouts.master')

@section('css')
<!-- إضافة تنسيقات CSS لتحسين التصميم -->
<style>
    .form-control {
        text-align: center;
    }

 

    /* تنسيق الاختيارات مع الفئات */
    .cash-option {
        color: green;
    }

    .card-option {
        color: blue;
    }

    .deferred-option {
        color: red;
    }
</style>
@endsection

@section('title')
    إضافة المصروفات - ناينوكس
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">إضافة المصروفات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضافة مصروف جديد</span>
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

<!-- بداية النموذج -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-muted mt-1 tx-20 mr-2 mb-0" style="text-align: right;">إضافة مصروف جديد</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="exp_inv_number">رقم الصرف</label>
                            <input type="text" name="exp_inv_number" id="exp_inv_number" class="form-control"
                                value="{{ old('exp_inv_number', $newExpensesNumber) }}" readonly>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="exp_date">تاريخ المصروف</label>
                            <input type="date" class="form-control" id="exp_date" name="exp_date" value="{{ old('exp_date') }}">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="exp_name">اسم المصروف</label>
                            <input type="text" class="form-control" id="exp_name" name="exp_name" value="{{ old('exp_name') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="category_id">الفئة</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="" disabled selected>اختر الفئة</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="exp_payment">طريقة الدفع</label>
                            <select name="exp_payment" id="exp_payment" class="form-control" required>
                                <option value="" disabled selected>اختر</option>
                                <option value="نقــدي" class="cash-option">نقدي</option>
                                <option value="شــبكة" class="card-option">شبكة</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="cashbox_id">{{ __('home.Cashbox') }}</label>
                            <select name="cashbox_id" id="cashbox_id" class="form-control">
                                <option value="" disabled selected>{{ __('home.SelectCashbox') }}</option> 
                                @foreach($cashboxes as $cashbox)
                                    <option value="{{ $cashbox->id }}">{{ $cashbox->cash_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="account_id">{{ __('home.Account') }}</label>
                            <select name="account_id" id="account_id" class="form-control">
                                <option value="" disabled selected>{{ __('home.SelectAccount') }}</option> 
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="branch_id">{{ __('home.Branch') }}</label>
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="" disabled selected>{{ __('home.Selectbranch') }}</option>   
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->bra_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="exp_inv_name">اسم الفاتورة المورد</label>
                            <input type="text" class="form-control" id="exp_inv_name" name="exp_inv_name" value="{{ old('exp_inv_name') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exp_sup_number">رقم فاتورة المورد</label>
                            <input type="text" class="form-control" id="exp_sup_number" name="exp_sup_number" value="{{ old('exp_sup_number') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="exp_sup_date">تاريخ فاتورة المورد</label>
                            <input type="date" class="form-control" id="exp_sup_date" name="exp_sup_date" required value="{{ old('exp_sup_date') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="exp_amount">المبلغ</label>
                            <input type="number" class="form-control" id="exp_amount" name="exp_amount" step="0.01" value="{{ old('exp_amount') }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="exp_discount">الخصم</label>
                            <input type="number" class="form-control" id="exp_discount" name="exp_discount" value="{{ old('exp_discount', 0) }}" required>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="tax_id">الضريبة</label>
                            <select class="form-control" id="tax_id" name="tax_id">
                                @foreach($taxes as $tax)
                                    <option value="{{ $tax->id }}" data-taxrate="{{ $tax->tax_rate }}">
                                        {{ $tax->tax_rate }}%
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="tax_amount">قيمة الضريبة</label>
                            <input type="number" class="form-control" id="tax_amount" name="tax_amount" step="0.01" readonly>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="final_amount">المجموع النهائي</label>
                            <input type="number" class="form-control" id="final_amount" name="final_amount" step="0.01" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="created_by">اسم المستخدم</label>
                        <input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px; display: block; margin: 0 auto;">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- نهاية النموذج -->

@endsection

@section('js')
<script>
    // إعادة حساب الضريبة والمجموع النهائي عندما يتغير المبلغ أو الخصم أو الضريبة
    function updateAmounts() {
        const amount = parseFloat(document.getElementById('exp_amount').value) || 0;
        const discount = parseFloat(document.getElementById('exp_discount').value) || 0;
        const taxRate = parseFloat(document.querySelector('#tax_id option:checked').getAttribute('data-taxrate')) || 0;

        // حساب الضريبة
        const taxAmount = (amount - discount) * (taxRate / 100);

        // حساب المجموع النهائي
        const finalAmount = (amount - discount) + taxAmount;

        // تحديث القيم في الحقول
        document.getElementById('tax_amount').value = taxAmount.toFixed(2);
        document.getElementById('final_amount').value = finalAmount.toFixed(2);
    }

    // إضافة مستمعي الحدث لجميع الحقول التي يجب أن تؤثر على الحسابات
    document.getElementById('exp_amount').addEventListener('input', updateAmounts);
    document.getElementById('exp_discount').addEventListener('input', updateAmounts);
    document.getElementById('tax_id').addEventListener('change', updateAmounts);

    // تحديث الحسابات عند تحميل الصفحة
    window.onload = updateAmounts;
</script>
@endsection
