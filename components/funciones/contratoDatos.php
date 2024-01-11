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
    CONCAT(DAY(contratos.fecha_in), '-', ELT(MONTH(contratos.fecha_in), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'), '-', YEAR(contratos.fecha_in)) AS fecha_in,
    CONCAT(DAY(contratos.fecha_fin), '-', ELT(MONTH(contratos.fecha_fin), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'), '-', YEAR(contratos.fecha_fin)) AS fecha_fin
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