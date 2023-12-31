<?php
header("Content-Type: application/json");
try {
    // Conectamos con la base de datos
    include '../../config.php';

    // Preparamos la consulta SQL para obtener los datos de los usuarios, sus unidades y sus roles
    $stmt = $conexion->prepare("SELECT usuarios.id, usuarios.matricula, usuarios.nombre, 
        DATE_FORMAT( usuarios.created_at, '%d-%M-%Y') AS created_at, 
        DATE_FORMAT( usuarios.updated_at, '%d-%M-%Y') AS updated_at, 
        unidades.nombre AS unidad, 
        roles.nombre AS rol, usuarios.contra FROM usuarios 
        JOIN unidades ON usuarios.unidadID = unidades.id 
        JOIN roles ON usuarios.rolID = roles.id ");

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