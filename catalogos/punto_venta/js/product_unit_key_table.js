function loadProductUnitKeyTable(){
  search = $("#buscar_clave_unidad_medida").val();

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_clvProductUnit',
    },
    dataType: 'json',
    success: function(response){
      html = "";
      
      $.each(response,function(i){
          html += '<tr data-id="'+response[i].id+'">'+
                    '<td>'+response[i].clave+'</td>'+
                    '<td>'+response[i].descripcion+'</td>'+
                  '</tr>';
      });
      
      if($(search).val() !== ""){
        if(response.length > 0){
          $("#tabla_body_medida").html(html);
        } else if($(search).val() !== "" && html === ""){
          $("#tabla_body_medida").html('<tr><td colspan="2">No se encontraron coincidencias...</td></tr>');
        } 
      }
    },error:function(error){
      console.log(error);
    }

 });
  
}

$(document).on("keyup",'#buscar_clave_unidad_medida',function(){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_clvProductUnitSearch',
      value: $(this).val()
    },
    dataType: 'json',
    success: function(response){
     
      html = "";
      
      $.each(response,function(i){
          html += '<tr data-id="'+response[i].id+'">'+
                    '<td>'+response[i].clave+'</td>'+
                    '<td>'+response[i].descripcion+'</td>'+
                  '</tr>';
      });
      
      if($('#buscar_clave_unidad_medida').val() !== ""){
        if(response.length > 0){
          $("#tabla_body_medida").html(html);
        } else if($('#buscar_clave_sat').val() !== "" && html === ""){
          $("#tabla_body_medida").html('<tr><td colspan="2">No se encontraron coincidencias...</td></tr>');
        }
      } else {
        loadProductUnitKeyTable();
      }

    },error:function(error){
      console.log(error);
    }

  });
});

$(document).on('click', '#tabla_body_medida tr', function(){
  clave =$(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtUnidadMedida").val(sat);
  $("#txtUnidadMedidaId").val(id);
  document.getElementById('txtUnidadMedidaId').dispatchEvent(new Event('change'));
  $("#add_unit_product_key").modal("hide");
});