<?php

namespace Tests\Unit;

use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\SalesAgent\Repositories\SalesAgentRepository;
use App\Domain\User\Models\User;
use App\Support\Enum\ActiveEnum;
use Application\Admin\Controllers\SalesAgentController;
use Application\Admin\Livewire\Finance\Reports\Driver\Invoice\Table\All as DriverInvoiceReportTable;
use Application\Admin\Livewire\Finance\Reports\Order\Driver\Table\All as DriverOrderReportTable;
use Application\Admin\Livewire\Finance\Reports\Order\Merchant\Table\All as MerchantOrderReportTable;
use Application\Admin\Livewire\SalesAgent\Modal\Create as CreateSalesAgent;
use Application\Admin\Livewire\SalesAgent\Tabs\Info as SalesAgentInfo;
use Application\Admin\Livewire\User\Tabs\Info\Info;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Tests\TestCase;

class AdminCustomerAndOrdersQueryTest extends TestCase
{
    public function test_admin_order_list_defaults_to_all_order_columns(): void
    {
        $repository = new MerchantOrderRepository(new MerchantOrder());

        $query = $repository->filterOrders();

        $this->assertNull($query->getQuery()->columns);
    }

    public function test_cash_back_filter_is_applied_to_order_reports(): void
    {
        $repository = new MerchantOrderRepository(new MerchantOrder());

        $query = $repository->filterOrders(['cash_back_id' => 17]);

        $this->assertStringContainsString('cash_back_id', $query->toSql());
        $this->assertContains(17, $query->getBindings());
    }

    public function test_finance_report_tables_select_all_columns_used_by_their_rows(): void
    {
        foreach ([
            new MerchantOrderReportTable(),
            new DriverOrderReportTable(),
            new DriverInvoiceReportTable(),
        ] as $table) {
            $this->assertNull($table->builder()->getQuery()->columns);
        }
    }

    public function test_sales_agent_forms_validate_uniqueness_against_sales_agents(): void
    {
        $create = new class extends CreateSalesAgent {
            public function exposedRules(): array
            {
                return $this->rules();
            }
        };
        $edit = new class extends SalesAgentInfo {
            public function exposedRules(): array
            {
                return $this->rules();
            }
        };
        $edit->salesAgentId = 42;

        $this->assertContains('unique:sales_agents,email', explode('|', $create->exposedRules()['email']));
        $this->assertContains('unique:sales_agents,phone', explode('|', $create->exposedRules()['phone']));
        $this->assertContains('unique:sales_agents,email,42', explode('|', $edit->exposedRules()['email']));
        $this->assertContains('unique:sales_agents,phone,42', explode('|', $edit->exposedRules()['phone']));
    }

    public function test_missing_sales_agent_redirects_to_the_list_instead_of_showing_a_404(): void
    {
        $repository = \Mockery::mock(SalesAgentRepository::class);
        $repository->shouldReceive('findById')->once()->with(3)->andReturnNull();
        $repository->shouldNotReceive('getSalesAgentTotalOrderReport');

        $response = (new SalesAgentController($repository))->show(3);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringEndsWith('/admin/salesagents', parse_url($response->getTargetUrl(), PHP_URL_PATH));
        $this->assertSame('warning', session('livewire-alert')['type']);
        $this->assertSame(__('admin::home.sales_agent_not_found'), session('livewire-alert')['message']);
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

    public function test_customer_phone_number_does_not_show_dial_code_without_a_phone(): void
    {
        $user = new User();
        $user->dialcode = '966';

        $this->assertSame('', $user->phone_number);

        $user->phone = '501234567';
        $this->assertSame('966501234567', $user->phone_number);
    }
}
