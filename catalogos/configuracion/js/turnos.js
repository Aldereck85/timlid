var DateTime = luxon.DateTime;

$(document).ready(function () {
  $("#txtComidaU").timepicki({
    show_meridian: false,
    start_time: ["00", "00"],
    min_hour_value: 0,
    max_hour_value: 2,
    step_size_minutes: 1,
    overflow_minutes: true,
    increase_direction: "up",
    disable_keyboard_mobile: true,
  });
  $("#txtComida").timepicki({
    show_meridian: false,
    start_time: ["00", "00"],
    min_hour_value: 0,
    max_hour_value: 2,
    step_size_minutes: 1,
    overflow_minutes: true,
    increase_direction: "up",
    disable_keyboard_mobile: true,
  });

  $("#txtEntrada").mdtimepicker({
    timeFormat: "hh:mm:ss",
    format: "hh:mm:ss",
  }); //Initializes the time picker
  $("#txtSalida").mdtimepicker({
    timeFormat: "hh:mm:ss",
    format: "hh:mm:ss",
  }); //Initializes the time picker
  $("#txtEntradaU").mdtimepicker({
    timeFormat: "hh:mm:ss",
    format: "hh:mm:ss",
  }); //Initializes the time picker
  $("#txtSalidaU").mdtimepicker({
    timeFormat: "hh:mm:ss",
    format: "hh:mm:ss",
  }); //Initializes the time picker
  
  new SlimSelect({
    select: '#tipo_jornada',
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar tipo de jornada...",
  });

  new SlimSelect({
    select: '#tipo_jornada_edit',
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar tipo de jornada...",
  });
});

var val_selected =
  []; /*Funcion para los checkbox de seleccion de dias de la semana*/
var val_selected2 = [];

function checkbox_Selected() {
  val_selected = [];
  $('input[name="weekday"]:checkbox:checked').each(function (i) {
    $("#invalid-dias").css("display", "none");
    val_selected[i] = $(this).val();
  });
  console.log(val_selected);
}

function checkbox_UnSelected() {
  val_selected2 = [];
  $("input[name='weekdayU']:checkbox:checked").each(function (i) {
    val_selected2[i] = $(this).val();
  });
  console.log(val_selected2);
}

function obtenerIdTurnoEditar(id) {
  /**Funcion para obtener datos para editar */
  val_selected = [];
  document.getElementById("txtUpdatePKTurnos_46").value = id;
  var id = "id=" + id;
  $.ajax({
    type: "POST",
    url: "turnos/functions/getTurnos.php",
    data: id,
    success: function (r) {
      var datos = JSON.parse(r);
      $("#txtUpdateTurnos_46").val(datos.Turno);
      $("#txtEntradaU").val(datos.Entrada);
      $("#tipo_jornada_edit").html(datos.tipo_jornada_id);
      $("#txtSalidaU").val(datos.Salida);
      $("#cmbDiasU").val(datos.html31);
      $("#txtComidaU").val(datos.TiempoComida);
      $("#weekday-monU").prop("checked", datos.Dias_de_trabajo.lunes);
      $("#weekday-tueU").prop("checked", datos.Dias_de_trabajo.martes);
      $("#weekday-wedU").prop("checked", datos.Dias_de_trabajo.miercoles);
      $("#weekday-thuU").prop("checked", datos.Dias_de_trabajo.jueves);
      $("#weekday-friU").prop("checked", datos.Dias_de_trabajo.viernes);
      $("#weekday-satU").prop("checked", datos.Dias_de_trabajo.sabado);
      $("#weekday-sunU").prop("checked", datos.Dias_de_trabajo.domingo);
      var idSucursal = $("#idTurnoU").val();
      if (idSucursal != "") {
        validarExisteRelacionTurno(idSucursal);
      }
    },
  });
}

$("#btnAgregarTurno").click(function () {
  if (!$("#txtTurno").val()) {
    $("#invalid-turno").css("display", "block");
    $("#txtTurno").addClass("is-invalid");
  }
  if (!$("#txtEntrada").val()) {
    $("#invalid-entrada").css("display", "block");
    $("#txtEntrada").addClass("is-invalid");
  }
  if (!$("#txtSalida").val()) {
    $("#invalid-salida").css("display", "block");
    $("#txtSalida").addClass("is-invalid");
  }
  if (!$("#txtComida").val()) {
    $("#invalid-comida").css("display", "block");
    $("#txtComida").addClass("is-invalid");
  }
  if (
    !$("#weekday-mon").prop("checked") &&
    !$("#weekday-tue").prop("checked") &&
    !$("#weekday-wed").prop("checked") &&
    !$("#weekday-thu").prop("checked") &&
    !$("#weekday-fri").prop("checked") &&
    !$("#weekday-sat").prop("checked") &&
    !$("#weekday-sun").prop("checked")
  ) {
    $("#invalid-dias").css("display", "block");
  }

  var badNombreTur =
    $("#invalid-turno").css("display") === "block" ? false : true;
  var badEntrada =
    $("#invalid-entrada").css("display") === "block" ? false : true;
  var badSalida =
    $("#invalid-salida").css("display") === "block" ? false : true;
  var badComida =
    $("#invalid-comida").css("display") === "block" ? false : true;
  var badDias = $("#invalid-dias").css("display") === "block" ? false : true;

  if (badNombreTur && badEntrada && badSalida && badComida && badDias) {
    var lunes = document.getElementById("weekday-mon").checked;
    var martes = document.getElementById("weekday-tue").checked;
    var miercoles = document.getElementById("weekday-wed").checked;
    var jueves = document.getElementById("weekday-thu").checked;
    var viernes = document.getElementById("weekday-fri").checked;
    var sabado = document.getElementById("weekday-sat").checked;
    var domingo = document.getElementById("weekday-sun").checked;
    var turno = $("#txtTurno").val().trim();
    var entrada = $("#txtEntrada").val();
    var salida = $("#txtSalida").val();
    var comida = $("#txtComida").val();
    var dias = val_selected;
    var diasTrabajados = JSON.stringify({
      lunes,
      martes,
      miercoles,
      jueves,
      viernes,
      sabado,
      domingo,
    });
    var empresa = $("#emp_id").val();
    var tipo_jornada = $("#tipo_jornada").val();
    var entradaNew = DateTime.fromISO(entrada);
    var salidaNew = DateTime.fromISO(salida);
    if(entradaNew.ts > salidaNew.ts) {
      salidaNew = DateTime.fromISO(salida).plus({ days: 1 })
      var horasTrabajadas = salidaNew.diff(entradaNew, ['hours', 'minutes']).shiftTo('hours').toObject().hours
    } else {
      var horasTrabajadas = salidaNew.diff(entradaNew, ['hours', 'minutes']).shiftTo('hours').toObject().hours;
    }
    $.ajax({
      url: "turnos/functions/agregar_Turno.php",
      type: "POST",
      data: {
        txtTurno: turno,
        txtEntrada: entrada,
        txtSalida: salida,
        cmbDias: diasTrabajados,
        txtComida: comida,
        empresa: empresa,
        hrsTrabajadas: horasTrabajadas,
        usuario: $("#txtUsuario").val(),
        tipo_jornada: tipo_jornada
      },
      success: function (data, status, xhr) {
        //console.log(data);
        if (data.trim() == "exito") {
          $("#agregar_Turnos_46").modal("toggle");
          $("#agregarTurno").trigger("reset");
          $("#tblTurnos").DataTable().ajax.reload();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: false,
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
            icon: false,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Ocurrió un error al agregar",
          });
        }
      },
    });
  }
});

$("#agregar_Turno").on("hidden.bs.modal", function (e) {
  $("#invalid-turno").text("El nombre del turno es requerido.");
  $("#invalid-turno").css("display", "none");
  $("#txtTurno").removeClass("is-invalid");
  $("#txtTurno").val("");

  $("#invalid-entrada").text("La hora de entrada es requerida.");
  $("#invalid-entrada").css("display", "none");
  $("#txtEntrada").removeClass("is-invalid");
  $("#txtEntrada").val("");

  $("#invalid-salida").text("La hora de salida es requerida.");
  $("#invalid-salida").css("display", "none");
  $("#txtSalida").removeClass("is-invalid");
  $("#txtSalida").val("");

  $("#invalid-comida").text("La hora de comida es requerida.");
  $("#invalid-comida").css("display", "none");
  $("#txtComida").removeClass("is-invalid");
  $("#txtComida").val("");
});

/* Reiniciar el modal al cerrarlo */
$("#editar_Turno").on("hidden.bs.modal", function (e) {
  $("#invalid-turnoEdit").text("El nombre del turno es requerido.");
  $("#invalid-turnoEdit").css("display", "none");
  $("#txtTurnoU").removeClass("is-invalid");
  $("#txtTurnoU").val("");

  $("#invalid-entradaEdit").text("La hora de entrada es requerida.");
  $("#invalid-entradaEdit").css("display", "none");
  $("#txtEntradaU").removeClass("is-invalid");
  $("#txtEntradaU").val("");

  $("#invalid-salidaEdit").text("La hora de salida es requerida.");
  $("#invalid-salidaEdit").css("display", "none");
  $("#txtSalidaU").removeClass("is-invalid");
  $("#txtSalidaU").val("");

  $("#invalid-comidaEdit").text("La hora de comida es requerida.");
  $("#invalid-comidaEdit").css("display", "none");
  $("#txtComidaU").removeClass("is-invalid");
  $("#txtComidaU").val("");
});

$(document).on("click", "#btnEditar_Turnos_46", function () {
  if (!$("#txtUpdateTurnos_46").val()) {
    $("#invalid-turnoEdit").css("display", "block");
    $("#txtUpdateTurnos_46").addClass("is-invalid");
  }
  if (!$("#txtEntradaU").val()) {
    $("#invalid-entradaEdit").css("display", "block");
    $("#txtEntradaU").addClass("is-invalid");
  }
  if (!$("#txtSalidaU").val()) {
    $("#invalid-salidaEdit").css("display", "block");
    $("#txtSalidaU").addClass("is-invalid");
  }
  if (!$("#txtComidaU").val()) {
    $("#invalid-comidaEdit").css("display", "block");
    $("#txtComidaU").addClass("is-invalid");
  }
  if (
    !$("#weekday-monU").prop("checked") &&
    !$("#weekday-mon").prop("checked") &&
    !$("#weekday-mon").prop("checked") &&
    !$("#weekday-mon").prop("checked") &&
    !$("#weekday-mon").prop("checked") &&
    !$("#weekday-mon").prop("checked") &&
    !$("#weekday-mon").prop("checked")
  ) {
    $("#invalid-dias").css("display", "block");
  }

  var badNombreTur =
    $("#invalid-turnoEdit").css("display") === "block" ? false : true;
  var badEntrada =
    $("#invalid-entradaEdit").css("display") === "block" ? false : true;
  var badSalida =
    $("#invalid-salidaEdit").css("display") === "block" ? false : true;
  var badComida =
    $("#invalid-comidaEdit").css("display") === "block" ? false : true;

  if (badNombreTur && badEntrada && badSalida && badComida) {
    var lunes = document.getElementById("weekday-monU").checked;
    var martes = document.getElementById("weekday-tueU").checked;
    var miercoles = document.getElementById("weekday-wedU").checked;
    var jueves = document.getElementById("weekday-thuU").checked;
    var viernes = document.getElementById("weekday-friU").checked;
    var sabado = document.getElementById("weekday-satU").checked;
    var domingo = document.getElementById("weekday-sunU").checked;
    var id = $("#txtUpdatePKTurnos_46").val();
    var turno = $("#txtUpdateTurnos_46").val();
    var entrada = $("#txtEntradaU").val();
    var salida = $("#txtSalidaU").val();
    var dias = val_selected2;
    var comida = $("#txtComidaU").val();
    var tipo_jornada = $("#tipo_jornada_edit").val();
    var diasTrabajados = JSON.stringify({
      lunes,
      martes,
      miercoles,
      jueves,
      viernes,
      sabado,
      domingo,
    });
    var entradaNew = DateTime.fromISO(entrada);
    var salidaNew = DateTime.fromISO(salida);
    if(entradaNew.ts > salidaNew.ts) {
      salidaNew = DateTime.fromISO(salida).plus({ days: 1 })
      var horasTrabajadas = salidaNew.diff(entradaNew, ['hours', 'minutes']).shiftTo('hours').toObject().hours
    } else {
      var horasTrabajadas = salidaNew.diff(entradaNew, ['hours', 'minutes']).shiftTo('hours').toObject().hours;
    }
    $.ajax({
      url: "turnos/functions/editar_Turno.php",
      type: "POST",
      data: {
        idTurnoU: id,
        txtTurnoU: turno,
        txtEntradaU: entrada,
        txtSalidaU: salida,
        cmbDiasU: diasTrabajados,
        txtComidaU: comida,
        usuario: $("#txtUsuario").val(),
        hrsTrabajadas: horasTrabajadas,
        tipo_jornada: tipo_jornada
      },
      success: function (data, status, xhr) {
        console.log(data);
        if (data.trim() == "1") {
          $("#editar_Turnos_46").modal("toggle");
          $("#editarTurno").trigger("reset");
          $("#tblTurnos").DataTable().ajax.reload();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: false,
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
            icon: false,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Ocurrió un error al agregar",
          });
        }
      },
    });
  }
});

$(document).on("click", "#btn_aceptar_eliminar_Turnos_46", function () {
  $.ajax({
    url: "turnos/functions/eliminar_Turno.php",
    type: "POST",
    data: {
      idTurnoD: $("#txtUpdatePKTurnos_46").val(),
      usuario: $("#txtUsuario").val(),
    },
    success: function (data, status, xhr) {
      if (data == "exito") {
        $("#eliminar_Turnos_46").modal("toggle");
        $("#tblTurnos").DataTable().ajax.reload();
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

/* $(document).on("change", "#txtSalida", validarHoras);
$(document).on("change", "#txtEntrada", validarHoras); */

function validarHoras() {
  if ($("#txtSalida").val() && $("#txtEntrada").val()) {
    if ($("#txtSalida").val() <= $("#txtEntrada").val()) {
      console.log("La salida no puede ser menor");
      $("#invalid-salida").text(
        "La hora de salida no puede ser menor que la de entrada."
      );
      $("#invalid-salida").css("display", "block");
      $("#txtSalida").addClass("is-invalid");
    } else {
      $("#invalid-salida").text("La hora de salida es requerida.");
      $("#invalid-salida").css("display", "none");
      $("#txtSalida").removeClass("is-invalid");
    }
  }
}

/* $(document).on("change", "#txtSalidaU", validarHorasU);
$(document).on("change", "#txtEntradaU", validarHorasU); */

function validarHorasU() {
  if ($("#txtSalidaU").val() && $("#txtEntradaU").val()) {
    if ($("#txtSalidaU").val() <= $("#txtEntradaU").val()) {
      console.log("La salida no puede ser menor");
      $("#invalid-salidaEdit").text(
        "La hora de salida no puede ser menor que la de entrada."
      );
      $("#invalid-salidaEdit").css("display", "block");
      $("#txtSalidaU").addClass("is-invalid");
    } else {
      $("#invalid-salidaEdit").text("La hora de salida es requerida.");
      $("#invalid-salidaEdit").css("display", "none");
      $("#txtSalidaU").removeClass("is-invalid");
    }
  }
}

function eliminarTurno(id) {
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
      title: "¿Desea eliminar el registro de este turno?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter2">Eliminar turno</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "turnos/functions/eliminar_Turno.php",
          type: "POST",
          data: {
            idTurnoD: id,
            usuario: $("#txtUsuario").val(),
          },
          success: function (data, status, xhr) {
            if (data == "exito") {
              $("#editar_Turnos_46").modal("toggle");
              $("#tblTurnos").DataTable().ajax.reload();
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

/*
function validarUnicoTurno(item) {
  var valor = item.value;
  console.log("Valor turno:  " + valor);
  $.ajax({
    url: "turnos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_turno", data: valor },
    dataType: "json",
    success: function (data) {
      //console.log("respuesta turno validado: ", data);
      
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-turno").text("El turno ya esta registrado.");
        $("#invalid-turno").css("display", "block");
        $("#txtTurno").addClass("is-invalid");
      } else {
        $("#invalid-turno").text("El nombre del turno es requerido.");
        $("#invalid-turno").css("display", "none");
        $("#txtTurno").removeClass("is-invalid");
      }
    },
  });
}
*/

function validarUnicoTurno(item) {
  var valor = item.value;

  console.log("Valor puesto:  " + valor);
  $.ajax({
    url: "turnos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_turno", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta sucursal validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data) == 1) {
        item.nextElementSibling.innerText =
          "El turno ya esta registrado en el sistema.";
        item.nextElementSibling.style.display = "block";
        item.classList.add("is-invalid");
      } else {
        item.nextElementSibling.innerText = "El nombre del turno es requerido.";
        item.nextElementSibling.style.display = "none";
        item.classList.remove("is-invalid");
      }
    },
  });
}

function validarUnicoTurnoEdit() {
  var valor = document.getElementById("txtTurnoU").value;
  console.log("Valor turno:  " + valor);
  $.ajax({
    url: "turnos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_turno", data: valor },
    dataType: "json",
    success: function (data) {
      //console.log("respuesta turno validado: ", data);

      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-turnoEdit").text("El turno ya esta registrado.");
        $("#invalid-turnoEdit").css("display", "block");
        $("#txtTurnoU").addClass("is-invalid");
      } else {
        $("#invalid-turnoEdit").text("El nombre del turno es requerido.");
        $("#invalid-turnoEdit").css("display", "none");
        $("#txtTurnoU").removeClass("is-invalid");
      }
    },
  });
}

function validarExisteRelacionTurno(valor) {
  $.ajax({
    url: "turnos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionTurno",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      /* Validar si ya existe relacion con clientes*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion con empleado", data);

        //$("#txtareau").prop('disabled', true);
        var eliminar = document.getElementById("idTurnoD");
        eliminar.style.display = "none";
        /*var modificar = document.getElementById("btnEditarTurno");
        modificar.style.display = "none";*/

        /*var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");*/
      } else {
        $("#txtareau").prop("disabled", false);

        //var eliminar = document.getElementById("idTurnoD");
        //eliminar.style.display = "block";
        //var modificar = document.getElementById("btnEditarTurno");
        //modificar.style.display = "block";
        /*var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");*/
      }
    },
  });
}

function validEmptyInput(item, invalid = null) {
  console.log(item);
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  if (invalid) {
    invalidDiv = document.getElementById(invalid);
  } else {
    for (let i = 0; i < parent.children.length; i++) {
      if (parent.children[i].classList.contains("invalid-feedback")) {
        invalidDiv = parent.children[i];
        break;
      }
    }
  }
  if (!val) {
    item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
}
