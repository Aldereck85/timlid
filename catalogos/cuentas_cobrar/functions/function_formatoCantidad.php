<?php
    function formatoCantidad($valor){
        //borra los 0 de más
        $valor=floatval($valor);
    
        //si tiene menos de 2 decimales le añade el/los 0
        $aux = explode('.', $valor);
        if(count($aux) > 0){
            if(count($aux) == 1 || strlen($aux[1]) <= 2){
                $valor=number_format($valor,2);
            }else{
            $valor=number_format($valor,strlen($aux[1]));
            }
        }
        return $valor;
    }
?>