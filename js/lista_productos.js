function saludar(){
  var producto = $("#chosen").val();
  var cantidad = $("#txtCantidad").val();
  var descripcion = $( "#chosen option:selected" ).text();
  $( "#myselect option:selected" ).text();
  $( "#lstProductos" ).append( "<div class='row'><div class='col-lg-2'><label name='txtProducto'>"+producto+"</label></div><div class='col-lg-8'>"+descripcion+"</div><div class='col-lg-2'>"+cantidad+"</div></div>" );
}
