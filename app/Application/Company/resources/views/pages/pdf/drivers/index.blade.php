@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order : #{{ $order->code }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }

        .container-bdf {
            width: 90%;
            margin: 0 auto;
        }

        .div-one {
            direction: rtl;
            margin-bottom: 2rem;
        }

        .div-one .logo {
            float: left;
            margin: auto 0;
        }

        .div-one .logo img {
            width: 10rem;
        }

        .div-one .content p {
            color: #6F6F84;
        }

        .div-one .content h3 {
            margin-bottom: 2rem;
        }

        .div-two {
            position: relative;
        }

        .div-two .cont-one {
            position: absolute;
            top: 0;
            left: 0;

        }

        .div-two .cont-two {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .div-two .cont-three {
            position: absolute;
            top: 0;
            right: 0;
        }

        .div-two .cont-one h5 {
            color: #6F6F84;
        }

        .div-two .cont-three h5 {
            color: #6F6F84;
        }

        .div-two .cont-two h3 {
            font-weight: 400;
        }

        .hr {
            margin-top: 15rem;
        }

        .div-three .title {
            position: relative;
            padding-bottom: 12rem;
        }

        .div-three .title h4 {
            line-height: 30px;
            color: #6F6F84;
            margin-bottom: 2rem;
            text-align: center;
        }

        .div-three .title .item {
            z-index: 12;
            line-height: 48px;
        }

        .div-three .title .item:nth-child(1) {
            position: absolute;
            top: 0;
            left: 5%;
        }

        .div-three .title .item:nth-child(2) {
            position: absolute;
            top: 0;
            left: 40%;
        }

        .div-three .title .item:nth-child(3) {
            position: absolute;
            top: 0;
            left: 60%;
        }

        .div-three .title .item:nth-child(4) {
            position: absolute;
            top: 0;
            right: 5%;
        }

        .div-three .title h3 {
            text-align: center;
        }

        .div-three .body {
            position: relative;
            padding-top: 10rem;
        }

        .div-three .body .item {
            z-index: 12;
        }

        .div-three .body .item:nth-child(1) {
            position: absolute;
            top: 0;
            left: 5%;
        }

        .div-three .body .item:nth-child(2) {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .div-three .body .item:nth-child(3) {
            position: absolute;
            top: 0;
            right: 5%;
        }

        .div-three .body .item:nth-child(3) h3 {
            text-align: right;
        }

        .div-three .body h2 {
            margin-bottom: 4rem;
            font-weight: 400;
            /*font-size: 20px;*/
            direction: rtl;
        }

        .div-three .body h3 {
            margin-bottom: 2.5rem;
            font-weight: 400;
            line-height: 30px;
        }

        .div-three .body .item h3 span {
            color: #6F6F84;
        }

        .text-right {
            text-align: right;
        }

        .bg {
            position: absolute;
            top: 6rem;
            left: 0;
            width: 100%;
            height: 5rem;
            background: #F5F5F5;
        }

        .bg-2 {
            position: absolute;
            top: 11.2rem;
            right: 0;
            width: 65%;
            height: 5rem;
            background: #F5F5F5;
        }

        .img img {
            display: block;
            margin: 0 auto;
            width: 127px;
            height: 127px;
        }

        .hr-two {
            margin-top: 8rem;
            width: 90%;
        }

        @media screen and (max-width: 991px) {
            .div-one .logo img {
                width: 8rem;
            }

            .div-three .title {
                padding-bottom: 11rem;
            }

            .div-three .title .item:nth-child(2) {
                left: 42%;
            }

            .div-three .title .item:nth-child(3) {
                left: 55%;
            }

            .div-three .body {
                padding-top: 9rem;
            }

            .div-three .body .item:nth-child(2) {
                position: absolute;
                top: 0;
                left: 45%;
                transform: translateX(-50%);
            }

            .div-three .body h2 {
                font-size: 16px;
            }

            .div-three .body h3 {
                margin-bottom: 2rem;
            }

            .bg-2 {
                width: 70%;
                top: 10.5rem;
            }

            .hr-two {
                margin-top: 7rem;
            }
        }

        @media screen and (max-width: 778px) {
            h2 {
                font-size: 18px !important;
            }

            h3 {
                font-size: 15px;
            }

            h4, span, p {
                font-size: 12px;
            }

            .div-two .cont-two h3 {
                line-height: 24px;
            }
        }

        .main {
            margin: 3rem 0;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="container-bdf">
        <div class="div-one">
            <div class="logo">
                <img src="{{asset('assets/vendor/images/logo-551edfe5.png')}}" alt="logo">

            </div>
            <div class="content">
                <h2>فاتورة ضريبية مبسطة</h2>
                <h3>Simplified Tax Invoice</h3>
                <p><b>شركة توصيل الخليجية للتجارة</b></p>
                <p><b>البريد الالكتروني :</b> info@eats.com.sa</p>
                <p><b>رقم التواصل :</b> 920033084 </p>
                <p><b>العنوان :</b> 13241طريق الإمام عبدالله بن سعود بن عبد العزيز - غرناطة - الرياض</p>
                <p><b>الرقم الضريبي : </b>310564268100003</p>
            </div>
        </div>
        <hr>
        <div class="div-two">
            <div class="cont-one box">
                <h5>Invoice Date :</h5>
                <h5>Order No :</h5>
                {{--                <h5>Invoice Number :</h5>--}}
            </div>
            <div class="cont-two box">
                <h3>{{ Carbon::parse($order->created_at)->format('j F Y g:i A') }}</h3>
                <h3>{{ $order->code }}</h3>
                {{--                <h3>29-03-2023</h3>--}}
            </div>
            <div class="cont-three box">
                <h5> :تاريخ الفاتورة </h5>
                <h5> : رقم الطلب </h5>
                {{--                <h5> : تاريخ الفاتورة</h5>--}}
            </div>
        </div>
        <hr style="margin-top: 7rem;">
    </div>
    <div class="div-three">
        <div class="title">

            <div class="item">
                <h4>
                    Services Details
                    <br>
                    تفاصيل الخدمات
                </h4>
                <h4>
                    خدمات التوصيل<br>Delivery Services
                </h4>
            </div>
            <div class="item">
                <h4>
                    VAT Rate
                    <br>
                    نسبة الضريبة
                </h4>
                <h3>15%</h3>
            </div>

            <div class="item">
                <h4>
                    Total Taxable Amount
                    <br>
                    الإجمالي
                </h4>
                <h3>{{ $order->delivery_fee }}</h3>
            </div>

            <div class="bg"></div>
        </div>
        <div class="body">
            <div class="item">
                <h3>
                    Total Taxable Amount <br>
                    (Excluding VAT) :
                </h3>
                <h3>Total VAT :</h3>
                <h3>
                    Total :
                    <br>
                    <span>VAT included 15%</span>
                </h3>
            </div>
            <div class="item">
                <h2>{{ $order->deliveryExcludeVat }} ريال سعودي</h2>
                <h2>{{ $order->deliveryVat }} ريال سعودي</h2>
                <h2>{{ $order->delivery_fee }} ريال سعودي</h2>
            </div>
            <div class="item">
                <h3 class="text-right">
                    الإجمالي الخاضع للضريبة <br>
                    : (غير شامل ضريبة القيمة المضافة)
                </h3>
                <h3> : مجموع ضريبة القيمة المضافة</h3>
                <h3 class="text-right">
                    : الإجمالي
                    <br>
                    <span>شامل ضريبة القيمة المضافة 15%</span>
                </h3>
            </div>
            <div class="bg-2"></div>
        </div>
        <hr class="hr-two">
        <div class="img">
            <img src="{{$displayQRCode}}" alt="bar">
        </div>
    </div>
</div>
</body>
</html>

