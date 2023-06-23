 //Ejecutar al cargar la de agregar claves SAT (Nota: Se carga junto con la página que la posee)
 function cargarTablaAcciones(id){
    console.log("Presionó 2");
   $("#tblListadoAccionesProducto").dataTable({
       "lengthChange": false,
       "pageLength": 15,
       //"paging": true,
       "info": false,
       "pagingType": "full_numbers",
       "ajax": {
           url:"../../php/funciones.php",
           data:{clase:"get_data", funcion:"get_accionProductoTable", data:id},
       },
       "columns":[
           { "data": "Id" },
           { "data": "TipoProducto" },

       ],
       "language": setFormatDatatables(),
       columnDefs: [
           { orderable: false, targets: 0, visible: false },
       ],
       responsive: true,
   });
}
function cargarTablaAccionesTemp(id){
   $("#tblListadoAccionesProductoTemp").dataTable({
       "lengthChange": false,
       "pageLength": 15,
       "searching": false,
       //"paging": true,
       "info": false,
       "pagingType": "full_numbers",
       "ajax": {
           url:"../../php/funciones.php",
           data:{clase:"get_data", funcion:"get_accionProductoTableTemp", data:id},
       },
       "columns":[
           { "data": "Id" },
           { "data": "TipoProducto" },

       ],
       "language": setFormatDatatables(),
       columnDefs: [
           { orderable: false, targets: 0, visible: false },
       ],
       responsive: true,
   });
}
 
//Funciones
 
//Función de data table
function setFormatDatatables(){
   var idioma_espanol = {
       "searchPlaceholder": "Buscar...",
       "sSearch": "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
       "sLengthMenu":     "",//Mostrar _MENU_ registros
       "sZeroRecords":    "No se encontraron resultados",
       "sEmptyTable":     "Ningún dato disponible en esta tabla",
       "sInfo":           "",//Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros
       "sInfoEmpty":      "",//Mostrando registros del 0 al 0 de un total de 0 registros
       "sInfoFiltered":   "",//(filtrado de un total de _MAX_ registros)
       "sInfoPostFix":    "",
       "sUrl":            "",
       "sInfoThousands":  ",",
       "sLoadingRecords": "Cargando...",
       "oPaginate": {
           "sFirst":    "",//Primero
           "sLast":     "",//Último
           "sNext":     "<img src='../../../../img/icons/pagination.svg' width='15px'>",
           "sPrevious": "<img src='../../../../img/icons/pagination.svg' width='15px' style='-webkit-transform: scaleX(-1); transform=scaleX(-1);'>"
       },
       "oAria": {
           "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
           "sSortDescending": ": Activar para ordenar la columna de manera descendente"
       }
   }
   return idioma_espanol;
}