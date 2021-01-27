@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">

            <input type="hidden" id="ruta" name="ruta" value="{{ route('update.accountingctnwarehouse', ['id' => $familia->id]) }}">
            <input type="hidden" name="proceso" id="proceso" @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
            <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">

                        <form id="frm_generales" action="" class="identificador ocultar">

                             <div class="row">
                                 <div class="form-group col-md-6">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero">Código: </label>
                                            <input type="text" class="form-control" name="txt_codigo" id="txt_codigo" placeholder="0000" value="{{ $familia->codigo }}" readonly>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="txt_numero">Descripción: </label>
                                            <input type="text" class="form-control" name="txt_desc" id="txt_desc" placeholder="0000" value="{{ $familia->descripcion }}" readonly>
                                        </div>
                                    </div>
                                     <div class="row">
                                         <p class="title-view">Configuración de Cuentas:</p>
                                     </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="txt_existencias">Existencias: </label>
                                            <select class="form-control select2" name="txt_existencias" id="txt_existencias">
                                                <option value="" disabled selected>-- Seleccione una existencia --</option>
                                                @foreach($existencias as $existencia)
                                                    <option value="{{$existencia->id}}"
                                                    @if($familia_cuenta) @if( $existencia->id == $familia_cuenta->cta20_id) selected @endif @endif>
                                                        {{ $existencia->codigo }} | {{ $existencia->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="txt_compras">Compras: </label>
                                            <select class="form-control select2" name="txt_compras" id="txt_compras">
                                                <option value="" disabled selected>-- Seleccione una existencia --</option>
                                                @foreach($compras as $compra)
                                                    <option value="{{$compra->id}}"
                                                    @if($familia_cuenta) @if( $compra->id == $familia_cuenta->cta60_id) selected @endif @endif>
                                                        {{ $compra->codigo }} | {{ $compra->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="txt_val_existencias">Variación de Existencias: </label>
                                            <select class="form-control select2" name="txt_val_existencias" id="txt_val_existencias">
                                                <option value="" disabled selected>-- Seleccione una existencia --</option>
                                                @foreach($variacionexistencias as $variacionexistencia)
                                                    <option value="{{$variacionexistencia->id}}"
                                                  @if($familia_cuenta)    @if( $variacionexistencia->id == $familia_cuenta->cta61_id) selected @endif @endif >
                                                        {{ $variacionexistencia->codigo }} | {{ $variacionexistencia->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="txt_trasnferencia">Transferencia: </label>
                                            <select class="form-control select2" name="txt_trasnferencia" id="txt_trasnferencia">
                                                <option value="" disabled selected>-- Seleccione una existencia --</option>
                                                @foreach($transferencias as $transferencia)
                                                    <option value="{{$transferencia->id}}"
                                                     @if($familia_cuenta) @if( $transferencia->id == $familia_cuenta->cta28_id) selected @endif @endif>
                                                        {{ $transferencia->codigo }} | {{ $transferencia->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="txt_consumo">Consumo: </label>
                                            <select class="form-control select2" name="txt_consumo" id="txt_consumo">
                                                <option value="" disabled selected>-- Seleccione un consumo --</option>
                                                @foreach($consumos as $consumo)
                                                    <option value="{{$consumo->id}}"
                                                     @if($familia_cuenta) @if( $consumo->id == $familia_cuenta->cta25_id) selected @endif @endif>
                                                        {{ $consumo->codigo }} | {{ $consumo->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-6">
                                      <table id="commercialCatalog" class="table display responsive nowrap table-striped table-hover table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>Centro Costo</th>
                                    <th>Descripción</th>
                                    <th>Cuenta</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($centroscosto as $centrocosto)
                                    <tr>
                                        <td><input type="hidden" value="{{ $centrocosto->id }}" name="tipotransaccion[]" id="tipotransaccion">{{ $centrocosto->codigo }}</td>
                                        <td> {{ $centrocosto->descripcion }}</td>
                                        <td>

                                          {{ $a = $centrocosto->ccostoxfamilia->filter(function ($value, $key) use($familia) {
                                                     return $value->familiapdto_id == $familia->id;
                                                }
                                              )


                                         }}
                                            <select class="form-control select2 cuentas" name="N[]" id="">
                                                <option value=""></option>
                                                <option value="{{ $a->pluck('id')->first() }}" selected>{{  $a->pluck('cuenta_id')->first() }} | {{  $a->pluck('cuenta_id')->first() }}</option>

                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
    <script src="{{ asset('anikama/ani/accountingctnwarehouse.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>

    <script>
        function prueba(){
            var form = $('#frm_generales').serialize();
            console.log(form);
        }
    </script>
@endsection
