<div>
    <div class="modal fade" id="createSpecification"  wire:ignore.self
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('admin::home.Create New Specification')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <form method="POST" wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                          <div class="col-xl-6">
                                                <label for="name" class="form-label text-black">{{__('admin::home.Name')}}<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error("name") is-invalid @enderror"
                                                       id="name" placeholder={{__('admin::home.Name')}} wire:model.defer="name" >
                                                @error("name") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="desc" class="form-label text-black">{{__('admin::home.Description')}}</label>
                                                <input type="text" class="form-control @error("description") is-invalid @enderror"
                                                       id="desc" placeholder={{__('admin::home.Description')}} wire:model.defer="description" >
                                                @error("description") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-12">
                                                <label for="category-active"
                                                       class="form-label text-black">{{__('admin::home.status')}}</label>
                                                <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                                                    <input id="category-active"
                                                           type="checkbox" {{ $status == 'active' ? 'checked' : '' }}>
                                                    <label for="category-active" class="label-success"
                                                           wire:click="updateStatus('{{ $status == 'active' ? 'inactive' : 'active' }}')"></label>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="namePre" class="form-label text-black">{{__('admin::home.Specification')}}<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control @error("namePre") is-invalid @enderror"
                                                           id="namePre" wire:model.defer="namePre" placeholder="{{__('admin::home.Specification')}}">
                                                    <a href="javascript:void(0);" class="btn btn-primary-light" title="{{__('admin::home.add')}}"
                                                       wire:click="createPer"><i class="bi bi-plus-lg"></i></a>
                                                </div>
                                                @error("namePre") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-12">


<table class="table">
    <thead>
    <th>@lang('admin::home.Specification') <span class="text-danger">*</span></th>
    <th>@lang('admin::home.status')</th>
    <th>@lang('admin::home.actions')</th>

    </thead>

    <tbody>
    @foreach($specifications_properties as $index =>$specification_propertiesL)
        <tr>
            <td>
            <input wire:model.defer="specifications_properties.{{$index}}.name" class="form-control" />
                            </td>
          <td>
           <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
            <input id="{{'active'.$index}}"
                       type="checkbox" {{ $specifications_properties[$index]['status'] == "active"  ? 'checked' : '' }}>
                <label for="{{'active'.$index}}" class="label-success"
                wire:click="updateStatusRow('{{ $index }}')" wire:ignore.self></label>
            </div>
                    </td>
<td>
<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="{{__('admin::home.Delete')}}"
 wire:click="$emit('deletePer',{{ $index }})" ><i class="ri-delete-bin-line"></i></a>

</td>


        </tr>

    @endforeach


    </tbody>
</table>

                                                </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">@lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none" wire:target="store">
                                    @lang('admin::home.Save')
                                </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="store">
                                    @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('js')
    <script>
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form) return;
            let isLivewireForm = false;
            for (let i = 0; i < form.attributes.length; i++) {
                if (form.attributes[i].name.indexOf('wire:submit') === 0) {
                    isLivewireForm = true;
                    break;
                }
            }
            if (!isLivewireForm) return;
            const modal = form.closest('#createSpecification');
            if (modal) {
                modal.querySelectorAll('button').forEach(function (btn) {
                    if (!btn.disabled) {
                        btn.setAttribute('data-op-disabled', '1');
                        btn.disabled = true;
                    }
                });
            }
        });
        window.addEventListener('livewire:update', function () {
            document.querySelectorAll('#createSpecification button[data-op-disabled]').forEach(function (btn) {
                const modal = btn.closest('#createSpecification');
                const spinner = modal ? modal.querySelector('.spinner-border') : null;
                const spinnerVisible = spinner && spinner.offsetParent !== null;
                if (!spinnerVisible) {
                    btn.disabled = false;
                    btn.removeAttribute('data-op-disabled');
                }
            });
        });
    </script>
@endpush
