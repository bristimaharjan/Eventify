@extends('layouts.app')

@section('title', 'Vendor Dashboard')
@php 
    $noNavbar = true; 
    $noFooter = true; 
    $kyc = $vendor->kyc;
    $isKycApproved = $vendor->isKycApproved();
@endphp

@section('content')
@include('vendor.sidebar')

<div class="ml-0 min-w-0 p-4 sm:ml-64 sm:p-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Top Header & Welcome -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#8D85EC]/15 text-[#8D85EC] dark:bg-[#8D85EC]/30">
                        Organizer Hub
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Vendor Management Dashboard</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mt-1">
                    Welcome back, {{ $vendor->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Track your ticket sales, manage event listings, and respond to customer inquiries.
                </p>
            </div>

            <!-- Create Event Action Button with KYC Guard -->
            <div class="flex items-center gap-3">
                <button id="theme-toggle" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-[#8D85EC]" aria-label="Toggle theme">
                    <svg id="icon-moon" class="h-5 w-5 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20" style="display: none;" aria-hidden="true">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                    <svg id="icon-sun" class="h-5 w-5 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20" style="display: none;" aria-hidden="true">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z"/>
                    </svg>
                </button>
                @if($isKycApproved)
                    <a href="{{ route('vendor.events.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#8D85EC] hover:bg-[#7b76e4] text-white text-xs sm:text-sm font-bold transition shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        + Create New Event
                    </a>
                @else
                    <a href="{{ route('vendor.kyc.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-bold hover:bg-gray-300 transition group" title="Complete KYC verification to create events">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        + Create Event (KYC Req.)
                    </a>
                @endif
            </div>
        </div>

        <!-- 1. Prominent KYC Status Widget -->
        @if(!$kyc || $kyc->isNotSubmitted())
            <div class="p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm sm:text-base text-amber-900 dark:text-amber-100">KYC Verification Required</h3>
                        <p class="text-xs sm:text-sm text-amber-700 dark:text-amber-300/90 mt-0.5">
                            Complete your KYC verification before creating and publishing events on Eventify.
                        </p>
                    </div>
                </div>
                <a href="{{ route('vendor.kyc.index') }}" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs sm:text-sm whitespace-nowrap shadow-sm transition">
                    Complete KYC &rarr;
                </a>
            </div>
        @elseif($kyc->isPending())
            <div class="p-5 rounded-2xl bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-[#8D85EC] text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm sm:text-base text-blue-900 dark:text-blue-100">KYC Under Review</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-200 text-blue-800 dark:bg-blue-800 dark:text-blue-200 uppercase">Pending Review</span>
                        </div>
                        <p class="text-xs sm:text-sm text-blue-700 dark:text-blue-300/90 mt-0.5">
                            Your KYC verification is currently under review by our team. You will be able to publish events as soon as it is approved.
                        </p>
                    </div>
                </div>
                <a href="{{ route('vendor.kyc.index') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700 font-semibold text-xs whitespace-nowrap hover:bg-blue-50 transition">
                    View Submission &rarr;
                </a>
            </div>
        @elseif($kyc->isRejected())
            <div class="p-5 rounded-2xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-rose-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm sm:text-base text-rose-900 dark:text-rose-100">KYC Verification Rejected</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-200 text-rose-800 dark:bg-rose-800 dark:text-rose-200 uppercase">Action Needed</span>
                        </div>
                        <p class="text-xs sm:text-sm text-rose-700 dark:text-rose-300/90 mt-0.5 font-medium">
                            <span class="font-bold">Reason:</span> {{ $kyc->rejection_reason ?? 'Please provide updated documents.' }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('vendor.kyc.resubmit') }}" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs sm:text-sm whitespace-nowrap shadow-sm transition">
                    Resubmit KYC &rarr;
                </a>
            </div>
        @elseif($kyc->isApproved())
            <div class="p-4 rounded-2xl bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800 text-green-900 dark:text-green-200 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-green-600 text-white flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs sm:text-sm font-bold text-green-900 dark:text-green-200">KYC Verified &bull; {{ $kyc->business_name }}</span>
                        <span class="text-xs text-green-700 dark:text-green-400 block sm:inline sm:ml-2">Your organizer account is fully approved to publish events & sell tickets.</span>
                    </div>
                </div>
                <span class="text-[11px] font-bold text-green-800 dark:text-green-300 bg-green-100 dark:bg-green-900/50 px-3 py-1 rounded-full">
                    KYC Verified ✓
                </span>
            </div>
        @endif

        <!-- 2. Vendor Summary Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- My Events -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">My Events</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-[#8D85EC] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($myEventsCount) }}</div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    {{ $pastEventsCount }} past events
                </p>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Upcoming</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($upcomingEventsCount) }}</div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    Scheduled future dates
                </p>
            </div>

            <!-- Total Bookings -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Bookings</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalVendorBookings) }}</div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    {{ number_format($totalVendorTicketsSold) }} tickets booked
                </p>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Revenue</span>
                    <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                        <span class="font-bold text-xs">NPR</span>
                    </div>
                </div>
                <div class="text-2xl font-black text-[#8D85EC] dark:text-[#a39df0]">
                    Rs {{ number_format($totalVendorRevenue, 2) }}
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    Your ticket sales earnings
                </p>
            </div>

            <!-- KYC Status -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-xs hover:border-[#8D85EC]/50 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">KYC Status</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="text-sm font-black">
                    @if(!$kyc || $kyc->isNotSubmitted())
                        <span class="text-amber-600 dark:text-amber-400">Not Submitted</span>
                    @elseif($kyc->isPending())
                        <span class="text-blue-600 dark:text-blue-400">Under Review</span>
                    @elseif($kyc->isApproved())
                        <span class="text-green-600 dark:text-green-400">Approved ✓</span>
                    @elseif($kyc->isRejected())
                        <span class="text-rose-600 dark:text-rose-400">Rejected</span>
                    @endif
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    <a href="{{ route('vendor.kyc.index') }}" class="text-[#8D85EC] hover:underline">Manage verification &rarr;</a>
                </p>
            </div>

        </div>

        <!-- 3. Vendor Performance Chart & Quick Inquiries Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Vendor 7-Day Performance Chart (2 Cols) -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Ticket Sales & Revenue Performance</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Booking frequency and daily earnings for your events over the last 7 days</p>
                    </div>
                    <span class="text-xs font-bold text-[#8D85EC] bg-[#8D85EC]/10 px-2.5 py-1 rounded-full">
                        Rs {{ number_format($totalVendorRevenue, 2) }} Earned
                    </span>
                </div>

                <div class="relative h-[240px] w-full">
                    <canvas id="vendorPerformanceChart"></canvas>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-[#8D85EC]"></span>
                            <span>Revenue (Rs)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span>Orders Count</span>
                        </div>
                    </div>
                    <a href="{{ route('vendor.reports.eventbooking') }}" class="font-semibold text-[#8D85EC] hover:underline">
                        View Event Booking Report &rarr;
                    </a>
                </div>
            </div>

            <!-- Inquiries from Customers (1 Col) -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Customer Inquiries</h2>
                        @if($unreadInquiriesCount > 0)
                            <span class="text-xs font-bold text-white bg-amber-500 px-2 py-0.5 rounded-full">
                                {{ $unreadInquiriesCount }} New
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Direct customer questions regarding your events and venues</p>

                    <div class="space-y-2.5">
                        @forelse($recentInquiries as $inq)
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-xs text-gray-900 dark:text-white">{{ $inq->name }}</span>
                                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded uppercase {{ $inq->status === 'unread' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' : 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300' }}">
                                        {{ $inq->status }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-600 dark:text-gray-300 mt-1 line-clamp-1">
                                    {{ $inq->subject ?? $inq->message }}
                                </p>
                                <div class="flex items-center justify-between mt-2 text-[10px] text-gray-400">
                                    <span>{{ $inq->event ? $inq->event->event_name : 'General' }}</span>
                                    <span>{{ $inq->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-400 text-xs">
                                <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-[#8D85EC] flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <p class="font-semibold text-gray-700 dark:text-gray-300">No Inquiries Yet</p>
                                <p class="text-gray-400 mt-0.5">When customers ask questions about your events, they will appear here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 text-right">
                    <a href="{{ route('vendor.inquiries.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                        Open Vendor Inbox &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- 4. Section: My Events Overview + Recent Bookings for Vendor's Events -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- My Events Overview -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">My Events</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">List of your created events and ticket tiers</p>
                        </div>
                        <a href="{{ route('vendor.events.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                            View All Events &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($vendorEvents as $vEvent)
                            @php
                                $totalSold = $vEvent->total_sold_tickets;
                                $totalCap = $vEvent->available_seats + $totalSold;
                                $pctSold = $totalCap > 0 ? min(100, round(($totalSold / $totalCap) * 100)) : 0;
                            @endphp
                            <div class="p-3.5 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($vEvent->image)
                                        <img src="{{ asset('uploads/' . $vEvent->image) }}" alt="{{ $vEvent->event_name }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0 border border-gray-200 dark:border-gray-700" onerror="this.onerror=null; this.src='{{ asset('images/eventify-logo.png') }}';">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8D85EC] flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ substr($vEvent->event_name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-white truncate">
                                            {{ $vEvent->event_name }}
                                        </h4>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ \Carbon\Carbon::parse($vEvent->event_date)->format('M d, Y') }} &bull; {{ $vEvent->category ?? 'General' }}
                                        </p>
                                        <!-- Progress Bar of Ticket Sales -->
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <div class="w-24 h-1.5 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                                <div class="h-full bg-[#8D85EC] rounded-full" style="width: {{ $pctSold }}%;"></div>
                                            </div>
                                            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-semibold">{{ $totalSold }} sold ({{ $vEvent->available_seats }} left)</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <a href="{{ route('vendor.events.edit', $vEvent->id) }}" class="px-2.5 py-1.5 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 hover:border-[#8D85EC] text-gray-700 dark:text-gray-200 font-semibold text-xs transition">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center text-gray-400">
                                <div class="w-12 h-12 mx-auto mb-2 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-[#8D85EC] flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <p class="font-semibold text-sm text-gray-700 dark:text-gray-300">No Events Created Yet</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    @if($isKycApproved)
                                        Start by publishing your first event to sell tickets.
                                    @else
                                        Complete your KYC verification to start creating events.
                                    @endif
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 text-right">
                    <a href="{{ route('vendor.events.index') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                        Manage all events &rarr;
                    </a>
                </div>
            </div>

            <!-- Recent Bookings for Vendor's Events -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Customer Bookings</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tickets booked strictly for your events</p>
                        </div>
                        <a href="{{ route('vendor.reports.eventbooking') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                            View All Bookings &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 font-semibold uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Customer</th>
                                    <th class="px-4 py-3">Event</th>
                                    <th class="px-4 py-3 text-center">Tickets</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-gray-800 dark:text-gray-200">
                                @forelse($recentBookings as $rb)
                                    @php
                                        $amt = $rb->total_amount ?? $rb->amount;
                                    @endphp
                                    <tr class="hover:bg-purple-50/30 dark:hover:bg-gray-700/30 transition">
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $rb->user->name ?? 'Customer' }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $rb->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-medium line-clamp-1" title="{{ $rb->event->event_name ?? '' }}">
                                                {{ $rb->event->event_name ?? 'Event #' . $rb->event_id }}
                                            </span>
                                            <span class="text-[10px] text-purple-600 dark:text-purple-400">
                                                {{ $rb->ticketType->name ?? 'General' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold">{{ $rb->tickets }}</td>
                                        <td class="px-4 py-3 font-bold text-[#8D85EC]">Rs {{ number_format($amt, 2) }}</td>
                                        <td class="px-4 py-3">
                                            @if($rb->payment_status === 'paid' || $rb->booking_status === 'confirmed')
                                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                                    Paid
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ ucfirst($rb->booking_status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-10 text-center text-gray-400">
                                            <p class="font-semibold text-sm text-gray-700 dark:text-gray-300">No Bookings for Your Events Yet</p>
                                            <p class="text-xs text-gray-400 mt-0.5">When customers book tickets for your events, they will show up here.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 text-right">
                    <a href="{{ route('vendor.reports.eventbooking') }}" class="text-xs font-semibold text-[#8D85EC] hover:underline">
                        Generate booking report PDF &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Chart.js scripts for Vendor Performance -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const vCtx = document.getElementById('vendorPerformanceChart').getContext('2d');
    
    const vGradient = vCtx.createLinearGradient(0, 0, 0, 220);
    vGradient.addColorStop(0, 'rgba(141, 133, 236, 0.45)');
    vGradient.addColorStop(1, 'rgba(141, 133, 236, 0.0)');

    new Chart(vCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($days7Labels) !!},
            datasets: [
                {
                    label: 'Revenue (NPR)',
                    data: {!! json_encode($revenue7Data) !!},
                    borderColor: '#8D85EC',
                    backgroundColor: vGradient,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'yRev',
                    pointBackgroundColor: '#8D85EC',
                    pointRadius: 3.5,
                    pointHoverRadius: 6
                },
                {
                    label: 'Orders Count',
                    data: {!! json_encode($bookings7Data) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    borderDash: [4, 4],
                    tension: 0.3,
                    yAxisID: 'yOrders',
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
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    padding: 10,
                    borderRadius: 8,
                    callbacks: {
                        label: function(context) {
                            if (context.dataset.label.includes('Revenue')) {
                                return 'Revenue: Rs ' + Number(context.parsed.y).toLocaleString(undefined, {minimumFractionDigits: 2});
                            }
                            return 'Orders: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                },
                yRev: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    grid: { color: 'rgba(156, 163, 175, 0.15)' },
                    ticks: {
                        font: { size: 10 },
                        callback: function(val) { return 'Rs ' + val; }
                    }
                },
                yOrders: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    grid: { display: false },
                    ticks: { font: { size: 10 }, precision: 0 }
                }
            }
        }
    });
</script>
@endsection
