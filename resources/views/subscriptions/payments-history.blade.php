@extends('layouts.master')

@section('css')
@endsection

@section('title')
{{ __('home.MainPage45') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Payments') }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.PaymentHistory') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">{{ __('home.PaymentHistory') }}</h2>

    @if ($payments->isEmpty())
        <div class="alert alert-info text-center">{{ __('home.NoPaymentsRecorded') }}</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>{{ __('home.Plan') }}</th>
                        <th>{{ __('home.PaymentMethod') }}</th>
                        <th>{{ __('home.Status') }}</th>
                        <th>{{ __('home.Amount') }}</th>
                        <th>{{ __('home.PaymentDate') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->plan->name ?? '-' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td>
                                @if($payment->payment_status == 'paid')
                                    <span class="badge bg-success">{{ __('home.Paid') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('home.Failed') }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($payment->amount, 2) }} {{ __('home.Currency') }}</td>
                            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@section('js')
@endsection
