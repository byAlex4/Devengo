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
    $consultaSQL = "SELECT SUM(monto) AS total, month(fecha) AS mes 
    FROM `devengos` 
    WHERE devengos.contratoID = 1
    GROUP BY month(fecha) 
    ORDER BY month(fecha) ASC";

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

                    // Llenar el array con los resultados de la consulta
                    foreach ($sentencia as $fila) {
                        $montosPorMes[$fila['mes']] = $fila['total'];
                    }

                    // Recorrer el array $meses
                    foreach ($meses as $numero => $nombre) {
                        if ($nombre != "") {
                            // Mostrar el nombre del mes y el monto correspondiente
                            echo "<div class='col-9'><p>$nombre</p></div><div class='col-3'><p class='float-end'>$" . $montosPorMes[$numero] . " </p></div> <hr>";
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