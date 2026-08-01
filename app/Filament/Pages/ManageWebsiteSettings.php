<?php

namespace App\Filament\Pages;

use App\Models\WebsiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ManageWebsiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Website Settings';
    protected static ?string $title = 'Website Settings';
    protected static string $view = 'filament.pages.manage-website-settings';

    public ?string $currentPage = 'home';
    public ?array $settingsData = [];
    public bool $editMode = false;
    public string $activeSection = '';
    public int $iframeKey = 0;

    public function mount(): void
    {
        $this->currentPage = request('page', 'home');
        $this->loadSettings();
    }

    private function loadSettings(): void
    {
        $setting = WebsiteSetting::getOrCreateByPage($this->currentPage);

        if ($this->currentPage === 'faqs' && empty($setting->content)) {
            $setting->content = [
                'title' => 'Frequently Asked Questions',
                'description' => 'Find answers to common questions about bookings, policies, and our services.',
                'faqs' => [
                    ['question' => 'How can I book a ticket?', 'answer' => 'You can book a ticket online through our Schedules page or by visiting our branch offices.'],
                    ['question' => 'What is your refund policy?', 'answer' => 'Refund policies depend on the transport operator. Generally, cancellations made within 24 hours of departure are non-refundable.'],
                    ['question' => 'Do you offer custom tours?', 'answer' => 'Yes! We specialize in custom tours and educational packages. Contact us for a personalized quote.'],
                ]
            ];
            $setting->save();
        }

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
            $formData = [
                'page' => $setting->page,
                'hero_images' => $setting->hero_images ?? [],
                'content' => $setting->content ?? [],
                'header_data' => $setting->header_data ?? [],
                'footer_data' => $setting->footer_data ?? [],
                'is_active' => $setting->is_active ?? true,
            ];

            if ($this->currentPage === 'home') {
                $formData['booking_cards'] = $setting->booking_cards ?? $this->getDefaultBookingCards();
            }

            $this->form->fill($formData);
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

    public function getPageContentSchema(): array
    {
        if ($this->currentPage === 'services') {
            return [
                Section::make('Services Cards')->collapsible()
                    ->description('Edit the service card content only')
                    ->schema([
                        Repeater::make('content.travel_service_cards')
                            ->label('Travel & Booking Services Cards')
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
                                TextInput::make('link')
                                    ->label('Card Link')
                                    ->maxLength(255),
                                TextInput::make('color')
                                    ->label('Color Class')
                                    ->helperText('Add a Tailwind text color class such as text-pink-600 or text-emerald-700'),
                            ])
                            ->columns(1),

                        Repeater::make('content.service_cards')
                            ->label('Specialized Service Cards')
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
        }

        return [
            Section::make('Page Content')->collapsible()
                ->schema([
                    TextInput::make('content.badge')->label('Page Badge')->maxLength(100),
                    TextInput::make('content.title')->label('Page Title')->maxLength(255),
                    Textarea::make('content.description')->label('Page Description')->rows(4)->maxLength(500),
                    Repeater::make('content.items')
                        ->label('Items')
                        ->schema([
                            TextInput::make('title')->label('Item Title')->maxLength(120),
                            Textarea::make('description')->label('Item Description')->rows(2)->maxLength(255),
                        ])
                        ->columns(1),
                ]),
        ];
    }

    public function form(Form $form): Form
    {
        if ($this->currentPage === 'header') {
            return $form
                ->schema([
                    Section::make('Header Configuration')->collapsible()
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
                    Section::make('Footer Configuration')->collapsible()
                        ->description('Manage footer content visible on all pages')
                        ->schema([
                            TextInput::make('footer_data.tagline')
                                ->label('Footer Tagline')
                                ->default('Kay Amiga Hassle Free Ka!')
                                ->maxLength(255),
                            Textarea::make('footer_data.about')
                                ->label('About Text')
                                ->rows(3)
                                ->columnSpanFull(),
                            Repeater::make('footer_data.social_links')
                                ->label('Social Media Links')
                                ->schema([
                                    TextInput::make('platform')->label('Platform (Facebook, Instagram, etc.)')->required(),
                                    TextInput::make('url')->label('URL')->url()->required(),
                                ])
                                ->columnSpanFull(),
                            Toggle::make('is_active')->label('Active')->default(true),
                        ]),
                ])
                ->statePath('settingsData');
        } else {
            return $form
                ->schema([
                    Tabs::make('Page Settings')
                        ->tabs([
                            Tabs\Tab::make('Promotion & Hero')
                                ->schema([
                                    Section::make('Promotion Carousel')->collapsible()
                                        ->description('Upload images for the promotion carousel section')
                                        ->schema([
                                            FileUpload::make('hero_images')
                                                ->label('Carousel Images')
                                                ->multiple()
                                                ->image()
                                                ->reorderable()
                                                ->directory('website-settings/promotions'),
                                        ]),
                                ])
                                ->visible(fn () => $this->currentPage === 'home'),

                            Tabs\Tab::make('Booking Cards')
                                ->schema([
                                    Section::make('Travel Booking Options')->collapsible()
                                        ->description('Manage the 6 booking cards displayed on home page')
                                        ->schema([
                                            Repeater::make('booking_cards')
                                                ->label('Booking Cards')
                                                ->addable(false)
                                                ->deletable(false)
                                                ->collapsible()
                                                ->collapsed(false)
                                                ->schema([
                                                    TextInput::make('title')->label('Card Title')->required()->maxLength(100),
                                                    Textarea::make('description')->label('Card Description')->rows(2)->maxLength(255),
                                                    FileUpload::make('image')->label('Card Image')->image()->directory('website-settings/booking-cards'),
                                                ])
                                                ->columns(1),
                                        ]),
                                ])
                                ->visible(fn () => $this->currentPage === 'home'),

                            Tabs\Tab::make('Page Content')
                                ->schema($this->getPageContentSchema())
                                ->visible(fn () => $this->currentPage !== 'home'),

                            Tabs\Tab::make('SEO & Sharing')
                                ->schema([
                                    Section::make('Search Engine Metadata')->collapsible()
                                        ->description('Update the page metadata used for search engines and social sharing.')
                                        ->schema([
                                            TextInput::make('content.meta_title')->label('Meta title')->maxLength(70),
                                            Textarea::make('content.meta_description')->label('Meta description')->rows(3)->maxLength(170),
                                            FileUpload::make('content.meta_image')->label('Meta image')->image()->directory('website-settings/meta'),
                                        ]),
                                ])
                                ->visible(fn () => $this->currentPage !== 'home'),

                            Tabs\Tab::make('Settings')
                                ->schema([
                                    Section::make('Page Settings')->collapsible()
                                        ->schema([
                                            Toggle::make('is_active')->label('Active')->default(true),
                                            TextInput::make('content.page_subtitle')->label('Page subtitle')->maxLength(120),
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

            Notification::make()->success()->title('Settings saved')->body("Website settings for {$setting->page} page have been updated successfully.")->send();

            $this->redirect(route('filament.admin.pages.manage-website-settings', ['page' => $this->currentPage]));
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Error')->body($e->getMessage())->send();
        }
    }

    public function toggleEditMode(): void
    {
        $this->editMode = !$this->editMode;
        if (!$this->editMode) {
            $this->activeSection = '';
        }
    }

    public function setActiveSection(string $section): void
    {
        $this->activeSection = $section;
    }

    public function closePanel(): void
    {
        $this->activeSection = '';
    }

    public function saveSectionDirect(): void
    {
        try {
            $setting = WebsiteSetting::getOrCreateByPage($this->currentPage);
            $data = $this->settingsData ?? [];
            $updateData = [];
            foreach (['content', 'hero_images', 'header_data', 'footer_data', 'booking_cards', 'is_active'] as $field) {
                if (array_key_exists($field, $data)) {
                    $updateData[$field] = $data[$field];
                }
            }
            $setting->update($updateData);
            $this->iframeKey++;
            $this->dispatch('refresh-preview');
            Notification::make()->success()->title('Saved!')->body('Changes have been published to the website.')->send();
        } catch (\Exception $e) {
            Notification::make()->danger()->title('Save failed')->body($e->getMessage())->send();
        }
    }

    public function removeHeroImage(int $index): void
    {
        $images = $this->settingsData['hero_images'] ?? [];
        array_splice($images, $index, 1);
        $this->settingsData['hero_images'] = array_values($images);
    }

    public function addFaq(): void
    {
        $faqs = $this->settingsData['content']['faqs'] ?? [];
        $faqs[] = ['question' => '', 'answer' => ''];
        $this->settingsData['content']['faqs'] = $faqs;
    }

    public function removeFaq(int $index): void
    {
        $faqs = $this->settingsData['content']['faqs'] ?? [];
        array_splice($faqs, $index, 1);
        $this->settingsData['content']['faqs'] = array_values($faqs);
    }

    public function addQuickFact(): void
    {
        $facts = $this->settingsData['content']['quick_facts'] ?? [];
        $facts[] = ['label' => '', 'value' => ''];
        $this->settingsData['content']['quick_facts'] = $facts;
    }

    public function removeQuickFact(int $index): void
    {
        $facts = $this->settingsData['content']['quick_facts'] ?? [];
        array_splice($facts, $index, 1);
        $this->settingsData['content']['quick_facts'] = array_values($facts);
    }

    public function addSocialLink(string $type = 'footer'): void
    {
        if ($type === 'contact') {
            $links = $this->settingsData['content']['social_links'] ?? [];
            $links[] = ['platform' => '', 'url' => ''];
            $this->settingsData['content']['social_links'] = $links;
        } else {
            $links = $this->settingsData['footer_data']['social_links'] ?? [];
            $links[] = ['platform' => '', 'url' => ''];
            $this->settingsData['footer_data']['social_links'] = $links;
        }
    }

    public function removeSocialLink(int $index, string $type = 'footer'): void
    {
        if ($type === 'contact') {
            $links = $this->settingsData['content']['social_links'] ?? [];
            array_splice($links, $index, 1);
            $this->settingsData['content']['social_links'] = array_values($links);
        } else {
            $links = $this->settingsData['footer_data']['social_links'] ?? [];
            array_splice($links, $index, 1);
            $this->settingsData['footer_data']['social_links'] = array_values($links);
        }
    }

    public function syncPage(string $path): void
    {
        $map = [
            '/' => 'home',
            '/about' => 'about',
            '/gallery' => 'gallery',
            '/services' => 'services',
            '/tour-package' => 'tour_package',
            '/schedules' => 'schedules',
            '/contact-us' => 'contact_us',
            '/download' => 'download',
            '/faqs' => 'faqs',
        ];

        $cleanPath = rtrim($path, '/') ?: '/';

        if (isset($map[$cleanPath])) {
            $key = $map[$cleanPath];
            if ($this->currentPage !== $key && !in_array($this->currentPage, ['header', 'footer'])) {
                $this->redirect(route('filament.admin.pages.manage-website-settings', ['page' => $key]));
            }
        }
    }

    public function getPreviewUrl(): string
    {
        $map = [
            'home'       => '/',
            'about'      => '/about',
            'gallery'    => '/gallery',
            'services'   => '/services',
            'tour_package' => '/tour-package',
            'schedules'  => '/schedules',
            'contact_us' => '/contact-us',
            'download'   => '/download',
            'faqs'       => '/faqs',
            'header'     => '/',
            'footer'     => '/',
        ];
        return url($map[$this->currentPage] ?? '/');
    }

    public function getPageSections(): array
    {
        return match ($this->currentPage) {
            'home' => [
                'nav_links' => [
                    'label' => 'Navigation (Locked)',
                    'icon'  => '🔒',
                    'color' => 'slate',
                    'description' => 'Navigation links are part of the template',
                    'locked' => true,
                ],
                'promotion_images' => [
                    'label' => 'Promotion Images',
                    'icon'  => '🖼',
                    'color' => 'blue',
                    'description' => 'Carousel images on the left column',
                ],
            ],
            'header' => [
                'header_config' => [
                    'label' => 'Header Configuration',
                    'icon'  => '🏷',
                    'color' => 'amber',
                    'description' => 'Logo, company name, phone, and email',
                ],
            ],
            'footer' => [
                'footer_config' => [
                    'label' => 'Footer Content',
                    'icon'  => '🔗',
                    'color' => 'slate',
                    'description' => 'About text, contact info, social links',
                ],
            ],
            'about' => [
                'about_content' => [
                    'label' => 'About Content',
                    'icon'  => '📝',
                    'color' => 'emerald',
                    'description' => 'Page title and main description',
                ],
            ],
            'services' => [
                'services_header' => [
                    'label' => 'Services Header',
                    'icon'  => '⚙️',
                    'color' => 'emerald',
                    'description' => 'Page title, description, and CTA',
                ],
                'service_cards' => [
                    'label' => 'Service Cards',
                    'icon'  => '🃏',
                    'color' => 'blue',
                    'description' => 'Individual service card content',
                ],
            ],
            default => [],
        };
    }

    public function getFormStatePath(): ?string
    {
        return 'settingsData';
    }
}
