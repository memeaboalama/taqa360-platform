<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    @lang('admin::home.additions_groups')
                </div>
                <div class="prism-toggle">
                    <button class="btn btn-primary-light m-1" data-bs-toggle="modal" data-bs-target="#creatAdditionGroup">@lang('admin::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <livewire:admin::merchant.tabs.groups.table.all :merchant_id="$merchantId"/>
                </div>
            </div>
            <livewire:admin::merchant.tabs.groups.modal.create :merchant_id="$merchantId"/>
            <livewire:admin::merchant.tabs.groups.modal.update :merchant_id="$merchantId"/>
            <livewire:admin::merchant.tabs.groups.modal.delete/>

        </div>
    </div>
</div>
