var contador = 1;
var etapas = 1;

//var id = $('#txtIdProject').val();

$(document).on('click','#plus_task',function(){
  var cadena = 'id='+$('#txtIdProject').val();
  $.ajax({
    method: 'POST',
    url: 'functions/add_task.php',
    data: cadena,
    dataType: 'json',
    success:function(data){
      if(data[0].Fecha == null || data[0].Fecha == ""){
        fecha = "dd-mm-aaaa";
      }else if(data[0].Fecha != "0000-00-00") {
        fecha = data[0].Fecha;
      }else{
        fecha = "dd-mm-aaaa";
      }
      $('.new_task').append('<div class="task-content" data-id1="'+data[0].PKTarea+'"><div class="title-task-new" style="background-color:#5cb85c" data-id2="'+data[0].PKTarea+'" id="task'+data[0].PKTarea+'">'+data[0].Tarea+'</div><div class="float-left priority-icon">'+fecha+'</div><div class="float-right chat-icon"><a href="#" class="chat-icon-text"><i class="far fa-comment fa-flip-horizontal"></i></a></div><hr class="line-hr"></div></div>');
      //$('.new_task').append(data);
      var my_element = document.getElementById('task'+data[0].PKTarea);
      my_element.scrollIntoView({
        behavior: "auto",
        block: "start",
        inline: "nearest"
      });

      $('.task-content').on({
        click: function(){
          var data = "id="+$(this).data('id1');
          $.ajax({
            type: 'POST',
            data: data,
            url: 'functions/view_task.php',
            dataType: 'json',
            success:function(data){
              $('#headerModalTask').html(data.tarea);
              $('#viewTaskInfo').modal('toggle');
              $('#headerModalTask').attr('data-id2',data.id);
              $('#txaDescription').val(data.texto);
              $('#txtDate').val(data.fecha);
              $('#cmbUser').val(data.usuario);
              $('#cmbStatus').val(data.estatus);
              $('#abrirChat').attr('onclick',"loadChat(" + data.id + ",1)");
            }
          });
        }
      });//end click event
      $.ajax({
        type: 'POST',
        data: cadena,
        url: 'functions/getPKEtapaStart.php',
        dataType: 'json',
        success: function(data1){
            var id = data1[0].PKEtapa;
            var stage = document.getElementById('content-stage-task'+id);
            Sortable.create(stage, {
              group: 'shared',
              animation: 150,
              ghostClass: 'ghost',
              dataIdAttr: 'data-id1',
              onEnd: function (evt) {
                var data = "etapa="+evt.to.dataset.id+
                           "&tarea="+evt.item.dataset.id1;
                $.ajax({
                  data : data,
                  url : 'functions/update_phaseTask.php',
                  method : 'POST',
                  success : function(){
                    //location.reload();
                  }
                });
                //alert($('.name').data('id'));
              }
            });
        }
      });

    }
  });

});

$(document).on('click','#add-task',function(){
  var cadena = "id="+$('#txtIdProject').val();
  $.ajax({
    data: cadena,
    url: 'functions/add_phase.php',
    method: 'POST',
    dataType: 'json',
    success:function(data){
      var noEtapa = data[0].PKEtapa;
      var etapa = data[0].Etapa;
      $('#body-container-scrum').append('<div class="container-task" id="container-task"><div class="title-stage-new" contenteditable="true"><span class="title-stage">'+etapa+'</span></div><div class="content-stage-task" id="content-stage-task'+noEtapa+'" data-id="'+noEtapa+'"></div><div class="button-add-newtask"><a href="#" class="add-task add-taskStage" data-id5="'+noEtapa+'"><img src="../../img/scrum/agregar_tarea_gris.svg" alt=""></a></div></div>')


      //$('.container-task').show();
      //$('.title-stage-new').focus();
      var stage = document.getElementById('content-stage-task'+noEtapa);
      Sortable.create(stage, {
        group: 'shared',
        animation: 150,
        ghostClass: 'ghost',
        onAdd: function (evt) {
          //alert($('.task-content').data('id1'));
          //alert(evt);
          /*$('.task-content').on({
            mouseout: function(){
              alert($(this).data('id1'));
            }

          });*/
        },
        onStart: function (evt) {
          $('.task-content').css('cursor','grab');  // element index within parent
        },
      });
    }
  });


});

$(document).on('click','.add-taskStage',function(){
  var id = $(this).data('id5');
  var cadena = "id="+$('#txtIdProject').val()+"&stageId="+id;
  //alert(cadena);
  $.ajax({
    method: 'POST',
    data: cadena,
    url: 'functions/add_task.php',
    dataType: 'json',
    success: function(data){
      if(data[0].Fecha == null || data[0].Fecha == ""){
        fecha = "dd-mm-aaaa";
      }else if(data[0].Fecha != "0000-00-00") {
        fecha = data[0].Fecha;
      }else{
        fecha = "dd-mm-aaaa";
      }
      $('#content-stage-task'+id).append('<div class="task-content" data-id1="'+data[0].PKTarea+'"><div class="title-task-new" style="background-color:#5cb85c" data-id2="'+data[0].PKTarea+'" id="task'+data[0].PKTarea+'">'+data[0].Tarea+'</div><div class="float-left priority-icon">'+fecha+'</div><div class="float-right chat-icon"><a href="#" class="chat-icon-text"><i class="far fa-comment fa-flip-horizontal"></i></a></div><hr class="line-hr"></div></div>');
      //$('#content-stage-task'+id).append(data);
      var my_element = document.getElementById('task'+data[0].PKTarea);
      my_element.scrollIntoView({
        behavior: "auto",
        block: "start",
        inline: "nearest"
      });
      $('.task-content').on({
        click: function(){
          var data = "id="+$(this).data('id1');
          $.ajax({
            type: 'POST',
            data: data,
            url: 'functions/view_task.php',
            dataType: 'json',
            success:function(data){
              $('#headerModalTask').html(data.tarea);
              $('#viewTaskInfo').modal('toggle');
              $('#headerModalTask').attr('data-id2',data.id);
              $('#txaDescription').val(data.texto);
              $('#txtDate').val(data.fecha);
              $('#cmbUser').val(data.usuario);
              $('#cmbStatus').val(data.estatus);
              $('#abrirChat').attr('onclick',"loadChat(" + data.id + ",1)");
            }
          });
        }
      });//end click event
    }
  });
});
/*
var task = document.getElementById('new_task');
Sortable.create(task,{
  group: 'shared',
  animation: 150,
  ghostClass: 'ghost',

});
*/

$(document).ready(function(){
  var cadena = "id="+$('#txtIdProject').val();
      $.ajax({
        type: 'POST',
        data: cadena,
        url: 'functions/getPKEtapaStart.php',
        dataType: 'json',
        success: function(data1){
            var id = data1[0].PKEtapa;
            $.ajax({
            type: 'POST',
            data: cadena,
            url: 'functions/load_task_start.php',
            success: function(data){
                $('#content-stage-task'+id).append(data);

                $(document).on('click','.task-content',function(){
                  var data = "id="+$(this).data('id1');
                  console.log('id modal: '+data);
                  $.ajax({
                    type: 'POST',
                    data: data,
                    url: 'functions/view_task.php',
                    dataType: 'json',
                    success:function(data){

                      $('#viewTaskInfo').modal('toggle');
                      $('#headerModalTask').html(data.tarea);
                      $('#headerModalTask').attr('data-id2',data.id);
                      $('#txaDescription').val(data.texto);
                      $('#txtDate').val(data.fecha);
                      $('#cmbUser').val(data.usuario);
                      $('#cmbStatus').val(data.estatus);
                      $('#abrirChat').attr('onclick',"loadChat(" + data.id + ",1)");
                    }
                  });
                });

                /*$('.task-content').on({
                  click: function(){
                    var data = "id="+$(this).data('id1');
                    $.ajax({
                      type: 'POST',
                      data: data,
                      url: 'functions/view_task.php',
                      dataType: 'json',
                      success:function(data){
                        $('#headerModalTask').html(data.tarea);
                        $('#viewTaskInfo').modal('toggle');
                        $('#headerModalTask').attr('data-id2',data.id);
                        $('#txaDescription').val(data.texto);
                        $('#txtDate').val(data.fecha);
                        $('#cmbUser').val(data.usuario);
                        $('#cmbStatus').val(data.estatus);
                        $('#abrirChat').attr('onclick',"loadChat(" + data.id + ",1)");
                      }
                    });
                  }
                });//end click event
                */
            }
            });//fin ajax carga de tareas en etapa 1
            var stage = document.getElementById('content-stage-task'+id);
            Sortable.create(stage, {
              group: 'shared',
              animation: 150,
              ghostClass: 'ghost',
              dataIdAttr: 'data-id1',
              onEnd: function (evt) {
                var data = "id="+$('#txtIdProject').val()+
                           "&etapa="+evt.to.dataset.id+
                           "&tarea="+evt.item.dataset.id1+
                           "&posicionAnterior="+evt.oldIndex+
                           "&posicionNueva="+evt.newIndex;
                //alert('Orden anterior: '+(evt.oldIndex+1));
                //alert('Orden nuevo: '+(evt.newIndex+1));

                $.ajax({
                  data : data,
                  url : 'functions/update_phaseTask.php',
                  method : 'POST',
                  success : function(data){
                    console.log(data);
                  }
                });
                //alert($('.name').data('id'));
              }
            });

        }
      });

  $.ajax({
    type: 'POST',
    data: cadena,
    url: 'functions/tasks_load.php',
    success: function(data){
      $('#body-container-scrum').append(data);

        $('.task-content').on({
          click: function(){
            var data = "id="+$(this).data('id1');
            $.ajax({
              type: 'POST',
              data: data,
              url: 'functions/view_task.php',
              dataType: 'json',
              success:function(data){
                console.log('Tarea: '+data.tarea+'\nId Tarea: '+data.id+'\nTexto: '+data.texto+'\nFecha: '+data.fecha+'\nResponsable: '+data.usuario+'\nEstado: '+data.estatus);
                $('#headerModalTask').html(data.tarea);
                $('#viewTaskInfo').modal('toggle');
                $('#headerModalTask').attr('data-id2',data.id);
                $('#txaDescription').val(data.texto);
                $('#txtDate').val(data.fecha);
                $('#cmbUser').val(data.usuario);
                $('#cmbStatus').val(data.estatus);
                $('#abrirChat').attr('onclick',"loadChat(" + data.id + ",1)");
              }
            });
          }
        });//end click event

        $.ajax({
          type: 'POST',
          data: cadena,
          url: 'functions/getPKEtapa.php',
          dataType: 'json',
          success: function(data1){
            for (var i = 0; i < data1.length; i++) {
              var id = data1[i].PKEtapa;
              var stage = document.getElementById('content-stage-task'+id);
              Sortable.create(stage, {
                group: 'shared',
                animation: 150,
                ghostClass: 'ghost',
                dataIdAttr: 'data-id1',
                onEnd: function (evt) {
                  var data = "id="+$('#txtIdProject').val()+
                             "&etapa="+evt.to.dataset.id+
                             "&tarea="+evt.item.dataset.id1+
                             "&posicionAnterior="+evt.oldIndex+
                             "&posicionNueva="+evt.newIndex;
                  //alert('Orden anterior: '+(evt.oldIndex+1));
                  //alert('Orden nuevo: '+(evt.newIndex+1));

                  $.ajax({
                    data : data,
                    url : 'functions/update_phaseTask.php',
                    method : 'POST',
                    success : function(data){
                      console.log(data);
                    }
                  });
                  //alert($('.name').data('id'));
                }
              });
              var stage1 = document.getElementById('body-container-scrum');
              Sortable.create(stage1, {
                group: 'stage',
                animation: 150,
                ghostClass: 'ghost',
                dataIdAttr: 'data-id1',
                onEnd: function (evt) {
                  var data = "id="+$('#txtIdProject').val()+
                             "&etapa="+evt.to.dataset.id+
                             "&tarea="+evt.item.dataset.id1+
                             "&posicionAnterior="+evt.oldDraggableIndex+
                             "&posicionNueva="+evt.newDraggableIndex;
                  console.log(data);
                   $.ajax({
                     data : data,
                     url : 'functions/update_phase.php',
                     method : 'POST',
                     success : function(data){
                       console.log(data);
                     }
                   });
                }
              });
            }
          }
        });
      }//end success
  });//fin ajax carga de tareas en etapas 2 en adelante

});



$(document).on('blur','.title-stage',function(){
  var id = $(this).data('id1');
  var stage_name = $(this).val();
  console.log("id: "+id);
  console.log("Nombre etapa: "+stage_name);
  $.ajax({
    method: 'POST',
    data: {id:id,stage_name:stage_name},
    url: 'functions/edit_stageName.php',
    success:function(data){

    }
  });
});

$(document).on('blur','#headerModalTask',function(){
  var id = $(this).data('id2');
  var task_name = $(this).text();
  $.ajax({
    method: 'POST',
    data: {id:id,task_name:task_name},
    url: 'functions/edit_taskName.php',
    success:function(data){
      $('#viewTaskInfo').on('hidden.bs.modal', function () {
        location.reload();
      });
    }
  });
});

$(document).on('click','.logo-views-title',function(){
  if($('.logo-views-menus').toggle() == false){
    $('.logo-views-menus').css('display','block');
  }
});
$(document).ready(function(){
  $('body').click(function(){
    if($('.logo-views-menus').is(':visible')){
      $('.logo-views-menus').css('display','none');
    }
  });
});

//$('#txtIdProject').val()
$(document).on('click','#goTimDesk',function(){
  var id = $('#txtIdProject').val();
  window.location.href = "../tareas/timDesk/index.php?id="+id;
});

$(document).on('click','#goScrum',function(){
  var id = $('#txtIdProject').val();
  window.location.href = "../pantalla_scrum/index.php?id="+id;
});

$(document).on('click','#goCalendar',function(){
  var id = $('#txtIdProject').val();
  window.location.href = "../calendario_tareas/index.php?id="+id;
});

$(document).on('click','#save_dataTask',function(){
  var id = $('#headerModalTask').data('id2');
  var description = $('#txaDescription').val();
  var date = $('#txtDate').val();
  var user = $('#cmdUser').val();
  var status = $('#cmbStatus').val();
  var data = "id="+id+
             "&descripcion="+description+
             "&fecha="+date+
             "&usuario="+user+
             "&estatus="+status;
  console.log(data);
  $.ajax({
    method: 'POST',
    data: data,
    url: 'functions/edit_dataTask.php',
    success : function(data){
      console.log(data);
      $('#viewTaskInfo').modal('toggle');
      location.reload();
    }
  });
});
/*
$(document).ready(function(){
  //Maximum number of characters
  var max = 24;

  $('.title-stage-new').keydown(function(e) {
      var keycode = e.keyCode;

      //List of keycodes of printable characters from:
      var printable =
          (keycode > 47 && keycode < 58)   || // number keys
          keycode == 32 || keycode == 13   || // spacebar & return key(s) (if you want to allow carriage returns)
          (keycode > 64 && keycode < 91)   || // letter keys
          (keycode > 95 && keycode < 112)  || // numpad keys
          (keycode > 185 && keycode < 193) || // ;=,-./` (in order)
          (keycode > 218 && keycode < 223);   // [\]' (in order)

      if (printable) {
          //Based on the Bala Velayutham's answer
          return $(this).text().length <= max;
      }
  });
});
*/
function show_task_tip(id,tipo){
  console.log("tipo: "+ tipo);
  if(tipo === 'etapa'){
    let comprobar = $('#title-stage-'+id).val();
    console.log("Nombre de la etapa: "+ comprobar);
    let contar = comprobar.length;
    console.log("Largo de la etapa: " + contar);
    console.log("id tooltip: "+id);

    if (contar>=25) {//Elementos tienen asignado el estado
      $('#stage-tip-'+id).html(comprobar);
      $('#stage-tip-'+id).removeClass('d-no');
      $('#stage-tip-'+id).addClass('d-in-block');
    }
  }else{
    let comprobar = $('#task-title-'+id).val();
    console.log("Nombre de la etapa: "+ comprobar);
    let contar = comprobar.length;
    console.log("Largo de la etapa: " + contar);
    console.log("id tooltip: "+id);

    if (contar>=25) {//Elementos tienen asignado el estado
      $('#task-tip-'+id).html(comprobar);
      $('#task-tip-'+id).removeClass('d-no');
      $('#task-tip-'+id).addClass('d-in-block');

    }
  }
}

function group_tip_hidden(id,tipo) {
  if(tipo === 'etapa'){
    $('#stage-tip-'+id).removeClass('d-in-block');
  	$('#stage-tip-'+id).addClass('d-no');
  }else{
    $('#task-tip-'+id).removeClass('d-in-block');
  	$('#task-tip-'+id).addClass('d-no');

  }

}



//addDate
//addUser
//addState

$(document).on('click','#addDate',function(){

});

$(document).on('click','#addUser',function(){
  var project = $('#txtIdProject').val();
  var tipo = 1;
  //$('#headerModalTask').attr('data-id2',data.id);
  //var idTarea = $('#headerModalTask').data('id2');
  var data = "project="+project+"&tipo="+tipo;
  $.ajax({
    method: 'POST',
    url: 'functions/addColumn.php',
    data: data,
    success:function(data){
      console.log('insertado...'+data);
    }
  });
});

$(document).on('click','#addState',function(){

});
