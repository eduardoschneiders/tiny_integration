<?php include 'functions.php';?>

<?php
  $id = '735217815';
  $data = "id=$id";
  $response = sendRequest('https://api.tiny.com.br/api2/pedido.obter.php', $data);
  pre(json_decode($response));

  $id = '735217191';
  $data = "id=$id";
  $response = sendRequest('https://api.tiny.com.br/api2/produto.obter.estrutura.php', $data);
  pre(json_decode($response));

  $id = '735217195';
  $data = "id=$id";
  $response = sendRequest('https://api.tiny.com.br/api2/produto.obter.estrutura.php', $data);
  pre(json_decode($response));
?>