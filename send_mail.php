<?php
session_start();
header('Content-Type: application/json');

// Anti-spam configuration
$honeypot_field = 'website_url';

$response = [
  'success' => false,
  'message' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // 1. Honeypot Check
  if (!empty($_POST[$honeypot_field])) {
    $response['success'] = true;
    $response['message'] = 'Mensaje enviado correctamente.';
    echo json_encode($response);
    exit;
  }

  // 2. Dynamic Captcha Validation
  $captcha_input = intval($_POST["captcha"]);
  $captcha_correct = isset($_SESSION['captcha_result']) ? $_SESSION['captcha_result'] : null;

  if ($captcha_correct === null || $captcha_input !== $captcha_correct) {
    $response['message'] = 'La respuesta de seguridad es incorrecta. Por favor intenta con la nueva pregunta.';
    http_response_code(400);
    echo json_encode($response);
    exit;
  }

  // 3. Sanitize and Validate Inputs
  $name = strip_tags(trim($_POST["nombre"]));
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  $phone = strip_tags(trim($_POST["telefono"]));
  $subject = strip_tags(trim($_POST["asunto"]));
  $message = strip_tags(trim($_POST["mensaje"]));

  // Required fields check
  if (empty($name) || empty($message) || empty($subject) || empty($phone)) {
    $response['message'] = 'Por favor completa los campos obligatorios (Nombre, Teléfono, Asunto, Mensaje).';
    http_response_code(400);
    echo json_encode($response);
    exit;
  }

  // 4. Email Configuration
  $recipient = "guillermo.diarte@gmail.com";
  $email_subject = "Nuevo Contacto Web: $subject";

  $email_content = "Has recibido un nuevo mensaje desde tu sitio web.\n\n";
  $email_content .= "Nombre: $name\n";
  $email_content .= "Teléfono: $phone\n";
  $email_content .= "Email: " . ($email ? $email : 'No especificado') . "\n\n";
  $email_content .= "Mensaje:\n$message\n";

  // Headers - Minimal Configuration (Matched to successful "Test 1")
  // We strictly avoid setting 'From' to let Hostinger handle authentication.

  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/plain;charset=UTF-8" . "\r\n";

  // Only add Reply-To if email is present.
  if (!empty($email)) {
    $headers .= "Reply-To: $email" . "\r\n";
  }

  // 5. Send Email
  if (mail($recipient, $email_subject, $email_content, $headers)) {
    $response['success'] = true;
    $response['message'] = '¡Gracias! Tu mensaje ha sido enviado.';
    // Clear captcha after success
    unset($_SESSION['captcha_result']);
    http_response_code(200);
  } else {
    $response['message'] = 'Hubo un error al enviar el mensaje. Por favor intenta más tarde o contáctanos por WhatsApp.';
    http_response_code(500);
  }

} else {
  $response['message'] = 'Método no permitido.';
  http_response_code(403);
}

echo json_encode($response);
?>