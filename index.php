<?php
  function foo() {
    echo('lalal');
  }

  function pre($variable) {
    print("<pre>".print_r($variable,true)."</pre>");
  }
?>

<p>Test Eduardo</p>

<?php

  echo("Hello world");
  pre(getenv('API_KEY'));
?>