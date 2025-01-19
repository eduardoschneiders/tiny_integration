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

<div class="mb-5">
  <h1>Romaneio pedido <?= $response->retorno->pedido->numero ?></h1>
  <a href="javascript:history.back()" class="btn btn-outline-success">Voltar</a>
  <a href="talao.php?id=<?= $_GET['id'] ?>" class="btn btn-outline-success">Talão</a>
</div>



<div class="mb-5">
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']); ?>">
    <label>
        <input type="checkbox" name="box_sizes[]" value="18" <?php echo (!isset($_POST['box_sizes']) || in_array(18, $_POST['box_sizes'])) ? 'checked' : ''; ?>> 18
    </label>
    <label>
        <input type="checkbox" name="box_sizes[]" value="12" <?php echo (!isset($_POST['box_sizes']) || in_array(12, $_POST['box_sizes'])) ? 'checked' : ''; ?>> 12
    </label>
    <label>
        <input type="checkbox" name="box_sizes[]" value="6" <?php echo (!isset($_POST['box_sizes']) || in_array(6, $_POST['box_sizes'])) ? 'checked' : ''; ?>> 6
    </label>
    <label>
        <input type="checkbox" name="box_sizes[]" value="3" <?php echo (!isset($_POST['box_sizes']) || in_array(3, $_POST['box_sizes'])) ? 'checked' : ''; ?>> 3
    </label>
    <button type="submit">Usar estas caixas</button>
  </form>

</div>






<?php
  // Retrieve selected box sizes from POST request
  if (isset($_POST['box_sizes'])) {
      $boxSizes = array_map('intval', $_POST['box_sizes']); // Convert to integers
      rsort($boxSizes); // Sort in descending order for best-fit logic
  } else {
      $boxSizes = [18, 12, 6, 3]; // Default box sizes if none selected
  }

  // Validate that at least one box size is selected
  if (empty($boxSizes)) {
      die("Error: Please select at least one box size.");
  }
?>




<?php
  $pedido = $response->retorno->pedido->numero;
  $cliente = $response->retorno->pedido->cliente->nome;

  $products = [];
  $boxes = [];
  $sizes = [];

  foreach ($response->retorno->pedido->itens as $item) {
    $cleaned_name = preg_replace('/ -\s+\d+\/\d+/', '', $item->item->descricao);
    preg_match('/\d{2}\/\d{2}/', $item->item->descricao, $matches);

    if (count($matches) == 0){
      continue;
    }
    $number = $matches[0];
    array_push($sizes, $number);
    $products[$cleaned_name][$number] = (int)$item->item->quantidade;
  }

  $sizes = array_unique($sizes);






// Box size options (sorted in descending order)
// $boxSizes = [18, 12, 6, 3];
$boxes = [];
$currentBox = [];
$currentCount = 0;

// Function to find the largest possible box size
function getLargestPossibleBoxSize($totalRemainingProducts, $boxSizes) {
    foreach ($boxSizes as $size) {
        if ($totalRemainingProducts <= $size) {
            return $size; // Use the smallest box that can fit the remaining products
        }
    }
    return max($boxSizes); // Default to the largest box if none fit exactly
}


// Ensure all products have all sizes
foreach ($products as $productName => $sizeQuantities) {
    foreach ($sizes as $size) {
        if (!isset($sizeQuantities[$size])) {
            $sizeQuantities[$size] = 0; // Add missing size with quantity 0
        }
    }
    ksort($sizeQuantities); // Sort sizes for consistency
    $products[$productName] = $sizeQuantities;
}

$totalProducts = 0;
foreach ($products as $productName => $sizeQuantities) {
    foreach ($sizeQuantities as $quantity) {
        $totalProducts += $quantity; // Calculate the total number of products
    }
}

foreach ($products as $productName => $sizeQuantities) {
    $normalizedSizes = array_fill_keys($sizes, 0);
    foreach ($sizeQuantities as $size => $quantity) {
        $normalizedSizes[$size] = $quantity;
    }

    foreach ($normalizedSizes as $size => $quantity) {
        while ($quantity > 0) {
            // Update the total amount of remaining products
            $totalRemainingProducts = $totalProducts;

            // Find the largest possible box size for the remaining products
            $largestBoxCapacity = getLargestPossibleBoxSize($totalRemainingProducts, $boxSizes);
            echo 'Total: '.$totalRemainingProducts.'<br>';

            $toAdd = min($quantity, $largestBoxCapacity - $currentCount);

            if (!isset($currentBox['products'][$productName])) {
                $currentBox['products'][$productName] = array_fill_keys($sizes, 0);
            }

            $currentBox['products'][$productName][$size] += $toAdd;
            $currentCount += $toAdd;
            $quantity -= $toAdd;
            $totalProducts -= $toAdd; // Decrement total remaining products

            // Add metadata to the box
            $currentBox['box_size'] = $largestBoxCapacity;
            $currentBox['total_products'] = $currentCount;

            if ($currentCount === $largestBoxCapacity) {
                $boxes[] = $currentBox;
                $currentBox = [];
                $currentCount = 0;
            }
        }
    }
}

// Add the last box if it's not empty
if (!empty($currentBox)) {
    $boxes[] = $currentBox;
}


?>

<?php
  if (count($products) == 0) {
    echo "<h7>Quantidade produtos:" . count($products) . "</h7>";
  }
?>





















<?php
  // Mocked data for "Pedido" and "Cliente"
  // $pedido = 721;
  // $cliente = "Mukako Store";

  // HTML structure for boxes
foreach ($boxes as $index => $box) {
    $volume = ($index + 1) . "/" . count($boxes); // Current box and total boxes
    $boxSize = $box['box_size']; // Box size
    $totalPairs = $box['total_products']; // Total products in the box

    // Start the main table
    echo "<table class='table table-bordered' style='width: 100%; text-align: left; margin-bottom: 20px;'>";

    // Pedido and Cliente row
    echo "
        <tr>
            <td><strong>Pedido:</strong> $pedido</td>
            <td colspan='6'><strong>Cliente:</strong> $cliente</td>
        </tr>
    ";

    // Start the "Referencia" table
    echo '<tr><td colspan="7" style="padding: 0;">';
    echo '<table class="table table-bordered" style="width: 100%; text-align: left;">';

    // Header row for sizes in the "Referencia" table
    echo "
        <tr>
            <td><strong>Referencia:</strong></td>";
    foreach ($sizes as $size) {
        echo "<td><strong>$size</strong></td>";
    }
    echo "</tr>";

    // Products and their sizes in the "Referencia" table
    foreach ($box['products'] as $productName => $sizeQuantities) {
        echo "<tr><td>$productName</td>";
        foreach ($sizes as $size) {
            echo "<td>" . $sizeQuantities[$size] . "</td>";
        }
        echo "</tr>";
    }

    // Close the "Referencia" table
    echo '</table>';
    echo '</td></tr>';

    // Footer row for volume, total pairs, and box size on the same line
    echo "
        <tr>
            <td colspan='7'>
                <strong>Volume:</strong> $volume |
                <strong>Total de pares:</strong> $totalPairs |
                <strong>Tamanho da caixa:</strong> $boxSize
            </td>
        </tr>
    ";

    // Close the main table
    echo '</table>';

    // Add an <hr> between tables
    if ($index < count($boxes) - 1) {
        echo "<hr  class='my-5'>";
    }
}




?>


























<?php die()?>

<hr>

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