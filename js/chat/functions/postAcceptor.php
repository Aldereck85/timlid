<?php
  /*******************************************************
   * Only these origins will be allowed to upload images *
   ******************************************************/
  $accepted_origins = array("http://localhost", "http://192.168.1.1", "http://127.0.0.1","http://erpghmedic.com.mx","http://timdesk.erpghmedic.com.mx");

  /*********************************************
   * Change this line to set the upload folder *
   *********************************************/
  $imageFolder = "images/";

  reset ($_FILES);
  $temp = current($_FILES);

  if($temp['size'] <= 4000000){ 
      if (is_uploaded_file($temp['tmp_name'])){
        if (isset($_SERVER['HTTP_ORIGIN'])) {
          // same-origin requests won't set an origin. If the origin is set, it must be valid.
          if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
          } else {
            header("HTTP/1.1 403 Origin Denied");
            return;
          }
        }

        /*
          If your script needs to receive cookies, set images_upload_credentials : true in
          the configuration and enable the following two headers.
        */
        // header('Access-Control-Allow-Credentials: true');
        // header('P3P: CP="There is no P3P policy."');

        // Sanitize input
        if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
            header("HTTP/1.1 400 Invalid file name.");
            return;
        }

        // Verify extension
        if (!in_array(strtolower($temp['type']), array("image/gif", "image/jpeg", "image/png"))) {
            header("HTTP/1.1 400 Invalid extension.");
            return;
        }

        // Accept upload if there was no origin, or if it is an accepted origin
        $filetowrite = $imageFolder . round(microtime()).'.'.pathinfo($temp['name'], PATHINFO_EXTENSION);
        move_uploaded_file($temp['tmp_name'], $filetowrite);
        $filetowrite = "functions/".$filetowrite;
        // Respond to the successful upload with JSON.
        // Use a location key to specify the path to the saved image resource.
        // { location : '/your/uploaded/image/file'}
        echo json_encode(array('location' => $filetowrite));
      } else {
        // Notify editor that the upload failed
        header("HTTP/1.1 500 Server Error");
      }
  }
  else{
      header("HTTP/1.1 600 Server Error");
  }
?>