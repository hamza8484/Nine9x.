@extends('layouts.master')

@section('css')
<!-- إضافة التنسيقات الخاصة بك إذا لزم الأمر -->
@endsection

@section('title')
   {{ __('home.MainPage65') }} 
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.accounts') }}</h4><span class="text-muted mt-1 tx-19 mr-2 mb-0">/ {{ __('home.edit_account') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>{{ __('home.edit_account') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('accounts.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">{{ __('home.account_name') }}</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $account->name }}" required>
                </div>

                <div class="form-group">
                    <label for="code">{{ __('home.account_code') }}</label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ $account->code }}" required>
                </div>

                <div class="form-group">
                    <label for="account_type_id">{{ __('home.account_type') }}</label>
                    <select name="account_type_id" id="account_type_id" class="form-control" required>
                        <option value="">{{ __('home.select_account_type') }}</option>
                        @foreach($accountTypes as $type)
                            <option value="{{ $type->id }}" {{ $account->account_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="parent_id">{{ __('home.parent_account') }} ({{ __('home.optional') }})</label>
                    <select name="parent_id" id="parent_id" class="form-control">
                        <option value="">{{ __('home.no_parent_account') }}</option>
                        @foreach($parentAccounts as $parent)
                            <option value="{{ $parent->id }}" {{ $account->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="balance">{{ __('home.balance') }}</label>
                    <input type="number" step="0.01" name="balance" id="balance" class="form-control" value="{{ $account->balance }}" required>
                </div>

                <div class="form-group">
                    <label for="account_nature">{{ __('home.account_nature') }}</label>
                    <select name="account_nature" id="account_nature" class="form-control" required>
                        <option value="debit" {{ $account->account_nature == 'debit' ? 'selected' : '' }}>{{ __('home.debit') }}</option>
                        <option value="credit" {{ $account->account_nature == 'credit' ? 'selected' : '' }}>{{ __('home.credit') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="account_group">{{ __('home.account_group') }}</label>
                    <select name="account_group" id="account_group" class="form-control" required>
                        <option value="asset" {{ $account->account_group == 'asset' ? 'selected' : '' }}>{{ __('home.asset') }}</option>
                        <option value="liability" {{ $account->account_group == 'liability' ? 'selected' : '' }}>{{ __('home.liability') }}</option>
                        <option value="equity" {{ $account->account_group == 'equity' ? 'selected' : '' }}>{{ __('home.equity') }}</option>
                        <option value="revenue" {{ $account->account_group == 'revenue' ? 'selected' : '' }}>{{ __('home.revenue') }}</option>
                        <option value="expense" {{ $account->account_group == 'expense' ? 'selected' : '' }}>{{ __('home.expense') }}</option>
                    </select>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" {{ $account->is_active ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">{{ __('home.active') }}</label>
                </div>

                <div class="form-group">
                    <label for="opening_date">{{ __('home.opening_date') }}</label>
                    <input type="date" name="opening_date" id="opening_date" class="form-control" value="{{ $account->opening_date }}">
                </div>

                <div class="form-group">
                    <label for="opening_balance">{{ __('home.opening_balance') }}</label>
                    <input type="number" step="0.01" name="opening_balance" id="opening_balance" class="form-control" value="{{ $account->opening_balance }}">
                </div>

                <div class="form-group">
                    <label for="last_modified_date">{{ __('home.last_modified_date') }}</label>
                    <input type="datetime-local" name="last_modified_date" id="last_modified_date" class="form-control" value="{{ $account->last_modified_date }}">
                </div>

                <div class="form-group">
                    <label for="sub_account_type">{{ __('home.sub_account_type') }}</label>
                    <select name="sub_account_type" id="sub_account_type" class="form-control">
                        <option value="current" {{ $account->sub_account_type == 'current' ? 'selected' : '' }}>{{ __('home.current_account') }}</option>
                        <option value="capital" {{ $account->sub_account_type == 'capital' ? 'selected' : '' }}>{{ __('home.capital_account') }}</option>
                        <option value="revenue" {{ $account->sub_account_type == 'revenue' ? 'selected' : '' }}>{{ __('home.revenue_account') }}</option>
                        <option value="expense" {{ $account->sub_account_type == 'expense' ? 'selected' : '' }}>{{ __('home.expense_account') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="currency_code">{{ __('home.currency_code') }}</label>
                    <select name="currency_code" id="currency_code" class="form-control">
                        <option value="SAR" {{ $account->currency_code == 'SAR' ? 'selected' : '' }}>ريال سعودي</option>
                        <option value="USD" {{ $account->currency_code == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                        <option value="AED" {{ $account->currency_code == 'AED' ? 'selected' : '' }}>درهم إماراتي</option>
                        <option value="JOD" {{ $account->currency_code == 'JOD' ? 'selected' : '' }}>دينار أردني</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="advanced_status">{{ __('home.advanced_status') }}</label>
                    <select name="advanced_status" id="advanced_status" class="form-control">
                        <option value="active" {{ $account->advanced_status == 'active' ? 'selected' : '' }}>{{ __('home.active') }}</option>
                        <option value="inactive" {{ $account->advanced_status == 'inactive' ? 'selected' : '' }}>{{ __('home.inactive') }}</option>
                        <option value="closed" {{ $account->advanced_status == 'closed' ? 'selected' : '' }}>{{ __('home.closed') }}</option>
                        <option value="suspended" {{ $account->advanced_status == 'suspended' ? 'selected' : '' }}>{{ __('home.suspended') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="account_description">{{ __('home.account_description') }}</label>
                    <textarea name="account_description" id="account_description" class="form-control">{{ $account->account_description }}</textarea>
                </div>

                <button type="submit" class="btn btn-success mt-3">{{ __('home.save_changes') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
