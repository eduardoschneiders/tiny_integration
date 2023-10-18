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
  $sizes = array('36/37', '38/39', '40/41', '42/43');

  $products = array(
    'Chinelo Slide XN Bike Indoor - Branco' => array(
      '36/37' => 1,
      '38/39' => 5,
      '40/41' => 22,
      '42/43' => 1,
    ),
    'Chinelo Slide XN Bike Indoor - Preto' => array(
      '36/37' => 1,
      '38/39' => 3,
      '40/41' => 1,
      '42/43' => 4,
    )
  );
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
    // echo 'building box: ' . ++$box_index . "/{$total_box_count}";


    echo build_box($products, $sizes, ++$box_index, $total_box_count, $MAX_BOX_COUNT);



    // echo "<p>Total of the Box: {$current_box_count}</p>";
  }
?>

<?php
  function build_box($products, $sizes, $box_index, $total_box_count, $MAX_BOX_COUNT) {
    $current_box_count = 0;

    return "
      <table class=\"table table-bordered\">
        <tr>
          <th>
            Pedido:
          </th>

          <td width=\"700px\">
            125
          </td>

          <th>
            Cliente:
          </th>

          <td>
            ASDF
          </td>
        </tr>

        <tr>
          <td colspan=\"4\">
            <table class=\"table table-bordered\">
              <tr>
               " . referencies_header($sizes) . "
              </tr>
               " . build_rows($products, $sizes, $current_box_count, $MAX_BOX_COUNT) . "
            </table>
          </td>
        </tr>

        <tr>
          <th>
            Volume:
          </th>

          <td width=\"700px\">
            " . $box_index . "/{$total_box_count}
          </td>

          <th>
            Total de pares:
          </th>

          <td>
            {$current_box_count}
          </td>
        </tr>
      </table>
    ";
  }

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

  function build_rows($products, $sizes, $current_box_count, $MAX_BOX_COUNT) {
    $text = "";

    while (count($products) > 0 && $current_box_count < $MAX_BOX_COUNT) {
      $product_name = array_key_first($products);
      $product = $products[$product_name];

      // echo "<p> Starting row: {$product_name} </p>";
      $text .= "<tr>";
      $completed_product = true;

      foreach ($sizes as $size) {
        $available_slots = $MAX_BOX_COUNT - $current_box_count;

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
        echo "<p> Row: size: {$size}, current_count:  {$current_count} </p>";
      }


      if ($completed_product){
        unset($products[$product_name]);
      }

      $text .= "</tr>";
    }

    return $text;
  }
?>