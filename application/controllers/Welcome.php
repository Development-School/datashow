<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function fake($total='')
	{
		for ($i=0; $i < $total; $i++) {
			$object1['nome'] = 'Fulano '.$i;
			$object1['cpf'] = $i;
			$this->db->insert('professores', $object1);

			$object2['patrimonio'] = $i;
			$object2['descricao'] = 'Datashow 00'.$i;
			$this->db->insert('datashows', $object2);
		}
	}
}
