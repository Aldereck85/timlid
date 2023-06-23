//Ejecutar al cargar la modal de agregar claves SAT (Nota: Se carga junto con la página que la posee)
$(document).on('click','#cmbClaveSAT',function(){

  var html = `<div style="position: fixed;
                  left: 0px;
                  top: 0px;
                  width: 100%;
                  height: 100%;
                  z-index: 9999;
                  background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                  opacity: .6;" id="loaderClaveSAT">
              </div>`;
  $('#cargarClaveSAT').html(html);

  if( $("#contadorClaveSAT").val() == 0){

    var buscador = $("#txtBuscarClave").val();
    $("#tblListadoClavesSAT").dataTable({
      "lengthChange": false,
      "pageLength": 100,
      "dom": 'lrtip',
      "info": false,
      "pagingType": "full_numbers",
      "ajax": {
        url:"../../php/funciones.php",
          data:{clase:"get_data", funcion:"get_clavesSATTable", data:buscador},
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
        responsive: true
    });

    $("#contadorClaveSAT").val('1');
  }

  $("#loaderClaveSAT").fadeOut("slow");

});

function buscando(){
  $("#tblListadoClavesSAT").DataTable().destroy();
  var buscador = $("#txtBuscarClave").val();
  $("#tblListadoClavesSAT").dataTable({
    "lengthChange": false,
    "pageLength": 100,
    "dom": 'lrtip',
    "info": false,
    "pagingType": "full_numbers",
    "ajax": {
      url:"../../php/funciones.php",
        data:{clase:"get_data", funcion:"get_clavesSATTable", data:buscador},
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
      responsive: true
  });

  $("#contadorClaveSAT").val('1');
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


//Función para asignar los valores seleccionados de la clave al combo y al input invisible 
function obtenerIdClaveSeleccionar(id, clave, descripcion) {

  console.log('Clave: ' + descripcion);
  document.getElementById('txtIDClaveSAT').value = id;
  document.getElementById('cmbClaveSAT').value = clave + ' - ' + descripcion;
  $("#btnAgregarImpuesto_ProdAdd").click();
}

function esElFinal() {
  /*let element = document.getElementById("agregar_ClaveSAT");

  if (element.offsetHeight + element.scrollTop >= element.scrollHeight) {
    alert("Llegamos al final del bloque");
  }*/
}