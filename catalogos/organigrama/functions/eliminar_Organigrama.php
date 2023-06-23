<?php
require_once '../../../include/db-conn.php';
//$_POST['idOrganigramaD'] = 26;
$id = $_POST['idOrganigramaD'];
if (isset($_POST['idOrganigramaD'])) {
    try {
        $stmt = $conn->prepare("SELECT ParentNode FROM organigrama");
        $stmt->execute();
        $cantidadOrganigrama = $stmt->rowCount();

        $stmt = $conn->prepare("SELECT ParentNode, Imagen_Perfil FROM organigrama WHERE PKOrganigrama=:id");
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch();

        $stmt = $conn->prepare("SELECT PKOrganigrama FROM organigrama WHERE ParentNode=:id");
        $stmt->execute(array(':id' => $id));
        $ultimonodo = $stmt->rowCount();

        if ($ultimonodo < 1) {

            if ($row['ParentNode'] != 0 || $cantidadOrganigrama == 1) {
                $stmt = $conn->prepare("DELETE FROM organigrama WHERE PKOrganigrama=:id");
                if ($stmt->execute(array(':id' => $id))) {

                    if(trim($row['Imagen_Perfil']) != "" || trim($row['Imagen_Perfil']) != null){
                        if (file_exists("perfil/" . $row['Imagen_Perfil'])) {
                            unlink("perfil/" . $row['Imagen_Perfil']);
                        }
                    }
                    echo json_encode(array('status' => 'success', 'message' => 'Registro eliminado'));
                }
            } else {
                echo json_encode(array('status' => 'fail', 'message' => 'No se puede eliminar a el líder de la organización hasta el final'));
            }
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'No se pueden eliminar empleados del organigrama que tengan personal a su cargo'));
        }
    } catch (PDOException $ex) {
        echo json_encode(array('status' => 'fail', 'message' => 'Algo salio mal por favor intentalo mas tarde'));
    }
}