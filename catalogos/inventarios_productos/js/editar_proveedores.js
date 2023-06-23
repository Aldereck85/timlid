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

function mostrardatosGenerales(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_generales_proveedor",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos generales del proveedor", respuesta);

      if (respuesta[0].FKEstatusGeneral == 1) {
        $("#active-proveedor").prop("checked", true);
      } else {
        $("#active-proveedor").prop("checked", false);
      }

      $("#txtNombreComercial").val(respuesta[0].NombreComercial);
      $("#txtNombreComercialHis").val(respuesta[0].NombreComercial);

      /*cargarCMBMedioContactoClienteEdit(
        "",
        "cmbMedioContactoCliente",
        respuesta[0].FKMedioContactoProveedor
      );
      cargarCMBVendedorEdit("", "cmbVendedor", respuesta[0].FKVendedor);*/

      $("#cmbVendedor").val(respuesta[0].FKVendedor);
      $("#txtTelefono").val(respuesta[0].Telefono);
      $("#txtEmail").val(respuesta[0].Email);
      $("#txtMovil").val(respuesta[0].Movil);
      $("#txtGiro").val(respuesta[0].Giro);
      $("#txtEmail2").val(respuesta[0].SegundoEmail);
      const cmbTipoPersona = new SlimSelect ({
        select: "#cmbTipoPersona",
        placeholder: "Seleccionar tipo de persona",
        deselectLabel: '<span class="">✖</span>',
      });
      cmbTipoPersona.set(respuesta[0].tipo_persona);

      if (respuesta[0].Monto_credito != "" && respuesta[0].Dias_credito != "") {
        $("#cbxCredito").prop("checked", true);
        $("#txtMontoCredito").prop("disabled", false);
        $("#txtDiasCredito").prop("disabled", false);
        $("#txtMontoCredito").val(respuesta[0].Monto_credito);
        $("#txtDiasCredito").val(respuesta[0].Dias_credito);
      }

      elemento = document.getElementById("txtNombreComercial");
      elemento.blur();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBMedioContactoClienteEdit(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_mediosContacto" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta medios de contacto: ", respuesta);

      html += '<option value="0">Seleccione un medio de contacto...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKMedioContactoCliente) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKMedioContactoCliente +
          '" ' +
          selected +
          ">" +
          respuesta[i].MedioContactoCliente +
          "</option>";
      });

      html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar medios de contacto</option>';

      $("#" + input + "").html(html);
      $("#cmbMedioContactoCliente").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBVendedorEdit(data, input, value) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vendedor" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta vendedor: ", respuesta);

      html += '<option value="0">Seleccione un vendedor...</option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKVendedor) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKVendedor +
          '" ' +
          selected +
          ">" +
          respuesta[i].Nombre +
          "</option>";
      });

      html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar vendedores</option>';

      $("#" + input + "").html(html);

      $("#cmbVendedor").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarTablaProductos(id, _permissionsEdit) {
  $("#tblListadoProveedoresProducto").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: [0,6], visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
      dom: {
        button: {
          tag: "button",
          className: "",//btn-table-custom
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
          exportOptions: {
            columns: ":visible",
          },
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
      { data: "Acciones" },
    ],
    columnDefs: [
      { targets: 0, visible: false },
    ]
  });
}