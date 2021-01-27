@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Facturación Electrónica - CPE<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_cpe" name="frm_conf_cpe" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-sm-7 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Emisor</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">RUC</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_ruc" id="cpe_ruc" value="{{ $cpe_ruc }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Razón Social</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_nombre" id="cpe_nombre" value="{{ $cpe_nombre }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Dirección</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_direccion" id="cpe_direccion" value="{{ $cpe_direccion }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Ubigeo</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="cbo_ubigeo" id="cbo_ubigeo" class="form-control select2">
                                                            <option value="{{ $cpe_ubigeo->codigo }}">{{ $cpe_ubigeo->codigo }} || {{ $cpe_ubigeo->descripcion }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Ciudad</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_ciudad" id="cpe_ciudad" value="{{ $cpe_ciudad }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Usuario SOL/OSE</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_usuario" id="cpe_usuario" value="{{ $cpe_usuario }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Clave SOL/OSE</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="password" name="cpe_clave" id="cpe_clave" value="{{ $cpe_clave }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Firma Digital</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">RUC</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cer_ruc" id="cer_ruc" value="{{ $cer_ruc }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Razón Social</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cer_nombre" id="cer_nombre" value="{{ $cer_nombre }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Certificado (PFX)</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="file" name="cer_pfx" id="cer_pfx" value="{{ $cer_pfx }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Clave Certificado</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="password" name="cer_clave" id="cer_clave" value="{{ $cer_clave }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Emisor</div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <button type="button" class=" btn btn-success" id="emisor"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="col-md-12">
                                                <br>
                                                    <table id="" class="table table-striped table-bordered table-bordered2" width="100%">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>Tipo Doc</th>
                                                                <th>%</th>
                                                                <th>Días Emitida</th>
                                                                <th>Cuenta</th>
                                                                <th>Tipo Operac.</th>
                                                                <th>Editar</th>
                                                                <th>Eliminar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Otras Parámetros</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="cpe_activar" id="cpe_activar" @if($cpe_activar == 1) checked @endif>Activar Facturación Electrónica
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Tipo Servidor</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="cpe_servidor" id="cpe_servidor" class="form-control select2">
                                                            <option value="1" @if($cpe_servidor == 1) selected @endif>OSE</option>
                                                            <option value="2" @if($cpe_servidor == 2) selected @endif>Producción</option>
                                                            <option value="3" @if($cpe_servidor == 3) selected @endif>Beta o Pruebas</option>
                                                            <option value="4" @if($cpe_servidor == 4) selected @endif>TCI</option>
                                                            <option value="9" @if($cpe_servidor == 9) selected @endif>Otros</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Servidor FTP</label>
                                                <div class="col-sm-8 col-xs-12">
                                                    <input type="text" id="cpe_ftpserv" name="cpe_ftpserv" value="{{ $cpe_ftpserv }}" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Repositorio</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="file" name="cpe_reposit" id="cpe_reposit" value="{{ $cpe_reposit }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Versión UBL</label>
                                                <div class="col-sm-8 col-xs-12">
                                                    <select name="" id="" class="form-control select2">
                                                        <option value="2.0" @if($cpe_ubl == '2.0') selected @endif>2.0</option>
                                                        <option value="2.1" @if($cpe_ubl == '2.1') selected @endif>2.1</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="checkbox" class="flat" name="cpe_plinea" id="cpe_plinea" @if($cpe_plinea == 1) checked @endif>Procesamiento en Línea
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="checkbox" class="flat" name="cpe_verpdf" id="cpe_verpdf" @if($cpe_verpdf == 1) cheched @endif>Mostrar PDF
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="checkbox" class="flat" name="cpe_vermsg" id="cpe_vermsg" @if($cpe_vermsg == 1) checked @endif>Ver Mensajes de Eventos
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="checkbox" class="flat" name="cpe_valruc" id="cpe_valruc" @if($cpe_valruc == 1) checked @endif>Validar RUC
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Envío Correo Electrónico</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Servidor</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_eservidor" id="cpe_eservidor" value="{{ $cpe_eservidor }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Puerto</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_epuerto" id="cpe_epuerto" value="{{ $cpe_epuerto }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Usuario</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_eusuario" id="cpe_eusuario" value="{{ $cpe_eusuario }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Clave</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="cpe_eclave" id="cpe_eclave" value="{{ $cpe_eclave }}" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                        <br><br>
                                            <div class="panel panel-info">
                                                <!--div class="panel-heading">Parametros para Depreciación</div-->
                                                <div class="panel-body">
                                                    <div class="form-group" id="scrollable-dropdown-menu">
                                                        <div class="col-md-6">
                                                            <label for="">Límite de Crédito para Socios</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="cxc_limcre1" id="cxc_limcre1" value="{{ $cxc_limcre1}}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="scrollable-dropdown-menu">
                                                        <div class="col-md-6">
                                                            <label for="">Límite de Crédito para Socios</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="cxc_interes" id="cxc_interes" value="{{ $cxc_interes }}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="scrollable-dropdown-menu">
                                                        <div class="col-md-12">
                                                            <label for="">Subdiario para Movimientos de Cuentas Corrientes</label>
                                                            <select name="" id="" class="form-control select2">
                                                                <option value="">-Seleccione-</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/system_configuration.js') }}"></script>
@endsection
