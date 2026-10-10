@extends('layouts.app')

@section('title', 'Vendor KYC Verification')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar')

<div class="ml-0 sm:ml-64 p-4 sm:p-8 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-700">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-[#8d85ec] flex items-center justify-center font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">KYC Verification</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Verify your organizer identity to publish events and list venues on Eventify.</p>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div>
                @if(!$kyc || $kyc->isNotSubmitted())
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200 dark:border-amber-700">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Action Required: Not Submitted
                    </span>
                @elseif($kyc->isPending())
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                        Under Review (Pending)
                    </span>
                @elseif($kyc->isApproved())
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 border border-green-200 dark:border-green-700">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        KYC Verified & Approved
                    </span>
                @elseif($kyc->isRejected())
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300 border border-rose-200 dark:border-rose-700">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Verification Rejected
                    </span>
                @endif
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('warning'))
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 text-amber-800 dark:text-amber-200 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="text-sm font-medium">{{ session('warning') }}</div>
            </div>
        @endif

        {{-- STATE 1: APPROVED --}}
        @if($kyc && $kyc->isApproved())
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-800 dark:to-emerald-950/30 p-6 sm:p-8 rounded-2xl border border-green-200 dark:border-green-800 shadow-sm">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-green-500 text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-500/20">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Your KYC Verification is Approved!</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Your organizer account has been officially verified. You have full access to publish events, manage venues, and accept bookings on Eventify.
                        </p>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 bg-white/80 dark:bg-gray-800/80 p-4 rounded-xl border border-green-100 dark:border-gray-700">
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Business / Org</span>
                                <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $kyc->business_name }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Document Type</span>
                                <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $kyc->document_type }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block font-medium">Verified On</span>
                                <span class="text-sm font-bold text-green-700 dark:text-green-400">{{ optional($kyc->approved_at)->format('M d, Y') ?? 'Verified' }}</span>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3 justify-center sm:justify-start">
                            <a href="{{ route('vendor.events.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#8d85ec] hover:bg-[#7b76e4] text-white text-sm font-semibold shadow-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Create New Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        {{-- STATE 2: PENDING REVIEW --}}
        @elseif($kyc && $kyc->isPending())
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-indigo-950/30 p-6 sm:p-8 rounded-2xl border border-blue-200 dark:border-blue-800 shadow-sm">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-[#8d85ec] text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-purple-500/20">
                        <svg class="w-8 h-8 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-900/60 text-[#8d85ec] mb-2">
                            Application Under Review
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Your KYC Verification is Under Review</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Our compliance team is currently reviewing your submitted verification documents. You will receive an email notification once your verification is completed.
                        </p>

                        <div class="mt-6 bg-white/80 dark:bg-gray-800/80 p-5 rounded-xl border border-blue-100 dark:border-gray-700">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">Submitted Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-left">
                                <div>
                                    <span class="text-xs text-gray-400 block">Business / Org Name</span>
                                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $kyc->business_name }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">PAN / VAT</span>
                                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $kyc->pan_vat_number ?? 'Not provided' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Document Type</span>
                                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $kyc->document_type }}</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center gap-3">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Attached Documents:</span>
                                @if($kyc->document_front)
                                    <a href="{{ route('vendor.kyc.document', ['kyc' => $kyc->id, 'type' => 'front']) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 transition">
                                        <svg class="w-3.5 h-3.5 text-[#8d85ec]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Front Doc
                                    </a>
                                @endif
                                @if($kyc->document_back)
                                    <a href="{{ route('vendor.kyc.document', ['kyc' => $kyc->id, 'type' => 'back']) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 transition">
                                        <svg class="w-3.5 h-3.5 text-[#8d85ec]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Back Doc
                                    </a>
                                @endif
                                @if($kyc->company_registration_doc)
                                    <a href="{{ route('vendor.kyc.document', ['kyc' => $kyc->id, 'type' => 'company']) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 transition">
                                        <svg class="w-3.5 h-3.5 text-[#8d85ec]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Registration Doc
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Submitted on: {{ $kyc->created_at->format('M d, Y h:i A') }} ({{ $kyc->created_at->diffForHumans() }})
                            </span>
                            <button type="button" onclick="document.getElementById('kyc-form-card').scrollIntoView({ behavior: 'smooth' });" class="text-xs font-semibold text-[#8d85ec] hover:underline">
                                Update / Edit Submission &darr;
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        {{-- STATE 3: REJECTED --}}
        @elseif($kyc && $kyc->isRejected())
            <div class="bg-gradient-to-br from-rose-50 to-red-50 dark:from-gray-800 dark:to-rose-950/30 p-6 sm:p-8 rounded-2xl border border-rose-200 dark:border-rose-800 shadow-sm">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-rose-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">KYC Verification Rejected</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Our team reviewed your submission but could not approve it. Please review the feedback below, make the necessary corrections, and resubmit your documents.
                        </p>

                        <div class="mt-4 p-4 rounded-xl bg-white/90 dark:bg-gray-800/90 border-l-4 border-rose-500 text-left">
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 block mb-1">Reason for Rejection:</span>
                            <p class="text-sm font-medium text-rose-900 dark:text-rose-200">{{ $kyc->rejection_reason }}</p>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3 justify-center sm:justify-start">
                            <a href="{{ route('vendor.kyc.resubmit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Resubmit KYC Documents &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- FORM CARD (Show for not_submitted, rejected, or collapsible for updating) --}}
        <div id="kyc-form-card" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200/80 dark:border-gray-700 p-6 sm:p-8" x-data="kycFormManager()">
            <div class="border-b border-gray-100 dark:border-gray-700 pb-5 mb-6">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        @if($kyc && $kyc->isRejected())
                            Resubmit KYC Documents
                        @elseif($kyc && $kyc->isPending())
                            Update Submitted Documents
                        @elseif($kyc && $kyc->isApproved())
                            Verified Details
                        @else
                            Submit KYC Documents
                        @endif
                    </h2>

                    @if($kyc && $kyc->isApproved())
                        <button type="button"
                                x-show="!isEditing"
                                @click="isEditing = true"
                                class="inline-flex items-center gap-2 rounded-xl border border-[#8d85ec] bg-[#8d85ec]/10 px-4 py-2 text-xs font-semibold text-[#8d85ec] hover:bg-[#8d85ec]/15 transition">
                            Edit Verification Details
                        </button>
                        <button type="button"
                                x-show="isEditing"
                                @click="isEditing = false"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Cancel Edit
                        </button>
                    @endif
                </div>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Please provide accurate legal business information and clear scans or photos of your identification documents.
                </p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300">
                    <div class="font-semibold text-sm mb-1">Please correct the errors below:</div>
                    <ul class="list-disc pl-5 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div x-show="!approvedLocked || isEditing">
                <form action="{{ route('vendor.kyc.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Business / Organization Name -->
                    <div>
                        <label for="business_name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            Business / Organization Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="business_name" 
                               id="business_name" 
                               value="{{ old('business_name', $kyc->business_name ?? $user->name) }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8d85ec] focus:border-transparent transition text-sm"
                               placeholder="e.g. Apex Events & Media Pvt. Ltd." 
                               required>
                    </div>

                    <!-- PAN / VAT Number -->
                    <div>
                        <label for="pan_vat_number" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                            PAN / VAT Number <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <input type="text" 
                               name="pan_vat_number" 
                               id="pan_vat_number" 
                               value="{{ old('pan_vat_number', $kyc->pan_vat_number ?? '') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8d85ec] focus:border-transparent transition text-sm"
                               placeholder="e.g. 600123456">
                    </div>
                </div>

                <!-- Document Type Selection -->
                <div>
                    <label for="document_type" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">
                        Identification Document Type <span class="text-red-500">*</span>
                    </label>
                    <select name="document_type" 
                            id="document_type" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#8d85ec] focus:border-transparent transition text-sm"
                            required>
                        <option value="">-- Select Document Type --</option>
                        @php
                            $selectedDoc = old('document_type', $kyc->document_type ?? 'Citizenship');
                        @endphp
                        <option value="Citizenship" {{ $selectedDoc === 'Citizenship' ? 'selected' : '' }}>Citizenship Certificate</option>
                        <option value="Passport" {{ $selectedDoc === 'Passport' ? 'selected' : '' }}>Passport</option>
                        <option value="Business Registration" {{ $selectedDoc === 'Business Registration' ? 'selected' : '' }}>Company / Business Registration Certificate</option>
                        <option value="National ID" {{ $selectedDoc === 'National ID' ? 'selected' : '' }}>National Identity Card (NID)</option>
                        <option value="Driving License" {{ $selectedDoc === 'Driving License' ? 'selected' : '' }}>Driving License</option>
                    </select>
                </div>

                <!-- File Uploads Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                    
                    <!-- 1. Document Front -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Document Front Page <span class="text-red-500">*</span>
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-[#8d85ec] dark:hover:border-[#8d85ec] rounded-2xl p-4 text-center cursor-pointer transition bg-gray-50/50 dark:bg-gray-700/30 group min-h-[170px] flex flex-col items-center justify-center"
                             @click="$refs.frontInput.click()">
                            <input type="file" 
                                   name="document_front" 
                                   x-ref="frontInput"
                                   @change="handleFileChange($event, 'front')"
                                   accept="image/jpeg,image/png,image/jpg,application/pdf"
                                   class="hidden">

                            <template x-if="!previews.front && !existingFiles.front">
                                <div>
                                    <svg class="mx-auto h-9 w-9 text-gray-400 group-hover:text-[#8d85ec] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-xs font-semibold text-gray-700 dark:text-gray-300">Click to upload Front</p>
                                    <p class="text-[11px] text-gray-400">JPG, PNG or PDF (Max 5MB)</p>
                                </div>
                            </template>

                            <!-- Existing File Preview -->
                            <template x-if="!previews.front && existingFiles.front">
                                <div class="w-full">
                                    <template x-if="existingFiles.front.is_pdf">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">PDF</div>
                                            <span class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Front Page (Uploaded)</span>
                                        </div>
                                    </template>
                                    <template x-if="!existingFiles.front.is_pdf && !existingFiles.front.unavailable">
                                        <img :src="existingFiles.front.url" x-on:error="existingFiles.front.unavailable = true" class="h-24 mx-auto rounded-lg object-cover shadow-sm">
                                    </template>
                                    <template x-if="existingFiles.front.unavailable">
                                        <p class="text-xs text-amber-700 dark:text-amber-300">Preview unavailable. Please upload this document again.</p>
                                    </template>
                                    <span class="text-[11px] text-[#8d85ec] font-semibold block mt-1">Click to replace</span>
                                </div>
                            </template>

                            <!-- New File Preview -->
                            <template x-if="previews.front">
                                <div class="w-full">
                                    <template x-if="previews.front.is_pdf">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">PDF</div>
                                            <span class="text-xs text-gray-700 dark:text-gray-200 mt-1 truncate max-w-[180px]" x-text="previews.front.name"></span>
                                        </div>
                                    </template>
                                    <template x-if="!previews.front.is_pdf">
                                        <img :src="previews.front.url" class="h-24 mx-auto rounded-lg object-cover shadow-sm">
                                    </template>
                                    <span class="text-[11px] text-green-600 font-semibold block mt-1">Selected: <span x-text="previews.front.name"></span></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 2. Document Back -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Document Back Page <span class="text-red-500">*</span>
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-[#8d85ec] dark:hover:border-[#8d85ec] rounded-2xl p-4 text-center cursor-pointer transition bg-gray-50/50 dark:bg-gray-700/30 group min-h-[170px] flex flex-col items-center justify-center"
                             @click="$refs.backInput.click()">
                            <input type="file" 
                                   name="document_back" 
                                   x-ref="backInput"
                                   @change="handleFileChange($event, 'back')"
                                   accept="image/jpeg,image/png,image/jpg,application/pdf"
                                   class="hidden">

                            <template x-if="!previews.back && !existingFiles.back">
                                <div>
                                    <svg class="mx-auto h-9 w-9 text-gray-400 group-hover:text-[#8d85ec] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-xs font-semibold text-gray-700 dark:text-gray-300">Click to upload Back</p>
                                    <p class="text-[11px] text-gray-400">JPG, PNG or PDF (Max 5MB)</p>
                                </div>
                            </template>

                            <template x-if="!previews.back && existingFiles.back">
                                <div class="w-full">
                                    <template x-if="existingFiles.back.is_pdf">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">PDF</div>
                                            <span class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Back Page (Uploaded)</span>
                                        </div>
                                    </template>
                                    <template x-if="!existingFiles.back.is_pdf && !existingFiles.back.unavailable">
                                        <img :src="existingFiles.back.url" x-on:error="existingFiles.back.unavailable = true" class="h-24 mx-auto rounded-lg object-cover shadow-sm">
                                    </template>
                                    <template x-if="existingFiles.back.unavailable">
                                        <p class="text-xs text-amber-700 dark:text-amber-300">Preview unavailable. Please upload this document again.</p>
                                    </template>
                                    <span class="text-[11px] text-[#8d85ec] font-semibold block mt-1">Click to replace</span>
                                </div>
                            </template>

                            <template x-if="previews.back">
                                <div class="w-full">
                                    <template x-if="previews.back.is_pdf">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">PDF</div>
                                            <span class="text-xs text-gray-700 dark:text-gray-200 mt-1 truncate max-w-[180px]" x-text="previews.back.name"></span>
                                        </div>
                                    </template>
                                    <template x-if="!previews.back.is_pdf">
                                        <img :src="previews.back.url" class="h-24 mx-auto rounded-lg object-cover shadow-sm">
                                    </template>
                                    <span class="text-[11px] text-green-600 font-semibold block mt-1">Selected: <span x-text="previews.back.name"></span></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 3. Company Registration Document -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Company Registration / Certificate <span class="text-gray-400 font-normal">(Optional)</span>
                        </label>
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-[#8d85ec] dark:hover:border-[#8d85ec] rounded-2xl p-4 text-center cursor-pointer transition bg-gray-50/50 dark:bg-gray-700/30 group min-h-[170px] flex flex-col items-center justify-center"
                             @click="$refs.compInput.click()">
                            <input type="file" 
                                   name="company_registration_doc" 
                                   x-ref="compInput"
                                   @change="handleFileChange($event, 'company')"
                                   accept="image/jpeg,image/png,image/jpg,application/pdf"
                                   class="hidden">

                            <template x-if="!previews.company && !existingFiles.company">
                                <div>
                                    <svg class="mx-auto h-9 w-9 text-gray-400 group-hover:text-[#8d85ec] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-xs font-semibold text-gray-700 dark:text-gray-300">Click to upload Certificate</p>
                                    <p class="text-[11px] text-gray-400">JPG, PNG or PDF (Max 5MB)</p>
                                </div>
                            </template>

                            <template x-if="!previews.company && existingFiles.company">
                                <div class="w-full">
                                    <template x-if="existingFiles.company.is_pdf">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">PDF</div>
                                            <span class="text-xs text-gray-600 dark:text-gray-300 mt-1 font-medium">Registration Doc (Uploaded)</span>
                                        </div>
                                    </template>
                                    <template x-if="!existingFiles.company.is_pdf && !existingFiles.company.unavailable">
                                        <img :src="existingFiles.company.url" x-on:error="existingFiles.company.unavailable = true" class="h-24 mx-auto rounded-lg object-cover shadow-sm">
                                    </template>
                                    <template x-if="existingFiles.company.unavailable">
                                        <p class="text-xs text-amber-700 dark:text-amber-300">Preview unavailable. Please upload this document again.</p>
                                    </template>
                                    <span class="text-[11px] text-[#8d85ec] font-semibold block mt-1">Click to replace</span>
                                </div>
                            </template>

                            <template x-if="previews.company">
                                <div class="w-full">
                                    <template x-if="previews.company.is_pdf">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">PDF</div>
                                            <span class="text-xs text-gray-700 dark:text-gray-200 mt-1 truncate max-w-[180px]" x-text="previews.company.name"></span>
                                        </div>
                                    </template>
                                    <template x-if="!previews.company.is_pdf">
                                        <img :src="previews.company.url" class="h-24 mx-auto rounded-lg object-cover shadow-sm">
                                    </template>
                                    <span class="text-[11px] text-green-600 font-semibold block mt-1">Selected: <span x-text="previews.company.name"></span></span>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- Guidelines Notice Box -->
                <div class="p-4 rounded-xl bg-purple-50/60 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/40 text-xs text-gray-600 dark:text-gray-300 flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#8d85ec] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <span class="font-bold text-gray-800 dark:text-gray-200">Guidelines for Quick Approval:</span>
                        <ul class="list-disc pl-4 mt-1 space-y-0.5 text-gray-600 dark:text-gray-400">
                            <li>Ensure all four corners of the identity document are clearly visible without glare or blur.</li>
                            <li>Organization/Business name should match the name registered on tax or identity documents.</li>
                            <li>Files must be under 5MB each in JPEG, PNG, or PDF format.</li>
                        </ul>
                    </div>
                </div>

                    <!-- Submit Button -->
                    @if($kyc && $kyc->isApproved())
                        <div class="flex justify-end pt-2" x-show="isEditing">
                            <button type="submit"
                                    class="px-8 py-3 rounded-xl bg-[#8d85ec] hover:bg-[#7b76e4] text-white font-bold text-sm shadow-md hover:shadow-lg transition transform active:scale-98">
                                Save Changes &amp; Submit for Review
                            </button>
                        </div>
                    @else
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                    class="px-8 py-3 rounded-xl bg-[#8d85ec] hover:bg-[#7b76e4] text-white font-bold text-sm shadow-md hover:shadow-lg transition transform active:scale-98">
                                @if($kyc && ($kyc->isRejected() || $kyc->isPending()))
                                    Resubmit Verification Documents
                                @else
                                    Submit KYC for Verification
                                @endif
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function kycFormManager() {
    return {
        approvedLocked: @json($kyc && $kyc->isApproved()),
        isEditing: false,
        previews: {
            front: null,
            back: null,
            company: null,
        },
        existingFiles: {
            front: @json($kyc && $kyc->document_front ? ['url' => $kyc->document_front_url, 'is_pdf' => \App\Models\VendorKyc::isPdf($kyc->document_front)] : null),
            back: @json($kyc && $kyc->document_back ? ['url' => $kyc->document_back_url, 'is_pdf' => \App\Models\VendorKyc::isPdf($kyc->document_back)] : null),
            company: @json($kyc && $kyc->company_registration_doc ? ['url' => $kyc->company_registration_doc_url, 'is_pdf' => \App\Models\VendorKyc::isPdf($kyc->company_registration_doc)] : null),
        },
        handleFileChange(event, type) {
            const file = event.target.files[0];
            if (!file) return;

            const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
            if (isPdf) {
                this.previews[type] = {
                    is_pdf: true,
                    name: file.name,
                    url: null
                };
            } else {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews[type] = {
                        is_pdf: false,
                        name: file.name,
                        url: e.target.result
                    };
                };
                reader.readAsDataURL(file);
            }
        }
    };
}
</script>
@endsection
