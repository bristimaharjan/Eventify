@extends('layouts.app')

@section('title', 'Admin - Event Bookings')
@php 
$noNavbar = true;
$noFooter = true; 
@endphp

@section('content')
@include('admin.sidebar') 

<div class="max-w-7xl mx-auto mt-10 ml-0 sm:ml-72 mr-4 sm:mr-10 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-3xl font-bold text-[#8d85ec]">All Platform Event Bookings</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Platform-wide overview of event bookings, ticket tiers, and revenue.</p>
            </div>
            <a href="{{ route('admin.reports.admineventbooking.pdf', array_merge(request()->query(), [
                'from_date' => request('from_date'),
                'to_date' => request('to_date')
            ])) }}" class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-bold py-2.5 px-5 rounded-xl text-xs transition shadow-md inline-flex items-center gap-2">
                <iconify-icon icon="solar:download-bold" class="text-base"></iconify-icon> Download PDF Report
            </a>
        </div>

        <!-- Date Filter Form -->
        <form method="GET" action="{{ route('admin.reports.admineventbooking') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl flex flex-wrap items-end gap-4">
            <div>
                <label for="from_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}" class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white">
            </div>
            <div>
                <label for="to_date" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}" class="block w-full border border-gray-300 dark:border-gray-600 rounded-lg p-2 text-xs dark:bg-gray-800 dark:text-white">
            </div>
            <div class="flex gap-2">
                <button type="submit" 
                        class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white px-5 py-2 rounded-lg text-xs font-semibold transition">
                    Filter
                </button>
                <a href="{{ route('admin.reports.admineventbooking') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold transition">
                    Reset
                </a>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 mb-4">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-[#8D85EC] text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Booking ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4">Ticket Type</th>
                        <th class="px-6 py-4 text-center">Tickets</th>
                        <th class="px-6 py-4">Price / Ticket</th>
                        <th class="px-6 py-4">Total Amount</th>
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
                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $booking->user->name ?? 'User' }}</td>
                        <td class="px-6 py-4">{{ $booking->event->event_name ?? 'Event #' . $booking->event_id }}</td>
                        <td class="px-6 py-4 text-center align-middle">
                            <span class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-purple-100 px-2 py-0.5 text-center text-xs font-semibold text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                {{ $ticketName }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold">{{ $booking->tickets }}</td>
                        <td class="px-6 py-4">Rs {{ number_format($unitPrice, 2) }}</td>
                        <td class="px-6 py-4 font-bold text-green-600 dark:text-green-400">Rs {{ number_format($total, 2) }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No event bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
                <!-- Footer row for total amount -->
                <tfoot class="bg-gray-50 dark:bg-gray-700/80 font-bold">
                    <tr>
                        <td colspan="4" class="whitespace-nowrap px-6 py-4 text-right">Total Summary:</td>
                        <td colspan="2" class="whitespace-nowrap px-6 py-4 text-center text-[#8d85ec]">{{ $eventBookings->sum('tickets') }} tickets</td>
                        <td colspan="2" class="whitespace-nowrap px-6 py-4 text-right text-base text-green-600 dark:text-green-400">Rs {{ number_format($eventBookings->sum(fn($b) => $b->total_amount ?? $b->amount), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection