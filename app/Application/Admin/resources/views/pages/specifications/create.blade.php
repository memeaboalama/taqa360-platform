@extends('admin::layout.layout')
@section('title', __('admin::home.' . ($mode === 'edit' ? 'specification_edit_template' : ($mode === 'copy' ? 'specification_copy_template' : 'specification_new_template'))))

@section('content')
    <div class="col-12 specification-editor">
        <livewire:admin::specification.create :specification-id="$specificationId" :mode="$mode"/>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dragula/dragula.min.css') }}">
    <style>
        .specification-editor .field-drag-handle,
        .field-list-row.gu-mirror .field-drag-handle { display:inline-flex; align-items:center; justify-content:center; width:34px; height:36px; padding:.35rem; border:1px solid rgba(var(--primary-rgb),.3); border-radius:7px; background:rgba(var(--primary-rgb),.09); color:rgb(var(--primary-rgb)); cursor:grab; touch-action:none; flex-shrink:0; }
        .specification-editor .field-drag-handle svg { flex-shrink:0; pointer-events:none; }
        .specification-editor .field-drag-handle:hover { background:rgba(var(--primary-rgb),.17); border-color:rgb(var(--primary-rgb)); }
        .specification-editor .field-drag-handle:focus-visible { outline:3px solid rgba(var(--primary-rgb),.3); outline-offset:3px; }
        .specification-editor .field-drag-handle:active { cursor:grabbing; }
        .specification-editor .field-list-saving { pointer-events:none; opacity:.65; }
        .field-list-row.gu-mirror { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem; background:var(--custom-white,#fff); border:1px solid rgb(var(--primary-rgb)); border-radius:.6rem; box-shadow:0 10px 30px rgba(0,0,0,.14); cursor:grabbing; }
        .field-list-row.gu-mirror .field-list-details,.field-list-row.gu-mirror .field-list-actions { display:flex; align-items:center; gap:.5rem; }
        .field-list-row.gu-mirror .field-list-meta { color:#8293a8; font-size:.8rem; }
        .specification-editor {
            --editor-border: #dce5ef;
            --editor-muted: #8293a8;
            color: #23364b;
        }

        .specification-editor .editor-header {
            border-bottom: 1px solid var(--editor-border);
            margin-bottom: 1.5rem;
            padding: 0 0 1rem;
        }

        .specification-editor .editor-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: .35rem;
        }

        .specification-editor .editor-meta {
            align-items: center;
            color: var(--editor-muted);
            display: flex;
            flex-wrap: wrap;
            font-size: .75rem;
            gap: .75rem;
        }

        .specification-editor .editor-card,
        .specification-editor .preview-card {
            background: #fff;
            border: 1px solid var(--editor-border);
            border-radius: .7rem;
        }

        .specification-editor .spec-stat-card {
            background: #f4f8fc;
            border-radius: .5rem;
            min-height: 72px;
            padding: .8rem 1rem;
        }

        .specification-editor .spec-stat-value {
            color: #286fa9;
            font-size: 1.15rem;
            font-weight: 700;
        }

        .specification-editor .spec-stat-label {
            color: var(--editor-muted);
            font-size: .72rem;
            margin-top: .2rem;
        }

        .specification-editor .editor-card-header,
        .specification-editor .preview-card-header {
            align-items: center;
            border-bottom: 1px solid var(--editor-border);
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .specification-editor .editor-card-body {
            padding: 1.25rem;
        }

        .specification-editor .form-label {
            color: #63788f;
            font-size: .78rem;
            font-weight: 500;
        }

        .specification-editor .form-control,
        .specification-editor .form-select {
            border-color: var(--editor-border);
            border-radius: .45rem;
            font-size: .82rem;
            min-height: 42px;
        }

        .specification-editor textarea.form-control {
            min-height: 90px;
        }

        .specification-editor .editor-tabs {
            border-bottom: 1px solid var(--editor-border);
            display: flex;
            gap: .4rem;
            margin-bottom: 1.5rem;
        }

        .specification-editor .editor-tab {
            background: transparent;
            border: 0;
            border-bottom: 3px solid transparent;
            color: #6f8399;
            font-size: .82rem;
            padding: .8rem 1rem;
        }

        .specification-editor .editor-tab.active {
            border-bottom-color: #286fa9;
            color: #286fa9;
            font-weight: 700;
        }

        .specification-editor .editor-tab-count {
            background: #edf2f7;
            border-radius: .2rem;
            color: #657b91;
            font-size: .68rem;
            margin-inline-start: .35rem;
            padding: .1rem .35rem;
        }

        .specification-editor .editor-note {
            background: #eef5fc;
            border-radius: .45rem;
            color: #587798;
            font-size: .74rem;
            line-height: 1.8;
            padding: .8rem 1rem;
        }

        .specification-editor .field-row,
        .specification-editor .tree-row {
            border: 1px solid #e6edf4;
            border-radius: .45rem;
            padding: .8rem;
        }

        .specification-editor .field-list-row {
            align-items: center;
            border: 1px solid #dfe7ef;
            border-radius: .45rem;
            display: flex;
            justify-content: space-between;
            min-height: 64px;
            padding: .75rem 1rem;
        }

        .specification-editor .field-list-details {
            align-items: center;
            display: flex;
            gap: .75rem;
            min-width: 0;
        }

        .specification-editor .field-type-icon {
            align-items: center;
            background: #f0f5fa;
            border-radius: .4rem;
            color: #286fa9;
            display: inline-flex;
            flex: 0 0 38px;
            font-size: .75rem;
            font-weight: 700;
            height: 38px;
            justify-content: center;
        }

        .specification-editor .field-list-meta {
            color: var(--editor-muted);
            display: flex;
            flex-wrap: wrap;
            font-size: .7rem;
            gap: .45rem;
            margin-top: .2rem;
        }

        .specification-editor .field-list-actions {
            display: flex;
            flex: 0 0 auto;
            gap: .2rem;
        }

        .specification-editor .field-list-actions .btn {
            color: #647a90;
            min-width: 30px;
            padding: .2rem;
        }

        .specification-editor .field-list-actions .btn:disabled {
            opacity: .35;
        }

        .specification-editor .field-modal-backdrop {
            align-items: center;
            background: rgb(25 42 58 / 48%);
            display: flex;
            inset: 0;
            justify-content: center;
            padding: 1rem;
            position: fixed;
            z-index: 1090;
        }

        .specification-editor .field-modal-backdrop--from-left {
            direction: ltr;
            justify-content: flex-start;
            padding: 0;
        }

        .specification-editor .field-modal-backdrop--from-right {
            direction: ltr;
            justify-content: flex-end;
            padding: 0;
        }

        .specification-editor .field-modal {
            background: #fff;
            border-radius: .55rem;
            box-shadow: 0 1rem 3rem rgb(0 0 0 / 20%);
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 2rem);
            max-width: 560px;
            overflow: hidden;
            width: 100%;
        }

        .specification-editor .field-modal-backdrop--from-left .field-modal {
            animation: field-modal-enter-from-left .28s ease-out both;
            border-radius: 0 .55rem .55rem 0;
            direction: rtl;
            height: 100vh;
            max-height: 100vh;
        }

        .specification-editor .field-modal-backdrop--from-right .field-modal {
            animation: field-modal-enter-from-right .28s ease-out both;
            border-radius: .55rem 0 0 .55rem;
            direction: ltr;
            height: 100vh;
            max-height: 100vh;
        }

        @keyframes field-modal-enter-from-left {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        @keyframes field-modal-enter-from-right {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .specification-editor .field-modal-header,
        .specification-editor .field-modal-footer {
            align-items: center;
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .specification-editor .field-modal-header {
            border-bottom: 1px solid var(--editor-border);
        }

        .specification-editor .field-modal-header h3 {
            color: #23364b;
            font-size: 1rem;
            font-weight: 700;
            margin: 0 0 .25rem;
        }

        .specification-editor .field-modal-header p,
        .specification-editor .field-modal-footer > span {
            color: var(--editor-muted);
            font-size: .72rem;
            margin: 0;
        }

        .specification-editor .field-modal-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding: 1.25rem;
        }

        .specification-editor .field-visibility-heading {
            color: #344b63;
            font-size: .82rem;
            font-weight: 700;
            margin: .25rem 0 .5rem;
        }

        .specification-editor .field-visibility-row {
            align-items: center;
            border-bottom: 1px solid #edf1f5;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            min-height: 54px;
            padding: .55rem .25rem;
        }

        .specification-editor .field-visibility-row strong,
        .specification-editor .field-visibility-row small {
            display: block;
        }

        .specification-editor .field-visibility-row strong {
            color: #344b63;
            font-size: .76rem;
        }

        .specification-editor .field-visibility-row small {
            color: var(--editor-muted);
            font-size: .67rem;
            margin-top: .15rem;
        }

        .specification-editor .conditional-config {
            background: #f4f8fc;
            border-radius: .45rem;
            margin: .6rem 0 .85rem;
            padding: .8rem;
        }

        .specification-editor .field-modal-footer {
            background: #fff;
            border-top: 1px solid var(--editor-border);
            flex: 0 0 auto;
            gap: 1rem;
            position: sticky;
            bottom: 0;
            z-index: 1;
        }

        .specification-editor .tree-children {
            border-inline-start: 1px solid #dfe8f1;
            margin-inline-start: 1.4rem;
            padding-inline-start: .8rem;
        }

        .specification-editor .tree-label {
            color: #344b63;
            font-size: .8rem;
            font-weight: 600;
        }

        .specification-editor .tree-code {
            color: var(--editor-muted);
            font-size: .68rem;
        }

        .specification-editor .preview-card {
            position: sticky;
            top: 1rem;
        }

        .specification-editor .preview-card-body {
            padding: 1rem;
        }

        .specification-editor .quick-preview-field .form-control,
        .specification-editor .quick-preview-field .form-select {
            min-height: 34px;
        }

        .specification-editor .merchant-preview-backdrop {
            align-items: center;
            background: rgb(25 42 58 / 48%);
            display: flex;
            inset: 0;
            justify-content: center;
            padding: 1.5rem;
            position: fixed;
            z-index: 1100;
        }

        .specification-editor .merchant-preview-modal {
            background: #fff;
            border-radius: .65rem;
            box-shadow: 0 1rem 3rem rgb(0 0 0 / 20%);
            max-height: min(85vh, 760px);
            max-width: 880px;
            overflow: hidden;
            width: 100%;
        }

        .specification-editor .merchant-preview-header {
            align-items: center;
            border-bottom: 1px solid var(--editor-border);
            display: flex;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
        }

        .specification-editor .merchant-preview-header h3 {
            color: #23364b;
            font-size: 1rem;
            font-weight: 700;
            margin: 0 0 .3rem;
        }

        .specification-editor .merchant-preview-header p {
            color: var(--editor-muted);
            font-size: .75rem;
            margin: 0;
        }

        .specification-editor .merchant-preview-body {
            display:grid;
            grid-template-columns:repeat(12,minmax(0,1fr));
            column-gap:1.25rem;
            max-height: calc(min(85vh, 760px) - 74px);
            overflow-y: auto;
            padding: 1.5rem;
        }

        .specification-editor .merchant-preview-field {
            grid-column:span var(--field-columns,6);
            min-width:0;
            border-bottom: 1px solid #edf1f5;
            margin-bottom: 1.2rem;
            padding-bottom: 1.2rem;
        }

        .specification-editor .merchant-preview-field:last-child {
            border-bottom: 0;
            margin-bottom: 0;
        }

        .specification-editor .merchant-preview-field .form-label {
            color: #344b63;
            font-weight: 600;
        }

        .specification-editor .merchant-preview-boolean .btn {
            min-height: 42px;
        }

        .specification-editor .merchant-preview-options {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .specification-editor .editor-multiselect { position:relative; }
        .specification-editor .editor-multiselect[open] { z-index:20; }
        .specification-editor .editor-multiselect summary { cursor:pointer; list-style:none; overflow:hidden; padding-inline-end:2.5rem; text-overflow:ellipsis; white-space:nowrap; }
        .specification-editor .editor-multiselect summary::-webkit-details-marker { display:none; }
        .specification-editor .editor-multiselect-options { position:absolute; inset-inline:0; top:calc(100% + 5px); max-height:260px; overflow-y:auto; padding:.5rem; background:var(--custom-white,#fff); border:1px solid var(--editor-border); border-radius:8px; box-shadow:0 8px 24px rgba(0,0,0,.12); }
        .specification-editor .editor-multiselect-option { display:flex; align-items:center; gap:.65rem; padding:.65rem .75rem; margin:0; min-height:36px; border-radius:5px; cursor:pointer; }
        .specification-editor .editor-multiselect-option:hover { background:rgba(var(--primary-rgb),.06); }
        .specification-editor .editor-multiselect-option .form-check-input { float:none; margin:0; flex-shrink:0; }
        .specification-editor .editor-multiselect--above .editor-multiselect-options { top:auto; bottom:calc(100% + 5px); }
        .specification-editor .merchant-preview-field .form-select { min-height:42px; border-radius:7px; font-size:.9375rem; }

        .specification-editor .merchant-preview-options span {
            background: #f7f9fc;
            border: 1px solid var(--editor-border);
            border-radius: .4rem;
            color: #52677e;
            display: inline-block;
            font-size: .78rem;
            padding: .45rem .7rem;
        }

        .specification-editor .quick-preview-options {
            gap: .3rem;
        }

        .specification-editor .quick-preview-options span {
            font-size: .68rem;
            padding: .25rem .45rem;
        }

        .specification-editor .merchant-preview-condition {
            color: #587798;
            display: block;
            font-size: .72rem;
            margin: -.25rem 0 .65rem;
        }

        .specification-editor .merchant-preview-guidance,
        .specification-editor .merchant-preview-unit {
            color: var(--editor-muted);
            display: block;
            font-size: .72rem;
            margin: -.25rem 0 .5rem;
        }

        .specification-editor .merchant-preview-unit {
            margin: .35rem 0 0;
        }

        .specification-editor .preview-product {
            border-bottom: 1px solid #e8edf3;
            margin-bottom: .8rem;
            padding-bottom: .8rem;
        }

        .specification-editor .preview-placeholder {
            grid-column:1 / -1;
            color: var(--editor-muted);
            font-size: .76rem;
            padding: 1.5rem .5rem;
            text-align: center;
        }

        @media (max-width: 767.98px) {
            .specification-editor .merchant-preview-field { grid-column:1 / -1; }
            .specification-editor .editor-header {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 1rem;
            }

            .specification-editor .editor-tabs {
                overflow-x: auto;
            }

            .specification-editor .editor-tab {
                white-space: nowrap;
            }

            .specification-editor .field-list-row,
            .specification-editor .field-modal-footer {
                align-items: flex-start;
                flex-direction: column;
            }

            .specification-editor .field-list-actions {
                align-self: flex-end;
            }

            .specification-editor .merchant-preview-backdrop {
                padding: .5rem;
            }

            .specification-editor .merchant-preview-modal {
                max-height: 94vh;
            }

            .specification-editor .merchant-preview-body {
                max-height: calc(94vh - 74px);
                padding: 1rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .specification-editor .field-modal-backdrop--from-left .field-modal,
            .specification-editor .field-modal-backdrop--from-right .field-modal {
                animation-duration: .01ms;
            }
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('assets/vendor/libs/dragula/dragula.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/specification-field-sort.js') }}"></script>
    <script>
        (function () {
            const selector = '.specification-editor [data-specification-choice-preview]';
            function placeOptions(menu) {
                const options = menu.querySelector('.editor-multiselect-options');
                const body = menu.closest('.merchant-preview-body');
                const bounds = body ? body.getBoundingClientRect() : { top: 0, bottom: window.innerHeight };
                const field = menu.getBoundingClientRect();
                const below = bounds.bottom - field.bottom - 12;
                const above = field.top - bounds.top - 12;
                const openAbove = below < Math.min(260, options.scrollHeight) && above > below;
                menu.classList.toggle('editor-multiselect--above', openAbove);
                options.style.maxHeight = Math.max(40, Math.min(260, openAbove ? above : below)) + 'px';
            }
            document.addEventListener('change', function (event) {
                const menu = event.target.closest(selector);
                if (!menu || event.target.type !== 'checkbox') return;
                const summary = menu.querySelector('[data-preview-choice-label]');
                const values = Array.from(menu.querySelectorAll('input:checked'), input => input.value);
                summary.textContent = values.length ? values.join(', ') : summary.dataset.placeholder;
            });
            document.addEventListener('toggle', function (event) {
                if (event.target.matches(selector) && event.target.open) placeOptions(event.target);
            }, true);
            document.addEventListener('click', function (event) {
                document.querySelectorAll(selector + '[open]').forEach(function (menu) {
                    if (!menu.contains(event.target)) menu.removeAttribute('open');
                });
            });
            document.addEventListener('keydown', function (event) {
                if (event.key !== 'Escape') return;
                document.querySelectorAll(selector + '[open]').forEach(function (menu) {
                    menu.removeAttribute('open');
                    menu.querySelector('summary').focus();
                });
            });
        })();
    </script>
@endpush
