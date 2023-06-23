$(document).ready(function () {
  initSelectPropietario();
  initSelectEtapas();
  loadEtapas();
  initSelectPriridad();
  var slimContactos = initSelectContactos();
  var slimEmpresaClien = initSelectEmpresaClien();
  var slimEmpresaPros = initSelectEmpresaPros();

  $("input[name='tipoContacto']").bind("change", (e) => {
    if (e.target.value == "cliente") {
      $("#div_empreClie").removeClass("d-none");
      $("#div_emprePros").addClass("d-none");
    }
    if (e.target.value == "lead") {
      $("#div_emprePros").removeClass("d-none");
      $("#div_empreClie").addClass("d-none");
    }
    slimContactos.setData([]);
    slimEmpresaClien.set(0);
    slimEmpresaPros.set(0);

    $("#invalid-empresaProsp").css("display", "none");
    $("#empresaProsSelect").removeClass("is-invalid");
    $("#invalid-empresaClien").css("display", "none");
    $("#empresaClieSelect").removeClass("is-invalid");
  });

  $("#empresaProsSelect").bind("change", (e) => {
    loadContactos("prospecto", e.target.value);
    $("#invalid-empresaProsp").css("display", "none");
    $("#empresaProsSelect").removeClass("is-invalid");
  });

  $("#empresaClieSelect").bind("change", (e) => {
    loadContactos("cliente", e.target.value);
    $("#invalid-empresaClien").css("display", "none");
    $("#empresaClieSelect").removeClass("is-invalid");
  });

  $("#valor").keyup(function () {
    var valor = $("#valor").val();
    var c = parseFloat(valor);
    $("#valor_1").val(c);
  });

  $("#etapa").on("change", function () {
    $("#etapa").removeClass("is-invalid");
    $("#invalid-etapa").css("display", "none");
  });

  $("#empresa").on("change", function () {
    $("#empresa").removeClass("is-invalid");
    $("#invalid-empresa").css("display", "none");
  });

  $("#prioridad").on("change", function () {
    $("#prioridad").removeClass("is-invalid");
    $("#invalid-prioridad").css("display", "none");
  });

  $("#nombre").on("change", function () {
    $("#nombre").removeClass("is-invalid");
    $("#invalid-nombre").css("display", "none");
  });

  $("#valor").on("change", function () {
    $("#valor").removeClass("is-invalid");
    $("#invalid-valor").css("display", "none");
  });

  $("#propietario").on("change", function () {
    $("#propietario").removeClass("is-invalid");
    $("#invalid-propietario").css("display", "none");
  });

  $("#btnGuardarNegocio").click(function (e) {
    e.preventDefault();
    var tipo = $("input[type='radio'][name='tipoContacto']:checked").val();
    if (tipo === "lead") {
      if (!$("#empresaProsSelect").val()) {
        $("#invalid-empresaProsp").css("display", "block");
        $("#empresaProsSelect").addClass("is-invalid");
      }
    } else if (tipo === "cliente") {
      if (!$("#empresaClieSelect").val()) {
        $("#invalid-empresaClien").css("display", "block");
        $("#empresaClieSelect").addClass("is-invalid");
      }
    }

    if (!$("#prioridad").val()) {
      $("#invalid-prioridad").css("display", "block");
      $("#prioridad").addClass("is-invalid");
    }
    if (!$("#nombre").val()) {
      $("#invalid-nombre").css("display", "block");
      $("#nombre").addClass("is-invalid");
    }
    if (!$("#etapa").val()) {
      $("#invalid-etapa").css("display", "block");
      $("#etapa").addClass("is-invalid");
    }
    if (!$("#valor").val()) {
      $("#invalid-valor").css("display", "block");
      $("#valor").addClass("is-invalid");
    }
    if (!$("#propietario").val()) {
      $("#invalid-propietario").css("display", "block");
      $("#propietario").addClass("is-invalid");
    }

    var validate_prioridad =
      $("#invalid-prioridad").css("display") === "block" ? false : true;
    var validate_nombre =
      $("#invalid-nombre").css("display") === "block" ? false : true;
    var validate_valor =
      $("#invalid-valor").css("display") === "block" ? false : true;
    var validate_propietario =
      $("#invalid-propietario").css("display") === "block" ? false : true;
    var validate_etapa =
      $("#invalid-etapa").css("display") === "block" ? false : true;

    if (
      validate_prioridad &&
      validate_nombre &&
      validate_valor &&
      validate_propietario &&
      validate_etapa
    ) {
      var accion = "guardarNegocio";
      var empresaPros = $("#empresaProsSelect").val();
      var empresaCliente = $("#empresaClieSelect").val();
      var cliente = $("#cliente").val();
      var etapa_usuario_id = $("#etapa").val();
      var nombre = $("#nombre").val();
      var prioridad_id = $("#prioridad").val();
      var valor = $("#valor_1").val();
      valor.replace(/[$,]+/g, "");
      var result = parseFloat(valor);
      var empleado_id = $("#propietario").val();
      var contacto = 0;
      contacto = $("#contacto").val();
      if($("#contacto").val() == "undefined"){
        contacto = 0;
      }
      var descripcion = $("#descripcion").val();
      $.ajax({
        url: "app/controladores/negocioController.php",
        method: "POST",
        dataType: "json",
        data: {
          nombre: nombre,
          valor: result,
          empresaCliente: empresaCliente,
          empleado_id: empleado_id,
          etapa_usuario_id: etapa_usuario_id,
          prioridad_id: prioridad_id,
          contacto: contacto,
          empresaPros: empresaPros,
          descripcion: descripcion,
          accion: accion,
        },
        success: function (data) {
          if (data.status == "success") {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3100,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: data.message,
            });
            window.location.href = "index.php";
            return;
          }
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: data.message,
          });
        },
      });
    }
  });

  function loadContactos(tipo, id) {
    var accion = "cargarContactosTipo";
    $.ajax({
      url: "app/controladores/EtapasNegocioController.php",
      data: {
        id,
        tipo,
        accion: accion,
      },
      type: "POST",
      dataType: "json",
      success: function (response) {
        slimContactos.setData(response);
      },
      error: function (e) {
        console.log(e);
      },
    });
  }
});

function initSelectEmpresaPros() {
  return new SlimSelect({
    select: "#empresaProsSelect",
    placeholder: "Seleccione una empresa",
    searchPlaceholder: "Buscar empresa",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectEmpresaClien() {
  return new SlimSelect({
    select: "#empresaClieSelect",
    placeholder: "Seleccione una empresa",
    searchPlaceholder: "Buscar empresa",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectPropietario() {
  new SlimSelect({
    select: "#propietario",
    placeholder: "Seleccione un vendedor",
    searchPlaceholder: "Buscar vendedor",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectEtapas() {
  Etapas = new SlimSelect({
    select: "#etapa",
    placeholder: "Seleccione un Etapa",
    searchPlaceholder: "Buscar Etapa",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectContactos() {
  return new SlimSelect({
    select: "#contacto",
    searchPlaceholder: "Buscar Contacto",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectPriridad() {
  new SlimSelect({
    select: "#prioridad",
    placeholder: "Seleccione un Prioridad",
    searchPlaceholder: "Buscar Prioridad",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
    valuesUseText: false,
    data: [
      { placeholder: true, text: "Seleccione un Prioridad" },
      {
        innerHTML:
          '<div class="d-flex"><div class="prioridad-alta"></div> Alta',
        text: "Alta",
        value: "1",
      },
      {
        innerHTML:
          '<div class="d-flex"><div class="prioridad-media"></div> Media',
        text: "Media",
        value: "2",
      },
      {
        innerHTML:
          '<div class="d-flex"><div class="prioridad-baja"></div> Baja',
        text: "Baja",
        value: "3",
      },
    ],
  });
}

function loadEtapas() {
  var accion = "cargarEtapas";
  $.ajax({
    url: "app/controladores/EtapasNegocioController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      $("#etapa").empty();
      $("#etapa").append('<option data-placeholder="true"></option>');
      $.each(response, function (key, value) {
        $("#etapa").append(
          '<option value="' + value.id + '">' + value.etapa + "</option>"
        );
      });
    },
  });
}

$("#empresa").on("change", function () {
  $("#empresa").removeClass("is-invalid");
  $("#invalid-empresa").css("display", "none");
});

$("#nombre").on("change", function () {
  $("#nombre").removeClass("is-invalid");
  $("#invalid-nombre").css("display", "none");
});

$("#valor").on("change", function () {
  $("#valor").removeClass("is-invalid");
  $("#invalid-valor").css("display", "none");
});

$("#propietario").on("change", function () {
  $("#propietario").removeClass("is-invalid");
  $("#invalid-propietario").css("display", "none");
});
