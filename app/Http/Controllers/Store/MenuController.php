<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $store = auth()->user()->store;
        $menus = $store->menus()->paginate(20);

        return view('store.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('store.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:5120',
            'is_available' => 'boolean',
        ]);

        $store = auth()->user()->store;

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')
                ->store('menu-images', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        $store->menus()->create($validated);

        return redirect()
            ->route('store.menus.index')
            ->with('success', 'メニューを追加しました');
    }

    public function edit(Menu $menu)
    {
        $this->authorize('update', $menu);

        return view('store.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', $menu);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|max:5120',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }
            $validated['image_path'] = $request->file('image')
                ->store('menu-images', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        $menu->update($validated);

        return redirect()
            ->route('store.menus.index')
            ->with('success', 'メニューを更新しました');
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);

        if ($menu->image_path) {
            Storage::disk('public')->delete($menu->image_path);
        }

        $menu->delete();

        return redirect()
            ->route('store.menus.index')
            ->with('success', 'メニューを削除しました');
    }
}
