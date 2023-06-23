$(document).ready(function(){
  loadAlertsNoti('alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());
  setInterval(loadAlertsNoti, 10000, 'alertaTareas', $('#txtUsuario').val(), $('#txtRuta').val(), $('#txtEdit').val());

  $("#tblEnvios").dataTable({
    "language": setFormatDatatables(),
    "dom": "Bfrtip",
    "buttons": [{
      extend: 'excelHtml5',
      text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
      titleAttr: 'Excel',
    }],
    "scrollX": true,
    "lengthChange": false,
    "info": false,
    "ajax": "../functions/function_Envios.php",
    "columns": [
      {
        "data": "Id de envio"
      },
      {
        "data": "Numero de rastreo"
      },
      {
        "data": "Estatus"
      },
      {
        "data": "Factura"
      },
      {
        "data": "Paqueteria"
      },
      {
        "data": "Fecha Envio"
      },
      {
        "data": "Fecha Entrega"
      },
      {
        "data": "Acciones"
      }
    ]
  })
});


function setFormatDatatables(){
  var idioma_espanol = {
    "sProcessing": "Procesando...",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    "sLoadingRecords": "Cargando...",
    searchPlaceholder: "Buscar...",
    "oPaginate": {
      "sFirst": "Primero",
      "sLast": "Último",
      "sNext": "<img src='../../img/icons/pagination.svg' width='20px'/>",
      "sPrevious": "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
    }
  }
  return idioma_espanol;
}