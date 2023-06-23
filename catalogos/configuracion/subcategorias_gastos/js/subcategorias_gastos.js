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
  $("#tblSubcategoriaGastos").dataTable({
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
      data: { clase: "get_data", funcion: "get_subcategoryTable" },
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "NoSubcategoria",
      },
      {
        data: "Nombre",
      },
      {
        data: "Categoria",
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

  cargarCMBCategoriasG("", "cmbCategoria");
  //CargarSlimSelect();
});

function CargarSlimSelect() {
  new SlimSelect({
    select: "#cmbCategoria",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar categoría...",
  });
}
function cargarCMBCategoriasG(data, input) {
  var idemp = $("#emp_id").val();
  var html = "";
  var selected;
  $.ajax({
    url: "subcategorias_gastos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_categorias_g", data: idemp },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta c: ", respuesta);
      html += "<option data-placeholder='true'></option>";

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKCategoria) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKCategoria +
          '" ' +
          selected +
          ">" +
          respuesta[i].Nombre +
          "</option>";
      });

      /*html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar marcas</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$("#btnAgregarSubcategoria").click(function () {
  if ($("#agregarSubcategoria")[0].checkValidity()) {
    var badNombreSubCat =
      $("#invalid-nombreSubCat").css("display") === "block" ? false : true;
    var badNombreCat =
      $("#invalid-nombreCat").css("display") === "block" ? false : true;
    if (badNombreSubCat && badNombreCat) {
      var nombreCategoria = $("#txtNombreSubcategoria").val();
      var fkcategoria = $("#cmbCategoria").val();
      $.ajax({
        url: "subcategorias_gastos/functions/agregar_Subcategoria.php",
        type: "POST",
        data: {
          nombreSubcategoria: nombreCategoria,
          fkcategoria: fkcategoria,
        },
        success: function (data, status, xhr) {
          if (data.trim() == "exito") {
            $("#agregar_SubcategoriaGastos").modal("toggle");
            $("#agregarSubcategoria").trigger("reset");
            $("#tblSubcategoriaGastos").DataTable().ajax.reload();
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
    if (!$("#txtNombreSubcategoria").val()) {
      $("#invalid-nombreSubCat").css("display", "block");
      $("#txtNombreSubcategoria").addClass("is-invalid");
    }
    if (!$("#cmbCategoria").val()) {
      $("#invalid-nombreCat").css("display", "block");
      $("#cmbCategoria").addClass("is-invalid");
    }
  }
});

$("#btnEditarSubcategoria").click(function () {
  var id = $("#idSubcategoriaU").val();
  var nombreSubcategoria = $("#txtNombreU").val().trim();
  var fksubcategoria = $("#txtCategoriaU").val().trim();

  if (nombreSubcategoria && parseInt(fksubcategoria) > 1) {
    var badNombreSubCatEdit =
      $("#invalid-nombreSubCatEdit").css("display") === "block" ? false : true;
    var badNombreCatEdit =
      $("#invalid-nombreCatEdit").css("display") === "block" ? false : true;
    if (badNombreSubCatEdit && badNombreCatEdit) {
      $.ajax({
        url: "subcategorias_gastos/functions/editar_Subcategoria.php",
        type: "POST",
        data: {
          idSubcategoriaU: id,
          txtNombreU: nombreSubcategoria,
          txtCategoriaU: fksubcategoria,
        },
        success: function (data, status, xhr) {
          if (data.trim() == "1") {
            $("#modalEditar").modal("toggle");
            $("#editarSubcategoriaU").trigger("reset");
            $("#tblSubcategoriaGastos").DataTable().ajax.reload();
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
    if (!nombreSubcategoria) {
      $("#invalid-nombreSubCatEdit").css("display", "block");
      $("#txtNombreU").addClass("is-invalid");
    }
    if (parseInt(fksubcategoria) <= 1) {
      $("#invalid-nombreCatEdit").css("display", "block");
      $("#txtCategoriaU").addClass("is-invalid");
    }
  }
});

function obtenerIdSubcategoriaEditar(id) {
  $("#txtCategoriaU").prop("disabled", true);

  document.getElementById("idSubcategoriaU").value = id;
  document.getElementById("idSubcategoriaD").value = id;
  var id = "id=" + id;
  $.ajax({
    type: "POST",
    url: "functions/getSubcategoriaEditar.php",
    data: id,
    success: function (r) {
      var datos = JSON.parse(r);
      $("#txtNombreU").val(datos.html);
      $("#txtFKCategoriaU").val(datos.html2);
      $("#txtCategoriaU").html(datos.lista);

      var idSubcategoria = $("#idSubcategoriaU").val();
      if (idSubcategoria != "") {
        validarExisteRelacionSubCatGasto(idSubcategoria);
        //validarUnicaSubCatGastoU();
      } else {
      }
    },
  });
}

function eliminarSubcategoria(id) {
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
      title: "¿Desea eliminar el registro de esta subcategoría?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar subcategoría</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "functions/eliminar_Subcategoria.php",
          type: "POST",
          data: {
            idSubcategoriaD: id,
          },
          success: function (data, status, xhr) {
            if (data == "1") {
              $("#modalEditar").modal("toggle");
              $("#tblSubcategoriaGastos").DataTable().ajax.reload();
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

function validarUnicaSubCatGasto() {
  var valor = document.getElementById("txtNombreSubcategoria").value;
  $.ajax({
    url: "subcategorias_gastos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_subcategoriaGasto",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreSubCat").text(
          "La subcategoría de gastos ya existe en el sistema."
        );
        $("#invalid-nombreSubCat").css("display", "block");
        $("#txtNombreSubcategoria").addClass("is-invalid");
      } else {
        $("#invalid-nombreSubCat").text(
          "La subcategoría de gastos debe tener un nombre."
        );
        $("#invalid-nombreSubCat").css("display", "none");
        $("#txtNombreSubcategoria").removeClass("is-invalid");
      }
    },
  });
}

function validarExisteRelacionSubCatGasto(valor) {
  $.ajax({
    url: "subcategorias_gastos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionSubCatGasto",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      /* Validar si ya existe relacion con clientes*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#txtNombreU").prop("disabled", true);
        $("#txtCategoriaU").prop("disabled", true);

        var eliminar = document.getElementById("idSubcategoriaD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarSubcategoria");
        modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");
      } else {
        $("#txtNombreU").prop("disabled", false);
        $("#txtCategoriaU").prop("disabled", false);

        var eliminar = document.getElementById("idSubcategoriaD");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditarSubcategoria");
        modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");
      }
    },
  });
}

function validarUnicaSubCatGastoU() {
  var valor = document.getElementById("txtNombreU").value;
  var valor2 = document.getElementById("txtFKCategoriaU").value;
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_subcategoriaGastoU",
      data: valor,
      data2: valor2,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        /* var agregar = document.getElementById("btnEditarSubcategoria");
        agregar.style.display = "none"; */
        $("#invalid-nombreSubCatEdit").text(
          "El gasto ya esta registrado en el sistema."
        );
        $("#invalid-nombreSubCatEdit").css("display", "block");
        $("#txtNombreU").addClass("is-invalid");
      } else {
        /* var agregar = document.getElementById("btnEditarSubcategoria");
        agregar.style.display = "block"; */

        $("#invalid-nombreSubCatEdit").text(
          "El gasto ya esta registrado en el sistema."
        );
        $("#invalid-nombreSubCatEdit").css("display", "none");
        $("#txtNombreU").removeClass("is-invalid");
      }
    },
  });
}

function verCategoria() {
  var fkcategoria = $("#txtCategoriaU").val();
  $("#txtFKCategoriaU").val(fkcategoria);
  validarUnicaSubCatGastoU();
}

function validEmptyInput(item, invalid = null) {
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  if (invalid) {
    invalidDiv = document.getElementById(invalid);
  } else {
    for (let i = 0; i < parent.children.length; i++) {
      if (parent.children[i].classList.contains("invalid-feedback")) {
        invalidDiv = parent.children[i];
        break;
      }
    }
  }
  if (!val) {
    item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
}
