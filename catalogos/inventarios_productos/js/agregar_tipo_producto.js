/* VALIAR QUE NO SE REPITA EL TIPO DE PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarTipoProducto() {
    var valor = document.getElementById("txtTipoProducto").value;
    console.log('Valor tipo producto'+valor);
    $.ajax({
      url: '../../php/funciones.php',
      data:{clase:"get_data", funcion:"validar_tipoProducto", data:valor},
      dataType:"json",
      success: function(data) {
        console.log('respuesta tipo producto validado: ',data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]['existe']) == 1) {
  
          var agregar = document.getElementById("btnAgregarTipoProducto");
          agregar.style.display = 'none';
  
          var nota = document.getElementById("notaTipoProducto");
          nota.setAttribute('type', 'text');
  
          console.log('¡Ya existe!');
  
        } else {
  
          var agregar = document.getElementById("btnAgregarTipoProducto");
          agregar.style.display = 'block';
  
          var nota = document.getElementById("notaTipoProducto");
          nota.setAttribute('type', 'hidden');
  
          console.log('¡No existe!');
        }
  
      }
    });
  }
  
  /* Añadir el tipo de producto */
  function anadirTipoProducto(){
    var valor = document.getElementById("txtTipoProducto").value;
    console.log('Valor tipo de producto'+valor);
    $.ajax({
      url:"../../php/funciones.php",
      data:{clase:"save_data", funcion:"save_tipoProducto", datos:valor},
      dataType:"json",
      success:function(respuesta){
        console.log("respuesta agregar tipo de producto:",respuesta);
  
        if(respuesta[0].status){
          Swal.fire('Registro exitoso',"Se guardo el tipo de producto con exito","success");
          $('#tblListadoTipoProducto').DataTable().ajax.reload();
        }else{
          Swal.fire('Error',"No se guardó el tipo de producto con exito","warning");
        }
  
      },
      error:function(error){
        console.log(error);
      }
    });
  }