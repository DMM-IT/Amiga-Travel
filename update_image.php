

$setting = \App\Models\WebsiteSetting::where('page', 'services')->first();
if ($setting) {
    $content = $setting->content;
    if (isset($content['travel_service_cards'])) {
        foreach ($content['travel_service_cards'] as &$card) {
            if ($card['title'] === 'Custom Travel Arrangements') {
                $card['image'] = 'images/amiga-logo-transparent.png';
            }
        }
        $setting->content = $content;
        $setting->save();
        echo 'Updated DB';
    } else {
        echo 'No travel_service_cards in DB';
    }
} else {
    echo 'No services setting in DB';
}
