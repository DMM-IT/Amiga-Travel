<?php

namespace App\Livewire;

use App\Mail\BookingConfirmation;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Discount;
use App\Models\Passenger;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelPdf\Facades\Pdf;
use Livewire\Component;

class BookingForm extends Component
{
    public int $step = 1;
    public string $origin = '';
    public string $destination = '';
    public ?string $departure_date = null;
    public ?string $return_date = null;
    public int $adults = 1;
    public int $children = 0;
    public int $infants = 0;
    public ?int $selected_discount_id = null;
    public array $accommodations = [['name' => '', 'price' => '']];
    public string $client_name = '';
    public string $client_email = '';
    public string $recaptchaToken = '';
    public $discounts = [];

    public function mount(): void
    {
        $this->discounts = Discount::orderBy('name')->get();
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName, $this->allRules());
    }

    public function nextStep(): void
    {
        $this->validate($this->stepRules());

        if ($this->step < 6) {
            $this->step++;
        }
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function addAccommodation(): void
    {
        $this->accommodations[] = ['name' => '', 'price' => ''];
    }

    public function removeAccommodation(int $index): void
    {
        if (count($this->accommodations) > 1) {
            unset($this->accommodations[$index]);
            $this->accommodations = array_values($this->accommodations);
        }
    }

    public function submit()
    {
        $this->validate($this->allRules());

        Validator::make([
            'recaptchaToken' => $this->recaptchaToken,
        ], [
            'recaptchaToken' => 'required|captcha',
        ])->validate();

        $transaction = null;

        DB::transaction(function () use (&$transaction) {
            $booking = Booking::create([
                'transaction_number' => $this->generateTransactionNumber(),
                'origin' => $this->origin,
                'destination' => $this->destination,
                'departure_date' => $this->departure_date,
                'return_date' => $this->return_date,
                'client_name' => $this->client_name,
                'client_email' => $this->client_email,
                'total_price' => $this->calculateTotalPrice(),
                'status' => 'pending',
            ]);

            $discountId = $this->selected_discount_id ? intval($this->selected_discount_id) : null;

            for ($i = 0; $i < $this->adults; $i++) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'type' => 'adult',
                    'discount_id' => $discountId,
                ]);
            }

            for ($i = 0; $i < $this->children; $i++) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'type' => 'child',
                    'discount_id' => $discountId,
                ]);
            }

            for ($i = 0; $i < $this->infants; $i++) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'type' => 'infant',
                    'discount_id' => $discountId,
                ]);
            }

            foreach ($this->accommodations as $accommodation) {
                Accommodation::create([
                    'booking_id' => $booking->id,
                    'name' => $accommodation['name'],
                    'price' => $accommodation['price'],
                ]);
            }

            $transaction = Transaction::create([
                'booking_id' => $booking->id,
                'payment_status' => 'unpaid',
            ]);

            $booking->load('passengers', 'accommodations', 'transaction');

            $receiptPath = storage_path('app/receipts/receipt-' . $booking->transaction_number . '.pdf');
            if (! file_exists(dirname($receiptPath))) {
                mkdir(dirname($receiptPath), 0755, true);
            }

            Pdf::driver('dompdf')
                ->view('pdf.receipt', ['booking' => $booking])
                ->save($receiptPath);

            $ticketUrl = URL::temporarySignedRoute(
                'ticket.download',
                now()->addDays(7),
                ['booking' => $booking->id]
            );

            Mail::to($booking->client_email)->send(new BookingConfirmation($booking, $ticketUrl, $receiptPath));
        });

        return redirect()->route('payment.show', $transaction);
    }

    public function render()
    {
        return view('livewire.booking-form');
    }

    protected function stepRules(): array
    {
        return match ($this->step) {
            1 => [
                'origin' => 'required|string|max:255',
                'destination' => 'required|string|max:255',
            ],
            2 => [
                'departure_date' => 'required|date',
                'return_date' => 'nullable|date|after_or_equal:departure_date',
            ],
            3 => [
                'adults' => 'required|integer|min:1',
                'children' => 'required|integer|min:0',
                'infants' => 'required|integer|min:0',
            ],
            4 => [
                'selected_discount_id' => 'nullable|exists:discounts,id',
            ],
            5 => [
                'accommodations' => 'required|array|min:1',
                'accommodations.*.name' => 'required|string|max:255',
                'accommodations.*.price' => 'required|numeric|min:0',
            ],
            6 => [
                'client_name' => 'required|string|max:255',
                'client_email' => 'required|email',
                'recaptchaToken' => 'required|string',
            ],
            default => [],
        };
    }

    protected function allRules(): array
    {
        return [
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'infants' => 'required|integer|min:0',
            'selected_discount_id' => 'nullable|exists:discounts,id',
            'accommodations' => 'required|array|min:1',
            'accommodations.*.name' => 'required|string|max:255',
            'accommodations.*.price' => 'required|numeric|min:0',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'recaptchaToken' => 'required|string',
        ];
    }

    protected function generateTransactionNumber(): string
    {
        return 'AGT-' . now()->format('Ymd') . '-' . rand(1000, 9999);
    }

    protected function calculateTotalPrice(): float
    {
        return collect($this->accommodations)
            ->sum(fn ($item) => floatval($item['price'] ?? 0));
    }
}
