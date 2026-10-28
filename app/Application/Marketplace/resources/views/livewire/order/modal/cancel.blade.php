<div>
    <div class="modal fade" id="cancelOrder" wire:ignore.self
         data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cancelOrderLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-white border-bottom-0 pb-0 justify-content-between align-items-center">
                    <h6 class="modal-title fw-bold text-dark fs-16" id="cancelOrderLabel">
                        <i class="ri-error-warning-line text-danger me-1"></i> تأكيد إلغاء الطلب
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" wire:loading.attr="disabled" wire:target="destroy"></button>
                </div>
                <form wire:submit.prevent="destroy">
                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="ri-alert-line text-danger display-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-2">هل أنت متأكد من رغبتك في إلغاء هذا الطلب؟</h6>
                        <p class="text-muted fs-13 mb-0">لا يمكن التراجع عن هذا الإجراء بعد التأكيد.</p>
                    </div>
                    <div class="modal-footer bg-white border-top-0 pt-0 pb-4 d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-light btn-md px-4 fw-semibold"
                                data-bs-dismiss="modal" wire:loading.attr="disabled" wire:target="destroy">
                            تراجع
                        </button>
                        <button type="submit" class="btn btn-danger btn-md px-4 fw-semibold"
                                wire:loading.attr="disabled" wire:target="destroy">
                            <span wire:loading.class="d-none" wire:target="destroy">
                                تأكيد الإلغاء
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="destroy">
                                جاري الإلغاء... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
