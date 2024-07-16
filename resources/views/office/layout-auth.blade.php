<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{{ env("APP_NAME") }} | @yield("title")</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ a("/res/office/css/adminlte.css") }}">
        <link rel="stylesheet" href="{{ a("/res/office/css/style.css") }}">
        <link rel="stylesheet" href="{{ a("/res/libs/jquery-ui-1.13.2/jquery-ui.css") }}">
        <link rel="stylesheet" href="{{ a("/res/libs/select2/css/select2.min.css") }}">
        <link rel="stylesheet" href="{{ a("/res/libs/select2/css/select2-bootstrap.min.css") }}">
    </head>
    
    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
        <div class="app-wrapper">
            <nav class="app-header navbar navbar-expand bg-body">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                                <i class="bi bi-list"></i>
                            </a>
                        </li>
                    </ul>
                        
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown user-menu">
                            <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <span class="d-none d-md-inline">{{ Auth::guard("office")->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <li class="user-footer">
                                    <a href="{{ route("office.profile") }}" class="btn btn-secondary btn-sm btn-icon">
                                        {{ __("Profil") }}
                                        <i class="bi bi-person-gear"></i>
                                    </a>
                                    <a href="{{ route("office.logout") }}" class="btn btn-warning btn-sm float-end">
                                        {{ __("Wyloguj się") }}
                                        <i class="bi bi-box-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
                <div class="sidebar-brand">
                    <a href="{{ route("office") }}" class="brand-link">
                        <img src="/res/office/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow">
                        <span class="brand-text fw-light">{{ env("APP_NAME") }}</span>
                    </a>
                </div>
                <div class="sidebar-wrapper">
                    @include("office.partials.sidebar")
                </div>
            </aside>
                
            <main class="app-main">
                <div class="app-content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 lh-1">
                                <h3 class="mb-0">@yield("title")</h3>
                                <small class="text-muted">@yield("subtitle")</small>
                            </div>
                            <div class="col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route("office") }}">{{ __("Start") }}</a>
                                    </li>
                                    @yield("breadcrumbs")
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-content">
                    <div class="container-fluid">
                        @yield("content")
                    </div>
                </div>
            </main>
                
            <footer class="app-footer">
                <div class="float-end d-none d-sm-inline">
                    <a href="https://kambit.pl" class="text-decoration-none" target="_blank">kambit.pl</a>
                </div>
            </footer>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <script src="/res/libs/ckeditor/ckeditor.js"></script>
        <script src="{{ a("/res/libs/jquery-ui-1.13.2/jquery-ui.js") }}"></script>
        <script src="{{ a("/res/libs/datepicker-pl.js") }}"></script>
        <script src="{{ a("/res/office/js/adminlte.js") }}"></script>
        <script src="{{ a("/res/libs/select2/js/select2.full.min.js") }}"></script>
        <script src="{{ a("/res/office/js/app.js") }}"></script>
        <script src="{{ a("/res/libs/modal.js") }}"></script>
        <script src="{{ a("/res/libs/validator.js") }}"></script>
        @yield("scripts")
        
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
            <div id="toast-message" class="toast text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </body>
        
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
        
        App.setLoginActivityInterval("{{ route("office.check-activity", [], false) }}");
    </script>
</html>