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
//variable que guarda el proveedor
var C_proveedor;
//Guardar la cuenta de destino inicial.
var cmbCuenta;

//variables para comparar que las facturas coincidan
bandera=false;
//bandera para que el importe del onjeto no se actualice en primera instancia, despues si.
flagActualizaImporte=false;

var clienteSelected;

let string="";

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

//Validar los selects
  function validateSelects(selectID,invalidDivID){
    textInvalidDiv = "Campo requerido";
  if (($('select[name='+selectID+'] option').filter(':selected').val())=="f") {
    $("#" + selectID).addClass("is-invalid");
    document.getElementById(invalidDivID).style.display = 'block';
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + selectID).removeClass("is-invalid");
    document.getElementById(invalidDivID).style.display = 'none';
    $("#" + invalidDivID).text("");
  }
}

function validar_Select(){
    redFlag1 = 0;
    redFlag2 = 0;
    textInvalidDiv = "Campo requerido";

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
      redFlag1 = 1;
    }
    inputID= "txtFecha";
    invalidDivID = "invalid-fecha";
    if ((document.getElementById('txtFecha').value)=="") {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).show();
      $("#" + invalidDivID).text(textInvalidDiv);
    } else {
      $("#" + inputID).removeClass("is-invalid");
      $("#" + invalidDivID).hide();
      $("#" + invalidDivID).text(textInvalidDiv);
      redFlag2 = 1;
    }
    if((redFlag1==1)&&(redFlag2==1)){
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
        msg: "¡Faltan algunos campos requeridos!",
      });
    }
}

$(document).ready(function() {
    crearSelects();
    cargardata();
    
    seleccion=0;
});


var html = "";
function cargarCMBTipo(){
    var values = {0:"Trasferencia",1:"Cheque",2:"Efectivo",3:"Tarjeta de credito/debito"};
    var idtipo = $('#tipopagoid').val();

    $.each(values, function(i) {

        //Crea el html para ser mostrado
        if (i == parseInt(idtipo)) {
            html +=
                '<option selected value="' +
                i +
                '">' +
                values[i] +
                "</option>";
        }else {
            html +=
            '<option value="' +
            i +
            '">' +
            values[i] +
            "</option>";
        }
    });
    $("#cmbTipoPag").append(html);
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
//Carga los selects con los datos agurupados.
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
            sNext: '<img src="../../img/icons/pagination.svg" width="20px"/>',
            sPrevious: '<img src="../../img/icons/pagination.svg" width="20px" style="transform: scaleX(-1)"/>'
          },
        }            
      
      tablaP= $("#tblcuentas").DataTable({
          "retrieve": true,
            "paging":true,
            destroy: true,
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
            "ajax": "functions/anticipo_getpagadas.php?pagoid=" + idpagos+"&proveedorid="+idprove+"&ver="+"1",
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
          if(!$.isEmptyObject(objFacturas)){
            Object.entries(objFacturas).forEach(([key, property]) => {
              $("#"+key).val(property);
            });
          }
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

function cargarCMBCategorias()
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
        if (i == 0) {
          html +=
            '<option disabled value="f" selected>Seleccione una categoria</option>';
          html +=
            '<option value="' +
            data[i].PKCategoria +
            '">' +
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
function cargarCMBSubcategorias(subCat)
{
  var html = "";
  $.ajax({
    type:'POST',
    url: "functions/addcontroller.php",
    dataType: "json",
    data: { clase:"get_data",funcion:"get_subcategorias",subCat:subCat},
    success: function (data) {
      //   console.log("data de cuenta: ", data);
      $.each(data, function (i) {
        if (i == 0) {
          html +=
            '<option disabled value="f" selected>Seleccione una categoria</option>';
          html +=
            '<option value="' +
            data[i].PKSubcategoria +
            '">' +
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

      $("#cmbSubcategoriaCuenta").html(html);
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}
