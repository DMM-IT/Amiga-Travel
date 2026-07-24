@extends('layouts.app')

@section('content')
    @livewire('booking-reschedule', ['transaction_number' => $transaction_number])
@endsection
