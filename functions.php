<?php
  function sendRequest($url, $data, $optional_headers = null) {
    $params = array('http' => array(
      'method' => 'POST',
        'content' => $data
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

    if ($response === false) {
      throw new Exception("Problema obtendo retorno de $url, $php_errormsg");
    }

    return $response;
  }
?>