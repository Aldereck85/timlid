//Función para validar el importe ingresado en cada input
function valida_Inputs_Importe(idPago, id_factura, importe, is_invoice){ 
    var result;
    $.ajax({
        type:'POST',
        url:'../recepcion_pagos/functions/function_RecuperaSaldoInsoluto.php',
        data:{_id_factura:id_factura, 
              _idPago:idPago, 
              _importe:importe,
              _is_invoice:is_invoice},
        dataType: "json",
        async:false,
        success:function(data){
            if(data['result']==1){
                result= true;
            }else{
                if(data['limite']==null){
                    data['limite']=0;
                }
                result= data['limite'];
            }  
        }
      });
    return result;
}