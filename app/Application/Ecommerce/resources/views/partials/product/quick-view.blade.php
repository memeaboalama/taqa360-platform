<style>
    .quick-view-modal .modal-body {
        position: relative;
        padding: 30px;
    }

    .quick-view-close {
        position: absolute;
        top: 12px;
        right: 14px;
        width: 32px;
        height: 32px;
        padding: 0;
        border: none;
        border-radius: 50%;
        background-color: #f1f2f7;
        color: #3e3e3e;
        font-size: 18px;
        font-weight: 700;
        line-height: 1;
        opacity: 0.85;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .quick-view-close:hover {
        background-color: #306FAA;
        color: #ffffff;
        opacity: 1;
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    [dir="rtl"] .quick-view-close {
        right: auto;
        left: 14px;
    }

    .quick-view-modal .product-right .product-buttons form,
    .quick-view-modal .product-right .product-buttons a {
        margin: 0 5px;
    }

    .quick-view-modal .theme_btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        float: none;
    }

    .quick-view-footer {
        display: flex;
        margin-top: 25px;
        border-top: 1px solid #e7e7e7;
        padding-top: 18px;
    }

    .quick-view-footer > * {
        flex: 1 1 0;
        min-width: 0;
    }

    .quick-view-footer form {
        display: flex;
        margin: 0;
    }

    .quick-view-footer .theme_btn {
        width: 100%;
        margin: 0;
        border-radius: 0;
        white-space: nowrap;
    }

    .quick-view-footer > * + * {
        margin-left: -2px;
    }

    [dir="rtl"] .quick-view-footer > * + * {
        margin-left: 0;
        margin-right: -2px;
    }

    .theme_btn.in-favorites,
    .theme_btn.in-favorites:hover {
        color: #ffffff;
        border-color: #306FAA;
    }

    .theme_btn.in-favorites .theme_btn_eff {
        border: 0 solid #306FAA;
        background-color: #306FAA;
    }

    .theme_btn.in-favorites:hover .theme_btn_eff {
        background-color: #306FAA;
        border: 0 solid #306FAA;
    }
</style>

<div class="modal fade bd-example-modal-lg theme-modal" id="quick-view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content quick-view-modal">
            <div class="modal-body">
                <button type="button" class="close quick-view-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="quick-view-body">
                    <div class="text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>