@extends('educator::layout.auth-layout')
@section('title', __('educator::auth.login_title'))

@section('content')
<div class="register-container">
    <div class="card space-y-5">

        <div class="text-center">
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
