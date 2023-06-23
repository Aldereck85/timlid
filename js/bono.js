jQuery(document).ready(function () {
  var idEmpleado = $("#txtId").val();

  $("#btnAgregarBono").hide();
  var sellado = $("#txtExiste").val();

  if (sellado == 1) {
    $("#btnAgregar").hide();
    $("#btnEliminar").show();
  } else {
    $("#btnAgregar").show();
    $("#btnEliminar").hide();
  }
});

function agregarBono() {
  $("#btnAgregarBono").hide();
  $("#btnEliminarBono").show();
  var bonoPuesto = $("#txtBonoPuesto").val();
  $("#lblBonoAsignado").text(bonoPuesto);
  $("#lblBono").text(bonoPuesto);
  $("#txtBono").val(bonoPuesto);
}

function eliminarBono() {
  $("#btnAgregarBono").show();
  $("#btnEliminarBono").hide();
  $("#lblBonoAsignado").text("0.00");
  $("#lblBono").text("0.00");
  $("#txtBono").val(0.0);
  ///SELECT * FROM `bono_mensual` WHERE FKEmpleado = 2 AND Fecha LIKE CONCAT('%',10,'%')
  ///SELECT * FROM `bono_mensual` WHERE FKEmpleado = 2 AND MONTH(Fecha) =10
}
