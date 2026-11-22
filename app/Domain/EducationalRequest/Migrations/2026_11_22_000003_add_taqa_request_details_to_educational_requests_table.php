<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = [
        'delivery_mode',
        'property_type',
        'site_city',
        'site_city_other',
        'service_address',
        'latitude',
        'longitude',
        'access_notes',
        'preferred_date',
        'is_flexible',
        'preferred_period',
        'contact_name',
        'contact_phone',
        'service_answers',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('educational_requests')) {
            return;
        }

        $columns = array_values(array_filter(
            $this->columns,
            fn (string $column) => ! Schema::hasColumn('educational_requests', $column)
        ));

        if (! $columns) {
            return;
        }

        Schema::table('educational_requests', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                switch ($column) {
                    case 'delivery_mode':
                    case 'property_type':
                    case 'site_city':
                    case 'site_city_other':
                    case 'preferred_period':
                        $table->string($column)->nullable();
                        break;
                    case 'service_address':
                    case 'access_notes':
                        $table->text($column)->nullable();
                        break;
                    case 'latitude':
                    case 'longitude':
                        $table->decimal($column, 10, 7)->nullable();
                        break;
                    case 'preferred_date':
                        $table->date($column)->nullable();
                        break;
                    case 'is_flexible':
                        $table->boolean($column)->nullable()->default(true);
                        break;
                    case 'contact_name':
                        $table->string($column)->nullable();
                        break;
                    case 'contact_phone':
                        $table->string($column, 30)->nullable();
                        break;
                    case 'service_answers':
                        $table->json($column)->nullable();
                        break;
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('educational_requests')) {
            return;
        }

        $columns = array_values(array_filter(
            $this->columns,
            fn (string $column) => Schema::hasColumn('educational_requests', $column)
        ));

        if ($columns) {
            Schema::table('educational_requests', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
