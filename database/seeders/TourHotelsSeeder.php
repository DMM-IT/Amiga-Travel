<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Accommodation;

class TourHotelsSeeder extends Seeder
{
    public function run()
    {
        $path = base_path('travel_packages_summary_MERGED.csv.txt');
        if (! File::exists($path)) {
            $this->command->info('tour CSV not found: ' . $path);
            return;
        }

        $raw = File::get($path);
        $utf8 = mb_convert_encoding($raw, 'UTF-8', 'UTF-16');
        $lines = preg_split('/\r\n|\n|\r/', $utf8);

        $header = null;
        $entries = [];
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $cols = str_getcsv($line, "\t");
            if ($header === null) {
                $header = $cols;
                continue;
            }
            $row = [];
            foreach ($header as $i => $h) {
                $key = strtolower(trim(preg_replace('/[^a-z0-9_]+/i', '_', $h)));
                $row[$key] = isset($cols[$i]) ? trim($cols[$i]) : '';
            }
            $entries[] = $row;

            $hotel = $row['hotel'] ?? '';
            if (! empty($hotel)) {
                $price = $row['price_per_pax'] ?? null;
                $p = null;
                if ($price !== null) {
                    $p = floatval(str_replace([',', ' '], ['', ''], $price));
                }
                Accommodation::firstOrCreate(
                    ['name' => $hotel],
                    ['destination' => $row['destinations'] ?? '', 'description' => $row['inclusions'] ?? '', 'price' => $p, 'is_active' => true]
                );
            }
        }

        $jsonPath = base_path('flutter_app/assets/tours.json');
        @mkdir(dirname($jsonPath), 0755, true);
        File::put($jsonPath, json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->command->info('TourHotelsSeeder: created ' . count($entries) . ' entries and seeded hotels.');
    }
}
