<?php
session_start();
require_once '../setup_files/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['userName'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE userName = ? AND password = ? AND isActive = 1");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['admin_id'] = $user['id_user'];
        $_SESSION['admin_name'] = $user['userName'];
        header('Location: admin.php');
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/Resources/css/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
        <div class="col-md-6">
    <div class="card login-container">
        <div class="card-header">
            <h3 class="login-title">Iniciar Sesión</h3>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="login-label">Usuario</label>
                    <input type="text" name="userName" class="login-input" required>
                </div>
                <div class="mb-3">
                    <label class="login-label">Contraseña</label>
                    <input type="password" name="password" class="login-input" required>
                </div>
                <button type="submit" class="login-button">Entrar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html> 