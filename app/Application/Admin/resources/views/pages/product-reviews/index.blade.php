@extends('admin::layout.layout')

@section('title', __('admin::lang.Product Reviews'))

@section('content')
<div class="main-container container-fluid">
        <div class="inner-body">
            <div class="page-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin:home') }}">{{ __('admin::lang.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('admin::lang.Product Reviews') }}</li>
                </ol>
            </div>

            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <h4 class="mb-4">{{ __('admin::lang.Product Reviews') }}</h4>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin::lang.Product') }}</th>
                                            <th>{{ __('admin::lang.Customer') }}</th>
                                            <th>{{ __('admin::lang.Rating') }}</th>
                                            <th>{{ __('admin::lang.Review') }}</th>
                                            <th>{{ __('admin::lang.Review Status') }}</th>
                                            <th>{{ __('admin::lang.Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reviews as $review)
                                            <tr>
                                                <td>{{ $review->item?->title }}</td>
                                                <td>{{ $review->user?->name }}</td>
                                                <td>{{ $review->rating }} / 5</td>
                                                <td>
                                                    <strong>{{ $review->title }}</strong>
                                                    <p class="mb-0">{{ $review->comment }}</p>
                                                </td>
                                                <td>{{ __('admin::lang.Product Review Status ' . $review->status) }}</td>
                                                <td class="text-nowrap">
                                                    @if ($review->status === 'pending')
                                                        <form method="POST" action="{{ route('admin:product-reviews.approve', $review) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                {{ __('admin::lang.Approve Product Review') }}
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin:product-reviews.reject', $review) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                {{ __('admin::lang.Reject Product Review') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">{{ __('admin::lang.No Product Reviews') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
