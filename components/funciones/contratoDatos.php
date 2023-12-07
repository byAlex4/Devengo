<?php
header("Content-Type: application/json");
try {
    // Conectamos con la base de datos
    include '../../config.php';
    // Preparamos la consulta SQL para obtener los datos de los usuarios, sus unidades y sus roles
    $stmt = $conexion->prepare("SELECT 
    contratos.id, 
    contratos.proveedor, 
    contratos.clave, 
    cuentas.cuenta AS cuenta,
    cuentas.descripcion AS cuentaDes,
    contratos.descripcion, 
    FORMAT(contratos.mont_max, 3, 'es-MX') AS mont_max, 
    FORMAT(contratos.mont_min, 3, 'es-MX') AS mont_min, 
    DATE_FORMAT( contratos.fecha_in, '%d-%M-%Y') AS fecha_in,
    DATE_FORMAT( contratos.fecha_fin, '%d-%M-%Y') AS fecha_fin
    FROM contratos
    JOIN cuentas ON contratos.cuentaID = cuentas.id");
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