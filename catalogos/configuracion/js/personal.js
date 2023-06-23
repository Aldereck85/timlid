let cmbGenero, cmbGeneroU, cmbRoles, cmbRolesU, cmbEstado, cmbEstadoU, roles, rolesU;
$(document).ready(function () {  
  cmbGenero = new SlimSelect({
    select: '#cmbGenero',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar género'
  });
  cmbGeneroU = new SlimSelect({
    select: '#cmbGeneroU',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar género',
    onChange: ()=>{
      $("#cmbGeneroU").removeClass("is-invalid");
      $("#invalid-generoU").css("display", "none");
    }
  });
  cmbRoles = new SlimSelect({
    select: '#cmbRolesPersonal',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar roles'
  });
  cmbRolesU = new SlimSelect({
    select: '#cmbRolesPersonalU',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar roles',
    onChange: ()=>{
      $("#invalid-rolesU").css("display", "none");
    }
  });
  cmbEstado = new SlimSelect({
    select: '#cmbEstadoPersonal',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar estado'
  });
  cmbEstadoU = new SlimSelect({
    select: '#cmbEstadoPersonalU',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar estado',
    onChange: ()=>{
      $("#cmbGeneroU").removeClass("is-invalid");
      $("#invalid-generoU").css("display", "none");
    }
  });
});
  
// Reiniciar el modal al cerrarlo
$("#agregar_Personal_72").on("hidden.bs.modal", function (e) {
  $("#invalid-nombre").css("display", "none");
  $("#txtNombre").removeClass("is-invalid");
  $("#txtNombre").val("");

  $("#invalid-primerApellido").css("display", "none");
  $("#txtPrimerApellido").removeClass("is-invalid");
  $("#txtPrimerApellido").val("");

  $("#invalid-roles").css("display", "none");
  $("#cmbRoles").val("");
  cmbRoles.set([]);

  $("#invalid-estado").css("display", "none");
  $("#cmbEstadoPersonal").val("");
  cmbEstado.set('');
});

$("#agregar_Personal_72").on("show.bs.modal", function (e) {
  $("#invalid-roles").css("display", "none");
  $("#invalid-estado").css("display", "none");
  $("#btnAgregarPersonal").removeAttr("disabled");
});
  
// AGREGA PERSONAL
$("#btnAgregarPersonal").on("click", function () {
  roles = cmbRoles.selected();
  if ($("#agregarPersonal")[0].checkValidity() && roles.length != 0) {
    var badNombre =
      $("#invalid-nombre").css("display") === "block" ? false : true;
    var badPrimerApellido =
      $("#invalid-primerApellido").css("display") === "block" ? false : true;
    var badGenero =
      $("#invalid-genero").css("display") === "block" ? false : true;
    var badEstado =
        $("#invalid-estado").css("display") === "block" ? false : true;
    if (
      badNombre &&
      badPrimerApellido &&
      badGenero &&
      badEstado
    ) {
      $("#btnAgregarPersonal").removeAttr("disabled");
      var nombre = $("#txtNombre").val().trim();
      var apellido = $("#txtPrimerApellido").val().trim();
      var genero = $("#cmbGenero").val();
      var estado = $("#cmbEstadoPersonal").val();

      if (!$("#txtNombre").val()) {
        $("#txtNombre")[0].reportValidity();
        $("#txtNombre")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (!$("#txtPrimerApellido").val()) {
        $("#txtPrimerApellido")[0].reportValidity();
        $("#txtPrimerApellido")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (!$("#cmbEstadoPersonal").val()) {
        $("#cmbEstadoPersonal")[0].reportValidity();
        $("#cmbEstadoPersonal")[0].setCustomValidity("Completa este campo.");
        return;
      } else {
        $.ajax({
          url: "php/funciones.php",
          type: "POST",
          data: {
            clase: 'save_data',
            funcion: 'save_personal',
            nombre: nombre,
            apellido: apellido,
            genero: genero,
            roles: roles,
            estado: estado
          },
          success: function (data, status, xhr) {
            $("#agregar_Personal_72").modal("toggle");
            $("#btnAgregarPersonal").trigger("reset");
            $("#tblPersonal").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              //img: '<i class="fas fa-check-circle"></i>',
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Registro agregado!",
            });
            
          },
          error: function (error) {
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/warning_circle.svg",
              img: null,
              msg: error,
            });
          },
        });
      }
    }
  } else {
    if (!$("#txtNombre").val()) {
      $("#invalid-nombre").css("display", "block");
      $("#txtNombre").addClass("is-invalid");
    }
    if (!$("#txtPrimerApellido").val()) {
      $("#invalid-primerApellido").css("display", "block");
      $("#txtPrimerApellido").addClass("is-invalid");
    }
    if (!$("#cmbEstadoPersonal").val()) {
      $("#invalid-estado").css("display", "block");
    }
    if (cmbRoles.selected().length === 0) {
      $("#invalid-roles").css("display", "block");
    }
  }
  $(this).prop("disabled", true);
});
  
function obtenerIdPersonalEditar(id) {
  document.getElementById("idPersonalU").value = id;
  $.ajax({
    type: "POST",
    url: "php/funciones.php",
    data: {
      clase: 'get_data',
      funcion: 'get_personalU',
      id: id,
    },
    success: function (r) {
      console.log(r);
      const datos = JSON.parse(r);
      $("#txtNombreU").val(datos[0].Nombres);
      $("#txtPrimerApellidoU").val(datos[0].PrimerApellido);
      cmbGeneroU.set(datos[0].Genero);
      cmbEstadoU.set(datos[0].FKEstado);
      const values = datos[0].tipo_id !== '' && datos[0].tipo_id !== null ? datos[0].tipo_id.split(',') : null;
      cmbRolesU.set(values);
    },
  });
}


$("#idPersonalD").on("click", function(){
  $("#hddPersonalD").val($("#idPersonalU").val());
});

$("#btn_aceptar_eliminar_Personal_72").on("click", function(){
  let id = $("#hddPersonalD").val();
  $.ajax({
    url: "php/funciones.php",
    type: "POST",
    data: {
      clase: 'delete_data',
      funcion: 'delete_personal',
      idEmpleado: id,
    },
    success: function (data, status, xhr) {
        console.log('data');
        console.log(data);
        console.log('status');
        console.log(status);
        console.log('xhr');
        console.log(xhr);
        $("#editar_Personal_72").modal("toggle");
        $("#tblPersonal").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../../img/chat/notificacion_error.svg",
          msg: "¡Registro eliminado!",
        });
    },
    error: function (error, status, xhr) {
      console.log('error');
      console.log(error);
      console.log('status');
      console.log(status);
      console.log('xhr');
      console.log(xhr);
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../img/timdesk/warning_circle.svg",
        img: null,
        msg: error,
      });
    },
  });
});

function validEmptyInput(item, invalid = null) {
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  
  if (invalid) {
    invalidDiv = document.getElementById(invalid);
  } else {
    for (let i = 0; i < parent.children.length; i++) {
      if (parent.children[i].classList.contains("invalid-feedback")) {
        invalidDiv = parent.children[i];
        break;
      }
    }
  }
  if (!val) {
    item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
  $("#btnAgregarPersonal").removeAttr("disabled");
}

$("#btnEditarPersonal").click(function() {
  var nombreU = $("#txtNombreU").val().trim();
  var apellidoU = $("#txtPrimerApellidoU").val().trim();
  var generoU = $("#cmbGeneroU").val();
  var estadoU = cmbEstadoU.selected();
  var idEmpleado = $("#idPersonalU").val();
  rolesU = cmbRolesU.selected();
  if ($("#editarPersonalU")[0].checkValidity() && rolesU.length != 0) {
    var badNombreU =
      $("#invalid-nombreU").css("display") === "block" ? false : true;
    var badPrimerApellidoU =
      $("#invalid-primerApellidoU").css("display") === "block" ? false : true;
    var badGeneroU =
      $("#invalid-generoU").css("display") === "block" ? false : true;
    var badEstadoU =
      $("#invalid-estadoU").css("display") === "block" ? false : true;
    if (
      badNombreU &&
      badPrimerApellidoU &&
      badGeneroU &&
      badEstadoU
    ) {
      $.ajax({
        url: "php/funciones.php",
        type: "POST",
        data: {
          clase: 'update_data',
          funcion: 'update_personal',
          nombreU: nombreU,
          apellidoU: apellidoU,
          generoU: generoU,
          rolesU: rolesU,
          estadoU: estadoU,
          idEmpleado: idEmpleado
        },
        success: function(data, status, xhr) {
          console.log(data);
          $('#editar_Personal_72').modal('toggle');
          $('#btnEditarPersonal').trigger("reset");
          $('#tblPersonal').DataTable().ajax.reload();
          Lobibox.notify('success', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: true,
            img: '../../../img/timdesk/checkmark.svg',
            msg: '¡Registro modificado!'
          });
        },
        error: function (error) {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/warning_circle.svg",
            img: null,
            msg: error,
          });
        },
      });
    }
  } else {
    if (!nombreU) {
      $("#txtNombreU").addClass("is-invalid")
      $("#invalid-nombreU").css("display", "block");
    }

    if (!apellidoU) {
      $("#txtPrimerApellidoU").addClass("is-invalid")
      $("#invalid-primerApellidoU").css("display", "block");
    }

    if (!generoU) {
      $("#cmbGeneroU").addClass("is-invalid")
      $("#invalid-generoU").css("display", "block");
    }

    if (!estadoU) {
      $("#cmbEstadoPersonalU").addClass("is-invalid")
      $("#invalid-estadoU").css("display", "block");
    }

    if (cmbRolesU.selected().length === 0) {
      $("#invalid-rolesU").css("display", "block");
    }
  }
});

/* Reiniciar el modal al cerrarlo */
$("#editar_Personal_72").on("hidden.bs.modal", function(e) {
  $("#invalid-nombreU").css("display", "none");
  $("#txtNombreU").removeClass("is-invalid");
  $("#txtNombreU").val("");

  $("#invalid-primerApellidoU").css("display", "none");
  $("#txtPrimerApellidoU").removeClass("is-invalid");
  $("#txtPrimerApellidoU").val("");

  $("#invalid-rolesU").css("display", "none");
  $("#cmbRolesU").val("");
  cmbRolesU.set([]);

  $("#invalid-estadoU").css("display", "none");
  $("#cmbEstadoPersonalU").val("");
  cmbEstadoU.set('');
});

$("#txtNombreU").on("cahnge", function(){
  $("#txtNombreU").removeClass("is-invalid")
      $("#invalid-nombreU").css("display", "none");
});

$("#txtPrimerApellidoU").on("cahnge", function(){
  $("#txtPrimerApellidoU").removeClass("is-invalid")
      $("#invalid-primerApellidoU").css("display", "none");
});

