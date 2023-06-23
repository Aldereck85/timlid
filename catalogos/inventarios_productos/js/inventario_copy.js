function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "",
      sLast: "",
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  showTableExistenciaPedido();
  $("#fitro_inventario").click(function () {
    var sucu = $("#sucursal_input").val();
    var exist = $("#existencia_input").val();
    if (!sucu) {
      $("#invalid-sucursal").css("display", "block");
      $("#sucursal_input").addClass("is-invalid");
    }
    if (!exist) {
      $("#invalid-exitencia").css("display", "block");
      $("#existencia_input").addClass("is-invalid");
    }

    var badSucursal =
      $("#invalid-sucursal").css("display") === "block" ? false : true;
    var badExistencia =
      $("#invalid-exitencia").css("display") === "block" ? false : true;

    if (badSucursal && badExistencia) {
      showTableExistenciaPedido(sucu, exist);
    }
  });
});

function showTableExistenciaPedido(sucu = "todas", exist = "todos") {
  $("#tblListadoInventario").DataTable().destroy();
  $("#tblListadoInventario").DataTable({
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
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_inventory2",
        sucu,
        exist,
      },
    },
    columns: [
      { data: "Sucursal" },
      { data: "Clave" },
      { data: "Pedidos" },
      { data: "Existencia" },
      { data: "Acciones" },
    ],
  });
}

function getInfoStock(idExistencia) {
  var idMinimo = "id-min-" + idExistencia;
  var idMaximo = "id-max-" + idExistencia;
  var idClave = "id-clave-" + idExistencia;
  var idSerieLote = "id-serieLote-" + idExistencia;

  var minimo = document.getElementById(idMinimo);
  var maximo = document.getElementById(idMaximo);
  var clave = document.getElementById(idClave);
  var serieLote = document.getElementById(idSerieLote);

  document.getElementById("id-stock").value = idExistencia;
  document.getElementById("stock-minimo").value = minimo.value;
  document.getElementById("stock-maximo").value = maximo.value;
  document.getElementById("stock-producto").textContent = clave.value;
  document.getElementById("stock-serieLote").textContent = serieLote.value;
}

function setInfoStock() {
  var minimo = document.getElementById("stock-minimo").value;
  var maximo = document.getElementById("stock-maximo").value;
  var idExistencia = document.getElementById("id-stock").value;
  if (parseInt(minimo) > parseInt(maximo)) {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top", //or 'center bottom'
      icon: true,
      img: "../../../../img/timdesk/notificacion_error.svg",
      msg: "¡El stock minimo no puede ser mayor al stock maximo!",
      sound: "../../../../../sounds/sound4",
    });
    return;
  }
  updateMinMaxStock(minimo, maximo, idExistencia);
}

function updateMinMaxStock(minimo, maximo, idExistencia) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_inventario",
      minimo,
      maximo,
      idExistencia,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.status === "success") {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Stock modificado!",
          sound: "../../../../../sounds/sound4",
        });
        $("#tblListadoInventario").DataTable().ajax.reload();
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal!",
          sound: "../../../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top", //or 'center bottom'
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal!",
        sound: "../../../../../sounds/sound4",
      });
    },
  });
}

function validEmptyInput(item, invalid = null) {
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  if (invalid) {
    invalidDiv = document.getElementById(invalid);
  } else {
    for (let i = 0; i < parent.children.length; i++) {
      if (parent.children[i].classList.contains("invalid-feedback")) {
        invalidDiv = parent.children[i];
        break;
      }
    }
  }
  if (!val) {
    item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
}
