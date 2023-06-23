<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

$token = $_POST['token_4s45us'];

if(empty($_SESSION['token_ld10d'])) {
    echo "fallo";
    return;           
}

if (!hash_equals($_SESSION['token_ld10d'], $token)) {
    echo "fallo";
    return;
}

    require_once('../../../include/db-conn.php');

        $id = (int) $_POST['idEquipoU'];
        $Equipo = $_POST['txtEquipo'];
        $idUsuarioU = $_POST['cmbIdUsuarioU'];
      //Consulta actual de equipos
        $stmt2 =$conn->prepare('SELECT FKEmpleado FROM integrantes_equipo WHERE FKEquipo = :id');
        $stmt2->execute(array(':id'=>$id));
        $stmt2->execute();
        $cont2 = $stmt2->rowCount();//contar los usuarios de la consulta actual
        
      

        try{
              $conn->beginTransaction();
              //Update Nombre de Equipo
              $stmt = $conn->prepare('UPDATE equipos set Nombre_Equipo= :equipo WHERE PKEquipo = :id');
              $stmt->bindValue(':equipo',$Equipo);
              $stmt->bindValue(':id', $id, PDO::PARAM_INT);
              $stmt->execute();

              $stmt4 = $conn->prepare("DELETE FROM integrantes_equipo WHERE FKEquipo = :id");
              $stmt4->bindValue(':id',$id);
              $stmt4->execute();

              foreach ($idUsuarioU as $usu){ 
                $stmt3 = $conn->prepare('INSERT INTO integrantes_equipo (FKEquipo, FKEmpleado) VALUES (:equipo,:fkusuario)');
                $stmt3->bindValue(':equipo',$id);
                $stmt3->bindValue(':fkusuario',$usu);
                $stmt3->execute();
              }

            if($conn->commit()){
              echo "exito";
            }
            else{
              echo "fallo";
            }
        
        }catch(PDOException $ex){
          //echo $ex->getMessage();
          $conn->rollBack();
          echo "fallo";
        }
 ?>
