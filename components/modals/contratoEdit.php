<div class="modal-header">
    <h2 class="modal-title" id="editarModalLabel">Editar contrato</h2>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <h4 class="txtEdit">Modificar los datos:</h4>

    <div class="container">
        <div class="col">
            <form id="formEdit" method="post">
                <div class="form-floating mb-3">
                    <input type="text" id="idEdit" data-id=show class="form-control" disabled>
                    <label for="idEdit" style="color: gray">ID</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="cuentaEdit" aria-label="Floating label select example">
                        <option value="0" selected>Selecciona la cuenta</option>
                        <?php
                        $consultaCuenta = "SELECT id, cuenta FROM cuentas;";
                        $sentenciaCuenta = $conexion->prepare($consultaCuenta);
                        $sentenciaCuenta->execute();
                        foreach ($sentenciaCuenta as $cuenta) { ?>
                            <option value="<?php echo ($cuenta["id"]); ?>">
                                <?php echo ($cuenta["cuenta"]); ?>
                            </option>
                            <?php
                        } ?>
                    </select>
                    <label for="cuentaEdit">Cuenta</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="claveEdit" class="form-control">
                    <label for="claveEdit" style="color: gray">Folio</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="desEdit" class="form-control">
                    <label for="desEdit" style="color: gray">Descripcion</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="provEdit" class="form-control">
                    <label for="provEdit" style="color: gray">Proveedor</label>
                </div>
                <div class='row form-row'>
                    <div class="form-floating mb-3 col-5">
                        <input type="text" id="maxEdit" class="form-control">
                        <label for="maxEdit" style="color: gray">Monto maximo</label>
                    </div>
                    <div class="form-floating mb-3 col-6">
                        <input type="text" id="minEdit" class="form-control">
                        <label for="minEdit" style="color: gray">Monto minimo</label>
                    </div>
                </div>
                <div class='row form-row'>
                    <div class="form-floating mb-3 col-5">
                        <input type="date" id="iniEdit" class="form-control">
                        <label for="iniEdit" style="color: gray">Fecha inicio</label>
                    </div>
                    <div class="form-floating mb-3 col-6">
                        <input type="date" id="finEdit" class="form-control">
                        <label for="finEdit" style="color: gray">Fecha final</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" name="editar" class="btn btn-primary editar" value="Editar">
                        <a class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>