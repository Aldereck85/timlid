/* Idioma de la tabla */
const idioma_espanol = {
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
    sPrevious:
      "<i class='fas fa-chevron-left'></i>",
  },
};

/* Opciones de la tabla */
var tableOptions = {
  language: idioma_espanol,
  info: false,
  scrollX: true,
  bSort: false,
  pageLength: 50,
  responsive: true,
  lengthChange: false,
  columnDefs: [{ orderable: false, targets: 0, visible: false }],
  ajax: "functions/function_Organigrama.php",
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
          obtenerDatosOrganigrama();
          $('#agregar_Organigrama').modal('show')
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
  columns: [
    { data: "PKOrganigrama" },
    { data: "Empleado" },
    { data: "Jefe inmediato" },
    { data: "Puesto" },
    { data: "Acciones" },
  ],
};
new SlimSelect({
  select: '#cmbIdEmpleado'
});
var cmbIdNodo = new SlimSelect({
  select: '#cmbIdNodo'
});
new SlimSelect({
  select: '#nombre-empleado-edit'
});
/* Inicializacionde la tabla */
var tableVentas = $("#tblOrganigrama").DataTable(tableOptions);

new $.fn.dataTable.Buttons(tableVentas, {
  dom: {
    button: {
      tag: "button",
      className: "btn-table-custom",
    },
    buttonLiner: {
      tag: null,
    },
  },
  buttons: [
    {
      text: '<i class="fas fa-project-diagram"></i> Ver organigrama',
      className: "btn-table-custom--blue",
      action: function () {
        window.location.href = "organigrama.php";
      },
    },
  ],
});

tableVentas.buttons(1, null).container().appendTo("#btn-filters");

/* Funcion para traer los datos al agregar de un elemento */
var cantidad_jefes_global = 0;
function obtenerDatosOrganigrama() {
  $("#img_perfil").val("");
  $("#uploaded_image").empty();
  $("#imagenSubir").val("");
  $("#puesto").val("");

  $.ajax({
    type: "POST",
    url: "functions/getOrganigrama.php",
    dataType: "json",
  })
    .done(function (res) {
      /* Extraemos los datos de la respuesta */
      const { jefes, empleados, cantidad_jefes } = res;

      cantidad_jefes_global = cantidad_jefes;

      const selectJefeDirecto = document.querySelector("#cmbIdNodo");
      selectJefeDirecto.innerHTML =
        "<option disabled selected value='-1'>Seleccione una opción...</option>";

      jefes.forEach((jefe) => {
        selectJefeDirecto.insertAdjacentHTML(
          "beforeend",
          `<option value="${jefe.PKOrganigrama}" >${jefe.Nombres} ${jefe.PrimerApellido} ${jefe.SegundoApellido}</option>`
        );
      });

      const selectEmpleados = document.querySelector("#cmbIdEmpleado");
      selectEmpleados.innerHTML =
        "<option disabled selected value='-1'>Seleccione una opción...</option>";

      empleados.forEach((empleado) => {
        selectEmpleados.insertAdjacentHTML(
          "beforeend",
          `<option value="${empleado.PKEmpleado}" >${empleado.Nombres} ${empleado.PrimerApellido} ${empleado.SegundoApellido}</option>`
        );
      });
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
      });
    });
}

/* Funcion para traer los datos al editar un elemento */
function obtenerDatosOrganigramaEditar(id) {
  $("#img_perfilEditar").val("");
  $("#uploaded_imageEditar").empty();
  $("#imagenSubirEditar").val("");

  $.ajax({
    type: "POST",
    url: "functions/getOrganigramaEditar.php",
    data: {
      id,
    },
    dataType: "json",
  })
    .done(function (res) {
      if (res.status === "fail") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../img/timdesk/checkmark.svg",
          msg: `¡${res.message}!`,
        });
      } else {
        /* Extraemos los datos de la respuesta */
        const { organigrama, empleados, jefes, cantidad_jefes } = res;
        /* Insertamos el id del orgaigrama */
        $("#id-organigrama").val(organigrama.PKOrganigrama);
        /* Insertamos el puesto del empleado */
        $("#puesto-edit").val(organigrama.puesto);
        /*Id del empleado antes de modificar*/
        $("#id-empleado-original").val(organigrama.FKEmpleado);

        const selectJefeDirecto = document.querySelector(
          "#jefe-inmediato-edit"
        );
        selectJefeDirecto.innerHTML =
          "<option disabled selected>Seleccione una opción...</option>";

        $("#form-group-jefe").css("display", "block");
        jefes.forEach((jefe) => {
          const selected =
            jefe.PKEmpleado === organigrama.idempleado ? "selected" : "";

          selectJefeDirecto.insertAdjacentHTML(
            "beforeend",
            `<option value="${jefe.PKEmpleado}" ${selected}>${jefe.Nombres} ${jefe.PrimerApellido} ${jefe.SegundoApellido}</option>`
          );
        });
        $("#jefe-inmediato-edit").prop("disabled", true);

        const selectEmpleados = document.querySelector("#nombre-empleado-edit");
        selectEmpleados.innerHTML =
          "<option disabled selected>Seleccione una opción...</option>";

        empleados.forEach((empleado) => {
          const selectedE =
            empleado.PKEmpleado === organigrama.FKEmpleado ? "selected" : "";

          selectEmpleados.insertAdjacentHTML(
            "beforeend",
            `<option value="${empleado.PKEmpleado}" ${selectedE}>${empleado.Nombres} ${empleado.PrimerApellido} ${empleado.SegundoApellido}</option>`
          );
        });

        if (organigrama.ParentNode == null) {
          $("#nombre-empleado-edit").prop("disabled", false);
        } else {
          $("#nombre-empleado-edit").prop("disabled", true);
        }
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
      });
    });
}

let counter = 0;

$("#agregar_Organigrama").on("hidden.bs.modal", function (e) {
  $("#invalid-nombreComEmp").css("display", "none");
  $("#cmbIdEmpleado").removeClass("is-invalid");
  $("#invalid-jefe").css("display", "none");
  $("#cmbIdNodo").removeClass("is-invalid");
});

/* Funcion para agregar un elemento */
$("#guardarOrganigrama").click(function () {
  let nodoPadre;
  let idEmpleado = parseInt($("#cmbIdEmpleado").val());

  if ($("#cmbIdNodo").val() == "-1" || $("#cmbIdNodo").val() == null) {
    nodoPadre = null;
  } else {
    nodoPadre = parseInt($("#cmbIdNodo").val());
  }

  if (!idEmpleado) {
    $("#invalid-nombreComEmp").css("display", "block");
    $("#cmbIdEmpleado").addClass("is-invalid");
    return;
  } else {
    $("#invalid-nombreComEmp").css("display", "none");
    $("#cmbIdEmpleado").removeClass("is-invalid");
  }
console.log($("#imagenSubir").length);
  if (!$("#imagenSubir").length) {
    $("#invalid-foto").css("display", "block");
    $("#img_perfil").addClass("is-invalid");
    return;
  } else {
    $("#invalid-foto").css("display", "none");
    $("#img_perfil").removeClass("is-invalid");
  }

  if (cantidad_jefes_global > 0 && !nodoPadre) {
    $("#invalid-jefe").css("display", "block");
    $("#cmbIdNodo").addClass("is-invalid");
    return;
  } else {
    $("#invalid-jefe").css("display", "none");
    $("#cmbIdNodo").removeClass("is-invalid");
  }

  let imagenSubir = $("#imagenSubir").val();
  let data = {
    idEmpleado,
    nodoPadre,
    imagenSubir,
  };
  $.ajax({
    type: "POST",
    url: "functions/addOrganigrama.php",
    dataType: "json",
    data,
  })
    .done(function (res) {
      res.status === "success" ? $("#guardarOrganigrama").prop("disabled", true) : $("#guardarOrganigrama").prop("disabled", false) ;
      const notificationType = res.status === "fail" ? "error" : res.status;
      Lobibox.notify(notificationType, {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: `¡${res.message}!`,
      });
      $("#agregar_Organigrama").modal("toggle");
      $("#guardarOrganigrama").prop("disabled", false);
      $("#tblOrganigrama").DataTable().ajax.reload();
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: `¡Algo salio mal. Por favor intentalo más tarde!`,
      });
    });
});

$("#editar_Organigrama").on("hidden.bs.modal", function (e) {
  $("#invalid-nombreComEmpEdit").css("display", "none");
  $("#nombre-empleado-edit").removeClass("is-invalid");
});
/* Funcion para editar un elemento */
$("#modificarOrganigrama").click(function () {
  const idOrganigrama = parseInt($("#id-organigrama").val());
  const imagenSubirEditar = $("#imagenSubirEditar").val();
  let idEmpleado = parseInt($("#nombre-empleado-edit").val());
  let idEmpleadoOriginal = parseInt($("#id-empleado-original").val());

  /* if (counter == 0) {
    $("#nombre-empleado-edit")[0].reportValidity();
    $("#nombre-empleado-edit")[0].setCustomValidity(
      "Necesitas seleccionar un empleado."
    );
    counter = 1;
  } */

  if (!idEmpleado) {
    $("#invalid-nombreComEmpEdit").css("display", "block");
    $("#nombre-empleado-edit").addClass("is-invalid");
    return;
  } else {
    $("#invalid-nombreComEmpEdit").css("display", "none");
    $("#nombre-empleado-edit").removeClass("is-invalid");
  }

  const data = {
    idEmpleado,
    idOrganigrama,
    idEmpleadoOriginal,
    imagenSubirEditar,
  };

  $.ajax({
    type: "POST",
    url: "functions/putOrganigrama.php",
    dataType: "json",
    data,
  })
    .done(function (res) {
      const notificationType = res.status === "fail" ? "error" : res.status;
      Lobibox.notify(notificationType, {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: `¡${res.message}!`,
      });
      $("#editar_Organigrama").modal("toggle");
      $("#tblOrganigrama").DataTable().ajax.reload();
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: `¡Algo salio mal. Por favor intentalo más tarde!`,
      });
    });
});

/* Funcion para elimianr un elemento */
$("#eliminarOrganigrama").click(function () {
  const idOrganigramaD = parseInt($("#id-organigrama").val());
  swalWithBootstrapButtons
    .fire({
      title: "¿Desea eliminar el registro del organigrama?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar registro</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((value) => {
      if (value.isConfirmed) {
        $.ajax({
          url: "functions/eliminar_Organigrama.php",
          type: "POST",
          dataType: "json",
          data: {
            idOrganigramaD,
          },
        })
          .done(function (res) {
            if (res.status === "success") {
              $("#editar_Organigrama").modal("toggle");
              $("#tblOrganigrama").DataTable().ajax.reload();
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: false,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Registro eliminado!",
              });
              $("#editar_Organigrama").modal("toggle");
              $("#tblOrganigrama").DataTable().ajax.reload();
            } else {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: false,
                img: "../../img/timdesk/warning_circle.svg",
                msg: `¡${res.message}!,`,
              });
            }
          })
          .fail(function (jqXHR, textStatus, errorThrown) {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: false,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "¡Algo salio mal intentalo de nuevo mas tarde!",
            });
          });
      }
    });
});

/* Custom buttons sweet alert */
const swalWithBootstrapButtons = Swal.mixin({
  customClass: {
    actions: "d-flex justify-content-around",
    confirmButton: "btn-custom btn-custom btn-custom--blue",
    cancelButton: "btn-custom btn-custom--border-blue",
  },
  buttonsStyling: false,
});

/* Funciones recortar imagen */
var anchopantalla = screen.width;
var width1 = 400,
  width2 = 600,
  height1 = 400,
  height2 = 600;

if (anchopantalla <= 750) {
  width1 = 160;
  height1 = 160;
  width2 = 200;
  height2 = 200;
}

$image_crop = $("#image_demo").croppie({
  enableExif: true,
  viewport: {
    width: width1,
    height: height1,
    type: "square", //circle
  },
  boundary: {
    width: width2,
    height: height2,
  },
});

$image_crop2 = $("#image_demo_editar").croppie({
  enableExif: true,
  viewport: {
    width: width1,
    height: height1,
    type: "square", //circle
  },
  boundary: {
    width: width2,
    height: height2,
  },
});

$("#img_perfilEditar").on("change", function () {
  var reader = new FileReader();
  reader.onload = function (event) {
    $image_crop2
      .croppie("bind", {
        url: event.target.result,
      })
      .then(function () {});
  };
  reader.readAsDataURL(this.files[0]);
  $("#uploadimageModalEditar").modal("show");
});

$(".crop_imageEditar").click(function (event) {
  var imagenSubir = $("#imagenSubirEditar").val();

  $image_crop2
    .croppie("result", {
      type: "canvas",
      size: "viewport",
    })
    .then(function (response) {
      $.ajax({
        url: "functions/uploadEditar.php",
        type: "POST",
        data: {
          image: response,
          imagenSubir: imagenSubir,
        },
      })
        .done(function (res) {
          res = res.replace('src="', 'src="functions/');
          $("#uploadimageModalEditar").modal("hide");
          $("#uploaded_imageEditar").html(res);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {});
    });
});

$("#cmbIdEmpleado,#nombre-empleado-edit").change(function () {
  var id = $("#cmbIdEmpleado").val();

  $.ajax({
    type: "POST",
    url: "functions/getPuesto.php",
    data: {
      idempleado: id,
    },
  })
    .done(function (res) {
      $("#puesto").val(res);
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../img/timdesk/checkmark.svg",
        msg: `¡Algo salio mal. Por favor intenalo más tarde!`,
      });
    });
});

$("#img_perfil").on("change", function () {
  var reader = new FileReader();
  reader.onload = function (event) {
    $image_crop
      .croppie("bind", {
        url: event.target.result,
      })
      .then(function () {});
  };
  reader.readAsDataURL(this.files[0]);
  $("#uploadimageModal").modal("show");
});

$(".crop_image").click(function (event) {
  var imagenSubir = $("#imagenSubir").val();

  $image_crop
    .croppie("result", {
      type: "canvas",
      size: "viewport",
    })
    .then(function (response) {
      $.ajax({
        url: "functions/upload.php",
        type: "POST",
        data: {
          image: response,
          imagenSubir: imagenSubir,
        },
      })
        .done(function (res) {
          res = res.replace('src="', 'src="functions/');
          $("#uploadimageModal").modal("hide");
          $("#uploaded_image").html(res);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {});
    });
});