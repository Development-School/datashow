<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Form_validation Class
 * Extends Form_Validation library
 */
class MY_Form_validation extends CI_Form_validation
{
	public function __construct()
	{
		parent::__construct();
		/**
		 * Prefixos do form validate erro
		 */
		$this->_error_prefix = '<p class="help-block">';
		$this->_error_suffix = '</p>';
	}

	/**
	 *
	 * decimar_br
	 *
	 * Verifica se é decimal, mas com virgula no lugar de .
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function decimal_br($str)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('decimal_br', 'O campo %s não contem um valor decimal válido.');

		return (bool) preg_match('/^[\-+]?[0-9]+\,[0-9]+$/', $str);
	}

	public function valid_placa($str)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('valid_placa', 'A %s informada não é válida.');
		return (bool) preg_match('/^([A-Z][A-Z][A-Z])-([0-9][0-9][0-9][0-9])$/', $str);
	}

	/**
	 *
	 * valid_cpf
	 *
	 * Verifica CPF é válido
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function valid_cpf($cpf)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('valid_cpf', 'O %s informado não é válido.');
		$cpf = preg_replace('/[^0-9]/','',$cpf);
		if(strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf))
		{
		  return FALSE;
		}
		// 9 primeiros digitos do cpf
		$digit = substr($cpf, 0, 9);
		// calculo dos 2 digitos verificadores
		for($j=10; $j <= 11; $j++)
		{
		  $sum = 0;
		  for($i=0; $i< $j-1; $i++)
		  {
		      $sum += ($j-$i) * ((int) $digit[$i]);
		  }

		  $summod11 = $sum % 11;
		  $digit[$j-1] = $summod11 < 2 ? 0 : 11 - $summod11;
		}
		return $digit[9] == ((int)$cpf[9]) && $digit[10] == ((int)$cpf[10]);
	}

	/**
	 * valid_cep
	 *
	 * Verifica se CEP é válido
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function valid_cep($cep)
	{
		$CI =& get_instance();
		$CI->form_validation->set_message('valid_cep', 'O campo %s não contém um CEP válido.');

		$cep = str_replace('.', '', $cep);
		$cep = str_replace('-', '', $cep);

		$url = 'http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 0);

		$resultado = curl_exec($ch);
		curl_close($ch);

		if( ! $resultado)
		  $resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";

		$resultado = urldecode($resultado);
		$resultado = utf8_encode($resultado);
		parse_str( $resultado, $retorno);

		if($retorno['resultado'] == 1 || $retorno['resultado'] == 2)
		  return TRUE;
		else
		  return FALSE;
	}

	function cpf_unique($cpf)
	{
    $cpf = preg_replace('/[^0-9]/','',$cpf);
		$CI =& get_instance();
		$CI->form_validation->set_message('cpf_unique', 'O %s já está cadastrado no sistema.');
    $CI->db->select('*');
    $CI->db->from('professores');
    $CI->db->where('cpf', $cpf);
    $ret = $CI->db->get()->first_row();
    return !(bool) $ret;
	}
}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
