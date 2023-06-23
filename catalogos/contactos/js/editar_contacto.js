let response;
let contacto_nota_id;

setTimeout(function () {
  contacto_nota_id = $("#contacto_id").val();
  initDataTableNotas();
}, 1000);

var max_chars = 250;

$("#contador").html(max_chars);

$("#nota").keyup(function () {
  var chars = $(this).val().length;
  var diff = max_chars - chars;
  $("#contador").html(diff);
});

//$("#empresaModal").prop("disabled", true);

initActividadesUsuario();
initActividadContacto();
initSelectPropietario();
var slimMedios = initSelectMedios();
initSelectEstados();
initSelectPaises();
//loadState();
loadSelectSelle();
loadMedios(slimMedios);
var id = verId();
getContact(id);

$("#task-tab-notas").click(function () {
  //$("#estilos-tables").attr("href", "../../css/stylesTable.css");
  $("#calendar").hide();
});

$("#task-tab-actividades").click(function () {
  //$("#estilos-tables").attr("href", "");
  $("#calendar").show();
});

$('[data-tooltip="tooltip"]').tooltip();

$("#agregarNotaSlide").on("click", () => {
  $(".nav-link.active:not(#note-tab)").removeClass("active");
  $("#note-tab").tab("show");
});

$("#nombreModal").on("change", function () {
  $("#nombreModal").removeClass("is-invalid");
  $("#invalid-nombre").css("display", "none");
});

$("#empresaModal").on("change", function () {
  $("#empresaModal").removeClass("is-invalid");
  $("#invalid-empresa").css("display", "none");
});

$("#celularModal").on("change", function () {
  $("#celularModal").removeClass("is-invalid");
  $("#invalid-celular").css("display", "none");
});

$("#nombreModalContacto").on("change", function () {
  $("#nombreModalContacto").removeClass("is-invalid");
  $("#invalid-nombre-contacto").css("display", "none");
});

$("#emailModalContacto").on("change", function () {
  $("#emailModalContacto").removeClass("is-invalid");
  $("#invalid-email-contacto").css("display", "none");
});

$("#celularModalContacto").on("change", function () {
  $("#celularModalContacto").removeClass("is-invalid");
  $("#invalid-celular-contacto").css("display", "none");
});

$("#btnEditarContacto").click(function (e) {
  if (!$("#empresaModal").val()) {
    $("#invalid-empresa").css("display", "block");
    $("#empresaModal").addClass("is-invalid");
  }
  if (!$("#nombreModal").val()) {
    $("#invalid-nombre").css("display", "block");
    $("#nombreModal").addClass("is-invalid");
  }
  if (!$("#celularModal").val()) {
    $("#invalid-celular").css("display", "block");
    $("#celularModal").addClass("is-invalid");
  }
  var validate_empresa =
    $("#invalid-empresa").css("display") === "block" ? false : true;
  var validate_name =
    $("#invalid-nombre").css("display") === "block" ? false : true;
  var validate_celular =
    $("#invalid-celular").css("display") === "block" ? false : true;

  if (validate_name && validate_empresa && validate_celular) {
    var contacto_list_id = verId();
    var id = $("#contacto_id").val();
    var accion = "editarContacto";
    var empresa = $("#empresaModal").val();
    var propietario =
      $("#propietarioModalEditar").val() === "undefined" ||
      !$("#propietarioModalEditar").val()
        ? ""
        : $("#propietarioModalEditar").val();
    var medio_contacto_campania_id =
      $("#campaniaModalEditar").val() === "undefined" ||
      !$("#campaniaModalEditar").val()
        ? ""
        : $("#campaniaModalEditar").val();
    var nombre = $("#nombreModal").val();
    var apellido = $("#apellidoModal").val();
    var puesto = $("#puestoModal").val();
    var email = $("#emailModal").val();
    var telefono = $("#telefonoModal").val();
    var celular = $("#celularModal").val();
    var sitioWeb = $("#sitioWebModal").val();
    var direccion = $("#direccionModal").val();
    var aniversario = $("#aniversarioModal").val();
    var pais_id = $("#paisModalEditar").val();
    var estado_id = $("#estadoModalEditar").val();
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
        sitioWeb: sitioWeb,
        direccion: direccion,
        aniversario: aniversario,
        estado_id: estado_id,
        pais_id: pais_id,
        id: id,
        contacto_empresa_id: contacto_list_id,
      },
      dataType: "json",
      success: function (res) {
        console.log(res);
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: res.message,
        });
        $("#editarContactoModal").modal("hide");
        getContact(contacto_list_id);
      },
      error: function (e) {},
    });
  }
});

$("#btnAgregarContacto").click(function (e) {
  if (!$("#nombreModalContacto").val()) {
    $("#invalid-nombre-contacto").css("display", "block");
    $("#nombreModalContacto").addClass("is-invalid");
  }
  if (!$("#emailModalContacto").val()) {
    $("#invalid-email-contacto").css("display", "block");
    $("#emailModalContacto").addClass("is-invalid");
  }
  if (!$("#celularModalContacto").val()) {
    $("#invalid-celular-contacto").css("display", "block");
    $("#celularModalContacto").addClass("is-invalid");
  }
  var validate_nombre =
    $("#invalid-nombre-contacto").css("display") === "block" ? false : true;
  var validate_email =
    $("#invalid-email-contacto").css("display") === "block" ? false : true;
  var validate_celular =
    $("#invalid-celular-contacto").css("display") === "block" ? false : true;

  if (validate_email && validate_nombre && validate_celular) {
    var id = $("#contacto_id").val();
    var accion = "agregarContactoProspecto";
    var nombre = $("#nombreModalContacto").val();
    var email = $("#emailModalContacto").val();
    var celular = $("#celularModalContacto").val();
    console.log({
      id: id,
      accion: accion,
      nombre: nombre,
      email: email,
      celular: celular,
    });
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "app/controladores/ContactoController.php",
      data: {
        id: id,
        accion: accion,
        nombre: nombre,
        email: email,
        celular: celular,
      },
      success: function (res) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: res.message,
        });
        $("#tblContactosProspectos").DataTable().ajax.reload();
        $("#nombreModalContacto").val("");
        $("#emailModalContacto").val("");
        $("#celularModalContacto").val("");
      },
      error: function (e) {},
    });
  }
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
  if ($('input[type="radio"][name="color"]').is(":checked")) {
    var color = $('input[type="radio"][name="color"]:checked').val();
  } else {
    color = "#98DDCA";
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
      $("#nota").val("");
      $("input:radio[name=color]").prop("checked", false);
      $("a[href='#note']").trigger("click");
    },
  });
});

/*---funcion para actualizar una nota, actualiza la tabla y oculta la modal --*/
$("#btnActualizarNotas").click(function () {
  var accion = "actualizarNota";
  var nota = $("#nota").val();
  var color = $('input[type="radio"][name="color"]:checked').val();
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
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
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
    language: idioma_espanol,
    info: false,
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "btn-custom mr-2",
        },
        buttonLiner: {
          tag: null,
        },
      },
      buttons: [
        {
          text: '<span class="d-flex align-items-center" id="btnAgregarNota"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
          className: "btn-custom--white-dark",
          action: function () {
            modalNota();
          },
        },
      ],
    },
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
            '<div class="color-picker-sm" style="background-color:' +
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
            '<div class="d-flex justify-content-center">' +
            '<i class="fas fa-edit pointer color-primary" id="' +
            row.id +
            '" onclick="editClick(this)" title="Editar" style="cursor: pointer"></i>' +
            '<i class="fas fa-trash-alt ml-2 color-primary" id="' +
            row.id +
            '" onclick="deleteClick(this)" title="Eliminar" style="cursor: pointer"></i></div>'
          );
        },
      },
    ],
  });

  new $.fn.dataTable.Buttons(table, {
    dom: {
      button: {
        tag: "button",
        className: "btn-table-custom",
      },
      buttonLiner: {
        tag: null,
      },
    },
    buttons: [
      {
        text: "Todas",
        action: function (e, dt, node, config) {
          filtro = "";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #98DDCA;"></div>',
        action: function (e, dt, node, config) {
          filtro = "#98DDCA";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #D5ECC2;"></div>',
        action: function (e, dt, node, config) {
          filtro = "#D5ECC2";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #FFD3B4;"></div>',
        action: function (e, dt, node, config) {
          filtro = "#FFD3B4";
          $("#tblNotas").DataTable().draw();
        },
      },
      {
        text: '<div class="color-picker-sm mx-auto" style="background-color: #FFAAA7;"></div>',
        action: function (e, dt, node, config) {
          filtro = "#FFAAA7";
          $("#tblNotas").DataTable().draw();
        },
      },
    ],
  });

  table.buttons(1, null).container().appendTo("#btn-filters");
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
  return new SlimSelect({
    select: "#campaniaModalEditar",
    placeholder: "Seleccione un medios",
    searchPlaceholder: "Buscar medios",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      if (value === "" || !value) {
        return false;
      }
      validateMedio(value);
    },
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

function initSelectPaises() {
  Paises = new SlimSelect({
    select: "#paisModalEditar",
    placeholder: "Seleccione un Pais",
    searchPlaceholder: "Buscar Pais",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function loadMedios(slimSelectItem, selectedItem = "") {
  $.ajax({
    url: "app/controladores/ContactoController.php",
    data: { accion: "cargarMedios" },
    type: "POST",
    dataType: "json",
    success: function (response) {
      slimSelectItem.setData(response);
      slimSelectItem.set(selectedItem);
    },
  });
}

function validateMedio(medio) {
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "app/controladores/ContactoController.php",
    data: {
      accion: "validateMedio",
      medio,
    },
    success: function (res) {
      if (res.status === "fail") {
        //lobibox
        return;
      }
      if (res.existe === 0) {
        addMedio(medio);
      } else {
        //lobibox
        return;
      }
    },
    error: function (e) {},
  });
}

function addMedio(medio) {
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "app/controladores/ContactoController.php",
    data: {
      accion: "addMedio",
      medio,
    },
    success: function (res) {
      if (res.status === "fail") {
        //lobibox
        return;
      }
      loadMedios(slimMedios, res.id);
    },
    error: function (e) {},
  });
}

function getContact(id) {
  var accion = "verContacto";
  $.ajax({
    type: "POST",
    url: "app/controladores/ContactoController.php",
    data: {
      id: id,
      accion: accion,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#tipo_contacto").html("");
      if (respuesta.cliente_id == null) {
        $("#tipo_contacto").append(
          '<h2 class="color-orange mt-3">Prospecto</h2>'
        );
      } else {
        $("#tipo_contacto").append('<h2 class="color-green mt-3">Cliente</h2>');
      }
      var fullName = respuesta.apellido
        ? respuesta.nombre + " " + respuesta.apellido
        : respuesta.nombre;
      $(".nombre").text(fullName);
      $(".empresa").text(respuesta.empresa);
      $(".estado").text(respuesta.Estado);
      $(".puesto").text(respuesta.puesto);
      respuesta.estado_id ? Estados.set(respuesta.estado_id) : Estados.set("0");
      respuesta.pais_id ? Paises.set(respuesta.pais_id) : Paises.set("0");
      Propietario.set(respuesta.empleado_id);
      slimMedios.set(respuesta.medio_contacto_campania_id);
      $("#empresaModal").val(respuesta.empresa);
      $("#nombreModal").val(respuesta.nombre);
      $("#apellidoModal").val(respuesta.apellido);
      $("#puestoModal").val(respuesta.puesto);
      $("#emailModal").val(respuesta.email);
      $("#telefonoModal").val(respuesta.telefono);
      $("#celularModal").val(respuesta.celular);
      $("#sitioWebModal").val(respuesta.sitio_web);
      $("#direccionModal").val(respuesta.direccion);
      $("#aniversarioModal").val(respuesta.aniversario_empresa);
      $("#contacto_id").val(respuesta.id);
      $("#contacto_id_select").val("cnt-" + respuesta.id);

      if(respuesta.motivo_declinar) {
        $("#contacto-declinado").removeClass("d-none");
        $(".motivo-declinacion").text(respuesta.motivo_declinar);
      }
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
      console.log({ response });
      $("#propietarioModalEditar").empty();
      $.each(response, function (key, item) {
        $("#propietarioModalEditar").append(
          "<option value=" + item.value + ">" + item.text + "</option>"
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
      if (data.color == "#98DDCA") {
        $("input[type='radio'][name='color'][value='#98DDCA']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#D5ECC2") {
        $("input[type='radio'][name='color'][value='#D5ECC2']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#FFD3B4") {
        $("input[type='radio'][name='color'][value='#FFD3B4']").prop(
          "checked",
          true
        );
      }
      if (data.color == "#FFAAA7") {
        $("input[type='radio'][name='color'][value='#FFAAA7']").prop(
          "checked",
          true
        );
      }
      $("#addNote").modal("show");
      $("#btnGuardarNotas").hide();
      $("#btnActualizarNotas").show();
    },
    error: function (e) {},
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
    },
  });
}

function modalContacto() {
  $("#editarContactoModal").modal("show");
  var id = verId();
  getContact(id);
}

function modalContactos() {
  $("#agregarContactoModal").modal("show");
  initDataTableContactosProspectos($("#contacto_id").val());
}

function initDataTableContactosProspectos(id) {
  $("#tblContactosProspectos").DataTable().destroy();
  $("#tblContactosProspectos").DataTable({
    language: {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    },
    info: false,
    searching: false,
    scrollX: true,
    bSort: false,
    pageLength: 50,
    responsive: true,
    lengthChange: false,
    buttons: [],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    ajax: {
      type: "POST",
      url: "app/controladores/ContactoController.php",
      data: { accion: "verContactosProspectos", id: id },
      dataSrc: "",
    },
    columns: [
      { data: "nombre" },
      { data: "email" },
      { data: "celular" },
      {
        data: "id",
        render: function (data) {
          return (
            '<i class="fas fa-trash-alt ml-2 color-primary" onclick="deleteContactoPros(' +
            data +
            ')" title="Eliminar" style="cursor: pointer"></i>'
          );
        },
      },
    ],
  });
}

function deleteContactoPros(id) {
  $.ajax({
    url: "app/controladores/ContactoController.php",
    data: {
      id: id,
      accion: "deleteContactoProspecto",
    },
    type: "POST",
    success: function (data) {
      response = JSON.parse(data);
      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: "Contacto eliminado correctamente",
      });
      $("#tblContactosProspectos").DataTable().ajax.reload();
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
        msg: "Algo salio mal.",
      });
    },
  });
}

$(document).on("click","#btnAgregarNota",()=>{
    console.log("hola");
    $("#btnActualizarNotas").hide();
});