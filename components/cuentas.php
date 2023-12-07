<?php include_once("./template/header.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<script>document.title = "Cuentas | Devengo";</script>

<main class="bodymain">
    <div class="mt-3">
        <h1>Página de cuentas</h1>
        <div class="col-md mb-3" style="text-align: right;">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#crearModal"
                style="background-color: #2a8f60; border-color:#8bc6a8;">
                Crear cuenta
            </button>
        </div>
        <div style="overflow-x: auto; display: grid">
            <form action="post">
                <div class="input-group mb-3">
                    <span class="input-group-text">Cuenta</span>
                    <input type="text" class="form-control" id="buscarCuenta" style="min-width: 100px;">
                    <span class="input-group-text">Descripcion</span>
                    <input type="text" class="form-control" id="buscarDesc" style="min-width: 100px;">
                    <button class="btn btn-outline-secondary buscar" name="submit" type="button">Buscar</button>
                </div>
            </form>
            <table class="table table-hover" id="tablaCuentas"
                style="background-color: #e4f7e8; margin-top: 15%; opacity: 0.2">
                <thead class="thead-primary" style="background-color: #a1d6aa; width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Cuenta</th>
                        <th>Descripcion</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modals -->
    <div class="modal fade" id="crearModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="crearModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <?php include "modals/cuentaCrear.php"; ?>
        </div>
    </div>
    <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editarModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <?php include "modals/cuentaEdit.php"; ?>
            </div>
        </div>
    </div>
</main>
<!-- Script para manejar el evento de clic del botón -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // Función para cargar los datos
    function cargarDatos() {
        // Vaciamos el cuerpo de la tabla
        $("tbody").empty();
        // Hacemos una petición GET=
        $.ajax({
            url: 'funciones/cuentasDatos.php',
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                var table = $('#tablaCuentas');
                if (data.length <= 0) {
                    $("tbody").empty();
                    var fila = "<tr>" +
                        "<td colspan='11'>No se encontraron cuentas</td>" +
                        "</tr>";
                    $("tbody").append(fila);
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                } else {
                    $.each(data, function (i, item) {
                        // Creamos una fila con los datos de cada usuario
                        var fila = "<tr>" +
                            "<td>" + item.id + "</td>" +
                            "<td>" + item.cuenta + "</td>" +
                            "<td>" + item.descripcion + "</td>" +
                            "<td>" + item.created_at + "</td>" +
                            "<td>" + item.updated_at + "</td>" +
                            "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                            "</tr>";
                        $("tbody").append(fila);
                    });
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                }
            }
        });
    }

    // Función para cargar los datos al iniciar la página
    $(document).ready(function () {
        cargarDatos();
    });

    // Detectar la tecla Enter en cualquier input del formulario
    $('#buscarCuenta, #buscarDesc').keypress(function (e) {
        // Obtener el código de la tecla presionada
        var code = e.which;
        // Si es igual a 13 (Enter)
        if (code == 13) {
            // Evitar el comportamiento por defecto del navegador
            e.preventDefault();
            // Simular un click en el botón buscar
            $('.buscar').click();
        }
    });

    //Funcion de buscar cuenta
    $(document).on('click', '.buscar', function (e) {
        var cuenta = $('#buscarCuenta').val();
        var descripcion = $('#buscarDesc').val();
        console.log(cuenta, descripcion);
        $.ajax({
            url: 'funciones/cuentasPost.php',
            type: 'POST',
            data: {
                'bscNumero': cuenta,
                'bscDesc': descripcion
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                var table = $('#tablaCuentas');
                table.animate({ marginTop: '15%', opacity: '0.2' }, "slow");
                // Vaciamos el cuerpo de la tabla 
                $("tbody").empty();
                if (data.length <= 0) {
                    $("tbody").empty();
                    var fila = "<tr>" +
                        "<td colspan='11'>No se encontraron usuarios</td>" +
                        "</tr>";
                    $("tbody").append(fila);
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                } else {
                    $.each(data, function (i, item) {
                        // Creamos una fila con los datos de cada usuario
                        var fila = "<tr>" +
                            "<td>" + item.id + "</td>" +
                            "<td>" + item.cuenta + "</td>" +
                            "<td>" + item.descripcion + "</td>" +
                            "<td>" + item.created_at + "</td>" +
                            "<td>" + item.updated_at + "</td>" +
                            "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                            "</tr>";
                        $("tbody").append(fila);
                    });
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                }
            }
        })
    });

    //Funcion de crear usuario
    $(document).on('click', '.crear', function (e) {
        e.preventDefault();
        var cuenta = $('#numeroCrear').val();
        var descripcion = $('#desCrear').val();
        $.ajax({
            url: 'funciones/cuentasPost.php',
            type: 'POST',
            data: {
                'cuenta': cuenta,
                'desc': descripcion
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'La cuenta ha sido agregada',
                    icon: 'success'
                });
                var table = $('#tablaCuentas');
                table.animate({ opacity: '0', marginTop: '15%' }, "slow");
                cargarDatos();
                $('#numeroCrear').val("");
                $('#desCrear').val("");
            },
            error: function (data) {
                console.log(data);
                Swal.fire({
                    title: 'Error!',
                    text: 'Algo no salio bien, vuelve a intentar',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                })
            }
        });
    });

    //Funcion para mostrar datos de la cuenta seleccionado para editar
    $(document).on('click', '.shw', function () {
        var id = $(this).data("id");
        $.ajax({
            url: 'funciones/cuentasPost.php',
            type: 'POST',
            data: { 'shw': id },
            dataType: 'JSON',
            success: function (data) {
                $('#formEdit').val(data.id);
                $('#idEdit').val(data.id);
                $('#cuentaEdit').val(data.cuenta);
                $('#descEdit').val(data.descripcion);
            }
        })
    });

    $(document).on('click', '.editar', function (e) {
        e.preventDefault();
        var id = $('#idEdit').val();
        var cuenta = $('#cuentaEdit').val();
        var descripcion = $('#descEdit').val();
        $.ajax({
            url: 'funciones/cuentasPost.php',
            type: 'POST',
            data: {
                'editar': id,
                'cuenta': cuenta,
                'desc': descripcion
            },
            dataType: 'JSON',
            success: function (data) {
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'Los cambios han sido guardados',
                    icon: 'success'
                });
                console.log(data);
                var table = $('#tablaCuentas');
                table.animate({ opacity: '0', marginTop: '15%' }, "slow");
                cargarDatos();
            },
            error: function (data) {
                console.log(data);
                Swal.fire({
                    title: 'Error!',
                    text: 'Algo no salio bien, vuelve a intentar',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                })
            }
        });
    });
</script>

<?php include_once("./template/footer.php") ?>