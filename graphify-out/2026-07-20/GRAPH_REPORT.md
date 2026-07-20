# Graph Report - .  (2026-07-20)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 1523 nodes · 2261 edges · 185 communities (164 shown, 21 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 85 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `df0913eb`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- main.dart
- BookingForm
- Win32Window
- RelationManager
- Seeder
- BookingLookup
- GeneratedPluginRegistrant.swift
- AuthController
- EditRecord
- State
- Model
- scripts
- Booking
- CreateRecord
- Booking.php
- Schedule
- my_application.cc
- Mailable
- ListRecords
- StatelessWidget
- ManageProofs
- devDependencies
- auth.php
- Resource
- Vehicle
- User
- Transaction
- ViewRecord
- FerryRoute.php
- require
- manifest.json
- TransactionResource.php
- wWinMain
- VehicleBrandResource.php
- Controller
- FerryRoute
- Tour
- manifest.json
- TourResource.php
- TransportClass
- AccommodationResource.php
- DiscountResource.php
- FerryRouteResource.php
- InquiryResource.php
- ScheduleResource.php
- TransportClassResource.php
- UserResource.php
- VehicleRateResource.php
- BookingController
- AdminNotificationFeedTest.php
- BookingResource
- composer.json
- require-dev
- .transportClasses
- config
- MaterialPageRoute
- Command
- OverallReports
- AdminMiddleware.php
- psr-4
- widget_test.dart
- TourController.php
- TourDate.php
- ListFerryRoutes.php
- ListSchedules.php
- ListTours
- ListTransactions.php
- ListTransportClasses.php
- ListUsers.php
- autoload-dev
- extra
- keywords
- booking-form.blade.php
- MainActivity
- ListVehicleBrands
- flutter_export_environment.sh
- manage-website-settings.blade.php
- overall-reports.blade.php
- date-picker.blade.php
- @gmail
- Collection
- String?

## God Nodes (most connected - your core abstractions)
1. `BookingForm` - 68 edges
2. `Booking` - 52 edges
3. `Schedule` - 43 edges
4. `User` - 37 edges
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
- `ScheduleController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/ScheduleController.php → app/Http/Controllers/Controller.php
- `TourController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/TourController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (185 total, 21 thin omitted)

### Community 0 - "main.dart"
Cohesion: 0.01
Nodes (197): dart:convert, dart:io, DateTime, double?, _accommodations, _activePassengerIndex, adults, availableAccommodations (+189 more)

### Community 1 - "BookingForm"
Cohesion: 0.06
Nodes (4): BookingForm, Collection, Tour, TourDate

### Community 2 - "Win32Window"
Cohesion: 0.06
Nodes (53): RegisterPlugins(), DartProject, HWND, LPARAM, LRESULT, UINT, WPARAM, FlutterWindow (+45 more)

### Community 3 - "RelationManager"
Cohesion: 0.05
Nodes (28): AccommodationsRelationManager, Form, Table, PassengersRelationManager, Form, Table, Form, Table (+20 more)

### Community 4 - "Seeder"
Cohesion: 0.06
Nodes (16): ManageWebsiteSettings, Form, VehicleBrand, VehicleModel, WebsiteSetting, BelongsTo, DatabaseSeeder, DiscountSeeder (+8 more)

### Community 5 - "BookingLookup"
Cohesion: 0.08
Nodes (6): BookingLookup, DatePicker, PaymentProof, UserDashboard, Component, WithFileUploads

### Community 6 - "GeneratedPluginRegistrant.swift"
Cohesion: 0.07
Nodes (25): Any, Cocoa, file_selector_macos, Flutter, AppDelegate, Bool, RunnerTests, RegisterGeneratedPlugins() (+17 more)

### Community 7 - "AuthController"
Cohesion: 0.11
Nodes (12): AuthController, Request, BookingExportController, AppServiceProvider, AdminPanelProvider, Panel, Color, PanelProvider (+4 more)

### Community 8 - "EditRecord"
Cohesion: 0.09
Nodes (12): EditAccommodation, EditBooking, EditDiscount, EditFerryRoute, EditSchedule, EditTour, EditTransportClass, EditUser (+4 more)

### Community 9 - "State"
Cohesion: 0.10
Nodes (31): ActivityScreen, _ActivityScreenState, BookingSubmitScreen, _BookingSubmitScreenState, ContactScreen, _ContactScreenState, DiscountScreen, _DiscountScreenState (+23 more)

### Community 10 - "Model"
Cohesion: 0.13
Nodes (10): Accommodation, BelongsToMany, Discount, HasMany, Passenger, BelongsTo, BelongsTo, ScheduleAccommodation (+2 more)

### Community 11 - "scripts"
Cohesion: 0.08
Nodes (27): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+19 more)

### Community 12 - "Booking"
Cohesion: 0.10
Nodes (5): Booking, BelongsTo, BelongsToMany, HasMany, ReportingService

### Community 13 - "CreateRecord"
Cohesion: 0.12
Nodes (13): CreateAccommodation, CreateBooking, CreateDiscount, CreateFerryRoute, CreateInquiry, CreateSchedule, CreateTour, CreateTransportClass (+5 more)

### Community 14 - "Booking.php"
Cohesion: 0.14
Nodes (8): BaseTestCase, CreatesApplication, RefreshDatabase, BookingLookupCancellationTest, ExampleTest, TestCase, ExampleTest, ReportingServiceTest

### Community 15 - "Schedule"
Cohesion: 0.12
Nodes (3): BelongsTo, Builder, Schedule

### Community 16 - "my_application.cc"
Cohesion: 0.10
Nodes (20): FlPluginRegistry, fl_register_plugins(), main(), my_application_activate(), my_application_class_init(), my_application_dispose(), my_application_init(), my_application_local_command_line() (+12 more)

### Community 17 - "Mailable"
Cohesion: 0.21
Nodes (10): BookingCancellation, self, BookingConfirmation, BookingCreated, PaymentProofReceived, RebookingRequested, RebookingVerification, Mailable (+2 more)

### Community 18 - "ListRecords"
Cohesion: 0.14
Nodes (8): ListAccommodations, ListBookings, ListDiscounts, ListInquiries, ListUserLoginHistories, ListVehicleRates, ListVehicles, ListRecords

### Community 19 - "StatelessWidget"
Cohesion: 0.10
Nodes (20): _AboutFact, AboutScreen, AppDrawer, BookingSuccessScreen, _ContactInfoCard, _CounterButton, _Field, _FormPage (+12 more)

### Community 20 - "ManageProofs"
Cohesion: 0.16
Nodes (6): Action, ManageProofs, Form, PaymentSetting, HasActions, InteractsWithActions

### Community 21 - "devDependencies"
Cohesion: 0.11
Nodes (17): concurrently, laravel-vite-plugin, devDependencies, concurrently, laravel-vite-plugin, tailwindcss, @tailwindcss/vite, vite (+9 more)

### Community 22 - "auth.php"
Cohesion: 0.20
Nodes (7): ManagePaymentSettings, Form, Collection, StaffPerformance, HasForms, InteractsWithForms, Page

### Community 23 - "Resource"
Cohesion: 0.16
Nodes (7): Form, Table, UserLoginHistoryResource, Form, Table, VehicleResource, Resource

### Community 24 - "Vehicle"
Cohesion: 0.15
Nodes (6): self, HasMany, Vehicle, UserFactory, Factory, static

### Community 25 - "User"
Cohesion: 0.18
Nodes (8): HasMany, Panel, User, Collection, Authenticatable, FilamentUser, HasFactory, Notifiable

### Community 26 - "Transaction"
Cohesion: 0.15
Nodes (6): Collection, DashboardSummaryWidget, BelongsTo, Transaction, BookingRebookingFlowTest, Widget

### Community 27 - "ViewRecord"
Cohesion: 0.18
Nodes (6): Form, ViewBooking, ViewInquiry, ViewTransaction, ViewUserLoginHistory, ViewRecord

### Community 28 - "FerryRoute.php"
Cohesion: 0.14
Nodes (3): BelongsTo, FerryRouteSeeder, ScheduleSeatingProfileTest

### Community 29 - "require"
Cohesion: 0.14
Nodes (14): require, anhskohbo/no-captcha, dompdf/dompdf, filament/filament, filament/support, intervention/image, laravel/framework, laravel/tinker (+6 more)

### Community 30 - "manifest.json"
Cohesion: 0.15
Nodes (12): background_color, categories, description, display, icons, name, orientation, short_name (+4 more)

### Community 31 - "TransactionResource.php"
Cohesion: 0.21
Nodes (4): Builder, Table, TransactionResource, Infolist

### Community 32 - "wWinMain"
Cohesion: 0.24
Nodes (9): wWinMain(), string, wchar_t, CreateAndAttachConsole(), GetCommandLineArguments(), Utf8FromUtf16(), _In_, _In_opt_ (+1 more)

### Community 33 - "VehicleBrandResource.php"
Cohesion: 0.22
Nodes (3): Form, Table, VehicleBrandResource

### Community 34 - "Controller"
Cohesion: 0.27
Nodes (4): AccommodationController, DiscountController, PromotionController, Controller

### Community 35 - "FerryRoute"
Cohesion: 0.25
Nodes (4): Request, ScheduleController, FerryRoute, HasMany

### Community 36 - "Tour"
Cohesion: 0.24
Nodes (4): TourController, HasMany, Tour, Attribute

### Community 37 - "manifest.json"
Cohesion: 0.18
Nodes (10): background_color, description, display, icons, name, orientation, prefer_related_applications, short_name (+2 more)

### Community 38 - "TourResource.php"
Cohesion: 0.27
Nodes (3): Form, Table, TourResource

### Community 39 - "TransportClass"
Cohesion: 0.24
Nodes (3): BelongsToMany, TransportClass, TransportClassSeeder

### Community 40 - "AccommodationResource.php"
Cohesion: 0.28
Nodes (3): AccommodationResource, Form, Table

### Community 41 - "DiscountResource.php"
Cohesion: 0.28
Nodes (3): DiscountResource, Form, Table

### Community 42 - "FerryRouteResource.php"
Cohesion: 0.28
Nodes (3): FerryRouteResource, Form, Table

### Community 43 - "InquiryResource.php"
Cohesion: 0.28
Nodes (3): InquiryResource, Form, Table

### Community 44 - "ScheduleResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, ScheduleResource

### Community 45 - "TransportClassResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, TransportClassResource

### Community 46 - "UserResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, UserResource

### Community 47 - "VehicleRateResource.php"
Cohesion: 0.28
Nodes (3): Form, Table, VehicleRateResource

### Community 49 - "AdminNotificationFeedTest.php"
Cohesion: 0.33
Nodes (4): Inquiry, AdminNotificationFeed, Collection, AdminNotificationFeedTest

### Community 50 - "BookingResource"
Cohesion: 0.29
Nodes (3): BookingResource, Form, Table

### Community 51 - "composer.json"
Cohesion: 0.25
Nodes (7): description, license, minimum-stability, name, prefer-stable, $schema, type

### Community 52 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 54 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 55 - "MaterialPageRoute"
Cohesion: 0.29
Nodes (7): build, _goNext, _goToSchedule, _selectTransportOption, _showAirlineClassPicker, _showFerryAccommodationPicker, MaterialPageRoute

### Community 56 - "Command"
Cohesion: 0.40
Nodes (3): DeleteAllUsers, PurgeExpiredProofs, Command

### Community 58 - "AdminMiddleware.php"
Cohesion: 0.60
Nodes (3): AdminMiddleware, Request, Closure

### Community 59 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 60 - "widget_test.dart"
Cohesion: 0.40
Nodes (4): main, package:flutter_app/main.dart, package:flutter/material.dart, package:flutter_test/flutter_test.dart

### Community 69 - "autoload-dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 70 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 71 - "keywords"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

## Knowledge Gaps
- **277 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+272 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **21 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `AuthController`, `AccommodationResource.php`, `DiscountResource.php`, `FerryRouteResource.php`, `InquiryResource.php`, `ScheduleResource.php`, `TransportClassResource.php`, `Booking.php`, `UserResource.php`, `VehicleRateResource.php`, `ManageProofs`, `auth.php`, `Resource`, `Vehicle`, `TransactionResource.php`?**
  _High betweenness centrality (0.081) - this node is a cross-community bridge._
- **Why does `Booking` connect `Booking` to `BookingLookup`, `AuthController`, `Model`, `Booking.php`, `BookingController`, `Mailable`, `AdminNotificationFeedTest.php`, `OverallReports`, `Transaction`, `User`?**
  _High betweenness centrality (0.074) - this node is a cross-community bridge._
- **Why does `BookingForm` connect `BookingForm` to `Model`, `BookingLookup`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **Are the 19 inferred relationships involving `Booking` (e.g. with `.loadStats()` and `.getViewData()`) actually correct?**
  _`Booking` has 19 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _277 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `main.dart` be split into smaller, more focused modules?**
  _Cohesion score 0.010101010101010102 - nodes in this community are weakly interconnected._
- **Should `BookingForm` be split into smaller, more focused modules?**
  _Cohesion score 0.05501165501165501 - nodes in this community are weakly interconnected._