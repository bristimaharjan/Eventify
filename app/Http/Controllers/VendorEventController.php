<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\TicketType;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;

class VendorEventController extends Controller
{
    // Display all events for the logged-in vendor
    public function index(Request $request)
    {
        $category = $request->query('category');

        $events = Event::with('ticketTypes')
            ->where('vendor_id', Auth::id())
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $vendorId = Auth::id();
        $categories = Cache::remember('vendor_categories_' . $vendorId, 1800, function () use ($vendorId) {
            return Event::where('vendor_id', $vendorId)
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->orderBy('category')
                ->pluck('category');
        });

        return view('vendor.events.index', compact('events', 'categories', 'category'));
    }

    // Show form to create a new event
    public function create()
    {
        $categories = Event::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('vendor.events.create', compact('categories'));
    }

    // Store a new event
    public function store(Request $request)
    {
        if ($request->input('category') === '__new__') {
            $customCategory = $request->input('custom_category');
            $request->merge([
                'category' => is_string($customCategory) ? trim($customCategory) : $customCategory,
            ]);
        }

        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'category' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'description' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (str_word_count(strip_tags($value)) > 50) {
                        $fail('The event description may not exceed 50 words.');
                    }
                },
            ],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'ticket_types' => 'required|array|min:1',
            'ticket_types.*.name' => 'required|string|max:255',
            'ticket_types.*.price' => 'required|numeric|min:0',
            'ticket_types.*.quantity' => 'required|integer|min:1',
            'ticket_types.*.description' => 'nullable|string',
        ], [
            'image.mimes' => 'Please upload a JPG, JPEG, or PNG image.',
            'image.max' => 'Image size must be 5 MB or less.',
            'ticket_types.required' => 'At least one ticket type must be added before an event can be published.',
            'ticket_types.min' => 'At least one ticket type must be added before an event can be published.',
            'ticket_types.*.name.required' => 'Ticket type name is required.',
            'ticket_types.*.price.required' => 'Ticket price is required.',
            'ticket_types.*.price.min' => 'Ticket price must be greater than or equal to 0.',
            'ticket_types.*.quantity.required' => 'Ticket quantity is required.',
            'ticket_types.*.quantity.min' => 'Ticket quantity must be at least 1.',
        ]);

        // Check for duplicate ticket type names within this event
        $names = array_map(function ($t) {
            return strtolower(trim($t['name'] ?? ''));
        }, $request->ticket_types);

        if (count($names) !== count(array_unique($names))) {
            return back()->withErrors(['ticket_types' => 'Ticket type names must be unique within the same event.'])->withInput();
        }

        // Handle image upload with a 4:5 cropped cover image
        $imageFile = $request->file('image');
        $extension = strtolower($imageFile->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::uuid() . '.' . $extension;

        $imageFile->move(public_path('uploads'), $filename);
        $imagePath = $filename;

        // Calculate aggregate minimum price and total capacity
        $minPrice = min(array_column($request->ticket_types, 'price'));
        $totalSeats = array_sum(array_column($request->ticket_types, 'quantity'));

        $createdEvent = DB::transaction(function () use ($request, $imagePath, $minPrice, $totalSeats) {
            $event = Event::create([
                'vendor_id' => Auth::id(),
                'event_name' => $request->event_name,
                'event_date' => $request->event_date,
                'venue' => $request->venue,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $minPrice,
                'available_seats' => $totalSeats,
                'image' => $imagePath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            foreach ($request->ticket_types as $ticketData) {
                TicketType::create([
                    'event_id' => $event->id,
                    'name' => trim($ticketData['name']),
                    'description' => $ticketData['description'] ?? null,
                    'price' => $ticketData['price'],
                    'quantity' => $ticketData['quantity'],
                    'sold_quantity' => 0,
                    'status' => 'active',
                ]);
            }

            return $event;
        });

        ActivityLogger::log('event_created', 'Created event "' . $request->event_name . '"', $createdEvent);

        return redirect()->route('vendor.events.index')->with('success', 'Event and ticket types created successfully!');
    }

    // Show form to edit an existing event
    public function edit(Event $event)
    {
        // Ensure the vendor owns the event
        if ($event->vendor_id !== Auth::id()) {
            abort(403);
        }

        $event->load('ticketTypes');

        return view('vendor.events.edit', compact('event'));
    }

    // Update an existing event
    public function update(Request $request, Event $event)
    {
        if ($event->vendor_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'venue' => 'required|string|max:255',
            'description' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (str_word_count(strip_tags($value)) > 50) {
                        $fail('The event description may not exceed 50 words.');
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ticket_types' => 'required|array|min:1',
            'ticket_types.*.id' => 'nullable|integer',
            'ticket_types.*.name' => 'required|string|max:255',
            'ticket_types.*.price' => 'required|numeric|min:0',
            'ticket_types.*.quantity' => 'required|integer|min:1',
            'ticket_types.*.description' => 'nullable|string',
            'ticket_types.*.status' => 'nullable|string|in:active,inactive',
        ], [
            'ticket_types.required' => 'At least one ticket type must be specified.',
            'ticket_types.min' => 'At least one ticket type must be specified.',
            'ticket_types.*.name.required' => 'Ticket type name is required.',
            'ticket_types.*.price.required' => 'Ticket price is required.',
            'ticket_types.*.price.min' => 'Ticket price must be greater than or equal to 0.',
            'ticket_types.*.quantity.required' => 'Ticket quantity is required.',
            'ticket_types.*.quantity.min' => 'Ticket quantity must be at least 1.',
        ]);

        // Check for duplicate ticket type names
        $names = array_map(function ($t) {
            return strtolower(trim($t['name'] ?? ''));
        }, $request->ticket_types);

        if (count($names) !== count(array_unique($names))) {
            return back()->withErrors(['ticket_types' => 'Ticket type names must be unique within the same event.'])->withInput();
        }

        // Validate sold quantity protection for each existing ticket type
        $existingTicketTypes = $event->ticketTypes()->get()->keyBy('id');

        foreach ($request->ticket_types as $ticketData) {
            if (!empty($ticketData['id']) && isset($existingTicketTypes[$ticketData['id']])) {
                $existing = $existingTicketTypes[$ticketData['id']];
                if ((int)$ticketData['quantity'] < $existing->sold_quantity) {
                    return back()->withErrors([
                        'ticket_types' => "Available quantity for '{$existing->name}' cannot be less than tickets already sold ({$existing->sold_quantity} sold)."
                    ])->withInput();
                }
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($event->image && file_exists(public_path('uploads/' . $event->image))) {
                @unlink(public_path('uploads/' . $event->image));
            }

            $extension = $request->file('image')->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $request->file('image')->move(public_path('uploads'), $filename);
            $event->image = $filename;
        }

        DB::transaction(function () use ($request, $event, $existingTicketTypes) {
            $submittedIds = [];

            foreach ($request->ticket_types as $ticketData) {
                if (!empty($ticketData['id']) && isset($existingTicketTypes[$ticketData['id']])) {
                    // Update existing ticket type
                    $ticket = $existingTicketTypes[$ticketData['id']];
                    $ticket->update([
                        'name' => trim($ticketData['name']),
                        'price' => $ticketData['price'],
                        'quantity' => $ticketData['quantity'],
                        'description' => $ticketData['description'] ?? null,
                        'status' => $ticketData['status'] ?? 'active',
                    ]);
                    $submittedIds[] = $ticket->id;
                } else {
                    // Create new ticket type
                    $newTicket = TicketType::create([
                        'event_id' => $event->id,
                        'name' => trim($ticketData['name']),
                        'price' => $ticketData['price'],
                        'quantity' => $ticketData['quantity'],
                        'sold_quantity' => 0,
                        'description' => $ticketData['description'] ?? null,
                        'status' => $ticketData['status'] ?? 'active',
                    ]);
                    $submittedIds[] = $newTicket->id;
                }
            }

            // Handle deleted ticket types
            foreach ($existingTicketTypes as $existing) {
                if (!in_array($existing->id, $submittedIds)) {
                    if ($existing->sold_quantity > 0) {
                        // Protect sold tickets from destructive deletion: deactivate instead
                        $existing->update(['status' => 'inactive']);
                    } else {
                        $existing->delete();
                    }
                }
            }

            // Reload active ticket types to update aggregate values on event
            $activeTickets = $event->ticketTypes()->where('status', 'active')->get();
            $minPrice = $activeTickets->min('price') ?? $event->price;
            $totalRemaining = $activeTickets->sum(function ($t) {
                return max(0, $t->quantity - $t->sold_quantity);
            });

            // Update event details
            $event->update([
                'event_name' => $request->event_name,
                'event_date' => $request->event_date,
                'category' => $request->category,
                'venue' => $request->venue,
                'description' => $request->description,
                'price' => $minPrice,
                'available_seats' => $totalRemaining,
            ]);
        });

        ActivityLogger::log('event_updated', 'Updated event "' . $request->event_name . '"', $event);

        return redirect()->route('vendor.events.index')->with('success', 'Event and ticket types updated successfully!');
    }

    // Delete an event
    public function destroy(Event $event)
    {
        if ($event->vendor_id !== Auth::id()) {
            abort(403);
        }

        $eventName = $event->event_name;

        // Delete image file
        if ($event->image && file_exists(public_path('uploads/' . $event->image))) {
            @unlink(public_path('uploads/' . $event->image));
        }

        $event->delete();

        ActivityLogger::log('event_deleted', 'Deleted event "' . $eventName . '"');

        return redirect()->route('vendor.events.index')->with('success', 'Event deleted successfully!');
    }

    public function showEvents()
    {
        // Fetch all event bookings related to logged-in vendor
        $vendorId = auth()->user()->id;

        $eventBookings = Booking::with(['user', 'event', 'ticketType'])
            ->whereHas('event', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->latest()
            ->get();

        // Pass the bookings to the view
        return view('vendor.eventbooking', compact('eventBookings'));
    }

    public function downloadPdf(Request $request)
    {
        $vendorId = auth()->user()->id;

        // Build the query with filters
        $query = Booking::with(['user', 'event', 'ticketType'])
            ->whereHas('event', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            });

        if ($request->filled('from_date')) {
            $query->whereDate('booking_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('booking_date', '<=', $request->input('to_date'));
        }

        // Fetch the filtered bookings
        $eventBookings = $query->latest()->get();

        // Generate PDF without passing filter variables
        $pdf = PDF::loadView('vendor.reports.eventbooking_pdf', compact('eventBookings'));

        return $pdf->download('event_bookings.pdf');
    }

    public function EventbookingReport(Request $request)
    {
        // Fetch logged-in vendor ID
        $vendorId = auth()->user()->id;

        // Build the base query
        $query = Booking::with(['user', 'event', 'ticketType'])
            ->whereHas('event', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            });

        // Apply date filters if provided
        if ($request->filled('from_date')) {
            $query->whereDate('booking_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('booking_date', '<=', $request->input('to_date'));
        }

        // Get the filtered bookings
        $eventBookings = $query->latest()->get();

        // Pass the bookings and request data to the view
        return view('vendor.reports.eventbooking', compact('eventBookings'))->with([
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date')
        ]);
    }
}
