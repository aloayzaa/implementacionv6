<div class="modal fade" id="modal_ref" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                <h4 class="modal-title"><span class="fa fa-check"></span>  Documentos de Referencia</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-add-ref" class="form-horizontal">
     {{--               <div class="row">
                        <div class="form-row">
                            <input type="hidden" id="id" name="id" value="">

                            <div class="form-group col-md-2">
                                <label for="referencia_desde">Desde: </label>
                                <input type="text" class="form-control" name="referencia_desde" id="referencia_desde" value="" readonly>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="referencia_hasta">Hasta: </label>
                                <input type="text" class="form-control" name="referencia_hasta" id="referencia_hasta" value="" readonly>
                            </div>
                        </div>
                    </div>--}}

                    <br>

                    <div class="row">
                        <table id="ref_documento" class="table table-bordered" width="100%">
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
                        <table id="ref_docdetalles" class="table table-bordered" width="100%">
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
                <button type="button" class="btn btn-primary" onclick="insertar_referencia()" ><span class="fa fa-save"></span> Agregar Referencia</button>
            </div>
        </div><!-- /.modal-dialog -->
         </div>
    </div>
</div>
