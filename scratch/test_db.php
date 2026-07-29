<?php
try {
    $p = new PDO("mysql:host=sakura.proxy.rlwy.net;port=43993;dbname=railway", "root", "BIMPMSZRxyaizrljoaKdBoAixcTWShuP");
    echo "Connected successfully\n";
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage() . "\n";
}
