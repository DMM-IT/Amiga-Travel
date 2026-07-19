<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $path = base_path('travel_packages_summary_MERGED.csv.txt');
        if (! File::exists($path)) {
            return response()->json([]);
        }

        $raw = File::get($path);
        // Convert from UTF-16 (file) to UTF-8 for correct parsing
        $utf8 = mb_convert_encoding($raw, 'UTF-8', 'UTF-16');
        $lines = preg_split('/\r\n|\n|\r/', $utf8);

        $rows = [];
        $header = null;
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
            foreach ($header as $i => $h) {
                $key = strtolower(trim(preg_replace('/[^a-z0-9_]+/i', '_', $h)));
                $obj[$key] = isset($cols[$i]) ? trim($cols[$i]) : '';
            }
            // Map mode and trip type from CSV fields
            $obj['mode'] = strtolower($obj['mode_of_transportation'] ?? $obj['airline'] ?? '');
            if (! empty($obj['mode']) && str_contains($obj['mode'], 'airline')) {
                $obj['mode'] = 'airline';
            } elseif (! empty($obj['mode']) && str_contains($obj['mode'], 'ferry')) {
                $obj['mode'] = 'ferry';
            }
            $obj['trip_type'] = '';
            if (isset($obj['trip_type']) && trim($obj['trip_type']) !== '') {
                $obj['trip_type'] = strtolower(trim($obj['trip_type']));
                if (str_contains($obj['trip_type'], 'round')) {
                    $obj['trip_type'] = 'round_trip';
                } elseif (str_contains($obj['trip_type'], 'one')) {
                    $obj['trip_type'] = 'one_way';
                }
            }

            // Parse available_dates into an array of ISO dates where possible
            $obj['available_dates_parsed'] = [];
            if (! empty($obj['available_dates']) && ! preg_match('/not\s*specified/i', $obj['available_dates'])) {
                $raw = $obj['available_dates'];
                $parts = preg_split('/[;,|\/]+/', $raw);
                foreach ($parts as $p) {
                    $p = trim($p);
                    if ($p === '') continue;
                    $ts = strtotime($p);
                    if ($ts !== false) {
                        $iso = date('Y-m-d', $ts);
                        if (! in_array($iso, $obj['available_dates_parsed'], true)) {
                            $obj['available_dates_parsed'][] = $iso;
                        }
                    }
                }
            }
            $obj['departure_date'] = $obj['available_dates_parsed'][0] ?? null;

            // Parse duration into days from explicit field or from the duration text
            $obj['duration_days'] = null;
            if (! empty($obj['duration_days']) && is_numeric($obj['duration_days'])) {
                $obj['duration_days'] = intval($obj['duration_days']);
            } elseif (! empty($obj['duration'])) {
                if (preg_match('/(\d+)\s*[dD]/', $obj['duration'], $m)) {
                    $obj['duration_days'] = intval($m[1]);
                } elseif (preg_match('/(\d+)\s*day/i', $obj['duration'], $m2)) {
                    $obj['duration_days'] = intval($m2[1]);
                }
            }
            $rows[] = $obj;
        }

        return response()->json($rows);
    }
}
