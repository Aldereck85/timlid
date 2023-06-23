var json_products_price = [];
$(document).ready(function () {
  truncateTablePRoducts(0,0);
  $("#tblDetalleProductos").DataTable({
    language: setFormatDatatables(),
    dom: "lrti",
    scrollX: true,
    scrollCollapse: false,
    lengthChange: false,
    info: false,
    bSort: false,
    paging: false,
    data: {},
    columns: [
      { data: "id" },
      { data: "edit" },
      { data: "clave" },
      { data: "descripcion" },
      { data: "sat_id" },
      { data: "id_unidad_medida" },
      { data: "unidad_medida" },
      { data: "cantidad" },
      { data: "precio" },
      { data: "subtotal" },
      { data: "impuestos" },
      { data: "descuento" },
      { data: "importe_total" },
      { data: "alerta" },
    ],

    columnDefs: [
      {
        targets: [0, 4, 5],
        visible: false,
        searchable: false,
      },
    ],
  });

  loadCombo("clientes", "#cmbCliente", "", "Seleccione un cliente...");
  loadSerieFolio();

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_lastUsoCFDI",
      value: 1,
    },
    asyn: false,
    dataType: "json",
    success: function (respuesta) {
      usoCfdi = respuesta[0]["uso_cfdi_id"];
      formaPago = respuesta[0]["forma_pago_id"];
      //metodoPago = respuesta[0]['metodo_pago'];
      switch (respuesta[0]["metodo_pago"]) {
        case "1":
          metodoPago = "PUE";
          break;
        case "2":
          metodoPago = "PPD";
          break;
        default:
          metodoPago = "PUE";
          break;
      }
      loadCombo(
        "cfdiUse",
        "#cmbUsoCFDI",
        usoCfdi,
        "Seleccione un uso de CFDI..."
      );
      cmbMetodoPago.set(metodoPago);
      loadCombo(
        "formasPago",
        "#cmbFormasPago",
        formaPago,
        "Seleccione una forma de pago..."
      );
      loadCombo("monedas", "#cmbMoneda", 100, "Seleccione una moneda...");
      loadCombo("vendedores", "#cmbVendedor", "", "Seleccione un vendedor");
    },
    error: function (error) {
      console.log(error);
    },
  });

  $("#cmbCliente").on("change", function () {
    value = $(this).val();
    
    if(value == "add"){
      cmbCliente.set(0);
      cargarCMBRegimen('cmbRegimen');
      cargarCMBVendedorNC("cmbVendedorNC");
      cargarCMBMedioContactoCliente("cmbMedioContactoCliente");
      $("#agregar_Cliente_50").modal("toggle");
      return;
    }

    is_customer_for_billing(value);

    $("#cmbProducto").prop("disabled", false);
    cmbProductos.enable();
    $("#txtPrecioUnitario").val("0.00");
    $("#txtCantidad").val("0");
    $("#chkProductosTodos").prop("disabled", false);
    $("#chkPrecioEspecial").prop("disabled", false);
    loadCombo(
      "productos",
      "#cmbProducto",
      "",
      "Seleccione un producto...",
      value
    );
    //truncateTablePRoducts(0,0);
    getRfcClient(value);
    setRFC(value);
  });

  $("#cmbProducto").on("change", function () {
    value = $(this).val();
    var client = $("#cmbCliente").val();
    if(value == "add"){
      cmbProductos.set();
      $("#agregar_Producto_FC").modal("toggle");
      return;
    }
    if ($(this).val() !== null) {
      if ($("#chkProductosTodos").is(":checked")) {
        
        loadPrice("precioAll", value,client);
        $("#txtCantidad").prop("disabled", false);
        $("#txtPrecioUnitario").prop("disabled", false);
        $("#cargarProducto").prop("disabled", false);
      } else {
        loadPrice("precio", value,client);
        $("#txtCantidad").prop("disabled", false);
        $("#txtPrecioUnitario").prop("disabled", false);
        $("#cargarProducto").prop("disabled", false);
      }
    }
  });

  $("#chkProductosTodos").on("click", function () {
    $("#txtPrecioUnitario").val("");
    if ($(this).is(":checked")) {
      loadCombo(
        "productosAll",
        "#cmbProducto",
        "",
        "Seleccione un producto..."
      );
    } else {
      loadCombo(
        "productos",
        "#cmbProducto",
        "",
        "Seleccione un producto...",
        value
      );
    }
  });
});

$(document).on("click", "#cargarProducto", function () {
  
  idProducto = $("#cmbProducto").val();
  producto =  $("#cmbProducto option:selected").text();
  cantidad = $("#txtCantidad").val();
  precioUnitario = $("#txtPrecioUnitario").val();
  sucursal = $("#cmbSucursales").val() !== null && $("#cmbSucursales").val() !== "" ? $("#cmbSucursales").val() : "";
  sucursal_name = $("#cmbSucursales option:selected").text() !== null && $("#cmbSucursales option:selected").val() !== "" ? $("#cmbSucursales option:selected").text() : "";
  
  const chk_afectar_inventario = $("#chkAfectarInventario");
    
  $("#cargarProducto").prop("disabled", false);

  if (idProducto !== "" && idProducto !== null) {

    if (cantidad !== "" && cantidad > 0) {
      
      if (precioUnitario !== "" && precioUnitario > 0) {
        
        json =
          "{" +
          '"producto_id": "' +
          idProducto +
          '",' +
          '"cantidad": "' +
          cantidad +
          '",' +
          '"precio_unitario": "' +
          precioUnitario +
          '"' +
        "}";
        var ban_price = $("#chkPrecioEspecial").is(":checked") ? 1 : 0;
        var cliente = $("#cmbCliente").val();
        
        json_products_price.push({producto_id:idProducto,precio_unitario:precioUnitario,cliente:cliente,precio_especial:ban_price});
        if(chk_afectar_inventario.is(":checked")){
          getStockProduct(idProducto,producto,json,cantidad,sucursal,sucursal_name);
        } else {
          getAddProduct(json,sucursal);
        }
          
      } else {
        cmbProductos.set(null);
        $("#cargarProducto").prop("disabled", true);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3100,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "Ingrese un precio unitario",
        });
      }
    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Ingrese una cantidad",
      });
    }
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3100,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Ingrese un producto",
    });
  }
  if($("#chkPrecioEspecial").is(":checked")){
    $("#chkPrecioEspecial").prop( "checked", false );
  }
});

function loadCombo(funcion, input, data, texto, value) {
  $.ajax({
    url: "php/funciones.php",
    method: "POST",
    data: {
      clase: "get_data",
      funcion: "get_" + funcion,
      value: value,
    },
    datatype: "json",
    success: function (respuesta) {
      var res = JSON.parse(respuesta);

      html = "<option data-placeholder='true' value=''>" + texto + "</option>";
      if (res.length > 0) {
        $.each(res, function (i) {
          if (res[i].id === parseInt(data)) {
            html +=
              "<option value='" +
              res[i].id +
              "' selected>" +
              res[i].texto +
              "</option>";
          } else {
            html +=
              "<option value='" + res[i].id + "'>" + res[i].texto + "</option>";
          }
        });
      } else {
        html += "<option value='0'>No hay registros.</option>";
      }

      if(input == "#cmbCliente"){
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Agregar cliente</option>';
      }else if(input == "#cmbProducto"){
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Agregar producto</option>';
      }

      $(input).html(html);
      //html = null;
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadSerieFolio() {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_folioSerie",
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtSerie").val(respuesta["serie"]);
      $("#txtFolio").val(respuesta["folio"]);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadPrice(func, value, client) {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_" + func,
      value: value,
      client: client
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtPrecioUnitario").val(respuesta);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).ready(function () {
  cmbCliente = new SlimSelect({
    select: "#cmbCliente",
    placeholder: "Seleccione un cliente...",
    addable: function (value) {
      cargarCMBRegimen('cmbRegimen');
      cargarCMBVendedorNC("cmbVendedorNC");
      cargarCMBMedioContactoCliente("cmbMedioContactoCliente");
      $("#agregar_Cliente_50").modal("toggle");
      return false;
    }
  });

  new SlimSelect({
    select: "#cmbSucursales",
    placeholder: 'Seleccione una sucursal...',
    deselectLabel: '<span class="">✖</span>',
  })

  new SlimSelect({
    select: '#cmbRegimen',
    placeholder: 'Seleccione un régimen...',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbMedioContactoCliente',
    placeholder: 'Seleccione un medio...',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbVendedorNC',
    placeholder: 'Seleccione un vendedor...',
    deselectLabel: '<span class="">✖</span>',
    
  });

  SS_cmbPais = new SlimSelect({
    select: '#cmbPais',
    placeholder: 'Seleccione un pais...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbEstado = new SlimSelect({
    select: '#cmbEstado',
    placeholder: 'Seleccione un estado...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbTipoProducto = new SlimSelect({
    select: '#cmbTipoProducto',
    placeholder: 'Seleccione un tipo...',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbUsoCFDI",
    placeholder: "Seleccione un uso de cfdi...",
  });

  cmbProductos = new SlimSelect({
    select: "#cmbProducto",
    placeholder: "Seleccione un producto...",
    addable: function (value) {
      $("#agregar_Producto_FC").modal("toggle");
      cargarCMBImpuestos("1", "cmbImpuestos");
      cargarCMBTasaImpuestos("1","cmbTasaImpuestos");
      return;
    }
  });

  new SlimSelect({
    select: "#cmbFormasPago",
    placeholder: "Seleccione una forma de pago...",
  });

  cmbMetodoPago = new SlimSelect({
    select: "#cmbMetodoPago",
    placeholder: "Seleccione un método de pago...",
  });

  new SlimSelect({
    select: "#cmbMoneda",
    placeholder: "Seleccione una moneda...",
  });

  new SlimSelect({
    select: "#cmbImpuesto",
    placeholder: "Seleccione una moneda...",
  });

  new SlimSelect({
    select: "#cmbCuentaBancaria",
    placeholder: "Seleccione una cuenta bancaria...",
  });

  new SlimSelect({
    select: "#cmbVendedor",
    placeholder: "Seleccione una cuenta bancaria...",
    addable: function (value) {
        $("#agregar_Personal").modal("toggle");
    }
  });
  cmbGenero = new SlimSelect({
    select: '#cmbGenero',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar género'
  });
  cmbRoles = new SlimSelect({
    select: '#cmbRolesPersonal',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar roles'
  });
  cmbEstado = new SlimSelect({
    select: '#cmbEstadoPersonal',
    deselectLabel: '<span class="">✖</span>',
    placeholder: 'Seleccionar estado'
  });
});

function setFormatDatatables() {
  return {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
  };
}

function loadDataTableProducts(id, func, value, id_row) {
  const productos = $(id).DataTable({
    language: setFormatDatatables(),
    dom: "lrti",
    scrollX: true,
    scrollCollapse: false,
    lengthChange: false,
    info: false,
    bSort: false,
    paging: false,
    ajax: {
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: func,
        value: value,
        id_row: id_row,
      },
      async: false,
    },
    columns: [
      { data: "id" },
      { data: "edit" },
      { data: "clave" },
      { data: "descripcion" },
      { data: "sat_id" },
      { data: "id_unidad_medida" },
      { data: "unidad_medida" },
      { data: "cantidad" },
      { data: "precio" },
      { data: "subtotal" },
      { data: "impuestos" },
      { data: "descuento" },
      { data: "importe_total" },
      { data: "alerta" },
    ],

    columnDefs: [
      {
        targets: [0, 4, 5],
        visible: false,
        searchable: false,
      },
    ],
  });
}

$(document).on("click", "#tblDetalleProductos a", function () {
  clase = $(this).attr("class");
  var deletePos = $(this).closest("tr").index();
  var table = $("#tblDetalleProductos").DataTable();
  if (clase === "delete") {
    id = $(this).attr("id");

    var id_producto = $("#" + id).data("id");
    var ref = $("#" + id).data("ref");

    json =
      "{" + '"id":"' + ref + '",' + '"product":"' + id_producto + '"' + "}";
    
    removeItemFromArr( json_products_price, id_producto );
    
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: "delete_data",
        funcion: "delete_product",
        value: json,
      },
      dataType: "json",
      success: function (respuesta) {
        if (respuesta) {
          table.rows(deletePos).remove().draw();
          if (!table.data().count()) {
            cmbCliente.enable();
            $("#agregarFactura").prop("disabled", true);
            $("#agregarPrefactura").prop("disabled", true);
            $("#txtCantidad").prop("disabled", true);
            $("#txtPrecioUnitario").prop("disabled", true);
          }
          loadSubtotal();
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "El producto no se pudo eliminar",
          });
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else if (clase === "edit") {
    id = $(this).attr("id");
    var id_producto = $("#" + id).data("id");
    var ref = $("#" + id).data("ref");
    $("#txtIdReferencia").val(ref);
    $("#txtIdProd").val(id_producto);

    validateSAT(id_producto);
    validateUnidadMedida(idProducto);
    loadDataProducto(idProducto, ref);
  }
});

$(document).on("keyup", "#txtCantidad", function () {
  if ($(this).val() < 0) {
    $(this).val("0");
    $(this).select();
  }
});

$(document).on("focus", "input[type=text]", function () {
  $(this).select();
});

function loadSubtotal() {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_totalSubtotalSalidas",
      value: 1,
      ref:0,
      type:0
    },
    asyn: false,
    dataType: "json",
    success: function (respuesta) {
      var res = respuesta;
      $("#subtotal").html(res.subtotal);
      $("#impuestos").html(res.impuestos);
      $("#total").html(res.total);
      $(".subtotal").css("display", "block");
      $(".impuestos").css("display", "block");
      $(".total").css("display", "block");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function validateSAT(prod) {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_claveSat",
      value: prod,
    },
    success: function (respuesta) {
      res = JSON.parse(respuesta);
      switch (res.mensaje) {
        case 0:
          $("#sec_clave_sat").css("display", "block");
          break;
        case 1:
          $("#sec_clave_sat").css("display", "none");
          break;
      }
      $("#txtClaveSatId").val(res.clave_sat_id);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function validateUnidadMedida(prod) {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_unidadMedida",
      value: prod,
    },
    success: function (respuesta) {
      res = JSON.parse(respuesta);

      switch (res.mensaje) {
        case "0":
          $("#sec_clave_sat").css("display", "block");
          break;
        case "1":
          $("#sec_clave_sat").css("display", "none");
          break;
      }
      $("#txtUnidadMedidaId").val(res.clave_sat_id);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadDataProducto(idProducto, id_row) {
  $("#txtIdProd").val(idProducto);
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_dataProduct",
      id_row: id_row,
    },
    datatype: "json",
    success: function (respuesta) {
      var res = JSON.parse(respuesta);
      $("#editarProducto").modal("show");
      var claveSat =
        res["clave_sat"] !== "" &&
        res["clave_sat"] !== null &&
        res["clave_sat"] !== 1
          ? res["clave_sat"]
          : "";
      //$("#txtClaveSatId").val(res['clave_sat']);
      $("#txtClave").val(res["clave"]);
      $("#txtDescripcion").val(res["nombre"]);
      var idUnidadMedida =
        res["unidad_medida"] !== "" &&
        res["unidad_medida"] !== null &&
        res["unidad_medida"] !== 1
          ? res["unidad_medida"]
          : "";

      var cantidad = parseInt(res["cantidad"]);
      var precioUnitario = parseFloat(res["precio_unitario"]);
      var importe = cantidad * precioUnitario;

      if (idUnidadMedida !== "") {
        $("#txtUnidadMedida").val(res["unidad_medida_texto"]);
        $("#txtUnidadMedidaId").val(idUnidadMedida);
      } else {
        $("#txtUnidadMedida").val("Clic para asignar una unidad de medida");
      }

      if (claveSat !== "") {
        $("#txtClaveSat").val(res["clave_sat"]);
      } else {
        $("#txtClaveSat").val("Clic para asignar clave SAT");
      }

      $("#txtCantidadEdit").val(res["cantidad"]);
      $("#txtPrecioUnitarioEdit").val(res["precio_unitario"]);

      $("#tipoDescuento" + res["tipo_descuento"]).attr("checked", true);

      $("#txtDescuento").val(res["descuento"]);
      $("input[type=radio][name=tipoDescuento]").on("click", function () {
        if ($(this).val() === "1") {
          $("#txtDescuento").val(numeral(res["descuento"]).format("0"));
        } else {
          $("#txtDescuento").val(
            numeral(res["descuento"]).format("0,000,000.00")
          );
        }
      });
      //var id_row = $("#txtIdReferencia").val();
      var idTable = "#tblDetalleImpuestosModal";
      var func = "get_impuestoTable";
      var tipo_doc = $("#cmbFacturarDesde").val();

      var rowCountTax = $("#tblDetalleImpuestosModal>tbody>tr");

      if (rowCountTax.length > 0) {
        $(idTable).DataTable().clear().destroy();
        loadDataTableTax(idTable, func, idProducto, id_row);
      } else {
        loadDataTableTax(idTable, func, idProducto, id_row);
      }

      loadCombo(
        "impuestos",
        "#cmbImpuesto",
        "",
        "Seleccione un impuesto...",
        "1"
      );
      $("#txtLabelTax").html("Tasa:");
      $("#txtTax").val("0");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function loadDataTableTax(id, func, product, id_row) {
  //$(id).css('display','table');
  const table = $(id).DataTable({
    language: setFormatDatatables(),
    dom: "lrti",
    scrollX: false,
    scrollCollapse: false,
    lengthChange: false,
    info: false,
    bSort: true,
    ajax: {
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: func,
        producto: product,
        id: id_row,
      },
    },

    columns: [
      { data: "id" },
      { data: "tipo" },
      { data: "nombre" },
      { data: "tasa" },
      { data: "delete" },
    ],

    columnDefs: [
      {
        targets: [0, 1],
        visible: false,
        searchable: false,
      },
      {
        targets: [1],
        width: "100%",
      },
    ],
  });
  return table;
}

$(document).on("click", "input[type=radio][name=tipoImpuesto]", function () {
  tipoImpuestos = $(this).val();
  loadCombo(
    "impuestos",
    "#cmbImpuesto",
    "",
    "Seleccione un impuesto...",
    tipoImpuestos
  );
});

$(document).on("click", "#btnAgregarImpuesto", function () {
  var tableTax = $("#tblDetalleImpuestosModal").DataTable();
  var counRows = tableTax.rows().count();

  if ($("#cmbImpuesto").val() !== "" && $("#txtTax").val() !== 0) {
    data1 = tableTax.rows().data();

    let ban = 0;

    if (counRows > 0) {
      for (let i = 0; i < data1.length; i++) {
        if (data1[i]["id"] === $("#cmbImpuesto").val()) {
          ban++;
        }
      }
    }

    if (ban > 0) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Ya se ingresó este impuesto",
      });
    } else {
      tableTax.row
        .add({
          id: $("#cmbImpuesto").val(),
          tipo: $("input[type=radio][name=tipoImpuesto]").val(),
          nombre: $("#cmbImpuesto option:selected").text(),
          tasa: $("#txtTax").val(),
          delete:
            "<a id='deleteImp" +
            $("#cmbImpuesto").val() +
            "' data-pos='" +
            counRows +
            "' data-id='" +
            $("#cmbImpuesto").val() +
            "' href='#'><i class='fas fa-trash-alt'></i></a>",
        })
        .draw();

      $.ajax({
        method: "POST",
        url: "php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_dataTaxes",
          producto: $("#txtIdProd").val(),
          value: $("#cmbImpuesto").val(),
          tasa: $("#txtTax").val(),
          id: $("#txtIdReferencia").val(),
        },
        datatype: "json",
        success: function (respuesta) {
          loadCombo(
            "impuestos",
            "#cmbImpuesto",
            "",
            "Seleccione un impuesto...",
            1
          );
          $("#tipoImpuesto1").prop("checked", true);
          $("#tipoImpuesto2").prop("checked", false);
          $("#tipoImpuesto3").prop("checked", false);
          $("#txtTax").val("0");
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3100,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Debe seleccionar un impuesto con una tasa",
    });
  }
});

$(document).on("click", "#txtUnidadMedida", function () {
  loadTableUnidadMedida("#tabla_body_medida", "#txtUnidadMedida");
  $("#buscar_clave_unidad_medida").val("");
  $("#editarProducto").modal("hide");

  $(".modal").on("hidden.bs.modal", function () {
    if ($(".modal:visible").length) {
      $("body").addClass("modal-open");
    }
  });

  $("#agregar_unidad_medida").modal("show");
});

$(document).on("click", "#btnAgregarNC_FC", function () {
   if ($("#agregarCliente")[0].checkValidity()) {
    var badNombreCom =
      $("#invalid-nombreCom").css("display") === "block" ? false : true;
     var badMedioContCli =
      $("#invalid-medioCont").css("display") === "block" ? false : true; 
    var badVendedorCli =
      $("#invalid-vendedor").css("display") === "block" ? false : true;
    var badEmailCli =
      $("#invalid-email").css("display") === "block" ? false : true;
    var badRazon =
      $("#invalid-razon").css("display") === "block" ? false : true;
    var badRFC = $("#invalid-rfc").css("display") === "block" ? false : true;
    var badRegimen = $("#invalid-regimen").css("display") === "block" ? false : true;
    var badCP = $("#invalid-cp").css("display") === "block" ? false : true;
     var badPais =
      $("#invalid-paisFisc").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-paisEstadoFisc").css("display") === "block" ? false : true; 
    if (
      badNombreCom &&
      badMedioContCli &&
      badVendedorCli &&
      badEmailCli && 
      badRazon &&
      badCP &&
      badRFC &&
      badRegimen &&
      badPais &&
      badEstado 
    ) {
      $("#btnAgregarNC").prop("disabled", true);
    
      var nombreComercial = $("#txtNombreComercial").val().trim();
      var email = $("#txtEmail").val().trim();
      var razonSocial = $("#txtRazonSocial").val();
      var rfc = $("#txtRFC").val();
      var medioContactoCliente = $("#cmbMedioContactoCliente").val();
      var vendedor = $("#cmbVendedorNC").val();
      var pais = $("#cmbPais").val();
      var estado = $("#cmbEstado").val();
      var cp = $("#txtCP").val().trim();
      var telefono = $("#txtTelefono_Cl").val();
      var estatus =  1;
      var pkRazon = 0;
      var montoCredito = 0;
      var diasCredito = 0; 
      var regimen = $("#cmbRegimen").val();

      $.ajax({
        url: "../ventas_directas/php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosCliente",
          datos2: nombreComercial,
          datos3: medioContactoCliente,
          datos4: vendedor,
          datos5: montoCredito,
          datos6: diasCredito,
          datos7: telefono,
          datos8: email,
          datos9: estatus,
          datos10: razonSocial,
          datos11: rfc,
          datos17: pais,
          datos18: estado,
          datos19: cp,
          datos20: pkRazon,
          datos21: regimen
        },
        dataType: "json",
        success: function (respuesta) {
          $("#btnAgregarNC_FC").prop("disabled", false);

          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Cliente registrado correctamente!",
              sound: '../../../../../sounds/sound4'
            });
            $("#btnCancelar_newCliente_FC").click();
            loadCombo("clientes", "#cmbCliente", "", "Seleccione un cliente...");
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
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
          $("#btnAgregarNC").prop("disabled", false);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: '../../../../../sounds/sound4'
          });
        },
      });
    }
  } else {
     if (!$("#txtNombreComercial").val()) {
      $("#invalid-nombreCom").css("display", "block");
      $("#txtNombreComercial").addClass("is-invalid");
    } 
     if (!$("#cmbMedioContactoCliente").val()) {
      $("#invalid-medioCont").css("display", "block");
      $("#cmbMedioContactoCliente").addClass("is-invalid");
    }
    if (!$("#cmbVendedorNC").val()) {
      $("#invalid-vendedor").css("display", "block");
      $("#cmbVendedorNC").addClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-email").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    } 
    if (!$("#txtRFC").val()) {
      $("#invalid-rfc").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#cmbRegimen").val()) {
      $("#invalid-regimen").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#txtRazonSocial").val()) {
      $("#invalid-razon").css("display", "block");
      $("#txtRazonSocial").addClass("is-invalid");
    }
    if (!$("#txtCP").val()) {
      $("#invalid-cp").css("display", "block");
      $("#txtCP").addClass("is-invalid");
    }
     if (!$("#cmbPais").val()) {
      $("#invalid-paisFisc").css("display", "block");
      $("#cmbPais").addClass("is-invalid");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-paisEstadoFisc").css("display", "block");
      $("#cmbEstado").addClass("is-invalid");
    } 
  }
});

function validInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val() || $("#" + inputID).val() == 0) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
    if(inputID == 'txtRFC'){
        let vRFC = $("#txtRFC").val()
        let rfc = vRFC.trim().toUpperCase();
        let rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba
        if (rfcCorrecto) {
          $("#invalid-rfc").css("display", "none");
          $("#invalid-rfc").text("El cliente debe tener un RFC.");
          $("#txtRFC").removeClass("is-invalid");
          escribirRFC();
        } else {
          $("#invalid-rfc").css("display", "block");
          $("#invalid-rfc").text("El RFC ingresado no es valido.");
          $("#txtRFC").addClass("is-invalid");
        }
    }
  }
}

function escribirRFC() {
  let rfc = $("#txtRFC").val().trim();
  let rfcHis = $("#txtRFCHis").val();

  if (rfc != rfcHis) {
    $.ajax({
      url: "../clientes/php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_rfc_Cliente",
        data: rfc
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta RFC validado: ", data);
        // Validar si ya existe el identificador con ese nombre
        if (parseInt(data.existe) == 1) {
          $("#invalid-rfc").css("display", "block");
          $("#invalid-rfc").text("El RFC ingresado ya existe en el sistema.");
          $("#txtRFC").addClass("is-invalid");
        } else {
          $("#invalid-rfc").css("display", "none");
          $("#invalid-rfc").text("El cliente debe tener un RFC.");
          $("#txtRFC").removeClass("is-invalid");
        }
      },
    });
  }
}

function rfcValido(rfc, aceptarGenerico = true) {
  const re =
    /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
  var validado = rfc.match(re);

  if (!validado)
    //Coincide con el formato general del regex?
    return false;

  //Separar el dígito verificador del resto del RFC
  const digitoVerificador = validado.pop(),
    rfcSinDigito = validado.slice(1).join(""),
    len = rfcSinDigito.length,
    //Obtener el digito esperado
    diccionario = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
    indice = len + 1;
  var suma, digitoEsperado;

  if (len == 12) suma = 0;
  else suma = 481; //Ajuste para persona moral

  for (var i = 0; i < len; i++)
    suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);
  digitoEsperado = 11 - (suma % 11);
  if (digitoEsperado == 11) digitoEsperado = 0;
  else if (digitoEsperado == 10) digitoEsperado = "A";

  //El dígito verificador coincide con el esperado?
  // o es un RFC Genérico (ventas a público general)?
  if (
    digitoVerificador != digitoEsperado &&
    (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000")
  )
    return false;
  else if (
    !aceptarGenerico &&
    rfcSinDigito + digitoVerificador == "XEXX010101000"
  )
    return false;
  return rfcSinDigito + digitoVerificador;
}

function resetForm(frm){
  form=document.getElementById(frm);
  form.reset();

  if(frm == "agregarCliente"){

    SS_cmbPais.set();
    SS_cmbEstado.set();

    $("#invalid-nombreCom").css("display", "none");
    $("#txtNombreComercial").removeClass("is-invalid");

    $("#invalid-telCl").css("display", "none");
    $("#txtTelefono_Cl").removeClass("is-invalid");

    $("#cmbMedioContactoCliente").trigger("change");
    $("#invalid-medioCont").css("display", "none");
    $("#cmbMedioContactoCliente").removeClass("is-invalid"); 

    $("#cmbVendedorNC").trigger("change");
    $("#invalid-vendedor").css("display", "none");
    $("#cmbVendedorNC").removeClass("is-invalid"); 

    $("#invalid-email").css("display", "none");
    $("#txtEmail").removeClass("is-invalid"); 

    $("#invalid-razon").css("display", "none");
    $("#txtRazonSocial").removeClass("is-invalid");

    $("#invalid-rfc").css("display", "none");
    $("#txtRFC").removeClass("is-invalid");

    $("#cmbRegimen").trigger("change");
    $("#invalid-regimen").css("display", "none");
    $("#cmbRegimen").removeClass("is-invalid");

    $("#invalid-cp").css("display", "none");
    $("#txtCP").removeClass("is-invalid");

    $("#invalid-paisFisc").css("display", "none");
    $("#cmbPais").removeClass("is-invalid");

    $("#invalid-paisEstadoFisc").css("display", "none");
    $("#cmbEstado").removeClass("is-invalid");
  }else if(frm == "agregarProductoForm"){
    $("#invalid-nombreProducto").css("display", "none");
    $("#txtProducto").removeClass("is-invalid");

    $("#invalid-clave").css("display", "none");
    $("#txtClave_FC").removeClass("is-invalid");

    SS_cmbTipoProducto.set();
    $("#invalid-tipoProd").css("display", "none");
    $("#cmbTipoProducto").removeClass("is-invalid");
    
    $("#txtClaveSatId_NP").val(0);
    $("#invalid-claveSat").css("display", "none");
    $("#txtClaveSat_NP").removeClass("is-invalid");

    $("#txtUnidadMedidaId_NP").val(0);
    $("#invalid-unidadSat").css("display", "none");
    $("#txtUnidadMedida_NP").removeClass("is-invalid");
  }
}

function validarCP(inpt, invalid_card) {
  var value = $("#"+inpt).val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  $.ajax({
    url: "../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "valid_cp",
      data: value
    },
    dataType: "json",
    success: function (respuesta) {
      if (!ercp.test(value) || !value || respuesta == false) {
        $("#"+invalid_card).css("display", "block");
        $("#"+invalid_card).text("El CP ingresado no es valido.");
        $("#"+inpt).addClass("is-invalid");
      } else {
        $("#"+invalid_card).css("display", "none");
        $("#"+invalid_card).text("El codigo postal.");
        $("#"+inpt).removeClass("is-invalid");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function validarCorreo(value, inpt, invalid_card) {
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#"+invalid_card).css("display", "none");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#"+invalid_card).css("display", "none");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).removeClass("is-invalid");
  } else {
    $("#"+invalid_card).css("display", "block");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).addClass("is-invalid");
  }
}

function escribirNombre() {
  var valor = $("#txtNombreComercial").val();
  $.ajax({
    url: "../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_nombreComercial",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreCom").css("display", "block");
        $("#invalid-nombreCom").text(
          "El nombre ya esta registrado en el sistema."
        );
        $("#txtNombreComercial").addClass("is-invalid");
      } else {
        $("#invalid-nombreCom").css("display", "none");
        $("#invalid-nombreCom").text(
          "El cliente debe tener un nombre comercial."
        );
        $("#txtNombreComercial").removeClass("is-invalid");
        if (!valor) {
          $("#invalid-nombreCom").css("display", "block");
          $("#invalid-nombreCom").text(
            "El cliente debe tener un nombre comercial."
          );
          $("#txtNombreComercial").addClass("is-invalid");
        }
      }
    },
  });
}

function validaNumTelefono(evt, input, invalid_card) {
  var key = window.Event ? evt.which : evt.keyCode;
  if($("#"+input).val()=='' || $("#"+input).val() == null){
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
    return false;
  }
  if (key == 8 || key == 46) {
    $("#result1").val($("#"+input).val().length);
    $("#result1").addClass("mui--is-not-empty");
    var valor = $("#result1").val();
    if (valor < 8 || valor == 9) {
      $("#"+invalid_card).css("display", "block");
      $("#"+input).addClass("is-invalid");
    } else {
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
    }
  } else {
    $("#result1").val($("#"+input).val().length);
    $("#result1").addClass("mui--is-not-empty");
    var valor = $("#result1").val();
    if (valor < 8 || valor == 9) {
      $("#"+invalid_card).css("display", "block");
      $("#"+input).addClass("is-invalid");
    } else {
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
      return false;
    }
  }
}

function escribirRazonSocial() {
  var valor = $("#txtRazonSocial").val();
  var valorHis = $("#txtRazonSocialHis").val();

  if (valor != valorHis) {
    $.ajax({
      url: "../clientes/php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_razonSocial_Cliente",
        data: valor,
      },
      dataType: "json",
      success: function (data) {
        /* Validar si ya existe el identificador con ese nombre*/
        if(parseInt(data[0]["existe"]) == 1){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "La razón social ya esta registrada en el sistema."
          );
          $("#txtRazonSocial").addClass("is-invalid");
        }else if(!valor){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "El cliente debe tener una razón social."
          );
          $("#txtRazonSocial").addClass("is-invalid");
        }else{
          $("#invalid-razon").css("display", "none");
          $("#invalid-razon").text(
            "El cliente debe tener una razón social."
          );
          $("#txtRazonSocial").removeClass("is-invalid");
        }
      },
    });
  }

  var razonSocial = $("#txtRazonSocial").val().toLowerCase();
  if(razonSocial.endsWith(' s.a. de c.v.') || razonSocial.endsWith(' sa de cv') || razonSocial.endsWith(' s.a.') || razonSocial.endsWith(' sa') || razonSocial.endsWith(' sociedad anónima') || razonSocial.endsWith(' sociedad anonima') || razonSocial.endsWith(' s. de r.l.') || razonSocial.endsWith(' s de rl') || razonSocial.endsWith(' sociedad de responsabilidad limitada') || razonSocial.endsWith(' s. en c') || razonSocial.endsWith(' s en c') || razonSocial.endsWith(' sociedad en comandita') || razonSocial.endsWith(' socidad civil')){
    $("#txtRazonSocial").addClass("is-invalid");
    $("#invalid-razonTipoSociedad").css("display", "block");
  }else{
    $("#txtRazonSocial").removeClass("is-invalid");
    $("#invalid-razonTipoSociedad").css("display", "none");
  }
}

function cargarCMBRegimen(input) {
  var html = "";
  $.ajax({
    url: "../ventas_directas/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_regimen" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de los régimenes fiscales: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          ">" +
          respuesta[i].clave +
          ' - ' +
          respuesta[i].descripcion +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBVendedorNC(input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../ventas_directas/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vendedor" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta vendedor: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        /* if (data === respuesta[i].PKVendedor) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKVendedor +
          '" ' +
          ">" +
          respuesta[i].Nombre +
          "</option>";
      });

      /*html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar vendedores</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBMedioContactoCliente(input) {
  var html = "";
  $.ajax({
    url: "../ventas_directas/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_mediosContacto" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        /* if (data === respuesta[i].PKMedioContactoCliente) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKMedioContactoCliente +
          '" ' +
          ">" +
          respuesta[i].MedioContactoCliente +
          "</option>";
      });

      /*html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar medios de contacto</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("change", "#cmbPais", function(){
  let html = "";
  let PKPais = $("#cmbPais").val();
  $.ajax({
    url: "../ventas_directas/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", pais: PKPais },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#cmbEstado").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function escribirNombreProd() {
  var valor = document.getElementById("txtProducto").value;
  $.ajax({
    url: "../cotizaciones/functions/validarNombreProducto.php",
    data: { data: valor },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-nombreProducto").css("display", "block");
        $("#invalid-nombreProducto").text("El nombre ya esta en el registro.");
        $("#txtProducto").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProducto").css("display", "block");
          $("#invalid-nombreProducto").text("El producto debe tener un nombre.");
          $("#txtProducto").addClass("is-invalid");
        } else {
          $("#invalid-nombreProducto").css("display", "none");
          $("#txtProducto").removeClass("is-invalid");
        }
      }
    },
    error: function(data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-nombreProducto").css("display", "block");
        $("#invalid-nombreProducto").text("El nombre ya esta en el registro.");
        $("#txtProducto").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProducto").css("display", "block");
          $("#invalid-nombreProducto").text("El producto debe tener un nombre.");
          $("#txtProducto").addClass("is-invalid");
        } else {
          $("#invalid-nombreProducto").css("display", "none");
          $("#txtProducto").removeClass("is-invalid");
        }
      }
    }
  });
}

function escribirClave() {
  var valor = $("#txtClave_FC").val();
  $.ajax({
    url: "../cotizaciones/functions/validarClaveProducto.php",
    data: { data: valor },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-clave").css("display", "block");
        $("#invalid-clave").text(
          "La clave interna ya existe."
        );
        $("#txtClave_FC").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-clave").css("display", "block");
          $("#invalid-clave").text("El producto debe tener un nombre.");
          $("#txtClave_FC").addClass("is-invalid");
        } else {
          $("#invalid-clave").css("display", "none");
          $("#txtClave_FC").removeClass("is-invalid");
        }
      }
    },
    error: function(data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-clave").css("display", "block");
        $("#invalid-clave").text(
          "La clave interna ya existe"
        );
        $("#txtClave_FC").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-clave").css("display", "block");
          $("#invalid-clave").text("El producto debe tener un nombre.");
          $("#txtClave_FC").addClass("is-invalid");
        } else {
          $("#invalid-clave").css("display", "none");
          $("#txtClave_FC").removeClass("is-invalid");
        }
      }
    }
  });
}

$(document).on("click", "#btnGenerarClave", function () {
  var categoria = $("#cmbTipoProducto").val();
  var limpieza = "";

  if (categoria == "1") {
    limpieza = "Cmp";
  } else if (categoria == "2") {
    limpieza = "Cns";
  } else if (categoria == "3") {
    limpieza = "MP";
  } else if (categoria == "4") {
    limpieza = "P";
  } else if (categoria == "5") {
    limpieza = "S";
  } else if (categoria == "6") {
    limpieza = "AF";
  } else if (categoria == "7") {
    limpieza = "A";
  }else {
    limpieza = "N";
  }

  if (limpieza != "N") {
    $.ajax({
      url: "../inventarios_productos/php/funciones.php",
      data: { clase: "get_data", funcion: "get_claveReferencia" },
      dataType: "json",
      success: function (respuesta) {
        $("#txtClave_FC").val(limpieza + "" + respuesta);
        $("#txtClave_FC"),trigger("change");
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    $("#invalid-tipoProd").css("display", "block");
    $("#invalid-tipoProd").text(
      "Debe de seleccionarse un tipo de producto para generar clave"
    );
    $("#cmbTipoProducto").addClass("is-invalid");
  }
});

$(document).on("click", "#btnAgregarProducto_FC", function () {
  if ($("#agregarProductoForm")[0].checkValidity()) {
    var badProducto =
      $("#invalid-nombreProducto").css("display") === "block" ? false : true;
    var badClave =
      $("#invalid-clave").css("display") === "block" ? false : true;
    var badTipo =
      $("#invalid-tipoProd").css("display") === "block" ? false : true;
    var badClaveSat =
      $("#invalid-claveSat").css("display") === "block" ? false : true;
    var badUnidadSat =
      $("#invalid-unidadSat").css("display") === "block" ? false : true;
    if (
      badProducto &&
      badClave &&
      badTipo &&
      badClaveSat &&
      badUnidadSat
    ) {
      $("#btnAgregarProducto_FC").prop("disabled", true);

      var producto = $("#txtProducto").val().trim();
      var clave = $("#txtClave_FC").val().trim();
      var tipo = $("#cmbTipoProducto").val().trim();
      var cliente = 0;
      var claveSat = $("#txtClaveSatId_NP").val();
      var unidadSat = $("#txtUnidadMedidaId_NP").val();
      let contadorImpuesto = 0;

      let existenciaFabricacion = $("#txtCostoUniFabri").val();
      let unidadSAT = $("#txtIDUnidadSATAAA").val();
      let idSucursal = 0;

      var idImpuestosArray = {};
      var tasaImpuestosArray = {};
      $.each($("#agregarProductoForm").serializeArray(), function (i, element) {

        if(element.name.substring(0, 11) == 'idimpuesto_'){
          idImpuestosArray[element.name] = element.value;
          contadorImpuesto++;
        }

        if(element.name.substring(0, 13) == 'tasaimpuesto_'){
          tasaImpuestosArray[element.name] = element.value;
        }

      });



      if($("#cmbCliente").val() != null && $("#cmbCliente").val() != '' && $("#cmbCliente").val() != 0){
        cliente = $("#cmbCliente").val();
      }
      
      if ((!$("#txtClaveSatId_NP").val() || $("#txtClaveSatId_NP").val()==0) || (!$("#txtUnidadMedidaId_NP").val() || $("#txtUnidadMedidaId_NP").val()==0)) {
        
        if(!$("#txtClaveSatId_NP").val() || $("#txtClaveSatId_NP").val()==0){
          $("#invalid-claveSat").css("display", "block");
          $("#txtClaveSatId_NP").addClass("is-invalid");
        }

        if (!$("#txtUnidadMedidaId_NP").val() || $("#txtUnidadMedidaId_NP").val()==0) {
          $("#invalid-unidadSat").css("display", "block");
          $("#txtUnidadMedidaId_NP").addClass("is-invalid");
        }
        $("#btnAgregarProducto_FC").prop("disabled", false);
        return;
      } 
      
      

      if (!$("#txtProducto").val()) {
        console.log('producto');
        $("#txtProducto")[0].reportValidity();
        $("#txtProducto")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarProducto_FC").prop("disabled", false);
        return;
      } else if (!$("#txtClave_FC").val()) {
        console.log('clave');
        $("#txtClave_FC")[0].reportValidity();
        $("#txtClave_FC")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarProducto_FC").prop("disabled", false);
        return;
      } else if(!$("#cmbTipoProducto").val()){
        $("#cmbTipoProducto")[0].reportValidity();
        $("#cmbTipoProducto")[0].setCustomValidity("Completa este campo.");
        $("#btnAgregarProducto_FC").prop("disabled", false);
        return;
      } else {
        $.ajax({
          url: "../ventas_directas/php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_datosProd",
            is_from_Facturacion: 1,
            claveSat: claveSat,
            unidadSat: unidadSat,
            nombre: producto,
            clave: clave,
            tipo: tipo,
            cliente:cliente,
            existenciaFabricacion: existenciaFabricacion,
            idSucursal: idSucursal,
            idImpuestosArray: idImpuestosArray,
            tasaImpuestosArray: tasaImpuestosArray
          },
          success: function (data, status, xhr) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              //img: '<i class="fas fa-check-circle"></i>',
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Producto agregado con exito!",
            });
            $("#btnAgregarProducto_FC").prop("disabled", false);
            $("#addImpuesto").html("");
            $("#btnCancelar_newProd_FC").click();
            cmbImpuestos.destroy();
            tasaImpuestos.destroy();
            $("#txtTipoImpuesto").val(1);
            $("#trasladado").css("display","block");
            $("#retenciones").css("display","none");
            $("#local").css("display","none");  
            loadCombo(
              "productos",
              "#cmbProducto",
              "",
              "Seleccione un producto...",
              $('#cmbCliente').val()
            );
          },
          error: function (error) {
            $("#btnAgregarProducto_FC").prop("disabled", false);
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              img: null,
              msg: error,
            });
          },
        });
      }
    }
  } else {
    if (!$("#txtProducto").val()) {
      $("#invalid-nombreProducto").css("display", "block");
      $("#txtProducto").addClass("is-invalid");
    }
    if (!$("#txtClave_FC").val()) {
      $("#invalid-clave").css("display", "block");
      $("#txtClave_FC").addClass("is-invalid");
    }
    if (!$("#cmbTipoProducto").val()) {
      $("#invalid-tipoProd").css("display", "block");
      $("#cmbTipoProducto").addClass("is-invalid");
    }
    if (!$("#txtClaveSatId_NP").val() || $("#txtClaveSatId_NP").val()==0) {
      $("#invalid-claveSat").css("display", "block");
      $("#txtClaveSatId_NP").addClass("is-invalid");
    }
    if (!$("#txtUnidadMedidaId_NP").val() || $("#txtUnidadMedidaId_NP").val()==0) {
      $("#invalid-unidadSat").css("display", "block");
      $("#txtUnidadMedidaId_NP").addClass("is-invalid");
    }
  }
});

$(document).on("click", "#txtClaveSat_NP", function () {
  loadTableSat("#tabla_body_sat_NP", "#txtClaveSat_NP");
  $("#buscar_clave_sat_NP").val("");
  $("#agregar_Producto_FC").modal("hide");

  $(".modal").on("hidden.bs.modal", function () {
    if ($(".modal:visible").length) {
      $("body").addClass("modal-open");
    }
  });
  $("#agregar_clave_sat_NP").modal("show");
});

$(document).on("keyup", "#buscar_clave_sat_NP", function () {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_claveSatTableSearch",
      value: $(this).val(),
    },
    dataType: "json",
    success: function (response) {
      html = "";

      $.each(response, function (i) {
        html +=
          '<tr data-id="' +
          response[i].id +
          '">' +
          "<td>" +
          response[i].clave +
          "</td>" +
          "<td>" +
          response[i].descripcion +
          "</td>" +
          "</tr>";
      });

      if ($("#buscar_clave_sat_NP").val() !== "") {
        if (response.length > 0) {
          $("#tabla_body_sat_NP").html(html);
        } else if ($("#buscar_clave_sat_NP").val() !== "" && html === "") {
          $("#tabla_body_sat_NP").html(
            '<tr><td colspan="2">No se encontraron coincidencias...</td></tr>'
          );
        }
      } else {
        loadTableSat("#tabla_body_sat_NP", "#txtClaveSat_NP");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("click", "#tabla_body_sat_NP tr", function () {
  clave = $(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtClaveSat_NP").val(sat);
  $("#txtClaveSat_NP").trigger("change");
  $("#txtClaveSatId_NP").val(id);

  $("#agregar_clave_sat_NP").modal("hide");

  $(".modal").on("hidden.bs.modal", function () {
    if ($(".modal:visible").length) {
      $("body").addClass("modal-open");
    }
  });
  $("#agregar_Producto_FC").modal("show");
});

$(document).on("click", "#txtUnidadMedida_NP", function () {
  loadTableUnidadMedida("#tabla_body_medida_NP", "#txtUnidadMedida_NP");
  $("#buscar_clave_unidad_medida_NP").val("");
  $("#agregar_Producto_FC").modal("hide");

  $(".modal").on("hidden.bs.modal", function () {
    if ($(".modal:visible").length) {
      $("body").addClass("modal-open");
    }
  });

  $("#agregar_unidad_medida_NP").modal("show");
});

$(document).on("keyup", "#buscar_clave_unidad_medida_NP", function () {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_claveUnidadMedidaTableSearch",
      value: $(this).val(),
    },
    dataType: "json",
    success: function (response) {
      html = "";

      $.each(response, function (i) {
        html +=
          '<tr data-id="' +
          response[i].id +
          '">' +
          "<td>" +
          response[i].clave +
          "</td>" +
          "<td>" +
          response[i].descripcion +
          "</td>" +
          "</tr>";
      });

      if ($("#buscar_clave_unidad_medida_NP").val() !== "") {
        if (response.length > 0) {
          $("#tabla_body_medida_NP").html(html);
        } else if (
          $("#buscar_clave_unidad_medida_NP").val() !== "" &&
          html === ""
        ) {
          $("#tabla_body_medida_NP").html(
            '<tr><td colspan="2">No se encontraron coincidencias...</td></tr>'
          );
        }
      } else {
        loadTableUnidadMedida("#tabla_body_medida_NP", "#txtUnidadMedida_NP");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("click", "#tabla_body_medida_NP tr", function () {
  clave = $(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtUnidadMedida_NP").val(sat);
  $("#txtUnidadMedida_NP").trigger("change");

  $("#txtUnidadMedidaId_NP").val(id);

  $("#agregar_unidad_medida_NP").modal("hide");

  $(".modal").on("hidden.bs.modal", function () {
    if ($(".modal:visible").length) {
      $("body").addClass("modal-open");
    }
  });
  $("#agregar_Producto_FC").modal("show");
});

function is_customer_for_billing(idCliente){
  if (idCliente) {
    $.ajax({
      url: "php/funciones.php",
      method: "POST",
      dataType: "json",
      data: {
        clase: "get_data",
        funcion: "get_clienteBilling",
        idCliente,
      },
      datatype: "json",
      success: function (res) {
        if(
          res[0].rfc == 'N/A' || 
          res[0].rfc == '' || 
          res[0].rfc == null ||
          res[0].regimen_fiscal_id == 0 || 
          res[0].regimen_fiscal_id == '' || 
          res[0].regimen_fiscal_id == null ||
          res[0].razon_social == '' || 
          res[0].razon_social == null ||
          res[0].codigo_postal == 0 || 
          res[0].codigo_postal == '' || 
          res[0].codigo_postal == null
        ){
          cmbCliente.set(0);
          $("#link").html('<a class="btn btn-light" href="../clientes/catalogos/clientes/editar_cliente.php?c='+res[0].PKCliente+'">Editar</a>');
          $("#alert_custumer_no_billing").modal("show");
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}

function loadTableUnidadMedida(table, search) {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_claveUnidadMedidaTable",
    },
    dataType: "json",
    success: function (response) {
      html = "";

      $.each(response, function (i) {
        html +=
          '<tr data-id="' +
          response[i].id +
          '">' +
          "<td>" +
          response[i].clave +
          "</td>" +
          "<td>" +
          response[i].descripcion +
          "</td>" +
          "</tr>";
      });

      if ($(search).val() !== "") {
        if (response.length > 0) {
          $(table).html(html);
        } else if ($(search).val() !== "" && html === "") {
          $(table).html(
            '<tr><td colspan="2">No se encontraron coincidencias...</td></tr>'
          );
        }
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("click", "#tabla_body_medida tr", function () {
  clave = $(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtUnidadMedida").val(sat);
  $("#txtUnidadMedidaId").val(id);

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_claveSatUnidad",
      value: id,
      prod: $("#txtIdProd").val(),
    },
    datatype: "json",
    success: function (respuesta) {},
    error: function (error) {
      console.log(error);
    },
  });

  $("#agregar_unidad_medida").modal("hide");
  //$("#editarProducto").modal('handleUpdate');
  //$("#editarProducto").modal("show");

  $(".modal").on("hidden.bs.modal", function () {
    if ($(".modal:visible").length) {
      $("body").addClass("modal-open");
    }
  });
  $("#editarProducto").modal("show");
});

$(document).on("keyup", "#buscar_clave_unidad_medida", function () {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_claveUnidadMedidaTableSearch",
      value: $(this).val(),
    },
    dataType: "json",
    success: function (response) {
      html = "";

      $.each(response, function (i) {
        html +=
          '<tr data-id="' +
          response[i].id +
          '">' +
          "<td>" +
          response[i].clave +
          "</td>" +
          "<td>" +
          response[i].descripcion +
          "</td>" +
          "</tr>";
      });

      if ($("#buscar_clave_unidad_medida").val() !== "") {
        if (response.length > 0) {
          $("#tabla_body_medida").html(html);
        } else if (
          $("#buscar_clave_unidad_medida").val() !== "" &&
          html === ""
        ) {
          $("#tabla_body_medida").html(
            '<tr><td colspan="2">No se encontraron coincidencias...</td></tr>'
          );
        }
      } else {
        loadTableUnidadMedida("#tabla_body_medida", "#txtUnidadMedida");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("click", "#btnEditarProducto", function () {
  const tableTax = $("#tblDetalleImpuestosModal").DataTable();
  var taxes = tableTax.rows().data();

  var id_row = $("#txtIdReferencia").val();
  var producto_id = $("#txtIdProd").val();
  var sat_id = $("#txtClaveSatId").val();
  var id_unidad_medida = $("#txtUnidadMedidaId").val();
  var cantidad = $("#txtCantidadEdit").val();
  var precio_unitario = $("#txtPrecioUnitarioEdit").val();
  var subtotal = parseInt(cantidad) * parseFloat(precio_unitario);
  var descuento_tasa;
  var descuento_fijo;
  var predial = $("#txtPredial").val();
  var impuestos = "[";

  if ($("#txtDescuento").val() !== "") {
    var descuentoValor = $("#txtDescuento").val();
    var descuentoTipo = $("input[type=radio][name=tipoDescuento]").val();
    switch (descuentoTipo) {
      case "1":
        descuentoTotal = subtotal * (parseInt(descuentoValor) / 100);
        descuento_tasa = descuentoValor;
        break;
      case "2":
        descuento_fijo = descuentoValor;
        break;
    }
  } else {
    descuento_tasa = "";
    descuento_fijo = "";
  }

  $.each(taxes, function (i) {
    impuestos +=
      "{" +
      '"id":"' +
      taxes[i].id +
      '",' +
      '"tipo":"' +
      taxes[i].tipo +
      '",' +
      '"tasa":"' +
      taxes[i].tasa +
      '"' +
      "},";
  });

  impuestos = impuestos.substring(0, impuestos.length - 1);

  impuestos += "]";

  if (impuestos === "]") {
    impuestos = "[]";
  }

  var data =
    "{" +
    '"id":"' +
    id_row +
    '",' +
    '"referencia":"0",' +
    '"tipo_referencia":"0",' +
    '"producto_id":"' +
    producto_id +
    '",' +
    '"sat_id":"' +
    sat_id +
    '",' +
    '"unidad_medida_id":"' +
    id_unidad_medida +
    '",' +
    '"cantidad":"' +
    cantidad +
    '",' +
    '"precio_unitario":"' +
    precio_unitario +
    '",' +
    '"subtotal":"' +
    subtotal +
    '",' +
    '"descuento_tasa":"' +
    descuento_tasa +
    '",' +
    '"importe_descuento_tasa":"' +
    descuentoTotal +
    '",' +
    '"descuento_monto_fijo":"' +
    descuento_fijo +
    '",' +
    '"predial":"' +
    predial +
    '",' +
    '"factura_concepto":"' +
    '1'+
    '",' +
    '"impuestos":' +
    impuestos +
    
    
    "}";

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_dataProducto",
      value: data,
    },
    datatype: "json",
    success: function (respuesta) {
      $("#editarProducto").modal("hide");

      var id = "#tblDetalleProductos";
      var func = "get_productosEditTable";

      $(id).DataTable().clear().destroy();
      loadDataTableProducts(id, func, data, id_row);
      loadSubtotal();
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$("#agregarFactura").on("click", function () {
  //console.log(json_products_price);
  updateSpecialPrice(json_products_price);
  var payment_form = $("#cmbFormasPago").val();
  var payment_method = $("#cmbMetodoPago").val();
  if(validatePayment_form(payment_form,payment_method)){
    $("#agregarFactura").prop("disabled", true);
    var cliente = $("#cmbCliente").val();
    var id_document = 0;
    var tipo_documento = 0;
    var predial = "";

    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_productsAllSat",
        value: 1,
        tipo: 0,
        ref: 0
      },
      dataType: "json",
      success: function (respuesta) {
        
        if (respuesta === 1) {
          if ($("#datos-factura")[0].checkValidity()) {
            var badUsoCfdi =
              $("#invalid-usoCFDI").css("display") === "block" ? false : true;
            var badFormaPago =
              $("#invalid-formasPago").css("display") === "block" ? false : true;
            var badMetodoPago =
              $("#invalid-metodosPago").css("display") === "block" ? false : true;
            var badMoneda =
              $("#invalid-moneda").css("display") === "block" ? false : true;
            var badCuentaBancaria =
              $("#invalid-cuentaBancaria").css("display") === "block"
                ? false
                : true;
            var badFechaVencimiento =
              $("#invalid-fechaVencimiento").css("display") === "block"
                ? false
                : true;
              var badAfectarInventario =
                  $("#invalid-afectarInventario").css("display") === "block" ? false : true;
            var badRazonSocialPG = 
                $("#invalid-razonSocialPG").css("display") === "block" ? false : true;

            if (
              badUsoCfdi &&
              badFormaPago &&
              badMetodoPago &&
              badMoneda &&
              badCuentaBancaria &&
              badFechaVencimiento && 
              badAfectarInventario &&
              badRazonSocialPG
            ) {
              $("#loader").addClass("loader");

              if($("#chkPrefactura").is(":checked")){
                prefactura = 1;
              } else {
                prefactura = 0;
              }
              var afectar_inventario = $("#chkAfectarInventario").is(":checked") ? 1 : 0;
              var sucursal = $("#cmbSucursales").val();
              var id_prefactura = $("#txtPrefactura").val();
              var nota_cliente = $("#txaNotasCliente").val();
              var datetime = $("#txtFechaEmision").val();
              let ref = $("#txaReferencia").val();
              let aux = ref.split("\t").join(" ");
              aux = aux.split("\n").join(" ");
              aux = aux.split(" ");
              ref1 = "";
              for (let i = 0; i < aux.length; i++) {
                  if(aux[i] !== ""){
                      ref1 += aux[i] + " ";
                  }
              }

              let aux1 = nota_cliente.split("\t").join(", ");
              aux1 = aux1.split("\n").join(", ");
              aux1 = aux1.split(", ");
              nota = "";
              for (let i = 0; i < aux1.length; i++) {
                  if(aux1[i] !== ""){
                      nota += aux1[i] + ", ";
                  }
                  
              }
              
              var json =
                  "{" +
                  '"idDocumento":"' +
                  id_document +
                  '",' +
                  '"fechaEmision":"' +
                  datetime +
                  '",' +
                  '"tipoDocumento":"' +
                  tipo_documento +
                  '",' +
                  '"serie":"' +
                  $("#txtSerie").val() +
                  '",' +
                  '"folio":"' +
                  $("#txtFolio").val() +
                  '",' +
                  '"usoCfdi":"' +
                  $("#cmbUsoCFDI").val() +
                  '",' +
                  '"formaPago":"' +
                  $("#cmbFormasPago").val() +
                  '",' +
                  '"metodoPago":"' +
                  $("#cmbMetodoPago").val() +
                  '",' +
                  '"moneda":"' +
                  $("#cmbMoneda").val() +
                  '",' +
                  '"cliente":"' +
                  cliente +
                  '",' +
                  '"predial":"' +
                  predial +
                  '",' +
                  '"cuenta_bancaria":"' +
                  $("#cmbCuentaBancaria").val() +
                  '",' +
                  '"fecha_vencimiento":"' +
                  $("#txtFechaVencimiento").val() +
                  '",' +
                  '"vendedor":"' +
                  $("#cmbVendedor").val() + 
                  '",' +
                  '"referencia":"' +
                  ref1 + 
                  '",' +
                  '"prefactura":"' + prefactura +'",' +
                  '"id_prefactura":"' + id_prefactura +
                  '",' +
                  '"nota_cliente":"' + nota + 
                  '",' +
                  '"afectar_inventario":"'+afectar_inventario+
                  '"';
              var razon_social = $("#txtLegalNamePG").val();
              $("#legal_name_hidden").is(":visible") ? json += ',"razon_social":"'+razon_social+'"' : '';
              json += afectar_inventario === 1 ? ',"sucursal":"'+sucursal+'"' : "";
              json +="}";
            
              
              $.ajax({
                method: "POST",
                url: "php/funciones.php",
                data: {
                  clase: "save_data",
                  funcion: "save_factura",
                  data: json,
                  data1: nota_cliente,
                  value: 1,
                },
                success: function (respuesta) {
                  $("#agregarFactura").prop("disabled", true);
                  $("#agregarPrefactura").prop("disabled", true);
                  res = JSON.parse(respuesta);
                  $(".loader").fadeOut("slow");
                  $("#loader").removeClass("loader");
                  if (res.status === 0) {
                    Lobibox.notify("success", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/checkmark.svg",
                      msg: res.message,
                      sound: false,
                    });

                    setTimeout(function() {
                      window.location.href = 'php/download_zip.php?value='+res.id_factura;
                      //window.open(window.location.href = 'php/download_zip.php?value='+res.id_factura);
                    }, 500);
                    setTimeout(function() {
                      window.location.href = 'detalle_factura.php?idFactura='+res.id_factura;
                    }, 1500);
                  } else {
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3100,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/warning_circle.svg",
                      msg: res.message,
                    });
                    $("#agregarFactura").prop("disabled", false);
                    $("#agregarPrefactura").prop("disabled", false);
                    optionSalidas = new Array();
                  }
                },
                error: function (error) {
                  console.log(JSON.stringify(error));
                },
              });
            }
          } else {
            if (!$("#cmbUsoCFDI").val()) {
              $("#invalid-usoCFDI").css("display", "block");
              $("#cmbUsoCFDI").addClass("is-invalid");
            }

            if (!$("#cmbFormasPago").val()) {
              $("#invalid-formasPago").css("display", "block");
              $("#cmbFormasPago").addClass("is-invalid");
            }

            if (!$("#cmbMetodoPago").val()) {
              $("#invalid-metodosPago").css("display", "block");
              $("#cmbMetodoPago").addClass("is-invalid");
            }

            if (!$("#cmbMoneda").val()) {
              $("#invalid-moneda").css("display", "block");
              $("#cmbMoneda").addClass("is-invalid");
            }

            if ($("#cmbCuentaBancaria").attr("required")) {
              if (!$("#cmbCuentaBancaria").val()) {
                $("#invalid-cuentaBancaria").css("display", "block");
                $("#cmbCuentaBancaria").addClass("is-invalid");
              }
            }

            if ($("#txtFechaVencimiento").attr("required")) {
              if (!$("#txtFechaVencimiento").val()) {
                $("#invalid-fechaVencimiento").css("display", "block");
                $("#txtFechaVencimiento").addClass("is-invalid");
              }
            }
            if ($("#txtLegalNamePG").attr("required")) {
              if (!$("#txtLegalNamePG").val()) {
                $("#invalid-razonSocialPG").css("display", "block");
                $("#txtLegalNamePG").addClass("is-invalid");
              }
            }
          }
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Uno o más productos no tienen una clave del SAT",
          });
        }
      },
      error: function (error) {
        console.log(JSON.stringify(error));
      },
    });
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3100,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: 'El método de pago en parcialidades debe tener la forma de pago 99 - por definir',
    });
  }
});

$(document).on("click", "#tblDetalleImpuestosModal a", function () {
  var deleteTaxPos = $(this).closest("tr").index();
  var deleteTaxId = $(this).data("id");
  var tableTax = $("#tblDetalleImpuestosModal").DataTable();
  var tipo_documento = $("#cmbFacturarDesde").val();
  var id_document;
  var aux = $(this).closest("tr").index();
  var tasa = tableTax.row(aux).data()["tasa"];
  switch (tipo_documento) {
    case "1":
      id_document =
        $("#cmbCotizacion").val() !== "" && $("#cmbCotizacion").val() !== null
          ? $("#cmbCotizacion").val()
          : $("#txtCotizacion").val();
      cliente =
        $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null
          ? $("#cmbCliente").val()
          : $("#txtCotizacion").val();
      break;
    case "2":
      id_document =
        $("#cmbVentaDirecta").val() !== "" &&
        $("#cmbVentaDirecta").val() !== null
          ? $("#cmbVentaDirecta").val()
          : $("#txtVenta").val();
      cliente =
        $("#cmbCliente").val() !== "" && $("#cmbCliente").val() !== null
          ? $("#cmbCliente").val()
          : $("#txtVenta").val();
      break;
    case "3":
      id_document = $("#cmbPedido").val();
      break;
  }

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_taxProducto",
      producto: $("#txtIdProd").val(),
      value: deleteTaxId,
      id: $("#txtIdReferencia").val(),
      tasa: tasa,
      ref:0,
      tipo:0
    },
    datatype: "json",
    success: function (respuesta) {
      if (respuesta) {
        tableTax.rows(deleteTaxPos).remove().draw();
      } else {
        console.log("hubo un error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function truncateTablePRoducts(ref,type){
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_truncateTableProducts",
      value: 1,
      type:type,
      ref: ref
    },
    success: function(){
    },
    error: function(error){
      console.log(error);
    }
  });
}

$(document).on("click", "#txtClaveSat", function () {
  loadTableSat("#tabla_body_sat", "#txtClaveSat");
  $("#buscar_clave_sat").val("");
  $("#editarProducto").modal("hide");

  $(".modal").on("hidden.bs.modal", function () {
    if ($(".modal:visible").length) {
      $("body").addClass("modal-open");
    }
  });
  $("#agregar_clave_sat").modal("show");
});

function loadTableSat(table, search) {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_claveSatTable",
    },
    dataType: "json",
    success: function (response) {
      html = "";

      $.each(response, function (i) {
        html +=
          '<tr data-id="' +
          response[i].id +
          '">' +
          "<td>" +
          response[i].clave +
          "</td>" +
          "<td>" +
          response[i].descripcion +
          "</td>" +
          "</tr>";
      });

      if ($(search).val() !== "") {
        if (response.length > 0) {
          $(table).html(html);
        } else if ($(search).val() !== "" && html === "") {
          $(table).html(
            '<tr><td colspan="2">No se encontraron coincidencias...</td></tr>'
          );
        }
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("keyup", "#buscar_clave_sat", function () {
  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_claveSatTableSearch",
      value: $(this).val(),
    },
    dataType: "json",
    success: function (response) {
      html = "";

      $.each(response, function (i) {
        html +=
          '<tr data-id="' +
          response[i].id +
          '">' +
          "<td>" +
          response[i].clave +
          "</td>" +
          "<td>" +
          response[i].descripcion +
          "</td>" +
          "</tr>";
      });

      if ($("#buscar_clave_sat").val() !== "") {
        if (response.length > 0) {
          $("#tabla_body_sat").html(html);
        } else if ($("#buscar_clave_sat").val() !== "" && html === "") {
          $("#tabla_body_sat").html(
            '<tr><td colspan="2">No se encontraron coincidencias...</td></tr>'
          );
        }
      } else {
        loadTableSat("#tabla_body_sat", "#txtClaveSat");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("click", "#tabla_body_sat tr", function () {
  clave = $(this).children()[0].innerHTML;
  descripcion = $(this).children()[1].innerHTML;
  sat = clave + " - " + descripcion;
  id = $(this).data("id");
  $("#txtClaveSat").val(sat);
  $("#txtClaveSatId").val(id);

  $.ajax({
    method: "POST",
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_claveSat",
      value: id,
      prod: $("#txtIdProd").val(),
    },
    datatype: "json",
    success: function (respuesta) {},
    error: function (error) {
      console.log(error);
    },
  });

  $("#agregar_clave_sat").modal("hide");
  //$("#editarProducto").modal('handleUpdate');
  //$("#editarProducto").modal("show");

  $(".modal").on("hidden.bs.modal", function () {
    if ($(".modal:visible").length) {
      $("body").addClass("modal-open");
    }
  });
  $("#editarProducto").modal("show");
});

$(document).on("click", "#chkPredial", function () {
  if ($(this).is(":checked")) {
    $("#predial").css("display", "block");
  } else {
    $("#predial").css("display", "none");
  }
});

$(document).on("change", "#cmbUsoCFDI", function () {
  if ($(this).hasClass("is-invalid")) {
    $("#invalid-usoCFDI").css("display", "none");
    $("#cmbUsoCFDI").removeClass("is-invalid");
    // $("#agregarFactura").prop("disabled", false);
    // $("#agregarPrefactura").prop("disabled", false);
  }
});

$(document).on("change", "#cmbFormasPago", function () {
  if ($(this).hasClass("is-invalid")) {
    $("#invalid-formasPago").css("display", "none");
    $("#cmbFormasPago").removeClass("is-invalid");
    // $("#agregarFactura").prop("disabled", false);
    // $("#agregarPrefactura").prop("disabled", false);
  }
});

$(document).on("change", "#cmbMetodoPago", function () {
  if ($(this).hasClass("is-invalid")) {
    $("#invalid-metodosPago").css("display", "none");
    $("#cmbMetodoPago").removeClass("is-invalid");
    // $("#agregarFactura").prop("disabled", false);
    // $("#agregarPrefactura").prop("disabled", false);
  }
});

$(document).on("change", "#cmbMoneda", function () {
  if ($(this).hasClass("is-invalid")) {
    $("#invalid-moneda").css("display", "none");
    $("#cmbMoneda").removeClass("is-invalid");
    // $("#agregarFactura").prop("disabled", false);
    // $("#agregarPrefactura").prop("disabled", false);
  }
});

$(document).on("change", "#cmbCuentaBancaria", function () {
  if ($(this).hasClass("is-invalid")) {
    $("#invalid-cuentaBancaria").css("display", "none");
    $("#cmbCuentaBancaria").removeClass("is-invalid");
    // $("#agregarFactura").prop("disabled", false);
    // $("#agregarPrefactura").prop("disabled", false);
  }
});

$(document).on("change", "#txtFechaVencimiento", function () {
  if ($(this).hasClass("is-invalid")) {
    $("#invalid-fechaVencimiento").css("display", "none");
    $("#txtFechaVencimiento").removeClass("is-invalid");
    // $("#agregarFactura").prop("disabled", false);
    // $("#agregarPrefactura").prop("disabled", false);
  }
});

$(document).on("keyup", "#txtLegalNamePG", function () {
  if ($(this).hasClass("is-invalid")) {
    $("#invalid-razonSocialPG").css("display", "none");
    $("#txtLegalNamePG").removeClass("is-invalid");
    $("#agregarFactura").prop("disabled", false);
  }
});

$(document).on("click", "#chkPagoContado", function () {
  if ($(this).is(":checked")) {
    loadCombo(
      "cuentasBancarias",
      "#cmbCuentaBancaria",
      "",
      "Seleccione una cuenta bancaria..."
    );
    $("#comboCuentaBancaria").css("display", "block");
    $("#cmbCuentaBancaria").prop("required", true);
  } else {
    $("#cmbCuentaBancaria").val("");
    $("#comboCuentaBancaria").css("display", "none");
    $("#cmbCuentaBancaria").prop("required", false);
    $("#invalid-cuentaBancaria").css("display", "none");
    $("#cmbCuentaBancaria").removeClass("is-invalid");
    //$("#agregarFactura").prop("disabled", false);
    //$("#agregarPrefactura").prop("disabled", false);
  }
});

$(document).on("click", "#chkFechaVencimiento", function () {
  if ($(this).is(":checked")) {
    $("#txtFechaVencimiento").css("display", "block");
    $("#txtFechaVencimiento").prop("required", true);
  } else {
    $("#txtFechaVencimiento").val("");
    $("#txtFechaVencimiento").css("display", "none");
    $("#txtFechaVencimiento").prop("required", false);
    $("#invalid-fechaVencimiento").css("display", "none");
    $("#txtFechaVencimiento").removeClass("is-invalid");
    //$("#agregarFactura").prop("disabled", false);
    //$("#agregarPrefactura").prop("disabled", false);
  }
});

$(document).on("click", "#agregarPrefactura", function () {
  arr = [];
  data = $("#tblDetalleProductos").DataTable();
  aux = data.rows().data();

  $.redirect(
    "ver_prefactura.php",
    {
      folio: $("#txtFolio").val(),
      serie: $("#txtSerie").val(),
      razon_social: $("#cmbCliente option:selected").text(),
      fecha: $("#txtFechaEmision").val(),
      cfdi: $("#cmbUsoCFDI option:selected").text(),
      forma_pago: $("#cmbFormasPago option:selected").text(),
      metodo_pago: $("#cmbMetodoPago option:selected").text(),
      moneda: $("#cmbMoneda option:selected").text(),
      rfc: $("#rfc-cliente").val(),
      productos: aux.toArray(),
      subtotal: $("#subtotal").text(),
      impuestos: $("#impuestos").html(),
      total: $("#total").text(),
    },
    "POST",
    "_blank"
  );
});

function setRFC(idCliente) {
  if (idCliente) {
    $.ajax({
      url: "php/funciones.php",
      method: "POST",
      dataType: "json",
      data: {
        clase: "get_data",
        funcion: "get_rfc",
        idCliente,
      },
      datatype: "json",
      success: function (res) {
        if (res.status === "success") {
          $("#rfc-cliente").val(res.data)
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}

$(document).on("change","#cmbFormasPago",function(){
  if(parseInt($(this).val()) === 22){
    $("#chkPagoContado").attr("disabled",true);
  } else {
    $("#chkPagoContado").attr("disabled",false);
  }
});

$(document).on("click","#divChkPagoContado",function(){
  tblProductos = $("#tblDetalleProductos").DataTable();
  
  if($("#chkPagoContado").prop("disabled")){
    if($("#cmbFormasPago").val() === 22){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Debe de elegir una forma de pago diferente",
      })
    } else
    if($("#cmbCliente").val() === "" && $("#cmbCliente").val() !== null){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Debe de elegir un cliente antes",
      })
    } 
    // else
    // if(tblProductos.column(0).data().length === 0){
    //   Lobibox.notify("error", {
    //     size: "mini",
    //     rounded: true,
    //     delay: 3100,
    //     delayIndicator: false,
    //     position: "center top",
    //     icon: true,
    //     img: "../../img/timdesk/warning_circle.svg",
    //     msg: "Debe ingresar al menos un producto",
    //   })
    // }
  }

});

$(document).on("click","#divChkFechaVencimiento",function(){
  tblProductos = $("#tblDetalleProductos").DataTable();
  if($("#chkFechaVencimiento").prop("disabled")){
    if($("#cmbCliente").val() === "" && $("#cmbCliente").val() !== null){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Debe de elegir un cliente antes",
      })
    } 
    // else 
    // if(tblProductos.column(0).data().length === 0){
    //   Lobibox.notify("error", {
    //     size: "mini",
    //     rounded: true,
    //     delay: 3100,
    //     delayIndicator: false,
    //     position: "center top",
    //     icon: true,
    //     img: "../../img/timdesk/warning_circle.svg",
    //     msg: "Debe ingresar al menos un producto",
    //   })
    // }
  }

});
//divChkPrefactura
$(document).on("click","#divChkPrefactura",function(){
  tblProductos = $("#tblDetalleProductos").DataTable();
  if($("#chkPrefactura").prop("disabled")){
    if($("#cmbCliente").val() === "" && $("#cmbCliente").val() !== null){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3100,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "Debe de elegir un cliente antes",
      })
    } 
    // else
    // if(tblProductos.column(0).data().length === 0){
    //   Lobibox.notify("error", {
    //     size: "mini",
    //     rounded: true,
    //     delay: 3100,
    //     delayIndicator: false,
    //     position: "center top",
    //     icon: true,
    //     img: "../../img/timdesk/warning_circle.svg",
    //     msg: "Debe ingresar al menos un producto",
    //   })
    // }
  }
});

$(document).on("click","#chkAfectarInventario",(e)=>{
    const target = e.target;
    const table = $("#tblDetalleProductos").DataTable();
    if(table.data().count() > 0){
      $("#alert_table_void").modal("show");
      $("#btnAgregar_table_void").on("click",()=>{
        if(target.checked){
          $("#comboAfectarInventario").css("display", "block");
          $("#cmbSucursales").prop("required", true);
          loadCombo("sucursales", "#cmbSucursales", "", "Seleccione una sucursal");
        } else {
          $("#comboAfectarInventario").css("display", "none");
          $("#cmbSucursales").prop("required", false);
        }
        table.clear().draw();
        truncateTablePRoducts(0,0);
        loadSubtotal();
        cmbCliente.enable();
        $("#alert_table_void").modal("hide");
      });
      $("#btnCancelar_table_void").on("click",()=>{
        if(target.checked){
          target.checked = false;
        } else {
          target.checked = true;
        }
        $("#alert_table_void").modal("hide");
      });
    } else {
      if(target.checked){
        $("#comboAfectarInventario").css("display", "block");
        $("#cmbSucursales").prop("required", true);
        loadCombo("sucursales", "#cmbSucursales", "", "Seleccione una sucursal");
      } else {
          $("#comboAfectarInventario").css("display", "none");
          $("#cmbSucursales").prop("required", false);
         
      }
    }
    
      if(target.checked){
          $("#comboAfectarInventario").css("display", "block");
          $("#cmbSucursales").prop("required", true);
          loadCombo("sucursales", "#cmbSucursales", "", "Seleccione una sucursal");
          // if(table.data().count() > 0){ 
          //   table.clear().draw();
          //   truncateTablePRoducts(0,0);
          // }
      } else {
          $("#comboAfectarInventario").css("display", "none");
          $("#cmbSucursales").prop("required", false);
          
      }
    
});

$(document).on('input', '.cantidadProducto',  function(){
  
   var regexp = /[^0-9]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }

});

//url: "../catalogos/inventarios_productos/php/funciones.php",
function cargarCMBImpuestos(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_impuestos" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta impuestos: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKImpuesto) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKImpuesto +
          '" ' +
          selected +
          ' data-tipo="' +
          respuesta[i].FKTipoImpuesto +
          '" data-importe="' +
          respuesta[i].FKTipoImporte +
          '">' +
          respuesta[i].Nombre +
          "</option>";
      });

      CargarSlimImpuestos();

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBTasaImpuestos(data, input) {
  var valor = data;
  console.log("PKImpuestos: " + valor);

  var html = "";
  var selected;

  $.ajax({
    url: "../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_tasa_impuestos", data: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tasas: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKImpuesto_tasas) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKImpuesto_tasas +
          '" ' +
          selected +
          ">" +
          respuesta[i].Tasa +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

let cmbImpuestos;
function CargarSlimImpuestos() {
  cmbImpuestos = new SlimSelect({
    select: "#cmbImpuestos",
    deselectLabel: '<span class="">✖</span>',
  });

  CargarSlimTasaImpuestos();
}

let tasaImpuestos;
function CargarSlimTasaImpuestos() {
  tasaImpuestos = new SlimSelect({
    select: "#cmbTasaImpuestos",
    deselectLabel: '<span class="">✖</span>',
  });
}


function cambioImpuesto(producto) {
  var FKImpuesto = document.getElementById("cmbImpuestos").value;
  cargarCMBTasaImpuestos(FKImpuesto, "cmbTasaImpuestos");

  var tipo =
    document.getElementById("cmbImpuestos").options[
      document.getElementById("cmbImpuestos").selectedIndex
    ].dataset.tipo;
  var importe =
    document.getElementById("cmbImpuestos").options[
      document.getElementById("cmbImpuestos").selectedIndex
    ].dataset.importe;

  console.log("Tipo:" + tipo);
  console.log("Importe:" + importe);

  if (tipo == 1) {
    $("#trasladado").css("display", "block");
    $("#retenciones").css("display", "none");
    $("#local").css("display", "none");
    $("#txtTipoImpuesto").val("1");
  }
  if (tipo == 2) {
    $("#trasladado").css("display", "none");
    $("#retenciones").css("display", "block");
    $("#local").css("display", "none");
    $("#txtTipoImpuesto").val("2");
  }
  if (tipo == 3) {
    $("#trasladado").css("display", "none");
    $("#retenciones").css("display", "none");
    $("#local").css("display", "block");
    $("#txtTipoImpuesto").val("3");
  }

  $("#cmbTasaImpuestos").attr("readonly", false);

  var select = `<select class="cmbSlim" name="cmbTasaImpuestos" id="cmbTasaImpuestos" required="">
              </select> `;

  var inputNumber = `<input type='number' min='0' value='' name='cmbTasaImpuestos' id='cmbTasaImpuestos' class='form-control'>`;

  if (importe == 1) {
    $("#etiquetaImpuesto").text("Tasa:");
    $("#areaimpuestos").html(select);
    CargarSlimTasaImpuestos();
  }
  if (importe == 2) {
    $("#etiquetaImpuesto").text("Importe:");
    $("#areaimpuestos").html(inputNumber);
  }
  if (importe == 3) {
    $("#etiquetaImpuesto").html("Tasa:");
    $("#areaimpuestos").html(inputNumber);
    $("#cmbTasaImpuestos").attr("readonly", true);
  }

  $("#txtTipoTasa").val(importe);

  /*console.log("Valor impuesto" + FKImpuesto);
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_impuestoProducto",
      data: producto,
      data2: FKImpuesto,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta impuesto validado: ", data);

      if (parseInt(data[0]["existe"]) == 1) {

        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "El impuesto ya ha sido agregado.",
          sound: '../../../../../sounds/sound4'
        });
        console.log("¡Ya existe!");
      } else if (parseInt(data[0]["existe"]) == 2) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "No es posible añadir el impuesto excento.",
          sound: '../../../../../sounds/sound4'
        });
      } else if (parseInt(data[0]["existe"]) == 3) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "Ya se posee un impuesto de tipo excento.",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        var nota = document.getElementById("notaImpuesto");
        nota.setAttribute("type", "hidden");

        console.log("¡No existe!");
      }
    },
  });*/
}


let contadorImpuestos = 1;
function validarImpuesto(){

  let fila;
  let tipoImpuesto = $("#txtTipoImpuesto").val();
  let idImpuesto = $("#cmbImpuestos").val();
  let nombreImpuesto = $("#cmbImpuestos").find('option:selected').text();

  var elementType = $("#cmbTasaImpuestos").get(0).tagName;

  let tasaImpuesto;
  if(elementType === "SELECT"){
    tasaImpuesto = $("#cmbTasaImpuestos").find('option:selected').text();
  }
  if(elementType === "INPUT"){
    tasaImpuesto = $("#cmbTasaImpuestos").val();

    if((tasaImpuesto.trim() == '' || tasaImpuesto.trim() == 0) && idImpuesto != 5 && idImpuesto != 16){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "Necesitas agregar un valor al importe.",
        sound: '../../../sounds/sound4'
      });
      return;
    }
  }


  let idsImpuestos = document.querySelectorAll('.getIDImpuesto')

  let id, encontrados = 0, band1 = 0, band2 = 0;
  idsImpuestos.forEach((item) => {
    id = item.id.split('_');
    //console.log(id[1]);

    if(id[1] == idImpuesto){
      encontrados++;
    }

    if(id[1] == 1 && idImpuesto == 5){
      band1 = 1;
    }
    if(id[1] == 5 && idImpuesto == 1){
      band1 = 1;
    }

    if((id[1] == 2 || id[1] == 3) && idImpuesto == 16){
      band2 = 1;
    }
    if(id[1] == 16 && idImpuesto == 2){
      band2 = 1;
    }
    if(id[1] == 16 && idImpuesto == 3){
      band2 = 1;
    }

  });

  if(encontrados > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "No puedes volver a agregar el mismo impuesto.",
        sound: '../../../sounds/sound4'
      });
      return;
  }

  if(band1 > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "No es posible añadir el impuesto exento.",
        sound: '../../../sounds/sound4'
      });
      return;
  }

  if(band2 > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "No es posible añadir el impuesto exento.",
        sound: '../../../sounds/sound4'
      });
      return;
  }

  let nombreTipoImpuesto = '';
  if(tipoImpuesto == 1){
    nombreTipoImpuesto = 'Trasladado';
  }
  if(tipoImpuesto == 2){
    nombreTipoImpuesto = 'Retención';
  }
  if(tipoImpuesto == 3){
    nombreTipoImpuesto = 'Local';
  }
  
  fila = '<tr id="fila_' + idImpuesto + '" class="getIDImpuesto">' +
            '<td>' + nombreImpuesto + '</td>' +
            '<td>' + nombreTipoImpuesto + '</td>' +
            '<td>' + tasaImpuesto + '</td>' +
            '<td><img class="btnEdit" src="../../img/timdesk/delete.svg" id="btnEliminarImpuesto" onclick="eliminarImpuesto(' + idImpuesto + ');">' +
            '<input type="hidden" value="' + idImpuesto + '" id="idimpuesto_' + contadorImpuestos + '" name="idimpuesto_' + contadorImpuestos + '" />' +
            '<input type="hidden" value="' + tasaImpuesto + '" id="tasaimpuesto_' + contadorImpuestos + '" name="tasaimpuesto_' + contadorImpuestos + '" />' +
            '</td>'
          '</tr>'
  $("#addImpuesto").append(fila);
  contadorImpuestos++;
}


function eliminarImpuesto(fila){
  $("#fila_"+fila).closest('tr').remove();
}

function getAddProduct(json,sucursal)
{
  var table = $("#tblDetalleProductos").DataTable();
  var pos = table.rows().data();
  $.ajax({
    url: "php/funciones.php",
    method: "POST",
    data: {
      clase: "save_data",
      funcion: "save_productoConcepto",
      value: json,
      value1:sucursal
    },
    datatype: "json",
    success: function (respuesta) {
      
      cmbCliente.disable();
      cmbProductos.set(null);
      $("#txtPrecioUnitario").val("0.00");
      $("#txtCantidad").val("0");
      $("#txtPrecioUnitario").prop("disabled", true);
      $("#txtCantidad").prop("disabled", true);
      $("#cargarProducto").prop("disabled", true);
      res = JSON.parse(respuesta);
      $("#chkPagoContado").prop("disabled",false);
      $("#chkFechaVencimiento").prop("disabled",false);
      $("#chkPrefactura").prop("disabled",false);
      
      ban = "";
      if (pos.length > 0) {
        for (let i = 0; i < pos.length; i++) {
          if (pos[i].id === res[0].id) {
            ban = i;
          }
        }
        if (ban !== "") {

          i = ban;
          
          table.cell({ row: i, column: 0 }).data(res[0].id).draw(false);
          table.cell({ row: i, column: 1 }).data(res[0].edit).draw(false);
          table
            .cell({ row: i, column: 2 })
            .data(res[0].clave)
            .draw(false);
          table
            .cell({ row: i, column: 3 })
            .data(res[0].descripcion)
            .draw(false);
          table
            .cell({ row: i, column: 4 })
            .data(res[0].sat_id)
            .draw(false);
          table
            .cell({ row: i, column: 5 })
            .data(res[0].id_unidad_medida)
            .draw(false);
          table
            .cell({ row: i, column: 6 })
            .data(res[0].unidad_medida)
            .draw(false);
          table
            .cell({ row: i, column: 7 })
            .data(res[0].cantidad)
            .draw(false);
          table
            .cell({ row: i, column: 8 })
            .data(res[0].precio)
            .draw(false);
          table
            .cell({ row: i, column: 9 })
            .data(res[0].subtotal)
            .draw(false);
          table
            .cell({ row: i, column: 10 })
            .data(res[0].impuestos)
            .draw(false);
          table
            .cell({ row: i, column: 11 })
            .data(res[0].descuento)
            .draw(false);
          table
            .cell({ row: i, column: 12 })
            .data(res[0].importe_total)
            .draw(false);
        } else {
          table.row
            .add({
              id: res[0].id,
              edit: res[0].edit,
              clave: res[0].clave,
              descripcion: res[0].descripcion,
              sat_id: res[0].sat_id,
              id_unidad_medida: res[0].id_unidad_medida,
              unidad_medida: res[0].unidad_medida,
              cantidad: res[0].cantidad,
              precio: res[0].precio,
              subtotal: res[0].subtotal,
              impuestos: res[0].impuestos,
              descuento: res[0].descuento,
              importe_total: res[0].importe_total,
              alerta: res[0].alerta,
            })
            .draw(false);
        }
      } else {
        table.row
          .add({
            id: res[0].id,
            edit: res[0].edit,
            clave: res[0].clave,
            descripcion: res[0].descripcion,
            sat_id: res[0].sat_id,
            id_unidad_medida: res[0].id_unidad_medida,
            unidad_medida: res[0].unidad_medida,
            cantidad: res[0].cantidad,
            precio: res[0].precio,
            subtotal: res[0].subtotal,
            impuestos: res[0].impuestos,
            descuento: res[0].descuento,
            importe_total: res[0].importe_total,
            alerta: res[0].alerta,
          })
          .draw(false);
      }
      loadSubtotal();
      $("#agregarFactura").prop("disabled", false);
      $("#agregarPrefactura").prop("disabled", false);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function getStockProduct(value,value1,json,quantity,sucursal,sucursal_name)
{
  var table = $("#tblDetalleProductos").DataTable();
  var pos = table.rows().data();
  var table_quantity = 0;
  $.ajax({
    url: "php/funciones.php",
    method: "POST",
    data: {
        clase: "get_data",
        funcion: "get_stockProduct",
        value: value,
        value1: sucursal
    },
    datatype: "json",
    success: function (respuesta) {
      r = JSON.parse(respuesta);
      
      if(r[0].existencia !== null){
        if(parseFloat(r[0].existencia) >= parseFloat(quantity)){
          ban = "";
          if (pos.length > 0) {
            for (let i = 0; i < pos.length; i++) {
              if (parseInt(pos[i].id) === parseInt(value)) {
                ban = i;
              }
            }
            
            if (ban !== "") {
              i = ban;
              table_quantity = table.cell({ row: i, column: 7 }).data();
              console.log(parseFloat(table_quantity + parseFloat(quantity)),parseFloat(r[0].existencia));
              if(parseFloat(table_quantity + parseFloat(quantity)) <= parseFloat(r[0].existencia)){
                getAddProduct(json,sucursal);
              } else {
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 4000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "Las existencias de "+value1+" son menores a la cantidad pedida",
                });
              }
            } else {
              getAddProduct(json,sucursal);
            }
          } else {
            getAddProduct(json,sucursal);
          }
          
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "Las existencias de "+value1+" son menores a la cantidad pedida",
          });
        }
        
      } else {
        $("#txtCantidad").val("0");
        $("#txtPrecioUnitario").prop("disabled", true);
        $("#txtCantidad").prop("disabled", true);
        $("#cargarProducto").prop("disabled", true);
        cmbProductos.set(null);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 4000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "No hay existencias de "+value1+" en "+sucursal_name,
        });
      }
    },
    error: function (error) {
        return error;
    },
  });
}

function validatePayment_form(payment_form,payment_method)
{
  let ban = false;
  if(payment_method === 'PPD')
  {
    if(parseInt(payment_form) === 22)
    {
      ban = true;
    } else {
      bar = false;
    }
  } else {
    ban = true;
  }

  return ban;
}

function updateSpecialPrice(value)
{
    str = JSON.stringify(value);
    $.ajax({
        url: "php/funciones.php",
        method: "POST",
        data: {
            clase: "edit_data",
            funcion: "update_price",
            value: str
        },
        datatype: "json",
        success: function (respuesta) {
            console.log();
        },
        error: function (error) {
            return error;
        },
    })
}

function removeItemFromArr ( arr, item ) {
    var i = arr.findIndex( e => parseInt(e.producto_id) === parseInt(item) );
    if ( i !== -1 ) {
        arr.splice( i, 1 );
    }
}

$("#btnAgregarPersonal").on("click", function () {
    roles = cmbRoles.selected();
    if ($("#agregarPersonal")[0].checkValidity() && roles.length != 0) {
        var badNombre =
        $("#invalid-nombre").css("display") === "block" ? false : true;
        var badPrimerApellido =
        $("#invalid-primerApellido").css("display") === "block" ? false : true;
        var badGenero =
        $("#invalid-genero").css("display") === "block" ? false : true;
        var badEstado =
            $("#invalid-estado").css("display") === "block" ? false : true;
        if (
        badNombre &&
        badPrimerApellido &&
        badGenero &&
        badEstado
        ) {
        $("#btnAgregarPersonal").removeAttr("disabled");
        var nombre = $("#txtNombre").val().trim();
        var apellido = $("#txtPrimerApellido").val().trim();
        var genero = $("#cmbGenero").val();
        var estado = $("#cmbEstadoPersonal").val();

        if (!$("#txtNombre").val()) {
            $("#txtNombre")[0].reportValidity();
            $("#txtNombre")[0].setCustomValidity("Completa este campo.");
            return;
        } else if (!$("#txtPrimerApellido").val()) {
            $("#txtPrimerApellido")[0].reportValidity();
            $("#txtPrimerApellido")[0].setCustomValidity("Completa este campo.");
            return;
        } else if (!$("#cmbEstadoPersonal").val()) {
            $("#cmbEstadoPersonal")[0].reportValidity();
            $("#cmbEstadoPersonal")[0].setCustomValidity("Completa este campo.");
            return;
        } else {
            $.ajax({
            url: "php/funciones.php",
            type: "POST",
            data: {
                clase: 'save_data',
                funcion: 'save_personal',
                nombre: nombre,
                apellido: apellido,
                genero: genero,
                roles: roles,
                estado: estado
            },
            success: function (data, status, xhr) {
                $("#agregar_Personal").modal("toggle");
                $("#btnAgregarPersonal").trigger("reset");
                loadCombo("vendedores", "#cmbVendedor", "", "Seleccione un vendedor");
                Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                //img: '<i class="fas fa-check-circle"></i>',
                img: "../../../img/timdesk/checkmark.svg",
                msg: "¡Registro agregado!",
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
                img: "../../../img/timdesk/warning_circle.svg",
                img: null,
                msg: error,
                });
            },
            });
        }
        }
    } else {
        if (!$("#txtNombre").val()) {
        $("#invalid-nombre").css("display", "block");
        $("#txtNombre").addClass("is-invalid");
        }
        if (!$("#txtPrimerApellido").val()) {
        $("#invalid-primerApellido").css("display", "block");
        $("#txtPrimerApellido").addClass("is-invalid");
        }
        if (!$("#cmbEstadoPersonal").val()) {
        $("#invalid-estado").css("display", "block");
        }
        if (cmbRoles.selected().length === 0) {
        $("#invalid-roles").css("display", "block");
        }
    }
    $(this).prop("disabled", true);
});

function getRfcClient(value)
{
    $.ajax({
        url: "php/funciones.php",
        type: "POST",
        data: {
            clase: 'get_data',
            funcion: 'get_RfcClient',
            value: value
        },
        success: function (response) {
            var r = JSON.parse(response);
            if(r[0].rfc === 'XAXX010101000'){
                $('#legal_name_hidden').css("display","block");
                if($('#legal_name_hidden').is(":visible")){
                    $('#txtLegalNamePG').prop("required",true);
                }
            } else {
                $('#legal_name_hidden').css("display","none");
                $('#txtLegalNamePG').prop("required",false);
            }
        },
        error: function (error) {
            return error;
        },
    });
}

$(document).on("keyup","#txtLegalNamePG",()=>{
    $('#txtLegalNamePG').val($('#txtLegalNamePG').val().toUpperCase());
})