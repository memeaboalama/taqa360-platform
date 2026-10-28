<div class="bread_crumb">
    <div class="container">
        <div class="row">
            <div class="col-md-6 sec_low">
                <div class="functionalities">
                    <ul id="breadcrumb" class="breadcrumb">
                        <li><a title="@lang('ecommerce::ecommerce.home')" href="{{ route('home') }}"><i class="fa fa-home" aria-hidden="true"></i> @lang('ecommerce::ecommerce.home')</a></li>
                        <li><strong><i class="fa fa-angle-double-right" aria-hidden="true"></i> {{ $title }}</strong></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="page-title">
                    <h2>{{ $title }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bread_crumb .functionalities ul li a,
    .bread_crumb .functionalities ul li a i,
    .bread_crumb .functionalities ul li strong i {
        color: #ffffff !important;
    }
    .bread_crumb .functionalities ul li strong i {
        display: inline-block;
    }
    [dir="rtl"] .bread_crumb .functionalities ul li strong i {
        transform: scaleX(-1);
    }
</style>
