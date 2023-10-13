<?php include_once("./template/header.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<main class="bodymain">
    <div class="mt-3">
        <h1>Página de unidades</h1>
        <div style="overflow-x: auto;">
            <table class="table table-hover" id="tablaUnidades"
                style="background-color: #e4f7e8; margin-top: 15%; opacity: 0.2">
                <thead class="thead-primary" style="background-color: #a1d6aa; width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripcipón</th>
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
    <!-- Modal -->
    <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editarModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <?php include "modals/unidadEdit.php"; ?>
            </div>
        </div>
    </div>
</main>
<!-- Script para manejar el evento de clic del botón -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    // Función para cargar los datos
    function cargarDatos() {
        // Vaciamos el cuerpo de la tabla
        $("tbody").empty();
        // Hacemos una petición GET
        $.ajax({
            url: "funciones/unidadDatos.php",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $.each(data, function (i, item) {
                    // Creamos una fila con los datos de cada conjtrato
                    var fila = "<tr>" +
                        "<td>" + item.id + "</td>" +
                        "<td>" + item.nombre + "</td>" +
                        "<td>" + item.descripcion + "</td>" +
                        "<td>" + item.created_at + "</td>" +
                        "<td>" + item.updated_at + "</td>" +
                        "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                        "</tr>";
                    $("tbody").append(fila);
                });
                var table = $('#tablaUnidades');
                table.animate({ opacity: '1', marginTop: '0' }, "slow");
            }
        });
    }

    // Función para cargar los datos al iniciar la página
    $(document).ready(function () {
        cargarDatos();
    });

    //Funcion para mostrar datos del contrato seleccionado para editar
    $(document).on('click', '.shw', function () {
        var id = $(this).data("id");
        console.log(id);
        $.ajax({
            url: 'funciones/unidadPost.php',
            type: 'POST',
            data: { 'shw': id },
            dataType: 'JSON',
            success: function (data) {
                console.log(data)
                $('#formEdit').val(data.id);
                $('#idEdit').val(data.id);
                $('#nombreEdit').val(data.nombre);
                $('#desEdit').val(data.descripcion);
            }
        })
    });

    $(document).on('click', '.editar', function (e) {
        e.preventDefault();
        var id = $('#idEdit').val();
        var nombre = $('#nombreEdit').val();
        var descripcion = $('#desEdit').val();
        console.log(id, nombre, descripcion);
        $.ajax({
            url: 'funciones/unidadPost.php',
            type: 'POST',
            data: {
                'editar': id,
                'nombre': nombre,
                'descripcion': descripcion,
            },
            dataType: 'JSON',
            success: function (data) {
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'Los cambios han sido guardados',
                    icon: 'success'
                });
                console.log(data);
                var table = $('#tablaUnidades');
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