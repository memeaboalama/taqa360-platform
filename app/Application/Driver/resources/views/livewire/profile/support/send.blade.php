<div class="mt-0">
    <form method="POST" wire:submit.prevent="sendMessage">
        <div class="row gy-3">
            <div class="col-xl-4">
                <label for="name" class="form-label text-default">{{ __('driver::home.Name') }}</label>
                <input
                    type="text"
                    class="form-control form-control-lg @error('name') is-invalid @enderror"
                    id="name"
                    wire:model.defer="name"
                    placeholder="{{ __('driver::home.Name') }}">
                @error('name') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
            <div class="col-xl-4">
                <label for="email" class="form-label text-default">{{ __('driver::home.Email Address') }}</label>
                <input
                    type="text"
                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                    id="email"
                    wire:model.defer="email"
                    placeholder="{{ __('driver::home.Email Address') }}">
                @error('email') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="col-xl-4">
                <label for="phone" class="form-label text-default">{{ __('driver::home.Phone Number') }}</label>
                <input
                    type="text"
                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                    id="phone"
                    wire:model.defer="phone"
                    placeholder="{{ __('driver::home.Phone Number') }}">
                @error('phone') <span class="d-block text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="row gy-3 mt-3">
            <div class="col-xl-12">
                <label for="message" class="form-label text-black">@lang('driver::home.message')</label>
                <textarea id="message" class="form-control text-black bg-gray-200" rows="7" placeholder="{{  __('driver::home.message') }}" wire:model.defer="message"></textarea>
                @error("message")
                <span class="text-danger d-block mt-2">{{ $message }}</span>
                @enderror
            </div>
        </div>

        @if(filled($supportEmail) || filled($supportPhone))
            <label class="form-label mt-2 text-info">{{ __('driver::home.Contact us') }} :</label><br>
            @if(filled($supportEmail))
                <label class="form-label mt-2 text-info">
                    {{ __('driver::home.Email') }} :
                    <a href="mailto:{{ $supportEmail }}" style="color: #0a58ca">{{ $supportEmail }}</a> .
                </label><br>
            @endif
            @if(filled($supportPhone))
                <label class="form-label mt-2 text-info">
                    {{ __('driver::home.Phone') }} :
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $supportPhone) }}" style="color: #0a58ca" dir="ltr">{{ $supportPhone }}</a>
                </label>
            @endif
        @endif


        <div class="row gy-3 mt-3">
            <div class="col-xl-4 d-grid mt-4">
                <button class="btn btn-lg btn-primary">{{ __('driver::home.Save') }}</button>
            </div>
        </div>
    </form>
</div>
