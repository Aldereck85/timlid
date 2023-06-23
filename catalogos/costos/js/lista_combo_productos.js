function cargarCMBProductos(pkProducto){

    var topButtons = [];

    topButtons.push({
        text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> </span>',
        className: "btn-custom--white-dark",
        action: function () {

          selectProductosAgr.destroy();
          selectCatProductoAgr.destroy();
          selectMarcaProductoAgr.destroy();
          cargarCMBTipo("", "cmbTipoProducto", 1);
          cargarCMBCategoria("","cmbCategoriaProductoAAA");
          cargarCMBMarca("", "cmbMarcaProductoAAA");

          $("#TipoProductoAlta").val(1);

          selectProductosAgr =  new SlimSelect({
            select: "#cmbTipoProducto",
            deselectLabel: '<span class="">✖</span>',
          });

          selectCatProductoAgr = new SlimSelect({
            select: "#cmbCategoriaProductoAAA",
            deselectLabel: '<span class="">✖</span>',
          });

          selectMarcaProductoAgr = new SlimSelect({
            select: "#cmbMarcaProductoAAA",
            deselectLabel: '<span class="">✖</span>',
          });

          actualizarComboProductos = 0;
          $("#agregar_Productos_Modal").modal('show');  
          

         /* cargarProductos();

          $("#areaDatos").html("");
          $("#areaCompuesto").html("");
          $("#costosModal").modal('show');*/
        },
      });

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
                data:{clase:"get_data", funcion:"get_cmb_productos", data:pkProducto, modo: 1},
        },
        "columns":[
            { "data": "Id" },
            { "data": "ClaveInterna" },
            { "data": "Nombre" },
            { "data": "Estatus" },

        ],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-0 col-md-0 p-0"><"col-sm-12 col-md-12 p-0"p>>>`,
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
          buttons: topButtons,
        },
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
let validadorProductos = 0;
function obtenerIdProductoSeleccionar(id, claveInterna, nombre, unidadMedida, costo, moneda, fkMoneda) {
            
        let productos = document.querySelectorAll('.contabilizarProductos')

        productos.forEach((item) => {
          //console.log(item.value);
            if(item.value == id){
                validadorProductos = 1;
            }
        });

        if(validadorProductos > 0){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "No puedes agregar el mismo producto.",
            });
            validadorProductos = 0;
            return;
        }

        var seleccion = $("#txtSeleccion").val();

        document.getElementById('txtProductos'+seleccion).value = id;
        document.getElementById('txtCantidadCompuesta_'+seleccion).value = '1';
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
        $('#txtCosto_'+seleccion).val(costo);
        

        var cantidad = $("#txtCantidadCompuesta_"+seleccion).val(); 

        var total = (cantidad * costo).toFixed(2);
        $("#txtTotalCosto"+seleccion).val(total);

        getTotales();
}

function clickSeleccionarProd(seleccion){
    $("#txtSeleccion").val(seleccion);
}


let actualizarComboProductos = 0;
function agregarNuevoProducto(){

      contadorRepeticiones = 1;
      selectProductos.set(0);
      contadorRepeticiones = 0;

      selectProductosAgr.destroy();
      selectCatProductoAgr.destroy();
      selectMarcaProductoAgr.destroy();
      cargarCMBTipo("", "cmbTipoProducto", 1);
      cargarCMBCategoria("","cmbCategoriaProductoAAA");
      cargarCMBMarca("", "cmbMarcaProductoAAA");

      $("#TipoProductoAlta").val(1);

      selectProductosAgr =  new SlimSelect({
        select: "#cmbTipoProducto",
        deselectLabel: '<span class="">✖</span>',
      });

      selectCatProductoAgr = new SlimSelect({
        select: "#cmbCategoriaProductoAAA",
        deselectLabel: '<span class="">✖</span>',
      });

      selectMarcaProductoAgr = new SlimSelect({
        select: "#cmbMarcaProductoAAA",
        deselectLabel: '<span class="">✖</span>',
      });

      actualizarComboProductos = 1;
      $("#TipoProductoAlta").val(0);
      $("#agregar_Productos_Modal").modal('show');  
}