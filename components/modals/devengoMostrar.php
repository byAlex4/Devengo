<?php
$error = false;
$config = include '../config.php';
?>
<div class="modal-content">
    <div class="modal-header">
        <h2 class="modal-title" id="crearModalLabel">Informacion del contrato</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="card-body mb-5" style='margin-left: 5%; margin-right: 5%; padding-bottom: 5%;'>
            <div class="mb-4" style="place-content: center;" id="contratoMostrar">
            </div>
            <button id="btnExportar2" class="btn btn-primary btn-lg" type="button"
                style="background-color: #2a8f60; border-color:#8bc6a8; position: absolute; right : 10%;">Exportar</button>
        </div>
    </div>
</div>
</div>