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
                    <input type="text" name="cuentaEdit" id="cuentaEdit" data-id="editar" class="form-control">
                    <label for="cuentaEdit" style="color: gray">Cuenta</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="descEdit" id="descEdit" class="form-control">
                    <label for="descEdit" style="color: gray">Descripcion</label>
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