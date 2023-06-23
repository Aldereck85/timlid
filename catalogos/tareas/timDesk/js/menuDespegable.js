function getMenuDesplegable(id,id_col){//Mostrar modal para link
  
  $('#boardContent').animate({scrollTop: $('#boardContent').height() }, 1000);//Recorrer scroll hacía abajo
  varContainer = '.form-group-menud';//Variable para cerrar modal
  $('.editable-menu').remove();
  $('.form-group-menud').remove();
  $('.menud-'+id).append("<div id='modal-"+id+"' class='form-group-menud pos-abs'>"+
                            "<div id='menu-modal-content' class='modal-content'>"+
                              "<input type='text' class='form-control' id='txtag' placeholder='Crear etiqueta' value=''>"+
                              "<br>"+
                              "<select name='menudesplegable[]' id='menudesplegable-"+id+"' class='menudesplegable' value='' multiple></select>"+
                              "</br>"+
                              "<button id='save-selected-elements' class='btn btn-primary' onclick='guardarEtiquetasTarea("+id+")' disabled=true>Guardar</button>"+
                              "</br>"+
                              "<button type='submit' class='btn btn-primary' onclick=guardarEtiquetaMenu("+id+","+id_col+")>+ Crear etiqueta nueva</button>"+
                              "<br>"+
                              "<button class='btn btn-primary' onclick='editarEtiquetas("+id+","+id_col+")'>Editar etiquetas</button>"+
                            "</div>"+
                          "</div>");

  consultarEtiquetasSelected(id,function(resp){//llamar a funcion que hace la consulta de etiquetas seleccionadas
    console.log("seleccionados: ",resp);
    array_menu_selected=resp;
    console.log("array_menu_selected", array_menu_selected);
    if(resp.length!=0){//SI HAY OPCIONES SELECCIONADAS
      $.each(resp,function(i){
        if(resp[i].Bandera == 1){
            $("#menudesplegable-"+id).append("<option value='"+resp[i].PKEtiqueta+"' selected>"+resp[i].Nombre+"</option>");
        }else{
            $("#menudesplegable-"+id).append("<option value='"+resp[i].PKEtiqueta+"'>"+resp[i].Nombre+"</option>"); 
        }    
      })

      obtenerTagsMenuDes(id,id_col,array_menu_selected, function(data){//llamar funcion que hace la consulta en la DDBB de TODAS las etiquetas
          console.log("respuesta todas las etiquetas si hay seleccionadas: ",data);
          /*$.each(data,function(i){
           $("#menudesplegable-"+id).append("<option value='"+data[i].PKEtiqueta+"'>"+data[i].Nombre+"</option>");   
          })*/  
          array_menu_elements=data;
          
          $.each(data,function(i){
            $("#menudesplegable-"+id).append("<option value='"+data[i].PKEtiqueta+"'>"+data[i].Nombre+"</option>");
             
          })

      })

    }else{
      obtenerTagsMenuDes(id,id_col,0, function(data){//llamar funcion que hace la consulta en la DDBB de TODAS las etiquetas
          console.log("respuesta all labels no selected: ",data);
          /*$.each(data,function(i){
           $("#menudesplegable-"+id).append("<option value='"+data[i].PKEtiqueta+"'>"+data[i].Nombre+"</option>");   
          })*/
          array_menu_elements=data;
          console.log("array_menu_elements", array_menu_elements);
          if (data.length!=0){
            $.each(data,function(i){
              $("#menudesplegable-"+id).append("<option value='"+data[i].PKEtiqueta+"'>"+data[i].Nombre+"</option>");
            })
          }else{
             $("#menudesplegable-"+id).append("<option value='no' disabled='true'>Sin etiquetas</option>");       
          }

      })
    }
  })

  

  new SlimSelect({
    select: '#menudesplegable-'+id,
    placeholder:'Elegir etiqueta',
    showSearch: false,
    onChange: (info)=>{
      etiquetasTarea=[];
      let boton = document.getElementById('save-selected-elements').disabled = false;
      console.log("INFO: ",info)
      for(i=0;i<info.length;i++){
        etiquetasTarea.push(info[i].value)
      }
    }
  })
}

function guardarEtiquetaMenu(id,id_col){//Guardar en BBDD las etiquetas nuevas creadas
  var valor = $('#txtag').val();
  if (valor != "") {//Si el valor no está vacío
    $.ajax({
      url:"php/funciones.php",
      data:{clase:"add_data",funcion:"addMenud",valor:valor,id:id,idcol:id_col},
      dataType:"json",

      success:function(resp){
        console.log(resp); 
        $('#txtag').val(" ");
        $('#txtag').attr("placeholder", "Crear etiqueta");
        $("#menudesplegable-"+id).append("<option value='"+resp[0].PKEtiqueta+"'>"+valor+"</option>");
        array_menu_elements.push(resp[0]);
        console.log("array_menu_elements actualizado: ", array_menu_elements);
      },
      error:function(error){
        console.log(error);
      }
    })
  }

}

function guardarEtiquetasTarea(id){//funcion para guardar en DDBB las etiquetas seleccionadas

  $('.form-group-menud').remove();
  console.log(id);
  console.log("EL array de etiquetas: ",etiquetasTarea);

  if (etiquetasTarea.length==0){
      etiquetasTarea=0;
  }

  $.ajax({
    url:"php/funciones.php",
    data:{clase:"add_data",funcion:"addMenudSelected", id:id,array:etiquetasTarea},
    dataType:"json",

    success:function(resp){
      console.log(resp);
      consultarElementosSelected(id,function(resp){
        if (etiquetasTarea!=0) {
          $("#menud-"+id).empty();
          for(i=0;i<resp.length;i++){
            var cont=0;
            //$("#menud-"+id).append(resp[i].Nombre); 

            if(i>=2){
              cont=resp.length-2;
              $("#menud-"+id).append("<div class='recuadro_mas'><label id='recuadro_mas_"+resp[i].PKEtiqueta+"'>+"+cont+"</label></div>"); 
              i=resp.length;   
            }else{
              $("#menud-"+id).append("<div class='recuadro'><label class='label_selected_"+resp[i].PKEtiqueta+"'>"+resp[i].Nombre+"</label></div>");    
            }

          }
        }else{
          $("#menud-"+id).empty();
        }
      });  
    },
    error:function(error){
      console.log(error);
    }
  })
}

function obtenerTagsMenuDes(id,id_col,array, callback){//funcion para consultar en la BBDD las etiquetas
 //var respuesta;
 console.log("el valor del array: ", array)
 $.ajax({
  url:"php/funciones.php",
  data:{clase:"get_data",funcion:"getAllTagsMenuDes",id:id_col,array:array},
  dataType:"json",

  success:callback
 })
 //return respuesta;
}

function consultarEtiquetasSelected(id, callback){
 $.ajax({
  url:"php/funciones.php",
  data:{clase:"get_data",funcion:"getAllTagsSelected",id:id},
  dataType:"json",

  success:callback
 })
}

function consultarElementosSelected(id, callback){
 $.ajax({
  url:"php/funciones.php",
  data:{clase:"get_data",funcion:"getAllElementsSelected",id:id},
  dataType:"json",

  success:callback
  
 })
}

function copyText(text,num){//Copia el texto de los elementos del tipo "id del elemento"
  console.log("text", text, "num:", num);
  
  $('.copy1').remove();
  //console.log('copytext: ',text)
  $('#copy-text-'+text+"-"+num).select();
  $('#copy-text-'+text+"-"+num).attr('disabled','disabled');
  document.execCommand("copy");
  $('#copy-text-'+text+"-"+num).hide();
  $('#copy-text-'+text+"-"+num).parent().addClass('coppied');
  $('.coppied').append('<div class="copy1" style="color:#28c67a;">¡Copiado!</div>');
  setTimeout(function(){
    //console.log('desaparece copiado')
    $('#copy-text-'+text+"-"+num).parent().removeClass('coppied');
    $('.copy1').fadeOut()
  }, 1000);

  setTimeout(function(){
    //console.log('aparece número')
    $('#copy-text-'+text+"-"+num).fadeIn("slow");
    $('#copy-text-'+text+"-"+num).attr('disabled',false);
  }, 2000);
}

function editarEtiquetas(id,id_col){

  $('#menu-modal-content').remove();
  $('#modal-'+id).append('<div class="editable-menu"></div>')
  $.ajax({
    url:"php/funciones.php",
    data:{clase:"get_data",funcion:"getAllTagsMenuToEdit",id:id_col},
    dataType:"json",
    success:function(resp){
      console.log(resp);
      for(i=0;i<resp.length;i++){
        $('#modal-'+id+' .editable-menu').append('<div id="element-menu-'+resp[i].PKEtiqueta+'" class="d-flex pos-rel">'+
                                                  '<input id="menu-element-editable-'+resp[i].PKEtiqueta+
                                                  '" class="editable-menu-element" type="text" onfocusout="edit_element_menu('+
                                                  resp[i].PKEtiqueta+')" value="'+resp[i].Nombre+'">'+
                                                  '<div class="d-flex" style="align-items:center;">'+
                                                      '<div id="tip-menu-element-'+resp[i].PKEtiqueta+'" class="delete-menu-element cursorPointer imgActive" onclick="delete_menu_element('+
                                                      resp[i].PKEtiqueta+','+id+')" onmouseenter="element_tooltip('+
                                                      resp[i].PKEtiqueta+','+id+')" onmouseleave="hide_element_tooltip('+resp[i].PKEtiqueta+')">'+
                                                      '</div>'+
                                                  '</div>'+
                                                '</div>')
      }
    },
    error:function(error){
      console.log(error);
    }
   })
  
  

  $('#modal-'+id).append('<div style="padding:13px;"><button class="btn btn-primary" style="width:100%;" onclick="getMenuDesplegable('+id+','+id_col+')">Aplicar</button></div>')
  
} 

function edit_element_menu(id){
  console.log(id)
  let texto = $('#menu-element-editable-'+id).val()
  $.ajax({
    url:"php/funciones.php",
    data:{clase:"edit_data",funcion:"edit_menu_element",id:id,texto:texto},
    dataType:"json",
    success:function(resp){
      console.log("resp: ", resp);
      if (resp=="ok") {
        $('.label_selected_'+id).html(texto);
      }else{
        $('#menu-element-editable-'+id).val(resp.Nombre);
        $('#menu-element-editable-'+id).blur(); 
        lobby_notify('¡El nombre del elemento no puede ir vacío!','warning_circle.svg','warning','timdesk/')
      }
    },
    error:function(error){
      console.log("error: ", error);
    }
  })
}

function delete_menu_element(id){
  $.ajax({
    url:"php/funciones.php",
    data:{clase:"get_data",funcion:"checkValueSelected",id:id},
    dataType:"json",
    success:function(resp){
      if (resp == 0) {//Que el elemento no está en uso
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        })

      swalWithBootstrapButtons.fire({
          title: '¿Desea continuar?',
          text: '¡Este elemento será eliminado del menú!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Eliminar elemento</span>',
          cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              url:"php/funciones.php",
              data:{clase:"elim_data", funcion:"delete_menu_element",id:id},
              dataType:"json",
              success:function(respuesta){
                console.log(respuesta);
                $('#element-menu-'+id).remove();
                lobby_notify("¡Elemento eliminado!","notificacion_error.svg","error","chat/")
              },
              error:function(error){
                console.log(error);
              }
            })

          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {

          }
        })
      }else{
        lobby_notify('¡El elemento está en uso!','warning_circle.svg','warning','timdesk/')
      }
    },
    error:function(error){

    }
  })
  
}

function element_tooltip(id){

  $.ajax({
    url:"php/funciones.php",
    data:{clase:"get_data",funcion:"checkValueSelected",id:id},
    dataType:"json",
    success:function(resp){
      console.log(resp)
      if (resp > 0) {
        $('#tip-menu-element-'+id).removeClass('cursorPointer');
        $('#tip-menu-element-'+id).addClass('cursorDenied');
      }
    },
    error:function(error){

    }
  })
}

function hide_element_tooltip(id) {
  $('#tip-menu-element-'+id).removeClass('cursorDenied');
  $('#tip-menu-element-'+id).addClass('cursorPointer');
}