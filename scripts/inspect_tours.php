<?php
$path = realpath(__DIR__ . '/../travel_packages_summary_MERGED.csv.txt');
if (! file_exists($path)) {
    echo json_encode(['error' => 'missing_file', 'path' => $path]);
    exit(0);
}
$raw = file_get_contents($path);
$utf8 = mb_convert_encoding($raw, 'UTF-8', 'UTF-16');
$lines = preg_split('/\r\n|\n|\r/', $utf8);
$header = null;
foreach ($lines as $line) {
    if (trim($line) === '') continue;
    $cols = str_getcsv($line, "\t");
    if ($header === null) { $header = $cols; continue; }
    $row = [];
    foreach ($header as $i => $h) {
        $key = strtolower(trim(preg_replace('/[^a-z0-9_]+/i', '_', $h)));
        $row[$key] = isset($cols[$i]) ? trim($cols[$i]) : '';
    }
    echo json_encode(['header' => $header, 'sample_row' => $row], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit(0);
}
echo json_encode(['error' => 'no_rows']);
