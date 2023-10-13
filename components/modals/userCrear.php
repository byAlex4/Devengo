<div class="modal-content">
    <div class="modal-header">
        <h2 class="modal-title" id="crearModalLabel">Crear un usuario nuevo</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <h4>Ingresa los siguentes datos</h4>

        <div class="container">
            <div class="col">
                <form method="post">
                    <div class="form-floating mb-3">
                        <input type="text" id="nombreCrear" class="form-control">
                        <label for="nombreCrear" style="color: gray">Nombre</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" id="unidadCrear" aria-label="Floating label select example">
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
                        <label for="unidadCrear">Unidad</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="matriculaCrear" class="form-control">
                        <label for="matriculaCrear" style="color: gray">Matricula</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" id="contraseñaCrear" class="form-control"
                        pattern=“.{8,}” title='La contraseña debe tener al menos 8 caracteres'>
                        <label for="contraseñaCrear" style="color: gray">Contraseña</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="rolCrear" id="rolCrear"
                            aria-label="Floating label select example">
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
                        <label for="rolCrear">Rol</label>
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