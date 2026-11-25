@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Specifications'))

@section('content')
    <div class="col-12 specification-library">
        @if(session('specificationCreated'))
            <div class="alert alert-success">{{ session('specificationCreated') }}</div>
        @endif

        <livewire:admin::specification.table.all/>

        @AdminHasRole(['edit_specifications'])
        <livewire:admin::specification.modal.update/>
        @endAdminHasRole

    </div>
@endsection

@push('css')
    <style>
        .specification-library {
            --spec-border: #dce5ef;
            --spec-muted: #8293a8;
            color: #23364b;
        }

        .specification-library .spec-page-heading {
            margin-bottom: 1.25rem;
        }

        .specification-library .spec-page-heading h2 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: .35rem;
        }

        .specification-library .spec-page-heading p {
            color: var(--spec-muted);
            font-size: .82rem;
            margin: 0;
        }

        .specification-library .spec-stat-card,
        .specification-library .spec-table-card {
            background: #fff;
            border: 1px solid var(--spec-border);
            border-radius: .7rem;
        }

        .specification-library .spec-stat-card {
            min-height: 78px;
            padding: 1rem 1.2rem;
        }

        .specification-library .spec-stat-value {
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .specification-library .spec-stat-label {
            color: var(--spec-muted);
            font-size: .75rem;
            margin-top: .3rem;
        }

        .specification-library .spec-stat-icon,
        .specification-library .spec-template-icon {
            align-items: center;
            background: #eef5fc;
            border-radius: .5rem;
            color: #3976ad;
            display: inline-flex;
            flex: 0 0 auto;
            height: 34px;
            justify-content: center;
            width: 34px;
        }

        .specification-library .spec-stat-icon.is-green {
            background: #eaf7f2;
            color: #3a9d7c;
        }

        .specification-library .spec-stat-icon.is-amber {
            background: #fff6e8;
            color: #b78431;
        }

        .specification-library .spec-table-card {
            overflow: hidden;
        }

        .specification-library .spec-toolbar {
            border-bottom: 1px solid var(--spec-border);
            padding: .8rem 1rem;
        }

        .specification-library .spec-toolbar .form-control,
        .specification-library .spec-toolbar .form-select {
            border-color: var(--spec-border);
            font-size: .78rem;
            min-height: 36px;
        }

        .specification-library .spec-table {
            margin-bottom: 0;
            min-width: 860px;
        }

        .specification-library .spec-table thead th {
            background: #f7f9fb;
            border-bottom: 1px solid var(--spec-border);
            color: var(--spec-muted);
            font-size: .72rem;
            font-weight: 600;
            padding: .75rem .85rem;
            white-space: nowrap;
        }

        .specification-library .spec-table tbody td {
            border-bottom: 1px solid #e8edf3;
            color: #62758b;
            font-size: .77rem;
            padding: .72rem .85rem;
            vertical-align: middle;
        }

        .specification-library .spec-template-name {
            color: #23364b;
            font-size: .8rem;
            font-weight: 700;
        }

        .specification-library .spec-template-description {
            color: var(--spec-muted);
            font-size: .68rem;
            margin-top: .15rem;
            max-width: 280px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .specification-library .spec-template-icon {
            border: 1px solid #e1eaf3;
            height: 30px;
            width: 30px;
        }

        .specification-library .spec-status {
            border-radius: .25rem;
            display: inline-block;
            font-size: .67rem;
            padding: .2rem .45rem;
        }

        .specification-library .spec-status.is-active {
            background: #eaf7f2;
            color: #339477;
        }

        .specification-library .spec-status.is-inactive,
        .specification-library .spec-status.is-draft,
        .specification-library .spec-status.is-rejected,
        .specification-library .spec-status.is-unknown {
            background: #fff6e8;
            color: #aa7a2c;
        }

        .specification-library .spec-action {
            background: transparent;
            border: 0;
            color: #8799ad;
            padding: .2rem;
        }

        .specification-library .spec-action:hover {
            color: #286da8;
        }

        .specification-library .spec-table-footer {
            align-items: center;
            border-top: 1px solid var(--spec-border);
            display: flex;
            justify-content: space-between;
            padding: .7rem 1rem;
        }

        .specification-library .spec-table-footer .pagination {
            margin: 0;
        }

        .specification-library .spec-table-footer .page-link {
            border-radius: .3rem;
            font-size: .72rem;
            margin-inline-start: .2rem;
            padding: .3rem .55rem;
        }

        .specification-library .spec-empty-state {
            color: var(--spec-muted);
            padding: 2rem 1rem !important;
            text-align: center;
        }

        @media (max-width: 767.98px) {
            .specification-library .spec-page-heading {
                align-items: flex-start !important;
                flex-direction: column;
                gap: .8rem;
            }

            .specification-library .spec-table-footer {
                align-items: flex-start;
                flex-direction: column;
                gap: .6rem;
            }
        }
    </style>
@endpush
