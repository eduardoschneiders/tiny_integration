<?php include 'functions.php';?>

<?php
  $data = "id={$_GET['id']}";
  $response = sendRequest('https://api.tiny.com.br/api2/pedido.obter.php', $data);

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
      <table border=\"1\">
        <tr>
          <th>Item</th>
          <th>Quantidade</th>
        </tr>
    ";

    foreach($items as $item){
      $data = "id={$item->item->id_produto}";
      $response = sendRequest('https://api.tiny.com.br/api2/produto.obter.estrutura.php', $data);

      foreach($response->retorno->produto as $component) {
        $total = $component->item->quantidade * $item->item->quantidade;

        echo "<tr>";
          echo "<td>{$component->item->nome}</td>";
          echo "<td>{$component->item->quantidade} * {$item->item->quantidade} = {$total}</td>";
        echo "</tr>";
      }
    }

    echo "</table>";
  }
?>

<style>
  th {
    text-align: left;
  }
</style>