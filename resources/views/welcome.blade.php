@extends('layouts.app')

@section('title', 'Eventify')

@section('content')
<style>
    .hero-slide {
        width: 88vw;
        min-height: 210px;
    }

    @media (min-width: 640px) {
        .hero-slide {
            width: 78vw;
            min-height: 280px;
        }
    }

    @media (min-width: 1024px) {
        .hero-slide {
            width: 72vw;
            min-height: 340px;
        }
    }

    @media (min-width: 1280px) {
        .hero-slide {
            width: 70vw;
        }
    }

    #hero-dots .hero-dot {
        display: block;
        width: 6px;
        height: 6px;
        flex: 0 0 6px;
        border-radius: 9999px;
        background: #d1d5db;
    }

    #hero-dots .hero-dot:first-child {
        width: 24px;
        flex-basis: 24px;
        background: #6c5ce7;
    }

    #hero-prev,
    #hero-next,
    #upcoming-events-prev,
    #upcoming-events-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        background: rgba(17, 24, 39, 0.78);
        color: white;
        border-radius: 9999px;
        z-index: 40;
    }

    #hero-prev:hover,
    #hero-next:hover,
    #upcoming-events-prev:hover,
    #upcoming-events-next:hover {
        transform: translateY(-50%) scale(1.05);
    }

    #events-grid .event-card {
        width: calc((100% - 3.75rem) / 4);
    }

    .hero-info {
        position: absolute;
        left: 20px;
        right: 80px;
        bottom: 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    @media (min-width: 640px) {
        .hero-info {
            left: 32px;
            bottom: 28px;
        }
    }

    @media (min-width: 1024px) {
        .hero-info {
            left: 40px;
            bottom: 32px;
        }
    }

    .hero-info .hero-category,
    .hero-info .hero-title {
        display: block;
        margin: 0;
    }

    .hero-info .hero-category {
        line-height: 1.25;
    }

    .hero-info .hero-title {
        line-height: 1.15;
    }

    @media (max-width: 1023px) {
        #events-grid .event-card {
            width: calc((100% - 1.25rem) / 2);
        }
    }

    @media (max-width: 639px) {
        #events-grid .event-card {
            width: 85vw;
        }
    }
</style>
<div class="bg-[#faf9ff] dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen font-sans selection:bg-[#6C5CE7] selection:text-white">
<!-- ========================================== -->
<!-- 1. HERO SECTION — WIDE EVENT CAROUSEL     -->
<!-- ========================================== -->
<section class="relative pt-4 pb-10 md:pt-6 md:pb-12 overflow-hidden bg-[#faf9ff] dark:bg-gray-950">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-5 text-left">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">
            Hot and Happening Events
        </h1>
        <p class="mt-1 text-sm sm:text-base text-gray-500 dark:text-gray-400">
            Discover the experiences everyone is talking about and find your next unforgettable outing.
        </p>
    </div>

    @php
        $today = \Carbon\Carbon::today();

        $heroEvents = $upcomingEvents
            ->filter(function ($event) use ($today) {
                return $event->event_date &&
                    \Carbon\Carbon::parse($event->event_date)
                        ->startOfDay()
                        ->gte($today);
            })
            ->sortBy(function ($event) {
                return \Carbon\Carbon::parse($event->event_date)->timestamp;
            })
            ->take(6)
            ->values();
    @endphp

    @if($heroEvents->isNotEmpty())

        <div class="relative w-full">

            <!-- Previous Button -->
            <button
                type="button"
                id="hero-prev"
                aria-label="Previous event"
                class="absolute left-3 sm:left-5 lg:left-8 top-1/2 -translate-y-1/2 z-30
                       w-10 h-10 sm:w-12 sm:h-12
                       rounded-full bg-black/45 hover:bg-black/65
                       backdrop-blur-sm text-white
                       flex items-center justify-center
                       transition-all duration-200
                       hover:scale-105 shadow-lg"
            >
                <svg
                    class="w-5 h-5 sm:w-6 sm:h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
            </button>

            <!-- Next Button -->
            <button
                type="button"
                id="hero-next"
                aria-label="Next event"
                class="absolute right-3 sm:right-5 lg:right-8 top-1/2 -translate-y-1/2 z-30
                       w-10 h-10 sm:w-12 sm:h-12
                       rounded-full bg-black/45 hover:bg-black/65
                       backdrop-blur-sm text-white
                       flex items-center justify-center
                       transition-all duration-200
                       hover:scale-105 shadow-lg"
            >
                <svg
                    class="w-5 h-5 sm:w-6 sm:h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>
            </button>

            <!-- Banner Carousel -->
            <div
                id="hero-carousel"
                class="flex items-center gap-3 sm:gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-hide px-0"
                style="
                    scroll-behavior: smooth;
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                "
            >

                @foreach($heroEvents as $index => $event)

                    @php
                        $heroDate = \Carbon\Carbon::parse($event->event_date);

                        $heroImage = $event->image
                            ? (
                                file_exists(public_path('uploads/' . $event->image))
                                    ? asset('uploads/' . $event->image)
                                    : asset('uploads/concert.jpg')
                            )
                            : asset('uploads/concert.jpg');

                        $isToday = $heroDate->isToday();
                        $isTomorrow = $heroDate->isTomorrow();
                        $heroCategory = $event->category ?? 'Event';

                        $heroIsSaved = in_array(
                            $event->id,
                            $savedEventIds ?? []
                        );
                    @endphp

                    <article
                        class="hero-slide relative shrink-0
                               w-[88vw] sm:w-[78vw] lg:w-[72vw] xl:w-[70vw]
                               max-w-[1400px]
                               aspect-[2.6/1]
                               min-h-[210px] sm:min-h-[280px] lg:min-h-[340px]
                               snap-center overflow-hidden
                               rounded-xl sm:rounded-2xl
                               cursor-pointer group"
                        data-index="{{ $index }}"
                        onclick="window.location.href='{{ route('events.show', $event->slug ?: $event->id) }}'"
                    >

                        <!-- Event Image -->
                        <img
                            src="{{ $heroImage }}"
                            alt="{{ $event->event_name }}"
                            class="absolute inset-0 w-full h-full object-cover
                                   transition-transform duration-700
                                   group-hover:scale-[1.02]"
                        >

                        <!-- Gradients -->
                        <div class="absolute inset-0 bg-gradient-to-r from-black/65 via-black/15 to-transparent"></div>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-black/10"></div>

                        <!-- Date Badge -->
                        <div class="absolute top-4 left-4 sm:top-6 sm:left-6 z-10">

                            @if($isToday)

                                <span class="inline-flex items-center gap-1.5
                                             px-3 py-1.5 rounded-md
                                             bg-red-500 text-white
                                             text-[10px] sm:text-xs
                                             font-bold uppercase tracking-wide
                                             shadow-lg">

                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>

                                    Happening Today
                                </span>

                            @elseif($isTomorrow)

                                <span class="inline-flex items-center
                                             px-3 py-1.5 rounded-md
                                             bg-[#6C5CE7] text-white
                                             text-[10px] sm:text-xs
                                             font-bold uppercase tracking-wide
                                             shadow-lg">

                                    Tomorrow

                                </span>

                            @else

                                <span class="inline-flex items-center
                                             px-3 py-1.5 rounded-md
                                             bg-white/95 text-gray-900
                                             text-[10px] sm:text-xs
                                             font-bold shadow-lg">

                                    {{ $heroDate->format('D, M j') }}

                                </span>

                            @endif

                        </div>

                        <!-- Save Button -->
                        <button
                            type="button"
                            onclick="event.stopPropagation(); toggleSaveEvent(event, {{ $event->id }}, this)"
                            data-save-event-id="{{ $event->id }}"
                            aria-label="{{ $heroIsSaved ? 'Remove from saved events' : 'Save this event' }}"
                            class="save-event-btn
                                   absolute top-4 right-4 sm:top-6 sm:right-6
                                   z-20
                                   w-9 h-9 sm:w-10 sm:h-10
                                   rounded-full
                                   bg-white/90 hover:bg-white
                                   backdrop-blur-sm
                                   flex items-center justify-center
                                   shadow-md
                                   transition-all duration-200
                                   hover:scale-110 active:scale-90
                                   {{ $heroIsSaved
                                        ? 'text-rose-500'
                                        : 'text-gray-700 hover:text-rose-500'
                                   }}"
                        >
                            <svg
                                class="w-4 h-4 sm:w-5 sm:h-5"
                                fill="{{ $heroIsSaved ? 'currentColor' : 'none' }}"
                                stroke="currentColor"
                                stroke-width="{{ $heroIsSaved ? '0' : '1.8' }}"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364l-1.318 1.318-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                />
                            </svg>
                        </button>

                        <!-- Event Information -->
                        <div
                            class="absolute left-5 bottom-5
                                   sm:left-8 sm:bottom-7
                                   lg:left-10 lg:bottom-8
                                   right-20 text-white
                                   hero-info"
                        >

                            <p class="hero-category text-[10px] sm:text-xs
                                      font-bold uppercase tracking-[0.15em]
                                      text-white/80">
                                {{ $heroCategory }}
                            </p>

                            <h2
                                class="hero-title text-xl sm:text-2xl lg:text-4xl
                                       font-extrabold
                                       tracking-tight line-clamp-2
                                       drop-shadow-lg max-w-full"
                            >
                                {{ $event->event_name }}
                            </h2>

                            <div
                                class="flex flex-wrap items-center
                                       gap-x-4 gap-y-1 mt-3
                                       text-[10px] sm:text-xs lg:text-sm
                                       text-white/90"
                            >

                                <span class="inline-flex items-center gap-1.5">
                                    <iconify-icon
                                        icon="solar:map-point-linear"
                                        class="text-sm sm:text-base"
                                    ></iconify-icon>

                                    <span class="truncate max-w-[180px] sm:max-w-[280px]">
                                        {{ $event->venue }}
                                    </span>
                                </span>

                                <span class="hidden sm:inline text-white/50">
                                    •
                                </span>

                                <span class="inline-flex items-center gap-1.5">
                                    <iconify-icon
                                        icon="solar:calendar-linear"
                                        class="text-sm sm:text-base"
                                    ></iconify-icon>

                                    {{ $heroDate->format('M j, Y') }}
                                </span>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

            <!-- Dots -->
            @if($heroEvents->count() > 1)

                <div
                    id="hero-dots"
                    class="flex items-center justify-center gap-1.5 mt-4"
                >

                    @foreach($heroEvents as $index => $event)

                        <button
                            type="button"
                            data-dot="{{ $index }}"
                            aria-label="Go to event {{ $index + 1 }}"
                                    class="hero-dot transition-all duration-300"
                        ></button>

                    @endforeach

                </div>

            @endif

        </div>

    @else

        <!-- Empty State -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div
                class="rounded-2xl bg-white dark:bg-gray-900
                       border border-gray-200 dark:border-gray-800
                       py-16 text-center"
            >

                <div
                    class="w-14 h-14 mx-auto rounded-full
                           bg-purple-100 dark:bg-purple-900/30
                           text-[#6C5CE7]
                           flex items-center justify-center mb-4"
                >
                    <iconify-icon
                        icon="solar:calendar-linear"
                        class="text-2xl"
                    ></iconify-icon>
                </div>

                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    No upcoming events yet
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Check back soon for new experiences.
                </p>

            </div>

        </div>

    @endif

</section>


    <!-- ========================================== -->
    <!-- 2. UPCOMING EVENTS SECTION (DYNAMIC DB)   -->
    <!-- ========================================== -->
    <section class="py-16 bg-white dark:bg-gray-900 border-t border-b border-gray-100 dark:border-gray-800" id="upcoming-events-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-left mb-6">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Upcoming Events</h2>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">Hand-picked live experiences happening this week and beyond</p>
            </div>

            @php
                $upcomingDisplayEvents = $upcomingEvents->values()->take(8);
                $upcomingCategories = $upcomingDisplayEvents
                    ->pluck('category')
                    ->filter()
                    ->unique()
                    ->values();
            @endphp

            <!-- Category Filter Pills (Interactive JS Filtering) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-4 scrollbar-hide text-sm font-medium" id="event-filters">
                <button type="button" onclick="filterEvents('all', this)" class="event-filter-btn px-5 py-2 rounded-full bg-[#6C5CE7] text-white shadow-sm transition whitespace-nowrap">
                    All events
                </button>
                @foreach($upcomingCategories as $category)
                    <button type="button" onclick="filterEvents(@js($category), this)" class="event-filter-btn px-5 py-2 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-gray-400 dark:hover:border-gray-500 transition whitespace-nowrap">
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            <!-- Dynamic Event Cards Carousel (show up to 8 events) -->
            <div class="relative mt-6">
                <button
                    type="button"
                    id="upcoming-events-prev"
                    aria-label="Previous upcoming events"
                    class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/65 hover:bg-black/80 text-white flex items-center justify-center shadow-lg transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    type="button"
                    id="upcoming-events-next"
                    aria-label="Next upcoming events"
                    class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/65 hover:bg-black/80 text-white flex items-center justify-center shadow-lg transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="flex gap-5 overflow-x-auto snap-x snap-mandatory scrollbar-hide pb-3 px-1" id="events-grid" data-max-events="8">
                @forelse($upcomingDisplayEvents as $event)
                    @php
                        $catColor = match($event->category) {
                            'Concert' => 'bg-purple-50 dark:bg-purple-900/40 text-[#6C5CE7] dark:text-purple-300',
                            'Comedy' => 'bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300',
                            'Sports' => 'bg-teal-50 dark:bg-teal-900/40 text-teal-600 dark:text-teal-300',
                            'Theatre' => 'bg-rose-50 dark:bg-rose-900/40 text-rose-600 dark:text-rose-300',
                            'Festival' => 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300',
                            default => 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300'
                        };
                        $eventDate = \Carbon\Carbon::parse($event->event_date);
                        $imagePath = $event->image ? (file_exists(public_path('uploads/' . $event->image)) ? asset('uploads/' . $event->image) : asset('uploads/concert.jpg')) : asset('uploads/concert.jpg');
                        $minPrice = $event->min_price ?? $event->price;
                    @endphp

                    <div onclick="window.location.href='{{ route('events.show', $event->slug ?: $event->id) }}'" class="cursor-pointer event-card shrink-0 snap-start bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/80 dark:border-gray-700/80 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group" data-category="{{ $event->category }}">
                        
                        <!-- Event Image -->
                        <div class="relative h-52 sm:h-56 overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <a href="{{ route('events.show', $event->slug ?: $event->id) }}">
                                <img src="{{ $imagePath }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </a>
                            
                            <!-- Date Badge -->
                            <div class="absolute top-3.5 left-3.5 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm rounded-xl px-3 py-1.5 text-center shadow-md border border-gray-100 dark:border-gray-700">
                                <span class="block text-xs font-bold text-gray-900 dark:text-white uppercase leading-none">{{ $eventDate->format('d') }}</span>
                                <span class="block text-[10px] font-semibold text-[#6C5CE7] uppercase leading-none mt-0.5">{{ $eventDate->format('M') }}</span>
                            </div>

                            <!-- Save / Favorite Button -->
                            @php
                                $isSaved = in_array($event->id, $savedEventIds ?? []);
                            @endphp
                            <button 
                                type="button"
                                onclick="toggleSaveEvent(event, {{ $event->id }}, this)"
                                data-save-event-id="{{ $event->id }}"
                                aria-label="{{ $isSaved ? 'Remove from saved events' : 'Save this event' }}"
                                title="{{ $isSaved ? 'Saved to favorites' : 'Save to favorites' }}"
                                class="save-event-btn absolute top-3.5 right-3.5 w-9 h-9 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 active:scale-90 z-20 group/btn {{ $isSaved ? 'text-rose-500' : 'text-gray-600 dark:text-gray-300 hover:text-rose-500' }}"
                            >
                                <svg class="w-4 h-4 transition-transform duration-200" 
                                     fill="{{ $isSaved ? 'currentColor' : 'none' }}" 
                                     stroke="currentColor" 
                                     stroke-width="{{ $isSaved ? '0' : '2' }}" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Event Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2 text-left">
                                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $catColor }}">
                                    • {{ $event->category ?? 'General' }}
                                </span>
                                <a href="{{ route('events.show', $event->slug ?: $event->id) }}" class="block">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition line-clamp-1">
                                        {{ $event->event_name }}
                                    </h3>
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5 line-clamp-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $event->venue }}</span>
                                </p>
                            </div>

                            <!-- Footer Price & Booking -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div>
                                    <span class="text-[11px] text-gray-400 block leading-tight">From</span>
                                    <span class="text-base font-extrabold text-gray-900 dark:text-white">Rs. {{ number_format($minPrice) }}</span>
                                </div>
                                <a href="{{ route('events.show', $event->slug ?: $event->id) }}" class="px-5 py-2 rounded-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-bold hover:bg-black dark:hover:bg-gray-100 transition shadow-sm">
                                    Book now
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full py-12 text-center text-gray-500 dark:text-gray-400">
                        No events found in the database.
                    </div>
                @endforelse
                </div>
            </div>

            <!-- View all events CTA -->
            <div class="mt-10 text-center">
                <a href="{{ route('events') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-semibold transition">
                    <span>Explore All Live Events</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 3. SELL OUT YOUR NEXT EVENT IN THREE STEPS -->
    <!-- ========================================== -->
    <section class="py-16 md:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Row -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
            <div class="space-y-4 max-w-xl text-left">
                <span class="inline-block text-xs font-bold uppercase tracking-wider text-[#6C5CE7] bg-[#EDE9FE] dark:bg-purple-900/50 px-3 py-1 rounded-full">
                    • FOR ORGANIZERS
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-tight">
                    Sell out your next<br/>event in three steps
                </h2>
                <div>
                    <a href="{{ route('register') }}" class="inline-block bg-[#6C5CE7] hover:bg-[#5b48db] text-white font-semibold text-sm px-7 py-3 rounded-full shadow-md shadow-[#6C5CE7]/30 hover:shadow-lg transition">
                        Start selling now
                    </a>
                </div>
            </div>
            
            <div class="lg:max-w-md text-left">
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base leading-relaxed">
                    From local gigs to stadium tours, Eventify gives you the tools to create, manage, and scale your ticket sales with ease.
                </p>
            </div>
        </div>

        <!-- 3 Step Cards Grid (Responsive Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            
            <!-- Step 1 Card -->
            <div class="bg-gradient-to-b from-[#F5F3FF] via-white to-white dark:from-gray-800/80 dark:via-gray-800 dark:to-gray-800 rounded-3xl p-6 sm:p-7 border border-[#E9D5FF]/60 dark:border-gray-700 shadow-sm flex flex-col justify-between overflow-hidden relative group hover:shadow-xl hover:border-[#6C5CE7]/40 transition-all duration-300">
                <div class="space-y-4 text-left">
                    <div class="w-10 h-10 rounded-full bg-[#EDE9FE] dark:bg-purple-900/60 text-[#6C5CE7] dark:text-purple-300 font-bold flex items-center justify-center text-base">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Create your vendor account</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Sign up in 2 minutes, verify your identity if you represent an organization or solo artist and you are ready to go.
                    </p>
                </div>
                
                <!-- Phone Mockup 1 -->
                <div class="mt-8 pt-4 flex justify-center -mb-10 group-hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-56 sm:w-60 rounded-t-3xl overflow-hidden shadow-2xl bg-white">
                        <img src="{{ asset('uploads/a0e119fe69f66a788f1ab7633889c4b94d308eaf.png') }}" alt="Step 1 Vendor Mockup" class="w-full object-cover object-top" />
                    </div>
                </div>
            </div>

            <!-- Step 2 Card -->
            <div class="bg-gradient-to-b from-[#F5F3FF] via-white to-white dark:from-gray-800/80 dark:via-gray-800 dark:to-gray-800 rounded-3xl p-6 sm:p-7 border border-[#E9D5FF]/60 dark:border-gray-700 shadow-sm flex flex-col justify-between overflow-hidden relative group hover:shadow-xl hover:border-[#6C5CE7]/40 transition-all duration-300">
                <div class="space-y-4 text-left">
                    <div class="w-10 h-10 rounded-full bg-[#EDE9FE] dark:bg-purple-900/60 text-[#6C5CE7] dark:text-purple-300 font-bold flex items-center justify-center text-base">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Set up your event</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Add your event details, set ticket tiers and pricing, customize seat maps and upload promotional posters.
                    </p>
                </div>
                
                <!-- Phone Mockup 2: Interactive Styled Card -->
                <div class="mt-8 pt-4 flex justify-center -mb-10 group-hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-56 sm:w-60 rounded-t-3xl overflow-hidden shadow-2xl bg-white dark:bg-gray-900 p-3 space-y-3">
                        <div class="h-24 rounded-xl overflow-hidden relative">
                            <img src="{{ asset('uploads/a9e3088f2698f4b567d9a1c8e03939eaf4410e02.png') }}" alt="Event setup banner" class="w-full h-full object-cover" />
                            <div class="absolute bottom-1 left-2 bg-[#6C5CE7] text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                                <iconify-icon icon="solar:music-note-bold" class="text-[10px]"></iconify-icon>
                                <span>Music Concert</span>
                            </div>
                        </div>
                        <div class="space-y-1.5 text-left">
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full w-3/4"></div>
                            <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded-full w-1/2"></div>
                        </div>
                        <div class="bg-[#F5F3FF] dark:bg-gray-800 p-2 rounded-xl flex items-center justify-between text-[10px]">
                            <span class="font-bold text-gray-800 dark:text-gray-200">VIP Ticket</span>
                            <span class="font-bold text-[#6C5CE7]">Rs. 2,500</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 Card -->
            <div class="bg-gradient-to-b from-[#F5F3FF] via-white to-white dark:from-gray-800/80 dark:via-gray-800 dark:to-gray-800 rounded-3xl p-6 sm:p-7 border border-[#E9D5FF]/60 dark:border-gray-700 shadow-sm flex flex-col justify-between overflow-hidden relative group hover:shadow-xl hover:border-[#6C5CE7]/40 transition-all duration-300">
                <div class="space-y-4 text-left">
                    <div class="w-10 h-10 rounded-full bg-[#EDE9FE] dark:bg-purple-900/60 text-[#6C5CE7] dark:text-purple-300 font-bold flex items-center justify-center text-base">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Publish and start selling</h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Go live with one click. Share your event link, track real-time ticket sales and scan QR tickets at the door.
                    </p>
                </div>
                
                <!-- Phone Mockup 3 -->
                <div class="mt-8 pt-4 flex justify-center -mb-10 group-hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-56 sm:w-60 rounded-t-3xl overflow-hidden shadow-2xl bg-white">
                        <img src="{{ asset('uploads/40c6f467804771131403928c8429669b565a97a3.png') }}" alt="Step 3 Sales Dashboard" class="w-full object-cover object-top" />
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 4. BROWSE BY CATEGORY SECTION (DYNAMIC)    -->
    <!-- ========================================== -->
    <section class="py-16 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-left mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Browse by category</h2>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">Find experiences that match your vibe and mood</p>
            </div>

            <!-- 6 Column Responsive Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-5">
                
                <!-- Category 1: Concerts -->
                <a href="{{ route('events', ['category' => 'Concert']) }}" class="group block text-left">
                    <div class="h-36 sm:h-44 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/concert.jpg') }}" alt="Concerts" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-2 py-0.5 rounded">
                            MUSIC
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Concerts</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Concert'] ?? 0 }} Events</p>
                    </div>
                </a>

                <!-- Category 2: Festivals -->
                <a href="{{ route('events', ['category' => 'Festival']) }}" class="group block text-left">
                    <div class="h-36 sm:h-44 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/event.jpg') }}" alt="Festivals" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/60 px-2 py-0.5 rounded">
                            CULTURE
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Festivals</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Festival'] ?? 0 }} Events</p>
                    </div>
                </a>

                <!-- Category 3: Food & Drink -->
                <a href="{{ route('events', ['category' => 'Food & Drink']) }}" class="group block text-left">
                    <div class="h-36 sm:h-44 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/Food Festival.jpg') }}" alt="Food & Drink" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-950/60 px-2 py-0.5 rounded">
                            FOOD
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Food & Drink</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Food & Drink'] ?? 0 }} Events</p>
                    </div>
                </a>

                <!-- Category 4: Technology -->
                <a href="{{ route('events', ['category' => 'Technology']) }}" class="group block text-left">
                    <div class="h-36 sm:h-44 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/Tech Conference.jpg') }}" alt="Technology" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-2 py-0.5 rounded">
                            TECH
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Technology</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Technology'] ?? 0 }} Events</p>
                    </div>
                </a>

                <!-- Category 5: Sports -->
                <a href="{{ route('events', ['category' => 'Sports']) }}" class="group block text-left">
                    <div class="h-36 sm:h-44 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/Charity Run.jpg') }}" alt="Sports" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/60 px-2 py-0.5 rounded">
                            SPORT
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Sports</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $categoryCounts['Sports'] ?? 0 }} Events</p>
                    </div>
                </a>

                <!-- Category 6: Art & Workshops -->
                <a href="{{ route('events', ['category' => 'Art']) }}" class="group block text-left">
                    <div class="h-36 sm:h-44 rounded-2xl overflow-hidden relative bg-gray-100 dark:bg-gray-800 shadow-sm group-hover:shadow-lg transition-all duration-300">
                        <img src="{{ asset('uploads/Art Exhibition.jpg') }}" alt="Exhibition & Art" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div class="mt-3 space-y-1">
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded">
                            EXHIBITION
                        </span>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#6C5CE7] transition">Exhibition / Art</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ ($categoryCounts['Art'] ?? 0) + ($categoryCounts['Wellness'] ?? 0) }} Events</p>
                    </div>
                </a>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 5. TRENDING IN YOUR CITY (DYNAMIC DB)      -->
    <!-- ========================================== -->
    <section class="py-16 bg-[#faf9ff] dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header with "See all" link -->
            <div class="flex items-end justify-between mb-8">
                <div class="text-left">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Trending in your city</h2>
                    <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">Popular events near you right now</p>
                </div>
                <a href="{{ route('events') }}" class="text-xs sm:text-sm font-bold text-[#6C5CE7] hover:text-[#5a48e0] flex items-center gap-1 group">
                    <span>See all ({{ $upcomingEvents->count() }})</span>
                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
            </div>

            <!-- 4 Column Trending Cards from Database -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($trendingEvents as $tEvent)
                    @php
                        $tImage = $tEvent->image ? (file_exists(public_path('uploads/' . $tEvent->image)) ? asset('uploads/' . $tEvent->image) : asset('uploads/concert.jpg')) : asset('uploads/concert.jpg');
                        $tIsSaved = in_array($tEvent->id, $savedEventIds ?? []);
                    @endphp
                    <div onclick="window.location.href='{{ route('events.show', $tEvent->slug ?: $tEvent->id) }}'" class="cursor-pointer bg-white dark:bg-gray-800 rounded-2xl border border-gray-200/80 dark:border-gray-700/80 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        <div class="h-44 overflow-hidden bg-gray-100 dark:bg-gray-700 relative">
                            <a href="{{ route('events.show', $tEvent->slug ?: $tEvent->id) }}">
                                <img src="{{ $tImage }}" alt="{{ $tEvent->event_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            </a>
                            
                            <!-- Save / Favorite Button -->
                            <button 
                                type="button"
                                onclick="toggleSaveEvent(event, {{ $tEvent->id }}, this)"
                                data-save-event-id="{{ $tEvent->id }}"
                                aria-label="{{ $tIsSaved ? 'Remove from saved events' : 'Save this event' }}"
                                title="{{ $tIsSaved ? 'Saved to favorites' : 'Save to favorites' }}"
                                class="save-event-btn absolute top-3 right-3 w-8 h-8 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 active:scale-90 z-20 group/btn {{ $tIsSaved ? 'text-rose-500' : 'text-gray-600 dark:text-gray-300 hover:text-rose-500' }}"
                            >
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" 
                                     fill="{{ $tIsSaved ? 'currentColor' : 'none' }}" 
                                     stroke="currentColor" 
                                     stroke-width="{{ $tIsSaved ? '0' : '2' }}" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-4 text-left">
                            <div>
                                <a href="{{ route('events.show', $tEvent->slug ?: $tEvent->id) }}" class="block">
                                    <h3 class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-[#6C5CE7] transition line-clamp-1">
                                        {{ $tEvent->event_name }}
                                    </h3>
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1 line-clamp-1">
                                    <iconify-icon icon="solar:map-point-linear" class="text-gray-400 text-xs shrink-0"></iconify-icon>
                                    <span>{{ $tEvent->venue }}</span>
                                </p>
                            </div>
                            <a href="{{ route('events.show', $tEvent->slug ?: $tEvent->id) }}" class="w-full py-2 px-4 rounded-xl bg-[#6C5CE7] hover:bg-[#5b48db] text-white text-xs font-bold text-center block transition shadow-sm">
                                Book Now
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 py-8 text-center text-gray-500">
                        No trending events available.
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 6. CTA BANNER: STAY IN THE LOOP            -->
    <!-- ========================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mb-10">
        <div class="relative bg-gradient-to-r from-[#5a48e0] via-[#6C5CE7] to-[#8D85EC] rounded-3xl p-8 sm:p-12 lg:p-14 text-white shadow-2xl overflow-hidden">
            
            <!-- Glow circles background -->
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-16 -top-16 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 grid lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left text -->
                <div class="lg:col-span-7 space-y-3 text-left">
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                        Stay in the loop
                    </h2>
                    <p class="text-purple-100 text-sm sm:text-base max-w-lg leading-relaxed">
                        Get early access to tickets, exclusive promo codes and weekly curated event guides delivered straight to your inbox.
                    </p>
                </div>

                <!-- Right Newsletter Box -->
                <div class="lg:col-span-5 space-y-2">
                    <form onsubmit="event.preventDefault(); alert('Thank you for subscribing to Eventify updates!'); this.reset();" class="flex items-center bg-white rounded-full p-1.5 shadow-lg">
                        <div class="pl-3 pr-2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            required 
                            placeholder="Enter your email address" 
                            class="w-full bg-transparent text-sm text-gray-800 placeholder-gray-400 border-0 border-none outline-none focus:outline-none focus:ring-0 focus:border-none shadow-none ring-0 pr-2 py-2"
                            style="border: none !important; outline: none !important; box-shadow: none !important;"
                        />
                        <button 
                            type="submit" 
                            class="bg-[#111827] hover:bg-black text-white text-xs sm:text-sm font-bold px-6 py-3 rounded-full transition-all duration-200 flex-shrink-0"
                        >
                            Subscribe
                        </button>
                    </form>
                    <p class="text-[11px] text-purple-200 text-center lg:text-left pl-2 flex items-center gap-1">
                        <iconify-icon icon="solar:lock-bold" class="text-purple-200 text-xs inline-block"></iconify-icon>
                        <span>No spam ever. Unsubscribe anytime with a single click.</span>
                    </p>
                </div>


            </div>
        </div>
    </section>

</div>

@if(isset($nearestEvent) && $nearestEvent)
<!-- ========================================== -->
<!-- NEAREST UPCOMING EVENT POPUP ANNOUNCEMENT  -->
<!-- ========================================== -->
<div id="upcoming-event-popup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 border border-gray-100 dark:border-gray-800" id="popup-content-box">
        
        <!-- Close Button (X) -->
        <button type="button" onclick="closeUpcomingPopup()" class="absolute top-3 right-3 z-20 w-9 h-9 flex items-center justify-center rounded-full bg-black/60 hover:bg-black/80 text-white backdrop-blur-md transition shadow-md focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Event Image with Gradient Banner -->
        <div class="relative h-56 sm:h-64 w-full bg-gray-900">
            <img src="{{ $nearestEvent->image ? asset('uploads/' . $nearestEvent->image) : asset('uploads/event.jpg') }}" 
                 alt="{{ $nearestEvent->event_name }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>

            <!-- Top Left Badge -->
            <div class="absolute top-4 left-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-[#6C5CE7] text-white shadow-lg tracking-wider uppercase">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Happening Soon
                </span>
            </div>

            <!-- Bottom Title on Image -->
            <div class="absolute bottom-4 left-4 right-4 text-white">
                <span class="text-xs font-bold uppercase tracking-wider text-purple-300 bg-purple-900/60 px-2 py-0.5 rounded backdrop-blur-sm">
                    {{ $nearestEvent->category ?? 'Special Event' }}
                </span>
                <h3 class="text-xl sm:text-2xl font-black text-white mt-1 leading-tight drop-shadow-md">
                    {{ $nearestEvent->event_name }}
                </h3>
            </div>
        </div>

        <!-- Event Meta & CTA Section -->
        <div class="p-5 sm:p-6 space-y-4">
            
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed">
                {{ $nearestEvent->description }}
            </p>

            <div class="grid grid-cols-2 gap-3 bg-gray-50 dark:bg-gray-800/60 p-3 rounded-2xl border border-gray-100 dark:border-gray-800 text-xs">
                <div class="flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-900/50 text-[#6C5CE7] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-400 font-semibold uppercase">Date</div>
                        <div class="font-bold">{{ \Carbon\Carbon::parse($nearestEvent->event_date)->format('M d, Y') }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-gray-700 dark:text-gray-200">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 dark:bg-rose-900/50 text-rose-500 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <div class="truncate">
                        <div class="text-[10px] text-gray-400 font-semibold uppercase">Venue</div>
                        <div class="font-bold truncate" title="{{ $nearestEvent->venue }}">{{ $nearestEvent->venue }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-1">
                <a href="{{ route('events.show', $nearestEvent->slug ?: $nearestEvent->id) }}" 
                   class="flex-1 py-3 px-4 rounded-xl bg-[#6C5CE7] hover:bg-[#5b4bc4] text-white font-extrabold text-xs sm:text-sm text-center shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 transition flex items-center justify-center gap-2">
                    <span>Book Tickets</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <button type="button" onclick="closeUpcomingPopup()" class="py-3 px-4 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 font-bold text-xs hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    Later
                </button>
            </div>
        </div>

    </div>
</div>
@endif

<!-- Interactive Category Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const popup = document.getElementById('upcoming-event-popup');
    const popupBox = document.getElementById('popup-content-box');
    if (popup && popupBox) {
        setTimeout(function() {
            popup.classList.remove('opacity-0', 'pointer-events-none');
            popupBox.classList.remove('scale-95');
            popupBox.classList.add('scale-100');
        }, 500);

        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                closeUpcomingPopup();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeUpcomingPopup();
            }
        });
    }
});

function closeUpcomingPopup() {
    const popup = document.getElementById('upcoming-event-popup');
    const popupBox = document.getElementById('popup-content-box');
    if (popup && popupBox) {
        popupBox.classList.remove('scale-100');
        popupBox.classList.add('scale-95');
        popup.classList.add('opacity-0', 'pointer-events-none');
    }
}
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('hero-carousel');
    const slides = carousel ? Array.from(carousel.querySelectorAll('.hero-slide')) : [];
    const previousButton = document.getElementById('hero-prev');
    const nextButton = document.getElementById('hero-next');
    const dots = Array.from(document.querySelectorAll('.hero-dot'));
    const carouselContainer = carousel ? carousel.parentElement : null;

    if (!carousel || slides.length < 2 || !previousButton || !nextButton) {
        return;
    }

    let activeIndex = 0;
    let autoAdvanceTimer;

    const updateDots = function () {
        dots.forEach(function (dot, index) {
            const isActive = index === activeIndex;
            dot.style.width = isActive ? '24px' : '6px';
            dot.style.flexBasis = isActive ? '24px' : '6px';
            dot.style.backgroundColor = isActive ? '#6C5CE7' : '#D1D5DB';
        });
    };

    const showSlide = function (index) {
        activeIndex = (index + slides.length) % slides.length;
        carousel.scrollTo({
            left: slides[activeIndex].offsetLeft,
            behavior: 'smooth'
        });
        updateDots();
    };

    const restartAutoAdvance = function () {
        window.clearInterval(autoAdvanceTimer);
        autoAdvanceTimer = window.setInterval(function () {
            showSlide(activeIndex + 1);
        }, 5000);
    };

    previousButton.addEventListener('click', function () {
        showSlide(activeIndex - 1);
        restartAutoAdvance();
    });

    nextButton.addEventListener('click', function () {
        showSlide(activeIndex + 1);
        restartAutoAdvance();
    });

    dots.forEach(function (dot, index) {
        dot.addEventListener('click', function () {
            showSlide(index);
            restartAutoAdvance();
        });
    });

    carousel.addEventListener('scroll', function () {
        const closestSlide = slides.reduce(function (closest, slide, index) {
            const currentDistance = Math.abs(slide.offsetLeft - carousel.scrollLeft);
            const closestDistance = Math.abs(closest.offsetLeft - carousel.scrollLeft);
            return currentDistance < closestDistance ? { offsetLeft: slide.offsetLeft, index: index } : closest;
        }, { offsetLeft: slides[0].offsetLeft, index: 0 });

        if (closestSlide.index !== activeIndex) {
            activeIndex = closestSlide.index;
            updateDots();
        }
    });

    if (carouselContainer) {
        carouselContainer.addEventListener('mouseenter', function () {
            window.clearInterval(autoAdvanceTimer);
        });
        carouselContainer.addEventListener('mouseleave', restartAutoAdvance);
        carouselContainer.addEventListener('focusin', function () {
            window.clearInterval(autoAdvanceTimer);
        });
        carouselContainer.addEventListener('focusout', restartAutoAdvance);
    }

    updateDots();
    restartAutoAdvance();
});

document.addEventListener('DOMContentLoaded', function () {
    const eventsCarousel = document.getElementById('events-grid');
    const previousButton = document.getElementById('upcoming-events-prev');
    const nextButton = document.getElementById('upcoming-events-next');

    if (!eventsCarousel || !previousButton || !nextButton) {
        return;
    }

    const maxEvents = Number(eventsCarousel.dataset.maxEvents || 8);
    Array.from(eventsCarousel.querySelectorAll('.event-card'))
        .slice(maxEvents)
        .forEach(function (card) {
            card.remove();
        });

    const scrollEvents = function (direction) {
        const firstVisibleCard = Array.from(eventsCarousel.querySelectorAll('.event-card'))
            .find(function (card) {
                return card.style.display !== 'none';
            });

        if (!firstVisibleCard) {
            return;
        }

        const cardGap = parseFloat(window.getComputedStyle(eventsCarousel).columnGap) || 0;
        eventsCarousel.scrollBy({
            left: direction * (firstVisibleCard.offsetWidth + cardGap),
            behavior: 'smooth'
        });
    };

    previousButton.addEventListener('click', function () {
        scrollEvents(-1);
    });

    nextButton.addEventListener('click', function () {
        scrollEvents(1);
    });
});

function filterEvents(category, btnElement) {
    // Update active filter button styling
    const buttons = document.querySelectorAll('.event-filter-btn');
    buttons.forEach(btn => {
        btn.classList.remove('bg-[#6C5CE7]', 'text-white', 'shadow-sm');
        btn.classList.add('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-200', 'dark:border-gray-700');
    });

    if (btnElement) {
        btnElement.classList.remove('bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-300', 'border', 'border-gray-200', 'dark:border-gray-700');
        btnElement.classList.add('bg-[#6C5CE7]', 'text-white', 'shadow-sm');
    }

    // Filter cards
    const cards = document.querySelectorAll('.event-card');
    cards.forEach(card => {
        const cardCat = card.getAttribute('data-category');
        if (category === 'all' || cardCat === category) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection