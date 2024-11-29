<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/');
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$allowed_languages = ['es', 'ca', 'en'];
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed_languages)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang_file = BASE_PATH . 'languages/' . $_SESSION['lang'] . '.php';

if (file_exists($lang_file)) {
    $lang = include $lang_file;
} else {
    $lang_file = BASE_PATH . 'languages/es.php';
    
    if (file_exists($lang_file)) {
        $lang = include $lang_file;
    } else {
        die('Error: Default language file not found.');
    }
}
