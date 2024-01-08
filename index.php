<?php
$error = false;
$config = include 'config.php';

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
} catch (PDOException $error) {
    $error = $error->getMessage();
}

if ($error) {
    ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            </div>
        </div>
    </div>
    <?php
} else
    echo ("todo correcto");
header("Location: components/index.php");
echo ("creando perfil...");
$matricula = "SS0123";
$contraseña = "12345";
$nombre = "Alejandro Bocanegra Medina";
$unidad = "1";
$rol = "1";

$contraseña_encriptada = password_hash($contraseña, PASSWORD_BCRYPT);
echo ($contraseña_encriptada);

$sql = "INSERT INTO usuarios (matricula, nombre, unidadID, rolID, contra) VALUES ('$matricula', '$nombre', '$unidad', '$rol', '$contraseña_encriptada')";
// Ejecutar la consulta SQL
$conexion->exec($sql);
echo "Perfil creado correctamente.";
?>

<form action="components/login.php" method="get">
    <button type="submit">Ir al login</button>
</form>