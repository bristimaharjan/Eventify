{{-- Eventify Vendor Sidebar — same design language as Admin sidebar --}}
@php
    $vendorUnreadInquiries = \App\Models\Inquiry::where('vendor_id', Auth::id())->where('status', 'unread')->count();
    $sidebarKyc            = Auth::user()->kyc;
    $sidebarKycStatus      = $sidebarKyc ? $sidebarKyc->status : 'not_submitted';
@endphp

<style>
/* ─── Reset & font ──────────────────────────────────────────── */
#ev-vendor-sidebar * { box-sizing: border-box; }

/* ─── Sidebar shell ─────────────────────────────────────────── */
#ev-vendor-sidebar {
    position: fixed;
    top: 0; left: 0;
    z-index: 40;
    width: 260px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    box-shadow: 2px 0 12px rgba(120,110,240,.07);
    transition: width .28s cubic-bezier(.4,0,.2,1), transform .28s cubic-bezier(.4,0,.2,1);
    font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    overflow: hidden;
}
#ev-vendor-sidebar.ev-collapsed { width: 72px; }

/* ─── Top bar ───────────────────────────────────────────────── */
#ev-vendor-sidebar .ev-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 18px 14px;
    flex-shrink: 0;
}
#ev-vendor-sidebar .ev-brand {
    display: flex;
    align-items: center;
    gap: 9px;
    overflow: hidden;
    white-space: nowrap;
}
#ev-vendor-sidebar .ev-brand img { width: 32px; height: 32px; object-fit: contain; flex-shrink: 0; }
#ev-vendor-sidebar .ev-brand-name {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a2e;
    letter-spacing: -.3px;
    transition: opacity .2s ease, max-width .25s ease;
    max-width: 120px;
    overflow: hidden;
}
#ev-vendor-sidebar.ev-collapsed .ev-brand-name { opacity: 0; max-width: 0; }

#ev-vendor-sidebar .ev-hamburger {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px; height: 34px;
    border: none;
    background: none;
    cursor: pointer;
    border-radius: 8px;
    color: #6b7280;
    flex-shrink: 0;
    transition: background .18s ease;
}
#ev-vendor-sidebar .ev-hamburger:hover { background: #f3f4f6; }
#ev-vendor-sidebar .ev-hamburger svg { width: 20px; height: 20px; }

/* ─── Profile card ──────────────────────────────────────────── */
#ev-vendor-sidebar .ev-profile {
    display: flex;
    align-items: center;
    gap: 11px;
    margin: 0 14px 16px;
    padding: 11px 13px;
    background: #f8f7ff;
    border-radius: 14px;
    cursor: pointer;
    text-decoration: none;
    flex-shrink: 0;
    transition: background .18s ease;
    overflow: hidden;
    min-width: 0;
}
#ev-vendor-sidebar .ev-profile:hover { background: #ede9fe; }

#ev-vendor-sidebar .ev-avatar-wrap {
    position: relative;
    flex-shrink: 0;
    width: 38px; height: 38px;
}
#ev-vendor-sidebar .ev-avatar-wrap img,
#ev-vendor-sidebar .ev-avatar-wrap .ev-avatar-init {
    width: 38px; height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #8d85ec;
}
#ev-vendor-sidebar .ev-avatar-wrap .ev-avatar-init {
    background: #8d85ec;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
#ev-vendor-sidebar .ev-online-dot {
    position: absolute;
    bottom: 1px; right: 1px;
    width: 10px; height: 10px;
    background: #22c55e;
    border: 2px solid #fff;
    border-radius: 50%;
}

#ev-vendor-sidebar .ev-profile-info {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    transition: opacity .18s ease, max-width .25s ease;
    max-width: 140px;
}
#ev-vendor-sidebar.ev-collapsed .ev-profile-info { opacity: 0; max-width: 0; pointer-events: none; }

#ev-vendor-sidebar .ev-profile-name {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}
#ev-vendor-sidebar .ev-profile-role {
    font-size: 11px;
    color: #9ca3af;
    white-space: nowrap;
    line-height: 1.3;
}
#ev-vendor-sidebar .ev-profile-chevron {
    flex-shrink: 0;
    color: #9ca3af;
    transition: opacity .18s ease, width .22s ease;
}
#ev-vendor-sidebar.ev-collapsed .ev-profile-chevron { opacity: 0; width: 0; overflow: hidden; }

/* ─── Collapsed profile: center avatar, strip card ─────────── */
#ev-vendor-sidebar.ev-collapsed .ev-profile {
    justify-content: center;
    background: transparent;
    padding: 6px;
    margin: 0 10px 12px;
    border-radius: 12px;
    gap: 0;
}
#ev-vendor-sidebar.ev-collapsed .ev-profile:hover {
    background: #ede9fe;
}
#ev-vendor-sidebar.ev-collapsed .ev-avatar-wrap {
    width: 42px; height: 42px;
}
#ev-vendor-sidebar.ev-collapsed .ev-avatar-wrap img,
#ev-vendor-sidebar.ev-collapsed .ev-avatar-wrap .ev-avatar-init {
    width: 42px; height: 42px;
}

/* ─── Section label ─────────────────────────────────────────── */
#ev-vendor-sidebar .ev-section-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .09em;
    text-transform: uppercase;
    color: #b0b4bc;
    padding: 0 18px;
    margin-bottom: 6px;
    flex-shrink: 0;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity .18s ease, height .22s ease;
    height: 18px;
}
#ev-vendor-sidebar.ev-collapsed .ev-section-label { opacity: 0; height: 0; margin-bottom: 0; }

/* ─── Nav list ──────────────────────────────────────────────── */
#ev-vendor-sidebar .ev-nav {
    flex: 1;
    min-height: 0;
    padding: 0 10px;
    overflow-x: hidden;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

/* ─── Nav item ──────────────────────────────────────────────── */
#ev-vendor-sidebar .ev-item {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 10px 12px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    color: #4b5563;
    text-decoration: none;
    transition: background .18s ease, color .18s ease;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    position: relative;
    white-space: nowrap;
    flex-shrink: 0;
}
#ev-vendor-sidebar .ev-item:hover { background: #f3f0ff; color: #6d28d9; }
#ev-vendor-sidebar .ev-item:hover svg { color: #7c3aed; }
#ev-vendor-sidebar .ev-item.ev-active {
    background: #ede9fe;
    color: #6d28d9;
    font-weight: 600;
}
#ev-vendor-sidebar .ev-item.ev-active svg { color: #7c3aed; }

#ev-vendor-sidebar .ev-item svg {
    width: 20px; height: 20px;
    flex-shrink: 0;
    color: #9ca3af;
    transition: color .18s ease;
}

#ev-vendor-sidebar.ev-collapsed .ev-item {
    justify-content: center;
    padding: 11px;
    gap: 0;
}

/* ─── Item label ────────────────────────────────────────────── */
#ev-vendor-sidebar .ev-item-label {
    flex: 1;
    overflow: hidden;
    transition: opacity .15s ease, max-width .22s ease;
    max-width: 140px;
}
#ev-vendor-sidebar.ev-collapsed .ev-item-label { opacity: 0; max-width: 0; pointer-events: none; }

/* ─── Badge ─────────────────────────────────────────────────── */
#ev-vendor-sidebar .ev-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px; height: 22px;
    padding: 0 6px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
    transition: opacity .15s ease;
}
#ev-vendor-sidebar.ev-collapsed .ev-badge { opacity: 0; width: 0; padding: 0; min-width: 0; overflow: hidden; }

#ev-vendor-sidebar .ev-badge-orange  { background: #f97316; }
#ev-vendor-sidebar .ev-badge-purple  { background: #7c3aed; }
#ev-vendor-sidebar .ev-badge-green   { background: #16a34a; color: #fff; }
#ev-vendor-sidebar .ev-badge-amber   { background: #d97706; color: #fff; }
#ev-vendor-sidebar .ev-badge-rose    { background: #e11d48; color: #fff; }
#ev-vendor-sidebar .ev-badge-gray    { background: #6b7280; color: #fff; }

#ev-vendor-sidebar .ev-badge-dot {
    display: none;
    position: absolute;
    top: 7px; right: 7px;
    width: 8px; height: 8px;
    border-radius: 50%;
    border: 2px solid #fff;
}
#ev-vendor-sidebar.ev-collapsed .ev-badge-dot { display: block; }
#ev-vendor-sidebar .ev-badge-dot.orange  { background: #f97316; }
#ev-vendor-sidebar .ev-badge-dot.purple  { background: #7c3aed; }
#ev-vendor-sidebar .ev-badge-dot.green   { background: #16a34a; }
#ev-vendor-sidebar .ev-badge-dot.amber   { background: #d97706; }
#ev-vendor-sidebar .ev-badge-dot.rose    { background: #e11d48; }

/* ─── Tooltip ───────────────────────────────────────────────── */
#ev-vendor-sidebar .ev-tip {
    position: absolute;
    left: calc(100% + 10px);
    top: 50%; transform: translateY(-50%);
    background: #1e1b4b;
    color: #e0e7ff;
    font-size: 12px;
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 7px;
    white-space: nowrap;
    pointer-events: none;
    opacity: 0;
    transition: opacity .15s ease;
    z-index: 9999;
    box-shadow: 0 4px 14px rgba(0,0,0,.2);
}
#ev-vendor-sidebar .ev-tip::before {
    content: '';
    position: absolute;
    right: 100%; top: 50%; transform: translateY(-50%);
    border: 5px solid transparent;
    border-right-color: #1e1b4b;
}
#ev-vendor-sidebar.ev-collapsed .ev-item:hover .ev-tip { opacity: 1; }

/* ─── Sign-out section ──────────────────────────────────────── */
#ev-vendor-sidebar .ev-signout-wrap {
    flex-shrink: 0;
    padding: 10px 10px 18px;
    border-top: 1px solid #f3f4f6;
    margin-top: auto;
}
#ev-vendor-sidebar .ev-signout-wrap .ev-item { color: #ef4444; }
#ev-vendor-sidebar .ev-signout-wrap .ev-item svg { color: #ef4444; }
#ev-vendor-sidebar .ev-signout-wrap .ev-item:hover { background: #fef2f2; color: #dc2626; }
#ev-vendor-sidebar .ev-signout-wrap .ev-item:hover svg { color: #dc2626; }

/* ─── Overlay (mobile) ──────────────────────────────────────── */
#ev-vendor-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 39;
    background: rgba(0,0,0,.35);
    backdrop-filter: blur(2px);
}
#ev-vendor-overlay.ev-show { display: block; }
</style>

<div class="sticky top-0 z-50 flex items-center justify-between border-b border-gray-200 bg-white/95 px-4 py-3 shadow-sm backdrop-blur dark:border-gray-700 dark:bg-gray-900/95 sm:px-6 md:hidden">
    <button
        type="button"
        id="ev-vendor-mobile-toggle"
        class="inline-flex min-h-11 items-center gap-2 rounded-lg px-3 font-semibold text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#8D85EC] dark:text-white dark:hover:bg-gray-800 md:hidden"
        aria-label="Open vendor navigation"
        aria-expanded="false"
        aria-controls="ev-vendor-sidebar"
    >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        Menu
    </button>
    <span class="text-sm font-bold text-gray-900 dark:text-white">Organizer Hub</span>
    @if (!request()->routeIs('vendor.dashboard'))
    <button id="theme-toggle" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-[#8D85EC]" aria-label="Toggle theme">
        <svg id="icon-moon" class="h-5 w-5 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20" style="display: none;" aria-hidden="true">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
        </svg>
        <svg id="icon-sun" class="h-5 w-5 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 20 20" style="display: none;" aria-hidden="true">
            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z"/>
        </svg>
    </button>
    @endif
</div>

{{-- ══════════════ SIDEBAR MARKUP ══════════════ --}}
<aside id="ev-vendor-sidebar" aria-label="Vendor Navigation">

    {{-- Top bar --}}
    <div class="ev-topbar">
        <div class="ev-brand">
            <img src="{{ asset('images/eventify-logo.png') }}" alt="Eventify Logo">
            <span class="ev-brand-name">Eventify</span>
        </div>
        <button id="ev-vendor-toggle" class="ev-hamburger" aria-label="Toggle sidebar">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    {{-- Profile card --}}
    <a href="{{ route('profile.show') }}" class="ev-profile">
        <div class="ev-avatar-wrap">
            @if(Auth::user()->profile_photo_url)
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="ev-avatar-init" style="display:none;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            @else
                <div class="ev-avatar-init">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            @endif
            <span class="ev-online-dot"></span>
        </div>
        <div class="ev-profile-info">
            <div class="ev-profile-name">{{ Auth::user()->name ?? 'Vendor' }}</div>
            <div class="ev-profile-role">Vendor account</div>
        </div>
        <svg class="ev-profile-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

    {{-- Section label --}}
    <div class="ev-section-label">Main</div>

    {{-- Navigation --}}
    <nav class="ev-nav">

        {{-- Dashboard --}}
        <a href="{{ route('vendor.dashboard') }}"
           class="ev-item {{ request()->routeIs('vendor.dashboard') ? 'ev-active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="ev-item-label">Dashboard</span>
            <span class="ev-tip">Dashboard</span>
        </a>

        {{-- My Events --}}
        <a href="{{ route('vendor.events.index') }}"
           class="ev-item {{ request()->routeIs('vendor.events.*') ? 'ev-active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="ev-item-label">My Events</span>
            <span class="ev-tip">My Events</span>
        </a>

        {{-- My Event Booking --}}
        <a href="{{ route('vendor.eventbooking') }}"
           class="ev-item {{ request()->routeIs('vendor.eventbooking') ? 'ev-active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span class="ev-item-label">My Event Booking</span>
            <span class="ev-tip">My Event Booking</span>
        </a>

        {{-- Event Booking Report --}}
        <a href="{{ route('vendor.reports.eventbooking') }}"
           class="ev-item {{ request()->routeIs('vendor.reports.eventbooking') ? 'ev-active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="ev-item-label">Event Booking Report</span>
            <span class="ev-tip">Event Booking Report</span>
        </a>

        {{-- Inquiries --}}
        <a href="{{ route('vendor.inquiries.index') }}"
           class="ev-item {{ request()->routeIs('vendor.inquiries.*') ? 'ev-active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="ev-item-label">Inquiries</span>
            @if($vendorUnreadInquiries > 0)
                <span class="ev-badge ev-badge-purple">{{ $vendorUnreadInquiries }}</span>
                <span class="ev-badge-dot purple"></span>
            @endif
            <span class="ev-tip">Inquiries{{ $vendorUnreadInquiries > 0 ? ' ('.$vendorUnreadInquiries.')' : '' }}</span>
        </a>

       

        {{-- KYC Verification --}}
        <a href="{{ route('vendor.kyc.index') }}"
           class="ev-item {{ request()->routeIs('vendor.kyc.*') ? 'ev-active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span class="ev-item-label">KYC Verification</span>
            {{-- KYC status badge --}}
            @if($sidebarKycStatus === 'approved')
                <span class="ev-badge ev-badge-green">✓</span>
                <span class="ev-badge-dot green"></span>
            @elseif($sidebarKycStatus === 'pending')
                <span class="ev-badge ev-badge-amber">•••</span>
                <span class="ev-badge-dot amber"></span>
            @elseif($sidebarKycStatus === 'rejected')
                <span class="ev-badge ev-badge-rose">!</span>
                <span class="ev-badge-dot rose"></span>
            @else
                <span class="ev-badge ev-badge-amber">Req</span>
                <span class="ev-badge-dot amber"></span>
            @endif
            <span class="ev-tip">KYC Verification</span>
        </a>

    </nav>

    {{-- Sign Out --}}
    <div class="ev-signout-wrap">
        <form action="{{ route('vendor.vendorLogout') }}" method="POST">
            @csrf
            <button type="submit" class="ev-item" title="Sign Out">
                <svg fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="ev-item-label">Sign Out</span>
                <span class="ev-tip">Sign Out</span>
            </button>
        </form>
    </div>

</aside>

{{-- Overlay (mobile) --}}
<div id="ev-vendor-overlay"></div>

<script>
(function () {
    'use strict';
    const KEY      = 'ev_vendor_sidebar_collapsed';
    const MOBILE   = 768;
    const sidebar  = document.getElementById('ev-vendor-sidebar');
    const toggle   = document.getElementById('ev-vendor-toggle');
    const overlay  = document.getElementById('ev-vendor-overlay');

    const isMobile = () => window.innerWidth < MOBILE;
    const getPref  = () => { try { return localStorage.getItem(KEY) === 'true'; } catch { return false; } };
    const setPref  = v  => { try { localStorage.setItem(KEY, v ? 'true' : 'false'); } catch {} };

    function applyState(collapsed, animate) {
        if (!animate) sidebar.style.transition = 'none';
        if (collapsed) {
            sidebar.classList.add('ev-collapsed');
            if (isMobile()) { sidebar.style.transform = 'translateX(-100%)'; overlay.classList.remove('ev-show'); }
            else              { sidebar.style.transform = 'translateX(0)'; overlay.classList.remove('ev-show'); }
        } else {
            sidebar.classList.remove('ev-collapsed');
            sidebar.style.transform = 'translateX(0)';
            if (isMobile()) overlay.classList.add('ev-show');
            else             overlay.classList.remove('ev-show');
        }
        const mobileToggle = document.getElementById('ev-vendor-mobile-toggle');
        if (mobileToggle) {
            const expanded = isMobile() && !collapsed;
            mobileToggle.setAttribute('aria-expanded', String(expanded));
            mobileToggle.setAttribute('aria-label', expanded ? 'Close dashboard navigation' : 'Open dashboard navigation');
        }
        syncMargin(collapsed);
        if (!animate) requestAnimationFrame(() => requestAnimationFrame(() => { sidebar.style.transition = ''; }));
    }

    function toggleSidebar() {
        collapsed = !collapsed;
        if (!isMobile()) setPref(collapsed);
        applyState(collapsed, true);
    }

    function syncMargin(collapsed) {
        let s = document.getElementById('ev-vendor-margin-style');
        if (!s) { s = document.createElement('style'); s.id = 'ev-vendor-margin-style'; document.head.appendChild(s); }
        const t = 'transition:margin-left .28s cubic-bezier(.4,0,.2,1);';
        if (isMobile()) {
            s.textContent = '[class*="sm:ml-64"],[class*="ml-72"]{margin-left:0!important;}';
        } else if (collapsed) {
            s.textContent = `[class*="sm:ml-64"],[class*="ml-72"]{margin-left:72px!important;${t}}`;
        } else {
            s.textContent = `[class*="sm:ml-64"],[class*="ml-72"]{margin-left:260px!important;${t}}`;
        }
    }

    let collapsed = isMobile() ? true : getPref();

    toggle.addEventListener('click', toggleSidebar);
    document.addEventListener('click', event => {
        if (event.target instanceof Element && event.target.closest('#ev-vendor-mobile-toggle')) {
            toggleSidebar();
        }
    });
    overlay.addEventListener('click', () => { collapsed = true; applyState(collapsed, true); });
    window.addEventListener('resize', () => { collapsed = isMobile() ? true : getPref(); applyState(collapsed, false); });

    applyState(collapsed, false);
    window.__evVendorSidebar = { toggle: toggleSidebar };
})();
</script>