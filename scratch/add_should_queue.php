<?php
$files = glob(__DIR__ . '/../app/Mail/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'implements ShouldQueue') === false) {
        $content = str_replace(
            'use Illuminate\Queue\SerializesModels;',
            "use Illuminate\Contracts\Queue\ShouldQueue;\nuse Illuminate\Queue\SerializesModels;",
            $content
        );
        
        $content = str_replace(
            'extends Mailable',
            'extends Mailable implements ShouldQueue',
            $content
        );
        
        file_put_contents($file, $content);
        echo "Updated " . basename($file) . "\n";
    }
}
