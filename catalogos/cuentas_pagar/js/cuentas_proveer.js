var tablacp;
$(document).ready(function () {
  crearSelects();
  $("#btnFilterExits").click(function (e) {
    e.preventDefault();
    validarImputs2();
  });

  $(".dtsb-add.dtsb-button").css("background", "darkblue");

  //Si no tiene permiso de ver muestra el modal que será redireccionado
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = "../dashboard.php";
  });

  //Definicion de la tabla
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };

  var proveedor = $("#proveedor_id").val();
  var periodo = $("#periodo").val();
  var toggle = $("#toggle").val();
  var fecha;
  if (toggle == 1) {
    fecha = "cp.fecha_factura";
    console.log(fecha);
  } else {
    fecha = "cp.fecha_vencimiento";
    console.log(fecha);
  }
  tablacp = $("#tblVehiculos").DataTable({
    language: idioma_espanol,
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
    ajax:
      "functions/function_Puestos.php?periodo=" +
      periodo +
      "&proveedor_id=" +
      proveedor +
      "&toggle=" +
      toggle,
    columns: [
      {
        data: "Proveedor",
      },
      {
        data: "Folio_Factura",
      },
      {
        data: "Serie_Factura",
      },
      {
        data: "Subtotal",
      },
      {
        data: "Importe",
      },
      {
        data: "saldo_insoluto",
      },
      {
        data: "Fecha_Factura",
      },
      {
        data: "Vence",
      },
      {
        data: "vencimiento",
      },
      {
        data: "Estatus",
      },
      {
        data: "Editar",
      },
    ], //Poner la columna de id oculta
  });
});

function crearSelects(){
  cargarCMBProveedor();
}

function cargarCMBProveedor() {
  //here our function should be implemented
  var html = "";

  var proveedor = $("#proveedor_id").val();
  //Consulta los proveedores de la empresa
  $.ajax({
    type: "POST",
    url: "../pagos/functions/addcontroller.php",
    dataType: "json",
    data: { clase: "get_data", funcion: "get_proveedorCombo" },
    success: function (data) {
      console.log("data de proveedor: ", data);
      $.each(data, function (i) {
        //Crea el html para ser mostrado
        if (data[i].PKData == parseInt(proveedor)) {
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
    },
    error: function (error) {
      console.log("Error");
      console.log(error);
    },
  });
}

function filtro(){
  let _proveedor = $("#proveedor_id").val();
  let _fecha_de = $('#txtDateFrom').val();
  let _fecha_to = $('#txtDateTo').val();
  var periodo = $("#periodo").val();
  var toggle = $("#toggle").val();
  if(_fecha_de== ""){
    _fecha_de = "f";
  }
  if(_fecha_to== ""){
    _fecha_to = "f";
  }

  //lleno la tabla con ajax
  tablacp.ajax.url("functions/tabla_filtrada.php?periodo="+periodo+ "&proveedor_id=" +_proveedor+ "&toggle=" + toggle+ "&Ffrom=" + _fecha_de+ "&Fto=" + _fecha_to).load();

}

function validarImputs2() {
  redFlag1 = 0;
  redFlag2 = 0;
  
  inputID= "txtDateFrom";
  invalidDivID = "invalid-txtDateFrom";
  if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {

  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text("Seleccione al menos una fecha");
    redFlag2 = 1;
  }
  inputID = "txtDateTo";
  invalidDivID = "invalid-txtDateTo";
  if (($('#'+inputID).val()=="") ||($('#'+inputID).val()==null)) {

  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text("Seleccione al menos una fecha");
    redFlag1 = 1;
  }
  if(redFlag1 == 0 && redFlag2 == 0){
    inputID= "txtDateTo";
    invalidDivID = "invalid-txtDateTo";
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("Seleccione al menos una fecha");

    inputID= "txtDateFrom";
    invalidDivID = "invalid-txtDateFrom";
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("Seleccione al menos una fecha");
  }else{
    inputID= "txtDateFrom";
    invalidDivID = "invalid-txtDateFrom";
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text("Seleccione al menos una fecha");

    inputID= "txtDateTo";
    invalidDivID = "invalid-txtDateTo";
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text("Seleccione al menos una fecha");
    filtro();
  }
}
