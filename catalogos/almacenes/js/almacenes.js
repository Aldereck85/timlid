$(document).ready(function () {
  loadAlertsNoti(
    "alertaTareas",
    $("#txtUsuario").val(),
    $("#txtRuta").val(),
    $("#txtEdit").val()
  );
  setInterval(
    loadAlertsNoti,
    10000,
    "alertaTareas",
    $("#txtUsuario").val(),
    $("#txtRuta").val(),
    $("#txtEdit").val()
  );

  $("#tblAlmacenes").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: "Excel",
      },
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_warehouseTable" },
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "Almacen",
      },
      {
        data: "Domicilio",
      },
      {
        data: "Colonia",
      },
      {
        data: "Ciudad",
      },
      {
        data: "Estado",
      },
      {
        data: "Pais",
      },
    ],
  });
});

$(document).on("click", "#btn-almacenes", function () {
  loadCombo(146, "cmbPais", "pais", "", "countries");
  $("#agregar_Almacen").modal("toggle");

  $("#cmbPais").on("change", function () {
    loadCombo("", "cmbEstados", "estado", $(this).val(), "states");
  });
  loadCombo("", "cmbEstados", "estado", 146, "states");
});

$(document).on("click", "#btnAgregarAlmacen", function () {
  var nombreAlmacen = $("#txtarea").val().trim();
  var calle = $("#txtarea2").val();
  var numExterior = $("#txtarea3").val();
  var prefijo = $("#txtarea9").val();
  var numInterior = $("#txtarea4").val();
  var colonia = $("#txtarea5").val();
  var ciudad = $("#txtarea7").val();
  var estado = $("#cmbEstados").val();
  var pais = $("#cmbPais").val();
  //var telefono = $("#txtarea10").val();
  var contPais = 0;
  var contEstado = 0;

  if (nombreAlmacen.length < 1) {
    $("#txtarea")[0].reportValidity();
    $("#txtarea")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (calle.length < 1) {
    $("#txtarea2")[0].reportValidity();
    $("#txtarea2")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (numExterior.length < 1) {
    $("#txtarea3")[0].reportValidity();
    $("#txtarea3")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (colonia.length < 1) {
    $("#txtarea5")[0].reportValidity();
    $("#txtarea5")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (ciudad.length < 1) {
    $("#txtarea7")[0].reportValidity();
    $("#txtarea7")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (estado == "Estado") {
    if (contPais == 0) {
      $("#cmbEstados")[0].reportValidity();
      $("#cmbEstados")[0].setCustomValidity("Selecciona un estado.");
    }
    contPais = 1;
    $("#cmbEstados")[0].reportValidity();
    $("#cmbEstados")[0].setCustomValidity("Selecciona un estado.");
    return;
  }

  if (pais == "Elegir") {
    if (contEstado == 0) {
      $("#cmbPais")[0].reportValidity();
      $("#cmbPais")[0].setCustomValidity("Selecciona un país.");
    }
    contEstado = 1;

    $("#cmbPais")[0].reportValidity();
    $("#cmbPais")[0].setCustomValidity("Selecciona un país.");
    return;
  }

  $.ajax({
    url: "functions/agregar_almacen.php",
    type: "POST",
    data: {
      txtAlmacen: nombreAlmacen,
      txtCalle: calle,
      txtNe: numExterior,
      prefijo: prefijo,
      txtNi: numInterior,
      txtColonia: colonia,
      txtCiudad: ciudad,
      cmbEstados: estado,
      cmbPais: pais,
    },
    success: function (data, status, xhr) {
      if (data.trim() == "exito") {
        $("#agregar_Almacen").modal("toggle");
        $("#agregarAlmacen").trigger("reset");
        $("#tblAlmacenes").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          //img: '<i class="fas fa-check-circle"></i>',
          img: "../../img/timdesk/checkmark.svg",
          msg: "¡Registro agregado!",
        });
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          img: null,
          msg: "Ocurrió un error al agregar",
        });
      }
    },
  });
});

$(document).on("click", "#btnEditarAlmacen", function () {
  var id = $("#idAlmacenU").val();
  var nombreAlmacen = $("#txtAlmacenU").val().trim();
  var calle = $("#txtCalleU").val();
  var numExterior = $("#txtNeU").val();
  var prefijo = $("#txtarea9u").val();
  var numInterior = $("#txtarea4u").val();
  var colonia = $("#txtarea5u").val();
  var ciudad = $("#txtarea7u").val();
  var estado = $("#cmbEstadosU").val();
  var pais = $("#cmbPaisU").val();
  //var telefono = $("#txtarea10u").val();
  var contPais = 0;
  var contEstado = 0;

  if (nombreAlmacen.length < 1) {
    $("#txtAlmacenU")[0].reportValidity();
    $("#txtAlmacenU")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (calle.length < 1) {
    $("#txtCalleU")[0].reportValidity();
    $("#txtCalleU")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (numExterior.length < 1) {
    $("#txtNeU")[0].reportValidity();
    $("#txtNeU")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (colonia.length < 1) {
    $("#txtarea5u")[0].reportValidity();
    $("#txtarea5u")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (ciudad.length < 1) {
    $("#txtarea7u")[0].reportValidity();
    $("#txtarea7u")[0].setCustomValidity("Completa este campo.");
    return;
  }

  if (estado == "Estado") {
    if (contPais == 0) {
      $("#cmbEstadosU")[0].reportValidity();
      $("#cmbEstadosU")[0].setCustomValidity("Selecciona un estado.");
    }
    contPais = 1;
    $("#cmbEstadosU")[0].reportValidity();
    $("#cmbEstadosU")[0].setCustomValidity("Selecciona un estado.");
    return;
  }

  if (pais == "Elegir") {
    if (contEstado == 0) {
      $("#cmbPaisU")[0].reportValidity();
      $("#cmbPaisU")[0].setCustomValidity("Selecciona un país.");
    }
    contEstado = 1;

    $("#cmbPaisU")[0].reportValidity();
    $("#cmbPaisU")[0].setCustomValidity("Selecciona un país.");
    return;
  }

  $.ajax({
    url: "functions/editar_almacen.php",
    type: "POST",
    data: {
      idAlmacenU: id,
      txtAlmacenU: nombreAlmacen,
      txtCalleU: calle,
      txtNeU: numExterior,
      prefijo: prefijo,
      txtNiU: numInterior,
      txtColoniaU: colonia,
      txtCiudadU: ciudad,
      cmbEstadosU: estado,
      cmbPaisU: pais,
    },
    success: function (data, status, xhr) {
      if (data.trim() == "exito") {
        $("#modalEditar").modal("toggle");
        $("#editarAlmacenU").trigger("reset");
        $("#tblAlmacenes").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "¡Registro modificado!",
        });
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Ocurrió un error al editar",
        });
      }
    },
  });

  $("#");
});

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<img src='../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

function loadCombo(data, input, name, value, fun) {
  var html =
    '<option value="0" disabled selected hidden>Seleccione un ' +
    name +
    "...</option>";

  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_" + fun + "Combo", value: value },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta "+name+" combo:",respuesta);
      //console.log("count combo"+name,respuesta.length);
      if (respuesta !== "" && respuesta !== null && respuesta.length > 0) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKData) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKData +
            '" ' +
            selected +
            ">" +
            respuesta[i].Data +
            "</option>";
          if (respuesta[i].Oculto !== "") {
            oculto = respuesta[i].Oculto;
          }
        });
      } else {
        html +=
          '<option value="vacio">No hay ' + name + " que mostrar</option>";
      }

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function obtenerIdAlmacenEditar(id) {
  document.getElementById("idAlmacenU").value = id;
  document.getElementById("idAlmacenD").value = id;
  console.log(document.getElementById("idAlmacenU").value);
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_warehouseEdit", value: id },
    dataType: "json",
    success: function (r) {
      $("#txtAlmacenU").val(r[0].Almacen);
      $("#txtCalleU").val(r[0].Ciudad);
      $("#txtNeU").val(r[0].Exterior);
      $("#txtarea9u").val(r[0].Prefijo);
      $("#txtarea4u").val(r[0].Interior);
      $("#txtarea5u").val(r[0].Colonia);
      $("#txtarea7u").val(r[0].Ciudad);
      loadCombo(r[0].FKPais, "cmbPaisU", "pais", "", "countries");
      loadCombo(r[0].FKEstado, "cmbEstadosU", "estado", r[0].FKPais, "states");

      $("#cmbPaisU").on("change", function () {
        loadCombo("", "cmbEstadosU", "estado", $(this).val(), "states");
      });
    },
  });
}

function eliminarAlmacen(id) {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn",
      cancelButton: "btn",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons
    .fire({
      title: "¿Desea eliminar el registro de este almacén?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar almacén</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "functions/eliminar_almacen.php",
          type: "POST",
          data: {
            idAlmacenD: id,
          },
          success: function (data, status, xhr) {
            if (data == "exito") {
              $("#modalEditar").modal("toggle");
              $("#tblAlmacenes").DataTable().ajax.reload();
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                img: "../../img/chat/notificacion_error.svg",
                msg: "¡Registro eliminado!",
              });
            } else {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/warning_circle.svg",
                msg: "Ocurrió un error al eliminar",
              });
            }
          },
        });
      }
    });
}
