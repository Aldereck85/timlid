var arID = {};
var arrIDTemporal = {};
//array para guardar los importes y calcular el total de las facturas
var arrSumaFacturas = {};
//array para guardar los importes asignados de las facturas
var objImportesFacturas = {};
//objeto utilizado para almacenar los saldos insolutos de las facturas cargadas y hacer la validacion.
var obtSaldoInsolutoFact = {};
var metodoPago_ForValidation;

var idioma_espanol = {
  sProcessing: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />.",
  sZeroRecords: "No se encontraron resultados",
  sEmptyTable: "Ningún dato disponible en esta tabla",
  sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
  sLoadingRecords: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
  searchPlaceholder: "Buscar...",
  oPaginate: {
    sFirst: "Primero",
    sLast: "Último",
    sNext: "<i class='fas fa-chevron-right'></i>",
    sPrevious: "<i class='fas fa-chevron-left'></i>",
  },
};

//variables para comparar que las facturas coincidan
bandera = false;
fromaPago = "";
metodoPago = "";
//variable que conserva la forma de pago registrada en la factura
formaPagoFactura="";

//variables temporales para comparar que las facturas coincidan
TempBandera = false;
TempFromaPago = "";
TempMetodoPago = "";
TempFormaPagoFactura="";

let string = "";

html = "";
function cargarCMBcuentasCheques() {
  $.ajax({
    async: false,
    type: "POST",
    url: "../recepcion_pagos/functions/function_cmbCuenta_Cheques.php",
    dataType: "json",
    success: function (data) {
      $.each(data, function (i) {
        if (i == 0) {
          html += '<optgroup label="Cheques"> ';
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option>";
        } else if (i == data.length) {
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option></optgroup>";
        } else {
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option>";
        }
      });
      $("#chosenCuenta").append(html);
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
  cargarCMBcuentasOtros();
}

function cargarCMBcuentasOtros() {
  var htmlO = "";
  $.ajax({
    type: "POST",
    async: false,
    url: "../recepcion_pagos/functions/function_cmbCuenta_Otros.php",
    dataType: "json",
    success: function (data) {
      $.each(data, function (i) {
        if (i == 0) {
          htmlO +=
            '<optgroup label="Otras"><option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option>";
        } else if (i == data.length) {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option></optgroup>";
        } else {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option>";
        }
      });
      $("#chosenCuenta").append(htmlO);
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
  cargarCMBcuentasCajaChica();
}

function cargarCMBcuentasCajaChica() {
  var htmlO = "";
  $.ajax({
    type: "POST",
    async: false,
    url: "../recepcion_pagos/functions/function_cmbCuenta_chica.php",
    dataType: "json",
    success: function (data) {
      $.each(data, function (i) {
        if (i == 0) {
          htmlO +=
            '<optgroup label="Caja chica"><option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option>";
        } else if (i == data.length) {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option></optgroup>";
        } else {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option>";
        }
      });
      $("#chosenCuenta").append(htmlO);
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
  cargarCMBcuentasPuntoVenta();
}

function cargarCMBcuentasPuntoVenta() {
  var htmlO = "";
  $.ajax({
    type: "POST",
    url: "../recepcion_pagos/functions/function_cmbCuenta_PuntoVenta.php",
    dataType: "json",
    success: function (data) {
      $.each(data, function (i) {
        if (i == 0) {
          htmlO +=
            '<optgroup label="Punto de venta"><option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option>";
        } else if (i == data.length) {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option></optgroup>";
        } else {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            "</option>";
        }
      });
      $("#chosenCuenta").append(htmlO);
      is_from_cuentasCobrar();
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}

function carga_Selects() {
  //carga cmb clientes
  $.ajax({
    type: "POST",
    url: "../recepcion_pagos/functions/function_cmbClientes.php",
    dataType: "json",
    async:false,
    success: function (data) {
      $.each(data, function (i) {
        document.getElementById("chosenClientes").innerHTML +=
          "<option value='" +
          data[i].PKCliente +
          "'>" +
          data[i].razon_social +
          "</option>";
      }); 
    },
  });

  //carga cmb formas de pago
  $.ajax({
    type: "POST",
    url: "../recepcion_pagos/functions/function_cmbFormaPago.php",
    dataType: "json",
    async:false,
    success: function (data) {
      $.each(data, function (i) {
        document.getElementById("cmbFormasPago").innerHTML +=
          "<option value='" +
          data[i].id +
          "'>" +
          data[i].descripcion +
          "</option>";
      }); 
    },
  });

  //carga cmb cuentas
  cargarCMBcuentasCheques();
}

//añade las facturas seleccionadas en un arreglo
function sumar(sender) {
  //recupera los valores del check y los separa en un arrreglo
  check = sender.getAttribute("value").split("-");
  tipoCuenta = sender.getAttribute("data-id");

  ids = check[0];

  if (sender.checked) {
    if (TempBandera == false) {
      TempFromaPago = check[1];
      TempFormaPagoFactura=check[1];
      TempMetodoPago = check[2];
      TempBandera = true;
    }
    if (TempMetodoPago == check[2] && (TempFromaPago == check[1] || check[1] == 22)) {
      arrIDTemporal[ids] = tipoCuenta;
    } else {
      //mostramos el modal de alerta
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡Seleccione documentos con el mismo metodo y forma de pago!",
      });
      sender.checked = false;
    }
  } else {
    var key = ids;
    delete arrIDTemporal[key];
    if ($.isEmptyObject(arrIDTemporal)) {
      TempBandera = false;
      TempFromaPago = "";
      TempFormaPagoFactura="";
      TempMetodoPago = "";
    }
  }
}

//Validar los selects
function validateSelects(selectID, invalidDivID) {
  textInvalidDiv = "Campo requerido";
  if (
    $("select[name=" + selectID + "] option")
      .filter(":selected")
      .val() == "f"
  ) {
    $("#" + selectID).addClass("is-invalid");
    document.getElementById(invalidDivID).style.display = "block";
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + selectID).removeClass("is-invalid");
    document.getElementById(invalidDivID).style.display = "none";
    $("#" + invalidDivID).text("");
  }
}

function validar_Select() {
  redFlag1 = 0;
  redFlag2 = 0;
  redFlag3 = 0;
  redFlag4 = 0;
  inputID = "cmbCliente";
  invalidDivID = "invalid-cliente";
  textInvalidDiv = "Campo requerido";
  if (
    $("select[name=" + inputID + "] option")
      .filter(":selected")
      .val() == "f"
  ) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag1 = 1;
  }
  inputID = "cmbCuenta";
  invalidDivID = "invalid-cuenta";
  if (
    $("select[name=" + inputID + "] option")
      .filter(":selected")
      .val() == "f"
  ) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag2 = 1;
  }
  inputID = "txtFecha";
  invalidDivID = "invalid-fecha";
  if (document.getElementById("txtFecha").value == "") {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag3 = 1;
  }
  inputID = "cmbFormasPago";
  invalidDivID = "invalid-formasPago";
  textInvalidDiv = "Campo requerido";
  if (
    $("select[name=" + inputID + "] option")
      .filter(":selected")
      .val() == "f"
  ) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag4 = 1;
  }
  if (redFlag1 == 1 && redFlag2 == 1 && redFlag3 == 1 && redFlag4==1) {
    savePago();
  } else {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "¡Faltan algunos campos requeridos!",
    });
    $("#btnAgregar").prop('disabled',false);
  }
}

function descargaFactura(id) {
  var form = document.createElement("form");
  document.body.appendChild(form);
  form.method = "post";
  form.action = "facturaPago.php";
  form.target = "_blank";
  var input = document.createElement("input");
  input.type = "hidden";
  input.name = "id";
  input.value = id;
  form.appendChild(input);
  form.submit();
  whindow.focus();
}

function toIndex(){
  setTimeout(() => { 
    location.href = "../recepcion_pagos/";
  },1500);
}

function savePago() {
  if (!$.isEmptyObject(arrSumaFacturas)) {
    greenflag=true;
    mensaje="";

    formaPagoFactura = parseInt(formaPagoFactura);
    fromaPago = parseInt(fromaPago);

    if(formaPagoFactura !== 22 && formaPagoFactura !== fromaPago){
      greenflag=false;
      mensaje="El campo forma de pago no puede ser modificado";
    }else if(formaPagoFactura === 22 && fromaPago === 22){
      greenflag=true;
      mensaje='La forma de pago no puede ser "Por definir"';
    }
    if(greenflag){
      //eliminación de espacios y saltos de linea de comentarios
      let coment = $("#txtComentarios").val();
      let aux = coment.split("\t").join(" ");
      aux = aux.split("\n").join(" ");
      aux = aux.split(" ");
      coment1 = "";
      for (let i = 0; i < aux.length; i++) {
        if(aux[i] !== ""){
         coment1 += aux[i] + " ";
        }
        
      } 
      var Comentarios = coment1;
      if (Comentarios.length <= 400) {
        //proveedor no es necesario para este modulo, asi que se manda en 1 solo para cumplir los parametros del sp
        let _proveedor = 1;
        let total=0;
        let string_tipocuenta = "";
        /// convierte a cadena clave-valor el array obtenido
        for (const property in arrSumaFacturas) {        
          string += property + "-" + parseFloat(arrSumaFacturas[property]) + ",";
          string_tipocuenta += property + "-" + parseFloat(arID[property]) + ",";
          total += parseFloat(arrSumaFacturas[property]);
        }
  
        let cadena_CC = string.substring(0, string.length - 1);
        let cadena_tipoCC = string_tipocuenta.substring(0, string_tipocuenta.length - 1);
        //limpia la cadena.
        string = "";
        string_tipocuenta = "";
  
        //recupera los datos y los envia por ajax
        let _referencia = $("#cmbReferencia").val().trim().replace(/[^a-zA-Z 0-9.-]+/g,' ');
        let tipo_movi = 0; //movimiento que simboliza que pertenece a una cuenta por cobrar
        let _origenCE = 300; //cuenta por defecto (deshabilitada) porque no existe una cuenta de origen
        let _cuentaDest = document.getElementById("chosenCuenta").value;
        let _fechaPago = $("#txtFecha").val();

        //eliminación de espacios y saltos de linea de referencia
        let ref = _referencia;
             let aux = ref.split("\t").join(" ");
             aux = aux.split("\n").join(" ");
             aux = aux.split(" ");
             ref1 = "";
             for (let i = 0; i < aux.length; i++) {
               if(aux[i] !== ""){
                 ref1 += aux[i] + " ";
               }
               
             }
  
        $.ajax({
          url: "functions/function_registrarPago.php",
          data: {
            _proveedor: _proveedor,
            _referencia: ref1,
            _cuentaCobrar: cadena_CC,
            Comentarios: Comentarios,
            total: total,
            tipo_movi: tipo_movi,
            _origenCE: _origenCE,
            _cuentaDest: _cuentaDest,
            _fechaPago: _fechaPago,
            _tipoPago: 0,
            _FormaPago: fromaPago,
            _cuentasTipos: cadena_tipoCC
          },
          dataType: "json",
          success: function (data) {
            if (data["estatus"] == "ok") {
              $.ajax({
                url: "functions/function_enviarPagoCorreosAutomaticos.php"
              });
              if(document.getElementById("checkComplement") != null && $("#checkComplement").is(':checked')){
                  //se timbra complemento
                  $.ajax({
                    url: "functions/function_Genera_Factura_Pago.php",
                    data: {
                      folio: data["folio"],
                    },
                    dataType: "json",
                    success: function (data) {
                      $.ajax({
                        url: "functions/function_enviar_correosAutomaticos.php",
                        data: {
                          folio: data["folio"],
                        },
                        dataType: "json",    
                        success: function (data) {
                          console.log(data);
                        }
                      });
                      $(".loader").fadeOut("slow");
                      $("#loader").removeClass("loader");
                      if (data["status"] == "ok") {
                        //si viene de la pantalla principal no recarga.
                        Lobibox.notify("success", {
                          size: "mini",
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: "center top",
                          icon: true,
                          img: "../../img/timdesk/checkmark.svg",
                          msg: "¡Complemento timbrado con exito!",
                        });
                        toIndex();
                        //se descarga la factura
                        descargaFactura(data["result"]);  
                      } else if (data["status"] == "fine") {
                        Lobibox.notify("warning", {
                          size: "mini",
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: "center top",
                          icon: true,
                          img: "../../img/timdesk/warning_circle.svg",
                          msg: "¡Advertencia, el pago ha sido timbrado!",
                        });
                      }else if(data["status"] == "warning"){
                        Lobibox.notify("warning", {
                          size: "mini",
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: "center top",
                          icon: true,
                          img: "../../img/timdesk/warning_circle.svg",
                          msg: "¡Advertencia, el metodo de pago no es PPD!",
                        });
                      }
                      if (data["status"] == "err") {
                        Lobibox.notify("error", {
                          size: "mini",
                          rounded: true,
                          delay: 3000,
                          delayIndicator: false,
                          position: "center top",
                          icon: true,
                          img: "../../img/timdesk/notificacion_error.svg",
                          msg: "¡Algo salio mal al timbrar pago!, " + data["result"],
                        });
                      }
                      
                      toIndex();
                    },
                    error: function (jqXHR, exception, data, response) {
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
                      $(".loader").fadeOut("slow");
                      $("#loader").removeClass("loader");
                      
                      toIndex();
                    },
                  });
              }else{
                location.href = "../recepcion_pagos/";
              }
            } else if(data["estatus"] == "err-v"){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Algo salio mal! "+data['result'],
              });
              $("#btnAgregar").prop('disabled',false);
            }else{
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Algo salio mal!",
              });
              $("#btnAgregar").prop('disabled',false);
            }
          },
          error: function (jqXHR, exception, data, response) {
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
            $("#btnAgregar").prop('disabled',false);
          },
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
          msg: "¡El campo comentarios solo admite 400 caracteres!",
        });
        $("#btnAgregar").prop('disabled',false);
      } 
    }else {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: mensaje,
      });
      $("#btnAgregar").prop('disabled',false);
    }
    //Si no se selecciona ninguna cuenta por pagar
  } else {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "¡Importe del pago en 0.00!",
    });
    $("#btnAgregar").prop('disabled',false);
  }
  
}

//carga las facturas seleccionadas del modal en la pantalla principal
function agregarFacturas() {
  if (!$.isEmptyObject(arID)) {
    let string = "";
    let stringb = "";
    /// Codigo para pasar el array de values de checks a un string
    for (const property in arID) {
      //si se trata de una cuenta por cobrar de una venta
      if(arID[property] == 1){
        stringb = stringb += property + ",";
      }else{
        string = string += property + ",";
      }
    }

    let cadena = string == '' ? 0 : string.substring(0, string.length - 1);
    let cadenab = stringb == '' ? 0 : stringb.substring(0, stringb.length - 1);
    string = "";
    stringb = "";

    let cliente=$("#chosenClientes").val();

    //lleno la tabla con ajax
    defineTabla(cadena, cadenab, cliente);
    
    $("#cmbTipo").text(metodoPago);
    if(formaPagoFactura!=22){
      selectFormapago.set(fromaPago);
      $("#cmbFormasPago").addClass("disabled");
    }else{
      $("#cmbFormasPago").removeClass("disabled");
    }
    $("#mod_agregarFacturas").modal("hide");

    //cuando se llene, si ya se le ha asignado un importe se le reasigna
    $("#tblFacturasSelect")
      .DataTable()
      .on("draw", function () {
        var hiddenRows = tblFacturasSelected.rows().nodes();

        $("input[name=inputs_facturas]", hiddenRows).each(function () {
          var aux = $(this).attr("id").split("-");
          var id = parseInt(aux[0]);
          if (objImportesFacturas.hasOwnProperty(id)) {
            $(this).val(objImportesFacturas[id]);
          }else{
            $(this).val($(this).val().replace(/ /, ""));
          }
          sumarInputs(this);
          resetValidations();
        });
      });
  } else {
    tblFacturasSelected.clear().draw();
    $("#txtTotal").text("0");
    arrSumaFacturas = {};
    fromaPago = "";
    formaPagoFactura="";
    metodoPago = "";
    checkTimbrar(metodoPago);
    bandera = false;
    $("#cmbTipo").text("");
    selectFormapago.set("f");
    ocultarCampos();
  }
}

//suma cada importe en un array
function sumarInputs(sender) {
  //estructura de la cadena enviada a la base de datos para almacenar (id factura - importe pagado, id factura - importe pagado)

  //recupera el id de la factura y el monto ingresado
  var id = $(sender).attr("id").split("-")[0];
  let is_invoice = $(sender).attr("data-id");
  var MF = parseFloat($(sender).attr("id").split("-")[1]);
  var valor = parseFloat($(sender).val().replace(/ /g, ""));
  if (!parseFloat(valor) || valor <= 0) {
    sender.value = "";

    //lo elimina del arreglo clave valor
    delete arrSumaFacturas[id];
  } else {
    //comprueba que no sea mayor al monto de la factura
    var res = valida_Inputs_Importe('0', id, valor, is_invoice);
    if (res == true) {
      //lo añade a un arreglo clave valor
      arrSumaFacturas[id] = valor;

      //separa el input pagos en el punto
      var partsInput = sender.value.split(".");
      // Si no tenia decimales le pone 00
      partsInput[1] = partsInput[1] == undefined ? "00" : partsInput[1];
      // Si tenia solo un decimal le agrega un 0 al final
      partsInput[1] =
        partsInput[1].length == 1 ? partsInput[1] + "0" : partsInput[1];
      partsInput[0] = partsInput[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
      let unionInput = partsInput.join(".");
      //Lo pone en el campo
      sender.value = unionInput;
    } else {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡el importe no debe exceder al saldo insoluto(" + res + ")!",
      });
      delete arrSumaFacturas[id];
      sender.value = "";
    }
  }

  calculaTotal();
}

//calcula el total de los importes en pantalla
function calculaTotal() {
  //suma el arreglo y pinta el total
  suma = 0;
  for (const property in arrSumaFacturas) {
    suma += parseFloat(arrSumaFacturas[property]);
  }
  suma = Math.round((suma + Number.EPSILON) * 100) / 100;
  suma=suma.toFixed(2);

  //da formato al numero
  var parts = suma.toString().split(".");
  // Si no tenia decimales le pone 00
  parts[1] = parts[1] == undefined ? "00" : parts[1];
  // Si tenia solo un decimal le agrega un 0 al final
  parts[1] = parts[1].length == 1 ? parts[1] + "0" : parts[1];
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  var union = parts.join(".");
  $("#txtTotal").text(union);
}

//desmarca la factura a eliminar.
function eliminaFactura(id) {
  var key = id;
  delete arrIDTemporal[key];
  delete arID[id];
  delete arrSumaFacturas[id];
  delete objImportesFacturas[id];
  recupera_importe();
  agregarFacturas();
  if ($.isEmptyObject(arID)) {
    bandera = false;
  }
}

function recargaChecks() {
  $("#tblFactura")
    .DataTable()
    .on("draw", function () {
      var hiddenRows = tblFacturas.rows().nodes();
      
      //se inicializan los cheks en false
      $("input[type='checkbox']", hiddenRows).prop('checked', false); 

      //se recorren los checks de la tabla y los que se encuentren en el array de los mostrados en pantalla, se marcan
      $("input[type='checkbox']", hiddenRows).each(function () {
        var arrid = $(this).attr("value").split("-");
        var idCheck = arrid[0];
        if (arrIDTemporal[idCheck]) {
          $(this).prop("checked", true);
        }
      });
    });
}

//funcion que recupera los importes de las facturas cargadas.
function recupera_importe() {
  objImportesFacturas = Object.assign({}, arrSumaFacturas);
}

function cargaDatatable() {
  seleccion = document.getElementById("chosenClientes").value;
  tblFacturas = $("#tblFactura").DataTable({
    restrieve: true,
    paging:true,
    destroy: true,
    language: idioma_espanol,
    async: false,
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
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
    columnDefs: [
      { targets: 9, visible: false }
    ],    
    ajax: "functions/function_CargaFacturas.php?cliente=" + seleccion,
    columns: [
      { data: "Folio" },
      { data: "F de expedicion" },
      { data: "F de vencimiento" },
      { data: "Metodo de pago" },
      { data: "Forma de pago" },
      { data: "Monto factura" },
      { data: "Saldo anterior" },
      { data: "Saldo insoluto" },
      { data: "No Parcialidad", width: "10px" },
      { data: "Seleccionar", width: "10px" },
    ],
  });
  recupera_importe();
  recargaChecks();
}

//funcion para generar el check del timbrado del complemento
function checkTimbrar(metodo){
  var html='';
  if(metodo.trim() == "En Parcialidades o Diferido"){
    html = '<input style="cursor:pointer;" type="checkbox" id="checkComplement"> <span style=" color: var(--color-primario);">Timbrar complemento de pago</span></input>'
  }
  $("#DivCheckComplement").html(html);
}

//verifica si se accedió desde la vista de una factura en "cuentas cobrar"
function is_from_cuentasCobrar() {
  if ($("#idClienteFrom").attr("value") != undefined) {
    cliente=$("#idClienteFrom").val();
    selectCliente.set(cliente);
    $("#chosenClientes").trigger("change");    
  }

  if ($("#idFacturaFrom").attr("value") != undefined) {
    factura=$("#idFacturaFrom").val();
    let is_invoice=$("#is_invoice").val();

    $.ajax({
      url: "functions/function_valida_from_cuentasCobrar.php",
      data:{id:factura,is_invoice:is_invoice},
      dataType: "json",
      success: function (data) {
        if (data["estatus"] == "ok") {
            fromaPago = data['forma'];
            formaPagoFactura= data['forma'];
            metodoPago = data['metodo'];
            checkTimbrar(metodoPago);
            bandera = true;
    
            //carga la factura
            arID[factura] = is_invoice;
            agregarFacturas();
            $("#txtTotal").text(0);
            arrSumaFacturas = {};
            mostrarCampos();
        }
      },
      error: function (jqXHR, exception, data, response) {
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
}

function mostrarCampos(){
  //muestra los demás campos cuando se agregan facturas
  $(".inpt-fecha-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".inpt-metodo-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".inpt-total-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".inpt-forma-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".slct-cuenta-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".inpt-referencia-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".txt-comment-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".inf-campos-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".btn-guardar-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
}

function ocultarCampos(){
  //cambia de cliente, se muestra el boton de facturas, se ocultan los demas campos y se vacían
  $(".btn-AgregarFacturas-disabled").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  $(".inpt-fecha-disabled").css({'display': 'none','opacity': '0','visibility': 'hidden'});
  $(".inpt-forma-disabled").css({'display': 'none','opacity': '0','visibility': 'hidden'});
  $(".inpt-metodo-disabled").css({'display': 'none','opacity': '0','visibility': 'hidden'});
  $(".inpt-total-disabled").css({'display': 'none','opacity': '0','visibility': 'hidden'});

}

function resetValidations() {
  $(".alpha-only").on("input", function () {
    var regexp = /[^a-zA-Z ]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente letras y numeros*/
  $(".alphaNumeric-only").on("input", function () {
    var regexp = /[^a-zA-Z0-9 @.]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente letras y numeros sin punto*/
  $(".alphaNumericNDot-only").on("input", function () {
    var regexp = /[^a-zA-Z0-9 @]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente numeros*/
  $(".numeric-only").on("input", function () {
    console.log($(this).val());
    var regexp = /[^0-9]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente numeros y ":" reloj*/
  $(".time-only").on("input", function () {
    var regexp = /[^0-9:]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });
/* /[^\d.]/g */
  /*Permitir numero decimales */
  $(".numericDecimal-only").on("input", function () {
    var regexp = /[^(\d{1,12})(\.\d{1,6})?$]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });
  $("#txtCantidad").keypress(function (event) {
    event.preventDefault();
  });
  $("#txtClabeU").keypress(function (event) {
    event.preventDefault();
  });
}

function defineTabla(cadena = '', cadenab = '', cliente = ''){

  if(cadena == '' &&
    cadenab == '' &&
    cliente == ''){
      url = '';
    }else{
      url = "functions/function_cargaFacturasSelected.php?ids=" + cadena + "&ids2="+cadenab+"&cliente="+cliente;
    }

  let topButtons = [
    {
      text: '<button class="btn-custom mr-2 btn-custom--white-dark" tabindex="0" type="button"><span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir Documentos</span></button>',
      className: "btn-custom--white-dark",
      action: function () {
        $("#mod_agregarFacturas").modal("show");
      },
    },
  ];
  tblFacturasSelected = $("#tblFacturasSelect")
    .DataTable({
      language: idioma_espanol,
      restrieve: true,
      destroy: true,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
      responsive: true,
      lengthChange: false,
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
        buttons: topButtons,
      },
      ajax: url,
      columns: [
        { data: "Folio" },
        { data: "Cliente" },
        { data: "F Facturacion" },
        { data: "F Vencimiento" },
        { data: "Monto total" },
        { data: "Saldo anterior" },
        { data: "Importe pago" },
        { data: "Saldo insoluto" },
        { data: "No Parcialidad", width: "103.1px" },
        { data: "Acciones", width: "10px" },
      ],
    });
}

$(document).ready(function () {
  carga_Selects();
  seleccion = 0;
  var clienteSelected;

  //boton para agregar las facturas del modal
  $("#btnAgregarFacturas").off('click').click(function () {
    if (!$.isEmptyObject(arrIDTemporal)) {
      arID = Object.assign({}, arrIDTemporal);
      fromaPago = TempFromaPago;
      formaPagoFactura = TempFormaPagoFactura;
      metodoPago = TempMetodoPago;
      checkTimbrar(metodoPago);
      bandera = TempBandera;
      agregarFacturas();
      $("#txtTotal").text(0);
      arrSumaFacturas = {};

      mostrarCampos();
    } else {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡Debe seleccionar almenos una cuenta por cobrar!",
      });
    }
  });

  //cada que cambia el cliente, se actualiza la tabla, se reinicia el total y se vacia el arreglo de las facturas seleccionadas y se ocultan los elementos 
  $("#chosenClientes").on("change", function () {
    ocultarCampos();
    $("#txtTotal").text(0);
    arrIDTemporal = {};
    arrSumaFacturas = {};
    arID = {};
    tblFacturasSelected.clear().draw();
    bandera = false;
    fromaPago = "";
    formaPagoFactura="";
    metodoPago = "";
    checkTimbrar(metodoPago);
    $("#cmbTipo").text("");
    $("#cmbForma").val("");
    //se guarda el nombre del cliente en una variable, para ser enviado al modal de añadir facturas
    clienteSelected = $("#chosenClientes option:selected").text();
    //se abre modal para agregar facturas
    $("#mod_agregarFacturas").modal("show");
  });

  $("#cmbFormasPago").on("change", function () {
    fromaPago=$("#cmbFormasPago").val();
  });

  //boton para guardar el pago
  $("#btnAgregar").click(function () {
    $("#btnAgregar").prop('disabled',true);
    validar_Select();
  });

  defineTabla();

    //activa los tooltips en datatable
    $('#tblFacturasSelect tbody').on('mouseover', 'tr', function () {
      $('[data-toggle="tooltip"]').tooltip({
          trigger: 'hover',
          html: true
      });
      $('[data-toggle="tooltip"]').on("click", function () {
        $(this).tooltip("dispose");
      });
    });

  //cada que se actualice el campo Referencia se actualiza el tooltip
  $("#cmbReferencia").on("keyup", function(){
    texto=$("#cmbReferencia").val();
    if(texto.length>50){
      $("#cmbReferencia").attr('data-original-title',texto);
    }
  });

  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });

  //Comprobamos si tiene permisos
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }

  if ($("#add").val() !== "1") {
    $("#alert").modal("show");
  }

  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = href = "../dashboard.php";
  });

  //muestra el modal para seleccionar las facturas a cobrar.
  $("#mod_agregarFacturas").on("shown.bs.modal", function () {
    //se recuperan las facturas cargadas en el arreglo temporal
    arrIDTemporal = Object.assign({}, arID);
    //junto con los datos para la comparacion de las facturas
      TempFromaPago = fromaPago;
      TempFormaPagoFactura = formaPagoFactura;
      TempMetodoPago = metodoPago;
      TempBandera = bandera;
    cargaDatatable();
    $("#txtCliente").val(clienteSelected);
  });
});
