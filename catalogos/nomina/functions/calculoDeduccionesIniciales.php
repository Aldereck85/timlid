<?php
   //todos aquellos que no tienen un calculo especifico
   if($tipo_base == 1){   
    $importe_p = bcdiv($emp['Sueldo'] * ($cantidad_base / 100),1,2); 
   }

   if($tipo_base == 2){
    $importe_p = $cantidad_base;           
   }          
   $exento_p = 0.00;
