<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="{{ asset('img/anikama.ico') }}">

    <title>Sprinter Web - Software de Gestión</title>

    <link rel="stylesheet" href="{{ asset('css/login/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/skins/blueAnikama.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/fontello/css/fontello.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/login/plugins/magnific-popup/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/plugins/owl-carousel/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login/font-min/flaticon.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">


</head>

<body class="nav-md" style="overflow-x: hidden;">
<div class="page-wrapper">

    <!-- HEADER MENU -->
    <div class="header-top dark" style="background: black;">
        <div class="container">
            <div class="row">

                <div class="col-xs-4 col-sm-5" style="padding-right: 0px;">
                    <div class="header-top-first clearfix">
                        <font color="white">
                            <ul class="social-links clearfix " style="border-right: 1px solid transparent;">
                                <li class="facebook" style="border-right: 1px solid transparent;"><a target="_blank" href="http://www.facebook.com/AnikamaGroup" style="font-size: 13px;">
                                        <i class="fa fa-facebook"></i></a></li>
                                <li class="twitter" style="border-right: 1px solid transparent;"><a target="_blank" href="http://www.twitter.com/AnikamaGroup" style="font-size: 13px;">
                                        <i class="fa fa-twitter"></i></a></li>
                                <li class="youtube" style="border-right: 1px solid transparent;"><a target="_blank" href="https://www.youtube.com/channel/UCr4MCkY4untC_2nNNVGYFsA" style="font-size: 13px;">
                                        <i class="fa fa-youtube-play"></i></a></li>
                            </ul>
                        </font>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-3"></div>

                <div class="col-xs-8 col-sm-4" style="padding-left: 0px;">
                    <div class="header-top-dropdown" style="margin-right: 18px;">
                        <div class="btn-group hidden-xs" style="margin-top: 8px;">
                            <a target="_blank" href="https://sprinter.anikamagroup.com" style="text-decoration:none; font-size: 12px;"><font color="whitesmoke"> Sprinter Web &nbsp; &nbsp; &nbsp;</font></a>
                        </div>
                        <div class="btn-group" style="margin-top: 8px;">
                            <a target="_blank" href="https://esprinter.anikamagroup.com" style="text-decoration:none; font-size: 12px;"><font color="whitesmoke"> e-Sprinter &nbsp; &nbsp; &nbsp;</font></a>
                        </div>
                        <div class="btn-group" style="margin-top: 8px;">
                            <a target="_blank" href="https://soporte.anikamagroup.com" style="text-decoration:none; font-size: 12px;"><font color="whitesmoke">Soporte &nbsp; &nbsp;</font></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ============================================================== -->

    <!-- HEADER MENU -->
    <header class="header fixed header-small clearfix">
        <div class="container" style="align:center">
            <div class="row">
                <!-- header-left start -->
                <div class="col-md-4">
                    <div class="header-left clearfix">
                        <div class="logo">
                            <a href="http://www.anikamagroup.com/" class="# hidden-xs"><img id="logo" src="{{ asset('img/anikamagroup.png') }}" alt="Anikama  Group S.A.C."></a>
                        </div>
                    </div>
                </div>
                <!-- header-left end -->
                <!-- header-right start -->
                <div class="col-md-8">
                    <div class="header-right clearfix">
                        <!-- main-navigation start -->
                        <div class="main-navigation animated">
                            <!-- Barra de navegación -->
                            <nav class="navbar navbar-default" role="navigation">
                                <div class="container-fluid">
                                    <!-- Agrupados en un desglosable para una mejor vista en dispositivos móviles -->
                                    <div class="navbar-header">
                                        <div class="col-xs-9">
                                            <!-- logo moviles -->
                                            <div class="logo">
                                                <a href="http://www.anikamagroup.com/" class="# hidden-lg hidden-md hidden-sm">
                                                    <img id="logo" src="{{ asset('img/anikamagroup.png') }}" style="margin-top: 0.5em;" alt="Anikama Group S.A.C."></a>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-2">
                                                <span class="sr-only">Menú</span>
                                                <span class="icon-bar" style="background-color: #428bca"></span>
                                                <span class="icon-bar" style="background-color: #428bca"></span>
                                                <span class="icon-bar" style="background-color: #428bca"></span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="collapse navbar-collapse" id="navbar-collapse-2">
                                        <ul class="nav navbar-nav navbar-right" >
                                            <li><a href="http://www.anikamagroup.com" class="dropdown-toggle" data-toggle="">Inicio</a></li>

                                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> SOLUCIONES</a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="http://www.anikamagroup.com/sprinter"><i class="fa fa-laptop">
                                                            </i>&nbsp;&nbsp; Sprinter - Software de Gestión</a></li>
                                                    <li><a href="http://www.anikamagroup.com/eSprinter"><i class="fa fa-globe">
                                                            </i>&nbsp;&nbsp; eSprinter - Facturación Electrónica</a></li>
                                                    <li><a href="http://www.anikamagroup.com/servidornube"><i class="fa fa-cloud">
                                                            </i>&nbsp;&nbsp; Servidores dedicados en la nube</a></li>
                                                    <li><a href="http://www.anikamagroup.com/gruposaeta"><i class="fa fa-certificate">
                                                            </i>&nbsp;&nbsp; Certificados y firmas digitales</a></li>
                                                </ul>
                                            </li>

                                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> SERVICIOS</a>
                                                <ul class="dropdown-menu">
                                                    <li><a href="http://www.anikamagroup.com/lourtec"><i class="fa fa-graduation-cap">
                                                            </i>&nbsp;&nbsp; Cursos Microsoft, Cisco y PMI</a></li>
                                                    <li><a href="http://www.anikamagroup.com/paginasweb"><i class="fa fa-link">
                                                            </i>&nbsp;&nbsp; Creación de páginas web</a></li>
                                                    <li><a href="http://www.anikamagroup.com/contactos"><i class="fa fa-info-circle">
                                                            </i>&nbsp;&nbsp; Asesoría y consultoría TI</a></li>
                                                </ul>
                                            </li>

                                            <li class="active"><a href="http://www.anikamagroup.com/blog_noticias" class="dropdown-toggle" data-toggle="">NOTICIAS</a></li>

                                            <li class="active"><a href="http://www.anikamagroup.com/contactos" class="dropdown-toggle" data-toggle="">CONTACTOS</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <!-- navbar end -->
                        </div>
                        <!-- main-navigation end -->
                    </div>
                    <!-- header-right end -->
                </div>
            </div>
        </div>
    </header>
    <!--END MENU LEFT SIDE-->
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    @yield('content')

    <div class="modal fade" tabindex="-1" role="dialog" id="modalBrochure">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Brochure informativo</h4>
                </div>
                <div class="modal-body">
                    <object data="/Sprinter_Web_Brochure_2018.pdf" type="application/pdf" style="width: 100%;height: 500px;">
                        <iframe src="/Sprinter_Web_Brochure_2018.pdf"></iframe>
                    </object>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <footer style="width: 100%; bottom: 0; z-index: 100; position: fixed;">
        <div class="subfooter">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <p><font color="white" size="1">2017 © <a href="http://www.anikamagroup.com" style="text-decoration: none; color: white;">ANIKAMA GROUP S.A.C.</font></a></p>
                    </div>

                    <div class="col-md-7 hidden-xs hidden-sm">
                        <nav class="navbar navbar-default" role="navigation">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-2">
                                    <span class="sr-only">Menú</span>
                                    <span class="icon-bar" style="background: #428bca"></span>
                                    <span class="icon-bar" style="background: #428bca"></span>
                                    <span class="icon-bar" style="background: #428bca"></span>
                                </button>
                            </div>
                            <div class="collapse navbar-collapse" id="navbar-collapse-2">
                                <ul class="nav navbar-nav">
                                    <li class="nav_footer"><a href="http://www.anikamagroup.com/sobre_nosotros">Sobre Nosotros</a></li>
                                    <li class="nav_footer"><a href="http://www.anikamagroup.com/sobre_nosotros#partners" class="smooth" data-alt="#partners">Partners</a></li>
                                    <li class="nav_footer"><a href="http://www.anikamagroup.com/sobre_nosotros#clientes" class="smooth" data-alt="#clientes">Clientes</a></li>
                                    <li class="nav_footer"><a href="http://www.anikamagroup.com/contactos">Contactos</a></li>
                                    <li class="nav_footer"><a href="http://www.anikamagroup.com/sitemap">Mapa del Sitio</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
</body>
<script src="{{ asset('css/login/jquery.js') }}"></script>
<script src="{{ asset('css/login/bootstrap.js') }}"></script>
<script src="{{ asset('css/login/plugins/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('css/login/plugins/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>
<script src="{{ asset('css/login/plugins/modernizr.js') }}"></script>
<script src="{{ asset('css/login/plugins/isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('css/login/plugins/owl-carousel/owl.carousel.js') }}"></script>
<script src="{{ asset('css/login/plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('css/login/plugins/jquery.appear.js') }}"></script>
<script src="{{ asset('css/login/plugins/jquery.countTo.js') }}"></script>
<script src="{{ asset('css/login/plugins/jquery.parallax-1.1.3.js') }}"></script>
<script src="{{ asset('css/login/plugins/jquery.validate.js') }}"></script>
<script src="{{ asset('css/login/js/template.js') }}"></script>
<script src="{{ asset('css/login/js/custom.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@yield('scripts')

<script type="text/javascript">
    $(document).ready(function(){
        $('#btnBrochure').click(function(){
            $('#modalBrochure').modal('show');
        });
    });
</script>
</html>
