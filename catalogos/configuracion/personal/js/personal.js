function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../img/timdesk/buscar.svg' width='20px' />",
    sLoadingRecords: "Cargando...",
    searchPlaceholder: "Buscar...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "<img src='../../../img/icons/pagination.svg' width='20px'/>",
      sPrevious:
        "<img src='../../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
    },
  };
  return idioma_espanol;
}

$(document).ready(function () {
  $("#tblPersonal").dataTable({
    language: setFormatDatatables(),
    dom: "Bfrtip",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<img class="readEditPermissions" type="submit" width="50px" src="../../../img/excel-azul.svg" />',
        className: "excelDataTableButton",
        titleAttr: "Excel",
      },
    ],
    scrollX: true,
    lengthChange: false,
    info: false,
    order: [[0, "desc"]],
    ajax: {
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_personalTable" },
    },
    columns: [
      {
        data: "id",
      },
      {
        data: "Nombre",
      },
      {
        data: "Género",
      },
      {
        data: "Estado",
      },
      {
        data: "Roles",
      },
    ],
    columnDefs: [
      {
        orderable: false,
        targets: 0,
        visible: false,
      },
    ],
    responsive: true,
  });

  let {cmbEstado, cmbEstadoU, cmbGenero, cmbGeneroU, cmbRoles, cmbRolesU} = 0;
  cmbEstado = new SlimSelect({
    select: '#cmbEstado',
    placeholder: 'Seleccionar estado'
  });
  cmbEstadoU = new SlimSelect({
    select: '#cmbEstadoU',
    placeholder: 'Seleccionar estado'
  });
  cmbGenero = new SlimSelect({
    select: '#cmbGenero',
    placeholder: 'Seleccionar género'
  });
  cmbGeneroU = new SlimSelect({
    select: '#cmbGeneroU',
    placeholder: 'Seleccionar género'
  });
  cmbRoles = new SlimSelect({
    select: '#cmbRoles',
    placeholder: 'Seleccionar roles'
  });
  cmbRolesU = new SlimSelect({
    select: '#cmbRolesU',
    placeholder: 'Seleccionar roles'
  });
});

// Reiniciar el modal al cerrarlo
$("#agregar_Personal").on("hidden.bs.modal", function (e) {
  $("#invalid-nombre").css("display", "none");
  $("#txtNombre").removeClass("is-invalid");
  $("#txtNombre").val("");

  $("#invalid-primerApellido").css("display", "none");
  $("#txtPrimerApellido").removeClass("is-invalid");
  $("#txtPrimerApellido").val("");

  $("#invalid-genero").css("display", "none");
  $("#cmbGenero").val("");

  $("#invalid-estado").css("display", "none");
  $("#cmbEstado").val("");

  $("#invalid-roles").css("display", "none");
  $("#cmbRoles").val("");
});

// AGREGA SUCURSAL
$("#btnAgregarPersonal").click(function () {
  if ($("#agregarPersonal")[0].checkValidity()) {
    var badNombre =
      $("#invalid-nombre").css("display") === "block" ? false : true;
    var badPrimerApellido =
      $("#invalid-primerApellido").css("display") === "block" ? false : true;
    var badGenero =
      $("#invalid-genero").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estado").css("display") === "block" ? false : true;
    var badRoles =
      $("#invalid-roles").css("display") === "block" ? false : true;
    if (
      badNombre &&
      badPrimerApellido &&
      badGenero &&
      badEstado &&
      badRoles
    ) {
      var nombre = $("txtNombre").val().trim();
      var apellido = $("txtPrimerApellido").val().trim();
      var estado = $("#cmbEstado").val();
      var genero = $("#cmbGenero").val();
      var prefijo = $("#cmbRoles").val();

      if (nombreSucursal.length < 1) {
        $("#txtarea")[0].reportValidity();
        $("#txtarea")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (calle.length < 1) {
        $("#txtarea2")[0].reportValidity();
        $("#txtarea2")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (numExterior.length < 1) {
        $("#txtarea3")[0].reportValidity();
        $("#txtarea3")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (colonia.length < 1) {
        $("#txtarea5")[0].reportValidity();
        $("#txtarea5")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (municipio.length < 1) {
        $("#txtarea7")[0].reportValidity();
        $("#txtarea7")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (cmbEstado == 0) {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3500,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/warning_circle.svg",
          img: null,
          msg: "Selecciona el estado",
        });
        //$("#txtarea6")[0].reportValidity();
        //$("#txtarea6")[0].setCustomValidity('Selecciona un estado.');
      } else {
        $.ajax({
          url: "functions/agregar_Locacion.php",
          type: "POST",
          data: {
            txtLocacion: nombreSucursal,
            txtCalle: calle,
            txtNe: numExterior,
            prefijo: prefijo,
            txtNi: numInterior,
            txtColonia: colonia,
            txtMunicipio: municipio,
            cmbEstados: estado,
            cmbPais: pais,
            telefono: telefono,
            actInventario: actInventario,
          },
          success: function (data, status, xhr) {
            if (data.trim() == "exito") {
              $("#agregar_Locacion").modal("toggle");
              $("#agregarLocacion").trigger("reset");
              $("#tblSucursales").DataTable().ajax.reload();
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                //img: '<i class="fas fa-check-circle"></i>',
                img: "../../../img/timdesk/checkmark.svg",
                msg: "¡Registro agregado!",
              });
            } else {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../img/timdesk/warning_circle.svg",
                img: null,
                msg: "Ocurrió un error al agregar",
              });
            }
          },
        });
      }
    }
  } else {
    if (!$("#txtarea").val()) {
      $("#invalid-nombreSuc").css("display", "block");
      $("#txtarea").addClass("is-invalid");
    }
    if (!$("#txtarea2").val()) {
      $("#invalid-calleSuc").css("display", "block");
      $("#txtarea2").addClass("is-invalid");
    }
    if (!$("#txtarea3").val()) {
      $("#invalid-noExtSuc").css("display", "block");
      $("#txtarea3").addClass("is-invalid");
    }
    if (!$("#txtarea5").val()) {
      $("#invalid-coloniaSuc").css("display", "block");
      $("#txtarea5").addClass("is-invalid");
    }
    if (!$("#txtarea7").val()) {
      $("#invalid-municipioSuc").css("display", "block");
      $("#txtarea7").addClass("is-invalid");
    }
    if (!$("#txtarea8").val()) {
      $("#invalid-paisSuc").css("display", "block");
      $("#txtarea8").addClass("is-invalid");
    }
    if (!$("#txtarea6").val()) {
      $("#invalid-estadoSuc").css("display", "block");
      $("#txtarea6").addClass("is-invalid");
    }
    if (!$("#txtarea10").val()) {
      $("#invalid-telSuc").css("display", "block");
      $("#txtarea10").addClass("is-invalid");
    }
  }
});

function obtenerIdSucursalEditar(id) {
  document.getElementById("idLocacionU").value = id;
  document.getElementById("idLocacionD").value = id;
  var id = "id=" + id;
  console.log(id);
  $.ajax({
    type: "POST",
    url: "functions/getLocacion.php",
    data: id,
    success: function (r) {
      var datos = JSON.parse(r);
      $("#txtareau").val(datos.html);
      $("#txtarea2u").val(datos.html11);
      $("#txtarea3u").val(datos.html21);
      $("#txtarea4u").val(datos.html31);
      $("#txtarea5u").val(datos.html41);
      $("#txtarea6u").val(datos.html51);
      select.set(datos.html51);
      $("#txtarea7u").val(datos.html61);
      $("#txtarea8u").val(datos.html71);
      $("#txtarea9u").val(datos.html81);
      $("#txtarea10u").val(datos.html91);

      if (datos.html101 == 1) {
        $("#cbxActivarInventarioU").prop("checked", true);
      } else {
        $("#cbxActivarInventarioU").prop("checked", false);
      }

      var idSucursal = $("#idLocacionU").val();
      if (idSucursal != "") {
        validarExisteRelacionSucursal(idSucursal);
      } else {
      }
    },
  });
}
function eliminarLocacion(id) {
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
      title: "¿Desea eliminar el registro de esta locacion?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter2">Eliminar locación</span>',
      cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "functions/eliminar_Locacion.php",
          type: "POST",
          data: {
            idLocacionD: id,
          },
          success: function (data, status, xhr) {
            if (data == "1") {
              $("#modalEditar").modal("toggle");
              $("#tblSucursales").DataTable().ajax.reload();
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: true,
                img: "../../../img/timdesk/checkmark.svg",
                msg: "¡Registro eliminado!",
              });
            } else {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../img/timdesk/warning_circle.svg",
                msg: "Ocurrió un error al eliminar",
              });
            }
          },
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });

  swal("¿Desea eliminar el registro de esta locación?", {
    buttons: {
      cancel: {
        text: "Cancelar",
        value: null,
        visible: true,
        className: "",
        closeModal: true,
      },
      confirm: {
        text: "Eliminar locación",
        value: true,
        visible: true,
        className: "",
        closeModal: true,
      },
    },
    icon: "warning",
  }).then((value) => {
    if (value) {
      $.ajax({
        url: "functions/eliminar_Locacion.php",
        type: "POST",
        data: {
          idLocacionD: id,
        },
        success: function (data, status, xhr) {
          if (data == "1") {
            $("#modalEditar").modal("toggle");
            $("#tblLocaciones").DataTable().ajax.reload();
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: false,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: "¡Registro eliminado!",
            });
          } else {
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: false,
              img: "../../../img/timdesk/warning_circle.svg",
              msg: "Ocurrió un error al eliminar",
            });
          }
        },
      });
    } else {
      //cuando se presiona el boton de cancelar
    }
  });
}
function validarUnicaSucursal(item) {
  var valor = item.value;
  $.ajax({
    url: "sucursales/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_sucursal", data: valor },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        item.nextElementSibling.innerText =
          "La sucursal ya esta en el registro.";
        item.nextElementSibling.style.display = "block";
        item.classList.add("is-invalid");
      } else {
        item.nextElementSibling.innerText = "La sucursal debe tener un nombre.";
        item.nextElementSibling.style.display = "none";
        item.classList.remove("is-invalid");
      }
    },
  });
}
function validarUnicaSucursalU(item) {
  var valor = item.value;
  var id = $("#idLocacionU").val();

  $.ajax({
    url: "sucursales/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_sucursalU",
      data: valor,
      data2: id,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        item.nextElementSibling.innerText =
          "La sucursal ya esta en el registro.";
        item.nextElementSibling.style.display = "block";
        item.classList.add("is-invalid");
      } else {
        item.nextElementSibling.innerText = "La sucursal debe tener un nombre.";
        item.nextElementSibling.style.display = "none";
        item.classList.remove("is-invalid");
      }
    },
  });
}
function validarExisteRelacionSucursal(valor) {
  $.ajax({
    url: "sucursales/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionSucursal",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion con cliente", data);

        $("#txtareau").prop("disabled", true);
        var eliminar = document.getElementById("idLocacionD");
        eliminar.style.display = "none";
        //var modificar = document.getElementById("btnEditarLocacion");
        //modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");
      } else {
        $("#txtareau").prop("disabled", false);

        var eliminar = document.getElementById("idLocacionD");
        eliminar.style.display = "block";
        //var modificar = document.getElementById("btnEditarLocacion");
        //modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");
      }
    },
  });
}
function validaNumTelefono(evt, input) {
  var key = window.Event ? evt.which : evt.keyCode;
  if (key == 8 || key == 46) {
    $("#result1").val($("#txtarea10").val().length);
    $("#result1").addClass("mui--is-not-empty");
    var valor = $("#result1").val();
    if (valor < 8 || valor == 9) {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").addClass("is-invalid");
    } else {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").removeClass("is-invalid");
    }
  } else {
    $("#result1").val($("#txtarea10").val().length);
    $("#result1").addClass("mui--is-not-empty");
    var valor = $("#result1").val();
    if (valor < 8 || valor == 9) {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").addClass("is-invalid");
    } else {
      $("#invalid-invalid-telSuc").css("display", "block");
      $("#txtarea10").removeClass("is-invalid");
      return false;
    }
  }
}
function validaNumTelefonoU(evt, input) {
  var key = window.Event ? evt.which : evt.keyCode;
  if (key == 8 || key == 46) {
    $("#result2").val($("#txtarea10u").val().length);
    $("#result2").addClass("mui--is-not-empty");
    var valor = $("#result2").val();
    if (valor < 8 || valor == 9) {
      $("#invalid-telSucEdit").css("display", "block");
      $("#txtarea10u").addClass("is-invalid");
    } else {
      $("#invalid-telSucEdit").css("display", "none");
      $("#txtarea10u").removeClass("is-invalid");
    }
  } else {
    $("#result2").val($("#txtarea10u").val().length);
    $("#result2").addClass("mui--is-not-empty");
    var valor = $("#result2").val();
    if (valor < 8 || valor == 9) {
      $("#invalid-telSucEdit").css("display", "block");
      $("#txtarea10u").addClass("is-invalid");
    } else {
      $("#invalid-telSucEdit").css("display", "none");
      $("#txtarea10u").removeClass("is-invalid");
      return false;
    }
  }
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

$("#btnEditarPersonal").click(function() {
  var id = $('#idLocacionU').val();
  var nombreSucursal = $("#txtareau").val().trim();
  var calle = $("#txtarea2u").val();
  var numExterior = $("#txtarea3u").val();
  var prefijo = $("#txtarea9u").val();
  var numInterior = $("#txtarea4u").val();
  var colonia = $("#txtarea5u").val();
  var municipio = $("#txtarea7u").val();
  var estado = $("#txtarea6u").val();
  var pais = $("#txtarea8u").val();
  var telefono = $("#txtarea10u").val();
  var contPais = 0;
  var contEstado = 0;
  var actInventario = 0;

  if ($("#editarLocacionU")[0].checkValidity()) {
    var badNombreSucEdit =
      $("#invalid-nombreSucEdit").css("display") === "block" ? false : true;
    var badCalleSucEdit =
      $("#invalid-calleSucEdit").css("display") === "block" ? false : true;
    var badNoExtSucEdit =
      $("#invalid-noExtSucEdit").css("display") === "block" ? false : true;
    var badColoniaSucEdit =
      $("#invalid-coloniaSucEdit").css("display") === "block" ? false : true;
    var badMunicipioSucEdit =
      $("#invalid-municipioSucEdit").css("display") === "block" ? false : true;
    var badPaisSucEdit =
      $("#invalid-paisSucEdit").css("display") === "block" ? false : true;
    var badEstadoSucEdit =
      $("#invalid-estadoSucEdit").css("display") === "block" ? false : true;
    var badTelSucEdit =
      $("#invalid-telSucEdit").css("display") === "block" ? false : true;
    if (
      badNombreSucEdit &&
      badCalleSucEdit &&
      badNoExtSucEdit &&
      badColoniaSucEdit &&
      badMunicipioSucEdit &&
      badPaisSucEdit &&
      badEstadoSucEdit &&
      badTelSucEdit
    ) {
      if ($("#cbxActivarInventarioU").is(":checked")) {
        actInventario = 1;
      } else {
        actInventario = 0;
      }



      $.ajax({
        url: "functions/editar_Locacion.php",
        type: "POST",
        data: {
          "idLocacionU": id,
          "txtLocacionU": nombreSucursal,
          "txtCalleU": calle,
          "txtNeU": numExterior,
          "prefijo": prefijo,
          "txtNiU": numInterior,
          "txtColoniaU": colonia,
          "txtMunicipioU": municipio,
          "cmbEstadosU": estado,
          "cmbPaisU": pais,
          "telefono": telefono,
          "actInventario": actInventario
        },
        success: function(data, status, xhr) {
          console.log(data);
          if (data.trim() == "exito") {
            $('#modalEditar').modal('toggle');
            $('#editarLocacionU').trigger("reset");
            $('#tblSucursales').DataTable().ajax.reload();
            Lobibox.notify('success', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: true,
              img: '../../../img/timdesk/checkmark.svg',
              msg: '¡Registro modificado!'
            });
          } else {
            Lobibox.notify('warning', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top',
              icon: true,
              img: '../../../img/timdesk/warning_circle.svg',
              msg: 'Ocurrió un error al editar'
            });
          }
        }
      });
    }
  } else {
    if (!nombreSucursal) {
      $("#txtareau").addClass("is-invalid")
      $("#invalid-nombreSucEdit").css("display", "block");
    }

    if (!calle) {
      $("#txtarea2u").addClass("is-invalid")
      $("#invalid-calleSucEdit").css("display", "block");
    }

    if (!colonia) {
      $("#txtarea5u").addClass("is-invalid")
      $("#invalid-coloniaSucEdit").css("display", "block");
    }

    if (!municipio) {
      $("#txtarea7u").addClass("is-invalid")
      $("#invalid-municipioSucEdit").css("display", "block");
    }

    if (!estado) {
      $("#txtarea6u").addClass("is-invalid")
      $("#invalid-estadoSucEdit").css("display", "block");
    }

    if (!pais) {
      $("#txtarea8u").addClass("is-invalid")
      $("#invalid-paisSucEdit").css("display", "block");
    }
    if (!telefono) {
      $("#txtarea10u").addClass("is-invalid")
      $("#invalid-telSucEdit").css("display", "block");
    }
  }
});

/*function obtenerIdLocacionEliminar(id) {
  document.getElementById('idLocacionD').value = id;
}*/


function eliminarLocacion(id) {

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      actions: "d-flex justify-content-around",
      confirmButton: "btn-custom btn-custom--border-blue",
      cancelButton: "btn-custom btn-custom--blue"
    },
    buttonsStyling: false
  })

  swalWithBootstrapButtons.fire({
    title: '¿Desea eliminar el registro de esta locacion?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: '<span class="verticalCenter2">Eliminar locación</span>',
    cancelButtonText: '<span class="verticalCenter2">Cancelar</span>',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {

      $.ajax({
        url: "functions/eliminar_Locacion.php",
        type: "POST",
        data: {
          "idLocacionD": id
        },
        success: function(data, status, xhr) {
          if (data == "1") {
            $('#modalEditar').modal('toggle');
            $('#tblSucursales').DataTable().ajax.reload();
            Lobibox.notify('error', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: true,
              img: '../../../img/chat/notificacion_error.svg',
              msg: '¡Registro eliminado!'
            });
          } else {
            Lobibox.notify('warning', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top',
              icon: true,
              img: '../../../img/timdesk/warning_circle.svg',
              msg: 'Ocurrió un error al eliminar'
            });
          }
        }
      });

    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {

    }
  })

  swal("¿Desea eliminar el registro de esta locación?", {
      buttons: {
        cancel: {
          text: "Cancelar",
          value: null,
          visible: true,
          className: "",
          closeModal: true,
        },
        confirm: {
          text: "Eliminar locación",
          value: true,
          visible: true,
          className: "",
          closeModal: true,
        },
      },
      icon: "warning"
    })
    .then((value) => {
      if (value) {
        $.ajax({
          url: "functions/eliminar_Locacion.php",
          type: "POST",
          data: {
            "idLocacionD": id
          },
          success: function(data, status, xhr) {
            if (data == "1") {
              $('#modalEditar').modal('toggle');
              $('#tblLocaciones').DataTable().ajax.reload();
              Lobibox.notify('error', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../../img/timdesk/notificacion_error.svg',
                msg: '¡Registro eliminado!'
              });
            } else {
              Lobibox.notify('warning', {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top',
                icon: false,
                img: '../../../img/timdesk/warning_circle.svg',
                msg: 'Ocurrió un error al eliminar'
              });
            }
          }
        });
      } else {
        //cuando se presiona el boton de cancelar
      }
    });


}

/* Reiniciar el modal al cerrarlo */
$("#modalEditar").on("hidden.bs.modal", function(e) {
  $("#invalid-nombreSucEdit").css("display", "none");
  $("#txtareau").removeClass("is-invalid");
  $("#txtareau").val("");

  $("#invalid-calleSucEdit").css("display", "none");
  $("#txtarea2u").removeClass("is-invalid");
  $("#txtarea2u").val("");

  $("#invalid-noExtSucEdit").css("display", "none");
  $("#txtarea3u").removeClass("is-invalid");
  $("#txtarea3u").val("");

  $("#invalid-coloniaSucEdit").css("display", "none");
  $("#txtarea5u").removeClass("is-invalid");
  $("#txtarea5u").val("");

  $("#invalid-municipioSucEdit").css("display", "none");
  $("#txtarea7u").removeClass("is-invalid");
  $("#txtarea7u").val("");

  $("#invalid-paisSucEdit").css("display", "none");
  $("#txtarea8u").removeClass("is-invalid");
  $("#txtarea8u").val("");

  $("#invalid-estadoSucEdit").css("display", "none");
  $("#txtarea6u").removeClass("is-invalid");
  $("#txtarea6u").val("");

  $("#invalid-telSucEdit").css("display", "none");
  $("#txtarea10u").removeClass("is-invalid");
  $("#txtarea10u").val("");
});

var ruta = "../../";
