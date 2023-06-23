/* VALIAR QUE NO SE REPITA EL TIPO DE ORDEN DE INVENTARIO AGREGADO POR EL USUARIO EN AGREGAR */
function validarTipoOrdenInventario() {
  var valor = document.getElementById("txtTipoOrdenInventario").value;
  $.ajax({
    url: "../inventarios_productos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_tipoOrdenInventario",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta tipo orden de inventario validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreOrden").text(
          "El tipo de orden ya existe en el registro."
        );
        $("#invalid-nombreOrden").css("display", "block");
        $("#txtTipoOrdenInventario").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreOrden").text(
          "El tipo de orden debe tener un nombre."
        );
        $("#invalid-nombreOrden").css("display", "none");
        $("#txtTipoOrdenInventario").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* Añadir el tipo de orden de inventario */
function anadirTipoOrdenInventario() {
  if (!$("#txtTipoOrdenInventario").val()) {
    $("#invalid-nombreOrden").css("display", "block");
    $("#txtTipoOrdenInventario").addClass("is-invalid");
  }
  var badNombreOrden =
    $("#invalid-nombreOrden").css("display") === "block" ? false : true;
  if (badNombreOrden) {
    var idemp = $("#emp_id").val();
    var valor = document.getElementById("txtTipoOrdenInventario").value;

    $("#txtTipoOrdenInventario").prop("required", true);
    $.ajax({
      url: "../inventarios_productos/php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_tipoOrdenInventario",
        datos: valor,
        datos2: idemp,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar tipo orden de inventario:", respuesta);

        if (respuesta[0].status) {
          //Swal.fire('Registro exitoso',"Se guardo el tipo de orden con exito","success");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Registro agregado!",
          });
          $("#tblListadoTipoOrdenInventario").DataTable().ajax.reload();
          $("#agregar_Tipoordeninventario_51").modal("toggle");
          $("#agregarTipoOrdenInventario").trigger("reset");
        } else {
          Swal.fire(
            "Error",
            "No se guardó  el tipo de orden con exito",
            "warning"
          );
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}
