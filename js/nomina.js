

jQuery(document).ready(function(){
      $("#btnHide").attr("disabled", true);
      //var descuento = $("#txtDescuento").val();
      //$("#lblDescuento").text(descuento);
      var idEmpleado = $("#txtId").val();
      var idSemana = $("#txtSemana").val();

      var sellado = $("#txtSellado").val();

      if(sellado == 1){
        $("#btnAgregarPago").hide();
        $("#btnEliminarPago").hide();
        $("#btnAgregar").hide();
        $("#btnEliminar").show();

        //$(".btnJustificar").hide();
      }else{
        $("#btnAgregar").show();
        $("#btnEliminar").hide();
      }
      
      var numberEstatus = $("#txtContEstatus").val();
      var contExcelente = $("#txtContExcelente").val();
      var bonoExiste = $("#txtBonoExiste").val();

      var parcialidad = $("#txtParcialidades").val();
      $("#btnAgregarBono").hide();
      
      $("#btnEliminarBono").hide()
      $("#btnAgregarPago").hide();

      if(numberEstatus == 1){
        $("#lblDescuento").text($("#txtSalarioSem").val());
        $("#lblTotal").text("0.00");
        $("#txtSalario").val("0.00");
      }
      if(numberEstatus == 7 && contExcelente > 0 && bonoExiste == 1){
        $("#btnAgregarBono").show();
      }

      if(parcialidad == 0.00){
        $("#btnEliminarPago").hide();
      }
      
      if(sellado == 1){
        $("#btnAgregarBono").hide();
        $("#btnEliminarBono").hide();
      }

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
        "ajax":"functions/function_nomina_directa.php?id="+idEmpleado+"&semana="+idSemana,
          "columns":[
              {"data":"Dia"},
              {"data":"Fecha"},
              {"data":"Fecha Sin Formato"},
              {"data":"Entrada"},
              {"data":"Salida a comer"},
              {"data":"Regreso de Comer"},
              {"data":"Salida"},
              {"data":"Tiempo a deber"},
              {"data":"Estatus"},
              {"data":"Acciones"}
          ],
          "language": idioma_espanol,
            columnDefs: [
              { orderable: false, targets: 9 },
              { targets: 2, "visible": false,  "searchable": false }
            ],
            destroy: true,
            responsive: true,
            "order": [[ 2, "asc" ]],
      }
      )
});


$(window).on('load', function() {
  var sellado = $("#txtSellado").val();
    if(sellado == 1){
      $(".btnJustificar").hide();
    }
 });


function justificarFalta(idChecada,estatus) {
    var idEmpleado = $("#txtId").val();
    var semana = $("#txtSemana").val();
    var inputs = "Hola";
    $.ajax({
      type:"POST",
      url:"actualizarEstatus.php?id="+idChecada+"&estatus="+estatus+"&idEmpleado="+idEmpleado,
      data:inputs,
      success:function(data){
      window.location.reload();
      }
    });
  }

  function agregarHora(idHoraExtra,estatus) {
    var inputs = "Hola";
    $.ajax({
      type:"POST",
      url:"agregarHora.php?idHoraExtra="+idHoraExtra+"&estatus="+estatus,
      data:inputs,
      success:function(data){
      window.location.reload();
      }
    });
  }


    function agregarBono(){
      $("#btnAgregarBono").hide();
      $("#btnEliminarBono").show();
      $("#txtBonoAgregado").val(1);
      var id = $("#txtId").val();
      var salarioSemanal = parseFloat($("#txtSalarioSem").val());
      var horasExtras = parseFloat($("#lblHorasExtras").text());
      var dobleTurno = parseFloat($("#lblDobleTurno").text());
      var bonoPre = $("#txtBonoPreAprobado").val();
      var bono = parseFloat(bonoPre);

      var descuentoImproductividad = parseFloat($("#lblDescuento").text());
      var descuentoDeudaInterna = parseFloat($("#lblDeudaInterna").text());
      var descuentoInfonavit = parseFloat($("#lblInfonavit").text());

      var diasTrabajados = $("#txtDiasTrabajados").val();
      
      $("#lblBono").text(bono.toFixed(2));
      $("#txtBono").val(bono.toFixed(2));

      $.ajax({
        type:"POST",
        url:"calculo_nomina_directa_js.php",
        data: { id : id, horasExtras : horasExtras, dobleTurno : dobleTurno, salarioSemanal : salarioSemanal, bono : bono, descuentoImproductividad: descuentoImproductividad, descuentoDeudaInterna: descuentoDeudaInterna, descuentoInfonavit : descuentoInfonavit, diasTrabajados : diasTrabajados},
        success:function(data){
          var datos = JSON.parse(data);
          $('#lblISR').html(datos.ISRSemana);
          $('#txtISR').val(datos.ISRSemana);
          $('#lblIMSS').html(datos.cuota_obrero);
          $('#txtIMSS').val(datos.cuota_obrero);
          $('#lblTotal').html(datos.neto_a_pagar); 
          $('#txtSalario').val(datos.neto_a_pagar);   
        }
      });

    }

    function eliminarBono(){
      $("#btnEliminarBono").hide();
      $("#btnAgregarBono").show();
      $("#txtBonoAgregado").val(0);
      $("#lblBono").text("0.00");
      $("#txtBono").val("0.00");
      //$("#txtBonoPreAprobado").val("0.00");

      var id = $("#txtId").val();
      var salarioSemanal = parseFloat($("#txtSalarioSem").val());
      var horasExtras = parseFloat($("#lblHorasExtras").text());
      var dobleTurno = parseFloat($("#lblDobleTurno").text());
      var bono = parseFloat($("#txtBono").val());

      var descuentoImproductividad = parseFloat($("#lblDescuento").text());
      var descuentoDeudaInterna = parseFloat($("#lblDeudaInterna").text());
      var descuentoInfonavit = parseFloat($("#lblInfonavit").text());
      var diasTrabajados = $("#txtDiasTrabajados").val();

      $.ajax({
        type:"POST",
        url:"calculo_nomina_directa_js.php",
        data: { id : id, horasExtras : horasExtras, dobleTurno : dobleTurno, salarioSemanal : salarioSemanal, bono : bono, descuentoImproductividad: descuentoImproductividad, descuentoDeudaInterna: descuentoDeudaInterna, descuentoInfonavit : descuentoInfonavit, diasTrabajados : diasTrabajados},
        success:function(data){
          var datos = JSON.parse(data);
          $('#lblISR').html(datos.ISRSemana);
          $('#txtISR').val(datos.ISRSemana);
          $('#lblIMSS').html(datos.cuota_obrero);
          $('#txtIMSS').val(datos.cuota_obrero);
          $('#lblTotal').html(datos.neto_a_pagar);   
          $('#txtSalario').val(datos.neto_a_pagar);   
        }
      });

    }

    function eliminarPagoDeuda(){
      $("#btnEliminarPago").hide();
      $("#btnAgregarPago").show();
      $("#lblDeudaInterna").text("0.00");
      $("#txtDeudaInterna").val("0.00");
      
      var descuentoDeudaInterna = parseFloat($("#lblDeudaInterna").text());
      var salarioSemanal = parseFloat($("#txtSalarioSem").val());
      var salarioTotal = parseFloat($("#lblTotal").text());
      var bono = parseFloat($("#lblBono").text());
      var horasExtras = parseFloat($("#lblHorasExtras").text());
      var dobleTurno = parseFloat($("#lblDobleTurno").text());
      var descuentoImproductividad = parseFloat($("#lblDescuento").text());
      var descuentoInfonavit = parseFloat($("#lblInfonavit").text());
      salarioTotal = salarioSemanal + bono + horasExtras + dobleTurno - descuentoImproductividad - descuentoInfonavit - descuentoDeudaInterna;
      $("#lblTotal").text(salarioTotal.toFixed(2));
      $("#txtSalario").val(salarioTotal.toFixed(2));
    }

    function agregarPago(){
      $("#btnAgregarPago").hide();
      $("#btnEliminarPago").show();
      var deuda = parseFloat($("#txtParcialidades").val());
      $("#lblDeudaInterna").text(deuda.toFixed(2));
      $("#txtDeudaInterna").val(deuda.toFixed(2));
      var descuentoDeudaInterna = parseFloat($("#lblDeudaInterna").text());
      var salarioSemanal = parseFloat($("#txtSalarioSem").val());
      var salarioTotal = parseFloat($("#lblTotal").text());
      var bono = parseFloat($("#lblBono").text());
      var horasExtras = parseFloat($("#lblHorasExtras").text());
      var dobleTurno = parseFloat($("#lblDobleTurno").text());
      var descuentoImproductividad = parseFloat($("#lblDescuento").text());
      var descuentoInfonavit = parseFloat($("#lblInfonavit").text());
      salarioTotal = salarioSemanal + bono + horasExtras + dobleTurno - descuentoImproductividad - descuentoInfonavit - descuentoDeudaInterna;
      $("#lblTotal").text(salarioTotal.toFixed(2));
      $("#txtSalario").val(salarioTotal.toFixed(2));
    }


