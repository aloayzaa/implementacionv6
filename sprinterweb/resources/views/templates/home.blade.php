@extends('templates.app')

@section('content')

    <div class="row top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12 item__card_home">
            <div class="tile-stats alert-danger">
                <div class="icon text-light"><i class="fa fa-building text-white"></i></div>
                <h3>Empresas</h3>
                <p>Registradas</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12 item__card_home">
            <div class="tile-stats alert-info">
                <div class="icon"><i class="fa fa-users text-white"></i></div>
                <h3 class="text-white">Usuarios</h3>
                <p>Registrados</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12 item__card_home">
            <div class="tile-stats alert-success">
                <div class="icon"><i class="fa fa-check text-white"></i></div>
                <h3>Suscripciones</h3>
                <p>Activas</p>
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12 item__card_home">
            <div class="tile-stats alert-warning">
                <div class="icon"><i class="fa fa-certificate text-white"></i></div>
                <h3>Certificados</h3>
                <p>Registrados</p>
            </div>
        </div>
    </div>

{{--    <article class="bk-home-new">--}}
{{--        <div class="bk-home__item item_card_one">--}}
{{--            <div class="bk-home__card">--}}
{{--                <h2>EMPRESAS</h2>--}}
{{--                <p>Registradas</p>--}}
{{--            </div>--}}
{{--            <div class="bk-home__icon">--}}
{{--                <i class="fa fa-building text-white"></i>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="bk-home__item  item_card_two">--}}
{{--            <div class="bk-home__card">--}}
{{--                <h2>Usuarios</h2>--}}
{{--                <p>Registradas</p>--}}
{{--            </div>--}}
{{--            <div class="bk-home__icon">--}}
{{--                <i class="fa fa-users text-white"></i>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="bk-home__item item_card_third">--}}
{{--            <div class="bk-home__card">--}}
{{--                <h2>Suscripciones</h2>--}}
{{--                <p>Registradas</p>--}}
{{--            </div>--}}
{{--            <div class="bk-home__icon">--}}
{{--                <i class="fa fa-check text-white"></i>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="bk-home__item item_card_fourth">--}}
{{--            <div class="bk-home__card">--}}
{{--                <h2>Certificados</h2>--}}
{{--                <p>Registradas</p>--}}
{{--            </div>--}}
{{--            <div class="bk-home__icon">--}}
{{--                <i class="fa fa-certificate text-white"></i>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </article>--}}

    <section class="ctr-home">
        <article class="bk-home__art">
            <div class="home__card centrar card_color1">
                <div class="icon_div vertical">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="style_title">
                    <h2>690</h2>
                    <span>New</span>
                </div>
                <div class="bk-home_f centrar">
                    <span>Orders</span>
                    <span>1,12.900</span>
                </div>
            </div>
            <div class="home__card centrar card_color2">
                <div class="icon_div vertical">
                    <i class="far fa-user"></i>
                </div>
                <div class="style_title">
                    <h2>690</h2>
                    <span>New</span>
                </div>
                <div class="bk-home_f centrar">
                    <span>Orders</span>
                    <span>1,12.900</span>
                </div>
            </div>
            <div class="home__card centrar card_color3">
                <div class="icon_div vertical">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="style_title">
                    <h2>690</h2>
                    <span>New</span>
                </div>
                <div class="bk-home_f centrar">
                    <span>Orders</span>
                    <span>1,12.900</span>
                </div>
            </div>
            <div class="home__card centrar card_color4">
                <div class="icon_div vertical">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="style_title">
                    <h2>690</h2>
                    <span>New</span>
                </div>
                <div class="bk-home_f centrar">
                    <span>Orders</span>
                    <span>1,12.900</span>
                </div>
            </div>

        </article>
    </section>


@endsection
