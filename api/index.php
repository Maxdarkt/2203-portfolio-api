<?php
require_once( __DIR__ . '/../cors.php');
require_once 'controllers/sendmail.php';

try {
  if(!empty($_GET['request'])) {
    $url = explode('/', filter_var($_GET['request'], FILTER_SANITIZE_URL));
    switch($url[0]) {
      case 'contact': 
          if(empty($url[1])) {
            sendmail();
          } else {
            throw new Exception ('Please, Define a valid route.');
          }
        break;
      case 'products': 
        if(empty($url[1])) {
          // sendmail();
          echo 'product';
        } else {
          echo 'id';
        }
        break;
      default: throw new Exception ('Please, Define a valid route.');
    }
  } else {
    throw new Exception ('Please, Define a route.');
  }
} catch(Exception $e) {
  $erreur = [
    'message' => $e->getMessage(),
    'code' => $e->getCode()
  ];
  http_response_code($erreur['code']);
  echo json_encode($erreur);
}

