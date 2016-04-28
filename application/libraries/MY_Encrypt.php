<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Encrypt extends CI_Encrypt
{

  public function encode($string, $key = "", $url_safe = TRUE)
  {
    $ret = parent::encode($string, $key);
    if ($url_safe) {
      $ret = strtr($ret, array('+' => '.', '=' => '-', '/' => '~'));
    }
    return $ret;
  }

  public function decode($string, $key = "")
  {
    $string = strtr($string, array('.' => '+', '-' => '=', '~' => '/'));
    return parent::decode($string, $key);
  }

}

/* End of file MY_Encrypt.php */
/* Location: ./application/libraries/MY_Encrypt.php */
