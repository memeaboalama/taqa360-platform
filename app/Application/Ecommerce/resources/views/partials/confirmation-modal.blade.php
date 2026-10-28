<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">@lang('ecommerce::ecommerce.warning')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fa fa-exclamation-circle fa-3x text-danger mb-3"></i>
                    <h4 class="text-muted" id="confirmationModalTitle">@lang('ecommerce::ecommerce.confirm')</h4>
                    <p class="text-muted mt-3" id="confirmationModalBody">@lang('ecommerce::ecommerce.confirm_action')</p>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="theme_btn btn-secondary me-2" data-bs-dismiss="modal">
                    @lang('ecommerce::ecommerce.cancel')
                    <span class="theme_btn_eff"></span>
                </button>
                <form id="confirmationModalForm" method="POST" action="">
                    @csrf
                    <div id="confirmationMethodField">
                        @method('DELETE')
                    </div>
                    <div id="confirmationHiddenFields"></div>

                    <button type="submit" class="theme_btn btn-danger">
                        <span id="confirmationModalButton">@lang('ecommerce::ecommerce.delete')</span>
                        <span class="theme_btn_eff"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showConfirmationModal(url, message, title = null, buttonText = null, method = 'DELETE', extraFields = {}) {
    const modalEl = document.getElementById('confirmationModal');
    const form = document.getElementById('confirmationModalForm');
    const body = document.getElementById('confirmationModalBody');
    const titleEl = document.getElementById('confirmationModalTitle');
    const btn = document.getElementById('confirmationModalButton');
    const methodField = document.getElementById('confirmationMethodField');
    const hiddenFields = document.getElementById('confirmationHiddenFields');

    form.action = url;
    body.textContent = message;

    if (title) titleEl.textContent = title;
    if (buttonText) btn.textContent = buttonText;

    if (method.toUpperCase() === 'DELETE') {
        methodField.innerHTML = '<input type="hidden" name="_method" value="DELETE">';
    } else if (method.toUpperCase() === 'PUT') {
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    } else {
        methodField.innerHTML = '';
    }

    hiddenFields.innerHTML = '';
    for (const [key, value] of Object.entries(extraFields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        hiddenFields.appendChild(input);
    }

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (!modal) {
            modal = new bootstrap.Modal(modalEl);
        }
        modal.show();
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modalEl).modal('show');
    }
}

(function () {
    const modalEl = document.getElementById('confirmationModal');
    const form = document.getElementById('confirmationModalForm');
    let processing = false;

    if (!form || !modalEl) return;

    form.addEventListener('submit', function (event) {
        if (processing) {
            event.preventDefault();
            return;
        }
        processing = true;
        event.preventDefault();

        const submitBtn = form.querySelector('button[type="submit"]');
        const dismissBtns = modalEl.querySelectorAll('[data-bs-dismiss="modal"]');

        const label = document.getElementById('confirmationModalButton');
        if (label && !label.querySelector('.spinner-border')) {
            label.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' + label.textContent;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.pointerEvents = 'none';
            submitBtn.style.opacity = '0.65';
        }

        dismissBtns.forEach(btn => {
            btn.disabled = true;
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.65';
        });

        setTimeout(function () {
            form.submit();
        }, 0);
    });

    modalEl.addEventListener('hide.bs.modal', function (event) {
        if (processing) {
            event.preventDefault();
        }
    });
})();
</script>
