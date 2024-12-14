<?php
$host = 'vietnamnailsechegaray.com'; // Nombre del servidor
$dbname = 'u480382244_VietnamNails'; // Nombre de la base de datos
$username = 'u480382244_VNEchegaray';
$password = '@Vnechagaray18';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión a la base de datos");
}
?>
