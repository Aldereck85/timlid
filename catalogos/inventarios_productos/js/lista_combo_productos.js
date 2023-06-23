function cargarCMBProductos(pkProducto){
    if ($("#cmbTipoProducto").val()==1)
    {
        if( $("#contadorCompuesto").val() == 0){
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

            $("#contadorCompuesto").val('1');
        }
    }else{
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
function obtenerIdProductoSeleccionar(id, claveInterna, nombre, unidadMedida, costo, moneda) {
    
    var pkUsuario = $("#PKUsuario").val();
    $.ajax({
        url: '../../php/funciones.php',
        data:{clase:"get_data", funcion:"validar_producto_compuesto_temp", data:pkUsuario, data2:id},
        dataType:"json",
        success: function(data) {
          console.log('respuesta clave interna valida: ',data);
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]['existe']) == 1) {
            Swal.fire('Producto duplicado',"El producto se agrego con anterioridad","warning");
            console.log('¡Ya existe!');
          } else {
            console.log('Producto: ' + nombre);
            console.log('Unidad de medida: ' + unidadMedida);

            var seleccion = $("#txtSeleccion").val();
            var pkUsuario = $("#PKUsuario").val();
            

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
            $('#txtMoneda'+seleccion).val(moneda);


            var cantidad = $("#txtCantidadCompuesta"+seleccion).val(); 

            $.ajax({
                url:"../../php/funciones.php",
                data:{clase:"save_data", funcion:"save_datosProductoCompTemp", datos:id, datos2: cantidad, datos3: pkUsuario, datos4: seleccion, datos5: costo, datos6: moneda},
                dataType:"json",
                success:function(respuesta){
                console.log("respuesta agregar datos generales del producto:",respuesta);
        
                if(respuesta[0].status){
                    console.log('Prod. Agregado')
                }else{
                    console.log('Error al agregar');
                }
        
                },
                error:function(error){
                console.log(error);
                }
            });
    
            console.log('¡No existe!');
          }
    
        }
      });
    
}

function clickSeleccionarProd(seleccion){
    $("#txtSeleccion").val(seleccion);
}