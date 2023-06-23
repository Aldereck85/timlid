function cargar_moduloPagos(id) {
  let is_invoice = $("#is_invoice").val();
  $.ajax({
    url: "functions/function_redirecciona_RecepcionPagos.php",
    data: {
      id: id,
      is_invoice: is_invoice
    },
    dataType: "json",
    success: function (data) {
      if (data["estatus"] == "ok") {
        if (data["result"] == 1) {
          $().redirect("../recepcion_pagos/", {
            'rutaFrom':2,
            'idCliente':data['cliente_id'],
            'idFactura':data['idFactura'],
            'is_invoice':is_invoice
            });
        } else if (data["result"] == 2) {
          $().redirect("../recepcion_pagos/pagos.php", {
            'rutaFrom':2,
            'idCliente':data['cliente_id'],
            'idFactura':data['idFactura'],
            'is_invoice':is_invoice
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
      } else if (data["estatus"] == "cancelada"){
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
      }else {
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

function cargar_moduloNotasCredito(id) {
  if($("#is_invoice").val() != 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "La cuenta por cobrar no aplica para notas de crédito",
    });
    return;
  }
  $.ajax({
    url: "functions/function_redirecciona_notasCredito.php",
    data: {
      id: id,
    },
    dataType: "json",
    success: function (data) {
      if (data["estatus"] == "ok") {
        $().redirect("../notas_credito/agregar.php", {
          'rutaFrom':2,
          'idCliente':data['cliente_id'],
          'idFactura':data['idFactura'],
          });
      } else if (data["estatus"] == "cancelada"){
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
      }else{
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

//obtiene array con ids de complementos
function Descarga_ComplementosPago(){
  if($("#is_invoice").val() != 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "La cuenta por cobrar no aplica",
    });
    return;
  }
  id=$("#idFactura").val();
  $.ajax({
    url: "functions/function_recuperaComplementos_deFactura.php",
    data: {
      id: id,
    },
    dataType: "json",
    success: function (data) {
      if(data["status"]=="ok"){
        data["result"].forEach(element => {
          carga_complemento(element); 
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
          msg: data["result"],
        });
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

function Descarga_NotasCredito(){
  if($("#is_invoice").val() != 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "La cuenta por cobrar no aplica",
    });
    return;
  }
  id=$("#idFactura").val();
  $.ajax({
    url: "functions/function_recuperaNotasCredito_factura.php",
    data: {
      id: id,
    },
    dataType: "json",
    success: function (data) {
      if(data["status"]=="ok"){
        data["result"].forEach(element => {
          carga_complemento(element); 
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
          msg: data["result"],
        });
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

function Descarga_pdf(){
  let id = $("#idFactura").val();

  if($("#is_invoice").val() != 1){
      window.location.href = "../ventas_directas/catalogos/ventas/functions/descargar_VentaDirecta?txtId="+id;
  }else{
    folio=$("#txtfolio").val();
    $.ajax({
      url: "functions/function_descarga_pdf.php",
      data: {
        id: id,
      },
      xhrFields: {
        responseType: "blob",
      },  
      success: function (data) {
        try{
          var a = document.createElement("a");
          var url = window.URL.createObjectURL(data);
          a.href = url;
          a.download = "Folio-" + folio + ".pdf";
          a.click();
          window.URL.revokeObjectURL(url);
        }catch(error){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Error, no se pudo descargar la factura",
          });
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

function Descarga_xml(){
  let id = $("#idFactura").val();

  if($("#is_invoice").val() == 1){
    folio=$("#txtfolio").val();
    $.ajax({
      url: "functions/function_descarga_xml.php",
      data: {
        id: id,
      },
      dataType: "XML",  
      success: function (data) {
        try{
          var xmlDoc = new XMLSerializer().serializeToString(data);
          var blob = new File([xmlDoc], "Folio-" + folio + ".xml",  {type: "text/xml"});
          var a = document.createElement("a");
          var url = window.URL.createObjectURL(blob);
          a.href = url;
          a.download = "Folio-" + folio + ".xml";
          a.click();
          window.URL.revokeObjectURL(url);
        }catch(error){
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Error, no se pudo descargar la factura",
          });
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
  } else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "No es posible descargar el XML, la cuenta por cobrar no proviene de una factura",
    });
  }
}

//carga en pestaña un pdf de facturapi(complemento o nota de crédito)
function carga_complemento(idComplemento){
  if($("#is_invoice").val() != 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "La cuenta por cobrar no aplica",
    });
    return;
  }
  $.ajax({
    url: "functions/function_descarga_pdf_nuevaVentana.php",
    data: {
      id: idComplemento,
    },
    dataType: "binary",
    xhrFields: {
      responseType: "blob",
    },   
    success: function (data) {
      try{
        var file = new Blob([data],{type: 'application/pdf'});
        let url = window.URL.createObjectURL(file);
        window.open(url);
      }catch(error){
        console.log(error);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "Error, no se pudo descargar el complemento",
        });
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

function valida_CuentaCobrar(){
  let is_invoice = $("#is_invoice").val();
  if(is_invoice == 1){
    $("#btnNotas").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    $("#btn_docs_Relacionados").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
    $("#btn_downxml").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  }
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}

$(document).ready(function () {
  /* En #edit.val Tengo el valor binario de la tabla funciones_permisos->funcion_editar-> En Mi Pantalla (60) en el rol actual*/
  /* En el If() ocultaremos la columna de editar dependiendo de su rol*/
  /* Ocultamos la columna cargando dos tablas diferentes donde en el else agregamos la columna de editar al target de ocultar */

  var idFactura = $("#idFactura").val();

  //valida si es una cuenta por cobrar de una factura o venta
  valida_CuentaCobrar();

  //redirecciona al modulo de recepcion de pagos
  $("#btnPagos").on("click", function (e) {
    cargar_moduloPagos(idFactura);
  });

  //redirecciona al modulo de notas de crédito
  $("#btnNotas").on("click", function (e) {
    cargar_moduloNotasCredito(idFactura);
  });

  //abre todos los complementos de pago
  $("#btnCP").on("click", function (e) {
    Descarga_ComplementosPago(idFactura);
  });
  
  //abre todos las notas de crédito
  $("#btnNC").on("click", function (e) {
    Descarga_NotasCredito(idFactura);
  });

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

  var topButtons = [];

  var var_get = $("#is_invoice").val() == 1 ? "idFactura" : "idVenta"; 

  $("#tbldetalle")
    .DataTable({
      language: idioma_espanol,
      info: false,
      scrollX: true,
      bSort: false,
      responsive: true,
      lengthChange: false,
      paging: false,
      searching: false,
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
        buttons: topButtons,
      },
      ajax: "functions/get_detalle_factura.php?"+var_get+"=" + idFactura,
      columns: [
        { data: "Clave" },
        { data: "Descripcion" },
        { data: "Cantidad" },
        { data: "Precio" },
        { data: "Importe" },
      ],
      columnDefs:[
        {
            targets:[1,2,3,4],
            class:"text-center"
        }
      ]
    });

    //se recuperan impuestos de la cuenta por cobrar
    let is_invoice = $("#is_invoice").val();
    if(is_invoice == 1){
      $.ajax({
        method: "post",
        url: "../facturacion/php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_invoiceDetail",
          value:idFactura
        },
        dataType: 'json',
        success: function(response){        
          $("#Subtotal").html(response.subtotal);
          $("#impuestos").html(response.impuestos);
          $("#Total").html('<b>'+response.total+'</b>'); 
        },
        error:function(error){
          console.log(error);
        }
      });
    }else{
      //Obtener subtotal
      $.ajax({
        url: "../ventas_directas/php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "get_subTotalVentaDirectaVer",
          datos: idFactura,
        },
        dataType: "json",
        success: function (respuesta) {
          $("#Subtotal").html("$ " + dosDecimales(respuesta[0].subtotal));
        },
        error: function (error) {
          console.log(error);
        },
      });

       //Obtener impuestos
        $.ajax({
          url: "../ventas_directas/php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_impuestoVentaDirectaEdit_v2",
            datos: idFactura,
            datos2: 0,
          },
          dataType: "json",
          success: function (respuesta) {
            //Recorrer las respuestas de la consulta
            var tasa = "";
            let html = "";
            $.each(respuesta, function (i) {
              if (!$("#impuestos-head-" + respuesta[i].pkImpuesto+ respuesta[i].tasa).length) {
                if (respuesta[i].tasa == "" || respuesta[i].tasa == null) {
                  tasa = respuesta[i].tasa;
                } else {
                  tasa = respuesta[i].tasa + "%";
                }
                
                html +=
                  '<span style="color: var(--color-primario);" id="impuestos-head-' + respuesta[i].pkImpuesto+ respuesta[i].tasa + '">'+
                  respuesta[i].nombre + ": $ " + dosDecimales(respuesta[i].totalImpuesto) +
                  "</span><br>";

                  /// Si es Retenido TipoImp 2 se resta del Total.
                  if(respuesta[i].tipoImp == 2){
                    Total -= respuesta[i].totalImpuesto;
                  }else{
                    Total += respuesta[i].totalImpuesto;
                  }
                  
              }
            });

            $("#impuestos").html(html);
            $("#Total").html(dosDecimales(Total));

          },
          error: function (error) {
            console.log(error);
          },
        });
    }

  //Comprobamos si tiene permisos
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = href = "../dashboard.php";
  });

  /* Obtenemos los valores de la cuenta por pagar y los ponemos en los campos de la pantalla editar */
  $.ajax({
    type: "POST",
    url: "../cuentas_cobrar/functions/get_ajax_detalle_factura.php",
    dataType: "json",
    data: { idFactura: idFactura, is_invoice: var_get, funcion: "1" },
    success: function (data) {
      if (data.status == "ok") {
        var html = '<a style="cursor:pointer" href="../clientes/catalogos/clientes/detalles_cliente.php?c='+data.result.id+'" target="_blank">'+data.result.razon_social+'</a>'
        $("#alertInvoice").modal("hide");
        $("#nombre").html(html);
        $("#txtfolio").html(data.result.folio);
        $("#txtimporte").html("$"+data.result.total_facturado);
        $("#Total").html("$"+data.result.total_facturado);
        $("#txtfechaF").html(data.result.fecha_timbrado);
        $("#txtfechaV").html(data.result.fechaVencimiento);
      } else {
        //mostramos el modal de alerta
        $("#alertInvoice").modal("show");

        //Redireccionamos al modulo cuando se oculta el modal.
        $("#alertInvoice").on("hidden.bs.modal", function (e) {
          window.location = href = "../cuentas_cobrar";
        });
      }
    },
  });
});
