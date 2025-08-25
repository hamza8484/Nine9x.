@extends('layouts.master')

@section('css')
@endsection

@section('title')
 {{ __('home.MainPage43') }}
@stop


@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Subscriptions') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.PaymentSuccess') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="container text-center py-5">
    <h1 class="text-success mb-4 display-4">{{ __('home.PaymentCompleted') }} 🎉</h1>
    <p class="lead mb-4">{{ __('home.ThanksMessage') }}</p>
    <p class="lead mb-4">{{ __('home.ContactMessage') }}</p>

    <a href="{{ route('subscription.plans') }}">{{ __('home.BackToPlans') }}</a>
</div>
@endsection

@section('js')
@endsection
