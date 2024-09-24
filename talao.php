<?php include 'header.php';?>

<head>
  <title>Talão pedido <?= $_GET['id'] ?></title>
</head>

<?php
  $data = "id={$_GET['id']}";
  $response = api_request('https://api.tiny.com.br/api2/pedido.obter.php', $data);

  if ($response->retorno->status_processamento != 3){
    echo "Problema na API";
    pre($response);
    die();
  }

  $grouped_products = [];

  foreach ($response->retorno->pedido->itens as $item) {
    $cleaned_name = preg_replace('/ -\s+\d+\/\d+/', '', $item->item->descricao);

    if (!array_key_exists($cleaned_name, $grouped_products)){
      $grouped_products[$cleaned_name] = [];
    }

    array_push($grouped_products[$cleaned_name], $item);
  }
?>

<h1>Talão pedido <?= $response->retorno->pedido->numero ?></h1>
<a href="javascript:history.back()" class="btn btn-outline-success">Voltar</a>
<a href="romaneio.php?id=<?= $_GET['id'] ?>" class="btn btn-outline-success">Romaneio</a>

<h7>Agrupamentos de produtos: <?= count($grouped_products) ?></h7>

<hr>

<?php
  $talao_index = 0;
  $total_talao = count($grouped_products);

  foreach($grouped_products as $product_name => $products){
    $sizes = [];

    foreach ($products as $product) {
      preg_match('/\d{2}\/\d{2}$/', $product->item->descricao, $matches);

      if (count($matches) == 0){
        continue;
      }

      $number = $matches[0];
      $sizes[$number] = (int)$product->item->quantidade;
    }

    echo "
      <table class=\"table table-bordered\" style=\"break-inside: avoid;\">
        <tr>
          <th>
            Referencia:
          </th>

          <td width=\"700px\">
            {$product_name}
          </td>

          <th>
            Talão:
          </th>

          <td>
            " . ++$talao_index . "/" . $total_talao . "
          </td>
        </tr>
        <tr>
          <th>
            Cliente:
          </th>

          <td width=\"700px\">
            {$response->retorno->pedido->cliente->nome}
          </td>

          <th>
            Número do pedido:
          </th>

          <td>
            {$response->retorno->pedido->numero}
          </td>
        </tr>

        <tr>
          <th>
            Data do Pedido:
          </th>

          <td width=\"700px\">
            {$response->retorno->pedido->data_pedido}
          </td>

          <th>
            Previsão:
          </th>

          <td>
            {$response->retorno->pedido->data_prevista}
          </td>
        </tr>

        <tr>
          <td colspan=\"4\">
            <table class=\"table table-bordered\">
              " . sizes($sizes) . "
            </table>
          </td>
        </tr>

        <tr>
          <td colspan=\"4\">
            <table class=\"table table-bordered\">
              <thead>
                <tr>
                  <th>Material </th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                " . components($products) . "
              </tbody>
            </table>
          </td>
        </tr>

        <tr>
          <th>Observações:</th>
          <td colspan=\"3\">{$response->retorno->pedido->obs}</td>
        </tr>
      </table>

      <hr class=\"my-5\">
    ";
  }
?>


<?php
function components($products = []){
  $grouped_components = [];

  foreach($products as $product){
    $response = api_request('https://api.tiny.com.br/api2/produto.obter.estrutura.php', "id={$product->item->id_produto}");

    if ($response->retorno->status_processamento != '3'){
      continue;
    }

    $components = (array) $response->retorno->produto;

    uasort($components, 'compare');

    foreach ($components as $component) {
      if (strpos((string)$component->item->nome, 'SOLA') !== false){
        continue;
      }

      if (!array_key_exists($component->item->nome, $grouped_components)){
        $grouped_components[$component->item->nome] = 0;
      }

      $grouped_components[$component->item->nome] += $component->item->quantidade * $product->item->quantidade;
    }
  }

  $text = "";
  foreach($grouped_components as $name => $total){
    $parsed_total = strpos($total, '.') !== false ? number_format($total, 4, '.', '') : $total;

    $text .= "
      <tr>
        <td>{$name}</td>
        <td>{$parsed_total}</td>
      </tr>
    ";
  }

  return $text;
}

function sizes($sizes) {
  $text = '';

  $text .= '<tr>';

  foreach($sizes as $size => $quantity) {
    $text .= "
      <th>{$size}</th>
    ";
  }

  $text .= "
    <th>Total</th>
  ";

  $text .= '</tr>';

  $text .= '<tr>';

  $total = 0;
  foreach($sizes as $size => $quantity) {
    $total += $quantity;
    $text .= "
      <td>{$quantity} pares</td>
    ";
  }

  $text .= "
    <td>{$total} pares</td>
  ";

  $text .= '</tr>';

  return $text;
}
?>