<div>
    <div class="modal fade" id="showOrderHistory"  wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">@lang('admin::home.Order History')</h6>
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
                                                @if($order_history)
                                                @foreach ($order_history as $history)
                                                <ul class="list-unstyled mb-0">
                                                    <li><div class="dropdown-item d-flex align-items-center gap-2 py-2">
                                                      <span class="d-inline-block bg-danger rounded-circle p-1"></span>
                                                      {{$history->created_at }}
                                                      {{__('admin::home.'.$history->action)}}
                                                    </div></li>
                                                  </ul>
                                                @endForeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="page-content page-container" id="page-content">
                                        <div class="padding">
                                            <div class="row d-flex">
                                    
                                    <div class="col-lg-12 grid-margin stretch-card">
                                                  <div class="card">
                                                    <div class="card-body">
                                                    
                                                      <div class="table-responsive">
                                                        <table class="table">
                                                          <thead>
                                                            <tr>
                                                              <th>Name</th>
                                                              <th>ID No.</th>
                                                              <th>Created On</th>
                                                              <th>Status</th>
                                                            </tr>
                                                          </thead>
                                                          <tbody>
                                                            <tr>
                                                              <td>Samso Park</td>
                                                              <td>34424433</td>
                                                              <td>12 May 2017</td>
                                                              <td><label class="badge badge-danger">Pending</label></td>
                                                            </tr>
                                                            <tr>
                                                              <td>Marlo Sanki</td>
                                                              <td>53425532</td>
                                                              <td>15 May 2015</td>
                                                              <td><label class="badge badge-warning">In progress</label></td>
                                                            </tr>
                                                            <tr>
                                                              <td>John ryte</td>
                                                              <td>53275533</td>
                                                              <td>14 May 2017</td>
                                                              <td><label class="badge badge-info">Fixed</label></td>
                                                            </tr>
                                                            <tr>
                                                              <td>Peter mark</td>
                                                              <td>53275534</td>
                                                              <td>16 May 2017</td>
                                                              <td><label class="badge badge-success">Completed</label></td>
                                                            </tr>
                                                            <tr>
                                                              <td>Dave</td>
                                                              <td>53275535</td>
                                                              <td>20 May 2017</td>
                                                              <td><label class="badge badge-warning">In progress</label></td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                
                                                </div>
                                                  </div>
                                                </div> --}}
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
