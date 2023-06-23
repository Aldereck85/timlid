/*$(document).ready(function(){
  var idemp = $("#txtEmpresa").val();
    $("#tblListadoTipoOrdenInventario").dataTable({
      "lengthChange": false,
      "pageLength": 15,
      //"paging": true,
      "info": false,
      "pagingType": "full_numbers",
      "ajax": {
        url:"../../inventarios_productos/php/funciones.php",
          data:{clase:"get_data", funcion:"get_tipoOrdenInventarioTable", data:idemp},
      },
      "order": [ 0, 'desc' ],
      "columns":[
        { "data": "Id" },
        { "data": "TipoOrdenInventario" },
        { "data": "Estatus" },
      ],
      "language": setFormatDatatables(),
        columnDefs: [
          { orderable: false, targets: 0, visible: false },
        ],
        responsive: true,
        dom: "Bfrtip",
        buttons: [
          {
            extend: "excelHtml5",
            text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../img/excel-azul.svg" />',
            className: "excelDataTableButton",
            titleAttr: "Excel",
          },
        ],
        rowCallback: function(row, data) {
          console.log("Estatus:" + data.Estatus.substr(25,8));
          $($(row).find("td")[0]).css("width", "30%");
          if ((data.Estatus.substr(25,8) == 'Inactivo')) {
            $($(row).find("td")[0]).css("background-color", "#cac8c6");
            $($(row).find("td label.textTable")[0]).attr("style", "color: #FFFFFF!important");
            $($(row).find("td label.textTable")[0]).attr("title", "Tipo de orden inactivo");
          } else {
            $($(row).find("td")[0]).css("background-color", "#28c67a");
            $($(row).find("td label.textTable")[0]).attr("style", "color: #FFFFFF!important");
            $($(row).find("td label.textTable")[0]).attr("title", "Tipo de orden activo");
          }
        }
    });
  });
  
  function setFormatDatatables(){
    var idioma_espanol = {
      "searchPlaceholder": "Buscar...",
      "sSearch": "<img src='../../../img/timdesk/buscar.svg' width='20px' />",
      "sProcessing":     "Procesando...",
      "sLengthMenu":     "",//Mostrar _MENU_ registros
      "sZeroRecords":    "No se encontraron resultados",
      "sEmptyTable":     "Ningún dato disponible en esta tabla",
      "sInfo":           "",//Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros
      "sInfoEmpty":      "",//Mostrando registros del 0 al 0 de un total de 0 registros
      "sInfoFiltered":   "",//(filtrado de un total de _MAX_ registros)
      "sInfoPostFix":    "",
      "sUrl":            "",
      "sInfoThousands":  ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
          "sFirst":    "",//Primero
          "sLast":     "",//Último
          "sNext":     "<img src='../../../img/icons/pagination.svg' width='15px'>",
          "sPrevious": "<img src='../../../img/icons/pagination.svg' width='15px' style='-webkit-transform: scaleX(-1); transform=scaleX(-1);'>"
      },
      "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
      }
    }
    return idioma_espanol;
  }
  */
