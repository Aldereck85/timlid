function carga_cmbClientes(){
    //carga cmb clientes
  $.ajax({
    type:'POST',
    url:'../recepcion_pagos/functions/function_cmbClientes.php',
    dataType: "json",
    success:function(data){
      $.each(data, function(i) {
        document.getElementById("chosenClientes").innerHTML += "<option value='"+data[i].PKCliente+"'>"+data[i].razon_social+"</option>"; 
      });
    }
  });
}

function filtra_indexPagos(seleccion,fecha_desde,fecha_hasta){
    if(!validarImputs()){
        if(fecha_desde==""){
            fecha_desde="no"
        }
        if(fecha_hasta==""){
            fecha_hasta="no"
        }
        tablaC.ajax.url("functions/function_filtra_indexPagos.php?seleccion="+seleccion+"&fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta).load();
    }
}

function validarImputs(){
    redFlag = true;

    inputID= "chosenClientes"; 
    invalidDivID = "invalid-cmbCliente";
    textInvalidDiv = "Se requiere almenos un dato";

    inputID2= "txtDateFrom";
    invalidDivID2 = "invalid-txtDateFrom";

    inputID3= "txtDateTo";
    invalidDivID3 = "invalid-txtDateTo";

    if (($('select[name='+inputID+'] option').filter(':selected').val())=="f" && (($('#'+inputID2).val()=="") || ($('#'+inputID2).val()==null)) && (($('#'+inputID3).val()=="") || ($('#'+inputID3).val()==null))) {
      $("#" + inputID).addClass("is-invalid");
      $("#" + invalidDivID).show();
      $("#" + invalidDivID).text(textInvalidDiv);

      $("#" + inputID2).addClass("is-invalid");
      $("#" + invalidDivID2).show();
      $("#" + invalidDivID2).text(textInvalidDiv);

      $("#" + inputID3).addClass("is-invalid");
      $("#" + invalidDivID3).show();
      $("#" + invalidDivID3).text(textInvalidDiv);
    } else {
      $("#" + inputID).removeClass("is-invalid");
      $("#" + invalidDivID).hide();
      $("#" + invalidDivID).text(textInvalidDiv);

      $("#" + inputID2).removeClass("is-invalid");
      $("#" + invalidDivID2).hide();
      $("#" + invalidDivID2).text(textInvalidDiv);

      $("#" + inputID3).removeClass("is-invalid");
      $("#" + invalidDivID3).hide();
      $("#" + invalidDivID3).text(textInvalidDiv);
      redFlag = false;
    }
    return redFlag;
}
