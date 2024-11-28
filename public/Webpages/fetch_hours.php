<?php
require_once '../../connection.php';
require_once 'reservations.php';

$staff = $_GET['staff'];
$date = $_GET['date'];

if ($staff && $date) {
    $available_hours = get_available_hours($staff, $date);
    echo json_encode($available_hours);
}
?>