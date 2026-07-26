<?php
$text = file_get_contents(__DIR__ . '/../resources/views/livewire/booking-form.blade.php');
$pattern = '/(@if\b|@elseif\b|@else\b|@endif\b)/';
$stack = [];
$lines = explode("\n", $text);
foreach ($lines as $idx => $line) {
    if (preg_match_all($pattern, $line, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $tok = $m[1];
            $lineNumber = $idx + 1;
            if ($tok === '@if') {
                $stack[] = [$lineNumber, trim($line)];
            } elseif ($tok === '@endif') {
                if (count($stack) > 0) {
                    array_pop($stack);
                } else {
                    echo "UNMATCHED endif at {$lineNumber}: {$line}\n";
                }
            } elseif (in_array($tok, ['@else', '@elseif'], true)) {
                if (count($stack) === 0) {
                    echo "UNMATCHED {$tok} at {$lineNumber}: {$line}\n";
                }
            }
        }
    }
}
if (count($stack) > 0) {
    echo "OPEN_IF=" . count($stack) . "\n";
    foreach (array_slice($stack, -20) as $item) {
        echo "OPEN @if at line {$item[0]}: {$item[1]}\n";
    }
} else {
    echo "ALL MATCHED\n";
}
