function verComplemento(id){
  var form = document.createElement('form');
   document.body.appendChild(form);
   form.method = 'post';
   form.action = 'cargaComplemento.php';
   form.target = "_blank";
     var input = document.createElement('input');
     input.type = 'hidden';
     input.name = "id";
     input.value = id;
     form.appendChild(input);
   form.submit(); 
}
function Descarga_pdf(id){
  $.ajax({
    url: "functions/function_descarga_pdf.php",
    data: { 
      id:id,
    },
    xhrFields: {
      responseType: 'blob'
    },
    success: function (data) {
      if(data!="err"){
        var a = document.createElement('a');
        var url = window.URL.createObjectURL(data);
        a.href = url;
        a.download = 'CP-'+id+'.pdf';
        a.click();
        window.URL.revokeObjectURL(url);
      }else{
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Error, no se pudo descargar!",
        });
      }
        
    },
    error: function(jqXHR, exception,data,response) {
      var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
    },
  });
}

function Descarga_xml(id){
  $.ajax({
    url: "functions/function_descarga_xml.php",
    data: { 
      id:id,
    },
    dataType : "XML",
    
    success: function (data) {
      if(data!="err"){
        var a = document.createElement('a');
        var xmlDoc = new XMLSerializer().serializeToString(data);
        var blob = new File([xmlDoc], "CP"+id+".xml",  {type: "text/xml"});
        var url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = 'CP-'+id+'.xml';
        a.click();
        window.URL.revokeObjectURL(url);
      }else{
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Error, no se pudo descargar!",
        });
      }
        
    },
    error: function(jqXHR, exception,data,response) {
      var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
    },
  });
}

//funcion para crear una variable de session como bandera para filtrar dentro de recepcion de pagos
function añadir(){
  $.ajax({
    url: "functions/function_generaBanderaComplementos.php",
    dataType: "json",
    success: function (data) {
      if(data['estatus']=='ok'){
        location.href ="../recepcion_pagos/";
      }else{
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salió mal!",
        });
      }
    },
    error: function(jqXHR, exception,data,response) {
      var msg = '';
        if (jqXHR.status === 0) {
            msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
            msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
            msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
            msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
            msg = 'Time out error.';
        } else if (exception === 'abort') {
            msg = 'Ajax request aborted.';
        } else {
            msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
    },
  });
}

$(document).ready(function(){
  carga_cmbClientes();
  $('#btnFilterExits').on('click', function (e) {
    seleccion = document.getElementById("chosenClientes").value;
    fecha_desde = document.getElementById("txtDateFrom").value;
    fecha_hasta = document.getElementById("txtDateTo").value;

    filtra_Complementos(seleccion,fecha_desde,fecha_hasta);
  });

  //cuando cambia la selección del cliente se filtra
  $("#chosenClientes").on("change",function(){
    $('#btnFilterExits').click();
  });

  var idioma_espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    }  

  var topButtons = [
    {
      text: '<i class="fas fa-plus-square"></i> Añadir',
      className: "btn-table-custom--blue",
      action: function () {
        añadir();
      },
    },
  ];

  if($("#exportar").val()=="1"){
    topButtons.push({
      extend: "excelHtml5",
      text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
      className: "btn-table-custom--turquoise",
    });              
  }

  tablaM= $('#tblComplemt').DataTable({
    language: idioma_espanol,
    restrieve: true,
    destroy: true,
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 10,
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
        buttons: topButtons,
      },
    scrollY: "100%",
    ajax: "functions/get_Complementos.php",
    columns: [
      {data: "Cliente"},
      {data: "Folio pago"},
      {data: "Folio facturas"},
      {data: "Folio complemento"},
      {data: "F de timbrado"},
      {data: "Responsable"},
      {data: "Total"},
      {data: "Acciones", width:"80px"},
    ],
    columnDefs: [
      {
          targets: [ 4,6,7 ],
          searchable: false
      }
    ],
  });  

  //activa los tooltips en datatable
  $('#tblComplemt tbody').on('mouseover', 'tr', function () {
    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
        html: true
    });
    $('[data-toggle="tooltip"]').on("click", function () {
      $(this).tooltip("dispose");
    });
  });

  $(function(){
    $("[data-toggle='tooltip']").tooltip();
  });

  //Comprobamos si tiene permisos para ver
  if(($("#ver").val()) != "1"){
    $("#alert").modal('show');
  }

  //Redireccionamos al Dash cuando se oculta el modal.
  $('#alert').on('hidden.bs.modal', function (e) {
    window.location= href="../dashboard.php";
  });
    
});
