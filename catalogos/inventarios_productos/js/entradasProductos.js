var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _global = {
  tipo:0,
  cliente: 0,
  proveedor: 0,
  sucOrigen: 0,
}

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
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
    select: "#cmbTypeEntryFilter",
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
        filtrarTableEntry(data[0]);
      }

      filtroSucursal("", "cmbBranchFilter");
      filtroTipoEntrada("", "cmbTypeEntryFilter");
    },
  });
}

function filtroSucursal(data, input) {
  var html = '<option value="" selected>Seleccione una sucursal...</option>';

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

function filtroTipoEntrada(data, input) {
  var html =
    '<option value="" selected>Seleccione un tipo de entrada...</option>';

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_typeEntryFilter" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta orden de fabricacion combo:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKTipoEntrada) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKTipoEntrada +
            '" ' +
            selected +
            ">" +
            respuesta[i].TipoEntrada +
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

$(document).on("click", "#btnFilterEntries", function () {
  validate_Permissions(23);
});

function filtrarTableEntry(permisions) {
  var topButtons = [];
  if (permisions.isAdd == "1") {
    topButtons.push({
      text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
      className: "btn-custom--white-dark",
      action: function () {
        window.location.href = "agregar_entrada.php";
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
  var typeEntryFilter = $("#cmbTypeEntryFilter").val()
    ? $("#cmbTypeEntryFilter").val()
    : "";
  var fromDate = $("#txtDateFrom").val()
    ? $("#txtDateFrom").val()
    : "0000-00-00";
  var toDate = $("#txtDateTo").val() ? $("#txtDateTo").val() : "0000-00-00";

  $("#tblEntradasProductos").DataTable().destroy();
  $("#tblEntradasProductos").DataTable({
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
        funcion: "get_entriesTableFilter",
        data: branchFilter,
        data2: typeEntryFilter,
        data3: fromDate,
        data4: toDate,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Folio" },
      { data: "Origen" },
      { data: "Fecha" },
      { data: "Referencia" },
      { data: "Tipo_Entrada" },
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

function obtenerVer(folioEntrada, tipoId) {
  window.location.href = "ver_entrada.php?f=" + folioEntrada;
}

function obtenerEditar(folioEntrada, tipoId, id, sucursal, fecha, usuario) {
  if(folioEntrada.startsWith("AJP")){
    var tipo = 'Positivo';
  }else{
    var tipo = 'Negativo';
  }
  if(tipoId == 3){
    $().redirect("../ajuste_inventario/ajuste_movimientos.php", {
      data1: id,
      data2: sucursal,
      data3: fecha,
      data4: usuario,
      data5: folioEntrada,
      data6: tipo,
      data7: '',
      data8: '',
      data9: ''
    });
  }else{
    window.location.href = "editar_entrada.php?f=" + folioEntrada;
  }
}

function obtenerEliminar(folioEntrada, tipoId, cliente, proveedor, sucOrigen) {
  _global.tipo = tipoId;
  _global.cliente = cliente;
  _global.proveedor = proveedor;
  _global.sucOrigen = sucOrigen;
  $("#entradaMD").html(folioEntrada);
  $("#eliminar_Entry").modal("show");
}

function deleteEntry() {
  var folio_entrada = $("#entradaMD").html();

  if (_global.tipo == 1){
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "delete_data",
        funcion: "delete_entry_Table",
        data: folio_entrada,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta[0].status) {
          $("#eliminar_Entry").modal("toggle");
  
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se ha eliminado la entrada con éxito!",
            sound: '../../../../../sounds/sound4'
          });
  
          $("#tblEntradasProductos").DataTable().ajax.reload();
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: '../../../../../sounds/sound4'
          });
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }else if(_global.tipo == 3){
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "delete_data",
        funcion: "delete_entryAdjust_Table",
        data: folio_entrada,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta[0].status) {
          $("#eliminar_Entry").modal("toggle");
  
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se ha eliminado la entrada con éxito!",
            sound: '../../../../../sounds/sound4'
          });
  
          $("#tblEntradasProductos").DataTable().ajax.reload();
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: '../../../../../sounds/sound4'
          });
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }else if(_global.tipo == 2){
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "delete_data",
        funcion: "delete_entryTranfer_Table",
        data: folio_entrada,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta[0].status) {
          $("#eliminar_Entry").modal("toggle");
  
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se ha eliminado la entrada con éxito!",
            sound: '../../../../../sounds/sound4'
          });
  
          $("#tblEntradasProductos").DataTable().ajax.reload();
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: '../../../../../sounds/sound4'
          });
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }else if(_global.tipo == 4){
    if(_global.sucOrigen != 0){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "delete_data",
          funcion: "delete_entryDirectBranch_Table",
          data: folio_entrada,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#eliminar_Entry").modal("toggle");
    
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 2000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se ha eliminado la entrada con éxito!",
              sound: '../../../../../sounds/sound4'
            });
    
            $("#tblEntradasProductos").DataTable().ajax.reload();
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }else if(_global.proveedor != 0){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "delete_data",
          funcion: "delete_entryDirectProvider_Table",
          data: folio_entrada,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#eliminar_Entry").modal("toggle");
    
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 2000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se ha eliminado la entrada con éxito!",
              sound: '../../../../../sounds/sound4'
            });
    
            $("#tblEntradasProductos").DataTable().ajax.reload();
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }else if(_global.cliente != 0){
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "delete_data",
          funcion: "delete_entryDirectCustomer_Table",
          data: folio_entrada,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#eliminar_Entry").modal("toggle");
    
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 2000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se ha eliminado la entrada con éxito!",
              sound: '../../../../../sounds/sound4'
            });
    
            $("#tblEntradasProductos").DataTable().ajax.reload();
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  }
}
