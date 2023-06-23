$("#btnAgregarCategoriaGastos").click(function () {
  var idemp = $("#emp_id").val();
  var usuario = $("#txtUsuario").val(); 
  if (!$("#txtNombreCategoria").val()) {
    $("#invalid-nombreGasto").css("display", "block");
    $("#txtNombreCategoria").addClass("is-invalid");
    return;
  }
  var badNombreCat = $("#invalid-nombreGasto").css("display") === "block" ? false : true;
  
  if (badNombreCat) {
    var nombreCategoria = $("#txtNombreCategoria").val();

    $.ajax({
      url: "categoria_gastos/functions/agregar_Categoria.php",
      type: "POST",
      data: {
        nombreCategoria: nombreCategoria,
        idemp: idemp,
        usuario: usuario
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
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
          $("#tblCategoriaGastos").DataTable().ajax.reload();
          $("#agregar_CategoriaGastos_47").modal("toggle");
          $("#txtNombreCategoria").val("");
          cargarCMBCategoriasG("", "cmbCategoria");
        } else {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            img: null,
            msg: "Ocurrió un error al agregar",
          });
        }
      },
    });
  }
  
});

$(document).on("click","#btnEditar_CategoriaGastos_47" ,function () {
  var id = $("#txtUpdatePKCategoriaGastos_47").val();
  var nombreEstatus = $("#txtUpdateCategoriaGastos_47").val().trim();
  var usuario = $('#txtUsuario').val();
  if ($("#editarCategoriaU")[0].checkValidity()) {
    var badNombreCatEdit =
      $("#invalid-nombreGastoEdit").css("display") === "block" ? false : true;
    if (badNombreCatEdit) {
      if (nombreEstatus.length < 1) {
        return;
      }
      $.ajax({
        url: "categoria_gastos/functions/editar_Categoria.php",
        type: "POST",
        data: {
          idCategoriaU: id,
          txtNombreU: nombreEstatus,
          usuario: usuario
        },
        success: function (data, status, xhr) {
          if (data.trim() == "1") {
            $("#editar_CategoriaGastos_47").modal("toggle");
            $("#editarCategoriaU").trigger("reset");
            $("#tblCategoriaGastos").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Registro modificado!",
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
              msg: "Ocurrió un error al editar",
            });
          }
        },
      });
    }
  } else {
    if (!$("#txtUpdateCategoriaGastos_47").val()) {
      $("#invalid-nombreGastoEdit").css("display", "block");
      $("#txtUpdateCategoriaGastos_47").addClass("is-invalid");
    }
  }
});

function obtenerIdCategoriaGastosEditar(id) {
  document.getElementById("txtUpdatePKCategoriaGastos_47").value = id;
  //document.getElementById("idCategoriaD").value = id;
  var id = "id=" + id;
  $.ajax({
    type: "POST",
    url: "categoria_gastos/functions/getCategoriaEditar.php",
    data: id,
    success: function (r) {
      var datos = JSON.parse(r);
      $("#txtUpdateCategoriaGastos_47").val(datos.html);

      var idCategoria = $("#txtUpdatePKCategoriaGastos_47").val();
      if (idCategoria != "") {
        validarExisteRelacionCatGasto(idCategoria);
      }
    },
  });
}

$(document).on("click","#btn_aceptar_eliminar_CategoriaGastos_47",function(){
  var id = $('#txtPKCategoriaGastos_47D').val();
  var usuario = $('#txtUsuario').val();
  $.ajax({
    url: "categoria_gastos/functions/eliminar_Categoria.php",
    type: "POST",
    data: {
      idEstatusD: id,
      usuario: usuario
    },
    success: function (data, status, xhr) {
      if (data == "1") {
        $("#eliminar_CategoriaGastos_47").modal("toggle");
        $("#tblCategoriaGastos").DataTable().ajax.reload();
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../img/chat/notificacion_error.svg",
          msg: "¡Registro eliminado!",
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
  });
});

function eliminarCategoria(id) {
  var usuario = $('#txtUsuario').val();
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });

  swalWithBootstrapButtons
    .fire({
      title:
        "¿Desea eliminar el registro de esta categoría y sus subcategorías relacionadas?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar categoría</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "categoria_gastos/functions/eliminar_Categoria.php",
          type: "POST",
          data: {
            idEstatusD: id,
            usuario: usuario
          },
          success: function (data, status, xhr) {
            if (data == "1") {
              $("#modalEditar").modal("toggle");
              $("#tblCategoriaGastos").DataTable().ajax.reload();
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                img: "../../img/chat/notificacion_error.svg",
                msg: "¡Registro eliminado!",
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
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

function validarUnicaCategoriaGasto() {
  var valor = document.getElementById("txtNombreCategoria").value;
  console.log("Valor categ:  " + valor);
  $.ajax({
    url: "categoria_gastos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_categoriaGasto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreGasto").text(
          "El gasto ya esta registrado en el sistema."
        );
        $("#invalid-nombreGasto").css("display", "block");
        $("#txtNombreCategoria").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreGasto").text("El gasto debe tener un nombre.");
        $("#invalid-nombreGasto").css("display", "none");
        $("#txtNombreCategoria").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarUnicaCategoriaGastoU() {
  var valor = document.getElementById("txtUpdateCategoriaGastos_47").value;
  console.log("Valor marca:  " + valor);
  $.ajax({
    url: "categoria_gastos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_categoriaGasto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreGastoEdit").text(
          "El gasto ya esta registrado en el sistema."
        );
        $("#invalid-nombreGastoEdit").css("display", "block");
        $("#txtUpdateCategoriaGastos_47").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreGastoEdit").text("El gasto debe tener un nombre.");
        $("#invalid-nombreGastoEdit").css("display", "none");
        $("#txtUpdateCategoriaGastos_47").removeClass("is-invalid");
        console.log("¡No existe!");
      }
    },
  });
}

function validarExisteRelacionCatGasto(valor) {
  $.ajax({
    url: "categoria_gastos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionCatGasto",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      /* Validar si ya existe relacion con clientes*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion con cliente", data);

        $("#txtNombreU").prop("disabled", true);
        //$("#btnEditarCategoria").prop('disabled', true);

        var eliminar = document.getElementById("idCategoriaD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarCategoria");
        modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        //nota.setAttribute("type", "text");
      } else {
        $("#txtNombreU").prop("disabled", false);
        //$("#btnEditarCategoria").prop('disabled', false);

        var eliminar = document.getElementById("txtPKCategoriaGastos_47D");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditar_CategoriaGastos_47");
        modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        //nota.setAttribute("type", "hidden");
      }
    },
  });
}

/* Reiniciar el modal al cerrarlo */
$("#agregar_CategoriaGastos").on("hidden.bs.modal", function (e) {
  $("#invalid-nombreGasto").css("display", "none");
  $("#txtNombreCategoria").removeClass("is-invalid");
  $("#txtNombreCategoria").val("");
});

$("#modalEditar").on("hidden.bs.modal", function (e) {
  $("#invalid-nombreGastoEdit").css("display", "none");
  $("#txtNombreU").removeClass("is-invalid");
  $("#txtNombreU").val("");
});

