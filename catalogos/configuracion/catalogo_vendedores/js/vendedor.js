function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<img src='../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  $("#tblVendedor").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: "Excel",
      },
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    order: [[0, "desc"]],
    ajax: {
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_sellerTable" },
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "NoVendedor",
      },
      {
        data: "Nombre",
      },
      {
        data: "Estatus",
      },
    ],
    columnDefs: [
      {
        orderable: false,
        targets: 0,
        visible: false,
      },
    ],
    responsive: true,
  });
});

$("#btnAgregarVendedor").click(function () {
  var fkusuario = $("#cmbUsuario").val();
  var badUsuario =
    $("#invalid-usuario").css("display") === "block" ? false : true;
  if (fkusuario && badUsuario) {
    $.ajax({
      url: "functions/agregar_Vendedor.php",
      type: "POST",
      data: {
        fkusuario: fkusuario,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#agregar_Vendedor").modal("toggle");
          $("#agregarVendedor").trigger("reset");
          $("#tblVendedor").DataTable().ajax.reload();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../../img/timdesk/checkmark.svg",
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
            img: "../../../img/timdesk/warning_circle.svg",
            img: null,
            msg: "Ocurrió un error al agregar",
          });
        }
      },
    });
  } else {
    if (!$("#cmbUsuario").val()) {
      $("#invalid-usuario").css("display", "block");
      $("#cmbUsuario").addClass("is-invalid");
    }
  }
});

$("#btnEditarVendedor").click(function () {
  var id = $("#idVendedorU").val();
  var fkvendedor = $("#txtVendedorU").val().trim();
  var estatus = $("#activeVendedor").prop("checked") ? 1 : 2;
  console.log(id, fkvendedor, estatus);
  var data = {};
  var badNombreVendedorEd =
    $("#invalid-usuarioEdit").css("display") === "block" ? false : true;
  if (fkvendedor == 0) {
    data = {
      idVendedorU: id,
      txtVendedorU: id,
      cmbEstatusVendedor: estatus,
    };
  } else {
    data = {
      idVendedorU: id,
      txtVendedorU: fkvendedor,
      cmbEstatusVendedor: estatus,
    };
  }
  if (badNombreVendedorEd) {
    $.ajax({
      url: "functions/editar_Vendedor.php",
      type: "POST",
      data,
      success: function (data, status, xhr) {
        if (data.trim() == "1") {
          $("#modalEditar").modal("toggle");
          $("#editarVendedorU").trigger("reset");
          $("#tblVendedor").DataTable().ajax.reload();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../../img/timdesk/checkmark.svg",
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
            img: "../../../img/timdesk/warning_circle.svg",
            msg: "Ocurrió un error al editar",
          });
        }
      },
    });
  }
});

function obtenerIdVendedorEditar(id) {
  document.getElementById("idVendedorU").value = id;
  document.getElementById("idVendedorD").value = id;
  var id = "id=" + id;
  $.ajax({
    type: "POST",
    url: "functions/getVendedorEditar.php",
    data: id,
    success: function (r) {
      var datos = JSON.parse(r);
      $("#txtNombreVU").val(datos.html);
      $("#txtVendedorU").html(datos.lista);
      $("#idFkVendedorU").val(datos.fkusuario);
      if (datos.listaEstatus == 1) {
        console.log("Es Activo");
        $("#activeVendedor").prop("checked", true);
      }
      var idVendedor = $("#idVendedorU").val();
      if (idVendedor != "") {
        validarExisteRelacion(idVendedor);
      }
    },
  });
}

function eliminarVendedor(id) {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons
    .fire({
      title: "¿Desea eliminar el registro del  vendedor?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar vendedor</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "functions/eliminar_Vendedor.php",
          type: "POST",
          data: {
            idVendedorD: id,
          },
          success: function (data, status, xhr) {
            if (data == "1") {
              $("#modalEditar").modal("toggle");
              $("#tblVendedor").DataTable().ajax.reload();
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                img: "../../../img/chat/notificacion_error.svg",
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
                img: "../../../img/timdesk/warning_circle.svg",
                msg: "Ocurrió un error al eliminar",
              });
            }
          },
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

function cambiarColor() {
  if ($("#cmbEstatusVendedor").val() == 1) {
    console.log("Es Activo");
    $("#cmbEstatusVendedor").css({
      "background-color": "#28c67a",
      color: "#FFFFFF",
    });
  } else {
    console.log("Es Inactivo");
    $("#cmbEstatusVendedor").css({ "background-color": "#cac8c6" });
  }
}

function validarExixteVendedor() {
  var valor = $("#cmbUsuario").val();
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "validar_existeVendedor", data: valor },
    dataType: "json",

    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese usuario*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-usuario").text("El vendedor ya existe en el registro.");
        $("#invalid-usuario").css("display", "block");
        $("#cmbUsuario").addClass("is-invalid");
      } else {
        $("#invalid-usuario").text("El vendedor debe tener un usuario.");
        $("#invalid-usuario").css("display", "none");
        $("#cmbUsuario").removeClass("is-invalid");
      }
    },
  });
}

function validarExisteRelacion(valor) {
  //document.getElementById('idVendedorD').disabled = true;
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionVendedor",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      /* Validar si ya existe relacion con clientes*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion con cliente", data);
        $("#txtVendedorU").prop("disabled", true);
        $("#activeVendedor").prop("disabled", true);
        var eliminar = document.getElementById("idVendedorD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarVendedor");
        modificar.style.display = "none";
        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");
      } else {
        $("#txtVendedorU").prop("disabled", false);
        $("#activeVendedor").prop("disabled", false);
        var eliminar = document.getElementById("idVendedorD");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditarVendedor");
        modificar.style.display = "block";
        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");
      }
    },
  });
}
function nuevoVendedor() {
  var id = $("#txtVendedorU").val();
  //alert(id);
  $.ajax({
    type: "POST",
    url: "functions/getNuevoNombreVendedor.php",
    data: { id: id },
    success: function (r) {
      var datos = JSON.parse(r);
      $("#idNuevoNombreU").val(datos.html);

      var fkVendedor = $("#idNuevoNombreU").val();
      if (fkVendedor != "") {
        validarExixteVendedorNuevo(fkVendedor);
      }
    },
  });
}
function validarExixteVendedorNuevo(valor) {
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "validar_existeVendedor", data: valor },
    dataType: "json",

    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese usuario*/
      if (parseInt(data[0]["existe"]) == 1) {
        /* var modificar = document.getElementById("btnEditarVendedor");
        modificar.style.display = "none"; */
        $("#invalid-usuarioEdit").text("El vendedor ya existe en el sistema.");
        $("#invalid-usuarioEdit").css("display", "block");
        $("#txtVendedorU").addClass("is-invalid");
      } else {
        /* var agregar = document.getElementById("btnEditarVendedor");
        agregar.style.display = "block"; */
        $("#invalid-usuarioEdit").text("El vendedor debe tener un nombre.");
        $("#invalid-usuarioEdit").css("display", "none");
        $("#txtVendedorU").removeClass("is-invalid");
      }
    },
  });
}
