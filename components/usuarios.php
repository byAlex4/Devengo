<?php include_once("./template/header.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<script>document.title = "Usuarios | Devengo";</script>

<main class="bodymain">
    <div class="mt-3">
        <h1>Página de usuarios</h1>
        <div class="col-md mb-3" style="text-align: right;">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#crearModal"
                style="background-color: #2a8f60; border-color:#8bc6a8;">
                Crear usuario
            </button>
        </div>
        <div style="overflow-x: auto; display: grid">
            <form action="post">
                <div class="input-group mb-3">
                    <span class="input-group-text">Matricula</span>
                    <input type="text" class="form-control" id="buscarMatri" style="min-width: 100px;">
                    <span class="input-group-text">Nombre</span>
                    <input type="text" class="form-control" id="buscarNombre" style="min-width: 100px;">
                    <span class="input-group-text">Unidad</span>
                    <input type="text" class="form-control" id="buscarUnidad" style="min-width: 100px;">
                    <span class="input-group-text">Rol</span>
                    <input type="text" class="form-control" id="buscarRol" style="min-width: 100px;">
                    <button class="btn btn-outline-secondary buscar" name="submit" type="button">Buscar</button>
                </div>
            </form>
            <table class="table table-hover" id="tablaUsuarios"
                style="background-color: #e4f7e8; margin-top: 15%; opacity: 0.2">
                <thead class="thead-primary" style="background-color: #a1d6aa; width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Matricula</th>
                        <th>Nombre</th>
                        <th>Monto</th>
                        <th>Unidad</th>
                        <th>Rol</th>
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
            <?php include "modals/userCrear.php"; ?>
        </div>
    </div>
    <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editarModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <?php include "modals/userEdit.php"; ?>
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
            url: 'funciones/userDatos.php',
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $.each(data, function (i, item) {
                    // Creamos una fila con los datos de cada usuario
                    var fila = "<tr>" +
                        "<td>" + item.id + "</td>" +
                        "<td>" + item.matricula + "</td>" +
                        "<td>" + item.nombre + "</td>" +
                        "<td>" + item.monto + "</td>" +
                        "<td>" + item.unidad + "</td>" +
                        "<td>" + item.rol + "</td>" +
                        "<td>" + item.created_at + "</td>" +
                        "<td>" + item.updated_at + "</td>" +
                        "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                        "</tr>";
                    $("tbody").append(fila);
                });
                var table = $('#tablaUsuarios');
                table.animate({ opacity: '1', marginTop: '0' }, "slow");
            }
        });
    }

    // Función para cargar los datos al iniciar la página
    $(document).ready(function () {
        cargarDatos();
    });

    // Detectar la tecla Enter en cualquier input del formulario
    $('#buscarMatri, #buscarNombre, #buscarUnidad, #buscarRol').keypress(function (e) {
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

    //Funcion de crear usuario
    $(document).on('click', '.buscar', function (e) {
        var matricula = $('#buscarMatri').val();
        var nombre = $('#buscarNombre').val();
        var unidad = $('#buscarUnidad').val();
        var rol = $('#buscarRol').val();
        console.log(matricula, nombre, unidad, rol);
        $.ajax({
            url: 'funciones/userPost.php',
            type: 'POST',
            data: {
                'bscNombre': nombre,
                'bscUnidad': unidad,
                'bscMatricula': matricula,
                'bscRol': rol
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                var table = $('#tablaUsuarios');
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
                            "<td>" + item.matricula + "</td>" +
                            "<td>" + item.nombre + "</td>" +
                            "<td>" + item.monto + "</td>" +
                            "<td>" + item.unidad + "</td>" +
                            "<td>" + item.rol + "</td>" +
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
        var nombre = $('#nombreCrear').val();
        var unidad = $('#unidadCrear').val();
        var matricula = $('#matriculaCrear').val();
        var contraseña = $('#contraseñaCrear').val();
        var rol = $('#rolCrear').val();
        $.ajax({
            url: 'funciones/userPost.php',
            type: 'POST',
            data: {
                'nombre': nombre,
                'unidad': unidad,
                'matricula': matricula,
                'contraseña': contraseña,
                'rol': rol
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'El usuario ha sido creado',
                    icon: 'success'
                });
                var table = $('#tablaUsuarios');
                table.animate({ opacity: '0', marginTop: '15%' }, "slow");
                cargarDatos();
                $('#nombreCrear').val("");
                $('#unidadCrear').val(0);
                $('#matriculaCrear').val("");
                $('#contraseñaCrear').val("");
                $('#rolCrear').val(0);
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

    //Funcion para mostrar datos del usuario seleccionado para editar
    $(document).on('click', '.shw', function () {
        var id = $(this).data("id");
        console.log(id);
        $.ajax({
            url: 'funciones/userPost.php',
            type: 'POST',
            data: { 'shw': id },
            dataType: 'JSON',
            success: function (data) {
                $('#formEdit').val(data.id);
                $('#idEdit').val(data.id);
                $('#nombreEdit').val(data.nombre);
                $('#unidadEdit').val(data.unidadID);
                $('#matriculaEdit').val(data.matricula);
                $('#contraseñaEdit').val(data.contra);
                $('#rolEdit').val(data.rolID);
            }
        })
    });

    $(document).on('click', '.editar', function (e) {
        e.preventDefault();
        var id = $('#idEdit').val();
        var nombre = $('#nombreEdit').val();
        var unidad = $('#unidadEdit').val();
        var matricula = $('#matriculaEdit').val();
        var contraseña = $('#contraseñaEdit').val();
        var rol = $('#rolEdit').val();
        console.log(id, nombre, unidad, matricula, contraseña, rol);
        $.ajax({
            url: 'funciones/userPost.php',
            type: 'POST',
            data: {
                'editar': id,
                'nombre': nombre,
                'unidad': unidad,
                'matricula': matricula,
                'contra': contraseña,
                'rol': rol
            },
            dataType: 'JSON',
            success: function (data) {
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'Los cambios han sido guardados',
                    icon: 'success'
                });
                console.log(data);
                var table = $('#tablaUsuarios');
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