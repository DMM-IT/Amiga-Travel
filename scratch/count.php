<?php
$content = file_get_contents('c:/laragon/www/AMIGA/Amiga-Travel/resources/views/livewire/booking-form.blade.php');
$content = str_replace("\r\n", "\n", $content);
$lines = explode("\n", $content);
$stack = [];
foreach ($lines as $i => $line) {
    if ($i >= 143 && $i <= 658) {
        if (preg_match_all('/@(if|foreach|forelse)\b/', $line, $matches)) {
            foreach ($matches[1] as $m) {
                $stack[] = ($i + 1);
                echo "PUSH " . ($i + 1) . " (Depth: " . count($stack) . ")\n";
            }
        }
        if (preg_match_all('/@(endif|endforeach|endforelse)\b/', $line, $matches)) {
            foreach ($matches[1] as $m) {
                if (!empty($stack)) {
                    $popped = array_pop($stack);
                    echo "POP " . ($i + 1) . " matched with $popped (Depth: " . count($stack) . ")\n";
                }
            }
        }
    }
}



