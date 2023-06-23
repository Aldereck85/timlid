function lobiAlert(mensaje, status) {
  var img = status === "error" ? "notificacion_error.svg" : "checkmark.svg";
  Lobibox.notify(status, {
    size: "mini",
    rounded: true,
    delay: 4000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: true,
    img: "../../img/timdesk/" + img,
    msg: mensaje,
  });
}
function lobiWarning(mensaje) {
  var img = "notificacion_error.svg" ;
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

function agregarCheque() {
  var msj = "¡Registro agregado!";
  var error = "Ocurrió un error al agregar";

  var nombreCuenta = $("#txtNombreCuenta").val();
  var tipoCuenta = $("#cmbTipoCuenta").val();
  var banco = $("#cmbBanco").val();
  var noCuenta = $("#txtNoCuenta").val();
  var clabe = $("#txtCLABE").val();
  var saldo = $("#txtSaldo").val();
  var fkempresa = $("#emp_id").val();
  var estado = 1;

  if (!tipoCuenta || tipoCuenta < 1) {
    $("#invalid-tipoCnt").css("display", "block");
    $("#cmbTipoCuenta").addClass("is-invalid");
  }
  if (!nombreCuenta) {
    $("#invalid-nombreCnt").css("display", "block");
    $("#txtNombreCuenta").addClass("is-invalid");
  }
  if (!banco) {
    $("#invalid-BancoChe").css("display", "block");
    $("#cmbBanco").addClass("is-invalid");
  }
  if (!noCuenta) {
    $("#invalid-noCuenta").css("display", "block");
    $("#txtNoCuenta").addClass("is-invalid");
  }
  if (!clabe) {
    $("#invalid-claveCuenta").css("display", "block");
    $("#txtCLABE").addClass("is-invalid");
  }
  if (!saldo) {
    $("#invalid-saldoChe").css("display", "block");
    $("#txtSaldo").addClass("is-invalid");
  }
  var badNombre =
    $("#invalid-nombreCnt").css("display") === "block" ? false : true;
  var badBanco =
    $("#invalid-BancoChe").css("display") === "block" ? false : true;
  var badNoCuenta =
    $("#invalid-invalid-noCuenta").css("display") === "block" ? false : true;
  var badClabe =
    $("#invalid-claveCuenta").css("display") === "block" ? false : true;
  var badSaldo =
    $("#invalid-saldoChe").css("display") === "block" ? false : true;

  if (badNombre && badBanco && badNoCuenta && badClabe && badSaldo) {
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "agregar_cuenta_cheques",
        data: tipoCuenta,
        data2: nombreCuenta,
        data3: fkempresa,
        data4: estado,
        data5: banco,
        data6: noCuenta,
        data7: clabe,
        data8: saldo,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar cuenta cheques:", respuesta[0].status);
        /*if( parse(respuesta[0]['status']) == true ){
            alert("BIEN");
          }*/
        if (respuesta[0].status) {
          $("#agregar_Cuenta").modal("toggle");
          $("#agregarCuenta").trigger("reset");
          $("#tblCuentasBancarias").DataTable().ajax.reload();
          lobiAlert("Registro agregado", "success");
        } else {
          $("#agregar_Cuenta").modal("toggle");
          $("#agregarCuenta").trigger("reset");
          lobiWarning(error);
        }
        cmtipoSelect.set("0");
        console.log($("#cmbTipoCuenta").val());
      },
    });
  }
}

function agregarCredito() {
  var nombreCuenta = $("#txtNombreCuenta").val();
  var tipoCuenta = $("#cmbTipoCuenta").val();
  var banco = $("#cmbBancoCredito").val();

  var credito = $("#txtNoCredito").val();
  var referencia = $("#txtReferencia").val();
  var limiteCredito = $("#txtLimiteCredito").val();
  var fkempresa = $("#emp_id").val();
  var estado = 1;

  if (!tipoCuenta || tipoCuenta < 1) {
    $("#invalid-tipoCnt").css("display", "block");
    $("#cmbTipoCuenta").addClass("is-invalid");
  }
  if (!nombreCuenta) {
    $("#invalid-nombreCnt").css("display", "block");
    $("#txtNombreCuenta").addClass("is-invalid");
  }
  if (!banco) {
    $("#invalid-bancoCred").css("display", "block");
    $("#cmbBancoCredito").addClass("is-invalid");
  }
  if (!credito) {
    $("#invalid-noCredito").css("display", "block");
    $("#txtNoCredito").addClass("is-invalid");
  }
  if (!referencia) {
    $("#invalid-refCre").css("display", "block");
    $("#txtReferencia").addClass("is-invalid");
  }
  if (!limiteCredito) {
    $("#invalid-limCred").css("display", "block");
    $("#txtLimiteCredito").addClass("is-invalid");
  }

  var badNombre =
    $("#invalid-nombreCnt").css("display") === "block" ? false : true;
  var badBanco =
    $("#invalid-bancoCred").css("display") === "block" ? false : true;
  var badCredito =
    $("#invalid-noCredito").css("display") === "block" ? false : true;
  var badReferencia =
    $("#invalid-refCre").css("display") === "block" ? false : true;
  var badLimitCre =
    $("#invalid-limCred").css("display") === "block" ? false : true;

  if (badNombre && badBanco && badCredito && badReferencia && badLimitCre) {
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "agregar_cuenta_credito",
        data: tipoCuenta,
        data2: nombreCuenta,
        data3: fkempresa,
        data4: estado,
        data5: banco,
        data6: credito,
        data7: referencia,
        data8: limiteCredito,
      },
      dataType: "json",
      success: function (respuesta) {
        //console.log("respuesta agregar cuenta credito:",respuesta[0].status);

        if (respuesta[0].status) {
          $("#agregar_Cuenta").modal("toggle");
          $("#agregarCuenta").trigger("reset");
          $("#tblCuentasBancarias").DataTable().ajax.reload();
          lobiAlert("Registro agregado", "success");
        } else {
          $("#agregar_Cuenta").modal("toggle");
          $("#agregarCuenta").trigger("reset");
          lobiAlert("Algo salio mal", "error");
        }
        cmtipoSelect.set("0");
      },
    });
  }
}

function agregaOtras() {
  var msj = "¡Registro agregado!";
  var error = "Ocurrió un error al agregar";

  var nombreCuenta = $("#txtNombreCuenta").val();
  var tipoCuenta = $("#cmbTipoCuenta").val();

  var idCuenta = $("#txtIdCuenta").val();
  var descripcion = $("#txtDescripcion").val();
  var saldoInicial = $("#txtSaldoInicial").val();

  var fkempresa = $("#emp_id").val();
  var estado = 1;

  $("#txtIdCuenta").prop("required", true);
  $("#txtSaldoInicial").prop("required", true);

  if (!tipoCuenta || tipoCuenta < 1) {
    $("#invalid-tipoCnt").css("display", "block");
    $("#cmbTipoCuenta").addClass("is-invalid");
  }
  if (!nombreCuenta) {
    $("#invalid-nombreCnt").css("display", "block");
    $("#txtNombreCuenta").addClass("is-invalid");
  }
  if (!idCuenta) {
    $("#invalid-identOtros").css("display", "block");
    $("#txtIdCuenta").addClass("is-invalid");
  }
  if (!saldoInicial) {
    $("#invalid-saldoInOtros").css("display", "block");
    $("#txtSaldoInicial").addClass("is-invalid");
  }

  var badNombre =
    $("#invalid-nombreCnt").css("display") === "block" ? false : true;
  var badCuenta =
    $("#invalid-identOtros").css("display") === "block" ? false : true;
  var badSaldo =
    $("#invalid-saldoInOtros").css("display") === "block" ? false : true;

  if (badNombre && badCuenta && badSaldo) {
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "agregar_cuenta_otras",
        data: tipoCuenta,
        data2: nombreCuenta,
        data3: fkempresa,
        data4: estado,
        data5: idCuenta,
        data6: descripcion,
        data7: saldoInicial,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar cuenta credito:", respuesta[0].status);

        if (respuesta[0].status) {
          $("#agregar_Cuenta").modal("toggle");
          $("#agregarCuenta").trigger("reset");
          $("#tblCuentasBancarias").DataTable().ajax.reload();
          lobiAlert("Registro agregado", "success");
        } else {
          /* $("#agregar_Cuenta").modal("toggle");
          $("#agregarCuenta").trigger("reset"); */
          lobiAlert("Algo salio mal", "error");
        }
        cmtipoSelect.set("0");
      },
    });
  }
}

function agregaCajaChica() {
  var idu = $("#txtUsuario").val();

  var nombreCuenta = $("#txtNombreCuenta").val();
  var tipoCuenta = $("#cmbTipoCuenta").val();

  var responsable = $("#cmbResponsable").val();
  var descripcionCaja = $("#areaDescripcion").val();
  var sucursal = $("#cmbLocacion").val();
  var saldoInicialCaja = $("#txtSaldoInicialCaja").val();

  var fkempresa = $("#emp_id").val();
  var estado = 1;

  if (!tipoCuenta || tipoCuenta < 1) {
    $("#invalid-tipoCnt").css("display", "block");
    $("#cmbTipoCuenta").addClass("is-invalid");
  }
  if (!nombreCuenta) {
    $("#invalid-nombreCnt").css("display", "block");
    $("#txtNombreCuenta").addClass("is-invalid");
  }
  if (!responsable) {
    $("#invalid-ResponsableCajaCh").css("display", "block");
    $("#cmbResponsable").addClass("is-invalid");
  }
  if (!sucursal) {
    $("#invalid-SucursalCajaCh").css("display", "block");
    $("#cmbLocacion").addClass("is-invalid");
  }
  if (!saldoInicialCaja) {
    $("#invalid-SaldoInCajaCh").css("display", "block");
    $("#txtSaldoInicialCaja").addClass("is-invalid");
  }

  var badNombre =
    $("#invalid-nombreCnt").css("display") === "block" ? false : true;
  var badResp =
    $("#invalid-ResponsableCajaCh").css("display") === "block" ? false : true;
  var badSuc =
    $("#invalid-SucursalCajaCh").css("display") === "block" ? false : true;
  var badSaldo =
    $("#invalid-SaldoInCajaCh").css("display") === "block" ? false : true;

  if (badNombre && badResp && badSuc && badSaldo) {
    $.ajax({
      url: "php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "agregar_cuenta_caja_chica",
        data: tipoCuenta,
        data2: nombreCuenta,
        data3: fkempresa,
        data4: estado,
        data5: responsable,
        data6: descripcionCaja,
        data7: sucursal,
        data8: saldoInicialCaja,
        data10: idu,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta[0].status) {
          $("#agregar_Cuenta").modal("toggle");
          $("#agregarCuenta").trigger("reset");
          $("#tblCuentasBancarias").DataTable().ajax.reload();
          lobiAlert("Registro agregado", "success");
        } else {
          lobiAlert("Algo salio mal", "error");
        }
        cmtipoSelect.set("0");
      },
    });
  }
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
