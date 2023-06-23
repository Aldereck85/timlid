<?php

if($_POST['gestionLogin'] == 1){
    session_start();
    $_SESSION['login_attempt'] = 1;
}