# Graph Report - Amiga-Travel  (2026-07-22)

## Corpus Check
- 367 files · ~236,209 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 5094 nodes · 12322 edges · 282 communities (257 shown, 25 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1161 edges (avg confidence: 0.67)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3ca4ffa8`
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
- Filament Admin & UI (C103)
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
- AccommodationResource.php
- InquiryResource.php
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
- UserResource.php
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- graphify reference: add a URL and watch a folder
- graphify reference: commit hook and native CLAUDE.md integration
- graphify reference: incremental update and cluster-only
- ExampleTest
- console.php
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
6. `Booking` - 70 edges
7. `BookingForm` - 69 edges
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
- `wWinMain()` --calls--> `CreateAndAttachConsole()`  [INFERRED]
  flutter_app/windows/runner/main.cpp → flutter_app/windows/runner/utils.cpp
- `Win32Window::Win32Window()` --calls--> `Destroy`  [INFERRED]
  flutter_app/windows/runner/win32_window.cpp → flutter_app/windows/runner/win32_window.h

## Import Cycles
- None detected.

## Communities (282 total, 25 thin omitted)

### Community 0 - "HTTP Controllers & Routing (C0)"
Cohesion: 0.01
Nodes (108): acquireContext(), addControllers(), addPlugins(), addScales(), afterDraw(), alpha(), beforeDatasetDraw(), beforeDatasetsDraw() (+100 more)

### Community 1 - "Data Models & Domain (C1)"
Cohesion: 0.01
Nodes (239): bool get, dart:async, dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex (+231 more)

### Community 2 - "HTTP Controllers & Routing (C2)"
Cohesion: 0.02
Nodes (121): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeAttributes(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), canRedo() (+113 more)

### Community 3 - "Core Module 3"
Cohesion: 0.03
Nodes (162): be(), Ac(), ad(), Ae(), af(), ai(), An(), ao() (+154 more)

### Community 4 - "Core Module 4"
Cohesion: 0.02
Nodes (131): aa(), active(), alpha(), an(), _animateOptions(), Ao(), applyStack(), aspectRatio() (+123 more)

### Community 5 - "Core Module 5"
Cohesion: 0.03
Nodes (117): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), canBeGroupedWith(), canDecreaseBlockAttributeLevel() (+109 more)

### Community 6 - "Core Module 6"
Cohesion: 0.06
Nodes (76): [g](), [x](), $c(), D(), E(), Ea(), ef(), fa() (+68 more)

### Community 7 - "Data Models & Domain (C7)"
Cohesion: 0.04
Nodes (84): addBox(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+76 more)

### Community 8 - "Core Module 8"
Cohesion: 0.04
Nodes (114): ad(), adjustHitBoxes(), ae(), af(), C(), _calculateBarValuePixels(), calculateLabelRotation(), _calculatePadding() (+106 more)

### Community 9 - "Database Schema (C9)"
Cohesion: 0.03
Nodes (105): _a(), abutsStart(), after(), afterAutoSkip(), Ag(), Ai(), before(), buildLookupTable() (+97 more)

### Community 10 - "Core Module 10"
Cohesion: 0.15
Nodes (16): clear(), cn(), Da(), fh(), gc(), _getLegendItemAt(), kn(), _o() (+8 more)

### Community 11 - "Database Schema (C11)"
Cohesion: 0.04
Nodes (22): Aa(), Bi(), bn(), cf(), Id(), Jc(), ji(), Ln() (+14 more)

### Community 12 - "Data Models & Domain (C12)"
Cohesion: 0.09
Nodes (35): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate(), beforeBuildTicks() (+27 more)

### Community 13 - "Core Module 13"
Cohesion: 0.06
Nodes (50): ba(), bi(), c(), ca(), clickPercent(), constructor(), de(), define() (+42 more)

### Community 14 - "Core Module 14"
Cohesion: 0.12
Nodes (61): Sg(), al(), at(), B(), br(), Bt(), ca(), Cr() (+53 more)

### Community 15 - "Core Module 15"
Cohesion: 0.07
Nodes (55): $h(), Te(), acquireContext(), adjustHitBoxes(), afterDraw(), bc(), Bl(), clear() (+47 more)

### Community 16 - "Data Models & Domain (C16)"
Cohesion: 0.06
Nodes (4): BookingForm, Collection, BelongsTo, TourDate

### Community 17 - "Core Module 17"
Cohesion: 0.03
Nodes (95): Ac(), Bl(), cf(), chartOptionScopes(), clone(), constructor(), create(), describe() (+87 more)

### Community 18 - "HTTP Controllers & Routing (C18)"
Cohesion: 0.06
Nodes (59): attachmentManagerDidRequestRemovalOfAttachment(), breakFormattedBlock(), breaksOnReturn(), Ca(), canSetCurrentAttribute(), canSetCurrentBlockAttribute(), compositionControllerDidRequestRemovalOfAttachment(), copyWithoutText() (+51 more)

### Community 19 - "HTTP Controllers & Routing (C19)"
Cohesion: 0.12
Nodes (27): canDecreaseNestingLevel(), canIncreaseNestingLevel(), compositionDidLoadSnapshot(), compositionDidRequestChangingSelectionToLocationRange(), decreaseNestingLevel(), formatIndent(), formatOutdent(), freezeSelection() (+19 more)

### Community 20 - "Core Module 20"
Cohesion: 0.05
Nodes (70): It(), A(), add(), applyKeyboardCommand(), attachmentEditorDidRequestRemovalOfAttachment(), box(), canBeGrouped(), checkValidity() (+62 more)

### Community 21 - "Data Models & Domain (C21)"
Cohesion: 0.17
Nodes (16): as(), At(), Bi(), Bs(), cc(), Fr(), greyscale(), io() (+8 more)

### Community 22 - "Core Module 22"
Cohesion: 0.06
Nodes (24): Accommodation, BelongsToMany, Passenger, BelongsTo, BelongsTo, ScheduleAccommodation, BelongsTo, UserLoginHistory (+16 more)

### Community 23 - "Core Module 23"
Cohesion: 0.06
Nodes (40): ai(), apply(), B(), co(), Cr(), $e(), es(), Et() (+32 more)

### Community 24 - "Filament Admin & UI (C24)"
Cohesion: 0.09
Nodes (11): ManagePaymentSettings, Form, OverallReports, Form, Collection, Form, StaffPerformance, DatePicker (+3 more)

### Community 25 - "Data Models & Domain (C25)"
Cohesion: 0.08
Nodes (44): ar(), buildTicks(), calculateLabelRotation(), _calculatePadding(), _computeAngle(), _computeGridLineItems(), _computeLabelItems(), computeTickLimit() (+36 more)

### Community 26 - "Data Models & Domain (C26)"
Cohesion: 0.05
Nodes (29): BookingsRelationManager, Form, Table, AccommodationsRelationManager, Form, Table, PassengersRelationManager, Form (+21 more)

### Community 27 - "Core Module 27"
Cohesion: 0.09
Nodes (47): e(), i(), l(), Ni(), o(), t(), u(), _a() (+39 more)

### Community 28 - "Core Module 28"
Cohesion: 0.05
Nodes (56): Yn(), Ge(), _a(), add(), ba(), _cachedScopes(), chartOptionScopes(), _computeLabelSizes() (+48 more)

### Community 29 - "Database Schema (C29)"
Cohesion: 0.04
Nodes (81): attachFiles(), backspace(), canApplyToDocument(), compositionstart(), compositionupdate(), createLinkHTML(), d(), delete() (+73 more)

### Community 30 - "Core Module 30"
Cohesion: 0.05
Nodes (59): aa(), add(), Al(), ar(), bf(), buildTicks(), _cachedScopes(), count() (+51 more)

### Community 31 - "Frontend & Components (C31)"
Cohesion: 0.06
Nodes (24): actions(), button(), constructor(), danger(), dispatch(), dispatchSelf(), dispatchTo(), duration() (+16 more)

### Community 32 - "Core Module 32"
Cohesion: 0.04
Nodes (78): addElements(), Ah(), aspectRatio(), buildOrUpdateElements(), Ca(), _calculateBarIndexPixels(), calculateCircumference(), Ce() (+70 more)

### Community 33 - "Core Module 33"
Cohesion: 0.08
Nodes (33): _calculateBarIndexPixels(), calculateCircumference(), _circumference(), countVisibleElements(), datasetAnimationScopeKeys(), dr(), getBasePixel(), getBasePosition() (+25 more)

### Community 34 - "Data Models & Domain (C34)"
Cohesion: 0.08
Nodes (8): BelongsTo, BelongsToMany, Builder, HasMany, Schedule, BelongsToMany, TransportClass, TransportClassSeeder

### Community 35 - "Data Models & Domain (C35)"
Cohesion: 0.07
Nodes (15): ListAccommodations, ListApkUsers, ListBookings, ListDiscounts, ListFerryRoutes, ListInquiries, ListSchedules, ListTransactions (+7 more)

### Community 36 - "Core Module 36"
Cohesion: 0.10
Nodes (27): At(), average(), dataset(), Fa(), getCenterPoint(), getMaximumSize(), getProps(), hasValue() (+19 more)

### Community 37 - "Filament Admin & UI (C37)"
Cohesion: 0.07
Nodes (17): BookingResource, Form, Table, AdminNotificationFeed, Collection, BaseTestCase, CreatesApplication, RefreshDatabase (+9 more)

### Community 38 - "Core Module 38"
Cohesion: 0.11
Nodes (33): as(), C(), Co(), cr(), diff(), endOf(), Et(), format() (+25 more)

### Community 39 - "Data Models & Domain (C39)"
Cohesion: 0.06
Nodes (16): BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested, RebookingVerification, Booking (+8 more)

### Community 40 - "HTTP Controllers & Routing (C40)"
Cohesion: 0.11
Nodes (25): buildOrUpdateScales(), cl(), D(), data(), E(), ensureScalesHaveIDs(), Eo(), fl() (+17 more)

### Community 41 - "Data Models & Domain (C41)"
Cohesion: 0.09
Nodes (3): ManageWebsiteSettings, Form, WebsiteSetting

### Community 42 - "Filament Admin & UI (C42)"
Cohesion: 0.08
Nodes (28): addEventListener(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), _checkEventBindings(), dn(), Du(), Ef() (+20 more)

### Community 43 - "Core Module 43"
Cohesion: 0.08
Nodes (13): ApkUserResource, Builder, Form, Table, Form, Table, ScheduleResource, Form (+5 more)

### Community 44 - "Core Module 44"
Cohesion: 0.06
Nodes (37): attachmentForFile(), attributesForFile(), canSetCurrentTextAttribute(), compositionShouldAcceptFile(), cut(), didChangeAttributes(), didClickAttachment(), dragstart() (+29 more)

### Community 45 - "Core Module 45"
Cohesion: 0.23
Nodes (18): Ae(), at(), de(), dt(), fr(), Gt(), ht(), It() (+10 more)

### Community 46 - "HTTP Controllers & Routing (C46)"
Cohesion: 0.08
Nodes (31): Bt(), xo(), addEventListener(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), cs(), Ct() (+23 more)

### Community 47 - "Core Module 47"
Cohesion: 0.04
Nodes (57): an(), applyStack(), Au(), average(), ba(), beforeDraw(), bu(), cu() (+49 more)

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
Cohesion: 0.10
Nodes (30): afterDatasetsUpdate(), buildOrUpdateControllers(), _d(), _destroyDatasetMeta(), generateLabels(), getDatasetMeta(), getDataVisibility(), getMaxBorderWidth() (+22 more)

### Community 53 - "Filament Admin & UI (C53)"
Cohesion: 0.14
Nodes (19): ArrowLeft(), ArrowRight(), editAttachment(), expandSelectionInDirection(), findNodeAndOffsetFromLocation(), getAttachmentAtRange(), getExpandedRangeInDirection(), getSignificantNodesForIndex() (+11 more)

### Community 54 - "Core Module 54"
Cohesion: 0.10
Nodes (13): a(), ar(), at(), cr(), d(), f(), H(), ji() (+5 more)

### Community 55 - "HTTP Controllers & Routing (C55)"
Cohesion: 0.08
Nodes (29): actionIsExternal(), canBeConsolidatedWith(), canInvokeAction(), compositionControllerDidBlur(), compositionControllerDidRender(), compositionControllerDidSyncDocumentView(), compositionDidAddAttachment(), compositionDidChangeAttachmentPreviewURL() (+21 more)

### Community 56 - "Core Module 56"
Cohesion: 0.25
Nodes (25): d(), Di(), f(), Ge(), I(), ir(), ja(), k() (+17 more)

### Community 58 - "Core Module 58"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 59 - "Core Module 59"
Cohesion: 0.14
Nodes (30): Qt(), Cn(), da(), En(), fa(), Fi(), fn(), h() (+22 more)

### Community 60 - "Filament Admin & UI (C60)"
Cohesion: 0.15
Nodes (7): ListTours, Request, TourController, TourController, HasMany, Tour, Attribute

### Community 61 - "Data Models & Domain (C61)"
Cohesion: 0.08
Nodes (10): AccommodationController, DiscountController, PromotionController, Request, ScheduleController, Controller, FerryRoute, BelongsTo (+2 more)

### Community 62 - "Core Module 62"
Cohesion: 0.08
Nodes (30): cacheViewForObject(), copyUsingObjectMap(), copyUsingObjectsFromDocument(), createAttachmentNodes(), createChildView(), createContainerElement(), createDocumentFragmentForSync(), createElement() (+22 more)

### Community 63 - "Data Models & Domain (C63)"
Cohesion: 0.12
Nodes (8): Action, ManageProofs, Collection, Form, PaymentSetting, self, HasActions, InteractsWithActions

### Community 64 - "Data Models & Domain (C64)"
Cohesion: 0.13
Nodes (12): CreateAccommodation, CreateBooking, CreateDiscount, CreateInquiry, CreateSchedule, CreateTour, CreateTransportClass, CreateUser (+4 more)

### Community 65 - "Core Module 65"
Cohesion: 0.08
Nodes (39): addElements(), afterDatasetsUpdate(), beforeUpdate(), buildOrUpdateControllers(), buildOrUpdateElements(), _checkEventBindings(), _dataCheck(), _destroy() (+31 more)

### Community 66 - "HTTP Controllers & Routing (C66)"
Cohesion: 0.07
Nodes (26): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+18 more)

### Community 67 - "Core Module 67"
Cohesion: 0.09
Nodes (21): APP_DEBUG, APP_ENV, APP_NAME, APP_URL, CACHE_STORE, DB_CONNECTION, DB_DATABASE, DB_HOST (+13 more)

### Community 68 - "Database Schema (C68)"
Cohesion: 0.10
Nodes (29): afterAutoSkip(), Bi(), buildLookupTable(), determineDataLimits(), Fi(), getAllParsedValues(), getDataTimestamps(), getDecimalForValue() (+21 more)

### Community 69 - "Core Module 69"
Cohesion: 0.10
Nodes (10): DiscountResource, Form, Table, Form, Table, TourResource, Form, Table (+2 more)

### Community 70 - "Database Seeders & Testing (C70)"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 71 - "Data Models & Domain (C71)"
Cohesion: 0.10
Nodes (20): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+12 more)

### Community 72 - "Core Module 72"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 73 - "HTTP Controllers & Routing (C73)"
Cohesion: 0.13
Nodes (20): addControllers(), addPlugins(), addScales(), al(), cancel(), _createDescriptors(), _descriptors(), _each() (+12 more)

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
Cohesion: 0.13
Nodes (25): appendAttachmentWithAttributes(), appendBlockForAttributesWithElement(), appendBlockForElement(), appendBlockForTextNode(), appendEmptyBlock(), appendPiece(), appendStringWithAttributes(), beforeinput() (+17 more)

### Community 80 - "HTTP Controllers & Routing (C80)"
Cohesion: 0.15
Nodes (11): Cocoa, file_selector_macos, RegisterGeneratedPlugins(), MainFlutterWindow, FlutterMacOS, FlutterPluginRegistry, FlutterViewController, Foundation (+3 more)

### Community 81 - "Core Module 81"
Cohesion: 0.13
Nodes (13): DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow, flutter_controller_ (+5 more)

### Community 82 - "Core Module 82"
Cohesion: 0.11
Nodes (9): DeleteAllUsers, PurgeExpiredProofs, BookingController, Request, Discount, HasMany, BelongsTo, Transaction (+1 more)

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
Cohesion: 0.14
Nodes (7): BookingStatusChart, RecentActivityWidget, RevenueChart, TopRoutesWidget, Builder, Collection, Widget

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
Nodes (10): ViewApkUser, Form, ViewBooking, ViewInquiry, ViewTransaction, Builder, Table, TransactionResource (+2 more)

### Community 95 - "Filament Admin & UI (C95)"
Cohesion: 0.11
Nodes (13): AuthController, Request, HasMany, Panel, User, AppServiceProvider, Authenticatable, FilamentUser (+5 more)

### Community 96 - "Filament Admin & UI (C96)"
Cohesion: 0.07
Nodes (12): FerryRouteResource, CreateFerryRoute, EditFerryRoute, Form, Table, HasMany, Vehicle, UserFactory (+4 more)

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
Nodes (22): canAcceptDataTransfer(), compositionControllerDidFocus(), createDOMRangeFromLocationRange(), createDOMRangeFromPoint(), createLocationRangeFromDOMRange(), didMouseDown(), domRangeWithinElement(), dragover() (+14 more)

### Community 101 - "Core Module 101"
Cohesion: 0.22
Nodes (6): Flutter, RunnerTests, RunnerTests, UIKit, XCTest, XCTestCase

### Community 102 - "Core Module 102"
Cohesion: 0.36
Nodes (10): HWND, LPARAM, LRESULT, UINT, WPARAM, EnableFullDpiSupportIfAvailable(), GetHandle, GetThisFromHandle (+2 more)

### Community 103 - "Filament Admin & UI (C103)"
Cohesion: 0.24
Nodes (3): Form, Table, VehicleBrandResource

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
Cohesion: 0.24
Nodes (10): tl(), ac(), Ai(), ca(), Li(), oc(), ro(), sc() (+2 more)

### Community 114 - "Core Module 114"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 115 - "Core Module 115"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 116 - "AccommodationResource.php"
Cohesion: 0.28
Nodes (3): AccommodationResource, Form, Table

### Community 117 - "InquiryResource.php"
Cohesion: 0.28
Nodes (3): InquiryResource, Form, Table

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

### Community 246 - "UserResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, UserResource

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

### Community 256 - "ExampleTest"
Cohesion: 0.28
Nodes (3): Form, Table, VehicleRateResource

### Community 257 - "console.php"
Cohesion: 0.47
Nodes (4): AdminPanelProvider, Panel, Color, PanelProvider

### Community 263 - "flutter_app"
Cohesion: 0.50
Nodes (3): Amiga Gracia Flutter App, Getting Started, Railway build

### Community 280 - "AdminMiddleware.php"
Cohesion: 0.60
Nodes (3): AdminMiddleware, Request, Closure

## Knowledge Gaps
- **503 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+498 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **25 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `A()` connect `Core Module 20` to `HTTP Controllers & Routing (C2)`, `Core Module 3`, `Core Module 6`, `Core Module 8`, `Core Module 59`, `Core Module 15`, `HTTP Controllers & Routing (C18)`, `Core Module 56`, `Core Module 27`?**
  _High betweenness centrality (0.054) - this node is a cross-community bridge._
- **Why does `draw()` connect `Core Module 8` to `HTTP Controllers & Routing (C0)`, `Core Module 32`, `Core Module 6`, `Data Models & Domain (C7)`, `HTTP Controllers & Routing (C40)`, `Core Module 10`, `Core Module 47`, `Core Module 15`, `Core Module 20`, `Core Module 56`, `Data Models & Domain (C25)`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Why does `F()` connect `Core Module 6` to `HTTP Controllers & Routing (C2)`, `Core Module 4`, `Core Module 8`, `Core Module 45`, `Core Module 14`, `Core Module 15`, `Core Module 23`, `Core Module 56`, `Data Models & Domain (C25)`, `Core Module 27`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Are the 16 inferred relationships involving `x()` (e.g. with `de()` and `g()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **Are the 20 inferred relationships involving `te()` (e.g. with `je()` and `Pr()`) actually correct?**
  _`te()` has 20 INFERRED edges - model-reasoned connections that need verification._
- **Are the 29 inferred relationships involving `V()` (e.g. with `Sg()` and `g()`) actually correct?**
  _`V()` has 29 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _503 weakly-connected nodes found - possible documentation gaps or missing edges._