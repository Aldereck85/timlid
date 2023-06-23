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
mostrar();
/* Variables */
let caracter2 = "'"; //auxiliar para insertar variables en los campos onclick o similares.
let varContainer = ""; //variable que guarda la clase que se va a ocultar con click fuera.
let selectedColumns = []; //Guardará info de las columnas seleccionadas para mostrar
let ids = [];
let array = [];
//Lista de las columnas para empleados disponible

$.ajax({
  url: "php/funciones.php",
  data: { clase: "get_data", funcion: "lista_columnas" },
  dataType: "json",
  success: function (respuesta) {
    //console.log("Respuesta lista_columnas: ",respuesta);
    let classCheck;
    let onclick;
    for (i = 0; i < respuesta.length; i++) {
      if (respuesta[i].Seleccionada == 1) {
        classCheck = "checked-type-column";
        onclick =
          'onclick="unselectColumn(' + respuesta[i].PKColumnasEmp + ',0)"';
        selectedColumns.push([
          respuesta[i].PKColumnasEmp,
          respuesta[i].Tabla,
          respuesta[i].FKTipoColumnaEmp,
        ]);
        classCheckColumn = "checked";
        onclickColumn =
          'onclick="unselectColumnModal(' + respuesta[i].PKColumnasEmp + ',0)"';
      } else {
        classCheck = "check-type-column";
        onclick =
          'onclick="selectColumn(' + respuesta[i].PKColumnasEmp + ',1)"';
        classCheckColumn = "";
        onclickColumn =
          'onclick="selectColumnModal(' + respuesta[i].PKColumnasEmp + ',1)"';
      }
    }
    /*$('.listaColumnas').append('<div class="pd-15 columna-add">'+
									'<div class="columns_modal" data-toggle="modal" data-target="#modalColumnsElement">Agregar columna</div>'+
								'</div>');*/

    //console.log("selectedColumns en lista_columnas", selectedColumns);
    get_info(selectedColumns);
  },
  error: function (error) {
    console.log(error);
  },
});

$.ajax({
  url: "php/funciones.php",
  data: { clase: "get_data", funcion: "orden_columnas" },
  dataType: "json",
  success: function (respuesta) {
    //console.log("Respuesta orden_columnas: ",respuesta);
    let classCheck;

    for (i = 0; i < respuesta.length; i++) {
      if (respuesta[i].FKTipoColumnaEmp != 1) {
        $("#sortableColumns").append(
          '<div id="col_' +
            respuesta[i].PKColumnasEmp +
            '" data-pos=' +
            respuesta[i].PKColumnasEmp +
            ">" +
            '<div class="columna-empleado handle column-header">' +
            '<div class="column-title">' +
            respuesta[i].Nombre +
            '</div><div><a class="column-order" id="sort-' +
            respuesta[i].PKColumnasEmp +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            respuesta[i].PKColumnasEmp +
            '" class="columna-info"></div>' +
            "</div>"
        );
      } else {
        $("#noSortableColumns").append(
          '<div id="col_' +
            respuesta[i].PKColumnasEmp +
            '" data-pos=' +
            respuesta[i].PKColumnasEmp +
            ">" +
            '<div class="columna-empleado column-header">' +
            '<div class="column-title">' +
            respuesta[i].Nombre +
            '</div><div><a class="column-order" id="sort-' +
            respuesta[i].PKColumnasEmp +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            respuesta[i].PKColumnasEmp +
            '" class="columna-info"></div>' +
            "</div>"
        );
      }
    }

    //console.log("selectedColumns en orden_columnas", selectedColumns);
    //get_info(selectedColumns);
  },
  error: function (error) {
    console.log(error);
  },
});

function validarExcel() {
  $("#loaderValidacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("tipo", $("#tipoImportacion").val());
  $.ajax({
    url: "validarExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
    },
    error: function (error) {
      console.log(error.responseText);
      if (error.responseText.includes("Formato no aceptado") == true) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>Formato no aceptado</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else if (error.responseText.includes("error") == true) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: error.responseText.slice(5),
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else if (error.responseText.includes("Formato incorrecto") == true) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "<p style='font-size: 15px'>Formato incorrecto</p>",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else if (
        error.responseText != "" &&
        error.responseText.includes("Formato incorrecto") == false &&
        error.responseText.includes("Formato no aceptado") == false
      ) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: error.responseText,
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else if (error.responseText.includes("fail")) {
        $("#loaderValidacion").css("display", "none");
        Swal.fire({
          title: "Error",
          icon: "error",
          html: "No se pudo subir el archivo",
          showCancelButton: false,
          confirmButtonText: "Aceptar",
          customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            $("#dataexcel").val("");
            Swal.close();
          }
          if (result.isDismissed) {
            $("#dataexcel").val("");
          }
        });
      } else {
        $("#loaderValidacion").css("display", "none");
        importExcel();
      }
    },
  });
}

function importExcel() {
  $("#loaderImportacion").css("display", "block");

  var formData = new FormData();
  formData.append("dataexcel", dataexcel.files[0]);
  formData.append("tipo", $("#tipoImportacion").val());
  $.ajax({
    url: "uploadExcel.php",
    type: "POST",
    data: formData,
    dataType: "json",
    async: false,
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      $("#tblInventariosIniciales").DataTable().ajax.reload();
    },
    error: function (error) {
      console.log(error);
      $("#loaderImportacion").css("display", "none");

      Swal.fire({
        title: "Datos importados",
        icon: "success",
        showCancelButton: false,
        confirmButtonText: "Aceptar",
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
        },
        buttonsStyling: false,
        allowEnterKey: false,
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.close();
          location.reload();
        } else if (result.isDismissed) {
          Swal.close();
          location.reload();
        }
      });
    },
  });
}

function get_id(id) {
  return id;
}
//idEmpleado para la paginación
function templateIdEmployer(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    //console.log(data[j]);
    html +=
      '<div class="hideEmployer show-icon-edit b-bottom row-' +
      data[j].PKEmpleado +
      '" id="idEmpleado-' +
      data[j].PKEmpleado +
      '" style="height: 36px; color:black;"><span class="idEmployer-' +
      data[j].PKEmpleado +
      '" style="display:inline-block;position:relative;">' +
      data[j].PKEmpleado +
      "</span>" +
      '<a id="delete-tabs-' +
      data[j].PKEmpleado +
      '" data-id="' +
      data[j].PKEmpleado +
      '" href="#"><img class="edit-icon" id="delete-icon-' +
      data[j].PKEmpleado +
      '" src="../../img/timdesk/delete.svg"></a>' +
      '<a id="edit-tabs-' +
      data[j].PKEmpleado +
      '" data-id="' +
      data[j].PKEmpleado +
      '" href="#"><img class="edit-icon" id="edit-icon-' +
      data[j].PKEmpleado +
      '" src="../../img/timdesk/edit.svg"></a>' +
      "</div>";
  }
  //console.log(html);
  return html;
}

//Nombre Empleado para la paginación
function templateEmployer(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    // console.log(data[j].PKNombreEmpleado);
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="NombreEmpleado-' +
      data[j].PKEmpleado +
      '">' +
      '<input class="text-center border-n NombreEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKNombreEmpleado +
      ',3)" value="' +
      data[j].Nombres +
      '" readonly>' +
      "</div>";
  }
  //console.log(html);
  return html;
}

//Primer Apellido Empleado para la paginación
function templateFirstLastName(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    //console.log(data[j].PrimerApellido);
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="ApellidoPaterno-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n ApellidoPaternotexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKApellidoPaterno +
      ',4)" value="' +
      data[j].PrimerApellido +
      '" readonly></div>';
  }

  return html;
}

//Segundo Apellido Empleado para la paginación
function templateSecondLastName(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="ApellidoMaterno-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n ApellidoMaternotexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKApellidoMaterno +
      ',5)" value="' +
      data[j].SegundoApellido +
      '" readonly></div>';
  }

  return html;
}

//Estado civil del empleado para la paginación
function templateStateCivil(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="CivilEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n CivilEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKECivilEmpleado +
      ',5)" value="' +
      data[j].EstadoCivil +
      '" readonly></div>';
  }

  return html;
}

//Género del empleado para la paginación
function templateGender(data, array) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="GeneroEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n GenreroEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKGeneroEmpleado +
      ',5)" value="' +
      data[j].Genero +
      '" readonly></div>';
  }

  return html;
}

//Direccion del empleado para la paginación
function templateDirection(data) {
  var html = "";
  for (var j = 0; j < data.length; j++) {
    let interior;
    if (data[j].Interior !== "") {
      interior = "Int " + data[j].Interior;
    } else {
      interior = "";
    }
    let direccion =
      data[j].Direccion + " " + data[j].NumeroExterior + " " + interior;
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="DireccionEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n DireccionEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" value="' +
      direccion +
      '" readonly></div>';
    //console.log("html:",data[j].PKDireccionEmpleado);
  }

  return html;
}

//Estado del empleado para la paginación
function templateState(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="EstadoEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n EstadoEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKEstadoEmpleado +
      ',9)" value="' +
      data[j].Estado +
      '" readonly></div>';
  }

  return html;
}

//Ciudad del empleado para la paginación
function templateCity(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="CiudadEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n CiudadEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKCiudadEmpleado +
      ',10)" value="' +
      data[j].Ciudad +
      '" readonly></div>';
  }

  return html;
}

//Colonia del empleado para la paginación
function templateColony(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="ColoniaEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n ColoniaEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKColoniaEmpleado +
      ',11)" value="' +
      data[j].Colonia +
      '" readonly></div>';
  }

  return html;
}

//CP del empleado para la paginación
function templateCp(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="CPEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n CPEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKPostalEmpleado +
      ',12)" value="' +
      data[j].CP +
      '" readonly></div>';
  }

  return html;
}

//CURP del empleado para la paginación
function templateCurp(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="CurpEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n CurpEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKCurp +
      ',13)" value="' +
      data[j].CURP +
      '" readonly></div>';
  }

  return html;
}

//RFC del empleado para la paginación
function templateRfc(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="RfcEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n RfcEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKRfcEmpleado +
      ',14)" value="' +
      data[j].RFC +
      '" readonly></div>';
  }

  return html;
}

//Nacimiento del empleado para la paginación
function templateBirth(data) {
  var html = "";
  let fechaform = "";
  for (var j = 0; j < data.length; j++) {
    if (data[j].FechaNacimiento != "0000-00-00") {
      //console.log("date birth: "+data[j].FechaNacimiento);
      let fecha = new Date(data[j].FechaNacimiento);
      let mes = "";

      let auxMes = fecha.getMonth() + 1;
      //console.log("month birth: "+auxMes);
      switch (auxMes) {
        case 1:
          mes = "01";
          break;
        case 2:
          mes = "02";
          break;
        case 3:
          mes = "03";
          break;
        case 4:
          mes = "04";
          break;
        case 5:
          mes = "05";
          break;
        case 6:
          mes = "06";
          break;
        case 7:
          mes = "07";
          break;
        case 8:
          mes = "08";
          break;
        case 9:
          mes = "09";
          break;
        case 10:
          mes = "10";
          break;
        case 11:
          mes = "11";
          break;
        case 12:
          mes = "12";
          break;
      }
      let dia = "";
      let auxDia = fecha.getDate() + 1;
      if (auxDia.toString().length == 1) {
        dia = "0" + auxDia;
      } else {
        dia = auxDia;
      }

      fechaform = dia + "/" + mes + "/" + fecha.getFullYear();
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="NacimientoEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n NacimientoEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKNacimiento +
      ',15)" value="' +
      fechaform +
      '" readonly></div>';
  }

  return html;
}

//Télefono del empleado para la paginación
function templatePhone(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="TelefonoEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n TelefonoEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKTelefonoEmpleado +
      ',16)" value="' +
      data[j].Telefono +
      '" readonly></div>';
  }

  return html;
}

//Estatus del empleado para la paginación
function templateStatus(data) {
  let html = "";
  let estatus = "";
  for (var j = 0; j < data.length; j++) {
    if (data[j].Estatus_Empleado != null) {
      estatus = data[j].Estatus_Empleado;
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="EstatusEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n EstatusEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKEstatusEmpleado +
      ',16)" value="' +
      estatus +
      '" readonly></div>';
  }

  return html;
}

function templateAdmissionDate(data) {
  let html = "";

  let fechaform = "";
  for (var j = 0; j < data.length; j++) {
    if (data[j].FechaIngreso != "0000-00-00" && data[j].FechaIngreso != null) {
      //console.log("date birth: "+data[j].FechaNacimiento);
      let fecha = new Date(data[j].FechaIngreso);
      let mes = "";

      let auxMes = fecha.getMonth() + 1;
      //console.log("month birth: "+auxMes);
      switch (auxMes) {
        case 1:
          mes = "01";
          break;
        case 2:
          mes = "02";
          break;
        case 3:
          mes = "03";
          break;
        case 4:
          mes = "04";
          break;
        case 5:
          mes = "05";
          break;
        case 6:
          mes = "06";
          break;
        case 7:
          mes = "07";
          break;
        case 8:
          mes = "08";
          break;
        case 9:
          mes = "09";
          break;
        case 10:
          mes = "10";
          break;
        case 11:
          mes = "11";
          break;
        case 12:
          mes = "12";
          break;
      }
      let dia = "";
      let auxDia = fecha.getDate() + 1;
      if (auxDia.toString().length == 1) {
        dia = "0" + auxDia;
      } else {
        dia = auxDia;
      }

      fechaform = dia + "/" + mes + "/" + fecha.getFullYear();
    }
    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="IngresoEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n IngresoEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKIngresoEmp +
      ',15)" value="' +
      fechaform +
      '" readonly></div>';
  }

  return html;
}

function templateInfonavit(data) {
  var html = "";
  let numFormat = "";
  for (var j = 0; j < data.length; j++) {
    if (data[j].Infonavit !== "" && data[j].Infonavit !== null) {
      numFormat = "$ " + numeral(data[j].Infonavit).format("0,0.00");
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="InfonavitEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n InfonavitEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKInfonavitEmp +
      ',15)" value="' +
      numFormat +
      '" readonly></div>';
  }
  return html;
}

function templateInternalDebt(data) {
  var html = "";
  let numFormat = "";
  for (var j = 0; j < data.length; j++) {
    if (data[j].DeudaInterna !== "" && data[j].DeudaInterna !== null) {
      numFormat = "$ " + numeral(data[j].DeudaInterna).format("0,0.00");
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="DeudaInternaEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n DeudaInternaEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKDeudaInternaEmp +
      ',15)" value="' +
      numFormat +
      '" readonly></div>';
  }
  return html;
}

function templateRemainingDebt(data) {
  var html = "";
  let numFormat = "";
  for (var j = 0; j < data.length; j++) {
    if (data[j].DeudaRestante !== "" && data[j].DeudaRestante !== null) {
      numFormat = "$ " + numeral(data[j].DeudaRestante).format("0,0.00");
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="DeudaRestanteEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n DeudaRestanteEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKDeudaRestanteEmp +
      ',15)" value="' +
      numFormat +
      '" readonly></div>';
  }
  return html;
}

function templateTurn(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    let turno = "";
    if (data[j].Turno != null) {
      turno = data[j].Turno;
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="TurnoEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n TurnoEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKTurnoEmp +
      ',16)" value="' +
      turno +
      '" readonly></div>';
  }

  return html;
}

function templateWorkstation(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    let puesto = "";
    if (data[j].Puesto != null) {
      puesto = data[j].Puesto;
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="PuestoEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n PuestoEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKPuestoEmp +
      ',16)" value="' +
      puesto +
      '" readonly></div>';
  }

  return html;
}

function templateSubsidiary(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    let sucursal = "";
    if (data[j].Locacion != null) {
      sucursal = data[j].Locacion;
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="SucursalEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n SucursalEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKSucursalEmp +
      ',16)" value="' +
      sucursal +
      '" readonly></div>';
  }
  return html;
}

function templateEnterprise(data) {
  var html = "";

  for (var j = 0; j < data.length; j++) {
    let empresa = "";
    if (data[j].NombreComercial != null) {
      empresa = data[j].NombreComercial;
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="EmpresaEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n EmpresaEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKEmpresaEmp +
      ',16)" value="' +
      empresa +
      '" readonly></div>';
  }
  return html;
}

function templateNSS(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].NSS !== null && data[j].NSS !== "") {
      value = data[j].NSS;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="NSSEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n NSSEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKEmpresaEmp +
      ',16)" value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeateBloodType(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].TipoSangre !== null && data[j].TipoSangre !== "") {
      value = data[j].TipoSangre;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="tipoSangreEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n tipoSangreEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKEmpresaEmp +
      ',16)" value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeateContact(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (
      data[j].ContactoEmergencia !== null &&
      data[j].ContactoEmergencia !== ""
    ) {
      value = data[j].ContactoEmergencia;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="contactoEmergenciaEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n contactoEmergenciaEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" onfocusout="editar_elemento(' +
      data[j].PKEmpresaEmp +
      ',16)" value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeateNumberContact(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].NumeroEmergencia !== null && data[j].NumeroEmergencia !== "") {
      value = data[j].NumeroEmergencia;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="numeroEmergenciaEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n numeroEmergenciaEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" ' +
      'value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeateAllergies(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].Alergias !== null && data[j].Alergias !== "") {
      value = data[j].Alergias;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="alergiasEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n alergiasEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" ' +
      'value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeateMedicNotes(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].Notas !== null && data[j].Notas !== "") {
      value = data[j].Notas;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="notasEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n notasEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" ' +
      'value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeateBank(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].Banco !== null && data[j].Banco !== "") {
      value = data[j].Banco;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="notasEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n notasEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" ' +
      'value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeateCountBank(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].CuentaBancaria !== null && data[j].CuentaBancaria !== "") {
      value = data[j].CuentaBancaria;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="notasEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n notasEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" ' +
      'value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeateClabe(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].CLABE !== null && data[j].CLABE !== "") {
      value = data[j].CLABE;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="notasEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n notasEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" ' +
      'value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function templeatenumberCard(data) {
  var html = "";
  let value = "";

  for (var j = 0; j < data.length; j++) {
    if (data[j].NumeroTarjeta !== null && data[j].NumeroTarjeta !== "") {
      value = data[j].NumeroTarjeta;
    } else {
      value = "";
    }

    html +=
      '<div class="hideEmployer b-bottom row-' +
      data[j].PKEmpleado +
      '" id="notasEmpleado-' +
      data[j].PKEmpleado +
      '"><input class="text-center border-n notasEmpleadoTexto-' +
      data[j].PKEmpleado +
      '" type="text" ' +
      'value="' +
      value +
      '" readonly></div>';
  }
  return html;
}

function get_info(array) {
  if (array !== null && array !== "") {
    //console.log("Array columnas: ",array);
    $.ajax({
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "info_columnas", array: array },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta desde get info: ", respuesta);
        //console.log("Infonavit: ",respuesta[0][0]);

        $("#pagination-bar").pagination({
          dataSource: respuesta[0][0],
          pageSize: 50,
          pageRange: 1,
          prevText: "",
          nextText: "",
          className: "paginationjs-theme-timlid paginationjs-big",
          //autoHidePrevious: true,
          //autoHideNext: true,
          callback: function (data, pagination) {
            var idEmployer = templateIdEmployer(data);
            var employer = templateEmployer(data);
            var firstLastName = templateFirstLastName(data);
            var secondLastName = templateSecondLastName(data);
            var stateCivil = templateStateCivil(data);
            var gender = templateGender(data);
            var direction = templateDirection(data);
            var state = templateState(data);
            var city = templateCity(data);
            var colony = templateColony(data);
            var cp = templateCp(data);
            var curp = templateCurp(data);
            var rfc = templateRfc(data);
            var birth = templateBirth(data);
            var phone = templatePhone(data);
            var status = templateStatus(data);
            var admission = templateAdmissionDate(data);
            var infonavit = templateInfonavit(data);
            var internalDebt = templateInternalDebt(data);
            var remainingDebt = templateRemainingDebt(data);
            var turn = templateTurn(data);
            var workstation = templateWorkstation(data);
            var subsidiary = templateSubsidiary(data);
            var enterprise = templateEnterprise(data);
            var nss = templateNSS(data);
            var bloodType = templeateBloodType(data);
            var contact = templeateContact(data);
            var numberContact = templeateNumberContact(data);
            var allergies = templeateAllergies(data);
            var medicNotes = templeateMedicNotes(data);
            var bank = templeateBank(data);
            var countBank = templeateCountBank(data);
            var clabe = templeateClabe(data);
            var numberCard = templeatenumberCard(data);

            $.each(respuesta, function (i) {
              //console.log("direccion:",respuesta[i][0][0].FKColumnasEmp);
              //console.log("longitud respuesta get info:",respuesta[i][0].length);

              if (respuesta[i][0].length > 0) {
                //console.log("prueba: ",respuesta[i][1]);
                if (respuesta[i][1] == 1) {
                  //ID del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    idEmployer
                  );
                }
                if (respuesta[i][1] == 2) {
                  //Nombre del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    employer
                  );
                }
                if (respuesta[i][1] == 3) {
                  //Primer Apellido del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    firstLastName
                  );
                }
                if (respuesta[i][1] == 4) {
                  //Segundo Apellido del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    secondLastName
                  );
                }
                if (respuesta[i][1] == 5) {
                  //Estado civil del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    stateCivil
                  );
                }
                if (respuesta[i][1] == 6) {
                  //Género del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(gender);
                }
                if (respuesta[i][1] == 7) {
                  //Dirección del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    direction
                  );
                }
                if (respuesta[i][1] == 8) {
                  //Estado del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(state);
                }
                if (respuesta[i][1] == 9) {
                  //Ciudad del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(city);
                }
                if (respuesta[i][1] == 10) {
                  //Colonia del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(colony);
                }
                if (respuesta[i][1] == 11) {
                  //CP del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(cp);
                }
                if (respuesta[i][1] == 12) {
                  //CURP del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(curp);
                }
                if (respuesta[i][1] == 13) {
                  //RFC del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(rfc);
                }
                if (respuesta[i][1] == 14) {
                  //Fecha de nacimiento del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(birth);
                }
                if (respuesta[i][1] == 15) {
                  //Telefono del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(phone);
                }
                if (respuesta[i][1] == 16) {
                  //Estatus del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(status);
                }
                if (respuesta[i][1] == 17) {
                  //Fecha de ingresa del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    admission
                  );
                }
                if (respuesta[i][1] == 18) {
                  //Infonavit del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    infonavit
                  );
                }
                if (respuesta[i][1] == 19) {
                  //Deuda interna del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    internalDebt
                  );
                }
                if (respuesta[i][1] == 20) {
                  //Deuda restante del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    remainingDebt
                  );
                }
                if (respuesta[i][1] == 21) {
                  //Turno del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(turn);
                }
                if (respuesta[i][1] == 22) {
                  //Puesto del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    workstation
                  );
                }
                if (respuesta[i][1] == 23) {
                  //Sucursal del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    subsidiary
                  );
                }
                if (respuesta[i][1] == 24) {
                  //Empresa del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    enterprise
                  );
                }
                if (respuesta[i][1] == 25) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(nss);
                }
                if (respuesta[i][1] == 26) {
                  //tipo de sangre del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    bloodType
                  );
                }
                if (respuesta[i][1] == 27) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    contact
                  );
                }
                if (respuesta[i][1] == 28) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    numberContact
                  );
                }
                if (respuesta[i][1] == 29) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    allergies
                  );
                }
                if (respuesta[i][1] == 30) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    medicNotes
                  );
                }
                if (respuesta[i][1] == 31) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(bank);
                }
                if (respuesta[i][1] == 32) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    countBank
                  );
                }
                if (respuesta[i][1] == 33) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(clabe);
                }
                if (respuesta[i][1] == 34) {
                  //NSS del empleado
                  $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                    numberCard
                  );
                }
              }
            });
            var prev = $(".paginationjs-prev a");
            var next = $(".paginationjs-next a");
            prev.html(
              "<img src='../../img/icons/pagination.svg' width='15px' style='-webkit-transform: scaleX(-1); transform=scaleX(-1);'>"
            );
            next.html(
              "<img src='../../img/icons/pagination.svg' width='15px'>"
            );
          },
        });
      },
      error: function (error) {
        console.log(error);
      },
      complete: function (_, __) {
        ocultar();
      },
    });
  } else {
    console.log("El array no se cargo...");
  }
  getSortable();
  dataSortMain();
}

function getSortable() {
  new Sortable(sortableColumns, {
    handle: ".handle",
    animation: 150,
    ghostClass: "blue-background-class",
    dataIdAttr: "data-pos",
    scroll: true,
    bubbleScroll: true,
    store: {
      set: function (sortable) {
        var orden = sortable.toArray();
        //console.log('data-pos en "set": ',sortable.options['dataIdAttr']);
        //console.log("orden array: ");
        //console.log(orden);

        $.ajax({
          url: "php/funciones.php",
          dataType: "json",
          data: {
            clase: "data_order",
            funcion: "columnOrder",
            ordenArray: orden,
          },
          success: function (resp) {
            //console.log(resp);
          },
          error: function (error) {
            console.log(error);
          },
        });
      },
    },
    onMove: function (evt) {
      var data = evt.dragged.dataset.pos;
      //console.log(data);
      if (data != 1) {
        return true;
      } else {
        return false;
      }
    },
  });
}

function unselectColumn(id_column, flag) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "update_data",
      funcion: "update_check_column",
      id_column: id_column,
      flag: flag,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      if (respuesta == "ok") {
        $("#checkType-" + id_column).removeClass("checked-type-column");
        $("#checkType-" + id_column).addClass("check-type-column");

        $("#checkType-" + id_column).removeAttr("onclick");
        $("#checkType-" + id_column).attr(
          "onclick",
          "selectColumn(" + id_column + ",1)"
        );

        $("#col_" + id_column).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function unselectColumnModal(id_column, flag) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "update_data",
      funcion: "update_check_column",
      id_column: id_column,
      flag: flag,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      if (respuesta == "ok") {
        $("#checkType-" + id_column).removeClass("checked");
        //$("#checkType-" + id_column).addClass("check-type-column-modal");

        $("#checkType-" + id_column).removeAttr("onclickColumn");
        $("#checkType-" + id_column).attr(
          "onclick",
          "selectColumnModal(" + id_column + ",1)"
        );

        $("#col_" + id_column).remove();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function selectColumn(id_column, flag) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "update_data",
      funcion: "update_check_column",
      id_column: id_column,
      flag: flag,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("Agregar columnas",respuesta);
      //console.log("id: ",respuesta[0][1]);

      if (respuesta[0][0].length > 0) {
        $("#checkType-" + id_column).removeClass("check-type-column");
        $("#checkType-" + id_column).addClass("checked-type-column");

        $("#checkType-" + id_column).removeAttr("onclick");
        $("#checkType-" + id_column).attr(
          "onclick",
          "unselectColumn(" + id_column + ",0)"
        );

        $("#sortableColumns").append(
          '<div id="col_' +
            respuesta[0][0][0].FKColumnasEmp +
            '" data-pos=' +
            respuesta[0][0][0].FKColumnasEmp +
            ">" +
            '<div class="columna-empleado handle column-header">' +
            "<div>" +
            respuesta[0][2] +
            '</div><div><a class="column-order" id="sort-' +
            respuesta[0][0][0].FKColumnasEmp +
            '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
            "</div>" +
            '<div id="column-' +
            respuesta[0][0][0].FKColumnasEmp +
            '" class="columna-info"></div>' +
            "</div>"
        );
        selectedColumns.push([
          respuesta[0][0][0].FKColumnasEmp,
          respuesta[0][3],
          respuesta[0][1],
        ]);

        get_info(selectedColumns);

        let coord = $("#col_" + respuesta[0][0][0].FKColumnasEmp).offset();
        //console.log("coord", coord);
        // $('#boardContent').animate({scrollLeft: coord.left}, 1000);
        document.getElementById("boardContent").scrollLeft += coord.left;
      } else {
        Swal.fire({
          title: "Datos no cargan",
          html: "No hay datos en la base de datos.",
          icon: "error",
          showConfirmButton: true,
          confirmButtonText: 'Salir  <i class="far fa-times-circle"></i>',
          buttonsStyling: false,
          allowEnterKey: false,
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function selectColumnModal(id_column, flag) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "update_data",
      funcion: "update_check_column",
      id_column: id_column,
      flag: flag,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("Agregar columnas extras: ",respuesta);
      //console.log("id: ",respuesta[0][1]);

      //$("#checkType-" + id_column).removeClass("check-type-column-modal");
      $("#checkType-" + id_column).addClass("checked");

      $("#checkType-" + id_column).removeAttr("onclickColumn");
      $("#checkType-" + id_column).attr(
        "onclick",
        "unselectColumnModal(" + id_column + ",0)"
      );

      $("#sortableColumns").append(
        '<div id="col_' +
          respuesta[0][0][0].FKColumnasEmp +
          '" data-pos=' +
          respuesta[0][0][0].FKColumnasEmp +
          ">" +
          '<div class="columna-empleado handle column-header">' +
          "<div>" +
          respuesta[0][2] +
          '</div><div><a class="column-order" id="sort-' +
          respuesta[0][0][0].FKColumnasEmp +
          '" data-sort="0" href="#"><i class="fas fa-sort"></i></a></div>' +
          "</div>" +
          '<div id="column-' +
          respuesta[0][0][0].FKColumnasEmp +
          '" class="columna-info"></div>' +
          "</div>"
      );
      selectedColumns.push([
        respuesta[0][0][0].FKColumnasEmp,
        respuesta[0][3],
        respuesta[0][1],
      ]);

      get_info(selectedColumns);

      let coord = $("#col_" + respuesta[0][0][0].FKColumnasEmp).offset();
      //console.log("coord", coord);
      // $('#boardContent').animate({scrollLeft: coord.left}, 1000);
      document.getElementById("boardContent").scrollLeft += coord.left;
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function editar_elemento(id_elemento, tipo) {
  /*
	let valor = $('#input-'+id_elemento).val();
	console.log('entra el ajax');
		console.log("valor", valor);
	return;
	$.ajax({
		url:"php/funciones.php",
		data:{clase:"update_data", funcion:"editar_elemento",id_elemento:id_elemento,tipo:tipo},
		dataType:"json",

		success:function(respuesta){
			console.log(respuesta);
		},
		error:function(error){
			console.log(error);
		}
	});
*/
}

/*===============================
=            EVENTOS            =
===============================*/

$(".seleccionarColumna").click(function () {
  varContainer = ".listaColumnas";
  $(".listaColumnas").show();
});

$(document).on("mouseup", function (e) {
  if (!$(e.target).closest(varContainer).length) {
    $(varContainer).hide();
  }

  $("#boardContent").css("overflow", "auto");
});

//Buscador
//evento cuando se presiona una tecla
$("#search-input").keyup(function () {
  mostrar();
  $("#sin-coincidencias").remove();
  if (!$("#search-input").val()) {
    get_info(selectedColumns);
  }
  var valor = $("#search-input").val();
  var res = [];
  //console.log("VALOR DEL INPUT",valor);
  // console.log("VALOR DEL INPUT",selectedColumns);
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "buscar_data",
      funcion: "buscar_empleado",
      usuarioInput: valor,
      Array: selectedColumns,
    },
    dataType: "json",

    success: function (respuesta) {
      //console.log("Busqueda: ",respuesta);

      $("#sin-coincidencias").remove();

      if (respuesta.length > 0) {
        $("#pagination-bar").pagination("destroy");

        $(".hideEmployer").hide();

        $("#pagination-bar").pagination({
          //plugin paginacion en busqueda inicio
          dataSource: respuesta, //datos que se van a paginar
          locator: "0.0",
          pageSize: 50, //numero de datos que se van a mostrar por paginas
          pageRange: 1, //distancia entre la numeracion de paginas
          prevText: "",
          nextText: "",
          className: "paginationjs-theme-timlid paginationjs-big", //estilos que afectan a la barra de paginacion
          callback: function (data, pagination) {
            //callback paginacion en busqueda inicio
            //console.log(respuesta[0][1]);
            for (var i = 0; i < respuesta[0][1].length; i++) {
              //for paginacion para datos en busqueda inicio
              if (i == respuesta[0][1].length - 1) {
                ocultar();
              } else {
                mostrar();
              }
              var idEmployer = templateIdEmployer(data[0][0]); //funcion para traer el id del empleado formateado en html
              var employer = templateEmployer(data[0][0]); //funcion para traer el nombre del empleado formateado en html
              var firstLastName = templateFirstLastName(data[0][0]); //funcion para traer el primer apellido del empleado formateado en html
              var secondLastName = templateSecondLastName(data[0][0]); //funcion para traer el segundo apellido del empleado formateado en html
              var stateCivil = templateStateCivil(data[0][0]); //funcion para traer el estado civil del empleado formateado en html
              var gender = templateGender(data[0][0]);
              var direction = templateDirection(data[0][0]);
              var state = templateState(data[0][0]);
              var city = templateCity(data[0][0]);
              var colony = templateColony(data[0][0]);
              var cp = templateCp(data[0][0]);
              var curp = templateCurp(data[0][0]);
              var rfc = templateRfc(data[0][0]);
              var birth = templateBirth(data[0][0]);
              var phone = templatePhone(data[0][0]);
              var status = templateStatus(data[0][0]);
              var admission = templateAdmissionDate(data[0][0]);
              var infonavit = templateInfonavit(data[0][0]);
              var internalDebt = templateInternalDebt(data[0][0]);
              var remainingDebt = templateRemainingDebt(data[0][0]);
              var turn = templateTurn(data[0][0]);
              var workstation = templateWorkstation(data[0][0]);
              var subsidiary = templateSubsidiary(data[0][0]);
              var enterprise = templateEnterprise(data[0][0]);
              var nss = templateNSS(data[0][0]);
              var bloodType = templeateBloodType(data[0][0]);
              var contact = templeateContact(data[0][0]);
              var numberContact = templeateNumberContact(data[0][0]);
              var allergies = templeateAllergies(data[0][0]);
              var medicNotes = templeateMedicNotes(data[0][0]);

              switch (respuesta[0][1][i][2]) {
                case "1":
                  $("#column-" + respuesta[0][1][i][0]).html(idEmployer);
                  break;
                case "2":
                  $("#column-" + respuesta[0][1][i][0]).html(employer);
                  break;
                case "3":
                  $("#column-" + respuesta[0][1][i][0]).html(firstLastName);
                  break;
                case "4":
                  $("#column-" + respuesta[0][1][i][0]).html(secondLastName);
                  break;
                case "5":
                  $("#column-" + respuesta[0][1][i][0]).html(stateCivil);
                  break;
                case "6":
                  $("#column-" + respuesta[0][1][i][0]).html(gender);
                  break;
                case "7":
                  $("#column-" + respuesta[0][1][i][0]).html(direction);
                  break;
                case "8":
                  $("#column-" + respuesta[0][1][i][0]).html(state);
                  break;
                case "9":
                  $("#column-" + respuesta[0][1][i][0]).html(city);
                  break;
                case "10":
                  $("#column-" + respuesta[0][1][i][0]).html(colony);
                  break;
                case "11":
                  $("#column-" + respuesta[0][1][i][0]).html(cp);
                  break;
                case "12":
                  $("#column-" + respuesta[0][1][i][0]).html(curp);
                  break;
                case "13":
                  $("#column-" + respuesta[0][1][i][0]).html(rfc);
                  break;
                case "14":
                  $("#column-" + respuesta[0][1][i][0]).html(birth);
                  break;
                case "15":
                  $("#column-" + respuesta[0][1][i][0]).html(phone);
                  break;
                case "16":
                  $("#column-" + respuesta[0][1][i][0]).html(status);
                  break;
                case "17":
                  $("#column-" + respuesta[0][1][i][0]).html(admission);
                  break;
                case "18":
                  $("#column-" + respuesta[0][1][i][0]).html(infonavit);
                  break;
                case "19":
                  $("#column-" + respuesta[0][1][i][0]).html(internalDebt);
                  break;
                case "20":
                  $("#column-" + respuesta[0][1][i][0]).html(remainingDebt);
                  break;
                case "21":
                  $("#column-" + respuesta[0][1][i][0]).html(turn);
                  break;
                case "22":
                  $("#column-" + respuesta[0][1][i][0]).html(workstation);
                  break;
                case "23":
                  $("#column-" + respuesta[0][1][i][0]).html(subsidiary);
                  break;
                case "24":
                  $("#column-" + respuesta[0][1][i][0]).html(enterprise);
                  break;
                case "25":
                  $("#column-" + respuesta[0][1][i][0]).html(nss);
                  break;
                case "26":
                  $("#column-" + respuesta[0][1][i][0]).html(bloodType);
                  break;
                case "27":
                  $("#column-" + respuesta[0][1][i][0]).html(contact);
                  break;
                case "28":
                  $("#column-" + respuesta[0][1][i][0]).html(numberContact);
                  break;
                case "29":
                  $("#column-" + respuesta[0][1][i][0]).html(allergies);
                  break;
                case "30":
                  $("#column-" + respuesta[0][1][i][0]).html(medicNotes);
                  break;
              }
            }
            var prev = $(".paginationjs-prev a");
            var next = $(".paginationjs-next a");
            prev.html(
              "<img style='margin:0 4px 1px 0;fill:#006dd9;' src='../../img/Empleados/caret-left-solid.svg' width='9'>"
            );
            next.html(
              "<img style='margin:0 0 1px 4px;fill:#006dd9;' src='../../img/Empleados/caret-right-solid.svg' width='9'>"
            );
          },
        });
      } else {
        $(".hideEmployer").hide();
        $("#boardContent").append(
          '<div id="sin-coincidencias" style="margin:170px 0 100px 0;" class="text-center"><img src="../tareas/timDesk/img/icons/fail.svg" width="80" height="80"></br></br><h1 class="h5 text-blutTim">No se encontraron coincidencias en la búsqueda</h1></div>'
        );
      }

      //$('.claseEtapa').hide()//función remove remueve o quita la información
    },
    error: function (error) {
      console.log("Hubo un error: ", error);
    },
  }).fail((jqXHR, textStatus, errorThrow) => {
    if (jqXHR.status === 0) {
      alert("Not connect: Verify Network.");
    } else if (jqXHR.status == 404) {
      alert("Requested page not found [404]");
    } else if (jqXHR.status == 500) {
      alert("Internal Server Error [500].");
    } else if (textStatus === "parsererror") {
      alert("Requested JSON parse failed.");
    } else if (textStatus === "timeout") {
      alert("Time out error.");
    } else if (textStatus === "abort") {
      alert("Ajax request aborted.");
    } else {
      alert("Uncaught Error: " + jqXHR.responseText);
    }
  });
});

/*=====  End of EVENTOS  ======*/

$(document).ready(function () {
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_ids" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        $(document).on(
          "mouseenter",
          "#idEmpleado-" + respuesta[i].PKEmpleado,
          function () {
            $(".idEmployer-" + respuesta[i].PKEmpleado).css(
              "display",
              "inline-block"
            );
            $(".idEmployer-" + respuesta[i].PKEmpleado).css(
              "text-align",
              "center"
            );
            $(".idEmployer-" + respuesta[i].PKEmpleado).css(
              "margin-left",
              "40px"
            );
            $("#edit-icon-" + respuesta[i].PKEmpleado).css(
              "display",
              "inline-block"
            );
            $("#delete-icon-" + respuesta[i].PKEmpleado).css(
              "display",
              "inline-block"
            );
            $("#edit-icon-" + respuesta[i].PKEmpleado).css("float", "right");
            $("#delete-icon-" + respuesta[i].PKEmpleado).css("float", "right");
          }
        );
      });

      $.each(respuesta, function (i) {
        $(document).on(
          "mouseleave",
          "#idEmpleado-" + respuesta[i].PKEmpleado,
          function () {
            $(".idEmployer-" + respuesta[i].PKEmpleado).css(
              "display",
              "inline-block"
            );
            $(".idEmployer-" + respuesta[i].PKEmpleado).css(
              "text-align",
              "center"
            );
            $(".idEmployer-" + respuesta[i].PKEmpleado).css(
              "margin-left",
              "0px"
            );
            $("#edit-icon-" + respuesta[i].PKEmpleado).css("display", "none");
            $("#delete-icon-" + respuesta[i].PKEmpleado).css("display", "none");
          }
        );
      });

      $.each(respuesta, function (i) {
        $(document).on(
          "mouseenter",
          ".row-" + respuesta[i].PKEmpleado,
          function () {
            $("#idEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#NombreEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".NombreEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#ApellidoPaterno-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".ApellidoPaternotexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#ApellidoMaterno-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".ApellidoMaternotexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#CivilEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".CivilEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#GeneroEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".GenreroEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#DireccionEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".DireccionEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#EstadoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".EstadoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#CiudadEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".CiudadEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#ColoniaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".ColoniaEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#CPEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".CPEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#CurpEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".CurpEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#RfcEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".RfcEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#NacimientoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".NacimientoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#TelefonoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".TelefonoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#EstatusEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".EstatusEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#IngresoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".IngresoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#InfonavitEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".InfonavitEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#DeudaInternaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".DeudaInternaEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#DeudaRestanteEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".DeudaRestanteEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#TurnoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".TurnoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#PuestoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".PuestoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#SucursalEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".SucursalEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#EmpresaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".EmpresaEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#NSSEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".NSSEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#tipoSangreEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".tipoSangreEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#contactoEmergenciaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(
              ".contactoEmergenciaEmpleadoTexto-" + respuesta[i].PKEmpleado
            ).css("background-color", "rgba(192,247,231,3.0)");
            $("#numeroEmergenciaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".numeroEmergenciaEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#alergiasEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".alergiasEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $("#notasEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
            $(".notasEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "rgba(192,247,231,3.0)"
            );
          }
        );
      });
      $.each(respuesta, function (i) {
        $(document).on(
          "mouseleave",
          ".row-" + respuesta[i].PKEmpleado,
          function () {
            $(this).css("background-color", "white");
            $("#idEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#NombreEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".NombreEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#ApellidoPaterno-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".ApellidoPaternotexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#ApellidoMaterno-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".ApellidoMaternotexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#CivilEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".CivilEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#GeneroEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".GenreroEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#DireccionEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".DireccionEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#EstadoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".EstadoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#CiudadEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".CiudadEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#ColoniaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".ColoniaEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#CPEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".CPEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#CurpEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".CurpEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#RfcEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".RfcEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#NacimientoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".NacimientoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#TelefonoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".TelefonoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#EstatusEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".EstatusEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#IngresoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".IngresoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#InfonavitEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".InfonavitEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#DeudaInternaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".DeudaInternaEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#DeudaRestanteEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".DeudaRestanteEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#TurnoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".TurnoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#PuestoEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".PuestoEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#SucursalEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".SucursalEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#EmpresaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".EmpresaEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#NSSEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".NSSEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#tipoSangreEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".tipoSangreEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#contactoEmergenciaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(
              ".contactoEmergenciaEmpleadoTexto-" + respuesta[i].PKEmpleado
            ).css("background-color", "white");
            $("#numeroEmergenciaEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".numeroEmergenciaEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#alergiasEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".alergiasEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $("#notasEmpleado-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
            $(".notasEmpleadoTexto-" + respuesta[i].PKEmpleado).css(
              "background-color",
              "white"
            );
          }
        );
      });

      $.each(respuesta, function (i) {
        $(document).on(
          "click",
          "#edit-tabs-" + respuesta[i].PKEmpleado,
          function () {
            var data = $(this).data("id");

            window.location.href =
              "functions/editar_Empleado.php?idEmpleadoU=" + data;
          }
        );
      });

      $.each(respuesta, function (i) {
        $(document).on(
          "click",
          "#delete-tabs-" + respuesta[i].PKEmpleado,
          function () {
            var id = $(this).data("id");

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
                title: "¿Desea dar de baja este empleado?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText:
                  '<span class="verticalCenter2">Baja empleado</span>',
                cancelButtonText:
                  '<span class="verticalCenter2">Cancelar</span>',
                reverseButtons: true,
              })
              .then((result) => {
                if (result.isConfirmed) {
                  $.ajax({
                    url: "functions/dar_BajaEmpleado.php",
                    type: "POST",
                    data: {
                      idEmpleadoB: id,
                    },
                    success: function (data, status, xhr) {
                      /*  $("#idEmpleado-" + id).css("display","none");
                        $("#NombreEmpleado-" + id).css("display","none");
                        $("#ApellidoPaterno-" + id).css("display","none");
                        $("#ApellidoMaterno-" + id).css("display","none");
                        $("#CivilEmpleado-" + id).css("display","none");
                        $("#EstadoEmpleado-" + id).css("display","none");
                        $("#CiudadEmpleado-" + id).css("display","none");
                        $("#CPEmpleado-" + id).css("display","none");
                        $("#CurpEmpleado-" + id).css("display","none");
                        $("#RfcEmpleado-" + id).css("display","none");
                        $("#NacimientoEmpleado-" + id).css("display","none");
                        $("#TelefonoEmpleado-" + id).css("display","none");
                        $("#EstatusEmpleado-" + id).css("display","none");
                        $("#IngresoEmpleado-" + id).css("display","none");
                        $("#InfonavitEmpleado-" + id).css("display","none");
                        $("#PuestoEmpleado-" + id).css("display","none");
                        $("#alergiasEmpleado-" + id).css("display","none");
                        $("#notasEmpleado-" + id).css("display","none");
                        $("#GeneroEmpleado-" + id).css("display","none");
                        $("#DireccionEmpleado-" + id).css("display","none");
                        $("#ColoniaEmpleado-" + id).css("display","none");
                        $("#DeudaInternaEmpleado-" + id).css("display","none");
                        $("#DeudaRestanteEmpleado-" + id).css("display","none");
                        $("#TurnoEmpleado-" + id).css("display","none");
                        $("#NSSEmpleado-" + id).css("display","none");
                        $("#SucursalEmpleado-" + id).css("display","none");
                        $("#contactoEmergenciaEmpleado-" + id).css("display","none");
                        $("#numeroEmergenciaEmpleado-" + id).css("display","none");
                        $("#notasEmpleado-" + id).css("display","none");
                        $("#tipoSangreEmpleado-" + id).css("display","none");
                        $("#notasEmpleado-" + id).css("display","none");
                        $("#notasEmpleado-" + id).css("display","none");   
                        $("#notasEmpleado-" + id).css("display","none");   */
                      get_info(selectedColumns);

                      /* if (data == "exito") {
                          
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
                          $("NombreEmpleado-" + id).hide();
                        $("idEmpleado-" + id).hide(); 
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
                        }*/
                    },
                  });
                } else if (
                  /* Read more about handling dismissals below */
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                }
              });
            /*
            window.location.href =
              "functions/editar_Empleado.php?idEmpleadoU=" + data;
*/
            //
            //alert('Pendiente de desarrollo y diseño\n'+$(this).data("id"));
            //console.log($(this).data("id"));
          }
        );
      });
    },
  });

  $(document).on("click", "#btnAddEmployer", function () {
    window.location.href = "functions/agregar_Empleado.php";
  });
  //dataSortMain();

  $("#importExcel").prop("disabled", true);

  $("#dataexcel").on("change", () => {
    if (!$("#dataexcel").val()) {
      $("#importExcel").prop("disabled", true);
    } else {
      $("#importExcel").prop("disabled", false);
    }
  });
});

function dataSortMain() {
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "orden_columnas" },
    dataType: "json",
    success: function (respuesta) {
      //("respuesta desde sort: ",respuesta);

      $.each(respuesta, function (i) {
        //console.log("la columna es: ",respuesta[i].PKColumnasEmp);
        //$('#sort-'+respuesta[i].PKColumnasEmp).data('sort','0');

        $("#sort-" + respuesta[i].PKColumnasEmp).on("click", function () {
          var idDom = $("#sort-" + respuesta[i].PKColumnasEmp);
          var indice = respuesta[i].PKColumnasEmp;

          switch (respuesta[i].PKColumnasEmp) {
            case 1:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 2:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 3:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 4:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 5:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 6:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 7:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 8:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 9:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 10:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 11:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 12:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 13:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 14:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 15:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 16:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 17:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 18:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 19:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 20:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 21:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 22:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 23:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 24:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 25:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 26:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 27:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 28:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 29:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;

            case 30:
              selectColumnSort(idDom, indice);
              formatColumnSort(respuesta, indice);
              break;
          }

          var texto = "";
          for (var j = 0; j < respuesta.length; j++) {
            texto +=
              j +
              1 +
              " Table: " +
              respuesta[j].Nombre +
              " .- " +
              $("#sort-" + respuesta[j].PKColumnasEmp).data("sort") +
              "\n";
          }
          //console.log("data despues de actualizar: \n"+texto);
        });
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function selectColumnSort(idDom, indice) {
  if (idDom.data("sort") === 0) {
    idDom.data("sort", 1);
    var sort = idDom.data("sort");
    //console.log("Data desde sort-"+indice+": "+sort);
  } else {
    idDom.data("sort", 0);
    var sort = idDom.data("sort");
    //console.log("Data desde sort-"+indice+": "+sort);
  }
  dataSort(sort, indice, selectedColumns);
}

function formatColumnSort(array, indice) {
  //console.log("valor de indice en formatear sort: "+indice);

  $.each(array, function (i) {
    if (array[i].PKColumnasEmp !== indice) {
      $("#sort-" + array[i].PKColumnasEmp).data("sort", 0);
    }
  });
}

function mostrar() {
  $("#loader").fadeIn("slow");
}

function ocultar() {
  $("#loader").fadeOut("slow");
}

function dataSort(sort, indice, array) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "orden_datos",
      sort: sort,
      indice: indice,
      array: array,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("Desde sort data: ",respuesta);
      //console.log("Array desde sort data: ",respuesta[0][0]);

      $("#pagination-bar").pagination("destroy");

      $("#pagination-bar").pagination({
        dataSource: respuesta[0][0],
        pageSize: 50,
        pageRange: 1,
        prevText: "",
        nextText: "",
        className: "paginationjs-theme-timlid paginationjs-big",
        //autoHidePrevious: true,
        //autoHideNext: true,
        callback: function (data, pagination) {
          var idEmployer = templateIdEmployer(data);
          var employer = templateEmployer(data);
          var firstLastName = templateFirstLastName(data);
          var secondLastName = templateSecondLastName(data);
          var stateCivil = templateStateCivil(data);
          var gender = templateGender(data);
          var direction = templateDirection(data);
          var state = templateState(data);
          var city = templateCity(data);
          var colony = templateColony(data);
          var cp = templateCp(data);
          var curp = templateCurp(data);
          var rfc = templateRfc(data);
          var birth = templateBirth(data);
          var phone = templatePhone(data);
          var status = templateStatus(data);
          var admission = templateAdmissionDate(data);
          var infonavit = templateInfonavit(data);
          var internalDebt = templateInternalDebt(data);
          var remainingDebt = templateRemainingDebt(data);
          var turn = templateTurn(data);
          var workstation = templateWorkstation(data);
          var subsidiary = templateSubsidiary(data);
          var enterprise = templateEnterprise(data);
          var nss = templateNSS(data);
          var bloodType = templeateBloodType(data);
          var contact = templeateContact(data);
          var numberContact = templeateNumberContact(data);
          var allergies = templeateAllergies(data);
          var medicNotes = templeateMedicNotes(data);

          $.each(respuesta, function (i) {
            //console.log("prueba: ",respuesta[i][1]);
            if (respuesta[i][1] == 1) {
              //ID del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(idEmployer);
            }
            if (respuesta[i][1] == 2) {
              //Nombre del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(employer);
            }
            if (respuesta[i][1] == 3) {
              //Primer Apellido del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                firstLastName
              );
            }
            if (respuesta[i][1] == 4) {
              //Segundo Apellido del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                secondLastName
              );
            }
            if (respuesta[i][1] == 5) {
              //Estado civil del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(stateCivil);
            }
            if (respuesta[i][1] == 6) {
              //Género del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(gender);
            }
            if (respuesta[i][1] == 7) {
              //Dirección del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(direction);
            }
            if (respuesta[i][1] == 8) {
              //Estado del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(state);
            }
            if (respuesta[i][1] == 9) {
              //Ciudad del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(city);
            }
            if (respuesta[i][1] == 10) {
              //Colonia del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(colony);
            }
            if (respuesta[i][1] == 11) {
              //CP del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(cp);
            }
            if (respuesta[i][1] == 12) {
              //CP del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(curp);
            }
            if (respuesta[i][1] == 13) {
              //CP del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(rfc);
            }
            if (respuesta[i][1] == 14) {
              //CP del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(birth);
            }
            if (respuesta[i][1] == 15) {
              //CP del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(phone);
            }
            if (respuesta[i][1] == 16) {
              //CP del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(status);
            }
            if (respuesta[i][1] == 17) {
              //Fecha de ingreso del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(admission);
            }
            if (respuesta[i][1] == 18) {
              //Infonavit del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(infonavit);
            }
            if (respuesta[i][1] == 19) {
              //Deuda interna del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                internalDebt
              );
            }
            if (respuesta[i][1] == 20) {
              //Deuda restante del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                remainingDebt
              );
            }
            if (respuesta[i][1] == 21) {
              //turno del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(turn);
            }
            if (respuesta[i][1] == 22) {
              //Puesto del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                workstation
              );
            }
            if (respuesta[i][1] == 23) {
              //Sucursal del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(subsidiary);
            }
            if (respuesta[i][1] == 24) {
              //Empresa del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(enterprise);
            }
            if (respuesta[i][1] == 25) {
              //NSS del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(nss);
            }
            if (respuesta[i][1] == 26) {
              //Tipo de sangre del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(bloodType);
            }
            if (respuesta[i][1] == 27) {
              //contacto de emergencia del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(contact);
            }
            if (respuesta[i][1] == 28) {
              //numero de emergencia del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(
                numberContact
              );
            }
            if (respuesta[i][1] == 29) {
              //alergias del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(allergies);
            }
            if (respuesta[i][1] == 30) {
              //notas medicas del empleado
              $("#column-" + respuesta[i][0][0].FKColumnasEmp).html(medicNotes);
            }
          });
          var prev = $(".paginationjs-prev a");
          var next = $(".paginationjs-next a");
          prev.html(
            "<img style='margin:0 4px 1px 0;fill:#006dd9;' src='../../img/Empleados/caret-left-solid.svg' width='9'>"
          );
          next.html(
            "<img style='margin:0 0 1px 4px;fill:#006dd9;' src='../../img/Empleados/caret-right-solid.svg' width='9'>"
          );
        },
      });
    },
  });
}
