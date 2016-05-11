<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Professores extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin_model', 'admin');
    $this->load->model('Professor_model', 'professor');
    if (!$this->session->logado) {
      redirect('Home','refresh');
    }
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
  public function cadastrar()
  {
    $this->layout->view('cadastrar_professores_view');

  }

  public function receber()
  {
    $this->form_validation->set_rules('nome', 'NOME', 'required');
    $this->form_validation->set_rules('cpf', 'CPF', 'required|valid_cpf|cpf_unique');
    $this->form_validation->set_rules('email', 'EMAIL', 'required');

    if ($this->form_validation->run() == FALSE) {
      self::cadastrar();
    } else {
      $dados = $this->input->post(null,true);
      ( $this->professor->add($dados) ) ?
        setMensagem('Inicio','Cadastro Realizado com sucesso'):
        setMensagem('Inicio','Ocorreu um erro no Cadastrar', true);
    }
  }
}

/* End of file Professores.php */
/* Location: ./application/controllers/Professores.php */
