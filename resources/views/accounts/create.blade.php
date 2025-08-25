@extends('layouts.master')

@section('title')
   {{ __('home.MainPage64') }} 
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.accounts') }}</h4><span class="text-muted mt-1 tx-19 mr-2 mb-0">/ {{ __('home.add_account') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>{{ __('home.add_new_account') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('accounts.store') }}" method="POST">
                @csrf

                <!-- اسم الحساب -->
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="name">{{ __('home.account_name') }}</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <!-- كود الحساب -->
                    <div class="form-group col-md-2">
                        <label for="code">{{ __('home.account_code') }}</label>
                        <input type="text" name="code" id="code" class="form-control" required>
                    </div>

                    <!-- نوع الحساب -->
                    <div class="form-group col-md-3">
                        <label for="account_type_id">{{ __('home.account_type') }}</label>
                        <select name="account_type_id" id="account_type_id" class="form-control" required>
                            <option value="">{{ __('home.select_account_type') }}</option>
                            @foreach($accountTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الحساب الأب -->
                    <div class="form-group col-md-3">
                        <label for="parent_id">{{ __('home.parent_account') }} ({{ __('home.optional') }})</label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="">{{ __('home.no_parent_account') }}</option>
                            @foreach($parentAccounts as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            {{ __('home.leave_empty_if_main_account') }}
                        </small>
                    </div>
                </div>

                <!-- طبيعة الحساب -->
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="account_nature">{{ __('home.account_nature') }}</label>
                        <select name="account_nature" id="account_nature" class="form-control" required>
                            <option value="">{{ __('home.select_nature') }}</option>
                            <option value="debit">{{ __('home.debit') }}</option>
                            <option value="credit">{{ __('home.credit') }}</option>
                        </select>
                    </div>

                    <!-- مجموعة الحساب -->
                    <div class="form-group col-md-3">
                        <label for="account_group">{{ __('home.account_group') }}</label>
                        <select name="account_group" id="account_group" class="form-control" required>
                            <option value="">{{ __('home.select_group') }}</option>
                            <option value="asset">{{ __('home.asset') }}</option>
                            <option value="liability">{{ __('home.liability') }}</option>
                            <option value="equity">{{ __('home.equity') }}</option>
                            <option value="revenue">{{ __('home.revenue') }}</option>
                            <option value="expense">{{ __('home.expense') }}</option>
                        </select>
                    </div>

                    <!-- الرصيد -->
                    <div class="form-group col-md-2">
                        <label for="balance">{{ __('home.balance') }}</label>
                        <input type="number" step="0.01" name="balance" id="balance" class="form-control" value="0">
                    </div>

                    <!-- الحالة (نشط / غير نشط) -->
                    <div class="form-group col-md-2">
                        <label for="is_active">{{ __('home.status') }}</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" selected>{{ __('home.active') }}</option>
                            <option value="0">{{ __('home.inactive') }}</option>
                        </select>
                    </div>

                    <!-- حالة الحساب المتقدمة -->
                    <div class="form-group col-md-2">
                        <label for="advanced_status">{{ __('home.advanced_status') }}</label>
                        <select name="advanced_status" id="advanced_status" class="form-control">
                            <option value="active">{{ __('home.active') }}</option>
                            <option value="inactive">{{ __('home.inactive') }}</option>
                            <option value="closed">{{ __('home.closed') }}</option>
                            <option value="suspended">{{ __('home.suspended') }}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- تاريخ افتتاح الحساب -->
                    <div class="form-group col-md-2">
                        <label for="opening_date">{{ __('home.opening_date') }}</label>
                        <input type="date" name="opening_date" id="opening_date" class="form-control">
                    </div>

                    <!-- الرصيد الافتتاحي -->
                    <div class="form-group col-md-2">
                        <label for="opening_balance">{{ __('home.opening_balance') }}</label>
                        <input type="number" step="0.01" name="opening_balance" id="opening_balance" class="form-control" value="0">
                    </div>

                    <!-- تاريخ آخر تعديل -->
                    <div class="form-group col-md-3">
                        <label for="last_modified_date">{{ __('home.last_modified_date') }}</label>
                        <input type="datetime-local" name="last_modified_date" id="last_modified_date" class="form-control">
                    </div>

                    <!-- نوع الحساب الفرعي -->
                    <div class="form-group col-md-3">
                        <label for="sub_account_type">{{ __('home.sub_account_type') }}</label>
                        <select name="sub_account_type" id="sub_account_type" class="form-control">
                            <option value="">{{ __('home.select_sub_account_type') }}</option>
                            <option value="current">{{ __('home.current_account') }}</option>
                            <option value="capital">{{ __('home.capital_account') }}</option>
                            <option value="revenue">{{ __('home.revenue_account') }}</option>
                            <option value="expense">{{ __('home.expense_account') }}</option>
                        </select>
                    </div>

                    <!-- رمز العملة -->
                    <div class="form-group col-md-2">
                        <label for="currency_code">{{ __('home.currency_code') }}</label>
                        <select name="currency_code" id="currency_code" class="form-control">
                            <option value="SAR">ريال سعودي</option>
                            <option value="USD">دولار أمريكي</option>
                            <option value="AED">درهم إماراتي</option>
                            <option value="JOD">دينار أردني</option>
                        </select>
                    </div>
                </div>

                <!-- وصف الحساب -->
                <div class="form-group">
                    <label for="account_description">{{ __('home.account_description') }}</label>
                    <textarea name="account_description" id="account_description" class="form-control"></textarea>
                </div>

                <!-- زر الحفظ -->
                <button type="submit" class="btn btn-success mt-3">{{ __('home.save') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
