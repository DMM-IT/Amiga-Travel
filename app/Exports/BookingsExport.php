<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BookingsExport implements WithMultipleSheets
{
    use Exportable;

    protected $groupedBookings;

    public function __construct(array $groupedBookings)
    {
        $this->groupedBookings = $groupedBookings;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->groupedBookings as $title => $bookings) {
            $sheets[] = new BookingsSheet($title, $bookings);
        }

        return $sheets;
    }
}
