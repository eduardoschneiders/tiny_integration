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

<?php
  $talao_index = 0;
  $total_talao = count($grouped_products);

  foreach($grouped_products as $product_name => $products){
    echo "
      <table class=\"table table-bordered\">
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
          <td colspan=\"3\">Observações</td>
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
?>