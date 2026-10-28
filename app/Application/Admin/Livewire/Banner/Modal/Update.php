<?php

namespace Application\Admin\Livewire\Banner\Modal;

use App\Domain\Admin\Actions\Dashboard\Banner\FetchSingleBannerAction;
use App\Domain\Admin\Actions\Dashboard\Banner\UpdateBannerAction;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class Update extends Component
{
    use LivewireAlert, WithFileUploads;

    public string $status = 'active';
    public int|null $banner_id;
    public $image, $image_path;

    protected $listeners = ['updateBanner'];

    public function updateBanner($id, FetchSingleBannerAction $fetchSingleBannerAction): void
    {

        $banner = $fetchSingleBannerAction->handle($id);
        if ($banner) {
            $this->status = $banner->status->value;
            $this->banner_id = $banner->id;
            $this->image_path = $banner->image_path;
            $this->emit('openModal', '#updateBanner');
        }
    }

    protected function rules(): array
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|string|in:active,inactive',
        ];
    }

    public function updateStatus($val): void
    {
        $this->status = $val;
    }

    public function store(UpdateBannerAction $updateBannerAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateBannerAction->handle($this->banner_id, $data);
        $this->emit('closeModal', '#updateBanner');
        $this->emit('refreshDatatable');
        $this->alert('success', __('admin::lang.Banner Updated Successfully'));
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.banner.modal.update');
    }

}
