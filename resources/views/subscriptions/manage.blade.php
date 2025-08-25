@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .full-page-table {
        min-height: calc(10vh - 150px);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .custom-table {
        border-radius: 12px;
        overflow: hidden;
    }

    .custom-table thead {
        background-color: #f7f7f7;
    }

    .custom-table th {
        font-weight: bold;
        font-size: 16px;
        color: #333;
    }

    .custom-table td {
        vertical-align: middle;
        padding: 15px 10px;
    }

    .custom-table tbody tr:hover {
        background-color: #f0f8ff;
        transition: background-color 0.3s;
    }

    .progress {
        background-color: #e9ecef;
        border-radius: 20px;
        overflow: hidden;
    }

    .progress-bar {
        font-weight: bold;
        font-size: 14px;
        transition: width 0.6s ease;
    }

    .modal-content {
        border-radius: 12px;
    }
</style>
@endsection

@section('title')
 {{ __('home.MainPage46') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Subscriptions') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.SubscriptionManagement') }}</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid py-5">
    <h2 class="mb-5 text-center fw-bold" style="font-size: 2rem;">{{ __('home.Subscriptions') }}</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    @if($subscriptions->count())
        <div class="table-responsive animate__animated animate__fadeIn full-page-table" data-aos="fade-up">
            <table class="table table-hover text-center shadow custom-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('home.Sequence') }}</th>
                        <th>{{ __('home.UserName') }}</th>
                        <th>{{ __('home.Plan') }}</th>
                        <th>{{ __('home.StartDate') }}</th>
                        <th>{{ __('home.EndDate') }}</th>
                        <th>{{ __('home.Status') }}</th>
                        <th>{{ __('home.Countdown') }}</th>
                        <th>{{ __('home.ProgressBar') }}</th>
                        <th>{{ __('home.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $index => $subscription)
                        @php
                            $startDate = \Carbon\Carbon::parse($subscription->start_date);
                            $endDate = \Carbon\Carbon::parse($subscription->end_date);
                            $now = \Carbon\Carbon::now();
                            $totalDays = $startDate->diffInDays($endDate);
                            $passedDays = $startDate->diffInDays($now, false);
                            $progress = $totalDays > 0 ? max(0, min(100, ($passedDays / $totalDays) * 100)) : 0;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $subscription->user->name ?? auth()->user()->name }}</td>
                            <td>{{ $subscription->plan->name }}</td>
                            <td>{{ $startDate->format('Y-m-d') }}</td>
                            <td>{{ $endDate->format('Y-m-d') }}</td>
                            <td>
                                @if($endDate->isPast())
                                    <span class="badge bg-danger">{{ __('home.Expired') }}</span>
                                @elseif($now->diffInDays($endDate, false) <= 3)
                                    <span class="badge bg-warning text-dark">{{ __('home.ExpireSoon') }}</span>
                                @else
                                    <span class="badge bg-success">{{ __('home.Active') }}</span>
                                @endif
                            </td>
                            <td>
                                @if(!$endDate->isPast())
                                    <div id="countdown-{{ $subscription->id }}" class="fw-bold text-primary"></div>
                                @else
                                    <small>{{ __('home.SubscriptionEnded') }}</small>
                                @endif
                            </td>
                            <td>
                                @if(!$endDate->isPast())
                                    <div class="progress shadow-sm" style="height: 22px; border-radius: 12px; background-color: #f1f1f1;">
                                        <div id="progress-bar-{{ $subscription->id }}"
                                            class="progress-bar"
                                            role="progressbar"
                                            style="width: {{ $progress }}%; border-radius: 12px; background: linear-gradient(90deg, #4caf50, #81c784); font-weight: bold;"
                                            aria-valuenow="{{ $progress }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ round($progress) }}%
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @if($endDate->isPast())
                                        <button class="btn btn-success btn-sm" onclick="renewSubscription({{ $subscription->id }})">{{ __('home.Renew') }}</button>
                                    @endif
                                    <button class="btn btn-danger btn-sm" onclick="cancelSubscription({{ $subscription->id }})">{{ __('home.Cancel') }}</button>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubscriptionModal{{ $subscription->id }}">{{ __('home.EditDate') }}</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal لتعديل تاريخ الاشتراك -->
        <div class="modal fade" id="editSubscriptionModal{{ $subscription->id }}" tabindex="-1" aria-labelledby="editSubscriptionModalLabel{{ $subscription->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubscriptionModalLabel{{ $subscription->id }}">{{ __('home.EditSubscriptionDate') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('subscription.update', $subscription->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">{{ __('home.StartDate') }}</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">{{ __('home.EndDate') }}</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('home.Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('home.Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @else
        <div class="d-flex justify-content-center align-items-center full-page-table flex-column">
            <p class="mb-4 fs-4">{{ __('home.NoSubscription') }}</p>
            <a href="{{ route('subscription.plans') }}" class="btn btn-primary px-4 py-2">{{ __('home.ViewPlans') }}</a>
        </div>
    @endif
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- تضمين ملفات CSS و JS الخاصة بـ Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
    });

    @foreach($subscriptions as $subscription)
        @php
            $startDate = \Carbon\Carbon::parse($subscription->start_date);
            $endDate = \Carbon\Carbon::parse($subscription->end_date);
        @endphp
        @if(!$endDate->isPast())
            (function(){
                let countdownDate{{ $subscription->id }} = new Date("{{ $endDate->format('Y-m-d H:i:s') }}").getTime();
                let startDate{{ $subscription->id }} = new Date("{{ $startDate->format('Y-m-d H:i:s') }}").getTime();
                let totalDuration{{ $subscription->id }} = countdownDate{{ $subscription->id }} - startDate{{ $subscription->id }};

                let countdownFunction{{ $subscription->id }} = setInterval(function() {
                    let now = new Date().getTime();
                    let distance = countdownDate{{ $subscription->id }} - now;
                    let usedDuration = now - startDate{{ $subscription->id }};
                    let progressPercent = Math.min(100, Math.max(0, (usedDuration / totalDuration{{ $subscription->id }}) * 100));

                    let countdownElement = document.getElementById("countdown-{{ $subscription->id }}");
                    let progressBar = document.getElementById("progress-bar-{{ $subscription->id }}");

                    if (distance < 0) {
                        clearInterval(countdownFunction{{ $subscription->id }});
                        if(countdownElement) countdownElement.innerHTML = "{{ __('home.SubscriptionEnded') }}";
                        if(progressBar) {
                            progressBar.style.width = "100%";
                            progressBar.classList.remove('bg-success', 'bg-warning');
                            progressBar.classList.add('bg-danger');
                            progressBar.innerText = "100%";
                        }
                    } else {
                        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        if(countdownElement) {
                            countdownElement.innerHTML = `${days}{{ __('home.Days') }} ${hours}{{ __('home.Hours') }} ${minutes}{{ __('home.Minutes') }} ${seconds}{{ __('home.Seconds') }}`;
                        }
                        if(progressBar) {
                            progressBar.style.width = progressPercent.toFixed(0) + "%";
                            progressBar.innerText = progressPercent.toFixed(0) + "%";
                            if (distance <= 3 * 24 * 60 * 60 * 1000) {
                                progressBar.classList.remove('bg-success');
                                progressBar.classList.add('bg-warning');
                            } else {
                                progressBar.classList.remove('bg-warning');
                                progressBar.classList.add('bg-success');
                            }
                        }
                    }
                }, 1000);
            })();
        @endif
    @endforeach

    // إلغاء الاشتراك
    function cancelSubscription(id) {
        Swal.fire({
            title: '{{ __("home.AreYouSure") }}',
            text: "{{ __('home.SubscriptionCancelMessage') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("home.YesCancel") }}',
            cancelButtonText: '{{ __("home.NoBack") }}',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger mx-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('{{ route('subscription.cancel') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }).then(response => {
                    Swal.fire('{{ __("home.Done") }}', '{{ __("home.CanceledSuccessfully") }}', 'success').then(() => {
                        location.reload();
                    });
                }).catch(error => {
                    Swal.fire('{{ __("home.Error") }}', '{{ __("home.CancelError") }}', 'error');
                });
            }
        });
    }

    // تجديد الاشتراك
    function renewSubscription(id) {
        Swal.fire({
            title: '{{ __("home.ConfirmRenewal") }}',
            text: "{{ __('home.RenewSubscriptionQuestion') }}",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __("home.YesRenew") }}',
            cancelButtonText: '{{ __("home.No") }}',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-primary mx-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('{{ route('subscription.renew') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }).then(response => {
                    Swal.fire('{{ __("home.Done") }}', '{{ __("home.RenewedSuccessfully") }}', 'success').then(() => {
                        location.reload();
                    });
                }).catch(error => {
                    Swal.fire('{{ __("home.Error") }}', '{{ __("home.RenewError") }}', 'error');
                });
            }
        });
    }
</script>

@endsection
