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
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Website Settings';

    protected static ?string $title = 'Website Settings';

    protected static ?int $navigationSort = 10;

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

                            // Page Content Tab
                            Tabs\Tab::make('Page Content')
                                ->schema([
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
                                ])
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
                                ])
                                ->visible(fn () => $this->currentPage === 'home'),

                            // Settings Tab
                            Tabs\Tab::make('Settings')
                                ->schema([
                                    Section::make('Page Settings')
                                        ->schema([
                                            Toggle::make('is_active')
                                                ->label('Active')
                                                ->default(true),
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
