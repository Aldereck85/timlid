$(document).ready(function () {
  loadEtapas();
  //initPropietarios();
  //loadPropietarios();
  $("#filter_all").hide();
  $('[data-toggle="tooltip"]').tooltip();
  window.addEventListener("click", ocultarEtapas);

  
});

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
      $.each(response, function (key, value) {
        if (value.id !== 6 && value.id !== 7) {
          var claseEtapaActiva =
            value.active === 1 ? "checked-type-column" : "check-type-column";
          $("#lista-etapas").append(
            '<div class="p-3 d-flex align-items-center listItem cursor-pointer" data-id="' +
              value.id +
              '"  onclick="ckeck(' +
              value.id +
              ')">' +
              '<div class="text-left mr-3">\n' +
              '<div id="checkType-' +
              value.id +
              '" class="' +
              claseEtapaActiva +
              '"></div>\n' +
              '</div><div><span class="etapa-nombre">' +
              value.etapa +
              "</span></div>"
          );
        }

        if (value.id === 6 || value.id === 7) {
          var claseGanadoPerdido =
            value.id === 6 ? "cierre-ganado" : "cierre-perdido";
          $("#negocios-not-draggable").append(
            ' <div class="card mt-2 mt-lg-0 border etapas ' +
              claseGanadoPerdido +
              '" id="column_' +
              value.id +
              '" data-id="' +
              value.id +
              '" data-orden="' +
              value.orden +
              '"  data-etapa="' +
              value.etapa_id +
              '"> \n' +
              '<div class="card-header">' +
              value.etapa +
              "</div>\n" +
              '<div class="card-body space-y-1" id="card_grid-' +
              value.id +
              '"></div>\n' +
              '<div class="card-footer total-etapa-' +
              value.etapa_id +
              '">\n' +
              'Total: $ <span class="total">0.00</span>\n' +
              "</div>\n" +
              "</div>"
          );
        } else {
          if (value.active === 1) {
            $("#negocios-draggable").append(
              '<div class="card mt-2 mt-lg-0 border etapas" id="column_' +
                value.id +
                '" data-id="' +
                value.id +
                '" data-orden="' +
                value.orden +
                '"  data-etapa="' +
                value.etapa_id +
                '"> \n' +
                '<div class="card-header">' +
                value.etapa +
                "</div>\n" +
                '<div class="card-body space-y-1" id="card_grid-' +
                value.id +
                '"></div>\n' +
                '<div class="card-footer total-etapa-' +
                value.etapa_id +
                '">\n' +
                'Total: $ <span class="total">0.00</span>\n' +
                "</div>\n" +
                "</div>"
            );
          }
        }
        getRows(value.id);
        functionSortable();
      });
    },
  });
}

function functionSortable() {
  var el = document.getElementById("negocios-draggable");
  new Sortable(el, {
    group: "list-2",
    onEnd: function () {
      var position = [];

      $(".etapas").each(function () {
        position.push($(this).attr("data-id"));
      });

      $.ajax({
        url: "app/controladores/EtapasNegocioController.php",
        method: "POST",
        dataType: "json",
        data: {
          // id:position[0],
          orden: position,
          accion: "updateOrdenColumn",
        },
        success: function (r) {},
        error: function (e) {},
      });
    },
  });
}

function initPropietarios() {
  Prioriodades = new SlimSelect({
    select: "#propietarioNegocio",
    placeholder: "Seleccione un vendedor",
    searchPlaceholder: "Buscar vendedor",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function loadPropietarios() {
  var accion = "cargarPropietarios";
  $.ajax({
    url: "app/controladores/EtapasNegocioController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      $("#propietarioNegocio").append('<option value="todos">Todos</option>');
      $.each(response, function (key, value) {
        $("#propietarioNegocio").append(
          "<option value=" + value.id + ">" + value.nombre + "</option>"
        );
      });
    },
  });
}

function mostrarEtapas(ev) {
  if ($(".listaColumnas").hasClass("invisible")) {
    $(".listaColumnas").removeClass("invisible");
  } else {
    $(".listaColumnas").addClass("invisible");
  }
  ev.stopPropagation();
}

function ocultarEtapas(ev) {
  if (
    $(ev.target).closest(".listaColumnas").length == 0 &&
    $(ev.target).closest("#AgregarEtapa").length == 0
  ) {
    $(".listaColumnas").addClass("invisible");
  }
}

function ckeck(id) {
  console.log({ id });
  if ($("#checkType-" + id).hasClass("checked-type-column")) {
    if ($("#card_grid-" + id + " .negocio_parent").length > 0) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "¡La etapa contiene negocios!",
      });
    } else {
      $("#checkType-" + id).removeClass("checked-type-column");
      $("#checkType-" + id).addClass("check-type-column");
      $("#column_" + id).remove();
      $.ajax({
        url: "app/controladores/EtapasNegocioController.php",
        method: "POST",
        data: {
          accion: "activarDesactivarEtapa",
          activarDesactivar: 0,
          etapa_id: id,
        },
        success: function (data) {},
      });
    }
  } else {
    $("#checkType-" + id).removeClass("check-type-column");
    $("#checkType-" + id).addClass("checked-type-column");

    const index = $(".listItem").index($(".listItem[data-id='" + id + "']"));
    const nombre = $(".listItem[data-id='" + id + "'] .etapa-nombre").html();
    var elemento = `<div class="card mt-2 mt-lg-0 border etapas" id="column_${id}" data-id="${id}" data-orden="1" data-etapa="${id}">
                    <div class="card-header">${nombre}</div>
                    <div class="card-body space-y-1" id="card_grid-${id}">
                    </div>
                    <div class="card-footer total-etapa-${id}">Total: $ <span class="total">0.00</span></div>
                  </div>`;
    var negociosDraggable = document.getElementById("negocios-draggable");
    negociosDraggable.insertAdjacentHTML("beforeend", elemento);
    $.ajax({
      url: "app/controladores/EtapasNegocioController.php",
      method: "POST",
      data: {
        accion: "activarDesactivarEtapa",
        activarDesactivar: 1,
        etapa_id: id,
      },
      success: function (data) {},
    });
  }
}

function cambiarOrden(ev) {
  const id = $(ev.item).attr("data-id");
  const html = $(".etapas[data-id='" + id + "']");

  $(".etapas[data-id='" + id + "']").remove();
  if (ev.newIndex > 0) {
    const despuesDe = $(".grid-etapas .etapas:nth-child(" + ev.newIndex + ")");
    despuesDe.after(html);
  } else {
    $(".grid-etapas .etapas:nth-child(1)").before(html);
  }

  $.ajax({
    url: "app/controladores/EtapasNegocioController.php",
    method: "POST",
    data: {
      etapa_id: id,
    },
    success: function (data) {},
  });
}

function cambiarOrdenEtapas(ev) {
  const id = $(ev.item).attr("data-id");
  const html = $(".listItem[data-id='" + id + "']");
  $(".listItem[data-id='" + id + "']").remove();
  if (ev.newIndex > 0) {
    const despuesDe = $(
      "#lista-etapas .listItem:nth-child(" + ev.newIndex + ")"
    );
    despuesDe.after(html);
  } else {
    $("#lista-etapas .listItem:nth-child(1)").before(html);
  }
}

function agregarEtapa(ev) {
  const etapa = $("#etapa").val();
  if (!etapa) {
    $("#etapa-invalid").css("display", "block");
    $("#etapa").addClass("is-invalid");
    return;
  }
  $("#etapa-invalid").css("display", "none");
  $("#etapa").removeClass("is-invalid");
  $.ajax({
    url: "app/controladores/EtapasNegocioController.php",
    method: "POST",
    dataType: "json",
    data: {
      etapa,
      accion: "guardarEtapa",
    },
    success: function (data) {
      var nuevaEtapaLista = `<div class="p-3 d-flex align-items-center listItem cursor-pointer" data-id="${data.res.idEtapa}" onclick="ckeck(${data.res.idEtapa})"><div class="text-left mr-3">
      <div id="checkType-${data.res.idEtapa}" class="checked-type-column"></div>
      </div><div><span class="etapa-nombre">${etapa}</span></div></div>`;

      var nuevaEtapaDrag = `<div class="card mt-2 mt-lg-0 border etapas" id="column_${data.res.idEtapa}" data-id="${data.res.idEtapa}" data-orden="${data.res.ord}" data-etapa="${data.res.idEtapa}">
      <div class="card-header">${etapa}</div>
      <div class="card-body space-y-1" id="card_grid-${data.res.idEtapa}"></div>
      <div class="card-footer total-etapa-${data.res.idEtapa}">Total: $ <span class="total">0</span></div>
      </div>`;
      var listaEtapas = document.getElementById("lista-etapas");
      listaEtapas.insertAdjacentHTML("beforeend", nuevaEtapaLista);
      var negociosDraggable = document.getElementById("negocios-draggable");
      negociosDraggable.insertAdjacentHTML("beforeend", nuevaEtapaDrag);

      $("#AgregarEtapa").modal("hide");
      $("#etapa").val("");
    },
    error: function (e) {},
  });
}

function siguiente(e) {
  var negocio = e.parentElement.parentElement.parentElement;
  var negocioID = e.parentElement.parentElement.parentElement.dataset.id;
  var etapa =
    e.parentElement.parentElement.parentElement.parentElement.parentElement
      .nextElementSibling.children[1];
  var etapaID =
    e.parentElement.parentElement.parentElement.parentElement.parentElement
      .nextElementSibling.dataset.etapa;
  var etapaActualID =
    e.parentElement.parentElement.parentElement.parentElement.parentElement
      .dataset.etapa;
  $.ajax({
    url: "app/controladores/EtapasNegocioController.php",
    method: "POST",
    data: {
      etapa_id: etapaID,
      accion: "updateSiguiente",
      id: negocioID,
    },
    success: function (data) {
      negocio.remove();
      etapa.insertAdjacentElement("beforeend", negocio);
      calcularTotalEtapa(etapaActualID);
      calcularTotalEtapa(etapaID);
    },
    error: function () {},
  });
}

function anterior(e) {
  var negocio = e.parentElement.parentElement.parentElement;
  var negocioID = e.parentElement.parentElement.parentElement.dataset.id;
  var etapa =
    e.parentElement.parentElement.parentElement.parentElement.parentElement
      .previousElementSibling.children[1];
  var etapaID =
    e.parentElement.parentElement.parentElement.parentElement.parentElement
      .previousElementSibling.dataset.etapa;
  var etapaActualID =
    e.parentElement.parentElement.parentElement.parentElement.parentElement
      .dataset.etapa;
  $.ajax({
    url: "app/controladores/EtapasNegocioController.php",
    method: "POST",
    data: {
      etapa_id: etapaID,
      accion: "updateAnterior",
      id: negocioID,
    },
    success: function () {
      negocio.remove();
      etapa.insertAdjacentElement("beforeend", negocio);
      calcularTotalEtapa(etapaActualID);
      calcularTotalEtapa(etapaID);
    },
    error: function (e) {},
  });
}

function getRows(id) {
  var accion = "cargarFilas";
  $.ajax({
    url: "app/controladores/EtapasNegocioController.php",
    method: "POST",
    data: {
      accion: accion,
      id: id,
    },
    dataType: "json",
    success: function (data) {
      loadBodyCards(data);
      calcularTotalEtapa(id);
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function getNegocios() {
  var lista = $("div.etapas")
    .map(function () {
      return $(this).attr("data-id");
    })
    .get();
  var accion = "filtrarNegocioFechas";
  var date_1 = $("#fecha_inicio").val();
  var date_2 = $("#fecha_fin").val();

  if (date_1 == "") {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3100,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Necesitas agregar la fecha inicio",
    });
  } else if (date_2 == "") {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3100,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../img/timdesk/warning_circle.svg",
      msg: "Necesitas agregar la fecha final",
    });
  } else {
    $.ajax({
      url: "app/controladores/EtapasNegocioController.php",
      method: "POST",
      data: {
        fecha_inicio: date_1,
        fecha_fin: date_2,
        accion: accion,
      },
      dataType: "json",
      success: function (data) {
        $.each(lista, function (key, card_id) {
          $("#card_grid-" + card_id).empty();
        });
        //FECHAS
        loadBodyCards(data);
        $("#filter_dates").hide();
        $("#filter_all").show();
      },
    });
  }
}

function getRowsNegocios() {
  var id = $("div.etapas")
    .map(function () {
      return $(this).attr("data-id");
    })
    .get();
  $.each(id, function (key, card_id) {
    getRows(card_id);
    $("#filter_all").hide();
    $("#filter_dates").show();
  });
}

function getNegocioByEmpleado(obj) {
  var lista = $("div.etapas")
    .map(function () {
      return $(this).attr("data-id");
    })
    .get();
  var accion = "filtrarNegociosByEmpleado";
  var empleado = obj.value;

  $.ajax({
    url: "app/controladores/EtapasNegocioController.php",
    method: "POST",
    data: {
      empleado_id: empleado,
      accion: accion,
    },
    dataType: "json",
    success: function (data) {
      $.each(lista, function (key, card_id) {
        $("#card_grid-" + card_id).empty();
      });
      loadBodyCards(data);
    },
    error: function (e) {},
  });
}

function loadBodyCards(data) {
  $.each(data, function (key, value) {
    var nombreContacto = "";
    var empresa = "";
    var descripcion = value.descripcion == null ? '' : value.descripcion;
    if(value.motivo == null){
      var motivo = ``;
    }else{
      var motivo = `<div data-toggle="modal" data-target="#modalTextElement-${value.id}" style="cursor: pointer;"><span class="text-sm">Motivo: </span><span class="text-sm font-weight-bold" style="overflow:hidden; white-space:nowrap; text-overflow: ellipsis; width: 100px; display: block;">${value.motivo}</span></div>`;
    }
    if (value.nombreContClient) {
      nombreContacto = value.nombreContClient;
    }
    if (value.nombreContProps) {
      nombreContacto = value.nombreContProps;
    }
    if (value.nombreComercial) {
      empresa = value.nombreComercial;
    }
    if (value.empresa) {
      empresa = value.empresa;
    }
    $("#card_grid-" + value.etapa_id).append(
    ` <div class="modal fade" id="modalTextElement-${value.id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="padding: 0.5rem 1rem; font-family: unset; display: flex;align-items: center;">
              <img src="../../img/nuevos-iconos/ICONO-MOTIVO BLANCO NVO-01.svg" style="width: 50px">
              <h5 class="modal-title tittle-text-task" id="exampleModalLabel">Motivo</h5>
              <button type="button" class="close btn-close-text-element" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">x</span>
              </button>
            </div>
            <div class="modal-body">
              <textarea id="textAreaElement" cols="30" rows="10" maxlength="150" style="width: 100%;"
                placeholder="Agrega notas a la tarea (150 caracteres máximo.)" readonly>${value.motivo}
              </textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal" style="height: 41px;">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <div id="negocio-id-${
        value.id
      }" class="position-relative negocio_parent" data-id="${value.id}" data-toggle="tooltip" data-placement="right" title="${
        descripcion
      }">
          <div class="negocio border rounded-bottom p-2 w-full ${
            value.prioridad
          }-prioridad">
            <h5 class="text-center mb-0">${nombreContacto}</h5>
            <a type="button" class="btn btn-xs rounded-full float-right p-1" data-toggle="tooltip" title="Editar negocio" href="editar_negocio.php?id=${
              value.id
            }">
            <i class="fas fa-edit text-info"></i>
            </a>
            <h6 class="text-center mb-0" data-toggle="tooltip" data-placement="top" title="${
              value.prioridad
            }-prioridad">${value.nombre}</h6>
            <div><span class="text-sm font-weight-bold">${
              empresa
            }</span></div>
            <div><span class="text-sm font-weight-bold">${
              value.nombre_empleado
            }</span></div>${motivo}
            <div><span class="text-sm">Prioridad: </span><span class="text-sm font-weight-bold">${
              value.prioridad
            }</span></div>
            <span class="text-sm valor etapa-${
              value.etapa_id
            }" data-type="currency" >${addCommas(value.valor)}</span>
            <div class="d-flex justify-content-center mt-1">
              <button type="button" class="btn btn-xs mx-2 rounded-full btn-anterior" id="${
                value.id
              }" data-toggle="tooltip" title="Anterior" onclick="anterior(this)">
                <i class="fas fa-arrow-left text-info"></i>
              </button>
              <button type="button" class="btn btn-xs mx-2 rounded-full btn-ganado" data-toggle="tooltip" title="Cierres ganados" onclick="cierreGanado(${
                value.id
              })">
                <i class="fas fa-check text-success"></i>
              </button>
              <button type="button" class="btn btn-xs mx-2 rounded-full btn-perdido" data-toggle="tooltip" title="Cierres perdidos" onclick="cierrePerdido(${
                value.id
              })">
                <i class="fas fa-times text-danger"></i>
              </button>
              <button type="button" class="btn btn-xs mx-2 rounded-full btn-siguiente" id="${
                value.id
              }" data-toggle="tooltip" title="Siguiente" onclick="siguiente(this)">
                <i class="fas fa-arrow-right text-info"></i>
              </button>
            </div>
          </div>
        </div>`
    );
  });
  $("[data-toggle='tooltip']").tooltip({
    trigger: "click focus hover",
  });
}

function addCommas(nStr) {
  nStr += "";
  x = nStr.split(".");
  x1 = x[0];
  x2 = x.length > 1 ? "." + x[1] : "";
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, "$1" + "," + "$2");
  }
  return x1 + x2;
}

function cierreGanado(idNegocio) {
  Swal.fire({
    title: "¿Transferir negocio a cierres ganados?",
    input: 'text',
    inputAttributes: {
      id: 'motivo-'+idNegocio
    },
    inputPlaceholder: 'Escribe un motivo',
    showDenyButton: true,
    confirmButtonText: "Si",
    denyButtonText: "No",
    buttonsStyling: false,
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--blue",
      denyButton: "btn-custom btn-custom--border-blue",
      inputLabel: 'd-block',
    },
    inputValidator: (value) => {
      if (!value) {
        return 'Escribe un motivo'
      }
    }
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "app/controladores/EtapasNegocioController.php",
        method: "POST",
        data: {
          accion: "cierreGanado",
          ganadoPerdido: 6,
          negocio: idNegocio,
          motivo: $("#motivo-"+idNegocio).val()
        },
        success: function () {
          window.location.reload();
          var negocio = document.getElementById("negocio-id-" + idNegocio);
          var etapaActual = negocio.parentElement.parentElement.dataset.etapa;
          var etapaGanados = document.getElementById("card_grid-6");
          negocio.remove();
          etapaGanados.insertAdjacentElement("beforeend", negocio);
          calcularTotalEtapa(6);
          calcularTotalEtapa(etapaActual);
        },
        error: function (e) {},
      });
    }
  });
}

function cierrePerdido(idNegocio) {
  Swal.fire({
    title: "¿Transferir negocio a cierres perdidos?",
    input: 'text',
    inputAttributes: {
      id: 'motivo-'+idNegocio
    },
    inputPlaceholder: 'Escribe un motivo',
    showDenyButton: true,
    confirmButtonText: "Si",
    denyButtonText: "No",
    buttonsStyling: false,
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--blue",
      denyButton: "btn-custom btn-custom--border-blue",
      inputLabel: 'd-block',
    },
    inputValidator: (value) => {
      if (!value) {
        return 'Escribe un motivo'
      }
    }
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "app/controladores/EtapasNegocioController.php",
        method: "POST",
        dataType: "json",
        data: {
          accion: "cierreGanado",
          ganadoPerdido: 7,
          negocio: idNegocio,
          motivo: $("#motivo-"+idNegocio).val()
        },
        success: function (res) {
          window.location.reload();
          var negocio = document.getElementById("negocio-id-" + idNegocio);
          var etapaActual = negocio.parentElement.parentElement.dataset.etapa;
          var etapa = document.getElementById("card_grid-7");
          negocio.remove();
          etapa.insertAdjacentElement("beforeend", negocio);
          calcularTotalEtapa(7);
          calcularTotalEtapa(etapaActual);
        },
        error: function (e) {},
      });
    }
  });
}

function calcularTotalEtapa(idEtapa) {
  var negocios = document.getElementById("card_grid-" + idEtapa);
  if (!negocios) return;
  var negociosArr = [...negocios.childNodes].filter((item) => {
    if (item.nodeName === "DIV") return item;
  });
  if (negociosArr.length < 0) {
    var divTotal = document.querySelector(".total-etapa-" + idEtapa);
    divTotal.innerHTML = "";
    divTotal.innerHTML = "Total: $ <span class='total'>0.00</span>";
    return;
  }
  var totalFloat = 0;
  var total = negociosArr.reduce((prev, current) => {
    var value = current.firstElementChild.children[6];
    var value1 = current.firstElementChild.children[7];

    if(value1 !== undefined && value1.classList.contains('valor'))
    {
        totalFloat += parseFloat(value1.textContent.split(",").join(""));
    }
    
    if(value !== undefined && value.classList.contains('valor')){
        totalFloat += parseFloat(value.textContent.split(",").join(""));
    }
    return totalFloat;
  }, 0);
  
  total = new Intl.NumberFormat("es-MX",{
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(total);
  var divTotal = document.querySelector(".total-etapa-" + idEtapa);
  divTotal.innerHTML = "";
  divTotal.innerHTML = "Total: $ <span class='total'>" + total + "</span>";
}


$(document).on('click','.text-info',()=>{
    $("[data-toggle='tooltip']").tooltip('hide');
});

$(document).on('click','.text-center',()=>{
    $("[data-toggle='tooltip']").tooltip('hide');
});

$(document).on('click','div',()=>{
    $("[data-toggle='tooltip']").tooltip('hide');
});