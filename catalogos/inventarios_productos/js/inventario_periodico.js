$(document).ready(function () {
    cargarCMBSucursal();
    verificarCambioCMBSucursales();
    cargarConteosInventariosPorSucursales();
    
  });

  function cargarConteosInventariosPorSucursales() {
    $("#tblConteosInventariosPorSucursales")
      .DataTable({
        language: setFormatDatatables(),
        dom: "frtip",
        scrollX: true,
        lengthChange: true,
        info: false,
        ajax: {
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_conteosInventariosPorSucursales",
          },
        },
        pageLength: 20,
        paging: true,
        order: [],
        columns: [
          { data: "IdInventario" },
          { data: "Conteo" },
          { data: "Estatus" },
          { data: "Usuario" },
          { data: "NumeroProductos" },
        ],
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
      });
  }
  
  function cargarCMBSucursal() {
    var html = '<option value="0" selected disabled>Seleccionar sucursal</option>';
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_cmb_sucursalesInvPeriodico" },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta de sucursales: ", respuesta);
  
        $.each(respuesta, function (i) {
          html +=
            '<option value="' +
            respuesta[i].id +
            '">' +
            respuesta[i].sucursal +
            "</option>";
        });
  
        $("#cmbSucursales").append(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  
    new SlimSelect({
      select: "#cmbSucursales",
      deselectLabel: '<span class="">✖</span>',
    });
  }
  
  function verificarCambioCMBSucursales() {
    $("#cmbSucursales").on("change", function () {
      $("#tblInventarioPeriodico").DataTable().destroy();
      if($("#cmbSucursales option:selected").text().includes("(En inventario)")){
        $("#lblSucursal").text($("#cmbSucursales option:selected").text().replace("(En inventario)", ""));
        $("#iniciarSeguimientoInv").text("Seguimiento inventario");
      }else{
        $("#lblSucursal").text($("#cmbSucursales option:selected").text());
        $("#iniciarSeguimientoInv").text("Iniciar inventario");
      }
      $.ajax({
        url: "../../php/funciones.php",
        data: { clase: "get_data", funcion: "get_dataSucursal", data: $("#cmbSucursales").val() },
        dataType: "json",
        success: function (respuesta) {
          console.log("respuesta de datos de inventario de la sucursal: ", respuesta);
          if(respuesta.length != 0){
            switch(respuesta[0].estatus){
              case 0:
                var estatusInv = "Pendiente";
                $("#cancelInv").prop("disabled", false);
              break;
              case 1:
                var estatusInv = "Finalizado";
                $("#cancelInv").prop("disabled", true);
              break;
              case 2:
                var estatusInv = "Cancelado";
                $("#cancelInv").prop("disabled", true);
              break;
            }
            $("#lblFechaIni").text(respuesta[0].created_at.split(" ")[0]);
            $("#lblEstatus").text(estatusInv);
          }else{
            $("#lblFechaIni").text("-");
            $("#lblEstatus").text("-");
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
      $("#modalNuevoInv").modal("show");
    });
  }
  
  function iniSeguiInv(){
    var sucursal = $("#cmbSucursales").val();
    if($("#iniciarSeguimientoInv").text() == "Iniciar inventario"){
      $.ajax({
        url: "../../php/funciones.php",
        data: { clase: "save_data", funcion: "save_IniciarPeriodicInv", data: sucursal },
        dataType: "json",
        success: function (respuesta) {
          console.log("respuesta de iniciar el inventario periodico: ", respuesta);
          window.open(
            "descargarLayout.php?sucursal=" + sucursal
          );
          setTimeout(window.location.href = "conteo_inventario.php?sucursal="+sucursal,1000);
        },
        error: function (error) {
          console.log(error);
        },
      });
    }else{
      console.log('seguir');
      window.location.href = "conteo_inventario.php?sucursal="+sucursal;
    }
  }
  
  function descargarLayout(){
    var sucursal = $("#cmbSucursales").val();
    window.location.href = "descargarLayout.php?sucursal="+sucursal;
  }

  function cancelInv(){
    var sucursal = $("#cmbSucursales").val();
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "edit_data", funcion: "edit_cancelInv", data: sucursal },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta de cancelar el inventario: ", respuesta);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }

  function setFormatDatatables() {
    var idioma_espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      lengthMenu: `Mostrar: 
                    <select class="mb-n2 mt-1">
                      <option value="20">20</option>
                      <option value="100">100</option>
                      <option value="200">200</option>
                    <select> productos`,
      oPaginate: {
        sFirst: "",
        sLast: "",
        sNext: "<img src='../../../../img/icons/pagination.svg' width='20px'/>",
        sPrevious:
          "<img src='../../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
      },
    };
    return idioma_espanol;
  }