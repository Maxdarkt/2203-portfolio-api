<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER["REQUEST_METHOD"] == "POST") {

  function sendEmailContactForm() {
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->lastName) && !empty($data->firstName) && !empty($data->email) && !empty($data->message)) {
      $to = "Maxdev74@gmail.com";
      $subject = "Formulaire de contact";
      $message = $data->message . "\n" . $data->firstName . "\n" . $data->lastName . "\n" . $data->mobile . "\n" . $data->company;
      $headers = 'From: ' . $to . "\r\n" . 'Reply to: ' . $to . "\r\n" . 'X-Mailer: PHP/' .phpversion();
      $confirmation_message = "Bonjour, \n votre email a bien été envoyé. Je vous répondrai dans les plus brefs délai. \n Cordialement, \n Tourneux Maxence";
      try {
        mail($to, $subject, $message, $headers);
        mail($to, $subject, $confirmation_message, $headers);
        http_response_code(200);
        echo json_encode(["message" => "email send"]);
      } catch(Exception $e) {
        $erreur = [
          "message" => $e->getMessage(),
          "code" => $e->getCode()
        ];
        print_r($erreur);
      }
    }
  }
} else {
  http_response_code(405);
  echo json_encode(["message" => "The method is not allowed"]);
}