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

  public function entregar($id, $dados)
  {
    $this->db->where('id', $id);
    return $this->db->update('solicitacao', $dados);
  }

  public function solicitacao($dados)
  {
    return $this->db->insert('solicitacao', $dados);
  }

  public  function login($email, $senha)
  {
    $this->db->where('email',$email);
    $this->db->where('senha',$senha);
    $usuario = $this->db->get($this->tabela);
    return $usuario->first_row(); // RETORNA usuario
  }

  public function getTudo($cpf, $patrimonio)
  {
    $cpf = preg_replace('/[^0-9]/','',$cpf);
    $this->db->select('*');
    $this->db->from('professores');
    $this->db->where('cpf', $cpf);
    $retorno['professor'] = $this->db->get()->first_row();
    if ($retorno['professor'] == null) {
      return null;
    }
    else{
      $this->db->select('*');
      $this->db->from('datashows');
      $this->db->where('patrimonio',$patrimonio);
      $retorno['datashow'] = $this->db->get()->first_row();
      if ($retorno['datashow'] == null) {
        return null;
      }
      else{
        return  $retorno;
      }
    }
  }

  public function getPagSolicitacao($nomebusca=null,$inicio=NULL,$quantidade=NULL)
  {
    if ($inicio !== NULL) {
      $this->db->limit($quantidade, $inicio);
    }
    $this->db->select('a.id, a.horario, a.turma, a.status, a.id_patrimonio, a.id_professor, b.nome, b.email, b.cpf');
    $this->db->from('solicitacao as a');
    $this->db->join('professores as b', 'a.id_professor = b.id', 'inner');
    $this->db->like('horario', $nomebusca);
    $sql = $this->db->get();
    return array('inicio' => $inicio, 'total' => $sql->num_rows(), 'dados' => $sql->result() );
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