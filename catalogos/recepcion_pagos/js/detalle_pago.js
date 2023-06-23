function cargaCabecera(idPago){
  //reinicia los botones
  html="";
  $("#divPdf").html(html);
  $("#divXml").html(html);
  $("#divCancelarComplemento").html(html);
  $("#divEditar").html(html);
  $("#divBTimbrar").html(html);
  $("#divEliminarPago").html(html);

  /* Obtenemos los valores de la cuenta por pagar y los ponemos en los campos de la pantalla editar */
  $.ajax({
    type: "POST",
    url: "../recepcion_pagos/functions/get_ajax_detallePago.php",
    dataType: "json",
    data: { idPago: idPago },
    success: function (data) {
      if (data.status == "ok") {
        $("#alertInvoice").modal("hide");
        $("#cliente").text(data.result.NombreComercial);
        $("#txtFecha").text(data.result.fecha_pago);
        $("#cmbTipo").text(data.result.metodo_pago);
        $("#cmbForma").text(data.result.descripcion);
        $("#cuenta").text(data.result.cuenta);
        $("#Referencia").text(data.result.Referencia);
        $("#txtComentarios").text(data.result.comentarios);
        $("#txtTotal").text(data.result.total);

        //botones de acciones
        data.arrButtons.forEach(element => {
          switch(element){
            case 1:
              html = '<span class="btn-table-custom btn-table-custom--blue" name="btnDescargarPDF" onclick="Descarga_pdf_d(\''+idPago+'\')"><i class="fas fa-file-pdf"></i> Descargar PDF</span>';
              $("#divPdf").html(html);
              break;
            case 2:
              html = '<span class="btn-table-custom btn-table-custom--turquoise" id="btnDescargarXML" onclick="Descarga_xml_d(\''+idPago+'\')"><i class="fas fa-cloud-download-alt"></i> Descargar XML</span>';
              $("#divXml").html(html);
              break;
            case 3:
              html = '<button class="btn-table-custom btn-table-custom--red" id="btnCancelaComplemento" onclick="cancelaComplemento_d(\''+idPago+'\')"><i class="fas fa-solid fa-ban"></i> Cancela Complemento</button>';
              $("#divCancelarComplemento").html(html);
              break;
            case 4:
              html = '<a class="btn-table-custom btn-table-custom--blue" id="btnEditarRP" href="editarPago.php?folio='+idPago+'"><i class="fas fa-edit"></i> Editar</a>';
              $("#divEditar").html(html);
              break;
            case 5:
              html = '<button class="btn-table-custom btn-table-custom--blue" id="btnTimbraRP" onclick="facturaPago_d(\''+idPago+'\');"><img src="../../img/icons/ICONO FACTURACION-01.svg" width="30px" height="30px"> Timbrar Complemento de pago</button>';
              $("#divBTimbrar").html(html);
              break;
            case 6:
              html = '<button class="btn-table-custom btn-table-custom--blue" id="btnEliminaPago" onclick="eliminaPago(\''+idPago+'\',2);"><img src="../../img/timdesk/delete.svg" width="20px" height="20px"> Eliminar pago</button>';
              $("#divEliminarPago").html(html);
              break;
          }
        });

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

function facturaPago_d(folio) {
  $("#loader").addClass("loader");
  $(".loader").fadeIn("slow");
  $("#btnTimbraRP").prop('disabled',true);
  setTimeout(() => {
    $.ajax({
      url: "functions/function_Genera_Factura_Pago.php",
      data: {
        folio: folio,
      },
      dataType: "json",
      success: function (data) {
        $.ajax({
          url: "functions/function_enviar_correosAutomaticos.php",
          data: {
            folio: folio,
          },
          dataType: "json",    
          success: function (data) {
            console.log(data);
          }
        });
        $(".loader").fadeOut("slow");
        $("#btnTimbraRP").prop('disabled',false);
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

          cargaCabecera(folio);

          //se descarga la factura
          descargaFactura_d(data["result"]);
          
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
            msg: "¡Algo salio mal!, " + data["result"],
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
        $(".loader").fadeOut("slow");
        $("#loader").removeClass("loader");
        $("#btnTimbraRP").prop('disabled',false);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Error al timbrar!: "+msg,
        });
      },
    });
  }, 1500); 
}

function descargaFactura_d(id) {
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

function Descarga_pdf_d(id) {
  $.ajax({
    url: "functions/function_descarga_pdf.php",
    data: {
      id: id,
    },
    xhrFields: {
      responseType: "blob",
    },
    success: function (data) {
      if (data != "err") {
        var a = document.createElement("a");
        var url = window.URL.createObjectURL(data);
        a.href = url;
        a.download = "CP-" + id + ".pdf";
        a.click();
        window.URL.revokeObjectURL(url);
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Error, no se pudo descargar!",
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

function Descarga_xml_d(id) {
  $.ajax({
    url: "functions/function_descarga_xml.php",
    data: {
      id: id,
    },
    dataType: "XML",

    success: function (data) {
      if (data != "err") {
        var a = document.createElement("a");
        var xmlDoc = new XMLSerializer().serializeToString(data);
        var blob = new File([xmlDoc], "CP" + id + ".xml", { type: "text/xml" });
        var url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = "CP-" + id + ".xml";
        a.click();
        window.URL.revokeObjectURL(url);
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Error, no se pudo descargar!",
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

function cancelaComplemento_d(folio){
  //se reinicia select
  selectMotivoCancelacion.set("f");
  $("#mdlCancelComplement").modal('show');

  $("#btnCancelarCancelarComplemento").on('click', function(){
      $("#mdlCancelComplement").modal('hide');
  });

  $("#btnAcepCancelarComplemento").off('click').on('click', function(){
    var motivo = document.getElementById("cmbMotivoCancela").value;
    if(motivo != "f"){
      if(motivo == "01"){
        $().redirect("../recepcion_pagos/editarPago.php", {
          'folio': folio,
          'sustitucion':1
          });
      }else{
        $("#loader").addClass("loader");
        $(".loader").fadeIn("slow");
        setTimeout(() => {
        $.ajax({
          url: "functions/function_cancela_complemento.php",
          data: {
            folio: folio,
            motivo: motivo,
          },
          dataType: "json",
          success: function (data) {
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
                msg: "¡Complemento cancelado con exito!",
              });
              cargaCabecera(folio);
            } else if (data["status"] == "fine") {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/warning_circle.svg",
                msg: "¡Advertencia, el complemento no se ha encontrado!",
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
                msg: "¡Algo salio mal!, " + data["result"],
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
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: msg,
            });
            $(".loader").fadeOut("slow");
            $("#loader").removeClass("loader");
          },
        });
        }, 1500);
      }
    }else{
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Se deve seleccionar un motivo de cancelación!",
      });
    }
    
  });
}

$(document).ready(function () {
  var idPago = $("#idPago").val();
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

  var topButtons = [];
  if ($("#exportar").val() == 1) {
    topButtons.push({
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar Excel</span>',
      className: "btn-custom--white-dark btn-custom",
      titleAttr: "Excel",
      exportOptions: {
        columns: ":visible",
      },
    });
  }

  $("#tblFacturas").DataTable({
    language: idioma_espanol,
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
          className: "",//btn-table-custom
        },
        buttonLiner: {
          tag: null,
        },
      },
      buttons: topButtons,
    },
    ajax: "functions/get_detalle_Pago.php?idPago=" + idPago,
    columns: [
      { data: "Folio" },
      { data: "Cliente" },
      { data: "F Expedicion" },
      { data: "F Vencimiento" },
      { data: "Monto factura" },
      { data: "Saldo anterior" },
      { data: "Importe pago" },
      { data: "Saldo insoluto" },
      { data: "No Parcialidad", width: "59px" },
    ],
  });

  cargaCabecera(idPago);

  //Comprobamos si tiene permisos para ver
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = href = "../dashboard.php";
  });
});
