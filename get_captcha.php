<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
session_start();
header('Content-Type: application/json');

// Generate two random numbers between 1 and 9
$num1 = rand(1, 9);
$num2 = rand(1, 9);
$result = $num1 + $num2;

// Store the result in the session for validation
$_SESSION['captcha_result'] = $result;

// Return the question
echo json_encode([
  'question' => "¿Cuánto es $num1 + $num2?"
]);
?>