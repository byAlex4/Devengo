<?php include_once("./template/header.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<main class="bodymain">
    <div class="mt-3">
        <h1>Página de devengos</h1>
        <div class="col-md mb-3" style="text-align: right;">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#crearModal"
                style="background-color: #2a8f60; border-color:#8bc6a8;">
                Crear devengo
            </button>
        </div>
        <div style="overflow-x: auto;">
            <table class="table table-hover" id="tablaDevengos"
                style="background-color: #e4f7e8; margin-top: 15%; opacity: 0.2">
                <thead class="thead-primary" style="background-color: #a1d6aa; width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Fecha de cargo</th>
                        <th>Descripcion</th>
                        <th>Monto</th>
                        <th>Contrato</th>
                        <th>Saldo de contrato</th>
                        <th>Saldo disponible</th>
                        <th>Unidad</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class='DatosDevengo'>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modals -->
    <div class="modal fade" id="crearModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="crearModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <?php include "modals/devengoCrear.php"; ?>
        </div>
    </div>
    <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editarModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <?php include "modals/devengoEdit.php"; ?>
        </div>
    </div>
    <div class="modal fade" id="mostrarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="mostrarModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <?php include "modals/devengoMostrar.php"; ?>
        </div>
    </div>
    <button type='button' class='btn btn-link' data-bs-toggle='tooltip' data-bs-placement='top'
        data-bs-title='Tooltip on top'>tool</button>
</main>


<!-- Script para manejar el evento de clic del botón -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    // Función para cargar los datos
    function cargarDatos() {
        // Vaciamos el cuerpo de la tabla
        $(".DatosDevengo").empty();
        // Hacemos una petición GET=
        $.ajax({
            url: 'funciones/devengoDatos.php',
            type: "GET",
            //dataType: "JSON",
            success: function (data) {
                $.each(data, function (i, item) {
                    // Creamos una fila con los datos de cada usuario
                    var fila = "<tr>" +
                        "<td>" + item.id + "</td>" +
                        "<td>" + item.fecha + "</td>" +
                        "<td>" + item.descripcion + "</td>" +
                        "<td>" + item.monto + "</td>" +
                        "<td> <button type='button' class='btn contr btn-link' data-bs-toggle='modal' data-bs-target='#mostrarModal' data-id='" + item.contrato + "'>" + item.contrato + "</button> </td>" +
                        "<td>" + item.saldo + "</td>" +
                        "<td>" + item.saldoDis + "</td>" +
                        "<td>" + item.unidad + "</td>" +
                        "<td>" + item.created_at + "</td>" +
                        "<td>" + item.updated_at + "</td>" +
                        "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                        "</tr>";
                    $(".DatosDevengo").append(fila);
                });
                var table = $('#tablaDevengos');
                table.animate({ opacity: '1', marginTop: '0' }, "slow");
            }
        });
    }

    // Función para cargar los datos al iniciar la página
    $(document).ready(function () {
        cargarDatos();
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });

    //Funcion de crear usuario
    $(document).on('click', '.crear', function (e) {
        e.preventDefault();
        $("DatosDevengo").empty();
        var fecha = $('#fechaCrear').val();
        var descripcion = $('#descCrear').val();
        var monto = $('#montoCrear').val();
        var usuario = $('#usuarioCrear').val();
        var clave = $('#contratoCrear').val();
        console.log(fecha, monto, descripcion, usuario, clave);
        $.ajax({
            url: 'funciones/devengoPost.php',
            type: 'POST',
            data: {
                'fecha': fecha,
                'monto': monto,
                'descripcion': descripcion,
                'usuario': usuario,
                'clave': clave
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'El usuario ha sido creado',
                    icon: 'success'
                });
                var table = $('#tablaDevengos');
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
            url: 'funciones/devengoPost.php',
            type: 'POST',
            data: { 'shw': id },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                $('#formEdit').val(data.id);
                $('#idEdit').val(data.id);
                $('#fechaEdit').val(data.fecha);
                $('#contratoEdit').val(data.clave);
                $('#montoEdit').val(data.monto);
                $('#descEdit').val(data.desc);
                $('#usuarioEdit').val(data.usuario);
            }
        })
    });

    $(document).on('click', '.contr', function () {
        var clave = $(this).data("id");
        console.log(clave);
        $.ajax({
            url: 'funciones/devengoPost.php',
            type: 'POST',
            data: { 'contr': clave },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                $('#formMostrar').val(data.clave);
                $('#claveMostrar').text(data.clave);
                $('#descMostrar').text(data.desc);
                $('#maxMostrar').text(data.mont_max);
                $('#saldoMostrar').text(data.saldo);
                $('#minMostrar').text(data.min);
                $('#iniMostrar').text(data.fecha_in);
                $('#finMostrar').text(data.fecha_fin);
            }
        })
    });

    $(document).on('click', '.editar', function (e) {
        e.preventDefault();
        var id = $('#idEdit').val();
        var fecha = $('#fechaEdit').val();
        var descripcion = $('#descEdit').val();
        var clave = $('#contratoEdit').val();
        var monto = $('#montoEdit').val();
        var usuario = $('#usuarioEdit').val();
        console.log(id, fecha, monto, usuario, clave);
        $.ajax({
            url: 'funciones/devengoPost.php',
            type: 'POST',
            data: {
                'editar': id,
                'fecha': fecha,
                'descripcion': descripcion,
                'clave': clave,
                'monto': monto,
                'usuario': usuario
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'Los cambios han sido guardados',
                    icon: 'success'
                });
                var table = $('#tablaDevengos');
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