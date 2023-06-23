<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$user = $_SESSION["Usuario"];

class conectar
{ //Llamado al archivo de la conexión.
    public function getDb()
    {
        include "../../../../include/db-conn.php";
        return $conn;
    }
}
class get_data
{
    public function getSubcategoryTable()
    {
        $idEmpresa = $_SESSION["IDEmpresa"];
        $con = new conectar();
        $db = $con->getDb();
        $table = "";
        $estilo = '<label ' . 'class=' . '\"' . 'textTable' . '\"' . '>';

        $query = 'SELECT * FROM(
                    SELECT sc.PKSubcategoria,
                        sc.Nombre as nomSubCat,
                        sc.FKCategoria,
                        c.PKCategoria,
                        c.Nombre as nomCategoria
                    FROM subcategorias_gastos as sc INNER JOIN categoria_gastos as c ON sc.FKCategoria = c.PKCategoria
                    WHERE c.estatus = :stat AND c.empresa_id = :idEmpresa
                    UNION 
                    SELECT
                        sc.PKSubcategoria,
                        sc.Nombre as nomSubCat,
                        sc.FKCategoria,
                        c.PKCategoria,
                        c.Nombre as nomCategoria
                    FROM 
                        subcategorias_gastos as sc INNER JOIN categoria_gastos as c ON sc.FKCategoria = c.PKCategoria
                    WHERE PKSubcategoria = 1 ) AS cat ORDER BY cat.PKCategoria;
                    ';
        $stmt = $db->prepare($query);
        $stmt->bindValue(':stat', 1);
        $stmt->bindValue(':idEmpresa', $idEmpresa);
        $stmt->execute();
        $array = $stmt->fetchAll();
        $cont = 0;

        foreach ($array as $r) {
            $id = $r['PKSubcategoria'];
            $nombre = $r['nomSubCat'];
            $nombreCategoria = $r['nomCategoria'];

            $acciones = '<i class=\"fas fa-edit pointer permission-view-edit\" data-toggle=\"modal\" data-target=\"#editar_SubcategoriaGastos_48\" onclick=\"obtenerIdSubcategoriaEditar(' . $id . ');\"></i>';

            $table .= '
        {"id":"' . $id . '",
          "NoSubcategoria":"' . $id . '",
          "Nombre":"' . $nombre . '",
          "Acciones":"' . $acciones . '",
          "Categoria":"' . $nombreCategoria . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }
    public function validarSubcategoriaGasto($data, $categoria)
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf('call spc_ValidarUnicaSubCatGasto(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $categoria));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarRelacionSubCatGasto($data)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarRelacionSubCatGasto(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function validarSubcategoriaGastoU($data, $data2)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_ValidarUnicaSubCatGastoU(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($data, $data2));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }
    public function getCmbCategoriaG($idemp)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('SELECT cg.PKCategoria,
      cg.Nombre
      from categoria_gastos cg where empresa_id = :idempresa AND estatus = 1');
        $stmt = $db->prepare($query);
        $stmt->bindValue(':idempresa', $idemp);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}