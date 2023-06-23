var _permissions = {
    read: 0,
    add: 0,
    edit: 0,
    delete: 0,
    export: 0,
  };
  
  var _global = {
    pkProducto: 0,
    rutaServer: '',
    txtHistorialNombre: 0,
    txtHistorialClave: 0,
    txtHistorialCodigoBarras: 0,
    contadorCompuesto: 0,
    position: 0,
  };

$(document).ready(function(){
    new SlimSelect({
        select: "#cmbProductoMaterial",
        deselectLabel: '<span class="">✖</span>',
    });

    cargarCMBProductosNoMaterial("1", "cmbProductoMaterial");
});

$(document).on('change','#cmbProductoMaterial',function(){
    cargarAreaDatos();
    _global.pkProducto = $("#cmbProductoMaterial").val();
});

function cargarCMBProductosNoMaterial(data, input) {
    var html = "";
    var selected;
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_cmb_producto_listaCompuestos" },
      dataType: "json",
      success: function (respuesta) {
        html += '<option value="0">Seleccione un producto...</option>';
  
        $.each(respuesta, function (i) {
          if (data === respuesta[i].pkProducto) {
            selected = "selected";
          } else {
            selected = "";
          }
  
          if (respuesta[i].clave == "") {
            html += `<option value="${respuesta[i].pkProducto}" ${selected}> ${respuesta[i].nombre}</option>`;
          } else {
            html +=
              `<option value="${respuesta[i].pkProducto}" ${selected}> ${respuesta[i].clave} - ${respuesta[i].nombre}</option>`;
          }
        });
  
        $("#" + input + "").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
}

function cargarAreaDatos(){
    var elements = `<div class="form-group">
                    <div class="row">
                    <div class="col-lg-8">
                      <div class="form-group " id="btnDeletePermissions">
                        <div class="row">
                            <div class="col-xl-9 col-lg-9 col-md-6 col-sm-3 col-xs-0">
                            
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-4 col-xs-6">
                            <label for="usr">Estatus:*</label>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5 col-xs-6">
                            <input type="checkbox" id="activeProducto" name="activeProducto" class="check-custom" checked>
                            <label class="shadow-sm check-custom-label" for="activeProducto">
                                <div class="circle"></div>
                                <div class="check-inactivo">Inactivo</div>
                                <div class="check-activo">Activo</div>
                            </label>
                            </div>
                        </div>
                        </div>
                        
                        <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                            <label for="usr">Nombre:*</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control " type="text" name="txtNombre" id="txtNombre" disabled>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        
                        <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                            <label for="usr">Clave interna:*</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control " type="text" name="txtClaveInterna" id="txtClaveInterna" style="text-transform:uppercase" disabled>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <label for="usr">Código de barras:</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control " type="text" name="txtCodigoBarras" id="txtCodigoBarras" disabled>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                        <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                            <label for="usr">Categoría:</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control " type="text" name="cmbCategoriaProducto" id="cmbCategoriaProducto" disabled>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <label for="usr">Marca:</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control " type="text" name="cmbMarcaProducto" id="cmbMarcaProducto" disabled>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                        <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                            <label for="usr">Descripción:</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <textarea class="form-control " type="text" id="txtDescripcionLarga" name="txtDescripcionLarga"></textarea>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="col-lg-4">
                        <div class="file-field">
                        <span id="espacioImagen">
                        <div class="mb-4 img-thumbnail" style="position: relative; width:350px; height:350px; display:block; margin:auto; no-repeat rgb(249,249,249); opacity: .6;">
                            <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg"
                            alt="example placeholder" id="imgProd" name="imgProd" width="100px" height="100px" style=" position: absolute; top: 35%; right: 0; left: 0; margin: 0 auto;">
                        </div>
                        </span>
                        <div class="d-flex justify-content-center">
                            <span id="espacioFile">
                            </span>
                        </div>
                        </div>
                    </div>
                    </div>
                    </div>`;

    $("#areaDatos").html(elements);
    setTimeout(function(){
        cargarTablaCompuestos();
    },100);
}

function cargarTablaCompuestos(){
  
    var body = `<div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Productos que lo componen:</label>
                    </div>
                  </div>
                </div>
                <input  name="txtSeleccion" id="txtSeleccion" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tablaprueba" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:1%"></th>
                            <th style="width:39%">Clave/Producto*</th>
                            <th style="width:30%">Cantidad y unidad de medida</th>
                            <th style="width:25%">Costo</th>
                            <th style="width:5%"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="resaltar" data-toggle="modal" data-target="#agregar_Producto" onclick="clickSeleccionarProd(1)">
                              <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd1" name="addProd" width="20px" height="20px" style=" position: relative;">
                            </td>
                            <td>
                              <input  name="txtProductos1" id="txtProductos1" type="hidden" readonly>
                              <input type="text" class="form-control" name="cmbProductos1" id="cmbProductos1" data-toggle="modal" data-target="#agregar_Producto" 
                              placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProd(1)">
                              <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                              src="../../../../img/timdesk/alerta.svg" width=30px
                              title="Seleccione por lo menos un producto" readonly>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-6">
                                  <input class="form-control" type="number" name="txtCantidadCompuesta1" id="txtCantidadCompuesta1" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus="" required="" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(1)" step="0.01">
                                </div>
                                <div class="col-lg-6">
                                  <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(1)"><span id="lblUnidadMedida1"> </span></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <label  for="usr"><span id="lblCosto1" hidden> </span><input class="form-control" type="text" id="txtCosto1"  onkeyup="guardarCostoProdCompTemp(1)" required></label>
                                    </div>  
                                    <div class="col-lg-6"> 
                                      <select id="txtMoneda1" onchange="guardarMonedaProdCompTemp(1)"></select>
                                    </div>  
                                  </div>  
                                </div>
                              </div>
                            </td>
                            <td>
                              *
                            </td>
                          </tr>
                          <tr>
                            <td class="resaltar" data-toggle="modal" data-target="#agregar_Producto" onclick="clickSeleccionarProd(2)">
                              <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd2" name="addProd" width="20px" height="20px" style=" position: relative;">
                            </td>
                            <td>
                              <input  name="txtProductos2" id="txtProductos2" type="hidden" readonly>
                              <input type="text" class="form-control" name="cmbProductos2" id="cmbProductos2" data-toggle="modal" data-target="#agregar_Producto" 
                              placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProd(2)">
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-6">
                                  <input class="form-control" type="number" name="txtCantidadCompuesta2" id="txtCantidadCompuesta2" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus="" required="" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(2)" step="0.01">
                                </div>
                                <div class="col-lg-6">
                                  <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(2)"><span id="lblUnidadMedida2"> </span></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <label  for="usr"><span id="lblCosto2" hidden> </span><input class="form-control" type="text" id="txtCosto2" onkeyup="guardarCostoProdCompTemp(2)" required></label>
                                    </div>
                                    <div class="col-lg-6">
                                      <select id="txtMoneda2" onchange="guardarMonedaProdCompTemp(2)"></select>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td>
                              <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" onclick="eliminarCompTemp(2); event.preventDefault(); $(this).closest('tr').remove(); "></i>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 ">
                      <i class="resaltar" data-toggle="tooltip" data-placement="top" title="Agregar producto" onclick="agregarFila()"><img src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\"  width="30px"> Añadir producto </i>
                      
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr">Empaques que lo componen:</label>
                    </div>
                  </div>
                </div>
                <input  name="txtSeleccion2" id="txtSeleccion2" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tablapruebaEmp" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:1%"></th>
                            <th style="width:34%">Clave/Producto*</th>
                            <th style="width:15%">Cantidad y unidad de medida</th>
                            <th style="width:20%">Costo</th>
                            <th style="width:25%">No. de piezas colectivas</th>
                            <th style="width:5%"></th>
                          </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6 ">
                      <i class="resaltar" data-toggle="tooltip" data-placement="top" title="Agregar empaque" onclick="agregarFilaEmp()"><img src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\"  width="30px"> Añadir empaque </i>
                      
                    </div>
                  </div>
                </div>
                `;
    $("#areaCompuesto").html(body);

    validate_Permissions(58);
    cargarCMBProductos(0);
    cargarCMBEmpaques(0);

    //Al dar click por primera vez que se eliminen los que se hayan quedado de acción anterior no completada
    eliminarRegistrosTempProdComp();

    cargarCMBMoneda(100,"txtMoneda1");
    cargarCMBMoneda(100,"txtMoneda2");
    cargarCMBMoneda(100,"txtMonedaEmp1");
    cargarCMBMoneda(100,"txtMonedaEmp2");

    new SlimSelect({
      select: "#txtMoneda1",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
        select: "#txtMoneda2",
        deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
      select: "#txtMonedaEmp1",
      deselectLabel: '<span class="">✖</span>',
    });

    new SlimSelect({
        select: "#txtMonedaEmp2",
        deselectLabel: '<span class="">✖</span>',
    });
}

function cargarDatosGrales(id) {
  console.log('aqui');
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_DataDatosProducto", data: id },
      dataType: "json",
      success: function (data) {
  
        $("#cmbEstatusProducto").val(parseInt(data[0].FKEstatusGeneral));
        if (data[0].FKEstatusGeneral == 1) {
          $("#activeProducto").attr("checked", "true");
        }
  
        $("#txtNombre").val(data[0].Nombre);
        _global.txtHistorialNombre = data[0].Nombre;
  
        $("#txtClaveInterna").val(data[0].ClaveInterna);
        _global.txtHistorialClave = data[0].ClaveInterna;
  
        $("#txtCodigoBarras").val(data[0].CodigoBarras);
        _global.txtHistorialCodigoBarras = data[0].CodigoBarras;
  
        if (parseInt(data[0].FKCategoriaProducto) == 0) {
          $("#cmbCategoriaProducto").val('Sin Categoría');
        } else {
          $("#cmbCategoriaProducto").val(data[0].categoria);
        }
  
        if (parseInt(data[0].FKMarcaProducto) == 0) {
          $("#cmbMarcaProducto").val('Sin Marca');
        } else {
          $("#cmbMarcaProducto").val(data[0].marca);
        }
  
        $("#txtDescripcionLarga").val(data[0].Descripcion);
  
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
      },
    });
}

function cargarCMBMoneda(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_moneda" },
    dataType: "json",
    success: function (respuesta) {

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKTipoMoneda) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          `<option value="${respuesta[i].PKTipoMoneda}" ${selected}>${respuesta[i].TipoMoneda}</option>`;
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}
    
function agregarFila() {
    var table = document.getElementById('tablaprueba');
    var rowCount = table.rows.length;
    document.getElementById('tablaprueba').insertRow(-1).innerHTML =
      `<td class="resaltar" data-toggle="modal" data-target="#agregar_Producto" onclick="clickSeleccionarProd(${rowCount})">
        <input  name="txtProductos${rowCount}" id="txtProductos${rowCount}" type="hidden" readonly>
        <i><img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd${rowCount}" name="addProd${rowCount}" width="20px" height="20px" style=" position: relative; margin: auto; display: inline;" ></i>
        </td>
        <td>
        <input type="text" class="form-control" name="cmbProductos${rowCount}" id="cmbProductos${rowCount}" data-toggle="modal" data-target="#agregar_Producto" 
        placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarProd(${rowCount})">
      </td>
      <td>
        <div class="row">
          <div class="col-lg-6">
            <input class="form-control" type="number" name="txtCantidadCompuesta${rowCount}" id="txtCantidadCompuesta${rowCount}" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(${rowCount})" step="0.01">
          </div>
          <div class="col-lg-6">
            <label for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(${rowCount})"><span id="lblUnidadMedida${rowCount}"> </span></label>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-6">
                <label  for="usr"><span id="lblCosto${rowCount}" hidden> </span><input class="form-control" type="text" id="txtCosto${rowCount}" onkeyup="guardarCostoProdCompTemp(${rowCount})" required></label>
              </div> 
              <div class="col-lg-6"> 
                <select id="txtMoneda${rowCount}" onchange="guardarMonedaProdCompTemp(${rowCount})"></select>
              </div>
            </div>
          </div>
        </div>
      </td>
      <td>
        <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" onclick="eliminarCompTemp(${rowCount}); event.preventDefault(); $(this).closest('tr').remove(); "></i>
      </td>`;

    
    cargarCMBMoneda(100,`txtMoneda${rowCount}`);

    new SlimSelect({
      select: `#txtMoneda${rowCount}`,
      deselectLabel: '<span class="">✖</span>',
    });
}

function agregarFilaEmp() {
  var table = document.getElementById('tablapruebaEmp');
  var rowCount = table.rows.length;
  document.getElementById('tablapruebaEmp').insertRow(-1).innerHTML =
    `<tr>
      <td class="resaltar" data-toggle="modal" data-target="#agregar_Producto" onclick="clickSeleccionarEmp(${rowCount})">
        <input  name="txtProductosEmp${rowCount}" id="txtProductosEmp${rowCount}" type="hidden" readonly>
        <i><img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProdEmp${rowCount}" name="addProdEmp${rowCount}" width="20px" height="20px" style=" position: relative; margin: auto; display: inline;" ></i>
        </td>
        <td>
        <input type="text" class="form-control" name="cmbProductosEmp${rowCount}" id="cmbProductosEmp${rowCount}" data-toggle="modal" data-target="#agregar_Empaque" 
        placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarEmp(${rowCount})">
      </td>
      <td>
        <div class="row">
          <div class="col-lg-6">
            <input class="form-control" type="number" name="txtCantidadCompuestaEmp${rowCount}" id="txtCantidadCompuestaEmp${rowCount}" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10" onkeyup="guardarCantidadEmpCompTemp(${rowCount})" step="0.01">
          </div>
          <div class="col-lg-6">
            <label for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(${rowCount})"><span id="lblUnidadMedidaEmp${rowCount}"> </span></label>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-6">
                <label  for="usr"><span id="lblCostoEmp${rowCount}" hidden> </span><input class="form-control" type="text" id="txtCostoEmp${rowCount}" onkeyup="guardarCostoEmpCompTemp(${rowCount})" required></label>
              </div> 
              <div class="col-lg-6"> 
                <select id="txtMonedaEmp${rowCount}" onchange="guardarMonedaEmpCompTemp(${rowCount})"></select>
              </div>
            </div>
          </div>
        </div>
      </td>
      <td>
        <input class="form-control" type="number" id="txtColectivas${rowCount}" onkeyup="guardarColectivosEmpCompTemp(${rowCount})" required>
      </td>
      <td>
        <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" onclick="eliminarCompTempEmp(${rowCount}); event.preventDefault(); $(this).closest('tr').remove(); "></i>
      </td>
    </tr>`;

  
  cargarCMBMoneda(100,`txtMonedaEmp${rowCount}`);

  new SlimSelect({
    select: `#txtMonedaEmp${rowCount}`,
    deselectLabel: '<span class="">✖</span>',
  });
}
  
function eliminarCompTemp(elemento) {
    var seleccion = $("#txtProductos" + elemento + "").val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "delete_data",
        funcion: "delete_datosProductoCompTemp",
        datos: seleccion,
      },
      dataType: "json",
      success: function (respuesta) {
  
        if (respuesta[0].status) {
          console.log("OK");
        } else {
          console.log("Error");
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
}
  
function eliminarCompTempEmp(elemento) {
    var seleccion = $("#txtProductosEmp" + elemento + "").val();

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "delete_data",
        funcion: "delete_datosProductoCompTemp",
        datos: seleccion,
      },
      dataType: "json",
      success: function (respuesta) {
  
        if (respuesta[0].status) {
          console.log("OK");
        } else {
          console.log("Error");
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
}

function eliminarRegistrosTempProdComp() {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "delete_data",
        funcion: "delete_datosProductoCompTempAll",
      },
      dataType: "json",
      success: function (respuesta) {
  
        if (respuesta[0].status) {
          console.log("OK");
        } else {
          console.log("Error");
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
}

function validate_Permissions(pkPantalla) {
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_Permisos",
        data: pkPantalla,
      },
      dataType: "json",
      success: function (data) {
        _permissions.read = data[0].isRead;
        _permissions.add = data[0].isAdd;
        _permissions.edit = data[0].isEdit;
        _permissions.delete = data[0].isDelete;
        _permissions.export = data[0].isExport;
  
        cargarDatosGrales(_global.pkProducto);
  
        if (_permissions.add == "1") {
          html = `<a href="#" class="btn-custom btn-custom--blue float-right" id="btnAgregarProducto">Guardar</a>`;
          $("#btnAgregarProducto2").html(html);
        }else{
          html = ``;
          $("#btnAgregarProducto2").html(html);
        }
      },
    });
}

$(document).on("click", "#btnAgregarProducto", function () {
    if ($("#formDatosProducto")[0].checkValidity()) {   
      var datos = {
        pkProducto: _global.pkProducto,
        estatus: $("#activeProducto").is(":checked") ? 1 : 0,
      };
  
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "edit_data",
          funcion: "edit_ListaMaterialesProducto",
          datos,
        },
        dataType: "json",
        success: function (respuesta) {
          if (respuesta[0].status) {
            //SeguirDatosImpuestos($("#txtPKProducto").val());
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Los materiales  del producto fueron agregados con éxito!",
              sound: '../../../../../sounds/sound4'
            });

            setTimeout(function(){
              window.location.href = "../lista_materiales"
            }, 1000);
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
              sound: '../../../../../sounds/sound4'
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
            sound: '../../../../../sounds/sound4'
          });
        },
      });
    }else{
      $("#formDatosProducto")[0].classList.add('was-validated');
    }
});

function cargarUnidadesSat(positionTable){
  _global.position = positionTable;

  var html = `<div style="position: fixed;
                  left: 0px;
                  top: 0px;
                  width: 100%;
                  height: 100%;
                  z-index: 9999;
                  background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                  opacity: .6;" id="loaderUnidad">
              </div>`;
  $('#cargarUnidadSAT').html(html);

  if( $("#contadorUnidadSAT").val() == 0){

    var buscador = $("#txtBuscarUnidad").val();

    $("#tblListadoUnidadesSAT").dataTable({
      "lengthChange": false,
      "pageLength": 100,
      "dom": 'lrtip',
      "info": false,
      "pagingType": "full_numbers",
      "ajax": {
        url:"../../php/funciones.php",
          data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador},
      },
      "columns":[
        { "data": "Id" },
        { "data": "Clave" },
        { "data": "Descripcion" },
  
      ],
      "language": setFormatDatatables(),
        columnDefs: [
          { orderable: false, targets: 0, visible: false },
        ],
        order:[],
        responsive: true
    });

    $("#contadorUnidadSAT").val('1');
  }

  $("#loaderUnidad").fadeOut("slow");
  
};

function buscandoUnidad(){
  $("#tblListadoUnidadesSAT").DataTable().destroy();
  var buscador = $("#txtBuscarUnidad").val();
  $("#tblListadoUnidadesSAT").dataTable({
    "lengthChange": false,
    "pageLength": 100,
    "dom": 'lrtip',
    "info": false,
    "pagingType": "full_numbers",
    "ajax": {
      url:"../../php/funciones.php",
        data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador},
    },
    "columns":[
      { "data": "Id" },
      { "data": "Clave" },
      { "data": "Descripcion" },

    ],
    "language": setFormatDatatables(),
      columnDefs: [
        { orderable: false, targets: 0, visible: false },
      ],
      order:[],
      responsive: true
  });

  $("#contadorUnidadSAT").val('1');
}

function guardarCantidadProdCompTemp(elemento) {
  var seleccion = $("#txtProductos" + elemento).val();
  var cantidad = 0;
  if ($("#txtCantidadCompuesta" + elemento).val() == '' || $("#txtCantidadCompuesta" + elemento).val() == null){
    cantidad = 0;
  }else{
    cantidad = $("#txtCantidadCompuesta" + elemento).val();
  }

  var costo = $("#txtCosto" + elemento).val();
  var moneda = $("#txtMoneda" + elemento).val();

  $("#lblCosto" + elemento).html(costo + " " + moneda);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosCantidadProductoCompTemp",
      datos2: seleccion,
      datos3: cantidad,
      datos4: costo,
      datos5: moneda,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        console.log("OK Act.");
      } else {
        console.log("Error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guardarCantidadEmpCompTemp(elemento) {
  var seleccion = $("#txtProductosEmp" + elemento).val();
  var cantidad = 0;
  if ($("#txtCantidadCompuestaEmp" + elemento).val() == '' || $("#txtCantidadCompuestaEmp" + elemento).val() == null){
    cantidad = 0;
  }else{
    cantidad = $("#txtCantidadCompuestaEmp" + elemento).val();
  }

  var costo = $("#txtCostoEmp" + elemento).val();
  var moneda = $("#txtMonedaEmp" + elemento).val();

  $("#lblCostoEmp" + elemento).html(costo + " " + moneda);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosCantidadProductoCompTemp",
      datos2: seleccion,
      datos3: cantidad,
      datos4: costo,
      datos5: moneda,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        console.log("OK Act.");
      } else {
        console.log("Error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guardarCostoProdCompTemp(elemento) {
  var seleccion = $("#txtProductos" + elemento).val();

  var costo = $("#txtCosto" + elemento).val();
  var moneda = $("#txtMoneda" + elemento).val();

  $("#lblCosto" + elemento).html(costo + " " + moneda);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosCostoProductoCompTemp",
      datos2: seleccion,
      datos4: costo,
      datos5: moneda,
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        console.log("OK Act.");
      } else {
        console.log("Error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guardarCostoEmpCompTemp(elemento) {
  var seleccion = $("#txtProductosEmp" + elemento).val();

  var costo = $("#txtCostoEmp" + elemento).val();
  var moneda = $("#txtMonedaEmp" + elemento).val();

  $("#lblCostoEmp" + elemento).html(costo + " " + moneda);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosCostoProductoCompTemp",
      datos2: seleccion,
      datos4: costo,
      datos5: moneda,
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        console.log("OK Act.");
      } else {
        console.log("Error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guardarMonedaProdCompTemp(elemento) {
  var seleccion = $("#txtProductos" + elemento).val();

  var costo = $("#txtCosto" + elemento).val();
  var moneda = $("#txtMoneda" + elemento).val();

  $("#lblCosto" + elemento).html(costo + " " + moneda);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosCostoProductoCompTemp",
      datos2: seleccion,
      datos4: costo,
      datos5: moneda,
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        console.log("OK Act.");
      } else {
        console.log("Error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guardarMonedaEmpCompTemp(elemento) {
  var seleccion = $("#txtProductosEmp" + elemento).val();

  var costo = $("#txtCostoEmp" + elemento).val();
  var moneda = $("#txtMonedaEmp" + elemento).val();

  $("#lblCostoEmp" + elemento).html(costo + " " + moneda);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosCostoProductoCompTemp",
      datos2: seleccion,
      datos4: costo,
      datos5: moneda,
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        console.log("OK Act.");
      } else {
        console.log("Error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function guardarColectivosEmpCompTemp(elemento) {
  var seleccion = $("#txtProductosEmp" + elemento).val();

  var colectivas = $("#txtColectivas" + elemento).val();

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_colectivosEmpaqueCompTemp",
      datos: seleccion,
      datos2: colectivas
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        console.log("OK Act.");
      } else {
        console.log("Error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
function obtenerIdUnidadSeleccionar(id, clave, descripcion) {
  $(`#lblUnidadMedida${_global.position}`).html(descripcion);

  var seleccion = $("#txtProductos" + _global.position).val();

  var costo = $("#txtCosto" + _global.position).val();
  var moneda = $("#txtMoneda" + _global.position).val();

  $("#lblCosto" + _global.position).html(costo + " " + moneda);

  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_datosUnidadMProductoCompTemp",
      datos2: seleccion,
      datos3: id,
      datos4: costo,
      datos5: moneda,
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        console.log("OK Act.");
      } else {
        console.log("Error");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}