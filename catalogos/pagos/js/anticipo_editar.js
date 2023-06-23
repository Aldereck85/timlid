//array con facturas modificadas
var arID = {};
var arrIDTemporal=[];
//array con las facturas iniciales, para comparación
var arIdOlds ={};
//array que guarda solo las llaves de los arrays
var aridonlyolds= [];
var aridonly= [];
var aridUpdateonly=[];
//array para ingresar los cambios de los valores
var arrImportesOld={};
//array para guardar los importes y calcular el total de las facturas
var arrSumaFacturas = [];
//array para guardar los importes asignados de las facturas 
var objImportesFacturas = {};
//variable que guarda el proveedor
var C_proveedor;
var IdsNoEdit ={};
//Guardar la cuenta de destino inicial.
var cmbCuenta;
var tablaP;
//variables para comparar que las facturas coincidan
bandera=false;
fromaPago='';
//bandera para que el importe del onjeto no se actualice en primera instancia, despues si.
flagActualizaImporte=false;

var clienteSelected;

let string="";
var objFacturas_Insolutos= {};
//Objeto con los retiros para cada id actuales.
var objFacturas = {};
// Objeto con los movimientos iniciales.
var objOldsMv = {};
// objeto con los movimientos que antes estaban y ahora ya no, estos fueron elminados, por lo que se eliminaran de la BD.
var objToDelete = {};
// objeto con los movimientos que se les actualizo el retiro.
var objToUpdate = {};
// objeto con los movimientos que antes no estaban y ahora estan osea que se añadieron nuevos (To Insert)
var objToInsert = {};


$(document).ready(function() {
    crearSelects();
    cargardata();
    Importe_movimiento();
    
    
    seleccion=0;
    $('#mod_agregarFacturas').on('shown.bs.modal', showModal);
/* 
    $('#mod_agregarFacturas').on('hiden.bs.modal', function(){
      $("#tblFactura").DataTable().clear();
      $("#tblFactura").DataTable().destroy();

    });
 */
/*     $("#modalshow").click(function(){
        showModal();  
      }); */

    $("#modalshow").click(function(){
        
       // Importe_movimiento();
    });

    //boton para agregar las facturas del modal
    $("#btnAgregarFacturas").click(function(){
        passSelected();
    });

    //boton para guardar los pagos
    $("#btnguardarDetalle").click(function(){
        validarImputs();
        //validar_Select();
    });


    $(function(){
        $("[data-toggle='tooltip']").tooltip();
    });

    //Comprobamos si tiene permisos para editar
    if($('#edit').val() !== "1"){
    $('#mod').hide();
    } 
    //Comprobamos si tiene permisos para ver
    if($('#ver').val() !=="1"){
    $('#alert').modal('show');
    }
    //Redireccionamos al Dash cuando se oculta el modal.
    $('#alert').on('hidden.bs.modal', function (e) {
    window.location= href="../dashboard.php";
    });   

    //muestra el modal para seleccionar las facturas a cobrar.
   $('#agregarFacturas').on('click', function (e) {
    passSelected();
  });
});

function validarImputs(){
  redFlag1 = 0;
  redFlag2 = 0;
  redFlag3 = 0;
  redFlag4 = 0;
  redFlag5 = 0;
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
  if((redFlag1==1)&&(redFlag2==1) && (redFlag3==1) && (redFlag5==1) && (redFlag6==1)){
    Posible();
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
//Comprueba que aun se tenga el saldo en la cuenta interna para pagar las cuentas.
//Comprueba si el saldo insoluto de la factura se actualizó
function Posible(){
  //estructura de la cadena enviada a la base de datos para almacenar (id factura - importe pagado, id factura - importe pagado)
  let validIn = false;
  let _origenCE = $('select[name=cmbCuenta] option').filter(':selected').val();
  let _origenCE_text = $('select[name=cmbCuenta] option').filter(':selected').text();
  var idpagos = $('#idpago').val();
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
        data: { clase:"get_data",funcion:"Add_Validate_importeAnticipos",origen:_origenCE, _cadena_CP:_cadena_CP, _cadena_CP_insolutos:_cadena_CP_insolutos,_idpagos:idpagos},
        success: function (data) {
          console.log("Posible?: ", data);
          $.each(data, function (i) {
            if(data[i].iguales == 0){
            flag = false;
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/warning_circle.svg",
                msg: "¡Parece que el saldo insoluto de una factura ha cambiado ahora es: $"+data[i].nuevolimite +" !",
              });
              var idpagos = $('#idpago').val();
              var idprove = C_proveedor;
              console.info(_cadena_CP_insolutos);
              //Recarga la tabla con el nuevo saldo Insoluto
              tablaP.ajax.url( "functions/anticipo_getpagadas.php?pagoid=" + idpagos+"&proveedorid="+idprove ).load();
              //Cambia el saldo insoluto guardado en el objeto al nuevo
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
          console.log(_cadena_CP);
          if(flag){
            constructObjets();
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

async function showModal(){
  //Carga el modal con las cuentas por pagar de la empresa
    await loadModal();
    //Espera 300 milesegundos para renderizar la tabla y marcar los checs que venian en true (Las cuentas pagadas)
    setTimeout(function(){
      cuentas = cuentas_Copy.slice();
      console.info(cuentas_Copy);
      if(tablaD){
        var hiddenRows = tablaD.rows().nodes();
          $("input[type='checkbox']", hiddenRows).prop('checked', false); 
          if(!$.isEmptyObject(cuentas_Copy)){
            cuentas_Copy.forEach((index,id) => {
                console.log(index);
              $("#"+index , hiddenRows).prop( "checked", true );
              console.log((id+"-"+ index));
            });
          }
      }
    }, 6000);
   
}

var html = "";
function cargarCMBTipo(){
    var values = {0:"Trasferencia",1:"Cheque",2:"Efectivo",3:"Tarjeta de credito/debito"};
    var idtipo = $('#tipopagoid').val();
  var htmltipos = "";
    $.each(values, function(i) {
        //Crea el html para ser mostrado
        if (i == parseInt(idtipo)) {
          htmltipos +=
                '<option selected value="' +
                i +
                '">' +
                values[i] +
                "</option>";
        } else {
          htmltipos +=
            '<option value="' +
            i +
            '">' +
            values[i] +
            "</option>";
        }
        /* console.log(values[i]);
        console.log(i); */
    });
    
    $("#cmbTipoPag").append(htmltipos);
    //cargarDetail();

}
function cargarCMBcuentasCheques(){
    return new Promise((resolve)=>{
    var idcuenta = cmbCuenta;       
    var html;
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
  var idcuenta = cmbCuenta;
  console.log(idcuenta);
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
     // cargarCMBTipo();
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
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
async function cargarhisto(){
    await cargarCMBProveedor();
    const promise = new Promise((resolve, reject) => {
        var idpagos = $('#idpago').val();
        var idprove = C_proveedor;
      let proveedor = $("#cmbProveedor option:selected").val();
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
            sPrevious: "<i class='fas fa-chevron-left'></i>"
          },
        }            
      
      tablaP= $("#tblcuentas").DataTable({
            "language": espanol,
            info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-table-custom",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [{
            extend: "excelHtml5",
            text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
            className: "btn-table-custom--turquoise",
            titleAttr: "Excel",
          }],
        },
            "ajax": "functions/anticipo_getpagadas.php?pagoid=" + idpagos+"&proveedorid="+idprove,
            "columns": [
            {"data": "Proveedor"},
            {"data": "Folio de Factura"},
            {"data": "Serie de Factura"},
            {"data": "Fecha de Vencimiento"},
            {"data": "Importe"},
            {"data": "Saldo insoluto"},
            {"data": "Pago"},
            {"data": "Estatus"},
            {"data": "Id"},
            {"data": "No Edit"},
            {"data": "Acciones"},
            ],
            "columnDefs": [
              {
                  "targets": [ 8,9 ],
                  "visible": false,
                  "searchable": false
              }, 
            ]
          });
          resolve();
      });
      promise.then(values => {
        
        $('#tblcuentas').DataTable().on("draw", function(){
          NoUpdate();
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
            console.info(objFacturas);
            Object.entries(objFacturas).forEach(([key, property]) => {
               //separa el input pagos en el punto
               try{
                var partsInput =  String(property).split(".");

               // Si no tenia decimales le pone 00
               partsInput[1] = (partsInput[1] == undefined)?"00":partsInput[1];
               // Si tenia solo un decimal le agrega un 0 al final
               partsInput[1] = (partsInput[1].length == 1)?partsInput[1]+"0":partsInput[1];
               partsInput[0] = partsInput[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
               let unionInput = partsInput.join(".");
               //Lo pone en el campo
              $("#"+key).val(unionInput);
               }catch (error){
                console.error(error);
               }
               
            });
          }
          /* var hiddenRows = tablaP.rows().nodes();
          $('.pagoinput').maskMoney({thousands:'', decimal:'.', prefix:'$',affixesStay:false }); */
        });
      });
  }


  function cargarCMBProveedor() {
    return new Promise((resolve)=>{
        var idprove = C_proveedor;
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
                    //Crea el html para ser mostrado
                    if (data[i].PKData == parseInt(idprove)) {
                        html +=
                            '<option selected value="' +
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
                    cargarCMBTipo();
                    cargarCMBcuentasOtros();
                    resolve();
                
                //cargarProductosEmpresa();
            },
            error: function(error) {
                console.log("Error");
                console.log(error);
            },
        });
    });

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
                C_proveedor = (data.result.PKProveedor);
                cmbCuenta = (data.result.cuenta_origen_id);
                $('#txtfecha').val(data.result.fecha_pago);
                $('#tipopagoid').val(data.result.tipo_pago);
                $('#cuentaid').val(data.result.cuenta_origen_id);
                $('#txtreferencia').val(data.result.Referencia);
                $('#textareaCoemtarios').val(data.result.comentarios);
                $('#txtTotal').val(data.result.total);
    

                cargarhisto();

            } else {
                $('.user-content').slideUp();
                $("#alertInvoice").modal("show");
            }
        }
    });

}
//CArga las facturas del modal

  //CArga las facturas del modal
function loadModal(){
  $("#tblFactura").DataTable().destroy();

    return new Promise((resolve)=>{
      var idpagos = $('#idpago').val();
    let proveedor = $("#cmbProveedor option:selected").val();
    $("#txtProveeModal").val($("#cmbProveedor option:selected").text());
    console.log(proveedor);
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
          sPrevious: "<i class='fas fa-chevron-left'></i>"
        },
      }            
    
    tablaD= $("#tblFactura").DataTable({
      "language": espanol,
      info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-table-custom",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [{
            extend: "excelHtml5",
            text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
            className: "btn-table-custom--turquoise",
            titleAttr: "Excel",
          }],
        },
          "ajax":"functions/anticipos_getcuentas.php?pagoid=" + idpagos+"&id="+proveedor,
          "columns": [
          {"data": "Proveedor"},
          {"data": "Folio de Factura"},
          {"data": "Serie de Factura"},
          {"data": "Fecha de Vencimiento"},
          {"data": "Importe"},
          {"data": "Saldo insoluto"},
          {"data": "Estatus"},
          {"data": "Id"},
          {"data": "Acciones"},
          ],
          "columnDefs": [
            {
                "targets": [ 7 ],
                "visible": false,
                "searchable": false
            }, 
          ]
        });
        resolve();
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
        var key = arreglo[0];
        delete arID[key];
        delete objFacturas[key];
        delete objFacturas_Insolutos[key];
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
function deletePago(idPago){
    console.log("Eliminando: "+ idPago);
    $.ajax({
      type:'POST',
      url: "functions/addcontroller.php",
      dataType: "json",
      data: { clase:"delete_data",funcion:"delete_pago", idpagos:idPago},
      success: function (data) {
        console.log("Dato eliminado: ", data);
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 2000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "¡Pago eliminado con exito!",
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
  
}
objOldInsolutos = {};
function sumarInputs(sender, insoluto){
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
        console.log("IN1");
        break;
      case (NaN):
        sender.value="";
        validIn = false;
        valor = 0;
        console.log("IN2");
        break;
      case 0:
        valor = 0;
        delete objFacturas[id];
        delete objFacturas_Insolutos[id];
        sender.value="";
        validIn = false;
        console.log("IN3");
        break;
      case valor>0 && (parseFloat(valor)):
        console.log("IN4");
        validIn = true;
        break;
      default:
        valor = 0;
        sender.value="0";
        validIn = true;
        console.log("IN5");
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
                $("#txtTotal").val(" "+union);
          });
      //lo elimina del arreglo clave valor
     // delete arrSumaFacturas[id];
    }else{
      //comprueba que no sea mayor al monto que queda de la factura
      flag = false;
      const promise = new Promise((resolve, reject) => {
        var idpagos = $('#idpago').val();
        $.ajax({
          type:'POST',
          url: "functions/addcontroller.php",
          dataType: "json",
          data: { clase:"get_data",funcion:"Update_validateimportes",id:id,importe:valor,id_pago:idpagos },
          success: function (data) {
            console.log("Posible?: ", data);
            $.each(data, function (i) {
              if(data[i].posible==1){
                flag = true;
                objFacturas[id] = valor;
                console.info(objFacturas);
                objFacturas_Insolutos[id] = insoluto;
                resolve(flag);          
              }else{
                flag = false;
                //data[i].limite = data[i].limite.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                //Regresar el valor al limite de la cuenta
                //this.val(data[i].limite);
                //delete objFacturas[id];
                sender.value = data[i].limite;
                objFacturas[id] = data[i].limite;
                objFacturas_Insolutos[id] = insoluto;
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "¡El saldo por pagar es "+ data[i].limite +" !",
                });
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
          suma=0;
          Object.entries(objFacturas).forEach(([key, property]) => {
            var hiddenRows = tablaP.rows().nodes();
            let saldo_inso =  ($("#S"+key, hiddenRows).text()).replace(/ /g, "");
                
            //Saldo insoluto actual.
            saldo_inso =parseFloat(saldo_inso.slice(1).replace(',', ''));
            console.log(saldo_inso);
            ///Si el id de la cuenta por pagar del FOR no esta aun en el arreglo de insolutos 
            if(!objOldInsolutos.hasOwnProperty(key)){
              //Pone en el objeto de insolutos
              objOldInsolutos[key] = saldo_inso;
            }

            let newInsoluto = 0;

            //Si el movimiento ya se habia afectado por este pago suma lo que se hbia sacado antes al saldo insoluto y le resta lo que ahora se le quiere sacar 
            //
            if(objOldsMv.hasOwnProperty(key)){
              newInsoluto = objOldsMv[key] + objOldInsolutos[key] - property;
            }else{
              newInsoluto = objOldInsolutos[key] - property;
            }

            var parts = newInsoluto.toString().split(".");
            //Si los decimales estan vacios parts[1] es undefined por lo que le pongo 00 
                ///Si no le dejo el mismo valor anterior
                parts[1] = (parts[1]== undefined)?"00":parts[1];
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                var union =parts.join(".");
                $("#S"+key, hiddenRows).text("$"+(union.toLocaleString('en-US')));
                console.log(newInsoluto);
                suma += parseFloat(property);
                //Agrega el espacio cada 3 numeros y si tiene 
                 parts = suma.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                 union =parts.join(".");
                $("#txtTotal").val(" "+union);

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
  //Guarda las cuentas que estan en la tabla principal para al abrir el modal se pongan checked
  var cuentas_Copy = [];
var cuentas = [];
  function get_ids(sender){
    /* cuentas = cuentas_Copy.slice(); */
    if(sender.checked){
      cuentas.push((sender.getAttribute('id')));
      console.info( cuentas );
    }else{
      removeItemFromArr( cuentas, (sender.getAttribute('id')) );
      delete objFacturas[(sender.getAttribute('id'))];
      delete objFacturas_Insolutos[(sender.getAttribute('id'))];

    }
  }
  
  function removeItemFromArr ( arr, item ) {
    console.info( cuentas );
    console.info( cuentas_Copy );
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
    var idpagos = $('#idpago').val();
    if(!($.isEmptyObject(cuentas_Copy))){
      /// Codigo para pasar el array de values de checks a un string 
      cuentas_Copy.forEach(function(index){
        string = string+=index+",";
      });
      let cadena = string.substring(0, string.length - 1);
      console.log(cadena);
      string = "";
  
      //lleno la tabla con ajax
      tablaP.ajax.url("functions/load_cuentasSelected.php?id="+cadena+"&pago="+idpagos).load();
      $('#mod_agregarFacturas').modal('hide'); 
      
    }else{
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡Seleccione al menos una factura por pagar!",
      });
    }
  }
  //Funcion para sacar los movimientos iniciales del pago
  function Importe_movimiento(){
    var idpagos = $('#idpago').val();
    let arreglo = {};
    $.ajax({
        type: 'POST',
        url: "../pagos/functions/anticipos_getmovimientos.php",
        dataType: "json",
        data: { idpagos: idpagos, funcion: "1" },
        success: function(data) {
            if (data.status == 'ok') {
                // se recupera la cadena string en formato id_factura,deposito-id_factura,deposito
                //separa el string en un arreglo {[id_factura,deposito],[id_factura,importe]}
                arreglo = data.result.split('-');
               // se recorre el arreglo para volverlo a separarlo en clave valor {id_factura:importe,id_factura:importe]}
                $.each(arreglo,function(i){
                    arreglo[i]=arreglo[i].split(',');

                    //se insertan las facturas y sus importes en el arreglo
                    /* arID [idFactura] = idFactura;
                    arrImportesOld[idFactura] = importe; */
                });
                $.each(arreglo,function(i){
                    objFacturas[arreglo[i][0]] = parseFloat(arreglo[i][1]);
                    objFacturas_Insolutos[arreglo[i][0]] = parseFloat(arreglo[i][2]);
                    cuentas_Copy.push(arreglo[i][0]);
                });
                //Crea una copia del objeto que tiene los valores que ya estaban en la base de datos
                //Esto sirve para despues comparar los arreglos y ver que se agrego o eliminó
                objOldsMv = Object.assign({},objFacturas);
                //agregarFacturas();
            }else{
                $('.user-content').slideUp();
                $("#alertInvoice").modal("show");
            }
        }
    });
  }
//Elimina la factura de las seleccionadas
  async function delete_fact(id){
    console.info(cuentas_Copy);
    console.info(cuentas);
    var idpagos = $('#idpago').val();
    if(cuentas_Copy.length>1){
      await removeItemFromArr(cuentas_Copy, String(id));
    delete objFacturas[id];
    delete objFacturas_Insolutos[id];
    console.info(objFacturas); 
    
    //delete cuentas_Copy[id];
    if(!($.isEmptyObject(cuentas_Copy))){
      /// Codigo para pasar el array de values de checks a un string 
      cuentas_Copy.forEach(function(index){
        string = string+=index+",";
      });
      let cadena = string.substring(0, string.length - 1);
      console.log(cadena);
      string = "";
      suma=0;
          Object.entries(objFacturas).forEach(([key, property]) => {
            suma += parseFloat(property);
            //Agrega el espacio cada 3 numeros
                var parts = suma.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                var union =parts.join(".");
                $("#txtTotal").val(" "+union);
          });
      //lleno la tabla con ajax
      tablaP.ajax.url("functions/load_cuentasSelected.php?id="+cadena+"&pago="+idpagos).load();
  
      //$('#mod_agregarFacturas').modal('hide'); 
    }else{
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡Algo anda mal!",
      });
      location.reload();
    }
    }else{
      
      if(!Notificaciones.hasOwnProperty("Seleccione al menos una factura por pagar") || Notificaciones["Seleccione al menos una factura por pagar"]==true){
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "¡Seleccione al menos una factura por pagar!",
        });
        NoSpamLobibox("Seleccione al menos una factura por pagar");
        console.log( Notificaciones["Seleccione al menos una factura por pagar"]);
      }

      
    }
  
  }
//Construye los objetos con los valores que se van a editar eliminar y agregar
  function constructObjets(){
      //// VAciar objetos sin problemas con Garbage Collection en IE6.
     // objToDelete = {};
      for (var prop in objToDelete) { if (objToDelete.hasOwnProperty(prop)) { delete objToDelete[prop]; } }
     // objToInsert = {};
      for (var prop in objToInsert) { if (objToInsert.hasOwnProperty(prop)) { delete objToInsert[prop]; } }
     // objToUpdate = {};
      for (var prop in objToUpdate) { if (objToUpdate.hasOwnProperty(prop)) { delete objToUpdate[prop]; } }
    if(!$.isEmptyObject(objFacturas)){
        //To Insert
        //Los que antes no estaban en el arreglo y ahora si estan.
        Object.entries(objFacturas).forEach(([key, property]) => {
            if(!objOldsMv.hasOwnProperty(key)){
                objToInsert[key] = property;
            //To update
            //Los que antes estaban y ahora estan pero con valores diferentes de pago
            }else if(objOldsMv.hasOwnProperty(key)){
              //Comprobar si el valor cambio
                if(objOldsMv[key]==property){
                    console.log("iguales");
                }else{
                    if (!(IdsNoEdit.hasOwnProperty(key))){
                      console.log("diferentes");
                      objToUpdate[key]=property;
                    }
                    
                }
            }
        });
        //To Delete
        //Los que antes estaban y ahora ya no estan.
        Object.entries(objOldsMv).forEach(([key, property])=>{
            if(!objFacturas.hasOwnProperty(key)){
              if (!(IdsNoEdit.hasOwnProperty(key))){
                console.log("Eliminado")
                objToDelete[key] = property;
              }
            }
        });
        console.info(objFacturas);
        console.log(objToInsert);
        console.log(objToDelete);
        console.log(objToUpdate);
        cosntructCadenas();

    }
    
  }

  function cosntructCadenas(){
    let Insert_cadena_CP = null;
    let delete_cadena_CP = null;
    let update_cadena_CP = null;
    if(!$.isEmptyObject(objToInsert)){
    Object.entries(objToInsert).forEach(([index, movimiento]) => {
        string = string+=index+"-"+movimiento+",";
      });
       Insert_cadena_CP = string.substring(0, string.length - 1);
      string = "";
      console.log(Insert_cadena_CP);
    }
    if(!$.isEmptyObject(objToDelete)){
      Object.entries(objToDelete).forEach(([index, movimiento]) => {
        string = string+=index+"-"+movimiento+",";
      });
       delete_cadena_CP = string.substring(0, string.length - 1);
      string = "";
      console.log(delete_cadena_CP);
    }
    if(!$.isEmptyObject(objToUpdate)){
        Object.entries(objToUpdate).forEach(([index, movimiento]) => {
          string = string+=index+"-"+movimiento+",";
        });
         update_cadena_CP = string.substring(0, string.length - 1);
        string = "";
        console.log(update_cadena_CP);
    }
    update(Insert_cadena_CP,delete_cadena_CP,update_cadena_CP);
  }

function update(insert,delet,update){
    stringToInsert = insert;
    stringToDelete = delet;
    stringToUpdate = update;
    console.log(stringToUpdate);
    console.log(stringToInsert);
    console.log(stringToUpdate);
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

    //creacion de las cadenas para insertar, eliminar y actualizar las facturas

    $.ajax({
        type: 'POST',
        url: "functions/addcontroller.php",
        dataType: "json",
        async:false,
        data: { 
            clase: "update_data",
            funcion: "anticipo_update_detail" ,

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
            stringToUpdate:stringToUpdate,
            //CountToUpdate
            textareaCoemtarios:textareaCoemtarios,
            cuentaid:cuentaid,
            cuentaDest: null},
        success: function(data) {
            console.log("Exito al update: ", data);
            //Re direcciona con variable POST
/*             $().redirect('../pagos/index.php', {
                'notifi': "1"
                }); */
             setTimeout(function(){ window.location="../pagos"; }, 500);
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
             // setTimeout(function(){ window.location= '../pagos';}, 1500);
            console.log("Error");
            console.log(error);
        },
    });
    
}
function alerta(){
  Lobibox.notify("warning", {
    size: "mini",
    rounded: true,
    delay: 3500,
    delayIndicator: false,
    position: "center top",
    icon: true,
    img: "../../img/timdesk/warning_circle.svg",
    msg: "Solo es posible editar el ultimo registro de una factura pagada",
  });
}

function NoUpdate(){
  var hiddenRows = tablaP.rows().nodes();
  $("input[name = txtdisable]", hiddenRows).each(function(){
    var ids = $(this).val();
    if(ids!=0){
      IdsNoEdit[ids] = ids;
    }
  }); 
  console.info(IdsNoEdit);
  
 /*  if(!$.isEmptyObject(cuentas_Copy)){
    cuentas_Copy.forEach((index,id) => {
        console.log(index);
      $("#"+index , hiddenRows).prop( "checked", true );
     // console.log((id+"-"+ index));
    });
  } */
}