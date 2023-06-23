
  function cambioOrigen (){
    var sucOrigen = $("#chosenSucursalOrigen").val();
    var html = '';
    console.log("HEYYY");

    $.ajax({
      url: "php/funciones.php",
      data: { clase: "get_data", funcion: "get_cmb_productosSucOrigen", data: sucOrigen},
      dataType: "json",
      success: function (respuesta) {
  
        html += '<option value="0">Elegir opción</option>';
  console.log(respuesta);
        $.each(respuesta, function (i) {
          selected = "";
          html +=
          `<option value="${respuesta[i].PKProducto}" ${selected}>${respuesta[i].Nombre}</option>`;
        });
  
        $("#chosenProducto").html(html);
      },
      error: function (error) {
        console.log(error);
      },
    });
  }