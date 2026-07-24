# Graph Report - Amiga-Travel  (2026-07-25)

## Corpus Check
- 436 files · ~411,927 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 108 nodes · 178 edges · 12 communities (8 shown, 4 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `2b00ce70`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- ScheduleResource
- ScheduleResource.php
- booking-form.blade.php
- filament.admin.notification-scripts
- .nextStep
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

## Import Cycles
- None detected.

## Communities (12 total, 4 thin omitted)

### Community 0 - "ScheduleResource"
Cohesion: 0.19
Nodes (4): ScheduleResource, Form, Resource, Table

### Community 8 - "BookingForm.php"
Cohesion: 0.25
Nodes (5): Component, PromotionalTicket, Tour, TourDate, WithFileUploads

## Knowledge Gaps
- **3 isolated node(s):** `date-picker`, `setTripType(`, `filament.admin.notification-scripts`
  These have ≤1 connection - possible missing edges or undocumented components.
- **4 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `BookingForm` connect `ScheduleResource.php` to `.saveDraft`, `.updateReturnDateFromDuration`, `.nextStep`, `BookingForm.php`, `.calculateTotalPrice`, `.resetVehicleData`?**
  _High betweenness centrality (0.598) - this node is a cross-community bridge._
- **What connects `date-picker`, `setTripType(`, `filament.admin.notification-scripts` to the rest of the system?**
  _3 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `ScheduleResource.php` be split into smaller, more focused modules?**
  _Cohesion score 0.07407407407407407 - nodes in this community are weakly interconnected._
- **Should `.saveDraft` be split into smaller, more focused modules?**
  _Cohesion score 0.12280701754385964 - nodes in this community are weakly interconnected._