<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- Moyasar Styles -->
    <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.7.3/moyasar.css" />

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
        <div class="mysr-order-form"></div>
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
        function initMoyasar() {
            Moyasar.init({
                // Required
                // Specify where to render the form
                element: '.mysr-order-form',
                language: '{{ $lang }}',
                // Required
                // Amount in the smallest currency unit
                // For example:
                // 10 SAR = 10 * 100 Halalas
                amount: "{{ $order->PriceAtSmallestUnit }}",

                // Required
                currency: 'SAR',

                // Required
                // A small description of the current payment process
                description: "Order Payment at {{ config('app.name','ECommarce-App') }}",

                // Required
                publishable_api_key: "{{ config('services.moyasar.key') }}",

                // Required
                // This URL is used to redirect the user when payment process has completed
                // Payment can be either a success or a failure, which you need to verify on you system (We will show this in a couple of lines)
                // on_completed: "",
                on_completed: function(payment) {
                    payment.key = "{{ $order->key }}";
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('order.payment.save') }}",
                            data: payment,
                            dataType: 'json',
                            success: function(data, textStatus, jqXHR) {
                                // An empty dictionary/object is required to indicate to the form everything is good to go
                                resolve({});
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                reject();
                            }
                        });
                    });
                },
                callback_url: "{{ route('marketplace:order.payment.check') }}",


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
                },
            });
        }

        initMoyasar();
    </script>

</body>

</html>
