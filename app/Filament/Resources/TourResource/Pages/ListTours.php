<?php

namespace App\Filament\Resources\TourResource\Pages;

use App\Filament\Resources\TourResource;
use App\Models\Tour;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\File;

class ListTours extends ListRecords
{
    protected static string $resource = TourResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('importFromCsv')
                ->label('Import From CSV')
                ->color('info')
                ->action(function () {
                    $this->importToursFromCsv();
                }),
            Actions\CreateAction::make(),
        ];
    }

    protected function importToursFromCsv(): void
    {
        $path = base_path('travel_packages_summary_MERGED.csv.txt');
        if (!File::exists($path)) {
            Notification::make()
                ->title('CSV file not found')
                ->danger()
                ->send();
            return;
        }

        $raw = File::get($path);
        $utf8 = mb_convert_encoding($raw, 'UTF-8', 'UTF-16');
        $lines = preg_split('/\r\n|\n|\r/', $utf8);

        $header = null;
        $importedCount = 0;
        $skippedCount = 0;

        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }

            $cols = str_getcsv($line, "\t");
            if ($header === null) {
                $header = $cols;
                continue;
            }

            $obj = [];
            foreach ($header as $idx => $colName) {
                $cleanKey = strtolower(trim(preg_replace('/[^a-z0-9_]+/i', '_', $colName)));
                $obj[$cleanKey] = isset($cols[$idx]) ? trim($cols[$idx]) : '';
            }

            // Skip if we already have this tour name
            if (Tour::where('tour_name', $obj['tour_name'])->exists()) {
                $skippedCount++;
                continue;
            }

            // Parse mode and trip type
            $modeCandidate = strtolower($obj['mode_of_transportation'] ?? $obj['airline'] ?? '');
            $inclusions = strtolower($obj['inclusions'] ?? '');
            $mode = str_contains($modeCandidate, 'ferry') || str_contains($inclusions, 'ferry') ? 'ferry' : 'airline';

            // Parse duration days
            $durationDays = 1;
            if (!empty($obj['duration_days']) && is_numeric($obj['duration_days'])) {
                $durationDays = intval($obj['duration_days']);
            } elseif (!empty($obj['duration'])) {
                if (preg_match('/(\d+)\s*[dD]/', $obj['duration'], $m)) {
                    $durationDays = intval($m[1]);
                } elseif (preg_match('/(\d+)\s*day/i', $obj['duration'], $m2)) {
                    $durationDays = intval($m2[1]);
                }
            }

            // Parse price per pax (remove non-digit except .)
            $pricePerPax = preg_replace('/[^0-9.]/', '', $obj['price_per_pax'] ?? '0');
            $pricePerPax = floatval($pricePerPax);

            // Create tour
            $tour = Tour::create([
                'tour_name' => $obj['tour_name'],
                'promo' => $obj['promo'] ?? null,
                'country' => $obj['country'] ?? null,
                'destinations' => $obj['destinations'] ?? '',
                'duration' => $obj['duration'] ?? '',
                'duration_days' => $durationDays,
                'price_per_pax' => $pricePerPax,
                'airline' => $obj['airline'] ?? null,
                'origin' => $obj['departure'] ?? '',
                'destination' => explode(';', $obj['destinations'] ?? '')[0],
                'mode' => $mode,
                'hotel' => $obj['hotel'] ?? null,
                'inclusions' => $obj['inclusions'] ?? null,
                'exclusions' => $obj['exclusions'] ?? null,
                'highlights' => $obj['highlights'] ?? null,
                'day1' => $obj['day1'] ?? null,
                'day2' => $obj['day2'] ?? null,
                'day3' => $obj['day3'] ?? null,
                'day4' => $obj['day4'] ?? null,
                'day5' => $obj['day5'] ?? null,
                'day6' => $obj['day6'] ?? null,
                'meals' => $obj['meals'] ?? null,
                'hand_carry' => $obj['hand_carry'] ?? null,
                'check_in_baggage' => $obj['check_in_baggage'] ?? null,
                'tour_guide' => $obj['tour_guide'] ?? null,
                'travel_insurance' => $obj['travel_insurance'] ?? null,
                'remarks' => $obj['remarks'] ?? null,
            ]);

            // Parse available dates if any
            if (!empty($obj['available_dates']) && !preg_match('/not\s*specified/i', $obj['available_dates'])) {
                $availableDatesRaw = $obj['available_dates'];
                $candidates = preg_split('/[;,|\/]+/', $availableDatesRaw);
                foreach ($candidates as $dateStr) {
                    $dateStr = trim($dateStr);
                    if ($dateStr === '') continue;
                    try {
                        $date = Carbon::parse($dateStr);
                        $tour->dates()->create([
                            'date' => $date->format('Y-m-d'),
                        ]);
                    } catch (\Throwable $e) {
                        // Skip invalid dates
                    }
                }
            }

            $importedCount++;
        }

        Notification::make()
            ->title('Import Complete')
            ->body("Imported {$importedCount} tours, skipped {$skippedCount} existing tours.")
            ->success()
            ->send();
    }
}
