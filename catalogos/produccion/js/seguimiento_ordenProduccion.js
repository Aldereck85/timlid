$(document).ready(function(){
  let idOrdenProduccion = $("#txtProduccionOrderId").val();
  
  cmbGrupoTrabajo = new SlimSelect({
    select: '#cmbGrupoTrabajo',
    placeholder: 'Seleccione un grupo de trabajo...',
  });

  cmbLote = new SlimSelect({
    select: '#cmbLote',
    placeholder: 'Seleccione un lote...',
  });

  loadCombo("grupoTrabajoOrdenProduccion",'#cmbGrupoTrabajo',"",idOrdenProduccion,"Seleccione un grupo de trabajo...");
  loadCombo("lotes",'#cmbLote',"",idOrdenProduccion,"Seleccione un lote...");

  $.ajax({
    url: "../../php/funciones_copy.php",
    method:"POST",
    data: {
      clase: "get_data",
      funcion: "get_detailsDataproductionOrder",
      value: idOrdenProduccion
    },
    datatype: "json",
    success: function(respuesta){
      res = JSON.parse(respuesta);
      folio = ('0000' + res.folio).slice(-5);
      $("#noOrdenProduccion").html("OP" + folio);
      $("#txtClaveProducto").val(res.clave);

      $("#txtSucursal").val(res.sucursal);
      $("#txtSucursalId").val(res.sucursal_id)
      $("#txtFechaCreacion").val(res.fecha_creacion);
      $("#txtFechaPrevista").val(res.fecha_prevista);
      $("#txtProducto").val(res.clave + " - " + res.producto);
      $("#txtProductoId").val(res.producto_id);
      $("#txtCantidad").val(res.cantidad);
      $("#txtResponsable").val(res.responsable);
      $("#txaNotas").val(res.notas);

      switch(parseInt(res.estatus)){
        case 1:
          estatus = "Pendiente";
          $("#estatusOrdenProduccion").addClass("left-dot yellow-dot");
        break;
        case 2:
          estatus = "Aceptada";
          $("#estatusOrdenProduccion").addClass("left-dot green-dot");
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--red" id="btn_cancel_production"><i class="fas fa-ban"></i> Cancelar</a>');
        break;
        case 3:
          estatus = "En proceso";
          $("#estatusOrdenProduccion").addClass("left-dot green-dot");
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--red" id="btn_close_production"><i class="fas fa-ban"></i> Cerrar</a>');
        break;
        case 4:
          estatus = "Terminada";
          $("#estatusOrdenProduccion").addClass("left-dot turquoise-dot");
          $(".btn-action").append('<a href="#" class="btn-table-custom btn-table-custom--turquoise" id="btn_download_pdf"><i class="far fa-file-pdf"></i> PDF</a>');
        break;
        case 5:
          estatus = "Cancelada";
          $("#estatusOrdenProduccion").addClass("left-dot red-dot");
        break;
        case 6:
          estatus = "En proceso atrasada";
          $("#estatusOrdenProduccion").addClass("left-dot yellow-dot");
          break;
        case 7:
          estatus = "Cerrada parcial";
          $("#estatusOrdenProduccion").addClass("left-dot red-dot");
          break;
        }
        
        $("#estatusOrdenProduccion").html(estatus);

        $.ajax({
          url: "../../php/funciones_copy.php",
          method:"POST",
          data: {
            clase: "get_data",
            funcion: "get_dataProductionOrderTracking",
            value: idOrdenProduccion
          },
          datatype: "json",
          success: function(respuesta){
            res = JSON.parse(respuesta);
            cantidad_terminada = res[0].cantidad_terminada !== "" && res[0].cantidad_terminada !== null ? res[0].cantidad_terminada : 0;
            cantidad_faltante = res[0].cantidad_faltante !== "" && res[0].cantidad_faltante !== null ? res[0].cantidad_faltante : parseInt($("#txtCantidad").val()) - parseInt(cantidad_terminada);
            cantidad_producir = parseInt($("#txtCantidad").val());
            $("#txtCantidadFabricada").val(cantidad_terminada);
            $("#txtCantidadPendiente").val(cantidad_faltante);
      
            if(cantidad_producir <= cantidad_terminada){
              $("#guardar_seguimientoOrdenProduccion").removeClass("enabled_link");
              $("#guardar_seguimientoOrdenProduccion").addClass("disabled_link");
              document.querySelector("#cmbGrupoTrabajo").slim.disable();
              $("#txtFechaFabricacion").prop("disabled",true);
              $("#txtCantidadTerminada").prop("disabled",true);
              $("#txtLote").prop("disabled",true);
              document.querySelector("#cmbLote").slim.disable();
              $('#chkNuevoLote').prop("disabled",true);
            }
          },
          error: function(error){
            console.log(error);
          }
        });
    },
    error: function(error){
      console.log(error);
    }
  });


  tblManufacturingHistory = $("#tblManufacturingHistory").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 10,
    responsive: true,
    lengthChange: false,
    ajax: {
      method: "POST",
      url: "../../php/funciones_copy.php",
      data: {
        clase: "get_data",
        funcion: "get_manufacturingHistory",
        value: idOrdenProduccion
      },
    },
    columns: [
      { data: "fecha_captura" },
      { data: "grupo_trabajo" },
      { data: "fecha_termino" },
      { data: "cantidad_termina" },
      { data: "lote" },
      { data: "usuario_registro" },
      { data: "function" }
    ]
  });
});



$(document).on("click","#btn_download_pdf",()=>{
    console.log("hola");
    let idOrdenProduccion = $("#txtProduccionOrderId").val();

    $.redirect('../../php/historico_seguimiento_op.php',
        {
            id:idOrdenProduccion
        },
        "POST",
        "_blank"
    );
});

$(document).on("click","#chkNuevoLote",function(){

  if($(this).is(":checked")){
    if($("#txtLote").attr("disabled",true)){
      $("#txtLote").prop("disabled",false);
    }

    $("#new-lote-text").css("display","block");
    $("#new-lote-combo").css("display","none");
    document.querySelector("#cmbLote").slim.disable();
    $("#txtLote").prop("required",true);
    $("#cmbLote").prop("required",false);

    if($("#txtLote").hasClass("is-invalid")){
      $("#invalid-txtLote").css("display", "none");
      $("#txtLote").removeClass("is-invalid");
    }

  } else {
    if($("#cmbLote").attr("disabled",true)){
      document.querySelector("#cmbLote").slim.enable();
    }
    $("#new-lote-text").css("display","none");
    $("#new-lote-combo").css("display","block");
    $("#txtLote").prop("disabled",true);
    $("#txtLote").prop("required",false);
    $("#cmbLote").prop("required",true);

    if($("#cmbLote").hasClass("is-invalid")){
      $("#invalid-cmbLote").css("display", "none");
      $("#cmbLote").removeClass("is-invalid");
    }
  }
});

$(document).on("click","#guardar_seguimientoOrdenProduccion",function(){
    
    const swalWithBootstrapButtons = getHeadAlertSwan();
    let idOrdenProduccion = $("#txtProduccionOrderId").val();
    if ($("#data-productionOrderTracking")[0].checkValidity()) {
        var badWorkgroup =
        $("#invalid-workgroup").css("display") === "block" ? false : true;
        var badManufacturingDate =
        $("#invalid-manufacturingDate").css("display") === "block" ? false : true;
        var badFinishedQuantity =
        $("#invalid-finishedQuantity").css("display") === "block" ? false : true;
        var badLote = null;
        if($("#new-lote-text").is(":visible")){
        badLote =
        $("#invalid-txtLote").css("display") === "block" ? false : true;
        } else if($("#new-lote-combo").is(":visible")){
        badLote =
        $("#invalid-cmbLote").css("display") === "block" ? false : true;
    }

    if(badWorkgroup &&
        badManufacturingDate &&
        badFinishedQuantity &&
        badLote) {
            swalWithBootstrapButtons.fire({
            title: "Aviso",
            html: "Se va a realizar el ingreso del producto al inventario según la cantidad seleccionada.<br><br>¿Desea continuar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#loader").css("display","block");
                    $("#loader").addClass("loader");
                    grupo_trabajo =$("#cmbGrupoTrabajo").val();
                    fecha_fabricacion = $("#txtFechaFabricacion").val();
                    cantidad_terminada = $("#txtCantidadTerminada").val();
                    sucursal = $("#txtSucursalId").val();
                    producto = $("#txtProductoId").val();
                    clave_producto =$("#txtClaveProducto").val();

                    lote = "";

                    if($("#cmbLote").is(':disabled')){
                        lote = $("#txtLote").val();
                    } else {
                        lote = $("#cmbLote").val();
                    }

                    value = '{'+
                            '"orden_produccion_id":"' + idOrdenProduccion + '",' +
                            '"grupo_trabajo":"' + grupo_trabajo + '",' +
                            '"fecha_fabricacion":"' + fecha_fabricacion + '",' +
                            '"cantidad_terminada":"' + cantidad_terminada + '",' +
                            '"lote":"' + lote + '",'+
                            '"sucursal_id":"' + sucursal + '",' +
                            '"clave_producto": "' + clave_producto + '",' +
                            '"producto_id":"' + producto + '"'+
                            '}';

                    $.ajax({
                        url: "../../php/funciones_copy.php",
                        method:"POST",
                        data: {
                            clase: "save_data",
                            funcion: "save_productionOrderTracking",
                            value: value
                        },
                        datatype: "json",
                        success: function(respuesta){
                    
                        $.ajax({
                            url: "../../php/funciones_copy.php",
                            method:"POST",
                            data: {
                                clase: "get_data",
                                funcion: "get_dataProductionOrderTracking",
                                value: idOrdenProduccion
                            },
                            datatype: "json",
                            success: function(respuesta){
                                res = JSON.parse(respuesta);
                                cantidad_terminada = res[0].cantidad_terminada !== "" && res[0].cantidad_terminada !== null ? parseInt(res[0].cantidad_terminada) : 0;
                                cantidad_faltante = res[0].cantidad_faltante !== "" && res[0].cantidad_faltante !== null ? parseInt(res[0].cantidad_faltante) : parseInt($("#txtCantidad").val()) - cantidad_terminada;
                                cantidad_producir = parseInt($("#txtCantidad").val());
                                $("#txtCantidadFabricada").val(cantidad_terminada);
                                $("#txtCantidadPendiente").val(cantidad_faltante);
                                sucursal = $("#txtSucursalId").val();
                
                                if(cantidad_terminada >= cantidad_producir){
                                    $("#guardar_seguimientoOrdenProduccion").removeClass("enabled_link");
                                    $("#guardar_seguimientoOrdenProduccion").addClass("disabled_link");
                                    document.querySelector("#cmbGrupoTrabajo").slim.disable();
                                    $("#txtFechaFabricacion").prop("disabled",true);
                                    $("#txtCantidadTerminada").prop("disabled",true);
                                    $("#txtLote").prop("disabled",true);
                                    document.querySelector("#cmbLote").slim.disable();
                                    $('#chkNuevoLote').prop("disabled",true);
                                }

                                if(cantidad_faltante === 0){
                                    $.post(
                                        "../../php/funciones_copy.php",
                                        {
                                        clase: "update_data",
                                        funcion: "update_status_productionOrder",
                                        value: 4,
                                        id: idOrdenProduccion,
                                        sucursal: sucursal
                                        },
                                        function(response){
                                        estatus = "Terminada";
                                        $("#estatusOrdenProduccion").html(estatus);
                                        }
                                    );
                                } else {
                                    estatus = "En proceso";
                                    $("#estatusOrdenProduccion").html(estatus);
                                    }
                            },
                                error: function(error){
                                console.log(error);
                            }
                        });

                    $.redirect('../../php/historico_seguimiento_op.php',
                        {
                            id:idOrdenProduccion
                        },
                        "POST",
                        "_blank"
                    );

                    $("#tblManufacturingHistory").DataTable().ajax.reload();
                    document.querySelector("#cmbGrupoTrabajo").slim.set("");
                    $("#txtFechaFabricacion").val("");
                    $("#txtCantidadTerminada").val("");

                    if($('#chkNuevoLote').is(":checked")){
                        $("#txtLote").val("");
                        $("#txtLote").prop("disabled",true);
                        $("#new-lote-text").css("display","none");
                        $("#new-lote-combo").css("display","block");
                        document.querySelector("#cmbLote").slim.enable();
                        loadCombo("lotes",'#cmbLote',"",idOrdenProduccion,"Seleccione un lote...");
                        $('#chkNuevoLote').prop("checked",false);
                    } else {
                        document.querySelector("#cmbLote").slim.set("");
                    }
                    $(".loader").fadeOut("slow");
                    $("#loader").removeClass("loader");
                },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
        });
      }
  } else {

    if (!$("#cmbGrupoTrabajo").val()) {
      $("#invalid-workgroup").css("display", "block");
      $("#cmbGrupoTrabajo").addClass("is-invalid");
    }

    if (!$("#txtFechaFabricacion").val()) {
      $("#invalid-manufacturingDate").css("display", "block");
      $("#txtFechaFabricacion").addClass("is-invalid");
    }

    if (!$("#txtCantidadTerminada").val()) {
      $("#invalid-finishedQuantity").css("display", "block");
      $("#txtCantidadTerminada").addClass("is-invalid");
    }

   
    if($("#new-lote-text").is(":visible")){
      if (!$("#txtLote").val()) {
        $("#invalid-txtLote").css("display", "block");
        $("#txtLote").addClass("is-invalid");
      }
    } else if($("#new-lote-combo").is(":visible")){
      if (!$("#cmbLote").val()) {
        $("#invalid-cmbLote").css("display", "block");
        $("#cmbLote").addClass("is-invalid");
      }
    }

   

    
  }
      
});

$(document).on("change","#cmbGrupoTrabajo",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-workgroup").css("display", "none");
    $("#cmbGrupoTrabajo").removeClass("is-invalid");
  }
});

$(document).on("change","#txtFechaFabricacion",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-manufacturingDate").css("display", "none");
    $("#txtFechaFabricacion").removeClass("is-invalid");
  }
});

$(document).on("change","#txtCantidadTerminada",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-finishedQuantity").css("display", "none");
    $("#txtCantidadTerminada").removeClass("is-invalid");
  }
});

$(document).on("change","#cmbLote",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-cmbLote").css("display", "none");
    $("#cmbLote").removeClass("is-invalid");
  }
});

$(document).on("change","#txtLote",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-txtLote").css("display", "none");
    $("#txtLote").removeClass("is-invalid");
  }
});


$(document).on("keyup","#txtCantidadTerminada",function(){
  //let idOrdenProduccion = $("#txtProduccionOrderId").val();
  let cantidad_terminada = parseInt($(this).val());
  let cantidad_pendiente = parseInt($("#txtCantidadPendiente").val());
  
  if(cantidad_pendiente < cantidad_terminada){
    if($("#guardar_seguimientoOrdenProduccion").hasClass("enabled_link")){
      $("#guardar_seguimientoOrdenProduccion").removeClass("enabled_link");
      $("#guardar_seguimientoOrdenProduccion").addClass("disabled_link");
    }
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "Valor de cantidad terminada es mayor al valor de la cantidad a producir",
      sound:false,
    });
  } else {
    if($("#guardar_seguimientoOrdenProduccion").hasClass("disabled_link")){
      $("#guardar_seguimientoOrdenProduccion").removeClass("disabled_link");
      $("#guardar_seguimientoOrdenProduccion").addClass("enabled_link");
    }
  }

});

$(document).on("click","#btn_cancel_production",function(){
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
        method:"POST",
        data: {
          clase: "update_data",
          funcion: "update_status_productionOrder",
          value: 5,
          id: id,
          sucursal: $("#txtSucursalId").val()
        },
        datatype: "json",
        success: function(respuesta){
          if(respuesta === "1"){
            $("#txtEstatusOrdenProduccion").val("5");
            
            estatus = "Cancelada";
            $("#estatusOrdenProduccion").removeClass("yellow-dot");
            $("#estatusOrdenProduccion").addClass("red-dot");
            $("#estatusOrdenProduccion").html(estatus);

            
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "La orden de producción se canceló con éxito",
              sound:false,
            });

            setTimeout(function() {
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
              sound:false,
            });
          }
        },error: function(error){
          console.log(error);
        }
      });
    }
  });
});

$(document).on("click","#btn_close_production",function(){
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
        method:"POST",
        data: {
          clase: "update_data",
          funcion: "update_status_productionOrder",
          value: 7,
          id: id,
          sucursal: $("#txtSucursalId").val()
        },
        datatype: "json",
        success: function(respuesta){
          if(respuesta === "1"){
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
              sound:false,
            });

            setTimeout(function() {
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
              sound:false,
            });
          }
        },error: function(error){
          console.log(error);
        }
      });
    }
  });
});

$(document).on("click","#btn_download_pdf1",(e)=>{
    id = e.target.dataset.id;
    let idOrdenProduccion = $("#txtProduccionOrderId").val();
    $.redirect('../../php/historico_seguimiento_op.php',
        {
            id:idOrdenProduccion,
            id1:id
        },
        "POST",
        "_blank"
    );
})

function loadCombo(funcion,input,data,value,texto){

  $.ajax({
    url: "../../php/funciones_copy.php",
    method:"POST",
    data: {
      clase: "get_data",
      funcion: "get_"+funcion,
      value: value
    },
    datatype: "json",
    success: function(respuesta){
      var res = JSON.parse(respuesta);
      html = "<option data-placeholder='true'></option>";
      if(res.length > 0){
        $.each(res,function(i){
          if(res[i].id === parseInt(data)){
            
            html += "<option value='"+res[i].id+"' selected>"+res[i].texto+"</option>";
          } else {
            
            html += "<option value='"+res[i].id+"'>"+res[i].texto+"</option>";
          }
        });
      } else {
        html += "<option value='0'>No hay registros.</option>";
      }
      
      $(input).html(html);
    },
    error: function(error){
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

function getHeadAlertSwan(){
  return Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });
}
