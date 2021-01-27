@extends('templates.login')

@section('content')

    <div class="login_wrapper">
        <section class="main-container" style="margin-top: -25px;">
            <div class="container body">
                <div class="main_container">
                    <div class="container">
                        <div class="row">
                        </div>
                        <div class="row">
                            <input type="hidden" id="ruta" name="ruta" value="{{ route('login') }}">
                            <div class="main object-non-visible" data-animation-effect="fadeInDownSmall" data-effect-delay="300">
                                <div class="form-block center-block" style="width: 330px;">
                                    <center><h3 class="title">Iniciar Sesión</h3></center>
                                    <hr>
                                    <center><img src="{{ asset('img/sprinter3.png') }}" width="25%"></center><br>
                                    <form id="form_login" autocomplete="off">
                                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group has-feedback">
                                            <input type="email" class="form-control" id="usu_correo" name="usu_correo" placeholder="Usuario" onchange="email_check()" required>
                                        </div>

                                        <div class="icon1">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="Contraseña" required=""/>
                                        </div>

                                        <div id="div-empresa" style="padding-bottom: 8px; padding-top: 8px;">
                                            <select  id="empresa" class="form-control" name="empresa">
                                                <option value="" selected>-- Seleccione una Empresa --</option>
                                            </select>
                                        </div>

                                        <div style="padding-bottom: 8px;">
                                            <select id="periodo" class="form-control" name="periodo">
                                                <option value="" selected>-- Seleccione un Periodo --</option>
                                            </select>
                                        </div>


                                    </form>
                                        <center><button onclick="login()" class="btn btn-group btn-primary btn-sm" style="border-radius: 5px; text-transform: capitalize; padding: 5px; font-size: 14px; background: #3897f0; border-color: #3897f0;">Ingresar</button></center>
                                        <center><a href="htt://www.anikamagroup.com/contactos" style="text-decoration: none; font-size: 12px;">¿Olvidaste tu contraseña?</a></center>

                                </div>
                                <div class="form-block reg center-block" style="width: 330px; margin-top: 10px;">
                                    <center><a href="#" disabled style="text-decoration: none; color:#262626; font-size: 13px; cursor: context-menu;">¿No tienes una cuenta?</a> <a href="{{ route('register') }}" style="font-size: 13px; text-decoration: none;">Regístrate</a></center>
                                    <center><a href="#" id="btnBrochure" name="btnBrochure">Ver brochure informativo.</a></center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/login.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
@endsection
