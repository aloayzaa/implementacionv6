<div class="modal fade" id="myModalPedidos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Pedidos para Compra</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-pedidos" name="form-pedidos">
                    <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                    
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <label for="txt_hasta">hasta:</label>
                            <input type="date" id="txt_hasta" name="txt_hasta" class="form-control" min="" max="">
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            <br>
                            <button type="button" class="btn-primary form-control text-center"
                                    id="btn_filtrar" name="btn_filtrar" value="{{route('pedido_cabecera.purchaseorder')}}">
                                <i class="fa fa-filter"></i>
                            </button>
                            <input type="hidden" name="btn_detalles" id="btn_detalles" value="{{route('pedido_detalle.purchaseorder')}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <table id="cab_documento" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>sel</th>
                                    <th>Documento</th>
                                    <th>Fecha</th>
                                    <th>Nombre / Razón Social</th>
                                    <th>Glosa</th>
                                </tr>
                            </thead>
                            <tbdod>
                            </tbdod>
                        </table>
                    </div>
                    <p class="title-view">Listado de Productos:</p>
                    <div class="row">
                        <table id="det_documento" class="table table-bordered" width="100%">
                            <thead>
                            <tr>
                                <th>sel</th>
                                <th>id</th>
                                <th>documento</th>
                                <th>Para Pedir</th>
                                <th>Producto</th>
                                <th>U.M.</th>
                                <th>Descripción</th>
                                <th>Solicitado</th>
                                <th>C.Costo</th>
                            </tr>
                            </thead>
                            <tbdod>
                            </tbdod>
                        </table>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cancelar
                </button>
                <button type="button" class="btn btn-primary" name="btn_agregar_cart" id="btn_agregar_cart"
                        onclick="agregar_pedidos()"><span
                        class="fa fa-save"></span> Aceptar
                </button>
            </div>

        </div>
    </div>
</div>
