@extends('templates.login')

@section('content')

    <div class="login_wrapper">
        <section class="main-container" style="padding: 15px 15px;">
            <div class="container body">
                <div class="main_container">
                    <div class="container">
                        <div class="row">
                            <div class="main object-non-visible" data-animation-effect="fadeInDownSmall"
                                 data-effect-delay="300">
                                <div class="form-block center-block" style="width: 350px;">
                                    <center><h3 class="title">Regístrate</h3></center>
                                    <hr>
                                    <br>
                                    <input type="hidden" id="ruta" name="ruta" value="{{route('users.store')}}"/>

                                    <form action="" method="POST" id="frm_generales" autocomplete="off">
                                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}"/>
                                        {!! csrf_field() !!}

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="txt_ruc" name="txt_ruc"
                                                   onblur="busca_ruc()"
                                                   placeholder="Registro Único Contribuyentes" required autofocus/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="txt_empresa" name="txt_empresa"
                                                   placeholder=" Empresa" readonly tabindex="-1"
                                                   required/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="txt_address" name="txt_address"
                                                   placeholder=" Dirección" readonly tabindex="-1"
                                                   required/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="txt_ubigeo"
                                                   name="txt_ubigeo"
                                                   placeholder="Ubigeo" required readonly/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="txt_apellidos"
                                                   name="txt_apellidos"
                                                   placeholder="Apellidos" required/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="txt_nombre" name="txt_nombre"
                                                   placeholder="Nombres"
                                                   required/>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" id="txt_celular" name="txt_celular"
                                                   placeholder="Teléfono" required/>
                                        </div>

                                        <div class="form-group">
                                            <input type="email" class="form-control" id="txt_correo" name="txt_correo"
                                                   placeholder="Usuario"
                                                   required/>
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control"  id="txt_password" name="txt_password"
                                                   placeholder="Contraseña" required/>
                                        </div>

                                        <div class="form-group has-feedback">
                                            <input type="checkbox" name="check_terminos" id="check_terminos">
                                            Acepto los <a href="#" data-toggle="modal" data-target="#termsAndConditions">Términos y Condiciones</a>
                                        </div>
                                        <label class="text-danger" style="font-size: 12px"> (*) Este proceso puede
                                            tardar un par de minutos, sea paciente mientras configuramos su base de
                                            datos, gracias.</label>


                                        <center>
                                            <button id="btn_grabar" class="btn btn-group btn-primary btn-sm" style="border-radius: 5px; text-transform: capitalize; padding: 5px; font-size: 14px; background: #3897f0; border-color: #3897f0;">
                                                Siguiente
                                            </button>
                                        </center>
                                    </form>
                                </div>
                                <div class="form-block center-block" style="width: 350px; margin-top: 10px;">
                                    <center><a href="#" disabled
                                               style="text-decoration: none; color:#262626; font-size: 13px; cursor: context-menu;">¿Ya tienes una cuenta?</a> <a href="{{url('/')}}" style="font-size: 13px; text-decoration: none;">Entrar</a>
                                    </center>
                                </div>
                                <br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

    <div class="modal fade" id="termsAndConditions" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Folleto informativo
                    </h4>
                    <button type="button" class="close btn-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <object data="/test.pdf" type="application/pdf"
                            style="width: 100%;height: 500px;">
                        <iframe src="/test.pdf"></iframe>
                    </object>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/register.js') }}"></script>
@endsection
