<?php

class EventoRequest
{
    public static function rules(Eventos $data, $actividad)
    {

        switch ($data->funcion) {
            case 'index':

                break;
            case 'show':
                break;
            case 'store':
                switch ($actividad) {
                    case 1:
                        $validar_tarea = self::ValidateTask($data);
                        if ($validar_tarea == null) {
                            return $validar_tarea = null;
                        } else {
                            return $validar_tarea;
                        }
                        break;
                    case 2:
                        $validar_reunion = self::ValidateReunion($data);
                        if ($validar_reunion == null) {
                            return $validar_reunion = null;
                        } else {
                            return $validar_reunion;
                        }
                        break;
                    case 3:
                        $validar_llamada = self::ValidateLlamada($data);
                        if ($validar_llamada == null) {
                            return $validar_llamada = null;
                        } else {
                            return $validar_llamada;
                        }
                        break;
                    case 4:
                        $validar_correo = self::ValidateCorreo($data);
                        if ($validar_correo == null) {
                            return $validar_correo = null;
                        } else {
                            return $validar_correo;
                        }
                        break;
                }
                break;
            case 'update':
                break;
            case 'destroy':
                return $destroy_contact = self::validateDestroy($data);
                break;
        }
    }

    public static function ValidateTask($data)
    {

        if ($data->title == null || $data->title == '') {

            $output['error'] = true;
            $output['tipo'] = 'campo_tarea';
            $output['message'] = 'El campo titulo es requerido';
            return $output;
        }

        if ($data->contacto_id == 0 || $data->contacto_id == null) {

            $output['error'] = true;
            $output['tipo'] = 'campo_tarea';
            $output['message'] = 'Selecciona un contacto';
            return $output;
        }

        if ($data->color == null || $data->color == '') {

            $output['error'] = true;
            $output['tipo'] = 'campo_tarea';
            $output['message'] = 'El campo tarea es requerido';
            return $output;
        }

    }


    public static function ValidateReunion($data)
    {

        if ($data->title == null || $data->title == '') {

            $output['error'] = true;
            $output['tipo'] = 'campo_reunion';
            $output['message'] = 'El campo titulo es requerido';
            return $output;
        }

        if ($data->contacto_id == 0 || $data->contacto_id == null) {

            $output['error'] = true;
            $output['tipo'] = 'campo_reunion';
            $output['message'] = 'Selecciona un contacto';
            return $output;
        }

        if ($data->color == null || $data->color == '') {

            $output['error'] = true;
            $output['tipo'] = 'campo_reunion';
            $output['message'] = 'El campo tarea es requerido';
            return $output;
        }

    }

    public static function ValidateLlamada($data)
    {
        if ($data->title == null || $data->title == '') {

            $output['error'] = true;
            $output['tipo'] = 'campo_llamada';
            $output['message'] = 'El campo titulo es requerido';
            return $output;
        }

        if ($data->contacto_id == 0 || $data->contacto_id == null) {

            $output['error'] = true;
            $output['tipo'] = 'campo_llamada';
            $output['message'] = 'Selecciona un contacto';
            return $output;
        }

        if ($data->color == null || $data->color == '') {

            $output['error'] = true;
            $output['tipo'] = 'campo_llamada';
            $output['message'] = 'El campo tarea es requerido';
            return $output;
        }

    }

    public static function ValidateCorreo($data)
    {
        if ($data->title == null || $data->title == '') {

            $output['error'] = true;
            $output['tipo'] = 'campo_correo';
            $output['message'] = 'El campo titulo es requerido';
            return $output;
        }

        if ($data->contacto_id == 0 || $data->contacto_id == null) {

            $output['error'] = true;
            $output['tipo'] = 'campo_correo';
            $output['message'] = 'Selecciona un contacto';
            return $output;
        }

        if ($data->color == null || $data->color == '') {

            $output['error'] = true;
            $output['tipo'] = 'campo_correo';
            $output['message'] = 'El campo tarea es requerido';
            return $output;
        }


    }


}


?>