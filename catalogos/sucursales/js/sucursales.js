function setFormatDatatables(){
  var idioma_espanol = {
    "sProcessing": "Procesando...",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    "sLoadingRecords": "Cargando...",
    "searchPlaceholder": "Buscar...",
    "oPaginate": {
      "sFirst": "Primero",
      "sLast": "Último",
      "sNext": "<img src='../../img/icons/pagination.svg' width='20px'/>",
      "sPrevious": "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
    },
  }
  return idioma_espanol;
}

$(document).ready(function(){
  $("#tblSucursales").dataTable({
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
    "order": [
      [0, "desc"]
    ],
    "ajax": { 
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_sucursalTable" },
    },
    "columns": [{
        "data": "id"
      },
      {
        "data": "Sucursal"
      },
      {
        "data": "Domicilio"
      },
      {
        "data": "Colonia"
      },
      {
        "data": "Municipio"
      },
      {
        "data": "Estado"
      },
      {
        "data": "Pais"
      },
      {
        "data": "Telefono"
      }
    ],
    columnDefs: [{
      orderable: false,
      targets: 0,
      visible: false
    }],
    responsive: true,

  });
});