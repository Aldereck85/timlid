<?php
  require_once('../../../include/db-conn.php');
  if($_POST['id']){
    $id = $_POST['id'];
    $etapa = $_POST['etapa'];
    $tarea = $_POST['tarea'];
    $posicionAnterior =$_POST['posicionAnterior'];
    $posicionNueva = $_POST['posicionNueva'];
    $txt = "";
    $indiceNuevo = $posicionNueva - 1;
    $indiceAnterior = $posicionAnterior - 1;

    $txt .= "Posicion origen: ".$indiceAnterior."\n".
            "Posicion destino: ".$indiceNuevo."\n\n";

    if($posicionNueva < $posicionAnterior){
      //Query para obtener las etapas que pertenecen al proyecto origen
      $stmt = $conn->prepare('SELECT * FROM etapas WHERE FKProyecto = :id ORDER BY Orden ASC');
      $stmt->execute(array(':id'=>$id));
      $arrayGeneral = $stmt->fetchAll();
      if($indiceNuevo >= 2){
        $indiceNuevo = $indiceNuevo -1;
      }
      if($indiceAnterior >= 2){
        $indiceAnterior = $indiceAnterior -1;
      }
      $txt .= "<-------Array original------->\n";
      foreach ($arrayGeneral as $r) {
        $txt .= "Id etapa: ".$r['PKEtapa']."\n".
                "Etapa: ".$r['Etapa']."\n".
                "Orden: ".$r['Orden']."\n";
      }
      $txt .= "<-------Final Array original------->\n\n";
      $stmt = $conn->prepare('SELECT PKEtapa FROM etapas WHERE FKProyecto = :etapa ORDER BY PKEtapa DESC LIMIT 1');
      //$maximo = max($arrAux);
      $stmt->execute(array(':etapa'=>$id));
      $row1 = $stmt->fetch();
      $maximo = $row1['PKEtapa'];
      $ordenDestino = $arrayGeneral[$indiceNuevo]['Orden'];
      $ordenOrigen = $arrayGeneral[$indiceAnterior]['Orden'];
      $diff = abs($ordenOrigen - $ordenDestino);
      $txt .= "Orden destino: ".$ordenDestino."\n".
              "Orden origen: ".$ordenOrigen."\n\n";
      foreach ($arrayGeneral as $r) {
        if($diff == 1){
          //$txt .= "Si es igual a 1\n";
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
        $txt .= "Id Etapa: ".$r['PKEtapa']."\n".
                "Etapa: ".$r['Etapa']."\n".
                "Orden: ".$orden."\n\n";
        $stmt = $conn->prepare('UPDATE etapas SET Orden =:orden WHERE PKEtapa =:id AND PKEtapa <= :limite');
        $stmt->execute(array(':orden'=>$orden,':id'=>$r['PKEtapa'],':limite'=>$maximo));
      }
    }
    echo $txt;
  }


?>
