<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') — SIPEKA Admin</title>
    @include('partials.head-assets')
</head>
<body>
@php $admin = auth('web')->user(); @endphp

<aside class="sp-sidebar sp-sidebar-fixed d-none d-lg-flex" aria-label="Sidebar admin">
    @include('partials.admin-menu')
</aside>

<div class="offcanvas offcanvas-start sp-sidebar" tabindex="-1" id="adminDrawer" aria-label="Menu admin">
    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="offcanvas" aria-label="Tutup menu"></button>
    <div class="d-flex flex-column h-100">
        @include('partials.admin-menu')
    </div>
</div>

<div class="sp-main">
    <header class="sp-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sp-outline btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminDrawer" aria-controls="adminDrawer" aria-label="Buka menu admin">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
            <h1 class="t-title-lg mb-0">@yield('page_title', 'Panel Admin')</h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="t-label-lg lh-sm">{{ $admin->name }}</div>
                <div class="t-label-sm sp-muted">Admin Sarpras</div>
            </div>
            <span class="sp-avatar" aria-hidden="true">{{ $admin->initials() }}</span>
        </div>
    </header>

    <div class="sp-content">
        <x-flash-message />
        @yield('content')
    </div>
</div>

@include('partials.foot-assets')
</body>
</html>
