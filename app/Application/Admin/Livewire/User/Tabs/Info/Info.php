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

    public mixed $user_name, $email, $phone;
    public $user;


    public function mount(int $user_id, UserRepository $userRepository): void
    {
        $this->user = $userRepository->findByCols(['id' => $user_id]);
        if ($this->user){
            $this->email = $this->user?->email;
            $this->phone = $this->user?->phone;
            $this->name = $this->user?->name;
        }

    }


    protected function rules(): array
    {
        return [
            'email' => 'required|email|unique:merchants,email,' . $this->user->id,
            'phone' => 'required|unique:merchants,phone,' . $this->user->id,
            'name' => 'required|string|min:6',
        ];
    }

    public function updateBasicInfo(UpdateBasicInfoAction $updateBasicInfoAction): void
    {
        $data = $this->validate();
        DB::beginTransaction();
        $updateBasicInfoAction->handle($this->user->id, $data);
        $this->alert('success', 'Basic Info Updated Successfully');
        DB::commit();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin::livewire.user.tabs.info.info');
    }
}
