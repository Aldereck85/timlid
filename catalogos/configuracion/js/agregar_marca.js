/* VALIAR QUE NO SE REPITA LA MARCA PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarMarca() {
  var valor = document.getElementById("txtMarca").value;
  console.log("Valor marca" + valor);
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "validar_marcaProducto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreMarca").text("La marca ya existe en el registro.");
        $("#invalid-nombreMarca").css("display", "block");
        $("#txtMarca").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreMarca").text("La marca debe tener un nombre.");
        $("#invalid-nombreMarca").css("display", "none");
        $("#txtMarca").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

/* Añadir la marca */
function anadirMarca() {
  if (!$("#txtMarca").val()) {
    $("#invalid-nombreMarca").css("display", "block");
    $("#txtMarca").addClass("is-invalid");
  }
  var badNombreMarca =
    $("#invalid-nombreMarca").css("display") === "block" ? false : true;
  if (badNombreMarca) {
    var valor = document.getElementById("txtMarca").value;

    $.ajax({
      url: "php/funciones.php",
      data: { clase: "save_data", funcion: "save_marca", datos: valor },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar marca de producto:", respuesta);

        if (respuesta[0].status) {
          //Swal.fire('Registro exitoso',"Se guardo la marca con exito","success");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Registro agregado!",
            sound: '../../../../../sounds/sound4'
          });
          $("#tblMarcadeProductos").DataTable().ajax.reload();
          $("#agregar_MarcadeProductos_9").modal("hide");
          $("#invalid-nombreMarca").css("display", "none");
          $("#txtMarca").removeClass("is-invalid");
          $("#txtMarca").val("");
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Registro agregado!",
            sound: '../../../../../sounds/sound4'
          });
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}
