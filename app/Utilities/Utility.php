<?php

namespace App\Utilities;
use Illuminate\Support\Str;
use Log;

class Utility 
{

  public static function hashString($pass){
    return md5(md5(sha1(sha1(md5($pass)))));
  }

  public static function hashedStringMatch($plain, $hash) {
    return $hash === static::hashString($plain);
  }

  public static function getTP($p) {
    return Utility::pauline($p);
  }

  public static function generatePassword(array $data)
  {
    if(!isset($data['password']))
    {
      $defaultPassword = Str::random(10);
      $userStr = "(email: {$data['email']}, phone: {$data['phone']})";
      Log::warning("Assigned '$defaultPassword' as default password to $userStr");
      return array('password' => $defaultPassword, 'temp_password' => Utility::paul($defaultPassword), 'plain_password' => $defaultPassword);
    }
    return false;
  }

  public static function paul($data)
  {
    $first_key = base64_decode(env("FIRSTKEY", ""));
    $second_key = base64_decode(env("SECONDKEY", ""));
    $method = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $first_encrypted = openssl_encrypt($data,$method,$first_key, OPENSSL_RAW_DATA ,$iv);
    $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
    $output = base64_encode($iv.$second_encrypted.$first_encrypted);
    return $output;
  }
  public static function pauline($input)
  {
    $first_key = base64_decode(env("FIRSTKEY", ""));
    $second_key = base64_decode(env("SECONDKEY", ""));
    $mix = base64_decode($input);
    $method = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = substr($mix,0,$iv_length);
    $second_encrypted = substr($mix,$iv_length,64);
    $first_encrypted = substr($mix,$iv_length+64);
    $data = openssl_decrypt($first_encrypted,$method,$first_key,OPENSSL_RAW_DATA,$iv);
    $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
    if (hash_equals($second_encrypted,$second_encrypted_new))
      return $data;
    return false;
  }

  public static function time_tomysql($a){
    $b=strftime('%Y-%m-%d %H:%M:%S',$a);
    //$b=strftime('%Y-%m-%d %I:%M:%S',$a);
    return $b;
  }

  public static function remove_from_array($haystack,$needle,$criteria){
    $d=array_intersect($haystack,!is_array($needle) ? array($needle) : $needle);
    $e=array_diff($haystack,$d);
    return array('new_array'=>$e,'removed_item'=>$d);
  }

  public static function remove_special_chars($names){
    $names=str_replace("'","",$names);
    $names=str_replace("("," ",$names);
    $names=str_replace(")"," ",$names);
    $names=str_replace(",","",$names);
    $names=str_replace("/","",$names);
    $names=str_replace("$","",$names);
    $names=str_replace("%","",$names);
    $names=str_replace(".","",$names);
    $names=str_replace("!","",$names);
    $names=str_replace("`","",$names);
    $names=str_replace("&","AND",$names);
    $names=str_replace('"',"",$names);
    $names=str_replace("?","",$names);
    $names=str_replace(">","",$names);
    $names=str_replace("<","",$names);
    $names=str_replace("=","",$names);
    $names=str_replace("|","",$names);
    $names=str_replace("#","",$names);
    return $names;
  }

  public static function is_time_tomysql($date){
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
      return true;
    } else {
      return false;
    }
  }

  public static function contains($needle, $haystack)
  {
    return strpos($haystack, $needle) !== false;
  }

  public static function resemble($in, $text){
    if(strnatcasecmp($in,$text)==0){
      return true;
    }else{
      return false;
    }
  }

  public static function hours_to_date($hours){
    $secs=$hours*60*60;
    $tim=time()+$secs;
    $date = strftime('%Y-%m-%d %I:%M:%S', $tim);
    return $date;
  }

  public static function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters2 = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public static function generateRandomCharUpper($length = 10) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public static function generateRandomCharUpperLower($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public static function generateRandomNumber($length = 12) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public static function ping($host, $port, $timeout) {
    $tB = microtime(true);
    $fP = @fSockOpen($host, $port, $timeout);
    if (!$fP) {
      return false;
    }
    $tA = microtime(true);
    return round((($tA - $tB) * 1000), 0)." ms";
  }

  public static function url_is_reachable($host, $port=80, $timeout=20)
  {
    $tB = microtime(true);
    $fP = @fSockOpen($host, $port, $timeout);
    if (!$fP || is_null($host) || empty($host)) {
      $opts = array(
        'http'=>array(
          'method'=>"GET",
          'header'=>"Accept-language: en\r\n"
        )
      );
      $context = stream_context_create($opts);
      $file = @file_get_contents($host, false, $context);
      if(!$file){
        return false;
      }
    }
    $tA = microtime(true);
    return round((($tA - $tB) * 1000), 0)." ms";
  }

  public static function respondOK($a)
  {

    if (is_callable('fastcgi_finish_request')) {
      session_write_close();
      if(!empty($a)){
        echo $a;
      }
      fastcgi_finish_request();
    }
    ignore_user_abort(true);
    ob_start();
    if(!empty($a)){
      echo $a;
    }
    $serverProtocole = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL', FILTER_SANITIZE_STRING);
    header($serverProtocole.' 200 OK');
    header('Content-Encoding: none');
    header('Content-Length: '.ob_get_length());
    header('Connection: close');
    ob_end_flush();
    ob_flush();
    flush();
  }












}
