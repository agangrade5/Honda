<nav class="navbar user-info-navbar" role="navigation">
    <!-- Left links for user info navbar -->
    <ul class="user-info-menu left-links list-inline list-unstyled">
        <li class="hidden-sm hidden-xs">
            <a href="#" data-toggle="sidebar"><i class="fa-bars"></i></a>
        </li>
        <li>
            <h3 style="margin-top:26px;">{{$title}}</h3>
        </li>
    </ul>
    <!-- Right links for user info navbar -->
    <ul class="user-info-menu right-links list-inline list-unstyled">
        <li>
            <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-lock"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</nav>
