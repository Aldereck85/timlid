$(document).ready(function () {
  loadAlertsNoti(
    "alertaTareas",
    $("#txtUsuario").val(),
    $("#txtRuta").val(),
    $("#txtEdit").val()
  );
  setInterval(
    loadAlertsNoti,
    10000,
    "alertaTareas",
    $("#txtUsuario").val(),
    $("#txtRuta").val(),
    $("#txtEdit").val()
  );

  $("#tblEquipos").dataTable({
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
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
          className: "btn-custom--white-dark",
          action: function () {
            $("#agregar_Equipo").modal("show");
          },
        },
        {
          extend: "excelHtml5",
          text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
          className: "btn-custom--white-dark",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_teamsTable" },
    },
    columns: [
      { data: "Equipo", },
      { data: "Acciones", width: "15%" },
    ],
  });

  function setFormatDatatables() {
    var idioma_espanol = {
      sProcessing: "Procesando...",
      sZeroRecords: "No se encontraron resultados",
      sEmptyTable: "Ningún dato disponible en esta tabla",
      sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
      sLoadingRecords: "Cargando...",
      searchPlaceholder: "Buscar...",
      oPaginate: {
        sFirst: "Primero",
        sLast: "Último",
        sNext: "<i class='fas fa-chevron-right'></i>",
        sPrevious: "<i class='fas fa-chevron-left'></i>",
      },
    };
    return idioma_espanol;
  }

  $("#btnEliminarEquipo2").click(function () {
    var data = "idEquipoD=" + $("#idEquipoU").val();
    $.ajax({
      type: "POST",
      url: "functions/eliminar_Equipo.php",
      data: data,
      success: function (r) {
        window.location.href = "index.php";
      },
    });
  });

  $(document).on("click", "#btnEditarEquipo", function () {
    $("#editar_Equipo").modal("toggle");
  });

  $(document).on("click", "#btnAgregar_Equipo", function () {
    $("#agregar_Equipo").modal("toggle");
  });

  $(document).on("click", "#btnAgregar", function () {
    let nombre_equipo = $("#txtEquipo").val().trim();

    let equipos = $("#multiple").val();

    $("#btnAgregar").prop("disabled", true);
    $("#btnCancelar").prop("disabled", true);

    if (nombre_equipo == "") {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "El nombre del equipo es obligatorio.",
      });
      $("#btnAgregar").prop("disabled", false);
      $("#btnCancelar").prop("disabled", false);
      return;
    }

    if (equipos == "") {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "Necesitas seleccionar al menos un empleado.",
      });
      $("#btnAgregar").prop("disabled", false);
      $("#btnCancelar").prop("disabled", false);
      return;
    }

    $.ajax({
      type: "POST",
      url: "functions/agregar_Equipo.php",
      data: $("#form-equipos").serialize(),
      success: function (r) {
        if (r == "exito") {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "Equipo agregado.",
          });
          $("#btnAgregar").prop("disabled", false);
          $("#btnCancelar").prop("disabled", false);
          $("#tblEquipos").DataTable().ajax.reload();
          $("#agregar_Equipo").modal("hide");
        }

        if (r == "fallo") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Ocurrio un error, intentalo nuevamente!",
          });
        }
        $("#btnAgregar").prop("disabled", false);
        $("#btnCancelar").prop("disabled", false);
      },
      error: function () {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Ocurrio un error, intentalo nuevamente!",
        });
        $("#btnAgregar").prop("disabled", false);
        $("#btnCancelar").prop("disabled", false);
      },
    });
  });

  $(document).on("click", "#btnEditar", function () {
    let nombre_equipo = $("#txtEquipoU").val().trim();

    let equipos = $("#multipleU").val();

    $("#btnEditar").prop("disabled", true);
    $("#btnCancelarEditar").prop("disabled", true);

    if (nombre_equipo == "") {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "El nombre del equipo es obligatorio.",
      });
      $("#btnAgregar").prop("disabled", false);
      $("#btnCancelarEditar").prop("disabled", false);
      return;
    }

    if (equipos == "") {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/notificacion_error.svg",
        msg: "Necesitas seleccionar al menos un empleado.",
      });
      $("#btnEditar").prop("disabled", false);
      $("#btnCancelarEditar").prop("disabled", false);
      return;
    }

    $.ajax({
      type: "POST",
      url: "functions/editar_Equipo.php",
      data: $("#editarEquipo").serialize(),
      success: function (r) {
        if (r == "exito") {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/checkmark.svg",
            msg: "Equipo agregado.",
          });
          $("#btnEditar").prop("disabled", false);
          $("#btnCancelarEditar").prop("disabled", false);
          $("#tblEquipos").DataTable().ajax.reload();
          $("#editar_Equipo").modal("hide");
        }

        if (r == "fallo") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "¡Ocurrio un error, intentalo nuevamente!",
          });
        }
        $("#btnEditar").prop("disabled", false);
        $("#btnCancelarEditar").prop("disabled", false);
      },
      error: function () {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/notificacion_error.svg",
          msg: "¡Ocurrio un error, intentalo nuevamente!",
        });
        $("#btnEditar").prop("disabled", false);
        $("#btnCancelarEditar").prop("disabled", false);
      },
    });
  });

  $(document).on("click", "#btnEliminarEquipo", function () {
    let token = $("#token_4s45us").val().trim();

    let id_equipo = $("#idEquipoU").val();

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--border-blue",
        cancelButton: "btn-custom btn-custom--blue",
      },
      buttonsStyling: false,
    });

    swalWithBootstrapButtons
      .fire({
        title: "¿Desea continuar?",
        text: "Se eliminará el equipo",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText:
          '<span class="verticalCenter">Eliminar equipo</span>',
        cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {
          $("#btnEliminarEquipo").prop("disabled", true);

          $.ajax({
            type: "POST",
            url: "functions/eliminar_Equipo.php",
            data: {
              token_4s45us: token,
              idEquipo: id_equipo,
            },
            success: function (data) {
              if (data == "exito") {
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top", //or 'center bottom'
                  icon: false,
                  img: "../../img/timdesk/checkmark.svg",
                  msg: "Se ha eliminado el equipo",
                });

                $("#btnEliminarEquipo").prop("disabled", false);
                $("#editar_Equipo").modal("hide");
                $("#tblEquipos").DataTable().ajax.reload();
              }
              if (data == "fallo") {
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top", //or 'center bottom'
                  icon: false,
                  img: "../../img/timdesk/warning_circle.svg",
                  msg: "Ocurrio un error, vuelva intentarlo.",
                });

                $("#btnEliminarEquipo").prop("disabled", false);
              }
            },
            error: function () {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });
              $("#btnEliminarEquipo").prop("disabled", false);
            },
          });
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
        }
      });
  });

  function obtenerIdEquipoEliminar(id) {
    document.getElementById("idEquipoD").value = id;
  }

  var selectEquipo = new SlimSelect({
    select: "#multiple",
    placeholder: "Seleccione miembros para el equipo",
  });

  var selectEquipoEdit = new SlimSelect({
    select: "#multipleU",
    placeholder: "Seleccione miembros para el equipo",
  });
});

function obtenerIdEquipoEditar(id) {
  $("#editar_Equipo").modal("toggle");
  $("#idEquipoU").val(id);
  $("#idEquipoD").val(id);

  $("#btnEditar").prop("disabled", true);
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_teamsEdit", value: id },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      $("#txtEquipoU").val(respuesta[0].Nombre_Equipo);
    },
    error: function (error) {
      console.log(error);
    },
  });

  loadComboMulti("usuario", id);
  $("#btnEditar").prop("disabled", false);
}

function loadComboMulti(name, value) {
  let html;
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "get_userComboEdit", idEquipo: value },
    dataType: "json",
    success: function (respuesta) {
      $("#multipleU").html(respuesta);
    },
    error: function (error) {
      console.log(error);
    },
  });
}
