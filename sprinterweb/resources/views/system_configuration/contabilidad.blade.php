@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Contabilidad<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_contabilidad" name="frm_conf_contabilidad" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="col-sm-4 col-xs-12 ">
                                        Plan de Cuentas usado:
                                    </div>
                                    <div class="col-sm-4 col-xs-12">
                                        <select class="form-control" id="cbo_etiqueta" name="cbo_etiqueta">
                                            <option value="">Seleccionar -</option>
                                            <option value="01" @if($etiqueta==01)selected @endif>PCG Empresarial</option>
                                            <option value="02" @if($etiqueta==02)selected @endif>PCG Revisado</option>
                                            <option value="03" @if($etiqueta==03)selected @endif>Para Sistema Financiero</option>
                                            <option value="04" @if($etiqueta==04)selected @endif>Para EPS</option>
                                            <option value="05" @if($etiqueta==05)selected @endif>Para Sistema Asegurado</option>
                                            <option value="06" @if($etiqueta==06)selected @endif>Para AFP</option>
                                            <option value="99" @if($etiqueta==99)selected @endif>Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="col-sm-4 col-xs-12 ">
                                        Catálogo de Estado Financiero
                                    </div>
                                    <div class="col-sm-4 col-xs-12">
                                        <select class="form-control" id="cbo_eeff_tipo" name="cbo_eeff_tipo">
                                            <option value="">Seleccionar -</option>
                                            <option value="01" @if($eeff_tipo=='01')selected @endif>Diversas</option>
                                            <option value="02" @if($eeff_tipo=='02')selected @endif>Seguros</option>
                                            <option value="03" @if($eeff_tipo=='03')selected @endif>Bancos y Financieras</option>
                                            <option value="04" @if($eeff_tipo=='04')selected @endif>AFP</option>
                                            <option value="05" @if($eeff_tipo=='05')selected @endif>Agentes de Intermediación</option>
                                            <option value="06" @if($eeff_tipo=='06')selected @endif>Fondos de Inversión</option>
                                            <option value="07" @if($eeff_tipo=='07')selected @endif>Patromonio en Fideicomiso</option>
                                            <option value="08" @if($eeff_tipo=='08')selected @endif>ICLV</option>
                                            <option value="09" @if($eeff_tipo=='09')selected @endif>Otros</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="col-sm-4 col-xs-12 ">
                                        El tipo de cambio se registra:
                                    </div>
                                    <div class="col-sm-4 col-xs-12">
                                        <select class="form-control select2" id="cbo_tcambio" name="cbo_tcambio">
                                            <option value="AUTOMATICO" @if($act_cambio=='AUTOMATICO')selected @endif>AUTOMATICO</option>
                                            <option value="MANUAL" @if($act_cambio=='MANUAL')selected @endif>MANUAL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Parámetros para Ajuste por Diferencia de Cambio</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Cuenta Ganancia</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuentaganancia" id="txt_cuentaganancia" class="form-control select2">
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $gan_ctadif) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Cuenta Perdida</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuentaperdida" id="txt_cuentaperdida" class="form-control select2">
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $per_ctadif) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Centro de Costo</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_centrocosto" id="txt_centrocosto" class="form-control select2">
                                                            @foreach($costo as $co)
                                                                <option value="{{ $co->id }}" @if($co->id == $dif_ccosto) selected @endif>{{ $co->codigo }} | {{ $co->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Parámetros para Cierre Contable</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Cuenta Ganancia</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuentaganancia_cierre" id="txt_cuentaganancia_cierre" class="form-control select2">
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $cierre_ctagan) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Cuenta Perdida</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuentaperdida_cierre" id="txt_cuentaperdida_cierre" class="form-control select2">
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $cierre_ctaper) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Parámetros para Ajuste por Redondeo</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Cuenta Ganancia</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuentaganancia_redondeo" id="txt_cuentaganancia_redondeo" class="form-control select2">
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $gan_ctaredon) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Cuenta Perdida</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuentaperdida_redondeo" id="txt_cuentaperdida_redondeo" class="form-control select2">
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $per_ctaredon) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Centro Costo</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_centrocosto_redondeo" id="txt_centrocosto_redondeo" class="form-control select2">
                                                            @foreach($costo as $co)
                                                                <option value="{{ $co->id }}" @if($co->id == $redon_ccosto) selected @endif>{{ $co->codigo }} | {{ $co->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="col-md-10 col-sm-10 col-xs-12">
                                                <input type="checkbox" class="flat" name="chk_rige_configuracion" id="chk_rige_configuracion" @if($rigeconfig==1) checked @endif> Rige la configuración de la cuenta Pide_OP
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 col-sm-6 col-xs-12">
                                            <br>
                                            <div class="col-sm-4 col-xs-12 ">
                                                Cuenta para costos por distribución:
                                            </div>
                                            <div class="col-sm-3 col-xs-12">
                                                <select class="form-control" id="cbo_ctagasxdis" name="cbo_ctagasxdis">
                                                    <option value="">Seleccionar -</option>
                                                    <option value="90" @if($etiqueta==90)selected @endif>90</option>
                                                    <option value="91" @if($etiqueta==91)selected @endif>91</option>
                                                    <option value="92" @if($etiqueta==92)selected @endif>92</option>
                                                    <option value="93" @if($etiqueta==93)selected @endif>93</option>
                                                    <option value="96" @if($etiqueta==96)selected @endif>96</option>
                                                    <option value="98" @if($etiqueta==98)selected @endif>98</option>
                                                    <option value="99" @if($etiqueta==99)selected @endif>99</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <input type="checkbox" class="flat" name="chk_gxd_monfun" id="chk_gxd_monfun" @if($gxd_monfun==1) checked @endif> Distibuir Gastos con moneda funcional
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
