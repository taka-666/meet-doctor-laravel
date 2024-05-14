<!DOCTYPE html>
<html lang="en">
    <head>
        @include('includes.frontsite.meta')
        
        <title>@yield('title') | MeetDoctor</title>
        
        @stack('before-style')
            @include('includes.frontsite.style')
        @stack('after-style')
    </head>


    <body>

        @include('sweetalert::alert')
        
        @include('components.frontsite.header')
            @yield('content')
        @include('components.frontsite.footer')

        @stack('before-script')
            @include('includes.frontsite.script')
        @stack('after-script')


        {{-- Modals here --}}
        {{-- if you have a modals, create here!! --}}
        </body>
</html>