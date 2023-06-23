<?php
    $dias_vacaciones = $_POST['dias_vacaciones'];
    $fechaInicial = $_POST['txtFechaInicio'];
    $fechaFinal = $_POST['txtFechaTermino'];
    $dias_trabajo = $_POST['dias_trabajo'];

    $fechaini = new DateTime($fechaInicial);
    $fechafin = new DateTime($fechaFinal);
    $num_dias = 0;
    $num_dias_fin_sem = 0;
    
    while( $fechaini <= $fechafin){
            
        $num_dias++;
        if($dias_trabajo == 5){
                if($fechaini->format('l')== 'Saturday' || $fechaini->format('l')== 'Sunday'){
                    $num_dias_fin_sem++;
                }
        }
        if($dias_trabajo == 6){
                if($fechaini->format('l')== 'Sunday'){
                    $num_dias_fin_sem++;
                }
        }
        $fechaini->modify("+1 days");
    }

    $num_dias_vacaciones = $num_dias - $num_dias_fin_sem;
    
    if(strtotime($fechaInicial)  > strtotime($fechaFinal)){
        echo "2";
    }
    elseif($num_dias_vacaciones != $dias_vacaciones){
        echo "1";
    }

?>
