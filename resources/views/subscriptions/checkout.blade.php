@extends('layouts.master')

@section('css')
<!-- مكتبة Card.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/card/2.5.6/card.min.css">

<!-- مكتبة أيقونات Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .card-wrapper {
        direction: ltr;
        margin-bottom: 30px;
        perspective: 1000px;
    }

    .jp-card {
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .jp-card.jp-card-flipped {
        transform: rotateY(180deg);
    }

    .payment-card {
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff, #f9f9f9);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .payment-card:hover {
        transform: translateY(-5px);
    }

    .payment-header {
        background: linear-gradient(45deg, #4CAF50, #388E3C);
        color: white;
        padding: 20px;
        text-align: center;
    }

    .payment-icon {
        font-size: 3rem;
    }

    .form-label {
        font-weight: bold;
    }

    .btn-pay {
        background-color: #4CAF50;
        border: none;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .btn-pay:hover {
        background-color: #45a049;
    }
</style>
@endsection

@section('title')
    {{ __('home.MainPage47') }}
@stop

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.Subscriptions') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.CompleteSubscription') }}</span>
        </div>
    </div>
</div>
@endsection


@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card payment-card">
                <div class="payment-header">
                    <i class="bi bi-credit-card payment-icon mb-2"></i>
                    <h4 class="mb-0">{{ __('home.CompleteSubscription') }}</h4>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">{{ $plan->name }}</h5>
                    <p class="text-center mb-4">
                        <i class="bi bi-currency-dollar"></i>
                        {{ __('home.Price') }}: 
                        <strong>{{ number_format($plan->price, 2) }}</strong> 
                        {{ __('home.SAR') }} / 
                        {{ __('home.Duration', ['months' => $plan->duration_months]) }}
                    </p>

                    <div class="card-wrapper mb-4"></div>
                    <div id="card-brand" style="text-align: center; margin-bottom: 15px;">
                        <img src="" id="card-brand-logo" style="height: 40px; display: none;">
                    </div>

                    <form action="{{ route('subscription.processCheckout', ['planId' => $plan->id]) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-person-fill"></i> {{ __('home.CardHolderName') }}
                            </label>
                            <input type="text" name="card_holder" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-credit-card-2-front-fill"></i> {{ __('home.CardNumber') }}
                            </label>
                            <input 
                                type="text" 
                                name="card_number" 
                                id="card_number" 
                                class="form-control" 
                                placeholder="1234 5678 9012 3456" 
                                required 
                                maxlength="19" 
                                inputmode="numeric">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-calendar-event"></i> {{ __('home.ExpiryDate') }}
                                </label>
                                <input 
                                    type="text" 
                                    name="expiry_date" 
                                    id="expiry_date" 
                                    class="form-control" 
                                    placeholder="MM/YY" 
                                    required 
                                    maxlength="5" 
                                    inputmode="numeric">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-shield-lock-fill"></i> {{ __('home.CVV') }}
                                </label>
                                <input 
                                    type="text" 
                                    name="cvv" 
                                    id="cvv" 
                                    class="form-control" 
                                    required 
                                    maxlength="4" 
                                    inputmode="numeric">
                            </div>
                        </div>
                        <input type="hidden" name="payment_method" value="credit_card">

                        <div class="d-grid gap-2">
                            <button type="submit" id="submitBtn" class="btn btn-success btn-lg btn-pay">
                                <span id="submitText">{{ __('home.PayNow') }}</span>
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<!-- صوت النقر -->
<audio id="card-sound" src="https://assets.mixkit.co/sfx/preview/mixkit-quick-lock-sound-2852.mp3" preload="auto"></audio>

<!-- مكتبة Card.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/card/2.5.6/card.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        

        // تفعيل مكتبة Card.js
        const card = new Card({
            form: '#checkoutForm',
            container: '.card-wrapper',
            formSelectors: {
                numberInput: 'input[name="card_number"]',
                expiryInput: 'input[name="expiry_date"]',
                cvcInput: 'input[name="cvv"]',
                nameInput: 'input[name="card_holder"]'
            },
            width: 300,
            formatting: true,
            placeholders: {
                number: '•••• •••• •••• ••••',
                name: 'الاسم الكامل',
                expiry: '••/••',
                cvc: '•••'
            }
        });

    // المتغيرات
    const cardWrapper = document.querySelector('.jp-card');
    const cvvInput = document.getElementById('cvv');
    const cardNumberInput = document.getElementById('card_number');
    const cardSound = document.getElementById('card-sound');
    const expiryInput = document.getElementById('expiry_date');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const checkoutForm = document.getElementById('checkoutForm');
    const brandLogo = document.getElementById('card-brand-logo');

    // تشغيل الصوت
    function playSound() {
        cardSound.currentTime = 0;
        cardSound.play();
    }

    // اهتزاز البطاقة
    function vibrateCard() {
        if (cardWrapper) {
            cardWrapper.style.transition = 'transform 0.1s';
            cardWrapper.style.transform += ' scale(1.03)';
            setTimeout(() => {
                cardWrapper.style.transform = cardWrapper.style.transform.replace(' scale(1.03)', '');
                cardWrapper.style.transition = 'transform 0.6s';
            }, 100);
        }
    }

    // قلب البطاقة عند التركيز على CVV
    cvvInput.addEventListener('focus', function() {
        cardWrapper?.classList.add('jp-card-flipped');
    });
    cvvInput.addEventListener('blur', function() {
        cardWrapper?.classList.remove('jp-card-flipped');
    });

    // تشغيل الصوت والاهتزاز عند الكتابة
    ['input', 'keydown'].forEach(evt => {
        cardNumberInput.addEventListener(evt, () => {
            playSound();
            vibrateCard();
        });
        cvvInput.addEventListener(evt, () => {
            playSound();
            vibrateCard();
        });
    });

    // كشف نوع البطاقة وعرض الشعار
    function detectCardBrand(number) {
        const cleaned = number.replace(/\s+/g, '');
        if (/^4/.test(cleaned)) {
            return 'visa';
        } else if (/^5[1-5]/.test(cleaned) || /^2(2[2-9]|[3-6][0-9]|7[01])/.test(cleaned)) {
            return 'mastercard';
        } else {
            return 'unknown';
        }
    }

    function updateCardBrand(number) {
        const brand = detectCardBrand(number);
        if (brand === 'visa') {
            brandLogo.src = 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg';
            brandLogo.style.display = 'inline-block';
        } else if (brand === 'mastercard') {
            brandLogo.src = 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg';
            brandLogo.style.display = 'inline-block';
        } else {
            brandLogo.style.display = 'none';
        }
    }

    cardNumberInput.addEventListener('input', function() {
        updateCardBrand(this.value);
    });

    // كشف نوع البطاقة مباشرة عند تحميل الصفحة
    updateCardBrand(document.getElementById('card_number').value);

    // التحقق من المدخلات قبل الإرسال
    checkoutForm.addEventListener('submit', function(e) {
        const cardValue = cardNumberInput.value.replace(/\s/g, '');
        const expiryValue = expiryInput.value;
        const cvvValue = cvvInput.value;

        if (cardValue.length !== 16) {
            e.preventDefault();
            alert('{{ __("home.CardNumberMustBe16") }}');
            return;
        }

        const expiryParts = expiryValue.split('/');
        if (expiryParts.length !== 2 || expiryParts[0].length !== 2 || expiryParts[1].length !== 2) {
            e.preventDefault();
            alert('{{ __("home.InvalidExpiryFormat") }}');
            return;
        }

        const month = parseInt(expiryParts[0]);
        const year = parseInt('20' + expiryParts[1]);
        const today = new Date();
        const expiryDate = new Date(year, month - 1);

        if (month < 1 || month > 12 || expiryDate <= today) {
            e.preventDefault();
            alert('{{ __("home.ExpiredOrInvalidExpiry") }}');
            return;
        }

        if (cvvValue.length < 3 || cvvValue.length > 4 || isNaN(cvvValue)) {
            e.preventDefault();
            alert('{{ __("home.InvalidCVV") }}');
            return;
        }

        // Show loading spinner
        submitText.classList.add('d-none');
        loadingSpinner.classList.remove('d-none');
    });


    // تأثير ظهور البطاقة مع التحميل
    const cardWrapperContainer = document.querySelector('.card-wrapper');
        if (cardWrapperContainer) {
            cardWrapperContainer.style.opacity = '0';
            cardWrapperContainer.style.transform = 'scale(0.8)';
            cardWrapperContainer.style.transition = 'opacity 0.8s ease, transform 0.8s ease';

            setTimeout(() => {
                cardWrapperContainer.style.opacity = '1';
                cardWrapperContainer.style.transform = 'scale(1)';
            }, 300);
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cardInput = document.getElementById('card_number');

        if (cardInput) {
            cardInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); // احذف كل شيء مو رقم
                value = value.match(/.{1,4}/g); // كل 4 أرقام
                if (value) {
                    e.target.value = value.join(' '); // حط فراغ بعد كل 4 أرقام
                } else {
                    e.target.value = '';
                }
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const expiryInput = document.getElementById('expiry_date');

        if (expiryInput) {
            expiryInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, ''); // احذف كل شيء مو رقم

                if (value.length === 1 && parseInt(value) > 1) {
                    value = '0' + value; // لو كتب رقم 2 يصير 02
                }

                if (value.length >= 2) {
                    value = value.slice(0, 2) + '/' + value.slice(2, 4);
                }

                e.target.value = value;
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cvvInput = document.getElementById('cvv');

        if (cvvInput) {
            cvvInput.addEventListener('input', function (e) {
                e.target.value = e.target.value.replace(/\D/g, ''); // حذف أي شيء مو رقم
            });

            // منع لصق أحرف مو رقمية
            cvvInput.addEventListener('paste', function (e) {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text');
                const numbersOnly = text.replace(/\D/g, '');
                document.execCommand('insertText', false, numbersOnly);
            });
        }
    });
</script>

<script>
    function shakeElement(element) {
        if (!element) return;
        element.style.transition = 'transform 0.1s';
        element.style.transform = 'translateX(-10px)';
        setTimeout(() => {
            element.style.transform = 'translateX(10px)';
            setTimeout(() => {
                element.style.transform = 'translateX(0px)';
                element.style.transition = 'transform 0.6s';
            }, 100);
        }, 100);
    }
</script>

@endsection
