<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportPriority;
use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use App\Services\ReportWorkflowService;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function __construct(private ReportWorkflowService $workflow)
    {
    }

    public function index(Request $request)
    {
        $reports = Report::with('category')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . trim($request->query('q')) . '%';
                $q->where(fn ($w) => $w->where('title', 'like', $term)
                    ->orWhere('location', 'like', $term)
                    ->orWhere('report_code', 'like', $term));
            })
            ->when(in_array($request->query('status'), ReportStatus::values(), true), fn ($q) => $q->where('status', $request->query('status')))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', (int) $request->query('category')))
            ->when(in_array($request->query('priority'), ReportPriority::values(), true), fn ($q) => $q->where('priority', $request->query('priority')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.reports.index', compact('reports', 'categories'));
    }

    public function show(Report $report)
    {
        $report->load(['category', 'updates.user', 'attachments']);

        $attachments = $report->attachments->groupBy('attachment_type');

        return view('admin.reports.show', compact('report', 'attachments'));
    }

    public function verify(Request $request, Report $report)
    {
        $data = $request->validate([
            'priority' => ['required', Rule::in(ReportPriority::values())],
        ], ['priority.required' => 'Prioritas wajib dipilih sebelum verifikasi.']);

        try {
            $this->workflow->verify($report, $request->user(), ReportPriority::from($data['priority']));
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Laporan berhasil diverifikasi dan kini tampil di daftar laporan publik.');
    }

    public function reject(Request $request, Report $report)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min' => 'Alasan penolakan minimal :min karakter.',
            'reason.max' => 'Alasan penolakan maksimal :max karakter.',
        ]);

        try {
            $this->workflow->reject($report, $request->user(), $data['reason']);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Laporan telah ditolak.');
    }

    public function status(Request $request, Report $report)
    {
        $data = $request->validate([
            'status' => ['nullable', Rule::in(ReportStatus::values())],
            'priority' => ['required', Rule::in(ReportPriority::values())],
            'note' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['nullable', 'boolean'],
            'photo_type' => ['nullable', Rule::in(['progress', 'after'])],
            'photos' => ['nullable', 'array', 'max:3'],
            'photos.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'photos.max' => 'Maksimal 3 foto per unggahan.',
            'photos.*.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',
            'photos.*.max' => 'Ukuran setiap foto maksimal 5 MB.',
            'photos.*.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya maksimal 5 MB.',
            'note.max' => 'Catatan maksimal :max karakter.',
        ]);

        try {
            $changed = $this->workflow->update(
                $report,
                $request->user(),
                filled($data['status'] ?? null) ? ReportStatus::from($data['status']) : null,
                ReportPriority::from($data['priority']),
                $data['note'] ?? null,
                $request->boolean('is_public'),
                $request->file('photos', []),
                $data['photo_type'] ?? 'progress'
            );
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return back()->with($changed ? 'success' : 'info', $changed ? 'Pembaruan laporan berhasil disimpan.' : 'Tidak ada perubahan yang disimpan.');
    }
}
