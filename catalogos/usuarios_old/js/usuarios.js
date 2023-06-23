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

$(document).ready(function () {
    $("#tblUsuarios").dataTable({
        "language": setFormatDatatables(),
        "dom": "Bfrtip",
        "buttons": [{
            extend: "excelHtml5",
            text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
            className: "excelDataTableButton",
            titleAttr: "Excel",
        }],
        "scrollX": true,
        "lengthChange": false,
        "info": false,
        "ajax": { 
            url: "php/funciones.php",
            data: { clase: "get_data", funcion: "get_userTable" },
        },

        "columns": [{
            "data": "Id empleado"
          },
          {
            "data": "Nombres"
          },
          {
            "data": "Primer Apellido"
          },
          {
            "data": "Usuario"
          },
          {
            "data": "Rol"
          },
        ]
    });
});