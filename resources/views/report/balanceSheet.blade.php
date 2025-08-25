@extends('layouts.master')

@section('title')
  {{ __('home.MainPage79') }} 
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.accounts') }}</h4><span class="text-muted mt-1 tx-17 mr-2 mb-0">/ {{ __('home.trial_balance') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="container mt-5">
        <!-- العنوان -->
        <div class="text-center mb-4">
            <h2>{{ __('home.trial_balance_for_fiscal_year') }}: <strong>{{ $fiscalYearId }}</strong></h2>
        </div>

        <!-- زر الرجوع إلى الحسابات -->
        <div class="text-left mb-4">
            <a href="{{ route('accounts.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> {{ __('home.back_to_accounts') }}
            </a>
        </div>

        <!-- جدول ميزان المراجعة -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered shadow-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>{{ __('home.sequence') }}</th>
                        <th>{{ __('home.account_code') }}</th>
                        <th>{{ __('home.account_name') }}</th>
                        <th>{{ __('home.total_debit') }}</th>
                        <th>{{ __('home.total_credit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($journalEntries as $index => $entry)
                        <tr>
                            <td>{{ $index + 1 }}</td> <!-- التسلسل -->
                            <td>{{ $entry->account_code }}</td>
                            <td>{{ $entry->account_name }}</td>
                            <td>{{ number_format($entry->total_debit, 2) }}</td>
                            <td>{{ number_format($entry->total_credit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- مجموع المدين والدائن -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="alert alert-info text-center">
                    <h4>{{ __('home.total_debit') }}: <strong>{{ number_format($journalEntries->sum('total_debit'), 2) }}</strong></h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-success text-center">
                    <h4>{{ __('home.total_credit') }}: <strong>{{ number_format($journalEntries->sum('total_credit'), 2) }}</strong></h4>
                </div>
            </div>
        </div>

        <!-- رابط لتحميل التقرير كـ PDF -->
        <div class="text-center mt-4">
            <a href="{{ url('/balance-sheet-pdf/' . $fiscalYearId) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-file-pdf"></i> {{ __('home.download_pdf') }}
            </a>
        </div>
    </div>

@endsection

@section('styles')
    <!-- إضافة بعض التعديلات على الستايل -->
    <style>
        .table {
            margin-top: 20px;
            border-radius: 10px;
        }
        .table th, .table td {
            text-align: center;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .alert {
            font-size: 18px;
        }
        .btn-lg {
            font-size: 18px;
            padding: 10px 30px;
        }
    </style>
@endsection

@section('scripts')
    <!-- يمكنك إضافة أي سكربتات إضافية إذا لزم الأمر -->
@endsection
