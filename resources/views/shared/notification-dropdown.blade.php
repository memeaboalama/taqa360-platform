@php
    $notificationVariant = $notificationVariant ?? 'dashboard';
    $notificationCount = $notifications->count();
    $notificationIsRtl = in_array(app()->getLocale(), ['ar', 'he'], true);
@endphp
<div class="dropdown notification-center notification-center--{{ $notificationVariant }}"
     dir="{{ $notificationIsRtl ? 'rtl' : 'ltr' }}">
    <button type="button"
            class="notification-center__toggle {{ $notificationVariant === 'store' ? 'action' : 'header-link' }}"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-haspopup="true"
            aria-label="{{ __('admin::home.notifications') }}">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path>
        </svg>
        @if($notificationCount > 0)
            <span class="notification-center__count" aria-hidden="true">
                {{ $notificationCount > 99 ? '99+' : $notificationCount }}
            </span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end notification-center__menu">
        <div class="notification-center__heading">
            <div>
                <h2>{{ __('admin::home.notifications') }}</h2>
                <p>{{ __('admin::home.view_notification') }}</p>
            </div>
            <span class="notification-center__heading-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" focusable="false">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path>
                </svg>
            </span>
        </div>

        @if($notificationCount > 0)
            <div class="notification-center__list">
                @foreach($notifications as $notification)
                    <button type="button"
                            class="notification-center__item"
                            data-bs-toggle="modal"
                            data-bs-target="#notificationDetailsModal"
                            data-notification-title="{{ $notification->title }}"
                            data-notification-body="{{ $notification->body }}"
                            data-notification-date="{{ $notification->created_at->format('j M Y H:i') }}"
                            data-notification-time="{{ $notification->created_at->format('c') }}"
                            aria-label="{{ __('admin::home.view_notification') }}: {{ $notification->title }}">
                        <span class="notification-center__item-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path>
                            </svg>
                        </span>
                        <span class="notification-center__item-content">
                            <span class="notification-center__item-title">{{ $notification->title }}</span>
                            <span class="notification-center__item-preview">{{ $notification->body }}</span>
                            <time class="notification-center__item-time" datetime="{{ $notification->created_at->format('c') }}">
                                {{ $notification->created_at->format('j M Y H:i') }}
                            </time>
                        </span>
                        <span class="notification-center__item-arrow" aria-hidden="true">›</span>
                    </button>
                @endforeach
            </div>
        @else
            <div class="notification-center__empty">
                <span class="notification-center__empty-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path>
                    </svg>
                </span>
                <p>{{ __('admin::home.no_notifications') }}</p>
            </div>
        @endif
    </div>
</div>

<div class="modal fade notification-details-modal" id="notificationDetailsModal" tabindex="-1"
     dir="{{ $notificationIsRtl ? 'rtl' : 'ltr' }}"
     aria-labelledby="notificationDetailsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <span class="notification-details-modal__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path>
                    </svg>
                </span>
                <div class="notification-details-modal__heading">
                    <span>{{ __('admin::home.view_notification') }}</span>
                    <h2 class="modal-title" id="notificationDetailsTitle"></h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('admin::home.close') }}"></button>
            </div>
            <div class="modal-body">
                <p id="notificationDetailsBody"></p>
                <time id="notificationDetailsDate" class="notification-details-modal__date"></time>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    {{ __('admin::home.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-center {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .notification-center__toggle {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        padding: 0;
        border: 0;
        border-radius: 12px;
        background: transparent;
        color: inherit;
        cursor: pointer;
        transition: background-color .18s ease, color .18s ease;
    }

    .notification-center__toggle:hover,
    .notification-center__toggle:focus-visible {
        background: rgba(48, 111, 170, .1);
        color: var(--bs-primary, #306faa);
        outline: none;
    }

    .notification-center__toggle svg,
    .notification-center__heading-icon svg,
    .notification-center__item-icon svg,
    .notification-center__empty-icon svg,
    .notification-details-modal__icon svg {
        width: 21px;
        height: 21px;
        fill: none;
        stroke: currentColor;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 1.7;
    }

    .notification-center__count {
        position: absolute;
        top: 1px;
        inset-inline-end: 0;
        display: inline-flex;
        min-width: 18px;
        height: 18px;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 2px solid #fff;
        border-radius: 999px;
        background: #e5484d;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;
    }

    .notification-center__menu {
        width: min(380px, calc(100vw - 24px));
        max-height: min(540px, calc(100vh - 88px));
        overflow: hidden;
        padding: 0;
        border: 1px solid #e8edf3;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 18px 48px rgba(27, 45, 70, .18);
        color: #253247;
        direction: inherit;
        text-align: start;
    }

    .notification-center__heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border-bottom: 1px solid #edf1f5;
    }

    .notification-center__heading h2 {
        margin: 0;
        color: #1d2c41;
        font-size: 15px;
        font-weight: 700;
    }

    .notification-center__heading p {
        margin: 4px 0 0;
        color: #7b8797;
        font-size: 12px;
    }

    .notification-center__heading-icon,
    .notification-center__item-icon,
    .notification-center__empty-icon,
    .notification-details-modal__icon {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #edf5fc;
        color: #306faa;
    }

    .notification-center__heading-icon {
        width: 38px;
        height: 38px;
    }

    .notification-center__list {
        max-height: 440px;
        overflow-y: auto;
        padding: 6px;
    }

    .notification-center__item {
        display: flex;
        width: 100%;
        align-items: flex-start;
        gap: 11px;
        padding: 12px 10px;
        border: 0;
        border-bottom: 1px solid #f0f3f6;
        border-radius: 10px;
        background: #fff;
        color: inherit;
        text-align: start;
        transition: background-color .18s ease;
    }

    .notification-center__item:last-child {
        border-bottom-color: transparent;
    }

    .notification-center__item:hover,
    .notification-center__item:focus-visible {
        background: #f5f9fd;
        outline: none;
    }

    .notification-center__item-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
    }

    .notification-center__item-icon svg {
        width: 17px;
        height: 17px;
    }

    .notification-center__item-content {
        display: flex;
        min-width: 0;
        flex: 1;
        flex-direction: column;
        gap: 3px;
    }

    .notification-center__item-title {
        overflow: hidden;
        color: #26364b;
        font-size: 13px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .notification-center__item-preview {
        display: -webkit-box;
        overflow: hidden;
        color: #68778a;
        font-size: 12px;
        line-height: 1.55;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .notification-center__item-time {
        margin-top: 3px;
        color: #96a0ad;
        font-size: 10px;
    }

    .notification-center__item-arrow {
        margin-inline-start: auto;
        align-self: center;
        color: #9aa6b5;
        font-size: 20px;
        line-height: 1;
    }

    .notification-center__empty {
        display: flex;
        min-height: 150px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 22px;
        color: #7b8797;
        text-align: center;
    }

    .notification-center__empty-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
    }

    .notification-center__empty-icon svg {
        width: 23px;
        height: 23px;
    }

    .notification-center__empty p {
        margin: 0;
        font-size: 13px;
    }

    .notification-details-modal .modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 18px;
        box-shadow: 0 24px 70px rgba(21, 39, 61, .24);
        direction: inherit;
        text-align: start;
    }

    .notification-details-modal .modal-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 20px 22px 16px;
        border-bottom: 1px solid #edf1f5;
    }

    .notification-details-modal__icon {
        width: 42px;
        height: 42px;
    }

    .notification-details-modal__heading {
        min-width: 0;
        flex: 1;
    }

    .notification-details-modal__heading > span {
        display: block;
        margin-bottom: 4px;
        color: #7b8797;
        font-size: 11px;
    }

    .notification-details-modal .modal-title {
        color: #1d2c41;
        font-size: 17px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .notification-details-modal .modal-body {
        padding: 20px 22px;
    }

    .notification-details-modal #notificationDetailsBody {
        margin: 0;
        color: #425168;
        font-size: 14px;
        line-height: 1.8;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }

    .notification-details-modal__date {
        display: block;
        margin-top: 18px;
        color: #8b97a5;
        font-size: 11px;
    }

    .notification-details-modal .modal-footer {
        padding: 0 22px 20px;
        border: 0;
    }

    .notification-center--store .notification-center__toggle {
        width: 30px;
        height: 36px;
        border-radius: 8px;
        font-size: 19px;
    }

    .notification-center--store .notification-center__count {
        top: 0;
        inset-inline-end: -4px;
        min-width: 17px;
        height: 17px;
        font-size: 9px;
    }

    @media (max-width: 575.98px) {
        .notification-center__menu {
            position: fixed !important;
            inset-inline: 12px !important;
            top: 64px !important;
            width: auto;
            max-height: calc(100vh - 78px);
            margin: 0 !important;
            transform: none !important;
        }

        .notification-center__list {
            max-height: calc(100vh - 190px);
        }
    }
</style>

<script>
    (function () {
        var modal = document.getElementById('notificationDetailsModal');
        if (!modal) return;

        document.body.appendChild(modal);
        modal.addEventListener('show.bs.modal', function (event) {
            var trigger = event.relatedTarget;
            if (!trigger) return;

            modal.querySelector('#notificationDetailsTitle').textContent =
                trigger.getAttribute('data-notification-title') || '';
            modal.querySelector('#notificationDetailsBody').textContent =
                trigger.getAttribute('data-notification-body') || '';

            var date = modal.querySelector('#notificationDetailsDate');
            date.textContent = trigger.getAttribute('data-notification-date') || '';
            date.setAttribute('datetime', trigger.getAttribute('data-notification-time') || '');
        });
    })();
</script>
