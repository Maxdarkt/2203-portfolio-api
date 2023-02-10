<?php
require_once __DIR__ . '/../cors.php';
require_once 'controllers/sendmail.php';

cors();

$url = explode("/", filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL));
$method = $_SERVER['REQUEST_METHOD'];

// echo $url[2];
// echo $method;
echo $_SERVER['REQUEST_URI'];

try {
  if($url[1] === "api") {
    switch($url[2]) {
      case 'contact': 
          if($method === "POST") {
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

