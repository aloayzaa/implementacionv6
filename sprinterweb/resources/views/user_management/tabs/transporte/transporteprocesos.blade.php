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
    @foreach($trprocesos as $value => $procesos)
        <tr>
            <th>{{$procesos->descripcion}}<input type="hidden" name="menu_id[{{$procesos->id}}]" value="{{$procesos->id}}"></th>
            <th class="text-center"><input type="checkbox" name="consulta[{{$procesos->id}}]" @if(count($trpprocesos)>0)@if($trpprocesos[$value]['consulta'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="crea[{{$procesos->id}}]" @if(count($trpprocesos)>0)@if($trpprocesos[$value]['crea'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="edita[{{$procesos->id}}]" @if(count($trpprocesos)>0)@if($trpprocesos[$value]['edita'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="anula[{{$procesos->id}}]" @if(count($trpprocesos)>0)@if($trpprocesos[$value]['anula'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="borra[{{$procesos->id}}]" @if(count($trpprocesos)>0)@if($trpprocesos[$value]['borra'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="imprime[{{$procesos->id}}]" @if(count($trpprocesos)>0)@if($trpprocesos[$value]['imprime'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="aprueba[{{$procesos->id}}]" @if(count($trpprocesos)>0)@if($trpprocesos[$value]['aprueba'] == 1) checked @endif @endif></th>
            <th class="text-center"><input type="checkbox" name="precio[{{$procesos->id}}]" @if(count($trpprocesos)>0)@if($trpprocesos[$value]['precio'] == 1) checked @endif @endif></th>
        </tr>
    @endforeach
    </tbody>
</table>
