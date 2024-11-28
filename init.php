<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establecer idioma predeterminado si no está configurado
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es'; // Español por defecto
}

// Comprobar si se ha enviado un cambio de idioma
if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'ca', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Cargar archivo de idioma
$lang_file = BASE_PATH . 'languages/' . $_SESSION['lang'] . '.php';
if (file_exists($lang_file)) {
    $lang = include $lang_file;
} else {
    die('Error: Archivo de idioma no encontrado.');
}
?>
