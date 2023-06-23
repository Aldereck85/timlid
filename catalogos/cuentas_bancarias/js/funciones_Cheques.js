function tablaInicialCheques(idCuenta) {
  $.ajax({
    type: "POST",
    url: "functions/tabla_Cheques.php",
    data: { idDetalle: idCuenta },
    success: function (r) {
      var datos = JSON.parse(r);
      console.log(r);
      $("#saldoG").text(datos.saldoG);
      $("#tipoCuentaG").text(datos.tipoCuenta);
      $("#nomCuentaG").text(datos.nomCuenta);
      $("#noCuentaG").text(datos.noCuenta);
      $("#clabeG").text(datos.clabe);
      $("#bancoG").text(datos.banco);
    },
  });
}

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  var idCuenta = $("#pkCuenta").val();
  $("#tblMovimientosCheques").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 10,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "",//btn-table-custom
        },
        buttonLiner: {
          tag: null,
        },
      },
      buttons: [
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar Excel</span>',
          className: "btn-custom--white-dark btn-custom",
          titleAttr: "Excel",
          exportOptions: {
            columns: ":visible",
          },
        },
      ],
    },
    ajax: {
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_checksTableMovements",
        data: idCuenta,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Fecha" },
      { data: "Descripción" },
      { data: "Retiro/cargo" },
      { data: "Deposito/Abono" },
      { data: "Acciones" },
    ],
  });
});

$(document).ready(function () {
  $("#aCuenta").hide();
  $("#moneda_diferente").hide();
  $("#moneda_diferenteT").hide();
  $("#aCuentaP").hide();
  $("#moneda_diferenteP").hide();
  var checkbox = document.querySelector("input[name=checkDisponer]");

  checkbox.addEventListener("change", function () {
    $("#invalid-cuentaDisp").css("display", "none");
    $("#cmbACuenta").removeClass("is-invalid");
    if (this.checked) {
      // Checkbox is checked
      $("#aCuenta").show();
    } else {
      // Checkbox is not checked
      $("#aCuenta").hide();
    }
  });
  var checkboxP = document.querySelector("input[name=checkDisponerP]");

  checkboxP.addEventListener("change", function () {
    $("#invalid-cuentaDeposito").css("display", "none");
    $("#cmbACuentaP").removeClass("is-invalid");
    if (this.checked) {
      // Checkbox is checked
      $("#aCuentaP").show();
    } else {
      // Checkbox is not checked
      $("#aCuentaP").hide();
    }
  });
});

function lobiboxAlert(tipo, mensaje) {
  var tipoImg = tipo === "success" ? "checkmark.svg" : "warning_circle.svg";
  Lobibox.notify(tipo, {
    size: "mini",
    rounded: true,
    delay: 4000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: true,
    img: "../../img/timdesk/" + tipoImg,
    msg: mensaje,
  });
}

function disponerCh(getId) {
  $("#cheques_Disponer").modal("show");
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasCheques.php",
    data: { id: getId },
    success: function (res) {
      var datos = JSON.parse(res);

      $("#idCuentaActualCheque").val(datos.idCuentaActualCheques);
      $("#cmbACuenta").html(datos.listaCuentasDisponer);
      $("#moActualCheque").val(datos.moActual);
      $("#nomCuentaCheque").val(datos.nomCuenta);
      $("#saldoDisponibleCheque").val(datos.saldoCheque);
      $("#cmbMonedaAcD").val(datos.monedaAct);
    },
  });
}

function agregar_DisposicionCh(fecha) {
  var msjwarningSaldo = "Saldo Insuficiente para disponer de cuenta origen!";
  var msjsucces = "¡Disposición exitosa!";
  var msjerror = "Disposición no realizada";

  var idActual = $("#idCuentaActualCheque").val();
  var idACuenta = $("#cmbACuenta").val();
  let creditDisponible = $("#saldoDisponibleCheque").val();

  let cantidadDisponer = $("#cantidadDisponer").val();
  let tipoCambio = $("#tipoDeCambioDis").val();
  let observaciones = $("#areaObservacionD").val();

  let tipoC = $("#tipoC").val();
  let nomCuenta = $("#nomCuentaCheque").val();

  let moActual = $("#moActualCheque").val();
  let moACuenta = $("#moACuenta").val();

  var valorCuenta = $("#cmbACuenta").val();

  var hayCuenta = 0;

  var s1 = parseFloat($("#saldoDisponibleCheque").val());
  var s3 = parseFloat($("#cantidadDisponer").val());

  if (Math.fround(s3) > Math.fround(s1)) {
    // si la cantidad es menor hace la disposición,
    lobiboxAlert("error", msjwarningSaldo);
    return;
  }

  if ($("#checkDisponer").is(":checked")) {
    hayCuenta = 1;
    if (valorCuenta == 0) {
      $("#invalid-cuentaDisp").css("display", "block");
      $("#cmbACuenta").addClass("is-invalid");
    }
    if (moACuenta != moActual) {
      if ($("#tipoDeCambioDis").val().length == 0) {
        $("#invalid-monedaCambio").css("display", "block");
        $("#tipoDeCambioDis").addClass("is-invalid");
      }
    }
  } else {
    hayCuenta = 0;
  }
  if ($("#cantidadDisponer").val().length == 0) {
    $("#invalid-cantidadDisp").css("display", "block");
    $("#cantidadDisponer").addClass("is-invalid");
  }

  var badCuentaDisp =
    $("#invalid-cuentaDisp").css("display") === "block" ? false : true;
  var badCantidadDisp =
    $("#invalid-cantidadDisp").css("display") === "block" ? false : true;
  var badMonedaCambio =
    $("#invalid-monedaCambio").css("display") === "block" ? false : true;

  if (badCuentaDisp && badCantidadDisp && badMonedaCambio) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_DisposicionCheques.php",
      data: {
        idActual: idActual,
        idACuenta: idACuenta,
        creditDisponible: creditDisponible,
        cantidadDisponer: cantidadDisponer,
        tipoCambio: tipoCambio,
        areaObservacionD: observaciones,
        hayCuenta: hayCuenta,
        tipoC: tipoC,
        nomCuenta: nomCuenta,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          var idActual = $("#idCuentaActualCheque").val();
          tablaInicialCheques(idActual);

          $("#cheques_Disponer").modal("toggle");
          $("#chequesDisponer").trigger("reset");
          $("#aCuenta").hide();
          $("#tblMovimientosCheques").DataTable().ajax.reload();
          lobiboxAlert("success", msjsucces);
        } else {
          var msjwarning = "Dispocicion no realizada!";
          lobiboxAlert("error", msjwarning);
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        console.log("Request: " + XMLHttpRequest);
        console.log("Status: " + textStatus);
        console.log("Error: " + errorThrown);
      },
    });
  }
}

function get_ACuenta(idActual) {
  var id = $("#cmbACuenta").val();
  var estado = $("#cmbACuenta").val();
  var mActual = $("#moActualCheque").val();

  if (estado == "0") {
    $("#moACuenta").val("");
    $("#invalid-cuentaDisp").css("display", "block");
    $("#cmbACuenta").addClass("is-invalid");
  } else {
    $("#invalid-cuentaDisp").css("display", "none");
    $("#cmbACuenta").removeClass("is-invalid");
    $.ajax({
      type: "POST",
      url: "functions/get_MonedaACuenta.php",
      data: {
        idCuentaActual: idActual,
        idCuentaDestino: id,
        moOrigen: mActual,
      },
      success: function (res) {
        var datos = JSON.parse(res);
        $("#idAcuenta").val(datos.idACuenta);
        $("#moACuenta").val(datos.moACuenta);
        $("#monedaACuenta").val(datos.moACuentaDescrip);
        $("#tipoC").val(datos.tipoC);
        $("#saldoCuentaDest").val(datos.saldoACuenta);
        $("#tipoDeCambioDis").val(datos.valorTipoCambio);

        var mA = $("#moActualCheque").val();
        var mD = $("#moACuenta").val();

        if (mA == mD) {
          $("#moneda_diferente").hide();
          $("#invalid-monedaCambio").css("display", "none");
          $("#tipoDeCambioDis").removeClass("is-invalid");
        } else {
          $("#moneda_diferente").show();
        }
      },
    });
  }
}

function disposicionTransfer(id) {
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasCheques.php",
    data: { id: getId },
    success: function (res) {
      var datos = JSON.parse(res);
      // TRANSFERENCIA
      $("#cmbCuentaDestino").html(datos.listaCuentasTodas);
      $("#idCuentaActualTransfer").val(datos.idCuentaAc);
      $("#moActualT").val(datos.moActual);
      $("#moDescripcionActual").val(datos.moDescripcionActual);
      $("#saldoCuentaActual").val(datos.saldoCheque);
      $("#nomCuentaT").val(datos.nomCuenta);
      $("#moDescripcionActual").val(datos.monedaAct);
    },
  });
}

function get_monedasTransfer(idActual) {
  var id = $("#cmbCuentaDestino").val();
  var estado = $("#cmbCuentaDestino").val();
  var mActual = $("#moActualT").val();

  if (estado == 0) {
    $("#moDestino").val("");
    $("#invalid-cuentaTrans").css("display", "block");
    $("#cmbCuentaDestino").addClass("is-invalid");
  } else {
    $("#invalid-cuentaTrans").css("display", "none");
    $("#cmbCuentaDestino").removeClass("is-invalid");
    $.ajax({
      type: "POST",
      url: "functions/get_MonedaACuenta.php",
      data: {
        idCuentaActual: idActual,
        idCuentaDestino: id,
        moOrigen: mActual,
      },
      success: function (res) {
        var datos = JSON.parse(res);
        $("#idDestino").val(datos.idACuenta);
        $("#saldoCuentaDest").val(datos.saldoACuenta);
        $("#moDestino").val(datos.moACuenta);
        $("#monedaDescrip").val(datos.moACuentaDescrip);
        $("#tipoCuenta").val(datos.tipoC);
        $("#tipoDeCambioT").val(datos.valorTipoCambio);

        var mA = $("#moActualT").val();
        var mD = $("#moDestino").val();

        if (mA == mD) {
          $("#moneda_diferenteT").hide();
          $("#invalid-monedaCambioTrans").css("display", "none");
          $("#tipoDeCambioT").removeClass("is-invalid");
        } else {
          $("#moneda_diferenteT").show();
        }
      },
    });
  }
}

//Guardar Transferencia
function guardarTransferenciaCh() {
  var msjsucces = "¡Transferencia exitosa!";
  var msjwarningSaldo = "Saldo insuficiente para hacer la transferencia";
  var msjwarning = "Transferencia no realizada!";

  var idCuentaActual = $("#idCuentaActualTransfer").val();

  var estado = $("#cmbCuentaDestino").val();
  var idCuentaDestino = $("#idDestino").val();
  var estado = $("#cmbCuentaDestino").val();
  var saldoIn = $("#saldoCuentaActual").val();
  var monActual = $("#moActualT").val();
  var monDestino = $("#moDestino").val();
  var cantidadEnvio = $("#txtCantidadT").val();
  var tipoCambio = $("#tipoDeCambioT").val();
  var observaciones = $("#areaObservaciones").val();
  var fechaTransferencia = $("#txtFechaTransferencia").val();
  var nomCuentaT = $("#nomCuentaT").val();
  var tipoC = $("#tipoCuenta").val();

  var s1 = parseFloat($("#saldoCuentaActual").val());
  var s2 = parseFloat($("#txtCantidadT").val());

  if (Math.fround(s2) > Math.fround(s1)) {
    lobiboxAlert("error", msjwarningSaldo);
    return;
  }
  if (estado < 1 || !estado) {
    $("#invalid-cuentaTrans").css("display", "block");
    $("#cmbCuentaDestino").addClass("is-invalid");
  }
  if (
    $("#idCuentaActualTransfer").val().length == 0 &&
    $("#idDestino").val().length == 0
  ) {
    $("#invalid-cuentaTrans").css("display", "block");
    $("#cmbCuentaDestino").addClass("is-invalid");
  }
  if (monActual != monDestino) {
    if ($("#tipoDeCambioT").val().length == 0) {
      $("#invalid-monedaCambioTrans").css("display", "block");
      $("#tipoDeCambioT").addClass("is-invalid");
    }
  }
  if ($("#txtCantidadT").val().length == 0) {
    $("#invalid-cantidadTrans").css("display", "block");
    $("#txtCantidadT").addClass("is-invalid");
  }
  if ($("#txtFechaTransferencia").val().length == 0) {
    $("#invalid-fechaTrans").css("display", "block");
    $("#txtFechaTransferencia").addClass("is-invalid");
  }

  var badCuentaTrans =
    $("#invalid-cuentaTrans").css("display") === "block" ? false : true;
  var badMonedaTrans =
    $("#invalid-monedaCambioTrans").css("display") === "block" ? false : true;
  var badCantidadTrans =
    $("#invalid-cantidadTrans").css("display") === "block" ? false : true;
  var badFechaTrans =
    $("#invalid-fechaTrans").css("display") === "block" ? false : true;

  if (badCuentaTrans && badMonedaTrans && badCantidadTrans && badFechaTrans) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_TransferenciaCheques.php",
      data: {
        idCuentaActual: idCuentaActual,
        idCuentaDestino: idCuentaDestino,
        monedaOrigen: monActual,
        monedaDestino: monDestino,
        txtCantidadT: cantidadEnvio,
        tipoCambio: tipoCambio,
        fechaTransferncia: fechaTransferencia,
        areaObservacionD: observaciones,
        saldoCuentaActual: saldoIn,
        nomCuentaT: nomCuentaT,
        tipoC: tipoC,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          var idCuentaActual = $("#idCuentaActualTransfer").val();
          tablaInicialCheques(idCuentaActual);
          $("#transferencia_Modal").modal("toggle");
          $("#transferencia").trigger("reset");
          $("#tblMovimientosCheques").DataTable().ajax.reload();
          lobiboxAlert("success", msjsucces);
        } else {
          lobiboxAlert("error", msjwarning);
        }
      },
    });
  }
}

function pagar(idAc) {
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasCheques.php",
    data: { id: getId },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#idCuentaAcP").val(datos.idCuentaAc);
      $("#saldoDisponibleP").val(datos.saldoCheque);
      $("#cmbACuentaP").html(datos.listaCuentasTodas);
      $("#moActualP").val(datos.moActual);
      $("#nomCuentaP").val(datos.nomCuenta);
      $("#cmbMonedaAcP").val(datos.monedaAct);
    },
  });
}

function get_ACuentaP(idActual) {
  var id = $("#cmbACuentaP").val();
  var mActual = $("#moActualP").val();
  var estado = $("#cmbACuentaP").val();

  if (estado == 0) {
    $("#moACuentaP").val("");
    $("#invalid-cuentaDeposito").css("display", "block");
    $("#cmbACuentaP").addClass("is-invalid");
  } else {
    $("#invalid-cuentaDeposito").css("display", "none");
    $("#cmbACuentaP").removeClass("is-invalid");
    $.ajax({
      type: "POST",
      url: "functions/get_MonedaACuenta.php",
      data: {
        idCuentaActual: idActual,
        idCuentaDestino: id,
        moOrigen: mActual,
      },
      success: function (res) {
        var datos = JSON.parse(res);
        $("#idAcuentaP").val(datos.idACuenta);
        $("#saldoCuentaDestP").val(datos.saldoACuenta);
        $("#moACuentaP").val(datos.moACuenta);
        $("#monedaACuentaP").val(datos.moACuentaDescrip);
        $("#tipoCP").val(datos.tipoC);

        $("#tipoDeCambioP").val(datos.valorTipoCambio);

        var mA = $("#moActualP").val();
        var mD = $("#moACuentaP").val();
        //monedas(mA, mD);

        if (mA == mD) {
          $("#moneda_diferenteP").hide();
          $("#invalid-monedaCambioDep").css("display", "none");
          $("#tipoDeCambioP").removeClass("is-invalid");
        } else {
          $("#moneda_diferenteP").show();
        }
      },
    });
  }
}

function agregar_Pago() {
  var msjsucces = "¡Pago realizado con exito!";
  var msjwarningSaldo = "¡Saldo Insuficiente para Pagar de la cuenta origen!";
  var msjwarning = "Pago no realizado!";

  var idActual = $("#idCuentaAcP").val();
  var idACuenta = $("#cmbACuentaP").val();
  let creditDisponible = $("#saldoDisponibleP").val();

  let cantidadDisponer = $("#cantidadDisponerP").val();
  let tipoCambio = $("#tipoDeCambioP").val();
  let observaciones = $("#areaObservacionP").val();

  let tipoC = $("#tipoCP").val();
  let nomCuenta = $("#nomCuentaP").val();

  let moActual = $("#moActualP").val();
  let moACuenta = $("#moACuentaP").val();

  var valorCuenta = $("#cmbACuentaP").val();

  var hayCuenta = 0;

  var s1 = parseFloat($("#saldoCuentaDestP").val());
  var s2 = parseFloat($("#cantidadDisponerP").val());

  if (Math.fround(s2) > Math.fround(s1)) {
    lobiboxAlert("error", msjwarningSaldo);
    return;
  }

  if ($("#checkDisponerP").is(":checked")) {
    hayCuenta = 1;
    if (valorCuenta == 0) {
      $("#invalid-cuentaDeposito").css("display", "block");
      $("#cmbACuentaP").addClass("is-invalid");
    }
    if (moACuenta != moActual) {
      if ($("#tipoDeCambioP").val().length == 0) {
        $("#invalid-monedaCambioDep").css("display", "block");
        $("#tipoDeCambioP").addClass("is-invalid");
      }
    }
  } else {
    hayCuenta = 0;
  }
  if ($("#cantidadDisponerP").val().length == 0) {
    $("#invalid-cantidadDeposito").css("display", "block");
    $("#cantidadDisponerP").addClass("is-invalid");
  }

  var badCuentaDep =
    $("#invalid-cuentaDeposito").css("display") === "block" ? false : true;
  var badCantidadDep =
    $("#invalid-cantidadDeposito").css("display") === "block" ? false : true;
  var badMonedaCambioDep =
    $("#invalid-monedaCambioDep").css("display") === "block" ? false : true;

  if (badCuentaDep && badCantidadDep && badMonedaCambioDep) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_PagoCheques.php",
      data: {
        idActual: idActual,
        idACuenta: idACuenta,
        creditDisponible: creditDisponible,
        cantidadDisponer: cantidadDisponer,
        tipoCambio: tipoCambio,
        areaObservacionD: observaciones,
        hayCuenta: hayCuenta,
        tipoC: tipoC,
        nomCuenta: nomCuenta,
      },
      success: function (data, status, xhr) {
        console.log(data);
        if (data.trim() == "exito") {
          var idActual = $("#idCuentaAcP").val();
          tablaInicialCheques(idActual);

          $("#credito_Pagar").modal("toggle");
          $("#creditoPagar").trigger("reset");
          $("#aCuentaP").hide();
          $("#tblMovimientosCheques").DataTable().ajax.reload();
          lobiboxAlert("success", msjsucces);
        } else {
          lobiboxAlert("error", msjwarning);
        }
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
