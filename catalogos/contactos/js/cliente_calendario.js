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

initActividadesUsuario();
initActividadContacto();
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

$("#btnEditarContacto").click(function (e) {
  $("#invalid-empresa").css("display", "none");
  $("#empresaModal").removeClass("is-invalid");
  $("#invalid-nombre").css("display", "none");
  $("#nombreModal").removeClass("is-invalid");

  if (!$("#empresaModal").val()) {
    $("#invalid-empresa").css("display", "block");
    $("#empresaModal").addClass("is-invalid");
  }
  if (!$("#nombreModal").val()) {
    $("#invalid-nombre").css("display", "block");
    $("#nombreModal").addClass("is-invalid");
  }
  var validate_empresa =
    $("#invalid-empresa").css("display") === "block" ? false : true;
  //var validate_owner = $("#invalid-empresa").css("display") === "block" ? false : true;
  var validate_name =
    $("#invalid-nombre").css("display") === "block" ? false : true;

  var contacto_list_id = verId();
  var id = $("#contacto_id").val();
  var accion = "editarContacto";
  var empresa = $("#empresaModal").val();
  var propietario = $("#propietarioModalEditar").val();
  var medio_contacto_campania_id = $("#campaniaModalEditar").val();
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

  if (validate_name && validate_empresa) {
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
      $("#nota").val("");
      $("input:radio[name=color]").prop("checked", false);
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
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
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

function getContact(id) {
  var accion = "verCliente";
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
      $("#tipo_contacto").append('<h2 class="color-green mt-3">Cliente</h2>');
      $(".nombre").text(respuesta.NombreComercial);
      $(".rfc").text(respuesta.rfc);
      $("#contacto_id").val(respuesta.PKCliente);
      $("#contacto_id_select").val("cli-" + respuesta.PKCliente);
    },
    error: function (e) {},
  });
}


