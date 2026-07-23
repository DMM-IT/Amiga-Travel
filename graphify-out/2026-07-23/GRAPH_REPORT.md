# Graph Report - Amiga-Travel  (2026-07-23)

## Corpus Check
- 391 files · ~241,494 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 5129 nodes · 12359 edges · 290 communities (260 shown, 30 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1160 edges (avg confidence: 0.67)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `9c3a4188`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- HTTP Controllers & Routing (C0)
- Data Models & Domain (C1)
- HTTP Controllers & Routing (C2)
- Core Module 3
- Core Module 4
- Core Module 5
- Core Module 6
- Data Models & Domain (C7)
- Core Module 8
- Database Schema (C9)
- Core Module 10
- Database Schema (C11)
- Data Models & Domain (C12)
- Core Module 13
- Core Module 14
- Core Module 15
- Data Models & Domain (C16)
- Core Module 17
- HTTP Controllers & Routing (C18)
- HTTP Controllers & Routing (C19)
- Core Module 20
- Data Models & Domain (C21)
- Core Module 22
- Core Module 23
- Filament Admin & UI (C24)
- Data Models & Domain (C25)
- Data Models & Domain (C26)
- Core Module 27
- Core Module 28
- Database Schema (C29)
- Core Module 30
- Frontend & Components (C31)
- Core Module 32
- Core Module 33
- Data Models & Domain (C34)
- Data Models & Domain (C35)
- Core Module 36
- Filament Admin & UI (C37)
- Core Module 38
- Data Models & Domain (C39)
- HTTP Controllers & Routing (C40)
- Data Models & Domain (C41)
- Filament Admin & UI (C42)
- Core Module 43
- Core Module 44
- Core Module 45
- HTTP Controllers & Routing (C46)
- Core Module 47
- Data Models & Domain (C48)
- Data Models & Domain (C49)
- Data Models & Domain (C50)
- Core Module 51
- Data Models & Domain (C52)
- Filament Admin & UI (C53)
- Core Module 54
- HTTP Controllers & Routing (C55)
- Core Module 56
- Data Models & Domain (C57)
- Core Module 58
- Core Module 59
- Filament Admin & UI (C60)
- Data Models & Domain (C61)
- Core Module 62
- Data Models & Domain (C63)
- Data Models & Domain (C64)
- Core Module 65
- HTTP Controllers & Routing (C66)
- Core Module 67
- Database Schema (C68)
- Core Module 69
- Database Seeders & Testing (C70)
- Data Models & Domain (C71)
- Core Module 72
- HTTP Controllers & Routing (C73)
- Data Models & Domain (C74)
- Core Module 75
- Core Module 76
- Core Module 77
- Core Module 78
- Core Module 79
- HTTP Controllers & Routing (C80)
- Core Module 81
- Core Module 82
- Core Module 83
- Core Module 84
- Core Module 85
- Data Models & Domain (C86)
- Core Module 87
- Core Module 88
- Core Module 89
- Filament Admin & UI (C90)
- Core Module 91
- Core Module 92
- Core Module 93
- Data Models & Domain (C94)
- Filament Admin & UI (C95)
- Filament Admin & UI (C96)
- Core Module 97
- Core Module 98
- Core Module 99
- Filament Admin & UI (C100)
- Core Module 101
- Core Module 102
- Resource
- Filament Admin & UI (C106)
- Filament Admin & UI (C107)
- Core Module 108
- Core Module 109
- Core Module 110
- Core Module 111
- Core Module 112
- Core Module 113
- Core Module 114
- Core Module 115
- Transaction
- _each
- Database Seeders & Testing (C118)
- Core Module 119
- Core Module 120
- Core Module 121
- Core Module 171
- Core Module 172
- Core Module 173
- Data Models & Domain (C178)
- Core Module 179
- Core Module 181
- Core Module 186
- Core Module 199
- Core Module 222
- Core Module 224
- graphify reference: query, path, explain
- ManageProofs
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- qo
- PurgeExpiredProofs.php
- graphify reference: GitHub clone and cross-repo merge
- graphify reference: transcribe video and audio
- graphify reference: GitHub clone and cross-repo merge
- graphify reference: transcribe video and audio
- flutter_app
- graphify reference: GitHub clone and cross-repo merge
- graphify reference: transcribe video and audio
- rules/graphify.md
- workflows/graphify.md
- CLAUDE.md
- .claude/CLAUDE.md
- .claude/skills/graphify/references/extraction-spec.md
- .copilot/skills/graphify/references/extraction-spec.md
- LaunchImage.imageset/README.md
- copilot-instructions.md
- .github/skills/graphify/references/extraction-spec.md
- AdminMiddleware.php
- flutter_export_environment.sh
- TourResource.php
- VehicleBrandResource.php
- OverallReports
- BookingResource.php
- InquiryResource.php
- TransportClassResource.php
- BookingExportController
- How to Update the Android App (APK)

## God Nodes (most connected - your core abstractions)
1. `_update()` - 88 edges
2. `x()` - 85 edges
3. `_update()` - 84 edges
4. `te()` - 74 edges
5. `V()` - 72 edges
6. `BookingForm` - 69 edges
7. `Booking` - 69 edges
8. `draw()` - 55 edges
9. `vd()` - 53 edges
10. `Schedule` - 51 edges

## Surprising Connections (you probably didn't know these)
- `te()` --indirect_call--> `Pr()`  [INFERRED]
  public/js/filament/forms/components/markdown-editor.js → public/js/filament/filament/echo.js
- `getExtension()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/file-upload.js → public/js/filament/forms/components/markdown-editor.js
- `_getTestState()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/file-upload.js → public/js/filament/forms/components/markdown-editor.js
- `dt()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/rich-editor.js → public/js/filament/forms/components/markdown-editor.js
- `wWinMain()` --calls--> `CreateAndAttachConsole()`  [INFERRED]
  flutter_app/windows/runner/main.cpp → flutter_app/windows/runner/utils.cpp

## Import Cycles
- None detected.

## Communities (290 total, 30 thin omitted)

### Community 0 - "HTTP Controllers & Routing (C0)"
Cohesion: 0.01
Nodes (107): acquireContext(), addControllers(), addPlugins(), addScales(), afterDraw(), alpha(), beforeDatasetDraw(), beforeDatasetsDraw() (+99 more)

### Community 1 - "Data Models & Domain (C1)"
Cohesion: 0.01
Nodes (261): bool get, dart:async, dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex (+253 more)

### Community 2 - "HTTP Controllers & Routing (C2)"
Cohesion: 0.02
Nodes (127): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), canRedo(), canSyncDocumentView() (+119 more)

### Community 3 - "Core Module 3"
Cohesion: 0.04
Nodes (166): _a(), Aa(), Ac(), Ae(), af(), ai(), al(), An() (+158 more)

### Community 4 - "Core Module 4"
Cohesion: 0.02
Nodes (89): aa(), addControllers(), addPlugins(), addScales(), aspectRatio(), At(), beforeDatasetDraw(), beforeDatasetsDraw() (+81 more)

### Community 5 - "Core Module 5"
Cohesion: 0.04
Nodes (111): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), breakFormattedBlock(), breaksOnReturn() (+103 more)

### Community 6 - "Core Module 6"
Cohesion: 0.08
Nodes (67): [g](), [x](), $c(), D(), E(), Ea(), g(), H() (+59 more)

### Community 7 - "Data Models & Domain (C7)"
Cohesion: 0.04
Nodes (99): addBox(), addElements(), addEventListener(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions() (+91 more)

### Community 8 - "Core Module 8"
Cohesion: 0.06
Nodes (71): adjustHitBoxes(), ae(), cd(), _computeGridLineItems(), _computeLabelArea(), _computeTitleHeight(), cs(), df() (+63 more)

### Community 9 - "Database Schema (C9)"
Cohesion: 0.06
Nodes (54): ad(), af(), bf(), buildTicks(), calculateCircumference(), calculateLabelRotation(), _calculatePadding(), _circumference() (+46 more)

### Community 10 - "Core Module 10"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), kh(), _notify() (+6 more)

### Community 11 - "Database Schema (C11)"
Cohesion: 0.04
Nodes (11): Bi(), Id(), kd(), qi(), Ri(), te(), Vi(), Xc() (+3 more)

### Community 12 - "Data Models & Domain (C12)"
Cohesion: 0.07
Nodes (46): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate(), beforeBuildTicks() (+38 more)

### Community 13 - "Core Module 13"
Cohesion: 0.05
Nodes (47): ba(), bi(), c(), ca(), clickPercent(), constructor(), e(), getExtension() (+39 more)

### Community 14 - "Core Module 14"
Cohesion: 0.11
Nodes (75): Sg(), ad(), at(), B(), br(), Bt(), ca(), cd() (+67 more)

### Community 15 - "Core Module 15"
Cohesion: 0.07
Nodes (55): acquireContext(), adjustHitBoxes(), afterDraw(), bc(), Bl(), clear(), _computeGridLineItems(), _computeLabelArea() (+47 more)

### Community 16 - "Data Models & Domain (C16)"
Cohesion: 0.06
Nodes (4): BookingForm, Collection, BelongsTo, TourDate

### Community 17 - "Core Module 17"
Cohesion: 0.03
Nodes (101): Ac(), Al(), ar(), Bl(), Ce(), cf(), clone(), count() (+93 more)

### Community 18 - "HTTP Controllers & Routing (C18)"
Cohesion: 0.07
Nodes (56): Ca(), canSetCurrentAttribute(), canSetCurrentBlockAttribute(), canSetCurrentTextAttribute(), dragstart(), drop(), findRangesOfBlocks(), fromJSON() (+48 more)

### Community 19 - "HTTP Controllers & Routing (C19)"
Cohesion: 0.07
Nodes (29): canAcceptDataTransfer(), compositionControllerDidFocus(), compositionDidRequestChangingSelectionToLocationRange(), createDOMRangeFromLocationRange(), createDOMRangeFromPoint(), createLocationRangeFromDOMRange(), didMouseDown(), domRangeWithinElement() (+21 more)

### Community 20 - "Core Module 20"
Cohesion: 0.07
Nodes (50): add(), applyKeyboardCommand(), attachmentDidChangeAttributes(), attachmentEditorDidRequestRemovalOfAttachment(), canBeGrouped(), checkValidity(), copyUsingObjectMap(), copyUsingObjectsFromDocument() (+42 more)

### Community 21 - "Data Models & Domain (C21)"
Cohesion: 0.08
Nodes (36): as(), At(), Bi(), Bs(), cc(), De(), Fc(), fh() (+28 more)

### Community 22 - "Core Module 22"
Cohesion: 0.14
Nodes (9): DatabaseSeeder, DiscountSeeder, FerryRouteSeeder, ScheduleAccommodationSeeder, VehicleRateSeeder, VehicleSeeder, WebsiteSettingSeeder, Seeder (+1 more)

### Community 23 - "Core Module 23"
Cohesion: 0.06
Nodes (40): ai(), apply(), B(), co(), Cr(), $e(), es(), Et() (+32 more)

### Community 24 - "Filament Admin & UI (C24)"
Cohesion: 0.07
Nodes (19): Action, ManagePaymentSettings, Form, ManageProofs, Collection, Form, Collection, Form (+11 more)

### Community 25 - "Data Models & Domain (C25)"
Cohesion: 0.08
Nodes (40): buildTicks(), calculateCircumference(), calculateLabelRotation(), _calculatePadding(), _circumference(), _computeAngle(), _computeLabelItems(), _computeLabelSizes() (+32 more)

### Community 26 - "Data Models & Domain (C26)"
Cohesion: 0.05
Nodes (29): BookingsRelationManager, Form, Table, AccommodationsRelationManager, Form, Table, PassengersRelationManager, Form (+21 more)

### Community 27 - "Core Module 27"
Cohesion: 0.10
Nodes (35): e(), i(), l(), Ni(), o(), t(), u(), be() (+27 more)

### Community 28 - "Core Module 28"
Cohesion: 0.06
Nodes (51): _a(), active(), add(), al(), _animateOptions(), average(), ba(), _cachedScopes() (+43 more)

### Community 29 - "Database Schema (C29)"
Cohesion: 0.05
Nodes (62): attachFiles(), beforeinput(), canApplyToDocument(), compositionend(), compositionShouldAcceptFile(), compositionstart(), compositionupdate(), createLinkHTML() (+54 more)

### Community 30 - "Core Module 30"
Cohesion: 0.07
Nodes (34): cacheViewForObject(), compositionDidChangeDocument(), compositionDidLoadSnapshot(), createAttachmentNodes(), createChildView(), createContainerElement(), createDocumentFragmentForSync(), createElement() (+26 more)

### Community 31 - "Frontend & Components (C31)"
Cohesion: 0.06
Nodes (24): actions(), button(), constructor(), danger(), dispatch(), dispatchSelf(), dispatchTo(), duration() (+16 more)

### Community 32 - "Core Module 32"
Cohesion: 0.05
Nodes (75): aa(), Ah(), applyStack(), aspectRatio(), buildOrUpdateElements(), C(), Ca(), _calculateBarIndexPixels() (+67 more)

### Community 33 - "Core Module 33"
Cohesion: 0.06
Nodes (51): Ao(), applyStack(), ar(), as(), _calculateBarIndexPixels(), _calculateBarValuePixels(), cl(), countVisibleElements() (+43 more)

### Community 34 - "Data Models & Domain (C34)"
Cohesion: 0.08
Nodes (8): BelongsTo, BelongsToMany, Builder, HasMany, Schedule, BelongsToMany, TransportClass, TransportClassSeeder

### Community 35 - "Data Models & Domain (C35)"
Cohesion: 0.07
Nodes (15): ListAccommodations, ListApkUsers, ListBookings, ListDiscounts, ListFerryRoutes, ListInquiries, ListSchedules, ListTransactions (+7 more)

### Community 36 - "Core Module 36"
Cohesion: 0.06
Nodes (43): alpha(), be(), beforeDraw(), dataset(), ea(), en(), fe(), Fs() (+35 more)

### Community 37 - "Filament Admin & UI (C37)"
Cohesion: 0.10
Nodes (29): afterAutoSkip(), Bi(), buildLookupTable(), determineDataLimits(), Fi(), getAllParsedValues(), getDataTimestamps(), getDecimalForValue() (+21 more)

### Community 38 - "Core Module 38"
Cohesion: 0.10
Nodes (37): C(), Co(), cr(), diff(), endOf(), Et(), format(), formats() (+29 more)

### Community 39 - "Data Models & Domain (C39)"
Cohesion: 0.05
Nodes (21): BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested, RebookingVerification, Booking (+13 more)

### Community 40 - "HTTP Controllers & Routing (C40)"
Cohesion: 0.15
Nodes (19): tl(), ac(), Ai(), ca(), ec(), Fc(), G(), getIndexAngle() (+11 more)

### Community 41 - "Data Models & Domain (C41)"
Cohesion: 0.05
Nodes (15): ManageWebsiteSettings, Form, AuthController, WebsiteSetting, AppServiceProvider, AdminPanelProvider, Panel, Color (+7 more)

### Community 42 - "Filament Admin & UI (C42)"
Cohesion: 0.11
Nodes (13): Passenger, BelongsTo, BelongsTo, ScheduleAccommodation, BelongsTo, UserLoginHistory, HasMany, VehicleBrand (+5 more)

### Community 43 - "Core Module 43"
Cohesion: 0.12
Nodes (11): self, HasMany, Panel, User, Authenticatable, UserFactory, Factory, FilamentUser (+3 more)

### Community 44 - "Core Module 44"
Cohesion: 0.04
Nodes (77): _a(), abutsStart(), after(), afterAutoSkip(), Ai(), before(), buildLookupTable(), daysInMonth() (+69 more)

### Community 45 - "Core Module 45"
Cohesion: 0.23
Nodes (18): Ae(), at(), de(), dt(), fr(), Gt(), ht(), It() (+10 more)

### Community 46 - "HTTP Controllers & Routing (C46)"
Cohesion: 0.08
Nodes (31): Bt(), xo(), addEventListener(), bindResponsiveEvents(), cs(), Ct(), data(), di() (+23 more)

### Community 47 - "Core Module 47"
Cohesion: 0.04
Nodes (60): an(), Au(), average(), ba(), bu(), clear(), cn(), cu() (+52 more)

### Community 48 - "Data Models & Domain (C48)"
Cohesion: 0.08
Nodes (37): ActivityScreen, _ActivityScreenState, BookingDetailsScreen, _BookingDetailsScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState (+29 more)

### Community 49 - "Data Models & Domain (C49)"
Cohesion: 0.09
Nodes (11): EditAccommodation, EditBooking, EditDiscount, EditSchedule, EditTour, EditTransportClass, EditUser, EditVehicleBrand (+3 more)

### Community 50 - "Data Models & Domain (C50)"
Cohesion: 0.12
Nodes (5): BookingLookup, PaymentProof, UserDashboard, Component, WithFileUploads

### Community 51 - "Core Module 51"
Cohesion: 0.16
Nodes (33): _a(), aa(), ba(), Be(), Bi(), br(), Ca(), ce() (+25 more)

### Community 52 - "Data Models & Domain (C52)"
Cohesion: 0.08
Nodes (34): afterDatasetsUpdate(), buildOrUpdateControllers(), _d(), _destroyDatasetMeta(), Fd(), first(), generateLabels(), getDatasetMeta() (+26 more)

### Community 53 - "Filament Admin & UI (C53)"
Cohesion: 0.07
Nodes (44): backspace(), canDecreaseBlockAttributeLevel(), canDecreaseNestingLevel(), canIncreaseNestingLevel(), d(), decreaseBlockAttributeLevel(), decreaseListLevel(), decreaseNestingLevel() (+36 more)

### Community 54 - "Core Module 54"
Cohesion: 0.10
Nodes (13): a(), ar(), at(), cr(), d(), f(), H(), ji() (+5 more)

### Community 55 - "HTTP Controllers & Routing (C55)"
Cohesion: 0.13
Nodes (19): actionIsExternal(), canInvokeAction(), compositionControllerDidBlur(), compositionControllerDidSyncDocumentView(), compositionDidAddAttachment(), compositionDidChangeAttachmentPreviewURL(), compositionDidChangeCurrentAttributes(), compositionDidEditAttachment() (+11 more)

### Community 56 - "Core Module 56"
Cohesion: 0.25
Nodes (25): d(), Di(), f(), Ge(), I(), ir(), ja(), k() (+17 more)

### Community 57 - "Data Models & Domain (C57)"
Cohesion: 0.08
Nodes (30): box(), canBeConsolidatedWith(), compositionControllerDidRender(), constructor(), disabled(), formDisabledCallback(), fromUCS2String(), get() (+22 more)

### Community 58 - "Core Module 58"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 59 - "Core Module 59"
Cohesion: 0.14
Nodes (30): Qt(), Cn(), da(), En(), fa(), Fi(), fn(), h() (+22 more)

### Community 60 - "Filament Admin & UI (C60)"
Cohesion: 0.11
Nodes (10): ListTours, Form, Table, TourResource, Request, TourController, TourController, HasMany (+2 more)

### Community 61 - "Data Models & Domain (C61)"
Cohesion: 0.10
Nodes (6): Request, ScheduleController, FerryRoute, BelongsTo, HasMany, ScheduleSeatingProfileTest

### Community 62 - "Core Module 62"
Cohesion: 0.10
Nodes (22): attachmentForFile(), attributesForFile(), didChangeAttributes(), getContentType(), getHeight(), getHref(), getPreviewURL(), getType() (+14 more)

### Community 63 - "Data Models & Domain (C63)"
Cohesion: 0.10
Nodes (15): DashboardStatsOverview, AdminNotificationFeed, Collection, BaseTestCase, BaseWidget, CreatesApplication, RefreshDatabase, BookingLookupCancellationTest (+7 more)

### Community 64 - "Data Models & Domain (C64)"
Cohesion: 0.13
Nodes (12): CreateAccommodation, CreateBooking, CreateDiscount, CreateInquiry, CreateSchedule, CreateTour, CreateTransportClass, CreateUser (+4 more)

### Community 65 - "Core Module 65"
Cohesion: 0.17
Nodes (18): afterDatasetsUpdate(), generateLabels(), getDatasetMeta(), getDataVisibility(), getMaxBorderWidth(), getStyle(), hide(), isDatasetVisible() (+10 more)

### Community 66 - "HTTP Controllers & Routing (C66)"
Cohesion: 0.07
Nodes (26): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+18 more)

### Community 67 - "Core Module 67"
Cohesion: 0.09
Nodes (21): APP_DEBUG, APP_ENV, APP_NAME, APP_URL, CACHE_STORE, DB_CONNECTION, DB_DATABASE, DB_HOST (+13 more)

### Community 68 - "Database Schema (C68)"
Cohesion: 0.10
Nodes (30): addElements(), bindEvents(), bindUserEvents(), buildOrUpdateControllers(), buildOrUpdateElements(), buildOrUpdateScales(), _checkEventBindings(), configure() (+22 more)

### Community 69 - "Core Module 69"
Cohesion: 0.15
Nodes (22): cf(), ef(), fa(), Jc(), Ln(), ma(), no(), pa() (+14 more)

### Community 70 - "Database Seeders & Testing (C70)"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 71 - "Data Models & Domain (C71)"
Cohesion: 0.10
Nodes (20): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+12 more)

### Community 72 - "Core Module 72"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 74 - "Data Models & Domain (C74)"
Cohesion: 0.10
Nodes (20): 1. Clone the repository, 1. Navigate to the Flutter folder, 2. Install Flutter Dependencies, 2. Install PHP Dependencies, 3. Install Node Dependencies, 3. Update the API Endpoint, 4. Environment Configuration, 4. Run the App (+12 more)

### Community 75 - "Core Module 75"
Cohesion: 0.11
Nodes (19): scripts, dev, post-autoload-dump, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout (+11 more)

### Community 76 - "Core Module 76"
Cohesion: 0.11
Nodes (17): concurrently, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+9 more)

### Community 77 - "Core Module 77"
Cohesion: 0.14
Nodes (16): RegisterPlugins(), OnCreate, OnDestroy, HWND, Win32Window, child_content_, GetClientArea, OnCreate (+8 more)

### Community 78 - "Core Module 78"
Cohesion: 0.18
Nodes (14): wchar_t, Scale(), Create, Destroy, UpdateTheme, Win32Window::Win32Window(), WindowClassRegistrar, class_registered_ (+6 more)

### Community 79 - "Core Module 79"
Cohesion: 0.23
Nodes (17): appendAttachmentWithAttributes(), appendBlockForAttributesWithElement(), appendBlockForElement(), appendBlockForTextNode(), appendEmptyBlock(), appendPiece(), appendStringWithAttributes(), findBlockElementAncestors() (+9 more)

### Community 80 - "HTTP Controllers & Routing (C80)"
Cohesion: 0.13
Nodes (13): Cocoa, file_selector_macos, RegisterGeneratedPlugins(), MainFlutterWindow, FlutterMacOS, FlutterPluginRegistry, FlutterViewController, Foundation (+5 more)

### Community 81 - "Core Module 81"
Cohesion: 0.13
Nodes (13): DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow, flutter_controller_ (+5 more)

### Community 82 - "Core Module 82"
Cohesion: 0.08
Nodes (28): Yn(), Ge(), an(), chartOptionScopes(), color(), constructor(), darken(), desaturate() (+20 more)

### Community 83 - "Core Module 83"
Cohesion: 0.15
Nodes (14): Ce(), De(), di(), e(), Ht(), Ie(), Me(), Re() (+6 more)

### Community 84 - "Core Module 84"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 85 - "Core Module 85"
Cohesion: 0.14
Nodes (14): require, anhskohbo/no-captcha, dompdf/dompdf, filament/filament, filament/support, intervention/image, laravel/framework, laravel/tinker (+6 more)

### Community 87 - "Core Module 87"
Cohesion: 0.04
Nodes (64): Ag(), chartOptionScopes(), constructor(), describe(), divideEqually(), Ec(), Ef(), equals() (+56 more)

### Community 88 - "Core Module 88"
Cohesion: 0.15
Nodes (12): background_color, categories, description, display, icons, name, orientation, short_name (+4 more)

### Community 89 - "Core Module 89"
Cohesion: 0.14
Nodes (13): addFaq, addQuickFact, addSocialLink, closePanel, removeFaq({{ $fi }}), removeHeroImage({{ (int)$idx }}), removeQuickFact({{ $fi }}), removeSocialLink({{ $li }}) (+5 more)

### Community 90 - "Filament Admin & UI (C90)"
Cohesion: 0.16
Nodes (5): BookingStatusChart, RecentActivityWidget, RevenueChart, TopRoutesWidget, Widget

### Community 91 - "Core Module 91"
Cohesion: 0.24
Nodes (9): wWinMain(), string, wchar_t, CreateAndAttachConsole(), GetCommandLineArguments(), Utf8FromUtf16(), _In_, _In_opt_ (+1 more)

### Community 92 - "Core Module 92"
Cohesion: 0.26
Nodes (7): C(), D(), J(), O(), U(), v(), X()

### Community 93 - "Core Module 93"
Cohesion: 0.18
Nodes (12): Be(), ei(), ii(), le(), ni(), oi(), r(), ri() (+4 more)

### Community 94 - "Data Models & Domain (C94)"
Cohesion: 0.10
Nodes (7): ViewApkUser, Form, ViewBooking, ViewInquiry, ViewTransaction, DatePicker, ViewRecord

### Community 96 - "Filament Admin & UI (C96)"
Cohesion: 0.13
Nodes (7): FerryRouteResource, CreateFerryRoute, EditFerryRoute, Form, Table, HasMany, Vehicle

### Community 97 - "Core Module 97"
Cohesion: 0.18
Nodes (10): background_color, description, display, icons, name, orientation, prefer_related_applications, short_name (+2 more)

### Community 98 - "Core Module 98"
Cohesion: 0.20
Nodes (11): b(), Dt(), Fe(), g(), He(), i(), ir(), Mt() (+3 more)

### Community 99 - "Core Module 99"
Cohesion: 0.20
Nodes (8): Any, AppDelegate, Bool, AppDelegate, Bool, FlutterAppDelegate, NSApplication, UIApplication

### Community 100 - "Filament Admin & UI (C100)"
Cohesion: 0.10
Nodes (25): ArrowLeft(), ArrowRight(), attachmentManagerDidRequestRemovalOfAttachment(), compositionControllerDidRequestRemovalOfAttachment(), dragend(), editAttachment(), expandSelectionAroundCommonAttribute(), expandSelectionForEditing() (+17 more)

### Community 101 - "Core Module 101"
Cohesion: 0.25
Nodes (5): Flutter, RunnerTests, RunnerTests, UIKit, XCTestCase

### Community 102 - "Core Module 102"
Cohesion: 0.36
Nodes (10): HWND, LPARAM, LRESULT, UINT, WPARAM, EnableFullDpiSupportIfAvailable(), GetHandle, GetThisFromHandle (+2 more)

### Community 103 - "Resource"
Cohesion: 0.13
Nodes (8): ApkUserResource, Builder, Form, Table, Form, Table, VehicleResource, SystemStatsOverview

### Community 106 - "Filament Admin & UI (C106)"
Cohesion: 0.20
Nodes (9): Flutter & Android Studio Setup Guide, Option A: VS Code (Recommended), Option B: Android Studio, 📋 Prerequisites, 🚀 Step 1: Install the Flutter SDK, 📱 Step 2: Install and Configure Android Studio, 🛠️ Step 3: Run Flutter Doctor, 💻 Step 4: Configure Your IDE (+1 more)

### Community 107 - "Filament Admin & UI (C107)"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 108 - "Core Module 108"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 109 - "Core Module 109"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 110 - "Core Module 110"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 111 - "Core Module 111"
Cohesion: 0.29
Nodes (7): build, _goNext, _goToSchedule, _selectTransportOption, _showAirlineClassPicker, _showFerryAccommodationPicker, MaterialPageRoute

### Community 113 - "Core Module 113"
Cohesion: 0.28
Nodes (3): Form, Table, UserResource

### Community 114 - "Core Module 114"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 115 - "Core Module 115"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 116 - "Transaction"
Cohesion: 0.10
Nodes (11): AccommodationController, BookingController, Request, DiscountController, PromotionController, Controller, Accommodation, BelongsToMany (+3 more)

### Community 117 - "_each"
Cohesion: 0.17
Nodes (16): add(), _getAnims(), Gi(), gn(), has(), Jc(), listen(), Me() (+8 more)

### Community 118 - "Database Seeders & Testing (C118)"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 119 - "Core Module 119"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 120 - "Core Module 120"
Cohesion: 0.36
Nodes (8): hs(), Ln(), Nn(), ps(), qt(), Ro(), Se(), wo()

### Community 121 - "Core Module 121"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 173 - "Core Module 173"
Cohesion: 0.33
Nodes (7): ar(), ft(), kn(), sr(), wn(), Ye(), yn()

### Community 186 - "Core Module 186"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 222 - "Core Module 222"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 224 - "Core Module 224"
Cohesion: 0.22
Nodes (4): Form, Table, ScheduleResource, $set(

### Community 245 - "graphify reference: query, path, explain"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 247 - "graphify reference: add a URL and watch a folder"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 248 - "graphify reference: commit hook and native CLAUDE.md integration"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 249 - "graphify reference: incremental update and cluster-only"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 250 - "graphify reference: add a URL and watch a folder"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 251 - "graphify reference: commit hook and native CLAUDE.md integration"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 252 - "graphify reference: incremental update and cluster-only"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 253 - "graphify reference: add a URL and watch a folder"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 254 - "graphify reference: commit hook and native CLAUDE.md integration"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 255 - "graphify reference: incremental update and cluster-only"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 256 - "qo"
Cohesion: 0.31
Nodes (3): BookingResource, Form, Table

### Community 257 - "PurgeExpiredProofs.php"
Cohesion: 0.38
Nodes (3): DeleteAllUsers, PurgeExpiredProofs, Command

### Community 263 - "flutter_app"
Cohesion: 0.50
Nodes (3): Amiga Gracia Flutter App, Getting Started, Railway build

### Community 280 - "AdminMiddleware.php"
Cohesion: 0.60
Nodes (3): AdminMiddleware, Request, Closure

### Community 285 - "VehicleBrandResource.php"
Cohesion: 0.24
Nodes (3): Form, Table, VehicleBrandResource

### Community 287 - "BookingResource.php"
Cohesion: 0.10
Nodes (10): AccommodationResource, Form, Table, DiscountResource, Form, Table, Form, Table (+2 more)

### Community 289 - "InquiryResource.php"
Cohesion: 0.28
Nodes (3): InquiryResource, Form, Table

### Community 290 - "TransportClassResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, TransportClassResource

### Community 293 - "How to Update the Android App (APK)"
Cohesion: 0.33
Nodes (5): How to Update the Android App (APK), Step 1: Bump the Version Number, Step 2: Build the New APK, Step 3: Copy the New APK to the Web Server, What happens automatically next?

## Knowledge Gaps
- **524 isolated node(s):** `Step 1: Bump the Version Number`, `Step 2: Build the New APK`, `Step 3: Copy the New APK to the Web Server`, `What happens automatically next?`, `UserSession` (+519 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **30 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `A()` connect `Core Module 69` to `HTTP Controllers & Routing (C2)`, `Core Module 3`, `Core Module 6`, `Core Module 8`, `Core Module 59`, `Core Module 14`, `Core Module 15`, `HTTP Controllers & Routing (C18)`, `Core Module 20`, `Core Module 56`, `Data Models & Domain (C57)`, `Core Module 27`?**
  _High betweenness centrality (0.049) - this node is a cross-community bridge._
- **Why does `draw()` connect `Core Module 8` to `HTTP Controllers & Routing (C0)`, `Core Module 32`, `Database Schema (C68)`, `Core Module 69`, `Core Module 6`, `Data Models & Domain (C7)`, `Database Schema (C9)`, `Core Module 47`, `Core Module 56`, `Data Models & Domain (C25)`?**
  _High betweenness centrality (0.038) - this node is a cross-community bridge._
- **Why does `F()` connect `Core Module 6` to `HTTP Controllers & Routing (C2)`, `Core Module 4`, `Core Module 8`, `HTTP Controllers & Routing (C40)`, `Core Module 45`, `Core Module 14`, `Core Module 15`, `Core Module 23`, `Core Module 56`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Are the 16 inferred relationships involving `x()` (e.g. with `g()` and `_i()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **Are the 20 inferred relationships involving `te()` (e.g. with `je()` and `Pr()`) actually correct?**
  _`te()` has 20 INFERRED edges - model-reasoned connections that need verification._
- **Are the 29 inferred relationships involving `V()` (e.g. with `Sg()` and `g()`) actually correct?**
  _`V()` has 29 INFERRED edges - model-reasoned connections that need verification._
- **What connects `Step 1: Bump the Version Number`, `Step 2: Build the New APK`, `Step 3: Copy the New APK to the Web Server` to the rest of the system?**
  _524 weakly-connected nodes found - possible documentation gaps or missing edges._