$(document).ready(function () {
  $(window).on("load", function () {
    $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
    $("#opEG-2").css({ "background-color": "#cac8c6", color: "#FFFFFF" });

    //Cambiar de color el combo del estatus al abrir por primera vez la página
    if ($("#cmbEstatusCliente").val() == 1) {
      $("#cmbEstatusCliente").css({
        "background-color": "#28c67a",
        color: "#FFFFFF",
      });
    } else {
      $("#cmbEstatusCliente").css({
        "background-color": "#cac8c6",
        color: "#FFFFFF",
      });
    }

  });

   //Asignar plugin Slim a combos desplegables, para la opción de búsqueda de opciones dentro del combo
   new SlimSelect({
    select: "#cmbMedioContactoCliente",
    placeholder: "Seleciona un medio de contacto",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
    /*addable: function (value) {
      validarMedioContacto(value);
    },*/
  });

  new SlimSelect({
    select: "#cmbVendedor",
    placeholder: "Seleciona un vendedor",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbCategoria",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: "#cmbRegimen",
    placeholder: "Seleciona un régimen fiscal",
    allowDeselect: true,
    deselectLabel: '<span class="">✖</span>',
  });

});

function validarMedioContacto(valor) {
  console.log("Valor medio de contacto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_medioContactoCliente",
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
      funcion: "save_medioContactoCliente",
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

function mostrarDatosGrales(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_generales_cliente",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta datos generales del cliente", respuesta);
      /* TODO: verificar check */
      if (respuesta[0].FKEstatusGeneral == 1) {
        console.log("active");
        $("#active-cliente").attr("checked", "true");
      }

      //$("#cmbEstatusCliente").val(respuesta[0].FKEstatusGeneral);

      //Cambiar de color el combo del estatus al abrir por primera vez la página
      if ($("#cmbEstatusCliente").val() == 1) {
        $("#cmbEstatusCliente").css({
          "background-color": "#28c67a",
          color: "#FFFFFF",
        });
      } else {
        $("#cmbEstatusCliente").css({
          "background-color": "#cac8c6",
          color: "#FFFFFF",
        });
      }

      $("#txtNombreComercial").val(respuesta[0].NombreComercial);
      $("#txtNombreComercialHis").val(respuesta[0].NombreComercial);

      cargarCMBMedioContactoClienteEdit(
        "",
        "cmbMedioContactoCliente",
        respuesta[0].FKMedioContactoCliente
      );

      cargarCMBVendedorEdit("", "cmbVendedor", respuesta[0].FKVendedor);

      $("#txtTelefono").val(respuesta[0].Telefono);
      $("#txtContactoDir").val(respuesta[0].contactoDir);
      $("#txtEmail").val(respuesta[0].Email);

      cargarcmbCategoria(respuesta[0].categoriaCliente, "cmbCategoria");

      if (respuesta[0].Monto_credito != "" && respuesta[0].Dias_credito != "") {
        $("#cbxCredito").prop("checked", true);
        $("#txtMontoCredito").prop("disabled", false);
        $("#txtDiasCredito").prop("disabled", false);
        $("#txtMontoCredito").val(respuesta[0].Monto_credito);
        $("#txtDiasCredito").val(respuesta[0].Dias_credito);
      }

      cargarCMBPaisesEdit("", "cmbPais", respuesta[0].Pais);
      cargarCMBEstadosEdit(respuesta[0].Pais, "cmbEstado", respuesta[0].Estado);
      cargarCMBRegimenEdit("", "cmbRegimenInfoFiscal", respuesta[0].regimen_fiscal_id);

      $("#txtRazonSocial").val(respuesta[0].razon_social);
      $("#txtRFC").val(respuesta[0].rfc);
      $("#txtCalle").val(respuesta[0].Calle);
      $("#txtNumInt").val(respuesta[0].Numero_Interior);
      $("#txtNumExt").val(respuesta[0].Numero_exterior);
      $("#txtColonia").val(respuesta[0].Colonia);
      $("#txtMunicipio").val(respuesta[0].Municipio);

      $("#txtCP").val(respuesta[0].cp);

      $("#txtEdicion").val("1");
      $("#txtRazonSocialHis").val(respuesta[0].razon_social);
      $("#txtRFCHis").val(respuesta[0].rfc);

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

      //html += '<option value="0">Seleccione un medio de contacto...</option>';

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

      /* html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar medios de contacto</option>'; */

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

      //html += '<option value="0">Seleccione un vendedor...</option>';

      $.each(respuesta, function (i) {
        if (value === respuesta[i].PKVendedor) {
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

      console.log("Valor de Vendedor:"+value);
      //$("#" + input + "").val(value);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBRegimenEdit(data, input, value) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_regimen" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de los régimenes fiscales: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (value === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].clave +
          ' - ' +
          respuesta[i].descripcion +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}
