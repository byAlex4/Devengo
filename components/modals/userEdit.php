<div class="modal-header">
    <h2 class="modal-title" id="editarModalLabel">Editar usuario</h2>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <h4 class="h1 txtEdit">Modificar los datos del usuario:
    </h4>
    <div class="container">
        <div class="col">
            <form id="formEdit" method="post">
                <div class="form-floating mb-3">
                    <input type="text" name="idEdit" id="idEdit" data-id=show class="form-control" value="" disabled>
                    <label for="idEdit" style="color: gray">ID</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="nombreEdit" id="nombreEdit" data-id="editar" class="form-control">
                    <label for="nombreEdit" style="color: gray">Nombre</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" name="unidadEdit" id="unidadEdit" id="unidad"
                        aria-label="Floating label select example">
                        <option selected>Selecciona una unidad</option>
                        <?php
                        $consultaUnidades = "SELECT id, nombre FROM unidades";
                        $sentenciaUnidades = $conexion->prepare($consultaUnidades);
                        $sentenciaUnidades->execute();
                        foreach ($sentenciaUnidades as $unidad) { ?>
                            <option value="<?php echo ($unidad["id"]); ?>">
                                <?php echo ($unidad["nombre"]); ?>
                            </option>
                            <?php
                        } ?>
                    </select>
                    <label for="unidadEdit">Unidad</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="matriculaEdit" id="matriculaEdit" class="form-control">
                    <label for="matriculaEdit" style="color: gray">Matricula</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="contraseñaEdit" id="contraseñaEdit" class="form-control">
                    <label for="contraseñaEdit" style="color: gray">Contraseña</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" name="rolEdit" id="rolEdit" aria-label="Floating label select example">
                        <option selected>Selecciona un rol</option>
                        <?php
                        $consultaRoles = "SELECT id, nombre FROM roles";
                        $sentenciaRoles = $conexion->prepare($consultaRoles);
                        $sentenciaRoles->execute();
                        foreach ($sentenciaRoles as $rol) { ?>
                            <option value="<?php echo ($rol["id"]); ?>">
                                <?php echo ($rol["nombre"]); ?>
                            </option>
                            <?php
                        } ?>
                    </select>
                    <label for="rolEdit">Rol</label>
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