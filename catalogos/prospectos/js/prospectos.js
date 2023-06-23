/* FUNCION SELECCIONAR CONTENEDOR */
$(".nav-link").click(function (e) {
  e.preventDefault();
  $(".nav-link").removeClass("active");
  $(".contenedor-seccion").removeClass("active");

  var contenedor = "#" + $(this).data("contenedor");
  $(this).addClass("active");
  $(contenedor).addClass("active");
});

/* FUNCION EDITAR PROSPECTO */
$("#btnEditarProspecto").click(function () {
  const idProspecto = parseInt($("#idProspecto").val());
  const nombreComercial = $("#nombreProspecto").val();
  const medioContacto = parseInt($("#medioProspecto").val());
  const data = {
    idProspecto,
    nombreComercial,
    medioContacto,
  };
  $.ajax({
    type: "POST",
    url: "./editar_datos_generales.php",
    dataType: "json",
    data,
  })
    .done(function (res) {
      const notificationType = res.status === "fail" ? "error" : res.status;
      const iconType =
        res.status === "fail" ? "notificacion_error" : "checkmark";
      Lobibox.notify(notificationType, {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: `../../../img/timdesk/${iconType}.svg`,
        msg: `¡${res.message}!`,
      });
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../img/timdesk/notificacion_error.svg",
        msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
      });
    });
});

/* FUNCION PARA TRAER LOS DATOS DE CONTACTO */
function obtenerDatosContacto(id) {
  $.ajax({
    type: "POST",
    url: "getContacto.php",
    data: {
      id,
    },
    dataType: "json",
  })
    .done(function (res) {
      if (res.status === "fail") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/notificacion_error.svg",
          msg: `¡${res.message}!`,
        });
        $("#id-contacto").val("");
        $("#nombre-contacto").val("");
        $("#apellido-contacto").val("");
        $("#puesto-contacto").val("");
        $("#telefono-contacto").val("");
        $("#celular-contacto").val("");
        $("#email-contacto").val("");
        $("#modalContacto").modal("toggle");
      } else {
        /* Extraemos los datos de la respuesta */
        const { data } = res;
        /* Insertamos los datos */
        $("#id-contacto").val(data.PKContactoCliente);
        $("#nombre-contacto").val(data.Nombres);
        $("#apellido-contacto").val(data.Apellidos);
        $("#puesto-contacto").val(data.Puesto);
        $("#telefono-contacto").val(data.Telefono);
        $("#celular-contacto").val(data.Celular);
        $("#email-contacto").val(data.Email);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../img/timdesk/notificacion_error.svg",
        msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
      });
      $("#id-contacto").val("");
      $("#nombre-contacto").val("");
      $("#apellido-contacto").val("");
      $("#puesto-contacto").val("");
      $("#telefono-contacto").val("");
      $("#celular-contacto").val("");
      $("#email-contacto").val("");
      $("#modalContacto").modal("toggle");
    });
}

/* FUNCION PARA EDITAR UN CONTACTO */
$("#guardarContacto").click(function () {
  const id = parseInt($("#id-contacto").val());
  const nombres = $("#nombre-contacto").val();
  const apellidos = $("#apellido-contacto").val();
  const puesto = $("#puesto-contacto").val();
  const telefono = $("#telefono-contacto").val();
  const celular = $("#celular-contacto").val();
  const email = $("#email-contacto").val();
  const data = {
    id,
    nombres,
    apellidos,
    puesto,
    telefono,
    celular,
    email,
  };
  $.ajax({
    type: "POST",
    url: "putContacto.php",
    dataType: "json",
    data,
  })
    .done(function (res) {
      const notificationType = res.status === "fail" ? "error" : res.status;
      const iconType =
        res.status === "fail" ? "notificacion_error" : "checkmark";
      Lobibox.notify(notificationType, {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: `../../../img/timdesk/${iconType}.svg`,
        msg: `¡${res.message}!`,
      });
      $("#tblDatosContacto").DataTable().ajax.reload();
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../img/timdesk/notificacion_error.svg",
        msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
      });
    });
});

/* FUNCION PARA ELIMINAR UN CONTACTO */
$("#eliminarContacto").click(function () {
  const id = parseInt($("#id-contacto").val());
  const data = {
    id,
  };
  swalWithBootstrapButtons
    .fire({
      title: "¿Desea eliminar el contacto?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar registro</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((value) => {
      if (value) {
        $.ajax({
          type: "POST",
          url: "deleteContacto.php",
          dataType: "json",
          data,
        })
          .done(function (res) {
            const notificationType =
              res.status === "fail" ? "error" : res.status;
            const iconType =
              res.status === "fail" ? "notificacion_error" : "checkmark";
            Lobibox.notify(notificationType, {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: `../../../img/timdesk/${iconType}.svg`,
              msg: `¡${res.message}!`,
            });
            $("#tblDatosContacto").DataTable().ajax.reload();
            $("#modalContacto").modal("toggle");
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
            });
          });
      }
    });
});

/* FUNCION PARA TRAER LOS DATOS DE NOTA */
function obtenerDatosNota(id) {
  $.ajax({
    type: "POST",
    url: "getNota.php",
    data: {
      id,
    },
    dataType: "json",
  })
    .done(function (res) {
      if (res.status === "fail") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/notificacion_error.svg",
          msg: `¡${res.message}!`,
        });
        $("#id-nota").val("");
        $("#nota-desc-edit").val("");
        $("#modalNotaEditar").modal("toggle");
      } else {
        /* Extraemos los datos de la respuesta */
        const { data } = res;
        /* Insertamos los datos */
        $("#id-nota").val(data.PKBitacoraNotas);
        $("#nota-desc-edit").val(data.Nota);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../img/timdesk/notificacion_error.svg",
        msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
      });
      $("#id-nota").val("");
      $("#nota-desc-edit").val("");
      $("#modalNotaEditar").modal("toggle");
    });
}

/* FUNCION PARA EDITAR UNA NOTA */
$("#guardarNota").click(function () {
  const id = parseInt($("#id-nota").val());
  const nota = $("#nota-desc-edit").val();
  const data = {
    id,
    nota,
  };
  $.ajax({
    type: "POST",
    url: "putNota.php",
    dataType: "json",
    data,
  })
    .done(function (res) {
      const notificationType = res.status === "fail" ? "error" : res.status;
      const iconType =
        res.status === "fail" ? "notificacion_error" : "checkmark";
      if (res.status === "success") {
        $("#nota-par-" + id).text(nota);
      }
      Lobibox.notify(notificationType, {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: `../../../img/timdesk/${iconType}.svg`,
        msg: `¡${res.message}!`,
      });
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../img/timdesk/notificacion_error.svg",
        msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
      });
    });
});

/* FUNCION PARA ELIMINAR UNA NOTA */
function elimarNota(id) {
  const data = {
    id: parseInt(id),
  };
  swalWithBootstrapButtons
    .fire({
      title: "¿Desea eliminar la nota?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter2">Eliminar nota</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((value) => {
      if (value.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "deleteNota.php",
          dataType: "json",
          data,
        })
          .done(function (res) {
            console.log(res);
            const notificationType =
              res.status === "fail" ? "error" : res.status;
            const iconType =
              res.status === "fail" ? "notificacion_error" : "checkmark";
            Lobibox.notify(notificationType, {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: `../../../img/timdesk/${iconType}.svg`,
              msg: `¡${res.message}!`,
            });
            if (res.status === "success") {
              $("#nota_" + id).remove();
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
            });
          });
      }
    });
}

/* Custom buttons sweet alert */
const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    confirmButton: "btn",
    cancelButton: "btn",
  },
  buttonsStyling: false,
});
