let sinExistencia = false;

$(document).ready(function () {
  if (idPedido == "0") {
    //loadTypeExits("", "cmbTipoSalida");
    loadBranchOrigin("", "cmbSucursalOrigen");
    $(".data-container .branchOrigin-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    $("#div2").html(`<div class="form-group">
                      <div class="row">
                        <div class="col-lg-4">
                          <div class="brancheDestination-disabled">
                            <label for="cmbSucursalDestino">Sucursal de destino*:</label>
                              <div class="input-group">
                                <select name="cmbSucursalDestino" id="cmbSucursalDestino" required></select>
                              <div class="invalid-feedback" id="invalid-sucursalDestino">La salida debe de tener una sucursal de destino.</div>
                            </div>
                          </div>
                          <div class="branchOrSale-disabled">
                            <label for="cmbSucursalOrVenta">Destino*:</label>
                              <div class="input-group">
                                <select name="cmbSucursalOrVenta" id="cmbSucursalOrVenta" required></select>
                              <div class="invalid-feedback" id="invalid-sucursalDestino">La salida debe de tener una sucursal de destino.</div>
                            </div>
                          </div>
                          <div class="customer-disabled">
                            <label for="cmbClienteCotizacion">Cliente*:</label>
                              <div class="input-group">
                                <input class="form-control" type="text" name="cmbClienteCotizacion" id="cmbClienteCotizacion" readonly></select>
                              <div class="invalid-feedback" id="invalid-clienteCotizacion">La salida debe de tener un cliente.</div>
                            </div>
                          </div>
                          <div class="customerSales-disabled">
                            <label for="cmbClienteVenta">Cliente*:</label>
                              <div class="input-group">
                                <input class="form-control" type="text" name="cmbClienteVenta" id="cmbClienteVenta" readonly></select>
                              <div class="invalid-feedback" id="invalid-clienteVenta">La salida debe de tener un cliente.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="brancheDestination-disabled">
                            <label for="cmbSurtidorSalida">Surtidor*:</label>
                              <div class="input-group">
                                <select name="cmbSurtidorSalida" id="cmbSurtidorSalida" required></select>
                              <div class="invalid-feedback" id="invalid-surtidorSalida">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                          <div class="branchOrSale-disabled">
                            <label for="cmbSurtidorSalidaGral">Surtidor*:</label>
                              <div class="input-group">
                                <select name="cmbSurtidorSalidaGral" id="cmbSurtidorSalidaGral" required></select>
                              <div class="invalid-feedback" id="invalid-surtidorSalida">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                          <div class="customer-disabled">
                            <label for="cmbSurtidorSalidaCoti">Surtidor*:</label>
                              <div class="input-group">
                                <select class="form-control" name="cmbSurtidorSalidaCoti" id="cmbSurtidorSalidaCoti"></select>
                              <div class="invalid-feedback" id="invalid-surtidorSalidaCoti">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                          <div class="customerSales-disabled">
                            <label for="cmbSurtidorSalidaVenta">Surtidor*:</label>
                              <div class="input-group">
                                <select class="form-control" name="cmbSurtidorSalidaVenta" id="cmbSurtidorSalidaVenta"></select>
                              <div class="invalid-feedback" id="invalid-surtidorVenta">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="brancheDestination-disabled">
                            <label for="usr">No. Bultos / Paquetes / Notas:</label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetes" id="txtNoBultosPaquetes" placeholder="50 Bultos" style="float:left;">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetes">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                          <div class="branchOrSale-disabled">
                            <label for="usr">No. Bultos / Paquetes / Notas:</label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesGral" id="txtNoBultosPaquetesGral" placeholder="50 Bultos" style="float:left;">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetes">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                          <div class="customer-disabled">
                            <label for="usr">No. Bultos / Paquetes / Notas:</label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesCoti" id="txtNoBultosPaquetesCoti" placeholder="50 Bultos" style="float:left;">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetesCoti">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                          <div class="customerSales-disabled">
                            <label for="usr">No. Bultos / Paquetes / Notas:</label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesVenta" id="txtNoBultosPaquetesVenta" placeholder="50 Bultos" style="float:left;">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetesVenta">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>`);

    $("#divCodigoBarras").html(`<div class = "brancheBarCode-disabled" style="display:none; visibility: "hidden"; opacity: 0;">
                                  <label id="lblBarCode" for="BarCode" style="display:none; visibility: "hidden"; opacity: 0;">Escanear código de barras:</label>
                                  <input class="form-control alphaNumeric-only" type="text" name="BarCode" id="BarCode" style="display:none; visibility: "hidden"; opacity: 0;float:left;">
                                </div>`);
    new SlimSelect({
      select: "#cmbSucursalOrigen",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbOrderPedido",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbOrderPedidoGral",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbSucursalDestino",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbSucursalOrVenta",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbSurtidorSalida",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbSurtidorSalidaGral",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbTypeOrderPedido",
      deselectLabel: '<span class="">✖</span>',
    });
    loadProductosOrderPedido(0);
  } else {
    //console.log({ idPedido });
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_Data_Tipo_SalidaPedido",
        data: idPedido,
      },
      dataType: "json",
      success: function (respuesta) {
        //console.log({ respuesta });
        if (
          respuesta[0].tipoSalida == "1" ||
          respuesta[0].tipoSalida == "2" ||
          respuesta[0].tipoSalida == "3" ||
          respuesta[0].tipoSalida == "4"
        ) {
          loadTypeExits(1, "cmbTipoSalida");

          loadBranchOrigin(respuesta[0].sucursalOrigen, "cmbSucursalOrigen");
          $(".data-container .branchOrigin-disabled").css({
            display: "inline-block",
            visibility: "visible",
            opacity: "1",
            animation: "fade 1s",
          });

          $("#div2").html(`<div class="form-group">
                            <div class="row">
                              <div class="col-lg-4">
                                <div class="brancheDestination-disabled">
                                  <label for="cmbSucursalDestino">Sucursal de destino*:</label>
                                    <div class="input-group">
                                      <select name="cmbSucursalDestino" id="cmbSucursalDestino" required></select>
                                    <div class="invalid-feedback" id="invalid-sucursalDestino">La salida debe de tener una sucursal de destino.</div>
                                  </div>
                                </div>
                                <div class="branchOrSale-disabled">
                                  <label for="cmbSucursalOrVenta">Destino*:</label>
                                    <div class="input-group">
                                      <select name="cmbSucursalOrVenta" id="cmbSucursalOrVenta" required></select>
                                    <div class="invalid-feedback" id="invalid-sucursalDestino">La salida debe de tener una sucursal de destino.</div>
                                  </div>
                                </div>
                                <div class="customer-disabled">
                                  <label for="cmbClienteCotizacion">Cliente*:</label>
                                    <div class="input-group">
                                      <input class="form-control" type="text" name="cmbClienteCotizacion" id="cmbClienteCotizacion" readonly></select>
                                    <div class="invalid-feedback" id="invalid-clienteCotizacion">La salida debe de tener un cliente.</div>
                                  </div>
                                </div>
                                <div class="customerSales-disabled">
                                  <label for="cmbClienteVenta">Cliente*:</label>
                                    <div class="input-group">
                                      <input class="form-control" type="text" name="cmbClienteVenta" id="cmbClienteVenta" readonly></select>
                                    <div class="invalid-feedback" id="invalid-clienteVenta">La salida debe de tener un cliente.</div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="brancheDestination-disabled">
                                  <label for="cmbSurtidorSalida">Surtidor*:</label>
                                    <div class="input-group">
                                      <select name="cmbSurtidorSalida" id="cmbSurtidorSalida" required></select>
                                    <div class="invalid-feedback" id="invalid-surtidorSalida">La salida debe de tener un surtidor.</div>
                                  </div>
                                </div>
                                <div class="branchOrSale-disabled">
                                  <label for="cmbSurtidorSalidaGral">Surtidor*:</label>
                                    <div class="input-group">
                                      <select name="cmbSurtidorSalidaGral" id="cmbSurtidorSalidaGral" required></select>
                                    <div class="invalid-feedback" id="invalid-surtidorSalida">La salida debe de tener un surtidor.</div>
                                  </div>
                                </div>
                                <div class="customer-disabled">
                                  <label for="cmbSurtidorSalidaCoti">Surtidor*:</label>
                                    <div class="input-group">
                                      <select class="form-control" name="cmbSurtidorSalidaCoti" id="cmbSurtidorSalidaCoti"></select>
                                    <div class="invalid-feedback" id="invalid-surtidorSalidaCoti">La salida debe de tener un surtidor.</div>
                                  </div>
                                </div>
                                <div class="customerSales-disabled">
                                  <label for="cmbSurtidorSalidaVenta">Surtidor*:</label>
                                    <div class="input-group">
                                      <select class="form-control" name="cmbSurtidorSalidaVenta" id="cmbSurtidorSalidaVenta"></select>
                                    <div class="invalid-feedback" id="invalid-surtidorVenta">La salida debe de tener un surtidor.</div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="brancheDestination-disabled">
                                  <label for="usr">No. Bultos / Paquetes / Notas:</label>
                                    <div class="input-group">
                                      <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetes" id="txtNoBultosPaquetes" placeholder="50 Bultos" style="float:left;">
                                    <div class="invalid-feedback" id="invalid-noBultosPaquetes">La salida debe de tener un número de bultos o paquetes.</div>
                                  </div>
                                </div>
                                <div class="branchOrSale-disabled">
                                  <label for="usr">No. Bultos / Paquetes / Notas:</label>
                                    <div class="input-group">
                                      <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesGral" id="txtNoBultosPaquetesGral" placeholder="50 Bultos" style="float:left;">
                                    <div class="invalid-feedback" id="invalid-noBultosPaquetes">La salida debe de tener un número de bultos o paquetes.</div>
                                  </div>
                                </div>
                                <div class="customer-disabled">
                                  <label for="usr">No. Bultos / Paquetes / Notas:</label>
                                    <div class="input-group">
                                      <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesCoti" id="txtNoBultosPaquetesCoti" placeholder="50 Bultos" style="float:left;">
                                    <div class="invalid-feedback" id="invalid-noBultosPaquetesCoti">La salida debe de tener un número de bultos o paquetes.</div>
                                  </div>
                                </div>
                                <div class="customerSales-disabled">
                                  <label for="usr">No. Bultos / Paquetes / Notas:</label>
                                    <div class="input-group">
                                      <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesVenta" id="txtNoBultosPaquetesVenta" placeholder="50 Bultos" style="float:left;">
                                    <div class="invalid-feedback" id="invalid-noBultosPaquetesVenta">La salida debe de tener un número de bultos o paquetes.</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>`);

          $("#divCodigoBarras").html(`<div class = "brancheBarCode-disabled" style="display:none; visibility: "hidden"; opacity: 0;">
                                        <label for="BarCode">Escanear código de barras:</label>
                                        <input class="form-control alphaNumeric-only" type="text" name="BarCode" id="BarCode" style="float:left">
                                      </div>`);

          new SlimSelect({
            select: "#cmbSucursalOrigen",
            deselectLabel: '<span class="">✖</span>',
          });

          new SlimSelect({
            select: "#cmbOrderPedido",
            deselectLabel: '<span class="">✖</span>',
          });

          new SlimSelect({
            select: "#cmbOrderPedidoGral",
            deselectLabel: '<span class="">✖</span>',
          });

          new SlimSelect({
            select: "#cmbSucursalDestino",
            deselectLabel: '<span class="">✖</span>',
          });

          new SlimSelect({
            select: "#cmbSucursalOrVenta",
            deselectLabel: '<span class="">✖</span>',
          });

          new SlimSelect({
            select: "#cmbSurtidorSalida",
            deselectLabel: '<span class="">✖</span>',
          });

          new SlimSelect({
            select: "#cmbSurtidorSalidaGral",
            deselectLabel: '<span class="">✖</span>',
          });

          new SlimSelect({
            select: "#cmbTypeOrderPedido",
            deselectLabel: '<span class="">✖</span>',
          });

          $(".data-container .typeOrderPedido-disabled").css({
            display: "inline-block",
            visibility: "visible",
            opacity: "1",
            animation: "fade 1s",
          });
          if (respuesta[0].tipoSalida == "1") {
            loadTypeOrderPedido(3, "cmbTypeOrderPedido");
            $(".data-container .quote-disabled").css({
              display: "inline-block",
              visibility: "visible",
              opacity: "1",
              animation: "fade 1s",
            });
            loadQuote(
              respuesta[0].id,
              respuesta[0].sucursalOrigen,
              "cmbQuote",
              true
            );
            $(".data-container .customer-disabled").css({
              display: "inline-block",
              visibility: "visible",
              opacity: "1",
              animation: "fade 1s",
            });
          } else if (respuesta[0].tipoSalida == "2") {
            loadTypeOrderPedido(4, "cmbTypeOrderPedido");
            $(".data-container .sales-disabled").css({
              display: "inline-block",
              visibility: "visible",
              opacity: "1",
              animation: "fade 1s",
            });
            loadSales(
              respuesta[0].id,
              respuesta[0].sucursalOrigen,
              "cmbSales",
              true
            );
            $(".data-container .customerSales-disabled").css({
              display: "inline-block",
              visibility: "visible",
              opacity: "1",
              animation: "fade 1s",
            });
          } else if (respuesta[0].tipoSalida == "3") {
            loadTypeOrderPedido(1, "cmbTypeOrderPedido");
            $(".data-container .orderPedido-disabled").css({
              display: "inline-block",
              visibility: "visible",
              opacity: "1",
              animation: "fade 1s",
            });
            loadOrderPedido(
              respuesta[0].id,
              respuesta[0].sucursalOrigen,
              "cmbOrderPedido",
              true
            );
            $(".data-container .brancheDestination-disabled").css({
              display: "inline-block",
              visibility: "visible",
              opacity: "1",
              animation: "fade 1s",
            });
          } else if (respuesta[0].tipoSalida == "4") {
            loadTypeOrderPedido(2, "cmbTypeOrderPedido");
            $(".data-container .orderPedidoGral-disabled").css({
              display: "inline-block",
              visibility: "visible",
              opacity: "1",
              animation: "fade 1s",
            });
            loadOrderPedidoGral(
              respuesta[0].id,
              respuesta[0].sucursalOrigen,
              "cmbOrderPedidoGral",
              true
            );
            $(".data-container .branchOrSale-disabled").css({
              display: "inline-block",
              visibility: "visible",
              opacity: "1",
              animation: "fade 1s",
            });
          }
          
          flagBarCode();  
        }
      },
      error: function (error) {
        //console.log(error);
      },
      complete: function (_, __) {},
    });
    saveSalidaOPTemp();
  }

 /*  new SlimSelect({
    select: "#cmbTipoSalida",
    deselectLabel: '<span class="">✖</span>',
  }); */

  new SlimSelect({
    select: "#cmbQuote",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbSales",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbProveedor",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbNoDocumento",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbSucursalOC",
    deselectLabel: '<span class="">✖</span>',
  });
});

//Cargar Combo de Tipo Salida
function loadTypeExits(data, input) {
  var html = '<option value="" selected>Seleccione un tipo de salida</option>';
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_typeExits" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta tipo de salidas combo:", respuesta);

      $.each(respuesta, function (i) {
        //console.log("HOA" + data);
        if (data === respuesta[i].PKTipoSalida) {
          selected = "selected";
        } else {
          selected = "";
        }
        html +=
          '<option value="' +
          respuesta[i].PKTipoSalida +
          '" ' +
          selected +
          ">" +
          respuesta[i].TipoSalida +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      //console.log(error);
    },
  });
}

//bandera del permiso a la funcion y arreglo que contiene los productos que han sido escaneados con el codigo de barras (cuando la empresa tenga accesso a esta función);
var prodsIdBarCod = {};
var barCod = false;

async function flagBarCode(){
  let promise_BC = await loadBarcode(); 
  if(promise_BC[0].access == 1){
    barCod = true;
    $(".data-container .brancheBarCode-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });
    $("#BarCode").focus();
  }else{
    barCod = false;
    $(".data-container .brancheBarCode-disabled").css({
      display: "none",
      visibility: "hidden",
      opacity: "0",
    });
  } 
}

//comprueba si la empresa tiene acceso a la lectura de codigo de barras en salidas.
function loadBarcode(){
  return $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_Barcode_salidas" },
    dataType: "json"
  });
/* 
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_Barcode_salidas" },
    dataType: "json",
    success: function (respuesta) {
      if(respuesta[0].access == 1){
        barCod=true;
        html +=
          '<label for="BarCode">Escanear código de barras:</label>' +
          '<input class="form-control alphaNumeric-only" type="text" name="BarCode" id="BarCode" style="float:left;">'; 
      }else{
        barCod=false;
      }

      $("#divCodigoBarras").html(html);
    },
    error: function (error) {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        img: null,
        msg: "Ocurrió un error al validar permiso para código de barras",
      });
    },
  }); */
}

/* Load Sucursales (Origen) de la empresa */
function loadBranchOrigin(data, input) {
  var html = '<option value="" selected>Seleccione una sucursal...</option>';
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_branch_origin" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKSucursal) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKSucursal +
            '" ' +
            selected +
            ">" +
            respuesta[i].Sucursal +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $("#" + input + "").html(html);
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

/* Load Sucursales (Destino) de la empresa */
function loadBranchDestination(data, input, callOtherFunction = false) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_branch_destination",
      data: data,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKSucursal) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKSucursal +
            '" ' +
            selected +
            ">" +
            respuesta[i].Sucursal +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (callOtherFunction) {
        loadDispenser("", "cmbSurtidorSalida", true);
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

/* Load Sucursales Destino o Clientes (Pedido General) de la empresa */
function loadBranchOrSales(data, input, callOtherFunction = false) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_branch_or_customer",
      data: data,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKSucursalOCliente) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKSucursalOCliente +
            '" ' +
            selected +
            ">" +
            respuesta[i].SucursalOCliente +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (callOtherFunction) {
        loadDispenserGral("", "cmbSurtidorSalidaGral", true);
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

/* Load Orden de pedido de la empresa */
function loadOrderPedido(data, sucOrigen, input, callOtherFunction = false) {
  var html =
    '<option value="" selected>Seleccione una orden de pedido...</option>';
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_order_pedido",
      data: sucOrigen,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKOrdenPedido) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKOrdenPedido +
            '" ' +
            selected +
            ">" +
            respuesta[i].OrdenPedido +
            "</option>";
        });
      } else {
        html +=
          '<option value="vacio">No hay ordenes de pedido que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (callOtherFunction) {
        loadBranchDestination(data, "cmbSucursalDestino", true);
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

/* Load Orden de pedido de la empresa */
function loadOrderPedidoGral(
  data,
  sucOrigen,
  input,
  callOtherFunction = false
) {
  var html =
    '<option value="" selected>Seleccione una orden de pedido...</option>';
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_order_pedidoGral",
      data: sucOrigen,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKOrdenPedido) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKOrdenPedido +
            '" ' +
            selected +
            ">" +
            respuesta[i].OrdenPedido +
            "</option>";
        });
      } else {
        html +=
          '<option value="vacio">No hay ordenes de pedido que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (callOtherFunction) {
        loadBranchOrSales(data, "cmbSucursalOrVenta", true);
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

/* Load Cotizaciones de la empresa */
function loadQuote(data, sucOrigen, input, callOtherFunction = false) {
  var html = '<option value="" selected>Seleccione una cotización...</option>';
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_quotes",
      data: sucOrigen,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKOrdenPedido) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKOrdenPedido +
            '" ' +
            selected +
            ">" +
            respuesta[i].OrdenPedido +
            "</option>";
        });
      } else {
        html +=
          '<option value="vacio">No hay cotizaciones que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (callOtherFunction) {
        dataQuote(data, true);
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

/* Load Ventas directas de la empresa */
function loadSales(data, sucOrigen, input, callOtherFunction = false) {
  var html = '<option value="" selected>Seleccione una venta...</option>';
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_sales",
      data: sucOrigen,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKOrdenPedido) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKOrdenPedido +
            '" ' +
            selected +
            ">" +
            respuesta[i].OrdenPedido +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay ventas que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (callOtherFunction) {
        /* INFO */
        //console.log("llamo data sales");
        dataSales(data, true);
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

function loadTypeOrderPedido(data, input) {
  var html = `<option value="" selected>Seleccione un tipo de pedido...</option>`;
  /* if (data == 1) {
    html += `<option value="1" selected>Traspasos</option>`;
  } else {
    html += `<option value="1">Traspasos</option>`;
  } */

  if (data == 3) {
    html += `<option value="3" selected>Cotizaciones</option>`;
  } else {
    html += `<option value="3">Cotizaciones</option>`;
  }

  if (data == 4) {
    html += `<option value="4" selected>Ventas</option>`;
  } else {
    html += `<option value="4">Ventas</option>`;
  }

  if (data == 2) {
    html += `<option value="2" selected>General</option>`;
  } else {
    html += `<option value="2">General</option>`;
  }

  $("#" + input + "").html(html);
}

function loadDispenser(data, input, callOtherFunction = false) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_dispenser_exit",
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKVendedor) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKVendedor +
            '" ' +
            selected +
            ">" +
            respuesta[i].Nombre +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay surtidores que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (callOtherFunction) {
        deleteSalidaOPTemp(true);
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

function loadDispenserGral(data, input, callOtherFunction = false) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_dispenser_exit",
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKVendedor) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKVendedor +
            '" ' +
            selected +
            ">" +
            respuesta[i].Nombre +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay surtidores que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (callOtherFunction) {
        deleteSalidaOPTemp(true);
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

function loadDispenserReturn(data, input) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_dispenser_exit",
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKVendedor) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKVendedor +
            '" ' +
            selected +
            ">" +
            respuesta[i].Nombre +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay surtidores que mostrar</option>';
      }

      $("#" + input + "").html(html);
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

var tblSalidas;
var tblSalidasD;

function loadProductosOrderPedido(PKOrdenPedido) {
  //console.log({ PKOrdenPedido });
  $("#tblSalidaOrdenPedido").DataTable().destroy();
  tblSalidas = $("#tblSalidaOrdenPedido").DataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    searching: false,
    buttons: [],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_productoSalidaOPTempTable",
        data: PKOrdenPedido,
      }
    },
    //"pageLength": 20,
    paging: false,
    order: [1, "asc"],
    columns: [
      { data: "Id" },
      { data: "Clave" },
      { data: "Producto" },
      { data: "CantidadPedida" },
      { data: "CantidadSurtida" },
      { data: "CantidadRestante" },
      { data: "Existencias" },
      { data: "CantidadSalida" },
      { data: "Lote" },
      //{ data: "Serie" },
      { data: "um" },
      { data: "cb" },
      { data: "Caducidad" },
      { data: "Acciones" },
    ],
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
  });
  flagBarCode();  
  //si aplica, recupera los productos de la orden de pedido y los añade al arreglo prodsIdBarCod con una bandera que indica si ya fué checado con el código (1: si y 0: no)
  if(barCod == true){
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", 
              funcion: "get_idProds_salidas",
              data: PKOrdenPedido 
            },
      dataType: "json",
      success: function (respuesta) {
        //se vacia arreglo de prods
        prodsIdBarCod = {};
        $.each(respuesta, function (i) {
          if(respuesta[i].existencia > 0 || respuesta[i].existencia == 'N/A'){
            prodsIdBarCod[respuesta[i].pkProducto]=0;
          }
        }); 
      },
      error: function (error) {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: "Ocurrió un error al recuperar productos para código de barras",
        });
      },
    });

    tblSalidas.one("draw", function () {
      if(barCod){
        inptCantidadChangeDisable();
      }
    });
  }  

  setTimeout(function () {
    obtenerTotal(PKOrdenPedido);
    $('[data-toggle="tooltip"]').tooltip();
  }, 500);
}

function loadProductosDevolucion(IdCuentaPagar) {
  $("#tblSalidaDevolucion").DataTable().destroy();
  tblSalidasD = $("#tblSalidaDevolucion").DataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    searching: false,
    buttons: [],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_productoSalidaDevolucionTempTable",
        data: IdCuentaPagar,
      },
    },
    //"pageLength": 20,
    paging: false,
    order: [1, "asc"],
    columns: [
      { data: "Id" },
      { data: "Clave" },
      { data: "Producto" },
      { data: "CantidadEntrada" },
      { data: "Existencias" },
      { data: "CantidadSalida" },
      { data: "Lote" },
      //{ data: "Serie" },
      { data: "Caducidad" },
      { data: "Acciones" },
    ],
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
  });

  setTimeout(function () {
    obtenerTotal(IdCuentaPagar);
  }, 500);
}

function loadProvider(data, input) {
  var html = '<option value="" selected>Seleccione un proveedor...</option>';

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_providerEntry" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKProveedor) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKProveedor +
            '" ' +
            selected +
            ">" +
            respuesta[i].Razon_Social +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay proveedores que mostrar</option>';
      }

      $("#" + input + "").html(html);
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);

  $("#" + input + " option:not(:selected)").remove();
}

/* Load Ordenes de compra de la empresa */
function loadNoDocumentos(data, input) {
  var html =
    '<option value="" selected>Seleccione una orden de compra...</option>';
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_noDocsExit", data: data },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta:", respuesta);

      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].id) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].id +
            '" ' +
            selected +
            ">" +
            respuesta[i].numero_documento +
            "</option>";
        });
      } else {
        html +=
          '<option value="vacio">No hay órdenes de compra que mostrar</option>';
      }

      $("#" + input + "").html(html);
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);
}

function loadBranchDoc(data, input) {
  var html = "";

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_branchExit", data: data },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta !== "" && respuesta !== null) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKSucursal) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKSucursal +
            '" ' +
            selected +
            ">" +
            respuesta[i].Sucursal +
            "</option>";
        });
      } else {
        html += '<option value="vacio">No hay sucursales que mostrar</option>';
      }

      $("#" + input + "").html(html);
    },
    error: function (error) {
      //console.log(error);
    },
  });

  $("#" + input + "").html(html);

  $("#" + input + " option:not(:selected)").remove();
}

function loadInfoNoDoc(id_cuenta_pagar) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_DataNoDocExit",
      data: id_cuenta_pagar,
    },
    dataType: "json",
    success: function (data) {
      $("#txtNoDocumento").val(data[0].folio_factura);
      $("#txtSerie").val(data[0].num_serie_factura);
      $("#txtSubtotal").val(data[0].subtotal);
      $("#txtIva").val(data[0].iva);
      $("#txtIEPS").val(data[0].ieps);
      $("#txtImporte").val(data[0].importe);
      $("#txtDescuento").val(data[0].descuento);
      $("#txtFechaFactura").val(data[0].fecha_factura);
      $("#txtNoBultosPaquetesDevolucion").val(data[0].fecha_factura);
    },
  });
}

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<img src='../../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

function saveSalidaDevolucionTemp() {
  var IdCuentaPagar = $("#cmbNoDocumento").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_exitDevolucion_TempTable",
      data: IdCuentaPagar,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      if (respuesta[0].status) {
        loadProductosDevolucion(IdCuentaPagar);
      } else {
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });
}

function saveSalidaOPTemp() {
  /* INFO */
  //console.log("Entro");
  var ordenPedido = $("#cmbOrderPedido").val();
  var cotizacion = $("#cmbQuote").val();
  var venta = $("#cmbSales").val();
  var ordenPedidoGral = $("#cmbOrderPedidoGral").val();

  var IDOP = 0;

  if (ordenPedido != "" && ordenPedido != null) {
    IDOP = ordenPedido;
  } else if (cotizacion != "" && cotizacion != null) {
    IDOP = cotizacion;
  } else if (venta != "" && venta != null) {
    IDOP = venta;
  } else if (ordenPedidoGral != "" && ordenPedidoGral != null) {
    IDOP = ordenPedidoGral;
  }
  //console.log({ venta, IDOP });
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_exitOP_TempTable", //FIX: El procedimiento que s
      data: IDOP,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      if (respuesta[0].status) {
        loadProductosOrderPedido(IDOP);
      } else {
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });
}

function deleteSalidaOPTemp(callOtherFunction = false) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosSalidaOPTemp",
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta eliminar salida op temp:", respuesta);
      if (callOtherFunction) {
        /* INFO */
        //console.log("Llamo saveSalidaOPTemp");
        saveSalidaOPTemp();
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });
}

function deleteSalidaDevolucionTemp() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_datosSalidaDevolucionTemp",
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
      } else {
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });
}

/*begins combo Tipo Salida selections*/
$(document).on("change", "#cmbTipoSalida", function () {
  $(".data-container .branchOrigin-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .orderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .brancheDestination-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .typeOrderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .quote-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customer-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .sales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customerSales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .providers-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .purchases-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOriginOC-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .orderPedidoGral-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOrSale-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .brancheBarCode-disabled").css({
    display: "none",
    visibility: "hidden",
    opacity: "0",
  });

  if ($("#cmbTipoSalida").val() === "") {
    $(".data-container .branchOrigin-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .typeOrderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .purchases-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOriginOC-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheBarCode-disabled").css({
      display: "none",
      visibility: "hidden",
      opacity: "0",
    });
  }

  if ($("#cmbTipoSalida").val() === "1") {
    loadBranchOrigin("", "cmbSucursalOrigen");
    $(".data-container .branchOrigin-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    $("#div2").html(`<div class="form-group">
                      <div class="row">
                        <div class="col-lg-4">
                          <div class="brancheDestination-disabled">
                            <label for="cmbSucursalDestino">Sucursal de destino*:</label>
                              <div class="input-group">
                                <select name="cmbSucursalDestino" id="cmbSucursalDestino" required></select>
                              <div class="invalid-feedback" id="invalid-sucursalDestino">La salida debe de tener una sucursal de destino.</div>
                            </div>
                          </div>
                          <div class="branchOrSale-disabled">
                            <label for="cmbSucursalOrVenta">Destino*:</label>
                              <div class="input-group">
                                <select name="cmbSucursalOrVenta" id="cmbSucursalOrVenta" required></select>
                              <div class="invalid-feedback" id="invalid-sucursalDestino">La salida debe de tener una sucursal de destino.</div>
                            </div>
                          </div>
                          <div class="customer-disabled">
                            <label for="cmbClienteCotizacion">Cliente*:</label>
                              <div class="input-group">
                                <input class="form-control" type="text" name="cmbClienteCotizacion" id="cmbClienteCotizacion" readonly></select>
                              <div class="invalid-feedback" id="invalid-clienteCotizacion">La salida debe de tener un cliente.</div>
                            </div>
                          </div>
                          <div class="customerSales-disabled">
                            <label for="cmbClienteVenta">Cliente*:</label>
                              <div class="input-group">
                                <input class="form-control" type="text" name="cmbClienteVenta" id="cmbClienteVenta" readonly></select>
                              <div class="invalid-feedback" id="invalid-clienteVenta">La salida debe de tener un cliente.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="brancheDestination-disabled">
                            <label for="cmbSurtidorSalida">Surtidor*:</label>
                              <div class="input-group">
                                <select name="cmbSurtidorSalida" id="cmbSurtidorSalida" required></select>
                              <div class="invalid-feedback" id="invalid-surtidorSalida">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                          <div class="branchOrSale-disabled">
                            <label for="cmbSurtidorSalidaGral">Surtidor*:</label>
                              <div class="input-group">
                                <select name="cmbSurtidorSalidaGral" id="cmbSurtidorSalidaGral" required></select>
                              <div class="invalid-feedback" id="invalid-surtidorSalida">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                          <div class="customer-disabled">
                            <label for="cmbSurtidorSalidaCoti">Surtidor*:</label>
                              <div class="input-group">
                                <select class="form-control" name="cmbSurtidorSalidaCoti" id="cmbSurtidorSalidaCoti"></select>
                              <div class="invalid-feedback" id="invalid-surtidorSalidaCoti">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                          <div class="customerSales-disabled">
                            <label for="cmbSurtidorSalidaVenta">Surtidor*:</label>
                              <div class="input-group">
                                <select class="form-control" name="cmbSurtidorSalidaVenta" id="cmbSurtidorSalidaVenta"></select>
                              <div class="invalid-feedback" id="invalid-surtidorVenta">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="brancheDestination-disabled">
                            <label for="usr">No. Bultos / Paquetes / Notas:</label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetes" id="txtNoBultosPaquetes" placeholder="50 Bultos" style="float:left;">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetes">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                          <div class="branchOrSale-disabled">
                            <label for="usr">No. Bultos / Paquetes / Notas:</label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesGral" id="txtNoBultosPaquetesGral" placeholder="50 Bultos" style="float:left;">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetes">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                          <div class="customer-disabled">
                            <label for="usr">No. Bultos / Paquetes / Notas:</label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesCoti" id="txtNoBultosPaquetesCoti" placeholder="50 Bultos" style="float:left;">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetesCoti">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                          <div class="customerSales-disabled">
                            <label for="usr">No. Bultos / Paquetes / Notas:</label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesVenta" id="txtNoBultosPaquetesVenta" placeholder="50 Bultos" style="float:left;">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetesVenta">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>`);

    $("#divCodigoBarras").html(`<div class = "brancheBarCode-disabled" style="display:none; visibility: "hidden"; opacity: 0;">
                                  <label for="BarCode">Escanear código de barras:</label>
                                  <input class="form-control alphaNumeric-only" type="text" name="BarCode" id="BarCode" style="float:left;">
                                </div>`);
    new SlimSelect({
      select: "#cmbSucursalOrigen",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbOrderPedido",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbOrderPedidoGral",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbSucursalDestino",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbSucursalOrVenta",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbSurtidorSalida",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbSurtidorSalidaGral",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#cmbTypeOrderPedido",
      deselectLabel: '<span class="">✖</span>',
    });
  } else if ($("#cmbTipoSalida").val() === "2") {
    loadProvider("", "cmbProveedor");
    $(".data-container .providers-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    $("#div2").html(`<div class="form-group">
                      <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">No. de documento*:</label>
                            <div class="input-group">
                              <input required class="form-control alphaNumeric-only" type="text" name="txtNoDocumento" id="txtNoDocumento" placeholder="Folio" style="float:left;" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">Serie de factura*:</label>
                            <div class="input-group">
                              <input required class="form-control alphaNumeric-only" type="text" name="txtSerie" id="txtSerie" placeholder="Serie" style="float:left;" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">Subtotal*:</label>
                            <div class="input-group">
                              <input required class="form-control numericDecimal-only" type="number" name="txtSubtotal" id="txtSubtotal" placeholder="Ej. 1000.00" style="float:left;" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">IVA (Monto):</label>
                            <div class="input-group">
                              <input class="form-control numericDecimal-only" type="number" name="txtIva" id="txtIva" placeholder="Ej. 1000.00" style="float:left;" value="0" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">IEPS (Monto):</label>
                            <div class="input-group">
                              <input class="form-control numericDecimal-only" type="number" name="txtIEPS" id="txtIEPS" placeholder="Ej. 1000.00" style="float:left;" value="0" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">Importe factura*:</label>
                            <div class="input-group">
                              <input required class="form-control numericDecimal-only" type="number" name="txtImporte" id="txtImporte" placeholder="Ej. 1000.00" style="float:left;" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">Descuento (Monto):</label>
                            <div class="input-group">
                              <input class="form-control numericDecimal-only" type="number" name="txtDescuento" id="txtDescuento" placeholder="Ej. 1000.00" style="float:left;" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">Fecha de factura*:</label>
                            <div class="input-group">
                              <input required class="form-control" type="date" name="txtFechaFactura" id="txtFechaFactura" style="float:left;" readonly>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="cmbSurtidorSalidaDevolucion">Surtidor*:</label>
                            <div class="input-group">
                              <select name="cmbSurtidorSalidaDevolucion" id="cmbSurtidorSalidaDevolucion" required></select>
                              <div class="invalid-feedback" id="invalid-surtidorSalida">La salida debe de tener un surtidor.</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                          <div class="branchOriginOC-disabled">
                            <label for="usr">Notas: </label>
                              <div class="input-group">
                                <input class="form-control alphaNumeric-only" type="text" name="txtNoBultosPaquetesDevolucion" id="txtNoBultosPaquetesDevolucion" placeholder="50 Bultos"">
                              <div class="invalid-feedback" id="invalid-noBultosPaquetes">La salida debe de tener un número de bultos o paquetes.</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>`);

    $("#divCodigoBarras").html(`<div class = "brancheBarCode-disabled"  style="display:none; visibility: "hidden"; opacity: 0;">
                                  <label for="BarCode">Escanear código de barras:</label>
                                  <input class="form-control alphaNumeric-only" type="text" name="BarCode" id="BarCode" style="float:left;">
                                </div>`);
    loadDispenserReturn("", "cmbSurtidorSalidaDevolucion");

    new SlimSelect({
      select: "#cmbSurtidorSalidaDevolucion",
      deselectLabel: '<span class="">✖</span>',
    });
  } else {
    $(".data-container .branchOrigin-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .typeOrderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .purchases-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOriginOC-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheBarCode-disabled").css({
      display: "none",
      visibility: "hidden",
      opacity: "0",
    });
  }
});

/*begins combo tipo orden pedido selections*/
$(document).on("change", "#cmbTypeOrderPedido", function () {
  $(".data-container .orderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .brancheDestination-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .quote-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customer-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .sales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customerSales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .orderPedidoGral-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOrSale-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .brancheBarCode-disabled").css({
    display: "none",
    visibility: "hidden",
    opacity: "0",
  });
  

  setTimeout(function () {
    deleteSalidaOPTemp();
    deleteSalidaDevolucionTemp();
  }, 0);

  if ($("#cmbTypeOrderPedido").val() === "") {
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
  } else if ($("#cmbTypeOrderPedido").val() === "1") {
    $(".data-container .orderPedido-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadOrderPedido("", $("#cmbSucursalOrigen").val(), "cmbOrderPedido");
  } else if ($("#cmbTypeOrderPedido").val() === "2") {
    $(".data-container .orderPedidoGral-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadOrderPedidoGral(
      "",
      $("#cmbSucursalOrigen").val(),
      "cmbOrderPedidoGral"
    );
  } else if ($("#cmbTypeOrderPedido").val() === "3") {
    $(".data-container .quote-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadQuote("", $("#cmbSucursalOrigen").val(), "cmbQuote");
  } else if ($("#cmbTypeOrderPedido").val() === "4") {
    $(".data-container .sales-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadSales("", $("#cmbSucursalOrigen").val(), "cmbSales");
  } else {
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
  }
});

/*begins combo sucursal origen selections*/
$(document).on("change", "#cmbSucursalOrigen", function () {
  $(".data-container .orderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .brancheDestination-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .typeOrderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .quote-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customer-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .sales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customerSales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .orderPedidoGral-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOrSale-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .brancheBarCode-disabled").css({
    display: "none",
    visibility: "hidden",
    opacity: "0",
  });

  if ($("#cmbSucursalOrigen").val() === "") {
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .typeOrderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheBarCode-disabled").css({
      display: "none",
      visibility: "hidden",
      opacity: "0",
    });
  } else {
    $(".data-container .typeOrderPedido-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    loadTypeOrderPedido("", "cmbTypeOrderPedido");
  }
});

/*begins combo Orden pedido selections*/
$(document).on("change", "#cmbOrderPedido", function () {
  $(".data-container .brancheDestination-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });

  if ($("#cmbOrderPedido").val() === "") {
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
  } else {
    $(".data-container .brancheDestination-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    setTimeout(function () {
      deleteSalidaOPTemp();
      deleteSalidaDevolucionTemp();
      $("#cmbQuote").val(null);
      $("#cmbSales").val(null);
      $("#cmbOrderPedidoGral").val(null);
    }, 0);

    loadBranchDestination($("#cmbOrderPedido").val(), "cmbSucursalDestino");
    loadDispenser("", "cmbSurtidorSalida");

    setTimeout(function () {
      saveSalidaOPTemp();
    }, 500);
  }
});

/*begins combo Orden pedido general selections*/
$(document).on("change", "#cmbOrderPedidoGral", function () {
  $(".data-container .branchOrSale-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });

  if ($("#cmbOrderPedidoGral").val() === "") {
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
  } else {
    $(".data-container .branchOrSale-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });

    setTimeout(function () {
      deleteSalidaOPTemp();
      deleteSalidaDevolucionTemp();
      $("#cmbOrderPedido").val(null);
      $("#cmbQuote").val(null);
      $("#cmbSales").val(null);
    }, 0);

    loadBranchOrSales($("#cmbOrderPedidoGral").val(), "cmbSucursalOrVenta");
    loadDispenserGral("", "cmbSurtidorSalidaGral");

    setTimeout(function () {
      saveSalidaOPTemp();
    }, 500);
  }
});

/*begins combo Cotizaciones selections*/
$(document).on("change", "#cmbQuote", function () {
  $(".data-container .brancheDestination-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customer-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .sales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customerSales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .orderPedidoGral-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOrSale-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $("#BarCode, #lblBarCode, .data-container .brancheBarCode-disabled").css({
    display: "inline-block",
    visibility: "visible",
    opacity: "100",
  });

  if ($("#cmbQuote").val() === "") {
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
  } else {
    $(".data-container .customer-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });
    $("#BarCode, #lblBarCode, .data-container .brancheBarCode-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "100",
    });
    dataQuote($("#cmbQuote").val());

    setTimeout(function () {
      deleteSalidaOPTemp();
      deleteSalidaDevolucionTemp();
      $("#cmbOrderPedido").val(null);
      $("#cmbSales").val(null);
      $("#cmbOrderPedidoGral").val(null);
    }, 0);

    setTimeout(function () {
      saveSalidaOPTemp();
    }, 500);
  }
});

/*begins combo Cotizaciones selections*/
$(document).on("change", "#cmbSales", function () {
  $(".data-container .brancheDestination-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customer-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .quote-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customerSales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOrSale-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });

  if ($("#cmbSales").val() === "") {
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
  } else {
    $(".data-container .customerSales-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });
    dataSales($("#cmbSales").val());

    setTimeout(function () {
      deleteSalidaOPTemp();
      deleteSalidaDevolucionTemp();
      $("#cmbOrderPedido").val(null);
      $("#cmbQuote").val(null);
      $("#cmbOrderPedidoGral").val(null);
    }, 0);

    setTimeout(function () {
      saveSalidaOPTemp();
    }, 500);
  }
});

/*begins combo Proveedores selections*/
$(document).on("change", "#cmbProveedor", function () {
  $(".data-container .orderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .brancheDestination-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .typeOrderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .quote-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customer-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .sales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customerSales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOriginOC-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .orderPedidoGral-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOrSale-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });

  if ($("#cmbProveedor").val() === "") {
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .typeOrderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOriginOC-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
  } else {
    loadNoDocumentos($("#cmbProveedor").val(), "cmbNoDocumento");
    $(".data-container .purchases-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });
  }
});

/*begins combo Orden compra selections*/
$(document).on("change", "#cmbNoDocumento", function () {
  $(".data-container .orderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .brancheDestination-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .typeOrderPedido-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .quote-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customer-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .sales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .customerSales-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .orderPedidoGral-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });
  $(".data-container .branchOrSale-disabled").css({
    display: "none",
    opacity: "0",
    visibility: "hidden",
  });

  //deleteEntradaOCTemp();

  if ($("#cmbNoDocumento").val() === "") {
    $(".data-container .orderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .brancheDestination-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .typeOrderPedido-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .quote-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customer-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .sales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .customerSales-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .orderPedidoGral-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
    $(".data-container .branchOrSale-disabled").css({
      display: "none",
      opacity: "0",
      visibility: "hidden",
    });
  } else {
    $(".data-container .branchOriginOC-disabled").css({
      display: "inline-block",
      visibility: "visible",
      opacity: "1",
      animation: "fade 1s",
    });
  }

  loadBranchDoc($("#cmbNoDocumento").val(), "cmbSucursalOC");
  loadInfoNoDoc($("#cmbNoDocumento").val());

  setTimeout(function () {
    deleteSalidaOPTemp();
    deleteSalidaDevolucionTemp();
    $("#cmbOrderPedido").val(null);
    $("#cmbQuote").val(null);
    $("#cmbSales").val(null);
    $("#cmbOrderPedidoGral").val(null);
  }, 0);

  setTimeout(function () {
    saveSalidaDevolucionTemp();
  }, 500);
});

//cuando se presione enter en la barcode, se validará si el producto está en la orden 
$(document).on("keyup", "#BarCode", function (e) {
  if (e.key === 'Enter' || e.keyCode === 13) {
      verificaCodigoBarra($("#BarCode").val());
  }
});

function inptCantidadChangeDisable(){
  
    let hiddenRows = tblSalidas.rows().nodes();
    $("input[name='inptCantidad']", hiddenRows).each(function() {
        let arrid = $(this).attr("data-id");
        if (prodsIdBarCod[arrid] == 1) {
          $(this).prop("disabled", false);
          $("#BarCode").val('');
        }else{
          $(this).prop("disabled", true);
        }        
    });

    $("#lbl_bc", hiddenRows).each(function() {
      let arrid = $(this).attr("data-id-lbl");
      if (prodsIdBarCod[arrid] == 1) {
        $(this).css("display","block");
      }else{
        $(this).css("display","none");
      }        
  });
  
}

function verificaCodigoBarra(codigo){
  //recupera id de la orden
  let ordenPedido = $("#cmbOrderPedido").val();
  let cotizacion = $("#cmbQuote").val();
  let venta = $("#cmbSales").val();
  let ordenPedidoGral = $("#cmbOrderPedidoGral").val();

  let IDOP = 0;

  if (ordenPedido != "" && ordenPedido != null) {
    IDOP = ordenPedido;
  } else if (cotizacion != "" && cotizacion != null) {
    IDOP = cotizacion;
  } else if (venta != "" && venta != null) {
    IDOP = venta;
  } else if (ordenPedidoGral != "" && ordenPedidoGral != null) {
    IDOP = ordenPedidoGral;
  }

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", 
            funcion: "get_valida_codigo_ProdOrden",
            data : codigo,
            data2: IDOP
            },
    dataType: "json",
    success: function (respuesta) {
      if(respuesta['existeProd'] == 1){
        // cambiar valor en arreglo prodsIdBarCod y habilita el input
        prodsIdBarCod[respuesta['producto']] = 1;
        $("#BarCode").focus();
        inptCantidadChangeDisable();        
      }else{
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: "El código de barras escaneado no se encuentra en este pedido",
        });
        $("#BarCode").val('');
      }

    },
    error: function (error) {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: "Ocurrió un error al validar permiso para código de barras",
      });
    },
  });
}

function dataQuote(pkOrdenPedido, callOtherFunction = false) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_DataQuoteExit",
      data: pkOrdenPedido,
    },
    dataType: "json",
    success: function (data) {
      $("#cmbClienteCotizacion").val(data[0].cliente);
      loadDispenser(data[0].surtidor, "cmbSurtidorSalidaCoti");
      $("#txtNoBultosPaquetesCoti").val(data[0].notas);
      if (callOtherFunction) {
        deleteSalidaOPTemp(true);
      }
    },
  });
}

function dataSales(pkOrdenPedido, callOtherFunction = false) {
  //console.log(pkOrdenPedido);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_DataSaleExit",
      data: pkOrdenPedido,
    },
    dataType: "json",
    success: function (data) {
      $("#cmbClienteVenta").val(data[0].cliente);
      loadDispenser(data[0].surtidor, "cmbSurtidorSalidaVenta");
      $("#txtNoBultosPaquetesVenta").val(data[0].notas);
      if (callOtherFunction) {
        /* INFO */
        //console.log("llamo deleteSalidaOPTemp");
        deleteSalidaOPTemp(true);
      }
    },
    error: function (e) {
      //console.log(e);
    },
  });
}

function disableButtonsAdd() {
  console.log("disabled");
  $("#btnAgregarSalidaOCP").bind("click", false);
  $("#btnAgregarSalidaGral").bind("click", false);
  $("#btnAgregarSalidaCoti").bind("click", false);
  $("#btnAgregarSalidaVenta").bind("click", false);
}

function validarCantidad(serieLote, pkProducto) {
  escribiendoValidarCantidad(serieLote, pkProducto);
}

var controladorTiempo = "";
function escribiendoValidarCantidad(serieLote, pkProducto) {
  clearTimeout(controladorTiempo);
  //Llamar la busqueda cuando el usuario deje de escribir
  //controladorTiempo = setTimeout(function () {
  var cantidad = $("#cantidad-" + serieLote + pkProducto).val();
  var ordenPedido = $("#cmbOrderPedido").val();
  var cotizacion = $("#cmbQuote").val();
  var venta = $("#cmbSales").val();
  var ordenPedidoGral = $("#cmbOrderPedidoGral").val();

  var IDOP = 0;

  if (ordenPedido != "" && ordenPedido != null) {
    IDOP = ordenPedido;
  } else if (cotizacion != "" && cotizacion != null) {
    IDOP = cotizacion;
  } else if (venta != "" && venta != null) {
    IDOP = venta;
  } else if (ordenPedidoGral != "" && ordenPedidoGral != null) {
    IDOP = ordenPedidoGral;
  }
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_salida_cantidadModalTemp",
      data: serieLote,
      data2: cantidad,
      data3: IDOP,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (data) {
      //console.log("respuesta validación: ", data);

      if (parseInt(data[0]["existe"]) == 2) {
        //$("#cantidad-"+serieLote).val(0);

        $("#cantidad-" + serieLote + pkProducto).addClass("is-invalid");
        $("#invalid-cantidad-" + serieLote + pkProducto).css(
          "display",
          "block"
        );
        $("#invalid-cantidad-" + serieLote + pkProducto).text(
          "La suma de las cantidades es mayor a la cantidad faltante."
        );
      } else if (parseInt(data[0]["existe"]) == 1) {
        //$("#cantidad-"+serieLote+pkProducto).val(0);

        $("#cantidad-" + serieLote + pkProducto).addClass("is-invalid");
        $("#invalid-cantidad-" + serieLote + pkProducto).css(
          "display",
          "block"
        );
        $("#invalid-cantidad-" + serieLote + pkProducto).text(
          "La cantidad es mayor a la existente."
        );
      } else {
        editarCantidad(serieLote, cantidad, IDOP, pkProducto);

        $("#cantidad-" + serieLote + pkProducto).removeClass("is-invalid");
        $("#invalid-cantidad-" + serieLote + pkProducto).css("display", "none");
        $("#invalid-cantidad-" + serieLote + pkProducto).text(
          "La cantidad es válida."
        );
      }
    },
  });
  //}, 150);
}

function validarCantidadModal(serieLote) {
  escribiendoValidarCantidadModal(serieLote);
}

var controladorTiempo = "";
function escribiendoValidarCantidadModal(serieLote) {
  clearTimeout(controladorTiempo);
  var inputCantidad = $(".cnt-lote-serie");
  var suma = 0;
  var cantidadFalt = $("#configProductoCantFaltInput").val() * 1;
  for (let index = 0; index < inputCantidad.length; index++) {
    suma += inputCantidad[index].value * 1;
  }
  //console.log({ suma, cantidadFalt });
  if (suma > cantidadFalt) {
    $("#invalid-cantidad-mayor").addClass("d-block");
    $("#cantidadModal-" + serieLote).val(
      $("#cantidad-modal-old-" + serieLote).val()
    );
    return;
  }
  $("#invalid-cantidad-mayor").removeClass("d-block");
  $("#cantidad-modal-old-" + serieLote).val(
    $("#cantidadModal-" + serieLote).val()
  );
  //Llamar la busqueda cuando el usuario deje de escribir
  //controladorTiempo = setTimeout(function () {
  var pkProducto = $("#idProducto").val();
  var cantidad = $("#cantidadModal-" + serieLote).val();
  var ordenPedido = $("#cmbOrderPedido").val();
  var cotizacion = $("#cmbQuote").val();
  var venta = $("#cmbSales").val();
  var ordenPedidoGral = $("#cmbOrderPedidoGral").val();

  var IDOP = 0;

  if (ordenPedido != "" && ordenPedido != null) {
    IDOP = ordenPedido;
  } else if (cotizacion != "" && cotizacion != null) {
    IDOP = cotizacion;
  } else if (venta != "" && venta != null) {
    IDOP = venta;
  } else if (ordenPedidoGral != "" && ordenPedidoGral != null) {
    IDOP = ordenPedidoGral;
  }

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_salida_cantidadModalTemp",
      data: serieLote,
      data2: cantidad,
      data3: IDOP,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (data) {
      //console.log("respuesta validación: ", data);

      if (parseInt(data[0]["existe"]) == 2) {
        $("#cantidadModal-" + serieLote + pkProducto).addClass("is-invalid");
        $("#invalid-cantidadModal-" + serieLote + pkProducto).css(
          "display",
          "block"
        );
        $("#invalid-cantidadModal-" + serieLote + pkProducto).text(
          "La suma de las cantidades es mayor a la cantidad faltante."
        );
      } else if (parseInt(data[0]["existe"]) == 1) {
        $("#cantidadModal-" + serieLote + pkProducto).addClass("is-invalid");
        $("#invalid-cantidadModal-" + serieLote + pkProducto).css(
          "display",
          "block"
        );
        $("#invalid-cantidadModal-" + serieLote + pkProducto).text(
          "La cantidad es mayor a la existente."
        );
      } else {
        editarCantidad(serieLote, cantidad, IDOP, pkProducto);

        $("#cantidadModal-" + serieLote + pkProducto).removeClass("is-invalid");
        $("#invalid-cantidadModal-" + serieLote + pkProducto).css(
          "display",
          "none"
        );
        $("#invalid-cantidadModal-" + serieLote + pkProducto).text(
          "La cantidad es válida."
        );
      }
    },
    error: function (e) {
      //console.log(e);
    },
  });
  //}, 150);
}

function validarCantidadDevolucion(salidaTemp, pkProducto, IdCuentaPagar) {
  var cantidad = $("#cantidad-" + salidaTemp).val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_salida_cantidadDevolucionTemp",
      data: salidaTemp,
      data2: cantidad,
      data3: IdCuentaPagar,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (data) {
      //console.log("respuesta validación: ", data);

      if (parseInt(data[0]["existe"]) == 2) {
        $("#cantidad-" + salidaTemp).addClass("is-invalid");
        $("#invalid-cantidad-" + salidaTemp).css("display", "block");
        $("#invalid-cantidad-" + salidaTemp).text(
          "La cantidad es mayor a la cantidad que entró."
        );
      } else if (parseInt(data[0]["existe"]) == 1) {
        $("#cantidad-" + salidaTemp).addClass("is-invalid");
        $("#invalid-cantidad-" + salidaTemp).css("display", "block");
        $("#invalid-cantidad-" + salidaTemp).text(
          "La cantidad es mayor a la existente."
        );
      } else {
        editarCantidadDevolucion(
          salidaTemp,
          cantidad,
          IdCuentaPagar,
          pkProducto
        );

        $("#cantidad-" + salidaTemp).removeClass("is-invalid");
        $("#invalid-cantidad-" + salidaTemp).css("display", "none");
        $("#invalid-cantidad-" + salidaTemp).text("La cantidad es válida.");
      }
    },
  });
}

function configurarProducto(
  pkProducto,
  producto,
  cantidadPedida,
  descripcion,
  faltante
) {
  var ordenPedido = $("#cmbOrderPedido").val();
  var cotizacion = $("#cmbQuote").val();
  var venta = $("#cmbSales").val();
  var ordenPedidoGral = $("#cmbOrderPedidoGral").val();

  var IDOP = 0;

  if (ordenPedido != "" && ordenPedido != null) {
    IDOP = ordenPedido;
  } else if (cotizacion != "" && cotizacion != null) {
    IDOP = cotizacion;
  } else if (venta != "" && venta != null) {
    IDOP = venta;
  } else if (ordenPedidoGral != "" && ordenPedidoGral != null) {
    IDOP = ordenPedidoGral;
  }
  var html = ``;

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_DataDatosSalidaCantTemp",
      data: pkProducto,
      data2: IDOP,
    },
    dataType: "json",
    success: function (data) {
      //console.log("respuesta de datos de producto: ", data);
      for (var dato of data) {
        var lote = dato.Lote;
        var serie = dato.Serie;
        var SerieLote = "";
        if (lote == "") {
          SerieLote = serie;
        } else {
          SerieLote = lote;
        }
        //console.log("Salida: " + dato.salida);

        html += `<div class="row">
                  <div class="col-md-3">
                    <label class="form-check-label">${SerieLote}</label>
                  </div>

                  <div class="col-md-2">
                  ${dato.existencia} 
                  </div>

                  <div class="col-md-2">
                  ${dato.Caducidad ? dato.Caducidad : "----/--/----"} 
                  </div>
                  
                  <div class="col-md-2">
                    <input class="form-control textTable border-0 cnt-lote-serie" type="number" value="${
                      dato.salida
                    }" onchange="validarCantidadModal('${SerieLote}')" id="cantidadModal-${SerieLote}" data-serieLote="${SerieLote}">
                    <input type="hidden" id="cantidad-modal-old-${SerieLote}" value="${
          dato.salida
        }">
                  </div>
                  
                  <div class="col-md-2">
                    <input type="hidden" value="${
                      dato.existencia
                    } " id="cantidadHisModal-${SerieLote}"> 
                    <div class="invalid-feedback" id="invalid-cantidadModal-${SerieLote}">La cantidad es inválida.</div> 
                  </div>
                </div>
              `;
      }
      $("#idProducto").val(pkProducto);
      $("#cantidad-faltante").val(faltante);
      $("#configProducto").html(producto);
      $("#configProductoCant").html(cantidadPedida);
      $("#configProductoCantFalt").html(faltante);
      $("#configProductoCantFaltInput").val(faltante);
      $("#descripcionProducto").html(descripcion);

      $("#listProducto").html(html);

      $("#configurarProducto").modal("show");
    },
  });
}

function eliminarCantidadTemp() {
  var ordenPedido = $("#cmbOrderPedido").val();
  var cotizacion = $("#cmbQuote").val();
  var venta = $("#cmbSales").val();
  var ordenPedidoGral = $("#cmbOrderPedidoGral").val();

  var IDOP = 0;

  if (ordenPedido != "" && ordenPedido != null) {
    IDOP = ordenPedido;
  } else if (cotizacion != "" && cotizacion != null) {
    IDOP = cotizacion;
  } else if (venta != "" && venta != null) {
    IDOP = venta;
  } else if (ordenPedidoGral != "" && ordenPedidoGral != null) {
    IDOP = ordenPedidoGral;
  }

  editarCantidad($("#exitTempIDD").val(), 0, IDOP, $("#ProductoTempIDD").val());
}

function editarCantidad(serieLote, cantidad, ordenPedido, pkProducto) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_salida_cantidad_modal_temp",
      data: serieLote,
      data2: cantidad,
      data3: ordenPedido,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("--------------------");
      console.log("respuesta", respuesta);
      console.log("--------------------");

      if (respuesta[0].status) {
        $("#eliminar_ProductoSalida").modal("hide");
        //si aplica, se elimina producto de arreglo prodsIdBarCod
        if(barCod == true && cantidad==0){
          //cuenta cuantos productos, del que se poretende eliminar, existen en la tabla si son mas de 1 no lo elimina del arreglo de productos del codigo de barras
          let contador = 0;
          let hiddenRows = tblSalidas.rows().nodes();
          $("input[name='inptCantidad']", hiddenRows).each(function() {
              let arrid = $(this).attr("data-id");
              if (arrid == pkProducto) {
                contador++;
              }       
          });
          if(contador == 1){
            delete prodsIdBarCod[pkProducto];
          }
        }
        recargarTablaSalidas();
      } else {
        //console.log("¡Algo salió mal :(!");
        $("#cantidadModal-" + serieLote).val(0);
      }
    },
    error: function (error) {
      //console.log(error);
      //console.log("¡No se guardaron los cambios con éxito :(!");
    },
  });
}

function editarCantidadDevolucion(
  salidaTemp,
  cantidad,
  IdCuentaPagar,
  pkProducto
) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_salida_cantidad_devolucion_temp",
      data: salidaTemp,
      data2: cantidad,
      data3: IdCuentaPagar,
      data4: pkProducto,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta", respuesta);

      if (respuesta[0].status) {
        recargarTablaSalidas();
      } else {
        //console.log("¡Algo salió mal :(!");
        $("#cantidadModal-" + serieLote).val(0);
      }
    },
    error: function (error) {
      //console.log(error);
      //console.log("¡No se guardaron los cambios con éxito :(!");
    },
  });
}

function recargarTablaSalidas() {
  var ordenPedido = $("#cmbOrderPedido").val();
  var cotizacion = $("#cmbQuote").val();
  var venta = $("#cmbSales").val();
  var ordenPedidoGral = $("#cmbOrderPedidoGral").val();

  var IDOP = 0;

  if (ordenPedido != "" && ordenPedido != null) {
    IDOP = ordenPedido;
  } else if (cotizacion != "" && cotizacion != null) {
    IDOP = cotizacion;
  } else if (venta != "" && venta != null) {
    IDOP = venta;
  } else if (ordenPedidoGral != "" && ordenPedidoGral != null) {
    IDOP = ordenPedidoGral;
  }

  $("#tblSalidaOrdenPedido").DataTable().destroy();
  tblSalidas = $("#tblSalidaOrdenPedido").DataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    searching: false,
    buttons: [],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_productoSalidaOPTempTableEditModal",
        data: IDOP,
      },
    },
    //"pageLength": 20,
    paging: false,
    order: [1, "asc"],
    columns: [
      { data: "Id" },
      { data: "Clave" },
      { data: "Producto" },
      { data: "CantidadPedida" },
      { data: "CantidadSurtida" },
      { data: "CantidadRestante" },
      { data: "Existencias" },
      { data: "CantidadSalida" },
      { data: "Lote" },
      /* { data: "Serie" }, */
      { data: "um" },
      { data: "cb" },
      { data: "Caducidad" },
      { data: "Acciones" },
    ],
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
  });
  flagBarCode();  
  if(barCod == true){
    tblSalidas.one("draw", function () {
      inptCantidadChangeDisable();
    });
  }  
  obtenerTotal(IDOP);
  $("#btnAgregarSalidaOCP").unbind("click", false);
  $("#btnAgregarSalidaGral").unbind("click", false);
  $("#btnAgregarSalidaCoti").unbind("click", false);
  $("#btnAgregarSalidaVenta").unbind("click", false);
}

//se recorre el arreglo de los productos ingresados con el codigo de barras para comprobar que todos hayan sido ingresados, si alguno falta no continua el proceso de registro hasta que se haya ingresado o eliminado.
function Comprueba_Prods_Barcode(){
  if(barCod){
    for (const property in prodsIdBarCod) {
      if(prodsIdBarCod[property] == 0){
        return false;
      }
    }
    return true;
  }else{
    return true;
  }
}

function validaUnidad(orden){
  return new Promise((resolve, reject) => {
    $array = [];
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "get_validUnidadMedida",
        data: orden
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta['status'] == 0) {
          $array['status'] = 0;
          $array['msj'] = respuesta['msj'];
          resolve($array);
        }else{
          $array['status'] = 1;
          resolve($array);
        } 
      },
      error: function (error) {
        $array['status'] = 2;
        resolve($array);
      },
    });
  })
};

$(document).on("click", "#btnAgregarSalidaOCP", function () {
  if (!$("#cmbSurtidorSalida").val()) {
    $("#invalid-surtidorSalida").css("display", "block");
    $("#cmbSurtidorSalida").addClass("is-invalid");
  } else {
    if(Comprueba_Prods_Barcode()){
      if(!tblSalidas
        .column( 6 )
        .data()[0].includes('>0<')){
        console.log('si llega');
        $("#invalid-surtidorSalida").css("display", "none");
        $("#cmbSurtidorSalida").removeClass("is-invalid");
    
        var badSucursalDestino =
          $("#invalid-nombreCom").css("display") === "block" ? false : true;
        var badSurtidor =
          $("#invalid-medioCont").css("display") === "block" ? false : true;
        if (badSucursalDestino && badSurtidor) {
          var ordenPedido = $("#cmbOrderPedido").val();
          var observaciones = $("#txtNoBultosPaquetes").val();
          var surtidor = $("#cmbSurtidorSalida").val();

          validaUnidad(ordenPedido).then(respuesta => {
              if(respuesta['status'] == 0){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/warning_circle.svg",
                  msg: respuesta['msj']+"no contiene(n) Unidad de medida",
                });
              }else if(respuesta['status'] == 2){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "¡Error al validar Unidad de medida!",
                });
              }else{
                $.ajax({
                  url: "../../php/funciones.php",
                  data: {
                    clase: "save_data",
                    funcion: "save_datosSalida_OP",
                    data: ordenPedido,
                    data2: observaciones,
                    data3: surtidor,
                  },
                  dataType: "json",
                  success: function (respuesta) {
                    if (respuesta[0].status) {
                      //console.log("Salida registrada correctamente!");
                      imprimirPDFSalida(respuesta[0].folio, ordenPedido);
                    } else {
                      //console.log("¡Algo salió mal :(!");
                    }
                  },
                  error: function (error) {
                    //console.log("¡Algo salió mal :(! :" + error);
                  },
                });
              }
          });
        } 
      }else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algún(os) producto(s) no tiene(n) existencias.!",
        });
      }
    }else{
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: "¡Algún(o/os) productos no han sido identificados con el código de barras.!",
      });
    }
    
  }
  //imprimirPDFSalida('6-1','32');
});

$(document).on("click", "#btnAgregarSalidaGral", function () {
  if (!$("#cmbSurtidorSalidaGral").val()) {
    $("#invalid-surtidorSalida").css("display", "block");
    $("#cmbSurtidorSalidaGral").addClass("is-invalid");
  } else {
    if(Comprueba_Prods_Barcode()){
      if(!tblSalidas
        .column( 6 )
        .data()[0].includes('>0<')){
        $("#invalid-surtidorSalida").css("display", "none");
        $("#cmbSurtidorSalidaGral").removeClass("is-invalid");
    
        var ordenPedido = $("#cmbOrderPedidoGral").val();
        var observaciones = $("#txtNoBultosPaquetesGral").val();
        var surtidor = $("#cmbSurtidorSalidaGral").val();
    
        validaUnidad(ordenPedido).then(respuesta => {
          if(respuesta['status'] == 0){
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: respuesta['msj']+"no contiene(n) Unidad de medida",
            });
          }else if(respuesta['status'] == 2){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Error al validar Unidad de medida!",
            });
          }else{
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "save_data",
                funcion: "save_datosSalida_OPGral",
                data: ordenPedido,
                data2: observaciones,
                data3: surtidor,
              },
              dataType: "json",
              success: function (respuesta) {
                if (respuesta[0].status) {
                  //console.log("Salida registrada correctamente!");
                  imprimirPDFSalidaGral(respuesta[0].folio, ordenPedido);
                } else {
                  //console.log("¡Algo salió mal :(!");
                }
              },
              error: function (error) {
                //console.log("¡Algo salió mal :(! :" + error);
              },
            });
          }
        });

      }else{
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: "¡Algún(os) producto(s) no tiene(n) existencias.!",
        });
      }
    }else{
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: "¡Algún(o/os) productos no han sido identificados con el código de barras.!",
      });
    }
    
  }
  //imprimirPDFSalidaGral('46-1','132');
});

$(document).on("click", "#btnAgregarSalidaCoti", function () {
  if (!$("#cmbSurtidorSalidaCoti").val()) {
    $("#invalid-surtidorSalidaCoti").css("display", "block");
    $("#cmbSurtidorSalidaCoti").addClass("is-invalid");
  } else {
    if(Comprueba_Prods_Barcode()){
      if(!tblSalidas
        .column( 6 )
        .data()[0].includes('>0<')){
        $("#invalid-surtidorSalidaCoti").css("display", "none");
        $("#cmbSurtidorSalidaCoti").removeClass("is-invalid");
    
        var ordenPedido = $("#cmbQuote").val();
        var observaciones = $("#txtNoBultosPaquetesCoti").val();
        var surtidor = $("#cmbSurtidorSalidaCoti").val();
    
        validaUnidad(ordenPedido).then(respuesta => {
          if(respuesta['status'] == 0){
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: respuesta['msj']+"no contiene(n) Unidad de medida",
            });
          }else if(respuesta['status'] == 2){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Error al validar Unidad de medida!",
            });
          }else{
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "save_data",
                funcion: "save_datosSalida_Coti",
                data: ordenPedido,
                data2: observaciones,
                data3: surtidor,
              },
              dataType: "json",
              success: function (respuesta) {
                if (respuesta[0].status) {
                  //console.log("Salida registrada correctamente!");
                  imprimirPDFSalidaCoti(respuesta[0].folio, ordenPedido);
                } else {
                  //console.log("¡Algo salió mal :(!");
                }
              },
              error: function (error) {
                //console.log("¡Algo salió mal :(! :" + error);
              },
            });
          }
        });

      }else{
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: "¡Algún(os) producto(s) no tiene(n) existencias.!",
        });
      }
    }else{
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: "¡Algún(o/os) productos no han sido identificados con el código de barras.!",
      });
    }
  }
  //imprimirPDFSalidaCoti('6-1','32');
});

$(document).on("click", "#btnAgregarSalidaVenta", function () {
  if (!$("#cmbSurtidorSalidaVenta").val()) {
    $("#invalid-surtidorVenta").css("display", "block");
    $("#cmbSurtidorSalidaVenta").addClass("is-invalid");
  } else {
    if(Comprueba_Prods_Barcode()){
      if(!tblSalidas
        .column( 6 )
        .data()[0].includes('>0<')){
        $("#invalid-surtidorVenta").css("display", "none");
        $("#cmbSurtidorSalidaVenta").removeClass("is-invalid");
    
        var ordenPedido = $("#cmbSales").val();
        var observaciones = $("#txtNoBultosPaquetesVenta").val();
        var surtidor = $("#cmbSurtidorSalidaVenta").val();
    
        validaUnidad(ordenPedido).then(respuesta => {
          if(respuesta['status'] == 0){
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: respuesta['msj']+"no contiene(n) Unidad de medida",
            });
          }else if(respuesta['status'] == 2){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Error al validar Unidad de medida!",
            });
          }else{
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "save_data",
                funcion: "save_datosSalida_Venta",
                data: ordenPedido,
                data2: observaciones,
                data3: surtidor,
              },
              dataType: "json",
              success: function (respuesta) {
                if (respuesta[0].status) {
                  console.log("Salida registrada correctamente!");
                  imprimirPDFSalidaVenta(respuesta[0].folio, ordenPedido);
                } else {
                  //console.log("¡Algo salió mal :(!");
                }
              },
              error: function (error) {
                //console.log("¡Algo salió mal :(! :" + error);
              },
            });
          }
        });

      }else{
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: "¡Algún(os) producto(s) no tiene(n) existencias.!",
        });
      }
    }else{
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: "¡Algún(o/os) productos no han sido identificados con el código de barras.!",
      });
    }
    
  }

  //imprimirPDFSalidaVenta('6-1','32');
});

$(document).on("click", "#btnAgregarSalidaDevolucion", function () {
  if(Comprueba_Prods_Barcode()){
    if(!tblSalidasD
      .column( 6 )
      .data()[0].includes('>0<')){
      var id_cuenta_pagar = $("#cmbNoDocumento").val();
      var observaciones = $("#txtNoBultosPaquetesDevolucion").val();
      var surtidor = $("#cmbSurtidorSalidaDevolucion").val();
    
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosSalida_Devolucion",
          data: id_cuenta_pagar,
          data2: observaciones,
          data3: surtidor,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            //console.log("Salida registrada correctamente!");
            imprimirPDFSalidaDevolucion(respuesta[0].folio, id_cuenta_pagar);
          } else {
            //console.log("¡Algo salió mal :(!");
          }
        },
        error: function (error) {
          //console.log("¡Algo salió mal :(! :" + error);
        },
      });
    }else{
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: "¡Algún(os) producto(s) no tiene(n) existencias.!",
      });
    }
  }else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡Algún(o/os) productos no han sido identificados con el código de barras.!",
    });
  }
  

  //imprimirPDFSalidaDevolucion('DV000001-1','173');
});

function imprimirPDFSalida(folio, ordenPedido) {
  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../../../img/timdesk/checkmark.svg",
    msg: "¡Se registró exitosamente la salida.!",
    sound: "../../../../../sounds/sound4",
  });

  setTimeout(function () {
    window.location.href = "../salidas_productos";
  }, 1000);

  window.open(
    "functions/descargar_Salida.php?folio=" + folio + "&orden=" + ordenPedido
  );
}

function imprimirPDFSalidaGral(folio, ordenPedido) {
  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../../../img/timdesk/checkmark.svg",
    msg: "¡Se registró exitosamente la salida.!",
    sound: "../../../../../sounds/sound4",
  });

  setTimeout(function () {
    window.location.href = "../salidas_productos";
  }, 1000);

  window.open(
    "functions/descargar_SalidaGral.php?folio=" +
      folio +
      "&orden=" +
      ordenPedido
  );
}

function imprimirPDFSalidaCoti(folio, ordenPedido) {
  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../../../img/timdesk/checkmark.svg",
    msg: "¡Se registró exitosamente la salida.!",
    sound: "../../../../../sounds/sound4",
  });

  /* setTimeout(function () {
    window.location.href = "../salidas_productos";
  }, 1000); */

  window.open(
    "functions/descargar_SalidaCoti.php?folio=" +
      folio +
      "&orden=" +
      ordenPedido
  );
}

function imprimirPDFSalidaVenta(folio, ordenPedido) {
  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../../../img/timdesk/checkmark.svg",
    msg: "¡Se registró exitosamente la salida.!",
    sound: "../../../../../sounds/sound4",
  });

  setTimeout(function () {
    window.location.href = "../salidas_productos";
  }, 1000); 

  window.open(
    "functions/descargar_SalidaVenta.php?folio=" +
      folio +
      "&orden=" +
      ordenPedido
  );
}

function imprimirPDFSalidaDevolucion(folio, id_cuenta_pagar) {
  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../../../img/timdesk/checkmark.svg",
    msg: "¡Se registró exitosamente la salida.!",
    sound: "../../../../../sounds/sound4",
  });

  setTimeout(function () {
    window.location.href = "../salidas_productos";
  }, 1000);

  window.open(
    "functions/descargar_SalidaDevolucion.php?folio=" +
      folio +
      "&cuenta=" +
      id_cuenta_pagar
  );
}

function openModalDelete(idSalidaTemp, idProducto) {
  $("#exitTempIDD").val(idSalidaTemp);
  $("#ProductoTempIDD").val(idProducto);
  $("#eliminar_ProductoSalida").modal("show");
}

function deleteProductoSalida(idSalidaTemp) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_exit_Remove_TempTable",
      data: idSalidaTemp,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      if (respuesta[0].status) {
        recargarTablaSalidas();
      }
    },
    error: function (error) {
      //console.log(error);
    },
  });
}

function obtenerTotal(pkOrden) {
  //Obtener total
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_totalSalidaOP", datos: pkOrden },
    dataType: "json",
    success: function (respuesta) {
      $("#Total").html(dosDecimales(respuesta[0].salida));
      //console.log("SALIDA: " + respuesta[0].salida);
    },
    error: function (error) {
      //console.log(error);
    },
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}
