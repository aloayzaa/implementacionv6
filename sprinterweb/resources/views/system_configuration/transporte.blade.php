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
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_compras" name="frm_conf_compras" method="post">
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
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Razón Social</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Dirección</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Ubigeo</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="" id="" class="form-control select2">
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Ciudad</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Usuario SOL/OSE</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Clave SOL/OSE</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
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
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Razón Social</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Certificado (PFX)</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="file" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Clave Certificado</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
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
                                                    <input type="checkbox" class="flat" name="" id="">Activar Facturación Electrónica
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Tipo Servidor</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="" id="" class="form-control select2">
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Servidor FTP</label>
                                                <div class="col-sm-8 col-xs-12">
                                                    <input type="text" id="" name="" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Repositorio</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="file" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Versión UBL</label>
                                                <div class="col-sm-8 col-xs-12">
                                                    <input type="text" id="" name="" class="form-control" value="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="checkbox" class="flat" name="" id="">Procesamiento en Línea
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="checkbox" class="flat" name="" id="">Mostrar PDF
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="checkbox" class="flat" name="" id="">Ver Mensajes de Eventos
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <input type="checkbox" class="flat" name="" id="">Validar RUC
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
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Puerto</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Usuario</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="col-md-4 col-sm-4 col-xs-12">Clave</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <input type="text" name="" id="" class="form-control">
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
                                                            <input type="text" name="" id="" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="scrollable-dropdown-menu">
                                                        <div class="col-md-6">
                                                            <label for="">Límite de Crédito para Socios</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="" id="" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group" id="scrollable-dropdown-menu">
                                                        <div class="col-md-12">
                                                            <label for="">Subdiario para Movimientos de Cuentas Corrientes</label>
                                                            <select name="" id="" class="form-control">
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
