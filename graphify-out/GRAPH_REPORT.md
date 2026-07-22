# Graph Report - .  (2026-07-22)

## Corpus Check
- 421 files · ~221,982 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 4743 nodes · 11912 edges · 245 communities (232 shown, 13 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 1129 edges (avg confidence: 0.67)
- Token cost: 0 input · 0 output

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
- Filament Admin & UI (C104)
- Filament Admin & UI (C105)
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
- Core Module 116
- Core Module 117
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
- Core Module 199

## God Nodes (most connected - your core abstractions)
1. `_update()` - 88 edges
2. `x()` - 85 edges
3. `_update()` - 84 edges
4. `te()` - 74 edges
5. `V()` - 72 edges
6. `BookingForm` - 69 edges
7. `Booking` - 55 edges
8. `draw()` - 55 edges
9. `vd()` - 53 edges
10. `Schedule` - 49 edges

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

## Communities (245 total, 13 thin omitted)

### Community 0 - "HTTP Controllers & Routing (C0)"
Cohesion: 0.01
Nodes (122): acquireContext(), addControllers(), addPlugins(), addScales(), afterDraw(), Ag(), alpha(), beforeDatasetDraw() (+114 more)

### Community 1 - "Data Models & Domain (C1)"
Cohesion: 0.01
Nodes (197): dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex, adults, availableAccommodations (+189 more)

### Community 2 - "HTTP Controllers & Routing (C2)"
Cohesion: 0.02
Nodes (118): activateAttributeIfSupported(), appendStringToTextAtIndex(), applyBlockAttribute(), attachmentDidChangeUploadProgress(), attachmentIsManaged(), attributeChangedCallback(), canRedo(), canSyncDocumentView() (+110 more)

### Community 3 - "Core Module 3"
Cohesion: 0.04
Nodes (142): _a(), Aa(), Ac(), af(), ai(), al(), An(), ao() (+134 more)

### Community 4 - "Core Module 4"
Cohesion: 0.02
Nodes (105): aa(), active(), an(), _animateOptions(), aspectRatio(), beforeDatasetDraw(), beforeDatasetsDraw(), beforeDraw() (+97 more)

### Community 5 - "Core Module 5"
Cohesion: 0.04
Nodes (109): addAttribute(), addAttributeAtRange(), addAttributesAtRange(), addHTMLAttribute(), appendText(), applyBlockAttributeAtRange(), breakFormattedBlock(), canBeGroupedWith() (+101 more)

### Community 6 - "Core Module 6"
Cohesion: 0.09
Nodes (60): [g](), [x](), $c(), D(), E(), g(), H(), _i() (+52 more)

### Community 7 - "Data Models & Domain (C7)"
Cohesion: 0.04
Nodes (95): addBox(), addEventListener(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+87 more)

### Community 8 - "Core Module 8"
Cohesion: 0.05
Nodes (93): ad(), adjustHitBoxes(), ae(), af(), C(), calculateLabelRotation(), _calculatePadding(), _computeAngle() (+85 more)

### Community 9 - "Database Schema (C9)"
Cohesion: 0.04
Nodes (89): _a(), abutsStart(), after(), afterAutoSkip(), Ai(), Al(), before(), bf() (+81 more)

### Community 10 - "Core Module 10"
Cohesion: 0.04
Nodes (81): addElements(), Ah(), applyStack(), aspectRatio(), buildOrUpdateElements(), Ca(), _calculateBarIndexPixels(), _calculateBarValuePixels() (+73 more)

### Community 11 - "Database Schema (C11)"
Cohesion: 0.03
Nodes (17): Bi(), bn(), fe(), Id(), ji(), kd(), on(), qi() (+9 more)

### Community 12 - "Data Models & Domain (C12)"
Cohesion: 0.05
Nodes (75): addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+67 more)

### Community 13 - "Core Module 13"
Cohesion: 0.05
Nodes (53): ba(), bi(), c(), ca(), clickPercent(), constructor(), de(), e() (+45 more)

### Community 14 - "Core Module 14"
Cohesion: 0.13
Nodes (70): Ae(), as(), at(), B(), br(), Bt(), ca(), Cr() (+62 more)

### Community 15 - "Core Module 15"
Cohesion: 0.06
Nodes (69): $h(), acquireContext(), adjustHitBoxes(), bc(), Bl(), calculateLabelRotation(), _calculatePadding(), clear() (+61 more)

### Community 16 - "Data Models & Domain (C16)"
Cohesion: 0.06
Nodes (4): BookingForm, Collection, BelongsTo, TourDate

### Community 17 - "Core Module 17"
Cohesion: 0.04
Nodes (65): ar(), Bl(), cf(), clone(), create(), Dl(), dtFormatter(), eg() (+57 more)

### Community 18 - "HTTP Controllers & Routing (C18)"
Cohesion: 0.06
Nodes (64): breaksOnReturn(), Ca(), canSetCurrentAttribute(), canSetCurrentBlockAttribute(), compositionControllerDidRequestDeselectingAttachment(), compositionDidStartEditingAttachment(), decreaseBlockAttributeLevel(), decreaseListLevel() (+56 more)

### Community 19 - "HTTP Controllers & Routing (C19)"
Cohesion: 0.05
Nodes (62): attachFiles(), backspace(), createLinkHTML(), cut(), d(), delete(), deleteByComposition(), deleteByCut() (+54 more)

### Community 20 - "Core Module 20"
Cohesion: 0.06
Nodes (61): add(), applyKeyboardCommand(), attachmentDidChangeAttributes(), attachmentEditorDidRequestRemovalOfAttachment(), canBeGrouped(), checkValidity(), compositionDidLoadSnapshot(), copyUsingObjectMap() (+53 more)

### Community 21 - "Data Models & Domain (C21)"
Cohesion: 0.06
Nodes (20): DashboardSummaryWidget, BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested, RebookingVerification (+12 more)

### Community 22 - "Core Module 22"
Cohesion: 0.05
Nodes (56): as(), At(), Bs(), cc(), cd(), clear(), cn(), Da() (+48 more)

### Community 23 - "Core Module 23"
Cohesion: 0.06
Nodes (42): ai(), apply(), co(), Cr(), $e(), es(), Et(), fo() (+34 more)

### Community 24 - "Filament Admin & UI (C24)"
Cohesion: 0.07
Nodes (21): Action, ManagePaymentSettings, Form, ManageProofs, Collection, Form, Collection, Form (+13 more)

### Community 25 - "Data Models & Domain (C25)"
Cohesion: 0.06
Nodes (52): Ao(), applyStack(), ar(), as(), Bi(), _calculateBarIndexPixels(), _calculateBarValuePixels(), calculateCircumference() (+44 more)

### Community 26 - "Data Models & Domain (C26)"
Cohesion: 0.06
Nodes (26): AccommodationsRelationManager, Form, Table, PassengersRelationManager, Form, Table, Form, Table (+18 more)

### Community 27 - "Core Module 27"
Cohesion: 0.07
Nodes (46): e(), i(), l(), Ni(), o(), t(), u(), define() (+38 more)

### Community 28 - "Core Module 28"
Cohesion: 0.06
Nodes (48): Yn(), Ge(), _a(), add(), ba(), _cachedScopes(), chartOptionScopes(), configure() (+40 more)

### Community 29 - "Database Schema (C29)"
Cohesion: 0.05
Nodes (48): beforeinput(), box(), canApplyToDocument(), compositionend(), compositionstart(), compositionupdate(), constructor(), dragend() (+40 more)

### Community 30 - "Core Module 30"
Cohesion: 0.06
Nodes (48): add(), Bi(), _cachedScopes(), chartOptionScopes(), constructor(), describe(), diffNow(), Ec() (+40 more)

### Community 31 - "Frontend & Components (C31)"
Cohesion: 0.06
Nodes (24): actions(), button(), constructor(), danger(), dispatch(), dispatchSelf(), dispatchTo(), duration() (+16 more)

### Community 32 - "Core Module 32"
Cohesion: 0.06
Nodes (45): Ac(), an(), Au(), ba(), bu(), color(), darken(), Dc() (+37 more)

### Community 33 - "Core Module 33"
Cohesion: 0.06
Nodes (44): Bt(), xo(), addEventListener(), bindResponsiveEvents(), cl(), cs(), Ct(), D() (+36 more)

### Community 34 - "Data Models & Domain (C34)"
Cohesion: 0.08
Nodes (8): BelongsTo, BelongsToMany, Builder, HasMany, Schedule, BelongsToMany, TransportClass, TransportClassSeeder

### Community 35 - "Data Models & Domain (C35)"
Cohesion: 0.07
Nodes (15): ListAccommodations, ListBookings, ListDiscounts, ListFerryRoutes, ListInquiries, ListSchedules, ListTransactions, ListTransportClasses (+7 more)

### Community 36 - "Core Module 36"
Cohesion: 0.07
Nodes (41): alpha(), At(), be(), dataset(), ea(), en(), Fa(), fe() (+33 more)

### Community 37 - "Filament Admin & UI (C37)"
Cohesion: 0.11
Nodes (10): Accommodation, BelongsToMany, Passenger, BelongsTo, BelongsTo, ScheduleAccommodation, HasMany, VehicleBrand (+2 more)

### Community 38 - "Core Module 38"
Cohesion: 0.09
Nodes (38): C(), Co(), _computeLabelSizes(), cr(), endOf(), Et(), format(), formats() (+30 more)

### Community 39 - "Data Models & Domain (C39)"
Cohesion: 0.08
Nodes (15): Inquiry, AdminNotificationFeed, Collection, BaseTestCase, CreatesApplication, RefreshDatabase, BookingLookupCancellationTest, BookingRebookingFlowTest (+7 more)

### Community 40 - "HTTP Controllers & Routing (C40)"
Cohesion: 0.08
Nodes (36): canAcceptDataTransfer(), canDecreaseNestingLevel(), canIncreaseNestingLevel(), compositionControllerDidFocus(), compositionDidRequestChangingSelectionToLocationRange(), createDOMRangeFromLocationRange(), createDOMRangeFromPoint(), createLocationRangeFromDOMRange() (+28 more)

### Community 41 - "Data Models & Domain (C41)"
Cohesion: 0.07
Nodes (6): ManageWebsiteSettings, Form, WebsiteSetting, AppServiceProvider, WebsiteSettingSeeder, ServiceProvider

### Community 42 - "Filament Admin & UI (C42)"
Cohesion: 0.08
Nodes (11): FerryRouteResource, CreateFerryRoute, EditFerryRoute, Form, Table, self, HasMany, Vehicle (+3 more)

### Community 43 - "Core Module 43"
Cohesion: 0.09
Nodes (34): be(), Sg(), ad(), cd(), dd(), ef(), fa(), Jl() (+26 more)

### Community 44 - "Core Module 44"
Cohesion: 0.07
Nodes (34): attachmentForFile(), attributesForFile(), canSetCurrentTextAttribute(), compositionShouldAcceptFile(), didChangeAttributes(), didClickAttachment(), dragstart(), findAttachmentForElement() (+26 more)

### Community 45 - "Core Module 45"
Cohesion: 0.12
Nodes (34): Ae(), ar(), at(), Cn(), de(), dt(), En(), fr() (+26 more)

### Community 46 - "HTTP Controllers & Routing (C46)"
Cohesion: 0.08
Nodes (34): afterDatasetsUpdate(), buildOrUpdateControllers(), _d(), _destroyDatasetMeta(), Fd(), first(), generateLabels(), getDatasetMeta() (+26 more)

### Community 47 - "Core Module 47"
Cohesion: 0.08
Nodes (32): average(), ch(), cu(), dataset(), en(), eu(), getCenterPoint(), getMaximumSize() (+24 more)

### Community 48 - "Data Models & Domain (C48)"
Cohesion: 0.10
Nodes (31): ActivityScreen, _ActivityScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState, DiscountScreen, _DiscountScreenState (+23 more)

### Community 49 - "Data Models & Domain (C49)"
Cohesion: 0.09
Nodes (11): EditAccommodation, EditBooking, EditDiscount, EditSchedule, EditTour, EditTransportClass, EditUser, EditVehicleBrand (+3 more)

### Community 50 - "Data Models & Domain (C50)"
Cohesion: 0.12
Nodes (5): BookingLookup, PaymentProof, UserDashboard, Component, WithFileUploads

### Community 51 - "Core Module 51"
Cohesion: 0.17
Nodes (29): _a(), ba(), Be(), Bi(), br(), Ca(), ce(), Dn() (+21 more)

### Community 52 - "Data Models & Domain (C52)"
Cohesion: 0.10
Nodes (11): BelongsTo, VehicleModel, DatabaseSeeder, DiscountSeeder, ScheduleAccommodationSeeder, TourHotelsSeeder, VehicleBrandModelSeeder, VehicleRateSeeder (+3 more)

### Community 53 - "Filament Admin & UI (C53)"
Cohesion: 0.10
Nodes (10): DiscountResource, Form, Table, InquiryResource, Form, Table, Form, Table (+2 more)

### Community 54 - "Core Module 54"
Cohesion: 0.10
Nodes (13): a(), ar(), at(), cr(), d(), f(), H(), ji() (+5 more)

### Community 55 - "HTTP Controllers & Routing (C55)"
Cohesion: 0.09
Nodes (27): actionIsExternal(), canBeConsolidatedWith(), canInvokeAction(), compositionControllerDidBlur(), compositionControllerDidRender(), compositionControllerDidSyncDocumentView(), compositionDidAddAttachment(), compositionDidChangeAttachmentPreviewURL() (+19 more)

### Community 56 - "Core Module 56"
Cohesion: 0.24
Nodes (26): d(), Di(), f(), Ge(), h(), I(), ja(), k() (+18 more)

### Community 57 - "Data Models & Domain (C57)"
Cohesion: 0.09
Nodes (7): Form, ViewBooking, ViewInquiry, ViewTransaction, ViewUserLoginHistory, DatePicker, ViewRecord

### Community 58 - "Core Module 58"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 59 - "Core Module 59"
Cohesion: 0.17
Nodes (25): Qt(), aa(), da(), fa(), Fi(), fn(), gr(), Ii() (+17 more)

### Community 60 - "Filament Admin & UI (C60)"
Cohesion: 0.12
Nodes (8): ListTours, Form, Table, TourResource, TourController, HasMany, Tour, Attribute

### Community 61 - "Data Models & Domain (C61)"
Cohesion: 0.12
Nodes (8): AccommodationController, DiscountController, PromotionController, Request, TourController, BookingExportController, Controller, Response

### Community 62 - "Core Module 62"
Cohesion: 0.11
Nodes (24): cacheViewForObject(), createAttachmentNodes(), createChildView(), createContainerElement(), createDocumentFragmentForSync(), createElement(), createNodes(), didSync() (+16 more)

### Community 63 - "Data Models & Domain (C63)"
Cohesion: 0.11
Nodes (8): DeleteAllUsers, PurgeExpiredProofs, BookingController, Request, Discount, HasMany, PaymentSetting, Command

### Community 64 - "Data Models & Domain (C64)"
Cohesion: 0.13
Nodes (12): CreateAccommodation, CreateBooking, CreateDiscount, CreateInquiry, CreateSchedule, CreateTour, CreateTransportClass, CreateUser (+4 more)

### Community 65 - "Core Module 65"
Cohesion: 0.13
Nodes (23): afterDatasetsUpdate(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), getStyle(), _handleEvent() (+15 more)

### Community 66 - "HTTP Controllers & Routing (C66)"
Cohesion: 0.12
Nodes (22): ArrowLeft(), ArrowRight(), attachmentManagerDidRequestRemovalOfAttachment(), compositionControllerDidRequestRemovalOfAttachment(), editAttachment(), expandSelectionInDirection(), findNodeAndOffsetFromLocation(), getAttachmentAtRange() (+14 more)

### Community 67 - "Core Module 67"
Cohesion: 0.09
Nodes (21): APP_DEBUG, APP_ENV, APP_NAME, APP_URL, CACHE_STORE, DB_CONNECTION, DB_DATABASE, DB_HOST (+13 more)

### Community 68 - "Database Schema (C68)"
Cohesion: 0.12
Nodes (21): disabled(), afterAutoSkip(), buildLookupTable(), buildTicks(), diff(), Fi(), _generate(), getAllParsedValues() (+13 more)

### Community 69 - "Core Module 69"
Cohesion: 0.13
Nodes (21): tl(), ac(), Ai(), ca(), ec(), Fc(), G(), getIndexAngle() (+13 more)

### Community 70 - "Database Seeders & Testing (C70)"
Cohesion: 0.14
Nodes (6): Request, ScheduleController, FerryRoute, BelongsTo, HasMany, FerryRouteSeeder

### Community 71 - "Data Models & Domain (C71)"
Cohesion: 0.10
Nodes (20): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+12 more)

### Community 72 - "Core Module 72"
Cohesion: 0.11
Nodes (20): beforeDraw(), _drawDatasets(), eh(), getSortedVisibleDatasetMetas(), getVisibleDatasetCount(), Gi(), ih(), Me() (+12 more)

### Community 73 - "HTTP Controllers & Routing (C73)"
Cohesion: 0.13
Nodes (20): addControllers(), addPlugins(), addScales(), al(), cancel(), _createDescriptors(), _descriptors(), _each() (+12 more)

### Community 74 - "Data Models & Domain (C74)"
Cohesion: 0.20
Nodes (8): AuthController, Request, AdminPanelProvider, Panel, Color, PanelProvider, RedirectResponse, View

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
Cohesion: 0.15
Nodes (11): Cocoa, file_selector_macos, RegisterGeneratedPlugins(), MainFlutterWindow, FlutterMacOS, FlutterPluginRegistry, FlutterViewController, Foundation (+3 more)

### Community 81 - "Core Module 81"
Cohesion: 0.13
Nodes (13): DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow, flutter_controller_ (+5 more)

### Community 82 - "Core Module 82"
Cohesion: 0.17
Nodes (16): average(), getCenterPoint(), getProps(), hasValue(), inRange(), Is(), Kt(), nearest() (+8 more)

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
Cohesion: 0.17
Nodes (11): addQuickFact, addSocialLink, closePanel, removeHeroImage({{ (int)$idx }}), removeQuickFact({{ $fi }}), removeSocialLink({{ $li }}), removeSocialLink({{ $sli }}, , save (+3 more)

### Community 90 - "Filament Admin & UI (C90)"
Cohesion: 0.21
Nodes (5): Form, Table, UserLoginHistoryResource, BelongsTo, UserLoginHistory

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
Cohesion: 0.23
Nodes (12): aa(), determineDataLimits(), Dh(), _getLabelBounds(), getMinMax(), _getOtherScale(), getUserBounds(), handleTickRangeOptions() (+4 more)

### Community 95 - "Filament Admin & UI (C95)"
Cohesion: 0.18
Nodes (4): Builder, Table, TransactionResource, Infolist

### Community 96 - "Filament Admin & UI (C96)"
Cohesion: 0.22
Nodes (4): Form, Table, ScheduleResource, $set(

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
Cohesion: 0.24
Nodes (3): Form, Table, VehicleBrandResource

### Community 101 - "Core Module 101"
Cohesion: 0.22
Nodes (6): Flutter, RunnerTests, RunnerTests, UIKit, XCTest, XCTestCase

### Community 102 - "Core Module 102"
Cohesion: 0.36
Nodes (10): HWND, LPARAM, LRESULT, UINT, WPARAM, EnableFullDpiSupportIfAvailable(), GetHandle, GetThisFromHandle (+2 more)

### Community 103 - "Filament Admin & UI (C103)"
Cohesion: 0.28
Nodes (3): AccommodationResource, Form, Table

### Community 104 - "Filament Admin & UI (C104)"
Cohesion: 0.28
Nodes (3): Form, Table, TransportClassResource

### Community 105 - "Filament Admin & UI (C105)"
Cohesion: 0.28
Nodes (3): Form, Table, UserResource

### Community 106 - "Filament Admin & UI (C106)"
Cohesion: 0.28
Nodes (3): Form, Table, VehicleRateResource

### Community 107 - "Filament Admin & UI (C107)"
Cohesion: 0.29
Nodes (3): BookingResource, Form, Table

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

### Community 115 - "Core Module 115"
Cohesion: 0.33
Nodes (6): B(), g(), Hn(), lt(), _o(), Y()

### Community 117 - "Core Module 117"
Cohesion: 0.60
Nodes (3): AdminMiddleware, Request, Closure

### Community 118 - "Database Seeders & Testing (C118)"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 119 - "Core Module 119"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 121 - "Core Module 121"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **308 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+303 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **13 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `A()` connect `Core Module 43` to `HTTP Controllers & Routing (C2)`, `Core Module 3`, `Database Schema (C68)`, `Core Module 6`, `Core Module 8`, `Core Module 59`, `Core Module 14`, `Core Module 15`, `HTTP Controllers & Routing (C18)`, `Core Module 20`, `Core Module 56`, `Core Module 27`, `Database Schema (C29)`?**
  _High betweenness centrality (0.048) - this node is a cross-community bridge._
- **Why does `draw()` connect `Core Module 8` to `HTTP Controllers & Routing (C0)`, `Core Module 32`, `Core Module 6`, `Data Models & Domain (C7)`, `Core Module 72`, `Core Module 10`, `Core Module 43`, `Data Models & Domain (C12)`, `Core Module 47`, `Core Module 22`, `Core Module 56`, `Data Models & Domain (C25)`?**
  _High betweenness centrality (0.042) - this node is a cross-community bridge._
- **Why does `F()` connect `Core Module 6` to `HTTP Controllers & Routing (C2)`, `Core Module 4`, `Core Module 69`, `Core Module 8`, `Core Module 43`, `Core Module 45`, `Core Module 14`, `Core Module 15`, `Core Module 115`, `Core Module 56`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Are the 16 inferred relationships involving `x()` (e.g. with `de()` and `g()`) actually correct?**
  _`x()` has 16 INFERRED edges - model-reasoned connections that need verification._
- **Are the 20 inferred relationships involving `te()` (e.g. with `je()` and `Pr()`) actually correct?**
  _`te()` has 20 INFERRED edges - model-reasoned connections that need verification._
- **Are the 29 inferred relationships involving `V()` (e.g. with `Sg()` and `g()`) actually correct?**
  _`V()` has 29 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _308 weakly-connected nodes found - possible documentation gaps or missing edges._