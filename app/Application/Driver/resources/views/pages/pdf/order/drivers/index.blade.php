@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin::home.Driver Order Report') }}</title>
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
            width: 8rem;
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
            top: 40px;
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
            margin-top: 10rem;
        }

        .div-three .title {
            position: relative;
            padding-bottom: 6rem;
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
            left: 4%;
        }

        .div-three .title .item:nth-child(2) {
            position: absolute;
            top: 0;
            left: 12%;
        }

        .div-three .title .item:nth-child(3) {
            position: absolute;
            top: 0;
            left: 20%;
        }

        .div-three .title .item:nth-child(4) {
            position: absolute;
            top: 0;
            right: 28%;
        }

        .div-three .title .item:nth-child(5) {
            position: absolute;
            top: 0;
            right: 36%;
        }

        .div-three .title .item:nth-child(6) {
            position: absolute;
            top: 0;
            right: 44%;
        }

        .div-three .title .item:nth-child(6) {
            position: absolute;
            top: 0;
            right: 52%;
        }

        .div-three .title .item:nth-child(7) {
            position: absolute;
            top: 0;
            right: 60%;
        }

        .div-three .title .item:nth-child(8) {
            position: absolute;
            top: 0;
            right: 68%;
        }

        .div-three .title .item:nth-child(9) {
            position: absolute;
            top: 0;
            right: 76%;
        }

        .div-three .title .item:nth-child(10) {
            position: absolute;
            top: 0;
            right: 84%;
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

        table {
            border-collapse:separate;
            border-spacing: 0 2em;

        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
            height: 5em;
        }
        th{
            border-bottom: 1px solid black;
            border-top: 1px solid black;
            padding: 2px 10px 8px 10px
        }
        td{
            padding: 2px 10px 8px 10px
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
                <h2>كشف حساب</h2>
                <p><b>شركة توصيل الخليجية للتجارة</b></p>
                <p><b>البريد الالكتروني :</b> info@eats.com.sa</p>
                <p><b>رقم التواصل :</b> 920033084 </p>
                <p><b>العنوان :</b> 13241طريق الإمام عبدالله بن سعود بن عبد العزيز - غرناطة - الرياض</p>
                <p><b>الرقم الضريبي : </b>310564268100003</p>
            </div>
        </div>
        <hr class="hr-two">
        <div class="div-one">
            <div class="content">
                <h1><b>كشف حساب التاجر- {{ $orders->first()?->driver?->company_name }}</b> </h1>


            @if(isset($filters['date']) && $filters['date'] != "")
                    <h2> التاريخ :<b>{{ isset($filters['date']) && $filters['date'] != "" ? $filters['date'] : "----" }}</b>@if(isset($filters['to_date']) && $filters['to_date'] != "")  - <b>{{ isset($filters['to_date']) && $filters['to_date'] != "" ? $filters['to_date'] : "----" }}</b>@endif</h2>
                @endif
            </div>
        </div>
    </div>
    <div class="div-three container-bdf " style=" margin-top: 2rem;">
        <table class="table " style="width: 100% !important;" >
            <thead style="background: #F5F5F5; height: 7rem">
            <tr>
                <th>{{ __('admin::home.payment_method') }}</th>
                <th>{{ __('admin::home.status') }}</th>
                <th>{{ __('admin::home.date') }}</th>
                <th>{{ __('admin::home.Due to the driver') }}</th>
                <th>{{ __('admin::home.commission') }}</th>
                <th>{{ __('admin::home.commission percentage') }}</th>
                <th>{{ __('admin::home.totalOrder') }}</th>
                <th style="margin-right: 2rem">{{ __('admin::home.Branch Name') }}</th>
                <th style="margin-right: 2rem">{{ __('admin::home.userName') }}</th>
                <th>{{ __('admin::home.code') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->payment_method }}</td>
                    <td>{{ $order->status?->trans() }}</td>
                    <td>{{ Carbon::parse($order->created_at)->format('Y-m-d') }}</td>
                    <td class="fit">{{ $order->dueToDriver }}</td>
                    <td class="fit">{{ $order->commission }}</td>
                    <td class="fit">{{ $order->driver?->commissionPercentage }}</td>
                    <td class="fit">{{ number_format($order?->total_after_vat, 2) }}</td>
                    <td>{{ $order->branch?->title }}</td>
                    <td>{{ $order->user?->name }}</td>
                    <td>{{ $order->code }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <hr style="margin-top: 4rem">
        <div class="body">
            <div class="item">
                <h3>
                    Total :
                    <br>
                    <span>VAT included 15%</span>
                </h3>
                <h3>
                    Total Commission :
                </h3>
                <h3>Total Due to Driver :</h3>

            </div>
            <div class="item">
                <h2>{{ number_format($orders->sum('total_after_vat'), 2) }} ريال سعوي</h2>
                <h2>{{ number_format($orders->sum(function($order) {return $order->commission;})) }} ريال سعوي</h2>
                <h2>{{ number_format($orders->sum(function($order) {return $order->dueToDriver;})) }} ريال سعوي</h2>
            </div>
            <div class="item">
                <h3 class="text-right">
                    : الإجمالي
                    <br>
                    (15%) <span>شامل ضريبة القيمة المضافة</span>
                </h3>
                <h3> : إجمالي العمولة </h3>

                <h3 class="text-right">
                    : إجمالي المستحق للتاجر
                </h3>

            </div>
        </div>
        <hr class="hr-two">
    </div>
    <hr class="hr">
</div>
</body>
</html>

