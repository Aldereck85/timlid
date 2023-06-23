$(document).ready(function () {
  $(window).on("load", function () {
    $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
    $("#opEG-2").css({ "background-color": "#cac8c6", color: "#FFFFFF" });

    //Cambiar de color el combo del estatus al abrir por primera vez la página
    if ($("#cmbEstatusProveedor").val() == 1) {
      $("#cmbEstatusProveedor").css({
        "background-color": "#28c67a",
        color: "#FFFFFF",
      });
    } else {
      $("#cmbEstatusProveedor").css({
        "background-color": "#cac8c6",
        color: "#FFFFFF",
      });
    }

    //Asignar plugin Slim a combos desplegables, para la opción de búsqueda de opciones dentro del combo
    /*new SlimSelect({
      select: '#cmbMedioContactoCliente',
      deselectLabel: '<span class="">✖</span>',
      addable: function (value) {
        validarMedioContacto(value);
      }
    });

    new SlimSelect({
      select: '#cmbVendedor',
      deselectLabel: '<span class="">✖</span>',
    }); */
  });
});

function validarMedioContacto(valor) {
  console.log("Valor medio de contacto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_medioContactoProveedor",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta medio de contacto validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("¡Ya existe!");
      } else {
        console.log("¡No existe!");

        anadirMedioContacto(valor);
      }
    },
  });
}

/* Añadir el medio de contacto */
function anadirMedioContacto(valor) {
  console.log("Valor medio de contacto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_medioContactoProveedor",
      datos: valor,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar medio de contacto de cliente:", respuesta);

      if (respuesta[0].status) {
        Swal.fire(
          "Registro exitoso",
          "Se guardo el medio de contacto con exito",
          "success"
        );
        cargarCMBMedioContactoCliente(valor, "cmbMedioContactoCliente");
      } else {
        Swal.fire(
          "Error",
          "No se guardó el medio de contacto con exito",
          "warning"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("change", "#cmbMedioContactoCliente", function () {
  var medioContacto = $("#cmbMedioContactoCliente").val();

  console.log("Selección:" + medioContacto);
  if (medioContacto == "add") {
    window.location.href = "../medios_contacto";
  }
});

$(document).on("change", "#cmbVendedor", function () {
  var vendedor = $("#cmbVendedor").val();

  console.log("Selección:" + vendedor);
  if (vendedor == "add") {
    window.location.href = "../vendedores";
  }
});

function cargarTablaProductos(id, _permissionsEdit) {
  $("#tblListadoProveedoresProducto").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
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
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar Excel</span>',
          className: "btn-custom--white-dark btn-custom",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_productoProveTable",
        data: id,
        data2: _permissionsEdit,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Producto" },
      { data: "Proveedor" },
      { data: "Clave" },
      { data: "Precio" },
      { data: "DiasEntrega" },
    ],
  });
}
