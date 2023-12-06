<?php include_once("./template/header.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<script>document.title = "Devengos | Devengo";</script>
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
        <div style="overflow-x: auto; display: grid">
            <form action="post">
                <div class="input-group mb-4">
                    <span class=" input-group-text">Descripcion</span>
                    <input type="text" class="form-control" id="bscDesc" style="min-width: 100px;">
                    <span class="input-group-text">Montos</span>
                    <input type="text" class="form-control" id="bscMonto" style="min-width: 100px;">
                    <span class="input-group-text">Contratos</span>
                    <input type="text" class="form-control" id="bscContrato" style="min-width: 100px;">
                    <span class="input-group-text">Unidades</span>
                    <input type="text" class="form-control" id="bscUnidad" style="min-width: 100px;">
                    <span class="input-group-text">Fecha</span>
                    <input type="month" class="form-control" id="bscFecha" style="min-width: 100px;">
                    <button class="btn btn-outline-secondary buscar" name="submit" type="button">Buscar</button>
                </div>
            </form>
            <table class="table table-hover" id="DatosDevengo"
                style="background-color: #e4f7e8; margin-top: 15%; opacity: 0.2">
                <thead class="thead-primary" style="background-color: #a1d6aa; width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Proveedor</th>
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
            dataType: "JSON",
            success: function (data) {
                if (data.length <= 0) {
                    var fila = "<tr>" +
                        "<td colspan='11'>No se encontraron devengos</td>" +
                        "</tr>";
                    $("tbody").append(fila);
                    var table = $('#DatosDevengo');
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                } else {
                    $.each(data, function (i, item) {
                        // Creamos una fila con los datos de cada usuario
                        var fila = "<tr>" +
                            "<td>" + item.id + "</td>" +
                            "<td>" + item.proveedor + "</td>" +
                            "<td>" + item.fecha + "</td>" +
                            "<td>" + item.descripcion + "</td>" +
                            "<td>$" + item.monto_formato + "</td>" +
                            "<td> <button type='button' class='btn contr btn-link' data-bs-toggle='modal' data-bs-target='#mostrarModal' data-id='" + item.contratoID + "'>" + item.contrato + "</button> </td>" +
                            "<td>$" + item.saldo + "</td>" +
                            "<td>$" + item.saldoDis + "</td>" +
                            "<td>" + item.unidad + "</td>" +
                            "<td>" + item.created_at + "</td>" +
                            "<td>" + item.updated_at + "</td>" +
                            "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                            "</tr>";
                        $(".DatosDevengo").append(fila);
                    });
                    var table = $('#DatosDevengo');
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                }
            }
        });
    }

    // Detectar la tecla Enter en cualquier input del formulario
    $('#bscDesc, #bscMonto, #bscContrato, #bscUnidad, #bscFecha').keypress(function (e) {
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

    $(document).on('click', '.buscar', function (e) {
        var descripcion = $('#bscDesc').val();
        var monto = $('#bscMonto').val();
        var contrato = $('#bscContrato').val();
        var unidad = $('#bscUnidad').val();
        var fecha = $('#bscFecha').val();
        console.log(descripcion, monto, contrato, unidad, fecha);
        $.ajax({
            url: 'funciones/devengoPost.php',
            type: 'POST',
            data: {
                'bscDesc': descripcion,
                'bscMonto': monto,
                'bscContrato': contrato,
                'bscUnidad': unidad,
                'bscFecha': fecha
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                var table = $('#DatosDevengo');
                $("tbody").empty();
                table.animate({ marginTop: '15%', opacity: '0.2' }, "slow");
                if (data.length <= 0) {
                    $("tbody").empty();
                    var fila = "<tr>" +
                        "<td colspan='11'>No se encontraron devengos</td>" +
                        "</tr>";
                    $("tbody").append(fila);
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                } else {
                    $.each(data, function (i, item) {
                        //Creamos una fila con los datos de cada usuario
                        var fila = "<tr>" +
                            "<td>" + item.id + "</td>" +
                            "<td>" + item.proveedor + "</td>" +
                            "<td>" + item.fecha + "</td>" +
                            "<td>" + item.descripcion + "</td>" +
                            "<td>$" + item.monto + "</td>" +
                            "<td> <button type='button' class='btn contr btn-link' data-bs-toggle='modal' data-bs-target='#mostrarModal' data-id='" + item.contratoID + "'>" + item.contrato + "</button> </td>" +
                            "<td>$" + item.saldo + "</td>" +
                            "<td>$" + item.saldoDis + "</td>" +
                            "<td>" + item.unidad + "</td>" +
                            "<td>" + item.created_at + "</td>" +
                            "<td>" + item.updated_at + "</td>" +
                            "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                            "</tr>";
                        $(".DatosDevengo").append(fila);
                    });
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                }
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
        })
    });

    // Función para cargar los datos al iniciar la página
    $(document).ready(function () {
        cargarDatos();
    });

    //Funcion de crear devengo
    $(document).on('click', '.crear', function (e) {
        e.preventDefault();
        var fecha = $('#fechaCrear').val();
        var clave = $('#contratoCrear').val();
        var monto = $('#montoCrear').val();
        var descripcion = $('#descCrear').val();
        var usuario = '<?php echo $_SESSION['id'] ?>';
        monto = parseFloat(monto);
        var objfecha = new Date(fecha);
        if (clave == "" || descripcion == "" || isNaN(monto) || usuario == "" || isNaN(objfecha.getTime())) {
            Swal.fire({
                title: 'Advertecia!',
                text: 'Favor de llenar todos los campos',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }
        var now = new Date;
        if (objfecha > now) {
            Swal.fire({
                title: 'Advertecia!',
                text: 'La fecha no puede ser superior a la fecha actual',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }
        $.ajax({
            url: 'funciones/devengoPost.php',
            type: 'POST',
            data: {
                'fecha': fecha,
                'clave': clave,
                'monto': monto,
                'descripcion': descripcion,
                'usuario': usuario
            },
            dataType: 'JSON',
            success: function (data) {
                var table = $('#DatosDevengo');
                table.animate({ marginTop: '15%', opacity: '0.2' }, "slow");
                console.log(data);
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'El devengo ha sido creado',
                    icon: 'success'
                });
                table.animate({ opacity: '1', marginTop: '0' }, "slow");
                cargarDatos();
                $('#fechaCrear').val("");
                $('#descCrear').val("");
                $('#montoCrear').val("");
                $('#contratoCrear').val(0);
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
            }
        })
    });

    $(document).on('click', '.contr', function () {
        var id = $(this).data("id");
        console.log(id);
        $.ajax({
            url: 'funciones/devengoPost.php',
            type: 'POST',
            data: { 'contr': id },
            success: function (data) {
                $('#contratoMostrar').html(data);
            },
            error: function () {
                $('#contratoMostrar').html('<div>Error, no se encontro nada</div>');
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
        var usuario = '<?php echo $_SESSION['id'] ?>';
        console.log(id, fecha, monto, usuario, clave);
        var objfecha = new Date(fecha);
        var now = new Date;
        if (objfecha > now) {
            Swal.fire({
                title: 'Advertecia!',
                text: 'La fecha no puede ser superior a la fecha actual',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }
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
                var table = $('#DatosDevengo');
                table.animate({ marginTop: '15%', opacity: '0.2' }, "slow");
                console.log(data);
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'Los cambios han sido guardados',
                    icon: 'success'
                });
                table.animate({ opacity: '1', marginTop: '0' }, "slow");
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