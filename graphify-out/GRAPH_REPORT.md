# Graph Report - c:/laragon/www/AMIGA/Amiga-Travel  (2026-07-20)

## Corpus Check
- 389 files · ~170,457 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1480 nodes · 2206 edges · 172 communities (158 shown, 14 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 92 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Convert
- Bookingform
- Registrant
- Destinations
- Accommodationsrelationmanager
- Tourresource
- Bookinglookup
- Any Components
- Listaccommodations
- Action
- Authcontroller
- Activityscreen
- Editaccommodation
- Scripts
- Booking
- Flpluginregistry
- Bookinglookup
- Createaccommodation
- Purgeexpiredproofs
- Aboutfact
- Activedestinationsfor
- Basetestcase
- Concurrently
- Userloginhistoryresource
- Discount
- User
- Mount
- Form
- Require
- Dashboardsummarywidget
- Manifest
- Viewtransactionurl
- Main
- Manifest
- Managewebsitesettings
- Save
- Accommodationresource
- Discountresource
- Ferryrouteresource
- Inquiryresource
- Scheduleresource
- Transportclassresource
- Userresource
- Vehiclerateresource
- Inquiry
- Bookingresource
- Composer
- Dev Components
- Accommodation
- Plugin
- Build
- Deleteallusers
- Scheduleseatingprofiletest
- Overallreports
- Adminmiddleware
- Autoload
- Test
- Bookingcancellation
- Belongsto
- Dev Components
- Extra
- Keywords
- Picker
- Mainactivity
- Environment
- Blade
- Blade
- Blade
- Gmail
- String

## God Nodes (most connected - your core abstractions)
1. `BookingForm` - 65 edges
2. `Booking` - 53 edges
3. `Schedule` - 48 edges
4. `User` - 39 edges
5. `Transaction` - 24 edges
6. `Win32Window` - 22 edges
7. `TransportClass` - 21 edges
8. `BookingLookup` - 19 edges
9. `ManageProofs` - 18 edges
10. `TestCase` - 17 edges

## Surprising Connections (you probably didn't know these)
- `wWinMain()` --calls--> `CreateAndAttachConsole()`  [INFERRED]
  flutter_app/windows/runner/main.cpp → flutter_app/windows/runner/utils.cpp
- `Win32Window::Win32Window()` --calls--> `Destroy`  [INFERRED]
  flutter_app/windows/runner/win32_window.cpp → flutter_app/windows/runner/win32_window.h
- `BookingController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/BookingController.php → app/Http/Controllers/Controller.php
- `AuthController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/AuthController.php → app/Http/Controllers/Controller.php
- `BookingExportController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/BookingExportController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (172 total, 14 thin omitted)

### Community 0 - "Convert"
Cohesion: 0.01
Nodes (197): dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex, adults, availableAccommodations (+189 more)

### Community 1 - "Bookingform"
Cohesion: 0.06
Nodes (4): BookingForm, Collection, BelongsTo, TourDate

### Community 2 - "Registrant"
Cohesion: 0.06
Nodes (53): RegisterPlugins(), DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow (+45 more)

### Community 3 - "Destinations"
Cohesion: 0.06
Nodes (12): FerryRoute, BelongsTo, HasMany, BelongsTo, BelongsToMany, Builder, HasMany, Schedule (+4 more)

### Community 4 - "Accommodationsrelationmanager"
Cohesion: 0.06
Nodes (25): AccommodationsRelationManager, Form, Table, PassengersRelationManager, Form, Table, Form, Table (+17 more)

### Community 5 - "Tourresource"
Cohesion: 0.07
Nodes (16): ListTours, Form, Table, TourResource, AccommodationController, DiscountController, PromotionController, Request (+8 more)

### Community 6 - "Bookinglookup"
Cohesion: 0.08
Nodes (6): BookingLookup, DatePicker, PaymentProof, UserDashboard, Component, WithFileUploads

### Community 7 - "Any Components"
Cohesion: 0.07
Nodes (25): Any, Cocoa, file_selector_macos, Flutter, AppDelegate, Bool, RunnerTests, RegisterGeneratedPlugins() (+17 more)

### Community 8 - "Listaccommodations"
Cohesion: 0.08
Nodes (13): ListAccommodations, ListBookings, ListDiscounts, ListFerryRoutes, ListInquiries, ListSchedules, ListTransactions, ListTransportClasses (+5 more)

### Community 9 - "Action"
Cohesion: 0.11
Nodes (13): Action, ManagePaymentSettings, Form, ManageProofs, Collection, Form, Collection, StaffPerformance (+5 more)

### Community 10 - "Authcontroller"
Cohesion: 0.11
Nodes (12): AuthController, Request, BookingExportController, AppServiceProvider, AdminPanelProvider, Panel, Color, PanelProvider (+4 more)

### Community 11 - "Activityscreen"
Cohesion: 0.10
Nodes (31): ActivityScreen, _ActivityScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState, DiscountScreen, _DiscountScreenState (+23 more)

### Community 12 - "Editaccommodation"
Cohesion: 0.09
Nodes (11): EditAccommodation, EditBooking, EditDiscount, EditFerryRoute, EditSchedule, EditTour, EditTransportClass, EditUser (+3 more)

### Community 13 - "Scripts"
Cohesion: 0.08
Nodes (27): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+19 more)

### Community 14 - "Booking"
Cohesion: 0.10
Nodes (6): Booking, BelongsTo, BelongsToMany, HasMany, Collection, ReportingService

### Community 15 - "Flpluginregistry"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 16 - "Bookinglookup"
Cohesion: 0.18
Nodes (8): BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested, RebookingVerification, Mailable, Queueable, SerializesModels

### Community 17 - "Createaccommodation"
Cohesion: 0.13
Nodes (12): CreateAccommodation, CreateBooking, CreateDiscount, CreateFerryRoute, CreateInquiry, CreateSchedule, CreateTour, CreateTransportClass (+4 more)

### Community 18 - "Purgeexpiredproofs"
Cohesion: 0.20
Nodes (4): Passenger, BelongsTo, VehicleRate, Model

### Community 19 - "Aboutfact"
Cohesion: 0.10
Nodes (20): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+12 more)

### Community 20 - "Activedestinationsfor"
Cohesion: 0.14
Nodes (6): self, HasMany, Vehicle, UserFactory, Factory, static

### Community 21 - "Basetestcase"
Cohesion: 0.20
Nodes (9): BaseTestCase, CreatesApplication, RefreshDatabase, BookingLookupCancellationTest, BookingRebookingFlowTest, ExampleTest, TestCase, ExampleTest (+1 more)

### Community 22 - "Concurrently"
Cohesion: 0.11
Nodes (17): concurrently, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+9 more)

### Community 23 - "Userloginhistoryresource"
Cohesion: 0.16
Nodes (7): Form, Table, UserLoginHistoryResource, Form, Table, VehicleResource, Resource

### Community 24 - "Discount"
Cohesion: 0.19
Nodes (8): Discount, HasMany, DatabaseSeeder, DiscountSeeder, ScheduleAccommodationSeeder, VehicleRateSeeder, Seeder, WithoutModelEvents

### Community 25 - "User"
Cohesion: 0.22
Nodes (7): HasMany, Panel, User, Authenticatable, FilamentUser, HasFactory, Notifiable

### Community 26 - "Mount"
Cohesion: 0.21
Nodes (3): BookingController, Request, PaymentSetting

### Community 27 - "Form"
Cohesion: 0.18
Nodes (6): Form, ViewBooking, ViewInquiry, ViewTransaction, ViewUserLoginHistory, ViewRecord

### Community 28 - "Require"
Cohesion: 0.14
Nodes (14): require, anhskohbo/no-captcha, dompdf/dompdf, filament/filament, filament/support, intervention/image, laravel/framework, laravel/tinker (+6 more)

### Community 29 - "Dashboardsummarywidget"
Cohesion: 0.17
Nodes (4): DashboardSummaryWidget, BelongsTo, Transaction, Widget

### Community 30 - "Manifest"
Cohesion: 0.15
Nodes (12): background_color, categories, description, display, icons, name, orientation, short_name (+4 more)

### Community 31 - "Viewtransactionurl"
Cohesion: 0.21
Nodes (4): Builder, Table, TransactionResource, Infolist

### Community 32 - "Main"
Cohesion: 0.24
Nodes (9): wWinMain(), string, wchar_t, CreateAndAttachConsole(), GetCommandLineArguments(), Utf8FromUtf16(), _In_, _In_opt_ (+1 more)

### Community 33 - "Manifest"
Cohesion: 0.18
Nodes (10): background_color, description, display, icons, name, orientation, prefer_related_applications, short_name (+2 more)

### Community 36 - "Accommodationresource"
Cohesion: 0.28
Nodes (3): AccommodationResource, Form, Table

### Community 37 - "Discountresource"
Cohesion: 0.28
Nodes (3): DiscountResource, Form, Table

### Community 38 - "Ferryrouteresource"
Cohesion: 0.28
Nodes (3): FerryRouteResource, Form, Table

### Community 39 - "Inquiryresource"
Cohesion: 0.28
Nodes (3): InquiryResource, Form, Table

### Community 40 - "Scheduleresource"
Cohesion: 0.28
Nodes (3): Form, Table, ScheduleResource

### Community 41 - "Transportclassresource"
Cohesion: 0.28
Nodes (3): Form, Table, TransportClassResource

### Community 42 - "Userresource"
Cohesion: 0.28
Nodes (3): Form, Table, UserResource

### Community 43 - "Vehiclerateresource"
Cohesion: 0.28
Nodes (3): Form, Table, VehicleRateResource

### Community 44 - "Inquiry"
Cohesion: 0.33
Nodes (4): Inquiry, AdminNotificationFeed, Collection, AdminNotificationFeedTest

### Community 45 - "Bookingresource"
Cohesion: 0.29
Nodes (3): BookingResource, Form, Table

### Community 46 - "Composer"
Cohesion: 0.25
Nodes (7): description, license, minimum-stability, name, prefer-stable, $schema, type

### Community 47 - "Dev Components"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 48 - "Accommodation"
Cohesion: 0.29
Nodes (3): Accommodation, BelongsToMany, TourHotelsSeeder

### Community 49 - "Plugin"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 50 - "Build"
Cohesion: 0.29
Nodes (7): build, _goNext, _goToSchedule, _selectTransportOption, _showAirlineClassPicker, _showFerryAccommodationPicker, MaterialPageRoute

### Community 51 - "Deleteallusers"
Cohesion: 0.40
Nodes (3): DeleteAllUsers, PurgeExpiredProofs, Command

### Community 54 - "Adminmiddleware"
Cohesion: 0.60
Nodes (3): AdminMiddleware, Request, Closure

### Community 55 - "Autoload"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 56 - "Test"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 59 - "Dev Components"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 60 - "Extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 61 - "Keywords"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

## Knowledge Gaps
- **277 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+272 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **14 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `Accommodationresource`, `Discountresource`, `Ferryrouteresource`, `Inquiryresource`, `Scheduleresource`, `Action`, `Transportclassresource`, `Userresource`, `Vehiclerateresource`, `Authcontroller`, `Booking`, `Purgeexpiredproofs`, `Activedestinationsfor`, `Userloginhistoryresource`, `Discount`, `Viewtransactionurl`?**
  _High betweenness centrality (0.081) - this node is a cross-community bridge._
- **Why does `BookingForm` connect `Bookingform` to `Purgeexpiredproofs`, `Destinations`, `Tourresource`, `Bookinglookup`?**
  _High betweenness centrality (0.062) - this node is a cross-community bridge._
- **Why does `Booking` connect `Booking` to `Bookingform`, `Bookinglookup`, `Authcontroller`, `Inquiry`, `Bookinglookup`, `Purgeexpiredproofs`, `Overallreports`, `Bookingcancellation`, `Mount`, `Dashboardsummarywidget`?**
  _High betweenness centrality (0.055) - this node is a cross-community bridge._
- **Are the 20 inferred relationships involving `Booking` (e.g. with `.loadStats()` and `.getViewData()`) actually correct?**
  _`Booking` has 20 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _277 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Convert` be split into smaller, more focused modules?**
  _Cohesion score 0.010101010101010102 - nodes in this community are weakly interconnected._
- **Should `Bookingform` be split into smaller, more focused modules?**
  _Cohesion score 0.05683563748079877 - nodes in this community are weakly interconnected._