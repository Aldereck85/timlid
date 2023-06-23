function validaUnicaClabeCheques(evt, input) {
  if ($("#txtCLABE").val().length < 18) {
    $("#invalid-claveCuenta").text("La CLABE no es valida.");
    $("#invalid-claveCuenta").css("display", "block");
    $("#txtCLABE").addClass("is-invalid");
  } else {
    validacionUno();
  }
}

function validacionUno() {
  console.log("Paso a la siguente validación");
  var valor = document.getElementById("txtCLABE").value;
  var fkempresa = $("#emp_id").val();
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "validar_clabe", valor, fkempresa },
    dataType: "json",

    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-claveCuenta").text("La CLABE ya existe en el sistema.");
        $("#invalid-claveCuenta").css("display", "block");
        $("#txtCLABE").addClass("is-invalid");
      } else {
        $("#invalid-claveCuenta").text("La CLABE no es valida.");
        $("#invalid-claveCuenta").css("display", "none");
        $("#txtCLABE").removeClass("is-invalid");
      }
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function validaUnicaClabeChequesU(evt, input) {
  if ($("#txtClabeUp").val().length < 18) {
    $("#invalid-clabeCuentaU").text("La CLABE no es valida.");
    $("#invalid-clabeCuentaU").css("display", "block");
    $("#txtClabeUp").addClass("is-invalid");
  } else {
    validacionClabe2();
  }
}

function validacionClabe2() {
  var clave = document.getElementById("txtClabeUp").value;
  var idcuenta = document.getElementById("idCuentaU").value;
  var fkempresa = $("#emp_id").val();
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_clabeU",
      clave,
      idcuenta,
      fkempresa,
    },
    dataType: "json",
    success: function (data) {
      console.log("resp: " + data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-clabeCuentaU").text("La CLABE ya existe en el sistema.");
        $("#invalid-clabeCuentaU").css("display", "block");
        $("#txtClabeUp").addClass("is-invalid");
      } else {
        $("#invalid-clabeCuentaU").text("La CLABE no es valida.");
        $("#invalid-clabeCuentaU").css("display", "none");
        $("#txtClabeUp").removeClass("is-invalid");
      }
    },
  });
}

//VALIDACION DEL NUMERO DE CUENTA CHEQUES
function validaUnicaNoCuentaCheques(evt, input) {
  if ($("#txtNoCuenta").val().length < 1 || $("#txtNoCuenta").val().length > 12) {
    $("#invalid-noCuenta").text("La CLABE no es valida.");
    $("#invalid-noCuenta").css("display", "block");
    $("#txtNoCuenta").addClass("is-invalid");
  } else {
    validacionNoCuentaUno();
  }
}

function validacionNoCuentaUno() {
  var valor = document.getElementById("txtNoCuenta").value;
  var fkempresa = $("#emp_id").val();
  $.ajax({
    url: "php/funciones.php",
    data: { clase: "get_data", funcion: "validar_no_cuenta", valor, fkempresa },
    dataType: "json",
    success: function (data) {
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-noCuenta").text("No. Cuenta ya existe en el sistema.");
        $("#invalid-noCuenta").css("display", "block");
        $("#txtNoCuenta").addClass("is-invalid");
      } else {
        $("#invalid-noCuenta").text("Número de cuenta no valida.");
        $("#invalid-noCuenta").css("display", "none");
        $("#txtNoCuenta").removeClass("is-invalid");
      }
    },
  });
}

function validaUnicaNoCuentaChequesU(evt, input) {
  if ($("#txtNoCuentaU").val().length < 11) {
    $("#invalid-noCuentaU").text("La CLABE no es valida.");
    $("#invalid-noCuentaU").css("display", "block");
    $("#txtNoCuentaU").addClass("is-invalid");
  } else {
    validacionNoCuentaDos();
  }
}

function validacionNoCuentaDos() {
  var nocuenta = document.getElementById("txtNoCuentaU").value;
  var idcuenta = document.getElementById("idCuentaU").value;
  var fkempresa = $("#emp_id").val();
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_no_cuentaU",
      nocuenta,
      idcuenta,
      fkempresa,
    },
    dataType: "json",
    success: function (data) {
      console.log("respuesta : ", data);
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("¡Ya existe!");
        $("#invalid-noCuentaU").text(
          "Número de cuenta ya existe en el sistema."
        );
        $("#invalid-noCuentaU").css("display", "block");
        $("#txtNoCuentaU").addClass("is-invalid");
      } else {
        $("#invalid-noCuentaU").text("Número de cuenta no es valida.");
        $("#invalid-noCuentaU").css("display", "none");
        $("#txtNoCuentaU").removeClass("is-invalid");
      }
    },
  });
}

function validacionUnicoNoCredito() {
  var valor = document.getElementById("txtNoCredito").value;
  var fkempresa = $("#emp_id").val();
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_no_credito",
      valor,
      fkempresa,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-noCredito").text("El número ya existe en el sistema.");
        $("#invalid-noCredito").css("display", "block");
        $("#txtNoCredito").addClass("is-invalid");
      } else {
        $("#invalid-noCredito").css("display", "none");
        $("#txtNoCredito").removeClass("is-invalid");
      }
    },
  });
}

function validacionUnicoNoCreditoU() {
  var credito = document.getElementById("txtNoCreditoU").value;
  var idcuenta = document.getElementById("idCuentaU").value;
  var fkempresa = $("#emp_id").val();
  if (!credito) {
    $("#invalid-noCreditoU").text("La cuenta debe tener número de crédito.");
    $("#invalid-noCreditoU").css("display", "block");
    $("#txtNoCreditoU").addClass("is-invalid");
    return;
  }
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_no_creditoU",
      credito,
      idcuenta,
      fkempresa,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-noCreditoU").text("El número ya existe en el sistema.");
        $("#invalid-noCreditoU").css("display", "block");
        $("#txtNoCreditoU").addClass("is-invalid");
      } else {
        $("#invalid-noCreditoU").text(
          "La cuenta debe tener número de crédito."
        );
        $("#invalid-noCreditoU").css("display", "none");
        $("#txtNoCreditoU").removeClass("is-invalid");
      }
    },
  });
}

function validacionUnicoIdentificador(evt, input) {
  if ($("#txtIdCuenta").val().length < 11) {
    $("#invalid-identOtros").text("Identificador no valido.");
    $("#invalid-identOtros").css("display", "block");
    $("#txtIdCuenta").addClass("is-invalid");
  } else {
    validacionIdentificadorUno();
  }
}

function validacionUnicoIdentificadorU(evt, input) {
  if ($("#txtIdentificadorU").val().length < 11) {
    $("#invalid-identfCntU").text("Identificador no valido.");
    $("#invalid-identfCntU").css("display", "block");
    $("#txtIdentificadorU").addClass("is-invalid");
  } else {
    validacionIdentificadorUnoU();
  }
}

function validacionIdentificadorUno() {
  var valor = document.getElementById("txtIdCuenta").value;
  var fkempresa = $("#emp_id").val();
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_identificacor",
      valor,
      fkempresa,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-identOtros").text("El registro ya existe en el sistema.");
        $("#invalid-identOtros").css("display", "block");
        $("#txtIdCuenta").addClass("is-invalid");
      } else {
        $("#invalid-identOtros").text("La cuenta debe tener un identificador.");
        $("#invalid-identOtros").css("display", "none");
        $("#txtIdCuenta").removeClass("is-invalid");
      }
    },
  });
}

function validacionIdentificadorUnoU() {
  var valor = document.getElementById("txtIdentificadorU").value;
  var idcuenta = document.getElementById("idCuentaU").value;
  var fkempresa = $("#emp_id").val();
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_identificacorU",
      valor,
      idcuenta,
      fkempresa,
    },
    dataType: "json",
    success: function (data) {
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        $("#invalid-identfCntU").text("El registro ya existe en el sistema.");
        $("#invalid-identfCntU").css("display", "block");
        $("#txtIdentificadorU").addClass("is-invalid");
      } else {
        $("#invalid-identfCntU").text("La cuenta debe tener un identificador.");
        $("#invalid-identfCntU").css("display", "none");
        $("#txtIdentificadorU").removeClass("is-invalid");
      }
    },
  });
}
