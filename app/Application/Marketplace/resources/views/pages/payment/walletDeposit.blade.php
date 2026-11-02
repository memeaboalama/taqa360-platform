<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- Moyasar Styles -->
    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.7.3/moyasar.css"/>

    <!-- Moyasar Scripts -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
    <script src="https://cdn.moyasar.com/mpf/1.7.3/moyasar.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Moyasar Payment</title>
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            /*width: 50%;*/
        }

        #mysr-form-form-el#mysr-form-form-el#mysr-form-form-el.mysr-form-moyasarForm[payment-form=true] div.mysr-form-footer {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 1rem;
            margin-bottom: 1rem;
            display: none !important;
        }

        .mb-3 {
            padding-bottom: 20px;
        }

        /*.parent {*/
        /*    padding: 0;*/
        /*}*/
        @media (max-width: 480px) {
            .parent {
                padding-right: 15px;
                padding-left: 15px;
            }
        }
    </style>
</head>
<body>

<div class="mb-3">
    <img class="center"
         src="{{ asset('assets/ecommerce/images/logo.png') }}" width="100" alt="Logo">
</div>


<div class="parent">
    <div role="status" style="max-width:640px;margin:0 auto 16px;padding:12px 16px;border:1px solid #f0d99a;border-radius:8px;background:#fff8e1;color:#624b00;font:14px/1.7 sans-serif;">
        @lang('ecommerce::ecommerce.wallet_payment_help')
        <a href="{{ route('account.wallet.deposit') }}" style="display:inline-block;margin-inline-start:8px;">@lang('ecommerce::ecommerce.back_to_wallet')</a>
    </div>
    <div class="mysr-order-form"></div>
    <div id="wallet-payment-error" role="alert" style="display:none;max-width:640px;margin:16px auto;padding:12px 16px;border:1px solid #f1aeb5;border-radius:8px;background:#f8d7da;color:#842029;font:14px/1.7 sans-serif;"></div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

</script>
<script>
    function showWalletPaymentError(message) {
        var errorElement = document.getElementById('wallet-payment-error');
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }

    function initMoyasar() {
        if (typeof Moyasar === 'undefined') {
            showWalletPaymentError(@json(__('ecommerce::ecommerce.wallet_payment_unavailable')));
            return;
        }

        Moyasar.init({
            // Required
            // Specify where to render the form
            element: '.mysr-order-form'
            , language: '{{ $lang }}',
            // Required
            // Amount in the smallest currency unit
            // For example:
            // 10 SAR = 10 * 100 Halalas
            amount: "{{ $walletDeposit->PriceAtSmallestUnit }}",

            // Required
            currency: 'SAR',

            // Required
            // A small description of the current payment process
            description: "Wallet Deposit at {{ config('app.name','ECommarce-App') }}",

            // Required
            publishable_api_key: "{{ config('services.moyasar.key') }}",

            // Required
            // This URL is used to redirect the user when payment process has completed
            // Payment can be either a success or a failure, which you need to verify on you system (We will show this in a couple of lines)
            // on_completed: "",
            on_completed: function (payment) {
                payment.key = "{{ $walletDeposit->key }}";
                return new Promise(function (resolve, reject) {
                    $.ajax({
                        type: 'POST'
                        , url: "{{ route('wallet.payment.save') }}"
                        , data: payment
                        , dataType: 'json'
                        , success: function (data, textStatus, jqXHR) {
                            // An empty dictionary/object is required to indicate to the form everything is good to go
                            resolve({});
                        }
                        , error: function (jqXHR, textStatus, errorThrown) {
                            showWalletPaymentError(@json(__('ecommerce::ecommerce.wallet_payment_save_failed')));
                            reject();
                        }
                    });
                });
            }
            , callback_url: "{{ route('marketplace:wallet.payment.check') }}",


            // Optional
            // Required payments methods
            // Default: ['creditcard', 'applepay', 'stcpay']
            methods: [
                'creditcard',
                'applepay',
                'stcpay',

            ],
            apple_pay: {
                country: 'SA',
                label: 'Restaurant Ordering',
                validate_merchant_url: 'https://api.moyasar.com/v1/applepay/initiate',
            }
            ,
        });
    }

    try {
        initMoyasar();
    } catch (error) {
        showWalletPaymentError(@json(__('ecommerce::ecommerce.wallet_payment_unavailable')));
    }

</script>

</body>
</html>
