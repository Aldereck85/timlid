var _global = {
  rutaServer: ''
}

$(document).ready(function(){

  $( window ).on( "load", function() {
    var anchopantalla = screen.width;
    var width1 = 400, width2 = 600, height1 = 400, height2 = 600;
    if(anchopantalla <= 750){
      width1 = 160;
      height1 = 160;
      width2 = 200;
      height2 = 200;
    }
  
    $image_crop = $('#image_demo').croppie({
      enableExif: true,
      viewport: {
        width:width1,
        height:height1,
        type:'square' //circle
      },
      boundary:{
        width:width2,
        height:height2
      }
    });

    $("#opEG-1").css({"background-color": "#28c67a", "color":"#FFFFFF"});
    $("#opEG-2").css({"background-color": "#cac8c6", "color":"#FFFFFF"});

    //Cambiar de color el combo del estatus al abrir por primera vez la página
    if($("#cmbEstatusProducto").val() == 1){
      $("#cmbEstatusProducto").css({"background-color": "#28c67a", "color":"#FFFFFF"});
    }else{
      
    $("#cmbEstatusProducto").css({"background-color": "#cac8c6", "color":"#FFFFFF"});
    }


    /*new SlimSelect({
      select: '#cmbAccionesProductoTemp',
      deselectLabel: '<span class="">✖</span>',
    });*/

    //setTimeout(ocultar, 1000);

    $('#imgFile').on('change', function(){
      var reader = new FileReader();
      reader.onload = function (event) {
        $image_crop.croppie('bind', {
          url: event.target.result
        }).then(function(){
          console.log('jQuery bind complete');
        });
      }
      reader.readAsDataURL(this.files[0]);
      $('#uploadimageModal').modal('show');
    });

    $('.crop_image').click(function(event){
      var imagenSubir = $("#imagenSubir").val();
  
      $image_crop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
      }).then(function(response){
        $.ajax({
          url:"uploadTemp.php",
          type: "POST",
          data:{"image": response, "imagenSubir": imagenSubir},
          success:function(data)
          {
            $('#uploadimageModal').modal('hide');

					  html = `<div class="mb-4" style="position: relative; width:350px; height:350px; display:block; margin:auto;">
                      <img class="z-depth-1-half img-thumbnail" src="${_global.rutaServer}${data}" alt="example placeholder" id="imgProd" name="imgProd" style=" position: absolute;">
                    </div>
                    <input type="hidden" id="imagenSubir" name="imagenSubir" value="${data}" /> `;

				    $('#espacioImagen').html(html);

          }
        });
      })
    });

  });

  /*function ocultar(){
    $("#loader").fadeOut("slow");
  }*/

});


/* VALIAR QUE NO SE REPITA La CATEOGRIA DE PRODUCTOS AGREGADO POR EL USUARIO EN AGREGAR */
function validarCategoria(valor) {
  console.log('Valor categoria'+valor);
  $.ajax({
    url: '../../php/funciones.php',
    data:{clase:"get_data", funcion:"validar_categoriaProducto", data:valor},
    dataType:"json",
    success: function(data) {
      console.log('respuesta categoría validado: ',data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]['existe']) == 1) {

        console.log('¡Ya existe!');

      } else {

        console.log('¡No existe!');

        anadirCategoria(valor);
      }

    }
  });
}

/* Añadir la categoría */
function anadirCategoria(valor){
  
  console.log('Valor categoria'+valor);
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"save_data", funcion:"save_categoria", datos:valor},
    dataType:"json",
    success:function(respuesta){
      console.log("respuesta agregar categoria de producto:",respuesta);

      if(respuesta[0].status){
        Swal.fire('Registro exitoso',"Se guardo la categoría con exito","success");
        cargarCMBCategoria(valor,'cmbCategoriaProducto');
      }else{
        Swal.fire('Error',"No se guardó la categoría con exito","warning");
      }

    },
    error:function(error){
      console.log(error);
    }
  });
}

/* VALIAR QUE NO SE REPITA LA MARCA DE PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarMarca(valor) {
  console.log('Valor marca'+valor);
  $.ajax({
    url: '../../php/funciones.php',
    data:{clase:"get_data", funcion:"validar_marcaProducto", data:valor},
    dataType:"json",
    success: function(data) {
      console.log('respuesta marca validado: ',data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]['existe']) == 1) {

        console.log('¡Ya existe!');

      } else {

        console.log('¡No existe!');

        anadirMarca(valor);
      }

    }
  });
}

/* Añadir la marca */
function anadirMarca(valor){
  
  console.log('Valor marca'+valor);
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"save_data", funcion:"save_marca", datos:valor},
    dataType:"json",
    success:function(respuesta){
      console.log("respuesta agregar marca de producto:",respuesta);

      if(respuesta[0].status){
        Swal.fire('Registro exitoso',"Se guardo la marca con exito","success");
        cargarCMBMarca(valor,'cmbMarcaProducto');
      }else{
        Swal.fire('Error',"No se guardó la marca con exito","warning");
      }

    },
    error:function(error){
      console.log(error);
    }
  });
}

/* VALIAR QUE NO SE REPITA EL TIPO DE PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarTipoProducto(valor) {
  console.log('Valor tipo producto'+valor);
  $.ajax({
    url: '../../php/funciones.php',
    data:{clase:"get_data", funcion:"validar_tipoProducto", data:valor},
    dataType:"json",
    success: function(data) {
      console.log('respuesta marca validado: ',data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]['existe']) == 1) {

        console.log('¡Ya existe!');

      } else {

        console.log('¡No existe!');

        anadirTipoProducto(valor);
      }

    }
  });
}

/* Añadir el tipo de producto */
function anadirTipoProducto(valor){
  
  console.log('Valor tipo producto'+valor);
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"save_data", funcion:"save_tipoProducto", datos:valor},
    dataType:"json",
    success:function(respuesta){
      console.log("respuesta agregar tipo de producto:",respuesta);

      if(respuesta[0].status){
        Swal.fire('Registro exitoso',"Se guardo el tipo de producto con exito","success");
        cargarCMBTipo(valor,'cmbTipoProducto');
      }else{
        Swal.fire('Error',"No se guardó el tipo de producto con exito","warning");
      }

    },
    error:function(error){
      console.log(error);
    }
  });
}

/* VALIAR QUE NO SE REPITA EL TIPO DE ORDEN DE INVENTARIO AGREGADO POR EL USUARIO EN AGREGAR */
function validarTipoOrdenInventario(valor) {
  console.log('Valor tipo orden inventario'+valor);
  $.ajax({
    url: '../../php/funciones.php',
    data:{clase:"get_data", funcion:"validar_tipoOrdenInventario", data:valor},
    dataType:"json",
    success: function(data) {
      console.log('respuesta tipo orden inventario validado: ',data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]['existe']) == 1) {

        console.log('¡Ya existe!');

      } else {

        console.log('¡No existe!');

        anadirTipoOrdenInventario(valor);
      }

    }
  });
}

/* Añadir el tipo de orden de inventario */
function anadirTipoOrdenInventario(valor){
  
  console.log('Valor tipo orden inventario'+valor);
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"save_data", funcion:"save_tipoOrdenInventario", datos:valor},
    dataType:"json",
    success:function(respuesta){
      console.log("respuesta agregar tipo orden inventario:",respuesta);

      if(respuesta[0].status){
        Swal.fire('Registro exitoso',"Se guardo el tipo de orden de inventario con exito","success");
        cargarCMBTipoOrden(valor,'cmbTipoInventario');
      }else{
        Swal.fire('Error',"No se guardó el tipo de orden de inventario con exito","warning");
      }

    },
    error:function(error){
      console.log(error);
    }
  });
}

$(document).on('change','#cmbCategoriaProducto',function(){
  var categoria = $("#cmbCategoriaProducto").val();

  console.log('Selección:'+categoria);
  if(categoria =='add'){
    window.location.href = "../categoria_productos";
  }
  
});

$(document).on('change','#cmbMarcaProducto',function(){
  var categoria = $("#cmbMarcaProducto").val();

  console.log('Selección:'+categoria);
  if(categoria =='add'){
    window.location.href = "../marca_productos";
  }
  
});

$(document).on('change','#cmbTipoProducto',function(){
  var categoria = $("#cmbTipoProducto").val();

  console.log('Selección:'+categoria);
  if(categoria =='add'){
    window.location.href = "../../../configuracion/tipo_productos";
  }
  
});

$(document).on('change','#cmbTipoInventario',function(){
  var categoria = $("#cmbTipoInventario").val();

  console.log('Selección:'+categoria);
  if(categoria =='add'){
    window.location.href = "../../../configuracion/tipos_orden_inventarios";
  }
  
});