@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{route('store.typeSales')}}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="panel panel-default">
                    <div class="panel panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="code">Código:</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" name="code" id="code">
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Descripción:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" name="name" id="name">
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="codSunat">Cód.Sunat(Cat.17):</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" name="codSunat" id="codSunat">
                                </div>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="codSunat">Tipo Operación(Catálogo.51):</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" name="codsunat_to" id="codsunat_to">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <br>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="buy">Tipo de Transacción Asociada:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" name="buy" id="buy">
                                        <option value="">-Seleccione-</option>
                                        @foreach($buys as $buy)
                                            <option value="{{ $buy->id }}"> {{ $buy->codigo }} | {{ $buy->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="movement">Tipo Movimiento Asociado:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" name="movement" id="movement">
                                        <option value="">-Seleccione-</option>
                                        @foreach($movements as $movement)
                                            <option value="{{ $movement->id }}"> {{ $movement->codigo }} | {{ $movement->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="subdiario">Subdiario Asociado:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" name="subdiario" id="subdiario">
                                        <option value="">-Seleccione-</option>
                                        @foreach($subdiaries as $subdiarie)
                                            <option value="{{ $subdiarie->id }}"> {{ $subdiarie->codigo }} | {{ $subdiarie->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-md-3 col-xs-12">
                                    <div class="form-group">
                                        <div class="icheck-wetasphalt" style="float: right!important;">
                                            <input type="checkbox" id="deferred" name="deferred">
                                            <label for="deferred">Es Diferido</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="document">Tipo Documento Asociado:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" name="document" id="document"disabled>
                                        <option selected disabled>-Seleccione-</option>
                                        @foreach($documents as $document)
                                            <option value="{{ $document->id }}">
                                                {{ $document->codigo }} | {{ $document->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 label-input">
                                            {{--espacio--}}
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <div class="icheck-peterriver">
                                                <input type="checkbox" id="service" name="service">
                                                <label for="service">Es por servicios</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 label-input">
                                            {{--espacio--}}
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <div class="icheck-belizehole">
                                                <input type="checkbox" id="byExports" name="byExports">
                                                <label for="byExports">Es por exportaciones</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 label-input">
                                            {{--espacio--}}
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <div class="icheck-info">
                                                <input type="checkbox" id="unaffected" name="unaffected">
                                                <label for="unaffected">Operación inafecta</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 label-input">
                                            {{--espacio--}}
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <div class="icheck-info">
                                                <input type="checkbox" id="unaffectedivap" name="unaffectedivap">
                                                <label for="unaffectedivap">Operación afecta a IVAP</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 label-input">
                                            {{--espacio--}}
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <div class="icheck-primary">
                                                <input type="checkbox" id="free" name="free">
                                                <label for="free">Es Trasferencia Gratuita</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 label-input">
                                            {{--espacio--}}
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <div class="icheck-primary">
                                                <input type="checkbox" id="warehouse" name="warehouse">
                                                <label for="warehouse">Genera salida de almacén</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 label-input">
                                            {{--espacio--}}
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <div class="icheck-info">
                                                <input type="checkbox" id="retention" name="retention">
                                                <label for="retention">Es para el registro de los documentos de
                                                    retención de IGV</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <label for="">Precio de lista por defecto</label>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-info">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <input type="radio" id="precio" name="precio" value="1">Precio 1 <br>
                                                        <input type="radio" id="precio" name="precio" value="2">Precio 2 <br>
                                                        <input type="radio" id="precio" name="precio" value="3">Precio 3 <br>
                                                    </div>
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
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/typeSale.js') }}"></script>
@endsection
