<span class="badge badge-{{ $status === \App\Support\Enum\StatusEnum::ACTIVE ? 'success' : 'secondary' }}">
    {{ ucfirst($status->value) }}
</span>
