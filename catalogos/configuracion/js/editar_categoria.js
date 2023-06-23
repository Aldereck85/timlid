$(document).ready(function () {
  var html = "";
  var selected;
  /* $.ajax({
    url: "php/funciones.php",
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

      $("#cmbEstatusCategoria").append(html);
    },
    error: function (error) {
      console.log(error);
    },
  }); */
  //
  $('#editar_CategoriadeProductos_8').on('hidden.bs.modal', function (event) {
    $("#invalid-nombreCatEdit").css("display", "none");
    $("#txtCategoriaU").removeClass("is-invalid");
  });
});

function obtenerIdCategoriaEditar(id) {
  document.getElementById("txtPKCategoria").value = id;
  document.getElementById("txtPKCategoriaD").value = id;

  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_datos_categoria", datos: id },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos a editar de una categoria: ", respuesta);

      $("#txtCategoriaU").val(respuesta[0].categoria);
      $("#txtCatActual").val(respuesta[0].categoria);
      $("#txtCategoriaD").text(respuesta[0].categoria);

      if (respuesta[0].estatus == 1) {
        console.log("Es Activo");
        $("#activeMarca").prop("checked", true);
      } else {
        console.log("Es Inactivo");
        $("#activeMarca").prop("checked", false);
      }

      $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
      $("#opEG-2").css({ "background-color": "#cac8c6" });

      if (respuesta[0].noEliminar == 1) {
        var eliminar = document.getElementById("btnEliminarU");
        eliminar.style.display = "none";
        $("#activeMarca").attr("disabled", true);
        var nota = document.getElementById("notaEstatusU");
        nota.setAttribute("type", "text");
      } else {
        var eliminar = document.getElementById("btnEliminarU");
        eliminar.style.display = "block";
        $("#activeMarca").attr("disabled", false);
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
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal!",
        sound: '../../../../../sounds/sound4'
      });
      console.log(error);
    },
  });
}

function obtenerIdCategoriaClienteEditar(id) {
  document.getElementById("txtPKCategoriaCliente").value = id;
  document.getElementById("txtPKCategoriaClienteD").value = id;

  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_datos_categoria_clientes", datos: id },
    dataType: "json",
    success: function (respuesta) {

      $("#txtCategoriaUclientes").val(respuesta[0].categoria);
      $("#txtCatClienteActual").val(respuesta[0].categoria);
      $("#txtCategoriaClientesD").text(respuesta[0].categoria);

      if (respuesta[0].estatus == 1) {
        $("#activeCatCliente").prop("checked", true);
      } else {
        $("#activeCatCliente").prop("checked", false);
      }

      $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
      $("#opEG-2").css({ "background-color": "#cac8c6" });

      if (respuesta[0].existe == 1) {
        var eliminar = document.getElementById("btnEliminarUClientes");
        eliminar.style.display = "none";
        $("#activeCatCliente").attr("disabled", true);
        var nota = document.getElementById("notaEstatusUClientes");
        nota.setAttribute("type", "text");
      } else {
        var eliminar = document.getElementById("btnEliminarUClientes");
        eliminar.style.display = "block";
        $("#activeCatCliente").attr("disabled", false);
        var nota = document.getElementById("notaEstatusUClientes");
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
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal!",
        sound: '../../sounds/sound4'
      });
      console.log(error);
    },
  });
}

//Validar que se hayan completado todos los campos antes de pasar a la siguiente pestaña
$(document).on("click", "#btnEditarCategoria", function () {
  if (!$("#txtCategoriaU").val()) {
    $("#invalid-nombreCatEdit").css("display", "block");
    $("#txtCategoriaU").addClass("is-invalid");
  }
  var badNombreCatEdit =
    $("#invalid-nombreCatEdit").css("display") === "block" ? false : true;
  if (badNombreCatEdit) {
    var estatus = $("#activeMarca").prop("checked") ? 1 : 2;
    var categoria = $("#txtCategoriaU").val();
    var id = $("#txtPKCategoria").val();

    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "update_data",
        funcion: "edit_categoria",
        datos: estatus,
        datos2: categoria,
        datos3: id,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta editar categoría:", respuesta);
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

          $("#tblCategoriadeProductos").DataTable().ajax.reload();
          $("#editar_CategoriadeProductos_8").modal("hide");
          $("#invalid-nombreCatEdit").css("display", "none");
          $("#txtCategoriaU").removeClass("is-invalid");
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
      },
    });
  }
});

$(document).on("click", "#btnEditarCategoriaClientes", function () {
  if (!$("#txtCategoriaUclientes").val()) {
    $("#invalid-nombreCatClientesEdit").css("display", "block");
    $("#txtCategoriaUclientes").addClass("is-invalid");
  }
  var badNombreCatEdit =
    $("#invalid-nombreCatClientesEdit").css("display") === "block" ? false : true;
  if (badNombreCatEdit) {
    var estatus = $("#activeCatCliente").prop("checked") ? 1 : 2;
    var categoria = $("#txtCategoriaUclientes").val();
    var id = $("#txtPKCategoriaCliente").val();

    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "update_data",
        funcion: "edit_categoria_clientes",
        datos: estatus,
        datos2: categoria,
        datos3: id,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Registro modificado!",
            sound: '../../sounds/sound4'
          });

          $("#tblCategoriadeClientes").DataTable().ajax.reload();
          $("#invalid-nombreCatClientesEdit").css("display", "none");
          $("#txtCategoriaUclientes").removeClass("is-invalid");
        } else if(respuesta[0].msj){
          Lobibox.notify('warning', {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: true,
            img: '../../img/chat/warning_circle.svg',
            msg: respuesta[0].msj,
            sound: '../../sounds/sound4'
          });
        }else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal!",
            sound: '../../sounds/sound4'
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
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal!",
          sound: '../../sounds/sound4'
        });
      },
    });
  }
});

function cambiarColor() {
  if ($("#cmbEstatusCategoria").val() == 1) {
    console.log("Es Activo");
    $("#cmbEstatusCategoria").css({
      "background-color": "#28c67a",
      color: "#FFFFFF",
    });
  } else {
    console.log("Es Inactivo");
    $("#cmbEstatusCategoria").css({ "background-color": "#cac8c6" });
  }
}

/* VALIAR QUE NO SE REPITA LA CATEGORÍA POR PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarCategoriaU() {
  var valor = document.getElementById("txtCategoriaU").value;
  console.log("Valor categoria" + valor);
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_categoriaProducto",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta categoría validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (
        parseInt(data[0]["existe"]) == 1 &&
        $("#txtCatActual").val() != $("#txtCategoriaU").val()
      ) {
        $("#invalid-nombreCatEdit").text(
          "La categoria ya existe en el sistema."
        );
        $("#invalid-nombreCatEdit").css("display", "block");
        $("#txtCategoriaU").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreCatEdit").text("La categoria debe tener un nombre.");
        $("#invalid-nombreCatEdit").css("display", "none");
        $("#txtCategoriaU").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
    error: function(e){
      console.log(e);
    }
  });
}

function validarCategoriaClientesU() {
  var valor = document.getElementById("txtCategoriaUclientes").value;
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_categoriaCliente",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (
        parseInt(data[0]["existe"]) == 1 &&
        $("#txtCatClienteActual").val() != $("#txtCategoriaUclientes").val()
      ) {
        $("#invalid-nombreCatClientesEdit").text(
          "La categoria ya existe en el sistema."
        );
        $("#invalid-nombreCatClientesEdit").css("display", "block");
        $("#txtCategoriaUclientes").addClass("is-invalid");
      } else {
        $("#invalid-nombreCatClientesEdit").text("La categoria debe tener un nombre.");
        $("#invalid-nombreCatClientesEdit").css("display", "none");
        $("#txtCategoriaUclientes").removeClass("is-invalid");
      }
    },
    error: function(e){
      console.log(e);
    }
  });
}