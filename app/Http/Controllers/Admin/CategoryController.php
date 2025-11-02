<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $categories = Category::with('parent')
            ->orderBy('display_order')
            ->paginate(50);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::rootCategories()->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'カテゴリーを作成しました');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::rootCategories()
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'カテゴリーを更新しました');
    }

    public function destroy(Category $category)
    {
        if ($category->stores()->count() > 0) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'このカテゴリーには店舗が登録されているため削除できません');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'カテゴリーを削除しました');
    }
}
