<?php

namespace Tests\Unit;

use Domain\Project\Models\ProjectFile;
use Domain\Project\Models\TaskFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileSizeInBytesTest extends TestCase
{
    public function test_returns_the_actual_stored_size_for_legacy_rounded_values(): void
    {
        Storage::fake('fileDisk');
        $path = 'projects/attachments/example.pdf';
        Storage::disk('fileDisk')->put($path, str_repeat('x', 1500));

        $file = new ProjectFile();
        $file->setRawAttributes([
            'size' => 0,
            'path' => $path,
            'storage' => 'fileDisk',
        ], true);

        $this->assertSame(1500, $file->sizeInBytes());
    }

    public function test_prefers_the_original_byte_size_for_new_uploads(): void
    {
        Storage::fake('fileDisk');
        $path = 'projects/attachments/compressed.webp';
        Storage::disk('fileDisk')->put($path, str_repeat('x', 1500));

        $file = new ProjectFile();
        $file->setRawAttributes([
            'size' => 3000,
            'path' => $path,
            'storage' => 'fileDisk',
        ], true);

        $this->assertSame(3000, $file->sizeInBytes());
    }

    public function test_converts_legacy_megabytes_and_preserves_byte_values_when_file_is_missing(): void
    {
        $legacyFile = new TaskFile();
        $legacyFile->setRawAttributes(['size' => 0.25], true);

        $newFile = new TaskFile();
        $newFile->setRawAttributes(['size' => 2048], true);

        $this->assertSame(262144, $legacyFile->sizeInBytes());
        $this->assertSame(2048, $newFile->sizeInBytes());
    }
}
