<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorEventController;
use App\Http\Controllers\VendorVenueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorForgotPasswordController;
use App\Http\Controllers\KhaltiController;
use App\Http\Controllers\VenueBookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminInquiryController;
use App\Http\Controllers\AdminKycController;
use App\Http\Controllers\VendorInquiryController;
use App\Http\Controllers\VendorKycController;

Route::get('/venues', [ChirpController::class, 'venues'])->name('venues');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email OTP Verification Routes
Route::get('/verify-email', [EmailVerificationController::class, 'showVerifyForm'])->name('verification.notice');
Route::post('/verify-email', [EmailVerificationController::class, 'verifyOtp'])->name('verification.verify');
Route::post('/resend-otp', [EmailVerificationController::class, 'resendOtp'])->name('verification.resend');

// For root URL
Route::get('/', [ChirpController::class, 'showWelcomePage'])->name('home');

// For /welcome URL
Route::get('/welcome', [ChirpController::class, 'showWelcomePage'])->name('welcome');

Route::get('/about', [ChirpController::class, 'about'])->name('about');
Route::get('/contact', [ChirpController::class, 'contact'])->name('contact');
Route::post('/contact', [ChirpController::class, 'storeContact'])->name('contact.store');
Route::get('/events', [ChirpController::class, 'events'])->name('events');
Route::get('/events/{event}', [ChirpController::class, 'showEvent'])->name('events.show');
Route::post('/events/{event}/toggle-save', [ChirpController::class, 'toggleSave'])->name('events.toggleSave');
Route::get('/userbooking', [UserController::class, 'showReport'])->name('userbooking');
Route::get('/usereventbook', [UserController::class, 'showUserEvent'])->name('usereventbook');

// Authenticated and Verified User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'customerDashboard'])->name('dashboard');
    Route::get('/customer/dashboard', [UserController::class, 'customerDashboard'])->name('customer.dashboard');
    Route::post('/bookings/{id}/cancel', [UserController::class, 'cancelEventBooking'])->name('bookings.cancel');

    Route::get('/chirps', [ChirpController::class, 'index'])->name('chirps.index');
    Route::post('/chirps', [ChirpController::class, 'store'])->name('chirps.store');

    Route::get('/chirps/{id}/edit', [ChirpController::class, 'edit'])->name('chirps.edit');
    Route::put('/chirps/{id}', [ChirpController::class, 'update'])->name('chirps.update');

    Route::delete('/chirps/{id}', [ChirpController::class, 'destroy'])->name('chirps.destroy');
    Route::post('/events/{event}/book', [ChirpController::class, 'book'])->name('events.book');
});

// Admin Routes (Auth, Verified, Admin)
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/chirps', [AdminDashboardController::class, 'index'])->name('chirps.adminIndex');
    Route::get('/admin', [AdminDashboardController::class, 'index']);
    Route::post('/admin/chirps', [ChirpController::class, 'adminStore'])->name('chirps.adminStore');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'adminEdit'])->name('users.adminEdit');
    Route::put('/admin/users/{id}', [UserController::class, 'adminUpdate'])->name('users.adminUpdate');
    Route::delete('/admin/users/{id}', [UserController::class, 'adminDestroy'])->name('users.adminDestroy');
    Route::get('/admin/users', [UserController::class, 'adminView'])->name('chirps.user');
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('chirps.adminLogout');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/bookings', [UserController::class, 'bookings'])->name('profile.bookings');
    Route::get('/admin/reports/booking', [VenueBookingController::class, 'showReport'])->name('admin.reports.adminbooking');
    Route::get('/admin/reports/admineventbooking', [UserController::class, 'showAllEvents'])->name('admin.reports.admineventbooking');
    Route::get('/admin/reports/adminbooking/pdf', [VenueBookingController::class, 'downloadAdminBookingPdf'])->name('admin.reports.adminbooking.pdf');
    Route::get('/admin/reports/admineventbooking/pdf', [UserController::class, 'downloadAdminPdf'])->name('admin.reports.admineventbooking.pdf');
    Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activityLogs.index');
    Route::get('/admin/events', [AdminEventController::class, 'index'])->name('admin.events.index');
    Route::get('/admin/events/{event}', [AdminEventController::class, 'show'])->name('admin.events.show');
    Route::get('/admin/inquiries', [AdminInquiryController::class, 'index'])->name('admin.inquiries.index');
    Route::get('/admin/inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::patch('/admin/inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('admin.inquiries.updateStatus');
    Route::delete('/admin/inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('admin.inquiries.destroy');
    Route::get('/admin/kyc-requests', [AdminKycController::class, 'index'])->name('admin.kyc.index');
    Route::get('/admin/kyc-requests/{kyc}', [AdminKycController::class, 'show'])->name('admin.kyc.show');
    Route::post('/admin/kyc-requests/{kyc}/approve', [AdminKycController::class, 'approve'])->name('admin.kyc.approve');
    Route::post('/admin/kyc-requests/{kyc}/reject', [AdminKycController::class, 'reject'])->name('admin.kyc.reject');
});

// Vendor Dashboard & Report Routes (Auth, Verified, Vendor)
Route::middleware(['auth', 'verified', 'vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
    Route::post('/vendor/logout', [VendorController::class, 'logout'])->name('vendor.vendorLogout');
    Route::get('vendor/venuebooking', [VenueBookingController::class, 'showVenues'])->name('vendor.venuebooking');
    Route::get('vendor/eventbooking', [VendorEventController::class, 'showEvents'])->name('vendor.eventbooking');
    Route::get('/vendor/reports/booking', [VenueBookingController::class, 'bookingReport'])->name('vendor.reports.booking');
    Route::get('/vendor/reports/eventbooking', [VendorEventController::class, 'EventbookingReport'])->name('vendor.reports.eventbooking');
    Route::get('/vendor/reports/booking/pdf', [VenueBookingController::class, 'downloadBookingPdf'])->name('vendor.reports.booking.pdf');
    Route::get('/vendor/reports/eventbooking/pdf', [VendorEventController::class, 'downloadPdf'])->name('vendor.reports.eventbooking.pdf');
    Route::get('/vendor/inquiries', [VendorInquiryController::class, 'index'])->name('vendor.inquiries.index');
    Route::get('/vendor/inquiries/{inquiry}', [VendorInquiryController::class, 'show'])->name('vendor.inquiries.show');
    Route::patch('/vendor/inquiries/{inquiry}/status', [VendorInquiryController::class, 'updateStatus'])->name('vendor.inquiries.updateStatus');

    // Vendor KYC Management
    Route::get('/vendor/kyc', [VendorKycController::class, 'index'])->name('vendor.kyc.index');
    Route::post('/vendor/kyc', [VendorKycController::class, 'store'])->name('vendor.kyc.store');
    Route::get('/vendor/kyc/resubmit', [VendorKycController::class, 'resubmitForm'])->name('vendor.kyc.resubmit');
    Route::post('/vendor/kyc/resubmit', [VendorKycController::class, 'processResubmit'])->name('vendor.kyc.resubmit.process');
    Route::get('/vendor/kyc/success', [VendorKycController::class, 'success'])->name('vendor.kyc.success');
    Route::get('/vendor/kyc/document/{kyc}/{type}', [VendorKycController::class, 'downloadDocument'])->name('vendor.kyc.document');
});

// Direct email access route (authenticates vendor and opens vendor dashboard)
Route::get('/vendor/kyc/email-access/{user}', [VendorKycController::class, 'emailAccess'])->name('vendor.kyc.emailAccess');

Route::prefix('vendor/venues')->middleware(['auth', 'verified', 'vendor'])->group(function() {
    Route::get('/', [VendorVenueController::class, 'index'])->name('vendor.venues.index');
    Route::get('/create', [VendorVenueController::class, 'create'])->name('vendor.venues.create')->middleware('kyc.approved');
    Route::post('/', [VendorVenueController::class, 'store'])->name('vendor.venues.store')->middleware('kyc.approved');
    Route::get('/{venue}/edit', [VendorVenueController::class, 'edit'])->name('vendor.venues.edit');
    Route::put('/{venue}', [VendorVenueController::class, 'update'])->name('vendor.venues.update');
    Route::delete('/{venue}', [VendorVenueController::class, 'destroy'])->name('vendor.venues.destroy');
});

Route::prefix('vendor/events')->middleware(['auth', 'verified', 'vendor'])->group(function() {
    Route::get('/', [VendorEventController::class, 'index'])->name('vendor.events.index');
    Route::get('/create', [VendorEventController::class, 'create'])->name('vendor.events.create')->middleware('kyc.approved');
    Route::post('/', [VendorEventController::class, 'store'])->name('vendor.events.store')->middleware('kyc.approved');
    Route::get('/{event}/edit', [VendorEventController::class, 'edit'])->name('vendor.events.edit');
    Route::put('/{event}', [VendorEventController::class, 'update'])->name('vendor.events.update');
    Route::delete('/{event}', [VendorEventController::class, 'destroy'])->name('vendor.events.destroy');
});

// User Profile Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [UserController::class, 'deletePhoto'])->name('profile.photo.delete');
});

// Vendor Profile Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vendor/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/vendor/profile/update', [ProfileController::class, 'update'])->name('profile.updates');
});

// Vendor Forgot Password (Guest access)
Route::get('/vendor/forgot-password', [VendorForgotPasswordController::class, 'showForgotForm'])
    ->name('vendor.password.request');
Route::post('/vendor/forgot-password', [VendorForgotPasswordController::class, 'sendResetLink'])
    ->name('vendor.password.email');

// Vendor Reset Password
Route::get('/vendor/reset-password/{token}', [VendorForgotPasswordController::class, 'showResetForm'])
    ->name('vendor.password.reset');
Route::post('/vendor/reset-password', [VendorForgotPasswordController::class, 'reset'])
    ->name('vendor.password.update');

// Change password while logged in
Route::post('/vendor/change-password', [ProfileController::class, 'updatePassword'])
    ->name('vendor.password.change');
Route::post('/vendor/password/check', [ProfileController::class, 'checkCurrentPassword'])
     ->name('vendor.password.check');

Route::get('/payment', function () {
    return view('payment');
});

Route::post('/khalti/verify', [KhaltiController::class, 'verify'])
    ->name('khalti.verify')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/venues/book', [VenueBookingController::class, 'store'])->name('venues.book');
    Route::get('/venues/{venue}/booked-dates', [VenueBookingController::class, 'getBookedDates']);
    Route::post('/venues/{id}/mark-as-paid', [VenueBookingController::class, 'markAsPaid'])->name('venue_bookings.markAsPaid');
});

Route::post('/khalti/save-booking', [App\Http\Controllers\KhaltiController::class, 'saveBooking'])->name('khalti.saveBooking');
Route::delete('/venue-bookings/{id}/cancel', [VenueBookingController::class, 'cancel'])->name('venueBooking.cancel');

Route::post('/chatbot/message', [App\Http\Controllers\ChatbotController::class, 'respond'])
    ->name('chatbot.message');
Route::post('/chatbot/clear', [App\Http\Controllers\ChatbotController::class, 'clearHistory'])
    ->name('chatbot.clear');

Route::post('/venue-review', [ReviewController::class, 'store'])->name('venueReview.store');
Route::get('/admin/reviews', [ReviewController::class, 'index'])
    ->name('admin.reports.review');

Route::delete('/venue-review/{review}', [ReviewController::class, 'destroy'])->name('venueReview.destroy');
Route::get('/vendor/reviews', [ReviewController::class, 'vendorIndex'])
    ->name('vendor.venue-reviews')
    ->middleware(['auth', 'verified']);
Route::get('/venues/{venue}/reviews', [ReviewController::class, 'getVenueReviews'])->name('venues.reviews');

// Fallback route - MUST be at the end
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});