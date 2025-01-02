
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
    <title>Puestos</title>
    <link rel="stylesheet" href="path_to_bootstrap.css"> <!-- Asegúrate de que la hoja de estilos de Bootstrap esté incluida -->
</head>
<style>
/* Estilo del botón de los tres puntos */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.dots {
    font-size: 24px;
}

/* Estilo del menú desplegable */
.dropdown-menu {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 120px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    z-index: 1;
    padding: 10px 0;
    border-radius: 5px;
}

.dropdown-item {
    padding: 8px 16px;
    text-decoration: none;
    color: #333;
    display: block;
    font-size: 14px;
}

.dropdown-item:hover {
    background-color: #f1f1f1;
}

/* Mostrar el menú cuando el botón es clickeado */
.dropdown.show .dropdown-menu {
    display: block;
}

thead {
    background-color: black;
    color: white;
}

</style>
<body>
    <div class="container mt-4">
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
        <div class="card" style="width: 95%;  margin-top: 20px; ">
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
                        <?php foreach ($allPuestos as $puesto): ?>
                            <tr>
                                <td><?php echo $puesto->id; ?></td>
                                <td><?php echo $puesto->nombre; ?></td>
                                <td><?php echo $puesto->departamento; ?></td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton<?php echo $puesto->id; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?php echo $puesto->id; ?>">
                                            <li><a class="dropdown-item" href="#" onclick="editPuesto(<?php echo $puesto->id; ?>)">Editar</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="deletePuesto(<?php echo $puesto->id; ?>, '<?php echo $puesto->nombre; ?>')">Eliminar</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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

    <script>
        $(document).on('click', '.dropdown-toggle', function (event) {
            event.preventDefault();
            var menuId = $(this).attr('id').replace('dropdownMenuButton', 'dropdownMenu');
            var $menu = $('#' + menuId);

            // Cerrar cualquier otro menú abierto
            $('.dropdown-menu').not($menu).hide();

            // Alternar la visibilidad del menú correspondiente
            $menu.toggle();
        });

        $(document).on('click', function (event) {
            if (!$(event.target).closest('.dropdown').length) {
                $('.dropdown-menu').hide();
            }
        });

        function editPuesto(id) {
            $.ajax({
                url: "./?action=puestos/get&id=" + id,
                type: "GET",
                success: function(response) {
                    try {
                        let data = response;
                        $('#editPuestoName').val(data.nombre);
                        $('#editPuestoDepartment').val(data.id_departamento);
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
            var updatedPositionData = {
                "editRoleName": $('#editRoleName').val(),
                "editRoleDepartment": $('#editRoleDepartment').val()
            };

            $.ajax({
                url: "./?action=puestos/update",
                type: 'POST',
                data: updatedPositionData,
                success: function(response) {
                    if (response.success) {
                        alert("Puesto actualizado correctamente.");
                        window.location.reload();
                    } else {
                        alert("Error al actualizar los datos del puesto.");
                    }
                },
                error: function() {
                    alert("Hubo un error al actualizar los datos del puesto.");
                }
            });
        }

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

       
    </script>

</body>
</html>
