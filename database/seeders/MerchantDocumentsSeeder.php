<?php

namespace Database\Seeders;

use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * MerchantDocumentsSeeder
 * ----------------------
 * Fills the eight document slots that the admin and vendor profile screens
 * read: company_logo, owner_id, bank_account_details, vat_certificated,
 * menu_copy, cr_copy, national_address_copy, signed_contract_copy.
 *
 * Those slots are what the 404 bug affected, so the data has to live in those
 * exact `collection_name` values to be verifiable.
 *
 * It writes the same row shape ImageService::storeImage() produces and stores
 * the bytes on the same 'fileDisk', so nothing about the storage setup changes.
 *
 * Coverage on purpose:
 *   company_logo          -> image  (jpg)   image branch
 *   menu_copy             -> pdf              lowercase pdf
 *   cr_copy               -> .PDF             uppercase extension
 *   national_address_copy -> csv
 *   signed_contract_copy  -> xlsx
 *   bank_account_details  -> pdf
 *   owner_id              -> (empty)          empty-state branch
 *   vat_certificated      -> (empty)          empty-state branch
 *
 * Idempotent: matched on (fileable_id, collection_name).
 */
class MerchantDocumentsSeeder extends Seeder
{
    private const FOLDER = 'upload/file/merchant-documents';

    public function run(): void
    {
        $disk = Storage::disk('fileDisk');
        if (!$disk->exists(self::FOLDER)) {
            $disk->makeDirectory(self::FOLDER);
        }

        $merchants = Merchant::where('status', 'active')->orderBy('id')->limit(2)->get();
        if ($merchants->isEmpty()) {
            $this->command->warn('No active merchants found.');
            return;
        }

        $plan = [
            'jpg'  => ['collection' => 'company_logo',          'original' => 'company-logo.jpg'],
            'pdf'  => ['collection' => 'menu_copy',             'original' => 'menu-copy.pdf'],
            'PDF'  => ['collection' => 'cr_copy',               'original' => 'commercial-register.PDF'],
            'csv'  => ['collection' => 'national_address_copy', 'original' => 'national-address.csv'],
            'xlsx' => ['collection' => 'signed_contract_copy',  'original' => 'signed-contract.xlsx'],
            'pdf2' => ['collection' => 'bank_account_details',  'original' => 'bank-account-details.pdf'],
            // owner_id and vat_certificated are intentionally left empty.
        ];

        $created = 0;
        $skipped = 0;

        foreach ($merchants as $merchant) {
            foreach ($plan as $kind => $spec) {
                $existing = $merchant->files()
                    ->where('collection_name', $spec['collection'])
                    ->first();

                if ($existing) {
                    $skipped++;
                    continue;
                }

                $ext  = strtolower(pathinfo($spec['original'], PATHINFO_EXTENSION));
                $name = 'merchant-' . $merchant->id . '-' . $spec['collection'] . '.' . $ext;
                $path = self::FOLDER . '/' . $name;

                if (!$disk->exists($path)) {
                    $disk->put($path, $this->payload($kind));
                }

                $mime = match ($ext) {
                    'jpg'  => 'image/jpeg',
                    'csv'  => 'text/csv',
                    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    default => 'application/pdf',
                };

                $merchant->files()->create([
                    'file_name'       => $name,
                    'original_name'   => $spec['original'],
                    'mime_type'       => $mime,
                    'collection_name' => $spec['collection'],
                    'type'            => 'profile',
                    'storage'         => 'fileDisk',
                    'url'             => $disk->url($path),
                    'path'            => $path,
                    'order_column'    => null,
                    'size'            => strlen($this->payload($kind)),
                ]);

                $created++;
            }
        }

        $this->command->info("Merchant documents: $created created, $skipped already existed.");
        $this->command->line('  owner_id and vat_certificated were left empty on purpose (empty-state test).');
    }

    private function payload(string $kind): string
    {
        return match ($kind) {
            'jpg'  => $this->minimalJpeg(),
            'csv'  => "name,value\r\nrating,4.8\r\nshipping,free\r\n",
            'xlsx' => $this->minimalXlsx(),
            default => $this->minimalPdf(),
        };
    }

    private function minimalJpeg(): string
    {
        // Smallest valid baseline JPEG (8x8, solid colour).
        return base64_decode(
            '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0a'
            . 'HBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/wAALCAAIAAgBAREA/8QAHwAAAQUBAQEB'
            . 'AQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1Fh'
            . 'ByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZ'
            . 'WmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXG'
            . 'x8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/9oACAEBAAA/APn+iiigD//Z')
            ?: '';
    }

    private function minimalPdf(): string
    {
        $content = "BT /F1 14 Tf 60 760 Td (Merchant document sample) Tj ET";

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

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
        foreach ($offsets as $o) {
            $pdf .= sprintf("%010d 00000 n \n", $o);
        }
        $pdf .= "trailer<</Size " . (count($objects) + 1) . "/Root 1 0 R>>\nstartxref\n" . $xref . "\n%%EOF";

        return $pdf;
    }

    private function minimalXlsx(): string
    {
        if (!class_exists(\ZipArchive::class)) {
            // Fallback: a CSV payload still exercises the .xlsx link branch.
            return "name,value\r\nrating,4.8\r\n";
        }

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml',
            '<?xml version="1.0" encoding="UTF-8"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '</Types>');

        $zip->addFromString('_rels/.rels',
            '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>');

        $zip->addFromString('xl/workbook.xml',
            '<?xml version="1.0" encoding="UTF-8"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets></workbook>');

        $zip->addFromString('xl/_rels/workbook.xml.rels',
            '<?xml version="1.0" encoding="UTF-8"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '</Relationships>');

        $zip->addFromString('xl/worksheets/sheet1.xml',
            '<?xml version="1.0" encoding="UTF-8"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetData><row r="1"><c r="A1" t="inlineStr"><is><t>name</t></is></c>'
            . '<c r="B1" t="inlineStr"><is><t>value</t></is></c></row>'
            . '<row r="2"><c r="A2" t="inlineStr"><is><t>rating</t></is></c>'
            . '<c r="B2"><v>4.8</v></c></row></sheetData></worksheet>');

        $zip->close();

        $bytes = file_get_contents($tmp);
        @unlink($tmp);

        return $bytes !== false ? $bytes : '';
    }
}