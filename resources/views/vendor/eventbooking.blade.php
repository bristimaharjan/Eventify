@extends('layouts.app')

@section('title', 'Event Bookings & Attendees')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar') 

<div class="max-w-7xl mx-auto mt-10 ml-0 sm:ml-72 mr-4 sm:mr-10 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-3xl font-bold text-[#8d85ec]">Event Bookings & Attendees</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage ticket sales, attendee records, and ticket tiers for your events.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('vendor.reports.eventbooking') }}" class="bg-[#8d85ec] hover:bg-[#7b76e4] text-white px-4 py-2 rounded-xl text-xs font-semibold transition shadow-sm inline-flex items-center gap-1.5">
                    <iconify-icon icon="solar:chart-square-bold" class="text-base"></iconify-icon> View Detailed Report
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-[#8D85EC] text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Booking ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4 text-center">Ticket Type</th>
                        <th class="px-6 py-4 text-center">Qty</th>
                        <th class="px-6 py-4">Price / Ticket</th>
                        <th class="px-6 py-4">Total Amount</th>
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
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $booking->user->name ?? 'Guest User' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $booking->event->event_name ?? 'Event #' . $booking->event_id }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex max-w-full items-center justify-center break-words text-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                {{ $ticketName }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold">{{ $booking->tickets }}</td>
                        <td class="px-6 py-4">Rs {{ number_format($unitPrice, 2) }}</td>
                        <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">Rs {{ number_format($total, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
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
                                <iconify-icon icon="solar:clipboard-list-bold"></iconify-icon>
                            </div>
                            <p class="font-semibold text-base">No attendee bookings found for your events yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection