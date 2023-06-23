function setFormatDatatables() {
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
      sNext: "<img src='../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {


  let table = $("#tblOrdenPedido").dataTable({
    language: setFormatDatatables(),
    dom: '<"Menu">Bfrtip',
  buttons: [
    'excel'
  ],
    scrollX: true,
    lengthChange: false,
    info: false,
    ajax: {
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_OrdenPedido" },
      //data: { clase: "get_data", funcion: "get_productsTable" },
    },
    pageLength: 15,
    order: [0, "desc"],
    columns: [
      { data: "No Pedido" },
      { data: "Sucursal origen" },
      { data: "Sucursal destino" },
      { data: "Cliente" },
	  { data: "Fecha generacion" },
	  { data: "Fecha entrega" },
    { data: "Estatus" },
    ],
  });

  let filtros =   '<div class="row"><div class="col-md-4">' +
                  '<button class="btn btn-secondary buttons-excel1 buttons-html5 excelDataTableButton" tabindex="0" aria-controls="tblCotizacion" type="button" title="Excel" id="excelExport"><span><img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg"></span></button>' +
                  '</div>' +
                  '<div class="col-md-4">' +
                      '<div class="row">' +
                        '<div class="col-md-6">' +
                          '<div class="col-md-12">' +
                            '<label for="fechaIni">Fecha inicio</label>' +
                          '</div>' +
                          '<div class="col-md-12">' +
                            '<div class="col-md-12"><input type="date" id="fechaIni" name="fechaIni" class="form-control" value=""></div>' +
                          '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                          '<div class="col-md-12">' +
                            '<label for="fechaFin">Fecha final</label>' +
                          '</div>' +
                          '<div class="col-md-12">' +
                            '<div class="col-md-12"><input type="date" id="fechaFin" name="fechaFin" class="form-control" value=""></div>' +
                          '</div>' +
                        '</div>' +
                      '</div>' +
                  '</div>' +
                  '<div class="col-md-4">' +
                  '<span class="panel-header">Ordenar por estado:</span>' +
                    '<button type="button" id="filtros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../../img/chat/filtros_expandir.svg" class="img-responsive" width="15px" style="position: relative; left: 20px;"></button>'+
                      '<span id="fluidModalRightSuccess"><ul class="dropdown-menu dropdown-menu ellipsis dropdown-menu-filtros" role="menu" aria-labelledby="dropdownMenu" style="width: 460px;">' +
                        '<div class="row">' +
                          '<div class="col-md-6">' + 
                            '<span style="color:#fff;font-weight:800;">Fecha:</span>' +
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="Nuevo" style="width: 90%;" id="Nuevo">Nuevo</button>' +
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="Parcialmente surtido" style="width: 90%;" id="Parcialmente-surtido">Parcialmente surtido</button>'+
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="Surtido completo" style="width: 90%;" id="Surtido-completo">Surtido completo</button>' +
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="Facturado-almacen" style="width: 90%;" id="Facturado-almacen">Facturado-almacen</button>' +
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="Cancelado" style="width: 90%;" id="Cancelado">Cancelado</button>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                              '<span style="color:#fff;font-weight:800;">Tipo:</span>' +
                              '<button type="button" class="btn-mdb btn-cyan btn-filter-tipo btn-sm-mdb" data-target="Nuevo-FD" style="width: 90%;" id="Nuevo-FD">Nuevo-FD</button>' +
                              '<button type="button" class="btn-mdb btn-cyan btn-filter-tipo btn-sm-mdb" data-target="Parcialmente surtido-FD" style="width: 90%;" id="Parcialmente-surtido-FD">Parcialmente surtido-FD</button>' +
                              '<button type="button" class="btn-mdb btn-cyan btn-filter-tipo btn-sm-mdb" data-target="Surtido completo-FD" style="width: 90%;" id="Surtido-completo-FD">Surtido completo-FD</button>' +
                              '<button type="button" class="btn-mdb btn-cyan btn-filter-tipo btn-sm-mdb" data-target="Facturado-directo" style="width: 90%;" id="Facturado-directo">Facturado-directo/button>' +
                              '<button type="button" class="btn-mdb btn-cyan btn-filter-tipo btn-sm-mdb" data-target="Cerrado" style="width: 90%;" id="Cerrado">Cerrado</button>' +
                            '</div>' +
                          '</div>' +
                          '<div class="row text-center">' +
                               '<div class="col-md-12">' +
                               '<button type="button" class="btn-mdb btn-mdb-color btn-filter btn-sm-mdb" data-target="" style="width: 90%;" id="Todos">Todos</button>' +
                               '</div>' +
                          '</div>' +
                      '</ul>' +
                  '</span>' +
                  '</div></div>';

  $("div.Menu").html(filtros);

  let filtro = "";
  let opcionFiltro = 1;
  let min ,fechamin ,arregloFechamin ,aniomin ,mesmin ,diamin;
  let max ,fechamax ,arregloFechamax ,aniomax ,mesmax ,diamax;

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    
    if(opcionFiltro == 1){
        var estatus = data[6]; // informacion del estado de la cotizacion

        if (filtro == "") {
          return true;
        }

        if (estatus == filtro) {
          return true;
        } else {
          return false;
        }
    }
    else{
        var date = data[4];

        var arregloFechaData = date.split("/");
        var getAnio = arregloFechaData[2].split(" ");
        var anioData = getAnio[0];
        var mesData = arregloFechaData[1];
        var diaData = arregloFechaData[0];
        var fechaData = new Date(anioData, mesData, diaData);
 
        if (
            ( fechamin === null && fechamax === null ) ||
            ( fechamin === null && fechaData <= max ) ||
            ( fechamin <= fechaData   && fechamax === null ) ||
            ( fechamin <= fechaData   && fechaData <= fechamax )
        ) {
            return true;
        }
        return false;
    }



  });


    $(document).on('click', '#Nuevo,#Nuevo-FD,#Parcialmente-surtido-FD,#Parcialmente-surtido,#Surtido-completo,#Surtido-completo-FD,#Facturado-almacen,#Facturado-directo,#Cancelado,#Cerrado,#Todos', function(e, dt, node, config) {
        opcionFiltro = 1;
        filtro = $(this).data("target");
        $("#tblOrdenPedido").DataTable().draw();
    });

    $(document).on('change', '#fechaIni,#fechaFin', function(e, dt, node, config) {

          let elem = this.id;

          min = $("#fechaIni").val();
          max = $("#fechaFin").val();

          if(min === null || min == ""){
            fechamin = null;
          }
          else{
            arregloFechamin = min.split("-");
            aniomin = arregloFechamin[0];
            mesmin = arregloFechamin[1];
            diamin = arregloFechamin[2];
            fechamin = new Date(aniomin, mesmin, diamin);
          }

          if(max === null || max == ""){
            fechamax = null;
          }
          else{
            arregloFechamax = max.split("-");
            aniomax = arregloFechamax[0];
            mesmax = arregloFechamax[1];
            diamax = arregloFechamax[2];
            fechamax = new Date(aniomax, mesmax, diamax);
          }


          if((fechamin != null && fechamax != null) && fechamin > fechamax ){

            if(elem == "fechaIni"){
              $("#fechaIni").val(min);
            }
            if(elem == "fechaFin"){
              $("#fechaFin").val(max);
            }
            
            return;
          }


          opcionFiltro = 2;
          $("#tblOrdenPedido").DataTable().draw();
    });


    $(document).on('click', '#excelExport', function() {
      table.DataTable().button(0).trigger();
    });

  });
