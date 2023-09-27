<?php include 'functions.php';?>

<?php
  $response = sendRequest('https://api.tiny.com.br/api2/info.php');
  pre(json_decode($response));

  $numero = '22';
  $data = "numero=$numero";
  $response = sendRequest('https://api.tiny.com.br/api2/pedidos.pesquisa.php', $data);
  pre(json_decode($response));

  $id = '698787824';
  $data = "id=$id";
  $response = sendRequest('https://api.tiny.com.br/api2/pedido.obter.php', $data);
  pre(json_decode($response));
?>