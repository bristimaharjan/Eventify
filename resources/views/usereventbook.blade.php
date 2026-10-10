@extends('layouts.app')

@section('title', 'My Event Bookings')
@php 
    $noFooter = true; 
@endphp

@section('content')

<div class="max-w-7xl mx-auto mt-10 px-4 mb-16">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-3xl font-bold text-[#8d85ec]">My Event Bookings</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Review your tickets, selected ticket tiers, and payment records.</p>
            </div>
            <a href="{{ route('events') }}" class="bg-[#8d85ec] hover:bg-[#7b76e4] text-white px-5 py-2 rounded-xl text-sm font-semibold transition shadow-sm">
                + Browse More Events
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-[#8D85EC] text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Booking ID</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4">Ticket Type</th>
                        <th class="px-6 py-4 text-center">Quantity</th>
                        <th class="px-6 py-4">Price / Ticket</th>
                        <th class="px-6 py-4">Total Amount</th>
                        <th class="px-6 py-4">Booking Status</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">Booking Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                    @forelse($eventBookings as $booking)
                    @php
                        $ticketName = $booking->ticketType ? $booking->ticketType->name : 'General Admission';
                        $unitPrice = $booking->price_per_ticket ?? ($booking->ticketType ? $booking->ticketType->price : ($booking->event ? $booking->event->price : 0));
                        $total = $booking->total_amount ?? $booking->amount;
                    @endphp
                    <tr class="hover:bg-purple-50/50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 font-mono font-bold text-xs text-gray-500 dark:text-gray-400">#{{ $booking->id }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                            {{ $booking->event->event_name ?? 'Event #' . $booking->event_id }}
                        </td>
                        <td class="px-6 py-4 text-center align-middle">
                            <span class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-purple-100 px-2.5 py-1 text-center text-xs font-bold text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                {{ $ticketName }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold">{{ $booking->tickets }}</td>
                        <td class="px-6 py-4">Rs {{ number_format($unitPrice, 2) }}</td>
                        <td class="px-6 py-4 font-bold text-[#8d85ec]">Rs {{ number_format($total, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                {{ ucfirst($booking->booking_status ?? 'Confirmed') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                {{ ucfirst($booking->payment_status ?? 'Paid') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-[#8D85EC] text-3xl">
                                <iconify-icon icon="solar:ticket-bold"></iconify-icon>
                            </div>
                            <p class="font-semibold text-base">You haven't booked any event tickets yet.</p>
                            <p class="text-xs mt-1 text-gray-400">Explore upcoming concerts, workshops, and exhibitions!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection