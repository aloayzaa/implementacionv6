
<div class="col-md-12 col-xs-12">
    <div class="row">
        <label for="">Confirmaciones de Servicio</label>
        <div class="form-group" style="float: right">
            <a class="form-control btn-primary text-center" @if(isset($servicio)) onclick="archivar()" @endif><span class="fa fa-folder-open-o"></span> Archivar Orden</a>
        </div>
        <table id="lista_confirmaciones" class="table table-striped table-bordered table-bordered2" width="100%">
            <thead>
                <tr role="row">
                    <th>Fecha</th>
                    <th>Referencia</th>
                    <th>Glosa</th>
                    <th>Importe M.N.</th>
                    <th>Importe M.E.</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <label for="">Provisiones</label>
        <input type="hidden" id="tabla_provisiones" name="tabla_provisiones" value="{{route('lista_provisiones.serviceorders')}}">
        <table id="lista_proviciones" class="table table-striped table-bordered table-bordered2" width="100%">
            <thead>
            <tr role="row">
                <th>Fecha</th>
                <th>Referencia</th>
                <th>Glosa</th>
                <th>Importe M.N.</th>
                <th>Importe M.E.</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div class="form-group">
            <div class="col-md-6">
                <label for="" style="float: right">Total Provisiones</label>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control btn-block" placeholder="0.00" readonly id="importe_nacional">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control btn-block" placeholder="0.00" readonly id="importe_extranjero">
            </div>
        </div>
    </div>
</div>
