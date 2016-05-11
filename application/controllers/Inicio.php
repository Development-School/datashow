<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin_model', 'admin');
    if (!$this->session->logado) {
      redirect('Home','refresh');
    }
  }

  public function solicitar()
  {
    $this->form_validation->set_rules('cpf', 'CPF', 'required|valid_cpf');
    $this->form_validation->set_rules('patrimonio', 'Patrimonio', 'required');

    if ($this->form_validation->run() == FALSE)
    {
      self::index();
    }
    else
    {
      $dados = $this->input->post(null, true);
      $dados = $this->admin->getTudo($dados['cpf'], $dados['patrimonio']);
      if ($dados != null) {
        $this->layout->view('solicitar_view', $dados);
      }
      else{ setMensagem('inicio','Dados não Encontrados!', true); }
    }
  }

  public function confirma()
  {
    $dados = $this->input->post(null, true);
    if ($dados) {
      $dados['horario'] = date("d/m/Y H:i:s");
      $dados['status'] = 'Emprestado';
      ( $this->admin->solicitacao($dados)) ?
        setMensagem('inicio','Datashow Alugado com Sucesso!'):
        setMensagem('inicio','Ocorreu um erro', true);
    }
    else{ setMensagem('inicio','Ocorreu um erro', true); }
  }

  public function sair()
  {
    $this->session->sess_destroy();
    redirect('home','refresh');
  }

  public function index($offset=0)
  {
    $busca = null;
    $url_paginacao = base_url('inicio');
    $get_total_results = $this->admin->getPagSolicitacao($busca);
    $total_resultados = $get_total_results['total'];
    $get_paginacao = paginacao($url_paginacao, $total_resultados, 5, $offset);
    $get_users = $this->admin->getPagSolicitacao($busca, $get_paginacao['inicio'], $get_paginacao['qtidade_re']);

    if ($get_users['dados'] == null) {
      setMensagem('inicio','Nenhum Resultado encontrado', true, 10000);
    }
    else{
      $dados['valores'] = $get_users['dados'];
      $dados['paginacao'] = $get_paginacao['paginacao'];
      $this->layout->view('inicio_view', $dados);
    }
  }

  public function busca($busca=null, $offset=0)
  {
    if ($this->input->post('horario', true)) {
      $busca = $this->input->post('horario', true);
    }
    $url_paginacao = base_url('inicio/busca/'.$busca);
    $get_total_results = $this->admin->getPagSolicitacao($busca);
    $total_resultados = $get_total_results['total'];
    $get_paginacao = paginacao($url_paginacao, $total_resultados, 5, $offset);
    $get_users = $this->admin->getPagSolicitacao($busca, $get_paginacao['inicio'], $get_paginacao['qtidade_re']);

    if ($get_users['dados'] == null) {
      setMensagem('inicio','Nenhum Resultado encontrado', true);
    }
    else{
      $dados['valores'] = $get_users['dados'];
      $dados['paginacao'] = $get_paginacao['paginacao'];
      $this->layout->view('inicio_view', $dados);
    }
  }

  public function entregar($id='')
  {
    $dados['status'] = 'Devolvido';
    ( $this->admin->entregar($id, $dados) ) ?
      setMensagem('inicio','Datashow Entregue com Sucesso'):
      setMensagem('inicio','Ocorreu um erro', true);
  }
}

/* End of file Inicio.php */
/* Location: ./application/controllers/Inicio.php */