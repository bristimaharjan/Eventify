@extends('layouts.app')

@section('title', 'My Profile')
@php 
    $noFooter = true; 
@endphp

@section('content')
<div x-data="{ openChange: false, openForgot: false, confirmDeletePhoto: false }" class="min-h-[calc(100vh-80px)] bg-[#f6f8fd] dark:bg-gray-900 relative overflow-hidden py-10 px-4 sm:px-6 lg:px-8">

    <!-- Ambient Decorative Gradients -->
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 bg-purple-200/40 dark:bg-purple-900/20 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-200/30 dark:bg-indigo-900/20 rounded-full blur-3xl"></div>

    <div class="max-w-4xl mx-auto relative z-10 space-y-6">

        <!-- Error Messages -->

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition 
                 class="p-4 rounded-2xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 shadow-xs">
                <div class="flex items-center gap-3 mb-1">
                    <iconify-icon icon="solar:danger-circle-bold" class="text-2xl text-red-600 dark:text-red-400 shrink-0"></iconify-icon>
                    <span class="text-sm font-bold">Please fix the following issues:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-7">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Header -->
        <div class="mb-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1a2340] dark:text-white tracking-tight">
                My Profile
            </h1>
            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">
                Manage your account and keep your information up to date.
            </p>
        </div>

        <!-- Main Profile Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700/60">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" 
                  class="flex flex-col md:flex-row gap-8 items-stretch md:items-center">
                @csrf

                <!-- Left Column: Avatar & Change/Remove Photo -->
                <div class="w-full md:w-72 lg:w-80 bg-[#f4f6fe] dark:bg-gray-700/40 rounded-2xl p-6 sm:p-8 flex flex-col items-center justify-center relative overflow-hidden shrink-0 border border-purple-50 dark:border-gray-600/30">
                    
                    <!-- Subtle background wave decorations -->
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-purple-100/60 dark:bg-purple-800/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-indigo-100/60 dark:bg-indigo-800/20 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Avatar with Camera Badge -->
                    <div class="relative group cursor-pointer" onclick="document.getElementById('profile_photo').click()">
                        <div class="w-36 h-36 sm:w-40 sm:h-40 rounded-full overflow-hidden ring-4 ring-white dark:ring-gray-800 shadow-md bg-white flex items-center justify-center">
                            @if($user->profile_photo && $user->profile_photo_url)
                                <img id="photoPreview" 
                                     src="{{ $user->profile_photo_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-full h-full bg-gradient-to-br from-[#6961e2] to-[#8D85EC] text-white items-center justify-center font-bold text-4xl shadow-inner">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @else
                                <div id="photoPreview" class="w-full h-full bg-gradient-to-br from-[#6961e2] to-[#8D85EC] text-white flex items-center justify-center font-bold text-4xl shadow-inner">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Camera Badge Button -->
                        <button type="button" 
                                onclick="event.stopPropagation(); document.getElementById('profile_photo').click();"
                                title="Choose Photo"
                                class="absolute bottom-1 right-2 w-9 h-9 rounded-full bg-[#6961e2] hover:bg-[#5850d6] text-white flex items-center justify-center shadow-md transition transform hover:scale-110 active:scale-95 cursor-pointer">
                            <iconify-icon icon="solar:camera-minimalistic-bold" class="text-lg"></iconify-icon>
                        </button>
                    </div>

                    <!-- Hidden File Input -->
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="hidden">

                    <!-- Photo Action Buttons -->
                    <div class="mt-5 flex flex-wrap items-center justify-center gap-2">
                        <!-- Pill Change Photo Button -->
                        <button type="button" 
                                onclick="document.getElementById('profile_photo').click()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#e8ebff] hover:bg-[#dfe3fe] dark:bg-gray-700 dark:hover:bg-gray-600 text-[#6961e2] dark:text-[#a5a0f5] text-xs sm:text-sm font-semibold rounded-full shadow-2xs transition transform hover:scale-105 active:scale-95 cursor-pointer">
                            <iconify-icon icon="solar:camera-minimalistic-bold" class="text-base"></iconify-icon>
                            <span>Change</span>
                        </button>

                        <!-- Adjust Photo Button -->
                        <button type="button" 
                                id="btnAdjustPhoto"
                                onclick="openPhotoAdjuster()"
                                style="{{ ($user->profile_photo && $user->profile_photo_url) ? '' : 'display: none;' }}"
                                title="Adjust Photo Position (Move Up / Down)"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white dark:bg-gray-800 border border-[#6961e2]/40 hover:border-[#6961e2] hover:bg-[#f6f7ff] dark:hover:bg-gray-700 text-[#6961e2] dark:text-[#a5a0f5] text-xs sm:text-sm font-semibold rounded-full shadow-2xs transition transform hover:scale-105 active:scale-95 cursor-pointer">
                            <iconify-icon icon="solar:slider-vertical-bold" class="text-base"></iconify-icon>
                            <span>Adjust</span>
                        </button>

                        <!-- Remove Photo Button (Only shown if user has photo) -->
                        @if($user->profile_photo)
                            <button type="button" 
                                    id="btnRemovePhoto"
                                    @click="confirmDeletePhoto = true"
                                    title="Remove Profile Photo"
                                    class="inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-full transition transform hover:scale-105 active:scale-95 cursor-pointer shadow-2xs">
                                <iconify-icon icon="solar:trash-bin-trash-bold" class="text-base sm:text-lg"></iconify-icon>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Personal Information Form -->
                <div class="flex-1 flex flex-col justify-center space-y-6">
                    
                    <!-- Section Title -->
                    <div class="flex items-center gap-2.5">
                        <iconify-icon icon="solar:user-bold" class="text-2xl text-[#1a2340] dark:text-white"></iconify-icon>
                        <h2 class="text-xl font-bold text-[#1a2340] dark:text-white tracking-tight">Personal Information</h2>
                    </div>

                    <!-- Input Fields -->
                    <div class="space-y-4">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">
                                Name
                            </label>
                            <input type="text" 
                                   id="name"
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required
                                   class="w-full px-4 py-3 bg-[#fbfbfe] dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-800 dark:text-white text-sm sm:text-base focus:ring-2 focus:ring-[#6961e2]/25 focus:border-[#6961e2] focus:bg-white dark:focus:bg-gray-900 outline-none transition placeholder-gray-400">
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">
                                Email
                            </label>
                            <input type="email" 
                                   id="email"
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required
                                   class="w-full px-4 py-3 bg-[#fbfbfe] dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-800 dark:text-white text-sm sm:text-base focus:ring-2 focus:ring-[#6961e2]/25 focus:border-[#6961e2] focus:bg-white dark:focus:bg-gray-900 outline-none transition placeholder-gray-400">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                        <!-- Update Profile (Solid Purple) -->
                        <button type="submit" 
                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-8 py-3 bg-[#6961e2] hover:bg-[#5850d6] text-white text-sm sm:text-base font-semibold rounded-xl shadow-sm transition transform hover:scale-[1.02] active:scale-95 cursor-pointer">
                            <iconify-icon icon="solar:pen-2-bold" class="text-lg"></iconify-icon>
                            <span>Update Profile</span>
                        </button>

                        <!-- Change Password (Outline Purple) -->
                        <button type="button" 
                                @click="openChange = true"
                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-8 py-3 bg-white dark:bg-gray-800 border-2 border-[#6961e2] hover:bg-[#f5f6ff] dark:hover:bg-gray-700 text-[#6961e2] dark:text-[#a5a0f5] text-sm sm:text-base font-semibold rounded-xl shadow-2xs transition transform hover:scale-[1.02] active:scale-95 cursor-pointer">
                            <iconify-icon icon="solar:lock-keyhole-bold" class="text-lg"></iconify-icon>
                            <span>Change Password</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>

    <!-- Hidden Remove Photo Form -->
    <form id="delete-photo-form" action="{{ route('profile.photo.delete') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- ======================================================== -->
    <!-- Confirm Delete Photo Modal                               -->
    <!-- ======================================================== -->
    <div x-show="confirmDeletePhoto" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        
        <div @click.away="confirmDeletePhoto = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-3xl w-full max-w-sm shadow-2xl relative border border-gray-100 dark:border-gray-700 text-center">
            
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center mx-auto mb-4">
                <iconify-icon icon="solar:trash-bin-trash-bold" class="text-3xl"></iconify-icon>
            </div>

            <h3 class="text-lg font-bold text-[#1a2340] dark:text-white mb-1">Remove Profile Photo?</h3>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-6">
                Are you sure you want to remove your profile photo? Your avatar will revert to the default letter icon.
            </p>

            <div class="flex items-center justify-center gap-3">
                <button type="button" 
                        @click="confirmDeletePhoto = false" 
                        class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl text-sm transition cursor-pointer">
                    Cancel
                </button>
                <button type="button" 
                        onclick="document.getElementById('delete-photo-form').submit();" 
                        class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-sm transition shadow-sm cursor-pointer">
                    Yes, Remove
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- Change Password Modal                                    -->
    <!-- ======================================================== -->
    <div x-show="openChange" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        
        <div @click.away="openChange = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-3xl w-full max-w-md shadow-2xl relative border border-gray-100 dark:border-gray-700">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#eef0fe] text-[#6961e2] flex items-center justify-center">
                        <iconify-icon icon="solar:lock-keyhole-bold" class="text-xl"></iconify-icon>
                    </div>
                    <h3 class="text-xl font-bold text-[#1a2340] dark:text-white">Change Password</h3>
                </div>
                <button type="button" @click="openChange = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition cursor-pointer">
                    <iconify-icon icon="solar:close-circle-bold" class="text-2xl"></iconify-icon>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('vendor.password.change') }}" method="POST" class="space-y-4" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                @csrf
                <input type="hidden" name="email" value="{{ $user->email }}">
                
                <!-- Current Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200">Current Password</label>
                        <button type="button" @click="openForgot = true; openChange = false" class="text-xs text-[#6961e2] dark:text-[#a5a0f5] hover:underline font-medium cursor-pointer">
                            Forgot password?
                        </button>
                    </div>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password" required
                            class="w-full pl-4 pr-11 py-2.5 bg-[#fbfbfe] dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl dark:text-gray-200 focus:ring-2 focus:ring-[#6961e2]/25 focus:border-[#6961e2] outline-none text-sm">
                        <button type="button" 
                                @click="showCurrent = !showCurrent" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 cursor-pointer flex items-center justify-center">
                            <iconify-icon :icon="showCurrent ? 'solar:eye-bold' : 'solar:eye-closed-bold'" class="text-lg"></iconify-icon>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">New Password</label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" id="new_password" name="password" required
                            class="w-full pl-4 pr-11 py-2.5 bg-[#fbfbfe] dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl dark:text-gray-200 focus:ring-2 focus:ring-[#6961e2]/25 focus:border-[#6961e2] outline-none text-sm">
                        <button type="button" 
                                @click="showNew = !showNew" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 cursor-pointer flex items-center justify-center">
                            <iconify-icon :icon="showNew ? 'solar:eye-bold' : 'solar:eye-closed-bold'" class="text-lg"></iconify-icon>
                        </button>
                    </div>
                </div>

                <!-- Password Rules Checklist -->
                <ul class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-xs text-gray-600 dark:text-gray-400 space-y-1.5" id="password-rules">
                    <li id="rule-length" class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 border border-gray-400 rounded-full inline-block shrink-0"></span>
                        <span>At least 8 characters</span>
                    </li>
                    <li id="rule-uppercase" class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 border border-gray-400 rounded-full inline-block shrink-0"></span>
                        <span>At least one uppercase letter</span>
                    </li>
                    <li id="rule-number" class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 border border-gray-400 rounded-full inline-block shrink-0"></span>
                        <span>At least one number</span>
                    </li>
                    <li id="rule-special" class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 border border-gray-400 rounded-full inline-block shrink-0"></span>
                        <span>At least one special character (!@#$%^&*)</span>
                    </li>
                </ul>

                <!-- Confirm New Password -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" id="confirm_password" name="password_confirmation" required
                            class="w-full pl-4 pr-11 py-2.5 bg-[#fbfbfe] dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl dark:text-gray-200 focus:ring-2 focus:ring-[#6961e2]/25 focus:border-[#6961e2] outline-none text-sm">
                        <button type="button" 
                                @click="showConfirm = !showConfirm" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 cursor-pointer flex items-center justify-center">
                            <iconify-icon :icon="showConfirm ? 'solar:eye-bold' : 'solar:eye-closed-bold'" class="text-lg"></iconify-icon>
                        </button>
                    </div>
                    <p id="password-match" class="mt-2 text-xs font-medium"></p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button" @click="openChange = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl text-sm transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#6961e2] hover:bg-[#5850d6] text-white font-semibold rounded-xl text-sm transition shadow-sm cursor-pointer">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- Forgot Password Modal                                    -->
    <!-- ======================================================== -->
    <div x-show="openForgot" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        
        <div @click.away="openForgot = false" 
             class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-3xl w-full max-w-md shadow-2xl relative border border-gray-100 dark:border-gray-700">
            
            <h3 class="text-xl font-bold mb-3 text-[#1a2340] dark:text-white">Forgot Password</h3>
            <form id="forgotPasswordForm" class="space-y-4">
                @csrf
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Enter your email to receive a password reset link.
                </p>
                <input type="email" name="email" id="forgot_email" value="{{ $user->email }}" required
                    class="w-full px-4 py-2.5 bg-[#fbfbfe] dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl dark:text-gray-200 focus:ring-2 focus:ring-[#6961e2]/25 focus:border-[#6961e2] outline-none text-sm">
                <p id="forgot-feedback" class="text-xs mt-1"></p>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="openForgot = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-xl text-sm transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#6961e2] hover:bg-[#5850d6] text-white font-semibold rounded-xl text-sm transition shadow-sm cursor-pointer">
                        Send Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- Photo Position Adjuster Modal                            -->
    <!-- ======================================================== -->
    <div id="photoAdjustModal" 
         style="display: none;"
         class="fixed inset-0 bg-black/75 backdrop-blur-xs flex items-center justify-center z-50 p-4 overflow-y-auto">
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-md shadow-2xl relative border border-gray-100 dark:border-gray-700 overflow-hidden my-auto animate-scaleUp">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/80 flex items-center justify-between bg-gradient-to-r from-purple-50/60 to-indigo-50/60 dark:from-gray-800 dark:to-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#6961e2]/10 text-[#6961e2] dark:text-[#a5a0f5] flex items-center justify-center">
                        <iconify-icon icon="solar:slider-vertical-bold" class="text-xl"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-[#1a2340] dark:text-white leading-tight">Adjust Profile Photo</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Drag or slide to move photo up and down</p>
                    </div>
                </div>
                <button type="button" onclick="closePhotoAdjuster()" class="w-8 h-8 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center justify-center transition cursor-pointer text-xl">
                    &times;
                </button>
            </div>

            <!-- Viewport & Drag Canvas Area -->
            <div class="p-6 flex flex-col items-center select-none">
                
                <!-- Circular Crop Frame Area -->
                <div class="relative w-64 h-64 rounded-full overflow-hidden bg-gray-900 ring-4 ring-[#6961e2] shadow-xl flex items-center justify-center cursor-grab active:cursor-grabbing touch-none select-none" id="adjustViewport">
                    <img id="adjustImg" src="" alt="Adjust Photo" class="absolute max-w-none pointer-events-none transition-none" style="transform-origin: center center;" />
                    
                    <!-- Circular Guide Overlay / Vignette -->
                    <div class="absolute inset-0 rounded-full border border-white/30 pointer-events-none shadow-[inset_0_0_25px_rgba(0,0,0,0.45)]"></div>
                    
                    <!-- Visual center guidelines -->
                    <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 h-px bg-white/20 pointer-events-none"></div>
                    <div class="absolute inset-y-0 left-1/2 -translate-x-1/2 w-px bg-white/20 pointer-events-none"></div>
                </div>

                <div class="mt-2.5 flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                    <iconify-icon icon="solar:hand-shake-bold" class="text-sm text-[#6961e2]"></iconify-icon>
                    <span>Click & drag to move photo in any direction</span>
                </div>

                <!-- Controls Section -->
                <div class="w-full space-y-3.5 mt-4 bg-[#f8f9ff] dark:bg-gray-700/40 p-4 rounded-2xl border border-purple-50 dark:border-gray-600/30">
                    
                    <!-- Vertical Position Slider (Move Up / Down) -->
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1.5">
                            <span class="flex items-center gap-1.5">
                                <iconify-icon icon="solar:sort-vertical-bold" class="text-sm text-[#6961e2]"></iconify-icon>
                                Move Up / Down
                            </span>
                            <div class="flex items-center gap-1">
                                <button type="button" onclick="nudgePhotoY(-15)" title="Move Up" class="px-2 py-0.5 rounded bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-[#6961e2] hover:text-white border border-gray-200 dark:border-gray-600 text-xs font-bold transition cursor-pointer shadow-2xs">
                                    ↑ Up
                                </button>
                                <button type="button" onclick="nudgePhotoY(15)" title="Move Down" class="px-2 py-0.5 rounded bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-[#6961e2] hover:text-white border border-gray-200 dark:border-gray-600 text-xs font-bold transition cursor-pointer shadow-2xs">
                                    ↓ Down
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="solar:arrow-up-linear" class="text-sm text-gray-400"></iconify-icon>
                            <input type="range" id="adjustSliderY" min="-180" max="180" value="0" step="1" 
                                   class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-lg appearance-none cursor-pointer accent-[#6961e2]">
                            <iconify-icon icon="solar:arrow-down-linear" class="text-sm text-gray-400"></iconify-icon>
                        </div>
                    </div>

                    <!-- Zoom Slider -->
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1.5">
                            <span class="flex items-center gap-1.5">
                                <iconify-icon icon="solar:magnifer-zoom-in-bold" class="text-sm text-[#6961e2]"></iconify-icon>
                                Zoom Scale
                            </span>
                            <button type="button" onclick="resetPhotoAdjust()" class="text-xs text-[#6961e2] dark:text-[#a5a0f5] hover:underline font-semibold cursor-pointer">
                                ↺ Reset Center
                            </button>
                        </div>
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="solar:magnifer-zoom-out-linear" class="text-sm text-gray-400"></iconify-icon>
                            <input type="range" id="adjustSliderZoom" min="1" max="3" value="1" step="0.02" 
                                   class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-lg appearance-none cursor-pointer accent-[#6961e2]">
                            <iconify-icon icon="solar:magnifer-zoom-in-linear" class="text-sm text-gray-400"></iconify-icon>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Modal Footer Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closePhotoAdjuster()" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 font-semibold rounded-xl text-sm transition cursor-pointer">
                    Cancel
                </button>
                <button type="button" onclick="applyPhotoAdjust()" class="inline-flex items-center gap-1.5 px-5 py-2 bg-[#6961e2] hover:bg-[#5850d6] text-white font-semibold rounded-xl text-sm transition shadow-sm cursor-pointer transform hover:scale-[1.02] active:scale-95">
                    <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                    <span>Apply Position</span>
                </button>
            </div>

        </div>
    </div>

</div>

<!-- Profile Photo Adjuster, Preview & Password Validation Script -->
<script>
    const profileInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('photoPreview');
    const adjustViewport = document.getElementById('adjustViewport');
    const adjustImg = document.getElementById('adjustImg');

    let adjustState = {
        img: null,
        naturalW: 0,
        naturalH: 0,
        zoom: 1,
        baseScale: 1,
        panX: 0,
        panY: 0,
        viewportSize: 256,
        isDragging: false,
        startX: 0,
        startY: 0,
        initialPanX: 0,
        initialPanY: 0,
        currentSourceUrl: ''
    };

    function openPhotoAdjuster(srcUrl) {
        const modal = document.getElementById('photoAdjustModal');
        let source = srcUrl;
        if (!source) {
            const previewEl = document.getElementById('photoPreview');
            if (previewEl && previewEl.tagName === 'IMG' && previewEl.src) {
                source = previewEl.src;
            } else if (adjustState.currentSourceUrl) {
                source = adjustState.currentSourceUrl;
            }
        }
        
        if (!source) {
            profileInput.click();
            return;
        }

        adjustState.currentSourceUrl = source;
        modal.style.display = 'flex';

        const tempImg = new Image();
        tempImg.crossOrigin = 'anonymous';
        tempImg.onload = function() {
            adjustState.img = tempImg;
            adjustState.naturalW = tempImg.naturalWidth;
            adjustState.naturalH = tempImg.naturalHeight;
            
            adjustState.viewportSize = (adjustViewport && adjustViewport.clientWidth > 50) ? adjustViewport.clientWidth : 256;
            
            // Base scale to comfortably fill circular viewport
            adjustState.baseScale = Math.max(
                adjustState.viewportSize / adjustState.naturalW,
                adjustState.viewportSize / adjustState.naturalH
            );
            
            adjustState.zoom = 1;
            adjustState.panX = 0;
            adjustState.panY = 0;

            const sliderZoomEl = document.getElementById('adjustSliderZoom');
            const sliderYEl = document.getElementById('adjustSliderY');
            if (sliderZoomEl) sliderZoomEl.value = 1;
            if (sliderYEl) sliderYEl.value = 0;

            adjustImg.src = source;
            updateAdjustView();
        };
        tempImg.src = source;
    }

    function updateAdjustView() {
        if (!adjustImg || !adjustState.img) return;

        const currentW = adjustState.naturalW * adjustState.baseScale * adjustState.zoom;
        const currentH = adjustState.naturalH * adjustState.baseScale * adjustState.zoom;

        // Base center coordinates
        const centerX = (adjustState.viewportSize - currentW) / 2;
        const centerY = (adjustState.viewportSize - currentH) / 2;

        // Allow generous movement up and down
        const maxPanY = Math.max(currentH, adjustState.viewportSize) * 0.9;
        const maxPanX = Math.max(currentW, adjustState.viewportSize) * 0.9;

        adjustState.panY = Math.max(-maxPanY, Math.min(maxPanY, adjustState.panY));
        adjustState.panX = Math.max(-maxPanX, Math.min(maxPanX, adjustState.panX));

        const finalX = centerX + adjustState.panX;
        const finalY = centerY + adjustState.panY;

        adjustImg.style.width = `${currentW}px`;
        adjustImg.style.height = `${currentH}px`;
        adjustImg.style.left = `${finalX}px`;
        adjustImg.style.top = `${finalY}px`;
    }

    function nudgePhotoY(delta) {
        // delta negative = move image up, delta positive = move image down
        adjustState.panY += delta;
        updateAdjustView();
        
        const sliderY = document.getElementById('adjustSliderY');
        if (sliderY) {
            sliderY.value = Math.round(adjustState.panY);
        }
    }

    function resetPhotoAdjust() {
        if (!adjustState.img) return;
        adjustState.zoom = 1;
        adjustState.panX = 0;
        adjustState.panY = 0;
        
        const sliderZoomEl = document.getElementById('adjustSliderZoom');
        const sliderYEl = document.getElementById('adjustSliderY');
        if (sliderZoomEl) sliderZoomEl.value = 1;
        if (sliderYEl) sliderYEl.value = 0;
        
        updateAdjustView();
    }

    function closePhotoAdjuster() {
        document.getElementById('photoAdjustModal').style.display = 'none';
    }

    function applyPhotoAdjust() {
        if (!adjustState.img) return;

        const exportSize = 450;
        const canvas = document.createElement('canvas');
        canvas.width = exportSize;
        canvas.height = exportSize;
        const ctx = canvas.getContext('2d');

        const ratio = exportSize / adjustState.viewportSize;
        const currentW = adjustState.naturalW * adjustState.baseScale * adjustState.zoom;
        const currentH = adjustState.naturalH * adjustState.baseScale * adjustState.zoom;

        const centerX = (adjustState.viewportSize - currentW) / 2;
        const centerY = (adjustState.viewportSize - currentH) / 2;

        const finalX = centerX + adjustState.panX;
        const finalY = centerY + adjustState.panY;

        // Clear and draw image at adjusted position
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, exportSize, exportSize);
        ctx.drawImage(adjustState.img, finalX * ratio, finalY * ratio, currentW * ratio, currentH * ratio);

        canvas.toBlob(function(blob) {
            if (!blob) return;

            const file = new File([blob], 'profile_photo.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            profileInput.files = dataTransfer.files;

            const previewUrl = URL.createObjectURL(blob);
            const currentPreview = document.getElementById('photoPreview');

            if (currentPreview && currentPreview.tagName === 'IMG') {
                currentPreview.src = previewUrl;
            } else if (currentPreview) {
                const img = document.createElement('img');
                img.id = 'photoPreview';
                img.src = previewUrl;
                img.alt = 'Profile Photo';
                img.className = 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105';
                currentPreview.replaceWith(img);
            }

            const btnAdjust = document.getElementById('btnAdjustPhoto');
            if (btnAdjust) btnAdjust.style.display = 'inline-flex';

            closePhotoAdjuster();
        }, 'image/jpeg', 0.92);
    }

    // Drag / Touch listeners on viewport
    function onPointerDown(e) {
        adjustState.isDragging = true;
        const clientX = e.clientX || (e.touches && e.touches[0].clientX);
        const clientY = e.clientY || (e.touches && e.touches[0].clientY);
        adjustState.startX = clientX;
        adjustState.startY = clientY;
        adjustState.initialPanX = adjustState.panX;
        adjustState.initialPanY = adjustState.panY;
    }

    function onPointerMove(e) {
        if (!adjustState.isDragging) return;
        const clientX = e.clientX || (e.touches && e.touches[0].clientX);
        const clientY = e.clientY || (e.touches && e.touches[0].clientY);
        const deltaX = clientX - adjustState.startX;
        const deltaY = clientY - adjustState.startY;
        
        adjustState.panX = adjustState.initialPanX + deltaX;
        adjustState.panY = adjustState.initialPanY + deltaY;
        
        updateAdjustView();

        const sliderY = document.getElementById('adjustSliderY');
        if (sliderY) {
            sliderY.value = Math.round(adjustState.panY);
        }
    }

    function onPointerUp() {
        adjustState.isDragging = false;
    }

    if (adjustViewport) {
        adjustViewport.addEventListener('mousedown', onPointerDown);
        window.addEventListener('mousemove', onPointerMove);
        window.addEventListener('mouseup', onPointerUp);

        adjustViewport.addEventListener('touchstart', onPointerDown, { passive: true });
        window.addEventListener('touchmove', onPointerMove, { passive: true });
        window.addEventListener('touchend', onPointerUp);
    }

    // Sliders
    const sliderY = document.getElementById('adjustSliderY');
    if (sliderY) {
        sliderY.addEventListener('input', function(e) {
            if (!adjustState.img) return;
            adjustState.panY = parseFloat(e.target.value);
            updateAdjustView();
        });
    }

    const sliderZoom = document.getElementById('adjustSliderZoom');
    if (sliderZoom) {
        sliderZoom.addEventListener('input', function(e) {
            if (!adjustState.img) return;
            adjustState.zoom = parseFloat(e.target.value);
            updateAdjustView();
        });
    }

    // File input change: immediately open adjuster
    profileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                openPhotoAdjuster(e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Change Password Realtime Validation
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const currentPasswordInput = document.getElementById('current_password');
    let debounceTimer;

    const rules = {
        length: document.getElementById('rule-length'),
        uppercase: document.getElementById('rule-uppercase'),
        number: document.getElementById('rule-number'),
        special: document.getElementById('rule-special')
    };

    const matchText = document.getElementById('password-match');
    const currentPasswordFeedback = document.createElement('p');
    currentPasswordFeedback.className = "mt-2 text-xs font-medium";
    currentPasswordInput.parentNode.appendChild(currentPasswordFeedback);

    newPasswordInput.addEventListener('input', validatePassword);
    confirmPasswordInput.addEventListener('input', checkMatch);

    currentPasswordInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const currentPassword = currentPasswordInput.value.trim();

        if (currentPassword.length === 0) {
            currentPasswordFeedback.textContent = '';
            return;
        }

        currentPasswordFeedback.textContent = "Checking...";
        currentPasswordFeedback.className = "mt-2 text-xs font-medium text-gray-500";

        debounceTimer = setTimeout(() => {
            fetch("{{ route('vendor.password.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ current_password: currentPassword })
            })
            .then(res => res.json())
            .then(data => {
                if (data.valid) {
                    currentPasswordFeedback.innerHTML = '<iconify-icon icon="solar:check-circle-bold" class="inline align-middle mr-1 text-base text-green-600"></iconify-icon> Current password is correct';
                    currentPasswordFeedback.className = "mt-2 text-xs font-medium text-green-600 flex items-center";
                } else {
                    currentPasswordFeedback.innerHTML = '<iconify-icon icon="solar:close-circle-bold" class="inline align-middle mr-1 text-base text-red-600"></iconify-icon> Current password is incorrect';
                    currentPasswordFeedback.className = "mt-2 text-xs font-medium text-red-600 flex items-center";
                }
                validatePassword();
            })
            .catch(() => {
                currentPasswordFeedback.textContent = "Error checking password.";
                currentPasswordFeedback.className = "mt-2 text-xs font-medium text-red-600";
            });
        }, 500);
    });

    function validatePassword() {
        const value = newPasswordInput.value;
        updateRule(rules.length, value.length >= 8);
        updateRule(rules.uppercase, /[A-Z]/.test(value));
        updateRule(rules.number, /\d/.test(value));
        updateRule(rules.special, /[!@#$%^&*(),.?":{}|<>]/.test(value));

        if(currentPasswordInput.value && value === currentPasswordInput.value) {
            matchText.innerHTML = '<iconify-icon icon="solar:close-circle-bold" class="inline align-middle mr-1 text-base text-red-600"></iconify-icon> New password cannot be the same as current';
            matchText.className = "mt-2 text-xs font-medium text-red-600 flex items-center";
        } else {
            checkMatch();
        }
    }

    function updateRule(element, isValid) {
        if (!element) return;
        const circle = element.querySelector('span:first-child');
        if (isValid) {
            circle.classList.remove('border', 'border-gray-400');
            circle.classList.add('bg-green-500');
            element.classList.add('text-green-600', 'font-medium');
        } else {
            circle.classList.remove('bg-green-500');
            circle.classList.add('border', 'border-gray-400');
            element.classList.remove('text-green-600', 'font-medium');
        }
    }

    function checkMatch() {
        if (confirmPasswordInput.value === "") {
            matchText.innerHTML = "";
            return;
        }

        if(newPasswordInput.value === currentPasswordInput.value) {
            matchText.innerHTML = '<iconify-icon icon="solar:close-circle-bold" class="inline align-middle mr-1 text-base text-red-600"></iconify-icon> New password cannot be the same as current';
            matchText.className = "mt-2 text-xs font-medium text-red-600 flex items-center";
            return;
        }

        if (newPasswordInput.value === confirmPasswordInput.value) {
            matchText.innerHTML = '<iconify-icon icon="solar:check-circle-bold" class="inline align-middle mr-1 text-base text-green-600"></iconify-icon> Passwords match';
            matchText.className = "mt-2 text-xs font-medium text-green-600 flex items-center";
        } else {
            matchText.innerHTML = '<iconify-icon icon="solar:close-circle-bold" class="inline align-middle mr-1 text-base text-red-600"></iconify-icon> Passwords do not match';
            matchText.className = "mt-2 text-xs font-medium text-red-600 flex items-center";
        }
    }

    // Forgot Password Form AJAX
    const forgotForm = document.getElementById('forgotPasswordForm');
    const forgotEmail = document.getElementById('forgot_email');
    const forgotFeedback = document.getElementById('forgot-feedback');

    if (forgotForm) {
        forgotForm.addEventListener('submit', function(e) {
            e.preventDefault();
            forgotFeedback.innerHTML = `
                <span class="flex items-center gap-2 text-gray-500">
                    <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Sending reset link...
                </span>
            `;
            forgotFeedback.className = "text-xs mt-1 text-gray-500";

            fetch("{{ route('vendor.password.email') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({ email: forgotEmail.value })
            })
            .then(async res => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    throw data;
                }
                return data;
            })
            .then(data => {
                if (data.status) {
                    forgotFeedback.innerHTML = `<span class="flex items-center gap-1.5 text-emerald-600 font-medium"><iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon> ${data.status}</span>`;
                    forgotFeedback.className = "text-xs mt-2 text-emerald-600";
                } else {
                    forgotFeedback.textContent = 'Password reset link sent successfully.';
                    forgotFeedback.className = "text-xs mt-2 text-emerald-600 font-medium";
                }
            })
            .catch(err => {
                if (err && err.errors && err.errors.email) {
                    forgotFeedback.textContent = err.errors.email[0];
                } else if (err && err.error) {
                    forgotFeedback.textContent = err.error;
                } else if (err && err.message) {
                    forgotFeedback.textContent = err.message;
                } else {
                    forgotFeedback.textContent = 'Failed to send reset link. Please try again.';
                }
                forgotFeedback.className = "text-xs mt-2 text-red-600 font-medium";
            });
        });
    }
</script>
@endsection
