$(document).ready(function(){
    
  /* Por defecto realiza la consulta de las Cuentas al Corriente (consulta = 1) */
  var consulta = 1;
    $(document).ready(function() {
      /* Al cargar ocultamos la tabla historico */
        $("#tabla_histo").hide();
       

        /* Los botones selectores de tabla se evaluan */
        $('input[type=radio][name=options]').on('change', function() {
            switch ($(this).val()) {
              /* Si se clica en historico muestra esta tabla y oculta la otra */
              case 'historial':
                

                break;
                /* Si se clica en al corriente Carga los datos con la variable consulta en 1 osea los que esten Al Corriente */
                /* Y tambien oculta la tabla de historico y Muestra la otra */
              case 'corriente':
                
                break;
                /* Si clica en venciadas pide manda un 0 en la var consulta para traer las facturas vencidas */
                /* Se asegura de que la tabla este visible y que la tabla_histo este bloqueada */
              case 'vencidas':
                break;      
            }
          });

        $('input[type=checkbox]').on('change', function() {
            if ($(this).is(':checked') ) {
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
        function cargarhisto(){
          let espanol = {
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
              depthLimit: 1,
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
              logicAnd: 'Y',
              logicOr: 'O',
              rightTitle: 'Derecha',
              title: {
                  0: '',
                  _: ''
              },
              value: 'Opción',
              valueJoiner: 'et',
            }
          }            
        
        tablaD= $("#tblhistorico").DataTable({
            "retrieve": true,
            "destroy": true,
              "paging":true,
              "pageLength": 7,
              "language": espanol,
              buttons: [{
                  extend: "excelHtml5",
                  text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
                  className: "excelDataTableButton",
                  titleAttr: "Excel",
                },{
                  text: '<i class="fas fa-angle-double-up"></i> Al Corriente',
                  className: "btn btn-success datatables-btn float-left corriente",
                  action: function (e, dt, node, config) {
                    consulta = 1;
                    tablaP.ajax.url("functions/get_periodos.php?toDo=" + consulta).load();
                    /* Cambia el titulo de la pagina */
                    $("#tabla").css("display", "block");
                    $("#tabla_histo").hide();
                  },
                },
                {
                  text: '<i class="fas fa-ban"></i> Vencidas',
                  className: "btn btn-danger datatables-btn float-left vencidas",
                  action: function (e, dt, node, config) {
                    consulta = 0;
                    tablaP.ajax.url("functions/get_periodos.php?toDo=" + consulta).load();
                    /* Cambia el titulo de la pagina */
                    $("#tabla").css("display", "block");
                    $("#tabla_histo").css("display", "none");
                  },
                },
                {
                  text: '<i class="fas fa-file-invoice"></i> Historial',
                  className: "btn btn-light datatables-btn float-left historial",
                  action: function (e, dt, node, config) {
                    $("#tabla").css("display", "none");
                    $("#tabla_histo").css("display", "block");
                    cargarhisto();
                  },
                },
                
              ],
              "dom": "BQlfrtip",
              "scrollX": true,
              "lengthChange": false,
              "info": false,
              "searchBuilder": {
                "columns": [2,3],
            },
              
              "scrollY": "100%",
              "ajax":"functions/get_historico.php",
              "columns": [{
                  "data": "idpagos"
                },
                {
                  "data": "Fecha"
                },
                {
                  "data": "Proveedor"
                },
                {
                  "data": "Total"
                },
                {
                  "data": "Folio factura"
                },
              ],
              //Poner la columna de id oculta
              "columnDefs": [
                      {
                          "targets": [0],
                          "visible": true,
                          "searchable": false
                      },
                      
                  ]
            }
            )
            $('.dtsb-add.dtsb-button').off("click").on("click",function(){
              console.log("Hola");
            });
        }
          
            var idioma_espanol = {
                "sProcessing": "Procesando...",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sSearch": "<img src='../../img/timdesk/buscar.svg' width='20px' />",
                "sLoadingRecords": "Cargando...",
                searchPlaceholder: "Buscar...",
                "oPaginate": {
                  "sFirst": "Primero",
                  "sLast": "Último",
                  "sNext": "<img src='../../img/icons/pagination.svg' width='20px'/>",
                  "sPrevious": "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>"
                },
              }
              console.log(consulta);

              tablaP= $("#tblproveedor").DataTable({
                "retrieve": true,
                "destroy": true,
                  "paging":true,
                  "pageLength": 7,
                  "language": idioma_espanol,
                  "dom": "Bfrtip",
                  buttons: [
                    {
                      extend: "excelHtml5",
                      text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
                      className: "excelDataTableButton",
                      titleAttr: "Excel",
                      
                    },{
                      text: '<i class="fas fa-angle-double-up"></i> Al Corriente',
                      className: "btn btn-success datatables-btn float-left corriente",
                      action: function (e, dt, node, config) {
                        consulta = 1;
                        tablaP.ajax.url("functions/get_periodos.php?toDo=" + consulta).load();
                        /* Cambia el titulo de la pagina */
                        $("#tabla").css("display", "block");
                        $("#tabla_histo").hide();
                      },
                    },
                    {
                      text: '<i class="fas fa-ban"></i> Vencidas',
                      className: "btn btn-danger datatables-btn float-left vencidas",
                      action: function (e, dt, node, config) {
                        consulta = 0;
                        tablaP.ajax.url("functions/get_periodos.php?toDo=" + consulta).load();
                        /* Cambia el titulo de la pagina */
                        $("#tabla").css("display", "block");
                        $("#tabla_histo").css("display", "none");
                      },
                    },
                    {
                      text: '<i class="fas fa-file-invoice"></i> Historial',
                      className: "btn btn-light datatables-btn float-left historial",
                      action: function (e, dt, node, config) {
                        $("#tabla").css("display", "none");
                        $("#tabla_histo").css("display", "block");
                        cargarhisto();
                        
                      },
                    },
                    
                  ],
                  "scrollX": true,
                  "lengthChange": false,
                  "info": false,
                  
                  "scrollY": "100%",
                  "ajax":"functions/get_periodos.php?toDo=" + consulta,
                  "columns": [{
                      "data": "Proveedor"
                    },
                    {
                      "data": "De 0-30 Dias"
                    },
                    {
                      "data": "De 31-60 Dias"
                    },
                    {
                      "data": "De 61-60 Dias"
                    },
                    {
                      "data": "Mas de 90 Dias"
                    },
                    {
                      "data": "Id"
                    },
                    
                  ],
                  "columnDefs": [
                          {
                              "targets": [ 5 ],
                              "visible": false,
                              "searchable": false
                          },
                          {
                            "targets": [ 1,2,3,4 ],
                            "searchable": false
                        }
                      ]
                })

                

                
    

/* Acceder al valor de la fila clicada */
    var proveedor;
     var table = $("#tblproveedor").DataTable()
        $('#tblproveedor tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();
            /* alert( 'Seleccionaste la fila de: '+data.Id+'\'s row' ); */
            proveedor = data.Id;
            $.ajax({
                type:'POST',
                url:'../cuentas_pagar/cuentas_Proveedor.php',
                dataType: "json",
                data:{proveedor_id:proveedor},
                success:function(data){
                    if(data.status == 'ok'){
                        $(location).attr('href','../cuentas_pagar/cuentas_Proveedor.php');
                       /*  $('#nombre').val(data.result.NombreComercial);
                        $('#txtfolio').val(data.result.folio_factura);
                        $('#txtserie').val(data.result.num_serie_factura);
                        $('#txtsubtotal').val(data.result.subtotal);
                        $('#txtimporte').val(data.result.importe);
                        $('#txtfechaF').val(data.result.fecha_factura);
                        $('#txtfechaV').val(data.result.fecha_vencimiento); */
                    }else{
                        $('.user-content').slideUp();
                        alert("User not found...");
                    } 
                }
            });
             proveedor = data.Id;
            /* $('#hidden_user_id').val(data.Key); */
            

        } );
    });

    //Comprobamos si tiene permisos para ver
    if($('#ver').val() !=="1"){
      $('#alert').modal('show');
    }
    //Redireccionamos al Dash cuando se oculta el modal.
    $('#alert').on('hidden.bs.modal', function (e) {
      window.location= '../dashboard.php';
    })
});
