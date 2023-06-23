function tablaInicialOtras(idCuenta) {
  $.ajax({
    type: "POST",
    url: "functions/tabla_Otras.php",
    data: { idDetalle: idCuenta },
    success: function (r) {
      var datos = JSON.parse(r);

      $("#saldoG").text(datos.saldoG);
      $("#tipoCuentaG").text(datos.tipoCuenta);
      $("#nomCuentaG").text(datos.nomCuenta);
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
  $("#tblDetalles").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
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
        funcion: "get_otrerTableMovements",
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
  CargarSlimSelect();
});

function retiro_Gasto(idAc) {
  $.ajax({
    type: "POST",
    url: "functions/get_CombosOtros.php",
    data: { id: idAc },
    success: function (res) {
      var datos = JSON.parse(res);
      //console.log(datos.pkcategoria);
      $("#idCuentaCaja").val(datos.idCajaActual);
      $("#saldoCuentaCaja").val(datos.saldoInicialO);
    },
  });
  cargarCMBCategoriasG("", "cmbCategoria");
}

function CargarSlimSelect() {
  new SlimSelect({
    select: "#cmbCategoria",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "Seleccionar categoría...",
  });
  new SlimSelect({
    select: "#cmbSubcategoria",
    deselectLabel: '<span class="">✖</span>',
    placeholder: "",
  });
}

function cargarCMBCategoriasG(data, input) {
  var idemp = $("#emp_id").val();
  var html = "";
  var selected;

  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_categorias_gasto",
      data: idemp,
    },
    dataType: "json",
    success: function (respuesta) {
      html += "<option data-placeholder='true'></option>";

      $.each(respuesta, function (i) {
        /* if (data === respuesta[i].PKCategoria) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKCategoria +
          '" ' +
          //selected +
          ">" +
          respuesta[i].Nombre +
          "</option>";
      });
      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guarda_MovimientoO() {
  var idCuentaCaja = $("#idCuentaCaja").val();
  var hayArchivo = 0;
  var file = $("#inputFile").val();
  var responsable = $("#cmbResponsableGasto").val();
  var importe = $("#txtImporteGasto").val();
  var fechaGasto = $("#txtFechaGasto").val();
  var observaciones = $("#areaDescripcionGasto").val();
  var proveedor = $("#cmbProvedoresGasto").val();
  var categoria = $("#cmbCategoria").val();
  var subcategoria = $("#cmbSubcategoria").val();
  var miArchivo = $("#inputFile").prop("files")[0];
  console.log(categoria);
  console.log(subcategoria);
  var s1 = parseFloat($("#saldoCuentaCaja").val());
  var s2 = parseFloat($("#txtImporteGasto").val());
  if (Math.fround(s2) > Math.fround(s1)) {
    bandera = 0;
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      img: null,
      msg: "Saldo Insuficiente!",
    });
    return;
  }

  if (!responsable) {
    $("#invalid-responsableRet").css("display", "block");
    $("#cmbResponsableGasto").addClass("is-invalid");
  }
  if (!importe) {
    $("#invalid-importeRet").css("display", "block");
    $("#txtImporteGasto").addClass("is-invalid");
  }
  if (!fechaGasto) {
    $("#invalid-fechaRet").css("display", "block");
    $("#txtFechaGasto").addClass("is-invalid");
  }
  if (!proveedor) {
    $("#invalid-provRet").css("display", "block");
    $("#cmbProvedoresGasto").addClass("is-invalid");
  }
  if (!observaciones) {
    $("#invalid-observRet").css("display", "block");
    $("#areaDescripcionGasto").addClass("is-invalid");
  }

  if ($("#checkCaja").is(":checked")) {
    check = 0;
    hayArchivo = 0;
  } else {
    if (!file) {
      $("#invalid-archivoRet").css("display", "block");
      $("#inputFile").addClass("is-invalid");
    } else {
      hayArchivo = 1;
      check = 1;
    }
  }

  var badResponsableRet =
    $("#invalid-responsableRet").css("display") === "block" ? false : true;
  var importeRet =
    $("#invalid-importeRet").css("display") === "block" ? false : true;
  var badFechaRet =
    $("#invalid-fechaRet").css("display") === "block" ? false : true;
  var badProvRet =
    $("#invalid-provRet").css("display") === "block" ? false : true;
  var badObsRet =
    $("#invalid-observRet").css("display") === "block" ? false : true;
  var badArchivoRet =
    $("#invalid-archivoRet").css("display") === "block" ? false : true;

  if (
    badResponsableRet &&
    importeRet &&
    badFechaRet &&
    badProvRet &&
    badObsRet &&
    badArchivoRet
  ) {
    var fd = new FormData();
    fd.append("inputFile", miArchivo);
    fd.append("idCuentaCaja", idCuentaCaja);
    fd.append("cmbResponsableGasto", responsable);
    fd.append("txtImporteGasto", importe);
    fd.append("txtFechaGasto", fechaGasto);
    fd.append("cmbProvedoresGasto", proveedor);
    fd.append("areaDescripcionGasto", observaciones);
    fd.append("cmbCategoria", categoria);
    fd.append("cmbSubcategoria", subcategoria);
    fd.append("comprobado", check);
    fd.append("hayArchivo", hayArchivo);

    $.ajax({
      type: "POST",
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      url: "functions/agregar_MovimientoOtras.php",
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          var idCuentaCaja = $("#idCuentaCaja").val();
          tablaInicialOtras(idCuentaCaja);
          $("#retiro_Gasto").modal("toggle");
          $("#retiroGasto").trigger("reset");
          $("#tblDetalles").DataTable().ajax.reload();
          $("#checkCaja").prop("disabled", false);
          $("#checkCaja").prop("checked", true);
          $("#inputFile").css("display", "none");
          $("#inputFile").val("");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Gasto Registrado!",
          });
        } else if (data.trim() == "incorrecto") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            img: null,
            msg: "Importe del gasto Incorrecto!",
          });
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            img: null,
            msg: "Ocurrió un error al agregar el Gasto",
          });
        }
      },
    });
  }
}

function abrirModalTransferO(id) {
  $("#transferencia_Modal").modal("show");
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasTransferenciaO.php",
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

function getMonedaDes(idCuentaActual) {
  var id = $("#cmbCuentaDestinoCj").val();
  var m = document.getElementById("cmbMonedaG");
  m.disabled = true;
  var estado = $("#cmbCuentaDestinoCj").val();
  var mActual = $("#actual").val();
  if (estado == 0) {
    $("#moACuenta").val("");
    $("#invalid-cuentaTrans").css("display", "block");
    $("#cmbCuentaDestinoCj").addClass("is-invalid");
  } else {
    $("#invalid-cuentaTrans").css("display", "none");
    $("#cmbCuentaDestinoCj").removeClass("is-invalid");
    $.ajax({
      type: "POST",
      url: "functions/get_MonedaO.php",
      data: {
        idCuentaDestino: id,
        idCuentaActual: idCuentaActual,
        moOrigen: mActual,
      },
      success: function (res) {
        var datos = JSON.parse(res);

        $("#saldoDes").val(datos.saldoDest);
        $("#destino").val(datos.monedaD);
        $("#cmbMonedaG").val(datos.monedaDes);
        $("#actual").val(datos.monedaActual);
        $("#saldoI").val(datos.saldoIn);

        $("#idCuentaDestinoTransfer").val(datos.idDestinoTr);
        $("#idCuentaActualTransfer").val(datos.idActualTr);
        $("#tipoDeCambio").val(datos.valorTipoCambio);
        //$("#moneda_act").val(datos.claveMoneda);

        var monAct = $("#actual").val();
        var monDes = $("#destino").val();

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

function guardarTransferenciaO() {
  var idCuentaActual = $("#idCuentaActualTransfer").val();
  var idCuentaDestino = $("#cmbCuentaDestinoCj").val();
  var saldoIn = $("#saldoI").val();
  var monActual = $("#actual").val();
  var monDestino = $("#destino").val();
  var cantidadEnvio = $("#txtCantidad1").val();
  var tipoCambio = $("#tipoDeCambio").val();
  var observaciones = $("#areaObservaciones").val();
  var fechaTransferencia = $("#txtFechaTransferencia").val();
  var nomCuentaT = $("#nomCuentaT").val();

  //SI NO ESTA SELECCIONADA UNA CUANTA BANCARIA BORRA TODO
  var s1 = parseFloat($("#saldoI").val());
  var s2 = parseFloat($("#txtCantidad1").val());

  if (Math.fround(s2) > Math.fround(s1)) {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 4000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      img: null,
      msg: "¡Saldo Insificiente para transferir!",
    });
    return;
  }
  if (idCuentaDestino < 1 || !idCuentaDestino) {
    $("#cmbCuentaDestinoCj")[0].reportValidity();
    $("#cmbCuentaDestinoCj")[0].setCustomValidity("Ingresa la cuenta destino.");
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
      url: "functions/agregar_TransferenciaOtras.php",
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
        console.log(data);
        if (data.trim() == "exito") {
          $("#transferencia_Modal").modal("toggle");
          $("#transferencia").trigger("reset");
          $("#tblDetalles").DataTable().ajax.reload();
          tablaInicialOtras(idCuentaActual);

          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Transferencia exitosa!",
          });
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            img: null,
            msg: "Transferencia no realizada!",
          });
        }
      },
    });
  }
}

function modalAjusteOtros(id) {
  $("#ajuste_Modal").modal("show");
  $.ajax({
    type: "POST",
    url: "functions/get_CuentasTransferenciaO.php",
    data: { id: id },
    success: function (res) {
      var datos = JSON.parse(res);
      //console.log(datos.cuentasDestinatarios);
      $("#idCuentaActualAjuste").val(datos.idCuentaActualTransfer);
      $("#cmbMonedaAjuste").val(datos.monedaActualText);
    },
  });
}

function guardarAjusteOtros() {
  var idCuentaActual = $("#idCuentaActualAjuste").val();
  var cantidadAjuste = $("#txtCantidadAjuste").val();
  var tipoAjuste = $("input[name='ajusteOtr']:checked").val();
  var txtFechaAjuste = $("#txtFechaAjuste").val();
  var observacionesAjsute = $("#areaObservacionesAjuste").val();
  console.log(tipoAjuste);

  if (!cantidadAjuste) {
    $("#invalid-cantidadAjusOtr").css("display", "block");
    $("#txtCantidadAjuste").addClass("is-invalid");
  }

  if (!tipoAjuste) {
    $("#invalid-tipoAjusOtr").css("display", "block");
  }

  if (!txtFechaAjuste) {
    $("#invalid-fechaAjusOtr").css("display", "block");
    $("#txtFechaAjuste").addClass("is-invalid");
  }

  if (!observacionesAjsute) {
    $("#invalid-obsAjusOtr").css("display", "block");
    $("#areaObservacionesAjuste").addClass("is-invalid");
  }

  var badCantidadAjusOtr =
    $("#invalid-cantidadAjusOtr").css("display") === "block" ? false : true;
  var badTipoAjusOtr =
    $("#invalid-tipoAjusOtr").css("display") === "block" ? false : true;
  var badFechaAjusOtr =
    $("#invalid-fechaAjusOtr").css("display") === "block" ? false : true;
  var badObsAjusOtr =
    $("#invalid-obsAjusOtr").css("display") === "block" ? false : true;

  if (
    badCantidadAjusOtr &&
    badTipoAjusOtr &&
    badFechaAjusOtr &&
    badObsAjusOtr
  ) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_AjusteOtros.php",
      data: {
        idCuentaActual: idCuentaActual,
        cantidadAjuste: cantidadAjuste,
        tipoAjuste: tipoAjuste,
        txtFechaAjuste: txtFechaAjuste,
        observacionesAjsute: observacionesAjsute,
      },
      success: function (data, status, xhr) {
        console.log(data);
        if (data.trim() == "exito") {
          $("#ajuste_Modal").modal("toggle");
          $("#ajuste").trigger("reset");
          $("#tblDetalles").DataTable().ajax.reload();
          tablaInicialOtras(idCuentaActual);

          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Ajuste exitoso!",
          });
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            img: null,
            msg: "Ajuste no realizado!",
          });
        }
      },
    });
  }
}

function eliminarMovimientoOtras(idMov, idCuenta) {
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
      text: "Este moviiento será eliminado junto con otros movimientos asociados",
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
          url: "functions/eliminar_MovimientoOtras.php",
          data: {
            idMovimiento: idMov,
            idCuenta: idCuenta,
          },
          success: function (resp) {
            if (resp == "1") {
              var idc = $("#pkCuenta").val();

              $("#tblDetalles").DataTable().ajax.reload();
              tablaInicialOtras(idc);

              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                img: "../../img/chat/notificacion_error.svg",
                msg: "Movimiento eliminado",
              });
            }
          },
          error: function (error) {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              img: null,
              msg: "No eliminado!",
            });
          },
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

function agregar_InyeccionCapitalOtras() {
  if ($("#inyeccionCapital")[0].checkValidity()) {
    var badMonto =
      $("#invalid-montoOtras").css("display") === "block" ? false : true;
    var badFecha =
      $("#invalid-fechaOtras").css("display") === "block" ? false : true;
    var badObservacion =
      $("#invalid-obsOtras").css("display") === "block" ? false : true;
    var badArchivo =
      $("#invalid-archivoOtras").css("display") === "block" ? false : true;

    if (badMonto && badFecha && badObservacion && badArchivo) {
      var idCuentaActual = $("#idCuentaIny").val();
      var saldoCuantaActual = $("#saldoIny").val();
      var monto = $("#montoInyeccionCapital").val();
      var fecha = $("#fechaInyeccionCapital").val();
      var observaciones = $("#areaObservacion").val();

      var hayArchivo = 0;
      // archivo
      var file = $("#inputFileInyeccion").val();
      var miArchivo = $("#inputFileInyeccion").prop("files")[0];
      var fd = new FormData();
      //Datos para el movimiento
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
      if (monto == "") {
        $("#montoInyeccionCapital")[0].reportValidity();
        $("#montoInyeccionCapital")[0].setCustomValidity("Ingresa el monto.");
      } else {
        $.ajax({
          type: "POST",
          cache: false,
          contentType: false,
          processData: false,
          data: fd,
          url: "functions/agregar_InyeccionCapitalOtras.php",
          success: function (data, status, xhr) {
            console.log(data);
            if (data.trim() == "exito") {
              $("#inyeccion_Capital").modal("toggle");
              $("#inyeccionCapital").trigger("reset");
              $("#tblDetalles").DataTable().ajax.reload();
              tablaInicialOtras(idCuentaActual);
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                //img: '<i class="fas fa-check-circle"></i>',
                img: "../../img/timdesk/checkmark.svg",
                msg: "¡Se agrego dinero exitosamente!",
              });
            } else {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/warning_circle.svg",
                img: null,
                msg: "No se pudo agregar!",
              });
            }
          },
        });
      }
    }
  } else {
    if (!$("#montoInyeccionCapital").val()) {
      $("#invalid-montoOtras").css("display", "block");
      $("#montoInyeccionCapital").addClass("is-invalid");
    }
    if (!$("#fechaInyeccionCapital").val()) {
      $("#invalid-fechaOtras").css("display", "block");
      $("#fechaInyeccionCapital").addClass("is-invalid");
    }
    if (!$("#areaObservacion").val()) {
      $("#invalid-obsOtras").css("display", "block");
      $("#areaObservacion").addClass("is-invalid");
    }
    if (!$("#checkCajaInyeccion").prop("checked")) {
      if (!$("#inputFileInyeccion").val()) {
        $("#invalid-archivoOtras").css("display", "block");
        $("#inputFileInyeccion").addClass("is-invalid");
      }
    }
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

function cargarCMBProveedor(input) {
  var html = "";
  var selected;
  $.ajax({
    url: "functions/get_proveedor.php",
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta proveedor: ", respuesta);
      html = "<option disabled selected>Selecciona un proveedor</option>";
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKProveedor +
          '">' +
          respuesta[i].Razon_Social +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Guardar Categoria
function guardarCategoria() {
  var nombreCat = $("#txtCategoria").val();

  if (!nombreCat) {
    $("#invalid-categoria").css("display", "block");
    $("#txtCategoria").addClass("is-invalid");
  }

  var badNombreCat =
    $("#invalid-categoria").css("display") === "block" ? false : true;

  if (badNombreCat) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_Categoria.php",
      data: {
        nombreCat: nombreCat,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#txtCategoria").val("");
          $("#nueva_categoria").modal("toggle");
          cargarCMBCategoriasG("", "cmbCategoria");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Categoria agregada exitosa!",
          });
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            img: null,
            msg: "¡Algo salio mal!",
          });
        }
      },
    });
  }
}

function guardarSubCategoria() {
  var categoriaId = $("#cmCategoria").val();
  var nombreSubCat = $("#txtSubCategoria").val();

  if (!categoriaId) {
    $("#invalid-categoriaSub").css("display", "block");
    $("#cmCategoria").addClass("is-invalid");
  }
  if (!nombreSubCat) {
    $("#invalid-subcategoria").css("display", "block");
    $("#txtSubCategoria").addClass("is-invalid");
  }

  var badCategoria =
    $("#invalid-categoriaSub").css("display") === "block" ? false : true;
  var badNombreSubCat =
    $("#invalid-subcategoria").css("display") === "block" ? false : true;

  if (badCategoria && badNombreSubCat) {
    $.ajax({
      type: "POST",
      url: "functions/agregar_SubCategoria.php",
      data: {
        categoriaId: categoriaId,
        nombreSubCat: nombreSubCat,
      },
      success: function (data, status, xhr) {
        if (data.trim() == "exito") {
          $("#txtSubCategoria").val("");
          $("#nueva_subCategoria").modal("toggle");
          cargarCMBCategoriasG("", "cmbCategoria");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Subcategoria agregada exitosa!",
          });
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            img: null,
            msg: "¡Algo salio mal!",
          });
        }
      },
    });
  }
}

function activarDesactivarCred(item) {
  var dias = document.getElementById("txtDiasCredito");
  var cred = document.getElementById("txtLimiteCredito");
  dias.classList.remove("is-invalid");
  cred.classList.remove("is-invalid");
  document.getElementById("invalid-diasProv").style.display = "none";
  document.getElementById("invalid-credProv").style.display = "none";
  if (item.checked) {
    dias.disabled = false;
    cred.disabled = false;
    return;
  }
  document.getElementById("txtDiasCredito").disabled = true;
  document.getElementById("txtLimiteCredito").disabled = true;
  return;
}
