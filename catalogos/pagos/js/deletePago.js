
function modalShow(idPago, origen){
    $('#mdlsavealert').modal('show');
    //$('#btnAcepCambios').off('click').on('click', { modal: modal }, deletePago(idPago));
    $('#btnAcepCambios').one('click', function(){
      deletePago(idPago,origen);
    });
  }
  
  //Elimina y pone el status de la cuenta por pagar en 3 llamando a procedimineto almacenado con transaccion
  function deletePago(idPago, origen){
    console.log("Eliminando: "+ idPago);
    $.ajax({
      type:'POST',
      url: "functions/addcontroller.php",
      dataType: "json",
      async:false,
      data: { clase:"delete_data",funcion:"delete_pago", idpagos:idPago, _origen:origen},
      success: function (data) {
        if(origen==1){
          //Si data trae 0 entonces no se pudo eliminar porque alguno no era el ultimo
          if(data[0]=="0"){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 1500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Solo se puede eliminar el ultimo pago de una factura pagada!",
            });
            $('#mdlsavealert').modal('hide');
          }else if(data[0]=="1"){
            setTimeout(function(){ window.location= '../pagos';}, 300);
          }else{
            console.log("Dato eliminado: ", data);
            $('#mdlsavealert').modal('hide');
            //Recarga la tabla con las nuevas cuentas
            //tablaD.ajax.url( "functions/get_pagos.php?toDo=3" ).load();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 1500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Pago eliminado!",
            });
            setTimeout(function(){ window.location= '../pagos';}, 300);
          }
        }else{
          if(data[0]=="0"){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 1500,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Solo se puede eliminar el ultimo pago de una factura pagada!",
            });
            $('#mdlsavealert').modal('hide');
          }else if(data[0]=="1"){
            setTimeout(function(){ window.location= '../pagos';}, 300);
          }else{
            setTimeout(function(){ window.location= '../pagos';}, 300);
          }
          
        }
        
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
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
        setTimeout(function(){ window.location= '../pagos';}, 1500);
      },
    });
  }