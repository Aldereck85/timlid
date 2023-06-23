$(document).ready(function () {
  var slimMesV;
  /* CREATE SELECTS */
  new SlimSelect({
    select: "#vendedor_input_H",
  });
  new SlimSelect({
    select: "#cliente_input_H",
  });
  new SlimSelect({
    select: "#estado_input_H",
  });
  new SlimSelect({
    select: "#cmbAño",
  });

});

function validEmptyInput(item, invalid = null) {
  const val = item.value;
  const parent = item.parentNode;
  let invalidDiv;
  if (invalid) {
    invalidDiv = document.getElementById(invalid);
  } else {
    for (let i = 0; i < parent.children.length; i++) {
      if (parent.children[i].classList.contains("invalid-feedback")) {
        invalidDiv = parent.children[i];
        break;
      }
    }
  }
  if (!val) {
    //item.classList.add("is-invalid");
    invalidDiv.style.display = "block";
  } else {
    //item.classList.remove("is-invalid");
    invalidDiv.style.display = "none";
  }
}

function Descarga_excel() {
  var cmbCliente = $("#cliente_input_H option:selected").val();
  var cmbVendedor = $("#vendedor_input_H option:selected").val();
  var cmbEstado = $("#estado_input_H option:selected").val();
  var cmbAño = $("#cmbAño option:selected").val();

  $().redirect(
    'php/descargar_excel_historico.php',
    {
    'cliente_id': cmbCliente,
    'vendedor_id': cmbVendedor,
    'estado_id': cmbEstado,
    'año' : cmbAño
    },
  "POST");
}

