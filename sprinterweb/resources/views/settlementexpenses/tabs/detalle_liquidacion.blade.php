<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-12 label-input">
                        <label for="customer">Proveedor / RUC:</label>
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <select class="form-control select2"
                                name="txt_proveedor"
                                id="txt_proveedor" onchange="ruc_proveedor_tab(this.value)">
                            <option selected disabled>-Seleccione-</option>
                            @foreach($terceros as $tercero)
                                <option value="{{$tercero->id}}">
                                    {{$tercero->codigo}} | {{$tercero->descripcion}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <input class="form-control"
                               name="txt_ruc"
                               id="txt_ruc"
                               readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-12 label-input">
                        <label for="number">Documento:</label>
                    </div>
                    <div class="col-md-2 col-xs-12">
                        <select class="form-control select2"
                                name="documenttype"
                                id="documenttype">
                            <option selected disabled>-Seleccione-</option>
                            @foreach($documentoscompra as $documentocompra)
                                <option value="{{$documentocompra->id}}">
                                    {{$documentocompra->codigo}} | {{$documentocompra->descripcion}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-xs-12">
                        <input class="form-control" type="text" name="series" id="series" required="">
                    </div>
                    <div class="col-md-2 col-xs-12">
                        <input class="form-control" type="text" name="numberseries" id="numberseries" value="00000000" required="">
                    </div>
                    <div class="col-md-1 col-xs-12 label-input">
                        <label for="date">Del:</label>
                    </div>
                    <div class="col-md-2 col-xs-12">
                        <input class="form-control"
                               type="date"
                               name="date"
                               id="date"
                               min="{{$period->inicio}}"
                               max="{{$period->final}}"
                               onchange="expirationdate()"
                               required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-xs-12 label-input">
                        <label for="">TC:</label>
                    </div>
                    <div class="col-md-2 col-xs-12">
                        <input class="form-control"
                               type="text"
                               name="changerate"
                               id="changerate"
                               required readonly>
                    </div>
                    <div class="col-md-2 col-xs-12 label-input">
                        <label for="">Cuenta:</label>
                    </div>
{{--                    <div class="col-sm-12 col-xs-12">--}}
{{--                        <select class="form-control"--}}
{{--                                name="account"--}}
{{--                                id="account">--}}
{{--                            <option selected disabled value="0">-Seleccione-</option>--}}
{{--                            @foreach($cuentas as $cuenta)--}}
{{--                                <option value="{{$cuenta->id}}">{{$cuenta->codigo}}--}}
{{--                                    | {{$cuenta->descripcion}}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}

                </div>
                <br>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-md-1 col-xs-12 label-input">
                        <label for="comment">Glosa:</label>
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <input class="form-control"
                               type="text"
                               name="comment"
                               id="comment">
                    </div>
                </div>

            </div>
        </div>


                <div class="col-md-1 col-xs-12 label-input">
                    <label for="">Moneda:</label>
                </div>
                <div class="col-md-2 col-xs-12">
                    <select class="form-control select2" name="currency" id="currency">
                        @foreach($monedas as $moneda)
                            <option value="{{$moneda->id}}">
                                {{$moneda->descripcion}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1 col-xs-12 label-input">
                    <label for="ruc">Total:</label>
                </div>
                <div class="col-md-2 col-xs-12">
                    <input class="form-control" type="text" name="total" id="total" value="0.00"
                           readonly>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <p class="title-view">Detalle del Documento:</p>
</div>
<div class="row" >
    <table id="detalle_documento" class="table table-bordered" width="100%">
        <thead>
            <th scope="col"></th>
            <th scope="col">Item</th>
            <th scope="col">Código</th>
            <th scope="col">Descripción</th>
            <th scope="col">U.M.</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Costo Unit</th>
            <th scope="col">% Dscto</th>
            <th scope="col">Importe</th>
            <th scope="col">Volumen</th>
            <th scope="col">Glosa</th>
            <th scope="col">Editar</th>
            <th scope="col">Eliminar</th>
        </thead>
        <tbody>
        </tbody>
    </table>

</div>

