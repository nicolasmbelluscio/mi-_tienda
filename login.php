<?php
// login.php - Manejo de autenticación, sesión y cookies
session_start();

// Si el usuario ya inició sesión, lo redirigimos al panel principal
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require_once "conexion.php";

$error = "";
// Recuperar la cookie si existe para autocompletar el campo
$usuario_cookie = isset($_COOKIE['usuario_guardado']) ? $_COOKIE['usuario_guardado'] : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if (!empty($usuario) && !empty($password)) {
        $db = new Conexion();
        $conn = $db->getConexion();

        // Consulta mediante Prepared Statement (seguridad anti SQL Injection)
        $stmt = $conn->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($user = $resultado->fetch_assoc()) {
            // Verificar contraseña (para producción se usará password_verify)
            if ($password === $user['password']) {
                // 1. Guardar sesión
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['usuario_id'] = $user['id'];

                // 2. Manejo de Cookie "Recordarme"
                if (isset($_POST['recordar'])) {
                    // Guarda la cookie por 30 días
                    setcookie("usuario_guardado", $usuario, time() + (86400 * 30), "/");
                } else {
                    // Borra la cookie si no se marcó la casilla
                    setcookie("usuario_guardado", "", time() - 3600, "/");
                }

                $db->cerrar();
                header("Location: index.php");
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "El usuario no existe.";
        }
        $db->cerrar();
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}

require_once "header.php";
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4>Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario:</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" 
                               value="<?php echo htmlspecialchars($usuario_cookie); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="recordar" id="recordar" class="form-check-input" 
                               <?php echo !empty($usuario_cookie) ? 'checked' : ''; ?>>
                        <label for="recordar" class="form-check-label">Recordar mi usuario</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                </form>
            </div>
            <div class="card-footer text-muted text-center">
                <small>Datos de prueba: <strong>admin</strong> / <strong>1234</strong></small>
            </div>
        </div>
    </div>
</div>

<?php require_once "footer.php"; ?>