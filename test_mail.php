<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$recipient = "guillermo.diarte@gmail.com";
$subject = "Prueba de Diagnóstico - " . time();

echo "<h1>Prueba de Envío de Correo</h1>";
echo "<p>Intentando enviar correos a: <strong>$recipient</strong></p>";

// Test 1: Minimal (Default PHP settings)
$headers1 = ""; // Let PHP decide
$result1 = mail($recipient, "$subject (Mínimo)", "Prueba 1: Sin headers personalizados.", $headers1);
echo "<p>Intento 1 (Sin headers): " . ($result1 ? '✅ Aceptado por PHP' : '❌ Rechazado por PHP') . "</p>";

// Test 2: Standard Headers with potential domain
$domain = $_SERVER['SERVER_NAME'];
$from = "wordpress@$domain"; // 'wordpress' or 'info' often whitelisted default
$headers2 = "From: $from\r\n";
$result2 = mail($recipient, "$subject (Standard)", "Prueba 2: From: $from", $headers2);
echo "<p>Intento 2 (From: $from): " . ($result2 ? '✅ Aceptado por PHP' : '❌ Rechazado por PHP') . "</p>";

// Test 3: Full Headers
$headers3 = "MIME-Version: 1.0" . "\r\n";
$headers3 .= "Content-type:text/plain;charset=UTF-8" . "\r\n";
$headers3 .= "From: no-reply@$domain" . "\r\n";
$headers3 .= "X-Mailer: PHP/" . phpversion();
$result3 = mail($recipient, "$subject (Full Headers)", "Prueba 3: Full Headers", $headers3);
echo "<p>Intento 3 (Full Headers): " . ($result3 ? '✅ Aceptado por PHP' : '❌ Rechazado por PHP') . "</p>";

echo "<h2>Instrucciones:</h2>";
echo "<ul>";
echo "<li>Si ves '✅ Aceptado', el servidor intentó enviarlo.</li>";
echo "<li>Revisa tu correo (y SPAM) en 5 minutos.</li>";
echo "<li>Dime cuál de los 3 (o ninguno) llegó.</li>";
echo "</ul>";
?>