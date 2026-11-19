<?php

namespace Tests\Feature;

use App\Domain\AppBase\Models\CustomerTestimonial;
use Application\Admin\Livewire\CustomerTestimonials\Index;
use Domain\Admin\Models\Admin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AdminCustomerTestimonialsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(new Admin([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
        ]), 'admin');

        config(['database.default' => 'customer_testimonials_test', 'database.connections.customer_testimonials_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
        DB::purge('customer_testimonials_test');

        (require app_path('Domain/AppBase/Migrations/2026_11_02_120000_create_customer_testimonials_table.php'))->up();
        (require app_path('Domain/AppBase/Migrations/2026_11_19_160000_add_status_to_customer_testimonials_table.php'))->up();
    }

    public function test_testimonial_can_be_created_edited_deactivated_and_deleted(): void
    {
        Cache::put('home_page_data_storefront_v5_ar', 'stale');

        Livewire::test(Index::class)
            ->set('name', 'عميل')
            ->set('positionAr', 'مدير')
            ->set('positionEn', 'Manager')
            ->set('contentAr', 'رأي باللغة العربية')
            ->set('contentEn', 'Review in English')
            ->set('sortOrder', 2)
            ->call('save')
            ->assertHasNoErrors();

        $testimonial = CustomerTestimonial::query()->firstOrFail();
        $this->assertFalse(Cache::has('home_page_data_storefront_v5_ar'));
        $this->assertSame('رأي باللغة العربية', $testimonial->getTranslation('content', 'ar'));
        $this->assertSame('Review in English', $testimonial->getTranslation('content', 'en'));

        Livewire::test(Index::class)
            ->call('edit', $testimonial->id)
            ->set('name', 'عميلة')
            ->set('isActive', false)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customer_testimonials', [
            'id' => $testimonial->id,
            'name' => 'عميلة',
            'is_active' => false,
        ]);

        Livewire::test(Index::class)
            ->call('delete', $testimonial->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('customer_testimonials', ['id' => $testimonial->id]);
    }

    public function test_both_review_translations_are_required(): void
    {
        Livewire::test(Index::class)
            ->set('name', 'عميل')
            ->set('contentAr', 'رأي باللغة العربية')
            ->call('save')
            ->assertHasErrors(['contentEn' => 'required']);

        $this->assertSame(0, CustomerTestimonial::query()->count());
    }

    public function test_customer_submission_is_pending_until_admin_approves_or_rejects_it(): void
    {
        $this->from('/ar')
            ->post('/ar/customer-testimonials', [
                'name' => 'عميل جديد',
                'position' => 'معلم',
                'content' => 'تجربتي كانت ممتازة مع الخدمة.',
            ])
            ->assertRedirect('/ar');

        $testimonial = CustomerTestimonial::query()->firstOrFail();
        $this->assertSame('pending', $testimonial->status);
        $this->assertFalse($testimonial->is_active);
        $this->assertSame(
            'تجربتي كانت ممتازة مع الخدمة.',
            $testimonial->getTranslation('content', 'ar')
        );

        Livewire::test(Index::class)
            ->call('approve', $testimonial->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customer_testimonials', [
            'id' => $testimonial->id,
            'status' => 'approved',
            'is_active' => true,
        ]);

        Livewire::test(Index::class)
            ->call('reject', $testimonial->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customer_testimonials', [
            'id' => $testimonial->id,
            'status' => 'rejected',
            'is_active' => false,
        ]);
    }

    public function test_customer_cannot_submit_a_testimonial_with_a_filled_honeypot(): void
    {
        $this->from('/ar')
            ->post('/ar/customer-testimonials', [
                'name' => 'عميل مزعج',
                'content' => 'محاولة آلية لإرسال رأي مزعج.',
                'website' => 'https://spam.example',
            ])
            ->assertSessionHasErrors('website');

        $this->assertSame(0, CustomerTestimonial::query()->count());
    }
}
