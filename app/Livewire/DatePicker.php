<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class DatePicker extends Component
{
    public string $field;
    #[Modelable]
    public ?string $value = null;
    public string $label = 'Date';
    public string $placeholder = 'Select date';
    public ?string $min = null;
    public bool $isOpen = false;
    public bool $disabled = false;
    public int $viewYear;
    public int $viewMonth;
    public array $enabledDates = [];
    public bool $hasEnabledDatesRestriction = false;

    // Note: Avoid using Livewire helper methods that may not exist in this
    // project's Livewire version (emit/dispatchBrowserEvent). This component
    // relies on `wire:model` binding to update parent properties.

    public function mount(string $field, ?string $value = null, string $label = 'Date', ?string $min = null, $disabled = false, $enabledDates = null, string $placeholder = 'Select date'): void
    {
        $this->field = $field;
        $this->value = $value;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->min = $min;
        $this->disabled = filter_var($disabled, FILTER_VALIDATE_BOOLEAN);

        $today = new \DateTimeImmutable('today');
        $this->viewYear = (int) $today->format('Y');
        $this->viewMonth = (int) $today->format('m');

        if ($this->value) {
            $selected = \DateTimeImmutable::createFromFormat('Y-m-d', $this->value);
            if ($selected) {
                $this->viewYear = (int) $selected->format('Y');
                $this->viewMonth = (int) $selected->format('m');
            }
        }

        // Normalize enabledDates if provided (can be string or array)
        if ($enabledDates !== null) {
            $this->hasEnabledDatesRestriction = true;
            if (is_string($enabledDates)) {
                $parts = preg_split('/[;,|]+/', $enabledDates);
                $this->enabledDates = array_values(array_filter(array_map('trim', $parts)));
            } elseif (is_array($enabledDates)) {
                $this->enabledDates = array_values(array_filter(array_map('trim', $enabledDates)));
            }
        }
    }

    protected $listeners = [
        'dropdownOpened' => 'onDropdownOpened',
    ];

    public function toggleCalendar(): void
    {
        $this->isOpen = ! $this->isOpen;
        if ($this->isOpen) {
            $this->dispatch('dropdownOpened', name: 'date-' . $this->field);
        }
    }

    #[\Livewire\Attributes\On('dropdownOpened')]
    public function onDropdownOpened($name = null): void
    {
        if (is_array($name) && isset($name['name'])) {
            $name = $name['name'];
        }
        // If another dropdown opened (not the datepicker for this field), close.
        if ($this->isOpen && $name !== 'date-' . $this->field) {
            $this->isOpen = false;
        }
    }

    public function prevMonth(): void
    {
        $current = new \DateTimeImmutable(sprintf('%04d-%02d-01', $this->viewYear, $this->viewMonth));
        $previous = $current->modify('-1 month');

        $this->viewYear = (int) $previous->format('Y');
        $this->viewMonth = (int) $previous->format('m');
    }

    public function nextMonth(): void
    {
        $current = new \DateTimeImmutable(sprintf('%04d-%02d-01', $this->viewYear, $this->viewMonth));
        $next = $current->modify('+1 month');

        $this->viewYear = (int) $next->format('Y');
        $this->viewMonth = (int) $next->format('m');
    }

    public function selectDate(int $day): void
    {
        $date = sprintf('%04d-%02d-%02d', $this->viewYear, $this->viewMonth, $day);

        if ($this->min !== null && $date < $this->min) {
            return;
        }

        if ($this->hasEnabledDatesRestriction && ! empty($this->enabledDates) && ! in_array($date, $this->enabledDates, true)) {
            return;
        }

        $this->value = $date;
        $this->isOpen = false;

        \Illuminate\Support\Facades\Log::info('[DatePicker] selectDate', ['field' => $this->field, 'value' => $this->value]);
        $this->dispatch('datePickerUpdated', field: $this->field, value: $this->value);
    }

    public function getCalendarDaysProperty(): array
    {
        $firstOfMonth = sprintf('%04d-%02d-01', $this->viewYear, $this->viewMonth);
        $startOffset = (int) date('w', strtotime($firstOfMonth));
        $daysInMonth = (int) date('t', strtotime($firstOfMonth));
        $minDate = $this->min ? 
            \DateTimeImmutable::createFromFormat('Y-m-d', $this->min) : null;

        $days = array_fill(0, $startOffset, null);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $this->viewYear, $this->viewMonth, $day);
            $disabled = $minDate !== null && $date < $this->min;

            // If enabledDates restriction is active, only those dates are selectable
            if ($this->hasEnabledDatesRestriction) {
                $disabled = $disabled || ! in_array($date, $this->enabledDates, true);
            }

            $days[] = [
                'day' => $day,
                'disabled' => $disabled,
            ];
        }

        while (count($days) % 7 !== 0) {
            $days[] = null;
        }

        return $days;
    }

    public function getMonthLabelProperty(): string
    {
        return date('F', mktime(0, 0, 0, $this->viewMonth, 1, $this->viewYear));
    }

    public function render()
    {
        return view('livewire.date-picker');
    }
}
