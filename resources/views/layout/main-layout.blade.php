@include('layout.header')
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::navbar-->
        @include('layout.navbar')
        <!--end::navbar-->

        <!--begin::Sidebar-->
        @include('layout.sidebar')
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">

            @yield('content')
           
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        @include('layout.footer')
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->


    <!--begin::Script-->
    @include('layout.scripts')
    <!--end::Script-->
</body>
<!--end::Body-->
</html>