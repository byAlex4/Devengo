<div class="modal-header">
    <h2 class="modal-title" id="crearModalLabel">Crear un nuevo contrato</h2>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <h4>Ingresa los siguentes datos</h4>

    <div class="container">
        <div class="col">
            <form method="post">
                <div class="form-floating mb-3">
                    <input type="text" id="claveCrear" class="form-control">
                    <label for="claveCrear" style="color: gray">Clave</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="desCrear" class="form-control">
                    <label for="desCrear" style="color: gray">Descripcion</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="maxCrear" class="form-control">
                    <label for="maxCrear" style="color: gray">Monto maximo</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" id="minCrear" class="form-control">
                    <label for="minCrear" style="color: gray">Monto minimo</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" id="iniCrear" class="form-control">
                    <label for="iniCrear" style="color: gray">Fecha inicio</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="date" id="finCrear" class="form-control">
                    <label for="finCrear" style="color: gray">Fecha final</label>
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