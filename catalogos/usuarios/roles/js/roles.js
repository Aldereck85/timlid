$(document).ready(function () {
  var html = '<div class="card spaced-title">';
  var html1 = "";

  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "getSections" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        console.log("respuesta:",respuesta);
        if (respuesta[i].seccion !== "Timdesk" && respuesta[i].seccion !== "Textiles") {
          $("#title-card-tabs").append(
            '<div class="card mb-1">' +
              '<div class="card-header title_tab" id="heading' +
              respuesta[i].id +
              '">' +
              '<h5 class="mb-0">' +
              '<a class="roles-buton-secction" id="opt-header-' +
              respuesta[i].siglas +
              '" type="button" data-toggle="collapse" data-target="#collapse' +
              respuesta[i].id +
              '" aria-expanded="true" aria-controls="collapse' +
              respuesta[i].id +
              '">' +
              '<i id="opt-group-' +
              respuesta[i].siglas +
              '" class="opt-menu-icon"></i>' +
              "<span class='roles-title'>" +
              respuesta[i].seccion +
              "</span>" +
              "</a>" +
              "</h5>" +
              "</div>" +
              '<div id="collapse' +
              respuesta[i].id +
              '" class="collapse collapse-body" aria-labelledby="heading' +
              respuesta[i].id +
              '" data-parent="#title-card-tabs">' +
              '<div class="card-body">' +
              '<div class="radio-permisos-container">' +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoControlTotal' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender"><label for="rdoControlTotal' +
              respuesta[i].siglas +
              '">Control total<span></span> <span></span></label>' +
              "</span>" +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoNoEliminar' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender" ><label for="rdoNoEliminar' +
              respuesta[i].siglas +
              '">Controlar todo excepto eliminar<span></span> <span></span></label>' +
              "</span>" +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoVer' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender" ><label for="rdoVer' +
              respuesta[i].siglas +
              '">Solo lectura<span></span> <span></span></label>' +
              "</span>" +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoSinPermisos' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender" ><label for="rdoSinPermisos' +
              respuesta[i].siglas +
              '">Sin permisos<span></span> <span></span></label>' +
              "</span>" +
              "<span class='radio-permisos'>" +
              '<input type="radio" id="rdoPersonalizado' +
              respuesta[i].siglas +
              '" class="rdbHeader" name="gender" ><label for="rdoPersonalizado' +
              respuesta[i].siglas +
              '">Personalizado<span></span> <span></span></label>' +
              "</span>" +
              "</div>" +
              "<br>" +
              '<div class="d-flex flex-wrap" id="checks-body-' +
              respuesta[i].id +
              '">'
          );

          $.ajax({
            url:"php/funciones.php",
            data:{clase:"get_data", funcion:"getScreensVal", value:respuesta[i].id},
            dataType:"json",
            success:function(respuesta1){
              $.each(respuesta1,function(j){
                var pantalla = respuesta1[j].Pantalla.replace(/ /g, "");
                $('#checks-body-'+respuesta[i].PKSeccion).append(
                    '<div class="col-lg-2">'+
                      '<input type="checkbox" name="chkTodo'+pantalla+'" class="chk'+respuesta[i].Siglas+' chkEncabezado" id="chkTodo'+pantalla+'" disabled>'+
                      '<label class="space-left-7" for="chkTodo'+pantalla+'"><b>'+respuesta1[j].Pantalla+'</b></label>'+
                    
                      '<form id="form-checks-'+respuesta1[j].PKPantalla+'" action="" method="post">'

                );

                $.ajax({
                  url: "php/funciones.php",
                  data: {
                    clase: "get_data",
                    funcion: "getFunctionsVal",
                    value: respuesta1[j].PKPantalla,
                  },
                  dataType: "json",
                  success: function (respuesta2) {
                    var pantalla = respuesta1[j].Pantalla.replace(/ /g, "");
                    //console.log("pantalla: ",pantalla);
                    $.each(respuesta2, function (l) {
                      $("#form-checks-" + respuesta1[j].PKPantalla).append(
                        '<input type="checkbox" id="chk' +
                          respuesta2[l].Funcion +
                          pantalla +
                          '" class="space-left-7 chk' +
                          pantalla +
                          " chk" +
                          respuesta[i].Siglas +
                          " chk" +
                          respuesta[i].Siglas +
                          respuesta2[l].Funcion +
                          '" name="chkVerEmpleado'+
                          respuesta[i].Siglas +
                          respuesta2[l].Funcion + ' chk' +
                          respuesta[i].Siglas +
                          respuesta2[l].Funcion +
                          '" disabled data-idf="' +
                          respuesta2[l].PKFuncion +
                          '" data-idp="' +
                          respuesta1[j].PKPantalla +
                          '" data-ids="' +
                          respuesta[i].PKSeccion +
                          '">' +
                          '<label class="space-left-7" for="chk' +
                          respuesta2[l].Funcion +
                          respuesta[i].PKSeccion +
                          '">' +
                          respuesta2[l].Funcion +
                          "</label><br>"
                      );
                        //console.log("idf:",$('#chk'+respuesta2[l].Funcion+pantalla).data('idf'));
                      $.ajax({
                        url:"php/funciones.php",
                        data:{clase:"get_data", funcion:"getFunctionsValues", value:$('#txtIdUserAdd').val(),id:$('#chk'+respuesta2[l].Funcion+pantalla).data('idf')},
                        dataType:"json",
                        success:function(respuesta3){
                          //console.log("respuesta3:",respuesta3);

                          var pantalla = respuesta1[j].Pantalla.replace(/ /g, "");
                          if(respuesta3.length > 0){
                            if(respuesta3[0].Permiso === 0){
                              $('#chkTodo'+pantalla).attr('checked',false);
                              $('#chk'+respuesta2[l].Funcion+pantalla).attr('checked',false);
                            }else{
                              $('#chkTodo'+pantalla).attr('checked',false);
                              $('#chk'+respuesta2[l].Funcion+pantalla).attr('checked',true);
                            }
                          }
                          

                        },
                        error:function(error){
                          console.log(error);
                        }
                      });
                      
                      $('#rdoControlTotalGeneral').on('click',function(){
                        var pantalla = respuesta1[j].Pantalla.replace(/ /g, "");
                        if(this.checked){
                          console.log(".chk" + pantalla);
                          $(".chk" + pantalla).each(function () {

                            this.checked = true;
                            this.disabled = true;
                          });
                          $("#chkTodo" + pantalla).each(function () {
                            this.checked = true;
                            this.disabled = true;
                          });
                        }
                      });

                      $('#rdoNoEliminarGeneral').on('click',function(){
                        if(this.checked){
                          $(".chk" + respuesta[i].Siglas).each(function () {
                            this.disabled = true;
                            this.checked = true;
                          });

                          $(".chk" + respuesta[i].Siglas + "Eliminar").each(function () {
                            this.checked = false;
                          });
                        }
                      });

                      $('#rdoVerGeneral').on('click',function(){
                        if (this.checked) {
                          $(".chk" + respuesta[i].Siglas).each(function () {
                            this.disabled = true;
                            this.checked = false;
                          });
              
                          $(".chk" + respuesta[i].Siglas + "Ver").each(function () {
                            this.checked = true;
                          });
                        }
                      });

                      $('#rdoSinPermisosGeneral').on('click',function(){
                        if(this.checked){
                          $(".chk" + respuesta[i].Siglas).each(function () {
                            this.disabled = true;
                            this.checked = false;
                          });  
                        }
                      });

                      $('#rdoPersonalizadoGeneral').on('click',function(){
                        if (this.checked) {
                          $(".chk" + respuesta[i].Siglas).each(function () {
                            this.disabled = false;
                          });
                        }
                      });
                      
                    });
                    
                    
                  },
                  error: function (error) {
                    console.log(error);
                  },
                });
                $("#checks-body-" + respuesta[i].PKSeccion).append(
                  "</form>" + "</div>" + "</div>" + "</div>" + "</div>"
                );
                
                $("#chkTodo" + respuesta1[j].Pantalla).on("click", function () {
                  if (this.checked) {
                    $(".chk" + respuesta1[j].Pantalla).each(function () {
                      this.checked = true;
                    });
                  } else {
                    $(".chk" + respuesta1[j].Pantalla).each(function () {
                      this.checked = false;
                    });
                  }
                });
              });
            },
            error: function (error) {
              console.log(error);
            },
          });
        }

        $("#rdoControlTotal" + respuesta[i].Siglas).on("click", function () {
          
          if (this.checked) {
            $(".chk" + respuesta[i].Siglas).each(function () {
              this.disabled = true;
              this.checked = true;
            });
          }
        });

        $("#rdoNoEliminar" + respuesta[i].Siglas).on("click", function () {
          if (this.checked) {
            $(".chk" + respuesta[i].Siglas).each(function () {
              this.disabled = true;
              this.checked = true;
            });

            $(".chk" + respuesta[i].Siglas + "Eliminar").each(function () {
              this.checked = false;
            });

            $(".chkEncabezado").each(function () {
              this.checked = false;
            });
          }
        });

        $("#rdoVer" + respuesta[i].Siglas).on("click", function () {
          if (this.checked) {
            $(".chk" + respuesta[i].Siglas).each(function () {
              this.disabled = true;
              this.checked = false;
            });

            $(".chk" + respuesta[i].Siglas + "Ver").each(function () {
              this.checked = true;
            });
          }
        });

        $("#rdoSinPermisos" + respuesta[i].Siglas).on("click", function () {
          if (this.checked) {
            $(".chk" + respuesta[i].Siglas).each(function () {
              this.disabled = true;
              this.checked = false;
            });
          }
        });

        $("#rdoPersonalizado" + respuesta[i].Siglas).on("click", function () {
          if (this.checked) {
            $(".chk" + respuesta[i].Siglas).each(function () {
              this.disabled = false;
            });
          }
        });

        /*$('#opt-group-'+respuesta[i].Siglas).on('click',function(){
          console.log("contraer: ",respuesta[i].Siglas);
          $('#rdoControlTotal'+respuesta[i].Siglas).attr('checked',true);
        });*/
      });
    },
    error: function (error) {
      console.log(error);
    },
  });

  /*Tabs*/
  /*
  $('#administrador-tab').on('click',function(){
      $('.rdbHeader').each(function(){
          this.disabled = true;
      });

      $('.chkRh').each(function(){
          this.disabled = true;
          this.checked = true;
      });

      $('#rdoControlTotalRh').prop('checked', true);
  });

  $('#rh-tab').on('click',function(){
      $('.rdbHeader').each(function(){
          this.disabled = true;
      });

      $('.chkRh').each(function(){
          this.disabled = true;
          this.checked = true;
      });

      $('#rdoControlTotalRh').prop('checked', true);
  });

  $('#personalizado-tab').on('click',function(){
      $('.rdbHeader').each(function(){
          this.disabled = false;
      });
  });
  */

  /* radio buttons */

  /*$('#rdoNoEliminarRh').on('click',function(){
    if(this.checked){
        $('.chkRh').each(function(){
            this.disabled = true;
            this.checked = true;
        });

        $('.chkRhEliminar').each(function(){
            this.checked = false;
        });

        $('.chkEncabezado').each(function(){
            this.checked = false;
        });
    }
  });*/

  /*
  $('#rdoVerRh').on('click',function(){
    if(this.checked){
        $('.chkRh').each(function(){
            this.disabled = true;
            this.checked = false;
        });

        $('.chkRhVer').each(function(){
            this.checked = true;
        });
    }
  });
  */

  /*$('#rdoSinPermisosRh').on('click',function(){
    if(this.checked){
        $('.chkRh').each(function(){
            this.disabled = true;
            this.checked = false;
        });
    }
  });*/

  /*$('#rdoPersonalizadoRh').on('click',function(){
    if(this.checked){
        $('.chkRh').each(function(){
            this.disabled = false;
        });

        $('.chkRh').each(function(){
            this.checked = true;
        });
    }
  });*/

  /* Check box*/
  /*$('.chkRh').on('click',function(){
      $('.chkEncabezado').each(function(){
          this.checked = false;
      });
  });*/

  /*$('#chkTodoEmpleados').on('click',function(){
    if(this.checked){
        $('.chkEmpleados').each(function(){
            this.checked = true;
        });
    }else{
         $('.chkEmpleados').each(function(){
            this.checked = false;
        });
    }
  });*/

  /*$('#chkTodoUsuarios').on('click',function(){
    if(this.checked){
        $('.chkUsuarios').each(function(){
            this.checked = true;
        });
    }else{
         $('.chkUsuarios').each(function(){
            this.checked = false;
        });
    }
  });*/

  /*$('#chkTodoNomina').on('click',function(){
    if(this.checked){
        $('.chkNomina').each(function(){
            this.checked = true;
        });
    }else{
         $('.chkNomina').each(function(){
            this.checked = false;
        });
    }
  });*/

  /* $('#chkTodoTurnos').on('click',function(){
    if(this.checked){
        $('.chkTurnos').each(function(){
            this.checked = true;
        });
    }else{
         $('.chkTurnos').each(function(){
            this.checked = false;
        });
    }
  });*/

  /*$('#chkTodoPuestos').on('click',function(){
    if(this.checked){
        $('.chkPuestos').each(function(){
            this.checked = true;
        });
    }else{
         $('.chkPuestos').each(function(){
            this.checked = false;
        });
    }
  });*/

  /*$('#chkTodoSucursales').on('click',function(){
    if(this.checked){
        $('.chkSucursales').each(function(){
            this.checked = true;
        });
    }else{
         $('.chkSucursales').each(function(){
            this.checked = false;
        });
    }
  });*/
});

/*function getCondense(id){
	$('#opt-header-'+id).removeAttr('onclick');
	$('#opt-header-'+id).addClass('rotate180');
}

function noCondense(id){
	$('#opt-header-'+id).removeAttr('onclick');
	$('#opt-header-'+id).attr('onclick','getCondense('+id+')');
	$('#opt-header-'+id).removeClass('rotate180');
}*/

/*$(document).on('click','#opt-header-RH',function(){
  console.log("hola");
  if(!$('#rdoNoEliminarRH').is(':checked') || !$('#rdoVerRH').is(':checked') || !$('#rdoSinPermisosRH').is(':checked') || !$('#rdoPersonalizadoRH').is(':checked')){
    $('#rdoControlTotalRH').attr('checked',true);
  }
  
});*/

$(document).on("click", "#btnGuardar", function () {
  var noForms = document.forms;
  var permisos = new Array();
  var funciones = new Array();
  var pantallas = new Array();
  var secciones = new Array();
  var usuario = $("#txtIdUserAdd").val();

  $.each(noForms, function (i) {
    //console.log("forms:",noForms[i].id);
    var noElements = document.forms[i].elements;
    $.each(noElements, function (j) {
      var idf = $("#" + noElements[j].id + "").data("idf");
      var idp = $("#" + noElements[j].id + "").data("idp");
      var ids = $("#" + noElements[j].id + "").data("ids");
      if ($("#" + noElements[j].id + "").is(":checked")) {
        permisos.push({ value: 1, funcion: idf, pantalla: idp, seccion: ids });
      } else {
        permisos.push({ value: 0, funcion: idf, pantalla: idp, seccion: ids });
      }
    });
  });

  console.log("array permisos:", permisos.length);

  var jsonPermisos = JSON.stringify(permisos);
  $.ajax({
    url:"php/funciones.php",
    data:{clase:"save_data", funcion:"savePermission",value:jsonPermisos,usuario:usuario},
    method:'post',
    dataType:"json",
    success:function(respuesta){
      console.log("save permission",respuesta);
      if(respuesta > 0){
        window.location.href = "../";
      }
    },
    error:function(error){
      console.log(error);
    }
  });
  //console.log("El numero de elementos del primer formulario es ",noElements);
  //var elementsForms = document.forms[0].elements[0].id;
  //console.log("id: ",elementsForms);
  //if($('#'+elementsForms+'').is(':checked')){
  //console.log("Valor",1);
  //var data = $('#'+elementsForms+'').data('idp');
  //console.log("data:",data);
  //}else{
  //console.log("Valor",0);
  //}
  //if($('#chkVerEmpleados').val() === "on"){
  //alert("hola estoy activo");
  //}
  //alert(typeof($('#chkVerEmpleados').val()));
});

/*$(document).on('click','#chkVerEmpleados',function(){
  if($(this).is(':checked')){
    console.log(1);
  }else{
    console.log(0);
  }
  
});
*/
/*$(document).ready(function(){
  $.ajax({
    url:"php/funciones.php",
    data:{clase:"get_data", funcion:"getFunctionsValues", value:$('#txtIdUserAdd').val()},
    dataType:"json",
    success:function(respuesta){

      $.each(respuesta,function(i){
        var pantalla = respuesta[i].Pantalla.replace(/ /g, "");
        
        if(respuesta[i].Permiso === 0){
          if($('#chk'+respuesta[i].Funcion+pantalla).is(':checked')){
            $('#chk'+respuesta[i].Funcion+pantalla).attr('checked',false);
          }
          
        }else{
          $('#chk'+respuesta[i].Funcion+pantalla).attr('checked',true);
        }

      });
        
    },
    error:function(error){
      console.log(error);
    }
  });
});
*/