$(document).ready(function () {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
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

      $("#cmbEstatusMarca").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  });

  $('#editar_Marca').on('hidden.bs.modal', function (event) {
    $("#invalid-nombreMarcaEdit").css("display", "none");
    $("#txtMarcaU").removeClass("is-invalid");
  });
});

function obtenerIdMarcaEditar(id) {
  document.getElementById("txtPKMarca").value = id;
  document.getElementById("txtPKMarcaD").value = id;

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_datos_marca", datos: id },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos a editar de una marca: ", respuesta);

      $("#txtMarcaU").val(respuesta[0].marca);
      $("#txtMarActual").val(respuesta[0].marca);
      $("#txtMarcaD").text(respuesta[0].marca);

      if (respuesta[0].estatus == 1) {
        console.log("Es Activo");
        $("#activeMarcProd").prop("checked", true);
      } else {
        console.log("Es Inactivo");
        $("#activeMarcProd").prop("checked", false);
      }

      if (respuesta[0].noEliminar == 1) {
        var eliminar = document.getElementById("btnEliminarMarcaU");
        eliminar.style.display = "none";
        $("#activeMarcProd").attr("disabled", true);
        var nota = document.getElementById("notaEstatusU");
        nota.setAttribute("type", "text");
      } else {
        var eliminar = document.getElementById("btnEliminarMarcaU");
        eliminar.style.display = "block";
        $("#activeMarcProd").attr("disabled", false);
        var nota = document.getElementById("notaEstatusU");
        nota.setAttribute("type", "hidden");
      }
    },
    error: function (error) {
      Lobibox.notify("error", {
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
$(document).on("click", "#btnEditarMarca", function () {
  if (!$("#txtMarcaU").val()) {
    $("#invalid-nombreMarcaEdit").css("display", "block");
    $("#txtMarcaU").addClass("is-invalid");
  }
  var badNombreMarcaEdit =
    $("#invalid-nombreMarcaEdit").css("display") === "block" ? false : true;
  if (badNombreMarcaEdit) {
    var estatus = $("#activeMarcProd").prop("checked") ? 1 : 2;
    var marca = $("#txtMarcaU").val();
    var id = $("#txtPKMarca").val();

    if (marca.length < 1) {
      return;
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_marca",
        datos: estatus,
        datos2: marca,
        datos3: id,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta editar marca:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Registro modificado!",
            sound: '../../../../../sounds/sound4'
          });
          $("#tblListadoMarcas").DataTable().ajax.reload();
          $("#editar_Marca").modal("toggle");
          $("#aditarMarca").trigger("reset");
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
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
});

function cambiarColor() {
  if ($("#cmbEstatusMarca").val() == 1) {
    console.log("Es Activo");
    $("#cmbEstatusMarca").css({
      "background-color": "#28c67a",
      color: "#FFFFFF",
    });
  } else {
    console.log("Es Inactivo");
    $("#cmbEstatusMarca").css({ "background-color": "#cac8c6" });
  }
}

/* VALIAR QUE NO SE REPITA LA MARCA POR PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarMarcaU() {
  var valor = document.getElementById("txtMarcaU").value;
  console.log("Valor marca" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_marcaProducto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (
        parseInt(data[0]["existe"]) == 1 &&
        $("#txtMarActual").val() != $("#txtMarcaU").val()
      ) {
        $("#invalid-nombreMarcaEdit").text("La marca ya existe en el sistema.");
        $("#invalid-nombreMarcaEdit").css("display", "block");
        $("#txtMarcaU").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreMarcaEdit").text("La marca debe tener un nombre.");
        $("#invalid-nombreMarcaEdit").css("display", "none");
        $("#txtMarcaU").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}
