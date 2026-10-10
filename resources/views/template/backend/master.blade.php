<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    @include('template.backend.css')
    @livewireStyles
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      @include('template.backend.header')
        @include('template.backend.sidebar')
        @yield('content')
      @include('template.backend.footer')
    </div>
    <!--end::App Wrapper-->
    @include('template.backend.js')
    @livewireScripts
  </body>

  <!--end::Body-->
</html>