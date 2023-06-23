<?php
session_start();
if (isset($_POST['id'])) {
    require_once '../../../../include/db-conn.php';
    $json = new \stdClass();
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT v.PKVendedor,
    v.FKUsuario,
    v.FKEstatusGeneral as eg,
    u.Nombre as nom,
    eg.Estatus as nomest
      FROM vendedores as v INNER JOIN usuarios as u ON v.FKUsuario=u.PKUsuario
      INNER JOIN estatus_general as eg ON eg.PKEstatusGeneral=v.FKEstatusGeneral
       WHERE PKVendedor= :id");

    $stmt->execute(array(':id' => $id));
    $stmt->execute();
    $row = $stmt->fetch();
    $html = $row['nom'];
    $fkusuario = $row['FKUsuario'];
    $eg = $row['eg'];

    $json->html = $html;
    $json->fkusuario = $fkusuario;

    $stmt2 = $conn->prepare('SELECT * FROM usuarios ');

    $stmt2->execute(array(':id' => $id));
    $stmt2->execute();
    $row = $stmt2->fetchAll();
    $lista = " <option value='0'>Elija el nuevo nombre...</option>";
    //$listaCuentasDisponer = "<option value='0'>Elija la cuenta destino...</option>";
    foreach ($row as $d) {
        $lista .= "<option value='" . $d["PKUsuario"] . "'";
        //$lista .= " selected";
        $lista .= ">" . $d['Nombre'] . "</option>";
    }
    $json->lista = $lista;

    $stmt3 = $conn->prepare('SELECT * FROM estatus_general ');
    $stmt3->execute(array(':id' => $id));
    $stmt3->execute();
    $row3 = $stmt3->fetchAll();
    $listaEstatus = "";
    foreach ($row3 as $d) {
        $listaEstatus .= "<option value='" . $d["PKEstatusGeneral"] . "'";
        if ($eg == $d["PKEstatusGeneral"]) {
            $listaEstatus .= " selected";
        }
        $listaEstatus .= ">" . $d['Estatus'] . "</option>";
    }
    $json->listaEstatus = $eg;

    $json = json_encode($json);
    echo $json;
}