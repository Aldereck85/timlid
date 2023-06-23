$(function () {
    cargarTablaCancel();
    crearSelects();
    cargarCMBCliente();
    $(document).on("click", "#detalle_nota", function () {
        var data = $(this).data("id");
      
        $().redirect("detalle_nota.php", {
          'idNota': data,
          'toDo':1
        });
      });

    //activa los tooltips en datatable
  $('#tblNotasCreditCancel tbody').on('mouseover', 'tr', function () {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
        html: true
    });
    $('[data-toggle="tooltip"]').on("click", function () {
      $(this).tooltip("dispose");
    });
  });
});

function crearSelects(){
    new SlimSelect({
        select: '#cmbCliente', 
        deselectLabel: '<span class="">✖</span>'
      });     
}


function cargarCMBCliente() {
    //here our function should be implemented 
    var html = "";
    //Consulta los proveedores de la empresa
    $.ajax({
      type:'POST',
      url: "functions/controller.php",
      dataType: "json",
      data: { clase:"get_data",funcion:"get_Cliente"},
      success: function (data) {
        //console.log("data de proveedor: ", data);
        $.each(data, function (i) {
          //Crea el html para ser mostrado
          if (i == 0) {
              html += '<option selected value="f">Todos</option>';
              html +=
              '<option value="' +
              data[i].PKCliente +
              '">' +
              data[i].NombreComercial +
              "</option>";
          } else {
            html +=
              '<option value="' +
              data[i].PKCliente +
              '">' +
              data[i].NombreComercial +
              "</option>";
          }
        });
        //Pone los proveedores en el select
        $("#cmbCliente").append(html);
      },
      error: function (error) {
        console.log("Error");
        console.log(error);
      },
    
  });
  }

var tablaD;
function cargarTablaCancel() {
    $("#tblNotasCreditCancel").DataTable().destroy();
    let espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };

    tablaD = $("#tblNotasCreditCancel").DataTable({
      
      language: espanol,
      info: false,
      scrollX: true,
      bSort: false,
      pageLength: 15,
      responsive: true,
      lengthChange: false,
      dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
            <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
      buttons: {
        dom: {
          button: {
            tag: "button",
            className: "btn-table-custom",
          },
          buttonLiner: {
            tag: null,
          },
        },
        buttons: [
          {
            
          }
        ],
      },
      ajax: "functions/getNotasCancel.php",
      columns: [
        { data: "Folio" },
        { data: "UUID" , className: "text-center"},
        { data: "Cliente" },
        { data: "Importe" , className: "text-center"},
        { data: "F_Creacion" },
        { data: "F_Cancelacion" },
        { data: "Estado" },
      ],
      //Poner la columna de id oculta
      columnDefs: [
        {
          targets: [],
          visible: false,
          searchable: false,
        },
      ],
    });
    
  }
  function validarImputs2(){
    let _cliente = $('select[name=cmbCliente] option').filter(':selected').val();
    let _fecha_de = $('#txtDateFrom').val();
    let _fecha_to = $('#txtDateTo').val();
    console.log(_cliente);
    if(_fecha_de== ""){
      _fecha_de = "";
    }
    if(_fecha_to== "f"){
      _fecha_to = "f";
    }
    filtro();
    
  }

  function filtro(){
    let _cliente = $('select[name=cmbCliente] option').filter(':selected').val();
    let _fecha_de = $('#txtDateFrom').val();
    let _fecha_to = $('#txtDateTo').val();
    _cliente = (_cliente==undefined)?"f":_cliente;
    if(_fecha_de== ""){
      _fecha_de = "f";
    }
    if(_fecha_to== ""){
      _fecha_to = "f";
    }
    //lleno la tabla con ajax
    tablaD.ajax
      .url(
        "functions/Filtrada_get_Notas_CreditoCancel.php?cliente_id=" +
          _cliente +
          "&Ffrom=" +
          _fecha_de +
          "&Fto=" +
          _fecha_to
      )
      .load();
  }