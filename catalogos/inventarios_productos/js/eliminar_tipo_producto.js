function eliminarTipoProducto(){
    var id = $("#txtPKTipoProductoD").val();

    $.ajax({
        url:"../../php/funciones.php",
        data:{clase:"delete_data", funcion:"delete_tipoProducto", datos:id},
        dataType:"json",
        success:function(respuesta){
          console.log("respuesta eliminar tipo producto:",respuesta);
    
          if(respuesta[0].status){
    
            Swal.fire('Tipo de producto eliminado exitosamente',"Los datos del tipo de producto fueron eliminados con éxito.","success");
            $('#tblListadoTipoProducto').DataTable().ajax.reload();
          }else{
            Swal.fire({
              title: '<h3 style="color:white; arialRoundedEsp;">Error en la eliminación<h3>',
              html: '<h5 style="color:white; arialRoundedEsp;">La eliminación del tipo de producto no se pudo realizar.<h5>',
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