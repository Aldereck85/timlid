var tablaD;

$(document).ready(function(){

  
  $('#btnFilterExits').click(function (e) { 
    e.preventDefault();
    validarImputs2();
  });

  crearSelects();
  /* Por defecto realiza la consulta de las Cuentas al Corriente (consulta = 1) */
  var consulta = 1;
  $(document).ready(function () {
    cargarhisto();
    /* Al cargar ocultamos la tabla historico */
    $("#tabla").hide();
    $("#tabla_histo").show();

    /* Los botones selectores de tabla se evaluan */
    $("input[type=radio][name=options]").on("change", function () {
      switch ($(this).val()) {
        /* Si se clica en historico muestra esta tabla y oculta la otra */
        case "historial":
          break;
        /* Si se clica en al corriente Carga los datos con la variable consulta en 1 osea los que esten Al Corriente */
        /* Y tambien oculta la tabla de historico y Muestra la otra */
        case "corriente":
          break;
        /* Si clica en venciadas pide manda un 0 en la var consulta para traer las facturas vencidas */
        /* Se asegura de que la tabla este visible y que la tabla_histo este bloqueada */
        case "vencidas":
          break;
      }
    });

    $("input[type=checkbox]").on("change", function () {
      if ($(this).is(":checked")) {
        consulta = 1;
        /* tablaP.ajax.url("functions/get_periodos.php?toDo=" + consulta).load(); */
        /* alert( 'Si' ); */
      } else {
        consulta = 0;
        /* tablaP.ajax.url("functions/get_periodos.php?toDo=" + consulta).load(); */

        /* tablaP.ajax.reload(); */
        console.log(consulta);

        /* alert( 'no' ); */
      }
    });
    //Definicion de la tabla de historico
    function cargarhisto() {
      $("#tblhistorico").DataTable().destroy();
      let espanol = {
        sProcessing: "Procesando...",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
        sLoadingRecords: "<img src='../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
        searchPlaceholder: "Buscar...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "<i class='fas fa-chevron-right'></i>",
          sPrevious: "<i class='fas fa-chevron-left'></i>",
        },
      };

      tablaD = $("#tblhistorico").DataTable({
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
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: [
            {
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
              className: "btn-custom--white-dark",
              action: function () {
                window.location.href = "agregar.php";
              },
            },
            {
              extend: "excelHtml5",
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
              className: "btn-custom--white-dark",
              titleAttr: "Excel",
            },
            {
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_CHECK_DARK.svg" width="20" class="mr-1"> Cuentas al corriente</span>',
              className: "btn-custom--white-dark",
              action: function (e, dt, node, config) {
                consulta = 1;
                tablaP.ajax
                  .url("functions/get_periodos.php?toDo=" + consulta)
                  .load();
                /* Cambia el titulo de la pagina */
                $("#tabla").css("display", "block");
                $("#tabla_histo").hide();
              },
            },
            {
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_CERRAR_DARK.svg" width="20" class="mr-1"> Cuentas vencidas</span>',
              className: "btn-custom--white-dark",
              action: function (e, dt, node, config) {
                consulta = 0;
                tablaP.ajax
                  .url("functions/get_periodos.php?toDo=" + consulta)
                  .load();
                /* Cambia el titulo de la pagina */
                $("#tabla").css("display", "block");
                $("#tabla_histo").css("display", "none");
              },
            },
            {
              text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO LISTADO DE MARCAS_DARK.svg" width="20" class="mr-1"> Historial</span>',
              className: "btn-custom--white-dark",
              action: function (e, dt, node, config) {
                $("#tabla").css("display", "none");
                $("#tabla_histo").css("display", "block");
                cargarhisto();
              },
            },
          ],
        },
        ajax: "functions/get_historico.php",
        columns: [
          { data: "Id" },
          { data: "Proveedor" },
          { data: "Folio de Factura" },
          { data: "Fecha de Factura" },
          { data: "Fecha de Vencimiento" },
          { data: "Vencimiento" },
          { data: "Importe" },
          { data: "total_pagado"},
          { data: "saldo_insoluto" },
          { data: "Estatus" },
        ],
        //Poner la columna de id oculta
        columnDefs: [
          {
            targets: [0],
            visible: false,
            searchable: false,
          },
        ],
      });
      /* $(".dtsb-add.dtsb-button")
        .off("click")
        .on("click", function () {
          console.log("Hola");
        }); */
    }

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
    //console.log(consulta);

    tablaP = $("#tblproveedor").DataTable({
      language: idioma_espanol,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 50,
      responsive: true,
      lengthChange: false,
      dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
      buttons: {
        dom: {
          button: {
            tag: "button",
            className: "btn-custom mr-2",
          },
          buttonLiner: {
            tag: null,
          },
        },
        buttons: [
          {
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
            className: "btn-custom--white-dark",
            action: function () {
              window.location.href = "agregar.php";
            },
          },
          {
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",
            titleAttr: "Excel",
          },
          {
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_CHECK_DARK.svg" width="20" class="mr-1"> Cuentas al corriente</span>',
            className: "btn-custom--white-dark",
            action: function (e, dt, node, config) {
              consulta = 1;
              tablaP.ajax
                .url("functions/get_periodos.php?toDo=" + consulta)
                .load();
              /* Cambia el titulo de la pagina */
              $("#tabla").css("display", "block");
              $("#tabla_histo").hide();
            },
          },
          {
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_CERRAR_DARK.svg" width="20" class="mr-1"> Cuentas vencidas</span>',
            className: "btn-custom--white-dark",
            action: function (e, dt, node, config) {
              consulta = 0;
              tablaP.ajax
                .url("functions/get_periodos.php?toDo=" + consulta)
                .load();
              /* Cambia el titulo de la pagina */
              $("#tabla").css("display", "block");
              $("#tabla_histo").css("display", "none");
            },
          },
          {
            text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO LISTADO DE MARCAS_DARK.svg" width="20" class="mr-1"> Historial</span>',
            className: "btn-custom--white-dark",
            action: function (e, dt, node, config) {
              $("#tabla").css("display", "none");
              $("#tabla_histo").css("display", "block");
              cargarhisto();
            },
          },
        ],
      },
      ajax: "functions/get_periodos.php?toDo=" + consulta,
      columns: [
        { data: "Proveedor" },
        { data: "De 0-30 Dias" },
        { data: "De 31-60 Dias" },
        { data: "De 61-60 Dias" },
        { data: "Mas de 90 Dias" },
        { data: "Id" },
      ],
      columnDefs: [
        {
          targets: [5],
          visible: false,
          searchable: false,
        },
        {
          targets: [1, 2, 3, 4],
          searchable: false,
        },
      ],
    });

    /* Acceder al valor de la fila clicada */
    var proveedor;
    var table = $("#tblproveedor").DataTable();
    $("#tblproveedor tbody").on("click", "tr", function () {
      var data = table.row(this).data();
      /* alert( 'Seleccionaste la fila de: '+data.Id+'\'s row' ); */
      proveedor = data.Id;
      $.ajax({
        type: "POST",
        url: "../cuentas_pagar/cuentas_Proveedor.php",
        dataType: "json",
        data: { proveedor_id: proveedor },
        success: function (data) {
          if (data.status == "ok") {
            $(location).attr("href", "../cuentas_pagar/cuentas_Proveedor.php");
            /*  $('#nombre').val(data.result.NombreComercial);
                        $('#txtfolio').val(data.result.folio_factura);
                        $('#txtserie').val(data.result.num_serie_factura);
                        $('#txtsubtotal').val(data.result.subtotal);
                        $('#txtimporte').val(data.result.importe);
                        $('#txtfechaF').val(data.result.fecha_factura);
                        $('#txtfechaV').val(data.result.fecha_vencimiento); */
          } else {
            $(".user-content").slideUp();
            alert("User not found...");
          }
        },
      });
      proveedor = data.Id;
      /* $('#hidden_user_id').val(data.Key); */
    });
  });

  //Comprobamos si tiene permisos para ver
  if ($("#ver").val() !== "1") {
    $("#alert").modal("show");
  }
  //Redireccionamos al Dash cuando se oculta el modal.
  $("#alert").on("hidden.bs.modal", function (e) {
    window.location = "../dashboard.php";
  });
});

function crearSelects() {
  new SlimSelect({
    select: "#cmbProveedor",
    deselectLabel: '<span class="">✖</span>',
  });

  cargarCMBProveedor();
}

function cargarCMBProveedor() {
  /* $("#cmbProveedor").prop("disabled", true); */
  /* $("#chkCategoria").prop("disabled", true); */
    //here our function should be implemented 
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "../pagos/functions/addcontroller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_proveedorCombo"},
      success: function (data) {
        console.log("data de proveedor: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
            html += "<option value=f>Todos</option>";
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
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    
});
}

function filtro(){
  let _proveedor = $('select[name=cmbProveedor] option').filter(':selected').val();
  let _fecha_de = $('#txtDateFrom').val();
  let _fecha_to = $('#txtDateTo').val();
  if(_fecha_de== ""){
    _fecha_de = "f";
  }
  if(_fecha_to== ""){
    _fecha_to = "f";
  }
  //lleno la tabla con ajax
  tablaD.ajax
    .url(
      "functions/historico_filtro.php?proveedor_id=" +
        _proveedor +
        "&Ffrom=" +
        _fecha_de +
        "&Fto=" +
        _fecha_to
    )
    .load();
}

var _Flagtime = true;
function validarImputs2(){
  let _proveedor = $('select[name=cmbProveedor] option').filter(':selected').val();
  let _fecha_de = $('#txtDateFrom').val();
  let _fecha_to = $('#txtDateTo').val();
  if(_fecha_de== ""){
    _fecha_de = "f";
  }
  if(_fecha_to== ""){
    _fecha_to = "f";
  }
  /* console.log(_proveedor);
  inputID= "cmbProveedor"; 
  invalidDivID = "invalid-cmbProveedor";
  if ((_proveedor=="f" && (_fecha_de=="f") && _fecha_to=="f")) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("Todos los campos no pueden estar vacios");

    inputID= "txtDateFrom";
    invalidDivID = "invalid-txtDateFrom";
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("Todos los campos no pueden estar vacios");

    if(_Flagtime==true){
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡Usa al menos un filtro!",
      });
      // _Flagtime = false;
     // NoSpamLobibox();
    }

  inputID= "txtDateTo";
  invalidDivID = "invalid-txtDateTo";
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text("Todos los campos no pueden estar vacios");
  } else {
    inputID= "cmbProveedor"; 
    invalidDivID = "invalid-cmbProveedor";
    textInvalidDiv = "Todos los campos no pueden estar vacios";
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);
    redFlag1 = 1;

    inputID= "txtDateFrom";
    invalidDivID = "invalid-txtDateFrom";
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text("Todos los campos no pueden estar vacios");

    inputID= "txtDateTo";
    invalidDivID = "invalid-txtDateTo";
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text("Todos los campos no pueden estar vacios");

  } */
  filtro();
  
}

function toDo_filtros(){

}

function NoSpamLobibox(){
  if(!_Flagtime){
    setTimeout(function(){ (_Flagtime = true); }, 3000);
  }
}

/* $(document).on("mouseover", "#tblhistorico tbody tr", function () {
  $(this).css("cursor", "pointer");
});

$(document).on("click", "#tblhistorico tbody tr", function () {
  var tableCuentas = $("#tblhistorico").DataTable();
  var rowData = tableCuentas.row(this).data();
  console.log(rowData);
  var input = rowData.Acciones;
  var idpos1 = input.indexOf('d-');
  var idpos2 = input.indexOf('\">');
  var id = input.slice(idpos1 + 2, idpos2);
  window.location.href= "editar.php?id="+id;
}); */
