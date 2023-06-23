var slimPropietarios = initSelect();
var slimMedios = initSelectMedios();
var slimEstados = initSelectEstados();
var slimPaises = initSelectPaises();
loadPropietarios(slimPropietarios);
loadMedios(slimMedios);

$("#nombre").on("change", function () {
  $("#nombre").removeClass("is-invalid");
  $("#invalid-nombre").css("display", "none");
});

$("#empresa").on("change", function () {
  $("#empresa").removeClass("is-invalid");
  $("#invalid-empresa").css("display", "none");
});

$("#celular").on("change", function () {
  $("#celular").removeClass("is-invalid");
  $("#invalid-celular").css("display", "none");
});

$("#btnGuardarContacto").click(function (e) {
  e.preventDefault();
  if (!$("#empresa").val()) {
    $("#invalid-empresa").css("display", "block");
    $("#empresa").addClass("is-invalid");
  }
  if (!$("#nombre").val()) {
    $("#invalid-nombre").css("display", "block");
    $("#nombre").addClass("is-invalid");
  }
  if (!$("#celular").val()) {
    $("#invalid-celular").css("display", "block");
    $("#celular").addClass("is-invalid");
  }

  var validate_company =
    $("#invalid-empresa").css("display") === "block" ? false : true;
  var validate_name =
    $("#invalid-nombre").css("display") === "block" ? false : true;
  var validate_celular =
    $("#invalid-celular").css("display") === "block" ? false : true;

  if (validate_name && validate_company && validate_celular) {
    var empresa = $("#empresa").val();
    var propietario =
      $("#propietario").val() === "undefined" || !$("#propietario").val()
        ? ""
        : $("#propietario").val();
    var medio_contacto_id =
      $("#campania").val() === "undefined" || !$("#campania").val()
        ? ""
        : $("#campania").val();
    var nombre = $("#nombre").val();
    var apellido = $("#apellido").val();
    var puesto = $("#puesto").val();
    var email = $("#email").val();
    var telefono = $("#telefono").val();
    var celular = $("#celular").val();
    var sitio_web = $("#sitio-web").val();
    var direccion = $("#direccion").val();
    var aniversario = $("#aniversario").val();
    var estado_id = $("#estado").val();
    var pais_id = $("#pais").val();
    $.ajax({
      type: "POST",
      url: "app/controladores/ContactoController.php",
      dataType: "json",
      data: {
        accion: "agregarContacto",
        empresa,
        propietario,
        medio_contacto_campania_id: medio_contacto_id,
        nombre,
        apellido,
        puesto,
        email,
        telefono,
        celular,
        sitio_web,
        direccion,
        aniversario,
        estado_id,
        pais_id,
      },
      success: function (res) {
        if (res.status === "success") {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: res.message,
          });
          window.location.href = "../contactos/";
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3100,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/warning_circle.svg",
            msg: res.message,
          });
          /* if (response["tipo"] == "nombre") {
              $("#invalid-nombre").css("display", "block");
              $("#nombre").addClass("is-invalid");
              $("#invalid-nombre").text(response["message"]);
            } else if (response["tipo"] == "email") {
              $("#invalid-email").css("display", "block");
              $("#email").addClass("is-invalid");
              $("#invalid-email").text(response["message"]);
            } */
        }
      },
      error: function (e) {
        console.log(e);
      },
    });
  }
});

function initSelect() {
  return new SlimSelect({
    select: "#propietario",
    placeholder: "Seleccione un vendedor",
    searchPlaceholder: "Buscar vendedor",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectMedios() {
  return new SlimSelect({
    select: "#campania",
    placeholder: "Seleccione un medio",
    searchPlaceholder: "Buscar medios",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      if (value === "" || !value) {
        return false;
      }
      validateMedio(value);
    },
  });
}

function initSelectEstados() {
  return new SlimSelect({
    select: "#estado",
    placeholder: "Seleccione un Estados",
    searchPlaceholder: "Buscar Estados",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initSelectPaises() {
  return new SlimSelect({
    select: "#pais",
    placeholder: "Seleccione un Pais",
    searchPlaceholder: "Buscar Pais",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function loadPropietarios(slimSelectItem) {
  $.ajax({
    url: "app/controladores/ContactoController.php",
    data: { accion: "cargarPropietarios" },
    type: "POST",
    dataType: "json",
    success: function (response) {
      slimSelectItem.setData(response);
    },
  });
}

function loadMedios(slimSelectItem, selectedItem = "") {
  $.ajax({
    url: "app/controladores/ContactoController.php",
    data: { accion: "cargarMedios" },
    type: "POST",
    dataType: "json",
    success: function (response) {
      slimSelectItem.setData(response);
      slimSelectItem.set(selectedItem);
    },
  });
}

function validateMedio(medio) {
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "app/controladores/ContactoController.php",
    data: {
      accion: "validateMedio",
      medio,
    },
    success: function (res) {
      if (res.status === "fail") {
        //lobibox
        return;
      }
      if (res.existe === 0) {
        addMedio(medio);
      } else {
        //lobibox
        return;
      }
    },
    error: function (e) {},
  });
}

function addMedio(medio) {
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "app/controladores/ContactoController.php",
    data: {
      accion: "addMedio",
      medio,
    },
    success: function (res) {
      if (res.status === "fail") {
        //lobibox
        return;
      }
      loadMedios(slimMedios, res.id);
    },
    error: function (e) {},
  });
}
