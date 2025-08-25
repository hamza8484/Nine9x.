@extends('layouts.master')

@section('css')
<style>
    .subscription-section {
        background: linear-gradient(120deg, #f0f2f5, #ffffff);
        padding: 60px 0;
    }

    .plan-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.05);
        transition: 0.4s ease-in-out;
        overflow: hidden;
        position: relative;
        border: 1px solid #e6e6e6;
    }

    .plan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }

    .plan-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        opacity: 0.07;
        z-index: 0;
        transform: rotate(30deg);
    }

    .plan-header {
        background: #0072ff;
        color: white;
        padding: 25px;
        text-align: center;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        position: relative;
        z-index: 1;
    }

    .plan-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
        display: block;
    }

    .plan-title {
        font-size: 1.6rem;
        font-weight: bold;
        margin: 0;
    }

    .plan-price {
        font-size: 1.7rem;
        font-weight: 700;
        color: #333;
        margin: 20px 0 15px;
    }

    .plan-features {
        font-size: 0.95rem;
        color: #555;
        min-height: 100px;
        line-height: 1.6;
    }

    .subscribe-btn {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color: #fff;
        border: none;
        font-weight: bold;
        font-size: 1rem;
        padding: 12px 20px;
        border-radius: 30px;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .subscribe-btn:hover {
        background: linear-gradient(135deg, #005bb5, #003e80);
    }

    .subscribe-btn::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .subscribe-btn:hover::after {
        width: 200%;
        height: 500%;
    }

    .animated {
        animation: fadeInUp 1s ease forwards;
        opacity: 0;
    }

    @keyframes fadeInUp {
        0% {
            transform: translateY(40px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('title')
{{ __('home.MainPage44') }} 
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Subscriptions') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.SubscriptionPlans') }}</span>
        </div>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="subscription-section">
    <div class="container">
        <h1 class="text-center mb-5 font-weight-bold">{{ __('home.AvailableSubscriptionPlans') }}</h1>

        <div class="row justify-content-center">
            @php
                $icons = ['🎯', '🚀', '👑', '💎', '⚡', '🌟'];
            @endphp

            @foreach($plans as $index => $plan)
                <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch animated" style="animation-delay: {{ $index * 0.2 }}s">
                    <div class="card plan-card w-100">
                        <div class="plan-header">
                            <span class="plan-icon">{{ $icons[$index % count($icons)] }}</span>
                            <h5 class="plan-title">{{ $plan->name }}</h5>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between text-center p-4">
                            <div>
                                <div class="plan-price">{{ number_format($plan->price, 2) }} {{ __('home.Currency') }}</div>

                                @if(!empty($plan->features))
                                    <p class="plan-features">{!! nl2br(e($plan->features)) !!}</p>
                                @else
                                    <p class="plan-features">{{ __('home.NoFeatures') }}</p>
                                @endif

                                <p class="text-muted mt-3">{{ __('home.SubscriptionDuration') }}: <strong>{{ $plan->duration_months }} {{ __('home.Months') }}</strong></p>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('subscription.checkout', ['plan' => $plan->id]) }}" class="btn subscribe-btn">
                                    {{ __('home.SubscribeNow') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('js')
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: '{{ __('home.Success') }}!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonText: '{{ __('home.Great') }} 🙌',
        onClose: () => {
            window.location.href = "{{ route('subscription.manage') }}";
        }
    });
</script>
@endif
@endsection
