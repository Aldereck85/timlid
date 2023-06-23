var total,sutotal = 0;

$(function () {
    
    

   
    loadCabecera();
    loadTable();
    crearSlimSelect();
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
                    html = `<a href="#" data-toggle="modal" class="btn-table-custom btn-table-custom--red" onclick="showModal(`+response[i].id+','+response[i].PKCliente+`)" id="btnModalCancelacion"><img style="width:1.5rem; vertical-align: top;" src="../../img/cotizaciones_nuevos_iconos/ICONO-CANCELAR ROJO NVO-01.svg"></img> Cancelar</a>`;
                    $("#btnModalCancelacion").show();
                    console.log(response[i].estatus);
                }
                response[i].estatus = (response[i].estatus == 1) ? "Activa": "Cancelada"
                $("#estatus").text("Estatus: "+response[i].estatus);
                total = response[i].importe;
            });
            $("#total").html("<b>$ " + dosDecimales(total) + "</b>");
            $("#totalFactura").text(dosDecimales(total));
            $("#btncancelar").html(html);
            console.log(html);
            console.log(response);
          
        },
        error:function(error){
          console.log(error);
        }
    });
}

function crearSlimSelect(){
    new SlimSelect({
        select: '#cmbMotivo', 
        deselectLabel: '<span class="">✖</span>'
      });
}
function loadheader(){

}

function loadTable(){
    $.ajax({
        url: "functions/controller.php",
        data: {
          clase: "get_data",
          funcion: "get_tblConceptos",
          idnc:$("#idNota").val()
        },
        dataType: 'json',
        success: function(response){
            console.log(response);
            html = "";
            ///Aqui se debe de crear la tabla con los impuestos de un concepto en una misma row
            
            var tabla = {};
            var objtbl = {};
            ///Recorremos la tabla del select con impuestos por tipo y concepto 1 por row
            $.each(response,function(i){
                ///Si el id del concepto del impuesto no existe en el objeto tabla lo crea.
                if(tabla[response[i].iddetalle_notasCredito]){
                    if(response[i].tipo != null){
                         //Si ya existe entonces solo concatena al property impuestos el nuevo impuesto
                      if(response[i].factor == "Tasa"){
                      response[i].rate = (response[i].rate * 100) + "%";
                      objtbl.impuestos +=  response[i].tipo + " " + response[i].factor + ": " + response[i].rate + "-";
                  }else{
                      response[i].rate = "$" + (response[i].rate);
                      objtbl.impuestos +=  response[i].tipo + " " + response[i].factor + ": " + response[i].rate + "-";
                  }
                    }
                     
                }else{
                    ///Si no existe crea el objeto con todas sus propertys
                        //Si es Tasa lo pone en %
                    objtbl.impuestos ="";

                    //Si de la base de datos trae null, el concepto no tiene impuestos, entonces no pinta nada.
                    if(response[i].tipo != null){
                      if(response[i].factor == "Tasa"){
                          response[i].rate = (response[i].rate * 100) + "%";
                      }else{
                          response[i].rate = "$" + (response[i].rate);
                      }
                    objtbl.impuestos += response[i].tipo + " " + response[i].factor + ": " + response[i].rate+ "-";
                    }else{
                      
                    }
                   // sutotal += parseFloat(response[i].subtotal);
                    objtbl.iddetalle_notasCredito = response[i].iddetalle_notasCredito;
                    objtbl.clave_dePS = response[i].clave_dePS;
                    objtbl.unidad = response[i].unidad;
                    objtbl.cantidad = response[i].cantidad;
                    objtbl.descripcion = response[i].descripcion;
                    objtbl.importe = response[i].importe;
                }
                //Copia el contenido actual del objeto de conceptos en el objeto de la tabla general.
                    ///El objeto conceptos va cambiando en cada iteracion.
                tabla[response[i].iddetalle_notasCredito] = Object.assign({},objtbl);
                    /// Object.assign({},objtbl) CLONA el objeto vs = mantiene los objetos iguales siempre.          
            });
            sutotal = 0;
            var impuestos= "";
            var htmlimpuestos = "";
            //Recorre el objeto tala generando el html de la tabla visible.
            $.each(tabla,function(i){
                html += '<tr>'+
                        '<td style="text-align: center;">' + tabla[i].clave_dePS + '</td>'+
                        '<td style="text-align: center;">' + tabla[i].descripcion + '</td>'+
                        '<td style="text-align: center;">' + tabla[i].unidad + '</td>'+
                        '<td style="text-align: center;">' + tabla[i].cantidad + '</td>'+
                        '<td style="text-align: center;">' + ' ' + '</td>'+
                        '<td style="text-align: center;">$ ' + cortarNumber(tabla[i].importe) + '</td>'+
                      '</tr>';
                    sutotal= sutotal + parseFloat(tabla[i].importe);
                ///Aqui se deberá generar el html de la seccion de impuestos de la nota de credito

                if(tabla[i].impuestos!=undefined){
                  impuestos +=  (tabla[i].impuestos);
                }

            });
            impuestos = impuestos.split("-");
            /* impuestos = impuestos.substring(0, impuestos.length - 1); */
            $.each( impuestos, function( key, value ) {
                if(value!=""){
                    htmlimpuestos += value + "<br>";
                }
                

              });
            $("#tblDetalleNota tbody").append(html);
            $("#impuestos").html(htmlimpuestos);
            $("#subtotal").html("$ " + dosDecimales(sutotal));
            $("#tblDetalleNota").dataTable({
              info: false,
              scrollX: true,
              bSort: false,
              pageLength: 15,
              responsive: true,
              lengthChange: false,
              dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
              <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
              "columns": [{
                  "data": "Clave"
                },
                {
                  "data": "descripcion"
                },
                {
                  "data": "Unidad"
                },
                {
                  "data": "cantidad"
                },
                {
                  "data": "precio"
                },
                {
                  "data": "Importe"
                }
              ],
              responsive: true
            });
            
        },
        error:function(error){
          console.log(error);
        }
      });
}

function loadImpuestos(){

}
function dosDecimales(n) {
  return Number.parseFloat(n)
    .toFixed(2)
    .replace(/\d(?=(\d{3})+\.)/g, "$&,");
}

function cargarCMBRelacion(cliente){
  console.log(cliente);
  //here our function should be implemented 
  var html = "";
  //Consulta los proveedores de la empresa
  $.ajax({
    type:'POST',
    url: "functions/controller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_Docs",client:cliente},
    success: function (data) {
      //console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
            html += '<option disabled selected value="f">Seleccione</option>';
            html +=
            '<option value="' +
            data[i].id_Nota_Facturapi +
            '">' +
            data[i].num_serie_nota + ' ' + data[i].folion_nota + ": $" + dosDecimales(data[i].importe) +
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].id_Nota_Facturapi +
            '">' +
            data[i].num_serie_nota + ' ' + data[i].folion_nota + ": $" + dosDecimales(data[i].importe) +
            "</option>";
        }
      });
      //Pone los proveedores en el select
      $("#cmbRelacion").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  
});
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

function cancelarNC(id){
  var idsustituto = $("#cmbRelacion option:selected").val(); 
    console.log("sustituto" + idsustituto);
    if(idsustituto == undefined){
      idsustituto = 0;
    }
    console.log(idsustituto);
    let motivo = $("#cmbMotivo option:selected").val();
    $.ajax({
      type: "POST",
      url: "functions/controller.php",
      data: { clase:"delete_data",funcion:"cancel_NC",idnc:id, motive:motivo, idsnc:idsustituto },
      dataType: "text",
      success: function (response) {
        console.info(response);
        console.log(response);
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
        $('#mdlsavealert').modal('hide');
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
  function showModal(id,cliente){
    $('#mdlsavealert').modal('show')
    //Cada que se cambia el motivo comprueba la opcion seleccionada
    $("#cmbMotivo").change(function (e) { 
      // e.preventDefault();
       $option = $("#cmbMotivo option:selected").val();
       //Si es 02 03 04 habilita el boton aceptar
       if($option== "02" || $option== "03"|| $option== "04"){
         $("#btnAcepCambios").removeAttr("disabled").focus().val("Ahora si lo puedes editar");
         $("#relacion").html(`<div id="relacion" class= "col-lg">
                              </div>`)
      //Si es 01 muestra select de notas de credito del mismo cliente y empresa y pone el boton disabled
       }else if($option == "01"){
       console.log("cambio");
         $("#relacion").html(
           `<div id="relacion" class= "col-lg">
             <label for="usr">Documento Relacionado:*</label>
             <select name="cmbRelacion" class="form-select" id="cmbRelacion" aria-label="Default select example">
             </select>
             <div class="invalid-feedback" id="invalid-cmbFMPago">Seleccione un documento.</div>
           </div>`
         );
         setTimeout(() => {
           new SlimSelect({
             select: '#cmbRelacion', 
             deselectLabel: '<span class="">✖</span>'
           });
           //Llena el select
           cargarCMBRelacion(cliente);
         }, 100);
        // Pone el boton disabled
         $("#btnAcepCambios").attr('disabled', true);
         }
         /// Si se cambia el documento relacionado y no es f o undefine, habilita el boton
         $("#cmbRelacion").change(function (e) { 
          $option2 = $("#cmbRelacion option:selected").val();
          console.log($option2);
           if($option2 != "f"  && $option2 != undefined){
             $("#btnAcepCambios").removeAttr("disabled").focus().val("Ahora si lo puedes editar");
            }
         });
     });

    
     

    console.log(id);
    //Si se da click en aceptar, pero esta disabled no hace nada
    $("#btnAcepCambios").click(function (e) {
      if($(this).is('[disabled]')){

      }else{
        e.preventDefault();
        cancelarNC(id);
      } 
      
    });
  }

function test(){

}

var nDestinos = 1;

$(document).on("click","#btnEnviarFactura",function(){

    if($("#formEnviarEmail")[0].checkValidity()){
      
      var badDestinatario =
      $("#invalid-destino").css("display") === "block" ? false : true;
  
      if(badDestinatario){
        var a = $("input[type=text][name=txtDestino]");
        var arr = Array(
          $("#txtDestino").val()
        );
        console.log("no falta nada");
        if(a.length > 1){
          for (let index = 1; index < a.length; index++) {
            var value = $("#txtDestino"+index).val()
            if(value !== "")
            arr.push(value);
          }
        }
  
        $.ajax({
          url: "functions/controller.php",
          data: {
            clase: "send_data",
            funcion: "send_email",
            value:$("#idNota").val(),
            destinos:arr
          },
          dataType: 'json',
          success: function(response){
            console.log(response.ok);
            if(response.ok){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Se ha envíado el archivo pdf y el xml",
              });
              $("#enviarFactura").modal("toggle");
              
            }
            
          },
          error:function(error){
            console.log(error);
          }
        });
      } 
    }else {
      if (!$("#txtDestino").val()) {
        $("#invalid-destino").css("display", "block");
        $("#txtDestino").addClass("is-invalid");
      }
    }
  });

  $('#enviarFactura').on('hidden.bs.modal', function () {
    var a = $("input[type=text][name=txtDestino]");
    if(a.length > 1){
      for (let index = 1; index < a.length; index++) {
        $("#txtDestino").val("");
        $("#form-group-"+index).remove();
        nDestinos = 1;
      }
    }
  });

  $(document).on("click","#agregar_destinatarios",function(){
    nDestinos++;
    $("#eliminar_destinatarios").css("display","block");
    
    html = '<div class="form-group" id="form-group-'+nDestinos+'">'+
              '<label for="txtDestino'+nDestinos+'">Destinatario '+nDestinos+':</label>'+
              '<input type="text" class="form-control" name="txtDestino" id="txtDestino'+nDestinos+'">'+
            '</div>';
    if(nDestinos === 10){
      $("#agregar_destinatarios").css("display","none");
    }
    $("#enviarDestinatarios").append(html);
    
    
  });
  
  $(document).on("click","#eliminar_destinatarios",function(){
    $("#form-group-"+nDestinos).remove();
    $("#agregar_destinatarios").css("display","block");
    nDestinos--;
    if(nDestinos === 0){
      $("#eliminar_destinatarios").css("display","none");
    }
  });