var btnAgregarTarea = document.getElementById("btnAgregarTarea");
var btnEditarTarea = document.getElementById("btnEditarTarea");
var inputTitulo = document.getElementById("input-titulo");
var inputDescripcion = document.getElementById("input-descripcion");
var inputFecha = document.getElementById("input-fecha");
var inputIdTarea = document.getElementById("input-id-tarea");
var inputBuscar = document.getElementById("input-buscar");
var btnVerFiltros = document.getElementById("btnVerFiltros");
var checkRecurrencia = document.getElementById("check-recurrencia");
var statusSlim = new SlimSelect({ select: "#input-status" });
var recurrenciaSlim = new SlimSelect({ select: "#input-recurrencia" });
var contenedorRecurrente = document.getElementById("contenedor-recurrente");

function eventoRecurrenciaSlim() {
  if (checkRecurrencia.checked) {
    contenedorRecurrente.classList.remove("d-none");
  } else {
    contenedorRecurrente.classList.add("d-none");
  }
}

function mostrarTareas(filtros = {}) {
  /* FILTROS Y BUSQUEDA */
  var terminoBusqueda = inputBuscar.value;
  var tareasContainer = document.getElementById("tareas");
  var tareaElement = `
      <div class="nota-tarea nota-tarea--{{STATUS}}  shadow p-3" id="tarea-{{ID}}">
        <div class="nota-tarea__header mb-2">
          <strong class="nota-tarea__titulo">{{TITULO}}</strong>
          <span class="d-flex">
            <i class="fas fa-edit pointer mr-1" onClick="mostrarTarea({{ID}})"></i>
            <i class="fas fa-trash-alt pointer" onClick="eliminarTarea({{ID}})"></i>
          </span>
        </div>
        <div class="nota-tarea__body mb-2">
          {{DESCRIPCION}}
        </div>
        <div class="nota-tarea__footer">
          <span class="text-muted nota-tarea__fecha">{{FECHA}}</span>
          <i class="fas fa-check pointer" onClick="finalizarTarea({{ID}})"></i>
        </div>
      </div>`;

  $.ajax({
    url: "php/funciones.php",
    type: "POST",
    dataType: "json",
    data: {
      clase: "get_data",
      funcion: "get_tareas",
      terminoBusqueda,
      filtros,
    },
    success: function (res) {
      tareasContainer.innerHTML = "";
      if (res.status === "success") {
        res.data.forEach((element) => {
          var fechas = setFechas(element.fecha_tarea);
          var tarea = tareaElement;
          tarea = tarea.replace("{{DESCRIPCION}}", element.descripcion);
          tarea = tarea.replace("{{STATUS}}", element.status);
          tarea = tarea.replace("{{FECHA}}", fechas.fechaMostrar);
          tarea = tarea.replaceAll("{{ID}}", element.id);
          if (element.recurrencia) {
            tarea = tarea.replace(
              "{{TITULO}}",
              element.titulo + " - Recurrente"
            );
          } else {
            tarea = tarea.replace("{{TITULO}}", element.titulo);
          }
          tareasContainer.insertAdjacentHTML("beforeend", tarea);
        });
        return;
      }
      callAlert("error", "No se cargaron las tareas correctamente");
    },
    error: function (e) {
      callAlert("error", "No se cargaron las tareas correctamente");
    },
  });
}

function mostrarTarea(id) {
  $.ajax({
    url: "php/funciones.php",
    type: "POST",
    dataType: "json",
    data: {
      clase: "get_data",
      funcion: "get_tarea",
      id,
    },
    success: function (res) {
      if (res.status === "success") {
        $("#modalAddTarea").modal("show");
        btnAgregarTarea.classList.add("d-none");
        btnEditarTarea.classList.remove("d-none");
        inputTitulo.value = res.data.titulo;
        inputDescripcion.value = res.data.descripcion;
        inputIdTarea.value = res.data.id;
        statusSlim.set(res.data.status);
        if (res.data.fecha_tarea) {
          inputFecha.value = res.data.fecha_tarea;
        }
        if (res.data.recurrencia) {
          checkRecurrencia.checked = true;
          recurrenciaSlim.set(res.data.frecuencia);
          contenedorRecurrente.classList.remove("d-none");
        }
        return;
      }
      callAlert("error", "No se cargo la tarea correctamente");
    },
    error: function (e) {
      callAlert("error", "No se cargo la tarea correctamente");
    },
  });
}

function addTarea() {
  var titulo = inputTitulo.value;
  var descripcion = inputDescripcion.value ? inputDescripcion.value : '';
  var fecha = inputFecha.value;
  var status = statusSlim.selected() ? statusSlim.selected() : "todo";
  var recurrencia = checkRecurrencia.checked ? 1 : 0;
  var frecuencia = checkRecurrencia.checked ? recurrenciaSlim.selected() : null;
  var tarea = { titulo, descripcion, fecha, status, recurrencia, frecuencia };
  var validForm = true;

  inputTitulo.classList.remove("is-invalid");
  $("#invalid-titulo").removeClass("d-block");
  inputDescripcion.classList.remove("is-invalid");
  $("#invalid-descripcion").removeClass("d-block");
  inputFecha.classList.remove("is-invalid");
  $("#invalid-fecha").removeClass("d-block");

  if (!titulo) {
    inputTitulo.classList.add("is-invalid");
    $("#invalid-titulo").addClass("d-block");
    validForm = false;
  }
  if (checkRecurrencia.checked && !fecha) {
    inputFecha.classList.add("is-invalid");
    $("#invalid-fecha").addClass("d-block");
    validForm = false;
  }

  if (validForm) {
    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: {
        clase: "save_data",
        funcion: "save_tarea",
        tarea,
      },
      success: function (res) {
        if (res.status === "success") {
          $("#modalAddTarea").modal("hide");
          mostrarTareas({
            orden: [],
            mostrar: ["todo", "delayed"],
            rangos: [0, 0],
          });
          callAlert("success", "Se añadio la tarea correctamente");
          return;
        }
        callAlert("error", "No se añadio la tarea correctamente");
      },
      error: function (e) {
        callAlert("error", "No se añadio la tarea correctamente 2");
      },
    });
  }
}

function editarTarea() {
  var idTarea = inputIdTarea.value;
  var titulo = inputTitulo.value;
  var descripcion = inputDescripcion.value;
  var fecha = inputFecha.value;
  var status = statusSlim.selected() ? statusSlim.selected() : "todo";
  var recurrencia = checkRecurrencia.checked ? 1 : 0;
  var frecuencia = checkRecurrencia.checked ? recurrenciaSlim.selected() : null;
  var validForm = true;
  var tarea = {
    id: idTarea,
    titulo,
    descripcion,
    fecha,
    status,
    recurrencia,
    frecuencia,
  };

  inputTitulo.classList.remove("is-invalid");
  $("#invalid-titulo").removeClass("d-block");
  inputDescripcion.classList.remove("is-invalid");
  $("#invalid-descripcion").removeClass("d-block");
  inputFecha.classList.remove("is-invalid");
  $("#invalid-fecha").removeClass("d-block");

  if (!titulo) {
    inputTitulo.classList.add("is-invalid");
    $("#invalid-titulo").addClass("d-block");
    validForm = false;
  }
  if (!descripcion) {
    inputDescripcion.classList.add("is-invalid");
    $("#invalid-descripcion").addClass("d-block");
    validForm = false;
  }
  if (checkRecurrencia.checked && !fecha) {
    inputFecha.classList.add("is-invalid");
    $("#invalid-fecha").addClass("d-block");
    validForm = false;
  }

  if (validForm) {
    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: {
        clase: "edit_data",
        funcion: "edit_tarea",
        tarea,
      },
      success: function (res) {
        if (res.status === "success") {
          $("#modalAddTarea").modal("hide");
          callAlert("success", "Se edito la tarea correctamente");
          var filtros = crearFiltros();
          mostrarTareas(filtros);
          return;
        }
        callAlert("error", "No se edito la tarea correctamente");
      },
      error: function (e) {
        callAlert("error", "No se edito la tarea correctamente");
      },
    });
  }
}

function eliminarTarea(id) {
  Swal.fire({
    title: "¿Quieres eliminar la tarea?",
    showDenyButton: true,
    buttonsStyling: false,
    customClass: {
      actions: "",
      confirmButton: "btn-custom btn-custom--blue mr-2",
      denyButton: "btn-custom btn-custom--border-blue",
    },
    confirmButtonText: "Eliminar",
    denyButtonText: `Cancelar`,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "php/funciones.php",
        type: "POST",
        dataType: "json",
        data: {
          clase: "delete_data",
          funcion: "delete_tarea",
          id,
        },
        success: function (res) {
          if (res.status === "success") {
            var tarea = document.getElementById("tarea-" + id);
            tarea.remove();
            callAlert("success", "Se elimino la tarea correctamente");
            return;
          }
          callAlert("error", "No se elimino la tarea correctamente");
        },
        error: function (e) {
          callAlert("error", "No se elimino la tarea correctamente");
        },
      });
    }
  });
}

function finalizarTarea(id) {
  Swal.fire({
    title: "¿Quieres finalizar la tarea?",
    showDenyButton: true,
    buttonsStyling: false,
    customClass: {
      actions: "",
      confirmButton: "btn-custom btn-custom--blue mr-2",
      denyButton: "btn-custom btn-custom--border-blue",
    },
    confirmButtonText: "Finalizar",
    denyButtonText: `Cancelar`,
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "php/funciones.php",
        type: "POST",
        dataType: "json",
        data: {
          clase: "edit_data",
          funcion: "finalizar_tarea",
          id,
        },
        success: function (res) {
          if (res.status === "success") {
            callAlert("success", "Se finalizo la tarea correctamente");
            var filtros = crearFiltros();
            mostrarTareas(filtros);
            return;
          }
          callAlert("error", "No se finalizo la tarea correctamente");
        },
        error: function (e) {
          callAlert("error", "No se finalizo la tarea correctamente");
        },
      });
    }
  });
}

function limpiarModal() {
  btnAgregarTarea.classList.remove("d-none");
  btnEditarTarea.classList.add("d-none");
  inputTitulo.value = "";
  inputDescripcion.value = "";
  inputFecha.value = "";
  inputIdTarea.value = "";
  statusSlim.set("todo");
  checkRecurrencia.checked = false;
  contenedorRecurrente.classList.add("d-none");
  inputTitulo.classList.remove("is-invalid");
  $("#invalid-titulo").removeClass("d-block");
  inputDescripcion.classList.remove("is-invalid");
  $("#invalid-descripcion").removeClass("d-block");
  inputFecha.classList.remove("is-invalid");
  $("#invalid-fecha").removeClass("d-block");
}

function callAlert(tipo, mensaje) {
  var img = tipo === "success" ? "checkmark.svg" : "warning_circle.svg";
  Lobibox.notify(tipo, {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: true,
    img: "../../../img/timdesk/" + img,
    msg: "¡" + mensaje + "!",
  });
}

function filtroSelect(e) {
  if (e.tagName === "DIV") {
    e.classList.contains("checked-type-column")
      ? e.classList.remove("checked-type-column")
      : e.classList.add("checked-type-column");
  }
  var filtros = crearFiltros();
  mostrarTareas(filtros);
}

function crearFiltros() {
  var filtros = { orden: [], mostrar: [], rangos: [] };
  var elementosfitro = document.getElementsByClassName("filtro");
  for (let index = 0; index < elementosfitro.length; index++) {
    var element = elementosfitro[index];
    if (element.classList.contains("checked-type-column")) {
      filtros[element.dataset.tipo].push(element.dataset.valor);
    }
    if (element.classList.contains("input-filtro")) {
      element.value
        ? (filtros.rangos[element.dataset.index] = element.value)
        : (filtros.rangos[element.dataset.index] = 0);
    }
  }
  return filtros.mostrar.length
    ? filtros
    : { ...filtros, mostrar: ["todo", "delayed"] };
}

function setFechas(fecha) {
  var fechaArray = fecha ? fecha.split("-") : "";
  var fechaString = [fechaArray[1], fechaArray[2], fechaArray[0]];
  var fechaMostrar = fechaArray ? fechaArray.reverse().join("/") : "";
  var fechaObj = fechaArray ? new Date(fechaString) : "";
  var fechaHoy = new Date();

  return { fechaMostrar, fechaObj, fechaHoy };
}

function setStatusColor(fechas) {
  var color = "color-green";
  if (fechas.fechaObj < fechas.fechaHoy) {
    color = "color-red";
  } else {
    var dias = Math.ceil(
      (fechas.fechaObj.getTime() - fechas.fechaHoy.getTime()) /
        (1000 * 3600 * 24)
    );
    if (dias < 5) {
      color = "color-orange";
    }
  }
  return color;
}

function checkAndUpdateRecurrecias() {
  $.ajax({
    url: "php/funciones.php",
    type: "POST",
    dataType: "json",
    data: {
      clase: "get_data",
      funcion: "check_concurrencias",
    },
    success: function (res) {
      console.log(res);
    },
    error: function (e) {
      console.log(e);
    },
  });
}

if (checkRecurrencia) {
  checkRecurrencia.addEventListener("change", eventoRecurrenciaSlim);
}

if (btnAgregarTarea) {
  btnAgregarTarea.addEventListener("click", addTarea);
}

if (btnEditarTarea) {
  btnEditarTarea.addEventListener("click", editarTarea);
}

if (inputBuscar) {
  inputBuscar.addEventListener("keyup", () => {
    var filtros = crearFiltros();
    mostrarTareas(filtros);
  });
}

if (btnVerFiltros) {
  window.addEventListener("click", function (e) {
    if (
      e.target.classList.contains("btn-tareas-simples") ||
      e.target.classList.contains("filtro") ||
      e.target.classList.contains("input-filtro")
    ) {
      document.getElementById("listaColumnas").classList.remove("d-none");
      return;
    }
    document.getElementById("listaColumnas").classList.add("d-none");
  });
}

$("#modalAddTarea").on("hide.bs.modal", function (event) {
  limpiarModal();
});

mostrarTareas({ orden: [], mostrar: ["todo", "delayed"], rangos: [0, 0] });

checkAndUpdateRecurrecias();
