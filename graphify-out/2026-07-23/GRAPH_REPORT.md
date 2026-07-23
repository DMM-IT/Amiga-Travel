# Graph Report - Amiga-Travel  (2026-07-22)

## Corpus Check
- 367 files · ~236,445 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 5101 nodes · 12325 edges · 276 communities (249 shown, 27 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1160 edges (avg confidence: 0.67)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `e5c5acf2`
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
- graphify reference: query, path, explain
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
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

## Communities (276 total, 27 thin omitted)

### Community 0 - "HTTP Controllers & Routing (C0)"
Cohesion: 0.01
Nodes (117): acquireContext(), Ag(), beforeDatasetDraw(), beforeDatasetsDraw(), bh(), Br(), ch(), color() (+109 more)

### Community 1 - "Data Models & Domain (C1)"
Cohesion: 0.01
Nodes (243): bool get, dart:async, dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex (+235 more)

### Community 2 - "HTTP Controllers & Routing (C2)"
Cohesion: 0.02
Nodes (125): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeAttributes(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), canRedo() (+117 more)

### Community 3 - "Core Module 3"
Cohesion: 0.04
Nodes (122): Aa(), af(), ai(), al(), An(), ao(), Be(), bf() (+114 more)

### Community 4 - "Core Module 4"
Cohesion: 0.02
Nodes (94): aa(), addControllers(), addPlugins(), addScales(), an(), aspectRatio(), beforeDatasetDraw(), beforeDatasetsDraw() (+86 more)

### Community 5 - "Core Module 5"
Cohesion: 0.03
Nodes (122): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), canBeGroupedWith(), charAt() (+114 more)

### Community 6 - "Core Module 6"
Cohesion: 0.08
Nodes (65): [g](), [x](), Sg(), $c(), ca(), D(), E(), g() (+57 more)

### Community 7 - "Data Models & Domain (C7)"
Cohesion: 0.04
Nodes (92): addBox(), addEventListener(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit(), afterSetDimensions() (+84 more)

### Community 8 - "Core Module 8"
Cohesion: 0.04
Nodes (114): ad(), adjustHitBoxes(), ae(), af(), _calculateBarValuePixels(), calculateLabelRotation(), _calculatePadding(), cd() (+106 more)

### Community 9 - "Database Schema (C9)"
Cohesion: 0.03
Nodes (123): _a(), abutsStart(), after(), afterAutoSkip(), Ah(), Ai(), Al(), ar() (+115 more)

### Community 10 - "Core Module 10"
Cohesion: 0.12
Nodes (19): clear(), cn(), Da(), Fc(), fh(), gc(), _getLegendItemAt(), interpolate() (+11 more)

### Community 11 - "Database Schema (C11)"
Cohesion: 0.03
Nodes (17): Bi(), bn(), fe(), Id(), ji(), kd(), on(), qi() (+9 more)

### Community 12 - "Data Models & Domain (C12)"
Cohesion: 0.08
Nodes (43): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate(), beforeBuildTicks() (+35 more)

### Community 13 - "Core Module 13"
Cohesion: 0.05
Nodes (52): ba(), bi(), c(), ca(), clickPercent(), constructor(), de(), e() (+44 more)

### Community 14 - "Core Module 14"
Cohesion: 0.11
Nodes (81): define(), _a(), Ae(), ar(), as(), at(), B(), Ba() (+73 more)

### Community 15 - "Core Module 15"
Cohesion: 0.08
Nodes (43): adjustHitBoxes(), afterDraw(), bc(), Bl(), clear(), _computeLabelArea(), _computeTitleHeight(), _createItems() (+35 more)

### Community 16 - "Data Models & Domain (C16)"
Cohesion: 0.06
Nodes (4): BookingForm, Collection, BelongsTo, TourDate

### Community 17 - "Core Module 17"
Cohesion: 0.04
Nodes (78): Bl(), cf(), chartOptionScopes(), clone(), constructor(), create(), describe(), dtFormatter() (+70 more)

### Community 18 - "HTTP Controllers & Routing (C18)"
Cohesion: 0.07
Nodes (58): attachmentManagerDidRequestRemovalOfAttachment(), breakFormattedBlock(), breaksOnReturn(), Ca(), canSetCurrentAttribute(), canSetCurrentBlockAttribute(), compositionControllerDidRequestRemovalOfAttachment(), decreaseBlockAttributeLevel() (+50 more)

### Community 19 - "HTTP Controllers & Routing (C19)"
Cohesion: 0.08
Nodes (36): canAcceptDataTransfer(), canDecreaseNestingLevel(), canIncreaseNestingLevel(), compositionControllerDidFocus(), compositionDidRequestChangingSelectionToLocationRange(), createDOMRangeFromLocationRange(), createDOMRangeFromPoint(), createLocationRangeFromDOMRange() (+28 more)

### Community 20 - "Core Module 20"
Cohesion: 0.10
Nodes (36): add(), applyKeyboardCommand(), attachmentEditorDidRequestRemovalOfAttachment(), canBeGrouped(), checkValidity(), createCaptionElement(), dialogIsVisible(), didClickActionButton() (+28 more)

### Community 21 - "Data Models & Domain (C21)"
Cohesion: 0.07
Nodes (39): Ac(), alpha(), an(), as(), At(), Bi(), Bs(), cc() (+31 more)

### Community 22 - "Core Module 22"
Cohesion: 0.05
Nodes (26): DiscountController, Accommodation, BelongsToMany, Discount, HasMany, BelongsTo, ScheduleAccommodation, BelongsTo (+18 more)

### Community 23 - "Core Module 23"
Cohesion: 0.06
Nodes (40): ai(), apply(), B(), co(), Cr(), $e(), es(), Et() (+32 more)

### Community 24 - "Filament Admin & UI (C24)"
Cohesion: 0.05
Nodes (28): Action, ManagePaymentSettings, Form, ManageProofs, Collection, Form, OverallReports, Form (+20 more)

### Community 25 - "Data Models & Domain (C25)"
Cohesion: 0.08
Nodes (46): acquireContext(), ar(), calculateLabelRotation(), _calculatePadding(), _computeAngle(), _computeGridLineItems(), _computeLabelItems(), _computeLabelSizes() (+38 more)

### Community 26 - "Data Models & Domain (C26)"
Cohesion: 0.05
Nodes (29): BookingsRelationManager, Form, Table, AccommodationsRelationManager, Form, Table, PassengersRelationManager, Form (+21 more)

### Community 27 - "Core Module 27"
Cohesion: 0.14
Nodes (18): e(), i(), l(), Ni(), o(), t(), u(), be() (+10 more)

### Community 28 - "Core Module 28"
Cohesion: 0.05
Nodes (54): Yn(), addElements(), al(), beforeUpdate(), buildOrUpdateControllers(), buildOrUpdateElements(), _cachedScopes(), chartOptionScopes() (+46 more)

### Community 29 - "Database Schema (C29)"
Cohesion: 0.05
Nodes (65): attachFiles(), canApplyToDocument(), compositionShouldAcceptFile(), compositionstart(), compositionupdate(), createLinkHTML(), deleteByDrag(), dragend() (+57 more)

### Community 30 - "Core Module 30"
Cohesion: 0.11
Nodes (23): add(), addControllers(), addPlugins(), addScales(), _each(), _exec(), _getAnims(), _getRegistryForType() (+15 more)

### Community 31 - "Frontend & Components (C31)"
Cohesion: 0.06
Nodes (24): actions(), button(), constructor(), danger(), dispatch(), dispatchSelf(), dispatchTo(), duration() (+16 more)

### Community 32 - "Core Module 32"
Cohesion: 0.04
Nodes (89): addElements(), aspectRatio(), buildOrUpdateElements(), C(), Ca(), _calculateBarIndexPixels(), calculateCircumference(), Ce() (+81 more)

### Community 33 - "Core Module 33"
Cohesion: 0.06
Nodes (50): Ao(), applyStack(), as(), Bi(), _calculateBarIndexPixels(), _calculateBarValuePixels(), calculateCircumference(), _circumference() (+42 more)

### Community 34 - "Data Models & Domain (C34)"
Cohesion: 0.08
Nodes (8): BelongsTo, BelongsToMany, Builder, HasMany, Schedule, BelongsToMany, TransportClass, TransportClassSeeder

### Community 35 - "Data Models & Domain (C35)"
Cohesion: 0.05
Nodes (18): ListAccommodations, ListApkUsers, ListBookings, ListDiscounts, ListFerryRoutes, ListInquiries, ListSchedules, ListTransactions (+10 more)

### Community 36 - "Core Module 36"
Cohesion: 0.06
Nodes (43): _a(), active(), add(), _animateOptions(), average(), ba(), _createAnimations(), Fi() (+35 more)

### Community 37 - "Filament Admin & UI (C37)"
Cohesion: 0.06
Nodes (58): ad(), Cc(), cd(), cl(), Cn(), dd(), Do(), Ec() (+50 more)

### Community 38 - "Core Module 38"
Cohesion: 0.06
Nodes (55): buildOrUpdateScales(), C(), cl(), Co(), cr(), D(), data(), E() (+47 more)

### Community 39 - "Data Models & Domain (C39)"
Cohesion: 0.03
Nodes (38): BookingController, Request, BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested (+30 more)

### Community 40 - "HTTP Controllers & Routing (C40)"
Cohesion: 0.06
Nodes (46): alpha(), At(), be(), beforeDraw(), dataset(), ea(), en(), Fa() (+38 more)

### Community 41 - "Data Models & Domain (C41)"
Cohesion: 0.05
Nodes (15): ManageWebsiteSettings, Form, AuthController, WebsiteSetting, AppServiceProvider, AdminPanelProvider, Panel, Color (+7 more)

### Community 42 - "Filament Admin & UI (C42)"
Cohesion: 0.09
Nodes (25): box(), canBeConsolidatedWith(), canDecreaseBlockAttributeLevel(), compositionControllerDidRender(), constructor(), formDisabledCallback(), fromUCS2String(), get() (+17 more)

### Community 43 - "Core Module 43"
Cohesion: 0.10
Nodes (10): ApkUserResource, Builder, Form, Table, Form, Table, VehicleResource, DashboardStatsOverview (+2 more)

### Community 44 - "Core Module 44"
Cohesion: 0.12
Nodes (19): attachmentForFile(), attributesForFile(), didChangeAttributes(), getContentType(), getHeight(), getHref(), getPreviewURL(), getType() (+11 more)

### Community 45 - "Core Module 45"
Cohesion: 0.23
Nodes (18): Ae(), at(), de(), dt(), fr(), Gt(), ht(), It() (+10 more)

### Community 46 - "HTTP Controllers & Routing (C46)"
Cohesion: 0.08
Nodes (31): Bt(), xo(), addEventListener(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), _checkEventBindings(), cs() (+23 more)

### Community 47 - "Core Module 47"
Cohesion: 0.04
Nodes (59): applyStack(), Au(), average(), ba(), beforeDraw(), bu(), cu(), dataset() (+51 more)

### Community 48 - "Data Models & Domain (C48)"
Cohesion: 0.09
Nodes (33): ActivityScreen, _ActivityScreenState, BookingDetailsScreen, _BookingDetailsScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState (+25 more)

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
Cohesion: 0.09
Nodes (31): afterDatasetsUpdate(), buildOrUpdateControllers(), _d(), _destroyDatasetMeta(), generateLabels(), getDatasetMeta(), getDataVisibility(), getMaxBorderWidth() (+23 more)

### Community 53 - "Filament Admin & UI (C53)"
Cohesion: 0.07
Nodes (40): ArrowLeft(), ArrowRight(), backspace(), d(), delete(), deleteByComposition(), deleteByCut(), deleteCompositionText() (+32 more)

### Community 54 - "Core Module 54"
Cohesion: 0.10
Nodes (13): a(), ar(), at(), cr(), d(), f(), H(), ji() (+5 more)

### Community 55 - "HTTP Controllers & Routing (C55)"
Cohesion: 0.11
Nodes (21): actionIsExternal(), canInvokeAction(), compositionControllerDidBlur(), compositionControllerDidSyncDocumentView(), compositionDidAddAttachment(), compositionDidChangeAttachmentPreviewURL(), compositionDidChangeCurrentAttributes(), compositionDidChangeDocument() (+13 more)

### Community 56 - "Core Module 56"
Cohesion: 0.25
Nodes (25): d(), Di(), f(), Ge(), I(), ir(), ja(), k() (+17 more)

### Community 57 - "Data Models & Domain (C57)"
Cohesion: 0.12
Nodes (18): canSetCurrentTextAttribute(), compositionControllerDidRequestDeselectingAttachment(), compositionDidStartEditingAttachment(), didClickAttachment(), dragstart(), findAttachmentForElement(), getAttachmentAndPositionById(), getAttachmentById() (+10 more)

### Community 58 - "Core Module 58"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 59 - "Core Module 59"
Cohesion: 0.14
Nodes (30): Qt(), Cn(), da(), En(), fa(), Fi(), fn(), h() (+22 more)

### Community 60 - "Filament Admin & UI (C60)"
Cohesion: 0.07
Nodes (15): ListTours, Form, Table, TourResource, AccommodationController, PromotionController, Request, TourController (+7 more)

### Community 61 - "Data Models & Domain (C61)"
Cohesion: 0.10
Nodes (6): Request, ScheduleController, FerryRoute, BelongsTo, HasMany, ScheduleSeatingProfileTest

### Community 62 - "Core Module 62"
Cohesion: 0.06
Nodes (40): beforeinput(), cacheViewForObject(), compositionDidLoadSnapshot(), compositionend(), createAttachmentNodes(), createChildView(), createContainerElement(), createDocumentFragmentForSync() (+32 more)

### Community 63 - "Data Models & Domain (C63)"
Cohesion: 0.25
Nodes (11): aa(), determineDataLimits(), Dh(), _getLabelBounds(), getMinMax(), _getOtherScale(), getUserBounds(), handleTickRangeOptions() (+3 more)

### Community 64 - "Data Models & Domain (C64)"
Cohesion: 0.03
Nodes (34): AccommodationResource, CreateAccommodation, Form, Table, BookingResource, CreateBooking, Form, Table (+26 more)

### Community 65 - "Core Module 65"
Cohesion: 0.15
Nodes (20): afterDatasetsUpdate(), generateLabels(), getDatasetMeta(), getDataVisibility(), getMaxBorderWidth(), getStyle(), hide(), isDatasetVisible() (+12 more)

### Community 66 - "HTTP Controllers & Routing (C66)"
Cohesion: 0.07
Nodes (26): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+18 more)

### Community 67 - "Core Module 67"
Cohesion: 0.09
Nodes (21): APP_DEBUG, APP_ENV, APP_NAME, APP_URL, CACHE_STORE, DB_CONNECTION, DB_DATABASE, DB_HOST (+13 more)

### Community 68 - "Database Schema (C68)"
Cohesion: 0.13
Nodes (21): disabled(), afterAutoSkip(), buildLookupTable(), buildTicks(), diff(), _generate(), getAllParsedValues(), getDataTimestamps() (+13 more)

### Community 69 - "Core Module 69"
Cohesion: 0.42
Nodes (9): sf(), vo(), A(), connectedCallback(), Ge(), Je(), required(), setCustomValidity() (+1 more)

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
Cohesion: 0.18
Nodes (20): appendAttachmentWithAttributes(), appendBlockForAttributesWithElement(), appendBlockForElement(), appendBlockForTextNode(), appendEmptyBlock(), appendPiece(), appendStringWithAttributes(), find() (+12 more)

### Community 80 - "HTTP Controllers & Routing (C80)"
Cohesion: 0.14
Nodes (12): Cocoa, file_selector_macos, RegisterGeneratedPlugins(), MainFlutterWindow, FlutterMacOS, FlutterPluginRegistry, FlutterViewController, Foundation (+4 more)

### Community 81 - "Core Module 81"
Cohesion: 0.13
Nodes (13): DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow, flutter_controller_ (+5 more)

### Community 82 - "Core Module 82"
Cohesion: 0.38
Nodes (3): DeleteAllUsers, PurgeExpiredProofs, Command

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
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), kh(), _notify() (+6 more)

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
Cohesion: 0.07
Nodes (11): ViewApkUser, Form, ViewBooking, ViewInquiry, ViewTransaction, Builder, Table, TransactionResource (+3 more)

### Community 96 - "Filament Admin & UI (C96)"
Cohesion: 0.08
Nodes (11): FerryRouteResource, CreateFerryRoute, EditFerryRoute, Form, Table, self, HasMany, Vehicle (+3 more)

### Community 97 - "Core Module 97"
Cohesion: 0.18
Nodes (10): background_color, description, display, icons, name, orientation, prefer_related_applications, short_name (+2 more)

### Community 98 - "Core Module 98"
Cohesion: 0.20
Nodes (11): b(), Dt(), Fe(), g(), He(), i(), ir(), Mt() (+3 more)

### Community 99 - "Core Module 99"
Cohesion: 0.20
Nodes (8): Any, AppDelegate, Bool, AppDelegate, Bool, FlutterAppDelegate, NSApplication, UIApplication

### Community 101 - "Core Module 101"
Cohesion: 0.25
Nodes (5): Flutter, RunnerTests, RunnerTests, UIKit, XCTestCase

### Community 102 - "Core Module 102"
Cohesion: 0.36
Nodes (10): HWND, LPARAM, LRESULT, UINT, WPARAM, EnableFullDpiSupportIfAvailable(), GetHandle, GetThisFromHandle (+2 more)

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
Cohesion: 0.13
Nodes (23): ac(), Ai(), ca(), drawGrid(), ec(), Fc(), G(), getDistanceFromCenterForValue() (+15 more)

### Community 114 - "Core Module 114"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 115 - "Core Module 115"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

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

### Community 263 - "flutter_app"
Cohesion: 0.50
Nodes (3): Amiga Gracia Flutter App, Getting Started, Railway build

### Community 280 - "AdminMiddleware.php"
Cohesion: 0.60
Nodes (3): AdminMiddleware, Request, Closure

## Knowledge Gaps
- **505 isolated node(s):** `UserSession`, `BookingData`, `prefs`, `isFirstLaunch`, `kGreen` (+500 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **27 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `A()` connect `Core Module 69` to `HTTP Controllers & Routing (C2)`, `Core Module 3`, `Database Schema (C68)`, `Filament Admin & UI (C37)`, `Core Module 6`, `Core Module 8`, `Core Module 59`, `Filament Admin & UI (C42)`, `Core Module 14`, `Core Module 15`, `HTTP Controllers & Routing (C18)`, `Core Module 20`, `Core Module 56`, `Core Module 27`?**
  _High betweenness centrality (0.047) - this node is a cross-community bridge._
- **Why does `draw()` connect `Core Module 8` to `HTTP Controllers & Routing (C0)`, `Core Module 32`, `Core Module 69`, `Core Module 6`, `Data Models & Domain (C7)`, `Core Module 38`, `Core Module 10`, `Core Module 47`, `Data Models & Domain (C21)`, `Core Module 56`, `Data Models & Domain (C25)`?**
  _High betweenness centrality (0.034) - this node is a cross-community bridge._
- **Why does `F()` connect `Core Module 6` to `HTTP Controllers & Routing (C2)`, `Core Module 4`, `Core Module 8`, `Core Module 45`, `Core Module 14`, `Core Module 15`, `Core Module 113`, `Core Module 23`, `Core Module 56`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Are the 16 inferred relationships involving `x()` (e.g. with `g()` and `_i()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **Are the 20 inferred relationships involving `te()` (e.g. with `je()` and `Pr()`) actually correct?**
  _`te()` has 20 INFERRED edges - model-reasoned connections that need verification._
- **Are the 29 inferred relationships involving `V()` (e.g. with `Sg()` and `g()`) actually correct?**
  _`V()` has 29 INFERRED edges - model-reasoned connections that need verification._
- **What connects `UserSession`, `BookingData`, `prefs` to the rest of the system?**
  _505 weakly-connected nodes found - possible documentation gaps or missing edges._