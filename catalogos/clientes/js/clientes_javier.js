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
      sNext: "<img src='../../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  $("#tblListadoClientes").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: "Excel",
      },
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_clientesTable" },
    },
    pageLength: 20,
    order: [0, "desc"],
    columns: [
      { data: "Id" },
      { data: "NombreComercial" },
      { data: "MedioContacto" },
      { data: "FechaAlta" },
      { data: "Vendedor" },
      { data: "Estatus" },
    ],
    columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 5, visible: false },
    ],
    rowCallback: function (row, data) {
      console.log("Estatus:" + data.Estatus.substr(25, 8));
      if (data.Estatus.substr(25, 8) == "Inactivo") {
        $($(row).find("td")[0]).css("background-color", "#cac8c6");
        $($(row).find("td label.textTable")[0]).attr(
          "style",
          "color: #FFFFFF!important"
        );
        $($(row).find("td label.textTable")[0]).attr(
          "title",
          "Cliente inactivo"
        );
      } else {
        $($(row).find("td")[0]).css("background-color", "#28c67a");
        $($(row).find("td label.textTable")[0]).attr(
          "style",
          "color: #FFFFFF!important"
        );
        $($(row).find("td label.textTable")[0]).attr("title", "Cliente activo");
      }
    },
  });

  $("#tblListadoClientes").DataTable().ajax.reload();
});

function obtenerIdClienteEditar(id) {
  window.location.href = "editar_cliente?c=" + id;
}

function obtenerIdClienteEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_generales_cliente",
      datos: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de datos de cliente: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      $("#txtNombre").val(data[0].NombreComercial);
      $("#txtClienteD").val(id);
    },
  });
}

function eliminarCliente() {
  var PKCliente = $("#txtClienteD").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "delete_data", funcion: "delete_Cliente", data: PKCliente },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de eliminar clientes: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      if (data[0].status) {
        $("#tblListadoClientes").DataTable().ajax.reload();
        Swal.fire(
          "Eliminación exitosa",
          "Se eliminó el cliente con exito",
          "success"
        );
      } else {
        Swal.fire("Error", "No se eliminó el cliente con exito", "warning");
      }
    },
  });
}
