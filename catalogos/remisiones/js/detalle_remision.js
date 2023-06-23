$(document).ready(function(){
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_invoiceDetailTable",
      value:$("#txtIdFactura").val()
    },
    dataType: 'json',
    success: function(response){
      html = "";
      console.log(response.data);
      $.each(response.data,function(i){
        html += "<tr>"+
                  "<td>" + response.data[i].clave + "</td>"+
                  "<td>" + response.data[i].descripcion + "</td>"+
                  "<td>" + response.data[i].unidad_medida + "</td>"+
                  "<td>" + response.data[i].cantidad + "</td>"+
                  "<td>" + response.data[i].precio + "</td>"+
                  "<td>" + response.data[i].importe + "</td>"+
                "</tr>";
        console.log(response.data[i].clave);
      });

      $("#tblDetalleFactura tbody").append(html);
    },
    error:function(error){
      console.log(error);
    }
  });

  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_invoiceDetail",
      value:$("#txtIdFactura").val()
    },
    dataType: 'json',
    success: function(response){
      //res = JSON.parse(response);
      $("#serie_folio").append(" "+response.serie_folio);
      $("#fecha_timbrado").append(" "+response.fecha_creacion);
      $("#razon_social").append(" "+response.razon_social);
      $("#rfc").append(" "+response.rfc);
      $("#estatus").html("Estatus: "+response.estatus);
      $("#subtotales #subtotal").append(response.subtotal);
      
      $("#subtotal").html(response.subtotal);
      $("#impuestos").html(response.impuestos);
      $("#total").html(response.total);
      if(response.estatus === "Timbrada"){
        $("#btnModalCancelacion").css("display","block");
      }
      
    },
    error:function(error){
      console.log(error);
    }
  });
})