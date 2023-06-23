var arID = [];
let string="";
let stringtamaño;
let stringSincoma;
var jsonString;
function tests(){

}

/* var txtfecha = document.getElementById('txtfecha').value;
var txtdescrip = arreglo[0]+"->"+arreglo[1];
var txtreferencia = document.getElementById('txtreferencia').value;
var txtorigen = $('select[name=cmbCuenta] option').filter(':selected').val();


$.ajax({
  url: "functions/addcontroller.php",
  data: { 
    clase: "save_datas",
    funcion: "insert_mov_temp",

   
    fecha: txtfecha,
    descripcion: txtdescrip,
    importe: arreglo[1],
    referencia:txtreferencia,
    cuenta_origen: txtorigen,
    id_cuenta_pagar: arreglo[0],
    ramdon: r
  },
  dataType: "json",
  success: function (data,response) {
    console.log("data de cabecera: "+ data);
    if (data[0]!="E") {
      console.log("Respuesta 0 "+data);
      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: "¡Pago registrado con exito!",
      });
    } else {
      console.log("Error");
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
}) */
//Funcion para ir agregando los value de los checks al el arreglo arID
function sumar(sender){
  
  imput=document.getElementById('txtTotal');
  //Optiene lo que este en value del check que se le dio click y lo pone en un arreglo separandolo en el coma
  arreglo=sender.getAttribute('value').split(',');
  //Eliomina los espacios de el importe que viene en el value
  cantidad=arreglo[1].replace(/[ ]/g,'');
  sumaTotal=imput.value=parseFloat(imput.value.replace(/[ ]/g,''), 10);
  // Si está check suma la cantidad y lo agrega al arreglo.
  if(sender.checked){
        arID [arreglo[0]] = arreglo[1];
       sumaTotal=sumaTotal + parseFloat(cantidad, 10);
  // Si no, lo resta y lo elimina del arreglo.
  }else{
      var key = arreglo[0];
      delete arID[key];
      sumaTotal=sumaTotal - parseFloat(cantidad, 10);
  }  
  //Pone el total en el imput
  imput.value=" "+sumaTotal.toLocaleString("en-EU").replace(/[,]/g,' ');
  
}
//Guardar en un arreglo los ids de los checkbox de las cuentas por pagar
function countChecks(id){
  var totale = 0;
	$("input:checkbox").change(function() {
  	ar.length=0;
    $(".check").each ( function() {
    	if ($(this).is(':checked')) {
        var separada = ($(this).val()).split(",");
      	ar.push(separada);
      }
    });
    //alert(JSON.stringify(ar));
    //ar contiene el valor de 
    jason = Object.assign({}, ar);
    for (const property in jason) {
      var total = `${property}: ${jason[property]}`;
      total = total.split(",");
      console.log(`${property}: ${jason[property]}`);
      totale += parseFloat(total[1]);

    }
    console.log(totale);
    $("#txtTotal").val(totale);
  });
  

  console.log("ar: " + ar);


/*   $.each(jason, function(key,value){
    console.log(jason);
    console.log("Valores "+ jas)
    console.log("Cuenta " + key + " | ID: " + value[key] + " IMPORTE: " + value.[key]);
  }); */
/*   jason.forEach(function(jason, index) {
    console.log("Cuenta " + index + " | ID: " + jason[0] + " IMPORTE: " + jason.id);
  }); */
  return ar;
}
$(document).ready(function(){
  


  //Saca los ids de los checks en true
  //console.log(JSON.stringify(countChecks()))

  $("#btnguardarDetalle").click(function(){
    validarImputs();
});
 
 
  crearSelects();
  filtroProveedorTabla();
  cargarCabeceras();
  cargarhisto();
  cargarCMBProveedor();

  //Inicializar los tooltip
$('[data-toggle="tooltip"]').tooltip({
      //Para que desaparescan cuando se sale del elemento
      trigger : 'hover'
})

function validarImputs(){
  redFlag1 = 0;
  redFlag2 = 0;
  redFlag3 = 0;
  redFlag4 = 0;
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
  inputID= "txtreferencia";
  invalidDivID = "invalid-reference";
  if (!$("#" + inputID).val()) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag4= 1;
  }
  if((redFlag1==1)&&(redFlag2==1) && (redFlag3==1)&& (redFlag4==1)){
    saveAll();
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

function crearSelects(){
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
  /* Verificar si el valor de un input cambio para activar el boton guardar */
$('.edit').each(function() {
    var elem = $(this);
 
    // Save current value of element
    elem.data('oldVal', elem.val());
 
    // Look for changes in the value
    elem.bind("propertychange change keyup input paste", function(event){
       // If value has changed...
       if (subtotal != elem.val()) {
        // Updated stored value
          elem.data('oldVal', elem.val());
          //console.log(subtotal)
          //console.log(elem.val())
          $("#btnguardarDetalle").removeAttr('disabled');
          $("#btnguardarDetalle").removeAttr('style');
          $("#spanbutton").tooltip('disable');
          
        // Do action
        }else{
          $("#btnguardarDetalle").attr('disabled');
        }
    });
});

    ///
    //// Funcion para editar en el modal
    ///
function UpdateUserDetails() {
    // Optener Valores del modal
    var inputPrecio = $("#inputPrecio").val();
    var inputDescuento = $("#inputDescuento").val();
    var inputIva = $("#inputIva").val();
    var inputCantidad = $("#inputCantidad").val();
    var inputIeps = $("#inputIeps").val();

    // Optener el valor coculto del id modal 
    var id = $("#hidden_user_id").val();

    // Mandar los datos en un POST a el archivo UPdate.php a actualizar
    $.post("../cuentas_pagar/functions/Update.php", {
            action: "0",
            id: id,
            inputPrecio: inputPrecio,
            inputDescuento: inputDescuento,
            inputIva: inputIva,
            inputCantidad: inputCantidad,
            inputIeps: inputIeps,
        },
        ///Funcion de data
        // Data trae los datos del Update.php y status el estado de la consulta.
        function (data, status) {
            // hide modal popup
            alert("Data: " + data + "\nStatus: " + status);
            $("#modaldcp").modal("hide");
            console.log( inputPrecio );
        }
    );
}
  ///
  //// FUncion para editar los datos de la cabecera 
  ///
function Updatecabecera(){
    var inputSubtotal = $('#subtotal').val();
    var inmputImporte = $('#txtimporte').val();
    var inputIva = $('#_txtiva').val();
    var inmputIeps = $('#_txtieps').val();

    var id = $("#user_id").val();

    $.post("../cuentas_pagar/functions/Update.php", {
      action: "1",
      id: id,
      inputSubtotal: inputSubtotal,
      inmputImporte: inmputImporte,
      inputIva: inputIva,
      inmputIeps: inmputIeps,
  },
  /* Funcion segunda */
  function (data, status) {
    if(status=="success"){
      console.log("tamo bien")
      $('#mdlsavealert').hide();
      window.history.back();
      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: "¡Los datos de la cuenta por pagar se han actualizado con exito!",
      });
        
      
      /* $("#mdlnotifi").modal('show'); */
    }else{
      alert("Algo ha fallado, revisa tus entradas")
    }
  }
);

  }

    ///Llamada a la funcion con los clicks
    $("#enviar").click(UpdateUserDetails);
    $("#btnAcepCambios").click(Updatecabecera);

    //Funcion para recuperar los proveedores de la empresa y ponerlos en el select
function cargarCMBProveedor() {
  /* $("#cmbProveedor").prop("disabled", true); */
  /* $("#chkCategoria").prop("disabled", true); */
  var html = "";
  //Consulta los proveedores de la empresa
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_proveedorCombo"},
    success: function (data) {
      console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (i == 0) {
            html +=
            '<option value="' +
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
      var table = $('#tblcuentas').DataTable();
      $('input[type="search"]').val($("#cmbProveedor option:selected").text());
      table
          .search($("#cmbProveedor option:selected").text())
          .draw();
      cargarCMBCuentas();
      //cargarProductosEmpresa();
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });


}
function cargarCMBCuentas() {
  /* $("#cmbProveedor").prop("disabled", true); */
  /* $("#chkCategoria").prop("disabled", true); */
  var html = "";
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_cuenta"},
    success: function (data) {
      console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (i == 0) {
          html +=
            '<option disabled value="f" selected>Seleccione una cuenta</option>';
          html +=
            '<option value="' +
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
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });

  
}

function savePagos(){

  let redFlag;


  if(arID.length>0){
    let ramdon = r;
    let tipoPago = $('select[name=cmbTipoPag] option').filter(':selected').val();
    var Comentarios = $("#textareaCoemtarios").val();
    var total = $("#txtTotal").val();
    $.ajax({
      url: "functions/addcontroller.php",
      data: { 
        clase: "save_datas",
        funcion: "insert",
        tipoPago: tipoPago,
        Comentarios: Comentarios,
        total: total,
        ramdon_str:ramdon
      },
      dataType: "json",
      success: function (data,response) {
        console.log("data de cabecera: "+ data);
        if (response[0]) {
          console.log("Respuesta 0 "+response[0]);
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Pago registrado con exito!",
          });
          var txtdescrip = arreglo[0]+"->"+arreglo[1];
          var txtreferencia = document.getElementById('txtreferencia').value;
          var origenCE = $('select[name=cmbCuenta] option').filter(':selected').val();
          let tiopo_movimi = 5;

          arID.forEach(function(importe,index){
            inserMov(txtdescrip,importe,txtreferencia,tiopo_movimi,origenCE,index,ramdon);
          });
        } else {
          console.log("Error");
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
  //Si no se selecciona ninguna cuenta por pagar
  }else{
    console.log("Ninguna Cuenta Por Pagar Seleccionada");
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Seleccione al menos una cuenta por pagar!",
    });
  }
  
}

function inserMov(txtdescrip,importe,txtreferencia,tiopo_movimi,origenCE,index,ramdon) {
  
  if (tiopo_movimi!="" && txtreferencia!="" && txtdescrip!="" && importe!=""&& origenCE!=""&& index!=""&& ramdon!=""){
    $.ajax({
      url: "functions/addcontroller.php",
      data: { 
        clase: "save_datas",
        funcion: "insert_mov",
        _descripcion: txtdescrip,
        _referencia: txtreferencia,
        _importe: importe,
        _tipoMovimiento: tiopo_movimi,
        _origen: origenCE,
        _destino: index,
        _ramdon_str:ramdon
      },
      dataType: "json",
      success: function (data,response) {
        console.log("data de cabecera: "+ data);
        if (data[0]!="E") {
          console.log("Respuesta 0 "+data);
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Cuenta por pagar saldada! " + index,
          });
        } else {
          console.log("Error");
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal con la cuenta por pagar! "+ index,
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

}
//Guarda la cabecera con sus detalles, los detalles son movimientos que son las cuentas que se vana apagar
function saveAll(){
 // alert(arID.length);
  //Comprueba que aya alguna cuenta seleccionada para pagar
  if(!($.isEmptyObject(arID))){
    //Cuenta por cobarar se manda vacia ya que yo no la necesito
    let _cuentaCobrar = "";

    /// Codigo para pasar el array de values de checks a un string 
    /// Recorre el arreglo de valores del check y lo concatena en un string con formato: clave-valor,clave,valor
      arID.forEach(function(movimiento,index){
        string = string+=index+"-"+movimiento+",";
      });
      //Le quita el ultimo coma a la cadena.
      let _cadena_CP = string.substring(0, string.length - 1);
      //Restaura la cadena a vacio para las proximas insercciones.
      string = "";
      // sacan los valores de la pantalla y asigna las variables para el ajax
    let _proveedor = $('select[name=cmbProveedor] option').filter(':selected').val();
    let tipoPago = $('select[name=cmbTipoPag] option').filter(':selected').val();
    let Comentarios = $("#textareaCoemtarios").val();
    let total = $("#txtTotal").val();
    let _referencia = $("#txtreferencia").val();
    let tipo_movi = 1;
    let _origenCE = $('select[name=cmbCuenta] option').filter(':selected').val();
    let _cuentaDest = 300;

      //Ajax que manda los parametros para el procedimiento almacenado spc_tablaDetalle_cuentasCobrar
    $.ajax({
      url: "functions/addcontroller.php",
      data: { 
        clase: "save_datas",
        funcion: "insert_all",
        _proveedor: _proveedor,
        _referencia: _referencia,
        _cuentaCobrar: _cuentaCobrar,
        _cadena_CP: _cadena_CP,
        tipoPago: tipoPago,
        Comentarios:Comentarios,
        total:total,
        tipo_movi:tipo_movi,
        _origenCE:_origenCE,
        _cuentaDest:_cuentaDest
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
          //setTimeout(function(){ window.location= '../pagos';}, 1500);
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
  //Si no se selecciona ninguna cuenta por pagar
  }else{
    console.log("Ninguna Cuenta Por Pagar Seleccionada");
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Seleccione al menos una cuenta por pagar!",
    });
  }
}

//Funcion para cargar la tabla de cuentass por pagar
function cargarhisto(){
    let espanol = {
      sProcessing: 'Procesando...',
      sZeroRecords: 'No se encontraron resultados',
      sEmptyTable: 'Ningún dato disponible en esta tabla',
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
  
  tablaD= $("#tblcuentas").DataTable({
      "retrieve": true,
      "destroy": true,
        "paging":true,
        "pageLength": 7,
        "language": espanol,
        buttons: [{
            extend: "excelHtml5",
            text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
            className: "excelDataTableButton",
            titleAttr: "Excel",
          },          
        ],
        "dom": "Blfrtip",
        "scrollX": true,
        "lengthChange": false,
        "info": false,
        order: [[ 1, 'asc' ]],
        "scrollY": "100%",
        "ajax":"functions/get_cuentas.php",
        "columns": [
        {"data": "Proveedor"},
        {"data": "Folio de Factura"},
        {"data": "Fecha de Vencimiento"},
        {"data": "Importe"},
        {"data": "Estatus"},
        {"data": "Id"},
        {"data": "Acciones"},
        ],
      });
}

//Metodo para aplicar filtro a la tabla cada que se cambia de proveedor
function filtroProveedorTabla(){
  $(document).on('change', '#cmbProveedor', function(event) {
    //Descheck a todos los checkbox cuando se cambia de proveedor
    $( ".check" ).prop( "checked", false );
    var table = $('#tblcuentas').DataTable();
    console.log((($("#cmbProveedor option:selected").val())=="f"));
    if(($("#cmbProveedor option:selected").val())=="0"){
      $('input[type="search"]').val("");
      $('#servicioSelecionado').val($("#servicio option:selected").text());
      table
          .search($("").text())
          .draw();
    }else{
      $('input[type="search"]').val($("#cmbProveedor option:selected").text());
      $('#servicioSelecionado').val($("#servicio option:selected").text());
      table
          .search($("#cmbProveedor option:selected").text())
          .draw();
    }
  });
}

function cargarCabeceras(){
  var f = new Date();
  //document.write(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
  $('#txtfecha').val(f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear());
  console.log(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
}
  /* Optenemos los valores de la cuenta por pagar y los ponemos en los campos de la pantalla editar */
      /* $('#editarcp').on('click',function(){ */
function showheader(){
  var user_id = $('#user_id').val();
  $.ajax({
      type:'POST',
      url:'../cuentas_pagar/functions/get_ajax.php',
      dataType: "json",
      data:{user_id:user_id,funcion: "1"},
      success:function(data){
          if(data.status == 'ok'){
              $('#nombre').val(data.result.NombreComercial);
              $('#txtfolio').val(data.result.folio_factura);
              $('#txtserie').val(data.result.num_serie_factura);
              $('#subtotal').val(data.result.subtotal);
              $('#txtimporte').val(data.result.importe);
              $('#_txtiva').val(data.result.iva);
              $('#_txtieps').val(data.result.ieps);
              /* $('#txtimporte').val(Intl.NumberFormat("es-MX").format(data.result.importe)); */
              $('#txtfechaF').val(data.result.fecha_factura);
              $('#txtfechaV').val(data.result.fecha_vencimiento);
          }else{
              $('.user-content').slideUp();
              alert("User not found...");
          } 
      }
  });
}

});

