var idRequisicion;
var estatusRequisicion;

function obtenerSeguimiento(){
    $().redirect('seguimiento_Requisicion.php', {
        'idRequisicion': idRequisicion,
    });
}

function descargarRequisición(){
    if(estatusRequisicion !== 0){
        $().redirect('functions/descargar_RequisicionCompra.php', {
            'idRequisicion': idRequisicion,
        });
    }else{
        Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: '¡No se puede descargar una requisición cancelada!',
            sound: '../../../../../sounds/sound4'
          });
    }
}

function obtenerEditar() {  
    $.ajax({
      url: "php/functions.php",
      data: {
        clase: "validate_data",
        funcion: "validar_estadoRequisicionCompra",
        data: idRequisicion,
      },
      dataType: "json",
      success: function (data) {

        if (data.estatus === 1) {
            $().redirect('editar_Requisicion.php', {
                'idRequisicion': idRequisicion,
            });
        } else {
            Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "No se pudo acceder, estatus cambió",
                sound: '../../../../../sounds/sound4'
            });
        }
      },
    });
}

function cancelaRequisicion(){
    //cambia estatus
    if(estatusRequisicion === 1){
        $.ajax({
            url: "php/functions.php",
            data: { 
                clase: "delete_data", 
                funcion: "cancela_Requisicion",
                data2: idRequisicion
            },
            dataType: "json",
            success: function () {
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../../../img/timdesk/checkmark.svg",
                    msg: "¡Requisición cancelada con exito!",
                    sound: '../../../../../sounds/sound2'
                  });
                  botones();
            },
            error: function (error) {
            console.log(error);
            Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/notificacion_error.svg",
                msg: '¡Algo salió mal al cancelar requisición!',
                sound: '../../../../../sounds/sound4'
            });
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
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: '¡Solo se pueden cancelar requisiciones pendientes!',
            sound: '../../../../../sounds/sound4'
          });
    }
}

function cerrarRequisicion(){
    //cambia estatus
    if(estatusRequisicion === 1){
        $.ajax({
            url: "php/functions.php",
            data: { 
                clase: "delete_data", 
                funcion: "cerrar_Requisicion",
                data2: idRequisicion
            },
            dataType: "json",
            success: function () {
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../../../img/timdesk/checkmark.svg",
                    msg: "¡Requisición cerrada con exito!",
                    sound: '../../../../../sounds/sound2'
                });
                botones();
            },
            error: function (error) {
            console.log(error);
            Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/notificacion_error.svg",
                msg: '¡Algo salió mal al cerrar requisición!',
                sound: '../../../../../sounds/sound4'
            });
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
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: '¡La requisición no se puede cerrar!',
            sound: '../../../../../sounds/sound4'
          });
    }  
}

//funcion para cargar los botones superiores y cabecera 
function botones(){    
    //se recuperan datos de la cabecera
    $.ajax({
        url: "php/functions.php",
        data: { 
            clase: "get_data", 
            funcion: "get_data_CabeceraRequisicion",
            data: idRequisicion,
        },
        dataType: "json",
        success: function (respuesta) {
            if($.isEmptyObject(respuesta)){
                $("#alert_NotFound").modal("show");
            }
            
            $("#txtFolio").text(respuesta[0].folio);
            $("#txtFechaEmision").text(respuesta[0].fecha_registro);
            $("#txtFechaEntrega").text(respuesta[0].fecha_estimada_entrega);
            $("#txtSucursal").text(respuesta[0].sucursal);
            $("#txtArea").text(respuesta[0].area);
            $("#txtEmpleado").text(respuesta[0].NombreEmpleado);
            $("#txtComprador").text(respuesta[0].NombreComprador);
            if(respuesta[0].NombreComercial != "" && respuesta[0].NombreComercial != null){
                $("#txtProveedor").text(respuesta[0].NombreComercial);
            }
            $("#NotasComprador").val(respuesta[0].notas_comprador);
            $("#NotasInternas").val(respuesta[0].notas_internas);

            estatusRequisicion=respuesta[0].estatus;
            var estado="";
            switch(respuesta[0].estatus){
                case 1:
                    estado = '<span class="left-dot turquoise-dot">Pendiente</span>';
                    break;
                case 2:
                    estado = '<span class="left-dot yellow-dot">Parcialmente colocada</span>';
                    break;
                case 3:
                    estado = '<span class="left-dot green-dot">Colocada completa</span>';
                    break;
                case 4:
                    estado = '<span class="left-dot red-dot">Cerrada</span>';
                    break;
                case 0:
                    estado = '<span class="left-dot red-dot">Cancelada</span>';
                    break;
            }

            html = `<h5>Estatus: </h5>`;
            $("#divEstatus").html(html);
            html = `<h5>` + estado + `</h5>`;
            $("#divEstatusSpan").html(html);

            if(respuesta[0].estatus != 0){
                html = `<span data-toggle="modal" class="btn-table-custom btn-table-custom--turquoise" name="btnDescargarRC" onclick="descargarRequisición();"><i class="fas fa-download"></i> Descargar</span>`;
                $("#divBtnDescargar").html(html);

                //si el estatus es pendiente, se puede editar.
                if(respuesta[0].estatus === 1 && _permissions.edit===1){
                    html = `<button class="btn-table-custom btn-table-custom--blue-light" name="btnEditarRC" onclick="obtenerEditar();"><i class="fas fa-edit"></i> Editar</button> `;
                    $("#divBtnEditar").html(html);
                }else{
                    $("#divBtnEditar").html('');
                }

                //valida si tiene permiso para editar, para el botón de cerrar
                if( _permissions.edit === 1  && respuesta[0].estatus === 2){
                    html = `<button data-toggle="modal" data-target="#modal_cerrar_Requisicion" class="btn-table-custom btn-table-custom--red" name="btnCerrarRC"><i class="fas fa-times"></i> Cerrar</button>`;
                    $("#divBtnCerrar").html(html);
                }else{
                    $("#divBtnCerrar").html('');
                }

                //Valida si tiene permiso para eliminar.
                if(_permissions.delete === 1 && respuesta[0].estatus == 1){
                    html = `<button data-toggle="modal" data-target="#modal_cancelar_Requisicion" class="btn-table-custom btn-table-custom--red" name="btnCancelarRC"><i class="fas fa-trash-alt"></i> Cancelar</button>`;
                    $("#divBtnCancelar").html(html);
                }else{
                    $("#divBtnCancelar").html('');
                }
            }else{
                html='';
                $("#divBtnDescargar").html(html);
                $("#divBtnEditar").html(html);
                $("#divBtnCerrar").html(html);
                $("#divBtnCancelar").html(html);
            }            

            //valida si es comprador
            $.ajax({
                url: "php/functions.php",
                data: { 
                    clase: "validate_data", 
                    funcion: "validate_isComprador_Requisicion",
                },
                dataType: "json",
                success: function (res) {
                    //si el estatus es 1, se puede acceder al seguimiento.
                    if(res.seguimiento == 1 && (respuesta[0].estatus === 1  || respuesta[0].estatus === 2)){
                        html = `<button class="btn-table-custom btn-table-custom--blue" id="btnSeguimientoRC" onclick="obtenerSeguimiento();"><i class="fas fa-angle-double-right"></i> Seguimiento</button> `;
                        $("#divBtnSeguimiento").html(html);
                    }else{
                        $("#divBtnSeguimiento").html('&nbsp;&nbsp;');
                    }
                },
                error: function (error) {
                console.log(error);
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../../../img/timdesk/notificacion_error.svg",
                    msg: '¡Algo salió mal al validar datos!',
                    sound: '../../../../../sounds/sound4'
                });
                },
            });
        },
        error: function (error) {
        console.log(error);
        Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: '¡Algo salió mal!',
            sound: '../../../../../sounds/sound4'
        });
        },
    });    
}

//funcion para recuperar las ordenes de compra generadas a partir de la requisición
function getOrdenesGeneradas(){
    $.ajax({
        url: "php/functions.php",
        data: { 
            clase: "get_data", 
            funcion: "get_ordenes_generadas",
            data: idRequisicion,
        },
        dataType: "json",
        success: function (res) {
            if(res.length > 0){
                html='<div class="btn-group"><button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ordenes de compra</button><div class="dropdown-menu" id="items"></div></div>';
                $("#divComboOrdenes").html(html);
                html="";
                res.forEach(element => {
                    html += '<a class="dropdown-item" style="cursor:pointer" onclick="verOrden('+element.FKOrden+')">'+element.folio+'</a>'
                });
                $("#items").html(html);
            }            
        },
        error: function (error) {
        console.log(error);
        Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: '¡Algo salió mal al recuperar datos!',
            sound: '../../../../../sounds/sound4'
        });
        },
    }); 
}

function verOrden(id) {
    window.location.href = "../orden_compras/verOrdenCompra.php?oc=" + id;
}

//funcion para cargar los productos 
function loadProducts(){
    //se recuperan datos para validar

    $("#tblListadoProductosRequisiciones").DataTable({
        language: setFormatDatatables(),
        destroy: true,
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
        responsive: true,
        lengthChange: false,
        ajax: {
            url: "php/functions.php",
            data: {
              clase: "get_data",
              funcion: "get_ProductosTableDetalle",
              data: idRequisicion,
            },
            error: function (error) {
                console.log(error);
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../../../img/timdesk/notificacion_error.svg",
                    msg: '¡Algo salió mal!',
                    sound: '../../../../../sounds/sound4'
                })
            }
          },
        columns: [
            { data: "Id" },
            { data: "Clave" },
            { data: "Producto" },
            { data: "Cantidad", width: "80px"},
            { data: "CantidadPedida", width: "80px"},
            { data: "Unidad medida", width: "35%" },
        ],
        columnDefs: [{targets: 0, visible: false }],
    })
}

$(document).ready(function () {
    idRequisicion = $("#RequisicionId").val();
    botones();
    getOrdenesGeneradas();
    loadProducts();

    //valida permisos
    if (_permissions.read !== 1) {
        $("#alert").modal("show");
    }

    //Redireccionamos al Dash cuando se oculta el modal.
    $("#alert").on("hidden.bs.modal", function (e) {
        window.location.href = "../../../dashboard.php";
    });

    $("#alert_NotFound").on("hidden.bs.modal", function (e) {
        window.location.href = "./";
    });
});