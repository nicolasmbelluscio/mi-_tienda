<?php
// agregar.php - Formulario de Inserción (Create)
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once "conexion.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    if (!empty($nombre) && $precio > 0 && $stock >= 0) {
        $db = new Conexion();
        $conn = $db->getConexion();

        // Consulta preparada para insertar de forma segura
        $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)");
        $stmt->bind_param("sdi", $nombre, $precio, $stock);

        if ($stmt->execute()) {
            $db->cerrar();
            header("Location: index.php?mensaje=Producto agregado correctamente");
            exit();
        } else {
            $error = "Error al guardar el producto.";
        }
        $db->cerrar();
    } else {
        $error = "Por favor completa todos los campos correctamente.";
    }
}

require_once "header.php";
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Agregar Nuevo Producto</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="agregar.php" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio ($):</label>
                        <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock inicial:</label>
                        <input type="number" name="stock" id="stock" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once "footer.php"; ?>