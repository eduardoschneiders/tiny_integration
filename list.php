<?php include 'header.php' ?>

<head>
  <title>Listagem de Pedidos</title>
</head>

<?php
  $page = $_GET['page'] ?? 1;
  $number = $_GET['number'] ?? null;
  $client_name = $_GET['client_name'] ?? null;
  $situacao = $_GET['situacao'] ?? null;

  $params = "pagina={$page}";
  $params .= "&sort=DESC";
  $params .= $number ? ('&numero=' . $number) : '';
  $params .= $client_name ? ('&cliente=' . $client_name) : '';
  $params .= $situacao ? ('&situacao=' . $situacao) : '';

  $response = api_request('https://api.tiny.com.br/api2/pedidos.pesquisa.php', $params);

  if ($response->retorno->status_processamento != 3){
    if ($response->retorno->codigo_erro != 20) { # A consulta não retornou registros
      echo "Problema na API";
      pre($response);
      die();
    }
  }
?>

<nav class="my-4" aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?php echo $page - 1?>">Anterior</a>
    </li>

    <li class="page-item me-2 <?= ($response->retorno->numero_paginas == $page) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?php echo $page + 1?>">Próximo</a>
    </li>

    <li class="page-item">
      <form action="list.php" method="get">
        <div class="row g-3 align-items-center">
          <div class="col-auto">
            <label for="number" class="col-form-label">Numero</label>
          </div>
          <div class="col-auto">
            <input type="text" id="number" name="number" class="form-control" value="<?= $number ?>" style="width: 80px" >
          </div>

          <div class="col-auto">
            <label for="number" class="col-form-label">Nome Cliente</label>
          </div>
          <div class="col-auto">
            <input type="text" id="client_name" name="client_name" class="form-control" value="<?= $client_name ?>">
          </div>

          <div class="col-auto">
            <label for="number" class="col-form-label">Status</label>
          </div>
          <div class="col-auto">
            <select name="situacao" id="situacao" class="form-control" value="<?= 'parrot' ?>">
              <option value="">Todos</option>
              <option value="aberto" <?= $situacao == 'aberto' ? 'selected' : ''?>>Aberto</option>
              <option value="aprovado" <?= $situacao == 'aprovado' ? 'selected' : ''?>>Aprovado</option>
              <option value="preparando_envio" <?= $situacao == 'preparando_envio' ? 'selected' : ''?>>Preparando envio</option>
              <option value="faturado" <?= $situacao == 'faturado' ? 'selected' : ''?>>Faturado (atendido)</option>
              <option value="pronto_envio" <?= $situacao == 'pronto_envio' ? 'selected' : ''?>>Pronto para envio</option>
              <option value="enviado" <?= $situacao == 'enviado' ? 'selected' : ''?>>Enviado</option>
              <option value="entregue" <?= $situacao == 'entregue' ? 'selected' : ''?>>Entregue</option>
              <option value="nao_entregue" <?= $situacao == 'nao_entregue' ? 'selected' : ''?>>Não Entregue</option>
              <option value="cancelado" <?= $situacao == 'cancelado' ? 'selected' : ''?>>Cancelado</option>
            </select>
          </div>

          <div class="col-auto">
            <span class="form-text">
              <button type="submit" class="btn btn-primary">Search</button>
            </span>
          </div>
        </div>
      </form>
    </li>

    <li class="page-item me-2 <?= (!$number && !$client_name && !$situacao) ? 'disabled' : '' ?>">
      <a class="page-link" href="list.php">Clear</a>
    </li>
  </ul>
</nav>

<table class="table align-middle">
  <tr>
    <th>Pedido</th>
    <th>Cliente</th>
    <th>Status</th>
    <th></th>
    <th></th>
  </tr>

  <?php
    foreach(($response->retorno->pedidos ?? []) as $order){
      echo "<tr>";
        echo "<td> {$order->pedido->numero} </td>";
        echo "<td>{$order->pedido->nome} </td>";
        echo "<td>{$order->pedido->situacao} </td>";
        echo '<td><a href="talao.php?id=' . $order->pedido->id . '" class="btn btn-outline-success">Talões</a></td>';
        echo '<td><a href="romaneio.php?id=' . $order->pedido->id . '" class="btn btn-outline-primary">Romaneio</a></td>';
      echo "</tr>";
    }
  ?>
</table>

<nav class="my-4" aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?php echo $page - 1?>">Anterior</a>
    </li>

    <li class="page-item me-2 <?= ($response->retorno->numero_paginas == $page) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?php echo $page + 1?>">Próximo</a>
    </li>

    <li class="page-item">
      <form action="list.php" method="get">
        <div class="row g-3 align-items-center">
          <div class="col-auto">
            <label for="number" class="col-form-label">Numero</label>
          </div>
          <div class="col-auto">
            <input type="text" id="number" name="number" class="form-control" value="<?= $number ?>" style="width: 80px" >
          </div>

          <div class="col-auto">
            <label for="number" class="col-form-label">Nome Cliente</label>
          </div>
          <div class="col-auto">
            <input type="text" id="client_name" name="client_name" class="form-control" value="<?= $client_name ?>">
          </div>

          <div class="col-auto">
            <label for="number" class="col-form-label">Status</label>
          </div>
          <div class="col-auto">
            <select name="situacao" id="situacao" class="form-control" value="<?= 'parrot' ?>">
              <option value="">Todos</option>
              <option value="aberto" <?= $situacao == 'aberto' ? 'selected' : ''?>>Aberto</option>
              <option value="aprovado" <?= $situacao == 'aprovado' ? 'selected' : ''?>>Aprovado</option>
              <option value="preparando_envio" <?= $situacao == 'preparando_envio' ? 'selected' : ''?>>Preparando envio</option>
              <option value="faturado" <?= $situacao == 'faturado' ? 'selected' : ''?>>Faturado (atendido)</option>
              <option value="pronto_envio" <?= $situacao == 'pronto_envio' ? 'selected' : ''?>>Pronto para envio</option>
              <option value="enviado" <?= $situacao == 'enviado' ? 'selected' : ''?>>Enviado</option>
              <option value="entregue" <?= $situacao == 'entregue' ? 'selected' : ''?>>Entregue</option>
              <option value="nao_entregue" <?= $situacao == 'nao_entregue' ? 'selected' : ''?>>Não Entregue</option>
              <option value="cancelado" <?= $situacao == 'cancelado' ? 'selected' : ''?>>Cancelado</option>
            </select>
          </div>

          <div class="col-auto">
            <span class="form-text">
              <button type="submit" class="btn btn-primary">Search</button>
            </span>
          </div>
        </div>
      </form>
    </li>

    <li class="page-item me-2 <?= (!$number && !$client_name && !$situacao) ? 'disabled' : '' ?>">
      <a class="page-link" href="list.php">Clear</a>
    </li>
  </ul>
</nav>