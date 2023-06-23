<?php
date_default_timezone_set('America/Mexico_City');
session_start();

class conectar
{ //Llamado al archivo de la conexión.

    public function getDB()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }

    public function getPermisos($idProyecto)
    {
        $db = $this->getDb();

        ///////COMENTADA LA VERSION DE SOLO 1 RESPONSABLE///////////////
        /*      $stmt = $db->prepare("SELECT * FROM proyectos WHERE PKProyecto = :idProyecto"); //Where usuario id sea= $_SESSION["id"];
        $stmt->bindValue(":idProyecto", $idProyecto);
        $stmt->execute();
        $rowPermiso = $stmt->fetch(); */

        $stmt = $db->prepare("SELECT Proyecto, usuarios_id from proyectos as p 
        inner join responsables_proyecto as rp on rp.proyectos_PKProyecto = p.PKProyecto where rp.proyectos_PKProyecto = :idProyecto"); //Where usuario id sea= $_SESSION["id"];
        $stmt->bindValue(":idProyecto", $idProyecto);
        $stmt->execute();
        $rowPermiso = $stmt->fetchAll();
        $idUsuario = [];
        foreach ($rowPermiso as $us) {
            /* print_r($us["usuarios_id"]); */
            array_push($idUsuario, $us["usuarios_id"]);
        }
        $UsSession = $_SESSION['PKUsuario'];

        if (in_array(strval($UsSession), $idUsuario)) {
            return 1;
        } else {
            return 0;
        }

        /* if ($rowPermiso['FKResponsable'] == $_SESSION['PKUsuario']) {
            return 1;
        } else {
            return 0;
        } */
    }

    public function getPermisosResponsables($idProyecto, $idTarea)
    {
        $db = $this->getDb();

        $columnas = sprintf("SELECT PKColumnaProyecto FROM columnas_proyecto WHERE FKProyecto=? AND tipo = 1 ORDER BY tipo");
        $stC = $db->prepare($columnas);
        $stC->execute(array($idProyecto));
        $count = $stC->rowCount();

        if ($count > 0) {
            $query = sprintf("SELECT FKUsuario as id FROM responsables_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea=PKTarea WHERE FKTarea=?");
            $stmt = $db->prepare($query);
            $stmt->execute(array($idTarea));
            $responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($responsables);
            if (in_array($_SESSION['PKUsuario'], array_column($responsables, 'id'))) {
                //echo "existe";
                return 1;
            } else {
                //echo "prohibido";
                return 0;
            }
        } else {
            return 1;
        }
    }
}

class admin_data
{

    public function getProject($id)
    {

        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf("SELECT * FROM proyectos WHERE PKProyecto = ?");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id));
            $row = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($_SESSION['PKUsuario'] == $row[0]->FKResponsable) {
                $row[0]->permiso = 1;
            } else {
                $row[0]->permiso = 0;
            }

            //return $stmt->fetchAll(PDO::FETCH_OBJ);
            return $row;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getProjectLite($lite)
    {

        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf("SELECT * FROM proyectos WHERE Lite = ?");
            $stmt = $db->prepare($query);
            $stmt->execute(array($lite));
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getLevels($id)
    { //Etapas y sus columnas
        $con = new conectar();
        $db = $con->getDb();

        try {
            //Guardando la consulta en la variable $query
            $query = sprintf("SELECT PKColumnaProyecto, nombre, tipo, Orden FROM columnas_proyecto WHERE FKProyecto = ? ORDER BY Orden ASC");
            $stmt = $db->prepare($query); //Preparando la consulta
            $stmt->execute(array($id));
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //var_dump($columns); echo "<br><br><br><br>";

            $query = sprintf("SELECT * FROM proyectos WHERE PKProyecto = ?");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id));
            $row = $stmt->fetchAll();

            $stmt1 = $db->prepare("SELECT Proyecto, usuarios_id from proyectos as p 
                inner join responsables_proyecto as rp on rp.proyectos_PKProyecto = p.PKProyecto where rp.proyectos_PKProyecto = :idProyecto"); //Where usuario id sea= $_SESSION["id"];
            $stmt1->bindValue(":idProyecto", $id);
            $stmt1->execute();
            $rowPermiso = $stmt1->fetchAll();
            $idUsuario = [];
            foreach ($rowPermiso as $us) {
                /* print_r($us["usuarios_id"]); */
                array_push($idUsuario, $us["usuarios_id"]);
            }
            $UsSession = $_SESSION['PKUsuario'];

            if (in_array(strval($UsSession), $idUsuario)) {
                $permiso = 1;
            } else {
                $permiso = 0;
            }


            /*if(count($columns) > 0){
            $x = 0;
            foreach ($columns as $c) {
            $columns[$x]['permiso'] = $permiso;
            $x++;
            }
            }
            else{
            $columns[0]['permiso'] = $permiso;
            }*/

            //var_dump($columns); echo "<br><br><br><br>";

            /* if ($_SESSION['PKUsuario'] == $row[0]['FKResponsable']) {
                $permiso = 1;
            } else {
                $permiso = 0;
            } */

            $permisosArray['permiso'] = $permiso;

            $query1 = sprintf("SELECT * FROM etapas WHERE FKProyecto = ? ORDER BY Orden");
            $stmt1 = $db->prepare($query1);
            $stmt1->execute(array($id));
            $etapas = $stmt1->fetchAll(PDO::FETCH_ASSOC); //Guardalo como array

            //if(count($etapas) > 0){
            for ($i = 0; $i < count($etapas); $i++) {
                //array_push(array al que le quieres añadir la inforamción , variable que vas a añadir)
                array_push($etapas[$i], $columns);
            }
            /*}
            else{
            $etapas = $columns;
            }*/
            array_push($etapas, $permisosArray);

            return $etapas;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getTask($id)
    { //Tareas
        $con = new conectar();
        $db = $con->getDb();

        try {

            $query = sprintf("SELECT FKEtapa,tareas.FKProyecto,tareas.Orden,PKTarea,Tarea,Terminada,Estado,etapas.color FROM tareas LEFT JOIN etapas ON PKEtapa = FKetapa WHERE tareas.FKProyecto = ? ORDER BY Orden");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id));
            $tasks = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($tasks as $t => $task) {
                $permiso = $con->getPermisos($id);
                //$permisoResponsable = $con->getPermisosResponsables($id, $task->PKTarea);
                //echo "este es ".$permisoResponsable;
                if ($permiso == 0) {
                    //if($permisoResponsable == 0){
                    $task->permiso = 0;
                    /*}
                else{
                $task->permiso = 1;
                }*/
                } else {
                    $task->permiso = 1;
                }
            }

            return $tasks; //devuelvelo como objeto.

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getInfo($array, $id)
    { //Información de las tareas
        $con = new conectar();
        $db = $con->getDb();
        $data = [];
        //En el array vienen los tipos de columnas
        //1:responsables_tarea.
        $responsables = [];
        //2:estado_tarea.
        $tareas_estados = [];
        //3:fecha_tarea
        $fechas = [];
        //4:Hipervinculo
        $hipervinculo = [];
        //5:id del elemento
        $idElemento = [];
        //6:menú despegable
        $menu_desplegable = [];
        //7:Teléfono
        $telefono = [];
        //8:Números
        $numeros = [];
        //9:Verificar
        $verificar = [];
        //10: Progreso
        $progreso = [];
        //11:Rango
        $rango = [];
        //12:texto
        $texto_task = [];
        //13:Números simple
        $numeros_simple = [];
        //14:Texto_corto
        $short_text = [];
        //15:Verificar subtareas
        $check_subtareas = [];
        //16:Progreso subtareas
        $progress_subtareas = [];
        try {
            $stmt = $db->prepare("SELECT u.empresa_id
                    FROM responsables_tarea AS rt 
                    LEFT JOIN usuarios as u ON rt.FKUsuario = u.id 
                    WHERE rt.FKProyecto = ? LIMIT 1");
            $stmt->execute(array($id)); //array()
            $idEmpresa = $stmt->fetch(PDO::FETCH_ASSOC);
            for ($i = 0; $i < count($array); $i++) {
                if ($array[$i] == 1) { //Responsable.
                    $query = sprintf("SELECT rt.FKTarea,cp.PKColumnaProyecto,rt.PKResponsable as id,
                    u.id as idusuario,u.usuario, u.imagen, cp.Orden as cOrden, cp.Tipo, t.Orden as tOrden,
					CONCAT(e.Nombres,' ',e.PrimerApellido) as Texto, u.empresa_id
					FROM responsables_tarea  as rt
                    LEFT JOIN columnas_proyecto as cp ON rt.FKColumnaProyecto = cp.PKColumnaProyecto
                    LEFT JOIN empleados as e ON rt.FKUsuario = e.PKEmpleado 
                    LEFT JOIN usuarios as u ON e.PKEmpleado = u.id 
                    LEFT JOIN tareas as t ON rt.FKTarea = t.PKTarea 
                    WHERE rt.FKProyecto = ? ORDER BY t.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id)); //array()
                    $responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($responsables as $r) {
                        $permiso = $con->getPermisos($id);

                        if ($permiso == 0) {
                            $responsables[$x]['permiso'] = 0;
                        } else {
                            $responsables[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                    //var_dump($responsables);
                }
                if ($array[$i] == 2) { //Estado
                    $query = sprintf("SELECT PKEstadoTarea as id, colores_columna.color, colores_columna.nombre as Texto, PKColorColumna, FKTarea, PKColumnaProyecto, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM estado_tarea LEFT JOIN colores_columna ON FKColorColumna = PKColorColumna LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE colores_columna.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $tareas_estados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($tareas_estados as $te) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $te['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $tareas_estados[$x]['permiso'] = 0;
                            } else {
                                $tareas_estados[$x]['permiso'] = 1;
                            }
                        } else {
                            $tareas_estados[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 3) { //Fecha
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKFecha as id,Fecha as Texto, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM fecha_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE fecha_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($fechas as $f) {
                        $permiso = $con->getPermisos($id);

                        if ($permiso == 0) {
                            $fechas[$x]['permiso'] = 0;
                        } else {
                            $fechas[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 4) { //Hipervinculo
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKHipervinculo as id,Texto,Direccion,columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM hipervinculo_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE hipervinculo_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $hipervinculo = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($hipervinculo as $h) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $h['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $hipervinculo[$x]['permiso'] = 0;
                            } else {
                                $hipervinculo[$x]['permiso'] = 1;
                            }
                        } else {
                            $hipervinculo[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 5) { //id del elemento
                    $query = sprintf("SELECT PKColumnaProyecto,Tipo,columnas_proyecto.Orden as cOrden,PKTarea as id,tareas.Orden as tOrden FROM columnas_proyecto LEFT JOIN tareas ON tareas.FKProyecto = ? WHERE tipo = ? and columnas_proyecto.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id, 5, $id));
                    $idElemento = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                if ($array[$i] == 6) { //menu despegable
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKMenu as id,columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM menu_columna LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE menu_columna.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $menu_desplegable = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($menu_desplegable as $md) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $md['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $menu_desplegable[$x]['permiso'] = 0;
                            } else {
                                $menu_desplegable[$x]['permiso'] = 1;
                            }
                        } else {
                            $menu_desplegable[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 7) { //Telefono
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKTelefono as id,Telefono,columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM telefono_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE telefono_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $telefono = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($telefono as $t) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $t['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $telefono[$x]['permiso'] = 0;
                            } else {
                                $telefono[$x]['permiso'] = 1;
                            }
                        } else {
                            $telefono[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 8) { //Números
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKNumeros as id,Numero,Simbolo,Lugar,columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden FROM numeros_tabla LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE numeros_tabla.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $numeros = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($numeros as $n) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $n['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $numeros[$x]['permiso'] = 0;
                            } else {
                                $numeros[$x]['permiso'] = 1;
                            }
                        } else {
                            $numeros[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 9) { //Verificar
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKVerificacion as id,columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, tareas.Estado FROM verificacion_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE verificacion_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $verificar = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($verificar as $v) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $v['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $verificar[$x]['permiso'] = 0;
                            } else {
                                $verificar[$x]['permiso'] = 1;
                            }
                        } else {
                            $verificar[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 10) { //Progreso
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKProgreso as id, Progreso, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, tareas.Estado FROM progreso_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE progreso_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $progreso = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                if ($array[$i] == 11) { //Rango de fechas
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKRangoFecha as id, Rango, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, tareas.Terminada FROM rango_fecha LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE rango_fecha.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $rango = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($rango as $r) {
                        $permiso = $con->getPermisos($id);

                        if ($permiso == 0) {
                            $rango[$x]['permiso'] = 0;
                        } else {
                            $rango[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 12) { //Texto largo
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKTexto as id,Texto, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, tareas.Terminada FROM texto_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE texto_tarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $texto_task = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($texto_task as $tt) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $tt['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $texto_task[$x]['permiso'] = 0;
                            } else {
                                $texto_task[$x]['permiso'] = 1;
                            }
                        } else {
                            $texto_task[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 13) { //Números simple
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKNumerosSimple as id,Numero, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, tareas.Terminada FROM numeros_simple LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE numeros_simple.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $numeros_simple = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($numeros_simple as $ns) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $ns['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $numeros_simple[$x]['permiso'] = 0;
                            } else {
                                $numeros_simple[$x]['permiso'] = 1;
                            }
                        } else {
                            $numeros_simple[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 14) { //Texto corto
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKTexto as id,Texto, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, tareas.Terminada FROM texto_corto LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE texto_corto.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $short_text = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($short_text as $st) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $st['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $short_text[$x]['permiso'] = 0;
                            } else {
                                $short_text[$x]['permiso'] = 1;
                            }
                        } else {
                            $short_text[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 15) { //Verificar subtareas
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKVerificaSub as id,columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, tareas.Terminada FROM verificar_subtarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE verificar_subtarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $check_subtareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $x = 0;
                    foreach ($check_subtareas as $cs) {
                        $permiso = $con->getPermisos($id);
                        $permisoResponsable = $con->getPermisosResponsables($id, $cs['FKTarea']);
                        //echo "este es ".$permisoResponsable;
                        if ($permiso == 0) {
                            if ($permisoResponsable == 0) {
                                $check_subtareas[$x]['permiso'] = 0;
                            } else {
                                $check_subtareas[$x]['permiso'] = 1;
                            }
                        } else {
                            $check_subtareas[$x]['permiso'] = 1;
                        }
                        $x++;
                    }
                }

                if ($array[$i] == 16) { //Progreso subtareas
                    $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKProgresoSubtarea as id, Progreso, columnas_proyecto.Orden as cOrden, Tipo, tareas.Orden as tOrden, tareas.Terminada FROM progreso_subtarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE progreso_subtarea.FKProyecto = ? ORDER BY tareas.Orden ASC");
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($id));
                    $progress_subtareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            $resultado = array_merge($responsables, $tareas_estados, $fechas, $hipervinculo, $idElemento, $menu_desplegable, $telefono, $numeros, $verificar, $progreso, $rango, $texto_task, $numeros_simple, $short_text, $check_subtareas, $progress_subtareas);
            //var_dump($resultado);

            $ordenamiento = array_column($resultado, 'tOrden');
            //var_dump($ordenamiento);
            array_multisort($ordenamiento, SORT_ASC, $resultado);
            //var_dump($resultado);
            array_push($data, $resultado);
            $data['idEmpresa'] = $idEmpresa;
            return $data;
            //$query = sprintf("SELECT * FROM estado_tarea WHERE FKProyecto = ? ORDER BY FKColumnaProyecto ASC");
            //$stmt = $db->prepare($query);
            //$stmt->execute(array($id));
            //return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getColor($id, $color)
    { //Actualiza el color de la etapa
        $con = new conectar();
        $db = $con->getDb();

        $select = sprintf("SELECT FKProyecto FROM etapas WHERE PKEtapa=?");
        $stmt = $db->prepare($select);
        $stmt->execute(array($id));
        $row = $stmt->fetch();

        $permiso = $con->getPermisos($row['FKProyecto']);

        if ($permiso == 0) {
            return -1;
        }

        try {
            $picker = sprintf("UPDATE etapas SET color=? WHERE PKEtapa=?");
            $stmt = $db->prepare($picker);
            $stmt->execute(array($color, $id));

            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getFecha($id, $fecha)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $date = sprintf("UPDATE fecha_tarea SET Fecha=? WHERE PKFecha=?");
            $stmt = $db->prepare($date);
            $stmt->execute(array($fecha, $id));

            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getColorColumna($id_estado, $id_columna)
    { //consulta los colores de una columna estado
        $con = new conectar();
        $db = $con->getDb();

        try {
            $consulta = sprintf("SELECT * FROM colores_columna WHERE FKColumnaProyecto = ?");
            $stmt = $db->prepare($consulta);
            $stmt->execute(array($id_columna));
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function setColorTarea($id_estado, $id_color, $flag)
    {
        $con = new conectar();
        $db = $con->getDb();
        $response = [];
        $tarea_update = "Update";
        $total_progresos = [];
        $progreso_update = "no";

        try {

            //Obteniendo el id de la Tarea
            $tarea = sprintf("SELECT FKTarea,FKColorColumna FROM estado_tarea WHERE PKEstadoTarea = ?");
            $stmt1 = $db->prepare($tarea);
            $stmt1->execute(array($id_estado));
            $data = $stmt1->fetch(PDO::FETCH_ASSOC);

            //Actualizando el estado de la tarea (el id del color)
            $actualiza = sprintf("UPDATE estado_tarea SET FKColorColumna = ? WHERE PKEstadoTarea = ?");
            $stmt = $db->prepare($actualiza);
            $stmt->execute(array($id_color, $id_estado));

            //Consultar si tiene columna de verificación
            $verificar = sprintf("SELECT * FROM verificacion_tarea WHERE FKTarea = ?");
            $stmt3 = $db->prepare($verificar);
            $stmt3->execute(array($data["FKTarea"]));
            $data1 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $cuenta_verificacion_tarea = $stmt3->rowCount();

            //Obteniendo el total de columnas tipo estado:
            $total_columnas = sprintf("SELECT * FROM estado_tarea WHERE FKTarea = ?");
            $stmt5 = $db->prepare($total_columnas);
            $stmt5->execute(array($data["FKTarea"]));
            $total_columnas = $stmt5->rowCount(); //total de columnas tipo estado en el proyecto

            //Comprobar si existe una columna de tipo 10 (progreso)
            $verificarP = sprintf("SELECT * FROM progreso_tarea WHERE FKTarea = ?");
            $stmtP = $db->prepare($verificarP);
            $stmtP->execute(array($data["FKTarea"]));
            $cuenta_progreso_tarea = $stmtP->rowCount();

            //Actualizando registro de actividades de la tarea
            date_default_timezone_set('America/Mexico_City');
            $fecha = date('Y-m-d H:i:s');
            $insertar_actividad = sprintf("INSERT INTO chat_actividad (Tipo, Fecha, FKTarea, FKUsuario, FKColorColumnaOrigen, FKColorColumnaDestino) VALUES (?,?,?,?,?,?)");
            $stmt3 = $db->prepare($insertar_actividad);
            $stmt3->execute(array(1, $fecha, $data["FKTarea"], $_SESSION['PKUsuario'], $data["FKColorColumna"], $id_color));

            if ($flag == "true") { //task done
                if ($total_columnas == 1) { //Sólo hay una columna de tipo estado, se marca terminada la tarea

                    $actualiza1 = sprintf("UPDATE tareas SET Estado = ? WHERE PKTarea = ?");
                    $stmt2 = $db->prepare($actualiza1);
                    $stmt2->execute(array(1, $data["FKTarea"]));

                    $response = [
                        "updatecheck" => "ok",
                        "progreso_update" => $progreso_update,
                    ]; //Se actualiza la tarea en la tabla tareas

                } else { //Hay más de una columna de tipo estado, Saber si todas tienen el color que marca la tarea completada
                    $estados_tareas = $stmt5->fetchAll(PDO::FETCH_ASSOC);
                    //var_dump($estados_tareas);
                    for ($i = 0; $i < count($estados_tareas); $i++) {

                        //var_dump("Vuelta para checar el color de la Tarea: ".$i);

                        $stmt01 = $db->prepare("SELECT color FROM colores_columna WHERE PKColorColumna = ?");
                        $stmt01->execute(array($estados_tareas[$i]["FKColorColumna"]));
                        $color_done_proof = $stmt01->fetch(PDO::FETCH_ASSOC);
                        //var_dump("color de la tarea: ".$color_done_proof["color"]);

                        if ($color_done_proof["color"] !== "#28c67a") {
                            $tarea_update = "no_Update";
                            $i = count($estados_tareas);
                        }
                    }

                    //var_dump("La variable tarea_update es igual a: ".$tarea_update);

                    if ($tarea_update == "Update") {

                        $actualiza1 = sprintf("UPDATE tareas SET Estado = ? WHERE PKTarea = ?");
                        $stmt2 = $db->prepare($actualiza1);
                        $stmt2->execute(array(1, $data["FKTarea"]));

                        $response = [
                            "updatecheck" => "ok",
                            "progreso_update" => $progreso_update,
                        ]; //Se actualiza la tarea en la tabla tareas

                    } else {

                        $actualiza1 = sprintf("UPDATE tareas SET Estado = ? WHERE PKTarea = ?");
                        $stmt2 = $db->prepare($actualiza1);
                        $stmt2->execute(array(0, $data["FKTarea"]));

                        $response = [
                            "updatecheck" => "ok",
                            "progreso_update" => $progreso_update,
                        ]; //Se actualiza la tarea en la tabla tareas
                    }
                }

                //Si existe una columna de verificación
                if ($cuenta_verificacion_tarea !== 0) {

                    $actualizar_tareas = $db->prepare("SELECT FKTarea,Estado,PKVerificacion FROM tareas left join verificacion_tarea on PKTarea = FKTarea WHERE FKTarea = ?");
                    $actualizar_tareas->execute(array($data["FKTarea"]));
                    $array_actualizar_tareas = $actualizar_tareas->fetchAll(PDO::FETCH_OBJ);

                    $response = [
                        "updatecheck" => "checar", //Actualizar la casilla de verificación
                        "progreso_update" => $progreso_update,
                        $array_actualizar_tareas,
                    ];
                }
            } else { //Se marca la tarea como no terminada
                $actualiza1 = sprintf("UPDATE tareas SET Estado = ? WHERE PKTarea = ?");
                $stmt2 = $db->prepare($actualiza1);
                $stmt2->execute(array(0, $data["FKTarea"]));

                $response = [
                    "updatecheck" => "ok",
                    "progreso_update" => $progreso_update,
                ]; //Se actualiza la tarea en la tabla tareas

                //Si existe una columna de verificación
                if ($cuenta_verificacion_tarea !== 0) {

                    $response = [
                        "updatecheck" => 0, //Actualizar la casilla de verificación
                        "progreso_update" => $progreso_update,
                        $data1,
                    ]; //Se debe actualizar el checkmark a undone
                }
            }

            //calcular el porcentaje de progreso.
            if ($cuenta_progreso_tarea !== 0) { //Tiene columna progreso el proyecto

                $response["progreso_update"] = "si";
                $check_estado = $db->prepare("SELECT PKTarea,color FROM tareas LEFT JOIN estado_tarea on PKTarea = FKTarea LEFT JOIN colores_columna ON PKColorColumna = FKColorColumna where PKTarea = ?"); //Trae tarea y color hexadecimal
                $check_estado->execute(array($data["FKTarea"]));
                $array_check_color = $check_estado->fetchAll(PDO::FETCH_ASSOC);
                $cienporciento = $check_estado->rowCount();
                $verde = 0;

                for ($j = 0; $j < count($array_check_color); $j++) {

                    if ($array_check_color[$j]["color"] == "#28c67a") {
                        $verde++; //Terminada
                    }
                }

                $porcentaje = ($verde * 100) / $cienporciento;
                $progreso = intval($porcentaje);

                $stmt4 = $db->prepare("UPDATE progreso_tarea SET Progreso=? WHERE FKTarea=?");
                $stmt4->execute(array($progreso, $data["FKTarea"]));

                $progreso_tarea = array(
                    "PKTarea" => $data["FKTarea"],
                    "progreso" => $progreso,
                );

                array_push($response, $progreso_tarea);
            }

            return $response;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function setHipervinculo($valor1, $valor2, $id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $actualiza = sprintf("UPDATE hipervinculo_tarea SET Direccion = ?, Texto = ?  WHERE PKHipervinculo = ?");
            $stmt = $db->prepare($actualiza);
            $stmt->execute(array($valor1, $valor2, $id));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function setPhoneNumber($number, $id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $actualiza = sprintf("UPDATE telefono_tarea SET Telefono = ? WHERE PKTelefono = ?");
            $stmt = $db->prepare($actualiza);
            $stmt->execute(array($number, $id));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
} //fin admin_data

class add_data
{

    public function addColumn($id, $tipo, $tabla)
    {
        $con = new conectar();
        $db = $con->getDb();
        $nombre_nuevo = "";
        $identificador_tareas = [];
        $respuesta = [];

        $permiso = $con->getPermisos($id);

        if ($permiso == 0) {
            return -1;
        }
        try {
            //Comprobando que existe al menos una etapa para agregar la columna:
            $stmt = $db->prepare("SELECT * FROM etapas WHERE FKProyecto = ?");
            $stmt->execute(array($id));
            $countGroups = $stmt->rowCount();
            if ($countGroups == 0) {
                return "noGroups";
            } else {
                if ($tipo == 1) { //Columna tipo responsables
                    $nombre = "Responsable";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    if (count($resp[0]) != 0) { //Si hay tareas en el proyecto:
                        //Por cada tarea se le asignará null como responsable
                        for ($i = 0; $i < count($resp[0]); $i++) {
                            $stmt4 = $db->prepare("INSERT INTO responsables_tarea(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
                            $stmt4->execute(array($resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                            $id_tarea = $db->lastInsertId();
                            array_push($identificador_tareas, $id_tarea);
                        }
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "id" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "Texto" => null,
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 2) { //Columna tipo estado

                    $nombre = "Estado";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    $verificar = "no"; //Variable para comprobar si hay columna tipo 9 en el proyecto y mandarle el dato a JS, por default no hay
                    $verificacion_tarea = ""; //contenido de la tabla de verificacion_tarea para actualizar data en JS

                    $progreso1 = "no"; //Variable para comprobar si hay columna tipo 10 en el proyecto y mandarle el daro a JS, por dafault no hay
                    $total_progresos = []; //contenido de la tabla de progreso_tarea para actualizar dara en JS

                    $colores = array(
                        [
                            "nombre" => "Hecho",
                            "color" => "#28c67a",
                            "bandera" => 1,
                        ],
                        [
                            "nombre" => "Pendiente",
                            "color" => "#ede966",
                            "bandera" => 0,
                        ],
                        [
                            "nombre" => "Atrasado",
                            "color" => "#e53341",
                            "bandera" => 0,
                        ],
                        [
                            "nombre" => " ",
                            "color" => "#b7b7b7",
                            "bandera" => 1,
                        ],
                    );
                    $id_color = "";

                    //Comprobar si existe una columna de tipo 9 (verificar) y actualizar a 0
                    $comprobarV = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo=9 AND FKProyecto = ?");
                    $comprobarV->execute(array($id));
                    $cuentaV = $comprobarV->rowCount();
                    if ($cuentaV > 0) { //Si existe una columna de tipo 9:

                        //Si existe columna de tipo verificar, entonces me interesa saber el estado en el que se encuentra la tarea si es la primera columna tipo estado que se va a agregar:
                        $columnas_tipo_estado = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = 2 AND FKProyecto = ?");
                        $columnas_tipo_estado->execute(array($id));
                        $cuenta_columnas_tipo_estado = $columnas_tipo_estado->rowCount();

                        if ($cuenta_columnas_tipo_estado == 1) { //Sólo hay una columna de tipo estado (la primera)
                            //Asignar según el estado en el que se encuentra la tarea al crear la columna
                            //resp[0][i].Terminada
                            for ($i = 0; $i < count($colores); $i++) {
                                $stmt4 = $db->prepare("INSERT INTO colores_columna(nombre,color,FKColumnaProyecto,FKProyecto,bandera) VALUES(?,?,?,?,?)");
                                $stmt4->execute(array($colores[$i]["nombre"], $colores[$i]["color"], $resp['id_columna'], $id, $colores[$i]["bandera"]));
                            }
                            $id_color = $db->lastInsertId(); //Id del color gris

                            //Obtener el id del color verde
                            $color_verde = $db->prepare("SELECT PKColorColumna FROM colores_columna WHERE FKColumnaProyecto = ? AND color='#28c67a'");
                            $color_verde->execute(array($resp['id_columna']));
                            $id_color_verde = $color_verde->fetch(PDO::FETCH_ASSOC);
                            for ($i = 0; $i < count($resp[0]); $i++) {
                                if ($resp[0][$i]["Estado"] == 1) {
                                    $stmtColor = $db->prepare("INSERT INTO estado_tarea(FKColorColumna,FKTarea) VALUES(?,?)");
                                    $stmtColor->execute(array($id_color_verde["PKColorColumna"], $resp[0][$i]['PKTarea']));
                                    $id_tarea = $db->lastInsertId();
                                    array_push($identificador_tareas, $id_tarea, "#28c67a");
                                } else {
                                    $stmtColor = $db->prepare("INSERT INTO estado_tarea(FKColorColumna,FKTarea) VALUES(?,?)");
                                    $stmtColor->execute(array($id_color, $resp[0][$i]['PKTarea']));
                                    $id_tarea = $db->lastInsertId();
                                    array_push($identificador_tareas, $id_tarea, "#b7b7b7");
                                }
                            }

                            $verificar = "si_primera"; //La variable cambia a sí_primera, indicando que hay una columna de tipo 9 en el proyecto y que se agregó una de estado por lo que se respeta la última configuración de tareas.Terminada

                        } else { //No es la primer columna de tipo estado y hay columna verificar. Setear la tarea.Terminada en 0 y asignar default (gris) a las tareas

                            //Por cada tarea se le asignará un estado default
                            for ($i = 0; $i < count($colores); $i++) {
                                $stmt4 = $db->prepare("INSERT INTO colores_columna(nombre,color,FKColumnaProyecto,FKProyecto,bandera) VALUES(?,?,?,?,?)");
                                $stmt4->execute(array($colores[$i]["nombre"], $colores[$i]["color"], $resp['id_columna'], $id, $colores[$i]["bandera"]));
                            }
                            $id_color = $db->lastInsertId();

                            for ($i = 0; $i < count($resp[0]); $i++) { //Se insertan los datos en estado tarea
                                $stmt4 = $db->prepare("INSERT INTO estado_tarea(FKColorColumna,FKTarea) VALUES(?,?)");
                                $stmt4->execute(array($id_color, $resp[0][$i]['PKTarea']));
                                $id_tarea = $db->lastInsertId();
                                array_push($identificador_tareas, $id_tarea);
                            }

                            //A cada tarea se le actualiza la columna Terminada a 0
                            for ($i = 0; $i < count($resp[0]); $i++) {
                                $updateTask = $db->prepare('UPDATE tareas SET Estado=0 WHERE PKTarea=?');
                                $updateTask->execute(array($resp[0][$i]['PKTarea']));
                            }

                            $verificar = "si"; //La variable cambia a sí, indicando que hay una columna de tipo 9 en el proyecto.

                        }

                        //Trayendo la información necesaria para configurar la columna en el JS
                        $datosCol = $comprobarV->fetch(PDO::FETCH_ASSOC);

                        $datosVer = $db->prepare('SELECT PKVerificacion, FKTarea FROM verificacion_tarea WHERE FKColumnaProyecto = ?');
                        $datosVer->execute(array($datosCol["PKColumnaProyecto"]));
                        $verificacion_tarea = $datosVer->fetchAll(PDO::FETCH_OBJ);
                    } else { //No existe columna de tipo 9:

                        //Por cada tarea se le asignará un estado default
                        for ($i = 0; $i < count($colores); $i++) {
                            $stmt4 = $db->prepare("INSERT INTO colores_columna(nombre,color,FKColumnaProyecto,FKProyecto,bandera) VALUES(?,?,?,?,?)");
                            $stmt4->execute(array($colores[$i]["nombre"], $colores[$i]["color"], $resp['id_columna'], $id, $colores[$i]["bandera"]));
                        }
                        $id_color = $db->lastInsertId();

                        for ($i = 0; $i < count($resp[0]); $i++) { //Se insertan los datos en estado tarea
                            $stmt4 = $db->prepare("INSERT INTO estado_tarea(FKColorColumna,FKTarea) VALUES(?,?)");
                            $stmt4->execute(array($id_color, $resp[0][$i]['PKTarea']));
                            $id_tarea = $db->lastInsertId();
                            array_push($identificador_tareas, $id_tarea);
                        }

                        //A cada tarea se le actualiza la columna Terminada a 0
                        for ($i = 0; $i < count($resp[0]); $i++) {
                            $updateTask = $db->prepare('UPDATE tareas SET Estado=0 WHERE PKTarea=?');
                            $updateTask->execute(array($resp[0][$i]['PKTarea']));
                        }
                    }

                    //Comprobar si existe una columna de tipo 10 (progreso)
                    $comprobarP = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo=10 AND FKProyecto = ?");
                    $comprobarP->execute(array($id));
                    $cuentaP = $comprobarP->rowCount();

                    if ($cuentaP > 0) { //Si existe una columna de tipo 10
                        //Hacer el cálculo según el número de columnas y el color verde
                        $progreso1 = "si";
                        for ($i = 0; $i < count($resp[0]); $i++) { //Por cada tarea

                            $check_estado = $db->prepare("SELECT PKTarea,color FROM tareas LEFT JOIN estado_tarea on PKTarea = FKTarea LEFT JOIN colores_columna ON PKColorColumna = FKColorColumna where PKTarea = ?"); //Trae tarea y color hexadecimal
                            $check_estado->execute(array($resp[0][$i]["PKTarea"]));
                            $array_check_color = $check_estado->fetchAll(PDO::FETCH_ASSOC);
                            $cienporciento = $check_estado->rowCount();
                            $verde = 0;

                            for ($j = 0; $j < count($array_check_color); $j++) {

                                if ($array_check_color[$j]["color"] == "#28c67a") {
                                    $verde++; //Terminada
                                }
                            }

                            $porcentaje = ($verde * 100) / $cienporciento;
                            $progreso = intval($porcentaje);

                            $stmt4 = $db->prepare("UPDATE progreso_tarea SET Progreso=? WHERE FKTarea=?");
                            $stmt4->execute(array($progreso, $resp[0][$i]['PKTarea']));

                            $progreso_tarea = array(
                                "PKTarea" => $resp[0][$i]['PKTarea'],
                                "progreso" => $progreso,
                            );

                            array_push($total_progresos, $progreso_tarea);
                        }
                    } else { //No existe columna de tipo 10

                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $id_color,
                        "Texto" => " ",
                        "color" => "#b7b7b7",
                        "Verificar" => $verificar,
                        "Progreso" => $progreso1,
                        $identificador_tareas,
                        $verificacion_tarea,
                        $total_progresos,
                    ];

                    return $respuesta;
                }

                if ($tipo == 3) { //Columna tipo fecha
                    $nombre = "Fecha";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    //Por cada tarea se le asignará un estado default
                    for ($i = 0; $i < count($resp[0]); $i++) {
                        $stmt4 = $db->prepare("INSERT INTO fecha_tarea(Fecha,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                        $stmt4->execute(array(NULL, $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                        $id_tarea = $db->lastInsertId();
                        array_push($identificador_tareas, $id_tarea);
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        "Texto" => "0000-00-00",
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 4) { //Columna tipo Hipervínculo
                    $nombre = "Hipervínculo";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    //Por cada tarea se le asignará un estado default
                    for ($i = 0; $i < count($resp[0]); $i++) {
                        $stmt4 = $db->prepare("INSERT INTO hipervinculo_tarea(Direccion,Texto,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?,?)");
                        $stmt4->execute(array(" ", " ", $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                        $id_tarea = $db->lastInsertId();
                        array_push($identificador_tareas, $id_tarea);
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        "Texto" => " ",
                        "Direccion" => " ",
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 5) { //Columna tipo id del elemento
                    $nombre = "Id de elemento";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    $verificar = sprintf("SELECT * FROM columnas_proyecto WHERE FKProyecto = ? AND Tipo=5 ");
                    $stmt = $db->prepare($verificar);
                    $stmt->execute(array($id));
                    $numero = $stmt->rowCount();

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        "cuenta" => $numero,
                        $resp[0],
                    ];

                    return $respuesta;
                }

                if ($tipo == 6) { //Columna tipo Menú despegable
                    $nombre = "Menú desplegable";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    if (count($resp[0]) != 0) { //Si hay tareas en el proyecto:
                        //Por cada tarea se le asignará null como responsable
                        for ($i = 0; $i < count($resp[0]); $i++) {
                            $stmt4 = $db->prepare("INSERT INTO menu_columna(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
                            $stmt4->execute(array($resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                            $id_tarea = $db->lastInsertId();
                            array_push($identificador_tareas, $id_tarea);
                        }
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 7) { //Telefono
                    $nombre = "Teléfono";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    //Por cada tarea se le asignará un estado default
                    for ($i = 0; $i < count($resp[0]); $i++) {
                        $stmt4 = $db->prepare("INSERT INTO telefono_tarea(Telefono,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                        $stmt4->execute(array(" ", $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                        $id_tarea = $db->lastInsertId();
                        array_push($identificador_tareas, $id_tarea);
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        "Telefono" => " ",
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 8) { //Números
                    $nombre = "Números";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    //Por cada tarea se le asignará un estado default
                    for ($i = 0; $i < count($resp[0]); $i++) {
                        $stmt4 = $db->prepare("INSERT INTO numeros_tabla(Numero,Simbolo,Lugar,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?,?,?)");
                        $stmt4->execute(array(0, "$", 0, $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                        $id_tarea = $db->lastInsertId();
                        array_push($identificador_tareas, $id_tarea);
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        "Simbolo" => "$",
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 9) { //Verificar

                    //Comprobar que no exista una columna de verificación:
                    $comprueba = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
                    $stmt = $db->prepare($comprueba);
                    $stmt->execute(array($tipo, $id));
                    $num = $stmt->rowCount();

                    if ($num == 0) { //No hay columnas de verificación agregadas
                        $nombre = "Verificar Estados";
                        $comun = new add_data();
                        $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                        $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                        //Por cada tarea se le asignará un estado default
                        for ($i = 0; $i < count($resp[0]); $i++) {
                            $stmt4 = $db->prepare("INSERT INTO verificacion_tarea(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
                            $stmt4->execute(array($resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                            $id_tarea = $db->lastInsertId();
                            array_push($identificador_tareas, $id_tarea);
                        }

                        $respuesta = [
                            "Nombre" => $nombre_nuevo,
                            "Orden" => $resp['Orden'],
                            "PKColumnaProyecto" => $resp['id_columna'],
                            "Tipo" => $tipo,
                            "id" => $resp['id_columna'],
                            $identificador_tareas,
                            $resp[0],
                        ];

                        return $respuesta;
                    } else {
                        return "verifica1";
                    }
                }

                if ($tipo == 10) { //Progreso
                    # Comprobar que exista columna progreso:
                    $comprueba = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
                    $stmt = $db->prepare($comprueba);
                    $stmt->execute(array($tipo, $id));
                    $num = $stmt->rowCount();
                    $total_progresos = [];

                    if ($num == 0) { //No existen columnas tipo progreso
                        $nombre = "Progreso Estados";
                        $comun = new add_data();
                        $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                        $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                        //Verificar que existan columnas de tipo estado.
                        $tipo_estado = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = 2 AND FKProyecto = ?");
                        $tipo_estado->execute(array($id));
                        $num_tipo_estado = $tipo_estado->rowCount();

                        if ($num_tipo_estado == 0) { //No hay columnas tipo estado, toma el dato Terminado de la tabla tareas:

                            for ($i = 0; $i < count($resp[0]); $i++) {

                                if ($resp[0][$i]["Estado"] == 1) {
                                    $progreso = 100;
                                } else {
                                    $progreso = 0;
                                }

                                $stmt4 = $db->prepare("INSERT INTO progreso_tarea(Progreso,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                                $stmt4->execute(array($progreso, $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                                $id_tarea = $db->lastInsertId();
                                array_push($identificador_tareas, $id_tarea);

                                $progreso_tarea = array(
                                    $id_tarea => $progreso,
                                );

                                array_push($total_progresos, $progreso_tarea);
                            }
                        } else { //Hay columnas de tipo estado, cuantas son y cuales tienen verde:

                            for ($i = 0; $i < count($resp[0]); $i++) {

                                $check_estado = $db->prepare("SELECT PKTarea,color FROM tareas LEFT JOIN estado_tarea on PKTarea = FKTarea LEFT JOIN colores_columna ON PKColorColumna = FKColorColumna where PKTarea = ?");
                                $check_estado->execute(array($resp[0][$i]["PKTarea"]));
                                $array_check_color = $check_estado->fetchAll(PDO::FETCH_ASSOC);
                                $cienporciento = $check_estado->rowCount();
                                $verde = 0;

                                for ($j = 0; $j < count($array_check_color); $j++) {

                                    if ($array_check_color[$j]["color"] == "#28c67a") {
                                        $verde++; //Terminada
                                    }
                                }

                                $porcentage = ($verde * 100) / $cienporciento;
                                $progreso = intval($porcentage);

                                $stmt4 = $db->prepare("INSERT INTO progreso_tarea(Progreso,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                                $stmt4->execute(array($progreso, $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                                $id_tarea = $db->lastInsertId();
                                array_push($identificador_tareas, $id_tarea);

                                $progreso_tarea = array(
                                    $id_tarea => $progreso,
                                );

                                array_push($total_progresos, $progreso_tarea);
                            }
                        }

                        $respuesta = [
                            "Nombre" => $nombre_nuevo,
                            "Orden" => $resp['Orden'],
                            "PKColumnaProyecto" => $resp['id_columna'],
                            "Tipo" => $tipo,
                            "id" => $resp['id_columna'],
                            $identificador_tareas,
                            $total_progresos,
                            $resp[0],
                        ];

                        return $respuesta;
                    } else {
                        return "progreso1";
                    }
                }

                if ($tipo == 11) {
                    $nombre = "Rango de fechas";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    //Por cada tarea se le asignará un estado default
                    for ($i = 0; $i < count($resp[0]); $i++) {
                        $stmt4 = $db->prepare("INSERT INTO rango_fecha(Rango,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                        $stmt4->execute(array("", $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                        $id_tarea = $db->lastInsertId();
                        array_push($identificador_tareas, $id_tarea);
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 12) { //Texto
                    $nombre = "Texto";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    //Por cada tarea se le asignará un estado default
                    for ($i = 0; $i < count($resp[0]); $i++) {
                        $stmt4 = $db->prepare("INSERT INTO texto_tarea(Texto,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                        $stmt4->execute(array("", $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                        $id_tarea = $db->lastInsertId();
                        array_push($identificador_tareas, $id_tarea);
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 13) { //Número simple
                    $nombre = "Números";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    //Por cada tarea se le asignará un estado default
                    for ($i = 0; $i < count($resp[0]); $i++) {
                        $stmt4 = $db->prepare("INSERT INTO numeros_simple(Numero,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                        $stmt4->execute(array(0, $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                        $id_tarea = $db->lastInsertId();
                        array_push($identificador_tareas, $id_tarea);
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 14) { //Texto
                    $nombre = "Texto corto";
                    $comun = new add_data();
                    $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                    $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                    //Por cada tarea se le asignará un estado default
                    for ($i = 0; $i < count($resp[0]); $i++) {
                        $stmt4 = $db->prepare("INSERT INTO texto_corto(Texto,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                        $stmt4->execute(array("", $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                        $id_tarea = $db->lastInsertId();
                        array_push($identificador_tareas, $id_tarea);
                    }

                    $respuesta = [
                        "Nombre" => $nombre_nuevo,
                        "Orden" => $resp['Orden'],
                        "PKColumnaProyecto" => $resp['id_columna'],
                        "Tipo" => $tipo,
                        "id" => $resp['id_columna'],
                        $identificador_tareas,
                    ];

                    return $respuesta;
                }

                if ($tipo == 15) { //Verificar subtareas

                    //Comprobar que no exista una columna de verificación:
                    $comprueba = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
                    $stmt = $db->prepare($comprueba);
                    $stmt->execute(array($tipo, $id));
                    $num = $stmt->rowCount();
                    if ($num == 0) {
                        $nombre = "Verificar Subtareas";
                        $comun = new add_data();
                        $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                        $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);

                        //Por cada tarea se le asignará una línea de verificar
                        for ($i = 0; $i < count($resp[0]); $i++) {
                            $stmt4 = $db->prepare("INSERT INTO verificar_subtarea(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
                            $stmt4->execute(array($resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                            $id_tarea = $db->lastInsertId();
                            array_push($identificador_tareas, $id_tarea);
                        }

                        $respuesta = [
                            "Nombre" => $nombre_nuevo,
                            "Orden" => $resp['Orden'],
                            "PKColumnaProyecto" => $resp['id_columna'],
                            "Tipo" => $tipo,
                            "id" => $resp['id_columna'],
                            $identificador_tareas,
                            $resp[0],
                        ];

                        return $respuesta;
                    } else {
                        return "verificar1";
                    }
                }

                if ($tipo == 16) { //Progreso subtareas
                    # Comprobar que exista columna progreso:
                    $comprueba = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
                    $stmt = $db->prepare($comprueba);
                    $stmt->execute(array($tipo, $id));
                    $num = $stmt->rowCount();
                    $total_progresos = [];

                    if ($num == 0) { //No existen columnas tipo progreso subtareas
                        $nombre = "Progreso subtareas";
                        $comun = new add_data();
                        $nombre_nuevo = $comun->getAName($tipo, $nombre, $id);
                        $resp = $comun->columnComun($nombre_nuevo, $id, $tipo, $tabla);
                        //Verificar subtareas
                        for ($i = 0; $i < count($resp[0]); $i++) {
                            $subtarea1 = $db->prepare("SELECT * FROM subtareas WHERE FKTarea=?");
                            $subtarea1->execute(array($resp[0][$i]['PKTarea']));
                            $num_subtarea1 = $subtarea1->rowCount();

                            //Si existe subtareas para esa tarea, calcular progreso
                            if ($num_subtarea1 !== 0) {
                                $data_subtarea = $subtarea1->fetchAll(PDO::FETCH_ASSOC);
                                $terminada = 0;
                                for ($j = 0; $j < $num_subtarea1; $j++) {
                                    if ($data_subtarea[$j]["Terminada"] == 1) {
                                        $terminada++;
                                    }
                                }

                                $porcentage = ($terminada * 100) / $num_subtarea1;
                                $progreso = intval($porcentage);

                                $stmt4 = $db->prepare("INSERT INTO progreso_subtarea(Progreso,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                                $stmt4->execute(array($progreso, $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                                $id_tarea = $db->lastInsertId();
                                array_push($identificador_tareas, $id_tarea);

                                $progreso_tarea = array(
                                    $id_tarea => $progreso,
                                );

                                array_push($total_progresos, $progreso_tarea);
                            } else {
                                $progreso = 0;

                                if ($resp[0][$i]["Terminada"] == 1) {
                                    $progreso = 100;
                                }
                                //tomar dato "terminada" de la tarea:
                                $stmt4 = $db->prepare("INSERT INTO progreso_subtarea(Progreso,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                                $stmt4->execute(array($progreso, $resp[0][$i]['PKTarea'], $resp['id_columna'], $id));
                                $id_tarea = $db->lastInsertId();
                                array_push($identificador_tareas, $id_tarea);

                                $progreso_tarea = array(
                                    $id_tarea => $progreso,
                                );

                                array_push($total_progresos, $progreso_tarea);
                            }
                        }

                        $respuesta = [
                            "Nombre" => $nombre_nuevo,
                            "Orden" => $resp['Orden'],
                            "PKColumnaProyecto" => $resp['id_columna'],
                            "Tipo" => $tipo,
                            "id" => $resp['id_columna'],
                            $identificador_tareas,
                            $total_progresos,
                            $resp[0],
                        ];

                        return $respuesta;
                    } else {
                        return "progreso1";
                    }
                }
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getAName($tipo, $nombre, $id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf("SELECT * FROM columnas_proyecto WHERE tipo=? AND nombre=? AND FKProyecto=?");
            $stmt = $db->prepare($query);
            $stmt->execute(array($tipo, $nombre, $id));
            $cuenta = $stmt->rowCount();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            //Si el nombre ya existe va probando con un consecutivo hasta encontrar uno inexistente del 1 al 100
            if ($cuenta > 0) {
                for ($a = 1; $a < 100; $a++) {
                    $pn = $data["nombre"] . " " . $a;
                    $stmt1 = $db->prepare("SELECT * FROM columnas_proyecto WHERE tipo=? AND nombre=? AND FKProyecto=?");
                    $stmt1->execute(array($tipo, $pn, $id));
                    $cuenta1 = $stmt1->rowCount();
                    if ($cuenta1 == 0) {
                        $nombre_nuevo = $data["nombre"] . " " . $a;
                        $a = 100;
                    }
                }
            } else {
                $nombre_nuevo = $nombre;
            }

            return $nombre_nuevo;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function columnComun($nombre_nuevo, $id, $tipo, $tabla)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            //Orden que le corresponderá a la columna
            $query1 = sprintf("SELECT Orden FROM columnas_proyecto WHERE FKProyecto=? ORDER BY Orden DESC");
            $stmt2 = $db->prepare($query1);
            $stmt2->execute(array($id));
            $count_data1 = $stmt2->rowCount();
            $data1 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            if ($count_data1 == 0) { //Si no hay columnas agregadas:
                //El orden será el uno
                $ordenY = 1;
            } else { //Si hay columnas agregadas:
                //El orden será el mayor número en la columna "orden" + 1
                $mayor = $data1[0]['Orden'];
                $ordenY = $mayor + 1;
            }
            //Insertando la nueva columna en la tabla columnas_proyecto
            $insert = sprintf("INSERT INTO columnas_proyecto(nombre, tipo, Orden, FKProyecto) VALUES(?,?,?,?)");
            $stmt3 = $db->prepare($insert);
            $stmt3->execute(array($nombre_nuevo, $tipo, $ordenY, $id));
            $id_columna = $db->lastInsertId();

            //Consultando el total de tareas que existen en el proyecto
            $tareas = sprintf("SELECT PKTarea, Terminada, Estado FROM tareas WHERE FKProyecto = ? ORDER BY Orden");
            $stmt3 = $db->prepare($tareas);
            $stmt3->execute(array($id));
            $tareasRows = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($tareasRows);
            return $resp = [
                "id_columna" => $id_columna,
                "Orden" => $ordenY,
                $tareasRows,
            ];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    } //fin de la función columnComun

    public function addTask($id_etapa, $id_proyecto)
    {
        $con = new conectar();
        $db = $con->getDb();
        $nombre_nuevo = "";
        $up1 = new acciones();
        $aritmetica = "sumar";
        $newOrderTareas = [];
        $upnow = [];
        $id_tarea = '';

        $permiso = $con->getPermisos($id_proyecto);

        if ($permiso == 0) {
            return -1;
        }

        try {
            $verificar = sprintf("SELECT * FROM tareas WHERE Tarea='Tarea 1' AND FKProyecto=?");
            $stmt = $db->prepare($verificar);
            $stmt->execute(array($id_proyecto));
            $cuenta = $stmt->rowCount();
            //Si el nombre ya existe va probando con un consecutivo hasta encontrar uno inexistente del 1 al 100
            if ($cuenta > 0) {
                for ($a = 2; $a < 100; $a++) {
                    $pn = "Tarea " . $a;
                    $stmt1 = $db->prepare("SELECT * FROM tareas WHERE Tarea=? AND FKProyecto=?");
                    $stmt1->execute(array($pn, $id_proyecto));
                    $cuenta1 = $stmt1->rowCount();
                    if ($cuenta1 == 0) {
                        $nombre_nuevo = "Tarea " . $a;
                        $a = 100;
                    }
                }
            } else {
                $nombre_nuevo = "Tarea 1";
            }

            //Verificar que existan tareas:
            $query = sprintf("SELECT PKTarea FROM tareas WHERE FKProyecto=?");
            $statment = $db->prepare($query);
            $statment->execute(array($id_proyecto));
            $count = $statment->rowCount();

            if ($count != 0) { //Si existen tareas dentro del proyecto
                //Orden de la etapa
                $ordenE = sprintf("SELECT Orden,color FROM etapas WHERE PKEtapa=? ORDER BY Orden DESC");
                $stEt = $db->prepare($ordenE);
                $stEt->execute(array($id_etapa));
                $etapasO = $stEt->fetch(PDO::FETCH_ASSOC);
                $pos = $etapasO['Orden'];
                //Orden de las tareas dentro de la etapa:
                $orden = sprintf("SELECT Orden FROM tareas WHERE FKEtapa=? ORDER BY Orden DESC");
                $stmt2 = $db->prepare($orden);
                $stmt2->execute(array($id_etapa));
                $cuenta_tareas = $stmt2->rowCount();
                $tareas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

                if ($cuenta_tareas == 0) { //Si no hay tareas agregadas en la etapa:
                    //Verificar si la etapa está en la posición 1:
                    if ($pos == 1) { //Que esté en la posición 1 del proyecto
                        //El orden de la tarea será el uno, mientras que el orden de las demás aumentara en consecutivo
                        $ordenY = 1;
                        //$upnow = $up1->sumaOResta($ordenY,$id_proyecto,$aritmetica);
                    } else { //No está en la posición 1, Verificar el total de etapas y el último número en orden de las tareas:

                        $orden2 = sprintf("SELECT Orden FROM tareas WHERE FKProyecto=? ORDER BY Orden DESC");
                        $stmt21 = $db->prepare($orden2);
                        $stmt21->execute(array($id_proyecto));
                        $tareas2 = $stmt21->fetchAll(PDO::FETCH_ASSOC);

                        $etapas = sprintf("SELECT Orden FROM etapas WHERE FKProyecto=? ORDER BY Orden DESC");
                        $stmtE = $db->prepare($etapas);
                        $stmtE->execute(array($id_proyecto));
                        $total = $stmtE->fetchAll(PDO::FETCH_ASSOC);
                        //La mayor posición de las etapas (última en orden) es igual a la posición de la etapa.
                        if ($total[0]['Orden'] == $pos) {
                            $mayor = $tareas2[0]['Orden'];
                            $ordenY = $mayor + 1;
                        } else { //Si la posición de la etapa no es la primera ni la ultima.
                            $ord = $pos - 1;
                            $tareas3 = [];
                            for ($i = $ord; $i > 0; $i--) { //ejmeplo: es la posición 3 de 4 etapas y la etapa 2 no tiene tareas
                                //Obtener el id de la etapa que en orden es la inmediata superior y tiene tareas
                                $plusFromUp = sprintf("SELECT PKEtapa FROM etapas WHERE Orden = ? AND FKProyecto=?");
                                $st = $db->prepare($plusFromUp);
                                $st->execute(array($i, $id_proyecto));
                                $etapa = $st->fetch(PDO::FETCH_ASSOC);
                                //var_dump($etapa);
                                //Obtener el número más alto en orden de esa etapa inmediata superior
                                $ordenT = sprintf("SELECT Orden FROM tareas WHERE FKEtapa=? ORDER BY Orden DESC");
                                $st2 = $db->prepare($ordenT);
                                $st2->execute(array($etapa['PKEtapa']));
                                $count_tareas3 = $st2->rowCount();
                                $tareas3 = $st2->fetchAll(PDO::FETCH_ASSOC);
                                //Si la etapa inmediata superior tiene elementos:
                                if ($count_tareas3 != 0) {
                                    $i = 0;
                                }
                            }
                            //Si ninguna etapa superior tiene elementos:
                            if ($count_tareas3 == 0) {
                                $ordenY = 1;
                            } else { //tarea de orden mayor de la etapa con elementos
                                $mayor = $tareas3[0]['Orden'];
                                $ordenY = $mayor + 1;
                            }
                        }
                    }
                } else { //Si hay tareas agregadas dentro de la etapa:
                    //El orden de la tarea será el mayor número dentro de las tareas en la etapa
                    $mayor = $tareas[0]['Orden'];
                    $ordenY = $mayor + 1;
                    //$upnow = $up1->sumaOResta($ordenY,$id_proyecto,$aritmetica);
                }

                $insertar = sprintf("INSERT INTO tareas(Tarea,Orden,FKProyecto,Terminada,FKEtapa) VALUES(?,?,?,?,?)");
                $stmt3 = $db->prepare($insertar);
                $stmt3->execute(array($nombre_nuevo, $ordenY, $id_proyecto, 0, $id_etapa));
                $id_tarea = $db->lastInsertId();

                //NOTIFICACIONES:
                $auxHoy = getdate();
                $hoy = $auxHoy['year'] . "-" . $auxHoy['mon'] . "-" . $auxHoy['mday'];
                $hora = $auxHoy['hours'] . ":" . $auxHoy['minutes'] . ":" . ($auxHoy['seconds']);
                $fechaHora = $hoy . " " . $hora;

                $notificaciones = sprintf("INSERT INTO tarea_notificaciones(FKResponsableTarea,FKUsuarioMencion,FechaCreacion,FKTarea,FKTipoNotificacion) VALUES (?,?,?,?,?)");
                $statment = $db->prepare($notificaciones);
                $statment->execute(array(null, $_SESSION["PKUsuario"], $fechaHora, $id_tarea, 1));

                $upnow = $up1->sumaOResta($ordenY, $id_proyecto, $aritmetica, $id_tarea);
            } else { //No existen tareas en el proyecto:
                $ordenY = 1;

                $insertar = sprintf("INSERT INTO tareas(Tarea,Orden,FKProyecto,Terminada,FKEtapa) VALUES(?,?,?,?,?)");
                $stmt3 = $db->prepare($insertar);
                $stmt3->execute(array($nombre_nuevo, $ordenY, $id_proyecto, 0, $id_etapa));
                $id_tarea = $db->lastInsertId();

                //NOTIFICACIONES:
                $auxHoy = getdate();
                $hoy = $auxHoy['year'] . "-" . $auxHoy['mon'] . "-" . $auxHoy['mday'];
                $hora = $auxHoy['hours'] . ":" . $auxHoy['minutes'] . ":" . ($auxHoy['seconds']);
                $fechaHora = $hoy . " " . $hora;

                $notificaciones = sprintf("INSERT INTO tarea_notificaciones(FKResponsableTarea,FKUsuarioMencion,FechaCreacion,FKTarea,FKTipoNotificacion) VALUES (?,?,?,?,?)");
                $statment = $db->prepare($notificaciones);
                $statment->execute(array(null, $_SESSION["PKUsuario"], $fechaHora, $id_tarea, 1));
            }

            //Seguimiento de las actividades de la tarea:
            date_default_timezone_set('America/Mexico_City');
            $fecha = date('Y-m-d H:i:s');
            $insertar_chat = sprintf("INSERT INTO chat_actividad(Tipo,Fecha,FKTarea,FKUsuario) VALUES(?,?,?,?)");
            $stmt3 = $db->prepare($insertar_chat);
            $stmt3->execute(array(0, $fecha, $id_tarea, $_SESSION['PKUsuario']));

            //Columnas:
            $columnas = sprintf("SELECT PKColumnaProyecto,tipo,Orden FROM columnas_proyecto WHERE FKProyecto=? ORDER BY tipo");
            $stC = $db->prepare($columnas);
            $stC->execute(array($id_proyecto));
            $count_all_columns = $stC->rowCount();
            $all_columns = $stC->fetchAll(PDO::FETCH_ASSOC);

            if ($count_all_columns == 0) { //No existen columnas en el proyecto
                return $resp = [
                    "id_etapa" => $id_etapa,
                    "id_tarea" => $id_tarea,
                    "orden" => $ordenY,
                    "nombre" => $nombre_nuevo,
                    "permiso" => $permiso,
                    $upnow, //Lista de las tareas con el orden actualizado
                ];
            } else { //Si existen columnas dentro del proyecto
                $elementos = new add_data();
                $resp = $elementos->addElementsFromTask($all_columns, $id_tarea, $id_proyecto);
                /*
                $id_etapa (id de la etapa donde se agrego la tarea)
                $id_tarea (id de la tarea que se creó)
                $nombre_nuevo (Nombre que se le asignó a la tarea)
                $all_columns (Array del que se obtendrá el orden de las columnas)
                (Id de cada elemento creado para la tarea)
                 */
                if ($resp == "ok") {
                    $dataElements = [];
                    //2:estado_tarea.
                    $tareas_estados = [];
                    //1:responsables_tarea.
                    $responsables = [];
                    //3:fecha_tarea
                    $fechas = [];
                    //4:hipervínculo
                    $hipervinculo = [];
                    //5:id del elemento
                    $idElemento = [];
                    //6:Menú desplegable
                    $menuDesplegable = [];
                    //7:Teléfono
                    $telefono = [];
                    //8:Números
                    $numeros = [];
                    //9:Verificar
                    $verificar = [];
                    //10:Progeso
                    $progreso = [];
                    //11:Rango
                    $rango = [];
                    //12:Texto
                    $texto_tarea = [];
                    //13:Numeros simple
                    $numeros_simple = [];
                    //14:texto_corto
                    $short_text = [];
                    //15:verificar subtarea
                    $verificar_subtarea = [];
                    //16:progreso subtarea
                    $progress_subtask = [];
                    for ($i = 0; $i < count($all_columns); $i++) {
                        if ($all_columns[$i]['tipo'] == 1) {
                            $query = sprintf("SELECT PKResponsable as id, FKUsuario as Texto,FKTarea,PKColumnaProyecto, Tipo, columnas_proyecto.Orden as cOrden, tareas.FKEtapa, tareas.Tarea, tareas.Orden as tOrden FROM responsables_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea=PKTarea WHERE FKTarea=?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            //var_dump($responsables);
                        }
                        if ($all_columns[$i]['tipo'] == 2) {
                            $query = sprintf("SELECT PKEstadoTarea as id, FKColorColumna, colores_columna.color, colores_columna.nombre as Texto, PKColorColumna, FKTarea, columnas_proyecto.Orden as cOrden, Tipo,tareas.FKEtapa,tareas.Tarea, tareas.Orden as tOrden, PKColumnaProyecto FROM estado_tarea LEFT JOIN colores_columna ON FKColorColumna = PKColorColumna LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea=?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $tareas_estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 3) {
                            $query = sprintf("SELECT PKFecha as id, Fecha as Texto,FKTarea, PKColumnaProyecto,Tipo,columnas_proyecto.Orden as cOrden, tareas.FKEtapa,tareas.Tarea,tareas.Orden as tOrden FROM fecha_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea=PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 4) { //hipervínculo
                            $query = sprintf("SELECT PKHipervinculo as id,Texto,Direccion,FKTarea, PKColumnaProyecto,Tipo,columnas_proyecto.Orden as cOrden, tareas.FKEtapa,tareas.Tarea,tareas.Orden as tOrden FROM hipervinculo_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea=PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $hipervinculo = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 5) { //id del elemento
                            $query = sprintf("SELECT PKColumnaProyecto,Tipo,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,PKTarea as id,tareas.Tarea,tareas.Orden as tOrden FROM columnas_proyecto LEFT JOIN tareas ON tareas.PKTarea = ? WHERE tipo = ? AND columnas_proyecto.FKProyecto=?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea, 5, $id_proyecto));
                            $idElemento = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 6) { //menú_desplegable
                            $query = sprintf("SELECT PKMenu as id,menu_columna.FKTarea, PKColumnaProyecto,Tipo,columnas_proyecto.Orden as cOrden, tareas.FKEtapa,tareas.Tarea,tareas.Orden as tOrden FROM menu_columna LEFT JOIN columnas_proyecto ON FKColumnaProyecto=PKColumnaProyecto LEFT JOIN tareas ON FKTarea=PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $menuDesplegable = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 7) { //Teléfono
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKTelefono as id,Telefono,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden FROM telefono_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $telefono = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 8) { //Números
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKNumeros as id,Numero,Simbolo,Lugar,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden FROM numeros_tabla LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $numeros = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 9) { //Verificar
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKVerificacion as id,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden, tareas.Estado FROM verificacion_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $verificar = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 10) { //Progreso
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKProgreso as id,Progreso,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden, tareas.Estado FROM progreso_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $progreso = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 11) { //Rango
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKRangoFecha as id,Rango,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden FROM rango_fecha LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $rango = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 12) { //Texto
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKTexto as id,Texto,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden FROM texto_tarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $texto_tarea = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 13) { //Numeros simple
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKNumerosSimple as id,Numero,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden FROM numeros_simple LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $numeros_simple = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 14) { //Texto
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKTexto as id,Texto,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden FROM texto_corto LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $short_text = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 15) { //verificar subtarea
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKVerificaSub as id,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden, tareas.Terminada FROM verificar_subtarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $verificar_subtarea = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                        if ($all_columns[$i]['tipo'] == 16) { //progreso subtarea
                            $query = sprintf("SELECT FKTarea,PKColumnaProyecto,PKProgresoSubtarea as id,Progreso,columnas_proyecto.Orden as cOrden,tareas.FKEtapa,tareas.Tarea, Tipo, tareas.Orden as tOrden, tareas.Terminada FROM progreso_subtarea LEFT JOIN columnas_proyecto ON FKColumnaProyecto = PKColumnaProyecto LEFT JOIN tareas ON FKTarea = PKTarea WHERE FKTarea = ?");
                            $stmt = $db->prepare($query);
                            $stmt->execute(array($id_tarea));
                            $progress_subtask = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                    }

                    $resultado = array_merge($responsables, $tareas_estados, $fechas, $hipervinculo, $idElemento, $menuDesplegable, $telefono, $numeros, $verificar, $progreso, $rango, $texto_tarea, $numeros_simple, $short_text, $verificar_subtarea, $progress_subtask);

                    $ordenamiento = array_column($resultado, 'cOrden');
                    array_multisort($ordenamiento, SORT_ASC, $resultado);
                    //var_dump($resultado);
                    array_push($dataElements, $resultado);

                    return $array = [
                        $dataElements,
                        $upnow, //Lista de las tareas con el orden actualizado
                    ];
                } else {
                    return $resp;
                }
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    } //Termina función addTask

    public function addElementsFromTask($array, $id_tarea, $id_proyecto)
    {
        $con = new conectar();
        $db = $con->getDb();

        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]['tipo'] == 1) { //Responsable
                $stmt = $db->prepare("INSERT INTO responsables_tarea(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
                $stmt->execute(array($id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }
            if ($array[$i]['tipo'] == 2) { //Estado

                $stmt = $db->prepare("SELECT PKColorColumna FROM colores_columna WHERE FKColumnaProyecto = ? AND color = ?");
                $stmt->execute(array($array[$i]['PKColumnaProyecto'], '#b7b7b7'));
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $id_color = $data['PKColorColumna'];

                $stmt4 = $db->prepare("INSERT INTO estado_tarea(FKColorColumna,FKTarea) VALUES(?,?)");
                $stmt4->execute(array($id_color, $id_tarea));

                /*
            $stmt = $db->prepare("INSERT INTO estado_tarea(Estado,Color,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?,?)");
            $stmt->execute(array("","#f5f6f8",$id_tarea,$array[$i]['PKColumnaProyecto'],$id_proyecto));
             */
            }
            if ($array[$i]['tipo'] == 3) { //Fecha
                $stmt = $db->prepare("INSERT INTO fecha_tarea(Fecha,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                $stmt->execute(array("1970-01-01", $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }
            if ($array[$i]['tipo'] == 4) { //Hipervínculo
                $stmt = $db->prepare("INSERT INTO hipervinculo_tarea(Direccion,Texto,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?,?)");
                $stmt->execute(array(" ", " ", $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }
            //tipo 5 no necesita insert
            if ($array[$i]['tipo'] == 6) { //Menu despegable
                $stmt = $db->prepare("INSERT INTO menu_columna(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
                $stmt->execute(array($id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }
            if ($array[$i]['tipo'] == 7) { //Teléfono
                $stmt = $db->prepare("INSERT INTO telefono_tarea(Telefono,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                $stmt->execute(array(" ", $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }
            if ($array[$i]['tipo'] == 8) { //Números
                $symbol = "$";
                $place = 0;

                //Comprobando si hay datos con configuración símbolo y lugar (izquierda, derecha)
                $stmt = $db->prepare("SELECT Simbolo,Lugar FROM numeros_tabla WHERE FKColumnaProyecto = ?");
                $stmt->execute(array($array[$i]['PKColumnaProyecto']));
                $cuenta_datoN = $stmt->rowCount();
                $datoN = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($cuenta_datoN != 0) { //Que sí hay datos
                    $symbol = $datoN['Simbolo'];
                    $place = $datoN['Lugar'];
                }

                $stmt = $db->prepare("INSERT INTO numeros_tabla(Numero,Simbolo,Lugar,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?,?,?)");
                $stmt->execute(array(0, $symbol, $place, $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }

            if ($array[$i]['tipo'] == 9) { //Verificar
                $stmt = $db->prepare("INSERT INTO verificacion_tarea(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
                $stmt->execute(array($id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }

            if ($array[$i]['tipo'] == 10) { //Progreso
                $insert_progreso = $db->prepare("INSERT INTO progreso_tarea(Progreso,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                $insert_progreso->execute(array(0, $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }

            if ($array[$i]['tipo'] == 11) { //Rango
                $insert_rango = $db->prepare("INSERT INTO rango_fecha(Rango,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                $insert_rango->execute(array("", $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }

            if ($array[$i]['tipo'] == 12) { //Texto
                $insert_texto = $db->prepare("INSERT INTO texto_tarea(Texto,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                $insert_texto->execute(array("", $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }

            if ($array[$i]['tipo'] == 13) { //Numero simple
                $insert_simple = $db->prepare("INSERT INTO numeros_simple(Numero,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                $insert_simple->execute(array(0, $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }

            if ($array[$i]['tipo'] == 14) { //Texto corto
                $insert_texto = $db->prepare("INSERT INTO texto_corto(Texto,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                $insert_texto->execute(array("", $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }

            if ($array[$i]['tipo'] == 15) { //Verificar subtarea
                $stmt = $db->prepare("INSERT INTO verificar_subtarea(FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?)");
                $stmt->execute(array($id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }

            if ($array[$i]['tipo'] == 16) { //Progreso subtarea
                $insert_progreso = $db->prepare("INSERT INTO progreso_subtarea(Progreso,FKTarea,FKColumnaProyecto,FKProyecto) VALUES(?,?,?,?)");
                $insert_progreso->execute(array(0, $id_tarea, $array[$i]['PKColumnaProyecto'], $id_proyecto));
            }
        }

        return "ok";

        $stmt = null;
        $db = null;
    }

    public function addGroup($id_proyecto)
    {
        $con = new conectar();
        $db = $con->getDb();
        $orden = 1;
        $columns = [];
        $up = new acciones();
        $allGroups = [];

        $permiso = $con->getPermisos($id_proyecto);

        if ($permiso == 0) {
            return -1;
        }

        $verificar = sprintf("SELECT * FROM etapas WHERE Etapa='Etapa 1' AND FKProyecto=?");
        $stmt = $db->prepare($verificar);
        $stmt->execute(array($id_proyecto));
        $cuenta = $stmt->rowCount();
        //Si el nombre ya existe va probando con un consecutivo hasta encontrar uno inexistente del 1 al 100
        if ($cuenta > 0) {
            for ($a = 2; $a < 100; $a++) {
                $pn = "Etapa " . $a;
                $stmt1 = $db->prepare("SELECT * FROM etapas WHERE Etapa=? AND FKProyecto=?");
                $stmt1->execute(array($pn, $id_proyecto));
                $cuenta1 = $stmt1->rowCount();
                if ($cuenta1 == 0) {
                    $nombre_nuevo = "Etapa " . $a;
                    $a = 100;
                }
            }
        } else {
            $nombre_nuevo = "Etapa 1";
        }

        $st = $db->prepare("SELECT * FROM etapas WHERE FKProyecto = ?");
        $st->execute(array($id_proyecto));
        $change_comprobar = $st->rowCount();
        $change = $st->fetchAll(PDO::FETCH_ASSOC);

        if ($change_comprobar != 0) { //Si hay etapas en el proyecto
            for ($i = 0; $i < count($change); $i++) {
                $update = sprintf("UPDATE etapas SET Orden=? WHERE PKEtapa = ?");
                $dec = $db->prepare($update);
                $num = $change[$i]['Orden'] + 1;
                $dec->execute(array($num, $change[$i]['PKEtapa']));
            }

            $allGroups = $up->ordenEtapas($id_proyecto);
        }

        $query = sprintf("SELECT PKColumnaProyecto, nombre, tipo, Orden FROM columnas_proyecto WHERE FKProyecto = ? ORDER BY Orden ASC");
        $stmt = $db->prepare($query);
        $stmt->execute(array($id_proyecto));
        $proof = $stmt->rowCount();
        if ($proof != 0) { //Si existen columnas en el proyecto
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $newGroup = sprintf("INSERT INTO etapas(Etapa,Orden,FKProyecto,color) VALUES(?,?,?,?)");
        $stmt2 = $db->prepare($newGroup);
        $stmt2->execute(array($nombre_nuevo, $orden, $id_proyecto, "#1c4587"));
        $id_etapa = $db->lastInsertId();
        $total_etapas = $change_comprobar + 1;
        $etapa = [
            "PKEtapa" => $id_etapa,
            "Orden" => $orden,
            "Etapa" => $nombre_nuevo,
            "total_etapas" => $total_etapas,
        ];

        array_push($etapa, $columns, $allGroups);

        return $etapa;

        $stmt = null;
        $db = null;
    }

    public function add_label($id_proyecto, $PKColumnaProyecto, $color)
    {
        $con = new conectar();
        $db = $con->getDb();

        $insert = sprintf("INSERT INTO colores_columna(nombre, color, FKColumnaProyecto, FKProyecto, bandera) VALUES(?,?,?,?,?)");
        $stmt = $db->prepare($insert);
        $stmt->execute(array(" ", $color, $PKColumnaProyecto, $id_proyecto, 0));
        $data = $db->lastInsertId();
        return $data;

        $stmt = null;
        $db = null;
    }

    public function addETiqueta($nombre, $id, $id_col)
    { //crea una nueva etiqueta
        $con = new conectar();
        $db = $con->getDb();

        try {
            $stmt = $db->prepare("INSERT INTO etiquetas_columna(Nombre,FKColumnaProyecto, FKMenu) VALUES(?,?,?)");
            $stmt->execute(array($nombre, $id_col, $id));
            $id_etiqueta = $db->lastInsertId();

            $query = sprintf("SELECT * FROM etiquetas_columna WHERE PKEtiqueta=?");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_etiqueta));
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function addETiquetaSelected($idmenu, $array)
    { //agregar etiquetas seleccionadas y eliminar las que se quitan
        $con = new conectar();
        $db = $con->getDb();

        //var_dump($array);
        try {

            $stmt = $db->prepare("DELETE FROM etiquetas_tarea WHERE FKMenu = :idmenu");
            $stmt->bindValue(':idmenu', $idmenu);
            $stmt->execute();

            if ($array !== "0") { //*checar en el servidor
                for ($x = 0; $x < count($array); $x++) {
                    $stmt = $db->prepare("INSERT INTO etiquetas_tarea(FKEtiqueta, FKMenu, Bandera) VALUES(?,?,?)");
                    $stmt->execute(array($array[$x], $idmenu, 1));
                }
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function add_sub($id_tarea)
    {
        $timestamp = date('Y-m-d H:i:s');
        $con = new conectar();
        $db = $con->getDb();
        $action_check = 0;
        $action_progress = 0;
        $id_verificar = 0;
        $progreso = 0;

        try {

            //Obteniendo el proyecto al que pertenece la tarea:
            $queryProyecto = sprintf("SELECT FKProyecto FROM tareas WHERE PKTarea=?");
            $proyecto = $db->prepare($queryProyecto);
            $proyecto->execute(array($id_tarea));
            $id_proyecto = $proyecto->fetch(PDO::FETCH_ASSOC);

            $permiso = $con->getPermisos($id_proyecto["FKProyecto"]);
            $permisoResponsable = $con->getPermisosResponsables($id_proyecto["FKProyecto"], $id_tarea);

            if ($permiso == 0) {
                if ($permisoResponsable == 0) {
                    return -1;
                }
            }

            $verificar = sprintf("SELECT * FROM subtareas WHERE SubTarea='Subtarea 1' AND FKTarea=?");
            $stmt = $db->prepare($verificar);
            $stmt->execute(array($id_tarea));
            $cuenta = $stmt->rowCount();
            //Si el nombre ya existe va probando con un consecutivo hasta encontrar uno inexistente del 1 al 100
            if ($cuenta > 0) {
                for ($a = 2; $a < 100; $a++) {
                    $pn = "Subtarea " . $a;
                    $stmt1 = $db->prepare("SELECT * FROM subtareas WHERE SubTarea=? AND FKTarea=?");
                    $stmt1->execute(array($pn, $id_tarea));
                    $cuenta1 = $stmt1->rowCount();
                    if ($cuenta1 == 0) {
                        $nombre_nuevo = "Subtarea " . $a;
                        $a = 100;
                    }
                }
            } else {
                $nombre_nuevo = "Subtarea 1";
            }

            $insert = sprintf("INSERT INTO subtareas(SubTarea,FKTarea,FKUsuario) VALUES(?,?,?)");
            $stmt = $db->prepare($insert);
            $stmt->execute(array($nombre_nuevo, $id_tarea, $_SESSION["PKUsuario"]));
            $id_sub = $db->lastInsertId();

            /*=============================================
            =            Sección notificaciones           =
            =============================================*/

            $query = sprintf("SELECT FKUsuario, FKProyecto FROM responsables_tarea WHERE FKTarea = ? AND EXISTS (SELECT 1 FROM usuarios)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_tarea));
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($tareas as $tarea) {
                    if ($tarea['FKUsuario'] > 0) {
                        /* INSERTAMOS LA NOTIFICACION EN LA BD */
                        $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                        $stmt->execute([':tipoNot' => 3, ':detaleNot' => 4, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $id_tarea, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);
                    }
                }
            }

            /*=====  End of Sección notificaciones ======*/

            $stmt = $db->prepare("SELECT * FROM subtareas WHERE FKTarea=?");
            $stmt->execute(array($id_tarea));
            $total = $stmt->rowCount();

            //checar progreso:
            $progress = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
            $progress->execute(array(16, $id_proyecto["FKProyecto"]));
            $cuentaP = $progress->rowCount();

            //checar verificar
            $verificar = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
            $verificar->execute(array(15, $id_proyecto["FKProyecto"]));
            $cuentaV = $verificar->rowCount();

            if ($cuentaV !== 0) { //Si existe columna verificar
                $action_check = 1;
                $queryV = sprintf("SELECT PKVerificaSub FROM  verificar_subtarea WHERE FKTarea=?");
                $idverificar = $db->prepare($queryV);
                $idverificar->execute(array($id_tarea));
                $id_verificar = $idverificar->fetch(PDO::FETCH_ASSOC);
                //Saber si el porcentaje es cien para alterar el check
            }
            //calculando progreso:
            $totalSubs = $db->prepare("SELECT * FROM subtareas WHERE FKTarea=?");
            $totalSubs->execute(array($id_tarea));
            $all = $totalSubs->rowCount(); //total de subtareas
            $dataSubs = $totalSubs->fetchAll(PDO::FETCH_ASSOC); //Data de las subtareas

            $terminada = 0;

            for ($i = 0; $i < count($dataSubs); $i++) {
                if ($dataSubs[$i]["Terminada"] == 1) {
                    $terminada++;
                }
            }

            $porcentaje = ($terminada * 100) / $all;
            $progreso = intval($porcentaje);

            if ($cuentaP !== 0) { //Si existe columna progreso
                $action_progress = 1;
                //$data_columna = $progress->fetchAll(PDO::FETCH_ASSOC);
                //Trayendo las subtareas:
                //Actualizando el progreso:
                $stmt4 = $db->prepare("UPDATE progreso_subtarea SET Progreso=? WHERE FKTarea=?");
                $stmt4->execute(array($progreso, $id_tarea));
            }

            if ($progreso !== 100) {
                //actualizando la tarea como no terminada:
                $queryTask = sprintf("UPDATE tareas SET Terminada = ? WHERE PKTarea = ?");
                $tarea = $db->prepare($queryTask);
                $tarea->execute(array(0, $id_tarea));
            }

            return $data = [
                "id_subtarea" => $id_sub,
                "total" => $total,
                "nombre" => $nombre_nuevo,
                "action_progress" => $action_progress,
                "action_check" => $action_check,
                "progreso" => $progreso,
                "id_verificar" => $id_verificar,
            ];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
} //fin add_data

class edit_data
{

    public function getPermisos($idProyecto)
    {
        $con = new conectar();
        $db = $con->getDb();

        /* $stmt = $db->prepare("SELECT * FROM proyectos WHERE PKProyecto = :idProyecto");
        $stmt->bindValue(":idProyecto", $idProyecto);
        $stmt->execute();
        $rowPermiso = $stmt->fetch();

        if ($rowPermiso['FKResponsable'] == $_SESSION['PKUsuario']) {
            return 1;
        } else {
            return 0;
        } */

        $stmt = $db->prepare("SELECT Proyecto, usuarios_id from proyectos as p 
        inner join responsables_proyecto as rp on rp.proyectos_PKProyecto = p.PKProyecto where rp.proyectos_PKProyecto = :idProyecto"); //Where usuario id sea= $_SESSION["id"];
        $stmt->bindValue(":idProyecto", $idProyecto);
        $stmt->execute();
        $rowPermiso = $stmt->fetchAll();
        $idUsuario = [];
        foreach ($rowPermiso as $us) {
            /* print_r($us["usuarios_id"]); */
            array_push($idUsuario, $us["usuarios_id"]);
        }
        $UsSession = $_SESSION['PKUsuario'];

        if (in_array(strval($UsSession), $idUsuario)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function editGroup($id, $nombre)
    {
        $con = new conectar();
        $db = $con->getDb();

        $select = sprintf("SELECT FKProyecto FROM etapas WHERE PKEtapa=?");
        $stmt = $db->prepare($select);
        $stmt->execute(array($id));
        $row = $stmt->fetch();

        $permiso = $this->getPermisos($row['FKProyecto']);

        if ($permiso == 0) {
            return -1;
        }

        try {
            if ($nombre !== "") {
                $edita = sprintf("UPDATE etapas SET Etapa=? WHERE PKEtapa=?");
                $stmt = $db->prepare($edita);
                $stmt->execute(array($nombre, $id));

                return "ok";
            } else {
                $stmt = $db->prepare("SELECT Etapa FROM etapas WHERE PKEtapa=?");
                $stmt->execute(array($id));
                $name = $stmt->fetch(PDO::FETCH_OBJ);
                return $name;
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editTask($id, $nombre)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf("SELECT FKProyecto FROM tareas WHERE PKTarea=?");
        $stmt = $db->prepare($query);
        $stmt->execute(array($id));
        $row = $stmt->fetch();
        $id_proyecto = $row['FKProyecto'];

        $permiso = $con->getPermisos($id_proyecto);
        $permisoResponsable = $con->getPermisosResponsables($id_proyecto, $id);

        if ($permiso == 0) {
            if ($permisoResponsable == 0) {
                return -1;
            }
        }

        try {
            if ($nombre !== "") {
                $edita = sprintf("UPDATE tareas SET Tarea=? WHERE PKTarea=?");
                $stmt = $db->prepare($edita);
                $stmt->execute(array($nombre, $id));

                return "ok";
            } else {
                $stmt = $db->prepare("SELECT Tarea FROM tareas WHERE PKTarea=?");
                $stmt->execute(array($id));
                $name = $stmt->fetch(PDO::FETCH_OBJ);
                return $name;
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editColumn($id, $nombre)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            if ($nombre !== "") {
                $edita = sprintf("UPDATE columnas_proyecto SET nombre=? WHERE PKColumnaProyecto=?");
                $stmt = $db->prepare($edita);
                $stmt->execute(array($nombre, $id));
                return "ok";
            } else {
                $stmt = $db->prepare("SELECT Nombre FROM columnas_proyecto WHERE PKColumnaProyecto=?");
                $stmt->execute(array($id));
                $oName = $stmt->fetch(PDO::FETCH_OBJ);
                return $oName;
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function setLead($id, $pkR)
    {
        $timestamp = date('Y-m-d H:i:s');
        $con = new conectar();
        $db = $con->getDb();

        try {

            $lider = sprintf("UPDATE responsables_tarea SET FKUsuario=? WHERE PKResponsable=?");
            $stmt = $db->prepare($lider);
            $stmt->execute(array($id, $pkR));

            $query = sprintf("SELECT FKTarea, FKProyecto FROM responsables_tarea WHERE PKResponsable=?");
            $stmt = $db->prepare($query);
            $stmt->execute(array($pkR));
            $tarea = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $db->prepare("SELECT id FROM usuarios WHERE id=?");
            $stmt->execute(array($id));
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                /* INSERTAMOS LA NOTIFICACION EN LA BD */
                $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                $stmt->execute([':tipoNot' => 1, ':detaleNot' => 1, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $tarea['FKTarea'], ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $id]);
            }
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function noLead($pkR)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $lider = sprintf("UPDATE responsables_tarea SET FKUsuario=? WHERE PKResponsable=?");
            $stmt = $db->prepare($lider);
            $stmt->execute(array(null, $pkR));

            //id de la tarea
            $getid = sprintf("SELECT FKTarea FROM responsables_tarea WHERE PKResponsable=?");
            $stmtId = $db->prepare($getid);
            $stmtId->execute(array($pkR));
            $id_tarea = $stmtId->fetch(PDO::FETCH_ASSOC);

            $notificacion = sprintf("UPDATE tarea_notificaciones SET FKResponsableTarea=? WHERE FKTarea=?");
            $stmt = $db->prepare($notificacion);
            $stmt->execute(array(null, $id_tarea["FKTarea"]));

            $notificacion2 = sprintf("UPDATE subtarea_notificaciones SET FKResponsableSubTarea=? WHERE FKSubTarea=?");
            $stmt2 = $db->prepare($notificacion2);
            $stmt2->execute(array(null, $id_tarea["FKTarea"]));

            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function change_text_elements($PKColorColumna, $texto)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $text = sprintf("UPDATE colores_columna SET nombre=? WHERE PKColorColumna=?");
            $stmt = $db->prepare($text);
            $stmt->execute(array($texto, $PKColorColumna));

            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function change_color_elements($PKColorColumna, $color)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $edit = sprintf("UPDATE colores_columna SET color=? WHERE PKColorColumna=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($color, $PKColorColumna));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function define_symbol_side($id, $side)
    {
        $con = new conectar();
        $db = $con->getDb();
        $lugar = 0;

        if ($side == "right") {
            $lugar = 1;
        }

        try {

            $edit = sprintf("UPDATE numeros_tabla SET Lugar=? WHERE FKColumnaProyecto=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($lugar, $id));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function set_numeric_value($id, $num)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $edit = sprintf("UPDATE numeros_tabla SET Numero=? WHERE PKNumeros=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($num, $id));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function set_symbol_column($id, $symbol)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $edit = sprintf("UPDATE numeros_tabla SET Simbolo=? WHERE FKColumnaProyecto=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($symbol, $id));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function set_task_done($id_tarea, $id_element)
    { //columna verificar
        $timestamp = date('Y-m-d H:i:s');
        $con = new conectar();
        $db = $con->getDb();
        $ids_colores = [];
        $data_pkestado = [];
        $update_progress = "no";
        $datos_progreso = 0;

        $respuesta = [
            "respuesta" => "ok",
            "progreso" => $update_progress,
            $ids_colores,
            $data_pkestado,
            $datos_progreso,
        ];

        try {
            $edit = sprintf("UPDATE tareas SET Estado=1 WHERE PKTarea=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($id_tarea));

            /*$query = sprintf("SELECT proyectos.FKResponsable, responsables_tarea.FKUsuario FROM proyectos
            INNER JOIN tareas ON proyectos.PKProyecto = tareas.FKProyecto
            INNER JOIN responsables_tarea ON tareas.PKTarea = responsables_tarea.FKTarea
            WHERE tareas.PKTarea = ?");
            $stmtI = $db->prepare($query);
            $stmtI->execute(array($id_tarea));
            $row = $stmtI->fetch();

            $query = sprintf("INSERT INTO verificacion_notificaciones (FKResponsableTarea,FKUsuarioMencion,FechaCreacion,FKTarea,FKTipoNotificacion) VALUES (?,?,?,?,?)");
            $stmtII = $db->prepare($query);
            $stmtII->execute(array($row['FKUsuario'],$row['FKResponsable']),date('Y-m-d'),$id_tarea,4);
             */
            // $edit2=sprintf("UPDATE verificacion_tarea SET Bandera=1 WHERE PKVerificacion=?");
            // $stmt2= $db->prepare($edit2);
            // $stmt2->execute(array($id_element));

            //Obteniendo el total de columnas tipo estado:
            $fkproyecto = sprintf("SELECT FKProyecto FROM tareas WHERE PKTarea = ?");
            $stmt5 = $db->prepare($fkproyecto);
            $stmt5->execute(array($id_tarea));
            $dato_proyecto = $stmt5->fetch(PDO::FETCH_ASSOC); //ID del proyecto

            $stmt6 = $db->prepare("SELECT PKColumnaProyecto FROM columnas_proyecto WHERE tipo=2 AND FKProyecto=?");
            $stmt6->execute(array($dato_proyecto["FKProyecto"]));
            $cuenta_datos = $stmt6->rowCount();

            //columnas tipo progreso:
            $progreso_columna = $db->prepare("SELECT * FROM columnas_proyecto WHERE tipo=10 AND FKProyecto=?");
            $progreso_columna->execute(array($dato_proyecto["FKProyecto"]));
            $cuenta_progeso = $progreso_columna->rowCount();

            //Si tiene columnas de tipo estado:
            if ($cuenta_datos !== 0) {

                $dato_columnas_estado = $stmt6->fetchAll(PDO::FETCH_ASSOC);

                $pkestado = $db->prepare("SELECT PKEstadoTarea FROM estado_tarea WHERE FKTarea = ?");
                $pkestado->execute(array($id_tarea));
                $data_pkestado = $pkestado->fetchAll(PDO::FETCH_ASSOC);

                for ($i = 0; $i < count($dato_columnas_estado); $i++) {
                    $colores = $db->prepare("SELECT * FROM colores_columna WHERE FKColumnaProyecto = ? AND color='#28c67a'");
                    $colores->execute(array($dato_columnas_estado[$i]["PKColumnaProyecto"]));
                    $color_verde_id = $colores->fetchAll(PDO::FETCH_ASSOC);
                    array_push($ids_colores, $color_verde_id); //Por cada columna guarda el id del color verde.
                }
                //Comprobar esta consulta
                //SELECT * FROM colores_columna LEFT JOIN estado_tarea ON FKTarea = ? WHERE FKColumnaProyecto = 86 AND color='#28c67a'
                for ($j = 0; $j < count($data_pkestado); $j++) {
                    $up_colores = $db->prepare("UPDATE estado_tarea SET FKColorColumna=? WHERE PKEstadoTarea=?");
                    $up_colores->execute(array($ids_colores[$j][0]["PKColorColumna"], $data_pkestado[$j]["PKEstadoTarea"]));
                }

                $respuesta = [
                    "respuesta" => "update",
                    "progreso" => $update_progress,
                    $ids_colores,
                    $data_pkestado,
                    $datos_progreso,
                ];
            }

            //Si tiene columna de tipo progreso:
            //actualizar el porcentaje de la tarea a 100 y modificar el HTML:
            if ($cuenta_progeso !== 0) {
                $respuesta["progreso"] = "si";
                $actualiza_cien = $db->prepare("UPDATE progreso_tarea SET Progreso=100 WHERE FKTarea = ?");
                $actualiza_cien->execute(array($id_tarea));
                $respuesta[2] = $id_tarea;
            }

            /*=============================================
            =            Sección notificaciones           =
            =============================================*/

            $query = sprintf("SELECT FKUsuario, FKProyecto FROM responsables_tarea WHERE FKTarea = ? AND EXISTS (SELECT 1 FROM usuarios)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_tarea));
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($tareas as $tarea) {
                    if ($tarea['FKUsuario'] > 0) {
                        /* INSERTAMOS LA NOTIFICACION EN LA BD */
                        $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                        $stmt->execute([':tipoNot' => 1, ':detaleNot' => 2, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $id_tarea, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);
                    }
                }
            }

            /*=====  End of Sección notificaciones ======*/

            return $respuesta;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function set_subtask_done($id_tarea, $id_element)
    {
        $timestamp = date('Y-m-d H:i:s');
        $con = new conectar();
        $db = $con->getDb();
        $progress_subtareas = 0; //si hay progreso subtareas cambia a 1

        try {
            $progress = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ?");
            $progress->execute(array(16));
            $cuentaP = $progress->rowCount();

            if ($cuentaP !== 0) { //Si existe columna progreso
                $datos_progreso = $progress->fetch(PDO::FETCH_ASSOC);
                $progress_subtareas = 1;
                //actualizar progreso:
                $q = sprintf("UPDATE progreso_subtarea SET Progreso=? WHERE FKTarea=?");
                $update = $db->prepare($q);
                $update->execute(array(100, $id_tarea));
            }

            //actualizar tarea
            $query = sprintf("UPDATE tareas SET Terminada=? WHERE PKTarea = ?");
            $actualiza = $db->prepare($query);
            $actualiza->execute(array(1, $id_tarea));

            //actualizar subtareas
            $query2 = sprintf("UPDATE subtareas SET Terminada=? WHERE FKTarea = ?");
            $actualiza2 = $db->prepare($query2);
            $actualiza2->execute(array(1, $id_tarea));

            /*=============================================
            =            Sección notificaciones           =
            =============================================*/

            $query = sprintf("SELECT FKUsuario, FKProyecto FROM responsables_tarea WHERE FKTarea = ? AND EXISTS (SELECT 1 FROM usuarios)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_tarea));
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($tareas as $tarea) {
                    if ($tarea['FKUsuario'] > 0) {
                        /* INSERTAMOS LA NOTIFICACION EN LA BD */
                        $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                        $stmt->execute([':tipoNot' => 3, ':detaleNot' => 17, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $id_tarea, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);
                    }
                }
            }

            /*=====  End of Sección notificaciones ======*/

            //ids de las subtareas actualizadas:
            $query3 = sprintf("SELECT PKSubTarea FROM subtareas WHERE FKTarea = ?");
            $ids = $db->prepare($query3);
            $ids->execute(array($id_tarea));
            $ids_subs = $ids->fetchAll(PDO::FETCH_OBJ);

            return $respuesta = [
                "progreso" => $progress_subtareas, //hacer el cambio al 100%
                $ids_subs, //ids de las subtareas de la tarea
            ];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function set_task_undone($id_tarea, $id_element)
    {
        $timestamp = date('Y-m-d H:i:s');
        $con = new conectar();
        $db = $con->getDb();
        $ids_colores = [];
        $data_pkestado = [];
        $update_progress = "no";
        $datos_progreso = 0;

        $respuesta = [
            "respuesta" => "ok",
            "progreso" => $update_progress,
            $ids_colores,
            $data_pkestado,
            $datos_progreso,
        ];

        try {
            $edit = sprintf("UPDATE tareas SET Estado=0 WHERE PKTarea=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($id_tarea));

            // $edit2=sprintf("UPDATE verificacion_tarea SET Bandera=0 WHERE PKVerificacion=?");
            // $stmt2= $db->prepare($edit2);
            // $stmt2->execute(array($id_element));

            //Obteniendo el total de columnas tipo estado:
            $fkproyecto = sprintf("SELECT FKProyecto FROM tareas WHERE PKTarea = ?");
            $stmt5 = $db->prepare($fkproyecto);
            $stmt5->execute(array($id_tarea));
            $dato_proyecto = $stmt5->fetch(PDO::FETCH_ASSOC); //ID del proyecto

            $stmt6 = $db->prepare("SELECT PKColumnaProyecto FROM columnas_proyecto WHERE tipo=2 AND FKProyecto=?");
            $stmt6->execute(array($dato_proyecto["FKProyecto"]));
            $cuenta_datos = $stmt6->rowCount();

            //columnas tipo progreso:
            $progreso_columna = $db->prepare("SELECT * FROM columnas_proyecto WHERE tipo=10 AND FKProyecto=?");
            $progreso_columna->execute(array($dato_proyecto["FKProyecto"]));
            $cuenta_progeso = $progreso_columna->rowCount();

            //Si tiene columnas de tipo estado:
            if ($cuenta_datos !== 0) {

                $dato_columnas_estado = $stmt6->fetchAll(PDO::FETCH_ASSOC);

                $pkestado = $db->prepare("SELECT PKEstadoTarea FROM estado_tarea WHERE FKTarea = ?");
                $pkestado->execute(array($id_tarea));
                $data_pkestado = $pkestado->fetchAll(PDO::FETCH_ASSOC);

                for ($i = 0; $i < count($dato_columnas_estado); $i++) {
                    $colores = $db->prepare("SELECT * FROM colores_columna WHERE FKColumnaProyecto = ? AND color='#b7b7b7'");
                    $colores->execute(array($dato_columnas_estado[$i]["PKColumnaProyecto"]));
                    $color_verde_id = $colores->fetchAll(PDO::FETCH_ASSOC);
                    array_push($ids_colores, $color_verde_id); //Por cada columna guarda el id del color verde.
                }
                //Comprobar esta consulta
                //SELECT * FROM colores_columna LEFT JOIN estado_tarea ON FKTarea = ? WHERE FKColumnaProyecto = 86 AND color='#28c67a'
                for ($j = 0; $j < count($data_pkestado); $j++) {
                    $up_colores = $db->prepare("UPDATE estado_tarea SET FKColorColumna=? WHERE PKEstadoTarea=?");
                    $up_colores->execute(array($ids_colores[$j][0]["PKColorColumna"], $data_pkestado[$j]["PKEstadoTarea"]));
                }

                $respuesta = [
                    "respuesta" => "update",
                    "progreso" => $update_progress,
                    $ids_colores,
                    $data_pkestado,
                    $datos_progreso,
                ];
            }

            //Si tiene columna de tipo progreso:
            //actualizar el porcentaje de la tarea a 100 y modificar el HTML:
            if ($cuenta_progeso !== 0) {
                $respuesta["progreso"] = "si";
                $actualiza_cien = $db->prepare("UPDATE progreso_tarea SET Progreso=0 WHERE FKTarea = ?");
                $actualiza_cien->execute(array($id_tarea));
                $respuesta[2] = $id_tarea;
            }

            /*=============================================
            =            Sección notificaciones           =
            =============================================*/

            $query = sprintf("SELECT FKUsuario, FKProyecto FROM responsables_tarea WHERE FKTarea = ? AND EXISTS (SELECT 1 FROM usuarios)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_tarea));
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($tareas as $tarea) {
                    if ($tarea['FKUsuario'] > 0) {
                        /* INSERTAMOS LA NOTIFICACION EN LA BD */
                        $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                        $stmt->execute([':tipoNot' => 1, ':detaleNot' => 15, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $id_tarea, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);
                    }
                }
            }

            /*=====  End of Sección notificaciones ======*/

            return $respuesta;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function set_subtask_undone($id_tarea, $id_element)
    {
        $timestamp = date('Y-m-d H:i:s');
        $con = new conectar();
        $db = $con->getDb();
        $progress_subtareas = 0; //si hay progreso subtareas cambia a 1

        try {
            //columna progreso
            $progress = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ?");
            $progress->execute(array(16));
            $cuentaP = $progress->rowCount();

            if ($cuentaP !== 0) { //Si existe columna progreso
                $datos_progreso = $progress->fetch(PDO::FETCH_ASSOC);
                $progress_subtareas = 1;
                //actualizar progreso:
                $q = sprintf("UPDATE progreso_subtarea SET Progreso=? WHERE FKTarea=?");
                $update = $db->prepare($q);
                $update->execute(array(0, $id_tarea));
            }

            //actualizar tarea
            $query = sprintf("UPDATE tareas SET Terminada=? WHERE PKTarea = ?");
            $actualiza = $db->prepare($query);
            $actualiza->execute(array(0, $id_tarea));

            //actualizar subtareas
            $query2 = sprintf("UPDATE subtareas SET Terminada=? WHERE FKTarea = ?");
            $actualiza2 = $db->prepare($query2);
            $actualiza2->execute(array(0, $id_tarea));

            //ids de las subtareas actualizadas:
            $query3 = sprintf("SELECT PKSubTarea FROM subtareas WHERE FKTarea = ?");
            $ids = $db->prepare($query3);
            $ids->execute(array($id_tarea));
            $ids_subs = $ids->fetchAll(PDO::FETCH_OBJ);

            /*=============================================
            =            Sección notificaciones           =
            =============================================*/

            $query = sprintf("SELECT FKUsuario, FKProyecto FROM responsables_tarea WHERE FKTarea = ? AND EXISTS (SELECT 1 FROM usuarios)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_tarea));
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($tareas as $tarea) {
                    if ($tarea['FKUsuario'] > 0) {
                        /* INSERTAMOS LA NOTIFICACION EN LA BD */
                        $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                        $stmt->execute([':tipoNot' => 3, ':detaleNot' => 18, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $id_tarea, ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);
                    }
                }
            }

            /*=====  End of Sección notificaciones ======*/

            return $respuesta = [
                "progreso" => $progress_subtareas, //hacer el cambio al 100%
                $ids_subs, //ids de las subtareas de la tarea
            ];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function edit_rank($id_element, $rango)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $edit = sprintf("UPDATE rango_fecha SET Rango=? WHERE PKRangoFecha=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($rango, $id_element));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function edit_text_element($id_element, $new_text)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $edit = sprintf("UPDATE texto_tarea SET Texto=? WHERE PKTexto=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($new_text, $id_element));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function simple_number($id_element, $number)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $edit = sprintf("UPDATE numeros_simple SET Numero=? WHERE PKNumerosSimple=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($number, $id_element));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function simple_text($id_element, $text)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $edit = sprintf("UPDATE texto_corto SET Texto=? WHERE PKTexto=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array($text, $id_element));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function edit_sub($id_sub, $nombre)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            if ($nombre !== "") {
                $edit = sprintf("UPDATE subtareas SET SubTarea=? WHERE PKSubTarea=?");
                $stmt = $db->prepare($edit);
                $stmt->execute(array($nombre, $id_sub));
                return "ok";
            } else {
                $stmt = $db->prepare("SELECT SubTarea FROM subtareas WHERE PKSubTarea=?");
                $stmt->execute(array($id_sub));
                $name = $stmt->fetch(PDO::FETCH_OBJ);
                return $name;
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function set_sub_done($id_sub)
    {
        $timestamp = date('Y-m-d H:i:s');
        $con = new conectar();
        $db = $con->getDb();
        $action_progress = 0; //bandera actualiza progreso
        $action_check = 0; //bandera actualiza verificar
        $progreso = 0;
        $id_verificar = 0;

        try {
            //actualizando subtarea
            $edit = sprintf("UPDATE subtareas SET Terminada=? WHERE PKSubTarea=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array(1, $id_sub));

            //obteniendo el id de la Tarea
            $total = $db->prepare("SELECT FKTarea FROM subtareas WHERE PKSubTarea=?");
            $total->execute(array($id_sub));
            $id_tarea = $total->fetch(PDO::FETCH_ASSOC);

            //Obteniendo el proyecto al que pertenece la tarea:
            $queryProyecto = sprintf("SELECT FKProyecto FROM tareas WHERE PKTarea=?");
            $proyecto = $db->prepare($queryProyecto);
            $proyecto->execute(array($id_tarea["FKTarea"]));
            $id_proyecto = $proyecto->fetch(PDO::FETCH_ASSOC);

            //checar progreso:
            $progress = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto=?");
            $progress->execute(array(16, $id_proyecto['FKProyecto']));
            $cuentaP = $progress->rowCount();

            //checar verificar
            $verificar = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto=?");
            $verificar->execute(array(15, $id_proyecto['FKProyecto']));
            $cuentaV = $verificar->rowCount();

            //Porcentaje:
            $totalSubs = $db->prepare("SELECT * FROM subtareas WHERE FKTarea=?");
            $totalSubs->execute(array($id_tarea["FKTarea"]));
            $all = $totalSubs->rowCount(); //total de subtareas
            $dataSubs = $totalSubs->fetchAll(PDO::FETCH_ASSOC); //Data de las subtareas

            $terminada = 0;

            for ($i = 0; $i < count($dataSubs); $i++) {
                if ($dataSubs[$i]["Terminada"] == 1) {
                    $terminada++;
                }
            }

            $porcentaje = ($terminada * 100) / $all;
            $progreso = intval($porcentaje);

            //actualizar tabla tareas en la BBDD de acuerdo al porcentaje:
            if ($progreso == 100) {
                $queryTask = sprintf("UPDATE tareas SET Terminada = ? WHERE PKTarea = ?");
                $tarea = $db->prepare($queryTask);
                $tarea->execute(array(1, $id_tarea["FKTarea"]));
            }

            if ($cuentaV !== 0) { //Si existe columna verificar
                $action_check = 1;
                $queryV = sprintf("SELECT PKVerificaSub FROM  verificar_subtarea WHERE FKTarea=?");
                $idverificar = $db->prepare($queryV);
                $idverificar->execute(array($id_tarea["FKTarea"]));
                $id_verificar = $idverificar->fetch(PDO::FETCH_ASSOC);
                //Saber si el porcentaje es cien para alterar el check
            }

            if ($cuentaP !== 0) { //Si existe columna progreso
                $action_progress = 1;
                //$data_columna = $progress->fetchAll(PDO::FETCH_ASSOC);
                //Trayendo las subtareas:
                //Actualizando el progreso:
                $stmt4 = $db->prepare("UPDATE progreso_subtarea SET Progreso=? WHERE FKTarea=?");
                $stmt4->execute(array($progreso, $id_tarea["FKTarea"]));
            }

            /*=============================================
            =            Sección notificaciones           =
            =============================================*/

            $query = sprintf("SELECT FKUsuario, FKProyecto FROM responsables_tarea WHERE FKTarea = ? AND EXISTS (SELECT 1 FROM usuarios)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_tarea["FKTarea"]));
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($tareas as $tarea) {
                    if ($tarea['FKUsuario'] > 0) {
                        /* INSERTAMOS LA NOTIFICACION EN LA BD */
                        $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                        $stmt->execute([':tipoNot' => 3, ':detaleNot' => 5, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $id_tarea["FKTarea"], ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);
                    }
                }
            }

            /*=====  End of Sección notificaciones ======*/

            return $response = [
                "action_progress" => $action_progress,
                "action_check" => $action_check,
                "progreso" => $progreso,
                "columna" => $id_tarea["FKTarea"],
                "id_verificar" => $id_verificar,
            ];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function set_sub_undone($id_sub)
    {
        $timestamp = date('Y-m-d H:i:s');
        $con = new conectar();
        $db = $con->getDb();
        $action_progress = 0; //bandera actualiza progreso
        $action_check = 0; //bandera actualiza verificar
        $progreso = 0;
        $id_verificar = 0;

        try {
            //actualizando dato de la subtarea:
            $edit = sprintf("UPDATE subtareas SET Terminada=? WHERE PKSubTarea=?");
            $stmt = $db->prepare($edit);
            $stmt->execute(array(0, $id_sub));

            //id de la tarea
            $total = $db->prepare("SELECT FKTarea FROM subtareas WHERE PKSubTarea=?");
            $total->execute(array($id_sub));
            $id_tarea = $total->fetch(PDO::FETCH_ASSOC);

            //Obteniendo el proyecto al que pertenece la tarea:
            $queryProyecto = sprintf("SELECT FKProyecto FROM tareas WHERE PKTarea=?");
            $proyecto = $db->prepare($queryProyecto);
            $proyecto->execute(array($id_tarea["FKTarea"]));
            $id_proyecto = $proyecto->fetch(PDO::FETCH_ASSOC);

            //checar progreso:
            $progress = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto=?");
            $progress->execute(array(16, $id_proyecto['FKProyecto']));
            $cuentaP = $progress->rowCount();

            //checar verificar
            $verificar = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto=?");
            $verificar->execute(array(15, $id_proyecto['FKProyecto']));
            $cuentaV = $verificar->rowCount();

            //Porcentaje:
            //Trayendo las subtareas:

            $totalSubs = $db->prepare("SELECT * FROM subtareas WHERE FKTarea=?");
            $totalSubs->execute(array($id_tarea["FKTarea"]));
            $all = $totalSubs->rowCount(); //total de subtareas
            $dataSubs = $totalSubs->fetchAll(PDO::FETCH_ASSOC); //Data de las subtareas

            $terminada = 0;

            for ($i = 0; $i < count($dataSubs); $i++) {
                if ($dataSubs[$i]["Terminada"] == 1) {
                    $terminada++;
                }
            }

            $porcentaje = ($terminada * 100) / $all;
            $progreso = intval($porcentaje);

            //actualizando la tarea como no terminada:
            $queryTask = sprintf("UPDATE tareas SET Terminada = ? WHERE PKTarea = ?");
            $tarea = $db->prepare($queryTask);
            $tarea->execute(array(0, $id_tarea["FKTarea"]));

            if ($cuentaV !== 0) { //Si existe columna verificar
                $action_check = 1;
                $queryV = sprintf("SELECT PKVerificaSub FROM  verificar_subtarea WHERE FKTarea=?");
                $idverificar = $db->prepare($queryV);
                $idverificar->execute(array($id_tarea["FKTarea"]));
                $id_verificar = $idverificar->fetch(PDO::FETCH_ASSOC);
                //Saber si el porcentaje es cien para alterar el check
            }

            if ($cuentaP !== 0) { //Si existe columna progreso
                $action_progress = 1;

                //Actualizando el progreso:
                $stmt4 = $db->prepare("UPDATE progreso_subtarea SET Progreso=? WHERE FKTarea=?");
                $stmt4->execute(array($progreso, $id_tarea["FKTarea"]));
            }

            /*=============================================
            =            Sección notificaciones           =
            =============================================*/

            $query = sprintf("SELECT FKUsuario, FKProyecto FROM responsables_tarea WHERE FKTarea = ? AND EXISTS (SELECT 1 FROM usuarios)");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_tarea["FKTarea"]));
            $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($tareas as $tarea) {
                    if ($tarea['FKUsuario'] > 0) {
                        /* INSERTAMOS LA NOTIFICACION EN LA BD */
                        $stmt = $db->prepare('INSERT INTO notificaciones (tipo_notificacion, detalle_tipo_notificacion, id_elemento, id_sub_elemento, created_at, usuario_creo, usuario_recibe) VALUES (:tipoNot, :detaleNot, :idElem, :idSubElem, :fecha, :usrCreo, :usrRecibe)');
                        $stmt->execute([':tipoNot' => 3, ':detaleNot' => 16, ':idElem' => $tarea['FKProyecto'], ':idSubElem' => $id_tarea["FKTarea"], ':fecha' => $timestamp, ':usrCreo' => $_SESSION['PKUsuario'], ':usrRecibe' => $tarea['FKUsuario']]);
                    }
                }
            }

            /*=====  End of Sección notificaciones ======*/

            return $response = [
                "action_progress" => $action_progress,
                "action_check" => $action_check,
                "progreso" => $progreso,
                "columna" => $id_tarea["FKTarea"],
                "id_verificar" => $id_verificar,
            ];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function new_project_name($id, $nombre)
    {
        $con = new conectar();
        $db = $con->getDb();

        $permiso = $this->getPermisos($id);

        if ($permiso == 0) {
            return -1;
        }

        try {
            if ($nombre !== "") {
                $edit = sprintf("UPDATE proyectos SET Proyecto=? WHERE PKProyecto=?");
                $stmt = $db->prepare($edit);
                $stmt->execute(array($nombre, $id));
                return "ok";
            } else {
                $stmt = $db->prepare("SELECT Proyecto FROM proyectos WHERE PKProyecto=?");
                $stmt->execute(array($id));
                $name = $stmt->fetch(PDO::FETCH_OBJ);
                return $name;
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function edit_menu_element($id, $texto)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            if ($texto !== "") {
                $edit = sprintf("UPDATE etiquetas_columna SET Nombre=? WHERE PKEtiqueta=?");
                $stmt = $db->prepare($edit);
                $stmt->execute(array($texto, $id));
                return "ok";
            } else {
                $stmt = $db->prepare("SELECT Nombre FROM etiquetas_columna WHERE PKEtiqueta=?");
                $stmt->execute(array($id));
                $name = $stmt->fetch(PDO::FETCH_OBJ);
                return $name;
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
} //fin edit_data

class elim_data
{

    public function elimColumn($id, $tipo)
    {
        $con = new conectar();
        $db = $con->getDb();
        $_bandera = null;
        $respuesta = [];

        try {

            if ($tipo == 2) {
                $update_progress = "no"; //no actualizar barra de progreso.
                $update_verificar = "no"; //no actualizar columna de verificar.
                $array_actualizar_tareas = [];
                $total_progresos = [];
                // //obtener el id del proyecto
                $consulta = $db->prepare("SELECT FKProyecto FROM columnas_proyecto WHERE PKColumnaProyecto = ?");
                $consulta->execute(array($id));
                $id_proyecto = $consulta->fetch(PDO::FETCH_ASSOC);

                $permiso = $con->getPermisos($id_proyecto["FKProyecto"]);

                if ($permiso == 0) {
                    return "hey" . -1 . $id_proyecto["FKProyecto"];
                }

                //Verificar cuántas columnas de tipo 2 existen en el proyecto:
                $total_tipos = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = 2 AND FKProyecto = ?");
                $total_tipos->execute(array($id_proyecto["FKProyecto"]));
                $cuantos = $total_tipos->rowCount();
                $verifica = $cuantos - 1; //Menos uno por la que se va a elminar

                //Verificar si hay columna de tipo "Casilla de verificación":
                $checkmark = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = 9 AND FKProyecto = ?");
                $checkmark->execute(array($id_proyecto["FKProyecto"]));
                $num_checkmark = $checkmark->rowCount();

                //Verificar si hay columna de tipo "Progreso"
                $comprobarP = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo=10 AND FKProyecto = ?");
                $comprobarP->execute(array($id_proyecto["FKProyecto"]));
                $cuentaP = $comprobarP->rowCount();

                //Obteniendo el total de tareas del proyecto:
                $total_tareas = $db->prepare("SELECT * FROM tareas WHERE FKProyecto = ?");
                $total_tareas->execute(array($id_proyecto["FKProyecto"]));
                $checar_num_tareas = $total_tareas->rowCount();
                $array_total_tareas = $total_tareas->fetchAll(PDO::FETCH_ASSOC);

                //Se elimina la columna de tipo estado
                $elimina = sprintf("DELETE FROM columnas_proyecto WHERE PKColumnaProyecto = ?");
                $stmt = $db->prepare($elimina);
                $stmt->execute(array($id));

                if ($verifica !== 0) { //Si había más de una columna tipo estado

                    if ($checar_num_tareas !== 0) { //Si hay tareas:

                        for ($i = 0; $i < count($array_total_tareas); $i++) {

                            $check_estado = $db->prepare("SELECT PKTarea,color FROM tareas LEFT JOIN estado_tarea on PKTarea = FKTarea LEFT JOIN colores_columna ON PKColorColumna = FKColorColumna where PKTarea = ?");
                            $check_estado->execute(array($array_total_tareas[$i]["PKTarea"]));
                            $array_check_color = $check_estado->fetchAll(PDO::FETCH_ASSOC);

                            for ($j = 0; $j < count($array_check_color); $j++) {

                                if ($array_check_color[$j]["color"] == "#28c67a") {
                                    $_bandera = 1; //Terminada
                                } else {
                                    $_bandera = 0; //No terminada
                                    $j = count($array_check_color);
                                }
                            }

                            $update_task = $db->prepare("UPDATE tareas SET Estado = ? WHERE PKTarea = ?");
                            $update_task->execute(array($_bandera, $array_total_tareas[$i]["PKTarea"]));
                        }
                    }
                } else { //Era la única columna de tipo estado

                    //Se actualizan las tareas al no haber más columnas tipo estado:
                    $update_task = $db->prepare("UPDATE tareas SET Estado = 0 WHERE FKProyecto = ?");
                    $update_task->execute(array($id_proyecto["FKProyecto"]));
                    //Si hay casilla de verificación y se elimina la columna estado, el estado de la tarea queda acorde a la casilla de verificación.

                }

                if ($num_checkmark !== 0) { //Existe columna tipo verificar
                    $update_verificar = "si";
                    if ($checar_num_tareas !== 0) { //Si hay tareas
                        $actualizar_tareas = $db->prepare("SELECT FKTarea,Terminada,PKVerificacion,Estado FROM tareas left join verificacion_tarea on PKTarea = FKTarea WHERE verificacion_tarea.FKProyecto = ?");
                        $actualizar_tareas->execute(array($id_proyecto["FKProyecto"]));
                        $array_actualizar_tareas = $actualizar_tareas->fetchAll(PDO::FETCH_OBJ);
                    }
                }

                if ($cuentaP !== 0) { //Existe columna tipo progreso
                    $update_progress = "si";

                    if ($checar_num_tareas !== 0) { //Si hay tareas

                        for ($i = 0; $i < count($array_total_tareas); $i++) { //Por cada tarea

                            $check_estado = $db->prepare("SELECT PKTarea,color FROM tareas LEFT JOIN estado_tarea on PKTarea = FKTarea LEFT JOIN colores_columna ON PKColorColumna = FKColorColumna where PKTarea = ?"); //Trae tarea y color hexadecimal
                            $check_estado->execute(array($array_total_tareas[$i]["PKTarea"]));
                            $array_check_color = $check_estado->fetchAll(PDO::FETCH_ASSOC);
                            $cienporciento = $check_estado->rowCount();
                            $verde = 0;

                            for ($j = 0; $j < count($array_check_color); $j++) {

                                if ($array_check_color[$j]["color"] == "#28c67a") {
                                    $verde++; //Terminada
                                }
                            }

                            $porcentaje = ($verde * 100) / $cienporciento;
                            $progreso = intval($porcentaje);

                            $stmt4 = $db->prepare("UPDATE progreso_tarea SET Progreso=? WHERE FKTarea=?");
                            $stmt4->execute(array($progreso, $array_total_tareas[$i]["PKTarea"]));

                            $progreso_tarea = array(
                                "PKTarea" => $array_total_tareas[$i]["PKTarea"],
                                "progreso" => $progreso,
                            );

                            array_push($total_progresos, $progreso_tarea);
                        }
                    }
                }
                return $respuesta = [
                    "update" => "no",
                    "updateV" => $update_verificar,
                    "updateP" => $update_progress,
                    $array_actualizar_tareas,
                    $total_progresos,
                ];
            } else { //Columna de otro tipo se elimina directo:

                $elimina = sprintf("DELETE FROM columnas_proyecto WHERE PKColumnaProyecto = ?");
                $stmt = $db->prepare($elimina);
                $stmt->execute(array($id));

                return $respuesta = [
                    "update" => "ok",
                ];
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function elimTask($id_tarea, $id_proyecto)
    {
        $con = new conectar();
        $db = $con->getDb();
        $aritmetica = "restar";
        $up1 = new acciones();

        try {
            $orden = sprintf("SELECT Orden FROM tareas WHERE PKTarea=?");
            $stm = $db->prepare($orden);
            $stm->execute(array($id_tarea));
            $ordTask = $stm->fetch(PDO::FETCH_ASSOC);
            $ord = $ordTask['Orden'];

            $orden2 = sprintf("SELECT Orden FROM tareas WHERE FKProyecto=? ORDER BY Orden DESC");
            $stmt21 = $db->prepare($orden2);
            $stmt21->execute(array($id_proyecto));
            $tareas2 = $stmt21->fetchAll(PDO::FETCH_ASSOC);

            $elimina = sprintf("DELETE FROM tareas WHERE PKTarea=?");
            $stmt = $db->prepare($elimina);
            $stmt->execute(array($id_tarea));

            //Si no quedan tareas:
            if ((count($tareas2)) == 0) {
                return "ok";
            } else {
                if ($tareas2[0]['Orden'] == $ord) { //Si la tarea era la última en orden del total de tareas:
                    return "ok";
                } else { //Si la posición de la tarea no es la última, actualiza el orden de las tareas
                    $upnow = $up1->sumaOResta($ord, $id_proyecto, $aritmetica, $id_tarea);
                    return $upnow;
                }
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function elimGroup($id_etapa, $id_proyecto)
    {
        $con = new conectar();
        $db = $con->getDb();
        $up = new acciones();
        try {
            //Consulta el total de etapas para determinar la acción según la posición de la etapa eliminada.
            $consulta = sprintf("SELECT * FROM etapas WHERE FKProyecto=? ORDER BY Orden DESC");
            $st1 = $db->prepare($consulta);
            $st1->execute(array($id_proyecto));
            $etapas = $st1->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($etapas);

            //Comprobar si la etapa tiene tareas o está vacía:
            $comprobar = sprintf("SELECT * FROM tareas WHERE FKEtapa=? ORDER BY Orden DESC");
            $stmt = $db->prepare($comprobar);
            $stmt->execute(array($id_etapa));
            $tareasEtapa = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
            //Verificar la posición de la etapa
            $posicion = sprintf("SELECT * FROM etapas WHERE PKEtapa=?");
            $stmt = $db->prepare($posicion);
            $stmt->execute(array($id_etapa));
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $pos = $data['Orden'];
            //Se elimina la etapa
            $delete = sprintf("DELETE FROM etapas WHERE PKEtapa=?"); //Elimina la etapa
            $st = $db->prepare($delete);
            $st->execute(array($id_etapa));

            if ($count != 0) { //Qué sí hay tareas en la etapa.
                //Eliminar las tareas de la etapa eliminada
                $deleteT = sprintf("DELETE FROM tareas WHERE FKEtapa=?"); //Elimina la etapa
                $stT = $db->prepare($deleteT);
                $stT->execute(array($id_etapa));
                //Si la posición de la etapa era la primera:
                if ($pos == 1) {
                    //Consulta el orden actual de las tareas del proyecto por el campo Orden
                    $tabla = "tareas";
                    $orderByOrden = $up->orderByOrden($id_proyecto, $tabla);
                    //Actualiza el orden de las tareas:
                    for ($i = 0; $i < count($orderByOrden); $i++) {
                        $act = $db->prepare("UPDATE tareas SET Orden=? WHERE PKTarea=?");
                        $act->execute(array($i + 1, $orderByOrden[$i]['PKTarea']));
                    }

                    //Consulta nuevo orden de las tareas:
                    $newOrderTareas = $up->ordenTareas($id_proyecto);
                    //Resta uno el campo Orden de la tabla "etapas".
                    $orden = $up->restaEtapas($id_proyecto, $pos);

                    if ($orden == "updated") { //Si existen más etapas
                        $update = $up->ordenEtapas($id_proyecto); //Consulta el orden nuevo de las etapas.
                        $accion = [ //Identifica la acción para el JavaScript.
                            "accion" => "actualizarTareas",
                            "numTareas" => $count,
                        ];
                        array_push($accion, $update, $newOrderTareas);
                        return $accion;
                    } else { //No existen más etapas:
                        $accion = [
                            "accion" => "eliminar",
                        ];
                        return $accion;
                    }
                } else if ($pos == $etapas[0]['Orden']) { //Verificar que la etapa sea la última
                    $accion = [
                        "accion" => "eEtapaATareas",
                        "numTareas" => $count,
                    ];
                    return $accion;
                } else { //La etapa no es la primera ni la última:
                    //Obteniendo la etapa inmediata superior a la etapa eliminada:
                    /*
                    $value = $pos-1;
                    $PKEtapa;
                    for ($i=0; $i < count($etapas); $i++) {
                    if ($etapas[$i]['Orden'] == $value) {
                    $PKEtapa = $etapas[$i]['PKEtapa'];
                    }
                    }
                     */
                    $val = $pos - 1;
                    $tareas3 = [];
                    for ($i = $val; $i > 0; $i--) { //ejmeplo: es la posición 3 de 4 etapas y la etapa 2 no tiene tareas
                        //Obtener el id de la etapa que en orden es la inmediata superior y tiene tareas
                        $plusFromUp = sprintf("SELECT PKEtapa FROM etapas WHERE Orden = ? AND FKProyecto=?");
                        $st = $db->prepare($plusFromUp);
                        $st->execute(array($i, $id_proyecto));
                        $etapa = $st->fetch(PDO::FETCH_ASSOC);
                        //Obtener el número más alto en orden de la tarea de esa etapa inmediata superior
                        $ordenT = sprintf("SELECT Orden FROM tareas WHERE FKEtapa=? ORDER BY Orden DESC");
                        $st2 = $db->prepare($ordenT);
                        $st2->execute(array($etapa['PKEtapa']));
                        $count_tareas_3 = $st2->rowCount();
                        $tareas3 = $st2->fetchAll(PDO::FETCH_ASSOC);
                        //var_dump($tareas3);
                        //Si la etapa inmediata superior tiene elementos:
                        if ($count_tareas_3 != 0) {
                            $i = 0;
                        }
                    }

                    //Si ninguna etapa superior tiene tareas:
                    if ($count_tareas_3 == 0) {
                        //Consulta el orden actual de las tareas del proyecto por el campo Orden
                        $tabla = "tareas";
                        $orderByOrden = $up->orderByOrden($id_proyecto, $tabla);
                        //Actualiza el orden de las tareas:
                        for ($i = 0; $i < count($orderByOrden); $i++) {
                            $act = $db->prepare("UPDATE tareas SET Orden=? WHERE PKTarea=?");
                            $act->execute(array($i + 1, $orderByOrden[$i]['PKTarea']));
                        }
                        //Consulta nuevo orden de las tareas:
                        $newOrderTareas = $up->ordenTareas($id_proyecto);
                        //Resta uno el campo Orden de la tabla "etapas".
                        $orden = $up->restaEtapas($id_proyecto, $pos);

                        $update = $up->ordenEtapas($id_proyecto); //Consulta el orden nuevo de las etapas.

                        $accion = [ //Identifica la acción para el JavaScript.
                            "accion" => "actualizarTareas",
                            "numTareas" => $count,
                        ];
                        array_push($accion, $update, $newOrderTareas);
                        return $accion;
                    } else { //tarea de orden mayor de la etapa con elementos
                        $mayor = $tareas3[0]['Orden']; //2
                        $ordenT = $mayor + 1;

                        //Seleccionando las tareas mayores al último orden de la etapa con elementos
                        $query = sprintf("SELECT * FROM tareas WHERE FKProyecto=? AND Orden > " . $mayor . "");
                        $stmt = $db->prepare($query);
                        $stmt->execute(array($id_proyecto));
                        $count_response = $stmt->rowCount();
                        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($count_response != 0) { //SI hay tareas inferiores

                            for ($i = 0; $i < count($response); $i++) {
                                $update = sprintf("UPDATE tareas SET Orden = ? WHERE PKTarea = ?");
                                $stmt = $db->prepare($update);
                                $stmt->execute(array($ordenT, $response[$i]['PKTarea']));
                                $ordenT++;
                            }

                            //Consulta nuevo orden de las tareas:
                            $newOrderTareas = $up->ordenTareas($id_proyecto);
                            //Resta uno el campo Orden de la tabla "etapas".
                            $orden = $up->restaEtapas($id_proyecto, $pos);

                            $update = $up->ordenEtapas($id_proyecto); //Consulta el orden nuevo de las etapas.
                            $accion = [ //Identifica la acción para el JavaScript.
                                "accion" => "actualizarTareas",
                                "numTareas" => $count,
                            ];
                            array_push($accion, $update, $newOrderTareas);
                            return $accion;
                        } else { //No hay tareas inferiores.
                            $update = $up->ordenEtapas($id_proyecto); //Consulta el orden nuevo de las etapas.
                            $accion = [ //Identifica la acción para el JavaScript.
                                "accion" => "actualizarArray",
                                "numTareas" => $count,
                            ];
                            array_push($accion, $update);
                            return $accion;
                        }
                    }
                }
            } else { //No hay tareas en la etapa.
                //Si la posición de la etapa eliminada es menor a la posición de la última etapa en orden
                if ($pos < $etapas[0]['Orden']) {
                    $orden = $up->restaEtapas($id_proyecto, $pos); //Resta uno el campo Orden de la tabla "etapas".
                    if ($orden == "updated") { //Si se actualizaron las etapas:
                        $update = $up->ordenEtapas($id_proyecto); //Consulta el orden nuevo de las etapas.
                        $accion = [
                            "accion" => "actualizar",
                        ];
                        array_push($accion, $update);
                        return $accion;
                    } else { //No existen etapas que actualizar
                        $accion = [
                            "accion" => "eliminar",
                        ];
                        return $accion;
                    }
                } else { //La etapa eliminada es la última en posición.
                    $accion = [
                        "accion" => "eliminar",
                    ];
                    return $accion;
                }
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function elimColorElement($PKColorColumna)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $delete = sprintf("DELETE FROM colores_columna WHERE PKColorColumna = ?");
            $stmt = $db->prepare($delete);
            $stmt->execute(array($PKColorColumna));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function elim_sub($id_sub, $id_tarea)
    {
        $con = new conectar();
        $db = $con->getDb();
        $cuenta = 0;
        $action_check = 0;
        $action_progress = 0;
        $id_verificar = 0;
        $progreso = 0;

        try {
            $delete = sprintf("DELETE FROM subtareas WHERE PKSubTarea = ?");
            $stmt = $db->prepare($delete);
            $stmt->execute(array($id_sub));

            //Subtareas que quedan:
            $consulta = sprintf("SELECT * FROM subtareas WHERE FKTarea=?");
            $stmt = $db->prepare($consulta);
            $stmt->execute(array($id_tarea));
            $cuenta = $stmt->rowCount();

            //Obteniendo el proyecto al que pertenece la tarea:
            $queryProyecto = sprintf("SELECT FKProyecto FROM tareas WHERE PKTarea=?");
            $proyecto = $db->prepare($queryProyecto);
            $proyecto->execute(array($id_tarea));
            $id_proyecto = $proyecto->fetch(PDO::FETCH_ASSOC);

            if ($cuenta !== 0) { //Que todavía hay subtareas:
                //Obtener porcentaje de terminadas:
                $totalSubs = $db->prepare("SELECT * FROM subtareas WHERE FKTarea=?");
                $totalSubs->execute(array($id_tarea));
                $all = $totalSubs->rowCount(); //total de subtareas
                $dataSubs = $totalSubs->fetchAll(PDO::FETCH_ASSOC); //Data de las subtareas

                $terminada = 0;

                for ($i = 0; $i < count($dataSubs); $i++) {
                    if ($dataSubs[$i]["Terminada"] == 1) {
                        $terminada++;
                    }
                }

                $porcentaje = ($terminada * 100) / $all;
                $progreso = intval($porcentaje);

                if ($progreso !== 100) {
                    //actualizando la tarea como no terminada:
                    $queryTask = sprintf("UPDATE tareas SET Terminada = ? WHERE PKTarea = ?");
                    $tarea = $db->prepare($queryTask);
                    $tarea->execute(array(0, $id_tarea));
                }

                if ($progreso == 100) {
                    $queryTask = sprintf("UPDATE tareas SET Terminada = ? WHERE PKTarea = ?");
                    $tarea = $db->prepare($queryTask);
                    $tarea->execute(array(1, $id_tarea));
                }
            } else {
                $queryTask = sprintf("UPDATE tareas SET Terminada = ? WHERE PKTarea = ?");
                $tarea = $db->prepare($queryTask);
                $tarea->execute(array(0, $id_tarea));
            }

            //checar progreso:
            $progress = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
            $progress->execute(array(16, $id_proyecto["FKProyecto"]));
            $cuentaP = $progress->rowCount();

            //checar verificar
            $verificar = $db->prepare("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
            $verificar->execute(array(15, $id_proyecto["FKProyecto"]));
            $cuentaV = $verificar->rowCount();

            if ($cuentaV !== 0) { //Si existe columna verificar
                $action_check = 1;
                $queryV = sprintf("SELECT PKVerificaSub FROM  verificar_subtarea WHERE FKTarea=?");
                $idverificar = $db->prepare($queryV);
                $idverificar->execute(array($id_tarea));
                $id_verificar = $idverificar->fetch(PDO::FETCH_ASSOC);
                //Saber si el porcentaje es cien para alterar el check
            }

            if ($cuentaP !== 0) { //Si existe columna progreso
                $action_progress = 1;
                //$data_columna = $progress->fetchAll(PDO::FETCH_ASSOC);
                //Trayendo las subtareas:
                //Actualizando el progreso:
                $stmt4 = $db->prepare("UPDATE progreso_subtarea SET Progreso=? WHERE FKTarea=?");
                $stmt4->execute(array($progreso, $id_tarea));
            }

            return $data = [
                "cuenta" => $cuenta,
                "action_progress" => $action_progress,
                "action_check" => $action_check,
                "progreso" => $progreso,
                "id_verificar" => $id_verificar,
            ];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function delete_menu_element($id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $elim = sprintf("DELETE FROM etiquetas_columna WHERE PKEtiqueta = ?");
            $stmt = $db->prepare($elim);
            $stmt->execute(array($id));
            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteProyect($id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $delete = sprintf("DELETE FROM proyectos WHERE PKProyecto = ?");
            $stmt = $db->prepare($delete);
            if ($stmt->execute(array($id))) {
                return ['status' => 'success'];
            }
            return ['status' => 'fail'];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
} //fin elim_data

/*****************************************/
/*### ORDEN DE TAREAS COLUMNAS ETAPAS ###*/
/*****************************************/
class data_order
{

    public function columnOrder($id, $orden)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            for ($i = 0; $i < count($orden); $i++) {
                $update = sprintf("UPDATE columnas_proyecto SET Orden = ? WHERE PKColumnaProyecto = ?");
                $stmt = $db->prepare($update);
                $stmt->execute(array($i + 1, $orden[$i]));
            }

            return "ok";
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function etapaOrder($id, $orden)
    { //Cuando la terea cambia de orden dentro de la misma etapa.
        $con = new conectar();
        $db = $con->getDb();
        $up1 = new acciones();

        try {
            for ($i = 0; $i < count($orden); $i++) {
                $update = sprintf("UPDATE tareas SET Orden = ? WHERE PKTarea = ?");
                $stmt = $db->prepare($update);
                $stmt->execute(array($i + 1, $orden[$i]));
            }

            $order = $up1->ordenTareas($id);

            return $order;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function tablaOrder($id, $orden, $tarea, $etapa)
    { //Cuando se reordena de una etapa a otra una tarea.
        $con = new conectar();
        $db = $con->getDb();
        $up1 = new acciones();

        try {

            for ($i = 0; $i < count($orden); $i++) {
                $update = sprintf("UPDATE tareas SET Orden = ? WHERE PKTarea = ?");
                $stmt = $db->prepare($update);
                $stmt->execute(array($i + 1, $orden[$i]));
            }

            $cambio = sprintf("UPDATE tareas SET FKEtapa=? WHERE PKTarea=?");
            $stmt = $db->prepare($cambio);
            $stmt->execute(array($etapa, $tarea));

            $order = $up1->ordenTareas($id);

            return $order;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function groupOrder($id, $orden)
    { //Cuando se cambia el orden de una etapa
        $con = new conectar();
        $db = $con->getDb();
        $up1 = new acciones();

        try {
            //Actualizando el orden de las etapas del proyecto.
            for ($i = 0; $i < count($orden); $i++) {
                $update = sprintf("UPDATE etapas SET Orden = ? WHERE PKEtapa = ?");
                $stmt = $db->prepare($update);
                $stmt->execute(array($i + 1, $orden[$i]));
            }

            //Obteniendo las tareas dentro de esa etapa.
            $dec = $db->prepare('SELECT * FROM tareas where FKEtapa = ? ORDER BY Orden');
            $dec->execute(array($orden[0]));
            $count_tareas1 = $dec->rowCount();
            $tarea1 = $dec->fetchAll(PDO::FETCH_ASSOC);

            //Si la etapa tiene tareas:
            if ($count_tareas1 != 0) {
                //Actualiza el orden de las tareas de esa etapa:
                $st = $db->prepare('SELECT * FROM etapas WHERE FKProyecto=? ORDER BY Orden');
                $st->execute(array($id));
                $groups = $st->fetchAll(PDO::FETCH_ASSOC);

                $cont = 1;
                for ($i = 0; $i < count($groups); $i++) { //Por cada etapa

                    $st = $db->prepare('SELECT * FROM tareas WHERE FKEtapa=? ORDER BY Orden');
                    $st->execute(array($groups[$i]['PKEtapa']));
                    $count_tasks = $st->rowCount();
                    $tasks = $st->fetchAll(PDO::FETCH_ASSOC);

                    if ($count_tasks != 0) { //Si la etapa tiene tareas
                        for ($j = 0; $j < count($tasks); $j++) { //Por cada tarea
                            $update = sprintf("UPDATE tareas SET Orden = ? WHERE PKTarea = ?");
                            $stmt = $db->prepare($update);
                            $stmt->execute(array($cont, $tasks[$j]['PKTarea']));
                            $cont++;
                        }
                    }
                }

                $data = [
                    "info" => "tareas", //Para indicarle al js que se van a actualizar todas las tareas.
                ];

                $order = $up1->ordenTareas($id);
                $allGroups = $up1->ordenEtapas($id);
                array_push($data, $order, $allGroups);
                return $data;
            }

            $data = [
                "info" => "no", //Para indicarle al js que NO se van a actualizar las tareas.
            ];

            $allGroups = $up1->ordenEtapas($id);
            array_push($data, $allGroups);
            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
} //fin data_order

class get_data
{

    public function getUsers($idProyecto)
    {
        /* TODO: CAMBIAR LA CONSULTA PARA QUE SOLO TRAIGA LOS USUARIOS Y EMPLEADOS DEL PROYECTO */
        $con = new conectar();
        $db = $con->getDb();

        try {
            $stmt = $db->prepare("SELECT e.PKEmpleado, CONCAT(e.Nombres,' ',e.PrimerApellido) AS nombre_empleado, u.imagen, u.empresa_id AS empresa
            FROM integrantes_proyecto AS ip
            INNER JOIN empleados as e ON ip.FKUsuario = e.PKEmpleado 
            LEFT JOIN usuarios AS u ON e.PKEmpleado = u.id
            WHERE ip.FKProyecto = :idProyecto");
            $stmt->execute([":idProyecto" => $idProyecto]);
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getAllProjects()
    {

        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf("SELECT * FROM proyectos WHERE PKProyecto <> 3 ORDER BY Proyecto"); //Where usuario id sea= $_SESSION["id"];
            $stmt = $db->prepare($query);
            $stmt->execute();
            $proyectos = $stmt->fetchAll();
            //var_dump($proyectos);
            $proyectosmostrar = array();
            $x = 0;

            foreach ($proyectos as $p) {

                $stmt = $db->prepare("SELECT FKUsuario FROM integrantes_proyecto WHERE FKProyecto = :idProyecto");
                $stmt->bindValue(":idProyecto", $p['PKProyecto']);
                $stmt->execute();
                $usuariosProyecto = $stmt->fetchAll();
                //print_r($usuariosProyecto);

                $stmt = $db->prepare("SELECT ie.FKUsuario, ep.FKEquipo FROM equipos_por_proyecto as ep INNER JOIN integrantes_equipo as ie ON ie.FKEquipo = ep.FKEquipo WHERE ep.FKProyecto = :idProyecto");
                $stmt->bindValue(":idProyecto", $p['PKProyecto']);
                $stmt->execute();
                $usuariosEquipo = $stmt->fetchAll();
                //var_dump($usuariosEquipo);

                $usuarios = array_merge($usuariosProyecto, $usuariosEquipo);
                //print_r($usuarios);

                $cont = 0;
                foreach ($usuarios as $us) {
                    if ($us['FKUsuario'] == $_SESSION['PKUsuario']) {
                        $cont++;
                    }
                }

                if ($_SESSION['PKUsuario'] == $p['FKResponsable'] || $cont > 0) {
                    $proyectosmostrar[$x]['PKProyecto'] = $p['PKProyecto'];
                    $proyectosmostrar[$x]['Proyecto'] = $p['Proyecto'];
                }
                $x++;
            }

            //$stmt->fetchAll(PDO::FETCH_OBJ)
            return $proyectosmostrar;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function get_columns_type()
    {

        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf("SELECT * FROM tipo_columna WHERE PKTipoColumna <> 10 AND PKTipoColumna <> 9 AND PKTipoColumna <> 14 AND PKTipoColumna <> 15"); //
            $stmt = $db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function comprobar_columna_estado($id_project)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo=2 AND FKProyecto=?"); //
            $stmt = $db->prepare($query);
            $stmt->execute(array($id_project));
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }

    public function consultaEtiquetasMenu($id_columna, $array)
    { //consultar las todas etiquetas
        $con = new conectar();
        $db = $con->getDb();
        $datos = [];
        try {
            if ($array == 0) {

                $query = sprintf("SELECT * FROM etiquetas_columna WHERE FKColumnaProyecto = ?"); //Where usuario id sea= $_SESSION["id"];
                //$query = sprintf('SELECT * FROM etiquetas_columna LEFT JOIN etiquetas_tarea ON PKEtiqueta = FKEtiqueta WHERE FKColumnaProyecto = ?');
                $stmt = $db->prepare($query);
                $stmt->execute(array($id_columna));
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            } else {

                $query = sprintf("SELECT * FROM etiquetas_columna WHERE FKColumnaProyecto = ?"); //Where usuario id sea= $_SESSION["id"];
                $stmt = $db->prepare($query);
                $stmt->execute(array($id_columna));
                $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $object = new get_data();
                for ($i = 0; $i < count($array); $i++) {
                    $datos = $object->removeElementWithValue($datos, "PKEtiqueta", $array[$i]["PKEtiqueta"]);
                    //var_dump($result);
                }
                $result = array_values($datos);
                //var_dump($result);
                return $result;
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function removeElementWithValue($array, $key, $value)
    {
        foreach ($array as $subKey => $subArray) {
            if ($subArray[$key] == $value) {
                unset($array[$subKey]);
            }
        }
        return $array;
    }

    public function consultaEtiquetasSelected($id)
    { //consultar las todas etiquetas
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf("SELECT c.PKEtiqueta, c.Nombre, t.Bandera FROM etiquetas_columna AS c LEFT JOIN etiquetas_tarea AS t ON c.PKEtiqueta = t.FKEtiqueta WHERE t.FKMenu = ?"); //Where usuario id sea= $_SESSION["id"];
            //$query=sprintf("SELECT PKEtiqueta, Nombre, Bandera FROM etiquetas_columna AS c LEFT JOIN etiquetas_tarea AS t ON c.PKEtiqueta=t.FKEtiqueta WHERE c.FKMenu = ? ORDER BY PKEtiqueta"); //Where usuario id sea= $_SESSION["id"];
            //$query=sprintf("SELECT c.PKEtiqueta, c.Nombre, t.Bandera FROM etiquetas_columna AS c LEFT JOIN etiquetas_tarea AS t ON c.PKEtiqueta=t.FKEtiqueta WHERE FKColumnaProyecto = ? ORDER BY PKEtiqueta"); //Where usuario id sea= $_SESSION["id"];
            $stmt = $db->prepare($query);
            $stmt->execute(array($id));
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function consultaElementsSelected($id)
    { //consultar las todas etiquetas
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = sprintf("SELECT PKEtiqueta, Nombre FROM etiquetas_columna INNER JOIN etiquetas_tarea ON PKEtiqueta=FKEtiqueta WHERE etiquetas_tarea.FKMenu = ? ORDER BY PKEtiqueta"); //Where usuario id sea= $_SESSION["id"];
            $stmt = $db->prepare($query);
            $stmt->execute(array($id));
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function getAllTagsMenuToEdit($id)
    { //consultar las todas etiquetas
        $con = new conectar();
        $db = $con->getDb();

        try {
            //$query=sprintf("SELECT * FROM etiquetas_columna WHERE FKColumnaProyecto = ? "); //Where usuario id sea= $_SESSION["id"];
            $query = sprintf('SELECT * FROM etiquetas_columna WHERE FKColumnaProyecto = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($id));
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function get_sub($id_tarea)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $permisos = new stdClass();

            $get_data = sprintf("SELECT *, '-1' as permiso  FROM subtareas WHERE FKTarea = ?");
            $stmt = $db->prepare($get_data);
            $stmt->execute(array($id_tarea));
            $subtarea = $stmt->fetchAll(PDO::FETCH_OBJ);

            //Obteniendo el proyecto al que pertenece la tarea:
            $queryProyecto = sprintf("SELECT FKProyecto FROM tareas WHERE PKTarea=?");
            $proyecto = $db->prepare($queryProyecto);
            $proyecto->execute(array($id_tarea));
            $id_proyecto = $proyecto->fetch(PDO::FETCH_ASSOC);

            $permiso = $con->getPermisos($id_proyecto["FKProyecto"]);
            $permisoResponsable = $con->getPermisosResponsables($id_proyecto["FKProyecto"], $id_tarea);

            if ($permiso == 0) {
                if ($permisoResponsable == 0) {
                    $permisos->permiso = 0;
                } else {
                    $permisos->permiso = 1;
                }
            } else {
                $permisos->permiso = 1;
            }

            array_push($subtarea, $permisos);

            return $subtarea;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function get_subtask($array)
    {
        $con = new conectar();
        $db = $con->getDb();
        $data = [];
        try {
            for ($i = 0; $i < count($array); $i++) {
                $get_data = sprintf("SELECT * FROM subtareas WHERE FKTarea = ?");
                $stmt = $db->prepare($get_data);
                $stmt->execute(array($array[$i]));
                $total = $stmt->rowCount();

                $get_total_chat = sprintf("SELECT COUNT(c.PKChat) as total FROM chat as c WHERE c.FKTarea = ? UNION ALL SELECT COUNT(c.PKChat) as total FROM chat as c INNER JOIN chat_vistos as cv ON cv.FKChat = c.PKChat AND cv.FKUsuario = ? WHERE c.FKTarea = ?");
                $stmt = $db->prepare($get_total_chat);
                $stmt->execute(array($array[$i], $_SESSION['PKUsuario'], $array[$i]));
                $total_chat_row = $stmt->fetchAll();
                $total_chat = $total_chat_row[0]['total'] - $total_chat_row[1]['total'];

                array_push($data, [$array[$i], $total, $total_chat]);
            }

            return $data;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function verify_progress_columns($id_project)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $verificar = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
            $stmt = $db->prepare($verificar);
            $stmt->execute(array(9, $id_project));
            $respuesta1 = $stmt->rowCount();

            $verificar2 = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
            $stmt2 = $db->prepare($verificar2);
            $stmt2->execute(array(10, $id_project));
            $respuesta2 = $stmt2->rowCount();

            $verificar3 = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
            $stmt3 = $db->prepare($verificar3);
            $stmt3->execute(array(15, $id_project));
            $respuesta3 = $stmt3->rowCount();

            $verificar4 = sprintf("SELECT * FROM columnas_proyecto WHERE Tipo = ? AND FKProyecto = ?");
            $stmt4 = $db->prepare($verificar4);
            $stmt4->execute(array(16, $id_project));
            $respuesta4 = $stmt4->rowCount();

            return $respuesta = [
                "verificar" => $respuesta1,
                "progreso" => $respuesta2,
                "ver_sub" => $respuesta3,
                "pro_sub" => $respuesta4,
            ];
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function comprobar_subtareas($id_tarea)
    { //comprobar si la tarea tiene subtareas
        $con = new conectar();
        $db = $con->getDb();

        try {

            $query = sprintf("SELECT * FROM subtareas WHERE FKTarea = ?");
            $subtareas = $db->prepare($query);
            $subtareas->execute(array($id_tarea));
            $total = $subtareas->rowCount();
            return $total;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function checkValueSelected($id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf("SELECT * FROM etiquetas_tarea WHERE FKEtiqueta = ?");
            $isSelected = $db->prepare($query);
            $isSelected->execute(array($id));
            $cuenta = $isSelected->rowCount();
            return $cuenta;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
} //fin get_data

class acciones
{

    public function sumaOResta($ordenY, $id_proyecto, $aritmetica, $id_tarea)
    {
        $con = new conectar();
        $db = $con->getDb();
        //Orden de las tareas mayores
        $orden2 = sprintf("SELECT * FROM tareas WHERE FKProyecto=? AND PKTarea!=? AND Orden >= " . $ordenY . "");
        $stmtO = $db->prepare($orden2);
        $stmtO->execute(array($id_proyecto, $id_tarea));
        $tareasO = $stmtO->fetchAll(PDO::FETCH_ASSOC);

        if ($aritmetica == "sumar") {
            /*
            Se le asignará el orden mayor (a la nueva tarea) de acuerdo a la etapa que pertenece, por lo que
            sólo las tareas que ya existen con un orden mayor al asignado a la nueva tarea deberán cambiar aumentando
            +1 en cada caso
             */
            for ($i = 0; $i < count($tareasO); $i++) {
                $stmtU = $db->prepare("UPDATE tareas SET Orden=? WHERE PKTarea=?");
                $stmtU->execute(array(($tareasO[$i]['Orden'] + 1), $tareasO[$i]['PKTarea']));
            }
        } else {

            for ($i = 0; $i < count($tareasO); $i++) {
                $stmtU = $db->prepare("UPDATE tareas SET Orden=? WHERE PKTarea=?");
                $stmtU->execute(array(($tareasO[$i]['Orden'] - 1), $tareasO[$i]['PKTarea']));
            }
        }

        //Consulta con el nuevo orden de las tareas:
        $newOrder = sprintf("SELECT PKTarea,Orden FROM tareas WHERE FKProyecto=?");
        $stmtF = $db->prepare($newOrder);
        $stmtF->execute(array($id_proyecto));
        $newOrderTareas = $stmtF->fetchAll(PDO::FETCH_OBJ);

        return $newOrderTareas;

        $stmt = null;
        $db = null;
    }

    public function restaEtapas($id_proyecto, $pos)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $resta = sprintf("SELECT * FROM etapas WHERE FKProyecto=?");
            $stmt = $db->prepare($resta);
            $stmt->execute(array($id_proyecto));
            $count_data_1 = $stmt->rowCount();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Si existen etapas:
            if ($count_data_1 != 0) {
                for ($i = 0; $i < count($data); $i++) {
                    $stmt = $db->prepare("UPDATE etapas SET Orden = ? WHERE PKEtapa = ? AND Orden > ?");
                    $num = $data[$i]['Orden'] - 1;
                    $stmt->execute(array($num, $data[$i]['PKEtapa'], $pos));
                }

                return "updated";
            } else { //Si no existen etapas
                return "noGroups";
            }
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function ordenTareas($id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            //Consulta con el nuevo orden de las tareas:
            $newOrder = sprintf("SELECT PKTarea,Orden FROM tareas WHERE FKProyecto=?");
            $stmtF = $db->prepare($newOrder);
            $stmtF->execute(array($id));
            $newOrderTareas = $stmtF->fetchAll(PDO::FETCH_OBJ);

            return $newOrderTareas;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function orderByOrden($id, $tabla)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {

            $query = sprintf("SELECT * FROM " . $tabla . " WHERE FKProyecto=? ORDER BY Orden");
            $stmt = $db->prepare($query);
            $stmt->execute(array($id));
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $response;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function ordenEtapas($id)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            //Consulta con el nuevo orden de las tareas:
            $newOrder = sprintf("SELECT PKEtapa,Orden FROM etapas WHERE FKProyecto=?");
            $stmtF = $db->prepare($newOrder);
            $stmtF->execute(array($id));
            $newOrderTareas = $stmtF->fetchAll(PDO::FETCH_OBJ);

            return $newOrderTareas;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
}

class buscar_data
{

    public function buscarTarea($inputValue, $id_proyecto)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {

            //consulta a solo tareas de las etapas
            //$busqueda = sprintf("SELECT * FROM tareas WHERE Tarea LIKE ? and FKProyecto = ?");
            //consulta a las etapas con sus tareas
            $busqueda = sprintf("SELECT tareas.PKTarea, tareas.Tarea,etapas.PKEtapa, etapas.Etapa FROM tareas, etapas WHERE (etapas.PKEtapa = tareas.FKEtapa AND tareas.Tarea LIKE ? AND tareas.FKProyecto = ?) OR (etapas.Etapa LIKE ? AND tareas.FKProyecto = ?)");
            $stmt = $db->prepare($busqueda);
            $stmt->execute(array("%" . $inputValue . "%", $id_proyecto, "%" . $inputValue . "%", $id_proyecto));
            $respuesta = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $respuesta;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
}

//clase buscar mis tareas asignadas
class mis_tareas
{

    public function buscarMisTareas($id_user, $id_proyecto)
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $misTareas = sprintf("SELECT PKTarea, FKEtapa FROM tareas INNER JOIN responsables_tarea on responsables_tarea.FKTarea = tareas.PKTarea WHERE responsables_tarea.FKUsuario = ? AND tareas.FKProyecto=?");
            $stmt = $db->prepare($misTareas);
            $stmt->execute(array($id_user, $id_proyecto));
            $respuesta = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $respuesta;
        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }
        $stmt = null;
        $db = null;
    }
}
