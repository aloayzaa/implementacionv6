@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.accountsTypesSales', $sale->id) }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="estado" name="estado" value="{{ $sale->estado }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="id" name="id" value="{{ $sale->id }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="code">Código</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="code" name="code" class="form-control" value="{{ $sale->codigo }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Descripción:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="name" name="name" class="form-control" value="{{ $sale->descripcion }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="x_title">
                                        <h2>Familias de Productos</h2>
                                        <button type="button" class="btn btn-default btn-xs" style="float: right!important;"><i class="flaticon-menu-settings"></i> Copiar Cuenta</button>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="x_content">
                                    <div class="col-xs-12">
                                        <table id="commercialCatalog" class="table display responsive nowrap table-striped table-hover table-bordered" width="100%">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                    <th>Cuenta Venta</th>
                                                    <th>Cuenta Costo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!--Los valors que ya existen en el detalle-->
                                                <input type="hidden" value="{{count($FamilySaleType)}}" name="condiciones" id="condiciones">
                                                @foreach($FamilySaleType as $key => $value)
                                                    <tr>
                                                        <td><input type="hidden" value="{{ $value->familiapdto_id }}" name="familiapdto_id[]" id="familiapdto_id">{{ $value->codigo }}</td>
                                                        <td>{{$value->descripcion}}</td>
                                                        <td>
                                                            <select class="form-control select2" name="N[]" id="N{{$key}}">
                                                                <option value=""></option>
                                                                <option value="{{$value->cuenta_id}}" selected>{{$value->cta_cod}} | {{$value->cta_dsc}}</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control select2" name="E[]" id="E{{$key}}">
                                                                <option value=""></option>
                                                                <option value="{{$value->ctacos_id}}" selected>{{$value->cos_cod}} | {{ $value->cos_dsc }}</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <!--Para los nuevos tipos de transaccion agregados-->
                                                <input type="hidden" value="{{count($familia)}}" name="condiciones2" id="condiciones2">
                                                @foreach($familia as $type => $key)
                                                    <tr>
                                                        <td><input type="hidden" value="{{ $key->id }}" name="familiapdto_id[]" id="familiapdto_id">{{ $key->codigo }}</td>
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
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/accountsSales.js') }}"></script>
@endsection
