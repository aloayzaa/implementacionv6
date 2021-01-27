function prueba_o_produccion(id, tipo) {
    if(tipo === 1){
        swal.fire({
            title: '¿Desea actualizar a producción?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, actualizar",
            cancelButtonText: "Cancelar",
        }).then(function (result) {
            if (result.value) {
                var variable = $("#variable").val();
                var token = $("#_token").val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    url: "/" + variable + "/prueba-produccion/" + id,
                    type: "POST",
                    data: {tipo: tipo},
                    success: function (data) {
                        if (data.estado === 'ok') {
                            $(".table").DataTable().ajax.reload();
                            success("success", data.mensaje, "Actualizado");
                        } else {
                            error("error", data.mensaje, "Error");
                        }
                    }
                });
            }
        });
    } else if(tipo === 0){
        swal.fire({
            title: '¿Desea actualizar a prueba?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "Si, actualizar",
            cancelButtonText: "Cancelar",
        }).then(function (result) {
            if (result.value) {
                var variable = $("#variable").val();
                var token = $("#_token").val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': token},
                    url: "/" + variable + "/prueba-produccion/" + id,
                    type: "POST",
                    data: {tipo: tipo},
                    success: function (data) {
                        if (data.estado === 'ok') {
                            $(".table").DataTable().ajax.reload();
                            success("success", data.mensaje, "Actualizado");
                        } else {
                            error("error", data.mensaje, "Error");
                        }
                    }
                });
            }
        });
    }

}
