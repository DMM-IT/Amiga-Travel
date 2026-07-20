<?php

namespace App\Filament\Pages;

use App\Models\WebsiteSetting;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManageWebsiteSettings extends Page implements HasForms
{
    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('manage_website_settings');
    }

    private function getPageContentSchema(): array
    {
        // Provide different form fields per page to allow page-specific content
        switch ($this->currentPage) {
            case 'about':
                return [
                    Section::make('About Page')
                        ->description('Main content for the About page')
                        ->schema([
                            TextInput::make('content.title')
                                ->label('Page Title')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('content.description')
                                ->label('Page Description')
                                ->rows(5)
                                ->columnSpanFull(),
                        ]),
                    Section::make('Quick Facts')
                        ->description('Update the quick facts shown on the about page')
                        ->schema([
                            Repeater::make('content.quick_facts')
                                ->label('Quick Facts')
                                ->schema([
                                    TextInput::make('label')
                                        ->label('Fact Label')
                                        ->required()
                                        ->maxLength(100),
                                    Textarea::make('value')
                                        ->label('Fact Value')
                                        ->required()
                                        ->rows(2)
                                        ->maxLength(255),
                                ])
                                ->columns(1)
                                ->defaultItems(fn () => [
                                    ['label' => 'Established', 'value' => 'July 2017 in Oriental Mindoro'],
                                    ['label' => 'Key Partnerships', 'value' => '2GO and Starlite Ferries'],
                                    ['label' => 'Specialty', 'value' => 'Ferry bookings, Educational tours, Apprenticeship programs'],
                                ]),
                        ]),
                ];

            case 'gallery':
                return [
                    Section::make('Gallery Page')
                        ->description('Manage gallery header and image cards')
                        ->schema([
                            TextInput::make('content.badge')
                                ->label('Page Badge')
                                ->default('Gallery')
                                ->maxLength(100),
                            TextInput::make('content.title')
                                ->label('Page Title')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('content.description')
                                ->label('Page Description')
                                ->rows(4)
                                ->maxLength(255),
                            Repeater::make('content.gallery_items')
                                ->label('Gallery Items')
                                ->schema([
                                    FileUpload::make('image')
                                        ->label('Image')
                                        ->image()
                                        ->directory('website-settings/gallery'),
                                    TextInput::make('alt')
                                        ->label('Alt Text')
                                        ->maxLength(255),
                                    TextInput::make('label')
                                        ->label('Badge Label')
                                        ->maxLength(50),
                                    TextInput::make('title')
                                        ->label('Title')
                                        ->required()
                                        ->maxLength(120),
                                    TextInput::make('description')
                                        ->label('Description')
                                        ->maxLength(255),
                                    TextInput::make('caption')
                                        ->label('Caption')
                                        ->maxLength(255),
                                ])
                                ->columns(1),
                        ]),
                ];

            case 'services':
                return [
                    Section::make('Services Page')
                        ->description('Manage the service header and service cards')
                        ->schema([
                            TextInput::make('content.badge')
                                ->label('Page Badge')
                                ->default('Services')
                                ->maxLength(100),
                            TextInput::make('content.title')
                                ->label('Page Title')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('content.description')
                                ->label('Page Description')
                                ->rows(4)
                                ->maxLength(255),
                            Section::make('Service CTA')
                                ->schema([
                                    TextInput::make('content.service_cta.badge')
                                        ->label('CTA Badge')
                                        ->maxLength(100),
                                    TextInput::make('content.service_cta.title')
                                        ->label('CTA Title')
                                        ->maxLength(255),
                                    Textarea::make('content.service_cta.description')
                                        ->label('CTA Description')
                                        ->rows(3)
                                        ->maxLength(255),
                                    TextInput::make('content.service_cta.button_text')
                                        ->label('CTA Button Text')
                                        ->maxLength(50),
                                ]),
                            Repeater::make('content.service_cards')
                                ->label('Service Cards')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Card Title')
                                        ->required()
                                        ->maxLength(120),
                                    Textarea::make('description')
                                        ->label('Card Description')
                                        ->rows(3)
                                        ->maxLength(255),
                                    TextInput::make('note')
                                        ->label('Note Text')
                                        ->maxLength(80),
                                    TextInput::make('button_text')
                                        ->label('Button Text')
                                        ->maxLength(50),
                                    TextInput::make('button_link')
                                        ->label('Button Link')
                                        ->url(),
                                    TextInput::make('color')
                                        ->label('Color Class')
                                        ->helperText('Add a Tailwind text color class such as text-pink-600 or text-emerald-700'),
                                ])
                                ->columns(1),
                        ]),
                ];

            case 'tour_package':
                return [
                    Section::make('Tour Packages Page')
                        ->description('Manage the header and package sections for tour packages')
                        ->schema([
                            TextInput::make('content.badge')
                                ->label('Page Badge')
                                ->default('Tour Packages')
                                ->maxLength(100),
                            TextInput::make('content.title')
                                ->label('Page Title')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('content.description')
                                ->label('Page Description')
                                ->rows(4)
                                ->maxLength(255),
                            Tabs::make('Tour Packages')
                                ->tabs([
                                    Tabs\Tab::make('Domestic')
                                        ->schema([
                                            Repeater::make('content.tour_packages.domestic')
                                                ->label('Domestic Packages')
                                                ->schema([
                                                    FileUpload::make('image')
                                                        ->label('Image')
                                                        ->image()
                                                        ->directory('website-settings/tour-packages'),
                                                    TextInput::make('alt')
                                                        ->label('Image Alt Text')
                                                        ->maxLength(100),
                                                    TextInput::make('label')
                                                        ->label('Tag Label')
                                                        ->maxLength(50),
                                                    TextInput::make('title')
                                                        ->label('Package Title')
                                                        ->required()
                                                        ->maxLength(120),
                                                    TextInput::make('subtitle')
                                                        ->label('Subtitle')
                                                        ->maxLength(120),
                                                    Textarea::make('description')
                                                        ->label('Description')
                                                        ->rows(3)
                                                        ->maxLength(255),
                                                    TextInput::make('price')
                                                        ->label('Price')
                                                        ->maxLength(60),
                                                    TextInput::make('button_text')
                                                        ->label('Button Text')
                                                        ->maxLength(50),
                                                    TextInput::make('button_link')
                                                        ->label('Button Link')
                                                        ->url(),
                                                ])
                                                ->columns(1),
                                        ]),
                                    Tabs\Tab::make('International')
                                        ->schema([
                                            Repeater::make('content.tour_packages.international')
                                                ->label('International Packages')
                                                ->schema([
                                                    FileUpload::make('image')
                                                        ->label('Image')
                                                        ->image()
                                                        ->directory('website-settings/tour-packages'),
                                                    TextInput::make('alt')
                                                        ->label('Image Alt Text')
                                                        ->maxLength(100),
                                                    TextInput::make('label')
                                                        ->label('Tag Label')
                                                        ->maxLength(50),
                                                    TextInput::make('title')
                                                        ->label('Package Title')
                                                        ->required()
                                                        ->maxLength(120),
                                                    TextInput::make('subtitle')
                                                        ->label('Subtitle')
                                                        ->maxLength(120),
                                                    Textarea::make('description')
                                                        ->label('Description')
                                                        ->rows(3)
                                                        ->maxLength(255),
                                                    TextInput::make('price')
                                                        ->label('Price')
                                                        ->maxLength(60),
                                                    TextInput::make('button_text')
                                                        ->label('Button Text')
                                                        ->maxLength(50),
                                                    TextInput::make('button_link')
                                                        ->label('Button Link')
                                                        ->url(),
                                                ])
                                                ->columns(1),
                                        ]),
                                ]),
                            Repeater::make('content.supported_destinations')
                                ->label('Supported Destination Groups')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Group Title')
                                        ->required()
                                        ->maxLength(120),
                                    Repeater::make('destinations')
                                        ->label('Destinations')
                                        ->schema([
                                            TextInput::make('name')
                                                ->label('Destination Name')
                                                ->required()
                                                ->maxLength(120),
                                        ])
                                        ->columns(1),
                                ])
                                ->columns(1),
                        ]),
                ];

            case 'download':
                return [
                    Section::make('Download Page')
                        ->description('Manage the download page content and app install steps')
                        ->schema([
                            TextInput::make('content.title')
                                ->label('Page Title')
                                ->required()
                                ->maxLength(255),
                            Textarea::make('content.description')
                                ->label('Page Description')
                                ->rows(4)
                                ->maxLength(255),
                            Repeater::make('content.download_steps')
                                ->label('Download Steps')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Step Title')
                                        ->required()
                                        ->maxLength(120),
                                    Textarea::make('description')
                                        ->label('Step Description')
                                        ->rows(3)
                                        ->maxLength(255),
                                ])
                                ->columns(1),
                            Repeater::make('content.download_features')
                                ->label('Download Features')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Feature Title')
                                        ->required()
                                        ->maxLength(120),
                                    Textarea::make('description')
                                        ->label('Feature Description')
                                        ->rows(3)
                                        ->maxLength(255),
                                ])
                                ->columns(1),
                            TextInput::make('content.how_it_works_label')
                                ->label('How It Works Label')
                                ->maxLength(80),
                            TextInput::make('content.how_it_works_title')
                                ->label('How It Works Title')
                                ->maxLength(255),
                            Textarea::make('content.how_it_works_description')
                                ->label('How It Works Description')
                                ->rows(3)
                                ->maxLength(255),
                        ]),
                ];

            case 'contact_us':
                return [
                    Section::make('Contact Information')
                        ->description('Contact details shown on Contact Us page')
                        ->schema([
                            TextInput::make('content.title')
                                ->label('Page Title')
                                ->default('Get in Touch')
                                ->maxLength(255),
                            Textarea::make('content.description')
                                ->label('Page Description')
                                ->rows(4)
                                ->maxLength(255),
                            TextInput::make('content.contact_name')
                                ->label('Contact Name')
                                ->placeholder('Amiga Travel Support'),
                            TextInput::make('content.phone')
                                ->label('Phone Number')
                                ->tel(),
                            TextInput::make('content.email')
                                ->label('Email Address')
                                ->email(),
                            Textarea::make('content.address')
                                ->label('Address')
                                ->rows(3)
                                ->columnSpanFull(),
                            Textarea::make('content.map_embed')
                                ->label('Map Embed (iframe)')
                                ->rows(4)
                                ->helperText('Paste iframe embed code for map if available.'),
                            Repeater::make('content.social_links')
                                ->label('Social Media Links')
                                ->schema([
                                    TextInput::make('platform')
                                        ->label('Platform')
                                        ->required()
                                        ->maxLength(100),
                                    TextInput::make('url')
                                        ->label('URL')
                                        ->url()
                                        ->required(),
                                ])
                                ->columns(1),
                        ]),
                ];

            default:
                return [
                    Section::make('Page Information')
                        ->description('Main content for this page')
                        ->schema([
                            FileUpload::make('content.hero_image')
                                ->label('Hero/Banner Image')
                                ->image()
                                ->directory('website-settings/pages'),
                            Textarea::make('content.title')
                                ->label('Page Title')
                                ->rows(2)
                                ->maxLength(255),
                            Textarea::make('content.description')
                                ->label('Page Description/Content')
                                ->rows(5)
                                ->columnSpanFull(),
                        ]),
                ];
        }
    }
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Website Settings';

    protected static ?string $title = 'Website Settings';

    protected static string $view = 'filament.pages.manage-website-settings';

    public ?string $currentPage = 'home';

    public ?array $settingsData = [];

    public function mount(): void
    {
        $this->currentPage = request('page', 'home');
        $this->loadSettings();
    }

    private function loadSettings(): void
    {
        $setting = WebsiteSetting::getOrCreateByPage($this->currentPage);
        
        if ($this->currentPage === 'header') {
            $this->form->fill([
                'header_data' => $setting->header_data ?? [],
                'is_active' => $setting->is_active ?? true,
            ]);
        } elseif ($this->currentPage === 'footer') {
            $this->form->fill([
                'footer_data' => $setting->footer_data ?? [],
                'is_active' => $setting->is_active ?? true,
            ]);
        } else {
            $this->form->fill([
                'page' => $setting->page,
                'hero_images' => $setting->hero_images ?? [],
                'content' => $setting->content ?? [],
                'booking_cards' => $setting->booking_cards ?? $this->getDefaultBookingCards(),
                'header_data' => $setting->header_data ?? [],
                'footer_data' => $setting->footer_data ?? [],
                'is_active' => $setting->is_active ?? true,
            ]);
        }
    }

    private function getDefaultBookingCards(): array
    {
        return [
            ['title' => 'BOOK YOUR 2GO FERRY TICKET NOW', 'description' => 'Kasiyahan po namin ang paglingkuran kayo.', 'image' => null],
            ['title' => 'BOOK YOUR STARLITE FERRY TICKET NOW', 'description' => 'Kasiyahan po namin ang paglingkuran kayo.', 'image' => null],
            ['title' => 'BOOK YOUR AIR ASIA TICKET NOW', 'description' => 'Kasiyahan po namin ang paglingkuran kayo.', 'image' => null],
            ['title' => 'BOOK YOUR CEBU PACIFIC TICKET NOW', 'description' => 'Kasiyahan po namin ang paglingkuran kayo.', 'image' => null],
            ['title' => 'BOOK YOUR PHILIPPINE AIRLINE TICKET NOW', 'description' => 'Kasiyahan po namin ang paglingkuran kayo.', 'image' => null],
            ['title' => 'BOOK YOUR TRAVEL WITH US NOW', 'description' => 'Kasiyahan po namin ang paglingkuran kayo.', 'image' => null],
        ];
    }

    public function form(Form $form): Form
    {
        if ($this->currentPage === 'header') {
            return $form
                ->schema([
                    Section::make('Header Configuration')
                        ->description('Manage header content visible on all pages')
                        ->schema([
                            FileUpload::make('header_data.logo')
                                ->label('Logo')
                                ->image()
                                ->directory('website-settings/header'),
                            TextInput::make('header_data.company_name')
                                ->label('Company Name')
                                ->placeholder('Amiga Gracia')
                                ->required(),
                            TextInput::make('header_data.phone')
                                ->label('Phone Number')
                                ->tel()
                                ->placeholder('+63 (XXX) XXX-XXXX'),
                            TextInput::make('header_data.email')
                                ->label('Email Address')
                                ->email()
                                ->placeholder('info@amiga-travel.com'),
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ]),
                ])
                ->statePath('settingsData');
        } elseif ($this->currentPage === 'footer') {
            return $form
                ->schema([
                    Section::make('Footer Configuration')
                        ->description('Manage footer content visible on all pages')
                        ->schema([
                            Textarea::make('footer_data.about')
                                ->label('About Text')
                                ->rows(3)
                                ->columnSpanFull(),
                            TextInput::make('footer_data.phone')
                                ->label('Phone Number')
                                ->tel(),
                            TextInput::make('footer_data.email')
                                ->label('Email Address')
                                ->email(),
                            TextInput::make('footer_data.address')
                                ->label('Address')
                                ->columnSpanFull(),
                            TextInput::make('footer_data.website')
                                ->label('Website')
                                ->url(),
                            TextInput::make('footer_data.app_version')
                                ->label('App Version')
                                ->placeholder('1.0.0')
                                ->helperText('Optional application version shown in the site footer.'),
                            Repeater::make('footer_data.social_links')
                                ->label('Social Media Links')
                                ->schema([
                                    TextInput::make('platform')
                                        ->label('Platform (Facebook, Instagram, etc.)')
                                        ->required(),
                                    TextInput::make('url')
                                        ->label('URL')
                                        ->url()
                                        ->required(),
                                ])
                                ->columnSpanFull(),
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ]),
                ])
                ->statePath('settingsData');
        } else {
            // Content pages: Home, About, Gallery, etc.
            return $form
                ->schema([
                    Tabs::make('Page Settings')
                        ->tabs([
                            // Promotion/Hero Tab (only for home)
                            Tabs\Tab::make('Promotion & Hero')
                                ->schema([
                                    Section::make('Promotion Carousel')
                                        ->description('Upload images for the promotion carousel section')
                                        ->schema([
                                            FileUpload::make('hero_images')
                                                ->label('Carousel Images')
                                                ->multiple()
                                                ->image()
                                                ->reorderable()
                                                ->appendFiles()
                                                ->directory('website-settings/promotions'),
                                        ]),
                                ])
                                ->visible(fn () => $this->currentPage === 'home'),

                            // Booking Cards Tab (only for home)
                            Tabs\Tab::make('Booking Cards')
                                ->schema([
                                    Section::make('Travel Booking Options')
                                        ->description('Manage the 6 booking cards displayed on home page')
                                        ->schema([
                                            Repeater::make('booking_cards')
                                                ->label('Booking Cards')
                                                ->addable(false)
                                                ->deletable(false)
                                                ->collapsible()
                                                ->collapsed(false)
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->label('Card Title')
                                                        ->required()
                                                        ->maxLength(100),
                                                    Textarea::make('description')
                                                        ->label('Card Description')
                                                        ->rows(2)
                                                        ->maxLength(255),
                                                    FileUpload::make('image')
                                                        ->label('Card Image')
                                                        ->image()
                                                        ->directory('website-settings/booking-cards'),
                                                ])
                                                ->columns(1),
                                        ]),
                                ])
                                ->visible(fn () => $this->currentPage === 'home'),

                            // Page Content Tab (per-page schemas)
                            Tabs\Tab::make('Page Content')
                                ->schema($this->getPageContentSchema())
                                ->visible(fn () => $this->currentPage !== 'home'),

                            // Welcome Section Tab (only for home)
                            Tabs\Tab::make('Welcome Section')
                                ->schema([
                                    Section::make('Welcome Message')
                                        ->description('Customize the welcome text shown on home page')
                                        ->schema([
                                            TextInput::make('content.welcome_title')
                                                ->label('Welcome Title')
                                                ->default('Welcome to Amiga Gracia Travel Services')
                                                ->maxLength(255),
                                            Textarea::make('content.welcome_subtitle')
                                                ->label('Welcome Subtitle')
                                                ->rows(2)
                                                ->default('Ferry bookings, accommodations, and everything in between — made easy. What would you like to do today?')
                                                ->maxLength(255),
                                        ]),
                                    Section::make('Hero Cards')
                                        ->description('Edit the text for the two hero cards shown on the home page.')
                                        ->schema([
                                            TextInput::make('content.hero_card_title_1')
                                                ->label('Primary Card Title')
                                                ->default('Book a Trip')
                                                ->maxLength(100),
                                            Textarea::make('content.hero_card_description_1')
                                                ->label('Primary Card Description')
                                                ->rows(2)
                                                ->default('Start a new booking — choose your route, schedule, passengers, and accommodations.')
                                                ->maxLength(255),
                                            TextInput::make('content.hero_card_button_1')
                                                ->label('Primary Card Button Text')
                                                ->default('Get started →')
                                                ->maxLength(50),
                                            TextInput::make('content.hero_card_title_2')
                                                ->label('Secondary Card Title')
                                                ->default('Check My Booking')
                                                ->maxLength(100),
                                            Textarea::make('content.hero_card_description_2')
                                                ->label('Secondary Card Description')
                                                ->rows(2)
                                                ->default('Already booked? Enter your transaction number to view your booking details and status.')
                                                ->maxLength(255),
                                            TextInput::make('content.hero_card_button_2')
                                                ->label('Secondary Card Button Text')
                                                ->default('Check status →')
                                                ->maxLength(50),
                                        ]),
                                ])
                                ->visible(fn () => $this->currentPage === 'home'),

                            Tabs\Tab::make('SEO & Sharing')
                                ->schema([
                                    Section::make('Search Engine Metadata')
                                        ->description('Update the page metadata used for search engines and social sharing.')
                                        ->schema([
                                            TextInput::make('content.meta_title')
                                                ->label('Meta title')
                                                ->maxLength(70),
                                            Textarea::make('content.meta_description')
                                                ->label('Meta description')
                                                ->rows(3)
                                                ->maxLength(170),
                                            TextInput::make('content.meta_keywords')
                                                ->label('Meta keywords')
                                                ->helperText('Comma-separated keywords for SEO. Optional.'),
                                            FileUpload::make('content.meta_image')
                                                ->label('Meta image')
                                                ->image()
                                                ->directory('website-settings/meta'),
                                        ]),
                                    Section::make('Social Sharing')
                                        ->description('Optional social media preview content.')
                                        ->schema([
                                            TextInput::make('content.og_title')
                                                ->label('Open Graph title')
                                                ->maxLength(100),
                                            Textarea::make('content.og_description')
                                                ->label('Open Graph description')
                                                ->rows(3)
                                                ->maxLength(170),
                                            FileUpload::make('content.og_image')
                                                ->label('Open Graph image')
                                                ->image()
                                                ->directory('website-settings/meta'),
                                        ]),
                                ])
                                ->visible(fn () => $this->currentPage !== 'home'),

                            // Settings Tab
                            Tabs\Tab::make('Settings')
                                ->schema([
                                    Section::make('Page Settings')
                                        ->schema([
                                            Toggle::make('is_active')
                                                ->label('Active')
                                                ->default(true),
                                            TextInput::make('content.page_subtitle')
                                                ->label('Page subtitle')
                                                ->maxLength(120),
                                            TextInput::make('content.banner_cta')
                                                ->label('Banner CTA text')
                                                ->maxLength(50)
                                                ->helperText('Optional button text for hero/banner calls to action.'),
                                        ]),
                                ]),
                        ])
                        ->columnSpanFull(),
                ])
                ->statePath('settingsData');
        }
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            
            $setting = WebsiteSetting::getOrCreateByPage($this->currentPage);
            $setting->update($data);

            Notification::make()
                ->success()
                ->title('Settings saved')
                ->body("Website settings for {$setting->page} page have been updated successfully.")
                ->send();

            $this->redirect(route('filament.admin.pages.manage-website-settings', ['page' => $this->currentPage]));
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body($e->getMessage())
                ->send();
        }
    }

    public function getFormStatePath(): ?string
    {
        return 'settingsData';
    }
}
