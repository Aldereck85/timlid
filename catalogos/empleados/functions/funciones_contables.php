<?php
    function esBisiesto($year=NULL) {
        $year = ($year==NULL)? date('Y'):$year;
        return ( ($year%4 == 0 && $year%100 != 0) || $year%400 == 0 );
    }

    function calcularDiasVacaciones($num_dias_totales){
        switch ($num_dias_totales)
        {
        case ($num_dias_totales > 0 && $num_dias_totales < 2):
        $dias_vacaciones = 6;
        break;
        case ($num_dias_totales > 2 && $num_dias_totales < 3):
        $dias_vacaciones = 8;
        break;
        case ($num_dias_totales > 3 && $num_dias_totales < 4):
        $dias_vacaciones = 10;
        break;
        case ($num_dias_totales > 4 && $num_dias_totales < 5):
        $dias_vacaciones = 12;
        break;
        case ($num_dias_totales > 5 && $num_dias_totales < 10):
        $dias_vacaciones = 14;
        break;
        case ($num_dias_totales > 10 && $num_dias_totales < 15):
        $dias_vacaciones = 16;
        break;
        case ($num_dias_totales > 15 && $num_dias_totales < 20):
        $dias_vacaciones = 18;
        break;
        case ($num_dias_totales > 20 && $num_dias_totales < 25):
        $dias_vacaciones = 20;
        break;
        case ($num_dias_totales > 25 && $num_dias_totales < 30):
        $dias_vacaciones = 22;
        break;
        case ($num_dias_totales > 30 && $num_dias_totales < 35):
        $dias_vacaciones = 24;
        break;
        case ($num_dias_totales > 35 && $num_dias_totales < 40):
        $dias_vacaciones = 26;
        break;
        case ($num_dias_totales > 40 && $num_dias_totales < 45):
        $dias_vacaciones = 28;
        break;
        case ($num_dias_totales > 45 && $num_dias_totales < 50):
        $dias_vacaciones = 30;
        break;
        }

        return $dias_vacaciones;
    }

?>