<?php include 'functions.php';?>

<?php
  function foo() {
    echo('lalal');
  }

  function pre($variable) {
    print("<pre>".print_r($variable,true)."</pre>");
  }
?>

<p>Test Eduardo</p>

<?php
  echo("Hello world");
  pre(getenv('TOKEN'));

  $token = getenv('TOKEN');
  $numero = 'xxxxx';
  $data = "token=$token&numero=$numero&formato=JSON";

  $response = sendRequest('https://api.tiny.com.br/api2/pedidos.pesquisa.php', $data);
  pre($response);
?>