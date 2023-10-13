<?php include 'header.php' ?>

<table class="table table-striped">
  <tr>
    <th>Numero</th>
    <th>Data Pedido</th>
    <th>Data Prevista</th>
    <th>Nome</th>
    <th>Valor</th>
    <th>Numero Ecommerce</th>
  </tr>

  <?php
    $page = $_GET['page'] ?? 1;
    $response = api_request('https://api.tiny.com.br/api2/pedidos.pesquisa.php', "pagina={$page}");

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

<nav aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?php echo $page - 1?>">Previous</a>
    </li>
    <li class="page-item me-2 <?= ($response->retorno->numero_paginas == $page) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?php echo $page + 1?>">Next</a>
    </li>
  </ul>
</nav>