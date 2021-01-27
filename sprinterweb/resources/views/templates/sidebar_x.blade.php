<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title">
            <a href="#" id="menuSprinter" class="menu_toggle site_title"><img src="{{ asset('img/sprinter.png') }}">
                <span>SPRINTER WEB</span></a>
        </div>

        <div class="clearfix"></div>

        {{--        <!-- menu profile quick info -->--}}
        {{--        <div class="profile clearfix">--}}
        {{--            <div class="profile_pic">--}}
        {{--                <img src="{{ asset('img/programer.png') }}" alt="..." class="img-circle profile_img">--}}
        {{--            </div>--}}
        {{--            <div class="profile_info">--}}
        {{--                <span class="text-w">Bienvenido,</span>--}}
        {{--                --}}{{--                <h2>{{ \Auth::user()->usu_apellidos .' '. \Auth::user()->usu_nombres }}</h2>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <!-- /menu profile quick info -->--}}

        <br/>




        <!-- sidebar menu -->

        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section content contentScroll mCustomScrollbar">
                {{--                <h3>General</h3>--}}
                <ul class="nav side-menu">
                    {!! \Menu::buildMenu() !!}
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->

        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Configuración">
                <i class="material-icons">settings</i>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Usuario">
                <i class="material-icons">face</i>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Buscar">
                <i class="material-icons">search</i>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="" href="{{route('logout')}}"
               data-original-title="Salir">
                <i class="material-icons">power_settings_new</i>
            </a>
        </div>

    </div>
</div>
