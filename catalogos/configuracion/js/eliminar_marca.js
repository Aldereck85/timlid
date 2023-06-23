function eliminarMarca() {
  var id = $("#txtPKMarcaD").val();
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "delete_data", funcion: "delete_marca", datos: id },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta eliminar marca:", respuesta);
      if (respuesta[0].status) {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../../../img/chat/checkmark.svg",
          msg: "¡Registro eliminado!",
          sound: '../../../../../sounds/sound4'
        });
        $("#tblMarcadeProductos").DataTable().ajax.reload();
      } else {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: true,
          img: "../../../../img/chat/notificacion_error.svg",
          msg: "¡Algo salio mal!",
          sound: '../../../../../sounds/sound4'
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}
