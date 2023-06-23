<?php

	function encryptor($action, $string) {
        $output = false;

        $encrypt_method = "AES-256-CBC";

        $secret_key = 'f(>FZA18Nx$m5$)8jT*wG2_-u8V#C0aI[jalB?k5z439itWgp;p<RIIASB[OeoC';
        $secret_iv = 'Eix{6C]T.xdc;Z!rZxgYsw[~IpR@/*X=3O5QKpGNv[2b)qt&ETt_6<R=@e}]qu8';

        // hash
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    function guardarSession($conn, $users_id){
		date_default_timezone_set('America/Mexico_City');
		$fechaIngreso = date('Y-m-d H:i:s');
		$stmt = $conn->prepare('DELETE FROM sessions WHERE sessions_userid = :sessions_userid');
		$stmt->execute(array(':sessions_userid' => $users_id));

		$serial_sesion_unico = encryptor("encrypt", bin2hex(random_bytes(32)));
        $token_sesion_unico = encryptor("encrypt", bin2hex(random_bytes(32)));

		$_SESSION['serial_sesion_unico'] = $serial_sesion_unico;
		$_SESSION['token_sesion_unico'] = $token_sesion_unico;
		$_SESSION['fecha_sesion_unico'] = $fechaIngreso;

		$stmt = $conn->prepare('INSERT INTO sessions (sessions_userid, sessions_token, sessions_serial, sessions_date) VALUES (:user_id, :token, :serial1, :date1) ');
		$stmt->execute(array(':user_id' => $users_id, ':token' => $token_sesion_unico, ':serial1' => $serial_sesion_unico, 'date1' => $fechaIngreso ));

	}
   
	function checkLoginState($conn){
		
		if(!isset($_SESSION)){
			session_start();
		}

		if(isset($_SESSION['PKUsuario']) && isset($_SESSION['token_sesion_unico']) && isset($_SESSION['serial_sesion_unico'])){

			$query = "SELECT sessions_userid, sessions_token, sessions_serial FROM sessions WHERE sessions_userid = :userid AND sessions_token = :token AND sessions_serial = :serial1";
			$stmt = $conn->prepare($query);
			$stmt->execute(array('userid' => $_SESSION['PKUsuario'], ':token' => $_SESSION['token_sesion_unico'],':serial1' => $_SESSION['serial_sesion_unico']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			$sesion_token = encryptor("decrypt", $_SESSION['token_sesion_unico']);
			$sesion_serial = encryptor("decrypt", $_SESSION['serial_sesion_unico']);

			$bd_token = encryptor("decrypt", $row['sessions_token']);
			$bd_serial = encryptor("decrypt", $row['sessions_serial']);

			if($row['sessions_userid'] > 0)
			{
				if($row['sessions_userid'] == $_SESSION['PKUsuario'] &&
				   $bd_token == $sesion_token &&
				   $bd_serial == $sesion_serial )
				{
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}

		}
		else{
			return false;
		}
	}