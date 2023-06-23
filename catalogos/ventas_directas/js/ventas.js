var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _global = {
  pkSuc: 0,
  idVenta: 0,
};

$(document).ready(function () {
  validate_Permissions(13);
});

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "<img src='../../../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />.",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "<img src='../../../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
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

function cargarTablaVentasDirectasTemp() {
  var pkUsuario = $("#txtUsuario").val();
  if(_global.pkSuc == null){
    _global.pkSuc = 0;
  }

  $("#tblListadoVentasDirectasTemp").DataTable().destroy();
  $("#tblListadoVentasDirectasTemp").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    paging: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: [0,1,3,9], visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: [],
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_ventasDirectasTempTable",
        data: pkUsuario,
        data2: _global.pkSuc
      },
    },
    columns: [
      { data: "producto_id" },
      { data: "Id" },
      { data: "Producto" },
      { data: "cantidad_real" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "PrecioUnitario" },
      { data: "Impuestos" },
      { data: "Importe" },
      { data: "stock"},
      { data: "Existencias" },
      { data: "Acciones" },
    ],
  });
}

function obtenerEditar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_estadoVentaDirecta",
      data: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
      if (parseInt(data[0]["existe"]) == 1) {
        window.location.href = "editar_venta.php?vd=" + id;
      } else {
        obtenerVer(id);
      }
    },
  });
}

function cargarTablaVentasDirectasEdit(pkVenta,IdOpRmd) {
  $("#tblListadoVentasDirectasEdit").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    //pageLength: 15,
    paging: false,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "btn-table-custom",
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
        funcion: "get_ventaDirectaTableEdit",
        data: pkVenta,
        rmdID: pkVenta,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "PrecioUnitario" },
      { data: "Impuestos" },
      { data: "Importe" },
      { data: "Existencias" },
      { data: "Acciones" },
    ],
    columnDefs: [
      { "width": "10%", "targets": 4 },
      { "width": "10%", "targets": 2 },
      {targets: 0, visible: false}
    ],
  });
}

function cargarTablaVentasDirectasAdd(pkVenta) {
  $("#tblListadoVentasDirectasTemp").DataTable().destroy();
  $("#tblListadoVentasDirectasTemp").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "btn-table-custom",
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
        funcion: "get_ventaDirectaTableEdit",
        data: pkVenta,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "PrecioUnitario" },
      { data: "Impuestos" },
      { data: "Importe" },
      { data: "Existencias" },
      { data: "Acciones" },
    ],
  });
}

$(document).keydown(function(event){
  if(event.which=="17")
      cntrlIsPressed = true;
});

$(document).keyup(function(){
  cntrlIsPressed = false;
});

var cntrlIsPressed = true;

function obtenerVer(id) {
  if(cntrlIsPressed){
    window.open("ver_ventas.php?vd=" + id,"_blank");
  } else {
    window.location.href ="ver_ventas.php?vd=" + id;
  }
}

function mostrarIdEliminar(id, referencia) {
  $("#txtVentaDirectaIDD").val(id);
  $("#txtReferenciaD").val(referencia);
}

function obtenerEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_ventaDirecta",
      data: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
      if (data[0].status) {
        $("#tblVentasDirectas").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó la venta directa con exito!",
          sound: "../../../../../sounds/sound4",
        });
        setTimeout(function(){window.location.href = "../ventas"},1500);
        console.log("Eliminada");
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
          sound: "../../../../../sounds/sound4",
        });
      }
    },
  });
}

function lobby_notify(string, icono, classStyle, carpeta) {
  Lobibox.notify(classStyle, {
    size: "mini",
    rounded: true,
    delay: 4000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: false,
    img: "../../../../img/" + carpeta + icono,
    msg: string,
    sound: "../../../../../sounds/sound4",
  });

  return;
}

function validate_Permissions(pkPantalla) {
  var filtro = "";
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[7]; // informacion del estado de la cotizacion

    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else {
      return false;
    }
  });

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

      //PRODUCTOS
      if (pkPantalla == "13") {
        var html = "";
        var topButtons = [
          {
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          },
        ];
        if (_permissions.add == "1") {
          topButtons = [
            {
              text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
              className: "btn-custom--white-dark",
              action: function () {
                window.location.href = "agregar_venta.php";
              },
            },
            {
              extend: "excelHtml5",
              text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
              className: "btn-custom--white-dark",
              titleAttr: "Excel",
            },
          ];
        }
      }

      var tableVentas = $("#tblVentasDirectas").DataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 50,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false },
                      { orderable: false, targets: [2,3,4,5,6,7,8,9], class:"text-center" }],
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
            funcion: "get_ventaDirecta_Table",
            data: _permissions.edit,
            data2: _permissions.delete,
          },
        },
        columns: [
          { data: "Id" },
          { data: "Referencia" },
          { data: "Cliente" },
          { data: "RFC" },
          { data: "FechaEmision" },
          { data: "FechaVencimiento" },
          { data: "EstatusPago" },
          { data: "Importe" },
          { data: "EstatusVenta" },
          { data: "EstatusFactura" },
          { data: "Facturar" },
          { data: "Acciones", width: "1%" },
        ],
        order: [[0, 'desc']],
      });

      /* new $.fn.dataTable.Buttons(tableVentas, {
        dom: {
          button: {
            tag: "button",
            className: "btn-table-custom",
          },
          buttonLiner: {
            tag: null,
          },
        },
        buttons: [
          {
            text: '<i class="fas fa-globe"></i> Todas',
            className: "btn-table-custom--blue",
            action: function (e, dt, node, config) {
              filtro = "";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-archive"></i> Nueva',
            className: "btn-table-custom--blue-lightest",
            action: function (e, dt, node, config) {
              filtro = "Nueva";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-book-open"></i> Parcialmente surtida',
            className: "btn-table-custom--yellow",
            action: function (e, dt, node, config) {
              filtro = "Parcialmente surtida";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-check-circle"></i> Surtida completa',
            className: "btn-table-custom--green",
            action: function (e, dt, node, config) {
              filtro = "Surtida completa";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-archive"></i> Nueva FD',
            className: "btn-table-custom--blue-lightest",
            action: function (e, dt, node, config) {
              filtro = "Nueva FD";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-book-open"></i> Parcialmente surtida FD',
            className: "btn-table-custom--yellow",
            action: function (e, dt, node, config) {
              filtro = "Parcialmente surtida FD";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-check-circle"></i> Surtida completa FD',
            className: "btn-table-custom--green",
            action: function (e, dt, node, config) {
              filtro = "Surtida completa FD";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
          {
            text: '<i class="fas fa-times"></i> Cerrada',
            className: "btn-table-custom--red",
            action: function (e, dt, node, config) {
              filtro = "Cerrada";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
          {
            text: '<i class="far fa-arrow-alt-circle-up"></i> Facturada',
            className: "btn-table-custom--gray",
            action: function (e, dt, node, config) {
              filtro = "Facturada";
              $("#tblVentasDirectas").DataTable().draw();
            },
          },
        ],
      });

      tableVentas.buttons(1, null).container().appendTo("#btn-filters"); */
    },
  });
}

function isExport() {
  if (_permissions.export == "1") {
    return '<img class="readEditPermissions" type="submit" width="50px" id="btnExportPermissions" onclick="exportarPDF()" src="../../../../img/excel-azul.svg" />';
  } else {
    return "";
  }
}

function facturar(id) {
  let mensaje;
  let activo;

  if ($("#cbxFacturar-" + id).is(":checked")) {
    mensaje = "La venta será seleccionada para facturación directa.";
    activo = 1;
  } else {
    mensaje = "La venta ya no estará seleccionada para facturación directa.";
    activo = 2;
  }

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
      title: "¿Desea continuar?",
      text: mensaje,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        checkFD(id, activo);
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        if (activo == 1) {
          $("#cbxFacturar-" + id).prop("checked", false);
        } else {
          $("#cbxFacturar-" + id).prop("checked", true);
        }
      }
    });
}

function checkFD(id, activo) {
  var isCheck;

  if ($("#cbxFacturar-" + id).is(":checked")) {
    isCheck = "2";
  } else {
    isCheck = "1";
  }

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_ventaDirectaFD",
      data: id,
      data2: isCheck,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblVentasDirectas").DataTable().ajax.reload();
      } else {
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function idCopear(id){
  _global.idVenta = id;
}

function duplicarVenta(idVenta){
  _global.idVenta = idVenta
  $().redirect('agregar_venta.php', {
    'idVenta': _global.idVenta
  });
}

/* $(document).on("click", "#detalle_venta", function () {
  var data = $(this).data("id");

  window.location.href = "ver_ventas.php?vd=" + data;

   var win = window.location.href = "ver_ventas.php?vd="+data;
   if (win) {
      //Browser has allowed it to be opened
      win.focus();
  } else {
    //Browser has blocked it
    alert('Please allow popups for this website');
  } *
}); */