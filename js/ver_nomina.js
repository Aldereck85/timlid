jQuery(document).ready(function(){
      //var descuento = $("#txtDescuento").val();
      //$("#lblDescuento").text(descuento);
      var idEmpleado = $("#txtId").val();
      var idSemana = $("#txtSemana").val();

      /*var sellado = $("#txtSellado").val();

      if(sellado == 1){
        $("#btnAgregar").hide();
        $("#btnEliminar").show();
        //$(".btnJustificar").hide();
      }else{
        $("#btnAgregar").show();
        $("#btnEliminar").hide();
      }
      
      var numberEstatus = $("#txtContEstatus").val();
      var parcialidad = $("#txtParcialidades").val();
      $("#btnAgregarBono").hide();
      
      $("#btnEliminarBono").hide()
      $("#btnAgregarPago").hide();

      if(numberEstatus == 1){
        $("#lblDescuento").text($("#txtSalarioSem").val());
        $("#lblTotal").text("0.00");
        $("#txtSalario").val("0.00");
      }
      if(numberEstatus == 7){
        $("#btnAgregarBono").show();
      }

      if(parcialidad == 0.00){
        $("#btnEliminarPago").hide();
      }*/
      
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
      $("#tblVerNomina").dataTable(
        {
          "ajax":"functions/function_ver_nomina.php?id="+idEmpleado+"&semana="+idSemana,
            "columns":[
                {"data":"Dia"},
                {"data":"Fecha"},
                {"data":"Entrada"},
                {"data":"Salida a comer"},
                {"data":"Regreso de Comer"},
                {"data":"Salida"},
                {"data":"Tiempo a deber"},
                {"data":"Estatus"}
            ],
            "language": idioma_espanol,
              destroy: true,
              responsive: true,
              "order": [[ 1, "asc" ]],
        }
      )
});


   
  

   



