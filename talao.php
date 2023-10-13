<?php include 'header.php';?>

<?php
  $data = "id={$_GET['id']}";
  $response = api_request('https://api.tiny.com.br/api2/pedido.obter.php', $data);

  if ($response->retorno->status_processamento != 3){
    echo "Problema na API";
    pre($response);
    die();
  }

  $grouped_items = [];

  foreach ($response->retorno->pedido->itens as $item) {
    $cleaned_name = preg_replace('/ -\s+\d+\/\d+/', '', $item->item->descricao);

    if (!array_key_exists($cleaned_name, $grouped_items)){
      $grouped_items[$cleaned_name] = [];
    }

    array_push($grouped_items[$cleaned_name], $item);
  }
?>

<?php
  foreach($grouped_items as $name => $items){
    echo "<br><br><b>Product: {$name}</b><br>";
    echo "
      <table class=\"table table-striped\">
        <tr>
          <th>Item</th>
          <th>Quantidade</th>
        </tr>
    ";

    foreach($items as $item){
      $response = api_request('https://api.tiny.com.br/api2/produto.obter.estrutura.php', "id={$item->item->id_produto}");

      if ($response->retorno->status_processamento != '3'){
        continue;
      }

      $components = (array) $response->retorno->produto;


      uasort($components, 'compare');


      foreach($components as $component) {
        $component_total = $component->item->quantidade * $item->item->quantidade;

        echo "<tr>";
          echo "<td>{$component->item->nome}</td>";
          echo "<td>{$component_total}</td>";
        echo "</tr>";
      }
    }

    echo "</table>";
  }
?>
