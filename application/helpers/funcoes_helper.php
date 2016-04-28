<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('setMensagem'))
{
  function setMensagem($local, $mensagem, $erro = false, $tempo = 5000)
  {
    $CI =& get_instance();
    return $CI->layout->setMensagem($local, $mensagem, $erro, $tempo);
  }
}

if ( ! function_exists('format_cpf'))
{
  function format_cpf($cpf)
  {
    return $cpf = preg_replace('/^([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2})$/', '$1.$2.$3-$4', $cpf);
  }
}

if ( ! function_exists('paginacao'))
{
  function paginacao($url_pagination,$total_resultados,$resultados_per_pagina=10, $offset=null)
  {
    $CI =& get_instance();
    $CI->load->library('pagination');
    $config['base_url'] = $url_pagination;

    $config['total_rows'] = $total_resultados;
    $config['per_page'] = $resultados_per_pagina;
    $config['full_tag_open'] = '<ul class="pagination pagination-lg">';
    $config['full_tag_close'] = '</ul>';

    $config['first_link'] = 'Primeiro';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';

    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $config['last_link'] = 'Último';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    $config['prev_link'] = '<i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;&nbsp;Anterior';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';

    $config['next_link'] = 'Proximo&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';

    $config['cur_tag_open'] = '<li class="active"><a href="">';
    $config['cur_tag_close'] = '</a></li>';

    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';

    $qtidade = $config['per_page'];
    $CI->pagination->initialize($config);

    $inicio = $offset != NULL ? $offset :  '0';
    return array(
      'qtidade_re' => $qtidade,
      'inicio' => $inicio,
      'paginacao' => $CI->pagination->create_links()
    );
  }
}

/* End of file funcoes_helper.php */
/* Location: ./application/helpers/funcoes_helper.php */
