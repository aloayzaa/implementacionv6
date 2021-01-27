<div class="modal fade" id="modalHistorialAplicacion" role="dialog" aria-labelledby="myModalLabel"
     data-keyboard="false" data-backdrop="static" data-focus-on="input:first">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="color: black"><span class="fa fa-check"></span> Aplicación De Documentos</h4>
            </div>
            <div class="modal-body">
                <form action="" id="form-historial-aplicacion" name="form-historial-aplicacion">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <label for="historial_aplicacion_tercero">Tercero:</label><br>
                            <select name="historial_aplicacion_tercero" id="historial_aplicacion_tercero" class="select2"></select>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <a onclick="mostar_documentos_aplicar()" class="btn btn-xs btn-primary"> Mostrar</a>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-bordered" id="listado_documentos_aplicar">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Sel</th>
                                    <th>Aplicar</th>
                                    <th>Documento</th>
                                    <th>Fecha</th>
                                    <th>Vence</th>
                                    <th>Mon</th>
                                    <th>Saldo M.N.</th>
                                    <th>Saldo M.E.</th>
                                    <th>Glosa</th>
                                </tr>                            
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <label for="total_documentos_aplicar">Total:</label>
                            <input type="text" id="total_documentos_aplicar" name="total_documentos_aplicar" class="form-control" disabled>                        
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><span
                        class="fa fa-ban"></span> Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="button_acction" onclick="agregar_documentos_aplicar()"><span class="fa fa-save"></span>
                    Agregar
                </button>
            </div>
        </div>
    </div>
</div>
