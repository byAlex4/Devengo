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
    $consultaSQL = "SELECT DATE_FORMAT(m.fecha, '%Y-%m') AS mes, 
    COALESCE(t.total_mes, 500) AS total_mes, 
    SUM(COALESCE(t.total_mes, 500)) OVER (ORDER BY m.fecha) AS total_acumulado 
    FROM ( SELECT LAST_DAY(CURDATE()) - INTERVAL (11 - n) MONTH AS fecha 
    FROM ( SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 
    4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 
    9 UNION ALL SELECT 10 UNION ALL SELECT 11 ) AS numbers ) AS m 
    LEFT JOIN ( SELECT SUM(monto) AS total_mes, DATE_FORMAT(fecha, '%Y-%m') AS mes 
    FROM devengos WHERE devengos.contratoID = 1 GROUP BY mes ) AS t ON DATE_FORMAT(m.fecha, '%Y-%m') = t.mes;";

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

                    // Verificar si hay resultados
                    if ($sentencia->rowCount() > 0) {
                        // Crear una tabla para mostrar los datos
                        echo "<div class='col-4'><p>Fecha</p></div><div class='col-4'><p class='float-end'>Total del mes</p></div> <div class='col-4'><p class='float-end'>Total acumulado</p></div><hr>";
                        // Recorrer los resultados y mostrarlos en la tabla
                        while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {

                            echo "<div class='col-4'><p>" . $fila["mes"] . "</p></div><div class='col-4'><p class='float-end'>$" . $fila["total_mes"] . " </p></div> <div class='col-4'><p class='float-end'>$" . $fila["total_acumulado"] . " </p></div><hr>";
                        }
                    } else {
                        // No hay resultados
                        echo "No se encontraron datos";
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