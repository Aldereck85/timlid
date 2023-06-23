var _permissions = { 
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0
}

let cmbProveedor, cmbSucrusal, cmbComprador, cmbRoles, cmbProducto, cmbTipoProducto;

$(document).ready(function () {
  validate_Permissions(22,'url')
  
  eliminarOrdenTemp();

  cargarTablaOrdenesCompra();
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_referencia" },
    dataType: "json",
    success: function (respuesta) {
      $("#txtReferencia").val(respuesta);
    },
    error: function (error) {
      console.log(error);
    },
  });

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_fechaEmision" },
    dataType: "json",
    success: function (respuesta) {
      $("#txtFechaEmision").val(changeFormateDate(respuesta));
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
      $("#txtFechaEstimada").val(changeFormateDate(respuesta));
    },
    error: function (error) {
      console.log(error);
    },
  });

  obtenerTotal();

  function changeFormateDate(oldDate) {
    return oldDate.toString().split("/").reverse().join("/");
  }

  $("#txtFechaEstimada").change(function () {
    /* var fechaMin = document.getElementById("txtFechaEstimadaMin").value;
    var fechaEntrega = document.getElementById("txtFechaEstimada").value;

    if (Date.parse(fechaEntrega) < Date.parse(fechaMin)) {
      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/checkmark.svg",
        msg: "¡La fecha de entrega no puede ser menor a 7 días posteriores a la fecha de emisión.!",
        sound: '../../../../../sounds/sound4'
      });

      $("#txtFechaEstimada").val($("#txtFechaEstimadaMin").val());
    } */
  });

  cmbProveedor = new SlimSelect({
    select: "#cmbProveedor",
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Proveedor").modal("show");
      return;
    }
  });

  cmbComprador = new SlimSelect({
    select: "#cmbComprador",
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Empleado").modal("show");    
      return;
    }
  });

  new SlimSelect({
    select: "#cmbCondicionPago",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbMoneda",
    deselectLabel: '<span class="">✖</span>',
  });

  cmbSucrusal = new SlimSelect({
    select: "#cmbDireccionEnvio",
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Locacion").modal("show");
      return;
    }
  });

  cmbProducto = new SlimSelect({
    select: "#cmbProducto",
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Producto").modal("show");
      return;
    }
  });

  new SlimSelect({
    select: "#cmbTipoPer",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#txtarea8",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#txtarea6",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbGenero",
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbEstado",
    deselectLabel: '<span class="">✖</span>',
  });

  cmbRoles = new SlimSelect({
    select: "#cmbRoles",
    deselectLabel: '<span class="">✖</span>',
    onChange: (info) => {
      $("#invalid-roles").css("display", "none");
    }
  });

  cmbTipoProducto = new SlimSelect({
    select: "#cmbTipoProducto",
    deselectLabel: '<span class="">✖</span>',
    onChange: (info) => {
      $("#invalid-tipoProd").css("display", "none");
    }
  });

  cmbCategoriaCuenta = new SlimSelect({
    select: '#cmbCategoriaCuenta', 
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
        $("#agregar_categoria").modal("show");
      }
  })

  cmbSubcategoriaCuenta = new SlimSelect({
    select: '#cmbSubcategoriaCuenta', 
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
        $("#agregar_subcategoria").modal("show");
        return;
      }
  });

  cmbAddCategoria = new SlimSelect({
    select: '#cmbAddCategoria', 
    deselectLabel: '<span class="">✖</span>',
    
  });


  loadCombo("", "cmbProveedor", "proveedor", "", "proveedor");

  cargarCMBComprador("","cmbComprador");
 
  cargarCMBCondicionPago("", "cmbCondicionPago");

  cargarCMBMoneda("", "cmbMoneda");

  loadCombo("", "cmbDireccionEnvio", "sucursal", "", "sucursal");

  cargarCMBTipo("cmbTipoProducto");

  cargarCMBCategorias('');

  //cmbSubcategoriaCuenta.disable();
});

function eliminarOrdenTemp() {
  var pkUs = $("#txtUsuario").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_OrdenCompraTempAll",
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

function cambioProveedor() {
  $("#chkcmbTodoProducto").prop("disabled", false);
  var valor = $("#cmbProveedor").val();
  if (valor) {
    $("#invalid-proveedor").css("display", "none");
    $("#cmbProveedor").removeClass("is-invalid");
    if(valor == 'add'){
      cmbProveedor.set(0);
      $("#agregar_Proveedor").modal("show");
    }

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
                  <div class="form-group">
                    <label for="usr">Nombre del producto del proveedor:*</label>
                    <input type="text" class="form-control alphaNumeric-only" maxlength="255" name="txtNombreProducto" id="txtNombreProducto" required onkeyup="validEmptyInput(this)">
                    <div class="invalid-feedback" id="invalid-nombreProd">El producto debe tener un nombre.</div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
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
        //$("#txtPrecioUnitario").prop("disabled", false);
        loadCombo("", "cmbProducto", "producto", valor, "producto");
        html = ``;
        $("#datosNew").html(html);
      }
    });
    loadCombo("", "cmbProducto", "producto", valor);

    $("#cmbProducto").on("change", function () {
      $("#invalid-producto").css("display", "none");
      $("#cmbProducto").removeClass("is-invalid");
      $("#invalid-nombreProd").css("display", "none");
      $("#txtNombreProducto").removeClass("is-invalid");
      $("#invalid-claveProd").css("display", "none");
      $("#txtClaveProducto").removeClass("is-invalid");
      var prod = $("#cmbProducto").val();
      if(prod == 'add'){
        cmbProducto.set(0);
        $("#agregar_Producto").modal("show");           
      }else{
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
              $("#txtCantidad").prop("min", respuesta[0].CantidadMinima);
  
              $("#txtCantidadHis").val(respuesta[0].CantidadMinima);
              $("#txtCantidadHis").val(respuesta[0].CantidadMinima);
            }
          },
          error: function (error) {
            console.log(error);
          },
        });
      }
    });
  }
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
    var badCategoria = 
    $("#invalid-categoriaCuenta").css("display") === "block" ? false : true;
    var badSubcategoria = 
    $("#invalid-subcategoriaCuenta").css("display") === "block" ? false : true;
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
      badCondicion,
      badCategoria,
      badSubcategoria)
    ) {
      //Obtener valores de los campos
      var idproducto = $("#cmbProducto").val();
      var pkUsuario = $("#txtUsuario").val();
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
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "validar_productoOrdenCompra",
            data: idproducto,
            data2: pkUsuario,
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
        pkUsuario,
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

    if (!$("#cmbMoneda").val() || $("#cmbMoneda").val() < 1) {
      $("#invalid-moneda").css("display", "block");
      $("#cmbMoneda").addClass("is-invalid");
    }
    if (!$("#cmbCondicionPago").val()) {
      $("#invalid-condicionPago").css("display", "block");
      $("#cmbCondicionPago").addClass("is-invalid");
    }
    if (!$("#cmbCategoriaCuenta").val()) {
        $("#invalid-categoriaCuenta").css("display", "block");
        $("#cmbCategoriaCuenta").addClass("is-invalid");
    }
    if (!$("#cmbSubcategoriaCuenta").val()) {
    $("#invalid-subcategoriaCuenta").css("display", "block");
    $("#cmbSubcategoriaCuenta").addClass("is-invalid");
    }
  }
}

function validarYGuardarProducto(
  idproducto,
  pkUsuario,
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
      funcion: "validar_productoOrdenCompra",
      data: idproducto,
      data2: pkUsuario,
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
          icon: "question",
          showConfirmButton: true,
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
                funcion: "edit_orden_compraTemp",
                datos: idproducto,
                datos2: cantidad,
                datos3: pkUsuario,
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
                    msg: "¡Se actualizó la cantidad del producto en la orden de compra!",
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
            funcion: "save_orden_compraTemp",
            datos: idproducto,
            datos2: cantidad,
            datos3: pkUsuario,
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
  //$('#chkcmbTodoProducto').prop('checked',false);
  $("#txtPrecioUnitario").val("");
  $("#cmbProveedor option:not(:selected)").remove();
  $("#txtCantidad").val("");
}

function enviarOrdenCompra() {
  $("#btnAgregar").prop("disabled", true);
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
      var referencia = $("#txtReferencia").val();
      var fechaEmision = $("#txtFechaEmision").val();
      var fechaEntrega = $("#txtFechaEstimada").val();
      var proveedor = $("#cmbProveedor").val();
      var comprador = $("#cmbComprador").val();
      var moneda = $("#cmbMoneda").val();
      var condicionPago = $("#cmbCondicionPago").val();
      var direccionEntrega = $("#cmbDireccionEnvio").val();
      var datasplit = $("#Total").html().split(",");
      var importeBetha = "";
      for (var i = 0; i < datasplit.length; i++) {
        importeBetha += datasplit[i];
      }
      var importe = parseFloat(importeBetha);
      var descuento = $("#txtDescuento").val();;
      var pkUsuario = $("#txtUsuario").val();
      var notasInternas = $("#NotasInternas").val();
      var notasProveedor = $("#NotasProveedor").val();
      var categoria = $("#cmbCategoriaCuenta").val();
      var subcategoria = $("#cmbSubcategoriaCuenta").val()

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_OrderPurchase",
          datos: referencia,
          datos2: fechaEmision,
          datos3: fechaEntrega,
          datos4: proveedor,
          datos5: direccionEntrega,
          datos6: importe,
          datos7: pkUsuario,
          datos8: notasInternas,
          datos9: notasProveedor,
          datos10: comprador,
          datos11: condicionPago,
          datos12: descuento,
          datos13: moneda,
          datos14: categoria,
          datos15: subcategoria
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            Swal.fire({
              icon: "success",
              title: "Registro exitoso",
              text: "¿Deseas enviarle un correo electrónico al proveedor?",
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
                    respuesta[0].id +
                    "&estatus=0&txtNotas=",
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
                  msg: "¡Se registró exitosamente los datos de la orden de compra.!",
                  sound: '../../../../../sounds/sound4'
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
                  msg: "¡Se registró exitosamente los datos de la orden de compra.!",
                  sound: '../../../../../sounds/sound4'
                });

                setTimeout(function(){window.location.href = 'index.php'},1000);
              }
            });
          } else {
            if (respuesta[0].id == 0) {
              $("#btnAgregar").prop("disabled", true);
              $("#modal_envio").load(
                "functions/modal_envio.php?id=" +
                  $("#cmbProveedor").val() +
                  "&txtId=" +
                  respuesta[0].id +
                  "&estatus=0&txtNotas=",
                function () {
                  $("#datos_envio").modal("show");
                }
              );
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
              
              $("#btnAgregar").prop("disabled", false);
            }
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
      
      $("#btnAgregar").prop("disabled", false);
    }
  }else{
    $("#btnAgregar").prop("disabled", false);
  }
}

function obtenerIdOrdenCompraTempEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_OrdenCompraTemp",
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
  controladorTiempo = setTimeout(validarCant(id), 3000);
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
        funcion: "edit_OrdenCompra_Cantidad",
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

        html +=
          `<option value="${respuesta[i].PKComprador}">${respuesta[i].Nombre}</option>`;
      });
      html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir comprador</option>';
      $("#" + input + "").html(html);

      $("#" + input + "").val(data);
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
        if(data === respuesta[i].PKTipoMoneda || (data == "" && respuesta[i].PKTipoMoneda == 100)){
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
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir ' + name + '</option>';
      } else {
        html += '<option value="vacio">No hay datos que mostrar</option><option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir ' + name + '</option>';
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
  var pkUsu = $("#txtUsuario").val();
  //Obtener subtotal
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_subTotalOrdenCompraTemp",
      datos: pkUsu,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#Subtotal").html(dosDecimales(respuesta[0].subtotal));
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
      funcion: "get_impuestoOrdenCompraTemp",
      datos: pkUsu,
    },
    dataType: "json",
    success: function (respuesta) {
      //Recorrer las respuestas de la consulta
      $.each(respuesta, function (i) {
        var tasa = '';
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
      funcion: "get_totalOrdenCompraTemp",
      datos: pkUsu,
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

function saveDescuento(){
  valor = $("#txtDescuento").val();
  
  if (parseInt($("#txtDescuento").val()) < 0 || $("#txtDescuento").val() == "") {
    $("#invalid-descuento").css("display", "block");
    $("#invalid-descuento").text("El descuento no puede ser menor a 0");
    $("#txtDescuento").addClass("is-invalid");
  } else {
    $("#invalid-descuento").css("display", "none");
    $("#txtDescuento").removeClass("is-invalid");

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_OrdenCompra_Descuento",
        datos: valor,
      },
      dataType: "json",
      success: function (respuesta) {
        
        if (respuesta[0].status) {
          console.log("Actualización exitosa");
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
        if (_permissions.add == '0'){
          window.location.href = "../orden_compras";
        }
      }
    }
  });
}

$(document).on("click", "#btnAgregarProveedor", function () {
  if ($("#agregarProveedor")[0].checkValidity()) {
    var badNombreCom =
      $("#invalid-nombreCom").css("display") === "block" ? false : true;
    var badContacto =
      $("#invalid-contacto").css("display") === "block" ? false : true;
    var badTelefono =
      $("#invalid-telefonoProv").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-correoProv").css("display") === "block" ? false : true;
    var badTipoPer =
      $("#invalid-tipoPer").css("display") === "block" ? false : true;
    if (
      badNombreCom &&
      badContacto &&
      badTelefono &&
      badEmail &&
      badTipoPer
    ) {

      $("#btnAgregarProveedor").prop("disabled", true);

      var nombreCom = $("#txtNombreCom").val();
      var contacto = $("#txtContactoProv").val();
      var telefono = $("#txtTelefono").val();
      var email = $("#txtEmail").val();
      var tipo = $("#cmbTipoPer").val();

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_proveedor",
          datos: nombreCom,
          datos2: contacto,
          datos3: telefono,
          datos4: email,
          datos5: tipo
        },
        success: function (respuesta,status,xhr) {
          console.log(respuesta);
          console.log(status);
          console.log(xhr);
          $("#btnAgregarProveedor").prop("disabled", false);

          if (respuesta == '"exito"') {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se guardó el proveedor con éxito!",
              sound: '../../../../../sounds/sound4'
            });
            $("#agregar_Proveedor").modal("toggle");
            $("#agregarProveedor").trigger("reset");
            location.reload();
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡No se guardó el proveedor con éxito :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error,data,xhr) {
          console.log(error);
          console.log(data);
          console.log(xhr);
          $("#btnAgregarProveedor").prop("disabled", false);
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
        },
      });
    }
  } else {
    console.log("Faltan campos");
    if (!$("#txtNombreCom").val()) {
      $("#invalid-nombreCom").css("display", "block");
      $("#txtNombreCom").addClass("is-invalid");
    }
    if (!$("#txtContactoProv").val()) {
      $("#invalid-contacto").css("display", "block");
      $("#txtContactoProv").addClass("is-invalid");
    }
    if (!$("#txtTelefono").val()) {
      $("#invalid-telefonoProv").css("display", "block");
      $("#txtTelefono").addClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-correoProv").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    }
    if (!$("#cmbTipoPer").val()) {
      $("#invalid-tipoPer").css("display", "block");
      $("#cmbTipoPer").addClass("is-invalid");
    }
  }
});

$("#cmbCondicionPago").on("change", ()=>{
  $("#invalid-condicionPago").css("display", "none");
  $("#cmbCondicionPago").removeClass("is-invalid");
});

$("#cmbMoneda").on("change", ()=>{
  $("#invalid-moneda").css("display", "none");
  $("#cmbMoneda").removeClass("is-invalid");
});

$("#txtNombreCom").on("change", ()=>{
  $("#invalid-nombreCom").css("display", "none");
  $("#txtNombreCom").removeClass("is-invalid");  
});

$("#txtContactoProv").on("change", ()=>{
  $("#invalid-contacto").css("display", "none");
  $("#txtContactoProv").removeClass("is-invalid");  
});

$("#txtTelefono").on("change", ()=>{
  $("#invalid-telefonoProv").css("display", "none");
  $("#txtTelefono").removeClass("is-invalid");  
});

$("#txtEmail").on("change", ()=>{
  $("#invalid-correoProv").css("display", "none");
  $("#txtEmail").removeClass("is-invalid");  
});

$("#cmbTipoPer").on("change", ()=>{
  $("#invalid-tipoPer").css("display", "none");
  $("#cmbTipoPer").removeClass("is-invalid");  
});

$("#cmbCategoriaCuenta").on("change", ()=>{
    $("#invalid-categoriaCuenta").css("display", "none");
    $("#cmbCategoriaCuenta").removeClass("is-invalid");  
    cmbSubcategoriaCuenta.enable();
  });

$("#cmbSubcategoriaCuenta").on("change", ()=>{
    $("#invalid-subcategoriaCuenta").css("display", "none");
    $("#cmbSubcategoriaCuenta").removeClass("is-invalid");  
});

$("#btnCancelarActualizacion").on("click", ()=>{
  $("#agregarProveedor").trigger("reset");
  $("#agregarLocacion").trigger("reset");
  $("#agregarEmpleado").trigger("reset");
  $("#agregarProductoForm").trigger("reset");
  $("#invalid-nombreCom").css("display", "none");
  $("#txtNombreCom").removeClass("is-invalid");
  $("#invalid-contacto").css("display", "none");
  $("#txtContactoProv").removeClass("is-invalid");
  $("#invalid-telefonoProv").css("display", "none");
  $("#txtTelefono").removeClass("is-invalid");
  $("#invalid-correoProv").css("display", "none");
  $("#txtEmail").removeClass("is-invalid");
  $("#invalid-tipoPer").css("display", "none");
  $("#cmbTipoPer").removeClass("is-invalid");
  $("#agregarLocacion").trigger("reset");
  $("#invalid-nombreSuc").css("display", "none");
  $("#txtarea").removeClass("is-invalid");
  $("#invalid-nombre").css("display", "none");
  $("#txtNombre").removeClass("is-invalid");
  $("#invalid-primerApellido").css("display", "none");
  $("#txtPrimerApellido").removeClass("is-invalid");
  $("#invalid-genero").css("display", "none");
  $("#invalid-estado").css("display", "none");
  $("#invalid-roles").css("display", "none");
  $("#invalid-nombreProducto").css("display", "none");
  $("#txtProducto").removeClass("is-invalid");
  $("#invalid-clave").css("display", "none");
  $("#txtClave").removeClass("is-invalid");
  $("#invalid-tipoProd").css("display", "none");
  $("#cmbTipoProducto").removeClass("is-invalid");
});

$("#agregar_Proveedor").on('hidden.bs.modal', function () {
  $("#agregarProveedor").trigger("reset");
  $("#invalid-nombreCom").css("display", "none");
  $("#txtNombreCom").removeClass("is-invalid");
  $("#invalid-contacto").css("display", "none");
  $("#txtContactoProv").removeClass("is-invalid");
  $("#invalid-telefonoProv").css("display", "none");
  $("#txtTelefono").removeClass("is-invalid");
  $("#invalid-correoProv").css("display", "none");
  $("#txtEmail").removeClass("is-invalid");
  $("#invalid-tipoPer").css("display", "none");
  $("#cmbTipoPer").removeClass("is-invalid");
});

$("#btnAgregarLocacion").click(function () {
  if ($("#agregarLocacion")[0].checkValidity()) {
    var badNombreSuc =
      $("#invalid-nombreSuc").css("display") === "block" ? false : true;
    if (
      badNombreSuc
    ) {
      var estado = document.getElementById("txtarea6");
      var cmbEstado = estado.options[estado.selectedIndex].value;
      var nombreSucursal = $("#txtarea").val().trim();
      var calle = $("#txtarea2").val();
      var numExterior = $("#txtarea3").val();
      var prefijo = $("#txtarea9").val();
      var numInterior = $("#txtarea4").val();
      var colonia = $("#txtarea5").val();
      var municipio = $("#txtarea7").val();
      var estado = $("#txtarea6").val();
      var pais = $("#txtarea8").val();
      var telefono = $("#txtarea10").val();
      var actInventario = 0;
      console.log(telefono);
      var zonaSalarioMinimo = $("#radioZonaSalarioMinimo").val();

      if ($("#cbxActivarInventario").is(":checked")) {
        actInventario = 1;
      } else {
        actInventario = 0;
      }

      if (nombreSucursal.length < 1) {
        $("#txtarea")[0].reportValidity();
        $("#txtarea")[0].setCustomValidity("Completa este campo.");
        return;
      } else {
        $.ajax({
          url: "../../../cotizaciones/functions/agregar_Locacion.php",
          type: "POST",
          data: {
            txtLocacion: nombreSucursal,
            txtCalle: calle,
            txtNe: numExterior,
            prefijo: prefijo,
            txtNi: numInterior,
            txtColonia: colonia,
            txtMunicipio: municipio,
            cmbEstados: estado,
            cmbPais: pais,
            telefono: telefono,
            actInventario: actInventario,
            zonaSalarioMinimo: zonaSalarioMinimo
          },
          success: function (data, status, xhr) {
            if (data.trim() == "exito") {
              $("#agregar_Locacion").modal("toggle");
              $("#agregarLocacion").trigger("reset");
              location.reload();
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
            } else {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../img/timdesk/warning_circle.svg",
                img: null,
                msg: "Ocurrió un error al agregar",
              });
            }
          },
        });
      }
    }
  } else {
    if (!$("#txtarea").val()) {
      $("#invalid-nombreSuc").css("display", "block");
      $("#txtarea").addClass("is-invalid");
    }
  }
});

$("#txtarea").on("change", ()=>{
  $("#invalid-nombreSuc").css("display", "none");
  $("#txtarea").removeClass("is-invalid");  
});

$("#agregar_Locacion").on('hidden.bs.modal', function () {
  $("#agregarLocacion").trigger("reset");
  $("#invalid-nombreSuc").css("display", "none");
  $("#txtarea").removeClass("is-invalid");
});

function validarUnicaSucursal(item) {
  var valor = item.value;
  $.ajax({
    url: "../../../cotizaciones/functions/validarSucursal.php",
    data: {data: valor },
    dataType: "json",
    success: function (data) {
    },
    error: function(data) {
      console.log(data);
      if (data.responseText != 'exito') {
        item.nextElementSibling.innerText =
          "La sucursal ya esta en el registro.";
        item.nextElementSibling.style.display = "block";
        item.classList.add("is-invalid");
      } else {
        item.nextElementSibling.innerText = "La sucursal debe tener un nombre.";
        item.nextElementSibling.style.display = "none";
        item.classList.remove("is-invalid");
      }
    }
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

function validaNumTelefono(evt, input) {
  var key = window.Event ? evt.which : evt.keyCode;
  if (key == 8 || key == 46) {
    $("#result1").val($("#txtarea10").val().length);
    $("#result1").addClass("mui--is-not-empty");
    var valor = $("#result1").val();
    if (valor < 8 || valor == 9) {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").addClass("is-invalid");
    } else {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").removeClass("is-invalid");
    }
  } else {
    $("#result1").val($("#txtarea10").val().length);
    $("#result1").addClass("mui--is-not-empty");
    var valor = $("#result1").val();
    if (valor < 8 || valor == 9) {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").addClass("is-invalid");
    } else {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").removeClass("is-invalid");
      return false;
    }
  }
}

$("#cmbDireccionEnvio").on("change", ()=>{
  if($("#cmbDireccionEnvio").val() == 'add'){
    cmbSucrusal.set(0);
    $("#agregar_Locacion").modal("show");
  }
  $("#invalid-sucursal").css("display", "none");
  $("#cmbDireccionEnvio").removeClass("is-invalid");
});

// AGREGA PERSONAL
$("#btnAgregarPersonal").on("click", function () {
  roles = cmbRoles.selected();
  if ($("#agregarEmpleado")[0].checkValidity() && roles.length != 0) {
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
      var nombre = $("#txtNombre").val().trim();
      var apellido = $("#txtPrimerApellido").val().trim();
      var genero = $("#cmbGenero").val();
      var estado = $("#cmbEstadoPersonal").val();
      
      if (!$("#txtNombre").val()) {
        console.log('nombre');
        $("#txtNombre")[0].reportValidity();
        $("#txtNombre")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (!$("#txtPrimerApellido").val()) {
        console.log('ape');
        $("#txtPrimerApellido")[0].reportValidity();
        $("#txtPrimerApellido")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (!$("#cmbGenero").val()) {
        console.log('gen');
        $("#cmbGenero")[0].reportValidity();
        $("#cmbGenero")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (!$("#cmbEstado").val()) {
        console.log('est');
        $("#cmbGenero")[0].reportValidity();
        $("#cmbGenero")[0].setCustomValidity("Completa este campo.");
        return;
      } else {
        console.log('bien');
        $.ajax({
          url: "../../../cotizaciones/functions/agregarEmpleado.php",
          data: {
            nombre: nombre,
            apellido: apellido,
            genero: genero,
            roles: roles,
            estado: estado
          },
          success: function (data, status, xhr) {
            $("#agregar_Empleado").modal("toggle");
            $("#btnAgregarPersonal").trigger("reset");
            location.reload();
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
    if (!$("#cmbGenero").val()) {
      $("#invalid-genero").css("display", "block");
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

$("#txtNombre").on("change",()=>{
  $("#invalid-nombre").css("display", "none");
  $("#txtNombre").removeClass("is-invalid");
});

$("#txtPrimerApellido").on("change",()=>{
  $("#invalid-primerApellido").css("display", "none");
  $("#txtPrimerApellido").removeClass("is-invalid");
});

$("#cmbGenero").on("change",()=>{
  $("#invalid-genero").css("display", "none");
});

$("#cmbEstadoPersonal").on("change",()=>{
  $("#invalid-estado").css("display", "none");
});

$("#agregar_Empleado").on('hidden.bs.modal', function () {
  $("#agregarEmpleado").trigger("reset");
  $("#invalid-nombre").css("display", "none");
  $("#txtNombre").removeClass("is-invalid");
  $("#invalid-primerApellido").css("display", "none");
  $("#txtPrimerApellido").removeClass("is-invalid");
  $("#invalid-genero").css("display", "none");
  $("#invalid-estado").css("display", "none");
  $("#invalid-roles").css("display", "none");
});

$("#cmbComprador").on("change", ()=>{
  if($("#cmbComprador").val() == 'add'){
    cmbComprador.set(0);
    $("#agregar_Empleado").modal("show");    
  }
  $("#invalid-comprador").css("display", "none");
  $("#cmbComprador").removeClass("is-invalid");
});

function cargarCMBTipo(input) {
  var html = "";
  var tipo;
  $.ajax({
    url: "../../../cotizaciones/functions/getCmbTipo.php",
    success: function (respuesta) {
      console.log("respuesta tipo producto: ", JSON.parse(respuesta));
      tipo = JSON.parse(respuesta);
      html += '<option data-placeholder="true"></option>';

      for(var i=0;i < tipo.length; i++) {
        html +=
          '<option value="' +
          tipo[i].PKTipoProducto +
          '" >' +
          tipo[i].TipoProducto +
          "</option>";
      };

      $("#" + input + "").html(html);
    },
    error: function (error, data) {
      console.log(data);
    },
  });
}

// AGREGA PRODUCTOS
$("#btnAgregarProducto").on("click", function () {
  if ($("#agregarProductoForm")[0].checkValidity()) {
    var badProducto =
      $("#invalid-nombreProducto").css("display") === "block" ? false : true;
    var badTipo =
      $("#invalid-clave").css("display") === "block" ? false : true;
    var badClave =
      $("#invalid-clave").css("display") === "block" ? false : true;
    if (
      badProducto &&
      badClave &&
      badTipo
      ) {
      var producto = $("#txtProducto").val().trim();
      var clave = $("#txtClave").val().trim();
      var tipo = $("#cmbTipoProducto").val();
      
      if (!$("#txtProducto").val()) {
        console.log('producto');
        $("#txtProducto")[0].reportValidity();
        $("#txtProducto")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (!$("#txtClave").val()) {
        console.log('clave');
        $("#txtClave")[0].reportValidity();
        $("#txtClave")[0].setCustomValidity("Completa este campo.");
        return;
      }  else if (!$("#cmbTipoProducto").val()) {
        console.log('clave');
        $("#cmbTipoProducto")[0].reportValidity();
        $("#cmbTipoProducto")[0].setCustomValidity("Completa este campo.");
        return;
      } else {
        console.log('bien');
        $.ajax({
          url: "functions/agregarProducto.php",
          data: {
            nombre: producto,
            clave: clave,
            tipo: tipo,
            proveedor: $("#cmbProveedor").val()
          },
          success: function (data, status, xhr) {
            console.log(data);
            console.log(status);
            console.log(xhr);
            $("#agregar_Producto").modal("toggle");
            $("#agregarProductoForm").trigger("reset");
            location.reload();
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
    if (!$("#txtProducto").val()) {
      $("#invalid-nombreProducto").css("display", "block");
      $("#txtProducto").addClass("is-invalid");
    }
    if (!$("#txtClave").val()) {
      $("#invalid-clave").css("display", "block");c
    }
    if (!$("#cmbTipoProducto").val()) {
      $("#invalid-tipoProd").css("display", "block");
    }
  }
  $(this).prop("disabled", true);
});

$("#txtProducto").on("cahnge", ()=>{
  $("#invalid-nombreProducto").css("display", "none");
  $("#txtProducto").removeClass("is-invalid");  
});

$("#txtClave").on("cahnge", ()=>{
  $("#invalid-clave").css("display", "none");
  $("#txtClave").removeClass("is-invalid");
});

$("#agregar_Producto").on('hidden.bs.modal', function () {
  $("#agregarProductoForm").trigger("reset");
  $("#invalid-nombreProducto").css("display", "none");
  $("#txtProducto").removeClass("is-invalid");
  $("#invalid-clave").css("display", "none");
  $("#txtClave").removeClass("is-invalid");
  $("#invalid-tipoProd").css("display", "none");
  $("#cmbTipoProducto").removeClass("is-invalid");
});

function escribirNombreProd() {
  var valor = document.getElementById("txtProducto").value;
  console.log("Valor nombre: " + valor);
  $.ajax({
    url: "../../../cotizaciones/functions/validarNombreProducto.php",
    data: { data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-nombreProd").css("display", "block");
        $("#invalid-nombreProd").text("El nombre ya esta en el registro.");
        $("#txtNombre").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProd").css("display", "block");
          $("#invalid-nombreProd").text("El producto debe tener un nombre.");
          $("#txtNombre").addClass("is-invalid");
        } else {
          $("#invalid-nombreProd").css("display", "none");
          $("#txtNombre").removeClass("is-invalid");
        }
      }
    },
    error: function(data) {
      console.log(data);
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
  var valor = $("#txtClave").val();
  console.log("Valor clave" + valor);
  $.ajax({
    url: "../../../cotizaciones/functions/validarClaveProducto.php",
    data: { data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta clave interna valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-clave").css("display", "block");
        $("#invalid-clave").text(
          "La clave interna ya existe."
        );
        $("#txtClave").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-clave").css("display", "block");
          $("#invalid-clave").text("El producto debe tener un nombre.");
          $("#txtClave").addClass("is-invalid");
        } else {
          $("#invalid-clave").css("display", "none");
          $("#txtClave").removeClass("is-invalid");
        }
      }
    },
    error: function(data) {
      console.log(data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-clave").css("display", "block");
        $("#invalid-clave").text(
          "La clave interna ya existe"
        );
        $("#txtClave").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-clave").css("display", "block");
          $("#invalid-clave").text("El producto debe tener un nombre.");
          $("#txtClave").addClass("is-invalid");
        } else {
          $("#invalid-clave").css("display", "none");
          $("#txtClave").removeClass("is-invalid");
        }
      }
    }
  });
}

$(document).on("change","#cmbCategoriaCuenta",(e)=>{
  var subCat = e.target.value;
  if(subCat !== 'add'){
    $("#cmbSubcategoriaCuenta").html('');
    cargarCMBSubcategorias(subCat,'');
  } else {
    $("#agregar_categoria").modal('show');
  }
});

$(document).on("change","#cmbSubcategoriaCuenta",(e)=>{
  var subCat = e.target.value;
  //var cat = document.getElementById('cmbCategoriaCuenta').value;
  
  if(subCat === 'add'){
    $("#agregar_subcategoria").modal('show');
    //cargarCMBAddCategorias(parseInt(cat));
    
  }
});

// $("#agregar_categoria").on('hidden.bs.modal',()=>{
//   var cat = document.getElementById('txtIdAddCategory').value;
//   console.log(cat);
//   $("#cmbCategoriaCuenta").html('');
//   cargarCMBCategorias(parseInt(cat));
// });

$("#agregar_subcategoria").on('shown.bs.modal',()=>{
  var cat = document.getElementById('cmbCategoriaCuenta').value;
  cargarCMBAddCategorias(parseInt(cat));
});

// $("#agregar_subcategoria").on('hidden.bs.modal',()=>{
//   $("#cmbSubcategoriaCuenta").html('');
//   cargarCMBSubcategorias('');
// });

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
  } else if (categoria == "8") {
    limpieza = "SI";
  } else if (categoria == "9") {
    limpieza = "EMP";
  } else {
    limpieza = "N";
  }

  if (limpieza != "N") {
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_claveReferencia" },
      dataType: "json",
      success: function (respuesta) {
        $("#txtClave").val(limpieza + "" + respuesta);
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
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir categoría</option>';
      } else {
        html += '<option value="vacio">No hay datos que mostrar</option><option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir categoría</option>';
      }
      $("#cmbCategoriaCuenta").html(html);
      if(name == ''){
        $("#cmbCategoriaCuenta").val("1");
        $("#cmbCategoriaCuenta").trigger("change");
      }
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}


function cargarCMBAddCategorias(cat)
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
          if(data[i].PKCategoria === cat){
            html +=
            '<option value="' +
            data[i].PKCategoria +
            '" selected>' +
            data[i].Nombre+
            "</option>";
          } else{
            html +=
              '<option value="' +
              data[i].PKCategoria +
              '">' +
              data[i].Nombre+
              "</option>";
          }
        });
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir categoría</option>';
      } else {
        html += '<option value="vacio">No hay datos que mostrar</option><option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir categoría</option>';
      }
      $("#cmbAddCategoria").html(html);
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
            
            if(data[i].PKSubcategoria === name || (name == '' && data[i].PKSubcategoria == 1)){
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
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir subcategoría</option>';
      } else {
        html += '<option value="vacio">No hay datos que mostrar</option><option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Añadir subcategoría</option>';
      }
      
      $("#cmbSubcategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function addSubcategoria(value,value1)
{
  var cat = value !== null && value !== "" ? value : document.getElementById("cmbAddCategoria").value;
  var subcat = value1 !== null && value1 !== "" ? value1 : document.getElementById("txtAddSubcategoria").value;
  $.ajax({
    type:'POST',
    url: "../../php/funciones.php",
    dataType: "json",
    data: {
      clase:"save_data",
      funcion:"save_subcategoriaGastos",
      value:subcat,
      value1:cat
    },
    success: function (data) {
        if(data.estatus){
            cargarCMBSubcategorias(parseInt(cat),parseInt(data.id));
        }
        
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

$(document).on('click','#btnAgregarCategoriaGastos',(e)=>{
    var form = document.getElementById("add_category_form");
    const txtAddCategoria = document.getElementById('txtAddCategoria');
    const txtAddSubcategoria= document.getElementById('txtAddSubcategoriaCat');
    const invalid_cat = document.getElementById('invalid-addCategoriaCuenta');
    const invalid_subcat = document.getElementById('invalid-addSubCategoriaCuentaCat');
    
    if (form.checkValidity()) {
        
        var badCategoryName =
        $("#invalid-addCategoriaCuenta").css("display") === "block" ? false : true;
        var badSubcategoryName =
        $("#invalid-addSubCategoriaCuentaCat").css("display") === "block" ? false : true;
        
        if (
            badCategoryName &&
            badSubcategoryName
        ) {
            var chkAddSubcategoria = document.getElementById('chkAddSubcategoria');
           
            var value = txtAddCategoria.value;
            $.ajax({
                type:'POST',
                url: "../../php/funciones.php",
                dataType: "json",
                data: {
                    clase:"save_data",
                    funcion:"save_categoriaGastos",
                    value:value,
                    value1:txtAddSubcategoria.value,
                    checked:chkAddSubcategoria.checked
                },
                success: function (data) {
                
                if(data.estatus){
                    //$("#txtIdAddCategory").val(data.id);
                    cargarCMBCategorias(parseInt(data.id));
                    $("#agregar_categoria").modal('hide');
                    form.reset();
                    if(data.id_sub.estatus){
                        cargarCMBSubcategorias(parseInt(data.id),parseInt(data.id_sub.id));
                    }
                }
                // if(chkAddSubcategoria.checked)
                // {
                //     addSubcategoria(data.id,txtAddSubcategoria.value);
                //     $("#agregar_categoria").modal('hide');
                // }
                $('#div_subcategoriaCat').addClass('d-none');
                chkAddSubcategoria.checked = false;
                },
                error: function (error) {
                    console.log("Error");
                    console.log(error);
                },
            });
        }
    } else {
        if(!txtAddCategoria.value)
        {
            invalid_cat.style.display = 'block'; 
            txtAddCategoria.classList.add("is-invalid");
        }
        if(chkAddSubcategoria.checked){
            if(!txtAddSubcategoria.value)
            {
                invalid_subcat.style.display = 'block'; 
                txtAddSubcategoria.classList.add("is-invalid");
            }
        }
    }
});

document.getElementById('txtAddCategoria').addEventListener('keyup',(e)=>{
    const target = e.target;
    const invalid_cat = document.getElementById('invalid-addCategoriaCuenta'); 
    target.classList.contains('is-invalid') ? target.classList.remove('is-invalid') : null;
    invalid_cat.style.display === 'block' ? invalid_cat.style.display = 'none': null;
});

document.getElementById('txtAddSubcategoriaCat').addEventListener('keyup',(e)=>{
    const target = e.target;
    const invalid_cat = document.getElementById('invalid-addSubCategoriaCuentaCat'); 
    target.classList.contains('is-invalid') ? target.classList.remove('is-invalid') : null;
    invalid_cat.style.display === 'block' ? invalid_cat.style.display = 'none': null;
});

document.getElementById('cmbAddCategoria').addEventListener('change',(e)=>{
    const target = e.target;
    const invalid_cat = document.getElementById('invalid-addCategoriaCuentaSubCat'); 
    target.classList.contains('is-invalid') ? target.classList.remove('is-invalid') : null;
    invalid_cat.style.display === 'block' ? invalid_cat.style.display = 'none': null;
});

document.getElementById('txtAddSubcategoria').addEventListener('keyup',(e)=>{
    const target = e.target;
    const invalid_cat = document.getElementById('invalid-addSubcategoriaCuenta'); 
    target.classList.contains('is-invalid') ? target.classList.remove('is-invalid') : null;
    invalid_cat.style.display === 'block' ? invalid_cat.style.display = 'none': null;
});

document.getElementById('chkAddSubcategoria').addEventListener('click',(e)=>{
    const target = e.target;
    const subcat = document.getElementById("div_subcategoriaCat");
    const input_subcat = document.getElementById("txtAddSubcategoriaCat");
    const invalid_subcat = document.getElementById('invalid-addSubCategoriaCuentaCat'); 
    console.log(target.checked);
    if(target.checked)
    {
        subcat.classList.remove('d-none');
        input_subcat.setAttribute('required',true);
        input_subcat.classList.contains('is-invalid') ? input_subcat.classList.remove('is-invalid') : null;
        invalid_subcat.style.display === 'block' ? invalid_subcat.style.display = 'none' : null;
    } else {
        subcat.classList.add('d-none');
        input_subcat.removeAttribute('required');
    }
});

document.getElementById('btnAgregarSubcategoria').addEventListener('click',()=>{
    const cat = document.getElementById('cmbAddCategoria');
    const subCat = document.getElementById('txtAddSubcategoria');
    const form = document.getElementById('add_subcat_form');
    const invalid_cat = document.getElementById('invalid-addCategoriaCuentaSubCat');
    const invalid_subcat = document.getElementById('invalid-addSubcategoriaCuenta');
    if(form.checkValidity())
    {
        bad_cat = cat.style.display === 'block' ? false : true;
        bad_subCat = subCat.style.display === 'block' ? false : true;

        if(
            bad_cat &&
            bad_subCat
        )
        {
            addSubcategoria(cat.value,subCat.value);
            $("#agregar_subcategoria").modal('hide');
            form.reset();
        }
    } else {
        if(!cat.value)
        {
            invalid_cat.style.display = 'block'; 
            cat.classList.add("is-invalid");
        }
        if(!subCat.value)
        {
            invalid_subcat.style.display = 'block'; 
            subCat.classList.add("is-invalid");
        }
        
    }
})