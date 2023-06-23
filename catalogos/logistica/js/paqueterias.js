var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _globalG = {
  pkPaqueteria: 0,
};

$(document).ready(function () {
  validate_Permissions(41);
});

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

function validate_Permissions(pkPantalla) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      var topButtons = [];
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;

      //PRODUCTOS
      if (pkPantalla == "41") {
        if (_permissions.add == "1") {
          topButtons.push({
            text: '<i class="fas fa-plus-square"></i> Añadir registro',
            className: "btn-table-custom--blue",
            action: function () {
              window.location.href = "agregar_paqueteria.php";
            },
          });
        }
        if (_permissions.export == "1") {
          topButtons.push({
            extend: "excelHtml5",
            text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
            className: "btn-table-custom--turquoise",
            titleAttr: "Excel",
          });
        }
      }

      $("#tblPaqueterias").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 50,
        responsive: true,
        lengthChange: false,
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
          buttons: topButtons,
        },
        ajax: {
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_paqueteriasTable",
            data: _permissions.edit,
            data2: _permissions.delete,
          },
        },
        columns: [
          { data: "Id" },
          { data: "Estatus" },
          { data: "NombreComercial" },
          { data: "Telefono" },
          { data: "RazonSocial" },
          { data: "Email" },
          { data: "RFC" },
          { data: "Calle" },
          { data: "NumeroExt" },
          { data: "NumeroInt" },
          { data: "Colonia" },
          { data: "CP" },
          { data: "Municipio" },
          { data: "Estado" },
          { data: "Pais" },
          { data: "Acciones" },
        ],
        columnDefs: [
          { orderable: false, targets: 0, visible: false },
          { orderable: false, targets: 1, visible: false },
        ],
        rowCallback: function (row, data) {
          if (data.Estatus.substr(25, 1) == "0") {
            $($(row).find("td span.textTable")[0]).addClass("left-dot gray-dot");
          } else {
            $($(row).find("td span.textTable")[0]).addClass("left-dot green-dot");
          }
        },
      });
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

function obtenerEditarPaqueteria(id) {
  window.location.href = "editar_paqueteria.php?pq=" + id;
}

function obtenerDatosEliminarPaqueteria(id) {
  _globalG.pkPaqueteria = id;
}

function obtenerEliminarPaqueteria() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosPaqueteriaEstatus",
      datos: _globalG.pkPaqueteria,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblPaqueterias").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Paquetería inactivada correctamente!",
          sound: "../../../../../sounds/sound4",
        });
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
        sound: "../../../../../sounds/sound4",
      });
    },
  });
}
