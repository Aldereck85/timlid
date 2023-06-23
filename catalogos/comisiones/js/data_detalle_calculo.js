//Variables necesarias para el cálculo de los totales
var totalCal = 0;
var totalCom = 0;
var idioma_espanol = {
  sProcessing: "Procesando...",
  sZeroRecords: "No se encontraron resultados",
  sEmptyTable: "Ningún dato disponible en esta tabla",
  sLoadingRecords: "Cargando...",
  oPaginate: {
    sFirst: "Primero",
    sLast: "Último",
    sNext: "<i class='fas fa-chevron-right'></i>",
    sPrevious: "<i class='fas fa-chevron-left'></i>",
  },
};
var tablaAbonos;

//Función para cargar los datos del cálculo
function cargaCabecera(idComision){
  $.ajax({
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
        $("#txtMontoComisionado").val(formatoCantidad(data['monto_comisionado']));
        $("#txtSaldoInsoluto").val(formatoCantidad(data['saldo_insoluto']));
        $("#txtEstatus").val(validarEstado(data['estatus']));
      }
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}


//Función para validar y mostrar el estado actual del cálculo
function validarEstado (estatus) {
  var str = "";
  if (estatus==1 || estatus==3){
    if(estatus==1){
      document.getElementById("divEditar").style.display = "block";
      document.getElementById("divEliminar").style.display = "block";
    }else{
      document.getElementById("divEditar").style.display = "none";
      document.getElementById("divEliminar").style.display = "none";
    }
    document.getElementById("divModCabeceraAbonos").style.display = "block";
    document.getElementById("btnAbonos").textContent = 'Parcialidades';
    $("#divPagarCanPagoCom").html('<button type="button" class="btnesp espAgregar float-left" name="btnPagarComision" id="btnPagarComision" onclick="pagarCanPagoCom(1)"> <span class="ajusteProyecto">Pagar comisión</span></button>');

    if(estatus==1){
      str = "Pendiente de pago";
    } else if(estatus==3){
      str = "Parcialmente pagado";
    }
  }

  else if(estatus==2){
    document.getElementById("divEditar").style.display = "none";
    document.getElementById("divEliminar").style.display = "none";
    document.getElementById("divModCabeceraAbonos").style.display = "none";
    document.getElementById("btnAbonos").textContent = 'Ver parcialidades';
    $("#divPagarCanPagoCom").html('<button type="button" class="btnesp espAgregar float-left" name="btnCancelarPago" id="btnCancelarPago" onclick="pagarCanPagoCom(2)"> <span class="ajusteProyecto">Cancelar pago</span></button>');

    str = "Pagado";
  }
  return str;
}


//Función para darle formato al porcentaje
function formatoPorcentaje(porcentaje){
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


//Función para mostrar el alert modal para eliminar un cálculo
function eliminarCalculo() {
  $("#mdleliminarcalculo").modal('show');
}


//Función para eliminar el cálculo de una comisión
function eliminarComision () {
  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_eliminarCalculo.php",
    dataType: "json",
    data: {
      idComision:$("#idComision").val(),
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
          msg: "¡Cálculo de comisión eliminado con éxito!",
        });
        toIndex();
      } else if (data["result"] == 0){
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 2000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "¡No es posible eliminar el calculo con pagos registrados!",
        });
      }else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "¡Algo salio mal al eliminar el cálculo!, " + data["result"],
        });
      }
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });

}


//Función para rediccionar al incio
function toIndex(){
  setTimeout(() => { 
    location.href = "../comisiones/";
  },2100);
}


//Función para mostrar los datos del modal de abonos
function mostrarDatosAbonos() {
  let valortxtAbono = document.getElementById("txtModMontoAbono").value;
  $("#txtModMontoAbono").val(formatoCantidad(valortxtAbono));

  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/getSaldoInsoluto.php",
    dataType: "json",
    data: {
      idComision:$("#idComision").val(),
    },
    success: function (data) {
      if(!(data=="")){
        $("#txtModSaldoInsoluto").val(formatoCantidad(data['saldo_insoluto']));
      }
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}


//Función para validar el monto del abono
function validarAbono () {
  let montoAbono = parseFloat(document.getElementById("txtModMontoAbono").value);
  let cuenta = $("#chosenCuenta").val();
  let salins = parseFloat($("#txtModSaldoInsoluto").val().replace(/ /g, ""));

  if (montoAbono>0 && cuenta != 'f') {
    var numA = numAbonos();
    var totalsalins = salins-montoAbono;
    if (numA!=0) { //No es la primera parcialidad
      if (salins>=montoAbono) {
        if (totalsalins == 0){ //El monto de la parcialidad es igual al saldo insoluto (se ha liquidado la comisión)
          registrarAbono(2, montoAbono, totalsalins, cuenta);
        } else { // El monto de la parcialidad no es igual al saldo insoluto (Aun falta completar el pago)
          registrarAbono(3, montoAbono, totalsalins, cuenta);
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
          msg: "¡El monto de la parcialidad no puede ser mayor que el saldo insoluto!",
        });
        $("#txtModMontoAbono").val("");
      } 
    } else { //Sí es la primera parcialidad
      if (totalsalins == 0) {  //El total del saldo insoluto es igual a cero (paga la comisión) 
        pagarComision(cuenta);
      } else {
        registrarAbono(3, montoAbono, totalsalins, cuenta); //Es la primera parcialidad
      }
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
      msg: "¡El monto de la parcialidad no puede ser menor o igual a 0!",
    });
    $("#txtModMontoAbono").val("");
  }
}


//Función para registrar la parcialidad
function registrarAbono(estatus, montoAbono, totalsalins, cuenta, is_reloadTable = 1) {
  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_agregarAbono.php",
    dataType: "json",
    data: {
      estatusCom:estatus,
      montoA:montoAbono,
      totalsaldo:totalsalins,
      idComision:$("#idComision").val(),
      fechaParcialidad:$("#txtFechaParcialidad").val(),
      cuenta: cuenta
    },
    success: function (data) {
      if(data["result"]==1){
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 2000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: "¡Parcialidad registrada con éxito!",
        });
        if(is_reloadTable == 1){
          tablaAbonos.ajax.reload();
        }
        cargarCMBcuentas("chosenCuenta");
        selectCuenta.set('f');
        $("#txtFechaParcialidad").val("");
        $("#txtModMontoAbono").val(formatoCantidad(0));
        $("#txtEstatus").val(validarEstado(data['estatus']));
        $("#txtModSaldoInsoluto").val(formatoCantidad(totalsalins));
        $("#txtSaldoInsoluto").val(formatoCantidad(totalsalins));
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "¡Algo salio mal al registrar la parcialidad!, " + data["result"],
        });
      }
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}


//Función para eliminar la parcialidad
function eliminarAbono (idAbono, montoAbono, numFila) {
  var totalfilas = tablaAbonos.rows().count();

  if(totalfilas==numFila){
    var salins = parseFloat($("#txtModSaldoInsoluto").val().replace(/ /g, ""));
    var numAbon = numAbonos(); 
    var estatus = 0;

    salins = salins + montoAbono;

    if(numAbon == 1){
      estatus = 1;
    } else {
      estatus = 3;
    }


    $.ajax({
      async: false,
      type: "POST",
      url: "../comisiones/functions/function_eliminarAbono.php",
      dataType: "json",
      data: {
        totalsalins:salins,
        estatus:estatus,
        idAbono:idAbono,
        idComision:$("#idComision").val(),
      },
      success: function (data) {
        if(data["result"]==1){
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡La parcialidad número "+numFila+" ha sido elimianda con éxito!",
          });
          tablaAbonos.ajax.reload();
          cargarCMBcuentas("chosenCuenta");
          $("#txtFechaParcialidad").val("");
          $("#txtModMontoAbono").val(formatoCantidad(0));
          $("#txtEstatus").val(validarEstado(data['estatus']));
          $("#txtModSaldoInsoluto").val(formatoCantidad(salins));
          $("#txtSaldoInsoluto").val(formatoCantidad(salins));
        } else {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡Algo salió mal al eliminar la parcialidad!, " + data["result"],
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
      msg: "¡Sólo se puede eliminar la última parcialidad!",
    });
  }
}


//Función para recuperar el número de parcialidades del cálculo
function numAbonos() {
  var totalnabonos=null;
  $.ajax({
    async: false,
    type: "POST",
    url: "../comisiones/functions/function_recuperaNumAbonos.php",
    dataType: "json",
    data: {
      idComision:$("#idComision").val(),
    },
    success: function (data) {
      if(data!=""){
        totalnabonos = data["numeroAbonos"];
      }
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
  return totalnabonos;
}


//Función para pagar la comisión por completo
function pagarComision(cuenta) {
  var numA = numAbonos();
  var montoLiq = parseFloat($("#txtSaldoInsoluto").val().replace(/ /g, ""));
  if(cuenta != 'f'){
    $.ajax({
      async: false,
      type: "POST",
      url: "../comisiones/functions/function_pagarComision.php",
      dataType: "json",
      data: {
        idComision:$("#idComision").val(),
        numA:numA,
        montoLiq:montoLiq,
        cuenta:cuenta
      },
      success: function (data) {
        if(data["result"]==1){
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Cálculo de comisión pagado con éxito!",
          });
          cargarCMBcuentas("chosenCuenta");
          $("#txtFechaParcialidad").val("");
          $("#txtEstatus").val(validarEstado(data['estatus']));
          $("#txtModSaldoInsoluto").val(formatoCantidad(0));
          $("#txtSaldoInsoluto").val(formatoCantidad(0));
  
        } else if(data["result"]==2){
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Cálculo de comisión pagado con éxito!",
          });
          //tablaAbonos.ajax.reload();
          $("#txtFechaParcialidad").val("");
          $("#txtEstatus").val(validarEstado(data['estatus']));
          $("#txtModSaldoInsoluto").val(formatoCantidad(0));
          $("#txtSaldoInsoluto").val(formatoCantidad(0));
        } else {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: "¡Algo salió mal al pagar la comisión!, " + data["result"],
          });
        }
      },
      error: function (error) {
        console.log("Error: "+error);
      },
    });
  }else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Cuenta bancaria obligatoria"
    });
  }
  
}


//Función mostrar las opciones del alert modal dependiendo si se va a pagar o si se va a cancelar el pago
function pagarCanPagoCom(acc) {
  $("#mdlcancelarpagarcomision").modal('show');
  var numA = numAbonos();
  if(acc==1) {

    $("#mdlTituloCPC").html('<h5 id="titlemdl" class="modal-title" id="exampleModalLabel">Pagar cálculo</h5>');
    if(numA==0) {
      $("#msjPagoComision").html('<center><h4>¿Deseas liquidar este cálculo?</h4></center>');
    } else {
      $("#msjPagoComision").html('<center><h4>Este cálculo tiene parcialidades ('+numA+'), ¿desea liquidarlo?</h4></center>');
    }
    let html = '<div class="form-group">'+
                    '<label for="usr">Cuenta bancaria:</label>'+
                    '<select name="cmbCuenta2" id="chosenCuenta2">'+
                        '<option disabled selected value="f">Seleccione una cuenta</option>'+
                    '</select>'+
                    '<div class="invalid-feedback" id="invalid-cuenta2">gg</div>'+
                '</div>';
    $("#cmbCuentaPagoComision").html(html);
    cargarCMBcuentas("chosenCuenta2");
    
    new SlimSelect({
      select: '#chosenCuenta2',
      deselectLabel: '<span class="">✖</span>'
    });



    $("#mdlBtnCPC").html('<a class="btn btn-primary" id="btnModPagarCalculo" onclick="botonModCanPagoCom(1,'+numA+')">Pagar cálculo</a>');

  } else if(acc==2) {
    $("#cmbCuentaPagoComision").html('');

    $("#mdlTituloCPC").html('<h5 id="titlemdl" class="modal-title" id="exampleModalLabel">Cancelar pago</h5>');
    if(numA==0){
      $("#msjPagoComision").html('<center><h4>¿Desea cancelar el pago de este cálculo?</h4></center>');
    } else {
      $("#msjPagoComision").html('<center><h4>Todas las parcialidades de este pago se eliminarán ('+numA+'), ¿desea cancelar el pago?</h4></center>');
    }
    $("#mdlBtnCPC").html('<a class="btn btn-primary" id="btnModCancelarPago" onclick="botonModCanPagoCom(2,'+numA+')">Cancelar pago</a>');

  }
}


//Función para cancelar o pagar el cálculo
function botonModCanPagoCom(acc, numA) {
  if(acc==1) {
    let cuenta = $("#chosenCuenta2").val();
    if(numA==0) {
      pagarComision(cuenta);
    } else {
      let liquidarCalculo = parseFloat($("#txtSaldoInsoluto").val().replace(/ /g, ""));
      registrarAbono(2, liquidarCalculo, 0, cuenta, 0);
    }
  } else if(acc==2) {
    $.ajax({
      async: false,
      type: "POST",
      url: "../comisiones/functions/function_cancelarPago.php",
      dataType: "json",
      data: {
        idComision:$("#idComision").val(),
      },
      success: function (data) {
        if(data["result"]==1){
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 2000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡Pago cancelado con éxito!",
          });
          cargarCMBcuentas("chosenCuenta");
          $("#txtFechaParcialidad").val("");
          $("#txtEstatus").val(validarEstado(data['estatus']));
          $("#txtModSaldoInsoluto").val(formatoCantidad(data['monto_comision']));
          $("#txtSaldoInsoluto").val(formatoCantidad(data['monto_comision']));
          /* if(numA>0){
            tablaAbonos.ajax.reload();
          } */
        }
      },
      error: function (error) {
        console.log("Error: "+error);
      },
    });
  }
  $("#mdlcancelarpagarcomision").modal('hide');
}

$(document).ready(function () {
  document.getElementById("divEditar").style.display = "none";
  document.getElementById("divEliminar").style.display = "none";
  cargarCMBcuentas("chosenCuenta");
  var idComision = $("#idComision").val();
  var idVendedor = $("#idVendedor").val();

  $("#btnEditar").attr("href","../comisiones/edit_detalle_calculo.php?idComision="+idComision+"&idVendedor="+idVendedor);
  cargaCabecera(idComision);

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
    order: [[2, 'asc']],
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
      method: "POST",
      url: "functions/getFacturasCalculo.php",
      data: {idComision:idComision},
    },
    columns: [
      { data: "Folio", width: "90px"},
      { data: "fecha_factura" , width: "200px" },
      { data: "razon_social" },
      { data: "monto_facturado", width: "350px" },
      { data: "monto_comisionado", width: "350px", className: "text-left" },
      { data: "Seleccionar" },
    ],
  });
  tablaFacturasComision.column(5).visible(false);
  
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

  $("#btnModEliminarCal").off("click").click(function () {
    $("#mdleliminarcalculo").modal('hide');
    eliminarComision();
  });

  $("#btnAbonos").on("click", function (e) {
    $("#txtModMontoAbono").val(0);
    $("#txtFechaParcialidad").val("");
    $("#modalAbonos").modal('show');
    mostrarDatosAbonos();
  });

  $("#modalAbonos").on("shown.bs.modal", function () { 
    tablaAbonos=$("#tblModAbonos").DataTable({
      language: idioma_espanol,
      search: false,
      destroy: true,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 6,
      responsive: true,
      lengthChange: false,
      bFilter: false,
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
        url: "functions/getAbonos.php",
        data: {idComision:$("#idComision").val()},
      },
      columns: [
        { data: "Fecha" },
        { data: "Monto_abono" },
        { data: "Nombre_usuario" },
        { data: "Eliminar", width: "50px" },
      ],
    });
  });

  $("#btnModCerrar").off("click").click(function () {
    $("#modalAbonos").modal('hide');
  });

  $("#btnModAgregarAbono").off("click").click(function () {
    if($("#txtModMontoAbono").val()!=""){
      if($("#txtFechaParcialidad").val()!="") {
        validarAbono();
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/warning_circle.svg",
          msg: "¡Ingrese la fecha de la parcialidad!",
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
        msg: "¡Ingrese un monto correcto para la parcialidad!",
      });
    }
  });
});

function cargarCMBcuentas(input) {
  $.ajax({
    type: "POST",
    url: "functions/function_cmbCuentas.php",
    dataType: "json",
    success: function (data) {
      
      html = "<option disabled selected value='f'>Seleccione una cuenta</option>";
      let TypeAcount =  0;
      $.each(data, function (i) {
        
        if(TypeAcount != data[i].tipoCuenta){
          if(data[i].tipoCuenta == 1){
            html += '<optgroup label="Cheques"> ';
          }else if (data[i].tipoCuenta == 2){
            html += '<optgroup label="Otras"> ';
          }
          TypeAcount = data[i].tipoCuenta;
        }

        if (i == data.length) {
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            ": $"+data[i].saldo_actual+
            "</option></optgroup>";
        } else {
          html +=
            '<option value="' +
            data[i].PKCuenta +
            '">' +
            data[i].Cuenta +
            ": $"+data[i].saldo_actual+
            "</option>";
        }
      });
      $("#"+input).html(html);
    },
    error: function (error) {
      console.log("Error: "+error);
    },
  });
}

var selectCuenta = new SlimSelect({
  select: '#chosenCuenta',
  deselectLabel: '<span class="">✖</span>'
});