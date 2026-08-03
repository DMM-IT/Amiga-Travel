# Graph Report - Amiga-Travel  (2026-08-03)

## Corpus Check
- 557 files · ~4,306,609 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 144 nodes · 258 edges · 14 communities (7 shown, 7 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3225a149`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- BookingForm
- .mount
- manage-website-settings.blade.php
- .updateAvailableScheduleDates
- .getActivePromoTicket
- booking-form.blade.php
- HomePageTest
- download.blade.php
- app.blade.php
- schedules.blade.php

## God Nodes (most connected - your core abstractions)
1. `BookingForm` - 109 edges
2. `HomePageTest` - 3 edges
3. `partials.global-skeleton` - 1 edges
4. `setActiveSection(` - 1 edges
5. `toggleEditMode` - 1 edges
6. `closePanel` - 1 edges
7. `saveSectionDirect` - 1 edges
8. `removeHeroImage({{ (int)$idx }})` - 1 edges
9. `removeSocialLink({{ $li }})` - 1 edges
10. `addSocialLink` - 1 edges

## Surprising Connections (you probably didn't know these)
- `BookingForm` --inherits--> `Component`  [EXTRACTED]
  app/Livewire/BookingForm.php →   _Bridges community 0 → community 2_
- `BookingForm` --references--> `TourDate`  [EXTRACTED]
  app/Livewire/BookingForm.php →   _Bridges community 0 → community 5_

## Import Cycles
- None detected.

## Communities (14 total, 7 thin omitted)

### Community 2 - ".mount"
Cohesion: 0.14
Nodes (5): Component, Tour, Transaction, Validator, WithFileUploads

### Community 4 - "manage-website-settings.blade.php"
Cohesion: 0.14
Nodes (13): addFaq, addQuickFact, addSocialLink, closePanel, removeFaq({{ $fi }}), removeHeroImage({{ (int)$idx }}), removeQuickFact({{ $fi }}), removeSocialLink({{ $li }}) (+5 more)

### Community 8 - "booking-form.blade.php"
Cohesion: 0.40
Nodes (4): changeSelection, confirmOperatorSelection, date-picker, setTripType(

## Knowledge Gaps
- **20 isolated node(s):** `partials.global-skeleton`, `setActiveSection(`, `toggleEditMode`, `closePanel`, `saveSectionDirect` (+15 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `BookingForm` connect `BookingForm` to `.saveDraft`, `.mount`, `.processBookingInternal`, `.updateAvailableScheduleDates`, `.updateBaggagePriceFromRates`, `.getActivePromoTicket`?**
  _High betweenness centrality (0.565) - this node is a cross-community bridge._
- **What connects `partials.global-skeleton`, `setActiveSection(`, `toggleEditMode` to the rest of the system?**
  _20 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `BookingForm` be split into smaller, more focused modules?**
  _Cohesion score 0.06190476190476191 - nodes in this community are weakly interconnected._
- **Should `.saveDraft` be split into smaller, more focused modules?**
  _Cohesion score 0.11052631578947368 - nodes in this community are weakly interconnected._
- **Should `.mount` be split into smaller, more focused modules?**
  _Cohesion score 0.13970588235294118 - nodes in this community are weakly interconnected._
- **Should `manage-website-settings.blade.php` be split into smaller, more focused modules?**
  _Cohesion score 0.14285714285714285 - nodes in this community are weakly interconnected._