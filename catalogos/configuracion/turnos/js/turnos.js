/*
function validarUnicoTurno() {
  var valor = document.getElementById("txtTurno").value;
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
*/
/*
function validarExisteRelacionTurno(valor) {
  $.ajax({
    url: "/turnos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionTurno",
      data: valor,
    },
    dataType: "json",

    success: function (data) {*/
      /* Validar si ya existe relacion con clientes*//*
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion con empleado", data);

        //$("#txtareau").prop('disabled', true);
        var eliminar = document.getElementById("idTurnoD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarTurno");
        modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");
      } else {
        $("#txtareau").prop("disabled", false);

        var eliminar = document.getElementById("idTurnoD");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditarTurno");
        modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");
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
*/