var total,sutotal = 0;

$(function () {
    loadCabecera();
});

function loadCabecera(){
  var html ='';
    $.ajax({
        url: "functions/controller.php",
        data: {
          clase: "get_data",
          funcion: "get_cabecera",
          idnc:$("#idNota").val()
        },
        dataType: 'json',
        success: function(response){
            $.each(response, function (i) {
                $("#rfc").text(" "+response[i].rfc);
                $("#razon_social").text(response[i].razon_social);
                $("#fecha_timbrado").append(" "+response[i].fecha_captura);
                if(response[i].fecha_modifico == undefined){
                    $("#fecha_cancelacion").hide();
                }else{
                    $("#fech_canc").show();
                    $("#fecha_cancelacion").text(response[i].fecha_modifico);
                }
                $("#serie_folio").text(response[i].num_serie_nota + " " + response[i].folion_nota);
                if(response[i].estatus == 1){
                    html = `<a href="#" data-toggle="modal" class="btn-table-custom btn-table-custom--red" onclick="showModal(`+response[i].id+`)" id="btnModalCancelacion"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img> Cancelar</a>`;
                    $("#btnModalCancelacion").show();
                    console.log(response[i].estatus);
                }
                response[i].estatus = (response[i].estatus == 1) ? "Activa": "Cancelada"
                $("#estatus").text("Estatus: "+response[i].estatus);
                total = response[i].importe;
                $("#descripcion_NCV").text(response[i].descripcion);

            });
            $("#totalFactura").text(dosDecimales(total));
            $("#btncancelar").html(html);          
        },
        error:function(error){
          console.log(error);
        }
    });
}

function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}

function cortarNumber(n){
  //Corta los decimales en el punto
  if(n.split(".")){
    var m = n.split(".");
    ///Guarda la parte decimal en unarray
    m = m[1].split('');
    //Contador de decimales
    var cont = 0;
    var flagdecimales = 2;
    //Recorre la parte decimal
    m.forEach(element => {
      cont++;
      //Si el elemento es 0 no hace na
      if(element = "0"){

      }else{
        /// Si no es 0 la flag de la pos de decimales se iguala al contador
        flagdecimales = cont;
      }
    });
    //Si tiene menos de 3 decimales pone 2 decimales
    if(flagdecimales < 3){
      return Number.parseFloat(n)
      .toFixed(2)
      .replace(/\d(?=(\d{3})+\.)/g, "$&,");
      ///Si tiene mas de 3 pone los necesarios hasta 6
    }else{
      return ((parseFloat(n)).toFixed(6).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/,'$1'));

    }
    
  }
}

function showModal(id){
  $('#mdlAlert').modal('show');   

  //Si se da click en aceptar, pero esta disabled no hace nada
  $("#btnAcepCambioss").on("click", function () {
    
      cancelarNC(id);
    
    
  });
}

function cancelarNC(id){
    $.ajax({
      type: "POST",
      url: "functions/controller.php",
      data: { clase:"delete_data",funcion:"cancel_NCV",idnc:id},
      dataType: "text",
      success: function (response) {
        if(response=="1"){
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Se canceló la nota de credito!",
        });
        $('#mdlAlert').modal('hide');
        loadCabecera();
        $("#btnModalCancelacion").hide();
        }   
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
}
