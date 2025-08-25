@extends('layouts.master')


@section('title')
    {{ __('home.MainPage71') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.entries') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.add_new_entry') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ __('home.add_new_entry') }}</h5>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('journal_entries.store') }}" method="POST" onsubmit="return validateForm()">
                @csrf

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="transaction_date">{{ __('home.transaction_date') }}</label>
                        <input type="date" name="transaction_date" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fiscal_year_id">{{ __('home.fiscal_year') }}</label>
                        <select name="fiscal_year_id" class="form-control" required>
                            <option value="">{{ __('home.choose_fiscal_year') }}</option>
                            @foreach($fiscalYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }} ({{ $year->start_date }} - {{ $year->end_date }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="reference_number">{{ __('home.reference_number') }}</label>
                        <input type="text" name="reference_number" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="description">{{ __('home.description') }}</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                </div>

                <hr>
                <h5>{{ __('home.entry_details') }}</h5>

                <div class="table-responsive">
                    <table class="table table-bordered" id="entry-lines">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('home.account') }}</th>
                                <th>{{ __('home.entry_type') }}</th>
                                <th>{{ __('home.amount') }}</th>
                                <th>{{ __('home.description') }}</th>
                                <th>{{ __('home.remove') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="lines[0][account_id]" class="form-control" required>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="lines[0][entry_type]" class="form-control" required>
                                        <option value="debit">{{ __('home.debit') }}</option>
                                        <option value="credit">{{ __('home.credit') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="lines[0][amount]" class="form-control" step="0.01" required>
                                </td>
                                <td>
                                    <input type="text" name="lines[0][description]" class="form-control">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">✖</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="addRow()">{{ __('home.add_new_row') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('home.save_journal_entry') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let rowIndex = 1;

    // إضافة سطر جديد
    function addRow() {
        const row = `
        <tr>
            <td>
                <select name="lines[${rowIndex}][account_id]" class="form-control" required>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="lines[${rowIndex}][entry_type]" class="form-control" required>
                    <option value="debit">{{ __('home.debit') }}</option>
                    <option value="credit">{{ __('home.credit') }}</option>
                </select>
            </td>
            <td>
                <input type="number" name="lines[${rowIndex}][amount]" class="form-control" step="0.01" required>
            </td>
            <td>
                <input type="text" name="lines[${rowIndex}][description]" class="form-control">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">✖</button>
            </td>
        </tr>
        `;
        document.querySelector('#entry-lines tbody').insertAdjacentHTML('beforeend', row);
        rowIndex++;
    }

    // حذف سطر
    function removeRow(button) {
        button.closest('tr').remove();
    }

    // التحقق من المبالغ المدين والدائن
    function validateForm() {
        let debitTotal = 0;
        let creditTotal = 0;
        let rows = document.querySelectorAll('#entry-lines tbody tr');

        rows.forEach(row => {
            const amount = parseFloat(row.querySelector('input[name*="amount"]').value);
            const entryType = row.querySelector('select[name*="entry_type"]').value;

            if (entryType === 'debit') {
                debitTotal += amount;
            } else if (entryType === 'credit') {
                creditTotal += amount;
            }
        });

        if (Math.abs(debitTotal - creditTotal) > 0.01) {
            alert('{{ __('home.debit_credit_mismatch') }}');
            return false; // منع إرسال النموذج
        }

        return true;
    }
</script>

@endsection
