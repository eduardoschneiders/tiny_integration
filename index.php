<?php include 'header.php' ?>

<?php
  $page = $_GET['page'] ?? 1;
  $number = $_GET['number'] ?? null;
  $client_name = $_GET['client_name'] ?? null;

  $params = "pagina={$page}";
  $params .= $number ? ('&numero=' . $number) : '';
  $params .= $client_name ? ('&cliente=' . $client_name) : '';

  $response = api_request('https://api.tiny.com.br/api2/pedidos.pesquisa.php', $params);

  if ($response->retorno->status_processamento != 3){
    echo "Problema na API";
    pre($response);
    die();
    }
?>

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
    foreach($response->retorno->pedidos as $order){
      echo "<tr>";
        echo "<td><a href=\"talao.php?id={$order->pedido->id}\"> {$order->pedido->numero} </a></td>";
        echo "<td><a href=\"talao.php?id={$order->pedido->id}\"> {$order->pedido->data_pedido} </a></td>";
        echo "<td><a href=\"talao.php?id={$order->pedido->id}\"> {$order->pedido->data_prevista} </a></td>";
        echo "<td><a href=\"talao.php?id={$order->pedido->id}\"> {$order->pedido->nome} </a></td>";
        echo "<td><a href=\"talao.php?id={$order->pedido->id}\"> {$order->pedido->valor} </a></td>";
        echo "<td><a href=\"talao.php?id={$order->pedido->id}\"> {$order->pedido->numero_ecommerce} </a></td>";
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

    <li class="page-item">
      <form action="index.php" method="get">
        <div class="row g-3 align-items-center">
          <div class="col-auto">
            <label for="number" class="col-form-label">Number</label>
          </div>
          <div class="col-auto">
            <input type="text" id="number" name="number" class="form-control" value="<?= $number ?>">
          </div>

          <div class="col-auto">
            <label for="number" class="col-form-label">Nome Cliente</label>
          </div>
          <div class="col-auto">
            <input type="text" id="client_name" name="client_name" class="form-control" value="<?= $client_name ?>">
          </div>

          <div class="col-auto">
            <span class="form-text">
              <button type="submit" class="btn btn-primary">Search</button>
            </span>
          </div>
        </div>
      </form>
    </li>

    <li class="page-item me-2 <?= (!$number && !$client_name) ? 'disabled' : '' ?>">
      <a class="page-link" href="index.php">Clear Search</a>
    </li>
  </ul>
</nav>