<?php

namespace Database\Seeders\Educator;

use App\Application\Educator\Enums\EducatorStatus;
use Domain\Educator\Models\Educator;
use Domain\Educator\Models\EducatorAcademicMajor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LogicException;
use RuntimeException;

class ProviderTestDataSeeder extends Seeder
{
    private const TEST_DOCUMENT_PATH = 'local-test/educators/demo-document.svg';

    public function run(): void
    {
        if (!app()->environment('local')) {
            throw new LogicException('Provider test data can only be seeded in the local environment.');
        }

        $this->createDemoDocument();

        $providers = [
            [
                'name' => 'مزود تجريبي - أحمد',
                'email' => 'provider.test@local.test',
                'phone' => '500000001',
                'status' => EducatorStatus::ACTIVE->value,
                'gender' => 'male',
                'identity_id' => 'DEMO-EDU-001',
                'specialization' => 'تطوير المناهج وتقنيات التعليم',
                'experience_years' => 8,
            ],
            [
                'name' => 'مزودة تجريبية - سارة',
                'email' => 'provider.test.2@local.test',
                'phone' => '500000002',
                'status' => EducatorStatus::PENDING->value,
                'gender' => 'female',
                'identity_id' => 'DEMO-EDU-002',
                'specialization' => 'التدريب والتطوير المهني',
                'experience_years' => 5,
            ],
            [
                'name' => 'مزود تجريبي - خالد',
                'email' => 'provider.test.3@local.test',
                'phone' => '500000003',
                'status' => EducatorStatus::REJECTED->value,
                'gender' => 'male',
                'identity_id' => 'DEMO-EDU-003',
                'specialization' => 'التعليم الإلكتروني',
                'experience_years' => 3,
            ],
        ];

        foreach ($providers as $index => $provider) {
            $existing = Educator::where('email', $provider['email'])->first();

            if ($existing) {
                $existing->update([
                    'is_accepted' => $provider['status'] !== EducatorStatus::PENDING->value,
                ]);
                $this->command->line("Provider already exists (#{$existing->id}): {$existing->email}");
                continue;
            }

            $educator = DB::transaction(fn () => $this->createProvider($provider, $index + 1));
            $this->command->info("Created local test provider #{$educator->id}: {$educator->email}");
        }

        $this->command->info('Local test providers are ready in the Admin > Providers list.');
    }

    private function createProvider(array $provider, int $sequence): Educator
    {
        $educator = Educator::create([
            'name' => $provider['name'],
            'email' => $provider['email'],
            'phone' => $provider['phone'],
            'password' => bcrypt('local-test-password'),
            'role' => 'provider',
            'discipline' => $provider['specialization'],
            'school_name' => 'أكاديمية الاختبار المحلية',
            'dialcode' => '966',
            'country_name' => 'Saudi Arabia',
            'country_code' => 'sa',
            'rating' => 4.50,
            'availability_status' => 'active',
            'status' => $provider['status'],
            'is_accepted' => $provider['status'] !== EducatorStatus::PENDING->value,
            'accepted_terms' => true,
            'phone_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        DB::table('educator_profiles')->insert([
            'uuid' => (string) Str::uuid(),
            'educator_id' => $educator->id,
            'identity_id' => $provider['identity_id'],
            'identity_type' => 'national_id',
            'identity_status' => 'pending',
            'gender' => $provider['gender'],
            'birth_date' => sprintf('%04d-01-15', 1988 + $sequence),
            'education_level' => 'bachelor',
            'experience_years' => $provider['experience_years'],
            'specialization' => $provider['specialization'],
            'employment_status' => 'employed',
            'completion_step' => 6,
            'is_complete' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $qualifications = [
            ['name' => 'شهادة الثانوية العامة', 'institution' => 'مدرسة تجريبية', 'year' => '2010-01-01', 'degree' => 'high_school'],
            ['name' => 'بكالوريوس التربية', 'institution' => 'جامعة تجريبية', 'year' => '2014-01-01', 'degree' => 'bachelor'],
            ['name' => 'ماجستير التعليم', 'institution' => 'جامعة تجريبية', 'year' => '2018-01-01', 'degree' => 'master'],
        ];

        foreach ($qualifications as $qualification) {
            $major = EducatorAcademicMajor::create([
                'educator_id' => $educator->id,
                'custom_name' => $qualification['name'],
                'is_custom' => true,
                'graduation_institution' => $qualification['institution'],
                'graduation_year' => $qualification['year'],
                'degree' => $qualification['degree'],
                'approval_document' => false,
            ]);

            $this->createFileRecord($major, 'certificate', "شهادة {$qualification['name']}.svg");
        }

        foreach ([
            ['title' => 'جائزة التميز التعليمي', 'organization' => 'جهة تعليمية تجريبية', 'date' => '2024-05-01'],
            ['title' => 'ورشة تدريبية تجريبية', 'organization' => 'مركز تدريب تجريبي', 'date' => '2022-03-15'],
        ] as $achievement) {
            DB::table('educator_achievements')->insert([
                'uuid' => (string) Str::uuid(),
                'educator_id' => $educator->id,
                'title' => $achievement['title'],
                'type' => 'award',
                'description' => 'سجل تجريبي محلي لاختبار شاشة المزود.',
                'organization' => $achievement['organization'],
                'start_date' => $achievement['date'],
                'issue_date' => $achievement['date'],
                'approval_document' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (['identity_front', 'identity_back', 'profile_image'] as $collection) {
            $this->createFileRecord($educator, $collection, "ملف تجريبي {$collection}.svg");
        }

        return $educator;
    }

    private function createFileRecord(Educator|EducatorAcademicMajor $owner, string $collection, string $name): void
    {
        DB::table('educator_files')->insert([
            'uuid' => (string) Str::uuid(),
            'fileable_type' => $owner::class,
            'fileable_id' => $owner->id,
            'file_name' => basename(self::TEST_DOCUMENT_PATH),
            'original_name' => $name,
            'mime_type' => 'image/svg+xml',
            'collection_name' => $collection,
            'type' => 'image',
            'storage' => 'public',
            'path' => self::TEST_DOCUMENT_PATH,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createDemoDocument(): void
    {
        $svg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="640" height="400" viewBox="0 0 640 400">
  <rect width="640" height="400" fill="#f3f6fa"/>
  <rect x="20" y="20" width="600" height="360" rx="18" fill="#fff" stroke="#306faa" stroke-width="4"/>
  <text x="320" y="165" text-anchor="middle" font-family="Arial, sans-serif" font-size="38" font-weight="700" fill="#306faa">LOCAL TEST FILE</text>
  <text x="320" y="225" text-anchor="middle" font-family="Arial, sans-serif" font-size="25" fill="#444">NOT A REAL DOCUMENT</text>
  <text x="320" y="285" text-anchor="middle" font-family="Arial, sans-serif" font-size="19" fill="#666">For local testing only</text>
</svg>
SVG;

        if (!Storage::disk('public')->put(self::TEST_DOCUMENT_PATH, $svg)) {
            throw new RuntimeException('Could not create the local provider test document.');
        }
    }
}
