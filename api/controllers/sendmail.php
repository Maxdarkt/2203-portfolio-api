<?php
use PHPMailer\PHPMailer\PHPMailer;
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../src/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../../src/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../src/PHPMailer-master/src/SMTP.php';

// function smtp PHPMailer
function smtpMailer($to, $subject, $body) {
	$mail = new PHPMailer();  // Cree un nouvel objet PHPMailer
	$mail->isSMTP(); // active SMTP
	$mail->SMTPDebug = 0;  // debogage: 1 = Erreurs et messages, 2 = messages seulement
	$mail->Host = 'smtp.hostinger.com';
  $mail->Port = 587;
	$mail->SMTPAuth = true;  // Authentification SMTP active
  // $mail->SMTPAutoTLS = false;
	// $mail->SMTPSecure = 'ssl'; // Gmail REQUIERT Le transfert securise
	$mail->Username = MAIL_USER;
	$mail->Password = MAIL_PASS;
	$mail->setFrom(MAIL_USER, "MT-DEVELOP");
	$mail->Subject = $subject;
  $mail->isHTML(true);
	$mail->Body = $body;
  $mail->CharSet = 'UTF-8';
	$mail->addAddress($to);
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
  $to = htmlspecialchars(strip_tags($request->to));
  $email = htmlspecialchars(strip_tags($request->email));
  $lastName = htmlspecialchars(strip_tags($request->lastName));
  $firstName = htmlspecialchars(strip_tags($request->firstName));
  $company = htmlspecialchars(strip_tags($request->company));
  $mobile = htmlspecialchars(strip_tags($request->mobile));
  $subject = 'Formulaire de contact';
  $message = htmlspecialchars(strip_tags($request->message));
  $body = "
  <html>
  <head>
      <title>This is a test HTML email</title>
  </head>
  <body>
      <h1>Vous avez reçu un nouveau message du formulaire de contact</h1>
      <p>
        Nom : {$lastName}
      </p>
      <p>
        Prénom : {$firstName}
      </p>
      <p>
        Société : {$company}
      </p>
      <p>
        Mobile : {$mobile}
      </p>
      <p>
        Email : {$email}
      </p>
      <div>
        <blockquote>
          {$message}
        </blockquote>
      </div>
  </body>
  </html>
  ";

  // send mail
  $result = smtpmailer($to, $subject, $body);

  if ($result !== true) {
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