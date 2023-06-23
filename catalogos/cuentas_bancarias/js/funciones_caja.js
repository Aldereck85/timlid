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
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  var idCuenta = $("#pkCuenta").val();

  $("#tblDetalles").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 10,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "",//btn-table-custom
        },
        buttonLiner: {
          tag: null,
        },
      },
      buttons: [
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar Excel</span>',
          className: "btn-custom--white-dark btn-custom",
          titleAttr: "Excel",
          exportOptions: {
            columns: ":visible",
          },
        },
      ],
    },
    ajax: {
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_cajaTableMovimientos",
        data: idCuenta,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Fecha" },
      { data: "Descripción" },
      { data: "Retiro/cargo" },
      { data: "Deposito/Abono" },
      { data: "Acciones" },
    ],
  });
  //cargarCMBCategoriasG("", "cmbCategoria");
  CargarSlimSelect();
});

function rDinero(idAc) {
  $("#gasto").hide();
  $.ajax({
    type: "POST",
    url: "functions/get_Combos.php",
    data: { id: idAc },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#idCuentaCaja").val(datos.idCajaActual);
      $("#saldoCuentaCaja").val(datos.saldoInicialCaja);
    },
  });
  cargarCMBCategoriasG("", "cmbCategoria");
}

function CargarSlimSelect() {
  new SlimSelect({
    select: "#cmbCategoria",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar categoría",
  });
  new SlimSelect({
    select: "#cmbSubcategoria",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona la subcategoría",
  });
}

function cargarCMBCategoriasG(data, input) {
  var idemp = $("#emp_id").val();
  var html = "";
  var selected;
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_categorias_gasto",
      data: idemp,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta c: ", respuesta);

      html += "<option data-placeholder='true'></option>";

      $.each(respuesta, function (i) {
        /* if (data === respuesta[i].PKCategoria) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKCategoria +
          '" ' +
          //selected +
          ">" +
          respuesta[i].Nombre +
          "</option>";
      });
      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBProveedor(input) {
  var html = "";
  var selected;
  $.ajax({
    url: "functions/get_proveedor.php",
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta proveedor: ", respuesta);
      html = "<option disabled selected>Selecciona un proveedor</option>";
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKProveedor +
          '">' +
          respuesta[i].Razon_Social +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Guardar Categoria
function guardarCategoria() {
  var msjsucces = "¡Categoria agregada exitosa!";
  var msjwarning = "¡Algo salio mal!";

  var nombreCat = $("#txtCategoria").val();

  if (!nombreCat) {
    $("#invalid-categoria").css("display", "block");
    $("#txtCategoria").addClass("is-invalid");
  }

  var badNombreCat =
    $("#invalid-categoria").css("display") === "block" ? false : true;

  if (badNombreCat) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_Categoria.php",
      data: {
        nombreCat: nombreCat,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#txtCategoria").val("");
          $("#nueva_categoria").modal("toggle");
          cargarCMBCategoriasG("", "cmbCategoria");
          lobiboxAlert("success", msjsucces);
        } else {
          lobiboxAlert("error", msjwarning);
        }
      },
    });
  }
}

//Guardar SubCategoria
function guardarSubCategoria() {
  var msjsucces = "¡Subcategoria agregada exitosa!";
  var msjwarning = "¡Algo salio mal!";

  var categoriaId = $("#cmCategoria").val();
  var nombreSubCat = $("#txtSubCategoria").val();

  if (!categoriaId) {
    $("#invalid-categoriaSub").css("display", "block");
    $("#cmCategoria").addClass("is-invalid");
  }
  if (!nombreSubCat) {
    $("#invalid-subcategoria").css("display", "block");
    $("#txtSubCategoria").addClass("is-invalid");
  }

  var badCategoria =
    $("#invalid-categoriaSub").css("display") === "block" ? false : true;
  var badNombreSubCat =
    $("#invalid-subcategoria").css("display") === "block" ? false : true;

  if (badCategoria && badNombreSubCat) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_SubCategoria.php",
      data: {
        categoriaId: categoriaId,
        nombreSubCat: nombreSubCat,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#txtSubCategoria").val("");
          $("#nueva_subCategoria").modal("toggle");
          cargarCMBCategoriasG("", "cmbCategoria");
          lobiboxAlert("success", msjsucces);
        } else {
          lobiboxAlert("error", msjwarning);
        }
      },
    });
  }
}

function activarDesactivarCred(item) {
  var dias = document.getElementById("txtDiasCredito");
  var cred = document.getElementById("txtLimiteCredito");
  dias.classList.remove("is-invalid");
  cred.classList.remove("is-invalid");
  document.getElementById("invalid-diasProv").style.display = "none";
  document.getElementById("invalid-credProv").style.display = "none";
  if (item.checked) {
    dias.disabled = false;
    cred.disabled = false;
    return;
  }
  document.getElementById("txtDiasCredito").disabled = true;
  document.getElementById("txtLimiteCredito").disabled = true;
  return;
}
