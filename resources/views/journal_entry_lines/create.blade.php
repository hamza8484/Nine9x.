@extends('layouts.master')

@section('title')
    {{ __('home.MainPage74') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.journal_entries_list') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.add_new_entry_line') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
    <div class="container">
        <h3>{{ __('home.add_new_entry_line') }}</h3>

        <!-- عرض نموذج إضافة سطر القيد داخل كارت -->
        <div class="card">
            <div class="card-header">
                <h4>{{ __('home.entry_details') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('journal_entry_lines.store') }}" method="POST">
                    @csrf <!-- حماية ضد هجمات CSRF -->
                    
                    <!-- عرض الرسائل العامة للأخطاء -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label for="journal_entry_id">{{ __('home.reference_number') }}</label>
                            <select name="journal_entry_id" id="journal_entry_id" class="form-control @error('journal_entry_id') is-invalid @enderror" required>
                                <option value="">{{ __('home.select_entry') }}</option>
                                @foreach($journalEntries as $journalEntry)
                                    <option value="{{ $journalEntry->id }}" {{ old('journal_entry_id') == $journalEntry->id ? 'selected' : '' }}>
                                        {{ $journalEntry->reference_number }} - {{ $journalEntry->transaction_date }}
                                    </option>
                                @endforeach
                            </select>
                            @error('journal_entry_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-5">
                            <label for="account_id">{{ __('home.account') }}</label>
                            <select name="account_id" id="account_id" class="form-control @error('account_id') is-invalid @enderror" required>
                                <option value="">{{ __('home.select_account') }}</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="entry_type">{{ __('home.entry_type') }}</label>
                            <select name="entry_type" id="entry_type" class="form-control @error('entry_type') is-invalid @enderror" required>
                                <option value="debit" {{ old('entry_type') == 'debit' ? 'selected' : '' }}>{{ __('home.debit') }}</option>
                                <option value="credit" {{ old('entry_type') == 'credit' ? 'selected' : '' }}>{{ __('home.credit') }}</option>
                            </select>
                            @error('entry_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="amount">{{ __('home.amount') }}</label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="description">{{ __('home.description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>  

                    <button type="submit" class="btn btn-primary">{{ __('home.add_entry_line') }}</button>
                    <a href="{{ route('journal_entry_lines.index') }}" class="btn btn-secondary">{{ __('home.back') }}</a>
                </form>
            </div>
        </div>
    </div>
@endsection
