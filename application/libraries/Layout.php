<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class layout
{
  protected $ci;

  /* configurações gerais */
  public $template  = 'layouts/default.html';
  public $header    = 'layouts/header.html';
  public $footer    = 'layouts/footer.html';
  public $mensagem  = 'layouts/mensagem.html';

  public $extencao   = '.html';
  public $minificar  = FALSE;
  public $getByModel = null;
  public $dadosView  = array();

  public function __construct($config = array())
  {
    $this->ci =& get_instance();
    if ( empty($config) ) {
      $this->ci->config->load('layout',true);
      $config = $this->ci->config->item('layout');
    }
    $this->initialize($config);
  }

  public function initialize($config = array())
  {
    $configClass = array('ci', 'template', 'header', 'footer', 'extencao', 'minificar', 'getByModel');
    foreach ($config as $key => $value) {
      in_array($key, $configClass, TRUE) ?
        $this->$key = $value :
        $this->dadosView[$key] = $value;
    }
    if ($this->getByModel) {
      $model = $this->getByModel[0];
      $alias = $this->getByModel[1];
      $this->ci->load->model($model, $alias);
      $this->dadosView[$alias] = $this->ci->{$alias}->get();
    }
  }

  public function view($view, $vars = array(), $return = FALSE)
  {
    $ext = pathinfo($view, PATHINFO_EXTENSION);
    if($ext === '' or $ext === 'view') $view = $view.$this->extencao;
    foreach ($this->dadosView as $key => $value) {
      $vars[$key] = (isset($vars[$key])) ? $vars[$key] : $value;
    }
    $vars['layout']['header'] = ($this->header) ? $this->ci->load->view($this->header, $vars, true) : '';
    $vars['layout']['footer'] = ($this->footer) ? $this->ci->load->view($this->footer, $vars, true) : '';
    $vars['layout']['content'] = $this->ci->load->view($view, $vars, true);
    $output = $this->ci->load->view($this->template, $vars, true);
    $output = ($this->minificar) ? $this->minify_html($output) : $output;
    return ($return) ? $output : $this->ci->output->set_output($output);
  }

  public function setMensagem($local, $mensagem, $erro = false, $tempo = 5000)
  {
    $dados['local'] = $local;
    $dados['mensagem'] = $mensagem;
    $dados['erro'] = $erro;
    $dados['tempo'] = $tempo;
    return $this->view($this->mensagem,$dados);
  }

  public function setFooter($view)
  {
    $ext = pathinfo($view, PATHINFO_EXTENSION);
    if($ext === '' or $ext === 'view') $view = ($view) ? $view.$this->extencao : '';
    return $this->footer = $view;
  }

  public function setHeader($view)
  {
    $ext = pathinfo($view, PATHINFO_EXTENSION);
    if($ext === '' or $ext === 'view') $view = ($view) ? $view.$this->extencao : '';
    return $this->header = $view;
  }

  // HTML Minifier
  public function minify_html($input) {
    if(trim($input) === "") return $input;
    // Remove extra white-space(s) between HTML attribute(s)
    $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
      return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
    }, str_replace("\r", "", $input));
    // Minify inline CSS declaration(s)
    if(strpos($input, ' style=') !== false) {
      $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
        return '<' . $matches[1] . ' style=' . $matches[2] . $this->minify_css($matches[3]) . $matches[2];
      }, $input);
    }
    if(strpos($input, '</style>') !== false) {
      $input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches) {
        return '<style' . $matches[1] .'>'. $this->minify_css($matches[2]) . '</style>';
      }, $input);
    }
    if(strpos($input, '</script>') !== false) {
      $input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches) {
        return '<script' . $matches[1] .'>'. $this->minify_js($matches[2]) . '</script>';
      }, $input);
    }
    return preg_replace(
      array(
        // t = text
        // o = tag open
        // c = tag close
        // Keep important white-space(s) after self-closing HTML tag(s)
        '#<(img|input)(>| .*?>)#s',
        // Remove a line break and two or more white-space(s) between tag(s)
        '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
        '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
        '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
        '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
        '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
        '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
        '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
        '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
        // Remove HTML comment(s) except IE comment(s)
        '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s',
        '/\s+/'
      ),
      array(
        '<$1$2</$1>',
        '$1$2$3',
        '$1$2$3',
        '$1$2$3$4$5',
        '$1$2$3$4$5$6$7',
        '$1$2$3',
        '<$1$2',
        '$1 ',
        '$1',
        '',
        ' '
      ),
    $input);
  }

  // CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
  public function minify_css($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
      array(
        // Remove comment(s)
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
        // Remove unused white-space(s)
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
        // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
        '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
        // Replace `:0 0 0 0` with `:0`
        '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
        // Replace `background-position:0` with `background-position:0 0`
        '#(background-position):0(?=[;\}])#si',
        // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
        '#(?<=[\s:,\-])0+\.(\d+)#s',
        // Minify string value
        '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
        '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
        // Minify HEX color code
        '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
        // Replace `(border|outline):none` with `(border|outline):0`
        '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
        // Remove empty selector(s)
        '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
      ),
      array(
        '$1',
        '$1$2$3$4$5$6$7',
        '$1',
        ':0',
        '$1:0 0',
        '.$1',
        '$1$3',
        '$1$2$4$5',
        '$1$2$3',
        '$1:0',
        '$1$2'
      ),
    $input);
  }

  // JavaScript Minifier
  public function minify_js($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
      array(
        // Remove comment(s)
        '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
        // Remove white-space(s) outside the string and regex
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
        // Remove the last semicolon
        '#;+\}#',
        // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
        '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
        // --ibid. From `foo['bar']` to `foo.bar`
        '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
      ),
      array(
        '$1',
        '$1$2',
        '}',
        '$1$3',
        '$1.$3'
      ),
    $input);
  }
}

/* End of file View.php */
/* Location: ./application/libraries/View.php */
