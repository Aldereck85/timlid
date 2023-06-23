var _global = {
  rutaServer: '',
  rutaServer_t: ''
}

$(document).ready(function () {
  $(window).on("load", function () {
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

    $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
    $("#opEG-2").css({ "background-color": "#cac8c6", color: "#FFFFFF" });

    //Cambiar de color el combo del estatus al abrir por primera vez la página
    if ($("#cmbEstatusProducto").val() == 1) {
      $("#cmbEstatusProducto").css({
        "background-color": "#28c67a",
        color: "#FFFFFF",
      });
    } else {
      $("#cmbEstatusProducto").css({
        "background-color": "#cac8c6",
        color: "#FFFFFF",
      });
    }

    setTimeout(function(){
      $("#imgFile").on("change", function () {
        var reader = new FileReader();
        reader.onload = function (event) {
          $image_crop
            .croppie("bind", {
              url: event.target.result,
            })
            .then(function () {
              console.log("jQuery bind complete");
            });
        };
        reader.readAsDataURL(this.files[0]);
        $("#uploadimageModal").modal("show");
      });
    },500);
    

    $(".crop_image").click(function (event) {
      var imagenSubir = $("#imagenSubir").val();

      $image_crop
        .croppie("result", {
          type: "canvas",
          size: "viewport",
        })
        .then(function (response) {
          $.ajax({
            url: "uploadTemp.php",
            type: "POST",
            data: { image: response, imagenSubir: imagenSubir },
            success: function (data) {
              $("#uploadimageModal").modal("hide");

              html =
                `<div class="mb-4" style="position: relative; width:350px; height:350px; display:block; margin:auto;">
                      <img class="z-depth-1-half img-thumbnail" src="${_global.rutaServer_t}${data}" alt="example placeholder" id="imgProd" name="imgProd" style=" position: absolute;">
                    </div>
                    <input type="hidden" id="imagenSubir" name="imagenSubir" value="${data}" /> `;

              $("#espacioImagen").html(html);
            },
          });
        });
    });
  });
  /*function ocultar(){
    $("#loader").fadeOut("slow");
  }*/
});

function cargarDatosGrales(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataDatosProducto", data: id },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de datos de producto: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      $("#cmbEstatusProducto").val(parseInt(data[0].FKEstatusGeneral));

      if (data[0].FKEstatusGeneral == 1) {
        console.log("active");
        $("#activeProducto").attr("checked", "true");
      }

      $("#txtNombre").val(data[0].Nombre);
      $("#txtHistorialNombre").val(data[0].Nombre);

      $("#txtClaveInterna").val(data[0].ClaveInterna);
      $("#txtHistorialClave").val(data[0].ClaveInterna);

      $("#txtCodigoBarras").val(data[0].CodigoBarras);
      $("#txtHistorialCodigoBarras").val(data[0].CodigoBarras);

      if (parseInt(data[0].FKCategoriaProducto) == 0) {
        $("#cmbCategoriaProducto").val(0);
      } else {
        $("#cmbCategoriaProducto").val(parseInt(data[0].FKCategoriaProducto));
      }

      if (parseInt(data[0].FKMarcaProducto) == 0) {
        $("#cmbMarcaProducto").val(0);
      } else {
        $("#cmbMarcaProducto").val(parseInt(data[0].FKMarcaProducto));
      }

      if (parseInt(data[0].FKTipoProducto) == 0) {
        $("#cmbTipoProducto").val(0);
      } else {
        $("#cmbTipoProducto").val(parseInt(data[0].FKTipoProducto));
      }

      if (parseInt(data[0].FKTipoProducto) == 1) {
        //cargarCompuestos(id);
        $("#cmbTipoProducto").attr("disabled", "true");
      } else {
        if (parseInt(data[0].FKTipoProducto) == 3) {
          $("#cbxFabricacion").prop("checked", true);
          $("#cbxFabricacion").prop("disabled", true);

          setTimeout(function(){
            $("#cbxLote").prop("checked", true);
            $("#cbxLote").prop("disabled", true);
  
            $("#cbxSerie").prop("disabled", true);
          });
        } else {
          $("#cbxFabricacion").prop("checked", false);
          $("#cbxFabricacion").prop("disabled", false);

          //setTimeout(function(){
            $("#cbxLote").prop("checked", false);
            $("#cbxLote").prop("disabled", false);
  
            $("#cbxSerie").prop("disabled", false);
          //});
        }

        if (parseInt(data[0].FKTipoProducto) == 6) {
          $("#cbxSerie").prop("checked", true);
          $("#cbxSerie").prop("disabled", true);

          $("#cbxLote").prop("disabled", true);
        } else {
          $("#cbxSerie").prop("checked", false);
          $("#cbxSerie").prop("disabled", false);

          $("#cbxLote").prop("disabled", false);
        }

        $("#cmbTipoProducto").val(parseInt(data[0].FKTipoProducto));
        var body = ``;
        $("#areaCompuesto").html(body);
        $("#cmbTipoProducto").attr("disabled", "true");
      }

      $("#txtDescripcionLarga").val(data[0].Descripcion);

      if (parseInt(data[0].IsCompra) == "1") {
        $("#cbxCompra").prop("checked", true);
        $("#txtCostoUniCompra").val(data[0].CostoCompra);
        cargarCMBCostoUniCompraGralEdit(data[0].MonedaCompra, "cmbCostoUniCompra");

        $("#pestDatosProveedor").removeAttr("style","pointer-events:none; opacity:0.4;");

        var count = 0;
        count = count + 1;

        if ($("#cbxVenta").is(":checked")) {
          count = count + 1;
        }

        if ($("#cbxFabricacion").is(":checked")) {
          count = count + 1;
        }

        $("#spanCompraL").css("display", "block");
        $("#spanCompra").css("display", "block");
      } else {
        cbxCompra = 0;

        $("#pestDatosProveedor").attr("style","pointer-events:none; opacity:0.4;");
      }

      if (parseInt(data[0].IsVenta) == "1") {
        $("#cbxVenta").prop("checked", true);
        $("#txtCostoUniVenta").val(data[0].CostoGeneral);
        cargarCMBCostoUniVentaGralEdit(data[0].MonedaGeneral, "cmbCostoUniVenta");

        $("#pestDatosVenta").removeAttr("style","pointer-events:none; opacity:0.4;");

        var count = 0;
        count = count + 1;

        if ($("#cbxCompra").is(":checked")) {
          count = count + 1;
        }

        if ($("#cbxFabricacion").is(":checked")) {
          count = count + 1;
        }

        $("#spanVentaL").css("display", "block");
        $("#spanVenta").css("display", "block");
      } else {
        cbxCompra = 0;
        $("#pestDatosVenta").attr("style","pointer-events:none; opacity:0.4;");
      }

      if (parseInt(data[0].IsFabricacion) == "1") {
        $("#cbxFabricacion").prop("checked", true);
        $("#txtCostoUniFabri").val(data[0].CostoFabricacion);
        cargarCMBCostoUniFabriGralEdit(
          data[0].MonedaFabricacion,
          "cmbCostoUniFabri"
        );

        var count = 0;
        count = count + 1;

        if ($("#cbxCompra").is(":checked")) {
          count = count + 1;
        }

        if ($("#cbxVenta").is(":checked")) {
          count = count + 1;
        }

        $("#spanFabriL").css("display", "block");
        $("#spanFabri").css("display", "block");
      } else {
        cbxCompra = 0;
      }

      if (parseInt(data[0].IsGastoFijo) == "1") {
        $("#cbxGastoFijo").prop("checked", true);
        $("#cbxGastoFijo").prop("disabled", true);
        $("#txtCostoUniGastoF").val(data[0].CostoGastoF);
        cargarCMBCostoUniGastoFijoEdit(
          data[0].MonedaGastoF,
          "cmbCostoUniGastoF"
        );

        var count = 0;
        count = count + 1;

        if ($("#cbxCompra").is(":checked")) {
          count = count + 1;
        }

        if ($("#cbxVenta").is(":checked")) {
          count = count + 1;
        }

        $("#spanGastoF").css("display", "block");
      } else {
        cbxCompra = 0;
      }

      if (parseInt(data[0].IsSerie) == "1") {
        /*$("#spanSerie").css("display", "block");*/
        $("#cbxSerie").prop("checked", true);
        /*$("#txtSerie").val(data[0].Serie);
        $("#spanLote").css("display", "none");*/
        $("#cbxLote").prop("checked", false);
        /*$("#txtLotes").val("");*/
      }

      if (parseInt(data[0].IsLote) == "1") {
        /*$("#spanLote").css("display", "block");*/
        $("#cbxLote").prop("checked", true);
        /*$("#txtLotes").val(data[0].Lote);
        $("#spanSerie").css("display", "none");*/
        $("#cbxSerie").prop("checked", false);
        /*$("#txtSerie").val("");*/
      }

      if (parseInt(data[0].IsCaducidad) == "1") {
        /*$("#spanCaducidad").css("display", "block");*/
        $("#cbxCaducidad").prop("checked", true);
        /*$("#txtCaducidad").val(data[0].Caducidad);*/
      }

      if (data[0].Imagen == "agregar.svg") {
        imagen = `<div class="mb-4 img-thumbnail" style="position: relative; width:350px; height:350px; display:block; margin:auto; no-repeat rgb(249,249,249); opacity: .6;">
                    <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg" alt="example placeholder" id="imgProd" name="imgProd" width="100px" height="100px" style=" position: absolute; top: 35%; right: 0; left: 0; margin: 0 auto;">
                  </div>`;

        $("#espacioImagen").html(imagen);
      } else {
        imagen =
          `<div class="mb-4" style="position: relative; width:350px; height:350px; display:block; margin:auto;">
                    <img class="z-depth-1-half img-thumbnail" src="${_global.rutaServer}${data[0].Imagen}" alt="example placeholder" id="imgProd" name="imgProd" style=" position: absolute;">
                  </div>
                  <input type="hidden" id="imagenSubir" name="imagenSubir" value="" /> `;

        $("#espacioImagen").html(imagen);
      }

      //Asignar plugin Slim a combos desplegables, para la opción de búsqueda de opciones dentro del combo
      new SlimSelect({
        select: "#cmbCategoriaProducto",
        deselectLabel: '<span class="">✖</span>',
        addable: function (value) {
          if (_permissionsCat.add == '1'){
            validarCategoria(value);
          }
        },
      });

      new SlimSelect({
        select: "#cmbMarcaProducto",
        deselectLabel: '<span class="">✖</span>',
        addable: function (value) {
          if (_permissionsMar.add == '1'){
            validarMarca(value);
          }
        },
      });

      new SlimSelect({
        select: "#cmbTipoProducto",
        deselectLabel: '<span class="">✖</span>',
        /*addable: function (value) {
          validarTipoProducto(value);
        }*/
      });

      $("#opEG-1").css({ "background-color": "#28c67a", color: "#FFFFFF" });
      $("#opEG-2").css({ "background-color": "#cac8c6", color: "#FFFFFF" });

      //Cambiar de color el combo del estatus al abrir por primera vez la página
      if ($("#cmbEstatusProducto").val() == 1) {
        $("#cmbEstatusProducto").css({
          "background-color": "#28c67a",
          color: "#FFFFFF",
        });
      } else {
        $("#cmbEstatusProducto").css({
          "background-color": "#cac8c6",
          color: "#FFFFFF",
        });
      }
    },
  });
}

function cargarCompuestos(id) {
  var pkUsuario = $("#txtUsuario").val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_DataDatosProductoCompuesto",
      data: id,
      data2: pkUsuario,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de datos de producto: ", data);

      var body2 = "";
      var contador = 1;
      var unidadMedida = "";
      var nombre = "";
      var cadenaCosto = "";

      // Obteniendo todas las claves del JSON
      for (var dato of data) {
        if (dato.Descripcion == null) {
          unidadMedida = "(Sin unidad de medida)";
        } else {
          unidadMedida = dato.Descripcion;
        }

        if (dato.ClaveInterna == "") {
          nombre = dato.Nombre;
        } else {
          nombre = dato.ClaveInterna + " - " + dato.Nombre;
        }

        cadenaCosto = dato.Costo + " " + dato.TipoMoneda;

        if (parseInt(contador) == 1) {
          body2 =
            body2 +
            `<tr>
              <td>
                <input  name="txtProductos1" id="txtProductos1" type="hidden" readonly value="${dato.FKProductoCompuesto}">
                <input type="text" class="form-control" name="cmbProductos1" id="cmbProductos1" data-toggle="modal" data-target="#agregar_Producto" 
                placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProd(1)" value="${nombre}">
                <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                src="../../../../img/timdesk/alerta.svg" width=30px
                title="Seleccione por lo menos un producto" readonly>
              </td>
              <td>
                <div class="row">
                  <div class="col-lg-6">
                    <input class="form-control" type="number" name="txtCantidadCompuesta1" id="txtCantidadCompuesta1" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus="" required="" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(1)" value="${dato.Cantidad}">
                  </div>
                  <div class="col-lg-6">
                    <label  for="usr"><span id="lblUnidadMedida1">${unidadMedida}</span></label>
                  </div>
                </div>
              </td>
              <td>
                <div class="row">
                  <div class="col-lg-12">
                    <label  for="usr"><span id="lblCosto1">${cadenaCosto}</span><input type="hidden" id="txtCosto1" value="${dato.CostoFabricacion}"><input type="hidden" id="txtMoneda1" value="${dato.TipoMoneda}"></label>
                  </div>
                </div>
              </td>
              <td>
                *
              </td>
            </tr>`;
        } else {
          body2 =
            body2 +
            `<tr>
              <td>
                <input  name="txtProductos${contador}" id="txtProductos${contador}" type="hidden" readonly value="${dato.FKProductoCompuesto}">
                <input type="text" class="form-control" name="cmbProductos${contador}" id="cmbProductos${contador}" data-toggle="modal" data-target="#agregar_Producto" 
                placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProd(${contador})" value="${nombre}">
                <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                src="../../../../img/timdesk/alerta.svg" width=30px
                title="Seleccione por lo menos un producto" readonly>
              </td>
              <td>
                <div class="row">
                  <div class="col-lg-6">
                    <input class="form-control" type="number" name="txtCantidadCompuesta${contador}" id="txtCantidadCompuesta${contador}" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus="" required="" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(${contador})" value="${dato.Cantidad}">
                  </div>
                  <div class="col-lg-6">
                    <label  for="usr"><span id="lblUnidadMedida${contador}">${unidadMedida}</span></label>
                  </div>
                </div>
              </td>
              <td>
                <div class="row">
                  <div class="col-lg-12">
                    <label  for="usr"><span id="lblCosto${contador}">${cadenaCosto}</span><input type="hidden" id="txtCosto${contador}" value="${dato.CostoFabricacion}">
                    <input type="hidden" id="txtMoneda${contador}" value="${dato.TipoMoneda}"></label>
                  </div>
                </div>
              </td>
              <td>
                <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" onclick="eliminarCompTemp(${contador}); event.preventDefault(); $(this).closest('tr').remove(); "></i>
              </td>
            </tr>`;
        }

        contador = parseInt(contador) + 1;
        console.log("El dato es:" + unidadMedida);
      }

      var body =
        `<div class="form-group">
                    <div class="row">
                      <div class="col-lg-12">
                        <label for="usr">Productos que lo componen:</label>
                      </div>
                    </div>
                  </div>
                  <input  name="txtSeleccion" id="txtSeleccion" type="hidden" readonly>
                  <div class="form-group">
                    <!-- DataTales Example -->
                    <div class="card-body" id="tablaCompuesto" name="tablaCompuesto">
                      <div class="table-responsive">
                        <table class="table" id="tablaprueba" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th style="width:45%">Clave/Producto*</th>
                              <th>Cantidad y unidad de medida</th>
                              <th>Costo</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            ${body2}
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group" id="anadirCompuesto" name="anadirCompuesto">
                    <i><img  src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" onclick="agregarFila()" width="30px">  </i>
                    <label>  Añadir producto</label>
                  </div>`;
      $("#areaCompuesto").html(body);
      cargarCMBProductos(id);

      
      if (_permissions.edit == '1') {
        $("#tablaCompuesto").removeClass("readNotEditPermissions");
        $("#tablaCompuesto").addClass("readEditPermissions");

        $("#anadirCompuesto").removeClass("readNotEditPermissions");
        $("#anadirCompuesto").addClass("readEditPermissions");
      }else{
        $("#tablaCompuesto").removeClass("readEditPermissions");
        $("#tablaCompuesto").addClass("readNotEditPermissions");

        $("#anadirCompuesto").removeClass("readEditPermissions");
        $("#anadirCompuesto").addClass("readNotEditPermissions");
      }
    },
  });
}

function cargarDatosFiscales(id, isGeneral = 0) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataFiscalProducto", data: id },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de datos de producto: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if(isGeneral == 0){
        if (data[0].FKClaveSAT == 0 || data[0].FKClaveSAT == ''){
          $("#txtIDClaveSAT").val(parseInt(1));
          $("#cmbClaveSAT").val("S/C - Sin Clave");
        }else{
          $("#txtIDClaveSAT").val(parseInt(data[0].FKClaveSAT));
          $("#cmbClaveSAT").val(data[0].Clave);
        }
      }else{
        if (data[0].FKClaveSATUnidad == 0 || data[0].FKClaveSATUnidad == ''){
          $("#txtIDUnidadSAT").val(parseInt(1071));
          $("#cmbUnidadSAT").val("H87 - Pieza");
        }else{
          $("#txtIDUnidadSAT").val(parseInt(data[0].FKClaveSATUnidad));
          $("#cmbUnidadSAT").val(data[0].Unidad);
        }
      }
    },
  });
}

function cargarDatosInventario(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_DataInventarioProducto",
      data: id,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de inventario de producto: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data[0].FKTipoOrdenInventario == 0){
        $("#cmbTipoInventario").val(1);
      }else{
        $("#cmbTipoInventario").val(data[0].FKTipoOrdenInventario);
      }
      $("#txtStockExi").val(data[0].StockExistencia);
      $("#txtStockMin").val(data[0].StockMinimo);
      $("#txtStockMax").val(data[0].StockMaximo);
      $("#txtReorden").val(data[0].PuntoReorden);

      new SlimSelect({
        select: "#cmbTipoInventario",
        deselectLabel: '<span class="">✖</span>',
        addable: function (value) {
          validarTipoOrdenInventario(value);
        },
      });
    },
  });
}

function cargarDatosVenta(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_DataVentaProducto", data: id },
    dataType: "json",
    success: function (data) {
      console.log("respuesta de inventario de producto: ", data);
      /* Validar si ya existe el identificador con ese nombre*/

      $("#txtCostoUniVenta").val(data[0].CostoGeneral);
      $("#cmbCostoUniVenta").val(data[0].FKTipoMoneda);

      new SlimSelect({
        select: "#cmbCostoUniVenta",
        deselectLabel: '<span class="">✖</span>',
      });
    },
  });
}

/* VALIAR QUE NO SE REPITA La CATEOGRIA DE PRODUCTOS AGREGADO POR EL USUARIO EN AGREGAR */
function validarCategoria(valor) {
  console.log("Valor categoria" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_categoriaProducto",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta categoría validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("¡Ya existe!");
      } else {
        console.log("¡No existe!");

        anadirCategoria(valor);
      }
    },
  });
}

/* Añadir la categoría */
function anadirCategoria(valor) {
  console.log("Valor categoria" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "save_data", funcion: "save_categoria", datos: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar categoria de producto:", respuesta);

      if (respuesta[0].status) {
        Swal.fire(
          "Registro exitoso",
          "Se guardo la categoría con exito",
          "success"
        );
        cargarCMBCategoria(valor, "cmbCategoriaProducto");
      } else {
        Swal.fire("Error", "No se guardó la categoría con exito", "warning");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* VALIAR QUE NO SE REPITA LA MARCA DE PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarMarca(valor) {
  console.log("Valor marca" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_marcaProducto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("¡Ya existe!");
      } else {
        console.log("¡No existe!");

        anadirMarca(valor);
      }
    },
  });
}

/* Añadir la marca */
function anadirMarca(valor) {
  console.log("Valor marca" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "save_data", funcion: "save_marca", datos: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar marca de producto:", respuesta);

      if (respuesta[0].status) {
        Swal.fire(
          "Registro exitoso",
          "Se guardo la marca con exito",
          "success"
        );
        cargarCMBMarca(valor, "cmbMarcaProducto");
      } else {
        Swal.fire("Error", "No se guardó la marca con exito", "warning");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* VALIAR QUE NO SE REPITA EL TIPO DE PRODUCTO AGREGADO POR EL USUARIO EN AGREGAR */
function validarTipoProducto(valor) {
  console.log("Valor tipo producto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_tipoProducto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta marca validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("¡Ya existe!");
      } else {
        console.log("¡No existe!");

        anadirTipoProducto(valor);
      }
    },
  });
}

/* Añadir el tipo de producto */
function anadirTipoProducto(valor) {
  console.log("Valor tipo producto" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "save_data", funcion: "save_tipoProducto", datos: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar tipo de producto:", respuesta);

      if (respuesta[0].status) {
        Swal.fire(
          "Registro exitoso",
          "Se guardo el tipo de producto con exito",
          "success"
        );
        cargarCMBTipo(valor, "cmbTipoProducto");
      } else {
        Swal.fire(
          "Error",
          "No se guardó el tipo de producto con exito",
          "warning"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* VALIAR QUE NO SE REPITA EL TIPO DE ORDEN DE INVENTARIO AGREGADO POR EL USUARIO EN AGREGAR */
function validarTipoOrdenInventario(valor) {
  console.log("Valor tipo orden inventario" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_tipoOrdenInventario",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta tipo orden inventario validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("¡Ya existe!");
      } else {
        console.log("¡No existe!");

        anadirTipoOrdenInventario(valor);
      }
    },
  });
}

/* Añadir el tipo de orden de inventario */
function anadirTipoOrdenInventario(valor) {
  console.log("Valor tipo orden inventario" + valor);
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_tipoOrdenInventario",
      datos: valor,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta agregar tipo orden inventario:", respuesta);

      if (respuesta[0].status) {
        Swal.fire(
          "Registro exitoso",
          "Se guardo el tipo de orden de inventario con exito",
          "success"
        );
        cargarCMBTipoOrden(valor, "cmbTipoInventario");
      } else {
        Swal.fire(
          "Error",
          "No se guardó el tipo de orden de inventario con exito",
          "warning"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("change", "#cmbCategoriaProducto", function () {
  var categoria = $("#cmbCategoriaProducto").val();

  console.log("Selección:" + categoria);
  if (categoria == "add") {
    window.location.href = "../categoria_productos";
  }
});

$(document).on("change", "#cmbMarcaProducto", function () {
  var categoria = $("#cmbMarcaProducto").val();

  console.log("Selección:" + categoria);
  if (categoria == "add") {
    window.location.href = "../marca_productos";
  }
});

$(document).on("change", "#cmbTipoProducto", function () {
  var categoria = $("#cmbTipoProducto").val();

  console.log("Selección:" + categoria);
  if (categoria == "add") {
    window.location.href = "../../../configuracion/tipo_productos";
  }
});

$(document).on("change", "#cmbTipoInventario", function () {
  var categoria = $("#cmbTipoInventario").val();

  console.log("Selección:" + categoria);
  if (categoria == "add") {
    window.location.href = "../../../configuracion/tipo_orden_inventario";
  }
});
