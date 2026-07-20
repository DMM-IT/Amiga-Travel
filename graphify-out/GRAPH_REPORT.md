# Graph Report - Amiga-Travel  (2026-07-20)

## Corpus Check
- 321 files · ~170,457 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 4750 nodes · 11834 edges · 246 communities (222 shown, 24 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1114 edges (avg confidence: 0.67)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `5ae8356c`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- chart.js
- main.dart
- rich-editor.js
- chart.js
- markdown-editor.js
- User
- getLength
- constructor
- select.js
- draw
- _update
- format
- x
- P
- Booking
- Model
- file-upload.js
- setSelectedRange
- qt
- updateElements
- te
- BookingForm
- getSelectedRange
- I
- getContext
- support.js
- T
- getDatasetMeta
- constructor
- notifications.js
- RelationManager
- Schedule
- deleteInDirection
- draw
- setAttribute
- on
- render
- _update
- EditRecord
- Controller
- ListRecords
- get
- cd
- Seeder
- vd
- Vn
- getOptionScopes
- CreateRecord
- State
- C
- fn
- getSortedVisibleDatasetMetas
- Tour
- _each
- St
- notifyEditorElement
- AuthController
- my_application.cc
- echo.js
- m
- getDatasetMeta
- InquiryResource.php
- DatePicker
- PaymentSetting
- StatelessWidget
- appendBlockForElement
- scripts
- getAttachments
- Vehicle
- devDependencies
- FlutterWindow
- win32_window.cpp
- qe
- GeneratedPluginRegistrant.swift
- preload
- BookingLookup
- Win32Window
- composer.json
- require
- color-picker.js
- constructor
- manifest.json
- wWinMain
- app.js
- r
- t
- manifest.json
- A
- buildTicks
- .application
- MessageHandler
- RunnerTests.swift
- Fe
- require-dev
- setup
- a
- qt
- config
- MaterialPageRoute
- yn
- br
- AdminPanelProvider.php
- Be
- oe
- pe
- te
- AdminMiddleware.php
- psr-4
- widget_test.dart
- le
- extra
- booking-form.blade.php
- MainActivity
- flutter_export_environment.sh
- manage-website-settings.blade.php
- overall-reports.blade.php
- date-picker.blade.php
- @gmail
- String?
- BookingExportController
- graphify reference: query, path, explain
- graphify reference: query, path, explain
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
- graphify.md
- graphify.md
- extraction-spec.md
- copilot-instructions.md
- extraction-spec.md

## God Nodes (most connected - your core abstractions)
1. `_update()` - 88 edges
2. `x()` - 85 edges
3. `_update()` - 84 edges
4. `te()` - 74 edges
5. `V()` - 72 edges
6. `BookingForm` - 65 edges
7. `draw()` - 55 edges
8. `Booking` - 53 edges
9. `vd()` - 53 edges
10. `m()` - 49 edges

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

## Communities (246 total, 24 thin omitted)

### Community 0 - "chart.js"
Cohesion: 0.01
Nodes (122): acquireContext(), addEventListener(), afterDraw(), Ag(), alpha(), beforeDatasetDraw(), beforeDatasetsDraw(), bh() (+114 more)

### Community 1 - "main.dart"
Cohesion: 0.01
Nodes (197): dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex, adults, availableAccommodations (+189 more)

### Community 2 - "rich-editor.js"
Cohesion: 0.02
Nodes (120): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), Ca(), canRedo() (+112 more)

### Community 3 - "chart.js"
Cohesion: 0.02
Nodes (99): aa(), active(), addControllers(), addPlugins(), addScales(), an(), _animateOptions(), aspectRatio() (+91 more)

### Community 4 - "markdown-editor.js"
Cohesion: 0.04
Nodes (183): u(), be(), _a(), Ac(), Ae(), af(), ai(), An() (+175 more)

### Community 5 - "User"
Cohesion: 0.03
Nodes (34): AccommodationResource, CreateAccommodation, Form, Table, CreateBooking, DiscountResource, CreateDiscount, Form (+26 more)

### Community 6 - "getLength"
Cohesion: 0.03
Nodes (120): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), breakFormattedBlock(), breaksOnReturn() (+112 more)

### Community 7 - "constructor"
Cohesion: 0.04
Nodes (88): _a(), abutsStart(), after(), afterAutoSkip(), Ah(), Ai(), before(), Br() (+80 more)

### Community 8 - "select.js"
Cohesion: 0.07
Nodes (71): [g](), [x](), Sg(), $c(), ca(), D(), E(), Ea() (+63 more)

### Community 9 - "draw"
Cohesion: 0.04
Nodes (115): ad(), adjustHitBoxes(), ae(), af(), _calculateBarValuePixels(), calculateLabelRotation(), _calculatePadding(), cd() (+107 more)

### Community 10 - "_update"
Cohesion: 0.04
Nodes (88): addBox(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+80 more)

### Community 11 - "format"
Cohesion: 0.03
Nodes (91): Bl(), Ce(), cf(), chartOptionScopes(), clone(), constructor(), create(), describe() (+83 more)

### Community 12 - "x"
Cohesion: 0.10
Nodes (81): define(), ad(), al(), at(), B(), br(), Bt(), cd() (+73 more)

### Community 13 - "P"
Cohesion: 0.05
Nodes (59): addControllers(), addElements(), addPlugins(), addScales(), as(), At(), Bi(), Bs() (+51 more)

### Community 14 - "Booking"
Cohesion: 0.04
Nodes (32): OverallReports, DashboardSummaryWidget, BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested (+24 more)

### Community 15 - "Model"
Cohesion: 0.22
Nodes (4): BookingController, Request, Discount, HasMany

### Community 16 - "file-upload.js"
Cohesion: 0.05
Nodes (53): ba(), bi(), c(), ca(), clickPercent(), constructor(), de(), e() (+45 more)

### Community 17 - "setSelectedRange"
Cohesion: 0.03
Nodes (81): ArrowLeft(), ArrowRight(), attachmentManagerDidRequestRemovalOfAttachment(), backspace(), canAcceptDataTransfer(), canApplyToDocument(), compositionControllerDidFocus(), compositionControllerDidRequestRemovalOfAttachment() (+73 more)

### Community 18 - "qt"
Cohesion: 0.09
Nodes (23): applyStack(), beforeDraw(), _drawDatasets(), eh(), fa(), _getSortedDatasetMetas(), getSortedVisibleDatasetMetas(), getVisibleDatasetCount() (+15 more)

### Community 19 - "updateElements"
Cohesion: 0.05
Nodes (63): aspectRatio(), C(), Ca(), _calculateBarIndexPixels(), calculateCircumference(), _circumference(), co(), _computeAngle() (+55 more)

### Community 20 - "te"
Cohesion: 0.04
Nodes (15): Bi(), bn(), Id(), ji(), pi(), qi(), Ri(), Rr() (+7 more)

### Community 21 - "BookingForm"
Cohesion: 0.05
Nodes (6): BookingForm, Collection, Accommodation, BelongsToMany, BelongsTo, TourDate

### Community 22 - "getSelectedRange"
Cohesion: 0.06
Nodes (65): attachFiles(), compositionShouldAcceptFile(), createLinkHTML(), decreaseListLevel(), deleteByDrag(), drop(), findRangesOfBlocks(), formatRemove() (+57 more)

### Community 23 - "I"
Cohesion: 0.06
Nodes (47): Bt(), xo(), addEventListener(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), buildOrUpdateScales(), cl() (+39 more)

### Community 24 - "getContext"
Cohesion: 0.09
Nodes (36): buildTicks(), calculateLabelRotation(), _computeAngle(), _computeLabelItems(), computeTickLimit(), _drawArgs(), ec(), Fc() (+28 more)

### Community 25 - "support.js"
Cohesion: 0.06
Nodes (40): ai(), apply(), B(), co(), Cr(), $e(), es(), Et() (+32 more)

### Community 26 - "T"
Cohesion: 0.24
Nodes (10): tl(), ac(), Ai(), ca(), Li(), oc(), ro(), sc() (+2 more)

### Community 27 - "getDatasetMeta"
Cohesion: 0.06
Nodes (51): addElements(), afterDatasetsUpdate(), afterDraw(), buildOrUpdateControllers(), buildOrUpdateElements(), _checkEventBindings(), _dataCheck(), _destroy() (+43 more)

### Community 28 - "constructor"
Cohesion: 0.07
Nodes (37): beforeinput(), compositionend(), compositionstart(), compositionupdate(), didMutate(), elementDidMutate(), findSignificantMutations(), formResetCallback() (+29 more)

### Community 29 - "notifications.js"
Cohesion: 0.06
Nodes (24): actions(), button(), constructor(), danger(), dispatch(), dispatchSelf(), dispatchTo(), duration() (+16 more)

### Community 30 - "RelationManager"
Cohesion: 0.07
Nodes (23): AccommodationsRelationManager, Form, Table, PassengersRelationManager, Form, Table, Form, Table (+15 more)

### Community 31 - "Schedule"
Cohesion: 0.08
Nodes (8): BelongsTo, BelongsToMany, Builder, HasMany, Schedule, BelongsToMany, TransportClass, TransportClassSeeder

### Community 32 - "deleteInDirection"
Cohesion: 0.31
Nodes (13): canDecreaseNestingLevel(), canIncreaseNestingLevel(), decreaseNestingLevel(), formatIndent(), formatOutdent(), getBlock(), getLastNestableAttribute(), getListItemAttributes() (+5 more)

### Community 33 - "draw"
Cohesion: 0.08
Nodes (49): $h(), acquireContext(), adjustHitBoxes(), bc(), Bl(), clear(), _computeLabelArea(), _computeTitleHeight() (+41 more)

### Community 34 - "setAttribute"
Cohesion: 0.09
Nodes (39): add(), applyKeyboardCommand(), attachmentDidChangeAttributes(), attachmentEditorDidRequestRemovalOfAttachment(), canBeGrouped(), checkValidity(), createCaptionElement(), createContentNodes() (+31 more)

### Community 35 - "on"
Cohesion: 0.07
Nodes (39): Ac(), an(), Au(), ba(), bu(), color(), darken(), Dc() (+31 more)

### Community 36 - "render"
Cohesion: 0.07
Nodes (36): cacheViewForObject(), canSyncDocumentView(), compositionDidChangeDocument(), compositionDidLoadSnapshot(), createAttachmentNodes(), createChildView(), createContainerElement(), createDocumentFragmentForSync() (+28 more)

### Community 37 - "_update"
Cohesion: 0.07
Nodes (45): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate(), beforeBuildTicks() (+37 more)

### Community 38 - "EditRecord"
Cohesion: 0.09
Nodes (11): EditAccommodation, EditBooking, EditDiscount, EditFerryRoute, EditSchedule, EditTour, EditTransportClass, EditUser (+3 more)

### Community 39 - "Controller"
Cohesion: 0.10
Nodes (10): AccommodationController, DiscountController, PromotionController, Request, ScheduleController, Controller, FerryRoute, BelongsTo (+2 more)

### Community 40 - "ListRecords"
Cohesion: 0.08
Nodes (13): ListAccommodations, ListBookings, ListDiscounts, ListFerryRoutes, ListInquiries, ListSchedules, ListTransactions, ListTransportClasses (+5 more)

### Community 41 - "get"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), kh(), _notify() (+6 more)

### Community 42 - "cd"
Cohesion: 0.09
Nodes (28): average(), clear(), cn(), Da(), dataset(), getCenterPoint(), _getLegendItemAt(), getProps() (+20 more)

### Community 43 - "Seeder"
Cohesion: 0.06
Nodes (20): Inquiry, Passenger, BelongsTo, BelongsTo, ScheduleAccommodation, BelongsTo, UserLoginHistory, VehicleRate (+12 more)

### Community 44 - "vd"
Cohesion: 0.29
Nodes (6): e(), i(), l(), Ni(), o(), t()

### Community 45 - "Vn"
Cohesion: 0.16
Nodes (33): _a(), aa(), ba(), Be(), Bi(), br(), Ca(), ce() (+25 more)

### Community 46 - "getOptionScopes"
Cohesion: 0.06
Nodes (49): Yn(), Ge(), _a(), add(), al(), ba(), _cachedScopes(), chartOptionScopes() (+41 more)

### Community 47 - "CreateRecord"
Cohesion: 0.09
Nodes (39): C(), Co(), _computeLabelSizes(), cr(), diff(), endOf(), Et(), format() (+31 more)

### Community 48 - "State"
Cohesion: 0.10
Nodes (31): ActivityScreen, _ActivityScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState, DiscountScreen, _DiscountScreenState (+23 more)

### Community 49 - "C"
Cohesion: 0.07
Nodes (53): Nc(), afterAutoSkip(), Ao(), applyStack(), ar(), as(), Bi(), buildLookupTable() (+45 more)

### Community 50 - "fn"
Cohesion: 0.14
Nodes (30): Qt(), Cn(), da(), En(), fa(), Fi(), fn(), h() (+22 more)

### Community 51 - "getSortedVisibleDatasetMetas"
Cohesion: 0.07
Nodes (36): alpha(), be(), beforeDraw(), ea(), en(), fe(), ge(), _getSortedDatasetMetas() (+28 more)

### Community 52 - "Tour"
Cohesion: 0.11
Nodes (10): ListTours, Form, Table, TourResource, Request, TourController, TourController, HasMany (+2 more)

### Community 53 - "_each"
Cohesion: 0.10
Nodes (25): cancel(), _createDescriptors(), _descriptors(), dl(), Do(), _getLegendItemAt(), getPlugin(), _handleEvent() (+17 more)

### Community 54 - "St"
Cohesion: 0.09
Nodes (30): At(), average(), dataset(), Fa(), fn(), getBasePosition(), getBaseValue(), getCenterPoint() (+22 more)

### Community 55 - "notifyEditorElement"
Cohesion: 0.11
Nodes (22): actionIsExternal(), canInvokeAction(), compositionControllerDidBlur(), compositionControllerDidRender(), compositionControllerDidSyncDocumentView(), compositionDidAddAttachment(), compositionDidChangeAttachmentPreviewURL(), compositionDidChangeCurrentAttributes() (+14 more)

### Community 56 - "AuthController"
Cohesion: 0.09
Nodes (17): AuthController, Request, HasMany, Panel, User, AppServiceProvider, AdminPanelProvider, Panel (+9 more)

### Community 57 - "my_application.cc"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 58 - "echo.js"
Cohesion: 0.10
Nodes (13): a(), ar(), at(), cr(), d(), f(), H(), ji() (+5 more)

### Community 59 - "m"
Cohesion: 0.25
Nodes (25): d(), Di(), f(), Ge(), I(), ir(), ja(), k() (+17 more)

### Community 60 - "getDatasetMeta"
Cohesion: 0.12
Nodes (24): afterDatasetsUpdate(), _d(), generateLabels(), getDatasetMeta(), getDataVisibility(), getMaxBorderWidth(), getStyle(), _handleEvent() (+16 more)

### Community 61 - "InquiryResource.php"
Cohesion: 0.10
Nodes (10): InquiryResource, ViewInquiry, Form, Table, ViewTransaction, ViewUserLoginHistory, Form, Table (+2 more)

### Community 62 - "DatePicker"
Cohesion: 0.08
Nodes (7): Form, ViewBooking, DatePicker, PaymentProof, UserDashboard, Component, WithFileUploads

### Community 63 - "PaymentSetting"
Cohesion: 0.05
Nodes (26): Action, DeleteAllUsers, PurgeExpiredProofs, ManagePaymentSettings, Form, ManageProofs, Collection, Form (+18 more)

### Community 64 - "StatelessWidget"
Cohesion: 0.10
Nodes (20): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+12 more)

### Community 65 - "appendBlockForElement"
Cohesion: 0.20
Nodes (19): appendAttachmentWithAttributes(), appendBlockForAttributesWithElement(), appendBlockForElement(), appendBlockForTextNode(), appendEmptyBlock(), appendPiece(), appendStringWithAttributes(), find() (+11 more)

### Community 66 - "scripts"
Cohesion: 0.11
Nodes (19): scripts, dev, post-autoload-dump, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout (+11 more)

### Community 67 - "getAttachments"
Cohesion: 0.10
Nodes (22): canSetCurrentAttribute(), canSetCurrentBlockAttribute(), canSetCurrentTextAttribute(), copy(), cut(), didClickAttachment(), dragstart(), findAttachmentForElement() (+14 more)

### Community 68 - "Vehicle"
Cohesion: 0.10
Nodes (9): Form, Table, VehicleResource, self, HasMany, Vehicle, UserFactory, Factory (+1 more)

### Community 69 - "devDependencies"
Cohesion: 0.11
Nodes (17): concurrently, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+9 more)

### Community 70 - "FlutterWindow"
Cohesion: 0.13
Nodes (13): DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow, flutter_controller_ (+5 more)

### Community 71 - "win32_window.cpp"
Cohesion: 0.18
Nodes (14): wchar_t, Scale(), Create, Destroy, UpdateTheme, Win32Window::Win32Window(), WindowClassRegistrar, class_registered_ (+6 more)

### Community 72 - "qe"
Cohesion: 0.23
Nodes (18): Ae(), at(), de(), dt(), fr(), Gt(), ht(), It() (+10 more)

### Community 73 - "GeneratedPluginRegistrant.swift"
Cohesion: 0.14
Nodes (12): Cocoa, file_selector_macos, RegisterGeneratedPlugins(), MainFlutterWindow, FlutterMacOS, FlutterPluginRegistry, FlutterViewController, Foundation (+4 more)

### Community 74 - "preload"
Cohesion: 0.10
Nodes (22): attachmentForFile(), attributesForFile(), didChangeAttributes(), getContentType(), getHeight(), getHref(), getPreviewURL(), getType() (+14 more)

### Community 76 - "Win32Window"
Cohesion: 0.14
Nodes (16): RegisterPlugins(), OnCreate, OnDestroy, HWND, Win32Window, child_content_, GetClientArea, OnCreate (+8 more)

### Community 77 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 78 - "require"
Cohesion: 0.14
Nodes (14): require, anhskohbo/no-captcha, dompdf/dompdf, filament/filament, filament/support, intervention/image, laravel/framework, laravel/tinker (+6 more)

### Community 80 - "constructor"
Cohesion: 0.08
Nodes (30): box(), canBeConsolidatedWith(), canBeGroupedWith(), canDecreaseBlockAttributeLevel(), constructor(), disabled(), formDisabledCallback(), fromUCS2String() (+22 more)

### Community 81 - "manifest.json"
Cohesion: 0.15
Nodes (12): background_color, categories, description, display, icons, name, orientation, short_name (+4 more)

### Community 82 - "wWinMain"
Cohesion: 0.24
Nodes (9): wWinMain(), string, wchar_t, CreateAndAttachConsole(), GetCommandLineArguments(), Utf8FromUtf16(), _In_, _In_opt_ (+1 more)

### Community 83 - "app.js"
Cohesion: 0.26
Nodes (7): C(), D(), J(), O(), U(), v(), X()

### Community 84 - "r"
Cohesion: 0.18
Nodes (12): Be(), ei(), ii(), le(), ni(), oi(), r(), ri() (+4 more)

### Community 85 - "t"
Cohesion: 0.15
Nodes (14): Ce(), De(), di(), e(), Ht(), Ie(), Me(), Re() (+6 more)

### Community 86 - "manifest.json"
Cohesion: 0.18
Nodes (10): background_color, description, display, icons, name, orientation, prefer_related_applications, short_name (+2 more)

### Community 87 - "A"
Cohesion: 0.21
Nodes (15): Aa(), da(), ef(), fa(), Ln(), qc(), uo(), Yc() (+7 more)

### Community 88 - "buildTicks"
Cohesion: 0.06
Nodes (51): aa(), add(), Al(), ar(), bf(), buildTicks(), _cachedScopes(), count() (+43 more)

### Community 89 - ".application"
Cohesion: 0.20
Nodes (8): Any, AppDelegate, Bool, AppDelegate, Bool, FlutterAppDelegate, NSApplication, UIApplication

### Community 90 - "MessageHandler"
Cohesion: 0.36
Nodes (10): HWND, LPARAM, LRESULT, UINT, WPARAM, EnableFullDpiSupportIfAvailable(), GetHandle, GetThisFromHandle (+2 more)

### Community 91 - "RunnerTests.swift"
Cohesion: 0.25
Nodes (5): Flutter, RunnerTests, RunnerTests, UIKit, XCTestCase

### Community 92 - "Fe"
Cohesion: 0.20
Nodes (11): b(), Dt(), Fe(), g(), He(), i(), ir(), Mt() (+3 more)

### Community 93 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 94 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 95 - "a"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 96 - "qt"
Cohesion: 0.36
Nodes (8): hs(), Ln(), Nn(), ps(), qt(), Ro(), Se(), wo()

### Community 97 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 98 - "MaterialPageRoute"
Cohesion: 0.29
Nodes (7): build, _goNext, _goToSchedule, _selectTransportOption, _showAirlineClassPicker, _showFerryAccommodationPicker, MaterialPageRoute

### Community 99 - "yn"
Cohesion: 0.33
Nodes (7): ar(), ft(), kn(), sr(), wn(), Ye(), yn()

### Community 100 - "br"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 101 - "AdminPanelProvider.php"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 106 - "AdminMiddleware.php"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 107 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 108 - "widget_test.dart"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 109 - "le"
Cohesion: 0.29
Nodes (3): BookingResource, Form, Table

### Community 110 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 229 - "graphify reference: query, path, explain"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 230 - "graphify reference: query, path, explain"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 231 - "graphify reference: add a URL and watch a folder"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 232 - "graphify reference: commit hook and native CLAUDE.md integration"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 233 - "graphify reference: incremental update and cluster-only"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 234 - "graphify reference: add a URL and watch a folder"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 235 - "graphify reference: commit hook and native CLAUDE.md integration"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 236 - "graphify reference: incremental update and cluster-only"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

## Knowledge Gaps
- **362 isolated node(s):** `graphify`, `Workflow: graphify`, `Usage`, `What graphify is for`, `Step 0 - GitHub repos and multi-path merge (only if a URL or several paths)` (+357 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **24 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `A()` connect `A` to `draw`, `rich-editor.js`, `setAttribute`, `markdown-editor.js`, `select.js`, `draw`, `x`, `constructor`, `fn`, `m`?**
  _High betweenness centrality (0.042) - this node is a cross-community bridge._
- **Why does `draw()` connect `draw` to `chart.js`, `on`, `select.js`, `cd`, `_update`, `qt`, `updateElements`, `I`, `A`, `getContext`, `m`?**
  _High betweenness centrality (0.036) - this node is a cross-community bridge._
- **Why does `te()` connect `te` to `markdown-editor.js`, `select.js`, `_update`, `x`, `file-upload.js`, `A`, `echo.js`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Are the 16 inferred relationships involving `x()` (e.g. with `g()` and `_i()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **Are the 20 inferred relationships involving `te()` (e.g. with `je()` and `Pr()`) actually correct?**
  _`te()` has 20 INFERRED edges - model-reasoned connections that need verification._
- **Are the 29 inferred relationships involving `V()` (e.g. with `Sg()` and `g()`) actually correct?**
  _`V()` has 29 INFERRED edges - model-reasoned connections that need verification._
- **What connects `graphify`, `Workflow: graphify`, `Usage` to the rest of the system?**
  _362 weakly-connected nodes found - possible documentation gaps or missing edges._