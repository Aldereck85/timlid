var _permissionsFacturar = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};
var destinatarios = "";

var estatusOP = 0;
var estatusFacturaid = 0;
var isInventario = 0;
var numTiposProd = 0;
var tipoProd = 0;
var isServicio = 0;
var ordenPedidoIdGlobal = 0;
var referenciaVD = "";

$(document).ready(function () {
  var PKVenta = $("#txtPKVenta").val();

  cargarTablaVentasDirectasVer(PKVenta); //ventas.js

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_VentaDirectaEdit",
      data: PKVenta,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log({ respuesta });
      $("#txtReferencia").text(respuesta[0].Referencia);
      $("#txtSucursal").text(respuesta[0].sucursal);
      $("#txtCliente").html(
        '<a style="cursor:pointer" href="../../../clientes/catalogos/clientes/detalles_cliente.php?c=' +
          respuesta[0].FKCliente +
          '">' +
          respuesta[0].razon_social +
          "</a>"
      );
      $("#txtDomi").text(respuesta[0].domicilio);
      $("#txtVendedor").text(respuesta[0].Vendedors);
      $("#txtPKVentaEncrip").val(respuesta[0].PKVentaDirecta);
      $("#txtFechaEmision").text(respuesta[0].FechaCreacion);
      $("#txtFechaEstimada").text(respuesta[0].FechaVencimiento);
      $("#NotasCliente").text(respuesta[0].NotasCliente);
      $("#NotasInternas").text(respuesta[0].NotasInternas);

      var condicionPago;
      if (respuesta[0].CondicionPago == "1") {
        condicionPago = "Contado";
      } else if (respuesta[0].CondicionPago == "2") {
        condicionPago = "Crédito";
      } else {
        condicionPago = "Sin especificar";
      }
      $("#txtCondicionPago").text(condicionPago);

      respuesta[0].moneda =
        respuesta[0].moneda == "0" ? "No seleccionada" : respuesta[0].moneda;
      $("#txtmoneda").text(respuesta[0].moneda);

      /* respuesta[0].Importe = Math.round((parseFloat(respuesta[0].Importe) + Number.EPSILON) * 100) / 100;
      respuesta[0].Importe=respuesta[0].Importe.toFixed(2); */

      //da formato al numero
      /* let parts = respuesta[0].Importe.toString().split("."); */
      // Si no tenia decimales le pone 00
      /* parts[1] = parts[1] == undefined ? "00" : parts[1]; */
      // Si tenia solo un decimal le agrega un 0 al final
      /* parts[1] = parts[1].length == 1 ? parts[1] + "0" : parts[1];
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      let union = parts.join("."); */

      if (respuesta[0].ordenPedidoId != "0") {
        let htmlOrden = `<b class="textBlue">Pedido: </b><a href="../../../pedidos/detallePedido.php?id=${respuesta[0].ordenPedidoId}">${respuesta[0].folioOP}</a>`;
        $("#orderPedidoID").html(htmlOrden);
        ordenPedidoIdGlobal = respuesta[0].ordenPedidoId;
      } else {
        folio_ticket = document.getElementById("txtFolioTicketHide").value;
        if (folio_ticket != "" && folio_ticket != null) {
          let htmlOrden = `<b class="textBlue">Folio ticket: </b>${folio_ticket}`;
          $("#orderPedidoID").html(htmlOrden);
        }
      }

      estatusOP = respuesta[0].EstatusOP;
      estatusFacturaid = respuesta[0].estatus_factura_id;
      isInventario = respuesta[0].IsInventario;
      numTiposProd = respuesta[0].numTiposProd;
      tipoProd = respuesta[0].tipoProdVenta;
      isServicio = respuesta[0].isServicio;

      referenciaVD = respuesta[0].Referencia;

      /*       if (
        ((respuesta[0].FKEstatusVenta == "1" ||
        respuesta[0].FKEstatusVenta == "2")  &&
        respuesta[0].Estatus == "1") ||
        respuesta[0].IsInventario == "0"
      ) {
        var html = `Estatus: <span data-toggle="modal" class="btn-table-custom btn-table-custom--green" name="btnAceptarOC">
                      <i class="far fa-check-square"></i> Nueva</span>`;

        var html2 = `<span data-toggle="modal" class="btn-table-custom btn-table-custom--red" name="btnCancelarOC"
                      onclick="cambiarEstatusVentaDirecta(5);"><i class="fas fa-trash-alt"></i> Cancelar</span>`;

        var html3 = `<span class="btn-table-custom btn-table-custom--blue-light" name="btnEditarOC"
                      onclick="obtenerEditar();"><i class="fas fa-edit"></i> Editar</span>`;

        $("#btnAceptar").html(html);
        $("#btnCancelar").html(html2);
        $("#btnEditar").html(html3);
      } else {
        var text = "";
        var color = "";
        if (
          respuesta[0].FKEstatusVenta == "9" ||
          respuesta[0].FKEstatusVenta == "10"
        ) {
          text = "Facturada";
          color = "#36b9cc";
        } else if (
          respuesta[0].FKEstatusVenta == "3" ||
          respuesta[0].FKEstatusVenta == "4"
        ) {
          text = "Parcialmente surtida";
          color = "btn-table-custom--yellow";
        } else if (respuesta[0].FKEstatusVenta == "7") {
          text = "Cerrada";
          color = "btn-table-custom--red";
        } else if (respuesta[0].FKEstatusVenta == "8") {
          text = "Cancelada";
          color = "btn-table-custom--red";
        } else if (respuesta[0].FKEstatusVenta == "5") {
          text = "Surtido completa";
          color = "btn-table-custom--green";
        } else if (respuesta[0].FKEstatusVenta == "6") {
          text = "Facturada pendiente";
          color = "btn-table-custom--turquoise";
        }

        var html = `Estatus: <span class="btn-table-custom ${color}" name="btnAgregarProducto">
                      <i class="far fa-check-square"></i> ${text}</span>`;

        var html2 = `<span class="btn-table-custom btn-table-custom" name="btnCancelarOC" title="La venta ha sido ${text}">
                        <i class="fas fa-trash-alt"></i> Cancelar</span>`;

        var html3 = `<span class="btn-table-custom btn-table-custom" name="btnEditarOC" title="La venta ha sido ${text}">
                        <i class="fas fa-edit"></i> Editar</span>`;

        $("#btnAceptar").html(html);
        $("#btnCancelar").html(html2);
        $("#btnEditar").html(html3);
      } */
      var edit = false;
      var text = "";
      var color = "";
      var textF = "";
      var colorF = "";
      //Si NO es de inventariso
      if (respuesta[0].IsInventario == "0") {
        if (respuesta[0].Estatus == "1") {
          text = "Nueva";
          color = "btn-table-custom--green";
          edit = true;
        } else if (respuesta[0].Estatus == "2") {
          text = "Facturada";
          color = "#36b9cc";
        } else if (respuesta[0].Estatus == "3") {
          text = "Parcialmente surtida";
          color = "btn-table-custom--yellow";
        } else if (respuesta[0].Estatus == "4") {
          text = "Surtida Completa";
          color = "btn-table-custom--green";
        } else if (respuesta[0].Estatus == "5") {
          text = "Cerrada";
          color = "btn-table-custom--red";
        } else if (respuesta[0].Estatus == "6") {
          text = "Factura Pendiente";
          color = "btn-table-custom--turquoise";
          edit = true;
        }
      } else {
        if (
          respuesta[0].FKEstatusVenta == "9" ||
          respuesta[0].FKEstatusVenta == "10"
        ) {
          text = "Facturada";
          color = "#36b9cc";
        } else if (
          respuesta[0].FKEstatusVenta == "3" ||
          respuesta[0].FKEstatusVenta == "4"
        ) {
          text = "Parcialmente surtida";
          color = "btn-table-custom--yellow";
        } else if (
          respuesta[0].FKEstatusVenta == "1" ||
          respuesta[0].FKEstatusVenta == "2"
        ) {
          text = "Nueva";
          color = "btn-table-custom--green";
          edit = true;
        } else if (respuesta[0].FKEstatusVenta == "7") {
          text = "Cerrada";
          color = "btn-table-custom--red";
        } else if (respuesta[0].FKEstatusVenta == "8") {
          text = "Cancelada";
          color = "btn-table-custom--red";
        } else if (respuesta[0].FKEstatusVenta == "5") {
          text = "Surtido completa";
          color = "btn-table-custom--green";
        } else if (respuesta[0].FKEstatusVenta == "6") {
          text = "Factura pendiente";
          color = "btn-table-custom--turquoise";
          edit = true;
        }
      }

      if (estatusOP == "1") {
        text = "Nuevo";
        color = "btn-table-custom--gray";
      } else if (estatusOP == "2") {
        text = "Nuevo-FD";
        color = "btn-table-custom--gray";
      } else if (estatusOP == "3") {
        text = "Parcialmente surtido";
        color = "btn-table-custom--yellow";
      } else if (estatusOP == "4") {
        text = "Parcialmente surtido-FD";
        color = "btn-table-custom--yellow";
      } else if (estatusOP == "5") {
        text = "Surtido completo";
        color = "btn-table-custom--turquoise";
      } else if (estatusOP == "6") {
        text = "Surtido completo-FD";
        color = "btn-table-custom--green";
      } else if (estatusOP == "7") {
        text = "Cerrado";
        color = "btn-table-custom--red";
      } else if (estatusOP == "8") {
        text = "Cancelado";
        color = "btn-table-custom--red";
      } else if (estatusOP == "9") {
        text = "Facturado-directo";
        color = "btn-table-custom--turquoise";
      } else if (estatusOP == "10") {
        text = "Facturado-almacen";
        color = "btn-table-custom--turquoise";
      } else if (estatusOP == "11") {
        text = "Remisionado parcial";
        color = "btn-table-custom--orange";
      } else if (estatusOP == "12") {
        text = "Remisionado completo";
        color = "btn-table-custom--orange";
      }

      if (estatusFacturaid == "1") {
        textF = "Facturado completo";
        colorF = "btn-table-custom--turquoise";
      } else if (estatusFacturaid == "2") {
        textF = "Facturado directo";
        colorF = "btn-table-custom--turquoise";
      } else if (estatusFacturaid == "3") {
        textF = "Pendiente de facturar";
        colorF = "btn-table-custom--yellow";
      } else if (estatusFacturaid == "4") {
        textF = "Pendiente de facturar directo";
        colorF = "btn-table-custom--yellow";
      } else if (estatusFacturaid == "5") {
        textF = "Parcialmente facturado almacén";
        colorF = "btn-table-custom--green";
      } else if (estatusFacturaid == "6") {
        textF = "Cancelado";
        colorF = "btn-table-custom--red";
      } else if (estatusFacturaid == "7") {
        textF = "Remisionado parcial";
        colorF = "btn-table-custom--orange";
      } else if (estatusFacturaid == "8") {
        textF = "Remisionado completo";
        colorF = "btn-table-custom--orange";
      } else if (estatusFacturaid == "9") {
        textF = "Facturado de remision parcial";
        colorF = "btn-table-custom--gray";
      } else if (estatusFacturaid == "10") {
        textF = "Facturado de remision completo";
        colorF = "btn-table-custom--dark";
      }
      console.log(edit);
      // Pone los elementos habilidatos
      //modificado: si no esta facturado y no tiene pagos la venta, entra
      if (edit && respuesta[0].estatus_cuentaCobrar == 1) {
        var html = `<b class="btn-table-custom--turquoise">Estatus del pedido:</b> <span data-toggle="modal" class="btn-table-custom ${color}" name="btnAceptarOC">
        <img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-ACEPTAR VERDE NVO-01.svg"></img>${text}</span>
        <b class="btn-table-custom--turquoise">Estatus de la factura:</b> <span data-toggle="modal" class="btn-table-custom ${colorF}" name="btnAceptarOC">
        <img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-ACEPTAR VERDE NVO-01.svg"></img>${textF}</span>`;

        var html2 = `<span data-toggle="modal" class="btn-table-custom btn-table-custom--red" name="btnCancelarOC"
                    onclick="cambiarEstatusVentaDirecta(5);"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img>Cancelar</span>`;

        var html3 = `<span class="btn-table-custom btn-table-custom--blue-light" name="btnEditarOC"
                    onclick="obtenerEditar();"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-EDITAR VERDE OSCURO NVO-01.svg"></img>Editar</span>`;

        var html4 = `<span class="btn-table-custom btn-table-custom--red" name="btnEliminarOC"
                    data-toggle="modal" data-target="#eliminar_VentaDirecta" onclick="mostrarIdEliminar($('#txtPKVenta').val(),referenciaVD)" >
                   <i class="fa fa-times-circle"></i> Eliminar Venta</span>`;
      } else {
        var html = `<b class="btn-table-custom--turquoise">Estatus del pedido:</b> <span class="btn-table-custom ${color}" name="btnAgregarProducto">
        <img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-ACEPTAR VERDE NVO-01.svg"></img>${text}</span>
        <b class="btn-table-custom--turquoise">Estatus de la factura:</b> <span data-toggle="modal" class="btn-table-custom ${colorF}" name="btnAceptarOC">
        <img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-ACEPTAR VERDE NVO-01.svg"></img>${textF}</span`;

        var html2 = `<span class="btn-table-custom btn-table-custom" name="btnCancelarOC" title="La venta ha sido ${text}">
        <img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img>Cancelar</span>`;

        var html3 = `<span class="btn-table-custom btn-table-custom" name="btnEditarOC" title="La venta ha sido ${text}">
        <img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-EDITAR VERDE OSCURO NVO-01.svg"></img>Editar</span>`;

        var html4 = `<span class="btn-table-custom btn-table-custom" name="btnEliminarOC" title="La venta ha sido ${text}">
        <i class="fa fa-times-circle"></i> Eliminar Venta</span>`;
      }

      //si ya está facturada permite copiarla
      if (
        estatusFacturaid == "1" ||
        estatusFacturaid == "2" ||
        estatusFacturaid == "9" ||
        estatusFacturaid == "10"
      ) {
        var html5 =
          '<span data-toggle="modal" data-target="#copyVenta" class="btn-table-custom btn-table-custom--blue" name="btnDescargarOC"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-COPIAR-AZUL-NVO.svg"></img>Copiar venta</span>';
        var html4 = "";
      }
      console.log(estatusFacturaid);
      console.log(estatusOP);
      //Se valida que solo se puedan eliminar ventas con pedidos nuevos o cancelados
      if (estatusOP != "1" && estatusOP != "2" && estatusOP != "8") {
        var html4 = "";
      }

      $("#btnAceptar").html(html);
      //$("#btnCancelar").html(html2);
      $("#btnEditar").html(html3);

      $("#btnEliminar").html(html4);
      $("#btnCopiar").html(html5);

      //redirecciona al modulo de recepcion de pagos
      $("#link-recepecion-pagos").html(
        '<a style="cursor:pointer; padding-right:1.5rem" id="btnPagos" class="btn-table-custom btn-table-custom--blue"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/facturacion/aplicar_pago.svg"> Registrar pago</a>'
      );
      $("#btnPagos").on("click", function (e) {
        cargar_moduloPagos(respuesta[0].id);
      });
    },
    error: function (error) {
      console.log(error);
    },
    complete: function (_, __) {
      validate_Permissions(13);
    },
  });

  obtenerTotal();

  emailRegex =
    /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
  destinatarios = new SlimSelect({
    select: "#txtDestino",
    placeholder: "Seleccione el/los destinatarios",
    addable: function (value) {
      if (!emailRegex.test(value)) {
        return false;
      }
      return {
        text: value,
        value: value.toLowerCase(),
      };
    },
  });
});

$(document).on("click", "#txtOrdenPedido", function () {
  window.location.href = `../../../pedidos/detallePedido.php?id=${ordenPedidoIdGlobal}`;

  /*$().redirect("../../../pedidos/detallePedido.php?id=", {
    id: ordenPedidoIdGlobal,
  });*/
});

function cargarCMBVendedor(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vendedor", data: data },
    dataType: "json",
    success: function (respuesta) {
      $("#" + input).text(respuesta[0].Nombre);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBDireccionesEnvio(data, input, cliente) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_cmb_direccionesEnvio",
      data: cliente,
    },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        html += `<option value="${respuesta[i].id}">${respuesta[i].sucursal}</option>`;
      });

      $("#" + input + "").html(html);
      $("#" + input + "").val(data);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cambioCliente(valor) {
  $("#chkcmbTodoProducto").prop("disabled", false);
  console.log("Valor:" + valor);
  loadComboProd("", "cmbProducto", "producto", valor, "producto");
  $("#chkcmbTodoProducto").on("change", function () {
    if (this.checked) {
      Swal.fire(
        "Al cliente seleccionado no se le venden los productos listados",
        "Para agregarlos a la lista de los productos que se le venden, favor completar los campos.",
        "success"
      );
      html = `<br>
              <div class="col-lg-6">
                <label for="usr">Precio especial para el cliente:*</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="text" class="form-control numericDecimal-only" maxlength="10"
                    name="txtPrecioUnitarioEspecial" id="txtPrecioUnitarioEspecial" >
                </div>
              </div>`;
      $("#datosNew").html(html);
      $("#txtPrecioUnitario").prop("disabled", false);
      loadComboProd("", "cmbProducto", "producto", valor, "todoProducto");
    } else {
      $("#txtPrecioUnitario").prop("disabled", true);
      loadComboProd("", "cmbProducto", "producto", valor, "producto");
      html = ``;
      $("#datosNew").html(html);
    }
  });
  loadComboProd("", "cmbProducto", "producto", valor);
  $("#cmbProducto").on("change", function () {
    var prod = $("#cmbProducto").val();
    var all = 0;
    if ($("#chkcmbTodoProducto").is(":checked")) {
      all = 1;
    } else {
      all = 0;
    }

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_precioCliente",
        value: valor,
        value1: prod,
        value2: all,
      },
      dataType: "json",
      success: function (respuesta) {
        //Activar el comoboe ingresar los datos respectivos
        if ($("#chkcmbTodoProducto").is(":checked")) {
          //$('#txtPrecioUnitario').val(0);
          $("#txtPrecioUnitario").val(respuesta[0].Precio);
        } else {
          if (respuesta[0].Precio != null) {
            $("#txtPrecioUnitario").val(respuesta[0].Precio);
          }
        }
        $("#txtCantidad").val(0);
        $("#txtCantidadHis").val(1);
        $("#txtCantidad").prop("min", 1);
      },
      error: function (error) {
        console.log(error);
      },
    });

    cambioSucursal();
  });
}

function cambioSucursal() {
  var pkSucursal = $("#cmbDireccionEnvio").val();
  console.log("Sucursal:" + pkSucursal);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_SucursalInventario",
      data: pkSucursal,
    },
    dataType: "json",
    success: function (respuesta) {
      var html = ``;

      if (parseInt(respuesta[0]["activo"]) === 1) {
        console.log("Posee inventarios Func");
        /*html = `<button class="btn-custom btn-custom--blue"
                style="position: relative; top: 32px;width: 100%;" type="button" id="verInventario"
                name="verInventario" onclick="verInventario()">Ver inventario</button>`*/

        html = `<div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">Cantidad:*</label>
                      <input type="number" class="form-control numeric-only txtCantidad" maxlength="8"
                        name="txtCantidad" id="txtCantidad" value="0" onchange="validEmptyInput('txtCantidadExistencia', 'invalid-existencia', 'No se poseen existencias en la sucursal.')">
                      <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                    </div>
                    <div class="col-lg-6">
                      <label for="usr">Stock en sucursal:</label>
                      <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtCantidadExistencia" id="txtCantidadExistencia" disabled="disabled" value="0">
                      <div class="invalid-feedback" id="invalid-existencia">No se poseen existencias en la sucursal.</div>
                    </div>
                  </div>
                </div>`;

        mostrarStock(pkSucursal);
      } else {
        console.log("No tiene inventarios Func");
        html = `<div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Cantidad:*</label>
                      <input type="number" class="form-control numeric-only txtCantidad" maxlength="8"
                        name="txtCantidad" id="txtCantidad" value="0" onchange="validEmptyInput('txtCantidadExistencia', 'invalid-existencia', 'No se poseen existencias en la sucursal.')">
                      <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                    </div>
                  </div>
                </div>`;
      }
      //$("#verInventarioSuc").html(html);
      $("#inventarioStock").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function mostrarStock(pkSucursal) {
  var pkProducto = $("#cmbProducto").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_InventarioSucursal",
      data: pkSucursal,
      data2: pkProducto,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].isServicio == "5") {
        var html = `<div class="form-group">
                      <div class="row">
                        <div class="col-lg-12">
                          <label for="usr">Cantidad:*</label>
                          <input type="number" class="form-control numeric-only txtCantidad" maxlength="8"
                            name="txtCantidad" id="txtCantidad" value="0" onchange="validEmptyInput('txtCantidadExistencia', 'invalid-existencia', 'No se poseen existencias en la sucursal.')">
                          <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                        </div>
                      </div>
                    </div>`;
        $("#inventarioStock").html(html);
      } else {
        $("#txtCantidadExistencia").val(respuesta[0].StockExistencia);
        validEmptyInput(
          "txtCantidadExistencia",
          "invalid-existencia",
          "No se poseen existencias en la sucursal."
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function agregarProd() {
  //Obtener valores de los campos
  var idproducto = $("#cmbProducto").val();
  var pkVentaDirecta = $("#txtPKVenta").val();
  var cantidad = parseInt($("#txtCantidad").val());
  var pkCliente = $("#cmbCliente").val();
  var precio = $("#txtPrecioUnitario").val();
  var precioEsp = 0;
  if ($("#chkcmbTodoProducto").is(":checked")) {
    precioEsp = $("#txtPrecioUnitarioEspecial").val();
  }

  //inicio alertas
  var alerta = "";
  if ($("#cmbProducto").val() === 0) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe seleccionar un producto" +
      "</div>";
  } else if (!$("#txtPrecioUnitario").val()) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar por lo menos un producto" +
      "</div>";
  } else if (!$("#txtCantidad").val()) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar la cantidad" +
      "</div>";
  } else if (!$("#cmbDireccionEnvio").val()) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar una sucursal" +
      "</div>";
  } else if ($("#txtCantidad").val() < 1) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar una cantidad mayor a cero" +
      "</div>";
  } else if (
    parseInt($("#txtCantidad").val()) < parseInt($("#txtCantidadHis").val())
  ) {
    //Validar producto
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_productoVentaDirectaEdit",
        data: idproducto,
        data2: pkVentaDirecta,
        data3: pkCliente,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta nombre valida: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) === 1) {
          validarYGuardarProducto(
            idproducto,
            pkVentaDirecta,
            cantidad,
            pkCliente,
            precio,
            precioEsp
          );
          console.log("¡Ya existe!");
        } else {
          alerta =
            '<div class="alert alert-warning" role="alert">' +
            "Debe ingresar una cantidad mayor a 0." +
            "</div>";

          $("#txtCantidad").val($("#txtCantidadHis").val());

          console.log("¡No existe!");

          $("#alertas").html(alerta);
        }
      },
    });
  } else if (
    $("#chkcmbTodoProducto").is(":checked") &&
    !$("#txtPrecioUnitarioEspecial").val()
  ) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar un especial del producto para el cliente" +
      "</div>";
  } else {
    validarYGuardarProducto(
      idproducto,
      pkVentaDirecta,
      cantidad,
      pkCliente,
      precio,
      precioEsp
    );
  }

  $("#alertas").html(alerta);

  $("#invalid-existencia").css("display", "none");
  $("#invalid-existencia").text("");
  $("#txtCantidadExistencia").removeClass("is-invalid");
}

function validarYGuardarProducto(
  idproducto,
  pkVentaDirecta,
  cantidad,
  pkCliente,
  precio,
  precioEsp
) {
  //Validar producto
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_productoVentaDirectaEdit",
      data: idproducto,
      data2: pkVentaDirecta,
      data3: pkCliente,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) === 1) {
        Swal.fire({
          title:
            '<h3 style="arialRoundedEsp;">El producto ya se encuentra agregado<h3>',
          html: '<h5 style="arialRoundedEsp;">¿Desea agregar la nueva cantidad a la ya existente?.<h5>',
          icon: "success",
          showConfirmButton: true,
          focusConfirm: false,
          showCloseButton: false,
          showCancelButton: true,
          confirmButtonText:
            'Si   <i class="far fa-arrow-alt-circle-right"></i>',
          cancelButtonText: 'No   <i class="far fa-times-circle"></i>',
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            var element = document.getElementById("content");
            element.scrollIntoView();
            //actualización de datos a tabla
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_venta_directaEdit",
                datos: idproducto,
                datos2: cantidad,
                datos3: pkVentaDirecta,
                datos4: pkCliente,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log("respuesta agregar venta directa:", respuesta);

                if (respuesta[0].status) {
                  $("#tblListadoVentasDirectasEdit").DataTable().ajax.reload();
                  obtenerTotal();
                  Swal.fire(
                    "Actualización exitosa",
                    "Se actualizó la cantidad del producto en la orden de venta con exito",
                    "success"
                  );
                } else {
                  Swal.fire(
                    "Error",
                    "No se actualizó la cantidad del producto en la orden de venta con exito",
                    "warning"
                  );
                }
              },
              error: function (error) {
                console.log(error);
              },
            });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            /*No hacer nada*/
          } else {
            /*No hacer nada*/
          }
        });

        console.log("¡Ya existe!");
      } else {
        /*Agregar producto a la vetna directa*/
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_venta_directaEdit",
            datos: idproducto,
            datos2: cantidad,
            datos3: pkVentaDirecta,
            datos4: pkCliente,
            datos5: precio,
            datos6: precioEsp,
          },
          dataType: "json",
          success: function (respuesta) {
            console.log("respuesta agregar venta directa:", respuesta);

            if (respuesta[0].status) {
              $("#tblListadoVentasDirectasEdit").DataTable().ajax.reload();

              Swal.fire(
                "Registro exitoso",
                "Se guardo el producto en la orden de venta con exito",
                "success"
              );
              obtenerTotal();
            } else {
              Swal.fire(
                "Error",
                "No se guardó el producto en la orden de venta con exito",
                "warning"
              );
            }
          },
          error: function (error) {
            console.log(error);
          },
        });

        console.log("¡No existe!");
      }
    },
  });
  if ($("#chkcmbTodoProducto").is(":checked")) {
    $("#txtPrecioUnitarioEspecial").val("");
    loadComboProd(
      "",
      "cmbProducto",
      "producto",
      $("#cmbCliente").val(),
      "todoProducto"
    );
  } else {
    loadComboProd(
      "0",
      "cmbProducto",
      "producto",
      $("#cmbCliente").val(),
      "producto"
    );
  }
  //$('#chkcmbTodoProducto').prop('checked',false);
  $("#txtPrecioUnitario").val("");
  $("#cmbCliente option:not(:selected)").remove();
  $("#txtCantidad").val("");
}

function enviarVentaDirecta() {
  var alerta = "";
  var table = $("#tblListadoVentasDirectasEdit").DataTable();

  console.log("EJECUTANDO::::::::::::::");
  if (!$("#cmbCliente").val()) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar un cliente" +
      "</div>";
  } else if (!$("#cmbDireccionEnvio").val()) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar una sucursal desde donde se realizará la entrega" +
      "</div>";
  } else if (!$("#txtFechaEstimada").val()) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar una fecha de vencimiento" +
      "</div>";
  } else if (!$("#cmbVendedor").val()) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar un vendedor" +
      "</div>";
  } else if (!table.data().count()) {
    alerta =
      '<div class="alert alert-warning" role="alert">' +
      "Debe ingresar por lo menos un producto" +
      "</div>";
  } else {
    var referencia = $("#txtReferencia").val();
    var fechaEmision = $("#txtFechaEmision").val();
    var fechaVencimiento = $("#txtFechaEstimada").val();
    var cliente = $("#cmbCliente").val();
    var direccionEntrega = $("#cmbDireccionEnvio").val();

    var datasplit = $("#Total").html().split(",");
    var importeBetha = "";
    for (var i = 0; i < datasplit.length; i++) {
      importeBetha += datasplit[i];
    }
    var importe = parseFloat(importeBetha);

    var datasplit2 = $("#Subtotal").html().split(",");
    var subtotalBetha = "";
    for (var i = 0; i < datasplit2.length; i++) {
      subtotalBetha += datasplit2[i];
    }
    var subtotal = parseFloat(subtotalBetha);

    var pkVentaDirecta = $("#txtPKVenta").val();
    var notasInternas = $("#NotasInternas").val();
    var notasCliente = $("#NotasCliente").val();
    var vendedor = $("#cmbVendedor").val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_VentaDirecta",
        datos: referencia,
        datos2: fechaEmision,
        datos3: fechaVencimiento,
        datos4: cliente,
        datos5: direccionEntrega,
        datos6: importe,
        datos7: pkVentaDirecta,
        datos8: notasInternas,
        datos9: notasCliente,
        datos10: vendedor,
        datos11: subtotal,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Orden de venta registrada correctamente!",
            sound: "../../../../../sounds/sound4",
          });

          setTimeout(function () {
            window.location.href = "../ventas";
          }, 1500);
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: "../../../../../sounds/sound4",
          });
        }
      },
    });
  }
  if (alerta != "") {
    $("#alertas").html(alerta);
  }

  /*$('#modal_envio').load('functions/modal_envio.php?id='+$('#cmbCliente').val()+'&txtId='+14+'&estatus=0&txtNotas='+$('#NotasCliente').val(), function(){
    $('#datos_envio').modal('show');
  });*/
}

function obtenerIdVentaDirectaEditEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_VentaDirectaEdit",
      data: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar producto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoVentasDirectasEdit").DataTable().ajax.reload();
        obtenerTotal();
        Swal.fire(
          "Eliminación exitosa",
          "Se eliminó el producto de la orden con exito",
          "success"
        );
      } else {
        Swal.fire(
          "Error",
          "No se eliminó el producto de la orden con exito",
          "warning"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

var controladorTiempo = "";

function validarCantidad(id) {
  clearTimeout(controladorTiempo);
  controladorTiempo = setTimeout(validarCant(id), 3000);
}

function validarCant(id) {
  valor = $("#cantidad-" + id).val();
  if (
    parseInt($("#cantidad-" + id).val()) < 1 ||
    $("#cantidad-" + id).val() == ""
  ) {
    $("#notaCantidad-" + id).css("display", "block");
    $("#notaCantidad-" + id).prop("title", "La cantidad debe de ser mayor a 0");
  } else {
    $("#notaCantidad-" + id).css("display", "none");

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_VentaDirectaEdit_Cantidad",
        datos: id,
        datos2: valor,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(
          "respuesta editar datos de cantidad de orden de compra:",
          respuesta
        );

        if (respuesta[0].status) {
          console.log("Actualización exitosa");
          $("#tblListadoVentasDirectasEdit").DataTable().ajax.reload();
          obtenerTotal();
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}

function loadCombo(data, input, name, value, fun) {
  if (input == "cmbDireccionEnvio") {
    var html =
      '<option value="0" disabled selected hidden>Seleccione una ' +
      name +
      "...</option>";
  } else {
    var html =
      '<option value="0" disabled selected hidden>Seleccione un ' +
      name +
      "...</option>";
  }

  var oculto;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_" + fun + "Combo", value: value },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta !== "" && respuesta !== null && respuesta.length > 0) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKData) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKData +
            '" ' +
            selected +
            ">" +
            respuesta[i].Data +
            "</option>";
          if (respuesta[i].Oculto !== "") {
            oculto = respuesta[i].Oculto;
          }
        });
      } else {
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (oculto !== "") {
        $("#unidadMedida").val(oculto);
      }

      //$("#cmbCliente").prop("disabled",true);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadComboProd(data, input, name, value, fun) {
  var html =
    '<option value="0" disabled selected hidden>Seleccione un ' +
    name +
    "...</option>";

  var oculto;

  var PKVenta = $("#txtPKVenta").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_" + fun + "ComboEdit",
      value: value,
      value2: PKVenta,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta !== "" && respuesta !== null && respuesta.length > 0) {
        $.each(respuesta, function (i) {
          if (data === respuesta[i].PKData) {
            selected = "selected";
          } else {
            selected = "";
          }
          html +=
            '<option value="' +
            respuesta[i].PKData +
            '" ' +
            selected +
            ">" +
            respuesta[i].Data +
            "</option>";
          if (respuesta[i].Oculto !== "") {
            oculto = respuesta[i].Oculto;
          }
        });
      } else {
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }

      $("#" + input + "").html(html);
      if (oculto !== "") {
        $("#unidadMedida").val(oculto);
      }

      //$("#cmbCliente").prop("disabled",true);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function lobby_notify(string, icono, classStyle, carpeta) {
  Lobibox.notify(classStyle, {
    size: "mini",
    rounded: true,
    delay: 4000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: false,
    img: "../../../../img/" + carpeta + icono,
    msg: string,
    sound: "../../../../../sounds/sound4",
  });

  return;
}

var Total = 0.0;
function obtenerTotal() {
  var PKVenta = $("#txtPKVenta").val();
  Total = 0.0;
  var Termino = false;
  //Obtener subtotal
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_subTotalVentaDirectaVer",
      datos: PKVenta,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Subtotal").html(dosDecimales(respuesta[0].subtotal));
      Total += respuesta[0].subtotal;
      $("#Total").html(dosDecimales(Total));
    },
    error: function (error) {
      console.log(error);
    },
  });

  var html = "",
    ieps,
    iva;
  $("#impuestos").html(html);
  //Obtener impuestos
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_impuestoVentaDirectaEdit_v2",
      datos: PKVenta,
      datos2: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      //Recorrer las respuestas de la consulta
      var tasa = "";
      $.each(respuesta, function (i) {
        if (
          !$("#impuestos-head-" + respuesta[i].pkImpuesto + respuesta[i].tasa)
            .length
        ) {
          if (respuesta[i].tasa == "" || respuesta[i].tasa == null) {
            tasa = respuesta[i].tasa;
          } else {
            tasa = respuesta[i].tasa + "%";
          }

          html +=
            /* "<tr>" +
            //'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+ <--
            '<td style="text-align: right; color: var(--color-primario); font-weight: bold;" id="impuestos-head-' +
            respuesta[i].pkImpuesto+ respuesta[i].tasa +
            '">' +
            respuesta[i].nombre +
            "</td>" +
            '<td style="text-align: right; color: var(--color-primario);">' +
            tasa +
            " </td>" +
            '<td style="text-align: right; color: var(--color-primario);">.....</td>' +
            '<td style="text-align: right; color: var(--color-primario);"> $ ' +
            dosDecimales(respuesta[i].totalImpuesto) +
            "</td>" +
            "</tr>"; */

            '<span style="color: var(--color-primario);" id="impuestos-head-' +
            respuesta[i].pkImpuesto +
            respuesta[i].tasa +
            '">' +
            respuesta[i].nombre +
            ": $ " +
            dosDecimales(respuesta[i].totalImpuesto) +
            "</span><br>";

          /// Si es Retenido TipoImp 2 se resta del Total.
          if (respuesta[i].tipoImp == 2) {
            Total -= respuesta[i].totalImpuesto;
          } else {
            Total += respuesta[i].totalImpuesto;
          }
        }
      });

      $("#impuestos").html(html);
      $("#Total").html(dosDecimales(Total));
      $("#txtImporte").text("$" + dosDecimales(Total));
    },
    error: function (error) {
      console.log(error);
    },
  });
  //Obtener total
  /* $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_totalVentaDirectaEdit",
      datos: PKVenta,
      datos2: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Total").html(dosDecimales(respuesta[0].Total));
    },
    error: function (error) {
      console.log(error);
    },
  }); */
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}

function cambiarEstatusVentaDirecta(valor) {
  var id = $("#txtPKVenta").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_EstatusVentaDirecta",
      datos: id,
      datos2: valor,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        Swal.fire({
          title: "Operación exitosa",
          text: "Se ha cerrado la venta directa correctamente",
          type: "success",
        }).then(function () {
          window.location.href = "../ventas";
        });
      } else {
        Swal.fire(
          "Error",
          "No se pudo cerrar la venta directa correctamente, ¡Favor de intentarlo más tarde!",
          "warning"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function verVentaDirecta() {
  var pkVenta = $("#txtPKVenta").val();
  //window.location.href = "ver_venta.php?vd=" + pkVenta;
  window.location.href = "functions/open_VentaDirecta?txtId=" + pkVenta;
}

function obtenerEditar() {
  var id = $("#txtPKVenta").val();
  console.log(id);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_estadoVentaDirecta",
      data: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("data de obtenerEditar");
      console.log(data[0]["existe"]);
      console.log(data[0]["EstatusVenta"]);
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("se cumple condicion");
        window.location.href = "editar_venta.php?vd=" + id;
      } else {
        console.log("no se cumple condicion");
        obtenerVer(id);
      }
    },
  });
}

function obtenerVer(id) {
  window.location.href = "ver_ventas.php?vd=" + id;
  console.log("si llega a obtenerVer()");
}

function validate_Permissions(pkPantalla) {
  var PKVenta = $("#txtPKVenta").val();
  var id_ticket = $("#txtIdTicketHide").val();
  var estatus_ticket = parseInt($("#txtEstatusTicketHide").val());
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      _permissionsFacturar.read = data[0].isRead;
      _permissionsFacturar.add = data[0].isAdd;
      _permissionsFacturar.edit = data[0].isEdit;
      _permissionsFacturar.delete = data[0].isDelete;
      _permissionsFacturar.export = data[0].isExport;
      console.log({
        read: _permissionsFacturar.read,
        add: _permissionsFacturar.add,
        edit: _permissionsFacturar.edit,
        delete: _permissionsFacturar.delete,
        export: _permissionsFacturar.export,
      });

      //PRODUCTOS
      if (pkPantalla == "13") {
        var html = "";
        if (_permissionsFacturar.add == "1") {
          if (
            estatusFacturaid == "4" ||
            estatusFacturaid == "3" ||
            estatusFacturaid == "5"
          ) {
            html = `<span class="btn-table-custom btn-table-custom--blue" id="btnFacturar" onclick="facturarVentaDirecta(${PKVenta})"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-FACTURACION AZUL NVO-01.svg">Facturar</span>`;
          } else if (
            id_ticket != "" &&
            id_ticket != null &&
            estatus_ticket != 3 &&
            (estatusFacturaid == "4" ||
              estatusFacturaid == "3" ||
              estatusFacturaid == "5")
          ) {
            html = `<span class="btn-table-custom btn-table-custom--blue" id="btnFacturar" onclick="facturarVentaDirecta(${PKVenta})"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-FACTURACION AZUL NVO-01.svg">Facturar</span>`;
          } else {
            $("#btnEliminar").html();
            html = ``;
          }
          $("#isPermissionsFacturar").html(html);
        } else {
          html = ``;
          $("#isPermissionsFacturar").html(html);
        }
        if (_permissionsFacturar.delete != "1") {
          $("#btnEliminar").html("");
        }
        console.log(estatusFacturaid);
        console.log(estatusOP);
        $("#cmbCliente").attr("disabled", true);
        $("#cmbDireccionEnvio").attr("disabled", true);
        $("#cmbVendedor").attr("disabled", true);
        $("#cmbDireccionEntrega").attr("disabled", true);
        if (_permissionsFacturar.edit == "1") {
          $("#cmbCliente").attr("disabled", false);
          $("#cmbDireccionEnvio").attr("disabled", false);
          $("#cmbVendedor").attr("disabled", false);
          $("#cmbDireccionEntrega").attr("disabled", false);
        }
      }
    },
  });
}

$(document).on("click", "#btnFacturar", function () {
  var PKVenta = $("#txtPKVenta").val();

  //window.location.href =`../../../facturacion/agregar_facturacion.php?idVentaDirecta=${PKVenta}`;

  $().redirect("../../../facturacion/agregar_facturacion.php", {
    idVentaDirecta: PKVenta,
  });
});

function validEmptyInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val() || $("#" + inputID).val() == 0) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }

  var cantidad = $("#txtCantidad").val();

  if (parseInt($("#" + inputID).val()) < parseInt(cantidad)) {
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(
      "La cantidad en existencia es menor a la deseada."
    );
    $("#" + inputID).addClass("is-invalid");
  } else {
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text("");
    $("#" + inputID).removeClass("is-invalid");
  }
}

function eliminarVentaTemp() {
  var pkUs = $("#txtUsuario").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_VentaDirectaTempAll",
      data: pkUs,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtReferencia").val(respuesta);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function descargarVentaDirecta() {
  var id = $("#txtPKVenta").val();

  window.location.href = "functions/descargar_VentaDirecta?txtId=" + id;
}

function cargarTablaVentasDirectasVer(pkVenta) {
  $("#tblListadoVentasDirectasEdit").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    //pageLength: 15,
    paging: false,
    searching: false,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "btn-table-custom",
        },
        buttonLiner: {
          tag: null,
        },
      },
      buttons: [],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_ventaDirectaTableVer",
        data: pkVenta,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Cantidad" },
      { data: "UnidadMedida" },
      { data: "PrecioUnitario" },
      { data: "Impuestos" },
      { data: "Importe" },
      { data: "Existencias" },
      { data: "Acciones" },
    ],
    columnDefs: [
      {
        targets: 2,
        data: function (row, type, val, meta) {
          return val.toFixed(2);
        },
      },
    ],
  });
}

function mostrarIdEliminar(id, referencia) {
  $("#txtVentaDirectaIDD").val(id);
  $("#txtReferenciaD").val(referencia);
}

function obtenerEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_ventaDirecta",
      data: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
      if (data[0].status) {
        $("#tblVentasDirectas").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó la venta directa con exito!",
          sound: "../../../../../sounds/sound4",
        });
        setTimeout(function () {
          window.location.href = "../ventas";
        }, 1500);
        console.log("Eliminada2");
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: "../../../../../sounds/sound4",
        });
      }
    },
  });
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

$(document).on("click", "#enviarVenta", function () {
  var id = $("#txtId").val();
  var emailOrigen = $("#txtOrigen").val();
  var emailDestino = destinatarios.selected();
  console.log(emailDestino);
  var valParam = JSON.stringify(emailDestino);
  var asunto = $("#txtAsunto").val();
  var mensaje = $("#txaMensaje").val();
  let token = $("#csr_token_7ALF1").val();
  console.log(token);

  var contadorEnviar = 0;
  if (contadorEnviar == 0) {
    $("#txtOrigen")[0].reportValidity();
    $("#txtOrigen")[0].setCustomValidity(
      "Ingresa el correo electrónico de origen."
    );

    $("#txtDestino")[0].reportValidity();
    $("#txtDestino")[0].setCustomValidity(
      "Ingresa un correo electrónico válido."
    );

    $("#txtAsunto")[0].reportValidity();
    $("#txtAsunto")[0].setCustomValidity("Ingresa el asunto del correo.");

    $("#txaMensaje")[0].reportValidity();
    $("#txaMensaje")[0].setCustomValidity("Ingresa un mensaje del correo.");
    contadorEnviar = 1;
  }

  if (emailOrigen.trim() == "") {
    $("#txtOrigen")[0].reportValidity();
    $("#txtOrigen")[0].setCustomValidity(
      "Ingresa el correo electrónico de origen."
    );
    return;
  }
  var validarEmailOrigen = isEmail(emailOrigen);
  if (validarEmailOrigen == false) {
    $("#txtOrigen")[0].reportValidity();
    $("#txtOrigen")[0].setCustomValidity(
      "Ingresa un correo electrónico válido."
    );
    return;
  }

  if (emailDestino.length == 0) {
    $("#invalid-emailDestino").text(
      "Ingresa el correo electrónico de destino."
    );
    $("#invalid-emailDestino").css("display", "block");
    setTimeout(function () {
      $("#invalid-emailDestino").fadeOut("slow");
    }, 2000);
    return;
  }

  var validarEmailDestino = isEmail(emailDestino);
  if (validarEmailDestino == false) {
    $("#invalid-emailDestino").text("Ingresa un correo electrónico válido.");
    $("#invalid-emailDestino").css("display", "block");
    return;
  }

  if (asunto.trim() == "") {
    $("#txtAsunto")[0].reportValidity();
    $("#txtAsunto")[0].setCustomValidity("Ingresa el asunto del correo.");
    return;
  }

  if (mensaje.trim() == "") {
    $("#txaMensaje")[0].reportValidity();
    $("#txaMensaje")[0].setCustomValidity("Ingresa un mensaje del correo.");
    return;
  }

  $("#enviarVenta").attr("disabled", true);
  $("#cancelarVenta").attr("disabled", true);
  $("#loading").css("display", "flex");

  emailDestino.forEach((index) => {
    console.log("1");
    console.log(index);
    $.ajax({
      type: "POST",
      url: "functions/enviar_VentaDirecta.php",
      data: {
        txtId: id,
        txtOrigen: emailOrigen,
        txtDestino: index,
        txtAsunto: asunto,
        txaMensaje: mensaje,
        csr_token_7ALF1: token,
      },
      success: function (data) {
        console.log(data);
        if (data == "exito") {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: false,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Se envio la venta al correo.",
          });

          $("#txaMensaje").val("");
          $("#datos_envio").modal("toggle");
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: false,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: "Ocurrio un error al enviar, vuelva intentarlo.",
          });
        }

        $("#enviarVenta").attr("disabled", false);
        $("#cancelarVenta").attr("disabled", false);
        $("#loading").css("display", "none");
      },
    });
  });
});

function cargar_moduloPagos(id) {
  let is_invoice = 0;
  $.ajax({
    url: "../../../cuentas_cobrar/functions/function_redirecciona_RecepcionPagos.php",
    data: {
      id: id,
      is_invoice: is_invoice,
    },
    dataType: "json",
    success: function (data) {
      console.log({ data });
      if (data["estatus"] == "ok") {
        if (data["result"] == 1) {
          $().redirect("../../../recepcion_pagos/", {
            rutaFrom: 2,
            idCliente: data["cliente_id"],
            idFactura: data["idFactura"],
            is_invoice: is_invoice,
          });
        } else if (data["result"] == 2) {
          $().redirect("../../../recepcion_pagos/pagos.php", {
            rutaFrom: 2,
            idCliente: data["cliente_id"],
            idFactura: data["idFactura"],
            is_invoice: is_invoice,
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
            msg: data["result"],
          });
        }
      } else if (data["estatus"] == "cancelada") {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: data["result"],
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salió mal!",
        });
      }
    },
    error: function (jqXHR, exception, data, response) {
      console.log({ jqXHR });
      var msg = "";
      if (jqXHR.status === 0) {
        msg = "Not connect.\n Verify Network.";
      } else if (jqXHR.status == 404) {
        msg = "Requested page not found. [404]";
      } else if (jqXHR.status == 500) {
        msg = "Internal Server Error [500].";
      } else if (exception === "parsererror") {
        msg = "Requested JSON parse failed.";
      } else if (exception === "timeout") {
        msg = "Time out error.";
      } else if (exception === "abort") {
        msg = "Ajax request aborted.";
      } else {
        msg = "Uncaught Error.\n" + jqXHR.responseText;
      }
    },
  });
}
