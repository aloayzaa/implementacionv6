@extends('templates.home')
@section('content')
    <div class="row-body">
        @include('system_configuration.menu.menu')
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> Maestros<small></small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask" id="frm_conf_maestros" name="frm_conf_maestros" method="post">
                        <input type="hidden" id="var" name="var" value="{{ $var }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-sm-6 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Generales</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-sm-12 col-xs-12">
                                            <label class="col-sm-7 col-xs-12 margen_top_label">Nomenclatura para las familias de productos:
                                            </label>
                                            <div class="col-sm-4 col-xs-12">
                                                <input type="text" id="txt_nom_familia" name="txt_nom_familia" class="form-control" value="{{ $nomFamilia }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 col-xs-12">
                                            <label class="col-sm-7 col-xs-12 margen_top_label">Nomenclatura para los centros de costos:
                                            </label>
                                            <div class="col-sm-4 col-xs-12">
                                                <input type="text" id="txt_nom_ccosto" name="txt_nom_ccosto" class="form-control" value="{{  $nomCcosto }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 col-xs-12">
                                            <label class="col-sm-7 col-xs-12 margen_top_label">Nomenclatura para el plan de cuentas:
                                            </label>
                                            <div class="col-sm-4 col-xs-12">
                                                <input type="text" id="txt_nom_pcuentas" name="txt_nom_pcuentas" class="form-control" value="{{  $nomPcuentas }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 col-xs-12">
                                            <label class="col-sm-12 col-xs-12 margen_top_label">
                                                <input type="checkbox" class="flat" id="chk_bd_comun" name="chk_bd_comun"  @if($chckPcComun=='1') checked @endif > La gestión de pacientes está en la BD común
                                            </label>
                                        </div>
                                        <div class="col-sm-12 col-xs-12">
                                            <label class="col-sm-12 col-xs-12 margen_top_label">
                                                <input type="checkbox" class="flat" id="chk_ind_empresa" name="chk_ind_empresa"  @if($chkGestionIndependiente=='1') checked @endif> La gestión de productos es independiente por empresa
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-info">
                                <div class="panel-heading">Productos</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-sm-12 col-xs-12">
                                            <label class="col-sm-12 col-xs-12 margen_top_label">Parámetros para codificación de productos:
                                                <br><br>
                                            </label>

                                            <div class="col-sm-offset-1 col-sm-11 col-xs-12">
                                                <div class="col-sm-4 col-xs-12">
                                                    El código se genera
                                                </div>
                                                <div class="col-sm-4 col-xs-12">
                                                    <select class="form-control" id="cboCodigoGenera" name="cboCodigoGenera">
                                                        <option @if($cboComboSegenera=='AUTOMATICO') selected @endif value="AUTOMATICO">AUTOMATICO</option>
                                                        <option @if($cboComboSegenera=='INCREMENTAL') selected @endif value="INCREMENTAL">INCREMENTAL</option>
                                                        <option @if($cboComboSegenera=='MANUAL') selected @endif value="MANUAL">MANUAL</option>
                                                    </select>
                                                    <br>
                                                </div>

                                            </div>

                                            <div class="col-sm-offset-1 col-sm-11 col-xs-12">
                                                <div class="col-sm-4 col-xs-12 ">
                                                    Longitud del código
                                                </div>
                                                <div class="col-sm-4 col-xs-12">
                                                    <input type="text" id="txt_long_codigo" name="txt_long_codigo" class="form-control" value="{{ $longCodigo }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="col-sm-11 col-xs-12">
                                            <div class="col-sm-5 col-xs-12">
                                                <div class="row">
                                                    <div class="row">
                                                        <label class="col-sm-12 col-xs-12 margen_top_label">Interfaces para fichas
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <select class="form-control" id="cboInterfacesFichas" name="cboInterfacesFichas" >
                                                    <option @if($InterFicha=='GENERAL') selected @endif value="GENERAL">GENERAL</option>
                                                    <option @if($InterFicha=='RESTAURANT') selected @endif value="RESTAURANT">RESTAURANT</option>
                                                    <option @if($InterFicha=='DOS') selected @endif value="DOS">DOS</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Impresión de tickets</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Impresora</label>
                                        <div class="col-sm-3 col-xs-12">
                                            <select name="txt_impresora_impr" id="txt_impresora_impr" class="form-control">
                                                <option value="M" @if ($impresora == 'M') selected @endif>MATRICIAL</option>
                                                <option value="T" @if ($impresora == 'T') selected @endif>TERMICA</option>
                                                <option value="F" @if ($impresora == 'F') selected @endif>FORMATO VFP</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Fuente</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" id="txt_fuente_impr" name="txt_fuente_impr" class="form-control" value="{{ $fuente }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-3 margen_top_label">Tamaño</label>
                                        <div class="col-sm-3 col-xs-3">
                                            <input type="text" id="txt_tamanio_impr" name="txt_tamanio_impr" class="form-control" value="{{ $tamanio }}">
                                        </div>
                                        <label class="col-sm-3 col-xs-3 margen_top_label">Columna</label>
                                        <div class="col-sm-3 col-xs-3">
                                            <input type="text" id="txt_columna_impr" name="txt_columna_impr" class="form-control" value="{{ $columna }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Título 1</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" id="txt_titulo1_impr" name="txt_titulo1_impr" class="form-control" value="{{ $linea1 }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Título 2</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" id="txt_titulo2_impr" name="txt_titulo2_impr" class="form-control" value="{{ $linea2 }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Título 3</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" id="txt_titulo3_impr" name="txt_titulo3_impr" class="form-control" value="{{ $linea3 }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Título 4</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" id="txt_titulo4_impr" name="txt_titulo4_impr" class="form-control" value="{{ $linea4 }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Título 5</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" id="txt_titulo5_impr" name="txt_titulo5_impr" class="form-control" value="{{ $linea5 }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Pie 1</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" id="txt_pie1" name="txt_pie1" class="form-control" value="{{ $linea6 }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 col-xs-12 margen_top_label">Pie 2</label>
                                        <div class="col-sm-9 col-xs-12">
                                            <input type="text" id="txt_pie2" name="txt_pie2" class="form-control" value="{{ $linea7 }}">
                                        </div>
                                    </div><br>

                                    <center><div class="form-group">
                                            <input type="checkbox" class="flat" id="chk_prim_pro" name="chk_prim_pro" @if($codProducto=='1') checked @endif> Imprimir código del producto &nbsp;
                                            <input type="checkbox" class="flat" id="chk_imp_vend" name="chk_imp_vend" @if($impVendedor=='1') checked @endif> Imprimir vendedor
                                        </div></center>

                                </div>
                            </div>
                        </div>
                    </form>
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
