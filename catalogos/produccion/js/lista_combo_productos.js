function cargarCMBProductos(pkProducto){
    if( _global.contadorCompuesto == 0){
        $("#tblListadoProductos").DataTable().destroy();
        $("#tblListadoProductos").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_cmb_productos", data:pkProducto},
        },
        "columns":[
            { "data": "Id" },
            { "data": "ClaveInterna" },
            { "data": "Nombre" },
            { "data": "Estatus" },

        ],
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 3, visible: false },
            ],
            responsive: true
        });
    }  
}

function cargarCMBEmpaques(pkProducto){
    if( _global.contadorCompuesto == 0){
        $("#tblListadoEmpaques").DataTable().destroy();
        $("#tblListadoEmpaques").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_cmb_empaques", data:pkProducto},
        },
        "columns":[
            { "data": "Id" },
            { "data": "ClaveInterna" },
            { "data": "Nombre" },
            { "data": "Estatus" },

        ],
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 3, visible: false },
            ],
            responsive: true
        });

        _global.contadorCompuesto = 1;
    }  
}
  
  //Funciones
  
//Función de data table
function setFormatDatatables(){
    var idioma_espanol = {
        "searchPlaceholder": "Buscar...",
        "sLengthMenu":     "",//Mostrar _MENU_ registros
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "",//Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros
        "sInfoEmpty":      "",//Mostrando registros del 0 al 0 de un total de 0 registros
        "sInfoFiltered":   "",//(filtrado de un total de _MAX_ registros)
        "sInfoPostFix":    "",
        "sSearch":         "",//Buscar:
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "",//Primero
            "sLast":     "",//Último
            "sNext":     "<img src='../../../../img/icons/pagination.svg' width='15px'>",
            "sPrevious": "<img src='../../../../img/icons/pagination.svg' width='15px' style='-webkit-transform: scaleX(-1); transform=scaleX(-1);'>"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
    return idioma_espanol;
}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
function obtenerIdProductoSeleccionar(id, claveInterna, nombre, unidadMedida, costo, moneda, fkMoneda) {
    $.ajax({
        url: '../../php/funciones.php',
        data:{clase:"get_data", funcion:"validar_producto_compuesto_temp", data:id},
        dataType:"json",
        success: function(data) {
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]['existe']) == 1) {
            Swal.fire('Material duplicado',"El material se agregó con anterioridad","warning");
          } else {

            var seleccion = $("#txtSeleccion").val();
            

            document.getElementById('txtProductos'+seleccion).value = id;
            document.getElementById('txtCantidadCompuesta'+seleccion).value = '1';
            if(claveInterna == ''){
                document.getElementById('cmbProductos'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbProductos'+seleccion).value = claveInterna + ' - ' + nombre;
            }
            if(unidadMedida == ''){
                $('#lblUnidadMedida'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedida'+seleccion).html(unidadMedida);
            }

            $('#lblCosto'+seleccion).html(costo +' '+ moneda);
            $('#txtCosto'+seleccion).val(costo);
            
            cargarCMBMoneda(parseInt(fkMoneda),'txtMoneda'+seleccion);

            var cantidad = $("#txtCantidadCompuesta"+seleccion).val(); 

            $.ajax({
                url:"../../php/funciones.php",
                data:{clase:"save_data", funcion:"save_datosProductoCompTemp", datos:id, datos2: cantidad, datos4: seleccion, datos5: costo, datos6: moneda, datos7: 0},
                dataType:"json",
                success:function(respuesta){
        
                if(respuesta[0].status){
                    console.log('OK')
                }else{
                    console.log('Error');
                }
        
                },
                error:function(error){
                console.log(error);
                }
            });
            console.log('No Duplicate');
          }   
        }
    });
}

function clickSeleccionarProd(seleccion){
    $("#txtSeleccion").val(seleccion);
}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
function obtenerIdEmpaquesSeleccionar(id, claveInterna, nombre, unidadMedida, costo, moneda, fkMoneda) {
    $.ajax({
        url: '../../php/funciones.php',
        data:{clase:"get_data", funcion:"validar_producto_compuesto_temp", data:id},
        dataType:"json",
        success: function(data) {
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]['existe']) == 1) {
            Swal.fire('Material duplicado',"El material se agregó con anterioridad","warning");
          } else {

            var seleccion = $("#txtSeleccion2").val();
            

            document.getElementById('txtProductosEmp'+seleccion).value = id;
            document.getElementById('txtCantidadCompuestaEmp'+seleccion).value = '1';
            if(claveInterna == ''){
                document.getElementById('cmbProductosEmp'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbProductosEmp'+seleccion).value = claveInterna + ' - ' + nombre;
            }
            if(unidadMedida == ''){
                $('#lblUnidadMedidaEmp'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedidaEmp'+seleccion).html(unidadMedida);
            }

            $('#lblCostoEmp'+seleccion).html(costo +' '+ moneda);
            $('#txtCostoEmp'+seleccion).val(costo);
            
            cargarCMBMoneda(parseInt(fkMoneda),'txtMonedaEmp'+seleccion);

            var cantidad = $("#txtCantidadCompuestaEmp"+seleccion).val(); 

            $.ajax({
                url:"../../php/funciones.php",
                data:{clase:"save_data", funcion:"save_datosProductoCompTemp", datos:id, datos2: cantidad, datos4: seleccion, datos5: costo, datos6: moneda, datos7: 1},
                dataType:"json",
                success:function(respuesta){
        
                if(respuesta[0].status){
                    console.log('OK')
                }else{
                    console.log('Error');
                }
        
                },
                error:function(error){
                console.log(error);
                }
            });
            console.log('No Duplicate');
          }   
        }
    });
}

function clickSeleccionarEmp(seleccion){
    $("#txtSeleccion2").val(seleccion);
}