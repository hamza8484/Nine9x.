<head>
    <!-- Title -->
    <title> @yield("title") </title>
    <!-- Favicon -->
    <link rel="icon" href="{{ URL::asset('assets/img/brand/favicon.png') }}" type="image/x-icon"/>
    
    <!-- Icons css -->
    <link href="{{ URL::asset('assets/css/icons.css') }}" rel="stylesheet">

    <!--  Custom Scroll bar-->
    <link href="{{ URL::asset('assets/plugins/mscrollbar/jquery.mCustomScrollbar.css') }}" rel="stylesheet"/>

    <!--  Sidebar css -->
    <link href="{{ URL::asset('assets/plugins/sidebar/sidebar.css') }}" rel="stylesheet">

    <!-- Sidemenu css -->
    <!-- قم بتحميل الـ CSS الخاص بالـ RTL أو LTR بناءً على اللغة -->
    @if(app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ URL::asset('assets/css-rtl/sidemenu.css') }}">
        <link href="{{ URL::asset('assets/css-rtl/style.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/css-rtl/style-dark.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/css-rtl/skin-modes.css') }}" rel="stylesheet">
    @else
        <!-- إذا كانت اللغة إنجليزية أو أي لغة أخرى (LTR) -->
        <link rel="stylesheet" href="{{ URL::asset('assets/css/sidemenu.css') }}">
        <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/css/style-dark.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/css/skin-modes.css') }}" rel="stylesheet">
    @endif

    <!-- إضافة الـ CSRF Token هنا -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('css')

</head>
