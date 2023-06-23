<?php


///Contar  numero de decimales utiles (000 ya no es util).
function contarnDecimales($number){

    $decimas = 2;

    $precio1 = $number;
    
    /* Separar parte entera del decimal */
    $explPreecio1 = explode(".",$precio1);
    //print_r($explPreecio1);
    if(count($explPreecio1) >1){
        /* convertir la parte decimal a una array */
        $precio_split1 = str_split($explPreecio1[1]);
        /* Guarda donde se detecta la ultima parte decimal */
        $flagdecimales1 = 2;
        /* Cuenta las posisiones del array recorridas */
        $count1=0;

            foreach($precio_split1 as $numero1){
                $count1++;

                if($numero1 == 0){
                    
                }else{
                    /* Si el numero que encontro no es 0 guarda la posision en la posision de decimal */
                    $flagdecimales1 = $count1;
                }
            }
            if($flagdecimales1 > $decimas){
                $decimas = $flagdecimales1;
            }
    }
    
    

        ///Retorna numero de decimales utiles.
        return $decimas;

}


?>