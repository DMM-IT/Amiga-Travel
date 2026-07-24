# Graph Report - Amiga-Travel  (2026-07-25)

## Corpus Check
- 436 files · ~412,069 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 20 nodes · 20 edges · 5 communities (2 shown, 3 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `9cb61cd7`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- ScheduleResource
- ScheduleResource.php
- booking-form.blade.php
- filament.admin.notification-scripts

## God Nodes (most connected - your core abstractions)
1. `ScheduleResource` - 11 edges
2. `filament.admin.notification-scripts` - 1 edges
3. `date-picker` - 1 edges
4. `setTripType(` - 1 edges

## Surprising Connections (you probably didn't know these)
- `ScheduleResource` --inherits--> `Resource`  [EXTRACTED]
  app/Filament/Resources/ScheduleResource.php →   _Bridges community 0 → community 1_

## Import Cycles
- None detected.

## Communities (5 total, 3 thin omitted)

### Community 1 - "ScheduleResource.php"
Cohesion: 0.40
Nodes (3): Form, Resource, Table

## Knowledge Gaps
- **3 isolated node(s):** `filament.admin.notification-scripts`, `date-picker`, `setTripType(`
  These have ≤1 connection - possible missing edges or undocumented components.
- **3 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `ScheduleResource` connect `ScheduleResource` to `ScheduleResource.php`?**
  _High betweenness centrality (0.386) - this node is a cross-community bridge._
- **What connects `filament.admin.notification-scripts`, `date-picker`, `setTripType(` to the rest of the system?**
  _3 weakly-connected nodes found - possible documentation gaps or missing edges._