# Graph Report - Amiga-Travel  (2026-07-25)

## Corpus Check
<<<<<<< HEAD
- 449 files · ~417,297 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 5574 nodes · 13396 edges · 335 communities (286 shown, 49 thin omitted)
- Extraction: 90% EXTRACTED · 10% INFERRED · 0% AMBIGUOUS · INFERRED: 1293 edges (avg confidence: 0.69)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `8438a3ae`
=======
- 436 files · ~411,927 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 108 nodes · 178 edges · 12 communities (8 shown, 4 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `2b00ce70`
>>>>>>> 9adb4dc65eeb8b8576a21baa966c74bbe0a1ff84
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
<<<<<<< HEAD
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
- Livewire\Component
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
- md
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
- pe
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
=======
- ScheduleResource
>>>>>>> 9adb4dc65eeb8b8576a21baa966c74bbe0a1ff84
- ScheduleResource.php
- booking-form.blade.php
- filament.admin.notification-scripts
- .nextStep
<<<<<<< HEAD
- livewire/booking-reschedule.blade.php
- qt
- ListAppNotifications.php
- ListBookings.php
- .confirmTermsAndContinue
- yn
- ListDiscounts.php
- ListFerryRoutes.php
- ListGraciaEarningRules.php
- ListPromotionalTickets.php
- Illuminate\Support\Facades\Validator
- ListServiceCancellations.php
- ListTours
- ListTransactions.php
- ListTransportClasses.php
- ListUsers.php
- ListVehicleRates.php
- ListVouchers.php
- ListVehicleBrands

## God Nodes (most connected - your core abstractions)
1. `Booking` - 101 edges
2. `_update()` - 88 edges
3. `x()` - 85 edges
4. `BookingForm` - 84 edges
5. `_update()` - 84 edges
6. `te()` - 74 edges
7. `V()` - 72 edges
8. `Schedule` - 57 edges
9. `draw()` - 55 edges
10. `User` - 53 edges

## Surprising Connections (you probably didn't know these)
- `ServiceCancellationTest` --references--> `Booking`  [EXTRACTED]
  tests/Feature/ServiceCancellationTest.php → app/Models/Booking.php
- `ServiceCancellationTest` --references--> `FerryRoute`  [EXTRACTED]
  tests/Feature/ServiceCancellationTest.php → app/Models/FerryRoute.php
- `ServiceCancellationTest` --references--> `User`  [EXTRACTED]
  tests/Feature/ServiceCancellationTest.php → app/Models/User.php
- `getExtension()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/file-upload.js → public/js/filament/forms/components/markdown-editor.js
- `_getTestState()` --indirect_call--> `Ht()`  [INFERRED]
  public/js/filament/forms/components/file-upload.js → public/js/filament/forms/components/markdown-editor.js
=======
- BookingForm.php

## God Nodes (most connected - your core abstractions)
1. `BookingForm` - 84 edges
2. `ScheduleResource` - 11 edges
3. `date-picker` - 1 edges
4. `setTripType(` - 1 edges
5. `filament.admin.notification-scripts` - 1 edges

## Surprising Connections (you probably didn't know these)
- `BookingForm` --inherits--> `Component`  [EXTRACTED]
  app/Livewire/BookingForm.php →   _Bridges community 1 → community 8_
>>>>>>> 9adb4dc65eeb8b8576a21baa966c74bbe0a1ff84

## Import Cycles
- None detected.

<<<<<<< HEAD
## Communities (335 total, 49 thin omitted)

### Community 0 - "HTTP Controllers & Routing (C0)"
Cohesion: 0.01
Nodes (315): bool get, Color, dart:async, dart:convert, DateTime, double?, _accommodations, _activePassengerIndex (+307 more)

### Community 1 - "Data Models & Domain (C1)"
Cohesion: 0.01
Nodes (111): acquireContext(), addControllers(), addPlugins(), addScales(), alpha(), beforeDatasetDraw(), beforeDatasetsDraw(), beforeDraw() (+103 more)

### Community 2 - "HTTP Controllers & Routing (C2)"
Cohesion: 0.02
Nodes (128): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), canRedo(), canSyncDocumentView() (+120 more)

### Community 3 - "Core Module 3"
Cohesion: 0.02
Nodes (21): AccommodationResource, ApkUserResource, AppNotificationResource, BookingResource, DiscountResource, GraciaEarningRuleResource, InquiryResource, ScheduleResource (+13 more)

### Community 4 - "Core Module 4"
Cohesion: 0.04
Nodes (173): u(), _a(), Ac(), Ae(), af(), ai(), al(), An() (+165 more)

### Community 5 - "Core Module 5"
Cohesion: 0.02
Nodes (107): aa(), active(), addControllers(), addPlugins(), addScales(), al(), an(), _animateOptions() (+99 more)

### Community 6 - "Core Module 6"
Cohesion: 0.03
Nodes (105): _a(), abutsStart(), after(), afterAutoSkip(), Ag(), Ai(), Al(), before() (+97 more)

### Community 7 - "Data Models & Domain (C7)"
Cohesion: 0.03
Nodes (38): AdminNotifications, BookingsRelationManager, GraciaPointLedgersRelationManager, AccommodationsRelationManager, PassengersRelationManager, TransportClassesRelationManager, ScheduleAccommodationsRelationManager, TransportClassesRelationManager (+30 more)

### Community 8 - "Core Module 8"
Cohesion: 0.04
Nodes (113): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), canBeGroupedWith(), canDecreaseBlockAttributeLevel() (+105 more)

### Community 9 - "Database Schema (C9)"
Cohesion: 0.05
Nodes (9): FerryRouteResource, CreateFerryRoute, EditFerryRoute, ScheduleController, FerryRoute, Vehicle, FerryRouteSeeder, $set( (+1 more)

### Community 10 - "Core Module 10"
Cohesion: 0.07
Nodes (67): [g](), [x](), Sg(), $c(), D(), E(), Ea(), g() (+59 more)

### Community 11 - "Database Schema (C11)"
Cohesion: 0.04
Nodes (88): attachFiles(), backspace(), beforeinput(), canApplyToDocument(), compositionend(), compositionstart(), compositionupdate(), createLinkHTML() (+80 more)

### Community 12 - "Data Models & Domain (C12)"
Cohesion: 0.04
Nodes (118): ad(), adjustHitBoxes(), ae(), af(), aspectRatio(), C(), _calculateBarValuePixels(), calculateLabelRotation() (+110 more)

### Community 13 - "Core Module 13"
Cohesion: 0.04
Nodes (24): BookingController, BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested, RebookingVerification (+16 more)

### Community 14 - "Core Module 14"
Cohesion: 0.07
Nodes (43): aa(), Ah(), ar(), bf(), buildTicks(), determineDataLimits(), Dh(), Ea() (+35 more)

### Community 15 - "Core Module 15"
Cohesion: 0.05
Nodes (23): EditUser, AdminNotificationController, AuthController, AdminMiddleware, EnsureStaffPermission, AdminNotificationStatus, HasOne, User (+15 more)

### Community 16 - "Data Models & Domain (C16)"
Cohesion: 0.05
Nodes (48): ba(), bi(), c(), ca(), clickPercent(), constructor(), define(), e() (+40 more)

### Community 17 - "Core Module 17"
Cohesion: 0.12
Nodes (72): ad(), at(), B(), br(), Bt(), ca(), cd(), Cr() (+64 more)

### Community 19 - "HTTP Controllers & Routing (C19)"
Cohesion: 0.04
Nodes (81): Ac(), Bl(), Ce(), cf(), clone(), create(), Dl(), dtFormatter() (+73 more)

### Community 20 - "Core Module 20"
Cohesion: 0.03
Nodes (113): addBox(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterDraw(), afterFit(), afterSetDimensions() (+105 more)

### Community 21 - "Data Models & Domain (C21)"
Cohesion: 0.04
Nodes (18): Pr(), Bi(), bn(), Id(), ji(), kd(), ki(), kl() (+10 more)

### Community 22 - "Core Module 22"
Cohesion: 0.09
Nodes (37): Ao(), applyStack(), ar(), as(), _calculateBarIndexPixels(), _calculateBarValuePixels(), _computeGridLineItems(), countVisibleElements() (+29 more)

### Community 23 - "Core Module 23"
Cohesion: 0.06
Nodes (40): ai(), apply(), B(), co(), Cr(), $e(), es(), Et() (+32 more)

### Community 24 - "Filament Admin & UI (C24)"
Cohesion: 0.06
Nodes (37): an(), Au(), ba(), bu(), clear(), cn(), Da(), eo() (+29 more)

### Community 25 - "Data Models & Domain (C25)"
Cohesion: 0.29
Nodes (6): e(), i(), l(), Ni(), o(), t()

### Community 26 - "Data Models & Domain (C26)"
Cohesion: 0.09
Nodes (28): At(), beforeDraw(), dataset(), ea(), _eventHandler(), Fa(), ge(), getMaximumSize() (+20 more)

### Community 27 - "Core Module 27"
Cohesion: 0.14
Nodes (22): cr(), getMaxOffset(), getMaxOverflow(), Gi(), gt(), I(), _insertElements(), label() (+14 more)

### Community 28 - "Core Module 28"
Cohesion: 0.25
Nodes (5): ListAccommodations, ListApkUsers, ListInquiries, ListSchedules, Filament\Resources\Pages\ListRecords

### Community 29 - "Database Schema (C29)"
Cohesion: 0.06
Nodes (24): actions(), button(), constructor(), danger(), dispatch(), dispatchSelf(), dispatchTo(), duration() (+16 more)

### Community 30 - "Core Module 30"
Cohesion: 0.11
Nodes (26): buildOrUpdateScales(), cl(), D(), data(), E(), ensureScalesHaveIDs(), Eo(), fl() (+18 more)

### Community 31 - "Frontend & Components (C31)"
Cohesion: 0.07
Nodes (44): ActivityScreen, _ActivityScreenState, BookingDetailsScreen, _BookingDetailsScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState (+36 more)

### Community 32 - "Core Module 32"
Cohesion: 0.08
Nodes (43): add(), afterAutoSkip(), Bi(), buildLookupTable(), buildTicks(), C(), Co(), determineDataLimits() (+35 more)

### Community 33 - "Core Module 33"
Cohesion: 0.07
Nodes (54): $h(), Te(), acquireContext(), adjustHitBoxes(), afterDraw(), bc(), Bl(), clear() (+46 more)

### Community 34 - "Data Models & Domain (C34)"
Cohesion: 0.08
Nodes (5): OverallReports, Collection, StaffPerformance, ServiceCancellationResource, DatePicker

### Community 35 - "Data Models & Domain (C35)"
Cohesion: 0.13
Nodes (10): DatabaseSeeder, DiscountSeeder, ScheduleAccommodationSeeder, TourHotelsSeeder, TransportClassSeeder, VehicleRateSeeder, VehicleSeeder, WebsiteSettingSeeder (+2 more)

### Community 36 - "Core Module 36"
Cohesion: 0.12
Nodes (17): applyStack(), cs(), _drawDataset(), _drawDatasets(), fa(), Fd(), first(), _getSortedDatasetMetas() (+9 more)

### Community 37 - "Filament Admin & UI (C37)"
Cohesion: 0.06
Nodes (60): attachmentManagerDidRequestRemovalOfAttachment(), breakFormattedBlock(), breaksOnReturn(), Ca(), canSetCurrentAttribute(), canSetCurrentBlockAttribute(), compositionControllerDidRequestRemovalOfAttachment(), copyWithoutText() (+52 more)

### Community 38 - "Core Module 38"
Cohesion: 0.07
Nodes (14): EditAccommodation, EditAppNotification, EditBooking, EditDiscount, EditGraciaEarningRule, EditPromotionalTicket, EditSchedule, EditTour (+6 more)

### Community 39 - "Data Models & Domain (C39)"
Cohesion: 0.08
Nodes (29): cacheViewForObject(), compositionDidChangeDocument(), compositionDidLoadSnapshot(), createAttachmentNodes(), createChildView(), createContainerElement(), createDocumentFragmentForSync(), createElement() (+21 more)

### Community 40 - "HTTP Controllers & Routing (C40)"
Cohesion: 0.09
Nodes (41): add(), applyKeyboardCommand(), attachmentDidChangeAttributes(), attachmentEditorDidRequestRemovalOfAttachment(), canBeGrouped(), checkValidity(), createCaptionElement(), createContentNodes() (+33 more)

### Community 41 - "Data Models & Domain (C41)"
Cohesion: 0.08
Nodes (25): canAcceptDataTransfer(), compositionControllerDidFocus(), compositionDidRequestChangingSelectionToLocationRange(), createDOMRangeFromLocationRange(), createDOMRangeFromPoint(), createLocationRangeFromDOMRange(), didMouseDown(), domRangeWithinElement() (+17 more)

### Community 42 - "Filament Admin & UI (C42)"
Cohesion: 0.09
Nodes (35): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate(), beforeBuildTicks() (+27 more)

### Community 43 - "Core Module 43"
Cohesion: 0.10
Nodes (33): calculateCircumference(), calculateLabelRotation(), _calculatePadding(), _circumference(), _computeAngle(), _computeLabelItems(), _computeLabelSizes(), computeTickLimit() (+25 more)

### Community 44 - "Core Module 44"
Cohesion: 0.05
Nodes (16): AccommodationController, DiscountController, PromotionController, TourController, VoucherController, BookingExportController, Controller, TourController (+8 more)

### Community 45 - "Core Module 45"
Cohesion: 0.07
Nodes (33): as(), At(), Bi(), Bs(), cc(), chartOptionScopes(), constructor(), describe() (+25 more)

### Community 46 - "HTTP Controllers & Routing (C46)"
Cohesion: 0.11
Nodes (27): afterDatasetsUpdate(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), getStyle(), _handleEvent() (+19 more)

### Community 47 - "Core Module 47"
Cohesion: 0.08
Nodes (28): box(), canBeConsolidatedWith(), compositionControllerDidRender(), constructor(), disabled(), formDisabledCallback(), fromUCS2String(), get() (+20 more)

### Community 48 - "Data Models & Domain (C48)"
Cohesion: 0.23
Nodes (18): Ae(), at(), de(), dt(), fr(), Gt(), ht(), It() (+10 more)

### Community 49 - "Data Models & Domain (C49)"
Cohesion: 0.11
Nodes (24): Bt(), xo(), addEventListener(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), _checkEventBindings(), cs() (+16 more)

### Community 50 - "Data Models & Domain (C50)"
Cohesion: 0.07
Nodes (5): ManageWebsiteSettings, WebsiteSetting, AppServiceProvider, Illuminate\Support\Facades\View, Illuminate\Support\ServiceProvider

### Community 51 - "Core Module 51"
Cohesion: 0.15
Nodes (15): Ef(), features(), getMinDaysInFirstWeek(), getMinimumDaysInFirstWeek(), getStartOfWeek(), getWeekendDays(), getWeekendWeekdays(), getWeekSettings() (+7 more)

### Community 52 - "Data Models & Domain (C52)"
Cohesion: 0.06
Nodes (42): addEventListener(), average(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), ch(), cu(), dataset() (+34 more)

### Community 53 - "Filament Admin & UI (C53)"
Cohesion: 0.09
Nodes (11): Action, DeleteAllUsers, PurgeExpiredProofs, ManagePaymentSettings, ManageProofs, PaymentSetting, self, Filament\Actions\Action (+3 more)

### Community 54 - "Core Module 54"
Cohesion: 0.09
Nodes (17): a(), ar(), at(), b(), cr(), d(), f(), g() (+9 more)

### Community 55 - "HTTP Controllers & Routing (C55)"
Cohesion: 0.06
Nodes (45): Yn(), Rs(), U(), Ge(), _a(), ba(), _cachedScopes(), chartOptionScopes() (+37 more)

### Community 56 - "Core Module 56"
Cohesion: 0.10
Nodes (26): tl(), ac(), Ai(), ca(), ec(), Fc(), G(), getIndexAngle() (+18 more)

### Community 57 - "Data Models & Domain (C57)"
Cohesion: 0.08
Nodes (16): CreateAccommodation, CreateAppNotification, CreateBooking, CreateDiscount, CreateGraciaEarningRule, CreateInquiry, CreatePromotionalTicket, CreateSchedule (+8 more)

### Community 58 - "Core Module 58"
Cohesion: 0.16
Nodes (33): _a(), aa(), ba(), Be(), Bi(), br(), Ca(), ce() (+25 more)

### Community 59 - "Core Module 59"
Cohesion: 0.09
Nodes (18): Any, Cocoa, Flutter, AppDelegate, Bool, RunnerTests, AppDelegate, Bool (+10 more)

### Community 60 - "Filament Admin & UI (C60)"
Cohesion: 0.07
Nodes (11): GraciaPointsController, AppNotification, GraciaEarningRule, GraciaPointLedger, GraciaUserBalance, VoucherRedemption, GraciaPointsService, GraciaEarningRuleSeeder (+3 more)

### Community 61 - "Data Models & Domain (C61)"
Cohesion: 0.07
Nodes (26): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+18 more)

### Community 62 - "Core Module 62"
Cohesion: 0.15
Nodes (15): canSetCurrentTextAttribute(), cut(), didClickAttachment(), dragstart(), findAttachmentForElement(), getAttachmentAndPositionById(), getAttachmentById(), getAttachmentPieces() (+7 more)

### Community 63 - "Data Models & Domain (C63)"
Cohesion: 0.10
Nodes (25): alpha(), be(), en(), fe(), greyscale(), Hi(), ic(), interpolate() (+17 more)

### Community 64 - "Data Models & Domain (C64)"
Cohesion: 0.13
Nodes (24): _calculateBarIndexPixels(), calculateCircumference(), _circumference(), _computeAngle(), countVisibleElements(), _getCircumference(), getMaxOffset(), getMaxOverflow() (+16 more)

### Community 65 - "Core Module 65"
Cohesion: 0.25
Nodes (25): d(), Di(), f(), Ge(), I(), ir(), ja(), k() (+17 more)

### Community 66 - "HTTP Controllers & Routing (C66)"
Cohesion: 0.08
Nodes (35): active(), add(), _animateOptions(), _cachedScopes(), cancel(), _createAnimations(), _createDescriptors(), _descriptors() (+27 more)

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
Cohesion: 0.14
Nodes (30): Qt(), Cn(), da(), En(), fa(), Fi(), fn(), h() (+22 more)

### Community 71 - "Data Models & Domain (C71)"
Cohesion: 0.08
Nodes (24): APP_DEBUG, APP_ENV, APP_NAME, APP_URL, CACHE_STORE, DB_CONNECTION, DB_DATABASE, DB_HOST (+16 more)

### Community 72 - "Core Module 72"
Cohesion: 0.10
Nodes (30): be(), de(), Fe(), j(), je(), le(), vt(), Aa() (+22 more)

### Community 73 - "HTTP Controllers & Routing (C73)"
Cohesion: 0.12
Nodes (22): average(), getCenterPoint(), getProps(), hasValue(), hs(), inRange(), inXRange(), inYRange() (+14 more)

### Community 74 - "Data Models & Domain (C74)"
Cohesion: 0.10
Nodes (20): apexcharts, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, concurrently, laravel-vite-plugin (+12 more)

### Community 75 - "Core Module 75"
Cohesion: 0.10
Nodes (21): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+13 more)

### Community 76 - "Core Module 76"
Cohesion: 0.05
Nodes (46): actionIsExternal(), attachmentForFile(), attributesForFile(), canInvokeAction(), compositionControllerDidBlur(), compositionControllerDidSyncDocumentView(), compositionDidAddAttachment(), compositionDidChangeAttachmentPreviewURL() (+38 more)

### Community 77 - "Core Module 77"
Cohesion: 0.27
Nodes (14): canDecreaseNestingLevel(), canIncreaseNestingLevel(), decreaseNestingLevel(), formatIndent(), formatOutdent(), getBlock(), getLastNestableAttribute(), getListItemAttributes() (+6 more)

### Community 78 - "Core Module 78"
Cohesion: 0.10
Nodes (31): addElements(), buildOrUpdateControllers(), buildOrUpdateElements(), Ca(), co(), _dataCheck(), _destroy(), _destroyDatasetMeta() (+23 more)

### Community 79 - "Core Module 79"
Cohesion: 0.10
Nodes (20): 1. Clone the repository, 1. Navigate to the Flutter folder, 2. Install Flutter Dependencies, 2. Install PHP Dependencies, 3. Install Node Dependencies, 3. Update the API Endpoint, 4. Environment Configuration, 4. Run the App (+12 more)

### Community 80 - "HTTP Controllers & Routing (C80)"
Cohesion: 0.15
Nodes (16): wchar_t, Scale(), Create, Destroy, SetQuitOnClose, Show, UpdateTheme, Win32Window::Win32Window() (+8 more)

### Community 81 - "Core Module 81"
Cohesion: 0.20
Nodes (19): appendAttachmentWithAttributes(), appendBlockForAttributesWithElement(), appendBlockForElement(), appendBlockForTextNode(), appendEmptyBlock(), appendPiece(), appendStringWithAttributes(), find() (+11 more)

### Community 82 - "Core Module 82"
Cohesion: 0.11
Nodes (17): AndroidFlutterLocalNotificationsPlugin, @pragma, dart:io, _downloadAndSaveFile, firebaseMessagingBackgroundHandler, initialize, initializeApp, _localNotifications (+9 more)

### Community 83 - "Core Module 83"
Cohesion: 0.12
Nodes (15): DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow, flutter_controller_ (+7 more)

### Community 84 - "Core Module 84"
Cohesion: 0.16
Nodes (5): BookingStatusChart, RecentActivityWidget, RevenueChartWidget, TopRoutesWidget, Filament\Widgets\Widget

### Community 86 - "Livewire\Component"
Cohesion: 0.14
Nodes (5): BookingReschedule, PaymentProof, UserDashboard, Livewire\Component, Livewire\WithFileUploads

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
=======
## Communities (12 total, 4 thin omitted)

### Community 0 - "ScheduleResource"
>>>>>>> 9adb4dc65eeb8b8576a21baa966c74bbe0a1ff84
Cohesion: 0.19
Nodes (4): ScheduleResource, Form, Resource, Table

<<<<<<< HEAD
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

### Community 99 - "md"
Cohesion: 0.24
Nodes (10): dd(), Jl(), lr(), md(), rd(), uf(), xl(), yr() (+2 more)

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
Cohesion: 0.33
Nodes (6): build, _checkVersionAndProceed, _goNext, _goToSchedule, _selectTransportOption, MaterialPageRoute

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

### Community 117 - "_each"
Cohesion: 0.14
Nodes (19): ArrowLeft(), ArrowRight(), editAttachment(), expandSelectionInDirection(), findNodeAndOffsetFromLocation(), getAttachmentAtRange(), getExpandedRangeInDirection(), getSignificantNodesForIndex() (+11 more)

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

### Community 300 - "Filament\Resources\Pages\ViewRecord"
Cohesion: 0.15
Nodes (7): ViewApkUser, ViewBooking, ViewInquiry, ViewPromotionalTicket, ViewTransaction, ViewVoucher, Filament\Resources\Pages\ViewRecord

### Community 301 - "getDataset"
Cohesion: 0.17
Nodes (17): addElements(), beforeUpdate(), buildOrUpdateControllers(), buildOrUpdateElements(), _dataCheck(), _destroy(), _destroyDatasetMeta(), Ei() (+9 more)

### Community 302 - "VoucherResource"
Cohesion: 0.05
Nodes (5): PromotionalTicketResource, TransactionResource, PromotionalTicket, Filament\Infolists\Infolist, Illuminate\Database\Eloquent\Builder

### Community 306 - "livewire/booking-reschedule.blade.php"
Cohesion: 0.50
Nodes (3): requestSupport, selectOption({{ $sch->id }}, , submitReschedule

### Community 307 - "qt"
Cohesion: 0.36
Nodes (8): hs(), Ln(), Nn(), ps(), qt(), Ro(), Se(), wo()

### Community 310 - ".confirmTermsAndContinue"
Cohesion: 0.04
Nodes (12): CreateServiceCancellation, ViewServiceCancellation, Schedule, ScheduleAccommodation, ServiceCancellation, ServiceCancellationReplacementSchedule, ServiceCancellationManager, Filament\Tables\Concerns\InteractsWithTable (+4 more)

### Community 311 - "yn"
Cohesion: 0.33
Nodes (7): ar(), ft(), kn(), sr(), wn(), Ye(), yn()

## Knowledge Gaps
- **597 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+592 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **49 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.
=======
### Community 8 - "BookingForm.php"
Cohesion: 0.25
Nodes (5): Component, PromotionalTicket, Tour, TourDate, WithFileUploads

## Knowledge Gaps
- **3 isolated node(s):** `date-picker`, `setTripType(`, `filament.admin.notification-scripts`
  These have ≤1 connection - possible missing edges or undocumented components.
- **4 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.
>>>>>>> 9adb4dc65eeb8b8576a21baa966c74bbe0a1ff84

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

<<<<<<< HEAD
- **Why does `A()` connect `Core Module 72` to `Core Module 65`, `HTTP Controllers & Routing (C2)`, `md`, `Core Module 4`, `Filament Admin & UI (C37)`, `Database Seeders & Testing (C70)`, `Core Module 33`, `HTTP Controllers & Routing (C40)`, `Core Module 10`, `Data Models & Domain (C12)`, `Core Module 47`, `Core Module 17`?**
  _High betweenness centrality (0.033) - this node is a cross-community bridge._
- **Why does `draw()` connect `Data Models & Domain (C12)` to `Data Models & Domain (C64)`, `Data Models & Domain (C1)`, `Core Module 65`, `Core Module 33`, `Core Module 36`, `Core Module 72`, `Core Module 10`, `Core Module 43`, `Core Module 20`, `Data Models & Domain (C52)`, `Filament Admin & UI (C24)`, `Core Module 30`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Why does `Br()` connect `Data Models & Domain (C1)` to `Core Module 6`, `HTTP Controllers & Routing (C40)`, `Core Module 47`, `Core Module 17`, `Data Models & Domain (C94)`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Are the 40 inferred relationships involving `Booking` (e.g. with `.getStaffBookings()` and `.table()`) actually correct?**
  _`Booking` has 40 INFERRED edges - model-reasoned connections that need verification._
- **Are the 16 inferred relationships involving `x()` (e.g. with `de()` and `g()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _597 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `HTTP Controllers & Routing (C0)` be split into smaller, more focused modules?**
  _Cohesion score 0.006329113924050633 - nodes in this community are weakly interconnected._
=======
- **Why does `BookingForm` connect `ScheduleResource.php` to `.saveDraft`, `.updateReturnDateFromDuration`, `.nextStep`, `BookingForm.php`, `.calculateTotalPrice`, `.resetVehicleData`?**
  _High betweenness centrality (0.598) - this node is a cross-community bridge._
- **What connects `date-picker`, `setTripType(`, `filament.admin.notification-scripts` to the rest of the system?**
  _3 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `ScheduleResource.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07407407407407407 - nodes in this community are weakly interconnected._
- **Should `.saveDraft` be split into smaller, more focused modules?**
  _Cohesion score 0.12280701754385964 - nodes in this community are weakly interconnected._
>>>>>>> 9adb4dc65eeb8b8576a21baa966c74bbe0a1ff84
