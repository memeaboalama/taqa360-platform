<?php

namespace Tests\Unit;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\User\Models\User;
use App\Support\Enum\ActiveEnum;
use Application\Admin\Livewire\User\Tabs\Info\Info;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AdminCustomerAndOrdersQueryTest extends TestCase
{
    public function test_admin_order_list_defaults_to_all_order_columns(): void
    {
        $repository = new MerchantOrderRepository(new MerchantOrder());

        $query = $repository->filterOrders();

        $this->assertNull($query->getQuery()->columns);
    }

    public function test_customer_order_list_selects_and_loads_columns_used_by_its_table(): void
    {
        $repository = new MerchantOrderRepository(new MerchantOrder());

        $query = $repository->getUserOrderById(123);

        $this->assertSame([
            'id',
            'total_after_vat',
            'user_id',
            'status',
            'merchant_id',
            'merchant_branch_id',
            'created_at',
        ], $query->getQuery()->columns);
        $this->assertArrayHasKey('user', $query->getEagerLoads());
        $this->assertArrayHasKey('merchant', $query->getEagerLoads());
        $this->assertArrayHasKey('branch', $query->getEagerLoads());
    }

    public function test_customer_status_filter_renders_backed_enum_values(): void
    {
        $html = view('admin::livewire.user.table.filter', [
            'statuses' => new Collection(ActiveEnum::cases()),
            'customer_name' => null,
            'status' => null,
        ])->render();

        $this->assertStringContainsString('value="active"', $html);
        $this->assertStringContainsString('value="inactive"', $html);
    }

    public function test_customer_info_validates_the_customer_phone_against_users(): void
    {
        $component = new class extends Info {
            public function exposedRules(): array
            {
                return $this->rules();
            }
        };
        $component->user = new User();
        $component->user->id = 26;

        $rules = $component->exposedRules();

        $this->assertContains('unique:users,phone,26', explode('|', $rules['phone']));
        $this->assertArrayHasKey('name', get_object_vars($component));
    }
}
