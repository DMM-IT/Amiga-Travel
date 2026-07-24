# Graph Report - Amiga-Travel  (2026-07-24)

## Corpus Check
- 433 files · ~410,771 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 5516 nodes · 13147 edges · 323 communities (284 shown, 39 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1243 edges (avg confidence: 0.68)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `85e6a592`
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
- Filament Admin & UI (C104)
- Filament Admin & UI (C105)
- Filament Admin & UI (C106)
- Filament Admin & UI (C107)
- Core Module 108
- Core Module 109
- Core Module 110
- Core Module 111
- Core Module 112
- User
- Core Module 114
- Core Module 115
- qt
- _each
- Database Seeders & Testing (C118)
- Core Module 119
- Vehicle
- Core Module 121
- Core Module 122
- Core Module 123
- Core Module 124
- Core Module 125
- Core Module 126
- Core Module 127
- Core Module 128
- Core Module 129
- Core Module 130
- Core Module 131
- Core Module 132
- Core Module 133
- Core Module 134
- Core Module 135
- Core Module 136
- Core Module 137
- Core Module 138
- Core Module 139
- Core Module 205
- Core Module 206
- Core Module 207
- Core Module 208
- Core Module 209
- Core Module 210
- Core Module 211
- Core Module 212
- Core Module 213
- Core Module 214
- Core Module 215
- Core Module 216
- Core Module 217
- Core Module 222
- Core Module 223
- ScheduleResource.php
- Core Module 225
- Core Module 227
- Core Module 228
- Core Module 231
- Core Module 241
- Core Module 242
- ManageProofs
- graphify reference: add a URL and watch a folder
- graphify reference: add a URL and watch a folder
- FerryRoute.php
- Filament\Resources\Pages\ViewRecord
- getDataset
- VoucherResource
- .nextStep
- .updateReturnDateFromDuration
- UserResource
- VehicleRateResource
- qt
- PurgeExpiredProofs.php
- BookingExportController
- yn
- AdminPanelProvider.php
- UserFactory
- Collection
- Illuminate\Database\Eloquent\Relations\HasOne
- Illuminate\Support\Facades\Validator

## God Nodes (most connected - your core abstractions)
1. `components/chart.js` - 948 edges
2. `stat/chart.js` - 612 edges
3. `_update()` - 88 edges
4. `x()` - 85 edges
5. `BookingForm` - 84 edges
6. `Booking` - 84 edges
7. `_update()` - 84 edges
8. `te()` - 74 edges
9. `V()` - 72 edges
10. `draw()` - 55 edges

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

## Communities (323 total, 39 thin omitted)

### Community 0 - "HTTP Controllers & Routing (C0)"
Cohesion: 0.01
Nodes (316): bool get, Color, dart:async, dart:convert, DateTime, double?, _accommodations, _activePassengerIndex (+308 more)

### Community 1 - "Data Models & Domain (C1)"
Cohesion: 0.01
Nodes (120): components/chart.js, acquireContext(), addEventListener(), alpha(), beforeDatasetDraw(), beforeDatasetsDraw(), bh(), bindResponsiveEvents() (+112 more)

### Community 2 - "HTTP Controllers & Routing (C2)"
Cohesion: 0.02
Nodes (124): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), canRedo(), canSyncDocumentView() (+116 more)

### Community 3 - "Core Module 3"
Cohesion: 0.03
Nodes (10): AccommodationResource, AppNotificationResource, BookingResource, DiscountResource, GraciaEarningRuleResource, InquiryResource, TourResource, TransportClassResource (+2 more)

### Community 4 - "Core Module 4"
Cohesion: 0.04
Nodes (152): _a(), Ae(), af(), ai(), al(), An(), ao(), ar() (+144 more)

### Community 5 - "Core Module 5"
Cohesion: 0.02
Nodes (104): stat/chart.js, aa(), active(), addControllers(), addPlugins(), addScales(), al(), an() (+96 more)

### Community 6 - "Core Module 6"
Cohesion: 0.02
Nodes (144): _a(), aa(), abutsStart(), after(), afterAutoSkip(), Ag(), Ai(), Al() (+136 more)

### Community 7 - "Data Models & Domain (C7)"
Cohesion: 0.07
Nodes (24): AdminNotifications, ApkUserResource/RelationManagers/BookingsRelationManager.php, BookingsRelationManager, GraciaPointLedgersRelationManager, AccommodationsRelationManager, PassengersRelationManager, BookingResource/RelationManagers/TransportClassesRelationManager.php, TransportClassesRelationManager (+16 more)

### Community 8 - "Core Module 8"
Cohesion: 0.04
Nodes (115): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), canBeGroupedWith(), canDecreaseBlockAttributeLevel() (+107 more)

### Community 9 - "Database Schema (C9)"
Cohesion: 0.06
Nodes (7): PromotionalTicket, Schedule, TransportClass, TransportClassSeeder, HasOneThrough, Illuminate\Database\Eloquent\Relations\BelongsToMany, ScheduleSeatingProfileTest

### Community 10 - "Core Module 10"
Cohesion: 0.07
Nodes (66): [g](), [x](), $c(), D(), E(), Ea(), g(), H() (+58 more)

### Community 11 - "Database Schema (C11)"
Cohesion: 0.03
Nodes (97): attachFiles(), backspace(), beforeinput(), canApplyToDocument(), compositionend(), compositionShouldAcceptFile(), compositionstart(), compositionupdate() (+89 more)

### Community 12 - "Data Models & Domain (C12)"
Cohesion: 0.06
Nodes (66): adjustHitBoxes(), ae(), afterDraw(), aspectRatio(), _computeLabelArea(), _computeTitleHeight(), cs(), De() (+58 more)

### Community 13 - "Core Module 13"
Cohesion: 0.06
Nodes (10): Booking, Inquiry, Transaction, ReportingService, BelongsTo, BelongsToMany, HasMany, Illuminate\Support\Collection (+2 more)

### Community 14 - "Core Module 14"
Cohesion: 0.04
Nodes (96): ad(), af(), Ah(), applyStack(), bf(), buildTicks(), _calculateBarIndexPixels(), _calculateBarValuePixels() (+88 more)

### Community 15 - "Core Module 15"
Cohesion: 0.08
Nodes (11): AdminNotificationController, ScheduleController, AuthController, AdminMiddleware, EnsureStaffPermission, FerryRoute, Closure, Illuminate\Http\RedirectResponse (+3 more)

### Community 16 - "Data Models & Domain (C16)"
Cohesion: 0.05
Nodes (45): ba(), bi(), c(), ca(), clickPercent(), constructor(), e(), getExtension() (+37 more)

### Community 17 - "Core Module 17"
Cohesion: 0.10
Nodes (83): define(), Sg(), ad(), at(), B(), br(), Bt(), ca() (+75 more)

### Community 18 - "HTTP Controllers & Routing (C18)"
Cohesion: 0.07
Nodes (4): BookingForm, Collection, Component, WithFileUploads

### Community 19 - "HTTP Controllers & Routing (C19)"
Cohesion: 0.03
Nodes (83): ar(), Bl(), cf(), clone(), constructor(), create(), Dl(), dtFormatter() (+75 more)

### Community 20 - "Core Module 20"
Cohesion: 0.04
Nodes (88): addBox(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+80 more)

### Community 21 - "Data Models & Domain (C21)"
Cohesion: 0.04
Nodes (16): Bi(), bn(), Id(), ji(), kd(), ki(), kl(), on() (+8 more)

### Community 22 - "Core Module 22"
Cohesion: 0.06
Nodes (59): attachmentManagerDidRequestRemovalOfAttachment(), breakFormattedBlock(), breaksOnReturn(), Ca(), canSetCurrentAttribute(), canSetCurrentBlockAttribute(), compositionControllerDidRequestRemovalOfAttachment(), copyWithoutText() (+51 more)

### Community 23 - "Core Module 23"
Cohesion: 0.06
Nodes (40): ai(), apply(), B(), co(), Cr(), $e(), es(), Et() (+32 more)

### Community 24 - "Filament Admin & UI (C24)"
Cohesion: 0.04
Nodes (63): Ac(), an(), Au(), average(), ba(), beforeDraw(), bu(), Ca() (+55 more)

### Community 25 - "Data Models & Domain (C25)"
Cohesion: 0.17
Nodes (16): e(), i(), l(), Ni(), o(), t(), u(), be() (+8 more)

### Community 26 - "Data Models & Domain (C26)"
Cohesion: 0.09
Nodes (30): average(), fn(), getBasePosition(), getBaseValue(), getCenterPoint(), getProps(), hasValue(), hn() (+22 more)

### Community 27 - "Core Module 27"
Cohesion: 0.09
Nodes (38): add(), C(), Co(), _computeLabelSizes(), cr(), diff(), endOf(), Et() (+30 more)

### Community 28 - "Core Module 28"
Cohesion: 0.06
Nodes (17): ListAccommodations, ListApkUsers, ListAppNotifications, ListBookings, ListDiscounts, ListFerryRoutes, ListGraciaEarningRules, ListInquiries (+9 more)

### Community 29 - "Database Schema (C29)"
Cohesion: 0.06
Nodes (24): actions(), button(), constructor(), danger(), dispatch(), dispatchSelf(), dispatchTo(), duration() (+16 more)

### Community 30 - "Core Module 30"
Cohesion: 0.07
Nodes (18): Discount, GraciaPointLedger, Passenger, ScheduleAccommodation, TourDate, UserLoginHistory, VehicleBrand, VehicleModel (+10 more)

### Community 31 - "Frontend & Components (C31)"
Cohesion: 0.07
Nodes (44): ActivityScreen, _ActivityScreenState, BookingDetailsScreen, _BookingDetailsScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState (+36 more)

### Community 32 - "Core Module 32"
Cohesion: 0.10
Nodes (29): afterAutoSkip(), Ao(), Bi(), buildLookupTable(), determineDataLimits(), Fi(), getAllParsedValues(), getDataTimestamps() (+21 more)

### Community 33 - "Core Module 33"
Cohesion: 0.08
Nodes (43): adjustHitBoxes(), afterDraw(), bc(), Bl(), clear(), _computeLabelArea(), _computeTitleHeight(), _createItems() (+35 more)

### Community 34 - "Data Models & Domain (C34)"
Cohesion: 0.10
Nodes (4): OverallReports, Collection, StaffPerformance, DatePicker

### Community 35 - "Data Models & Domain (C35)"
Cohesion: 0.13
Nodes (10): DatabaseSeeder, DiscountSeeder, FerryRouteSeeder, ScheduleAccommodationSeeder, TourHotelsSeeder, VehicleRateSeeder, VehicleSeeder, WebsiteSettingSeeder (+2 more)

### Community 36 - "Core Module 36"
Cohesion: 0.09
Nodes (34): At(), Bs(), cc(), cd(), clear(), cn(), Da(), Fc() (+26 more)

### Community 37 - "Filament Admin & UI (C37)"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), _d(), generateLabels(), getDatasetMeta(), getDataVisibility(), getMaxBorderWidth(), getStyle(), _handleEvent() (+18 more)

### Community 38 - "Core Module 38"
Cohesion: 0.07
Nodes (14): EditAccommodation, EditAppNotification, EditBooking, EditDiscount, EditGraciaEarningRule, EditPromotionalTicket, EditSchedule, EditTour (+6 more)

### Community 39 - "Data Models & Domain (C39)"
Cohesion: 0.09
Nodes (28): cacheViewForObject(), compositionDidLoadSnapshot(), createAttachmentNodes(), createChildView(), createContainerElement(), createDocumentFragmentForSync(), createElement(), createNodes() (+20 more)

### Community 40 - "HTTP Controllers & Routing (C40)"
Cohesion: 0.08
Nodes (45): add(), applyKeyboardCommand(), attachmentDidChangeAttributes(), attachmentEditorDidRequestRemovalOfAttachment(), canBeGrouped(), checkValidity(), createCaptionElement(), createContentNodes() (+37 more)

### Community 41 - "Data Models & Domain (C41)"
Cohesion: 0.08
Nodes (37): canAcceptDataTransfer(), canDecreaseNestingLevel(), canIncreaseNestingLevel(), compositionControllerDidFocus(), compositionDidRequestChangingSelectionToLocationRange(), createDOMRangeFromPoint(), createLocationRangeFromDOMRange(), decreaseNestingLevel() (+29 more)

### Community 42 - "Filament Admin & UI (C42)"
Cohesion: 0.07
Nodes (44): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate(), beforeBuildTicks() (+36 more)

### Community 43 - "Core Module 43"
Cohesion: 0.06
Nodes (64): applyStack(), ar(), as(), buildTicks(), _calculateBarIndexPixels(), _calculateBarValuePixels(), calculateCircumference(), _calculatePadding() (+56 more)

### Community 44 - "Core Module 44"
Cohesion: 0.07
Nodes (13): ListTours, AccommodationController, DiscountController, PromotionController, Api/TourController.php, TourController, Controller, Controllers/TourController.php (+5 more)

### Community 45 - "Core Module 45"
Cohesion: 0.12
Nodes (13): CreatesApplication, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, BookingLookupCancellationTest, BookingRebookingFlowTest, Feature/ExampleTest.php, ExampleTest, GraciaPointsServiceTest (+5 more)

### Community 46 - "HTTP Controllers & Routing (C46)"
Cohesion: 0.10
Nodes (28): afterDatasetsUpdate(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas(), getStyle() (+20 more)

### Community 47 - "Core Module 47"
Cohesion: 0.08
Nodes (30): box(), canBeConsolidatedWith(), compositionControllerDidRender(), constructor(), disabled(), formDisabledCallback(), fromUCS2String(), get() (+22 more)

### Community 48 - "Data Models & Domain (C48)"
Cohesion: 0.23
Nodes (18): Ae(), at(), de(), dt(), fr(), Gt(), ht(), It() (+10 more)

### Community 49 - "Data Models & Domain (C49)"
Cohesion: 0.06
Nodes (52): Bt(), xo(), addEventListener(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), buildOrUpdateScales(), _checkEventBindings() (+44 more)

### Community 50 - "Data Models & Domain (C50)"
Cohesion: 0.07
Nodes (5): ManageWebsiteSettings, WebsiteSetting, AppServiceProvider, Illuminate\Support\Facades\View, Illuminate\Support\ServiceProvider

### Community 51 - "Core Module 51"
Cohesion: 0.06
Nodes (11): EditUser, AdminNotificationStatus, HasOne, User, Voucher, AdminNotificationFeed, Filament\Models\Contracts\FilamentUser, Illuminate\Database\Eloquent\Relations\HasMany (+3 more)

### Community 52 - "Data Models & Domain (C52)"
Cohesion: 0.10
Nodes (26): alpha(), be(), en(), fe(), greyscale(), Hi(), ic(), interpolate() (+18 more)

### Community 53 - "Filament Admin & UI (C53)"
Cohesion: 0.07
Nodes (9): Action, ManagePaymentSettings, ManageProofs, TransactionResource, PaymentSetting, self, Filament\Actions\Action, Filament\Actions\Concerns\InteractsWithActions (+1 more)

### Community 54 - "Core Module 54"
Cohesion: 0.10
Nodes (13): a(), ar(), at(), cr(), d(), f(), H(), ji() (+5 more)

### Community 55 - "HTTP Controllers & Routing (C55)"
Cohesion: 0.07
Nodes (43): Yn(), _a(), acquireContext(), ba(), _cachedScopes(), configure(), constructor(), createResolver() (+35 more)

### Community 56 - "Core Module 56"
Cohesion: 0.07
Nodes (9): FerryRouteResource, CreateFerryRoute, EditFerryRoute, ListVehicles, Vehicle, Form, Illuminate\Database\Eloquent\Builder, Resource (+1 more)

### Community 57 - "Data Models & Domain (C57)"
Cohesion: 0.08
Nodes (16): CreateAccommodation, CreateAppNotification, CreateBooking, CreateDiscount, CreateGraciaEarningRule, CreateInquiry, CreatePromotionalTicket, CreateSchedule (+8 more)

### Community 58 - "Core Module 58"
Cohesion: 0.16
Nodes (33): _a(), aa(), ba(), Be(), Bi(), br(), Ca(), ce() (+25 more)

### Community 59 - "Core Module 59"
Cohesion: 0.09
Nodes (22): Any, Cocoa, Flutter, ios/Runner/AppDelegate.swift, AppDelegate, Bool, ios/RunnerTests/RunnerTests.swift, RunnerTests (+14 more)

### Community 60 - "Filament Admin & UI (C60)"
Cohesion: 0.15
Nodes (7): GraciaPointsController, AppNotification, GraciaEarningRule, GraciaUserBalance, GraciaPointsService, GraciaEarningRuleSeeder, Illuminate\Database\Eloquent\Factories\HasFactory

### Community 61 - "Data Models & Domain (C61)"
Cohesion: 0.07
Nodes (27): .claude/skills/graphify/SKILL.md, For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands (+19 more)

### Community 62 - "Core Module 62"
Cohesion: 0.08
Nodes (30): attachmentForFile(), attributesForFile(), canSetCurrentTextAttribute(), didChangeAttributes(), didClickAttachment(), dragstart(), findAttachmentForElement(), getAttachmentAndPositionById() (+22 more)

### Community 63 - "Data Models & Domain (C63)"
Cohesion: 0.09
Nodes (29): addControllers(), addElements(), addPlugins(), addScales(), buildOrUpdateControllers(), buildOrUpdateElements(), _dataCheck(), _destroy() (+21 more)

### Community 64 - "Data Models & Domain (C64)"
Cohesion: 0.12
Nodes (24): ac(), Ai(), ca(), calculateLabelRotation(), drawGrid(), ec(), Fc(), G() (+16 more)

### Community 65 - "Core Module 65"
Cohesion: 0.25
Nodes (25): d(), Di(), f(), Ge(), I(), ir(), ja(), k() (+17 more)

### Community 66 - "HTTP Controllers & Routing (C66)"
Cohesion: 0.07
Nodes (39): active(), add(), _animateOptions(), Bi(), _cachedScopes(), cancel(), chartOptionScopes(), _createAnimations() (+31 more)

### Community 67 - "Core Module 67"
Cohesion: 0.08
Nodes (25): .copilot/skills/graphify/SKILL.md, For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands (+17 more)

### Community 68 - "Database Schema (C68)"
Cohesion: 0.10
Nodes (22): FlPluginRegistry, linux/flutter/generated_plugin_registrant.cc, fl_register_plugins(), linux/flutter/generated_plugin_registrant.h, main(), my_application_activate(), my_application_class_init(), my_application_dispose() (+14 more)

### Community 69 - "Core Module 69"
Cohesion: 0.08
Nodes (25): .github/skills/graphify/SKILL.md, For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands (+17 more)

### Community 70 - "Database Seeders & Testing (C70)"
Cohesion: 0.14
Nodes (30): Qt(), Cn(), da(), En(), fa(), Fi(), fn(), h() (+22 more)

### Community 71 - "Data Models & Domain (C71)"
Cohesion: 0.08
Nodes (24): APP_DEBUG, APP_ENV, APP_NAME, APP_URL, CACHE_STORE, DB_CONNECTION, DB_DATABASE, DB_HOST (+16 more)

### Community 72 - "Core Module 72"
Cohesion: 0.08
Nodes (44): Aa(), Ac(), bl(), Cc(), ce(), cf(), cl(), Dc() (+36 more)

### Community 73 - "HTTP Controllers & Routing (C73)"
Cohesion: 0.08
Nodes (5): ApkUserResource, VehicleResource, DashboardStatsOverview, SystemStatsOverview, Filament\Widgets\StatsOverviewWidget

### Community 74 - "Data Models & Domain (C74)"
Cohesion: 0.10
Nodes (20): apexcharts, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, concurrently, laravel-vite-plugin (+12 more)

### Community 75 - "Core Module 75"
Cohesion: 0.10
Nodes (21): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+13 more)

### Community 76 - "Core Module 76"
Cohesion: 0.11
Nodes (21): actionIsExternal(), canInvokeAction(), compositionControllerDidBlur(), compositionControllerDidSyncDocumentView(), compositionDidAddAttachment(), compositionDidChangeAttachmentPreviewURL(), compositionDidChangeCurrentAttributes(), compositionDidChangeDocument() (+13 more)

### Community 77 - "Core Module 77"
Cohesion: 0.14
Nodes (10): BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested, RebookingVerification, Illuminate\Bus\Queueable (+2 more)

### Community 78 - "Core Module 78"
Cohesion: 0.13
Nodes (24): C(), Ce(), co(), formats(), Ft(), ga(), getLabelAndValue(), getLabelForValue() (+16 more)

### Community 79 - "Core Module 79"
Cohesion: 0.10
Nodes (20): 1. Clone the repository, 1. Navigate to the Flutter folder, 2. Install Flutter Dependencies, 2. Install PHP Dependencies, 3. Install Node Dependencies, 3. Update the API Endpoint, 4. Environment Configuration, 4. Run the App (+12 more)

### Community 80 - "HTTP Controllers & Routing (C80)"
Cohesion: 0.15
Nodes (16): wchar_t, Scale(), Create, Destroy, SetQuitOnClose, Show, UpdateTheme, Win32Window::Win32Window() (+8 more)

### Community 81 - "Core Module 81"
Cohesion: 0.18
Nodes (20): It(), appendAttachmentWithAttributes(), appendBlockForAttributesWithElement(), appendBlockForElement(), appendBlockForTextNode(), appendEmptyBlock(), appendPiece(), appendStringWithAttributes() (+12 more)

### Community 82 - "Core Module 82"
Cohesion: 0.11
Nodes (17): AndroidFlutterLocalNotificationsPlugin, @pragma, dart:io, _downloadAndSaveFile, firebaseMessagingBackgroundHandler, initialize, initializeApp, _localNotifications (+9 more)

### Community 83 - "Core Module 83"
Cohesion: 0.12
Nodes (15): DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow, flutter_controller_ (+7 more)

### Community 84 - "Core Module 84"
Cohesion: 0.16
Nodes (5): BookingStatusChart, RecentActivityWidget, RevenueChartWidget, TopRoutesWidget, Filament\Widgets\Widget

### Community 85 - "Core Module 85"
Cohesion: 0.12
Nodes (5): BookingLookup, PaymentProof, UserDashboard, Livewire\Component, Livewire\WithFileUploads

### Community 86 - "Data Models & Domain (C86)"
Cohesion: 0.10
Nodes (23): At(), beforeDraw(), dataset(), ea(), Fa(), ge(), getMaximumSize(), getSortedVisibleDatasetMetas() (+15 more)

### Community 87 - "Core Module 87"
Cohesion: 0.12
Nodes (16): require, anhskohbo/no-captcha, dompdf/dompdf, filament/filament, filament/support, intervention/image, kreait/laravel-firebase, laravel/framework (+8 more)

### Community 88 - "Core Module 88"
Cohesion: 0.13
Nodes (15): scripts, dev, post-autoload-dump, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+7 more)

### Community 89 - "Core Module 89"
Cohesion: 0.14
Nodes (13): addFaq, addQuickFact, addSocialLink, closePanel, removeFaq({{ $fi }}), removeHeroImage({{ (int)$idx }}), removeQuickFact({{ $fi }}), removeSocialLink({{ $li }}) (+5 more)

### Community 90 - "Filament Admin & UI (C90)"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 91 - "Core Module 91"
Cohesion: 0.19
Nodes (14): windows/flutter/generated_plugin_registrant.cc, windows/flutter/generated_plugin_registrant.h, RegisterPlugins(), OnCreate, HWND, Win32Window, child_content_, GetClientArea (+6 more)

### Community 93 - "Core Module 93"
Cohesion: 0.15
Nodes (11): file_selector_macos, firebase_core, firebase_messaging, RegisterGeneratedPlugins(), flutter_local_notifications, FlutterPluginRegistry, FlutterViewController, Foundation (+3 more)

### Community 94 - "Data Models & Domain (C94)"
Cohesion: 0.15
Nodes (14): Ce(), De(), di(), e(), Ht(), Ie(), Me(), Re() (+6 more)

### Community 95 - "Filament Admin & UI (C95)"
Cohesion: 0.15
Nodes (13): public/manifest.json, background_color, categories, description, display, icons, name, orientation (+5 more)

### Community 96 - "Filament Admin & UI (C96)"
Cohesion: 0.24
Nodes (9): wWinMain(), string, wchar_t, CreateAndAttachConsole(), GetCommandLineArguments(), Utf8FromUtf16(), _In_, _In_opt_ (+1 more)

### Community 97 - "Core Module 97"
Cohesion: 0.26
Nodes (8): filament/app.js, C(), D(), J(), O(), U(), v(), X()

### Community 98 - "Core Module 98"
Cohesion: 0.18
Nodes (12): Be(), ei(), ii(), le(), ni(), oi(), r(), ri() (+4 more)

### Community 99 - "Core Module 99"
Cohesion: 0.09
Nodes (3): PromotionalTicketResource, ScheduleResource, $set(

### Community 100 - "Filament Admin & UI (C100)"
Cohesion: 0.18
Nodes (11): web/manifest.json, background_color, description, display, icons, name, orientation, prefer_related_applications (+3 more)

### Community 101 - "Core Module 101"
Cohesion: 0.36
Nodes (10): HWND, LPARAM, LRESULT, UINT, WPARAM, EnableFullDpiSupportIfAvailable(), GetHandle, GetThisFromHandle (+2 more)

### Community 102 - "Core Module 102"
Cohesion: 0.20
Nodes (9): Flutter & Android Studio Setup Guide, Option A: VS Code (Recommended), Option B: Android Studio, 📋 Prerequisites, 🚀 Step 1: Install the Flutter SDK, 📱 Step 2: Install and Configure Android Studio, 🛠️ Step 3: Run Flutter Doctor, 💻 Step 4: Configure Your IDE (+1 more)

### Community 103 - "Resource"
Cohesion: 0.22
Nodes (9): .claude/skills/graphify/references/exports.md, graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag) (+1 more)

### Community 104 - "Filament Admin & UI (C104)"
Cohesion: 0.22
Nodes (9): .copilot/skills/graphify/references/exports.md, graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag) (+1 more)

### Community 105 - "Filament Admin & UI (C105)"
Cohesion: 0.22
Nodes (9): .github/skills/graphify/references/exports.md, graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag) (+1 more)

### Community 106 - "Filament Admin & UI (C106)"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 107 - "Filament Admin & UI (C107)"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 108 - "Core Module 108"
Cohesion: 0.33
Nodes (6): build, _checkVersionAndProceed, _goNext, _goToSchedule, _selectTransportOption, MaterialPageRoute

### Community 109 - "Core Module 109"
Cohesion: 0.20
Nodes (11): b(), Dt(), Fe(), g(), He(), i(), ir(), Mt() (+3 more)

### Community 110 - "Core Module 110"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 111 - "Core Module 111"
Cohesion: 0.33
Nodes (5): How to Update the Android App (APK), Step 1: Bump the Version Number, Step 2: Build the New APK, Step 3: Copy the New APK to the Web Server, What happens automatically next?

### Community 112 - "Core Module 112"
Cohesion: 0.33
Nodes (6): .claude/skills/graphify/references/query.md, For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 113 - "User"
Cohesion: 0.33
Nodes (6): .copilot/skills/graphify/references/query.md, For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 114 - "Core Module 114"
Cohesion: 0.33
Nodes (6): .github/skills/graphify/references/query.md, For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 117 - "_each"
Cohesion: 0.11
Nodes (23): ArrowLeft(), ArrowRight(), createDOMRangeFromLocationRange(), editAttachment(), expandSelectionInDirection(), findContainerAndOffsetFromLocation(), findNodeAndOffsetFromLocation(), getAttachmentAtRange() (+15 more)

### Community 118 - "Database Seeders & Testing (C118)"
Cohesion: 0.17
Nodes (6): BookingController, Request, Request, VoucherController, Controller, HasOne

### Community 119 - "Core Module 119"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 120 - "Vehicle"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 122 - "Core Module 122"
Cohesion: 0.50
Nodes (4): .claude/skills/graphify/references/add-watch.md, For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 123 - "Core Module 123"
Cohesion: 0.50
Nodes (4): .claude/skills/graphify/references/hooks.md, For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 124 - "Core Module 124"
Cohesion: 0.50
Nodes (4): .claude/skills/graphify/references/update.md, For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 125 - "Core Module 125"
Cohesion: 0.50
Nodes (4): post-create-project-cmd, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\

### Community 126 - "Core Module 126"
Cohesion: 0.50
Nodes (4): .copilot/skills/graphify/references/add-watch.md, For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 127 - "Core Module 127"
Cohesion: 0.50
Nodes (4): .copilot/skills/graphify/references/hooks.md, For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 128 - "Core Module 128"
Cohesion: 0.50
Nodes (4): .copilot/skills/graphify/references/update.md, For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 129 - "Core Module 129"
Cohesion: 0.50
Nodes (4): flutter_app/README.md, Amiga Gracia Flutter App, Getting Started, Railway build

### Community 130 - "Core Module 130"
Cohesion: 0.50
Nodes (4): .github/skills/graphify/references/add-watch.md, For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 131 - "Core Module 131"
Cohesion: 0.50
Nodes (4): .github/skills/graphify/references/hooks.md, For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 132 - "Core Module 132"
Cohesion: 0.50
Nodes (4): .github/skills/graphify/references/update.md, For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 133 - "Core Module 133"
Cohesion: 0.67
Nodes (3): PHPUnit\Framework\TestCase, Unit/ExampleTest.php, ExampleTest

### Community 135 - "Core Module 135"
Cohesion: 0.67
Nodes (3): .claude/skills/graphify/references/github-and-merge.md, graphify reference: GitHub clone and cross-repo merge, Step 0 - Clone GitHub repo(s) (only if a GitHub URL was given)

### Community 136 - "Core Module 136"
Cohesion: 0.67
Nodes (3): .claude/skills/graphify/references/transcribe.md, graphify reference: transcribe video and audio, Step 2.5 - Transcribe video / audio files (only if video files detected)

### Community 137 - "Core Module 137"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 138 - "Core Module 138"
Cohesion: 0.67
Nodes (3): .copilot/skills/graphify/references/github-and-merge.md, graphify reference: GitHub clone and cross-repo merge, Step 0 - Clone GitHub repo(s) (only if a GitHub URL was given)

### Community 139 - "Core Module 139"
Cohesion: 0.67
Nodes (3): .copilot/skills/graphify/references/transcribe.md, graphify reference: transcribe video and audio, Step 2.5 - Transcribe video / audio files (only if video files detected)

### Community 207 - "Core Module 207"
Cohesion: 0.67
Nodes (3): .github/skills/graphify/references/github-and-merge.md, graphify reference: GitHub clone and cross-repo merge, Step 0 - Clone GitHub repo(s) (only if a GitHub URL was given)

### Community 208 - "Core Module 208"
Cohesion: 0.67
Nodes (3): .github/skills/graphify/references/transcribe.md, graphify reference: transcribe video and audio, Step 2.5 - Transcribe video / audio files (only if video files detected)

### Community 300 - "Filament\Resources\Pages\ViewRecord"
Cohesion: 0.15
Nodes (7): ViewApkUser, ViewBooking, ViewInquiry, ViewPromotionalTicket, ViewTransaction, ViewVoucher, Filament\Resources\Pages\ViewRecord

### Community 301 - "getDataset"
Cohesion: 0.18
Nodes (16): addElements(), buildOrUpdateControllers(), buildOrUpdateElements(), _dataCheck(), _destroy(), _destroyDatasetMeta(), Ei(), getController() (+8 more)

### Community 307 - "qt"
Cohesion: 0.36
Nodes (8): hs(), Ln(), Nn(), ps(), qt(), Ro(), Se(), wo()

### Community 308 - "PurgeExpiredProofs.php"
Cohesion: 0.38
Nodes (3): DeleteAllUsers, PurgeExpiredProofs, Illuminate\Console\Command

### Community 311 - "yn"
Cohesion: 0.33
Nodes (7): ar(), ft(), kn(), sr(), wn(), Ye(), yn()

### Community 312 - "AdminPanelProvider.php"
Cohesion: 0.47
Nodes (4): AdminPanelProvider, Filament\Panel, Filament\PanelProvider, Filament\Support\Colors\Color

## Knowledge Gaps
- **633 isolated node(s):** `UserSession`, `BookingData`, `UpdateChecker`, `prefs`, `isFirstLaunch` (+628 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **39 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `components/chart.js` connect `Data Models & Domain (C1)` to `HTTP Controllers & Routing (C66)`, `Core Module 36`, `Filament Admin & UI (C37)`, `Core Module 6`, `Core Module 10`, `Data Models & Domain (C12)`, `Core Module 14`, `Core Module 78`, `Data Models & Domain (C49)`, `Core Module 17`, `HTTP Controllers & Routing (C19)`, `Core Module 20`, `Filament Admin & UI (C24)`, `Data Models & Domain (C63)`?**
  _High betweenness centrality (0.093) - this node is a cross-community bridge._
- **Why does `stat/chart.js` connect `Core Module 5` to `Core Module 4`, `Core Module 6`, `Core Module 10`, `Data Models & Domain (C12)`, `Core Module 17`, `Data Models & Domain (C26)`, `Core Module 27`, `Core Module 32`, `Core Module 33`, `Filament Admin & UI (C42)`, `Core Module 43`, `getDataset`, `HTTP Controllers & Routing (C46)`, `Core Module 47`, `Data Models & Domain (C49)`, `Data Models & Domain (C52)`, `HTTP Controllers & Routing (C55)`, `Data Models & Domain (C64)`, `HTTP Controllers & Routing (C66)`, `Data Models & Domain (C86)`?**
  _High betweenness centrality (0.078) - this node is a cross-community bridge._
- **Why does `A()` connect `Core Module 72` to `Core Module 65`, `HTTP Controllers & Routing (C2)`, `Core Module 33`, `Core Module 4`, `Database Seeders & Testing (C70)`, `HTTP Controllers & Routing (C40)`, `Core Module 10`, `Data Models & Domain (C12)`, `Core Module 47`, `Core Module 17`, `Core Module 22`, `Data Models & Domain (C25)`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Are the 4 inferred relationships involving `components/chart.js` (e.g. with `dg()` and `Ms()`) actually correct?**
  _`components/chart.js` has 4 INFERRED edges - model-reasoned connections that need verification._
- **Are the 16 inferred relationships involving `x()` (e.g. with `g()` and `_i()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **What connects `UserSession`, `BookingData`, `UpdateChecker` to the rest of the system?**
  _633 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `HTTP Controllers & Routing (C0)` be split into smaller, more focused modules?**
  _Cohesion score 0.006309148264984227 - nodes in this community are weakly interconnected._