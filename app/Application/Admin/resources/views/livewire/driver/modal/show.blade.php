<div>
    <div class="modal fade" id="showRegDriver" wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('admin::home.Create new driver')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
<div class="row authentication mx-0" >

    @if($driver_id)
    <div class="col-xxl-6 col-xl-6 col-lg-6" >        

        <div class="row justify-content-center align-items-center" >
            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="p-5">
                <livewire:admin::driver.tabs.files-register :id="$driver_id"/>
                    
                   
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6 col-xl-6 col-lg-6 d-xl-block d-none px-0 bg-primary" >
    <livewire:admin::driver.tabs.register :id="$driver_id"/>
       
    </div>
    @endif

</div>
</div>
</div>
</div>
</div>