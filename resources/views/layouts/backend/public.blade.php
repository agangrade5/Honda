<!DOCTYPE html>
<html lang="en">
    <head>
        @include('layouts.backend.head')
    </head>
    <body class="page-body">
        <div class="page-container">
            <!-- content @s -->
            @yield('content')
            <!-- content @e -->
        </div>
        <div class="page-loading-overlay">
            <div class="loader-2"></div>
        </div>
        <!-- Footer Scripts -->
        @include('layouts.backend.footer_scripts')
        <!-- Custom Scripts -->
        @stack('scripts')
    </body>
</html>
