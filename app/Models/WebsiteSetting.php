<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = ['page', 'hero_images', 'content', 'booking_cards', 'header_data', 'footer_data', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'hero_images' => 'array',
        'content' => 'array',
        'booking_cards' => 'array',
        'header_data' => 'array',
        'footer_data' => 'array',
    ];

    const PAGES = [
        'header' => 'Header',
        'footer' => 'Footer',
        'home' => 'Home',
        'about' => 'About',
        'gallery' => 'Gallery',
        'services' => 'Services',
        'tour_package' => 'Tour Package',
        'schedules' => 'Schedules',
        'contact_us' => 'Contact Us',
        'faqs' => 'FAQs',
        'download' => 'Download',
    ];

    const BOOKING_CARD_NAMES = [
        '2GO Ferry' => '2GO Ferry',
        'Starlite Ferry' => 'Starlite Ferry',
        'Air Asia' => 'Air Asia',
        'Cebu Pacific' => 'Cebu Pacific',
        'Philippine Airlines' => 'Philippine Airlines',
        'Travel With Us' => 'Travel With Us',
    ];

    public static function getPageOptions()
    {
        return self::PAGES;
    }

    public static function getBookingCardNames()
    {
        return self::BOOKING_CARD_NAMES;
    }

    public static function getOrCreateByPage($page)
    {
        return self::firstOrCreate(['page' => $page], [
            'page' => $page,
            'is_active' => true,
        ]);
    }
}
