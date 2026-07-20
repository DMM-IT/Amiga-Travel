# Graph Report - .  (2026-07-21)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 1527 nodes · 2266 edges · 188 communities (169 shown, 19 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 85 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `dd517ce1`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- main.dart
- BookingForm
- Win32Window
- Controller
- Booking
- CreateRecord
- ListRecords
- GeneratedPluginRegistrant.swift
- DatePicker
- Schedule
- AuthController
- State
- scripts
- TestCase
- my_application.cc
- ManageProofs
- User
- EditRecord
- StatelessWidget
- Resource
- devDependencies
- Seeder
- BookingLookup
- Model
- ManageWebsiteSettings
- require
- Transaction
- BookingController
- FerryRoute.php
- manifest.json
- Component
- wWinMain
- TransactionResource
- TransportClass
- manifest.json
- Vehicle.php
- VehicleResource.php
- static
- PaymentSetting
- FerryRouteResource.php
- InquiryResource.php
- ScheduleResource.php
- UserResource.php
- RelationManager
- VehicleRateResource.php
- DiscountResource.php
- composer.json
- require-dev
- ScheduleAccommodation.php
- VehicleBrand.php
- config
- MaterialPageRoute
- Command
- AccommodationsRelationManager.php
- PassengersRelationManager.php
- TransportClassesRelationManager.php
- ScheduleAccommodationsRelationManager.php
- TransportClassesRelationManager.php
- DatesRelationManager.php
- VehicleModelsRelationManager.php
- ScheduleSeatingProfileTest
- OverallReports
- AdminMiddleware.php
- Accommodation
- psr-4
- widget_test.dart
- StaffPerformance
- EditFerryRoute.php
- EditTransportClass.php
- EditVehicleRate.php
- autoload-dev
- extra
- keywords
- booking-form.blade.php
- MainActivity
- flutter_export_environment.sh
- manage-website-settings.blade.php
- overall-reports.blade.php
- date-picker.blade.php
- @gmail
- Form
- Table
- Collection
- String?

## God Nodes (most connected - your core abstractions)
1. `BookingForm` - 68 edges
2. `Booking` - 52 edges
3. `Schedule` - 43 edges
4. `User` - 36 edges
5. `Transaction` - 23 edges
6. `Win32Window` - 22 edges
7. `BookingLookup` - 19 edges
8. `ManageProofs` - 18 edges
9. `TransportClass` - 18 edges
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

## Communities (188 total, 19 thin omitted)

### Community 0 - "main.dart"
Cohesion: 0.01
Nodes (197): dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex, adults, availableAccommodations (+189 more)

### Community 1 - "BookingForm"
Cohesion: 0.05
Nodes (6): BookingForm, VehicleModel, BelongsTo, Collection, Tour, TourDate

### Community 2 - "Win32Window"
Cohesion: 0.06
Nodes (53): RegisterPlugins(), DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow (+45 more)

### Community 3 - "Controller"
Cohesion: 0.07
Nodes (16): ListTours, Form, Table, TourResource, AccommodationController, DiscountController, PromotionController, Request (+8 more)

### Community 4 - "Booking"
Cohesion: 0.08
Nodes (15): BookingCancellation, self, BookingConfirmation, BookingCreated, RebookingRequested, RebookingVerification, Booking, BelongsTo (+7 more)

### Community 5 - "CreateRecord"
Cohesion: 0.07
Nodes (18): CreateAccommodation, CreateBooking, CreateDiscount, CreateInquiry, CreateSchedule, CreateTour, CreateTransportClass, Form (+10 more)

### Community 6 - "ListRecords"
Cohesion: 0.07
Nodes (14): ListAccommodations, ListBookings, ListDiscounts, ListFerryRoutes, ListInquiries, ListSchedules, ListTransactions, ListTransportClasses (+6 more)

### Community 7 - "GeneratedPluginRegistrant.swift"
Cohesion: 0.07
Nodes (25): Any, Cocoa, file_selector_macos, Flutter, AppDelegate, Bool, RunnerTests, RegisterGeneratedPlugins() (+17 more)

### Community 8 - "DatePicker"
Cohesion: 0.07
Nodes (10): Form, ViewBooking, ViewInquiry, ViewTransaction, ViewUserLoginHistory, Form, Table, UserLoginHistoryResource (+2 more)

### Community 9 - "Schedule"
Cohesion: 0.11
Nodes (5): BelongsTo, BelongsToMany, Builder, HasMany, Schedule

### Community 10 - "AuthController"
Cohesion: 0.11
Nodes (12): AuthController, Request, BookingExportController, AppServiceProvider, AdminPanelProvider, Panel, Color, PanelProvider (+4 more)

### Community 11 - "State"
Cohesion: 0.10
Nodes (31): ActivityScreen, _ActivityScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState, DiscountScreen, _DiscountScreenState (+23 more)

### Community 12 - "scripts"
Cohesion: 0.08
Nodes (27): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+19 more)

### Community 13 - "TestCase"
Cohesion: 0.12
Nodes (12): Inquiry, AdminNotificationFeed, Collection, BaseTestCase, CreatesApplication, RefreshDatabase, BookingLookupCancellationTest, ExampleTest (+4 more)

### Community 14 - "my_application.cc"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 16 - "ManageProofs"
Cohesion: 0.18
Nodes (9): Action, ManageProofs, Collection, Form, HasActions, HasForms, InteractsWithActions, InteractsWithForms (+1 more)

### Community 17 - "User"
Cohesion: 0.19
Nodes (7): HasMany, Panel, User, Authenticatable, FilamentUser, HasFactory, Notifiable

### Community 18 - "EditRecord"
Cohesion: 0.14
Nodes (8): EditAccommodation, EditBooking, EditDiscount, EditSchedule, EditTour, EditUser, EditVehicleBrand, EditRecord

### Community 19 - "StatelessWidget"
Cohesion: 0.10
Nodes (20): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+12 more)

### Community 20 - "Resource"
Cohesion: 0.15
Nodes (7): AccommodationResource, Form, Table, BookingResource, Form, Table, Resource

### Community 21 - "devDependencies"
Cohesion: 0.11
Nodes (17): concurrently, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+9 more)

### Community 22 - "Seeder"
Cohesion: 0.17
Nodes (7): DatabaseSeeder, DiscountSeeder, TourHotelsSeeder, VehicleRateSeeder, WebsiteSettingSeeder, Seeder, WithoutModelEvents

### Community 24 - "Model"
Cohesion: 0.20
Nodes (8): Passenger, BelongsTo, BelongsTo, TourDate, BelongsTo, UserLoginHistory, VehicleRate, Model

### Community 25 - "ManageWebsiteSettings"
Cohesion: 0.19
Nodes (3): ManageWebsiteSettings, Form, WebsiteSetting

### Community 26 - "require"
Cohesion: 0.14
Nodes (14): require, anhskohbo/no-captcha, dompdf/dompdf, filament/filament, filament/support, intervention/image, laravel/framework, laravel/tinker (+6 more)

### Community 27 - "Transaction"
Cohesion: 0.16
Nodes (6): DashboardSummaryWidget, PaymentProofReceived, BelongsTo, Transaction, BookingRebookingFlowTest, Widget

### Community 28 - "BookingController"
Cohesion: 0.22
Nodes (4): BookingController, Request, Discount, HasMany

### Community 29 - "FerryRoute.php"
Cohesion: 0.19
Nodes (4): FerryRoute, BelongsTo, HasMany, FerryRouteSeeder

### Community 30 - "manifest.json"
Cohesion: 0.15
Nodes (12): background_color, categories, description, display, icons, name, orientation, short_name (+4 more)

### Community 31 - "Component"
Cohesion: 0.21
Nodes (4): PaymentProof, UserDashboard, Component, WithFileUploads

### Community 32 - "wWinMain"
Cohesion: 0.24
Nodes (9): wWinMain(), string, wchar_t, CreateAndAttachConsole(), GetCommandLineArguments(), Utf8FromUtf16(), _In_, _In_opt_ (+1 more)

### Community 33 - "TransactionResource"
Cohesion: 0.18
Nodes (4): Builder, Table, TransactionResource, Infolist

### Community 34 - "TransportClass"
Cohesion: 0.24
Nodes (3): BelongsToMany, TransportClass, TransportClassSeeder

### Community 35 - "manifest.json"
Cohesion: 0.18
Nodes (10): background_color, description, display, icons, name, orientation, prefer_related_applications, short_name (+2 more)

### Community 36 - "Vehicle.php"
Cohesion: 0.22
Nodes (3): CreateFerryRoute, HasMany, Vehicle

### Community 37 - "VehicleResource.php"
Cohesion: 0.24
Nodes (4): EditVehicle, Form, Table, VehicleResource

### Community 38 - "static"
Cohesion: 0.24
Nodes (4): self, UserFactory, Factory, static

### Community 39 - "PaymentSetting"
Cohesion: 0.25
Nodes (3): ManagePaymentSettings, Form, PaymentSetting

### Community 40 - "FerryRouteResource.php"
Cohesion: 0.28
Nodes (3): FerryRouteResource, Form, Table

### Community 41 - "InquiryResource.php"
Cohesion: 0.28
Nodes (3): InquiryResource, Form, Table

### Community 42 - "ScheduleResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, ScheduleResource

### Community 43 - "UserResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, UserResource

### Community 44 - "RelationManager"
Cohesion: 0.33
Nodes (5): BookingsRelationManager, Table, LoginHistoriesRelationManager, Table, RelationManager

### Community 45 - "VehicleRateResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, VehicleRateResource

### Community 46 - "DiscountResource.php"
Cohesion: 0.28
Nodes (3): DiscountResource, Form, Table

### Community 47 - "composer.json"
Cohesion: 0.25
Nodes (7): description, license, minimum-stability, name, prefer-stable, $schema, type

### Community 48 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 49 - "ScheduleAccommodation.php"
Cohesion: 0.38
Nodes (3): BelongsTo, ScheduleAccommodation, ScheduleAccommodationSeeder

### Community 50 - "VehicleBrand.php"
Cohesion: 0.38
Nodes (3): VehicleBrand, VehicleBrandModelSeeder, HasMany

### Community 51 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 52 - "MaterialPageRoute"
Cohesion: 0.29
Nodes (7): build, _goNext, _goToSchedule, _selectTransportOption, _showAirlineClassPicker, _showFerryAccommodationPicker, MaterialPageRoute

### Community 53 - "Command"
Cohesion: 0.40
Nodes (3): DeleteAllUsers, PurgeExpiredProofs, Command

### Community 54 - "AccommodationsRelationManager.php"
Cohesion: 0.47
Nodes (3): AccommodationsRelationManager, Form, Table

### Community 55 - "PassengersRelationManager.php"
Cohesion: 0.47
Nodes (3): PassengersRelationManager, Form, Table

### Community 56 - "TransportClassesRelationManager.php"
Cohesion: 0.47
Nodes (3): Form, Table, TransportClassesRelationManager

### Community 57 - "ScheduleAccommodationsRelationManager.php"
Cohesion: 0.47
Nodes (3): Form, Table, ScheduleAccommodationsRelationManager

### Community 58 - "TransportClassesRelationManager.php"
Cohesion: 0.47
Nodes (3): Form, Table, TransportClassesRelationManager

### Community 59 - "DatesRelationManager.php"
Cohesion: 0.47
Nodes (3): DatesRelationManager, Form, Table

### Community 60 - "VehicleModelsRelationManager.php"
Cohesion: 0.47
Nodes (3): Form, Table, VehicleModelsRelationManager

### Community 63 - "AdminMiddleware.php"
Cohesion: 0.60
Nodes (3): AdminMiddleware, Request, Closure

### Community 65 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 66 - "widget_test.dart"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 71 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 72 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 73 - "keywords"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

## Knowledge Gaps
- **277 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+272 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **19 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `Booking`, `CreateRecord`, `VehicleResource.php`, `static`, `DatePicker`, `InquiryResource.php`, `ScheduleResource.php`, `UserResource.php`, `AuthController`, `VehicleRateResource.php`, `DiscountResource.php`, `Booking.php`, `ManageProofs`, `Resource`?**
  _High betweenness centrality (0.078) - this node is a cross-community bridge._
- **Why does `BookingForm` connect `BookingForm` to `Component`, `Booking.php`?**
  _High betweenness centrality (0.060) - this node is a cross-community bridge._
- **Why does `Booking` connect `Booking` to `AuthController`, `TestCase`, `Booking.php`, `BookingLookup`, `Model`, `Transaction`, `BookingController`, `OverallReports`, `Component`?**
  _High betweenness centrality (0.050) - this node is a cross-community bridge._
- **Are the 19 inferred relationships involving `Booking` (e.g. with `.loadStats()` and `.getViewData()`) actually correct?**
  _`Booking` has 19 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _277 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `main.dart` be split into smaller, more focused modules?**
  _Cohesion score 0.010101010101010102 - nodes in this community are weakly interconnected._
- **Should `BookingForm` be split into smaller, more focused modules?**
  _Cohesion score 0.05134575569358178 - nodes in this community are weakly interconnected._