@extends('layouts.master')

@section('title')
    {{ __('home.add_financial_reconciliation') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.financial_reconciliation') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.add_financial_reconciliation') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
    <!-- بطاقة إضافة التسوية المالية -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('home.add_new_reconciliation') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reconciliations.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="account_id">{{ __('home.account') }}</label>
                        <select name="account_id" id="account_id" class="form-control" required>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="reconciliation_date">{{ __('home.reconciliation_date') }}</label>
                        <input type="date" name="reconciliation_date" id="reconciliation_date" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="system_balance">{{ __('home.system_balance') }}</label>
                        <input type="number" step="0.01" name="system_balance" id="system_balance" class="form-control" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="actual_balance">{{ __('home.actual_balance') }}</label>
                        <input type="number" step="0.01" name="actual_balance" id="actual_balance" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">{{ __('home.notes') }}</label>
                    <textarea name="notes" id="notes" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('home.add_reconciliation') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
