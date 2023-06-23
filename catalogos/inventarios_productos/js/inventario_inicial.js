$(document).ready(function () {
  var categorias = new SlimSelect({
    select: "#cmbCategorias",
    placeholder: "Seleccionar categorias",
    deselectLabel: '<span class="">✖</span>',
  });
  validarCMBCategorias(categorias);
  cargarCMBSucursal();
  verificarCambioCMBSucursales();

  $("#btnCategorias").prop("disabled", true);
  $("#btnGuardar").prop("disabled", true);
  $("#btnFinalizar").prop("disabled", true);
  $("#btnGuardarFot").prop("disabled", true);
  $("#btnFinalizarFot").prop("disabled", true);
  $("#btnGuardar").on("click", botonGuardar);
  $("#btnGuardarFot").on("click", botonGuardar);
  $("#btnFinalizar").on("click", finalizarInventario);
  $("#btnFinalizarFot").on("click", finalizarInventario);
  $("#importExcel").prop("disabled", true);

  $("#dataexcel").on("change", () => {
    if (!$("#dataexcel").val()) {
      $("#importExcel").prop("disabled", true);
    } else {
      $("#importExcel").prop("disabled", false);
    }
  });

  $("#cmbSucursales").on("change", () => {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_TempInitialStock",
        data: $("#cmbSucursales").val(),
      },
      dataType: "json",
      async: false,
      success: function (respuesta) {
        console.log(
          "respuesta de guardar el detalle temporal del inventario inicial: ",
          respuesta
        );
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
});

function validarExcel() {
  $("#loaderValidacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("sucursal", $("#cmbSucursales").val());
  console.log(formData.get("dataexcel"));
  console.log(formData.get("sucursal"));
  $.ajax({
    url: "validarExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta.status == "fail") {
        $("#dataexcel").val("");
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>No se pudo subir el archivo</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.close();
          }
        });
      }
    },
    error: function (error) {
      console.log(error.responseText);
      if (error.responseText.includes("Formato no aceptado") == true) {
        $("#dataexcel").val("");
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>Formato no aceptado</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.close();
          }
        });
      } else if (
        error.responseText != "" &&
        error.responseText.includes("Formato incorrecto") == false &&
        error.responseText.includes("Formato no aceptado") == false
      ) {
        $("#dataexcel").val("");
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: error.responseText,
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.close();
          }
        });
      } else if (error.responseText.includes("Formato incorrecto") == true) {
        $("#dataexcel").val("");
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>Formato incorrecto</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.close();
          }
        });
      } else {
        $("#loaderValidacion").css("display", "none");
        importExcel();
      }
    },
  });
}

function importExcel() {
  $("#loaderImportacion").css("display", "block");

  var sucursal = $("#cmbSucursales").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_headerInitialStock",
      data: sucursal,
    },
    dataType: "json",
    async: false,
    success: function (respuesta) {
      console.log(
        "respuesta de guardar la cabecera del  inventario inicial con archivo de excel: ",
        respuesta
      );
    },
    error: function (error) {
      console.log(error);
    },
  });

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("sucursal", $("#cmbSucursales").val());
  console.log(formData.get("dataexcel"));
  console.log(formData.get("sucursal"));
  $.ajax({
    url: "upload.php",
    type: "POST",
    data: formData,
    dataType: "json",
    async: false,
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      $("#tblInventariosIniciales").DataTable().ajax.reload();
      $("#dataexcel").val("");
    },
    error: function (error) {
      console.log(error);
      $("#loaderImportacion").css("display", "none");

      Swal.fire({
        title: "Datos importados",
        icon: "success",
        showCancelButton: false,
        confirmButtonText: "Aceptar",
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
        },
        buttonsStyling: false,
        allowEnterKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.close();
        }
      });
      $("#tblInventariosIniciales").DataTable().ajax.reload();
      $("#dataexcel").val("");
    },
  });
}

function validarRepeticion(item) {
  var sucursal = $("#cmbSucursales").val();
  var id = $(item).attr("id").split("_")[1];
  var campo = $(item).attr("id").split("_")[0];
  var identificador = $(item).attr("id").split("_")[1];

  if (!$("#idDetalle_" + identificador).val()) {
    var idDetalle = 0;
  } else {
    var idDetalle = $("#idDetalle_" + identificador).val();
  }
  var clave = $("#clave_" + identificador).val();
  if (campo == "lot") {
    var lote = $(item).val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_validation",
        data: sucursal,
        data2: idDetalle,
        data3: clave,
        data4: lote,
        data5: "lote",
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta de la validacion: ", respuesta);
        if (respuesta[0].mensaje != null) {
          $("#invalid_campo_rep_" + campo + "_" + id).remove();
          $(
            "<div class='invalid-feedback d-block' id='invalid_campo_rep_" +
              campo +
              "_" +
              id +
              "'>" +
              respuesta[0].mensaje +
              "</div>"
          ).insertAfter($(item));
        } else {
          $("#invalid_campo_rep_" + campo + "_" + id).remove();
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    var serie = $(item).val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_validation",
        data: sucursal,
        data2: idDetalle,
        data3: clave,
        data4: serie,
        data5: "serie",
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta de la validacion: ", respuesta[0]);
        if (respuesta[0].mensaje != null) {
          $("#invalid_campo_rep_" + campo + "_" + id).remove();
          $(
            "<div class='invalid-feedback d-block' id='invalid_campo_rep_" +
              campo +
              "_" +
              id +
              "'>" +
              respuesta[0].mensaje +
              "</div>"
          ).insertAfter($(item));
        } else {
          $("#invalid_campo_rep_" + campo + "_" + id).remove();
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}

function botonGuardar() {
  var sucursal = $("#cmbSucursales").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_ValidacionProductosIncompletos",
      data: sucursal,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de la validacion: ", respuesta);
      if (respuesta == 1) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 1000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡No dejes productos incompletos!",
          sound: "../../../../../sounds/sound4",
        });
      } else {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 1000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Productos guardados.!",
          sound: "../../../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function inserPrevio(item) {
  validarRepeticion(item);

  var sucursal = $("#cmbSucursales").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_headerInitialStock",
      data: sucursal,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta de guardar la cabecera del inventario inicial: ",
        respuesta
      );
    },
    error: function (error) {
      console.log(error);
    },
  });

  var campo = $(item).attr("id").split("_")[0];
  var identificador = $(item).attr("id").split("_")[1];

  if (!$("#idDetalle_" + identificador).val()) {
    var idDetalle = 0;
  } else {
    var idDetalle = $("#idDetalle_" + identificador).val();
  }
  var id = $("#id_" + identificador).val();
  var clave = $("#clave_" + identificador).val();

  switch (campo) {
    case "cant":
      var cantidad = $(item).val();
      var cantidad2 = cantidad.toString();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_detailInitialStock2",
          data: sucursal,
          data2: idDetalle,
          data3: id,
          data4: clave,
          data5: cantidad2,
          data6: "cantidad",
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta de guardar el detalle del inventario inicial: ",
            respuesta
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
      validarCantidad(item);
      break;
    case "lot":
      var lote = $(item).val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_detailInitialStock2",
          data: sucursal,
          data2: idDetalle,
          data3: id,
          data4: clave,
          data5: lote,
          data6: "lote",
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta de guardar el detalle del inventario inicial: ",
            respuesta
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
      validarCampo(item);
      break;
    case "ser":
      var serie = $(item).val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_detailInitialStock2",
          data: sucursal,
          data2: idDetalle,
          data3: id,
          data4: clave,
          data5: serie,
          data6: "serie",
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta de guardar el detalle del inventario inicial: ",
            respuesta
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
      validarCampo(item);
      break;
    case "fech":
      var caducidad = $(item).val();
      var caducidad2 = caducidad.toString();

      if (!$(item).val() || $(item).val() == "") {
        var caducidad2 = "0000-00-00";
      }

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_detailInitialStock2",
          data: sucursal,
          data2: idDetalle,
          data3: id,
          data4: clave,
          data5: caducidad2,
          data6: "caducidad",
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(
            "respuesta de guardar el detalle del inventario inicial: ",
            respuesta
          );
        },
        error: function (error) {
          console.log(error);
        },
      });
      validarCampo(item);
      break;
  }
}

function finalizarInventario() {
  if (!$("div").hasClass("invalid-feedback")) {
    var sucursal = $("#cmbSucursales").val();
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_headerInitialStock",
        data: sucursal,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(
          "respuesta de guardar la cabecera del inventario inicial: ",
          respuesta
        );
      },
      error: function (error) {
        console.log(error);
      },
    });

    Lobibox.notify("success", {
      size: "mini",
      rounded: true,
      delay: 1000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "¡Productos guardados.!",
      sound: "../../../../../sounds/sound4",
    });

    Swal.fire({
      title: "Finalizar inventario",
      html: "¿Desea finalizar el inventario?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Aceptar",
      cancelButtonText: "Cancelar",
      reverseButtons: true,
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--blue",
        cancelButton: "btn-custom btn-custom--border-blue",
      },
      buttonsStyling: false,
      allowEnterKey: false,
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_InitialStock",
            data: sucursal,
          },
          dataType: "json",
          success: function (respuesta) {
            console.log("respuesta de finalizar el inventario: ", respuesta);
          },
          error: function (error) {
            console.log(error);
          },
        });

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Inventario finalizado.!",
          sound: "../../../../../sounds/sound4",
        });

        location.reload();
      } else {
        Swal.close();
      }
    });
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 5000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "¡Datos inválidos!",
      sound: "../../../../../sounds/sound4",
    });
  }
}

function cargarCMBSucursal() {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_sucursales" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de sucursales: ", respuesta);
      if (respuesta.length == 0) {
        Swal.fire({
          title: "Sin sucursales con inventario",
          html: "Se puede configurar el inventario en sucursales.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText:
            'Sucursales<i class="far fa-arrow-alt-circle-right ml-1"></i>',
          cancelButtonText: "Cancelar ",
          reverseButtons: false,
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
            cancelButton: "btn-custom btn-custom--blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "../../../configuracion/";
          } else {
            window.location.href = "../../../dashboard.php";
          }
        });
      } else if (respuesta[0][0] != undefined) {
        Swal.fire({
          title: "Inventario finalizado",
          html: "No existen sucursales pendientes de inventario inicial.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText:
            'Inventario periódico<i class="far fa-arrow-alt-circle-right ml-1"></i>',
          cancelButtonText: "Cancelar",
          reverseButtons: false,
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
            cancelButton: "btn-custom btn-custom--blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "../inventario_periodico/index.php";
          } else {
            window.location.href = "../../../dashboard.php";
          }
        });
      } else {
        $.each(respuesta, function (i) {
          html +=
            '<option value="' +
            respuesta[i].id +
            '">' +
            respuesta[i].sucursal +
            "</option>";
        });
        $("#cmbSucursales").append(html);
        $("#cmbSucursales").on("change", function () {
          $("#tblInventariosIniciales").DataTable().destroy();
          cargarProductosEmpresa();
          cargarCategoriasProductosEmpresa();
          $("#btnGuardar").prop("disabled", false);
          $("#btnFinalizar").prop("disabled", false);
          $("#btnGuardarFot").prop("disabled", false);
          $("#btnFinalizarFot").prop("disabled", false);
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });

  new SlimSelect({
    select: "#cmbSucursales",
    deselectLabel: '<span class="">✖</span>',
    /*addable: function (value) {
      validarTipoProducto(value);
    }*/
  });
}

function verificarCambioCMBSucursales() {
  $("#cmbSucursales").on("change", function () {
    $("#tblInventariosIniciales").DataTable().destroy();
    cargarProductosEmpresa();
  });
}

function cargarCategoriasProductosEmpresa() {
  var select = $("#cmbSucursales").val();
  var html = "";

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_categorias_productos_empresa",
      data: select,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de categorias: ", respuesta);
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKCategoriaProducto +
          '" id="' +
          respuesta[i].PKCategoriaProducto +
          '">' +
          respuesta[i].CategoriaProductos +
          "</option>";
      });

      $("#cmbCategorias").html(html);
    },
  });
}

function validarCMBCategorias(categorias) {
  $("#cmbCategorias").on("change", function () {
    $("#cmbCategorias option").each(function () {
      var activo = 0;
      var idCategoria = $(this).attr("id");
      cargarProductosPorCategoria(idCategoria, activo);
    });
    var seleccionados = categorias.selected();
    $.each(seleccionados, function () {
      var activo = 1;
      var idCategoria = this;
      cargarProductosPorCategoria(idCategoria, activo);
    });

    if (seleccionados.length < 1) {
      $("#tblInventariosIniciales").DataTable().destroy();
      cargarProductosEmpresa();
    }
  });
}

function cargarProductosEmpresa() {
  var sucursal = $("#cmbSucursales").val();

  var table = $("#tblInventariosIniciales")
    .DataTable({
      language: setFormatDatatables(),
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 50,
      responsive: true,
      lengthChange: false,
      columnDefs: [
        { orderable: false, targets: 0, visible: false },
        { orderable: false, targets: 1, visible: false },
      ],
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
            extend: "excelHtml5",
            exportOptions: {
              columns: [2, 3, 4, 5, 6, 7, 8],
            },
            customize: function (xlsx) {
              var sheet = xlsx.xl.worksheets["sheet1.xml"];

              // Bucle en la columna `E`
              $('row c[r^="E"]', sheet).each(function () {
                // Verificar si no esta vacio y si no esta escrito la palabra "Serie"
                if ($(this).text() == "No aplica") {
                  $(this).attr("s", "32");
                }
              });

              // Bucle en la columna `F`
              $('row c[r^="F"]', sheet).each(function () {
                // Verificar si no esta vacio y si no esta escrito la palabra "Lote"
                if ($(this).text() == "No aplica") {
                  $(this).attr("s", "32");
                }
              });

              // Bucle en la columna `G`
              $('row c[r^="G"]', sheet).each(function () {
                // Verificar si no esta vacio y si no esta escrito la palabra "Caducidad"
                if ($(this).text() == "No aplica") {
                  $(this).attr("s", "32");
                }
              });
            },
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          },
          {
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO SUBIR ARCHIVO-01.svg" width="20" class="mr-1"> Importar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
            action: function (e, dt, node, config) {
              $("#excelmodal").modal("show");
            },
          },
        ],
      },
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_productosEmpresa",
          data: sucursal,
        },
      },
      columns: [
        { data: "IdDetalle" },
        { data: "Id" },
        { data: "Clave" },
        { data: "Nombre" },
        { data: "Descripcion" },
        { data: "Cantidad" },
        /*{ data: "Serie" },*/
        { data: "Lote" },
        { data: "FechaCaducidad" },
        { data: "Acciones" },
      ],
      columnDefs: [
        {
          target: 0,
          visible: false,
        },
        {
          target: 1,
          visible: false,
        },
      ],
    })
    .on("xhr.dt", function () {
      $("#btnCategorias").prop("disabled", false);
      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "click focus hover",
        });
      });
    });

  $("#tblInventariosIniciales").on("page.dt", function () {
    $(function () {
      $("[data-toggle='tooltip']").tooltip({
        trigger: "click focus hover",
      });
    });
  });

  $("#tblInventariosIniciales tbody")
    .off("click")
    .on("click", "img", function () {
      var identificador = $(this).attr("id").split("_")[1];
      var tipoBoton = $(this).attr("id").split("_")[0];

      if (tipoBoton == "btnAgregar") {
        var id = $("#id_" + identificador).val();
        var clave = $("#clave_" + identificador).val();

        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "delete_data",
            funcion: "delete_emptyProductStock",
            data: 0,
            data2: sucursal,
            data3: id,
            data4: clave,
            data5: 0,
          },
          dataType: "json",
          success: function (respuesta) {
            console.log(
              "respuesta de guardar un producto vacio de inventario: ",
              respuesta
            );
            table.ajax.reload(null, false);
          },
          error: function (error) {
            console.log(error);
          },
        });

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se agregó un producto.!",
          sound: "../../../../../sounds/sound4",
        });

        $(".tooltip").tooltip("hide");
      } else {
        var idDetalle = 0;
        if ($("#idDetalle_" + identificador).val()) {
          var idDetalle = $("#idDetalle_" + identificador).val();
        }

        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "delete_data",
            funcion: "delete_emptyProductStock",
            data: idDetalle,
            data2: 0,
            data3: 0,
            data4: "",
            data5: 1,
          },
          dataType: "json",
          success: function (respuesta) {
            console.log(
              "respuesta de guardar un producto vacio de inventario: ",
              respuesta
            );
          },
          error: function (error) {
            console.log(error);
          },
        });

        table.ajax.reload(null, false);

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó un producto.!",
          sound: "../../../../../sounds/sound4",
        });

        $(".tooltip").tooltip("hide");
      }
    });
}

function cargarProductosPorCategoria(idCategoria, activo) {
  var sucursal = $("#cmbSucursales").val();

  $("#tblInventariosIniciales").DataTable().destroy();

  var table = $("#tblInventariosIniciales")
    .DataTable({
      language: setFormatDatatables(),
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 50,
      responsive: true,
      lengthChange: false,
      columnDefs: [
        { orderable: false, targets: 0, visible: false },
        { orderable: false, targets: 1, visible: false },
      ],
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
            extend: "excelHtml5",
            exportOptions: {
              columns: [2, 3, 4, 5, 6, 7, 8],
            },
            customize: function (xlsx) {
              var sheet = xlsx.xl.worksheets["sheet1.xml"];

              // Bucle en la columna `E`
              $('row c[r^="E"]', sheet).each(function () {
                // Verificar si no esta vacio y si no esta escrito la palabra "Serie"
                if ($(this).text() == "No aplica") {
                  $(this).attr("s", "32");
                }
              });

              // Bucle en la columna `F`
              $('row c[r^="F"]', sheet).each(function () {
                // Verificar si no esta vacio y si no esta escrito la palabra "Lote"
                if ($(this).text() == "No aplica") {
                  $(this).attr("s", "32");
                }
              });

              // Bucle en la columna `G`
              $('row c[r^="G"]', sheet).each(function () {
                // Verificar si no esta vacio y si no esta escrito la palabra "Caducidad"
                if ($(this).text() == "No aplica") {
                  $(this).attr("s", "32");
                }
              });
            },
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          },
          {
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO SUBIR ARCHIVO-01.svg" width="20" class="mr-1"> Importar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
            action: function (e, dt, node, config) {
              $("#excelmodal").modal("show");
            },
          },
        ],
      },
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_productosPorCategoria",
          dataIdCategoria: idCategoria,
          dataActivo: activo,
          data: sucursal,
        },
      },
      columns: [
        { data: "IdDetalle" },
        { data: "Id" },
        { data: "Clave" },
        { data: "Nombre" },
        { data: "Descripcion" },
        { data: "Cantidad" },
        /*{ data: "Serie" },*/
        { data: "Lote" },
        { data: "FechaCaducidad" },
        { data: "Acciones" },
      ],
      columnDefs: [
        {
          target: 0,
          visible: false,
        },
        {
          target: 1,
          visible: false,
        },
      ],
    })
    .on("xhr.dt", function () {
      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "click focus hover",
        });
      });
    });

  $("#tblInventariosIniciales").on("page.dt", function () {
    $(function () {
      $("[data-toggle='tooltip']").tooltip({
        trigger: "click focus hover",
      });
    });
  });

  $("#tblInventariosIniciales tbody")
    .off("click")
    .on("click", "img", function () {
      var identificador = $(this).attr("id").split("_")[1];
      var tipoBoton = $(this).attr("id").split("_")[0];

      if (tipoBoton == "btnAgregar") {
        var id = $("#id_" + identificador).val();
        var clave = $("#clave_" + identificador).val();

        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "delete_data",
            funcion: "delete_emptyProductStock",
            data: 0,
            data2: sucursal,
            data3: id,
            data4: clave,
            data5: 0,
          },
          dataType: "json",
          success: function (respuesta) {
            console.log(
              "respuesta de guardar un producto vacio de inventario: ",
              respuesta
            );
            table.ajax.reload(null, false);
          },
          error: function (error) {
            console.log(error);
          },
        });

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se agregó un producto.!",
          sound: "../../../../../sounds/sound4",
        });

        $(".tooltip").tooltip("hide");
      } else {
        var idDetalle = 0;
        if ($("#idDetalle_" + identificador).val()) {
          var idDetalle = $("#idDetalle_" + identificador).val();
        }

        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "delete_data",
            funcion: "delete_emptyProductStock",
            data: idDetalle,
            data2: 0,
            data3: 0,
            data4: "",
            data5: 1,
          },
          dataType: "json",
          success: function (respuesta) {
            console.log(
              "respuesta de guardar un producto vacio de inventario: ",
              respuesta
            );
          },
          error: function (error) {
            console.log(error);
          },
        });

        table.ajax.reload(null, false);

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó un producto.!",
          sound: "../../../../../sounds/sound4",
        });

        $(".tooltip").tooltip("hide");
      }
    });
}

function validarCampo(item) {
  var id = $(item).attr("id").split("_")[1];
  var campo = $(item).attr("id").split("_")[0];

  var cantidad = $("#cant_" + id);

  if (!cantidad.val()) {
    $("#invalid_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_" +
        id +
        "'>Introduce también la cantidad para guardar este producto</div>"
    ).insertAfter($("#cant_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
  if ($(item).val() != null) {
    $("#invalid_campo_" + campo + "_" + id).remove();
    $("#mensaje_invalido").remove();
  } else {
    $("#invalid_campo_" + campo + "_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_campo_" +
        campo +
        "_" +
        id +
        "'>Introduce también este campo</div>"
    ).insertAfter($("#" + campo + "_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
}

function validarCantidad(item) {
  var id = $(item).attr("id").split("_")[1];
  if ($(item).val() != null) {
    $("#invalid_" + id).remove();
    $("#mensaje_invalido").remove();
  } else {
    $("#invalid_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_" +
        id +
        "'>Introduce también la cantidad para guardar este producto</div>"
    ).insertAfter($(item));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }

  if (!$("#ser_" + id).prop("disabled") && !$("#ser_" + id).val()) {
    $("#invalid_campo_ser_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_campo_ser_" +
        id +
        "'>Introduce también este campo</div>"
    ).insertAfter($("#ser_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
  if (!$("#lot_" + id).prop("disabled") && !$("#lot_" + id).val()) {
    $("#invalid_campo_lot_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_campo_lot_" +
        id +
        "'>Introduce también este campo</div>"
    ).insertAfter($("#lot_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
  if (!$("#fech_" + id).prop("disabled") && !$("#fech_" + id).val()) {
    $("#invalid_campo_fech_" + id).remove();
    $("#mensaje_invalido").remove();
    $(
      "<div class='invalid-feedback d-block' id='invalid_campo_fech_" +
        id +
        "'>Introduce también este campo</div>"
    ).insertAfter($("#fech_" + id));
    $("#div_invalido").append(
      "<div class='invalid-feedback d-block' id='mensaje_invalido'>No dejes productos incompletos</div>"
    );
  }
}

function campoCaducidad(item) {
  inserPrevio(item);
  validarCampo(item);
}
function limitarLongitud(input) {
  if (input.value.length > 11) {
    input.value = input.value.substring(0, 11);
  }
}

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    lengthMenu: `Mostrar: 
                  <select class="mb-n2 mt-1">
                    <option value="20">20</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                  <select> productos`,
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}
