<?php include 'header.php';?>

<head>
  <title>Romaneio pedido <?= $_GET['id'] ?></title>
</head>

<?php
  $data = "id={$_GET['id']}";
  $response = api_request('https://api.tiny.com.br/api2/pedido.obter.php', $data);

  if ($response->retorno->status_processamento != 3){
    echo "Problema na API";
    pre($response);
    die();
  }
?>

<?php
  $products = [];
  $sizes = [];

  foreach ($response->retorno->pedido->itens as $item) {
    $cleaned_name = preg_replace('/ -\s+\d+\/\d+/', '', $item->item->descricao);
    preg_match('/\d{2}\/\d{2}$/', $item->item->descricao, $matches);
    if (count($matches) == 0){
      continue;
    }
    $number = $matches[0];
    array_push($sizes, $number);
    $products[$cleaned_name][$number] = (int)$item->item->quantidade;
  }

  $sizes = array_unique($sizes)
?>

<?php
  $MAX_BOX_COUNT = 24;
  $total_box_count = 0;
  $box_index = 0;

  foreach ($products as $product) {
    foreach ($product as $size => $count) {
      $total_box_count += $count;
    }
  }

  $total_box_count = ceil($total_box_count / $MAX_BOX_COUNT);

  while(count($products) > 0){

    echo "
      <table class=\"table table-bordered \" style=\"break-inside: avoid;\">
        <tr>
          <th>
            Pedido:
          </th>

          <td width=\"700px\">
            {$response->retorno->pedido->numero}
          </td>

          <th>
            Cliente:
          </th>

          <td>
            {$response->retorno->pedido->cliente->nome}
          </td>
        </tr>

        <tr>
          <td colspan=\"4\">
            <table class=\"table table-bordered\">
              <tr>
               " . referencies_header($sizes) . "
              </tr>
    ";
    $current_box_count = 0;

    while (count($products) > 0 && $current_box_count < $MAX_BOX_COUNT) {
      $product_name = array_key_first($products);
      $product = $products[$product_name];

      echo "
        <tr>
          <td>{$product_name}</td>
      ";


      $completed_product = true;

      foreach ($sizes as $size) {
        $available_slots = $MAX_BOX_COUNT - $current_box_count;

        if(array_key_exists($size, $product)){
          if ($product[$size] > $available_slots) {
            $left = $product[$size] - $available_slots;
            $current_count = $product[$size] - $left;
          } else {
            $left = 0;
            $current_count = $product[$size];
          }

          $products[$product_name][$size] = $left;
          $product[$size] = $left;

          if ($left > 0) {
            $completed_product = false;
          }

          $current_box_count += $current_count;
          echo "<td>{$current_count}</td>";
        } else {
          echo "<td>0</td>";
        }
      }


      if ($completed_product){
        unset($products[$product_name]);
      }

      echo "
        </tr>
      ";
    }

    echo "
            </table>
          </td>
        </tr>

        <tr>
          <th>Volume</th>
          <td>" . ++$box_index . "/{$total_box_count}</td>
          <th>Total de pares</th>
          <td>{$current_box_count}</td>
        </tr>
      </table>

      <hr class=\"my-5\">
    ";
  }
?>

<?php
  function referencies_header($sizes) {
    $text = "
      <th>
        Referencia:
      </th>";

    foreach($sizes as $size) {
      $text .= "
        <th>
          {$size}
        </th>
      ";
    }

    return $text;
  }
?>