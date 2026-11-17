<?php
// eliminar.php - Borrado de registros (Delete)
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once "conexion.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $db = new Conexion();
    $conn = $db->getConexion();

    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $db->cerrar();
    header("Location: index.php?mensaje=Producto eliminado correctamente");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>