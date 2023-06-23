function loadCombo(data, input, stmt, value, field) {
  var html = '<option value="" selected>Seleccione ' + field + "</option>";
  var selected;

  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: stmt, value: value },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        //console.log("folio tipo de entradas combo: "+respuesta[i].Folio);
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }
        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].value +
          "</option>";
      });
      html +=
        '<option value="custom_rol" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Personalizar </option>';
      //console.log("Array estado civil",civilState);

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).ready(function () {
  //loadComboRoles("", "cmbRoles");

  $("#chkEmpleado").on("change", changeEmpCheck);
  changeProfileCheck();
  // $("#chkPerfil").on("change", changeProfileCheck);

  $("#chkPerfilPerzonalizado").on("change", changeProfileCustomCheck);

  new SlimSelect({
    select: "#cmbEmpleadoUsuario",
    placeholder: "Seleccion el nombre del usuario...",
  });

//   new SlimSelect({
//     select: "#cmbRoles",
//     placeholder: "Seleccione un rol...",
//   });

  new SlimSelect({
    select: "#cmbPerfiles",
    placeholder: "Seleccione un perfil...",
  });

  new SlimSelect({
    select: "#cmbUpdateEmpleadoUsuario",
    placeholder: "Seleccion el nombre del usuario...",
  });

//   new SlimSelect({
//     select: "#cmbUpdateRoles",
//     placeholder: "Seleccione un rol...",
//   });

  new SlimSelect({
    select: "#cmbUpdatePerfiles",
    placeholder: "Seleccione un perfil...",
  });
});

$(document).on("click", "#btnAgregar", function () {
  var nombre = document.getElementById("txtNameUser");
  var primerApp = document.getElementById("txtPrimerApp");
  var segundoApp = document.getElementById("txtSegundoApp");
  var usuario = document.getElementById("txtUser");
  var checkEmpleado = document.getElementById("chkEmpleado");
  var empleado = document.getElementById("cmbEmpleadoUsuario");
  //var rol = document.getElementById("cmbRoles");
  var perfil = document.getElementById("cmbPerfiles");
  var empresa = document.getElementById("emp_id");
  if (!usuario.value) {
    $("#invalid-correoUs").css("display", "block");
    $("#txtUser").addClass("is-invalid");
  }
//   if (!rol.value) {
//     $("#invalid-rolUs").css("display", "block");
//     $("#cmbRoles").addClass("is-invalid");
//   }
  if (checkEmpleado.checked) {
    if (!empleado.value) {
      $("#invalid-empleadoUs").css("display", "block");
      $("#cmbEmpleadoUsuario").addClass("is-invalid");
    }
  } else {
    if (!nombre.value) {
      $("#invalid-nombreUs").css("display", "block");
      $("#txtNameUser").addClass("is-invalid");
    }
    if (!primerApp.value) {
      $("#invalid-PrimerAppUs").css("display", "block");
      $("#txtPrimerApp").addClass("is-invalid");
    }
  }
  var badCorreoUs =
    $("#invalid-correoUs").css("display") === "block" ? false : true;
  var badNombreUs =
    $("#invalid-nombreUs").css("display") === "block" ? false : true;
  var badprimerAppUs =
    $("#invalid-PrimerAppUs").css("display") === "block" ? false : true;
  var badEmpleadoUs =
    $("#invalid-empleadoUs").css("display") === "block" ? false : true;

  if (badCorreoUs && badprimerAppUs && badNombreUs && badEmpleadoUs) {
    if (!$("#chkPerfilPerzonalizado").is(":checked")) {
      $.ajax({
        url: "php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_user",
          name: nombre.value,
          primerApp: primerApp.value,
          segundoApp: segundoApp.value,
          user: usuario.value,
          employer: empleado.value,
          role: "",
          profile: perfil.value,
          empresa: empresa.value,
        },
        method: "post",
        dataType: "json",
        success: function (respuesta) {
          console.log("respuesta desde agregar usuarios:", respuesta);
          if (respuesta === 0) {
            lobby_notify(
              "Este correo ya fue registrado.",
              "notificacion_error.svg",
              "error",
              "chat/"
            );
          } else if (respuesta === -777) {
            lobby_notify(
              "Actualiza tu plan si necesitas más usuarios.",
              "notificacion_error.svg",
              "error",
              "chat/"
            );
          } else {
            lobby_notify(
              "Usuario registrado.",
              "checkmark.svg",
              "success",
              "chat/"
            );
            $("#tblUsuarios").DataTable().ajax.reload();
            $("#agregar_Usuarios_43").modal("hide");
          }
        },
        error: function (error) {
          lobby_notify(
            "Algo salio mal.",
            "notificacion_error.svg",
            "error",
            "chat/"
          );
        },
      });
    } else {
      var user_hash = usuario.value;
      user_hash = window.btoa(user_hash);
      var name_hash = nombre.value;
      name_hash = window.btoa(name_hash);
      var role_hash = rol.value;
      role_hash = window.btoa(role_hash);

      window.location.href =
        "perfiles/roles/index.php?user=" +
        user_hash +
        "&name=" +
        name_hash +
        "&role=" +
        role_hash;
    }
  }
});

$(document).on("click", "#btnEditar_Usuarios_43", function () {
  var idUser = $("#txtUpdatePKUsuarios_43").val();
  var user = $("#txtUpdateUser").val();
  var name = $("#txtUpdateUsuarios_43").val();
  //var rol = $("#cmbUpdateRoles").val();
  var rol = "";
  var perfil = $("#cmbUpdatePerfiles").val();
  console.log({ idUser });
  console.log({ user });
  console.log({ name });
  console.log({ rol });
  console.log({ perfil });

  if (idUser != "") {
    if (user != "") {
      if (name != "") {
        //if (rol != "") {
          //if(pass.test(expreg)){
          $.ajax({
            url: "php/funciones.php",
            data: {
              clase: "update_data",
              funcion: "update_user",
              value: idUser,
              name: name,
              user: user,
              role: rol,
              profile: perfil,
            },
            dataType: "json",
            success: function (respuesta) {
              console.log(respuesta);

              if (respuesta === 1) {
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top", //or 'center bottom'
                  icon: false,
                  img: "../../img/timdesk/checkmark.svg",
                  msg: "¡Registro actualizado!",
                });

                $("#tblUsuarios").DataTable().ajax.reload();
                $("#editar_Usuarios_43").modal("hide");
              } else if (respuesta === -777) {
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: false,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "El correo ya esta registrado",
                });
              } else {
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: false,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "Ocurrió un error al agregar",
                });
              }
            },
            error: function (error) {
              console.log(error);
            },
          });
        // } else {
        //   lobby_notify(
        //     "El rol es obligatorio.",
        //     "notificacion_error.svg",
        //     "error",
        //     "chat/"
        //   );
        // }
      } else {
        lobby_notify(
          "El nombre es obligatorio.",
          "notificacion_error.svg",
          "error",
          "chat/"
        );
      }
    } else {
      lobby_notify(
        "El usuario es obligatorio.",
        "notificacion_error.svg",
        "error",
        "chat/"
      );
    }
  }
});

$(document).on("click", "#btnActivar_Usuarios_43", function () {
  var idUser = $("#txtUpdatePKUsuarios_43").val();

  if (idUser != "") {
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "update_data",
        funcion: "activate_user",
        value: idUser,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(respuesta);
        if (respuesta === 1) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: false,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Registro actualizado!",
          });
          $("#tblUsuarios").DataTable().ajax.reload();
          $("#editar_Usuarios_43").modal("hide");
        } else if (respuesta === 2) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: false,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Algo salio mal",
          });
        } else if (respuesta === 3) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: false,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "No puedes tener mas usuarios activos",
          });
        }
      },
      error: function (error) {
        console.log(error);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: false,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Algo salio mal",
        });
      },
    });
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: false,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Algo salio mal",
    });
  }
});

function Redireccionar(user) {
  var hash = user;
  hash = window.btoa(hash);
  window.location.href = "roles/index.php?usuario=" + hash;
}

function obtenerIdUsuarioEditar(id) {
  $("#txtUpdateUser").removeClass("is-invalid");
  $("#invalid-correoEd").css("display", "none");
  $("#txtUpdatePKUser").val(id);
  $("#txtPKUsuarioD").val(id);
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_user",
      value: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta[0].estatus === 2) {
        document.getElementById("btnActivar_Usuarios_43").style.display =
          "block";
        document.getElementById("btnEliminar_Usuarios_43").style.display =
          "none";
      } else {
        document.getElementById("btnActivar_Usuarios_43").style.display =
          "none";
        document.getElementById("btnEliminar_Usuarios_43").style.display =
          "block";
      }
      $("#txtUpdatePKUsuarios_43").val(id);
      $("#txtUpdateUser").val(respuesta[0].usuario);
      $("#txtUpdateUsuarios_43").val(respuesta[0].nombre);
      //loadComboRoles(respuesta[0].rol, "cmbUpdateRoles");
    //   if (respuesta[0].perfil > 10) {
        //$("#chkUpdatePerfil").prop("checked", true);
        $("#secUpdatePerfiles").css("display", "block");
        loadComboPerfiles(respuesta[0].perfil, "cmbUpdatePerfiles");
    //   } else {
    //     $("#chkUpdatePerfil").prop("checked", false);
    //     $("#secUpdatePerfiles").css("display", "none");
    //     loadComboPerfiles(respuesta[0].perfil, "cmbUpdatePerfiles");
    //   }
      $("#chkUpdatePerfil").on("change", function () {
        if ($(this).is(":checked")) {
          $("#secUpdatePerfiles").css("display", "block");
          loadComboPerfiles("", "cmbUpdatePerfiles");
        } else {
          $("#secUpdatePerfiles").css("display", "none");
        }
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function lobby_notify(string, icono, classStyle, carpeta) {
  Lobibox.notify(classStyle, {
    size: "mini",
    rounded: true,
    delay: 4000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: false,
    img: "../../img/" + carpeta + icono,
    msg: string,
  });
}

function loadComboUsuario(data, input) {
  $("#" + input + "").html("");
  var html = '<option data-placeholder="true"></option>';
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_employer" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta combo:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKEmpleado) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKEmpleado +
            '" ' +
            selected +
            ">" +
            respuesta[i].Nombre +
            "</option>";
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      } else {
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadComboPerfiles(data, input) {
  $("#" + input + "").html("");
  var html = '<option data-placeholder="true"></option>';
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_profiles" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta combo:", respuesta);

      if (respuesta !== "" && respuesta !== null && respuesta.length != 0) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].id) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].id +
            '" ' +
            selected +
            ">" +
            respuesta[i].nombre +
            "</option>";
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      } else {
        html +=
          '<option value="vacio" disabled>No hay perfiles que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadComboRoles(data, input) {
  var html = '<option data-placeholder="true"></option>';
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_rols" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta combo:", respuesta);

      if (respuesta !== "" && respuesta !== null && respuesta.length != 0) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].id) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].id +
            '" ' +
            selected +
            ">" +
            respuesta[i].rol +
            "</option>";
        });

        //html += '<option value="agregar_nuevo" style="font-weight:bold;color:white;background-color: #006dd9 !important;">Agregar nuevo</option>';
      } else {
        html +=
          '<option value="vacio" disabled>No hay perfiles que mostrar</option>';
      }
      //console.log("Array estado civil",civilState);

      $("#" + input + "").html(html);
      $("#" + input)
        .next()
        .attr("data-toggle", "tooltip");
      $("#" + input)
        .next()
        .attr("data-placement", "top");
      $("#" + input)
        .next()
        .attr("title", "Define el tipo de usuario y el acceso al sistema");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function validar_clave(item) {
  const pass = item.value;
  const invalidDiv = item.nextElementSibling.nextElementSibling;
  const reg =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/;
  if (reg.test(pass)) {
    console.log("Bien");
    invalidDiv.style.display = "none";
    item.classList.remove("is-invalid");
    return true;
  } else {
    console.log("Mal");
    invalidDiv.style.display = "block";
    item.classList.add("is-invalid");
    return false;
  }
}

function validarCorreo(item) {
  const val = item.value;
  const invalidDiv = item.nextElementSibling;

  const reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  const regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(val) && regOficial.test(val)) {
    invalidDiv.style.display = "none";
    invalidDiv.innerText = "El usuario debe tener un correo.";
    item.classList.remove("is-invalid");
  } else if (reg.test(val)) {
    invalidDiv.style.display = "none";
    invalidDiv.innerText = "El usuario debe tener un correo.";
    item.classList.remove("is-invalid");
  } else {
    invalidDiv.style.display = "block";
    invalidDiv.innerText = "El correo debe ser valido.";
    item.classList.add("is-invalid");
  }
}

function validEmptyInput(item) {
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  for (let i = 0; i < parent.children.length; i++) {
    if (parent.children[i].classList.contains("invalid-feedback")) {
      invalidDiv = parent.children[i];
      break;
    }
  }
  if (!val) {
    item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
}

$("#btn_aceptar_eliminar_Usuarios_43").on("click", function () {
  if ($("#txtPKUsuarios_43D").val() !== "") {
    var idUser = $("#txtPKUsuarios_43D").val();
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "delete_data",
        funcion: "delete_user",
        value: idUser,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta === 1) {
          $("#tblUsuarios").DataTable().ajax.reload();
          $("#eliminar_Usuarios_43").modal("toggle");
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/chat/notificacion_error.svg",
            msg: "¡Usuario actualizado!",
          });
        } else {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Ocurrió un error al eliminar",
          });
        }
      },
      error: function (error) {},
    });
  }
});

$("#btnPermisosActualizacion").on("click", function () {
  var idUser = $("#txtUpdatePKUser").val();
  //console.log("usuario en permisos:",idUser);
  Redireccionar(idUser);
});

function changeEmpCheck() {
  $("#invalid-empleadoUs").css("display", "none");
  $("#cmbEmpleadoUsuario").removeClass("is-invalid");
  $("#invalid-nombreUs").css("display", "none");
  $("#txtNameUser").removeClass("is-invalid");
  $("#invalid-PrimerAppUs").css("display", "none");
  $("#txtPrimerApp").removeClass("is-invalid");
  if ($(this).is(":checked")) {
    $("#secEmpleado").css("display", "block");
    $("#cmbEmpleadoUsuario").attr("required", true);
    $("#txtNameUser").attr("required", false);
    loadComboUsuario("", "cmbEmpleadoUsuario");
    $("#secNameEmpleado").css("display", "none");
  } else {
    $("#cmbEmpleadoUsuario").attr("required", false);
    $("#txtNameUser").attr("required", true);
    $("#secEmpleado").css("display", "none");
    $("#secNameEmpleado").css("display", "block");
  }
}

function changeProfileCheck() {
  //if ($(this).is(":checked")) {
    $("#secPerfiles").removeClass("d-none");
    loadComboPerfiles("", "cmbPerfiles");
    //$("#chkPerfilPerzonalizado").attr("disabled", true);
//   } else {
//     $("#secPerfiles").addClass("d-none");
//     $("#chkPerfilPerzonalizado").attr("disabled", false);
//   }
}

function changeProfileCustomCheck() {
//   if ($(this).is(":checked")) {
//     $("#chkPerfil").attr("disabled", true);
//   } else {
//     $("#chkPerfil").attr("disabled", false);
//   }
}

/* Reiniciar el modal al cerrarlo */
$("#agregar_Usuarios_43").on("hidden.bs.modal", function (e) {
  $("#invalid-correoUs").css("display", "none");
  $("#txtUser").removeClass("is-invalid");
  $("#txtUser").val("");
  $("#invalid-empleadoUs").css("display", "none");
  $("#txtPrimerApp").removeClass("is-invalid");
  $("#txtPrimerApp").val("");
  $("#invalid-PrimerAppUs").css("display", "none");
  $("#txtSegundoApp").val("");
  $("#chkEmpleado").prop("checked", false);
  $("#chkPerfil").prop("checked", false);
  $("#chkPerfilPerzonalizado").prop("checked", false);
  changeEmpCheck();
  changeProfileCheck();
  $("#cmbEmpleadoUsuario").removeClass("is-invalid");
  $("#invalid-nombreUs").css("display", "none");
  $("#txtNameUser").removeClass("is-invalid");
  $("#txtNameUser").val("");
  $("#invalid-passUs").css("display", "none");
  $("#id-maxUsers").text("");
});

/* Actualiza el numero de usuarios que tiene disponibles */
$("#agregar_Usuarios_43").on("show.bs.modal", function (e) {
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_NoUsuarios" },
    dataType: "json",
    success: function (respuesta) {
      $("#id-maxUsers").text(
        "Actualmente puede dar de alta a: " + respuesta.ursCount + " usuarios."
      );
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function resendEmailConfirmation(idUsuario) {
  $.ajax({
    url: "php/funciones.php",
    dataType: "json",
    method: "POST",
    data: {
      clase: "update_data",
      funcion: "resend_email",
      idUsuario,
    },
    success: function (respuesta) {
      if (respuesta.status === "success") {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "¡" + respuesta.message + "!",
          sound: "../../../sounds/sound4",
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: "../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal :(!",
        sound: "../../../sounds/sound4",
      });
    },
  });
}
