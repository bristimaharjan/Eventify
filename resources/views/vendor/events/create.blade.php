@extends('layouts.app')

@section('title', 'Add Event')
@php 
    $noNavbar = true; 
    $noFooter = true; 
@endphp
@include('vendor.sidebar')

@section('content')
<div class="w-full mx-auto ml-0 sm:ml-64 p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h2 class="text-3xl font-bold text-[#8d85ec] mb-6 text-center">Add New Event</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6 border border-red-300">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vendor.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Event Name -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Name <span class="text-red-500">*</span></label>
                <input type="text" name="event_name" value="{{ old('event_name') }}" placeholder="e.g. Tech Conference 2026" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
            </div>

            <!-- Event Date & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Date & Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                </div>
                <div>
                    <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Category <span class="text-red-500">*</span></label>
                    @php
                        $standardCategories = ['Concert', 'Art', 'Exhibition', 'Food & Drink', 'Technology', 'Sports', 'Wellness', 'Workshop'];
                        $oldCategory = old('category');
                        $knownCategories = array_merge($standardCategories, $categories->all());
                        $restoredCategory = $oldCategory && $oldCategory !== '__new__'
                            && !in_array($oldCategory, $knownCategories, true)
                                ? $oldCategory
                                : null;
                    @endphp
                    <select id="event-category" name="category" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                        <option value="" disabled {{ !$oldCategory ? 'selected' : '' }}>Select Category</option>
                        <option value="Concert" {{ old('category') == 'Concert' ? 'selected' : '' }}>Concert</option>
                        <option value="Art" {{ old('category') == 'Art' ? 'selected' : '' }}>Exhibition / Art</option>
                        <option value="Food & Drink" {{ old('category') == 'Food & Drink' ? 'selected' : '' }}>Food & Drink</option>
                        <option value="Technology" {{ old('category') == 'Technology' ? 'selected' : '' }}>Technology</option>
                        <option value="Sports" {{ old('category') == 'Sports' ? 'selected' : '' }}>Sports</option>
                        <option value="Wellness" {{ old('category') == 'Wellness' ? 'selected' : '' }}>Workshop / Wellness</option>
                        @foreach($categories as $existingCategory)
                            @unless(in_array($existingCategory, $standardCategories, true))
                                <option value="{{ $existingCategory }}" {{ old('category') === $existingCategory ? 'selected' : '' }}>{{ $existingCategory }}</option>
                            @endunless
                        @endforeach
                        @if($restoredCategory)
                            <option value="{{ $restoredCategory }}" selected>{{ $restoredCategory }}</option>
                        @endif
                        <option value="__new__" {{ $oldCategory === '__new__' ? 'selected' : '' }}>Create a new category...</option>
                    </select>
                </div>
            </div>

            <!-- Venue / Location -->
            <div>
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Venue / Location <span class="text-red-500">*</span></label>
                <input id="location" type="text" name="venue" value="{{ old('venue') }}" placeholder="e.g. Kathmandu Marriott Hotel" class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
            </div>

            <!-- TICKET TYPES SECTION (Alpine.js) -->
            <div x-data="{
                ticketTypes: {{ json_encode(old('ticket_types', [
                    ['name' => 'Regular', 'price' => '500', 'quantity' => '100', 'description' => 'General admission access'],
                    ['name' => 'VIP', 'price' => '1500', 'quantity' => '50', 'description' => 'VIP seating, priority lounge and refreshment access']
                ])) }},
                addTicketType() {
                    this.ticketTypes.push({ name: '', price: '', quantity: '', description: '' });
                },
                removeTicketType(index) {
                    if (this.ticketTypes.length > 1) {
                        this.ticketTypes.splice(index, 1);
                    } else {
                        alert('At least one ticket type must be added before an event can be published.');
                    }
                }
            }" class="p-6 bg-purple-50 dark:bg-gray-700 rounded-2xl border-2 border-dashed border-[#8d85ec]/50 space-y-4">
                
                <div class="flex items-center justify-between border-b border-purple-200 dark:border-gray-600 pb-3">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <iconify-icon icon="solar:ticket-bold" class="text-[#8d85ec] text-2xl"></iconify-icon> Ticket Types & Pricing
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                            Add multiple ticket options (e.g. Regular, VIP, Student) with their respective prices and quantities.
                        </p>
                    </div>
                    <button type="button" @click="addTicketType()" 
                            class="inline-flex items-center gap-1 bg-[#8d85ec] hover:bg-[#7a72d6] text-white px-3 py-1.5 rounded-lg text-sm font-semibold transition shadow-sm">
                        <span>+</span> Add Ticket Type
                    </button>
                </div>

                <!-- Ticket Type Cards -->
                <div class="space-y-4">
                    <template x-for="(ticket, index) in ticketTypes" :key="index">
                        <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm relative space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-[#8d85ec] uppercase tracking-wider" x-text="'Ticket Option #' + (index + 1)"></span>
                                <button type="button" @click="removeTicketType(index)" x-show="ticketTypes.length > 1"
                                        class="text-red-500 hover:text-red-700 text-xs font-semibold px-2.5 py-1 bg-red-50 dark:bg-gray-700 rounded transition inline-flex items-center gap-1">
                                    <iconify-icon icon="solar:trash-bin-trash-bold"></iconify-icon> Remove
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Name -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Ticket Name <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'ticket_types[' + index + '][name]'" x-model="ticket.name" placeholder="e.g. VIP, Regular, Student"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>

                                <!-- Price -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Price (Rs.) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0" :name="'ticket_types[' + index + '][price]'" x-model="ticket.price" placeholder="500"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>

                                <!-- Quantity -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Available Quantity <span class="text-red-500">*</span></label>
                                    <input type="number" min="1" :name="'ticket_types[' + index + '][quantity]'" x-model="ticket.quantity" placeholder="100"
                                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-200 mb-1">Description / Perks (Optional)</label>
                                <input type="text" :name="'ticket_types[' + index + '][description]'" x-model="ticket.description" placeholder="e.g. VIP Front row seating + Refreshments"
                                       class="w-full p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:ring-2 focus:ring-[#8d85ec] focus:outline-none">
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Description -->
            <div x-data="{ wordCount: {{ str_word_count(old('description', '')) }} }">
                <label class="block mb-2 font-semibold text-gray-700 dark:text-gray-200">Event Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" maxlength="500" placeholder="Provide full details about the event, schedule, speakers/artists..." class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-[#8d85ec] focus:outline-none" required @input="wordCount = $event.target.value.trim() ? $event.target.value.trim().split(/\s+/).length : 0">{{ old('description') }}</textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400"><span x-text="wordCount"></span>/50 words</p>
            </div>

            <!-- Photo -->
            <div>
                <div class="flex items-center justify-between gap-3 mb-2">
                    <label class="block font-semibold text-gray-700 dark:text-gray-200">Event Cover Image <span class="text-red-500">*</span></label>
                </div>

                <div class="rounded-2xl border border-dashed border-[#8d85ec]/50 bg-purple-50/50 dark:bg-gray-700/80 p-4">
                    <div id="event-image-trigger" class="cursor-pointer">
                        <input id="event-image-input" type="file" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" required>

                        <div id="event-image-empty" class="flex flex-col items-center justify-center text-center py-6">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-[#8d85ec]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5V7.75A2.75 2.75 0 0 1 5.75 5h12.5A2.75 2.75 0 0 1 21 7.75v8.75a2.75 2.75 0 0 1-2.75 2.75H5.75A2.75 2.75 0 0 1 3 16.5Zm3.5-5.5 2.75 3.25 2.75-3.25 4.75 5.75H7.25l-.75-.75Z"/>
                                </svg>
                            </div>
                            <p class="text-base font-semibold text-gray-800 dark:text-gray-100">Upload Event Cover</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, JPEG, PNG · Max 5 MB</p>
                        </div>

                        <div id="event-image-preview-wrapper" class="hidden mx-auto w-full max-w-[300px]">
                            <div class="aspect-video overflow-hidden rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 shadow-sm">
                                <img id="event-image-preview" alt="Event cover preview" class="h-full w-full object-cover">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-center gap-3">
                        <button type="button" id="event-image-change" class="hidden rounded-full border border-[#8d85ec] text-[#8d85ec] px-3 py-1.5 text-xs font-semibold hover:bg-[#8d85ec] hover:text-white transition">
                            Change Image
                        </button>
                        <button type="button" id="event-image-remove" class="hidden rounded-full border border-red-300 text-red-600 px-3 py-1.5 text-xs font-semibold hover:bg-red-50 transition">
                            Remove
                        </button>
                    </div>
                </div>

                <div class="mt-3 rounded-xl border border-purple-200 bg-purple-50/70 dark:border-gray-600 dark:bg-gray-800/80 p-3 text-sm text-gray-700 dark:text-gray-200">
                    <p class="font-semibold">Event Cover Image</p>
                    <p class="mt-1">Recommended format: JPG, JPEG, or PNG</p>
                    <p>Maximum file size: 5 MB</p>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">For best results, upload a landscape image suitable for the event card.</p>
                </div>

                <div id="event-image-error" class="hidden mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700"></div>
            </div>

            <!-- Submit -->
            <div class="text-center pt-4">
                <button type="submit" class="bg-gradient-to-r from-purple-500 to-[#8d85ec] hover:from-purple-600 hover:to-[#7a72d6] text-white px-8 py-3 rounded-full font-bold shadow-lg transition transform hover:-translate-y-0.5">
                    Add Event & Publish Tickets
                </button>
            </div>
        </form>
    </div>
</div>

<div id="category-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-gray-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="category-modal-title">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-800">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 id="category-modal-title" class="text-xl font-bold text-gray-900 dark:text-white">Create a category</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Add a category to use for this event.</p>
            </div>
            <button type="button" id="category-modal-close" class="rounded-full p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Close category dialog">
                <iconify-icon icon="solar:close-circle-bold" class="text-2xl"></iconify-icon>
            </button>
        </div>
        <div class="mt-5">
            <label for="new-category-name" class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Category name</label>
            <input id="new-category-name" type="text" maxlength="255" value="{{ old('custom_category', $restoredCategory) }}" placeholder="e.g. Stand up" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 focus:border-[#8d85ec] focus:outline-none focus:ring-2 focus:ring-[#8d85ec] dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <p id="category-modal-error" class="mt-2 hidden text-sm text-red-600 dark:text-red-400" role="alert"></p>
        </div>
        <div class="mt-6 flex justify-end gap-3">
            <button type="button" id="category-modal-cancel" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Cancel</button>
            <button type="button" id="category-modal-save" class="rounded-lg bg-[#8d85ec] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#7b76e4]">Add Category</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />

<div id="crop-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-6">
    <div class="w-full max-w-xl rounded-2xl bg-white dark:bg-gray-800 p-5 shadow-2xl sm:p-6">
        <div class="flex items-center justify-between gap-3">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Adjust Event Cover</h3>
            <button type="button" id="crop-modal-close" class="rounded-full p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-700 dark:hover:text-white flex items-center justify-center">
                <iconify-icon icon="solar:close-circle-bold" class="text-2xl"></iconify-icon>
            </button>
        </div>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Adjust the image so the important content stays inside the frame.</p>

        <div class="mt-5 rounded-2xl border border-gray-200 bg-gray-100/90 p-3 dark:border-gray-600 dark:bg-gray-700/80">
            <div class="mx-auto w-full max-w-[420px]">
                <img id="crop-image" class="mx-auto max-h-[60vh] w-full rounded-xl bg-white dark:bg-gray-800" alt="Crop preview">
            </div>
        </div>

        <div class="mt-5">
            <label for="crop-zoom" class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-200">Zoom</label>
            <input id="crop-zoom" type="range" min="1" max="3" step="0.01" value="1" class="w-full accent-[#8d85ec]">
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" id="crop-cancel" class="rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                Cancel
            </button>
            <button type="button" id="crop-use-image" class="rounded-full bg-[#8d85ec] px-5 py-2 text-sm font-semibold text-white hover:bg-[#7a72d6] transition">
                Use Image
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('event-category');
        const categoryModal = document.getElementById('category-modal');
        const categoryNameInput = document.getElementById('new-category-name');
        const categoryModalError = document.getElementById('category-modal-error');
        const categoryModalClose = document.getElementById('category-modal-close');
        const categoryModalCancel = document.getElementById('category-modal-cancel');
        const categoryModalSave = document.getElementById('category-modal-save');
        let previousCategory = categorySelect.value === '__new__' ? '' : categorySelect.value;

        function closeCategoryModal() {
            categoryModal.classList.add('hidden');
            categoryModal.classList.remove('flex');
            categoryModalError.textContent = '';
            categoryModalError.classList.add('hidden');
            categorySelect.value = previousCategory;
        }

        function openCategoryModal(clearInput = true) {
            if (clearInput) {
                categoryNameInput.value = '';
            }
            categoryModalError.textContent = '';
            categoryModalError.classList.add('hidden');
            categoryModal.classList.remove('hidden');
            categoryModal.classList.add('flex');
            requestAnimationFrame(() => categoryNameInput.focus());
        }

        categorySelect.addEventListener('change', function () {
            if (categorySelect.value === '__new__') {
                openCategoryModal();
                return;
            }

            previousCategory = categorySelect.value;
        });

        function saveCategory() {
            const categoryName = categoryNameInput.value.trim();
            if (!categoryName) {
                categoryModalError.textContent = 'Enter a category name.';
                categoryModalError.classList.remove('hidden');
                categoryNameInput.focus();
                return;
            }
            if (categoryName === '__new__') {
                categoryModalError.textContent = 'That category name is reserved. Choose a different name.';
                categoryModalError.classList.remove('hidden');
                categoryNameInput.focus();
                return;
            }

            const matchingOption = Array.from(categorySelect.options).find(option =>
                option.value !== '__new__' && option.value.toLocaleLowerCase() === categoryName.toLocaleLowerCase()
            );
            let option = matchingOption;

            if (!option) {
                option = document.createElement('option');
                option.value = categoryName;
                option.textContent = categoryName;
                categorySelect.insertBefore(option, categorySelect.querySelector('option[value="__new__"]'));
            }

            categorySelect.value = option.value;
            previousCategory = option.value;
            categoryModal.classList.add('hidden');
            categoryModal.classList.remove('flex');
            categoryModalError.textContent = '';
            categoryModalError.classList.add('hidden');
        }

        categoryModalSave.addEventListener('click', saveCategory);
        categoryModalClose.addEventListener('click', closeCategoryModal);
        categoryModalCancel.addEventListener('click', closeCategoryModal);
        categoryModal.addEventListener('click', function (event) {
            if (event.target === categoryModal) {
                closeCategoryModal();
            }
        });
        categoryNameInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                saveCategory();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !categoryModal.classList.contains('hidden')) {
                closeCategoryModal();
            }
        });

        if (categorySelect.value === '__new__') {
            openCategoryModal(false);
        }

        const input = document.getElementById('event-image-input');
        const trigger = document.getElementById('event-image-trigger');
        const emptyState = document.getElementById('event-image-empty');
        const previewWrapper = document.getElementById('event-image-preview-wrapper');
        const preview = document.getElementById('event-image-preview');
        const errorBox = document.getElementById('event-image-error');
        const changeBtn = document.getElementById('event-image-change');
        const removeBtn = document.getElementById('event-image-remove');
        const cropModal = document.getElementById('crop-modal');
        const cropImage = document.getElementById('crop-image');
        const zoomInput = document.getElementById('crop-zoom');
        const closeModalBtn = document.getElementById('crop-modal-close');
        const cancelCropBtn = document.getElementById('crop-cancel');
        const useCropBtn = document.getElementById('crop-use-image');

        let selectedFile = null;
        let cropper = null;
        let previousPreviewDataUrl = null;
        let zoomBounds = {
            min: 1,
            max: 3,
            initial: 1,
        };

        function clampZoom(value) {
            return Math.min(Math.max(value, zoomBounds.min), zoomBounds.max);
        }

        function showError(message) {
            errorBox.textContent = message;
            errorBox.classList.remove('hidden');
        }

        function clearError() {
            errorBox.textContent = '';
            errorBox.classList.add('hidden');
        }

        function updatePreviewState(hasImage) {
            emptyState.classList.toggle('hidden', hasImage);
            previewWrapper.classList.toggle('hidden', !hasImage);
            changeBtn.classList.toggle('hidden', !hasImage);
            removeBtn.classList.toggle('hidden', !hasImage);
        }

        function setPreviewImage(dataUrl) {
            preview.src = dataUrl;
            updatePreviewState(Boolean(dataUrl));
        }

        function restorePreviousPreview() {
            if (previousPreviewDataUrl) {
                setPreviewImage(previousPreviewDataUrl);
                return;
            }
            setPreviewImage('');
        }

        function createFileFromCanvas(canvas, originalName) {
            const dataUrl = canvas.toDataURL('image/jpeg', 0.92);
            const blob = dataURLtoBlob(dataUrl);
            const fileName = (originalName || 'event-cover.jpg').replace(/\.[^.]+$/, '.jpg');
            const file = new File([blob], fileName, {
                type: 'image/jpeg',
                lastModified: Date.now(),
            });
            return file;
        }

        function dataURLtoBlob(dataURL) {
            const parts = dataURL.split(',');
            const mime = parts[0].match(/:(.*?);/)[1];
            const binary = atob(parts[1]);
            const array = new Uint8Array(binary.length);
            for (let i = 0; i < binary.length; i++) {
                array[i] = binary.charCodeAt(i);
            }
            return new Blob([array], { type: mime });
        }

        function closeCropModal() {
            cropModal.classList.add('hidden');
            cropModal.classList.remove('flex');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            cropImage.src = '';
            zoomBounds = { min: 1, max: 3, initial: 1 };
            zoomInput.value = '1';
            zoomInput.min = '1';
            zoomInput.max = '3';
            zoomInput.step = '0.01';
        }

        function validateFile(file) {
            if (!file) {
                return false;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const fileName = (file.name || '').toLowerCase();
            const isValidType = allowedTypes.includes(file.type) || /\.(jpg|jpeg|png)$/i.test(fileName);

            if (!isValidType) {
                showError('Please upload a JPG, JPEG, or PNG image.');
                return false;
            }

            if (file.size > 5 * 1024 * 1024) {
                showError('Image size must be 5 MB or less.');
                return false;
            }

            return true;
        }

        const CROP_ASPECT_RATIO = 16 / 9;

        function openCropModal(file) {
            selectedFile = file;
            const objectUrl = URL.createObjectURL(file);
            cropImage.src = objectUrl;
            cropModal.classList.remove('hidden');
            cropModal.classList.add('flex');

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropImage, {
                aspectRatio: CROP_ASPECT_RATIO,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                responsive: true,
                movable: true,
                zoomable: true,
                scalable: true,
                rotatable: false,
                cropBoxResizable: false,
                minContainerWidth: 260,
                minContainerHeight: 150,
                minCropBoxWidth: 260,
                minCropBoxHeight: 146,
            });

            cropper.on('ready', function () {
                const imageData = cropper.getImageData();
                const cropBox = cropper.getCropBoxData();
                const naturalWidth = imageData.naturalWidth || imageData.width || 1;
                const naturalHeight = imageData.naturalHeight || imageData.height || 1;
                const fitZoom = Math.max(
                    (cropBox.width / naturalWidth) * 1.08,
                    (cropBox.height / naturalHeight) * 1.08,
                    1
                );
                const minZoom = Number(fitZoom.toFixed(2));
                const maxZoom = Math.min(Number((minZoom * 2.5).toFixed(2)), 3);

                zoomBounds = {
                    min: minZoom,
                    max: maxZoom,
                    initial: minZoom,
                };

                zoomInput.min = String(minZoom);
                zoomInput.max = String(maxZoom);
                zoomInput.step = '0.01';
                zoomInput.value = String(minZoom);
                cropper.zoomTo(minZoom);
            });

            const cropperCanvas = cropper.getCropperCanvas();
            if (cropperCanvas) {
                cropperCanvas.scaleStep = 0.06;
            }

            cropper.on('zoom', function () {
                if (!cropper || !zoomInput) {
                    return;
                }

                const currentZoom = clampZoom(Number(cropper.getZoom()));
                zoomInput.value = currentZoom.toFixed(2);
            });
        }

        function useCroppedImage() {
            if (!cropper || !selectedFile) {
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: 1280,
                height: 720,
                imageSmoothingQuality: 'high',
            });

            const finalFile = createFileFromCanvas(canvas, selectedFile.name);
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(finalFile);
            input.files = dataTransfer.files;

            previousPreviewDataUrl = canvas.toDataURL('image/jpeg', 0.92);
            setPreviewImage(previousPreviewDataUrl);
            clearError();
            closeCropModal();
        }

        trigger.addEventListener('click', function () {
            input.click();
        });

        changeBtn.addEventListener('click', function () {
            input.click();
        });

        removeBtn.addEventListener('click', function () {
            input.value = '';
            previousPreviewDataUrl = null;
            setPreviewImage('');
            clearError();
        });

        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                return;
            }

            if (!validateFile(file)) {
                this.value = '';
                return;
            }

            clearError();
            openCropModal(file);
            this.value = '';
        });

        closeModalBtn.addEventListener('click', function () {
            restorePreviousPreview();
            closeCropModal();
        });

        cancelCropBtn.addEventListener('click', function () {
            restorePreviousPreview();
            closeCropModal();
        });

        useCropBtn.addEventListener('click', function () {
            useCroppedImage();
        });

        zoomInput.addEventListener('input', function () {
            if (!cropper) {
                return;
            }

            const zoomValue = Number.parseFloat(this.value);
            if (Number.isFinite(zoomValue)) {
                cropper.zoomTo(clampZoom(zoomValue));
            }
        });

        updatePreviewState(false);
    });
</script>
@endsection
