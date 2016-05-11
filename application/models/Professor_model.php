<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Professor_model extends CI_Model {

  public function add($dados)
  {
    return $this->db->insert('professores', $dados);
  }

  public function update($id,$dados)
  {
    $this->db->where('id', $id);
    return $this->db->update('professores', $dados);
  }
  public function delete($i,$dados)
  {
    $this->db->where('id', $id);
    return $this->db->delete('professores', $dados);
  }



}

/* End of file Professor_model.php */
/* Location: ./application/models/Professor_model.php */