<?php
  $filename = $_GET['filename'];
  $UUID = $_GET['uid'];
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "http://devfactura.in/api/v3/cfdi33/".$UUID."/xml");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "F-PLUGIN: " . '9d4095c8f7ed5785cb14c0e3b033eeb8252416ed',
      "F-Api-Key: ".'JDJ5JDEwJElhR0lIbld1YkVxZ0FrdXo0ckhyL093cHJUZTB3bDE2TEhMd1RzVTlUMUdPLjVDMnRZaTRx',
      "F-Secret-Key: " .'JDJ5JDEwJHg5VzVGZ0Y4QTltMVN4SllHVFh0Wi40MldNbGowRWVmNEVTMmtxVVN1bVNmbnc0Z3dURkJh'
  ));

  $response = curl_exec($ch);
  curl_close($ch);

  header('Content-disposition: inline; filename="' . $filename . '.xml"');
  header('Content-type: "text/xml"; charset="utf8"');
  echo $response;

?>
