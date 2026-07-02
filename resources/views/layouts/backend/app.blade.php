<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.backend.head')
    </head>
    <body class="page-body">
        @include('layouts.backend.settings_panel')
        <div class="page-container">
            <div class="sidebar-menu toggle-others fixed">
                @include('layouts.backend.left_menu')
            </div>

            <!-- content @s -->
            @yield('content')
            <!-- content @e -->

            <!-- Chat Window -->
            @include('layouts.backend.chat')
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
