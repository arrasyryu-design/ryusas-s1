@props(['report'])
<a href="{{ route('reports.show', $report->report_code) }}" class="sp-card sp-card-link sp-report-card">
    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <span class="sp-muted t-label-sm"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $report->location }}</span>
        <x-status-badge :status="$report->status" />
    </div>
    <h3 class="t-title-md mb-0">{{ $report->title }}</h3>
    <p class="sp-muted t-body-md mb-0 sp-clamp">{{ $report->description }}</p>
    <div class="d-flex align-items-center justify-content-between gap-2 mt-auto pt-2 border-top" style="border-color: var(--sp-cream-deep) !important;">
        <span class="t-label-sm sp-muted">{{ $report->category->name }}</span>
        <span class="t-label-sm sp-muted">{{ ($report->verified_at ?? $report->created_at)->translatedFormat('d M Y') }}</span>
    </div>
</a>
