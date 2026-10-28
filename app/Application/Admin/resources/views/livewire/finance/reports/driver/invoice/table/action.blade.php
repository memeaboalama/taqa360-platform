<div>
    <div class="hstack gap-2 fs-15">
        <a
            class="btn btn-sm btn-primary-transparent rounded-pill"
            href="{{ route('admin:finance.reports.drivers.invoice.pdf',$row->id) }}"
            target="_blank"
            title="{{ __('admin::home.invoice') }}"
            onclick="
                var btn = this;
                btn.innerHTML = '<span class=\'spinner-border spinner-border-sm align-middle me-1\'></span> {{ __('admin::home.Wait') }}';
                btn.classList.add('disabled');
                setTimeout(function(){ btn.innerHTML = '<i class=\'ri-file-pdf-line\'></i> {{ __('admin::home.invoice') }}'; btn.classList.remove('disabled'); }, 3000);
            "><i class="ri-file-pdf-line"></i> {{ __('admin::home.invoice') }}</a>
    </div>
</div>
