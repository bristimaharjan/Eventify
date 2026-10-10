@extends('layouts.app')

@section('title', 'Events - Eventify')

@section('content')

<div class="min-h-screen bg-[#f7f9fc] dark:bg-gray-900 font-sans">
<div class="max-w-[1400px] mx-auto flex flex-col md:flex-row gap-7 p-5 items-start">
  
  <!-- ========================================================= -->
  <!-- 1. LEFT FILTER SIDEBAR                                    -->
  <!-- ========================================================= -->
  <aside class="w-full md:w-[270px] lg:w-[270px] shrink-0 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 md:sticky md:top-6 z-10 h-auto">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filters</h2>
      <button onclick="resetFilters()" class="text-[9px] text-[#8d85ec] hover:underline font-medium">Reset All</button>
    </div>

    <!-- Categories -->
    <div class="mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
      <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-3">Categories</p>
      <div id="categoriesFilter" class="space-y-1">
        @php $activeCategory = request('category'); @endphp
        <a href="{{ route('events', array_merge(request()->except('category'), [])) }}"
           class="block w-full text-sm py-2.5 px-3 rounded-lg transition {{ !$activeCategory ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/60' }}">All Categories</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Concert'])) }}"
           class="block w-full text-sm py-2.5 px-3 rounded-lg transition {{ $activeCategory == 'Concert' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/60' }}">Concert</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Art'])) }}"
           class="block w-full text-sm py-2.5 px-3 rounded-lg transition {{ $activeCategory == 'Art' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/60' }}">Exhibition / Art</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Food & Drink'])) }}"
           class="block w-full text-sm py-2.5 px-3 rounded-lg transition {{ $activeCategory == 'Food & Drink' || $activeCategory == 'Food and Drink' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/60' }}">Food & Drink</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Technology'])) }}"
           class="block w-full text-sm py-2.5 px-3 rounded-lg transition {{ $activeCategory == 'Technology' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/60' }}">Technology</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Sports'])) }}"
           class="block w-full text-sm py-2.5 px-3 rounded-lg transition {{ $activeCategory == 'Sports' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/60' }}">Sports</a>
        <a href="{{ route('events', array_merge(request()->except('category'), ['category' => 'Wellness'])) }}"
           class="block w-full text-sm py-2.5 px-3 rounded-lg transition {{ $activeCategory == 'Wellness' ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/60' }}">Workshop / Wellness</a>
      </div>
    </div>

    <!-- Quick Saved Filter in Sidebar -->
    <div class="mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
      <a href="{{ route('events', array_merge(request()->except(['tab', 'saved']), ['tab' => 'saved'])) }}" 
         class="flex items-center justify-between w-full text-left font-semibold text-xs py-2 px-2.5 rounded-xl transition {{ (request('tab') === 'saved' || request('saved')) ? 'bg-purple-100 text-[#8d85ec] font-bold dark:bg-purple-900/50' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700/60' }}">
        <span class="flex items-center gap-1.5">
          <iconify-icon icon="solar:heart-bold" class="text-rose-500 text-xs"></iconify-icon>
          <span>My Saved Events</span>
        </span>
        @auth
          <span class="saved-count-badge text-[10px] bg-purple-100 text-[#8d85ec] dark:bg-purple-900/80 dark:text-purple-200 px-2 py-0.5 rounded-full font-bold">
            {{ count($savedEventIds ?? []) }}
          </span>
        @endauth
      </a>
    </div>

    <!-- Date Range Filter Toggle -->
    <div class="mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
      <button class="flex items-center justify-between w-full text-left font-semibold text-gray-700 dark:text-gray-200 text-xs" onclick="toggleSection('dateFilter')">
        <span>Date Range</span>
        <svg id="icon-dateFilter" class="w-3.5 h-3.5 text-gray-400 transform transition-transform duration-200 {{ request('start_date') || request('end_date') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="dateFilter" class="mt-3 {{ request('start_date') || request('end_date') ? '' : 'hidden' }} space-y-2">
        <div class="flex gap-2">
          <input type="date" id="startDate" value="{{ request('start_date') }}" class="border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-1 text-xs w-1/2 dark:bg-gray-800 dark:text-white outline-none focus:ring-1 focus:ring-[#8d85ec]" />
          <input type="date" id="endDate" value="{{ request('end_date') }}" class="border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-1 text-xs w-1/2 dark:bg-gray-800 dark:text-white outline-none focus:ring-1 focus:ring-[#8d85ec]" />
        </div>
        <button onclick="applyCustomRange()" class="w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white py-1.5 px-2 rounded-lg text-xs font-semibold transition shadow-xs">Apply Range</button>
      </div>
    </div>

    <!-- Price Filter Toggle -->
    <div class="mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
      <button class="flex items-center justify-between w-full text-left font-semibold text-gray-700 dark:text-gray-200 text-xs" onclick="toggleSection('priceFilter')">
        <span>Max Budget</span>
        <svg id="icon-priceFilter" class="w-3.5 h-3.5 text-gray-400 transform transition-transform duration-200 {{ request('min_price') || request('max_price') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="priceFilter" class="mt-3 {{ request('min_price') || request('max_price') ? '' : 'hidden' }} space-y-2">
        <div class="flex gap-2">
          <input type="number" id="minPrice" placeholder="Min Rs" value="{{ request('min_price') }}" class="border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-1 text-xs w-1/2 dark:bg-gray-800 dark:text-white outline-none focus:ring-1 focus:ring-[#8d85ec]" />
          <input type="number" id="maxPrice" placeholder="Max Rs" value="{{ request('max_price') }}" class="border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-1 text-xs w-1/2 dark:bg-gray-800 dark:text-white outline-none focus:ring-1 focus:ring-[#8d85ec]" />
        </div>
        <button onclick="applyPriceFilter()" class="w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white py-1.5 px-2 rounded-lg text-xs font-semibold transition shadow-xs">Filter Price</button>
      </div>
    </div>

    <!-- 4. Location Search Toggle -->
    <div class="mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
      <button class="flex items-center justify-between w-full text-left font-semibold text-gray-700 dark:text-gray-200 text-xs" onclick="toggleSection('locationFilter')">
        <span>Search Location</span>
        <svg id="icon-locationFilter" class="w-3.5 h-3.5 text-gray-400 transform transition-transform duration-200 {{ request('location') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="locationFilter" class="mt-3 {{ request('location') ? '' : 'hidden' }} space-y-2">
        <input type="text" placeholder="e.g. Kathmandu, Pokhara" value="{{ request('location') }}" class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-[#8d85ec] dark:bg-gray-800 dark:text-white outline-none" id="locationSearchInput" onkeydown="if(event.key === 'Enter') searchLocations()" />
        
        <!-- Popular quick location chips -->
        <div class="flex flex-wrap gap-1 pt-1">
          @foreach(['Kathmandu', 'Pokhara', 'Lalitpur', 'Bhaktapur'] as $city)
            <button type="button" onclick="quickLocation('{{ $city }}')" class="text-[10px] px-2 py-0.5 rounded-md border transition {{ request('location') == $city ? 'bg-purple-100 text-[#8d85ec] border-purple-300 font-bold dark:bg-purple-900/50' : 'bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:border-purple-300' }}">
              {{ $city }}
            </button>
          @endforeach
        </div>

        <button onclick="searchLocations()" class="w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white py-1.5 px-2 rounded-lg text-xs font-semibold transition shadow-xs">Search Location</button>
      </div>
    </div>

    <!-- 5. Venue Search Toggle -->
    <div>
      <button class="flex items-center justify-between w-full text-left font-semibold text-gray-700 dark:text-gray-200 text-xs" onclick="toggleSection('venueFilter')">
        <span>Search Venue</span>
        <svg id="icon-venueFilter" class="w-3.5 h-3.5 text-gray-400 transform transition-transform duration-200 {{ request('venue') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div id="venueFilter" class="mt-3 {{ request('venue') ? '' : 'hidden' }} space-y-2">
        <input type="text" placeholder="e.g. Hotel, Stadium" value="{{ request('venue') }}" class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-[#8d85ec] dark:bg-gray-800 dark:text-white outline-none" id="venueSearchInput" onkeydown="if(event.key === 'Enter') searchVenues()" />
        <button onclick="searchVenues()" class="w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white py-1.5 px-2 rounded-lg text-xs font-semibold transition shadow-xs">Search</button>
      </div>
    </div>
  </aside>

  <!-- ========================================================= -->
  <!-- 2. MAIN EVENTS LISTING & BOOKING MODALS                   -->
  <!-- ========================================================= -->
  <main x-data="{
      openBookingId: null,
      selectedEvent: null,
      selectedTicketId: null,
      tickets: 1,
      showKhaltiPopup: false,
      phone: '',
      mpin: '',
      paymentError: '',
      
      initBooking(event) {
          this.selectedEvent = event;
          this.openBookingId = event.id;
          this.tickets = 1;
          this.phone = '';
          this.mpin = '';
          this.paymentError = '';
          this.showKhaltiPopup = false;
          
          // Auto-select first active available ticket type
          if (event.ticket_types && event.ticket_types.length > 0) {
              let firstAvail = event.ticket_types.find(t => t.status === 'active' && ((t.quantity - t.sold_quantity) > 0));
              this.selectedTicketId = firstAvail ? firstAvail.id : event.ticket_types[0].id;
          } else {
              this.selectedTicketId = null;
          }
      },

      getSelectedTicket() {
          if (!this.selectedEvent || !this.selectedEvent.ticket_types) return null;
          return this.selectedEvent.ticket_types.find(t => t.id === this.selectedTicketId) || null;
      },

      getTicketPrice() {
          let t = this.getSelectedTicket();
          return t ? Number(t.price) : (this.selectedEvent ? Number(this.selectedEvent.price) : 0);
      },

      getMaxTickets() {
          let t = this.getSelectedTicket();
          if (!t) return this.selectedEvent ? (this.selectedEvent.available_seats || 1) : 1;
          return Math.max(1, t.quantity - t.sold_quantity);
      },

      isSoldOut() {
          let t = this.getSelectedTicket();
          if (!t) return (this.selectedEvent && this.selectedEvent.available_seats <= 0);
          return (t.quantity - t.sold_quantity) <= 0;
      },

      getSubtotal() {
          return this.tickets * this.getTicketPrice();
      },

      getTotal() {
          return this.getSubtotal() + 5.65;
      }
  }" class="flex-1 min-w-0 w-full">
    
    <!-- Top Header Bar -->
    <div class="mb-6">
      <!-- Title -->
      <div class="mb-5">
        <h1 class="text-2xl sm:text-[27px] leading-none font-extrabold text-gray-900 dark:text-white tracking-tight">Events</h1>
        <p class="text-xs sm:text-[13px] text-gray-500 dark:text-gray-400 mt-1.5">
          Discover and book tickets for top events in Nepal.
        </p>
      </div>

      <!-- Search + Location -->
      <div class="flex flex-col sm:flex-row items-stretch gap-2.5 mb-3">
       <form action="{{ route('events') }}" method="GET"
      class="relative w-full sm:w-[400px] lg:w-[430px] shrink-0">
          <input type="hidden" name="tab" value="{{ $tab }}">
          <input type="search" name="query" value="{{ $searchTerm }}" placeholder="Search by events, venue and location"
                 class="w-full h-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 py-2 pl-10 pr-3 text-xs sm:text-sm text-gray-700 dark:text-gray-200 outline-none focus:ring-2 focus:ring-purple-200 focus:border-purple-200">
          <iconify-icon icon="solar:magnifer-linear" class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></iconify-icon>
        </form>

     <div class="relative w-full sm:w-[190px] shrink-0">
          <iconify-icon icon="solar:map-point-linear" class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></iconify-icon>
          <select onchange="window.location.href = this.value"
                  class="w-full h-10 appearance-none rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 pl-10 pr-8 py-2 text-xs sm:text-sm text-gray-600 dark:text-gray-300 outline-none focus:ring-2 focus:ring-purple-200">
            @foreach($availableLocations as $availableLocation)
              <option value="{{ route('events', array_merge(request()->except(['location', 'page']), ['location' => $availableLocation === 'All Locations' ? null : $availableLocation])) }}" {{ ($location ?: 'All Locations') === $availableLocation ? 'selected' : '' }}>
                {{ $availableLocation }}
              </option>
            @endforeach
          </select>
          <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
          </svg>
        </div>
      </div>

      <!-- Event Type / Quick View Tabs -->
      <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5">
        <a href="{{ route('events', array_merge(request()->except(['tab', 'saved']), [])) }}"
           class="px-3.5 py-2 rounded-full text-[10px] sm:text-[11px] font-bold whitespace-nowrap transition {{ $tab === 'hot' ? 'bg-[#8D85EC] text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">
          Hot's and Happening
        </a>

        <a href="{{ route('events', array_merge(request()->except(['tab', 'saved']), ['tab' => 'upcoming'])) }}"
           class="px-3.5 py-2 rounded-full text-[10px] sm:text-[11px] font-bold whitespace-nowrap transition {{ $tab === 'upcoming' ? 'bg-[#8D85EC] text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">
          Upcoming Events ({{ $totalEvents }})
        </a>

        <a href="{{ route('events', array_merge(request()->except(['tab', 'saved']), ['tab' => 'saved'])) }}"
           class="px-3.5 py-2 rounded-full text-[10px] sm:text-[11px] font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $tab === 'saved' ? 'bg-[#8D85EC] text-white shadow-xs' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">
          <iconify-icon icon="solar:heart-bold" class="text-rose-500 text-xs"></iconify-icon>
          <span>Saved (<span class="saved-count-badge">{{ count($savedEventIds ?? []) }}</span>)</span>
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 p-4 rounded-xl mb-6 shadow-xs flex items-center justify-between text-xs sm:text-sm">
        <div class="flex items-center gap-2">
          <iconify-icon icon="solar:check-circle-bold" class="text-emerald-500 text-base"></iconify-icon>
          <span class="font-medium">{{ session('success') }}</span>
        </div>
        <a href="{{ route('usereventbook') }}" class="font-semibold text-emerald-700 dark:text-emerald-300 underline hover:no-underline">View My Tickets &rarr;</a>
      </div>
    @endif

    <!-- Event Cards Grid (Exactly matching reference image layout) -->
    @if($events->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($events as $event)
          @php
            $activeTickets = $event->ticketTypes ? $event->ticketTypes->where('status', 'active') : collect();
            $minPrice = $activeTickets->isNotEmpty() ? $activeTickets->min('price') : $event->price;
            $maxPrice = $activeTickets->isNotEmpty() ? $activeTickets->max('price') : $event->price;
            $totalRemaining = $activeTickets->isNotEmpty() ? $activeTickets->sum(fn($t) => max(0, $t->quantity - $t->sold_quantity)) : $event->available_seats;
            $isSaved = in_array($event->id, $savedEventIds ?? []);
            $eventImage = $event->image ? basename(str_replace('\\', '/', $event->image)) : null;
            $eventImageUrl = $eventImage && is_file(public_path('uploads/' . $eventImage))
                ? asset('uploads/' . $eventImage)
                : asset('uploads/concert.jpg');
          @endphp

          <!-- Event Card -->
          <div onclick="window.location.href='{{ route('events.show', $event->slug ?: $event->id) }}'" 
               class="event-listing-card cursor-pointer rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition transform hover:-translate-y-1 w-full bg-white dark:bg-gray-800 flex flex-col justify-between border border-gray-100 dark:border-gray-200 group">
              <div>
                  <!-- Card Image Container -->
                  <div class="w-full h-40 overflow-hidden rounded-t-2xl relative bg-gray-100 dark:bg-gray-900">
                      <img src="{{ $eventImageUrl }}" alt="{{ $event->event_name }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" onerror="this.onerror=null; this.src='{{ asset('uploads/concert.jpg') }}';" />
                      
                      <!-- Category Badge (Top-Left) -->
                      @if($event->category)
                          <span class="absolute top-3 left-3 bg-white/90 dark:bg-gray-900/90 text-purple-700 dark:text-purple-300 text-[10px] font-bold px-3 py-1 rounded-full shadow-xs z-10">
                              {{ $event->category }}
                          </span>
                      @endif

                      <!-- Save / Favorite Button (Top-Right) -->
                      <button 
                          type="button"
                          onclick="toggleSaveEvent(event, {{ $event->id }}, this)"
                          data-save-event-id="{{ $event->id }}"
                          aria-label="{{ $isSaved ? 'Remove from saved events' : 'Save this event' }}"
                          title="{{ $isSaved ? 'Saved to favorites' : 'Save to favorites' }}"
                          class="save-event-btn absolute top-3.5 right-3.5 w-8 h-8 sm:w-9 sm:h-9 bg-white/95 dark:bg-gray-900/95 backdrop-blur-xs rounded-full flex items-center justify-center shadow-md transition-all duration-200 hover:scale-110 active:scale-90 z-20 group/btn {{ $isSaved ? 'text-rose-500' : 'text-gray-600 dark:text-gray-300 hover:text-rose-500' }}"
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
                  <!-- Card Body -->
                  <div class="p-4 flex flex-col gap-2 text-gray-900 dark:text-gray-200">
                      <h3 class="text-sm font-bold truncate text-gray-900 dark:text-white group-hover:text-[#8D85EC] transition">{{ $event->event_name }}</h3>
                      
                      <!-- Location -->
                      <p class="text-[11px] text-gray-600 dark:text-gray-400 truncate flex items-center gap-1">
                          <iconify-icon icon="solar:map-point-linear" class="text-rose-500 text-xs shrink-0"></iconify-icon>
                          <span class="truncate">{{ $event->venue }}</span>
                      </p>

                      <!-- Date & Time -->
                      <p class="text-[11px] text-gray-600 dark:text-gray-400 flex items-center gap-1">
                          <iconify-icon icon="solar:calendar-linear" class="text-blue-500 text-xs shrink-0"></iconify-icon>
                          <span>{{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y - h:i A') }}</span>
                      </p>

              
                      
                      <!-- Pricing, Seat Scarcity & Ticket Badges -->
                      <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                          <div class="flex justify-between items-center mb-1.5">
                              <span class="text-[10px] font-bold text-[#8d85ec]">
                                  @if((float)$minPrice <= 0)
                                      Free
                                  @elseif($minPrice == $maxPrice)
                                      Rs {{ number_format($minPrice, 2) }}
                                  @else
                                      From Rs {{ number_format($minPrice, 0) }}
                                  @endif
                              </span>
                              <span class="text-[9px] text-gray-500 dark:text-gray-400 font-medium">
                                  @if($totalRemaining <= 0)
                                      <span class="text-rose-500 font-semibold">Sold Out</span>
                                  @else
                                      {{ $totalRemaining }} seats left
                                  @endif
                              </span>
                          </div>

                      
                          
                      </div>
                  </div>
              </div>

              <!-- CTA Button -->
              <div class="p-4 pt-0">
                  <a href="{{ route('events.show', $event->slug ?: $event->id) }}"
                    class="block text-center w-full bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold text-[10px] sm:text-xs py-2 px-3 rounded-lg transition shadow-xs hover:shadow-md transform active:scale-95">
                      Book Tickets
                  </a>
              </div>
          </div>
        @endforeach
      </div>
      @if($totalPages > 1)
        <nav class="flex items-center justify-center gap-1.5 mt-6" aria-label="Event pages">
          <a href="{{ request()->fullUrlWithQuery(['page' => max(1, $currentPage - 1)]) }}" class="px-3 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs {{ $currentPage === 1 ? 'pointer-events-none opacity-40' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">Previous</a>
          @for($page = 1; $page <= $totalPages; $page++)
            <a href="{{ request()->fullUrlWithQuery(['page' => $page]) }}" class="min-w-8 text-center px-2 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 text-xs {{ $page === $currentPage ? 'bg-[#8D85EC] text-white border-[#8D85EC]' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">{{ $page }}</a>
          @endfor
          <a href="{{ request()->fullUrlWithQuery(['page' => min($totalPages, $currentPage + 1)]) }}" class="px-3 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs {{ $currentPage === $totalPages ? 'pointer-events-none opacity-40' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">Next</a>
        </nav>
      @endif
    @else
      <!-- Empty State -->
      @if(request('tab') === 'saved' || request('saved'))
        <div id="saved-empty-state" class="text-center py-16 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 max-w-lg mx-auto mt-6">
            <div class="w-14 h-14 bg-purple-50 dark:bg-purple-950/50 rounded-full flex items-center justify-center mx-auto mb-3">
                <iconify-icon icon="solar:heart-broken-bold" class="text-2xl text-rose-500"></iconify-icon>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">No saved events yet</h3>
            <p class="text-gray-500 dark:text-gray-300 text-xs sm:text-sm mt-1 leading-relaxed">
                Save events you’re interested in and they’ll appear here for quick access anytime.
            </p>
            <div class="mt-5 flex justify-center gap-3">
                <a href="{{ route('events') }}" class="bg-[#8D85EC] hover:bg-[#7b76e4] text-white font-semibold text-xs px-5 py-2.5 rounded-full transition shadow-xs">
                    Browse All Events
                </a>
            </div>
        </div>
      @else
        <div class="text-center mt-12 bg-white dark:bg-gray-800 p-12 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-[#8D85EC] text-3xl">
                <iconify-icon icon="solar:ticket-bold"></iconify-icon>
            </div>
            <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold mt-4">No events found matching your criteria.</p>
            <button onclick="resetFilters()" class="mt-4 bg-[#8d85ec] hover:bg-[#7b76e4] text-white px-5 py-2 rounded-lg text-xs font-semibold transition shadow-xs">Reset Filters</button>
        </div>
      @endif
    @endif

    <!-- ========================================================= -->
    <!-- 3. INTERACTIVE MULTI-TICKET BOOKING MODAL & KHALTI POPUP  -->
    <!-- ========================================================= -->
    <div 
        x-show="openBookingId !== null" 
        x-transition.opacity
        x-cloak
        class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4"
    >
        <div 
            @click.away="openBookingId = null; selectedEvent = null" 
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 sm:p-8 w-full max-w-lg border border-gray-200 dark:border-gray-700 transform transition-all max-h-[90vh] overflow-y-auto"
        >
            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate">
                    Book: <span class="text-[#8d85ec]" x-text="selectedEvent ? selectedEvent.event_name : ''"></span>
                </h2>
                <button @click="openBookingId = null; selectedEvent = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl font-bold">
                    <iconify-icon icon="solar:close-circle-bold" class="text-xl"></iconify-icon>
                </button>
            </div>

            <template x-if="selectedEvent">
                <div class="space-y-5">
                    
                    <!-- 1. CHOOSE TICKET TYPE -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                            1. Choose Ticket Type:
                        </label>

                        <div class="space-y-2">
                            <template x-for="ticket in (selectedEvent.ticket_types || [])" :key="ticket.id">
                                <label 
                                    :class="{
                                        'border-[#8d85ec] bg-purple-50/70 dark:bg-purple-950/30 ring-2 ring-[#8d85ec]': selectedTicketId === ticket.id,
                                        'border-gray-200 dark:border-gray-700 hover:border-purple-300 dark:hover:border-purple-600': selectedTicketId !== ticket.id,
                                        'opacity-50 cursor-not-allowed bg-gray-50 dark:bg-gray-800': (ticket.quantity - ticket.sold_quantity) <= 0
                                    }"
                                    class="flex items-start justify-between p-3.5 rounded-xl border transition cursor-pointer"
                                >
                                    <div class="flex items-start gap-3">
                                        <input 
                                            type="radio" 
                                            name="modal_ticket_type" 
                                            :value="ticket.id" 
                                            x-model="selectedTicketId" 
                                            :disabled="(ticket.quantity - ticket.sold_quantity) <= 0"
                                            class="mt-1 text-[#8d85ec] focus:ring-[#8d85ec]"
                                        >
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-gray-900 dark:text-white text-sm" x-text="ticket.name"></span>
                                                <template x-if="(ticket.quantity - ticket.sold_quantity) <= 0">
                                                    <span class="text-[10px] bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 px-2 py-0.5 rounded-full font-bold">Sold Out</span>
                                                </template>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="ticket.description || 'Standard admission'"></p>
                                            
                                            <!-- Remaining capacity text -->
                                            <div class="mt-1 text-[11px]">
                                                <template x-if="(ticket.quantity - ticket.sold_quantity) > 5">
                                                    <span class="text-gray-500 dark:text-gray-400" x-text="(ticket.quantity - ticket.sold_quantity) + ' tickets available'"></span>
                                                </template>
                                                <template x-if="(ticket.quantity - ticket.sold_quantity) > 0 && (ticket.quantity - ticket.sold_quantity) <= 5">
                                                    <span class="text-amber-600 dark:text-amber-400 font-bold" x-text="'Only ' + (ticket.quantity - ticket.sold_quantity) + ' tickets remaining!'"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <span class="text-base font-extrabold text-[#8d85ec]" x-text="'Rs ' + Number(ticket.price).toFixed(2)"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- 2. QUANTITY SELECTOR -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                            2. Select Quantity:
                        </label>
                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="if(tickets > 1) tickets--" 
                                :disabled="tickets <= 1 || isSoldOut()"
                                class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white font-bold text-lg hover:bg-gray-200 transition flex items-center justify-center disabled:opacity-40"
                            >-</button>
                            <input 
                                type="number" 
                                x-model.number="tickets" 
                                min="1" 
                                :max="getMaxTickets()" 
                                :disabled="isSoldOut()"
                                class="w-24 text-center border border-gray-300 dark:border-gray-600 rounded-lg py-2 text-gray-900 dark:text-white font-bold focus:ring-2 focus:ring-[#8d85ec] focus:outline-none dark:bg-gray-700"
                            >
                            <button 
                                type="button" 
                                @click="if(tickets < getMaxTickets()) tickets++" 
                                :disabled="tickets >= getMaxTickets() || isSoldOut()"
                                class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white font-bold text-lg hover:bg-gray-200 transition flex items-center justify-center disabled:opacity-40"
                            >+</button>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2" x-text="'Max: ' + getMaxTickets() + ' ticket(s)'"></span>
                        </div>
                    </div>

                    <!-- 3. REAL-TIME PRICE BREAKDOWN -->
                    <div class="bg-gray-50 dark:bg-gray-700/60 p-4 rounded-xl border border-gray-200 dark:border-gray-600 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600 dark:text-gray-300 text-xs">
                            <span>Selected Ticket:</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="getSelectedTicket() ? getSelectedTicket().name : 'None'"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300 text-xs">
                            <span>Price per Ticket:</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getTicketPrice().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300 text-xs">
                            <span>Subtotal (<span x-text="tickets"></span> &times; Rs <span x-text="getTicketPrice().toFixed(0)"></span>):</span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getSubtotal().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-300 text-xs">
                            <span>Service Charge:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">Rs 5.65</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-2 flex justify-between items-center">
                            <span class="font-bold text-gray-900 dark:text-white text-sm">Total Amount:</span>
                            <span class="font-extrabold text-[#8d85ec] text-lg" x-text="'Rs ' + getTotal().toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="openBookingId = null; selectedEvent = null" 
                            class="px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 font-semibold text-xs transition">
                            Cancel
                        </button>

                        <button
                            @click="if(!isSoldOut() && selectedTicketId) { showKhaltiPopup = true; }"
                            :disabled="isSoldOut() || !selectedTicketId"
                            class="px-6 py-2.5 rounded-xl text-white font-bold text-xs sm:text-sm transition transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed bg-[#8D85EC] hover:bg-[#7b76e4] shadow-xs">
                            <span x-text="isSoldOut() ? 'Sold Out' : 'Proceed to Pay with Khalti'"></span>
                        </button>
                    </div>

                    <!-- Khalti Modal Popup -->
                    <div x-show="showKhaltiPopup" x-transition.opacity
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-4xl w-full overflow-hidden flex flex-col md:flex-row border border-gray-200 dark:border-gray-700">

                            <!-- Left Section: Payment Summary -->
                            <div class="w-full md:w-2/3 bg-[#FAF9FC] dark:bg-gray-800 p-6 flex flex-col justify-between gap-6">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Payment Details</h2>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Complete your ticket booking via official Khalti verification</p>

                                    <!-- User & Event Info -->
                                    <div class="mt-4 bg-white dark:bg-gray-700 p-4 rounded-xl border border-gray-200 dark:border-gray-600 space-y-1">
                                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Booking For</p>
                                        <p class="font-bold text-gray-900 dark:text-white text-base" x-text="selectedEvent.event_name"></p>
                                        <p class="text-xs text-[#8D85EC] font-semibold" x-text="'Ticket Tier: ' + (getSelectedTicket() ? getSelectedTicket().name : '') + ' (' + tickets + ' ticket(s))'"></p>
                                        <p class="text-xs text-gray-600 dark:text-gray-300" x-text="'Billed to: {{ Auth::user()->name ?? 'User' }} ({{ Auth::user()->email ?? '' }})'"></p>
                                    </div>

                                    <!-- Amount Summary -->
                                    <div class="mt-4 bg-white dark:bg-gray-700 p-4 rounded-xl border border-gray-200 dark:border-gray-600 space-y-2 text-xs">
                                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                            <span>Ticket Price (<span x-text="tickets"></span> &times; Rs <span x-text="getTicketPrice().toFixed(0)"></span>)</span>
                                            <span class="font-semibold text-gray-900 dark:text-white" x-text="'Rs ' + getSubtotal().toFixed(2)"></span>
                                        </div>
                                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                                            <span>Service Charge</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">Rs 5.65</span>
                                        </div>
                                        <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                                        <div class="flex justify-between font-extrabold text-gray-900 dark:text-white text-sm">
                                            <span>Total Payable</span>
                                            <span class="text-[#8D85EC]" x-text="'Rs ' + getTotal().toFixed(2)"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-[#7B2CBF] text-white text-xs font-semibold py-2 px-4 text-center rounded-xl">
                                    PAYMENT POWERED BY <span class="ml-1 font-bold">KHALTI WALLET</span>
                                </div>
                            </div>

                            <!-- Right Section: Khalti Wallet Credentials -->
                            <div class="w-full md:w-1/3 bg-white dark:bg-gray-900 p-6 flex flex-col justify-between gap-4 relative">
                                <button @click="showKhaltiPopup=false; paymentError='';" 
                                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-white">
                                    <iconify-icon icon="solar:close-circle-bold" class="text-xl"></iconify-icon>
                                </button>

                                <div>
                                    <div class="flex items-center justify-center mb-3">
                                        <img src="{{ asset('uploads/khalti.png') }}" alt="Khalti Logo" class="h-8 object-contain">
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white text-center">Pay via Khalti Wallet</h3>
                                    <p class="text-gray-400 text-xs text-center mt-1">Enter your Khalti Mobile Number & MPIN</p>

                                    <div class="mt-4 space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Khalti Mobile Number</label>
                                            <input type="text" x-model="phone" 
                                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-[#8D85EC] dark:bg-gray-800 dark:text-white outline-none">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">Khalti MPIN</label>
                                            <input type="password" x-model="mpin"
                                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-[#8D85EC] dark:bg-gray-800 dark:text-white outline-none">
                                        </div>
                                        <p class="text-rose-500 text-xs font-semibold" x-text="paymentError"></p>
                                    </div>
                                </div>

                                <div>
                                    <button @click="
                                        paymentError='';
                                        let allowedPhones = ['9800000000','9800000001','9800000002','9800000003','9800000004','9800000005'];
                                        if (allowedPhones.includes(phone.trim()) && mpin === '1111') {
                                            let eventToSave = {...selectedEvent};
                                            let ticketTypeId = selectedTicketId;
                                            let count = tickets;
                                            showKhaltiPopup = false;
                                            openBookingId = null;
                                            selectedEvent = null;
                                            saveBooking(eventToSave, ticketTypeId, count);
                                        } else {
                                            paymentError = 'Invalid Khalti ID or MPIN. (Use test phone 9800000000 and PIN 1111)';
                                        }"
                                        class="w-full py-2.5 rounded-xl text-white font-bold text-xs sm:text-sm transition transform hover:scale-[1.02] active:scale-95 shadow-xs"
                                        style="background: linear-gradient(90deg,#8D85EC 0%,#6E29B0 100%);">
                                        Confirm & Pay
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </template>
        </div>
    </div>

  </main>
</div>
</div>

<script>
function toggleSection(id) {
    const section = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if(section) section.classList.toggle('hidden');
    if(icon) icon.classList.toggle('rotate-180');
}

function getQueryParams() {
    return new URLSearchParams(window.location.search);
}

function setQueryParams(params) {
    window.location.search = params.toString();
}

function resetFilters() {
  window.location.href = "{{ route('events') }}";
}

function applyCustomRange() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const params = getQueryParams();
    if (start) params.set('start_date', start);
    else params.delete('start_date');
    if (end) params.set('end_date', end);
    else params.delete('end_date');
    setQueryParams(params);
}

function applyPriceFilter() {
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    const params = getQueryParams();
    if (minPrice) params.set('min_price', minPrice);
    else params.delete('min_price');
    if (maxPrice) params.set('max_price', maxPrice);
    else params.delete('max_price');
    setQueryParams(params);
}

function searchLocations() {
    const input = document.getElementById('locationSearchInput').value.trim();
    const params = getQueryParams();
    if(input) params.set('location', input);
    else params.delete('location');
    setQueryParams(params);
}

function quickLocation(city) {
    const params = getQueryParams();
    if (params.get('location') === city) {
        params.delete('location');
    } else {
        params.set('location', city);
    }
    setQueryParams(params);
}

function searchVenues() {
    const input = document.getElementById('venueSearchInput').value.trim();
    const params = getQueryParams();
    if(input) params.set('venue', input);
    else params.delete('venue');
    setQueryParams(params);
}

// Server-side safe booking function with ticket_type_id
function saveBooking(event, ticketTypeId, tickets) {
  fetch("{{ route('khalti.saveBooking') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({
      event_id: event.id,
      ticket_type_id: ticketTypeId,
      tickets: tickets
    })
  })
  .then(async response => {
    const data = await response.json();
    if (response.ok && data.success) {
      alert("Payment & Booking Successful! Your ticket has been emailed to you.");
      window.location.href = "{{ route('usereventbook') }}";
    } else {
      console.error("Booking error:", data);
      alert(data.message || "Failed to complete booking.");
    }
  })
  .catch(err => {
    console.error("Fetch error:", err);
    alert("Network or server error. Please try again.");
  });
}
</script>

@endsection