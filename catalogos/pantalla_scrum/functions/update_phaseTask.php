<?php
  require_once('../../../include/db-conn.php');
  if($_POST['id']){
    $id = $_POST['id'];
    $etapa = $_POST['etapa'];
    $tarea = $_POST['tarea'];
    $posicionAnterior =$_POST['posicionAnterior']+1;
    $posicionNueva = $_POST['posicionNueva']+1;
    $txt = "";
    $indiceNuevo = $posicionNueva - 1;
    $indiceAnterior = $posicionAnterior - 1;

    $stmt = $conn->prepare('SELECT FKEtapa FROM tareas WHERE PKTarea = :id');
    $stmt->execute(array(':id'=>$tarea));
    $row = $stmt->fetch();
    $etapaActual = $row['FKEtapa'];
    //Si la etapa destino es igual a la etapa origen
    if($row['FKEtapa'] == $etapa){
      //Si la posición destino es menor a la posición origen
      if($posicionNueva < $posicionAnterior){
        //Query para obtener las tareas que pertenecen a la etapa origen
        $stmt = $conn->prepare('SELECT tareas.* FROM tareas WHERE FKEtapa = :etapa ORDER BY Orden ASC');
        $stmt->execute(array(':etapa'=>$row['FKEtapa']));
        $arrayGeneral = $stmt->fetchAll();
        $arrAux = [];
        $filasArrayGen = $stmt->rowCount();

        $txt .= "<-------Array original------->\n";
        foreach ($arrayGeneral as $r) {
          array_push($arrAux,$r['PKTarea']);
          $txt .= "Id tarea: ".$r['PKTarea']."\n".
                  "Tarea: ".$r['Tarea']."\n".
                  "Orden: ".$r['Orden']."\n";
        }
        $stmt = $conn->prepare('SELECT PKTarea FROM tareas WHERE FKEtapa = :etapa ORDER BY PKTarea DESC LIMIT 1');
        //$maximo = max($arrAux);
        $stmt->execute(array(':etapa'=>$row['FKEtapa']));
        $row1 = $stmt->fetch();
        $maximo = $row1['PKTarea'];
        $txt .= "Maximo: ".$maximo."\n";
        $txt .= "<-------Final Array original------->\n\n";
        $txt .= "Indice Anterior: ".$indiceAnterior."\nIndice Nuevo: ".$indiceNuevo."\n\n";
        $idDestino = $arrayGeneral[$indiceNuevo]['PKTarea'];
        $idOrigen = $arrayGeneral[$indiceAnterior]['PKTarea'];
        $idUltimo = $arrayGeneral[$filasArrayGen-1]['PKTarea'];
        $ordenDestino = $arrayGeneral[$indiceNuevo]['Orden'];
        $ordenOrigen = $arrayGeneral[$indiceAnterior]['Orden'];
        $ordenUltimo = $arrayGeneral[$filasArrayGen-1]['Orden'];

        $txt .= "<--------------TAREAS----------------->\n".
                "Tarea origen: ".$idOrigen."\n".
                "Tarea destino: ".$idDestino."\n".
                "Tarea última: ".$idUltimo."\n\n";
        $txt .= "<--------------Orden----------------->\n".
                "Orden origen: ".$ordenOrigen."\n".
                "Orden destino: ".$ordenDestino."\n".
                "Orden última: ".$ordenUltimo."\n\n";
        $diff = abs($ordenOrigen - $ordenDestino);
/*
        if($idOrigen > $idDestino){
          $ordenR = $idOrigen;
          $ordenQ = $idDestino;
        }else{
          $ordenR = $idDestino;
          $ordenQ = $idOrigen;
        }
        if($ordenOrigen > $ordenDestino){
          $ordenX = $ordenOrigen;
        }else{
          $ordenX = $ordenDestino;
        }

        $limite = $arrayGeneral[$indiceAnterior]['Orden'];
        //Query para obtener las tareas cuyo orden sea mayor al orden destino
        $stmt = $conn->prepare('SELECT PKTarea,Orden FROM tareas WHERE PKTarea <= :id ORDER BY Orden ASC');
        $stmt->execute(array(':id'=>$ordenR));
        $filasArray = $stmt->rowCount();
        $row = $stmt->fetchAll();
        $diff = abs($ordenOrigen - $ordenDestino);
*/

        foreach ($arrayGeneral as $r) {
          //$txt .= "Id arreglo: ".$r['Orden']."\n";
          if($diff == 1){
            if($ordenDestino < $ordenOrigen){
              if($ordenOrigen > $r['Orden']){
                $orden = $r['Orden'];
              }else if($ordenDestino < $r['Orden']){
                $orden = $r['Orden'];
              }
              if($ordenOrigen == $r['Orden']){
                $orden = $r['Orden'] - 1;
              }
              if($ordenDestino == $r['Orden']){
                $orden = $r['Orden'] + 1;
              }

            }else{
              $txt .= "todavía no...\n";
            }

          }else{
            if($ordenDestino < $ordenOrigen){
              if($ordenDestino <= $r['Orden']){
                $orden = $r['Orden'] + 1;
              }else{
                $orden = $r['Orden'];
              }
              if($ordenOrigen == $r['Orden']){
                $txt .= "entro...\n";
                $orden = $ordenDestino;
              }
              if($ordenOrigen < $r['Orden']){
                $orden = $r['Orden'];
              }


            }else{
              $txt .= "todavía no...\n";
            }

          }

          $txt .= "ID tarea: ".$r['PKTarea']."\n".
                  "Tarea: ".$r['Tarea']."\n".
                  "Orden: ".$orden."\n\n";
          $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id AND PKTarea <= :limite');
          $stmt->execute(array(':orden'=>$orden,':id'=>$r['PKTarea'],':limite'=>$maximo));
        }
        /*foreach ($row as $r) {
          $contador1 = 0;
          $diff = 0;
          if($idOrigen > $idDestino){
            if($ordenDestino > $ordenOrigen){
              $orden = $r['Orden'] - 1;
            }else{
              if(abs($ordenOrigen - $ordenDestino) == 1){
                $txt .= "no la...\n";
                $orden = $r['Orden'] + $contador1;
                $contador1++;
              }else{
                if($row[$rowCount-1]['PKTarea'] != $r['PKTarea']){
                  $orden = $r['Orden'] +1;

                }else {
                  $orden = $r['Orden'] - 1;
                }
              }

            }
          }else {
            if($ordenDestino < $ordenOrigen){
              if(abs($ordenOrigen - $ordenDestino) == 1){
                $orden = $r['Orden'] + $contador1;
                $contador1++;
              }else{
                if($row[$filasArray-1]['PKTarea'] != $r['PKTarea']){
                  $orden = $r['Orden'] + 1;
                }else if($arrayGeneral[$filasArrayGen-1]['PKTarea'] > $filasArrayGen) {
                  $txt .= "Holis...\n";
                }else{
                  $orden = $r['Orden'] - 1;
                }
              }
            }else{
              $orden = $r['Orden'] - 1;
            }
          }
          $txt .= "ID tarea: ".$r['PKTarea']."\nOrden: ".$r['Orden']."\nContador: ".$contador1."\nOrdenR: ".$ordenR."\n\n";
          $idTareaPlus = $r['PKTarea'];

          $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id AND PKTarea <= :limite');
          $stmt->execute(array(':orden'=>$orden,':id'=>$idTareaPlus,':limite'=>$ordenR));
        }
        if(abs($ordenOrigen - $ordenDestino) == 1){
          $txt .= "Entramos";
          $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id');
          $stmt->execute(array(':orden'=>$ordenOrigen,':id'=>$idDestino));
        }
          $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id');
          $stmt->execute(array(':orden'=>$ordenDestino,':id'=>$tarea));
          */


      }else if($posicionNueva > $posicionAnterior){
        $stmt = $conn->prepare('SELECT tareas.* FROM tareas WHERE FKEtapa = :etapa ORDER BY Orden ASC');
        $stmt->execute(array(':etapa'=>$row['FKEtapa']));
        $arrayGeneral = $stmt->fetchAll();
        $filasArrayGen = $stmt->rowCount();

        $txt .= "<-------Array original------->\n";
        foreach ($arrayGeneral as $r) {
          $txt .= "Id tarea: ".$r['PKTarea']."\n".
                  "Tarea: ".$r['Tarea']."\n".
                  "Orden: ".$r['Orden']."\n";
        }
        $stmt = $conn->prepare('SELECT PKTarea FROM tareas WHERE FKEtapa = :etapa ORDER BY PKTarea DESC LIMIT 1');
        //$maximo = max($arrAux);
        $stmt->execute(array(':etapa'=>$row['FKEtapa']));
        $row1 = $stmt->fetch();
        $maximo = $row1['PKTarea'];
        $txt .= "Maximo: ".$maximo."\n";
        $txt .= "<-------Final Array original------->\n\n";
        $txt .= "Indice Anterior: ".$indiceAnterior."\nIndice Nuevo: ".$indiceNuevo."\n\n";
        $idDestino = $arrayGeneral[$indiceNuevo]['PKTarea'];
        $idOrigen = $arrayGeneral[$indiceAnterior]['PKTarea'];
        $idUltimo = $arrayGeneral[$filasArrayGen-1]['PKTarea'];
        $ordenDestino = $arrayGeneral[$indiceNuevo]['Orden'];
        $ordenOrigen = $arrayGeneral[$indiceAnterior]['Orden'];
        $ordenUltimo = $arrayGeneral[$filasArrayGen-1]['Orden'];

        $txt .= "<--------------TAREAS----------------->\n".
                "Tarea origen: ".$idOrigen."\n".
                "Tarea destino: ".$idDestino."\n".
                "Tarea última: ".$idUltimo."\n\n";
        $txt .= "<--------------Orden----------------->\n".
                "Orden origen: ".$ordenOrigen."\n".
                "Orden destino: ".$ordenDestino."\n".
                "Orden última: ".$ordenUltimo."\n\n";
        $diff = abs($ordenOrigen - $ordenDestino);

        foreach ($arrayGeneral as $r) {
          if($diff == 1){
            if($ordenDestino > $ordenOrigen){
              if($ordenDestino < $r['Orden']){
                $orden = $r['Orden'];
              }else if($ordenOrigen > $r['Orden']){
                $orden = $r['Orden'];
              }
              if($ordenDestino == $r['Orden']){
                $orden = $r['Orden'] - 1;
              }
              if($ordenOrigen == $r['Orden']){
                $orden = $r['Orden'] + 1;
              }
            }else{
              $txt .= "todavía no 2da parte...\n";
            }
          }else{
            if($ordenDestino > $ordenOrigen){
              if($ordenOrigen > $r['Orden']){
                $orden = $r['Orden'];
              }else{
                $orden = $r['Orden'] - 1;
              }
              /*if($ordenDestino > $r['Orden']){
                $orden = $r['Orden'] - 1;
              }*/
              if($ordenOrigen == $r['Orden']){
                $txt .= "entro 2da parte...\n";
                $orden = $ordenDestino;
              }
              if($ordenDestino < $r['Orden']){
                $orden = $r['Orden'];
              }
            }
          }
          $txt .= "ID tarea: ".$r['PKTarea']."\n".
                  "Tarea: ".$r['Tarea']."\n".
                  "Orden: ".$orden."\n\n";
          $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id AND PKTarea <= :limite');
          $stmt->execute(array(':orden'=>$orden,':id'=>$r['PKTarea'],':limite'=>$maximo));
        }
        /*
        $stmt = $conn->prepare('SELECT * FROM tareas WHERE FKEtapa = :etapa');
        $stmt->execute(array(':etapa'=>$row['FKEtapa']));
        $arrayGeneral = $stmt->fetchAll();


        $stmt = $conn->prepare('SELECT PKTarea,Orden FROM tareas WHERE Orden <= :orden');
        $stmt->execute(array(':orden'=>$posicionNueva));
        $row = $stmt->fetchAll();
        $txt .= "ID destino: ".$arrayGeneral[$indiceNuevo]['Orden']." - Tarea Destino:".$arrayGeneral[$indiceNuevo]['Tarea']."\nID Origen: ".$arrayGeneral[$indiceAnterior]['Orden']."Tarea Origen: ".$arrayGeneral[$indiceAnterior]['Tarea'];
        //$txt .= "\nIndice Destino: ".$indiceNuevo."\nIndice Origen: ".$indiceAnterior."\n\n";
        if($arrayGeneral[$indiceNuevo]['Orden'] > $arrayGeneral[$indiceAnterior]['Orden']){
          foreach ($row as $r) {
            $orden = $r['Orden'];
            $idTareaPlus = $r['PKTarea'] + 1;
            $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id AND Orden <= :limite');
            $stmt->execute(array(':orden'=>$orden,':id'=>$idTareaPlus,':limite'=>$posicionNueva));
          }
          $stmt = $conn->prepare('SELECT * FROM tareas WHERE FKEtapa = :etapa');
          $stmt->execute(array(':etapa'=>$etapaActual));
          $arrayGeneral = $stmt->fetchAll();

          foreach ($arrayGeneral as $r) {
            $txt .= "ID: ".$r['PKTarea']."\nTarea: ".$r['Tarea']."\nOrden: ".$r['Tarea']."\n\n";
          }

          $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id');
          $stmt->execute(array(':orden'=>$arrayGeneral[$indiceNuevo]['Orden'],':id'=>$tarea));
        }else{
          foreach ($row as $r) {
            $orden = $r['Orden'];
            $idTareaPlus = $r['PKTarea'] +1;
            //$txt .= $idTareaPlus;
            $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id AND Orden <= :limite');
            $stmt->execute(array(':orden'=>$orden,':id'=>$idTareaPlus,':limite'=>$posicionNueva));
          }
          $stmt = $conn->prepare('SELECT * FROM tareas WHERE FKEtapa = :etapa');
          $stmt->execute(array(':etapa'=>$etapaActual));
          $arrayGeneral = $stmt->fetchAll();

          foreach ($arrayGeneral as $r) {
            $txt .= "ID: ".$r['PKTarea']."\nTarea: ".$r['Tarea']."\nOrden: ".$r['Tarea']."\n\n";
          }

          $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id');
          $stmt->execute(array(':orden'=>$arrayGeneral[$indiceNuevo]['Orden'],':id'=>$tarea));

        }
        */
      }
    }else if($row['FKEtapa'] < $etapa){
      $orden;
      $stmt = $conn->prepare('SELECT tareas.* FROM tareas ORDER BY Orden ASC');
      $stmt->execute();
      $arrayGeneral = $stmt->fetchAll();
      $filasArrayGen = $stmt->rowCount();

      $txt .= "<-------Array original------->\n";
      foreach ($arrayGeneral as $r) {
        $txt .= "Id tarea: ".$r['PKTarea']."\n".
                "Tarea: ".$r['Tarea']."\n".
                "Orden: ".$r['Orden']."\n";
        if($r['PKTarea'] == $tarea){
          $ordenOrigen = $r['Orden'];
          $tareaOrigen = $r['PKTarea'];
          $etapaOrigen = $r['FKEtapa'];
        }
      }
      $txt .= "<-------Final Array original------->\n\n";
      $txt .= "Orden origen: ".$ordenOrigen."\n";
      $indice = $posicionNueva-1;
      $txt .= "Indice: ".$indice."\nPosicion destino: ".$posicionNueva."\n";
      $stmt = $conn->prepare('SELECT PKTarea,Orden FROM tareas WHERE FKEtapa = :etapa ORDER BY Orden ASC');
      $stmt->execute(array(':etapa'=>$etapa));
      $row = $stmt->fetchAll();
      $nFilasEtapa = $stmt->rowCount();
      $txt .= "Cantidad de filas: ".$nFilasEtapa."\n";
      if($nFilasEtapa > 0){
        if(!array_key_exists($indiceNuevo,$row)){
          $ordenDestino = "";
          $stmt = $conn->prepare('SELECT Orden FROM tareas WHERE FKEtapa = :etapa ORDER BY Orden DESC LIMIT 1');
          $stmt->execute(array(':etapa'=>$etapa));
          $row = $stmt->fetch();
          $ordenDestino = $row['Orden'] + 1;
        }else{
          $ordenDestino = $row[$indiceNuevo]['Orden'];
        }

      }else{
        $auxEtapa = $etapa - 1;
        $stmt = $conn->prepare('SELECT Orden FROM tareas WHERE FKEtapa = :etapa ORDER BY Orden DESC LIMIT 1');
        $stmt->execute(array(':etapa'=>$auxEtapa));
        $row = $stmt->fetch();
        $ordenDestino = $row['Orden'] + 1;
      }

      //$txt .= "Posición nueva: ".$posicionNueva."\nIndice: ".$indice."\n\n";
      //$contador = count($row);
      //if($indice < $contador){
      $txt .= "Orden destino: ".$ordenDestino."\n\n";
        //$stmt = $conn->prepare('UPDATE tareas SET FKEtapa = :etapa, Orden = :orden WHERE PKTarea = :id');
        //$stmt->execute(array(':etapa'=>$etapa,':orden'=>$row[$indice]['Orden'],':id'=>$tarea));
      //}
      $stmt = $conn->prepare('SELECT * FROM tareas WHERE FKEtapa = :etapa');
      $stmt->execute(array(':etapa'=>$etapaOrigen));
      $contador = $stmt->rowCount();
      $arrayEtapa = $stmt->fetchAll();
      if($contador > 0){
        foreach ($arrayGeneral as $r) {

          if($r['Orden'] < $ordenDestino && $r['Orden'] > $ordenOrigen){
            $orden = $r['Orden'] - 1;
            $txt .= "<-------------Origen------------->\n";
            $txt .= "tarea: ".$r['PKTarea'].
                    "\nOrden: ".$orden.
                    "\nEtapa: ".$etapaOrigen.
                    "\nCantidad tareas: ".$contador."\n\n";
          }

          if($r['Orden'] >= $ordenDestino || $r['Orden'] < $ordenOrigen){
            $orden = $r['Orden'];
            $txt .= "<-------------Destino------------->\n";
            $txt .= "tarea: ".$r['PKTarea'].
                    "\nOrden: ".$orden."\n\n";
          }

          $stmt = $conn->prepare('UPDATE tareas SET Orden = :orden WHERE PKTarea = :id');
          $stmt->execute(array(':orden'=>$orden,':id'=>$r['PKTarea']));
        }
        $stmt = $conn->prepare('UPDATE tareas SET Orden = :orden, FKEtapa = :etapa WHERE PKTarea = :id');
        $stmt->execute(array(':orden'=>($ordenDestino - 1),':etapa'=>$etapa,':id'=>$tareaOrigen));
      }else{
        //$txt .= "No estan preparados\n";
        //$stmt = $conn->prepare('UPDATE tareas SET FKEtapa = :etapa WHERE PKTarea = :id');
        //$stmt->execute(array(':etapa'=>$etapa,':id'=>$tareaOrigen));
      }

        /*
        //$stmt = $conn->prepare('SELECT PKTarea, Orden FROM tareas WHERE PKTarea > :limite');
        //$stmt->execute(array(':id'=>$row[$indice]['PKTarea'],':limite'=>$tarea));
        $stmt = $conn->prepare('SELECT PKTarea, Orden FROM tareas WHERE PKTarea <= :id');
        $stmt->execute(array(':id'=>$row[$indice]['PKTarea']));
        $row1 = $stmt->fetchAll();
        $numero = 1;
        foreach ($row1 as $r) {

          if($r['PKTarea'] != $tarea){
            $txt .= "PKTarea: ".$r['PKTarea']."\nOrden: ".$r['Orden']."\n\n";

            $orden = $r['Orden'] - 1;
            $stmt = $conn->prepare('UPDATE tareas SET Orden = :orden WHERE PKTarea = :id');
            $stmt->execute(array(':orden'=>$numero,':id'=>$r['PKTarea']));
            $numero++;

          }
        }


      }else{
        $indiceNuevo = $indice - 1;
        $txt .= "hay un elemento nuevo.".$indiceNuevo."\n\n";

        $stmt = $conn->prepare('SELECT PKTarea, Tarea, Orden FROM tareas WHERE PKTarea = :id');
        $stmt->execute(array(':id'=>$row[$indiceNuevo]['PKTarea']));
        $row1 = $stmt->fetch();
        //$orden = $row1['Orden'] + 1;
        $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden, FKEtapa =:etapa WHERE PKTarea =:id');
        $stmt->execute(array(':orden'=>$row1['Orden'],':etapa'=>$etapa,':id'=>$tarea));

        $stmt = $conn->prepare('SELECT PKTarea, Tarea, Orden FROM tareas WHERE PKTarea <= :id AND PKTarea <> :tarea');
        $stmt->execute(array(':id'=>$row[$indiceNuevo]['PKTarea'],':tarea'=>$tarea));
        $row1 = $stmt->fetchAll();

        foreach ($row1 as $r) {
          $orden = $r['Orden'] - 1;
          $txt .= "PKTarea: ".$r['PKTarea']."\nTarea: ".$r['Tarea']."\nOrden: ".$r['Orden']."\n\n";
          $stmt = $conn->prepare('UPDATE tareas SET Orden =:orden WHERE PKTarea =:id');
          $stmt->execute(array(':orden'=>$orden,':id'=>$r['PKTarea']));
        }


      }*/

    }else{
      $stmt = $conn->prepare('SELECT tareas.* FROM tareas ORDER BY Orden ASC');
      $stmt->execute();
      $arrayGeneral = $stmt->fetchAll();
      $filasArrayGen = $stmt->rowCount();

      $txt .= "<-------Array original------->\n";
      foreach ($arrayGeneral as $r) {
        $txt .= "Id tarea: ".$r['PKTarea']."\n".
                "Tarea: ".$r['Tarea']."\n".
                "Orden: ".$r['Orden']."\n";
        if($r['PKTarea'] == $tarea){
          $ordenOrigen = $r['Orden'];
          $tareaOrigen = $r['PKTarea'];
          $etapaOrigen = $r['FKEtapa'];
        }
      }
      $txt .= "<-------Final Array original------->\n\n";
      $txt .= "Orden origen: ".$ordenOrigen."\n";
      $indice = $posicionNueva-1;
      $txt .= "Indice: ".$indice."\nPosicion destino: ".$posicionNueva."\n";
      $stmt = $conn->prepare('SELECT PKTarea,Orden FROM tareas WHERE FKEtapa = :etapa ORDER BY Orden ASC');
      $stmt->execute(array(':etapa'=>$etapa));
      $row = $stmt->fetchAll();
      $nFilasEtapa = $stmt->rowCount();
      $txt .= "Cantidad de filas: ".$nFilasEtapa."\n";
      if($nFilasEtapa > 0){
        if(!array_key_exists($indiceNuevo,$row)){
          $ordenDestino = "";
          $stmt = $conn->prepare('SELECT Orden FROM tareas WHERE FKEtapa = :etapa ORDER BY Orden DESC LIMIT 1');
          $stmt->execute(array(':etapa'=>$etapa));
          $row = $stmt->fetch();
          $ordenDestino = $row['Orden'] + 1;
        }else{
          $ordenDestino = $row[$indiceNuevo]['Orden'];
        }

      }else{
        $auxEtapa = $etapa + 1;
        $stmt = $conn->prepare('SELECT Orden FROM tareas WHERE FKEtapa = :etapa ORDER BY Orden ASC LIMIT 1');
        $stmt->execute(array(':etapa'=>$auxEtapa));
        $row = $stmt->fetch();
        $ordenDestino = $row['Orden'];
      }
      $txt .= "Orden destino: ".$ordenDestino."\n\n";

      $stmt = $conn->prepare('SELECT * FROM tareas WHERE FKEtapa = :etapa');
      $stmt->execute(array(':etapa'=>$etapaOrigen));
      $contador = $stmt->rowCount();
      $arrayEtapa = $stmt->fetchAll();

      foreach ($arrayGeneral as $r) {
        if($r['Orden'] >= $ordenDestino && $r['Orden'] < $ordenOrigen){
          $orden = $r['Orden'] + 1;
          $txt .= "<-------------Origen------------->\n";
          $txt .= "tarea: ".$r['PKTarea'].
                  "\nOrden: ".$orden.
                  "\nEtapa: ".$etapaOrigen.
                  "\nCantidad tareas: ".$contador."\n\n";
        }

        if($r['Orden'] < $ordenDestino || $r['Orden'] > $ordenOrigen){
          $orden = $r['Orden'];
          $txt .= "<-------------Destino------------->\n";
          $txt .= "tarea: ".$r['PKTarea'].
                  "\nOrden: ".$orden."\n\n";
        }
        $stmt = $conn->prepare('UPDATE tareas SET Orden = :orden WHERE PKTarea = :id');
        $stmt->execute(array(':orden'=>$orden,':id'=>$r['PKTarea']));
      }
      $stmt = $conn->prepare('UPDATE tareas SET Orden = :orden, FKEtapa = :etapa WHERE PKTarea = :id');
      $stmt->execute(array(':orden'=>($ordenDestino),':etapa'=>$etapa,':id'=>$tareaOrigen));
    }
    echo $txt;
  }

?>
