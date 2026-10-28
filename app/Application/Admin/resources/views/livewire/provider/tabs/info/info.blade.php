<div>
    <div class="mt-4">
        <form method="POST" wire:submit.prevent="updateBasicInfo">
            <div class="row gy-3">
                <div class="col-xl-6">
                    <label for="name" class="form-label text-default">{{__('admin::home.Full Name')}}</label>
                    <input type="text"
                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                           id="name"
                           disabled
                           wire:model.defer="name"
                           placeholder="name">
                    @error('name') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-6">
                    <label for="email" class="form-label text-default">{{__('admin::home.Email Address')}}</label>
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
                    <label for="phone" class="form-label text-default">{{__('admin::home.Phone Number')}}</label>
                    <input type="text"
                           disabled
                           class="form-control form-control-lg @error('phone') is-invalid @enderror"
                           id="phone"
                           wire:model.defer="phone"
                           placeholder="Phone Number">
                    @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>




                <div class="col-xl-4">
                    <label for="country_name" class="form-label text-default">{{__('admin::home.Country')}}</label>
                    <input type="text" id="country_name"
                           class="form-control form-control-lg @error('country_name') is-invalid @enderror" disabled
                           value="{{ $provider?->country?->title }}"
                           placeholder="country name">
                    {{--                    <select--}}
                    {{--                        wire:model.defer="country_id"--}}
                    {{--                        id="country_id"--}}
                    {{--                        class="form-control form-control-lg @error('country_id') is-invalid @enderror">--}}
                    {{--                        <option selected value="">---Select Country---</option>--}}
                    {{--                        @foreach($countries as $country)--}}
                    {{--                            <option value="{{ $country->id }}">{{ $country->title }}</option>--}}
                    {{--                        @endforeach--}}
                    {{--                    </select>--}}
                    @error('country_id') <span class="d-block text-danger">{{ $message }}</span>@enderror
                </div>

                <div class="col-xl-4">
                    <label for="status" class="form-label text-default">{{__('admin::home.status')}}</label>
                    <input type="text" id="status"
                           class="form-control form-control-lg" disabled
                           value="{{ $provider?->status }}"
                           placeholder="Status">
                </div>

                <div class="col-xl-4">
                    <label for="type" class="form-label text-default">{{__('admin::home.Last Login')}}</label>
                    <input type="text" id="type"
                           class="form-control form-control-lg" disabled
                           value="{{ \Carbon\Carbon::parse($provider?->last_logged_in_at)->format('j Y F g:i A') }}"
                           placeholder="Status">
                </div>

            </div>
{{--            <div class="row gy-3 mt-3">--}}
{{--                <div class="col-xl-12  text-end mt-4">--}}
{{--                    <button class="btn btn-lg btn-primary">Update Basic Info</button>--}}
{{--                </div>--}}
{{--            </div>--}}
        </form>
    </div>

</div>
