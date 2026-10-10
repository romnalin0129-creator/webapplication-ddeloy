<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

<html lang="en">

<head>
    @include('template.frontend.css')
</head>
<body>
    @include('template.frontend.header')
<div class="py-30"></div>

    @yield('content')

<div class="py-40"></div>
    @include('template.frontend.footer')
    @include('template.frontend.js')
    @livewireScripts
</body>
</html>
