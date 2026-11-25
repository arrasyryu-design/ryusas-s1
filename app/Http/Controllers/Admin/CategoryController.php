<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('reports')->orderByDesc('is_active')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $category = Category::create($data + ['slug' => $this->uniqueSlug($data['name']), 'is_active' => true]);

        ActivityLogger::log('category', "Menambahkan kategori {$category->name}", $category);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category);
        $category->update($data + ['slug' => $this->uniqueSlug($data['name'], $category->id)]);

        ActivityLogger::log('category', "Mengubah kategori {$category->name}", $category);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /** Kategori tidak pernah dihapus permanen; hanya diaktifkan/dinonaktifkan. */
    public function toggle(Category $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        ActivityLogger::log('category', ($category->is_active ? 'Mengaktifkan' : 'Menonaktifkan') . " kategori {$category->name}", $category);

        return back()->with('success', 'Status kategori diperbarui.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'name.max' => 'Nama kategori maksimal :max karakter.',
            'description.max' => 'Deskripsi maksimal :max karakter.',
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'kategori';
        $slug = $base;
        $i = 2;

        while (Category::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
