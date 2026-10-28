<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- Moyasar Styles -->
    <!-- <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.7.3/moyasar.css"/> -->

    <!-- Moyasar Scripts -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
    <!-- <script src="https://cdn.moyasar.com/mpf/1.7.3/moyasar.js"></script> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Click Pay</title>
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
        <iframe src="{{$order->redirect_url}}" width="100%" height="800"></iframe>
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


    </script>

</body>

</html>
