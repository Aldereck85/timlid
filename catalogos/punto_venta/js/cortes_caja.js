$(document).ready( (e) => 
{
  cmbBalancePerPeriod = new SlimSelect({
    select: "#cmbBalancePerPeriod",
    placeholder: "Seleccione un corte de caja..."
  });
  
  loadData("");
  loadCombo('cashRegisterClosing',"#cmbBalancePerPeriod","",$("#txtCashRegisterId").val(),"");
});

$("#tblCashRegisterCut tbody").on("click","td",function(e){
  var caja_id = $("#txtCashRegisterId").val();
  row_index = tblCashRegisterCut.cell(this).index().row;
  cell_index = tblCashRegisterCut.cell( this ).index().column
  row_data = tblCashRegisterCut.row( row_index ).data();

  switch(cell_index){
    case 2:
      if(e.target.getAttribute("id") === "edit"){

      }
      break;
  }
});

document.getElementById("cmbBalancePerPeriod").addEventListener("change", () =>{
  var value = cmbBalancePerPeriod.selected();
  loadData(value);
});

function loadCombo(funcion,input,data,value,texto){

  $.ajax({
    url: "php/funciones.php",
    method:"POST",
    data: {
      clase: "get_data",
      funcion: "get_"+funcion,
      value: value
    },
    datatype: "json",
    success: function(respuesta){
      var res = JSON.parse(respuesta);
      html = "<option data-placeholder='true'></option>";
      if(res.length > 0){
        $.each(res,function(i){
          if(res[i].id === parseInt(data)){
            html += "<option value='"+res[i].id+"' selected>"+res[i].texto+"</option>";
          } else {
            
            html += "<option value='"+res[i].id+"'>"+res[i].texto+"</option>";
          }
        });
      } else {
        html += "<option value='0'>No hay registros.</option>";
      }
      
      $(input).html(html);
    },
    error: function(error){
      console.log(error);
    }
  });
  
}

function loadData(value){
  var caja_id = $("#txtCashRegisterId").val();
  
  if(value === ""){
    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_generalDataCashRegisterCut',
        value: caja_id
      },
      asyn:false,
      dataType: 'json',
      success: function(response){
        r = response[0];
        
        document.getElementById("general_balance").textContent = "$ " + numeral(r.total_neto).format('0,000,000,000.00');

        document.getElementById("efectivo_contado_general").innerHTML = "$ " + numeral(r.efectivo_contado).format('0,000,000,000.00');
        document.getElementById("efectivo_calculado_general").innerHTML = "$ " + numeral(r.efectivo_calculado).format('0,000,000,000.00');
        document.getElementById("efectivo_direfencia_general").innerHTML = "$ " + numeral(r.efectivo_diferencia).format('0,000,000,000.00');
        document.getElementById("credito_contado_general").innerHTML = "$ " + numeral(r.credito_contado).format('0,000,000,000.00');
        document.getElementById("credito_calculado_general").innerHTML = "$ " + numeral(r.credito_calculado).format('0,000,000,000.00');
        document.getElementById("credito_direfencia_general").innerHTML = "$ " + numeral(r.credito_diferencia).format('0,000,000,000.00');
        document.getElementById("transferencia_contado_general").innerHTML = "$ " + numeral(r.transferencia_contado).format('0,000,000,000.00');
        document.getElementById("transferencia_calculado_general").innerHTML = "$ " + numeral(r.transferencia_calculado).format('0,000,000,000.00');
        document.getElementById("transferenciacredito_direfencia_general").innerHTML = "$ " + numeral(r.transferencia_diferencia).format('0,000,000,000.00');
        document.getElementById("total_contado_general").innerHTML = "$ " + numeral(r.total_contado).format('0,000,000,000.00');
        document.getElementById("total_calculado_general").innerHTML = "$ " + numeral(r.total_calculado).format('0,000,000,000.00');
        document.getElementById("total_direfencia_general").innerHTML = "$ " + numeral(r.total_diferencia).format('0,000,000,000.00');

        document.getElementById("efectivo_retirado_general").innerHTML = "$ " + numeral(r.efectivo_retirado).format('0,000,000,000.00');
        document.getElementById("credito_retirado_general").innerHTML = "$ " + numeral(r.credito_retirado).format('0,000,000,000.00');
        document.getElementById("transferencia_retirado_general").innerHTML = "$ " + numeral(r.transferencia_retirado).format('0,000,000,000.00');
        
      },
      error: function(error){
        console.log(error);
      }

    });
  } else {

    $.ajax({
      method: "POST",
      url: "php/funciones.php",
      data: {
        clase: 'get_data',
        funcion: 'get_balacenPerPeriodDataCashRegisterCut',
        value: caja_id,
        value1: value
      },
      asyn:false,
      dataType: 'json',
      success: function(response){
        r = response[0];
        document.getElementById("balancePerPeriod").textContent = "$ " + numeral(r.total_neto).format('0,000,000,000.00');

        document.getElementById("efectivo_contado").innerHTML = "$ " + numeral(r.efectivo_contado).format('0,000,000,000.00');
        document.getElementById("efectivo_calculado").innerHTML = "$ " + numeral(r.efectivo_calculado).format('0,000,000,000.00');
        document.getElementById("efectivo_direfencia").innerHTML = "$ " + numeral(r.efectivo_diferencia).format('0,000,000,000.00');
        document.getElementById("credito_contado").innerHTML = "$ " + numeral(r.credito_contado).format('0,000,000,000.00');
        document.getElementById("credito_calculado").innerHTML = "$ " + numeral(r.credito_calculado).format('0,000,000,000.00');
        document.getElementById("credito_direfencia").innerHTML = "$ " + numeral(r.credito_diferencia).format('0,000,000,000.00');
        document.getElementById("transferencia_contado").innerHTML = "$ " + numeral(r.transferencia_contado).format('0,000,000,000.00');
        document.getElementById("transferencia_calculado").innerHTML = "$ " + numeral(r.transferencia_calculado).format('0,000,000,000.00');
        document.getElementById("transferencia_direfencia").innerHTML = "$ " + numeral(r.transferencia_diferencia).format('0,000,000,000.00');
        document.getElementById("total_contado").innerHTML = "$ " + numeral(r.total_contado).format('0,000,000,000.00');
        document.getElementById("total_calculado").innerHTML = "$ " + numeral(r.total_calculado).format('0,000,000,000.00');
        document.getElementById("total_direfencia").innerHTML = "$ " + numeral(r.total_diferencia).format('0,000,000,000.00');

        document.getElementById("efectivo_retirado").innerHTML = "$ " + numeral(r.efectivo_retirado).format('0,000,000,000.00');
        document.getElementById("credito_retirado").innerHTML = "$ " + numeral(r.credito_retirado).format('0,000,000,000.00');
        document.getElementById("transferencia_retirado").innerHTML = "$ " + numeral(r.transferencia_retirado).format('0,000,000,000.00');
      },
      error: function(error){
        console.log(error);
      }

    });     
  }
}