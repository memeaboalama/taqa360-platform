<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add offer-related columns to the items table.
     *
     * sale_price      — The discounted price set by the merchant/admin.
     * is_on_sale      — Flag to mark the item as actively on sale.
     * sale_expires_at — Optional expiry timestamp for the offer.
     *
     * These columns are NULLABLE, ensuring zero impact on existing rows/features.
     */
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Discounted price (must be lower than default_price)
            $table->decimal('sale_price', 10, 2)
                  ->nullable()
                  ->default(null)
                  ->after('default_price')
                  ->comment('Promotional sale price; null means no active price discount');

            // Quick flag for filtering/querying on-sale items efficiently
            $table->boolean('is_on_sale')
                  ->default(false)
                  ->after('sale_price')
                  ->comment('True when the item is actively displayed in special offers');

            // Optional countdown deadline for time-limited offers
            $table->timestamp('sale_expires_at')
                  ->nullable()
                  ->default(null)
                  ->after('is_on_sale')
                  ->comment('Offer expiry; null means the offer runs indefinitely');

            // Index for performant offer queries
            $table->index(['is_on_sale', 'status'], 'items_sale_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('items_sale_status_index');
            $table->dropColumn(['sale_price', 'is_on_sale', 'sale_expires_at']);
        });
    }
};
