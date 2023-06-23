function agregar_TransferenciaC() {
  if ($("#inyeccionCapital")[0].checkValidity()) {
    console.log("Campos Validados");
    var badMonto =
      $("#invalid-montoCjChica").css("display") === "block" ? false : true;
    var badFecha =
      $("#invalid-fechaCjChica").css("display") === "block" ? false : true;
    var badObservacion =
      $("#invalid-obsCjChica").css("display") === "block" ? false : true;
    var badArchivo =
      $("#invalid-archivoCjChica").css("display") === "block" ? false : true;

    if (badMonto && badFecha && badObservacion && badArchivo) {
      var idCuentaActual = $("#idCuentaIny").val();
      var saldoCuantaActual = $("#saldoIny").val();
      var monto = $("#montoInyeccionCapital").val();
      var fecha = $("#fechaInyeccionCapital").val();
      var observaciones = $("#areaObservacion").val();

      var hayArchivo = 0;
      var file = $("#inputFileInyeccion").val();
      var miArchivo = $("#inputFileInyeccion").prop("files")[0];

      var fd = new FormData();

      if (!file == "") {
        var files = [];
        files = $("#inputFileInyeccion")[0].files[0];
        var nombrearchivo = files.name;
        var extension = nombrearchivo
          .substr(nombrearchivo.lastIndexOf(".") + 1)
          .toLowerCase();
        hayArchivo = 1;
        fd.append("inputFileInyeccion", miArchivo);
        fd.append("idCuentaActual", idCuentaActual);
        fd.append("saldoCuantaActual", saldoCuantaActual);
        fd.append("montoInyeccionCapital", monto);
        fd.append("fechaInyeccionCapital", fecha);
        fd.append("observaciones", observaciones);
        fd.append("hayArchivo", hayArchivo);
      } else {
        hayArchivo = 0;
        fd.append("idCuentaActual", idCuentaActual);
        fd.append("saldoCuantaActual", saldoCuantaActual);
        fd.append("montoInyeccionCapital", monto);
        fd.append("fechaInyeccionCapital", fecha);
        fd.append("observaciones", observaciones);
        fd.append("hayArchivo", hayArchivo);
      }
      $.ajax({
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: fd,
        url: "functions/agregar_InyeccionCapital.php",
        success: function (data, status, xhr) {
          console.log(data);
          if (data.trim() == "exito") {
            $("#inyeccion_Capital").modal("toggle");
            closeModalAgregarDinero();
            $("#tblDetalles").DataTable().ajax.reload();
            tablaInicialCaja(idCuentaActual);
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Inyección de capital exitosa!",
            });
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              img: null,
              msg: "No se pudo agregar!",
            });
          }
        },
      });
    }
  } else {
    if (!$("#montoInyeccionCapital").val()) {
      $("#invalid-montoCjChica").css("display", "block");
      $("#montoInyeccionCapital").addClass("is-invalid");
    }
    if (!$("#fechaInyeccionCapital").val()) {
      $("#invalid-fechaCjChica").css("display", "block");
      $("#fechaInyeccionCapital").addClass("is-invalid");
    }
    if (!$("#areaObservacion").val()) {
      $("#invalid-obsCjChica").css("display", "block");
      $("#areaObservacion").addClass("is-invalid");
    }
    if (!$("#checkCajaInyeccion").prop("checked")) {
      if (!$("#inputFileInyeccion").val()) {
        $("#invalid-archivoCjChica").css("display", "block");
        $("#inputFileInyeccion").addClass("is-invalid");
      }
    }
  }
}

function get_Cuenta_Origen() {
  var idCuentaOrigen = $("#cmbCuentasOrigen").val();
  var id = idCuentaOrigen; // se crea esta variable porque se manda como null al metodo
  $.ajax({
    type: "POST",
    url: "functions/get_OrigenInyeccion.php",
    data: { idCuentaOrigen: id },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#idCuentaOr").val(datos.idCuentaOrigen);
      $("#idMonedaO").val(datos.monOrgigen);
    },
  });
}

function get_Cuenta_Origen() {
  var idCuentaOrigen = $("#cmbCuentasOrigen").val();
  var id = idCuentaOrigen; // se crea esta variable porque se manda como null al metodo
  $.ajax({
    type: "POST",
    url: "functions/get_OrigenInyeccion.php",
    data: { idCuentaOrigen: id },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#idCuentaOr").val(datos.idCuentaOrigen);
      $("#idMonedaO").val(datos.monOrgigen);
    },
  });
}
function tablaInicialCaja(idCuenta) {
  $.ajax({
    type: "POST",
    url: "functions/tabla_Caja.php",
    data: { idDetalle: idCuenta },
    success: function (r) {
      var datos = JSON.parse(r);

      $("#saldoG").val(datos.saldoG);
      $("#tipoCuentaG").val(datos.tipoCuenta);
      $("#nomCuentaG").val(datos.nomCuenta);
    },
  });
}

function validEmptyInput(item, invalid = null) {
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

function closeModalAgregarDinero() {
  $("#montoInyeccionCapital").val("");
  $("#invalid-montoCjChica").css("display", "none");
  $("#montoInyeccionCapital").removeClass("is-invalid");

  $("#fechaInyeccionCapital").val("");
  $("#invalid-fechaCjChica").css("display", "none");
  $("#fechaInyeccionCapital").removeClass("is-invalid");

  $("#areaObservacion").val("");
  $("#invalid-obsCjChica").css("display", "none");
  $("#areaObservacion").removeClass("is-invalid");

  $("#checkCajaInyeccion").prop("disabled", false);
  $("#checkCajaInyeccion").prop("checked", true);
  $("#inputFileInyeccion").css("display", "none");
  $("#inputFileInyeccion").val("");
  $("#invalid-archivoCjChica").css("display", "none");
  $("#inputFileInyeccion").removeClass("is-invalid");
}
