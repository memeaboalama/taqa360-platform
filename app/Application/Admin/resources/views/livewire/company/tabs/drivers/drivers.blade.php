<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="row mb-0">
                    <livewire:admin::driver.table.filter/>
                </div>
                @AdminHasRole(['create_drivers'])
                <div class="prism-toggle">
                    <button
                        class="btn btn-primary-light m-1"
                        data-bs-toggle="modal"
                        data-bs-target="#createDriver">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                </div>
                @endAdminHasRole
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <livewire:admin::company.tabs.drivers.table.all :companyId="$companyId"/>
                </div>
            </div>

            @AdminHasRole(['create_drivers'])
            <livewire:admin::company.tabs.drivers.modal.create :companyId="$companyId"/>
            @endAdminHasRole

            @AdminHasRole(['delete_drivers'])
            <livewire:admin::driver.modal.delete/>
            @endAdminHasRole
            @AdminHasRole(['edit_drivers'])
            <livewire:admin::driver.modal.reset-password/>
            @endAdminHasRole
            @AdminHasRole(['approve_drivers'])
            <livewire:admin::driver.modal.approve/>
            @endAdminHasRole
            @AdminHasRole(['approve_drivers'])
            <livewire:admin::driver.modal.elmerrors/>
            @endAdminHasRole
        </div>
    </div>
</div>
