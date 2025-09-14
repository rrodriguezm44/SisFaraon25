function CargarSelect(id = null, id_select = null, txt_option_default, url_request, accion, id_filtro = null, todo_activo = 0) {

    var datos = new FormData();
    datos.append('accion', accion);
    datos.append('id_filtro',id_filtro)    

    $.ajax({
        async: false,
        url: url_request,
        type: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (respuesta) {

            id_select.html('');

            // var options = '<option selected value="" class="text-secondary disabled">' + txt_option_default + '</option>';

            if (todo_activo == 1) {
                var options = '<option selected value="" class="text-secondary">' + txt_option_default + '</option>';
            } else {                
                var options = '<option selected value="" class="text-secondary" disabled>' + txt_option_default + '</option>';
            }
            

            for (let index = 0; index < respuesta.length; index++) {
                if (id && respuesta[index][0] == id) {
                    options = options + '<option selected value=' + respuesta[index][0] + '>' + respuesta[index][1] + '</option>';
                } else {
                    options = options + '<option value=' + respuesta[index][0] + '>' + respuesta[index][1] + '</option>';
                }

            }

            id_select.append(options);

        }

    });
}

function SolicitudAjax(url_ajax, type_ajax, formData) {

    var response;

    $.ajax({
        async: false,
        url: url_ajax,
        type: type_ajax,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (respuesta) {
            response = respuesta;
        }
    });

    return response;

}