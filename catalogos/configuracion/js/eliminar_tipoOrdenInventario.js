function eliminarTipoOrdenInventario(){
    var id = $("#txtPKTipoOrdenInventarioD").val();

    $.ajax({
        url:"../inventarios_productos/php/funciones.php",
        data:{clase:"delete_data", funcion:"delete_tipoOrdenInventario", datos:id},
        dataType:"json",
        success:function(respuesta){
          console.log("respuesta eliminar tipoOrdenInventario:",respuesta);
          
          if(respuesta[0].status){
            /*Swal.fire('Tipo de orden eliminada exitosamente',"Los datos del tipo de orden fueron eliminados con éxito.","success");*/
            Lobibox.notify('error', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: true,
              img: '../../img/chat/notificacion_error.svg',
              msg: '¡Registro eliminado!'
            });
            
            $('#tblListadoTipoOrdenInventario').DataTable().ajax.reload();
          }else{
            Swal.fire({
              title: '<h3 style="color:white; arialRoundedEsp;">Error en la eliminación<h3>',
              html: '<h5 style="color:white; arialRoundedEsp;">La eliminación del tipo de orden no se pudo realizar.<h5>',
              icon: 'error',
              showConfirmButton: false,
              iconColor: '#fff',
              width: '100rem',
              position: 'top',
              background: '#d9534f',
              padding: '0',
              //timer: 5000
            }).then(
              function() {
                
              }
            );
          }
    
        },
        error:function(error){
          console.log(error);
        }
      });
}