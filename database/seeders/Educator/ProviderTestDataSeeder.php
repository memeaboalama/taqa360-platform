<?php

namespace Database\Seeders\Educator;

use App\Application\Educator\Enums\EducatorStatus;
use Domain\Educator\Models\Educator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * ProviderTestDataSeeder
 * ---------------------
 * Local-only sample data so the provider page
 * (/admin/educators/show/{id}) can be reviewed in the browser.
 *
 * Guarantees:
 *   - Additive only: it never truncates, deletes or updates existing rows.
 *   - Idempotent: matched on the provider email, so re-running adds nothing.
 *   - Reuses the existing models/tables (educators, educator_profiles,
 *     educator_academic_majors, educator_achievements). No new schema.
 *
 * The qualification list deliberately includes degree = 'high_school', which
 * used to crash the "qualifications and achievements" tab, plus a spread of
 * graduation years so approving down to the LAST row can be verified.
 *
 * Run with:
 *   php artisan db:seed --class=Database\Seeders\Educator\ProviderTestDataSeeder
 */
class ProviderTestDataSeeder extends Seeder
{
    private const EMAIL = 'provider.test@local.test';

    public function run(): void
    {
        $educator = Educator::where('email', self::EMAIL)->first();

        if ($educator) {
            $this->command->info("Provider already exists (#{$educator->id}) — nothing added.");
            $this->report($educator);
            return;
        }

        DB::transaction(function () use (&$educator) {
            $educator = Educator::create([
                'uuid'                => (string) Str::uuid(),
                'name'                => 'مزود تجريبي محلي',
                'email'               => self::EMAIL,
                'phone'               => '500000001',
                'password'            => bcrypt('123456'),
                'role'                => 'provider',
                'discipline'          => 'Educational technology',
                'school_name'         => 'Local Preview Academy',
                'dialcode'            => '966',
                'country_name'        => 'Saudi Arabia',
                'country_code'        => 'sa',
                'rating'              => 4.50,
                'availability_status' => 'active',
                'status'              => EducatorStatus::ACTIVE->value,
                // The educators table filters on is_accepted = 1.
                'is_accepted'         => true,
                'accepted_terms'      => true,
                'phone_verified_at'   => now(),
                'email_verified_at'   => now(),
            ]);

            DB::table('educator_profiles')->insert([
                'uuid'              => (string) Str::uuid(),
                'educator_id'       => $educator->id,
                'gender'            => 'male',
                'education_level'   => 'bachelor',
                'experience_years'  => 8,
                'specialization'    => ' curricula development and educational supplies',
                'employment_status' => 'employed',
                'completion_step'   => 6,
                'is_complete'       => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Four qualifications. The 2021 row is the one that used to take the
            // whole tab down, and it also sorts last among the qualifications.
            $qualifications = [
                ['name' => 'High School Certificate', 'institution' => 'Local Secondary School', 'year' => 2021, 'degree' => 'high_school'],
                ['name' => 'Bachelor of Education',    'institution' => 'Local State University',   'year' => 2022, 'degree' => 'bachelor'],
                ['name' => 'Master of Curriculum',      'institution' => 'Local Grad Institute',     'year' => 2023, 'degree' => 'master'],
                ['name' => 'Teaching Certificate',      'institution' => 'Local Training Centre',    'year' => 2024, 'degree' => 'diploma'],
            ];

            foreach ($qualifications as $q) {
                DB::table('educator_academic_majors')->insert([
                    'uuid'                   => (string) Str::uuid(),
                    'educator_id'            => $educator->id,
                    'academic_major_id'      => null,
                    'custom_name'            => $q['name'],
                    'is_custom'              => 1,
                    'graduation_institution' => $q['institution'],
                    'graduation_year'        => $q['year'],
                    'degree'                 => $q['degree'],
                    // Start unapproved so accept/reject can both be exercised.
                    'approval_document'      => 0,
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);
            }

            $achievements = [
                ['title' => 'Best Teacher Award',      'organization' => 'Local Education Council', 'year' => '2024-05-01', 'desc' => 'Awarded for outstanding classroom work.'],
                ['title' => 'Curriculum Design Award', 'organization' => 'Local Learning Guild',   'year' => '2023-03-15', 'desc' => 'Recognised for the new curriculum plan.'],
                // No start_date on purpose: this one sorts to the very bottom of
                // the timeline, which is exactly where the old bug showed up.
                ['title' => 'Community Workshop',      'organization' => 'Local Community',        'year' => null,          'desc' => 'Workshop with no start date, renders last.'],
            ];

            foreach ($achievements as $a) {
                DB::table('educator_achievements')->insert([
                    'uuid'              => (string) Str::uuid(),
                    'educator_id'       => $educator->id,
                    'title'             => $a['title'],
                    'type'              => 'award',
                    'description'       => $a['desc'],
                    'organization'      => $a['organization'],
                    'start_date'        => $a['year'],
                    'issue_date'        => $a['year'],
                    'approval_document' => 0,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        });

        $educator = Educator::where('email', self::EMAIL)->firstOrFail();
        $this->report($educator);
    }

    private function report(Educator $educator): void
    {
        $majors = DB::table('educator_academic_majors')->where('educator_id', $educator->id)->count();
        $achs   = DB::table('educator_achievements')->where('educator_id', $educator->id)->count();

        $this->command->info("Provider ready: #{$educator->id} — {$educator->name} ({$educator->email})");
        $this->command->line("  qualifications: $majors (one of them degree = high_school)");
        $this->command->line("  achievements  : $achs (the last one has no start_date)");
        $this->command->line('  page          : ' . url("admin/educators/show/{$educator->id}"));
    }
}