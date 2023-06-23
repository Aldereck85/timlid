$(document).ready(function () {

  $('#nav-tab-inactivos').click(function(){
    $("#tblContactosInactivos").DataTable().ajax.reload();
  });

  initDataTableInactivos();

});

function initDataTableInactivos(){
  let idioma_espanol = {
    sProcessing: 'Procesando...',
    sZeroRecords: 'No se encontraron resultados',
    sEmptyTable: 'Ningún dato disponible en esta tabla',
    sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
    sLoadingRecords: 'Cargando...',
    searchPlaceholder: 'Buscar...',
    oPaginate: {
      sFirst: 'Primero',
      sLast: 'Último',
      sNext: '<img src="../../img/icons/pagination.svg" width="20px"/>',
      sPrevious: '<img src="../../img/icons/pagination.svg" width="20px" style="transform: scaleX(-1)"/>'
    },
    searchBuilder: {
      add: 'Filtros',
      condition: 'Condición',
      conditions: {
        string: {
          contains: 'Contiene',
          empty: 'Vacio',
          endsWith: 'Finaliza con',
          equals: 'Igual',
          not: 'Diferente',
          notEmpty: 'No vacío',
          startsWith: 'Comienza con',
        },
        date: {
          after: 'Después de',
          before: 'Antes de',
          between: 'Entre',
          empty: 'Vacio',
          equals: 'Igual',
          not: 'Diferente',
          notBetween: 'No está entre',
          notEmpty: 'No vacío'
        },
        number: {
          between: 'Between',
          empty: 'Vacio',
          equals: 'Igual',
          gt: 'Mayor que',
          gte: 'Mayor o igual que',
          lt: 'Menor que',
          lte: 'Menor o igual que',
          not: 'Diferente',
          notBetween: 'No está entre',
          notEmpty: 'No vacío',
        },
        array: {
          contains: 'Contiene',
          empty: 'Vacio',
          equals: 'Igual',
          not: 'Diferente',
          notEmpty: 'No vacío',
          without: 'Sin'
        }
      },
      clearAll: 'Limpiar',
      deleteTitle: 'Eliminar',
      data: 'Columna',
      leftTitle: 'Izquierda',
      logicAnd: '+',
      logicOr: 'o',
      rightTitle: 'Derecha',
      title: {
        0: 'Filtros',
        _: 'Filtros (%d)'
      },
      value: 'Opción',
      valueJoiner: 'et'
    }
  }


  let table = $("#tblContactosInactivos")
      .DataTable({
        language: idioma_espanol,
        dom: "QBlfrtip",
        buttons: [{
          extend: 'excelHtml5',
          text: '<i class="fas fa-file-excel"></i>',
          className: "btn btn-info datatables-btn float-left mx-2",
          titleAttr: 'Excel',
        },
          {
            extend: 'collection',
            text: 'Listas',
            className: "btn btn-success datatables-btn float-left mx-2",
            autoClose: true,
            buttons: [
              {
                text: 'Abril',
                action: function ( e, dt, node, config ) {
                  stored = {
                    criteria:[
                      {
                        condition: 'between',
                        data: 'Último contacto',
                        value: ['2021-04-01', '2021-04-30']
                      },
                      {
                        condition: '=',
                        data: 'Estado Lead',
                        value: ['Nuevo']
                      }
                    ],
                    logic: 'AND',
                  };

                  $('#tblContactosInactivos').DataTable().searchBuilder.rebuild(stored);
                  let div = '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista: <input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15" value="Abril"></span><button id="dtsb-save-2" class="dtsb-button" type="button">Editar Lista</button></div>'
                  $(table.searchBuilder.container()).append(div);
                  reset_icons();
                  reset_listeners();

                }
              },
              {
                text: 'Nuevo',
                action: function ( e, dt, node, config ) {
                  stored = {
                    criteria:[
                      {
                        condition: '=',
                        data: 'Estado Lead',
                        value: ['Nuevo']
                      }
                    ],
                    logic: 'AND',
                  };

                  $('#tblContactosInactivos').DataTable().searchBuilder.rebuild(stored);
                  let div = '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista: <input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15" value="Nuevo"></span><button id="dtsb-save-1" class="dtsb-button" type="button">Editar Lista</button></div>'
                  $(table.searchBuilder.container()).append(div);
                  reset_icons();
                  reset_listeners();
                }
              }
            ]
          }
        ],
        colReorder: true,
        searchBuilder: {
          columns: [2,3,4,5,6,7],
        },
        scrollX: true,
        lengthChange: false,
        info: false,
        ajax: {
          type: 'POST',
          url : "app/controladores/ContactoController.php",
          data : {accion:"verContactosInactivos"},
          dataSrc:"",

        },
        //data: data,
        paging: true,
        pageLength: 10,

        columns: [
          {
            'data': 'contacto_id',
            className: "hide_column"
          },
          {
            'data': 'contacto',
            "render": function ( data, type, row, meta ) {
              return row.nombre+' '+row.apellido;
            }
          },
          {
            'data': 'empresa'
          },
          {
            'data': 'email'
          },
          {
            'data': 'medio_contacto_campania'
          },
          {
            'data': 'propietario'
          },
          {
            'data': 'estatus',
            "render": function ( data, type, row, meta ) {

              return '<h6><span class="badge badge-danger">Inactivo</span></h6>'

            }
          }
        ],
      });

  reset_listeners();
}


function reset_listeners(){
  delete_searchbuilder();
  left_right_searchbuilder();
  add_searchbuilder();
}