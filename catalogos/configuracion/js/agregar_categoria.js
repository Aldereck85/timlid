/* VALIAR QUE NO SE REPITA LA CATEGORIA PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarCategoria() {
  var valor = document.getElementById("txtCategoria").value;
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
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreCat").text("La categoria ya existe en el sistema.");
        $("#invalid-nombreCat").css("display", "block");
        $("#txtCategoria").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreCat").text("La categoria debe tener un nombre.");
        $("#invalid-nombreCat").css("display", "none");
        $("#txtCategoria").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarCategoriaClientes() {
  var valor = document.getElementById("txtCategoriaCliente").value;
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
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreCatCliente").text("La categoria ya existe en el sistema.");
        $("#invalid-nombreCatCliente").css("display", "block");
        $("#txtCategoriaCliente").addClass("is-invalid");
      } else {
        $("#invalid-nombreCatCliente").text("La categoria debe tener un nombre.");
        $("#invalid-nombreCatCliente").css("display", "none");
        $("#txtCategoriaCliente").removeClass("is-invalid");
      }
    },
  });
}

/* Añadir la categoría */
function anadirCategoria() {
  if (!$("#txtCategoria").val()) {
    $("#invalid-nombreCat").css("display", "block");
    $("#txtCategoria").addClass("is-invalid");
  }
  var badNombreCat =
    $("#invalid-nombreCat").css("display") === "block" ? false : true;
  if (badNombreCat) {
    var valor = document.getElementById("txtCategoria").value;

    if (valor == "" || valor.length < 1) {
      $("#txtCategoria")[0].reportValidity();
      $("#txtCategoria")[0].setCustomValidity("Completa este campo.");
      return;
    }
    $.ajax({
      url: "php/funciones.php",
      data: { clase: "save_data", funcion: "save_categoria", datos: valor },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar categoria de producto:", respuesta);

        if (respuesta[0].status) {
          $("#txtCategoria").val("");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Registro agregado!",
            sound: '../../../../../sounds/sound4'
          });
          $("#tblCategoriadeProductos").DataTable().ajax.reload();
          $("#agregar_CategoriadeProductos_8").modal("toggle");
          $("#formDatosCategoria").trigger("reset");
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
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
}

function anadirCategoriaClientes() {
  if (!$("#txtCategoriaCliente").val()) {
    $("#invalid-nombreCatCliente").css("display", "block");
    $("#txtCategoriaCliente").addClass("is-invalid");
  }
  var badNombreCat =
    $("#invalid-nombreCatCliente").css("display") === "block" ? false : true;
  if (badNombreCat) {
    var valor = document.getElementById("txtCategoriaCliente").value;

    if (valor == "" || valor.length < 1) {
      $("#txtCategoriaCliente")[0].reportValidity();
      $("#txtCategoriaCliente")[0].setCustomValidity("Completa este campo.");
      return;
    }
    $.ajax({
      url: "php/funciones.php",
      data: { clase: "save_data", funcion: "save_categoriaClientes", datos: valor },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta[0].status) {
          $("#txtCategoriaCliente").val("");
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
            sound: '../../sounds/sound4'
          });
          $("#tblCategoriadeClientes").DataTable().ajax.reload();
          $("#agregar_CategoriadeClientes_87").modal("toggle");
          $("#formDatosCategoriaClientes").trigger("reset");
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡Algo salio mal!",
            sound: '../../sounds/sound4'
          });
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}
