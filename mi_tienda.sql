\-- Base de datos para el proyecto "Mi Tienda" 
CREATE DATABASE IF NOT EXISTS mi\_tienda CHARACTER SET utf8 COLLATE utf8\_spanish\_ci; 
USE mi\_tienda;

\-- 1\. Tabla de Usuarios Administradores 
CREATE TABLE IF NOT EXISTS usuarios ( id INT AUTO\_INCREMENT PRIMARY KEY, usuario VARCHAR(50) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL );

\-- 2\. Tabla de Productos 
CREATE TABLE IF NOT EXISTS productos ( id INT AUTO\_INCREMENT PRIMARY KEY, nombre VARCHAR(100) NOT NULL, precio DECIMAL(10,2) NOT NULL, stock INT NOT NULL );

\-- 3\. Usuario Administrador por defecto 
INSERT INTO usuarios (usuario, password) VALUES ('admin', '1234');

\-- 4\. Productos de prueba iniciales 
INSERT INTO productos (nombre, precio, stock) VALUES ('Teclado Mecánico RGB', 45000.00, 15), ('Mouse Inalámbrico', 22000.50, 30), ('Monitor 24 Pulgadas FHD', 180000.00, 8);