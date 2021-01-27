<!DOCTYPE html>
<html lang="en">
<head>
    @include('templates.head')
    @yield('scripts_css')
</head>

<body class="nav-md">
<div class="container body layout-container">
    <div class="main_container">
        @include('templates.header')
        @include('templates.sidebar')
        @include('templates.plugin')

        <br><br><br>
        <div class="right_col container-fluid" role="main" id="rightCol">
            <div class="m-loader--mg loader-page"><span class="text-white">CARGANDO</span></div>
                @include('templates.boton_flotante')
                @yield('content')
        </div>
    </div>
    {{--@include('templates.footer')--}}
</div>

<!--===============================================================================================-->
<script src="{{ asset('js/template/jquery.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/template/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/template/popper.js') }}"></script>
<!--===============================================================================================-->
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
{{--<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>--}}
<script src="{{ asset('js/datatable_responsive/dataTables.responsive.min.js') }}"></script>
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<!--===============================================================================================-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<!--===============================================================================================-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!--===============================================================================================-->
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>--}}
<script src="{{ asset('select2-4.0.10/dist/js/select2.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/template/sidebar.js') }}"></script>
<script src="{{ asset('js/template/sidebar2.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/template/custom.js') }}"></script>
<!--===============================================================================================-->
{{--<script src="{{ asset('js/template/custom.min.js') }}"></script>--}}
<!--===============================================================================================-->
<script src="https://datatables.net/dev/dataTables.fixedHeader.js"></script>

@include('templates.script')

@yield('scripts')

</body>
</html>
