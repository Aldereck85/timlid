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

$(document).ready(function () {
  validate_Permissions(73);
});

function setFormatDatatables() {
  var idioma_espanol = {
    sProcessing: "Procesando...",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sSearch: "<img src='../../../../img/timdesk/buscar.svg' width='20px' />",
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

function validate_Permissions(pkPantalla) {

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "validar_Permisos", data: pkPantalla },
    dataType: "json",
    success: function (data) {
      var topButtons = [];
      _permissions.read = data[0].isRead;
      _permissions.add = data[0].isAdd;
      _permissions.edit = data[0].isEdit;
      _permissions.delete = data[0].isDelete;
      _permissions.export = data[0].isExport;

      //PRODUCTOS
      if (pkPantalla == "73") {
        if (_permissions.add == "1") {
          topButtons.push({
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
            className: "btn-custom--white-dark",
            action: function () {
              cargarProductos();

              $("#areaDatos").html("");
              $("#areaCompuesto").html("");
              $("#costosModal").modal('show');
            },
          });
        }
        if (_permissions.export == "1") {
          topButtons.push({
            extend: "excelHtml5",
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
            className: "btn-custom--white-dark",            
            titleAttr: "Excel",
          });
        }
      }

      $("#tblCostos").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 15,
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
          buttons: topButtons,
        },
        ajax: {
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_listaCostosTable",
            data: _permissions.edit,
            data2: _permissions.delete,
          },
        },
        columns: [
          { data: "Id" },
          { data: "ClaveInterna" },
          { data: "Nombre" },
          { data: "Costo componentes" },
          { data: "Costo adicionales" },
          { data: "Gastos fijos" },
          { data: "Utilidad" },
          { data: "Costo total" },
          { data: "Imagen" },
          { data: "estatus" },
          { data: "Acciones" }
        ],
        columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 8, visible: false },
            { targets: [2,3,4,5,6,8],class: "text-center"}
        ],
      });
    },
    error: function(error){
      console.log(error);

      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "Ocurrio un error, intentalo nuevamente",
      });

    }
  });
}

function isExport() {
  if (_permissions.export == "1") {
    return '<img class="readEditPermissions" type="submit" width="50px" id="btnExportPermissions" onclick="exportarPDF()" src="../../../../img/excel-azul.svg" />';
  } else {
    return "";
  }
}

function obtenerEditarListaMaterial(id) {
  window.location.href = "editar_material.php?lstm=" + id;
}

let pkCosto = 0;
function obtenerIDCosto(id) {
  pkCosto = id;
}

var selectMonedaEdit;
let datos_tabla, datos_adicionales, datos_GF, total_costo_glb, total_costo_componentes_glb, total_costo_adicionales_glb, total_costo_gastos_fijos_glb, utilidad_glb, utilidad_porcentaje_glb;
function editarCosto(id) {
  pkCosto = id;
  $.ajax({
       url: "../../php/funciones.php",
       data: { clase: "get_data", funcion: "get_Costos", datos: pkCosto },
       success: function(respuesta) {

        var datos = JSON.parse(respuesta);
        $("#idCostoEdit").val(datos.idCosto);
        $("#textProducto").val(datos.producto);

        selectMonedaEdit.destroy();

        $("#cmbMonedaEdit").html(datos.moneda);
        total_costo_glb = datos.total_costo;
        total_costo_adicionales_glb = datos.total_costo_adicionales; 
        total_costo_gastos_fijos_glb = datos.total_costo_gasto_fijo;
        total_costo_componentes_glb = datos.total_costo_componentes; 
        utilidad_glb = datos.utilidad; 
        utilidad_porcentaje_glb = datos.utilidad_porcentaje; 

        selectMonedaEdit = new SlimSelect({
          select: '#cmbMonedaEdit',
          deselectLabel: '<span class="">✖</span>'
        });

        _global.pkProducto = datos.idProducto;
        datos_tabla = datos.datos_tabla;
        datos_adicionales = datos.datos_adicionales;
        datos_GF = datos.datos_GF;
        contadorEditar = datos.contadorProducto;
        contadorAdicionalEdit = datos.contadorAdicionales;
        cargarAreaDatos(2);
       /* if(inicial == 0){
          selectProductos = new SlimSelect({
            select: '#cmbProductoMaterial',
            deselectLabel: '<span class="">✖</span>'
          });
          inicial = 1;
        }

        selectProductos.destroy();

        $("#cmbProductoMaterial").html(respuesta);

        selectProductos = new SlimSelect({
          select: '#cmbProductoMaterial',
          deselectLabel: '<span class="">✖</span>'
        });*/
        
      },
      error: function(){

        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "Ocurrio un error, intentalo nuevamente",
        });

      }
    });

}

function elminarCosto() {
  var pkCosto = document.getElementById('idCostoEdit').value;
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_Costos",
      datos: pkCosto,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        $("#tblCostos").DataTable().ajax.reload();
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "Se eliminó el costo.",
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
          msg: "Ocurrio un error, vuelva a intentarlo.",
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
        msg: "Ocurrio un error, vuelva a intentarlo.",
        sound: "../../../../../sounds/sound4",
      });
    },
  });
}

let inicial = 0;
let selectProductos;
function cargarProductos(){
    $.ajax({
         url: "../../php/funciones.php",
         data: { clase: "get_data", funcion: "get_Productos" },
         success: function(respuesta) {

          if(inicial == 0){
            selectProductos = new SlimSelect({
              select: '#cmbProductoMaterial',
              addable: function (value) {
                //alert("444");
                //agregarNuevoProducto();
                if (value === 'bad') { agregarNuevoProducto(); return false}

                // Return the value string
                agregarNuevoProducto();
                return value // Optional - value alteration // ex: value.toLowerCase()

                // Optional - Return a valid data object. See methods/setData for list of valid options
                return {
                  /*text: value,
                  value: value.toLowerCase()*/
                }
              }
            });
            inicial = 1;
          }

          selectProductos.destroy();

          $("#cmbProductoMaterial").html(respuesta);

          selectProductos = new SlimSelect({
            select: '#cmbProductoMaterial',
            addable: function (value) {
                agregarNuevoProducto();
                value = 'existe';
                if (value === 'bad') { agregarNuevoProducto(); return false}

                // Return the value string
                agregarNuevoProducto();
                //return true; // Optional - value alteration // ex: value.toLowerCase()

                // Optional - Return a valid data object. See methods/setData for list of valid options
                return {
                  /*text: value,
                  value: value.toLowerCase()*/
                }
                //alert("444");
                //agregarNuevoProducto();
              }
          });

        },
        error: function(){

          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Ocurrio un error, intentalo nuevamente!",
          });

        }
      });
}

let contadorRepeticiones = 0;
let idProductoAnterior = -1;
$(document).on('change','#cmbProductoMaterial',function(){


    let valorProducto = $("#cmbProductoMaterial").val();
//console.log(valorProducto + " - " + contadorRepeticiones);
    idProductoAnterior = valorProducto;
  
    if((idProductoAnterior != -1 && idProductoAnterior != 'add' || idProductoAnterior != null) && (valorProducto != 'add' || valorProducto != null)){
      //alert("limp");
      $("#areaDatos").html("");
      $("#areaCompuesto").html("");
    }

    if((valorProducto == 'add' || valorProducto == null) && contadorRepeticiones == 0){
      //console.log("inicio ejec");
      agregarNuevoProducto();
    }

    if((valorProducto != 'add' && valorProducto != null) && contadorRepeticiones == 0){
      _global.pkProducto = $("#cmbProductoMaterial").val();
      cargarAreaDatos(1);
    }

    
});

function cargarAreaDatos(modo){
    if(modo == 1){
      var elements = `<div class="form-group">
                    <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group " id="btnDeletePermissions">
                        <div class="row">
                            
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
                                <textarea class="form-control " type="text" id="txtDescripcionLarga" name="txtDescripcionLarga" readonly></textarea>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="col-lg-4" style="display:none;">
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
      cargarDatosGrales(_global.pkProducto);
      cargarTablaCompuestos();
    }
    else{

      var elements = `<div class="form-group">
                    <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group " id="btnDeletePermissions">
                        <div class="row">
                        </div>
                        </div>
                        
                        <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                            <label for="usr">Nombre:*</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control " type="text" name="txtNombreEdit" id="txtNombreEdit" disabled>
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
                                <input class="form-control " type="text" name="txtClaveInternaEdit" id="txtClaveInternaEdit" style="text-transform:uppercase" disabled>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <label for="usr">Código de barras:</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control " type="text" name="txtCodigoBarrasEdit" id="txtCodigoBarrasEdit" disabled>
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
                                <input class="form-control " type="text" name="cmbCategoriaProductoEdit" id="cmbCategoriaProductoEdit" disabled>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-6">
                            <label for="usr">Marca:</label>
                            <div class="row">
                                <div class="col-lg-12 input-group">
                                <input class="form-control " type="text" name="cmbMarcaProductoEdit" id="cmbMarcaProductoEdit" disabled>
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
                                <textarea class="form-control " type="text" id="txtDescripcionLargaEdit" name="txtDescripcionLargaEdit" readonly></textarea>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="col-lg-4" style="display:none;">
                        <div class="file-field">
                        <span id="espacioImagenEdit" >
                        <div class="mb-4 img-thumbnail" style="position: relative; width:350px; height:350px; display:block; margin:auto; no-repeat rgb(249,249,249); opacity: .6;">
                            <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg"
                            alt="example placeholder" id="imgProd" name="imgProdEdit" width="100px" height="100px" style=" position: absolute; top: 35%; right: 0; left: 0; margin: 0 auto;">
                        </div>
                        </span>
                        <div class="d-flex justify-content-center">
                            <span id="espacioFileEdit">
                            </span>
                        </div>
                        </div>
                    </div>
                    </div>
                    </div>`;

      $("#areaDatosEdit").html(elements);
      cargarDatosGralesEdit(_global.pkProducto);
      cargarTablaCompuestosEdit();
    }
  
}

$(document).on("click", "#addProd", function(){
  $("#"+$(this).attr("data-click")).click();
});

function cargarTablaCompuestos(){
    var body = `<div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr"><h3>Componentes:</h3></label>
                    </div>
                  </div>
                </div>
                <input  name="txtSeleccion" id="txtSeleccion" type="hidden" readonly>
                <input  name="txtSeleccionProveedor" id="txtSeleccionProveedor" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tablaprueba" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:1%"></th>
                            <th style="width:30%">Clave/Producto*</th>
                            <th style="width:22%">Cantidad y unidad de medida</th>
                            <th style="width:12%">Costo</th>
                            <th style="width:23%">Proveedor</th>
                            <th style="width:12%">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd" data-click="cmbProductos1" name="addProd" width="20px" height="20px" style=" position: relative; cursor:pointer;" >
                            </td>
                            <td>
                              <input class="contabilizarProductos" name="txtProductos1" id="txtProductos1" type="hidden" readonly>
                              <input type="text" class="form-control" name="cmbProductos1" id="cmbProductos1" data-toggle="modal" data-target="#agregar_Producto" 
                              placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProd(1)">
                              <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                              src="../../../../img/timdesk/alerta.svg" width=30px
                              title="Seleccione por lo menos un producto" readonly>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-6">
                                  <input class="form-control decimal cantidadProducto" type="text" name="txtCantidadCompuesta_1" id="txtCantidadCompuesta_1" min="0" maxlength="12" required placeholder="Ej. 10">

                                </div>
                                <div class="col-lg-6">
                                  <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(1, 1)"><span id="lblUnidadMedida1"> </span></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <label  for="usr"><span id="lblCosto1" hidden> </span>
                                          <div class="row">
                                            <div class="col-lg-8"><input class="form-control precioProducto" type="text" id="txtCosto_1"  name="txtCosto_1" maxlength="12" required></div>                                    
                                            <div class="col-lg-4"><i><img class="btnEdit" onclick="cargarCostos(1,1);" src="../../../../img/timdesk/ver.svg"></i></div> 
                                      </label>
                                    </div>  
                                  </div>  
                                </div>
                              </div>
                            </td>
                            <td>
                              <input  name="txtProveedores1" id="txtProveedores1" type="hidden" value="0" readonly>
                              <input type="text" class="form-control" name="cmbProveedores1" id="cmbProveedores1" data-toggle="modal" data-target="#agregar_Proveedores" 
                              placeholder="Seleccione un proveedor..." readonly required="" onclick="clickSeleccionarProv(1)" value='S/P - Sin Proveedor'>
                              <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                              src="../../../../img/timdesk/alerta.svg" width=30px
                              title="Seleccione un proveedor" readonly>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <label  for="usr"><span id="lblCosto1" hidden> </span><input class="form-control getTotales" type="text" id="txtTotalCosto1"  required readonly></label>
                                    </div>  
                                  </div>  
                                </div>
                              </div>
                            </td>
                            <td>
                                <i><img class="btnEdit" src="../../../../img/timdesk/delete.svg" id="btnEliminar_1" onclick="eliminarCompTemp(this);"></i>
                            </td>
                          </tr>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <i><img  src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" onclick="agregarFila()" width="30px">  </i>
                      <label onclick="agregarFila()">  Añadir componente</label>
                    </div>
                    <div class="col-lg-6" style:"float: right;">
                      <div class="row">
                        <div class="col-lg-6"><h4>Total componentes:</h4></div>
                        <div class="col-lg-6" id="totalProducto"><h4>$ <span id="totalComponentes">0.00</span></h4></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr"><h3>Gastos fijos:</h3></label>
                    </div>
                  </div>
                </div>
                <input  name="txtSeleccionGastoF" id="txtSeleccionGastoF" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tablaprueba_gastosFijos" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:1%"></th>
                            <th style="width:35%">Clave/Producto*</th>
                            <th style="width:16%">unidad de medida</th>
                            <th style="width:16%">Costo</th>
                            <th style="width:16%">porcentaje aplicado</th>
                            <th style="width:17%">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar gasto fijo" id="addProd" data-click="cmbGastosF1" name="addProd" width="20px" height="20px" style=" position: relative; cursor:pointer;" >
                            </td>
                            <td>
                              <input class="contabilizarGastosF" name="txtGastoF1" id="txtGastoF1" type="hidden" readonly>
                              <input type="text" class="form-control" name="cmbGastosF1" id="cmbGastosF1" data-toggle="modal" data-target="#agregar_GastoF" 
                              placeholder="Seleccione un Gasto fijo..." readonly onclick="clickSeleccionarProdGastoF(1)">
                            </td>
                            <td>
                              <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatGastoF(1, 8)"><span id="lblUnidadMedidaGastoF1"> </span></label>
                            </td>
                            <td>
                              <div class="col-lg-12"><input class="form-control precioGastoF" type="text" id="BtxtCostoGastoF_1"  name="BtxtCostoGastoF_1" maxlength="12"></div>                                    
                            </td>
                            <td>
                              <input type="text" class="form-control utilidadPorcentajeClass porcentajeGastoF" id="AtxtGastoFPorcentaje_1" name="AtxtGastoFPorcentaje_1" maxlength="5" value="0.00" style="width:200px;">  
                            </td>
                            <td>
                              <label  for="usr"><span id="lblCostoGastoF1" hidden> </span><input class="form-control getTotalesGF" type="text" id="txtTotalCostoGastoF1" readonly></label>      
                            </td>
                            <td>
                                <i><img class="btnEdit" src="../../../../img/timdesk/delete.svg" id="btnEliminar_1" onclick="eliminarGastoF(this);"></i>
                            </td>
                          </tr>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <i><img  src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" onclick="agregarFilaGastoF()" width="30px">  </i>
                      <label onclick="agregarFilaGastoF()">  Añadir gasto fijo</label>
                    </div>
                    <div class="col-lg-6" style:"float: right;">
                      <div class="row">
                        <div class="col-lg-6"><h4>Total gastos fijos:</h4></div>
                        <div class="col-lg-6" id="totalProducto"><h4>$ <span id="totalGastoF">0.00</span></h4></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr"><h3>Adicionales:</h3></label>
                    </div>
                  </div>
                </div>
                 <input  name="txtSeleccionAdicionales" id="txtSeleccionAdicionales" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table" id="tablaprueba_adicionales" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:1%"></th>
                            <th style="width:35%">Clave/Producto*</th>
                            <th style="width:30%">Cantidad y unidad de medida</th>
                            <th style="width:17%">Costo</th>
                            <th style="width:17%">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd" data-click="cmbProductosAdicionales1" name="addProdAdicional" width="20px" height="20px" style=" position: relative;">
                            </td>
                            <td>
                              <input class="contabilizarProductosAdicionales" name="AtxtProductosAdicionales1" id="AtxtProductosAdicionales1" type="hidden" readonly>
                              <input type="text" class="form-control" name="cmbProductosAdicionales1" id="cmbProductosAdicionales1" data-toggle="modal" data-target="#agregar_Producto_Adicionales" 
                              placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProdAdicionales(1)">
                              <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                              src="../../../../img/timdesk/alerta.svg" width=30px
                              title="Seleccione por lo menos un producto" readonly>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-6">
                                  <input class="form-control decimal cantidadProductoAdicionales" type="text" name="AtxtCantidadCompuestaAdicionales_1" id="AtxtCantidadCompuestaAdicionales_1" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required="" placeholder="Ej. 10">
                                </div>
                                <div class="col-lg-6">
                                  <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatAdicionales(1,3)"><span id="lblUnidadMedidaAdicionales1"> </span></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <label  for="usr"><span id="lblCosto1" hidden> </span>
                                      <div class="row">
                                        <div class="col-lg-8"><input class="form-control precioProductoAdicionales" type="text" id="AtxtCostoAdicionales_1"  name="AtxtCostoAdicionales_1" maxlength="12" required></div> 
                                        <div class="col-lg-4"><i><img class="btnEdit" onclick="cargarCostosAdicionales(1,3);" src="../../../../img/timdesk/ver.svg"></i></div> 
                                      </di>
                                      </label>
                                    </div>  
                                  </div>  
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <label  for="usr"><span id="lblCosto1" hidden> </span><input class="form-control getTotalAdicionales" type="text" id="txtTotalCostoAdicionales1"  required readonly></label>
                                    </div>  
                                  </div>  
                                </div>
                              </div>
                            </td>
                            <td>
                              <i><img class="btnEdit" src="../../../../img/timdesk/delete.svg" id="btnEliminarAdicionales_1" onclick="eliminarCompTempAdicionales(this);"></i>
                            </td>
                          </tr>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <i><img  src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" onclick="agregarFilaAdicionales()" width="30px">  </i>
                      <label onclick="agregarFilaAdicionales()">  Añadir adicional</label>
                    </div>
                    <div class="col-lg-6" style:"float: right;">
                      <div class="row">
                        <div class="col-lg-6"><h4>Total adicionales:</h4></div>
                        <div class="col-lg-6"><h4>$ <span id="totalAdicionales">0.00</span></h4></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                      <div class="col-lg-6">
                        <h3>Utilidad: </h3><input class="form-control utilidadPorcentajeClass" type="text" id="AtxtUtilidades"  name="AtxtUtilidades" maxlength="10" value="0.00" style="width:200px;" />
                      </div>
                      <div class="col-lg-6">
                        <label  for="usr">Utilidad %: </label><input type="text" class="form-control utilidadPorcentajeClass" id="AtxtUtilidadesPorcentaje"  name="AtxtUtilidadesPorcentaje" maxlength="5" value="0.00" style="width:200px;" />
                      </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6" style:"float: right;">
                      <div class="row">
                        <div class="col-lg-6"><h4>Total:</h4></div>
                        <div class="col-lg-6"><h4>$ <span id="total">0.00</span></h4></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <center>
                      <button type="button" class="btn-custom btn-custom--blue" id="btGuardarCostos">Guardar</button>
                    </center>
                  </div>
                </div>
                `;
    $("#areaCompuesto").html(body);

    cargarCMBProductos(0);
    cargarCMBGastosF(0);
    cargarCMBProductosAdicionales(0);
    cargarCMBProveedor();

}

function cargarTablaCompuestosEdit(){
    var body = `<div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr"><h3>Componentes:</h3></label>
                    </div>
                  </div>
                </div>
                <input  name="txtSeleccionEdit" id="txtSeleccionEdit" type="hidden" readonly>
                <input  name="txtSeleccionProveedorEdit" id="txtSeleccionProveedorEdit" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table dataTable no-footer" id="tablapruebaedit" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:1%"></th>
                            <th style="width:30%">Clave/Producto*</th>
                            <th style="width:22%">Cantidad y unidad de medida</th>
                            <th style="width:12%">Costo</th>
                            <th style="width:23%">Proveedor</th>
                            <th style="width:12%">Total</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="cargarProductosCosto">
                          

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <i><img  src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" onclick="agregarFilaEdit()" width="30px">  </i>
                      <label onclick="agregarFilaEdit()">  Añadir producto</label>
                    </div>
                    <div class="col-lg-6" style:"float: right;">
                      <div class="row">
                        <div class="col-lg-6"><h4>Total componentes:</h4></div>
                        <div class="col-lg-6"><h4>$ <span id="totalComponentesEdit">${total_costo_componentes_glb}</span></h4></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr"><h3>Gastos fijos:</h3></label>
                    </div>
                  </div>
                </div>
                 <input  name="txtSeleccionGastoFEdit" id="txtSeleccionGastoFEdit" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table dataTable no-footer" id="tablaprueba_gastos_fijos_edit" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:1%"></th>
                            <th style="width:35%">Clave/Producto*</th>
                            <th style="width:16%">Unidad de medida</th>
                            <th style="width:16%">Costo</th>
                            <th style="width:16%">Porcentaje aplicado</th>
                            <th style="width:17%">Total</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="cargarGastosFijosCosto">
                          

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <i><img  src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" onclick="agregarFilaGastosFEdit()" width="30px">  </i>
                      <label onclick="agregarFilaGastosFEdit()">  Añadir gasto fijo</label>
                    </div>
                    <div class="col-lg-6" style:"float: right;">
                      <div class="row">
                        <div class="col-lg-6"><h4>Total gastos fijos:</h4></div>
                        <div class="col-lg-6"><h4>$ <span id="totalGastosFEdit">${total_costo_gastos_fijos_glb}</span></h4></div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="usr"><h3>Adicionales:</h3></label>
                    </div>
                  </div>
                </div>
                 <input  name="txtSeleccionAdicionalesEdit" id="txtSeleccionAdicionalesEdit" type="hidden" readonly>
                <div class="form-group">
                  <!-- DataTales Example -->
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table dataTable no-footer" id="tablaprueba_adicionales_edit" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th style="width:1%"></th>
                            <th style="width:35%">Clave/Producto*</th>
                            <th style="width:30%">Cantidad y unidad de medida</th>
                            <th style="width:17%">Costo</th>
                            <th style="width:17%">Total</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="cargarProductosAdicionalesCosto">
                          

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <i><img  src=\"../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg\" onclick="agregarFilaAdicionalesEdit()" width="30px">  </i>
                      <label onclick="agregarFilaAdicionalesEdit()">  Añadir adicional</label>
                    </div>
                    <div class="col-lg-6" style:"float: right;">
                      <div class="row">
                        <div class="col-lg-6"><h4>Total adicionales:</h4></div>
                        <div class="col-lg-6"><h4>$ <span id="totalAdicionalesEdit">${total_costo_adicionales_glb}</span></h4></div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="form-group">
                  <div class="row">
                      <div class="col-lg-6">
                        <h3>Utilidad: </h3><input class="form-control utilidadPorcentajeClass" type="text" id="AtxtUtilidadesEdit"  name="AtxtUtilidadesEdit" maxLength="10" value="${utilidad_glb}" style="width:200px;">
                      </div>
                      <div class="col-lg-6">
                        <label  for="usr">Utilidad %: </label><input class="form-control utilidadPorcentajeClass" type="text" id="AtxtUtilidadesPorcentajeEdit"  name="AtxtUtilidadesPorcentajeEdit" maxLength="5" value="${utilidad_porcentaje_glb}" style="width:200px;">
                      </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6" style:"float: right;">
                      <div class="row">
                        <div class="col-lg-6"><h4>Total:</h4></div>
                        <div class="col-lg-6"><h4>$ <span id="totalEdit">${total_costo_glb}</span></h4></div>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-lg-12">
                    <center>
                      <button type="button" class="btn-custom btn-custom--blue" id="btEditarCostos">Modificar</button>
                      <button type="button" class="btn-custom btn-custom--blue" id="btEliminarCostos">Eliminar</button>
                    </center>
                  </div>
                </div>
                `;
    $("#areaCompuestoEdit").html(body);
    $("#cargarProductosCosto").html(datos_tabla);
    $("#cargarProductosAdicionalesCosto").html(datos_adicionales);
    $("#cargarGastosFijosCosto").html(datos_GF);

    cargarCMBProductosEdit(0);
    cargarCMBProveedorEdit();

    cargarCMBProductosAdicionalesEdit(0);
    cargarCMBGastosFEdit(0);
    document.getElementById('btEliminarCostos').addEventListener('click',()=>{
        $("#editarCostosModal").modal("hide");
        $("#eliminar_Costo").modal("show");
    });

}



function cargarDatosGrales(id) {

  //console.log('aqui');
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
            `<div class="mb-4" style="position: relative; width:280px; height:280px; display:block; margin:auto;">
                      <img class="z-depth-1-half img-thumbnail" src="${_global.rutaServer}${data[0].Imagen}" alt="example placeholder" id="imgProd" name="imgProd" style=" position: absolute;">
                    </div>
                    <input type="hidden" id="imagenSubir" name="imagenSubir" value="" /> `;
  
          $("#espacioImagen").html(imagen);
        }
      },
    });
}


function cargarDatosGralesEdit(id) {

  //console.log('aqui');
    $.ajax({
      url: "../../php/funciones.php",
      data: { clase: "get_data", funcion: "get_DataDatosProducto", data: id },
      dataType: "json",
      success: function (data) {

        $("#cmbEstatusProductoEdit").val(parseInt(data[0].FKEstatusGeneral));
        if (data[0].FKEstatusGeneral == 1) {
          $("#activeProductoEdit").attr("checked", "true");
        }
        
        $("#txtNombreEdit").val(data[0].Nombre);
        _global.txtHistorialNombre = data[0].Nombre;
  
        $("#txtClaveInternaEdit").val(data[0].ClaveInterna);
        _global.txtHistorialClave = data[0].ClaveInterna;
  
        $("#txtCodigoBarrasEdit").val(data[0].CodigoBarras);
        _global.txtHistorialCodigoBarras = data[0].CodigoBarras;
  
        if (parseInt(data[0].FKCategoriaProducto) == 0) {
          $("#cmbCategoriaProductoEdit").val('Sin Categoría');
        } else {
          $("#cmbCategoriaProductoEdit").val(data[0].categoria);
        }
  
        if (parseInt(data[0].FKMarcaProducto) == 0) {
          $("#cmbMarcaProductoEdit").val('Sin Marca');
        } else {
          $("#cmbMarcaProductoEdit").val(data[0].marca);
        }

        $("#txtDescripcionLargaEdit").val(data[0].Descripcion);
  
        if (data[0].Imagen == "agregar.svg") {
          imagen = `<div class="mb-4 img-thumbnail" style="position: relative; width:350px; height:350px; display:block; margin:auto; no-repeat rgb(249,249,249); opacity: .6;">
                      <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR2_Mesa de trabajo 1.svg" alt="example placeholder" id="imgProdEdit" name="imgProdEdit" width="100px" height="100px" style=" position: absolute; top: 35%; right: 0; left: 0; margin: 0 auto;">
                    </div>`;
  
          $("#espacioImagenEdit").html(imagen);
        } else {
          imagen =
            `<div class="mb-4" style="position: relative; width:280px; height:280px; display:block; margin:auto;">
                      <img class="z-depth-1-half img-thumbnail" src="${_global.rutaServer}${data[0].Imagen}" alt="example placeholder" id="imgProdEdit" name="imgProdEdit" style=" position: absolute;">
                    </div>
                    <input type="hidden" id="imagenSubirEdit" name="imagenSubirEdit" value="" /> `;
  
          $("#espacioImagenEdit").html(imagen);
        }
      },
    });
}

let opcionProveedores = 0;
function cargarCMBProveedor(){

        var topButtons = [];

        topButtons.push({
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> </span>',
            className: "btn-custom--white-dark",
            action: function () {
              
              opcionProveedores = 1;
              $("#agregar_Proveedores_Modal").modal('show');

            },
          });

        $("#tblListadoProveedores").DataTable().destroy();
        $("#tblListadoProveedores").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_cmb_proveedores", modo: 1},
        },
        "columns":[
            { "data": "Id" },
            { "data": "Nombre" },
            { "data": "Razon" },
            { "data": "Estatus" },

        ],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-0 col-md-0 p-0"><"col-sm-12 col-md-12 p-0"p>>>`,
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
          buttons: topButtons,
        },
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 3, visible: false },
            ],
            responsive: true
        });
}

function cargarCMBProveedorEdit(){

        var topButtons = [];

        topButtons.push({
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> </span>',
            className: "btn-custom--white-dark",
            action: function () {
              
              $("#agregar_Proveedores_Modal").modal('show');
              opcionProveedores = 2;

            },
          });

        $("#tblListadoProveedoresEdit").DataTable().destroy();
        $("#tblListadoProveedoresEdit").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_cmb_proveedores", modo: 2},
        },
        "columns":[
            { "data": "Id" },
            { "data": "Nombre" },
            { "data": "Razon" },
            { "data": "Estatus" },

        ],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-0 col-md-0 p-0"><"col-sm-12 col-md-12 p-0"p>>>`,
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
          buttons: topButtons,
        },
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 3, visible: false },
            ],
            responsive: true
        });
}


function obtenerIdProveedorSeleccionar(id, nombre) {

    var seleccion = $("#txtSeleccionProveedor").val();

    $('#txtProveedores'+seleccion).val(id);
    $('#cmbProveedores'+seleccion).val(nombre);

    /*$.ajax({
        url: '../../php/funciones.php',
        data:{clase:"get_data", funcion:"validar_producto_compuesto_temp", data:id},
        dataType:"json",
        success: function(data) {
          // Validar si ya existe el identificador con ese nombre
          if (parseInt(data[0]['existe']) == 1) {
            Swal.fire('Material duplicado',"El material se agregó con anterioridad","warning");
          } else {

            var seleccion = $("#txtSeleccion").val();
            

            document.getElementById('txtProductos'+seleccion).value = id;
            document.getElementById('txtCantidadCompuesta'+seleccion).value = '1';
            if(claveInterna == ''){
                document.getElementById('cmbProductos'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbProductos'+seleccion).value = claveInterna + ' - ' + nombre;
            }
            if(unidadMedida == ''){
                $('#lblUnidadMedida'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedida'+seleccion).html(unidadMedida);
            }

            $('#lblCosto'+seleccion).html(costo +' '+ moneda);
            $('#txtCosto'+seleccion).val(costo);
            
            cargarCMBMoneda(parseInt(fkMoneda),'txtMoneda'+seleccion);

            var cantidad = $("#txtCantidadCompuesta"+seleccion).val(); 

            $.ajax({
                url:"../../php/funciones.php",
                data:{clase:"save_data", funcion:"save_datosProductoCompTemp", datos:id, datos2: cantidad, datos4: seleccion, datos5: costo, datos6: moneda},
                dataType:"json",
                success:function(respuesta){
        
                if(respuesta[0].status){
                    console.log('OK')
                }else{
                    console.log('Error');
                }
        
                },
                error:function(error){
                console.log(error);
                }
            });
            console.log('No Duplicate');
          }   
        }
    });*/
}

function obtenerIdProveedorSeleccionarEdit(id, nombre) {

    var seleccion = $("#txtSeleccionProveedorEdit").val();

    $('#txtProveedoresEdit_'+seleccion).val(id);
    $('#cmbProveedoresEdit'+seleccion).val(nombre);

    /*$.ajax({
        url: '../../php/funciones.php',
        data:{clase:"get_data", funcion:"validar_producto_compuesto_temp", data:id},
        dataType:"json",
        success: function(data) {
          // Validar si ya existe el identificador con ese nombre
          if (parseInt(data[0]['existe']) == 1) {
            Swal.fire('Material duplicado',"El material se agregó con anterioridad","warning");
          } else {

            var seleccion = $("#txtSeleccion").val();
            

            document.getElementById('txtProductos'+seleccion).value = id;
            document.getElementById('txtCantidadCompuesta'+seleccion).value = '1';
            if(claveInterna == ''){
                document.getElementById('cmbProductos'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbProductos'+seleccion).value = claveInterna + ' - ' + nombre;
            }
            if(unidadMedida == ''){
                $('#lblUnidadMedida'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedida'+seleccion).html(unidadMedida);
            }

            $('#lblCosto'+seleccion).html(costo +' '+ moneda);
            $('#txtCosto'+seleccion).val(costo);
            
            cargarCMBMoneda(parseInt(fkMoneda),'txtMoneda'+seleccion);

            var cantidad = $("#txtCantidadCompuesta"+seleccion).val(); 

            $.ajax({
                url:"../../php/funciones.php",
                data:{clase:"save_data", funcion:"save_datosProductoCompTemp", datos:id, datos2: cantidad, datos4: seleccion, datos5: costo, datos6: moneda},
                dataType:"json",
                success:function(respuesta){
        
                if(respuesta[0].status){
                    console.log('OK')
                }else{
                    console.log('Error');
                }
        
                },
                error:function(error){
                console.log(error);
                }
            });
            console.log('No Duplicate');
          }   
        }
    });*/
}

function clickSeleccionarProv(seleccion){
    $("#txtSeleccionProveedor").val(seleccion);
}

function clickSeleccionarProvEdit(seleccion){
    $("#txtSeleccionProveedorEdit").val(seleccion);
}

$(document).on('change', '.cantidadProducto,.precioProducto',  function(){
    let value_id = $(this).attr('id');
    let id = value_id.split('_');
    
    let cantidad = $("#txtCantidadCompuesta_" + id[1]).val(); 
    let precio = $("#txtCosto_" + id[1]).val(); 
    
    let total = cantidad * precio;
    $("#txtTotalCosto" + id[1]).val(total.toFixed(2));

    getTotales();
});

$(document).on('change', '.cantidadProductoEdit,.precioProductoEdit',  function(){

    let value_id = $(this).attr('id');
    let id = value_id.split('_');

    let cantidad = $("#txtCantidadCompuestaEdit_" + id[1]).val(); 
    let precio = $("#txtCostoEdit_" + id[1]).val(); 
    
    let total = cantidad * precio;
    $("#txtTotalCostoEdit" + id[1]).val(total.toFixed(2)); 

    getTotalesEdit();
});

$(document).on('change', '.cantidadProductoAdicionales,.precioProductoAdicionales',  function(){
    
    let value_id = $(this).attr('id');
    let id = value_id.split('_');

    let cantidad = $("#AtxtCantidadCompuestaAdicionales_" + id[1]).val(); 
    let precio = $("#AtxtCostoAdicionales_" + id[1]).val(); 
    
    let total = cantidad * precio;
    $("#txtTotalCostoAdicionales" + id[1]).val(total.toFixed(2)); 

    getTotalesAdicionales();
});

$(document).on('change', '.cantidadProductoAdicionalesEdit,.precioProductoAdicionalesEdit',  function(){
    
    let value_id = $(this).attr('id');
    let id = value_id.split('_');

    let cantidad = $("#AtxtCantidadCompuestaAdicionalesEdit_" + id[1]).val(); 
    let precio = $("#AtxtCostoAdicionalesEdit_" + id[1]).val(); 
    
    let total = cantidad * precio;
    $("#txtTotalCostoAdicionalesEdit" + id[1]).val(total.toFixed(2)); 

    getTotalesAdicionalesEdit();
});

$(document).on('change', '.precioGastoF, .porcentajeGastoF',  function(){
  let value_id = $(this).attr('id');
  let id = value_id.split('_');
  
  let precio = $("#BtxtCostoGastoF_" + id[1]).val(); 
  let porcentaje = $("#AtxtGastoFPorcentaje_" + id[1]).val(); 

  let total = (precio * porcentaje)/100;
  $("#txtTotalCostoGastoF" + id[1]).val(total.toFixed(2));

  getTotalesGF();
});

$(document).on('change', '.precioGastoFEdit, .porcentajeGastoFEdit',  function(){
  let value_id = $(this).attr('id');
  let id = value_id.split('_');
  
  let precio = $("#BtxtCostoGastoFEdit_" + id[1]).val(); 
  let porcentaje = $("#AtxtGastoFPorcentajeEdit_" + id[1]).val(); 

  let total = (precio * porcentaje)/100;
  $("#txtTotalCostoGastoFEdit" + id[1]).val(total.toFixed(2));

  getTotalesGFEdit();
});

let contadorAgregar = 2;
function agregarFila() {

    let productos = document.querySelectorAll('.contabilizarProductos')

        productos.forEach((item) => {
          console.log(item.value);
          /*  if(item.value == id){
                validadorProductos = 1;
            }*/
        });

    /*    if(validadorProductos > 0){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "No puedes agregar el mismo producto.",
            });
            validadorProductos = 0;
            return;
        }*/

    var table = document.getElementById("tablaprueba");
    var rowCount = table.rows.length;
    document.getElementById("tablaprueba").insertRow(-1).innerHTML =
      `<tr>
        <td>
            <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd" data-click="cmbProductos${contadorAgregar}" name="addProd" width="20px" height="20px" style=" position: relative;">
        </td>
        <td>
            <input class="contabilizarProductos" name="txtProductos${contadorAgregar}" id="txtProductos${contadorAgregar}" type="hidden" readonly>
            <input type="text" class="form-control" name="cmbProductos${contadorAgregar}" id="cmbProductos${contadorAgregar}" data-toggle="modal" data-target="#agregar_Producto" 
            placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarProd(${contadorAgregar})" />
        </td>
      <td>
        <div class="row">
          <div class="col-lg-6">
            <input class="form-control decimal cantidadProducto" type="text" name="txtCantidadCompuesta_${contadorAgregar}" id="txtCantidadCompuesta_${contadorAgregar}" min="0" maxlength="12" placeholder="Ej. 10">
          </div>
          <div class="col-lg-6">
            <label for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSat(${contadorAgregar},1)"><span id="lblUnidadMedida${contadorAgregar}"> </span></label>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-12">
                <label  for="usr"><span id="lblCosto${contadorAgregar}" hidden> </span>
                <div class="row">
                    <div class="col-lg-8"><input class="form-control precioProducto" type="text" id="txtCosto_${contadorAgregar}" name="txtCosto_${contadorAgregar}" maxlength="12" required></div>                                    
                    <div class="col-lg-4"><i><img class="btnEdit" onclick="cargarCostos(${contadorAgregar},1);" src="../../../../img/timdesk/ver.svg"></i></div> 
                </div>
                </label>
              </div> 
            </div>
          </div>
        </div>
      </td>
      <td>
        <input  name="txtProveedores${contadorAgregar}" id="txtProveedores${contadorAgregar}" type="hidden" value='0' readonly>
        <input type="text" class="form-control" name="cmbProveedores${contadorAgregar}" id="cmbProveedores${contadorAgregar}" data-toggle="modal" data-target="#agregar_Proveedores" 
        placeholder="Seleccione un proveedor..." readonly required="" onclick="clickSeleccionarProv(${contadorAgregar})" value='S/P - Sin Proveedor'>
        <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
        src="../../../../img/timdesk/alerta.svg" width=30px
        title="Seleccione un proveedor" readonly>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-12">
                <label  for="usr"><span id="lblCosto${contadorAgregar}" hidden> </span><input class="form-control getTotales" type="text" id="txtTotalCosto${contadorAgregar}"  required readonly></label>
              </div>  
            </div>  
          </div>
        </div>
      </td>
      <td>
        <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" id="btnEliminar_${contadorAgregar}" onclick="eliminarCompTemp(this);"></i>
      </td>
      </tr>`;

    contadorAgregar++;
   
}

let contadorEditar;
function agregarFilaEdit() {
    var table = document.getElementById("tablapruebaedit");
    var rowCount = table.rows.length;
    document.getElementById("tablapruebaedit").insertRow(-1).innerHTML =
      `<tr>
        <td>
            <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd" data-click="cmbProductosEdit${contadorAgregar}" name="addProd" width="20px" height="20px" style=" position: relative;">
        </td>
        <td>
            <input class="contabilizarProductosEdit" name="txtProductosEdit${contadorEditar}" id="txtProductosEdit${contadorEditar}" type="hidden" readonly>
            <input type="text" class="form-control" name="cmbProductosEdit${contadorEditar}" id="cmbProductosEdit${contadorEditar}" data-toggle="modal" data-target="#editar_Producto" 
            placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarProdEdit(${contadorEditar})" />
        </td>
      <td>
        <div class="row">
          <div class="col-lg-6">
            <input class="form-control decimal cantidadProductoEdit" type="text" name="txtCantidadCompuestaEdit_${contadorEditar}" id="txtCantidadCompuestaEdit_${contadorEditar}" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(${rowCount})">
          </div>
          <div class="col-lg-6">
            <label for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatEdit(${contadorEditar},2)"><span id="lblUnidadMedidaEdit${contadorEditar}"> </span></label>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-12">
                <label  for="usr"><span id="lblCostoEdit${contadorEditar}" hidden> </span>
                <div class="row">
                  <div class="col-lg-8"><input class="form-control precioProductoEdit" type="text" id="txtCostoEdit_${contadorEditar}" name="txtCostoEdit_${contadorEditar}" onkeyup="guardarCostoProdCompTemp(${rowCount})" required></label></div> 
                  <div class="col-lg-4"><i><img class="btnEdit" onclick="cargarCostos(${contadorEditar},2);" src="../../../../img/timdesk/ver.svg"></i></div> 
                </div>
              </div> 
            </div>
          </div>
        </div>
      </td>
      <td>
        <input  name="txtProveedoresEdit_${contadorEditar}" id="txtProveedoresEdit_${contadorEditar}" type="hidden" value="0" readonly>
        <input type="text" class="form-control" name="cmbProveedoresEdit${contadorEditar}" id="cmbProveedoresEdit${contadorEditar}" data-toggle="modal" data-target="#editar_Proveedores" 
        placeholder="Seleccione un proveedor..." readonly required="" onclick="clickSeleccionarProvEdit(${contadorEditar})" value="S/P - Sin Proveedor">
        <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
        src="../../../../img/timdesk/alerta.svg" width=30px
        title="Seleccione un proveedor" readonly>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-12">
                <label  for="usr"><span id="lblCostoEdit${contadorEditar}" hidden> </span><input class="form-control getTotalesEdit" type="text" id="txtTotalCostoEdit${contadorEditar}"  required readonly></label>
              </div>  
            </div>  
          </div>
        </div>
      </td>
      <td>
        <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" id="btnEliminarEditar_${contadorEditar}" onclick="eliminarCompTempEdit(this);"></i>
      </td>
      </tr>`;
      contadorEditar++;
   
}

let contadorAdicional = 2;
function agregarFilaAdicionales() {
    var table = document.getElementById("tablaprueba_adicionales");
    var rowCount = table.rows.length;
    document.getElementById("tablaprueba_adicionales").insertRow(-1).innerHTML =
      `<tr>
        <td>
            <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd" data-click="cmbProductosAdicionales${contadorAgregar}" name="addProd" width="20px" height="20px" style=" position: relative;">
        </td>
        <td>
            <input class="contabilizarProductosAdicionales" name="AtxtProductosAdicionales${contadorAdicional}" id="AtxtProductosAdicionales${contadorAdicional}" type="hidden" readonly>
            <input type="text" class="form-control" name="cmbProductosAdicionales${contadorAdicional}" id="cmbProductosAdicionales${contadorAdicional}" data-toggle="modal" data-target="#agregar_Producto_Adicionales" 
            placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarProdAdicionales(${contadorAdicional})" />
        </td>
      <td>
        <div class="row">
          <div class="col-lg-6">
            <input class="form-control decimal cantidadProductoAdicionales" type="text" name="AtxtCantidadCompuestaAdicionales_${contadorAdicional}" id="AtxtCantidadCompuestaAdicionales_${contadorAdicional}" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10" onkeyup="guardarCantidadProdCompTemp(${rowCount})">
          </div>
          <div class="col-lg-6">
            <label for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatAdicionales(${contadorAdicional},3)"><span id="lblUnidadMedidaAdicionales${contadorAdicional}"> </span></label>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-12">
                <label  for="usr"><span id="lblCostoAdicionales${contadorAdicional}" hidden> </span>
                  <div class="row">
                    <div class="col-lg-8"><input class="form-control precioProductoAdicionales" type="text" id="AtxtCostoAdicionales_${contadorAdicional}" name="AtxtCostoAdicionales_${contadorAdicional}" maxlength="12" required></div>
                    <div class="col-lg-4"><i><img class="btnEdit" onclick="cargarCostosAdicionales(${contadorAdicional},3);" src="../../../../img/timdesk/ver.svg"></i></div> 
                  </div>
                </label>
              </div> 
            </div>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-12">
                <label  for="usr"><span id="lblCostoAdicionales${contadorAdicional}" hidden> </span><input class="form-control getTotalAdicionales" type="text" id="txtTotalCostoAdicionales${contadorAdicional}"  required readonly></label>
              </div>  
            </div>  
          </div>
        </div>
      </td>
      <td>
        <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" id="btnEliminarAdicionales_${contadorAdicional}" onclick="eliminarCompTempAdicionales(this);"></i>
      </td>
      </tr>`;

    contadorAdicional++;
   
}

let contadorAdicionalEdit;
function agregarFilaAdicionalesEdit() {

    var table = document.getElementById("tablaprueba_adicionales_edit");
    var rowCount = table.rows.length;
    document.getElementById("tablaprueba_adicionales_edit").insertRow(-1).innerHTML =
      `<tr>
        <td>
            <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd" data-click="cmbProductosAdicionalesEdit${contadorAgregar}" name="addProd" width="20px" height="20px" style=" position: relative;">
        </td>
        <td>
            <input class="contabilizarProductosAdicionalesEdit" name="AtxtProductosAdicionalesEdit${contadorAdicionalEdit}" id="AtxtProductosAdicionalesEdit${contadorAdicionalEdit}" type="hidden" readonly>
            <input type="text" class="form-control" name="cmbProductosAdicionalesEdit${contadorAdicionalEdit}" id="cmbProductosAdicionalesEdit${contadorAdicionalEdit}" data-toggle="modal" data-target="#editar_Producto_Adicionales" 
            placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarProdAdicionalesEdit(${contadorAdicionalEdit})" />
        </td>
      <td>
        <div class="row">
          <div class="col-lg-6">
            <input class="form-control decimal cantidadProductoAdicionalesEdit" type="text" name="AtxtCantidadCompuestaAdicionalesEdit_${contadorAdicionalEdit}" id="AtxtCantidadCompuestaAdicionalesEdit_${contadorAdicionalEdit}" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Ej. 10">
          </div>
          <div class="col-lg-6">
            <label for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatAdicionalesEdit(${contadorAdicionalEdit},4)"><span id="lblUnidadMedidaAdicionalEdit${contadorAdicionalEdit}"> </span></label>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-12">
                <label  for="usr"><span id="lblCostoAdicionalesEdit${contadorAdicionalEdit}" hidden> </span>
                  <div class="row">
                    <div class="col-lg-8"><input class="form-control precioProductoAdicionalesEdit" type="text" id="AtxtCostoAdicionalesEdit_${contadorAdicionalEdit}" name="AtxtCostoAdicionalesEdit_${contadorAdicionalEdit}" maxlength="12" required></div> 
                    <div class="col-lg-4"><i><img class="btnEdit" onclick="cargarCostosAdicionales(${contadorAdicionalEdit},4);" src="../../../../img/timdesk/ver.svg"></i></div> 
                  </div>
                </label>
              </div> 
            </div>
          </div>
        </div>
      </td>
      <td>
        <div class="row">
          <div class="col-lg-12 input-group">
            <div class="row">
              <div class="col-lg-12">
                <label  for="usr"><span id="lblCostoAdicionalesEdit${contadorAdicionalEdit}" hidden> </span><input class="form-control getTotalAdicionalesEdit" type="text" id="txtTotalCostoAdicionalesEdit${contadorAdicionalEdit}"  required readonly></label>
              </div>  
            </div>  
          </div>
        </div>
      </td>
      <td>
        <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" id="btnEliminarAdicionalesEdit_${contadorAdicionalEdit}" onclick="eliminarCompTempAdicionalesEdit(this);"></i>
      </td>
      </tr>`;

    contadorAdicionalEdit++;
   
}

let contadorGastoF = 2;
function agregarFilaGastoF() {
  var table = document.getElementById("tablaprueba_gastosFijos");
  var rowCount = table.rows.length;
  document.getElementById("tablaprueba_gastosFijos").insertRow(-1).innerHTML =
    `<tr>
      <td>
          <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar gasto fijo" id="addProd" data-click="cmbGastosF${contadorGastoF}" name="addProd" width="20px" height="20px" style=" position: relative;">
      </td>
      <td>
          <input class="contabilizarGastosF" name="txtGastoF${contadorGastoF}" id="txtGastoF${contadorGastoF}" type="hidden" readonly>
          <input type="text" class="form-control" name="cmbGastosF${contadorGastoF}" id="cmbGastosF${contadorGastoF}" data-toggle="modal" data-target="#agregar_GastoF" 
          placeholder="Seleccione un Gasto fijo..." readonly onclick="clickSeleccionarProdGastoF(${contadorGastoF})">
      </td>
      <td>
        <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatGastoF(${contadorGastoF}, 1)"><span id="lblUnidadMedidaGastoF${contadorGastoF}"> </span></label>
      </td>
      <td>
        <div class="col-lg-12"><input class="form-control precioGastoF" type="text" id="BtxtCostoGastoF_${contadorGastoF}"  name="BtxtCostoGastoF_${contadorGastoF}" maxlength="12"></div>                                    
      </td>
      <td>
        <input type="text" class="form-control utilidadPorcentajeClass porcentajeGastoF" id="AtxtGastoFPorcentaje_${contadorGastoF}" name="AtxtGastoFPorcentaje_${contadorGastoF}" maxlength="5" value="0.00" style="width:200px;">  
      </td>
      <td>
        <label  for="usr"><span id="lblCostoGastoF${contadorGastoF}" hidden> </span><input class="form-control getTotalesGF" type="text" id="txtTotalCostoGastoF${contadorGastoF}" readonly></label>      
      </td>
      <td>
        <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" id="btnEliminar_${contadorGastoF}" onclick="eliminarGastoF(this);"></i>
      </td>
    </tr>`;

    contadorGastoF++;
}

let contadorGastoFEdit = 2;
function agregarFilaGastosFEdit() {
  var table = document.getElementById("tablaprueba_gastos_fijos_edit");
  var rowCount = table.rows.length;
  document.getElementById("tablaprueba_gastos_fijos_edit").insertRow(-1).innerHTML =
    `<tr>
      <td>
          <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar gasto fijo" id="addProd" data-click="cmbGastoFEdit${contadorGastoF}" name="addProd" width="20px" height="20px" style=" position: relative;">
      </td>
      <td>
          <input class="contabilizarGastosFEdit" name="txtGastoFEdit${contadorGastoFEdit}" id="txtGastoFEdit${contadorGastoFEdit}" type="hidden" readonly>
          <input type="text" class="form-control" name="cmbGastoFEdit${contadorGastoFEdit}" id="cmbGastoFEdit${contadorGastoFEdit}" data-toggle="modal" data-target="#agregar_GastoFEdit" 
          placeholder="Seleccione un Gasto fijo..." readonly onclick="clickSeleccionarProdGastoFEdit(${contadorGastoFEdit})">
      </td>
      <td>
        <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatGastoFEdit(${contadorGastoFEdit}, 1)"><span id="lblUnidadMedidaGastoFEdit${contadorGastoFEdit}"> </span></label>
      </td>
      <td>
        <div class="col-lg-12"><input class="form-control precioGastoFEdit" type="text" id="BtxtCostoGastoFEdit_${contadorGastoFEdit}"  name="BtxtCostoGastoFEdit_${contadorGastoFEdit}" maxlength="12"></div>                                    
      </td>
      <td>
        <input type="text" class="form-control utilidadPorcentajeClass porcentajeGastoFEdit" id="AtxtGastoFPorcentajeEdit_${contadorGastoFEdit}" name="AtxtGastoFPorcentajeEdit_${contadorGastoFEdit}" maxlength="12" value="0.00" style="width:200px;">  
      </td>
      <td>
        <label  for="usr"><span id="lblCostoGastoF${contadorGastoFEdit}" hidden> </span><input class="form-control getTotalesGFEdit" type="text" id="txtTotalCostoGastoFEdit${contadorGastoFEdit}" readonly></label>      
      </td>
      <td>
        <i><img class=\"btnEdit\" src=\"../../../../img/timdesk/delete.svg\" id="btnEliminarGFEdit_${contadorGastoFEdit}" onclick="eliminarGastoFEdit(this);"></i>
      </td>
    </tr>`;

    contadorGastoFEdit++;
}

$(document).on('click', '#btGuardarCostos',  function(){

    var data = {};
    let contadorFilas = 0, contadorNoexiste = 0, contadorEle = 0;
    $.each($("#formAgregarCostos").serializeArray(), function (i, element) {
      data[element.name] = element.value;

      if(element.name.substring(0, 12) == 'txtProductos'){
        contadorFilas++;
        //console.log("elem '" + element.value + "'");
        if(element.value.trim() == '' || element.value.trim() == null){
          contadorNoexiste++;
          //alert("suma ini");
        }
        if(element.value.trim() != '' && element.value.trim() != null){
          contadorEle++;
          //alert("suma");
        }

      }

    });
//alert(contadorFilas + " - " +contadorNoexiste + " - " + contadorEle);
    if(contadorFilas == 0 || (contadorNoexiste > 0 && contadorEle == 0)){
        Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Necesitas ingresar por lo menos un producto de componentes.",
          });
        return;
    }
    //console.log(data);
    //return;
    $("#btGuardarCostos").prop("disabled", true);

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "guardar_Costos",
        datos: data,
      },
      dataType: "json",
      success: function (respuesta) {

        //console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Costos agregados correctamente",
          });

          $('#tblCostos').DataTable().ajax.reload();
          $("#btGuardarCostos").prop("disabled", false);
          $("#costosModal").modal('hide');
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrio un error, intentalo nuevamente.",
          });
          $("#btGuardarCostos").prop("disabled", false);
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
          msg: "Ocurrio un error, intentalo nuevamente.",
        });
        $("#btGuardarCostos").prop("disabled", false);
      },
    });
});


$(document).on('click', '#btEditarCostos',  function(){

    var data = {};

    let contadorFilas = 0, contadorNoexiste = 0, contadorEle = 0;
    $.each($("#formEditarCostos").serializeArray(), function (i, element) {
      data[element.name] = element.value;
      
      if(element.name.substring(0, 12) == 'txtProductos'){
        contadorFilas++;
        //console.log("elem '" + element.value + "'");
        if(element.value.trim() == '' || element.value.trim() == null){
          contadorNoexiste++;
          //alert("suma ini");
        }
        if(element.value.trim() != '' && element.value.trim() != null){
          contadorEle++;
          //alert("suma");
        }

      }

    });
    
//alert(contadorFilas + " - " +contadorNoexiste + " - " + contadorEle);
    if(contadorFilas == 0 || (contadorNoexiste > 0 && contadorEle == 0)){
        Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Necesitas ingresar por lo menos un producto de componentes.",
          });
        return;
    }

    $("#btEditarCostos").prop("disabled", true);

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "editar_Costos",
        datos: data,
      },
      dataType: "json",
      success: function (respuesta) {

        //console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Costos modificados correctamente",
          });

          $('#tblCostos').DataTable().ajax.reload();
          $("#btEditarCostos").prop("disabled", false);
          $("#editarCostosModal").modal('hide');
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrio un error, intentalo nuevamente.",
          });
          $("#btEditarCostos").prop("disabled", false);
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
          msg: "Ocurrio un error, intentalo nuevamente.",
        });
        $("#btGuardarCostos").prop("disabled", false);
      },
    });
});


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


function cargarCMBProductosEdit(pkProducto){

        var topButtons = [];

        topButtons.push({
        text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> </span>',
        className: "btn-custom--white-dark",
        action: function () {

          selectProductosAgr.destroy();
          selectCatProductoAgr.destroy();
          selectMarcaProductoAgr.destroy();
          cargarCMBTipo("", "cmbTipoProducto",1);
          cargarCMBCategoria("","cmbCategoriaProductoAAA");
          cargarCMBMarca("", "cmbMarcaProductoAAA");

          $("#TipoProductoAlta").val(3);

          selectProductosAgr =  new SlimSelect({
            select: "#cmbTipoProducto",
            deselectLabel: '<span class="">✖</span>',
          });

          selectCatProductoAgr = new SlimSelect({
            select: "#cmbCategoriaProductoAAA",
            deselectLabel: '<span class="">✖</span>',
          });

          selectMarcaProductoAgr = new SlimSelect({
            select: "#cmbMarcaProductoAAA",
            deselectLabel: '<span class="">✖</span>',
          });

          actualizarComboProductos = 0;
          $("#agregar_Productos_Modal").modal('show');  
          $('#agregar_Productos_Modal').modal({backdrop: 'static', keyboard: false});

         /* cargarProductos();

          $("#areaDatos").html("");
          $("#areaCompuesto").html("");
          $("#costosModal").modal('show');*/
        },
      });
    

        $("#tblListadoProductosEdit").DataTable().destroy();
        $("#tblListadoProductosEdit").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_cmb_productos", data:pkProducto, modo: 2},
        },
        "columns":[
            { "data": "Id" },
            { "data": "ClaveInterna" },
            { "data": "Nombre" },
            { "data": "Estatus" },

        ],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-0 col-md-0 p-0"><"col-sm-12 col-md-12 p-0"p>>>`,
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
          buttons: topButtons,
        },
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 3, visible: false },
            ],
            responsive: true
        });

    //    _global.contadorCompuesto = 1;
    //}  
}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
let validadorProductosEdit = 0;
function obtenerIdProductoSeleccionarEdit(id, claveInterna, nombre, unidadMedida, costo, moneda, fkMoneda) {

            let productos = document.querySelectorAll('.contabilizarProductosEdit')

            productos.forEach((item) => {
              //console.log(item.value);
                if(item.value == id){
                    validadorProductosEdit = 1;
                }
            });

            if(validadorProductosEdit > 0){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "No puedes agregar el mismo producto.",
                });
                validadorProductosEdit = 0;
                return;
            }

            var seleccion = $("#txtSeleccionEdit").val();

            document.getElementById('txtProductosEdit'+seleccion).value = id;
            document.getElementById('txtCantidadCompuestaEdit_'+seleccion).value = '1';
            if(claveInterna == ''){
                document.getElementById('cmbProductosEdit'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbProductosEdit'+seleccion).value = claveInterna + ' - ' + nombre;
            }
            if(unidadMedida == ''){
                $('#lblUnidadMedidaEdit'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedidaEdit'+seleccion).html(unidadMedida);
            }

            //$('#lblCosto'+seleccion).html(costo +' '+ moneda);
            $('#txtCostoEdit_'+seleccion).val(costo);
    

            var cantidad = $("#txtCantidadCompuestaEdit_"+seleccion).val(); 

            let total = (cantidad * costo).toFixed(2);
            $("#txtTotalCostoEdit"+seleccion).val(total);
            getTotalesEdit();

}


function clickSeleccionarProdEdit(seleccion){
    $("#txtSeleccionEdit").val(seleccion);
}

function eliminarCompTemp(elemento) {

    //Eliminar
    event.preventDefault(); 
    $("#"+elemento.id).closest('tr').remove();
    /*let id = value_id.split('_');


    let cantidad = $("#txtCantidadCompuestaAdicionales_" + id[1]).val(); */
    getTotales();
}

function eliminarCompTempEdit(elemento) {

    //Eliminar
    event.preventDefault(); 
    $("#"+elemento.id).closest('tr').remove();
    /*let id = value_id.split('_');


    let cantidad = $("#txtCantidadCompuestaAdicionales_" + id[1]).val(); */
    getTotalesEdit();
}

function clickSeleccionarProdAdicionales(seleccion){
    $("#txtSeleccionAdicionales").val(seleccion);
}

function clickSeleccionarProdAdicionalesEdit(seleccion){
    $("#txtSeleccionAdicionalesEdit").val(seleccion);
}

function cargarCMBProductosAdicionales(pkProducto){

        var topButtons = [];

        topButtons.push({
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> </span>',
            className: "btn-custom--white-dark",
            action: function () {

              selectProductosAgr.destroy();
              selectCatProductoAgr.destroy();
              selectMarcaProductoAgr.destroy();
              cargarCMBTipo("", "cmbTipoProducto",2);
              cargarCMBCategoria("","cmbCategoriaProductoAAA");
              cargarCMBMarca("", "cmbMarcaProductoAAA");

              $("#TipoProductoAlta").val(2);

              selectProductosAgr =  new SlimSelect({
                select: "#cmbTipoProducto",
                deselectLabel: '<span class="">✖</span>',
              });

              selectProductosAgr.set(7);

              selectCatProductoAgr = new SlimSelect({
                select: "#cmbCategoriaProductoAAA",
                deselectLabel: '<span class="">✖</span>',
              });

              selectMarcaProductoAgr = new SlimSelect({
                select: "#cmbMarcaProductoAAA",
                deselectLabel: '<span class="">✖</span>',
              });

              //$("#agregar_Productos_Modal").modal('show'); 
              actualizarComboProductos = 0;
              $('#agregar_Productos_Modal').modal({backdrop: 'static', keyboard: false});
              

             /* cargarProductos();

              $("#areaDatos").html("");
              $("#areaCompuesto").html("");
              $("#costosModal").modal('show');*/
            },
          });
    

        $("#tblListadoProductosAdicionales").DataTable().destroy();
        $("#tblListadoProductosAdicionales").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_cmb_productos", data:pkProducto, modo: 3},
        },
        "columns":[
            { "data": "Id" },
            { "data": "ClaveInterna" },
            { "data": "Nombre" },
            { "data": "Estatus" },

        ],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-0 col-md-0 p-0"><"col-sm-12 col-md-12 p-0"p>>>`,
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
          buttons: topButtons,
        },
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 3, visible: false },
            ],
            responsive: true
        });

}

function cargarCMBProductosAdicionalesEdit(pkProducto){
        
        var topButtons = [];

        topButtons.push({
            text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> </span>',
            className: "btn-custom--white-dark",
            action: function () {

              selectProductosAgr.destroy();
              selectCatProductoAgr.destroy();
              selectMarcaProductoAgr.destroy();
              cargarCMBTipo("", "cmbTipoProducto",2);
              cargarCMBCategoria("","cmbCategoriaProductoAAA");
              cargarCMBMarca("", "cmbMarcaProductoAAA");

              $("#TipoProductoAlta").val(4);

              selectProductosAgr =  new SlimSelect({
                select: "#cmbTipoProducto",
                deselectLabel: '<span class="">✖</span>',
              });

              selectCatProductoAgr = new SlimSelect({
                select: "#cmbCategoriaProductoAAA",
                deselectLabel: '<span class="">✖</span>',
              });

              selectMarcaProductoAgr = new SlimSelect({
                select: "#cmbMarcaProductoAAA",
                deselectLabel: '<span class="">✖</span>',
              });

              //$("#agregar_Productos_Modal").modal('show');  
              actualizarComboProductos = 0;
              selectProductosAgr.set(1);
              $('#agregar_Productos_Modal').modal({backdrop: 'static', keyboard: false});

             /* cargarProductos();

              $("#areaDatos").html("");
              $("#areaCompuesto").html("");
              $("#costosModal").modal('show');*/
            },
          });
    

        $("#tblListadoProductosAdicionalesEdit").DataTable().destroy();
        $("#tblListadoProductosAdicionalesEdit").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_cmb_productos", data:pkProducto, modo: 4},
        },
        "columns":[
            { "data": "Id" },
            { "data": "ClaveInterna" },
            { "data": "Nombre" },
            { "data": "Estatus" },

        ],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
        <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-0 col-md-0 p-0"><"col-sm-12 col-md-12 p-0"p>>>`,
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
          buttons: topButtons,
        },
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: false, targets: 3, visible: false },
            ],
            responsive: true
        });

    //    _global.contadorCompuesto = 1;
    //}  
}

function cargarCMBGastosF(pkProducto){

  var topButtons = [];

  topButtons.push({
      text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> </span>',
      className: "btn-custom--white-dark",
      action: function () {

        selectProductosAgr.destroy();
        selectCatProductoAgr.destroy();
        selectMarcaProductoAgr.destroy();
        cargarCMBTipo("", "cmbTipoProducto",3);
        cargarCMBCategoria("","cmbCategoriaProductoAAA");
        cargarCMBMarca("", "cmbMarcaProductoAAA");

        $("#TipoProductoAlta").val(5);

        selectProductosAgr =  new SlimSelect({
          select: "#cmbTipoProducto",
          deselectLabel: '<span class="">✖</span>',
        });

        selectProductosAgr.set(10);

        selectCatProductoAgr = new SlimSelect({
          select: "#cmbCategoriaProductoAAA",
          deselectLabel: '<span class="">✖</span>',
        });

        selectMarcaProductoAgr = new SlimSelect({
          select: "#cmbMarcaProductoAAA",
          deselectLabel: '<span class="">✖</span>',
        });

        actualizarComboProductos = 0;
        $('#agregar_Productos_Modal').modal('show');
        

       /* cargarProductos();

        $("#areaDatos").html("");
        $("#areaCompuesto").html("");
        $("#costosModal").modal('show');*/
      },
    });


  $("#tblListadoGastosF").DataTable().destroy();
  $("#tblListadoGastosF").dataTable({
      "lengthChange": false,
      "pageLength": 15,
      //"paging": true,
      "info": false,
      "pagingType": "full_numbers",
      "ajax": {
          url:"../../php/funciones.php",
          data:{clase:"get_data", funcion:"get_cmb_productos", data:pkProducto, modo: 5},
  },
  "columns":[
      { "data": "Id" },
      { "data": "ClaveInterna" },
      { "data": "Nombre" },
      { "data": "Estatus" },

  ],
  dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
  <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-0 col-md-0 p-0"><"col-sm-12 col-md-12 p-0"p>>>`,
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
    buttons: topButtons,
  },
  "language": setFormatDatatables(),
      columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 3, visible: false },
      ],
      responsive: true
  });

}

function cargarCMBGastosFEdit(pkProducto){

  var topButtons = [];

  topButtons.push({
      text: '<span class="d-flex align-items-center"><img src="../../../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> </span>',
      className: "btn-custom--white-dark",
      action: function () {

        selectProductosAgr.destroy();
        selectCatProductoAgr.destroy();
        selectMarcaProductoAgr.destroy();
        cargarCMBTipo("", "cmbTipoProducto",3);
        cargarCMBCategoria("","cmbCategoriaProductoAAA");
        cargarCMBMarca("", "cmbMarcaProductoAAA");

        $("#TipoProductoAlta").val(5);

        selectProductosAgr =  new SlimSelect({
          select: "#cmbTipoProducto",
          deselectLabel: '<span class="">✖</span>',
        });

        selectProductosAgr.set(10);

        selectCatProductoAgr = new SlimSelect({
          select: "#cmbCategoriaProductoAAA",
          deselectLabel: '<span class="">✖</span>',
        });

        selectMarcaProductoAgr = new SlimSelect({
          select: "#cmbMarcaProductoAAA",
          deselectLabel: '<span class="">✖</span>',
        });

        actualizarComboProductos = 0;
        $('#agregar_Productos_Modal').modal('show');
        

       /* cargarProductos();

        $("#areaDatos").html("");
        $("#areaCompuesto").html("");
        $("#costosModal").modal('show');*/
      },
    });


  $("#tblListadoGastosFEdit").DataTable().destroy();
  $("#tblListadoGastosFEdit").dataTable({
      "lengthChange": false,
      "pageLength": 15,
      //"paging": true,
      "info": false,
      "pagingType": "full_numbers",
      "ajax": {
          url:"../../php/funciones.php",
          data:{clase:"get_data", funcion:"get_cmb_productos", data:pkProducto, modo: 6},
  },
  "columns":[
      { "data": "Id" },
      { "data": "ClaveInterna" },
      { "data": "Nombre" },
      { "data": "Estatus" },

  ],
  dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
  <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-0 col-md-0 p-0"><"col-sm-12 col-md-12 p-0"p>>>`,
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
    buttons: topButtons,
  },
  "language": setFormatDatatables(),
      columnDefs: [
      { orderable: false, targets: 0, visible: false },
      { orderable: false, targets: 3, visible: false },
      ],
      responsive: true
  });

}

function clickSeleccionarProdGastoF(seleccion){
  $("#txtSeleccionGastoF").val(seleccion);
}

function clickSeleccionarProdGastoFEdit(seleccion){
  $("#txtSeleccionGastoFEdit").val(seleccion);
}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
let validadorProductosAdicionales = 0;
function obtenerIdProductoSeleccionarAdicionales(id, claveInterna, nombre, unidadMedida, costo, moneda, fkMoneda) {

            let productos = document.querySelectorAll('.contabilizarProductosAdicionales')

            productos.forEach((item) => {
              //console.log(item.value);
                if(item.value == id){
                    validadorProductosAdicionales = 1;
                }
            });

            if(validadorProductosAdicionales > 0){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "No puedes agregar el mismo producto.",
                });
                validadorProductosAdicionales = 0;
                return;
            }

            var seleccion = $("#txtSeleccionAdicionales").val();

            document.getElementById('AtxtProductosAdicionales'+seleccion).value = id;
            document.getElementById('AtxtCantidadCompuestaAdicionales_'+seleccion).value = '1';
            if(claveInterna == ''){
                document.getElementById('cmbProductosAdicionales'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbProductosAdicionales'+seleccion).value = claveInterna + ' - ' + nombre;
            }
            
            if(unidadMedida == ''){
                $('#lblUnidadMedidaAdicionales'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedidaAdicionales'+seleccion).html(unidadMedida);
            }

            //$('#lblCosto'+seleccion).html(costo +' '+ moneda);
            $('#AtxtCostoAdicionales_'+seleccion).val(costo);
    

            var cantidad = $("#AtxtCantidadCompuestaAdicionales_"+seleccion).val(); 

            var total = (cantidad * costo).toFixed(2);
            $("#txtTotalCostoAdicionales"+seleccion).val(total);

            getTotalesAdicionales();

}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
let validadorProductosAdicionalesEdit = 0;
function obtenerIdProductoSeleccionarAdicionalesEdit(id, claveInterna, nombre, unidadMedida, costo, moneda, fkMoneda) {

            let productos = document.querySelectorAll('.contabilizarProductosAdicionalesEdit')

            productos.forEach((item) => {
              //console.log(item.value);
                if(item.value == id){
                    validadorProductosAdicionalesEdit = 1;
                }
            });

            if(validadorProductosAdicionalesEdit > 0){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "No puedes agregar el mismo producto.",
                });
                validadorProductosAdicionalesEdit = 0;
                return;
            }

            var seleccion = $("#txtSeleccionAdicionalesEdit").val();

            document.getElementById('AtxtProductosAdicionalesEdit'+seleccion).value = id;
            document.getElementById('AtxtCantidadCompuestaAdicionalesEdit_'+seleccion).value = '1';
            if(claveInterna == ''){
                document.getElementById('cmbProductosAdicionalesEdit'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbProductosAdicionalesEdit'+seleccion).value = claveInterna + ' - ' + nombre;
            }

            if(unidadMedida == ''){
                $('#lblUnidadMedidaAdicionalEdit'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedidaAdicionalEdit'+seleccion).html(unidadMedida);
            }

            //$('#lblCosto'+seleccion).html(costo +' '+ moneda);
            $('#AtxtCostoAdicionalesEdit_'+seleccion).val(costo);
    

            var cantidad = $("#AtxtCantidadCompuestaAdicionalesEdit_"+seleccion).val(); 

            var total = (cantidad * costo).toFixed(2);
            $("#txtTotalCostoAdicionalesEdit"+seleccion).val(total);

            getTotalesAdicionalesEdit();

}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
let validadorGastosFijos = 0;
function obtenerIdGastoSeleccionar(id, claveInterna, nombre, unidadMedida, costo, moneda, fkMoneda) {

            let gastos = document.querySelectorAll('.contabilizarGastosF')

            gastos.forEach((item) => {
              //console.log(item.value);
                if(item.value == id){
                  validadorGastosFijos = 1;
                }
            });

            if(validadorGastosFijos > 0){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "No puedes agregar el mismo gasto.",
                });
                validadorGastosFijos = 0;
                return;
            }

            var seleccion = $("#txtSeleccionGastoF").val();

            document.getElementById('txtGastoF'+seleccion).value = id;
            if(claveInterna == ''){
                document.getElementById('cmbGastosF'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbGastosF'+seleccion).value = claveInterna + ' - ' + nombre;
            }
            
            if(unidadMedida == ''){
                $('#lblUnidadMedidaGastoF'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedidaGastoF'+seleccion).html(unidadMedida);
            }

            $('#BtxtCostoGastoF_'+seleccion).val(costo);
    
            $('#AtxtGastoFPorcentaje_'+seleccion).val(0);

            $("#txtTotalCostoGastoF"+seleccion).val(0);

            getTotalesGF();

}

let validadorGastosFijosEdit = 0;
function obtenerIdGastoSeleccionarEdit(id, claveInterna, nombre, unidadMedida, costo, moneda, fkMoneda) {

            let gastos = document.querySelectorAll('.contabilizarGastosFEdit')

            gastos.forEach((item) => {
              //console.log(item.value);
                if(item.value == id){
                  validadorGastosFijosEdit = 1;
                }
            });

            if(validadorGastosFijosEdit > 0){
                Lobibox.notify("error", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../../../img/timdesk/notificacion_error.svg",
                  msg: "No puedes agregar el mismo gasto.",
                });
                validadorGastosFijosEdit = 0;
                return;
            }

            var seleccion = $("#txtSeleccionGastoFEdit").val();

            document.getElementById('txtGastoFEdit'+seleccion).value = id;
            if(claveInterna == ''){
                document.getElementById('cmbGastoFEdit'+seleccion).value = nombre;
            }else{
                document.getElementById('cmbGastoFEdit'+seleccion).value = claveInterna + ' - ' + nombre;
            }
            
            if(unidadMedida == ''){
                $('#lblUnidadMedidaGastoFEdit'+seleccion).html('(Sin unidad de medida)');
            }else{
                $('#lblUnidadMedidaGastoFEdit'+seleccion).html(unidadMedida);
            }

            $('#BtxtCostoGastoFEdit_'+seleccion).val(costo);
            $('#AtxtGastoFPorcentajeEdit_'+seleccion).val(0);
    

            $("#txtTotalCostoGastoFEdit"+seleccion).val(0);

            getTotalesGFEdit()
}

function eliminarCompTempAdicionales(elemento) {
    
    
    //EliminarAdicionales_
    event.preventDefault(); 
    $("#"+elemento.id).closest('tr').remove();
    /*let id = value_id.split('_');


    let cantidad = $("#txtCantidadCompuestaAdicionales_" + id[1]).val(); */
    getTotalesAdicionales();
}

function eliminarCompTempAdicionalesEdit(elemento) {
    
    
    //EliminarAdicionales_
    event.preventDefault(); 
    $("#"+elemento.id).closest('tr').remove();
    /*let id = value_id.split('_');


    let cantidad = $("#txtCantidadCompuestaAdicionales_" + id[1]).val(); */
    getTotalesAdicionalesEdit();
}

function eliminarGastoF(elemento) {

  //Eliminar
  event.preventDefault(); 
  $("#"+elemento.id).closest('tr').remove();
  /*let id = value_id.split('_');


  let cantidad = $("#txtCantidadCompuestaAdicionales_" + id[1]).val(); */
  getTotalesGF();
}

function eliminarGastoFEdit(elemento) {

  //Eliminar
  event.preventDefault(); 
  $("#"+elemento.id).closest('tr').remove();
  /*let id = value_id.split('_');


  let cantidad = $("#txtCantidadCompuestaAdicionales_" + id[1]).val(); */
  getTotalesGFEdit();
}

function getTotales(){
  let costos = document.querySelectorAll('.getTotales')

  let totalComponentes =  0.00;
  costos.forEach((item) => {
    //console.log(item.value);
    if(item.value.trim() != ''){
      totalComponentes = totalComponentes + parseFloat(item.value);
    }
  });

  $("#totalComponentes").html(totalComponentes.toFixed(2));
  getPorcentajeUtilidad();
}

function getTotalesEdit(){
  let costos = document.querySelectorAll('.getTotalesEdit')

  let totalComponentes =  0.00;
  costos.forEach((item) => {
    //console.log(item.value);
    if(item.value.trim() != ''){
      totalComponentes = totalComponentes + parseFloat(item.value);
    }
  });

  $("#totalComponentesEdit").html(totalComponentes.toFixed(2));
  getPorcentajeUtilidadEdit();
}

function getTotalesAdicionales(){
  let costosAdicionales = document.querySelectorAll('.getTotalAdicionales')

  let totalAdicionales =  0.00;
  costosAdicionales.forEach((item) => {
    //console.log(item.value);
    if(item.value.trim() != ''){
      totalAdicionales = totalAdicionales + parseFloat(item.value);
    }
  });

  $("#totalAdicionales").html(totalAdicionales.toFixed(2));
  getPorcentajeUtilidad();
}

function getTotalesAdicionalesEdit(){
  let costosAdicionales = document.querySelectorAll('.getTotalAdicionalesEdit')

  let totalAdicionales =  0.00;
  costosAdicionales.forEach((item) => {
    //console.log(item.value);
    if(item.value.trim() != ''){
      totalAdicionales = totalAdicionales + parseFloat(item.value);
    }
  });

  $("#totalAdicionalesEdit").html(totalAdicionales.toFixed(2));
  getPorcentajeUtilidadEdit();

}

function getTotalesGF(){
  let costos = document.querySelectorAll('.getTotalesGF')

  let totalComponentes =  0.00;
  costos.forEach((item) => {
    //console.log(item.value);
    if(item.value.trim() != ''){
      totalComponentes = totalComponentes + parseFloat(item.value);
    }
  });

  $("#totalGastoF").html(totalComponentes.toFixed(2));
  getPorcentajeUtilidad();
}

function getTotalesGFEdit(){
  let costos = document.querySelectorAll('.getTotalesGFEdit')

  let totalComponentes =  0.00;
  costos.forEach((item) => {
    //console.log(item.value);
    if(item.value.trim() != ''){
      totalComponentes = totalComponentes + parseFloat(item.value);
    }
  });

  $("#totalGastosFEdit").html(totalComponentes.toFixed(2));
  getPorcentajeUtilidadEdit();
}

function getUtilidad(){
    var value = $("#AtxtUtilidadesPorcentaje").val().trim();
    var totalComponentes = $("#totalComponentes").html().trim();
//alert(valuePL);
    if(totalComponentes == ''){
      totalComponentes = parseFloat(0.00);
    }
    else{
      totalComponentes = parseFloat(totalComponentes);
    }
    //alert(totalComponentes);

    var totalAdicionales = $("#totalAdicionales").html().trim();

    if(totalAdicionales == ''){
      totalAdicionales = parseFloat(0.00);
    }
    else{
      totalAdicionales = parseFloat(totalAdicionales);
    }
    //alert(totalAdicionales);

    var totalGastosFijos = $("#totalGastoF").html().trim();

    if(totalGastosFijos == ''){
      totalGastosFijos = parseFloat(0.00);
    }
    else{
      totalGastosFijos = parseFloat(totalGastosFijos);
    }

    var total = totalComponentes + totalAdicionales + totalGastosFijos;
    var utilidad = total * (value / 100);
      //alert(total + " - " + utilidad);
    $("#AtxtUtilidades").val(utilidad.toFixed(2));

    var newTotal = parseFloat(total) + parseFloat(utilidad);
    $("#total").html(newTotal.toFixed(2));
}

function getPorcentajeUtilidad(){
    var value = parseFloat($("#AtxtUtilidades").val());
  ///  alert(value);
    var totalComponentes = $("#totalComponentes").html().trim();
//alert(valuePL);
    if(totalComponentes == ''){
      totalComponentes = parseFloat(0.00);
    }
    else{
      totalComponentes = parseFloat(totalComponentes);
    }
    //alert(totalComponentes);

    var totalAdicionales = $("#totalAdicionales").html().trim();

    if(totalAdicionales == ''){
      totalAdicionales = parseFloat(0.00);
    }
    else{
      totalAdicionales = parseFloat(totalAdicionales);
    }

    var totalGastosFijos = $("#totalGastoF").html().trim();

    if(totalGastosFijos == ''){
      totalGastosFijos = parseFloat(0.00);
    }
    else{
      totalGastosFijos = parseFloat(totalGastosFijos);
    }

    if(totalAdicionales == 0 && totalComponentes == 0 && totalGastosFijos == 0){
      $("#AtxtUtilidadesPorcentaje").val('0.00');
      $("#total").html('0.00');
    }
    else{
      var totalAnt = totalComponentes + totalAdicionales + totalGastosFijos;
      var total = totalComponentes + totalAdicionales + totalGastosFijos + value;
      var utilidadPorcentaje = ((total / totalAnt) - 1 ) * 100;
      $("#AtxtUtilidadesPorcentaje").val(utilidadPorcentaje.toFixed(2));
      $("#total").html(total.toFixed(2));
    }


    
}


function getUtilidadEdit(){
    let value = $("#AtxtUtilidadesPorcentajeEdit").val().trim();
    let totalComponentes = $("#totalComponentesEdit").html().trim();
    let totalAdicionales = $("#totalAdicionalesEdit").html().trim();
    let totalGastosF = $("#totalGastosFEdit").html().trim();

    if(totalComponentes == ''){
      totalComponentes = parseFloat(0.00);
    }
    else{
      totalComponentes = parseFloat(totalComponentes);
    }
    //alert(totalComponentes);

    if(totalAdicionales == ''){
      totalAdicionales = parseFloat(0.00);
    }
    else{
      totalAdicionales = parseFloat(totalAdicionales);
    }

    if(totalGastosF == ''){
      totalGastosF = parseFloat(0.00);
    }
    else{
      totalGastosF = parseFloat(totalGastosF);
    }

    let total = totalComponentes + totalAdicionales + totalGastosF;
    let utilidad = total * (value / 100);
    $("#AtxtUtilidadesEdit").val(utilidad.toFixed(2));

    let newTotal = parseFloat(total) + parseFloat(utilidad);
    $("#totalEdit").html(newTotal.toFixed(2));
}

function getPorcentajeUtilidadEdit(){
    let value = parseFloat($("#AtxtUtilidadesEdit").val());
    let totalComponentes = $("#totalComponentesEdit").html().trim();
    let totalAdicionales = $("#totalAdicionalesEdit").html().trim();
    let totalGastosF = $("#totalGastosFEdit").html().trim();

    if(totalComponentes == ''){
      totalComponentes = parseFloat(0.00);
    }
    else{
      totalComponentes = parseFloat(totalComponentes);
    }
    //alert(totalComponentes);

    if(totalAdicionales == ''){
      totalAdicionales = parseFloat(0.00);
    }
    else{
      totalAdicionales = parseFloat(totalAdicionales);
    }

    if(totalGastosF == ''){
      totalGastosF = parseFloat(0.00);
    }
    else{
      totalGastosF = parseFloat(totalGastosF);
    }

    if(totalAdicionales == 0 && totalComponentes == 0 && $totalGastosF == 0){
      $("#AtxtUtilidadesPorcentajeEdit").val('0.00');
      $("#totalEdit").html('0.00');
    }
    else{
      let totalAnt = totalComponentes + totalAdicionales + totalGastosF;
      let total = totalComponentes + totalAdicionales + totalGastosF + value;
      let utilidadPorcentaje = ((total / totalAnt) - 1 ) * 100;
      $("#AtxtUtilidadesPorcentajeEdit").val(utilidadPorcentaje.toFixed(2));
      $("#totalEdit").html(total.toFixed(2));
    }

    
}

$(document).on('change', '#AtxtUtilidadesPorcentaje',  function(){
    getUtilidad();
});


$(document).on('change', '#AtxtUtilidades',  function(){
    
    getPorcentajeUtilidad();

});

$(document).on('change', '#AtxtUtilidadesPorcentajeEdit',  function(){
    
    getUtilidadEdit();

});


$(document).on('change', '#AtxtUtilidadesEdit',  function(){
    
    getPorcentajeUtilidadEdit();

});

function countInstances(string, word) {
   return string.split(word).length - 1;
}

/* $(document).on('input', '.cantidadProducto, .cantidadProductoAdicionales, .cantidadProductoEdit, .cantidadProductoAdicionalesEdit',  function(){
    
   //var regexp = /[^0-9][.]{0,1}/g;
   var regexp = /[^0-9\.]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }

}); */


function isNumberKey(txt, evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode == 46) {
    //Check if the text already contains the . character
    if (txt.value.indexOf('.') === -1) {
      return true;
    } else {
      return false;
    }
  } else {
    if (charCode > 31 &&
      (charCode < 48 || charCode > 57))
      return false;
  }
  return true;
}

$(document).on('keypress', '.precioProducto, .precioProductoAdicionales, .precioProductoEdit, .precioProductoAdicionalesEdit, .utilidadPorcentajeClass, .costoUnitarioClass, .cantidadProducto, .cantidadProductoAdicionales, .cantidadProductoEdit, .cantidadProductoAdicionalesEdit, .decimal',  function(evt){

    var txt = $(this).val();
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 46) {
      //Check if the text already contains the . character
      if (txt.indexOf('.') === -1) {
        return true;
      } else {
        return false;
      }
    } else {
      if (charCode > 31 &&
        (charCode < 48 || charCode > 57))
        return false;
    }
    return true;

});

let filaSeleccionada = 0;
function cargarCostos(id, modo){

   filaSeleccionada = id;
   let idProducto;
   let proveedor = 0;
   if(modo == 1){
    /* proveedor = $("#txtProveedores"+id).val().trim();
    proveedor = !isNaN(proveedor) ? proveedor : ""; */
    idProducto = $("#txtProductos" + id).val().trim();
  }
  else{
    /* proveedor = $("#txtProveedoresEdit_"+id).val().trim();
    proveedor = !isNaN(proveedor) ? proveedor : ""; */
    idProducto = $("#txtProductosEdit" + id).val().trim();
  }

   if(idProducto == ''){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "Selecciona un producto primero.",
      });
      return;
   }

   $("#tblListadoCostosHistoricos").DataTable().destroy();
        $("#tblListadoCostosHistoricos").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_costos_historicos", data:idProducto, modo: modo, proveedor: proveedor},
        },
        "columns":[
            { "data": "Producto" },
            { "data": "Proveedor" },
            { "data": "Fecha" },
            { "data": "Costo" },

        ],
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: true, targets: 3, visible: true },
            ],
            responsive: true
        });

    $("#costos_historicos_Modal").modal('show');
}

function fijarCosto(costo, proveedor, proveedorName){

  let costoFijar = costo.replace(',', '');
  costoFijar = parseFloat(costoFijar);
  $("#txtCosto_" + filaSeleccionada).val(costoFijar.toFixed(2));
  $('#txtCosto_' + filaSeleccionada).trigger('change');
  $("#cmbProveedores" + filaSeleccionada).val(proveedorName);
  $("#cmbProveedores" + filaSeleccionada).trigger('change');
  $("#txtProveedores" + filaSeleccionada).val(proveedor);
  $("#txtProveedores" + filaSeleccionada).trigger('change');

}

function fijarCostoEdit(costo, proveedor, proveedorName){

  let costoFijar = costo.replace(',', '');
  costoFijar = parseFloat(costoFijar);
  $("#txtCostoEdit_" + filaSeleccionada).val(costoFijar.toFixed(2));
  $('#txtCostoEdit_' + filaSeleccionada).trigger('change');
  $("#cmbProveedoresEdit" + filaSeleccionada).val(proveedorName);
  $("#cmbProveedoresEdit" + filaSeleccionada).trigger('change');
  $("#txtProveedoresEdit_" + filaSeleccionada).val(proveedor);
  $("#txtProveedoresEdit_" + filaSeleccionada).trigger('change');
}

let filaSeleccionadaAdicional = 0;
function cargarCostosAdicionales(id, modo){

   filaSeleccionadaAdicional = id;
   let idProducto;
   if(modo == 3){
    idProducto = $("#AtxtProductosAdicionales" + id).val().trim();
  }
  else{
    idProducto = $("#AtxtProductosAdicionalesEdit" + id).val().trim();
  }

   if(idProducto == ''){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "Selecciona un producto primero.",
      });
      return;
   }

   $("#tblListadoCostosHistoricos").DataTable().destroy();
        $("#tblListadoCostosHistoricos").dataTable({
            "lengthChange": false,
            "pageLength": 15,
            //"paging": true,
            "info": false,
            "pagingType": "full_numbers",
            "ajax": {
                url:"../../php/funciones.php",
                data:{clase:"get_data", funcion:"get_costos_historicos", data:idProducto, modo: modo, proveedor:0},
        },
        "columns":[
            { "data": "Producto" },
            { "data": "Proveedor" },
            { "data": "Fecha" },
            { "data": "Costo" },

        ],
        "language": setFormatDatatables(),
            columnDefs: [
            { orderable: false, targets: 0, visible: false },
            { orderable: true, targets: 3, visible: true },
            ],
            responsive: true
        });

    $("#costos_historicos_Modal").modal('show');
}

function fijarCostoAdicionales(costo){

  let costoFijar = costo.replace(',', '');
  costoFijar = parseFloat(costoFijar);
  $("#AtxtCostoAdicionales_" + filaSeleccionadaAdicional).val(costoFijar.toFixed(2));
  $('#AtxtCostoAdicionales_' + filaSeleccionadaAdicional).trigger('change');
}

function fijarCostoAdicionalesEdit(costo){

  let costoFijar = costo.replace(',', '');
  costoFijar = parseFloat(costoFijar);
  $("#AtxtCostoAdicionalesEdit_" + filaSeleccionadaAdicional).val(costoFijar.toFixed(2));
  $('#AtxtCostoAdicionalesEdit_' + filaSeleccionadaAdicional).trigger('change');
}

let filaUnidad = 0;
function cargarUnidadesSat(fila, modo){

    modoGeneral = modo;
    filaUnidad = fila;

      var buscador = $("#txtBuscarUnidad").val();

      $("#tblListadoUnidadesSAT").DataTable().destroy();
      $("#tblListadoUnidadesSAT").dataTable({
        "lengthChange": false,
        "pageLength": 100,
        "dom": 'lrtip',
        "info": false,
        "pagingType": "full_numbers",
        "ajax": {
          url:"../../php/funciones.php",
            data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador, modo: modo},
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


}

let filaUnidadEdit = 0;
function cargarUnidadesSatEdit(fila, modo){

    modoGeneral = modo;
    filaUnidadEdit = fila;
    /*var html = `<div style="position: fixed;
                    left: 0px;
                    top: 0px;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                    background: url('../../../../img/timdesk/Preloader.gif') 50% 50% no-repeat rgb(249,249,249);
                    opacity: .6;" id="loaderUnidad">
                </div>`;
    $('#cargarUnidadSAT').html(html);*/

   // if( $("#contadorUnidadSAT").val() == 0){

      var buscador = $("#txtBuscarUnidad").val();

      $("#tblListadoUnidadesSAT").DataTable().destroy();
      $("#tblListadoUnidadesSAT").dataTable({
        "lengthChange": false,
        "pageLength": 100,
        "dom": 'lrtip',
        "info": false,
        "pagingType": "full_numbers",
        "ajax": {
          url:"../../php/funciones.php",
            data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador, modo: modo},
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

  /*    $("#contadorUnidadSAT").val('1');
    }

    $("#loaderUnidad").fadeOut("slow");*/
}

let filaUnidadAdicionales = 0;
function cargarUnidadesSatAdicionales(fila, modo){

    modoGeneral = modo;
    filaUnidadAdicionales = fila;

      var buscador = $("#txtBuscarUnidad").val();

      $("#tblListadoUnidadesSAT").DataTable().destroy();
      $("#tblListadoUnidadesSAT").dataTable({
        "lengthChange": false,
        "pageLength": 100,
        "dom": 'lrtip',
        "info": false,
        "pagingType": "full_numbers",
        "ajax": {
          url:"../../php/funciones.php",
            data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador, modo: modo},
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

}

let filaUnidadAdicionalesEdit = 0;
function cargarUnidadesSatAdicionalesEdit(fila, modo){

    modoGeneral = modo;
    filaUnidadAdicionalesEdit = fila;

      var buscador = $("#txtBuscarUnidad").val();

      $("#tblListadoUnidadesSAT").DataTable().destroy();
      $("#tblListadoUnidadesSAT").dataTable({
        "lengthChange": false,
        "pageLength": 100,
        "dom": 'lrtip',
        "info": false,
        "pagingType": "full_numbers",
        "ajax": {
          url:"../../php/funciones.php",
            data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador, modo: modo},
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

}

let filaUnidadGastoF = 0;
function cargarUnidadesSatGastoF(fila, modo){

    modoGeneral = modo;
    filaUnidadGastoF = fila;

      var buscador = $("#txtBuscarUnidad").val();

      $("#tblListadoUnidadesSAT").DataTable().destroy();
      $("#tblListadoUnidadesSAT").dataTable({
        "lengthChange": false,
        "pageLength": 100,
        "dom": 'lrtip',
        "info": false,
        "pagingType": "full_numbers",
        "ajax": {
          url:"../../php/funciones.php",
            data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador, modo: modo},
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


}

let filaUnidadGastoFEdit = 0;
function cargarUnidadesSatGastoFEdit(fila, modo){

    modoGeneral = modo;
    filaUnidadGastoFEdit = fila;

      var buscador = $("#txtBuscarUnidad").val();

      $("#tblListadoUnidadesSAT").DataTable().destroy();
      $("#tblListadoUnidadesSAT").dataTable({
        "lengthChange": false,
        "pageLength": 100,
        "dom": 'lrtip',
        "info": false,
        "pagingType": "full_numbers",
        "ajax": {
          url:"../../php/funciones.php",
            data:{clase:"get_data", funcion:"get_unidadesSATTable", data:buscador, modo: modo},
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


}
//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
function obtenerIdUnidadSeleccionarAgregar(id, clave, descripcion) {
  //document.getElementById('txtIDUnidadSATAAA').value = id;
  //clave + ' - ' + descripcion
  let idProducto = $('#txtProductos' + filaUnidad ).val(); 

  $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "modificar_unidad_sat",
        idProducto: idProducto,
        idUnidadSat: id
      },
      dataType: "json",
      success: function (respuesta) {

        //console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Unidad actualizada",
          });
          $('#lblUnidadMedida' + filaUnidad ).html(descripcion);
          $('#tblListadoProductos').DataTable().ajax.reload();
          $('#tblListadoUnidadesSAT').DataTable().ajax.reload();

        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrio un error, intentalo nuevamente.",
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
          msg: "Ocurrio un error, intentalo nuevamente.",
        });
      },
    });

   
}

function obtenerIdUnidadSeleccionarAgregarEditar(id, clave, descripcion) {
  //document.getElementById('txtIDUnidadSATAAA').value = id;
  //clave + ' - ' + descripcion
  let idProducto = $('#txtProductosEdit' + filaUnidadEdit ).val(); 

  $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "modificar_unidad_sat",
        idProducto: idProducto,
        idUnidadSat: id
      },
      dataType: "json",
      success: function (respuesta) {

        //console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Unidad actualizada",
          });
          $('#lblUnidadMedidaEdit' + filaUnidadEdit ).html(descripcion);
          $('#tblListadoProductosEdit').DataTable().ajax.reload();
          $('#tblListadoUnidadesSAT').DataTable().ajax.reload();

        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrio un error, intentalo nuevamente.",
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
          msg: "Ocurrio un error, intentalo nuevamente.",
        });
      },
    });

   
}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible 
function obtenerIdUnidadSeleccionarAgregarAdicionales(id, clave, descripcion) {
  //document.getElementById('txtIDUnidadSATAAA').value = id;
  //clave + ' - ' + descripcion
  let idProducto = $('#AtxtProductosAdicionales' + filaUnidadAdicionales ).val(); 

  $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "modificar_unidad_sat",
        idProducto: idProducto,
        idUnidadSat: id
      },
      dataType: "json",
      success: function (respuesta) {

        //console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Unidad actualizada",
          });
          $('#lblUnidadMedidaAdicionales' + filaUnidadAdicionales ).html(descripcion);
          $('#tblListadoProductosAdicionales').DataTable().ajax.reload();
          $('#tblListadoUnidadesSAT').DataTable().ajax.reload();

        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrio un error, intentalo nuevamente.",
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
          msg: "Ocurrio un error, intentalo nuevamente.",
        });
      },
    });

   
}


function obtenerIdUnidadSeleccionarAgregarAdicionalesEdit(id, clave, descripcion) {
  //document.getElementById('txtIDUnidadSATAAA').value = id;
  //clave + ' - ' + descripcion
  let idProducto = $('#AtxtProductosAdicionalesEdit' + filaUnidadAdicionalesEdit ).val(); 

  $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "modificar_unidad_sat",
        idProducto: idProducto,
        idUnidadSat: id
      },
      dataType: "json",
      success: function (respuesta) {

        //console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Unidad actualizada",
          });
          $('#lblUnidadMedidaAdicionalEdit' + filaUnidadAdicionalesEdit ).html(descripcion);
          $('#tblListadoProductosAdicionalesEdit').DataTable().ajax.reload();
          $('#tblListadoUnidadesSAT').DataTable().ajax.reload();

        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrio un error, intentalo nuevamente.",
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
          msg: "Ocurrio un error, intentalo nuevamente.",
        });
      },
    });

   
}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible de gastos
function obtenerIdUnidadSeleccionarGastoF(id, clave, descripcion) {
  let idProducto = $('#txtGastoF' + filaUnidadGastoF ).val(); 

  $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "modificar_unidad_sat",
        idProducto: idProducto,
        idUnidadSat: id
      },
      dataType: "json",
      success: function (respuesta) {

        //console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Unidad actualizada",
          });
          $('#lblUnidadMedidaGastoF' + filaUnidadGastoF ).html(descripcion);
          $('#tblListadoGastosF').DataTable().ajax.reload();
          $('#tblListadoUnidadesSAT').DataTable().ajax.reload();

        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrio un error, intentalo nuevamente.",
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
          msg: "Ocurrio un error, intentalo nuevamente.",
        });
      },
    });

   
}

//Función para asignar los valores seleccionados de la unidad al combo y al input invisible de gastos al editar
function obtenerIdUnidadSeleccionarGastoFEdit(id, clave, descripcion) {
  let idProducto = $('#txtGastoFEdit' + filaUnidadGastoFEdit ).val(); 

  $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "modificar_unidad_sat",
        idProducto: idProducto,
        idUnidadSat: id
      },
      dataType: "json",
      success: function (respuesta) {

        //console.log("respuesta agregar personales:", respuesta);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "Unidad actualizada",
          });
          $('#lblUnidadMedidaGastoFEdit' + filaUnidadGastoFEdit ).html(descripcion);
          $('#tblListadoGastosFEdit').DataTable().ajax.reload();
          $('#tblListadoUnidadesSAT').DataTable().ajax.reload();

        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "Ocurrio un error, intentalo nuevamente.",
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
          msg: "Ocurrio un error, intentalo nuevamente.",
        });
      },
    });

   
}