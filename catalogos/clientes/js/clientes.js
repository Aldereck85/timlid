function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "<img src='../../../../img/timdesk/Preloader.gif' width='100px' style='transition-duration:300ms;' />",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  var tableClientes = $("#tblListadoClientes").DataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 50,
    responsive: true,
    lengthChange: false,
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
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
      buttons: [
        {
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
          className: "btn-custom--white-dark",
          action: function () {
            window.location.href = "agregar_cliente.php";
          },
        },
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark btn-custom",
          titleAttr: "Excel",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO SUBIR ARCHIVO-01.svg" width="20" class="mr-1"> Importar excel</span>',
          className: "btn-custom--white-dark",
          action: function () {
            $("#excelmodal").modal("show");
          },
        },
        {
          text: '<span class="d-flex align-items-center btn-filtros"><img src="../../../../img/icons/ICONO FILTRAR-01.svg" width="20" class="mr-1 btn-filtros"> Filtrar columnas</span>',
          className: "btn-custom--white-dark",
          action: function (e) {
            window.addEventListener("click", function (e) {
              if (
                e.target.classList.contains("btn-filtros") ||
                e.target.classList.contains("filtro-columna")
              ) {
                document
                  .getElementById("listaColumnas")
                  .classList.remove("d-none");
                return;
              }
              document.getElementById("listaColumnas").classList.add("d-none");
            });
          },
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_clientesTable" },
    },
    columns: [
      { data: "Acciones" },
      { data: "NombreComercial" },
      { data: "RazonSocial" },
      { data: "Rfc" },
      { data: "Telefono" },
      { data: "Email" },
      { data: "Monto" },
      { data: "Dias" },
      { data: "Estatus" },
      { data: "Vendedor" },
      { data: "MedioContacto" },
      { data: "FechaAlta" },
    ],
    columnDefs: [
        {targets: 0,visible: false,searchable: false,}
    ]
  });

  $(".filtro-columna").on("click", function (e) {
    var item = e.target;
    var column = tableClientes.column($(this).attr("data-column"));
    column.visible(!column.visible());
    if (item.tagName === "DIV") {
      item.classList.contains("checked-type-column")
        ? item.classList.remove(
            "checked-type-column",
            "checked-type-column-imgClientes"
          )
        : item.classList.add(
            "checked-type-column",
            "checked-type-column-imgClientes"
          );
    }
  });
});

function obtenerIdClienteEditar(id) {
  window.location.href = "editar_cliente.php?c=" + id;
}

function obtenerIdClienteEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_generales_cliente",
      datos: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de datos de cliente: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      $("#txtNombre").val(data[0].NombreComercial);
      $("#txtClienteD").val(id);
    },
  });
}

function eliminarCliente() {
  var PKCliente = $("#txtClienteD").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "delete_data", funcion: "delete_Cliente", data: PKCliente },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de eliminar clientes: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      if (data[0].status) {
        $("#tblListadoClientes").DataTable().ajax.reload();
        Swal.fire(
          "Eliminación exitosa",
          "Se eliminó el cliente con exito",
          "success"
        );
      } else {
        Swal.fire("Error", "No se eliminó el cliente con exito", "warning");
      }
    },
  });
}

/*$(document).ready(function(){

  $( window ).on( "load", function() {
    setTimeout(ocultar, 1000);
  });

  function ocultar(){
    $("#loader").fadeOut("slow");
  }

});*/
