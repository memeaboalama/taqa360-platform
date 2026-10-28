<div>
    <div class="modal fade" id="showDriverHistory"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="false">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Actions Drivers To Day')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-body p-0">
                                        <div class="row gy-3"> 
                                            <div class="list-group">
                                                @forelse($driver_history ?? [] as $history)
                                                <ul class="list-unstyled mb-0">
                                                    <li><div class="dropdown-item d-flex align-items-center gap-2 py-2">
                                                      <span class="d-inline-block bg-danger rounded-circle p-1"></span>
                                                      {{$history->created_at }}
                                                      {{__('admin::home.'.$history->action)}}
                                                    </div></li>
                                                  </ul>
                                                @empty
                                                    <li class="text-center text-muted py-3">
                                                        {{ __('admin::home.No operations today') }}
                                                    </li>
                                                @endforelse
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
                    </div>
            </div>
        </div>
    </div>

</div>
