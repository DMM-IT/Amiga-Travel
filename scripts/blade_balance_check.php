<?php
$path = $argv[1] ?? 'resources/views/livewire/booking-form.blade.php';
$contents = file_get_contents($path);
$lines = preg_split('/\r?\n/', $contents);
$openers = ['if'=>'endif','foreach'=>'endforeach','forelse'=>'endforelse','isset'=>'endisset','unless'=>'endunless','section'=>'endsection','auth'=>'endauth','guest'=>'endguest'];
$closers = array_flip($openers);
$stack = [];
foreach ($lines as $i => $line) {
    // find directives
    if (preg_match_all('/@(if|foreach|forelse|isset|unless|section|auth|guest)\b/', $line, $m, PREG_SET_ORDER)) {
        foreach ($m as $mm) {
            $stack[] = ['type'=>$mm[1], 'line'=>$i+1, 'text'=>trim($line)];
        }
    }
    if (preg_match_all('/@(endif|endforeach|endforelse|endisset|endunless|endsection|endauth|endguest)\b/', $line, $m2, PREG_SET_ORDER)) {
        foreach ($m2 as $mm) {
            $closer = substr($mm[1], 3); // remove 'end'
            // handle endforelse -> forelse
            $closer = $closer === 'forelse' ? 'forelse' : $closer;
            // find last matching opener
            $found = false;
            for ($s = count($stack)-1; $s >= 0; $s--) {
                if ($stack[$s]['type'] === $closer) {
                    array_splice($stack, $s, 1);
                    $found = true; break;
                }
            }
            if (! $found) {
                echo "Unmatched closer {$mm[1]} on line " . ($i+1) . "\n";
            }
        }
    }
    // handle @else and @elseif: they don't pop but require last opener to be 'if'
    if (preg_match_all('/@(else|elseif)\b/', $line, $m3, PREG_SET_ORDER)) {
        foreach ($m3 as $mm) {
            // ensure stack not empty and last opener is if
            $last = end($stack);
            if (! $last || $last['type'] !== 'if') {
                echo "@{$mm[1]} without open @if at line " . ($i+1) . "\n";
            }
        }
    }
}
if (count($stack) > 0) {
    echo "Unclosed directives (top to bottom):\n";
    foreach ($stack as $s) {
        echo "  @{$s['type']} opened at line {$s['line']}: {$s['text']}\n";
    }
} else {
    echo "All directives balanced.\n";
}
