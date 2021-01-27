<div class="modal fade" id="modal_ordencompra" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span>  Agregar Orden Compra</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-orden" class="form-horizontal">
                    <div class="row">
                        <div class="form-row">
                            <input type="hidden" id="id" name="id" value="">
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <table id="ref_ordencompra" class="table table-bordered" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Numero</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Nombre / Razón Social</th>
                                <th>Glosa</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <br>
                    <div class="row">
                        <table id="ref_ordencompradetail" class="table table-bordered" width="100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Aplicar</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>U.M.</th>
                                <th>Cantidad</th>
                                <th>Serie</th>
                                <th>Atendido</th>
                                <th>Stock</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="fa fa-ban"></span> Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="insertar_ordendecompra()" ><span class="fa fa-save"></span> Agregar Orden Compra</button>
            </div>
        </div><!-- /.modal-dialog -->
         </div><
    </div>
</div>
