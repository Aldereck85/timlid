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
    var maxcantidadcajas = parseInt($('#txtCantidad').attr('max'));
    var cantidadcajas = parseInt($('#txtCantidad').val());
    var maxcantidadpiezas = parseInt($('#txtCantidadPiezas').attr('max'));
    var cantidadpiezas = parseInt($('#txtCantidadPiezas').val());

    var inputs = $('#frmProductos').serialize();

    if(cantidadcajas > maxcantidadcajas){
      $("#errorcantidad").css("display", "block");
      setTimeout(function(){  $("#errorcantidad").css("display", "none"); }, 2000);
      return;
    } 

    if(cantidadpiezas > maxcantidadpiezas){
      $("#errorcantidadpiezas").css("display", "block");
      setTimeout(function(){  $("#errorcantidadpiezas").css("display", "none"); }, 2000);
      return;
    }

    if(cantidadcajas >= 0){
        $.ajax({
          type:"POST",
          url:"insertar_productos.php",
          data:inputs,
          success:function(data){
            $('#lstProductos').load('actualizar_Lista.php?id='+id+'&pedido='+pedido);
            $( ".txtDisabled" ).prop( "disabled", true );

            $.ajax({
              type:"POST",
              url:"calculo_guias.php",
              data:{id : id},
              success:function(data){
                $('#numero_guias').val(data);
              }
            });


          }
        });
    }
  });

  $("#chosen").chosen().change(function(e, params){
    var values = $("#chosen").chosen().val();
    var folio = $("#txtFolio").val();
    //
    $('#divCantidad').load('delimitar_Envio.php?id='+values+'&factura='+folio+'&pedido='+pedido);
    $( ".txtDisabled" ).prop( "disabled", true );
  });



});
