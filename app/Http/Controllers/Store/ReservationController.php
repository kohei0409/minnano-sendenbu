<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $store = auth()->user()->store;
        $query = $store->reservations();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->where('reservation_date', $request->date);
        }

        $reservations = $query->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->paginate(20);

        return view('store.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        return view('store.reservations.show', compact('reservation'));
    }

    public function confirm(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return redirect()
            ->route('store.reservations.index')
            ->with('success', '予約を確定しました');
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()
            ->route('store.reservations.index')
            ->with('success', '予約をキャンセルしました');
    }

    public function complete(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->update([
            'status' => 'completed',
        ]);

        return redirect()
            ->route('store.reservations.index')
            ->with('success', '予約を完了にしました');
    }
}
