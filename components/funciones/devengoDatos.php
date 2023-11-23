<?php
header("Content-Type: application/json");
try {
    // Conectamos con la base de datos
    include '../../config.php';
    // Iniciar la sesión
    session_start();
    $unidad = $_SESSION['unidad'];

    // Preparamos la consulta SQL para obtener los datos de los usuarios, sus unidades y sus roles
    if ($_SESSION['rol'] == "Administrador") {
        $stmt = $conexion->prepare(
            "SELECT
            devengos.id,
            devengos.fecha,
            devengos.descripcion,
            FORMAT(devengos.monto, 3) AS monto_formato,
            devengos.created_at,
            devengos.updated_at,
            devengos.contratoID AS contratoID,
            contratos.clave AS contrato,
            FORMAT(contratos.mont_max, 3) AS saldo, (
                contratos.mont_max - (
                    SELECT SUM(monto)
                    FROM devengos
                    WHERE
                        contratoID = contratos.id
                    )
                ) AS saldoDis,
                    usuarios.nombre AS usuario,
                    unidades.nombre AS unidad,
                    contratos.proveedor AS proveedor
                FROM devengos
                    JOIN contratos ON devengos.contratoID = contratos.id
                    JOIN usuarios ON devengos.usuarioID = usuarios.id
                    JOIN unidades ON usuarios.unidadID = unidades.id"
        );
    } else {
        $stmt = $conexion->prepare(
            "SELECT
            devengos.id,
            devengos.fecha,
            devengos.descripcion,
            FORMAT(devengos.monto, 3) AS monto_formato,
            devengos.created_at,
            devengos.updated_at,
            devengos.contratoID AS contratoID,
            contratos.clave AS contrato,
            FORMAT(contratos.mont_max, 3) AS saldo, (
                contratos.mont_max - (
                    SELECT SUM(monto)
                    FROM devengos
                    WHERE
                        contratoID = contratos.id
                )
            ) AS saldoDis,
                usuarios.nombre AS usuario,
                unidades.nombre AS unidad
                contratos.proveedor AS proveedor
            FROM devengos
                JOIN contratos ON devengos.contratoID = contratos.id
                JOIN usuarios ON devengos.usuarioID = usuarios.id
                JOIN unidades ON usuarios.unidadID = unidades.id
            WHERE unidades.nombre = '" . $_SESSION['unidad'] . "';"
        );
    }

    // Ejecutamos la consulta SQL sin parámetros
    $stmt->execute();
    // Obtenemos el resultado como un array de arrays asociativos
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //echo json_encode($stmt);

    // Comprobamos si hay resultados
    if (count($resultados) > 0) {
        // Devolvemos el resultado en formato JSON
        echo json_encode($resultados);
    } else {
        // Mostramos un mensaje indicando que no hay resultados
        echo json_encode(array("message" => "No se encontraron usuarios"));
    }
} catch (PDOException $e) {
    // Mostramos un mensaje de error genérico
    echo json_encode(array("error" => "Ocurrió un error al obtener los datos"));
}
?>