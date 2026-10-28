<div class="card custom-card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">@lang('admin::educators.bio')</h5>
        <p class="text-muted small">{{ $educator->bios?->description ?? __('admin::educators.no_bio_added') }}</p>
        <hr class="my-4">
        <h5 class="fw-bold mb-3">@lang('admin::educators.personal_info')</h5>
        <div class="row g-4">
            <div class="col-md-4"><div class="data-label">@lang('admin::educators.name')</div><div class="data-value">{{ $educator->name ?? __('admin::educators.not_specified') }}</div></div>
            <div class="col-md-4"><div class="data-label">@lang('admin::educators.email')</div><div class="data-value">{{ $educator->email ?? __('admin::educators.not_specified') }}</div></div>
            <div class="col-md-4"><div class="data-label">@lang('admin::educators.phone')</div><div class="data-value">{{ $educator->phone ?? __('admin::educators.not_specified') }}</div></div>
            <div class="col-md-4"><div class="data-label">@lang('admin::educators.nationality')</div><div class="data-value">{{ $educator->profile?->nationality?->title ?? __('admin::educators.not_specified') }}</div></div>
            <div class="col-md-4"><div class="data-label">@lang('admin::educators.gender')</div><div class="data-value">{{ $educator->profile?->gender ? __("admin::educators." . $educator->profile->gender) : __('admin::educators.not_specified') }}</div></div>
            <div class="col-md-4"><div class="data-label">@lang('admin::educators.experience_years')</div><div class="data-value">{{ $educator->profile?->experience_years ?? '0' }} @lang('admin::educators.years')</div></div>
            <div class="col-md-4"><div class="data-label">@lang('admin::educators.country')</div><div class="data-value">{{ $educator->profile?->country?->title ?? __('admin::educators.not_specified') }}</div></div>
            <div class="col-md-4"><div class="data-label">@lang('admin::educators.city')</div><div class="data-value">{{ $educator->profile?->city?->title ?? __('admin::educators.not_specified') }}</div></div>
        </div>

        <hr class="my-4">
        <h5 class="fw-bold mb-3">@lang('admin::educators.languages')</h5>
        @if($educator->languages->isNotEmpty())
            <div class="d-flex flex-wrap gap-2">
                @foreach($educator->languages as $lang)
                    <span class="badge rounded-pill bg-light text-dark border p-2 fw-normal">
                        {{ $lang->language?->title }}
                        <span class="vr mx-1"></span>
                        {{ __("admin::educators.level_" . $lang->level) }}
                        @if($lang->is_primary)
                            <span class="text-primary fw-bold">@lang('admin::educators.primary_language')</span>
                        @endif
                    </span>
                @endforeach
            </div>
        @else
            <p class="text-muted small">@lang('admin::educators.no_languages_added')</p>
        @endif

        <hr class="my-4">
        <h5 class="fw-bold mb-3">@lang('admin::educators.bank_info')</h5>
        @if($educator->bankInfo)
            <div class="row g-4">
                <div class="col-md-4"><div class="data-label">@lang('admin::educators.account_holder_name')</div><div class="data-value">{{ $educator->bankInfo->name ?? __('admin::educators.not_specified') }}</div></div>
                <div class="col-md-4"><div class="data-label">@lang('admin::educators.bank_name')</div><div class="data-value">{{ $educator->bankInfo->bank_name ?? __('admin::educators.not_specified') }}</div></div>
                <div class="col-md-4"><div class="data-label">@lang('admin::educators.iban')</div><div class="data-value">{{ $educator->bankInfo->iban ?? __('admin::educators.not_specified') }}</div></div>
                <div class="col-md-4"><div class="data-label">@lang('admin::educators.bank_code')</div><div class="data-value">{{ $educator->bankInfo->bank_code ?? __('admin::educators.not_specified') }}</div></div>
                <div class="col-md-4"><div class="data-label">@lang('admin::educators.bank_country')</div><div class="data-value">{{ $educator->bankInfo->bank_country ?? __('admin::educators.not_specified') }}</div></div>
            </div>
        @else
            <p class="text-muted small">@lang('admin::educators.no_bank_info')</p>
        @endif

        @if($educator->socialLinks->isNotEmpty())
        <hr class="my-4">
        <h5 class="fw-bold mb-3">@lang('admin::educators.social_links')</h5>
        <div class="d-flex gap-3">
            @foreach($educator->socialLinks as $link)
            <a href="{{ $link->url }}" target="_blank" class="text-secondary">{{ ucfirst($link->platform) }}</a>
            @endforeach
        </div>
        @endif
    </div>
</div>
