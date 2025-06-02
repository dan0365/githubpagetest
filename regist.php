<?php
$conexion = new mysqli("localhost", "root", "", "bdusuarios");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

// Encriptar la contraseña
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

// Verificar si el usuario ya existe
$verifica = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
$verifica->bind_param("s", $usuario);
$verifica->execute();
$verificaResultado = $verifica->get_result();

if ($verificaResultado->num_rows > 0) {
    echo "El nombre de usuario ya está registrado.";
} else {
    // Insertar nuevo usuario
    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)");
    $stmt->bind_param("ss", $usuario, $contrasenaHash);

    if ($stmt->execute()) {
        echo "Usuario registrado con éxito.";
    } else {
        echo "Error al registrar: " . $stmt->error;
    }
}

$conexion->close();
?>
