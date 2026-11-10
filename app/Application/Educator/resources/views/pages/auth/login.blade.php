@extends('educator::layout.auth-layout')
@section('title', __('educator::auth.login_title'))

@section('content')
<div class="register-container">
    <div class="card space-y-5">

        <div class="text-center">
            <img src="{{ asset($platformBrand['logo']) }}"
                alt="{{ $platformBrand['name'][app()->getLocale()] ?? $platformBrand['name']['en'] }}"
                class="mx-auto mb-4" style="max-height: 64px; max-width: 180px;">
            <h2 class="text-3xl font-bold">{{ __('educator::auth.login_title') }}</h2>
            <p class="text-gray-600 mt-2">{{ __('educator::auth.join_us') }}</p>
        </div>

        <livewire:educator::auth.login />

        <div class="mt-6 text-center">
            <span class="text-sm text-gray-600">{{ __('educator::auth.dont_have_account') }}</span>
            <a href="{{ route('educator:register') }}" class="font-medium">
                {{ __('educator::auth.register') }}
            </a>
        </div>
    </div>
</div>
@endsection
