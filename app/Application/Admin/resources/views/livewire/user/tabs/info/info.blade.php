<div>
    <div class="container-fluid">
        <div class="row">
            <div class="mt-4 col-md-9">
                <form method="POST" wire:submit.prevent="updateBasicInfo">
                    <div class="row gy-3">
                        <div class="col-xl-6">
                            <label for="fullname" class="form-label text-default">Full Name</label>
                            <input type="text"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   id="fullname"
                                   wire:model.defer="name"
                                   placeholder="Full name">
                            @error('name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-xl-6">
                            <label for="email" class="form-label text-default">Email Address</label>
                            <input type="email"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   id="email"
                                   disabled
                                   wire:model.defer="email"
                                   placeholder="Email Address">
                            @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>

                    </div>
                    <div class="row gy-3 mt-3">
                        <div class="col-xl-4">
                            <label for="phone" class="form-label text-default">Phone Number</label>
                            <input type="text"
                                   class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                   id="phone"
                                   wire:model.defer="phone"
                                   placeholder="Phone Number">
                            @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    {{--            <div class="row gy-3 mt-3">--}}
                    {{--                <div class="col-xl-12  text-end mt-4">--}}
                    {{--                    <button class="btn btn-lg btn-primary">Update</button>--}}
                    {{--                </div>--}}
                    {{--            </div>--}}
                </form>
            </div>
            <div class="col-md-3">
                <div class="card custom-card" style="background: #f5f5f5">
                    <div class="card-header">
                        <div class="card-title">User Details</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="d-flex align-items-center border-bottom border-block-end-dashed p-3 flex-wrap">
                            <div class="me-2">
                                <span
                                    class="avatar avatar-lg avatar-rounded bg-primary">
                                {{ \Str::substr(ucfirst($user?->name),0,1) }}
                                </span>
                            </div>
                            <div class="flex-fill">
                                <p class="mb-0 fw-bold">{{ $user?->name }}</p>
                                <p class="mb-0 text-muted fs-12">{{ $user->phone }}</p>
                                <p class="mb-0 text-muted fs-12">{{ $user->email }}</p>
                            </div>
                            <div><span
                                    class="badge bg-info">{{  $user?->status }}</span>
                            </div>
                        </div>

                        {{--                        <div class="p-3 border-bottom border-block-end-dashed">--}}
                        {{--                            <div class="mb-3"><span class="fs-14 fw-semibold">Send updates to :</span></div>--}}
                        {{--                            <p class="mb-2 text-muted"><span class="fw-semibold text-default">Phone : </span>--}}
                        {{--                                (555)-0123-1210 </p>--}}
                        {{--                            <p class="mb-0 text-muted"><span class="fw-semibold text-default">Email : </span>--}}
                        {{--                                jsontaylor2134@gmail.com </p></div>--}}
                        <div class="p-3 border-bottom border-block-end-dashed">
                            <div class="mb-3"><span class="fs-14 fw-bold">Information</span></div>
                            <p class="mb-2 text-muted"><span
                                    class="fw-semibold text-default">Phone</span> {{ $user?->phone_number }}
                            </p>

{{--                            <p class="mb-2 text-muted"><span--}}
{{--                                    class="fw-semibold text-default">Country</span> {{ $user?->country->title }}--}}
{{--                            </p>--}}

                            @if($user?->last_logged_in_at)
                                <p class="mb-2 text-muted"><span
                                        class="fw-semibold text-default">Last Active</span> {{ \Carbon\Carbon::parse($user?->last_logged_in_at)->format('j Y F g:i A') }}
                                </p>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
