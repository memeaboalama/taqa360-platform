<div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true" up-overlay="swp">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginRequiredModalLabel">@lang('ecommerce::ecommerce.login_required')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h4 class="text-muted">@lang('ecommerce::ecommerce.login_to_continue')</h4>
                    <p class="text-muted mt-3">@lang('ecommerce::ecommerce.login_to_add_cart')</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="theme_btn btn-secondary me-2" data-bs-dismiss="modal">
                    @lang('ecommerce::ecommerce.cancel')
                    <span class="theme_btn_eff"></span>
                </button>
                <a href="{{ route('auth.login') }}" class="theme_btn" up-target=".main-body" onclick="closeLoginModalCheck()">
                    @lang('ecommerce::ecommerce.login')
                    <span class="theme_btn_eff"></span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function closeLoginModalCheck() {
    const modalEl = document.getElementById('loginRequiredModal');
    if (modalEl) {
        // Try Bootstrap 5
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        } 
        // Fallback or Bootstrap 4/jQuery
        else if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modalEl).modal('hide');
        }
        
        // Manual cleanup if needed
        setTimeout(() => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
            document.body.classList.remove('modal-open');
        }, 500);
    }
}
</script>
