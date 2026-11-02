<?php
namespace Tests\Feature;
use Domain\Educator\Models\Educator;
use Tests\TestCase;
use Livewire\Livewire;

class AdminDocTabTest extends TestCase
{
    public function test_admin_doc_tab_shows_file()
    {
        $educator = Educator::find(1);
        $educator->files()->delete();
        $educator->files()->create([
            'file_name' => 'AdminVisibleDoc',
            'original_name' => 'admin.pdf',
            'mime_type' => 'application/pdf',
            'type' => 'general_document',
            'collection_name' => 'passport',
            'storage' => 'public',
            'path' => 'educators/1/documents/admin.pdf',
            'size' => 10,
        ]);
        $component = Livewire::test(\Application\Admin\Livewire\Educator\Profile\Tabs\Document::class, ['educator' => $educator]);
        $component->assertSee('AdminVisibleDoc');
    }
}
