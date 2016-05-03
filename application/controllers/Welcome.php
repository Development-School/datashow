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
			$object1['nome'] = 'Beltrano';
			$object1['cpf'] = '05065871345';
			$object1['email'] = 'rodrigo@gmail.com';
			$this->db->insert('professores', $object1);

			$object2['patrimonio'] = '7898938795753';
			$object2['descricao'] = 'Datashow 00';
			$this->db->insert('datashows', $object2);
		}
	}
}
