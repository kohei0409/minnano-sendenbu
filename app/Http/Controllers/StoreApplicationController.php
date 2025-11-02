<?php

namespace App\Http\Controllers;

use App\Models\StoreApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StoreApplicationController extends Controller
{
    /**
     * Display the store application form.
     */
    public function create(): View
    {
        $industries = config('industries.categories');
        return view('store-applications.create', compact('industries'));
    }

    /**
     * Store a new store application.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        StoreApplication::create($validated);

        return redirect()->route('store-applications.success');
    }

    /**
     * Display success message after application submission.
     */
    public function success(): View
    {
        return view('store-applications.success');
    }
}
