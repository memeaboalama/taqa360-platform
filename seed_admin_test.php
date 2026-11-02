<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Domain\Educator\Models\Educator;
use Domain\Educator\Models\EducatorAcademicMajor;
use Domain\Educator\Models\EducatorAchievement;
use Domain\Educator\Models\EducatorFile;

$educator = Educator::find(2);

// clear
$educator->files()->delete();
$educator->academicMajors()->delete();
$educator->achievements()->delete();

// identity files
foreach (['identity_front','identity_back','passport_image'] as $col) {
    $educator->files()->create([
        'file_name' => $col.'.jpg',
        'original_name' => $col.'.jpg',
        'mime_type' => 'image/jpeg',
        'collection_name' => $col,
        'type' => $col,
        'storage' => 'public',
        'path' => "educators/2/identity/{$col}.jpg",
        'size' => 100,
    ]);
}

// other documents
foreach (['passport','professional_license','other'] as $col) {
    $educator->files()->create([
        'file_name' => $col.'.pdf',
        'original_name' => $col.'.pdf',
        'mime_type' => 'application/pdf',
        'collection_name' => $col,
        'type' => 'general_document',
        'storage' => 'public',
        'path' => "educators/2/documents/{$col}.pdf",
        'size' => 200,
    ]);
}

// academic major
$major = $educator->academicMajors()->create([
    'educator_id' => $educator->id,
    'academic_major_id' => null,
    'custom_name' => 'Computer Science',
    'is_custom' => true,
    'graduation_institution' => 'Test University',
    'graduation_year' => 2020,
    'degree' => 'bachelor',
    'approval_document' => false,
]);
$major->certificateFile()->create([
    'file_name' => 'certificate.pdf',
    'original_name' => 'certificate.pdf',
    'mime_type' => 'application/pdf',
    'collection_name' => 'academic_certificate',
    'type' => 'academic_certificate',
    'storage' => 'public',
    'path' => "educators/2/academic/certificate.pdf",
    'size' => 300,
    'fileable_id' => $major->id,
    'fileable_type' => 'Domain\\Educator\\Models\\EducatorAcademicMajor',
]);

// achievement
$ach = $educator->achievements()->create([
    'educator_id' => $educator->id,
    'title' => 'Test Achievement',
    'organization' => 'Test Org',
    'start_date' => '2021-01-01',
    'description' => 'Test description',
    'approval_document' => false,
]);
$ach->file()->create([
    'file_name' => 'achievement.pdf',
    'original_name' => 'achievement.pdf',
    'mime_type' => 'application/pdf',
    'collection_name' => 'achievement_attachment',
    'type' => 'achievement_attachment',
    'storage' => 'public',
    'path' => "educators/2/achievements/achievement.pdf",
    'size' => 400,
    'fileable_id' => $ach->id,
    'fileable_type' => 'Domain\\Educator\\Models\\EducatorAchievement',
]);

echo "seeded\n";
