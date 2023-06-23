function validarUnicoPuesto(item) {
  var valor = item.value;
  console.log("Valor puesto:  " + valor);
  $.ajax({
    url: "puestos/php/funciones.php",
    data: { clase: "get_data", funcion: "validar_puesto", data: valor },
    dataType: "json",
    success: function (data) {
      console.log("respuesta sucursal validado: ", data);
      /* Validar si ya existe el identificador con ese nombre*/
      if (parseInt(data[0]["existe"]) == 1) {
        item.nextElementSibling.innerText =
          "El puesto ya esta registrado en el sistema.";
        item.nextElementSibling.style.display = "block";
        item.classList.add("is-invalid");
      } else {
        item.nextElementSibling.innerText =
          "El nombre del puesto es requerido.";
        item.nextElementSibling.style.display = "none";
        item.classList.remove("is-invalid");
      }
    },
  });
}
function validarRelacionPuesto(valor) {
  $.ajax({
    url: "puestos/php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "validar_existeRelacionPuesto",
      data: valor,
    },
    dataType: "json",

    success: function (data) {
      /* Validar si ya existe relacion con clientes*/
      if (parseInt(data[0]["existe"]) == 1) {
        console.log("Relacion con cliente", data);

        $("#txtareau").prop("disabled", true);

        var eliminar = document.getElementById("idPuestoD");
        eliminar.style.display = "none";
        var modificar = document.getElementById("btnEditarPuesto");
        modificar.style.display = "none";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "text");
      } else {
        $("#txtareau").prop("disabled", false);

        var eliminar = document.getElementById("idPuestoD");
        eliminar.style.display = "block";
        var modificar = document.getElementById("btnEditarPuesto");
        modificar.style.display = "block";

        var nota = document.getElementById("notaExisteRelacion");
        nota.setAttribute("type", "hidden");
      }
    },
  });
}
