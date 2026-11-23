<?php

namespace Tests\Feature;

use Domain\AppBase\Models\Edu\Service;
use Domain\EducationalRequest\Models\EducationalRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AccountServiceRequestsViewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.brand' => 'taqa360']);
        app()->setLocale('ar');
    }

    private function requestRow(array $attributes = []): EducationalRequest
    {
        $row = (new EducationalRequest())->forceFill(array_merge([
            'id' => 1042,
            'title' => 'فحص توقف العاكس عن العمل',
            'type' => 'general',
            'status' => 'pending',
            'execution_status' => 'pending',
            'payment_status' => 'unpaid',
            'budget' => 5000,
            'agreed_amount' => null,
            'created_at' => '2026-11-21 12:20:00',
        ], $attributes));
        $row->setRelation('service', new Service(['name' => ['ar' => 'فحص وصيانة منظومات الطاقة الشمسية', 'en' => 'Solar maintenance']]));

        return $row;
    }

    private function renderRequests(array $rows, ?int $total = null): string
    {
        $serviceRequests = new LengthAwarePaginator($rows, $total ?? count($rows), 10, 1, [
            'path' => route('account.index'),
        ]);

        return view('ecommerce::pages.account._service_requests', compact('serviceRequests'))->render();
    }

    public function test_request_list_groups_service_identity_and_separates_statuses(): void
    {
        $html = $this->renderRequests([$this->requestRow()]);
        $this->assertStringContainsString('الطلب والخدمة', $html);
        $this->assertStringContainsString('فحص توقف العاكس عن العمل', $html);
        $this->assertStringContainsString('فحص وصيانة منظومات الطاقة الشمسية', $html);
        $this->assertStringContainsString('#1042', $html);
        $this->assertStringContainsString('قيد المراجعة', $html);
        $this->assertStringContainsString('لم يبدأ', $html);
        $this->assertStringContainsString('غير مستحق بعد', $html);
        $this->assertStringContainsString(route('account.service_request', 1042), $html);
        $this->assertStringContainsString('name="section" value="service_requests"', $html);
        $this->assertStringNotContainsString('5,000', $html);
        preg_match('/<thead>(.*?)<\/thead>/s', $html, $head);
        $this->assertSame(6, substr_count($head[1], '<th '));
    }

    public function test_agreed_payment_and_recorded_payment_are_not_shown_as_not_due(): void
    {
        $html = $this->renderRequests([
            $this->requestRow(['agreed_amount' => 250, 'payment_status' => 'unpaid']),
            $this->requestRow(['id' => 1043, 'payment_status' => 'paid', 'execution_status' => 'completed']),
        ]);
        $this->assertStringContainsString('بانتظار الدفع', $html);
        $this->assertStringContainsString('مدفوع', $html);
        $this->assertStringContainsString('مكتمل', $html);
        $this->assertStringNotContainsString('غير مستحق بعد', $html);
    }

    public function test_empty_filtered_list_keeps_toolbar_and_reports_no_matches(): void
    {
        request()->query->set('search', 'Missing request');
        request()->query->set('status', 'pending');
        $html = $this->renderRequests([]);
        $this->assertStringContainsString('value="Missing request"', $html);
        $this->assertStringContainsString('value="pending" selected', $html);
        $this->assertStringContainsString(__('ecommerce::ecommerce.no_matching_service_requests'), $html);
        $this->assertStringContainsString(__('ecommerce::ecommerce.sr_requests_count', ['count' => 0]), $html);
    }

    public function test_request_title_is_escaped_and_pagination_keeps_filters(): void
    {
        request()->query->set('section', 'service_requests');
        request()->query->set('status', 'pending');
        $html = $this->renderRequests([$this->requestRow(['title' => '<script>alert(1)</script>'])], 11);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('page=2', $html);
        $this->assertStringContainsString('section=service_requests', $html);
        $this->assertStringContainsString('status=pending', $html);
    }
}
