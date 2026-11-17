<?php
// index.php - Panel Principal y Lectura (Read)
session_start();

// Control de Acceso: si no está logueado, redirige al Login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once "conexion.php";
require_once "header.php";

$db = new Conexion();
$conn = $db->getConexion();

// Consulta para obtener todos los productos
$sql = "SELECT * FROM productos ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Listado de Productos</h2>
    <a href="agregar.php" class="btn btn-success">+ Agregar Producto</a>
</div>

<?php if (isset($_GET['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['mensaje']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow">
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td>$<?php echo number_format($row['precio'], 2, ',', '.'); ?></td>
                            <td><?php echo $row['stock']; ?> u.</td>
                            <td class="text-center">
                                <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning me-1">Editar</a>
                                <a href="eliminar.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No hay productos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
$db->cerrar();
require_once "footer.php"; 
?>