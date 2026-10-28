<?php

namespace Application\Admin\Livewire\Driver\Modal;

use App\Domain\Admin\Actions\Dashboard\Driver\DestroyDriverAction;
use Domain\Driver\Models\Driver;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Elmerrors extends Component
{
    use LivewireAlert;

    public int|null $element_id = null;
    public array|null $elm_errors = [];
    protected $listeners = ['showElmErros'];

    public function showElmErros($id): void
    {
        $this->elm_errors = Driver::where('id', $id)->value('elm_errors');
        $this->elm_errors ? $this->emit('openModal', '#showElmErros'): null;
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.driver.modal.elmerrors');
    }
}
