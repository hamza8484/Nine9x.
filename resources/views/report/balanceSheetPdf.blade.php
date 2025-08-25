<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('home.trial_balance_for_fiscal_year') }} {{ $fiscalYearId }}</title>
</head>
<body>
    <h2>{{ __('home.trial_balance_for_fiscal_year') }}: <strong>{{ $fiscalYearId }}</strong></h2>

    <!-- جدول ميزان المراجعة -->
    <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; text-align: center;">
        <thead>
            <tr>
                <th>{{ __('home.account_code') }}</th>
                <th>{{ __('home.account_name') }}</th>
                <th>{{ __('home.total_debit') }}</th>
                <th>{{ __('home.total_credit') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($journalEntries as $entry)
                <tr>
                    <td>{{ $entry->account_code }}</td>
                    <td>{{ $entry->account_name }}</td>
                    <td>{{ number_format($entry->total_debit, 2) }}</td>
                    <td>{{ number_format($entry->total_credit, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- مجموع المدين والدائن -->
    <div style="margin-top: 20px;">
        <p><strong>{{ __('home.total_debit') }}: </strong>{{ number_format($journalEntries->sum('total_debit'), 2) }}</p>
        <p><strong>{{ __('home.total_credit') }}: </strong>{{ number_format($journalEntries->sum('total_credit'), 2) }}</p>
    </div>
</body>
</html>
