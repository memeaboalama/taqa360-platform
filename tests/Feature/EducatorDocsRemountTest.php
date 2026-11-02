<?php
namespace Tests\Feature;
use Domain\Educator\Models\Educator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class EducatorDocsRemountTest extends TestCase
{
    public function test_remount_shows_saved_doc()
    {
        Storage::fake('public');
        $educator = Educator::find(1);
        $this->actingAs($educator, 'educator');
        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $component = Livewire::test(\Application\Educator\Livewire\Profile\Builder\Steps\Documents::class, ['educator' => $educator]);
        $component->set('documents.0.type', 'passport');
        $component->set('documents.0.file_name', 'My passport');
        $component->set('uploads.0', $file);
        $component->call('save');
        $component->assertHasNoErrors();

        // remount
        $component2 = Livewire::test(\Application\Educator\Livewire\Profile\Builder\Steps\Documents::class, ['educator' => $educator]);
        dump($component2->get('documents'));
        $this->assertCount(1, $component2->get('documents'));
    }
}
