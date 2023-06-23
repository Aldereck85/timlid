$(document).ready(function(){

    var html ="";
    var selected;
    $.ajax({
        url:"../../php/funciones.php",
        data:{clase:"get_data", funcion:"get_cmb_estatusGral"},
        dataType:"json",
        success:function(respuesta){
        console.log("respuesta estatus: ",respuesta);
  
        $.each(respuesta,function(i){
  
            html += '<option id="opEG-'+respuesta[i].PKEstatusGeneral+'" value="'+respuesta[i].PKEstatusGeneral+'" '+selected+'>'+respuesta[i].Estatus+'</option>';
        
        });
  
        $('#cmbEstatusTipoProducto').append(html);
  
        },
        error:function(error){
        console.log(error);
        }
    });
  
  });
  
  function obtenerIdTipoProductoEditar(id) {
    document.getElementById('txtPKTipoProducto').value = id;
    document.getElementById('txtPKTipoProductoD').value = id;
  
    $.ajax({
        url:"../../php/funciones.php",
        data:{clase:"get_data", funcion:"get_datos_tipoProducto", datos:id},
        dataType:"json",
        success:function(respuesta){
            console.log("respuesta datos a editar de un tipo producto: ",respuesta);
  
            $("#txtTipoProductoU").val(respuesta[0].tipoProducto);
            $("#cmbEstatusTipoProducto").val(respuesta[0].estatus);
            $("#txtTipPActual").val(respuesta[0].tipoProducto);
            $("#txtTipoProductoD").val(respuesta[0].tipoProducto);
  
            if(respuesta[0].estatus == 1){
              console.log("Es Activo");
              $("#cmbEstatusTipoProducto").css({"background-color": "#28c67a", "color":"#FFFFFF"});
            }else{
              console.log("Es Inactivo");
              $("#cmbEstatusTipoProducto").css({"background-color": "#cac8c6"});
            }
  
            $("#opEG-1").css({"background-color": "#28c67a", "color":"#FFFFFF"});
            $("#opEG-2").css({"background-color": "#cac8c6"});
  
            if(respuesta[0].noEliminar == 1){
              var eliminar = document.getElementById("btnEliminarTipoProductoU");
              eliminar.style.display = 'none';
  
              $('#cmbEstatusTipoProducto').attr('disabled',true);
  
              var nota = document.getElementById("notaEstatusU");
              nota.setAttribute('type', 'text');
  
            }else{
              var eliminar = document.getElementById("btnEliminarTipoProductoU");
              eliminar.style.display = 'block';
              $('#cmbEstatusTipoProducto').attr('disabled',false);
  
              var nota = document.getElementById("notaEstatusU");
              nota.setAttribute('type', 'hidden');
            }
        },
        error:function(error){
            Swal.fire('Error',"No se pudo acceder a los datos del tipo de producto","error");
            console.log(error);
        }
    });
  }
  
  //Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
  $(document).on('click','#btnEditarTipoProducto',function(){
    var estatus = $("#cmbEstatusTipoProducto").val();
    var tipoProducto = $("#txtTipoProductoU").val();
    var id = $("#txtPKTipoProducto").val();
  
    $.ajax({
      url:"../../php/funciones.php",
      data:{clase:"edit_data", funcion:"edit_tipoProducto", datos:estatus, datos2:tipoProducto, datos3:id},
      dataType:"json",
      success:function(respuesta){
        console.log("respuesta editar tipo producto:",respuesta);
  
        if(respuesta[0].status){
  
          Swal.fire('Datos actualizados exitosamente',"Los datos del tipo de producto fueron actualizados con éxito.","success");
          $('#tblListadoTipoProducto').DataTable().ajax.reload();
        }else{
          Swal.fire({
            title: '<h3 style="color:white; arialRoundedEsp;">Actualización no se pudo realizar<h3>',
            html: '<h5 style="color:white; arialRoundedEsp;">La actualización de los datos del tipo de producto no se pudieron realizar.<h5>',
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
  });
  
  function cambiarColor(){
    if($("#cmbEstatusTipoProducto").val() == 1){
      console.log("Es Activo");
      $("#cmbEstatusTipoProducto").css({"background-color": "#28c67a", "color":"#FFFFFF"});
    }else{
      console.log("Es Inactivo");
      $("#cmbEstatusTipoProducto").css({"background-color": "#cac8c6"});
    }
  }
  
  /* VALIAR QUE NO SE REPITA LA TIPO PRODUCTO POR PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
  function validarTipoProductoU() {
    var valor = document.getElementById("txtTipoProductoU").value;
    console.log('Valor tipo producto: '+valor);
    $.ajax({
      url: '../../php/funciones.php',
      data:{clase:"get_data", funcion:"validar_tipoProducto", data:valor},
      dataType:"json",
      success: function(data) {
        console.log('respuesta tipo producto validado: ',data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]['existe']) == 1 && $("#txtTipPActual").val() != $("#txtTipoProductoU").val()) {
  
          var agregar = document.getElementById("btnEditarTipoProducto");
          agregar.style.display = 'none';
  
          var nota = document.getElementById("notaTipoProductoU");
          nota.setAttribute('type', 'text');
  
          console.log('¡Ya existe!');
  
        } else {
  
          var agregar = document.getElementById("btnEditarTipoProducto");
          agregar.style.display = 'block';
  
          var nota = document.getElementById("notaTipoProductoU");
          nota.setAttribute('type', 'hidden');
  
          console.log('¡No existe!');
        }
  
      }
    });
  }