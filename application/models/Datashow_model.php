<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datashow_model extends CI_Model {
public function add($dados)
  {
    return $this->db->insert('datashows', $dados);
  }

  public function update($id,$dados)
  {
    $this->db->where('id', $id);
    return $this->db->update('datashows', $dados);
  }
  public function delete($i,$dados)
  {
    $this->db->where('id', $id);
    return $this->db->delete('datashows', $dados);
  }



}

/* End of file Datashow_model.php */
/* Location: ./application/models/Datashow_model.php */