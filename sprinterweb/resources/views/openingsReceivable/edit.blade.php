@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <ul class="nav nav-tabs bar_tabs">
                <li class="active">
                    <a href="#tab_content1" id="home-tab" data-toggle="tab">
                        General
                    </a>
                </li>
                @if($proceso == 'ver')
                    <li role="presentation" class="">
                        <a href="#tab_content2" id="profile-tab" data-toggle="tab">
                            Aplicaciones
                        </a>
                    </li>
                @endif
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane fade active in" id="tab_content1">
                    <form class="form-horizontal" id="frm_generales" name="frm_generales"
                          data-route="{{ route('openingReceivable') }}">

                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="var" name="var" value="{{ $var }}">
                        <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                        {{--<input type="hidden" id="tipo_tercero" name="tipo_tercero">--}}
                        <input type="hidden" id="id" name="id" value="{{ $opening->id }}">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="period">Periodo:</label>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="period"
                                                   id="period"
                                                   value="{{ $opening->periodo_id }}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="number">Número:</label>
                                        </div>
                                        <div class="col-md-1 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="number"
                                                   id="number"
                                                   value={{ $opening->numero }}
                                                       readonly>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="date">Fecha:</label>
                                        </div>
                                        <div class="col-md-2 col-xs-12">

                                            <input class="form-control"
                                                   type="date"
                                                   name="date"
                                                   id="date"
                                                   value="{{ $opening->fechaproceso }}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="sale">T. Compra:</label>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <select class="form-control select2"
                                                    name="sale"
                                                    id="sale">
                                                <option selected disabled>-Seleccione-</option>
                                                @foreach($sales as $sale)
                                                    <option value="{{ $sale->id }}"
                                                            @if( $sale->id == $opening->tipocompra_id) selected @endif>
                                                        {{ $sale->codigo }} | {{ $sale->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br class="hidden-xs">
                                    <div class="row">
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="customer">Proveedor:</label>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <select class="form-control select2"
                                                    name="customer"
                                                    id="customer"
                                                    data-route="{{ route('validate.openingReceivable') }}">
                                                <option selected disabled>-Seleccione-</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                            @if( $customer->id == $opening->tercero_id) selected @endif>
                                                        {{ $customer->codigo }} | {{ $customer->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="document">Documento:</label>
                                        </div>
                                        <div class="col-md-4 col-xs-12">
                                            <select class="form-control "
                                                    name="document"
                                                    id="document"
                                                    readonly>
                                                {{--                                    <option selected disabled>-Seleccione-</option>--}}
                                                @foreach($documents as $document)
                                                    {{--                                        <option value="{{ $document->id }}"--}}
                                                    {{--                                                @if($document->id == $opening->documento_id) selected @endif>--}}
                                                    {{--                                            {{ $document->codigo }} | {{ $document->descripcion }}--}}
                                                    {{--                                        </option>--}}
                                                    @if($document->id == $opening->documento_id)
                                                        <option value="{{ $document->id }}" selected>
                                                            {{ $document->codigo }} | {{ $document->descripcion }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="ruc">RUC:</label>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="ruc"
                                                   id="ruc"
                                                   value="{{ $opening->ruc }}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            {{--esapcio--}}
                                        </div>
                                        <div class="col-md-1 col-xs-12">
                                            <label for="series">Serie:</label>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="series"
                                                   id="series"
                                                   value="{{ $opening->seriedoc }}"
                                                   required readonly>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="numberOfSeries">Número:</label>
                                        </div>
                                        <div class="col-md-1 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="numberOfSeries"
                                                   id="numberOfSeries"
                                                   value="{{ $opening->numerodoc }}"
                                                   required readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="address">Dirección:</label>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="address"
                                                   id="address"
                                                   value="{{ $opening->direccion }}"
                                                   readonly>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="dateInitial">Fecha:</label>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <input class="form-control"
                                                   type="date"
                                                   name="dateInitial"
                                                   id="dateInitial"
                                                   value="{{ $opening->recepcion }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="glosa">Glosa:</label>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="glosa"
                                                   id="glosa"
                                                   value="{{ $opening->glosa }}"
                                                   required>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="dateEnd">Vencimiento:</label>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <input class="form-control"
                                                   type="date"
                                                   name="dateEnd"
                                                   id="dateEnd"
                                                   value="{{ $opening->vencimiento }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="currency">Moneda:</label>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <select class="form-control select2"
                                                    type="text"
                                                    name="currency"
                                                    id="currency"
                                                    required>
                                                <option selected disabled>-Seleccione-</option>
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->id }}"
                                                            @if($currency->id == $opening->moneda_id) selected @endif>
                                                        {{ $currency->codigo }} | {{ $currency->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="changerate">T.C:</label>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="changerate"
                                                   id="changerate"
                                                   value="{{ $opening->tcambio }}"
                                                   readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="total">Total:</label>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <input class="form-control"
                                                   type="text"
                                                   name="total"
                                                   id="total"
                                                   value="{{ $opening->total }}"
                                                   data-route="{{ route('calculateIgv.openingReceivable') }}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="ln_solid"></div>
                                        <div class="col-xs-12 form-group text-center">
                                            <a id="btn_editar" class="btnCode fifth">
                                                Actualizar <i class="fa fa-check"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(isset($movCte))
                    <div class="tab-pane fade" id="tab_content2">
                        <table id="openingToPayCatalog"
                               class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                               cellspacing="0" width="100%" role="grid"
                               aria-describedby="datatable-responsive_info" style="width: 100%;">
                            <thead>
                            <tr role="row">
                                <th>Fecha</th>
                                <th>Referencia</th>
                                <th>Glosa</th>
                                <th>Importe M.N.</th>
                                <th>Total M.E.</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $movCte->fechaproceso }}</td>
                                <td>{{ $opening->numero . ' ' . $opening->seriedoc . '-' . $opening->numerodoc }}</td>
                                <td>{{ $movCte->glosa }}</td>
                                <td>{{ $movCte->saldomn }}</td>
                                <td>{{ $movCte->saldome }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="form-group pull-right">
                            <label class="control-label col-md-3" for="saldoMN">
                                Saldo documento
                            </label>
                            <div class="col-md-4 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="saldoMN"
                                       id="saldoMN"
                                       value="{{$movCte->saldomn }}"
                                       required readonly>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <input class="form-control"
                                       type="text"
                                       name="saldoME"
                                       id="saldoME"
                                       value="{{ $movCte->saldome }}"
                                       required>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/buy.js') }}"></script>
@endsection