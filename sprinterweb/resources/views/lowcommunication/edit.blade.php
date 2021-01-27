@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel ocultar identificador">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.lowcommunication', $baja->id) }}">
                <input type="hidden" id="ruta_estado" name="ruta_estado" value="{{ route('estado.lowcommunication') }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" id="id" value='{{ $baja->id }}'>
                <input type="hidden" id="estado" name="estado" value="{{ $baja->estado }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="panel panel-info process-cpe">
                            <div class="panel-heading">Registro</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="period">Periodo:</label>
                                        <input type="hidden" name="period" id="period" value="{{$period->id}}" readonly>
                                        <input class="form-control" type="text" name="" id="" value="{{$period->descripcion}}" readonly>
                                    </div>
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="numberOfSeries">Número:</label>
                                        <input class="form-control" type="text" name="numberseries" id="numberseries"
                                            value="{{ $numero }}" readonly>
                                    </div>
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="buy">Fecha:</label>
                                        <input type="date" name="txt_fecha" id="txt_fecha" class="form-control tipocambio" value="{{ $fecha }}">
                                    </div>
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="buy">T.Cambio:</label>
                                        <input type="text" name="tcambio" id="tcambio" value='{{ $baja->tcambio }}' class="form-control typechange ocultar" readonly>
                                    </div>
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="buy">Moneda:</label>
                                        <select class="form-control ocultar" name="txt_descripcionmon" id="txt_descripcionmon"  readonly>
                                            <option value="{{ $monedas->id }}" data-tipo="{{ $monedas->tipo }}" @if($baja->moneda_id == $monedas->id) selected @endif>{{ $monedas->descripcion }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="buy">Motivo de Baja:</label>
                                        <input type="text" class="form-control" name="txtglosa" id="txtglosa" value="{{ $baja->glosa }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 process-cpe">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="detallepva active">
                                    <a href="#tab_content1" id="detalle" role="tab" class="detallepv" data-toggle="tab" aria-expanded="true">Detalle del Documento</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in detallepva" id="tab_content1"
                                     aria-labelledby="detalle">
                                    @include('lowcommunication.tabs.detalle')
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="">Fecha Envío</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="date" id="txtdate2" name="txtdate2" class="form-control" value='{{ $fecha_envio }}' readonly> 
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="">Fecha Recepc.</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="date" id="txtdate3" name="txtdate3" class="form-control" readonly value='{{ $fecha_recep }}'>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label for="">Nro. Ticket</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" id="txtstr1" name="txtstr1" value="{{ $ticket }}" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="" style="float: right">Código</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="txtstr3" value="{{ $cod_cpe }}" name="txtstr3" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="button" class="btn btn-success" id='procesar_baja'>Procesar Baja</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="" style="float: right">Descripción</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="txtstr4" value="{{ $dsc_cpe }}" name="txtstr4" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="" style="float: right">Hash</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="txtstr5" value="{{ $hash_cpe }}" name="txtstr5" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @include('lowcommunication.modals.add_item')
            @include('lowcommunication.modals.add_referencia')
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/lowcommunication.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            tableLowCommunicationDetails.init('{{ route('listDeatilLowCommunication.lowcommunication') }}');
            tableLowCommunicationReferences.init('{{ route('references.lowcommunication') }}');
        });
    </script>
@endsection
