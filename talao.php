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

<table class="table align-middle">
  <tr>
    <th>Número do Pedido</th>
    <th>Cliente</th>
    <th>Data do Pedido</th>
    <th>Previsão</th>
  </tr>
  <tr>
    <td><?= $response->retorno->pedido->numero ?></td>
    <td><?= $response->retorno->pedido->cliente->nome ?></td>
    <td><?= $response->retorno->pedido->data_pedido ?></td>
    <td><?= $response->retorno->pedido->data_prevista ?></td>
  </tr>
</table>


<?php
  foreach($grouped_products as $product_name => $products){
    // pre($products);
    echo "
      <table class=\"table table-bordered\">
        <tr>
          <th>
            Referenca:
          </th>

          <td width=\"700px\">
            {$product_name}
          </td>

          <th>
            Talão
          </th>

          <td>
            1/2
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
    $text .= "
      <tr>
        <td>{$name}</td>
        <td>{$total}</td>
      </tr>
    ";
  }

  return $text;
}
?>