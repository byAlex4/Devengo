<div class="modal-content">
    <div class="modal-header">
        <h2 class="modal-title" id="crearModalLabel">Crear un devengo</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <h4>Ingresa los siguentes datos</h4>

        <div class="container">
            <div class="col">
                <form method="post">
                    <div class="form-floating mb-3">
                        <input type="date" id="fechaCrear" class="form-control">
                        <label for="fechaCrear" style="color: gray">Fecha de cargo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="txt" id="proveedorCrear" class="form-control">
                        <label for="proveedorCrear" style="color: gray">Proveedor</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="contratoCrear" aria-label="Floating label select example">
                            <option value="0" selected>Selecciona el contrato</option>
                            <?php
                            $consultaContrato = "SELECT id, clave FROM contratos WHERE fecha_fin > CURDATE();";
                            $sentenciaContrato = $conexion->prepare($consultaContrato);
                            $sentenciaContrato->execute();
                            foreach ($sentenciaContrato as $contrato) { ?>
                                <option value="<?php echo ($contrato["id"]); ?>">
                                    <?php echo ($contrato["clave"]); ?>
                                </option>
                                <?php
                            } ?>
                        </select>
                        <label for="contratoCrear">Contrato</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="txt" id="montoCrear" class="form-control">
                        <label for="montoCrear" style="color: gray">Monto</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="txt" id="descCrear" class="form-control">
                        <label for="descCrear" style="color: gray">Descripcion</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="usuarioCrear" aria-label="Floating label select example">
                            <option value="0" selected>Selecciona al usuario</option>
                            <?php
                            $consultaUsuario = "SELECT id, nombre FROM usuarios";
                            $sentenciaUsuario = $conexion->prepare($consultaUsuario);
                            $sentenciaUsuario->execute();
                            foreach ($sentenciaUsuario as $user) { ?>
                                <option value="<?php echo ($user["id"]); ?>">
                                    <?php echo ($user["nombre"]); ?>
                                </option>
                                <?php
                            } ?>
                        </select>
                        <label for="usuarioCrear">Usuario registrado</label>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input type="submit" name="crear" class="btn btn-primary crear" value="Crear">
                            <a class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>