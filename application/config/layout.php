<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Configurações da Library Layout
| -------------------------------------------------------------------
*/
// $config['getByModel'] = array('InfoSite_model', 'info');
$config['template']   = 'layouts/default.html';
$config['header']     = 'layouts/navbar.html';
$config['footer']     = 'layouts/footer.html';
$config['extencao']   = '.html';
$config['minificar']  = TRUE;

/*
| -------------------------------------------------------------------
|  Configurações Personalizadas
| -------------------------------------------------------------------
*/
$config['titulo'] = 'Datashow';
$config['css'] = '<link rel="stylesheet" type="text/css" href="'.base_url('assets/css/estilo.min.css').'">';
$config['js'] = '<script src="'.base_url('assets/js/scripts.min.js').'"></script>';

/* End of file template.php */
/* Location: ./application/config/template.php */