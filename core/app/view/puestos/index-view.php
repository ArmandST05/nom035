<?php

$encuestas = EncuestaData::getAll();
$departamentos = DepartamentoData::getAll();
$allPuestos = PuestoData::getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>puestos</title>
    <link rel="stylesheet" href="path_to_bootstrap.css"> <!-- Asegúrate de que la hoja de estilos de Bootstrap esté incluida -->
</head>
<body>
    <div class="container mt-4"> <!-- Asegúrate de usar la clase container para que todo quede dentro del contenedor de Bootstrap -->
        <div class="row">
            <div class="col-md-8">
                <h4>Puestos</h4>
                <p>
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Suscipit, aliquam? In consequuntur fugiat perspiciatis cum? Beatae, iure minima cumque velit soluta nisi tenetur, quis repellendus culpa accusamus ab eius necessitatibus est veritatis reiciendis debitis fugit quibusdam et quo doloribus ut expedita illum! Amet aut corporis harum eum, iste excepturi ipsa.
                </p>
            </div>
            <div class="col-md-4">
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn btn-primary" onclick="openModalAddPuesto()">Nuevo Puesto</button>
                </div>
            </div>
        </div>

        <!-- Card with table -->
<<<<<<< HEAD
        <div class="card" style="width: 95%;  margin-top: 20px; ">
    <div class="card-body">
        <table id="lookup" class="table table-striped table-hover">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Departamento</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- El cuerpo se gestionará dinámicamente por DataTables -->
            </tbody>
        </table>
=======
        <div class="card mt-4" style="width: 90%; margin: auto; margin-top: 20px;"> <!-- Agregar mt-4 para dar un margen superior -->
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead style="background-color: grey; color: white;">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Departamento</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(!empty($allPuestos)){
                                $index= 1;
                                foreach ($allPuestos as $puesto) {
                                    echo "<tr>";
                                    echo "<td>{$index}</td>";
                                    echo "<td>{$puesto->nombre}</td>";
                                    echo "<td>{$puesto->id_departamento}</td>";
                                    echo "<td>
                                            <button class='btn btn-primary btn-sm' onclick='editPersonal({$puesto->id})'>Editar</button>
                                            <button class='btn btn-danger btn-sm' onclick='deletePersonal({$puesto->id})'>Eliminar</button>
                                          </td>";
                                    echo "</tr>";
                                    $index++;
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

>>>>>>> ada47f10f7c2533aea1589bde8b837e80c29e9a2
    </div>
</div>

    <!-- Modal for adding new puesto -->
    <div class="modal fade" id="PuestoModal" tabindex="-1" role="dialog" aria-labelledby="PuestoModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Agregar Nuevo Puesto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se colocarán los campos del formulario -->
                    <form id="addPuesto" action="index.php?action=puesto/add" method="POST"> 
                        <div class="form-group">
                            <label for="roleName">Nombre del Puesto</label>
                            <input type="text" class="form-control" id="roleName" placeholder="Ingrese el nombre">
                        </div>
                        <div class="form-group">
                            <label for="roleDepartment">Departamento</label>
                            <select class="form-control" id="id_departamento" name="id_departamento" required>
                                <option value="">Seleccione un departamento</option>
                                <?php 
                                    if(!empty($departamentos)){
                                        foreach($departamentos as $departamento){
                                            echo "<option value='{$departamento->idDepartamento}'>{$departamento->nombre}</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="roleEncuesta">Encuesta</label>
                            <select class="form-control" name="id_encuesta" id="id_encuesta">
                                <option value="">Seleccione una encuesta</option>
                                <?php 
                                    if(!empty($encuestas)){
                                        foreach($encuestas as $encuesta){
                                            echo "<option value='{$encuesta->id}'>{$encuesta->descripcion}</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="savePersonalBtn" class="btn btn-primary" onclick="addPuesto()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
<!-- Modal for editing puesto -->
<div class="modal fade" id="EditPuestoModal" tabindex="-1" role="dialog" aria-labelledby="EditPuestoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditPuestoModalLabel">Editar Puesto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Campos del formulario -->
                <form id="editPuestoForm" action="index.php?action=puestos/update" method="POST">
                    <div class="form-group">
                        <label for="editRoleName">Nombre del Puesto</label>
                        <input type="text" class="form-control" id="editRoleName" name="nombre" placeholder="Ingrese el nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="editRoleDepartment">Departamento</label>
                        <select class="form-control" id="editRoleDepartment" name="id_departamento" required>
                            <option value="">Seleccione un departamento</option>
                            <?php 
                                if (!empty($departamentos)) {
                                    foreach ($departamentos as $departamento) {
                                        echo "<option value='{$departamento->idDepartamento}'>{$departamento->nombre}</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editRoleEncuesta">Encuesta</label>
                        <select class="form-control" id="editRoleEncuesta" name="id_encuesta">
                            <option value="">Seleccione una encuesta</option>
                            <?php 
                                if (!empty($encuestas)) {
                                    foreach ($encuestas as $encuesta) {
                                        echo "<option value='{$encuesta->id}'>{$encuesta->descripcion}</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="updatePuestoBtn" class="btn btn-primary" onclick="updatePuesto()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

    <script>
    $(document).on('click', '.dropdown-toggle', function (event) {
    event.preventDefault(); // Evitar que el enlace redirija
    var menuId = $(this).attr('id').replace('dropdownMenuButton', 'dropdownMenu'); // Obtener el ID del menú correspondiente
    var $menu = $('#' + menuId);

    // Cerrar cualquier otro menú abierto
    $('.dropdown-menu').not($menu).hide();

    // Alternar la visibilidad del menú correspondiente
    $menu.toggle();
});

// Cerrar el menú si se hace clic fuera de él
$(document).on('click', function (event) {
    if (!$(event.target).closest('.dropdown').length) {
        $('.dropdown-menu').hide(); // Ocultar todos los menús desplegables
    }
});

           $(document).ready(function () {
    var dataTable = $('#lookup').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        "ordering": false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "./?action=puestos/get-all", // Ruta al endpoint
            type: "POST",
            error: function () {
                $(".lookup-error").html("");
                $("#lookup").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se han encontrado datos.</th></tr></tbody>');
                $("#lookup_processing").css("display", "none");
            }
        },
        "responsive": true,
        "scrollX": true
    });
});
function editPuesto(id) {
    $.ajax({
        url: "./?action=puestos/get&id=" + id,
        type: "GET",
        success: function(response) {
            try {
                // Si ya estás recibiendo los datos correctamente
                let data = response;

                // Asigna los valores a los campos del modal
                $('#editPuestoName').val(data.nombre);
                $('#editPuestoDepartment').val(data.id_departamento);

                // Muestra el modal
                $('#EditPuestoModal').modal('show');
            } catch (e) {
                console.error("Error al procesar los datos del puesto:", e);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", xhr.responseText);
            alert("Hubo un error al obtener los datos del puesto.");
        }
    });
}


function updatePuesto() {
    // Recopilamos los datos del formulario
    var updatedPositionData = {
        
        "editRoleName": $('#editRoleName').val(),
        "editRoleDepartment": $('#editRoleDepartment').val()
    };

    // Realizamos la solicitud AJAX para actualizar el puesto
    $.ajax({
        url: "./?action=puestos/update", // Endpoint para actualizar los datos del puesto
        type: 'POST',
        data: updatedPositionData,
        success: function(response) {
            if (response.success) {
                // Si la actualización es exitosa, mostramos un mensaje y actualizamos la lista
                alert("Puesto actualizado correctamente.");
                window.location.reload(); // Recarga la página para reflejar los cambios
            } else {
                alert("Error al actualizar los datos del puesto.");
            }
        },
        error: function() {
            alert("Hubo un error al actualizar los datos del puesto.");
        }
    });
}

=======

    <script>
>>>>>>> ada47f10f7c2533aea1589bde8b837e80c29e9a2
        function openModalAddPuesto() {
            $('#PuestoModal').modal('show');
        }

        var newPuestoId = null;

        function addPuesto() {
            if (!newPuestoId) {
                var puestoData = {
                    "roleName": $("#roleName").val(),
                    "id_departamento": $("#id_departamento").val(),
                    "id_encuesta": $("#id_encuesta").val()
                };
                console.log(puestoData);
                $.ajax({
                    url: "./?action=puestos/add",
                    type: 'POST',
                    data: puestoData,
                    success: function(response) {
                        newPuestoId = response;
                        alert("Puesto agregado correctamente");
                        window.location = "index.php?view=puestos/index";
                    },
                    error: function() {
                        alert("Algo salió mal");
                    }
                });
            } else {
                alert("Este personal ya existe.");
            }
        }
<<<<<<< HEAD
        function deletePuesto(puestoId, puestoName) {
    const swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: true
    });

    swalWithBootstrapButtons.fire({
        title: '¿Estás seguro de eliminar el puesto ' + puestoName + '?',
        text: "¡No podrás revertirlo!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: '¡No, cancelarlo!',
        reverseButtons: true
    }).then((result) => {
        if (result.value === true) {
            // Redirigir a la acción de eliminación de puesto
            window.location.href = "index.php?action=puestos/delete&id=" + puestoId;
        }
    });
}


=======
>>>>>>> ada47f10f7c2533aea1589bde8b837e80c29e9a2
    </script>

</body>
</html>
