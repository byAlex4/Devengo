<div class="modal-content">
    <div class="modal-header">
        <h2 class="modal-title" id="crearModalLabel">Agregar una cuenta</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <h4>Ingresa los siguentes datos</h4>
        <div class="container">
            <div class="col">
                <form method="post">
                    <div class="form-floating mb-3">
                        <input type="text" id="numeroCrear" class="form-control">
                        <label for="numeroCrear" style="color: gray">Numero de cuenta</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" id="desCrear" class="form-control">
                        <label for="desCrear" style="color: gray">Descripcion</label>
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