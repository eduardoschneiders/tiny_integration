<?php
  function sendRequest($url, $data = null, $optional_headers = null) {
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

    if ($response === false) {
      throw new Exception("Problema obtendo retorno de $url, $php_errormsg");
    }

    return $response;
  }

  function pre($data) {
    print("<pre>".print_r($data, true)."</pre>");
  }
?>