//include('');
//document.write('<script src="../../../js/validaciones.js"></script>');
include("../../../js/validaciones.js");
include("../../../js/numeral.min.js");

function include(archivo) {
  var s = document.createElement("script");
  s.src = archivo;
  document.querySelector("head").appendChild(s);
}

function cargarEstadoCivil(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_estadoCivilSeleccionable" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEstadoCivil) {
          selected = "selected";
        } else {
          selected = "";
        }
        html +=
          '<option value="' +
          respuesta[i].PKEstadoCivil +
          '" ' +
          selected +
          ">" +
          respuesta[i].EstadoCivil +
          "</option>";
      });

      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarTurnos(data, input) {
  var html = "";
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_turnos" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTurno) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKTurno +
          '" ' +
          selected +
          ">" +
          respuesta[i].Turno +
          "</option>";
      });

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarLocaciones(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_locaciones" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta locaciones:", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].sucursal +
          "</option>";
      });

      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarPuestos(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_puestos" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta puestos:", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].puesto +
          "</option>";
      });

      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarPeriodos(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_periodos" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta puestos:", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKPeriodo_pago) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKPeriodo_pago +
          '" ' +
          selected +
          ">" +
          respuesta[i].Periodo +
          "</option>";
      });

      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarEmpresas(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_empresas" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta empresas:", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEmpresa) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKEmpresa +
          '" ' +
          selected +
          ">" +
          respuesta[i].NombreComercial +
          "</option>";
      });

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarTipoContrato(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_tipo_contrato" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo Contrato:", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].tipo_contrato +
          "</option>";
      });

      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarAreaDepartamento(data, input) {
  $("#" + input + "").append("");
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_area_departamento" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data == respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].nombre +
          "</option>";
      });

      $("#" + input + "").append(html);
      var slimSelectAreaDep = new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
        addable: function (value) {
          if (value === "") {
            return false;
          }
          newAreaDepartamento(value, slimSelectAreaDep);
        },
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function newAreaDepartamento(value, slimSelect) {
  $.ajax({
    url: "../php/funciones.php",
    data: {
      name: value,
      clase: "save_data",
      funcion: "nueva_area_departamento",
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta.status === "success") {
        cargarAreaDepartamento(respuesta.id, "cmbAreaDepartamento");
        slimSelect.destroy();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarRiesgoPuesto(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_riesgo_puesto" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta riesgo puesto:", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].riesgo_puesto +
          "</option>";
      });

      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarTipoRegimen(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_tipo_regimen" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tipo Contrato:", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].tipo_regimen +
          "</option>";
      });

      //console.log("Array estado civil",civilState);

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarEstadosFederativos(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_estadosFederativos" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta estados federados: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEstado) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          selected +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarClaveRegimenFiscal(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_RegimenFiscal" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta estados federados: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].clave + ' - ' + respuesta[i].descripcion +
          "</option>";
      });

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarRolesInic(input) {
  var html = "";
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_roles_empleados" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta roles: ", respuesta);

      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          ">" +
          respuesta[i].tipo +
          "</option>";
      });
      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      //console.log(error);
    },
  });
}

function cargarGenero(data, input) {
  var html = "";
  var selectedM = "",
    selectedF = "";

  if (data == "Masculino") {
    selectedM = "selected";
  }
  if (data == "Femenino") {
    selectedF = "selected";
  }

  html += '<option value="Masculino" ' + selectedM + ">Masculino</option>";
  html += '<option value="Femenino" ' + selectedF + ">Femenino</option>";

  $("#" + input + "").append(html);
  new SlimSelect({
    select: "#" + input,
    deselectLabel: '<span class="">✖</span>',
  });
}

function cargarTipoSangre(data, input) {
  var html = "";
  var a1 = "",
    a2 = "",
    b1 = "",
    b2 = "",
    ab1 = "",
    ab2 = "",
    o1 = "",
    o2 = "";

  if (data == "A+") {
    a1 = "selected";
  }
  if (data == "A-") {
    a2 = "selected";
  }
  if (data == "B+") {
    b1 = "selected";
  }
  if (data == "B-") {
    b2 = "selected";
  }
  if (data == "AB+") {
    ab1 = "selected";
  }
  if (data == "AB-") {
    ab2 = "selected";
  }
  if (data == "O+") {
    o1 = "selected";
  }
  if (data == "O-") {
    o2 = "selected";
  }

  html += '<option value="A+" ' + a1 + ">A+</option>";
  html += '<option value="A-" ' + a2 + ">A-</option>";
  html += '<option value="B+" ' + b1 + ">B+</option>";
  html += '<option value="B-" ' + b2 + ">B-</option>";
  html += '<option value="AB+" ' + ab1 + ">AB+</option>";
  html += '<option value="AB-" ' + ab2 + ">AB-</option>";
  html += '<option value="O+" ' + o1 + ">O+</option>";
  html += '<option value="O-" ' + o2 + ">O-</option>";

  $("#" + input + "").append(html);
  new SlimSelect({
    select: "#" + input,
    deselectLabel: '<span class="">✖</span>',
  });
}

function cargarBancos(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_bancos" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta bancos: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKBanco) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKBanco +
          '" ' +
          selected +
          ">" +
          respuesta[i].Clave +
          " - " +
          respuesta[i].Banco +
          "</option>";
      });

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarRolesEmpleados(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_roles_empleados" },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta roles: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].tipo +
          "</option>";
      });
      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarMetodosPago(data, input) {
  var html = "";
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_forma_pago" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          selected +
          ">" +
          respuesta[i].clave + ' - ' + respuesta[i].descripcion +
          "</option>";
      });

      $("#" + input + "").append(html);
      new SlimSelect({
        select: "#" + input,
        deselectLabel: '<span class="">✖</span>',
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function CargarDatosPersonales() {
  resetTabs("#CargarDatosPersonales");
  /* $("#CargarDatosPersonales").css({ top: "15px" });
  $("#CargarDatosLaborales").css("top", "0");
  $("#CargarDatosMedicos").css("top", "0");
  $("#CargarDatosBancarios").css("top", "0"); */
  cargarEstadoCivil("", "cmbEstadoCivil");
  //cargarEstatusEmpleado("", "cmbEstatus");
  cargarEstadosFederativos("", "cmbEstados");
  cargarRolesInic("rolInicial");
  cargarClaveRegimenFiscal("", "cmbClaveRegimenFiscal");

  var html = `
  <div class="card shadow mb-4">
  <div class="card-body">
    <div class="row">
      <div class="col-lg-12">
        <form id="formDatosPersonales">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-2">
                <label for="usr">Nombre(s):*</label>
                <input type="text" maxlength="20" class="form-control alpha-only" id="txtNombre" name="txtNombre" required onkeyup="validEmptyInput('txtNombre', 'invalid-nombreEmp', 'El empleado debe tener un nombre.')">
                <div class="invalid-feedback" id="invalid-nombreEmp">El empleado debe tener un nombre.</div>
              </div>
              <div class="col-lg-2">
                <label for="usr">Primer apellido:*</label>
                <input type="text" maxlength="20" class="form-control alpha-only" name="txtApellidoPaterno" id="txtApellidoPaterno" required onkeyup="validEmptyInput('txtApellidoPaterno', 'invalid-paternoEmp', 'El empleado debe tener un apellido paterno.')">
                <div class="invalid-feedback" id="invalid-paternoEmp">El empleado debe tener un apellido paterno.</div>
              </div>
              <div class="col-lg-2">
                <label for="usr">Segundo apellido:</label>
                <input type="text" maxlength="20" class="form-control alpha-only" name="txtApellidoMaterno">
              </div>
              <div class="col-lg-2">
                <label for="txtEstadoCivil">Estado Civil:</label><br>
                <select class="form-control" name="cmbEstadoCivil" id="cmbEstadoCivil" required>
                  <option disabled selected>Seleccione un estado civil...</option>
                </select>
                <div class="invalid-feedback" id="invalid-civilEmp" style="display: none;">El empleado debe tener un estado civil.</div>
              </div>
              <div class="radio col-lg-2">
                <label for="txtSexo">Genero:*</label><br>
                <select class="form-control" name="cmbSexo" id="cmbSexo" required>
                  <option disabled selected>Seleccione un genero...</option>
                </select>
                <div class="invalid-feedback" id="invalid-generoEmp">El empleado debe tener un genero.</div>
              </div>
              <div class="radio col-lg-2">
                <label for="txtSexo">Rol inicial:*</label><br>
                <select class="form-control" name="rolInicial" id="rolInicial" required onchange="validEmptyInput('rolInicial', 'invalid-rolInic', 'El empleado debe tener un rol.')">
                  <option disabled selected>Seleccione un rol...</option>
                </select>
                <div class="invalid-feedback" id="invalid-rolInic">El empleado debe tener un rol.</div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-2">
                <label for="usr">CURP:</label>
                <input type="text" maxlength="18" class="form-control alphaNumericNDot-only upperCaseletter" name="txtCURP" id="txtCURP" required>
                <div class="invalid-feedback" id="invalid-MalCURP">El CURP esta mal formado.</div>
              </div>
              <div class="col-lg-2">
                <label for="usr">RFC:</label>
                <input type="text" maxlength="13" class="form-control alphaNumericNDot-only upperCaseletter" name="txtRFC" id="txtRFC">
                <div class="invalid-feedback" id="invalid-MalRFC">El RFC esta mal formado.</div>
              </div>
              <div class="col-lg-2">
                <label for="usr">Fecha de nacimiento:</label>
                <input type="date" name="txtFecha" id="txtFecha" class="form-control" step="1" required>
                <div class="invalid-feedback" id="invalid-fechaEmp">El empleado debe tener una fecha de nacimiento.</div>
              </div>
              <div class="col-lg-2">
                <label for="usr">Teléfono:</label>
                <input type="text" maxlength="10" class="form-control numeric-only" name="txtTelefono" id="txtTelefono" required>
                <div class="invalid-feedback" id="invalid-telefonoEmp">El empleado debe tener un número de teléfono.</div>
              </div>
              <div class="col-lg-4">
                <label for="usr">Email:</label>
                <input type="email" maxlength="50" class="form-control" name="txtEmail" id="txtEmail" required>
                <div class="invalid-feedback" id="invalid-Email">El email no es válido.</div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4">
                <label for="usr">Calle:</label>
                <input type="text" maxlength="100" class="form-control alphaNumericNDot-only" name="txtCalle" id="txtCalle" required>
                <div class="invalid-feedback" id="invalid-calleEmp">El domicilio del empleado debe tener una calle.</div>
              </div>
              <div class="col-lg-1">
                <label for="usr">No. Exterior:</label>
                <input type="text" maxlength="5" class="form-control numeric-only" name="txtNumeroExterior" id="txtNumeroExterior" required>
                <div class="invalid-feedback" id="invalid-exteriorEmp">El domicilio del empleado debe tener un no. Exterior.</div>
              </div>
              <div class="col-lg-1">
                <label for="usr">Interior:</label>
                <input type="text" maxlength="5" class="form-control alphaNumericNDot-only" name="txtNumeroInterior">
              </div>
              <div class="col-lg-4">
                <label for="usr">Colonia:</label>
                <input type="text" maxlength="20" class="form-control alpha-only" name="txtColonia" id="txtColonia" required>
                <div class="invalid-feedback" id="invalid-coloniaEmp">El domicilio del empleado debe tener una colinia.</div>
              </div>
              <div class="col-lg-2">
                <label for="usr">Código Postal:*</label>
                <input type="text" maxlength="5" class="form-control numeric-only" name="txtCodigoPostal" id="txtCodigoPostal" required>
                <div class="invalid-feedback" id="invalid-CPEmp">El domicilio del empleado debe tener un CP.</div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-lg-4">
                <label for="usr">Ciudad:</label>
                <input type="text" maxlength="20" class="form-control alphaNumericNDot-only" name="txtCiudad" id="txtCiudad" required>
                <div class="invalid-feedback" id="invalid-ciudadEmp">El domicilio del empleado debe tener una ciudad.</div>
              </div>
              <div class="col-lg-4">
                <label for="usr">Estado:*</label>
                <select name="cmbEstados" class="form-control" id="cmbEstados" required>
                  <option disabled selected>Seleccione un estado...</option>
                </select>
                <div class="invalid-feedback" id="invalid-estadoEmp">El empleado debe tener un estado.</div>
              </div>
              <div class="col-lg-4">
                <label for="usr">Régimen fiscal:</label>
                <select name="cmbClaveRegimenFiscal" class="form-control" id="cmbClaveRegimenFiscal" required>
                  <option value='0' selected>Seleccione una clave del régimen fiscal...</option>
                </select>
              </div>
            </div>
          </div>
        </form>
        <button class="btn-custom btn-custom--blue float-right" id="btnAgregarPersonales">Agregar</button>
        <label for="">* Campos requeridos </label>
      </div>
    </div>
  </div>
</div>`;

  $("#datos").append(html);
  cargarGenero("", "cmbSexo");
}

$(document).on("change", "#cmbEstadoCivil", function () {
  validEmptyInput(
    "cmbEstadoCivil",
    "invalid-civilEmp",
    "El empleado debe tener un estado civil."
  );
});

$(document).on("change", "#txtFecha", function () {
  validEmptyInput(
    "txtFecha",
    "invalid-fechaEmp",
    "El empleado debe tener una fecha de nacimiento."
  );
});

$(document).on("change", "#cmbSexo", function () {
  validEmptyInput(
    "cmbSexo",
    "invalid-generoEmp",
    "El empleado debe tener un genero."
  );
});

$(document).on("change", "#cmbEstados", function () {
  validEmptyInput(
    "cmbEstados",
    "invalid-estadoEmp",
    "El empleado debe tener un estado."
  );
});

$(document).on("change", "#cmbEstatus", function () {
  /* validEmptyInput(
    "cmbEstatus",
    "invalid-kjhdfksdkafsdjklh",
    "El domicilio del empleado debe tener un estado."
  ); */
});

function curpValida(curp) {
  var curpM = curp.toUpperCase();
  var re =
      /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
    validado = curpM.match(re);

  if (!validado)
    //Coincide con el formato general?
    return false;

  //Validar que coincida el dígito verificador
  function digitoVerificador(curp17) {
    //Fuente https://consultas.curp.gob.mx/CurpSP/
    var diccionario = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
      lngSuma = 0.0,
      lngDigito = 0.0;
    for (var i = 0; i < 17; i++)
      lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
    lngDigito = 10 - (lngSuma % 10);
    if (lngDigito == 10) return 0;
    return lngDigito;
  }

  if (validado[2] != digitoVerificador(validado[1])) return false;

  return true; //Validado
}

function rfcValido(rfc, aceptarGenerico = true) {
  var rfcM = rfc.toUpperCase();
  const re =
    /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
  var validado = rfcM.match(re);

  if (!validado)
    //Coincide con el formato general del regex?
    return false;

  //Separar el dígito verificador del resto del RFC
  const digitoVerificador = validado.pop(),
    rfcSinDigito = validado.slice(1).join(""),
    len = rfcSinDigito.length,
    //Obtener el digito esperado
    diccionario = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
    indice = len + 1;
  var suma, digitoEsperado;

  if (len == 12) suma = 0;
  else suma = 481; //Ajuste para persona moral

  for (var i = 0; i < len; i++)
    suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);
  digitoEsperado = 11 - (suma % 11);
  if (digitoEsperado == 11) digitoEsperado = 0;
  else if (digitoEsperado == 10) digitoEsperado = "A";

  //El dígito verificador coincide con el esperado?
  // o es un RFC Genérico (ventas a público general)?
  if (
    digitoVerificador != digitoEsperado &&
    (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000")
  )
    return false;
  else if (
    !aceptarGenerico &&
    rfcSinDigito + digitoVerificador == "XEXX010101000"
  )
    return false;
  return rfcSinDigito + digitoVerificador;
}

$(document).on("click", "#btnAgregarPersonales", function () {
  if (!$("#txtNombre").val()) {
    $("#invalid-nombreEmp").css("display", "block");
    $("#txtNombre").addClass("is-invalid");
  }
  if (!$("#txtApellidoPaterno").val()) {
    $("#invalid-paternoEmp").css("display", "block");
    $("#txtApellidoPaterno").addClass("is-invalid");
  }
  if (!$("#cmbSexo").val()) {
    $("#invalid-generoEmp").css("display", "block");
    $("#cmbSexo").addClass("is-invalid");
  }
  if (!$("#rolInicial").val()) {
    $("#invalid-rolInic").css("display", "block");
    $("#rolInicial").addClass("is-invalid");
  }
  if (!$("#txtCodigoPostal").val()) {
    $("#invalid-CPEmp").css("display", "block");
    $("#txtCodigoPostal").addClass("is-invalid");
  }
  if (!$("#cmbEstados").val()) {
    $("#invalid-estadoEmp").css("display", "block");
    $("#cmbEstados").addClass("is-invalid");
  }

  var badNombreEmp =
    $("#invalid-nombreEmp").css("display") === "block" ? false : true;
  var badPaternoEmp =
    $("#invalid-paternoEmp").css("display") === "block" ? false : true;
  var badGeneroEmp =
    $("#invalid-generoEmp").css("display") === "block" ? false : true;
  var badRolInicEmp =
    $("#invalid-rolInicEmp").css("display") === "block" ? false : true;
  var badCURP = $("#invalid-MalCURP").css("display") === "block" ? false : true;
  var badRFC = $("#invalid-MalRFC").css("display") === "block" ? false : true;
  var badEmail = $("#invalid-Email").css("display") === "block" ? false : true;
  var badCP = $("#invalid-CPEmp").css("display") === "block" ? false : true;
  var badEstado = $("#invalid-estadoEmp").css("display") === "block" ? false : true;

  if (
    badNombreEmp &&
    badPaternoEmp &&
    badGeneroEmp &&
    badRolInicEmp &&
    badCURP &&
    badRFC &&
    badEmail &&
    badCP &&
    badEstado
  ) {
    var data = {};
    $.each($("#formDatosPersonales").serializeArray(), function (i, element) {
      data[element.name] = element.value;
    });

    $("#btnAgregarPersonales").prop("disabled", true);

    $.ajax({
      url: "../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "guardar_datosPersonales",
        datos: data,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/checkmark.svg",
            msg: "¡Datos personales registrados correctamente!",
          });

          SeguirDatosLaborales(respuesta[0].id);
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
          $("#btnAgregarPersonales").prop("disabled", false);
        }

        if (respuesta[0].estatus_api == 'fallo-legal' || respuesta[0].estatus_api == 'fallo-rfc' || respuesta[0].estatus_api == 'fallo-zip' || respuesta[0].estatus_api == 'fallo-system') {
          let mensajeEstatusApi = '';

          if (respuesta[0].estatus_api == 'fallo-legal'){
            mensajeEstatusApi = 'El nombre no coincide con el RFC';
          }
          if (respuesta[0].estatus_api == 'fallo-rfc'){
            mensajeEstatusApi = 'RFC inválido';
          }
          if (respuesta[0].estatus_api == 'fallo-system'){
            mensajeEstatusApi = 'El Régimen Fiscal debe ser [601, 603, 605, 606, 607, 608, 610, 611, 612, 614, 615, 616, 620, 621, 622, 623, 624, 625, 626].';
          }
          if (respuesta[0].estatus_api == 'fallo-zip'){
            mensajeEstatusApi = 'El Código postal no coincide con el RFC.';
          }

          mensajeEstatusApi = mensajeEstatusApi + "<br> No se dió de alta el empleado para facturación, pero puedes editarlo para agregarlo."

          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: mensajeEstatusApi,
          });
        }

        

        if (respuesta[0].estatus_api == 'exito') {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/checkmark.svg",
            msg: "El empleado se dio de alta para facturación.",
          });
        }
        
      },
      error: function (error) {
        console.log(error);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
        });
        $("#btnAgregarPersonales").prop("disabled", false);
      },
    });
  }
});

function regresarEmpleados() {
  window.location.href = "../index.php";
}

function SeguirDatosLaborales(id) {
  resetTabs("#CargarDatosLaborales");
  /* $("#CargarDatosPersonales").css({ top: "0" });
  $("#CargarDatosLaborales").css("top", "15px");
  $("#CargarDatosMedicos").css("top", "0");
  $("#CargarDatosBancarios").css("top", "0"); */

  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_nombreCompleto", datos: id },
    dataType: "json",
    success: function (respuesta) {
      cargarTurnos("", "cmbTurno");
      cargarLocaciones("", "cmbLocacion");
      cargarPuestos("", "cmbPuesto");
      cargarEmpresas("", "cmbEmpresa");
      cargarPeriodos("", "cmbPeriodo");
      cargarTipoContrato("", "cmbTipoContrato");
      cargarAreaDepartamento("", "cmbAreaDepartamento");
      cargarRiesgoPuesto("", "cmbRiesgoPuesto");
      cargarTipoRegimen("", "cmbRegimen");
      cargarMetodosPago("", "cmbFormaPago");

      var html = `<div class="card shadow mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="formDatosAgregarLaborales">
              <div class="row">
                <div class="col-lg-12">
                  <input type="hidden" name="txtIdEmpleado" value="${id}">
                </div>
                <div class="col-lg-12" style="text-align: center;">
                  <h4>${respuesta}</h4>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-3">
                    <label for="txtfechaIngreso">Fecha de Ingreso:*</label>
                    <input type="date" class="form-control" id="txtfechaIngreso" name="txtfechaIngreso" step="1" required>
                    <div class="invalid-feedback" id="invalid-fechaIngEmp">El empleado debe tener una fecha de ingreso.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Tipo de contrato:*</label>
                    <select class="form-control" name="cmbTipoContrato" id="cmbTipoContrato" required>
                      <option disabled>Seleccione un tipo de contrato...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-TipoContrato">El empleado debe tener un tipo de contrato.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Puesto:*</label>
                    <select class="form-control" name="cmbPuesto" id="cmbPuesto" required>
                      <option disabled selected>Seleccione un puesto...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-puestoEmp">El empleado debe tener un puesto.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Área/Departamento:*</label>
                    <select class="form-control" name="cmbAreaDepartamento" id="cmbAreaDepartamento" required>
                      <option disabled>Seleccione un área o departamento...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-areaDepartamentoEmp">El empleado debe tener un área o departamento.</div>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <div class="row">
                <div class="col-lg-3">
                    <label for="usr">Riesgo del puesto:*</label>
                    <select class="form-control" name="cmbRiesgoPuesto" id="cmbRiesgoPuesto" required>
                      <option disabled>Seleccione un riesgo del puesto...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-riegopuestoEmp">El empleado debe tener un riesgo del puesto.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Régimen:*</label>
                    <select class="form-control" name="cmbRegimen" id="cmbRegimen" required>
                      <option disabled>Seleccione un régimen...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-regimenEmp">El empleado debe tener un régimen laboral.</div>
                  </div>
                  <div class="col-lg-3">
                      <label for="usr">Turno:*</label>
                      <select class="form-control" name="cmbTurno" id="cmbTurno" required>
                        <option disabled selected>Seleccione un turno...</option>
                      </select>
                      <div class="invalid-feedback" id="invalid-turnoEmp">El empleado debe tener un turno laboral.</div>
                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Sucursal:*</label>
                      <select class="form-control" name="cmbLocacion" id="cmbLocacion" required>
                        <option disabled selected>Seleccione una local...</option>
                      </select>
                      <div class="invalid-feedback" id="invalid-areaEmp">El empleado debe tener una sucursal.</div>
                    </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-lg-3">
                    <label for="usr">Sueldo:*</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="number" class="form-control numeric-only" id="txtSueldo" name="txtSueldo" onkeyup="validEmptyInput('txtSueldo', 'invalid-sueldoEmp', 'El empleado debe tener un sueldo.')">
                      <div class="invalid-feedback" id="invalid-sueldoEmp">El empleado debe tener un sueldo.</div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Período de pago:*</label>
                    <select class="form-control" name="cmbPeriodo" id="cmbPeriodo" required>
                      <option disabled selected>Seleccione un período de pago...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-periodoEmp">El empleado debe tener un período de paga.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Sueldo diario:</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" class="form-control numericDecimal-only" id="txtSueldoDiario" name="txtSueldoDiario" readonly
                        value="" maxlength="12">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Base de cotización:*</label>
                    <select class="form-control" name="cmbBaseCotizacion" id="cmbBaseCotizacion" required>
                      <option value="1" >Fijo</option>
                      <option value="2" >Variable</option>
                      <option value="3" >Mixto</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-lg-3">
                    <label for="usr">Salario base de cotización (Parte fija):</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" class="form-control numericDecimal-only" id="txtSalarioBaseCotizacionFijo" name="txtSalarioBaseCotizacionFijo"
                        value="" maxlength="12">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Salario base de cotización (Parte variable):</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" class="form-control numericDecimal-only" id="txtSalarioBaseCotizacionVariable" name="txtSalarioBaseCotizacionVariable"
                        value="" maxlength="12">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Fecha inicial para el cálculo de vacaciones:*</label>
                    <div class="input-group mb-3">
                      <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" onkeyup="validEmptyInput('fechaInicio', 'invalid-fechaInicio', 'El empleado debe tener una fecha de inicio para el cálculo de vacaciones.')">
                      <div class="invalid-feedback" id="invalid-fechaInicio">El empleado debe tener una fecha de inicio para el cálculo de vacaciones.</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                    <div class="col-lg-3">
                      <label for="usr">Infonavit:</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">$</span>
                        </div>
                        <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtInfonavit">
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Deuda interna:</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="basic-addon1">$</span>
                        </div>
                        <input type="text" maxlength="13" class="form-control numericDecimal-only" name="txtDeuda">
                      </div>
                    </div>

                    <div class="col-lg-3">
                      <label for="usr">Forma de pago:*</label>
                      <select class="form-control" name="cmbFormaPago" id="cmbFormaPago" required>
                        <option disabled>Seleccione una forma de pago...</option>
                      </select>
                      <div class="invalid-feedback" id="invalid-formaPagoEmp">El empleado debe tener una forma de pago.</div>
                    </div>

                    <div class="col-lg-3">
                      <label for="usr">Nómina confidencial:</label>
                      <br>
                      <label style="position: relative; top: 10px;"><input type="checkbox" id="cboxNominaConfidencial"  name="cboxNominaConfidencial" value="1" style="height:15px; width: 15px;" > Incluir</label>
                    </div>

                </div>
              </div>


              <div class="form-group">
                <div class="row">
                  <div class="col-lg-6">
                    <label for="usr">Sindicalizado:*</label><br>
                     <div class="row">
                        <div class="col-lg-6">
                          <input type="radio" id="rSindicalizado" name="rSindicalizado" value="1" checked>
                          <label for="si">Si</label>
                        </div>
                        <div class="col-lg-6">
                          <input type="radio" id="rSindicalizado" name="rSindicalizado" value="2" >
                          <label for="no">No</label>
                        </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Tipo de prestación:*</label><br>
                     <div class="row">
                        <div class="col-lg-6">
                          <input type="radio" id="rTipoPrestacion" name="rTipoPrestacion" value="1" checked>
                          <label for="sindicalizado">Sindicalizado</label>
                        </div>
                        <div class="col-lg-6">
                          <input type="radio" id="rTipoPrestacion" name="rTipoPrestacion" value="2">
                          <label for="confianza">Confianza</label>
                        </div>
                    </div>
                  </div>
                </div>
              </div>

              <button type="button" class="btn-custom btn-custom--blue float-right" name="btnAgregarLaborales" id="btnAgregarLaborales">Agregar</button>
              <label for="">* Campos requeridos</label>
            </form>
          </div>
        </div>
      </div>
    </div>`;
      $("#datos").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function SeguirDatosMedicos(id) {
  resetTabs("#CargarDatosMedicos");
  /* $("#CargarDatosPersonales").css({ top: "0" });
  $("#CargarDatosLaborales").css("top", "0");
  $("#CargarDatosMedicos").css("top", "15px");
  $("#CargarDatosBancarios").css("top", "0"); */

  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_nombreCompleto", datos: id },
    dataType: "json",
    success: function (respuesta) {
      var html = `
      <div class="card shadow mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="formDatosAgregarMedicos">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="hidden" name="txtIdEmpleado" value="${id}">
                    </div>
                    <div class="col-lg-12" style="text-align: center;">
                      <h4>${respuesta}</h4>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">NSS:*</label>
                      <input type="text" maxlength="11" class="form-control numeric-only" name="txtNSS" id="txtNSS" required onkeyup="validEmptyInput('txtNSS', 'invalid-NSSEmp', 'El empleado debe tener un número de seguro social.')">
                        <div class="invalid-feedback" id="invalid-NSSEmp">El empleado debe tener un número de seguro social.</div>
                    </div>
                    <div class="col-lg-6">
                      <label for="usr">Tipo de sangre:*</label>
                      <select class="form-control" name="cmbTipoSangre" id="cmbTipoSangre" required>
                        <option disabled selected>Seleccione tipo de sangre</option>
                      </select>
                      <div class="invalid-feedback" id="invalid-sangreEmp">El empleado debe tener un tipo de sangre.</div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">Contacto de emergencia:</label>
                      <input type="text" maxlength="25" class="form-control alpha-only" name="txtContactoEmergencia">
                    </div>
                    <div class="col-lg-6">
                      <label for="usr">Numero de emergencia:</label>
                      <input type="text" maxlength="11" class="form-control numeric-only upperCaseletter"
                        name="txtNumeroEmergencia">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">Alergias:</label>
                      <textarea class="form-control" maxlength="70" name="txaAlergias" rows="4" cols="2"></textarea>
                    </div>
                    <div class="col-lg-6">
                      <label for="usr">Notas:</label>
                      <textarea class="form-control" maxlength="70" name="txaNotas" rows="4" cols="2"></textarea>
                    </div>
                  </div>
                </div>
                <div class="form-group" style="position: relative; top: 10px;">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">Donador de organos:</label>
                      <div class="row">
                        <div class="col-lg-3"><input type="radio" id="donador" name="donador" value="1"> <label for="donador">
                            Sí</label></div>
                        <div class="col-lg-3"><input type="radio" id="donador" name="donador" value="0" checked> <label
                            for="donador"> No</label></div>
                      </div>
                    </div>
                    <div class="col-lg-6"></div>
                  </div>
                  <button class="btn-custom btn-custom--blue float-right" name="btnAgregarMedicos"
                    id="btnAgregarMedicos">Agregar</button>
                  <label for="">* Campos requeridos</label>
              </form>
            </div>
          </div>
        </div>
      </div>`;
      $("#datos").html(html);
      cargarTipoSangre("", "cmbTipoSangre");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function SeguirDatosBancarios(id) {
  resetTabs("#CargarDatosBancarios");
  /* $("#CargarDatosPersonales").css({ top: "0" });
  $("#CargarDatosLaborales").css("top", "0");
  $("#CargarDatosMedicos").css("top", "0");
  $("#CargarDatosBancarios").css("top", "15px"); */

  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_nombreCompleto", datos: id },
    dataType: "json",
    success: function (respuesta) {
      cargarBancos("", "cmbBanco");
      var html = `
      <div class="card shadow mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="formDatosAgregarBancarios">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="hidden" name="txtIdEmpleado" value="${id}">
                    </div>
                    <div class="col-lg-12" style="text-align: center;">
                      <h4>${respuesta}</h4>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">Banco:*</label>
                      <select class="form-control" name="cmbBanco" id="cmbBanco" required>
                        <option disabled selected>Elegir banco...</option>
                      </select>
                      <div class="invalid-feedback" id="invalid-bancoEmp">El empleado debe tener un banco.</div>
                    </div>
                    <div class="col-lg-6">
                      <label for="usr">Cuenta bancaria:*</label>
                      <input class="form-control numeric-only" maxlength="11" type="text" name="txtCuentaBancaria" id="txtCuentaBancaria" required onkeyup="validEmptyInput('txtCuentaBancaria', 'invalid-cuentaEmp', 'El empleado debe tener una cuenta bancaria.')">
                      <div class="invalid-feedback" id="invalid-cuentaEmp">El empleado debe tener una cuenta bancaria.</div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">CLABE:</label>
                      <input class="form-control numeric-only" maxlength="18" type="text" name="txtCLABE">
                    </div>
                    <div class="col-lg-6">
                      <label for="usr">Número de tarjeta:*</label>
                      <input class="form-control numeric-only" maxlength="16" type="text" name="txtNumeroTarjeta" id="txtNumeroTarjeta" required onkeyup="validEmptyInput('txtNumeroTarjeta', 'invalid-NumEmp', 'El empleado debe tener un número de tarjeta.')">
                      <div class="invalid-feedback" id="invalid-NumEmp">El empleado debe tener un número de tarjeta.</div>
                    </div>
                  </div>
                </div>
                <button type="button" class="btn-custom btn-custom--blue float-right" name="btnAgregarBancarios"
                  id="btnAgregarBancarios">Agregar</button>
                <label for="">* Campos requeridos</label>
              </form>
            </div>
          </div>
        </div>
      </div>`;

      $("#datos").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function SeguirDatosRoles(id) {
  resetTabs("#CargarDatosRoles");

  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_Roles", data: id },
    dataType: "json",
    success: function (data) {
      //console.log("datos roles seguir: ", data);

      cargarRolesEmpleados("", "cmbRoles");

      var nameFormRoles = "formDatosRoles";

      var html = `
      <div class="card shadow mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="${nameFormRoles}">
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <input type="hidden" name="txtIdEmpleado" value="${data[0].PKEmpleado}">
                  </div>
                  <div class="col-lg-12" style="text-align: center;">
                    <h4>${data[0].Nombres} ${data[0].PrimerApellido} ${data[0].SegundoApellido}</h4>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
                    <label for="usr">Roles:*</label>
                    <select class="form-control" name="cmbRoles" id="cmbRoles" required>
                      <option disabled>Elegir rol...</option>'
                    </select>
                    <div class="invalid-feedback" id="invalid-rolEmp">Selecciona un rol.</div>
                  </div>
                  <div class="col-lg-2">
                    <br>
                    <input type="hidden" name="txtIdEmpleadoRol" id="txtIdEmpleadoRol" value="${id}">
                    <button type="button" class="btn-custom btn-custom--blue float-left" name="agregarRol" id="agregarRol" >Agregar</button>
                    <div class="invalid-feedback" id="invalid-rolEmp">Selecciona un rol.</div>
                  </div>
                  <div class="col-lg-6">
                    
                  </div>
                </div>
              </div>
              <label for="">* Campos requeridos</label>
            </form>

            <br>
            <div class="form-group">
              <!-- DataTales Example -->
              <div class="card mb-4 internal-table">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table" id="tblListadoRolesEmpleado" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Rol</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>

          </div>
        </div>
      </div>
    </div>`;

      $("#datos").html(html);
      cargarDataTablesRoles(id);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function CargarDatosPersonalesEdicion(id) {
  resetTabs("#CargarEdicionDatosPersonales");
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_empleado", data: id },
    dataType: "json",
    success: function (data) {
      console.log("respuesta edicion personales: ", data);
      cargarEstadoCivil(data[0]["FKEstadoCivil"], "cmbEstadoCivilEdicion");
      //cargarEstatusEmpleado(data[0]["FKEstatus"], "cmbEstatusEdicion");
      cargarEstadosFederativos(data[0]["FKEstado"], "cmbEstadosEdicion");
      cargarClaveRegimenFiscal(data[0]["claves_regimen_fiscal_id"], "cmbClaveRegimenFiscal");
      var masculino = "";
      var femenino = "";
      let genero = "";
      if ("Masculino" === data[0]["Genero"]) {
        masculino = "selected";
        genero = "Masculino";
      } else if ("Femenino" === data[0]["Genero"]) {
        femenino = "selected";
        genero = "Femenino";
      }

      var html = `
      <div class="card shadow mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="formDatosEdicionPersonales">
              <input type="hidden" name="txtId" value="${data[0]["PKEmpleado"]}">
              <div class="row">
                <div class="col-lg-12" style="text-align: center;">
                  <h4>${data[0]["Nombres"]} ${data[0]["PrimerApellido"]} ${data[0]["SegundoApellido"]}</h4>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-2">
                    <label for="usr">Nombre(s):*</label>
                    <input type="text" maxlength="20" class="form-control alpha-only" name="txtNombre" id="txtNombre"
                      value="${data[0]["Nombres"]}" required onkeyup="validEmptyInput('txtNombre', 'invalid-nombreEmp', 'El empleado debe tener un nombre.')">
                      <div class="invalid-feedback" id="invalid-nombreEmp">El empleado debe tener un nombre.</div>
                  </div>
                  <div class="col-lg-2">
                    <label for="usr">Primer apellido:*</label>
                    <input type="text" maxlength="20" class="form-control alpha-only" name="txtApellidoPaterno" id="txtApellidoPaterno"
                      value="${data[0]["PrimerApellido"]}" required onkeyup="validEmptyInput('txtApellidoPaterno', 'invalid-paternoEmp', 'El empleado debe tener un apellido paterno.')">
                      <div class="invalid-feedback" id="invalid-paternoEmp">El empleado debe tener un apellido paterno.</div>
                  </div>
                  <div class="col-lg-2">
                    <label for="usr">Segundo apellido:</label>
                    <input type="text" maxlength="20" class="form-control alpha-only" name="txtApellidoMaterno"
                      value="${data[0]["SegundoApellido"]}">
                  </div>
                  <div class="col-lg-2">
                    <label for="txtEstadoCivil">Estado Civil:</label><br>
                    <select class="form-control" name="cmbEstadoCivilEdicion" id="cmbEstadoCivilEdicion" required>
                      <option disabled>Seleccione un estado civil...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-civilEmp">El empleado debe tener un estado civil.</div>
                  </div>
                  <div class="radio col-lg-2">
                    <label for="txtSexo">Genero:*</label><br>
                    <select class="form-control" name="cmbSexo" id="cmbSexo" required>'
                      <option disabled>Seleccion un género...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-generoEmp">El empleado debe tener un genero.</div>
                  </div>
                  <div class="radio col-lg-2"></div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-2">
                    <label for="usr">CURP:</label>
                    <input type="text" maxlength="18" class="form-control alphaNumericNDot-only upperCaseletter"
                      name="txtCURPEdicion" id="txtCURPEdicion" value="${data[0]["CURP"]}" required>
                      <div class="invalid-feedback" id="invalid-MalCURPEdicion">El CURP esta mal formado.</div>
                  </div>
                  <div class="col-lg-2">
                    <label for="usr">RFC:</label>
                    <input type="text" maxlength="13" class="form-control alphaNumericNDot-only upperCaseletter"
                      name="txtRFCEdicion" id="txtRFCEdicion" value="${data[0]["RFC"]}">
                      <div class="invalid-feedback" id="invalid-MalRFCEdicion">El RFC esta mal formado.</div>
                  </div>
                  <div class="col-lg-2">
                    <label for="usr">Fecha de nacimiento:</label>
                    <input type="date" name="txtFecha" id="txtFecha" class="form-control" step="1" value="${data[0]["FechaNacimiento"]}"
                      required>
                      <div class="invalid-feedback" id="invalid-fechaEmp">El empleado debe tener una fecha de nacimiento.</div>
                  </div>
                  <div class="col-lg-2">
                    <label for="usr">Telefono:</label>
                    <input type="text" maxlength="10" class="form-control numeric-only" name="txtTelefono" id="txtTelefono"
                      value="${data[0]["Telefono"]}" required>
                      <div class="invalid-feedback" id="invalid-telefonoEmp">El empleado debe tener un número de teléfono.</div>
                  </div>
                  <div class="col-lg-4">
                    <label for="usr">Email:</label>
                    <input type="email" maxlength="50" class="form-control" name="txtEmailEdicion" id="txtEmailEdicion" value="${data[0]["email"]}" required>
                    <div class="invalid-feedback" id="invalid-Email">El emai no es válido.</div>
                  </div>
      
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
                    <label for="usr">Calle:</label>
                    <input type="text" maxlength="100" class="form-control alphaNumericNDot-only" name="txtCalle" id="txtCalle"
                      value="${data[0]["Direccion"]}" required>
                      <div class="invalid-feedback" id="invalid-calleEmp">El domicilio del empleado debe tener una calle.</div>
                  </div>
                  <div class="col-lg-1">
                    <label for="usr">No. Exterior:</label>
                    <input type="text" maxlength="5" class="form-control numeric-only" name="txtNumeroExterior" id="txtNumeroExterior"
                      value="${data[0]["NumeroExterior"]}" required >
                      <div class="invalid-feedback" id="invalid-exteriorEmp">El domicilio del empleado debe tener un no. Exterior.</div>
                  </div>
                  <div class="col-lg-1">
                    <label for="usr">Interior:</label>
                    <input type="text" maxlength="5" class="form-control alphaNumericNDot-only" name="txtNumeroInterior"
                      value="${data[0]["Interior"]}">
                  </div>
                  <div class="col-lg-4">
                    <label for="usr">Colonia:</label>
                    <input type="text" maxlength="20" class="form-control alpha-only" name="txtColonia" id="txtColonia" value="${data[0]["Colonia"]}" required>
                    <div class="invalid-feedback" id="invalid-coloniaEmp">El domicilio del empleado debe tener una colinia.</div>
                  </div>
                  <div class="col-lg-2">
                    <label for="usr">Código Postal:*</label>
                    <input type="text" maxlength="5" class="form-control numeric-only" name="txtCodigoPostal" id="txtCodigoPostal"
                      value="${data[0]["CP"]}" required>
                      <div class="invalid-feedback" id="invalid-CPEmp">El domicilio del empleado debe tener un CP.</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
                    <label for="usr">Ciudad:</label>
                    <input type="text" maxlength="20" class="form-control alphaNumericNDot-only" name="txtCiudad" id="txtCiudad"
                      value="${data[0]["Ciudad"]}" required>
                      <div class="invalid-feedback" id="invalid-ciudadEmp">El domicilio del empleado debe tener una ciudad.</div>
                  </div>
                  <div class="col-lg-4">
                    <label for="usr">Estado:</label>
                    <select name="cmbEstados" class="form-control" id="cmbEstadosEdicion" required>
                      <option disabled>Seleccione un estado...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-estadoEmp">El domicilio del empleado debe tener un estado.</div>
                  </div>
                  <div class="col-lg-4">
                    <label for="usr">Régimen fiscal:</label>
                    <select name="cmbClaveRegimenFiscal" class="form-control" id="cmbClaveRegimenFiscal" required>
                      <option value='0' selected>Seleccione una clave del régimen fiscal...</option>
                    </select>
                  </div>

                </div>
              </div>
            </form>
            <a href="#" class="btn-custom btn-custom--blue float-right" id="btnEditarPersonales">Guardar</a>
            <label for="">* Campos requeridos </label>
          </div>
        </div>
      </div>
    </div>`;

      $("#datos").html(html);
      cargarGenero(genero, "cmbSexo");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* $(document).on("change", "#cmbEstadoCivilEdicion", function () {
  validEmptyInput(
    "cmbEstadoCivilEdicion",
    "invalid-civilEmp",
    "El empleado debe tener un estado civil."
  );
}); */

$(document).on("change", "#cmbEstadosEdicion", function () {
  validEmptyInput(
    "cmbEstadosEdicion",
    "invalid-estadoEmp",
    "El domicilio del empleado debe tener un estado."
  );
});

$(document).on("change", "#cmbEstatusEdicion", function () {
  addRemoveInvalid($(this)[0], "El empleado debe tener un status.");
});

/*Cargar ventanas de las pestañas*/
$(document).on("click", "#CargarEdicionDatosPersonales", function () {
  var id = $(this).data("id");
  CargarDatosPersonalesEdicion(id);
  $("#CargarEdicionDatosLaborales").css("top", "0");
  $("#CargarEdicionDatosPersonales").css("top", "15px");
  $("#CargarEdicionDatosMedicos").css("top", "0");
  $("#CargarEdicionDatosBancarios").css("top", "0");
});

$(document).on("click", "#CargarEdicionDatosLaborales", function () {
  resetTabs("#CargarEdicionDatosLaborales");

  //console.log('Hola desde edicion de laborales');
  var id = $(this).data("id1");
  //console.log('Hola desde edicion de laborales:\nCon id: '+id);
  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_datosLaborales", data: id },
    dataType: "json",
    success: function (data) {
      console.log("datos laborales edicion: ", data);

      cargarTurnos(data[0]["FKTurno"], "cmbTurno");
      cargarLocaciones(data[0]["FKSucursal"], "cmbLocacion");
      cargarPuestos(data[0]["FKPuesto"], "cmbPuesto");
      cargarEmpresas(data[0]["FKEmpresa"], "cmbEmpresa");
      cargarPeriodos(data[0]["FKPeriodo"], "cmbPeriodo");
      cargarTipoContrato(data[0]["FKTipoContrato"], "cmbTipoContrato");
      cargarRiesgoPuesto(data[0]["FKRiesgoPuesto"], "cmbRiesgoPuesto");
      cargarTipoRegimen(data[0]["FKRegimen"], "cmbRegimen");
      cargarAreaDepartamento(data[0]["idAreaDepartamento"], "cmbAreaDepartamento");
      cargarMetodosPago(data[0]["FKFormaPago"], "cmbFormaPago");

      var infonavit = "";
      var deudaInterna = "";
      var deudaRestante = "";
      var fechaIngreso = "";

      if (data[0]["DeudaInterna"] !== "" && data[0]["DeudaInterna"] !== null) {
        deudaInterna = data[0]["DeudaInterna"];
      }

      if (data[0]["Infonavit"] !== "" && data[0]["Infonavit"] !== null) {
        infonavit = data[0]["Infonavit"];
      }

      if (
        data[0]["DeudaRestante"] !== "" &&
        data[0]["DeudaRestante"] !== null
      ) {
        deudaRestante = data[0]["DeudaInterna"];
      }

      if (data[0]["FechaIngreso"] !== "" && data[0]["FechaIngreso"] !== null) {
        fechaIngreso = data[0]["FechaIngreso"];
      }

      if (data[0]["FKEmpleado"] !== "" && data[0]["FKEmpleado"] !== null) {
        var btnDatosLaborales = "btnEditarLaborales";
        var formName = "formDatosEdicionLaborales";
      } else {
        var btnDatosLaborales = "btnAgregarLaborales";
        var formName = "formDatosAgregarLaborales";
      }

      let opcion1 = "",
        opcion2 = "",
        opcion3 = "";
      if (data[0]["BaseCotizacion"] == "1") {
        opcion1 = "selected";
      }
      if (data[0]["BaseCotizacion"] == "2") {
        opcion2 = "selected";
      }
      if (data[0]["BaseCotizacion"] == "3") {
        opcion3 = "selected";
      }


      let activoSindicalizado1 = "", activoSindicalizado2 = "";
      
      if(data[0]["Sindicalizado"] == 1){
        activoSindicalizado1 = "checked";
      }
      if(data[0]["Sindicalizado"] == 2){
        activoSindicalizado2 = "checked";
      }

      let activoTipoPrestacion1 = "", activoTipoPrestacion2 = "";
      
      if(data[0]["TipoPrestacion"] == 1){
        activoTipoPrestacion1 = "checked";
      }
      if(data[0]["TipoPrestacion"] == 2){
        activoTipoPrestacion2 = "checked";
      }

      let sueldoPeriodo = "";
      
      if(data[0]["Sueldo"] !== "" || data[0]["Sueldo"] !== null){
        sueldoPeriodo = data[0]["Sueldo"];
      } 

      let nominaConfidencial = "";

      if(data[0]["Confidencial"] == 1){
        nominaConfidencial = "checked";
      }

      var html = `
      <div class="card shadow mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="${formName}">
              <div class="row">
                <div class="col-lg-12">
                  <input type="hidden" name="txtIdEmpleado" value="${data[0]["PKEmpleado"]}">
                </div>
                <div class="col-lg-12" style="text-align: center;">
                  <h4>${data[0]["Nombres"]} ${data[0]["PrimerApellido"]} ${data[0]["SegundoApellido"]}</h4>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-3">
                    <label for="usr">Fecha de Ingreso:*</label>
                    <input type="date" class="form-control" id="txtfechaIngreso" name="txtfechaIngreso" step="1" value="${fechaIngreso}"
                      required>
                      <div class="invalid-feedback" id="invalid-fechaIngEmp">El empleado debe tener una fecha de ingreso.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Tipo de contrato:*</label>
                    <select class="form-control" name="cmbTipoContrato" id="cmbTipoContrato" required>
                      <option disabled>Seleccione un tipo de contrato...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-TipoContrato">El empleado debe tener un tipo de contrato.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Puesto:*</label>
                    <select class="form-control" name="cmbPuesto" id="cmbPuesto" required>
                      <option disabled>Seleccione un puesto...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-puestoEmp">El empleado debe tener un puesto.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Área/Departamento:*</label>
                    <select class="form-control" name="cmbAreaDepartamento" id="cmbAreaDepartamento" required>
                      <option disabled>Seleccione un área o departamento...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-areaDepartamentoEmp">El empleado debe tener un área o departamento.</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-3">
                    <label for="usr">Riesgo del puesto:*</label>
                    <select class="form-control" name="cmbRiesgoPuesto" id="cmbRiesgoPuesto" required>
                      <option disabled>Seleccione un riesgo del puesto...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-riegopuestoEmp">El empleado debe tener un riesgo del puesto.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Régimen:*</label>
                    <select class="form-control" name="cmbRegimen" id="cmbRegimen" required>
                      <option disabled>Seleccione un régimen...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-regimenEmp">El empleado debe tener un régimen laboral.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Turno:*</label>
                    <select class="form-control" name="cmbTurno" id="cmbTurno" required>
                      <option disabled>Seleccione un turno...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-turnoEmp">El empleado debe tener un turno laboral.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Sucursal:*</label>
                    <select class="form-control" name="cmbLocacion" id="cmbLocacion" required>
                      <option disabled>Seleccione una local...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-areaEmp">El empleado debe tener una sucursal.</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-3">
                    <label for="usr">Sueldo período:*</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" class="form-control numericDecimal-only" maxlength="12" id="txtSueldo" name="txtSueldo" required
                        value="${sueldoPeriodo}"  onkeyup="validEmptyInput('txtSueldo', 'invalid-sueldoEmp', 'El empleado debe tener un sueldo.')">
                        <div class="invalid-feedback" id="invalid-sueldoEmp">El empleado debe tener un sueldo.</div>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Periodo de pago:*</label>
                    <select class="form-control" name="cmbPeriodo" id="cmbPeriodo" required>
                      <option disabled>Seleccione un periodo de pago...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-periodoEmp">El empleado debe tener un período de paga.</div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Sueldo diario:</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" class="form-control numericDecimal-only" id="txtSueldoDiario" name="txtSueldoDiario" readonly
                        value="${data[0]["SalarioDiario"]}" maxlength="12">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Base de cotización:*</label>
                    <select class="form-control" name="cmbBaseCotizacion" id="cmbBaseCotizacion" required>
                      <option value="1" ${opcion1} >Fijo</option>
                      <option value="2" ${opcion2} >Variable</option>
                      <option value="3" ${opcion3} >Mixto</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-lg-3">
                    <label for="usr">Salario base de cotización (Parte fija):</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" class="form-control numericDecimal-only" id="txtSalarioBaseCotizacionFijo" name="txtSalarioBaseCotizacionFijo"
                        value="${data[0]["SalarioBaseCotizacionFijo"]}" maxlength="12">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Salario base de cotización (Parte variable):</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" class="form-control numericDecimal-only" id="txtSalarioBaseCotizacionVariable" name="txtSalarioBaseCotizacionVariable"
                        value="${data[0]["SalarioBaseCotizacionVariable"]}" maxlength="12">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Fecha inicial para el cálculo de vacaciones:*</label>
                    <div class="input-group mb-3">
                      <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="${data[0]["FechaInicioVacaciones"]}" onkeyup="validEmptyInput('fechaInicio', 'invalid-fechaInicio', 'El empleado debe tener una fecha de inicio para el cálculo de vacaciones.')">
                      <div class="invalid-feedback" id="invalid-fechaInicio">El empleado debe tener una fecha de inicio para el cálculo de vacaciones.</div>
                    </div>
                  </div>
                  
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-lg-3">
                    <label for="usr">Infonavit:</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" maxlength="11" class="form-control numericDecimal-only" name="txtInfonavit"
                        value="${infonavit}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <label for="usr">Deuda interna:</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">$</span>
                      </div>
                      <input type="text" maxlength="13" class="form-control numericDecimal-only" name="txtDeuda"
                        value="${deudaInterna}">
                    </div>
                  </div>
                  
                  <div class="col-lg-3">
                    <label for="usr">Forma de pago:*</label>
                    <select class="form-control" name="cmbFormaPago" id="cmbFormaPago" required>
                      <option disabled>Seleccione una forma de pago...</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-formaPagoEmp">El empleado debe tener una forma de pago.</div>
                  </div>

                  <div class="col-lg-3">
                    <label for="usr">Nómina confidencial:</label>
                    <br>
                    <label style="position: relative; top: 10px;"><input type="checkbox" id="cboxNominaConfidencial"  name="cboxNominaConfidencial" value="1" style="height:15px; width: 15px;" ${nominaConfidencial}> Incluir</label>
                  </div>

                </div>
              </div>


              <div class="form-group">
                <div class="row">
                  <div class="col-lg-6">
                    <label for="usr">Sindicalizado:*</label><br>
                     <div class="row">
                        <div class="col-lg-6">
                          <input type="radio" id="rSindicalizado" name="rSindicalizado" value="1" ${activoSindicalizado1}>
                          <label for="si">Si</label>
                        </div>
                        <div class="col-lg-6">
                          <input type="radio" id="rSindicalizado" name="rSindicalizado" value="2" ${activoSindicalizado2}>
                          <label for="no">No</label>
                        </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Tipo de prestación:*</label><br>
                     <div class="row">
                        <div class="col-lg-6">
                          <input type="radio" id="rTipoPrestacion" name="rTipoPrestacion" value="1" ${activoTipoPrestacion1}>
                          <label for="sindicalizado">Sindicalizado</label>
                        </div>
                        <div class="col-lg-6">
                          <input type="radio" id="rTipoPrestacion" name="rTipoPrestacion" value="2" ${activoTipoPrestacion2}>
                          <label for="confianza">Confianza</label>
                        </div>
                    </div>
                  </div>
                </div>
              </div>

              <a href="#" class="btn-custom btn-custom--blue float-right" name="${btnDatosLaborales}"
                id="${btnDatosLaborales}">Guardar</a>
              <label for="">* Campos requeridos</label>
            </form>
          </div>
        </div>
      </div>
    </div>`;

      $("#datos").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("change", "#txtSueldo,#cmbPeriodo", function () {
  let sueldo = $("#txtSueldo").val();
  let periodo = $("#cmbPeriodo option:selected").val();
  let sueldoDiario;

  if (periodo == 1) {
    sueldoDiario = sueldo / 7;
  }
  if (periodo == 2) {
    sueldoDiario = sueldo / 14;
  }
  if (periodo == 3) {
    sueldoDiario = sueldo / 15;
  }
  if (periodo == 4) {
    sueldoDiario = sueldo / 30;
  }

  $("#txtSueldoDiario").val(sueldoDiario.toFixed(2));
});

$(document).on("click", "#CargarEdicionDatosMedicos", function () {
  resetTabs("#CargarEdicionDatosMedicos");

  var id = $(this).data("id1");

  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_datosMedicos", data: id },
    dataType: "json",
    success: function (data) {
      //console.log("datos medicos edicion: ", data);

      if (data[0].NSS == null) {
        nss = "";
      } else {
        nss = data[0].NSS;
      }

      if (data[0].ContactoEmergencia == null) {
        ContactoEmergencia = "";
      } else {
        ContactoEmergencia = data[0].ContactoEmergencia;
      }

      if (data[0].NumeroEmergencia == null) {
        NumeroEmergencia = "";
      } else {
        NumeroEmergencia = data[0].NumeroEmergencia;
      }

      if (data[0].Alergias == null) {
        Alergias = "";
      } else {
        Alergias = $.trim(data[0].Alergias);
      }

      if (data[0].Notas == null) {
        Notas = "";
      } else {
        Notas = $.trim(data[0].Notas);
      }

      if (data[0].Donador != null) {
        Donador = data[0].Donador;
      } else {
        Donador = 0;
      }

      if (
        data[0]["PKMedicosEmpleado"] !== "" &&
        data[0]["PKMedicosEmpleado"] !== null
      ) {
        var nameFormMedicos = "formDatosEdicionMedicos";
        var btnMedicos = "btnEditarMedicos";
      } else {
        var nameFormMedicos = "formDatosAgregarMedicos";
        var btnMedicos = "btnAgregarMedicos";
      }

      var donadorPos = "";
      var donadorNeg = "";
      if (Donador == 1) {
        donadorPos = "checked";
      } else {
        donadorNeg = "checked";
      }

      var html = `
      <div class="card shadow mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="${nameFormMedicos}">
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <input type="hidden" name="txtIdEmpleado" value="${data[0].PKEmpleado}">
                  </div>
                  <div class="col-lg-12" style="text-align: center;">
                    <h4>${data[0].Nombres} ${data[0].PrimerApellido} ${data[0].SegundoApellido}</h4>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-6">
                    <label for="usr">NSS:*</label>
                    <input type="text" maxlength="11" class="form-control numeric-only" name="txtNSS" id="txtNSS"
                      value="${nss}" required onkeyup="validEmptyInput('txtNSS', 'invalid-NSSEmp', 'El empleado debe tener un número de seguro social.')">
                      <div class="invalid-feedback" id="invalid-NSSEmp">El empleado debe tener un número de seguro social.</div>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Tipo de sangre:*</label>
                    <select class="form-control" name="cmbTipoSangre" id="cmbTipoSangre" required>
                      <option disabled>Seleccione tipo de sangre</option>
                    </select>
                    <div class="invalid-feedback" id="invalid-sangreEmp">El empleado debe tener un tipo de sangre.</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-6">
                    <label for="usr">Contacto de emergencia:</label>
                    <input type="text" maxlength="25" class="form-control alpha-only" name="txtContactoEmergencia"
                      value="${ContactoEmergencia}">
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Numero de emergencia:</label>
                    <input type="text" maxlength="11" class="form-control numeric-only upperCaseletter"
                      name="txtNumeroEmergencia" value="${NumeroEmergencia}">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-6">
                    <label for="usr">Alergias:</label>
                    <textarea class="form-control" maxlength="70" name="txaAlergias" rows="4" cols="2" value="${Alergias}">
            ${Alergias}
            </textarea>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Notas:</label>
                    <textarea class="form-control" maxlength="70" name="txaNotas" rows="4" cols="2" value="${Notas}">
            ${Notas}
            </textarea>
                  </div>
                </div>
                <div class="form-group" style="position: relative; top: 10px;">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">Donador de organos:</label>
                      <div class="row">
                        <div class="col-lg-3">
                          <input type="radio" id="donador" name="donador" value="1" ${donadorPos}>
                          <label for="donador">Sí</label>
                        </div>
                        <div class="col-lg-3">
                          <input type="radio" id="donador" name="donador" value="0" ${donadorNeg}>
                          <label for="donador">No</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6"></div>
                  </div>
                </div>
                <a href="#" class="btn-custom btn-custom--blue float-right" name="${btnMedicos}" id="${btnMedicos}">Guardar</a>
                <label for="">* Campos requeridos</label>
            </form>
          </div>
        </div>
      </div>
    </div>`;

      $("#datos").html(html);
      cargarTipoSangre(data[0].TipoSangre, "cmbTipoSangre");
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("click", "#CargarEdicionDatosBancarios", function () {
  resetTabs("#CargarEdicionDatosBancarios");

  var id = $(this).data("id1");

  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_datosBancarios", data: id },
    dataType: "json",
    success: function (data) {
      console.log("datos bancarios edicion: ", data);

      cargarBancos(data[0]["FKBanco"], "cmbBanco");

      if (data[0].CuentaBancaria != null) {
        CuentaBancaria = data[0].CuentaBancaria;
      } else {
        CuentaBancaria = "";
      }

      if (data[0].CLABE != null) {
        CLABE = data[0].CLABE;
      } else {
        CLABE = "";
      }

      if (data[0].NumeroTarjeta != null) {
        NumeroTarjeta = data[0].NumeroTarjeta;
      } else {
        NumeroTarjeta = "";
      }

      if (data[0]["PKEmpleado"] !== "" && data[0]["PKEmpleado"] !== null) {
        var nameFormBancarios = "formDatosEdicionBancarios";
        var btnBancarios = "btnEditarBancarios";
      } else {
        var nameFormBancarios = "formDatosAgregarBancarios";
        var btnBancarios = "btnAgregarBancarios";
      }

      var html = `
      <div class="card shadow mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="${nameFormBancarios}">
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <input type="hidden" name="txtIdEmpleado" value="${data[0].PKEmpleado}">
                  </div>
                  <div class="col-lg-12" style="text-align: center;">
                    <h4>${data[0].Nombres} ${data[0].PrimerApellido} ${data[0].SegundoApellido}</h4>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-6">
                    <label for="usr">Banco:*</label>
                    <select class="form-control" name="cmbBanco" id="cmbBanco" required>
                      <option disabled>Elegir banco...</option>'
                    </select>
                    <div class="invalid-feedback" id="invalid-bancoEmp">El empleado debe tener un banco.</div>
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Cuenta bancaria:*</label>
                    <input class="form-control numeric-only" maxlength="11" type="text" name="txtCuentaBancaria"
                      id="txtCuentaBancaria" value="${CuentaBancaria}" required onkeyup="validEmptyInput('txtCuentaBancaria', 'invalid-cuentaEmp', 'El empleado debe tener una cuenta bancaria.')">
                    <div class="invalid-feedback" id="invalid-cuentaEmp">El empleado debe tener una cuenta bancaria.</div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-6">
                    <label for="usr">CLABE:</label>
                    <input class="form-control numeric-only" maxlength="18" type="text" name="txtCLABE" value="${CLABE}">
                  </div>
                  <div class="col-lg-6">
                    <label for="usr">Numero de tarjeta:*</label>
                    <input class="form-control numeric-only" maxlength="16" type="text" name="txtNumeroTarjeta" id="txtNumeroTarjeta"
                      value="${NumeroTarjeta}" onkeyup="validEmptyInput('txtNumeroTarjeta', 'invalid-NumEmp', 'El empleado debe tener un número de tarjeta.')">
                      <div class="invalid-feedback" id="invalid-NumEmp">El empleado debe tener un número de tarjeta.</div>
                  </div>
                </div>
              </div>
              <a href="#" class="btn-custom btn-custom--blue float-right" name="${btnBancarios}"
                id="${btnBancarios}">Guardar</a>
              <label for="">* Campos requeridos</label>
            </form>
          </div>
        </div>
      </div>
    </div>`;

      $("#datos").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("change", "#txtfechaIngreso", function () {
  validEmptyInput(
    "txtfechaIngreso",
    "invalid-fechaIngEmp",
    "El empleado debe tener una fecha de ingreso."
  );
});

$(document).on("change", "#cmbPuesto", function () {
  validEmptyInput(
    "cmbPuesto",
    "invalid-puestoEmp",
    "El empleado debe tener un puesto."
  );
});

$(document).on("change", "#cmbTurno", function () {
  validEmptyInput(
    "cmbTurno",
    "invalid-turnoEmp",
    "El empleado debe tener un turno laboral."
  );
});

$(document).on("change", "#cmbLocacion", function () {
  validEmptyInput(
    "cmbLocacion",
    "invalid-areaEmp",
    "El empleado debe tener una sucursal."
  );
});

$(document).on("change", "#cmbPeriodo", function () {
  validEmptyInput(
    "cmbPeriodo",
    "invalid-periodoEmp",
    "El empleado debe tener un período de paga."
  );
});

$(document).on("change", "#fechaInicio", function () {
  validEmptyInput(
    "fechaInicio",
    "invalid-fechaInicio",
    "El empleado debe tener una fecha de inicio para el cálculo de vacaciones."
  );
});

/*Agregar y guardar datos ventanas de las pestañas*/
$(document).on("click", "#btnAgregarLaborales", function () {

  if (
    $("#formDatosAgregarLaborales")[0].checkValidity() &&
    $("#cmbPuesto").val() != null &&
    $("#cmbTurno").val() != null &&
    $("#cmbLocacion").val() != null &&
    $("#cmbPeriodo").val() != null &&
    $("#fechaInicio").val() != null &&
    $("#cmbAreaDepartamento").val() != null
  ) {
    var badFechaIngEmp =
      $("#invalid-fechaIngEmp").css("display") === "block" ? false : true;
    var badPuestoEmp =
      $("#invalid-puestoEmp").css("display") === "block" ? false : true;
    var badTurnoEmp =
      $("#invalid-turnoEmp").css("display") === "block" ? false : true;
    var badAreaEmp =
      $("#invalid-areaEmp").css("display") === "block" ? false : true;
    var badSueldoEmp =
      $("#invalid-sueldoEmp").css("display") === "block" ? false : true;
    var badPeriodoEmp =
      $("#invalid-periodoEmp").css("display") === "block" ? false : true;
    var badFechaInicio =
      $("#invalid-fechaInicio").css("display") === "block" ? false : true;
    var badAreaDepartamento =
      $("#invalid-areaDepartamentoEmp").css("display") === "block"
        ? false
        : true;

    if (
      badFechaIngEmp &&
      badPuestoEmp &&
      badTurnoEmp &&
      badAreaEmp &&
      badSueldoEmp &&
      badPeriodoEmp &&
      badFechaInicio &&
      badAreaDepartamento
    ) {
      $("#btnAgregarLaborales").prop("disabled", true);
      var data = {};

      $.each(
        $("#formDatosAgregarLaborales").serializeArray(),
        function (i, field) {
          data[field.name] = field.value;
          //data.push({ id: i, campos: field.name, datos: field.value });
        }
      );

      if($('#cboxNominaConfidencial').is(":checked")){
        data['cboxNominaConfidencialJS'] = 1;  
      }
      else{
        data['cboxNominaConfidencialJS'] = 0;
      }
      
      console.log(data);
      $.ajax({
        url: "../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "guardar_datosLaborales",
          datos: data,
        },
        dataType: "json",
        success: function (respuesta) {
          console.log(respuesta);
          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Datos laborales registrados correctamente!",
            });
            $("#btnAgregarLaborales").prop("disabled", false);
            SeguirDatosMedicos(respuesta[0].id);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
            });
            $("#btnAgregarLaborales").prop("disabled", false);
          }
        },
        error: function (error) {
          console.log(error);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
          $("#btnAgregarLaborales").prop("disabled", false);
        },
      });
    }
  } else {
    if (!$("#txtfechaIngreso").val()) {
      $("#invalid-fechaIngEmp").css("display", "block");
      $("#txtfechaIngreso").addClass("is-invalid");
    }
    if (!$("#cmbPuesto").val()) {
      $("#invalid-puestoEmp").css("display", "block");
      $("#cmbPuesto").addClass("is-invalid");
    }
    if (!$("#cmbTurno").val()) {
      $("#invalid-turnoEmp").css("display", "block");
      $("#cmbTurno").addClass("is-invalid");
    }
    if (!$("#cmbLocacion").val()) {
      $("#invalid-areaEmp").css("display", "block");
      $("#cmbLocacion").addClass("is-invalid");
    }
    if (!$("#txtSueldo").val()) {
      $("#invalid-sueldoEmp").css("display", "block");
      $("#txtSueldo").addClass("is-invalid");
    }
    if (!$("#cmbPeriodo").val()) {
      $("#invalid-periodoEmp").css("display", "block");
      $("#cmbPeriodo").addClass("is-invalid");
    }
    if (!$("#fechaInicio").val()) {
      $("#invalid-fechaInicio").css("display", "block");
      $("#fechaInicio").addClass("is-invalid");
    }
    if (!$("#cmbAreaDepartamento").val()) {
      $("#invalid-areaDepartamentoEmp").css("display", "block");
      $("#cmbAreaDepartamento").addClass("is-invalid");
    }

    $("#btnAgregarLaborales").prop("disabled", false);
  }
});

$(document).on("click", "#CargarEdicionRoles", function () {
  resetTabs("#CargarEdicionRoles");

  var id = $(this).data("id1");

  $.ajax({
    url: "../php/funciones.php",
    data: { clase: "get_data", funcion: "obtener_Roles", data: id },
    dataType: "json",
    success: function (data) {
      //console.log("datos roles edicion: ", data);

      cargarRolesEmpleados("", "cmbRoles");

      var nameFormRoles = "formDatosRoles";

      var html = `
      <div class="card shadow mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="${nameFormRoles}">
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-12">
                    <input type="hidden" name="txtIdEmpleado" value="${data[0].PKEmpleado}">
                  </div>
                  <div class="col-lg-12" style="text-align: center;">
                    <h4>${data[0].Nombres} ${data[0].PrimerApellido} ${data[0].SegundoApellido}</h4>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
                    <label for="usr">Roles:*</label>
                    <select class="form-control" name="cmbRoles" id="cmbRoles" required>
                      <option disabled>Elegir rol...</option>'
                    </select>
                    <div class="invalid-feedback" id="invalid-rolEmp">Selecciona un rol.</div>
                  </div>
                  <div class="col-lg-2">
                    <br>
                    <button type="button" class="btn-custom btn-custom--blue float-left" name="editarRol" id="editarRol" >Agregar</button>
                    <div class="invalid-feedback" id="invalid-rolEmp">Selecciona un rol.</div>
                  </div>
                  <div class="col-lg-6">
                    
                  </div>
                </div>
              </div>
              <label for="">* Campos requeridos</label>
            </form>

            <br>
            <div class="form-group">
              <!-- DataTales Example -->
              <div class="card mb-4 internal-table">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table" id="tblListadoRolesEmpleado" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Rol</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>

          </div>
        </div>
      </div>
    </div>`;

      $("#datos").html(html);
      cargarDataTablesRoles(id);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function cargarDataTablesRoles(idEmpleado) {
  $("#tblListadoRolesEmpleado").dataTable({
    language: setFormatDatatables(),
    info: false,
    scrollX: true,
    bSort: false,
    pageLength: 15,
    responsive: true,
    lengthChange: false,
    columnDefs: [{ orderable: false, targets: 0, visible: false }],
    dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
    buttons: {
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
          extend: "excelHtml5",
          text: '<i class="fas fa-cloud-download-alt"></i> Descargar excel',
          className: "btn-table-custom--turquoise",
          titleAttr: "Excel",
        },
      ],
    },
    ajax: {
      url: "../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "obtener_DatatablesRoles",
        data: idEmpleado,
      },
    },
    columns: [{ data: "Id" }, { data: "Rol" }, { data: "Acciones" }],
  });
}

//Función de data table
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
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}

/* Añadir el impuesto */
$(document).on("click", "#agregarRol,#editarRol", function (e) {
  let idRol = $("#cmbRoles").val();
  var target = $(e.target);

  if (target[0].id == "editarRol") {
    var idEmpleado = $("#CargarEdicionRoles").data("id1");
  } else {
    var idEmpleado = $("#txtIdEmpleadoRol").val();
  }

  $.ajax({
    url: "../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "guardar_rolEmpleado",
      idRol: idRol,
      idEmpleado: idEmpleado,
    },
    dataType: "json",
    success: function (resp) {
      if (resp[0].status == "exito") {
        $("#tblListadoRolesEmpleado").DataTable().ajax.reload();

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/checkmark.svg",
          msg: "Rol registrado correctamente!",
        });
      }

      if (resp[0].status == "fallo") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal!",
        });
      }

      if (resp[0].status == "existe") {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/notificacion_error.svg",
          msg: "Ese rol ya fue agregado al empleado.",
        });
      }
    },
    error: function (error) {
      console.log(error);
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡Algo salio mal :(!",
      });
    },
  });
});

/* Eliminar el impuesto */
function eliminarRol(idRol) {
  //console.log("ID del id Rol: " + idRol);

  $.ajax({
    url: "../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_rol",
      idRol: idRol,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log("respuesta eliminar impuestoProducto:", respuesta);

      if (respuesta == 1) {
        $("#tblListadoRolesEmpleado").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el rol con exito!",
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal !",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("change", "#txtfechaIngreso", function () {
  validEmptyInput(
    "txtfechaIngreso",
    "invalid-fechaIngEmp",
    "El empleado debe tener una fecha de ingreso."
  );
});

$(document).on("change", "#cmbPuesto", function () {
  validEmptyInput(
    "cmbPuesto",
    "invalid-puestoEmp",
    "El empleado debe tener un puesto."
  );
});

$(document).on("change", "#cmbTurno", function () {
  validEmptyInput(
    "cmbTurno",
    "invalid-turnoEmp",
    "El empleado debe tener un turno laboral."
  );
});

$(document).on("change", "#cmbLocacion", function () {
  validEmptyInput(
    "cmbLocacion",
    "invalid-areaEmp",
    "El empleado debe tener una sucursal."
  );
});

$(document).on("change", "#cmbPeriodo", function () {
  validEmptyInput(
    "cmbPeriodo",
    "invalid-periodoEmp",
    "El empleado debe tener un período de paga."
  );
});

$(document).on("change", "#cmbTipoSangre", function () {
  validEmptyInput(
    "cmbTipoSangre",
    "invalid-sangreEmp",
    "El empleado debe tener un tipo de sangre."
  );
});

$(document).on("click", "#btnAgregarMedicos", function () {
  $("#btnAgregarMedicos").prop("disabled", true);

  if (
    $("#formDatosAgregarMedicos")[0].checkValidity() &&
    $("#cmbTipoSangre").val() != null
  ) {
    var badNSSEmp =
      $("#invalid-NSSEmp").css("display") === "block" ? false : true;
    var badSangreEmp =
      $("#invalid-sangreEmp").css("display") === "block" ? false : true;
    if (badNSSEmp && badSangreEmp) {
      var data = [];
      $.each(
        $("#formDatosAgregarMedicos").serializeArray(),
        function (i, field) {
          data.push({ id: i, campos: field.name, datos: field.value });
        }
      );

      //console.log("data en agregar medicos: ", data);

      $.ajax({
        url: "../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "guardar_datosMedicos",
          datos: data,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Datos médicos registrados correctamente!",
            });
            SeguirDatosBancarios(respuesta[0].id);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
            });
            $("#btnAgregarMedicos").prop("disabled", false);
          }
        },
        error: function (error) {
          console.log(error);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
          $("#btnAgregarMedicos").prop("disabled", false);
        },
      });
    }
  } else {
    if (!$("#txtNSS").val()) {
      $("#invalid-NSSEmp").css("display", "block");
      $("#txtNSS").addClass("is-invalid");
    }
    if (!$("#cmbTipoSangre").val()) {
      $("#invalid-sangreEmp").css("display", "block");
      $("#cmbTipoSangre").addClass("is-invalid");
    }

    $("#btnAgregarMedicos").prop("disabled", false);
  }
});

$(document).on("change", "#cmbBanco", function () {
  validEmptyInput(
    "cmbBanco",
    "invalid-bancoEmp",
    "El empleado debe tener un banco."
  );
});

$(document).on("click", "#btnAgregarBancarios", function () {
  $("#btnAgregarBancarios").prop("disabled", true);

  if (
    $("#formDatosAgregarBancarios")[0].checkValidity() &&
    $("#cmbBanco").val() != null
  ) {
    var badBancoEmp =
      $("#invalid-bancoEmp").css("display") === "block" ? false : true;
    var badCuentaEmp =
      $("#invalid-cuentaEmp").css("display") === "block" ? false : true;
    var badNumEmp =
      $("#invalid-NumEmp").css("display") === "block" ? false : true;
    if (badBancoEmp && badCuentaEmp && badNumEmp) {
      var data = [];
      $.each(
        $("#formDatosAgregarBancarios").serializeArray(),
        function (i, field) {
          data.push({ id: i, campos: field.name, datos: field.value });
        }
      );

      console.log("data en agregar medicos: ", data);

      $.ajax({
        url: "../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "guardar_datosBancarios",
          datos: data,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Datos bancarios registrados correctamente!",
            });

            SeguirDatosRoles(respuesta[0].id);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
            });
            $("#btnAgregarBancarios").prop("disabled", false);
          }
        },
        error: function (error) {
          console.log(error);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
          $("#btnAgregarBancarios").prop("disabled", false);
        },
      });
    }
  } else {
    if (!$("#cmbBanco").val()) {
      $("#invalid-bancoEmp").css("display", "block");
      $("#cmbBanco").addClass("is-invalid");
    }
    if (!$("#txtCuentaBancaria").val()) {
      $("#invalid-cuentaEmp").css("display", "block");
      $("#txtCuentaBancaria").addClass("is-invalid");
    }
    if (!$("#txtNumeroTarjeta").val()) {
      $("#invalid-NumEmp").css("display", "block");
      $("#txtNumeroTarjeta").addClass("is-invalid");
    }

    $("#btnAgregarBancarios").prop("disabled", false);
  }
});

/*Editar y guardar datos ventanas de las pestañas*/
$(document).on("click", "#btnEditarPersonales", function () {
  if (!$("#txtNombre").val()) {
    $("#invalid-nombreEmp").css("display", "block");
    $("#txtNombre").addClass("is-invalid");
  }
  if (!$("#txtApellidoPaterno").val()) {
    $("#invalid-paternoEmp").css("display", "block");
    $("#txtApellidoPaterno").addClass("is-invalid");
  }
  if (!$("#cmbSexo").val()) {
    $("#invalid-generoEmp").css("display", "block");
    $("#cmbSexo").addClass("is-invalid");
  }
  if (!$("#txtCodigoPostal").val()) {
    $("#invalid-CPEmp").css("display", "block");
    $("#txtCodigoPostal").addClass("is-invalid");
  }

  var badNombreEmp =
    $("#invalid-nombreEmp").css("display") === "block" ? false : true;
  var badPaternoEmp =
    $("#invalid-paternoEmp").css("display") === "block" ? false : true;
  var badGeneroEmp =
    $("#invalid-generoEmp").css("display") === "block" ? false : true;
  var badCURP =
    $("#invalid-MalCURPEdicion").css("display") === "block" ? false : true;
  var badRFC =
    $("#invalid-MalRFCEdicion").css("display") === "block" ? false : true;
  var badEmail = $("#invalid-Email").css("display") === "block" ? false : true;

  var badCP = $("#invalid-CPEmp").css("display") === "block" ? false : true;

  if (
    badNombreEmp &&
    badPaternoEmp &&
    badGeneroEmp &&
    badCURP &&
    badRFC &&
    badEmail &&
    badCP
  ) {
    var data = [];
    $.each(
      $("#formDatosEdicionPersonales").serializeArray(),
      function (i, field) {
        data.push({ id: i, campos: field.name, datos: field.value });
      }
    );
    $.ajax({
      url: "../php/funciones.php",
      data: {
        clase: "update_data",
        funcion: "actualizar_datosPersonales",
        datos: data,
      },
      dataType: "json",
      success: function (respuesta) {

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/checkmark.svg",
            msg: "¡Datos personales registrados correctamente!",
          });
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
        }

        if (respuesta[0].estatus_api == 'fallo-legal' || respuesta[0].estatus_api == 'fallo-rfc' || respuesta[0].estatus_api == 'fallo-zip' || respuesta[0].estatus_api == 'fallo-system') {
          let mensajeEstatusApi = '';

          if (respuesta[0].estatus_api == 'fallo-legal'){
            mensajeEstatusApi = 'El nombre no coincide con el RFC';
          }
          if (respuesta[0].estatus_api == 'fallo-rfc'){
            mensajeEstatusApi = 'RFC inválido';
          }
          if (respuesta[0].estatus_api == 'fallo-system'){
            mensajeEstatusApi = 'El Régimen Fiscal debe ser [601, 603, 605, 606, 607, 608, 610, 611, 612, 614, 615, 616, 620, 621, 622, 623, 624, 625, 626].';
          }
          if (respuesta[0].estatus_api == 'fallo-zip'){
            mensajeEstatusApi = 'El Código postal no coincide con el RFC.';
          }

          mensajeEstatusApi = mensajeEstatusApi + "<br> No se dió de alta el empleado para facturación, pero puedes editarlo para agregarlo."

          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: mensajeEstatusApi,
          });
        }

        

        if (respuesta[0].estatus_api == 'exito') {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/checkmark.svg",
            msg: "Los datos de facturación del empleado se han actualizado.",
          });
        }

      },
      error: function (error) {
        console.log(error);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
        });
      },
    });
  }
});

$(document).on("change", "#cmbTurno", function () {
  validEmptyInput(
    "cmbTurno",
    "invalid-turnoEmp",
    "El empleado debe tener un turno laboral."
  );
});

$(document).on("click", "#btnEditarLaborales", function () {
  //console.log("Hola desde boton editar laborales");
  if ($("#formDatosEdicionLaborales")[0].checkValidity()) {
    var badFechaIngEmp =
      $("#invalid-fechaIngEmp").css("display") === "block" ? false : true;
    var badPuestoEmp =
      $("#invalid-puestoEmp").css("display") === "block" ? false : true;
    var badTurnoEmp =
      $("#invalid-turnoEmp").css("display") === "block" ? false : true;
    var badAreaEmp =
      $("#invalid-areaEmp").css("display") === "block" ? false : true;
    var badSueldoEmp =
      $("#invalid-sueldoEmp").css("display") === "block" ? false : true;
    var badPeriodoEmp =
      $("#invalid-periodoEmp").css("display") === "block" ? false : true;
    var badFechaInicio =
      $("#invalid-fechaInicio").css("display") === "block" ? false : true;

    if (
      badFechaIngEmp &&
      badPuestoEmp &&
      badTurnoEmp &&
      badAreaEmp &&
      badSueldoEmp &&
      badPeriodoEmp &&
      badFechaInicio
    ) {
      var data = {};
      $.each(
        $("#formDatosEdicionLaborales").serializeArray(),
        function (i, field) {
          data[field.name] = field.value;
          //data.push({ id: i, campos: field.name, datos: field.value });
        }
      );

      if($('#cboxNominaConfidencial').is(":checked")){
        data['cboxNominaConfidencialJS'] = 1;  
      }
      else{
        data['cboxNominaConfidencialJS'] = 0;
      }
      
      console.log(data);
      $.ajax({
        url: "../php/funciones.php",
        data: {
          clase: "update_data",
          funcion: "actualizar_datosLaborales",
          datos: data,
        },
        success: function (respuesta) {
          console.log("respuesta en actualizar_datosLaborales", respuesta);
          //window.location.href = "editar_Empleado.php?idEmpleadoU="+respuesta;

          //$('#CargarEdicionDatosLaborales').data('id1') = respuesta;
          if (respuesta) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Datos laborales registrados correctamente!",
            });
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
            });
          }
        },
        error: function (error) {
          console.log(error);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
        },
      });
    }
  } else {
    if (!$("#txtfechaIngreso").val()) {
      $("#invalid-fechaIngEmp").css("display", "block");
      $("#txtfechaIngreso").addClass("is-invalid");
    }
    if (!$("#cmbPuesto").val()) {
      $("#invalid-puestoEmp").css("display", "block");
      $("#cmbPuesto").addClass("is-invalid");
    }
    if (!$("#cmbTurno").val()) {
      $("#invalid-turnoEmp").css("display", "block");
      $("#cmbTurno").addClass("is-invalid");
    }
    if (!$("#cmbLocacion").val()) {
      $("#invalid-areaEmp").css("display", "block");
      $("#cmbLocacion").addClass("is-invalid");
    }
    if (!$("#txtSueldo").val()) {
      $("#invalid-sueldoEmp").css("display", "block");
      $("#txtSueldo").addClass("is-invalid");
    }
    if (!$("#cmbPeriodo").val()) {
      $("#invalid-periodoEmp").css("display", "block");
      $("#cmbPeriodo").addClass("is-invalid");
    }
    if (!$("#fechaInicio").val()) {
      $("#invalid-fechaInicio").css("display", "block");
      $("#fechaInicio").addClass("is-invalid");
    }
  }
});

$(document).on("click", "#btnEditarMedicos", function () {
  if ($("#formDatosEdicionMedicos")[0].checkValidity()) {
    var badNSSEmp =
      $("#invalid-NSSEmp").css("display") === "block" ? false : true;
    var badSangreEmp =
      $("#invalid-sangreEmp").css("display") === "block" ? false : true;
    if (badNSSEmp && badSangreEmp) {
      var data = [];
      $.each(
        $("#formDatosEdicionMedicos").serializeArray(),
        function (i, field) {
          data.push({ id: i, campos: field.name, datos: field.value });
        }
      );

      console.log("datos para editar medicos:", data);
      $.ajax({
        url: "../php/funciones.php",
        data: {
          clase: "update_data",
          funcion: "actualizar_datosMedicos",
          datos: data,
        },
        success: function (respuesta) {
          console.log("respuesta en actualizar_datosMedicos", respuesta);
          //window.location.href = "editar_Empleado.php?idEmpleadoU="+respuesta;

          //$('#CargarEdicionDatosMedicos').data('id1') = respuesta;
          if (respuesta) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Datos médicos registrados correctamente!",
            });
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
            });
          }
        },
        error: function (error) {
          console.log(error);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
        },
      });
    }
  }
});

$(document).on("click", "#btnEditarBancarios", function () {
  if ($("#formDatosEdicionBancarios")[0].checkValidity()) {
    var badBancoEmp =
      $("#invalid-bancoEmp").css("display") === "block" ? false : true;
    var badCuentaEmp =
      $("#invalid-cuentaEmp").css("display") === "block" ? false : true;
    var badNumEmp =
      $("#invalid-NumEmp").css("display") === "block" ? false : true;
    if (badBancoEmp && badCuentaEmp && badNumEmp) {
      var data = [];
      $.each(
        $("#formDatosEdicionBancarios").serializeArray(),
        function (i, field) {
          data.push({ id: i, campos: field.name, datos: field.value });
        }
      );
      $.ajax({
        url: "../php/funciones.php",
        data: {
          clase: "update_data",
          funcion: "actualizar_datosBancarios",
          datos: data,
        },
        success: function (respuesta) {
          console.log("respuesta en actualizar_datosLaborales", respuesta);
          //window.location.href = "editar_Empleado.php?idEmpleadoU="+respuesta;

          //$('#CargarEdicionDatosBancarios').data('id1') = respuesta;
          if (respuesta) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Datos bancarios registrados correctamente!",
            });
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/notificacion_error.svg",
              msg: "¡Algo salio mal :(!",
            });
          }
        },
        error: function (error) {
          console.log(error);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
          });
        },
      });
    }
  } else {
    if (!$("#cmbBanco").val()) {
      $("#invalid-bancoEmp").css("display", "block");
      $("#cmbBanco").addClass("is-invalid");
    }
    if (!$("#txtCuentaBancaria").val()) {
      $("#invalid-cuentaEmp").css("display", "block");
      $("#txtCuentaBancaria").addClass("is-invalid");
    }
    if (!$("#txtNumeroTarjeta").val()) {
      $("#invalid-NumEmp").css("display", "block");
      $("#txtNumeroTarjeta").addClass("is-invalid");
    }
  }
});

function resetTabs(id) {
  $(".nav-link").removeClass("active");
  $(id).addClass("active");
}

function validEmptyInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val()) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }
}

function addRemoveInvalid(element, text) {
  var invalidDiv = element.nextElementSibling;
  invalidDiv.style.display = "block";
  element.classList.add("is-invalid");
  if (element.value) {
    invalidDiv.style.display = "none";
    element.classList.remove("is-invalid");
    invalidDiv.textContent = text;
  }
}

$(document).on("change", "#txtCURP", function () {
  let curp = $("#txtCURP").val().trim();

  if (curpValida(curp) || curp === "") {
    $("#txtCURP").removeClass("is-invalid");
    $("#invalid-MalCURP").css("display", "none");
  } else {
    $("#txtCURP").addClass("is-invalid");
    $("#invalid-MalCURP").css("display", "block");
    $("#invalid-MalCURP").text("Ingresa un CURP válido.");
  }
});

$(document).on("change", "#txtCURPEdicion", function () {
  let curp = $("#txtCURPEdicion").val().trim();

  if (curpValida(curp) || curp === "") {
    $("#txtCURPEdicion").removeClass("is-invalid");
    $("#invalid-MalCURPEdicion").css("display", "none");
  } else {
    $("#txtCURPEdicion").addClass("is-invalid");
    $("#invalid-MalCURPEdicion").css("display", "block");
    $("#invalid-MalCURPEdicion").text("Ingresa un CURP válido.");
  }
});

$(document).on("change", "#txtRFC", function () {
  let RFC = $("#txtRFC").val().trim();

  if (rfcValido(RFC) || RFC == "") {
    $("#txtRFC").removeClass("is-invalid");
    $("#invalid-MalRFC").css("display", "none");
    $("#invalid-MalRFC").text("");
  }
  if (!rfcValido(RFC) && RFC != "") {
    $("#txtRFC").addClass("is-invalid");
    $("#invalid-MalRFC").css("display", "block");
    $("#invalid-MalRFC").text("Ingresa un RFC válido.");
  }
});

$(document).on("change", "#txtRFCEdicion", function () {
  let RFC = $("#txtRFCEdicion").val().trim();

  if (rfcValido(RFC) || RFC == "") {
    $("#txtRFCEdicion").removeClass("is-invalid");
    $("#invalid-MalRFCEdicion").css("display", "none");
    $("#invalid-MalRFCEdicion").text("");
  }
  if (!rfcValido(RFC) && RFC != "") {
    $("#txtRFCEdicion").addClass("is-invalid");
    $("#invalid-MalRFCEdicion").css("display", "block");
    $("#invalid-MalRFCEdicion").text("Ingresa un RFC válido.");
  }
});

$(document).on("change", "#txtEmail", function () {
  let email = $("#txtEmail").val().trim();
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

  //validar si el email esta validado
  if (!emailReg.test(email)) {
    $("#txtEmail").addClass("is-invalid");
    $("#invalid-Email").css("display", "block");
    $("#invalid-Email").text("Ingresa un email válido.");
  }
  if (emailReg.test(email)) {
    $("#txtEmail").removeClass("is-invalid");
    $("#invalid-Email").css("display", "none");
  }

  //validar si lo ingresaron
  // if (email != "") {
  //   $("#txtEmail").removeClass("is-invalid");
  //   $("#invalid-EmailRequerido").css("display", "none");
  // }
  // if (email == "") {
  //   $("#txtEmail").addClass("is-invalid");
  //   $("#invalid-EmailRequerido").css("display", "block");
  //   $("#invalid-EmailRequerido").text("Ingresa un email.");
  // }
});

$(document).on("change", "#txtEmailEdicion", function () {
  let email = $("#txtEmailEdicion").val().trim();
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

  if (!emailReg.test(email)) {
    $("#txtEmailEdicion").addClass("is-invalid");
    $("#invalid-Email").css("display", "block");
    $("#invalid-Email").text("Ingresa un email válido.");
  }
  if (emailReg.test(email)) {
    $("#txtEmailEdicion").removeClass("is-invalid");
    $("#invalid-Email").css("display", "none");
  }

  //validar si lo ingresaron
  // if (email != "") {
  //   $("#txtEmailEdicion").removeClass("is-invalid");
  //   $("#invalid-EmailRequerido").css("display", "none");
  // }
  // if (email == "") {
  //   $("#txtEmailEdicion").addClass("is-invalid");
  //   $("#invalid-EmailRequerido").css("display", "block");
  //   $("#invalid-EmailRequerido").text("Ingresa un email.");
  // }
});

$(document).on("change", "#txtCodigoPostal", function () {
  validEmptyInput(
    "txtCodigoPostal",
    "invalid-CPEmp",
    "El domicilio del empleado debe tener un CP."
  );
});