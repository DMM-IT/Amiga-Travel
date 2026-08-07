<?php
$s = App\Models\WebsiteSetting::where('page', 'faqs')->first();
if ($s && isset($s->content['faqs']) && !isset($s->content['faqs_list'])) {
    $c = $s->content;
    $c['faqs_list'] = $c['faqs'];
    unset($c['faqs']);
    $s->content = $c;
    $s->save();
    echo 'Fixed faqs_list';
} else {
    echo 'No fix needed';
}
