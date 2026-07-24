# Graph Report - amiga-gracia  (2026-07-24)

## Corpus Check
- 427 files · ~267,789 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 5475 nodes · 13152 edges · 299 communities (267 shown, 32 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1272 edges (avg confidence: 0.68)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6347cd97`
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
- ManageProofs
- graphify reference: add a URL and watch a folder

## God Nodes (most connected - your core abstractions)
1. `_update()` - 88 edges
2. `x()` - 85 edges
3. `_update()` - 84 edges
4. `BookingForm` - 80 edges
5. `Booking` - 80 edges
6. `te()` - 74 edges
7. `V()` - 72 edges
8. `Schedule` - 58 edges
9. `draw()` - 55 edges
10. `vd()` - 53 edges

## Surprising Connections (you probably didn't know these)
- `getExtension()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/file-upload.js → public/js/filament/forms/components/markdown-editor.js
- `_getTestState()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/file-upload.js → public/js/filament/forms/components/markdown-editor.js
- `dt()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/rich-editor.js → public/js/filament/forms/components/markdown-editor.js
- `wWinMain()` --calls--> `CreateAndAttachConsole()`  [INFERRED]
  flutter_app/windows/runner/main.cpp → flutter_app/windows/runner/utils.cpp
- `Win32Window::Win32Window()` --calls--> `Destroy`  [INFERRED]
  flutter_app/windows/runner/win32_window.cpp → flutter_app/windows/runner/win32_window.h

## Import Cycles
- None detected.

## Communities (299 total, 32 thin omitted)

### Community 0 - "HTTP Controllers & Routing (C0)"
Cohesion: 0.01
Nodes (308): bool get, Color, dart:async, dart:convert, DateTime, double?, _accommodations, _activePassengerIndex (+300 more)

### Community 1 - "Data Models & Domain (C1)"
Cohesion: 0.01
Nodes (105): acquireContext(), addControllers(), addPlugins(), addScales(), alpha(), beforeDatasetDraw(), beforeDatasetsDraw(), bh() (+97 more)

### Community 2 - "HTTP Controllers & Routing (C2)"
Cohesion: 0.02
Nodes (128): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeAttributes(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), canRedo() (+120 more)

### Community 3 - "Core Module 3"
Cohesion: 0.01
Nodes (23): AccommodationResource, ApkUserResource, AppNotificationResource, BookingResource, DiscountResource, GraciaEarningRuleResource, InquiryResource, PromotionalTicketResource (+15 more)

### Community 4 - "Core Module 4"
Cohesion: 0.04
Nodes (156): _a(), Ac(), ad(), Ae(), af(), ai(), al(), An() (+148 more)

### Community 5 - "Core Module 5"
Cohesion: 0.02
Nodes (107): Bt(), xo(), aa(), addEventListener(), an(), aspectRatio(), beforeDatasetDraw(), beforeDatasetsDraw() (+99 more)

### Community 6 - "Core Module 6"
Cohesion: 0.03
Nodes (121): _a(), abutsStart(), after(), afterAutoSkip(), Ah(), Ai(), Al(), ar() (+113 more)

### Community 7 - "Data Models & Domain (C7)"
Cohesion: 0.05
Nodes (23): AdminNotifications, BookingsRelationManager, GraciaPointLedgersRelationManager, AccommodationsRelationManager, PassengersRelationManager, TransportClassesRelationManager, ScheduleAccommodationsRelationManager, TransportClassesRelationManager (+15 more)

### Community 8 - "Core Module 8"
Cohesion: 0.04
Nodes (108): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), breakFormattedBlock(), charAt() (+100 more)

### Community 9 - "Database Schema (C9)"
Cohesion: 0.03
Nodes (13): TourResource, ListVehicles, ScheduleController, FerryRoute, PromotionalTicket, Schedule, TransportClass, FerryRouteSeeder (+5 more)

### Community 10 - "Core Module 10"
Cohesion: 0.08
Nodes (62): [x](), $c(), ca(), D(), E(), Ea(), g(), H() (+54 more)

### Community 11 - "Database Schema (C11)"
Cohesion: 0.03
Nodes (101): ArrowLeft(), ArrowRight(), attachFiles(), backspace(), beforeinput(), canApplyToDocument(), compositionend(), compositionstart() (+93 more)

### Community 12 - "Data Models & Domain (C12)"
Cohesion: 0.04
Nodes (101): ad(), adjustHitBoxes(), ae(), af(), afterDraw(), bn(), calculateLabelRotation(), _calculatePadding() (+93 more)

### Community 13 - "Core Module 13"
Cohesion: 0.04
Nodes (22): BookingController, PaymentProof, UserDashboard, BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived (+14 more)

### Community 14 - "Core Module 14"
Cohesion: 0.04
Nodes (95): addElements(), applyStack(), aspectRatio(), buildOrUpdateElements(), C(), Ca(), _cachedScopes(), _calculateBarIndexPixels() (+87 more)

### Community 15 - "Core Module 15"
Cohesion: 0.05
Nodes (23): CreateUser, EditUser, AdminNotificationController, AuthController, AdminMiddleware, EnsureStaffPermission, AdminNotificationStatus, HasOne (+15 more)

### Community 16 - "Data Models & Domain (C16)"
Cohesion: 0.05
Nodes (52): ba(), bi(), c(), ca(), clickPercent(), constructor(), de(), e() (+44 more)

### Community 17 - "Core Module 17"
Cohesion: 0.12
Nodes (69): Sg(), at(), B(), br(), Bt(), cd(), Cr(), Ct() (+61 more)

### Community 18 - "HTTP Controllers & Routing (C18)"
Cohesion: 0.05
Nodes (4): BookingForm, Collection, Illuminate\Support\Facades\Validator, Validator

### Community 19 - "HTTP Controllers & Routing (C19)"
Cohesion: 0.04
Nodes (67): Ac(), Bl(), cf(), clone(), create(), Dl(), dtFormatter(), eg() (+59 more)

### Community 20 - "Core Module 20"
Cohesion: 0.05
Nodes (67): addBox(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+59 more)

### Community 21 - "Data Models & Domain (C21)"
Cohesion: 0.04
Nodes (12): Pr(), Bi(), Id(), ji(), qi(), Ri(), te(), Vi() (+4 more)

### Community 22 - "Core Module 22"
Cohesion: 0.06
Nodes (59): attachmentManagerDidRequestRemovalOfAttachment(), breaksOnReturn(), Ca(), canSetCurrentAttribute(), canSetCurrentBlockAttribute(), compositionControllerDidRequestRemovalOfAttachment(), decreaseBlockAttributeLevel(), decreaseListLevel() (+51 more)

### Community 23 - "Core Module 23"
Cohesion: 0.06
Nodes (42): ai(), apply(), co(), Cr(), $e(), es(), Et(), fo() (+34 more)

### Community 24 - "Filament Admin & UI (C24)"
Cohesion: 0.05
Nodes (55): addEventListener(), an(), Au(), ba(), beforeDraw(), bindEvents(), bindResponsiveEvents(), bindUserEvents() (+47 more)

### Community 25 - "Data Models & Domain (C25)"
Cohesion: 0.08
Nodes (50): e(), i(), l(), Ni(), o(), t(), u(), be() (+42 more)

### Community 26 - "Data Models & Domain (C26)"
Cohesion: 0.05
Nodes (54): _a(), active(), add(), _animateOptions(), average(), ba(), _cachedScopes(), _createAnimations() (+46 more)

### Community 27 - "Core Module 27"
Cohesion: 0.07
Nodes (49): buildOrUpdateScales(), C(), cl(), Co(), cr(), D(), E(), endOf() (+41 more)

### Community 28 - "Core Module 28"
Cohesion: 0.06
Nodes (17): ListAccommodations, ListApkUsers, ListAppNotifications, ListBookings, ListDiscounts, ListFerryRoutes, ListGraciaEarningRules, ListInquiries (+9 more)

### Community 29 - "Database Schema (C29)"
Cohesion: 0.06
Nodes (23): actions(), button(), constructor(), danger(), dispatch(), dispatchSelf(), dispatchTo(), duration() (+15 more)

### Community 30 - "Core Module 30"
Cohesion: 0.07
Nodes (7): Passenger, ScheduleAccommodation, TourDate, Voucher, VoucherRedemption, VoucherService, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 31 - "Frontend & Components (C31)"
Cohesion: 0.07
Nodes (44): ActivityScreen, _ActivityScreenState, BookingDetailsScreen, _BookingDetailsScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState (+36 more)

### Community 32 - "Core Module 32"
Cohesion: 0.06
Nodes (43): disabled(), afterAutoSkip(), Ao(), Bi(), buildLookupTable(), buildTicks(), determineDataLimits(), diff() (+35 more)

### Community 33 - "Core Module 33"
Cohesion: 0.08
Nodes (43): adjustHitBoxes(), afterDraw(), bc(), Bl(), clear(), _computeLabelArea(), _computeTitleHeight(), _createItems() (+35 more)

### Community 34 - "Data Models & Domain (C34)"
Cohesion: 0.06
Nodes (11): OverallReports, Collection, StaffPerformance, ViewApkUser, ViewBooking, ViewInquiry, ViewPromotionalTicket, ViewTransaction (+3 more)

### Community 35 - "Data Models & Domain (C35)"
Cohesion: 0.07
Nodes (16): AccommodationController, DiscountController, Accommodation, Discount, VehicleBrand, VehicleModel, DatabaseSeeder, DiscountSeeder (+8 more)

### Community 36 - "Core Module 36"
Cohesion: 0.07
Nodes (41): as(), At(), Bs(), cc(), De(), Ea(), ed(), Fc() (+33 more)

### Community 37 - "Filament Admin & UI (C37)"
Cohesion: 0.07
Nodes (40): afterDatasetsUpdate(), buildOrUpdateControllers(), _d(), _destroyDatasetMeta(), Fd(), first(), generateLabels(), getDatasetMeta() (+32 more)

### Community 38 - "Core Module 38"
Cohesion: 0.07
Nodes (14): EditAccommodation, EditAppNotification, EditBooking, EditDiscount, EditGraciaEarningRule, EditPromotionalTicket, EditSchedule, EditTour (+6 more)

### Community 39 - "Data Models & Domain (C39)"
Cohesion: 0.07
Nodes (33): cacheViewForObject(), compositionDidLoadSnapshot(), createAttachmentNodes(), createChildView(), createContainerElement(), createDocumentFragmentForSync(), createElement(), createNodes() (+25 more)

### Community 40 - "HTTP Controllers & Routing (C40)"
Cohesion: 0.10
Nodes (36): add(), applyKeyboardCommand(), attachmentEditorDidRequestRemovalOfAttachment(), canBeGrouped(), checkValidity(), createCaptionElement(), dialogIsVisible(), didClickActionButton() (+28 more)

### Community 41 - "Data Models & Domain (C41)"
Cohesion: 0.08
Nodes (36): canAcceptDataTransfer(), canDecreaseNestingLevel(), canIncreaseNestingLevel(), compositionControllerDidFocus(), compositionDidRequestChangingSelectionToLocationRange(), createDOMRangeFromPoint(), createLocationRangeFromDOMRange(), decreaseNestingLevel() (+28 more)

### Community 42 - "Filament Admin & UI (C42)"
Cohesion: 0.09
Nodes (36): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate(), beforeBuildTicks() (+28 more)

### Community 43 - "Core Module 43"
Cohesion: 0.09
Nodes (36): calculateCircumference(), calculateLabelRotation(), _calculatePadding(), _circumference(), _computeAngle(), _computeLabelItems(), _computeLabelSizes(), computeTickLimit() (+28 more)

### Community 44 - "Core Module 44"
Cohesion: 0.07
Nodes (11): ListTours, PromotionController, TourController, VoucherController, BookingExportController, Controller, TourController, Tour (+3 more)

### Community 45 - "Core Module 45"
Cohesion: 0.10
Nodes (13): CreatesApplication, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, BookingLookupCancellationTest, BookingRebookingFlowTest, ExampleTest, GraciaPointsServiceTest, TestCase (+5 more)

### Community 46 - "HTTP Controllers & Routing (C46)"
Cohesion: 0.08
Nodes (35): afterDatasetsUpdate(), buildOrUpdateControllers(), _destroyDatasetMeta(), dl(), Do(), generateLabels(), getController(), getDatasetMeta() (+27 more)

### Community 47 - "Core Module 47"
Cohesion: 0.07
Nodes (34): box(), canBeConsolidatedWith(), canBeGroupedWith(), canDecreaseBlockAttributeLevel(), compositionControllerDidRender(), constructor(), formDisabledCallback(), fromUCS2String() (+26 more)

### Community 48 - "Data Models & Domain (C48)"
Cohesion: 0.12
Nodes (34): Ae(), ar(), at(), Cn(), de(), dt(), En(), fr() (+26 more)

### Community 49 - "Data Models & Domain (C49)"
Cohesion: 0.10
Nodes (34): applyStack(), ar(), as(), _calculateBarIndexPixels(), _calculateBarValuePixels(), _computeGridLineItems(), countVisibleElements(), datasetAnimationScopeKeys() (+26 more)

### Community 50 - "Data Models & Domain (C50)"
Cohesion: 0.07
Nodes (5): ManageWebsiteSettings, WebsiteSetting, AppServiceProvider, Illuminate\Support\Facades\View, Illuminate\Support\ServiceProvider

### Community 51 - "Core Module 51"
Cohesion: 0.07
Nodes (33): Ag(), Ef(), fe(), features(), fromFormat(), fromHTTP(), fromISOTime(), fromRFC2822() (+25 more)

### Community 52 - "Data Models & Domain (C52)"
Cohesion: 0.08
Nodes (32): alpha(), be(), ea(), en(), fe(), Fs(), ge(), Go() (+24 more)

### Community 53 - "Filament Admin & UI (C53)"
Cohesion: 0.08
Nodes (11): Action, DeleteAllUsers, PurgeExpiredProofs, ManagePaymentSettings, ManageProofs, PaymentSetting, self, Filament\Actions\Action (+3 more)

### Community 54 - "Core Module 54"
Cohesion: 0.09
Nodes (17): a(), ar(), at(), b(), cr(), d(), f(), g() (+9 more)

### Community 55 - "HTTP Controllers & Routing (C55)"
Cohesion: 0.09
Nodes (31): Yn(), acquireContext(), addElements(), buildOrUpdateElements(), configure(), constructor(), _dataCheck(), datasetScopeKeys() (+23 more)

### Community 56 - "Core Module 56"
Cohesion: 0.08
Nodes (5): FerryRouteResource, CreateFerryRoute, EditFerryRoute, Vehicle, $set(

### Community 57 - "Data Models & Domain (C57)"
Cohesion: 0.10
Nodes (15): CreateAccommodation, CreateAppNotification, CreateBooking, CreateDiscount, CreateGraciaEarningRule, CreateInquiry, CreatePromotionalTicket, CreateSchedule (+7 more)

### Community 58 - "Core Module 58"
Cohesion: 0.17
Nodes (29): _a(), ba(), Be(), Bi(), br(), Ca(), ce(), Dn() (+21 more)

### Community 59 - "Core Module 59"
Cohesion: 0.09
Nodes (18): Any, Cocoa, Flutter, AppDelegate, Bool, RunnerTests, AppDelegate, Bool (+10 more)

### Community 60 - "Filament Admin & UI (C60)"
Cohesion: 0.13
Nodes (8): GraciaPointsController, AppNotification, GraciaEarningRule, GraciaPointLedger, GraciaUserBalance, GraciaPointsService, GraciaEarningRuleSeeder, Illuminate\Database\Eloquent\Factories\HasFactory

### Community 61 - "Data Models & Domain (C61)"
Cohesion: 0.07
Nodes (26): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+18 more)

### Community 62 - "Core Module 62"
Cohesion: 0.09
Nodes (27): attachmentForFile(), attributesForFile(), compositionShouldAcceptFile(), didChangeAttributes(), getContentType(), getCurrentTextAttributes(), getHeight(), getHref() (+19 more)

### Community 63 - "Data Models & Domain (C63)"
Cohesion: 0.09
Nodes (27): active(), _animateOptions(), average(), cd(), clear(), cn(), _createAnimations(), Da() (+19 more)

### Community 64 - "Data Models & Domain (C64)"
Cohesion: 0.10
Nodes (27): tl(), ac(), Ai(), ca(), ec(), Fc(), G(), getIndexAngle() (+19 more)

### Community 65 - "Core Module 65"
Cohesion: 0.24
Nodes (26): d(), Di(), f(), Ge(), h(), I(), ja(), k() (+18 more)

### Community 66 - "HTTP Controllers & Routing (C66)"
Cohesion: 0.10
Nodes (26): add(), bf(), buildTicks(), eh(), _generate(), _getAnims(), getTickLimit(), Gi() (+18 more)

### Community 67 - "Core Module 67"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 68 - "Database Schema (C68)"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 69 - "Core Module 69"
Cohesion: 0.08
Nodes (24): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+16 more)

### Community 70 - "Database Seeders & Testing (C70)"
Cohesion: 0.17
Nodes (25): Qt(), aa(), da(), fa(), Fi(), fn(), gr(), Ii() (+17 more)

### Community 71 - "Data Models & Domain (C71)"
Cohesion: 0.08
Nodes (24): APP_DEBUG, APP_ENV, APP_NAME, APP_URL, CACHE_STORE, DB_CONNECTION, DB_DATABASE, DB_HOST (+16 more)

### Community 72 - "Core Module 72"
Cohesion: 0.12
Nodes (23): [g](), Aa(), Jc(), Ln(), ma(), pi(), qa(), qc() (+15 more)

### Community 73 - "HTTP Controllers & Routing (C73)"
Cohesion: 0.11
Nodes (22): addControllers(), addPlugins(), addScales(), al(), cancel(), _createDescriptors(), _descriptors(), _each() (+14 more)

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
Cohesion: 0.10
Nodes (21): canSetCurrentTextAttribute(), compositionControllerDidRequestDeselectingAttachment(), compositionDidStartEditingAttachment(), cut(), didClickAttachment(), dragstart(), findAttachmentForElement(), getAttachmentAndPositionById() (+13 more)

### Community 78 - "Core Module 78"
Cohesion: 0.10
Nodes (21): Bi(), chartOptionScopes(), constructor(), describe(), Ec(), Fr(), getDevicePixelRatio(), getMeta() (+13 more)

### Community 79 - "Core Module 79"
Cohesion: 0.10
Nodes (20): 1. Clone the repository, 1. Navigate to the Flutter folder, 2. Install Flutter Dependencies, 2. Install PHP Dependencies, 3. Install Node Dependencies, 3. Update the API Endpoint, 4. Environment Configuration, 4. Run the App (+12 more)

### Community 80 - "HTTP Controllers & Routing (C80)"
Cohesion: 0.15
Nodes (16): wchar_t, Scale(), Create, Destroy, SetQuitOnClose, Show, UpdateTheme, Win32Window::Win32Window() (+8 more)

### Community 81 - "Core Module 81"
Cohesion: 0.18
Nodes (20): appendAttachmentWithAttributes(), appendBlockForAttributesWithElement(), appendBlockForElement(), appendBlockForTextNode(), appendEmptyBlock(), appendPiece(), appendStringWithAttributes(), find() (+12 more)

### Community 82 - "Core Module 82"
Cohesion: 0.11
Nodes (17): AndroidFlutterLocalNotificationsPlugin, @pragma, dart:io, _downloadAndSaveFile, firebaseMessagingBackgroundHandler, initialize, initializeApp, _localNotifications (+9 more)

### Community 83 - "Core Module 83"
Cohesion: 0.12
Nodes (15): DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow, flutter_controller_ (+7 more)

### Community 84 - "Core Module 84"
Cohesion: 0.16
Nodes (5): BookingStatusChart, RecentActivityWidget, RevenueChartWidget, TopRoutesWidget, Filament\Widgets\Widget

### Community 86 - "Data Models & Domain (C86)"
Cohesion: 0.15
Nodes (17): At(), beforeDraw(), dataset(), Fa(), getMaximumSize(), getSortedVisibleDatasetMetas(), getVisibleDatasetCount(), index() (+9 more)

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
Nodes (12): RegisterPlugins(), OnCreate, HWND, Win32Window, child_content_, GetClientArea, OnCreate, quit_on_close_ (+4 more)

### Community 93 - "Core Module 93"
Cohesion: 0.15
Nodes (11): file_selector_macos, firebase_core, firebase_messaging, RegisterGeneratedPlugins(), flutter_local_notifications, FlutterPluginRegistry, FlutterViewController, Foundation (+3 more)

### Community 94 - "Data Models & Domain (C94)"
Cohesion: 0.18
Nodes (12): Ce(), De(), di(), e(), Ht(), Ie(), Re(), t() (+4 more)

### Community 95 - "Filament Admin & UI (C95)"
Cohesion: 0.15
Nodes (12): background_color, categories, description, display, icons, name, orientation, short_name (+4 more)

### Community 96 - "Filament Admin & UI (C96)"
Cohesion: 0.24
Nodes (9): wWinMain(), string, wchar_t, CreateAndAttachConsole(), GetCommandLineArguments(), Utf8FromUtf16(), _In_, _In_opt_ (+1 more)

### Community 97 - "Core Module 97"
Cohesion: 0.26
Nodes (7): C(), D(), J(), O(), U(), v(), X()

### Community 98 - "Core Module 98"
Cohesion: 0.18
Nodes (12): Be(), ei(), ii(), le(), ni(), oi(), r(), ri() (+4 more)

### Community 99 - "Core Module 99"
Cohesion: 0.23
Nodes (12): aa(), determineDataLimits(), Dh(), _getLabelBounds(), getMinMax(), _getOtherScale(), getUserBounds(), handleTickRangeOptions() (+4 more)

### Community 100 - "Filament Admin & UI (C100)"
Cohesion: 0.18
Nodes (10): background_color, description, display, icons, name, orientation, prefer_related_applications, short_name (+2 more)

### Community 101 - "Core Module 101"
Cohesion: 0.36
Nodes (10): HWND, LPARAM, LRESULT, UINT, WPARAM, EnableFullDpiSupportIfAvailable(), GetHandle, GetThisFromHandle (+2 more)

### Community 102 - "Core Module 102"
Cohesion: 0.20
Nodes (9): Flutter & Android Studio Setup Guide, Option A: VS Code (Recommended), Option B: Android Studio, 📋 Prerequisites, 🚀 Step 1: Install the Flutter SDK, 📱 Step 2: Install and Configure Android Studio, 🛠️ Step 3: Run Flutter Doctor, 💻 Step 4: Configure Your IDE (+1 more)

### Community 103 - "Resource"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 104 - "Filament Admin & UI (C104)"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 105 - "Filament Admin & UI (C105)"
Cohesion: 0.22
Nodes (8): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7a - FalkorDB export (only if --falkordb or --falkordb-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 106 - "Filament Admin & UI (C106)"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 107 - "Filament Admin & UI (C107)"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 108 - "Core Module 108"
Cohesion: 0.25
Nodes (8): build, _checkVersionAndProceed, _goNext, _goToSchedule, _selectTransportOption, _showAirlineClassPicker, _showFerryAccommodationPicker, MaterialPageRoute

### Community 109 - "Core Module 109"
Cohesion: 0.29
Nodes (8): Dt(), Fe(), He(), i(), ir(), Mt(), nr(), rt()

### Community 110 - "Core Module 110"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 111 - "Core Module 111"
Cohesion: 0.33
Nodes (5): How to Update the Android App (APK), Step 1: Bump the Version Number, Step 2: Build the New APK, Step 3: Copy the New APK to the Web Server, What happens automatically next?

### Community 112 - "Core Module 112"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 113 - "User"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 114 - "Core Module 114"
Cohesion: 0.33
Nodes (5): For /graphify explain, For /graphify path, graphify reference: query, path, explain, Step 0 — Constrained query expansion (REQUIRED before traversal), Step 1 — Traversal

### Community 118 - "Database Seeders & Testing (C118)"
Cohesion: 0.33
Nodes (6): B(), g(), Hn(), lt(), _o(), Y()

### Community 119 - "Core Module 119"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 120 - "Vehicle"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 122 - "Core Module 122"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 123 - "Core Module 123"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 124 - "Core Module 124"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 125 - "Core Module 125"
Cohesion: 0.50
Nodes (4): post-create-project-cmd, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\

### Community 126 - "Core Module 126"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 127 - "Core Module 127"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 128 - "Core Module 128"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 129 - "Core Module 129"
Cohesion: 0.50
Nodes (3): Amiga Gracia Flutter App, Getting Started, Railway build

### Community 130 - "Core Module 130"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 131 - "Core Module 131"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 132 - "Core Module 132"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 137 - "Core Module 137"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **587 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+582 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **32 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `A()` connect `Core Module 72` to `Core Module 32`, `Core Module 65`, `HTTP Controllers & Routing (C2)`, `Core Module 33`, `Core Module 4`, `Database Seeders & Testing (C70)`, `HTTP Controllers & Routing (C40)`, `Core Module 10`, `Data Models & Domain (C12)`, `Core Module 47`, `Core Module 17`, `Core Module 22`, `Data Models & Domain (C25)`?**
  _High betweenness centrality (0.037) - this node is a cross-community bridge._
- **Why does `draw()` connect `Data Models & Domain (C12)` to `Data Models & Domain (C1)`, `Core Module 65`, `Core Module 72`, `Core Module 10`, `Core Module 43`, `Core Module 14`, `Core Module 20`, `Filament Admin & UI (C24)`, `Core Module 27`, `Data Models & Domain (C63)`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **Why does `Br()` connect `Data Models & Domain (C1)` to `Core Module 6`, `HTTP Controllers & Routing (C40)`, `Core Module 47`, `Core Module 17`, `Data Models & Domain (C94)`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Are the 16 inferred relationships involving `x()` (e.g. with `de()` and `g()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **Are the 80 inferred relationships involving `static` (e.g. with `.canCreate()` and `.canDelete()`) actually correct?**
  _`static` has 80 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _587 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `HTTP Controllers & Routing (C0)` be split into smaller, more focused modules?**
  _Cohesion score 0.006472491909385114 - nodes in this community are weakly interconnected._