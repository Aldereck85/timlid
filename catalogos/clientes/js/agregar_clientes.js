$(document).ready(function () {
  $(window).on("load", function () {
    $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
    $("#opEG-2").css({ "background-color": "#cac8c6", color: "#FFFFFF" });

    //Cambiar de color el combo del estatus al abrir por primera vez la página
    if ($("#cmbEstatusCliente").val() == 1) {
      $("#cmbEstatusCliente").css({
        "background-color": "#28c67a",
        color: "#FFFFFF",
      });
    } else {
      $("#cmbEstatusCliente").css({
        "background-color": "#cac8c6",
        color: "#FFFFFF",
      });
    }

  });

  //Asignar plugin Slim a combos desplegables, para la opción de búsqueda de opciones dentro del combo
  new SlimSelect({
    select: "#cmbMedioContactoCliente",
    placeholder: "Seleciona un medio de contacto",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
    /*addable: function (value) {
      validarMedioContacto(value);
    },*/
  });

  new SlimSelect({
    select: "#cmbVendedor",
    placeholder: "Seleciona un vendedor",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbCategoria",
  });

  new SlimSelect({
    select: "#cmbRegimen",
    placeholder: "Seleciona un régimen fiscal",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });

});

function validarMedioContacto(valor) {
  console.log("Valor medio de contacto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_medioContactoCliente",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta medio de contacto validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("¡Ya existe!");
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡El medio de contacto ya existe en el sistema!",
        });
      } else {
        console.log("¡No existe!");

        anadirMedioContacto(valor);
      }
    },
  });
}

/* Añadir el medio de contacto */
function anadirMedioContacto(valor) {
  console.log("Valor medio de contacto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_medioContactoCliente",
      datos: valor,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar medio de contacto de cliente:", respuesta);

      if (respuesta[0].status) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Medio de contacto registrado correctamente!",
        });
        cargarCMBMedioContactoCliente(valor, "cmbMedioContactoCliente");
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal!",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("change", "#cmbMedioContactoCliente", function () {
  var medioContacto = $("#cmbMedioContactoCliente").val();

  console.log("Selección:" + medioContacto);
  if (medioContacto == "add") {
    window.location.href = "../medios_contacto";
  }
});

$(document).on("change", "#cmbVendedor", function () {
  var vendedor = $("#cmbVendedor").val();

  console.log("Selección:" + vendedor);
  if (vendedor == "add") {
    window.location.href = "../vendedores";
  }
});
