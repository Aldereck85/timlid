window.addEventListener("load", function () {
  var txtURL = document.querySelector("#txtURL");
  var iconFile = document.querySelector("#iconFile");
  var txtSiglas = document.querySelector("#txtSiglas");
  var txtSeccion = document.querySelector("#txtSeccion");
  var btnEditar = document.querySelector("#btnEditar");
  var btnEliminar = document.querySelector("#btnEliminar");
  var cmbSeccion = document.querySelector("#cmbSeccion");
  var txtPantalla = document.querySelector("#txtPantalla");
  var addSectionBtn = document.querySelector("#agregarSeccion");
  var addPantallaBtn = document.querySelector("#agregarPantalla");
  var dragableSecciones = document.querySelector("#dragable-secciones");
  var cmbPerfilesSeccion = document.querySelector("#cmbPerfilesSeccion");
  var cmbPerfilesPantalla = document.querySelector("#cmbPerfilesPantalla");
  var dragablePantallasSeccion = document.querySelectorAll(
    ".dragable-pantallas-seccion"
  );

  addSectionBtn.addEventListener("click", validarSeccion);

  addPantallaBtn.addEventListener("click", validarPantalla);

  btnEditar.addEventListener("click", validarEditarCampoBD);

  btnEliminar.addEventListener("click", eliminarCampoBD);

  txtSeccion.addEventListener("change", function (e) {
    var elemento = e.target;
    var tipo = e.target.dataset.tipo;
    validarCampoBD(elemento, tipo);
  });

  txtPantalla.addEventListener("change", function (e) {
    var elemento = e.target;
    var tipo = e.target.dataset.tipo;
    validarCampoBD(elemento, tipo);
  });

  txtURL.addEventListener("change", function (e) {
    var elemento = e.target;
    var tipo = e.target.dataset.tipo;
    validarCampoBD(elemento, tipo);
  });

  txtSiglas.addEventListener("change", function (e) {
    var invalidID = "invalid-siglas";
    validarEmptyInput(e, invalidID);
  });

  cmbPerfilesSeccion.addEventListener("change", function (e) {
    var invalidID = "invalid-perfil-seccion";
    validarEmptyInput(e, invalidID);
  });

  iconFile.addEventListener("change", function (e) {
    var invalidID = "invalid-icono";
    validarEmptyInput(e, invalidID);
  });

  cmbSeccion.addEventListener("change", function (e) {
    var invalidID = "invalid-seccion-id";
    validarEmptyInput(e, invalidID);
  });

  cmbPerfilesPantalla.addEventListener("change", function (e) {
    var invalidID = "invalid-perfil-pantalla";
    validarEmptyInput(e, invalidID);
  });

  dragableSecciones.addEventListener("click", function (e) {
    if (e.target.classList.contains("edit-seccion")) {
      openModalEditar(e.target, "seccion");
    } else if (e.target.classList.contains("delete-seccion")) {
      openModalEliminar(e.target, "seccion");
    } else if (e.target.classList.contains("edit-pantalla")) {
      openModalEditar(e.target, "pantalla");
    } else if (e.target.classList.contains("delete-pantalla")) {
      openModalEliminar(e.target, "pantalla");
    }
  });

  var cmbSeccionSlim = new SlimSelect({
    select: "#cmbSeccion",
  });

  var cmbPerfilesSeccionSlim = new SlimSelect({
    select: "#cmbPerfilesSeccion",
    placeholder: "Seleccione los pefiles",
  });

  var cmbPerfilesPantallaSlim = new SlimSelect({
    select: "#cmbPerfilesPantalla",
    placeholder: "Seleccione los perfiles",
  });

  new Sortable(dragableSecciones, {
    animation: 150,
    ghostClass: "blue-background-class",
    onEnd: function () {
      nuevoOrdenSecciones();
    },
  });

  dragablePantallasSeccion.forEach((pantalla) => {
    new Sortable(pantalla, {
      animation: 150,
      ghostClass: "blue-background-class",
      onEnd: function (evt) {
        nuevoOrdenPantallas(evt.item.dataset.seccion);
      },
    });
  });

  /* SECCIONES */
  function validarSeccion() {
    var nombre = $("#txtSeccion").val();
    var siglas = $("#txtSiglas").val();
    var icono = $("#iconFile")[0].files;
    var perfiles = $("#cmbPerfilesSeccion").val();
    if (nombre && siglas && perfiles && icono) {
      agregarSeccion();
    } else {
      if (!nombre) {
        $("#invalid-seccion").css("display", "block");
        $("#txtSeccion").addClass("is-invalid");
      }
      if (!siglas) {
        $("#invalid-siglas").css("display", "block");
        $("#txtSiglas").addClass("is-invalid");
      }
      if (!icono.length) {
        $("#invalid-icono").css("display", "block");
        $("#iconFile").addClass("is-invalid");
      }
      if (!perfiles.length) {
        $("#invalid-perfil-seccion").css("display", "block");
        $("#cmbPerfilesSeccion").addClass("is-invalid");
      }
    }
  }

  function agregarSeccion() {
    var siglas = $("#txtSiglas").val();
    var nombre = $("#txtSeccion").val();
    var icono = $("#iconFile")[0].files;
    var perfiles = $("#cmbPerfilesSeccion").val();
    var form_data = new FormData();
    form_data.append("clase", "save_data");
    form_data.append("funcion", "save_seccion");
    form_data.append("siglas", siglas);
    form_data.append("nombre", nombre);
    form_data.append("icono", icono[0]);
    form_data.append("perfiles", perfiles);
    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      cache: false,
      dataType: "json",
      contentType: false,
      processData: false,
      data: form_data,
      success: function (respuesta) {
        if (respuesta.status === "success") {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡" + respuesta.message + "!",
            sound: "../../../sounds/sound4",
          });
          crearCmbSecciones();
          crearElementoSeccion(respuesta.data);
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: "../../../sounds/sound4",
          });
        }
        cleanInputsSeccion();
      },
      error: function (error) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Error en la peticion!",
          sound: "../../../sounds/sound4",
        });
        cleanInputsSeccion();
      },
    });
  }

  function crearElementoSeccion(seccion) {
    var dragableSecciones = document.querySelector("#dragable-secciones");
    var layout = `
    <div class="card border-bottom" id="seccion-$SECCIONID$" data-seccion="$SECCIONNOMBRE$">
      <div class="card-header seccion-flex" id="btn-$SECCIONID$">
        <span class="linkTable dragable seccion-flex__title" data-toggle="collapse" data-target="#pantallas-seccion-$SECCIONID$" aria-expanded="true" aria-controls="pantallas-seccion-$SECCIONID$">
          $SECCIONNOMBRE$
        </span>
        <span class="seccion-flex__buttons" data-id="$SECCIONID$" data-name="$SECCIONNOMBRE$"><i class="fas fa-edit pointer mr-1 color-primary edit-seccion"></i> <i class="fas fa-trash-alt pointer color-primary delete-seccion"></i></span>
      </div>

      <div id="pantallas-seccion-$SECCIONID$" class="collapse" aria-labelledby="btn-$SECCIONID$" data-parent="#dragable-secciones">
        <div class="card-body dragable-pantallas-seccion" id="dragable-pantallas-seccion-$SECCIONID$">
        </div>
      </div>
    </div>`;
    layout = layout.replaceAll("$SECCIONID$", seccion.idSeccion);
    layout = layout.replaceAll("$SECCIONNOMBRE$", seccion.seccion);
    dragableSecciones.insertAdjacentHTML("beforeend", layout);
  }

  function nuevoOrdenSecciones() {
    var dragableSecciones = document.querySelector("#dragable-secciones");
    var nuevoOrden = [];
    for (var i = 0; i < dragableSecciones.children.length; i++) {
      var seccion = dragableSecciones.children[i].dataset.seccion;
      nuevoOrden.push({
        seccion,
        orden: i + 1,
      });
    }
    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: {
        clase: "edit_data",
        funcion: "edit_orden_secciones",
        nuevoOrden,
      },
      success: function (respuesta) {
        if (respuesta.status !== "success") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: "../../../sounds/sound4",
          });
        }
      },
      error: function (error) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: "../../../sounds/sound4",
        });
      },
    });
  }

  function crearCmbSecciones() {
    var initialData = [{ placeholder: true, text: "Seleccione una sección" }];
    cmbSeccionSlim.setData([]);

    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: { clase: "get_data", funcion: "get_secciones" },
      success: function (respuesta) {
        cmbSeccionSlim.setData(initialData.concat(respuesta.data));
      },
      error: function (error) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡No pudimos cargar las secciones por favor actualiza la página :(!",
          sound: "../../../sounds/sound4",
        });
      },
    });
  }

  /* PANTALLAS */
  function validarPantalla() {
    var nombre = $("#txtPantalla").val();
    var url = $("#txtURL").val();
    var seccion = $("#cmbSeccion").val();
    var perfiles = $("#cmbPerfilesPantalla").val();
    if (nombre && url && seccion != "undefined" && perfiles) {
      agregarPantalla();
    } else {
      if (!nombre) {
        $("#invalid-pantalla").css("display", "block");
        $("#txtPantalla").addClass("is-invalid");
      }
      if (!url) {
        $("#invalid-url").css("display", "block");
        $("#txtURL").addClass("is-invalid");
      }
      if (!seccion || seccion == "undefined") {
        $("#invalid-seccion-id").css("display", "block");
        $("#cmbSeccion").addClass("is-invalid");
      }
      if (!perfiles.length) {
        $("#invalid-perfil-pantalla").css("display", "block");
        $("#cmbPerfilesPantalla").addClass("is-invalid");
      }
    }
  }

  function agregarPantalla() {
    var nombre = $("#txtPantalla").val();
    var url = $("#txtURL").val();
    var seccion = $("#cmbSeccion").val();
    var perfiles = $("#cmbPerfilesPantalla").val();
    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: {
        clase: "save_data",
        funcion: "save_pantalla",
        nombre,
        url,
        seccion,
        perfiles,
      },
      success: function (respuesta) {
        if (respuesta.status === "success") {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡" + respuesta.message + "!",
            sound: "../../../sounds/sound4",
          });
          crearCmbSecciones();
          crearElementoPantalla(respuesta.data);
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: "../../../sounds/sound4",
          });
        }
        cleanInputsPantalla();
      },
      error: function (error) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: "../../../sounds/sound4",
        });
        cleanInputsPantalla();
      },
    });
  }

  function crearElementoPantalla(pantalla) {
    var seccionContenedor = document.querySelector(
      "#dragable-pantallas-seccion-" + pantalla.idSeccion
    );
    var layout = `
    <div id="pantalla-$PANTALLAID$" class="alert alert-primary linkTable dragable pantalla-flex" data-pantalla="$PANTALLAID$" data-seccion="$SECCIONID$">
      <span class="pantalla-flex__title">$PANTALLANOMBRE$</span>
      <span class="pantalla-flex__buttons" data-id="$PANTALLAID$" data-name="$PANTALLANOMBRE$"><i class="fas fa-edit pointer mr-1 color-primary edit-pantalla"></i> <i class="fas fa-trash-alt pointer color-primary delete-pantalla"></i></span>
    </div>`;
    layout = layout.replace("$PANTALLAID$", pantalla.idPantalla);
    layout = layout.replace("$PANTALLANOMBRE$", pantalla.pantalla);
    layout = layout.replace("$SECCIONID$", pantalla.idSeccion);
    seccionContenedor.insertAdjacentHTML("beforeend", layout);
  }

  function nuevoOrdenPantallas(seccion) {
    var dragablePantallas = document.querySelector(
      "#dragable-pantallas-seccion-" + seccion
    );
    var nuevoOrden = [];
    for (var i = 0; i < dragablePantallas.children.length; i++) {
      var pantalla = dragablePantallas.children[i].dataset.pantalla;
      nuevoOrden.push({
        pantalla,
        orden: i + 1,
      });
    }
    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: {
        clase: "edit_data",
        funcion: "edit_orden_pantallas",
        seccion,
        nuevoOrden,
      },
      success: function (respuesta) {
        if (respuesta.status !== "success") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: "../../../sounds/sound4",
          });
        }
      },
      error: function (error) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: "../../../sounds/sound4",
        });
      },
    });
  }

  /* UTILITIES */
  function validarCampoBD(elemento, tipo) {
    var valor = elemento.value;
    var invalidDiv = elemento.nextElementSibling;
    if (!valor) {
      invalidDiv.style.display = "block";
      invalidDiv.textContent = "La sección debe tener un nombre.";
      elemento.classList.add("is-invalid");
      return;
    }
    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: {
        clase: "get_data",
        funcion: "validate_seccion",
        valor,
        tipo,
      },
      success: function (respuesta) {
        if (respuesta.status === "success") {
          if (!respuesta.existe) {
            invalidDiv.style.display = "none";
            invalidDiv.textContent = "La sección debe tener un nombre.";
            elemento.classList.remove("is-invalid");
          } else {
            invalidDiv.style.display = "block";
            invalidDiv.textContent = "La sección debe tener un nombre unico.";
            elemento.classList.add("is-invalid");
          }
        } else {
          invalidDiv.style.display = "block";
          invalidDiv.textContent = "Algo salio mal.";
          elemento.classList.add("is-invalid");
        }
      },
      error: function (error) {
        invalidDiv.style.display = "block";
        invalidDiv.textContent = "Algo salio mal.";
        elemento.classList.add("is-invalid");
      },
    });
  }

  function validarEditarCampoBD() {
    var input = document.getElementById("txtEditar");
    var id = document.getElementById("hiddenIdEditar").value;
    var tipo = input.dataset.tipo;
    var valor = input.value;
    var invalidDiv = input.nextElementSibling;

    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: {
        clase: "edit_data",
        funcion: "validar_editar",
        id,
        valor,
        tipo,
      },
      success: function (respuesta) {
        if (respuesta.status === "success") {
          invalidDiv.style.display = "none";
          input.classList.remove("is-invalid");
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡" + respuesta.message + "!",
            sound: "../../../sounds/sound4",
          });
          var htmlElementString = "#" + tipo +  "-" + id + " ." + tipo + "-flex__title";
          var htmlElement = document.querySelector(htmlElementString);
          htmlElement.textContent = valor;
          cleanAndCloseEditModal();
        } else {
          invalidDiv.style.display = "block";
          invalidDiv.textContent = respuesta.message;
          input.classList.add("is-invalid");
        }
      },
      error: function (error) {
        invalidDiv.style.display = "block";
        invalidDiv.textContent = "Algo salio mal.";
        input.classList.add("is-invalid");
      },
    });
  }

  function eliminarCampoBD() {
    var input = document.getElementById("hiddenIdEliminar");
    var tipo = input.dataset.tipo;
    var id = input.value;
    $.ajax({
      url: "php/funciones.php",
      type: "POST",
      dataType: "json",
      data: {
        clase: "delete_data",
        funcion: "eliminar_pantalla_seccion",
        id,
        tipo,
      },
      success: function (respuesta) {
        if (respuesta.status === "success") {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "¡" + respuesta.message + "!",
            sound: "../../../sounds/sound4",
          });
          var itemToRemove = document.getElementById(tipo + "-" + id);
          itemToRemove.remove();
          cleanAndCloseDeleteModal();
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡" + respuesta.message + "!",
            sound: "../../../sounds/sound4",
          });
        }
      },
      error: function (error) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal!",
          sound: "../../../sounds/sound4",
        });
      },
    });
  }

  function validarEmptyInput(e, invalidID) {
    var valor = $("#" + e.target.id).val();
    var elemento = $("#" + e.target.id);
    var invalidDiv = $("#" + invalidID);
    if (!valor || !valor.length) {
      invalidDiv.css("display", "block");
      elemento.addClass("is-invalid");
    } else {
      invalidDiv.css("display", "none");
      elemento.removeClass("is-invalid");
    }
  }

  function cleanInputsSeccion() {
    document.getElementById("txtSeccion").value = "";
    document.getElementById("txtSiglas").value = "";
    document.getElementById("iconFile").value = "";
    cmbPerfilesSeccionSlim.set([]);
  }

  function cleanInputsPantalla() {
    document.getElementById("txtPantalla").value = "";
    document.getElementById("txtURL").value = "";
    cmbSeccionSlim.set("");
    cmbPerfilesPantallaSlim.set([]);
  }

  /* MODALES */
  function openModalEditar(item, tipo) {
    console.log(item.parentElement);
    var hiddenID = document.getElementById("hiddenIdEditar");
    var hiddenName = document.getElementById("hiddenNameEditar");
    var txtEditar = document.getElementById("txtEditar");
    var titulo = document.getElementById("modalLabelEditar");
    var label = document.getElementById("label-name");
    var invalid = document.getElementById("invalid-editar-modal");

    var id = item.parentElement.dataset.id;
    var name = item.parentElement.dataset.name;

    titulo.textContent =
      tipo === "seccion" ? "Editar sección" : "Editar pantalla";
    label.textContent = tipo === "seccion" ? "Sección" : "Pantalla";
    invalid.textContent =
      tipo === "seccion"
        ? "La sección debe tener un nombre."
        : "La pantalla debe tener un nombre.";
    hiddenID.value = id;
    hiddenName.value = name;
    txtEditar.value = name;
    txtEditar.dataset.tipo = tipo;

    $("#modal-editar").modal("show");
  }

  function openModalEliminar(item, tipo) {
    var hiddenID = document.getElementById("hiddenIdEliminar");
    var leyenda = document.getElementById("leyenda-eliminar");
    var titulo = document.getElementById("modalLabelEliminar");

    var id = item.parentElement.dataset.id;
    var name = item.parentElement.dataset.name;

    titulo.textContent =
      tipo === "seccion" ? "Eliminar sección" : "Eliminar pantalla";
    leyenda.textContent =
      tipo === "seccion"
        ? 'Se eliminará la sección con los siguientes datos: "' + name + '"'
        : 'Se  eliminará la pantalla con los siguientes datos: "' + name + '"';
    hiddenID.value = id;
    hiddenID.dataset.tipo = tipo;

    $("#modal-eliminar").modal("show");
  }

  function cleanAndCloseEditModal() {
    var hiddenID = document.getElementById("hiddenIdEditar");
    var hiddenName = document.getElementById("hiddenNameEditar");
    var txtEditar = document.getElementById("txtEditar");
    var titulo = document.getElementById("modalLabelEditar");
    var label = document.getElementById("label-name");
    var invalid = document.getElementById("invalid-editar-modal");

    titulo.textContent = "";
    label.textContent = "";
    invalid.textContent = "";
    hiddenID.value = "";
    hiddenName.value = "";
    txtEditar.value = "";
    txtEditar.dataset.tipo = "";

    $("#modal-editar").modal("hide");
  }

  function cleanAndCloseDeleteModal() {
    var hidenID = document.getElementById("hiddenIdEliminar");
    var leyenda = document.getElementById("leyenda-eliminar");
    var titulo = document.getElementById("modalLabelEliminar");

    leyenda.textContent = "";
    titulo.textContent = "";
    hidenID.value = "";
    hidenID.dataset.tipo = "";

    $("#modal-eliminar").modal("hide");
  }

  /* INITIALIZATIONS */
  crearCmbSecciones();
});
