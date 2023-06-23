function eliminarCategoria(){
    var id = $("#txtPKCategoriaD").val();

    $.ajax({
        url:"../../php/funciones.php",
        data:{clase:"delete_data", funcion:"delete_categoria", datos:id},
        dataType:"json",
        success:function(respuesta){
          console.log("respuesta eliminar categoría:",respuesta);
    
          if(respuesta[0].status){
            //Swal.fire('Categoría eliminada exitosamente',"Los datos de la categoría fueron eliminados con éxito.","success");
            Lobibox.notify('success', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: true,
              img: '../../../../img/chat/notificacion_error.svg',
              msg: '¡Registro eliminado!',
              sound: '../../../../../sounds/sound4'
            });
            $('#tblListadoCategorias').DataTable().ajax.reload(),3000;
          }else{
            Lobibox.notify('fail', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: true,
              img: '../../../../img/chat/notificacion_error.svg',
              msg: '¡Algo salio mal!',
              sound: '../../../../../sounds/sound4'
            });
          }
    
        },
        error:function(error){
          console.log(error);
        }
      });
}