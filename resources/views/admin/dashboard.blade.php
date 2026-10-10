@extends('layouts.app')

@section('title', 'Admin Dashboard')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('admin.sidebar')

<div class="ml-0 min-w-0 p-4 sm:ml-64 sm:p-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="mx-auto max-w-7xl space-y-8">

        <!-- Top Header & Welcome -->
        <div class="flex min-w-0 flex-col gap-4 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#8D85EC]/15 text-[#8D85EC] dark:bg-[#8D85EC]/30">
                        Admin Overview
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Platform Control Center</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mt-1">
                    Eventify Platform Overview
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Welcome back, {{ $user->name }}. Here is what's happening across Eventify today.
                </p>
            </div>
            
            <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto sm:gap-3">
                <a href="{{ route('admin.events.index') }}" class="inline-flex min-w-0 flex-1 items-center justify-center gap-2 rounded-xl bg-[#8D85EC] px-3 py-2.5 text-center text-xs font-semibold text-white shadow-sm transition hover:bg-[#7b76e4] sm:flex-none sm:px-4 sm:text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Manage Events
                </a>
                <a href="{{ route('admin.kyc.index') }}" class="inline-flex min-w-0 flex-1 items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-center text-xs font-semibold text-gray-700 shadow-xs transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 sm:flex-none sm:px-4 sm:text-sm">
                    <svg class="w-4 h-4 text-[#8D85EC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    KYC Submissions
                    @if($pendingKyc > 0)
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold text-white bg-amber-500 rounded-full">
                            {{ $pendingKyc }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        <!-- 1. Top Statistics Summary Cards -->
        <div class="grid min-w-0 grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            
            <!-- Total Users -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Users</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $totalAccounts }}</span> total accounts
                </p>
            </div>

            <!-- Total Vendors -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Vendors</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-[#8D85EC] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalVendors) }}</div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    Organizers & Hosts
                </p>
            </div>

            <!-- Pending KYC (Prominently Highlighted) -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border {{ $pendingKyc > 0 ? 'border-amber-400 dark:border-amber-600 bg-amber-50/20 dark:bg-amber-950/20 shadow-md ring-1 ring-amber-300/50' : 'border-gray-100 dark:border-gray-700 shadow-xs' }} transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider {{ $pendingKyc > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}">Pending KYC</span>
                    <div class="w-10 h-10 rounded-xl {{ $pendingKyc > 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-300 animate-pulse' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }} flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <div class="text-2xl font-black {{ $pendingKyc > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-900 dark:text-white' }}">
                        {{ number_format($pendingKyc) }}
                    </div>
                    @if($pendingKyc > 0)
                        <span class="text-[10px] font-bold text-amber-700 dark:text-amber-300 bg-amber-200/70 dark:bg-amber-800/60 px-2 py-0.5 rounded-full">
                            Action Req.
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    {{ $approvedKyc }} approved, {{ $rejectedKyc }} rejected
                </p>
            </div>

            <!-- Total Events -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Events</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalEvents) }}</div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    {{ $upcomingEventsCount }} upcoming events
                </p>
            </div>

            <!-- Total Bookings -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Bookings</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalBookings) }}</div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    {{ number_format($totalTicketsSold) }} tickets sold
                </p>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Revenue</span>
                    <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                        <span class="font-bold text-sm">NPR</span>
                    </div>
                </div>
                <div class="text-2xl font-black text-[#8D85EC] dark:text-[#a39df0]">
                    Rs {{ number_format($totalRevenue, 2) }}
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    Verified Khalti payments
                </p>
            </div>

        </div>

        <!-- 2. Charts Section: Booking & Revenue Activity + Category Distribution -->
        <div class="grid min-w-0 grid-cols-1 gap-6 lg:grid-cols-3">
            
            <!-- Booking & Revenue Activity (2 Cols) -->
            <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6 lg:col-span-2">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Booking & Revenue Activity</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Real-time ticket booking volume and revenue generated over time</p>
                    </div>

                    <!-- Time Range Switcher Tabs -->
                    <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-700 rounded-xl text-xs font-semibold self-start sm:self-auto">
                        <button id="btn-trend-7" onclick="switchTrendRange('7')" class="px-3 py-1.5 rounded-lg bg-white dark:bg-gray-800 text-[#8D85EC] shadow-xs transition">
                            Last 7 Days
                        </button>
                        <button id="btn-trend-30" onclick="switchTrendRange('30')" class="px-3 py-1.5 rounded-lg text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">
                            Last 30 Days
                        </button>
                    </div>
                </div>

                <!-- Canvas -->
                <div class="relative h-[240px] w-full min-w-0 sm:h-[280px]">
                    <canvas id="bookingRevenueChart"></canvas>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex flex-wrap items-center justify-between text-xs text-gray-500 dark:text-gray-400 gap-2">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-[#8D85EC]"></span>
                            <span>Revenue (NPR)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span>Bookings Count</span>
                        </div>
                    </div>
                    <span>Data sourced directly from confirmed transactions</span>
                </div>
            </div>

            <!-- Events by Category Distribution (1 Col) -->
            <div class="flex min-w-0 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Events by Category</h2>
                        <span class="text-xs font-bold text-[#8D85EC] bg-[#8D85EC]/10 dark:bg-[#8D85EC]/20 px-2 py-0.5 rounded-full">
                            {{ $totalEvents }} Total
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Distribution of active & listed events by genre</p>

                    @if(count($categoryLabels) > 0)
                        <div class="relative h-[200px] w-full flex items-center justify-center">
                            <canvas id="categoryChart"></canvas>
                        </div>

                        <!-- Category Breakdown List -->
                        <div class="mt-4 space-y-2 max-h-[140px] overflow-y-auto pr-1">
                            @foreach($categoryLabels as $index => $cat)
                                @php
                                    $cnt = $categoryCounts[$index] ?? 0;
                                    $pct = $totalEvents > 0 ? round(($cnt / $totalEvents) * 100, 1) : 0;
                                @endphp
                                <div class="flex items-center justify-between text-xs py-1 px-2 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $cat }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $cnt }}</span>
                                        <span class="text-gray-400 text-[10px]">({{ $pct }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="w-12 h-12 mx-auto mb-2 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-[#8D85EC] flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <p class="font-semibold text-sm">No Event Categories Yet</p>
                            <p class="text-xs text-gray-400 mt-1">Once organizers publish events with categories, they will appear here.</p>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 text-right">
                    <a href="{{ route('admin.events.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                        Manage all events &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- 3. Middle Section: Recent Bookings + Vendor KYC Verification Overview -->
        <div class="grid min-w-0 grid-cols-1 gap-6 lg:grid-cols-3">
            
            <!-- Recent Bookings Table (2 Cols) -->
            <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Ticket Bookings</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Latest tickets purchased by customers across all events</p>
                    </div>
                    <a href="{{ route('admin.reports.admineventbooking') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline flex items-center gap-1">
                        View All Bookings &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Event</th>
                                <th class="px-4 py-3">Ticket Type</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-gray-800 dark:text-gray-200">
                            @forelse($recentBookings as $b)
                                @php
                                    $amount = $b->total_amount ?? $b->amount;
                                    $ticketTypeName = $b->ticketType ? $b->ticketType->name : 'General';
                                @endphp
                                <tr class="hover:bg-purple-50/30 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $b->user->name ?? 'Guest / Deleted' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $b->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($b->event)
                                            <a href="{{ route('admin.events.show', $b->event->id) }}" class="font-medium text-[#8D85EC] hover:underline line-clamp-1" title="{{ $b->event->event_name }}">
                                                {{ $b->event->event_name }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Event #{{ $b->event_id }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                            {{ $ticketTypeName }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold">{{ $b->tickets }}</td>
                                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white">Rs {{ number_format($amount, 2) }}</td>
                                    <td class="px-4 py-3">
                                        @if($b->payment_status === 'paid' || $b->booking_status === 'confirmed')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                                Paid
                                            </span>
                                        @elseif($b->booking_status === 'cancelled')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                                {{ ucfirst($b->payment_status ?? $b->booking_status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-[11px] text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($b->booking_date ?? $b->created_at)->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-sm text-gray-700 dark:text-gray-300">No Bookings Recorded Yet</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Once customers purchase tickets via Khalti, bookings will populate here.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Vendor KYC Overview Card (1 Col) -->
            <div class="flex min-w-0 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Vendor Verification</h2>
                        <a href="{{ route('admin.kyc.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                            Review &rarr;
                        </a>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Status of organizer identity and business KYC submissions</p>

                    <!-- KYC Status Counters -->
                    <div class="grid min-w-0 grid-cols-3 gap-2 mb-4">
                        <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200/60 dark:border-amber-800/40 text-center">
                            <span class="block text-xs font-semibold text-amber-800 dark:text-amber-300">Pending</span>
                            <span class="text-xl font-black text-amber-600 dark:text-amber-400">{{ $pendingKyc }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-green-50 dark:bg-green-950/40 border border-green-200/60 dark:border-green-800/40 text-center">
                            <span class="block text-xs font-semibold text-green-800 dark:text-green-300">Approved</span>
                            <span class="text-xl font-black text-green-600 dark:text-green-400">{{ $approvedKyc }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200/60 dark:border-rose-800/40 text-center">
                            <span class="block text-xs font-semibold text-rose-800 dark:text-rose-300">Rejected</span>
                            <span class="text-xl font-black text-rose-600 dark:text-rose-400">{{ $rejectedKyc }}</span>
                        </div>
                    </div>

                    <!-- Pending KYC Submissions List -->
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-1">
                            {{ $pendingKycsList->isNotEmpty() ? 'Awaiting Review:' : 'KYC Status Queue:' }}
                        </span>

                        @forelse($pendingKycsList as $kycItem)
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 flex items-center justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-white line-clamp-1">
                                        {{ $kycItem->business_name }}
                                    </h4>
                                    <p class="text-[10px] text-gray-400">
                                        {{ $kycItem->user->name ?? 'Vendor' }} &bull; {{ $kycItem->document_type }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.kyc.index', ['search' => $kycItem->business_name]) }}" class="px-2.5 py-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold text-[10px] whitespace-nowrap transition">
                                    Review
                                </a>
                            </div>
                        @empty
                            <div class="p-4 rounded-xl bg-green-50/50 dark:bg-green-950/20 border border-green-200/50 dark:border-green-900/30 text-center text-xs text-green-800 dark:text-green-300">
                                <span class="font-bold block">No Pending KYC Submissions!</span>
                                <span class="text-[11px] text-green-600 dark:text-green-400">All vendor verification requests have been processed.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60">
                    <a href="{{ route('admin.kyc.index') }}" class="w-full py-2 px-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold text-xs flex items-center justify-center gap-1.5 transition">
                        <span>Open KYC Verification Portal</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- 4. Section: Recently Added Events + Upcoming Events Showcase -->
        <div class="grid min-w-0 grid-cols-1 gap-6 lg:grid-cols-3">
            
            <!-- Recently Added Events (2 Cols) -->
            <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recently Added Events</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Latest events submitted by organizers on the platform</p>
                    </div>
                    <a href="{{ route('admin.events.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline flex items-center gap-1">
                        View All Events &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 font-semibold uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Event Name</th>
                                <th class="px-4 py-3">Organizer</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3">Event Date</th>
                                <th class="px-4 py-3">Seats</th>
                                <th class="px-4 py-3">Starting Price</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-gray-800 dark:text-gray-200">
                            @forelse($recentEvents as $ev)
                                <tr class="hover:bg-purple-50/30 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                        <div class="flex items-center gap-2.5">
                                            @if($ev->image)
                                                <img src="{{ asset('uploads/' . $ev->image) }}" alt="{{ $ev->event_name }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-gray-200 dark:border-gray-700" onerror="this.onerror=null; this.src='{{ asset('images/eventify-logo.png') }}';">
                                            @else
                                                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                    {{ substr($ev->event_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="line-clamp-1">{{ $ev->event_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                        {{ $ev->vendor->name ?? 'Admin / Platform' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                            {{ $ev->category ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($ev->event_date)->format('M d, Y') }}
                                        @if(\Carbon\Carbon::parse($ev->event_date)->isFuture())
                                            <span class="text-[10px] text-green-600 font-semibold ml-1">(Upcoming)</span>
                                        @else
                                            <span class="text-[10px] text-gray-400 ml-1">(Past)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-bold">
                                        {{ $ev->available_seats }}
                                    </td>
                                    <td class="px-4 py-3 font-bold text-[#8D85EC]">
                                        Rs {{ number_format($ev->price, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('admin.events.show', $ev->id) }}" class="px-2.5 py-1 rounded-lg bg-[#8D85EC]/10 hover:bg-[#8D85EC] text-[#8D85EC] hover:text-white font-semibold text-[10px] transition">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                                        <p class="font-semibold text-sm">No Events Listed Yet</p>
                                        <p class="text-xs mt-0.5">Events published by organizers will appear here.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upcoming Events Highlights (1 Col) -->
            <div class="flex min-w-0 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Upcoming Events</h2>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full">
                            {{ $upcomingEventsCount }} In Schedule
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Chronological upcoming events on Eventify</p>

                    <div class="space-y-3">
                        @forelse($upcomingEvents as $upEv)
                            <a href="{{ route('admin.events.show', $upEv->id) }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 border border-gray-100 dark:border-gray-700 transition group">
                                @if($upEv->image)
                                    <img src="{{ asset('uploads/' . $upEv->image) }}" alt="{{ $upEv->event_name }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 border border-gray-200 dark:border-gray-700 group-hover:scale-105 transition" onerror="this.onerror=null; this.src='{{ asset('images/eventify-logo.png') }}';">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center font-black text-sm flex-shrink-0">
                                        {{ substr($upEv->event_name, 0, 2) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-gray-900 dark:text-white truncate group-hover:text-[#8D85EC] transition">
                                        {{ $upEv->event_name }}
                                    </h4>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                        {{ \Carbon\Carbon::parse($upEv->event_date)->format('M d, Y') }} &bull; {{ $upEv->venue }}
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-[10px] font-semibold text-[#8D85EC]">Rs {{ number_format($upEv->price, 2) }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $upEv->available_seats }} seats left</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="py-8 text-center text-gray-400">
                                <p class="text-xs font-semibold">No upcoming events found</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 text-right">
                    <a href="{{ route('admin.events.index', ['status' => 'upcoming']) }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                        View all upcoming events &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- 5. Bottom Section: Recent Activity Log + Contact & Inquiry Overview -->
        <div class="grid min-w-0 grid-cols-1 gap-6 lg:grid-cols-2">
            
            <!-- Recent Activity Log -->
            <div class="flex min-w-0 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent System Activity</h2>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <a href="{{ route('admin.activityLogs.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                            View All Activity &rarr;
                        </a>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Audit trail of logins, bookings, KYC approvals, and event updates</p>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($recentActivities as $act)
                            @php
                                $role = $act->user ? $act->user->role : 'user';
                                $roleBadge = match($role) {
                                    'admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300',
                                    'vendor' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                    default => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                };
                            @endphp
                            <div class="py-3 flex items-start justify-between gap-3 text-xs">
                                <div class="flex items-start gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-gray-500 dark:text-gray-300 font-bold text-[10px]">
                                        {{ $act->user ? substr($act->user->name, 0, 1) : 'S' }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-900 dark:text-white truncate max-w-[140px]">
                                                {{ $act->user->name ?? 'System' }}
                                            </span>
                                            <span class="inline-flex px-1.5 py-0.2 rounded text-[9px] font-bold uppercase {{ $roleBadge }}">
                                                {{ $role }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 text-[11px] mt-0.5 line-clamp-1">
                                            {{ $act->description }}
                                        </p>
                                    </div>
                                </div>
                                <span class="text-[10px] text-gray-400 whitespace-nowrap flex-shrink-0">
                                    {{ $act->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-400 text-xs">
                                No activity recorded yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 text-right">
                    <a href="{{ route('admin.activityLogs.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                        Open complete audit log &rarr;
                    </a>
                </div>
            </div>

            <!-- Contact & Inquiry Overview -->
            <div class="flex min-w-0 flex-col justify-between rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Contact & Support Inquiries</h2>
                        <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                            View Inquiries &rarr;
                        </a>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Customer inquiries and organizer support messages</p>

                    <!-- Inquiries Metrics Cards -->
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="p-3.5 rounded-xl bg-purple-50 dark:bg-purple-950/30 border border-purple-100 dark:border-purple-800/40 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-purple-900 dark:text-purple-300 block">Unresolved / Unread</span>
                                <span class="text-2xl font-black text-[#8D85EC]">{{ $unreadInquiries }}</span>
                            </div>
                            <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-900/60 text-[#8D85EC] flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-100 dark:border-green-800/40 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-green-900 dark:text-green-300 block">Resolved</span>
                                <span class="text-2xl font-black text-green-600 dark:text-green-400">{{ $resolvedInquiries }}</span>
                            </div>
                            <div class="w-9 h-9 rounded-lg bg-green-100 dark:bg-green-900/60 text-green-600 dark:text-green-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Messages Preview -->
                    <div class="space-y-2">
                        @forelse($recentInquiries as $inq)
                            <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-700/40 border border-gray-100 dark:border-gray-700 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900 dark:text-white truncate max-w-[120px]">
                                            {{ $inq->name }}
                                        </span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded uppercase {{ $inq->status === 'unread' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' }}">
                                            {{ $inq->status }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300 text-[11px] truncate mt-0.5">
                                        {{ $inq->subject ?? $inq->message }}
                                    </p>
                                </div>
                                <span class="text-[10px] text-gray-400 whitespace-nowrap">
                                    {{ $inq->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @empty
                            <div class="py-6 text-center text-gray-400 text-xs">
                                No contact inquiries yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 text-right">
                    <a href="{{ route('admin.inquiries.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                        Open Inquiry Inbox &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Chart.js scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Time-series Data Collections
    const trend7 = {
        labels: {!! json_encode($days7Labels) !!},
        bookings: {!! json_encode($bookings7Data) !!},
        revenue: {!! json_encode($revenue7Data) !!}
    };

    const trend30 = {
        labels: {!! json_encode($days30Labels) !!},
        bookings: {!! json_encode($bookings30Data) !!},
        revenue: {!! json_encode($revenue30Data) !!}
    };

    // Initialize Booking & Revenue Chart
    const trendCtx = document.getElementById('bookingRevenueChart').getContext('2d');
    
    // Revenue Gradient Fill
    const revGradient = trendCtx.createLinearGradient(0, 0, 0, 260);
    revGradient.addColorStop(0, 'rgba(141, 133, 236, 0.4)');
    revGradient.addColorStop(1, 'rgba(141, 133, 236, 0.0)');

    let bookingRevenueChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trend7.labels,
            datasets: [
                {
                    label: 'Revenue (NPR)',
                    data: trend7.revenue,
                    borderColor: '#8D85EC',
                    backgroundColor: revGradient,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'yRevenue',
                    pointBackgroundColor: '#8D85EC',
                    pointRadius: 3.5,
                    pointHoverRadius: 6
                },
                {
                    label: 'Bookings Count',
                    data: trend7.bookings,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    borderDash: [4, 4],
                    tension: 0.3,
                    yAxisID: 'yBookings',
                    pointBackgroundColor: '#10B981',
                    pointRadius: 3,
                    pointHoverRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    padding: 12,
                    borderRadius: 10,
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label.includes('Revenue')) {
                                return 'Revenue: Rs ' + Number(context.parsed.y).toLocaleString(undefined, {minimumFractionDigits: 2});
                            }
                            return 'Bookings: ' + context.parsed.y + ' ticket order(s)';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 11 }
                    }
                },
                yRevenue: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(156, 163, 175, 0.15)'
                    },
                    ticks: {
                        font: { size: 10 },
                        callback: function(val) {
                            return 'Rs ' + val;
                        }
                    }
                },
                yBookings: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 10 },
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    // Switch between 7 days and 30 days
    function switchTrendRange(range) {
        const btn7 = document.getElementById('btn-trend-7');
        const btn30 = document.getElementById('btn-trend-30');

        if (range === '7') {
            btn7.className = 'px-3 py-1.5 rounded-lg bg-white dark:bg-gray-800 text-[#8D85EC] shadow-xs transition';
            btn30.className = 'px-3 py-1.5 rounded-lg text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition';

            bookingRevenueChart.data.labels = trend7.labels;
            bookingRevenueChart.data.datasets[0].data = trend7.revenue;
            bookingRevenueChart.data.datasets[1].data = trend7.bookings;
        } else {
            btn30.className = 'px-3 py-1.5 rounded-lg bg-white dark:bg-gray-800 text-[#8D85EC] shadow-xs transition';
            btn7.className = 'px-3 py-1.5 rounded-lg text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition';

            bookingRevenueChart.data.labels = trend30.labels;
            bookingRevenueChart.data.datasets[0].data = trend30.revenue;
            bookingRevenueChart.data.datasets[1].data = trend30.bookings;
        }
        bookingRevenueChart.update();
    }

    // 2. Events by Category Chart
    @if(count($categoryLabels) > 0)
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryLabels) !!},
                datasets: [{
                    data: {!! json_encode($categoryCounts) !!},
                    backgroundColor: [
                        '#8D85EC',
                        '#3B82F6',
                        '#10B981',
                        '#F59E0B',
                        '#EC4899',
                        '#8B5CF6',
                        '#6366F1'
                    ],
                    borderWidth: 2,
                    borderColor: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        padding: 10,
                        borderRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const val = context.parsed;
                                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ' ' + context.label + ': ' + val + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    @endif
</script>
@endsection
