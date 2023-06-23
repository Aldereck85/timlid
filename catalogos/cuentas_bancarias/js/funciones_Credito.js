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
  $("#tblMovimientosCredito").dataTable({
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
        funcion: "get_creditTableMovements",
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
    if (this.checked) {
      // Checkbox is checked
      $("#aCuentaP").show();
    } else {
      // Checkbox is not checked
      $("#aCuentaP").hide();
    }
  });
});

function disponer(getId) {
  //abrir el modal Disponer
  $("#credito_Disponer").modal("show");
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasCredito.php",
    data: { id: getId },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#idCuentaAc").val(datos.idCuentaAc);
      $("#saldoDisponible").val(datos.saldoDisponible);
      $("#cmbACuenta").html(datos.listaCuentasDisponer);
      $("#moActual").val(datos.moActual);
      $("#nomCuenta").val(datos.nomCuenta);
      $("#cmbMonedaAcD").val(datos.monedaDescripcion);
    },
  });
}

function agregar_Disposicion(fecha) {
  var idActual = $("#idCuentaAc").val();
  var idACuenta = $("#cmbACuenta").val();
  let creditDisponible = $("#saldoDisponible").val();

  let cantidadDisponer = $("#cantidadDisponer").val();
  let tipoCambio = $("#tipoDeCambio").val();
  let observaciones = $("#areaObservacionD").val();

  let tipoC = $("#tipoC").val();
  let nomCuenta = $("#nomCuenta").val();

  let moActual = $("#moActual").val();
  let moACuenta = $("#moACuenta").val();

  var valorCuenta = $("#cmbACuenta").val();

  var hayCuenta = 0;

  var s1 = parseFloat($("#saldoDisponible").val());
  var s2 = parseFloat($("#cantidadDisponer").val());

  if (Math.fround(s2) > Math.fround(s1)) {
    lobiboxAlert("error", "¡Saldo Insuficiente para disponer!");
    return;
  }
  if ($("#checkDisponer").is(":checked")) {
    hayCuenta = 1;
    if (valorCuenta == 0) {
      $("#invalid-cuentaDisp").css("display", "block");
      $("#cmbACuenta").addClass("is-invalid");
    }
    if (moACuenta != moActual) {
      if ($("#tipoDeCambio").val().length == 0) {
        $("#invalid-monedaCambio").css("display", "block");
        $("#tipoDeCambio").addClass("is-invalid");
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
      url: "functions/agregar_Disposicion.php",
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
          tablaInicialCredito(idActual);
          $("#aCuenta").hide();
          $("#credito_Disponer").modal("toggle");
          $("#creditoDisponer").trigger("reset");
          $("#tblMovimientosCredito").DataTable().ajax.reload();
          lobiboxAlert("success", "¡Disposición exitosa!");
        } else {
          lobiboxAlert("error", "¡Dispocicion no realizada!");
        }
      },
    });
  }
}

function get_ACuenta(idActual) {
  var id = $("#cmbACuenta").val();
  var mActual = $("#moActual").val();
  var mACuenta = $("#moACuenta").val();
  var estado = $("#cmbACuenta").val();

  if (estado == 0) {
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
        $("#tipoDeCambio").val(datos.valorTipoCambio);

        var mA = $("#moActual").val();
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
    url: "functions/get_CuentasCredito.php",
    data: { id: getId },
    success: function (res) {
      var datos = JSON.parse(res);

      // TRANSFERENCIA
      $("#cmbCuentaDestino").html(datos.listaCuentasDisponer);
      $("#idCuentaActualTransfer").val(datos.idCuentaAc);
      $("#moActualT").val(datos.moActual);
      $("#moDescripcionActual").val(datos.moDescripcionActual);
      $("#saldoCuentaActual").val(datos.saldoDisponible);
      $("#nomCuentaT").val(datos.nomCuenta);
      $("#moDescripcionActual").val(datos.monedaDescripcion);
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

        $("#idDestinoT").val(datos.idACuenta);
        $("#saldoCuentaDest").val(datos.saldoACuenta);
        //$("#moDestino").val(datos.moACuenta);
        $("#moDestinoT").val(datos.moACuenta);
        $("#monedaDescripT").val(datos.moACuentaDescrip);
        $("#tipoCuenta").val(datos.tipoC);
        $("#tipoDeCambioT").val(datos.valorTipoCambio);

        var mA = $("#moActualT").val();
        var mD = $("#moDestinoT").val();

        if (mA == mD) {
          $("#tipoDeCambioT").val("");
          $("#monedaDescripT").val("");
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
function guardarTransferenciaCr() {
  var idCuentaActual = $("#idCuentaActualTransfer").val();

  var estado = $("#cmbCuentaDestino").val();
  var idCuentaDestino = $("#idDestinoT").val();
  var saldoIn = $("#saldoCuentaActual").val();
  var monActual = $("#moActualT").val();
  var monDestino = $("#moDestinoT").val();
  var cantidadEnvio = $("#txtCantidadT").val();
  var tipoCambio = $("#tipoDeCambioT").val();
  var observaciones = $("#areaObservaciones").val();
  var nomCuentaT = $("#nomCuentaT").val();
  var tipoC = $("#tipoCuenta").val();

  //SI NO ESTA SELECCIONADA UNA CUANTA BANCARIA BORRA TODO

  //SI HAY CUENTAS PARA TRANSFERIR

  var s1 = parseFloat($("#saldoCuentaActual").val());
  var s2 = parseFloat($("#txtCantidadT").val());

  if (Math.fround(s2) > Math.fround(s1)) {
    lobiboxAlert("error", "¡Saldo insuficiente para hacer la transferencia!");
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
    if ($("#txtCantidadT").val().length == 0) {
      $("#invalid-monedaCambioTrans").css("display", "block");
      $("#tipoDeCambioT").addClass("is-invalid");
    }
  }
  if ($("#txtCantidadT").val().length == 0) {
    $("#invalid-cantidadTrans").css("display", "block");
    $("#txtCantidadT").addClass("is-invalid");
  }

  var badCuentaTrans =
    $("#invalid-cuentaTrans").css("display") === "block" ? false : true;
  var badMonedaTrans =
    $("#invalid-monedaCambioTrans").css("display") === "block" ? false : true;
  var badCantidadTrans =
    $("#invalid-cantidadTrans").css("display") === "block" ? false : true;

  if (badCuentaTrans && badMonedaTrans && badCantidadTrans) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_TransferenciaCredito.php",
      data: {
        idCuentaActual: idCuentaActual,
        idCuentaDestino: idCuentaDestino,
        monedaOrigen: monActual,
        monedaDestino: monDestino,
        txtCantidadT: cantidadEnvio,
        tipoCambio: tipoCambio,
        areaObservacionD: observaciones,
        saldoCuentaActual: saldoIn,
        nomCuentaT: nomCuentaT,
        tipoC: tipoC,
      },
      success: function (data, status, xhr) {
        console.log(data);
        if (data.trim() == "exito") {
          tablaInicialCredito(idCuentaActual);
          $("#transferencia_Modal").modal("toggle");
          $("#transferencia").trigger("reset");
          $("#tblMovimientosCredito").DataTable().ajax.reload();
          lobiboxAlert("success", "¡Transferencia exitosa!");
        } else {
          lobiboxAlert("error", "¡Transferencia no realizada!");
        }
      },
    });
  }
}

function pagar(idAc) {
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasCredito.php",
    data: { id: getId },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#idCuentaAcP").val(datos.idCuentaAc);
      $("#saldoDisponibleP").val(datos.saldoDisponible);
      $("#cmbACuentaP").html(datos.listaCuentasDisponer);
      $("#moActualP").val(datos.moActual);
      $("#nomCuentaP").val(datos.nomCuenta);
      //$("#cmbMonedaAcP").val(datos.monedaDescripcion);
      $("#moDescripcionActualP").val(datos.monedaDescripcion);
    },
  });
}

function get_ACuentaP(idActual) {
  var id = $("#cmbACuentaP").val();
  var mActual = $("#moActualP").val();
  var mACuenta = $("#moACuentaP").val();
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
        console.log(res);
        var datos = JSON.parse(res);
        $("#idAcuentaP").val(datos.idACuenta);
        $("#saldoCuentaDestP").val(datos.saldoACuenta);
        $("#moACuentaP").val(datos.moACuenta);
        $("#tipoCP").val(datos.tipoC);
        $("#monedaACuentaP").val(datos.moACuentaDescrip);
        $("#tipoDeCambioP").val(datos.valorTipoCambio);

        var mA = $("#moActualP").val();
        var mD = $("#moACuentaP").val();

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

  var s1 = $("#saldoCuentaDestP").val();
  parseFloat(s1);
  var s2 = $("#cantidadDisponerP").val();
  parseFloat(s2);

  if ($("#checkDisponerP").is(":checked")) {
    hayCuenta = 1;
    if (Math.fround(s2) > Math.fround(s1)) {
      lobiboxAlert("error", "¡Saldo Insuficiente!");
      return;
    }
    if (valorCuenta == 0) {
      $("#invalid-cuentaDeposito").css("display", "block");
      $("#cmbACuentaP").addClass("is-invalid");
    }
    if (moACuenta != moActual) {
      // ACTUALMENTE NO SE USARA DIFERENTES MONEDAS
      /* if ($("#tipoDeCambioP").val().length == 0) {
        $("#tipoDeCambioP")[0].reportValidity();
        $("#tipoDeCambioP")[0].setCustomValidity("Digite el tipo de cambio.");
      } */
    }
  } else {
    hayCuenta = 0;
  }
  if ($("#cantidadDisponerP").val().length == 0) {
    $("#invalid-cantidadDeposito").css("display", "block");
    $("#cantidadDisponerP").addClass("is-invalid");
  }

  var badCuentaPago =
    $("#invalid-cuentaDeposito").css("display") === "block" ? false : true;
  var badCantidadPago =
    $("#invalid-cantidadDeposito").css("display") === "block" ? false : true;
  var badMonedaCambioPago =
    $("#invalid-monedaCambioDep").css("display") === "block" ? false : true;

  if (badCuentaPago && badCantidadPago && badMonedaCambioPago) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_Pago.php",
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
          tablaInicialCredito(idActual);
          $("#credito_Pagar").modal("toggle");
          $("#creditoPagar").trigger("reset");
          $("#aCuentaP").hide();
          $("#tblMovimientosCredito").DataTable().ajax.reload();
          lobiboxAlert("success", "¡Pago realizado con exito!");
        } else {
          console.log(data);
          console.log("kjfdshgfkjdskjfhkj");
          lobiboxAlert("error", "¡Pago no realizadossssss!");
        }
      },
    });
  }
}

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

function tablaInicialCredito(idCuenta) {
  $.ajax({
    type: "POST",
    url: "functions/tabla_credito.php",
    data: { idDetalle: idCuenta },
    success: function (r) {
      var datos = JSON.parse(r);
      $("#limiteCredTbl").text(datos.limiteCred + " " + datos.claveMon);
      $("#credUtlTbl").text(datos.credUtilizado + " " + datos.claveMon);
      $("#credDispTbl").text(datos.credDisponible + " " + datos.claveMon);
      $("#tipoCntTbl").text(datos.tipoCuenta);
      $("#nombreCntTbl").text(datos.nombre);
      $("#noCredTbl").text(datos.noCredito);
      $("#bancoTbl").text(datos.banco);
    },
  });
}
