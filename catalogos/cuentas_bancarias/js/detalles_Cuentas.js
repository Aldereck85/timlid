function detalleCuenta() {
  var id = $("#idCuentaU").val();
  var tipoCuenta = $("#tipoIdCuentaU").val();
  console.log({id});
  console.log({tipoCuenta});

  if (tipoCuenta == 4) {
    $().redirect("detalles_Cuenta.php", {
      idDetalle: id,
      tipoIdCuentaU: tipoCuenta,
    });
  } else if (tipoCuenta == 2) {
    $().redirect("detalles_CuentaCredito.php", {
      idDetalle: id,
      tipoIdCuentaU: tipoCuenta,
    });
  } else if (tipoCuenta == 3) {
    $().redirect("detalles_CuentaOtras.php", {
      idDetalle: id,
      tipoIdCuentaU: tipoCuenta,
    });
  } else {
    $().redirect("detalles_CuentaCheques.php", {
      idDetalle: id,
      tipoIdCuentaU: tipoCuenta,
    });
  }

  $.ajax({
    type: "POST",
    url: "functions/get_Ids.php",
    data: { idDetalle: getId },
    success: function (r) {
      var datos = JSON.parse(r);
      $("#pkCuenta").val(datos.pkcuenta);
      $("#idCuentaActuala").val(datos.pkcuentaActual);
    },
  });
}
