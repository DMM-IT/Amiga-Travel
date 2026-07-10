<?php

namespace App\Livewire;

use Livewire\Component;

class DatePicker extends Component
{
    public string $field;
    public ?string $value = null;
    public string $label = 'Date';
    public ?string $min = null;
    public bool $isOpen = false;
    public int $viewYear;
    public int $viewMonth;

    public function mount(string $field, ?string $value = null, string $label = 'Date', ?string $min = null): void
    {
        $this->field = $field;
        $this->value = $value;
        $this->label = $label;
        $this->min = $min;

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
    }

    public function toggleCalendar(): void
    {
        $this->isOpen = ! $this->isOpen;
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

        $this->dispatch('datePickerUpdated', $this->field, $this->value);
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
            $days[] = [
                'day' => $day,
                'disabled' => $minDate !== null && $date < $this->min,
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
