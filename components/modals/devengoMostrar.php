<?php
$error = false;
$config = include '../config.php';

try {
    $consultaSQL = "SELECT SUM(monto), month(fecha) 
    FROM `devengos` 
    GROUP BY month(fecha) 
    ORDER BY month(fecha) ASC";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

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
            <div class="container">
                <div class="row">
                    <h3>Contrato: <span id="claveMostrar"></span></h3>
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li class="h4 text-black mt-1">Fecha de inicio:</li>
                            <li class="h4 text-black mt-1">Fecha de fin:</li>
                            <li class="h4 text-black mt-1">Monto:</li>
                        </ul>
                    </div>
                    <div class="col-4">
                        <ul class="list-unstyled">
                            <li class="h4 text-black mt-1"><span class="text-muted" id="iniMostrar"></span></li>
                            <li class="h4 text-black mt-1"><span class="text-muted" id="finMostrar"></span></li>
                            <li class="h4 text-black mt-1"><span class="text-muted" id="maxMostrar"></span></li>
                        </ul>
                    </div>
                    <?php
                    while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
                        $monto = $fila['SUM(monto)'];
                        $mes = $fila['month(fecha)'];
                        $nombre_mes = $meses[$mes];
                        echo "<hr><div class='col-10'><p>$nombre_mes</p></div><div class='col-2'><p class='float-end'>$monto</p></div>";
                    }
                    ?>
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