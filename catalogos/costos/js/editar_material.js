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
  slims: '',
};

$(document).ready(function(){
  validate_Permissions(58);
});

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

      if (_permissions.edit == "1") {
        html = `<a href="#" class="btn-custom btn-custom--blue float-right" id="btnAgregarProducto">Guardar</a>`;
        $("#btnAgregarProducto2").html(html);
      }else{
        html = ``;
        $("#btnAgregarProducto2").html(html);
      }
    },
  });
}

function cargarDatosGrales(id) {
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
  
        if (parseInt(data[0].FKTipoProducto) != 3 && parseInt(data[0].FKTipoProducto) != 5) {
          cargarCompuestos(id);
          $("#cmbTipoProducto").attr("disabled", "true");
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
                    <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(1)"><span id="lblUnidadMedida1">${unidadMedida}</span></label>
                  </div>
                </div>
              </td>
              <td>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <div class="row">
                      <div class="col-lg-6">
                        <label  for="usr"><span id="lblCosto1" hidden> </span><input class="form-control" type="text" id="txtCosto1" value="${dato.Costo}" onkeyup="guardarCostoProdCompTemp(1)" required></label>
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
                    <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(${contador})"><span id="lblUnidadMedida${contador}">${unidadMedida}</span></label>
                  </div>
                </div>
              </td>
              <td>
                <div class="row">
                  <div class="col-lg-12 input-group">
                    <div class="row">
                      <div class="col-lg-6">
                        <label  for="usr"><span id="lblCosto${contador}" hidden> </span><input class="form-control" type="text" id="txtCosto${contador}" value="${dato.Costo}" onkeyup="guardarCostoProdCompTemp(${contador})" required></label>
                      </div>
                      <div class="col-lg-6">
                        <select id="txtMoneda${contador}" onchange="guardarMonedaProdCompTemp(${contador})"></select>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" onclick="eliminarCompTemp(${contador}); event.preventDefault(); $(this).closest('tr').remove(); "></i>
              </td>
            </tr>`;
        }

        _global.slims += `cargarCMBMoneda(${dato.FKTipoMoneda},'txtMoneda${contador}');

        new SlimSelect({
          select: '#txtMoneda${contador}',
          deselectLabel: '<span class="">✖</span>',
        });`;

        contador = parseInt(contador) + 1;

        
      }

      var body =
        `<div class="form-group">
                    <div class="row">
                      <div class="col-lg-12">
                        <label for="usr">Materiales del producto:</label>
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
                              <th style="width:40%">Clave/Producto*</th>
                              <th style="width:30%">Cantidad y unidad de medida</th>
                              <th style="width:25%">Costo</th>
                              <th style="width:5%"></th>
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

      
      eval(_global.slims);
      fn();
      
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

function agregarFila() {
  var table = document.getElementById("tablaprueba");
  var rowCount = table.rows.length;
  document.getElementById("tablaprueba").insertRow(-1).innerHTML =
    `<td>
      <input  name="txtProductos${rowCount}" id="txtProductos${rowCount}" type="hidden" readonly>
      <input type="text" class="form-control" name="cmbProductos${rowCount}" id="cmbProductos${rowCount}" data-toggle="modal" data-target="#agregar_Producto" 
      placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarProd(${rowCount})">
    </td>
    <td>
      <div class="row">
        <div class="col-lg-6">
          <input class="form-control" type="number" name="txtCantidadCompuesta${rowCount}" id="txtCantidadCompuesta${rowCount}" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(${rowCount})">
        </div>
        <div class="col-lg-6">
          <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(${rowCount})"><span id="lblUnidadMedida${rowCount}"> </span></label>
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