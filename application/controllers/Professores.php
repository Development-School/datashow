<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Professores extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin_model', 'admin');
  }

  public function index($offset=0)
  {
    $busca = null;
    $url_paginacao = base_url('professores');
    $get_total_results = $this->admin->getPagProfessor($busca);
    $total_resultados = $get_total_results['total'];
    $get_paginacao = paginacao($url_paginacao, $total_resultados, 10, $offset);
    $get_users = $this->admin->getPagProfessor($busca, $get_paginacao['inicio'], $get_paginacao['qtidade_re']);

    if ($get_users['dados'] == null) {
      setMensagem('inicio','Nenhum Resultado encontrado', true, 10000);
    }
    else{
      $dados['valores'] = $get_users['dados'];
      $dados['paginacao'] = $get_paginacao['paginacao'];
      $this->layout->view('professores_view', $dados);
    }
  }

  public function busca($busca=null, $offset=0)
  {
    if ($this->input->post('nome', true)) {
      $busca = $this->input->post('nome', true);
    }
    $url_paginacao = base_url('professores/busca/'.$busca);
    $get_total_results = $this->admin->getPagProfessor($busca);
    $total_resultados = $get_total_results['total'];
    $get_paginacao = paginacao($url_paginacao, $total_resultados, 10, $offset);
    $get_users = $this->admin->getPagProfessor($busca, $get_paginacao['inicio'], $get_paginacao['qtidade_re']);

    if ($get_users['dados'] == null) {
      setMensagem('inicio','Nenhum Resultado encontrado', true, 10000);
    }
    else{
      $dados['valores'] = $get_users['dados'];
      $dados['paginacao'] = $get_paginacao['paginacao'];
      $this->layout->view('professores_view', $dados);
    }
  }
}

/* End of file Professores.php */
/* Location: ./application/controllers/Professores.php */
