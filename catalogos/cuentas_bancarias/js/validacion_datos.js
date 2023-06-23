$(document).ready(function () {
  $("#btnAgregar").css("display", "none");
  $("#btnAgregarDin").css("display", "none");
  //$("#btnAgregarGasto").css("display","none");
  $("#cajaChicaA").hide();
});

$(document).ready(function () {
  $("#btnRetirar").css("display", "none");
});

function lobiSucces(msjbien) {
  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: true,
    //img: '<i class="fas fa-check-circle"></i>',
    img: "../../img/timdesk/checkmark.svg",
    msg: msjbien,
  });
}

function lobiWarning(msjwarning) {
  Lobibox.notify("error", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../../../img/timdesk/warning_circle.svg",
    img: null,
    msg: msjwarning,
  });
}

function lobiError(msjerror) {
  Lobibox.notify("error", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: true,
    img: "../../img/chat/notificacion_error.svg",
    msg: msjerror,
  });
}

//Creamos la Funcion
function validaCLABE1(evt, input) {
  var key = window.Event ? evt.which : evt.keyCode;

  if (key == 46) {
    //$("#result3").val("HHHH");
  } else {
    $("#result2").val($("#txtCLABE").val().length); //Detectamos los Caracteres del Input
    $("#result2").addClass("mui--is-not-empty"); //Agregamos la Clase de Mui para decir que el input no esta vacio y que suba el Texto del Label(Como cuando haces Focus)
    var valor = $("#result2").val();
    if (valor < 18) {
      $("#invalid-claveCuenta").text("La CLABE no es valida.");
      $("#invalid-claveCuenta").css("display", "block");
      $("#txtCLABE").addClass("is-invalid");
    } else {
      $("#invalid-claveCuenta").css("display", "none");
      $("#txtCLABE").removeClass("is-invalid");
      return false;
    }
  }
}

function validaNoCuenta() {
  $("#result1").val($("#txtNoCuenta").val().length); //Detectamos los Caracteres del Input
  $("#result1").addClass("mui--is-not-empty"); //Agregamos la Clase de Mui para decir que el input no esta vacio y que suba el Texto del Label(Como cuando haces Focus)
  var valor = $("#result1").val();
  if (valor < 11) {
    $("#notaNoCuenta").css("display", "block");
  } else {
    $("#notaNoCuenta").css("display", "none");
    return false;
  }
}

$(document).on("change", "#cmbRetirarDinero", function (event) {
  if ($("#cmbRetirarDinero option:selected").val() == 1) {
    $("#capitalRetiro").hide();
    $("#gasto").hide();
    $("#compra").show();
  } else if ($("#cmbRetirarDinero option:selected").val() == 2) {
    $("#capitalRetiro").hide();
    $("#gasto").show();
    $("#compra").hide();
  } else if ($("#cmbRetirarDinero option:selected").val() == 3) {
  } else {
    $("#btnAgregarGasto").css("display", "none");
  }
});

function ir_Ventana(idAc) {
  $("#inyeccion_Capital").modal("show");
  $.ajax({
    type: "POST",
    url: "functions/get_Combos.php",
    data: { id: idAc },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#idCuentaIny").val(datos.idCajaActual);
      $("#saldoIny").val(datos.saldoInicialCaja);
      $("#cmbCuentasOrigen").html(datos.listaO);
    },
  });
}

function ir_ModalInyeccion(idAc) {
  $("#inyeccion_Capital").modal("show");
  $.ajax({
    type: "POST",
    url: "functions/get_CombosOtros.php",
    data: { id: idAc },
    success: function (res) {
      var datos = JSON.parse(res);

      $("#idCuentaIny").val(datos.idCajaActual);
      $("#saldoIny").val(datos.saldoInicialO);
      $("#MonedaDescripcion").val(datos.moDescripcion);
      $("#cmbCuentasOrigen").html(datos.listaO);
    },
  });
}

function agregar_provedor() {
  var contacto = $("#txtContacto").val();
  if ($("#txtContacto").val() == "") {
    $("#txtContacto").prop("required", true);
  }

  $("#btnAgregarProveedor").click(function () {
    var razon = $("#txtRazon").val();
    var nombre = $("#txtNombre").val();
    var rfc = $("#txtRFC").val();
    var calle = $("#txtCalle").val();
    var numeroext = $("#txtNumeroEx").val();
    var numeroint = $("#txtNumeroInt").val();
    var colonia = $("#txtColonia").val();
    var municipio = $("#txtMunicipio").val();
    var pais = $("#txtPais").val();
    var estado = $("#cmbEstados").val();
    var cp = $("#txtCP").val();
    var diascredito = $("#txtDiasCredito").val();
    var limitepractico = $("#txtLimiteCredito").val();

    var contacto = $("#txtContacto").val();
    var apellido = $("#txtApellido").val();
    var telefono = $("#txtTelefono").val();
    var celular = $("#txtCelular").val();
    var email = $("#txtEmail").val();
    $.ajax({
      url: "functions/agregar_Proveedor.php",
      type: "POST",
      data: {
        txtRazon: razon,
        txtNombre: nombre,
        txtRFC: rfc,
        txtCalle: calle,
        txtNumeroEx: numeroext,
        txtNumeroInt: numeroint,
        txtColonia: colonia,
        txtMunicipio: municipio,
        txtPais: pais,
        cmbEstados: estado,
        txtCP: cp,
        txtDiasCredito: diascredito,
        txtLimiteCredito: limitepractico,
        txtContacto: contacto,
        txtApellido: apellido,
        txtTelefono: telefono,
        txtCelular: celular,
        txtEmail: email,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#agregar_Proveedor").modal("toggle");
          $("#agregarProveedor").trigger("reset");
          $("#tblProveedores").DataTable().ajax.reload();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Registro agregado!",
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
            msg: "Ocurrió un error al agregar",
          });
        }
      },
    });
  });
}

function filterFloat(evt, input) {
  // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
  var key = window.Event ? evt.which : evt.keyCode;
  var chark = String.fromCharCode(key);
  var tempValue = input.value + chark;
  if (key >= 48 && key <= 57) {
    if (filter(tempValue) === false) {
      return false;
    } else {
      return true;
    }
  } else {
    if (key == 8 || key == 13 || key == 0) {
      return true;
    } else if (key == 46) {
      if (filter(tempValue) === false) {
        return false;
      } else {
        return true;
      }
    } else {
      return false;
    }
  }
}

function filter(__val__) {
  var preg = /^([0-9]+\.?[0-9]{0,2})$/;
  if (preg.test(__val__) === true) {
    return true;
  } else {
    return false;
  }
}

function agregarFila() {
  var table = document.getElementById("tablaprueba");
  var rowCount = table.rows.length;
  document.getElementById("tablaprueba").insertRow(-1).innerHTML =
    `<td>
                                                                      <input  name="txtProductos` +
    rowCount +
    `" id="txtProductos` +
    rowCount +
    `" type="hidden" readonly>

                                                                      <select name="cmbProductos` +
    rowCount +
    `"  class="form-control" id="cmbProductos` +
    rowCount +
    `" onchange="" >
                                                                        <option value="0"></option>
                                                                        <option value="1">X1</option>
                                                                        <option value="2">x2</option>
                                                                      </select>

                                                                    </td>
                                                                    <td>
                                                                      <input class="form-control" type="numeric" name="txtCantidad" id="txtCantidad" autofocus="" required="" placeholder="Ej. 10">
                                                                    </td>
                                                                    <td>
                                                                      <label  for="usr" name="lblUnidadMedida" id="lblUnidadMedida">Kilogramo</label>
                                                                    </td>
                                                                    <td>
                                                                      <label  for="usr" name="lblPrecioUnitario" id="lblPrecioUnitario">$</label>
                                                                    </td>
                                                                    <td>
                                                                      <label  for="usr" name="lblImpuestos" id="lblImpuestos">IVA 16%</label>
                                                                    </td>
                                                                    <td>
                                                                      <label  for="usr" name="lblImporte" id="lblImporte">$</label>
                                                                    </td>
                                                                    <td>
                                                                      <button type="button" class="btn btn-danger" onclick="event.preventDefault();                                                           
                                                                      $(this).closest('tr').remove();">Eliminar Fila</button>
                                                                    </td>`;
}

function categoria() {
  var id = $("#cmbCategoria").val();

  $.ajax({
    type: "POST",
    url: "functions/getSubcategoria.php",
    data: { id: id },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#pkCategoria").val(datos.pkcategoria);
      $("#cmbSubcategoria").html(datos.subCategorias);
    },
  });
}

function validar_documento() {
  var cbx = document.getElementById("checkCaja");
  var file = $("#inputFile").val();
  var invalidArchivoRet = $("#invalid-archivoCjChica");
  if (!file == "") {
    cbx.disabled = true;
    $("#checkCaja").prop("checked", false);
    invalidArchivoRet.css("display", "none");
  } else {
    cbx.disabled = false;
    invalidArchivoRet.css("display", "block");
  }
  //
}

function validar_documentoInyeccion() {
  var cmbx = document.getElementById("checkCajaInyeccion");
  var fileI = $("#inputFileInyeccion").val();
  var invalidArchivo = $("#invalid-archivoCjChica");
  if (!fileI == "") {
    cmbx.disabled = true;
    $("#checkCajaInyeccion").prop("checked", false);
    invalidArchivo.css("display", "none");
  } else {
    cmbx.disabled = false;
    invalidArchivo.css("display", "block");
  }
}

function getAbsolutePath() {
  var loc = window.location;
  var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf("/") + 1);
  return loc.href.substring(
    0,
    loc.href.length -
      ((loc.pathname + loc.search + loc.hash).length - pathName.length)
  );
}

function abrirModalTransfer(id) {
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasTranferencia.php",
    data: { id: id },
    success: function (res) {
      var datos = JSON.parse(res);

      $("#idCuentaActualTransfer").val(datos.idCuentaActualTransfer);
      $("#cmbCuentaDestinoCj").html(datos.cuentasDestinatarios);
      $("#cmbMonedaAc").val(datos.monedaActualText);
      $("#saldoI").val(datos.saldoI);
      $("#nomCuentaT").val(datos.nomCuentaT);
      $("#actual").val(datos.monedaActual);
      $("#destino").val(datos.monedaDestin);
    },
  });
}

function modalAjuste(id) {
  $("#ajuste_Modal").modal("show");
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasTranferencia.php",
    data: { id: id },
    success: function (res) {
      var datos = JSON.parse(res);
      $("#idCuentaActualAjuste").val(datos.idCuentaActualTransfer);
      $("#cmbMonedaAjuste").val(datos.monedaActualText);
    },
  });
}

$("#moneda_diferente").hide();

function getMonedaD(idCuentaActual) {
  var id = $("#cmbCuentaDestinoCj").val();
  var m = document.getElementById("cmbMonedaG");
  m.disabled = true;
  var estado = $("#cmbCuentaDestinoCj").val();
  var mActual = $("#actual").val();

  if (estado == "0") {
    $("#moACuenta").val("");
    $("#invalid-cuentaTrans").css("display", "block");
    $("#cmbCuentaDestinoCj").addClass("is-invalid");
  } else {
    $("#invalid-cuentaTrans").css("display", "none");
    $("#cmbCuentaDestinoCj").removeClass("is-invalid");
    $.ajax({
      type: "POST",
      url: "functions/get_Moneda.php",
      data: {
        idCuentaDestino: id,
        idCuentaActual: idCuentaActual,
        moOrigen: mActual,
      },
      success: function (res) {
        var datos = JSON.parse(res);
        $("#cmbMonedaG").val(datos.monedaDes);
        $("#actual").val(datos.monedaActual);
        $("#destino").val(datos.monedaD);
        $("#saldoI").val(datos.saldoIn);
        $("#saldoDes").val(datos.saldoDest);
        $("#idCuentaDestinoTransfer").val(datos.idDestinoTr);
        $("#idCuentaActualTransfer").val(datos.idActualTr);
        $("#tipoDeCambio").val(datos.valorTipoCambio);

        $("#idDestinoTr").val(datos.idDestinoTr);

        var monAct = $("#actual").val();
        var monDes = $("#destino").val();
        console.log({ monAct });
        console.log({ monDes });

        if (monAct == monDes) {
          $("#moneda_diferente").hide();
          $("#invalid-cuentaTrans").css("display", "none");
          $("#cmbCuentaDestinoCj").removeClass("is-invalid");
        } else {
          $("#moneda_diferente").show();
        }
      },
    });
  }
}

function validarDatosGasto() {
  $(document).ready(function () {
    $("#txtImporteGasto").prop("required", true);
  });
}

function guardarAjuste() {
  var msjbien = "¡Ajuste agregado!";
  var msjerror = "Error al agregar";
  var idCuentaActual = $("#idCuentaActualAjuste").val();
  var cantidadAjuste = $("#txtCantidadAjuste").val();
  var tipoAjuste = $("input[name='ajusteCaja']:checked").val();
  var txtFechaAjuste = $("#txtFechaAjuste").val();
  var observacionesAjsute = $("#areaObservacionesAjuste").val();

  if (!cantidadAjuste) {
    $("#invalid-cantidadAjusCaj").css("display", "block");
    $("#txtCantidadAjuste").addClass("is-invalid");
  }

  if (!tipoAjuste) {
    $("#invalid-tipoAjusCaj").css("display", "block");
  }

  if (!txtFechaAjuste) {
    $("#invalid-fechaAjusCaj").css("display", "block");
    $("#txtFechaAjuste").addClass("is-invalid");
  }

  if (!observacionesAjsute) {
    $("#invalid-obsAjusCaj").css("display", "block");
    $("#areaObservacionesAjuste").addClass("is-invalid");
  }

  var badCantidadAjusCaj =
    $("#invalid-cantidadAjusCaj").css("display") === "block" ? false : true;
  var badTipoAjusCaj =
    $("#invalid-tipoAjusCaj").css("display") === "block" ? false : true;
  var badFechaAjusCaj =
    $("#invalid-fechaAjusCaj").css("display") === "block" ? false : true;
  var badObsAjusCaj =
    $("#invalid-obsAjusCaj").css("display") === "block" ? false : true;

  if (
    badCantidadAjusCaj &&
    badTipoAjusCaj &&
    badFechaAjusCaj &&
    badObsAjusCaj
  ) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_Ajuste.php",
      data: {
        idCuentaActual: idCuentaActual,
        cantidadAjuste: cantidadAjuste,
        tipoAjuste: tipoAjuste,
        txtFechaAjuste: txtFechaAjuste,
        observacionesAjsute: observacionesAjsute,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#ajuste_Modal").modal("toggle");
          $("#ajuste").trigger("reset");
          $("#tblDetalles").DataTable().ajax.reload();
          tablaInicialCaja(idCuentaActual);
          lobiSucces(msjbien);
        } else {
          lobiWarning(msjerror);
        }
      },
    });
  }
}

function guardarTransferencia() {
  var msjerrorSaldo = "Saldo Insificiente para transferir!";
  var msjbien = "Transferencia exitosa";
  var msjerror = "Transferencia no realizada!";

  var idCuentaActual = $("#idCuentaActualTransfer").val();
  var idCuentaDestino = $("#cmbCuentaDestinoCj").val();
  var idDestinoTr = $("#cmbCuentaDestinoCj").val();
  var cantidadEnvio = $("#txtCantidad1").val();
  var fechaTransferencia = $("#txtFechaTransferencia").val();
  var observaciones = $("#areaObservaciones").val();

  var saldoIn = $("#saldoI").val();
  var monActual = $("#actual").val();
  var monDestino = $("#destino").val();
  var tipoCambio = $("#tipoDeCambio").val();
  var nomCuentaT = $("#nomCuentaT").val();

  var s1 = parseFloat($("#saldoI").val());
  var s2 = parseFloat($("#txtCantidad1").val());

  if (Math.fround(s2) > Math.fround(s1)) {
    lobiError(msjerrorSaldo);
    return;
  }
  if (idCuentaDestino < 1 || !idCuentaDestino) {
    $("#invalid-cuentaTrans").css("display", "block");
    $("#cmbCuentaDestinoCj").addClass("is-invalid");
  }
  if (!cantidadEnvio) {
    $("#invalid-cantidadTrans").css("display", "block");
    $("#txtCantidad1").addClass("is-invalid");
  }
  if (!fechaTransferencia) {
    $("#invalid-fechaTrans").css("display", "block");
    $("#txtFechaTransferencia").addClass("is-invalid");
  }

  var badCuentaTrans =
    $("#invalid-cuentaTrans").css("display") === "block" ? false : true;
  var badCantidadTrans =
    $("#invalid-cantidadTrans").css("display") === "block" ? false : true;
  var badFechaTrans =
    $("#invalid-fechaTrans").css("display") === "block" ? false : true;

  if (badCuentaTrans && badCantidadTrans && badFechaTrans) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_Transferencia.php",
      data: {
        idCuentaActual: idCuentaActual,
        idCuentaDestino: idCuentaDestino,
        monedaOrigen: monActual,
        monedaDestino: monDestino,
        cantidad: cantidadEnvio,
        tipoCambio: 0,
        fechaTransferncia: fechaTransferencia,
        observaciones: observaciones,
        saldoCuentaActual: saldoIn,
        nomCuentaT: nomCuentaT,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#transferencia_Modal").modal("toggle");
          $("#transferencia").trigger("reset");
          $("#tblDetalles").DataTable().ajax.reload();
          tablaInicialCaja(idCuentaActual);
          lobiSucces(msjbien);
        } else {
          lobiWarning(msjerror);
        }
      },
    });
  }
}

function eliminarMovimiento(idMov, idCuenta) {
  var msjbien = "Movimiento eliminado";
  var msjwarning = "No eliminado";
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
      title: "¿Desea continuar?",
      text: "Este movimiento será eliminado junto con otros movimientos asociados",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter">Eliminar Movimiento</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "POST",
          url: "functions/eliminar_Movimiento.php",
          data: {
            idMovimiento: idMov,
            idCuenta: idCuenta,
          },
          success: function (resp) {
            if (resp == "1") {
              var idc = $("#pkCuenta").val();

              $("#tblDetalles").DataTable().ajax.reload();
              tablaInicialCaja(idc);
              lobiError(msjbien);
            }
          },
          error: function (error) {
            lobiWarning(msjwarning);
          },
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

function subirReferencia(idMov, idCuenta) {
  var msjsucces = "Archivo agregado";
  var msjwarning = "No se pudo agregar";
  var sinarchivo = "No hay archivo";

  var nombreArchivo = document.getElementById("file-input").files[0].name;
  var hayArchivo = 0;

  // archivo
  var file = $("#namefile").val();
  var miArchivo = $("#file-input").prop("files")[0];
  var fd = new FormData();
  //Datos para el movimiento

  if (!nombreArchivo == "") {
    var files = [];
    files = $("#file-input")[0].files[0];
    var nombrearchivo = files.name;

    var extension = nombrearchivo
      .substr(nombrearchivo.lastIndexOf(".") + 1)
      .toLowerCase();
    hayArchivo = 1;
    fd.append("file-input", miArchivo);
    fd.append("idMovimiento", idMov);
    fd.append("idCuenta", idCuenta);
    fd.append("hayArchivo", hayArchivo);
    //
    $.ajax({
      type: "POST",
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      url: "functions/subir_Archivo.php",
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#tblDetalles").DataTable().ajax.reload();
          //location.reload();
          lobiSucces(msjsucces);
        } else {
          lobiWarning(msjwarning);
        }
      },
    });
  } else {
    lobiWarning(sinarchivo);
  }
}

function obtenerIdCuentaEditar(id, tipo) {
  var id = "id=" + id;
  if (tipo == 1) {
    $("#chequesU").show();
    $("#creditoU").hide();
    $("#otrasU").hide();
    $("#cajaU").hide();
  } else {
    if (tipo == 2) {
      $("#creditoU").show();
      $("#chequesU").hide();
      $("#otrasU").hide();
      $("#cajaU").hide();
    } else {
      if (tipo == 3) {
        $("#otrasU").show();
        $("#creditoU").hide();
        $("#chequesU").hide();
        $("#cajaU").hide();
      } else if (tipo == 4) {
        $("#cajaU").show();
        $("#otrasU").hide();
        $("#creditoU").hide();
        $("#chequesU").hide();
      } else {
        $("#cajaU").hide();
        $("#otrasU").hide();
        $("#creditoU").hide();
        $("#chequesU").hide();
      }
    }
  }
  $.ajax({
    type: "POST",
    url: "functions/getCuenta.php",
    data: id,
    success: function (r) {
      var datos = JSON.parse(r);
      $("#idCuentaUChe").val(datos.pkcuenta);
      $("#idCuentaU").val(datos.pkcuenta);
      $("#txtNombreCuentaU").val(datos.nombre);
      $("#txtTipoCuentaU").val(datos.nombreTipoCuenta);
      $("#tipoIdCuentaUChe").val(datos.tipoIdCuenta);
      $("#tipoIdCuentaU").val(datos.tipoIdCuenta);
      $("#txtNoCuentaU").val(datos.noCuentaCheques);
      $("#cmbEmpresaUCheques").html(datos.empresasCh);
      $("#cmbBancoU").html(datos.bancos);
      $("#txtClabeUp").val(datos.clabe);
      $("#txtSaldoInicialU").val(datos.saldoCheques);
      //DATOS DE CUENTA CREDITO
      $("#cmbEmpresaUCredito").html(datos.empresas);
      $("#cmbBancoUCredito").html(datos.bancosC);
      $("#txtNoCreditoU").val(datos.noCredito);
      $("#txtReferenciaU").val(datos.referencia);
      $("#txtLimiteCreditoU").val(datos.limiteCredito);
      $("#cmbEmpresaUCredito").html(datos.empresasCr);
      $("#txtCreditoUtilizadoU").val(datos.creditoUtilizado);
      //DATOS DE CUENTA OTROS
      $("#txtIdentificadorU").val(datos.idCuenta);
      $("#cmbEmpresaUOtras").html(datos.empresasOtras);
      $("#txtDescripcionU").val(datos.descripcion);
      $("#txtSaldoInicialUOtras").val(datos.saldoInicial);
      //CAJA CHICA
      $("#cmbResponsableU").html(datos.respuesta);
      $("#txtDescripcionUCaja").val(datos.descrip);
      $("#txtSaldoInicialUCaja").val(datos.saldoIniciailC);
      $("idCuentaCaja").val(datos.idCuentaCaja);
    },
  });
}

function eliminarCuenta() {
  var msjbien = "¡Registro eliminado!";
  var msjwarning = "Ocurrió un error al eliminar";
  var id = $("#idCuentaU").val();
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
      title: "¿Desea eliminar el registro de esta cuenta?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: 'Eliminar Cuenta',
      cancelButtonText: 'Cancelar',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "functions/eliminar_Cuenta.php",
          type: "POST",
          data: {
            idCuentaU: id,
          },
          success: function (data, status, xhr) {
            if (data == "exito") {
              $("#editar_Cuenta").modal("toggle");
              $("#tblCuentasBancarias").DataTable().ajax.reload();
              lobiError(msjbien);
            } else {
              lobiWarning(msjwarning);
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
