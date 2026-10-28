<?php

namespace Application\Admin\Livewire\SalesAgent\Tabs;

use App\Domain\Admin\Actions\Dashboard\SalesAgent\FetchSingleSalesAgentAction;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Files extends Component
{
    public $salesAgentId;
    public $files = [];
    public $diskName = 'fileDisk';

    public function mount(int $id, FetchSingleSalesAgentAction $fetchSingleSalesAgentAction): void
    {
        $this->salesAgentId = $id;
        $salesAgent = $fetchSingleSalesAgentAction->handle($id);

        if (!$salesAgent) {
            abort(404);
        }

        $this->files = $salesAgent->files()->get()->map(function ($file) {
            $file->preview_url = $file->path
                ? Storage::disk($this->diskName)->url($file->path . '/' . $file->file_name)
                : null;
            return $file;
        });
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.salesagent.tabs.files');
    }
}