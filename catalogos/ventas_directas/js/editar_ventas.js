var _permissionsFacturar = { 
  read: 0,
  add: 0,
  edit: 0,
  delete: 0,
  export: 0
}

var IdOpRmd = 0;
var estatusOP = 0;
var estatusFacturaid = 0;
var isInventario = 0;
var isServicio = 0;
var table;
$(document).ready(function(){ 

  
  var PKVenta = $("#txtPKVenta").val();
  //reinicia los productos preeliminadas a activas 
  reactivateProductos(PKVenta);
  clearEdicionestemp(PKVenta);
  /* table = $('#tblListadoVentasDirectasEdit').DataTable(); */
  //Can change 7 to 2 for longer results.
  IdOpRmd = (Math.random() + 1).toString(36).substring(7);
  console.log("random: ", IdOpRmd);

  cargarTablaVentasDirectasEdit(PKVenta,IdOpRmd); //ventas.js
  htmlMoneda ='<option data-placeholder="true"></option>';
  var monedas ={100:"MXN",49:"EUR",149:"USD"};
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_datos_VentaDirectaEdit", data:PKVenta},
    dataType:"json",
    success:function(respuesta){
      $('#txtReferencia').val(respuesta[0].Referencia);
      $('#txtPKVentaEncrip').val(respuesta[0].PKVentaDirecta);
      $('#txtFechaEmision').val(respuesta[0].FechaCreacion);
      $('#txtFechaEstimada').val(respuesta[0].FechaVencimiento);
      loadCombo(respuesta[0].FKCliente,'cmbCliente','cliente','','cliente');
      loadCombo(respuesta[0].FKSucursal,'cmbDireccionEnvio','sucursal','','sucursal');
      cambioCliente(respuesta[0].FKCliente);
      $('#NotasCliente').val(respuesta[0].NotasCliente);
      $('#NotasInternas').val(respuesta[0].NotasInternas);
      cargarCMBVendedor(respuesta[0].Vendedor,"cmbVendedor");
      cargarCMBCondicionPago(respuesta[0].CondicionPago, "cmbCondicionPago");  
      cargarCMBDireccionesEnvio(respuesta[0].direccionEntrega, "cmbDireccionEntrega", respuesta[0].FKCliente);
      /* Object.entries(monedas).forEach(([key, value]) => {
        if(key == respuesta[0].PKMoneda){
          htmlMoneda += '<option value="'+respuesta[0].PKMoneda+' selected">'+value+'</option>';
        }else{
          htmlMoneda += '<option value="'+respuesta[0].PKMoneda+'">'+value+'</option>';
        }
      }); */
      //$("#cmbMoneda").html(htmlMoneda);
      cmbMoneda.set(respuesta[0].PKMoneda);
      
      estatusOP = respuesta[0].EstatusOP;
      estatusFacturaid = respuesta[0].estatus_factura_id;
      isInventario = respuesta[0].IsInventario
      isServicio = respuesta[0].isServicio
    },
    error:function(error){
      console.log(error);
    },
    complete: function(_, __) {
      validate_Permissions(13);
    }
  });

  obtenerTotal();
  
  new SlimSelect({
    select: '#cmbCliente',
    deselectLabel: '<span class="">✖</span>',
  });

  cmbMoneda = new SlimSelect({
    select: '#cmbMoneda',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbProducto',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbDireccionEnvio',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbVendedor',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbCondicionPago',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbDireccionEntrega',
    deselectLabel: '<span class="">✖</span>',
  });
});

function reactivateProductos(PKVenta){
  console.log("PK: " + PKVenta);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "resetProducts", data: PKVenta },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      console.log("Products Reset");
      if(respuesta.includes('error')){
        console.log("No se reseteo")
      }
    },
    error: function (error) {
      console.log(error);
      console.log("No se resetearon los productos");
    },
  });
}

function clearEdicionestemp(PKVenta){
  console.log("PK: " + PKVenta);
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "clearEdicionestemp", data: PKVenta },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      console.log("Products Reset");
      if(respuesta.includes('error')){
        console.log("No se Limpio la Tabla de Ediciones")
      }
    },
    error: function (error) {
      console.log(error);
      console.log("No se Limpio la Tabla de Ediciones");
    },
  });
}

function cargarCMBVendedor(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vendedor" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {

        html +=
          `<option value="${respuesta[i].PKVendedor}">${respuesta[i].Nombre}</option>`;
      });

      $("#" + input + "").html(html);

      $("#" + input + "").val(data);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBCondicionPago(data, input) {
  var html = "", selected = "";

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_condicionPago" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        if(data === respuesta[i].PKCondicion){
          selected = 'selected';
        }else{
          selected = '';
        }
        html += '<option value="'+respuesta[i].PKCondicion+'" '+selected+'>'+respuesta[i].Condicion+'</option>';
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBDireccionesEnvio(data, input, cliente) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_direccionesEnvio", data: cliente},
    dataType: "json",
    success: function (respuesta) {

      html += '<option data-placeholder="true"></option>';
      if(respuesta.pop() != 6){
        html += '<option value="1">Pendiente de confirmar</option>';
      }
      $.each(respuesta, function (i) {
        console.log(respuesta[i].sucursal.substr(-3));
        if(respuesta[i].sucursal.substr(-4) == " -  "){
          html +=
          `<option value="${respuesta[i].id}">${respuesta[i].sucursal+"Desconocido"}</option>`;
        }else{
          html +=
          `<option value="${respuesta[i].id}">${respuesta[i].sucursal}</option>`;  
        }
      });

      $("#" + input + "").html(html);
      $("#" + input + "").val(data);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("click", "#mostrarTodos", function () {
  var valor = $('#cmbCliente').val();

  if($("#chkcmbTodoProducto").is(":checked")){
    $('#chkcmbTodoProducto').prop('checked',false);
    $('#textoMos').html("Mostrar todos los productos");
  }else{
    $('#chkcmbTodoProducto').prop('checked',true);
    $('#textoMos').html("Mostrar productos para venta");
  }

  if($("#chkcmbTodoProducto").is(":checked")){
    Swal.fire(
      "Al cliente seleccionado no se le venden los productos listados",
      "Para agregarlos a la lista de los productos que se le venden, favor completar los campos.",
      "success"
    );
    html = `<br>
            <div class="col-lg-6">
              <label for="usr">Precio especial para el cliente:*</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">$</span>
                </div>
                <input type="text" class="form-control numericDecimal-only" maxlength="10"
                  name="txtPrecioUnitarioEspecial" id="txtPrecioUnitarioEspecial" >
              </div>
            </div>`;
    $("#datosNew").html(html);
    $("#txtPrecioUnitario").prop("disabled",false);
    loadCombo('','cmbProducto','producto',valor,'todoProducto');
  }else{
    $("#txtPrecioUnitario").prop("disabled",false);
    loadCombo('','cmbProducto','producto',valor,'producto');
    html = ``;
    $("#datosNew").html(html);
  }

  
});

function cambioCliente(valor){
  $("#chkcmbTodoProducto").prop("disabled",false);
  $("#mostrarTodos").prop("disabled",false);

  loadComboProd('','cmbProducto','producto',valor,'producto');
  $('#chkcmbTodoProducto').on('change',function(){
    if(this.checked){
      Swal.fire(
        "Al cliente seleccionado no se le venden los productos listados",
        "Para agregarlos a la lista de los productos que se le venden, favor completar los campos.",
        "success"
      );
      html = `<br>
              <div class="col-lg-6">
                <label for="usr">Precio especial para el cliente:*</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                  </div>
                  <input type="text" class="form-control numericDecimal-only" maxlength="10"
                    name="txtPrecioUnitarioEspecial" id="txtPrecioUnitarioEspecial" >
                </div>
              </div>`;
      $("#datosNew").html(html);
      $("#txtPrecioUnitario").prop("disabled",false);
      loadComboProd('','cmbProducto','producto',valor,'todoProducto');
    }else{
      $("#txtPrecioUnitario").prop("disabled",false);
      loadComboProd('','cmbProducto','producto',valor,'producto');
      html = ``;
      $("#datosNew").html(html);
    }
  });
  loadComboProd('','cmbProducto','producto',valor);

  ////CAMBIO SUCURSAL
  $('#cmbProducto').on('change',function(){
    var prod = $('#cmbProducto').val();
    var all = 0;
    if($("#chkcmbTodoProducto").is(":checked")){
      all = 1;
    }else{
      all = 0;
    }

    $.ajax({
      url:"../../php/funciones.php",
      data:{clase:"get_data", funcion:"get_precioCliente",value:valor,value1:prod, value2: all},
      dataType:"json",
      success:function(respuesta){
        //Activar el comoboe ingresar los datos respectivos
        if ($("#chkcmbTodoProducto").is(":checked")) {
          //$('#txtPrecioUnitario').val(0);
          $('#txtPrecioUnitario').val(respuesta[0].Precio);
        }else{
          if(respuesta[0].Precio != null){
            $('#txtPrecioUnitario').val(respuesta[0].Precio);
          } 
        }
        $('#txtCantidad').val(0);
        $('#txtCantidadHis').val(1);
        $('#txtCantidad').prop("min",1);
      },
      error:function(error){
        console.log(error);
      }
    });

    cambioSucursal();
  });
}

function cambioSucursal(){
  var pkSucursal = $('#cmbDireccionEnvio').val();
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"validar_SucursalInventario",data:pkSucursal},
    dataType:"json",
    success:function(respuesta){
      var html = ``;

      if (parseInt(respuesta[0]["activo"]) === 1) {
        console.log('Posee inventarios Func');
        /*html = `<button class="btn-custom btn-custom--blue"
                style="position: relative; top: 32px;width: 100%;" type="button" id="verInventario"
                name="verInventario" onclick="verInventario()">Ver inventario</button>`*/

                html = `<div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
                    <label for="usr">Cantidad:*</label>
                    <input type="number" class="form-control numeric-only txtCantidad" maxlength="8"
                      name="txtCantidad" id="txtCantidad" value="0" onchange="validEmptyInput('txtCantidadExistencia', 'invalid-existencia', 'No se poseen existencias en la sucursal.')">
                    <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                  </div>
                  <div class="col-lg-4">
                    <label for="usr">Precio unitario:*</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtPrecioUnitario" id="txtPrecioUnitario">
                      <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un precio unitario.</div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <label for="usr">Stock en sucursal:</label>
                    <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtCantidadExistencia" id="txtCantidadExistencia" disabled="disabled" value="0">
                    <div class="invalid-feedback" id="invalid-existencia">No se poseen existencias en la sucursal.</div>
                  </div>
                </div>
              </div>`;
        
        mostrarStock(pkSucursal);
        
      }else{
        console.log('No tiene inventarios Func');
        html = `<div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <label for="usr">Cantidad:*</label>
                      <input type="number" class="form-control numeric-only txtCantidad" maxlength="8"
                        name="txtCantidad" id="txtCantidad" value="0" onchange="validEmptyInput('txtCantidadExistencia', 'invalid-existencia', 'No se poseen existencias en la sucursal.')">
                      <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                    </div>
                    <div class="col-lg-6">
                      <label for="usr">Precio unitario:*</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtPrecioUnitario" id="txtPrecioUnitario">
                        <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un precio unitario.</div>
                      </div>
                    </div>
                  </div>
                </div>`;
      }
      //$("#verInventarioSuc").html(html);
      $("#inventarioStock").html(html);
    },
    error:function(error){
      console.log(error);
    }
  });
  
}

function mostrarStock(pkSucursal){
  var pkProducto = $('#cmbProducto').val();
  var valor = $('#cmbCliente').val();
  var prod = $('#cmbProducto').val();
    var all = 0;
    if($("#chkcmbTodoProducto").is(":checked")){
      all = 1;
    }else{
      all = 0;
    }
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_InventarioSucursal",data:pkSucursal, data2:pkProducto},
    dataType:"json",
    success:function(respuesta){
      if (respuesta[0].isServicio == '5'){
        var html = `<div class="form-group">
                      <div class="row">
                        <div class="col-lg-6">
                          <label for="usr">Cantidad:*</label>
                          <input type="number" class="form-control numeric-only txtCantidad" maxlength="8"
                            name="txtCantidad" id="txtCantidad" value="0" onchange="validEmptyInput('txtCantidadExistencia', 'invalid-existencia', 'No se poseen existencias en la sucursal.')">
                          <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                        </div>
                        <div class="col-lg-6">
                      <label for="usr">Precio unitario:*</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtPrecioUnitario" id="txtPrecioUnitario">
                        <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un precio unitario.</div>
                      </div>
                    </div>
                      </div>
                    </div>`;
          $("#inventarioStock").html(html);
      }else{
        $("#txtCantidadExistencia").val(respuesta[0].StockExistencia);
        validEmptyInput('txtCantidadExistencia', 'invalid-existencia', 'No se poseen existencias en la sucursal.');
      }
      $.ajax({
        url:"../../php/funciones.php",
        data:{clase:"get_data", funcion:"get_precioCliente",value:valor,value1:prod, value2: all},
        dataType:"json",
        success:function(respuesta){
          //Activar el comoboe ingresar los datos respectivos
          if ($("#chkcmbTodoProducto").is(":checked")) {
            //$('#txtPrecioUnitario').val(0);
            $('#txtPrecioUnitario').val(respuesta[0].Precio);
          }else{
            if(respuesta[0].Precio != null){
              $('#txtPrecioUnitario').val(respuesta[0].Precio);
            } 
          }
          $('#txtCantidad').val(0);
          $('#txtCantidadHis').val(1);
          $('#txtCantidad').prop("min",1);
        },
        error:function(error){
          console.log(error);
        }
      });
    },
    error:function(error){
      console.log(error);
    }
  });
  
}

function agregarProd(){
  //Obtener valores de los campos
  var idproducto = $('#cmbProducto').val();   
  var pkVentaDirecta= $("#txtPKVenta").val();
  var cantidad = parseInt($('#txtCantidad').val());
  var pkCliente = $('#cmbCliente').val(); 
  var precio = $('#txtPrecioUnitario').val(); 
  var precioEsp = 0;
  if($("#chkcmbTodoProducto").is(":checked")){
    console.log("chkTrue");
    if(!$('#txtPrecioUnitarioEspecial').val()){  
      console.log("precio especial 0") ;
      precioEsp = 0;
    }else{
      precioEsp = $('#txtPrecioUnitarioEspecial').val(); 
      console.log("precio especial " + precioEsp);
    }
  }

  //inicio alertas
  var alerta = "";
  if($('#cmbProducto').val() === 0){
    $("#invalid-producto").css("display", "block");
    $("#cmbProducto").addClass("is-invalid");
  }else if(!$('#txtCantidad').val()){
    $("#invalid-producto").css("display", "none");
    $("#cmbProducto").removeClass("is-invalid");

    $("#invalid-productoCnt").css("display", "block");
    $("#txtCantidad").addClass("is-invalid");
  }else if(!$('#cmbDireccionEnvio').val()){
    $("#invalid-productoCnt").css("display", "none");
    $("#txtCantidad").removeClass("is-invalid");

    $("#invalid-sucursal").css("display", "block");
    $("#cmbDireccionEnvio").addClass("is-invalid");
  }else if(!$('#cmbDireccionEntrega').val()){
    $("#invalid-sucursal").css("display", "none");
    $("#cmbDireccionEnvio").removeClass("is-invalid");

    $("#invalid-direccionEntrega").css("display", "block");
    $("#cmbDireccionEntrega").addClass("is-invalid");
  }else if($('#txtCantidad').val() < 1){
    $("#invalid-direccionEntrega").css("display", "none");
    $("#cmbDireccionEntrega").removeClass("is-invalid");

    $("#invalid-productoCnt").css("display", "block");
    $("#txtCantidad").addClass("is-invalid");
  }else if(parseInt($('#txtCantidad').val()) < parseInt($('#txtCantidadHis').val())){
    $("#invalid-productoCnt").css("display", "none");
    $("#txtCantidad").removeClass("is-invalid");

    $("#invalid-producto").css("display", "none");
    $("#cmbProducto").removeClass("is-invalid");
    $("#invalid-productoCnt").css("display", "none");
    $("#txtCantidad").removeClass("is-invalid");
    $("#invalid-sucursal").css("display", "none");
    $("#cmbDireccionEnvio").removeClass("is-invalid");
    $("#invalid-direccionEntrega").css("display", "none");
    $("#cmbDireccionEntrega").removeClass("is-invalid");
    //Validar producto
    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_productoVentaDirectaEdit",
        data: idproducto, data2: pkVentaDirecta, data3: pkCliente,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta nombre valida: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if (parseInt(data[0]["existe"]) === 1) {
          validarYGuardarProducto(idproducto,pkVentaDirecta,cantidad,pkCliente,precio,precioEsp);
          console.log("¡Ya existe!");
        } else {
          
          alerta ='<div class="alert alert-warning" role="alert">'+
          'Debe ingresar una cantidad mayor a 0.'+
          '</div>';

          $('#txtCantidad').val($('#txtCantidadHis').val());

          console.log("¡No existe!");

          $('#alertas').html(alerta);
        }
      },
    });
    
    
  }
  /*else if($("#chkcmbTodoProducto").is(":checked") && !$('#txtPrecioUnitarioEspecial').val()){
    alerta ='<div class="alert alert-warning" role="alert">'+
            'Debe ingresar un especial del producto para el cliente'+
            '</div>';
  }*/
  else{
    
    validarYGuardarProducto(idproducto,pkVentaDirecta,cantidad,pkCliente,precio,precioEsp);

  }
  
  $('#alertas').html(alerta);

  $("#invalid-existencia").css("display", "none");
  $("#invalid-existencia").text(
    ""
  );
  $("#txtCantidadExistencia").removeClass("is-invalid");

}

function validarYGuardarProducto(idproducto,pkVentaDirecta,cantidad,pkCliente,precio,precioEsp){
  console.log("Pk product: " + idproducto);
  //Validar Si el producto ya esta en la venta.
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_productoVentaDirectaEdit",
      data: idproducto, data2: pkVentaDirecta, data3: pkCliente,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) === 1) {
        
        Swal.fire({
          title:
            '<h3 style="arialRoundedEsp;">El producto ya se encuentra agregado<h3>',
          html:
            '<h5 style="arialRoundedEsp;">¿Desea agregar la nueva cantidad a la ya existente?.<h5>',
          icon: "success",
          showConfirmButton: true,
          focusConfirm: false,
          showCloseButton: false,
          showCancelButton: true,
          confirmButtonText:
            'Si   <i class="far fa-arrow-alt-circle-right"></i>',
          cancelButtonText: 'No   <i class="far fa-times-circle"></i>',
          buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            /* Cambia el valor de los inputs y lanza el trigger change para pasar los cambios */
            console.log($("#cantidad-"+data[0]["PKDetalleVentaDirecta"]).val());
            $("#cantidad-"+data[0]["PKDetalleVentaDirecta"] ).val(cantidad);
            $("#precio-"+data[0]["PKDetalleVentaDirecta"] ).val(precio);
            $("#cantidad-"+data[0]["PKDetalleVentaDirecta"] ).trigger( "change" );
            console.log("Confirmada Edicion");
           /*  var element = document.getElementById("content");
            element.scrollIntoView();
            //actualización de datos a tabla
            $.ajax({
              url: "../../php/funciones.php",
              data: {
                clase: "edit_data",
                funcion: "edit_venta_directaEdit",
                datos: idproducto, datos2: cantidad, datos3: pkVentaDirecta, datos4: pkCliente
              },
              dataType: "json",
              success: function (respuesta) {
                console.log("respuesta agregar venta directa:", respuesta);
          
                if (respuesta[0].status) {
                  $("#tblListadoVentasDirectasEdit").DataTable().ajax.reload();
                  obtenerTotal();
                  Swal.fire(
                    "Actualización exitosa",
                    "Se actualizó la cantidad del producto en la orden de venta con exito",
                    "success"
                  );
                  
                } else {
                  Swal.fire("Error", "No se actualizó la cantidad del producto en la orden de venta con exito", "warning");
                }
              },
              error: function (error) {
                console.log(error);
              },
            }); */ 
            
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            /*No hacer nada*/
          } else {
            /*No hacer nada*/
          }
        });

        console.log("¡Ya existe!");
      } else {
        console.log("random: ", IdOpRmd);
        /*Agregar producto a la vetna directa*/
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_venta_directaEdit",
            datos: idproducto, datos2: cantidad, datos3: pkVentaDirecta, datos4: pkCliente, datos5:precio, datos6:precioEsp, random:pkVentaDirecta
          },
          dataType: "json",
          success: function (respuesta) {
            console.log("respuesta agregar venta directa:", respuesta);
      
            if (respuesta[0].status) {
              $("#tblListadoVentasDirectasEdit").DataTable().ajax.reload();
              
              Swal.fire(
                "Registro exitoso",
                "Se guardó el producto en la orden de venta con exito",
                "success"
              );
              obtenerTotal();
            } else {
              Swal.fire("Error", "No se guardó el producto en la orden de venta con exito", "warning");
            }
          },
          error: function (error) {
            console.log(error);
          },
        });

        console.log("¡No existe!");
      }
      
      
    },
    error: function (error) {
      console.log(error);
    },
  });
  if($("#chkcmbTodoProducto").is(":checked")){
    $('#txtPrecioUnitarioEspecial').val(''); 
    loadComboProd('','cmbProducto','producto',$('#cmbCliente').val(),'todoProducto');
  }else{
    loadComboProd('0','cmbProducto','producto',$('#cmbCliente').val(),'producto');
  }
  //$('#chkcmbTodoProducto').prop('checked',false);
  $('#txtPrecioUnitario').val('');
  $('#cmbCliente option:not(:selected)').remove();
  $('#txtCantidad').val('');
}

function enviarVentaDirecta(){
  var alerta = "";
  $("#btnAgregar").prop('disabled',true);
  /* var table = $('#tblListadoVentasDirectasEdit').DataTable(); */
 
  if ($("#frmVentaDirectaEdit")[0].checkValidity()) {
    var badFechaEstimada =
      $("#invalid-fechaVen").css("display") === "block" ? false : true;
    var badCliente =
      $("#invalid-cliente").css("display") === "block" ? false : true;
    var badSucursal =
      $("#invalid-sucursal").css("display") === "block" ? false : true;  
    var badVendedor =
      $("#invalid-vendedor").css("display") === "block" ? false : true; 
    var badDireccionEnvio =
      $("#invalid-direccionEntrega").css("display") === "block" ? false : true; 
    var badCondicionPago =
      $("#invalid-condicionPago").css("display") === "block" ? false : true; 
    if (
      badFechaEstimada &&
      badCliente &&
      badSucursal &&
      badVendedor &&
      badDireccionEnvio &&
      badCondicionPago

    ) {
      var referencia = $('#txtReferencia').val();
      var fechaEmision = $('#txtFechaEmision').val();
      var fechaVencimiento = $('#txtFechaEstimada').val();
      var cliente = $('#cmbCliente').val();
      var direccionEntrega = $('#cmbDireccionEnvio').val();
      var direccionEntregaCliente = $('#cmbDireccionEntrega').val();
      var condicionPago = $('#cmbCondicionPago').val();
      var moneda = $('#cmbMoneda').val();

      var datasplit = $("#Total").html().split(",");
      var importeBetha = "";
      for (var i = 0; i < datasplit.length; i++) {
        importeBetha += datasplit[i];
      }
      var importe = parseFloat(importeBetha);

      var datasplit2 = $("#Subtotal").html().split(",");
      var subtotalBetha = "";
      for (var i = 0; i < datasplit2.length; i++) {
        subtotalBetha += datasplit2[i];
      }
      var subtotal = parseFloat(subtotalBetha);

      var pkVentaDirecta= $("#txtPKVenta").val();
      var notasInternas = $("#NotasInternas").val();
      var notasCliente = $("#NotasCliente").val();
      var vendedor = $("#cmbVendedor").val();

      $.ajax({
        url:"../../php/funciones.php",
        data:{
          clase:"edit_data",funcion:"edit_VentaDirecta",
          datos:referencia,
          datos2:fechaEmision, 
          datos3:fechaVencimiento,
          datos4:cliente,
          datos5:direccionEntrega, 
          datos6:importe,
          datos7:pkVentaDirecta, 
          datos8: notasInternas, 
          datos9: notasCliente,
          datos10:vendedor,
          monedas: moneda,
          datos11: subtotal,
          datos12: direccionEntregaCliente,
          datos13: condicionPago,
          rdm: pkVentaDirecta
        }, 
        dataType:"json", 
        success:function(respuesta){ 
          console.info(respuesta);
          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "Orden de venta registrada correctamente!",
              sound: '../../../../../sounds/sound4'
            });

            setTimeout(
              function() {
                window.location.href = "functions/descargar_VentaDirecta.php?txtId="+pkVentaDirecta;
              },
              500,
            );
            setTimeout(function(){window.location.href = "../ventas"},1500);

          }else{
            $("#btnAgregar").prop('disabled',false);
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
        }
      });
    }
  }else{
    if(!$('#txtFechaEstimada').val()){
      $("#invalid-fechaVen").css("display", "block");
      $("#txtFechaEstimada").addClass("is-invalid");
    }else {
      $("#invalid-fechaVen").css("display", "none");
      $("#txtFechaEstimada").removeClass("is-invalid");
    }
    
    if(!$('#cmbDireccionEnvio').val()){
      $("#invalid-sucursal").css("display", "block");
      $("#cmbDireccionEnvio").addClass("is-invalid");
    }else {
      $("#invalid-sucursal").css("display", "none");
      $("#cmbDireccionEnvio").removeClass("is-invalid");
    }
  
    if(!$('#cmbVendedor').val()){
      $("#invalid-vendedor").css("display", "block");
      $("#cmbVendedor").addClass("is-invalid");
    }else {
      $("#invalid-vendedor").css("display", "none");
      $("#cmbVendedor").removeClass("is-invalid");
    }

    if(!$('#cmbCondicionPago').val()){
      $("#invalid-condicionPago").css("display", "block");
      $("#cmbCondicionPago").addClass("is-invalid");
    }else {
      $("#invalid-condicionPago").css("display", "none");
      $("#cmbCondicionPago").removeClass("is-invalid");
    }

    if(!$('#cmbDireccionEntrega').val()){
      $("#invalid-direccionEntrega").css("display", "block");
      $("#cmbDireccionEntrega").addClass("is-invalid");
    }else {
      $("#invalid-direccionEntrega").css("display", "none");
      $("#cmbDireccionEntrega").removeClass("is-invalid");
    }
  }
  
  /*$('#modal_envio').load('functions/modal_envio.php?id='+$('#cmbCliente').val()+'&txtId='+14+'&estatus=0&txtNotas='+$('#NotasCliente').val(), function(){
    $('#datos_envio').modal('show');
  });*/
}

function obtenerIdVentaDirectaEditEliminar(id) {
  var PKVenta = $("#txtPKVenta").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_VentaDirectaEdit",
      data: id,
      Venta: PKVenta, 
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar producto:", respuesta);

      if (respuesta[0].status) {
        $("#tblListadoVentasDirectasEdit").DataTable().ajax.reload();
        obtenerTotal();
        Swal.fire(
          "Eliminación exitosa",
          "Se eliminó el producto de la orden con exito",
          "success"
        );
      } else {
        Swal.fire("Error", "No se eliminó el producto de la orden con exito", "warning");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

var controladorTiempo = "";

function validarCantidad(id){
  clearTimeout(controladorTiempo);
  controladorTiempo = setTimeout(validarCant(id), 3000);
}

function validarCant(id){
  valor = $('#cantidad-'+id).val();
  newPrecio = $('#precio-'+id).val();
  //valida que el precio sea mayor que cero
  if(parseInt(newPrecio) < 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: 'El precio debe ser mayor a 0',
      sound: '../../../../../sounds/sound4'
    });
    $('#precio-'+id).val('');
    return;
  }

  //valida que la catidad sea mayor que cero
  if(parseInt(valor) < 1){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: 'La cantidad debe ser mayor a 0',
      sound: '../../../../../sounds/sound4'
    });
    $('#cantidad-'+id).val('');
    return;
  }
  /* if(parseInt($('#cantidad-'+id).val()) < 1 ||$('#cantidad-'+id).val()== ''){
    $("#notaCantidad-"+id).css("display", "block");
    $('#notaCantidad-'+id).prop('title', 'La cantidad debe de ser mayor a 0');
  }else{
    $("#notaCantidad-"+id).css("display", "none"); */
    //valida que la cantidad no sea mayor a 12 enteros y 6 decimales
  aux = newPrecio.toString().split(".");
  var ValorAux="";
  flag = false;

  if(aux.length > 0){
    if(aux.length == 1 && aux[0].length > 12){
      flag = true;
      ValorAux = aux[0].substring(0,12);
      }else if(aux.length >= 2 && (aux[0].length > 12 || aux[1].length > 6)){
        flag = true;
        ValorAux = aux[0].substring(0,12) + "." + aux[1].substring(0,6);
      }else if(aux.length == 1){
        ValorAux = newPrecio.toString() + ".00";
      }else{
        ValorAux = newPrecio;
      }
  }
  if(flag){
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/warning_circle.svg",
      msg: 'El precio solo admite hasta 12 enteros y 6 decimales',
      sound: '../../../../../sounds/sound4'
    });
    $('#precio-'+id).val(ValorAux);
  }
  



    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "edit_data",
        funcion: "edit_VentaDirectaEdit_Cantidad",
        datos: id,
        datos2: valor,
        precio: ValorAux,
      },
      dataType: "json",
      success: function (respuesta) {
        console.log(
          "respuesta editar datos de cantidad de orden de compra:",
          respuesta
        );

        if (respuesta[0].status) {
          console.log('Actualización exitosa');
          $("#tblListadoVentasDirectasEdit").DataTable().ajax.reload();
          obtenerTotal();
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
  /* } */
}

function loadCombo(data,input,name,value,fun){
  if (input == 'cmbDireccionEnvio'){
    var html ='<option value="0" disabled selected hidden>Seleccione una '+name+'...</option>';
  }else{
    var html ='<option value="0" disabled selected hidden>Seleccione un '+name+'...</option>';
  }

  var oculto;
  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_"+fun+"Combo",value:value},
  	dataType:"json",
    success:function(respuesta){
      if(respuesta !== "" && respuesta !== null && respuesta.length > 0){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKData){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKData+'" '+selected+'>'+respuesta[i].Data+'</option>';
          if(respuesta[i].Oculto !== ""){
            oculto = respuesta[i].Oculto;

          }
        });
        
      }else{
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }

      $('#'+input+'').html(html);
      if(oculto !== ""){
        $('#unidadMedida').val(oculto);
      }

      //$("#cmbCliente").prop("disabled",true);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function loadComboProd(data,input,name,value,fun){
  
  var html ='<option value="0" disabled selected hidden>Seleccione un '+name+'...</option>';

  var oculto;

  var PKVenta = $("#txtPKVenta").val();

  $.ajax({
    url:"../../php/funciones.php",
  	data:{clase:"get_data", funcion:"get_"+fun+"ComboEdit",value:value, value2: PKVenta},
  	dataType:"json",
    success:function(respuesta){
      if(respuesta !== "" && respuesta !== null && respuesta.length > 0){
        $.each(respuesta,function(i){
          if(data === respuesta[i].PKData){
            selected = 'selected';
          }else{
            selected = '';
          }
          html += '<option value="'+respuesta[i].PKData+'" '+selected+'>'+respuesta[i].Data+'</option>';
          if(respuesta[i].Oculto !== ""){
            oculto = respuesta[i].Oculto;

          }
        });
        
      }else{
        html += '<option value="vacio">No hay productos que mostrar</option>';
      }

      $('#'+input+'').html(html);
      if(oculto !== ""){
        $('#unidadMedida').val(oculto);
      }

      //$("#cmbCliente").prop("disabled",true);
    },
    error:function(error){
      console.log(error);
    }

  });
}

function lobby_notify(string, icono,classStyle, carpeta){

	Lobibox.notify(classStyle, {
      size: 'mini',
      rounded: true,
      delay: 4000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../../../img/' + carpeta +icono,
      msg: string,
      sound: '../../../../../sounds/sound4'
    });

    return;

}
var Total = 0.0;
async function obtenerTotal(){
  var PKVenta = $("#txtPKVenta").val();
  Total = 0.0;
  //Obtener subtotal
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_subTotalVentaDirectaEdit",datos:PKVenta},
    dataType:"json",
    success:function(respuesta){
     /*  var subtotal = respuesta[0].subtotal; */
     //respuesta[0].subtotal=(respuesta[0].subtotal).toFixed(6).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/,'$1')
      $('#Subtotal').html(dosDecimales(respuesta[0].subtotal));
      console.log("Subtotal: " + (respuesta[0].subtotal));
      Total += (respuesta[0].subtotal);
      $("#Total").html(dosDecimales(Total));
    },
    error:function(error){
      console.log(error);
    }
  });

  var html='',ieps, iva;
  $('#impuestos').html(html);
  //Obtener impuestos
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{
      clase:"get_data", 
      funcion:"get_impuestoVentaDirectaEdit_v2",
      datos:PKVenta,
      datos2: 1,
    },
    dataType:"json",
    success:function(respuesta){
      //Recorrer las respuestas de la consulta
      var tasa = '';
      $.each(respuesta,function(i){    

        if(respuesta[i].tipo == 2 || respuesta[i].tasa == null){
          tasa = "$"+respuesta[i].tasa;
        }else{
          tasa = respuesta[i].tasa+'%';
        }

        if(!$('#impuestos-head-'+respuesta[i].id).length){
        html += /* '<tr>'+
                  //'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+ <--
                  '<td style="text-align: right;" id="impuestos-head-'+respuesta[i].id+'">'+respuesta[i].nombre+'</td>'+
                  '<td style="text-align: right;">'+tasa+' </td>'+
                  '<td style="text-align: right;">.....</td>'+
                  '<td style="text-align: right;"> $ '+ dosDecimales(respuesta[i].totalImpuesto) +'</td>'+
                '</tr>'; */

                '<span style="color: var(--color-primario);" id="impuestos-head-' + respuesta[i].id + '">'+
                respuesta[i].nombre + ": $ " + dosDecimales(respuesta[i].totalImpuesto) +
                "</span><br>";

                /// Si es Retenido TipoImp 2 se resta del Total.
                if(respuesta[i].tipoImp == 2){
                  Total -= respuesta[i].totalImpuesto;
                }else{
                  Total += respuesta[i].totalImpuesto;
                }
        }
         

      });
      $("#Total").html(dosDecimales(Total));
     
      $('#impuestos').html(html);
    },
    error:function(error){
      console.log(error);
    }
  });

  ///Deprecate: No grava IEPS a IVA,
    // Ahora el calculo se hacce en el front en esta misma funcion
  //Obtener total
  /* $.ajax({
    
    url:"../../php/funciones.php",
    data:{
      clase:"get_data", 
      funcion:"get_totalVentaDirectaEdit",
      datos:PKVenta,
      datos2: 1,
    },
    dataType:"json",
    success:function(respuesta){
      //$('#Total').html(dosDecimales(respuesta[0].Total))
    },
    error:function(error){
      console.log(error);
    }
  }); */
  
}

function cortarNumber(n){
  /* var number = n;
    n = Number.parseFloat(n).toFixed(6).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    number = n; */
  /* if(number.toString.length > 6){
    number = Number.parseFloat(n).toFixed(6).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  } */
  return ((n).toFixed(6).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/,'$1'));
  ;
}
function cortarNumber2(n){
  /* var number = n;
    n = Number.parseFloat(n).toFixed(6).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    number = n; */
  /* if(number.toString.length > 6){
    number = Number.parseFloat(n).toFixed(6).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  } */
  return ((n).toFixed(6).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/,'$1'));
  ;
}

function dosDecimales(n) {
  ///Redondeo preciso 34.533423131231 -> 34.53
  n = Math.round((n + Number.EPSILON) * 100) / 100;
  return Number.parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

function formatNumber(){
  //valida que la cantidad no sea mayor a 12 enteros y 6 decimales
  aux = valor.toString().split(".");
  var ValorAux;
  flag = false;

  if(aux.length > 0){
    if(aux.length == 1 && aux[0].length > 12){
      flag = true;
      ValorAux = aux[0].substring(0,12);
    }else if(aux.length >= 2 && (aux[0].length > 12 || aux[1].length > 6)){
      flag = true;
      ValorAux = aux[0].substring(0,12) + "." + aux[1].substring(0,6);
    }else{
      ValorAux = ValorAux + ".00";
    }

    if(flag){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: 'El precio solo admite hasta 12 enteros y 6 decimales',
        sound: '../../../../../sounds/sound4'
      });
      valor = ValorAux;
      sender.value = ValorAux;
    }
  }
}

function cambiarEstatusVentaDirecta(valor){
  var id = $("#txtPKVenta").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "edit_EstatusVentaDirecta",
      datos: id,
      datos2: valor,
    },
    dataType: "json",
    success: function (respuesta) {

      if (respuesta[0].status) {
        
          Swal.fire({
            title: "Operación exitosa",
            text: "Se ha cerrado la venta directa correctamente",
            type: "success"
          }).then (function() {
            window.location.href = "../ventas";
          });
        
      } else {
        Swal.fire("Error", 
          "No se pudo cerrar la venta directa correctamente, ¡Favor de intentarlo más tarde!", 
          "warning"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function verVentaDirecta(){
  var pkVenta = $("#txtPKVenta").val();
  window.location.href = "ver_ventas.php?vd="+pkVenta;
}

function validate_Permissions(pkPantalla){
  var PKVenta = $("#txtPKVenta").val();
  $.ajax({
    url: '../../php/funciones.php',
    data:{clase:"get_data", 
          funcion:"validar_Permisos", 
          data:pkPantalla},
    dataType:"json",
    success: function(data) {
      _permissionsFacturar.read = data[0].isRead;
      _permissionsFacturar.add = data[0].isAdd;
      _permissionsFacturar.edit = data[0].isEdit;
      _permissionsFacturar.delete = data[0].isDelete;
      _permissionsFacturar.export = data[0].isExport;

      //PRODUCTOS
      if (pkPantalla == "16"){
        var html = '';
        if (_permissionsFacturar.add == "1"){
          if ((estatusOP == '2' || estatusOP == '4' || estatusOP == '6' || estatusOP == '9' || estatusOP == '10' || isInventario == '0' || isServicio == '5') && (estatusFacturaid == "4" || estatusFacturaid == "3" || estatusFacturaid == "5")){
            html = `<span class="btn-table-custom btn-table-custom--blue" id="btnFacturar" onclick="facturarVentaDirecta(${PKVenta})"><i class="fas fa-file-invoice"></i> Facturar</span>`;
            console.log(estatusFacturaid);
          }else{
            html = ``;
          }
          $("#isPermissionsFacturar").html(html);
        }else{
          html = ``;
          $("#isPermissionsFacturar").html(html);
        }
      }
    }
  });
}

$(document).on("click", "#btnFacturar", function () {
  var PKVenta = $("#txtPKVenta").val();

  //window.location.href =`../../../facturacion/agregar_facturacion.php?idVentaDirecta=${PKVenta}`;

  $().redirect('../../../facturacion/agregar_facturacion.php', {
    'idVentaDirecta': PKVenta
  });
});

function validEmptyInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val() || $("#" + inputID).val() == 0) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
  }


  var cantidad = $("#txtCantidad").val();
  
  if (parseInt($("#" + inputID).val()) < parseInt(cantidad)) {
    $("#"+invalidDivID).css("display", "block");
    $("#"+invalidDivID).text(
      "La cantidad en existencia es menor a la deseada."
    );
    $("#"+inputID).addClass("is-invalid");
  } else {
    $("#"+invalidDivID).css("display", "none");
    $("#"+invalidDivID).text(
      ""
    );
    $("#"+inputID).removeClass("is-invalid");

    
  }
}

function eliminarVentaTemp(){
  var pkUs= $("#txtUsuario").val();
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"delete_data", funcion:"delete_VentaDirectaTempAll", data:pkUs},
    dataType:"json",
    success:function(respuesta){
      $('#txtReferencia').val(respuesta);
    },
    error:function(error){
      console.log(error);
    }
  });

}
$('#tblListadoVentasDirectasEdit').DataTable().on( 'draw', function () {
  console.log( 'Redraw occurred at: '+new Date().getTime() );  
  //Obtener total
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{
      clase:"get_data", 
      funcion:"get_totalVentaDirectaEdit",
      datos:PKVenta,
      datos2: 1,
    },
    dataType:"json",
    success:function(respuesta){
      $('#Total').html(cortarNumber(respuesta[0].Total))
    },
    error:function(error){
      console.log(error);
    }
  });
} );