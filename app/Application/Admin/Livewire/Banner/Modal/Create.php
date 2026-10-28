<?php

namespace Application\Admin\Livewire\Banner\Modal;

use App\Domain\Admin\Actions\Dashboard\Banner\StoreBannerAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use LivewireAlert;
    use WithFileUploads;

    public string $status = 'active';
    public $image;

    protected function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(StoreBannerAction $storeBannerAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $storeBannerAction->handle($data);
        $this->emit('refreshDatatable');
        $this->emit('closeModal', '#createBanner');
        $this->alert('success', __('admin::lang.Banner Created Successfully'));
        $this->reset('status', 'image');
        DB::commit();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.banner.modal.create');
    }
}
