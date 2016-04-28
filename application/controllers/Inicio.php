<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

  public function index()
  {
    $this->layout->view('inicio_view');
  }

  public function sair()
  {
    $this->session->sess_destroy();
    redirect('home','refresh');
  }
}

/* End of file Inicio.php */
/* Location: ./application/controllers/Inicio.php */