let id;
let nombre;
$(document).ready(function () {
  initDataTable();
  loadSelectSelle();
  loadMedios();
  loadRegimen();
  loadState();
  loadPais();
  initSelect();
  loadClientes();

  //$("#resultado").hide();
  $("#nav-tab-activos").click(function () {
    $("#nav-tab-activos").one("shown.bs.tab", function (e) {
      $("#tblContactosNuevos").DataTable().clear().draw();
      $("#tblContactosNuevos").DataTable().ajax.reload();
    });
  });

  $("#empresaModal").on("change", function () {
    $("#empresaModal").removeClass("is-invalid");
    $("#invalid-nombre-comercial").css("display", "none");
  });

  $("#emailModal").on("change", function () {
    $("#emailModal").removeClass("is-invalid");
    $("#invalid-email").css("display", "none");
  });

  $("#razon_socialModal").on("change", function () {
    $("#razon_socialModal").removeClass("is-invalid");
    $("#invalid-razon-social").css("display", "none");
  });

  /*  $("#rfcModal").on("change", function () {
    $("#rfcModal").removeClass("is-invalid");
    $("#invalid-rfc").css("display", "none");
  }); */

  $("#codigo_postalModal").on("change", function () {
    $("#codigo_postalModal").removeClass("is-invalid");
    $("#invalid-codigo-postal").css("display", "none");
  });

  $("#agregarCliente").click(function () {
    var datosFactClient = document.getElementById("clienteFacturacion");
    datosFactClient.checked
      ? addClienteFacturacion()
      : addClienteNoFacturacion();
  });

  /* $("#rfcModal").on("keypress", function () {
    $input = $(this);
    setTimeout(function () {
      $input.val($input.val().toUpperCase());
    }, 25);
  }); */

  $("#btnCreateContactCliente").click(function () {
    var accion = "CrearContactosCliente";

    var contacto_id = $("#contacto_id").val();

    var nombre = $("#nombreModal").val();
    var apellido = $("#apellidoModal").val();
    var puesto = $("#puestoModal").val();
    var celular = $("#celularModal").val();
    var telefono = $("#telefonoModal").val();
    var email = $("#emailModal").val();

    var facturacion = $("#Check1:checked").prop("checked") ? 1 : 0;
    var complemento = $("#Check2:checked").prop("checked") ? 1 : 0;
    var avisos = $("#Check3:checked").prop("checked") ? 1 : 0;
    var pagos = $("#Check4:checked").prop("checked") ? 1 : 0;

    $.ajax({
      method: "POST",
      url: "app/controladores/CrearClienteController.php",
      data: {
        accion: accion,
        contacto_id: contacto_id,
        facturacion: facturacion,
        complemento: complemento,
        avisos: avisos,
        pagos: pagos,
        nombre: nombre,
        apellido: apellido,
        puesto: puesto,
        celular: celular,
        telefono: telefono,
        email: email,
      },
      // dataType: 'json',
      success: function (data) {
        response = JSON.parse(data);

        if (response["success"] == true) {
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
          $("#ModalContact").modal("hide");
          $("#tblContactosNuevos").DataTable().ajax.reload();
          return;
        } else if (response["error"] == true) {
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
          $("#ModalContact").modal("hide");
          return;
        }
      },
    });
  });

  var max_chars = 250;

  $("#contador").html(max_chars);

  $("#motivo").keyup(function () {
    var chars = $(this).val().length;
    var diff = max_chars - chars;
    $("#contador").html(diff);
  });

  $("#btnEliminarContacto").click(function () {
    var accion = "eliminarContacto";
    var motivo = $("#motivo").val();
    var id = $("#id").val();

    $.ajax({
      method: "POST",
      dataType: "json",
      url: "app/controladores/ContactoController.php",
      data: {
        accion: accion,
        motivo: motivo,
        id: id,
      },
      success: function (res) {
        var notificationTipe = res.status === "success" ? "success" : "error";
        var notificationIcon =
          res.status === "success" ? "checkmark.svg" : "warning_circle.svg";
        Lobibox.notify(notificationTipe, {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/" + notificationIcon,
          msg: res.message,
        });
      },
      error: function (e) {
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
      complete: function () {
        $("#InactivarLead").modal("hide");
        $("#motivo").val("");
        $("#id").val("");
        $("#tblContactosNuevos").DataTable().ajax.reload();
      },
    });
  });

  $("#btnAgregarCliente").click(function () {
    var accion = "AgregarContactoCliente";
    var campania = $("#campaniaModal").val();
    var email = $("#emailModal").val();
    var id = 30;

    $.ajax({
      method: "POST",
      url: "app/controladores/ContactoController.php",
      data: {
        accion: accion,
        campania: campania,
        email: email,
        id: id,
      },
      dataType: "json",
      success: function (response) {
        if (response.error) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: response.error,
          });
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: response.message,
          });
        }
      },
    });
  });

  $("#agregarClienteExistente").click(function () {
    var accion = "AgregarContactoClienteExistente";
    var contacto_id = $("#contacto_id").val();
    var clienteExistente = $("#clientesModalExistente").val();
    var nombreExistente = $("#nombreModalExistente").val();
    var emailExistente = $("#emailModalExistente").val();
    var celularExistente = $("#celularModalExistente").val();

    $("#invalid-cliente-existente").css("display", "none");
    $("#clientesModalExistente").removeClass("is-invalid");
    $("#invalid-nombre-existente").css("display", "none");
    $("#nombreModalExistente").removeClass("is-invalid");
    $("#invalid-email-existente").css("display", "none");
    $("#emailModalExistente").removeClass("is-invalid");
    $("#invalid-celular-existente").css("display", "none");
    $("#celularModalExistente").removeClass("is-invalid");

    if (!clienteExistente || clienteExistente === "undefined") {
      $("#invalid-cliente-existente").css("display", "block");
      $("#clientesModalExistente").addClass("is-invalid");
    }
    if (!nombreExistente) {
      $("#invalid-nombre-existente").css("display", "block");
      $("#nombreModalExistente").addClass("is-invalid");
    }
    if (!emailExistente) {
      $("#invalid-email-existente").css("display", "block");
      $("#emailModalExistente").addClass("is-invalid");
    }
    if (!celularExistente) {
      $("#invalid-celular-existente").css("display", "block");
      $("#celularModalExistente").addClass("is-invalid");
    }

    if (
      contacto_id &&
      clienteExistente &&
      nombreExistente &&
      emailExistente &&
      celularExistente
    ) {
      $.ajax({
        method: "POST",
        dataType: "json",
        url: "app/controladores/ContactoController.php",
        data: {
          accion: accion,
          contacto_id: contacto_id,
          cliente_id: clienteExistente,
          emailExistente: emailExistente,
          nombreExistente: nombreExistente,
          celularExistente: celularExistente,
        },
        success: function (response) {
          console.log(response);
          if (response.status === "success") {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3100,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: response.message,
            });
            $("#tblContactosNuevos").DataTable().ajax.reload();
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3100,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "Algo salio mal",
            });
          }
        },
        error: function (e) {
          console.log(e);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Algo salio mal",
          });
        },
        complete: function () {
          $("#ActivarLead").modal("hide");
        },
      });
    }
  });

  $("#clienteFacturacion").change(function (e) {
    $("#invalid-nombre-contacto").css("display", "none");
    $("#nombreContacto").removeClass("is-invalid");
    $("#invalid-email").css("display", "none");
    $("#emailModal").removeClass("is-invalid");
    $("#invalid-celular").css("display", "none");
    $("#celularModalNuevo").removeClass("is-invalid");
    $("#invalid-medio").css("display", "none");
    $("#medioModal").removeClass("is-invalid");
    $("#invalid-nombre-comercial").css("display", "none");
    $("#empresaModal").removeClass("is-invalid");
    $("#invalid-vendedor").css("display", "none");
    $("#propietarioModalVendedor").removeClass("is-invalid");
    $("#invalid-razon-social").css("display", "none");
    $("#razonSocialModal").removeClass("is-invalid");
    $("#invalid-rfc").css("display", "none");
    $("#rfcModal").removeClass("is-invalid");
    $("#invalid-regimen").css("display", "none");
    $("#regimenModal").removeClass("is-invalid");
    $("#invalid-codigo-postal").css("display", "none");
    $("#codigo_postalModal").removeClass("is-invalid");
    $("#invalid-pais").css("display", "none");
    $("#modalPais").removeClass("is-invalid");
    $("#invalid-estado").css("display", "none");
    $("#estadoModal").removeClass("is-invalid");

    var datosFactClient = document.getElementById("datos-cliente-facturacion");
    if (e.target.checked) {
      datosFactClient.classList.remove("d-none");
      return;
    }
    datosFactClient.classList.add("d-none");
  });
});

var PropietarioVendedor = new SlimSelect({
  select: "#propietarioModalVendedor",
  placeholder: "Seleccione un vendedor",
  searchPlaceholder: "Buscar vendedor",
  allowDeselect: false,
  deselectLabel: '<span class="">✖</span>',
});

var Estados = new SlimSelect({
  select: "#estadoModal",
  placeholder: "Seleccione un estado federativo",
  searchPlaceholder: "Buscar estado federativo",
  allowDeselect: false,
  deselectLabel: '<span class="">✖</span>',
});

var paisesSlim = new SlimSelect({
  select: "#modalPais",
  placeholder: "Seleccione un pais",
  searchPlaceholder: "Buscar pais",
  allowDeselect: false,
  deselectLabel: '<span class="">✖</span>',
});

var clientesSlim = new SlimSelect({
  select: "#clientesModalExistente",
  placeholder: "Seleccione un cliente",
  searchPlaceholder: "Buscar cliente",
  allowDeselect: false,
  deselectLabel: '<span class="">✖</span>',
});

var mediosSlimSelect = new SlimSelect({
  select: "#medioModal",
  placeholder: "Seleccione un medio de contacto",
  searchPlaceholder: "Buscar medio",
  allowDeselect: false,
  deselectLabel: '<span class="">✖</span>',
});

var regimenSlimSelect = new SlimSelect({
  select: "#regimenModal",
  placeholder: "Seleccione un régimen fiscal",
  searchPlaceholder: "Buscar régimen",
  allowDeselect: false,
  deselectLabel: '<span class="">✖</span>',
});

function initDataTable() {
  var filtro = "";
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[8]; // informacion del estado de la cotizacion

    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else {
      return false;
    }
  });
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

  let table = $("#tblContactosNuevos").DataTable({
    language: idioma_espanol,
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 50,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ targets: [0, 1], visible: false }],
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
            window.location.href = "agregar_contacto.php";
          },
        },
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      type: "POST",
      url: "app/controladores/ContactoController.php",
      data: { accion: "verContactosActivos" },
      dataSrc: "",
    },
    columns: [
      { data: "id" },
      { data: "contacto_id" },
      {
        data: "contacto",
        render: function (data, type, row, meta) {
          var url = "editar_contacto.php?id=" + row.contacto_id + "";
          return (
            '<a href="' + url + '" title="Motivo declinar: '+row.motivo+'">' + row.nombre + " " + row.apellido + "</a>"
          );
        },
      },
      { data: "empresa" },
      { data: "email" },
      { data: "medio_contacto_campania" },
      { data: "propietario" },
      {
        data: "tipo",
        render: function (data, type, row, meta) {
          if (data == null) {
            return "Prospecto";
          } else {
            return "Cliente";
          }
        },
      },
      {
        data: "estatus",
        render: function (data, type, row, meta) {
          if (data == "2") {
            return '<span class="left-dot red-dot">Inactivo</span>';
          } else if (data == "1") {
            return `<span class="left-dot green-dot">Activo</span>`;
          }
        },
      },
      {
        data: "tipo",
        render: function (data, type, row, meta) {
          if (row.estatus === 2) {
            return `<button class="btn btn-sm id="${row.id}"  onclick="activarProspecto(${row.id})" title="Activar prospecto">
            <i class="far fa-thumbs-up text-primary"></i></button>`;
          }
          return `<button class="btn btn-sm" id="id_${row.id}" data-contacto="${row.contacto_id}" data-id="${row.id}" onclick="obtenerCliente(this)" title="Ascender a cliente"><i class="far fa-thumbs-up text-success" ></i></button>
          <button class="btn btn-sm" data-toggle="modal" data-target="#InactivarLead" id="${row.id}"  onclick="eliminarProspecto(${row.id})" title="Desactivar prospecto"><i class="far fa-thumbs-down text-warning"></i></button>`;
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
        text: '<i class="fas fa-globe"></i> Todas',
        className: "btn-table-custom--blue",
        action: function (e, dt, node, config) {
          filtro = "";
          $("#tblContactosNuevos").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-check-circle"></i> Activo',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          filtro = "Activo";
          $("#tblContactosNuevos").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-times"></i> Inactivo',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Inactivo";
          $("#tblContactosNuevos").DataTable().draw();
        },
      },
    ],
  });

  table.buttons(1, null).container().appendTo("#btn-filters");

  //reset_listeners();
}

function add_searchbuilder() {
  $(".dtsb-add").on("click", function () {
    reset_icons();

    if ($(".dtsb-list-edit").length == 0) {
      let div =
        '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista:</span><input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15">';
      div +=
        '<button id="dtsb-save-1" class="dtsb-button" type="button" style="margin-left: 10px">Crear Lista</button></div>';
      $($("#tblContactosNuevos").DataTable().searchBuilder.container()).append(
        div
      );
    }
    reset_listeners();
  });
}

function left_right_searchbuilder(edit = false) {
  $(".dtsb-left, .dtsb-right, .dtsb-clearAll, .dtsb-clearGroup").on(
    "click",
    function () {
      reset_listeners();
      reset_icons();
    }
  );
}

function delete_searchbuilder(edit = false) {
  $(".dtsb-delete").on("click", function () {
    reset_listeners();
    if ($(".dtsb-criteria").length == 0) {
      $(".dtsb-list-edit").remove();
    }
  });
}

function reset_icons() {
  $(".dtsb-clearGroup").html('<i class="fas fa-times"></i>');
  $(".dtsb-delete").html('<i class="fas fa-times"></i>');
  $(".dtsb-right").html('<i class="fas fa-angle-right"></i>');
  $(".dtsb-left").html('<i class="fas fa-angle-left"></i>');
}

function reset_listeners() {
  delete_searchbuilder();
  left_right_searchbuilder();
  add_searchbuilder();
}

function obtenerCliente(obj) {
  var contacto_id = obj.dataset.contacto;
  var id = obj.dataset.id;
  var accion = "VerificarContacto";

  $.ajax({
    method: "POST",
    url: "app/controladores/CrearClienteController.php",
    data: {
      accion: accion,
      id: id,
      contacto_empresa_id: contacto_id,
    },
    dataType: "json",
    success: function (response) {
      console.log({ response });
      console.log(response.celular);
      if (response) {
        /* CLIENTE NUEVO */
        $("#contacto_id").val(response.id);
        $("#nombreContacto").val(response.nombre);
        $("#apellidoModal").val(response.apellido);
        $("#puestoModal").val(response.puesto);
        $("#celularModalNuevo").val(response.celular);
        $("#campaniaModal").val(response.medio_contacto_campania_id);
        $("#telefonoModal").val(response.telefono);
        $("#emailModal").val(response.email);
        /* CLIENTE EXISTENTE */
        $("#nombreModalExistente").val(response.nombre);
        $("#apellidoContacto").val(response.apellido);
        $("#puestoContacto").val(response.puesto);
        $("#telefonoModal").val(response.telefono);
        $("#emailModalExistente").val(response.email);
        $("#celularModalExistente").val(response.celular);
        mediosSlimSelect.set(response.medio_contacto_campania_id);
        $("#empresaModal").val(response.empresa);
        PropietarioVendedor.set(response.empleado_id);
        $("#sitioWebModal").val(response.sitio_web);
        $("#calleModal").val(response.direccion);
        paisesSlim.set(response.pais_id);
        Estados.set(response.estado_id);

        $("#ActivarLead").modal("show");
      }
    },
  });
}

function confirmNuevoCliente(id, nombre_cliente) {
  Swal.fire({
    title: "¡Registro éxitoso!",
    icon: "success",
    html:
      "<label>¿Quieres darle siguiento en el módulo de clientes a: " +
      nombre_cliente +
      "?</label>",
    width: "600px",
    showCancelButton: true,
    showConfirmButton: true,
    confirmButtonText: "Si",
    cancelButtonText: "No",
    reverseButtons: true,
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
      cancelButton: "btn-custom btn-custom--border-blue",
    },
    buttonsStyling: false,
    allowEnterKey: false,
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href =
        "../../catalogos/clientes/catalogos/clientes/editar_cliente.php?c=" +
        id;
    } else if (result.isDismissed) {
      $("#ActivarLead").modal("hide");
      $("#tblContactosNuevos").DataTable().ajax.reload();
    }
  });
}

function confirmCreateClient(response, nombre, apellido) {
  var empresa = $("#empresaModal").val();
  Swal.fire({
    title: response.message,
    icon: "error",
    html:
      "<label>¿Quieres crearlo como contacto del cliente: " +
      empresa +
      "?</label>",
    width: "600px",
    showCancelButton: true,
    showConfirmButton: true,
    confirmButtonText: "Si",
    cancelButtonText: "No",
    reverseButtons: true,
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
      cancelButton: "btn-custom btn-custom--border-blue",
    },
    buttonsStyling: false,
    allowEnterKey: false,
  }).then((result) => {
    if (result.isConfirmed) {
      $("#ModalContact").modal("show");
    } else if (result.isDismissed) {
      Lobibox.notify("info", {
        size: "mini",
        rounded: true,
        delay: 6000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg:
          "Para poder agregar al contacto " +
          nombre +
          " " +
          apellido +
          " como cliente tienes que modificar el nombre comercial",
      });
    }
  });
}

function activarProspecto(id) {
  Swal.fire({
    icon: "warning",
    buttonsStyling: false,
    showCancelButton: true,
    confirmButtonText: "Activar",
    cancelButtonText: "Cancelar",
    title: "¿Activar el prospecto?",
    text: "El prospecto estará disponible de nuevo.",
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--blue",
      cancelButton: "btn-custom btn-custom--border-blue",
    },
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        method: "POST",
        dataType: "json",
        url: "app/controladores/ContactoController.php",
        data: {
          id: id,
          accion: "activarContacto",
        },
        success: function (res) {
          var notificationTipe = res.status === "success" ? "success" : "error";
          var notificationIcon =
            res.status === "success" ? "checkmark.svg" : "warning_circle.svg";
          Lobibox.notify(notificationTipe, {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/" + notificationIcon,
            msg: res.message,
          });
        },
        error: function (e) {
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
        complete: function () {
          $("#InactivarLead").modal("hide");
          $("#motivo").val("");
          $("#id").val("");
          $("#tblContactosNuevos").DataTable().ajax.reload();
        },
      });
    }
  });
}

function eliminarProspecto(id) {
  console.log(id);
  $("#id").val(id);
  $("#InactivarLead").modal("show");
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
      response.push({
        placeholder: true,
        text: "Selecciona un vendedor",
      });
      PropietarioVendedor.setData(response);
    },
  });
}

function loadMedios() {
  var accion = "CargarMedios";
  $.ajax({
    url: "app/controladores/CrearClienteController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      response.push({
        placeholder: true,
        text: "Selecciona medio",
      });
      mediosSlimSelect.setData(response);
    },
    error: function (e) {},
  });
}

function loadRegimen() {
  var accion = "CargarRegimen";
  $.ajax({
    url: "app/controladores/CrearClienteController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      regimenSlimSelect.setData(response);
    },
    error: function (e) {},
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
      Estados.setData(response);
    },
  });
}

function loadPais() {
  var accion = "CargarPaises";

  $.ajax({
    url: "app/controladores/CrearClienteController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      paisesSlim.setData(response);
    },
  });
}

function clearInputs() {
  $("#contacto_id").val("");
  $("#nombreModal").val("");
  $("#apellidoModal").val("");
  $("#puestoModal").val("");
  $("#celularModal").val("");

  $("#empresaModal").val("");
  $("#campaniaModal").val("");
  $("#propietarioModal").val(0);
  $("#telefonoModal").val("");
  $("#emailModal").val("");

  $("#montoModal").val("");
  $("#diasModal").val("");

  $("#razon_socialModal").val("");
  $("#rfcModal").val("");
  $("#municipioModal").val("");
  $("#coloniaModal").val("");
  $("#calleModal").val("");
  $("#numero_exteriorModal").val("");
  $("#numero_interiorModal").val("");
  $("#codigo_postalModal").val("");
  $("#paisModal").val(0);
  $("#estadoModal").val(0);
}

function initSelect() {
  Propietario = new SlimSelect({
    select: "#propietarioModal",
    placeholder: "Seleccione un vendedor",
    searchPlaceholder: "Buscar vendedor",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function rfcValido(rfc, aceptarGenerico = true) {
  const re =
    /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
  var validado = rfc.match(re);

  //Coincide con el formato general del regex?
  if (!validado) return false;

  //Separar el dígito verificador del resto del RFC
  const digitoVerificador = validado.pop(),
    rfcSinDigito = validado.slice(1).join(""),
    len = rfcSinDigito.length,
    //Obtener el digito esperado
    diccionario = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
    indice = len + 1;
  var suma, digitoEsperado;

  if (len == 12) suma = 0;
  else suma = 481; //Ajuste para persona moral

  for (var i = 0; i < len; i++)
    suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);
  digitoEsperado = 11 - (suma % 11);
  if (digitoEsperado == 11) digitoEsperado = 0;
  else if (digitoEsperado == 10) digitoEsperado = "A";

  //El dígito verificador coincide con el esperado?
  // o es un RFC Genérico (ventas a público general)?
  if (
    digitoVerificador != digitoEsperado &&
    (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000")
  )
    return false;
  else if (
    !aceptarGenerico &&
    rfcSinDigito + digitoVerificador == "XEXX010101000"
  )
    return false;
  return rfcSinDigito + digitoVerificador;
}

function validarInput(input) {
  var rfc = input.value.trim().toUpperCase();
  if (rfc == "") {
    $("#invalid-rfc").css("display", "none");
    $("#invalid-rfc").text("El campo rfc es requerido.");
    $("#rfcModal").removeClass("is-invalid");
    return;
  }
  var rfcCorrecto = rfcValido(rfc);
  if (rfcCorrecto) {
    //$("#resultado").hide();
    $.ajax({
      url: "../clientes/php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_rfc_Cliente",
        data: rfcCorrecto,
      },
      dataType: "json",
      success: function (data) {
        if (parseInt(data.existe) == 1) {
          $("#invalid-rfc").css("display", "block");
          $("#invalid-rfc").text("El rfc ya se encuentra registrado");
          $("#rfcModal").addClass("is-invalid");
        }
      },
    });
  } else {
    $("#invalid-rfc").css("display", "block");
    $("#invalid-rfc").text("El rfc es invalido");
    $("#rfcModal").addClass("is-invalid");
  }
}

function loadClientes() {
  $.ajax({
    url: "app/controladores/CrearClienteController.php",
    data: {
      accion: "CargarClientes",
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      response.unshift({
        placeholder: true,
        text: "Selecciona un cliente",
      });
      clientesSlim.setData(response);
    },
  });
}

$("#ActivarLead").on("hidden.bs.modal", function (event) {
  /* CLIENTE EXISTENTE */
  clientesSlim.set();
  $("#nombreModalExistente").val("");
  $("#emailModalExistente").val("");
  $("#celularModalExistente").val("");
  $("#invalid-cliente-existente").css("display", "none");
  $("#clientesModalExistente").removeClass("is-invalid");
  $("#invalid-nombre-existente").css("display", "none");
  $("#nombreModalExistente").removeClass("is-invalid");
  $("#invalid-email-existente").css("display", "none");
  $("#emailModalExistente").removeClass("is-invalid");
  $("#invalid-celular-existente").css("display", "none");
  $("#celularModalExistente").removeClass("is-invalid");

  /* CLIENTE NUEVO */
  mediosSlimSelect.set();
  PropietarioVendedor.set();
  regimenSlimSelect.set();
  paisesSlim.set();
  Estados.set();
  $("#nombreContacto").val("");
  $("#apellidoContacto").val("");
  $("#puestoContacto").val("");
  $("#telefonoModal").val("");
  $("#emailModal").val("");
  $("#celularModalNuevo").val("");
  $("#empresaModal").val("");
  $("#razonSocialModal").val("");
  $("#rfcModal").val("");
  $("#sitioWebModal").val("");
  $("#municipioModal").val("");
  $("#coloniaModal").val("");
  $("#calleModal").val("");
  $("#numero_exteriorModal").val("");
  $("#numero_interiorModal").val("");
  $("#codigo_postalModal").val("");
  $("#invalid-nombre-contacto").css("display", "none");
  $("#nombreContacto").removeClass("is-invalid");
  $("#invalid-email").css("display", "none");
  $("#emailModal").removeClass("is-invalid");
  $("#invalid-celular").css("display", "none");
  $("#celularModal").removeClass("is-invalid");
  $("#invalid-medio").css("display", "none");
  $("#medioModal").removeClass("is-invalid");
  $("#invalid-vendedor").css("display", "none");
  $("#propietarioModalVendedor").removeClass("is-invalid");
  $("#invalid-razon-social").css("display", "none");
  $("#razonSocialModal").removeClass("is-invalid");
  $("#invalid-rfc").css("display", "none");
  $("#rfcModal").removeClass("is-invalid");
  $("#invalid-regimen").css("display", "none");
  $("#regimenModal").removeClass("is-invalid");
  $("#invalid-codigo-postal").css("display", "none");
  $("#codigo_postalModal").removeClass("is-invalid");
  $("#invalid-pais").css("display", "none");
  $("#modalPais").removeClass("is-invalid");
  $("#invalid-estado").css("display", "none");
  $("#estadoModal").removeClass("is-invalid");
});

/* function initSelectMedios() {
  new SlimSelect({
    select: "#campaniaModal",
    placeholder: "Seleccione un medio de campaña",
    searchPlaceholder: "Buscar medios",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
} */

function checkNuevoClienteFacturacion() {
  var algoEstaMal = [];
  if (!$("#nombreContacto").val()) {
    $("#invalid-nombre-contacto").css("display", "block");
    $("#nombreContacto").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-nombre-contacto").css("display", "none");
    $("#nombreContacto").removeClass("is-invalid");
  }

  if (!$("#emailModal").val()) {
    $("#invalid-email").css("display", "block");
    $("#emailModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-email").css("display", "none");
    $("#emailModal").removeClass("is-invalid");
  }

  if (!$("#celularModalNuevo").val()) {
    $("#invalid-celular").css("display", "block");
    $("#celularModalNuevo").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-celular").css("display", "none");
    $("#celularModalNuevo").removeClass("is-invalid");
  }

  if (!$("#medioModal").val() || $("#medioModal").val() === "undefined") {
    $("#invalid-medio").css("display", "block");
    $("#medioModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-medio").css("display", "none");
    $("#medioModal").removeClass("is-invalid");
  }

  if (!$("#empresaModal").val()) {
    $("#invalid-nombre-comercial").css("display", "block");
    $("#empresaModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-nombre-comercial").css("display", "none");
    $("#empresaModal").removeClass("is-invalid");
  }

  if (
    !$("#propietarioModalVendedor").val() ||
    $("#propietarioModalVendedor").val() === "undefined"
  ) {
    $("#invalid-vendedor").css("display", "block");
    $("#propietarioModalVendedor").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-vendedor").css("display", "none");
    $("#propietarioModalVendedor").removeClass("is-invalid");
  }

  if (!$("#razonSocialModal").val()) {
    $("#invalid-razon-social").css("display", "block");
    $("#razonSocialModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-razon-social").css("display", "none");
    $("#razonSocialModal").removeClass("is-invalid");
  }

  if (!$("#rfcModal").val()) {
    $("#invalid-rfc").css("display", "block");
    $("#rfcModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-rfc").css("display", "none");
    $("#rfcModal").removeClass("is-invalid");
  }

  if (!$("#regimenModal").val() || $("#regimenModal").val() === "undefined") {
    $("#invalid-regimen").css("display", "block");
    $("#regimenModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-regimen").css("display", "none");
    $("#regimenModal").removeClass("is-invalid");
  }

  if (!$("#codigo_postalModal").val()) {
    $("#invalid-codigo-postal").css("display", "block");
    $("#codigo_postalModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-codigo-postal").css("display", "none");
    $("#codigo_postalModal").removeClass("is-invalid");
  }

  if (!$("#modalPais").val() || $("#modalPais").val() === "undefined") {
    $("#invalid-pais").css("display", "block");
    $("#modalPais").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-pais").css("display", "none");
    $("#modalPais").removeClass("is-invalid");
  }

  if (!$("#estadoModal").val() || $("#estadoModal").val() === "undefined") {
    $("#invalid-estado").css("display", "block");
    $("#estadoModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-estado").css("display", "none");
    $("#estadoModal").removeClass("is-invalid");
  }

  return algoEstaMal;
}

function checkNuevoClienteNoFacturacion() {
  var algoEstaMal = [];
  if (!$("#nombreContacto").val()) {
    $("#invalid-nombre-contacto").css("display", "block");
    $("#nombreContacto").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-nombre-contacto").css("display", "none");
    $("#nombreContacto").removeClass("is-invalid");
  }

  if (!$("#emailModal").val()) {
    $("#invalid-email").css("display", "block");
    $("#emailModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-email").css("display", "none");
    $("#emailModal").removeClass("is-invalid");
  }

  if (!$("#celularModalNuevo").val()) {
    $("#invalid-celular").css("display", "block");
    $("#celularModalNuevo").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-celular").css("display", "none");
    $("#celularModalNuevo").removeClass("is-invalid");
  }

  if (!$("#medioModal").val() || $("#medioModal").val() === "undefined") {
    $("#invalid-medio").css("display", "block");
    $("#medioModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-medio").css("display", "none");
    $("#medioModal").removeClass("is-invalid");
  }

  if (!$("#razonSocialModal").val()) {
    $("#invalid-razon-social").css("display", "block");
    $("#razonSocialModal").addClass("is-invalid");
    algoEstaMal.push(true);
  } else {
    $("#invalid-razon-social").css("display", "none");
    $("#razonSocialModal").removeClass("is-invalid");
  }
  return algoEstaMal;
}

function addClienteFacturacion() {
  {
    var validCliente = checkNuevoClienteFacturacion();
    if (validCliente.includes(true)) return;

    /* CAMPOS OBLIGATORIOS */
    var contacto_id = $("#contacto_id").val();
    var nombreContacto = $("#nombreContacto").val();
    var emailContacto = $("#emailModal").val();
    var celularContacto = $("#celularModalNuevo").val();
    var medioContacto = $("#medioModal").val();
    var nombreCliente = $("#empresaModal").val();
    var vendedorCliente = $("#propietarioModalVendedor").val();
    var razonSocCliente = $("#razonSocialModal").val();
    var rfcCliente = $("#rfcModal").val();
    var regimenCliente = $("#regimenModal").val();
    var codigoPostalCliente = $("#codigo_postalModal").val();
    var paisCliente = $("#modalPais").val();
    var estadoCliente = $("#estadoModal").val();
    /* CAMPOS NO OBLIGATORIOS */
    var apellidoContacto = $("#apellidoContacto").val();
    var puestoContacto = $("#puestoContacto").val();
    var telefonoContacto = $("#telefonoModal").val();
    var webCliente = $("#sitioWebModal").val();
    var municipioCliente = $("#municipioModal").val();
    var coloniaCliente = $("#coloniaModal").val();
    var calleCliente = $("#calleModal").val();
    var noExteriorCliente = $("#numero_exteriorModal").val();
    var noInteriorCliente = $("#numero_interiorModal").val();

    $.ajax({
      method: "POST",
      dataType: "json",
      url: "app/controladores/ContactoController.php",
      data: {
        accion: "CrearCliente",
        contacto_id: contacto_id,
        nombreContacto: nombreContacto,
        emailContacto: emailContacto,
        celularContacto: celularContacto,
        medioContacto: medioContacto,
        apellidoContacto: apellidoContacto,
        puestoContacto: puestoContacto,
        telefonoContacto: telefonoContacto,
        nombreCliente: nombreCliente,
        vendedorCliente: vendedorCliente,
        razonSocCliente: razonSocCliente,
        rfcCliente: rfcCliente,
        regimenCliente: regimenCliente,
        codigoPostalCliente: codigoPostalCliente,
        paisCliente: paisCliente,
        estadoCliente: estadoCliente,
        webCliente: webCliente,
        municipioCliente: municipioCliente,
        coloniaCliente: coloniaCliente,
        calleCliente: calleCliente,
        noExteriorCliente: noExteriorCliente,
        noInteriorCliente: noInteriorCliente,
      },
      success: function (res) {
        console.log(res);
        if (res.status !== "success") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: res.message,
          });
          return;
        }
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
        $("#tblContactosNuevos").DataTable().ajax.reload();
      },
      error: function (e) {
        console.log(e);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Algo salio mal",
        });
      },
      complete: function () {
        $("#ActivarLead").modal("hide");
      },
    });
  }
}

function addClienteNoFacturacion() {
  var validCliente = checkNuevoClienteNoFacturacion();
  if (validCliente.includes(true)) return;

  /* CAMPOS OBLIGATORIOS */
  var contacto_id = $("#contacto_id").val();
  var nombreContacto = $("#nombreContacto").val();
  var emailContacto = $("#emailModal").val();
  var celularContacto = $("#celularModalNuevo").val();
  var medioContacto = $("#medioModal").val();
  var razonSocCliente = $("#razonSocialModal").val();
  /* CAMPOS NO OBLIGATORIOS */
  var apellidoContacto = $("#apellidoContacto").val();
  var puestoContacto = $("#puestoContacto").val();
  var telefonoContacto = $("#telefonoModal").val();

  $.ajax({
    method: "POST",
    dataType: "json",
    url: "app/controladores/ContactoController.php",
    data: {
      accion: "CrearClienteNoFacturacion",
      contacto_id: contacto_id,
      nombreContacto: nombreContacto,
      emailContacto: emailContacto,
      celularContacto: celularContacto,
      medioContacto: medioContacto,
      apellidoContacto: apellidoContacto,
      puestoContacto: puestoContacto,
      telefonoContacto: telefonoContacto,
      razonSocCliente: razonSocCliente,
    },
    success: function (res) {
      console.log(res);
      if (res.status !== "success") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: res.message,
        });
        return;
      }
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
      $("#tblContactosNuevos").DataTable().ajax.reload();
    },
    error: function (e) {
      console.log(e);
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Algo salio mal",
      });
    },
    complete: function () {
      $("#ActivarLead").modal("hide");
    },
  });
}
