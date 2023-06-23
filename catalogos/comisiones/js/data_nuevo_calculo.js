//Variable para calcular el total calculado de la comisión
var totalCal = 0;

//Se declara un array de objetos llamado filasSeleccionadas para guardar las facturas seleccionadas
var filasSeleccionadas = [];


//Función para cargar el select de vendedores
function carga_cmbVendedores() {
  html='';
  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_cmbVendedor.php",
    dataType: "json",
    success: function (data) {
      $.each(data, function (i) {
        html +=
            '<option value="' +
            data[i].PKVendedor +
            '">' +
            data[i].Nombre +
            "</option>"; 
      });
      $("#chosenVendedores").append(html);
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}


//Función para validar que el campo de porcentaje no sea mayor a 100, menor a cero y que no acepte números negativos
function validarPorcentaje(){
  $('#txtPorcentaje').on("change", function(){
    let numerosPor = $('#txtPorcentaje').val();
    if(numerosPor > 100){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡El porcentaje no debe de ser mayor a 100!",
      });
      $('#txtPorcentaje').val("");
    }
    if(numerosPor <= 0){
      {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "¡El porcentaje no debe de ser menor a 0!",
        });
        $('#txtPorcentaje').val("");
      }
    }
    ocultarTabla();
  });
}


//Función para mostrar el div oculto con la tabla
function mostrarTabla (){
  $(".table-responsive").css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
}


//Función para ocultar el div oculto con la tabla
function ocultarTabla (){
  $(".table-responsive").css({'display': 'none','opacity': '0','visibility': 'hidden'});
  var comprobarTotalCalculado = document.getElementById('totalCalculado').innerHTML;
  if(!(comprobarTotalCalculado=="")){
    $("#totalCalculado").html("0.00");
    $("#txtMontoIngresado").val("0.00");
    totalCal = 0;
  }
}


//Función para sumar o restar los montos comisionados del total calculado   
function sumar(sender){
  //Arreglo para guardar el ID de la factura que trae en el atributo 'value' del check
  check = sender.getAttribute("value").split("-");

  montoPorFactura = check[1] != '' && parseFloat(check[1]) >= 0 ? parseFloat(check[1]) : 0;
  if(sender.checked){
    totalCal += montoPorFactura;
  } else {
    totalCal = totalCal - montoPorFactura;
  }
  let totalValidado = redondearDecimales(totalCal);
  $("#totalCalculado").html(totalValidado);
  totalValidado=totalValidado.replace(/ /g, "");
  $("#txtMontoIngresado").val(parseFloat(totalValidado).toFixed(2).replace(/,/g,"."));
}


//Función para mostrar el monto total calculado debajo de la tabla de facturas
function cargatxtCalculoTotal() {
  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_totalCalculado.php",
    dataType: "json",
    data: {
      vendedor:$('#chosenVendedores').val(),
      tipo:$('#chosenTipo').val(),
      fecha_desde:$('#txtDateFrom').val(),
      fecha_hasta:$('#txtDateTo').val(),
      porcentaje:$('#txtPorcentaje').val(),
    },
    success: function (data) {
      if(!(data['totalCalculado']==null)){
        totalCal = parseFloat(data['totalCalculado']);
        let totalValidado = redondearDecimales(totalCal);
        $("#totalCalculado").html(totalValidado);
        totalValidado=totalValidado.replace(/ /g, "");
        $("#txtMontoIngresado").val(parseFloat(totalValidado).toFixed(2));
      } else {
        $("#totalCalculado").html(redondearDecimales(0));
        $("#txtMontoIngresado").val(parseFloat(redondearDecimales(0)).toFixed(2));
      }
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}


//Función para guardar en un array de objetos las facturas seleccionadas
function funcionValidarFacturas() {
  filasSeleccionadas = [];

  //Iteración para cada fila de la tabla
  $("#tblSfacturas tr").each(function(){
    var itemCalculo = {};

    var tds = $(this).find("td");

    if(tds.find(":checkbox").prop("checked") == true){
      check = tds.find(":checkbox").val().split("-");
      itemCalculo.idFactura = check[0];
      itemCalculo.MontoCom = check[1];
      itemCalculo.tipoDoc = tds.find(":checkbox").attr("data-tipo");

      
      //Metemos el objeto itemCalculo en un arreglo
      filasSeleccionadas.push(itemCalculo);
    }
  });
}


//Función para guardar un nuevo cálculo de comisiones
function guardarCalculo() {
  if(!($.isEmptyObject(filasSeleccionadas))){
    if(!($('#txtMontoIngresado').val()=="")){
      $.ajax({
        async: false,
        type: "POST",
        url: "../comisiones/functions/function_GuardarCalculo.php",
        dataType: "json",
        data: {
          fecha_desde:$('#txtDateFrom').val(),
          fecha_hasta:$('#txtDateTo').val(),
          vendedor:$('#chosenVendedores').val(),
          porcentaje:$('#txtPorcentaje').val(),
          monto_calculado:totalCal,
          monto_ingresado:$('#txtMontoIngresado').val(),
          facturas_seleccionadas:filasSeleccionadas,
        },
        success: function (data) {
          if(data["result"] == 1){
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 2000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Cálculo de comisión registrado con éxito!",
            });
            toIndex();
          } else {
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "¡Algo salió mal al registrar el cálculo!, " + data["result"],
            });
          }
        },
        error: function (error) {
          console.log("Error: "+error);
        },
      });
    } else {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡Ingrese una cantidad válida!",
      });
    }
  } else {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "¡Seleccione al menos una factura!",
    });
  }
}


//Función para redondear n decimales a dos y dar formato de cantidad en pantalla
function redondearDecimales(numero) {
  let redon = Math.round((numero + Number.EPSILON)*100)/100;
  var validarTotal = (redon.toFixed(2)).toString().split(".");
  //Si no tenía ningún decimal le agrega 00
  validarTotal[1] = validarTotal[1] == undefined ? "00" : validarTotal[1];
  // Si tenía solo un decimal le agrega un 0 al final
  validarTotal[1] = validarTotal[1].length == 1 ? validarTotal[1] + "0" : validarTotal[1];
  //Le da cierto formato a la cantidad
  validarTotal[0] = validarTotal[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  return validarTotal.join(".");

}


//Función para devolver al usuario al incio
function toIndex(){
  setTimeout(() => { 
    location.href = "../comisiones/";
  },2100);
}



$(document).ready(function (){

  carga_cmbVendedores();
  validarPorcentaje();

  $("#btnBuscarFacturas").on("click", function (e) {   
    vendedor = document.getElementById("chosenVendedores").value;
    tipo = document.getElementById("chosenTipo").value;
    fecha_desde = document.getElementById("txtDateFrom").value;
    fecha_hasta = document.getElementById("txtDateTo").value;
    porcentaje = document.getElementById("txtPorcentaje").value;
    porcentaje = (porcentaje/100);
    
    if(!(vendedor == "f") && !(fecha_desde == "") && !(fecha_hasta == "") && !((document.getElementById("txtPorcentaje").value)=="")){
      if(fecha_desde<fecha_hasta){

        let espanol = {
          sProcessing: "Procesando...",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
          sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
          sLoadingRecords: "Cargando...",
          searchPlaceholder: "Buscar...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "<i class='fas fa-chevron-right'></i>",
            sPrevious: "<i class='fas fa-chevron-left'></i>",
          },
          searchBuilder: {
            add: "Filtros",
            condition: "Condición",
            conditions: {
              string: {
                contains: "Contiene",
                empty: "Vacio",
                endsWith: "Finaliza con",
                equals: "Igual",
                not: "Diferente",
                notEmpty: "No vacío",
                startsWith: "Comienza con",
              },
              date: {
                after: "Después de",
                before: "Antes de",
                between: "Entre",
                empty: "Vacio",
                equals: "Igual",
                not: "Diferente",
                notBetween: "No está entre",
                notEmpty: "No vacío",
              },
              number: {
                between: "Between",
                empty: "Vacio",
                equals: "Igual",
                gt: "Mayor que",
                gte: "Mayor o igual que",
                lt: "Menor que",
                lte: "Menor o igual que",
                not: "Diferente",
                notBetween: "No está entre",
                notEmpty: "No vacío",
              },
              array: {
                contains: "Contiene",
                empty: "Vacio",
                equals: "Igual",
                not: "Diferente",
                notEmpty: "No vacío",
                without: "Sin",
              },
            },
            clearAll: "Limpiar",
            deleteTitle: "Eliminar",
            data: "Columna",
            leftTitle: "Izquierda",
            logicAnd: "+",
            logicOr: "o",
            rightTitle: "Derecha",
            title: {
              0: "Filtros",
              _: "Filtros (%d)",
            },
            value: "Opción",
            valueJoiner: "et",
          },
        };

        mostrarTabla();
        cargatxtCalculoTotal();

        tablaF = $("#tblSfacturas").DataTable({
          language: espanol,
          ajax: {
            method: "POST",
            url: "functions/getFacturas.php",
            data: {
              vendedor:vendedor,
              tipo:tipo,
              fecha_desde:fecha_desde,
              fecha_hasta:fecha_hasta,
              porcentaje:porcentaje, 
            },
          },
          restrieve: true,
          async:false,
          destroy: true,
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
                className: "btn-table-custom",
              },
              buttonLiner: {
                tag: null,
              },
            },
            buttons: [],
          },
          columns: [
            { data: "Folio" },
            { data: "Fecha factura" , className: "text-center"},
            { data: "Razon social"},
            { data: "Monto facturado", className: "text-center"},
            { data: "Monto comisionado", className: "text-center"},
            { data: "Seleccionar", width: "80px"},
          ],
        });
        
        //Activa los tooltips en datatable
        $('#tblSfacturas tbody').on('mouseover', 'tr', function () {
          $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover',
            html: true
          });
          $('[data-toggle="tooltip"]').on("click", function () {
            $(this).tooltip("dispose");
          });
        });

        $(function () {
          $("[data-toggle='tooltip']").tooltip();
        });

      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "¡La fecha de inicio no puede ser mayor a la fecha de fin!",
        });
      }
    
    } else {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡Faltan campos requeridos por ingresar!",
      });
    }
  });

  $("#btnAgregarCalculo").on("click", function (e) {
    funcionValidarFacturas();
    guardarCalculo();
  });
});