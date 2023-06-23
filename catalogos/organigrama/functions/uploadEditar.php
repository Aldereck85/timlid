<?php

if (isset($_POST["image"])) {
    $data = $_POST["image"];

    $image_array_1 = explode(";", $data);

    $image_array_2 = explode(",", $image_array_1[1]);

    $data = base64_decode($image_array_2[1]);

    $imageName = time() . '.jpg';

    file_put_contents("temp/" . $imageName, $data);

    echo '<center><img src="temp/' . $imageName . '" class="img-thumbnail" /></center><input type="hidden" id="imagenSubirEditar" name="imagenSubirEditar" value="' . $imageName . '" /> ';

    if (isset($_POST["imagenSubirEditar"])) {
        $imagenSubir = $_POST["imagenSubirEditar"];
    } else {
        $imagenSubir = "";
    }
    if ($imagenSubir != "") {
        unlink("temp/" . $imagenSubir);
    }

}