<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;

/**
 * Laporan PDF: halaman cetak yang rapi; PDF dibuat lewat "Cetak > Simpan sebagai PDF"
 * di browser, sehingga tidak butuh library tambahan (tanpa composer/terminal).
 */
class ExportController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.export.index', compact('categories'));
    }

    public function print(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ], [
            'from.date' => 'Tanggal awal tidak valid.',
            'to.date' => 'Tanggal akhir tidak valid.',
            'to.after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal awal.',
        ]);

        $reports = Report::with('category')
            ->when(in_array($request->query('status'), ReportStatus::values(), true), fn ($q) => $q->where('status', $request->query('status')))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', (int) $request->query('category')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->query('to')))
            ->orderBy('created_at')
            ->get();

        $filters = [
            'status' => ReportStatus::tryFrom((string) $request->query('status'))?->label() ?? 'Semua status',
            'category' => $request->filled('category') ? optional(Category::find((int) $request->query('category')))->name : 'Semua kategori',
            'from' => $request->query('from'),
            'to' => $request->query('to'),
        ];

        return view('admin.export.print', compact('reports', 'filters'));
    }
}
