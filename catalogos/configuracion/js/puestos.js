/* Reiniciar el modal al cerrarlo */
$("#agregar_Puestos_45").on("hidden.bs.modal", function(e) {
  $("#invalid-puesto").css("display", "none");
  $("#txtPuesto").removeClass("is-invalid");
  $("#txtPuesto").val("");
});


$("#btnAgregarPuestos_45").click(function() {
  if ($("#agregarPuesto")[0].checkValidity()) {
    var badPuesto =
      $("#invalid-puesto").css("display") === "block" ? false : true; 
    if(badPuesto){
      var puesto = $("#txtPuesto").val().trim();
      var emp_id = $("#emp_id").val();
      var usuario = $("#txtUsuario").val();
      $.ajax({
        url: "puestos/functions/agregar_Puesto.php",
        type: "POST",
        data: {
          "txtPuesto": puesto,
          "empresa_id":emp_id,
          "usuario":usuario
        },
        success: function(data, status, xhr) {
          if (data.trim() == "exito") {
            $('#agregar_Puestos_45').modal('toggle');
            $('#agregarPuestos_45').trigger("reset");
            $('#tblPuestos').DataTable().ajax.reload();
            Lobibox.notify('success', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/checkmark.svg',
              msg: '¡Registro agregado!'
            });
          } else {
            Lobibox.notify('warning', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top',
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: 'Ocurrió un error al agregar'
            });
          }
        }
      });
    }
  } else {
    if (!$("#txtPuesto").val()) {
      $("#invalid-puesto").css("display", "block");
      $("#txtPuesto").addClass("is-invalid");
    }
  }
});

/* Reiniciar el modal al cerrarlo */
$("#editar_Puestos_45").on("hidden.bs.modal", function(e) {
  $("#invalid-puestoEdit").css("display", "none");
  $("#txtUpdatePuestos_45").removeClass("is-invalid");
  $("#txtUpdatePuestos_45").val("");
  
});


$(document).on("click", "#btnEditar_Puestos_45" ,function() {
  if ($("#editarPuesto")[0].checkValidity()) {
    var badNombrePuesto =
      $("#invalid-puestoEdit").css("display") === "block" ? false : true;
    if (badNombrePuesto) {
      var id = $('#txtUpdatePKPuestos_45').val();
      var puesto = $("#txtUpdatePuestos_45").val().trim();
      var emp_id = $("#emp_id").val();
      var usuario = $("#txtUsuario").val();

      $.ajax({
        url: "puestos/functions/editar_Puesto.php",
        type: "POST",
        data: {
          "idPuestoU": id,
          "txtPuestoU": puesto,
          "empresa_id": emp_id,
          "usuario": usuario
        },
        success: function(data, status, xhr) {
          
          if (data.trim() == "exito") {
            $('#editar_Puestos_45').modal('toggle');
            $('#editarPuestos_45').trigger("reset");
            $('#tblPuestos').DataTable().ajax.reload();
            Lobibox.notify('success', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/checkmark.svg',
              msg: '¡Registro modificado!'
            });
          } else {
            Lobibox.notify('warning', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top',
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: 'Ocurrió un error al modificar'
            });
          }
        }
      });
    }
  } else {
    if (!$("#txtPuestoU").val()) {
      $("#invalid-puestoEdit").css("display", "block");
      $("#txtUpdatePuestos_45").addClass("is-invalid");
    }
  }
});

function obtenerIdPuestoEditar(id) {
  $('#txtUpdatePKPuestos_45').val(id);
  var id = "id=" + id;
  $.ajax({
    type: 'POST',
    url: 'puestos/functions/traer_datos.php',
    data: id,
    success: function(r) {
      var datos = JSON.parse(r);
      $("#txtUpdatePuestos_45").val(datos.html);
      var idSucursal = $("#txtUpdatePKPuestos_45").val();
      if (idSucursal != "") {
        validarRelacionPuesto(idSucursal);
      }
    }
  });
}

/*function obtenerIdPuestoEliminar(ide) {
  window.location = "functions/editar_puesto.php?ver=" + ide + "";
}*/

$(document).on('click','#btn_aceptar_eliminar_Puestos_45',function(){
  var usuario = $('#txtUsuario').val();
  var id = $("#txtPKPuestos_45D").val();
  
  console.log(id);
  $.ajax({
    url: "puestos/functions/eliminar_Puesto.php",
    type: "POST",
    data: {
      "idPuestoD": id,
      "empresa_id": $("#emp_id").val(),
      "usuario" : usuario
    },
    success: function(data, status, xhr) {
      if (data == "exito") {
        $('#eliminar_Puestos_45').modal('toggle');
        $('#tblPuestos').DataTable().ajax.reload();
        Lobibox.notify('success', {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: true,
          img: '../../img/chat/checkmark.svg',
          msg: '¡Registro eliminado!'
        });
      } else {
        Lobibox.notify('error', {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top',
          icon: true,
          img: '../../img/timdesk/warning_circle.svg',
          msg: 'Ocurrió un error al eliminar'
        });
      }
    }
  });
});

function eliminarPuesto(id) {
  var usuario = $('#txtUsuario').val();
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue"
    },
    buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
    title: '¿Desea eliminar el registro de este puesto?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter2">Eliminar puesto</span>',
    cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {

      $.ajax({
        url: "puestos/functions/eliminar_Puesto.php",
        type: "POST",
        data: {
          "idPuestoD": id,
          "empresa_id": $("#emp_id").val(),
          "usuario" : usuario
        },
        success: function(data, status, xhr) {
          if (data == "exito") {
            $('#editar_Puestos_45').modal('toggle');
            $('#tblPuestos').DataTable().ajax.reload();
            Lobibox.notify('success', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: true,
              img: '../../img/chat/checkmark.svg',
              msg: '¡Registro eliminado!'
            });
          } else {
            Lobibox.notify('error', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top',
              icon: true,
              img: '../../img/timdesk/warning_circle.svg',
              msg: 'Ocurrió un error al eliminar'
            });
          }
        }
      });

    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {}
  });
  
}


function validarUnicoPuesto(item) {
  console.log('kjhkjhk');
  var valor = item.value;
  $.ajax({
    url: "puestos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_puesto", data: valor },
    dataType: "json",
    success: function(data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Existe");
        item.nextElementSibling.innerText =
        "El puesto ya esta registrado en el sistema.";
        item.nextElementSibling.style.display = "block";
        item.classList.add("is-invalid");
      } else {
        console.log("No existe");
        item.nextElementSibling.innerText =
          "El nombre del puesto es requerido.";
        item.nextElementSibling.style.display = "none";
        item.classList.remove("is-invalid");
      }
    },
    error: function(error) {
      console.log(error);
    }
  });
}

function validarRelacionPuesto(valor) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionPuesto",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      /* Validar si ya existe relacion con clientes*/
      if (parseInt(data[0]["existe"]) == 1) {
        

        $("#txtareau").prop("disabled", true);

        var eliminar = document.getElementById("idPuestoD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarPuesto");
        modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");
      } else {
        $("#txtareau").prop("disabled", false);

        var eliminar = document.getElementById("idPuestoD");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditarPuesto");
        modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");
      }
    },
  });
}
