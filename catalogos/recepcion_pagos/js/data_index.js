function facturaPago(folio) {
  $("#loader").addClass("loader");
  $(".loader").fadeIn("slow");
  $("#facturar").prop('disabled',true);
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
        $("#facturar").prop('disabled',false);
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

          tablaC.ajax.url("functions/get_movimientos.php").load();

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
            msg: data["msg"],
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

function Descarga_pdf(id) {
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

function Descarga_xml(id) {
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

function cancelaComplemento(folio){
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
              tablaC.ajax.url("functions/get_movimientos.php").load();
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
 
//verifica el estado de la bandera de filtrado de los complemenetos
function validaFiltro() {
  $.ajax({
    url: "functions/function_validaBanderaComplementos.php",
    dataType: "json",
    success: function (data) {
      if (data["estatus"] == "ok") {
        $("input[type=search]").val("No timbrado Pagado");
        tablaC.search("No timbrado Pagado");
        tablaC.draw();
      } else {
        is_from_cuentasCobrar();
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

//verifica si se accedió desde la vista de una factura en "cuentas cobrar"
function is_from_cuentasCobrar() {
  if ($("#idClienteFrom").attr("value") != undefined) {
    cliente=$("#idClienteFrom").val();
    selectCliente.set(cliente);
  }  
  if ($("#idFacturaFrom").attr("value") != undefined) {
    factura=$("#idFacturaFrom").val();
    is_invoice=$("#is_invoice").val();
    tablaC.ajax
    .url("functions/get_movimientos.php?factura=" + factura +"&&is_invoice="+is_invoice)
    .load();   
  }
  
}

function notificacion(){
  var notifi = $("#notifi").val();
    var m;
    switch (notifi) {
      case "1":
        m = "¡Pago registrado con exito!";
        break;
      case "2":
        m = "¡Pago actualizado con exito!"
        break;
      case "3":
        m = "¡Pago eliminado con exito!"
        break;
    }

    if (notifi > 0) {
      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 1000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: m,
      });
    }
}

$(document).ready(function () {
  $("#mdlDelete").modal("hide");

  carga_cmbClientes();

  //cuando cambia la selección del cliente se filtra
  $("#chosenClientes").on("change", function () {
    $("#btnFilterExits").click();
  });
 
  $("#btnFilterExits").on("click", function (e) {
    seleccion = document.getElementById("chosenClientes").value;
    fecha_desde = document.getElementById("txtDateFrom").value;
    fecha_hasta = document.getElementById("txtDateTo").value;

    filtra_indexPagos(seleccion, fecha_desde, fecha_hasta);
  });

  let espanol = {
    sProcessing: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />.",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
    sLoadingRecords: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
    searchBuilder: {
      add: "Filtros",
      condition: "Condición",
      conditions: {
        string: {
          contains: "Contiene",
          empty: "Vacio",
          endsWith: "Finaliza con",
          equals: "Igual",
          not: "Diferente",
          notEmpty: "No vacío",
          startsWith: "Comienza con",
        },
        date: {
          after: "Después de",
          before: "Antes de",
          between: "Entre",
          empty: "Vacio",
          equals: "Igual",
          not: "Diferente",
          notBetween: "No está entre",
          notEmpty: "No vacío",
        },
        number: {
          between: "Between",
          empty: "Vacio",
          equals: "Igual",
          gt: "Mayor que",
          gte: "Mayor o igual que",
          lt: "Menor que",
          lte: "Menor o igual que",
          not: "Diferente",
          notBetween: "No está entre",
          notEmpty: "No vacío",
        },
        array: {
          contains: "Contiene",
          empty: "Vacio",
          equals: "Igual",
          not: "Diferente",
          notEmpty: "No vacío",
          without: "Sin",
        },
      },
      clearAll: "Limpiar",
      deleteTitle: "Eliminar",
      data: "Columna",
      leftTitle: "Izquierda",
      logicAnd: "+",
      logicOr: "o",
      rightTitle: "Derecha",
      title: {
        0: "Filtros",
        _: "Filtros (%d)",
      },
      value: "Opción",
      valueJoiner: "et",
    },
  };
  var topButtons = [
    {
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
      className: "btn-custom--white-dark",
      action: function () {
        window.location.href = "pagos.php";
      },
    },
  ];
  if ($("#exportar").val() == 1) {
    topButtons.push({
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
      className: "btn-custom--white-dark",
    });
  }

  tablaC = $("#tblmovimientos")
    .DataTable({
      language: espanol,
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
            className: "btn-custom mr-2",
          },
          buttonLiner: {
            tag: null,
          },
        },
        buttons: topButtons,
      },
      ajax: {
        url: "functions/get_movimientos.php",
        async:false
      },
      columns: [
        { data: "Folio pago" },
        { data: "Folio factura" },
        { data: "Cliente" },
        { data: "Fecha" },
        { data: "Forma de pago" },
        { data: "Metodo de pago" },
        { data: "Cuenta" },
        { data: "Monto total", width: "15%"},
        { data: "Referencia" },
        { data: "Responsable" },
        { data: "Estado", width: "100px" },
        { data: "Acciones", width: "1px" },
      ],
      columnDefs: [
        {
            targets: [ 3,4,7,11 ],
            searchable: false,
        },
        {
          targets: [7],
          className: 'dt-center',
        }
      ],
    });

    notificacion();
    validaFiltro();

    //activa los tooltips en datatable
    $('#tblmovimientos tbody').on('mouseover', 'tr', function () {
      $('[data-toggle="tooltip"]').tooltip({
          trigger: 'hover',
          html: true
      });
      $('[data-toggle="tooltip"]').on("click", function () {
        $(this).tooltip("dispose");
      });
    });

  $(function () {
    $("[data-toggle='tooltip']").tooltip();
  });

  //Comprobamos si tiene permisos para ver
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }

  if ($("#add").val() !== "1") {
    $("#agregarPagoBTN").remove();
  }

  if ($("#delete").val() !== "1") {
    $("#deletePago").remove();
  }

  if ($("#edit").val() !== "1") {
    $("#editarMP").remove();
  }

  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = href = "../dashboard.php";
  });
});
