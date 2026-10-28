<?php

namespace Application\Marketplace\Livewire\Auth;

use App\Domain\AppBase\Models\Location\Country;
use App\Domain\MarketPlace\Actions\Dashboard\Auth\RegisterAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Mail\Marketplace\ConfirmMail;
use App\Providers\RouteServiceProvider;
use App\Support\Enum\OTPTypes;
use App\Support\Rules\IsValidPhone;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Register extends Component
{
    use LivewireAlert;

    public string $user_name, $email, $password, $password_confirmation, $phone, $company_name;
    public bool $terms = false;
    public int $country_id;
    public $countries;

    public function nickNames()
    {
        return [
            'company_name' => trans('marketplace::home.company_name'),
            'user_name' => trans('marketplace::home.Full Name'),
        ];
    }
    protected function rules(): array
    {
        return [
            'email' => 'required|email|unique:merchants,email|min:3',
            'password' => 'required|string|confirmed|min:4',
            'phone' => ['required','numeric','unique:merchants,phone','digits_between:9,10', new isValidPhone],
            'company_name' => 'required|string',
            'user_name' => 'required|string',
            'terms' => 'accepted',
        ];
    }

    public function mount()
    {
        $this->countries = Country::all();
    }

    public function register(RegisterAction $registerAction, SendOtpAction $sendOtpAction)
    {
        $data = $this->validate(attributes: $this->nickNames());
        $merchant = $registerAction->handle($data);
        $code = $sendOtpAction->handleForMail($merchant, OTPTypes::MERCHANT_VERIFY_EMAIL, 60);
        $supeRole = Role::create([
            'name' => 'الادمن' . '_' . $merchant->id
            ,'guard_name' => "merchant_employee"
            ,'team_id' => $merchant->id
        ]);
        
        $supeRole->syncPermissions($this->preparePermissions([], "hello"));
        
        $managerRole = Role::create([
            'name' => 'مدير الفرع' . '_' . $merchant->id
            ,'guard_name' => "merchant_employee"
            ,'team_id' => $merchant->id
        ]);
        
        $managerRole->syncPermissions($this->preparePermissions(['profile'], "hello"));
        
        $employeeRole = Role::create([
            'name' => 'موظف' . '_' . $merchant->id
            ,'guard_name' => "merchant_employee"
            ,'team_id' => $merchant->id
        ]);
        $employeeRole->syncPermissions($this->preparePermissions(['profile', 'branches', 'roles', 'employees'], 'delete'));
        try{

            Mail::to($data['email'])->send(new ConfirmMail($code));
        }catch(\Exception $ex)
        {
            Log::error("send Ver code When register Market Place With Email: ".$data['email']." , Verviy Code: ".$code  );
        }
        if ($merchant) return redirect(RouteServiceProvider::MARKETPLACE_HOME);
    }

    protected function preparePermissions(array $groupNames = [], $except = "") : array
    {
        return Permission::where('team_type', Merchant::class)
            ->whereNotIn('group_name', $groupNames)
            ->where('name', 'not LIKE', '%'.$except.'%')
            ->pluck('id','id')
            ->all();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('marketplace::livewire.auth.register');
    }
}
