"use-strict";

$(document).ready(function () {
  /* Funcion para editar la contraseña */
  $("#guardarContrasenia").click(function () {
    const currentPassInput = document.querySelector("#contasenia-actual");
    const newPassInput = document.querySelector("#contrasenia-nueva");
    const idUsuario = document.querySelector("#id-usuario").value;

    !currentPassInput.value
      ? currentPassInput.classList.add("is-invalid")
      : currentPassInput.classList.remove("is-invalid");

    !newPassInput.value
      ? newPassInput.classList.add("is-invalid")
      : newPassInput.classList.remove("is-invalid");

    if (
      currentPassInput.classList.contains("is-invalid") ||
      newPassInput.classList.contains("is-invalid")
    ) {
      return;
    } else {
      const data = {
        idUsuario,
        currentPassInput: currentPassInput.value,
        newPassInput: newPassInput.value,
      };
      $.ajax({
        type: "POST",
        url: "functions/cambiar_contrasena.php",
        dataType: "json",
        data,
        success: function (res) {
          const notificationType = res.status === "fail" ? "error" : res.status;
          Lobibox.notify(notificationType, {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: `¡${res.message}!`,
          });
          $("#editar_Contrasenia").modal("hide");
          currentPassInput.value = "";
          newPassInput.value = "";
        },
        error: function (error) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
          });
        },
      });
    }
  });

  $("#croppie-avatar").croppie({
    viewport: {
      width: 200,
      height: 200,
      type: "circle",
    },
    boundary: {
      width: 300,
      height: 300,
    },
  });

  $("#avatar-input").on("change", function (e) {
    $("#modalAvatar").modal("show");
  });

  $("#modalAvatar").on("shown.bs.modal", function () {
    const inputImgPerfil = document.getElementById("avatar-input");
    var tmppath = URL.createObjectURL(inputImgPerfil.files[0]);
    $("#croppie-avatar").croppie("bind", {
      url: tmppath,
      zoom: 0,
    });
  });

  $("#croppie-btn").click(function () {
    $("#croppie-avatar")
      .croppie("result", {
        type: "blob",
        size: "viewport",
        format: "png",
        quaity: 1,
        circle: true,
      })
      .then(function (blob) {
        // do something with cropped blob
        var tmppath = URL.createObjectURL(blob);
        var fileInput = $("#avatar-input")[0].files[0];
        var file = new File([blob], "avatar.png", { type: blob.type });
        var form_data = new FormData();
        form_data.append("avatar", file);
        $.ajax({
          url: "functions/cambiarAvatar.php",
          type: "POST",
          cache: false,
          dataType: "json",
          contentType: false,
          processData: false,
          data: form_data,
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
              $("#modalAvatar").modal("hide");
              location.reload();
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
              msg: "¡Error en la peticion!",
              sound: "../../../sounds/sound4",
            });
          },
        });
      });
  });
});
