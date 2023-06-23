
//Ejecutar al cargar la modal de agregar unidades SAT (Nota: Se carga junto con la página que la posee)
let modoGeneral = 0;
$(document).on('click','#cmbUnidadSATAAA',function(){

  modoGeneral = 5;
  var html = `<div style="position: fixed;
                  left: 0px;
                  top: 0px;
                  width: 100%;
                  height: 100%;
                  z-index: 9999;
                  background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                  opacity: .6;" id="loaderUnidad">
              </div>`;
  $('#cargarUnidadSAT').html(html);

  $("#contadorUnidadSAT").val(0);

  if( $("#contadorUnidadSAT").val() == 0){

    var buscador = $("#txtBuscarUnidad").val();

    $("#tblListadoUnidadesSAT").DataTable().destroy();
    $("#tblListadoUnidadesSAT").dataTable({
      "lengthChange": false,
      "pageLength": 100,
      "dom": 'lrtip',
      "info": false,
      "pagingType": "full_numbers",
      "ajax": {
        url:"../../php/funciones.php",
          data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador, modo: 5},
      },
      "columns":[
        { "data": "Id" },
        { "data": "Clave" },
        { "data": "Descripcion" },
  
      ],
      "language": setFormatDatatables(),
        columnDefs: [
          { orderable: false, targets: 0, visible: false },
        ],
        order:[],
        responsive: true
    });

    $("#contadorUnidadSAT").val('1');
  }

  $("#loaderUnidad").fadeOut("slow");
  
});

function buscandoUnidad(){
  $("#tblListadoUnidadesSAT").DataTable().destroy();
  var buscador = $("#txtBuscarUnidad").val();

  $("#tblListadoUnidadesSAT").dataTable({
    "lengthChange": false,
    "pageLength": 100,
    "dom": 'lrtip',
    "info": false,
    "pagingType": "full_numbers",
    "ajax": {
      url:"../../php/funciones.php",
        data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador, modo: modoGeneral},
    },
    "columns":[
      { "data": "Id" },
      { "data": "Clave" },
      { "data": "Descripcion" },

    ],
    "language": setFormatDatatables(),
      columnDefs: [
        { orderable: false, targets: 0, visible: false },
      ],
      order:[],
      responsive: true
  });

  $("#contadorUnidadSAT").val('1');
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
    "sLoadingRecords": "<img src='../../../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
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
function obtenerIdUnidadSeleccionar(id, clave, descripcion) {
  //console.log('Unidad: ' + descripcion);
  document.getElementById('txtIDUnidadSATAAA').value = id;
  document.getElementById('cmbUnidadSATAAA').value = clave + ' - ' + descripcion;
  $("#agregar_UnidadSAT").modal('hide');
}