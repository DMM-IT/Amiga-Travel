<?php
$path = realpath('travel_packages_summary_MERGED.csv.txt');
if (!file_exists($path)) {
    echo "missing\n";
    exit(1);
}
$raw = file_get_contents($path);
$utf8 = mb_convert_encoding($raw, 'UTF-8', 'UTF-16');
$lines = preg_split('/\r\n|\n|\r/', $utf8);
$header = null;
$rows = [];
foreach ($lines as $line) {
    if (trim($line) === '') continue;
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
    if (stripos($obj['tour_name'] ?? '', 'xiamen') !== false) {
        $rows[] = $obj;
        if (count($rows) >= 3) break;
    }
}
print_r(array_slice($header, 0, 60));
echo "---\n";
print_r($rows);
