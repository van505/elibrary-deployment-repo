<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('member.user', 'ebook')->paginate(10);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:ready,cancelled,expired',
        ]);

        $reservation->update($validated);

        ActivityLogger::log('updated', 'reservations', 'Updated reservation ID: ' . $id . ' status to: ' . $validated['status']);

        return redirect()->route('admin.reservations.index')->with('success', 'Reservation status updated successfully.');
    }
}
