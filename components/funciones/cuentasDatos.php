<?php
header("Content-Type: application/json");
try {
    // Conectamos con la base de datos
    include '../../config.php';

    // Preparamos la consulta SQL para obtener los datos de los usuarios, sus unidades y sus roles
    $stmt = $conexion->prepare("SELECT * FROM cuentas");

    // Ejecutamos la consulta SQL sin parámetros
    $stmt->execute();
    // Obtenemos el resultado como un array de arrays asociativos
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultados);
} catch (PDOException $e) {
    // Mostramos un mensaje de error genérico
    echo json_encode(array("error" => "Ocurrió un error al obtener los datos"));
}
?>