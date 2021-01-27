<div class="row">

    <div class="col-md-5 col-sm-5 col-xs-12">

        <div class="row">
        
            <div class="panel panel-info">
                <div class="panel-heading">Guías Remisión:</div>
                <div class="panel-body">
                    <div class="row">
                        <table id="listado_guias_remision"  class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Número</th>
                                    <th>Glosa</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>                    
                    </div>
                </div>
            </div>    

        </div>

        <div class="row">

            <div class="panel panel-info">
                <div class="panel-heading">Cronología CPE:</div>
                <div class="panel-body">
                    <div class="row">
                        <table id="listado_cronologia_cpe"  class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th> {{-- index para ordendar ver datatable --}}
                                    <th>Fecha</th>
                                    <th>Glosa</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>                    
                    </div>
                </div>
            </div> 

        </div>


    </div>

    <div class="col-md-7 col-sm-7 col-xs-12">
        {{-- 
        <div class="row">

            <div class="col-md-2 col-sm-2 col-xs-12">
                <button type="button" class="btn btn-xs btn-primary">Aplicar con</button>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <label for="historial_documento">Documento aplicar:</label>
                <input type="text" name="historial_documento" id="historial_documento" class="form-control" value="{{$documento_aplicacion}}" readonly>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <label for="historial_importe">Importe aplicar:</label>
                <input type="text" name="historial_importe" id="historial_importe" class="form-control" value="{{$importe_aplicacion}}" readonly>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <label for="chk_historial_aplicar"><input type="checkbox" value="1" id="chk_historial_aplicar" name="chk_historial_aplicar" @if(empty($documento_aplicacion) == false) @if($referencia->aplicar == 1) checked @endif @endif> Aplicar</label>
            </div>

        </div>
        --}}

        <div class="row">

            <div class="panel panel-info">
                <div class="panel-heading">Historial / Aplicaciones del documento:</div>
                <div class="panel-body">
                    <div class="row">
                        <table id="listado_hitorial_aplicaciones"  class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Referencia</th>
                                    <th>Importe M.N.</th>
                                    <th>Importe M.E.</th>
                                    <th>Glosa</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>                    
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <label for="historial_saldo_mn">Saldo documento &nbsp;&nbsp;&nbsp;M.N.</label>
                            <input type="text" id="historial_saldo_mn" name="historial_saldo_mn" class="form-control" value="{{$saldomn}}" disabled>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <label for="historial_saldo_me">M.E.</label>
                            <input type="text" id="historial_saldo_me" name="historial_saldo_me" class="form-control" value="{{$saldome}}" disabled>
                        </div>                        
                    </div>
                </div>
            </div> 

        
        </div>



    </div>

</div>