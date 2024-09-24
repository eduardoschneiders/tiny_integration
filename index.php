<?php
  switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
      require 'list.php';
      break;
    case '/list.php':
      require 'list.php';
      break;
    case '/talao.php':
      require 'talao.php';
      break;
    case '/romaneio.php':
      require 'romaneio.php';
      break;
    default:
        http_response_code(404);
        exit('Not Found');
  }
?>
