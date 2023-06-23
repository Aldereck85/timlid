<?php
require_once('../../../../include/db-conn.php');

$empresa_id = $_REQUEST['value'];
$stmt = $conn->prepare('SELECT * FROM puestos WHERE empresa_id = :empresa_id AND estatus = 1 ORDER BY id DESC');
$stmt->bindValue("empresa_id",$empresa_id);
$stmt->execute();
$table="";
$array = $stmt->fetchAll();
$estilo = '<label '.'class='.'\"'.'textTable'.'\"'.'>';
$cont = 1;

foreach ($array as $r) {

  $id = $r['id'];
  $puesto = $r['puesto'];
  
  $acciones = '<i class=\"fas fa-edit pointer permission-view-edit\" data-toggle=\"modal\" data-target=\"#editar_Puestos_45\" onclick=\"obtenerIdPuestoEditar('.$id.');\"></i>';
  
  $table .= '
  {"id":"'.$id.'",
    "No":"'.$cont.'",
    "Acciones":"'.$acciones.'",
    "Puesto":"'.$puesto.'"},';
    $cont++;
  }
  

/*while (($row = $stmt->fetch()) !== false) {
    //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_puesto\" class=\"btn btn-primary\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\"><i class=\"fas fa-edit\"></i> Editar</a>';
    //$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_puesto\" class=\"btn btn-danger\" onclick=\"obtenerIdPuestoEliminar('.$row['PKPuesto'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $puesto = $row['Puesto'];
    $table.='{"id":"<label class=\"textTable\">'.$row['PKPuesto'].'","Puesto":"<label class=\"textTable\">'.$puesto.'<i><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#editar_Puesto\" onclick=\"obtenerIdPuestoEditar('.$row['PKPuesto'].');\" src=\"../../../img/timdesk/edit.svg\"></i>"},';
}*/
$table = substr($table,0,strlen($table)-1);
echo '{"data":['.$table.']}';

$conn = null;
$stmt = null;
$table = null;