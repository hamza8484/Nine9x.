<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item navbar no-print">
    <div class="container-fluid">
        <div class="main-header-left">
            <div class="responsive-logo">
                <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/logo.png')}}" class="logo-1" alt="logo"></a>
                <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/logo-white.png')}}" class="dark-logo-1" alt="logo"></a>
                <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="logo-2" alt="logo"></a>
                <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="dark-logo-2" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
            <div class="main-header-center mr-3 d-sm-none d-md-none d-lg-block">
                <input class="form-control" placeholder="{{ __('home.search') }}" type="search">
                <button class="btn"><i class="fas fa-search d-none d-md-block"></i></button>
            </div>
        </div>
       


        <div class="main-header-right">
            <!-- رابط خطط الاشتراك -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('subscription.plans') }}">
                    <button class="btn btn-light"> {{ __('home.SubscriptionPlans') }}</button>
                </a>
            </li>

            <ul class="nav">
                <li class="">
                    <!-- روابط تغيير اللغة -->
                    <div class="language-switcher">
                        <a href="{{ route('setLocale', ['locale' => 'en']) }}" class="btn btn-light">{{ __('home.English') }}</a>
                        <a href="{{ route('setLocale', ['locale' => 'ar']) }}" class="btn btn-light">{{ __('home.Arabic') }}</a>
                    </div>
                </li>
            </ul>

            <div class="nav nav-item navbar-nav-right ml-auto">
                <div class="nav-link" id="bs-example-navbar-collapse-1">
                    <form class="navbar-form" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="{{ __('home.search') }}">
                            <span class="input-group-btn">
                                <button type="reset" class="btn btn-default">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button type="submit" class="btn btn-default nav-link resp-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>

                <!-- إشعارات -->
				@auth
                <!-- إشعارات -->
                <div class="dropdown nav-item main-header-notification">
                    <a class="new nav-link" href="{{ route('notifications.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>

                        @if (isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                            <span class="pulse-danger">{{ $unreadNotificationsCount }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu">
                        <div class="menu-header-content bg-primary text-right">
                            <div class="d-flex">
                                <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">{{ __('home.notifications') }}</h6>
                                <span class="badge badge-pill badge-warning mr-auto my-auto float-left">{{ __('home.mark_all_read') }}</span>
                            </div>
                            <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">
                                لديك {{ $unreadNotificationsCount }} إشعار غير مقروء
                            </p>
                        </div>
                        <div class="main-notification-list Notification-scroll">
                            @foreach (auth()->user()->unreadNotifications as $notification)
                                <a class="d-flex p-3 border-bottom" href="#">
                                    <div class="notifyimg bg-pink">
                                        <i class="la la-file-alt text-white"></i>
                                    </div>
                                    <div class="mr-3">
                                        <h5 class="notification-label mb-1">{{ $notification->data['message'] }}</h5>
                                        <div class="notification-subtext">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="dropdown-footer">
                            <a href="{{ route('notifications.index') }}">عرض جميع الإشعارات</a>
                        </div>
                    </div>
                </div>
                @endauth

               <!-- تحقق من وجود المستخدم -->
                @auth
                    <div class="dropdown main-profile-menu nav nav-item nav-link">
                        <a class="profile-user d-flex" href="#"><img alt="" src="{{ URL::asset('assets/img/faces/6..jpg') }}"></a>
                        <div class="dropdown-menu">
                            <div class="main-header-profile bg-primary p-3">
                                <div class="d-flex wd-100p">
                                    <div class="main-img-user">
                                        <img alt="" src="{{ URL::asset('assets/img/faces/6..jpg') }}" class="">
                                    </div>
                                    <div class="mr-3 my-auto">
                                        <!-- عرض اسم المستخدم والبريد الإلكتروني إذا كان مسجل الدخول -->
                                        <h6>{{ Auth::user()->name }}</h6>
                                        <span>{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- رابط لحساب المستخدم -->
                            <a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}">
                                <i class="bx bx-user-circle"></i> حسابي
                            </a>

                            <!-- رابط لتسجيل الخروج -->
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out"></i>تسجيل خروج
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <!-- إذا لم يكن المستخدم مسجلاً، سيتم توجيهه إلى صفحة تسجيل الدخول -->
                    <script>
                        window.location.href = "{{ route('login') }}";
                    </script>
                @endauth


            </div>
        </div>
    </div>
</div>
<!-- /main-header -->
