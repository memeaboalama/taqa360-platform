<?php

namespace Tests\Feature;

use Application\Admin\Livewire\Merchant\Tabs\Info\Iban as AdminMerchantIban;
use App\Domain\AppBase\Models\Setting\Currency;
use App\Domain\MarketPlace\Models\MerchantIban;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class AdminMerchantIbanTest extends TestCase
{
    public function test_admin_can_create_and_update_current_iban_for_a_merchant_without_one(): void
    {
        [$merchant, $country, $currency] = $this->createTestMerchant();
        Storage::fake('fileDisk');
        $iban = 'SA' . strtoupper(str_replace('-', '', (string) Str::uuid()));
        $updatedIban = 'SA' . strtoupper(str_replace('-', '', (string) Str::uuid()));

        try {
            $component = Livewire::test(AdminMerchantIban::class, ['merchant_id' => $merchant->id])
                ->set('current_bank_name', 'Test Bank')
                ->set('current_beneficiary_full_name', 'Test Beneficiary')
                ->set('current_iban', $iban)
                ->call('updateInfo')
                ->assertHasErrors();

            $component
                ->set('iban_file', UploadedFile::fake()->image('iban.png', 250, 250))
                ->call('updateInfo')
                ->assertHasNoErrors()
                ->assertSet('current_iban', $iban);

            $iban = MerchantIban::where('merchant_id', $merchant->id)
                ->where('is_temp', 0)
                ->firstOrFail();
            $this->assertSame('Test Bank', $iban->bank_name);
            $this->assertSame('Test Beneficiary', $iban->beneficiary_full_name);
            $this->assertNotEmpty($iban->iban_file);

            MerchantIban::create([
                'merchant_id' => $merchant->id,
                'bank_name' => 'Pending Bank',
                'beneficiary_full_name' => 'Pending Beneficiary',
                'iban' => 'SA' . strtoupper(str_replace('-', '', (string) Str::uuid())),
                'is_temp' => 1,
                'iban_file' => 'upload/file/pending-iban.webp',
            ]);

            $component
                ->set('current_iban', $updatedIban)
                ->call('updateInfo')
                ->assertHasNoErrors()
                ->assertSet('current_iban', $updatedIban);

            $iban->refresh();
            $this->assertSame($updatedIban, $iban->iban);
            $this->assertNotEmpty($iban->iban_file);
            $this->assertDatabaseHas('merchant_ibans', [
                'merchant_id' => $merchant->id,
                'is_temp' => 1,
                'bank_name' => 'Pending Bank',
                'beneficiary_full_name' => 'Pending Beneficiary',
            ]);
        } finally {
            $this->deleteTestMerchant($merchant, $country, $currency);
        }
    }

    public function test_accepting_a_requested_iban_replaces_the_current_record_and_clears_the_request(): void
    {
        [$merchant, $country, $currency] = $this->createTestMerchant();
        $currentFile = 'upload/file/current-iban.webp';
        $requestedFile = 'upload/file/requested-iban.webp';

        MerchantIban::create([
            'merchant_id' => $merchant->id,
            'bank_name' => 'Old Bank',
            'beneficiary_full_name' => 'Old Beneficiary',
            'iban' => 'SA' . strtoupper(str_replace('-', '', (string) Str::uuid())),
            'is_temp' => 0,
            'iban_file' => $currentFile,
        ]);
        $request = MerchantIban::create([
            'merchant_id' => $merchant->id,
            'bank_name' => 'New Bank',
            'beneficiary_full_name' => 'New Beneficiary',
            'iban' => $requestedIban = 'SA' . strtoupper(str_replace('-', '', (string) Str::uuid())),
            'is_temp' => 1,
            'iban_file' => $requestedFile,
        ]);

        try {
            Livewire::test(AdminMerchantIban::class, ['merchant_id' => $merchant->id])
                ->call('acceptChanges')
                ->assertHasNoErrors()
                ->assertSet('merchantTempIban', null)
                ->assertSet('current_iban', $requestedIban);

            $this->assertDatabaseHas('merchant_ibans', [
                'merchant_id' => $merchant->id,
                'is_temp' => 0,
                'bank_name' => 'New Bank',
                'beneficiary_full_name' => 'New Beneficiary',
                'iban' => $requestedIban,
                'iban_file' => $requestedFile,
            ]);
            $this->assertDatabaseMissing('merchant_ibans', ['id' => $request->id]);
        } finally {
            $this->deleteTestMerchant($merchant, $country, $currency);
        }
    }

    private function createTestMerchant(): array
    {
        $currency = Currency::create([
            'title' => ['en' => 'Test Currency', 'ar' => 'عملة اختبار'],
            'iso' => 'tst',
        ]);
        $countryId = DB::table('countries')->insertGetId([
            'title' => json_encode(['en' => 'Test Country', 'ar' => 'بلد اختبار']),
            'iso' => 'tst',
            'uuid' => (string) Str::uuid(),
            'currency_id' => $currency->id,
            'vat' => 0,
            'status' => 'active',
            'priority' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $merchant = Merchant::create(['country_id' => $countryId, 'status' => 'active']);

        return [$merchant, $countryId, $currency->id];
    }

    private function deleteTestMerchant(Merchant $merchant, int $countryId, int $currencyId): void
    {
        DB::table('merchant_ibans')->where('merchant_id', $merchant->id)->delete();
        DB::table('merchants')->where('id', $merchant->id)->delete();
        DB::table('countries')->where('id', $countryId)->delete();
        DB::table('currencies')->where('id', $currencyId)->delete();
    }
}
