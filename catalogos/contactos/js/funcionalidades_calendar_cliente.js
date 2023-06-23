let calendar;
let date_picker;
let filter_option = "all";
let contacto_id;
let evento;
let info_evento;
let selectionInfo;
let filter_option_all_events = "activides_contacto";
let obj;
let contacto_id_calendar;
let group_event_id;

$(document).ready(function () {
  $("#editar_actividad_id").hide();
  $("#actividad_id").hide();

  $("#tarea_con_recurrencia").hide();

  $("#fecha_tarea-2").change(function () {
    var valor = $(this).val();
    $("#fecha").val(valor);
    var fecha_es = crearFecha(valor);
    $("#label-date-tarea").text(fecha_es);
  });

  $("#fecha_correo-2").change(function () {
    var valor = $(this).val();
    $("#fecha").val(valor);
    var fecha_es = crearFecha(valor);
    $("#label-date-correo").text(fecha_es);
  });

  $("#fecha_llamada-2").change(function () {
    var valor = $(this).val();
    $("#fecha").val(valor);
    var fecha_es = crearFecha(valor);
    $("#label-date-llamada").text(fecha_es);
  });

  $("#fecha_reunion-2").change(function () {
    var valor = $(this).val();
    $("#fecha").val(valor);
    var fecha_es = crearFecha(valor);
    $("#label-date-reunion").text(fecha_es);
  });

  setTimeout(function () {
    contacto_id_calendar = $("#contacto_id").val();
    initCalendar();
  }, 2000);

  $("#tarea_recurrente").click(function () {
    $("#tarea_con_recurrencia").show();
  });

  initTokenField();
  initContactos();
  loadContactos();
  initActividades();
  loadActividades();
  initPrioridades();
  loadPrioridades();
  initEstatusLlamada();
  loadEstatusLlamada();
  initEmpleadoReunion();
  loadEmpleados();
  actividadTarea();
  actividadReunion();
  actividadCorreo();
  actividadLLamada();
});

function initCalendar() {
  var calendarEl = $("#calendar").get(0);
  var id = $("#contacto_id").val();
  calendar = new FullCalendar.Calendar(calendarEl, {
    locale: "es",
    editable: true,
    selectable: true,
    defaultView: "dayGridMonth",
    eventDurationEditable: false,
    schedulerLicenseKey: "GPL-My-Project-Is-Open-Source",
    plugins: [
      "interaction",
      "dayGrid",
      "timeGrid",
      "resourceTimeline",
      "list",
      "rrule",
    ],
    header: {
      left: "prev,next today myCustomButton",
      center: "title",
      right: "dayGridMonth,timeGridWeek,listMonth",
    },
    events: {
      url: "app/controladores/loadCalendarController.php",
      method: "POST",
      extraParams: {
        accion: "cargar_eventos_cliente",
        id,
      },
    },
    eventRender: function (info) {
      if (filter_option !== "all" && info.event.groupId !== filter_option) {
        return false;
      }
    },
    select: function (selectionInfo) {
      console.log("jejej");
      let fecha = selectionInfo.startStr;
      Actividades.enable();
      loadCamposModal(fecha);
    },
    eventClick: function (info) {
      loadCamposModal();
      evento = info.event.extendedProps;
      info_evento = info.event;
      group_event_id = info_evento.groupId;
      var contactoCliente = null;
      if (evento.contacto_id) contactoCliente = "cnt-" + evento.contacto_id;
      if (evento.cliente_id) contactoCliente = "cli-" + evento.cliente_id;

      $("#guardar_actividad").hide();
      $("#actividad_id").val(info_evento.id);
      $("#actividad_event_reunion").val(info_evento.id);
      $("#editar_actividad_id").val(info_evento.id);

      $("#editar_actividad_id").show();
      $("#actividad_id").show();

      $("#titulo").val(info_evento.title);
      Contactos.set(contactoCliente);
      Actividades.set(evento.tipo_actividad_id);
      Actividades.disable();

      checkColors(evento.color);

      if (evento.tipo_actividad_id == 1) {
        actvidadUno(evento, info_evento);
      }
      if (evento.tipo_actividad_id == 2) {
        actividadDos(evento, info_evento);
      }
      if (evento.tipo_actividad_id == 3) {
        actividadTres(evento, info_evento);
      }
      if (evento.tipo_actividad_id == 4) {
        actividadCuatro(evento, info_evento);
      }
      $("#descripcion").val(evento.descripcion);
      showModalEditar();
    },
    eventDrop: function (info) {
      editEvent(info);
    },
    /*eventResize: function (info) {
      editEvent(info);
    }, */
  });

  calendar.render();

  var url = new URL(window.location.href);
  var meet = url.searchParams.get("meet");
  if (meet) {
    var dateFormat = new Date(meet * 1000);
    calendar.gotoDate(dateFormat);
  }
}

function loadCamposModal(fecha = "") {
  console.log($("#contacto_id").val());
  actividadCorreo();
  actividadReunion();
  actividadTarea();
  actividadLLamada();
  $("#guardar_actividad").show();
  $("#editar_actividad_id").hide();
  $("#actividad_id").hide();

  $("#titulo").val("");
  Contactos.set($("#contacto_id_select").val());
  Actividades.set("0");
  Prioriodades.set("0");
  EstatusResultado.set("0");
  EmpleadoReunion.destroy();
  $("#empleadoModal option:selected").prop("selected", false);
  initEmpleadoReunion();

  $("input[type='radio'][name='color-actividad']").prop("checked", false);
  $("#cbox1").prop("checked", false);
  var hora = getHour();

  $("#fecha_correo-2").val("");
  $("#label-date-correo").text("");
  $("#hora_correo").val(hora);

  $("#fecha_llamada-2").val("");
  $("#label-date-llamada").text("");
  $("#hora_llamada").val(hora);

  $("#fecha_tarea-2").val("");
  $("#label-date-tarea").text("");
  $("#hora_tarea").val(hora);

  $("#lugar_reunion").val("");
  $("#fecha_reunion-2").val("");
  $("#label-date-reunion").text(fecha_es);
  $("#hora_inicio_reunion").val(hora);
  $("#hora_final_reunion").val(hora);

  $("#invitados").tokenfield("setTokens", []);
  $("#descripcion").val("");

  if (!fecha) {
    var currentDate = new Date();
    var day = ("0" + currentDate.getDate()).slice(-2);
    var month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
    var year = currentDate.getFullYear();
  }
  var date = fecha ? fecha : `${year}-${month}-${day}`;
  var fecha_es = crearFecha(date);

  $("#fecha").val(date);

  $("#fecha_correo-2").val(date);
  $("#label-date-correo").text(fecha_es);

  $("#fecha_llamada-2").val(date);
  $("#label-date-llamada").text(fecha_es);

  $("#fecha_reunion-2").val(date);
  $("#label-date-reunion").text(fecha_es);

  $("#fecha_tarea-2").val(date);
  $("#label-date-tarea").text(fecha_es);
  showModalEditar();
}

function addEventButton() {
  Actividades.enable();
  loadCamposModal();
}

function modalNota() {
  $("#addNote").modal("show");
}

$(document).ready(function () {
  $("#opciones-actividades").on("change", function () {
    filter_option = this.value;
    calendar.rerenderEvents();
  });

  $("#opciones-usuario").on("change", function () {
    calendar.removeAllEvents();
    filter_option_all_events = this.value;
    if (filter_option_all_events == 1) {
      var accion = "cargar_eventos_por_usuario";
      $.ajax({
        url: "app/controladores/loadCalendarController.php",
        method: "POST",
        data: {
          accion: accion,
        },
        success: function (data) {
          obj = JSON.parse(data);
          calendar.addEventSource(obj);
        },
        error: function (e) {},
      });
    } else if (filter_option_all_events == 2) {
      var accion = "cargar_eventos_cliente";
      var id = $("#contacto_id").val();
      console.log(id);
      $.ajax({
        url: "app/controladores/loadCalendarController.php",
        method: "POST",
        dataType: "json",
        data: {
          accion: accion,
          id,
        },
        success: function (res) {
          console.log(res);
          calendar.addEventSource(res);
        },
      });
    }
  });
});

function showModalEditar() {
  $("#actividades_fullcalendar").modal("show");
}

function hideModalEditar() {
  $("#actividades_fullcalendar").modal("hide");
}

function actividadTarea() {
  $("#actividadTareas").hide();
}

function actividadReunion() {
  $("#actividadReuniones").hide();
}

function actividadCorreo() {
  $("#actividadCorreos").hide();
}

function actividadLLamada() {
  $("#actividadLlamadas").hide();
}

function initTokenField() {
  $("#invitados").tokenfield();
}

function actvidadUno(evento, info_evento) {
  var fecha_tarea = moment(info_evento.start).format("YYYY-MM-DD");
  $("#fecha").val(fecha_tarea);
  $("#fecha_tarea-2").val(fecha_tarea);
  var fecha_es = crearFecha(fecha_tarea);
  $("#label-date-tarea").text(fecha_es);
  $("#hora_tarea").val(evento.hora_inicio);
  Prioriodades.set(evento.prioriodad);
  if (info_evento.es_todo_dia == 1) {
    $("#tarea_todo_dia").prop("checked", true);
  }
}

function actividadDos(evento, info_evento) {
  $("#invitados").tokenfield("setTokens", evento.participantes);
  buscarEmpleados();
  $("#lugar_reunion").val(evento.lugar);
  localStorage.setItem("lugar", evento.lugar);

  var fecha_tarea = moment(info_evento.start).format("YYYY-MM-DD");
  $("#fecha").val(fecha_tarea);
  $("#fecha_reunion-2").val(fecha_tarea);
  localStorage.setItem("fecha", fecha_tarea);
  var fecha_es = crearFecha(fecha_tarea);
  $("#label-date-reunion").text(fecha_es);

  $("#hora_inicio_reunion").val(evento.hora_inicio);
  localStorage.setItem("hora", evento.hora_inicio);
  $("#hora_final_reunion").val(evento.hora_final);

  if (evento.es_todo_dia == 1) {
    $("#cbox1").prop("checked", true);
  }
}

function buscarEmpleados() {
  var accion = "buscarEmpleados";
  console.log({
    idContacto: $("#contacto_id").val(),
    idEvento: info_evento.id,
  });
  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    data: {
      accion: accion,
      contacto_id: $("#contacto_id").val(),
      actividad_id: info_evento.id,
    },
    method: "POST",
    dataType: "json",
    success: function (response) {
      console.log(response);
      localStorage.setItem("empleados", JSON.stringify(response));
      $.each(response, function (i, e) {
        EmpleadoReunion.set(e.empleado_id);
      });
    },
  });
}

function actividadTres(evento, info_evento) {
  EstatusResultado.set(evento.resultado_llamada);
  var fecha_tarea = moment(info_evento.start).format("YYYY-MM-DD");
  $("#fecha").val(fecha_tarea);
  $("#fecha_llamada-2").val(fecha_es);
  var fecha_es = crearFecha(fecha_tarea);
  $("#label-date-llamada").text(fecha_es);
  $("#hora_llamada").val(evento.hora_inicio);
}

function actividadCuatro(evento, info_evento) {
  var fecha_tarea = moment(info_evento.start).format("YYYY-MM-DD");
  $("#fecha").val(fecha_tarea);
  $("#fecha_correo-2").val(fecha_tarea);
  var fecha_es = crearFecha(fecha_tarea);
  $("#label-date-correo").text(fecha_es);
  $("#hora_correo").val(evento.hora_inicio);
}

function clearAllInputs() {
  $("#titulo").val("");
  $("#lugar_reunion").val("");
}

function checkColors(color) {
  if (color == "#d63d22") {
    $("input[type='radio'][name='color-actividad'][value='#d63d22']").prop(
      "checked",
      true
    );
  } else if (color == "#ea899a") {
    $("input[type='radio'][name='color-actividad'][value='#ea899a']").prop(
      "checked",
      true
    );
  } else if (color == "#de6749") {
    $("input[type='radio'][name='color-actividad'][value='#de6749']").prop(
      "checked",
      true
    );
  } else if (color == "#ffd562") {
    $("input[type='radio'][name='color-actividad'][value='#ffd562']").prop(
      "checked",
      true
    );
  } else if (color == "#287233") {
    $("input[type='radio'][name='color-actividad'][value='#287233']").prop(
      "checked",
      true
    );
  } else if (color == "#2f4538") {
    $("input[type='radio'][name='color-actividad'][value='#2f4538']").prop(
      "checked",
      true
    );
  } else if (color == "#5dc1b9") {
    $("input[type='radio'][name='color-actividad'][value='#5dc1b9']").prop(
      "checked",
      true
    );
  } else if (color == "#6040a0") {
    $("input[type='radio'][name='color-actividad'][value='#6040a0']").prop(
      "checked",
      true
    );
  } else if (color == "#b57edc") {
    $("input[type='radio'][name='color-actividad'][value='#b57edc']").prop(
      "checked",
      true
    );
  } else if (color == "#572364") {
    $("input[type='radio'][name='color-actividad'][value='#572364']").prop(
      "checked",
      true
    );
  } else if (color == "#9d9d97") {
    $("input[type='radio'][name='color-actividad'][value='#9d9d97']").prop(
      "checked",
      true
    );
  }
}

function initEstatusLlamada() {
  EstatusResultado = new SlimSelect({
    select: "#selectEstatusLlamada",
    placeholder: "Selecciona un resultado",
    searchPlaceholder: "Buscar resultado",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function initEmpleadoReunion() {
  EmpleadoReunion = new SlimSelect({
    select: "#empleadoModal",
    placeholder: "Selecciona un empleado",
    searchPlaceholder: "Buscar empleado",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function loadEmpleados() {
  var accion = "cargarEmpleados";

  $.ajax({
    url: "app/controladores/ContactoController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      $("#empleadoModal").empty();
      $("#empleadoModal").append("<option value=''></option>");
      $.each(response, function (key, value) {
        $("#empleadoModal").append(
          "<option value=" +
            value.id +
            ">" +
            value.nombre_completo +
            "</option>"
        );
      });
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function loadEstatusLlamada() {
  var estatus = [
    { id: "1", estatus: "Ocupado" },
    { id: "2", estatus: "Conectado" },
    { id: "3", estatus: "Déjo un mensaje" },
    { id: "4", estatus: "Déjo mensaje de voz" },
    { id: "5", estatus: "Sin respuesta" },
    { id: "6", estatus: "Numero incorrecto" },
  ];
  $("#selectEstatusLlamada").empty();
  $.each(estatus, function (key, value) {
    $("#selectEstatusLlamada").append(
      '<option value="' + value.id + '">' + value.estatus + "</option>"
    );
  });
}

function initContactos() {
  Contactos = new SlimSelect({
    select: "#selectContactos",
    placeholder: "Selecciona un contacto",
    searchPlaceholder: "Buscar contacto",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function loadContactos() {
  var accion = "cargarContactos";

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      response.push({
        placeholder: true,
        text: "Selecciona un contacto / cliente",
      });
      Contactos.setData(response);
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function initPrioridades() {
  Prioriodades = new SlimSelect({
    select: "#selectPrioridadTareas",
    placeholder: "Seleccione una prioridad",
    searchPlaceholder: "Buscar prioriodad",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function loadPrioridades() {
  var prioridades = [
    { id: "1", prioridad: "Alta" },
    { id: "2", prioridad: "Baja" },
    { id: "3", prioridad: "Media" },
  ];
  $("#selectPrioridadTareas").empty();
  $.each(prioridades, function (key, value) {
    $("#selectPrioridadTareas").append(
      '<option value="' + value.id + '">' + value.prioridad + "</option>"
    );
  });
}

function initActividades() {
  Actividades = new SlimSelect({
    select: "#selectActividades",
    placeholder: "Selecciona una actividad",
    searchPlaceholder: "Buscar actividad",
    allowDeselect: false,
    deselectLabel: '<span class="">✖</span>',
  });
}

function loadActividades() {
  var accion = "cargarTipoActividades";
  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    data: {
      accion: accion,
    },
    type: "POST",
    dataType: "json",
    success: function (response) {
      $("#selectActividades").empty();
      $.each(response, function (key, value) {
        $("#selectActividades").append(
          '<option value="' + value.id + '">' + value.actividad + "</option>"
        );
      });
    },
  });
}

function selectActividad(obj) {
  if (obj.value == 1) {
    actividadReunion();
    actividadCorreo();
    actividadLLamada();
    $("#hora_tarea").val(getHour());
    $("#actividadTareas").show();
  } else if (obj.value == 2) {
    actividadTarea();
    actividadCorreo();
    actividadLLamada();
    $("#hora_inicio_reunion").val(getHour());
    $("#hora_final_reunion").val(getHour());
    $("#actividadReuniones").show();
  } else if (obj.value == 3) {
    actividadTarea();
    actividadReunion();
    actividadCorreo();
    $("#hora_llamada").val(getHour());
    $("#actividadLlamadas").show();
  } else if (obj.value == 4) {
    actividadReunion();
    actividadTarea();
    actividadLLamada();
    $("#hora_correo").val(getHour());
    $("#actividadCorreos").show();
  }
}

function crearActividad() {
  var accion = "insertarEvento";
  var title = $("#titulo").val();
  var start = $("#fecha").val();
  var descripcion = $("#descripcion").val();
  var contactoCliente = $("#selectContactos").val();
  var actividad = $("#selectActividades").val();
  var color = $('input[type="radio"][name="color-actividad"]').is(":checked")
    ? $('input[type="radio"][name="color-actividad"]:checked').val()
    : "#5dc1b9";
  var datosActividad = {
    title,
    color,
    start,
    accion,
    contactoCliente,
    actividad,
    descripcion,
  };

  switch (actividad) {
    case 1:
    case "1":
      /* TAREA */
      camposTareaAgregar(datosActividad);
      break;
    case 2:
    case "2":
      /* REUNION */
      camposReunionAgregar(datosActividad);
      break;
    case 3:
    case "3":
      /* LLAMADA */
      camposLlamadaAgregar(datosActividad);
      break;
    case 4:
    case "4":
      /* CORREO */
      camposCorreoAgregar(datosActividad);
    default:
      break;
  }
}

function camposTareaAgregar(datosActividad) {
  var { title, color, start, accion, contactoCliente, actividad, descripcion } =
    datosActividad;
  var tarea_todo_dia = 0;
  var hora_tarea = $("#hora_tarea").val();
  var prioridad = $("#selectPrioridadTareas").val();
  if ($("#tarea_todo_dia").is(":checked")) {
    tarea_todo_dia = 1;
    hora_tarea = "00:00:00";
  }

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      title: title,
      color: color,
      start: start,
      prioridad: prioridad,
      descripcion: descripcion,
      tarea_todo_dia: tarea_todo_dia,
      hora_inicio: hora_tarea,
      accion: accion,
      actividad_id: actividad,
      contactoCliente: contactoCliente,
    },
    success: function (res) {
      console.log(res);
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function camposReunionAgregar(datosActividad) {
  var { title, color, start, accion, contactoCliente, actividad, descripcion } =
    datosActividad;
  var tarea_todo_dia = 0;
  var invitados = $("#invitados").val();
  var integrantes = $("#empleadoModal").val();
  var lugar = $("#lugar_reunion").val();
  var hora_inicio = $("#hora_inicio_reunion").val();
  var hora_final = $("#hora_final_reunion").val();
  var fecha_reunion = $("#fecha_reunion-2").val();

  if ($("#cbox1").is(":checked")) {
    tarea_todo_dia = 1;
    hora_inicio = "00:00:00";
    hora_final = "23:59:59";
  }

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      title: title,
      actividad_id: actividad,
      color: color,
      start: start,
      descripcion: descripcion,
      tarea_todo_dia: tarea_todo_dia,
      hora_inicio: hora_inicio,
      hora_final: hora_final,
      accion: accion,
      lugar: lugar,
      contactoCliente: contactoCliente,
      invitados: invitados,
      integrantes: integrantes,
      fecha_reunion: fecha_reunion,
    },
    cache: false,
    success: function (res) {
      console.log(res);
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function camposLlamadaAgregar(datosActividad) {
  var { title, color, start, accion, contactoCliente, actividad, descripcion } =
    datosActividad;
  var hora_inicio = $("#hora_llamada").val();
  var resultado_llamada = $("#selectEstatusLlamada").val();

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      title: title,
      actividad_id: actividad,
      color: color,
      start: start,
      descripcion: descripcion,
      accion: accion,
      hora_inicio: hora_inicio,
      resultado_llamada: resultado_llamada,
      contactoCliente: contactoCliente,
    },
    success: function (res) {
      console.log(res);
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function camposCorreoAgregar(datosActividad) {
  var { title, color, start, accion, contactoCliente, actividad, descripcion } =
    datosActividad;
  var hora = $("#hora_correo").val();

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      title: title,
      color: color,
      start: start,
      descripcion: descripcion,
      accion: accion,
      actividad_id: actividad,
      hora_inicio: hora,
      contactoCliente: contactoCliente,
    },
    success: function (data) {
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function enviarCorreo(
  title,
  actividad,
  color,
  contacto_id,
  accion,
  json_empleados
) {
  camposReunionAgregarCorreo(
    title,
    actividad,
    color,
    contacto_id,
    accion,
    1,
    json_empleados
  );
  //INFO: YA NO PREGUNTA SI QUIERO ENVIAR EL CORREO Y LOS ENVIA
  /* Swal.fire({
    title: "",
    icon: "success",
    html: "<label>¿Enviar invitaciones por correo a los empleados?</label>",
    width: "600px",
    showCancelButton: true,
    showConfirmButton: true,
    confirmButtonText: "Si",
    cancelButtonText: "No",
    reverseButtons: true,
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
      cancelButton: "btn-custom btn-custom--border-blue",
    },
    buttonsStyling: false,
    allowEnterKey: false,
  }).then((result) => {
    var confirm = result.isConfirmed ? 1 : 0;
    camposReunionAgregarCorreo(
      title,
      actividad,
      color,
      contacto_id,
      accion,
      confirm,
      json_empleados
    );
  }); */
}

function camposReunionAgregarCorreo(
  title,
  actividad,
  color,
  contacto_id,
  accion,
  confirm,
  json_empleados
) {
  var json = $("#invitados").val();
  var fecha = $("#fecha").val();
  var lugar = $("#lugar_reunion").val();
  var hora_inicio = $("#hora_inicio_reunion").val();
  var hora_final = $("#hora_final_reunion").val();
  var fecha_reunion = $("#fecha_reunion-2").val();

  var start = fecha + " " + hora_inicio;
  var end = fecha + " " + hora_final;

  if ($("#cbox1").is(":checked")) {
    var checkbox = 1;
    var hora_tarea_check = "00:00:00";
    start = fecha + " " + hora_tarea_check;
    end = fecha + " " + hora_tarea_check;
    hora_inicio = hora_tarea_check;
    hora_final = hora_tarea_check;
  } else {
    checkbox = 0;
  }

  var tarea_todo_dia = checkbox;
  var descripcion = $("#descripcion").val();

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      title: title,
      actividad_id: actividad,
      color: color,
      start: start,
      end: end,
      descripcion: descripcion,
      tarea_todo_dia: tarea_todo_dia,
      hora_inicio: hora_inicio,
      hora_final: hora_final,
      accion: accion,
      lugar: lugar,
      contacto_id: contacto_id,
      json: json,
      fecha: fecha,
      json_empleados: json_empleados,
      fecha_reunion: fecha_reunion,
      confirm: confirm,
    },
    beforeSend: (_, __) => mostrar(),
    success: function (data) {
      calendar.refetchEvents();
      hideModalEditar();
    },
    complete: (_, __) => ocultar(),
  });
}

function mostrar() {
  $("#loader").fadeIn("slow");
  $("#loader").css("display", "block");
}

function ocultar() {
  $("#loader").fadeOut("slow");
  $("#loader").css("display", "none");
}

$(document).ready(function () {
  $("#empleadoModal").change(function () {
    var dataid = $(this).find(":selected").data("name");
    var optArray = [];
    $.each(dataid, function (value) {
      optArray.push(value);
    });
  });

  $("#addBtn").click(function (e) {
    e.preventDefault();
    //Get the selected Items in the dropdown 1
    var drop2html = "";
    $("#compresult option:selected").each(function () {
      drop2html +=
        '<option value="' + $(this).val() + '">' + $(this).text() + "</option>";
    });

    $("#drop2").html(drop2html);
  });
});

function modificarActividad() {
  var accion = "updateEvento";
  var id = $("#actividad_id").val();
  var title = $("#titulo").val();
  var start = $("#fecha").val();
  var descripcion = $("#descripcion").val();
  var contactoCliente = $("#selectContactos").val();
  var actividad = $("#selectActividades").val();
  var color = $('input[type="radio"][name="color-actividad"]').is(":checked")
    ? $('input[type="radio"][name="color-actividad"]:checked').val()
    : "#5dc1b9";
  var datosActividad = {
    id,
    title,
    color,
    start,
    accion,
    contactoCliente,
    actividad,
    descripcion,
  };

  switch (actividad) {
    case 1:
    case "1":
      /* TAREA */
      camposEditarTarea(datosActividad);
      break;
    case 2:
    case "2":
      /* REUNION */
      camposEditarReunion(datosActividad);
      break;
    case 3:
    case "3":
      /* LLAMADA */
      camposEditarLlamada(datosActividad);
      break;
    case 4:
    case "4":
      /* CORREO */
      camposEditarCorreo(datosActividad);
    default:
      break;
  }
}

function camposEditarTarea(datosActividad) {
  var { id, title, color, start, accion, contactoCliente, actividad, descripcion } =
    datosActividad;
  var tarea_todo_dia = 0;
  var hora_tarea = $("#hora_tarea").val();
  var prioridad = $("#selectPrioridadTareas").val();
  if ($("#tarea_todo_dia").is(":checked")) {
    tarea_todo_dia = 1;
    hora_tarea = "00:00:00";
  }

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      id: id,
      title: title,
      color: color,
      start: start,
      accion: accion,
      prioridad: prioridad,
      hora_inicio: hora_tarea,
      actividad_id: actividad,
      descripcion: descripcion,
      contactoCliente: contactoCliente,
      tarea_todo_dia: tarea_todo_dia,
    },
    success: function (data) {
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function camposEditarReunion(datosActividad) {
  var { id, title, color, start, accion, contactoCliente, actividad, descripcion } =
    datosActividad;
  var tarea_todo_dia = 0;
  var invitados = $("#invitados").val();
  var integrantes = $("#empleadoModal").val();
  var lugar = $("#lugar_reunion").val();
  var hora_inicio = $("#hora_inicio_reunion").val();
  var hora_final = $("#hora_final_reunion").val();
  var fecha_reunion = $("#fecha_reunion-2").val();

  if ($("#cbox1").is(":checked")) {
    tarea_todo_dia = 1;
    hora_inicio = "00:00:00";
    hora_final = "23:59:59";
  }

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      id: id,
      title: title,
      actividad_id: actividad,
      color: color,
      start: start,
      descripcion: descripcion,
      tarea_todo_dia: tarea_todo_dia,
      hora_inicio: hora_inicio,
      hora_final: hora_final,
      accion: accion,
      lugar: lugar,
      contactoCliente: contactoCliente,
      invitados: invitados,
      integrantes: integrantes,
      fecha_reunion: fecha_reunion,
    },
    success: function (res) {
      console.log(res);
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function camposEditarLlamada(datosActividad) {
  var { id, title, color, start, accion, contactoCliente, actividad, descripcion } =
    datosActividad;
  var hora_inicio = $("#hora_llamada").val();
  var resultado_llamada = $("#selectEstatusLlamada").val();

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      title: title,
      actividad_id: actividad,
      color: color,
      start: start,
      descripcion: descripcion,
      accion: accion,
      hora_inicio: hora_inicio,
      resultado_llamada: resultado_llamada,
      contactoCliente: contactoCliente,
      id: id,
    },
    success: function (res) {
      console.log(res);
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function camposEditarCorreo(datosActividad) {
  var { id, title, color, start, accion, contactoCliente, actividad, descripcion } =
    datosActividad;
  var hora = $("#hora_correo").val();

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      id: id,
      title: title,
      color: color,
      start: start,
      descripcion: descripcion,
      accion: accion,
      actividad_id: actividad,
      hora_inicio: hora,
      contactoCliente: contactoCliente,
    },
    success: function (res) {
      console.log(res);
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function checKCambios(color, title, actividad, contacto_id, id) {
  var opc = 0;
  var json_empleados = $("#empleadoModal").val();
  var fecha = $("#fecha").val();
  var hora_inicio = $("#hora_inicio_reunion").val();
  var lugar = $("#lugar_reunion").val();

  let datosReunion = { json_empleados, fecha, hora_inicio, lugar, id };
  let datosActividad = { color, title, actividad, contacto_id, id };

  mensajeEnviarCorreo(datosReunion, datosActividad);
  //camposEditarReunionCorreo(color, title, actividad, contacto_id, id);

  /* $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    method: "POST",
    data: {
      data: array_data,
      accion: "actualizarActividad",
    },
    success: function (data) {
      console.log(data);
      if (data === "") {
        camposEditarReunionCorreo(color, title, actividad, contacto_id, id);
      } else {
        var vector = JSON.parse(data);
        var agregar = 0;
        var actualizar = 0;
        var eliminar = 0;
        $.each(vector, function (ind, elem) {
          if (elem == "agregar") {
            agregar = 1;
          } else if (elem == "actualizar") {
            actualizar = 2;
          } else if (elem == "borrar") {
            eliminar = 3;
          }
        });
        //validar si el campo empleados esta nulo si esta nulo borrar los empleados ligados a esa actividad
        if (agregar == 1 && actualizar == 2) {
          opc = 1;
          console.log("agregar empleado y actualizar reunion");
          array_data.push(opc);
          mensajeEnviarCorreo(array_data);
        } else if (eliminar == 3 && actualizar == 2) {
          opc = 2;
          console.log("actualizar reunion y eliminar empleado");
          array_data.push(opc);
          mensajeEnviarCorreo(array_data);
        } else if (agregar == 1) {
          opc = 3;
          console.log("agregar empleado");
          array_data.push(opc);
          mensajeEnviarCorreo(array_data);
        } else if (actualizar == 2) {
          opc = 4;
          console.log("actualizar correo");
          array_data.push(opc);
          mensajeEnviarCorreo(array_data);
        } else if (eliminar == 3) {
          opc = 5;
          array_data.push(opc);
          console.log("eliminar empleado");
          $.ajax({
            url: "app/controladores/EventoCalendarioController.php",
            method: "POST",
            data: {
              accion: "enviarCorreos",
              data: array_data,
            },
            success: function (data) {},
          });
        }
      }
    },
  }); */
}

function camposEditarReunionCorreo(
  color,
  title,
  actividad,
  contacto_id,
  id,
  confirm
) {
  console.log("si llego");
  //var cambio = checKCambios();
  //console.log(cambio);
  // if (cambio) {

  var json = $("#invitados").val();
  var json_empleados = $("#empleadoModal").val();
  var fecha = $("#fecha").val();
  var fecha_reunion = $("#fecha_reunion-2").val();
  var lugar = $("#lugar_reunion").val();
  var hora_inicio = $("#hora_inicio_reunion").val();
  var hora_final = $("#hora_final_reunion").val();

  var start = fecha + " " + hora_inicio;
  var end = fecha + " " + hora_final;

  if ($("#cbox1").is(":checked")) {
    var checkbox = 1;
    var hora_tarea_check = "00:00:00";
    start = fecha + " " + hora_tarea_check;
    end = fecha + " " + hora_tarea_check;
    hora_inicio = hora_tarea_check;
    hora_final = hora_tarea_check;
  } else {
    checkbox = 0;
  }

  var tarea_todo_dia = checkbox;
  var descripcion = $("#descripcion").val();

  if (json_empleados.length == 0) {
    json_empleados = 0;
  }

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      title: title,
      actividad_id: actividad,
      color: color,
      start: start,
      end: end,
      descripcion: descripcion,
      tarea_todo_dia: tarea_todo_dia,
      hora_inicio: hora_inicio,
      hora_final: hora_final,
      accion: "updateEvento",
      lugar: lugar,
      contacto_id: contacto_id,
      json: json,
      json_empleados: json_empleados,
      confirm: confirm,
      fecha: fecha,
      fecha_reunion: fecha_reunion,
      id: id,
    },
    success: function (data) {
      calendar.refetchEvents();
      hideModalEditar();
    },
  });
  //}
}

function mensajeEnviarCorreo(array_data, datosActividad) {
  //console.log({ array_data });
  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    method: "POST",
    dataType: "json",
    data: {
      accion: "enviarCorreos",
      data: array_data,
    },
    beforeSend: (_, __) => mostrar(),
    success: function (data) {
      console.log(data);
      camposEditarReunionCorreo(
        datosActividad.color,
        datosActividad.title,
        datosActividad.actividad,
        datosActividad.contacto_id,
        datosActividad.id
      );
      //calendar.refetchEvents();
      //hideModalEditar();
    },
    complete: (_, __) => ocultar(),
  });

  /* Swal.fire({
    title: "",
    icon: "success",
    html: "<label>¿Se ha editado la actividad, quieres enviar correos?</label>",
    width: "600px",
    showCancelButton: true,
    showConfirmButton: true,
    confirmButtonText: "Si",
    cancelButtonText: "No",
    reverseButtons: true,
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
      cancelButton: "btn-custom btn-custom--border-blue",
    },
    buttonsStyling: false,
    allowEnterKey: false,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "app/controladores/EventoCalendarioController.php",
        method: "POST",
        data: {
          accion: "enviarCorreos",
          data: array_data,
        },
        beforeSend: (_, __) => mostrar(),
        success: function (data) {
          calendar.refetchEvents();
          hideModalEditar();
        },
        complete: (_, __) => ocultar(),
      });
    } else if (result.isDismissed) {
      camposEditarReunionCorreo();
    }
  }); */
}

function crearFecha(value) {
  var fecha = new Date(value);
  var dias = 1;
  fecha.setDate(fecha.getDate() + dias);
  var options = {
    weekday: "long",
    month: "long",
    day: "numeric",
  };
  var fecha_es = fecha.toLocaleDateString("es-ES", options);
  return fecha_es;
}

function getId() {
  return localStorage.getItem("contacto_id");
}

function sumarUnoFecha(valor) {
  var fecha = new Date(valor);
  var dias = 0; // Número de días a agregar
  var set_fecha = fecha.setDate(fecha.getDate() + dias);
  var nueva_fecha = moment(set_fecha).format("YYYY-MM-DD");
  return nueva_fecha;
}

function getHour() {
  var seconds = new Date().getSeconds();
  var minutes = new Date().getMinutes();
  var hours = new Date().getHours();

  var second = (seconds < 10 ? "0" : "") + seconds;
  var minute = (minutes < 10 ? "0" : "") + minutes;
  var hour = (hours < 10 ? "0" : "") + hours;
  var formatted = hour + ":" + minute + ":" + second;

  return formatted;
}

function editEvent(info) {
  var start, end, title, id, accion;

  start = moment(info.event.start).format("YYYY-MM-DD HH:mm:ss");
  if (info.event.end) {
    end = moment(info.event.end).format("YYYY-MM-DD HH:mm:ss");
  } else {
    end = start;
  }
  title = info.event.title;
  id = info.event.id;
  accion = "updateEventoDropRisize";

  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      start: start,
      end: end,
      title: title,
      id: id,
      accion: accion,
    },
    success: function (data) {
      calendar.refetchEvents();
      hideModalEditar();
    },
  });
}

function deleteEvent(id) {
  var id = $("#actividad_id").val();
  var actividad = $("#selectActividades").val();
  var accion = actividad === "2" ? "eliminarEventoReunion" : "eliminarEvento";
  $.ajax({
    url: "app/controladores/EventoCalendarioController.php",
    type: "POST",
    data: {
      id: id,
      accion: accion,
    },
    success: function (res) {
      console.log(res);
      calendar.refetchEvents();
      hideModalEditar();
    },
    error: function (e) {
      console.log(e);
    },
  });
}
