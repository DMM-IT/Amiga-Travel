<?php

namespace App\Filament\Pages;

use App\Models\Accommodation;
use App\Models\TransportClass;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\Builder;

class ManageTransportAccommodation extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Transport & Accommodation';
    protected static ?string $navigationGroup = 'Ferry & Airline';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.manage-transport-accommodation';
    
    public ?string $mode = null; // 'airline' or 'ferry'
    public bool $hasSelectedTransportType = false;
    public ?string $selectedOperator = null;
    public array $ferryOperators = ['2GO', 'Starlite'];

    public function mount(): void
    {
        $this->mode = null;
        $this->hasSelectedTransportType = false;
        $this->selectedOperator = null;
    }

    public function table(Table $table): Table
    {
        if (! $this->hasSelectedTransportType) {
            return $table
                ->query(TransportClass::query()->whereRaw('0 = 1'))
                ->columns([
                    TextColumn::make('name')->label('Name'),
                ]);
        }

        if ($this->mode === 'airline') {
            return $this->airlineTable($table);
        }

        if ($this->mode === 'ferry' && $this->selectedOperator === null) {
            return $table
                ->query(Accommodation::query()->whereRaw('0 = 1'))
                ->columns([
                    TextColumn::make('name')->label('Name'),
                ]);
        }

        return $this->ferryTable($table);
    }

    private function airlineTable(Table $table): Table
    {
        return $table
            ->query(TransportClass::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description),
                TextColumn::make('price')
                    ->money('php')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn (TransportClass $record) => route('filament.admin.resources.transport-classes.edit', $record)),
                DeleteAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('name');
    }

    private function ferryTable(Table $table): Table
    {
        return $table
            ->query(Accommodation::query()
                ->when($this->selectedOperator !== null, fn ($query) => $query->where('operator', $this->selectedOperator), fn ($query) => $query->whereRaw('0 = 1')))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('destination')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amenities')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->amenities),
                TextColumn::make('price')
                    ->money('php')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn (Accommodation $record) => route('filament.admin.resources.hotels.edit', $record)),
                DeleteAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('name');
    }

    public function switchMode(string $newMode): void
    {
        $this->mode = $newMode;

        if ($newMode === 'airline') {
            $this->hasSelectedTransportType = true;
            $this->selectedOperator = null;
        }

        if ($newMode === 'ferry') {
            $this->hasSelectedTransportType = false;
            $this->selectedOperator = null;
        }
    }

    public function updateOperator(string $operator): void
    {
        $this->selectedOperator = $operator;
        $this->hasSelectedTransportType = true;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('addAirline')
                ->label('New Class')
                ->visible($this->mode === 'airline')
                ->url(route('filament.admin.resources.transport-classes.create'))
                ->button(),
            Action::make('addFerry')
                ->label('New Class')
                ->visible($this->mode === 'ferry')
                ->url(route('filament.admin.resources.ferry-routes.create'))
                ->button(),
        ];
    }
}
