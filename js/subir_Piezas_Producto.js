jQuery(document).ready(function(){
  var id = $("#txtId").val();
  var estatus = $("#txtEstatus").val();
  var pedido = $("#txtPedido").val();
  $("#divAlert").hide();

  ////Conservar
  $('#lstProductos').load('actualizar_Lista.php?id='+id);
  $("#frmProductos").submit(function(e){
     e.preventDefault();
     var inputs = $(this).serialize();
     id = $("#txtId").val();
  });

  /*$("#btnAgregar").click(function(e){
    e.preventDefault();
    var cantidadPiezas = parseInt($('#txtCantidad').val());

    var inputs = $('#frmProductos').serialize();

    if(cantidadPiezas >= 0){
        $.ajax({
          type:"POST",
          url:"insertar_Piezas_Producto.php",
          data:inputs,
          success:function(data){
            $('#lstProductos').load('actualizar_Lista.php?id='+id);
            $( ".txtDisabled" ).prop( "disabled", true );
            alert("Pieza dada de alta");
          }
        });
    }
  });*/
  /*$.ajax({
    type:"POST",
    url:"calculo_guias.php",
    data:{id : id},
    success:function(data){
      $('#numero_guias').val(data);
    }
  });*/
});

function agregarProducto(){
  var cantidadPiezas = parseInt($('#txtCantidad').val());
  var id = parseInt($('#txtId').val());
  var inputs = $('#frmProductos').serialize();
      $.ajax({
        type:"POST",
        url:"insertar_Piezas_Producto.php",
        data:inputs,
        success:function(data){
          $('#lstProductos').load('actualizar_Lista.php?id='+id);
          $( ".txtDisabled" ).prop( "disabled", true );
          $("#divAlert").fadeIn(5000);
          $("#divAlert").fadeOut(5000);
        }
      });
}

function eliminarPieza(){
  var idPieza = parseInt($('#idPiezaElimina').val());
  var inputs = $('#frmPiezasEliminar').serialize();

  $.ajax({
    type:"POST",
    url:"eliminar_Pieza_Producto.php",
    data:inputs,
    success:function(data){
      $('#lstProductos').load('actualizar_Lista.php?id='+idPieza);
      $( ".txtDisabled" ).prop( "disabled", true );
      $("#divAlert").fadeIn(5000);
      $("#divAlert").fadeOut(5000);
    }
  });
}
