<div class="modal fade" id="myModalDetalleProductosProvision" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-ok"></span> Detalle Provisión
                    por pagar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="parent_id" id="parent_id">
                <input type="hidden" name="id_cart" id="id_cart">
                <input type="hidden" name="item" id="item">
                <input type="hidden" name="tipo_modal" id="tipo_modal">
                <input type="hidden" name="estado_modal" id="estado_modal"
                       class="form-control">

                <div class="form-group">
                    <div class="col-sm-9 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Código/Descripción</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control {{--select2--}}"
                                    name="products"
                                    id="products">
                                <option selected disabled value="0">-Seleccione-</option>
                                @foreach($productos as $producto)
                                    <option value="{{$producto->id}}">{{$producto->codigo}}
                                        | {{$producto->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">U.M.</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control {{--select2--}}"
                                    name="measurement"
                                    id="measurement">
                                <option selected disabled value="0">-Seleccione-</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Cuenta</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control {{--select2--}}"
                                    name="account"
                                    id="account">
                                <option selected disabled value="0">-Seleccione-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <label class="col-sm-12 col-xs-12">Centro Costo</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12" id="scrollable-dropdown-menu">
                            <select class="form-control {{--select2--}}"
                                    name="costcenterdt"
                                    id="costcenterdt">
                                <option selected disabled value="0">-Seleccione-</option>
                                @foreach($centroscosto as $centrocosto)
                                    <option value="{{$centrocosto->id}}">
                                        {{$centrocosto->codigo}} | {{$centrocosto->descripcion}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3 col-xs-12">
                        <label class="col-sm-12 col-xs-12">Cantidad</label>
                        <div class="col-sm-12 col-xs-12">
                            <input type="text" name="quantity" id="quantity"
                                   class="form-control" autocomplete="off"
                                   placeholder="0.000"
                                   onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <label class="col-sm-12 col-xs-12">Precio Unitario</label>
                        <div class="col-sm-12 col-xs-12">
                            <input type=text" name="unitprice" id="unitprice"
                                   class="form-control" autocomplete="off"
                                   placeholder="0.000000"
                                   onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <label class="col-sm-12 col-xs-12">Importe</label>
                        <div class="col-sm-12 col-xs-12">
                            <input type=text" name="dttotal" id="dttotal"
                                   class="form-control" autocomplete="off"
                                   placeholder="0.00"
                                   onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                                   readonly="readonly">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_agregar_cart" id="btn_agregar_cart"
                        onclick="agregar_detalle_provision()"><span
                        class="fa fa-save"></span> Guardar Detalle
                </button>
            </div>

        </div>
    </div>
</div>
