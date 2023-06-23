//objeto con facturas modificadas
var arID = {};
var arrIDTemporal = {};
//objeto con las facturas iniciales, para comparación
var arIdOlds = {};
//objeto para ingresar los cambios de los valores
var arrImportesOld = {};
//objeto para guardar los importes y calcular el total de las facturas
var arrSumaFacturas = {};
//objeto para guardar los importes asignados de las facturas
var objImportesFacturas = {};
//objeto de facturas que no se pueden editar
var noEdit = {};
//objeto que contendrá las facturas y la parcialidad que se muestra en pantalla.
var facCargadas = {};
//objetos con las facturas e importes
var toInsert = {};
var toDelete = {};
//variable global que define el tipo de cuenta por cobrar 1: venta y 2: factura
var tipoCuenta = 0;

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

var clienteSelected;
var cuenta = "";

let string = "";

//valida si este pago se puede editar
function valida_editPago() {
  if($("#isSubstitution").val() == 0){
    var folio = $("#idPago").val();
    $.ajax({
      type: "POST",
      url: "../recepcion_pagos/functions/function_valida_edit.php",
      dataType: "json",
      data: { folio: folio },
      success: function (data) {
        if (data.status == "ok") {
          $("#alertInvoice").modal("hide");
        } else if (data.result == "success") {
          $(".user-content").slideUp();
          $("#alert_noEdit").modal("show");
        }
      },
    });
  }
}

//muestra una alerta segun sea el caso,
function alerta(alerta) {
  if (alerta == 1) {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "¡No se puede eliminar la parcialidad!",
    });
  } else if (alerta == 2) {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "¡No se puede eliminar la parcialidad de la factura con complemento de pago!",
    });
  }
}

//funcion para generar el check del timbrado del complemento
function checkTimbrar(metodo){
  var html='';
  if(metodo.trim() == "Pago en Parcialidades o Diferido"){
    //si es una substitucion se marca el check de timbrado
    if($("#isSubstitution").val() == 1){
      html = '<input style="cursor:pointer;" type="checkbox" id="checkComplement" checked disabled> <span style=" color: var(--color-primario);">Timbrar complemento de pago</span></input>'
    }else{
      html = '<input style="cursor:pointer;" type="checkbox" id="checkComplement"> <span style=" color: var(--color-primario);">Timbrar complemento de pago</span></input>'
    }
  }
  $("#DivCheckComplement").html(html);
}

function carga_Cabecera() {
  idPago = $("#idPago").val();
  $.ajax({
    type: "POST",
    url: "../recepcion_pagos/functions/get_ajax_detallePago.php",
    dataType: "json",
    data: { idPago: idPago },
    success: function (data) {
      if (data.status == "ok") {
        $("#alertInvoice").modal("hide");
        $("#clienteId").val(data.result.PKCliente);
        $("#txtFecha").val(data.result.fecha_pago);
        $("#cmbTipo").text(data.result.metodo_pago);
        fromaPago = data.result.forma_pago;
        tipoCuenta = data.result.tipoDoc;
        formaPagoFactura=data.result.id;
        if(formaPagoFactura!=22 && data.result.tipoDoc == 2){
          $("#cmbFormasPago").addClass("disabled");
        }else{
          $("#cmbFormasPago").removeClass("disabled");
        } 
        metodoPago = data.result.metodo_pago;
        checkTimbrar(metodoPago);
        bandera = true;
        cuenta = data.result.PKCuenta;
        clienteSelected = data.result.NombreComercial;
        $("#Referencia").val(data.result.Referencia);
        if($("#Referencia").val().length>50){
          $("#Referencia").attr('data-original-title',data.result.Referencia);
        }
        $("#txtComentarios").val(data.result.comentarios);
        carga_Clientes(fromaPago);
      } else {
        //mostramos el modal de alerta
        $("#alertInvoice").modal("show");

        //Redireccionamos al modulo cuando se oculta el modal.
        $("#alertInvoice").on("hidden.bs.modal", function (e) {
          window.location = href = "../recepcion_pagos";
        });
      }
    },
  });
}

html = "";
function cargarCMBcuentasCheques() {
  $.ajax({
    async: false,
    type: "POST",
    url: "../recepcion_pagos/functions/function_cmbCuenta_Cheques.php",
    dataType: "json",
    success: function (data) {
      let selected = "";
      $.each(data, function (i) {
        selected = cuenta == data[i].PKCuenta? "selected" : "";

        if (i == 0) {
          html += '<optgroup label="Cheques"> ';
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option>";
        } else if (i == data.length) {
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option></optgroup>";
        } else {
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option>";
        }
      });
      $("#chosenCuenta").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
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
      let selected = "";

      $.each(data, function (i) {
        selected = cuenta == data[i].PKCuenta? "selected" : "";

        if (i == 0) {
          htmlO +=
            '<optgroup label="Otras"><option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option>";
        } else if (i == data.length) {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option></optgroup>";
        } else {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option>";
        }
      });
      $("#chosenCuenta").append(htmlO);
      /* selectSucursal.set(cuenta);
      selectFormapago.set(fromaPago); */
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
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
        selected = cuenta == data[i].PKCuenta? "selected" : "";

        if (i == 0) {
          htmlO +=
            '<optgroup label="Caja chica"><option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option>";
        } else if (i == data.length) {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option></optgroup>";
        } else {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
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
        selected = cuenta == data[i].PKCuenta? "selected" : "";

        if (i == 0) {
          htmlO +=
            '<optgroup label="Punto de venta"><option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option>";
        } else if (i == data.length) {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
            data[i].Cuenta +
            "</option></optgroup>";
        } else {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '" '+selected+'>' +
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
}

function carga_Clientes(fp) {
  $("#Cliente").val(clienteSelected);

  //carga cmb formas de pago
  $.ajax({
    type: "POST",
    url: "../recepcion_pagos/functions/function_cmbFormaPago.php",
    dataType: "json",
    //async:false,
    success: function (data) {
      let selected = "";
      let html = "";
      $.each(data, function (i) {
        selected = fp == data[i].id? "selected" : "";
        html += "<option value='" +
        data[i].id +
        "' "+selected+">" +
        data[i].descripcion +
        "</option>";
      });
      $("#cmbFormasPago").append(html);
    },
  });
  cargarCMBcuentasCheques();
}

//añade las facturas seleccionadas en un arreglo
function sumar(sender) {
  //recupera los valores del check y los separa en un arrreglo
  check = sender.getAttribute("value").split("-");
  ids = check[0];

  if (sender.checked) {
    if (TempBandera == false) {
      TempFromaPago = check[1];
      TempFormaPagoFactura=check[1];
      TempMetodoPago = check[2];
      TempBandera = true;
      AuxtipoCuenta = sender.getAttribute("data-id");
    }
    if (TempMetodoPago == check[2] && (TempFromaPago == check[1] || check[1] == 22) && AuxtipoCuenta == sender.getAttribute("data-id")) {
      arrIDTemporal[ids] = ids;
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
      AuxtipoCuenta = 0;
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
  textInvalidDiv = "Campo requerido";

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
    redFlag1 = 1;
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
    redFlag2 = 1;
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
    redFlag3 = 1;
  }
  if (redFlag1 == 1 && redFlag2 == 1 && redFlag3==1) {
    constructArrays();
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Faltan algunos campos requeridos!",
    });
    $("#btnAgregar").prop('disabled',false);
  }
}

function agregarFacturas() {
  if (!$.isEmptyObject(arID)) {
    /// Codigo para pasar el array de values de checks a un string
    for (const property in arID) {
      string = string += property + ",";
    }

    let cadena = string.substring(0, string.length - 1);
    string = "";

    //lleno la tabla con ajax
    defineTabla(cadena, tipoCuenta);

    /* tblFacturasSelected.ajax
      .url(
        "functions/function_cargaFacturasSelected_pagadas.php?ids=" +
          cadena +
          "&idPago=" +
          $("#idPago").val()+
          "&isSubstitution=" +
          $("#isSubstitution").val()+
          "&tipoCuenta="+tipoCuenta
      )
      .load(); */
    $("#cmbTipo").text(metodoPago);
    if(formaPagoFactura!=22 && tipoCuenta == 2){
      selectFormapago.set(fromaPago);
      $("#cmbFormasPago").addClass("disabled");
    }else{
      $("#cmbFormasPago").removeClass("disabled");
    }    $("#mod_agregarFacturas").modal("hide");

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
      mostrarCampos();
  } else {
    tblFacturasSelected.clear().draw();
    $("#txtTotal").text(0);
    arrSumaFacturas = {};
    fromaPago = "";
    formaPagoFactura = "";
    metodoPago = "";
    checkTimbrar(metodoPago);
    tipoCuenta=0;
    bandera = false;
    $("#cmbTipo").text("");
    selectFormapago.set("f");
    ocultarCampos();
  }
}

function sumarInputs(sender) {
  //estructura de la cadena enviada a la base de datos para almacenar (id factura - importe pagado, id factura - importe pagado)

  //recupera el id de la factura y el monto ingresado
  var id = $(sender).attr("id").split("-")[0];
  var MF = parseFloat($(sender).attr("id").split("-")[1]);
  var valor = parseFloat($(sender).val().replace(/ /g, ""));
  if (!parseFloat(valor) || valor <= 0) {
    sender.value = "";

    //lo elimina del arreglo clave valor
    delete arrSumaFacturas[id];
  } else {
    //comprueba que no sea mayor al monto de la factura
    var res = valida_Inputs_Importe($("#idPago").val(), id, valor, tipoCuenta);
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
        msg:
          "¡el importe no debe exceder al saldo insoluto de la factura (" +
          res +
          ")!",
      });
      delete arrSumaFacturas[id];
      sender.value = "";
    }
  }
  calculaTotal();
}

//funcion que recupera los importes de las facturas cargadas.
function recupera_importe() {
  objImportesFacturas = Object.assign({}, arrSumaFacturas);
}

function calculaTotal() {
  //suma el arreglo y pinta el total
  suma = 0;
  for (const property in arrSumaFacturas) {
    suma += parseFloat(arrSumaFacturas[property]);
  }
  suma=suma.toFixed(2);

  var parts = suma.toString().split(".");
  // Si no tenia decimales le pone 00
  parts[1] = parts[1] == undefined ? "00" : parts[1];
  // Si tenia solo un decimal le agrega un 0 al final
  parts[1] = parts[1].length == 1 ? parts[1] + "0" : parts[1];
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  var union = parts.join(".");
  $("#txtTotal").text(union);
}

//Recupera el array de las cuentas del pago para mostrarlas en la tabla principal
function cargarDetail() {
  var idPago = $("#idPago").val();
  $.ajax({
    type: "POST",
    url: "../recepcion_pagos/functions/get_FacturasPagadas.php",
    dataType: "json",
    data: { idPago: idPago},
    success: function (data) {
      if (data.status == "ok") {
        // se recupera la cadena string en formato id_factura,importe-id_factura,imprte
        //separa el string en un arreglo {[id_factura,importe],[id_factura,importe]}
        arreglo = data.result.split("-");
        arreglo2 = data.noEdit.split("-");
        arreglo3 = data.facCarg.split("-");
        tipoCuenta = data.tipoCuenta;

        $.each(arreglo3, function (i) {
          aux = arreglo3[i].split(",");
          idFactura = aux[0];
          parcialidad = aux[1];

          //se insertan las facturas y su parcialidad cargada en el arreglo
          facCargadas[idFactura] = parcialidad;
        });

        $.each(arreglo2, function (i) {
          aux = arreglo2[i].split(",");
          idFactura = aux[0];
          maxParcialidad = aux[1];

          //se insertan las facturas y su ultima parcialidad en el arreglo
          noEdit[idFactura] = maxParcialidad;
        });

        // se recorre el arreglo para volverlo a separarlo en clave valor {id_factura:importe,id_factura:importe]}
        $.each(arreglo, function (i) {
          aux = arreglo[i].split(",");
          idFactura = aux[0];
          importe = aux[1];

          //se insertan las facturas y sus importes en el arreglo
          arID[idFactura] = idFactura;
          arrImportesOld[idFactura] = importe;
        });

        //Crea una copia del arreglo que tiene los valores que ya estaban en la base de datos
        //Esto sirve para despues comparar los arreglos y ver que se agrego o eliminó
        arIdOlds = Object.assign({}, arID);
        agregarFacturas();
      } else {
        $(".user-content").slideUp();
        $("#alertInvoice").modal("show");
      }
    },
  });
}

function constructArrays() {
  if (!$.isEmptyObject(arID)) {
    toInsert = Object.assign({}, arID);
    toDelete = Object.assign({}, arIdOlds);
    //se eliminan las facturas que estan en ambos arreglos para determinar aquellas que se van a añadir
    for (const property in arIdOlds) {
      if (arID.hasOwnProperty(property)) {
        delete toInsert[property];
        delete toDelete[property];
      }
    }
    update();
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Ninguna cuenta por cobrar seleccionada!",
    });
    $("#btnAgregar").prop('disabled',false);
  }
}

function cargaDatatable(idPago, idCliente) {
  tblFacturas = $("#tblFactura").DataTable({
    language: idioma_espanol,
    restrieve: true,
    paging:true,
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
      buttons: [],
    },    
    columnDefs: [
      { targets: 9, visible: false }
    ],
    ajax:
      "functions/function_getFacturas.php?idPago=" +
      idPago +
      "&cliente=" +
      idCliente+
      "&tipoCuenta="+
      tipoCuenta
      ,
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

function reviertePago(idpagos){
  if(tipoCuenta == 2){
    $.ajax({
      url: "functions/function_RollBack_data_pago.php",
      async:false,
      data: {
        folio: idpagos,
      },
      dataType: "json",  
      error: function () {
        return false;
      }
    });
  }
}

function limpiaDatosPago(idpagos){
  if(tipoCuenta == 2){
    $.ajax({
      url: "functions/function_clear_data_pago.php",
      async:false,
      data: {
        folio: idpagos,
      },
      dataType: "json",  
    });
  }
}

function toIndex(){
  setTimeout(() => { 
    location.href = "../recepcion_pagos/";
  },1500);
}

function update() {
  if (!$.isEmptyObject(arrSumaFacturas)) {
    greenflag=true;
    mensaje="";

    formaPagoFactura = parseInt(formaPagoFactura);
    fromaPago = parseInt(fromaPago);

    if(formaPagoFactura != 22 && formaPagoFactura != fromaPago && tipoCuenta==2){
      greenflag=false;
      mensaje="El campo forma de pago no puede ser modificado";
    }else if(formaPagoFactura != 22 && fromaPago == 22){
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
      var Comentarios =coment1;
      if (Comentarios.length <= 400) {
        var txtTotal = 0;
        // calcula el total
        for (const property in arrSumaFacturas) {
          txtTotal = parseFloat(arrSumaFacturas[property]);
        }

        stringToInsert = "";
        stringToDelete = "";
        stringToUpdate = "";
        var idpagos = $("#idPago").val();
        var isSubstitution = $("#isSubstitution").val();
        var txtFecha = $("#txtFecha").val();
        var cuenta = $("#chosenCuenta").val();
        var Referencia = $("#Referencia").val().trim().replace(/[^a-zA-Z 0-9.-]+/g,' ');
        var txtComentarios = Comentarios;
        flagAlert = false;

        //eliminación de espacios y saltos de linea de referencia
        let ref = Referencia;
             let aux = ref.split("\t").join(" ");
             aux = aux.split("\n").join(" ");
             aux = aux.split(" ");
             ref1 = "";
             for (let i = 0; i < aux.length; i++) {
               if(aux[i] !== ""){
                 ref1 += aux[i] + " ";
               }
               
             }
  
        //creacion de las cadenas para insertar, eliminar y actualizar las facturas
        if (!$.isEmptyObject(toInsert)) {
          for (const property in arrSumaFacturas) {
            if (
              !arrImportesOld.hasOwnProperty(property) &&
              toInsert.hasOwnProperty(property)
            ) {
              stringToInsert =
                stringToInsert + property + "-" + arrSumaFacturas[property] + ",";
            }
          }
          stringToInsert = stringToInsert.substring(0, stringToInsert.length - 1);
        } else {
          stringToInsert = null;
        }
  
        if (!$.isEmptyObject(toDelete)) {
          for (const property in toDelete) {
            if (!noEdit.hasOwnProperty(property)) {
              stringToDelete = stringToDelete +=
                property + "-" + toDelete[property] + ",";
            } else {
              if (facCargadas[property] <= noEdit[property]) {
                if(isSubstitution == 1 && facCargadas[property] == noEdit[property]){
                  stringToDelete = stringToDelete +=
                  property + "-" + toDelete[property] + ",";
                }else{
                  flagAlert = true;
                  break;
                }
              } else {
                stringToDelete = stringToDelete +=
                  property + "-" + toDelete[property] + ",";
              }
            }
          }
          stringToDelete = stringToDelete.substring(0, stringToDelete.length - 1);
        } else {
          stringToDelete = null;
        }
  
        if (!$.isEmptyObject(arrImportesOld)) {
          for (const property in arrSumaFacturas) {
            if (arrImportesOld.hasOwnProperty(property)) {
              if (arrImportesOld[property] != arrSumaFacturas[property]) {
                if (noEdit.hasOwnProperty(property)) {
                  if (facCargadas[property] <= noEdit[property]) {
                    if(isSubstitution == 1 && facCargadas[property] == noEdit[property]){
                      stringToUpdate =
                      stringToUpdate +
                      property +
                      "-" +
                      arrSumaFacturas[property] +
                      ",";
                    }else{
                      flagAlert = true;
                      break;
                    }
                  } else {
                    stringToUpdate =
                      stringToUpdate +
                      property +
                      "-" +
                      arrSumaFacturas[property] +
                      ",";
                  }
                } else {
                  stringToUpdate =
                    stringToUpdate +
                    property +
                    "-" +
                    arrSumaFacturas[property] +
                    ",";
                }
              }
            }
          }
          stringToUpdate = stringToUpdate.substring(0, stringToUpdate.length - 1);
        } else {
          stringToUpdate = null;
        }

        if (flagAlert) {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡No se puede guardar, datos de pago comprometidos!",
          });
          $("#btnAgregar").prop('disabled',false);
        } else {
          $("#loader").addClass("loader");
          $(".loader").fadeIn("slow");

          /* antes de actualizar recupera los datos actuales del pago en tablas temporales en db, 
          ésto para que en el caso dónde no se timbre el complemento nuevo, se regresen los anteriores datos */
          $.ajax({
            url: "functions/function_get_data_Before_update.php",
            data: {
              folio: idpagos,
            },
            dataType: "json",  
            async: false,  
            success: function (data) {
              //si se recuperan los datos correctamente continúa
              if(data['status'] == "ok" || tipoCuenta == 1){
                $.ajax({
                  type: "POST",
                  url: "../recepcion_pagos/functions/actualizarDetalle.php",
                  dataType: "json",
                  data: {
                    idpagos: idpagos,
                    txtTotal: txtTotal,
                    txtFecha: txtFecha,
                    Referencia: ref1,
                    stringToInsert: stringToInsert,
                    stringToDelete: stringToDelete,
                    stringToUpdate: stringToUpdate,
                    txtComentarios: txtComentarios,
                    cuenta: cuenta,
                    formaPago:fromaPago,
                    tipoCuenta: tipoCuenta
                  },
                  success: function (data) {
                    if (data["estatus"] == "ok") {
                      if((document.getElementById("checkComplement") != null && $("#checkComplement").is(':checked')) || isSubstitution == 1){
                          //se timbra complemento
                          $.ajax({
                            url: "functions/function_Genera_Factura_Pago.php",
                            data: {
                              folio: idpagos,
                              isSubstitution : isSubstitution,
                            },
                            dataType: "json",
                            success: function (data) {
                              $.ajax({
                                url: "functions/function_enviar_correosAutomaticos.php",
                                data: {
                                  folio: idpagos,
                                },
                                dataType: "json",    
                                success: function (data) {
                                  console.log(data);
                                }
                              });
                              $(".loader").fadeOut("slow");
                              $("#loader").removeClass("loader");
                              if (data["status"] == "ok") {
                                //si se trata de una substitución, la realiza en facturapi
                                if(isSubstitution == 1){
                                  $.ajax({
                                    url: "functions/function_substituyeComplemento.php",
                                    async:false,
                                    data: {
                                      folio: idpagos,
                                      ComplementNew: data["result"],
                                      ComplementOld: data["complementoOld"]
                                    },
                                    dataType: "json",    
                                    success: function (data) {
                                      if(data['status'] == "err"){
                                          reviertePago(idpagos);
                                          toIndex();
                                      }
                                    },
                                    error: function () {
                                        reviertePago(idpagos);
                                        toIndex();
                                      }
                                  });
                                }
                              
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
                                reviertePago(idpagos);
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
                                reviertePago(idpagos);
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
                                reviertePago(idpagos);
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
                              //si se trata de una substitución se revierte el pago
                              if(isSubstitution == 1){
                                reviertePago(idpagos);
                              }
                              toIndex();
                            },
                          });
                      }else{
                        limpiaDatosPago(idpagos);
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
                        msg: "¡Error al guardar!",
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
                    Lobibox.notify("warning", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../img/timdesk/warning_circle.svg",
                      msg: msg,
                    });
                    $("#btnAgregar").prop('disabled',false);
                    $(".loader").fadeOut("slow");
                    $("#loader").removeClass("loader");
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
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "¡Los datos del pago no pudieron ser recuperados!",
                });
                $("#btnAgregar").prop('disabled',false);
                $(".loader").fadeOut("slow");
                $("#loader").removeClass("loader");
              }
            },
            error: function () {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/warning_circle.svg",
                msg: "¡Error al recuperar datos del pago!",
              });
              $("#btnAgregar").prop('disabled',false);   
              $(".loader").fadeOut("slow");
              $("#loader").removeClass("loader");         
            }
          });
        }
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
  } else {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "¡Para un importe con valor 0.00, se debe eliminar del pago!",
    });
    $("#btnAgregar").prop('disabled',false);
  }
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

      //se recorren los checks de la tabla y los que se encuentren en el array de los mostrados en pantalla y sean del mismo tipo de cuenta, se marcan
      $("input[name=invoiceSelected]", hiddenRows).each(function () {
        var arrid = $(this).attr("value").split("-");
        let is_invoice = $(this).attr("data-id");
        var idCheck = arrid[0];
        if (arrIDTemporal[idCheck] && is_invoice == tipoCuenta) {
          $(this).prop("checked", true);
        }
      });
    });
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

  /*Permitir numero decimales */
  $(".numericDecimal-only").on("input", function () {
    var regexp = /[^\d.]/g;
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

function defineTabla(cadena = '', tipoCuenta = ''){

  if(cadena == '' &&
    tipoCuenta == ''){
      url = '';
    }else{
      url= "functions/function_cargaFacturasSelected_pagadas.php?ids=" +
          cadena +
          "&idPago=" +
          $("#idPago").val()+
          "&isSubstitution=" +
          $("#isSubstitution").val()+
          "&tipoCuenta="+tipoCuenta
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
        { data: "Monto factura" },
        { data: "Saldo anterior" },
        { data: "Importe pago" },
        { data: "Saldo insoluto" },
        { data: "No Parcialidad", width: "103.1px" },
        { data: "Acciones", width: "10px" },
      ],
    });
}

$(document).ready(function () {
  valida_editPago();
  carga_Cabecera();
  seleccion = 0;
  cargarDetail();

  //boton para cancelar la seleccion de facturas del modal
  $("#btnCancelarSeleccion").click(function () {
      //si no se encuentra alguna factura cargada, se inicializan los valores para la seleccion de facturas
    if ($.isEmptyObject(arrIDTemporal)) {
      bandera = false;
      fromaPago = "";
      metodoPago = "";
      checkTimbrar(metodoPago);
    }
    $("#mod_agregarFacturas").modal("hide");
  });

  $("#cmbFormasPago").on("change", function () {
    fromaPago=$("#cmbFormasPago").val();
  });

  //boton para agregar las facturas del modal
  $("#btnAgregarFacturas").off('click').click(function () {
    if (!$.isEmptyObject(arrIDTemporal)) {
      arID = Object.assign({}, arrIDTemporal);
      tipoCuenta = AuxtipoCuenta;
      fromaPago = TempFromaPago;
      formaPagoFactura = TempFormaPagoFactura;
      metodoPago = TempMetodoPago;
      checkTimbrar(metodoPago);
      bandera = TempBandera;
      agregarFacturas();
      $("#txtTotal").text(0);
      arrSumaFacturas = {};
    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Debe seleccionar almenos una cuenta por cobrar!",
      });
    }
  });

  //boton para guardar los pagos
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
  $("#Referencia").on("keyup", function(){
    texto=$("#Referencia").val();
    if(texto.length>50){
      $("#Referencia").attr('data-original-title',texto);
    }
  });

  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });

  //Comprobamos si tiene permisos
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }

  if ($("#edit").val() !== "1") {
    $("#alert").modal("show");
  }

  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = href = "../dashboard.php";
  });

  //Redireccionamos al index cuando se oculta el modal alert de editar.
  $("#alert_noEdit").on("hidden.bs.modal", function (e) {
    window.location = href = "./";
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
    AuxtipoCuenta = tipoCuenta
    cargaDatatable($("#idPago").val(), $("#clienteId").val());
    recupera_importe();
    recargaChecks();
    $("#txtCliente").val(clienteSelected);
  });
});
