@extends('layouts.master')

@section('title')
    {{ __('home.MainPage77') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.financial_reconciliation') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.reconciliation_list') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
    <div class="d-flex justify-content-between mb-4">
        <h2 class="content-title">{{ __('home.reconciliation_list') }}</h2>
        <a href="{{ route('reconciliations.create') }}" class="btn btn-primary">{{ __('home.add_new_reconciliation') }}</a>
    </div>

    <!-- نموذج البحث -->
    <form method="GET" action="{{ route('reconciliations.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="{{ __('home.search_in_accounts') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="reconciliation_date" class="form-control" value="{{ request('reconciliation_date') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">{{ __('home.reconciliation_status') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('home.pending') }}</option>
                    <option value="reconciled" {{ request('status') == 'reconciled' ? 'selected' : '' }}>{{ __('home.reconciled') }}</option>
                    <option value="error" {{ request('status') == 'error' ? 'selected' : '' }}>{{ __('home.error') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-info">{{ __('home.search') }}</button>
            </div>
        </div>
    </form>

    <!-- الجدول -->
    <table class="table table-bordered table-striped table-hover mt-3">
        <thead>
            <tr>
                <th>{{ __('home.sequence') }}</th>
                <th>{{ __('home.account') }}</th>
                <th>{{ __('home.reconciliation_date') }}</th>
                <th>{{ __('home.system_balance') }}</th>
                <th>{{ __('home.actual_balance') }}</th>
                <th>{{ __('home.reconciliation_status') }}</th>
                <th>{{ __('home.notes') }}</th>
                <th>{{ __('home.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reconciliations as $index => $reconciliation)
                <tr>
                    <td>{{ $index + 1 }}</td> <!-- التسلسل -->
                    <td>{{ $reconciliation->account->name }}</td>
                    <td>{{ $reconciliation->reconciliation_date }}</td>
                    <td>{{ number_format($reconciliation->system_balance, 2) }}</td>
                    <td>{{ number_format($reconciliation->actual_balance, 2) }}</td>
                    <td>
                        <span class="badge 
                            @if($reconciliation->status == 'pending') 
                                badge-warning 
                            @elseif($reconciliation->status == 'reconciled') 
                                badge-success 
                            @else 
                                badge-danger 
                            @endif
                        ">
                            {{ ucfirst($reconciliation->status) }}
                        </span>
                    </td>
                    <td>{{ $reconciliation->notes ?? __('home.no_notes') }}</td>
                    <td>
                        <a href="{{ route('reconciliations.show', $reconciliation->id) }}" class="btn btn-info btn-sm">{{ __('home.view') }}</a>
                        <a href="{{ route('reconciliations.edit', $reconciliation->id) }}" class="btn btn-warning btn-sm">{{ __('home.edit') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- إذا لم تكن هناك تسويات مالية -->
    @if($reconciliations->isEmpty())
        <div class="alert alert-info mt-3" role="alert">
            {{ __('home.no_reconciliation_found') }}
        </div>
    @endif
@endsection
