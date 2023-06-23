$(document).ready(function() {

  $( ".dtsb-add.dtsb-button" ).css( "background", "darkblue" );

    //Si no tiene permiso de ver muestra el modal que será redireccionado
    if($('#ver').val()!=="1"){
      $('#alert').modal('show');
    }
    //Redireccionamos al Dash cuando se oculta el modal.
    $('#alert').on('hidden.bs.modal', function (e) {
      window.location= '../dashboard.php';
    })

    //Definicion de la tabla 
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
      searchBuilder: {
                      text:'Crear Filtros',
                      config: {
                        depthLimit: 1,
                        columns: [2,3,4,5],
                        greyscale: true
                      },
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
                            between: 'Entre',
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
                        clearGroup: 'X',
                        deleteText:'X',
                        clear:'X',
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
                        valueJoiner: 'et'
                    }
    }

    var proveedor = $("#proveedor_id").val();
    var periodo = $("#periodo").val();
    var toggle = $("#toggle").val();
    var fecha;
    if(toggle==1){
      fecha = "cp.fecha_factura";
      console.log(fecha);
    }else{
      fecha = "cp.fecha_vencimiento";
      console.log(fecha);

    }
    $("#tblVehiculos").dataTable({
        "pageLength": 5,
        "language": idioma_espanol,
        searchBuilder: {
          columns: [3,4,5,6],
        },
        "dom": "BQlfrtip",
        buttons: [
          {
            extend: "excelHtml5",
            text: '<img class="readEditPermissions" type="submit" width="50px" src="../../img/excel-azul.svg" />',
            className: "excelDataTableButton",
            titleAttr: "Excel",
          },
        ],
        "scrollX": true,
        "lengthChange": false,
        "info": false,
        "scrollY": "100%",
        "ajax": "functions/function_Puestos.php?periodo="+periodo+ "&proveedor_id=" +proveedor+ "&toggle=" + toggle,
        "columns": [{
            "data": "Proveedor"
          },
          {
            "data": "Folio_Factura"
          },
          {
            "data": "Serie_Factura"
          },
          {
            "data": "Subtotal"
          },
          {
            "data": "Importe"
          },
          {
            "data": "Fecha_Factura"
          },
          {
            "data": "Vence"
          },
          {
            "data": "vencimiento"
          },
          {
            "data": "Estatus"
          },
          {
            "data":"Editar"
          }
        ],//Poner la columna de id oculta
        "columnDefs": [
                {
                    "targets": [ 9 ],
                    "visible": false,
                    "searchable": false
                },
                
            ]
        
      }

    )
    
  });