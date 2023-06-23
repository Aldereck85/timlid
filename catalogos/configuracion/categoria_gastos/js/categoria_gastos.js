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
  var idemp = $("#emp_id").val();
  $("#tblCategoriaGastos").dataTable({
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
      data: { clase: "get_data", funcion: "get_categoryTable", data: idemp },
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "NoCategoria",
      },
      {
        data: "Nombre",
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

$("#btnAgregarCategoriaGastos").click(function () {
  var idemp = $("#emp_id").val();
console.log("hola");
  if ($("#agregarCategoria")[0].checkValidity()) {
    var badNombreCat =
      $("#invalid-nombreGasto").css("display") === "block" ? false : true;
    if (badNombreCat) {
      var nombreCategoria = $("#txtNombreCategoria").val();
      $("#txtNombreCategoria").prop("required", true);
      if (nombreCategoria.length < 1) {
        $("#txtNombreCategoria")[0].reportValidity();
        $("#txtNombreCategoria")[0].setCustomValidity("Completa este campo.");
        return;
      }

      $.ajax({
        url: "functions/agregar_Categoria.php",
        type: "POST",
        data: {
          nombreCategoria: nombreCategoria,
          idemp: idemp,
        },
        success: function (data, status, xhr) {
          if (data.trim() == "exito") {
            $("#agregar_CategoriaGastos").modal("toggle");
            $("#agregarCategoria").trigger("reset");
            $("#tblCategoriaGastos").DataTable().ajax.reload();
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
    }
  } else {
    if (!$("#txtNombreCategoria").val()) {
      $("#invalid-nombreGasto").css("display", "block");
      $("#txtNombreCategoria").addClass("is-invalid");
    }
  }
});

$("#btnEditar_CategoriaGastos_47").click(function () {
  var id = $("#idCategoriaU").val();
  var nombreEstatus = $("#txtNombreU").val().trim();
  if ($("#editarCategoriaU")[0].checkValidity()) {
    var badNombreCatEdit =
      $("#invalid-nombreGastoEdit").css("display") === "block" ? false : true;
    if (badNombreCatEdit) {
      if (nombreEstatus.length < 1) {
        return;
      }
      $.ajax({
        url: "functions/editar_Categoria.php",
        type: "POST",
        data: {
          idCategoriaU: id,
          txtNombreU: nombreEstatus,
        },
        success: function (data, status, xhr) {
          if (data.trim() == "1") {
            $("#modalEditar").modal("toggle");
            $("#editarCategoriaU").trigger("reset");
            $("#tblCategoriaGastos").DataTable().ajax.reload();
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
  } else {
    if (!$("#txtNombreU").val()) {
      $("#invalid-nombreGastoEdit").css("display", "block");
      $("#txtNombreU").addClass("is-invalid");
    }
  }
});

function obtenerIdCategoriaGastosEditar(id) {
  document.getElementById("idCategoriaU").value = id;
  document.getElementById("idCategoriaD").value = id;
  var id = "id=" + id;
  $.ajax({
    type: "POST",
    url: "functions/getCategoriaEditar.php",
    data: id,
    success: function (r) {
      var datos = JSON.parse(r);
      $("#txtUpdateCategoriaGastos_47").val(datos.html);

      var idCategoria = $("#idCategoriaU").val();
      if (idCategoria != "") {
        validarExisteRelacionCatGasto(idCategoria);
      }
    },
  });
}

function eliminarCategoria(id) {
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
      title:
        "¿Desea eliminar el registro de esta categoría y sus subcategorías relacionadas?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar categoría</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "functions/eliminar_Categoria.php",
          type: "POST",
          data: {
            idEstatusD: id,
          },
          success: function (data, status, xhr) {
            if (data == "1") {
              $("#modalEditar").modal("toggle");
              $("#tblCategoriaGastos").DataTable().ajax.reload();
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

function validarUnicaCategoriaGasto() {
  var valor = document.getElementById("txtNombreCategoria").value;
  console.log("Valor categ:  " + valor);
  $.ajax({
    url: "categoria_gastos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_categoriaGasto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreGasto").text(
          "El gasto ya esta registrado en el sistema."
        );
        $("#invalid-nombreGasto").css("display", "block");
        $("#txtNombreCategoria").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreGasto").text("El gasto debe tener un nombre.");
        $("#invalid-nombreGasto").css("display", "none");
        $("#txtNombreCategoria").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarUnicaCategoriaGastoU() {
  var valor = document.getElementById("txtNombreU").value;
  console.log("Valor marca:  " + valor);
  $.ajax({
    url: "categoria_gastos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_categoriaGasto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreGastoEdit").text(
          "El gasto ya esta registrado en el sistema."
        );
        $("#invalid-nombreGastoEdit").css("display", "block");
        $("#txtNombreU").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreGastoEdit").text("El gasto debe tener un nombre.");
        $("#invalid-nombreGastoEdit").css("display", "none");
        $("#txtNombreU").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarExisteRelacionCatGasto(valor) {
  $.ajax({
    url: "categoria_gatos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionCatGasto",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      /* Validar si ya existe relacion con clientes*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion con cliente", data);

        $("#txtNombreU").prop("disabled", true);
        //$("#btnEditarCategoria").prop('disabled', true);

        var eliminar = document.getElementById("idCategoriaD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarCategoria");
        modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");
      } else {
        $("#txtNombreU").prop("disabled", false);
        //$("#btnEditarCategoria").prop('disabled', false);

        var eliminar = document.getElementById("idCategoriaD");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditarCategoria");
        modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");
      }
    },
  });
}

/* Reiniciar el modal al cerrarlo */
$("#agregar_CategoriaGastos").on("hidden.bs.modal", function (e) {
  $("#invalid-nombreGasto").css("display", "none");
  $("#txtNombreCategoria").removeClass("is-invalid");
  $("#txtNombreCategoria").val("");
});

$("#modalEditar").on("hidden.bs.modal", function (e) {
  $("#invalid-nombreGastoEdit").css("display", "none");
  $("#txtNombreU").removeClass("is-invalid");
  $("#txtNombreU").val("");
});
