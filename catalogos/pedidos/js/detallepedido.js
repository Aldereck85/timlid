
$(document).ready(function(){
  $.ajax({
    url:"php/funciones.php",
    data:{clase:"get_data", funcion:"get_estatusSalidaTraspasoPedido", data:OrdenPedidoID},
    dataType:"json",
    success:function(respuesta){
      var html = '';
      if (respuesta[0].estatus == '5' || respuesta[0].estatus == '6'){
        html = '<span class="btn-table-custom custom-color-blue noclick" name="btnAgregarProducto"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-SALIDAS AZUL NVO-01.svg"></img> Generar salida</span>'; 
      }else{
        html = '<span class="btn-table-custom custom-color-blue" name="btnAgregarProducto" onclick="generarSalida('+OrdenPedidoID+')"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-SALIDAS AZUL NVO-01.svg"></img> Generar salida</span>'; 
        
      }

      $("#generarSalida").html(html);
    },
    error:function(error){
      console.log(error);
    },
    complete: function(_, __) {
      
    }
  });
});