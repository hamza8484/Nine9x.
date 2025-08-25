@extends('layouts.master')

@section('css')
    <style>
        .reconciliation-details {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .reconciliation-details h2 {
            margin-bottom: 25px;
        }

        .details-item {
            margin-bottom: 15px;
        }

        .table thead th {
            background-color: #007bff;
            color: #fff;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
        }

        #searchInput {
            width: 100%;
            max-width: 300px;
            margin-bottom: 15px;
            padding: 7px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .action-buttons {
            margin-top: 10px;
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media print {
            .action-buttons, #searchInput {
                display: none !important;
            }
        }
    </style>
@endsection

@section('title')
    {{ __('home.MainPage76') }} 
@stop

@section('content')
<div class="container mt-5">
    <div class="reconciliation-details" id="printArea">
        <h2 class="text-center text-primary">{{ __('home.reconciliation_details') }} رقم {{ $reconciliation->id }}</h2>

        <div class="details-item"><strong>{{ __('home.account') }}:</strong> {{ $reconciliation->account->name ?? __('home.unknown') }}</div>
        <div class="details-item"><strong>{{ __('home.reconciliation_date') }}:</strong> {{ $reconciliation->reconciliation_date }}</div>
        <div class="details-item"><strong>{{ __('home.system_balance') }}:</strong> {{ number_format($reconciliation->system_balance, 2) }}</div>
        <div class="details-item"><strong>{{ __('home.actual_balance') }}:</strong> {{ number_format($reconciliation->actual_balance, 2) }}</div>
        <div class="details-item">
            <strong>{{ __('home.status') }}:</strong>
            @if($reconciliation->status == 'pending')
                <span class="badge bg-warning text-dark">{{ __('home.pending') }}</span>
            @elseif($reconciliation->status == 'reconciled')
                <span class="badge bg-success">{{ __('home.reconciled') }}</span>
            @elseif($reconciliation->status == 'error')
                <span class="badge bg-danger">{{ __('home.error') }}</span>
            @else
                <span class="badge bg-secondary">{{ __('home.unknown') }}</span>
            @endif
        </div>

        <h4 class="mt-4 mb-3">{{ __('home.reconciliation_lines') }}</h4>

        <!-- أزرار فوق الجدول -->
        <div class="action-buttons">
            <a href="{{ route('reconciliations.index') }}" class="btn btn-secondary">{{ __('home.back') }}</a>
            <button onclick="printReconciliation()" class="btn btn-primary">{{ __('home.print') }}</button>
            <a href="{{ route('reconciliations.export.pdf', $reconciliation->id) }}" class="btn btn-danger">{{ __('home.export_pdf') }}</a>
            <a href="{{ route('reconciliations.export.excel', $reconciliation->id) }}" class="btn btn-success">{{ __('home.export_excel') }}</a>
        </div>

        <!-- مربع بحث -->
        <input type="text" id="searchInput" placeholder="{{ __('home.search_description') }}...">

        <!-- جدول الخطوط -->
        <table class="table table-bordered table-hover mt-3" id="linesTable">
            <thead>
                <tr>
                    <th>{{ __('home.sequence') }}</th>
                    <th>{{ __('home.description') }}</th>
                    <th>{{ __('home.amount') }}</th>
                    <th>{{ __('home.entry_type') }}</th>
                    <th>{{ __('home.matching') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reconciliation->lines as $index => $line)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $line->journalEntryLine->description ?? __('home.no_description') }}</td>
                        <td>{{ number_format($line->amount, 2) }}</td>
                        <td>{{ $line->entry_type == 'debit' ? __('home.debit') : __('home.credit') }}</td>
                        <td>
                            @if($line->is_matched)
                                <span class="badge bg-success">{{ __('home.matched') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('home.not_matched') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">{{ __('home.no_reconciliation_lines') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
<script>
    // فلترة على الجدول
    document.getElementById("searchInput").addEventListener("keyup", function () {
        var filter = this.value.toLowerCase();
        var rows = document.querySelectorAll("#linesTable tbody tr");

        rows.forEach(function (row) {
            var text = row.cells[1]?.textContent?.toLowerCase() || "";
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

    // طباعة
    function printReconciliation() {
        var printContents = document.getElementById("printArea").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
@endsection
