<?php
  if (
    !isset($_SERVER['HTTP_AUTHORIZATION']) ||
    base64_decode(str_replace('Basic ', '', $_SERVER['HTTP_AUTHORIZATION'])) != 'user:password'
  ) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');

    exit;
  }
?>

<?php include 'functions.php';?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Listagem de Pedidos</title> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  </head>

  <body>
    <div class="container mt-4">