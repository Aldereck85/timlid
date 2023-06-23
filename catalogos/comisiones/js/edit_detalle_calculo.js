//Declaración de variables globales
var totalCal = 0;
var totalCom = 0;

var gfecha_desde = "";
var gfecha_hasta = "";
var gporcentaje = 0;

var gcambioMonto = "";

var idFacturasSeleccionadas = [];


//Función para cargar los datos del cálculo
function cargaCabecera(idComision) {
  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/getDetalleCalculo.php",
    dataType: "json",
    data: { idComision: idComision },
    success: function (data) {
      if (!(data=="")) {
        $("#txtVendedor").val(data['nombre_vendedor']);
        $("#txtFechaRegistro").val(data['fecha_registro']);
        $("#txtFechaDesde").val(data['fecha_ini']);
        $("#txtFechaHasta").val(data['fecha_fin']);
        $("#txtPorcentaje").val(formatoPorcentaje(data['porcentaje']));
        totalCal=parseFloat(data['monto_calculado']);
        $("#txtMontoCalculado").val(formatoCantidad(data['monto_calculado']));
        totalCom=parseFloat(data['monto_comisionado']);
        $("#txtMontoComisionado").val(parseFloat(formatoCantidad(data['monto_comisionado']).replace(/ /g, "")).toFixed(2));
        $("#txtSaldoInsoluto").val(formatoCantidad(data['saldo_insoluto']));
        if(data['estatus']==1){
          $("#txtEstatus").val("Pendiente de pago");
        } else if(data['estatus']==2){
          $("#txtEstatus").val("Pagado");
        } else if(data['estatus']==3){
          $("#txtEstatus").val("Parcialmente pagado");
        }
      }
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}


//Función para validar las fechas dadas por el usuario
function validarFechas() {
  gfecha_desde=document.getElementById("txtFechaDesde").value;
  gfecha_hasta=document.getElementById("txtFechaHasta").value;
  var i=0;

  if(gfecha_desde<gfecha_hasta){
    var fechasSel=traerFechasSel();
    fechasSel.forEach(function(fecha) {
      if(fecha<gfecha_desde || fecha>gfecha_hasta){
        i=i+1;
      }
    });
    if(i==0){
      return i;
    } else {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/warning_circle.svg",
        msg: "¡Las fechas no puede excluir a facturas seleccionadas ("+i+")!",
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
      msg: "¡La fecha de inicio no puede ser mayor a la fecha de fin!",
    });
  }
}


//Función para validar el porcentaje dado por el usuario
function validarPorcentaje() {
  let valPorcentaje = $("#txtPorcentaje").val();
  var j=0;

  if (!valPorcentaje==""){
    if(valPorcentaje <= 100){
      if(valPorcentaje > 0){
        j=0;
      } else {
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
        j=j+1;
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
        msg: "¡El porcentaje no debe de ser mayor a 100!",
      });
      $('#txtPorcentaje').val("");
      j=j+1;
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
      msg: "¡Por favor, introduzca un valor de porcentaje!",
    });
    j=j+1;
  }
  return j;
}


//Función para guardar las fechas de las de las facturas que son seleccionadas
function traerFechasSel() {
  var fechasSeleccionadas = [];

  //Iteración para cada fila de la tabla
  $("#tblFacturas tr").each(function(){
    var tds = $(this).find("td");

    if(tds.find(":checkbox").prop("checked") == true){
      fechasSeleccionadas.push(tds.eq(1).html());
    }
  });
  return fechasSeleccionadas;
}


//Función para darle formato al porcentaje
function formatoPorcentaje(porcentaje) {
  var p = parseFloat(porcentaje);
  p=p*100;
  return p;
}


//Función para darle formato a las cantidades
function formatoCantidad(cantidad) {
  let fc = parseFloat(cantidad);
  let redon = Math.round((fc + Number.EPSILON)*100)/100;
  var validarNumero = (redon.toFixed(2)).toString().split(".");
  //Si no tenía ningún decimal le agrega 00
  validarNumero[1] = validarNumero[1] == undefined ? "00" : validarNumero[1];
  // Si tenía solo un decimal le agrega un 0 al final
  validarNumero[1] = validarNumero[1].length == 1 ? validarNumero[1] + "0" : validarNumero[1];
  //Le da cierto formato a la cantidad
  validarNumero[0] = validarNumero[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  return validarNumero.join(".");
}


//Función para sumar o restar los montos comisionados del total calculado
function sumar(sender) {
  let salins = parseFloat($("#txtSaldoInsoluto").val().replace(/ /g, ""));
  
  //Arreglo para guardar el ID de la factura que trae en el atributo 'value' del check
  check = sender.getAttribute("value").split("-");
  montoPorFactura = parseFloat(check[1]);
  
  if(sender.checked){
    salins += montoPorFactura;
    totalCal += montoPorFactura;
    totalCom += montoPorFactura;
  } else {
    totalCal = totalCal - montoPorFactura;
    totalCom = totalCom - montoPorFactura;
    salins = salins - montoPorFactura;
  }
  let totalCalValidado = formatoCantidad(totalCal);
  let totalComValidado = formatoCantidad(totalCom);
  $("#txtMontoCalculado").val(totalCalValidado);
  $("#txtSaldoInsoluto").val(formatoCantidad(salins));
  totalComValidado=totalComValidado.replace(/ /g, "");
  $("#txtMontoComisionado").val(parseFloat(totalComValidado).toFixed(2));
}


//Función para cargar las facturas que entran en el rango de fechas pero que no están vinculadas con la comisión
function traerFacturasComision() {
  porce=((document.getElementById("txtPorcentaje").value)/100)

  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_RecuperaFacturasEdit.php",
    dataType: "json",
    data: {
      fecha_desde:document.getElementById("txtFechaDesde").value,
      fecha_hasta:document.getElementById("txtFechaHasta").value,
      vendedor:document.getElementById("idVendedor").value,
      porcentaje:porce,
    },
    success: function (data) {
      if(!data==""){
        tablaFacturasComision.rows.add(data).draw();
      }
    },
  });
}


//Función para recuperaf las facturas que han sido seleccionadas
function recuperaIdFacturasSeleccionadas () {
  idFacturasSeleccionadas = [];
  $("#tblFacturas tr").each(function(){
    var tds = $(this).find("td");

    if(tds.find(":checkbox").prop("checked") == true){
      checkArray = tds.find(":checkbox").val().split("-");
      idFacturasSeleccionadas.push(parseFloat(checkArray[0]));
    }
  });
  console.log(idFacturasSeleccionadas);
  return idFacturasSeleccionadas;
}


//Función para consultar todas las facturas que entran en el rango de fechas y después mostrarlas
function consultarFacturas() {
  
  var porParaMC=(($("#txtPorcentaje").val())/100);
  totalCal = 0;
  totalCom = 0;

  $("#tblFacturas tr").each(function(){
  
    var tds = $(this).find("td");
    var checkArray = "";
    if(!(tds.length == 0)){
      checkArray=tds.find(":checkbox").val().split("-");
    }

    if(tds.find(":checkbox").prop("checked") == true) {
      var mcpf=checkArray[2]*porParaMC;
      let tipoDoc = tds.find(":checkbox").attr("data-tipo");
      tds.eq(4).html("$"+formatoCantidad(mcpf));
      tds.eq(5).html('<input class=contarFila type=checkbox name=invoiceSelected onclick=sumar(this) data-tipo="'+tipoDoc+'" value="'+checkArray[0]+'-'+mcpf+'-'+checkArray[2]+'" checked>');
      totalCal += mcpf;
      totalCom += mcpf;
    } 
    else {
      tablaFacturasComision.row($(this)).remove().draw();
      }
  });

  let totalCalValidado = formatoCantidad(totalCal); 
  let totalComValidado = formatoCantidad(totalCom);
  $("#txtMontoCalculado").val(totalCalValidado);
  totalComValidado=totalComValidado.replace(/ /g, "");
  $("#txtMontoComisionado").val(parseFloat(totalComValidado).toFixed(2));

  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_actualizarFacturasEdit.php",
    dataType: "json",
    data: {
      fecha_desde:$("#txtFechaDesde").val(),
      fecha_hasta:$("#txtFechaHasta").val(),
      vendedor:$("#idVendedor").val(),
      comision:$("#idComision").val(),
      porcentaje:(($("#txtPorcentaje").val())/100),
    },
    success: function (data) {
      if(!data==""){
        var aIdFacSel=recuperaIdFacturasSeleccionadas();
        $.each(data, function (i) {
          var id = aIdFacSel.indexOf(data[i].id);
          if(id==-1){
            tablaFacturasComision.row.add(data[i]).draw();
          }
        });
      }
    },
  });
}


//Función que actualiza el cálculo con las facturas seleccionadas y con todos los datos modificados
function actualizarCalculo() {
  var fMontoCom = parseFloat($('#txtMontoComisionado').val());

  consultarFacturas(); 

  var filasSeleccionadas = [];

  //Iteración para cada fila de la tabla
  $("#tblFacturas tr").each(function(){
    var itemCalculo = {};

    var tds = $(this).find("td");

    if(tds.find(":checkbox").prop("checked") == true){
      check = tds.find(":checkbox").val().split("-");
      itemCalculo.idFactura=check[0];
      itemCalculo.MontoCom=check[1];
      itemCalculo.tipoDoc = tds.find(":checkbox").attr("data-tipo");
      //Metemos el objeto itemCalculo en un arreglo
      filasSeleccionadas.push(itemCalculo);
    } 
  });

  var totalMA = parseFloat(montoTotalAbonos());

  if(!($.isEmptyObject(filasSeleccionadas))){
    if(fMontoCom>=totalMA){
      $.ajax({
        async: false,
        type: "POST",
        url: "../comisiones/functions/function_actualizarCalculo.php",
        dataType: "json",
        data: {
          idComision:$('#idComision').val(),
          fecha_desde:$('#txtFechaDesde').val(),
          fecha_hasta:$('#txtFechaHasta').val(),
          porcentaje:$('#txtPorcentaje').val(),
          monto_calculado:totalCal,
          monto_ingresado:fMontoCom,
          totalMA:totalMA,
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
              msg: "¡Comisión actualizada con éxito!",
            });
            volverDetalleComision();
          } else {
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "¡Algo salio mal al actualizar la comisión!, " + data["result"],
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
        msg: "¡El total ingresado no puede ser menor que el total de las parcialidades de este cálculo!",
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
      msg: "¡Seleccione almenos una factura!",
    });
  }
}


//Función para volver al detalle de la comisión
function volverDetalleComision(){
  setTimeout(() => { 
    location.href = "../comisiones/detalle_calculo.php?idComision="+$("#idComision").val()+"&idVendedor="+$("#idVendedor").val()+"";
  },2100);
}


//Función que recupera el total de los montos de las parcialidades si es que la comisión tiene
function montoTotalAbonos() {
  var ta=0;
  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_recuperaMontoTotalAbonos.php",
    dataType: "json",
    data: {
      idComision:$('#idComision').val(),
    },
    success: function (data) {
      if(!(data['totalAbonos']=="")){
        ta = data['totalAbonos'];
      }
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
  return ta;
}



$(document).ready(function () {

  idComision = $("#idComision").val();
  idVendedor = $("#idVendedor").val();

  cargaCabecera(idComision);

  
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  var topButtons = [];
  tablaFacturasComision=$("#tblFacturas").DataTable({
    language: idioma_espanol,
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
    ajax: {
      async: false,
      method: "POST",
      url: "functions/getFacturasCalculo.php",
      data: {idComision:idComision},
    },
    columns: [
      { data: "Folio" },
      { data: "fecha_factura", width: "200px" },
      { data: "razon_social" },
      { data: "monto_facturado", width: "350px" },
      { data: "monto_comisionado", width: "350px" },
      { data: "Seleccionar", width: "90px"},
    ],
  });
   
  
  traerFacturasComision();
 

  //Activa los tooltips en datatable
  $('#tblFacturas tbody').on('mouseover', 'tr', function () {
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

  $("#btnActualizarFacturas").on("click", function (e) {
    gporcentaje=document.getElementById("txtPorcentaje").value;
    gporcentaje =gporcentaje/100;

    var fecha_validada=validarFechas();
    var porcentaje_validado=validarPorcentaje();
    if(fecha_validada==0 && porcentaje_validado==0) {
        $("#mdlActualizarFacturas").modal('show');
      }
  });

  $("#mdlBtnActualizarFacturas").off("click").click(function () {
    $("#mdlActualizarFacturas").modal('hide');
    consultarFacturas();
  });

  $("#btnActualizarCalculo").off("click").click(function () {
    var fecha_validada=validarFechas();
    var porcentaje_validado=validarPorcentaje();
    if(fecha_validada==0 && porcentaje_validado==0) {
      $("#mdlactualizarcalculo").modal('show');
    }
  });

  $("#btnModActualizarCal").off("click").click(function () {
    $("#mdlactualizarcalculo").modal('hide');
    actualizarCalculo();
  });
});