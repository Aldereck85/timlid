function setFormatDatatables() {
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
  };
  return idioma_espanol;
}

function eliminarEmpleado(id) {
  console.log(id);
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      cancelButton: "btn-custom btn-custom--border-blue",
      confirmButton: "btn-custom btn-custom--blue",
    },
    buttonsStyling: false,
  });
  swalWithBootstrapButtons
    .fire({
      title: "¿Desea dar de baja este empleado?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter2">Baja empleado</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: true,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "functions/dar_BajaEmpleado.php",
          type: "POST",
          dataType: "json",
          data: {
            idEmpleadoB: id,
          },
          success: function (data, status, xhr) {
            if (data.status === "success") {
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                img: "../../../img/chat/checkmark.svg",
                msg: "¡Registro eliminado!",
              });
              $("#tblEmpleados").DataTable().ajax.reload();
              return;
            }
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/warning_circle.svg",
              msg: "Ocurrió un error al eliminar",
            });
          },
        });
      }
    });
}

$(document).ready(function () {
  var filtro = "";
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var estatus = data[5]; // informacion del estado de la cotizacion

    if (filtro == "") {
      return true;
    }

    if (estatus == filtro) {
      return true;
    } else {
      return false;
    }
  });

  var tableEmpleados = $("#tblEmpleados").DataTable({
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
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
          className: "btn-custom--white-dark",
          action: function () {
            window.location.href = "functions/agregar_Empleado.php";
          },
        },
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
          exportOptions: {
            columns: ":visible",
          },
        },
        {
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO SUBIR ARCHIVO-01.svg" width="20" class="mr-1"> Importar excel</span>',
          className: "btn-custom--white-dark",
          action: function () {
            $("#excelmodal").modal("show");
          },
        },
        {
          text: '<span class="d-flex align-items-center btn-filtros"><img src="../../img/icons/ICONO FILTRAR-01.svg" width="20" class="mr-1 btn-filtros"> Filtrar columnas</span>',
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
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_empleados" },
    },
    columns: [
      {
        data: "Acciones",
        render: function (data, type, row, meta) {
          return `<div class="d-flex"><a href="functions/editar_Empleado.php?idEmpleadoU=${data}" class="btn"><i class="fas fa-edit color-primary pointer"></i></a>
          <button class="btn" onClick="eliminarEmpleado(${data})"><i class="fas fa-trash-alt color-primary pointer"></i></button></div>`;
        },
      },
      { data: "PKEmpleado" },
      { data: "Nombre" },
      { data: "PrimerApellido" },
      { data: "SegundoApellido" },
      { data: "EstadoCivil" },
      { data: "Genero" },
      { data: "Estado" },
      { data: "Direccion" },
      { data: "Colonia" },
      { data: "CP" },
      { data: "Ciudad" },
      { data: "CURP" },
      { data: "RFC" },
      { data: "FechaNacimiento" },
      { data: "Telefono", visible: false },
      { data: "Estatus", visible: false },
      { data: "FechaIngreso", visible: false },
      { data: "Infonavit", visible: false },
      { data: "DeudaInterna", visible: false },
      { data: "DeudaRestante", visible: false },
      { data: "Turno", visible: false },
      { data: "Puesto", visible: false },
      { data: "Sucursal", visible: false },
      { data: "Empresa", visible: false },
      { data: "NSS", visible: false },
      { data: "TipoSangre", visible: false },
      { data: "ContactoEmergencia", visible: false },
      { data: "NumeroEmergencia", visible: false },
      { data: "Alergias", visible: false },
      { data: "NotasMedicas", visible: false },
      { data: "Banco", visible: false },
      { data: "CuentaBancaria", visible: false },
      { data: "Clabe", visible: false },
      { data: "NumeroTarjeta", visible: false },
    ],
  });

  $(".filtro-columna").on("click", function (e) {
    var item = e.target;
    var column = tableEmpleados.column($(this).attr("data-column"));
    column.visible(!column.visible());
    if (item.tagName === "DIV") {
      item.classList.contains("checked-type-column")
        ? item.classList.remove("checked-type-column", "checked-type-column-imgEmpleados")
        : item.classList.add("checked-type-column", "checked-type-column-imgEmpleados");
    }
  });

  /* BOTON EXCEL */
  $("#importExcel").prop("disabled", true);

  $("#dataexcel").on("change", () => {
    if (!$("#dataexcel").val()) {
      $("#importExcel").prop("disabled", true);
    } else {
      $("#importExcel").prop("disabled", false);
    }
  });
});

/* IMPORTAR EXCEL */
function validarExcel() {
  $("#loaderValidacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("tipo", $("#tipoImportacion").val());
  $.ajax({
    url: "validarExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
    },
    error: function (error) {
      console.log(error.responseText);
      if (error.responseText.includes("Formato no aceptado") == true) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>Formato no aceptado</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else if (error.responseText.includes("error") == true) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: error.responseText.slice(5),
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else if (error.responseText.includes("Formato incorrecto") == true) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>Formato incorrecto</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else if (
        error.responseText != "" &&
        error.responseText.includes("Formato incorrecto") == false &&
        error.responseText.includes("Formato no aceptado") == false
      ) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: error.responseText,
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else if (error.responseText.includes("fail")) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "No se pudo subir el archivo",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else {
        $("#loaderValidacion").css("display", "none");
        importExcel();
      }
    },
  });
}

function importExcel() {
  $("#loaderImportacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("tipo", $("#tipoImportacion").val());
  $.ajax({
    url: "uploadExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    async: false,
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      $("#tblInventariosIniciales").DataTable().ajax.reload();
    },
    error: function (error) {
      console.log(error);
      $("#loaderImportacion").css("display", "none");

      Swal.fire({
        title: "Datos importados",
        icon: "success",
        showCancelButton: false,
        confirmButtonText: "Aceptar",
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
        },
        buttonsStyling: false,
        allowEnterKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.close();
          location.reload();
        } else if (result.isDismissed) {
          Swal.close();
          location.reload();
        }
      });
    },
  });
}
