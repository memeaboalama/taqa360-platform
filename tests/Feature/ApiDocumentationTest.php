<?php

namespace Tests\Feature;

use App\Application\Admin\Middleware\AdminRedirect;
use Tests\TestCase;

class ApiDocumentationTest extends TestCase
{
    public function test_swagger_ui_and_api_definition_require_admin_authentication(): void
    {
        $this->get('/api/documentation')->assertRedirect('/admin/login');
        $this->get('/docs?api-docs.json')->assertRedirect('/admin/login');
    }

    public function test_generated_openapi_document_lists_routes_and_authentication(): void
    {
        $document = json_decode(
            file_get_contents(base_path('docs/api-docs/api-docs.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertSame('3.0.3', $document['openapi']);
        $this->assertNotEmpty($document['paths']);
        $this->assertArrayNotHasKey('security', $document['paths']['/api/v1/educator/auth/login']['post']);
        $this->assertArrayHasKey('security', $document['paths']['/api/v1/educator/dashboard/index']['get']);
    }

    public function test_api_definition_route_serves_the_generated_json(): void
    {
        $this->withoutMiddleware(AdminRedirect::class);

        $this->get('/docs?api-docs.json')
            ->assertOk()
            ->assertJsonPath('openapi', '3.0.3');
    }
}
