var arID = [];
var objFacturas = {};
let string="";
let stringtamaño;
let stringSincoma;
var jsonString;
var tablaP;
var Total;
var objFacturas_Insolutos= {};
var flagSaldoSuficiente;
var tablaD = false;
var pagoLibre = false;
var selectProveedor;
var cmbTipoPago;
var flagOpenModal = false; //bandera para evitar abrir el modal de facturas cuando se redireccionó desde cuentas por pagar
function tests(){
  Object.entries(objFacturas).forEach(([index, movimiento]) => {
    string = string+=index+"-"+movimiento+",";
  });
  let _cadena_CP = string.substring(0, string.length - 1);
  string = "";
 //  console.log(_cadena_CP);
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

 
  inputID= "cmbCuenta";
  invalidDivID = "invalid-cuenta";
  //Saldo de la cuenta seleccionada
  var saldoCuenta = $('select[name='+inputID+'] option').filter(':selected').text();
  saldoCuenta = saldoCuenta.split('$')[1];
  saldoCuenta = parseFloat(saldoCuenta);
  // console.log(saldoCuenta);
  //Comprueba si se selecciono una cueta antes de seleccionar que va a pagar para comprobar que se tenga saldo suficiente.
  if (($('select[name='+inputID+'] option').filter(':selected').val())=="f") {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("Seleccione una cuenta con saldo suficiente");
    //Des selecciona el check que se habia marcado
    $(sender).prop('checked',false);
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Selecciona una cuenta con saldo suficiente!",
    });
    $("select[name=cmbCuenta]").css("background-color", "blue");
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);

    imput=document.getElementById('txtTotal');
    //Optiene lo que este en value del check que se le dio click y lo pone en un arreglo separandolo en el coma
    arreglo=sender.getAttribute('value').split(',');
    //Eliomina los espacios de el importe que viene en el value
    cantidad=arreglo[1].replace(/[ ]/g,'');
    sumaTotal=imput.value=parseFloat(imput.value.replace(/[ ]/g,''), 10);
   //  console.log(sumaTotal);
    
      // Si está check suma la cantidad y lo agrega al arreglo.
      if(sender.checked){
        //Comprueba que el saldo sea suficiente para pagar la nueva factura
        if((sumaTotal+(parseFloat(cantidad, 10)))<saldoCuenta ){
            Arid[arreglo[0]] = [arreglo[1]];
            arID [arreglo[0]] = arreglo[1];
          sumaTotal=sumaTotal + parseFloat(cantidad, 10);
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
            msg: "¡Saldo insuficiente!",
          });
        }
      // Si no, lo resta y lo elimina del arreglo.
      }else{
          var key = arreglo[0];
          delete arID[key];
          sumaTotal=sumaTotal - parseFloat(cantidad, 10);
      }  
      //Pone el total en el imput
      imput.value=" "+sumaTotal.toLocaleString("en-EU").replace(/[,]/g,' ');
      Total = sumaTotal;
    
  }
  
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
    //   console.log(`${property}: ${jason[property]}`);
      totale += parseFloat(total[1]);

    }
  //   console.log(totale);
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
function click(select){
  alert("Ay!");
}
$(document).ready(function(){


  
  var valorx;
  $('select[name=cmbCuenta]').focus(function() {
    alert("Ay!");
    valorx = $("#cmbCuenta").val();
  });




  //Comprobar el saldo cada que se cambia de cuenta
  $('#cmbCuenta').on('change', function() {
    inputID= "cmbCuenta";
    invalidDivID = "invalid-cuenta";
    imput=document.getElementById('txtTotal');
    sumaTotal = 0;
    if(imput.value){
      sumaTotal=imput.value=parseFloat(imput.value.replace(/[ ]/g,''), 10);
    }
    console.log(imput.value);
    if(isNaN(imput.value)){
      sumaTotal = 0;
    }
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
        msg: "¡Saldo insuficiente!",
      });
      //Bandera para saber si el saldo de la cuenta no es suficiente a la hora de guardar
      flagSaldoSuficiente = false;
      $('select[name='+inputID+'] option[value=f]').attr('selected',true);
    }
    //alert( saldoCuenta );
  });
  total = 0;

  //Saca los ids de los checks en true
  //console.log(JSON.stringify(countChecks()))

  $("#btnguardarDetalle").click(function(){
    if(!$("#invalid-input").is(':visible')){
      validarImputs();          
    }
    //
  });

  $("#btnAgregarFacturas").click(function(){
    passSelected();
  });
  $("#modalshow").click(function(){
    $("#txtTotal").attr("disabled", true);
    $("#agregarPago").attr("disabled", true);
    cuentas = cuentas_Copy.slice();
   //  console.info(cuentas_Copy);
    if(tablaD){
      var hiddenRows = tablaD.rows().nodes();
        $("input[type='checkbox']", hiddenRows).prop('checked', false); 
        if(!$.isEmptyObject(cuentas_Copy)){
          cuentas_Copy.forEach((index,id) => {
            $("#"+index , hiddenRows).prop( "checked", true );
           // console.log((id+"-"+ index));
          });
        }
    }
    
  });
  $( ".check" ).blur(function() {
    alert( "Handler for .blur() called." );
  });
  $('#mod_agregarFacturas').on('shown.bs.modal', loadModal);
  $("#modalshow").click(function(){
    loadModal();
  });
 
  crearSelects();
  filtroProveedorTabla();
  cargarCabeceras();
  cargarhisto();

  cargarCMBCategorias('');
  

  //Inicializar los tooltip
$('[data-toggle="tooltip"]').tooltip({
      //Para que desaparescan cuando se sale del elemento
      trigger : 'hover'
})

$(document).on("change","#cmbCategoriaCuenta",(e)=>{
  var subCat = e.target.value;
  $("#cmbSubcategoriaCuenta").html('');
  cargarCMBSubcategorias(subCat,'');
});
function Posible(){
  //estructura de la cadena enviada a la base de datos para almacenar (id factura - importe pagado, id factura - importe pagado)
  let validIn = false;
  let _origenCE = $('select[name=cmbCuenta] option').filter(':selected').val();
  let _origenCE_text = $('select[name=cmbCuenta] option').filter(':selected').text();
  if(!($.isEmptyObject(objFacturas_Insolutos))){

    let string1 = "";
    Object.entries(objFacturas_Insolutos).forEach(([index, movimiento]) => {
      string1 = string1+=index+"-"+movimiento+",";
    });
    //Le quita el ultimo coma a la cadena.
    let _cadena_CP_insolutos = string1.substring(0, string1.length - 1);

    //Crea la cadena de id-valor,id-valor,
    let string2 = "";
    Object.entries(objFacturas).forEach(([index, movimiento]) => {
      string2 = string2+=index+"-"+movimiento+",";
    });
    //Le quita el ultimo coma a la cadena.
    let _cadena_CP = string2.substring(0, string2.length - 1);
    let flag = true;
      //////// Posibles Retornos del AJAX/////////
      //iguales: 1, ID_Diferente: 0, suficiente: 1, nuevolimite: null -> Si todo esta correcto.
      //iguales: 0, ID_Diferente: 194, suficiente: 1, nuevolimite: '9.00' -> Si cambio el saldo insoluto en lo que se guardan los cambios 
      //iguales: 0, ID_Diferente: 194, suficiente: 0, nuevolimite: '9.00' -> Si el saldo de la cuenta ya no es suficiente para pagar el total.
      $.ajax({
        type:'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        async: false,
        data: { clase:"get_data",funcion:"Validate_importeAnticipos",origen:_origenCE, _cadena_CP:_cadena_CP, _cadena_CP_insolutos:_cadena_CP_insolutos},
        success: function (data) {
         //  console.log("Posible?: ", data);
          $.each(data, function (i) {
            if(data[i].iguales == 0){
            flag = false;
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Parece que el saldo insoluto de una factura ha cambiado ahora es: $"+data[i].nuevolimite +" !",
              });
              passSelected();
              objFacturas_Insolutos[data[i].ID_Diferente] = data[i].nuevolimite;
            }
            if(data[i].suficiente == 0){
              flag = false;
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡La cuenta de origen seleccionada ya no tiene saldo suficiente!",
              });
            }
          });
         //  console.log(_cadena_CP);
          if(flag){
            saveAll();
          }
        },
        error: function (error) {
          flag = false;
          console.log("Error");
          console.log(error);
        },
      });
  }
}


function PosiblePagoLibre(){
  //estructura de la cadena enviada a la base de datos para almacenar (id factura - importe pagado, id factura - importe pagado)
  /* let validIn = false; */
  let _origenCE = $('select[name=cmbCuenta] option').filter(':selected').val();
  let total = $("#cantidadPagoLibre").val();
  /* let _origenCE_text = $('select[name=cmbCuenta] option').filter(':selected').text(); */
  /* if(!($.isEmptyObject(objFacturas_Insolutos))){ */

    /* let string1 = "";
    Object.entries(objFacturas_Insolutos).forEach(([index, movimiento]) => {
      string1 = string1+=index+"-"+movimiento+",";
    }); */
    //Le quita el ultimo coma a la cadena.
    /* let _cadena_CP_insolutos = string1.substring(0, string1.length - 1); */

    //Crea la cadena de id-valor,id-valor,
   /*  let string2 = "";
    Object.entries(objFacturas).forEach(([index, movimiento]) => {
      string2 = string2+=index+"-"+movimiento+",";
    }); */
    //Le quita el ultimo coma a la cadena.
    /* let _cadena_CP = string2.substring(0, string2.length - 1); */
    let flag = true;
      $.ajax({
        type:'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        async: false,
        data: { clase:"get_data",funcion:"Validate_importePagoLibre",origen:_origenCE, total:total},
        success: function (data) {
         //  console.log("Posible?: ", data);
          $.each(data, function (i) {
            if(data[i].suficiente == 0){
              flag = false;
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡La cuenta de origen seleccionada ya no tiene saldo suficiente!",
              });
            }
          });
         //  console.log(_cadena_CP);
          if(flag){
            saveAllPagolibre();
          }
        },
        error: function (error) {
          flag = false;
          console.log("Error");
          console.log(error);
        },
      });
  /* } */
}

function validarImputs(){
  redFlag1 = 0;
  redFlag2 = 0;
  redFlag3 = 0;
  redFlag4 = 0;
  redFlag5 = 0;
  redFlag6 = 0;
  redFlag7 = 0;
  redFlag8 = 0;
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
  inputID= "txtfecha";
  invalidDivID = "invalid-fecha";
  if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("La fecha no puede estar vacia");
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text("La fecha no puede estar vacia");
    redFlag5 = 1;
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
  inputID= "cmbCategoriaCuenta";
  invalidDivID = "invalid-categoriaCuenta";
  if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null) || ($('#'+inputID).val()==0) || ($('#'+inputID).val()=='0')) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag7 = 1;
  }
  inputID= "cmbSubcategoriaCuenta";
  invalidDivID = "invalid-subcategoriaCuenta";
  if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null) || ($('#'+inputID).val()==0) || ($('#'+inputID).val()=='0')) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag8 = 1;
  }
  if((redFlag1==1)&&(redFlag2==1) && (redFlag3==1) && (redFlag5==1) && (redFlag6==1) && (redFlag7 == 1) && (redFlag8 == 1)){
    if(pagoLibre == true){
      PosiblePagoLibre();
    }else{
      Posible();
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
      msg: "¡Faltan algunos campos requeridos!",
    });
  }
  
}

document.getElementById('cmbCategoriaCuenta').addEventListener("change",(e)=>{
    const target = e.target;
    const target1 = document.getElementById('invalid-categoriaCuenta');
    const target2 = document.getElementById('cmbSubcategoriaCuenta');
    const target3 = document.getElementById('invalid-subcategoriaCuenta');
    if(target.classList.contains('is-invalid')){
        target.classList.remove('is-invalid');
        target1.style.display = "none";
    }
    if(target2.classList.contains('is-invalid')){
        target2.classList.remove('is-invalid');
        target3.style.display = "none";
    }
});

document.getElementById('cmbSubcategoriaCuenta').addEventListener("change",(e)=>{
    const target = e.target;
    const target1 = document.getElementById('invalid-subcategoriaCuenta');
    if(target.classList.contains('is-invalid')){
        target.classList.remove('is-invalid');
        target1.style.display = "none";
    }
    
});

function crearSelects(){
  selectProveedor = new SlimSelect({
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
  cmbTipoPago = new SlimSelect({
    select: '#cmbTipoPago', 
    deselectLabel: '<span class="">✖</span>'
  })
  cmbCategoriaCuenta = new SlimSelect({
    select: '#cmbCategoriaCuenta', 
    deselectLabel: '<span class="">✖</span>'
  })
  cmbSubcategoriaCuenta = new SlimSelect({
    select: '#cmbSubcategoriaCuenta', 
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
           //  console.log( inputPrecio );
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
     //  console.log("tamo bien")
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
  return new Promise((resolve)=>{
    //here our function should be implemented 
    var html = "";
    html += '<option disabled value="f" selected hidden>Seleccione un proveedor</option>';
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/addcontroller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_proveedorCombo"},
      success: function (data) {
        // console.log("data de proveedor: ", data);
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
        if ($("#provee").attr('value')!=undefined){
          cambiarProveedor();
        }
        resolve();
       
        //Aplica el primer filtro con el proveedor primero
       // var table = $('#tblcuentas').DataTable();
       /*  $('input[type="search"]').val($("#cmbProveedor option:selected").text());
        table
            .search($("#cmbProveedor option:selected").text())
            .draw();*/
             
        //cargarProductosEmpresa();
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    });
    cargarCMBcuentasOtros();
    
});
 


}
var html = "";
function cargarCMBcuentasCheques(){
  return new Promise((resolve)=>{
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_cuenta_cheque"},
    success: function (data) {
     //  console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (i == 0) {
          html += '<option disabled value="f" selected hidden>Seleccione una cuenta</option>';
          html +=
            '<optgroup label="Cheques">';
          html +=
            '<option value="' +
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
  //cargarCMBcuentasOtros();
}
async function cargarCMBcuentasOtros(tamaño){
  await cargarCMBcuentasCheques();
  var htmlO = "";
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_cuenta_otras"},
    success: function (data) {
     //  console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (html=="") {
          htmlO +=
            '<optgroup label="Otras"> <option disabled value="f" selected>Seleccione una cuenta</option>';
          htmlO +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +": $"+ data[i].saldo_actual+
            "</option>";
        }else if(i == 0){
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
    //   console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (i == 0) {
          html +=
            '<option disabled value="f" selected>Seleccione una cuenta</option>';
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +" => $1600"+
            "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +" => $1600"+
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
       //  console.log("data de cabecera: "+ data);
        if (response[0]) {
        //   console.log("Respuesta 0 "+response[0]);
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
      //   console.log("data de cabecera: "+ data);
        if (data[0]!="E") {
         //  console.log("Respuesta 0 "+data);
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
 
 //Si la bandera saldo es false (insuficiente)
 if(flagSaldoSuficiente){
  //Comprueba que aya alguna cuenta seleccionada para pagar
    if(!($.isEmptyObject(objFacturas))){
      //Cuenta por cobarar se manda vacia ya que yo no la necesito
      let _cuentaCobrar = "";

      /// Codigo para pasar el array de values de checks a un string 
      /// Recorre el arreglo de valores del check y lo concatena en un string con formato: clave-valor,clave,valor
      Object.entries(objFacturas).forEach(([index, movimiento]) => {
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
      let total = $("#total").text();
      let _referencia = $("#txtreferencia").val();
      let tipo_movi = 1;
      let _origenCE = $('select[name=cmbCuenta] option').filter(':selected').val();
      let _cuentaDest = 300;
      let _Forma_pago_sat = 0;
      var _fecha_pago = $('#txtfecha').val();
      var _categoria = $("#cmbCategoriaCuenta").val();
      var _subcategoria = $("#cmbSubcategoriaCuenta").val();
      //let _fecha_pago = "2021/09/10";

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
          _cuentaDest:_cuentaDest,
          _Forma_pago_sat: _Forma_pago_sat,
          _fecha_pago:_fecha_pago,
          _categoria: _categoria,
          _subcategoria: _subcategoria
        },
        dataType: "json",
        success: function (data,response) {
            console.log(data,response);
         //  console.log("data de cabecera: "+ data);
          // Si se recibio un error 
          if (response[0]!="E") {
           //  console.log("Respuesta 0 "+ data);
            //Redirecciona con variable POST
           setTimeout(function(){ window.location= '../pagos';}, 200);
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
    //Si la bandera del saldo es false (insuficiente)
  }else{
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Saldo insuficiente en la cuenta seleccionada!",
    });
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("Saldo Insuficiente");
  }
}

function saveAllPagolibre(){
  // alert(arID.length);
  
  //Si la bandera saldo es false (insuficiente)
  if(flagSaldoSuficiente){
   //Comprueba que aya alguna cuenta seleccionada para pagar
     /* if(!($.isEmptyObject(objFacturas))){ */
       //Cuenta por cobarar se manda vacia ya que yo no la necesito
       let _cuentaCobrar = "";
 
       /// Codigo para pasar el array de values de checks a un string 
       /// Recorre el arreglo de valores del check y lo concatena en un string con formato: clave-valor,clave,valor
       /* Object.entries(objFacturas).forEach(([index, movimiento]) => {
         string = string+=index+"-"+movimiento+",";
       }); */
         //Le quita el ultimo coma a la cadena.
         /* let _cadena_CP = string.substring(0, string.length - 1); */
         //Restaura la cadena a vacio para las proximas insercciones.
         /* string = ""; */
         // sacan los valores de la pantalla y asigna las variables para el ajax
       let _proveedor = $('select[name=cmbProveedor] option').filter(':selected').val();
       let tipoPago = $('select[name=cmbTipoPag] option').filter(':selected').val();
       let Comentarios = $("#textareaCoemtarios").val();
       let total = $("#cantidadPagoLibre").val();
       let _referencia = $("#txtreferencia").val();
       let tipo_movi = 1;
       let _origenCE = $('select[name=cmbCuenta] option').filter(':selected').val();
       let _cuentaDest = 300;
       let _Forma_pago_sat = 0;
       var _fecha_pago = $('#txtfecha').val();
       var _categoria = $("#cmbCategoriaCuenta").val();
       var _subcategoria = $("#cmbSubcategoriaCuenta").val();
       //let _fecha_pago = "2021/09/10";
 
         //Ajax que manda los parametros para el procedimiento almacenado spc_tablaDetalle_cuentasCobrar
       $.ajax({
         url: "functions/addcontroller.php",
         data: { 
           clase: "save_datas",
           funcion: "insert_pago_libre",
           _proveedor: _proveedor,
           _referencia: _referencia,
           _cuentaCobrar: _cuentaCobrar,
           _cadena_CP: "",
           tipoPago: tipoPago,
           Comentarios:Comentarios,
           total:total,
           tipo_movi:tipo_movi,
           _origenCE:_origenCE,
           _cuentaDest:_cuentaDest,
           _Forma_pago_sat: _Forma_pago_sat,
           _fecha_pago:_fecha_pago,
           _categoria: _categoria,
           _subcategoria: _subcategoria
         },
         dataType: "json",
         success: function (data,response) {
          //  console.log("data de cabecera: "+ data);
           // Si se recibio un error 
           if (response[0]!="E") {
            //  console.log("Respuesta 0 "+ data);
             //Redirecciona con variable POST
            setTimeout(function(){ window.location= '../pagos';}, 200);
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
    /*  }else{
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
     } */
     //Si la bandera del saldo es false (insuficiente)
   }else{
     Lobibox.notify("error", {
       size: "mini",
       rounded: true,
       delay: 3000,
       delayIndicator: false,
       position: "center top",
       icon: true,
       img: "../../img/timdesk/notificacion_error.svg",
       msg: "¡Saldo insuficiente en la cuenta seleccionada!",
     });
     $("#" + inputID).addClass("is-invalid");
     $("#" + invalidDivID).show();
     $("#" + invalidDivID).text("Saldo Insuficiente");
   }
 }

//Funcion para cargar la tabla de cuentass por pagar
async function cargarhisto(){
  await cargarCMBProveedor();
  const promise = new Promise((resolve, reject) => {
    let proveedor = $("#cmbProveedor option:selected").val();
  // console.log(proveedor);
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
          sNext: "<i class='fas fa-chevron-right'></i>",
          sPrevious: "<i class='fas fa-chevron-left'></i>",
        },
      }            
    
    tablaP= $("#tblcuentas").DataTable({
      language: espanol,
      info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
        responsive: true,
        lengthChange: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "",//btn-table-custom
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [],
        },
          "columns": [
          {"data": "Proveedor"},
          {"data": "Folio de Factura"},
          {"data": "Serie de Factura", width: "11%"},
          {"data": "Fecha de Vencimiento", width: "11%"},
          {"data": "Importe"},
          {"data": "Saldo insoluto"},
          {"data": "Pago"},
          {"data": "Estatus", width: "17%"},
          {"data": "Id"},
          {"data": "Acciones"},
          ],
          "columnDefs": [
            {
                "targets": [ 8 ],
                "visible": false,
                "searchable": false
            }, 
          ]
        });
        resolve();
    });
    promise.then(values => {
      $('#tblcuentas').DataTable().on("draw", function(){
        /*Permitir numero decimales Solo*/
          //Esta aqui para acceder a los inputs de la tabla cada que se pinta.
          var hiddenRows = tablaP.rows().nodes();
          $(".numericDecimal-only", hiddenRows).on("input", function () {
            var regexp = /[^\d{3}.]/g;
            if ($(this).val().match(regexp)) {
              $(this).val($(this).val().replace(regexp, ""));
            }
          });
        if(!$.isEmptyObject(objFacturas)){
          Object.entries(objFacturas).forEach(([key, property]) => {
            $("#"+key).val(property);
          });
        }
       /*  var hiddenRows = tablaP.rows().nodes();
          $('.pagoinput').maskMoney({thousands:' ', decimal:'.', prefix:'$',affixesStay:false }); */
      });
      is_from_cuentasPagar();
    });
}

//Metodo para recargar la tabla con los datos del nuevo proveedor
function filtroProveedorTabla(){
  $(document).on('change', '#cmbProveedor', function(event) {
      $("#invalid-nombreProv").css("display", "none");
      cmbTipoPago.set('');
    //Descheck a todos los checkbox cuando se cambia de proveedor
    $( ".check" ).prop( "checked", false );
    $("#total").text("0");
    arID = [];
    objFacturas = {};
    objFacturas_Insolutos ={};

    let proveedor = $("#cmbProveedor").val();
    console.log(proveedor);
    //tablaD.destroy();
    //loadModal();
    //console.log($("#tblFactura"));
    //$("#tblFactura").ajax.url("functions/add_anticipos_getcuentas.php?id="+proveedor).load();
    tablaP.clear().draw();
     cuentas_Copy = [];
     cuentas = [];
  /*   tablaD = $('#tblcuentas').DataTable( {
      destroy: true,
      ajax: "functions/get_cuentas.php?id="+proveedor,
    }); */
    //tablaD.ajax.reload();
   /*  console.log((($("#cmbProveedor option:selected").val())=="f"));
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
    } */
  });
}
//Formatea la fecha
function cargarCabeceras(){
  var f = new Date();
  //document.write(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
 // $('#txtfecha').val(f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear() + " " + f.getHours() +":"+ f.getMinutes());
 //  console.log(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
}



});
//Cambia el proveedor seleccionado.
function cambiarProveedor(){
  let proveedorid = $("#provee").attr('value');
//   console.log(proveedorid);
  document.querySelector('#cmbProveedor [value="' + proveedorid + '"]').selected = true;
  //document.getElementById("cmbProveedor").value = proveedorid;
  //$("#cmbProveedor").puidropdown('selectValue', proveedorid);

}
//CArga las facturas del modal
function loadModal(){
  
  let proveedor = $("#cmbProveedor").val();
  $("#txtProveeModal").val($("#cmbProveedor option:selected").text());
 //  console.log(proveedor);
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
    $("#tblFactura").DataTable().destroy();
  tablaD= $("#tblFactura").DataTable({
      "retrieve": true,
        "paging":true,
        destroy: true,
        "pageLength": 15,
        "language": espanol,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "",//btn-table-custom
            },
            buttonLiner: {
              tag: null,
            },
          },
        buttons: [],
        },
        "dom": "Blfrtip",
        "scrollX": true,
        "lengthChange": false,
        "info": false,
        "scrollX": true,
        "info": false,
        "scrollY": "100%",
        order: [[ 1, 'asc' ]],
        "scrollY": "100%",
        "ajax":"functions/add_anticipos_getcuentas.php?id="+proveedor,
        "columns": [
        {"data": "Proveedor"},
        {"data": "Folio de Factura"},
        {"data": "Serie de Factura"},
        {"data": "Fecha de Vencimiento"},
        {"data": "Importe"},
        {"data": "Saldo insoluto"},
        {"data": "Estatus"},
        {"data": "Id"},
        {"data": "Agregar"},
        ],
        "columnDefs": [
          {
              "targets": [ 7 ],
              "visible": false,
              "searchable": false
          }, 
        ]
      });
}


var cuentas_Copy = [];
var cuentas = [];
function get_ids(sender){
  /* cuentas = cuentas_Copy.slice(); */
  if(sender.checked){
    cuentas.push((sender.getAttribute('value')));
  //   console.info( cuentas );
  }else{
    removeItemFromArr( cuentas, (sender.getAttribute('value')) );
  }
}

function removeItemFromArr ( arr, item ) {
//   console.info( cuentas );
//   console.info( cuentas_Copy );
  return new Promise((resolve)=>{
  var i = arr.indexOf( item );

  if ( i !== -1 ) {
      arr.splice( i, 1 );
      resolve();
  }
  
  
  });
  
}
function passSelected(){
  cuentas_Copy = cuentas.slice();
  var cont = 0;
  var id = "";
  if(!($.isEmptyObject(cuentas_Copy))){
    /// Codigo para pasar el array de values de checks a un string 
    cuentas_Copy.forEach(function(index){
      string = string+=index+",";
      if(cont === 0){
        id = index;
      }
      cont++;
    });
    let cadena = string.substring(0, string.length - 1);
   //  console.log(cadena);
    string = "";
    
    //lleno la tabla con ajax
    tablaP.ajax.url("functions/load_cuentasSelected_add.php?id="+cadena).load();
    $('#tblcuentas > tbody:last').append('<tr><th colspan="5"></th><th style="color: var(--color-primario)">Total:</th><td style="color: var(--color-primario)" colspan="2">$<span id="total">0.00</span></td></tr>');
    $("#tbltotal").removeClass("d-none");
    $('#mod_agregarFacturas').modal('hide'); 
    getExpenseCategory(id);
  }else{
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Seleccione al menos una Factura por pagar!",
    });
  }
}
objOldInsolutos = {};
function sumarInputs(sender,insoluto){
  //estructura de la cadena enviada a la base de datos para almacenar (id factura - importe pagado, id factura - importe pagado)
  let validIn = false;
  //recupera el id de la factura y el monto ingresado
  var id = $(sender).attr("id");
  var valor=parseFloat($(sender).val());
  //Comprueva la entrada del input
  switch(valor){
    case valor<0:
      sender.value="";
      valor = 0;
      validIn = false;
     //  console.log("IN1");
      break;
    case 0:
      valor = 0;
      delete objFacturas[id];
      delete objFacturas_Insolutos[id];
      sender.value="";
      validIn = false;
    //   console.log("IN3");
      break;
    case (NaN):
      sender.value="";
      validIn = true;
      valor = 0;
     //  console.log("IN2");
      break;
    case valor>0 && (parseFloat(valor)):
     //  console.log("IN4");
      validIn = true;
      break;
    default:
      valor = 0;
      sender.value="0";
      validIn = true;
    //   console.log("IN5");
      break;
  }
  if(!validIn){
    sender.value="";
    suma=0;
        Object.entries(objFacturas).forEach(([key, property]) => {
          suma += parseFloat(property);
          //Agrega el espacio cada 3 numeros
              var parts = suma.toString().split(".");
              parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
              var union =parts.join(".");
              $("#total").text(" "+union);
        });
    //lo elimina del arreglo clave valor
   // delete arrSumaFacturas[id];
  }else{
    //comprueba que no sea mayor al monto que queda de la factura
    flag = false;
    const promise = new Promise((resolve, reject) => {
      $.ajax({
        type:'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        data: { clase:"get_data",funcion:"validateTptal",id:id,importe:valor },
        success: function (data) {
         //  console.log("Posible?: ", data);
          $.each(data, function (i) {
            if(data[i].posible==1){
              flag = true;
              objFacturas[id] = [valor];
              objFacturas_Insolutos[id] = insoluto;
              resolve(flag);          
            }else{
              flag = true;
              //Regresar el valor al limite de la cuenta
              //this.val(data[i].limite);
              //delete objFacturas[id];
              sender.value = data[i].limite;
              objFacturas[id] = [data[i].limite];
              objFacturas_Insolutos[id] = insoluto;
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡El saldo por pagar es "+ data[i].limite +" !",
              });
              console.log(data[i].limite);
              resolve(flag);
            }
          });
        },
        error: function (error) {
          console.log("Error");
          console.log(error);
        },
      });
    });
      //suma el arreglo y pinta el total
    promise.then(values => {
      if(values){
       //  console.info(objFacturas);
        suma=0;
        Object.entries(objFacturas).forEach(([key, property]) => {
          suma += parseFloat(property);
          var hiddenRows = tablaP.rows().nodes();
            let saldo_inso =  ($("#S"+key, hiddenRows).text()).replace(/ /g, "");
            console.log(saldo_inso.slice(1));
            //Saldo insoluto actual.
            saldo_inso =parseFloat(saldo_inso.slice(1).replace(',', ''));
            if(!objOldInsolutos.hasOwnProperty(key)){
              objOldInsolutos[key] = saldo_inso;
            }

            let newInsoluto = 0;
            //Calcula la resta del nuevo movimiento 
              newInsoluto = objOldInsolutos[key] - property;

              //Lo pinta en pantalla
            var parts = newInsoluto.toString().split(".");
            //Si los decimales estan vacios parts[1] es undefined por lo que le pongo 00 
                ///Si no le dejo el mismo valor anterior
            parts[1] = (parts[1]== undefined)?"00":parts[1];
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            var union =parts.join(".");
            //var newUnion = union.replace(' ', '');
            //console.log(newUnion);
            $("#S"+key, hiddenRows).text("$ "+(union.toLocaleString('en-US')));
          //Agrega el espacio cada 3 numeros
            //Pinta el Total
              var parts = suma.toString().split(".");
              parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
              var union =parts.join(".");
              $("#total").text(" "+union);

              //separa el input pagos en el punto
            var partsInput = sender.value.split(".");
            // Si no tenia decimales le pone 00
            partsInput[1] = (partsInput[1] == undefined)?"00":partsInput[1];
            // Si tenia solo un decimal le agrega un 0 al final
            partsInput[1] = (partsInput[1].length == 1)?partsInput[1]+"0":partsInput[1];
            partsInput[0] = partsInput[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            let unionInput = partsInput.join(".");
            //Lo pone en el campo
            sender.value = unionInput;
        });
        
      }
      });
  }
}

async function delete_fact(id){
 //  console.info(cuentas_Copy);
 //  console.info(cuentas);
  await removeItemFromArr(cuentas_Copy, String(id));
  delete objFacturas[id]; 
  delete objFacturas_Insolutos[id];
  
  //delete cuentas_Copy[id];
  if(!($.isEmptyObject(cuentas_Copy))){
    /// Codigo para pasar el array de values de checks a un string 
    cuentas_Copy.forEach(function(index){
      string = string+=index+",";
    });
    let cadena = string.substring(0, string.length - 1);
   //  console.log(cadena);
    string = "";
    suma=0;
        Object.entries(objFacturas).forEach(([key, property]) => {
          suma += parseFloat(property);
          //Agrega el espacio cada 3 numeros
              var parts = suma.toString().split(".");
              parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
              var union =parts.join(".");
              $("#total").text(" "+union);
        });
    //lleno la tabla con ajax
    tablaP.ajax.url("functions/load_cuentasSelected_add.php?id="+cadena).load();

    //$('#mod_agregarFacturas').modal('hide'); 
  }else{
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Seleccione al menos una Factura por pagar!",
    });
  }

}


$(document).on("click", "#agregarPago", function (e){
  if($("#txtTotal").val()){
    
    pagoLibre = true;
    $("#txtTotal").attr("disabled", true);
    $("#modalshow").attr("disabled", true);
    $("#agregarPago").attr("disabled", true);
    $("#cantidadPagoLibre").val($("#txtTotal").val());
    $("#txtIdCategoria").val($("#cmbCategoriaCuenta").val());
    $("#txtIdSubcategoria").val($("#cmbSubcategoriaCuenta").val());
    
    tablaP.row
      .add({
        "Proveedor": $("#cmbProveedor option:selected").text(),
        "Folio de Factura": 'N/A',
        "Serie de Factura": 'N/A',
        "Fecha de Vencimiento": 'N/A',
        "Importe": $("#txtTotal").val(),
        "Saldo insoluto": 0,
        "Pago": '<input disabled class="form-control numericDecimal-only" maxlength="20" type="text" name="inputs_facturas" value="' + $("#txtTotal").val() + '" placeholder="0" id="cantidadPagoLibre" min="1" onchange="validarCantidad(this)"> <div class="invalid-feedback" id="invalid-input">Completa el importe</div>',
        "Estatus": "",
        "Acciones": '<img src="../../img/timdesk/delete.svg" width="20px" heigth="20px">',
        "Id": $("#cmbProveedor option:selected").val(),
      })
      .draw();
  }else{
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/notificacion_error.svg",
      msg: "¡Agrega una cantidad total!",
    });    
  }
    
});

$(document).on("click", "#tblcuentas tbody img", function (e) {
  tablaP.row($(this).parents("tr")).remove().draw();
  $("#txtTotal").attr("disabled", false);
});

$(document).on("click", "#tblcuentas tbody img", function () {
  setTimeout(function(){
    if(tablaP.rows().count() === 0){
      $("#modalshow").attr("disabled", false);
      $("#agregarPago").attr("disabled", false);
    }
  }, 500);
});

function validarCantidad(item){
  if(item.value != $("#txtTotal").val()){
    $("#invalid-input").css("display", "block");
  }else{
    $("#invalid-input").css("display", "none");    
  }
}

$(document).on('change', '#cmbTipoPago', function(event) {
  //si se viene de una cuenta por pagar evitar abrir el modal
  if($("#idProveedorFrom").attr("value") != undefined && flagOpenModal == false){
    return;
  }
  $("#cat_cuentas").removeClass("d-none");
  if($("#cmbTipoPago").val() == "0"){
    tablaP.clear().draw();
    $("#tbltotal").addClass("d-none");
    $("#invalid-nombreProv").css("display", "none");
    $("#divDNone").removeClass("d-none");
    $("#agregarPago").removeClass("d-none");
    
    $('#cmbCategoriaCuenta').prop('required',true);
    $('#cmbSubcategoriaCuenta').prop('required',true);
  }else{
    $("#divDNone").addClass("d-none");
    $("#agregarPago").addClass("d-none");
    $('#cmbCategoriaCuenta').prop('required',false);
    $('#cmbSubcategoriaCuenta').prop('required',false);
    if(!$("#cmbProveedor").val()){
        $("#invalid-nombreProv").css("display", "block");
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Elige un proveedor!",
        }); 
    }else{
      if($("#cmbTipoPago").val()){
        $("#invalid-nombreProv").css("display", "none");
        loadModal();
        cuentas = cuentas_Copy.slice();
       //  console.info(cuentas_Copy);
        if(tablaD){
          var hiddenRows = tablaD.rows().nodes();
            $("input[type='checkbox']", hiddenRows).prop('checked', false); 
            if(!$.isEmptyObject(cuentas_Copy)){
              cuentas_Copy.forEach((index,id) => {
                $("#"+index , hiddenRows).prop( "checked", true );
               // console.log((id+"-"+ index));
              });
            }
        }
        $("#mod_agregarFacturas").modal("show");            
      }  
    }
  }
});

//verifica si se accedió desde la vista de una factura en "cuentas cobrar"
function is_from_cuentasPagar() {
  if ($("#idProveedorFrom").attr("value") != undefined) {
    proveedor=$("#idProveedorFrom").val();
    selectProveedor.set(proveedor);
    cmbTipoPago.set("1");
  }

  if ($("#idFacturaFrom").attr("value") != undefined) {
    factura=$("#idFacturaFrom").val();
    //carga la factura
    cuentas.push(factura);
    passSelected();
    flagOpenModal=true;
  }
}

function cargarCMBCategorias(name)
{
  var html = "";
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_categorias"},
    success: function (data) {
      //   console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (data[i].PKCategoria === name) {
            html += '<option value="' +
              data[i].PKCategoria +
              '" selected>' +
              data[i].Nombre+
              "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKCategoria +
            '">' +
            data[i].Nombre+
            "</option>";
        }
      });

      $("#cmbCategoriaCuenta").append(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function cargarCMBSubcategorias(subCat,name)
{
  var html = '<option value="0" selected>Seleccione una subcategoria</option>';
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_subcategorias",subCat:subCat},
    success: function (data) {
      //   console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if(data[i].PKSubcategoria === name){
            html +=
                '<option value="' +
                data[i].PKSubcategoria +
                '" selected>' +
                data[i].Nombre+
                "</option>";
        } else {
          html +=
            '<option value="' +
            data[i].PKSubcategoria +
            '">' +
            data[i].Nombre+
            "</option>";
        }
      });

      $("#cmbSubcategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function getExpenseCategory(value)
{
    if($("#cat_cuentas").hasClass('d-none')){
        $("#cat_cuentas").removeClass('d-none');
        cmbSubcategoriaCuenta.enable();
    }
    $.ajax({
        type:'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        data: { clase:"get_data",funcion:"get_expenseCategory",cuenta_id:value},
        cache: false,
        success: function (data) {
            if(data.length > 0){
                cargarCMBCategorias(data[0].categoria_id);
                cargarCMBSubcategorias(data[0].categoria_id,data[0].subcategoria_id);
            }
            
            
        },
        error: function (error) {
          console.log("Error");
          console.log(error);
        },
    });
}