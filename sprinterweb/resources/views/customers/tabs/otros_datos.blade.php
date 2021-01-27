<div class="col-md-12 col-xs-12">
    <br>
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <label for="txt_tercero">Tercero:</label>
            <input type="text" id="txt_codigo_otros_datos" name="txt_codigo_tercero" class="form-control" readonly>
        </div>
        <div class="col-md-5 col-xs-12" style="margin-top: 16px;">
            <input type="text" id="txt_razonsocial_otros_datos" name="txt_tercero" class="form-control" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <label for="cbo_clasificacion_otros">Clasificación:</label><br>
            <select name="cbo_clasificacion_otros" id="cbo_clasificacion_otros" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                @foreach($clasetercero as $c)
                    <option value="{{ $c->id }}">{{ $c->codigo }} | {{ $c->descripcion }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 col-xs-12">
            <label for="txt_fecha_nacimiento_otros">Fecha nacimiento:</label><br>
            <input type="date" id="txt_fecha_nacimiento_otros" name="txt_fecha_nacimiento_otros" class="form-control">
        </div>
        <div class="col-md-3 col-xs-12">
            <label for="cbo_tratamiento_otros">Tratamiento:</label><br>
            <select name="cbo_tratamiento_otros" id="cbo_tratamiento_otros" class="select2" style="width: 100%">
                <option value="">-Seleccionar-</option>
                <option value="Sr.">Sr.</option>
                <option value="Sra.">Sra.</option>
                <option value="Srta.">Srta.</option>
                <option value="Sres.">Sres.</option>
            </select>
        </div>
        <div class="col-md-4 col-xs-12">
            <label for="txt_conyuge_otros">Cónyuge:</label>
            <input type="text" id="txt_conyuge_otros" name="txt_conyuge_otros" class="form-control">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-2 col-xs-12">
            <label for="txt_fecha_afiliacion_otros">Fecha afilación:</label>
            <input type="date" id="txt_fecha_afiliacion_otros" name="txt_fecha_afiliacion_otros" class="form-control" value="{{$today}}" readonly>
        </div>
        <div class="col-md-5 col-xs-12">
            <label for="txt_servicio_otros">Permiso Servicio:</label>
            <input type="text" name="txt_servicio_otros" id="txt_servicio_otros" class="form-control">
        </div>
        <div class="col-md-5 col-xs-12">
            <label for="txt_observaciones_otros">Observaciones:</label>
            <textarea name="txt_observaciones_otros" id="txt_observaciones_otros" rows="3" class="form-control"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <label>Locales anexos:</label>
            <a id="modal_tercero_direccion" class="btn"><span class="fa fa-plus-square bt-select-ag""></span></a>
            <table class="table-striped" id="tabla_tercero_direccion" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Dirección</th>
                        <th>Ubigeo</th>
                        <th>Descricpción Ubideo</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <label>Facturar a nombre de:</label>
            <a id="modal_tercero_empresa" class="btn"><span class="fa fa-plus-square bt-select-ag""></span></a>
            <table class="table table-striped" id="tabla_tercero_empresa" style="width: 100%">
                <thead>
                    <tr>
                        <th>DNI/RUC</th>
                        <th>Nombre o Razón Social</th>
                        <th>Dirección</th>
                        <th>Tipo</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
