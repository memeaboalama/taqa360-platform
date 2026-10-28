<div>
    <div class="modal fade" id="showElmErros"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Elm Eroors')</h6>
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
                                                @if($elm_errors)
                                                @foreach ($elm_errors as $error => $value)
                                                <ul class="list-unstyled mb-0">
                                                    <li><div class="dropdown-item d-flex align-items-center gap-2 py-2">
                                                      <span class="d-inline-block bg-danger rounded-circle p-1"></span>
                                                      {{ __('admin::elmErrors.'.$value )}}
                                                    </div></li>
                                                  </ul>
                                                @endForeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">Close
                    </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
