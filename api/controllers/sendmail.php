<?php
use PHPMailer\PHPMailer\PHPMailer;
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../src/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../../src/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../src/PHPMailer-master/src/SMTP.php';

// function smtp PHPMailer
function smtpMailer($to, $from, $from_name, $subject, $body) {
	$mail = new PHPMailer();  // Cree un nouvel objet PHPMailer
	$mail->IsSMTP(); // active SMTP
	$mail->SMTPDebug = 0;  // debogage: 1 = Erreurs et messages, 2 = messages seulement
	$mail->SMTPAuth = true;  // Authentification SMTP active
  $mail->SMTPAutoTLS = false;
	$mail->SMTPSecure = 'ssl'; // Gmail REQUIERT Le transfert securise
	$mail->Host = 'smtp.gmail.com';
  $mail->Port = 465;
	$mail->Username = MAIL_USER;
	$mail->Password = MAIL_PASS;
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		return 'Mail error: '.$mail->ErrorInfo;
	} else {
		return true;
	}
}

// sendmail form contact
function sendmail() {
  if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "The method is not allowed"]);
    return;
  } 
  // get informations in POST
  $request = json_decode(file_get_contents('php://input'));
  // the required data is checked
  if(empty($request->lastName) || empty($request->firstName) || empty($request->email) || empty($request->message)) {
    http_response_code(401);
    echo json_encode(['message' => 'Please fill in the mandatory fields of the form.']);
  }
  // we declare variables
  $from = htmlspecialchars(strip_tags($request->email));
  $lastName = htmlspecialchars(strip_tags($request->lastName));
  $firstName = htmlspecialchars(strip_tags($request->firstName));
  $name = $firstName . ' ' . $lastName;
  $subject = 'Formulaire de contact';
  $message = htmlspecialchars(strip_tags($request->message));

  // send mail
  $result = smtpmailer(MAIL_USER, $from, $name, $subject, $message);
  if (true !== $result) {
    // erreur -- traiter l'erreur
    http_response_code(500);
    $response['code'] = 500;
    $response['message'] = $result;
    echo json_encode($response);
  } else {
    http_response_code(200);
    $response['code'] = 200;
    $response['message'] = 'The mail has been successfully sent.';
    echo json_encode($response);
  }
}