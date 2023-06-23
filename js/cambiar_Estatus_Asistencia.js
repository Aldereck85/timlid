jQuery(document).ready(function(){


  $(".btnJustificar").click(function(e){
    e.preventDefault();
    var inputs = $('#frmProductos').serialize();

    $.ajax({
      type:"POST",
      url:"actualizarEstatus.php",
      data:inputs,
      success:function(data){
      //  $('#lstProductos').load('actualizar_Lista.php?id='+id+'&pedido='+pedido);
        $( ".txtDisabled" ).prop( "disabled", true );
      }
    });
  });
});
