<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Store;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create(Store $store)
    {
        return view('public.reservations.create', compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        $validated = $request->validate([
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => 'required|date_format:H:i',
            'number_of_people' => 'required|integer|min:1|max:100',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'message' => 'nullable|string',
        ]);

        $reservation = $store->reservations()->create([
            'customer_id' => auth('customer')->id() ?? null,
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $validated['reservation_time'],
            'number_of_people' => $validated['number_of_people'],
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('stores.show', $store)
            ->with('success', '予約リクエストを送信しました。店舗からの確認をお待ちください。');
    }

    public function show(Reservation $reservation)
    {
        return view('public.reservations.show', compact('reservation'));
    }
}
