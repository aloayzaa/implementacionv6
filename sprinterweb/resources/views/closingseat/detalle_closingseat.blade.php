@if($total==0)
    <div class="col-sm-12 col-xs-12 {{--contenido_scrollauto--}}">
        <input type="hidden" name="txt_instancia" id="txt_instancia" value="{{$instancia}}">
        <table id="{{--no-more-tables--}}" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Item</th>
                <th>Cuenta</th>
                <th>Glosa</th>
                <th>Cargo M.N.</th>
                <th>Abono M.N.</th>
                <th>Cargo M.E.</th>
                <th>Abono M.E.</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="7">
                    <div class="text-center">No existen registros en detalle</div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

@if($total>0)
    <div class="col-sm-12 col-xs-12 {{--contenido_scrollauto--}}">
        <input type="hidden" name="txt_instancia" id="txt_instancia" value="{{$instancia}}">
        <input type="hidden" name="tipo_proceso" id="tipo_proceso" value="{{$proceso}}">

        <table id="cart_table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Item</th>
                <th>Cuenta</th>
                <th>Glosa</th>
                <th>Cargo M.N.</th>
                <th>Abono M.N.</th>
                <th>Cargo M.E.</th>
                <th>Abono M.E.</th>
                <th>Estado</th>
                @if ($proceso !='consulta')
                    <th class="center">Editar</th>
                    <th class="center">Anular</th>
                @endif
            </tr>
            </thead>
            <tbody>
            <?php $i = 1;?>
            @foreach($carts as $cart)
                <input type="hidden" name="txt_id_detalle" id="txt_id_detalle"
                       value="{{ $cart->options->has('item') ? $cart->options->item: ''}}">
                <input type="hidden" name="txt_id_parent" id="txt_id_parent"
                       value="{{ $cart->options->has('id_parent') ? $cart->options->id_parent: ''}}">
                <tr class="">
                    <td data-title="Item">{{$i}}</td>

                    <td data-title="Cuenta">{{ $cart->options->has('cuenta_codigo') ? $cart->options->cuenta_codigo: '' }}</td>
                    <td data-title="C. Costo">{{ $cart->options->has('costo_codigo') ? $cart->options->costo_codigo: '' }}</td>
                    <td data-title="Cargo M.N">{{ $cart->options->has('cargomn') ? $cart->options->cargomn: '' }}</td>
                    <td data-title="Abono M.N">{{ $cart->options->has('abonomn') ? $cart->options->abonomn: '' }}</td>
                    <td data-title="Cargo M.E">{{ $cart->options->has('cargome') ? $cart->options->cargome: '' }}</td>
                    <td data-title="Abono M.E">{{ $cart->options->has('abonome') ? $cart->options->abonome: '' }}</td>
                    <td data-title="Glosa">{{ $cart->options->has('glosa') ? $cart->options->glosa: '' }}</td>
                    @if($cart->options->estado == 1)
                        <td><span class="label label-success">Activo</span></td>
                    @elseif($cart->options->estado == 0)
                        <td><span class="label label-danger">Anulado</span></td>
                    @endif
                    @if ($proceso !='consulta')
                        <td width="10%">
                            <a data-toggle="tooltip"
                               data-placement="left"
                               title="Actualizar"
                               class="icon-button updatee"
                               onclick="ver_detalle('{{$cart->rowId}}','{{ $cart->options->has('item') ? $cart->options->item: ''}}',
                                   '{{ $cart->options->has('parent_id') ? $cart->options->parent_id: ''}}')">
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
                                   onclick="eliminar_detalle('{{ $cart->rowId }}','{{$instancia}}','{{ $var }}', 0)">
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
                                   onclick="eliminar_detalle('{{$cart->rowId}}','{{$instancia}}','{{$var}}', 1)">
                                    <i class="fa fa-check"></i>
                                    <span></span>
                                </a>
                            </td>
                        @endif
                    @endif
                </tr>
                <?php $i++;?>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
