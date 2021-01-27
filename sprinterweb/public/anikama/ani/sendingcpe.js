$(document).ready(function(){

    tableSendingCpe.init("/sendingCpe/limpiar_datatable");

});

var variable = $("#var").val();

$("#btn_mostrar").click(function(){


    tableSendingCpe.init("/sendingCpe/mostrar/?txt_desde=" + $("#txt_desde").val() + "&txt_hasta=" + $("#txt_hasta").val() + "&cbo_tipo_doc=" + $("#cbo_tipo_doc").val());


});


$('#tableSendingCpe tbody').on('change', 'input[type=checkbox]', function () {

    tabla = $('#tableSendingCpe').DataTable();

    let data = tabla.row( $(this).parents('tr') ).data();

    if( $('#chk_aplicar' + data['id']).prop('checked') === true ) {

        let tipo_procesamiento = $("#cbo_tipo_procesamiento").val();


        switch(tipo_procesamiento){

            case 'S':

                let error = ['2017','2027','2145'];
                
                if(data['respuesta_dsc'] == '' || error.includes(data['respuesta_cod'])){

                    
                    $(this).closest('tr').toggleClass("color_listado", this.checked);

                }else{

                    $("#chk_aplicar" + data['id']).prop("checked", false);

                }

            break;


            case 'C':
                
                if(data['respuesta_dsc'] == '' && data['fecha_envio'] != null){

                    $(this).closest('tr').toggleClass("color_listado", this.checked);

                }else{

                    $("#chk_aplicar" + data['id']).prop("checked", false);

                }


            break;


            case 'E': 

                if(data['respuesta_cod'] === '0'){

                    $(this).closest('tr').toggleClass("color_listado", this.checked);

                }else{

                    $("#chk_aplicar" + data['id']).prop("checked", false);

                }

            break;


        }

      
    }else{

        $(this).closest('tr').toggleClass("color_listado", this.checked);

    }


});


$("#cbo_tipo_procesamiento").change(function(){

    deseleccionar_todos();

});


$("#chk_marcar_todos").change(function(){


    let tipo_procesamiento = $("#cbo_tipo_procesamiento").val();

    if($(this).prop('checked') === true){

        $("#chk_marcar_todos").prop("checked", true);

        let table = $('#tableSendingCpe').DataTable();
 
        let data = table.rows().data();

        

        for (i = 0; i < data.length; i++){
            

            switch(tipo_procesamiento){

                case 'S':

                    let error = ['2017','2027','2145'];
                    
                    if(data[i]['respuesta_dsc'] == '' || error.includes(data[i]['respuesta_cod'])){

                        seleccionar_todos(data[i]['id']);

                    }

                break;


                case 'C':
                    
                    if(data[i]['respuesta_dsc'] == '' && data[i]['fecha_envio'] != null){

                        seleccionar_todos(data[i]['id']);

                    }


                break;


                case 'E': 

                    if(data[i]['respuesta_cod'] === '0'){

                        seleccionar_todos(data[i]['id']);

                    }

                break;


            }


        }


    }else{

        deseleccionar_todos();

    }

});

function seleccionar_todos(id){

    // CHECKED
    $("#chk_aplicar"+id).prop('checked', true); 
    
    // SOMBREAR SELECCIONADOS
    $("#chk_aplicar"+id).closest('#tableSendingCpe tbody tr').toggleClass("color_listado", this.checked);

}

function deseleccionar_todos(){

    // QUITAR CHECKED 
    $("input:checkbox").prop("checked", false);

    // QUITAR SOMBREADO
    $('#tableSendingCpe tbody tr').removeClass("color_listado");


}

$("#btn_procesar").click(function(){

    let cbo_tipo_procesamiento = $("#cbo_tipo_procesamiento").val();
    let data = seleccionados_cpe();


    $.ajax({
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        type: "post",
        url: "/" + variable + "/" + 'procesar',
        data: {data: JSON.stringify(data), cbo_tipo_procesamiento: cbo_tipo_procesamiento},
        success: function (data) {



        },error: function (data) {
            mostrar_errores(data);
        }
    });


});

function seleccionados_cpe() {
    tabla = $('#tableSendingCpe').DataTable();
    let detalle_select = [];
    let det = {};

    tabla.rows().every(function (){
        var data = this.node();
        if($(data).find('input').prop('checked')){
            det = { ids:   $(data).find('input').serializeArray()[0].value };
            detalle_select.push(det);
        }
    });
    return detalle_select;
}


/*

EJEMPLO
$("#checkTodos").change(function () {
      $("input:checkbox").prop('checked', $(this).prop("checked"));
  });

*/