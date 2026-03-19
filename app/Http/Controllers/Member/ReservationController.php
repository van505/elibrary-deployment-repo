<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\Reservation;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $member       = auth()->user()->member;
        $reservations = $member->reservations()->with('ebook')->paginate(10);

        return view('member.reservations.index', compact('reservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ebook_id' => 'required|exists:ebooks,id',
        ]);

        $member = auth()->user()->member;

        // Check max reservations limit
        $max     = (int) Setting::get('max_reservations', 2);
        $current = $member->reservations()->where('status', 'pending')->count();

        if ($current >= $max) {
            return redirect()->back()->with('error', 'You have reached the maximum reservation limit.');
        }

        // Check for duplicate pending reservation
        $duplicate = $member->reservations()
            ->where('ebook_id', $request->ebook_id)
            ->where('status', 'pending')
            ->exists();

        if ($duplicate) {
            return redirect()->back()->with('error', 'You already have a pending reservation for this ebook.');
        }

        Reservation::create([
            'member_id'     => $member->id,
            'ebook_id'      => $request->ebook_id,
            'reserved_date' => Carbon::today(),
            'expiry_date'   => Carbon::today()->addDays(3),
            'status'        => 'pending',
        ]);

        return redirect()->route('member.reservations.index')->with('success', 'Reservation placed successfully.');
    }

    public function destroy($id)
    {
        $member      = auth()->user()->member;
        $reservation = Reservation::where('id', $id)
            ->where('member_id', $member->id)
            ->firstOrFail();

        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('member.reservations.index')->with('success', 'Reservation cancelled successfully.');
    }
}
