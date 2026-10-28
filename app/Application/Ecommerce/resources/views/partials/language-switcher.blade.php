@php
    $locale = app()->getLocale();
    $localizedUrl = static fn (string $language) => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($language, null, [], true);
@endphp
<div class="language-action" aria-label="@lang('ecommerce::ecommerce.language')">
    <button class="language-toggle" type="button" aria-expanded="false" aria-label="@lang('ecommerce::ecommerce.change_language')">
        <i class="fa fa-language" aria-hidden="true"></i>
    </button>
    <ul class="language-menu">
        <li><a href="{{ $localizedUrl('ar') }}" lang="ar" hreflang="ar">@lang('ecommerce::ecommerce.languages.ara')</a></li>
        <li><a href="{{ $localizedUrl('en') }}" lang="en" hreflang="en">@lang('ecommerce::ecommerce.languages.eng')</a></li>
    </ul>
</div>
<style>
    .language-action{position:relative;display:inline-flex;align-items:center}
    .language-toggle{width:30px;height:36px;padding:0;border:0;background:transparent;color:inherit;font-size:19px;cursor:pointer}
    .language-menu{display:none;position:absolute;top:39px;inset-inline-end:0;z-index:30;min-width:110px;margin:0;padding:5px 0;list-style:none;background:#fff;border:1px solid #e9edf1;box-shadow:0 5px 15px rgba(0,0,0,.12);direction:inherit;text-align:start}
    .language-action.open .language-menu{display:block}
    .language-menu a{display:block;padding:7px 12px;color:#5d6269;font-size:11px;text-decoration:none;white-space:nowrap}
    .language-menu a:hover{background:#eef4fa;color:#EA993E}
</style>
<script>
    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('.language-toggle');
        var action = event.target.closest('.language-action');
        document.querySelectorAll('.language-action.open').forEach(function (item) {
            if (item !== action) item.classList.remove('open');
        });
        if (toggle && action) {
            action.classList.toggle('open');
            toggle.setAttribute('aria-expanded', action.classList.contains('open') ? 'true' : 'false');
        }
    });
</script>