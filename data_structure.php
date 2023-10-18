<!--
Chinelo Slide XN Bike Indoor - Branco - 36/37
Chinelo Slide XN Bike Indoor - Branco - 38/39
Chinelo Slide XN Bike Indoor - Branco - 40/41
Chinelo Slide XN Bike Indoor - Branco - 42/43
Chinelo Slide XN Bike Indoor - Preto - 36/37
Chinelo Slide XN Bike Indoor - Preto - 38/39
Chinelo Slide XN Bike Indoor - Preto - 40/41
Chinelo Slide XN Bike Indoor - Preto - 42/43
Chinelo Slide XN Body - Preto - 34/35
Chinelo Slide XN Body - Preto - 36/37
Chinelo Slide XN Body - Preto - 38/39
Chinelo Slide XN Body - Preto - 42/43
Chinelo Slide XN Body - Branco - 34/35
Chinelo Slide XN Body - Branco - 36/37
Chinelo Slide XN Body - Branco - 38/39
Chinelo Slide XN Body - Branco - 42/43
-->


<?
  $sizes = array('36/37', '38/39', '40/41', '42/43');

  $products = array(
    'Chinelo Slide XN Bike Indoor - Branco' => array(
      '36/37' => 1,
      '38/39' => 5,
      '40/41' => 2,
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
<!--
  MAX_BOX_COUNT = 24

  while products
    build box
      current_box_count = 0
      while products && current_box_count < MAX_BOX_COUNT
        build row
          product = products.first
          completed_product = true

          foreach all sizes as size {
            available_slots = MAX_BOX_COUNT - current_box_count

            if (product[size] > available_lots) {
              left = product[size]  - available_count

              current_count += product[size] - left
              product[size] = left
            } else {
              current_count += product[size]
              product[size] = 0
            }

            if (current_count > 0)
              completed_product = false

            current_box_count += current_count
            echo <row>
          }

          if (completed_product){
            remove product
          }


 -->


 <!--
  MAX_BOX_COUNT = 24
  current_box_count = 20
  product[size] = 5

  ----

  (MAX_BOX_COUNT - current_box_count)
  slots: 4

  product[size] - 4
  current_box_count +=




  -->