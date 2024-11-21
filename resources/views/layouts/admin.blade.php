<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', appLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @yield('meta_tags')
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ mix('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ mix('css/selectpicker.css') }}">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    @yield('styles')
</head>

<body>
    <div id="app">
        @include('layouts.partials.back_to_top_btn')
        @include('layouts.partials.loading')
        @include('layouts.admin_partials.modals')
        @include('layouts.admin_partials.sidebar')

        <div id="main" class='layout-navbar'>
            @include('layouts.admin_partials.header')

            <div id="main-content">
                <div class="page-heading">

                    <div class="page-title">
                        @yield('page_title')
                    </div>

                    @yield('content')

                </div>

                @include('layouts.admin_partials.footer')

            </div>
        </div>

    </div>

    <script src="{{ mix('js/admin.js') }}"></script>
    <script src="{{ mix('js/selectpicker.js') }}"></script>
    <script src="{{ mix('js/functions.js') }}"></script>

    @if (appLocale() != 'en')
        <script src="{{ mix('js/datepicker/bootstrap-datepicker.' . appLocale() . '.js') }}"></script>
    @endif

    <script>
        createSelectPickerAll('.selectPicker', {
            language: "{{ appLocale() }}"
        });

        window.onload = function() {
            $('.datepicker').datepicker({
                clearBtn: true,
                todayBtn: 'linked',
                todayHighlight: true,
                weekStart: 1,
                enableOnReadonly: false,
                format: 'dd/mm/yyyy',
                language: "{{ appLocale() }}",
                showOnFocus: true
            });

            $('.rangedatepicker').datepicker({
                clearBtn: true,
                todayBtn: 'linked',
                todayHighlight: true,
                weekStart: 1,
                enableOnReadonly: false,
                format: 'dd/mm/yyyy',
                language: "{{ appLocale() }}",
                showOnFocus: true,
                inputs: $('.multidatepicker')
            });

        };
    </script>

    @yield('scripts')
</body>

</html>
