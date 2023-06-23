function loadCombo(data,input,stmt,value,field){
  var html ='<option value="" selected>Seleccione '+field+'</option>';
  var selected;

  $.ajax({
    url:"php/funciones.php",
  	data:{clase:"get_data", funcion:stmt, value:value},
  	dataType:"json",
    success:function(respuesta){

      $.each(respuesta,function(i){
        //console.log("folio tipo de entradas combo: "+respuesta[i].Folio);
        if(data === respuesta[i].id){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].id+'" '+selected+'>'+respuesta[i].value+'</option>';
      });
      html += '<option value="custom_rol" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Personalizar </option>';
      //console.log("Array estado civil",civilState);

      $('#'+input+'').html(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function setFormatDatatables(){
  var idioma_espanol = {
    "sProcessing": "Procesando...",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    "sLoadingRecords": "Cargando...",
    "searchPlaceholder": "Buscar...",
    "oPaginate": {
      "sFirst": "Primero",
      "sLast": "Último",
      "sNext": "<img src='../../img/icons/pagination.svg' width='20px'/>",
      "sPrevious": "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
    },
  }
  return idioma_espanol;
}

$(document).ready(function () {
    $("#tblUsuarios").dataTable({
        "language": setFormatDatatables(),
        "dom": "Bfrtip",
        "buttons": [{
            extend: "excelHtml5",
            text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
            className: "excelDataTableButton",
            titleAttr: "Excel",
        }],
        "scrollX": true,
        "lengthChange": false,
        "info": false,
        "order": [
          [0, "desc"]
        ],
        "ajax": { 
            url: "php/funciones.php",
            data: { clase: "get_data", funcion: "get_userTable" },
        },

        "columns": [{
            "data": "Id usuario"
          },
          {
            "data": "Usuario"
          },
          {
            "data": "Nombre completo"
          },
        ],
    });


    nombre = document.getElementById('txtNameUser');
    usuario = document.getElementById('txtUser');
    pass1 = document.getElementById('newPassword');
    pass2 = document.getElementById('newPasswordAgain');
    empleado = document.getElementById('cmbEmpleado');

    buttonAdd = document.getElementById('btnAgregar');
    //var expreg = new RegExp("(?=.*\d)(?=.*[A-Z])(?=.*[~`!@#$%^&*()\-_+={};:\[\]\?\.\\/,]).{10,}");

    buttonAdd.onclick = function(){
      if(usuario.value != ""){
        if(pass1.value != ""){
          if(pass2.value != ""){
            if(validar_clave(pass1.value)){
              if(pass1.value === pass2.value){
                //if(expreg.test(pass1)){
                  $.ajax({
                    url:"php/funciones.php",
                    data:{clase:"save_data", funcion:"save_user", name:nombre.value, user:usuario.value, pass:pass1.value, employer:empleado.value},
                    dataType:"json",
                    success:function(respuesta){
                      //console.log("respuesta desde agregar usuarios:",respuesta);
                      if(respuesta !== 0){
                        Redireccionar(respuesta);
                      }else{
                        lobby_notify("Este correo ya fue registrado.","notificacion_error.svg","error","chat/");
                      }
                    },
                    error:function(error){
                      console.log(error);
                    }
                  });
              /* }else{
                  alert("El formato de la contraseña no es el correcto");
                }*/
              }else{
                lobby_notify("Las contraseñas deben de coincidir","notificacion_error.svg","error","chat/");
              }
            }else{
              lobby_notify("La contraseña debe tener al menos una letra mayuscula,  un caracter especial y 10 caracteres.","notificacion_error.svg","error","chat/");
            }
          }else{
            lobby_notify("El contraseña es obligatorio.","notificacion_error.svg","error","chat/");
          }
        }else{
          lobby_notify("El nombre es obligatorio.","notificacion_error.svg","error","chat/");
        }
      }else{
        lobby_notify("El usuario es obligatorio.","notificacion_error.svg","error","chat/");
      }
    }

    /*cmbrol.onchange = function() {
      //console.log("cmbrol",this.value);
      //console.log("usuario",usuario.value);
      //console.log("pass1",pass1.value);
      //console.log("pass2",pass2.value);
      if(this.value === "custom_rol"){
        if(usuario.value != ""){
          if(pass1.value != ""){
            if(pass2.value != ""){
              if(pass1.value === pass2.value){
                Redireccionar(usuario.value,pass1.value);
                
                //window.location.href = "roles/index.php?usuario="+usuario.value+"&contraseña="+pass1;
              }else{
                alert("Las contraseñas no coinciden");
                loadCombo("","cmbRol","get_rols","","un rol de usuario");
              }
            }else{
              console.log("Debe de llenar el campo repetir contraseña");
              alert("Debe de llenar el campo repetir contraseña");
              loadCombo("","cmbRol","get_rols","","un rol de usuario");
            }
          }else{
            console.log("Debe de llenar el campo contraseña");
            alert("Debe de llenar el campo contraseña");
            loadCombo("","cmbRol","get_rols","","un rol de usuario");
          }
        }else{
          console.log("Debe de llenar el campo usuario");
          alert("Debe de llenar el campo usuario");
          loadCombo("","cmbRol","get_rols","","un rol de usuario");
        }

          
      }

    };*/


      
    

    /*$('#cmbRol').on('change',function(){
      if($(this).val() === "custom_rol"){
        alert("hola");
      }
    });*/

    $('#btnEditar').on('click',function(){
      var idUser = $('#txtUpdatePKUser').val();
      var user = $('#txtUpdateUser').val();
      var name = $('#txtUpdateNameUser').val();
      var pass = $('#newUpdatePassword').val();
      var pass1 = $('#newUpdatePasswordAgain').val();
      //var expreg = new RegExp("(?=.*\d)(?=.*[A-Z])(?=.*[~`!@#$%^&*()\-_+={};:\[\]\?\.\\/,]).{10,}");

      if(idUser != ""){
          if(user != ""){
            if(name != ""){
              if(pass != ""){
                if(pass === pass1){
                  //if(pass.test(expreg)){
                    $.ajax({
                      url:"php/funciones.php",
                      data:{clase:"update_data", funcion:"update_user", value:idUser, name:name,user:user,pass:pass},
                      dataType:"json",
                      success:function(respuesta){
                        if(respuesta === 1){
                          Swal.fire({
                            title: '<h3 style="arialRoundedEsp;">Datos actualizados con éxitoso<h3>',
                            html: '<h5 style="arialRoundedEsp;">Los datos del usuario fueron actualizados con éxito.<h5>',
                            icon: 'success',
                            showConfirmButton: true,
                            focusConfirm: false,
                            showCloseButton: false,
                            confirmButtonText: 'Aceptar  <i class="far fa-arrow-alt-circle-right"></i>',
                            buttonsStyling: false,
                            allowEnterKey: false
                          }).then((result) => {
                              if(result.isConfirmed) {
                                $('#tblUsuarios').DataTable().ajax.reload();
                                $('#editarUsuario').modal('hide');
                              }else if(result.dismiss === Swal.DismissReason.cancel){
                                $('#tblUsuarios').DataTable().ajax.reload();
                                $('#editarUsuario').modal('hide');
                              }
                            });
                          }
                      },
                      error:function(error){
                        console.log(error);
                      }
                    });
                  //}
                }else{
                  lobby_notify("Las contraseñas deben de coincidir","notificacion_error.svg","error","chat/");
                }
              }else{
                lobby_notify("El contraseña es obligatorio.","notificacion_error.svg","error","chat/");
              }
            }else{
              lobby_notify("El nombre es obligatorio.","notificacion_error.svg","error","chat/");
            }
          }else{
            lobby_notify("El usuario es obligatorio.","notificacion_error.svg","error","chat/");
          }
        }
    });

    $('#btnEliminar').on('click',function(){
      $('#txtPKUsuarioD').val($('#txtUpdatePKUser').val());
      $('#txtUsuarioD').val($('#txtUpdateUser').val());
      $('#eliminarUsuario').modal('show');
    });

    $('#btnEliminarUsuario').on('click',function(){
      if($('#txtPKUsuarioD').val() !== ""){
        var idUser = $('#txtPKUsuarioD').val();
        $.ajax({
          url:"php/funciones.php",
          data:{clase:"delete_data", funcion:"delete_user", value:idUser},
          dataType:"json",
          success:function(respuesta){
            if(respuesta === 1){
              Swal.fire({
                title: '<h3 style="arialRoundedEsp;">Datos eliminados con éxitoso<h3>',
                html: '<h5 style="arialRoundedEsp;">Los datos del usuario fueron eliminados con éxito.<h5>',
                icon: 'success',
                showConfirmButton: true,
                focusConfirm: false,
                showCloseButton: false,
                confirmButtonText: 'Aceptar  <i class="far fa-arrow-alt-circle-right"></i>',
                buttonsStyling: false,
                allowEnterKey: false
              }).then((result) => {
                  if(result.isConfirmed) {
                    $('#tblUsuarios').DataTable().ajax.reload();
                  }else if(result.dismiss === Swal.DismissReason.cancel){
                    $('#tblUsuarios').DataTable().ajax.reload();
                  }
                });
            }
          },
          error:function(error){
            console.log(error);
          }
        });
      }
    });

    $('#btnPermisosActualizacion').on('click',function(){
      var idUser = $('#txtUpdatePKUser').val();
      //console.log("usuario en permisos:",idUser);
      Redireccionar(idUser);
    });
  

  $('#chkEmpleado').on('change',function(){
    if($(this).is(':checked')){

      loadComboUsuario('','cmbEmpleado');
      $('#secEmpleado').css('display','block');
      $('#secNameEmpleado').css('display','none');
    }else{
      $('#secEmpleado').css('display','none');
      $('#secNameEmpleado').css('display','block');
    }
  });

  new SlimSelect({
    select: '#cmbEmpleado',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      validarMarca(value);
    }
  });

});

function Redireccionar(user){
  var hash=user;
  hash = window.btoa(hash);
  window.location.href = "roles/index.php?usuario="+hash;
}

function obtenerIdUsuarioEditar(id){
  $('#txtUpdatePKUser').val(id);
  $.ajax({
    url:"php/funciones.php",
    data:{clase:"get_data", funcion:"get_user", value:id},
    dataType:"json",
    success:function(respuesta){
      console.log("respuesta desde editar usuario",respuesta);
      $('#txtUpdateUser').val(respuesta[0].Usuario);
      $('#txtUpdateNameUser').val(respuesta[0].Nombre);
      $('#newUpdatePassword').val(respuesta[0].Contrasena);
      
    },
    error:function(error){
      console.log(error);
    }
  });
}

function lobby_notify(string, icono,classStyle, carpeta){
	console.log("icono", icono);
	console.log("string", string);

	Lobibox.notify(classStyle, {
      size: 'mini',
      rounded: true,
      delay: 4000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../img/' + carpeta +icono,
      msg: string
    });

    return;

}

function loadComboUsuario(data,input){
  var html ='<option value="" disabled selected hidden>Seleccione el nombre del usuario...</option>';
  $.ajax({
    url:"php/funciones.php",
  	data:{clase:"get_data", funcion:"get_employer"},
  	dataType:"json",
    success:function(respuesta){
      console.log("respuesta combo:",respuesta);

      if(respuesta !== "" && respuesta !== null){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKEmpleado){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKEmpleado+'" '+selected+'>'+respuesta[i].Nombre+'</option>';
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      }else{
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $('#'+input+'').append(html);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function validar_clave(pass){
  if(pass.length < 11){		
    var mayuscula = false;
    var minuscula = false;
    var numero = false;
    var caracter_raro = false;
    
    for(var i = 0;i<pass.length;i++){
      if(pass.charCodeAt(i) >= 65 && pass.charCodeAt(i) <= 90){
        mayuscula = true;
      }
      else if(pass.charCodeAt(i) >= 97 && pass.charCodeAt(i) <= 122){
        minuscula = true;
      }
      else if(pass.charCodeAt(i) >= 48 && pass.charCodeAt(i) <= 57){
        numero = true;
      }
      else{
        caracter_raro = true;
      }
    }

    if(mayuscula == true && minuscula == true && caracter_raro == true && numero == true){
      return true;
    }
  }
  return false;
}

