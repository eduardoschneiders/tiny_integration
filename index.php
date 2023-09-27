<?php include 'functions.php';?>

<?php
  $token = getenv('TOKEN');
  $numero = '22';
  $data = "token=$token&numero=$numero&formato=JSON";

  $response = sendRequest('https://api.tiny.com.br/api2/info.php', $data);
  pre($response);
?>