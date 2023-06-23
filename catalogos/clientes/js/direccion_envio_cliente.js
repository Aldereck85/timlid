var _global = {
  id: 0,
  permisionEdit: 0,
  idContato: 0,
}

function cargarTablaDireccionesEnvio(id, _permissionsEdit) {
  _global.id = id;
  _global.permisionEdit = _permissionsEdit;
  $("#tblListadoDatosDireccionesEnvioProveedor").DataTable().destroy();
  $("#tblListadoDatosDireccionesEnvioProveedor").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [
      { orderable: false, targets: [0,12], visible: false },
      { orderable: false, targets: 10, width: "100px" },
    ],
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
      buttons: [{
        extend: "excelHtml5",
        text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar Excel</span>',
        className: "btn-custom--white-dark btn-custom",
        titleAttr: "Excel",
        exportOptions: {
          columns: ":visible",
        },
      }],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_direccionEnvio_clientesTable",
        data: id,
        data2: _permissionsEdit,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Predeterminar" },
      { data: "Sucursal" },
      { data: "Email" },
      { data: "Calle" },
      { data: "NumeroExt" },
      { data: "NumeroInt" },
      { data: "Colonia" },
      { data: "Municipio" },
      { data: "Estado" },
      { data: "Pais" },
      { data: "CP" },
      { data: "Acciones", width: "5%" },
    ],
  });

  //$("#tblListadoDatosDireccionesEnvioProveedor").DataTable().ajax.reload();
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
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

function seleccionarPredeterminado(idContato){
  _global.idContato = idContato;

  $("#cbxPred-"+idContato).prop("checked", false);
}

function predeterminarDireccion(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosClientePredeterminado",
      datos: _global.idContato
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Contacto predeterminado correctamente!",
          sound: '../../../../../sounds/sound4'
        });

        cargarTablaDireccionesEnvio(_global.id, _global.permisionEdit);
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
    },
  });
}