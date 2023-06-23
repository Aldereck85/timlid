jQuery(document).ready(function(){
  var id = $("#txtId").val();
  var estatus = $("#txtEstatus").val();
  var pedido = $("#txtPedido").val();
  $('#lstProductos').load('actualizar_Lista.php?id='+id+'&pedido='+pedido);
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
      url:"insertar_productos.php",
      data:inputs,
      success:function(data){
        $('#lstProductos').load('actualizar_Lista.php?id='+id+'&pedido='+pedido);
        $( ".txtDisabled" ).prop( "disabled", true );
      }
    });
  });

  $("#chosen").chosen().change(function(e, params){
    var values = $("#chosen").chosen().val();
    var folio = $("#txtFolio").val();
    //
    $('#divCantidad').load('delimitar_Envio.php?id='+values+'&factura='+folio);
    $( ".txtDisabled" ).prop( "disabled", true );
  });



});
