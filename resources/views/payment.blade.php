@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
    <div class="max-w-3xl w-full bg-white rounded-3xl shadow-md p-8 space-y-8">
        <div class="text-center mb-2">
            <h1 class="text-2xl font-semibold">Payment QR Code</h1>
            <p class="text-slate-600 mt-2">Transaction <span class="font-medium">{{ $transaction->booking->transaction_number }}</span></p>
        </div>

        <div class="grid gap-6 md:grid-cols-[1fr_1fr] items-center">
            <div class="space-y-3">
                <p><strong>Origin:</strong> {{ $transaction->booking->origin }}</p>
                <p><strong>Destination:</strong> {{ $transaction->booking->destination }}</p>
                <p><strong>Departure:</strong> {{ $transaction->booking->departure_date }}</p>
                <p><strong>Return:</strong> {{ $transaction->booking->return_date ?? 'One-way' }}</p>
                @if($transaction->booking->schedule_summary)
                    <p><strong>Schedule:</strong> {{ $transaction->booking->schedule_summary }}</p>
                @endif
                <p><strong>Status:</strong> {{ ucfirst($transaction->payment_status) }}</p>
                <p class="pt-2 text-lg"><strong>Amount to pay:</strong> <span class="font-semibold" style="color:#216417;">₱{{ number_format($transaction->booking->total_price, 2) }}</span></p>
            </div>

            <div class="flex justify-center">
                @if($qrCodePath)
                    <img src="{{ asset('storage/' . $qrCodePath) }}" alt="Payment QR Code" class="h-64 w-64 rounded-2xl border border-slate-200 object-contain" />
                @else
                    <div class="h-64 w-64 rounded-2xl border border-dashed border-slate-300 flex items-center justify-center text-center text-sm text-slate-400 p-4">
                        QR code not uploaded yet. Please contact Amiga Gracia Travel Services to arrange payment.
                    </div>
                @endif
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
            <p class="text-sm text-slate-600">Scan this QR code with your GCash app (or any app that supports QR payments) and pay the exact amount shown above.</p>
            <p class="mt-3 text-sm text-slate-700">Please put this as your payment reference/note: <span class="font-semibold">{{ $transaction->booking->transaction_number }}</span></p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
            <h2 class="text-lg font-semibold text-slate-900">Proof of Payment</h2>
            <p class="mt-2 text-sm text-slate-600">Upload a screenshot or photo of your payment receipt.</p>
            @livewire('payment-proof', ['transaction' => $transaction])
        </div>
    </div>
</div>
@endsection
