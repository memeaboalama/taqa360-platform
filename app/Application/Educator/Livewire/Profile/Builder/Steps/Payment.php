<?php
namespace Application\Educator\Livewire\Profile\Builder\Steps;

use Domain\Educator\Models\Educator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Payment extends Component
{
    use LivewireAlert;

    public Educator $educator;
    public $bankInfo;

    protected function rules()
    {
        return [
            'bankInfo.name' => 'required|string|max:255',
            'bankInfo.iban' => 'required|string|min:15|max:34',
            'bankInfo.bank_name' => 'required|string|max:255',
            'bankInfo.bank_country' => 'required|string|max:255',
            'bankInfo.bank_code' => 'required|string|max:255',
        ];
    }

    public function mount(Educator $educator)
    {
        $this->educator = $educator;
        $this->bankInfo = $this->educator->bankInfo()->firstOrCreate(['educator_id' => $this->educator->id]);
    }

    public function save()
    {
        $this->bankInfo->iban = str_replace(' ', '', $this->bankInfo->iban);
        $this->validate();
        $this->bankInfo->save();
        $this->bankInfo->iban = chunk_split($this->bankInfo->iban, 4, ' ');
        $this->emitUp('stepCompleted', 9);
        $this->alert('success', __('educator::builder.messages.payment_info_saved'));
    }

    public function render()
    {
        return view('educator::livewire.profile.builder.steps.payment');
    }
}
