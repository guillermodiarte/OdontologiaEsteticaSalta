<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Access-Control-Allow-Origin: *');
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