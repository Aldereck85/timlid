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
      var table;
      var semana = $("#txtId").val();
      $("#tblEmpleados").hide();

        $("#tblEmpleados").show();
        var idioma_espanol = {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
        $("#tblEmpleados").dataTable(
          {
            "ajax":"functions/function_empleados.php?turno=6&semana="+semana,
              "columns":[
                {"data":"Id empleado"},
                {"data":"Primer nombre"},
                {"data":"Segundo nombre"},
                {"data":"Apellido paterno"},
                {"data":"Apellido materno"},
                {"data":"Puesto"},
                {"data":"Acciones"}
              ],
              "language": idioma_espanol,
                columnDefs: [
                  { orderable: false, targets: 6 }
                ],
                responsive: true,
                destroy: true,
          }
        )

        $("#tblVespertino").dataTable(
        {
          "ajax":"functions/function_empleados.php?turno=2&semana="+semana,
            "columns":[
              {"data":"Id empleado"},
              {"data":"Primer nombre"},
              {"data":"Segundo nombre"},
              {"data":"Apellido paterno"},
              {"data":"Apellido materno"},
              {"data":"Puesto"},
              {"data":"Acciones"}
            ],
            "language": idioma_espanol,
              columnDefs: [
                { orderable: false, targets: 6 }
              ],
              responsive: true,
              destroy: true,
        }
        )

        $("#tblMixto").dataTable(
        {
          "ajax":"functions/function_empleados.php?turno=7&semana="+semana,
            "columns":[
              {"data":"Id empleado"},
              {"data":"Primer nombre"},
              {"data":"Segundo nombre"},
              {"data":"Apellido paterno"},
              {"data":"Apellido materno"},
              {"data":"Puesto"},
              {"data":"Acciones"}
            ],
            "language": idioma_espanol,
              columnDefs: [
                { orderable: false, targets: 6 }
              ],
              responsive: true,
              destroy: true,
        }
        )

        $("#tblNocturno").dataTable(
        {
          "ajax":"functions/function_empleados.php?turno=1&semana="+semana,
            "columns":[
              {"data":"Id empleado"},
              {"data":"Primer nombre"},
              {"data":"Segundo nombre"},
              {"data":"Apellido paterno"},
              {"data":"Apellido materno"},
              {"data":"Puesto"},
              {"data":"Acciones"}
            ],
            "language": idioma_espanol,
              columnDefs: [
                { orderable: false, targets: 6 }
              ],
              responsive: true,
              destroy: true,
        }
        )


});