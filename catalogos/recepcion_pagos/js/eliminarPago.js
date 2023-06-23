function eliminaPago(idPago,from){
  $("#btnEliminaPago").prop('disabled',true);

    $("#mdlDelete").modal('show');
    $("#btnCancelarEliminarPago").on('click', function(){
        $("#mdlDelete").modal('hide');
    });
    $("#btnAcepEliminarPago").off('click').on('click', function(){
      $("#mdlDelete").modal('hide');
        $.ajax({
            url: "functions/function_eliminaPago.php",
            data: { 
              idPago:idPago,
              from:from
            },
            dataType: "json",
            async:false,
            success: function (data) {
              if (data['status']=='ok') {
                if(from==1){
                  //si viene de la pantalla prinmcipal no recarga.
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "¡Pago eliminado con exito!"
                  });
                }else{
                  location.href ="../recepcion_pagos/";
                }
                
              } else if(data['status'] == 'err-2') {
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "¡No se puede eliminar el pago! Pagos con complemento de pago",
                });
              }else if(data['status'] == 'err-1') {
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "¡Solo se puede eliminar el ultimo pago de una factura completada!",
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
                  msg: "¡Algo salio mal!",
                });
              }
              tabla=$("#tblmovimientos").DataTable();
              tabla.ajax.url("functions/get_movimientos.php").load();
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
                $("#btnEliminaPago").prop('disabled',false);
            },
          });
    });
}