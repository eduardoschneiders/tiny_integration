<?php include 'functions.php';?>

<table border="1">
  <tr>
    <th>Numero</th>
    <th>Data_Pedido</th>
    <th>Data_Prevista</th>
    <th>Nome</th>
    <th>Valor</th>
    <th>Numero_Ecommerce</th>
  </tr>

  <?php
    $response = sendRequest('https://api.tiny.com.br/api2/pedidos.pesquisa.php');

    foreach($response->retorno->pedidos as $order){
      echo "<tr>";
        echo "<td><a href=\"order.php?id={$order->pedido->id}\"> {$order->pedido->numero} </a></td>";
        echo "<td><a href=\"order.php?id={$order->pedido->id}\"> {$order->pedido->data_pedido} </a></td>";
        echo "<td><a href=\"order.php?id={$order->pedido->id}\"> {$order->pedido->data_prevista} </a></td>";
        echo "<td><a href=\"order.php?id={$order->pedido->id}\"> {$order->pedido->nome} </a></td>";
        echo "<td><a href=\"order.php?id={$order->pedido->id}\"> {$order->pedido->valor} </a></td>";
        echo "<td><a href=\"order.php?id={$order->pedido->id}\"> {$order->pedido->numero_ecommerce} </a></td>";
      echo "</tr>";
    }
  ?>
</table>

<style>
  th {
    text-align: left;
  }
</style>