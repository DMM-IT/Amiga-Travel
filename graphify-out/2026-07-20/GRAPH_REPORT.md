# Graph Report - .  (2026-07-20)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 4624 nodes · 11729 edges · 226 communities (210 shown, 16 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1114 edges (avg confidence: 0.67)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0371d25f`
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
- `constructor()` --indirect_call--> `Yn()`  [INFERRED]
  public/js/filament/widgets/components/stats-overview/stat/chart.js → public/js/filament/filament/echo.js
- `te()` --indirect_call--> `Pr()`  [INFERRED]
  public/js/filament/forms/components/markdown-editor.js → public/js/filament/filament/echo.js
- `getExtension()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/file-upload.js → public/js/filament/forms/components/markdown-editor.js
- `_getTestState()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/file-upload.js → public/js/filament/forms/components/markdown-editor.js
- `dt()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/rich-editor.js → public/js/filament/forms/components/markdown-editor.js

## Import Cycles
- None detected.

## Communities (226 total, 16 thin omitted)

### Community 0 - "chart.js"
Cohesion: 0.01
Nodes (109): acquireContext(), afterDraw(), alpha(), beforeDatasetDraw(), beforeDatasetsDraw(), bh(), Br(), Bt() (+101 more)

### Community 1 - "main.dart"
Cohesion: 0.01
Nodes (197): dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex, adults, availableAccommodations (+189 more)

### Community 2 - "rich-editor.js"
Cohesion: 0.02
Nodes (127): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), Ca(), canRedo() (+119 more)

### Community 3 - "chart.js"
Cohesion: 0.02
Nodes (120): aa(), active(), alpha(), an(), _animateOptions(), be(), beforeDatasetDraw(), beforeDatasetsDraw() (+112 more)

### Community 4 - "markdown-editor.js"
Cohesion: 0.04
Nodes (123): _a(), Aa(), Ac(), ad(), af(), al(), An(), Be() (+115 more)

### Community 5 - "User"
Cohesion: 0.03
Nodes (49): Action, ManagePaymentSettings, Form, ManageProofs, Collection, Form, Collection, StaffPerformance (+41 more)

### Community 6 - "getLength"
Cohesion: 0.03
Nodes (127): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), breakFormattedBlock(), breaksOnReturn() (+119 more)

### Community 7 - "constructor"
Cohesion: 0.03
Nodes (122): _a(), abutsStart(), after(), afterAutoSkip(), Ag(), Ai(), Al(), before() (+114 more)

### Community 8 - "select.js"
Cohesion: 0.09
Nodes (59): [g](), [x](), $c(), D(), E(), g(), H(), _i() (+51 more)

### Community 9 - "draw"
Cohesion: 0.04
Nodes (112): ad(), adjustHitBoxes(), ae(), af(), C(), calculateLabelRotation(), _calculatePadding(), _computeGridLineItems() (+104 more)

### Community 10 - "_update"
Cohesion: 0.04
Nodes (88): addBox(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+80 more)

### Community 11 - "format"
Cohesion: 0.05
Nodes (64): Bl(), cf(), clone(), create(), Dl(), dtFormatter(), eg(), el() (+56 more)

### Community 12 - "x"
Cohesion: 0.10
Nodes (78): Sg(), at(), B(), br(), Bt(), ca(), cd(), Cr() (+70 more)

### Community 13 - "P"
Cohesion: 0.07
Nodes (42): addControllers(), addElements(), addPlugins(), addScales(), as(), At(), Bs(), buildOrUpdateControllers() (+34 more)

### Community 14 - "Booking"
Cohesion: 0.05
Nodes (21): OverallReports, DashboardSummaryWidget, BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested (+13 more)

### Community 15 - "Model"
Cohesion: 0.06
Nodes (27): Accommodation, BelongsToMany, Discount, HasMany, Inquiry, Passenger, BelongsTo, BelongsTo (+19 more)

### Community 16 - "file-upload.js"
Cohesion: 0.05
Nodes (46): ba(), bi(), c(), ca(), clickPercent(), constructor(), e(), getExtension() (+38 more)

### Community 17 - "setSelectedRange"
Cohesion: 0.06
Nodes (41): ArrowLeft(), ArrowRight(), canAcceptDataTransfer(), compositionControllerDidFocus(), compositionDidRequestChangingSelectionToLocationRange(), createDOMRangeFromLocationRange(), createDOMRangeFromPoint(), dragend() (+33 more)

### Community 18 - "qt"
Cohesion: 0.05
Nodes (48): addEventListener(), Ah(), Au(), ba(), beforeDraw(), bindResponsiveEvents(), bu(), ch() (+40 more)

### Community 19 - "updateElements"
Cohesion: 0.05
Nodes (66): applyStack(), aspectRatio(), buildOrUpdateElements(), _calculateBarIndexPixels(), _calculateBarValuePixels(), calculateCircumference(), Ce(), _circumference() (+58 more)

### Community 20 - "te"
Cohesion: 0.04
Nodes (15): Bi(), bn(), Id(), ji(), Jr(), kd(), on(), qi() (+7 more)

### Community 22 - "getSelectedRange"
Cohesion: 0.06
Nodes (68): attachFiles(), createLinkHTML(), cut(), decreaseListLevel(), drop(), findRangesOfBlocks(), fromJSON(), fromJSONString() (+60 more)

### Community 23 - "I"
Cohesion: 0.05
Nodes (56): addEventListener(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), buildOrUpdateScales(), cl(), _computeLabelSizes(), cs() (+48 more)

### Community 24 - "getContext"
Cohesion: 0.07
Nodes (54): acquireContext(), calculateLabelRotation(), _calculatePadding(), _computeAngle(), _computeGridLineItems(), _computeLabelItems(), computeTickLimit(), _drawArgs() (+46 more)

### Community 25 - "support.js"
Cohesion: 0.06
Nodes (40): ai(), apply(), B(), co(), Cr(), $e(), es(), Et() (+32 more)

### Community 26 - "T"
Cohesion: 0.07
Nodes (50): xg(), ac(), Ai(), applyStack(), ar(), as(), aspectRatio(), ca() (+42 more)

### Community 27 - "getDatasetMeta"
Cohesion: 0.06
Nodes (50): addElements(), afterDatasetsUpdate(), buildOrUpdateControllers(), buildOrUpdateElements(), _checkEventBindings(), configure(), _dataCheck(), datasetAnimationScopeKeys() (+42 more)

### Community 28 - "constructor"
Cohesion: 0.06
Nodes (45): beforeinput(), box(), canApplyToDocument(), compositionend(), compositionstart(), compositionupdate(), constructor(), elementDidMutate() (+37 more)

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
Cohesion: 0.06
Nodes (49): backspace(), canDecreaseNestingLevel(), canIncreaseNestingLevel(), createLocationRangeFromDOMRange(), d(), decreaseNestingLevel(), delete(), deleteByComposition() (+41 more)

### Community 33 - "draw"
Cohesion: 0.08
Nodes (43): adjustHitBoxes(), afterDraw(), bc(), Bl(), clear(), _computeLabelArea(), _computeTitleHeight(), _createItems() (+35 more)

### Community 34 - "setAttribute"
Cohesion: 0.09
Nodes (39): add(), applyKeyboardCommand(), attachmentDidChangeAttributes(), attachmentEditorDidRequestRemovalOfAttachment(), canBeGrouped(), checkValidity(), createCaptionElement(), createContentNodes() (+31 more)

### Community 35 - "on"
Cohesion: 0.08
Nodes (32): Ac(), an(), color(), darken(), Dc(), desaturate(), eo(), fo() (+24 more)

### Community 36 - "render"
Cohesion: 0.07
Nodes (39): attachmentManagerDidRequestRemovalOfAttachment(), cacheViewForObject(), canSyncDocumentView(), compositionControllerDidRequestRemovalOfAttachment(), compositionDidChangeDocument(), compositionDidLoadSnapshot(), createAttachmentNodes(), createChildView() (+31 more)

### Community 37 - "_update"
Cohesion: 0.08
Nodes (39): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate(), beforeBuildTicks() (+31 more)

### Community 38 - "EditRecord"
Cohesion: 0.07
Nodes (14): EditAccommodation, EditBooking, EditDiscount, FerryRouteResource, EditFerryRoute, Form, Table, EditSchedule (+6 more)

### Community 39 - "Controller"
Cohesion: 0.08
Nodes (11): AccommodationController, DiscountController, PromotionController, Request, ScheduleController, Controller, FerryRoute, BelongsTo (+3 more)

### Community 40 - "ListRecords"
Cohesion: 0.08
Nodes (13): ListAccommodations, ListBookings, ListDiscounts, ListFerryRoutes, ListInquiries, ListSchedules, ListTransactions, ListTransportClasses (+5 more)

### Community 41 - "get"
Cohesion: 0.09
Nodes (32): active(), add(), _animateOptions(), _cachedScopes(), cancel(), _createAnimations(), _createDescriptors(), _descriptors() (+24 more)

### Community 42 - "cd"
Cohesion: 0.08
Nodes (32): average(), Ca(), cd(), clear(), cn(), Da(), Fc(), fh() (+24 more)

### Community 43 - "Seeder"
Cohesion: 0.08
Nodes (11): ManageWebsiteSettings, Form, WebsiteSetting, DatabaseSeeder, DiscountSeeder, ScheduleAccommodationSeeder, TourHotelsSeeder, VehicleRateSeeder (+3 more)

### Community 44 - "vd"
Cohesion: 0.06
Nodes (91): e(), i(), l(), Ni(), o(), t(), u(), be() (+83 more)

### Community 45 - "Vn"
Cohesion: 0.16
Nodes (33): _a(), aa(), ba(), Be(), Bi(), br(), Ca(), ce() (+25 more)

### Community 46 - "getOptionScopes"
Cohesion: 0.08
Nodes (33): _a(), al(), ba(), _cachedScopes(), createResolver(), datasetElementScopeKeys(), fn(), get() (+25 more)

### Community 47 - "CreateRecord"
Cohesion: 0.09
Nodes (15): CreateAccommodation, CreateBooking, CreateDiscount, CreateFerryRoute, CreateInquiry, CreateSchedule, CreateTour, CreateTransportClass (+7 more)

### Community 48 - "State"
Cohesion: 0.10
Nodes (31): ActivityScreen, _ActivityScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState, DiscountScreen, _DiscountScreenState (+23 more)

### Community 49 - "C"
Cohesion: 0.06
Nodes (61): add(), afterAutoSkip(), Ao(), Bi(), buildLookupTable(), buildTicks(), C(), Co() (+53 more)

### Community 50 - "fn"
Cohesion: 0.14
Nodes (30): Qt(), Cn(), da(), En(), fa(), Fi(), fn(), h() (+22 more)

### Community 52 - "Tour"
Cohesion: 0.11
Nodes (10): ListTours, Form, Table, TourResource, Request, TourController, TourController, HasMany (+2 more)

### Community 53 - "_each"
Cohesion: 0.08
Nodes (31): addControllers(), addPlugins(), addScales(), cancel(), _createDescriptors(), _descriptors(), dl(), Do() (+23 more)

### Community 54 - "St"
Cohesion: 0.10
Nodes (28): At(), average(), beforeDraw(), dataset(), Fa(), getCenterPoint(), getMaximumSize(), getProps() (+20 more)

### Community 55 - "notifyEditorElement"
Cohesion: 0.09
Nodes (27): actionIsExternal(), canBeConsolidatedWith(), canInvokeAction(), compositionControllerDidBlur(), compositionControllerDidRender(), compositionControllerDidSyncDocumentView(), compositionDidAddAttachment(), compositionDidChangeAttachmentPreviewURL() (+19 more)

### Community 56 - "AuthController"
Cohesion: 0.14
Nodes (8): AuthController, Request, BookingExportController, AppServiceProvider, RedirectResponse, Response, ServiceProvider, View

### Community 57 - "my_application.cc"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 58 - "echo.js"
Cohesion: 0.10
Nodes (11): ar(), b(), cr(), g(), Me(), P(), Pr(), qt() (+3 more)

### Community 59 - "m"
Cohesion: 0.25
Nodes (25): d(), Di(), f(), Ge(), I(), ir(), ja(), k() (+17 more)

### Community 60 - "getDatasetMeta"
Cohesion: 0.12
Nodes (24): afterDatasetsUpdate(), _d(), generateLabels(), getDatasetMeta(), getDataVisibility(), getMaxBorderWidth(), getStyle(), _handleEvent() (+16 more)

### Community 61 - "InquiryResource.php"
Cohesion: 0.11
Nodes (9): Form, ViewBooking, InquiryResource, ViewInquiry, Form, Table, ViewTransaction, ViewUserLoginHistory (+1 more)

### Community 62 - "DatePicker"
Cohesion: 0.10
Nodes (5): DatePicker, PaymentProof, UserDashboard, Component, WithFileUploads

### Community 63 - "PaymentSetting"
Cohesion: 0.14
Nodes (6): DeleteAllUsers, PurgeExpiredProofs, BookingController, Request, PaymentSetting, Command

### Community 64 - "StatelessWidget"
Cohesion: 0.10
Nodes (20): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+12 more)

### Community 65 - "appendBlockForElement"
Cohesion: 0.18
Nodes (20): appendAttachmentWithAttributes(), appendBlockForAttributesWithElement(), appendBlockForElement(), appendBlockForTextNode(), appendEmptyBlock(), appendPiece(), appendStringWithAttributes(), find() (+12 more)

### Community 66 - "scripts"
Cohesion: 0.11
Nodes (19): scripts, dev, post-autoload-dump, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout (+11 more)

### Community 67 - "getAttachments"
Cohesion: 0.11
Nodes (20): canSetCurrentAttribute(), canSetCurrentBlockAttribute(), canSetCurrentTextAttribute(), compositionControllerDidRequestDeselectingAttachment(), compositionDidStartEditingAttachment(), didClickAttachment(), dragstart(), findAttachmentForElement() (+12 more)

### Community 68 - "Vehicle"
Cohesion: 0.14
Nodes (6): self, HasMany, Vehicle, UserFactory, Factory, static

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
Cohesion: 0.15
Nodes (11): Cocoa, file_selector_macos, RegisterGeneratedPlugins(), MainFlutterWindow, FlutterMacOS, FlutterPluginRegistry, FlutterViewController, Foundation (+3 more)

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
Cohesion: 0.21
Nodes (11): di(), e(), Ht(), i(), Ie(), Mt(), Re(), t() (+3 more)

### Community 86 - "manifest.json"
Cohesion: 0.18
Nodes (10): background_color, description, display, icons, name, orientation, prefer_related_applications, short_name (+2 more)

### Community 87 - "A"
Cohesion: 0.36
Nodes (8): A(), connectedCallback(), Ge(), Je(), required(), setCustomValidity(), setFormValue(), Qe()

### Community 88 - "buildTicks"
Cohesion: 0.08
Nodes (35): aa(), ar(), bf(), buildTicks(), determineDataLimits(), Dh(), _generate(), getDataTimestamps() (+27 more)

### Community 89 - ".application"
Cohesion: 0.20
Nodes (8): Any, AppDelegate, Bool, AppDelegate, Bool, FlutterAppDelegate, NSApplication, UIApplication

### Community 90 - "MessageHandler"
Cohesion: 0.36
Nodes (10): HWND, LPARAM, LRESULT, UINT, WPARAM, EnableFullDpiSupportIfAvailable(), GetHandle, GetThisFromHandle (+2 more)

### Community 91 - "RunnerTests.swift"
Cohesion: 0.22
Nodes (6): Flutter, RunnerTests, RunnerTests, UIKit, XCTest, XCTestCase

### Community 92 - "Fe"
Cohesion: 0.22
Nodes (9): Ce(), De(), Dt(), Fe(), He(), ir(), nr(), rt() (+1 more)

### Community 93 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 94 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 95 - "a"
Cohesion: 0.25
Nodes (8): a(), at(), d(), f(), H(), ji(), L(), pt()

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
Cohesion: 0.29
Nodes (7): chartOptionScopes(), br(), ii(), vr(), xr(), Xs(), yr()

### Community 101 - "AdminPanelProvider.php"
Cohesion: 0.47
Nodes (4): AdminPanelProvider, Panel, Color, PanelProvider

### Community 106 - "AdminMiddleware.php"
Cohesion: 0.60
Nodes (3): AdminMiddleware, Request, Closure

### Community 107 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 108 - "widget_test.dart"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 110 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **277 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+272 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **16 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `A()` connect `A` to `draw`, `rich-editor.js`, `setAttribute`, `markdown-editor.js`, `select.js`, `draw`, `vd`, `x`, `fn`, `m`, `constructor`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **Why does `draw()` connect `draw` to `chart.js`, `on`, `select.js`, `cd`, `_update`, `qt`, `updateElements`, `I`, `A`, `getContext`, `m`?**
  _High betweenness centrality (0.050) - this node is a cross-community bridge._
- **Why does `Br()` connect `chart.js` to `setAttribute`, `constructor`, `x`, `t`, `constructor`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Are the 16 inferred relationships involving `x()` (e.g. with `g()` and `_i()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **Are the 20 inferred relationships involving `te()` (e.g. with `je()` and `Pr()`) actually correct?**
  _`te()` has 20 INFERRED edges - model-reasoned connections that need verification._
- **Are the 29 inferred relationships involving `V()` (e.g. with `Sg()` and `g()`) actually correct?**
  _`V()` has 29 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _277 weakly-connected nodes found - possible documentation gaps or missing edges._