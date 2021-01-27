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
    @foreach($pprocesos as $value => $procesos)
        <tr>
            <th>{{$procesos->descripcion}}<input type="hidden" name="menu_id[{{$procesos->id}}]" value="{{$procesos->id}}"></th>
            <th class="text-center"><input type="checkbox" name="consulta[{{$procesos->id}}]" @if(count($ppprocesos)>0)@if($ppprocesos[$value]['consulta'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="crea[{{$procesos->id}}]" @if(count($ppprocesos)>0)@if($ppprocesos[$value]['crea'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="edita[{{$procesos->id}}]" @if(count($ppprocesos)>0)@if($ppprocesos[$value]['edita'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="anula[{{$procesos->id}}]" @if(count($ppprocesos)>0)@if($ppprocesos[$value]['anula'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="borra[{{$procesos->id}}]" @if(count($ppprocesos)>0)@if($ppprocesos[$value]['borra'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="imprime[{{$procesos->id}}]" @if(count($ppprocesos)>0)@if($ppprocesos[$value]['imprime'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="aprueba[{{$procesos->id}}]" @if(count($ppprocesos)>0)@if($ppprocesos[$value]['aprueba'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="precio[{{$procesos->id}}]" @if(count($ppprocesos)>0)@if($ppprocesos[$value]['precio'] == 1) checked @endif @endif></th>
        </tr>
    @endforeach
    </tbody>
</table>
