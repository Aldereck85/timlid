var _global = { 
  fechaVencimientoMin: '0000-00-00',
  PKVenta: 0,
}
var cmbMoneda;
var IdOpRmd = 0;
$(document).ready(function(){
  //Can change 7 to 2 for longer results.
  IdOpRmd = (Math.random() + 1).toString(36).substring(7);
  console.log("random: ", IdOpRmd);

  eliminarVentaTemp();
  cargarTablaVentasDirectasTemp(); //ventas.js

  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_referencia"},
    dataType:"json",
    
    success:function(respuesta){
      if(respuesta.indexOf("Object")>-1){

      }else{
        $('#txtReferencia').val(respuesta);
      }
    },
    error:function(error){
      console.log(error);
    }
  });

  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_fechaEmision"},
    dataType:"json",
    success:function(respuesta){
      $('#txtFechaEmision').val(changeFormateDate(respuesta));
    },
    error:function(error){
      console.log(error);
    }
  });

  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_fechaVencimientoMin" },
    dataType: "json",
    success: function (respuesta) {
      var today = new Date();
      var tomorrow = today;
      tomorrow.setDate(tomorrow.getDate() + 1);
      var dd = tomorrow.getDate();
      var mm = tomorrow.getMonth()+1; //January is 0 so need to add 1 to make it 1!
      var yyyy = tomorrow.getFullYear();
      if(dd<10){
        dd='0'+dd
      } 
      if(mm<10){
        mm='0'+mm
      } 

      tomorrow = yyyy+'-'+mm+'-'+dd;

      _global.fechaVencimientoMin = tomorrow
      //_global.fechaVencimientoMin = respuesta;
      document.getElementById("txtFechaEstimada").min = tomorrow;
      $("#txtFechaEstimada").val(respuesta);
    },
    error: function (error) {
      console.log(error);
    },
  });

  function changeFormateDate(oldDate)
  {
    return oldDate.toString().split("/").reverse().join("/");
  }
  
  SS_cliente = new SlimSelect({
    select: '#cmbCliente',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      cargarCMBRegimen('cmbRegimen');
      cargarCMBVendedorNC("cmbVendedorNC");
      cargarCMBMedioContactoCliente("cmbMedioContactoCliente");
      $("#agregar_Cliente_50").modal("toggle");
      return;
    }
  });

  cmbMoneda = new SlimSelect({
    select: '#cmbMoneda',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_producto = new SlimSelect({
    select: '#cmbProducto',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Producto").modal("toggle");
      cargarCMBImpuestos("1", "cmbImpuestos");
      cargarCMBTasaImpuestos("1","cmbTasaImpuestos");
      return;
    }
  });

  SS_sucursal = new SlimSelect({
    select: '#cmbDireccionEnvio',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Locacion").modal("toggle");
      return;
    }
  });

  SS_vendedor = new SlimSelect({
    select: '#cmbVendedor',
    placeholder: 'Seleccione un vendedor...',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_Empleado").modal("toggle");
      return;
    }
  });

  new SlimSelect({
    select: '#cmbCondicionPago',
    placeholder: 'Seleccione una condición ...',
    deselectLabel: '<span class="">✖</span>',
  });

  var SS_direccion = new SlimSelect({
    select: '#cmbDireccionEntrega',
    deselectLabel: '<span class="">✖</span>',
    addable: function (value) {
      $("#agregar_direccion_50").modal("toggle");
      return;
    }
  });

  new SlimSelect({
    select: '#cmbRegimen',
    placeholder: 'Seleccione un régimen...',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbMedioContactoCliente',
    placeholder: 'Seleccione un medio...',
    deselectLabel: '<span class="">✖</span>',
  });

  new SlimSelect({
    select: '#cmbVendedorNC',
    placeholder: 'Seleccione un vendedor...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbPais = new SlimSelect({
    select: '#cmbPais',
    placeholder: 'Seleccione un pais...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbEstado = new SlimSelect({
    select: '#cmbEstado',
    placeholder: 'Seleccione un estado...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbPaisD = new SlimSelect({
    select: '#cmbPaisD',
    placeholder: 'Seleccione un pais...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbEstadoD = new SlimSelect({
    select: '#cmbEstadoD',
    placeholder: 'Seleccione un estado...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_txtarea8 = new SlimSelect({
    select: '#txtarea8',
    placeholder: 'Seleccione un pais...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_txtarea6 = new SlimSelect({
    select: '#txtarea6',
    placeholder: 'Seleccione un estado...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_genero = new SlimSelect({
    select: '#cmbGenero',
    placeholder: 'Seleccione un genero...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbEstado_NE = new SlimSelect({
    select: '#cmbEstado_NE',
    placeholder: 'Seleccione un estado...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbRoles = new SlimSelect({
    select: '#cmbRoles',
    placeholder: 'Seleccione al menos un rol...',
    deselectLabel: '<span class="">✖</span>',
  });

  SS_cmbTipoProducto = new SlimSelect({
    select: '#cmbTipoProducto',
    placeholder: 'Seleccione un tipo...',
    deselectLabel: '<span class="">✖</span>',
  });

  $( "#cmbDireccionEntrega" ).change(function() {
    $("#invalid-direccionEntrega").css("display","none");
    if($('#cmbDireccionEntrega').val() == "add"){
      SS_direccion.set(1);     
      $("#agregar_direccion_50").modal("toggle");
      return;
    }
  });
  

  if (_global.PKVenta != '0'){
    registrarProductosTemporales();
    $.ajax({
      url:"../../php/funciones.php",
      data:{clase:"get_data", funcion:"get_datos_VentaDirectaEdit", data:_global.PKVenta},
      dataType:"json",
      success:function(respuesta){
        if(respuesta[0].estatus_factura_id == "1" || respuesta[0].estatus_factura_id == "2" || respuesta[0].estatus_factura_id == "9" || respuesta[0].estatus_factura_id == "10"){
          loadComboCopeado(respuesta[0].FKCliente,'cmbCliente','cliente','','cliente');
          loadComboCopeado(respuesta[0].FKSucursal,'cmbDireccionEnvio','sucursal','','sucursal');    
          cambioClienteCopeado(respuesta[0].FKCliente);
          cargarCMBVendedorCopeado(respuesta[0].Vendedor,"cmbVendedor");
          cargarCMBCondicionPago(respuesta[0].CondicionPago, "cmbCondicionPago");       
          if(respuesta[0].direccionEntrega != null){
            cargarCMBDireccionesEnvioCopeado(respuesta[0].direccionEntrega, "cmbDireccionEntrega", respuesta[0].FKCliente);
          }else{
            cargarCMBDireccionesEnvio(respuesta[0].FKCliente, "cmbDireccionEntrega");
          }
  
          _global.pkSuc = respuesta[0].FKSucursal; 
          
          $('#NotasCliente').val(respuesta[0].NotasCliente);
          $('#NotasInternas').val(respuesta[0].NotasInternas);
  
          estatusOP = respuesta[0].EstatusOP;
          isInventario = respuesta[0].IsInventario
          isServicio = respuesta[0].isServicio
  
          cargarTablaVentasDirectasTemp();
        }else{
          loadCombo('','cmbCliente','cliente','','cliente');
          loadCombo('','cmbDireccionEnvio','sucursal','','sucursal');
          obtenerTotal();
          cargarCMBCondicionPago("", "cmbCondicionPago");
        }
      },
      error:function(error){
        console.log(error);
      },
      complete: function(_, __) {
        validate_Permissions(13);
      }
    });

    
    obtenerTotal(); 
  }else{  
    loadCombo('','cmbCliente','cliente','','cliente');
    loadCombo('','cmbDireccionEnvio','sucursal','','sucursal');
    obtenerTotal();
    cargarCMBCondicionPago("", "cmbCondicionPago");
  }
});

function recargarReferencia(){
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_referencia"},
    dataType:"json",
    
    success:function(respuesta){
      if(respuesta.indexOf("Object")>-1){
        recargarReferencia();
      }else{
        $('#txtReferencia').val(respuesta);
      }
    },
    error:function(error){
      console.log(error);
    }
  });
}

function cargarCMBVendedor(data, input) {
  var html = "";
  var vendedor;
  if(data != null && data != ''){
    setTimeout(function(){
      $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_VendedorCliente",
            data: data,
          },
          dataType: "json",
          success: function (data) {

            vendedor = data[0].empleado_id;
          },
        })
      },100
    );
  }

  setTimeout(function(){
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

          html +='<option value="add" style="background-color: #15589B; color:white; text-align:center; width:100%;">Añadir empleado</option>';
        
          $("#" + input + "").html(html);
          $("#" + input + "").val(vendedor);
        },
        error: function (error) {
          console.log(error);
        },
      });
    },200
  );
}

function cargarCMBVendedorCopeado(data, input) {
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

function cargarCMBDireccionesEnvio(data, input) {
  var html = "";
  var selected, direccionEnvio;
  if(data != null && data != ''){
    setTimeout(function(){
      $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_DireccionesEnviosCliente",
            data: data,
          },
          dataType: "json",
          success: function (data) {
            direccionEnvio = data[0].idPredeterminada;
          },
        })
      },100
    );
  }
  
  setTimeout(function(){
      $.ajax({
        url: "../../php/funciones.php",
        data: { clase: "get_data", funcion: "get_cmb_direccionesEnvio", data: data },
        dataType: "json",
        success: function (respuesta) {
          html += '<option data-placeholder="true"></option>';
          if(respuesta.pop() != 6){
            html += '<option value="1">Pendiente de confirmar</option>';
          }
          $.each(respuesta, function (i) {
            if(respuesta[i].sucursal.substr(-4) == " -  "){
              html +=
              `<option value="${respuesta[i].id}">${respuesta[i].sucursal+"Desconocido"}</option>`;
            }else{
              html +=
              `<option value="${respuesta[i].id}">${respuesta[i].sucursal}</option>`;  
            }
          });

          html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Agregar dirección</option>';

          $("#" + input + "").html(html);
          $("#" + input + "").val(direccionEnvio);
        },
        error: function (error) {
          console.log(error);
        },
      });
    },200
  );
}

function cargarCMBDireccionesEnvioCopeado(data, input, cliente) {
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

$(document).on("click","#chkAfectarInventario",(e)=>{
    const target = e.target;
    const table = $("#tblListadoVentasDirectasTemp").DataTable();
    if(table.data().count() > 0){
      $("#alert_table_void").modal("show");
      $(document).on("shown.bs.modal","#alert_table_void",()=>{
        const target = document.getElementById('chkAfectarInventario');
        const table = $("#tblListadoVentasDirectasTemp").DataTable();
        document.getElementById('btnAgregar_table_void').addEventListener('click',()=>{
          table.clear().draw();
          eliminarVentaTemp();
          obtenerTotal();
          $("#alert_table_void").modal("hide");
          $.ajax({
            url:"../../php/funciones.php",
            data:{clase:"get_data", funcion:"get_referencia"},
            dataType:"json",
            
            success:function(respuesta){
              if(respuesta.indexOf("Object")>-1){
        
              }else{
                $('#txtReferencia').val(respuesta);
              }
            },
            error:function(error){
              console.log(error);
            }
          });
        });
          
        document.getElementById('btnCancelar_table_void').addEventListener('click',()=>{
          if(target.checked){
            target.checked = false;
          } else {
            target.checked = true;
          }
          $("#alert_table_void").modal("hide");
        });
      });
    }
});

function eliminarVentaTemp(){
  var pkUs= $("#txtUsuario").val();
  $.ajax({
    url:"../../php/funciones.php",
    data:{clase:"delete_data", funcion:"delete_VentaDirectaTempAll", data:pkUs},
    dataType:"json",
    success:function(respuesta){
      //$('#txtReferencia').val(respuesta);
    },
    error:function(error){
      console.log(error);
    }
  });

}

$(document).on("click", "#mostrarTodos", function () {
  if (_global.PKVenta != 0){
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
      $("#txtPrecioUnitario").prop("disabled",false);
      loadComboCopeado('','cmbProducto','producto',valor,'todoProducto');
    }else{
      $("#txtPrecioUnitario").prop("disabled",false);
      loadComboCopeado('','cmbProducto','producto',valor,'producto');
    }
  }else{
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
      $("#txtPrecioUnitario").prop("disabled",false);
      loadCombo('','cmbProducto','producto',valor,'todoProducto');
    }else{
      $("#txtPrecioUnitario").prop("disabled",false);
      loadCombo('','cmbProducto','producto',valor,'producto');
    }
  }
});

function cargarCMBRegimen(input) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_regimen" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta de los régimenes fiscales: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {

        html +=
          '<option value="' +
          respuesta[i].id +
          '" ' +
          ">" +
          respuesta[i].clave +
          ' - ' +
          respuesta[i].descripcion +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBVendedorNC(input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_vendedor" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta vendedor: ", respuesta);

      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        /* if (data === respuesta[i].PKVendedor) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKVendedor +
          '" ' +
          ">" +
          respuesta[i].Nombre +
          "</option>";
      });

      /*html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar vendedores</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBMedioContactoCliente(input) {
  var html = "";
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_mediosContacto" },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';

      $.each(respuesta, function (i) {
        /* if (data === respuesta[i].PKMedioContactoCliente) {
          selected = "selected";
        } else {
          selected = "";
        } */

        html +=
          '<option value="' +
          respuesta[i].PKMedioContactoCliente +
          '" ' +
          ">" +
          respuesta[i].MedioContactoCliente +
          "</option>";
      });

      /*html +=
        '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Configurar medios de contacto</option>';*/

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("change", "#cmbPais", function(){
  let html = "";
  let PKPais = $("#cmbPais").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", pais: PKPais },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#cmbEstado").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("change", "#cmbPaisD", function(){
  let html = "";
  let PKPais = $("#cmbPaisD").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", pais: PKPais },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#cmbEstadoD").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

$(document).on("change", "#txtarea8", function(){
  let html = "";
  let PKPais = $("#txtarea8").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_estados", pais: PKPais },
    dataType: "json",
    success: function (respuesta) {
      html += '<option data-placeholder="true"></option>';
      $.each(respuesta, function (i) {
        html +=
          '<option value="' +
          respuesta[i].PKEstado +
          '" ' +
          ">" +
          respuesta[i].Estado +
          "</option>";
      });

      $("#txtarea6").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
});

//función para mostrar/ocultar campos del modal para añadir un cliente, cuando se marca el check de cliente para facturar
function valida_check(sender){
  if (sender.checked) {
    $('.DataClient_invoice').css({'display': 'inline-block','visibility': 'visible','opacity': '1','animation': 'fade 1s'});
  } else {
    $('.DataClient_invoice').css({'display': 'none','opacity': '0','visibility': 'hidden'});
  }
}

function escribirNombre() {
  var valor = $("#txtNombreComercial").val();
  $.ajax({
    url: "../../../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_nombreComercial",
      data: valor,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-nombreCom").css("display", "block");
        $("#invalid-nombreCom").text(
          "El nombre ya esta registrado en el sistema."
        );
        $("#txtNombreComercial").addClass("is-invalid");
        console.log("¡Ya existe!");
      } else {
        $("#invalid-nombreCom").css("display", "none");
        $("#invalid-nombreCom").text(
          "El cliente debe tener un nombre comercial."
        );
        $("#txtNombreComercial").removeClass("is-invalid");
        console.log("¡No existe!");
        if (!valor) {
          $("#invalid-nombreCom").css("display", "block");
          $("#invalid-nombreCom").text(
            "El cliente debe tener un nombre comercial."
          );
          $("#txtNombreComercial").addClass("is-invalid");
        }
      }
    },
  });
}

function escribirRazonSocial() {
  var valor = $("#txtRazonSocial").val();
  var valorHis = $("#txtRazonSocialHis").val();

  if (valor != valorHis) {
    console.log("Valor nombre" + valor);
    $.ajax({
      url: "../../../clientes/php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_razonSocial_Cliente",
        data: valor,
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta nombre valida: ", data);
        /* Validar si ya existe el identificador con ese nombre*/
        if(parseInt(data[0]["existe"]) == 1){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "La razón social ya esta registrada en el sistema."
          );
          $("#txtRazonSocial").addClass("is-invalid");
          console.log("¡Ya existe!");
        }else if(!valor){
          $("#invalid-razon").css("display", "block");
          $("#invalid-razon").text(
            "El cliente debe tener una razón social."
          );
          $("#txtRazonSocial").addClass("is-invalid");
        }else{
          $("#invalid-razon").css("display", "none");
          $("#invalid-razon").text(
            "El cliente debe tener una razón social."
          );
          $("#txtRazonSocial").removeClass("is-invalid");
          console.log("¡No existe!");
        }
      },
    });
  }

  var razonSocial = $("#txtRazonSocial").val().toLowerCase();
  if(razonSocial.endsWith(' s.a. de c.v.') || razonSocial.endsWith(' sa de cv') || razonSocial.endsWith(' s.a.') || razonSocial.endsWith(' sa') || razonSocial.endsWith(' sociedad anónima') || razonSocial.endsWith(' sociedad anonima') || razonSocial.endsWith(' s. de r.l.') || razonSocial.endsWith(' s de rl') || razonSocial.endsWith(' sociedad de responsabilidad limitada') || razonSocial.endsWith(' s. en c') || razonSocial.endsWith(' s en c') || razonSocial.endsWith(' sociedad en comandita') || razonSocial.endsWith(' socidad civil')){
    $("#txtRazonSocial").addClass("is-invalid");
    $("#invalid-razonTipoSociedad").css("display", "block");
  }else{
    $("#txtRazonSocial").removeClass("is-invalid");
    $("#invalid-razonTipoSociedad").css("display", "none");
  }
}

function validarCorreo(value, inpt, invalid_card) {
  var reg =
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  var regOficial =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;

  //Se muestra un texto a modo de ejemplo, luego va a ser un icono
  if (reg.test(value) && regOficial.test(value)) {
    $("#"+invalid_card).css("display", "none");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).removeClass("is-invalid");
  } else if (reg.test(value)) {
    $("#"+invalid_card).css("display", "none");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).removeClass("is-invalid");
  } else {
    $("#"+invalid_card).css("display", "block");
    $("#"+invalid_card).text("E-mail inválido.");
    $("#"+inpt).addClass("is-invalid");
  }
}

function validarCP(inpt, invalid_card) {
  var value = $("#"+inpt).val();
  var ercp = /(^([0-9]{5,5})|^)$/;
  $.ajax({
    url: "../../../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "valid_cp",
      data: value
    },
    dataType: "json",
    success: function (respuesta) {
      if (!ercp.test(value) || !value || respuesta == false) {
        $("#"+invalid_card).css("display", "block");
        $("#"+invalid_card).text("El CP ingresado no es valido.");
        $("#"+inpt).addClass("is-invalid");
      } else {
        $("#"+invalid_card).css("display", "none");
        $("#"+invalid_card).text("El codigo postal.");
        $("#"+inpt).removeClass("is-invalid");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cambioCliente(){
  $('#chkcmbTodoProducto').prop('checked',false);
  $("#chkcmbTodoProducto").prop("disabled",false);
  $('#mostrarTodos').prop('checked',false);
  $("#mostrarTodos").prop("disabled",false);

  var valor = $('#cmbCliente').val();
  if(valor == "add"){
    SS_cliente.set(0);
    cargarCMBRegimen('cmbRegimen');
    cargarCMBVendedorNC("cmbVendedorNC");
    cargarCMBMedioContactoCliente("cmbMedioContactoCliente");
    $("#agregar_Cliente_50").modal("toggle");
    return;
  }
  loadCombo('','cmbProducto','producto',valor,'producto');
  cargarCMBVendedor(valor, "cmbVendedor");
  cargarCMBDireccionesEnvio(valor, "cmbDireccionEntrega");
  $('#chkcmbTodoProducto').on('change',function(){
    if(this.checked){
      Swal.fire(
        "Al cliente seleccionado no se le venden los productos listados",
        "Para agregarlos a la lista de los productos que se le venden, favor completar los campos.",
        "success"
      );
      $("#txtPrecioUnitario").prop("disabled",false);
      loadCombo('','cmbProducto','producto',valor,'todoProducto');
    }else{
      $("#txtPrecioUnitario").prop("disabled",false);
      loadCombo('','cmbProducto','producto',valor,'producto');
    }
  });
  loadCombo('','cmbProducto','producto',valor);
  $('#cmbProducto').on('change',function(){
    var prod = $('#cmbProducto').val();
    if(prod == "add" || prod == null){
      return;
    }
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

function validInput(inputID, invalidDivID, textInvalidDiv) {
  if (!$("#" + inputID).val() || $("#" + inputID).val() == 0) {
    $("#" + inputID).addClass("is-invalid");
    $("#" + invalidDivID).css("display", "block");
    $("#" + invalidDivID).text(textInvalidDiv);
  } else {
    $("#" + inputID).removeClass("is-invalid");
    $("#" + invalidDivID).css("display", "none");
    $("#" + invalidDivID).text(textInvalidDiv);
    if(inputID == 'txtRFC'){
        let vRFC = $("#txtRFC").val()
        let rfc = vRFC.trim().toUpperCase();
        let rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba
        if (rfcCorrecto) {
          $("#invalid-rfc").css("display", "none");
          $("#invalid-rfc").text("El cliente debe tener un RFC.");
          $("#txtRFC").removeClass("is-invalid");
          escribirRFC();
        } else {
          $("#invalid-rfc").css("display", "block");
          $("#invalid-rfc").text("El RFC ingresado no es valido.");
          $("#txtRFC").addClass("is-invalid");
        }
    }
  }
}

function rfcValido(rfc, aceptarGenerico = true) {
  const re =
    /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
  var validado = rfc.match(re);

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

function escribirRFC() {
  let rfc = $("#txtRFC").val().trim();
  let rfcHis = $("#txtRFCHis").val();

  if (rfc != rfcHis) {
    $.ajax({
      url: "../../../clientes/php/funciones.php",
      data: {
        clase: "get_data",
        funcion: "validar_rfc_Cliente",
        data: rfc
      },
      dataType: "json",
      success: function (data) {
        console.log("respuesta RFC validado: ", data);
        // Validar si ya existe el identificador con ese nombre
        if (parseInt(data.existe) == 1) {
          $("#invalid-rfc").css("display", "block");
          $("#invalid-rfc").text("El RFC ingresado ya existe en el sistema.");
          $("#txtRFC").addClass("is-invalid");
        } else {
          $("#invalid-rfc").css("display", "none");
          $("#invalid-rfc").text("El cliente debe tener un RFC.");
          $("#txtRFC").removeClass("is-invalid");
        }
      },
    });
  }
}

function resetForm(frm){
  form=document.getElementById(frm);
  form.reset();

  if(frm == "agregarDireccionCL"){
    $("#invalid-sucursalD").css("display", "none");
    $("#txtSucursalD").removeClass("is-invalid");

    $("#invalid-emailDire").css("display", "none");
    $("#txtEmailD").removeClass("is-invalid");

    $("#invalid-numExt").css("display", "none");
    $("#txtNumExt").removeClass("is-invalid");

    $("#invalid-txtMunicipio").css("display", "none");
    $("#municipioDire").removeClass("is-invalid");

    $("#invalid-colonia").css("display", "none");
    $("#txtColonia").removeClass("is-invalid");

    SS_cmbPaisD.set();
    $("#invalid-paisDire").css("display", "none");
    $("#cmbPaisD").removeClass("is-invalid");

    SS_cmbEstadoD.set();
    $("#invalid-estadoDire").css("display", "none");
    $("#cmbEstadoD").removeClass("is-invalid");

    $("#invalid-cpDire").css("display", "none");
    $("#txtCPD").removeClass("is-invalid");

    $("#invalid-calleDire").css("display", "none");
    $("#txtCalle").removeClass("is-invalid");

    
  }else if(frm == "agregarCliente"){
    $('.DataClient_invoice').css({'display': 'none','opacity': '0','visibility': 'hidden'});

    $("#cmbMedioContactoCliente").trigger("change");
    $("#cmbVendedorNC").trigger("change");
    SS_cmbPais.set();
    SS_cmbEstado.set();

    $("#invalid-nombreCom").css("display", "none");
    $("#txtNombreComercial").removeClass("is-invalid");

    /* $("#invalid-medioCont").css("display", "none");
    $("#cmbMedioContactoCliente").removeClass("is-invalid"); */

    /* $("#invalid-vendedorNC").css("display", "none");
    $("#cmbVendedorNC").removeClass("is-invalid"); */

    /* $("#invalid-email").css("display", "none");
    $("#txtEmail").removeClass("is-invalid"); */

    $("#invalid-razon").css("display", "none");
    $("#txtRazonSocial").removeClass("is-invalid");

    $("#invalid-rfc").css("display", "none");
    $("#txtRFC").removeClass("is-invalid");

    $("#cmbRegimen").trigger("change");
    $("#invalid-regimen").css("display", "none");
    $("#cmbRegimen").removeClass("is-invalid");

    $("#invalid-cp").css("display", "none");
    $("#txtCP").removeClass("is-invalid");

    /* $("#invalid-paisFisc").css("display", "none");
    $("#cmbPais").removeClass("is-invalid"); */

    /* $("#invalid-paisEstadoFisc").css("display", "none");
    $("#cmbEstado").removeClass("is-invalid"); */
  }else if(frm == "agregarLocacion"){
    $("#invalid-nombreSuc").css("display", "none");
    $("#txtarea").removeClass("is-invalid");

    SS_txtarea6.set();
    SS_txtarea8.set();

    /* $("#invalid-calleSuc").css("display", "none");
    $("#txtarea2").removeClass("is-invalid");

    $("#invalid-noExtSuc").css("display", "none");
    $("#txtarea3").removeClass("is-invalid");

    $("#invalid-coloniaSuc").css("display", "none");
    $("#txtarea5").removeClass("is-invalid");

    $("#invalid-municipioSuc").css("display", "none");
    $("#txtarea7").removeClass("is-invalid");

    $("#invalid-paisSuc").css("display", "none");
    $("#txtarea8").removeClass("is-invalid");

    $("#invalid-estadoSuc").css("display", "none");
    $("#txtarea6").removeClass("is-invalid");

    $("#invalid-telSuc").css("display", "none");
    $("#txtarea10").removeClass("is-invalid"); */
  }else if(frm == "agregarEmpleado"){
    $("#invalid-nombre").css("display", "none");
    $("#txtNombre").removeClass("is-invalid");

    $("#invalid-primerApellido").css("display", "none");
    $("#txtPrimerApellido").removeClass("is-invalid");

    /* $("#invalid-genero").css("display", "none");
    $("#cmbGenero").removeClass("is-invalid"); */

    $("#invalid-cpE").css("display", "none");
    $("#txtCPE").removeClass("is-invalid");

   /*  $("#invalid-estadoNE").css("display", "none");
    $("#cmbEstado_NE").removeClass("is-invalid");

    $("#invalid-roles").css("display", "none");
    $("#cmbRoles").removeClass("is-invalid"); */

    SS_genero.set();
    SS_cmbEstado_NE.set();
    SS_cmbRoles.set(1);
  }else if(frm == "agregarProductoForm"){
    $("#invalid-nombreProducto").css("display", "none");
    $("#txtProducto").removeClass("is-invalid");

    $("#invalid-clave").css("display", "none");
    $("#txtClave").removeClass("is-invalid");

    SS_cmbTipoProducto.set();
    $("#invalid-tipoProd").css("display", "none");
    $("#cmbTipoProducto").removeClass("is-invalid");
  }
}

function validaNumTelefono(evt, input, invalid_card, resultHidden) {
  var key = window.Event ? evt.which : evt.keyCode;
  if($("#"+input).val()=='' || $("#"+input).val() == null){
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
    return false;
  }
  if (key == 8 || key == 46) {
    $("#"+resultHidden).val($("#"+input).val().length);
    $("#"+resultHidden).addClass("mui--is-not-empty");
    var valor = $("#"+resultHidden).val();
    if (valor < 8 || valor == 9) {
      $("#"+invalid_card).css("display", "block");
      $("#"+input).addClass("is-invalid");
    } else {
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
    }
  } else {
    $("#"+resultHidden).val($("#"+input).val().length);
    $("#"+resultHidden).addClass("mui--is-not-empty");
    var valor = $("#"+resultHidden).val();
    if (valor < 8 || valor == 9) {
      $("#"+invalid_card).css("display", "block");
      $("#"+input).addClass("is-invalid");
    } else {
      $("#"+invalid_card).css("display", "none");
      $("#"+input).removeClass("is-invalid");
      return false;
    }
  }
}

function escribirSucursal() {
  let sucursal = $("#txtSucursalD").val();
  let id = $("#cmbCliente").val();

  $.ajax({
    url: "../../../clientes/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_sucursal_Cliente",
      data: sucursal,
      data2: id,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-sucursalD").css("display", "block");
        $("#invalid-sucursalD").text(
          "La sucursal ya esta registrada en el sistema."
        );
        $("#txtSucursalD").addClass("is-invalid");
      } else {
        $("#invalid-sucursalD").css("display", "none");
        $("#invalid-sucursalD").text(
          "La dirección debe tener un nombre de sucursal."
        );
        $("#txtSucursalD").removeClass("is-invalid");
        if (!sucursal) {
          $("#invalid-sucursalD").css("display", "block");
          $("#invalid-sucursalD").text(
            "La dirección debe tener un nombre de sucursal."
          );
          $("#txtSucursalD").addClass("is-invalid");
        }
      }
    },
  });
}

$(document).on("click", "#btnGenerarClave", function () {
  var categoria = $("#cmbTipoProducto").val();
  var limpieza = "";

  if (categoria == "1") {
    limpieza = "Cmp";
  } else if (categoria == "2") {
    limpieza = "Cns";
  } else if (categoria == "3") {
    limpieza = "MP";
  } else if (categoria == "4") {
    limpieza = "P";
  } else if (categoria == "5") {
    limpieza = "S";
  } else if (categoria == "6") {
    limpieza = "AF";
  } else if (categoria == "7") {
    limpieza = "A";
  } else if (categoria == "8") {
    limpieza = "SI";
  } else if (categoria == "9") {
    limpieza = "EMP";
  } else if (categoria == "10") {
    limpieza = "GF";
  }else {
    limpieza = "N";
  }

  if (limpieza != "N") {
    $.ajax({
      url: "../../../inventarios_productos/php/funciones.php",
      data: { clase: "get_data", funcion: "get_claveReferencia" },
      dataType: "json",
      success: function (respuesta) {
        $("#txtClave").val(limpieza + "" + respuesta);
        $("#txtClave").trigger("change");
      },
      error: function (error) {
        console.log(error);
      },
    });
  } else {
    $("#invalid-tipoProd").css("display", "block");
    $("#invalid-tipoProd").text(
      "Debe de seleccionarse un tipo de producto para generar clave"
    );
    $("#cmbTipoProducto").addClass("is-invalid");
  }
});

$(document).on("click", "#btnAgregarNC", function () {
  if($("#check_clienteFacturar").is(":checked")){
    validInput('txtRazonSocial', 'invalid-razon', 'El cliente debe tener razón social.');
    validInput('txtRFC', 'invalid-rfc', 'El cliente debe tener rfc.');
    validInput('cmbRegimen', 'invalid-regimen', 'El cliente debe tener régimen fiscal.');
    validInput('txtCP', 'invalid-cp', 'El cliente debe tener código postal.');
  }else{
    validInput('txtRazonSocial', 'invalid-razon', 'El cliente debe tener razón social.');
  }

  var badRazon = $("#invalid-razon").css("display") === "block" ? false : true;
  var badRFC = $("#invalid-rfc").css("display") === "block" ? false : true;
  var badRegimen = $("#invalid-regimen").css("display") === "block" ? false : true;
  var badCP = $("#invalid-cp").css("display") === "block" ? false : true;

  if (
    badRazon &&
    badCP &&
    badRFC &&
    badRegimen
  ) {
    $("#btnAgregarNC").prop("disabled", true);
  
    var nombreComercial = '';
    var email = '';
    var razonSocial = $("#txtRazonSocial").val();
    var rfc = '';
    var medioContactoCliente = 1;
    var vendedor = '';
    var pais = 146;
    var estado = 14;
    var cp = 44100;
    var telefono = $("#txtTelefono_Cl").val();
    var regimen = 0;

    $("#txtNombreComercial").val() == "" || !$("#txtNombreComercial").val() || $("#txtNombreComercial").val() == 0 ? nombreComercial = razonSocial : nombreComercial = $("#txtNombreComercial").val();
    $("#txtEmail").val() == "" || !$("#txtEmail").val() || $("#txtEmail").val() == 0? email = "N/A" : email = $("#txtEmail").val();
    $("#txtRFC").val() == "" || !$("#txtRFC").val() || $("#txtRFC").val() == 0 ? rfc = "N/A" : rfc = $("#txtRFC").val();
    $("#cmbMedioContactoCliente").val() == "" || !$("#cmbMedioContactoCliente").val() || $("#cmbMedioContactoCliente").val() == 0 ? medioContactoCliente = 1 : medioContactoCliente = $("#cmbMedioContactoCliente").val();
    $("#cmbVendedorNC").val() == "" || !$("#cmbVendedorNC").val() || $("#cmbVendedorNC").val() == 0 ? vendedor = 0 : vendedor = $("#cmbVendedorNC").val();
    $("#cmbPais").val() == "" || !$("#cmbPais").val() || $("#cmbPais").val() == 0 ? pais = 146 : pais = $("#cmbPais").val();
    $("#cmbEstado").val() == "" || !$("#cmbEstado").val() || $("#cmbEstado").val() == 0 ? estado = 14 : estado = $("#cmbEstado").val();
    $("#txtCP").val() == "" || !$("#txtCP").val() || $("#txtCP").val() == 0 ? cp = 44100 : cp = $("#txtCP").val();
    $("#cmbRegimen").val() == "" || !$("#cmbRegimen").val() || $("#cmbRegimen").val() == 0 ? regimen = 0 : regimen = $("#cmbRegimen").val();

    var estatus =  1;
    var pkRazon = 0;
    var montoCredito = 0;
    var diasCredito = 0; 

    $.ajax({
      url: "../../php/funciones.php",
      data: {
        clase: "save_data",
        funcion: "save_datosCliente",
        datos2: nombreComercial,
        datos3: medioContactoCliente,
        datos4: vendedor,
        datos5: montoCredito,
        datos6: diasCredito,
        datos7: telefono,
        datos8: email,
        datos9: estatus,
        datos10: razonSocial,
        datos11: rfc,
        datos17: pais,
        datos18: estado,
        datos19: cp,
        datos20: pkRazon,
        datos21: regimen
      },
      dataType: "json",
      success: function (respuesta) {
        $("#btnAgregarNC").prop("disabled", false);

        if (respuesta[0].status) {
          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/checkmark.svg",
            msg: "¡Datos generales registrados correctamente!",
            sound: '../../../../../sounds/sound4'
          });
          $("#btnCancelar_newCliente").click();
          loadCombo('','cmbCliente','cliente','','cliente');
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
        $("#btnAgregarNC").prop("disabled", false);
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          //img: '<i class="fas fa-check-circle"></i>',
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "¡Algo salio mal :(!",
          sound: '../../../../../sounds/sound4'
        });
      },
    });
  }

 /* if ($("#agregarCliente")[0].checkValidity()) {
    var badNombreCom =
      $("#invalid-nombreCom").css("display") === "block" ? false : true;
     var badMedioContCli =
      $("#invalid-medioCont").css("display") === "block" ? false : true; 
    var badVendedorCli =
      $("#invalid-vendedorNC").css("display") === "block" ? false : true;
    var badEmailCli =
      $("#invalid-email").css("display") === "block" ? false : true;
    var badRazon =
      $("#invalid-razon").css("display") === "block" ? false : true;
    var badRFC = $("#invalid-rfc").css("display") === "block" ? false : true;
    var badRegimen = $("#invalid-regimen").css("display") === "block" ? false : true;
    var badCP = $("#invalid-cp").css("display") === "block" ? false : true;
    /* var badPais =
      $("#invalid-paisFisc").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-paisEstadoFisc").css("display") === "block" ? false : true; 
    if (
      badNombreCom &&
      badMedioContCli &&
      badVendedorCli &&
      badEmailCli && 
      badRazon &&
      badCP &&
      badRFC &&
      badRegimen &&
      badPais &&
      badEstado 
    ) {
      $("#btnAgregarNC").prop("disabled", true);
    
      var nombreComercial = '';
      var email = '';
      var razonSocial = $("#txtRazonSocial").val();
      var rfc = $("#txtRFC").val();
      var medioContactoCliente = 1;
      var vendedor = $("#cmbVendedorNC").val();
      var pais = 146;
      var estado = 14;
      var cp = 44100;
      var telefono = $("#txtTelefono_Cl").val();

      $("#txtNombreComercial").val() == "" || !$("#txtNombreComercial").val() ? nombreComercial = $("#txtNombreComercial").val() : nombreComercial = "N/A";
      $("#txtEmail").val() == "" || !$("#txtEmail").val() ? email = $("#txtEmail").val() : email = "N/A";
      $("#txtRazonSocial").val() == "" || !$("#txtRazonSocial").val() ? razonSocial = $("#txtRazonSocial").val() : razonSocial = "N/A";
      $("#txtRFC").val() == "" || !$("#txtRFC").val() ? rfc = $("#txtRFC").val() : rfc = "N/A";
      $("#cmbMedioContactoCliente").val() == "" || !$("#cmbMedioContactoCliente").val() ? medioContactoCliente = $("#txtNombreComercial").val() : medioContactoCliente = 1;
      $("#cmbVendedorNC").val() == "" || !$("#cmbVendedorNC").val() ? vendedor = $("#cmbVendedorNC").val() : vendedor = 0;
      $("#cmbPais").val() == "" || !$("#cmbPais").val() ? pais = $("#cmbPais").val() : pais = 146;
      $("#cmbEstado").val() == "" || !$("#cmbEstado").val() ? estado = $("#cmbEstado").val() : estado = 14;
      $("#txtCP").val() == "" || !$("#txtCP").val() ? cp = $("#txtCP").val() : cp = 44100;

      var estatus =  1;
      var pkRazon = 0;
      var montoCredito = 0;
      var diasCredito = 0; 

      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_datosCliente",
          datos2: nombreComercial,
          datos3: medioContactoCliente,
          datos4: vendedor,
          datos5: montoCredito,
          datos6: diasCredito,
          datos8: email,
          datos9: estatus,
          datos10: razonSocial,
          datos11: rfc,
          datos17: pais,
          datos18: estado,
          datos19: cp,
          datos20: pkRazon,
          datos21: regimen
        },
        dataType: "json",
        success: function (respuesta) {
          $("#btnAgregarNC").prop("disabled", false);

          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Datos generales registrados correctamente!",
              sound: '../../../../../sounds/sound4'
            });
            $("#btnCancelar_newCliente").click();
            loadCombo('','cmbCliente','cliente','','cliente');
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
          $("#btnAgregarNC").prop("disabled", false);
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top", //or 'center bottom'
            icon: true,
            //img: '<i class="fas fa-check-circle"></i>',
            img: "../../../../img/timdesk/notificacion_error.svg",
            msg: "¡Algo salio mal :(!",
            sound: '../../../../../sounds/sound4'
          });
        },
      });
    }
  } else {
     if (!$("#txtNombreComercial").val()) {
      $("#invalid-nombreCom").css("display", "block");
      $("#txtNombreComercial").addClass("is-invalid");
    } 
     if (!$("#cmbMedioContactoCliente").val()) {
      $("#invalid-medioCont").css("display", "block");
      $("#cmbMedioContactoCliente").addClass("is-invalid");
    }
    if (!$("#cmbVendedorNC").val()) {
      $("#invalid-vendedorNC").css("display", "block");
      $("#cmbVendedorNC").addClass("is-invalid");
    }
    if (!$("#txtEmail").val()) {
      $("#invalid-email").css("display", "block");
      $("#txtEmail").addClass("is-invalid");
    } 
    if (!$("#txtRFC").val()) {
      $("#invalid-rfc").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#cmbRegimen").val()) {
      $("#invalid-regimen").css("display", "block");
      $("#txtRFC").addClass("is-invalid");
    }
    if (!$("#txtRazonSocial").val()) {
      $("#invalid-razon").css("display", "block");
      $("#txtRazonSocial").addClass("is-invalid");
    }
    if (!$("#txtCP").val()) {
      $("#invalid-cp").css("display", "block");
      $("#txtCP").addClass("is-invalid");
    }
     if (!$("#cmbPais").val()) {
      $("#invalid-paisFisc").css("display", "block");
      $("#cmbPais").addClass("is-invalid");
    }
    if (!$("#cmbEstado").val()) {
      $("#invalid-paisEstadoFisc").css("display", "block");
      $("#cmbEstado").addClass("is-invalid");
    } 
  }*/
});

$(document).on("click", "#btnAgregarD", function () {
  if ($("#agregarDireccionCL")[0].checkValidity()) {
    var badSucursal =
      $("#invalid-sucursalD").css("display") === "block" ? false : true;
    var badCalle =
      $("#invalid-calleDire").css("display") === "block" ? false : true;
    var badNumExt =
      $("#invalid-numExt").css("display") === "block" ? false : true;
    var badEmail =
      $("#invalid-emailDire").css("display") === "block" ? false : true;
    var badColonia =
      $("#invalid-colonia").css("display") === "block" ? false : true;
    var badMunicipio = 
      $("#invalid-municipioDire").css("display") === "block" ? false : true;
    var badCP = 
      $("#invalid-cpDire").css("display") === "block" ? false : true;
    var badPais =
      $("#invalid-paisDire").css("display") === "block" ? false : true;
    var badEstado =
      $("#invalid-estadoDire").css("display") === "block" ? false : true;
    if (
      badSucursal &&
      badCalle &&
      badNumExt &&
      badEmail &&
      badColonia &&
      badCP &&
      badMunicipio &&
      badPais &&
      badEstado
    ) {
      if(!$("#cmbCliente").val() || $("#cmbCliente").val() == 0 || $("#cmbCliente").val() == ''){
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: "¡No se ha seleccionado un cliente!",
          sound: '../../../../../sounds/sound4'
        });
        return;
      }

      $("#btnAgregarD").prop("disabled", true);

      var sucursal = $("#txtSucursalD").val();
      var email = $("#txtEmailD").val();
      var calle = $("#txtCalle").val();
      var numExt = $("#txtNumExt").val();
      var numInt = "S/N";
      var colonia = $("#txtColonia").val();
      var municipio = $("#txtMunicipio").val();
      var pais = $("#cmbPaisD").val();
      var estado = $("#cmbEstadoD").val();
      var cp = $("#txtCPD").val();
      var pkDireccion = 0;
      var contacto = '';
      var telefono = '';

      $.ajax({
        url: "../../../clientes/php/funciones.php",
        data: {
          clase: "save_data",
          funcion: "save_direccionEnvio_Cliente",
          datos: sucursal,
          datos3: email,
          datos4: calle,
          datos5: numExt,
          datos6: numInt,
          datos7: colonia,
          datos8: municipio,
          datos9: pais,
          datos10: estado,
          datos11: cp,
          datos12: $("#cmbCliente").val(),
          datos13: pkDireccion,
          datos14: contacto,
          datos15: telefono,
        },
        dataType: "json",
        success: function (respuesta) {
          $("#btnAgregarD").prop("disabled", false);

          if (respuesta[0].status) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Se guardó la dirección de envío con éxito!",
              sound: '../../../../../sounds/sound4'
            });
            $("#btnCancelar_newDire").click();
            cargarCMBDireccionesEnvio($("#cmbCliente").val(), "cmbDireccionEntrega");
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/notificacion_error.svg",
              msg: "¡No se guardó la dirección de envío con éxito :(!",
              sound: '../../../../../sounds/sound4'
            });
          }
        },
        error: function (error) {
          console.log(error);
          $("#btnAgregarD").prop("disabled", false);
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
    }
  } else {
    console.log("Faltan campos");
    if (!$("#txtSucursalD").val()) {
      $("#invalid-sucursalD").css("display", "block");
      $("#txtSucursalD").addClass("is-invalid");
    }
    if (!$("#txtEmailD").val()) {
      $("#invalid-emailDire").css("display", "block");
      $("#txtEmailD").addClass("is-invalid");
    }
    if (!$("#txtCalle").val()) {
      $("#invalid-calleDire").css("display", "block");
      $("#txtCalle").addClass("is-invalid");
    }
    if (!$("#txtNumExt").val()) {
      $("#invalid-numExt").css("display", "block");
      $("#txtNumExt").addClass("is-invalid");
    }
    if (!$("#txtColonia").val()) {
      $("#invalid-colonia").css("display", "block");
      $("#txtColonia").addClass("is-invalid");
    }
    if (!$("#txtMunicipio").val()) {
      $("#invalid-municipioDire").css("display", "block");
      $("#txtMunicipio").addClass("is-invalid");
    }
    if (!$("#cmbPaisD").val()) {
      $("#invalid-paisDire").css("display", "block");
      $("#cmbPaisD").addClass("is-invalid");
    }
    if (!$("#cmbEstadoD").val()) {
      $("#invalid-estadoDire").css("display", "block");
      $("#cmbEstadoD").addClass("is-invalid");
    }
    if (!$("#txtCPD").val()) {
      $("#invalid-cpDire").css("display", "block");
      $("#txtCPD").addClass("is-invalid");
    }
  }
});

//los 3 combos de josias.
$(document).on("click","#btnAgregarLocacion",function () {  
  if ($("#agregarLocacion")[0].checkValidity()) {
    var badNombreSuc =
      $("#invalid-nombreSuc").css("display") === "block" ? false : true;
    /* var badCalleSuc =
      $("#invalid-calleSuc").css("display") === "block" ? false : true;
    var badNoExtSuc =
      $("#invalid-noExtSuc").css("display") === "block" ? false : true;
    var badColoniaSuc =
      $("#invalid-coloniaSuc").css("display") === "block" ? false : true;
    var badMunicipioSuc =
      $("#invalid-municipioSuc").css("display") === "block" ? false : true;
    var badPaisSuc =
      $("#invalid-paisSuc").css("display") === "block" ? false : true;
    var badEstadoSuc =
      $("#invalid-estadoSuc").css("display") === "block" ? false : true;
    var badTelSuc =
      $("#invalid-telSuc").css("display") === "block" ? false : true; */
    if (
      badNombreSuc /* &&
      badCalleSuc &&
      badNoExtSuc &&
      badColoniaSuc &&
      badMunicipioSuc &&
      badPaisSuc &&
      badEstadoSuc &&
      badTelSuc */
    ) {
      $("#btnAgregarLocacion").prop("disabled", true);

      var estado = 14;
      var nombreSucursal = $("#txtarea").val().trim();
      var calle = '';
      var numExterior = '';
      var prefijo = '';
      var numInterior = '';
      var colonia = 'N/A';
      var municipio = 'N/A';
      var pais = 146;
      var telefono = 'N/A';
      var actInventario = 0;
      var zonaSalarioMinimo = $("#radioZonaSalarioMinimo").val();

      $("#txtarea6").val() == "" || !$("#txtarea6").val() || $("#txtarea6").val() == 0 ? estado = 14 : estado = $("#txtarea6").val();
      $("#txtarea2").val() == "" || !$("#txtarea2").val() || $("#txtarea2").val() == 0 ? calle = 'N/A' : calle = $("#txtarea2").val().trim();
      $("#txtarea3").val() == "" || !$("#txtarea3").val() || $("#txtarea3").val() == 0 ? numExterior = '' : numExterior = $("#txtarea3").val().trim();
      $("#txtarea9").val() == "" || !$("#txtarea9").val() || $("#txtarea9").val() == 0 ? prefijo =  '' : prefijo = $("#txtarea9").val().trim();
      $("#txtarea4").val() == "" || !$("#txtarea4").val() || $("#txtarea4").val() == 0 ? numInterior = '' : numInterior = $("#txtarea4").val().trim();
      $("#txtarea5").val() == "" || !$("#txtarea5").val() || $("#txtarea5").val() == 0 ? colonia = 'N/A' : colonia = $("#txtarea5").val().trim();
      $("#txtarea7").val() == "" || !$("#txtarea7").val() || $("#txtarea7").val() == 0 ? municipio = 'N/A' : municipio = $("#txtarea7").val().trim();
      $("#txtarea8").val() == "" || !$("#txtarea8").val() || $("#txtarea8").val() == 0 ? pais = 146 : pais = $("#txtarea8").val().trim();
      $("#txtarea10").val() == "" || !$("#txtarea10").val() || $("#txtarea10").val() == 0 ? telefono = 'N/A' : telefono = $("#txtarea10").val().trim();


      if ($("#cbxActivarInventario").is(":checked")) {
        actInventario = 1;
      } else {
        actInventario = 0;
      }

      $.ajax({
        url: "../../../cotizaciones/functions/agregar_Locacion.php",
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
          zonaSalarioMinimo: zonaSalarioMinimo
        },
        success: function (data, status, xhr) {
          $("#btnAgregarLocacion").prop("disabled", false);

          if (data.trim() == "exito") {
            $("#agregar_Locacion").modal("toggle");
            $("#agregarLocacion").trigger("reset");
            loadCombo('','cmbDireccionEnvio','sucursal','','sucursal');
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Sucursal agregada correctamente!",
            });
          } else {
            Lobibox.notify("warning", {
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
        error: function (error) {
          console.log(error);
          $("#btnAgregarLocacion").prop("disabled", false);
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
      
    }
  } else {
    if (!$("#txtarea").val()) {
      $("#invalid-nombreSuc").css("display", "block");
      $("#txtarea").addClass("is-invalid");
    }/* 
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
    } */
  }
});

$(document).on("click", "#btnAgregarPersonal", function () {
  if (!$("#txtNombre").val()) {
    $("#invalid-nombre").css("display", "block");
    $("#txtNombre").addClass("is-invalid");
  }
  if (!$("#txtPrimerApellido").val()) {
    $("#invalid-primerApellido").css("display", "block");
    $("#txtPrimerApellido").addClass("is-invalid");
  }
  /* if (!$("#cmbGenero").val()) {
    $("#invalid-genero").css("display", "block");
    $("#cmbGenero").addClass("is-invalid");
  }
  if (!$("#cmbRoles").val()) {
    $("#invalid-roles").css("display", "block");
    $("#cmbRoles").addClass("is-invalid");
  } 
  if (!$("#txtCPE").val()) {
    $("#invalid-cpE").css("display", "block");
    $("#txtCPE").addClass("is-invalid");
  }
   if (!$("#cmbEstado_NE").val()) {
    $("#invalid-estadoNE").css("display", "block");
    $("#cmbEstado_NE").addClass("is-invalid");
  } */

  var badNombreEmp =
    $("#invalid-nombre").css("display") === "block" ? false : true;
  var badPaternoEmp =
    $("#invalid-primerApellido").css("display") === "block" ? false : true;
  /* var badGeneroEmp =
    $("#invalid-genero").css("display") === "block" ? false : true;
  var badRolInicEmp =
    $("#invalid-roles").css("display") === "block" ? false : true; */
  var badCP = 
    $("#invalid-cpE").css("display") === "block" ? false : true;
  /* var badEstado = 
    $("#invalid-estadoNE").css("display") === "block" ? false : true; */

  if (
    badNombreEmp &&
    badPaternoEmp &&
    /* badGeneroEmp &&
    badRolInicEmp && */ 
    badCP /* &&
    badEstado */
  ) {
    $("#btnAgregarPersonal").prop("disabled", true);

    var nombre = $("#txtNombre").val().trim();
    var apellido = $("#txtPrimerApellido").val().trim();
    var genero = '';
    var roles = '' 
    var estado = 14 
    var cp = ''

    $("#cmbGenero").val() == "" || !$("#cmbGenero").val() || $("#cmbGenero").val() == 0 ? genero = 'N/A' : genero = $("#cmbGenero").val().trim();
    $("#cmbRoles").val() == "" || !$("#cmbRoles").val() || $("#cmbRoles").val() == 0 ? roles = 1 : roles = $("#cmbRoles").val();
    $("#cmbEstado_NE").val() == "" || !$("#cmbEstado_NE").val() || $("#cmbEstado_NE").val() == 0 ? estado = 14 : estado = $("#cmbEstado_NE").val().trim();
    $("#txtCPE").val() == "" || !$("#txtCPE").val() || $("#txtCPE").val() == 0 ? cp = 44100 : cp = $("#txtCPE").val().trim();


    $.ajax({
      url: "../../../cotizaciones/functions/agregarEmpleado.php",
      data: {
        nombre: nombre,
        apellido: apellido,
        genero: genero,
        roles: roles,
        estado: estado,
        cp: cp
      },
      success: function (data, status, xhr) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Empleado Registrado con exito!",
        });
        $("#btnAgregarPersonal").prop("disabled", false);
        $("#btnCancelar_newEmpleado").click();
        cargarCMBVendedor($('#cmbCliente').val(), "cmbVendedor");
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
        $("#btnAgregarPersonales").prop("disabled", false);
      },
    });
  }
});

$(document).on("change", "#cmbDireccionEnvio", function(){
  if($('#cmbDireccionEnvio').val() == "add"){
    SS_sucursal.set(0);     
    $("#agregar_Locacion").modal("toggle");
    return;
  }
});

$(document).on("change", "#cmbVendedor", function(){
  if($('#cmbVendedor').val() == "add"){
    SS_vendedor.set();     
    $("#agregar_Empleado").modal("toggle");
    return;
  }
});

$(document).on("change", "#cmbProducto", function(){
  if($('#cmbProducto').val() == "add"){
    SS_producto.set(0);     
    $("#agregar_Producto").modal("toggle");
    cargarCMBImpuestos("1", "cmbImpuestos");
    cargarCMBTasaImpuestos("1","cmbTasaImpuestos");
    return;
  }
});

$(document).on("click", "#btnAgregarProducto", function () {
  if ($("#agregarProductoForm")[0].checkValidity()) {
    var badProducto =
      $("#invalid-nombreProducto").css("display") === "block" ? false : true;
    var badClave =
      $("#invalid-clave").css("display") === "block" ? false : true;
    var badTipo =
      $("#invalid-tipoProd").css("display") === "block" ? false : true;
    var badExistencia =
      $("#invalid-costoFabrProd").css("display") === "block" ? false : true;
    if (
      badProducto &&
      badClave &&
      badTipo &&
      badExistencia
    ) {
     // $("#btnAgregarProducto").prop("disabled", true);

      var producto = $("#txtProducto").val().trim();
      var clave = $("#txtClave").val().trim();
      var tipo = $("#cmbTipoProducto").val().trim();
      var cliente = 0;
      let contadorImpuesto = 0;

      if($("#cmbCliente").val() != null && $("#cmbCliente").val() != '' && $("#cmbCliente").val() != 0){
        cliente = $("#cmbCliente").val();
      }
      
      let existenciaFabricacion = $("#txtCostoUniFabri").val();
      let unidadSAT = $("#txtIDUnidadSATAAA").val();
      let idSucursal = $("#cmbDireccionEnvio").val();

      if(unidadSAT == null || unidadSAT == ''){
        unidadSAT = 1;
      }

      var idImpuestosArray = {};
      var tasaImpuestosArray = {};
      $.each($("#agregarProductoForm").serializeArray(), function (i, element) {

        if(element.name.substring(0, 11) == 'idimpuesto_'){
          idImpuestosArray[element.name] = element.value;
          contadorImpuesto++;
        }

        if(element.name.substring(0, 13) == 'tasaimpuesto_'){
          tasaImpuestosArray[element.name] = element.value;
        }

      });


      if (!$("#txtProducto").val()) {
        console.log('producto');
        $("#txtProducto")[0].reportValidity();
        $("#txtProducto")[0].setCustomValidity("Completa este campo.");
        return;
      } else if (!$("#txtClave").val()) {
        console.log('clave');
        $("#txtClave")[0].reportValidity();
        $("#txtClave")[0].setCustomValidity("Completa este campo.");
        return;
      } else if(!$("#cmbTipoProducto").val()){
        $("#cmbTipoProducto")[0].reportValidity();
        $("#cmbTipoProducto")[0].setCustomValidity("Completa este campo.");
      }else {
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_datosProd",
            is_from_Facturacion: 0,
            nombre: producto,
            clave: clave,
            tipo: tipo,
            cliente:cliente,
            existenciaFabricacion: existenciaFabricacion,
            unidadSat: unidadSAT,
            idSucursal: idSucursal,
            idImpuestosArray: idImpuestosArray,
            tasaImpuestosArray: tasaImpuestosArray
          },
          success: function (data, status, xhr) {
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: true,
              //img: '<i class="fas fa-check-circle"></i>',
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Producto agregado con exito!",
            });
            $("#btnAgregarProducto").prop("disabled", false);
            $("#btnCancelar_newProd").click();
            $("#addImpuesto").html("");
            loadCombo('0','cmbProducto','producto',$('#cmbCliente').val(),'producto');
            cmbImpuestos.destroy();
            tasaImpuestos.destroy();
            $("#txtTipoImpuesto").val(1);
            $("#trasladado").css("display","block");
            $("#retenciones").css("display","none");
            $("#local").css("display","none");          

          },
          error: function (error) {
            $("#btnAgregarProducto").prop("disabled", false);
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/warning_circle.svg",
              img: null,
              msg: error,
            });
          },
        });
      }
    }
  } else {
    if (!$("#txtProducto").val()) {
      $("#invalid-nombreProducto").css("display", "block");
      $("#txtProducto").addClass("is-invalid");
    }
    if (!$("#txtClave").val()) {
      $("#invalid-clave").css("display", "block");
      $("#txtClave").addClass("is-invalid");
    }
    if (!$("#cmbTipoProducto").val()) {
      $("#invalid-tipoProd").css("display", "block");
      $("#cmbTipoProducto").addClass("is-invalid");
    }
  }
});

function escribirNombreProd() {
  var valor = document.getElementById("txtProducto").value;
  $.ajax({
    url: "../../../cotizaciones/functions/validarNombreProducto.php",
    data: { data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta nombre valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-nombreProducto").css("display", "block");
        $("#invalid-nombreProducto").text("El nombre ya esta en el registro.");
        $("#txtProducto").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProducto").css("display", "block");
          $("#invalid-nombreProducto").text("El producto debe tener un nombre.");
          $("#txtProducto").addClass("is-invalid");
        } else {
          $("#invalid-nombreProducto").css("display", "none");
          $("#txtProducto").removeClass("is-invalid");
        }
      }
    },
    error: function(data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-nombreProducto").css("display", "block");
        $("#invalid-nombreProducto").text("El nombre ya esta en el registro.");
        $("#txtProducto").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-nombreProducto").css("display", "block");
          $("#invalid-nombreProducto").text("El producto debe tener un nombre.");
          $("#txtProducto").addClass("is-invalid");
        } else {
          $("#invalid-nombreProducto").css("display", "none");
          $("#txtProducto").removeClass("is-invalid");
        }
      }
    }
  });
}

function escribirClave() {
  var valor = $("#txtClave").val();
  $.ajax({
    url: "../../../cotizaciones/functions/validarClaveProducto.php",
    data: { data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta clave interna valida: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-clave").css("display", "block");
        $("#invalid-clave").text(
          "La clave interna ya existe."
        );
        $("#txtClave").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-clave").css("display", "block");
          $("#invalid-clave").text("El producto debe tener un nombre.");
          $("#txtClave").addClass("is-invalid");
        } else {
          $("#invalid-clave").css("display", "none");
          $("#txtClave").removeClass("is-invalid");
        }
      }
    },
    error: function(data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (data.responseText != 'exito') {
        $("#invalid-clave").css("display", "block");
        $("#invalid-clave").text(
          "La clave interna ya existe"
        );
        $("#txtClave").addClass("is-invalid");
      } else {
        if (!valor) {
          $("#invalid-clave").css("display", "block");
          $("#invalid-clave").text("El producto debe tener un nombre.");
          $("#txtClave").addClass("is-invalid");
        } else {
          $("#invalid-clave").css("display", "none");
          $("#txtClave").removeClass("is-invalid");
        }
      }
    }
  });
}

function validarUnicaSucursal(item) {
  var valor = item.value;
  $.ajax({
    url: "../../../configuracion/sucursales/php/funciones.php",
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

function cambioClienteCopeado(valor){
  $("#chkcmbTodoProducto").prop("disabled",false);
  $("#mostrarTodos").prop("disabled",false);

  loadComboProdCopeado('','cmbProducto','producto',valor,'producto');
  $('#chkcmbTodoProducto').on('change',function(){
    if(this.checked){
      Swal.fire(
        "Al cliente seleccionado no se le venden los productos listados",
        "Para agregarlos a la lista de los productos que se le venden, favor completar los campos.",
        "success"
      );
      $("#txtPrecioUnitario").prop("disabled",false);
      loadComboProdCopeado('','cmbProducto','producto',valor,'todoProducto');
    }else{
      $("#txtPrecioUnitario").prop("disabled",false);
      loadComboProdCopeado('','cmbProducto','producto',valor,'producto');
    }
  });
  loadComboProdCopeado('','cmbProducto','producto',valor);

  ////CAMBIO EL Producto
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
  _global.pkSuc = pkSucursal;
  ///Fixed se quedaba el invalidiv visible y no guardaba la venta.
  if($("#cmbDireccionEnvio").val()){
    $("#invalid-sucursal").css("display","none");
  }

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
                    <div class="col-lg-3">
                      <label for="usr">Cantidad:*</label>
                      <input type="number" class="form-control numeric-only txtCantidad" maxlength="8"
                        name="txtCantidad" id="txtCantidad" value="0" onchange="validEmptyInput('txtCantidadExistencia', 'invalid-existencia', 'No se poseen existencias en la sucursal.')" onclick="select()">
                      <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Precio unitario:*</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="text" class="form-control numericDecimal-only" maxlength="18" name="txtPrecioUnitario" id="txtPrecioUnitario" oninput="onlyDecimal(this)">
                        <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un precio unitario.</div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <input type="checkbox" name="check_precioEspecial" id="check_precioEspecial"/>
                      <label for="check_precioEspecial">Precio especial</label>
                    </div>
                    <div class="col-lg-3">
                      <label for="usr">Stock en sucursal:</label>
                      <input type="text" class="form-control numericDecimal-only" maxlength="10" name="txtCantidadExistencia" id="txtCantidadExistencia" disabled="disabled" value="0" oninput="onlyDecimal(this)" onclick="select()">
                      <div class="invalid-feedback" id="invalid-existencia">No se poseen existencias en la sucursal.</div>
                    </div>
                  </div>
                </div>`;
        
        mostrarStock(pkSucursal);

        
        
      }else{
        html = `<div class="form-group">
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <label for="usr">Cantidad:*</label>
                                      <input type="number" class="form-control numeric-only txtCantidad" maxlength="8" name="txtCantidad" id="txtCantidad" value="0" onclick="select()">
                                      <div class="invalid-feedback" id="invalid-productoCnt">El producto debe tener una cantidad.</div>
                                      <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
                                    </div>
                                    <div class="col-lg-3">
                                      <label for="usr">Precio unitario:*</label>
                                      <div class="input-group">
                                        <div class="input-group-prepend">
                                          <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control numericDecimal-only" maxlength="18" name="txtPrecioUnitario" id="txtPrecioUnitario" oninput="onlyDecimal(this)">
                                        <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un precio unitario.</div>
                                      </div>
                                    </div>
                                    <div class="col-lg-3">
                                      <input type="checkbox" name="check_precioEspecial" id="check_precioEspecial"/>
                                      <label for="check_precioEspecial">Precio especial</label>
                                    </div>
                                  </div>
                                </div> `;

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
      ///CUANDO ES SErVICIO PERO LA SUCURSAL MANEJA INVENTARIOS NO MUESTRA EL STOCK.
      if (respuesta[0].isServicio == '5'){
          var html = `<div class="form-group">
          <div class="row">
            <div class="col-lg-6">
              <label for="usr">Cantidad:*</label>
              <input type="number" class="form-control numeric-only txtCantidad" maxlength="8" name="txtCantidad" id="txtCantidad" value="0" onclick="select()">
              <div class="invalid-feedback" id="invalid-productoCnt">El producto debe tener una cantidad.</div>
              <input type="hidden" name="txtCantidadHis" id="txtCantidadHis">
            </div>
            <div class="col-lg-3">
              <label for="usr">Precio unitario:*</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">$</span>
                </div>
                <input type="text" class="form-control numericDecimal-only" maxlength="18" name="txtPrecioUnitario" id="txtPrecioUnitario" oninput="onlyDecimal(this)">
                <div class="invalid-feedback" id="invalid-precioUnit">El producto debe tener un precio unitario.</div>
              </div>
            </div>
            <div class="col-lg-3">
              <input type="checkbox" name="check_precioEspecial" id="check_precioEspecial"/>
              <label for="check_precioEspecial">Precio especial</label>
            </div>
          </div>
        </div> `;
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
  var pkUsuario= $("#txtUsuario").val();
  var cantidad = parseFloat($('#txtCantidad').val());
  var pkCliente = $('#cmbCliente').val(); 
  var precio = $('#txtPrecioUnitario').val(); 
  var precioEsp = 0;
  var stock = $("#txtCantidadExistencia").is(":visible") ? $("#txtCantidadExistencia").val() : null;
  if($("#check_precioEspecial").is(":checked")){
      precioEsp = $('#txtPrecioUnitario').val();
    }else{
      precioEsp = 0; 
    } 
  
  //inicio alertas
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
  }else if(parseFloat($('#txtCantidad').val()) < parseFloat($('#txtCantidadHis').val())){
    $('#agregarProducto').prop("disabled", true );   
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
    
    if(stock !== null && $("#chkAfectarInventario").is(":checked")){
      if(cantidad <= stock){
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "validar_productoVentaDirecta",
            data: idproducto, data2: pkUsuario, data3: pkCliente,
          },
          dataType: "json",
          success: function (data) {
            console.log("respuesta nombre valida: ", data);
            /* Validar si ya existe el identificador con ese nombre*/
            if (parseInt(data[0]["existe"]) === 1) {
            validarYGuardarProducto(idproducto,pkUsuario,cantidad,pkCliente,precio,precioEsp);
            } else {
            
            alerta ='<div class="alert alert-warning" role="alert">'+
            'Debe ingresar una cantidad mayor a 0.'+
            '</div>';

            $('#txtCantidad').val($('#txtCantidadHis').val());
            }
          },
        });
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 4000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/warning_circle.svg",
          msg: "La cantidad en existencia es menor a la deseada.",
        });
      }
    } else {
      $.ajax({
        url: "../../php/funciones.php",
        data: {
          clase: "get_data",
          funcion: "validar_productoVentaDirecta",
          data: idproducto, data2: pkUsuario, data3: pkCliente,
        },
        dataType: "json",
        success: function (data) {
          console.log("respuesta nombre valida: ", data);
          /* Validar si ya existe el identificador con ese nombre*/
          if (parseInt(data[0]["existe"]) === 1) {
          validarYGuardarProducto(idproducto,pkUsuario,cantidad,pkCliente,precio,precioEsp);
          } else {
          
          alerta ='<div class="alert alert-warning" role="alert">'+
          'Debe ingresar una cantidad mayor a 0.'+
          '</div>';

          $('#txtCantidad').val($('#txtCantidadHis').val());
          }
        },
      });
    }
  }
  else{
    if(idproducto!=null){
      if(stock !== null && $("#chkAfectarInventario").is(":checked")){
        if(parseInt(cantidad) <= parseInt(stock)){
          
          $('#agregarProducto').prop("disabled", true );   
          validarYGuardarProducto(idproducto,pkUsuario,cantidad,pkCliente,precio,precioEsp);
        } else {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 4000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../../../img/timdesk/warning_circle.svg",
            msg: "La cantidad en existencia es menor a la deseada.",
          });
        }
      } else {
        $('#agregarProducto').prop("disabled", true );   
          validarYGuardarProducto(idproducto,pkUsuario,cantidad,pkCliente,precio,precioEsp);
      }
    }else{
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡Seleccione un Producto!",
        sound: '../../../../../sounds/sound4'
      });
      $('#agregarProducto').prop("disabled", false );
    }
   

  }

  $("#invalid-existencia").css("display", "none");
  $("#invalid-existencia").text(
    ""
  );
  $("#txtCantidadExistencia").removeClass("is-invalid");
}

function validarYGuardarProducto(idproducto,pkUsuario,cantidad,pkCliente,precio,precioEsp){
  //Validar producto Ya está en la tabla temporal
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_productoVentaDirecta",
      data: idproducto, data2: pkUsuario, data3: pkCliente,
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
          confirmButtonColor: '#053d76',
          confirmButtonText:
            'Si   <i class="far fa-arrow-alt-circle-right"></i>',
          cancelButtonText: 'No   <i class="far fa-times-circle"></i>',
          //buttonsStyling: false,
          allowEnterKey: false,
        }).then((result) => {
          if (result.isConfirmed) {
            var element = document.getElementById("content");
            
            if(validarProductoStockTabla(idproducto,cantidad)){
              element.scrollIntoView();
              //actualización de datos a tabla
              $.ajax({
                url: "../../php/funciones.php",
                data: {
                  clase: "edit_data",
                  funcion: "edit_venta_directaTemp",
                  datos: idproducto, datos2: cantidad, datos3: pkUsuario, datos4: pkCliente, newprecio: precio
                },
                dataType: "json",
                success: function (respuesta) {
                  console.log("respuesta agregar venta directa:", respuesta);
            
                  if (respuesta[0].status) {
                    cargarTablaVentasDirectasTemp();
                    obtenerTotal();

                    Lobibox.notify("success", {
                      size: "mini",
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: "center top",
                      icon: true,
                      img: "../../../../img/timdesk/checkmark.svg",
                      msg: "¡Se actualizó la cantidad del producto en la orden de venta con éxito!",
                      sound: '../../../../../sounds/sound4'
                    });
                    $('#agregarProducto').prop("disabled", false );   
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
                    $('#agregarProducto').prop("disabled", false );   
                  }
                },
                error: function (error) {
                  console.log(error);
                },
              });
            } else {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 4000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/warning_circle.svg",
                msg: "La cantidad en existencia es menor a la deseada.",
              });
              $('#agregarProducto').prop("disabled", false );
            }
            
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            /*No hacer nada*/
          } else {
            /*No hacer nada*/
          }
        });

        console.log("¡Ya existe!");
      } else {
        /*Agregar producto a la venta directa*/
        $.ajax({
          url: "../../php/funciones.php",
          data: {
            clase: "save_data",
            funcion: "save_venta_directaTemp",
            datos: idproducto, datos2: cantidad, datos3: pkUsuario, datos4: pkCliente, datos5:precio, datos6:precioEsp, randomId: IdOpRmd
          },
          dataType: "json",
          success: function (respuesta) {
            console.log("respuesta agregar venta directa:", respuesta);
      
            if (respuesta[0].status) {
              cargarTablaVentasDirectasTemp();

              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 5000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "¡Se guardó el producto en la orden de venta con éxito!",
                sound: '../../../../../sounds/sound4'
              });
              $('#agregarProducto').prop("disabled", false );   
              obtenerTotal();
            } else {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 5000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/notificacion_error.svg",
                msg: "¡Algo salio mal :(!",
                sound: '../../../../../sounds/sound4'
              });
              $('#agregarProducto').prop("disabled", false );   
            }
          },
          error: function (error) {
            console.log(error);
          },
        });

        console.log("¡No existe!");
      }
      
      
    },
    complete: function(_, __) {
      if($("#chkcmbTodoProducto").is(":checked")){
        loadCombo('','cmbProducto','producto',$('#cmbCliente').val(),'todoProducto');
      }else{
        loadCombo('0','cmbProducto','producto',$('#cmbCliente').val(),'producto');
      }

      $('#txtPrecioUnitario').val('');
      $('#cmbCliente option:not(:selected)').remove();
      $('#cmbDireccionEnvio option:not(:selected)').remove();
      $('#txtCantidad').val('');
    }
  });
}

function validarProductoStockTabla(product_id,quantity)
{
  if($("#chkAfectarInventario").is(":checked")){
    const table = $("#tblListadoVentasDirectasTemp").DataTable();
    var pos = table.rows().data();
    var ban = "";
    var table_quantity = 0;
    var stock = $("#txtCantidadExistencia").val();
    
    if (pos.length > 0) {
      for (let i = 0; i < pos.length; i++) {
        if (parseInt(pos[i].producto_id) === parseInt(product_id)) {
          ban = i;
        }
      }
      i = ban;
      table_quantity = table.cell({ row: i, column: 3 }).data();
      table_stock = table.cell({ row: i, column: 9 }).data();
      //return "cantidad_input" + quantity + ", cantidad: " + table_quantity + ", stock: " + table_stock;
      if(parseFloat(parseFloat(table_quantity) + parseFloat(quantity)) <= parseFloat(table_stock)){
        return true;
      } else {
        return false;
      }
    }
  } else {
    return true;
  }
}

function enviarVentaDirecta(){
  $("#btnAgregar").prop('disabled',true);
  ///Fixed se quedaba el invalidiv visible y no guardaba la venta.
  if($("#cmbDireccionEntrega").val()){
    $("#invalid-direccionEntrega").css("display","none");
  }else{
    $("#invalid-direccionEntrega").css("display","block");
  }
  if($("#cmbCondicionPago").val()){
    $("#invalid-condicionPago").css("display","none");
  }else{
    $("#invalid-direccionEntrega").css("display","block");
  }
  if($("#txtFechaEmision").val()){
    $("#invalid-fechaEm").css("display","none");
  }else{
    $("#invalid-fechaEm").css("display","block");
  }
  if($("#txtFechaEstimada").val()){
    $("#invalid-fechaVen").css("display","none");
  }else{
    $("#invalid-fechaVen").css("display","block");
  }
  if($("#cmbCliente").val()){
    $("#invalid-cliente").css("display","none");
  }else{
    $("#invalid-cliente").css("display","block");
  }
  if($("#cmbDireccionEnvio").val()){
    $("#invalid-sucursal").css("display","none");
  }else{
    $("#invalid-sucursal").css("display","block");
  }
  if($("#cmbVendedor").val()){
    $("#invalid-vendedor").css("display","none");
  }else{
    $("#invalid-vendedor").css("display","block");
  }
  if($("#cmbDireccionEntrega").val()){
    $("#invalid-direccionEntrega").css("display","none");
  }else{
    $("#invalid-direccionEntrega").css("display","block");
  }
  if($("#cmbCondicionPago").val()){
    $("#invalid-condicionPago").css("display","none");
  }else{
    $("#invalid-condicionPago").css("display","block");
  }
  if($("#cmbMoneda").val()){
    $("#invalid-moneda").css("display","none");
  }else{
    $("#invalid-moneda").css("display","block");
  }
  var alerta = "";
  var table = $('#tblListadoVentasDirectasTemp').DataTable();
  var badReferencia;
  var badFechaEmision;
  var badFechaEstimada;
  var badCliente;
  var badSucursal;
  var badVendedor;
  var badDireccionEnvio;
  var badCondicionPago;


  //if ($("#frmVentaDirecta")[0].checkValidity()) {
  if (0==0) {
    if(table.rows().count() > 0){
      badReferencia =
        $("#invalid-referencia").css("display") === "block" ? false : true;
      badFechaEmision =
        $("#invalid-fechaEm").css("display") === "block" ? false : true;
      badFechaEstimada =
        $("#invalid-fechaVen").css("display") === "block" ? false : true;
      badCliente =
        $("#invalid-cliente").css("display") === "block" ? false : true;
      badSucursal =
        $("#invalid-sucursal").css("display") === "block" ? false : true;  
      badVendedor =
        $("#invalid-vendedor").css("display") === "block" ? false : true; 
      badDireccionEnvio =
        $("#invalid-direccionEntrega").css("display") === "block" ? false : true;
      badCondicionPago =
        $("#invalid-condicionPago").css("display") === "block" ? false : true; 
      if (
        badReferencia &&
        badFechaEmision &&
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
        var afectar_inventario = $("#chkAfectarInventario").is(":checked") ? 1 : 0; 
        
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

        var pkUsuario= $("#txtUsuario").val();
        var notasInternas = $("#NotasInternas").val();
        var notasCliente = $("#NotasCliente").val();
        var vendedor = $("#cmbVendedor").val();
        var monedas = $("#cmbMoneda").val();

        $.ajax({
          url:"../../php/funciones.php",
          data:{
            clase:"save_data",funcion:"save_VentaDirecta",
            datos:referencia,
            datos2:fechaEmision, 
            datos3:fechaVencimiento,
            datos4:cliente,
            datos5:direccionEntrega, 
            datos6:importe,
            datos7:pkUsuario, 
            datos8: notasInternas, 
            datos9: notasCliente,
            datos10: vendedor,
            moneda: monedas,
            datos11: subtotal,
            datos12: direccionEntregaCliente,
            datos13: condicionPago,
            randomID: IdOpRmd,
            afectar_inventario: afectar_inventario
          }, 
          dataType:"json", 
          success:function(respuesta){ 
            if (respuesta[0].status) {
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "Venta registrada correctamente!",
                sound: '../../../../../sounds/sound4'
              });

              setTimeout(
                function() {
                  window.location.href = "functions/descargar_VentaDirecta.php?txtId="+respuesta[0].id;
                },
                500,
              );

              setTimeout(function(){window.location.href = "../ventas/ver_ventas.php?vd="+respuesta[0].id},1500);
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
                msg: "¡Algo salio mal :(! (Error: 001)",
                sound: '../../../../../sounds/sound4'
              });
            }
          }
        });
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
          msg: "¡Algo salio mal :(! (Error: 002)",
          sound: '../../../../../sounds/sound4'
        });
      }
    }else{
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "¡Debes ingresar por lo menos un producto!",
        sound: '../../../../../sounds/sound4'
      });
    }
    
  }else{
    //alert($("#frmVentaDirecta")[0]);
    if(!$('#txtReferencia').val()){
      $("#invalid-referencia").css("display", "block");
      $("#txtReferencia").addClass("is-invalid");
    }else {
      $("#invalid-referencia").css("display", "none");
      $("#txtReferencia").removeClass("is-invalid");
    }
  
    if(!$('#txtFechaEmision').val()){
      $("#invalid-fechaEm").css("display", "block");
      $("#txtFechaEmision").addClass("is-invalid");
    }else {
      $("#invalid-fechaEm").css("display", "none");
      $("#txtFechaEmision").removeClass("is-invalid");
    }
  
    if(!$('#txtFechaEstimada').val()){
      $("#invalid-fechaVen").css("display", "block");
      $("#txtFechaEstimada").addClass("is-invalid");
    }else {
      $("#invalid-fechaVen").css("display", "none");
      $("#txtFechaEstimada").removeClass("is-invalid");
    }
  
    if(!$('#cmbCliente').val()){
      $("#invalid-cliente").css("display", "block");
      $("#cmbCliente").addClass("is-invalid");
    }else {
      $("#invalid-cliente").css("display", "none");
      $("#cmbCliente").removeClass("is-invalid");
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
    $("#btnAgregar").prop('disabled',false);
    Lobibox.notify("error", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top",
      icon: true,
      img: "../../../../img/timdesk/notificacion_error.svg",
      msg: "¡Error en los datos-ErrorCode: (0!=0) ",
      sound: '../../../../../sounds/sound4'
    });
  }
  
  /*$('#modal_envio').load('functions/modal_envio.php?id='+$('#cmbCliente').val()+'&txtId='+14+'&estatus=0&txtNotas='+$('#NotasCliente').val(), function(){
    $('#datos_envio').modal('show');
  });*/
}

function obtenerIdVentaDirectaTempEliminar(id) {
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "delete_data",
      funcion: "delete_VentaDirectaTemp",
      data: id,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar producto:", respuesta);

      if (respuesta[0].status) {
        cargarTablaVentasDirectasTemp();
        obtenerTotal();

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/checkmark.svg",
          msg: "¡Se eliminó el producto de la orden con éxito!",
          sound: '../../../../../sounds/sound4'
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
    },
  });
}

var controladorTiempo = "";

function validarCantidad(id,cantidad,producto_id,stock){
  clearTimeout(controladorTiempo);
  controladorTiempo = setTimeout(validarCant(id,cantidad,producto_id,stock), 3000);
}

function validarCant(id,cantidad,stock){
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
    $('#precio-'+id).val(cantidad);
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
    $('#cantidad-'+id).val(cantidad);
    return;
  }

  /* if(parseInt($('#cantidad-'+id).val()) < 1 ||$('#cantidad-'+id).val()== ''){
    $("#notaCantidad-"+id).css("display", "block");
    $('#notaCantidad-'+id).prop('title', 'La cantidad debe de ser mayor a 0');
  }else{
    $("#notaCantidad-"+id).css("display", "none"); */

     //valida que la cantidad no sea mayor a 12 enteros y 6 decimales
  if($("#chkAfectarInventario").is(":checked")){
    if(parseFloat(valor) <= parseFloat(stock)){
      changeQuantityPrice(valor,newPrecio)
    } else{
      Lobibox.notify("error", {
        size: "mini",
        rounded: true,
        delay: 4000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/warning_circle.svg",
        msg: "La cantidad en existencia es menor a la deseada.",
      });
      $('#cantidad-'+id).val(cantidad);
    }
  } else {
    changeQuantityPrice(valor,newPrecio)
  }
}

function changeQuantityPrice(valor,newPrecio)
{
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
        funcion: "edit_VentaDirectaTemp_Cantidad",
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
          cargarTablaVentasDirectasTemp();
          obtenerTotal();
        }
      },
      error: function (error) {
        console.log(error);
      },
    });
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

      if(input == "cmbCliente"){
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Agregar cliente</option>';
      }else if(input == "cmbDireccionEnvio"){
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Agregar sucursal</option>';
      }else if(input == "cmbProducto"){
        html += '<option value="add" style="background-color: #15589B;  color:white; text-align:center; width:100%;">Agregar Producto</option>';
      }

      

      $('#'+input+'').html(html);
      if(oculto !== ""){
        $('#unidadMedida').val(oculto);
      }

      /* if(input == 'cmbProducto'){
        if (respuesta[0].cantidad == '1'){
          if (respuesta[0].FKTipoProducto == '5'){
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 5000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Sólo se podrán agregar servicios apartir de este momento!",
              sound: '../../../../../sounds/sound4'
            });
          }else{
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 5000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../../img/timdesk/checkmark.svg",
              msg: "¡Sólo se podrán agregar productos apartir de este momento!",
              sound: '../../../../../sounds/sound4'
            });
          }
        }
      } */
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
function obtenerTotal(){
  var pkUsu= $("#txtUsuario").val();
  Total = 0.0;
  //Obtener subtotal
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_subTotalVentaDirectaTemp",datos:pkUsu},
    dataType:"json",
    success:function(respuesta){
      $('#Subtotal').html(dosDecimales(parseFloat(respuesta[0].subtotal)))
      Total += parseFloat(respuesta[0].subtotal);
      $('#Total').html(dosDecimales(Total));
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
    data:{clase:"get_data", funcion:"get_impuestoVentaDirectaTemp_v2",datos:pkUsu},
    dataType:"json",
    success:function(respuesta){
      //Recorrer las respuestas de la consulta
      var tasa = '';
      $.each(respuesta,function(i){    
        if(!$('#impuestos-head-'+respuesta[i].id).length){
        
        if(respuesta[i].tasa == '' || respuesta[i].tasa == null){
          tasa = respuesta[i].tasa;
        }else{
          tasa = respuesta[i].tasa+'%';
        }
        ///Recortar ceros.
        //La configuración de una "variable numérica" con 0 extra se recorta automáticamente.
        var Timp = respuesta[i].totalImpuesto;
        html += /* '<tr>'+
                  //'<th style="text-align: right;">'+respuesta[i].producto+'</th>'+ <--
                  '<td style="text-align: right;" id="impuestos-head-'+respuesta[i].id+'">'+respuesta[i].nombre+'</td>'+
                  '<td style="text-align: right;">'+tasa+' </td>'+
                  '<td style="text-align: right;">.....</td>'+
                  '<td style="text-align: right;"> $ '+ dosDecimales(respuesta[i].totalImpuesto) +'</td>'+
                '</tr>'; */

                '<span style="color: var(--color-primario);" id="impuestos-head-' + respuesta[i].id + '">'+
                respuesta[i].nombre + ": $ " + dosDecimales(parseFloat(respuesta[i].totalImpuesto)) +
                "</span><br>";

                /// Si es Retenido TipoImp 2 se resta del Total.
            if(respuesta[i].tipoImp == 2){
              Total -= respuesta[i].totalImpuesto;
            }else{
              Total += respuesta[i].totalImpuesto;
            }
        }
         
        
      });
      
      $('#impuestos').html(html);
      $('#Total').html(dosDecimales(Total));
    },
    error:function(error){
      console.log(error);
    }
  });

  //Obtener total
  /* $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_totalVentaDirectaTemp",datos:pkUsu},
    dataType:"json",
    success:function(respuesta){
      $('#Total').html(dosDecimales(respuesta[0].Total))
    },
    error:function(error){
      console.log(error);
    }
  }); */
}

function obtenerTotalCopeado(){
  var PKVenta = _global.PKVenta;
  //Obtener subtotal
  $.ajax({
    
    url:"../../php/funciones.php",
    data:{clase:"get_data", funcion:"get_subTotalVentaDirectaEdit",datos:PKVenta},
    dataType:"json",
    success:function(respuesta){
      $('#Subtotal').html(dosDecimales(parseFloat(respuesta[0].subtotal)))
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

        if(respuesta[i].tasa == '' || respuesta[i].tasa == null){
          tasa = respuesta[i].tasa;
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
                respuesta[i].nombre + ": $ " + dosDecimales(parseFloat(respuesta[i].totalImpuesto)) +
                "</span><br>";
        }
         

      });
      
      $('#impuestos').html(html);
      
    },
    error:function(error){
      console.log(error);
    }
  });

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
      $('#Total').html(dosDecimales(parseFloat(respuesta[0].Total)))
    },
    error:function(error){
      console.log(error);
    }
  });
}
function cortarNumber(n){
  var number = n;
    n = Number.parseFloat(n).toFixed(6).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    number = n;
  /* if(number.toString.length > 6){
    number = Number.parseFloat(n).toFixed(6).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  } */
  return number;
}
function dosDecimales(n) {
  n = Math.round((n + Number.EPSILON) * 100) / 100;
  return Number.parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

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

function loadComboCopeado(data,input,name,value,fun){
  if (input == 'cmbDireccionEnvio'){
    var html ='<option value="0" disabled selected hidden>Seleccione una '+name+'...</option>';
  }else if(input == 'cmbCliente'){
    setTimeout(function() {
      $('#cmbCliente option:not(:selected)').remove();
    }, 300);
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

    },
    error:function(error){
      console.log(error);
    }

  });
}

function loadComboProdCopeado(data,input,name,value,fun){
  
  var html ='<option value="0" disabled selected hidden>Seleccione un '+name+'...</option>';

  var oculto;

  var PKVenta = _global.PKVenta;

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

function registrarProductosTemporales(){
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "save_data",
      funcion: "save_VentaCopy_TableTemp",
      data:_global.PKVenta,
      rdmID:IdOpRmd
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta[0].status) {
        
        loadProductosDevolucion(folioSalida);
      } else {
       
      }
    },
    error: function (error) {
      console.log(error); 
    },
  });
}
function getval(sel){
  console.log(sel.value);
}

$(document).on('input', '.cantidadProducto',  function(){
  
   var regexp = /[^0-9]/g;
    if ($(this).val().match(regexp)) {
      $(this).val($(this).val().replace(regexp, ""));
    }

});

//url: "../catalogos/inventarios_productos/php/funciones.php",
function cargarCMBImpuestos(data, input) {
  var html = "";
  var selected;
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_impuestos" },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta impuestos: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKImpuesto) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKImpuesto +
          '" ' +
          selected +
          ' data-tipo="' +
          respuesta[i].FKTipoImpuesto +
          '" data-importe="' +
          respuesta[i].FKTipoImporte +
          '">' +
          respuesta[i].Nombre +
          "</option>";
      });

      CargarSlimImpuestos();

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function cargarCMBTasaImpuestos(data, input) {
  var valor = data;
  console.log("PKImpuestos: " + valor);

  var html = "";
  var selected;

  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: { clase: "get_data", funcion: "get_cmb_tasa_impuestos", data: valor },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta tasas: ", respuesta);

      $.each(respuesta, function (i) {
        if (data === respuesta[i].PKImpuesto_tasas) {
          selected = "selected";
        } else {
          selected = "";
        }

        html +=
          '<option value="' +
          respuesta[i].PKImpuesto_tasas +
          '" ' +
          selected +
          ">" +
          respuesta[i].Tasa +
          "</option>";
      });

      $("#" + input + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });
}


let cmbImpuestos;
function CargarSlimImpuestos() {
  cmbImpuestos = new SlimSelect({
    select: "#cmbImpuestos",
    deselectLabel: '<span class="">✖</span>',
  });

  CargarSlimTasaImpuestos();
}

let tasaImpuestos;
function CargarSlimTasaImpuestos() {
  tasaImpuestos = new SlimSelect({
    select: "#cmbTasaImpuestos",
    deselectLabel: '<span class="">✖</span>',
  });
}


function cambioImpuesto(producto) {
  var FKImpuesto = document.getElementById("cmbImpuestos").value;
  cargarCMBTasaImpuestos(FKImpuesto, "cmbTasaImpuestos");

  var tipo =
    document.getElementById("cmbImpuestos").options[
      document.getElementById("cmbImpuestos").selectedIndex
    ].dataset.tipo;
  var importe =
    document.getElementById("cmbImpuestos").options[
      document.getElementById("cmbImpuestos").selectedIndex
    ].dataset.importe;

  console.log("Tipo:" + tipo);
  console.log("Importe:" + importe);

  if (tipo == 1) {
    $("#trasladado").css("display", "block");
    $("#retenciones").css("display", "none");
    $("#local").css("display", "none");
    $("#txtTipoImpuesto").val("1");
  }
  if (tipo == 2) {
    $("#trasladado").css("display", "none");
    $("#retenciones").css("display", "block");
    $("#local").css("display", "none");
    $("#txtTipoImpuesto").val("2");
  }
  if (tipo == 3) {
    $("#trasladado").css("display", "none");
    $("#retenciones").css("display", "none");
    $("#local").css("display", "block");
    $("#txtTipoImpuesto").val("3");
  }

  $("#cmbTasaImpuestos").attr("readonly", false);

  var select = `<select class="cmbSlim" name="cmbTasaImpuestos" id="cmbTasaImpuestos" required="">
              </select> `;

  var inputNumber = `<input type='number' min='0' value='' name='cmbTasaImpuestos' id='cmbTasaImpuestos' class='form-control'>`;

  if (importe == 1) {
    $("#etiquetaImpuesto").text("Tasa:");
    $("#areaimpuestos").html(select);
    CargarSlimTasaImpuestos();
  }
  if (importe == 2) {
    $("#etiquetaImpuesto").text("Importe:");
    $("#areaimpuestos").html(inputNumber);
  }
  if (importe == 3) {
    $("#etiquetaImpuesto").html("Tasa:");
    $("#areaimpuestos").html(inputNumber);
    $("#cmbTasaImpuestos").attr("readonly", true);
  }

  $("#txtTipoTasa").val(importe);

  /*console.log("Valor impuesto" + FKImpuesto);
  $.ajax({
    url: "../../../inventarios_productos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_impuestoProducto",
      data: producto,
      data2: FKImpuesto,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta impuesto validado: ", data);

      if (parseInt(data[0]["existe"]) == 1) {

        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "El impuesto ya ha sido agregado.",
          sound: '../../../../../sounds/sound4'
        });
        console.log("¡Ya existe!");
      } else if (parseInt(data[0]["existe"]) == 2) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "No es posible añadir el impuesto excento.",
          sound: '../../../../../sounds/sound4'
        });
      } else if (parseInt(data[0]["existe"]) == 3) {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: true,
          img: "../../../../img/timdesk/notificacion_error.svg",
          msg: "Ya se posee un impuesto de tipo excento.",
          sound: '../../../../../sounds/sound4'
        });
      } else {
        var nota = document.getElementById("notaImpuesto");
        nota.setAttribute("type", "hidden");

        console.log("¡No existe!");
      }
    },
  });*/
}


let contadorImpuestos = 1;
function validarImpuesto(){

  let fila;
  let tipoImpuesto = $("#txtTipoImpuesto").val();
  let idImpuesto = $("#cmbImpuestos").val();
  let nombreImpuesto = $("#cmbImpuestos").find('option:selected').text();

  var elementType = $("#cmbTasaImpuestos").get(0).tagName;

  let tasaImpuesto;
  if(elementType === "SELECT"){
    tasaImpuesto = $("#cmbTasaImpuestos").find('option:selected').text();
  }
  if(elementType === "INPUT"){
    tasaImpuesto = $("#cmbTasaImpuestos").val();

    if((tasaImpuesto.trim() == '' || tasaImpuesto.trim() == 0) && idImpuesto != 5 && idImpuesto != 16){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "Necesitas agregar un valor al importe.",
        sound: '../../../../../sounds/sound4'
      });
      return;
    }
  }


  let idsImpuestos = document.querySelectorAll('.getIDImpuesto')

  let id, encontrados = 0, band1 = 0, band2 = 0;
  idsImpuestos.forEach((item) => {
    id = item.id.split('_');
    //console.log(id[1]);

    if(id[1] == idImpuesto){
      encontrados++;
    }

    if(id[1] == 1 && idImpuesto == 5){
      band1 = 1;
    }
    if(id[1] == 5 && idImpuesto == 1){
      band1 = 1;
    }

    if((id[1] == 2 || id[1] == 3) && idImpuesto == 16){
      band2 = 1;
    }
    if(id[1] == 16 && idImpuesto == 2){
      band2 = 1;
    }
    if(id[1] == 16 && idImpuesto == 3){
      band2 = 1;
    }

  });

  if(encontrados > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "No puedes volver a agregar el mismo impuesto.",
        sound: '../../../../../sounds/sound4'
      });
      return;
  }

  if(band1 > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "No es posible añadir el impuesto exento.",
        sound: '../../../../../sounds/sound4'
      });
      return;
  }

  if(band2 > 0){
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "center top",
        icon: true,
        img: "../../../../img/timdesk/notificacion_error.svg",
        msg: "No es posible añadir el impuesto exento.",
        sound: '../../../../../sounds/sound4'
      });
      return;
  }

  let nombreTipoImpuesto = '';
  if(tipoImpuesto == 1){
    nombreTipoImpuesto = 'Trasladado';
  }
  if(tipoImpuesto == 2){
    nombreTipoImpuesto = 'Retención';
  }
  if(tipoImpuesto == 3){
    nombreTipoImpuesto = 'Local';
  }
  
  fila = '<tr id="fila_' + idImpuesto + '" class="getIDImpuesto">' +
            '<td>' + nombreImpuesto + '</td>' +
            '<td>' + nombreTipoImpuesto + '</td>' +
            '<td>' + tasaImpuesto + '</td>' +
            '<td><img class="btnEdit" src="../../../../img/timdesk/delete.svg" id="btnEliminarImpuesto" onclick="eliminarImpuesto(' + idImpuesto + ');">' +
            '<input type="hidden" value="' + idImpuesto + '" id="idimpuesto_' + contadorImpuestos + '" name="idimpuesto_' + contadorImpuestos + '" />' +
            '<input type="hidden" value="' + tasaImpuesto + '" id="tasaimpuesto_' + contadorImpuestos + '" name="tasaimpuesto_' + contadorImpuestos + '" />' +
            '</td>'
          '</tr>'
  $("#addImpuesto").append(fila);
  contadorImpuestos++;
}


function eliminarImpuesto(fila){
  $("#fila_"+fila).closest('tr').remove();
}

function onlyDecimal(item) {
  var regexp = /[^\d.]/g;
  if ($(item).val().match(regexp)) {
    $(item).val($(item).val().replace(regexp, ""));
  }
};