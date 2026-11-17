<?php
// editar.php - Edición de registros (Update)
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once "conexion.php";

$db = new Conexion();
$conn = $db->getConexion();
$error = "";

// Obtener ID del producto a editar
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit();
}

// Procesar el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    if (!empty($nombre) && $precio > 0 && $stock >= 0) {
        $stmt = $conn->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?");
        $stmt->bind_param("sdii", $nombre, $precio, $stock, $id);

        if ($stmt->execute()) {
            $db->cerrar();
            header("Location: index.php?mensaje=Producto actualizado correctamente");
            exit();
        } else {
            $error = "Error al actualizar el producto.";
        }
    } else {
        $error = "Por favor completa todos los campos correctamente.";
    }
}

// Obtener los datos actuales del producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

if (!$producto) {
    $db->cerrar();
    header("Location: index.php");
    exit();
}

require_once "header.php";
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Editar Producto #<?php echo $producto['id']; ?></h4>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="editar.php?id=<?php echo $id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" 
                               value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio ($):</label>
                        <input type="number" step="0.01" name="precio" id="precio" class="form-control" 
                               value="<?php echo $producto['precio']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock:</label>
                        <input type="number" name="stock" id="stock" class="form-control" 
                               value="<?php echo $producto['stock']; ?>" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$db->cerrar();
require_once "footer.php"; 
?>