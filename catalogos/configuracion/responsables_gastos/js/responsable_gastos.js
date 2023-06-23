function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<img src='../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  //bonton de agregar invisible.
  var idemp = $("#emp_id").val();
  $("#tblResponsableGastos").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: "Excel",
      },
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    order: [[0, "desc"]],
    ajax: {
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_responsableTable", data: idemp },
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "NoResponsable",
      },
      {
        data: "Nombre",
      },
    ],
    columnDefs: [
      {
        orderable: false,
        targets: 0,
        visible: false,
      },
    ],
    responsive: true,
  });

  //cargarCMBEmpleados("", "cmbEmpleado"); //combo de los responsables que se van a agregar
  //cargarCMBEmpleados("", "txtResponsableU");
  //CargarSlimSelect();
});

function agregarResponsable() {
  if ($("#agregarResponsable")[0].checkValidity()) {
    var badResponsable =
      $("#invalid-responsable").css("display") === "block" ? false : true;
    if (badResponsable) {
      var fkempleado = $("#cmbEmpleado").val();
      const cont = 1;
      $("#cmbEmpleado").prop("required", true);
      if (cont == 1) {
        if (fkempleado == "0") {
          $("#cmbEmpleado")[0].reportValidity();
          $("#cmbEmpleado")[0].setCustomValidity("Completa este campo.");
          return;
        }
      }
      $.ajax({
        url: "functions/agregar_Responsable.php",
        type: "POST",
        data: {
          fkempleado: fkempleado,
        },
        success: function (data, status, xhr) {
          if (data.trim() == "exito") {
            $("#agregar_ResponsableGastos").modal("toggle");
            $("#agregarResponsable").trigger("reset");
            $("#tblResponsableGastos").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              //img: '<i class="fas fa-check-circle"></i>',
              img: "../../../img/timdesk/checkmark.svg",
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
              img: "../../../img/timdesk/warning_circle.svg",
              img: null,
              msg: "Ocurrió un error al agregar",
            });
          }
        },
      });
    }
  } else {
    if (!$("#cmbEmpleado").val()) {
      $("#invalid-responsable").css("display", "block");
      $("#cmbEmpleado").addClass("is-invalid");
    }
  }
}

$("#btnEditarResponsable").click(function () {
  var id = $("#idResponsableU").val();
  var fk = $("#idFkResponsableU").val();
  var fkresponsable = $("#txtResponsableU").val().trim();
  if (fk == fkresponsable) {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../img/timdesk/warning_circle.svg",
      msg: "Corresponde al mismo responsable!",
    });
    return;
  }
  if (parseInt(fkresponsable) >= 1) {
    var badNombreRes =
      $("#invalid-responsableEdit").css("display") === "block" ? false : true;
    if (badNombreRes) {
      $.ajax({
        url: "functions/editar_Responsable.php",
        type: "POST",
        data: {
          idResponsableU: id,
          txtResponsableU: fkresponsable,
        },
        success: function (data, status, xhr) {
          if (data.trim() == "1") {
            $("#modalEditar").modal("toggle");
            $("#editarResponsableU").trigger("reset");
            $("#tblResponsableGastos").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
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
              img: "../../../img/timdesk/warning_circle.svg",
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
  //var r = document.getElementById("cmbResponsableU");
  $("#cmbResponsableU").attr("disabled", true);

  document.getElementById("idResponsableU").value = id;
  document.getElementById("idResponsableD").value = id;
  var id = "id=" + id;
  $.ajax({
    type: "POST",
    url: "functions/getResponsableEditar.php",
    data: id,
    success: function (r) {
      var datos = JSON.parse(r);
      $("#txtNombreU").val(datos.html);
      //$("#txtResponsableU").html(datos.lista);
      $("#idFkResponsableU").val(datos.fkresponsable);

      var idVendedor = $("#idResponsableU").val();

      if (idVendedor != "") {
        validarExisteRelacion(idVendedor);
      } else {
      }
    },
  });
}

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
          url: "functions/eliminar_Responsable.php",
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
                img: "../../../img/chat/notificacion_error.svg",
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
                img: "../../../img/timdesk/warning_circle.svg",
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

function validarExisteResponsable() {
  //$('#btnCancelarActualizacion').attr("disabled", true);
  var valor = $("#cmbEmpleado").val();
  $.ajax({
    url: "php/funciones.php",
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
        $("#cmbEmpleado").addClass("is-invalid");
      } else {
        $("#invalid-responsable").text("El responsable debe tener un nombre.");
        $("#invalid-responsable").css("display", "none");
        $("#cmbEmpleado").removeClass("is-invalid");
      }
    },
  });
}

function validarExisteRelacion(valor) {
  //document.getElementById('txtResponsableU').disabled = true;
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionResponsable",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      /* Validar si ya existe relacion con clientes*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion responsable con gastos", data);
        document.getElementById("txtResponsableU").disabled = true;

        var eliminar = document.getElementById("idResponsableD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarResponsable");
        modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");
      } else {
        console.log("Sin relacion");
        document.getElementById("txtResponsableU").disabled = false;

        var eliminar = document.getElementById("idResponsableD");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditarResponsable");
        modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");
      }
    },
  });
}

function nuevoResponsable() {
  var id = $("#txtResponsableU").val();
  $.ajax({
    type: "POST",
    url: "functions/getNuevoNombreResponsable.php",
    data: { id: id },
    success: function (r) {
      var datos = JSON.parse(r);

      $("#idResponsableN").val(datos.html);

      var fkresponsable = $("#idResponsableN").val();
      if (fkresponsable != "") {
        validarExixteResponsableNuevo(fkresponsable);
      }
    },
  });
}

function validarExixteResponsableNuevo(valor) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeResponsable",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese usuario*/
      if (parseInt(data[0]["existe"]) == 1) {
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
  });
}

function CargarSlimSelect() {
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
}

/*function cargarCMBEmpleados(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_empleados" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta empleado: ", respuesta);

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

      html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar marcas</option>';

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}*/
