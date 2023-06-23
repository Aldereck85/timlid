<?php
//tipo porcentaje o cantidad

//aguinaldo   tipo_concepto : 9
//participación de los trabajadores en las utilidades       tipo_concepto : 10

if($id_percepcion == 15){
    //prima dominical   tipo_concepto : 6
    $stmt = $conn->prepare('SELECT cantidad FROM parametros WHERE descripcion = "UMA" ');
    $stmt->execute();
    $row_parametros = $stmt->fetch();
    $UMA = $row_parametros['cantidad'];

    if($tipo_base == 1){   
        $primaDominical = bcdiv($emp['Sueldo'] * ($cantidad_base / 100),1,2); 
    }

    if($tipo_base == 2){
        $primaDominical = $cantidad_base;           
    } 


    if($primaDominical > $UMA){
        $exento_p = $UMA;
        $importe_p = $primaDominical - $UMA;
    }
    else{
        $importe_p = $primaDominical;
        $exento_p = 0.00;
    }

    
}
else{
   //todos aquellos que no tienen un calculo especifico

   if($id_percepcion == 33){
        //turnos extras Otros ingresos por salarios
        $dias = 1;
   }

   if($tipo_base == 1){   
    $importe_p = bcdiv($emp['Sueldo'] * ($cantidad_base / 100),1,2); 
   }

   if($tipo_base == 2){
    $importe_p = $cantidad_base;           
   }          
   $exento_p = 0.00;    
}
