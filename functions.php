<?php
  function api_request($url, $data = null) {
    if($cache = fetch_cache($url, $data)) {
      return $cache;
    }

    $response = api_call($url, $data);

    if ($response->retorno->status_processamento == '1'){
      api_request($url, $data);
    }

    store_cache($url, $data, $response);
    return $response;
  }

  function fetch_cache($url, $data) {
    $host = explode('/', $url);
    $domain = end($host);

    $cache_file = "cache_files/{$domain}?{$data}";

    if(!is_expired($cache_file) && is_file($cache_file)){
      return json_decode(file_get_contents($cache_file));
    }
  }

  function store_cache($url, $data, $response) {
    $host = explode('/', $url);
    $domain = end($host);

    $cache_file = "cache_files/{$domain}?{$data}";

    file_put_contents($cache_file, json_encode($response));
  }

  function is_expired($filename) {
    if (!is_file($filename)){
      return true;
    }

    $diff = time() - filemtime($filename);

    return $diff >= (getenv('EXPIRE_CACHE') ? getenv('EXPIRE_CACHE') : 60);
  }

  function api_call($url, $data, $optional_headers = null) {
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

    return json_decode($response);
  }

  function pre($data) {
    print("<pre>".print_r($data, true)."</pre>");
  }

  function compare($a, $b) {
    return ($a->item->nome < $b->item->nome) ? -1 : 1;
  }
?>