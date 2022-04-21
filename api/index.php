<?php
require_once('./controllers/contact.php');

try {
  if(!empty($_GET['request'])) {
    $url = explode('/', filter_var($_GET['request'], FILTER_SANITIZE_URL));
    switch($url[0]) {
      case 'contact': 
          if(empty($url[1])) {
            sendEmailContactForm();
          }
        break;
      case 'files': echo 'files';
        break;
      default: throw new Exception ('Bad endpoint');
    }
  } else {
    throw new Exception ('Data recovery problem.');
  }
} catch(Exception $e) {
  $erreur = [
    'message' => $e->getMessage(),
    'code' => $e->getCode()
  ];
  print_r($erreur);
}