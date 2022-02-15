<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('inc.head')
    <title>@yield('title')</title>

    @yield('vue')

</head>
<body>

    <header class="header">
        @include('inc.header')
    </header>

    <main id="main" class="main container">

        @yield('content')

    </main>

    <footer class="footer">
        @include('inc.footer')
    </footer>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    @yield('scripts')

</body>
</html>