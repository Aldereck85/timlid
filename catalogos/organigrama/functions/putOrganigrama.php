<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
if (isset($_POST['idOrganigrama'])) {

    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);

    require_once '../../../include/db-conn.php';
    $idOrganigrama = $_POST['idOrganigrama'];
    $idEmpleadoOriginal = $_POST['idEmpleadoOriginal'];

    if (isset($_POST['imagenSubirEditar'])) {
        $imagenSubir = $_POST['imagenSubirEditar'];
    }
    else{
        $imagenSubir = "";
    }

    if(isset($_POST['idEmpleado'])){
      $idEmpleado = $_POST['idEmpleado'];
    }
    else{
      $idEmpleado = "";     
    }

    try{

            $conn->beginTransaction();

            $stmt = $conn->prepare('SELECT PKOrganigrama, Imagen_Perfil FROM organigrama WHERE FKEmpleado = :empleado');
            $stmt->bindValue(':empleado',$idEmpleado);
            $stmt->execute();
            $row = $stmt->fetch();
            
            $idorganigramaE = $row['PKOrganigrama'];
            if($row['Imagen_Perfil'] != null){
                $imagenMoverNueva = $row['Imagen_Perfil'];
            }else{
                $imagenMoverNueva = '';
            }
            $existeOrigen = $stmt->rowCount();

            $stmt = $conn->prepare('SELECT Imagen_Perfil FROM organigrama WHERE FKEmpleado = :empleadooriginal');
            $stmt->bindValue(':empleadooriginal',$idEmpleadoOriginal);
            $stmt->execute();
            $rowOriginal = $stmt->fetch();
            $imagenMover = $rowOriginal['Imagen_Perfil'];
            //echo $imagenMover."-".$idEmpleadoOriginal;

       if($idEmpleado != "" && $idEmpleado != $idEmpleadoOriginal){
            
            if($existeOrigen > 0){

              $stmt = $conn->prepare('DELETE FROM  organigrama WHERE PKOrganigrama = :idorganigrama');
              $stmt->bindValue(':idorganigrama',$idorganigramaE);
              $stmt->execute();
            }

            if($idorganigramaE != ''){
                //Obtener los nodos debajo del empleado ahora como lider de organizacion
                $stmt = $conn->prepare('SELECT PKOrganigrama FROM organigrama WHERE ParentNode = :idorganigrama');
                $stmt->bindValue(':idorganigrama',$idorganigramaE);
                $stmt->execute();
                $row = $stmt->fetchAll();
                
                foreach($row as $empleado){
                    //actualizar los nodos debajo del empleado ahora como lider de organizacion
                    $stmt = $conn->prepare('UPDATE organigrama SET ParentNode = :id WHERE PKOrganigrama = :idorganigrama');
                    $stmt->bindValue(':id',$idOrganigrama);
                    $stmt->bindValue(':idorganigrama',$empleado);
                    $stmt->execute();
                    echo $empleado;
                }
            }

            //actualiza los datos del lider de la organizacion
            $stmt = $conn->prepare('UPDATE organigrama SET FKEmpleado = :empleado, Imagen_Perfil = :imagen WHERE PKOrganigrama = :id');
            $stmt->bindValue(':empleado',$idEmpleado);
            $stmt->bindValue(':imagen',$imagenMoverNueva);
            $stmt->bindValue(':id',$idOrganigrama);
            
            if($stmt->execute()){
                if(trim($imagenMover) != ""){
                    unlink('../../empresas/archivos/'.$PKEmpresa.'/img'.'/'.$imagenMover);
                }
            }
        }
        //echo "imagenSubir: ".$imagenSubir;
        if($imagenSubir != ""){
            $stmt = $conn->prepare('UPDATE organigrama SET Imagen_Perfil = :imagen WHERE PKOrganigrama = :id');
            $stmt->bindValue(':imagen',$imagenSubir);
            $stmt->bindValue(':id',$idOrganigrama);
            $stmt->execute();
        }


        if($conn->commit()){

            if($imagenSubir != ""){
              $org_image='temp/'.$imagenSubir;
              $destination='../../empresas/archivos/'.$PKEmpresa.'/img'.'/';

              $img_name=basename($org_image);

              if(rename( $org_image , $destination.$imagenSubir )){
                if(trim($imagenMover) != ""){
                    unlink($destination.$imagenMover);
                }
              }
            }
            echo json_encode(array('status' => 'success', 'message' => 'Datos actualizados correctamente'));
        }


    }catch(PDOException $ex){
        echo $ex->getMessage();
    }
    
} else {
    echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos correctamente'));
}