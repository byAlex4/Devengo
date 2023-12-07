<?php include_once("./template/header.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<script>document.title = "Contratos | Devengo";</script>
<main class="bodymain">
    <div class="mt-3">
        <h1>Página de contratos</h1>
        <div class="col-md mb-3" style="text-align: right;">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#crearModal"
                style="background-color: #2a8f60; border-color:#8bc6a8;">
                Crear contrato
            </button>
        </div>
        <div style="overflow-x: auto; display: grid">
            <form action="post">
                <div class="input-group mb-4">
                    <span class=" input-group-text">Clave</span>
                    <input type="text" class="form-control" id="bscClave" style="min-width: 100px; max-width: 130px;">
                    <span class=" input-group-text">Proveedor</span>
                    <input type="text" class="form-control" id="bscProv" style="min-width: 100px; ">
                    <span class="input-group-text">Descripcion</span>
                    <input type="text" class="form-control" id="bscDesc" style="min-width: 100px;">
                    <span class="input-group-text">Monto</span>
                    <input type="text" class="form-control" id="bscMonto" style="min-width: 100px;">
                    <span class="input-group-text">Mes</span>
                    <input type="month" class="form-control" id="bscFecha" style="min-width: 100px; ">
                    <button class="btn btn-outline-secondary buscar" name="submit" type="button">Buscar</button>
                </div>
            </form>
            <table class="table table-hover" id="tablaContratos"
                style="background-color: #e4f7e8; margin-top: 15%; opacity: 0.2;">
                <thead class="thead-primary" style="background-color: #a1d6aa; width: 100%;">
                    <tr>
                        <th>ID</th>
                        <th>Clave</th>
                        <th>Cuenta</th>
                        <th></th>
                        <th>Proveedor</th>
                        <th>Descripcipón</th>
                        <th>Monto Maximo</th>
                        <th>Monton Minimo</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Finalización</th>
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
            <div class="modal-content">
                <?php include "modals/contratoCrear.php"; ?>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editarModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <?php include "modals/contratoEdit.php"; ?>
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
        // Hacemos una petición GET
        $.ajax({
            url: "funciones/contratoDatos.php",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.length <= 0) {
                    var fila = "<tr>" +
                        "<td colspan='11'>No se encontraron contratos</td>" +
                        "</tr>";
                    $("tbody").append(fila);
                    var table = $('#tablaContratos');
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                } else {
                    $.each(data, function (i, item) {
                        // Creamos una fila con los datos de cada conjtrato
                        var fila = "<tr>" +
                            "<td>" + item.id + "</td>" +
                            "<td>" + item.clave + "</td>" +
                            "<td>" + item.cuenta + "</td>" +
                            "<td>" + item.cuentaDes + "</td>" +
                            "<td>" + item.proveedor + "</td>" +
                            "<td>" + item.descripcion + "</td>" +
                            "<td>$" + item.mont_max + "</td>" +
                            "<td>$" + item.mont_min + "</td>" +
                            "<td>" + item.fecha_in + "</td>" +
                            "<td>" + item.fecha_fin + "</td>" +
                            "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                            "</tr>";
                        $("tbody").append(fila);
                    });
                    var table = $('#tablaContratos');
                    table.animate({ opacity: '1', marginTop: '0' }, "slow")
                }
            }
        });
    }

    // Función para cargar los datos al iniciar la página
    $(document).ready(function () {
        cargarDatos();
    });

    // Detectar la tecla Enter en cualquier input del formulario
    $('#bscClave, #bscDesc, #bscMonto, #bscFecha, #bscProv').keypress(function (e) {
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
        var clave = $('#bscClave').val();
        var descripcion = $('#bscDesc').val();
        var monto = $('#bscMonto').val();
        var fecha = $('#bscFecha').val();
        var proveedor = $('#bscProv').val();
        console.log(clave, proveedor, descripcion, monto, fecha);
        $.ajax({
            url: 'funciones/contratoPost.php',
            type: 'POST',
            data: {
                'bscClave': clave,
                'bscDesc': descripcion,
                'bscMonto': monto,
                'bscFecha': fecha,
                'bscProveedor': proveedor
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                var table = $('#tablaContratos');
                $("tbody").empty();
                table.animate({ marginTop: '15%', opacity: '0.2' }, "slow");
                if (data.length <= 0) {
                    $("tbody").empty();
                    var fila = "<tr>" +
                        "<td colspan='11'>No se encontraron contratos</td>" +
                        "</tr>";
                    $("tbody").append(fila);
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                } else {
                    $.each(data, function (i, item) {
                        // Creamos una fila con los datos de cada conjtrato
                        var fila = "<tr>" +
                            "<td>" + item.id + "</td>" +
                            "<td>" + item.clave + "</td>" +
                            "<td>" + item.cuenta + "</td>" +
                            "<td>" + item.cuentaDes + "</td>" +
                            "<td>" + item.proveedor + "</td>" +
                            "<td>" + item.descripcion + "</td>" +
                            "<td>$" + item.mont_max + "</td>" +
                            "<td>$" + item.mont_min + "</td>" +
                            "<td>" + item.fecha_in + "</td>" +
                            "<td>" + item.fecha_fin + "</td>" +
                            "<td><button type='button' class='btn shw' data-bs-toggle='modal' data-bs-target='#editarModal' data-id='" + item.id + "'>✏️</button></td>" +
                            "</tr>";
                        $("tbody").append(fila);
                    });
                    table.animate({ opacity: '1', marginTop: '0' }, "slow");
                }
            }
        })
    });
    //Funcion de crear contratos
    $(document).on('click', '.crear', function (e) {
        e.preventDefault();
        var cuenta = $('#cuentaCrear').val();
        var clave = $('#claveCrear').val();
        var descripcion = $('#desCrear').val();
        var mont_max = $('#maxCrear').val();
        var mont_min = $('#minCrear').val();
        var fecha_in = $('#iniCrear').val();
        var fecha_fin = $('#finCrear').val();
        var proveedor = $('#provCrear').val();
        mont_min = parseFloat(mont_min);
        mont_max = parseFloat(mont_max);
        var objfecha_in = new Date(fecha_in);
        var objfecha_fin = new Date(fecha_fin);
        if (cuenta == "" || clave == "" || descripcion == "" || isNaN(mont_max) || isNaN(mont_min) ||
            isNaN(objfecha_in.getTime()) || isNaN(objfecha_in.getTime()) || proveedor == "") {
            Swal.fire({
                title: 'Advertecia!',
                text: 'Favor de llenar todos los campos',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }
        if (mont_min > mont_max) {
            Swal.fire({
                title: 'Advertecia!',
                text: 'El monto mínimo no puede ser mayor al monto máximo',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }

        if (fecha_fin < fecha_in) {
            Swal.fire({
                title: 'Advertecia!',
                text: 'La fecha final no puede ser antes a la fecha de inicio',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }
        $.ajax({
            url: 'funciones/contratoPost.php',
            type: 'POST',
            data: {
                'cuenta': cuenta,
                'clave': clave,
                'descripcion': descripcion,
                'mont_max': mont_max,
                'mont_min': mont_min,
                'fecha_in': fecha_in,
                'fecha_fin': fecha_fin,
                'proveedor': proveedor
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'El contrato ha sido creado',
                    icon: 'success'
                });
                var table = $('#tablaContratos');
                table.animate({ opacity: '0', marginTop: '15%' }, "slow");
                cargarDatos();
                $('#claveCrear').val("");
                $('#desCrear').val("");
                $('#maxCrear').val("");
                $('#minCrear').val("");
                $('#iniCrear').val("");
                $('#finCrear').val("");
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

    //Funcion para mostrar datos del contrato seleccionado para editar
    $(document).on('click', '.shw', function () {
        var id = $(this).data("id");
        console.log(id);
        $.ajax({
            url: 'funciones/contratoPost.php',
            type: 'POST',
            data: { 'shw': id },
            dataType: 'JSON',
            success: function (data) {
                console.log(data)
                $('#formEdit').val(data.id);
                $('#idEdit').val(data.id);
                $('#claveEdit').val(data.clave);
                $('#desEdit').val(data.descripcion);
                $('#maxEdit').val(data.mont_max);
                $('#minEdit').val(data.mont_min);
                $('#iniEdit').val(data.fecha_in);
                $('#finEdit').val(data.fecha_fin);
                $('#provEdit').val(data.proveedor);
            }
        })
    });

    $(document).on('click', '.editar', function (e) {
        e.preventDefault();
        var id = $('#idEdit').val();
        var clave = $('#claveEdit').val();
        var descripcion = $('#desEdit').val();
        var mont_max = $('#maxEdit').val();
        var mont_min = $('#minEdit').val();
        var fecha_in = $('#iniEdit').val();
        var fecha_fin = $('#finEdit').val();
        var proveedor = $('#provEdit').val();

        mont_min = parseFloat(mont_min);
        mont_max = parseFloat(mont_max);
        var objfecha_in = new Date(fecha_in);
        var objfecha_fin = new Date(fecha_fin);
        if (clave == "" || descripcion == "" || isNaN(mont_max) || isNaN(mont_min) ||
            isNaN(objfecha_in.getTime()) || isNaN(objfecha_in.getTime()) || proveedor == "") {
            Swal.fire({
                title: 'Advertecia!',
                text: 'Favor de llenar todos los campos',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }
        if (mont_min > mont_max) {
            Swal.fire({
                title: 'Advertecia!',
                text: 'El monto mínimo no puede ser mayor al monto máximo',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }

        if (fecha_fin < fecha_in) {
            Swal.fire({
                title: 'Advertecia!',
                text: 'La fecha final no puede ser antes a la fecha de inicio',
                icon: 'warning',
                confirmButtonText: 'Cerrar'
            });
            // Cancelar el envío de la solicitud
            return;
        }

        $.ajax({
            url: 'funciones/contratoPost.php',
            type: 'POST',
            data: {
                'editar': id,
                'clave': clave,
                'descripcion': descripcion,
                'mont_max': mont_max,
                'mont_min': mont_min,
                'fecha_in': fecha_in,
                'fecha_fin': fecha_fin,
                'proveedor': proveedor
            },
            dataType: 'JSON',
            success: function (data) {
                Swal.fire({
                    title: '¡Hecho!',
                    text: 'Los cambios han sido guardados',
                    icon: 'success'
                });
                console.log(data);
                var table = $('#tablaContratos');
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