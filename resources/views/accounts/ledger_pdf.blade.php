<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('home.account_statement') }} - {{ $account->name }}</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif; /* أو 'DejaVu Sans' */
            direction: rtl;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .text-success {
            color: green;
        }
        .text-danger {
            color: red;
        }
        .font-weight-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>{{ __('home.account_statement') }}: {{ $account->name }}</h2>

    <table>
        <thead>
            <tr>
                <th>{{ __('home.serial') }}</th>
                <th>{{ __('home.date') }}</th>
                <th>{{ __('home.description') }}</th>
                <th>{{ __('home.reference_number') }}</th>
                <th>{{ __('home.debit') }}</th>
                <th>{{ __('home.credit') }}</th>
                <th>{{ __('home.balance') }}</th>
            </tr>
        </thead>
        <tbody>
            @php
                $balance = 0;
            @endphp

            @foreach($journalEntryLines as $index => $entry)
                @php
                    $debit = $entry->entry_type == 'debit' ? $entry->amount : 0;
                    $credit = $entry->entry_type == 'credit' ? $entry->amount : 0;
                    $balance += $debit - $credit;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $entry->journalEntry->transaction_date }}</td>
                    <td>{{ $entry->description }}</td>
                    <td>{{ $entry->journalEntry->reference_number }}</td>
                    <td class="text-success font-weight-bold">{{ $debit ? number_format($debit, 2) : '' }}</td>
                    <td class="text-danger font-weight-bold">{{ $credit ? number_format($credit, 2) : '' }}</td>
                    <td><strong>{{ number_format($balance, 2) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
