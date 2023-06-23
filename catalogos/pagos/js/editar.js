//array de cuentas a insertar, se guardan los que ya estaban seleccionados y se van agregando los nuevos o eliminando segun sea el caso
//To INSERT
var arID = [];
//Array que copia las cuentas que estaban seleccionadas desde un principio, para mas tarde hacer la comparacion de lo que habia y lo que ahora hay seleccionado
//To DELETE
var arIdOlds =[];
//array que guarda solo las llaves de los arrays anteriores respectivamente Array Id Only (olds)
var aridonlyolds= [];
var aridonly= [];
var flagSaldoSuficiente = true;

$(document).ready(function() {
    cargardata();
    crearSelects();

    $('#cmbCuenta').on('change', function() {
        inputID= "cmbCuenta";
        invalidDivID = "invalid-cuenta";
        imput=document.getElementById('txtTotal');
        sumaTotal=imput.value=parseFloat(imput.value.replace(/[ ]/g,''), 10);
        var saldoCuenta = $('select[name='+inputID+'] option').filter(':selected').text();
        saldoCuenta = saldoCuenta.split('$')[1];
        saldoCuenta = parseFloat(saldoCuenta);
        //Si el saldo de la cuenta es mayor a el total de las cuetas seleccionadas
        if(sumaTotal<saldoCuenta){
          $("#" + inputID).removeClass("is-invalid");
          $("#" + invalidDivID).hide();
          $("#" + invalidDivID).text(textInvalidDiv);
          //Bandera para saber si el saldo de la cuenta es suficiente a la hora de guardar
          flagSaldoSuficiente = true;
          //Si el saldo es menor a el total a pagar de las cuentas seleccionadas
        }else{
          $("#" + inputID).addClass("is-invalid");
          $("#" + invalidDivID).show();
          $("#" + invalidDivID).text("Saldo Insuficiente");
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Saldo insuficiente! on change",
          });
          //Bandera para saber si el saldo de la cuenta no es suficiente a la hora de guardar
          flagSaldoSuficiente = false;
          $('select[name='+inputID+'] option[value=f]').attr('selected',true);
        }
        //alert( saldoCuenta );
      });

    $("#btnguardar").click(function() {
        validarImputs();
    });

});
function validarImputs(){
        redFlag1 = 0;
        redFlag2 = 0;
        redFlag3 = 0;
        redFlag4 = 0;
        redFlag6 = 0;
        inputID= "cmbProveedor"; 
        invalidDivID = "invalid-nombreProv";
        textInvalidDiv = "Campo requerido";
        if (($('select[name='+inputID+'] option').filter(':selected').val())=="f") {
          $("#" + inputID).addClass("is-invalid");
          $("#" + invalidDivID).show();
          $("#" + invalidDivID).text(textInvalidDiv);
        } else {
          $("#" + inputID).removeClass("is-invalid");
          $("#" + invalidDivID).hide();
          $("#" + invalidDivID).text(textInvalidDiv);
          redFlag1 = 1;
        }
        inputID="cmbTipoPag";
        invalidDivID = "invalid-tipo";
        if (($('select[name='+inputID+'] option').filter(':selected').val())=="f") {
          $("#" + inputID).addClass("is-invalid");
          $("#" + invalidDivID).show();
          $("#" + invalidDivID).text(textInvalidDiv);
        } else {
          $("#" + inputID).removeClass("is-invalid");
          $("#" + invalidDivID).hide();
          $("#" + invalidDivID).text(textInvalidDiv);
          redFlag2 = 1;
        }
        inputID= "cmbCuenta";
        invalidDivID = "invalid-cuenta";
        if (($('select[name='+inputID+'] option').filter(':selected').val())=="f") {
          $("#" + inputID).addClass("is-invalid");
          $("#" + invalidDivID).show();
          $("#" + invalidDivID).text(textInvalidDiv);
        } else {
          $("#" + inputID).removeClass("is-invalid");
          $("#" + invalidDivID).hide();
          $("#" + invalidDivID).text(textInvalidDiv);
          redFlag3 = 1;
        }
        inputID= "textareaCoemtarios";
        invalidDivID = "invalid-textareaCoemtarios";
        if ((($('#'+inputID).val()).length)>140) {
          $("#" + inputID).addClass("is-invalid");
          $("#" + invalidDivID).show();
          $("#" + invalidDivID).text("Maximo 140 caracteres en el comenario");
        } else {
          $("#" + inputID).removeClass("is-invalid");
          $("#" + invalidDivID).hide();
          $("#" + invalidDivID).text("La fecha no puede estar vacia");
          redFlag6 = 1;
        }
        if((redFlag1==1)&&(redFlag2==1) && (redFlag3==1) && redFlag6 == 1){
            if(flagSaldoSuficiente){
                constructArrays();
            }else{
                Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Saldo insuficiente! validarinputs",
                  });
                  console.log(flagSaldoSuficiente);
            }
            
        }else{
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Validar: Faltan algunos campos requeridos!",
          });
        }
}

function cargardata() {
    /* Optenemos los valores de la cuenta por pagar y los ponemos en los campos de la pantalla editar */
    /* $('#editarcp').on('click',function(){ */
    var idpagos = $('#idpago').val();
    $.ajax({
        type: 'POST',
        url: '../pagos/functions/get_to_edit.php',
        dataType: "json",
        data: { idpagos: idpagos, funcion: "1" },
        success: function(data) {
            if (data.status == 'ok') {
                $('#proveedorid').val(data.result.PKProveedor);
                $('#txtfecha').val(data.result.fecha_pago);
                $('#tipopagoid').val(data.result.tipo_pago);
                $('#cuentaid').val(data.result.cuenta_origen_id);
                $('#txtreferencia').val(data.result.Referencia);
                $('#textareaCoemtarios').val(data.result.comentarios);
                $('#txtTotal').val(data.result.total);
    

                cargarCMBProveedor();

            } else {
                $('.user-content').slideUp();
                $("#alertInvoice").modal("show");
            }
        }
    });

}

function crearSelects() {
    new SlimSelect({
        select: '#cmbProveedor',
        deselectLabel: '<span class="">✖</span>'
    })
    new SlimSelect({
        select: '#cmbCuenta',
        deselectLabel: '<span class="">✖</span>'
    })
    new SlimSelect({
        select: '#cmbTipoPag',
        deselectLabel: '<span class="">✖</span>'
    })
}

function cargarCMBProveedor() {
    var idprove = $('#proveedorid').val();
    /* $("#cmbProveedor").prop("disabled", true); */
    /* $("#chkCategoria").prop("disabled", true); */
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
        type: 'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        data: { clase: "get_data", funcion: "get_proveedorCombo" },
        success: function(data) {
            console.log("data de proveedor: ", data);
            $.each(data, function(i) {
                console.log(data[i].PKData);
                console.log((idprove));
                console.log(data[i].PKData == parseInt(idprove));
                

                //Crea el html para ser mostrado
                if (data[i].PKData == parseInt(idprove)) {
                    html +=
                        '<option selected value="' +
                        data[i].PKData +
                        '">' +
                        data[i].Data +
                        "</option>";
                } else {
                    html +=
                        '<option value="' +
                        data[i].PKData +
                        '">' +
                        data[i].Data +
                        "</option>";
                }
            });
            //Pone los proveedores en el select
            $("#cmbProveedor").append(html);
            //Aplica el primer filtro con el proveedor primero
            /* var table = $('#tblcuentas').DataTable();
            $('input[type="search"]').val($("#cmbProveedor option:selected").text());
            table
                .search($("#cmbProveedor option:selected").text())
                .draw(); */
                cargarCMBcuentasOtros();
            //cargarProductosEmpresa();
        },
        error: function(error) {
            console.log("Error");
            console.log(error);
        },
    });


}
var html = "";
function cargarCMBcuentasCheques(){
    return new Promise((resolve)=>{
    var idcuenta = $('#cuentaid').val();
    html += '<optgroup label="Cheques">'
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_cuenta_cheque"},
    success: function (data) {
      console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (data[i].PKCuenta == idcuenta) {
          html +=
            '<option selected value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +": $"+ data[i].saldo_actual+
            "</option>";
        }else if(i== data.length){
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +": $"+ data[i].saldo_actual+
            "</option></optgroup>";

        } else {
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +": $"+ data[i].saldo_actual+
            "</option>";
        }
      });
      
      $("#cmbCuenta").append(html);
      resolve();
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
});
 // cargarCMBcuentasOtros();
}
async function cargarCMBcuentasOtros(tamaño){
    await cargarCMBcuentasCheques();
  var htmlO = "";
  var idcuenta = $('#cuentaid').val();
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_cuenta_otras"},
    success: function (data) {
      console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (html=="" && data[i].PKCuenta == idcuenta) {
          htmlO +=
            '<option selected value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +": $"+ data[i].saldo_actual+
            "</option>";
        }else if(i == 0 && data[i].PKCuenta == idcuenta){
        htmlO +=
          '<optgroup label="Otras"><option selected value="' +
          data[i].PKCuenta +
          '">' +
          data[i].Cuenta +": $"+ data[i].saldo_actual+
          "</option>";
        }else if(data[i].PKCuenta == idcuenta && i == 0){
            htmlO +=
              '<optgroup label="Otras"><option selected value="' +
              data[i].PKCuenta +
              '">' +
              data[i].Cuenta +": $"+ data[i].saldo_actual+
              "</option>";
        }else if(i == 0 ){
            htmlO +=
              '<optgroup label="Otras"><option value="' +
              data[i].PKCuenta +
              '">' +
              data[i].Cuenta +": $"+ data[i].saldo_actual+
              "</option>";
        }else if(i== data.length){
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +": $"+ data[i].saldo_actual+
            "</option></optgroup>";

        } else {
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +": $"+ data[i].saldo_actual+
            "</option>";
        }
      });
      
      $("#cmbCuenta").append(htmlO);
      cargarCMBTipo();
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}
function cargarCMBCuentas() {
    var idcuenta = $('#cuentaid').val();
    /* $("#cmbProveedor").prop("disabled", true); */
    /* $("#chkCategoria").prop("disabled", true); */
    var html = "";
    $.ajax({
        type: 'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        data: { clase: "get_data", funcion: "get_cuenta" },
        success: function(data) {
            console.log("data de cuenta: ", data);
            $.each(data, function(i) {
                if (data[i].PKCuenta == idcuenta) {
                    html +=
                        '<option selected value="' +
                        data[i].PKCuenta +
                        '">' +
                        data[i].Cuenta +
                        "</option>";
                } else {
                    html +=
                        '<option value="' +
                        data[i].PKCuenta +
                        '">' +
                        data[i].Cuenta +
                        "</option>";
                }
            });

            $("#cmbCuenta").append(html);
            cargarCMBTipo();
        },
        error: function(error) {
            console.log("Error");
            console.log(error);
        },
    });


}
function cargarCMBTipo(){
    var values = {0:"Trasferencia",1:"Cheque",2:"Efectivo",3:"Tarjeta de credito/debito"};
    console.log(values);
    var idtipo = $('#tipopagoid').val();
    var html = "";
    $.each(values, function(i) {

        //Crea el html para ser mostrado
        if (i == parseInt(idtipo)) {
            html +=
                '<option selected value="' +
                i +
                '">' +
                values[i] +
                "</option>";
        } else {
            html +=
            '<option value="' +
            i +
            '">' +
            values[i] +
            "</option>";
        }
        /* console.log(values[i]);
        console.log(i); */
    });
    $("#cmbTipoPag").append(html);
    cargartblmovimientos();
    cargarDetail();

}

function cargartblmovimientos() {
    var idpagos = $('#idpago').val();
    var idprove = $('#proveedorid').val();
    console.log("id "+ idprove);
    let espanol = {
        sProcessing: 'Procesando...',
        sZeroRecords: 'No se encontraron resultados',
        sEmptyTable: 'No hay cuentas pendientes de pago para el proveedor',
        sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
        sLoadingRecords: 'Cargando...',
        searchPlaceholder: 'Buscar...',
        oPaginate: {
            sFirst: 'Primero',
            sLast: 'Último',
            sNext: '<img src="../../img/icons/pagination.svg" width="20px"/>',
            sPrevious: '<img src="../../img/icons/pagination.svg" width="20px" style="transform: scaleX(-1)"/>'
        },
    }

    tablaD = $("#tblmovimientos").DataTable({
        "retrieve": true,
        "destroy": true,
        "paging": true,
        "pageLength": 7,
        "language": espanol,
        "dom": "lfrtip",
        "scrollX": true,
        "lengthChange": false,
        "info": false,
        order: [
            [1, 'asc']
        ],
        "scrollY": "100%",
        "ajax": "functions/get_movimientos.php?pagoid=" + idpagos+"&proveedorid="+idprove,
        "columns": [
            { "data": "Proveedor" },
            { "data": "Folio de Factura" },
            {"data": "Serie de Factura"},
            { "data": "Fecha de Vencimiento" },
            { "data": "Importe" },
            {"data": "Saldo insoluto"},
            { "data": "Estatus" },
            { "data": "Id" },
            { "data": "Seleccionar" ,"width": "10px" },
        ],
        "columnDefs": [
            {
                "targets": [  ],
                "visible": false,
                "searchable": false
            }, 
          ]
    });
}

function Updatecabecera() {
    var idpagos = $('#idpago').val();
    var proveedorid = $('#proveedorid').val();
    var txtfecha = $('#txtfecha').val();
    var tipopagoid = $('#tipopagoid').val();
    var cuentaid = $('#cuentaid').val();
    var txtreferencia = $('#txtreferencia').val();
    var textareaCoemtarios = $('#textareaCoemtarios').val();
    var txtTotal = $('#txtTotal').val();


    $.post("../pagos/functions/Update.php", {
            action: "0",
            idpagos: idpagos,
            proveedorid: proveedorid,
            txtfecha: txtfecha,
            tipopagoid: tipopagoid,
            cuentaid: cuentaid,
            txtreferencia: txtreferencia,
            textareaCoemtarios: textareaCoemtarios,
            txtTotal: txtTotal
        },
        /* Funcion segunda */
        function(data, status) {
            if (status == "success") {
                console.log("tamo bien")
                $('#mdlsavealert').hide();
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "¡Los datos del pago se han actualizado con exito!",
                });


                /* $("#mdlnotifi").modal('show'); */
            } else {
                alert("Algo ha fallado, revisa tus entradas")
            }
        }
    );

}
//Retorna el array de las cuentas que se han pagado en el PAgo y las pone en arID, representa el arreglo de ids
function cargarDetail() {
    /* Optenner el id y el importe de la factura afectada */
    /* $('#editarcp').on('click',function(){ */
    var idpagos = $('#idpago').val();
    let arreglod = [];
    $.ajax({
        type: 'POST',
        url: "../pagos/functions/get_pagadas.php",
        dataType: "json",
        data: { idpagos: idpagos, funcion: "1" },
        success: function(data) {
            if (data.status == 'ok') {
                // Data trae un estring en formato idcp,importe-idcp,imprte
                // Se divide en el - para separa cada factura afectada
                arreglo = data.result.split('-');
               // var array = JSON.parse(data);
               // recorro el arreglo de facturas e inserto en otro arreglo la separacion del primer arreglo en el ',' para separar por valores
                $.each(arreglo,function(i){
                    arreglod[i]=arreglo[i].split(',');
                   // arID [arreglo[0]] = arreglo[1];
                });

                console.log(arreglod);
                //rrecorre arreglod y mete en arID en la posicion con calve de idcp el valor del importe con formato 138: "550.00" 139: "500.00"
                $.each(arreglod,function(i){
                    arID[arreglod[i][0]] = arreglod[i][1];
                    console.log(arreglod[i]);
                });
                //Crea una copia del arreglo que tiene los valores que ya estaban en la base de datos
                //Esto sirve para despues comparar los arreglos y ver que se agrego o eliminó
                arIdOlds = arID.slice();
                
            } else {
                $('.user-content').slideUp();
                $("#alertInvoice").modal("show");
            }
        }
    });

}
//Funcion para ir agregando los value de los checks al el arreglo arID
//Actualmente el id de la cuenta a pagar se agrega como key y el importe como value de esa key, CAMBIAR ESO, Reserva el espaciio de memoria hasta el id de la cuenta en el que va.
function sumar(sender){
  
    inputID= "cmbCuenta";
    invalidDivID = "invalid-cuenta";
    //Saldo de la cuenta seleccionada
    var saldoCuenta = $('select[name='+inputID+'] option').filter(':selected').text();
    saldoCuenta = saldoCuenta.split('$')[1];
    saldoCuenta = parseFloat(saldoCuenta);
    console.log(saldoCuenta);
    imput=document.getElementById('txtTotal');
    //Optiene lo que este en value del check que se le dio click y lo pone en un arreglo separandolo en el coma
    arreglo=sender.getAttribute('value').split(',');
    //Eliomina los espacios de el importe que viene en el value
    cantidad=arreglo[1].replace(/[ ]/g,'');
    sumaTotal=imput.value=parseFloat(imput.value.replace(/[ ]/g,''), 10);
    // Si está check suma la cantidad y lo agrega al arreglo.
    if(sender.checked){
         //Comprueba que el saldo sea suficiente para pagar la nueva factura
         if((sumaTotal+(parseFloat(cantidad, 10)))<saldoCuenta ){
            arID [arreglo[0]] = arreglo[1];
          sumaTotal=sumaTotal + parseFloat(cantidad, 10);
          flagSaldoSuficiente = true;
        }else{
          $(sender).prop('checked',false);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Saldo insuficiente! sumar",
          });
        }
    // Si no, lo resta y lo elimina del arreglo.
    }else{
      //cantidad=arreglo[2].replace(/[ ]/g,'');
        var key = arreglo[0];
        delete arID[key];
        sumaTotal=sumaTotal - parseFloat(cantidad, 10);
    }  
    //Pone el total en el imput
    imput.value=" "+sumaTotal.toLocaleString("en-EU").replace(/[,]/g,' ');
    
}
function constructArrays(){
    let stringJason= "";
    if(!($.isEmptyObject(arID))){
        arIdOlds.forEach(function(element, index, array){
            console.log(index);
            aridonlyolds.push(index);
        });
        arID.forEach(function(element, index, array){
            console.log(index);
            aridonly.push(index);
        });
        console.log(aridonly);
        console.log((aridonlyolds));
        aridonlyolds.forEach(function(element,index,array){
            let incluye = aridonly.includes(element);
            if(incluye==true){
                delete arID[element];
                delete arIdOlds[element];
            }
            console.log(incluye);
        });
        update();
        console.log(arIdOlds);
        console.log(arID);
    }else{
        Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Ninguna cuenta por pagar seleccionada!",
          });
    }
    
    
/*     $.each(arIdOlds,function(i){
        
        console.log(incluye);
    }); */
}
function update(){
    stringToInsert = "";
    stringToDelete = "";
    var cobroOpago = 1;
    var idpagos = $('#idpago').val();
    var proveedorid = $('#proveedorid').val();
    var txtfecha = $('#txtfecha').val();
    var tipopagoid = $('select[name=cmbTipoPag] option').filter(':selected').val();
    var cuentaid = $('#cmbCuenta option:selected').val();
    console.log("Select "+$('#cmbCuenta option:selected').val());
    var txtreferencia = $('#txtreferencia').val();
    var textareaCoemtarios = $('#textareaCoemtarios').val();
    var txtTotal = $('#txtTotal').val();


        if(!($.isEmptyObject(arID))){
            arID.forEach(function(movimiento,index){
                stringToInsert = stringToInsert+=index+"-"+movimiento+",";
            });
            stringToInsert= stringToInsert.substring(0, stringToInsert.length - 1);
        }else{
            stringToInsert = null;
        }
        if(!($.isEmptyObject(arIdOlds))){
            arIdOlds.forEach(function(movimiento,index){
                stringToDelete = stringToDelete+=index+"-"+movimiento+",";
            });
            stringToDelete= stringToDelete.substring(0, stringToDelete.length - 1);
        }else{
            stringToDelete = null;
        }    


        $.ajax({
            type: 'POST',
            url: "functions/addcontroller.php",
            dataType: "json",
            data: { 
                clase: "update_data",
                funcion: "update_detail" ,

                idpagos:idpagos,
                cobroOpago:cobroOpago,
                tipopagoid:tipopagoid,
                txtTotal:txtTotal,
                txtfecha:txtfecha,

                proveedorid:proveedorid,
                txtreferencia:txtreferencia,
                //FKResponsable
                stringToInsert:stringToInsert,
                //CountarraytoInser
                stringToDelete:stringToDelete,
                //CountarrayToDElete
                textareaCoemtarios:textareaCoemtarios,
                cuentaid:cuentaid,
                cuentaDest: null},
            success: function(data) {
                console.log("Exito al update: ", data);
                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 1000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Pago actualizado!",
                  });
                  setTimeout(function(){ window.location="../pagos"; }, 200);
            },
            error: function(error) {
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
                console.log("Error");
                console.log(error);
            },
        });
       

}
/* function deletePago(idPago, origen){
    console.log("Eliminando: "+ idPago);
    $.ajax({
      type:'POST',
      url: "functions/addcontroller.php",
      dataType: "json",
      data: { clase:"delete_data",funcion:"delete_pago", idpagos:idPago, _origen:origen},
      success: function (data) {
        console.log("Dato eliminado: ", data);
        $().redirect('../pagos/index.php', {
          'notifi': "3"
          });
      //  setTimeout(function(){ window.location= '../pagos';}, 1500);
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
  
} */

