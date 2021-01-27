@extends('templates.home')

@section('content')

    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" enctype="multipart/form-data">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.accounts', $commercial->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $commercial->id }}">
                <input type="hidden" id="estado" name="estado" value="{{ $commercial->estado }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="code">Código:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="code" name="code" class="form-control" value="{{ $commercial->codigo }} " required readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Descripción:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="name" name="name" class="form-control" value="{{ $commercial->descripcion }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <h2>Tipos de Transacción</h2>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <table id="commercialCatalog" class="table display responsive nowrap table-striped table-hover table-bordered" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th>Cuenta M.N.</th>
                                            <th>Cuenta M.E.</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!--Los valors que ya existen en el detalle-->
                                        <input type="hidden" value="{{count($CommercialCountable)}}" name="condiciones" id="condiciones">
                                        @foreach($CommercialCountable as $type => $key)
                                            <tr>
                                                <td><input type="hidden" value="{{ $key->tipotransaccion_id }}" name="tipotransaccion[]" id="tipotransaccion">{{ $key->codigo }}</td>
                                                <td> {{ $key->descripcion }}</td>
                                                <td>
                                                    <select class="form-control select2" name="N[]" id="N{{$type}}">
                                                        <option value=""></option>
                                                        <option value="{{$key->pcuentamn_id}}" selected>{{$key->cta_pmn}} | {{$key->cta_pmnd}}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="E[]" id="E{{$type}}">
                                                        <option value=""></option>
                                                        <option value="{{$key->pcuentame_id}}" selected>{{$key->cta_pme}} | {{ $key->cta_pmed }}</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!--Para los nuevos tipos de transaccion agregados-->
                                        <input type="hidden" value="{{count($TransactionType)}}" name="condiciones2" id="condiciones2">
                                        @foreach($TransactionType as $type => $key)
                                            <tr>
                                                <td><input type="hidden" value="{{ $key->id }}" name="tipotransaccion[]" id="tipotransaccion">{{ $key->codigo }}</td>
                                                <td> {{ $key->descripcion }}</td>
                                                <td>
                                                    <select class="form-control select2" name="N[]" id="N2{{$type}}">
                                                        <option value=""></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select2" name="E[]" id="E2{{$type}}">
                                                        <option value=""></option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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
    <script src="{{ asset('anikama/ani/accounts.js') }}"></script>
@endsection
