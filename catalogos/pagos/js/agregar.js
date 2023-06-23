var arID = [];
let string = "";
let stringtamaño;
let stringSincoma;
var jsonString;
var tablaD;
var Total;
var flagSaldoSuficiente;
function tests() {}

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
function sumar(sender) {
  inputID = "cmbCuenta";
  invalidDivID = "invalid-cuenta";
  //Saldo de la cuenta seleccionada
  var saldoCuenta = $("select[name=" + inputID + "] option")
    .filter(":selected")
    .text();
  saldoCuenta = saldoCuenta.split("$")[1];
  saldoCuenta = parseFloat(saldoCuenta);
  console.log(saldoCuenta);
  //Comprueba si se selecciono una cueta antes de seleccionar que va a pagar para comprobar que se tenga saldo suficiente.
  if (
    $("select[name=" + inputID + "] option")
      .filter(":selected")
      .val() == "f"
  ) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("Seleccione una cuenta con saldo suficiente");
    //Des selecciona el check que se habia marcado
    $(sender).prop("checked", false);
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

    imput = document.getElementById("txtTotal");
    //Optiene lo que este en value del check que se le dio click y lo pone en un arreglo separandolo en el coma
    arreglo = sender.getAttribute("value").split(",");
    //Eliomina los espacios de el importe que viene en el value
    cantidad = arreglo[1].replace(/[ ]/g, "");
    sumaTotal = imput.value = parseFloat(imput.value.replace(/[ ]/g, ""), 10);
    console.log(sumaTotal);

    // Si está check suma la cantidad y lo agrega al arreglo.
    if (sender.checked) {
      //Comprueba que el saldo sea suficiente para pagar la nueva factura
      if (sumaTotal + parseFloat(cantidad, 10) < saldoCuenta) {
        arID[arreglo[0]] = arreglo[1];
        sumaTotal = sumaTotal + parseFloat(cantidad, 10);
      } else {
        $(sender).prop("checked", false);
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
    } else {
      var key = arreglo[0];
      delete arID[key];
      sumaTotal = sumaTotal - parseFloat(cantidad, 10);
    }
    //Pone el total en el imput
    imput.value = " " + sumaTotal.toLocaleString("en-EU").replace(/[,]/g, " ");
    Total = sumaTotal;
  }
}
//Guardar en un arreglo los ids de los checkbox de las cuentas por pagar
function countChecks(id) {
  var totale = 0;
  $("input:checkbox").change(function () {
    ar.length = 0;
    $(".check").each(function () {
      if ($(this).is(":checked")) {
        var separada = $(this).val().split(",");
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
function click(select) {
  alert("Ay!");
}
$(document).ready(function () {
  var valorx;
  $("select[name=cmbCuenta]").focus(function () {
    alert("Ay!");
    valorx = $("#cmbCuenta").val();
  });

  //Comprobar el saldo cada que se cambia de cuenta
  $("#cmbCuenta").on("change", function () {
    inputID = "cmbCuenta";
    invalidDivID = "invalid-cuenta";
    imput = document.getElementById("txtTotal");
    sumaTotal = imput.value = parseFloat(imput.value.replace(/[ ]/g, ""), 10);
    var saldoCuenta = $("select[name=" + inputID + "] option")
      .filter(":selected")
      .text();
    saldoCuenta = saldoCuenta.split("$")[1];
    saldoCuenta = parseFloat(saldoCuenta);
    //Si el saldo de la cuenta es mayor a el total de las cuetas seleccionadas
    if (sumaTotal < saldoCuenta) {
      $("#" + inputID).removeClass("is-invalid");
      $("#" + invalidDivID).hide();
      $("#" + invalidDivID).text(textInvalidDiv);
      //Bandera para saber si el saldo de la cuenta es suficiente a la hora de guardar
      flagSaldoSuficiente = true;
      //Si el saldo es menor a el total a pagar de las cuentas seleccionadas
    } else {
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
      $("select[name=" + inputID + "] option[value=f]").attr("selected", true);
    }
    console.log(sumaTotal);
    //alert( saldoCuenta );
  });
  total = 0;

  //Saca los ids de los checks en true
  //console.log(JSON.stringify(countChecks()))

  $("#btnguardarDetalle").click(function () {
    // validarImputs();
    Posible();
  });

  crearSelects();
  filtroProveedorTabla();
  cargarCabeceras();
  cargarhisto();
  loadModal();

  //Inicializar los tooltip
  $('[data-toggle="tooltip"]').tooltip({
    //Para que desaparescan cuando se sale del elemento
    trigger: "hover",
  });

  function crearSelects() {
    new SlimSelect({
      select: "#cmbProveedor",
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: "#cmbCuenta",
      deselectLabel: '<span class="">✖</span>',
    });
    new SlimSelect({
      select: "#cmbTipoPag",
      deselectLabel: '<span class="">✖</span>',
    });
  }
  /* Verificar si el valor de un input cambio para activar el boton guardar */
  $(".edit").each(function () {
    var elem = $(this);

    // Save current value of element
    elem.data("oldVal", elem.val());

    // Look for changes in the value
    elem.bind("propertychange change keyup input paste", function (event) {
      // If value has changed...
      if (subtotal != elem.val()) {
        // Updated stored value
        elem.data("oldVal", elem.val());
        //console.log(subtotal)
        //console.log(elem.val())
        $("#btnguardarDetalle").removeAttr("disabled");
        $("#btnguardarDetalle").removeAttr("style");
        $("#spanbutton").tooltip("disable");

        // Do action
      } else {
        $("#btnguardarDetalle").attr("disabled");
      }
    });
  });

  ///
  //// FUncion para editar los datos de la cabecera
  ///
  function Updatecabecera() {
    var inputSubtotal = $("#subtotal").val();
    var inmputImporte = $("#txtimporte").val();
    var inputIva = $("#_txtiva").val();
    var inmputIeps = $("#_txtieps").val();

    var id = $("#user_id").val();

    $.post(
      "../cuentas_pagar/functions/Update.php",
      {
        action: "1",
        id: id,
        inputSubtotal: inputSubtotal,
        inmputImporte: inmputImporte,
        inputIva: inputIva,
        inmputIeps: inmputIeps,
      },
      /* Funcion segunda */
      function (data, status) {
        if (status == "success") {
          console.log("tamo bien");
          $("#mdlsavealert").hide();
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
        } else {
          alert("Algo ha fallado, revisa tus entradas");
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
    return new Promise((resolve) => {
      //here our function should be implemented
      var html = "";
      //Consulta los proveedores de la empresa
      $.ajax({
        type: "POST",
        url: "functions/addcontroller.php",
        dataType: "json",
        data: { clase: "get_data", funcion: "get_proveedorCombo" },
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
          if ($("#provee").attr("value") != undefined) {
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
  function cargarCMBcuentasCheques() {
    return new Promise((resolve) => {
      $.ajax({
        type: "POST",
        url: "functions/addcontroller.php",
        dataType: "json",
        data: { clase: "get_data", funcion: "get_cuenta_cheque" },
        success: function (data) {
          // console.log("data de cuenta: ", data);
          $.each(data, function (i) {
            if (i == 0) {
              html +=
                '<option disabled value="f" selected hidden>Seleccione una cuenta</option>';
              html += '<optgroup label="Cheques">';
              html +=
                '<option value="' +
                data[i].PKCuenta +
                '">' +
                data[i].Cuenta +
                ": $" +
                data[i].saldo_actual +
                "</option>";
            } else if (i == data.length) {
              html +=
                '<option value="' +
                data[i].PKCuenta +
                '">' +
                data[i].Cuenta +
                ": $" +
                data[i].saldo_actual +
                "</option></optgroup>";
            } else {
              html +=
                '<option value="' +
                data[i].PKCuenta +
                '">' +
                data[i].Cuenta +
                ": $" +
                data[i].saldo_actual +
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
  async function cargarCMBcuentasOtros(tamaño) {
    await cargarCMBcuentasCheques();
   //  console.log(html);
    var htmlO = "";
    $.ajax({
      type: "POST",
      url: "functions/addcontroller.php",
      dataType: "json",
      data: { clase: "get_data", funcion: "get_cuenta_otras" },
      success: function (data) {
      //   console.log("data de cuenta: ", data);
        $.each(data, function (i) {
          if (html == "" || html == undefined) {
            htmlO +=
              '<optgroup label="Otras"> <option disabled value="f" selected>Seleccione una cuenta</option>';
            htmlO +=
              '<option value="' +
              data[i].PKCuenta +
              '">' +
              data[i].Cuenta +
              ": $" +
              data[i].saldo_actual +
              "</option>";
          } else if (i == 0) {
            htmlO +=
              '<optgroup label="Otras"><option value="' +
              data[i].PKCuenta +
              '">' +
              data[i].Cuenta +
              ": $" +
              data[i].saldo_actual +
              "</option>";
          } else if (i == data.length) {
            htmlO +=
              '<option value="' +
              data[i].PKCuenta +
              '">' +
              data[i].Cuenta +
              ": $" +
              data[i].saldo_actual +
              "</option></optgroup>";
          } else {
            htmlO +=
              '<option value="' +
              data[i].PKCuenta +
              '">' +
              data[i].Cuenta +
              ": $" +
              data[i].saldo_actual +
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
      type: "POST",
      url: "functions/addcontroller.php",
      dataType: "json",
      data: { clase: "get_data", funcion: "get_cuenta" },
      success: function (data) {
        // console.log("data de cuenta: ", data);
        $.each(data, function (i) {
          if (i == 0) {
            html +=
              '<option disabled value="f" selected>Seleccione una cuenta</option>';
            html +=
              '<option value="' +
              data[i].PKCuenta +
              '">' +
              data[i].Cuenta +
              " => $1600" +
              "</option>";
          } else {
            html +=
              '<option value="' +
              data[i].PKCuenta +
              '">' +
              data[i].Cuenta +
              " => $1600" +
              "</option>";
          }
        });

        $("#cmbCuenta").append(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
        console.log("Error");
      },
    });
  }

  function savePagos() {
    let redFlag;

    if (arID.length > 0) {
      let ramdon = r;
      let tipoPago = $("select[name=cmbTipoPag] option")
        .filter(":selected")
        .val();
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
          ramdon_str: ramdon,
        },
        dataType: "json",
        success: function (data, response) {
         //  console.log("data de cabecera: " + data);
          if (response[0]) {
           //  console.log("Respuesta 0 " + response[0]);
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
            var txtdescrip = arreglo[0] + "->" + arreglo[1];
            var txtreferencia = document.getElementById("txtreferencia").value;
            var origenCE = $("select[name=cmbCuenta] option")
              .filter(":selected")
              .val();
            let tiopo_movimi = 5;

            arID.forEach(function (importe, index) {
              inserMov(
                txtdescrip,
                importe,
                txtreferencia,
                tiopo_movimi,
                origenCE,
                index,
                ramdon
              );
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
        error: function (jqXHR, exception, data, response) {
          var msg = "";
          if (jqXHR.status === 0) {
            msg = "Not connect.\n Verify Network.";
          } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
          } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
          } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
          } else if (exception === "timeout") {
            msg = "Time out error.";
          } else if (exception === "abort") {
            msg = "Ajax request aborted.";
          } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
          }
          console.log("data de cabecera: " + data);
          console.log("response de cabecera: " + response);
          console.log("excepcion " + exception);
          console.log(msg);
        },
      });
      //Si no se selecciona ninguna cuenta por pagar
    } else {
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

  function inserMov(
    txtdescrip,
    importe,
    txtreferencia,
    tiopo_movimi,
    origenCE,
    index,
    ramdon
  ) {
    if (
      tiopo_movimi != "" &&
      txtreferencia != "" &&
      txtdescrip != "" &&
      importe != "" &&
      origenCE != "" &&
      index != "" &&
      ramdon != ""
    ) {
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
          _ramdon_str: ramdon,
        },
        dataType: "json",
        success: function (data, response) {
        //   console.log("data de cabecera: " + data);
          if (data[0] != "E") {
           //  console.log("Respuesta 0 " + data);
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
              msg: "¡Algo salio mal con la cuenta por pagar! " + index,
            });
          }
        },
        error: function (jqXHR, exception, data, response) {
          var msg = "";
          if (jqXHR.status === 0) {
            msg = "Not connect.\n Verify Network.";
          } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
          } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
          } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
          } else if (exception === "timeout") {
            msg = "Time out error.";
          } else if (exception === "abort") {
            msg = "Ajax request aborted.";
          } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
          }
          console.log("data de cabecera: " + data);
          console.log("response de cabecera: " + response);
          console.log("excepcion " + exception);
          console.log(msg);
        },
      });
    }
  }

  //Funcion para cargar la tabla de cuentass por pagar
  async function cargarhisto() {
    await cargarCMBProveedor();
    let proveedor = $("#cmbProveedor option:selected").val();
    // console.log(proveedor);
    let espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "No hay cuentas pendientes de pago para el proveedor",
      sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };

    tablaD = $("#tblcuentas").DataTable({
      language: espanol,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
      responsive: true,
      lengthChange: false,
      columnDefs: [{ orderable: false, targets: 0, visible: false }],
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
        buttons: [
          {
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar Excel</span>',
            className: "btn-custom--white-dark btn-custom",
            titleAttr: "Excel",
            exportOptions: {
              columns: ":visible",
            },
    
          },
        ],
      },
      ajax: "functions/get_cuentas.php?id=" + proveedor,
      columns: [
        { data: "Proveedor" },
        { data: "Folio de Factura" },
        { data: "Serie de Factura", width: "12%"},
        { data: "Fecha de Vencimiento", width: "12%"},
        { data: "Importe" },
        { data: "Saldo insoluto" },
        { data: "Estatus", width: "18%"},
        { data: "Id" },
        { data: "Acciones" },
      ],
      columnDefs: [
        {
          targets: [7],
          visible: false,
          searchable: false,
        },
      ],
    });
  }

  //Metodo para recargar la tabla con los datos del nuevo proveedor
  function filtroProveedorTabla() {
    $(document).on("change", "#cmbProveedor", function (event) {
      //Descheck a todos los checkbox cuando se cambia de proveedor
      $(".check").prop("checked", false);
      $("#txtTotal").val("0");
      arID = [];

      let proveedor = $("#cmbProveedor option:selected").val();
     //  console.log(proveedor);
      tablaD.ajax.url("functions/get_cuentas.php?id=" + proveedor).load();
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

  function cargarCabeceras() {
    var f = new Date();
    //document.write(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
    // $('#txtfecha').val(f.getDate() + "-" + (f.getMonth() +1) + "-" + f.getFullYear() + " " + f.getHours() +":"+ f.getMinutes());
    // console.log(f.getDate() + "/" + (f.getMonth() + 1) + "/" + f.getFullYear());
  }
  /* Optenemos los valores de la cuenta por pagar y los ponemos en los campos de la pantalla editar */
  /* $('#editarcp').on('click',function(){ */
  function showheader() {
    var user_id = $("#user_id").val();
    $.ajax({
      type: "POST",
      url: "../cuentas_pagar/functions/get_ajax.php",
      dataType: "json",
      data: { user_id: user_id, funcion: "1" },
      success: function (data) {
        if (data.status == "ok") {
          $("#nombre").val(data.result.NombreComercial);
          $("#txtfolio").val(data.result.folio_factura);
          $("#txtserie").val(data.result.num_serie_factura);
          $("#subtotal").val(data.result.subtotal);
          $("#txtimporte").val(data.result.importe);
          $("#_txtiva").val(data.result.iva);
          $("#_txtieps").val(data.result.ieps);
          /* $('#txtimporte').val(Intl.NumberFormat("es-MX").format(data.result.importe)); */
          $("#txtfechaF").val(data.result.fecha_factura);
          $("#txtfechaV").val(data.result.fecha_vencimiento);
        } else {
          $(".user-content").slideUp();
          alert("User not found...");
        }
      },
    });
  }
});

function validarImputs2() {
  redFlag1 = 0;
  redFlag2 = 0;
  redFlag3 = 0;
  redFlag4 = 0;
  redFlag5 = 0;
  redFlag6 = 0;
  inputID = "cmbProveedor";
  invalidDivID = "invalid-nombreProv";
  textInvalidDiv = "Campo requerido";
  if (
    $("select[name=" + inputID + "] option")
      .filter(":selected")
      .val() == "f"
  ) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag1 = 1;
  }
  inputID = "cmbTipoPag";
  invalidDivID = "invalid-tipo";
  if (
    $("select[name=" + inputID + "] option")
      .filter(":selected")
      .val() == "f"
  ) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag2 = 1;
  }
  inputID = "cmbCuenta";
  invalidDivID = "invalid-cuenta";
  if (
    $("select[name=" + inputID + "] option")
      .filter(":selected")
      .val() == "f"
  ) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag3 = 1;
  }
  inputID = "txtfecha";
  invalidDivID = "invalid-fecha";
  if ($("#" + inputID).val() == "" || $("#" + inputID).val() == null) {
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
   //  console.log("Comentario Malo");
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text("La fecha no puede estar vacia");
    redFlag6 = 1;
  }
  if (redFlag1 == 1 && redFlag2 == 1 && redFlag3 == 1 && redFlag5 == 1 && redFlag6 == 1) {
    saveAll();
  } else {
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
//Guarda la cabecera con sus detalles, los detalles son movimientos que son las cuentas que se vana apagar
function saveAll() {
  // alert(arID.length);

  //Si la bandera saldo es false (insuficiente)
  if (flagSaldoSuficiente) {
    //Comprueba que aya alguna cuenta seleccionada para pagar
    if (!$.isEmptyObject(arID)) {
      //Cuenta por cobarar se manda vacia ya que yo no la necesito
      let _cuentaCobrar = "";

      /// Codigo para pasar el array de values de checks a un string
      /// Recorre el arreglo de valores del check y lo concatena en un string con formato: clave-valor,clave,valor
      arID.forEach(function (movimiento, index) {
        string = string += index + "-" + movimiento + ",";
      });
      //Le quita el ultimo coma a la cadena.
      let _cadena_CP = string.substring(0, string.length - 1);
      //Restaura la cadena a vacio para las proximas insercciones.
      string = "";
      // sacan los valores de la pantalla y asigna las variables para el ajax
      let _proveedor = $("select[name=cmbProveedor] option")
        .filter(":selected")
        .val();
      let tipoPago = $("select[name=cmbTipoPag] option")
        .filter(":selected")
        .val();
      let Comentarios = $("#textareaCoemtarios").val();
      let total = ($("#txtTotal").val());

      let _referencia = $("#txtreferencia").val();
      let tipo_movi = 1;
      let _origenCE = $("select[name=cmbCuenta] option")
        .filter(":selected")
        .val();
      let _cuentaDest = 300;
      var _fecha_pago = $("#txtfecha").val();
     //  console.log(total);
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
          Comentarios: Comentarios,
          total: total,
          tipo_movi: tipo_movi,
          _origenCE: _origenCE,
          _cuentaDest: _cuentaDest,
          _fecha_pago: _fecha_pago,
        },
        dataType: "json",
        success: function (data, response) {
         //  console.log("data de cabecera: " + data);
          // Si se recibio un error
          if (response[0] != "E") {
            // console.log("Respuesta 0 " + data);
            //Redirecciona con variable POST
            setTimeout(function () {
              window.location = "../pagos";
            }, 200);
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
        error: function (jqXHR, exception, data, response) {
          var msg = "";
          if (jqXHR.status === 0) {
            msg = "Not connect.\n Verify Network.";
          } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
          } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
          } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
          } else if (exception === "timeout") {
            msg = "Time out error.";
          } else if (exception === "abort") {
            msg = "Ajax request aborted.";
          } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
          }
          console.log("data de cabecera: " + data);
          console.log("response de cabecera: " + response);
          console.log("excepcion " + exception);
          console.log(msg);
        },
      });
      //Si no se selecciona ninguna cuenta por pagar
    } else {
      // console.log("Ninguna Cuenta Por Pagar Seleccionada");
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
  } else {
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
    $("#cmbCuenta").addClass("is-invalid");
    $("#invalid-cuenta").text("Saldo Insuficiente");
    $("#invalid-cuenta").show();
  }
}

function cambiarProveedor() {
  let proveedorid = $("#provee").attr("value");
  // console.log(proveedorid);
  document.querySelector(
    '#cmbProveedor [value="' + proveedorid + '"]'
  ).selected = true;
  //document.getElementById("cmbProveedor").value = proveedorid;
  //$("#cmbProveedor").puidropdown('selectValue', proveedorid);
}

function Posible() {
  //estructura de la cadena enviada a la base de datos para almacenar (id factura - importe pagado, id factura - importe pagado)
  let validIn = false;
  let _origenCE = $("select[name=cmbCuenta] option").filter(":selected").val();
  let _origenCE_text = $("select[name=cmbCuenta] option")
    .filter(":selected")
    .text();
  if (!$.isEmptyObject(arID)) {
    let string1 = "";
    //Crea la cadena de ids que se afectaron
    arID.forEach(function (movimiento, index) {
      string1 = string1 += index + ",";
    });
    //Quita el ultimo coma
    let _cadena_CP_id = string1.substring(0, string1.length - 1);

    // console.log(_cadena_CP_id);

    //Crea la cadena de id-valor,id-valor,
    let string2 = "";
    arID.forEach(function (movimiento, index) {
      string2 = string2 += index + "-" + movimiento + ",";
    });
    //Le quita el ultimo coma a la cadena.
    let _cadena_CP = string2.substring(0, string2.length - 1);
    let flag = true;
    //////// Posibles Retornos del AJAX/////////
    //iguales: 1, ID_Diferente: 0, suficiente: 1, nuevolimite: null -> Si todo esta correcto.
    //iguales: 0, ID_Diferente: 194, suficiente: 1, nuevolimite: '9.00' -> Si cambio el saldo insoluto en lo que se guardan los cambios
    //iguales: 0, ID_Diferente: 194, suficiente: 0, nuevolimite: '9.00' -> Si el saldo de la cuenta ya no es suficiente para pagar el total.
    $.ajax({
      type: "POST",
      url: "functions/addcontroller.php",
      dataType: "json",
      async: false,
      data: {
        clase: "get_data",
        funcion: "Add_Validate_importe",
        ids: _cadena_CP_id,
        origen: _origenCE,
        _cadena_CP: _cadena_CP,
      },
      success: function (data) {
       //  console.log("Posible?: ", data);
        $.each(data, function (i) {
          if (data[i].iguales == 0) {
            flag = false;
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg:
                "¡Parece que el saldo insoluto de una factura ha cambiado ahora es: $" +
                data[i].nuevolimite +
                " !",
            });
          }
          if (data[i].suficiente == 0) {
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
        if (flag) {
          validarImputs2();
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
