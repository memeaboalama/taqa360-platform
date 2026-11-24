@push('css')
    <style>
        #merchant-order-show {
            --order-ink: #203247;
            --order-muted: #66778b;
            --order-border: #e2e9f1;
            --order-blue: #2f6fad;
            --order-soft-blue: #eaf2fc;
            color: var(--order-ink);
        }

        #merchant-order-show .od-page-title,
        #merchant-order-show .od-title-row,
        #merchant-order-show .od-page-actions,
        #merchant-order-show .od-card-head,
        #merchant-order-show .od-row,
        #merchant-order-show .od-amount-row,
        #merchant-order-show .od-detail-row {
            display: flex;
            align-items: center;
        }

        #merchant-order-show .od-page-title,
        #merchant-order-show .od-card-head,
        #merchant-order-show .od-row,
        #merchant-order-show .od-amount-row {
            justify-content: space-between;
        }

        #merchant-order-show .od-page-title {
            gap: 20px;
            margin-bottom: 20px;
        }

        #merchant-order-show .od-title-content {
            min-width: 0;
        }

        #merchant-order-show .od-eyebrow,
        #merchant-order-show .od-muted,
        #merchant-order-show .od-subtitle,
        #merchant-order-show .od-detail-label,
        #merchant-order-show .od-helper {
            color: var(--order-muted);
        }

        #merchant-order-show .od-eyebrow {
            font-size: 12px;
            font-weight: 600;
        }

        #merchant-order-show .od-title-row {
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 3px;
        }

        #merchant-order-show .od-title-row h1 {
            margin: 0;
            color: var(--order-ink);
            font-size: 1.6rem;
            font-weight: 800;
        }

        #merchant-order-show .od-subtitle {
            margin: 7px 0 0;
            font-size: 13px;
        }

        #merchant-order-show .od-page-actions {
            gap: 8px;
            flex-shrink: 0;
        }

        #merchant-order-show .od-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 38px;
            padding: 8px 13px;
            border: 1px solid var(--order-border);
            border-radius: 8px;
            background: #fff;
            color: var(--order-ink);
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background-color .15s ease, border-color .15s ease, color .15s ease;
        }

        #merchant-order-show .od-btn:hover {
            border-color: #c9d8e8;
            background: #f7faff;
            color: var(--order-blue);
        }

        #merchant-order-show .od-btn-primary {
            border-color: var(--order-blue);
            background: var(--order-blue);
            color: #fff;
        }

        #merchant-order-show .od-btn-primary:hover {
            border-color: #275e93;
            background: #275e93;
            color: #fff;
        }

        #merchant-order-show .od-btn-danger {
            border-color: #f3d9d9;
            background: #fff8f8;
            color: #b94747;
        }

        #merchant-order-show .od-badge {
            display: inline-flex;
            align-items: center;
            min-height: 27px;
            padding: 4px 9px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        #merchant-order-show .od-badge-blue {
            background: var(--order-soft-blue);
            color: #2d679d;
        }

        #merchant-order-show .od-badge-green {
            background: #e9f6ef;
            color: #28774e;
        }

        #merchant-order-show .od-badge-amber {
            background: #fff4de;
            color: #92600c;
        }

        #merchant-order-show .od-badge-red {
            background: #fff0ef;
            color: #ad453e;
        }

        #merchant-order-show .od-badge-gray {
            background: #f0f3f6;
            color: #627285;
        }

        #merchant-order-show .od-overview,
        #merchant-order-show .od-card {
            border: 1px solid var(--order-border);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 3px 14px rgba(23, 53, 78, .025);
        }

        #merchant-order-show .od-overview {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 20px;
            padding: 17px 20px;
        }

        #merchant-order-show .od-overview-content {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        #merchant-order-show .od-overview-icon,
        #merchant-order-show .od-avatar,
        #merchant-order-show .od-product-image,
        #merchant-order-show .od-icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        #merchant-order-show .od-overview-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--order-soft-blue);
            color: var(--order-blue);
            font-size: 21px;
        }

        #merchant-order-show .od-overview h2,
        #merchant-order-show .od-card-head h2 {
            margin: 0;
            color: var(--order-ink);
            font-size: 15px;
            font-weight: 800;
        }

        #merchant-order-show .od-overview p {
            margin: 4px 0 0;
            color: var(--order-muted);
            font-size: 13px;
        }

        #merchant-order-show .od-content-grid {
            display: grid;
            grid-template-columns: minmax(0, 2.25fr) minmax(280px, 1fr);
            gap: 20px;
            align-items: start;
        }

        #merchant-order-show .od-main-col,
        #merchant-order-show .od-side-col {
            display: flex;
            min-width: 0;
            flex-direction: column;
            gap: 20px;
        }

        #merchant-order-show .od-card {
            overflow: hidden;
        }

        #merchant-order-show .od-card-head {
            min-height: 56px;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--order-border);
        }

        #merchant-order-show .od-card-head h2 {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #merchant-order-show .od-card-body {
            padding: 18px 20px;
        }

        #merchant-order-show .od-head-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 6px;
            border-radius: 6px;
            background: #f0f3f7;
            color: #65768a;
            font-size: 12px;
        }

        #merchant-order-show .od-store-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--order-muted);
            font-size: 12px;
        }

        #merchant-order-show .od-products-wrap {
            overflow-x: auto;
        }

        #merchant-order-show .od-products {
            width: 100%;
            border-collapse: collapse;
            color: var(--order-ink);
            font-size: 13px;
        }

        #merchant-order-show .od-products th {
            padding: 13px 15px;
            border-bottom: 1px solid var(--order-border);
            background: #fbfcfe;
            color: var(--order-muted);
            font-size: 12px;
            font-weight: 600;
            text-align: start;
            white-space: nowrap;
        }

        #merchant-order-show .od-products td {
            padding: 17px 15px;
            border-bottom: 1px solid #edf1f5;
            vertical-align: middle;
        }

        #merchant-order-show .od-products tbody tr:last-child td {
            border-bottom: 0;
        }

        #merchant-order-show .od-product-main {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 230px;
        }

        #merchant-order-show .od-product-image {
            width: 54px;
            height: 54px;
            overflow: hidden;
            border: 1px solid var(--order-border);
            border-radius: 9px;
            background: #f7f9fc;
            color: #8a9bad;
            font-size: 22px;
        }

        #merchant-order-show .od-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #merchant-order-show .od-product-name {
            color: var(--order-ink);
            font-weight: 700;
        }

        #merchant-order-show .od-product-id,
        #merchant-order-show .od-product-option,
        #merchant-order-show .od-addon {
            display: block;
            margin-top: 5px;
            color: var(--order-muted);
            font-size: 11px;
        }

        #merchant-order-show .od-addon {
            color: #3b7aa8;
        }

        #merchant-order-show .od-products-foot {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 12px 18px;
            border-top: 1px solid var(--order-border);
            background: #fbfcfe;
            color: var(--order-muted);
            font-size: 11px;
        }

        #merchant-order-show .od-client-note {
            padding: 14px 18px;
            border-top: 1px solid var(--order-border);
            background: #fffdfa;
            color: var(--order-ink);
            font-size: 13px;
        }

        #merchant-order-show .od-client-note strong,
        #merchant-order-show .od-detail-label {
            display: block;
            margin-bottom: 5px;
            font-size: 11px;
            font-weight: 600;
        }

        #merchant-order-show .od-timeline {
            display: grid;
            grid-template-columns: repeat(4, minmax(100px, 1fr));
            margin: 4px 0 24px;
        }

        #merchant-order-show .od-step {
            position: relative;
            text-align: center;
        }

        #merchant-order-show .od-step:not(:last-child)::after {
            position: absolute;
            z-index: 0;
            top: 16px;
            inset-inline-start: 50%;
            width: 100%;
            height: 2px;
            background: #e5ebf1;
            content: "";
        }

        #merchant-order-show .od-step.is-done:not(:last-child)::after {
            background: #9dc0e0;
        }

        #merchant-order-show .od-step-dot {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 33px;
            height: 33px;
            border: 2px solid #e0e7ee;
            border-radius: 50%;
            background: #fff;
            color: #8190a0;
            font-size: 12px;
        }

        #merchant-order-show .od-step.is-done .od-step-dot,
        #merchant-order-show .od-step.is-current .od-step-dot {
            border-color: var(--order-blue);
            background: var(--order-blue);
            color: #fff;
        }

        #merchant-order-show .od-step.is-current .od-step-dot {
            box-shadow: 0 0 0 4px var(--order-soft-blue);
        }

        #merchant-order-show .od-step-label {
            margin-top: 8px;
            color: var(--order-muted);
            font-size: 11px;
        }

        #merchant-order-show .od-step.is-current .od-step-label {
            color: var(--order-ink);
            font-weight: 700;
        }

        #merchant-order-show .od-step-time {
            margin-top: 4px;
            color: #8b98a8;
            font-size: 10px;
        }

        #merchant-order-show .od-map-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 15px;
            border: 1px dashed #cbd9e7;
            border-radius: 9px;
            background: #f8fbfe;
            color: var(--order-muted);
            font-size: 12px;
        }

        #merchant-order-show .od-map-frame {
            height: 205px;
            overflow: hidden;
            border: 1px solid var(--order-border);
            border-radius: 9px;
            background: #f2f4f7;
        }

        #merchant-order-show .od-map-frame iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        #merchant-order-show .od-map-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 150px;
            padding: 18px;
            border-radius: 9px;
            background: #f2f4f7;
            color: var(--order-muted);
            font-size: 12px;
            text-align: center;
        }

        #merchant-order-show .od-map-link a,
        #merchant-order-show .od-link {
            color: var(--order-blue);
            font-weight: 700;
            text-decoration: none;
        }

        #merchant-order-show .od-amount-row {
            gap: 14px;
            padding: 11px 0;
            border-bottom: 1px solid #edf1f5;
            font-size: 13px;
        }

        #merchant-order-show .od-amount-row:last-child {
            border-bottom: 0;
        }

        #merchant-order-show .od-amount-total {
            margin-top: 10px;
            padding-top: 17px;
            border-top: 1px solid var(--order-border);
            font-size: 14px;
            font-weight: 800;
        }

        #merchant-order-show .od-total-value {
            color: var(--order-ink);
            font-size: 21px;
        }

        #merchant-order-show .od-tax-caption,
        #merchant-order-show .od-helper {
            margin: 7px 0 0;
            font-size: 11px;
        }

        #merchant-order-show .od-client-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        #merchant-order-show .od-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--order-soft-blue);
            color: var(--order-blue);
            font-size: 16px;
            font-weight: 800;
        }

        #merchant-order-show .od-client-name {
            color: var(--order-ink);
            font-weight: 700;
        }

        #merchant-order-show .od-detail-row {
            gap: 9px;
            margin-top: 10px;
            color: var(--order-ink);
            font-size: 12px;
            overflow-wrap: anywhere;
        }

        #merchant-order-show .od-detail-row i,
        #merchant-order-show .od-icon-wrap {
            color: #8092a5;
        }

        #merchant-order-show .od-divider {
            height: 1px;
            margin: 15px 0;
            background: var(--order-border);
        }

        #merchant-order-show .od-address {
            margin: 6px 0 0;
            color: var(--order-ink);
            font-size: 12px;
            line-height: 1.8;
        }

        #merchant-order-show .od-driver-line {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #merchant-order-show .od-driver-assign {
            margin-top: 15px;
            padding-top: 14px;
            border-top: 1px solid var(--order-border);
        }

        #merchant-order-show .od-driver-assign-label {
            display: block;
            margin-bottom: 8px;
            color: var(--order-ink);
            font-size: 12px;
            font-weight: 700;
        }

        #merchant-order-show .od-driver-assign-row {
            display: flex;
            align-items: stretch;
            gap: 8px;
        }

        #merchant-order-show .od-driver-select {
            min-width: 0;
            flex: 1;
            font-size: 12px;
        }

        #merchant-order-show .od-icon-wrap {
            width: 37px;
            height: 37px;
            border-radius: 10px;
            background: #f2f6fa;
            font-size: 17px;
        }

        #merchant-order-show .od-history-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        #merchant-order-show .od-history-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        #merchant-order-show .od-history-dot {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 27px;
            height: 27px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #f0f5fa;
            color: var(--order-blue);
        }

        #merchant-order-show .od-history-title {
            color: var(--order-ink);
            font-size: 12px;
            font-weight: 700;
        }

        #merchant-order-show .od-history-meta,
        #merchant-order-show .od-history-reason {
            margin-top: 4px;
            color: var(--order-muted);
            font-size: 11px;
        }

        #merchant-order-show .od-empty {
            padding: 12px 0;
            color: var(--order-muted);
            font-size: 12px;
        }

        #merchant-order-show .od-section-intro {
            margin: 0 0 14px;
        }

        #merchant-order-show .od-settlement-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        #merchant-order-show .od-settlement-summary > div {
            display: flex;
            min-height: 72px;
            flex-direction: column;
            justify-content: space-between;
            gap: 9px;
            padding: 12px;
            border: 1px solid var(--order-border);
            border-radius: 9px;
            background: #f7f9fc;
        }

        #merchant-order-show .od-settlement-summary span,
        #merchant-order-show .od-settlement-details span {
            color: var(--order-muted);
            font-size: 11px;
        }

        #merchant-order-show .od-settlement-summary strong {
            color: var(--order-ink);
            font-size: 14px;
        }

        #merchant-order-show .od-settlement-details {
            display: grid;
            gap: 10px;
            margin-top: 15px;
            padding-top: 14px;
            border-top: 1px solid var(--order-border);
        }

        #merchant-order-show .od-settlement-details > div {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
        }

        #merchant-order-show .od-settlement-details strong {
            text-align: end;
        }

        #merchant-order-show .od-settlement-link,
        #merchant-order-show .od-address-link {
            display: inline-flex;
            margin-top: 13px;
            font-size: 12px;
        }

        #merchant-order-show .od-notice {
            margin-top: 14px;
            padding: 11px 13px;
            border: 1px solid #d5e5f4;
            border-radius: 8px;
            background: #f2f7fc;
            color: #506d87;
            font-size: 12px;
        }

        #merchant-order-show .od-internal-note {
            margin-bottom: 15px;
            padding: 12px 14px;
            border: 1px solid var(--order-border);
            border-radius: 8px;
            background: #fbfcfe;
            color: var(--order-ink);
            font-size: 13px;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        #merchant-order-show .od-note-editor {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: start;
            gap: 10px;
        }

        #merchant-order-show .od-note-editor textarea {
            min-height: 68px;
            border-color: var(--order-border);
            font: inherit;
            font-size: 12px;
        }

        #merchant-order-show .od-payment-top,
        #merchant-order-show .od-payment-method,
        #merchant-order-show .od-payment-links,
        #merchant-order-show .od-payment-meta,
        #merchant-order-show .od-payment-movements-head {
            display: flex;
            align-items: center;
        }

        #merchant-order-show .od-payment-top,
        #merchant-order-show .od-payment-meta,
        #merchant-order-show .od-payment-movements-head {
            justify-content: space-between;
            gap: 12px;
        }

        #merchant-order-show .od-payment-links {
            gap: 14px;
            font-size: 12px;
        }

        #merchant-order-show .od-payment-top {
            margin-bottom: 18px;
        }

        #merchant-order-show .od-payment-method {
            gap: 11px;
        }

        #merchant-order-show .od-payment-method-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 1px solid #dbe7f3;
            border-radius: 9px;
            color: var(--order-blue);
            font-size: 18px;
        }

        #merchant-order-show .od-payment-method .od-detail-label {
            margin-bottom: 3px;
        }

        #merchant-order-show .od-payment-method strong {
            font-size: 13px;
        }

        #merchant-order-show .od-payment-numbers {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        #merchant-order-show .od-payment-numbers > div {
            display: flex;
            min-height: 76px;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
            padding: 12px;
            border: 1px solid #e6edf5;
            border-radius: 9px;
            background: #f7f9fc;
        }

        #merchant-order-show .od-payment-numbers span {
            color: var(--order-muted);
            font-size: 11px;
        }

        #merchant-order-show .od-payment-numbers strong {
            color: var(--order-ink);
            font-size: 13px;
        }

        #merchant-order-show .od-payment-numbers .is-collected strong {
            color: #159267;
        }

        #merchant-order-show .od-payment-numbers .is-balance {
            border-color: #d7e6f7;
            background: #f0f6fd;
        }

        #merchant-order-show .od-payment-meta {
            min-height: 49px;
            border-bottom: 1px solid var(--order-border);
            color: var(--order-muted);
            font-size: 12px;
        }

        #merchant-order-show .od-payment-numbers + .od-payment-meta {
            margin-top: 18px;
            border-top: 1px solid var(--order-border);
        }

        #merchant-order-show .od-payment-meta > span:first-child {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        #merchant-order-show .od-payment-meta strong {
            color: var(--order-ink);
            font-weight: 500;
        }

        #merchant-order-show .od-payment-actions {
            display: flex;
            justify-content: flex-start;
            margin-top: 15px;
        }

        #merchant-order-show .od-payment-actions button:disabled {
            color: #9aa7b4;
            cursor: not-allowed;
        }

        #merchant-order-show .od-payment-movements-head {
            min-height: 51px;
            padding: 0 20px;
            border-top: 1px solid var(--order-border);
            border-bottom: 1px solid var(--order-border);
            font-size: 13px;
        }

        #merchant-order-show .od-payment-table-wrap {
            overflow-x: auto;
        }

        #merchant-order-show .od-payment-table {
            width: 100%;
            border-collapse: collapse;
            color: var(--order-ink);
            font-size: 11px;
            white-space: nowrap;
        }

        #merchant-order-show .od-payment-table th,
        #merchant-order-show .od-payment-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #edf1f5;
            text-align: start;
        }

        #merchant-order-show .od-payment-table th {
            background: #fbfcfe;
            color: var(--order-muted);
            font-weight: 600;
        }

        #merchant-order-show .od-payment-table td small {
            display: block;
            margin-top: 3px;
            color: var(--order-muted);
        }

        #merchant-order-show .od-payment-table .is-collection {
            color: #159267;
            font-weight: 700;
        }

        #merchant-order-show .od-payment-table .is-refund {
            color: #ad453e;
            font-weight: 700;
        }

        #merchant-order-show .od-payment-empty {
            display: flex;
            min-height: 132px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 20px;
            background: #fbfcfe;
            color: #93a2b3;
            font-size: 12px;
            text-align: center;
        }

        #merchant-order-show .od-payment-empty i {
            margin-bottom: 3px;
            font-size: 21px;
        }

        #merchant-order-show .custom-card {
            border: 1px solid var(--order-border);
            border-radius: 14px;
            box-shadow: 0 3px 14px rgba(23, 53, 78, .025);
        }

        #merchant-order-show .custom-card .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--order-border);
            background: #fff;
        }

        #merchant-order-show .custom-card .card-body {
            padding: 18px 20px;
        }

        #merchant-order-show .custom-card .card-title {
            color: var(--order-ink);
            font-size: 15px;
            font-weight: 800;
        }

        #merchant-order-show .custom-card .table {
            margin-bottom: 0;
        }

        @media (max-width: 991.98px) {
            #merchant-order-show .od-content-grid {
                grid-template-columns: minmax(0, 1fr) minmax(250px, .75fr);
            }
        }

        @media (max-width: 767.98px) {
            #merchant-order-show .od-page-title,
            #merchant-order-show .od-overview {
                align-items: flex-start;
                flex-direction: column;
            }

            #merchant-order-show .od-page-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            #merchant-order-show .od-content-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            #merchant-order-show .od-side-col {
                order: -1;
            }
        }

        @media (max-width: 575.98px) {
            #merchant-order-show .od-card-head,
            #merchant-order-show .od-card-body {
                padding-right: 14px;
                padding-left: 14px;
            }

            #merchant-order-show .od-products th,
            #merchant-order-show .od-products td {
                padding: 11px 9px;
            }

            #merchant-order-show .od-timeline {
                grid-template-columns: repeat(4, minmax(70px, 1fr));
            }

            #merchant-order-show .od-settlement-summary {
                grid-template-columns: minmax(0, 1fr);
            }

            #merchant-order-show .od-payment-numbers {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            #merchant-order-show .od-step-label {
                font-size: 10px;
            }

            #merchant-order-show .od-driver-assign-row {
                flex-direction: column;
            }

            #merchant-order-show .od-note-editor {
                grid-template-columns: minmax(0, 1fr);
            }

            #merchant-order-show .od-note-editor .od-btn {
                width: 100%;
            }

            #merchant-order-show .od-payment-movements-head {
                padding-right: 14px;
                padding-left: 14px;
            }
        }

        @media print {
            #merchant-order-show .od-page-actions,
            #merchant-order-show .no-print {
                display: none !important;
            }
        }
    </style>
@endpush
