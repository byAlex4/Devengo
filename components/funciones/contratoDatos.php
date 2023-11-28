<?php
header("Content-Type: application/json");
try {
    // Conectamos con la base de datos
    include '../../config.php';
    // Preparamos la consulta SQL para obtener los datos de los usuarios, sus unidades y sus roles
    $stmt = $conexion->prepare("SELECT 
    id, 
    proveedor, 
    clave, 
    descripcion, 
    mont_max, 
    mont_min, 
    DATE_FORMAT( fecha_in, '%d-%M-%Y') AS fecha_in,
    DATE_FORMAT( fecha_fin, '%d-%M-%Y') AS fecha_fin, 
    DATE_FORMAT( created_at, '%d-%M-%Y') AS created_at, 
    DATE_FORMAT( updated_at, '%d-%M-%Y') AS updated_at
    FROM contratos");
    // Ejecutamos la consulta SQL sin parámetros
    $stmt->execute();
    // Obtenemos el resultado como un array de arrays asociativos
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Comprobamos si hay resultados

    // Devolvemos el resultado en formato JSON
    echo json_encode($resultados);

} catch (PDOException $e) {
    // Mostramos un mensaje de error genérico
    echo json_encode(array("error" => "Ocurrió un error al obtener los datos"));
}


?>