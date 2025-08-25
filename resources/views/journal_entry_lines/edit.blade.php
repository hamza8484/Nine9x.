@extends('layouts.master')

@section('title')
    {{ __('home.MainPage75') }} 
@stop

@section('content')
    <div class="container">
        <h3>{{ __('home.edit_entry_line') }}</h3>

        <!-- عرض نموذج تعديل سطر القيد -->
        <form action="{{ route('journal_entry_lines.update', $line->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- استخدمنا PUT للتعديل في الموجه -->
            
            <div class="form-group">
                <label for="journal_entry_id">{{ __('home.reference_number') }}</label>
                <input type="text" class="form-control" id="journal_entry_id" value="{{ $line->journalEntry->reference_number }}" disabled>
            </div>

            <div class="form-group">
                <label for="account_id">{{ __('home.account') }}</label>
                <select name="account_id" id="account_id" class="form-control">
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" 
                            @if($account->id == $line->account_id) selected @endif>
                            {{ $account->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="entry_type">{{ __('home.entry_type') }}</label>
                <select name="entry_type" id="entry_type" class="form-control">
                    <option value="debit" @if($line->entry_type == 'debit') selected @endif>{{ __('home.debit') }}</option>
                    <option value="credit" @if($line->entry_type == 'credit') selected @endif>{{ __('home.credit') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">{{ __('home.amount') }}</label>
                <input type="number" class="form-control" id="amount" name="amount" value="{{ $line->amount }}" required>
            </div>

            <div class="form-group">
                <label for="description">{{ __('home.description') }}</label>
                <textarea class="form-control" id="description" name="description">{{ $line->description }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('home.update_entry_line') }}</button>
            <a href="{{ route('journal_entry_lines.index') }}" class="btn btn-secondary">{{ __('home.cancel') }}</a>
        </form>
    </div>
@endsection
