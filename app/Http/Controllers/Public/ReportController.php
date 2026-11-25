<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Models\Category;
use App\Models\Report;
use App\Services\ReportWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $member = Auth::guard('member')->user(); // opsional: hanya untuk mengisi otomatis

        return view('public.report-create', compact('categories', 'member'));
    }

    public function store(StoreReportRequest $request, ReportWorkflowService $workflow)
    {
        $data = $request->safe()->except('photos');
        $report = $workflow->createReport($data, $request->file('photos', []));

        return redirect()->route('reports.success')->with('report_code', $report->report_code);
    }

    public function success()
    {
        $code = session('report_code');

        if (! $code) {
            return redirect()->route('reports.create');
        }

        return view('public.report-success', compact('code'));
    }

    public function index(Request $request)
    {
        $reports = Report::with('category')
            ->publiclyVisible()
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . trim($request->query('q')) . '%';
                $q->where(fn ($w) => $w->where('title', 'like', $term)
                    ->orWhere('location', 'like', $term)
                    ->orWhere('report_code', 'like', $term));
            })
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', (int) $request->query('category')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->latest('verified_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('public.reports-index', compact('reports', 'categories'));
    }

    public function show(string $code)
    {
        $report = Report::with(['category', 'attachments'])
            ->publiclyVisible()
            ->where('report_code', $code)
            ->firstOrFail();

        $updates = $report->updates()->where('is_public', true)->latest()->latest('id')->get();

        return view('public.report-show', compact('report', 'updates'));
    }
}
