
$(document).on('click','#CargarResponsableGastos',function(){
  new SlimSelect({
    select: '#cmbEmpleadoResGasto',
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar empleado...",
  });
  cargarCMBEmpleados("", "cmbEmpleadoResGasto");

});

function agregarResponsable() {
  if (!$("#cmbEmpleadoResGasto").val()) {
    $("#invalid-responsable").css("display", "block");
    $("#cmbEmpleadoResGasto").addClass("is-invalid");
  }
  var badResponsable =
    $("#invalid-responsable").css("display") === "block" ? false : true;
  if (badResponsable) {
    var fkempleado = $("#cmbEmpleadoResGasto").val();
    $.ajax({
      url: "responsables_gastos/functions/agregar_Responsable.php",
      type: "POST",
      data: {
        fkempleado: fkempleado,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#agregar_ResponsableGastos_49").modal("toggle");
          $("#agregarResponsable").trigger("reset");
          $("#tblResponsableGastos").DataTable().ajax.reload();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Registro agregado!",
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
            img: null,
            msg: "Ocurrió un error al agregar",
          });
        }
      }
    });
  } else {
    console.log("bad invalid");
  }
}

$(document).on("click", "#btnEditar_ResponsableGastos_49", function () {
  var id = $("#txtUpdatePKResponsableGastos_49").val();
  var fk = $("#idFkResponsableU").val();
  var fkresponsable = $("#txtResponsableGastosU_49").val().trim();
  if (fk == fkresponsable) {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Corresponde al mismo responsable!",
    });
    return;
  }
  if (parseInt(fkresponsable) >= 1) {
    var badNombreRes =
      $("#invalid-responsableEdit").css("display") === "block" ? false : true;
    if (badNombreRes) {
      $.ajax({
        url: "responsables_gastos/functions/editar_Responsable.php",
        type: "POST",
        data: {
          idResponsableU: id,
          txtResponsableU: fkresponsable,
        },
        success: function (data, status, xhr) {
          if (data.trim() == "1") {
            $("#editar_ResponsableGastos_49").modal("toggle");
            $("#editarResponsableU").trigger("reset");
            $("#tblResponsableGastos").DataTable().ajax.reload();
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
    $("#invalid-responsableEdit").css("display", "block");
    $("#txtResponsableU").addClass("is-invalid");
  }
});

function obtenerIdResponsableEditar(id) {
  $("#cmbResponsableU").attr("disabled", true);
  $.ajax({
    type: "POST",
    url: "responsables_gastos/functions/getResponsableEditar.php",
    data: {
      id
    },
    success: function (res) {
      var datos = JSON.parse(res);
      if (datos.status === "success") {
        $("#txtempleadoResponsable_49D").val(datos.fkresp);
        $("#txtResponsableGastos_49D").val(datos.nom);
        $('#eliminar_ResponsableGastos_49').modal('show');
      } else {
        $("#txtempleadoResponsable_49D").val("");
        $("#txtResponsableGastos_49D").val("");
      }
    },
  });
}

$(document).on("click","#btn_aceptar_eliminar_ResponsableGastos_49",function(){
  console.log($("#txtempleadoResponsable_49D").val());
  var idRes = $('#txtempleadoResponsable_49D').val();
  console.log(idRes);
  $.ajax({
    type: "POST",
    url: "responsables_gastos/functions/eliminar_Responsable.php",
    data: {
      id: idRes
    },
    success: function (data, status, xhr) {
      console.log(data);
      if (data == "1") {
        $("#eliminar_ResponsableGastos_49").modal("hide");
        $("#tblResponsableGastos").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../img/chat/checkmark.svg",
          msg: "¡Registro eliminado!",
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "Ocurrió un error al eliminar",
        });
      }
    },
  });
});

function eliminarResponsable(id) {
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
      title: "¿Desea eliminar el registro del responsable de gasto?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar responsable</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "responsables_gastos/functions/eliminar_Responsable.php",
          type: "POST",
          data: {
            idResponsableD: id,
          },
          success: function (data, status, xhr) {
            if (data == "1") {
              $("#modalEditar").modal("toggle");
              $("#tblResponsableGastos").DataTable().ajax.reload();
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

$(document).on("change","#cmbEmpleadoResGasto", validarExisteResponsable);

function validarExisteResponsable() {
  var valor = $("#cmbEmpleadoResGasto").val();
  $.ajax({
    url: "responsables_gastos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeResponsable",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta existe responsable: ", data);
      /* Validar si ya existe el identificador con ese usuario*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-responsable").text(
          "El responsable ya existe en el registro."
        );
        $("#invalid-responsable").css("display", "block");
        $("#cmbEmpleadoResGasto").addClass("is-invalid");
      } else {
        $("#invalid-responsable").text("El responsable debe tener un nombre.");
        $("#invalid-responsable").css("display", "none");
        $("#cmbEmpleadoResGasto").removeClass("is-invalid");
      }
    },
  });
}

function cargarCMBEmpleados(data, input) {
  console.log("kjshgkjsdhgklj");
  var html = "";
  var selected;
  $.ajax({
    url: "responsables_gastos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_empleados" },
    dataType: "json",
    success: function (respuesta) {

      html += "<option data-placeholder='true'></option>";

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEmpleado) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKEmpleado +
          '" ' +
          selected +
          ">" +
          respuesta[i].Nombres + " " +respuesta[i].PrimerApellido + " " +respuesta[i].SegundoApellido +
          "</option>";
      });
      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}


/*function validarExisteRelacion(valor) {
  //document.getElementById('txtResponsableU').disabled = true;
  $.ajax({
    url: "responsables_gastos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionResponsable",
      data: valor,
    },
    dataType: "json",

    success: function (data) {*/
      /* Validar si ya existe relacion con clientes*/
      /*if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion responsable con gastos", data);
        document.getElementById("txtResponsableU").disabled = true;

        var eliminar = document.getElementById("idResponsableD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarResponsable");
        modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        //nota.setAttribute("type", "text");
      } else {
        console.log("Sin relacion");
        document.getElementById("txtResponsableU").disabled = false;

        var eliminar = document.getElementById("idResponsableD");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditarResponsable");
        modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        //nota.setAttribute("type", "hidden");
      }
    },
  });
}*/

/*function nuevoResponsable() {
  var id = $("#txtResponsableU").val();
  $.ajax({
    type: "POST",
    url: "responsables_gastos/functions/getNuevoNombreResponsable.php",
    data: { id: id },
    success: function (r) {
      var datos = JSON.parse(r);

      $("#idResponsableN").val(datos.html);

      var fkresponsable = $("#idResponsableN").val();
      if (fkresponsable != "") {
        //validarExixteResponsableNuevo(fkresponsable);
      }
    },
  });
}*/

/*function validarExixteResponsableNuevo(valor) {
  $.ajax({
    url: "responsables_gastos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeResponsable",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese usuario*/
      /*if (parseInt(data[0]["existe"]) == 1) {

        $("#invalid-responsableEdit").text(
          "El responsable ya esta registrado en el sistema."
        );
        $("#invalid-responsableEdit").css("display", "block");
        //$("#txtResponsableU").addClass("is-invalid");
      } else {

        $("#invalid-responsableEdit").text(
          "El responsable debe tener un nombre."
        );
        $("#invalid-responsableEdit").css("display", "none");
        //$("#txtResponsableU").removeClass("is-invalid");
      }
    },
  });*/
//}

/*function CargarSlimSelect() {
  new SlimSelect({
    select: "#cmbEmpleado",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar empleado...",
  });
  new SlimSelect({
    select: "#txtResponsableU",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar empleado...",
  });
  new SlimSelect({
    select: '#cmbEmpleadoResGasto',
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar empleado...",
  });
}
*/
