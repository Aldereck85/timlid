$(document).ready(function () {
  const $selectFol = new SlimSelect({
    select: "#cmbFolios",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona folio",
  });

  const $selectSuc = new SlimSelect({
    select: "#cmbSucursales",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona sucursal",
  });

  new SlimSelect({
    select: "#cmbSucursalesModal",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona sucursal"
  });

  /* new SlimSelect({
    select: "#cmbTiposModal",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona tipo de cambio"
  }); */


  const $selectTip = new SlimSelect({
    select: "#cmbTipos",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona tipo de cambio",
  });

  if (sucursalcmb != "no" && tipocmb != "no" && foliocmb != "no") {
    setTimeout(function () {
      var sucursalCmb = $("#inputCmbSucursal").val();
      var tipoCmb = $("#inputCmbTipo").val();
      var folioCmb = $("#inputCmbFolio").val();

      $("#cmbSucursales option[value=" + sucursalCmb + "]").attr(
        "selected",
        "selected"
      );
      $("#cmbTipos option[value=" + tipoCmb + "]").attr("selected", "selected");
      $("#cmbFolios option[value=" + folioCmb + "]").attr(
        "selected",
        "selected"
      );
    }, 150);

    setTimeout(function () {
      $selectSuc.set(sucursalcmb);
      $selectTip.set(tipocmb);
      $selectFol.set(foliocmb);
    }, 300);
  } else {
    cargarCambiosTodos();
  }

  cargarCMBSucursal();
  cargarCMBTipo();
  cargarCMBFolio();

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_ValidarEmpresaAjusteInventario" },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta de validar empresa: ",
        respuesta[0]["sucursales"],
        respuesta[0]["productos"]
      );

      if (respuesta[0]["productos"] == 1 && respuesta[0]["sucursales"] == 0) {
        $("#btnCambio").prop("disabled", true);

        Swal.fire({
          title: "Sin productos",
          html:
            "<div class='container'" +
            "<div class='row'>" +
            "<p class='col-12 text-center'>No se cuenta con productos registrados o activos</p>" +
            "</div>" +
            "</div>",
          showCancelButton: false,
          confirmButtonText:
            "Productos <i class='far fa-arrow-alt-circle-right ml-1'></i>",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "../productos/";
          }
        });
      } else if (
        respuesta[0]["productos"] == 0 &&
        respuesta[0]["sucursales"] == 1
      ) {
        $("#btnCambio").prop("disabled", true);

        Swal.fire({
          title: "Sin sucursales con inventario",
          html:
            "<div class='container'" +
            "<div class='row'>" +
            "<p class='col-12 text-center'>Se puede configurar el inventario en sucursales.</p>" +
            "</div>" +
            "</div>",
          showCancelButton: false,
          confirmButtonText:
            "Sucursales <i class='far fa-arrow-alt-circle-right ml-1'></i>",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "../../../configuracion/";
          }
        });
      } else if (
        respuesta[0]["productos"] == 1 &&
        respuesta[0]["sucursales"] == 1
      ) {
        $("#btnCambio").prop("disabled", true);

        Swal.fire({
          title: "Sin productos ni sucursales con inventario",
          html:
            "<div class='container'" +
            "<div class='row'>" +
            "<p class='col-12 text-center'>No se cuenta con productos registrados o activos asi también sucursales con inventario.</p>" +
            "</div>" +
            "</div>",
          showCancelButton: true,
          confirmButtonText:
            "Productos <i class='far fa-arrow-alt-circle-right ml-1'></i>",
          cancelButtonText:
            "Sucursales <i class='far fa-arrow-alt-circle-right ml-1'></i>",
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
            window.location.href = "../productos/";
          } else {
            window.location.href = "../../../configuracion/";
          }
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });

  $("#btnCambio").on("click", function () {
    cargarCMBSucursalModal();

    // Swal.fire({
    //   title: "Nuevo cambio",
    //   html:
    //     "<div class='container'" +
    //     "<div class='row'>" +
    //     "<p class='col-12 text-center'>Elige la sucursal y el tipo de cambio</p>" +
    //     "</div>" +
    //     "<div class='row'>" +
    //     "<div class='col-12 col-md-6'>" +
    //     "<div class='form-group'>" +
    //     "<label for='cmbSucursalesModal' id='lblSucursalModal'>Sucursal:</label>" +
    //     "<select class='form-select' id='cmbSucursalesModal'>" +
    //     "<option data-placeholder='true'></option>" +
    //     "</select>" +
    //     "</div>" +
    //     "</div>" +
    //     "<div class='col-12 col-md-6'>" +
    //     "<div class='form-group'>" +
    //     "<label for='cmbTiposModal' id='lblTipoModal'>Tipo de cambio:</label>" +
    //     "<select class='form-select' id='cmbTiposModal'>" +
    //     "<option data-placeholder='true'></option>" +
    //     "<option value='1'>Lote</option>" +
    //     "<option value='0'>Serie</option>" +
    //     "</select>" +
    //     "</div>" +
    //     "</div>" +
    //     "</div>" +
    //     "</div>",
    //   showCancelButton: true,
    //   confirmButtonText: "Aceptar",
    //   cancelButtonText: "Cancelar",
    //   reverseButtons: true,
    //   customClass: {
    //     actions: "d-flex justify-content-around",
    //     confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
    //     cancelButton: "btn-custom btn-custom--border-blue",
    //   },
    //   buttonsStyling: false,
    //   allowEnterKey: false,
    // }).then((result) => {
    //   if (result.isConfirmed) {
    //     insertarCambios();
    //   } else {
    //     Swal.close();
    //   }
    // });

    $(".btn-aceptar").prop("disabled", true);
    $("#cmbSucursalesModal").on("change", function () {
      if ($("#cmbSucursalesModal").val() /* && $("#cmbTiposModal").val() */) {
        $(".btn-aceptar").prop("disabled", false);
      }
    });

    /* $("#cmbTiposModal").on("change", function () {
      if ($("#cmbSucursalesModal").val() && $("#cmbTiposModal").val()) {
        $(".btn-aceptar").prop("disabled", false);
      }
    }); */

    
  });
});

function cargarCMBSucursal() {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_sucursalesAjuste" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de sucursales: ", respuesta);
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].id +
          '">' +
          respuesta[i].sucursal +
          "</option>";
      });
      $("#cmbSucursales").append(html);
      $("#cmbSucursalesModal").append(html);
      $("#cmbSucursales").on("change", function () {
        $("#tblCambios").DataTable().destroy();
        cargarCambios();
        cargarCMBFolio();
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBTipo() {
  $("#cmbTipos").on("change", function () {
    $("#tblCambios").DataTable().destroy();
    cargarCambios();
    cargarCMBFolio();
  });
}

function cargarCMBFolio() {
  if (!$("#cmbSucursales").val()) {
    var sucursal = 0;
  } else {
    var sucursal = $("#cmbSucursales").val();
  }

  if (!$("#cmbTipos").val()) {
    var tipo = 3;
  } else {
    var tipo = $("#cmbTipos").val();
  }

  var html = '<option data-placeholder="true"></option>';

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_foliosCambiosLote",
      data1: sucursal,
      data2: tipo,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de folios: ", respuesta);
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].id +
          '">' +
          respuesta[i].folio +
          "</option>";
      });
      $("#cmbFolios").empty();
      $("#cmbFolios").append(html);
      $("#cmbFolios").on("change", function () {
        $("#tblCambios").DataTable().destroy();
        cargarCambios();
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCambios() {
  if (!$("#cmbSucursales").val()) {
    var sucursal = 0;
  } else {
    var sucursal = $("#cmbSucursales").val();
  }

  if (!$("#cmbTipos").val()) {
    var tipo = 3;
  } else {
    var tipo = $("#cmbTipos").val();
  }

  if (!$("#cmbFolios").val()) {
    var folio = 0;
  } else {
    var folio = $("#cmbFolios").val();
  }

  $("#tblCambios")
    .DataTable({
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
        buttons: [],
      },
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_Cambios",
          data1: sucursal,
          data2: tipo,
          data3: folio,
        },
        async: true,
      },
      columns: [
        { data: "IdCambio" },
        { data: "Sucursal" },
        { data: "FechaCaptura" },
        { data: "Usuario" },
        { data: "Folio" },
        { data: "TipoCambio" },
      ],
    })
    .on("xhr.dt", function () {
    //   $(function () {
    //     $("[data-toggle='tooltip']").tooltip({
    //       trigger: "click focus hover",
    //     });
    //   });
    });

  $("#tblCambios tbody").on("click", "tr", function () {
    if (!$("#cmbSucursales").val()) {
      var cmbsucursal = 0;
    } else {
      var cmbsucursal = $("#cmbSucursales").val();
    }

    if (!$("#cmbTipos").val()) {
      var cmbtipo = 3;
    } else {
      var cmbtipo = $("#cmbTipos").val();
    }

    if (!$("#cmbFolios").val()) {
      var cmbfolio = 0;
    } else {
      var cmbfolio = $("#cmbFolios").val();
    }

    var table = $("#tblCambios").DataTable();

    var rowData = table.row(this).data();

    var sucursal2 = rowData.Sucursal;
    var pos1sucursal = sucursal2.indexOf('">');
    var pos2sucursal = sucursal2.indexOf("</");
    var sucursal = sucursal2.slice(pos1sucursal + 2, pos2sucursal);

    var fecha2 = rowData.FechaCaptura;
    console.log(fecha2);
    var pos1fecha = fecha2.indexOf('">');
    
    var pos2fecha = fecha2.indexOf("</");
    var fechacaptura = fecha2.slice(pos1fecha + 2, pos2fecha);

    var usuario2 = rowData.Usuario;
    var pos1usuario = usuario2.indexOf('">');
    var pos2usuario = usuario2.indexOf("</");
    var usuario = usuario2.slice(pos1usuario + 2, pos2usuario);

    var folio2 = rowData.Folio;
    var posfolio = folio2.indexOf(">C");
    var possfolio = folio2.indexOf("</");
    var folio = folio2.slice(posfolio + 1, possfolio);
    var pos1folio = folio2.indexOf("ue=");
    var pos2folio = folio2.indexOf('">C');
    var idcambio = folio2.slice(pos1folio + 4, pos2folio);

    var tipo2 = rowData.TipoCambio;
    var pos1tipo = tipo2.indexOf('">');
    var pos2tipo = tipo2.indexOf("</");
    var tipocambio = tipo2.slice(pos1tipo + 2, pos2tipo);

    $().redirect("cambio_lote_movimientos.php", {
      data1: idcambio,
      data2: sucursal2,
      data3: fecha2,
      data4: usuario2,
      data5: folio2,
      data6: tipo2,
      data7: cmbsucursal,
      data8: cmbtipo,
      data9: cmbfolio,
    });
  });
}

function cargarCambiosTodos() {
  $("#tblCambios")
    .DataTable({
      language: setFormatDatatables(),
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 50,
      responsive: true,
      lengthChange: false,
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
        buttons: [],
      },
      ajax: {
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_CambiosTodos",
        },
        async: true,
      },
      columns: [
        { data: "IdCambio" },
        { data: "Sucursal",},
        { data: "FechaCaptura" },
        { data: "Usuario" },
        { data: "Folio" },
        { data: "TipoCambio" },
      ],
    })
    .on("xhr.dt", function () {
    //   $(function () {
    //     $("[data-toggle='tooltip']").tooltip({
    //       trigger: "click focus hover",
    //     });
    //   });
    });

  $("#tblCambios tbody").on("click", "tr", function () {
    if (!$("#cmbSucursales").val()) {
      var cmbsucursal = 0;
    } else {
      var cmbsucursal = $("#cmbSucursales").val();
    }

    if (!$("#cmbTipos").val()) {
      var cmbtipo = 3;
    } else {
      var cmbtipo = $("#cmbTipos").val();
    }

    if (!$("#cmbFolios").val()) {
      var cmbfolio = 0;
    } else {
      var cmbfolio = $("#cmbFolios").val();
    }

    var table = $("#tblCambios").DataTable();

    var rowData = table.row(this).data();

    var sucursal2 = rowData.Sucursal;
    var pos1sucursal = sucursal2.indexOf('">');
    var pos2sucursal = sucursal2.indexOf("</");
    var sucursal = sucursal2.slice(pos1sucursal + 2, pos2sucursal);

    var fecha2 = rowData.FechaCaptura;
    var pos1fecha = fecha2.indexOf('">');
    
    var pos2fecha = fecha2.indexOf("</");
    var fechacaptura = fecha2.slice(pos1fecha + 2, pos2fecha);
    
    var usuario2 = rowData.Usuario;
    var pos1usuario = usuario2.indexOf('">');
    var pos2usuario = usuario2.indexOf("</");
    var usuario = usuario2.slice(pos1usuario + 2, pos2usuario);

    var folio2 = rowData.Folio;
    var posfolio = folio2.indexOf(">C");
    var possfolio = folio2.indexOf('"</');
    var folio = folio2.slice(posfolio + 1, possfolio);
    var pos1folio = folio2.indexOf("ue=");
    var pos2folio = folio2.indexOf('">C');
    var idcambio = folio2.slice(pos1folio + 4, pos2folio);

    var tipo2 = rowData.TipoCambio;
    var pos1tipo = tipo2.indexOf('">');
    var pos2tipo = tipo2.indexOf("</");
    var tipocambio = tipo2.slice(pos1tipo + 2, pos2tipo);

    $().redirect("cambio_lote_movimientos.php", {
      data1: idcambio,
      data2: sucursal2,
      data3: fechacaptura,
      data4: usuario2,
      data5: folio2,
      data6: tipo2,
      data7: cmbsucursal,
      data8: cmbtipo,
      data9: cmbfolio,
    });
  });
}

function cargarCMBSucursalModal() {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_sucursalesAjuste",
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de sucursales: ", respuesta);
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].id +
          '">' +
          respuesta[i].sucursal +
          "</option>";
      });
      $("#cmbSucursalesModal").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function insertarCambios() {
  var sucursal = $("#cmbSucursalesModal").val();
  var tipo = $("#cmbTiposModal").val();
  //tipo -> 1: lote y 2: serie
  $().redirect("cambio_lote.php", {
    data1: sucursal,
    data2: 1, //Por el cambio de omitir la serie se quedara tipo siempre en 1
  });
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
                  <select>`,
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}
