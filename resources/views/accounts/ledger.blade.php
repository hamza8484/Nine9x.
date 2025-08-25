@extends('layouts.master')

@section('title')
    {{ __('home.account_statement') }} - {{ $account->name }}
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.accounts') }}</h4>
            <span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.account_statement') }}</span>
        </div>
    </div>
</div>
<!-- /breadcrumb -->
@endsection

@section('content')
<div class="container-fluid">

    <!-- 🔷 معلومات الحساب -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('home.account_statement') }}: {{ $account->name }}</h5>
            <small class="text-light">{{ __('home.account_number') }}: {{ $account->id }}</small>
        </div>

        <div class="card-body">

            <!-- 🔎 فلتر التاريخ -->
            <form method="GET" class="form-inline mb-4">
                <div class="form-group mr-3">
                    <label for="from_date" class="mr-2">{{ __('home.from_date') }}:</label>
                    <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}" class="form-control">
                </div>
                <div class="form-group mr-3">
                    <label for="to_date" class="mr-2">{{ __('home.to_date') }}:</label>
                    <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">{{ __('home.show') }}</button>
            </form>

            <!-- 📊 جدول كشف الحساب -->
            <div class="table-responsive">
                <table class="table table-bordered text-center table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>{{ __('home.serial') }}</th>
                            <th>{{ __('home.date') }}</th>
                            <th>{{ __('home.description') }}</th>
                            <th>{{ __('home.reference_number') }}</th>
                            <th class="text-success">{{ __('home.debit') }}</th>
                            <th class="text-danger">{{ __('home.credit') }}</th>
                            <th>{{ __('home.balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $balance = 0; @endphp

                        @forelse($journalEntryLines as $index => $entry)
                            @php
                                $debit = $entry->entry_type === 'debit' ? $entry->amount : 0;
                                $credit = $entry->entry_type === 'credit' ? $entry->amount : 0;
                                $balance += $debit - $credit;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $entry->journalEntry->transaction_date }}</td>
                                <td>{{ $entry->description ?? '-' }}</td>
                                <td>{{ $entry->journalEntry->reference_number ?? '-' }}</td>
                                <td class="text-success font-weight-bold">{{ $debit ? number_format($debit, 2) : '-' }}</td>
                                <td class="text-danger font-weight-bold">{{ $credit ? number_format($credit, 2) : '-' }}</td>
                                <td><strong>{{ number_format($balance, 2) }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">{{ __('home.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 🧾 الإجراءات -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('accounts.ledger.pdf', $account->id) }}?from_date={{ request('from_date') }}&to_date={{ request('to_date') }}"
                   class="btn btn-dark" target="_blank">
                    {{ __('home.download_pdf') }}
                </a>

                <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                    {{ __('home.back_to_accounts') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
