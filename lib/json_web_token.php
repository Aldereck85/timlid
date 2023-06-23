<?php
require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;

class Auth
{   
    private static $secret_key = 'TByNp[~H23`zL7gIT[5q{E|L,<.[?-z_4O{)v|-|DUw89zbdpQ2#&{OQoec>Czi';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public static function SignIn($data)
    {       
        date_default_timezone_set('America/Mexico_City');
        $time = time();

        $token = array(
            'exp' => $time + (60*60*8), //duracion del token 1 hora (60*60)
            'aud' => self::Aud(),
            'data' => $data
        );

        return JWT::encode($token, self::$secret_key);
    }

    public static function decode($token)
    {
        $decoded = JWT::decode($token, self::$secret_key, array('HS256'));

        return $decoded;
    }

    public static function Check($token)
    {

        if(empty($token))
        {
            return false;
            //throw new Exception("Invalid token supplied.");
        }

        try{
            $decode = JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            );
        }catch(\Firebase\JWT\ExpiredException $e){
            return false;
             //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }catch (DomainException $de){
            //echo $de->getMessage();
            return false;
        } catch (Exception $e){
            //echo $e->getMessage();
            return false;
        }

        if($decode->aud !== self::Aud())
        {
            //throw new Exception("Invalid user logged in.");
            return false;
        }

        return true;
    }

    public static function GetData($token)
    {
        return JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
    }

    private static function Aud()
    {
        $aud = '4856932';
        /*
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        //$aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();*/

        return sha1($aud);
    }
}