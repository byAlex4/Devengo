<?php include_once("./template/header.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
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

        <div style="overflow-x: auto;">
            <table class="table table-hover" id="tablaUsuarios"
                style="background-color: #e4f7e8; margin-top: 15%; opacity: 0.2">
                <thead class="thead-primary" style="background-color: #a1d6aa; width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Matricula</th>
                        <th>Nombre</th>
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