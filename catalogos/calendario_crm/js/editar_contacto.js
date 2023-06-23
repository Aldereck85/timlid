let response;
let contacto_nota_id;
$(document).ready(function () {
  setTimeout(function () {
    contacto_nota_id = $("#contacto_id").val();
    initDataTableNotas();
  }, 1000);

  initActividadesUsuario();
  initActividadContacto();
  initSelectPropietario();
  initSelectMedios();
  initSelectEstados();
  loadState();
  loadSelectSelle();
  var id = verId();
  getContact(id);

  $("#task-tab-notas").click(function () {
    $("#estilos-tables").attr("href", "../../css/stylesTable.css");
    $("#calendar").hide();
  });

  $("#task-tab-actividades").click(function () {
    $("#estilos-tables").attr("href", "");
    $("#calendar").show();
  });

  $('[data-tooltip="tooltip"]').tooltip();

  $("#agregarNotaSlide").on("click", () => {
    $(".nav-link.active:not(#note-tab)").removeClass("active");
    $("#note-tab").tab("show");
  });

  $("#btnEditarContacto").click(function () {
    var contacto_list_id = verId();
    var id = $("#contacto_id").val();
    var accion = "editarContacto";
    var nombre = $("#nombreModal").val();
    var apellido = $("#apellidoModal").val();
    var empresa = $("#empresaModal").val();
    var email = $("#emailModal").val();
    var puesto = $("#puestoModal").val();
    var telefono = $("#telefonoModal").val();
    var celular = $("#celularModal").val();
    var propietario = $("#propietarioModalEditar").val();
    var estado_id = $("#estadoModalEditar").val();
    var medio_contacto_campania_id = $("#campaniaModalEditar").val();

    $.ajax({
      type: "POST",
      url: "app/controladores/ContactoController.php",
      data: {
        accion: accion,
        nombre: nombre,
        apellido: apellido,
        empresa: empresa,
        email: email,
        puesto: puesto,
        telefono: telefono,
        celular: celular,
        propietario: propietario,
        medio_contacto_campania_id: medio_contacto_campania_id,
        estado_id: estado_id,
        id: id,
        contacto_empresa_id: contacto_list_id,
      },
      //dataType: "json",
      success: function (data) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: data,
        });
        $("#editarContactoModal").modal("hide");
        return;
      },
    });
  });

  $("#agregarNotaSlide").click(function () {
    $("h5#modal-title-nota").empty();
    $("h5#modal-title-nota").text("Nueva nota");
    $("#nota").val("");
    $("input[type='radio'][name='color-actividad']").prop("checked", false);
    $("#btnGuardarNotas").show();
    $("#btnActualizarNotas").hide();
  });

  /*funcion para agregar nota a table notas,recarga tabla notas y oculta modal notas*/
  $("#btnGuardarNotas").click(function () {
    var accion = "agregarNota";
    var nota = $("#nota").val();
    if ($('input[type="radio"][name="color-actividad"]').is(":checked")) {
      var color = $(
        'input[type="radio"][name="color-actividad"]:checked'
      ).val();
    } else {
      color = "#233D9B";
    }
    var id = $("#contacto_id").val();

    $.ajax({
      method: "POST",
      url: "app/controladores/NotasController.php",
      data: {
        accion: accion,
        nota: nota,
        color: color,
        contacto_id: id,
      },
      dataType: "json",
      success: function (data) {
        $("#tblNotas").DataTable().ajax.reload();
        $("#addNote").modal("hide");
        $("a[href='#note']").trigger("click");
      },
    });
  });

  /*---funcion para actualizar una nota, actualiza la tabla y oculta la modal --*/
  $("#btnActualizarNotas").click(function () {
    var accion = "actualizarNota";
    var nota = $("#nota").val();
    var color = $('input[type="radio"][name="color-actividad"]:checked').val();
    var id = $("#contacto_id").val();
    var nota_id = $("#nota_id").val();

    $.ajax({
      method: "POST",
      url: "app/controladores/NotasController.php",
      data: {
        accion: accion,
        nota: nota,
        color: color,
        contacto_id: id,
        nota_id: nota_id,
      },
      //dataType: 'json',
      success: function (data) {
        $("#addNote").modal("hide");
        $("#tblNotas").DataTable().ajax.reload();
      },
    });
  });
});

function verId() {
  var query = window.location.search.substring(1);
  var vars = query.split("=");
  var id = vars[1];
  return id;
}

function initDataTableNotas() {
  let idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    sInfo: "Mostrando _START_ a _END_ de _TOTAL_ regristros",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: '<img src="../../img/icons/pagination.svg" width="20px"/>',
      sPrevious:
        '<img src="../../img/icons/pagination.svg" width="20px" style="transform: scaleX(-1)"/>',
    },
  };

  let filtro = "";
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    let color = data[0]; // use data for the age columm

    if (filtro == "") {
      return true;
    }

    if (color == filtro) {
      return true;
    } else {
      return false;
    }
  });

  let table = $("#tblNotas").DataTable({
    dom: "Bfrtip",
    buttons: [
      {
        text: "Todas",
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #D12F37;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#D12F37";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #EC6816;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#EC6816";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #F6B803;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#F6B803";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #038144;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#038144";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #45CE89;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#45CE89";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #11E7DD;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#11E7DD";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #233D9B;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#233D9B";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #8E8EED;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#8E8EED";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #D687E3;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#D687E3";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #572364;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#572364";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #9d9d97;"></div>',
        className: "btn btn-light btn-outline-secondary",
        action: function (e, dt, node, config) {
          filtro = "#9d9d97";
          $("#tblNotas").DataTable().draw();
        },
      },
    ],
    language: idioma_espanol,
    info: false,
    ajax: {
      type: "POST",
      url: "app/controladores/NotasController.php",
      data: {
        accion: "index",
        id: contacto_nota_id,
      },
      dataSrc: "",
    },
    columns: [
      {
        data: "color",
        render: function (data, type, row, meta) {
          return (
            '<div class="color-picker-sm mx-auto" style="background-color:' +
            data +
            ';"></div><span class="d-none">' +
            data +
            "</span>"
          );
        },
      },
      {
        data: "nota",
      },
      {
        data: "fecha_creacion",
      },
      {
        data: "fecha_edicion",
      },
      {
        data: null,
        render: function (data, type, row) {
          return (
            '<div class="d-flex justify-content-center mx-auto">' +
            '<i class="fas fa-pen mx-2 text-info" id="' +
            row.id +
            '" onclick="editClick(this)" title="Editar" style="cursor: pointer"></i>' +
            '<i class="fas fa-times mx-2 text-danger" id="' +
            row.id +
            '" onclick="deleteClick(this)" title="Eliminar" style="cursor: pointer"></i></div>'
          );
        },
      },
    ],
  });
}

function initSelectPropietario() {
  Propietario = new SlimSelect({
    select: "#propietarioModalEditar",
    placeholder: "Seleccione un medios",
    searchPlaceholder: "Buscar medios",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initActividadesUsuario() {
  actividadUsuarios = new SlimSelect({
    select: "#opciones-usuario",
  });
}

function initActividadContacto() {
  actividadContacto = new SlimSelect({
    select: "#opciones-actividades",
  });
}

function initSelectMedios() {
  MedioContacto = new SlimSelect({
    select: "#campaniaModalEditar",
    placeholder: "Seleccione un medios",
    searchPlaceholder: "Buscar medios",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectEstados() {
  Estados = new SlimSelect({
    select: "#estadoModalEditar",
    placeholder: "Seleccione un Estados",
    searchPlaceholder: "Buscar Estados",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function getContact(id) {
  var accion = "verContacto";
  $.ajax({
    type: "POST",
    url: "app/controladores/ContactoController.php",
    data: { id: id, accion: accion },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.cliente_id == null) {
        $("#tipo_contacto").append(
          '<h2 class="header-title-screen mt-3">Contacto</h2>\n' +
            '                       <h4><span class="color-orange">Lead</span></h4'
        );
      } else {
        $("#tipo_contacto").append(
          '<h2 class="header-title-screen mt-3">Contacto</h2>\n' +
            '                                            <h4><span class="color-green">Cliente</span></h4>'
        );
      }
      $(".nombre").text(respuesta.nombre);
      $(".empresa").text(respuesta.empresa);
      $(".estado").text(respuesta.Estado);
      $(".puesto").text(respuesta.puesto);

      if (respuesta.estado_id) {
        Estados.set("0");
      }
      Estados.set(respuesta.estado_id);
      Propietario.set(respuesta.empleado_id);
      MedioContacto.set(respuesta.medio_contacto_campania_id);

      $("#empresaModal").val(respuesta.empresa);
      $("#nombreModal").val(respuesta.nombre);
      $("#apellidoModal").val(respuesta.apellido);
      $("#puestoModal").val(respuesta.puesto);
      $("#emailModal").val(respuesta.email);
      $("#telefonoModal").val(respuesta.telefono);
      $("#celularModal").val(respuesta.celular);
      $("#contacto_id").val(respuesta.id);
    },
  });
}

function loadSelectSelle() {
  var accion = "CargarPropietarios";

  $.ajax({
    url: "app/controladores/CrearClienteController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      $("#propietarioModalEditar").empty();
      $.each(response, function (key, value) {
        $("#propietarioModalEditar").append(
          "<option value=" +
            value.empleado_id +
            ">" +
            value.nombre_empleado +
            "</option>"
        );
      });
    },
  });
}

function loadState() {
  var accion = "CargarEstados";

  $.ajax({
    url: "app/controladores/CrearClienteController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      $("#estadoModalEditar").empty();
      $.each(response, function (key, value) {
        $("#estadoModalEditar").append(
          "<option value=" + value.estado_id + ">" + value.estado + "</option>"
        );
      });
    },
  });
}

function editClick(id) {
  $("h5#modal-title-nota").empty();
  var id = $(id).attr("id");
  var contacto_id = $("#contacto_id").val();
  var accion = "verNota";
  $.ajax({
    url: "app/controladores/NotasController.php",
    data: {
      accion: accion,
      id: id,
      contacto_id: contacto_id,
    },
    type: "POST",
    dataType: "json",
    success: function (data) {
      $("h5#modal-title-nota").text("Editar Nota");
      $("#nota").val(data.descripcion);
      $("#radio").val(data.color);
      $("#nota_id").val(data.id);
      if (data.color == "#D12F37") {
        $("input[type='radio'][name='color-actividad'][value='#D12F37']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#EC6816") {
        $("input[type='radio'][name='color-actividad'][value='#EC6816']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#F6B803") {
        $("input[type='radio'][name='color-actividad'][value='#F6B803']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#038144") {
        $("input[type='radio'][name='color-actividad'][value='#038144']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#45CE89") {
        $("input[type='radio'][name='color-actividad'][value='#45CE89']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#11E7DD") {
        $("input[type='radio'][name='color-actividad'][value='#11E7DD']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#233D9B") {
        $("input[type='radio'][name='color-actividad'][value='#233D9B']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#8E8EED") {
        $("input[type='radio'][name='color-actividad'][value='#8E8EED']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#572364") {
        $("input[type='radio'][name='color-actividad'][value='#572364']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#9d9d97") {
        $("input[type='radio'][name='color-actividad'][value='#9d9d97']").prop(
          "checked",
          true
        );
      }
      $("#addNote").modal("show");
      $("#btnGuardarNotas").hide();
      $("#btnActualizarNotas").show();
    },
  });
}

function deleteClick(id) {
  var id = $(id).attr("id");
  var contacto_id = $("#contacto_id").val();
  var accion = "eliminarNota";
  $.ajax({
    url: "app/controladores/NotasController.php",
    data: {
      accion: accion,
      id: id,
      contacto_id: contacto_id,
    },
    type: "POST",
    //dataType: 'json',
    success: function (data) {
      response = JSON.parse(data);
      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: response["message"],
      });
      $("#tblNotas").DataTable().ajax.reload();
      return;
    },
    error: function (data) {
      response = JSON.parse(data);
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: response["message"],
      });
      return;
    },
  });
}
