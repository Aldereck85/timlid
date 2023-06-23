$(document).ready(function(){
  validate_Permissions(22);
  
  consultarChat();
  setInterval(consultarChat, 1000);

  setInterval(cargarPDF, 1000); 
  //cargarPDF(); 
  
  //cargarProductosPDF();
  //cargarImpuestosPDF();
}); 
var PKLastComentario = 0;
//-- NOTE: No use time on insertChat.
function consultarChat(){
  var FKOrdenCompraEncripted = $("#txtFKEncripted").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_Comentario",
      data: FKOrdenCompraEncripted,
    },
    dataType: "json",
    success: function (respuesta) {
      for (i = 0; i < respuesta.length; i++) {
        if (respuesta[i].PKComentarioOC > PKLastComentario){
          if(respuesta[i].Tipo != '1'){
            insertChat("me", respuesta[i].Comentario, 1000,respuesta[i].Hora);      
          }else{ 
            insertChat("you", respuesta[i].Comentario, 1000,respuesta[i].Hora);
          }
          PKLastComentario = respuesta[i].PKComentarioOC;
        }      
      }
    },
  });
}

function cargarPDF(){
  
  var FKOrdenEn = $("#txtFKEncripted").val();
  var FKUsuario = $("#txtUsuario").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_OrdenCompraPDF",
      data: FKOrdenEn,
      data2: FKUsuario,
    },
    dataType: "json",
    success: function (respuesta) {
      if(respuesta[0].FKEstatusOrden == 1){
        var html = ``;
        if (_permissions.edit == '1'){
          var html2 = `<div class="form-group">
                          <div class="row">
                            <div class="col-lg-4" style="margin-top:100px">
                              <button type="button" class="btn-custom btn-custom--blue" name="btnAceptar" id="btnAceptar"
                              onclick="aceptarOrdenCompra(2)" style="float:right"> Aceptar orden de compra</button>
                            </div>
                            <div class="col-lg-8" style="margin-top:100px">
                              Notificar a ${respuesta[0].NombreComercial} que darás por
                              aceptada la orden de compra
                            </div>
                          </div>
                        </div>

                        <div class="form-group" id="permissionsCancelar">
                          <div class="row">
                            <div class="col-lg-4">
                              <button type="button" class="btn-custom btn-custom--blue" name="btnRechazar" id="btnRechazar"
                              onclick="aceptarOrdenCompra(3)" style="float:right"> Cancelar orden de compra</button>
                            </div>
                            <div class="col-lg-8" >
                              Notificar a `+respuesta[0].NombreComercial+` que deseas
                              cancelar la orden de compra
                            </div>
                          </div>
                        </div> `;
        }else{
          var html2 = ``;
        }

        if (_permissions.export == '1'){
          var html3 = `
                    <div class="form-group" id="permissionsDescargar">
                      <div class="row">
                        <div class="col-lg-4">
                          <button type="button" class="btn-custom btn-custom--blue" name="btnDescargar" id="btnDescargar"
                          onclick="descargarOrdenCompra()" style="float:right"> Descargar
                          orden
                          de compra</button>
                        </div>
                        <div class="col-lg-8">
                          Descargar PDF con la orden de compra solicitada a `+respuesta[0].NombreComercial+`
                        </div>
                      </div>
                    </div>`;
        }else{
          var html3 = ``;
        }

        html = html2 + html3;
       
        $("#btnEnviar").css("display","block");
        $("#mytext").attr("readonly", false); 
      }else if(respuesta[0].FKEstatusOrden == 2){
        var html = `La orden ha sido aceptada`;
        
        $("#btnEnviar").css("display","none");
        $("#mytext").prop("readonly","true");
      }else if(respuesta[0].FKEstatusOrden == 3){
        if (_permissions.edit == '1'){  
          var html = `
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-4" style="margin-top:100px">
                          <button type="button" class="btn-custom btn-custom--blue" name="btnActivar" id="btnActivar"
                          onclick="aceptarOrdenCompra(1)" style="float:right"> Reactivar
                          orden
                          de compra</button>
                        </div>
                        <div class="col-lg-8" style="margin-top:100px">
                          Notificar a `+respuesta[0].NombreComercial+` que deseas
                          reactivar la orden de compra
                        </div>
                      </div>
                    </div>`;
        }else{
          var html = ``;
        }
        $("#btnEnviar").css("display","none");
        $("#mytext").prop("readonly","true");
      }else if(respuesta[0].FKEstatusOrden == 4){
        var html = `La orden se ha vencido`;
        $("#btnEnviar").css("display","none");
        $("#mytext").prop("readonly","true");
      }else if(respuesta[0].FKEstatusOrden == 5){
        var html = `La orden ha sido rechazada`;
        $("#btnEnviar").css("display","none");
        $("#mytext").prop("readonly","true");
      }else if(respuesta[0].FKEstatusOrden == 6){
        if (_permissions.edit == '1'){  
          var html = `
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-4" style="margin-top:100px">
                          <button type="button" class="btn-custom btn-custom--blue" name="btnCerrar" id="btnCerrar" onclick="aceptarOrdenCompra(7)" style="float:right"> 
                            Cerrar orden de compra
                          </button>
                        </div>
                        <div class="col-lg-8" style="margin-top:100px">
                          Cerrar orden de compra.
                        </div>
                      </div>
                    </div>`;
        }else{
          var html = `La orden se ha demorado en su entrega`;
        }
        $("#btnEnviar").css("display","none");
        $("#mytext").prop("readonly","true");
      }else if(respuesta[0].FKEstatusOrden == 7){
        var html = `La orden ha sido cerrada`;
        $("#btnEnviar").css("display","none");
        $("#mytext").prop("readonly","true");
      }else if(respuesta[0].FKEstatusOrden == 8){
        var html = `La orden ha sido recibida completa`;
        $("#btnEnviar").css("display","none");
        $("#mytext").prop("readonly","true");
      }

      $("#botones").html(html);
      $("#referencia").html(respuesta[0].Referencia);
      $("#referencia2").html(respuesta[0].Referencia);
      $("#fechaIngreso").html(respuesta[0].FechaCreacion);
      $("#nombreComercial").html(respuesta[0].NombreComercial); 
      $("#nombreComercial2").html(respuesta[0].razon_social); 
      $("#vendedor").html(respuesta[0].Empleado); 
      $("#vendedor2").html(respuesta[0].Empleado); 
      $("#fechaEstimada").html(respuesta[0].FechaEstimada); 
      $("#sucursal").html(respuesta[0].Sucursal); 
      $("#direccion").html(respuesta[0].Calle + ' ' + respuesta[0].NumExt + ' Int.' + respuesta[0].NumInt + '- ' + respuesta[0].Prefijo + ', ' + respuesta[0].Colonia + ', ' + respuesta[0].Municipio + ', ' + respuesta[0].Estado + ', ' + respuesta[0].Pais); 
      $("#subtotal").html("$ "+dosDecimales(respuesta[0].Subtotal)); 
      $("#total").html("$ "+dosDecimales(respuesta[0].Total));

      if (respuesta[0].notas == '' || respuesta[0].notas == null){
        $("#notas").html('<br><br><br><br><br><br><br>');
      }else{
        $("#notas").html(respuesta[0].notas);
      }

      $("#telefono").html(respuesta[0].Telefono);
      $("#btnEnviar").prop("title","Envía comentarios a "+respuesta[0].Empleado+" de "+respuesta[0].NombreComercial+" sobre la orden de compra");

      $("#cmbProveedor").val(respuesta[0].FKProveedor);
    },
  });
}

function cargarProductosPDF(){
  var FKOrdenEn = $("#txtFKEncripted").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datosProd_OrdenCompraPDF",
      data: FKOrdenEn,
    },
    dataType: "json",
    success: function (respuesta) {

      for (i = 0; i < respuesta.length; i++) {
        var impuestos = '';
        if (respuesta[i].impuestos == null){
          impuestos = '';
        }else{
          impuestos = respuesta[i].impuestos
        }

          document.getElementById("tablaProductos").insertRow(-1).innerHTML = `<td class="td1" width="9%">`+respuesta[i].clave+`</td>
        <td class="td1" width="36%">`+respuesta[i].nombre+`</td>
        <td class="td1" width="10%">`+respuesta[i].cantidad+`</td>
        <td class="td1" width="10%">$ `+dosDecimales(respuesta[i].precio)+`</td>
        <td class="td1" width="11%">`+respuesta[i].unidadMedida+`</td>
        <td class="td1" width="12%">`+impuestos+`</td>
        <td class="td1" width="100%" height="50px">$ `+dosDecimales(respuesta[i].importe)+`</td>`;
      }
    },
  });
}

function cargarImpuestosPDF(){
  var FKOrdenEn = $("#txtFKEncripted").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datosImpu_OrdenCompraPDF",
      data: FKOrdenEn,
    },
    dataType: "json",
    success: function (respuesta) {
      var tasa = '';
      for (i = 0; i < respuesta.length; i++) {
          if (respuesta[i].tasa == '' || respuesta[i].tasa  == null){
            tasa = respuesta[i].nombre + respuesta[i].tasa;
          }else{
            tasa = respuesta[i].nombre +` - `+ respuesta[i].tasa + `%`; 
          }
          document.getElementById("tablaImpuestos").insertRow(-1).innerHTML = `<td class="td1" width="65%" style="background-color: transparent;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
          <td class="td1" width="21%" style="text-align: right;">`+ tasa +` </td>
          <td class="td1" width="100%" style="text-align: right;">$ `+ dosDecimales(respuesta[i].totalImpuesto) +`</td>`;
      }

      document.getElementById("tablaImpuestos").insertRow(-1).innerHTML = `<tr>
        <td class="td1" width="65%"
        style="background-color: transparent; border-bottom: 1px solid #fff; border-top: 1px solid #fff;">
      </td>
      <td class="td1" width="21%">Total:</td>
      <td class="td1" width="100%" style="text-align: right;">
        <span id="total">

        </span>
      </td>
    </tr>`;
    },
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

//-- No use time. It is a javaScript effect.
function insertChat(who, text, time, hour){
  if (time === undefined){
      time = 0;
  }
  var control = "";
  
  if (who == "me"){
      control = '<li style="width:100%">' +
                      '<div class="msj macro">' +
                          '<div class="text text-l">' +
                              '<p>'+ text +'</p>' +
                              '<p><small>'+hour+'</small></p>' +
                          '</div>' +
                      '</div>' +
                  '</li>';                    
  }else{
      control = '<li style="width:100%;">' +
                      '<div class="msj-rta macro">' +
                          '<div class="text text-r">' +
                              '<p>'+text+'</p>' +
                              '<p><small>'+hour+'</small></p>' +
                          '</div>' +                       
                '</li>';
  }
  setTimeout(
      function(){                        
          $("ul").append(control).scrollTop($("ul").prop('scrollHeight'));
      }, time);
  
}

function resetChat(){
  $("ul").empty();
} 

function validarTecla(e){
  tecla = (document.all) ? e.keyCode : e.which;
  if (tecla==13) {
    enviarMensaje(); 
  }
}

function enviarMensaje(){
  var FKOrdenEn = $("#txtFKEncripted").val();
  var mensaje = $("#mytext").val();

  if (mensaje != ''){
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_ordenCompra_Mensaje",
        datos: mensaje, datos2:'1', datos3:FKOrdenEn
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar orden de compra:", respuesta);
  
        if (respuesta[0].status) {
          console.log('Mensaje enviado');
          $("#mytext").val('');
          /*resetChat();*/
          consultarChat();
          //insertChat("me", mensaje, 1000,respuesta[0].fecha);
        } else {
          console.log('Mensaje no enviado');
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}

function aceptarOrdenCompra (valor){

  if (valor == 3){
    $("#estatusIDCancelar").val(valor);
    $('#cancelar_OrdenCompra').modal('show');
  }else if (valor == 2){
    $("#estatusIDAceptar").val(valor);
    $('#aceptar_OrdenCompra').modal('show');
  }else if (valor == 1){
    $("#estatusIDReactivar").val(valor);
    $('#activar_OrdenCompra').modal('show');
  }else if (valor == 7){
    $("#estatusIDCerrar").val(valor);
    $('#cerrar_OrdenCompra').modal('show');
  }
}


function updateEstatusOC(valor){
  var id = $("#txtFKEncripted").val();
  console.log(valor);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_AceptarOrdenCompra",
      datos: id,
      datos2: valor,
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        if (valor == 2){

          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se notificó que se ha aceptado la orden de compra.!",
            sound: '../../../../../sounds/sound4'
          });
        }else if (valor == 3){
 
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Se notificó que se ha cancelado la orden de compra.!",
            sound: '../../../../../sounds/sound4'
          });
        }else if (valor == 1){

          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se notificó que se ha reactivado la orden de compra!",
            sound: '../../../../../sounds/sound4'
          });
        }else if (valor == 7){

          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se ha cerrado la orden de compra!",
            sound: '../../../../../sounds/sound4'
          });
        }
        $("#mytext").val("");

        setTimeout(function(){window.location.href = '../orden_compras'},1500);
      } else {
        Swal.fire("Error", 
          "¡Algo salio mal :(!, ¡Favor de intentarlo más tarde!", 
          "warning"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function descargarOrdenCompra(){
  var id = $("#txtFKEncripted").val();

  window.location.href = "functions/descargar_OrdenCompra.php?txtId="+id;
}

function validate_Permissions(pkPantalla){
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
    }
  });
}