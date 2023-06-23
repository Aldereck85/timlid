$(document).on("click","#btnDescargarPrefactura",function(){
  var data = Array();
  var clave = Array();
  var producto = Array();
  var u_medida = Array();
  var cantidad = Array();
  var precio = Array();
  var impuestos = Array();
  var total = Array();
  

  $('input[name="clave[]"]').map(function(i){ 
    clave[i] = this.value; 
  }).get();

  $('input[name="producto[]"]').map(function(i){ 
    producto[i] = this.value; 
  }).get();

  $('input[name="u_medida[]"]').map(function(i){ 
    u_medida[i] = this.value; 
  }).get();

  $('input[name="cantidad[]"]').map(function(i){ 
    cantidad[i] = this.value; 
  }).get();

  $('input[name="precio[]"]').map(function(i){ 
    precio[i] = this.value; 
  }).get();

  $('input[name="impuestos[]"]').map(function(i){ 
    impuestos[i] = this.value; 
  }).get();

  $('input[name="total[]"]').map(function(i){ 
    total[i] = this.value; 
  }).get();

  for (let index = 0; index < clave.length; index++) {
    data[index] = {
      "clave":clave[index],
      "producto":producto[index],
      "u_medida":u_medida[index],
      "cantidad":cantidad[index],
      "precio":precio[index],
      "impuestos":impuestos[index],
      "total":total[index]
    };
  }

  var productos = JSON.stringify(data);
  
  $.redirect(
    'php/download_prefactura.php', 
    {
      'folioyserie': $("#folioyserie").val(),
      'razon_social':$("#razon_social").val(),
      'fecha':$("#fecha").val(),
      'cfdi':$("#cfdi").val(),
      'forma_pago':$("#forma_pago").val(),
      'metodo_pago':$("#metodo_pago").val(),
      'moneda':$("#moneda").val(),
      'rfc':$("#rfc").val(),
      'productos':productos,
      'subtotal':$("#subtotal").val(),
      'impuestos':$("#impuestos1").val(),
      'total':$("#total1").val(),
      'notas_cliente' : $("#notas_cliente").val(),
      'direccion_envio' : $("#direccion_envio").val(),
      'contacto' : $("#contacto").val(),
      'telefono' : $("#telefono").val()
    },
    "POST",
  );
});

$(document).on("click","#btnEnviarPrefactura",function(){
  $("#modalEnviarCorreo").modal('show');
});

$(document).on("click","#enviar_prefactura", function(){
  if($("#dataCancelacion")[0].checkValidity()){
    var badDestinatario =
      $("#invalid-destinoCancel").css("display") === "block" ? false : true;
    var badMotivo = 
      $("#invalid-motivoCancelacion").css("display") === "block" ? false : true;
    
    if(badDestinatario &&badMotivo){
      var data = Array();
      var clave = Array();
      var producto = Array();
      var u_medida = Array();
      var cantidad = Array();
      var precio = Array();
      var impuestos = Array();
      var total = Array();
      

      $('input[name="clave[]"]').map(function(i){ 
        clave[i] = this.value; 
      }).get();

      $('input[name="producto[]"]').map(function(i){ 
        producto[i] = this.value; 
      }).get();

      $('input[name="u_medida[]"]').map(function(i){ 
        u_medida[i] = this.value; 
      }).get();

      $('input[name="cantidad[]"]').map(function(i){ 
        cantidad[i] = this.value; 
      }).get();

      $('input[name="precio[]"]').map(function(i){ 
        precio[i] = this.value; 
      }).get();

      $('input[name="impuestos[]"]').map(function(i){ 
        impuestos[i] = this.value; 
      }).get();

      $('input[name="total[]"]').map(function(i){ 
        total[i] = this.value; 
      }).get();

      for (let index = 0; index < clave.length; index++) {
        data[index] = {
          "clave":clave[index],
          "producto":producto[index],
          "u_medida":u_medida[index],
          "cantidad":cantidad[index],
          "precio":precio[index],
          "impuestos":impuestos[index],
          "total":total[index]
        };
      }

      var productos = JSON.stringify(data);

      $.ajax({
        url: 'php/send_prefactura.php',
        method: 'POST',
        data: {
          'folioyserie': $("#folioyserie").val(),
          'razon_social':$("#razon_social").val(),
          'fecha':$("#fecha").val(),
          'cfdi':$("#cfdi").val(),
          'forma_pago':$("#forma_pago").val(),
          'metodo_pago':$("#metodo_pago").val(),
          'moneda':$("#moneda").val(),
          'rfc':$("#rfc").val(),
          'productos':productos,
          'subtotal':$("#subtotal").val(),
          'impuestos':$("#impuestos1").val(),
          'total':$("#total1").val(),
          'email':$("#txtDestinoCancel").val(),
          'mensaje':$("#txtMotivoCancelacion").val()
        },
        success: function(respuesta){
          
          $("#modalEnviarCorreo").modal('hide');
        },error: function(error){
          console.log(error);
        }
      });
      /*
      $.redirect(
        'php/send_prefactura.php', 
        {
          'folioyserie': $("#folioyserie").val(),
          'razon_social':$("#razon_social").val(),
          'fecha':$("#fecha").val(),
          'cfdi':$("#cfdi").val(),
          'forma_pago':$("#forma_pago").val(),
          'metodo_pago':$("#metodo_pago").val(),
          'moneda':$("#moneda").val(),
          'rfc':$("#rfc").val(),
          'productos':productos,
          'subtotal':$("#subtotal").val(),
          'impuestos':$("#impuestos1").val(),
          'total':$("#total1").val(),
          'email':$("#txtDestinoCancel").val(),
          'mensaje':$("#txtMotivoCancelacion").val()
        },
        "POST"
      );*/
    } 
  }
});

$(document).on("change","#txtDestinoCancel",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-destinoCancel").css("display", "none");
    $("#txtDestinoCancel").removeClass("is-invalid");
  }
});

$(document).on("change","#txtMotivoCancelacion",function(){
  if($(this).hasClass("is-invalid")){
    $("#invalid-motivoCancelacion").css("display", "none");
    $("#txtMotivoCancelacion").removeClass("is-invalid");
  }
});