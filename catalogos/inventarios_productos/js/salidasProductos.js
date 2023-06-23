var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "<img src='../../../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  validate_Permissions(23);

  new SlimSelect({
    select: "#cmbBranchFilter",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbTypeExitFilter",
    deselectLabel: '<span class="">✖</span>',
  });
});

function validate_Permissions(pkPantalla) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;

      if (pkPantalla == "23") {
        filtrarTableExit(data[0]);
        filtroSucursal("", "cmbBranchFilter");
        filtroTipoSalida("", "cmbTypeExitFilter");
      }
    },
  });
}

function filtroSucursal(data, input) {
  var html = '<option value="" selected>Todas...</option>';

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_branchEntryFilter" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta orden de fabricacion combo:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKSucursal) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKSucursal +
            '" ' +
            selected +
            ">" +
            respuesta[i].Sucursal +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }
      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });

  $("#" + input + "").html(html);
  $("#" + input + " option:not(:selected)").remove();
}

function filtroTipoSalida(data, input) {
  var html = '<option value="" selected>Todas...</option>';

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_typeExitFilter" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta orden de fabricacion combo:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKTipoSalida) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKTipoSalida +
            '" ' +
            selected +
            ">" +
            respuesta[i].TipoSalida +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay tipos que mostrar</option>';
      }
      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });

  $("#" + input + "").html(html);
  $("#" + input + " option:not(:selected)").remove();
}

$(document).on("click", "#btnFilterExits", function () {
  validate_Permissions(23);
});

function filtrarTableExit(permisions) {
  var topButtons = [];
  if (permisions.isAdd == "1") {
    topButtons.push({
      text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
      className: "btn-custom--white-dark",
      action: function () {
        window.location.href = "agregar_salida.php";
      },
    });
  }
  if (permisions.isExport == "1") {
    topButtons.push({
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
      className: "btn-custom--white-dark",
      titleAttr: "Excel",
    });
  }
  var branchFilter = $("#cmbBranchFilter").val()
    ? $("#cmbBranchFilter").val()
    : "";
  var typeExitFilter = $("#cmbTypeExitFilter").val()
    ? $("#cmbTypeExitFilter").val()
    : "";
  var fromDate = $("#txtDateFrom").val()
    ? $("#txtDateFrom").val()
    : "0000-00-00";
  var toDate = $("#txtDateTo").val() ? $("#txtDateTo").val() : "0000-00-00";

  $("#tblSalidasProductos").DataTable().destroy();
  $("#tblSalidasProductos").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 50,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
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
      buttons: topButtons,
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_extisTableFilter",
        data: branchFilter,
        data2: typeExitFilter,
        data3: fromDate,
        data4: toDate,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Folio" },
      { data: "Origen" },
      { data: "Destino" },
      { data: "Fecha" },
      { data: "Tipo_Salida" },
    ],
  });
}

function isExport() {
  if (_permissions.export == "1") {
    return '<img class="readEditPermissions" type="submit" width="50px" id="btnExportPermissions" onclick="exportarPDF()" src="../../../../img/excel-azul.svg" />';
  } else {
    return "";
  }
}

function obtenerVer(folioSalida) {
  //window.location.href = "ver_salida.php?f=" + folioSalida;
  //Obtener el tipo de salida seleccionada 
  fetch("../../../pedidos/php/funciones.php?clase=get_data&funcion=get_TipoParcialidadesPedido&data=" + folioSalida)
  .then( respuesta => {
      //console.log(respuesta)
      return respuesta.json()
  })
  .then( datos => {
    console.log(datos[0].tipo);
    switch (datos[0].tipo) {
      case '1':
        window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaCoti.php?folio="+folioSalida+"&orden="+0;
      break;
      case '2':
        window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaVenta.php?folio="+folioSalida+"&orden="+0;
      break;
      case '3':
        window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_Salida.php?folio="+folioSalida+"&orden="+0;
      break;
      case '4':
        window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaDevolucion.php?folio="+folioSalida+"&cuenta="+0;
      break;
      case '5':
        window.location.href = "../../../inventarios_productos/catalogos/salidas_productos/functions/descargar_SalidaGral.php?folio="+folioSalida+"&orden="+0;
      break;
    }
  })
  .catch( error => {
      //console.log(error)
  });
}

function obtenerEditar(folioSalida) {
  window.location.href = "editar_salida.php?f=" + folioSalida;
}
