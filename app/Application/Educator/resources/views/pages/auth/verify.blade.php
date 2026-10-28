@extends('educator::layout.auth-layout')
@section('title', __('educator::auth.verify_title'))

@section('content')
<div class="register-container">
    <div class="card">
        <livewire:educator::auth.verify />
    </div>
</div>
@endsection
