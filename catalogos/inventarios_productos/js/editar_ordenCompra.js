var _permissions = { 
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0
}

$(document).ready(function () {
  validate_Permissions(22,'url')

  var PKOrden = $("#txtPKOrden").val();
  //obtenerEditar(PKOrden);

  cargarTablaOrdenesCompraEdit(PKOrden);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_OrdenCompra",
      data: PKOrden,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtReferencia").val(respuesta[0].Referencia);
      $("#txtPKOrdenEncrip").val(respuesta[0].PKOrdenCompra);
      $("#txtFechaEmision").val(respuesta[0].FechaCreacion);
      $("#txtFechaEstimada").val(respuesta[0].FechaEstimada);
      cargarCMBCategorias(respuesta[0].categoria_id)
      cargarCMBSubcategorias(respuesta[0].categoria_id,respuesta[0].subcategoria_id)
      loadCombo(
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
      cargarCMBComprador(respuesta[0].comprador_id,"cmbComprador");
      cargarCMBCondicionPago(respuesta[0].condicion_Pago, "cmbCondicionPago");
      cargarCMBMoneda(respuesta[0].moneda,"cmbMoneda");
      cambioProveedor(respuesta[0].FKProveedor);
      $("#NotasProveedor").val(respuesta[0].NotasProveedor);
      $("#NotasInternas").val(respuesta[0].NotasInternas);
    },
    error: function (error) {
      console.log(error);
    },
  });

  $.ajax({
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
  });

  obtenerTotal();

  $("#txtFechaEstimada").change(function () {
    /*     var fechaMin = document.getElementById("txtFechaEstimadaMin").value;
    var fechaEntrega = document.getElementById("txtFechaEstimada").value;

    if (Date.parse(fechaEntrega) < Date.parse(fechaMin)) {
      Swal.fire({
        title: '<h3 style="color:white; arialRoundedEsp;">Fecha incorrecta<h3>',
        html: '<h5 style="color:white; arialRoundedEsp;">La fecha de entrega no puede ser menor a 7 días posteriores a la fecha de emisión.<h5>',
        icon: "error",
        showConfirmButton: false,
        iconColor: "#fff",
        width: "100rem",
        position: "top",
        background: "#d9534f",
        padding: "0",
        //timer: 5000
      }).then(function () {
        //No hacer nada
      });

      $("#txtFechaEstimada").val($("#txtFechaEstimadaMin").val());
    } */
  });

  new SlimSelect({
    select: "#cmbProveedor",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbComprador",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbCondicionPago",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbMoneda",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbDireccionEnvio",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbProducto",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbCategoriaCuenta",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbSubcategoriaCuenta",
    deselectLabel: '<span class="">✖</span>',
  });
});

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
                      <label for="usr">Clave del producto del proveedor:*</label>
                      <input type="text" class="form-control alphaNumeric-only" maxlength="255" name="txtClaveProducto" id="txtClaveProducto" required onkeyup="validEmptyInput(this)">
                      <div class="invalid-feedback" id="invalid-claveProd">El producto debe tener una clave.</div>
                  </div>
                </div>
              </div>`;
      $("#datosNew").html(html);
      //$("#txtPrecioUnitario").prop("disabled", false);
      loadCombo("", "cmbProducto", "producto", valor, "todoProducto");
    } else {
      //$("#txtPrecioUnitario").prop("disabled", true);
      loadCombo("", "cmbProducto", "producto", valor, "producto");
      html = ``;
      $("#datosNew").html(html);
    }
  });
  loadCombo("", "cmbProducto", "producto", valor);

  $("#cmbProducto").on("change", function () {
    $("#invalid-nombreProd").css("display", "none");
    $("#txtNombreProducto").removeClass("is-invalid");
    $("#invalid-claveProd").css("display", "none");
    $("#txtClaveProducto").removeClass("is-invalid");
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
          
            /* recupera el texto de la opción selecccionada, lo separa y precarga el texto en el nombre y clave 
            cuando es nuevo producto para el proveedor */
            var prodText = $("#cmbProducto option:selected").html();
            var aux = prodText.split("-");
            var auxlenght = aux.length;
            var prodName = aux[auxlenght-1].trim();
            var prodClave = "";

            for(i=0; i<auxlenght-1; i++){
              prodClave += aux[i]+"-"; 
            }

            prodClave=prodClave.substring(0,prodClave.length-1);

          $("#txtPrecioUnitario").val(0);
          $("#txtCantidad").val(0);
          $("#txtCantidadHis").val(1);
          $("#txtCantidad").prop("min", 1);
          $("#txtNombreProducto").val(prodName);
          $("#txtClaveProducto").val(prodClave);
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

$("#txtCantidad").on("change", function () {
  $("#invalid-cantidad").css("display", "none");
  $("#txtCantidad").removeClass("is-invalid");  
});

function agregarProd() {
  if ($("#frmOrdenCompra")[0].checkValidity() && $("#txtCantidad").val() > 0) {
    var badReferencia =
      $("#invalid-referencia").css("display") === "block" ? false : true;
    var badEmision =
      $("#invalid-emision").css("display") === "block" ? false : true;
    var badFechaEst =
      $("#invalid-fechaEst").css("display") === "block" ? false : true;
    var badProveedor =
      $("#invalid-proveedor").css("display") === "block" ? false : true;
    var badComprador =
      $("#invalid-comprador").css("display") === "block" ? false : true;
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
    var badMoneda =
      $("#invalid-moneda").css("display") === "block" ? false : true;
    var badCondicion =
      $("#invalid-condicionPago").css("display") === "block" ? false : true;
    if (
      (badReferencia,
      badEmision,
      badFechaEst,
      badProveedor,
      badComprador,
      badSucursal,
      badProducto,
      badPrecioUnit,
      badCantidad,
      badNombreProd,
      badClaveProd,
      badMoneda,
      badCondicion)
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

    if (!$("#cmbComprador").val() || $("#cmbComprador").val() < 1) {
      $("#invalid-comprador").css("display", "block");
      $("#cmbComprador").addClass("is-invalid");
    }

    if (!$("#cmbDireccionEnvio").val() || $("#cmbDireccionEnvio").val() < 1) {
      $("#invalid-sucursal").css("display", "block");
      $("#cmbDireccionEnvio").addClass("is-invalid");
    }

    if (!$("#cmbProducto").val() || $("#cmbProducto").val() < 1) {
      $("#invalid-producto").css("display", "block");
      $("#cmbProducto").addClass("is-invalid");
    }

    if (!$("#txtPrecioUnitario").val()) {
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

    var today = new Date();
    if ($("#txtFechaEstimada").val() < formatDate(today)){
      $("#invalid-fechaEst").css("display", "block");
      $("#txtFechaEstimada").addClass("is-invalid");
    }
  }
}

function formatDate(date) {
  var d = new Date(date),
      month = '' + (d.getMonth() + 1),
      day = '' + d.getDate(),
      year = d.getFullYear();

  if (month.length < 2) 
      month = '0' + month;
  if (day.length < 2) 
      day = '0' + day;

  return [year, month, day].join('-');
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
                    sound: '../../../../../sounds/sound4'
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
                    sound: '../../../../../sounds/sound4'
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
                sound: '../../../../../sounds/sound4'
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
                sound: '../../../../../sounds/sound4'
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
      var pkUsuario = $("#txtUsuario").val();
      var notasInternas = $("#NotasInternas").val();
      var notasProveedor = $("#NotasProveedor").val();
      var notasProveedor = $("#NotasProveedor").val();
      var PKOrden = $("#txtPKOrden").val();
      var comprador = $("#cmbComprador").val();
      var condicion_Pago = $("#cmbCondicionPago").val();
      var moneda = $("#cmbMoneda").val();
      var categoria = $("#cmbCategoriaCuenta").val();
      var subcategoria = $("#cmbSubcategoriaCuenta").val();


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
          datos8: comprador,
          datos9: condicion_Pago,
          datos10: moneda,
          datos11: categoria,
          datos12: subcategoria
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
                  sound: '../../../../../sounds/sound4'
                });
              }else{
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "¡Se actualizaron exitosamente los datos de la orden de compra.!",
                  sound: '../../../../../sounds/sound4'
                });

                setTimeout(function(){window.location.href = "../orden_compras"}, 1500);
                
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
              sound: '../../../../../sounds/sound4'
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
        sound: '../../../../../sounds/sound4'
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
          sound: '../../../../../sounds/sound4'
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
          sound: '../../../../../sounds/sound4'
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
            sound: '../../../../../sounds/sound4'
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
    sound: '../../../../../sounds/sound4'
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
      datos2: 1,
    },
    dataType: "json",
    success: function (respuesta) {
      //Recorrer las respuestas de la consulta
      var tasa = '';
      $.each(respuesta, function (i) {
        if (!$("#impuestos-head-" + respuesta[i].id).length) {
          if(respuesta[i].tasa == '' || respuesta[i].tasa == null){
            tasa = respuesta[i].tasa;
          }else{
            tasa = respuesta[i].tasa+'%';
          }

          html +=
            "<tr>" +
            /*'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+*/
            '<td style="text-align: right;" id="impuestos-head-' +
            respuesta[i].id +
            '">' +
            respuesta[i].nombre +
            "</td>" +
            '<td style="text-align: right;">' +
            tasa +
            " </td>" +
            '<td style="text-align: right;">.....</td>' +
            '<td style="text-align: right;"> $ ' +
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
      datos2: 1,
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

function cancelarOrdenCompra(valor) {
  $("#valorId").val(valor);
  //$('#cancelar_OrdenCompra').modal('show');
  Swal.fire({
    title:
      '<h3 style="arialRoundedEsp;">Cancelar orden de compra<h3>',
    html: '<h5 style="arialRoundedEsp;">¿Desea cancelar la orden de compra?.<h5>',
    icon: "warning",
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
      cancelar_OC();
    }
  });
}

function cancelar_OC(){
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
        setTimeout(function(){window.location.href = "../orden_compras"},1500);
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se notificó que se ha rechazado la orden de compra!",
          sound: '../../../../../sounds/sound4'
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
          sound: '../../../../../sounds/sound4'
        });
      }

      setTimeout(function(){window.location.href = '../orden_compras'},1500);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function comentarOrdenCompra() {
  /*var idEncrip = $("#txtPKOrdenEncrip").val();
  console.log("E: " + idEncrip);
  window.location.href = "comentarOrdenCompra.php?oc=" + idEncrip;*/
  var id = $("#txtPKOrden").val();
  window.location.href = "verOrdenCompra.php?oc=" + id;
}

/*function obtenerEditar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_estadoOrdenCompra",
      data: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta estado validado: ", data);
      if (parseInt(data[0]["existe"]) == 1) {
        //window.location.href = "editarOrdenCompra.php?oc="+id;

        console.log("¡Se encuentra en espera!");
      } else {
        console.log(hex_md5(id));
        window.location.href = "comentarOrdenCompra.php?oc=" + hex_md5(id);
        console.log(
          "¡Su estado ha cambiado, sólo puede ver la orden de compra!"
        );
      }
    },
  });
}*/

function obtenerVer(id) {
  /*console.log(id);
  var idEncrip = $("#inp-" + id).val();
  console.log("E: " + idEncrip);
  window.location.href = "comentarOrdenCompra.php?oc=" + idEncrip;*/

  var id = $("#txtPKOrden").val();
  window.location.href = "verOrdenCompra.php?oc=" + id;
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

function validate_Permissions(pkPantalla,pestana){
  $.ajax({
    url: '../../php/funciones.php',
    data:{clase:"get_data", 
          funcion:"validar_Permisos", 
          data:pkPantalla},
    dataType:"json",
    success: function(data) {
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;
        
      if (pestana == 'url'){
        if (_permissions.edit == '0'){
          window.location.href = "../orden_compras";
        }
      }
    }
  });
}

function cargarCMBCategorias(name)
{
  var html = '<option disabled value="f" selected>Seleccione una categoria</option>';
  $.ajax({
    type:'POST',
    url: "../../php/funciones.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_categorias"},
    success: function (data) {
      if (data !== "" && data !== null && data.length > 0) {
        $.each(data, function (i) {
          if (data[i].PKCategoria === name) {
            html += '<option value="' +
              data[i].PKCategoria +
              '" selected>' +
              data[i].Nombre+
              "</option>";
          } else {
            html +=
              '<option value="' +
              data[i].PKCategoria +
              '">' +
              data[i].Nombre+
              "</option>";
          }
        });
        // html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir categoría</option>';
      } else {
        // html += '<option value="vacio">No hay datos que mostrar</option><option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir categoría</option>';
      }
      $("#cmbCategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function cargarCMBSubcategorias(subCat,name)
{
  var html = '<option disabled value="f" selected>Seleccione una subcategoria</option>';
  $.ajax({
    type:'POST',
    url: "../../php/funciones.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_subcategorias",subCat:subCat},
    cache: false,
    success: function (data) {
      if (data !== "" && data !== null && data.length > 0) {
        
        $.each(data, function (i) {
            
            if(data[i].PKSubcategoria === name){
            html +=
                '<option value="' +
                data[i].PKSubcategoria +
                '" selected>' +
                data[i].Nombre+
                "</option>";
            } else {
            html +=
                '<option value="' +
                data[i].PKSubcategoria +
                '">' +
                data[i].Nombre+
                "</option>";
            }
        });
        // html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir subcategoría</option>';
      } else {
        // html += '<option value="vacio">No hay datos que mostrar</option><option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir subcategoría</option>';
      }
      
      $("#cmbSubcategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}