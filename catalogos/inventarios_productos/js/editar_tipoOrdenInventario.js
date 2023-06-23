$(document).ready(function () {
  var html = "";
  var selected;
  $.ajax({
    url: "../../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estatusGral" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta estatus: ", respuesta);

      $.each(respuesta, function (i) {
        html +=
          '<option id="opEG-' +
          respuesta[i].PKEstatusGeneral +
          '" value="' +
          respuesta[i].PKEstatusGeneral +
          '" ' +
          selected +
          ">" +
          respuesta[i].Estatus +
          "</option>";
      });

      $("#cmbEstatusTipoOrdenInventario").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function obtenerIdTipoOrdenInventarioEditar(id) {
  document.getElementById("txtPKTipoOrdenInventario").value = id;
  document.getElementById("txtPKTipoOrdenInventarioD").value = id;

  $.ajax({
    url: "../../inventarios_productos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_tipoOrdenInventario",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(
        "respuesta datos a editar de un tipo de orden de inventario: ",
        respuesta
      );

      $("#txtTipoOrdenInventarioU").val(respuesta[0].tipoOrdenInventario);
      $("#cmbEstatusTipoOrdenInventario").val(respuesta[0].estatus);
      $("#txtTipOIActual").val(respuesta[0].tipoOrdenInventario);
      $("#txtTipoOrdenInventarioD").val(respuesta[0].tipoOrdenInventario);

      if (respuesta[0].estatus == 1) {
        console.log("Es Activo");
        $("#activeOrdenInv").prop("checked", true);
      } else {
        console.log("Es Inactivo");
        $("#activeOrdenInv").prop("checked", false);
      }

      $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
      $("#opEG-2").css({ "background-color": "#cac8c6" });

      if (respuesta[0].noEliminar == 1) {
        var eliminar = document.getElementById(
          "btnEliminarTipoOrdenInventarioU"
        );
        eliminar.style.display = "none";
        $("#activeOrdenInv").attr("disabled", true);
        var nota = document.getElementById("notaEstatusU");
        nota.setAttribute("type", "text");
      } else {
        var eliminar = document.getElementById(
          "btnEliminarTipoOrdenInventarioU"
        );
        eliminar.style.display = "block";
        $("#activeOrdenInv").attr("disabled", false);
        var nota = document.getElementById("notaEstatusU");
        nota.setAttribute("type", "hidden");
      }
    },
    error: function (error) {
      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top", //or 'center bottom'
        icon: true,
        img: "../../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal!",
        sound: '../../../../../sounds/sound4'
      });
      console.log(error);
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
$(document).on("click", "#btnEditarTipoOrdenInventario", function () {
  if ($("#aditarTipoOrdenInventario")[0].checkValidity()) {
    var estatus = $("#activeOrdenInv").prop("checked") ? 1 : 2;
    var tipoOrdenInventario = $("#txtTipoOrdenInventarioU").val();
    console.log(estatus, tipoOrdenInventario);
    var id = $("#txtPKTipoOrdenInventario").val();
    var badNombreInv =
      $("#invalid-nombreCom").css("display") === "block" ? false : true;
    if (badNombreInv) {
      $.ajax({
        url: "../../inventarios_productos/php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_tipoOrdenInventario",
          datos: estatus,
          datos2: tipoOrdenInventario,
          datos3: id,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log("respuesta editar tipoOrdenInventario:", respuesta);

          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Registro modificado!",
              sound: '../../../../../sounds/sound4'
            });
            $("#tblListadoTipoOrdenInventario").DataTable().ajax.reload();

            $("#editar_TipoOrdenInventario").modal("toggle");
            $("#aditarTipoOrdenInventario").trigger("reset");
          } else {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  } else {
    $("#invalid-nombreOrdenEdit").css("display", "block");
    $("#txtTipoOrdenInventarioU").addClass("is-invalid");
  }
});

function cambiarColor() {
  if ($("#cmbEstatusTipoOrdenInventario").val() == 1) {
    console.log("Es Activo");
    $("#cmbEstatusTipoOrdenInventario").css({
      "background-color": "#28c67a",
      color: "#FFFFFF",
    });
  } else {
    console.log("Es Inactivo");
    $("#cmbEstatusTipoOrdenInventario").css({ "background-color": "#cac8c6" });
  }
}

/* VALIAR QUE NO SE REPITA EL TIPO DE ORDEN DE INVENTARIO AGREGADO POR EL USUARIO EN AGREGAR */
function validarTipoOrdenInventarioU() {
  var valor = document.getElementById("txtTipoOrdenInventarioU").value;
  console.log("Valor tipo de orden de inventario" + valor);
  $.ajax({
    url: "../../inventarios_productos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_tipoOrdenInventario",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta tipo de orden de inventario validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (
        parseInt(data[0]["existe"]) == 1 &&
        $("#txtTipOIActual").val() != $("#txtTipoOrdenInventarioU").val()
      ) {
        $("#invalid-nombreOrdenEdit").text(
          "El tipo de orden ya esta en el registro."
        );
        $("#invalid-nombreOrdenEdit").css("display", "block");
        $("#txtTipoOrdenInventarioU").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreOrdenEdit").text(
          "El tipo de orden debe tener un nombre."
        );
        $("#invalid-nombreOrdenEdit").css("display", "none");
        $("#txtTipoOrdenInventarioU").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);
    },
  });
}
