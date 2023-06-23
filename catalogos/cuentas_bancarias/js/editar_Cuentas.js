function editaCuenta() {
  var id = $("#idCuentaU").val();
  var nombreCuenta = $("#txtNombreCuentaU").val();
  var tipoCuenta = $("#tipoIdCuentaU").val();

  //CHEQUES
  var noCuenta = $("#txtNoCuentaU").val();
  var clabe = $("#txtClabeUp").val();
  //CREDTO
  var noCredito = $("#txtNoCreditoU").val();
  var referencia = $("#txtReferenciaU").val();
  //OTROS
  var idCuenta = $("#txtIdentificadorU").val();
  var descripcionO = $("#txtDescripcionU").val();
  //CAJA CHICA
  var descripcion = $("#txtDescripcionUCaja").val();
  console.log(idTipo);
  if (idTipo === 1) {
    if (!nombreCuenta) {
      $("#invalid-nombreCntU").css("display", "block");
      $("#txtNombreCuentaU").addClass("is-invalid");
    }
    if (!noCuenta) {
      $("#invalid-noCuentaU").css("display", "block");
      $("#txtNoCuentaU").addClass("is-invalid");
    }
    if (!clabe) {
      $("#invalid-clabeCuentaU").css("display", "block");
      $("#txtClabeUp").addClass("is-invalid");
    }
    var badNombre =
      $("#invalid-nombreCntU").css("display") === "block" ? false : true;
    var badNoCuenta =
      $("#invalid-noCuentaU").css("display") === "block" ? false : true;
    var badClabe =
      $("#invalid-clabeCuentaU").css("display") === "block" ? false : true;

    if (badNombre && badNoCuenta && badClabe) {
      agrega(
        id,
        nombreCuenta,
        noCuenta,
        clabe,
        noCredito,
        referencia,
        idCuenta,
        descripcionO,
        descripcion,
        idTipo
      );
    }
  } else if (idTipo === 2) {
    if (!nombreCuenta) {
      $("#invalid-nombreCntU").css("display", "block");
      $("#txtNombreCuentaU").addClass("is-invalid");
    }
    if (!noCredito) {
      $("#invalid-noCreditoU").css("display", "block");
      $("#txtNoCreditoU").addClass("is-invalid");
    }
    if (!referencia) {
      $("#invalid-referenciaU").css("display", "block");
      $("#txtReferenciaU").addClass("is-invalid");
    }
    var badNombre =
      $("#invalid-nombreCntU").css("display") === "block" ? false : true;
    var badNoCredito =
      $("#invalid-noCreditoU").css("display") === "block" ? false : true;
    var badRef =
      $("#invalid-referenciaU").css("display") === "block" ? false : true;

    if (badNombre && badNoCredito && badRef) {
      agrega(
        id,
        nombreCuenta,
        noCuenta,
        clabe,
        noCredito,
        referencia,
        idCuenta,
        descripcionO,
        descripcion,
        idTipo
      );
    }
  } else if (idTipo === 3) {
    if (!nombreCuenta) {
      $("#invalid-nombreCntU").css("display", "block");
      $("#txtNombreCuentaU").addClass("is-invalid");
    }
    if (!idCuenta) {
      $("#invalid-identfCntU").css("display", "block");
      $("#txtIdentificadorU").addClass("is-invalid");
    }

    var badNombre =
      $("#invalid-nombreCntU").css("display") === "block" ? false : true;
    var badIdenCuenta =
      $("#invalid-identfCntU").css("display") === "block" ? false : true;
    var badDescripO =
      $("#invalid-descrOtU").css("display") === "block" ? false : true;
    if (badNombre && badIdenCuenta && badDescripO) {
      agrega(
        id,
        nombreCuenta,
        noCuenta,
        clabe,
        noCredito,
        referencia,
        idCuenta,
        descripcionO,
        descripcion,
        idTipo
      );
    }
  } else if (idTipo === 4) {
    if (!nombreCuenta) {
      $("#invalid-nombreCntU").css("display", "block");
      $("#txtNombreCuentaU").addClass("is-invalid");
    }
    var badNombre =
      $("#invalid-nombreCntU").css("display") === "block" ? false : true;
    var badIdenCuenta =
      $("#invalid-identfCntU").css("display") === "block" ? false : true;
    var badDescripO =
      $("#invalid-descrOtU").css("display") === "block" ? false : true;
    if (badNombre && badIdenCuenta && badDescripO) {
      agrega(
        id,
        nombreCuenta,
        noCuenta,
        clabe,
        noCredito,
        referencia,
        idCuenta,
        descripcionO,
        descripcion,
        idTipo
      );
    }
  }
}

function agrega(
  id,
  nombreCuenta,
  noCuenta,
  clabe,
  noCredito,
  referencia,
  idCuenta,
  descripcionO,
  descripcion,
  tipoCuenta
) {
  $.ajax({
    url: "functions/editar_Cuenta.php",
    type: "POST",
    data: {
      idCuentaU: id,
      txtNombreCuentaU: nombreCuenta,
      txtNoCuentaU: noCuenta,
      txtClabeUp: clabe,
      txtNoCreditoU: noCredito,
      txtReferenciaU: referencia,
      txtIdentificadorU: idCuenta,
      txtDescripcionU: descripcionO,
      txtDescripcionUCaja: descripcion,
      txtTipoCuentaU: tipoCuenta,
    },
    success: function (data, status, xhr) {
      if (data == "exito") {
        $("#editarCuenta").trigger("reset");
        $("#editar_Cuenta").modal("toggle");
        switch(idTipo){
          case 1:
            tablaInicialCheques(id);
          break;
          case 2:
            tablaInicialCredito(id);
          break;
          case 3:
            tablaInicialOtras(id);
          break;
          default:
            tablaInicialCaja(id);
        }
        //window.location.reload();
        $("#tblCuentasBancarias").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: false,
          img: "../../img/timdesk/checkmark.svg",
          msg: "Cuenta modificada!",
        });
      } else {
        Lobibox.notify("error", {
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

function lobiAlert(mensaje, status) {
  var img = status === "error" ? "notificacion_error.svg" : "checkmark.svg";
  Lobibox.notify(status, {
    size: "mini",
    rounded: true,
    delay: 4000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: true,
    img: "../../img/timdesk/checkmark.svg",
    msg: mensaje,
  });
}
function lobiWarning(mensaje) {
  var img = "notificacion_error.svg";
  Lobibox.notify("error", {
    size: "mini",
    rounded: true,
    delay: 4000,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../img/timdesk/" + img,
    msg: mensaje,
  });
}
