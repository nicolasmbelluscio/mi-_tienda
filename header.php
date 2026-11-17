<?php
// Iniciar sesión en cada página si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Tienda - Gestión de Productos</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Mi Tienda</a>
    
    <?php if (isset($_SESSION['usuario'])): ?>
      <div class="d-flex align-items-center">
        <span class="navbar-text me-3 text-white">
          Hola, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>
        </span>
        <a href="index.php" class="btn btn-sm btn-outline-light me-2">Ver Productos</a>
        <a href="agregar.php" class="btn btn-sm btn-light me-2">+ Nuevo Producto</a>
        <a href="logout.php" class="btn btn-sm btn-outline-warning">Cerrar Sesión</a>
      </div>
    <?php endif; ?>
  </div>
</nav>

<div class="container">