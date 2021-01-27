<table class="table table-hover table-bordered table-dark table-responsive">
    <thead class="text-center">
    <tr>
        <th>Opción</th>
        <th>Ver</th>
        <th>Crea</th>
        <th>Edita</th>
        <th>Anula</th>
        <th>Borra</th>
        <th>Print</th>
        <th>Aprob</th>
        <th>Precio</th>
    </tr>
    </thead>
    <tbody>
    @foreach($trconfiguracion as $value => $configuracion)
        <tr>
            <th>{{$configuracion->descripcion}}<input type="hidden" name="menu_id[{{$configuracion->id}}]" value="{{$configuracion->id}}"></th>
            <th class="text-center"><input type="checkbox" name="consulta[{{$configuracion->id}}]" @if(count($trpconfiguracion)>0)@if($trpconfiguracion[$value]['consulta'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="crea[{{$configuracion->id}}]" @if(count($trpconfiguracion)>0)@if($trpconfiguracion[$value]['crea'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="edita[{{$configuracion->id}}]" @if(count($trpconfiguracion)>0)@if($trpconfiguracion[$value]['edita'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="anula[{{$configuracion->id}}]" @if(count($trpconfiguracion)>0)@if($trpconfiguracion[$value]['anula'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="borra[{{$configuracion->id}}]" @if(count($trpconfiguracion)>0)@if($trpconfiguracion[$value]['borra'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="imprimer[{{$configuracion->id}}]" @if(count($trpconfiguracion)>0)@if($trpconfiguracion[$value]['imprime'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="aprueba[{{$configuracion->id}}]" @if(count($trpconfiguracion)>0)@if($trpconfiguracion[$value]['aprueba'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="precio[{{$configuracion->id}}]" @if(count($trpconfiguracion)>0)@if($trpconfiguracion[$value]['precio'] == 1) checked @endif @endif></th>
        </tr>
    @endforeach
    </tbody>
</table>
