<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{

  public $tabela;

  public function tabela()
  {
    echo $this->tabela;
  }

  public function getTabela($tabela, $array = false)
  {
    $retorno = $this->db->get($tabela)->result();
    if ($array == true) {
      $array = array();
      foreach ($retorno as $value) {
        $array += array($value->id => $value->descricao );
      }
      return $array;
    }
    else{
      return $retorno;
    }
  }

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */