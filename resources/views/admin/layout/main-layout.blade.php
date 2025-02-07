@include('admin.layout.header')
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::navbar-->
        @include('admin.layout.navbar')
        <!--end::navbar-->

        <!--begin::Sidebar-->
        @include('admin.layout.sidebar')
        <!--end::Sidebar-->

        <!--begin::App Main-->
        <main class="app-main">

            @yield('content')
           
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        @include('admin.layout.footer')
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->


    <!--begin::Script-->
    @include('admin.layout.scripts')
    <!--end::Script-->
</body>
<!--end::Body-->
</html>