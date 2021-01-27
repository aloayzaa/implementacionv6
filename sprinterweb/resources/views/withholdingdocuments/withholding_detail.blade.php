@if($total==0)
    <div class="col-sm-12 col-xs-12 {{--contenido_scrollauto--}}">
        <input type="hidden" name="txt_instancia" id="txt_instancia" value="{{$instancia}}">
        <table id="{{--no-more-tables--}}" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Item</th>
                <th>Referencia</th>
                <th>Cuenta</th>
                <th>Glosa</th>
                <th>Importe</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="5">
                    <div align="center">No existen registros en detalle</div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

@if($total>0)
    <div class="col-sm-12 col-xs-12 {{--contenido_scrollauto--}}">
        <input type="hidden" name="instancia" id="instancia" value="{{$instancia}}">
        <input type="hidden" name="proceso" id="proceso" value="{{$proceso}}">

        <table id="cart_table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Item</th>
                <th>Referencia</th>
                <th>Cuenta</th>
                <th>Glosa</th>
                <th>Importe</th>
                @if ($proceso !='consulta')
                    <th class="center">Editar</th>
                    <th class="center">Anular</th>
                @endif
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;$total = 0.00;$importe = 0.00; ?>
            @foreach($carts as $cart)
                <tr class="">
                    <td data-title="Item">{{$i}}</td>
                    <td data-title="Referencia">{{ $cart->options->has('documento') ? $cart->options->documento: '' }}</td>
                    <td data-title="Cuenta">{{ $cart->options->cuenta_id }}</td>
                    <td data-title="Glosa">{{ $cart->options->has('glosa') ? $cart->options->glosa: '' }}</td>
                    <td data-title="Importe">{{ $cart->options->has('aplicar') ? $cart->options->aplicar: '' }}</td>
                    @if ($proceso !='consulta')
                        <td width="10%">
                            <a data-toggle="tooltip"
                               data-placement="left"
                               title="Actualizar"
                               class="icon-button updatee"
                               onclick="editar_modelo('{{ $cart->rowId }}','{{ $instancia }}','{{ $var }}')">
                                <i class="fa fa-refresh"></i>
                                <span></span>
                            </a>
                        </td>
                        @if($cart->options->estado == 1)
                            <td width="10%">
                                <a data-toggle="tooltip"
                                   data-placement="left"
                                   title="Anular"
                                   class="icon-button deletee"
                                   onclick="eliminar_detalle('{{ $cart->rowId }}','{{ $instancia }}', '{{ $var }}', 0)">
                                    <i class="fa fa-trash"></i>
                                    <span></span>
                                </a>
                            </td>
                        @elseif($cart->options->estado == 0)
                            <td align="center" data-title="Activar">
                                <a data-toggle="tooltip"
                                   data-placement="left"
                                   title="Activar"
                                   class="icon-button deletee"
                                   onclick="eliminar_detalle('{{$cart->rowId}}','{{$instancia}}', '{{$var}}', 1)">
                                    <i class="fa fa-check"></i>
                                    <span></span>
                                </a>
                            </td>
                        @endif
                    @endif
                </tr>
                <?php $i++; ?>
            @endforeach
            </tbody>
        </table>
    </div>

    {{--<div class="modal periodo" id="myModal_modelo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        eliminar_detalle
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><i class="flaticon-edit-2"></i> Editar Modelos</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <input type="hidden" name="txt_id_modelo_modal" id="txt_id_modelo_modal">
                        <input type="hidden" name="txt_id_detalle_modelo_modal" id="txt_id_detalle_modelo_modal"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-3 col-xs-12">
                            <label class="margen_top_label">Nombre Comercial:</label>
                        </div>
                        <div class="col-sm-7 col-xs-12">
                            <input type="text" name="name_trade_modal" id="name_trade_modal"
                                   class="form-control">
                            <input type="hidden" name="estado_modal" id="estado_modal"
                                   class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <center>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <span
                                class="fa fa-ban"></span> Cancelar
                        </button>
                        <button type="button" class="btn btn-primary"
                                onclick="guardar_cambios_modal_modelo('trademarks', '{{$instancia}}')"><span
                                class="fa fa-save"></span> Guardar
                        </button>
                    </center>
                </div>
            </div>
        </div>
    </div>--}}

@endif
