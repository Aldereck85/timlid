var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

$(document).ready(function () {
  validate_Permissions(22, "url");

  var PKOrden = $("#txtPKOrden").val();

  cargarTablaOrdenesCompraVer(PKOrden);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_OrdenCompra",
      data: PKOrden,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtReferencia").text(respuesta[0].Referencia);
      $("#txtPKOrdenEncrip").val(respuesta[0].PKOrdenCompra);
      $("#txtFechaEmision").text(respuesta[0].FechaCreacion);
      $("#txtFechaEstimada").text(respuesta[0].FechaEstimada);
      $("#txtmoneda").text(respuesta[0].mnd);
      $("#txtDomi").text(respuesta[0].sucur);
      $("#txtProveedor").text(respuesta[0].prov);
      $("#txtComprador").text(respuesta[0].compr);
      $("#txtImporte").text("$ " + dosDecimales(respuesta[0].Importe));
      $('#txtcategoria').text(respuesta[0].categoria)
      $('#txtsubcategoria').text(respuesta[0].subcategoria)

      respuesta[0].condicion_Pago = respuesta[0].condicion_Pago == 1 ? "Contado" : "Crédito";
      $("#txtCondicionPago").text(respuesta[0].condicion_Pago);

      /* loadCombo(
        respuesta[0].FKProveedor,
        "cmbProveedor",
        "proveedor",
        "",
        "proveedor"
      );
      loadCombo(
        respuesta[0].FKSucursal,
        "cmbDireccionEnvio",
        "sucursal",
        "",
        "sucursal"
      );
      cambioProveedor(respuesta[0].FKProveedor); */
      $("#NotasProveedor").text(respuesta[0].NotasProveedor);
      $("#NotasInternas").text(respuesta[0].NotasInternas);
      
      //cargarCMBComprador(respuesta[0].comprador_id,"cmbComprador");
      //cargarCMBCondicionPago(respuesta[0].condicion_Pago, "cmbCondicionPago");
      //cargarCMBMoneda(respuesta[0].moneda, "cmbMoneda");

      if (respuesta[0].FKEstatusOrden == "1") {
        var html = `<span data-toggle="modal" class="btn-table-custom btn-table-custom--green" name="btnAceptarOC"
                      onclick="aceptarOrdenCompra(2)"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-ACEPTAR VERDE NVO-01.svg">Aceptar</span>`;

        var html2 = `<button data-toggle="modal" class="btn-table-custom btn-table-custom--red" name="btnCancelarOC"
                      onclick="aceptarOrdenCompra(3);"><img style="width:1.5rem; vertical-align: top;" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg">Cancelar</button>`;

        var html3 = `<button class="btn-table-custom btn-table-custom--blue-light" name="btnEditarOC"
                      onclick="obtenerEditar();"><i class="fas fa-edit"></i> Editar</button>`;

        $("#btnAceptar").html(html);
        $("#btnCancelar").html(html2);
        $("#btnEditar").html(html3);
      } else {
        var text = "";
        var color = "";
        if (respuesta[0].FKEstatusOrden == "4") {
          text = "Vencida";
          color = "btn-table-custom--red";
        } else if (respuesta[0].FKEstatusOrden == "2") {
          text = "Aceptada";
          color = "btn-table-custom--turquoise";
        } else if (respuesta[0].FKEstatusOrden == "3") {
          text = "Cancelada";
          color = "btn-table-custom--red";
        } else if (respuesta[0].FKEstatusOrden == "5") {
          text = "Rechazada";
          color = "btn-table-custom--red";
        } else if (respuesta[0].FKEstatusOrden == "6") {
          text = "Aceptada-Demorada";
          color = "btn-table-custom--yellow";
        } else if (respuesta[0].FKEstatusOrden == "7") {
          text = "Cerrada";
          color = "btn-table-custom--red";
        } else if (respuesta[0].FKEstatusOrden == "8") {
          text = "Completa";
          color = "btn-table-custom--green";
        }

        var html = `<b class="btn-table-custom ${color}" style="margin-right:0px !important">Estatus: </b><span class="btn-table-custom ${color}" name="btnAgregarProducto">
                      <i class="far fa-check-circle"></i> ${text}</span>`;

        var html2 = `<span class="btn-table-custom--red" name="btnCancelarOC" title="La orden ha sido ${text}">
        <img style="width:1.5rem; vertical-align: top; color" src="../../../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg">Cancelar</span>`;

        var html3 = `<span class="btn-table-custom ${color}" name="btnEditarOC" title="La orden ha sido ${text}">
                        <i class="fas fa-edit"></i> Editar</span>`;

        $("#btnAceptar").html(html);
        $("#btnCancelar").html(html2);
        $("#btnEditar").html(html3);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });

  /* $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_fechaEntegraMin" },
    dataType: "json",
    success: function (respuesta) {
      $("#txtFechaEstimadaMin").val(respuesta);
      document.getElementById("txtFechaEstimada").min = respuesta;
    },
    error: function (error) {
      console.log(error);
    },
  }); */

  obtenerTotal();
});

function cargarTablaOrdenesCompraVer(pkOrden) {
  $("#tblListadoOrdenesCompra").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    searching: false,
    paging: false,
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
        funcion: "get_ordenesCompraTableVer",
        data: pkOrden,
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
    ],
  });
}

function cargarCMBComprador(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_comprador" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if(data === respuesta[i].PKComprador){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].PKComprador+'" '+selected+'>'+respuesta[i].Nombre+'</option>';
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCondicionPago(data, input) {
  var html = "", selected = "";

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_condicionPago" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if(data === respuesta[i].PKCondicion){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].PKCondicion+'" '+selected+'>'+respuesta[i].Condicion+'</option>';
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBMoneda(data, input) {
  var html = "", selected = "";

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_Moneda" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if(data === respuesta[i].PKTipoMoneda){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].PKTipoMoneda+'" '+selected+'>'+respuesta[i].TipoMoneda+'</option>';
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cambioProveedor(valor) {
  $("#chkcmbTodoProducto").prop("disabled", false);
  console.log("Valor:" + valor);
  loadCombo("", "cmbProducto", "producto", valor, "producto");
  $("#chkcmbTodoProducto").on("change", function () {
    if (this.checked) {
      Swal.fire(
        "El proveedor seleccionado no provee los productos listados",
        "Para agregarlo a la lista de los productos que provee, favor completar los campós.",
        "info"
      );
      html = `<div class="row">
                <div class="col-lg-6" id="">
                  <div className="form-group">
                    <label for="usr">Nombre del producto del proveedor:*</label>
                    <input type="text" class="form-control alphaNumeric-only" maxlength="255" name="txtNombreProducto" id="txtNombreProducto" required onkeyup="validEmptyInput(this)">
                    <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un nombre.</div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div className="form-group">
                      <label for="usr">Clave del producto del proveedor::*</label>
                      <input type="text" class="form-control alphaNumeric-only" maxlength="255" name="txtClaveProducto" id="txtClaveProducto" required onkeyup="validEmptyInput(this)">
                      <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave.</div>
                  </div>
                </div>
              </div>`;
      $("#datosNew").html(html);
      $("#txtPrecioUnitario").prop("disabled", false);
      loadCombo("", "cmbProducto", "producto", valor, "todoProducto");
    } else {
      $("#txtPrecioUnitario").prop("disabled", true);
      loadCombo("", "cmbProducto", "producto", valor, "producto");
      html = ``;
      $("#datosNew").html(html);
    }
  });
  loadCombo("", "cmbProducto", "producto", valor);

  $("#cmbProducto").on("change", function () {
    var prod = $("#cmbProducto").val();
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_precioProveedor",
        value: valor,
        value1: prod,
      },
      dataType: "json",
      success: function (respuesta) {
        //Activar el comoboe ingresar los datos respectivos
        if ($("#chkcmbTodoProducto").is(":checked")) {
          $("#txtPrecioUnitario").val(0);
          $("#txtCantidad").val(0);
          $("#txtCantidadHis").val(1);
          $("#txtCantidad").prop("min", 1);
        } else {
          if (respuesta[0].Precio != null) {
            $("#txtPrecioUnitario").val(respuesta[0].Precio);
            $("#invalid-precioUnit").css("display", "none");
            $("#txtPrecioUnitario").removeClass("is-invalid");
          }
          $("#txtCantidad").val(respuesta[0].CantidadMinima);
          $("#invalid-cantidad").css("display", "none");
          $("#txtCantidad").removeClass("is-invalid");

          $("#txtCantidadHis").val(respuesta[0].CantidadMinima);
          $("#txtCantidad").prop("min", respuesta[0].CantidadMinima);
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  });
}

function agregarProd() {
  if ($("#frmOrdenCompra")[0].checkValidity()) {
    var badReferencia =
      $("#invalid-referencia").css("display") === "block" ? false : true;
    var badEmision =
      $("#invalid-emision").css("display") === "block" ? false : true;
    var badFechaEst =
      $("#invalid-fechaEst").css("display") === "block" ? false : true;
    var badProveedor =
      $("#invalid-proveedor").css("display") === "block" ? false : true;
    var badSucursal =
      $("#invalid-sucursal").css("display") === "block" ? false : true;
    var badProducto =
      $("#invalid-producto").css("display") === "block" ? false : true;
    var badPrecioUnit =
      $("#invalid-precioUnit").css("display") === "block" ? false : true;
    var badCantidad =
      $("#invalid-cantidad").css("display") === "block" ? false : true;
    var badNombreProd =
      $("#invalid-nombreProd").css("display") === "block" ? false : true;
    var badClaveProd =
      $("#invalid-claveProd").css("display") === "block" ? false : true;
    if (
      (badReferencia,
      badEmision,
      badFechaEst,
      badProveedor,
      badSucursal,
      badProducto,
      badPrecioUnit,
      badCantidad,
      badNombreProd,
      badClaveProd)
    ) {
      //Obtener valores de los campos
      var idproducto = $("#cmbProducto").val();
      var PKOrden = $("#txtPKOrden").val();
      var cantidad = parseInt($("#txtCantidad").val());
      var pkProveedor = $("#cmbProveedor").val();
      var precio = $("#txtPrecioUnitario").val();
      var nombre = "";
      var clave = "";
      if ($("#chkcmbTodoProducto").is(":checked")) {
        nombre = $("#txtNombreProducto").val();
        clave = $("#txtClaveProducto").val();
      }

      if (!$("#chkcmbTodoProducto").is(":checked")) {
        //Validar producto
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "validar_productoOrdenCompraEdit",
            data: idproducto,
            data2: PKOrden,
            data3: pkProveedor,
          },
          dataType: "json",
          success: function (data) {
            console.log("respuesta nombre valida: ", data);
            /* Validar si ya existe el identificador con ese nombre*/
            if (parseInt(data[0]["existe"]) == 1) {
              console.log("¡Ya existe!");
              return;
            }
          },
        });
      }
      validarYGuardarProducto(
        idproducto,
        PKOrden,
        cantidad,
        pkProveedor,
        precio,
        nombre,
        clave
      );
    }
  } else {
    if (!$("#txtReferencia").val()) {
      $("#invalid-referencia").css("display", "block");
      $("#txtReferencia").addClass("is-invalid");
    }

    if (!$("#txtFechaEmision").val()) {
      $("#invalid-emision").css("display", "block");
      $("#txtFechaEmision").addClass("is-invalid");
    }

    if (!$("#txtFechaEstimada").val()) {
      $("#invalid-fechaEst").css("display", "block");
      $("#txtFechaEstimada").addClass("is-invalid");
    }

    if (!$("#cmbProveedor").val() || $("#cmbProveedor").val() < 1) {
      $("#invalid-proveedor").css("display", "block");
      $("#cmbProveedor").addClass("is-invalid");
    }

    if (!$("#cmbDireccionEnvio").val() || $("#cmbDireccionEnvio").val() < 1) {
      $("#invalid-sucursal").css("display", "block");
      $("#cmbDireccionEnvio").addClass("is-invalid");
    }

    if (!$("#cmbProducto").val() || $("#cmbProducto").val() < 1) {
      $("#invalid-producto").css("display", "block");
      $("#cmbProducto").addClass("is-invalid");
    }

    if (!$("#txtPrecioUnitario").val() || $("#txtPrecioUnitario").val() < 1) {
      $("#invalid-precioUnit").css("display", "block");
      $("#txtPrecioUnitario").addClass("is-invalid");
    }

    if (!$("#txtCantidad").val() || $("#txtCantidad").val() < 1) {
      $("#invalid-cantidad").css("display", "block");
      $("#invalid-cantidad").text("El producto debe tener una cantidad.");
      $("#txtCantidad").addClass("is-invalid");
    }

    if (!$("#chkcmbTodoProducto").is(":checked")) {
      if (
        parseInt($("#txtCantidad").val()) < parseInt($("#txtCantidadHis").val())
      ) {
        $("#invalid-cantidad").css("display", "block");
        $("#invalid-cantidad").text(
          "La cantidad no puede ser menor a la cantidad minima: " +
            $("#txtCantidadHis").val()
        );
        $("#txtCantidad").addClass("is-invalid");
      }
    }

    if ($("#chkcmbTodoProducto").is(":checked")) {
      if (!$("#txtNombreProducto").val()) {
        $("#invalid-nombreProd").css("display", "block");
        $("#txtNombreProducto").addClass("is-invalid");
      }

      if (!$("#txtClaveProducto").val()) {
        $("#invalid-claveProd").css("display", "block");
        $("#txtClaveProducto").addClass("is-invalid");
      }

      if (!$("#txtPrecioUnitario").val()) {
        $("#invalid-precioUnit").css("display", "block");
        $("#txtPrecioUnitario").addClass("is-invalid");
      }
    }
  }
}

function validarYGuardarProducto(
  idproducto,
  PKOrden,
  cantidad,
  pkProveedor,
  precio,
  nombre,
  clave
) {
  //Validar producto
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_productoOrdenCompraEdit",
      data: idproducto,
      data2: PKOrden,
      data3: pkProveedor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        Swal.fire({
          title:
            '<h3 style="arialRoundedEsp;">El producto ya se encuentra agregado<h3>',
          html: '<h5 style="arialRoundedEsp;">¿Desea agregar la nueva cantidad a la ya existente?.<h5>',
          icon: "success",
          showConfirmButton: true,
          focusConfirm: false,
          showCloseButton: false,
          showCancelButton: true,
          confirmButtonText: 'Si <i class="far fa-arrow-alt-circle-right"></i>',
          cancelButtonText: 'No <i class="far fa-times-circle"></i>',
          buttonsStyling: false,
          allowEnterKey: false,
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--blue",
            cancelButton: "btn-custom btn-custom--border-blue",
          },
        }).then((result) => {
          if (result.isConfirmed) {
            var element = document.getElementById("content");
            element.scrollIntoView();
            //actualización de datos a tabla
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_orden_compra",
                datos: idproducto,
                datos2: cantidad,
                datos3: PKOrden,
                datos4: pkProveedor,
              },
              dataType: "json",
              success: function (respuesta) {
                console.log("respuesta agregar orden de compra:", respuesta);

                if (respuesta[0].status) {
                  $("#tblListadoOrdenesCompra").DataTable().ajax.reload();
                  obtenerTotal();
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../../../img/timdesk/checkmark.svg",
                    msg: "¡Se actualizó la cantidad del producto en la orden de compra con éxito!",
                  });
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
                  });
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
        /*Agregar producto a la orden de compra*/
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_orden_compra",
            datos: idproducto,
            datos2: cantidad,
            datos3: PKOrden,
            datos4: pkProveedor,
            datos5: precio,
            datos6: nombre,
            datos7: clave,
          },
          dataType: "json",
          success: function (respuesta) {
            console.log("respuesta agregar orden de compra:", respuesta);

            if (respuesta[0].status) {
              $("#tblListadoOrdenesCompra").DataTable().ajax.reload();
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 4000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "¡Se guardó la orden de compra con éxito!",
              });
              obtenerTotal();
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
              });
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

  $("#txtPrecioUnitario").val("");
  if ($("#chkcmbTodoProducto").is(":checked")) {
    $("#txtNombreProducto").val("");
    $("#txtClaveProducto").val("");
    loadCombo(
      "",
      "cmbProducto",
      "producto",
      $("#cmbProveedor").val(),
      "todoProducto"
    );
  } else {
    loadCombo(
      "0",
      "cmbProducto",
      "producto",
      $("#cmbProveedor").val(),
      "producto"
    );
  }
  $("#cmbProveedor option:not(:selected)").remove();
  $("#txtCantidad").val("");
}

function enviarOrdenCompra() {
  var alerta = "";
  var table = $("#tblListadoOrdenesCompra").DataTable();
  var invalidDivs = document.querySelectorAll(".invalid-feedback");
  var isSomethingInvalid = false;
  invalidDivs.forEach((invalidDiv) => {
    console.log(invalidDiv.style.display);
    if (invalidDiv.style.display === "block") {
      isSomethingInvalid = true;
      return;
    } else {
      isSomethingInvalid = false;
    }
  });
  if (!isSomethingInvalid) {
    if (table.data().count()) {
      var fechaEntrega = $("#txtFechaEstimada").val();
      var direccionEntrega = $("#cmbDireccionEnvio").val();
      var datasplit = $("#Total").html().split(",");
      var importeBetha = "";
      for (var i = 0; i < datasplit.length; i++) {
        importeBetha += datasplit[i];
      }
      var importe = parseFloat(importeBetha);
      console.log("importe:", importe);
      var pkUsuario = $("#txtUsuario").val();
      var notasInternas = $("#NotasInternas").text();
      var notasProveedor = $("#NotasProveedor").text();
      var PKOrden = $("#txtPKOrden").val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_OrderPurchase",
          datos: fechaEntrega,
          datos2: direccionEntrega,
          datos3: importe,
          datos4: pkUsuario,
          datos5: notasInternas,
          datos6: notasProveedor,
          datos7: PKOrden,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          var id = $("#txtPKOrden").val();
          if (respuesta[0].status) {
            Swal.fire({
              icon: "success",
              title: "Actualización exitosa",
              text: `¿Deseas enviarle un correo electrónico al proveedor para notificarle?`,
              type: "question",
              showConfirmButton: true,
              showCancelButton: true,
              confirmButtonText:
                'Si <i class="far fa-arrow-alt-circle-right"></i>',
              cancelButtonText: 'No <i class="far fa-times-circle"></i>',
              buttonsStyling: false,
              allowEnterKey: false,
              customClass: {
                actions: "d-flex justify-content-around",
                confirmButton: "btn-custom btn-custom--blue",
                cancelButton: "btn-custom btn-custom--border-blue",
              },
            }).then((result) => {
              if (result.isConfirmed) {
                $("#modal_envio").load(
                  "functions/modal_envio.php?id=" +
                    $("#cmbProveedor").val() +
                    "&txtId=" +
                    id +
                    "&estatus=1&txtNotas=",
                  function () {
                    $("#datos_envio").modal("show");
                  }
                );
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "¡Se actualizaron exitosamente los datos de la orden de compra.!",
                });
              } else {
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "¡Se actualizaron exitosamente los datos de la orden de compra.!",
                });

                setTimeout(function () {
                  window.location.href = "../orden_compras";
                }, 1500);
              }
            });
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
            });
          }
        },
      });
    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡No hay productos agregados!",
      });
    }
  }
}

function obtenerIdOrdenCompraTempEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_OrdenCompra",
      data: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar producto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoOrdenesCompra").DataTable().ajax.reload();
        obtenerTotal();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el producto de la orden con éxito!",
        });
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
        });
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
  controladorTiempo = setTimeout(validarCant(id), 1000);
}

function validarCant(id) {
  valor = $("#cantidad-" + id).val();
  if (
    parseInt($("#cantidad-" + id).val()) < 1 ||
    $("#cantidad-" + id).val() == ""
  ) {
    $("#invalid-cantidad-" + id).css("display", "block");
    $("#invalid-cantidad-" + id).text("La cantidad debe de ser mayor a 0");
    $("#cantidad-" + id).addClass("is-invalid");
  } else if (
    parseInt($("#cantidad-" + id).val()) <
    parseInt($("#cantidadHis-" + id).val())
  ) {
    $("#invalid-cantidad-" + id).css("display", "block");
    $("#invalid-cantidad-" + id).text(
      "La cantidad debe de ser mayor o igual a la minima: " +
        $("#cantidadHis-" + id).val()
    );
    $("#cantidad-" + id).addClass("is-invalid");
  } else {
    $("#invalid-cantidad-" + id).css("display", "none");
    $("#cantidad-" + id).removeClass("is-invalid");

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_OrdenCompra_CantidadEdit",
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
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se actualizó la cantidad del producto en la orden de compra con éxito!",
          });
          $("#tblListadoOrdenesCompra").DataTable().ajax.reload();
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
  var html =
    "<option disabled selected hidden>Seleccione una " + name + "...</option>";
  var oculto;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_" + fun + "Combo", value: value },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta "+name+" combo:",respuesta);
      //console.log("count combo"+name,respuesta.length);
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

      $("#cmbProveedor").prop("disabled", true);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function lobby_notify(string, icono, classStyle, carpeta) {
  //console.log("icono", icono);
  //console.log("string", string);

  Lobibox.notify(classStyle, {
    size: "mini",
    rounded: true,
    delay: 4000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: false,
    img: "../../../../img/" + carpeta + icono,
    msg: string,
    sound: false,
  });

  return;
}

function obtenerTotal() {
  var PKOrden = $("#txtPKOrden").val();
  //Obtener subtotal
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_subTotalOrdenCompra",
      datos: PKOrden,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Subtotal").html(dosDecimales(respuesta[0].subtotal));
    },
    error: function (error) {
      console.log(error);
    },
  });

  var html = "";
  $("#impuestos").html(html);
  //Obtener impuestos
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_impuestoOrdenCompra",
      datos: PKOrden,
      datos2: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      //Recorrer las respuestas de la consulta
      var tasa = "";
      $.each(respuesta, function (i) {
        if (!$("#impuestos-head-" + respuesta[i].pkImpuesto+ respuesta[i].tasa).length) {
          if (respuesta[i].tasa == "" || respuesta[i].tasa == null) {
            tasa = respuesta[i].tasa;
          } else {
            tasa = respuesta[i].tasa + "%";
          }

          html +=
            "<tr style='text-align: right; color: var(--color-primario);'>" +
            /*'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+*/
            '<td style="text-align: right; color: var(--color-primario); width:auto;" id="impuestos-head-' +
            respuesta[i].pkImpuesto + respuesta[i].tasa +
            '"><b>' +
            respuesta[i].nombre +
            "</b>&nbsp&nbsp" +
            tasa +
            '&nbsp&nbsp$ ' +
            dosDecimales(respuesta[i].totalImpuesto) +
            "</td>" +
            "</tr>";
        }
      });
      $("#impuestos").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });

  //Obtener total
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_totalOrdenCompra",
      datos: PKOrden,
      datos2: 0,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Total").html(dosDecimales(respuesta[0].Total));
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}

function aceptarOrdenCompra(valor) {
  if (valor == 3) {
    $("#estatusIDCancelar").val(valor);
    $("#cancelar_OrdenCompra").modal("show");
  } else if (valor == 2) {
    $("#estatusIDAceptar").val(valor);
    $("#aceptar_OrdenCompra").modal("show");
  } else if (valor == 1) {
    $("#estatusIDReactivar").val(valor);
    $("#activar_OrdenCompra").modal("show");
  } else if (valor == 7) {
    $("#estatusIDCerrar").val(valor);
    $("#cerrar_OrdenCompra").modal("show");
  }
}

function updateEstatusOC(valor) {
  var id = $("#txtPKOrdenEncrip").val();
  console.log(valor);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_AceptarOrdenCompra",
      datos: id,
      datos2: valor,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        if (valor == 2) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se notificó que se ha aceptado la orden de compra.!",
          });
        } else if (valor == 3) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Se notificó que se ha cancelado la orden de compra.!",
          });
        } else if (valor == 1) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se notificó que se ha reactivado la orden de compra!",
          });
        } else if (valor == 7) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se ha cerrado la orden de compra!",
          });
        }
        $("#mytext").val("");

        setTimeout(function () {
          window.location.href = "../orden_compras";
        }, 1500);
      } else {
        Swal.fire(
          "Error",
          "¡Algo salio mal :(!, ¡Favor de intentarlo más tarde!",
          "warning"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cancelar_OC() {
  var id = $("#txtPKOrden").val();
  var valor = $("#valorId").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_AceptarOrdenCompra",
      datos: id,
      datos2: valor,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        setTimeout(function () {
          window.location.href = "../orden_compras";
        }, 1500);
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se notificó que se ha rechazado la orden de compra!",
        });
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
        });
      }

      setTimeout(function () {
        window.location.href = "../orden_compras";
      }, 1500);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function comentarOrdenCompra() {
  var idEncrip = $("#txtPKOrdenEncrip").val();
  window.location.href = "comentarOrdenCompra.php?oc=" + idEncrip;
}

function descargarOrdenCompra() {
  var idEncrip = $("#txtPKOrdenEncrip").val();

  window.location.href =
    "functions/descargar_OrdenCompra.php?txtId=" + idEncrip;
}

function obtenerEditar() {
  var id = $("#txtPKOrden").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_estadoOrdenCompra",
      data: id,
    },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        window.location.href = "editarOrdenCompra.php?oc=" + id;
      } else {
        obtenerVer(id);
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

function validate_Permissions(pkPantalla, pestana) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;

      if (pestana == "url") {
        if (_permissions.edit == "0") {
          window.location.href = "../orden_compras";
        }
      }
    },
  });
}
