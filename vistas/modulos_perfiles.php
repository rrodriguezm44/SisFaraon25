<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Administrar Modulos y Perfiles</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
          <li class="breadcrumb-item active">Adm. Modulos_Perfiles</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">

    <ul class="nav nav-tabs" id="tabs-asignar-modulos-perfil" role="tablist">

      <li class="nav-item">
        <a class="nav-link" id="content-perfiles-tab" data-bs-toggle="pill" href="#content-perfiles" role="tab"
          aria-controls="content-perfiles" aria-selected="false">Perfiles</a>
      </li>
      <li class="nav-item">
        <a class="nav-link " id="content-modulos-tab" data-bs-toggle="pill" href="#content-modulos" role="tab"
          aria-controls="content-modulos" aria-selected="false">Modulos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" id="content-modulo-perfil-tab" data-bs-toggle="pill" href="#content-modulo-perfil"
          role="tab" aria-controls="content-modulo-perfil" aria-selected="false">Asignar Modulo a Perfil</a>
      </li>
    </ul>

    <div class="tab-content" id="tabsContent-asignar-modulos-perfil">

      <div class="tab-pane fade mt-4 px-4" id="content-perfiles" role="tabpanel" aria-labelledby="content-modulos-tab">
        <h4>Administrar Pefiles</h4>
      </div>

      <div class="tab-pane fade mt-4 px-4" id="content-modulos" role="tabpanel" aria-labelledby="content-modulos-tab">
        <h4>Administrar Modulos</h4>
      </div>

      <div class="tab-pane fade active show mt-4 px-4" id="content-modulo-perfil" role="tabpanel"
        aria-labelledby="content-modulo-perfil-tab">
        <div class="row">
          <div class="col-md-8">

            <div class="card card-info card-outline shadow">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list"></i> Listado de Perfiles</h3>
              </div>

              <div class="card-body">
                <table id="tbl_perfiles_asignar" class="display nowrap table-striped w-100 shadow rounded">
                  <thead class="bg-info text-left">
                    <th>id Perfil</th>
                    <th>Perfil</th>
                    <th>Estado</th>
                    <th>F. Creación</th>
                    <th>F. Actualización</th>
                    <th class="text-center">Opciones</th>
                  </thead>

                  <tbody class="small text left">
                  </tbody>

                </table>
              </div>

            </div>
          </div>
          <div class="col-md-4">

            <div class="card card-info card-outline shadow" style="display: none;" id="card-modulos">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-laptop"></i> Modulos del Sistema</h3>
              </div>

              <div class="card-body" id="card-body-modulos">
                <div class="row m-2">
                  <div class="col-md-6">
                    <button class="btn btn-success btn-small m-0 p-0 w-100" id="marcar_modulos">Marcar todo</button>
                  </div>
                  <div class="col-md-6">
                    <button class="btn btn-danger btn-small m-0 p-0 w-100" id="desmarcar_modulos">Desmarcar
                      todo</button>
                  </div>
                </div>

                <div id="modulos" class="demo"></div>

                <div class="row m-2">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Seleccione el modulo de inicio</label>

                      <select class="custom-select" id="select_modulos">
                      </select>

                    </div>
                  </div>
                </div>

                <div class="row m-2">
                  <div class="col-md-12">
                    <button class="btn btn-success btn-small w-50 text-center" id="asignar_modulos">Asignar</button>
                  </div>
                </div>

              </div>

            </div>
          </div>
        </div>

      </div>

    </div>


  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<script>
/* =============================================================
VARIABLES GLOBALES
============================================================= */
var tbl_perfiles_asignar, tbl_modulos, modulos_usuario, modulos_sistema;

$(document).ready(function() {

  /* =============================================================
  FUNCIONES PARA LAS CARGAS INICIALES DE DATATABLES, ARBOL DE MODULOS Y REAJUSTE DE CABECERAS DE DATATABLES
  ============================================================= */
  cargarDataTables();
  iniciarArbolModulos();


  var idPerfil = 0;
  var selectedElmsIds = [];

  $('#tbl_perfiles_asignar tbody').on('click', '.btnSeleccionarPerfil', function() {

    var data = tbl_perfiles_asignar.row($(this).parents('tr')).data();


    if ($(this).parents('tr').hasClass('selected')) {

      $(this).parents('tr').removeClass('selected');

      $('#modulos').jstree('deselect_all', false);

      $("#select_modulos option").remove();

      idPerfil = 0;

      $("#card-modulos").css("display", "none");

    } else {

      tbl_perfiles_asignar.$('tr.selected').removeClass('selected');

      $(this).parents('tr').addClass('selected');


      idPerfil = data[0];

      //alert(idPerfil);
      $("#card-modulos").css("display", "block"); //mostrar panekl del sistema

      $.ajax({
        async: false,
        url: "ajax/modulo.ajax.php",
        method: 'POST',
        data: {
          accion: 'obtener_modulos_x_perfil',
          id_perfil: idPerfil
        },
        dataType: 'json',
        success: function(respuesta) {
          // console.log(respuesta);

          modulos_usuario = respuesta;

          seleccionarModulosPerfil(idPerfil);
        }
      });

    }
  })

  // /* =============================================================
  // EVENTO QUE SE DISPARA CADA VEZ QUE HAY UN CAMBIO EN EL ARBOL DE MODULOS
  // ============================================================= */

  $("#modulos").on("changed.jstree", function(evt, data) {

    $("#select_modulos option").remove();

    var selectedElms = $("#modulos").jstree("get_selected", true);

    //console.log(selectedElms)

    $.each(selectedElms, function() {

      for (let i = 0; i < modulos_sistema.length; i++) {

        if (modulos_sistema[i]["id"] == this.id && modulos_sistema[i]["vista"]) {

          $("#select_modulos").append($('<option>', {
            value: this.id,
            text: this.text
          }));

        }

      }
    })

    if ($("#select_modulos").has('option').length <= 0) {

      $('#select_modulos').append($('<option>', {
        value: 0,
        text: " -- No hay modulos seleccionados -- "
      }));
    }

  })


  // /*EVENTO PARA MARCAR TODOS LOS CHECKBOX DEL ARBOL DE MODULOS*/

  $("#marcar_modulos").on('click', function() {
    $('#modulos').jstree('select_all');
  })

  // /*EVENTO PARA DESMARCAR TODOS LOS CHECKBOX DEL ARBOL DE MODULOS*/
  $("#desmarcar_modulos").on('click', function() {

    $('#modulos').jstree("deselect_all", false);
    $("#select_modulos option").remove();

    $('#select_modulos').append($('<option>', {
      value: 0,
      text: " -- No hay modulos seleccionados -- "
    }));
  })

  /* REGISTRO EN BASE DE DATOS DE LOS MODULOS ASOCIADOS AL PERFIL*/

  $("#asignar_modulos").on('click', function() {

    selectedElmsIds = []

    var selectedElms = $('#modulos').jstree("get_selected", true);

    $.each(selectedElms, function() {

      selectedElmsIds.push(this.id);

      if (this.parent != "#") {

        selectedElmsIds.push(this.parent);

      }

    });

    //QUITAMOS ELEMENTOS DUPLICADOS
    let modulosSeleccionados = [...new Set(selectedElmsIds)];
    //console.log(modulosSeleccionados);

    let modulo_inicio = $("#select_modulos").val();



    if (idPerfil != 0 && modulosSeleccionados.length > 0) {
      registrarPerfilModulos(modulosSeleccionados, idPerfil, modulo_inicio);
    } else {
      Swal.fire({
        position: 'center',
        icon: 'warning',
        title: 'Debe seleccionar el perfil y módulos a registrar',
        showConfirmButton: false,
        timer: 3000
      })
    }

  })

  //funciones para la cargas iniciales de datatables, arbol de maodulos y reajuste de cabeceras de datatables
  //cargarDataTables();

  //iniciarArbolModulos();

  fnCargarArbolModulos();

  /* =============================================================
  REORGANIZAR MODULOS DEL SISTEMA
  ============================================================= */
  $("#btnReordenarModulos").on('click', function() {
    fnOrganizarModulos();
  })

  /* =============================================================
  REINICIALIZAR MODULOS DEL SISTEMA EN EL JSTREE
  ============================================================= */
  $("#btnReiniciar").on('click', function() {
    actualizarArbolModulos();
  })


}) /*fin document ready*/

function cargarDataTables() {

  tbl_perfiles_asignar = $('#tbl_perfiles_asignar').DataTable({
    ajax: {
      async: false,
      url: 'ajax/perfil.ajax.php',
      type: 'POST',
      dataType: 'json',
      dataSrc: "",
      data: {
        accion: 'obtener_perfiles_asignar'
      }
    },
    columnDefs: [{
        targets: 0,
        className: "text-center"
      },
      {
        targets: 2,
        sortable: false,
        createdCell: function(td, cellData, rowData, row, col) {
          if (parseInt(rowData[2]) == 1) {
            $(td).html("Activo")
          } else {
            $(td).html("Inactivo")
          }
        }
      },
      {
        targets: 5,
        sortable: false,
        render: function(data, type, full, meta) {
          return "<center>" +
            "<span class='btnSeleccionarPerfil text-primary px-1' style='cursor: pointer; ' data-bs-toggle='tooltip' data-bs-placement='top' title='Seleccionar Perfil'> " +
            "<i class='fas fa-check fs-5'></i> " +
            "</span> " +
            "</center>";
        }
      }
    ],
    language: {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      },
      "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
  });
}

function iniciarArbolModulos() {

  var formData = new FormData();
  formData.append('accion', 'obtener_modulos');

  response = SolicitudAjax("ajax/modulo.ajax.php", "POST", formData);
  modulos_sistema = response;

  // mensajeToast(response["tipo_msj"], response["msj"])

  // inline data demo
  $('#modulos').jstree({
    'core': {
      "check_callback": true,
      'data': response
    },
    "checkbox": {
      "keep_selected_style": true
    },
    "types": {
      "default": {
        "icon": "fas fa-laptop text-warning"
      }
    },
    "plugins": ["wholerow", "checkbox", "types", "changed"]

  }).bind("loaded.jstree", function(event, data) {
    // you get two params - event & data - check the core docs for a detailed description
    $(this).jstree("open_all");
  });

  /* $.ajax({
    async: false,
    url: "ajax/modulo.ajax.php",
    method: 'POST',
    data: {
      accion: 'obtener_modulos'
    },
    dataType: 'json',
    success: function(respuesta) {
      //inline data demo

      modulos_sistema = respuesta;
      console.log(respuesta)

      $('#modulos').jstree({
        'core': {
          "check_callback": true,
          'data': respuesta
        },
        "checkbox": {
          "keep_selected_style": true
        },
        "types": {
          "default": {
            "icon": "fas fa-laptop text-warning"
          }
        },
        "plugins": ["wholerow", "checkbox", "types", "changed"]

      }).bind("loaded.jstree", function(event, data) {
        //you get two params - event & data - check the core docs for a detailed description 
        $(this).jstree("open_all");
      });
    }
  }) */
}

function seleccionarModulosPerfil(pin_idPerfil) {

  //alert(pin_idPerfil)

  $('#modulos').jstree('deselct_all');

  for (let i = 0; i < modulos_sistema.length; i++) {

    if (modulos_sistema[i]["id"] == modulos_usuario[i]["id"] && modulos_usuario[i]["sel"] == 1) {

      $("#modulos").jstree("select_node", modulos_sistema[i]["id"]);

    }
  }

  /*OCULTAR LA OPCION DE MODULOS Y PERFILES PARA EL PERFIL DE ADMINISTRADOR*/
  if (pin_idPerfil == 1) {
    $("#modulos").jstree(true).hide_node(22);
  } else {
    $("#modulos").jstree(true).show_all();
  }
}

function registrarPerfilModulos(modulosSeleccionados, idPerfil, idModulo_inicio) {

  $.ajax({
    async: false,
    url: "ajax/perfil_modulo.ajax.php",
    method: 'POST',
    data: {
      accion: 'registrar_perfil_modulo',
      id_modulosSeleccionados: modulosSeleccionados,
      id_Perfil: idPerfil,
      id_modulo_inicio: idModulo_inicio
    },
    dataType: 'json',
    success: function(respuesta) {
      if (respuesta > 0) {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Se registro correctamente',
          showConfirmButton: false,
          timer: 2000
        })

        $("#select_modulos option").remove();
        $("#modulos").jstree("deselect_all", false);
        tbl_perfiles_asignar.ajax.reload();
        $("#card-modulos").css("display", "none");
      } else {

        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Error al registrar',
          showConfirmButton: false,
          timer: 2000
        })

      }
    }
  })
}

function actualizarArbolModulosPerfiles() {

  $.ajax({
    async: false,
    url: "ajax/modulo.ajax.php",
    method: 'POST',
    data: {
      accion: 'obtener_modulos'
    },
    dataType: 'json',
    success: function(respuesta) {
      modulos_sistema = respuesta;

      $('#modulos').jstree(true).settings.core.data = respuesta;
      $('#modulos').jstree(true).refresh();
    }
  });

}

function fnCargarArbolModulos() {

  var dataSource;

  $.ajax({
    async: false,
    url: "ajax/modulo.ajax.php",
    method: 'POST',
    data: {
      accion: 'obtener_modulos'
    },
    dataType: 'json',
    success: function(respuesta) {

      dataSource = respuesta;
    }
  });


  /*
  $.jstree.defaults.core.check_callback:
      Determina lo que sucede cuando un usuario intenta modificar la estructura del árbol .
      Si se deja como false se impiden todas las operaciones como crear, renombrar, eliminar, mover o copiar.
      Puede configurar esto en true para permitir todas las interacciones o usar una función para tener un mejor control.
  */
  $('#arbolModulos').jstree({
    "core": {
      "check_callback": true,
      "data": dataSource
    },
    "types": {
      "default": {
        "icon": "fas fa-laptop"
      },
      "file": {
        "icon": "fas fa-laptop"
      }
    },
    "plugins": ["types", "dnd"]
  }).bind('ready.jstree', function(e, data) {
    $('#arbolModulos').jstree('open_all')
  })

}

function actualizarArbolModulos() {

  $.ajax({
    async: false,
    url: "ajax/modulo.ajax.php",
    method: 'POST',
    data: {
      accion: 'obtener_modulos'
    },
    dataType: 'json',
    success: function(respuesta) {

      $('#arbolModulos').jstree(true).settings.core.data = respuesta;
      $('#arbolModulos').jstree(true).refresh();
    }
  });

}

function fnOrganizarModulos() {

  var array_modulos = [];

  var reg_id, reg_padre_id, reg_orden;

  var v = $("#arbolModulos").jstree(true).get_json('#', {
    'flat': true
  });

  for (i = 0; i < v.length; i++) {

    var z = v[i];

    //asignamos el id, el padre Id y el nombre del modulo
    reg_id = z["id"];
    reg_padre_id = z["parent"];
    reg_orden = i;

    array_modulos[i] = reg_id + ';' + reg_padre_id + ';' + reg_orden;

  }

  /*REGISTRAMOS LOS MODULOS CON EL NUEVO ORDENAMIENTO */
  $.ajax({
    async: false,
    url: "ajax/modulo.ajax.php",
    method: 'POST',
    data: {
      accion: 'reorganizar_modulos',
      modulos: array_modulos
    },
    dataType: 'json',
    success: function(respuesta) {

      mensajeToast(respuesta["tipo_msj"], respuesta["msj"])
      tbl_modulos.ajax.reload();

      //recargamos arbol de modulos - MANTENIMIENTO MODULOS ASIGNADOS A PERFILES                                
      actualizarArbolModulosPerfiles();


    }
  });

}
</script>