@if($total==0)
    <div class="col-sm-12 col-xs-12">
        <input type="hidden" name="txt_instancia" id="txt_instancia" value="{{$instancia}}">
        <table id="" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Empresa</th>
                <th>Rol</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2">
                    <div class="">No existen registros en detalle</div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endif

@if($total>0)
    <div class="col-sm-12 col-xs-12">
        <input type="hidden" name="instancia" id="instancia" value="{{$instancia}}">
        <input type="hidden" name="tipo_proceso" id="tipo_proceso" value="{{$proceso}}">

        <table id="cart_table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Empresa</th>
                <th>Rol</th>
                <th>Estado</th>
                <th class="center"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($carts as $cart)
                <tr>
                    <td width="40%">{{$cart->options->has('company') ? $cart->options->company: ''}}</td>
                    <td width="40%">{{$cart->options->has('rol') ? $cart->options->rol: ''}}</td>
                    @if($cart->options->estado == 1)
                        <td><span class="label label-success">Activo</span></td>
                    @elseif($cart->options->estado == 0)
                        <td><span class="label label-danger">Anulado</span></td>
                    @endif
                    @if($cart->options->estado == 1)
                        <td width="10%" align="center">
                            <a data-toggle="tooltip"
                               data-placement="left"
                               title="Anular"
                               class="icon-button deletee"
                               onclick="eliminar_detalle('{{$cart->rowId}}','{{$instancia}}', '{{$var}}', 0)">
                                <i class="fa fa-remove"></i>
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
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
