jQuery(document).ready(function(){

  $('#cmbPeriodo').on('change', function() {

    $("#tblEmpleados").dataTable(
    {
      "ajax":"actualizar_Lista.php",
        "columns":[
          {"data":"Id empleado"},
          {"data":"Fecha"},
          {"data":"Entrada"},
          {"data":"Salida Comida"},
          {"data":"Regreso Comida"},
          {"data":"Salida"},
          {"data":"Estatus"},
          {"data":"Acciones"}
        ],
          responsive: true
    }


  });


});
