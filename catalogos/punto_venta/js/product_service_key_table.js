function loadProductServiceKeyTable(){
  search = $("#buscar_clave_sat").val();

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_clvProductServ',
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
          $("#tabla_body_sat").html(html);
        } else if($(search).val() !== "" && html === ""){
          $("#tabla_body_sat").html('<tr><td colspan="2">No se encontraron coincidencias...</td></tr>');
        } 
      }
    },error:function(error){
      console.log(error);
    }

 });
  
}

$(document).on("keyup",'#buscar_clave_sat',function(){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_clvProductServSearch',
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
      
      if($('#buscar_clave_sat').val() !== ""){
        if(response.length > 0){
          $("#tabla_body_sat").html(html);
        } else if($('#buscar_clave_sat').val() !== "" && html === ""){
          $("#tabla_body_sat").html('<tr><td colspan="2">No se encontraron coincidencias...</td></tr>');
        }
      } else {
        loadProductServiceKeyTable();
      }

    },error:function(error){
      console.log(error);
    }

 });
});

$(document).on('click', '#tabla_body_sat tr', function(){
  clave =$(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtClaveSat").val(sat);
  $("#txtClaveSatId").val(id);
  document.getElementById('txtClaveSatId').dispatchEvent(new Event('change'));
  $("#add_producto_service_key").modal("hide");

});