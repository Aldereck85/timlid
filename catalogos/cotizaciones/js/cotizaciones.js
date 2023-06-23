let cmbRegimen, cmbMedioContactoCliente, cmbVendedorNC, cmbPais, cmbEstado;
var _global = {
  idCotizacion: 0,
};

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='animation-duration:120ms;' />",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    //sLoadingRecords: "Cargando datos...",
    sLoadingRecords: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
    //sLoadingRecords:"<span style='width:80%; animation-duration:120ms;'><img src='../../img/timdesk/Preloader.gif'></span>",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

var filtro = "";
/* $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
  var estatus = data[5]; // informacion del estado de la cotizacion

  if (filtro == "") {
    return true;
  }

  if (estatus == filtro) {
    return true;
  } else {
    return false;
  }
}); */

$(document).ready(function () {
    var tableCotizaciones = $("#tblCotizacion").DataTable({
        // processing: true,
        // serverSide: true,
        pageLength: 50,
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        
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
          buttons: [
            {
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
              className: "btn-custom--white-dark",
              action: function () {
                window.location.href = "agregarCotizacion.php";
              },
            },
            {
              extend: "excelHtml5",
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
              className: "btn-custom--white-dark",
              titleAttr: "Excel",
              exportOptions: {
                columns: ":visible",
              },
            },
          ],
        },
        // ajax: {
        //     url: "php/dataTablesCotizaciones.php",
        //     method: "post",
        //     data: function (data){
        //         var info = (tableCotizaciones == null) ? { "start": 0, "length": 50 } : tableCotizaciones.page.info();
        //         $.extend(data,info);
        //     }
        // },
        // columns: [
        //     {aaData: 0},
        //     {aaData: 1},
        //     {aaData: 2},
        //     {aaData: 3},
        //     {aaData: 4},
        //     {aaData: 5},
        //     {aaData: 6},
        //     {aaData: 7},
        //     {aaData: 8},
        //     {aaData: 9}
        // ],
        ajax: {
            url: "php/funciones.php",
            type: "POST",
            data: { clase: "get_data", funcion: "get_Cotizaciones" },
        },
        columns: [
            { data: "id" },
            { data: "Referencia" },
            { data: "Nombre" },
            { data: "RFC" },
            { data: "Importe" },
            { data: "Sucursal" },
            { data: "Estatus" },
            { data: "Estatus orden" },
            { data: "Estatus factura" },
            { data: "Facturacion directa" }
        ],
        columnDefs: [
            {className: "text-center", targets: [6, 8]},
            {targets:0,visible:false,searchable: false,},
            {targets:[2,3,4,5,6,7,8,9],className: "text-center"}
            
        ],
        order: [[1, 'desc']],
    });

  /* new $.fn.dataTable.Buttons(tableCotizaciones, {
    dom: {
      button: {
        tag: "button",
        className: "btn-table-custom",
      },
      buttonLiner: {
        tag: null,
      },
    },
    buttons: [
      {
        text: '<i class="fas fa-globe"></i> Todos',
        className: "btn-table-custom--blue",
        action: function (e, dt, node, config) {
          filtro = "";
          $("#tblCotizacion").DataTable().draw();
        },
      },
      {
        text: '<i class="far fa-clock"></i> Pendiente',
        className: "btn-table-custom--yellow",
        action: function (e, dt, node, config) {
          filtro = "Pendiente";
          $("#tblCotizacion").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-angle-double-up"></i> Aceptada',
        className: "btn-table-custom--green",
        action: function (e, dt, node, config) {
          filtro = "Aceptada";
          $("#tblCotizacion").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-file-invoice"></i> Facturada',
        className: "btn-table-custom--blue-lightest",
        action: function (e, dt, node, config) {
          filtro = "Facturada";
          $("#tblCotizacion").DataTable().draw();
        },
      },
      {
        text: '<i class="fas fa-ban"></i> Cancelada',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Cancelada";
          $("#tblCotizacion").DataTable().draw();
        },
      },
      {
        text: '<i class="far fa-calendar-times"></i> Vencida',
        className: "btn-table-custom--red",
        action: function (e, dt, node, config) {
          filtro = "Vencida";
          $("#tblCotizacion").DataTable().draw();
        },
      },
    ],
  });

  tableCotizaciones.buttons(1, null).container().appendTo("#btn-filters"); */
});

function facturacionDirecta(idCotizacion) {
  let valFacturacionDirecta;
  let activo, mensaje;
  if ($("#facturacionDirecta_" + idCotizacion).is(":checked")) {
    valFacturacionDirecta = true;
    activo = 1;
    mensaje = "La cotización será seleccionada para facturación directa.";
  } else {
    valFacturacionDirecta = false;
    activo = 0;
    mensaje =
      "La cotización ya no estará seleccionada para facturación directa.";
  }

  $("#facturacionDirecta_" + idCotizacion).prop("checked", false);

  $.ajax({
    type: "POST",
    url: "functions/verificarEstadoCotizacionFacturaFlujo.php",
    data: {
      idCotizacion: idCotizacion,
    },
    success: function (data) {
      var datos = JSON.parse(data);

      if (datos.estatus_cotizacion == 1 && datos.modificar_estado == 0) {
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
            cancelButton: "btn-custom btn-custom--blue",
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons
          .fire({
            title: "¿Desea continuar?",
            text: mensaje,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                type: "POST",
                url: "functions/facturacionDirecta.php",
                data: {
                  idCotizacion: idCotizacion,
                  activar: activo,
                },
                success: function (data) {
                  if (data == "exito") {
                    Lobibox.notify("success", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top", //or 'center bottom'
                      icon: false,
                      img: "../../img/timdesk/checkmark.svg",
                      msg: "Se ha aceptado la cotización para facturación directa, ya la puedes facturar.",
                    });

                    $("#facturacionDirecta_" + idCotizacion).prop(
                      "checked",
                      valFacturacionDirecta
                    );
                    $("#tblCotizacion").DataTable().ajax.reload();
                  }

                  if (data == "fallo") {
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top", //or 'center bottom'
                      icon: false,
                      img: "../../img/timdesk/warning_circle.svg",
                      msg: "Ocurrio un error, intentelo nuevamente.",
                    });
                    $("#facturacionDirecta_" + idCotizacion).prop(
                      "checked",
                      valFacturacionDirecta
                    );
                  }

                  if (data == "no_modificar") {
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top", //or 'center bottom'
                      icon: false,
                      img: "../../img/timdesk/warning_circle.svg",
                      msg: "No se pueden modificar cotizaciones con servicios o sucursales que no manejen inventario.",
                    });

                    if (valFacturacionDirecta) {
                      $("#facturacionDirecta_" + idCotizacion).prop(
                        "checked",
                        false
                      );
                    } else {
                      $("#facturacionDirecta_" + idCotizacion).prop(
                        "checked",
                        true
                      );
                    }
                  }

                  if (data == "no_modificar_pedido") {
                    Lobibox.notify("error", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top", //or 'center bottom'
                      icon: false,
                      img: "../../img/timdesk/warning_circle.svg",
                      msg: "No se pueden modificar cotizaciones con pedido que ya han sido parcialmente surtidos, surtidos, facturados o remisionados.",
                    });

                    if (valFacturacionDirecta) {
                      $("#facturacionDirecta_" + idCotizacion).prop(
                        "checked",
                        false
                      );
                    } else {
                      $("#facturacionDirecta_" + idCotizacion).prop(
                        "checked",
                        true
                      );
                    }
                  }
                },
              });
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
              if (valFacturacionDirecta) {
                $("#facturacionDirecta_" + idCotizacion).prop("checked", false);
              } else {
                $("#facturacionDirecta_" + idCotizacion).prop("checked", true);
              }
            }
          });
      }

      if (datos.estatus_cotizacion != 1 || datos.modificar_estado == 1) {
        let mensaje = "";

        if (datos.modificar_estado == 1) {
          mensaje =
            "No se pueden cambiar cotizaciones con servicios o sucursales sin inventario.";
        } else {
          mensaje = "Sólo se pueden seleccionar cotizaciones aceptadas.";
        }
        $("#facturacionDirecta_" + idCotizacion).prop("checked", false);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: false,
          img: "../../img/timdesk/warning_circle.svg",
          msg: mensaje,
        });
        if (valFacturacionDirecta) {
          $("#facturacionDirecta_" + idCotizacion).prop("checked", false);
        } else {
          $("#facturacionDirecta_" + idCotizacion).prop("checked", true);
        }
      }
    },
  });
}

function idCopear(id) {
  _global.idCotizacion = id;
}

function duplicarCotizacion() {
  $().redirect("agregarCotizaciones.php", {
    idCotizacionU: _global.idCotizacion,
  });
}

/* $(document).on("click", "#detalle_cotizacion", function () {
  var data = $(this).data("id");

  window.location.href = "detalleCotizacion.php?id=" + data;

  var win = window.open("detalleCotizacion.php?id=" + data, "_blank");
  if (win) {
    //Browser has allowed it to be opened
    win.focus();
  } else {
    //Browser has blocked it
    alert("Please allow popups for this website");
  } 
}); */

$(document).on("click", "#detalle_cotizacion_icono", function () {
  var data = $(this).data("id");

  var win = window.open("detalleCotizacion.php?id=" + data, "_blank");
  if (win) {
    //Browser has allowed it to be opened
    win.focus();
  } else {
    //Browser has blocked it
    alert("Please allow popups for this website");
  }
});

function mostrarInputs(cbxClienteFacturacion){
  if (cbxClienteFacturacion.checked) {
    $("#show").removeClass("d-none");
    cargarCMBRegimen('cmbRegimen');
    cargarCMBVendedorNC("cmbVendedorNC");
    cargarCMBMedioContactoCliente("cmbMedioContactoCliente");    
    cmbRegimen = new SlimSelect({
      select: '#cmbRegimen',
      placeholder: 'Seleccione un régimen...',
      deselectLabel: '<span class="">✖</span>'
    });

    cmbMedioContactoCliente = new SlimSelect({
      select: '#cmbMedioContactoCliente',
      placeholder: 'Seleccione un medio...',
      deselectLabel: '<span class="">✖</span>'
    });

    cmbVendedorNC = new SlimSelect({
      select: '#cmbVendedorNC',
      placeholder: 'Seleccione un vendedor...',
      deselectLabel: '<span class="">✖</span>'
    });

    cmbPais = new SlimSelect({
      select: '#cmbPais',
      placeholder: 'Seleccione un pais...',
      deselectLabel: '<span class="">✖</span>'
    });

    cmbEstadoC = new SlimSelect({
      select: '#cmbEstadoC',
      placeholder: 'Seleccione un estado...',
      deselectLabel: '<span class="">✖</span>'
    });
  } else {
    $("#show").addClass("d-none");
    cmbRegimen.destroy();
    cmbMedioContactoCliente.destroy();
    cmbVendedorNC.destroy();
    cmbPais.destroy();
    cmbEstadoC.destroy();
  }
}

function cargarCMBRegimen(input) {
  var html = "";
  $.ajax({
    url: "php/funciones.php",
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
    url: "php/funciones.php",
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
    url: "php/funciones.php",
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

/* function escribirRazonSocial() {
  var valor = $("#txtRazonSocial").val();
  var valorHis = $("#txtRazonSocialHis").val();

  if (valor != valorHis) {
    console.log("Valor nombre" + valor);
    $.ajax({
      url: "../clientes/php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_razonSocial_Cliente",
        data: valor,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta nombre valida: ", data);
        // Validar si ya existe el identificador con ese nombre
        if(parseInt(data[0]["existe"]) == 1){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "La razón social ya esta registrada en el sistema."
          );
          $("#txtRazonSocial").addClass("is-invalid");
          console.log("¡Ya existe!");
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
          console.log("¡No existe!");
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
} */

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
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreCom").css("display", "none");
        $("#invalid-nombreCom").text(
          "El cliente debe tener un nombre comercial."
        );
        $("#txtNombreComercial").removeClass("is-invalid");
        console.log("¡No existe!");
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

function venderCotizacion(idCotizacion, item) {
  if(item.checked){
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--border-blue",
        cancelButton: "btn-custom btn-custom--blue",
      },
      buttonsStyling: false,
    });

    swalWithBootstrapButtons
      .fire({
        title: "¿Desea continuar?",
        text: 'Marcar como vendida',
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
        cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
        reverseButtons: false,
      })
      .then((result) => {
        if (result.isConfirmed) {
          
          $.ajax({
            type: 'POST',
            url: 'functions/venderCotizacion.php',
            data: {
              idCotizacion: idCotizacion
            },
            dataType: "json",
            success: function (data) {
              console.log("respuesta nombre valida: ", data.Referencia);
              $("#tblCotizacion").DataTable().ajax.reload(); 
              const swalWithBootstrapButtonsReferencia = Swal.mixin({
                customClass: {
                  actions: "d-flex justify-content-around",
                  confirmButton: "btn-custom btn-custom--border-blue",
                  cancelButton: "btn-custom btn-custom--blue",
                },
                buttonsStyling: false,
              });

              swalWithBootstrapButtonsReferencia
              .fire({
                title: "Referencia de la venta",
                text: 'Ir a la venta: ' + data.Referencia,
                icon: "info",
                showCancelButton: true,
                confirmButtonText: '<span class="verticalCenter"> <a href="../ventas_directas/catalogos/ventas/ver_ventas.php?vd=' + data.PKVentaDirecta + '" target="_blank">Aceptar</a></span>',
                cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
                reverseButtons: false,
              })
              .then((result) => {
                if (result.isConfirmed) {
                  Swal.close();
                  $("#cbxMarcarVenta-" + idCotizacion).prop("checked", false);
                } else if (
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                  $("#cbxMarcarVenta-" + idCotizacion).prop("checked", false);
                }
              });      
            },
            error: function (error) {
              console.log("respuesta nombre valida: ", error);
              $("#tblCotizacion").DataTable().ajax.reload(); 
              const swalWithBootstrapButtonsReferencia = Swal.mixin({
                customClass: {
                  actions: "d-flex justify-content-around",
                  confirmButton: "btn-custom btn-custom--border-blue",
                  cancelButton: "btn-custom btn-custom--blue",
                },
                buttonsStyling: false,
              });

              swalWithBootstrapButtonsReferencia
              .fire({
                title: "Referencia de la venta",
                text: 'Ir a la venta: ' + error.responseText.Referencia,
                icon: "info",
                showCancelButton: true,
                confirmButtonText: '<span class="verticalCenter"> <a href="../ventas_directas/catalogos/ventas/ver_ventas.php?vd=' + error.responseText.PKVentaDirecta + '" target="_blank">Aceptar</a></span>',
                cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
                reverseButtons: false,
              })
              /* .then((result) => {
                if (result.isConfirmed) {
                  
                  $.ajax({
                    type: 'POST',
                    url: 'functions/venderCotizacion.php',
                    data: {
                      idCotizacion: idCotizacion
                    },
                    dataType: "json",
                    success: function (data) {
                      console.log("respuesta nombre valida: ", data);
                      location.reload();        
                    },
                    error: function (error) {
                      console.log("respuesta nombre valida: ", error);
                      $("#tblCotizacion").DataTable().ajax.reload(); 
                      const swalWithBootstrapButtonsReferencia = Swal.mixin({
                        customClass: {
                          actions: "d-flex justify-content-around",
                          confirmButton: "btn-custom btn-custom--border-blue",
                          cancelButton: "btn-custom btn-custom--blue",
                        },
                        buttonsStyling: false,
                      });
                      
                    }
                  });
                } else if (
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                  $("#cbxMarcarVenta-" + idCotizacion).prop("checked", false);
                }
              }) */;
              
            }
          });
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          $("#cbxMarcarVenta-" + idCotizacion).prop("checked", false);
        }
      });
  }
}