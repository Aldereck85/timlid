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
  const $selectSucModal = new SlimSelect({
    select: "#cmbSucursalesModal",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona sucursal",
  });

  const $selectTiposModal = new SlimSelect({
    select: "#cmbTiposModal",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona sucursal",
  });

  const $selectTip = new SlimSelect({
    select: "#cmbTipos",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Selecciona tipo de ajuste",
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
    cargarAjustesTodos();
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
        $("#btnAjuste").prop("disabled", true);

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
        $("#btnAjuste").prop("disabled", true);

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
        $("#btnAjuste").prop("disabled", true);

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

  $("#exportInventarioInicial").css("display", "none");
  $("#importInventarioInicial").css("display", "none");
  $("#cmbSucursales").on("change", () => {
    if ($("#cmbSucursales").val()) {
      $("#exportInventarioInicial").css("display", "inline");
      $("#importInventarioInicial").css("display", "inline");
    } else {
      $("#exportInventarioInicial").css("display", "none");
      $("#importInventarioInicial").css("display", "none");
    }
  });

  $("#exportInventarioInicial").on("click", () => {
    window.location.href =
      "export_inventario_inicial?sucursal=" + $("#cmbSucursales").val();
  });

  $("#btnAjuste").on("click", function () {
    cargarCMBSucursalModal();

    // Swal.fire({
    //     title: "",
    //     html:
    //         '<div class="row" style="margin-left:-1.49rem;margin-right:-1.49rem">'+
    //             '<div class="col-9">'+
    //                 '<div class="card">'+
    //                     '<div class="row">'+
    //                         '<div class="col-12">'+
    //                             '<p text-center">Elige la sucursal y el tipo de ajuste</p>' +
    //                         '</div>'+
    //                     '</div>'+
    //                     '<div class="form-group row">'+
    //                         '<div class="col-6">'+

    //                         '</div>'+
    //                     '</div>'+
    //                 '</div>'+
    //             '</div>'+
    //         '</div>'
    //         ,
    //     showCancelButton: true,
    //     confirmButtonText: "Aceptar",
    //     cancelButtonText: "Cancelar",
    //     reverseButtons: true,
    //     width: '45rem',
    //     customClass: {
    //         actions: "d-flex float-left justify-content-start",
    //         confirmButton: "btn-custom btn-custom--border-blue btn-aceptar ",
    //         cancelButton: "btn-custom btn-custom--border-blue",
    //     },
    //     buttonsStyling: false,
    //     allowEnterKey: false,
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         insertarAjustes();
    //     } else {
    //         Swal.close();
    //     }
    // });

    $(".btn-aceptar").prop("disabled", true);
    $("#cmbSucursalesModal").on("change", function () {
      if ($("#cmbSucursalesModal").val() && $("#cmbTiposModal").val()) {
        $(".btn-aceptar").prop("disabled", false);
      }
    });

    $("#cmbTiposModal").on("change", function () {
      if ($("#cmbSucursalesModal").val() && $("#cmbTiposModal").val()) {
        $(".btn-aceptar").prop("disabled", false);
      }
    });

    new SlimSelect({
      select: "#cmbSucursalesModal",
      deselectLabel: '<span class="">✖</span>',
      placeholder: "Selecciona sucursal",
      showContent: "up",
    });

    new SlimSelect({
      select: "#cmbTiposModal",
      deselectLabel: '<span class="">✖</span>',
      placeholder: "Selecciona tipo de ajuste",
      showContent: "up",
    });
  });
});

function validarExcel() {
  $("#loaderValidacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("sucursal", $("#cmbSucursales").val());
  //console.log(formData.get("dataexcel"));
  //console.log(formData.get("sucursal"));
  $.ajax({
    url: "validarExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta.status);
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
        $("#tblAjustes").DataTable().destroy();
        cargarAjustes();
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
    $("#tblAjustes").DataTable().destroy();
    cargarAjustes();
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
      funcion: "get_cmb_foliosAjuste",
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
        $("#tblAjustes").DataTable().destroy();
        cargarAjustes();
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarAjustes() {
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

  $("#tblAjustes")
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
          funcion: "get_Ajustes",
          data1: sucursal,
          data2: tipo,
          data3: folio,
        },
        async: true,
      },
      columns: [
        { data: "IdAjuste" },
        { data: "Sucursal" },
        { data: "FechaCaptura" },
        { data: "Usuario" },
        { data: "Folio" },
        { data: "TipoAjuste" },
      ],
    })
    .on("xhr.dt", function () {
      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "click focus hover",
        });
      });
    });

  $("#tblAjustes tbody").on("click", "tr", function () {
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

    var table = $("#tblAjustes").DataTable();

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
    var posfolio = folio2.indexOf("A");
    var possfolio = folio2.indexOf("</");
    var folio = folio2.slice(posfolio, possfolio);
    var pos1folio = folio2.indexOf("ue=");
    var pos2folio = folio2.indexOf('">A');
    var idajuste = folio2.slice(pos1folio + 4, pos2folio);

    var tipo2 = rowData.TipoAjuste;
    var pos1tipo = tipo2.indexOf('">');
    var pos2tipo = tipo2.indexOf("</");
    var tipoajuste = tipo2.slice(pos1tipo + 2, pos2tipo);

    $().redirect("ajuste_movimientos.php", {
      data1: idajuste,
      data2: sucursal,
      data3: fechacaptura,
      data4: usuario,
      data5: folio,
      data6: tipoajuste,
      data7: cmbsucursal,
      data8: cmbtipo,
      data9: cmbfolio,
    });
  });
}

function cargarAjustesTodos() {
  $("#tblAjustes")
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
          funcion: "get_AjustesTodos",
        },
        async: true,
      },
      columns: [
        { data: "IdAjuste" },
        { data: "Sucursal" },
        { data: "FechaCaptura" },
        { data: "Usuario" },
        { data: "Folio" },
        { data: "TipoAjuste" },
      ],
    })
    .on("xhr.dt", function () {
      $(function () {
        $("[data-toggle='tooltip']").tooltip({
          trigger: "click focus hover",
        });
      });
    });

  $("#tblAjustes tbody").on("click", "tr", function () {
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

    var table = $("#tblAjustes").DataTable();

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
    var posfolio = folio2.indexOf("A");
    var possfolio = folio2.indexOf('"</');
    var folio = folio2.slice(posfolio, possfolio);
    var pos1folio = folio2.indexOf("ue=");
    var pos2folio = folio2.indexOf('">A');
    var idajuste = folio2.slice(pos1folio + 4, pos2folio);

    var tipo2 = rowData.TipoAjuste;
    var pos1tipo = tipo2.indexOf('">');
    var pos2tipo = tipo2.indexOf("</");
    var tipoajuste = tipo2.slice(pos1tipo + 2, pos2tipo);

    $().redirect("ajuste_movimientos.php", {
      data1: idajuste,
      data2: sucursal,
      data3: fechacaptura,
      data4: usuario,
      data5: folio,
      data6: tipoajuste,
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

function insertarAjustes() {
  var sucursal = $("#cmbSucursalesModal").val();
  var tipo = $("#cmbTiposModal").val();

  $().redirect("ajuste_inventario.php", {
    data1: sucursal,
    data2: tipo,
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
