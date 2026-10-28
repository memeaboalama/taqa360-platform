@php
    use App\Support\Enum\BannerStatusEnum;
    $badgeClass = match($status) {
        BannerStatusEnum::ACTIVE => 'success',
        BannerStatusEnum::SCHEDULED => 'info',
        BannerStatusEnum::EXPIRED => 'danger',
        BannerStatusEnum::INACTIVE => 'warning',
        default => 'secondary'
    };
@endphp

<span class="badge badge-{{ $badgeClass }}">
    {{ $status->label() }}
</span>
