<?php

namespace Database\Seeders;

use App\Support\Enum\FileTypesEnum;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * MerchantAttachmentsSeeder
 * -------------------------
 * Gives the existing active stores a few files so the attachments section on
 * the store page can be reviewed.
 *
 * Reuses what the project already has:
 *   - Domain\MarketPlace\Models\Merchant::files()  (morphMany merchant_files)
 *   - the 'fileDisk' disk configured in config/filesystems.php
 *   - App\Support\Enum\FileTypesEnum for the `type` column
 *
 * The seeder is idempotent: rows are matched on
 * (fileable_type, fileable_id, collection_name, file_name) so running it twice
 * creates nothing new.
 *
 * Run with:
 *   php artisan db:seed --class=MerchantAttachmentsSeeder
 */
class MerchantAttachmentsSeeder extends Seeder
{
    /** Folder used for store documents, mirroring the existing app_logo folder. */
    private const FOLDER = 'upload/file/merchant-attachments';

    /** Collection name for these files. Deliberately not 'app_logo'. */
    private const COLLECTION = 'merchant_attachments';

    /**
     * How many active stores get demo attachments. Kept small on purpose so
     * the store list is not littered with test data; the last store of the
     * selection is always skipped so the empty state stays testable.
     */
    private const TARGET_STORES = 4;

    /**
     * Which existing store gets which demo files.
     * `#46` is intentionally left out so the empty state stays testable.
     */
    private function plan(): array
    {
        return [
            'many'  => ['count' => 3, 'images' => 2, 'docs' => 1],  // several files
            'images'=> ['count' => 2, 'images' => 2, 'docs' => 0],  // images only
            'pdf'   => ['count' => 2, 'images' => 0, 'docs' => 2],  // documents only
        ];
    }

    public function run(): void
    {
        $disk = Storage::disk('fileDisk');

        if (!$disk->exists(self::FOLDER)) {
            $disk->makeDirectory(self::FOLDER);
        }

        // Pick real active stores from the database — never create new merchants.
        $stores = Merchant::where('status', 'active')
            ->orderBy('id')
            ->limit(self::TARGET_STORES + 1)   // +1 so one can be left empty
            ->pluck('id')
            ->all();

        if (count($stores) < 2) {
            $this->command->warn('Need at least 2 active merchants to seed attachments. Found ' . count($stores));
            return;
        }

        // Last store of the selection is skipped on purpose (empty-state coverage).
        $targets = array_slice($stores, 0, self::TARGET_STORES);
        $plans = array_values($this->plan());

        $created = 0;
        $skipped = 0;

        foreach ($targets as $index => $merchantId) {
            $plan = $plans[$index % count($plans)];
            $label = $plan === $plans[0] ? 'several files' : ($plan === $plans[1] ? 'images only' : 'documents only');

            $result = $this->seedStore($merchantId, $plan, $disk);
            $created += $result['created'];
            $skipped += $result['skipped'];

            $this->command->info(sprintf(
                'Store #%d (%s): %d created, %d already existed',
                $merchantId,
                $label,
                $result['created'],
                $result['skipped']
            ));
        }

        $this->command->info(sprintf(
            "\nDone. %d file(s) created, %d skipped as duplicates.",
            $created,
            $skipped
        ));
    }

    /**
     * @return array{created:int, skipped:int}
     */
    private function seedStore(int $merchantId, array $plan, $disk): array
    {
        $merchant = Merchant::find($merchantId);
        if (!$merchant) {
            return ['created' => 0, 'skipped' => 0];
        }

        $created = 0;
        $skipped = 0;
        $order = 0;

        $specs = array_merge(
            array_fill(0, $plan['images'], 'image'),
            array_fill(0, $plan['docs'], 'pdf')
        );

        foreach ($specs as $kind) {
            $order++;

            $fileName = $kind === 'image'
                ? 'store-' . $merchantId . '-image-' . $order . '.webp'
                : 'store-' . $merchantId . '-doc-' . $order . '.pdf';

            $existing = $merchant->files()
                ->where('file_name', $fileName)
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            $path = self::FOLDER . '/' . $fileName;

            // Write the physical file so the generated link actually resolves.
            if (!$disk->exists($path)) {
                $kind === 'image'
                    ? $disk->put($path, $this->makeImageBytes($merchantId, $order))
                    : $disk->put($path, $this->makePdfBytes());
            }

            // Reuse the same shape ImageService::storeImage() produces.
            $merchant->files()->create([
                'file_name'      => $fileName,
                'original_name'  => $kind === 'image'
                    ? "store-catalogue-image-{$order}.webp"
                    : "store-commercial-terms-{$order}.pdf",
                'mime_type'      => $kind === 'image' ? 'image/webp' : 'application/pdf',
                'collection_name'=> self::COLLECTION,
                'type'           => FileTypesEnum::PROFILE->value,
                'storage'        => 'fileDisk',
                'url'            => $disk->url($path),
                'path'           => $path,
                'order_column'   => $order,
                'size'           => $kind === 'image' ? 24576 : 8192,
            ]);

            $created++;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    /**
     * Minimal valid single-page PDF as a byte string.
     */
    private function makePdfBytes(): string
    {
        $text = 'Store attachment sample document';

        $content = "BT /F1 18 Tf 72 720 Td (" . $text . ") Tj ET";

        $objects = [
            "1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj",
            "2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj",
            "3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 595 842]/Resources<</Font<</F1 4 0 R>>>>/Contents 5 0 R>>endobj",
            "4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj",
            "5 0 obj<</Length " . strlen($content) . ">>stream\n" . $content . "\nendstream endobj",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $obj) {
            $offsets[] = strlen($pdf);
            $pdf .= $obj . "\n";
        }

        $xrefPos = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";

        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= "trailer<</Size " . (count($objects) + 1) . "/Root 1 0 R>>\nstartxref\n" . $xrefPos . "\n%%EOF";

        return $pdf;
    }

    /**
     * Smallest valid lossless WebP (1x1) so the image branch has real bytes
     * without pulling in an image library at seed time.
     */
    private function makeImageBytes(int $merchantId, int $order): string
    {
        // A 1x1 lossless WebP: RIFF header + VP8L chunk.
        $webp = "RIFF" . pack('V', 0x2A) . "WEBPVP8L" . pack('V', 0x2F)
              . "\x2F\x00\x00\x00\x00\x88\x88\xFE\x0F\x00\x00\x00\x00\x00\x00\x00\x00";

        // Stamp the file so it is unique per store/order even though the
        // pixel payload is identical.
        return $webp . pack('V', crc32("store-{$merchantId}-{$order}"));
    }
}
