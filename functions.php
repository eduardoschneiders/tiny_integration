<?php
  function sendRequest($url, $data = null, $optional_headers = null) {

    $host = explode('/', $url);
    $domain = end($host);

    $cache_file = "cache_files/{$domain}?{$data}";

    if(is_file($cache_file)){
      return json_decode(file_get_contents($cache_file));
    }

    $token = getenv('TOKEN');

    $params = array('http' => array(
      'method' => 'POST',
      'content' => "token=$token&formato=JSON&".$data
    ));

    if ($optional_headers !== null) {
      $params['http']['header'] = $optional_headers;
    }

    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);

    if (!$fp) {
      throw new Exception("Problema com $url, $php_errormsg");
    }

    $response = @stream_get_contents($fp);

    // pre($http_response_header);

    if ($response === false) {
      throw new Exception("Problema obtendo retorno de $url, $php_errormsg");
    }

    if(json_decode($response)->retorno->status_processamento == '3'){
      file_put_contents($cache_file, $response);
    }

    return json_decode($response);
  }

  function pre($data) {
    print("<pre>".print_r($data, true)."</pre>");
  }

  function compare($a, $b) {
    return ($a->item->nome < $b->item->nome) ? -1 : 1;
  }
?>