var id_proveedor;
var cuenta;
$(document).ready(function(){
    cuenta = $("#cuenta").val();
    console.log(cuenta);
    crearSelects();
    cragarDAta(cuenta);
    $("#btnguardarDetalle").click(function(){
      validarData();
    });

});
function crearSelects(){
    new SlimSelect({
        select: '#cmbSucursal', 
        deselectLabel: '<span class="">✖</span>'
      })
}
function cragarDAta(cuenta){
    $.ajax({
        type:'POST',
        url:'../cuentas_pagar/functions/get_ajax.php',
        dataType: "json",
        data:{user_id:cuenta,funcion: "2"},
        success:function(data){
            if(data.status == 'ok'){
                $('#txtProveedor').val(data.result.NombreComercial);
                id_proveedor = data.result.proveedor_id;
                $('#txtNoDocumento').val(data.result.folio_factura);
                $('#txtSerie').val(data.result.num_serie_factura);
                $('#txtSubtotal').val(data.result.subtotal);
                $('#txtImporte').val(data.result.importe);
                $('#txtIva').val(data.result.iva);
                $('#txtIEPS').val(data.result.ieps);
                /* $('#txtimporte').val(Intl.NumberFormat("es-MX").format(data.result.importe)); */
                $('#txtfecha').val(data.result.fecha_factura);
                $('#id_sucursal').val(data.result.id_sucursal);
                $('#txtDescuento').val(data.result.descuento);
                $("#txtCategoriaGasto").text(data.result.categoria);
                $('#txtSubcategoriaGasto').text(data.result.subcategoria);
                cargarCMBsucursal(data.result.id_sucursal);
                var tipoDoc = data.result.tipo_documento;
                console.log(tipoDoc);
                if(tipoDoc==1){
                    $("#factura").prop("checked", true);
                }else if(tipoDoc == 2){
                    $("#remision").prop("checked", true);
                }else if(tipoDoc == 4){
                    $("#anticipo").prop("checked", true);
                }
  
            }else{
                $('.user-content').slideUp();
                $("#alertInvoice").modal("show");
            } 
        }
    });
}
function cargarCMBsucursal(suc){
    var idsucrsal = $('#cuentaid').val();
    /* $("#cmbProveedor").prop("disabled", true); */
    /* $("#chkCategoria").prop("disabled", true); */
    var html = "";
    $.ajax({
        type: 'POST',
        url: "functions/controller.php",
        dataType: "json",
        data: { clase: "get_data", funcion: "get_sucursalCombo" },
        success: function(data) {
            console.log("data de cuenta: ", data);
            $.each(data, function(i) {
                if (data[i].PKSucursal == suc) {
                    html +=
                        '<option selected value="' +
                        data[i].PKSucursal +
                        '">' +
                        data[i].Sucursal +
                        "</option>";
                } else {
                    html +=
                        '<option value="' +
                        data[i].PKSucursal +
                        '">' +
                        data[i].Sucursal +
                        "</option>";
                }
            });

            $("#cmbSucursal").append(html);
        },
        error: function(error) {
            console.log("Error");
            console.log(error);
        },
    });
}
//Validar que la serie y folio sean las misamas que anteriormente, si no son, no permite editar
function validarrepit(serie, folio){
    var idProveedor = id_proveedor;
    var cuenta = $("#cuenta").val();
    $.ajax({
        type:'POST',
        url: "functions/controller.php",
        dataType: "json",
        data: { clase:"get_data",funcion:"validate_seriefolio_toupdate", _serie:serie,_folio:folio,_idProveedor:idProveedor,_cuenta:cuenta},
        success: function (data) {
          console.log("data de proveedor: ", data);
          $.each(data, function (i) {
              if (data[i].existe==1){
                update();
              }
              else{
                inputID= "txtNoDocumento"; 
                invalidDivID = "invalid-noDocumento";
                $("#" + inputID).addClass("is-invalid");
                $("#" + invalidDivID).show();
                $("#" + invalidDivID).text("Folio no es editable, elimine e inserte una nuevo");
                inputID= "txtSerie"; 
                invalidDivID = "invalid-serie";
                $("#" + inputID).addClass("is-invalid");
                $("#" + invalidDivID).show();
                $("#" + invalidDivID).text("La serie no es editable, elimine e inserte una nueva");
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Error se modifico la serie o folio!",
                  });
              }
          });
        },
        error: function (error) {
          console.log("Error");
          console.log(error);
        },
      });
}
function validarData(){
    redFlag1 = 0;
    redFlag2 = 0;
    redFlag3 = 0;
    redFlag4 = 0;
    inputID= "txtNoDocumento"; 
    invalidDivID = "invalid-noDocumento";
    textInvalidDiv = "Campo requerido";
    if (($('#txtNoDocumento').val()=="" || $('#txtNoDocumento').val()==undefined )) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).show();
      $("#" + invalidDivID).text("Folio requerido");
    } else {
      $("#" + inputID).removeClass("is-invalid");
      $("#" + invalidDivID).hide();
      $("#" + invalidDivID).text("Folio requerido");
      redFlag1 = 1;
    }
    invalidDivID = "invalid-serie";
    if (($('#txtSerie').val()=="" || $('#txtSerie').val()==undefined )) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).show();
        $("#" + invalidDivID).text("Serie requerida");
    } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text("Serie requerida");
        redFlag2 = 1;
    }
    invalidDivID = "invalid-subtotal";
    if (($('#txtSubtotal').val()=="" || $('#txtSubtotal').val()==undefined )) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).show();
        $("#" + invalidDivID).text("Subtotal requerido");
    } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text("Subtotal requerido");
        redFlag3 = 1;
    }
    invalidDivID = "invalid-importe";
    if (($('#txtImporte').val()=="" || $('#txtImporte').val()==undefined )) {
        $("#" + inputID).addClass("is-invalid");
        $("#" + invalidDivID).show();
        $("#" + invalidDivID).text("Importe requerido");
    } else {
        $("#" + inputID).removeClass("is-invalid");
        $("#" + invalidDivID).hide();
        $("#" + invalidDivID).text(textInvalidDiv);
        redFlag4 = 1;
    }
    if(redFlag1==1&&redFlag2==1&&redFlag3==1&&redFlag4==1){
        var serie = $("#txtSerie").val();
        var folio = $("#txtNoDocumento").val();
        console.log(serie,folio);
        validarrepit(serie, folio);
    }else{
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
  }
}
function update(){
    var fecha = $("#txtfecha").val();
    var cmbSucursal = $('select[name=cmbSucursal] option').filter(':selected').val();
    var txtSubtotal = $("#txtSubtotal").val();
    var txtIva = $("#txtIva").val();
    if(txtIva==""||txtIva==null){
        txtIva=0;
    }
    var txtIEPS = $("#txtIEPS").val();
    if(txtIEPS==""||txtIEPS==null){
        txtIEPS=0;
    }
    
    var txtImporte = $("#txtImporte").val();
    var txtDescuento = $("#txtDescuento").val();
    var txtDescuento = $("#txtIEPS").val();
    if(txtDescuento==""||txtDescuento==null){
        txtDescuento=0.0;
    }
    var radiodoc = $('input:radio[name=radioDoc]:checked').val()

    //Ajax que manda los parametros para el procedimiento almacenado spc_tablaDetalle_cuentasCobrar
    $.ajax({
        url: "functions/controller.php",
        data: { 
          clase: "save_datas",
          funcion: "update",
          _cuenta:cuenta,
          _sucursal: cmbSucursal,
          _txtSubtotal: txtSubtotal,
          _txtIva:txtIva,
          _txtIEPS:txtIEPS,
          _txtImporte:txtImporte,
          _txtDescuento:txtDescuento,
          _fecha:fecha,
          _radiodoc:radiodoc
        },
        dataType: "json",
        success: function (data,response) {
          console.log("data de cabecera: "+ data);
          // Si se recibio un error 
          if (response[0]!="E") {
            console.log("Respuesta 0 "+ data);
            
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 2000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "¡Pago registrado con exito!",
            });
           setTimeout(function(){ window.location= '../pagos';}, 1500);
          } else {
            console.log("Error");
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 1498,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal!",
            });
          }
        },
        error: function(jqXHR, exception,data,response) {
          var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log("data de cabecera: "+ data);
            console.log("response de cabecera: "+ response);
            console.log("excepcion " + exception);
            console.log(msg);
        },
      });
}