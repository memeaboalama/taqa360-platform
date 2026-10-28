<?php

namespace Application\Admin\Livewire\AdsContent\Modal;

use App\Domain\AppBase\Actions\AdProductContent\DeleteProductContentAction;
use App\Domain\AppBase\Models\AdProductContent;
use App\Domain\AppBase\Repositories\AdProductContentRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DeleteProductContent extends Component
{
    use LivewireAlert;

    public $productContentId;
    public $productContent;

    protected $listeners = ['openDeleteProductContentModal'];

    public function openDeleteProductContentModal($productContentId)
    {
        $repo = new AdProductContentRepository(new AdProductContent());
        $this->productContent = $repo->findById($productContentId);
        
        if (!$this->productContent) {
            $this->alert('error', 'Product content not found');
            return;
        }

        $this->productContentId = $productContentId;
        $this->emit('openModal', '#deleteProductContentModal');
    }

    public function delete(DeleteProductContentAction $action)
    {
        try {
            $placement = $this->productContent->placement;
            
            $action->handle($this->productContentId);

            if ($placement) {
                Cache::forget("ecommerce_sections_{$placement->page_id}");
            }

            $this->emit('product-content-deleted');
            $this->emit('closeModal', '#deleteProductContentModal');
            $this->alert('success', __('admin::lang.Deleted Successfully'));
        } catch (\Exception $e) {
            Log::error('Error deleting product content: ' . $e->getMessage());
            $this->alert('error', __('admin::lang.Error occurred'));
        }
    }

    public function render()
    {
        return view('admin::livewire.ads-content.modal.delete-product-content');
    }
}
