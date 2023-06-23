<?php
session_start();
$PKEmpresa = $_SESSION["IDEmpresa"];
if (isset($_POST["image"])) {
    $data = $_POST["image"];

    $image_array_1 = explode(";", $data);

    $image_array_2 = explode(",", $image_array_1[1]);

    $data = base64_decode($image_array_2[1]);

    $imageName = time() . '.jpg';

    file_put_contents('../../empresas/archivos/'.$PKEmpresa.'/img'.'/' . $imageName, $data);

    echo '<center><img src="../../empresas/archivos/'.$PKEmpresa.'/img'.'/' . $imageName . '" class="img-thumbnail" /></center><input type="hidden" id="imagenSubir" name="imagenSubir" value="' . $imageName . '" /> ';

    if (isset($_POST["imagenSubir"])) {
        $imagenSubir = $_POST["imagenSubir"];
    } else {
        $imagenSubir = "";
    }
    if ($imagenSubir != "") {
        unlink("temp/" . $imagenSubir);
    }

}