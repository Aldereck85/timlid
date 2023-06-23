$(document).ready(function () {
    $("#btnAgregar").css("display", "none");
    $("#btnAgregarDin").css("display", "none");
    //$("#btnAgregarGasto").css("display","none");
    $("#cajaChicaA").hide();
  });
  
  $(document).ready(function () {
    $("#btnRetirar").css("display", "none");
  });
  
  function lobiSucces(msjbien) {
    Lobibox.notify("success", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top", //or 'center bottom'
      icon: true,
      //img: '<i class="fas fa-check-circle"></i>',
      img: "../../img/timdesk/checkmark.svg",
      msg: msjbien,
    });
  }
  
  function lobiWarning(msjwarning) {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      img: null,
      msg: msjwarning,
    });
  }
  
  function lobiError(msjerror) {
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top", //or 'center bottom'
      icon: true,
      img: "../../img/chat/notificacion_error.svg",
      msg: msjerror,
    });
  }
  
  //Creamos la Funcion
  function validaCLABE1(evt, input) {
    var key = window.Event ? evt.which : evt.keyCode;
  
    if (key == 46) {
      //$("#result3").val("HHHH");
    } else {
      $("#result2").val($("#txtCLABE").val().length); //Detectamos los Caracteres del Input
      $("#result2").addClass("mui--is-not-empty"); //Agregamos la Clase de Mui para decir que el input no esta vacio y que suba el Texto del Label(Como cuando haces Focus)
      var valor = $("#result2").val();
      if (valor < 18) {
        $("#invalid-claveCuenta").text("La CLABE no es valida.");
        $("#invalid-claveCuenta").css("display", "block");
        $("#txtCLABE").addClass("is-invalid");
      } else {
        $("#invalid-claveCuenta").css("display", "none");
        $("#txtCLABE").removeClass("is-invalid");
        return false;
      }
    }
  }
  
  function validaNoCuenta() {
    $("#result1").val($("#txtNoCuenta").val().length); //Detectamos los Caracteres del Input
    $("#result1").addClass("mui--is-not-empty"); //Agregamos la Clase de Mui para decir que el input no esta vacio y que suba el Texto del Label(Como cuando haces Focus)
    var valor = $("#result1").val();
    if (valor < 11) {
      $("#notaNoCuenta").css("display", "block");
    } else {
      $("#notaNoCuenta").css("display", "none");
      return false;
    }
  }
  
  $(document).on("change", "#cmbRetirarDinero", function (event) {
    if ($("#cmbRetirarDinero option:selected").val() == 1) {
      $("#capitalRetiro").hide();
      $("#gasto").hide();
      $("#compra").show();
    } else if ($("#cmbRetirarDinero option:selected").val() == 2) {
      $("#capitalRetiro").hide();
      $("#gasto").show();
      $("#compra").hide();
    } else if ($("#cmbRetirarDinero option:selected").val() == 3) {
    } else {
      $("#btnAgregarGasto").css("display", "none");
    }
  });
  
  function ir_Ventana(idAc) {
    $("#inyeccion_Capital").modal("show");
    $.ajax({
      type: "POST",
      url: "functions/get_Combos.php",
      data: { id: idAc },
      success: function (res) {
        var datos = JSON.parse(res);
        $("#idCuentaIny").val(datos.idCajaActual);
        $("#saldoIny").val(datos.saldoInicialCaja);
        $("#cmbCuentasOrigen").html(datos.listaO);
      },
    });
  }
  
  function agregar_provedor() {
    var contacto = $("#txtContacto").val();
    if ($("#txtContacto").val() == "") {
      $("#txtContacto").prop("required", true);
    }
  
    $("#btnAgregarProveedor").click(function () {
      var razon = $("#txtRazon").val();
      var nombre = $("#txtNombre").val();
      var rfc = $("#txtRFC").val();
      var calle = $("#txtCalle").val();
      var numeroext = $("#txtNumeroEx").val();
      var numeroint = $("#txtNumeroInt").val();
      var colonia = $("#txtColonia").val();
      var municipio = $("#txtMunicipio").val();
      var pais = $("#txtPais").val();
      var estado = $("#cmbEstados").val();
      var cp = $("#txtCP").val();
      var diascredito = $("#txtDiasCredito").val();
      var limitepractico = $("#txtLimiteCredito").val();
  
      var contacto = $("#txtContacto").val();
      var apellido = $("#txtApellido").val();
      var telefono = $("#txtTelefono").val();
      var celular = $("#txtCelular").val();
      var email = $("#txtEmail").val();
      $.ajax({
        url: "functions/agregar_Proveedor.php",
        type: "POST",
        data: {
          txtRazon: razon,
          txtNombre: nombre,
          txtRFC: rfc,
          txtCalle: calle,
          txtNumeroEx: numeroext,
          txtNumeroInt: numeroint,
          txtColonia: colonia,
          txtMunicipio: municipio,
          txtPais: pais,
          cmbEstados: estado,
          txtCP: cp,
          txtDiasCredito: diascredito,
          txtLimiteCredito: limitepractico,
          txtContacto: contacto,
          txtApellido: apellido,
          txtTelefono: telefono,
          txtCelular: celular,
          txtEmail: email,
        },
        success: function (data, status, xhr) {
          if (data.trim() == "exito") {
            $("#agregar_Proveedor").modal("toggle");
            $("#agregarProveedor").trigger("reset");
            $("#tblProveedores").DataTable().ajax.reload();
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              //img: '<i class="fas fa-check-circle"></i>',
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Registro agregado!",
            });
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              img: null,
              msg: "Ocurrió un error al agregar",
            });
          }
        },
      });
    });
  }
  
  function filterFloat(evt, input) {
    // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
    var key = window.Event ? evt.which : evt.keyCode;
    var chark = String.fromCharCode(key);
    var tempValue = input.value + chark;
    if (key >= 48 && key <= 57) {
      if (filter(tempValue) === false) {
        return false;
      } else {
        return true;
      }
    } else {
      if (key == 8 || key == 13 || key == 0) {
        return true;
      } else if (key == 46) {
        if (filter(tempValue) === false) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    }
  }
  
  function filter(__val__) {
    var preg = /^([0-9]+\.?[0-9]{0,2})$/;
    if (preg.test(__val__) === true) {
      return true;
    } else {
      return false;
    }
  }
  
  function agregarFila() {
    var table = document.getElementById("tablaprueba");
    var rowCount = table.rows.length;
    document.getElementById("tablaprueba").insertRow(-1).innerHTML =
      `<td>
                                                                        <input  name="txtProductos` +
      rowCount +
      `" id="txtProductos` +
      rowCount +
      `" type="hidden" readonly>
  
                                                                        <select name="cmbProductos` +
      rowCount +
      `"  class="form-control" id="cmbProductos` +
      rowCount +
      `" onchange="" >
                                                                          <option value="0"></option>
                                                                          <option value="1">X1</option>
                                                                          <option value="2">x2</option>
                                                                        </select>
  
                                                                      </td>
                                                                      <td>
                                                                        <input class="form-control" type="numeric" name="txtCantidad" id="txtCantidad" autofocus="" required="" placeholder="Ej. 10">
                                                                      </td>
                                                                      <td>
                                                                        <label  for="usr" name="lblUnidadMedida" id="lblUnidadMedida">Kilogramo</label>
                                                                      </td>
                                                                      <td>
                                                                        <label  for="usr" name="lblPrecioUnitario" id="lblPrecioUnitario">$</label>
                                                                      </td>
                                                                      <td>
                                                                        <label  for="usr" name="lblImpuestos" id="lblImpuestos">IVA 16%</label>
                                                                      </td>
                                                                      <td>
                                                                        <label  for="usr" name="lblImporte" id="lblImporte">$</label>
                                                                      </td>
                                                                      <td>
                                                                        <button type="button" class="btn btn-danger" onclick="event.preventDefault();                                                           
                                                                        $(this).closest('tr').remove();">Eliminar Fila</button>
                                                                      </td>`;
  }
  
  function categoria() {
    var id = $("#cmbCategoria").val();
  
    $.ajax({
      type: "POST",
      url: "functions/getSubcategoria.php",
      data: { id: id },
      success: function (res) {
        var datos = JSON.parse(res);
        $("#pkCategoria").val(datos.pkcategoria);
        $("#cmbSubcategoria").html(datos.subCategorias);
      },
    });
  }

  function categoriaEdit() {
    var id = $("#cmbCategoriaEdit").val();
  
    $.ajax({
      type: "POST",
      url: "functions/getSubcategoria.php",
      data: { id: id },
      success: function (res) {
        var datos = JSON.parse(res);
        $("#cmbSubcategoriaEdit").html(datos.subCategorias);
      },
    });
  }
  
  function validar_documento() {
    var cbx = document.getElementById("checkCaja");
    var file = $("#inputFile").val();
    var invalidArchivoRet = $("#invalid-archivoCjChica");
    if (!file == "") {
      cbx.disabled = true;
      $("#checkCaja").prop("checked", false);
      invalidArchivoRet.css("display", "none");
    } else {
      cbx.disabled = false;
      invalidArchivoRet.css("display", "block");
    }
    //
  }
  
  function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf("/") + 1);
    return loc.href.substring(
      0,
      loc.href.length -
        ((loc.pathname + loc.search + loc.hash).length - pathName.length)
    );
  }
  
  $("#moneda_diferente").hide();
  
  function getMonedaD(idCuentaActual) {
    var id = $("#cmbCuentaDestinoCj").val();
    var m = document.getElementById("cmbMonedaG");
    m.disabled = true;
    var estado = $("#cmbCuentaDestinoCj").val();
    var mActual = $("#actual").val();
  
    if (estado == "0") {
      $("#moACuenta").val("");
      $("#invalid-cuentaTrans").css("display", "block");
      $("#cmbCuentaDestinoCj").addClass("is-invalid");
    } else {
      $("#invalid-cuentaTrans").css("display", "none");
      $("#cmbCuentaDestinoCj").removeClass("is-invalid");
      $.ajax({
        type: "POST",
        url: "functions/get_Moneda.php",
        data: {
          idCuentaDestino: id,
          idCuentaActual: idCuentaActual,
          moOrigen: mActual,
        },
        success: function (res) {
          var datos = JSON.parse(res);
          $("#cmbMonedaG").val(datos.monedaDes);
          $("#actual").val(datos.monedaActual);
          $("#destino").val(datos.monedaD);
          $("#saldoI").val(datos.saldoIn);
          $("#saldoDes").val(datos.saldoDest);
          $("#idCuentaDestinoTransfer").val(datos.idDestinoTr);
          $("#idCuentaActualTransfer").val(datos.idActualTr);
          $("#tipoDeCambio").val(datos.valorTipoCambio);
  
          $("#idDestinoTr").val(datos.idDestinoTr);
  
          var monAct = $("#actual").val();
          var monDes = $("#destino").val();
          console.log({ monAct });
          console.log({ monDes });
  
          if (monAct == monDes) {
            $("#moneda_diferente").hide();
            $("#invalid-cuentaTrans").css("display", "none");
            $("#cmbCuentaDestinoCj").removeClass("is-invalid");
          } else {
            $("#moneda_diferente").show();
          }
        },
      });
    }
  }
  
  function validarDatosGasto() {
    $(document).ready(function () {
      $("#txtImporteGasto").prop("required", true);
    });
  }
  
  function subirReferencia(input) {
    console.log($(input).attr('id').split('-')[0]);
    console.log($(input).attr('id').split('-')[1]);
    var idMov = $(input).attr("id").split("-")[0];
    var idCuenta = $(input).attr("id").split("-")[1];
    var msjsucces = "Archivo agregado";
    var msjwarning = "No se pudo agregar";
    var sinarchivo = "No hay archivo";
  
    var nombreArchivo = document.getElementById(idMov+'-'+idCuenta).files[0].name;
    var hayArchivo = 0;
  
    // archivo
    var miArchivo = $("#"+idMov+"-"+idCuenta).prop("files")[0];
    var fd = new FormData();
    //Datos para el movimiento
  
    if (!nombreArchivo == "") {
      hayArchivo = 1;
      fd.append("file-input", miArchivo);
      fd.append("idMovimiento", idMov);
      fd.append("idCuenta", idCuenta);
      fd.append("hayArchivo", hayArchivo);
      //
      $.ajax({
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: fd,
        url: "functions/subir_Archivo.php",
        success: function (data, status, xhr) {
          if (data.trim() == "exito") {
            $("#tblGastos").DataTable().ajax.reload();
            //location.reload();
            lobiSucces(msjsucces);
          } else {
            lobiWarning(msjwarning);
          }
        },
      });
    } else {
      lobiWarning(sinarchivo);
    }
  }

  function obtenerIdGastoEditar(id) {
    var idMov = id;
    var id = "id=" + id;
    $.ajax({
      type: "POST",
      url: "functions/getGasto.php",
      data: id,
      success: function (r) {
        var datos = JSON.parse(r);
        console.log(datos);
        $("#inputsEdit").css("display", "block");
        $("#idMovimiento").val(idMov);
        $("#cmbCuentaEdit").html(datos.cuentas);
        $("#cmbResponsableGastoEdit").html(datos.responsable);
        $("#txtImporteGastoEdit").val(datos.importe);
        $("#txtFechaGastoEdit").val(datos.fecha);
        $("#cmbProvedoresGastoEdit").html(datos.proveedores);
        $("#areaDescripcionGastoEdit").val(datos.observaciones);
        $("#cmbCategoriaEdit").html(datos.categorias);
        $("#hddCategoria").val(datos.categoria);
        $("#hddSubcategoria").val(datos.subcategoria);
        $("#saldoCuentaCajaEdit").val(datos.saldo);
        if(!datos.categoria){
          cmbSubcategoriaEdit.disable();
        }else{
          cmbSubcategoriaEdit.enable();
        }
        if(datos.categoria){
          var id = $("#hddCategoria").val();
          console.log(id);
          var idSub = $("#hddSubcategoria").val();
          setTimeout(() => {
            $.ajax({
              type: "POST",
              url: "functions/get_subcategoriaEdit.php",
              data: { idCat: id, idSubcat: idSub },
              success: function (res) {
                var data = JSON.parse(res);
                console.log(data);
                $("#cmbSubcategoriaEdit").html(data.subCategorias);
              },
            });
            
          }, 100);
        }
        $.ajax({
          type: "POST",
          url: "functions/get_CombosFormat.php",
          data: { id: $("#cmbCuentaEdit").val() },
          success: function (res) {
            var datos = JSON.parse(res);
            $("#lblSaldoEdit").text(datos.saldoInicialCaja);
            if($("#lblSaldo").text() == ''){
              $("#lblSaldo").text('0')
            }
          },
        });

      },
    });
  }

  function obtenerIdGastoEliminar(id) {
    $("#idGasto").val(id);
  }

  function eliminarGasto() {
    var idGasto = $("#idGasto").val();
    
    $.ajax({
      type: "POST",
      url: "functions/eliminarGasto.php",
      data: {idGasto: idGasto},
      success: function (r) {
        console.log(r);        
        $('#eliminar_Gasto').modal('hide');
        $("#tblGastos").DataTable().ajax.reload();
      },
    });
  }
  
  function tablaInicialCaja(idCuenta) {
    $.ajax({
      type: "POST",
      url: "functions/tabla_Caja.php",
      data: { idDetalle: idCuenta },
      success: function (r) {
        var datos = JSON.parse(r);
  
        $("#saldoG").val(datos.saldoG);
        $("#tipoCuentaG").val(datos.tipoCuenta);
        $("#nomCuentaG").val(datos.nomCuenta);
      },
    });
  }