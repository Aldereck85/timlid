$(document).ready(function(){
  setInterval(cargarPDF, 1000); 
  //cargarPDF(); 
  
  cargarProductosPDF();
  cargarImpuestosPDF();
}); 
var PKLastComentario = 0;

function cargarPDF(){
  
  var PKVentaDirecta = $("#txtPKVenta").val();
  var FKUsuario = $("#txtUsuario").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datos_VentaDirectaPDF",
      data: PKVentaDirecta,
      data2: FKUsuario,
    },
    dataType: "json",
    success: function (respuesta) {
      var nombreComercial = respuesta[0].NombreComercial;
      var html = `<br><br>`;
      if(respuesta[0].FKEstatusVenta == 1){
        var html = `
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-4" style="margin-top:100px">
                          <button type="button" class="btn-custom btn-custom--blue" name="btnRechazar" id="btnRechazar"
                          onclick="cambiarEstatusVentaDirecta(5)" style="float:right; width:200px; height:50px;"> Cerrar venta directa</button>
                        </div>
                      </div>
                    </div>
                    `;
      }else if(respuesta[0].FKEstatusVenta == 2){
        //var html = `La orden a sido facturada`;
        console.log(`La orden a sido facturada`);
      }else if(respuesta[0].FKEstatusVenta == 3){
        //var html = `La venta ha sido parcialmente surtida`;
        console.log(`La venta ha sido parcialmente surtida`);
      }else if(respuesta[0].FKEstatusVenta == 4){
        //var html = `La venta ha sido surtida completa`;
        console.log(`La venta ha sido surtida completa`);
      }else if(respuesta[0].FKEstatusVenta == 5){
        var html = `
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-4" style="margin-top:100px">
                          <button type="button" class="btn-custom btn-custom--blue" name="btnActivar" id="btnActivar"
                          onclick="cambiarEstatusVentaDirecta(1)" style="float:right; width:200px; height:50px;"> Reabrir venta directa </button>
                        </div>
                      </div>
                    </div>`;
      }

      var html = html + `<div class="form-group">
                      <div class="row">
                        <div class="col-lg-4">
                          <button type="button" class="btn-custom btn-custom--blue" name="btnDescargar" id="btnDescargar"
                          onclick="descargarVentaDirecta()" style="float:right; width:200px; height:50px;"> Descargar venta</button>
                        </div>
                      </div>
                    </div>`;

      $("#botones").html(html);
      $("#referencia").html(respuesta[0].Referencia);
      $("#referencia2").html(respuesta[0].Referencia);
      $("#fechaIngreso").html(respuesta[0].FechaCreacion);
      $("#nombreComercial").html(nombreComercial); 
      $("#nombreComercial2").html(respuesta[0].razon_social); 
      $("#vendedor").html(respuesta[0].Empleado); 
      $("#vendedor2").html(respuesta[0].Empleado); 
      $("#fechaEstimada").html(respuesta[0].FechaEstimada); 
      $("#sucursal").html(respuesta[0].Sucursal); 
      $("#direccion").html(respuesta[0].Calle + ' ' + respuesta[0].NumExt + ' Int.' + respuesta[0].NumInt + '- ' + respuesta[0].Prefijo + ', ' + respuesta[0].Colonia + ', ' + respuesta[0].Municipio + ', ' + respuesta[0].Estado + ', ' + respuesta[0].Pais); 
      $("#subtotal").html("$ "+dosDecimales(respuesta[0].Subtotal)); 
      $("#total").html("$ "+dosDecimales(respuesta[0].Importe));

      if (respuesta[0].notas == '' || respuesta[0].notas == null){
        $("#notas").html('<br><br><br><br><br><br><br>');
      }else{
        $("#notas").html(respuesta[0].notas);
      }

      $("#telefono").html(respuesta[0].Telefono);
      $("#btnEnviar").prop("title","Envía comentarios a "+respuesta[0].Empleado+" de "+nombreComercial+" sobre la orden de compra");

      $("#cmbProveedor").val(respuesta[0].FKProveedor);
    },
  });
}

function cargarProductosPDF(){
  var PKVentaDirecta = $("#txtPKVenta").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datosProd_VentaDirectaPDF",
      data: PKVentaDirecta,
    },
    dataType: "json",
    success: function (respuesta) {

      for (i = 0; i < respuesta.length; i++) {
        var impuestos = '';
        var unidadM = '';
        if (respuesta[i].impuestos == null){
          impuestos = '';
        }else{
          impuestos = respuesta[i].impuestos
        }

        if(respuesta[i].unidadMedida === null){
          unidadM = '';
        }else{
          unidadM =respuesta[i].unidadMedida
        }

        document.getElementById("tablaProductos").insertRow(-1).innerHTML = `<td class="td1" width="9%">`+respuesta[i].clave+`</td>
        <td class="td1" width="36%">`+respuesta[i].nombre+`</td>
        <td class="td1" width="10%">`+respuesta[i].cantidad+`</td>
        <td class="td1" width="10%">$ `+dosDecimales(respuesta[i].precio)+`</td>
        <td class="td1" width="11%">`+unidadM+`</td>
        <td class="td1" width="12%">`+impuestos+`</td>
        <td class="td1" width="100%" height="50px">$ `+dosDecimales(respuesta[i].importe)+`</td>`;
      }
    },
  });
}

function cargarImpuestosPDF(){
  var PKVentaDirecta = $("#txtPKVenta").val();
  $.ajax({
    url: "../../php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_datosImpu_VentaDirectaPDF",
      data: PKVentaDirecta,
    },
    dataType: "json",
    success: function (respuesta) {

      var tasa = '';

      for (i = 0; i < respuesta.length; i++) {

        if (respuesta[i].tasa == '' || respuesta[i].tasa  == null){
          tasa = respuesta[i].nombre + respuesta[i].tasa;
        }else{
          tasa = respuesta[i].nombre +` - `+ respuesta[i].tasa + `%`; 
        }

        document.getElementById("tablaImpuestos").insertRow(-1).innerHTML = `<td class="td1" width="65%" style="background-color: transparent;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
        <td class="td1" width="21%" style="text-align: right;">`+tasa +` </td>
        <td class="td1" width="100%" style="text-align: right;">$ `+ dosDecimales(respuesta[i].totalImpuesto) +`</td>`;
      }

      document.getElementById("tablaImpuestos").insertRow(-1).innerHTML = `<tr>
        <td class="td1" width="65%"
        style="background-color: transparent; border-bottom: 1px solid #fff; border-top: 1px solid #fff;">
      </td>
      <td class="td1" width="21%">Total:</td>
      <td class="td1" width="100%" style="text-align: right;">
        <span id="total">

        </span>
      </td>
    </tr>`;
    },
  });
}

function dosDecimales(n) {
  return Number.parseFloat(n).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

function descargarVentaDirecta(){
  var id = $("#txtPKVenta").val();

  window.location.href = "functions/descargar_VentaDirecta?txtId="+id;
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