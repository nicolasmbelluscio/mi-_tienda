<?php
// conexion.php - Clase POO para conectar a MySQL
class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $bd = "mi_tienda";
    private $conexion;

    // Constructor: se ejecuta al instanciar la clase
    public function __construct() {
        $this->conexion = new mysqli(
            $this->host,
            $this->usuario,
            $this->password,
            $this->bd
        );

        // Verificar si hubo error de conexión
        if ($this->conexion->connect_errno) {
            die("Error de conexión a la base de datos: " . $this->conexion->connect_error);
        }

        // Establecer cotejamiento UTF-8
        $this->conexion->set_charset("utf8");
    }

    // Devuelve el objeto mysqli para realizar consultas SQL
    public function getConexion() {
        return $this->conexion;
    }

    // Cierra la conexión
    public function cerrar() {
        $this->conexion->close();
    }
}
?>