<div class="mt-0">
    <form method="POST" wire:submit.prevent="sendMessage">
        <div class="row gy-3">
            <div class="col-xl-4">
                <label for="name" class="form-label text-default">{{ __('marketplace::home.Name') }}</label>
                <input
                    type="text"
                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                    id="name"
                    wire:model.defer="name"
                    placeholder="{{ __('marketplace::home.Name') }}">
                @error('name') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="col-xl-4">
                <label for="email" class="form-label text-default">{{ __('marketplace::home.Email Address') }}</label>
                <input
                    type="text"
                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                    id="email"
                    wire:model.defer="email"
                    placeholder="{{ __('marketplace::home.Email Address') }}">
                @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="col-xl-4">
                <label for="phone" class="form-label text-default">{{ __('marketplace::home.Phone Number') }}</label>
                <input
                    type="text"
                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                    id="phone"
                    wire:model.defer="phone"
                    placeholder="{{ __('marketplace::home.Phone Number') }}">
                @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="row gy-3 mt-3">
            <div class="col-xl-12">
                <label for="message" class="form-label text-black">@lang('marketplace::home.message')</label>
                <textarea id="message" class="form-control text-black bg-gray-200" rows="7" placeholder="{{  __('marketplace::home.message') }}" wire:model.defer="message"></textarea>
                @error("message")
                <span class="text-danger d-block mt-2">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <label class="form-label mt-2 text-info">{{ __('marketplace::home.Contact us') }} : </label><br>
        <label class="form-label mt-2 text-info">{{ __('marketplace::home.Email') }} : <a href="mailto:{{config('mail.from.address')}}" style="color: #0a58ca">{{config('mail.from.address')}}</a> .</label><br>
        <label class="form-label mt-2 text-info">{{ __('marketplace::home.Phone') }} : <a href="tel:+966 9200 13363" style="color: #0a58ca" dir="ltr">+966 9200 13363</a></label>


        <div class="row gy-3 mt-3">
            <div class="col-xl-4 d-grid mt-4">
                <button class="btn btn-lg btn-primary">{{ __('marketplace::home.Save') }}</button>
            </div>
        </div>
    </form>
</div>
