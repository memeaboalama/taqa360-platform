<div>
    <div class="hstack gap-2 fs-15">
        @CompanyHasRole(['edit_drivers'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill position-relative"
            href="{{ url("company/drivers/show/$row->id") }}"
            title="{{ __('company::home.Edit') }}"
            onclick="this.querySelector('.btn-lbl').classList.add('invisible'); this.querySelector('.btn-spin').classList.remove('d-none'); this.classList.add('disabled');">
            <span class="btn-lbl"><i class="ri-edit-line"></i></span>
            <span class="btn-spin d-none position-absolute top-50 start-50 translate-middle">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>
        @endCompanyHasRole

        @CompanyHasRole(['edit_drivers'])
        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-warning-transparent rounded-pill btn-data-loading"
           data-loading-target="#resetDriverPassword"
           title="{{ __('company::home.Update Password') }}"
           wire:click="$emit('resetDriverPassword',{{ $row->id }})"><i class="ri-lock-password-line"></i></a>
        @endCompanyHasRole

        @CompanyHasRole(['edit_drivers'])
        <a
            class="btn  btn-sm btn-info-transparent rounded-pill"
            href="{{ url("company/drivers/show/$row->id") }}"
            title="edit">{{ __('company::home.collectedCashReport') }}</a>
        @endCompanyHasRole
    </div>
</div>
