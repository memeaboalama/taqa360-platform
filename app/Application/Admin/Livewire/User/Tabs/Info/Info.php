<?php

namespace Application\Admin\Livewire\User\Tabs\Info;

use App\Domain\Admin\Actions\Dashboard\User\UpdateBasicInfoAction;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Info extends Component
{

    use LivewireAlert;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public $user;


    public function mount(int $user_id, UserRepository $userRepository): void
    {
        $this->user = $userRepository->findByCols(['id' => $user_id]);
        abort_if(!$this->user, 404);

        $this->email = $this->user->email ?? '';
        $this->phone = $this->user->phone ?? '';
        $this->name = $this->user->name ?? '';
    }


    protected function rules(): array
    {
        return [
            'phone' => 'required|string|unique:users,phone,' . $this->user->id,
            'name' => 'required|string|min:6',
        ];
    }

    public function updateBasicInfo(UpdateBasicInfoAction $updateBasicInfoAction): void
    {
        $data = $this->validate();
        DB::transaction(fn () => $updateBasicInfoAction->handle($this->user->id, $data));
        $this->alert('success', 'Basic Info Updated Successfully');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.user.tabs.info.info');
    }
}
