jQuery(document).ready(function(){

  var id = $("#txtId").val();
  var ingrediente = $("#cmbIngrediente").val();
  var cantidad = $("#txtCantidadIng").val();
  var unidad = $("#cmbUnidad").val();

  $('#lstProductos').load('actualizar_Lista.php?id='+id+'&cantidad='+cantidad);
  $("#frmProductos").submit(function(e){
     e.preventDefault();
     var inputs = $(this).serialize();
     id = $("#txtId").val();
  });

  $("#btnAgregar").click(function(e){
    e.preventDefault();
    var inputs = $('#frmProductos').serialize();

    $.ajax({
      type:"POST",
      url:"insertar_ingredienteFormula.php",
      data:inputs,
      success:function(data){
        $('#lstProductos').load('actualizar_Lista.php?id='+id+'&cantidad='+cantidad);
        $('#divtotal').load('total.php?id='+id);

      }
    });
  });

  $("#chosen").chosen().change(function(e, params){
    var values = $("#chosen").chosen().val();
    var folio = $("#txtFolio").val();
    //
    $('#divCantidad').load('delimitar_Envio.php?id='+values+'&factura='+folio+'&cantidad='+cantidad);
    $( ".txtDisabled" ).prop( "disabled", true );
  });
});
