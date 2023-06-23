var workgroup = [];
var lotes_existencia = [];
id_orden = $("#txtProduccionOrderId").val();

$(document).ready(function () {
  value = $("#txtProduccionOrderId").val();
  cmbResponsable = new SlimSelect({
    select: '#cmbResponsable',
    placeholder: 'Seleccione un responsable...',
  });

  cmbGrupoTrabajo = new SlimSelect({
    select: '#cmbGrupoTrabajo',
    placeholder: 'Seleccione un grupo de trabajo...',
    addable: function (value) {
      addWorkGroup(value);
      return value;
    }
  });

  new SlimSelect({
    select: "#cmbLot",
    placeholder: "Seleccione un lote...",
  });

  loadCombo('responsable', '#cmbResponsable', "", "", "Seleccione un responsable...");
  loadCombo("grupoTrabajo", '#cmbGrupoTrabajo', "", "", "Seleccione un grupo de trabajo...");

  loadTblMateriales(value);

  $.ajax({
    url: "../../php/funciones_copy.php",
    method: "POST",
    data: {
      clase: "get_data",
      funcion: "get_detailsDataproductionOrder",
      value: value
    },
    datatype: "json",
    success: function (respuesta) {
      res = JSON.parse(respuesta);
      //console.log(res);
      grupo_trabajo = JSON.parse(res.grupo_trabajo);

      folio = ('0000' + res.folio).slice(-5);
      //grupo_trabajo = (!res.grupo_trabajo && res.grupo_trabajo !== null) ? res.grupo_trabajo : "Sin grupo de trabajo";
      //sucursal = (!res.sucursal && res.sucursal !== null) ? res.sucursal : "Sin sucursal";

      var estatus = $("#txtEstatusOrdenProduccion").val();
      var producto = res.clave + ' - ' + res.producto;
      $("#noOrdenProduccion").html("OP" + folio);
      $("#txtSucursalId").val(res.sucursal_id);
      $("#txtSucursal").val(res.sucursal);
      $("#txtFechaCreacion").val(res.fecha_creacion);
      $("#txtFechaPrevista").val(res.fecha_prevista);
      $("#txtProducto").val(producto);
      $("#txtCantidad").val(res.cantidad);
      $("#txtResponsable").val(res.responsable);
      $("#txaNotas").val(res.notas);

      cmbResponsable.set(res.id_responsable);

      cmbGrupoTrabajo.set(getConcatJson(grupo_trabajo));
      $("#txtExistenciaSeguimiento").val(res.existencia_seguimiento);

      switch (parseInt(res.estatus)) {
        case 1:
          estatus = "Pendiente";
          $("#estatusOrdenProduccion").addClass("left-dot yellow-dot");
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--green" id="btn_accept_production"><i class="fas fa-check-double"></i> Aceptar</a>');
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--red" id="btn_cancel_production"><i class="fas fa-ban"></i> Cancelar</a>');
          $("#check_expected_date").html('<div class="form-check">' +
            '<input class="form-check-input" type="checkbox" value="" id="chkFechaPrevista">' +
            '<label class="form-check-label" for="chkFechaPrevista">Editar</label>' +
            '<div id="editar_fechaPrevista_habilitar"></div>' +
            '                                 </div>');
          $("#check_quantity").html('<div class="form-check">' +
            '<input class="form-check-input" type="checkbox" value="" id="chkCantidad">' +
            '<label class="form-check-label" for="chkCantidad">Editar</label>' +
            '<div id="editar_cantidad_habilitar"></div>' +
            '</div>');
          $("#check_responsable").html('<div class="form-check">' +
            '<input class="form-check-input" type="checkbox" value="" id="chkResponsable">' +
            '<label class="form-check-label" for="chkResponsable">Editar</label>' +
            '<div id="editar_responsable_habilitar"></div>' +
            '</div>');
          $("#check_workgroup").html('<div class="form-check">' +
            '<input class="form-check-input" type="checkbox" value="" id="chkGrupoTrabajo">' +
            '<label class="form-check-label" for="chkGrupoTrabajo">Editar</label>' +
            '<div id="editar_grupoTrabajo_habilitar"></div>' +
            '</div>');
          break;
        case 2:
          estatus = "Aceptada";
          $("#estatusOrdenProduccion").addClass("left-dot green-dot");

          if (res.existencia_seguimiento > 0) {
            $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a>');
            $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--turquoise" id="btn_download_pdf"><i class="far fa-file-pdf"></i> PDF</a>');
          } else {
            $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a>');
            $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--red" id="btn_cancel_production"><i class="fas fa-ban"></i> Cancelar</a>');
            $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--turquoise" id="btn_download_pdf"><i class="far fa-file-pdf"></i> PDF</a>');
          }

          break;
        case 3:
          estatus = "En proceso";
          $("#estatusOrdenProduccion").addClass("left-dot green-dot");
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a>');
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--red" id="btn_close_production"><i class="fas fa-ban"></i> Cerrar</a>');
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--turquoise" id="btn_download_pdf"><i class="far fa-file-pdf"></i> PDF</a>');
          break;
        case 4:
          estatus = "Terminada";
          $("#estatusOrdenProduccion").addClass("left-dot turquoise-dot");
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a>');
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--turquoise" id="btn_download_pdf"><i class="far fa-file-pdf"></i> PDF</a>');
          break;
        case 5:
          estatus = "Cancelada";
          $("#estatusOrdenProduccion").addClass("left-dot red-dot");
          break;
        case 6:
          estatus = "En proceso atrasada";
          $("#estatusOrdenProduccion").addClass("left-dot yellow-dot");
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a>');
        case 7:
          estatus = "Cerrada parcial";
          $("#estatusOrdenProduccion").addClass("left-dot red-dot");
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a>');
      }
      $("#estatusOrdenProduccion").html(estatus);

    }, error: function (error) {
      console.log(error);
    }
  })

});

$(document).on("click","#btn_download_pdf",()=>{
    
    $.redirect('../../php/orden_produccion_pdf.php',
        {
            id:$("#txtProduccionOrderId").val()
        },
        "POST",
        "_blank"
    );
});

$(document).on("click", "#chkFechaPrevista", function () {
  if ($(this).is(":checked")) {
    $("#editar_fechaPrevista_habilitar").html('<a class="btn-table-custom--blue mt-auto p-2" href="#" id="btn_editarFechaPrevista"><i class="fas fa-plus-square"></i> Guardar</a>');
    $("#txtFechaPrevista").prop("readonly", false);
  } else {
    $("#txtFechaPrevista").prop("readonly", true);
    $("#editar_fechaPrevista_habilitar").html('');
  }
});

$(document).on("click", "#chkCantidad", function () {
  //if(estatus === 1){
  if ($(this).is(":checked")) {
    $("#editar_cantidad_habilitar").html('<a class="btn-table-custom--blue mt-auto p-2" href="#" id="btn_editarCantidad"><i class="fas fa-plus-square"></i> Guardar</a>');
    $("#txtCantidad").prop("readonly", false);
  } else {
    $("#txtCantidad").prop("readonly", true);
    $("#editar_cantidad_habilitar").html('');
  }
  //}
});

$(document).on("click", "#chkResponsable", function () {
  //if(estatus === 1){
  if ($(this).is(":checked")) {
    $("#txtResponsable").css("display", "none");
    $("#cmbResponsable_habilitar").css("display", "block");

    $("#editar_responsable_habilitar").html('<a class="btn-table-custom--blue mt-auto p-2" href="#" id="btn_editarResponsable"><i class="fas fa-plus-square"></i> Guardar</a>');
  } else {
    $("#txtResponsable").css("display", "block");
    $("#cmbResponsable_habilitar").css("display", "none");
    $("#editar_responsable_habilitar").html('');

  }
  //}
});

$(document).on("click", "#chkGrupoTrabajo", function () {
  //if(estatus === 1){
  if ($(this).is(":checked")) {
    cmbGrupoTrabajo.enable();
    //$("#cmbGrupoTrabajo_habilitar").css("display","block");
    $("#editar_grupoTrabajo_habilitar").html('<a class="btn-table-custom--blue mt-auto p-2" href="#" id="btn_editarGrupoTrabajo"><i class="fas fa-plus-square"></i> Guardar</a>');

  } else {
    //$("#txtGrupoTrabajo").css("display","block");
    cmbGrupoTrabajo.disable();
    //$("#cmbGrupoTrabajo_habilitar").css("display","none");
    $("#editar_grupoTrabajo_habilitar").html('');
  }
  //}
});

$(document).on("click", "#btn_accept_production", function () {
  const swalWithBootstrapButtons = getHeadAlertSwan();
  id = $("#txtProduccionOrderId").val();
  sucursal = $("#txtSucursalId").val();

  swalWithBootstrapButtons.fire({
    title: "Aviso",
    html: "¿Desea aceptar la orden de producción?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
    cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
    reverseButtons: false,
  }).then((result) => {
    if (result.isConfirmed) {
        $("#loader").css("display","block");
        $("#loader").addClass("loader");
        $.ajax({
            url: "../../php/funciones_copy.php",
            method: "POST",
            data: {
            clase: "get_data",
            funcion: "get_stocksGeneral",
            value: id
            },
            datatype: "json",
            success: function (respuesta) {
            if (parseInt(respuesta) > 0) {
                $.ajax({
                url: "../../php/funciones_copy.php",
                method: "POST",
                data: {
                    clase: "get_data",
                    funcion: "get_stocksPorLote",
                    value: id,
                    sucursal: sucursal
                },
                datatype: "json",
                success: function (respuesta) {
                    if (parseInt(respuesta) == 1) {
                    $.ajax({
                        url: "../../php/funciones_copy.php",
                        method: "POST",
                        data: {
                        clase: "get_data",
                        funcion: "get_correctQuantitryPerLot",
                        value: id,

                        },
                        datatype: "json",
                        success: function (respuesta) {
                        if (parseInt(respuesta) > 0) {

                            $.ajax({
                            url: "../../php/funciones_copy.php",
                            method: "POST",
                            cache: false,
                            data: {
                                clase: "update_data",
                                funcion: "update_status_productionOrder",
                                value: 2,
                                id: id,
                                sucursal: $("#txtSucursalId").val()
                            },
                            datatype: "json",
                            success: function (respuesta) {

                                if (respuesta === "1") {
                                $("#txtEstatusOrdenProduccion").val("2");
                                estatus = "Aceptada";
                                $("#estatusOrdenProduccion").removeClass("yellow-dot");
                                $("#estatusOrdenProduccion").addClass("green-dot");
                                $("#estatusOrdenProduccion").html(estatus);
                                $(".btn-action").html('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a><a href="#" class="btn-table-custom btn-table-custom--red" id="btn_cancel_production"><i class="fas fa-ban"></i> Cancelar</a>');

                                $("#check_expected_date").html("");
                                $("#check_quantity").html("");
                                $("#check_responsable").html("");
                                $("#check_workgroup").html("");
                                $(".check_add_lot").html("");


                                Lobibox.notify("success", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 3000,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../../../img/timdesk/checkmark.svg",
                                    msg: "La orden de producción se aceptó con éxito",
                                    sound: false,
                                });
                                $.redirect('../../php/orden_produccion_pdf.php',
                                    {
                                        id:$("#txtProduccionOrderId").val()
                                    },
                                    "POST",
                                    "_blank"
                                );
                                setTimeout(function () {
                                    window.location.href = 'index.php';
                                }, 1000);
                                $(".loader").fadeOut("slow");
                                $("#loader").removeClass("loader");
                                } else {
                                Lobibox.notify("error", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 3000,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../../../img/timdesk/checkmark.svg",
                                    msg: "Hubo un error al aceptar la orden de producción",
                                    sound: false,
                                });
                                }
                            }, error: function (error) {
                                console.log(error);
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
                            img: "../../../../img/timdesk/checkmark.svg",
                            msg: "Uno o varios lotes de los productos no concuerdan con la cantidad a producir.",
                            sound: false,
                            });
                        }
                        },
                        error: function (error) {
                        console.log(error);
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
                        img: "../../../../img/timdesk/checkmark.svg",
                        msg: "Uno o varios lotes de los productos no tiene existencias.",
                        sound: false,
                    });
                    }
                },
                error: function (error) {
                    console.log(error);
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
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "Uno o varios productos no tiene existencias.",
                sound: false,
                });
            }

            },
            error: function (error) {
            console.log(error);
            }
        });
    }
  });
});

$(document).on("click", "#btn_cancel_production", function () {
  const swalWithBootstrapButtons = getHeadAlertSwan();
  swalWithBootstrapButtons.fire({
    title: "Aviso",
    html: "¿Desea cancelar la orden de producción?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
    cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
    reverseButtons: false,
  }).then((result) => {
    if (result.isConfirmed) {
      id = $("#txtProduccionOrderId").val();
      $.ajax({
        url: "../../php/funciones_copy.php",
        method: "POST",
        data: {
          clase: "update_data",
          funcion: "update_status_productionOrder",
          value: 5,
          id: id,
          sucursal: $("#txtSucursalId").val()
        },
        datatype: "json",
        success: function (respuesta) {
          if (respuesta === "1") {
            $("#txtEstatusOrdenProduccion").val("5");

            estatus = "Cancelada";
            $("#estatusOrdenProduccion").removeClass("yellow-dot");
            $("#estatusOrdenProduccion").addClass("red-dot");
            $("#estatusOrdenProduccion").html(estatus);

            if (parseInt($("#txtExistenciaSeguimiento").val()) > 0) {
              $(".btn-action").html('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a>');
            }


            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "La orden de producción se canceló con éxito",
              sound: false,
            });

            setTimeout(function () {
              window.location.href = 'index.php';
            }, 1000);
          } else {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Hubo un error al cancelar la orden de producción",
              sound: false,
            });
          }
        }, error: function (error) {
          console.log(error);
        }
      });
    }
  });
});

$(document).on("click", "#btn_close_production", function () {
  const swalWithBootstrapButtons = getHeadAlertSwan();
  swalWithBootstrapButtons.fire({
    title: "Aviso",
    html: "¿Desea cerrar la orden de producción?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
    cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
    reverseButtons: false,
  }).then((result) => {
    if (result.isConfirmed) {
      id = $("#txtProduccionOrderId").val();
      $.ajax({
        url: "../../php/funciones_copy.php",
        method: "POST",
        data: {
          clase: "update_data",
          funcion: "update_status_productionOrder",
          value: 7,
          id: id,
          sucursal: $("#txtSucursalId").val()
        },
        datatype: "json",
        success: function (respuesta) {
          if (respuesta === "1") {
            $("#txtEstatusOrdenProduccion").val("7");

            estatus = "Cerrada parcial";
            $("#estatusOrdenProduccion").removeClass("yellow-dot");
            $("#estatusOrdenProduccion").addClass("red-dot");
            $("#estatusOrdenProduccion").html(estatus);
            $(".btn-action").html('<a href="#" class="btn-table-custom btn-table-custom--blue" id="btn_tracing_production"><i class="fas fa-angle-double-right"></i> Seguimiento</a>');
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "La orden de producción se canceló con éxito",
              sound: false,
            });

            setTimeout(function () {
              window.location.href = 'index.php';
            }, 1000);
          } else {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Hubo un error al cancelar la orden de producción",
              sound: false,
            });
          }
        }, error: function (error) {
          console.log(error);
        }
      });
    }
  });
});

$(document).on("click", "#btn_tracing_production", function () {
  id = $("#txtProduccionOrderId").val();
  console.log(id);
  $().redirect("seguimiento_ordenProduccion.php",
    {
      id: id_orden
    },
    "POST"
  );
});

$(document).on("click", "#btn_editarFechaPrevista", function () {
  const swalWithBootstrapButtons = getHeadAlertSwan();
  id = $("#txtProduccionOrderId").val();
  value = $("#txtFechaPrevista").val();
  swalWithBootstrapButtons.fire({
    title: "Aviso",
    html: "¿Desea actualizar la fecha prevista de la orden de producción?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
    cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
    reverseButtons: false,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../php/funciones_copy.php",
        method: "POST",
        data: {
          clase: "update_data",
          funcion: "update_expectedDate",
          value: value,
          id: id
        },
        datatype: "json",
        success: function (respuesta) {
          if (respuesta === "1") {
            $("#txtFechaPrevista").prop("readonly", true);
            $("#editar_fechaPrevista_habilitar").html('');
            $("#chkFechaPrevista").prop("checked", false);
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "La fecha prevista de la orden de producción se actualizó con éxito",
              sound: false,
            });
          } else {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Hubo un error al actualizar la fecha de la orden de producción",
              sound: false,
            });
          }
        }, error: function (error) {
          console.log(error);
        }
      });
    }
  });
});

$(document).on("click", "#btn_editarCantidad", function () {
  const swalWithBootstrapButtons = getHeadAlertSwan();
  id = $("#txtProduccionOrderId").val();
  value = $("#txtCantidad").val();
  swalWithBootstrapButtons.fire({
    title: "Aviso",
    html: "¿Desea actualizar la cantidad a producir de la orden de producción?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
    cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
    reverseButtons: false,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../php/funciones_copy.php",
        method: "POST",
        data: {
          clase: "update_data",
          funcion: "update_quantity",
          value: value,
          id: id
        },
        datatype: "json",
        success: function (respuesta) {
          if (respuesta === "1") {
            $("#txtCantidad").prop("readonly", true);
            $("#editar_cantidad_habilitar").html('');
            $("#chkCantidad").prop("checked", false);
            $("#tblMateriales tbody").html("");
            loadTblMateriales(id);
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "La cantidad a producir de la orden de producción se actualizó con éxito",
              sound: false,
            });
          } else {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Hubo un error al actualizar la cantidad a producir de la orden de producción",
              sound: false,
            });
          }
        }, error: function (error) {
          console.log(error);
        }
      });
    }
  });
});

$(document).on("click", "#btn_editarResponsable", function () {
  const swalWithBootstrapButtons = getHeadAlertSwan();
  id = $("#txtProduccionOrderId").val();
  value = $("#cmbResponsable").val();

  swalWithBootstrapButtons.fire({
    title: "Aviso",
    html: "¿Desea actualizar la cantidad a producir de la orden de producción?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
    cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
    reverseButtons: false,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../../php/funciones_copy.php",
        method: "POST",
        data: {
          clase: "update_data",
          funcion: "update_responsable",
          value: value,
          id: id
        },
        datatype: "json",
        success: function (respuesta) {
          if (respuesta === "1") {
            $("#editar_responsable_habilitar").html('');
            $("#chkResponsable").prop("checked", false);
            $("#txtResponsable").css("display", "block");
            $("#cmbResponsable_habilitar").css("display", "none");
            $("#txtResponsable").val($("#cmbResponsable option:selected").text());

            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "El responsable de la orden de producción se actualizó con éxito",
              sound: false,
            });
          } else {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Hubo un error al actualizar la fecha de la orden de producción",
              sound: false,
            });
          }
        }, error: function (error) {
          console.log(error);
        }
      });
    }
  });
});

$(document).on("click", "#btn_editarGrupoTrabajo", function () {
  const swalWithBootstrapButtons = getHeadAlertSwan();
  id = $("#txtProduccionOrderId").val();

  value = JSON.stringify(workgroup);
  if (value.length > 0) {
    swalWithBootstrapButtons.fire({
      title: "Aviso",
      html: "¿Desea actualizar el/los grupo(s) de trabajo de la orden de producción?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "../../php/funciones_copy.php",
          method: "POST",
          data: {
            clase: "update_data",
            funcion: "update_workgroup",
            value: value,
            id: id
          },
          datatype: "json",
          success: function (respuesta) {
            if (respuesta === "1") {
              var val = JSON.parse(respuesta);
              $("#editar_grupoTrabajo_habilitar").html('');
              $("#chkGrupoTrabajo").prop("checked", false);
              //$("#txtResponsable").css("display","block");
              //$("#cmbResponsable_habilitar").css("display","none");
              //$("#txtResponsable").val($("#cmbResponsable").text());
              console.log("cmbGrupoTrabajo", $("#cmbGrupoTrabajo").val());

              document.querySelector("#cmbGrupoTrabajo").slim.disable();
              //document.querySelector("#cmbGrupoTrabajo").slim.set(getConcatJson(val));
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3500,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "El/Los grupo(s) de trabajo de la orden de producción se actualizó con éxito",
                sound: false,
              });
            } else {
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3500,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "Hubo un error al actualizar la fecha de la orden de producción",
                sound: false,
              });
            }
          }, error: function (error) {
            console.log(error);
          }
        });
      }
    });
  } else {

  }
});

$(document).on("click", ".add_lot", function () {

  material_id = $(this).data("id");
  txtRowIndex = $(this).closest("tr").index();
  $("#txtCantidadModal").val("");
  $("#tblMaterialsLot tbody").html("");
  sucursal_id = $("#txtSucursalId").val();
  $.ajax({
    url: "../../php/funciones_copy.php",
    method: "POST",
    data: {
      clase: "get_data",
      funcion: "get_compoundsData",
      value: material_id,
    },
    datatype: "json",
    success: function (response) {
      res = JSON.parse(response);
      $("#txtMaterialId").val(material_id);
      $("#txtRowIndex").val(txtRowIndex)
      data_send = '{"material_id":"' + material_id + '","sucursal_id":"' + sucursal_id + '"}'
      $("#add_lot_modal").modal("show");
      $("#txtProductModal").val(res[0].producto);
      loadCombo("lots", "#cmbLot", "", data_send, "Selecion un lote...");

      if (lotes_existencia.length > 0) {

        lotes_existencia.forEach(function (i) {
          if (parseInt(i.id) === parseInt(material_id)) {
            if (i.lote !== null && i.lote !== "null" && i.lote !== "" && (i.cantidad !== null && i.cantidad !== "null" && i.cantidad !== NaN)) {
              $("#tblMaterialsLot tbody").
                append(
                  '<tr>' +
                  '<td>' + i.lote + '</td>' +
                  '<td>' + i.cantidad + '</td>' +
                  '<td><a class="btn-table-custom--blue delete_lot" href="#"><i class="fas fa-trash-alt"></i></a></td>' +
                  '</tr>'
                );
            }
          }

        });

      }
    },
    error: function (error) {
      console.log(error);
    }
  });
});

countClic = 0;

$(document).on("click", "#add_lote", function () {
  html = "";
  aux_values_sum = [];
  txtlote = $("#cmbLot option:selected").text();
  lote = $("#cmbLot option:selected").val();
  aux = lote.split(",");
  rowIndex = $("#txtRowIndex").val();
  cantidad_consumir = parseFloat($("#tblMateriales tbody tr:eq(" + rowIndex + ")").find("td:eq(4)").text());
  cantidad = parseFloat($("#txtCantidadModal").val());
  
  ban = true;
  cantidad_producir = parseFloat($("#txtCantidad").val());
  material_id = $("#txtMaterialId").val();
  countClic++;
  $("#txtNoInsert").val(countClic);
  cantidad_total = 0;
  cantidad_total_x_lote=0;
  sucursal_id = $("#cmbSucursal").val();
  if ($("#tblMaterialsLot tbody tr").length > 0) {
    for (var i = 0; i < $("#tblMaterialsLot tbody tr").length; i++) {
      //console.log(parseFloat($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(1)").text()));
      if (parseFloat($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(1)").text()) !== 0) {
        cantidad_total += parseFloat($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(1)").text());
      } else {
        cantidad_total = 0;
      }
      if($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(0)").text() == aux[1]){
        cantidad_total_x_lote += parseFloat($("#tblMaterialsLot tbody tr:eq(" + i + ")").find("td:eq(1)").text());
      } else {
        cantidad_total_x_lote = 0;
      }
    };

    if ((cantidad_total + cantidad) > cantidad_consumir) {
      ban = false;
    }
  } else {
    if (cantidad > cantidad_consumir) {
      ban = false;
    }
  }

  if (ban) {

    // if(lotes_existencia.length > 0){

    if (parseInt(aux[2]) >= parseFloat(cantidad + cantidad_total_x_lote)) {
      //lotes_existencia.forEach( index => {
      const lote_void = lotes_existencia.find(i => (i.lote === null || i.lote === 'null' || i.lote === '') && i.id === aux[0]);
      const data = lotes_existencia.find(i => i.lote === aux[1] && parseInt(i.id) === parseInt(aux[0]));
      
      if (!data) {
        if (!lote_void) {
          lotes_existencia.push
            (
              {
                id: aux[0],
                lote: aux[1],
                cantidad: parseFloat(cantidad),
                a_consumir: cantidad_consumir
              }
            );
        } else {
          lote_void.lote = aux[1];
          lote_void.cantidad = parseFloat(cantidad);

        }

      } else {
        data.cantidad = parseFloat(cantidad) + parseFloat(data.cantidad);
      }
      //});
      // } else {
      //   lotes_existencia.
      //     push(
      //       {
      //         id:aux[0],
      //         lote:aux[1],
      //         cantidad:cantidad,
      //         a_consumir: data1.a_consumir
      //       }
      //     );
      // }

      lotes_existencia.forEach(function (i) {
        if (parseInt(i.id) === parseInt(material_id)) {
          html += '<tr>' +
            '<td>' + i.lote + '</td>' +
            '<td>' + i.cantidad + '</td>' +
            '<td><a class="btn-table-custom--blue delete_lot" href="#"><i class="fas fa-trash-alt"></i></a></td>' +
            '</tr>';
        }
      })

      $("#tblMaterialsLot tbody").html(html);

      document.querySelector("#cmbLot").slim.set("");
      $("#txtCantidadModal").val("");

    } else {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3500,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/checkmark.svg",
        msg: "La cantidad es mayor a la cantidad en existencia",
        sound: false,
      });
    }

  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3500,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "La cantidad es mayor a la cantidad a producir",
      sound: false,
    });
  }
  
});

$(document).on("click", "#btnAddLot", function () {

  cantidad = $("#txtCantidadModal").val();
  var rowIndex = parseInt($("#txtRowIndex").val());
  var cadena = "";
  var lotes = "";
  material_id = $("#txtMaterialId").val();

  lotes_existencia.forEach(function (i) {
    if (parseInt(i.id) === parseInt(material_id)) {
      cadena += "Lote: " + i.lote + " - Cantidad: " + i.cantidad + "<br>";
      lotes += '{"id":"' + i.id + '",' +
        '"lote":"' + i.lote + '",' +
        '"cantidad":"' + i.cantidad + '",' +
        '"a_consumir":"' + i.quantity + '"' +
        '},'
    }

  });

  lotes = lotes.substring(0, lotes.length - 1);

  lotes = JSON.stringify(lotes_existencia);


  countClic = 0;
  orden_produccion_id = $("#txtProduccionOrderId").val();

  value = '{' +
    '"orden_produccion_id":"' + orden_produccion_id + '",' +
    '"detalle_lotes":' + lotes + '' +
    '}';
  console.log(value);
  if ($("#tblMaterialsLot tbody tr").length > 0) {
    $.ajax({
      url: "../../php/funciones_copy.php",
      method: "POST",
      data: {
        clase: "update_data",
        funcion: "update_lots",
        value: value
      },
      datatype: "json",
      success: function (respuesta) {
        $("#add_lot_modal").modal("hide");
        $("#tblMaterialsLot tbody").html("");

        $("#tblMateriales tbody tr:eq(" + rowIndex + ")").find("td:eq(6)").html(cadena);
      },
      error: function (error) {
        console.log(error);
      }
    })
  } else {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3500,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "No se han agregado lotes ni cantidades",
      sound: false,
    });
  }

});

$(document).on("click", "#btnCancelarActualizacion", function () {
  for (let i = 0; i < countClic; i++) {
    lotes_existencia.pop();
  }
  $("#txtCantidadModal").val("");
});

$(document).on("click", "#tblMaterialsLot .delete_lot", function () {
  $(this).closest("tr").remove();
  val1 = $(this).closest("tr").find("td:eq(0)").text();
  val2 = $("#txtMaterialId").val();
  
  removeItemFromArrText(lotes_existencia, val1, val2);
});

function loadCombo(funcion, input, data, value, texto) {

  $.ajax({
    url: "../../php/funciones_copy.php",
    method: "POST",
    data: {
      clase: "get_data",
      funcion: "get_" + funcion,
      value: value
    },
    datatype: "json",
    success: function (respuesta) {
      var res = JSON.parse(respuesta);
      html = "<option data-placeholder='true'></option>";
      if (res.length > 0) {
        $.each(res, function (i) {
          if (res[i].id === parseInt(data)) {

            html += "<option value='" + res[i].id + "' selected>" + res[i].texto + "</option>";
          } else {

            html += "<option value='" + res[i].id + "'>" + res[i].texto + "</option>";
          }
        });
      } else {
        html += "<option value='0'>No hay registros.</option>";
      }

      $(input).html(html);
    },
    error: function (error) {
      console.log(error);
    }
  });

}

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
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

function getHeadAlertSwan() {
  return Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });
}

function addWorkGroup(value) {
  $.ajax({
    url: "../../php/funciones_copy.php",
    method: "POST",
    data: {
      clase: "save_data",
      funcion: "save_workgroup",
      value: value,
    },
    datatype: "json",
    success: function (response) {

    },
    error: function (error) {
      console.log(error);
    }
  });
}

$(document).on("change", ".workgroup-select", function (e) {
  var val = $(this).val();
  for (let i = 0; i < val.length; i++) {
    if (!workgroup.find(e => e.id === val[i])) {
      workgroup.push(
        {
          id: val[i],
        }
      );
    }
  }
});

$(document).on("mousedown", ".workgroup-select .ss-value-delete", function (e) {
  val1 = e.target.previousElementSibling.innerHTML
  value = $(".workgroup-select option").filter(function () {
    return $(this).text() === val1;
  }).first().attr("value");
  removeItemFromArr(workgroup, value);
});

function removeItemFromArr(arr, item) {
  var i = arr.findIndex(e => e.id === item);
  if (i !== -1) {
    arr.splice(i, 1);
  }
}
function removeItemFromArrText(arr, item, item2) {
  var i = arr.findIndex(e => e.lote === item && e.id === item2);
  if (i === -1) return;
  arr.splice(i, 1);
}

function getConcatJson(text) {
  var val_multi = [];
  if (text !== null) {
    text.forEach(function (i) {
      val_multi.push(parseInt(i.id)); removeItemFromArr(workgroup, value);
    });
  }
  return val_multi;
}

function loadTblMateriales(value) {
  $.ajax({
    method: "POST",
    url: "../../php/funciones_copy.php",
    data: {
      clase: "get_data",
      funcion: "get_detailProducts",
      value: value
    },
    datatype: "json",
    success: function (respuesta) {
      aux = [];
      aux1 = [];
      lotes_existencia = [];
      res = JSON.parse(respuesta);
      html = "";

      res[0].data.forEach(function (i) {

        btn_add_lot = parseInt(i.estatus) === 1 ? '<a href="#" class="btn-table-custom--blue add_lot" data-id="' + i.id + '"><i class="fas fa-plus-square"></i> Editar lote</a>' : "";
        html += '<tr>' +
          '<td style="display:none">' + i.id + '</td>' +
          '<td>' + i.clave + '</td>' +
          '<td>' + i.nombre + '</td>' +
          '<td>' + i.unidad_medida + '</td>' +
          '<td>' + i.a_consumir + '</td>' +
          '<td>' + i.existencia + '</td>' +
          '<td>' + i.lote + '</td>' +
          '<td class="check_add_lot">' + btn_add_lot + '</td>' +
          '</tr>';
      });

      res[0].lotes.forEach(function (i) {
        lotes_existencia.push({
          id: i.id,
          lote: i.lote,
          cantidad: parseFloat(i.cantidad),
          texto: "Lote: " + i.lote + " - Cantidad: " + i.cantidad,
          a_consumir: i.a_consumir
        });
      });

      $("#tblMateriales tbody").html(html);

    }, error: function (error) {
      console.log(error);
    }
  });
}
