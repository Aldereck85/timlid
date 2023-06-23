var _permissions = {
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0,
};

var _global = {
  PKVehiculo: 0,
  idCarga: 0,
  idServicio: 0,
  idPrestamo: 0,
  rutaFile: "",
  estatusPrestamo: 0,
};

$(document).ready(function () {
  CargarDatosVehiculo();
  new SlimSelect({
    select: "#cmbResponsable",
    deselectLabel: '<span class="">✖</span>',
  });
});

//Función de data table
function setFormatDatatables() {
  var idioma_espanol = {
    searchPlaceholder: "Buscar...",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
    sLengthMenu: "", //Mostrar _MENU_ registros
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo: "", //Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros
    sInfoEmpty: "", //Mostrando registros del 0 al 0 de un total de 0 registros
    sInfoFiltered: "", //(filtrado de un total de _MAX_ registros)
    sInfoPostFix: "",
    sUrl: "",
    sInfoThousands: ",",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "", //Primero
      sLast: "", //Último
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna de manera ascendente",
      sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
    },
  };
  return idioma_espanol;
}

/*----------------------Diseño datos del producto-------------------------------*/

//Cargar pestaña de Datos del producto
function CargarDatosVehiculo() {
  validate_Permissions(40, "url");

  resetTabs("#CargarDatosVehiculo");

  cargarCMBResponsable("", "cmbResponsable");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de vehículos
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosVehiculo" class="needs-validation" novalidate> 
                        <div class="form-group">
                          <div class="row">
                            <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                              
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                              <label for="usr">Estatus:*</label>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                              <input type="checkbox" id="activeVehiculo" class="check-custom" checked>
                              <label class="shadow-sm check-custom-label" for="activeVehiculo">
                                <div class="circle"></div>
                                <div class="check-inactivo">Inactivo</div>
                                <div class="check-activo">Activo</div>
                              </label>
                            </div>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Submarca:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtLinea" id="txtLinea" autofocus="" required maxlength="255" placeholder="Linea 1" onchange="validEmptyInput('txtLinea', 'invalid-linea', 'El vehículo debe tener una linea.')">
                                  <div class="invalid-feedback" id="invalid-linea">El vehículo debe tener una linea.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Marca:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtLinea" id="txtMarca" required maxlength="255" placeholder="Marca del vehículo" onchange="validEmptyInput('txtMarca', 'invalid-marca', 'El vehículo debe tener una marca.')">
                                  <div class="invalid-feedback" id="invalid-marca">El vehículo debe tener una marca.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Serie:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control" type="text" name="txtSerie" id="txtSerie" required maxlength="255" placeholder="Serie del vehículo" onchange="validEmptyInput('txtSerie', 'invalid-serie', 'El vehículo debe tener una serie.')">
                                  <div class="invalid-feedback" id="invalid-serie">El vehículo debe tener una serie.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Placas:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtPlacas" id="txtPlacas" required maxlength="15" placeholder="Placas del vehículo" onchange="validEmptyInput('txtPlacas', 'invalid-placas', 'El vehículo debe tener placas.')">
                                  <div class="invalid-feedback" id="invalid-placas">El vehículo debe tener placas.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Modelo:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtModelo" id="txtModelo" required maxlength="4" placeholder="Modelo del vehículo" onchange="validEmptyInput('txtModelo', 'invalid-modelo', 'El vehículo debe tener un modelo.')">
                                  <div class="invalid-feedback" id="invalid-modelo">El vehículo debe tener un modelo.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Puertas:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtPuertas" id="txtPuertas" required maxlength="2" placeholder="Puertas del vehículo" onchange="validEmptyInput('txtPuertas', 'invalid-puertas', 'El vehículo debe tener la cantidad puertas.')">
                                  <div class="invalid-feedback" id="invalid-puertas">El vehículo debe tener la cantidad puertas.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Cilindros:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtCilindros" id="txtCilindros" required maxlength="2" placeholder="Cilindros del vehículo" onchange="validEmptyInput('txtCilindros', 'invalid-cilindros', 'El vehículo debe tener un número de cilindros.')">
                                  <div class="invalid-feedback" id="invalid-cilindros">El vehículo debe tener un número de cilindros.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Odometro:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtOdometro" id="txtOdometro" required maxlength="10" placeholder="Odometro del vehículo" onchange="validEmptyInput('txtOdometro', 'invalid-odometro', 'El vehículo debe tener odometro.')">
                                  <div class="invalid-feedback" id="invalid-odometro">El vehículo debe tener odometro.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Kilometros para servicio:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numeric-only" type="number" name="txtKilometros" id="txtKilometros" required maxlength="5" placeholder="Kilometros para servicio" onchange="validEmptyInput('txtKilometros', 'invalid-kilometros', 'El vehículo debe tener un número de kilometros para servicio.')">
                                  <div class="invalid-feedback" id="invalid-kilometros">El vehículo debe tener un número de kilometros para servicio.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Motor:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtMotor" id="txtMotor" required maxlength="17" placeholder="Motor del vehículo" onchange="validEmptyInput('txtMotor', 'invalid-motor', 'El vehículo debe tener motor.')">
                                  <div class="invalid-feedback" id="invalid-motor">El vehículo debe tener motor.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Color:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtColor" id="txtColor" required maxlength="50" placeholder="Color del vehículo" onchange="validEmptyInput('txtColor', 'invalid-color', 'El vehículo debe tener un color.')">
                                  <div class="invalid-feedback" id="invalid-color">El vehículo debe tener un color.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Combustible:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtCombustible" id="txtCombustible" required maxlength="50" placeholder="Combustible del vehículo" onchange="validEmptyInput('txtCombustible', 'invalid-combustible', 'El vehículo debe tener tipo de combustible.')">
                                  <div class="invalid-feedback" id="invalid-combustible">El vehículo debe tener tipo de combustible.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-6">
                              <label for="usr">Transmisión:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control alphaNumeric-only" type="text" name="txtTransmision" id="txtTransmision" required maxlength="50" placeholder="Transmisión del vehículo" onchange="validEmptyInput('txtTransmision', 'invalid-transmision', 'El vehículo debe tener un tipo de transmisión.')">
                                  <div class="invalid-feedback" id="invalid-transmision">El vehículo debe tener un tipo de transmisión.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              <label for="usr">Responsable carga de combustible:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbResponsable" id="cmbResponsable" required>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-responsable">El vehículo debe tener un responsable.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <br>
                        <label for="">* Campos requeridos</label>                  
                      </form>

                      <a class="btn-custom btn-custom--blue float-right" id="btnEditarVehiculo">Guardar</a>
                    </div>
                  </div>
                </div>
              </div>
              `;

  $("#datos").append(html);

  resetValidations();

  CargarDatosGeneralesVehiculo(_global.PKVehiculo);
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/

function cargarCMBResponsable(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vehiculo_responsable" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKChofer) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKChofer}" ${selected}> 
                  ${respuesta[i].Nombre}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/*----------------------Botón agregar producto-------------------------------*/

$(document).on("click", "#btnEditarVehiculo", function () {
  if ($("#formDatosVehiculo")[0].checkValidity()) {
    var badLinea =
      $("#invalid-linea").css("display") === "block" ? false : true;
    var badMarca =
      $("#invalid-marca").css("display") === "block" ? false : true;
    var badSerie =
      $("#invalid-serie").css("display") === "block" ? false : true;
    var badPlacas =
      $("#invalid-placas").css("display") === "block" ? false : true;
    var badModelo =
      $("#invalid-modelo").css("display") === "block" ? false : true;
    var badPuertas =
      $("#invalid-puertas").css("display") === "block" ? false : true;
    var badCilindros =
      $("#invalid-cilindros").css("display") === "block" ? false : true;
    var badOdometro =
      $("#invalid-odometro").css("display") === "block" ? false : true;
    var badKilometros =
      $("#invalid-kilometros").css("display") === "block" ? false : true;
    var badMotor =
      $("#invalid-motor").css("display") === "block" ? false : true;
    var badColor =
      $("#invalid-color").css("display") === "block" ? false : true;
    var badCombustible =
      $("#invalid-combustible").css("display") === "block" ? false : true;
    var badTransmision =
      $("#invalid-transmision").css("display") === "block" ? false : true;
    var badResponsable =
      $("#invalid-responsable").css("display") === "block" ? false : true;

    if (
      badLinea &&
      badMarca &&
      badSerie &&
      badPlacas &&
      badModelo &&
      badPuertas &&
      badCilindros &&
      badOdometro &&
      badKilometros &&
      badMotor &&
      badColor &&
      badCombustible &&
      badTransmision &&
      badResponsable
    ) {
      var datos = {
        estatus: $("#activeVehiculo").is(":checked") ? 1 : 0,
        linea: $("#txtLinea").val(),
        marca: $("#txtMarca").val(),
        serie: $("#txtSerie").val(),
        placas: $("#txtPlacas").val(),
        modelo: $("#txtModelo").val(),
        puertas: $("#txtPuertas").val(),
        cilindros: $("#txtCilindros").val(),
        odometro: $("#txtOdometro").val(),
        kilometros: $("#txtKilometros").val(),
        motor: $("#txtMotor").val(),
        color: $("#txtColor").val(),
        combustible: $("#txtCombustible").val(),
        transmision: $("#txtTransmision").val(),
        responsable: $("#cmbResponsable").val(),
        pkVehiculo: _global.PKVehiculo,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_datosVehiculo",
          datos,
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
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos del vehículo registrados correctamente!",
              sound: "../../../../../sounds/sound4",
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
              sound: "../../../../../sounds/sound4",
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
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#txtLinea").val()) {
      $("#invalid-linea").css("display", "block");
      $("#txtLinea").addClass("is-invalid");
    } else {
      $("#invalid-linea").css("display", "none");
      $("#txtLinea").removeClass("is-invalid");
    }
    if (!$("#txtMarca").val()) {
      $("#invalid-marca").css("display", "block");
      $("#txtMarca").addClass("is-invalid");
    } else {
      $("#invalid-marca").css("display", "none");
      $("#txtMarca").removeClass("is-invalid");
    }
    if (!$("#txtSerie").val()) {
      $("#invalid-serie").css("display", "block");
      $("#txtSerie").addClass("is-invalid");
    } else {
      $("#invalid-serie").css("display", "none");
      $("#txtSerie").removeClass("is-invalid");
    }
    if (!$("#txtPlacas").val()) {
      $("#invalid-placas").css("display", "block");
      $("#txtPlacas").addClass("is-invalid");
    } else {
      $("#invalid-placas").css("display", "none");
      $("#txtPlacas").removeClass("is-invalid");
    }
    if (!$("#txtModelo").val()) {
      $("#invalid-modelo").css("display", "block");
      $("#txtModelo").addClass("is-invalid");
    } else {
      $("#invalid-modelo").css("display", "none");
      $("#txtModelo").removeClass("is-invalid");
    }
    if (!$("#txtPuertas").val()) {
      $("#invalid-puertas").css("display", "block");
      $("#txtPuertas").addClass("is-invalid");
    } else {
      $("#invalid-puertas").css("display", "none");
      $("#txtPuertas").removeClass("is-invalid");
    }
    if (!$("#txtCilindros").val()) {
      $("#invalid-cilindros").css("display", "block");
      $("#txtCilindros").addClass("is-invalid");
    } else {
      $("#invalid-cilindros").css("display", "none");
      $("#txtCilindros").removeClass("is-invalid");
    }
    if (!$("#txtOdometro").val()) {
      $("#invalid-odometro").css("display", "block");
      $("#txtOdometro").addClass("is-invalid");
    } else {
      $("#invalid-odometro").css("display", "none");
      $("#txtOdometro").removeClass("is-invalid");
    }
    if (!$("#txtKilometros").val()) {
      $("#invalid-kilometros").css("display", "block");
      $("#txtKilometros").addClass("is-invalid");
    } else {
      $("#invalid-kilometros").css("display", "none");
      $("#txtKilometros").removeClass("is-invalid");
    }
    if (!$("#txtMotor").val()) {
      $("#invalid-motor").css("display", "block");
      $("#txtMotor").addClass("is-invalid");
    } else {
      $("#invalid-motor").css("display", "none");
      $("#txtMotor").removeClass("is-invalid");
    }
    if (!$("#txtColor").val()) {
      $("#invalid-color").css("display", "block");
      $("#txtColor").addClass("is-invalid");
    } else {
      $("#invalid-color").css("display", "none");
      $("#txtColor").removeClass("is-invalid");
    }
    if (!$("#txtCombustible").val()) {
      $("#invalid-combustible").css("display", "block");
      $("#txtCombustible").addClass("is-invalid");
    } else {
      $("#invalid-combustible").css("display", "none");
      $("#txtCombustible").removeClass("is-invalid");
    }
    if (!$("#txtTransmision").val()) {
      $("#invalid-transmision").css("display", "block");
      $("#txtTransmision").addClass("is-invalid");
    } else {
      $("#invalid-transmision").css("display", "none");
      $("#txtTransmision").removeClass("is-invalid");
    }
    if (!$("#cmbResponsable").val()) {
      $("#invalid-responsable").css("display", "block");
      $("#cmbResponsable").addClass("is-invalid");
    } else {
      $("#invalid-responsable").css("display", "none");
      $("#cmbResponsable").removeClass("is-invalid");
    }
  }
});

function CargarDatosGeneralesVehiculo(id) {
  _global.PKVehiculo = id;
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_vehiculo_generales",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].estatus == 1) {
        $("#activeVehiculo").prop("checked", true);
      } else {
        $("#activeVehiculo").prop("checked", false);
      }
      $("#txtLinea").val(respuesta[0].linea);
      $("#txtMarca").val(respuesta[0].marca);
      $("#txtSerie").val(respuesta[0].serie);
      $("#txtPlacas").val(respuesta[0].placas);
      $("#txtModelo").val(respuesta[0].modelo);
      $("#txtPuertas").val(respuesta[0].puertas);
      $("#txtCilindros").val(respuesta[0].cilindros);
      $("#txtOdometro").val(respuesta[0].odometro);
      $("#txtKilometros").val(respuesta[0].kilometraje);
      $("#txtMotor").val(respuesta[0].motor);
      $("#txtColor").val(respuesta[0].color);
      $("#txtCombustible").val(respuesta[0].combustible);
      $("#txtTransmision").val(respuesta[0].transmision);
      cargarCMBResponsable(respuesta[0].empleado, "cmbResponsable");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function SeguirDatosCombustible(id) {
  _global.PKVehiculo = id;
  validarEmpresaVehiculo(id);

  cargarCMBUnidadMedidaLiquido("", "cmbUnidadMedidaLiquido");
  cargarCMBMonedaCostoUnitario(100, "cmbMonedaPrecio");

  resetTabs("#CargarDatosCombustible");

  var html = `<div class="card shadow mb-4">
      <div class="card-header">
        Tarjeta de combustible
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <form id="formDatosCombustible"> 
              <span id="areaDiseno">
                <div class="form-group">
                  <div class="row mt-3">
                    <div class="col-lg-3">
                      <label for="usr">Fecha de carga:*</label>
                      <div class="row">
                        <div class="col-lg-12 input-group">
                          <input type="date" class="form-control" name="txtFechaCarga" id="txtFechaCarga" required onchange="validEmptyInput('txtFechaCarga', 'invalid-fechaCarga', 'La carga de combustible debe tener una fecha.')">
                          <div class="invalid-feedback" id="invalid-fechaCarga">La carga de combustible debe tener una fecha.</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                    <label for="usr">Cantidad:*</label>
                      <div class="row">
                        <div class="col-lg-12 input-group">
                          <input class="form-control numericDecimal-only" type="text" name="txtCantidadCarga" id="txtCantidadCarga" required min="0" maxlength="6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="10.00" onchange="validEmptyInput('txtCantidadCarga', 'invalid-cantidadCarga', 'La carga de combustible debe de tener una cantidad.')">
                          <span class="input-group-addon" style="width:100px">
                            <select name="cmbUnidadMedidaLiquido" id="cmbUnidadMedidaLiquido" required>
                            </select>
                          </span>
                          <div class="invalid-feedback" id="invalid-cantidadCarga">La carga de combustible debe de tener una cantidad.</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Costo unitario:*</label>
                      <div class="row">
                        <div class="col-lg-12 input-group">
                          <input class="form-control numericDecimal-only" type="text" name="txtPrecioCarga" id="txtPrecioCarga" required min="0" maxlength="13" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onchange="validEmptyInput('txtPrecioCarga', 'invalid-precioCarga', 'La carga de combustible debe de tener un costo unitario.')">
                          <span class="input-group-addon" style="width:100px">
                            <select name="cmbMonedaPrecio" id="cmbMonedaPrecio" required>
                            </select>
                          </span>
                          <div class="invalid-feedback" id="invalid-precioCarga">La carga de combustible debe de tener un costo unitario.</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Odometro:*</label>
                      <div class="row">
                        <div class="col-lg-12 input-group">
                          <input class="form-control numeric-only" type="text" name="txtOdometroCarga" id="txtOdometroCarga" required min="0" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 200" onchange="validEmptyInput('txtOdometroCarga', 'invalid-odometroCarga', 'La carga de combustible debe de tener la medida del odometro.')">
                          <div class="invalid-feedback" id="invalid-odometroCarga">La carga de combustible debe de tener la medida del odometro.</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-3">
                      <label for="usr">Tanque lleno:*</label>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="cbxTanqueLleno" name="cbxTanqueLleno" onclick="checkTanqueLleno()">
                        <label class="form-check-label" for="cbxTanqueLleno" id="txtTanqueLleno">No</label>
                      </div>
                    </div>
                    <div class="col-lg-7">
                      
                    </div>
                    <div class="col-lg-2" style="text-align:center!important; margin-top:35px;">
                      <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirCarga">Añadir carga</a>
                    </div>
                  </div>
                </div>
                <p>* Campos requeridos</p>
                <br>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card mb-4">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table" id="tblListadoCargasCombustibleVehiculo" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>Id</th>
                              <th>Fecha de carga</th>
                              <th>Cantidad</th>
                              <th>Unidad de medida</th>
                              <th>Costo unitario</th>
                              <th>Moneda</th>
                              <th>Odometro</th>
                              <th>Tanque lleno</th>
                              <th>Responsable</th>
                              <th></th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </span>
            </form>
          </div>
        </div>
      </div>
    </div>
    `;

  $("#datos").html(html);

  cargarTablaCargasCombustible(id, _permissions.edit, _permissions.delete);

  setTimeout(function () {
    new SlimSelect({
      select: `#cmbUnidadMedidaLiquido`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbMonedaPrecio`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbUnidadMedidaLiquidoEdit`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbMonedaPrecioEdit`,
      deselectLabel: '<span class="">✖</span>',
    });
  }, 500);

  resetValidations();
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/

function cargarCMBUnidadMedidaLiquido(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vehiculo_unidadMedida" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].id) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].id}" ${selected}> 
                  ${respuesta[i].abrv}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBMonedaCostoUnitario(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vehiculo_monedaCU" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKTipoMoneda}" ${selected}> 
                  ${respuesta[i].TipoMoneda}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function checkTanqueLleno() {
  if ($("#cbxTanqueLleno").is(":checked")) {
    $("#txtTanqueLleno").html("Si");
  } else {
    $("#txtTanqueLleno").html("No");
  }
}

function checkTanqueLlenoEdit() {
  if ($("#cbxTanqueLlenoEdit").is(":checked")) {
    $("#txtTanqueLlenoEdit").html("Si");
  } else {
    $("#txtTanqueLlenoEdit").html("No");
  }
}

function cargarTablaCargasCombustible(
  id,
  _permissionsEdit,
  _permissionsDelete
) {
  $("#tblListadoCargasCombustibleVehiculo").dataTable({
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
      buttons: [],
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_cargasCombustibleVehiculoTable",
        data: id,
        data2: _permissionsEdit,
        data3: _permissionsDelete,
      },
    },
    columns: [
      { data: "Id" },
      { data: "FechaCarga" },
      { data: "Cantidad" },
      { data: "UnidadMedidaLq" },
      { data: "CostoUnitario" },
      { data: "Moneda" },
      { data: "Odometro" },
      { data: "TanqueLleno" },
      { data: "Responsable" },
      { data: "Acciones", width: "5%" },
    ],
  });
}

/* Añadir la carga */
$(document).on("click", "#btnAnadirCarga", function () {
  if ($("#formDatosCombustible")[0].checkValidity()) {
    var badFechaCarga =
      $("#invalid-fechaCarga").css("display") === "block" ? false : true;
    var badCantidadCarga =
      $("#invalid-cantidadCarga").css("display") === "block" ? false : true;
    var badPrecioCarga =
      $("#invalid-precioCarga").css("display") === "block" ? false : true;
    var badOdometroCarga =
      $("#invalid-odometroCarga").css("display") === "block" ? false : true;

    if (
      badFechaCarga &&
      badCantidadCarga &&
      badPrecioCarga &&
      badOdometroCarga
    ) {
      var datos = {
        fechaCarga: $("#txtFechaCarga").val(),
        cantidad: $("#txtCantidadCarga").val(),
        unidadMedida: $("#cmbUnidadMedidaLiquido").val(),
        costoUnitario: $("#txtPrecioCarga").val(),
        moneda: $("#cmbMonedaPrecio").val(),
        odometro: $("#txtOdometroCarga").val(),
        tanqueLleno: {
          active: $("#cbxTanqueLleno").is(":checked") ? 1 : 0,
        },
        pkVehiculo: _global.PKVehiculo,
        isEdit: 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosVehiculoCargaCombustible",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoCargasCombustibleVehiculo").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Carga de combustible registrada correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtFechaCarga").val("");
            $("#txtCantidadCarga").val("");
            $("#txtPrecioCarga").val("");
            $("#txtOdometroCarga").val("");
            $("#cbxTanqueLleno").prop("checked", false);
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
              sound: "../../../../../sounds/sound4",
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
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#txtFechaCarga").val()) {
      $("#invalid-fechaCarga").css("display", "block");
      $("#txtFechaCarga").addClass("is-invalid");
    } else {
      $("#invalid-fechaCarga").css("display", "none");
      $("#txtFechaCarga").removeClass("is-invalid");
    }

    if (!$("#txtCantidadCarga").val()) {
      $("#invalid-cantidadCarga").css("display", "block");
      $("#txtCantidadCarga").addClass("is-invalid");
    } else {
      $("#invalid-cantidadCarga").css("display", "none");
      $("#txtCantidadCarga").removeClass("is-invalid");
    }

    if (!$("#txtPrecioCarga").val()) {
      $("#invalid-precioCarga").css("display", "block");
      $("#txtPrecioCarga").addClass("is-invalid");
    } else {
      $("#invalid-precioCarga").css("display", "none");
      $("#txtPrecioCarga").removeClass("is-invalid");
    }

    if (!$("#txtOdometroCarga").val()) {
      $("#invalid-odometroCarga").css("display", "block");
      $("#txtOdometroCarga").addClass("is-invalid");
    } else {
      $("#invalid-odometroCarga").css("display", "none");
      $("#txtOdometroCarga").removeClass("is-invalid");
    }
  }
});

/* Editar carga */
$(document).on("click", "#btnEditarCarga", function () {
  if ($("#formDatosCombustibleEdit")[0].checkValidity()) {
    var badFechaCarga =
      $("#invalid-fechaCargaEdit").css("display") === "block" ? false : true;
    var badCantidadCarga =
      $("#invalid-cantidadCargaEdit").css("display") === "block" ? false : true;
    var badPrecioCarga =
      $("#invalid-precioCargaEdit").css("display") === "block" ? false : true;
    var badOdometroCarga =
      $("#invalid-odometroCargaEdit").css("display") === "block" ? false : true;

    if (
      badFechaCarga &&
      badCantidadCarga &&
      badPrecioCarga &&
      badOdometroCarga
    ) {
      var datos = {
        fechaCarga: $("#txtFechaCargaEdit").val(),
        cantidad: $("#txtCantidadCargaEdit").val(),
        unidadMedida: $("#cmbUnidadMedidaLiquidoEdit").val(),
        costoUnitario: $("#txtPrecioCargaEdit").val(),
        moneda: $("#cmbMonedaPrecioEdit").val(),
        odometro: $("#txtOdometroCargaEdit").val(),
        tanqueLleno: {
          active: $("#cbxTanqueLlenoEdit").is(":checked") ? 1 : 0,
        },
        pkVehiculo: _global.PKVehiculo,
        isEdit: _global.idCarga,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosVehiculoCargaCombustible",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoCargasCombustibleVehiculo").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Carga de combustible actualizada correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtFechaCargaEdit").val("");
            $("#txtCantidadCargaEdit").val("");
            $("#txtPrecioCargaEdit").val("");
            $("#txtOdometroCargaEdit").val("");
            $("#cbxTanqueLlenoEdit").prop("checked", false);
            _global.idCarga = 0;
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
              sound: "../../../../../sounds/sound4",
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
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#txtFechaCargaEdit").val()) {
      $("#invalid-fechaCargaEdit").css("display", "block");
      $("#txtFechaCargaEdit").addClass("is-invalid");
    } else {
      $("#invalid-fechaCargaEdit").css("display", "none");
      $("#txtFechaCargaEdit").removeClass("is-invalid");
    }

    if (!$("#txtCantidadCargaEdit").val()) {
      $("#invalid-cantidadCargaEdit").css("display", "block");
      $("#txtCantidadCargaEdit").addClass("is-invalid");
    } else {
      $("#invalid-cantidadCargaEdit").css("display", "none");
      $("#txtCantidadCargaEdit").removeClass("is-invalid");
    }

    if (!$("#txtPrecioCargaEdit").val()) {
      $("#invalid-precioCargaEdit").css("display", "block");
      $("#txtPrecioCargaEdit").addClass("is-invalid");
    } else {
      $("#invalid-precioCargaEdit").css("display", "none");
      $("#txtPrecioCargaEdit").removeClass("is-invalid");
    }

    if (!$("#txtOdometroCargaEdit").val()) {
      $("#invalid-odometroCargaEdit").css("display", "block");
      $("#txtOdometroCargaEdit").addClass("is-invalid");
    } else {
      $("#invalid-odometroCargaEdit").css("display", "none");
      $("#txtOdometroCargaEdit").removeClass("is-invalid");
    }
  }
});

function modalDatosEditCargaCombustible(idCarga) {
  _global.idCarga = idCarga;

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_vehiculo_cargaCombustible",
      datos: _global.idCarga,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtFechaCargaEdit").val(respuesta[0].fechaCarga);
      $("#txtCantidadCargaEdit").val(respuesta[0].cantidad);
      cargarCMBUnidadMedidaLiquido(
        respuesta[0].unidadMedida,
        "cmbUnidadMedidaLiquidoEdit"
      );
      $("#txtPrecioCargaEdit").val(respuesta[0].costoUnitario);
      cargarCMBMonedaCostoUnitario(respuesta[0].moneda, "cmbMonedaPrecioEdit");
      $("#txtOdometroCargaEdit").val(respuesta[0].odometro);

      if (respuesta[0].tanqueLleno == "1") {
        $("#cbxTanqueLlenoEdit").prop("checked", true);
        $("#txtTanqueLlenoEdit").html("Si");
      } else {
        $("#cbxTanqueLlenoEdit").prop("checked", false);
        $("#txtTanqueLlenoEdit").html("No");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* Eliminar la carga de combustible */
function eliminarCargaCombustible() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_vehiculo_cargaCombustible",
      datos: _global.idCarga,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoCargasCombustibleVehiculo").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó la carga de combustible con éxito!",
          sound: "../../../../../sounds/sound4",
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
          sound: "../../../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function SeguirPolizaSeguro(id) {
  _global.PKVehiculo = id;
  validarEmpresaVehiculo(id);
  cargarCMBMonedaCostoUnitario(100, "cmbMonedaPrecioPoliza");
  resetTabs("#CargarDatosPolizaSeguro");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de póliza de seguro
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosPolizaSeguro">
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">No. de póliza:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="number" class="form-control numeric-only" name="txtNoPoliza" id="txtNoPoliza" required onchange="validEmptyInput('txtNoPoliza', 'invalid-noPoliza', 'La póliza de seguro debe de tener un número.')" placeholder="No. de Póliza de seguro">
                                  <div class="invalid-feedback" id="invalid-noPoliza">La póliza de seguro debe de tener un número.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Aseguradora:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control alphaNumeric-only" name="txtAseguradora" id="txtAseguradora" required onchange="validEmptyInput('txtAseguradora', 'invalid-aseguradora', 'La póliza de seguro debe de tener una aseguradora.')" placeholder="Aseguradora">
                                  <div class="invalid-feedback" id="invalid-aseguradora">La póliza de seguro debe de tener una aseguradora.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Fecha de inicio:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="date" class="form-control" name="txtFechaInicio" id="txtFechaInicio" required onchange="validEmptyInput('txtFechaInicio', 'invalid-fechaInicio', 'La póliza de seguro debe de tener una fecha de inicio.')">
                                  <div class="invalid-feedback" id="invalid-fechaInicio">La póliza de seguro debe de tener una fecha de inicio.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Fecha de termino:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="date" class="form-control" name="txtFechaTermino" id="txtFechaTermino" required onchange="validEmptyInput('txtFechaTermino', 'invalid-fechaTermino', 'La póliza de seguro debe de tener una fecha de termino.')">
                                  <div class="invalid-feedback" id="invalid-fechaTermino">La póliza de seguro debe de tener una fecha de termino.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Inciso:</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="number" class="form-control numeric-only" name="txtInciso" id="txtInciso" maxlength="5" onchange="validEmptyInput('txtInciso', 'invalid-inciso', 'La póliza de seguro debe de tener un inciso.')" placeholder="Inciso">
                                  <div class="invalid-feedback" id="invalid-inciso">La póliza de seguro debe de tener un inciso.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Importe:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numericDecimal-only" type="text" name="txtImportePoliza" id="txtImportePoliza" required min="0" maxlength="5" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 30.00" onchange="validEmptyInput('txtImportePoliza', 'invalid-importePoliza', 'La póliza de seguro debe de tener un importe.')">
                                  <span class="input-group-addon" style="width:100px">
                                    <select name="cmbMonedaPrecioPoliza" id="cmbMonedaPrecioPoliza" required>
                                    </select>
                                  </span>
                                  <div class="invalid-feedback" id="invalid-importePoliza">La póliza de seguro debe de tener un importe.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Agente de seguros:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control alphaNumeric-only" name="txtAgenteSeguros" id="txtAgenteSeguros" required onchange="validEmptyInput('txtAgenteSeguros', 'invalid-agenteSeguros', 'La póliza de seguro debe de tener un agente de seguros.')" placeholder="Agente de seguros">
                                  <div class="invalid-feedback" id="invalid-agenteSeguros">La póliza de seguro debe de tener un agente de seguros.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Teléfono del agente:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="number" class="form-control numeric-only" name="txtTelefonoAgente" id="txtTelefonoAgente" maxlength="10" required oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" onchange="validEmptyInput('txtTelefonoAgente', 'invalid-telefonoAgente', 'La póliza de seguro debe de tener un teléfono del agente de seguros.')" placeholder="Teléfono del agente">
                                  <div class="invalid-feedback" id="invalid-telefonoAgente">La póliza de seguro debe de tener un teléfono del agente de seguros.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Teléfono de siniestros:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control numeric-only" name="txtTelefonoSiniestros" id="txtTelefonoSiniestros" maxlength="10" required oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" onchange="validEmptyInput('txtTelefonoSiniestros', 'invalid-telefonoSiniestros', 'La póliza de seguro debe de tener un teléfono del agente de siniestros.')" placeholder="Teléfono de siniestros">
                                  <div class="invalid-feedback" id="invalid-telefonoSiniestros">La póliza de seguro debe de tener un teléfono de siniestros.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-1">
                              <a style="cursor:pointer; text-decoration:none; color:#15589b;" onclick="descargarPDFPoliza()"><i class="fas fa-cloud-download-alt" id="btnExportPermissions"></i>Descargar PDF</a>
                            </div>
                            <div class="col-lg-5">
                              <label for="usr">Cambiar PDF:</label>
                              <div class="d-flex justify-content-center">
                                <div class="btnesp espAgregar">
                                  <span>Seleccionar archivo</span>
                                  <input type="file" id="inptFile" name="inptFile" accept="application/pdf">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-12 mt-4 d-flex justify-content-end">
                              <a href="#" class="mr-3 btn-custom btn-custom--border-blue" id="btnAnadirPolizaSeguro">Guardar</a>
                            </div>
                            <p>* Campos requeridos</p>
                          </div>
                        </div>
                        
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              `;

  $("#datos").html(html);

  setTimeout(function () {
    new SlimSelect({
      select: `#cmbMonedaPrecioPoliza`,
      deselectLabel: '<span class="">✖</span>',
    });
  }, 500);

  resetValidations();

  CargarDatosPolizaVehiculo(_global.PKVehiculo);
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
/* Añadir la póliza de seguro */
$(document).on("click", "#btnAnadirPolizaSeguro", function () {
  if ($("#formDatosPolizaSeguro")[0].checkValidity()) {
    var badNoPoliza =
      $("#invalid-noPoliza").css("display") === "block" ? false : true;
    var badAseguradora =
      $("#invalid-aseguradora").css("display") === "block" ? false : true;
    var badFechaInicio =
      $("#invalid-fechaInicio").css("display") === "block" ? false : true;
    var badFechaTermino =
      $("#invalid-fechaTermino").css("display") === "block" ? false : true;
    var badInciso =
      $("#invalid-inciso").css("display") === "block" ? false : true;
    var badImportePoliza =
      $("#invalid-importePoliza").css("display") === "block" ? false : true;
    var badAgenteSeguros =
      $("#invalid-agenteSeguros").css("display") === "block" ? false : true;
    var badTelefonoAgente =
      $("#invalid-telefonoAgente").css("display") === "block" ? false : true;
    var badTelefonoSiniestros =
      $("#invalid-telefonoSiniestros").css("display") === "block"
        ? false
        : true;

    if (
      badNoPoliza &&
      badAseguradora &&
      badFechaInicio &&
      badFechaTermino &&
      badInciso &&
      badImportePoliza &&
      badAgenteSeguros &&
      badTelefonoAgente &&
      badTelefonoSiniestros
    ) {
      var datos = {
        noPoliza: $("#txtNoPoliza").val(),
        aseguradora: $("#txtAseguradora").val(),
        fechaInicio: $("#txtFechaInicio").val(),
        fechaTermino: $("#txtFechaTermino").val(),
        inciso: $("#txtInciso").val(),
        importePoliza: $("#txtImportePoliza").val(),
        monedaPoliza: $("#cmbMonedaPrecioPoliza").val(),
        agenteSeguros: $("#txtAgenteSeguros").val(),
        telefonoAgente: $("#txtTelefonoAgente").val(),
        telefonoSiniestros: $("#txtTelefonoSiniestros").val(),
        archivo: $("#inptFile").val() ? 1 : 0,
        pkVehiculo: _global.PKVehiculo,
        isEdit: 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosVehiculoPolizaSeguro",
          datos,
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
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Póliza de seguro guardada correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            subirArchivoPDF(respuesta[0].idPoliza);
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
              sound: "../../../../../sounds/sound4",
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
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#txtNoPoliza").val()) {
      $("#invalid-noPoliza").css("display", "block");
      $("#txtNoPoliza").addClass("is-invalid");
    } else {
      $("#invalid-noPoliza").css("display", "none");
      $("#txtNoPoliza").removeClass("is-invalid");
    }

    if (!$("#txtAseguradora").val()) {
      $("#invalid-aseguradora").css("display", "block");
      $("#txtAseguradora").addClass("is-invalid");
    } else {
      $("#invalid-aseguradora").css("display", "none");
      $("#txtAseguradora").removeClass("is-invalid");
    }

    if (!$("#txtFechaInicio").val()) {
      $("#invalid-fechaInicio").css("display", "block");
      $("#txtFechaInicio").addClass("is-invalid");
    } else {
      $("#invalid-fechaInicio").css("display", "none");
      $("#txtFechaInicio").removeClass("is-invalid");
    }

    if (!$("#txtFechaTermino").val()) {
      $("#invalid-fechaTermino").css("display", "block");
      $("#txtFechaTermino").addClass("is-invalid");
    } else {
      $("#invalid-fechaTermino").css("display", "none");
      $("#txtFechaTermino").removeClass("is-invalid");
    }

    if (!$("#txtInciso").val()) {
      $("#invalid-inciso").css("display", "block");
      $("#txtInciso").addClass("is-invalid");
    } else {
      $("#invalid-inciso").css("display", "none");
      $("#txtInciso").removeClass("is-invalid");
    }

    if (!$("#txtImportePoliza").val()) {
      $("#invalid-importePoliza").css("display", "block");
      $("#txtImportePoliza").addClass("is-invalid");
    } else {
      $("#invalid-importePoliza").css("display", "none");
      $("#txtImportePoliza").removeClass("is-invalid");
    }

    if (!$("#txtAgenteSeguros").val()) {
      $("#invalid-agenteSeguros").css("display", "block");
      $("#txtAgenteSeguros").addClass("is-invalid");
    } else {
      $("#invalid-agenteSeguros").css("display", "none");
      $("#txtAgenteSeguros").removeClass("is-invalid");
    }

    if (!$("#txtTelefonoAgente").val()) {
      $("#invalid-telefonoAgente").css("display", "block");
      $("#txtTelefonoAgente").addClass("is-invalid");
    } else {
      $("#invalid-telefonoAgente").css("display", "none");
      $("#txtTelefonoAgente").removeClass("is-invalid");
    }

    if (!$("#txtTelefonoSiniestros").val()) {
      $("#invalid-telefonoSiniestros").css("display", "block");
      $("#txtTelefonoSiniestros").addClass("is-invalid");
    } else {
      $("#invalid-telefonoSiniestros").css("display", "none");
      $("#txtTelefonoSiniestros").removeClass("is-invalid");
    }
  }
});

function subirArchivoPDF(idPoliza) {
  var dataFile = new FormData();
  $.each($("input[type=file]")[0].files, function (i, file) {
    dataFile.append("file-" + i, file);
  });

  $.ajax({
    url: "uploadFile.php",
    data: dataFile,
    cache: false,
    contentType: false,
    processData: false,
    type: "POST",
    success: function (respuesta) {
      renameFile(respuesta, idPoliza);
    },
  });
}

function renameFile(name, idPoliza) {
  $.ajax({
    url: "renameFile.php",
    type: "POST",
    data: { url: name, id: idPoliza },
    success: function (data) {
      _global.rutaFile = data;
    },
  });
}

function CargarDatosPolizaVehiculo(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_vehiculo_poliza",
      datos: id,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtNoPoliza").val(respuesta[0].noPoliza);
      $("#txtAseguradora").val(respuesta[0].aseguradora);
      $("#txtFechaInicio").val(respuesta[0].fechaInicio);
      $("#txtFechaTermino").val(respuesta[0].fechaTermino);
      $("#txtInciso").val(respuesta[0].inciso);
      $("#txtImportePoliza").val(respuesta[0].importe);
      cargarCMBMonedaCostoUnitario(
        respuesta[0].moneda,
        "cmbMonedaPrecioPoliza"
      );
      $("#txtAgenteSeguros").val(respuesta[0].agenteSeguros);
      $("#txtTelefonoAgente").val(respuesta[0].telefonoAgente);
      $("#txtTelefonoSiniestros").val(respuesta[0].telefonoSiniestros);
      _global.rutaFile = respuesta[0].archivo;
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function descargarPDFPoliza() {
  if (_global.rutaFile != "") {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_PKEmpresa"
      },
      success: function (data) {
        empresa = data;
        _global.rutaFile='http://app.timlid.com/file_server/'+empresa+'/archivos/logistica/vehiculos/pdfPolizas/'+_global.rutaFile;
        window.open(_global.rutaFile);
      },
    });
  } else {
    Lobibox.notify("success", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "¡No se ha cargado un PDF!",
      sound: "../../../../../sounds/sound4",
    });
  }
}

function SeguirDatosServicios(id) {
  _global.PKVehiculo = id;
  validarEmpresaVehiculo(id);
  cargarCMBTipoServicio("", "cmbTipoServicio");
  cargarCMBMonedaCostoUnitario(100, "cmbMonedaCostoServicio");
  resetTabs("#CargarDatosServicios");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de servicios
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosServicios"> 
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Servicio:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control" name="txtServicio" id="txtServicio" required onchange="validEmptyInput('txtServicio', 'invalid-servicio', 'El servicio debe de tener un nombre.')" placeholder="Ej. Afinación">
                                  <div class="invalid-feedback" id="invalid-servicio">El servicio debe de tener un nombre.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-8">
                              <label for="usr">Descripción:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control" name="txtDescripcion" id="txtDescripcion" required onchange="validEmptyInput('txtDescripcion', 'invalid-descripcion', 'El servicio debe de tener una descripcón.')" placeholder="Descripción del servicio">
                                  <div class="invalid-feedback" id="invalid-descripcion">El servicio debe de tener una descripcón.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-4">
                              <label for="usr">Lugar:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input type="text" class="form-control" name="txtLugar" id="txtLugar" required onchange="validEmptyInput('txtLugar', 'invalid-lugar', 'El servicio debe de tener un lugar donde se realizó.')" placeholder="Ej. Av. México 0001, Guadalaja, Jalisco">
                                  <div class="invalid-feedback" id="invalid-lugar">El servicio debe de tener un lugar donde se realizó.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Tipo de servicio:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <select name="cmbTipoServicio" id="cmbTipoServicio" required>
                                  </select>
                                  <div class="invalid-feedback" id="invalid-tipoServicio">El servicio debe tener un tipo.</div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <label for="usr">Costo:*</label>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <input class="form-control numericDecimal-only" type="text" name="txtCostoServicio" id="txtCostoServicio" required min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 3000.00" onchange="validEmptyInput('txtCostoServicio', 'invalid-costoServicio', 'El servicio debe de tener un costo.')">
                                  <span class="input-group-addon" style="width:100px">
                                    <select name="cmbMonedaCostoServicio" id="cmbMonedaCostoServicio" required>
                                    </select>
                                  </span>
                                  <div class="invalid-feedback" id="invalid-costoServicio">El servicio debe de tener un costo.</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                        <div class="row">
                          <div class="col-lg-5">
                            <label for="usr">PDF:</label>
                            <div class="d-flex justify-content-center">
                              <div class="btnesp espAgregar">
                                <span>Seleccionar archivo</span>
                                <input type="file" id="inptFile" name="inptFile" accept="application/pdf">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                        <div class="form-group">
                          <div class="row">
                            <div class="col-lg-10">
                            </div>
                            <div class="col-lg-2" style="text-align:center!important; margin-top:35px;">
                              <a href="#" class="btn-custom btn-custom--border-blue" id="btnAnadirServicio">Añadir servicio</a>
                            </div>
                          </div>
                        </div>
                        <label for="">* Campos requeridos</label>

                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoVehiculosServicios" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Servicio</th>
                                      <th>Descripción</th>
                                      <th>Lugar</th>
                                      <th>Tipo de servicio</th>
                                      <th>Costo</th>
                                      <th>Moneda</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  setTimeout(function () {
    new SlimSelect({
      select: `#cmbTipoServicio`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbMonedaCostoServicio`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbTipoServicioEdit`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbMonedaCostoServicioEdit`,
      deselectLabel: '<span class="">✖</span>',
    });
  }, 500);

  cargarTablaServicios(id, _permissions.edit, _permissions.delete);

  resetValidations();
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBTipoServicio(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vehiculo_tipoServicio" },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoServicio) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKTipoServicio}" ${selected}> 
                  ${respuesta[i].TipoServicio}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarTablaServicios(id, _permissionsEdit, _permissionsDelete) {
  $("#tblListadoVehiculosServicios").dataTable({
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
      buttons: [],
    },
    pagingType: "full_numbers",
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_serviciosVehiculoTable",
        data: id,
        data2: _permissionsEdit,
        data3: _permissionsDelete,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Servicio" },
      { data: "Descripcion" },
      { data: "Lugar" },
      { data: "TipoServicio" },
      { data: "Costo" },
      { data: "Moneda" },
      { data: "Acciones", width: "5%" },
    ],
  });
}

/* Añadir el servicio */
$(document).on("click", "#btnAnadirServicio", function () {
  if ($("#formDatosServicios")[0].checkValidity()) {
    var badServicio =
      $("#invalid-servicio").css("display") === "block" ? false : true;
    var badDescripcion =
      $("#invalid-descripcion").css("display") === "block" ? false : true;
    var badLugar =
      $("#invalid-lugar").css("display") === "block" ? false : true;
    var badTipoServicio =
      $("#invalid-tipoServicio").css("display") === "block" ? false : true;
    var badCostoServicio =
      $("#invalid-costoServicio").css("display") === "block" ? false : true;

    if (
      badServicio &&
      badDescripcion &&
      badLugar &&
      badTipoServicio &&
      badCostoServicio
    ) {
      var datos = {
        servicio: $("#txtServicio").val(),
        descripcion: $("#txtDescripcion").val(),
        lugar: $("#txtLugar").val(),
        tipoServicio: $("#cmbTipoServicio").val(),
        costoServico: $("#txtCostoServicio").val(),
        moneda: $("#cmbMonedaCostoServicio").val(),
        pkVehiculo: _global.PKVehiculo,
        archivo: $("#inptFile").val() ? 1 : 0,
        isEdit: 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosVehiculoServicio",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoVehiculosServicios").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Servicio registrado correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtServicio").val("");
            $("#txtDescripcion").val("");
            $("#txtLugar").val("");
            $("#txtCostoServicio").val("");
            cargarCMBMonedaCostoUnitario(100, "cmbMonedaCostoServicio");
            subirArchivoPDFServicio(respuesta[0].idServicio);
            $("#inptFile").val('');
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
              sound: "../../../../../sounds/sound4",
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
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#txtServicio").val()) {
      $("#invalid-servicio").css("display", "block");
      $("#txtServicio").addClass("is-invalid");
    } else {
      $("#invalid-servicio").css("display", "none");
      $("#txtServicio").removeClass("is-invalid");
    }

    if (!$("#txtDescripcion").val()) {
      $("#invalid-descripcion").css("display", "block");
      $("#txtDescripcion").addClass("is-invalid");
    } else {
      $("#invalid-descripcion").css("display", "none");
      $("#txtDescripcion").removeClass("is-invalid");
    }

    if (!$("#txtLugar").val()) {
      $("#invalid-lugar").css("display", "block");
      $("#txtLugar").addClass("is-invalid");
    } else {
      $("#invalid-lugar").css("display", "none");
      $("#txtLugar").removeClass("is-invalid");
    }

    if (!$("#cmbTipoServicio").val()) {
      $("#invalid-tipoServicio").css("display", "block");
      $("#cmbTipoServicio").addClass("is-invalid");
    } else {
      $("#invalid-tipoServicio").css("display", "none");
      $("#cmbTipoServicio").removeClass("is-invalid");
    }

    if (!$("#txtCostoServicio").val()) {
      $("#invalid-costoServicio").css("display", "block");
      $("#txtCostoServicio").addClass("is-invalid");
    } else {
      $("#invalid-costoServicio").css("display", "none");
      $("#txtCostoServicio").removeClass("is-invalid");
    }
  }
});

$(document).on("click", "#btnEditarServicio", function () {
  if ($("#formDatosServiciosEdit")[0].checkValidity()) {
    var badServicio =
      $("#invalid-servicioEdit").css("display") === "block" ? false : true;
    var badDescripcion =
      $("#invalid-descripcionEdit").css("display") === "block" ? false : true;
    var badLugar =
      $("#invalid-lugarEdit").css("display") === "block" ? false : true;
    var badTipoServicio =
      $("#invalid-tipoServicioEdit").css("display") === "block" ? false : true;
    var badCostoServicio =
      $("#invalid-costoServicioEdit").css("display") === "block" ? false : true;

    if (
      badServicio &&
      badDescripcion &&
      badLugar &&
      badTipoServicio &&
      badCostoServicio
    ) {
      var datos = {
        servicio: $("#txtServicioEdit").val(),
        descripcion: $("#txtDescripcionEdit").val(),
        lugar: $("#txtLugarEdit").val(),
        tipoServicio: $("#cmbTipoServicioEdit").val(),
        costoServico: $("#txtCostoServicioEdit").val(),
        moneda: $("#cmbMonedaCostoServicioEdit").val(),
        pkVehiculo: _global.PKVehiculo,
        isEdit: _global.idServicio,
        archivo: $("#inptFile").val() ? 1 : 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosVehiculoServicio",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoVehiculosServicios").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Servicio registrado correctamente!",
              sound: "../../../../../sounds/sound4",
            });

            $("#txtServicioEdit").val("");
            $("#txtDescripcionEdit").val("");
            $("#txtLugarEdit").val("");
            $("#txtCostoServicioEdit").val("");
            cargarCMBMonedaCostoUnitario(100, "cmbMonedaCostoServicioEdit");
            subirArchivoPDFServicio(respuesta[0].idServicio, 1);
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
              sound: "../../../../../sounds/sound4",
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
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#txtServicioEdit").val()) {
      $("#invalid-servicioEdit").css("display", "block");
      $("#txtServicioEdit").addClass("is-invalid");
    } else {
      $("#invalid-servicioEdit").css("display", "none");
      $("#txtServicioEdit").removeClass("is-invalid");
    }

    if (!$("#txtDescripcionEdit").val()) {
      $("#invalid-descripcionEdit").css("display", "block");
      $("#txtDescripcionEdit").addClass("is-invalid");
    } else {
      $("#invalid-descripcionEdit").css("display", "none");
      $("#txtDescripcionEdit").removeClass("is-invalid");
    }

    if (!$("#txtLugarEdit").val()) {
      $("#invalid-lugarEdit").css("display", "block");
      $("#txtLugarEdit").addClass("is-invalid");
    } else {
      $("#invalid-lugarEdit").css("display", "none");
      $("#txtLugarEdit").removeClass("is-invalid");
    }

    if (!$("#cmbTipoServicioEdit").val()) {
      $("#invalid-tipoServicioEdit").css("display", "block");
      $("#cmbTipoServicioEdit").addClass("is-invalid");
    } else {
      $("#invalid-tipoServicioEdit").css("display", "none");
      $("#cmbTipoServicioEdit").removeClass("is-invalid");
    }

    if (!$("#txtCostoServicioEdit").val()) {
      $("#invalid-costoServicioEdit").css("display", "block");
      $("#txtCostoServicioEdit").addClass("is-invalid");
    } else {
      $("#invalid-costoServicioEdit").css("display", "none");
      $("#txtCostoServicioEdit").removeClass("is-invalid");
    }
  }
});

function modalDatosEditServicios(idServicio) {
  //se recetea el modal
  form=document.getElementById('formDatosServiciosEdit');
  form.reset();

  _global.idServicio = idServicio;

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_vehiculo_servicio",
      datos: _global.idServicio,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#txtServicioEdit").val(respuesta[0].servicio);
      $("#txtDescripcionEdit").val(respuesta[0].descripcion);
      $("#txtLugarEdit").val(respuesta[0].lugarServicio);
      cargarCMBTipoServicio(respuesta[0].tipoServicio, "cmbTipoServicioEdit");
      $("#txtCostoServicioEdit").val(respuesta[0].costo);
      cargarCMBMonedaCostoUnitario(
        respuesta[0].monedaId,
        "cmbMonedaCostoServicioEdit"
      );
      _global.rutaFile=respuesta[0].archivo
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function subirArchivoPDFServicio(idServicio, edit=0) {
  var dataFile = new FormData();
  //comprueba si se accede desde agregar servicio
  if(edit==0){
    $.each($("input[type=file]")[0].files, function (i, file) {
      dataFile.append("file-" + i, file);
    });
  }else{
    $.each($("input[type=file]")[1].files, function (i, file) {
      dataFile.append("file-" + i, file);
    });
  }
  

  $.ajax({
    url: "uploadFileServicio.php",
    data: dataFile,
    cache: false,
    contentType: false,
    processData: false,
    type: "POST",
    success: function (respuesta) {
      renameFileServicio(respuesta, idServicio);
    },
  });
}

function renameFileServicio(name, idServicio) {
  $.ajax({
    url: "renameFileServicio.php",
    type: "POST",
    data: { url: name, id: idServicio },
    success: function (data) {
      _global.rutaFile = data;
    },
  });
}


function descargarPDFServicio(archivo=0) {
  if(archivo!=0){
    _global.rutaFile =archivo;
  }
  if (_global.rutaFile != "") {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_PKEmpresa"
      },
      success: function (data) {
        empresa = data;
        _global.rutaFile='http://app.timlid.com/file_server/'+empresa+'/archivos/logistica/vehiculos/pdfServicios/'+_global.rutaFile;
        window.open(_global.rutaFile);
      },
    });
  } else {
    Lobibox.notify("success", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/checkmark.svg",
      msg: "¡No se ha cargado un PDF!",
      sound: "../../../../../sounds/sound4",
    });
  }
}

/* Eliminar el servicio */
function eliminarServicio() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_vehiculo_servicio",
      datos: _global.idServicio,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblListadoVehiculosServicios").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el servicio con éxito!",
          sound: "../../../../../sounds/sound4",
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
          sound: "../../../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function SeguirBitacoraPrestamos(id) {
  _global.PKVehiculo = id;
  modalPrestamos();
  validarEmpresaVehiculo(id);
  cargarCMBEmpleado("", "cmbEmpleado", 0);
  cargarCMBEmpleado("", "cmbEmpleadoAdd");
  cargarCMBEmpleado("", "cmbAutorizaAdd", 0);
  resetTabs("#CargarDatosPrestamos");

  var html = `<div class="card shadow mb-4">
                <div class="card-header">
                  Tarjeta de bitácora de préstamos
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="formDatosPrestamos"> 
                        <div class="form-group">
                            <div class="row">
                              <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6" style="margin-left:20px;">
                                <label for="cmbProveedor">Empleado:</label>
                                <select name="cmbEmpleado" id="cmbEmpleado" required>
                              </select>
                              <div class="invalid-feedback" id="invalid-cmbCliente">.</div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                              <label for="txtDateFrom">De:</label>
                              <input class="form-control" type="date" name="txtDateFrom" id="txtDateFrom">
                              <div class="invalid-feedback" id="invalid-txtDateFrom"></div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                              <label for="txtDateTo">Hasta:</label>
                              <input class="form-control" type="date" name="txtDateTo" id="txtDateTo">
                              <div class="invalid-feedback" id="invalid-txtDateTo">.</div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-xs-6">
                              <a class="btn-custom btn-custom--blue" id="btnFilterExits" style="margin-top: 10px!important">Filtrar</a>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="form-group">
                          <!-- DataTales Example -->
                          <div class="card mb-4">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="table" id="tblListadoVehiculoPrestamos" width="100%" cellspacing="0">
                                  <thead>
                                    <tr>
                                      <th>Id</th>
                                      <th>Empleado</th>
                                      <th>Motivo</th>
                                      <th>Fecha de préstamo</th>
                                      <th>Estatus</th>
                                      <th>Acciones</th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>`;

  $("#datos").html(html);

  cargarTablaPrestamos(id, _permissions.edit, _permissions.delete);

  setTimeout(function () {
    new SlimSelect({
      select: `#cmbEmpleado`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbEmpleadoAdd`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbEmpleadoEdit`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbAutorizaAdd`,
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: `#cmbAutorizaEdit`,
      deselectLabel: '<span class="">✖</span>',
    });    

    new SlimSelect({
      select: `#cmbCombustibleAdd`,
      deselectLabel: '<span class="">✖</span>',
    }); 

    slimSelect_CombustibleEdit = new SlimSelect({
      select: `#cmbCombustibleEdit`,
      deselectLabel: '<span class="">✖</span>',
    });
    
    new SlimSelect({
      select: `#cmbCombustibleClose`,
      deselectLabel: '<span class="">✖</span>',
    });     
  }, 500);

  resetValidations();
}

/*----------------------Funciones de carga de datos de los combos-------------------------------*/
function cargarCMBEmpleado(data, input, isRequired=1) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vehiculo_empleados" },
    dataType: "json",
    success: function (respuesta) {
      if(isRequired==0 && data==""){
        html += '<option disabled selected value="f">Selecciona un empleado</option>'
      }else if(isRequired==0 && data!=""){
        html += '<option disabled value="f">Selecciona un empleado</option>'
      }else{
        html += '<option disabled value="f">Selecciona un empleado</option>'
      }

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKEmpleado) {
          selected = "selected";
        } else {
          selected = "";
        }

        html += `<option value="${respuesta[i].PKEmpleado}" ${selected}> 
                  ${respuesta[i].NombreCompleto}
                </option>`;
      });

      $(`#${input}`).html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarTablaPrestamos(id, _permissionsEdit, _permissionsDelete) {
  var topButtons = [
    {
      text: '<i class="fas fa-plus-square"></i> Añadir registro',
      className: "btn-table-custom--blue",
      action: function () {
        form=document.getElementById("formDatosPrestamosAdd");
        form.reset();
        //se reinician los campos
        $("#invalid-empleadoAdd").css("display", "none");
        $("#cmbEmpleadoAdd").removeClass("is-invalid");
    
        $("#invalid-motivoPrestamoAdd").css("display", "none");
        $("#txtMotivoPrestamoAdd").removeClass("is-invalid");
    
        $("#invalid-fechaPrestamoAdd").css("display", "none");
        $("#txtFechaPrestamoAdd").removeClass("is-invalid");
        
        $("#invalid-nivelCombustibleAdd").css("display", "none");
        $("#cmbCombustibleAdd").removeClass("is-invalid");
     
        $("#invalid-KilometrajeAdd").css("display", "none");
        $("#txtModalAddkilometraje").removeClass("is-invalid");

        $("#añadir_Prestamo").modal("show");
      },
    },
  ];
  
  $("#tblListadoVehiculoPrestamos").DataTable({
    retrieve:true,
    destroy:true,
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
      buttons: topButtons,
    },
    ajax: {
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "get_prestamosVehiculoTable",
        data: id,
        data2: _permissionsEdit,
        data3: _permissionsDelete,
      },
    },
    columns: [
      { data: "Id" },
      { data: "Empleado" },
      { data: "Motivo" },
      { data: "FechaPrestamo" },
      { data: "Estatus"},
      { data: "Acciones", width: "5%" },
    ],
  });
}

//valida campos para filtrar
function validarImputs(){
  redFlag = true;

  inputID= "cmbEmpleado"; 
  invalidDivID = "invalid-cmbCliente";
  textInvalidDiv = "Se requiere almenos un dato";

  inputID2= "txtDateFrom";
  invalidDivID2 = "invalid-txtDateFrom";

  inputID3= "txtDateTo";
  invalidDivID3 = "invalid-txtDateTo";

  if (($('select[name='+inputID+'] option').filter(':selected').val())=="f" && (($('#'+inputID2).val()=="") || ($('#'+inputID2).val()==null)) && (($('#'+inputID3).val()=="") || ($('#'+inputID3).val()==null))) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).show();
    $("#" + invalidDivID).text(textInvalidDiv);

    $("#" + inputID2).addClass("is-invalid");
    $("#" + invalidDivID2).show();
    $("#" + invalidDivID2).text(textInvalidDiv);

    $("#" + inputID3).addClass("is-invalid");
    $("#" + invalidDivID3).show();
    $("#" + invalidDivID3).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).hide();
    $("#" + invalidDivID).text(textInvalidDiv);

    $("#" + inputID2).removeClass("is-invalid");
    $("#" + invalidDivID2).hide();
    $("#" + invalidDivID2).text(textInvalidDiv);

    $("#" + inputID3).removeClass("is-invalid");
    $("#" + invalidDivID3).hide();
    $("#" + invalidDivID3).text(textInvalidDiv);
    redFlag = false;
  }
  return redFlag;
}

//funcion para filtrar
function filtra_indexPagos(){
  if(!validarImputs()){ 
    var datos = {
      pkVehiculo:_global.PKVehiculo,
      empleado: document.getElementById("cmbEmpleado").value,
      from: $("#txtDateFrom").val(),
      to: $("#txtDateTo").val(),
      data2: _permissions.edit,
      data3: _permissions.delete
    };

    $.ajax({
      url : '../../php/funciones.php',
      data : {
        clase: "get_data",
        funcion: "get_prestamosVehiculoTableFiltered", 
        data: datos,
      },
      type : 'get',
      dataType : 'json',
      success : function(json) {
        var tabla= $("#tblListadoVehiculoPrestamos").DataTable();
        tabla.clear().draw();
        tabla.rows.add(json.data).draw();
      }
    });
  }
}

$(document).on("click", "#btnFilterExits", function(){
  filtra_indexPagos();
});

/* Añadir el prestamo */
$(document).on("click", "#btnAñadirPrestamo", function () {
  if ($("#formDatosPrestamosAdd")[0].checkValidity()) {
    var badEmpleado =
      $("#invalid-empleadoAdd").css("display") === "block" ? false : true;
    var badMotivoPrestamo =
      $("#invalid-motivoPrestamoAdd").css("display") === "block" ? false : true;
    var badkilometraje =
      $("#invalid-KilometrajeAdd").css("display") === "block" ? false : true;
    var badCombustible =
      $("#invalid-nivelCombustibleAdd").css("display") === "block" ? false : true;
    var badFechaPrestamo =
      $("#invalid-fechaPrestamoAdd").css("display") === "block" ? false : true;

    if (badEmpleado && badMotivoPrestamo && badFechaPrestamo && badCombustible && badkilometraje) {
      var datos = {
        empleado: $("#cmbEmpleadoAdd").val(),
        motivo: $("#txtMotivoPrestamoAdd").val(),
        nivel_combustible_inicio : $("#cmbCombustibleAdd").val(),
        id_autorizo : $("#cmbAutorizaAdd").val(),
        kilometraje_inicio : $("#txtModalAddkilometraje").val(),
        fecha: $("#txtFechaPrestamoAdd").val(),
        pkVehiculo: _global.PKVehiculo,
        isEdit: 0,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosVehiculoPrestamo",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].haveOpen==0) {
            if (respuesta[0].status) {
              $("#tblListadoVehiculoPrestamos").DataTable().ajax.reload();
              if(respuesta[0].isCreatePdf==0){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/warning_circle.svg",
                  msg: "¡Prestamo registrado, Pdf con conflictos!",
                  sound: "../../../../../sounds/sound4",
                });
              }else{
                abrirPDFPrestamo(respuesta[0].idPrestamo);
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "¡Préstamo registrado correctamente!",
                  sound: "../../../../../sounds/sound4",
                });
              }
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
                sound: "../../../../../sounds/sound4",
              });
            }
          }else{
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              msg: "¡Ya se encuentra un prestamo activo para este vehículo!",
              sound: "../../../../../sounds/sound4",
            });
          }
          $("#añadir_Prestamo").modal("hide");
        },
        error: function (error) {
          console.log(error);
          $("#añadir_Prestamo").modal("hide");
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {
    if (!$("#cmbEmpleadoAdd").val()) {
      $("#invalid-empleadoAdd").css("display", "block");
      $("#cmbEmpleadoAdd").addClass("is-invalid");
    } else {
      $("#invalid-empleadoAdd").css("display", "none");
      $("#cmbEmpleadoAdd").removeClass("is-invalid");
    }

    if (!$("#txtMotivoPrestamoAdd").val()) {
      $("#invalid-motivoPrestamoAdd").css("display", "block");
      $("#txtMotivoPrestamoAdd").addClass("is-invalid");
    } else {
      $("#invalid-motivoPrestamoAdd").css("display", "none");
      $("#txtMotivoPrestamoAdd").removeClass("is-invalid");
    }

    if (!$("#txtFechaPrestamoAdd").val()) {
      $("#invalid-fechaPrestamoAdd").css("display", "block");
      $("#txtFechaPrestamoAdd").addClass("is-invalid");
    } else {
      $("#invalid-fechaPrestamoAdd").css("display", "none");
      $("#txtFechaPrestamoAdd").removeClass("is-invalid");
    }

    if (!$("#cmbCombustibleAdd").val()) {
      $("#invalid-nivelCombustibleAdd").css("display", "block");
      $("#cmbCombustibleAdd").addClass("is-invalid");
    } else {
      $("#invalid-nivelCombustibleAdd").css("display", "none");
      $("#cmbCombustibleAdd").removeClass("is-invalid");
    }
    
    if (!$("#txtModalAddkilometraje").val()) {
      $("#invalid-kilometrajeAdd").css("display", "block");
      $("#txtModalAddkilometraje").addClass("is-invalid");
    } else {
      $("#invalid-kilometrajeAdd").css("display", "none");
      $("#txtModalAddkilometraje").removeClass("is-invalid");
    }
  }
});

//verifica el estatus del prestamo para cerrarlo
$(document).on("click", "#btn_cerrarPrestamo_pantallaEdit", function(){
  if(_global.estatusPrestamo===1){
    form= document.getElementById("form_cerrarPrestamo");
    form.reset();
    $("#cerrar_PrestamoVehiculo").modal("show");
  }else{
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡El prestamo ya ha sido cerrado!",
    });
  }
});

$(document).on("click", "#btnEditarPrestamo", function () {
  if(_global.estatusPrestamo===1){
    if ($("#formDatosPrestamosEdit")[0].checkValidity()) {
      var badEmpleado =
        $("#invalid-empleadoEdit").css("display") === "block" ? false : true;
      var badMotivoPrestamo =
        $("#invalid-motivoPrestamoEdit").css("display") === "block" ? false : true;
      var badkilometraje =
        $("#invalid-KilometrajeEdit").css("display") === "block" ? false : true;
      var badCombustible =
        $("#invalid-nivelCombustibleEdit").css("display") === "block" ? false : true;
      
      var badFechaPrestamo =
        $("#invalid-fechaPrestamoEdit").css("display") === "block" ? false : true;
  
      if (badEmpleado && badMotivoPrestamo && badFechaPrestamo && badCombustible && badkilometraje) {
        var datos = {
          empleado: $("#cmbEmpleadoEdit").val(),
          motivo: $("#txtMotivoPrestamoEdit").val(),
          nivel_combustible_inicio : $("#cmbCombustibleEdit").val(),
          id_autorizo : $("#cmbAutorizaEdit").val(),
          kilometraje_inicio : $("#txtModalEditkilometraje").val(),
          fecha: $("#txtFechaPrestamoEdit").val(),
          pkVehiculo: _global.PKVehiculo,
          isEdit: _global.idPrestamo,
        };
  
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_datosVehiculoPrestamo",
            datos,
          },
          dataType: "json",
          success: function (respuesta) {
            if (respuesta[0].status) {
              $("#editar_Prestamos").modal("hide");
              $("#tblListadoVehiculoPrestamos").DataTable().ajax.reload();
              if(respuesta[0].isCreatePdf==0){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/warning_circle.svg",
                  msg: "¡Prestamo guardado, Pdf con conflictos!",
                  sound: "../../../../../sounds/sound4",
                });
              }else{
                Lobibox.notify("success", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/checkmark.svg",
                  msg: "!Préstamo guardado correctamente!",
                  sound: "../../../../../sounds/sound4",
                });
              }
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
                sound: "../../../../../sounds/sound4",
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
              sound: "../../../../../sounds/sound4",
            });
          },
        });
      }
    } else {
      if (!$("#cmbEmpleadoEdit").val()) {
        $("#invalid-empleadoEdit").css("display", "block");
        $("#cmbEmpleadoEdit").addClass("is-invalid");
      } else {
        $("#invalid-empleadoEdit").css("display", "none");
        $("#cmbEmpleadoEdit").removeClass("is-invalid");
      }
  
      if (!$("#txtMotivoPrestamoEdit").val()) {
        $("#invalid-motivoPrestamoEdit").css("display", "block");
        $("#txtMotivoPrestamoEdit").addClass("is-invalid");
      } else {
        $("#invalid-motivoPrestamo").css("display", "none");
        $("#txtMotivoPrestamo").removeClass("is-invalid");
      }
  
      if (!$("#txtFechaPrestamoEdit").val()) {
        $("#invalid-fechaPrestamoEdit").css("display", "block");
        $("#txtFechaPrestamoEdit").addClass("is-invalid");
      } else {
        $("#invalid-fechaPrestamoEdit").css("display", "none");
        $("#txtFechaPrestamoEdit").removeClass("is-invalid");
      }
      
      if (!$("#cmbCombustibleEdit").val()) {
        $("#invalid-nivelCombustibleEdit").css("display", "block");
        $("#cmbCombustibleEdit").addClass("is-invalid");
      } else {
        $("#invalid-nivelCombustibleEdit").css("display", "none");
        $("#cmbCombustibleEdit").removeClass("is-invalid");
      }
      
      if (!$("#txtModalEditkilometraje").val()) {
        $("#invalid-kilometrajeEdit").css("display", "block");
        $("#txtModalEditkilometraje").addClass("is-invalid");
      } else {
        $("#invalid-kilometrajeEdit").css("display", "none");
        $("#txtModalEditkilometraje").removeClass("is-invalid");
      }
    }
  }else{
    $("#editar_Prestamos").modal("hide");
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: "¡No se puede editar un préstramo cerrado!",
    });
  }
  
});

$(document).on("click", "#btn_cerrarPrestamoVehiculo", function () {
  if ($("#form_cerrarPrestamo")[0].checkValidity()) {
    var badkilometraje =
      $("#invalid-KilometrajeEdit").css("display") === "block" ? false : true;
    var badCombustible =
      $("#invalid-nivelCombustibleEdit").css("display") === "block" ? false : true;
   
    if (badCombustible && badkilometraje) {
      var datos = {
        nivel_combustible_final: $("#cmbCombustibleClose").val(),
        kilometraje_final: $("#txtModalClosekilometraje").val(),
        pkPrestamo: _global.idPrestamo,
      };

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "delete_data",
          funcion: "close_vehiculo_prestamo",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            $("#tblListadoVehiculoPrestamos").DataTable().ajax.reload();
            $("#cerrar_PrestamoVehiculo").modal("hide");
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "!Préstamo cerrado correctamente!",
              sound: "../../../../../sounds/sound4",
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
              sound: "../../../../../sounds/sound4",
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
            sound: "../../../../../sounds/sound4",
          });
        },
      });
    }
  } else {    
    if (!$("#cmbCombustibleClose").val()) {
      $("#invalid-nivelCombustibleClose").css("display", "block");
      $("#cmbCombustibleClose").addClass("is-invalid");
    } else {
      $("#invalid-nivelCombustibleClose").css("display", "none");
      $("#cmbCombustibleClose").removeClass("is-invalid");
    }
    
    if (!$("#txtModalClosekilometraje").val()) {
      $("#invalid-kilometrajeClose").css("display", "block");
      $("#txtModalClosekilometraje").addClass("is-invalid");
    } else {
      $("#invalid-kilometrajeClose").css("display", "none");
      $("#txtModalClosekilometraje").removeClass("is-invalid");
    }
  }
});

//carga dentro de las modales los datos generales del vehiculo
function modalPrestamos(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_vehiculo_generales",
      datos: _global.PKVehiculo,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#ModalAddSubmarca").text(respuesta[0].linea);
      $("#ModalAddMarca").text(respuesta[0].marca);
      $("#ModalAddModelo").text(respuesta[0].modelo);
      $("#ModalAddPlacas").text(respuesta[0].placas);
      $("#ModalAddColor").text(respuesta[0].color);
      $("#ModalAddCombustible").text(respuesta[0].combustible);
      $("#ModalEditSubmarca").text(respuesta[0].linea);
      $("#ModalEditMarca").text(respuesta[0].marca);
      $("#ModalEditModelo").text(respuesta[0].modelo);
      $("#ModalEditPlacas").text(respuesta[0].placas);
      $("#ModalEditColor").text(respuesta[0].color);
      $("#ModalEditCombustible").text(respuesta[0].combustible);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function modalDatosEditPrestamos(idPrestamo) {
  _global.idPrestamo = idPrestamo;
  //se reinician los campos
  $("#invalid-empleadoEdit").css("display", "none");
  $("#cmbEmpleadoEdit").removeClass("is-invalid");

  $("#invalid-motivoPrestamoEdit").css("display", "none");
  $("#txtMotivoPrestamoEdit").removeClass("is-invalid");

  $("#invalid-fechaPrestamoEdit").css("display", "none");
  $("#txtFechaPrestamoEdit").removeClass("is-invalid");
  
  $("#invalid-nivelCombustibleEdit").css("display", "none");
  $("#cmbCombustibleEdit").removeClass("is-invalid");

  $("#invalid-KilometrajeEdit").css("display", "none");
  $("#txtModalEditkilometraje").removeClass("is-invalid");

  
  $("#txtMotivoPrestamoEdit").removeClass("disabled");
  $("#txtFechaPrestamoEdit").removeClass("disabled");
  $("#txtModalEditkilometraje").removeClass("disabled");
  $("#cmbEmpleadoEdit").removeClass("disabled");
  $("#cmbAutorizaEdit").removeClass("disabled");
  $("#cmbCombustibleEdit").removeClass("disabled");

  $(".bloque_botonesModal").show();


  form=document.getElementById("formDatosPrestamosEdit");
  form.reset();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_vehiculo_prestamo",
      datos: _global.idPrestamo,
    },
    dataType: "json",
    success: function (respuesta) {
      cargarCMBEmpleado(respuesta[0].empleado, "cmbEmpleadoEdit");
      cargarCMBEmpleado(respuesta[0].empleado_autorizo, "cmbAutorizaEdit", 0);
      $("#txtMotivoPrestamoEdit").val(respuesta[0].motivo);
      $("#txtFechaPrestamoEdit").val(respuesta[0].fecha);
      $("#txtModalEditkilometraje").val(respuesta[0].kilometraje_inicio);
      slimSelect_CombustibleEdit.set(respuesta[0].nivel_combustible_inicio);
      _global.estatusPrestamo=respuesta[0].estatus;
      if(respuesta[0].estatus==0){
        $("#lblEstatus").text("Préstamo cerrado");
        $(".bloque_botonesModal").hide();
        $("#txtMotivoPrestamoEdit").addClass("disabled");
        $("#txtFechaPrestamoEdit").addClass("disabled");
        $("#txtModalEditkilometraje").addClass("disabled");
        $("#cmbEmpleadoEdit").addClass("disabled");
        $("#cmbAutorizaEdit").addClass("disabled");
        $("#cmbCombustibleEdit").addClass("disabled");
      }else{
        $("#lblEstatus").text("Préstamo abierto");
      }
      $("#editar_Prestamos").modal("show");
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//abre pdf en pestaña nueva
function abrirPDFPrestamo(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_Pdf_Prestamo",
      idPrestamo:id,
    },
    xhrFields: {
      responseType: "blob",
    },
    success: function (data) {
      var url = window.URL.createObjectURL(data);
      window.open(url);
      window.URL.revokeObjectURL(url);
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
        sound: "../../../../../sounds/sound4",
      });
    },
  });
}

//alternativa descarga pdf de prestamo.
function descargarPDFPrestamo(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_Pdf_Prestamo",
      idPrestamo:id,
    },
    xhrFields: {
      responseType: "blob",
    },
    success: function (data) {
      var a = document.createElement("a");
        var url = window.URL.createObjectURL(data);
        a.href = url;
        a.download = "Prestamo-" + id + ".pdf";
        a.click();
        window.URL.revokeObjectURL(url);
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
        sound: "../../../../../sounds/sound4",
      });
    },
  });
}

//funcion para descargar el pdf del préstamo
/* function descargarPDFPrestamo(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_rutaPdf_Prestamo",
      idPrestamo:id,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        if( respuesta[0].archivo!=""|| respuesta[0].archivo!=null){
          _global.rutaFile='http://app.timlid.com/file_server/'+respuesta[0].PKEmpresa+'/archivos/logistica/vehiculos/pdfPrestamos/'+respuesta[0].archivo+'.pdf';
          var link = document.createElement("a");
          link.href = _global.rutaFile;
          link.download = _global.rutaFile.substr(
            _global.rutaFile.lastIndexOf("/") + 1
          );
          link.click();
        }else{
          Lobibox.notify("Warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: "¡No se ha encontrado el PDF!",
            sound: "../../../../../sounds/sound4",
          })
        }
        
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡No se ha encontrado un PDF!",
          sound: "../../../../../sounds/sound4",
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
        sound: "../../../../../sounds/sound4",
      });
    },
  });
} */

function eliminarPrestamoVehiculo() {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_vehiculo_prestamo",
      datos: _global.idPrestamo,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        if(respuesta[0].result==1){
          $("#tblListadoVehiculoPrestamos").DataTable().ajax.reload();
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Se eliminó el préstamo con éxito!",
            sound: '../../../../../sounds/sound4'
          });
        }else{
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: "¡Solo se pueden borrar préstamos abiertos!",
            sound: '../../../../../sounds/sound4'
          });
        }
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
          sound: "../../../../../sounds/sound4",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

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

function resetValidations() {
  $(".alpha-only").on("input", function () {
    var regexp = /[^a-zA-Z ]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente letras y numeros*/
  $(".alphaNumeric-only").on("input", function () {
    var regexp = /[^a-zA-Z0-9 @.]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente letras y numeros sin punto*/
  $(".alphaNumericNDot-only").on("input", function () {
    var regexp = /[^a-zA-Z0-9 @]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente numeros*/
  $(".numeric-only").on("input", function () {
    console.log($(this).val());
    var regexp = /[^0-9]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir solamente numeros y ":" reloj*/
  $(".time-only").on("input", function () {
    var regexp = /[^0-9:]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });

  /*Permitir numero decimales */
  $(".numericDecimal-only").on("input", function () {
    var regexp = /[^\d.]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }
  });
  $("#txtCantidad").keypress(function (event) {
    event.preventDefault();
  });
  $("#txtClabeU").keypress(function (event) {
    event.preventDefault();
  });
}

function validarEmpresaVehiculo(pkVehiculo) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_EmpresaVehiculo",
      data: pkVehiculo,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["valido"]) == "1") {
        //return true;
      } else {
        window.location.href = "../vehiculos";
        //return false;
      }
    },
  });
}

function validate_Permissions(pkPantalla, pestana) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;

      if (pestana == "url") {
        if (_permissions.add == "0") {
          window.location.href = "../productos";
        }
      }
    },
  });
}

