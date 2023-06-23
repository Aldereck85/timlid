//funcion para mostrar todas mis tareas asignadas
function getMyTasks(id_user){
 $('#MisTareas i').removeAttr('onclick');
 $('#MisTareas i').attr('onclick','allTask('+id_user+')');
 $.ajax({
  url:"php/funciones.php",
  data:{clase:"mis_tareas", funcion:"mis_tareas", id:id_user,idProyecto:idProyecto},
  dataType:"json",
  success:function(respuesta){
   console.log(respuesta);
   $('#sin-tareas').remove();
    if (respuesta.length>0){
     console.log(respuesta);
     $('.hideEtapa').hide();
     $('.hideTarea').hide();
     $.each(respuesta, function(i){
      $('.group_'+respuesta[i].FKEtapa).show();
      $('#tarea-'+respuesta[i].PKTarea).show();
     });
    }else{
     $('.hideEtapa').hide();
     $('.hideTarea').hide();
     $('#boardContent').append('<div id="sin-tareas" class="text-center"><img src="img/icons/fail.svg" width="80" height="80"></br></br><h1 class="h5 text-blutTim">No tienes tareas asignadas en este proyecto</h1></div>')
    }   
   $('#MisTareas i').removeClass('mis-tareas').addClass('all-tareas');
  },
 })
}

//funcion para mostrar todas las tareas del proyecto
function allTask(id){
 $('#MisTareas i').removeClass('all-tareas').addClass('mis-tareas');
 $('#sin-tareas').remove();
 $('#MisTareas i').removeAttr('onclick');
 $('#MisTareas i').attr('onclick','getMyTasks('+id+')');
 $('#MisTareas i').attr('data-original-title','Ver mis tareas');
 $('.hideEtapa').show();
 $('.hideTarea').show();
}
