<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu">
                <a href="#dashboard" data-active="true" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>{{__('general.Location')}}</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled show" id="dashboard" data-parent="#accordionExample">
                    <li class="active">
                        <a href="{{route('countries.index')}}" class="{{request()->routeIs('cities.index') ? 'active' : ''}}"> {{__('general.Countries')}} </a>
                    </li>
                    <li>
                        <a href="{{route('cities.index')}}" class="{{request()->routeIs('cities.index') ? 'active' : ''}}"> {{__('general.Cities')}} </a>
                    </li>
                    {{-- <li>
                        <a href="{{route('users.index')}}" class="{{request()->routeIs('users.index') ? 'active' : ''}}"> Users </a>
                    </li> --}}
                </ul>
            </li>
        </ul>
    </nav>

</div>
<!--  END SIDEBAR  -->
