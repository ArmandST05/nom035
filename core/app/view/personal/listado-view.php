<?php 

$departamentos = DepartamentoData::getAll();
$allPersonal = PersonalData::getAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        ul {
            list-style-type: none;
        }
        ul li {
            margin-top: 5px;
        }

    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <!-- Columna del texto -->
            <div class="col-md-8">
                <h4>Listado Personal</h4>
                <p>
                    Indicaciones: En este módulo podrá agregar, eliminar y editar el personal. 
                    Utilice la opción Carga Masiva para cargar al personal mediante un archivo de Microsoft Excel.
                    Recuerde que al agregar a un personal se le activarán las encuestas seleccionadas del puesto.
                    De igual manera tiene la opción de seleccionar las encuestas a contestar de manera independiente 
                    por personal en la opción Encuestas seleccionadas.
                </p>
            </div>
            <!-- Columna de los botones -->
            <div class="col-md-4">
                <div class="d-flex flex-column gap-2">
                    <ul>
                        <li>
                            <button type="button" class="btn btn-primary" onclick="openModalAddPersonal()">Agregar Personal</button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-secondary">Exportar Excel</button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-success">Carga Masiva</button>
                        </li>
                    </ul>                    
                </div>
            </div>
        </div>
    </div>
    <div class="card" style="width: 90%; margin: auto; margin-top: 20px;">
    
    <div class="card-body">
    <table class="table table-striped table-hover">
    <thead style="background-color: grey; color: white;">
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Departamento / Puesto</th>
            <th>Usuario</th>
            <th>Clave</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (!empty($allPersonal)) {
            $index = 1; // Contador para la columna #
            foreach ($allPersonal as $personal) {
                echo "<tr>";
                echo "<td>{$index}</td>";
                echo "<td>{$personal->nombre}</td>";
                echo "<td>{$personal->id_departamento} / {$personal->id_puesto}</td>";
                echo "<td>{$personal->usuario}</td>";
                echo "<td>{$personal->clave}</td>";
                echo "<td>{$personal->correo}</td>";
                echo "<td>{$personal->telefono}</td>";
                echo "<td>
                        <div class='dropdown'>
                            <button class='btn btn-link' type='button' id='dropdownMenuButton{$index}' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='bi bi-three-dots'></i>
                            </button>
                            <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton{$index}'>
                                <li><a class='dropdown-item' href='#' onclick='editPersonal({$personal->id})'>Editar</a></li>
                                <li><a class='dropdown-item' href='#' onclick='deletePersonal({$personal->id})'>Eliminar</a></li>
                            </ul>
                        </div>
                      </td>";
                echo "</tr>";
                $index++;
            }
        } else {
            echo "<tr><td colspan='8' class='text-center'>No hay datos disponibles</td></tr>";
        }
        ?>
    </tbody>
</table>

    </div>
</div>
    <!-- Modal Nuevo Empleado -->
    <div class="modal fade" id="PersonalModal" tabindex="-1" role="dialog" aria-labelledby="PersonalModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Agregar Nuevo Personal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se colocarán los campos del formulario -->
                    <form id="addPersonal" action="index.php?action=personal/add" method="POST"> 
                        <div class="form-group">
                            <label for="employeeName">Nombre del Personal</label>
                            <input type="text" class="form-control" id="employeeName" placeholder="Ingrese el nombre">
                        </div>
                        <div class="form-group">
                            <label for="employeeEmail">Correo Electrónico</label>
                            <input type="email" class="form-control" id="employeeEmail" name="email" placeholder="Ingrese el correo" required>
                        </div>
                        <div class="form-group">
                            <label for="employeeRole">Puesto</label>
                            <input type="text" class="form-control" id="employeeRole" placeholder="Ingrese el puesto">
                        </div>
                        <div class="form-group">
                            <label for="employeeDepartment">Departamento</label>
                            <select class="form-control" id="employeeDepartment" name="id_departamento" required>
                                <option value="">Seleccione un departamento</option>
                                <?php 
                                    if(!empty ($departamentos)){
                                        foreach($departamentos as $departamento){
                                            echo "<option value='{$departamento->idDepartamento}'>{$departamento->nombre}</option>";

                                        }
                                    }

                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                        <label for="employeeDate">Fecha de Alta</label>
                        <input type="date" class="form-control" id="employeeDate" name="fecha_alta" required>
                    </div>
                    <div class="form-group">
                        <label for="employeePhone">Teléfono</label>
                        <input type="text" class="form-control" id="employeePhone" name="phone" placeholder="Ingrese el teléfono (Opcional)">
                    </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="savePersonalBtn" class="btn btn-primary" onclick="addPersonal()">Guardar</button>
                    </div>
            </div>
        </div>
    </div>   
    
    
    <!-- Modal Editar Personal -->
<div class="modal fade" id="EditPersonalModal" tabindex="-1" role="dialog" aria-labelledby="EditPersonalModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">Editar Personal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para editar el personal -->
                <form id="editPersonalForm">
                    <div class="form-group">
                        <label for="editEmployeeName">Nombre del Personal</label>
                        <input type="text" class="form-control" id="editEmployeeName" placeholder="Ingrese el nombre">
                    </div>
                    <div class="form-group">
                        <label for="editEmployeeEmail">Correo Electrónico</label>
                        <input type="email" class="form-control" id="editEmployeeEmail" placeholder="Ingrese el correo">
                    </div>
                    <div class="form-group">
                        <label for="editEmployeeRole">Puesto</label>
                        <input type="text" class="form-control" id="editEmployeeRole" placeholder="Ingrese el puesto">
                    </div>
                    <div class="form-group">
                        <label for="editEmployeeDepartment">Departamento</label>
                        <select class="form-control" id="editEmployeeDepartment">
                            <option value="">Seleccione un departamento</option>
                            <?php
                            foreach ($departamentos as $departamento) {
                                echo "<option value='{$departamento->idDepartamento}'>{$departamento->nombre}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editEmployeeDate">Fecha de Alta</label>
                        <input type="date" class="form-control" id="editEmployeeDate">
                    </div>
                    <div class="form-group">
                        <label for="editEmployeePhone">Teléfono</label>
                        <input type="text" class="form-control" id="editEmployeePhone" placeholder="Ingrese el teléfono (Opcional)">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="saveEditPersonalBtn" class="btn btn-primary" onclick="updatePersonal()">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
var editingPersonalId = null;

// Función para abrir el modal de edición
function editPersonal(personalId) {
    editingPersonalId = personalId;  // Guardamos el ID del personal a editar

    $.ajax({
    url: "./?action=personal/get&id=" + personalId, // Verifica que el ID se esté pasando correctamente
    type: 'GET',
    success: function(response) {
        console.log(response);  // Verifica la respuesta del servidor en la consola

        if (response.error) {
            alert(response.error);  // Si hay un error, muéstralo
        } else {
            // Cargar los datos en el formulario
            $('#editEmployeeName').val(response.nombre);
            $('#editEmployeeEmail').val(response.correo);
            $('#editEmployeeRole').val(response.puesto);
            $('#editEmployeeDepartment').val(response.id_departamento);
            $('#editEmployeeDate').val(response.fecha_alta);
            $('#editEmployeePhone').val(response.telefono);
            $('#EditPersonalModal').modal('show');
        }
    },
    error: function(xhr, status, error) {
        console.log(xhr.responseText);  // Para ver el error en la consola
        alert("Hubo un error al cargar los datos del personal.");
    }
});
}

// Función para actualizar el personal
function updatePersonal() {
    // Recopilamos los datos del formulario
    var updatedPersonalData = {
        "personalId": editingPersonalId,
        "employeeName": $('#editEmployeeName').val(),
        "employeeEmail": $('#editEmployeeEmail').val(),
        "employeeRole": $('#editEmployeeRole').val(),
        "employeeDepartment": $('#editEmployeeDepartment').val(),
        "employeeDate": $('#editEmployeeDate').val(),
        "employeePhone": $('#editEmployeePhone').val()
    };

    // Realizamos la solicitud AJAX para actualizar el personal
    $.ajax({
        url: "./?action=personal/update", // Cambia la URL al endpoint de actualización
        type: 'POST',
        data: updatedPersonalData,
        success: function(response) {
            if (response.success) {
                // Si la actualización es exitosa, mostramos un mensaje y actualizamos la lista
                alert("Personal actualizado correctamente.");
                window.location.reload();  // Recarga la página para reflejar los cambios
            } else {
                alert("Error al actualizar los datos del personal.");
            }
        },
        error: function() {
            alert("Hubo un error al actualizar los datos.");
        }
    });
}


        function openModalAddPersonal() {
            // Configura el contenido dinámico del modal si es necesario.
            $('#PersonalModal').modal('show'); // Muestra el modal.
        }
        var newPersonalId = null;
        function addPersonal() {
    if (!newPersonalId) { // Verifica si el empleado no ha sido agregado
        // Prepara los datos del formulario
        var personalData = {
    "employeeName": $("#employeeName").val(),
    "employeeEmail": $("#employeeEmail").val(),
    "employeeRole": $("#employeeRole").val(),
    "employeeDepartment": $("#employeeDepartment").val(),
    "employeeDate": $("#employeeDate").val(),
    "employeePhone": $("#employeePhone").val()
};



        // Realiza la solicitud AJAX
        $.ajax({
            url: "./?action=personal/add", // Cambia a la URL correspondiente de tu backend
            type: 'POST',
            data: personalData,
            success: function(response) {
                // Asigna el ID del nuevo personal
                newPersonalId = response;

                // Muestra un mensaje de éxito y redirecciona
                alert("Personal agregado correctamente.");
                window.location = "index.php?view=personal/listado";
            },
            error: function() {
                alert("Ha ocurrido un error al almacenar los datos del personal.");
            }
        });
    } else {
        // Si el personal ya existe
        alert("Este personal ya existe.");
    }
}



    </script>
</body>
</html>
