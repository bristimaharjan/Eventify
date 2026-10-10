<!DOCTYPE html>
<html>
<head>
    <title>Event Booking Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2 {
            color: #6a4c93;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #8D85EC;
            color: white;
            padding: 8px 6px;
            font-size: 11px;
            text-align: left;
        }
        td {
            padding: 8px 6px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .total-amount {
            color: green;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            background-color: #e9e5fc;
            color: #5c4eb5;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <h2>Event Booking & Ticket Sales Report</h2>
    <p style="text-align: center; font-size: 11px; color: #666; margin-top: -15px;">Generated on {{ now()->format('d M, Y - h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Event</th>
                <th style="text-align: center;">Ticket Type</th>
                <th style="text-align: center;">Tickets</th>
                <th>Price / Ticket</th>
                <th>Amount (Rs)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($eventBookings as $booking)
            @php
                $ticketName = $booking->ticketType ? $booking->ticketType->name : 'General Admission';
                $unitPrice = $booking->price_per_ticket ?? ($booking->ticketType ? $booking->ticketType->price : ($booking->event ? $booking->event->price : 0));
                $total = $booking->total_amount ?? $booking->amount;
            @endphp
            <tr>
                <td>#{{ $booking->id }}</td>
                <td>{{ $booking->user->name ?? 'Guest User' }}</td>
                <td>{{ $booking->event->event_name ?? 'Event #' . $booking->event_id }}</td>
                <td style="text-align: center;"><span class="badge">{{ $ticketName }}</span></td>
                <td style="text-align: center;">{{ $booking->tickets }}</td>
                <td>Rs {{ number_format($unitPrice, 2) }}</td>
                <td class="total-amount">Rs {{ number_format($total, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #777;">No event bookings found.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right; padding: 10px; white-space: nowrap;">Total Summary:</td>
                <td colspan="2" style="text-align: center; color: #6a4c93; white-space: nowrap;">{{ $eventBookings->sum('tickets') }} tickets</td>
                <td colspan="2" class="total-amount" style="font-size: 13px; white-space: nowrap;">Rs {{ number_format($eventBookings->sum(fn($b) => $b->total_amount ?? $b->amount), 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>