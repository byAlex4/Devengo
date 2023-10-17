<?php
$error = false;
$config = include '../config.php';

$meses = array(
    "",
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    'Agosto',
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre"
);

try {
    $consultaSQL = "SET @csum := 0; SELECT
        DATE_FORMAT(m.fecha, '%Y-%m') AS mes,
        COALESCE(t.total_mes, 500) AS total_mes,
        (@csum := @csum + COALESCE(t.total_mes,500)) AS total_acumulado
        FROM (SELECT DATE_ADD('2023-04-16', INTERVAL n MONTH) AS fecha 
        FROM (SELECT 0 AS n 
        UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 
        UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 
        UNION ALL SELECT 11) AS numbers WHERE DATE_ADD('2023-04-16', INTERVAL n MONTH) <= CURDATE()) AS m
        LEFT JOIN 
        (SELECT SUM(monto) AS total_mes, DATE_FORMAT(fecha, '%Y-%m') AS mes FROM devengos WHERE devengos.contratoID = 1 GROUP BY DATE_FORMAT(fecha, '%Y-%m')) AS t ON DATE_FORMAT(m.fecha, '%Y-%m') = t.mes
        ORDER BY 
        m.fecha ASC;";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
} catch (PDOException $error) {
    $error = $error->getMessage();
}

?>
<div class="modal-content">
    <div class="modal-header">
        <h2 class="modal-title" id="crearModalLabel">Informacion del contrato</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="card-body mx-4">
            <div class="container" style="place-content: center;">
                <div class="row ms-5">
                    <h3>Contrato: <span id="claveMostrar"></span></h3>
                </div>
                <div class="row ms-5">
                    <div class="col-5">
                        <ul class="list-unstyled">
                            <li class="h4 text-black mt-1">Fecha de inicio:</li>
                            <li class="h4 text-black mt-1">Fecha de fin:</li>
                            <li class="h4 text-black mt-1">Monto:</li>
                        </ul>
                    </div>
                    <div class="col-5">
                        <ul class="list-unstyled">
                            <li class="h4 text-black mt-1"><span class="text-muted" id="iniMostrar"></span></li>
                            <li class="h4 text-black mt-1"><span class="text-muted" id="finMostrar"></span></li>
                            <li class="h4 text-black mt-1"><span class="text-muted" id="maxMostrar"></span></li>
                        </ul>
                    </div>
                </div>
                <div class="row m-3">
                    <?php
                    // Crear un array para almacenar los montos por mes
                    $montosPorMes = array_fill(1, 12, 0);
                    var_dump($sentencia);

                    // Llenar el array con los resultados de la consulta
                    foreach ($sentencia as $fila) {
                        $montosPorMes[$fila['mes']] = $fila['total_mes'];
                    }

                    // Recorrer el array $meses
                    foreach ($meses as $numero => $nombre) {
                        if ($nombre != "") {
                            if ($montosPorMes[$numero] != 0) {
                                // Mostrar el nombre del mes y el monto correspondiente
                                echo "<div class='col-9'><p>$nombre</p></div><div class='col-3'><p class='float-end'>$" . $montosPorMes[$numero] . " </p></div> <hr>";
                            }
                        }
                    }
                    ?>
                </div>
                <div class="row text-black">
                    <hr style="border: 2px solid black;">
                    <div class="col-xl-12">
                        <p class="float-end fw-bold">Total: $10.00
                        </p>
                    </div>
                    <hr style="border: 2px solid black;">
                </div>

            </div>
        </div>
    </div>
</div>
</div>