<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php // $black_logo = \App\AdminSetting::find(1)->black_logo; ?>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{url('admin/dashboard')}}">
            {{-- <img src="{{asset('storage/images/app/'.$black_logo)}}" class="navbar-brand-img sidebar-logo" alt="..."> --}}
        </a>
        
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        {{-- <img alt="Image placeholder" src="{{asset('storage/images/users/'.Auth()->user()->image)}}"> --}}
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    {{-- <a href="{{url('/admin/profile/'.Auth::user()->id)}}" class="dropdown-item"> --}}
                        <i class="ni ni-single-02"></i>
                        <span>{{__('My profile')}}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{url('/admin/logout/')}}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a class="navbar-brand pt-0" href="{{url('admin/dashboard')}}">
                            {{-- <img src="{{asset('storage/images/app/'.$black_logo)}}" class="navbar-brand-img" alt="..."> --}}
                        </a>
                    </div>
                </div>
            </div>
            {{-- Main Admin --}}
            <ul class="navbar-nav">
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard')  ? 'active' : ''}}" href="{{url('admin/dashboard')}}">
                        <i class="ni ni-tv-2 text-teal"></i>
                        <span class="nav-link-text">{{ __('Dashboard') }}</span>
                    </a>
                </li>  --}}

                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/calendar*')  ? 'active' : ''}}" href="{{url('admin/calendar')}}">
                        <span class="nav-link-text">{{ __('Calender') }}</span>
                    </a>
                </li> --}}
                    
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/booking*')  ? 'active' : ''}}" href="{{url('admin/booking')}}">
                    <span class="nav-link-text">{{ __('Booking') }}</span>
                    </a>
                </li>
                
                

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/users*')  ? 'active' : ''}}" href="{{url('admin/users')}}">
                    <span class="nav-link-text">{{__('Users')}}</span>
                    </a>
                </li>
                

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/employee*')  ? 'active' : ''}}" href="{{url('admin/employee')}}">
                    <span class="nav-link-text">{{ __('Therapist') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('fetchreported')  ? 'active' : ''}}" href="{{url('fetchreported')}}">
                    <span class="nav-link-text">{{ __('Reported') }}</span>
                    </a>
                </li>
                
                
            </ul>
        </div>
    </div>
</nav>
