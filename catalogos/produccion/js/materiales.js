var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _globalG = {
  pkMaterial: 0,
};

$(document).ready(function () {
  validate_Permissions(58);
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
      if (pkPantalla == "58") {
        if (_permissions.add == "1") {
          topButtons.push({
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
            className: "btn-custom--white-dark",
            action: function () {
              window.location.href = "agregar_material.php";
            },
          });
        }
        if (_permissions.export == "1") {
          topButtons.push({
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          });
        }
      }

      $("#tblListaMateriales").dataTable({
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
            funcion: "get_listaMaterialesTable",
            data: _permissions.edit,
            data2: _permissions.delete,
          },
        },
        columns: [
          { data: "Id" },
          { data: "ClaveInterna" },
          { data: "Nombre" },
          { data: "Descripcion" },
          { data: "MarcaProducto" },
          { data: "CategoriaProductos" },
          { data: "TipoProducto" },
          { data: "CodigoBarras" },
          { data: "Estatus" },
          { data: "Imagen" },
          { data: "Acciones" }
        ],
        columnDefs: [
          { orderable: false, targets: 0, visible: false },
          { orderable: false, targets: 9, width: "150px" },
        ],
        rowCallback: function (row, data) {
          if (data.Estatus.substr(24, 1) == "I") {
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

function obtenerEditarListaMaterial(id) {
  window.location.href = "editar_material.php?lstm=" + id;
}

function obtenerDatosEliminarListaMaterial(id) {
  _globalG.pkMaterial = id;
}

function obtenerEliminarMaterial() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosListaMaterialEstatus",
      datos: _globalG.pkMaterial,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListaMateriales").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Material agregado correctamente!",
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
