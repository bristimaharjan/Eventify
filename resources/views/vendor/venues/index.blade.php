@extends('layouts.app')

@section('title', 'My Venues')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp

@section('content')
@include('vendor.sidebar')

<div class="ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen overflow-x-hidden">

    <!-- Header -->
    <div class="mb-6 mt-6 max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-[#8d85ec] truncate">My Venues</h2>
    </div>

    <!-- Venues Grid -->
@if($venues->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10 max-w-6xl mx-auto">
        @foreach($venues as $venue)
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 hover:scale-105 w-full">
                <div class="w-full h-64 overflow-hidden">
                    <img src="{{ asset('uploads/' . $venue->image) }}" 
                         alt="{{ $venue->venue_name }}" 
                         class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                </div>

                <div class="p-5 flex flex-col gap-2">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $venue->venue_name }}</h3>
                    <p class="text-gray-900 font-medium dark:text-gray-200 text-sm line-clamp-2">{{ $venue->description }}</p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm truncate">Location: {{ $venue->location }}</p>
    

                    @if($venue->price_type === 'package')
                        <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-200 flex items-center">
                                <iconify-icon icon="solar:gift-bold" class="text-[#8d85ec] text-base mr-1.5 flex-shrink-0"></iconify-icon>
                                <span><span class="font-semibold text-[#8d85ec]">Package Price:</span> Rs {{ number_format($venue->package_price ?? 0, 2) }}</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Package Includes: {{ $venue->package_details ?? '-' }}
                            </p>
                        </div>
                    @else
                        <p class="text-[#8d85ec] font-semibold text-sm mt-1">
                            Price: Rs {{ number_format($venue->base_price ?? 0, 2) }} / 
                            {{ $venue->price_type === 'per_person' ? 'person' : ($venue->price_type === 'per_day' ? 'day' : 'hour') }}
                        </p>
                    @endif

                    @if($venue->has_catering)
                        <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <p class="text-sm text-gray-700 dark:text-gray-200">
                                <span class="inline-flex items-center gap-1.5 font-semibold text-[#8d85ec]">
                                    <iconify-icon icon="solar:chef-hat-bold" class="text-base"></iconify-icon> Catering Available
                                </span><br>
                                Rs {{ number_format($venue->catering_price_per_person ?? 0, 2) }} per person
                            </p>
                            @if($venue->catering_menu)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menu: {{ $venue->catering_menu }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="flex justify-between mt-3 gap-2">
                            <a href="{{ route('vendor.venues.edit', $venue->id) }}"
                            class="flex-1 bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white font-semibold text-sm py-2 rounded-lg text-center transition">Edit</a>

                            <form action="{{ route('vendor.venues.destroy', $venue->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-full bg-gradient-to-r from-red-400 to-red-600 hover:from-red-500 hover:to-red-700 text-white font-semibold text-sm py-2 rounded-lg transition">
                                    Delete
                                </button>
                            </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center mt-20">
        <p class="text-gray-700 dark:text-gray-200 text-lg font-semibold">No venues found.</p>
    </div>
@endif

</div>


@endsection
