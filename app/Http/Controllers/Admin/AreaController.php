<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $areas = Area::with('parent')
            ->orderBy('level')
            ->orderBy('name')
            ->paginate(50);

        return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        $prefectures = Area::prefectures()->get();

        return view('admin.areas.create', compact('prefectures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:areas,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'level' => 'required|integer|in:1,2',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Area::create($validated);

        return redirect()
            ->route('admin.areas.index')
            ->with('success', 'エリアを作成しました');
    }

    public function edit(Area $area)
    {
        $prefectures = Area::prefectures()
            ->where('id', '!=', $area->id)
            ->get();

        return view('admin.areas.edit', compact('area', 'prefectures'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:areas,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'level' => 'required|integer|in:1,2',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $area->update($validated);

        return redirect()
            ->route('admin.areas.index')
            ->with('success', 'エリアを更新しました');
    }

    public function destroy(Area $area)
    {
        if ($area->stores()->count() > 0) {
            return redirect()
                ->route('admin.areas.index')
                ->with('error', 'このエリアには店舗が登録されているため削除できません');
        }

        $area->delete();

        return redirect()
            ->route('admin.areas.index')
            ->with('success', 'エリアを削除しました');
    }
}
