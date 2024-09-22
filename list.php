<?php include 'header.php' ?>

<head>
  <title>Listagem de Pedidos</title>
</head>

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

<table class="table align-middle">
  <tr>
    <th>Número do Pedido</th>
    <th>Cliente</th>
    <th>Total de Pares</th>
    <th>Talões</th>
    <th>Romaneio</th>
  </tr>

  <?php
    foreach($response->retorno->pedidos as $order){
      echo "<tr>";
        echo "<td> {$order->pedido->numero} </td>";
        echo "<td>{$order->pedido->nome} </td>";
        echo "<td>10</td>";
        echo '<td><a href="talao.php?id=' . $order->pedido->id . '" class="btn btn-outline-success">Talões</a></td>';
        echo '<td><a href="romaneio.php?id=' . $order->pedido->id . '" class="btn btn-outline-primary">Romaneio</a></td>';
      echo "</tr>";
    }
  ?>
</table>

<nav class="my-4" aria-label="Page navigation example">
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