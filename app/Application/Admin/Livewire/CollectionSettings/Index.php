<?php

namespace Application\Admin\Livewire\CollectionSettings;

use App\Domain\MarketPlace\Models\CollectionSetting;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use LivewireAlert;

    public $recipient = 'platform';
    public array $enabledMethods = [];
    public array $methods = [];
    public array $reviewRoleIds = [];
    public array $approvalRoleIds = [];
    public array $correctionRoleIds = [];
    public $roles;

    public function mount(): void
    {
        $this->authorizeSettings();
        $this->roles = Role::query()->where('guard_name', 'admin')->orderBy('name')->get();

        $setting = CollectionSetting::current();
        $this->methods = array_replace_recursive([
            'cash' => ['recipient_name' => '', 'instructions' => '', 'account_data' => ''],
            'transfer' => ['recipient_name' => '', 'instructions' => '', 'account_data' => ''],
            'deposit' => ['recipient_name' => '', 'instructions' => '', 'account_data' => ''],
        ], $setting?->methods ?? []);

        if ($setting) {
            $this->recipient = $setting->recipient;
            $this->enabledMethods = $setting->enabled_methods ?? [];
            $this->reviewRoleIds = $setting->review_role_ids ?? [];
            $this->approvalRoleIds = $setting->approval_role_ids ?? [];
            $this->correctionRoleIds = $setting->correction_role_ids ?? [];
        }
    }

    protected function rules(): array
    {
        $rules = [
            'recipient' => ['required', Rule::in(['platform', 'merchant'])],
            'enabledMethods' => ['required', 'array', 'min:1'],
            'enabledMethods.*' => [Rule::in(['cash', 'transfer', 'deposit'])],
            'reviewRoleIds' => ['nullable', 'array'],
            'approvalRoleIds' => ['nullable', 'array'],
            'correctionRoleIds' => ['nullable', 'array'],
        ];

        foreach (['reviewRoleIds', 'approvalRoleIds', 'correctionRoleIds'] as $property) {
            $rules[$property . '.*'] = [
                Rule::exists('roles', 'id')->where('guard_name', 'admin'),
            ];
        }

        foreach (['cash', 'transfer', 'deposit'] as $method) {
            $enabled = in_array($method, $this->enabledMethods, true);
            $rules["methods.{$method}.recipient_name"] = [$enabled ? 'required' : 'nullable', 'string', 'max:255'];
            $rules["methods.{$method}.instructions"] = [$enabled ? 'required' : 'nullable', 'string', 'max:2000'];
            $rules["methods.{$method}.account_data"] = [
                in_array($method, ['transfer', 'deposit'], true) && $enabled ? 'required' : 'nullable',
                'string',
                'max:2000',
            ];
        }

        return $rules;
    }

    public function save(): void
    {
        $this->authorizeSettings(true);
        $data = $this->validate();

        $setting = CollectionSetting::query()->firstOrNew(['id' => 1]);
        $setting->fill([
            'recipient' => $data['recipient'],
            'enabled_methods' => array_values(array_unique($data['enabledMethods'])),
            'methods' => $data['methods'],
            'review_role_ids' => $data['reviewRoleIds'] ?? [],
            'approval_role_ids' => $data['approvalRoleIds'] ?? [],
            'correction_role_ids' => $data['correctionRoleIds'] ?? [],
        ])->save();

        $this->alert('success', __('admin::home.data_updated_successfully'));
    }

    public function canEditSettings(): bool
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            return false;
        }

        setPermissionsTeamId(1);
        return $admin->type === 'admin'
            || $admin->hasAnyPermission(['admin', 'edit delivery fees_settings']);
    }

    private function authorizeSettings(bool $editing = false): void
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            abort(403);
        }

        setPermissionsTeamId(1);
        $permissions = $editing
            ? ['admin', 'edit delivery fees_settings']
            : ['admin', 'show delivery fees_settings', 'edit delivery fees_settings'];

        abort_unless($admin->type === 'admin' || $admin->hasAnyPermission($permissions), 403);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('admin::livewire.collection-settings.index');
    }
}
