

jQuery(document).ready(function(){
  var idEmpleado = $("#txtId").val();
  var idSemana = $("#txtSemana").val();
      
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
      $("#tblHorasExtras").dataTable(
      {
        "ajax":"functions/function_horas_extras.php?id="+idEmpleado+"&semana="+idSemana,
          "columns":[
              {"data":"Dia"},
              {"data":"Fecha"},
              {"data":"Entrada"},
              {"data":"Salida"},
              {"data":"Horas trabajadas"},
              {"data":"Horas autorizadas"},
              {"data":"Responsable"},
              {"data":"Acciones"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 3 }
            ],
            destroy: true,
            responsive: true,
            "order": [[ 1, "asc" ]],
      }
      )
});