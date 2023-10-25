<div class="modal-content">
    <div class="modal-header">
        <h2 class="modal-title" id="editarModalLabel">Editar devengo</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <h4 class="txtEdit">Modificar los datos:
        </h4>
        <div class="container">
            <div class="col">
                <form id="formEdit" method="post">
                    <div class="form-floating mb-3">
                        <input type="text" name="idEdit" id="idEdit" data-id=show class="form-control" value=""
                            disabled>
                        <label for="idEdit" style="color: gray">ID</label>
                    </div>
                     <div class="form-floating mb-3">
                        <input type="txt" id="proveedorEdit" class="form-control">
                        <label for="proveedorEdit" style="color: gray">Proveedor</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="date" id="fechaEdit" class="form-control">
                        <label for="fechaCrear" style="color: gray">Fecha de cargo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="contratoEdit" aria-label="Floating label select example">
                            <option selected>Selecciona el contrato</option>
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
                        <label for="contratoEdit">Contrato</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="txt" id="montoEdit" class="form-control">
                        <label for="montoCrear" style="color: gray">Monto</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="txt" id="descEdit" class="form-control">
                        <label for="descEdit" style="color: gray">Descripcion</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="usuarioEdit" aria-label="Floating label select example">
                            <option selected>Selecciona al usuario</option>
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
                        <label for="usuarioEdit">Usuario registrado</label>
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
</div>