<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu">
                @canany(['create_country', 'edit_country', 'create_city', 'edit_city', 'create_district', 'edit_district', 'admin_permission'])
                    <a href="#dashboard" data-active="true" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>{{ __('general.Location') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                @endcanany
                <ul class="collapse submenu list-unstyled show" id="dashboard" data-parent="#accordionExample">
                    @canany(['create_country', 'edit_country', 'delete_country', 'active_country', 'disactive_country', 'admin_permission'])
                        <li class="{{ request()->routeIs('countries.index') ? 'active' : '' }}">
                            <a href="{{ route('countries.index') }}"> {{ __('general.Countries') }} </a>
                        </li>
                    @endcanany
                    @canany(['create_city', 'edit_city', 'delete_city', 'active_city', 'disactive_city', 'admin_permission'])
                        <li class="{{ request()->routeIs('cities.index') ? 'active' : '' }}">
                            <a href="{{ route('cities.index') }}"> {{ __('general.Cities') }} </a>
                        </li>
                    @endcanany
                    @canany(['create_district', 'edit_district', 'delete_district', 'active_district', 'disactive_district', 'admin_permission'])
                        <li class="{{ request()->routeIs('districts.index') ? 'active' : '' }}">
                            <a href="{{ route('districts.index') }}"> {{ __('general.Districts') }} </a>
                        </li>
                    @endcanany
                </ul>

                @canany(['create_customer', 'edit_customer', 'delete_customer', 'active_customer', 'disactive_customer', 'admin_permission'])
                    <a href="#dashboard2" data-active="true" data-toggle="collapse" aria-expanded="true"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>{{ __('general.Customers') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled show" id="dashboard2" data-parent="#accordionExample">
                        <li class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">
                            <a href="{{ route('customers.index') }}"> {{ __('general.Customers') }} </a>
                        </li>
                    </ul>
                @endcanany

                @can('admin_permission')
                    {{-- <a href="#dashboard3" data-active="true" data-toggle="collapse" aria-expanded="true"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Roles</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled show" id="dashboard3" data-parent="#accordionExample">
                        <li class="{{ request()->routeIs('roles.index') ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}"> Roles </a>
                        </li>
                    </ul>

                    <a href="#dashboard4" data-active="true" data-toggle="collapse" aria-expanded="true"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Permission</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled show" id="dashboard4" data-parent="#accordionExample">
                        <li class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                            <a href="{{ route('permissions.index') }}"> Permission </a>
                        </li>
                    </ul> --}}


                    <a href="#dashboard5" data-active="true" data-toggle="collapse" aria-expanded="true"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>{{ __('general.Admins_Sypervisors') }}</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled show" id="dashboard5" data-parent="#accordionExample">
                        <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}"> {{ __('general.Admins_Sypervisors') }} </a>
                        </li>
                    </ul>
                @endcan
            </li>
        </ul>
    </nav>

</div>
<!--  END SIDEBAR  -->
