<div class="sidebar-menu-inner">
    <header class="logo-env">
        <!-- logo -->
        <div class="logo">
            <a href="/" class="logo-expanded">
                <img src={{ asset("assets/images/logo@2x.png")}} width="200" alt="" />
            </a>
            <a href="/" class="logo-collapsed">
                <img src={{ asset("assets/images/logo-collapsed@2x.png")}} width="40" alt="" />
            </a>
        </div>
        <!-- This will toggle the mobile menu and will be visible only on mobile devices -->
        <div class="mobile-menu-toggle visible-xs">
            <a href="#" data-toggle="user-info-menu">
                <i class="fa-bell-o"></i>
                <span class="badge badge-success">7</span>
            </a>
            <a href="#" data-toggle="mobile-menu">
                <i class="fa-bars"></i>
            </a>
        </div>
    </header>
    <ul id="main-menu" class="main-menu">
        <li class="{{ request()->routeIs('manage-events.*') ? 'active' : '' }}">
            <a href="{{ route('manage-events.index') }}">
            <i class="linecons-location"></i>
            <span class="title">Events</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-trucks.*') ? 'active' : '' }}">
            <a href="{{ route('manage-trucks.index') }}">
            <i class="linecons-truck"></i>
            <span class="title">Trucks</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-inventory.*') ? 'active' : '' }}">
            <a href="{{ route('manage-inventory.index') }}">
            <i class="linecons-tag"></i>
            <span class="title">Inventory</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-regions.*') ? 'active' : '' }}">
            <a href="{{ route('manage-regions.index') }}">
            <i class="linecons-globe"></i>
            <span class="title">Regions</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-social-media.*') ? 'active' : '' }}">
            <a href="{{ route('manage-social-media.index') }}">
            <i class="glyphicon glyphicon-bullhorn"></i>
            <span class="title">Manage Social Media</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-countries.*') ? 'active' : '' }}">
            <a href="{{ route('manage-countries.index') }}">
            <i class="glyphicon glyphicon-flag"></i>
            <span class="title">Manage Countries</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-dealers.*') ? 'active' : '' }}">
            <a href="{{ route('manage-dealers.index') }}">
            <i class="glyphicon glyphicon-star-empty"></i>
            <span class="title">Manage Dealers</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-restricted-riders.*') ? 'active' : '' }}">
            <a href="{{ route('manage-restricted-riders.index') }}">
            <i class="glyphicon glyphicon-remove"></i>
            <span class="title">Manage Restricted Riders</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-groups.*') ? 'active' : '' }}">
            <a href="{{ route('manage-groups.index') }}">
            <i class="glyphicon glyphicon-th"></i>
            <span class="title">Vehicle Groups</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-signed-waivers.*') ? 'active' : '' }}">
            <a href="{{ route('manage-signed-waivers.index') }}">
            <i class="glyphicon glyphicon-paperclip"></i>
            <span class="title">Manage Signed Waiver</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-import-vehicles.*') ? 'active' : '' }}">
            <a href="{{ route('manage-import-vehicles.index') }}">
            <i class="glyphicon glyphicon-paperclip"></i>
            <span class="title">Manage Import Vehicle</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-models.*') ? 'active' : '' }}">
            <a href="{{ route('manage-models.index') }}">
            <i class="glyphicon glyphicon-wrench"></i>
            <span class="title">Manage Models</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-users.*') ? 'active' : '' }}">
            <a href="{{ route('manage-users.index') }}">
            <i class="linecons-user"></i>
            <span class="title">Users</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('manage-waivers.*') ? 'active' : '' }}">
            <a href="{{ route('manage-waivers.index') }}">
            <i class="linecons-doc"></i>
            <span class="title">Waivers</span>
            </a>
        </li>
        <li>
            <a href="ManageEmailTemplates.php">
            <i class="linecons-mail"></i>
            <span class="title">Email Templates</span>
            </a>
        </li>
        <li>
            <a href="ManageSMSTemplates.php">
            <i class="linecons-mail"></i>
            <span class="title">SMS Templates</span>
            </a>
        </li>
        <li>
            <a href="ManageSurveys.php">
            <i class="linecons-globe"></i>
            <span class="title">Manage Surveys</span>
            </a>
        </li>
        <li>
            <a href="ManageData.php">
            <i class="linecons-globe"></i>
            <span class="title">Data Management</span>
            </a>
        </li>
        <li>
            <a href="ManageBikesAndTimes.php">
            <i class="linecons-globe"></i>
            <span class="title">Manage Bikes and Times</span>
            </a>
        </li>
        <li>
            <a href="PreRegEmails.php">
            <i class="linecons-mail"></i>
            <span class="title">Pre Reg-Emails</span>
            </a>
        </li>
        <li>
            <a href="ManagePreRegistrationHTML.php">
            <i class="linecons-mail"></i>
            <span class="title">Manage Pre Registration</span>
            </a>
        </li>
        <li>
            <a href="GenerateCards.php">
            <i class="linecons-globe"></i>
            <span class="title">Generate Cards</span>
            </a>
        </li>
    </ul>
</div>
