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
    public ?string $min = null;
    public bool $isOpen = false;
    public bool $disabled = false;
    public int $viewYear;
    public int $viewMonth;
    public array $enabledDates = [];
    // Note: Avoid using Livewire helper methods that may not exist in this
    // project's Livewire version (emit/dispatchBrowserEvent). This component
    // relies on `wire:model` binding to update parent properties.

    public function mount(string $field, ?string $value = null, string $label = 'Date', ?string $min = null, $disabled = false, $enabledDates = null): void
    {
        $this->field = $field;
        $this->value = $value;
        $this->label = $label;
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
            if (is_string($enabledDates)) {
                $parts = preg_split('/[;,|]+/', $enabledDates);
                $this->enabledDates = array_values(array_filter(array_map('trim', $parts)));
            } elseif (is_array($enabledDates)) {
                $this->enabledDates = array_values(array_filter(array_map('trim', $enabledDates)));
            }
        }
    }

    public function toggleCalendar(): void
    {
        $this->isOpen = ! $this->isOpen;
        // Intentionally do not call browser event helpers here to maintain
        // compatibility with the project's Livewire version.
    }

    public function onDropdownOpened($name = null): void
    {
        // If another dropdown opened (not the datepicker for this field), close.
        if ($this->isOpen && $name !== 'date-'.$this->field) {
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

        $this->value = $date;
        $this->isOpen = false;

        \Illuminate\Support\Facades\Log::info('[DatePicker] selectDate', ['field' => $this->field, 'value' => $this->value]);
        // The parent component should be bound with `wire:model` to receive
        // the updated `$value`. Avoid emitting events from PHP here.
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

            // If enabledDates is provided, only those dates are selectable
            if (! empty($this->enabledDates)) {
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
