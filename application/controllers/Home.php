<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Admin_model', 'admin');
    $this->layout->setHeader('layouts/header');
  }

  public function index()
  {
    $this->layout->view('home_view');
  }
  public function autenticacao($value='')
  {
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('senha', 'Senha', 'required');
    if ($this->form_validation->run() == FALSE)
    {
      self::index();
    }
    else{
      $dados = $this->input->post(null,true);
      $usuario = $this->admin->login($dados['email'], $dados['senha']);
      if($usuario) {
        $dados = array(
          'id'=>$usuario->id,
          'nome'=> $usuario->nome,
          'logado'=>TRUE
        );//array com os dados do cookie
        $this->session->set_userdata($dados);//passando a array para o cookie
        redirect("inicio");
      }
      else {
        $data['erro'] = 'Email ou Senha Invalidos!';
        $this->layout->view('admin/login', $data);
      }
    }
  }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */