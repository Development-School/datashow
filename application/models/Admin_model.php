<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends MY_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->tabela = "admin";
    $this->tabela2 = "veiculo";
  }

  public  function login($email, $senha)
  {
    $this->db->where('email',$email);
    $this->db->where('senha',$senha);
    $usuario = $this->db->get($this->tabela);
    return $usuario->first_row(); // RETORNA usuario
  }

  public function totais($tipo)
  {
    if ($tipo != 'usuarios' && $tipo != 'veiculo' ) {
      return false;
    }
    else{
      return $this->db->count_all($tipo);
    }
  }

  public function getTudo($id)
  {
    $this->db->select('*');
    $this->db->from('usuarios');
    $this->db->where('usuarios.id',$id);
    $retorno['usuario'] = $this->db->get()->first_row();

    $this->db->select('*');
    $this->db->from('veiculo');
    $this->db->where('veiculo.id_proprietario',$id);
    $retorno['veiculos'] = $this->db->get()->result();
    return  $retorno;
  }

  public function apagaTudo($id)
  {
    $this->db->where('id_proprietario', $id);
    $this->db->delete('veiculo');
    $this->db->where('id', $id);
    $this->db->delete('usuarios');
    return  $this->db->error();
  }

  public function apagaVeiculo($id)
  {
    $this->db->where('id', $id);
    $this->db->delete('veiculo');
    return  $this->db->error();
  }

  public function getAll_usuarios($tipo)
  {
    $this->db->select('*');
    $this->db->from('usuarios');
    $this->db->join('tipo_usuarios', 'tipo_usuarios.id = usuarios.id_tipo_usuario');
    $this->db->where('tipo_usuarios.descricao',$tipo);
    return $this->db->count_all_results();
  }

  public function getAll_veiculos($tipo)
  {
    $this->db->select('*');
    $this->db->from('veiculo');
    $this->db->join('tipo_veiculo', 'tipo_veiculo.id = veiculo.id_tipo_veiculo');
    $this->db->where('tipo_veiculo.descricao',$tipo);
    return $this->db->count_all_results();
  }

  public function getVeiculos()
  {
    $this->db->select('*');
    $this->db->from('veiculo');
    return $this->db->get()->result();
  }

  public function getPagDatashow($nomebusca=null,$inicio=NULL,$quantidade=NULL)
  {
    if ($inicio !== NULL) {
      $this->db->limit($quantidade, $inicio);
    }
    $this->db->select('*');
    $this->db->from('datashows');
    $this->db->like('patrimonio', $nomebusca);
    $sql = $this->db->get();
    return array('inicio' => $inicio, 'total' => $sql->num_rows(), 'dados' => $sql->result() );
  }

  public function getPagProfessor($nomebusca=null,$inicio=NULL,$quantidade=NULL)
  {
    if ($inicio !== NULL) {
      $this->db->limit($quantidade, $inicio);
    }
    $this->db->select('*');
    $this->db->from('professores');
    $this->db->like('nome', $nomebusca);
    $sql = $this->db->get();
    return array('inicio' => $inicio, 'total' => $sql->num_rows(), 'dados' => $sql->result() );
  }
}

/* End of file Admin_model.php */
/* Location: ./application/models/Admin_model.php */