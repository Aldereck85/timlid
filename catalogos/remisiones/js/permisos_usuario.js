$(document).ready(function(){
  var screen = $("body").data("screen");
  console.log(screen);
  $.ajax({
    url: "../../php_permisos/funciones.php",
    data: { 
      clase: "get_data", 
      funcion: "get_permissions", 
      value: screen
    },
    dataType: "json",
    success: function (respuesta) {
      var pantalla = quitarAcentos(respuesta[0].pantalla.replace(/\s+/g, ''));
      var modal = pantalla+"_"+screen;
      
      if(respuesta[0].funcion_agregar === 1){
        btn_agregar = '<div class="float-right">'+
                      '<div class="button-container2">'+
                        '<div class="button-icon-container">'+
                          '<a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"'+
                            'id="agregar_remision">'+
                            '<i class="fas fa-plus"></i>'+
                          '</a>'+
                        '</div>'+
                        '<div class="button-text-container">'+
                          '<span>Agregar remisión</span>'+
                        '</div>'+
                      '</div>'+
                    '</div>';
        
        $('.permission-view-add').addClass("card-header py-3");
        $('.permission-view-add').html(btn_agregar);
      }
    },

    error: function (error) {
      console.log(error);
    }
  });
});

function quitarAcentos(cadena){
	const acentos = {'á':'a','é':'e','í':'i','ó':'o','ú':'u','Á':'A','É':'E','Í':'I','Ó':'O','Ú':'U'};
	return cadena.split('').map( letra => acentos[letra] || letra).join('').toString();	
}